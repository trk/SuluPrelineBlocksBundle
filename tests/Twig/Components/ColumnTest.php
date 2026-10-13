<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Column;

final class ColumnTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $col = new Column();

        self::assertSame('auto', $col->col_span);
        self::assertSame('none', $col->style);
        self::assertSame('none', $col->padding);
        self::assertSame('top', $col->vertical_align);
        self::assertSame('start', $col->text_align);
        self::assertFalse($col->is_sticky);
        self::assertSame('default', $col->order);
        self::assertSame('', $col->getColSpanClasses());
        self::assertSame('', $col->getStyleClasses());
        self::assertSame('', $col->getPaddingClasses());
        self::assertSame('justify-start', $col->getVerticalAlignClasses());
        self::assertSame('text-start', $col->getTextAlignClasses());
        self::assertSame('', $col->getOrderClasses());
        self::assertSame('', $col->getStickyClasses());
        self::assertStringContainsString('flex flex-col w-full', $col->getColumnClasses());
    }

    public function testSpansAndStylesFromProps(): void
    {
        $col = new Column();
        $col->props = [
            'col_span' => '6',
            'style' => 'card',
            'padding' => 'md',
            'vertical_align' => 'center',
            'text_align' => 'center',
            'is_sticky' => true,
            'order' => 'first',
            'id' => 'col-main',
        ];

        self::assertSame('col-main', $col->getElementId());
        self::assertSame('col-span-12 md:col-span-6', $col->getColSpanClasses());
        self::assertStringContainsString('border border-gray-200', $col->getStyleClasses());
        self::assertSame('p-6 sm:p-8', $col->getPaddingClasses());
        self::assertSame('justify-center', $col->getVerticalAlignClasses());
        self::assertSame('text-center', $col->getTextAlignClasses());
        self::assertSame('sticky top-20 self-start', $col->getStickyClasses());
        self::assertSame('order-first md:order-none', $col->getOrderClasses());

        $classes = $col->getColumnClasses();
        self::assertStringContainsString('col-span-12 md:col-span-6', $classes);
        self::assertStringContainsString('p-6 sm:p-8', $classes);
        self::assertStringContainsString('sticky top-20 self-start', $classes);
    }

    public function testBlocksSupport(): void
    {
        $col = new Column();
        self::assertFalse($col->hasBlocks());
        self::assertEmpty($col->getNormalizedBlocks());

        $col->blocks = [
            ['type' => 'headline', 'title' => 'Inner Title'],
            ['type' => 'text', 'content' => '<p>Inner Text</p>'],
        ];

        self::assertTrue($col->hasBlocks());
        self::assertCount(2, $col->getNormalizedBlocks());
    }
}
