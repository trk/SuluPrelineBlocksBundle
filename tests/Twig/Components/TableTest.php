<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Table;

final class TableTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $table = new Table();

        self::assertNull($table->title);
        self::assertNull($table->header_title);
        self::assertNull($table->header_meta);
        self::assertNull($table->header_content);
        self::assertNull($table->header_badge);
        self::assertNull($table->header_link);
        self::assertEmpty($table->getNormalizedItems());
        self::assertSame('default', $table->getStyle());
        self::assertSame('md', $table->getSize());
        self::assertFalse($table->isStickyHeader());
        self::assertFalse($table->isHover());
        self::assertFalse($table->hasHeader());
        self::assertSame('overflow-x-auto w-full', $table->getWrapperClasses());
        self::assertSame('min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-start', $table->getTableClasses());
        self::assertSame('bg-gray-50 dark:bg-neutral-800', $table->getTheadClasses());
    }

    public function testHeaderPresence(): void
    {
        $table = new Table();
        $table->header_title = 'Product';

        self::assertTrue($table->hasHeader());
    }

    public function testStylesAndSizes(): void
    {
        $table = new Table();
        $table->props = [
            'style' => 'striped',
            'size' => 'lg',
            'sticky_header' => true,
            'bordered' => true,
        ];

        self::assertSame('striped', $table->getStyle());
        self::assertSame('lg', $table->getSize());
        self::assertTrue($table->isStickyHeader());
        self::assertTrue($table->isHover());
        self::assertStringContainsString('border border-gray-200', $table->getWrapperClasses());
        self::assertStringContainsString('sticky top-0 z-10', $table->getTheadClasses());
        self::assertStringContainsString('px-6 py-4 text-base', $table->getThClasses());
        self::assertStringContainsString('px-6 py-4 text-base', $table->getTdClasses());
        self::assertStringContainsString('bg-white', $table->getRowClasses(0));
        self::assertStringContainsString('bg-gray-50/70', $table->getRowClasses(1));
    }

    public function testNormalizedItems(): void
    {
        $table = new Table();
        $table->items = [
            [
                'title' => 'Alpha',
                'meta' => 'Sub alpha',
                'content' => 'Description of alpha',
                'badge' => 'Active',
                'image' => ['url' => '/media/alpha.png'],
                'link' => '/detail/alpha',
                'link_title' => 'Details',
            ],
            [
                'title' => 'Beta',
                'meta' => 'Sub beta',
                'content' => 'Description of beta',
                'badge' => '',
                'image' => '/media/beta.jpg',
                'link' => ['url' => '/detail/beta'],
            ],
        ];

        $items = $table->getNormalizedItems();

        self::assertCount(2, $items);
        self::assertSame('Alpha', $items[0]['title']);
        self::assertSame('/media/alpha.png', $items[0]['image_url']);
        self::assertSame('/detail/alpha', $items[0]['link']);
        self::assertSame('Details', $items[0]['link_title']);

        self::assertSame('Beta', $items[1]['title']);
        self::assertSame('/media/beta.jpg', $items[1]['image_url']);
        self::assertSame('/detail/beta', $items[1]['link']);
        self::assertSame('View', $items[1]['link_title']);
    }
}
