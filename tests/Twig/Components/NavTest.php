<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Nav;

final class NavTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $nav = new Nav();

        self::assertNull($nav->title);
        self::assertEmpty($nav->items);
        self::assertFalse($nav->hasItems());
        self::assertSame('flex flex-col space-y-1 w-full', $nav->getNavClasses());
    }

    public function testCardProp(): void
    {
        $nav = new Nav();
        $nav->props = ['card' => true];

        self::assertStringContainsString('border border-card-line', $nav->getNavClasses());
        self::assertStringContainsString('bg-card', $nav->getNavClasses());
    }

    public function testNormalizedItems(): void
    {
        $nav = new Nav();
        $nav->items = [
            [
                'item_type' => 'header',
                'title' => 'Main Navigation',
            ],
            [
                'item_type' => 'link',
                'title' => 'Dashboard',
                'meta' => 'Overview',
                'icon' => 'ri-dashboard-line',
                'badge' => 'New',
                'link' => '/admin/dashboard',
                'is_active' => true,
            ],
            [
                'item_type' => 'divider',
            ],
            [
                'item_type' => 'link',
                'title' => 'External Docs',
                'link' => 'https://example.com/docs',
                'is_active' => false,
            ],
        ];

        self::assertTrue($nav->hasItems());
        $items = $nav->getNormalizedItems();

        self::assertCount(4, $items);

        self::assertSame('header', $items[0]['item_type']);
        self::assertSame('Main Navigation', $items[0]['title']);

        self::assertSame('link', $items[1]['item_type']);
        self::assertSame('Dashboard', $items[1]['title']);
        self::assertSame('Overview', $items[1]['meta']);
        self::assertSame('ri-dashboard-line', $items[1]['icon']);
        self::assertSame('New', $items[1]['badge']);
        self::assertSame('/admin/dashboard', $items[1]['link']);
        self::assertTrue($items[1]['is_active']);
        self::assertFalse($items[1]['is_external']);

        self::assertSame('divider', $items[2]['item_type']);

        self::assertSame('link', $items[3]['item_type']);
        self::assertSame('https://example.com/docs', $items[3]['link']);
        self::assertTrue($items[3]['is_external']);
    }
}
