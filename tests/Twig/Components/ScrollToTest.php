<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ScrollTo;

final class ScrollToTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $scroll = new ScrollTo();

        self::assertNull($scroll->title);
        self::assertNull($scroll->targetId);
        self::assertSame('top', $scroll->getTargetId());
        self::assertSame('#top', $scroll->getTargetHref());
        self::assertTrue($scroll->isTop());
        self::assertSame('ti ti-arrow-up', $scroll->getResolvedIcon());
        self::assertSame('inline_button', $scroll->getDisplayMode());
        self::assertStringContainsString('justify-end', $scroll->getAlignClasses());
    }

    public function testCustomTargetId(): void
    {
        $scroll = new ScrollTo();
        $scroll->targetId = 'buraya-git';
        self::assertSame('buraya-git', $scroll->getTargetId());
        self::assertSame('#buraya-git', $scroll->getTargetHref());
        self::assertFalse($scroll->isTop());
        self::assertSame('ti ti-arrow-down', $scroll->getResolvedIcon());

        // Target ID with leading hash (#)
        $scrollHash = new ScrollTo();
        $scrollHash->target_id = '#iletisim-formu';
        self::assertSame('iletisim-formu', $scrollHash->getTargetId());
        self::assertSame('#iletisim-formu', $scrollHash->getTargetHref());
    }

    public function testCustomIcon(): void
    {
        $scroll = new ScrollTo();
        $scroll->icon = 'chevron-up';
        self::assertSame('ti ti-chevron-up', $scroll->getResolvedIcon());
    }

    public function testDisplayModesAndStyles(): void
    {
        $scrollCircle = new ScrollTo();
        $scrollCircle->props = [
            'display_mode' => 'totop_circle',
            'button_color' => 'dark',
        ];
        self::assertSame('totop_circle', $scrollCircle->getDisplayMode());
        self::assertStringContainsString('rounded-full', $scrollCircle->getButtonClasses());
        self::assertStringContainsString('bg-gray-900', $scrollCircle->getButtonClasses());

        $scrollFloating = new ScrollTo();
        $scrollFloating->props = ['display_mode' => 'floating'];
        self::assertSame('floating', $scrollFloating->getDisplayMode());
        self::assertStringContainsString('fixed bottom-6 end-6', $scrollFloating->getWrapperClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $scroll = new ScrollTo();
        $scroll->props = [
            'id' => 'my-scroll-btn',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'custom_class' => 'my-scroll',
        ];

        self::assertSame('my-scroll-btn', $scroll->getElementId());
        $wrapper = $scroll->getWrapperClasses();
        self::assertStringContainsString('flex items-center', $wrapper);
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('my-scroll', $wrapper);
    }
}
