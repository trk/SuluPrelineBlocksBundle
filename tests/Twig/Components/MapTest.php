<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Map;

final class MapTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $map = new Map();

        self::assertNull($map->get('title'));
        self::assertNull($map->get('location_query'));
        self::assertSame(14, $map->getZoom());
        self::assertSame('16/9', $map->get('aspect_ratio'));
        self::assertEmpty($map->get('items'));
        self::assertFalse($map->hasMap());
        self::assertFalse($map->hasItems());
        self::assertSame('', $map->getEmbedUrl());
        self::assertSame('aspect-video', $map->getAspectRatioClasses());
        self::assertSame('', $map->getWrapperClasses());
        self::assertSame('w-full', $map->getBlockClasses());
        self::assertSame('relative w-full rounded-2xl overflow-hidden border border-card-line shadow-xs bg-surface', $map->getMapFrameClasses());
    }

    public function testEmbedUrlGeneration(): void
    {
        $map = new Map();
        $map->assign('location_query', 'Istanbul, Turkey');
        $map->assign('zoom', 12);

        self::assertTrue($map->hasMap());
        self::assertSame('https://maps.google.com/maps?q=Istanbul%2C%20Turkey&z=12&output=embed', $map->getEmbedUrl());
    }

    public function testEmbedUrlFromIframeSrc(): void
    {
        $map = new Map();
        $map->assign('location_query', '<iframe src="https://www.google.com/maps/embed?pb=123&amp;hl=en" width="600"></iframe>');

        self::assertSame('https://www.google.com/maps/embed?pb=123&hl=en', $map->getEmbedUrl());
    }

    public function testEmbedUrlRejectsUntrustedHostsAndSchemes(): void
    {
        $map = new Map();

        $map->assign('location_query', '<iframe src="javascript:alert(1)"></iframe>');
        self::assertSame('', $map->getEmbedUrl());

        $map->assign('location_query', '<iframe src="https://evil.com/google.com/maps"></iframe>');
        self::assertSame('', $map->getEmbedUrl());

        $map->assign('location_query', 'https://evil.com/google.com/maps');
        self::assertSame('', $map->getEmbedUrl());

        $map->assign('location_query', 'javascript:alert(1)');
        self::assertSame('', $map->getEmbedUrl());

        $map->assign('location_query', 'https://www.openstreetmap.org/export/embed.html?bbox=1,2,3,4');
        self::assertSame('https://www.openstreetmap.org/export/embed.html?bbox=1,2,3,4', $map->getEmbedUrl());
    }

    public function testEmbedUrlFallbackToFirstItem(): void
    {
        $map = new Map();
        $map->assign('items', [
            [
                'location' => 'Ankara, Turkey',
                'title' => 'Capital Office',
            ],
        ]);

        self::assertTrue($map->hasMap());
        self::assertSame('https://maps.google.com/maps?q=Ankara%2C%20Turkey&z=14&output=embed', $map->getEmbedUrl());
    }

    public function testAspectRatios(): void
    {
        $map = new Map();

        $map->assign('aspect_ratio', '4/3');
        self::assertSame('aspect-4/3', $map->getAspectRatioClasses());

        $map->assign('aspect_ratio', '21/9');
        self::assertSame('aspect-21/9', $map->getAspectRatioClasses());

        $map->assign('aspect_ratio', '1/1');
        self::assertSame('aspect-square', $map->getAspectRatioClasses());

        $map->assign('aspect_ratio', 'auto');
        self::assertSame('h-96 md:h-[500px]', $map->getAspectRatioClasses());
    }

    public function testNormalizedItems(): void
    {
        $map = new Map();
        $map->assign('items', [
            [
                'location' => 'Levent, Istanbul',
                'title' => 'Headquarters',
                'meta' => 'Open 9:00 - 18:00',
                'content' => '<p>Contact us anytime.</p>',
                'image' => [
                    'url' => '/media/hq.jpg',
                    'formats' => [
                        'col-sm' => '/media/hq-400.webp',
                        'col-lg' => '/media/hq-960.webp',
                    ],
                    'focalPoint' => ['x' => 55, 'y' => 45],
                ],
                'link' => '/contact',
                'link_title' => 'Details',
            ],
        ]);

        self::assertTrue($map->hasItems());
        $items = $map->getNormalizedItems();
        self::assertCount(1, $items);
        self::assertSame('Levent, Istanbul', $items[0]['location']);
        self::assertSame('Headquarters', $items[0]['title']);
        self::assertSame('/media/hq.jpg', $items[0]['image_url']);
        self::assertNotNull($items[0]['image_srcset']);
        self::assertStringContainsString('hq-400.webp 400w', $items[0]['image_srcset']);
        self::assertSame('object-position: 55% 45%;', $items[0]['image_focus_style']);
        self::assertSame('/contact', $items[0]['link']);
        self::assertSame('Details', $items[0]['link_title']);
        self::assertSame('https://google.com/maps/dir/?api=1&destination=Levent%2C%20Istanbul', \str_replace('https://www.google.com', 'https://google.com', $items[0]['directions_url']));
    }
}
