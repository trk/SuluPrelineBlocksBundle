<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ListBlock;

final class ListBlockTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $list = new ListBlock();

        self::assertNull($list->get('title'));
        self::assertEmpty($list->getNormalizedItems());
        self::assertSame('vertical', $list->getListType());
        self::assertSame('none', $list->getMarker());
        self::assertSame('text-primary', $list->getMarkerColor());
        self::assertSame('default', $list->getStyle());
        self::assertSame('md', $list->getSize());
        self::assertSame('space-y-2.5', $list->getSpacingClasses());
        self::assertSame('space-y-2.5', $list->getListClasses());
        self::assertStringContainsString('text-sm sm:text-base', $list->getItemClasses());
        self::assertSame('', $list->getMarginClass());
        self::assertNull($list->getCustomId());
        self::assertSame('', $list->getCustomClass());
    }

    public function testMarkersAndColors(): void
    {
        $list = new ListBlock();
        $list->mergeProps([
            'marker' => 'checkmark',
            'marker_color' => 'secondary',
            'style' => 'striped',
            'size' => 'lg',
            'spacing' => 'lg',
            'columns' => '2',
            'margin_top' => 'md',
            'margin_bottom' => 'xl',
            'id' => 'features-list',
            'custom_class' => 'my-custom-list',
        ]);

        self::assertSame('checkmark', $list->getMarker());
        self::assertSame('text-secondary', $list->getMarkerColor());
        self::assertSame('striped', $list->getStyle());
        self::assertSame('lg', $list->getSize());
        self::assertStringContainsString('grid sm:grid-cols-2', $list->getListClasses());
        self::assertStringContainsString('odd:bg-transparent even:bg-background-1', $list->getItemClasses());
        self::assertSame('mt-6 mb-16', $list->getMarginClass());
        self::assertSame('features-list', $list->getCustomId());
        self::assertSame('my-custom-list', $list->getCustomClass());
    }

    public function testHorizontalListAndDividerStyle(): void
    {
        $list = new ListBlock();
        $list->assign('listType', 'horizontal');
        $list->assign('style', 'divider');
        $list->assign('spacing', 'sm');

        self::assertSame('horizontal', $list->getListType());
        self::assertSame('divider', $list->getStyle());
        self::assertStringContainsString('flex flex-wrap items-center', $list->getListClasses());
        self::assertStringContainsString('divide-x', $list->getListClasses());
        self::assertStringContainsString('px-3 first:ps-0 last:pe-0', $list->getItemClasses());
    }

    public function testItemNormalization(): void
    {
        $list = new ListBlock();
        $list->assign('items', [
            [
                'title' => 'Feature 1',
                'content' => 'Description 1',
                'image' => '/uploads/avatar1.jpg',
                'link' => 'https://example.com/one',
            ],
            [
                'title' => 'Feature 2',
                'image' => [
                    'url' => '/uploads/avatar2.jpg',
                    'focalPoint' => ['x' => 60, 'y' => 40],
                ],
                'link' => ['url' => 'https://example.com/two'],
            ],
            'invalid_item',
        ]);

        $normalized = $list->getNormalizedItems();
        self::assertCount(2, $normalized);
        self::assertSame('/uploads/avatar1.jpg', $normalized[0]['image_url']);
        self::assertSame('https://example.com/one', $normalized[0]['link_href']);
        self::assertSame('/uploads/avatar2.jpg', $normalized[1]['image_url']);
        self::assertSame('object-position: 60% 40%;', $normalized[1]['image_focus_style']);
        self::assertSame('https://example.com/two', $normalized[1]['link_href']);
    }
}
