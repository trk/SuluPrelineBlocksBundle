<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\OverlaySlider;

final class OverlaySliderTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $slider = new OverlaySlider();

        self::assertNull($slider->title);
        self::assertEmpty($slider->getNormalizedItems());
        self::assertFalse($slider->isAutoplay());
        self::assertSame(4000, $slider->getInterval());
        self::assertTrue($slider->isLoop());
        self::assertSame('inside', $slider->getArrows());
        self::assertSame('bottom', $slider->getDots());
        self::assertSame('3', $slider->getColumns());
        self::assertSame('w-full sm:w-1/2 lg:w-1/3', $slider->getSlideWidthClass());
        self::assertSame('px-2.5', $slider->getGapClass());
        self::assertStringContainsString('"isAutoPlay":false', $slider->getCarouselConfigJson());
        self::assertSame('', $slider->getMarginClass());
        self::assertNull($slider->getCustomId());
        self::assertSame('', $slider->getCustomClass());
    }

    public function testPropsUnpackingAndConfig(): void
    {
        $slider = new OverlaySlider();
        $slider->props = [
            'autoplay' => true,
            'interval' => 2500,
            'loop' => true,
            'arrows' => 'none',
            'dots' => 'inside',
            'columns' => '2',
            'gap' => 'none',
            'margin_top' => 'lg',
            'margin_bottom' => 'sm',
            'id' => 'hero-overlays',
            'custom_class' => 'my-overlay-slider',
        ];

        self::assertTrue($slider->isAutoplay());
        self::assertSame(2500, $slider->getInterval());
        self::assertTrue($slider->isLoop());
        self::assertSame('none', $slider->getArrows());
        self::assertSame('inside', $slider->getDots());
        self::assertSame('2', $slider->getColumns());
        self::assertSame('w-full sm:w-1/2', $slider->getSlideWidthClass());
        self::assertSame('px-0', $slider->getGapClass());
        self::assertStringContainsString('"speed":2500', $slider->getCarouselConfigJson());
        self::assertSame('mt-12 mb-4', $slider->getMarginClass());
        self::assertSame('hero-overlays', $slider->getCustomId());
        self::assertSame('my-overlay-slider', $slider->getCustomClass());
    }
}
