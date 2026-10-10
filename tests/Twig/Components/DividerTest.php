<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Divider;

final class DividerTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $divider = new Divider();

        self::assertNull($divider->icon);
        self::assertNull($divider->getResolvedIcon());
        self::assertSame('line', $divider->getDividerStyle());
        self::assertStringContainsString('border-gray-200', $divider->getDividerColorClasses());
        self::assertStringContainsString('border-t', $divider->getDividerThicknessClasses());
        self::assertSame('w-full', $divider->getDividerWidthClasses());
        self::assertStringContainsString('justify-center', $divider->getDividerAlignClasses());
    }

    public function testStylesAndThickness(): void
    {
        $divider = new Divider();

        $divider->props = ['divider_style' => 'dashed'];
        self::assertSame('dashed', $divider->getDividerStyle());

        $divider->props = ['divider_style' => 'dotted'];
        self::assertSame('dotted', $divider->getDividerStyle());

        $divider->props = ['divider_style' => 'small'];
        self::assertSame('small', $divider->getDividerStyle());
        self::assertSame('w-16', $divider->getDividerWidthClasses());

        $divider->props = ['divider_style' => 'icon'];
        self::assertSame('icon', $divider->getDividerStyle());

        $divider->props = ['divider_thickness' => '2'];
        self::assertStringContainsString('border-t-2', $divider->getDividerThicknessClasses());

        $divider->props = ['divider_thickness' => '4'];
        self::assertStringContainsString('border-t-4', $divider->getDividerThicknessClasses());
    }

    public function testColors(): void
    {
        $divider = new Divider();

        $divider->props = ['divider_color' => 'primary'];
        self::assertStringContainsString('border-blue-600', $divider->getDividerColorClasses());

        $divider->props = ['divider_color' => 'dark'];
        self::assertStringContainsString('border-gray-900', $divider->getDividerColorClasses());

        $divider->props = ['divider_color' => 'muted'];
        self::assertStringContainsString('border-gray-100', $divider->getDividerColorClasses());
    }

    public function testWidthAndAlignment(): void
    {
        $divider = new Divider();

        $divider->props = ['divider_width' => '1-2'];
        self::assertSame('w-1/2', $divider->getDividerWidthClasses());

        $divider->props = ['divider_width' => '3-4'];
        self::assertSame('w-3/4', $divider->getDividerWidthClasses());

        $divider->props = ['divider_align' => 'left'];
        self::assertStringContainsString('justify-start', $divider->getDividerAlignClasses());

        $divider->props = ['divider_align' => 'right'];
        self::assertStringContainsString('justify-end', $divider->getDividerAlignClasses());
    }

    public function testIconResolution(): void
    {
        $divider = new Divider();
        $divider->icon = 'star';
        self::assertSame('ti ti-star', $divider->getResolvedIcon());
        self::assertSame('icon', $divider->getDividerStyle());

        $divider->icon = 'ti ti-heart';
        self::assertSame('ti ti-heart', $divider->getResolvedIcon());

        $divider->icon = ['icon' => 'check'];
        self::assertSame('ti ti-check', $divider->getResolvedIcon());

        // Sulu single_icon_selection format
        $divider->icon = ['name' => 'star', 'icon_set' => 'tabler'];
        self::assertSame('ti ti-star', $divider->getResolvedIcon());
    }

    public function testIconAlignmentAndLineClasses(): void
    {
        $dividerLeft = new Divider();
        $dividerLeft->props = ['divider_align' => 'left'];
        $leftClasses = $dividerLeft->getIconLineClasses();
        self::assertStringContainsString('after:flex-1', $leftClasses);
        self::assertStringNotContainsString('before:flex-1', $leftClasses);

        $dividerRight = new Divider();
        $dividerRight->props = ['divider_align' => 'right'];
        $rightClasses = $dividerRight->getIconLineClasses();
        self::assertStringContainsString('before:flex-1', $rightClasses);
        self::assertStringNotContainsString('after:flex-1', $rightClasses);

        $dividerCenter = new Divider();
        $dividerCenter->props = ['divider_align' => 'center'];
        $centerClasses = $dividerCenter->getIconLineClasses();
        self::assertStringContainsString('before:flex-1', $centerClasses);
        self::assertStringContainsString('after:flex-1', $centerClasses);

        $dividerPrimary = new Divider();
        $dividerPrimary->props = ['divider_color' => 'primary'];
        self::assertStringContainsString('text-blue-600', $dividerPrimary->getIconBadgeColorClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $divider = new Divider();
        $divider->props = [
            'id' => 'section-divider',
            'custom_class' => 'my-divider',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
        ];

        self::assertSame('section-divider', $divider->getElementId());
        $wrapper = $divider->getWrapperClasses();
        self::assertStringContainsString('flex items-center', $wrapper);
        self::assertStringContainsString('mt-10', $wrapper);
        self::assertStringContainsString('mb-16', $wrapper);
        self::assertStringContainsString('my-divider', $wrapper);
    }
}
