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

        self::assertNull($col->title);
        self::assertNull($col->subtitle);
        self::assertNull($col->content);
        self::assertNull($col->image);
        self::assertNull($col->link);
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

    public function testSpansAndStyles(): void
    {
        $col = new Column();
        $col->col_span = '6';
        $col->style = 'card';
        $col->padding = 'md';
        $col->vertical_align = 'center';
        $col->text_align = 'center';
        $col->is_sticky = true;
        $col->order = 'first';

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

    public function testUrlsAndSubtitle(): void
    {
        $col = new Column();
        $col->meta = 'Eyebrow Text';
        $col->image = ['url' => '/media/sample.jpg'];
        $col->link = '/learn-more';
        $col->content = '<p>Content</p>';

        self::assertSame('Eyebrow Text', $col->getSubtitle());
        self::assertSame('/media/sample.jpg', $col->getImageUrl());
        self::assertSame('/learn-more', $col->getLinkUrl());
        self::assertTrue($col->hasContent());
    }
}
