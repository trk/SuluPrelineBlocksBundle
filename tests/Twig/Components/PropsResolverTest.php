<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PropsResolver;

final class PropsResolverTest extends TestCase
{
    public function testTextAlign(): void
    {
        self::assertSame('text-left', PropsResolver::textAlign(['text_align' => 'left']));
        self::assertSame('text-center', PropsResolver::textAlign(['text_align' => 'center']));
        self::assertSame('text-right', PropsResolver::textAlign(['text_align' => 'right']));
        self::assertSame('text-justify', PropsResolver::textAlign(['text_align' => 'justify']));
        self::assertSame('text-left', PropsResolver::textAlign(['text_align' => 'invalid_value']));
        self::assertSame('text-left', PropsResolver::textAlign([]));
    }

    public function testTitleColor(): void
    {
        self::assertSame('text-foreground', PropsResolver::titleColor(['title_color' => 'default']));
        self::assertSame('text-white', PropsResolver::titleColor(['title_color' => 'white']));
        self::assertSame('text-primary', PropsResolver::titleColor(['title_color' => 'primary']));
        self::assertSame('text-secondary', PropsResolver::titleColor(['title_color' => 'secondary']));
        self::assertSame('text-muted-foreground-1', PropsResolver::titleColor(['title_color' => 'muted']));
        self::assertSame('text-foreground', PropsResolver::titleColor(['title_color' => 'unknown']));
        self::assertSame('text-foreground', PropsResolver::titleColor([]));
    }

    public function testLinkColor(): void
    {
        self::assertSame('text-primary hover:text-primary-hover', PropsResolver::linkColor(['link_color' => 'primary']));
        self::assertSame('text-foreground hover:text-muted-foreground-2', PropsResolver::linkColor(['link_color' => 'dark']));
        self::assertSame('text-primary hover:text-primary-hover', PropsResolver::linkColor([]));
    }

    public function testBgColor(): void
    {
        self::assertSame('bg-card', PropsResolver::bgColor(['bg_color' => 'white']));
        self::assertSame('bg-surface', PropsResolver::bgColor(['bg_color' => 'muted']));
        self::assertSame('bg-transparent', PropsResolver::bgColor(['bg_color' => 'transparent']));
        self::assertSame('bg-transparent', PropsResolver::bgColor(['bg_color' => 'unknown']));
        self::assertSame('bg-transparent', PropsResolver::bgColor([]));
    }

    public function testTextColor(): void
    {
        self::assertSame('text-muted-foreground-1', PropsResolver::textColor(['text_color' => 'muted']));
        self::assertSame('text-white', PropsResolver::textColor(['text_color' => 'white']));
        self::assertSame('text-primary', PropsResolver::textColor(['text_color' => 'primary']));
        self::assertSame('text-muted-foreground-2', PropsResolver::textColor(['text_color' => 'default']));
        self::assertSame('text-muted-foreground-2', PropsResolver::textColor([]));
    }

    public function testButtonClasses(): void
    {
        $primaryClasses = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'primary']);
        self::assertStringContainsString('bg-primary', $primaryClasses);
        self::assertStringContainsString('text-sm', $primaryClasses);

        $secondarySolid = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'secondary']);
        self::assertStringContainsString('bg-secondary', $secondarySolid);

        $darkSolid = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'dark']);
        self::assertStringContainsString('bg-inverse', $darkSolid);

        $outlineClasses = PropsResolver::buttonClasses(['variant' => 'outline', 'size' => 'lg', 'link_color' => 'secondary']);
        self::assertStringContainsString('border-secondary-line', $outlineClasses);
        self::assertStringContainsString('text-base', $outlineClasses);

        $ghostClasses = PropsResolver::buttonClasses(['variant' => 'ghost', 'size' => 'sm', 'link_color' => 'dark']);
        self::assertStringContainsString('text-foreground', $ghostClasses);
        self::assertStringContainsString('text-xs', $ghostClasses);

        $secondaryAlias = PropsResolver::buttonClasses(['variant' => 'secondary', 'size' => 'sm']);
        self::assertStringContainsString('bg-secondary', $secondaryAlias);
    }

    public function testResolveIcon(): void
    {
        self::assertNull(PropsResolver::resolveIcon(null));
        self::assertNull(PropsResolver::resolveIcon(''));
        self::assertNull(PropsResolver::resolveIcon('   '));

        // Strings
        self::assertSame('ti ti-play', PropsResolver::resolveIcon('play'));
        self::assertSame('ti ti-star', PropsResolver::resolveIcon('star'));
        self::assertSame('ti ti-heart', PropsResolver::resolveIcon('ti ti-heart'));
        self::assertSame('ti ti-heart', PropsResolver::resolveIcon('ti-heart'));
        self::assertSame('su-play', PropsResolver::resolveIcon('su-play'));
        self::assertSame('fa-solid fa-star', PropsResolver::resolveIcon('fa-solid fa-star'));
        self::assertSame('bi-heart', PropsResolver::resolveIcon('bi-heart'));

        // Arrays (from single_icon_selection resolver)
        self::assertSame('ti ti-star', PropsResolver::resolveIcon(['name' => 'star', 'icon_set' => 'tabler']));
        self::assertSame('ti ti-player-play', PropsResolver::resolveIcon(['name' => 'player-play']));
        self::assertSame('ti ti-check', PropsResolver::resolveIcon(['icon' => 'check']));
        self::assertSame('ti ti-heart', PropsResolver::resolveIcon(['id' => 'heart']));
        self::assertSame('su-star', PropsResolver::resolveIcon(['name' => 'su-star']));
        self::assertNull(PropsResolver::resolveIcon([]));
        self::assertNull(PropsResolver::resolveIcon(['name' => '']));

        // Objects
        $objWithName = new class {
            public function getName(): string
            {
                return 'phone';
            }
        };
        self::assertSame('ti ti-phone', PropsResolver::resolveIcon($objWithName));

        $objWithIcon = new class {
            public function getIcon(): string
            {
                return 'su-video';
            }
        };
        self::assertSame('su-video', PropsResolver::resolveIcon($objWithIcon));

        $stringable = new class implements \Stringable {
            public function __toString(): string
            {
                return 'ti ti-home';
            }
        };
        self::assertSame('ti ti-home', PropsResolver::resolveIcon($stringable));
    }
}
