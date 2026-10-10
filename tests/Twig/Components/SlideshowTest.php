<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Slideshow;

final class SlideshowTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $slideshow = new Slideshow();

        self::assertNull($slideshow->title);
        self::assertEmpty($slideshow->getNormalizedItems());
        self::assertSame('min-h-[420px] sm:min-h-[500px]', $slideshow->getHeightClass());
        self::assertTrue($slideshow->isAutoplay());
        self::assertSame(5000, $slideshow->getInterval());
        self::assertTrue($slideshow->isLoop());
        self::assertSame('inside', $slideshow->getArrows());
        self::assertSame('dots', $slideshow->getIndicators());
        self::assertSame('text-center items-center', $slideshow->getContentAlign());
        self::assertStringContainsString('"isAutoPlay":true', $slideshow->getCarouselConfigJson());
        self::assertStringContainsString('"speed":5000', $slideshow->getCarouselConfigJson());
        self::assertSame('', $slideshow->getMarginClass());
        self::assertNull($slideshow->getCustomId());
        self::assertSame('', $slideshow->getCustomClass());
    }

    public function testPropsUnpackingAndConfig(): void
    {
        $slideshow = new Slideshow();
        $slideshow->props = [
            'height' => 'full',
            'autoplay' => false,
            'interval' => 7000,
            'loop' => false,
            'arrows' => 'outside',
            'indicators' => 'lines',
            'content_align' => 'left',
            'margin_top' => 'xl',
            'margin_bottom' => 'md',
            'id' => 'hero-slideshow',
            'custom_class' => 'my-hero-carousel',
        ];

        self::assertSame('min-h-screen', $slideshow->getHeightClass());
        self::assertFalse($slideshow->isAutoplay());
        self::assertSame(7000, $slideshow->getInterval());
        self::assertFalse($slideshow->isLoop());
        self::assertSame('outside', $slideshow->getArrows());
        self::assertSame('lines', $slideshow->getIndicators());
        self::assertSame('text-left items-start', $slideshow->getContentAlign());
        self::assertStringContainsString('"isAutoPlay":false', $slideshow->getCarouselConfigJson());
        self::assertStringContainsString('"speed":7000', $slideshow->getCarouselConfigJson());
        self::assertSame('mt-16 mb-8', $slideshow->getMarginClass());
        self::assertSame('hero-slideshow', $slideshow->getCustomId());
        self::assertSame('my-hero-carousel', $slideshow->getCustomClass());
    }

    public function testItemNormalization(): void
    {
        $slideshow = new Slideshow();
        $slideshow->items = [
            [
                'title' => 'Hero Slide 1',
                'image' => '/uploads/hero1.jpg',
                'link' => 'https://example.com/one',
            ],
            [
                'title' => 'Hero Slide 2',
                'image' => ['url' => '/uploads/hero2.jpg'],
                'link' => ['url' => 'https://example.com/two'],
            ],
            'invalid',
        ];

        $items = $slideshow->getNormalizedItems();
        self::assertCount(2, $items);
        self::assertSame('/uploads/hero1.jpg', $items[0]['image_url']);
        self::assertSame('https://example.com/one', $items[0]['link_href']);
        self::assertSame('/uploads/hero2.jpg', $items[1]['image_url']);
        self::assertSame('https://example.com/two', $items[1]['link_href']);
    }
}
