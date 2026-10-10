<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Popover;

final class PopoverTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $popover = new Popover();

        self::assertNull($popover->title);
        self::assertNull($popover->image);
        self::assertNull($popover->getImageUrl());
        self::assertEmpty($popover->getNormalizedItems());
        self::assertStringContainsString('rounded-full', $popover->getHotspotClasses());
    }

    public function testImageUrlExtraction(): void
    {
        $popover = new Popover();
        $popover->image = ['url' => '/media/map-background.jpg'];

        self::assertSame('/media/map-background.jpg', $popover->getImageUrl());

        $popover->image = '/media/direct.png';
        self::assertSame('/media/direct.png', $popover->getImageUrl());
    }

    public function testNormalizedHotspotItemsWithLegacyPositions(): void
    {
        $popover = new Popover();
        $popover->items = [
            [
                'position_x' => 25.5,
                'position_y' => 60.0,
                'title' => 'Main Office',
                'meta' => 'Building A',
                'content' => 'Headquarters building',
                'badge' => 'HQ',
                'link' => '/office/hq',
                'link_title' => 'Visit',
            ],
            [
                'position_x' => 150, // Should clamp to 100
                'position_y' => -20, // Should clamp to 0
                'title' => 'Boundary item',
            ],
        ];

        $items = $popover->getNormalizedItems();

        self::assertCount(2, $items);
        self::assertSame(25.5, $items[0]['x']);
        self::assertSame(60.0, $items[0]['y']);
        self::assertSame('Main Office', $items[0]['title']);
        self::assertSame('Building A', $items[0]['meta']);
        self::assertSame('Headquarters building', $items[0]['content']);
        self::assertSame('HQ', $items[0]['badge']);
        self::assertSame('/office/hq', $items[0]['link']);
        self::assertSame('Visit', $items[0]['link_title']);
        self::assertSame('pulse', $items[0]['marker_type']);
        self::assertSame('primary', $items[0]['marker_color']);
        self::assertSame('click', $items[0]['trigger']);

        self::assertSame(100.0, $items[1]['x']);
        self::assertSame(0.0, $items[1]['y']);
        self::assertSame('Learn More', $items[1]['link_title']);
    }

    public function testNormalizedHotspotItemsWithHotspotData(): void
    {
        $popover = new Popover();
        $popover->items = [
            [
                'hotspot' => [
                    'x' => 34.2,
                    'y' => 78.5,
                    'marker_type' => 'plus',
                    'marker_color' => 'accent',
                    'trigger' => 'hover',
                ],
                'title' => 'Interactive Lounge Chair',
                'meta' => '$349',
                'content' => 'Premium ergonomic lounge chair',
                'badge' => 'New',
                'image' => ['url' => '/media/chair.jpg'],
                'video' => ['url' => '/media/chair-tour.mp4'],
                'link' => '/products/chair',
                'link_title' => 'View Product',
            ],
            [
                'hotspot' => '{"x": 12.0, "y": 45.0, "marker_type": "number", "marker_color": "dark"}',
                'title' => 'Desk Lamp',
                'meta' => 'In Stock',
            ],
        ];

        $items = $popover->getNormalizedItems();

        self::assertCount(2, $items);

        self::assertSame(34.2, $items[0]['x']);
        self::assertSame(78.5, $items[0]['y']);
        self::assertSame('plus', $items[0]['marker_type']);
        self::assertSame('accent', $items[0]['marker_color']);
        self::assertSame('hover', $items[0]['trigger']);
        self::assertSame('Interactive Lounge Chair', $items[0]['title']);
        self::assertSame('$349', $items[0]['meta']);
        self::assertSame('/media/chair.jpg', $items[0]['image_url']);
        self::assertSame('/media/chair-tour.mp4', $items[0]['video_url']);
        self::assertSame('/products/chair', $items[0]['link']);
        self::assertSame('View Product', $items[0]['link_title']);

        self::assertSame(12.0, $items[1]['x']);
        self::assertSame(45.0, $items[1]['y']);
        self::assertSame('number', $items[1]['marker_type']);
        self::assertSame('dark', $items[1]['marker_color']);
        self::assertSame('In Stock', $items[1]['meta']);
    }

    public function testMarkerColorClasses(): void
    {
        $popover = new Popover();

        $primary = $popover->getMarkerColorClasses('primary');
        self::assertStringContainsString('bg-primary-600', $primary['inner']);

        $dark = $popover->getMarkerColorClasses('dark');
        self::assertStringContainsString('bg-gray-900', $dark['inner']);

        $light = $popover->getMarkerColorClasses('light');
        self::assertStringContainsString('bg-white', $light['inner']);

        $accent = $popover->getMarkerColorClasses('accent');
        self::assertStringContainsString('bg-amber-500', $accent['inner']);

        $success = $popover->getMarkerColorClasses('success');
        self::assertStringContainsString('bg-emerald-600', $success['inner']);

        $danger = $popover->getMarkerColorClasses('danger');
        self::assertStringContainsString('bg-rose-600', $danger['inner']);
    }
}
