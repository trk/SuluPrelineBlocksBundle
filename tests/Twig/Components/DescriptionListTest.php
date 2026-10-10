<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\DescriptionList;

final class DescriptionListTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $dl = new DescriptionList();

        self::assertNull($dl->title);
        self::assertEmpty($dl->getNormalizedItems());
        self::assertSame('horizontal', $dl->getLayout());
        self::assertTrue($dl->isDivider());
        self::assertSame('md', $dl->getSize());
        self::assertSame('sm:w-1/3', $dl->getTermWidthClasses());
        self::assertSame('sm:w-2/3', $dl->getDescWidthClasses());
        self::assertSame('divide-y divide-gray-200 dark:divide-neutral-700', $dl->getListClasses());
        self::assertStringContainsString('sm:flex sm:items-baseline gap-x-4', $dl->getItemRowClasses());
        self::assertStringContainsString('font-semibold', $dl->getTermClasses());
        self::assertStringContainsString('sm:w-2/3 text-sm text-gray-600', $dl->getDescClasses());
        self::assertSame('', $dl->getMarginClass());
        self::assertNull($dl->getCustomId());
        self::assertSame('', $dl->getCustomClass());
    }

    public function testLayoutsAndTermStyles(): void
    {
        $dl = new DescriptionList();
        $dl->props = [
            'layout' => 'striped',
            'term_width' => '1-4',
            'term_style' => 'primary',
            'size' => 'lg',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'id' => 'specs-list',
            'custom_class' => 'my-specs',
        ];

        self::assertSame('striped', $dl->getLayout());
        self::assertSame('sm:w-1/4', $dl->getTermWidthClasses());
        self::assertSame('sm:w-3/4', $dl->getDescWidthClasses());
        self::assertStringContainsString('even:bg-gray-50', $dl->getItemRowClasses());
        self::assertStringContainsString('text-primary-600', $dl->getTermClasses());
        self::assertSame('mt-8 mb-12', $dl->getMarginClass());
        self::assertSame('specs-list', $dl->getCustomId());
        self::assertSame('my-specs', $dl->getCustomClass());
    }

    public function testStackedAndBorderedLayouts(): void
    {
        $dlStacked = new DescriptionList();
        $dlStacked->layout = 'stacked';
        $dlStacked->termStyle = 'muted';
        $dlStacked->divider = false;

        self::assertSame('stacked', $dlStacked->getLayout());
        self::assertFalse($dlStacked->isDivider());
        self::assertSame('space-y-4', $dlStacked->getListClasses());
        self::assertStringContainsString('flex flex-col gap-y-1', $dlStacked->getItemRowClasses());
        self::assertStringContainsString('w-full', $dlStacked->getTermClasses());
        self::assertStringContainsString('text-gray-500', $dlStacked->getTermClasses());
        self::assertStringContainsString('w-full', $dlStacked->getDescClasses());

        $dlBordered = new DescriptionList();
        $dlBordered->props = ['layout' => 'bordered', 'term_width' => '1-2'];
        self::assertSame('bordered', $dlBordered->getLayout());
        self::assertSame('sm:w-1/2', $dlBordered->getTermWidthClasses());
        self::assertSame('sm:w-1/2', $dlBordered->getDescWidthClasses());
        self::assertStringContainsString('border', $dlBordered->getListClasses());
    }

    public function testNormalizedItemsAndPropsUnpacking(): void
    {
        $dl = new DescriptionList();
        $dl->items = [
            ['title' => 'Operating System', 'content' => 'macOS Sequoia', 'meta' => 'Latest'],
            ['title' => 'RAM', 'content' => '32 GB', 'icon' => 'ti ti-cpu'],
            'invalid_item',
        ];
        $dl->props = [
            'props' => [
                'layout' => 'striped',
                'term_style' => 'medium',
            ],
        ];

        $items = $dl->getNormalizedItems();
        self::assertCount(2, $items);
        self::assertSame('Operating System', $items[0]['title']);
        self::assertSame('RAM', $items[1]['title']);
        self::assertSame('striped', $dl->getLayout());
        self::assertStringContainsString('font-medium', $dl->getTermClasses());
    }
}
