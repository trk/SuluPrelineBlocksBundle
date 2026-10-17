<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Switcher;

final class SwitcherTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $switcher = new Switcher();

        self::assertNull($switcher->get('title'));
        self::assertEmpty($switcher->getNormalizedItems());
        self::assertSame('top', $switcher->getNavPosition());
        self::assertSame('pills', $switcher->getNavStyle());
        self::assertSame('start', $switcher->getAlignment());
        self::assertFalse($switcher->isVertical());
        self::assertStringContainsString('justify-start', $switcher->getTabNavClasses());
        self::assertStringContainsString('hs-tab-active:bg-primary', $switcher->getTabButtonClasses());
        self::assertSame('', $switcher->getMarginClass());
        self::assertNull($switcher->getElementId());
        self::assertSame('', $switcher->getCustomClass());
    }

    public function testVerticalAndUnderlineVariants(): void
    {
        $switcher = new Switcher();
        $switcher->mergeProps([
            'nav_position' => 'left',
            'nav_style' => 'underline',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'id' => 'faq-tabs',
            'custom_class' => 'my-switcher',
        ]);

        self::assertSame('left', $switcher->getNavPosition());
        self::assertTrue($switcher->isVertical());
        self::assertSame('underline', $switcher->getNavStyle());
        self::assertStringContainsString('flex-col space-y-1.5', $switcher->getTabNavClasses());
        self::assertStringContainsString('hs-tab-active:border-primary', $switcher->getTabButtonClasses());
        self::assertSame('mt-6 mb-10', $switcher->getMarginClass());
        self::assertSame('faq-tabs', $switcher->getElementId());
        self::assertSame('my-switcher', $switcher->getCustomClass());
    }

    public function testItemNormalization(): void
    {
        $switcher = new Switcher();
        $switcher->assign('items', [
            [
                'title' => 'Tab 1',
                'nav_title' => 'First',
                'image' => '/uploads/tab1.jpg',
                'link' => 'https://example.com/tab1',
            ],
            [
                'title' => 'Tab 2',
                'image' => [
                    'url' => '/uploads/tab2.jpg',
                    'formats' => [
                        '400w' => '/uploads/tab2-400.webp',
                        '960w' => '/uploads/tab2-960.webp',
                    ],
                    'focalPoint' => ['x' => 45, 'y' => 60],
                ],
                'link' => ['url' => 'https://example.com/tab2'],
            ],
            'invalid',
        ]);

        $items = $switcher->getNormalizedItems();
        self::assertCount(2, $items);
        self::assertSame('/uploads/tab1.jpg', $items[0]['image_url']);
        self::assertSame('https://example.com/tab1', $items[0]['link_href']);
        self::assertSame('/uploads/tab2.jpg', $items[1]['image_url']);
        self::assertIsString($items[1]['image_srcset']);
        self::assertStringContainsString('/uploads/tab2-400.webp 400w', $items[1]['image_srcset']);
        self::assertSame('object-position: 45% 60%;', $items[1]['image_focus_style']);
        self::assertSame('https://example.com/tab2', $items[1]['link_href']);
    }
}
