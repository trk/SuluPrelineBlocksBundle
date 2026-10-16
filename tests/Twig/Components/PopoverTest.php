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

        self::assertNull($popover->get('title'));
        self::assertNull($popover->get('image'));
        self::assertNull($popover->getImageUrl());
        self::assertEmpty($popover->getNormalizedItems());
        self::assertSame('click', $popover->getResolvedTrigger());
        self::assertSame('pulse', $popover->getResolvedDefaultMarkerType());
        self::assertSame('primary', $popover->getResolvedDefaultMarkerColor());
        self::assertStringContainsString('rounded-full', $popover->getHotspotClasses());
    }

    public function testImageUrlExtraction(): void
    {
        $popover = new Popover();
        $popover->assign('image', ['url' => '/media/map-background.jpg']);

        self::assertSame('/media/map-background.jpg', $popover->getImageUrl());

        $popover->assign('image', '/media/direct.png');
        self::assertSame('/media/direct.png', $popover->getImageUrl());
    }

    public function testNormalizedHotspotItemsWithLegacyPositions(): void
    {
        $popover = new Popover();
        $popover->assign('trigger', 'click');
        $popover->assign('markerType', 'pulse');
        $popover->assign('markerColor', 'primary');
        $popover->assign('items', [
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
        ]);

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

    public function testNormalizedHotspotItemsWithBlockInheritanceAndOverrides(): void
    {
        $popover = new Popover();
        $popover->assign('trigger', 'hover');
        $popover->assign('markerType', 'plus');
        $popover->assign('markerColor', 'accent');

        $popover->assign('items', [
            // Item 1: Inherits block defaults
            [
                'hotspot' => ['x' => 30.0, 'y' => 40.0],
                'marker_type' => 'inherit',
                'marker_color' => 'inherit',
                'title' => 'Inherited Marker',
            ],
            // Item 2: Overrides color to danger and type to custom icon
            [
                'hotspot' => ['x' => 70.0, 'y' => 80.0],
                'marker_type' => 'icon',
                'marker_color' => 'danger',
                'icon' => 'ti ti-shopping-cart',
                'title' => 'Featured Product',
                'meta' => '$199',
            ],
        ]);

        $items = $popover->getNormalizedItems();

        self::assertCount(2, $items);

        // Item 1 inherits block settings
        self::assertSame(30.0, $items[0]['x']);
        self::assertSame(40.0, $items[0]['y']);
        self::assertSame('plus', $items[0]['marker_type']);
        self::assertSame('accent', $items[0]['marker_color']);
        self::assertSame('hover', $items[0]['trigger']);
        self::assertNull($items[0]['icon_class']);

        // Item 2 overrides color and type with custom icon
        self::assertSame(70.0, $items[1]['x']);
        self::assertSame(80.0, $items[1]['y']);
        self::assertSame('icon', $items[1]['marker_type']);
        self::assertSame('danger', $items[1]['marker_color']);
        self::assertSame('hover', $items[1]['trigger']);
        self::assertSame('ti ti-shopping-cart', $items[1]['icon_class']);
        self::assertSame('$199', $items[1]['meta']);
    }

    public function testMarkerColorClasses(): void
    {
        $popover = new Popover();

        $primary = $popover->getMarkerColorClasses('primary');
        self::assertStringContainsString('bg-primary', $primary['inner']);

        $dark = $popover->getMarkerColorClasses('dark');
        self::assertStringContainsString('bg-inverse', $dark['inner']);

        $light = $popover->getMarkerColorClasses('light');
        self::assertStringContainsString('bg-layer', $light['inner']);

        $accent = $popover->getMarkerColorClasses('accent');
        self::assertStringContainsString('bg-amber-500', $accent['inner']);

        $success = $popover->getMarkerColorClasses('success');
        self::assertStringContainsString('bg-emerald-600', $success['inner']);

        $danger = $popover->getMarkerColorClasses('danger');
        self::assertStringContainsString('bg-destructive', $danger['inner']);
    }
}
