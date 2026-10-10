<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Section;

final class SectionTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $sec = new Section();

        self::assertNull($sec->title);
        self::assertNull($sec->subtitle);
        self::assertSame('center', $sec->title_align);
        self::assertSame('85rem', $sec->max_width);
        self::assertSame('default', $sec->padding_y);
        self::assertSame('default', $sec->padding_x);
        self::assertSame('transparent', $sec->bg_style);
        self::assertNull($sec->bg_image);
        self::assertFalse($sec->has_border);
        self::assertSame('halves', $sec->row_layout);
        self::assertSame('md', $sec->gap);
        self::assertNull($sec->content);
        self::assertEmpty($sec->items);

        self::assertFalse($sec->hasHeader());
        self::assertFalse($sec->hasContent());
        self::assertFalse($sec->hasItems());
        self::assertFalse($sec->isGridEnabled());

        self::assertSame('relative w-full overflow-hidden bg-transparent', $sec->getSectionClasses());
        self::assertStringContainsString('max-w-[85rem]', $sec->getContainerClasses());
        self::assertStringContainsString('py-10 sm:py-14', $sec->getContainerClasses());
        self::assertSame('text-center mx-auto', $sec->getHeaderAlignmentClasses());
    }

    public function testStylesAndContainers(): void
    {
        $sec = new Section();
        $sec->bg_style = 'muted';
        $sec->has_border = true;
        $sec->max_width = '7xl';
        $sec->padding_y = 'lg';
        $sec->title_align = 'start';

        self::assertStringContainsString('bg-gray-50', $sec->getSectionClasses());
        self::assertStringContainsString('border-t border-b', $sec->getSectionClasses());
        self::assertStringContainsString('max-w-7xl', $sec->getContainerClasses());
        self::assertStringContainsString('py-14 sm:py-20', $sec->getContainerClasses());
        self::assertSame('text-start', $sec->getHeaderAlignmentClasses());
    }

    public function testHeaderAndGrid(): void
    {
        $sec = new Section();
        $sec->title = 'Features Section';
        $sec->subtitle = 'Why choose us';
        $sec->content = '<p>Description</p>';
        $sec->items = [
            ['title' => 'Feature 1'],
            ['title' => 'Feature 2'],
        ];

        self::assertTrue($sec->hasHeader());
        self::assertTrue($sec->hasContent());
        self::assertTrue($sec->hasItems());
        self::assertTrue($sec->isGridEnabled());
    }
}
