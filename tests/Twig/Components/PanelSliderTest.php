<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PanelSlider;

final class PanelSliderTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $slider = new PanelSlider();

        self::assertNull($slider->get('title'));
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
        self::assertStringContainsString('"speed":4000', $slider->getCarouselConfigJson());
        self::assertSame('', $slider->getMarginClass());
        self::assertNull($slider->getCustomId());
        self::assertSame('', $slider->getCustomClass());
    }

    public function testPropsUnpackingAndConfig(): void
    {
        $slider = new PanelSlider();
        $slider->mergeProps([
            'autoplay' => true,
            'interval' => 3000,
            'loop' => false,
            'arrows' => 'outside',
            'dots' => 'none',
            'columns' => '4',
            'gap' => 'lg',
            'margin_top' => 'md',
            'margin_bottom' => 'xl',
            'id' => 'featured-slider',
            'custom_class' => 'my-slider',
        ]);

        self::assertTrue($slider->isAutoplay());
        self::assertSame(3000, $slider->getInterval());
        self::assertFalse($slider->isLoop());
        self::assertSame('outside', $slider->getArrows());
        self::assertSame('none', $slider->getDots());
        self::assertSame('4', $slider->getColumns());
        self::assertSame('w-full sm:w-1/2 lg:w-1/4', $slider->getSlideWidthClass());
        self::assertSame('px-4', $slider->getGapClass());
        self::assertStringContainsString('"isAutoPlay":true', $slider->getCarouselConfigJson());
        self::assertStringContainsString('"isInfiniteLoop":false', $slider->getCarouselConfigJson());
        self::assertSame('mt-6 mb-16', $slider->getMarginClass());
        self::assertSame('featured-slider', $slider->getCustomId());
        self::assertSame('my-slider', $slider->getCustomClass());
    }
}
