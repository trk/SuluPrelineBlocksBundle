<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Accordion;

final class AccordionTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $accordion = new Accordion();

        self::assertNull($accordion->title);
        self::assertEmpty($accordion->getNormalizedItems());
        self::assertSame('default', $accordion->getStyle());
        self::assertSame('chevron', $accordion->getIconStyle());
        self::assertFalse($accordion->isMultiple());
        self::assertTrue($accordion->isCollapsible());
        self::assertSame('text-base font-semibold', $accordion->getTitleSizeClasses());
        self::assertSame('top', $accordion->getImageAlign());
        self::assertSame('divide-y divide-gray-200 dark:divide-neutral-700', $accordion->getContainerClasses());
        self::assertSame('hs-accordion pt-4 pb-3', $accordion->getItemClasses());
        self::assertSame('pt-2 pb-1', $accordion->getContentWrapperClasses());
        self::assertStringContainsString('hs-accordion-toggle', $accordion->getToggleClasses());
        self::assertSame('', $accordion->getMarginClass());
        self::assertNull($accordion->getCustomId());
        self::assertSame('', $accordion->getCustomClass());
    }

    public function testStylesAndVariants(): void
    {
        $accordion = new Accordion();
        $accordion->props = [
            'style' => 'bordered',
            'icon_style' => 'plus',
            'multiple' => true,
            'collapsible' => false,
            'title_size' => 'lg',
            'image_align' => 'left',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'id' => 'faq-accordion',
            'custom_class' => 'my-faq',
        ];

        self::assertSame('bordered', $accordion->getStyle());
        self::assertSame('plus', $accordion->getIconStyle());
        self::assertTrue($accordion->isMultiple());
        self::assertFalse($accordion->isCollapsible());
        self::assertSame('text-lg font-semibold', $accordion->getTitleSizeClasses());
        self::assertSame('left', $accordion->getImageAlign());
        self::assertSame('space-y-3', $accordion->getContainerClasses());
        self::assertStringContainsString('border', $accordion->getItemClasses());
        self::assertSame('pt-3', $accordion->getContentWrapperClasses());
        self::assertSame('mt-8 mb-12', $accordion->getMarginClass());
        self::assertSame('faq-accordion', $accordion->getCustomId());
        self::assertSame('my-faq', $accordion->getCustomClass());
    }

    public function testSoftStyleAndTitleSizes(): void
    {
        $accordion = new Accordion();
        $accordion->props = [
            'style' => 'soft',
            'icon_style' => 'none',
            'title_size' => 'xl',
            'image_align' => 'right',
        ];

        self::assertSame('soft', $accordion->getStyle());
        self::assertSame('none', $accordion->getIconStyle());
        self::assertSame('text-xl font-bold', $accordion->getTitleSizeClasses());
        self::assertSame('right', $accordion->getImageAlign());
        self::assertStringContainsString('bg-gray-100', $accordion->getItemClasses());

        $accordionSm = new Accordion();
        $accordionSm->titleSize = 'sm';
        self::assertSame('text-sm font-medium', $accordionSm->getTitleSizeClasses());
    }

    public function testNormalizedItemsAndPropsUnpacking(): void
    {
        $accordion = new Accordion();
        $accordion->items = [
            ['title' => 'Item 1', 'content' => '<p>Answer 1</p>', 'is_active' => true],
            ['title' => 'Item 2', 'content' => '<p>Answer 2</p>'],
            'invalid_item_ignored',
        ];
        $accordion->props = [
            'props' => [
                'style' => 'bordered',
                'title_size' => 'lg',
            ],
        ];

        $items = $accordion->getNormalizedItems();
        self::assertCount(2, $items);
        self::assertSame('Item 1', $items[0]['title']);
        self::assertSame('Item 2', $items[1]['title']);
        self::assertSame('bordered', $accordion->getStyle());
        self::assertSame('text-lg font-semibold', $accordion->getTitleSizeClasses());
    }
}
