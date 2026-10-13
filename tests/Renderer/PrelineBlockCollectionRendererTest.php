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
            '@SuluPrelineBlocks/includes/blocks_renderer.html.twig' => '{% for b in blocks %}<div data-wrap="{{ (wrap_loose_blocks is defined ? wrap_loose_blocks : true) ? "yes" : "no" }}">{{ b.type }}:{{ b.title }}</div>{% endfor %}',
        ]);
        $twig = new Environment($loader);
        $collectionRenderer = new PrelineBlockCollectionRenderer($twig);

        $blocks = [
            ['type' => 'headline', 'title' => 'Intro'],
            ['type' => 'text', 'title' => 'Paragraph'],
        ];

        $html = $collectionRenderer->renderCollection($blocks, ['wrap_loose_blocks' => false]);

        $this->assertSame(
            '<div data-wrap="no">headline:Intro</div><div data-wrap="no">text:Paragraph</div>',
            $html,
        );
    }
}
