<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Gallery;

final class GalleryTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $gallery = new Gallery();

        self::assertNull($gallery->title);
        self::assertFalse($gallery->isFilterEnabled());
        self::assertFalse($gallery->isLightboxEnabled());
        self::assertFalse($gallery->isMasonry());
        self::assertEmpty($gallery->getNormalizedItems());
        self::assertEmpty($gallery->getFilterTags());
        self::assertStringContainsString('grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6', $gallery->getContainerClasses());
        self::assertSame('justify-center', $gallery->getFilterAlignClass());
    }

    public function testColumnsAndGapOverrides(): void
    {
        $gallery = new Gallery();
        $gallery->props = [
            'columns_default' => '2',
            'columns_sm' => '3',
            'columns_md' => '4',
            'columns_lg' => '5',
            'gap' => 'xl',
        ];

        $classes = $gallery->getContainerClasses();
        self::assertStringContainsString('grid-cols-2', $classes);
        self::assertStringContainsString('sm:grid-cols-3', $classes);
        self::assertStringContainsString('md:grid-cols-4', $classes);
        self::assertStringContainsString('lg:grid-cols-5', $classes);
        self::assertStringContainsString('gap-12', $classes);
    }

    public function testMasonryMode(): void
    {
        $gallery = new Gallery();
        $gallery->props = [
            'masonry' => 'columns',
            'columns_default' => '1',
            'columns_sm' => '2',
            'columns_md' => '3',
            'columns_lg' => '4',
            'gap' => 'sm',
        ];

        self::assertTrue($gallery->isMasonry());
        $classes = $gallery->getContainerClasses();
        self::assertStringContainsString('columns-1', $classes);
        self::assertStringContainsString('sm:columns-2', $classes);
        self::assertStringContainsString('md:columns-3', $classes);
        self::assertStringContainsString('lg:columns-4', $classes);
        self::assertStringContainsString('gap-4', $classes);
    }

    public function testFilterTagsAndSlugs(): void
    {
        $gallery = new Gallery();
        $gallery->props = [
            'filter' => true,
            'filter_align' => 'right',
        ];
        $gallery->items = [
            ['title' => 'Photo 1', 'tags' => 'Landscape, Architecture'],
            ['title' => 'Photo 2', 'tags' => 'Portrait, Landscape'],
        ];

        self::assertTrue($gallery->isFilterEnabled());
        self::assertSame('justify-end', $gallery->getFilterAlignClass());

        $tags = $gallery->getFilterTags();
        self::assertCount(3, $tags);
        self::assertSame('Landscape', $tags['landscape']);
        self::assertSame('Architecture', $tags['architecture']);
        self::assertSame('Portrait', $tags['portrait']);

        self::assertSame('landscape architecture', $gallery->getItemTagSlugs($gallery->items[0]));
        self::assertSame('portrait landscape', $gallery->getItemTagSlugs($gallery->items[1]));
    }

    public function testItemPropsInheritanceAndOverride(): void
    {
        $gallery = new Gallery();
        $gallery->props = [
            'overlay_position' => 'center',
            'overlay_style' => 'dark',
            'media_aspect_ratio' => '16/9',
            'rounded' => '2xl',
            'lightbox' => true,
        ];

        self::assertTrue($gallery->isLightboxEnabled());

        // Item 1 inherits gallery defaults
        $item1 = [
            'title' => 'Item 1',
            'overlay_style' => 'inherit',
        ];
        $resolved1 = $gallery->resolveItemProps($item1);
        self::assertSame('center', $resolved1['overlay_position']);
        self::assertSame('dark', $resolved1['overlay_style']);
        self::assertSame('16/9', $resolved1['media_aspect_ratio']);
        self::assertSame('2xl', $resolved1['rounded']);

        // Item 2 overrides overlay style
        $item2 = [
            'title' => 'Item 2',
            'overlay_style' => 'gradient',
            'link_style' => 'outline',
        ];
        $resolved2 = $gallery->resolveItemProps($item2);
        self::assertSame('center', $resolved2['overlay_position']);
        self::assertSame('gradient', $resolved2['overlay_style']);
        self::assertSame('outline', $resolved2['button_style']);
    }

    public function testResolveItemImageUrl(): void
    {
        $gallery = new Gallery();

        self::assertSame('https://example.com/photo.jpg', $gallery->resolveItemImageUrl([
            'image' => 'https://example.com/photo.jpg',
        ]));

        self::assertSame('/uploads/media/sample.jpg', $gallery->resolveItemImageUrl([
            'image' => ['url' => '/uploads/media/sample.jpg'],
        ]));

        self::assertSame('/uploads/media/large.jpg', $gallery->resolveItemImageUrl([
            'image' => ['formats' => ['large' => '/uploads/media/large.jpg']],
        ]));

        self::assertNull($gallery->resolveItemImageUrl([]));
    }

    public function testCustomIdAndClass(): void
    {
        $gallery = new Gallery();
        $gallery->id = 'photo-gallery';
        $gallery->customClass = 'custom-gallery-class';

        self::assertSame('photo-gallery', $gallery->getCustomId());
        self::assertSame('custom-gallery-class', $gallery->getCustomClass());
    }
}
