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
        self::assertSame('text-gray-900 dark:text-white', PropsResolver::titleColor(['title_color' => 'default']));
        self::assertSame('text-white', PropsResolver::titleColor(['title_color' => 'white']));
        self::assertSame('text-primary-600 dark:text-primary-400', PropsResolver::titleColor(['title_color' => 'primary']));
        self::assertSame('text-secondary-600 dark:text-secondary-400', PropsResolver::titleColor(['title_color' => 'secondary']));
        self::assertSame('text-gray-500 dark:text-neutral-400', PropsResolver::titleColor(['title_color' => 'muted']));
        self::assertSame('text-gray-900 dark:text-white', PropsResolver::titleColor(['title_color' => 'unknown']));
        self::assertSame('text-gray-900 dark:text-white', PropsResolver::titleColor([]));
    }

    public function testLinkColor(): void
    {
        self::assertSame('text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300', PropsResolver::linkColor(['link_color' => 'primary']));
        self::assertSame('text-gray-900 hover:text-gray-700 dark:text-white dark:hover:text-neutral-300', PropsResolver::linkColor(['link_color' => 'dark']));
        self::assertSame('text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300', PropsResolver::linkColor([]));
    }

    public function testBgColor(): void
    {
        self::assertSame('bg-white dark:bg-neutral-900', PropsResolver::bgColor(['bg_color' => 'white']));
        self::assertSame('bg-gray-50 dark:bg-neutral-800', PropsResolver::bgColor(['bg_color' => 'muted']));
        self::assertSame('bg-transparent', PropsResolver::bgColor(['bg_color' => 'transparent']));
        self::assertSame('bg-transparent', PropsResolver::bgColor(['bg_color' => 'unknown']));
        self::assertSame('bg-transparent', PropsResolver::bgColor([]));
    }

    public function testTextColor(): void
    {
        self::assertSame('text-gray-500 dark:text-neutral-400', PropsResolver::textColor(['text_color' => 'muted']));
        self::assertSame('text-white', PropsResolver::textColor(['text_color' => 'white']));
        self::assertSame('text-primary-600 dark:text-primary-400', PropsResolver::textColor(['text_color' => 'primary']));
        self::assertSame('text-gray-700 dark:text-neutral-300', PropsResolver::textColor(['text_color' => 'default']));
        self::assertSame('text-gray-700 dark:text-neutral-300', PropsResolver::textColor([]));
    }

    public function testButtonClasses(): void
    {
        $primaryClasses = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'primary']);
        self::assertStringContainsString('bg-primary-600', $primaryClasses);
        self::assertStringContainsString('text-sm', $primaryClasses);

        $secondarySolid = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'secondary']);
        self::assertStringContainsString('bg-secondary-600', $secondarySolid);

        $darkSolid = PropsResolver::buttonClasses(['variant' => 'solid', 'size' => 'md', 'link_color' => 'dark']);
        self::assertStringContainsString('bg-gray-900', $darkSolid);

        $outlineClasses = PropsResolver::buttonClasses(['variant' => 'outline', 'size' => 'lg', 'link_color' => 'secondary']);
        self::assertStringContainsString('border-secondary-600', $outlineClasses);
        self::assertStringContainsString('text-base', $outlineClasses);

        $ghostClasses = PropsResolver::buttonClasses(['variant' => 'ghost', 'size' => 'sm', 'link_color' => 'dark']);
        self::assertStringContainsString('text-gray-900', $ghostClasses);
        self::assertStringContainsString('text-xs', $ghostClasses);
    }

    public function testCardClasses(): void
    {
        $bordered = PropsResolver::cardClasses(['card_variant' => 'bordered', 'bg_color' => 'white']);
        self::assertStringContainsString('border', $bordered);
        self::assertStringContainsString('bg-white', $bordered);

        $glass = PropsResolver::cardClasses(['card_variant' => 'glass', 'bg_color' => 'transparent']);
        self::assertStringContainsString('backdrop-blur', $glass);
    }

    public function testResolveUrl(): void
    {
        self::assertSame('https://totoglu.com', PropsResolver::resolveUrl('https://totoglu.com'));
        self::assertSame('/about-us', PropsResolver::resolveUrl(['url' => '/about-us']));
        self::assertSame('#', PropsResolver::resolveUrl(null));
        self::assertSame('#', PropsResolver::resolveUrl(12345));
    }

    public function testResolveImageUrl(): void
    {
        self::assertSame('https://cdn.totoglu.com/sample.jpg', PropsResolver::resolveImageUrl('https://cdn.totoglu.com/sample.jpg'));
        self::assertSame('/uploads/media/test.png', PropsResolver::resolveImageUrl(['url' => '/uploads/media/test.png']));
        self::assertNull(PropsResolver::resolveImageUrl(null));
        self::assertNull(PropsResolver::resolveImageUrl([]));

        // Object with getUrl()
        $mediaWithUrl = new class {
            public function getUrl(): string
            {
                return '/uploads/media/from-url.png';
            }
        };
        self::assertSame('/uploads/media/from-url.png', PropsResolver::resolveImageUrl($mediaWithUrl));

        // Object with getFormats() fallback
        $mediaWithFormats = new DummyMediaWithFormats();
        self::assertSame('/uploads/media/thumb.png', PropsResolver::resolveImageUrl($mediaWithFormats));

        // Object with empty getUrl and empty getFormats
        $emptyMedia = new DummyEmptyMedia();
        self::assertNull(PropsResolver::resolveImageUrl($emptyMedia));
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

/**
 * @internal
 */
final class DummyMediaWithFormats
{
    public function getUrl(): null
    {
        return null;
    }

    /**
     * @return array<string, string>
     */
    public function getFormats(): array
    {
        return ['thumbnail' => '/uploads/media/thumb.png'];
    }
}

/**
 * @internal
 */
final class DummyEmptyMedia
{
    public function getUrl(): string
    {
        return '';
    }

    /**
     * @return array<string, string>
     */
    public function getFormats(): array
    {
        return [];
    }
}
