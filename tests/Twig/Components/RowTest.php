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

        self::assertNull($row->title);
        self::assertSame('halves', $row->layout);
        self::assertSame('md', $row->gap);
        self::assertSame('stretch', $row->align_items);
        self::assertFalse($row->has_divider);
        self::assertEmpty($row->columns);
        self::assertFalse($row->hasColumns());
        self::assertSame('grid-cols-1 sm:grid-cols-2', $row->getLayoutClasses());
        self::assertSame('gap-6 sm:gap-8', $row->getGapClasses());
        self::assertSame('items-stretch', $row->getAlignItemsClasses());
        self::assertSame('', $row->getDividerClasses());
        self::assertStringContainsString('grid w-full grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-8 items-stretch', $row->getRowClasses());
    }

    public function testLayoutsAndGaps(): void
    {
        $row = new Row();

        $row->layout = 'thirds';
        $row->gap = 'lg';
        $row->align_items = 'center';
        $row->has_divider = true;

        self::assertSame('grid-cols-1 md:grid-cols-3', $row->getLayoutClasses());
        self::assertSame('gap-8 sm:gap-12', $row->getGapClasses());
        self::assertSame('items-center', $row->getAlignItemsClasses());
        self::assertStringContainsString('divide-gray-200', $row->getDividerClasses());

        $row->layout = 'quarters';
        self::assertSame('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', $row->getLayoutClasses());

        $row->layout = '12-col';
        self::assertSame('grid-cols-1 md:grid-cols-12', $row->getLayoutClasses());
    }

    public function testNormalizedColumns(): void
    {
        $row = new Row();
        $row->layout = 'thirds-2-1';
        $row->columns = [
            ['title' => 'Main Column', 'col_span' => 'auto'],
            ['title' => 'Sidebar Column', 'col_span' => 'auto'],
        ];

        self::assertTrue($row->hasColumns());
        $cols = $row->getNormalizedColumns();
        self::assertCount(2, $cols);
        self::assertSame('8', $cols[0]['col_span']);
        self::assertSame('4', $cols[1]['col_span']);
    }
}
