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
        self::assertStringContainsString('rounded-full bg-primary-600', $popover->getHotspotClasses());
    }

    public function testImageUrlExtraction(): void
    {
        $popover = new Popover();
        $popover->image = ['url' => '/media/map-background.jpg'];

        self::assertSame('/media/map-background.jpg', $popover->getImageUrl());

        $popover->image = '/media/direct.png';
        self::assertSame('/media/direct.png', $popover->getImageUrl());
    }

    public function testNormalizedHotspotItems(): void
    {
        $popover = new Popover();
        $popover->items = [
            [
                'position_x' => 25.5,
                'position_y' => 60.0,
                'title' => 'Main Office',
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
        self::assertSame('Headquarters building', $items[0]['content']);
        self::assertSame('HQ', $items[0]['badge']);
        self::assertSame('/office/hq', $items[0]['link']);
        self::assertSame('Visit', $items[0]['link_title']);

        self::assertSame(100.0, $items[1]['x']);
        self::assertSame(0.0, $items[1]['y']);
        self::assertSame('Learn More', $items[1]['link_title']);
    }
}
