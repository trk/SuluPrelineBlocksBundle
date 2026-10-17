<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Renderer;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Renderer\PrelineBlockCollectionRenderer;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

final class PrelineBlockCollectionRendererTest extends TestCase
{
    public function testRenderCollectionReturnsEmptyStringForEmptyBlocks(): void
    {
        $twig = new Environment(new ArrayLoader([]));
        $collectionRenderer = new PrelineBlockCollectionRenderer($twig);

        $this->assertSame('', $collectionRenderer->renderCollection([]));
    }

    public function testRenderCollectionDelegatesToPrelineBlocksRendererTemplateWithContext(): void
    {
        $loader = new ArrayLoader([
            '@SuluPrelineBlocks/includes/blocks_renderer.html.twig' => '{% for b in blocks %}<div data-wrap="{{ (disable_auto_section|default(false)) ? "no" : "yes" }}">{{ b.type }}:{{ b.title }}</div>{% endfor %}',
        ]);
        $twig = new Environment($loader);
        $collectionRenderer = new PrelineBlockCollectionRenderer($twig);

        $blocks = [
            ['type' => 'headline', 'title' => 'Intro'],
            ['type' => 'text', 'title' => 'Paragraph'],
        ];

        $html = $collectionRenderer->renderCollection($blocks, ['disable_auto_section' => true]);

        $this->assertSame(
            '<div data-wrap="no">headline:Intro</div><div data-wrap="no">text:Paragraph</div>',
            $html,
        );
    }

    public function testRenderCollectionMergesReusableBlocksAndBuildsExpectedSectionStructure(): void
    {
        $templatePath = \dirname(__DIR__, 2) . '/templates/includes/blocks_renderer.html.twig';
        $templateSource = (string) \file_get_contents($templatePath);

        // Replace <twig:Section ... /> component tag with a verifiable <section><div class="row"><div class="column"> marker for pure Twig unit testing
        $normalizedTemplate = (string) \preg_replace(
            '/<twig:Section\s+:props="[^"]*"\s+:rows="\[\{\s*layout:\s*\'12-col\',\s*gap:\s*\'md\',\s*columns:\s*\[\{\s*col_span:\s*\'12\',\s*blocks:\s*entry\.blocks\s*\}\]\s*\}\]"\s+:context="outer_context"\s*\/>/s',
            '<section class="auto-section"><div class="row"><div class="column">{% for b in entry.blocks %}{{ sulu_block_render(b, outer_context) }}{% endfor %}</div></div></section>',
            $templateSource,
        );

        $loader = new ArrayLoader([
            '@SuluPrelineBlocks/includes/blocks_renderer.html.twig' => $normalizedTemplate,
            '@SuluPrelineBlocks/blocks/headline.html.twig' => '[headline:{{ block.title }}]',
            '@SuluPrelineBlocks/blocks/text.html.twig' => '[text:{{ block.title }}]',
            '@SuluPrelineBlocks/blocks/image.html.twig' => '[image:{{ block.title }}]',
            '@SuluPrelineBlocks/blocks/button.html.twig' => '[button:{{ block.title }}]',
            '@SuluPrelineBlocks/blocks/section.html.twig' => '<section class="explicit-section"><div class="row"><div class="column">{% for r in block.rows %}{% for c in r.columns %}{% for b in c.blocks %}{{ sulu_block_render(b, _context) }}{% endfor %}{% endfor %}{% endfor %}</div></div></section>',
        ]);

        $twig = new Environment($loader);
        $pipeline = new \Totoglu\SuluBlockSuiteBundle\Transformer\BlockTransformPipeline([
            new \Totoglu\SuluBlockSuiteBundle\Transformer\ReusableBlockTransformer(
                null,
                null,
                [new \Totoglu\SuluPrelineBlocksBundle\Transformer\SectionFlattener()],
            ),
            new \Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer(),
        ]);
        $blockRenderer = new \Totoglu\SuluBlockSuiteBundle\Renderer\BlockRenderer(
            $twig,
            $pipeline,
            ['@SuluPrelineBlocks/blocks' => 200],
        );
        $collectionRenderer = new PrelineBlockCollectionRenderer($twig);
        $blockRenderer->setCollectionRenderer($collectionRenderer);
        $twig->addExtension(new \Totoglu\SuluBlockSuiteBundle\Twig\DynamicContentTwigExtension($pipeline, $blockRenderer));

        // Exact sequence:
        // headline, text, image,
        // section > row > column > [image, headline, text],
        // reusable [headline, text],
        // image, text,
        // reusable [section > row > column > image, text, button]
        $pageBlocks = [
            ['type' => 'headline', 'title' => 'P1'],
            ['type' => 'text', 'title' => 'P2'],
            ['type' => 'image', 'title' => 'P3'],
            [
                'type' => 'section',
                'rows' => [
                    [
                        'columns' => [
                            [
                                'blocks' => [
                                    ['type' => 'image', 'title' => 'S1'],
                                    ['type' => 'headline', 'title' => 'S2'],
                                    ['type' => 'text', 'title' => 'S3'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'reusable',
                'snippet' => [
                    'blocks' => [
                        ['type' => 'headline', 'title' => 'R1-1'],
                        ['type' => 'text', 'title' => 'R1-2'],
                    ],
                ],
            ],
            ['type' => 'image', 'title' => 'P4'],
            ['type' => 'text', 'title' => 'P5'],
            [
                'type' => 'reusable',
                'snippet' => [
                    'blocks' => [
                        [
                            'type' => 'section',
                            'rows' => [
                                [
                                    'columns' => [
                                        [
                                            'blocks' => [
                                                ['type' => 'image', 'title' => 'R2-1'],
                                                ['type' => 'text', 'title' => 'R2-2'],
                                                ['type' => 'button', 'title' => 'R2-3'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $rendered = \trim($blockRenderer->renderBlocks($pageBlocks));

        // Extract all <section ...>...</section> blocks and ensure zero nested <section> tags
        \preg_match_all('/<section\b[^>]*>(.*?)<\/section>/s', $rendered, $matches);
        $this->assertCount(5, $matches[0]);
        foreach ($matches[1] as $innerSectionHtml) {
            $this->assertStringNotContainsString('<section', $innerSectionHtml);
        }

        $this->assertSame(
            [
                '<section class="auto-section"><div class="row"><div class="column">[headline:P1][text:P2][image:P3]</div></div></section>',
                '<section class="explicit-section"><div class="row"><div class="column">[image:S1][headline:S2][text:S3]</div></div></section>',
                '<section class="auto-section"><div class="row"><div class="column">[headline:R1-1][text:R1-2]</div></div></section>',
                '<section class="auto-section"><div class="row"><div class="column">[image:P4][text:P5]</div></div></section>',
                '<section class="explicit-section"><div class="row"><div class="column">[image:R2-1][text:R2-2][button:R2-3]</div></div></section>',
            ],
            $matches[0],
        );
    }

    public function testLeafBlocksRenderThreeLayerContainerBlockContextInsideSectionHierarchy(): void
    {
        $componentsDir = \dirname(__DIR__, 2) . '/templates/components';
        $templateFiles = \glob($componentsDir . '/*.html.twig');
        self::assertIsArray($templateFiles);
        self::assertNotEmpty($templateFiles);

        foreach ($templateFiles as $file) {
            $content = (string) \file_get_contents($file);
            if ('Section.html.twig' === \basename($file)) {
                self::assertStringContainsString('<section', $content);
            } else {
                self::assertStringNotContainsString(
                    '<section',
                    $content,
                    \sprintf('Template "%s" must not render a <section> tag; only Section.html.twig may render <section>.', \basename($file)),
                );
            }
        }

        $twig = new Environment(new \Twig\Loader\FilesystemLoader($componentsDir), ['autoescape' => false]);
        $twig->addFilter(new \Twig\TwigFilter('trans', static fn (string $message): string => $message));

        $table = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Table();
        $table->assign('header_title', 'Feature');
        $table->assign('items', [['title' => 'Row 1', 'content' => 'Value 1']]);
        $table->mergeProps(['bordered' => true, 'margin_top' => 'md', 'id' => 'pricing-table']);

        $map = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Map();
        $map->assign('location_query', 'Istanbul, Turkey');
        $map->mergeProps(['margin_bottom' => 'sm', 'id' => 'office-map']);

        $list = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\ListBlock();
        $list->assign('items', [['title' => 'Item A', 'content' => 'Detail A']]);
        $list->mergeProps(['id' => 'feature-list']);

        $dl = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\DescriptionList();
        $dl->assign('items', [['title' => 'Term 1', 'content' => 'Desc 1']]);
        $dl->mergeProps(['id' => 'spec-dl']);

        $nav = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Nav();
        $nav->assign('title', 'Docs Nav');
        $nav->assign('items', [['title' => 'Getting Started', 'link' => '/docs/start']]);
        $nav->mergeProps(['id' => 'docs-nav']);

        $subnav = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Subnav();
        $subnav->assign('title', 'Section Tabs');
        $subnav->assign('items', [['title' => 'Overview', 'link' => '/overview', 'is_active' => true]]);
        $subnav->mergeProps(['id' => 'sub-nav']);

        $accordion = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Accordion();
        $accordion->assign('items', [['title' => 'Question 1', 'content' => '<p>Answer 1</p>']]);
        $accordion->mergeProps(['id' => 'faq-accordion']);

        $switcher = new \Totoglu\SuluPrelineBlocksBundle\Twig\Components\Switcher();
        $switcher->assign('items', [['title' => 'Tab 1', 'content' => '<p>Panel 1</p>']]);
        $switcher->mergeProps(['id' => 'feature-switcher']);

        $renderedBlocks = [];
        $escaper = new \Twig\Runtime\EscaperRuntime();
        foreach ([
            ['Table.html.twig', $table],
            ['Map.html.twig', $map],
            ['List.html.twig', $list],
            ['DescriptionList.html.twig', $dl],
            ['Nav.html.twig', $nav],
            ['Subnav.html.twig', $subnav],
            ['Accordion.html.twig', $accordion],
            ['Switcher.html.twig', $switcher],
        ] as [$templateName, $component]) {
            $renderedBlocks[] = \trim($twig->render($templateName, [
                'this' => $component,
                'attributes' => new \Symfony\UX\TwigComponent\ComponentAttributes([], $escaper),
            ]));
        }

        $sectionHtml = '<section class="relative overflow-hidden bg-transparent py-10 sm:py-14">'
            . '<div class="mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8 relative z-10">'
            . '<div class="grid grid-cols-12 gap-6">'
            . '<div class="col-span-12"><div class="space-y-6">'
            . \implode('', $renderedBlocks)
            . '</div></div></div></div></section>';

        self::assertSame(1, \substr_count($sectionHtml, '<section'));
        self::assertMatchesRegularExpression(
            '/<div\s+id="pricing-table" class="mt-6">\s*<div class="w-full">\s*<div class="overflow-x-auto w-full border border-card-line rounded-2xl overflow-hidden shadow-2xs bg-card">\s*<table/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="office-map" class="mb-4">\s*<div class="w-full">\s*<div class="relative w-full rounded-2xl overflow-hidden border border-card-line shadow-xs bg-surface aspect-video">\s*<iframe/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="feature-list">\s*<div class="w-full">\s*<ul class="space-y-2\.5">/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="spec-dl">\s*<div class="w-full">\s*<dl class="divide-y divide-border">/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="docs-nav">\s*<div class="w-full">.*?<nav class="flex flex-col space-y-1 w-full" aria-label="Docs Nav">/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="sub-nav">\s*<div class="w-full">\s*<nav aria-label="Section Tabs">/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="faq-accordion">\s*<div class="w-full">\s*<div\s+class="hs-accordion-group/s',
            $sectionHtml,
        );
        self::assertMatchesRegularExpression(
            '/<div\s+id="feature-switcher">\s*<div class="w-full">\s*<div class="flex flex-col">/s',
            $sectionHtml,
        );
    }
}
