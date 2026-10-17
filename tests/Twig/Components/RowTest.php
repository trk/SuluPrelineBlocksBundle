<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Row;

final class RowTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $row = new Row();

        self::assertSame('halves', $row->get('layout'));
        self::assertSame('md', $row->get('gap'));
        self::assertSame('stretch', $row->get('align_items'));
        self::assertFalse($row->get('has_divider'));
        self::assertEmpty($row->get('columns'));
        self::assertFalse($row->hasColumns());
        self::assertSame('grid grid-cols-12', $row->getLayoutClasses());
        self::assertSame('gap-6 sm:gap-8', $row->getGapClasses());
        self::assertSame('items-stretch', $row->getAlignItemsClasses());
        self::assertSame('', $row->getDividerClasses());
        self::assertSame('grid grid-cols-12 gap-6 sm:gap-8 items-stretch', $row->getRowClasses());
        self::assertSame('grid grid-cols-12 gap-6 sm:gap-8 items-stretch', $row->getWrapperClasses());
    }

    public function testLayoutsAndGapsFromProps(): void
    {
        $row = new Row();
        $row->mergeProps([
            'layout' => 'thirds',
            'gap' => 'lg',
            'align_items' => 'center',
            'has_divider' => true,
            'id' => 'row-1',
            'max_width' => 'small',
            'block_align' => 'center',
        ]);

        self::assertSame('row-1', $row->getElementId());
        self::assertSame('grid grid-cols-12', $row->getLayoutClasses());
        self::assertSame('gap-8 sm:gap-12', $row->getGapClasses());
        self::assertSame('items-center', $row->getAlignItemsClasses());
        self::assertStringNotContainsString('max-w-', $row->getRowClasses());
        self::assertStringNotContainsString('mx-auto', $row->getRowClasses());
        // A single column never sits side by side.
        self::assertSame('divide-y divide-border', $row->getDividerClasses());

        $row->assign('columns', [['blocks' => []], ['blocks' => []], ['blocks' => []]]);
        self::assertSame('divide-y md:divide-y-0 md:divide-x divide-border', $row->getDividerClasses());

        $row->setProp('layout', 'quarters');
        self::assertSame('divide-y sm:divide-y-0 sm:divide-x divide-border', $row->getDividerClasses());

        $row->setProp('layout', 'auto');
        self::assertSame('grid grid-cols-1 sm:grid-cols-3', $row->getLayoutClasses());

        $row->setProp('layout', 'unknown');
        self::assertSame('halves', $row->getResolvedLayout());
    }

    public function testNormalizedColumns(): void
    {
        $row = new Row();
        $row->mergeProps(['layout' => 'thirds-2-1']);
        $row->assign('columns', [
            ['props' => ['col_span' => 'auto'], 'blocks' => [['type' => 'headline']]],
            ['props' => ['col_span' => 'auto'], 'blocks' => [['type' => 'text']]],
        ]);

        self::assertTrue($row->hasColumns());
        $cols = $row->getNormalizedColumns();
        self::assertCount(2, $cols);
        self::assertSame(['base' => 12, 'md' => 8], $cols[0]['_spans']);
        self::assertSame(['base' => 12, 'md' => 4], $cols[1]['_spans']);
        self::assertSame('md', $cols[0]['_side_by_side']);
    }
}
