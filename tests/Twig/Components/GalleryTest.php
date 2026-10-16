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

        self::assertNull($gallery->get('title'));
        self::assertFalse($gallery->isFilterEnabled());
        self::assertFalse($gallery->isLightboxEnabled());
        self::assertFalse($gallery->isMasonry());
        self::assertEmpty($gallery->getNormalizedItems());
        self::assertEmpty($gallery->getFilterTags());
        self::assertSame('grid grid-cols-1 md:grid-cols-3 gap-6', $gallery->getContainerClasses());
        self::assertSame('justify-center', $gallery->getFilterAlignClass());
    }

    public function testInheritLowerBreakpointWhenHigherBreakpointsAreNone(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'columns_default' => '1',
            'columns_sm' => '',
            'columns_md' => '2',
            'columns_lg' => '',
            'columns_xl' => '',
        ]);

        self::assertSame('grid grid-cols-1 md:grid-cols-2 gap-6', $gallery->getContainerClasses());

        // When only md: 2 is provided, default mobile fallback is 1 and md applies for >= 768px
        $gallery2 = new Gallery();
        $gallery2->mergeProps([
            'columns_md' => '2',
        ]);
        self::assertSame('grid grid-cols-1 md:grid-cols-2 gap-6', $gallery2->getContainerClasses());

        // When only default: 2 is provided and responsive are inherit/empty, 2 applies everywhere
        $gallery3 = new Gallery();
        $gallery3->mergeProps([
            'columns_default' => '2',
            'columns_sm' => '',
            'columns_md' => '',
            'columns_lg' => '',
            'columns_xl' => '',
        ]);
        self::assertSame('grid grid-cols-2 gap-6', $gallery3->getContainerClasses());
    }

    public function testColumnsAndGapOverrides(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'columns_default' => '2',
            'columns_sm' => '3',
            'columns_md' => '4',
            'columns_lg' => '5',
            'gap' => 'xl',
        ]);

        $classes = $gallery->getContainerClasses();
        self::assertStringContainsString('grid-cols-2', $classes);
        self::assertStringContainsString('sm:grid-cols-3', $classes);
        self::assertStringContainsString('md:grid-cols-4', $classes);
        self::assertStringContainsString('lg:grid-cols-5', $classes);
        self::assertStringContainsString('gap-12', $classes);
    }

    public function testIntegerColumnsAndXlSupport(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'columns_default' => 3,
            'columns_sm' => 2,
            'columns_md' => 4,
            'columns_lg' => 5,
            'columns_xl' => 6,
            'gap' => 'none',
        ]);

        $classes = $gallery->getContainerClasses();
        self::assertSame('grid grid-cols-3 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-0', $classes);
    }

    public function testNestedPropsUnpacking(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'props' => [
                'columns_default' => 4,
                'columns_sm' => 1,
                'columns_md' => 2,
                'columns_lg' => 3,
                'columns_xl' => 4,
                'gap' => '2xl',
            ],
        ]);

        $classes = $gallery->getContainerClasses();
        self::assertSame('grid grid-cols-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-16', $classes);
    }

    public function testMasonryMode(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'masonry' => 'columns',
            'columns_default' => '1',
            'columns_sm' => '2',
            'columns_md' => '3',
            'columns_lg' => '4',
            'gap' => 'sm',
        ]);

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
        $gallery->mergeProps([
            'filter' => true,
            'filter_align' => 'right',
        ]);
        $gallery->assign('items', [
            ['title' => 'Photo 1', 'tags' => 'Landscape, Architecture'],
            ['title' => 'Photo 2', 'tags' => 'Portrait, Landscape'],
        ]);

        self::assertTrue($gallery->isFilterEnabled());
        self::assertSame('justify-end', $gallery->getFilterAlignClass());

        $tags = $gallery->getFilterTags();
        self::assertCount(3, $tags);
        self::assertSame('Landscape', $tags['landscape']);
        self::assertSame('Architecture', $tags['architecture']);
        self::assertSame('Portrait', $tags['portrait']);

        self::assertSame('landscape architecture', $gallery->getItemTagSlugs($gallery->items()[0]));
        self::assertSame('portrait landscape', $gallery->getItemTagSlugs($gallery->items()[1]));
    }

    public function testItemPropsInheritanceAndOverride(): void
    {
        $gallery = new Gallery();
        $gallery->mergeProps([
            'overlay_position' => 'center',
            'overlay_style' => 'dark',
            'media_aspect_ratio' => '16/9',
            'rounded' => '2xl',
            'lightbox' => true,
        ]);

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

        $itemWithFocalAndFormats = [
            'image' => [
                'url' => '/uploads/media/photo.jpg',
                'formats' => [
                    'col-sm' => '/uploads/media/photo-400.webp',
                    'col-lg' => '/uploads/media/photo-960.webp',
                ],
                'focalPoint' => ['x' => 40, 'y' => 60],
            ],
        ];

        self::assertNotNull($gallery->resolveItemImageSrcSet($itemWithFocalAndFormats));
        self::assertStringContainsString('photo-400.webp 400w', $gallery->resolveItemImageSrcSet($itemWithFocalAndFormats));
        self::assertSame('object-position: 40% 60%;', $gallery->resolveItemImageFocusPointStyle($itemWithFocalAndFormats));
        self::assertNull($gallery->resolveItemImageSrcSet([]));
        self::assertNull($gallery->resolveItemImageFocusPointStyle([]));
    }

    public function testCustomIdAndClass(): void
    {
        $gallery = new Gallery();
        $gallery->assign('id', 'photo-gallery');
        $gallery->assign('customClass', 'custom-gallery-class');

        self::assertSame('photo-gallery', $gallery->getCustomId());
        self::assertSame('custom-gallery-class', $gallery->getCustomClass());
    }
}
