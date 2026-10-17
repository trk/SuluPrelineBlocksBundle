<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Search;

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\FieldMetadata;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\FormMetadata;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\SectionMetadata;
use Sulu\Bundle\AdminBundle\Metadata\FormMetadata\TypedFormMetadata;
use Sulu\Bundle\AdminBundle\Metadata\MetadataProviderInterface;
use Sulu\Bundle\AdminBundle\Metadata\MetadataProviderRegistry;
use Sulu\Content\Domain\Model\DimensionContentInterface;
use Sulu\Snippet\Domain\Model\SnippetDimensionContentInterface;
use Sulu\Snippet\Domain\Model\SnippetInterface;
use Sulu\Snippet\Domain\Repository\SnippetRepositoryInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Totoglu\SuluBlockSuiteBundle\DynamicContent\DynamicContentHydrator;
use Totoglu\SuluBlockSuiteBundle\DynamicContent\PageSourceResolverInterface;
use Totoglu\SuluBlockSuiteBundle\Metadata\BlockTargetFieldMetadataResolver;
use Totoglu\SuluBlockSuiteBundle\Search\BlockSuitePageReindexEnhancer;

final class PrelinePageReindexAndDynamicContentTest extends TestCase
{
    public function testReindexEnhancerExtractsTextFromPrelineSectionRowColumnCardAccordionAndReusableBlocks(): void
    {
        $snippetDimension = $this->createStub(SnippetDimensionContentInterface::class);
        $snippetDimension->method('getLocale')->willReturn('en');
        $snippetDimension->method('getStage')->willReturn(DimensionContentInterface::STAGE_LIVE);
        $snippetDimension->method('getVersion')->willReturn(DimensionContentInterface::CURRENT_VERSION);
        $snippetDimension->method('getTemplateKey')->willReturn('reusable_block');
        $snippetDimension->method('getTemplateData')->willReturn([
            'title' => 'Global Banner Reusable Title',
            'blocks' => [
                [
                    'type' => 'headline',
                    'title' => 'Reusable CTA Headline Text',
                    'content' => '<p>Reusable rich text description</p>',
                ],
            ],
        ]);

        $snippet = $this->createStub(SnippetInterface::class);
        $snippet->method('getDimensionContents')->willReturn(new ArrayCollection([$snippetDimension]));

        $snippetRepository = $this->createMock(SnippetRepositoryInterface::class);
        $snippetRepository->expects(self::once())
            ->method('findOneBy')
            ->with(
                [
                    'uuid' => 'reusable-uuid-001',
                    'locale' => 'en',
                    'stage' => DimensionContentInterface::STAGE_LIVE,
                ],
                [SnippetRepositoryInterface::SELECT_SNIPPET_CONTENT => []],
            )
            ->willReturn($snippet);

        $registry = $this->createPrelineMetadataRegistry();
        $enhancer = new BlockSuitePageReindexEnhancer($snippetRepository, $registry);

        $pageData = [
            'locale' => 'en',
            'templateKey' => 'default',
            'templateData' => [
                'headline' => 'Top Level Display Headline',
                'summary' => 'Top level plain summary text.',
                'content' => '<p>Top level <strong>rich body</strong> content.</p>',
                'image' => ['id' => 42],
                'blocks' => [
                    [
                        'type' => 'section',
                        'rows' => [
                            [
                                'type' => 'row_item',
                                'columns' => [
                                    [
                                        'type' => 'column_item',
                                        'blocks' => [
                                            [
                                                'type' => 'card',
                                                'title' => 'Enterprise AI Solutions',
                                                'meta' => 'Cloud & Architecture',
                                                'content' => '<p>High performance <strong>scalable</strong> architecture.</p>',
                                                'props' => [
                                                    'card_style' => 'card-default',
                                                    'name' => 'Internal Card Name Should Not Be Indexed',
                                                    'css' => '.el-element { color: red; }',
                                                ],
                                                'elements' => [
                                                    [
                                                        'type' => 'button',
                                                        'content' => 'Explore Platform',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type' => 'accordion',
                                                'items' => [
                                                    [
                                                        'type' => 'accordion_item',
                                                        'title' => 'How does Seal indexing work?',
                                                        'content' => '<p>It indexes nested layout blocks seamlessly.</p>',
                                                    ],
                                                ],
                                            ],
                                            [
                                                'type' => 'reusable',
                                                'snippet' => 'reusable-uuid-001',
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

        $document = [
            'id' => 'page-1',
            'title' => 'Homepage',
            'content' => ['Existing root text'],
        ];

        $enhanced = $enhancer->enhanceDocument($pageData, $document);

        self::assertSame('42', $enhanced['mediaId'] ?? null);
        self::assertIsArray($enhanced['content']);
        /** @var list<string> $content */
        $content = $enhanced['content'];

        self::assertContains('Existing root text', $content);
        self::assertContains('Top Level Display Headline', $content);
        self::assertContains('Top level plain summary text.', $content);
        self::assertContains('Top level rich body content.', $content);
        self::assertContains('Enterprise AI Solutions', $content);
        self::assertContains('Cloud & Architecture', $content);
        self::assertContains('High performance scalable architecture.', $content);
        self::assertContains('Explore Platform', $content);
        self::assertContains('How does Seal indexing work?', $content);
        self::assertContains('It indexes nested layout blocks seamlessly.', $content);
        self::assertContains('Global Banner Reusable Title', $content);
        self::assertContains('Reusable CTA Headline Text', $content);
        self::assertContains('Reusable rich text description', $content);

        self::assertNotContains('card-default', $content);
        self::assertNotContains('Internal Card Name Should Not Be Indexed', $content);
        self::assertNotContains('.el-element { color: red; }', $content);
    }

    public function testDynamicContentHydratorAutoMapsPrelineCardFieldsViaMetadata(): void
    {
        $sourceResolver = $this->createStub(PageSourceResolverInterface::class);
        $sourceResolver->method('resolvePage')->willReturn([
            'title' => 'Card Page Title',
            'content' => '<p>Card rich text body.</p>',
            'image' => ['id' => 99, 'url' => '/uploads/card.jpg'],
            'url' => '/solutions/card-page',
        ]);

        $registry = $this->createPrelineMetadataRegistry();
        $targetFieldResolver = new BlockTargetFieldMetadataResolver($registry);
        $hydrator = new DynamicContentHydrator($sourceResolver, [], $targetFieldResolver);

        $cardBlock = [
            'type' => 'card',
            'title' => '',
            'content' => '',
            'image' => null,
            'link' => '',
            'props' => [
                'dynamic_source' => 'specific_page',
                'dynamic_page_id' => 'page-123',
                'dynamic_mapping' => [],
            ],
        ];

        $hydratedList = $hydrator->hydrateBlock($cardBlock);
        self::assertCount(1, $hydratedList);
        $hydrated = $hydratedList[0];

        self::assertSame('Card Page Title', $hydrated['title']);
        self::assertSame('<p>Card rich text body.</p>', $hydrated['content']);
        self::assertSame(['id' => 99, 'url' => '/uploads/card.jpg'], $hydrated['image']);
        self::assertSame('/solutions/card-page', $hydrated['link']);
    }

    private function createPrelineMetadataRegistry(): MetadataProviderRegistry
    {
        $pageForm = new FormMetadata();
        $pageForm->setKey('default');
        $pageForm->addItem($this->createField('headline', 'text_line'));
        $pageForm->addItem($this->createField('summary', 'text_area'));
        $pageForm->addItem($this->createField('content', 'text_editor'));
        $pageForm->addItem($this->createField('image', 'single_media_selection'));
        $pageForm->addItem($this->createField('blocks', 'block'));

        $typedPageMetadata = new TypedFormMetadata();
        $typedPageMetadata->addForm('default', $pageForm);

        $snippetForm = new FormMetadata();
        $snippetForm->setKey('reusable_block');
        $snippetForm->addItem($this->createField('title', 'text_line'));
        $snippetForm->addItem($this->createField('blocks', 'block'));

        $typedSnippetMetadata = new TypedFormMetadata();
        $typedSnippetMetadata->addForm('reusable_block', $snippetForm);

        $typedBlockMetadata = new TypedFormMetadata();

        $headlineForm = new FormMetadata();
        $headlineForm->setKey('headline');
        $headlineForm->addItem($this->createField('title', 'text_line'));
        $headlineForm->addItem($this->createField('content', 'text_editor'));
        $typedBlockMetadata->addForm('headline', $headlineForm);

        $buttonForm = new FormMetadata();
        $buttonForm->setKey('button');
        $buttonForm->addItem($this->createField('content', 'text_line'));
        $typedBlockMetadata->addForm('button', $buttonForm);

        $cardForm = new FormMetadata();
        $cardForm->setKey('card');
        $cardTabs = new SectionMetadata('__tabs');
        $cardContent = new SectionMetadata('content');
        $cardContent->addItem($this->createField('title', 'text_line'));
        $cardContent->addItem($this->createField('meta', 'text_line'));
        $cardContent->addItem($this->createField('content', 'text_editor'));
        $cardContent->addItem($this->createField('image', 'single_media_selection'));
        $cardContent->addItem($this->createField('link', 'link'));
        $elementsField = $this->createField('elements', 'block');
        $elementsField->addType($buttonForm);
        $cardContent->addItem($elementsField);
        $cardTabs->addItem($cardContent);
        $cardForm->addItem($cardTabs);
        $typedBlockMetadata->addForm('card', $cardForm);

        $accordionItemForm = new FormMetadata();
        $accordionItemForm->setKey('accordion_item');
        $accordionItemForm->addItem($this->createField('title', 'text_line'));
        $accordionItemForm->addItem($this->createField('content', 'text_editor'));

        $accordionForm = new FormMetadata();
        $accordionForm->setKey('accordion');
        $itemsField = $this->createField('items', 'block');
        $itemsField->addType($accordionItemForm);
        $accordionForm->addItem($itemsField);
        $typedBlockMetadata->addForm('accordion', $accordionForm);

        $reusableForm = new FormMetadata();
        $reusableForm->setKey('reusable');
        $reusableForm->addItem($this->createField('snippet', 'single_snippet_selection'));
        $typedBlockMetadata->addForm('reusable', $reusableForm);

        $colForm = new FormMetadata();
        $colForm->setKey('column_item');
        $colForm->addItem($this->createField('blocks', 'block'));

        $rowForm = new FormMetadata();
        $rowForm->setKey('row_item');
        $colsField = $this->createField('columns', 'block');
        $colsField->addType($colForm);
        $rowForm->addItem($colsField);

        $sectionForm = new FormMetadata();
        $sectionForm->setKey('section');
        $rowsField = $this->createField('rows', 'block');
        $rowsField->addType($rowForm);
        $sectionForm->addItem($rowsField);
        $typedBlockMetadata->addForm('section', $sectionForm);

        $provider = new class($typedPageMetadata, $typedBlockMetadata, $typedSnippetMetadata) implements MetadataProviderInterface {
            public function __construct(
                private readonly TypedFormMetadata $pageMetadata,
                private readonly TypedFormMetadata $blockMetadata,
                private readonly TypedFormMetadata $snippetMetadata,
            ) {
            }

            /**
             * @param array<string, mixed> $metadataOptions
             */
            public function getMetadata(string $key, string $locale, array $metadataOptions = []): \Sulu\Bundle\AdminBundle\Metadata\MetadataInterface
            {
                return match ($key) {
                    'page' => $this->pageMetadata,
                    'block' => $this->blockMetadata,
                    'snippet' => $this->snippetMetadata,
                    default => new TypedFormMetadata(),
                };
            }
        };

        return new MetadataProviderRegistry(new ServiceLocator([
            'form' => static fn (): MetadataProviderInterface => $provider,
        ]));
    }

    private function createField(string $name, string $type): FieldMetadata
    {
        $field = new FieldMetadata($name);
        $field->setType($type);

        return $field;
    }
}
