<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Image;

final class ImageTest extends TestCase
{
    public function testStandaloneAndBareModes(): void
    {
        $image = new Image();
        self::assertFalse($image->shouldRender());

        $image->mergeProps([
            'image' => [
                'url' => '/uploads/hero.jpg',
                'title' => 'Hero Image',
                'focusPoint' => ['x' => 50, 'y' => 0],
            ],
            'hover_image' => [
                'url' => '/uploads/hero-hover.jpg',
                'title' => 'Hero Hover',
            ],
            'caption' => 'Hero caption text',
            'props' => [
                'rounded' => 'xl',
                'box_shadow' => 'lg',
                'aspect_ratio' => '16/9',
                'object_fit' => 'cover',
            ],
        ]);

        self::assertTrue($image->shouldRender());
        self::assertFalse($image->isBare());
        self::assertSame('lazy', $image->getLoading());
        self::assertSame('Hero caption text', $image->getCaption());
        self::assertSame('Hero Image', $image->getAlt());
        self::assertSame('Hero Hover', $image->getHoverAlt());
        self::assertSame('object-position: 50% 0%;', $image->getImageFocusPointStyle());

        $imgClasses = $image->getImgClasses();
        self::assertStringContainsString('w-full h-full', $imgClasses);
        self::assertStringContainsString('object-cover', $imgClasses);

        // Bare mode with custom imgClass and hoverImgClass overrides
        $bareImage = new Image();
        $bareImage->mergeProps([
            'bare' => true,
            'img_class' => 'w-full h-full object-cover custom-bare-img',
            'hover_img_class' => 'custom-hover-img',
            'loading' => 'eager',
            'image_focus_style' => 'object-position: 50% 100%;',
            'image' => ['url' => '/uploads/slide.jpg', 'title' => 'Slide'],
            'hover_image' => ['url' => '/uploads/slide-hover.jpg'],
        ]);

        self::assertTrue($bareImage->isBare());
        self::assertSame('eager', $bareImage->getLoading());
        self::assertSame('object-position: 50% 100%;', $bareImage->getImageFocusPointStyle());
        self::assertSame('w-full h-full object-cover custom-bare-img', $bareImage->getImgClasses());
        self::assertSame('custom-hover-img', $bareImage->getHoverImgClasses());
    }

    public function testHoverEffectAndBoxShadow(): void
    {
        $image = new Image();
        $image->mergeProps([
            'image' => ['url' => '/uploads/photo.jpg'],
            'props' => [
                'hover_effect' => 'zoom',
                'box_shadow' => 'xl',
                'rounded' => '2xl',
            ],
        ]);

        self::assertStringContainsString('shadow-xl', $image->getMediaContainerClasses());
        self::assertStringContainsString('rounded-2xl', $image->getMediaContainerClasses());
        self::assertStringContainsString('hover:scale-105', $image->getHoverEffectClasses());
        self::assertStringContainsString('hover:scale-105', $image->getImgClasses());
        self::assertSame('', $image->getWrapperClasses());
        self::assertSame('block w-full', $image->getBlockClasses());
    }

    public function testImageLoadingAndFetchPriority(): void
    {
        $lazyImage = new Image();
        $lazyImage->mergeProps([
            'image' => ['url' => '/uploads/below-fold.jpg'],
        ]);

        self::assertFalse($lazyImage->isImageEager());
        self::assertSame('lazy', $lazyImage->getLoading());
        self::assertNull($lazyImage->getFetchPriority());

        $eagerImage = new Image();
        $eagerImage->mergeProps([
            'image' => ['url' => '/uploads/lcp-hero.jpg'],
            'props' => [
                'image_loading' => true,
            ],
        ]);

        self::assertTrue($eagerImage->isImageEager());
        self::assertSame('eager', $eagerImage->getLoading());
        self::assertSame('high', $eagerImage->getFetchPriority());

        $offscreenImage = new Image();
        $offscreenImage->mergeProps([
            'image' => ['url' => '/uploads/modal.jpg'],
            'loading' => 'lazy',
            'fetchpriority' => 'low',
        ]);

        self::assertFalse($offscreenImage->isImageEager());
        self::assertSame('lazy', $offscreenImage->getLoading());
        self::assertSame('low', $offscreenImage->getFetchPriority());
    }
}
