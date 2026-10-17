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

        self::assertNull($scroll->get('title'));
        self::assertNull($scroll->get('targetId'));
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
        $scroll->assign('targetId', 'buraya-git');
        self::assertSame('buraya-git', $scroll->getTargetId());
        self::assertSame('#buraya-git', $scroll->getTargetHref());
        self::assertFalse($scroll->isTop());
        self::assertSame('ti ti-arrow-down', $scroll->getResolvedIcon());

        // Target ID with leading hash (#)
        $scrollHash = new ScrollTo();
        $scrollHash->assign('target_id', '#iletisim-formu');
        self::assertSame('iletisim-formu', $scrollHash->getTargetId());
        self::assertSame('#iletisim-formu', $scrollHash->getTargetHref());

        // Malicious target ID falls back to 'top'
        $scrollMalicious = new ScrollTo();
        $scrollMalicious->assign('targetId', "');alert(1);//");
        self::assertSame('top', $scrollMalicious->getTargetId());
        self::assertSame('#top', $scrollMalicious->getTargetHref());
    }

    public function testCustomIcon(): void
    {
        $scroll = new ScrollTo();
        $scroll->assign('icon', 'chevron-up');
        self::assertSame('ti ti-chevron-up', $scroll->getResolvedIcon());
    }

    public function testDisplayModesAndStyles(): void
    {
        $scrollCircle = new ScrollTo();
        $scrollCircle->mergeProps([
            'display_mode' => 'totop_circle',
            'button_color' => 'dark',
        ]);
        self::assertSame('totop_circle', $scrollCircle->getDisplayMode());
        self::assertStringContainsString('rounded-full', $scrollCircle->getButtonClasses());
        self::assertStringContainsString('bg-inverse', $scrollCircle->getButtonClasses());

        $scrollFloating = new ScrollTo();
        $scrollFloating->mergeProps(['display_mode' => 'floating']);
        self::assertSame('floating', $scrollFloating->getDisplayMode());
        self::assertTrue($scrollFloating->isFloating());
        self::assertStringContainsString('fixed bottom-6 end-6', $scrollFloating->getWrapperClasses());
        self::assertSame('inline-block', $scrollFloating->getBlockClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $scroll = new ScrollTo();
        $scroll->mergeProps([
            'id' => 'my-scroll-btn',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'custom_class' => 'my-scroll',
        ]);

        self::assertSame('my-scroll-btn', $scroll->getElementId());
        $wrapper = $scroll->getWrapperClasses();
        self::assertStringNotContainsString('flex items-center', $wrapper);
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('my-scroll', $wrapper);

        $blockClasses = $scroll->getBlockClasses();
        self::assertStringContainsString('w-full flex items-center', $blockClasses);
    }
}
