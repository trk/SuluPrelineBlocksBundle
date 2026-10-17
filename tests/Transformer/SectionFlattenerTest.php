<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Sulu\Bundle\HttpCacheBundle\ReferenceStore\ReferenceStoreInterface;
use Sulu\Snippet\Domain\Model\SnippetInterface;
use Totoglu\SuluBlockSuiteBundle\Transformer\BlockTransformPipeline;
use Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer;
use Totoglu\SuluBlockSuiteBundle\Transformer\ReusableBlockTransformer;
use Totoglu\SuluPrelineBlocksBundle\Transformer\SectionFlattener;

final class SectionFlattenerTest extends TestCase
{
    public function testSupportsOnlySectionBlocks(): void
    {
        $flattener = new SectionFlattener();

        self::assertTrue($flattener->supports(['type' => 'section']));
        self::assertFalse($flattener->supports(['type' => 'headline']));
        self::assertFalse($flattener->supports([]));
    }

    public function testFlattensRowsColumnsAndNestedSectionsInOrder(): void
    {
        $flattener = new SectionFlattener();

        $section = [
            'type' => 'section',
            'rows' => [
                [
                    'columns' => [
                        [
                            'blocks' => [
                                ['type' => 'headline', 'title' => 'H1'],
                                [
                                    'type' => 'section',
                                    'rows' => [
                                        [
                                            'columns' => [
                                                [
                                                    'blocks' => [
                                                        ['type' => 'text', 'content' => 'Nested T1'],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'blocks' => [
                                ['type' => 'button', 'title' => 'CTA'],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $flattened = $flattener->flatten(
            $section,
            static fn (array $child): array => [$child],
        );

        self::assertCount(3, $flattened);
        self::assertSame('headline', $flattened[0]['type']);
        self::assertSame('H1', $flattened[0]['title']);
        self::assertSame('text', $flattened[1]['type']);
        self::assertSame('Nested T1', $flattened[1]['content']);
        self::assertSame('button', $flattened[2]['type']);
        self::assertSame('CTA', $flattened[2]['title']);
    }

    public function testAcceptanceScenarioPreservesTopLevelSectionAndFlattensColumnLevelSection(): void
    {
        $pipeline = new BlockTransformPipeline([
            new ReusableBlockTransformer(null, null, [new SectionFlattener()]),
            new DefaultAttributesTransformer(),
        ]);

        $blocks = [
            ['type' => 'headline', 'title' => 'H1'],
            ['type' => 'text', 'content' => 'T1'],
            ['type' => 'image', 'image_url' => '/img1.jpg'],
            [
                'type' => 'section',
                'rows' => [
                    [
                        'columns' => [
                            [
                                'blocks' => [
                                    ['type' => 'image', 'image_url' => '/sec1.jpg'],
                                    ['type' => 'headline', 'title' => 'Sec1 H'],
                                    ['type' => 'text', 'content' => 'Sec1 T'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'type' => 'reusable',
                'snippet' => [
                    'uuid' => 'reusable-uuid-1',
                    'blocks' => [
                        ['type' => 'headline', 'title' => 'R1 H'],
                        ['type' => 'text', 'content' => 'R1 T'],
                    ],
                ],
            ],
            ['type' => 'image', 'image_url' => '/img2.jpg'],
            ['type' => 'text', 'content' => 'T2'],
            [
                'type' => 'reusable',
                'snippet' => [
                    'uuid' => 'reusable-uuid-2',
                    'blocks' => [
                        [
                            'type' => 'section',
                            'rows' => [
                                [
                                    'columns' => [
                                        [
                                            'blocks' => [
                                                ['type' => 'image', 'image_url' => '/r2.jpg'],
                                                ['type' => 'text', 'content' => 'R2 T'],
                                                ['type' => 'button', 'title' => 'R2 Btn'],
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

        $processed = $pipeline->processCollection($blocks);

        self::assertCount(9, $processed);

        // 1..3: Page loose blocks before section (no _section_group)
        self::assertSame('headline', $processed[0]['type']);
        self::assertNull($processed[0]['_section_group'] ?? null);
        self::assertSame('text', $processed[1]['type']);
        self::assertNull($processed[1]['_section_group'] ?? null);
        self::assertSame('image', $processed[2]['type']);
        self::assertNull($processed[2]['_section_group'] ?? null);

        // 4: Page section block
        self::assertSame('section', $processed[3]['type']);
        self::assertNull($processed[3]['_section_group'] ?? null);

        // 5..6: Reusable #1 loose blocks share reusable_1 group
        self::assertSame('headline', $processed[4]['type']);
        self::assertSame('R1 H', $processed[4]['title']);
        self::assertSame('reusable_1', $processed[4]['_section_group'] ?? null);
        self::assertSame('text', $processed[5]['type']);
        self::assertSame('R1 T', $processed[5]['content']);
        self::assertSame('reusable_1', $processed[5]['_section_group'] ?? null);

        // 7..8: Page loose blocks after reusable #1 (no _section_group)
        self::assertSame('image', $processed[6]['type']);
        self::assertNull($processed[6]['_section_group'] ?? null);
        self::assertSame('text', $processed[7]['type']);
        self::assertNull($processed[7]['_section_group'] ?? null);

        // 9: Reusable #2 section block stays intact at top level
        self::assertSame('section', $processed[8]['type']);
        self::assertSame('reusable_2', $processed[8]['_section_group'] ?? null);
    }

    public function testUnwrapsSectionInsideReusableWhenRenderedInsideColumn(): void
    {
        $recordedSnippets = [];
        $referenceStore = $this->createMock(ReferenceStoreInterface::class);
        $referenceStore->expects(self::exactly(2))
            ->method('add')
            ->willReturnCallback(static function (string $id, string $resourceKey) use (&$recordedSnippets): void {
                self::assertSame(SnippetInterface::RESOURCE_KEY, $resourceKey);
                $recordedSnippets[] = $id;
            });

        $pipeline = new BlockTransformPipeline([
            new ReusableBlockTransformer(null, $referenceStore, [new SectionFlattener()]),
            new DefaultAttributesTransformer(),
        ]);

        $processed = $pipeline->processCollection([
            [
                'type' => 'reusable',
                'snippet' => [
                    'uuid' => 'outer-snippet-uuid',
                    'blocks' => [
                        [
                            'type' => 'section',
                            'rows' => [
                                [
                                    'columns' => [
                                        [
                                            'blocks' => [
                                                ['type' => 'headline', 'title' => 'Outer Section Headline'],
                                                [
                                                    'type' => 'reusable',
                                                    'snippet' => [
                                                        'uuid' => 'inner-snippet-uuid',
                                                        'blocks' => [
                                                            [
                                                                'type' => 'section',
                                                                'rows' => [
                                                                    [
                                                                        'columns' => [
                                                                            [
                                                                                'blocks' => [
                                                                                    ['type' => 'text', 'content' => 'Inner Nested Text'],
                                                                                    ['type' => 'button', 'title' => 'Inner CTA'],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], ['_in_column' => true]);

        self::assertCount(3, $processed);
        self::assertSame('headline', $processed[0]['type']);
        self::assertSame('Outer Section Headline', $processed[0]['title']);
        self::assertSame('reusable_1', $processed[0]['_section_group'] ?? null);

        self::assertSame('text', $processed[1]['type']);
        self::assertSame('Inner Nested Text', $processed[1]['content']);
        self::assertSame('reusable_2', $processed[1]['_section_group'] ?? null);

        self::assertSame('button', $processed[2]['type']);
        self::assertSame('Inner CTA', $processed[2]['title']);
        self::assertSame('reusable_2', $processed[2]['_section_group'] ?? null);

        self::assertSame(['outer-snippet-uuid', 'inner-snippet-uuid'], $recordedSnippets);
    }

    public function testExpandsAndFlattensColumnLevelReusablesInsideTopLevelSection(): void
    {
        $pipeline = new BlockTransformPipeline([
            new ReusableBlockTransformer(null, null, [new SectionFlattener()]),
            new DefaultAttributesTransformer(),
        ]);

        $processed = $pipeline->processCollection([
            [
                'type' => 'section',
                'rows' => [
                    [
                        'columns' => [
                            [
                                'blocks' => [
                                    [
                                        'type' => 'reusable',
                                        'snippet' => [
                                            'blocks' => [
                                                ['type' => 'headline', 'title' => 'Col R1 H'],
                                                ['type' => 'text', 'content' => 'Col R1 T'],
                                            ],
                                        ],
                                    ],
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
                                                                        ['type' => 'headline', 'title' => 'Col R2 Sec H'],
                                                                    ],
                                                                ],
                                                            ],
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
                                                [
                                                    'type' => 'section',
                                                    'rows' => [
                                                        [
                                                            'columns' => [
                                                                [
                                                                    'blocks' => [
                                                                        [
                                                                            'type' => 'reusable',
                                                                            'snippet' => [
                                                                                'blocks' => [
                                                                                    ['type' => 'text', 'content' => 'Col R3 Nested T'],
                                                                                ],
                                                                            ],
                                                                        ],
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);

        self::assertCount(1, $processed);
        self::assertSame('section', $processed[0]['type']);
        self::assertIsArray($processed[0]['rows']);
        self::assertIsArray($processed[0]['rows'][0]);
        self::assertIsArray($processed[0]['rows'][0]['columns']);
        self::assertIsArray($processed[0]['rows'][0]['columns'][0]);
        $colBlocks = $processed[0]['rows'][0]['columns'][0]['blocks'] ?? null;
        self::assertIsArray($colBlocks);
        self::assertCount(4, $colBlocks);
        self::assertIsArray($colBlocks[0]);
        self::assertIsArray($colBlocks[1]);
        self::assertIsArray($colBlocks[2]);
        self::assertIsArray($colBlocks[3]);

        self::assertSame('headline', $colBlocks[0]['type'] ?? null);
        self::assertSame('Col R1 H', $colBlocks[0]['title'] ?? null);

        self::assertSame('text', $colBlocks[1]['type'] ?? null);
        self::assertSame('Col R1 T', $colBlocks[1]['content'] ?? null);

        self::assertSame('headline', $colBlocks[2]['type'] ?? null);
        self::assertSame('Col R2 Sec H', $colBlocks[2]['title'] ?? null);

        self::assertSame('text', $colBlocks[3]['type'] ?? null);
        self::assertSame('Col R3 Nested T', $colBlocks[3]['content'] ?? null);
    }
}
