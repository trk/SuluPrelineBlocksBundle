<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Grid;

final class GridTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $grid = new Grid();

        self::assertNull($grid->get('title'));
        self::assertFalse($grid->isFilterEnabled());
        self::assertFalse($grid->isMasonry());
        self::assertEmpty($grid->getNormalizedItems());
        self::assertEmpty($grid->getFilterTags());
        self::assertSame('grid grid-cols-1 md:grid-cols-3 gap-6', $grid->getContainerClasses());
        self::assertSame('justify-center', $grid->getFilterAlignClass());
    }

    public function testInheritLowerBreakpointWhenHigherBreakpointsAreNone(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'columns_default' => '1',
            'columns_sm' => '',
            'columns_md' => '2',
            'columns_lg' => '',
            'columns_xl' => '',
        ]);

        self::assertSame('grid grid-cols-1 md:grid-cols-2 gap-6', $grid->getContainerClasses());

        // When only md: 2 is provided, default mobile fallback is 1 and md applies for >= 768px
        $grid2 = new Grid();
        $grid2->mergeProps([
            'columns_md' => '2',
        ]);
        self::assertSame('grid grid-cols-1 md:grid-cols-2 gap-6', $grid2->getContainerClasses());

        // When only default: 2 is provided and responsive are inherit/empty, 2 applies everywhere
        $grid3 = new Grid();
        $grid3->mergeProps([
            'columns_default' => '2',
            'columns_sm' => '',
            'columns_md' => '',
            'columns_lg' => '',
            'columns_xl' => '',
        ]);
        self::assertSame('grid grid-cols-2 gap-6', $grid3->getContainerClasses());
    }

    public function testColumnsAndGapOverrides(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'columns_default' => '2',
            'columns_sm' => '3',
            'columns_md' => '4',
            'columns_lg' => '6',
            'gap' => 'lg',
        ]);

        $classes = $grid->getContainerClasses();
        self::assertStringContainsString('grid-cols-2', $classes);
        self::assertStringContainsString('sm:grid-cols-3', $classes);
        self::assertStringContainsString('md:grid-cols-4', $classes);
        self::assertStringContainsString('lg:grid-cols-6', $classes);
        self::assertStringContainsString('gap-8', $classes);
    }

    public function testIntegerColumnsAndXlSupport(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'columns_default' => 3,
            'columns_sm' => 2,
            'columns_md' => 4,
            'columns_lg' => 5,
            'columns_xl' => 6,
            'gap' => 'none',
        ]);

        $classes = $grid->getContainerClasses();
        self::assertSame('grid grid-cols-3 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-0', $classes);
    }

    public function testNestedPropsUnpacking(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'props' => [
                'columns_default' => 4,
                'columns_sm' => 1,
                'columns_md' => 2,
                'columns_lg' => 3,
                'columns_xl' => 4,
                'gap' => '2xl',
            ],
        ]);

        $classes = $grid->getContainerClasses();
        self::assertSame('grid grid-cols-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-16', $classes);
    }

    public function testMasonryMode(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'masonry' => 'columns',
            'columns_default' => '1',
            'columns_sm' => '2',
            'columns_md' => '3',
            'columns_lg' => '4',
            'gap' => 'sm',
        ]);

        self::assertTrue($grid->isMasonry());
        $classes = $grid->getContainerClasses();
        self::assertStringContainsString('columns-1', $classes);
        self::assertStringContainsString('sm:columns-2', $classes);
        self::assertStringContainsString('md:columns-3', $classes);
        self::assertStringContainsString('lg:columns-4', $classes);
        self::assertStringContainsString('gap-4', $classes);
    }

    public function testFilterTagsAndSlugs(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'filter' => true,
            'filter_align' => 'left',
        ]);
        $grid->assign('items', [
            ['title' => 'Item 1', 'tags' => 'Design, Tech, Web Development'],
            ['title' => 'Item 2', 'tags' => 'Tech, Marketing'],
            ['title' => 'Item 3', 'tags' => ''],
        ]);

        self::assertTrue($grid->isFilterEnabled());
        self::assertSame('justify-start', $grid->getFilterAlignClass());

        $tags = $grid->getFilterTags();
        self::assertCount(4, $tags);
        self::assertSame('Design', $tags['design']);
        self::assertSame('Tech', $tags['tech']);
        self::assertSame('Web Development', $tags['web-development']);
        self::assertSame('Marketing', $tags['marketing']);

        self::assertSame('design tech web-development', $grid->getItemTagSlugs($grid->items()[0]));
        self::assertSame('tech marketing', $grid->getItemTagSlugs($grid->items()[1]));
        self::assertSame('', $grid->getItemTagSlugs($grid->items()[2]));
    }

    public function testItemPropsInheritanceAndOverride(): void
    {
        $grid = new Grid();
        $grid->mergeProps([
            'card_variant' => 'bordered',
            'button_style' => 'outline',
            'media_align' => 'left',
            'text_align' => 'center',
        ]);

        // Item 1 inherits grid defaults
        $item1 = [
            'title' => 'Card 1',
            'panel_style' => 'inherit',
            'link_style' => 'inherit',
        ];
        $resolved1 = $grid->resolveItemProps($item1);
        self::assertSame('bordered', $resolved1['card_variant']);
        self::assertSame('outline', $resolved1['button_style']);
        self::assertSame('left', $resolved1['media_align']);
        self::assertSame('center', $resolved1['text_align']);

        // Item 2 overrides card variant and button style
        $item2 = [
            'title' => 'Card 2',
            'panel_style' => 'raised',
            'link_style' => 'soft',
        ];
        $resolved2 = $grid->resolveItemProps($item2);
        self::assertSame('raised', $resolved2['card_variant']);
        self::assertSame('soft', $resolved2['button_style']);
        self::assertSame('left', $resolved2['media_align']);
        self::assertSame('center', $resolved2['text_align']);
    }

    public function testCardGroupLayout(): void
    {
        $grid = new Grid();
        $grid->assign('layoutType', 'card_group');

        self::assertTrue($grid->isCardGroup());
        $classes = $grid->getContainerClasses();
        self::assertStringContainsString('divide-y', $classes);
        self::assertStringContainsString('sm:flex', $classes);
        self::assertStringContainsString('sm:divide-y-0', $classes);
        self::assertStringContainsString('sm:divide-x', $classes);

        $itemProps = $grid->resolveItemProps(['title' => 'Group Card']);
        self::assertSame('group', $itemProps['card_variant']);
    }
}
