<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Icon;

final class IconTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $icon = new Icon();

        self::assertNull($icon->icon);
        self::assertNull($icon->link);
        self::assertNull($icon->getResolvedIcon());
        self::assertNull($icon->getUrl());
        self::assertFalse($icon->hasLink());
        self::assertSame('plain', $icon->getIconStyle());
        self::assertSame('md', $icon->getIconSize());
        self::assertStringContainsString('justify-start', $icon->getAlignClasses());
    }

    public function testIconAndLinkResolution(): void
    {
        $icon = new Icon();
        $icon->icon = 'star';
        $icon->link = 'https://totoglu.com';

        self::assertSame('ti ti-star', $icon->getResolvedIcon());
        self::assertSame('https://totoglu.com', $icon->getUrl());
        self::assertTrue($icon->hasLink());
        self::assertStringContainsString('ti ti-star', $icon->getIconClasses());
    }

    public function testStylesAndContainers(): void
    {
        $icon = new Icon();
        $icon->icon = 'check';
        $icon->props = [
            'icon_style' => 'circle_solid',
            'icon_size' => 'lg',
            'icon_color' => 'primary',
            'align' => 'center',
        ];

        self::assertSame('circle_solid', $icon->getIconStyle());
        self::assertSame('lg', $icon->getIconSize());
        self::assertStringContainsString('rounded-full', $icon->getContainerClasses());
        self::assertStringContainsString('bg-blue-600', $icon->getContainerClasses());
        self::assertStringContainsString('w-16 h-16', $icon->getContainerClasses());
        self::assertStringContainsString('justify-center', $icon->getAlignClasses());
    }

    public function testSquareSoftStyle(): void
    {
        $icon = new Icon();
        $icon->props = [
            'icon_style' => 'square_soft',
            'icon_color' => 'secondary',
        ];

        self::assertStringContainsString('rounded-xl', $icon->getContainerClasses());
        self::assertStringContainsString('bg-indigo-50', $icon->getContainerClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $icon = new Icon();
        $icon->props = [
            'id' => 'feature-icon',
            'margin_top' => 'sm',
            'margin_bottom' => 'md',
            'custom_class' => 'my-icon',
        ];

        self::assertSame('feature-icon', $icon->getElementId());
        $wrapper = $icon->getWrapperClasses();
        self::assertStringContainsString('flex items-center', $wrapper);
        self::assertStringContainsString('mt-4', $wrapper);
        self::assertStringContainsString('mb-6', $wrapper);
        self::assertStringContainsString('my-icon', $wrapper);
    }
}
