<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Badge;

final class BadgeTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $badge = new Badge();
        $badge->title = 'New Feature';

        self::assertSame('New Feature', $badge->title);
        self::assertFalse($badge->isClickable());
        self::assertNull($badge->getUrl());
        self::assertFalse($badge->hasDot());
        self::assertNull($badge->getResolvedIcon());
        self::assertSame('left', $badge->getIconAlign());

        $classes = $badge->getClasses();
        self::assertStringContainsString('inline-flex', $classes);
        self::assertStringContainsString('bg-primary-100', $classes);
        self::assertStringContainsString('text-primary-800', $classes);
        self::assertStringContainsString('rounded-full', $classes);
    }

    public function testClickableBadgeWithLink(): void
    {
        $badge = new Badge();
        $badge->title = 'Clickable';
        $badge->link = '/changelog';

        self::assertTrue($badge->isClickable());
        self::assertSame('/changelog', $badge->getUrl());
    }

    public function testStylesAndColors(): void
    {
        $badge = new Badge();
        $badge->badgeStyle = 'solid';
        $badge->badgeColor = 'danger';
        $badge->size = 'lg';
        $badge->rounded = 'rounded';
        $badge->hasDot = true;

        self::assertTrue($badge->hasDot());
        $classes = $badge->getClasses();
        self::assertStringContainsString('bg-destructive', $classes);
        self::assertStringContainsString('text-destructive-foreground', $classes);
        self::assertStringContainsString('py-2 px-3', $classes);
        self::assertStringContainsString('rounded-md', $classes);

        // Soft style with success
        $badge = new Badge();
        $badge->badgeStyle = 'soft';
        $badge->badgeColor = 'success';
        $classes = $badge->getClasses();
        self::assertStringContainsString('bg-teal-100', $classes);
        self::assertStringContainsString('text-teal-800', $classes);

        // Outline style with warning
        $badge = new Badge();
        $badge->badgeStyle = 'outline';
        $badge->badgeColor = 'warning';
        $classes = $badge->getClasses();
        self::assertStringContainsString('border-yellow-500', $classes);
        self::assertStringContainsString('text-yellow-500', $classes);
    }

    public function testIconAndAlignment(): void
    {
        $badge = new Badge();
        $badge->icon = 'heroicons:star';
        $badge->iconAlign = 'right';

        self::assertSame('ti ti-heroicons:star', $badge->getResolvedIcon());
        self::assertSame('right', $badge->getIconAlign());
    }

    public function testPropsResolution(): void
    {
        $badge = new Badge();
        $badge->props = [
            'badge_style' => 'glass',
            'badge_color' => 'white',
            'size' => 'xs',
            'has_dot' => true,
            'id' => 'promo-badge',
            'custom_class' => 'my-custom-badge',
        ];

        self::assertTrue($badge->hasDot());
        self::assertSame('promo-badge', $badge->getElementId());
        $classes = $badge->getClasses();
        self::assertStringContainsString('backdrop-blur-md', $classes);
        self::assertStringContainsString('my-custom-badge', $classes);
    }
}
