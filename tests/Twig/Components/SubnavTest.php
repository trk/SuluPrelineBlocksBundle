<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Subnav;

final class SubnavTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $subnav = new Subnav();

        self::assertNull($subnav->get('title'));
        self::assertEmpty($subnav->getNormalizedItems());
        self::assertSame('pills', $subnav->getStyle());
        self::assertSame('start', $subnav->getAlignment());
        self::assertSame('md', $subnav->getSize());
        self::assertStringContainsString('justify-start', $subnav->getNavClasses());
        self::assertStringContainsString('gap-2', $subnav->getNavClasses());
        self::assertStringContainsString('bg-primary text-primary-foreground', $subnav->getItemClasses(true));
        self::assertStringContainsString('text-muted-foreground-2 hover:text-foreground', $subnav->getItemClasses(false));
        self::assertSame('', $subnav->getMarginClass());
        self::assertNull($subnav->getElementId());
        self::assertSame('', $subnav->getCustomClass());
    }

    public function testStylesAndAlignments(): void
    {
        $subnav = new Subnav();
        $subnav->mergeProps([
            'style' => 'segmented',
            'alignment' => 'center',
            'size' => 'lg',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'id' => 'tab-nav',
            'custom_class' => 'my-subnav',
        ]);

        self::assertSame('segmented', $subnav->getStyle());
        self::assertSame('center', $subnav->getAlignment());
        self::assertSame('lg', $subnav->getSize());
        self::assertStringContainsString('justify-center', $subnav->getNavClasses());
        self::assertStringContainsString('bg-surface rounded-xl', $subnav->getNavClasses());
        self::assertStringContainsString('bg-layer text-layer-foreground', $subnav->getItemClasses(true));
        self::assertSame('mt-6 mb-10', $subnav->getMarginClass());
        self::assertSame('tab-nav', $subnav->getElementId());
        self::assertSame('my-subnav', $subnav->getCustomClass());

        $subnavUnderline = new Subnav();
        $subnavUnderline->assign('style', 'underline');
        self::assertSame('underline', $subnavUnderline->getStyle());
        self::assertStringContainsString('border-b border-border', $subnavUnderline->getNavClasses());
        self::assertStringContainsString('border-primary text-primary', $subnavUnderline->getItemClasses(true));
    }

    public function testItemNormalization(): void
    {
        $subnav = new Subnav();
        $subnav->assign('items', [
            [
                'title' => 'Overview',
                'link' => '/overview',
                'is_active' => true,
            ],
            [
                'title' => 'Settings',
                'link' => ['url' => '/settings'],
                'badge' => '3',
            ],
            'invalid',
        ]);

        $items = $subnav->getNormalizedItems();
        self::assertCount(2, $items);
        self::assertSame('/overview', $items[0]['link_href']);
        self::assertSame('/settings', $items[1]['link_href']);
    }
}
