<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluBlockSuiteBundle\Util\ClassList;
use Totoglu\SuluBlockSuiteBundle\Util\PropReader;

/**
 * Centralized resolver for Preline UI 5 & Tailwind CSS 4 component properties,
 * delegating all static utility class lookups to `PrelineClassMap`.
 */
final class PropsResolver
{
    /**
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props, string $default = 'left'): string
    {
        $align = PropReader::string($props, 'text_align', $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::TEXT_ALIGN,
            $align,
            PrelineClassMap::TEXT_ALIGN[$default] ?? 'text-left',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function titleColor(array $props, string $default = 'default'): string
    {
        $color = PropReader::string($props, 'title_color', $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::TITLE_COLORS,
            $color,
            PrelineClassMap::TITLE_COLORS[$default] ?? 'text-foreground',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function linkColor(array $props, string $default = 'primary'): string
    {
        $color = PropReader::string($props, 'link_color', $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::LINK_COLORS,
            $color,
            PrelineClassMap::LINK_COLORS[$default] ?? PrelineClassMap::LINK_COLORS['primary'],
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function textColor(array $props, string $default = 'default'): string
    {
        $color = PropReader::string($props, 'text_color', $default, 'color');

        return PrelineClassMap::resolve(
            PrelineClassMap::TEXT_COLORS,
            $color,
            PrelineClassMap::TEXT_COLORS[$default] ?? 'text-muted-foreground-2',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function bgColor(array $props, string $default = 'transparent'): string
    {
        $color = PropReader::string($props, 'bg_color', $default, 'color');

        return PrelineClassMap::resolve(
            PrelineClassMap::BG_COLORS,
            $color,
            PrelineClassMap::BG_COLORS[$default] ?? 'bg-transparent',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function rounded(array $props, string $default = 'xl', string $key = 'rounded'): string
    {
        $val = PropReader::string($props, $key, $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::ROUNDED,
            $val,
            PrelineClassMap::ROUNDED[$default] ?? 'rounded-xl',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function shadow(array $props, string $default = 'none', string $fallbackEmpty = ''): string
    {
        $val = PropReader::string($props, 'box_shadow', $default, 'shadow');

        if ('none' === $val && '' === $fallbackEmpty) {
            return '';
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::SHADOW,
            $val,
            'none' === $default ? $fallbackEmpty : (PrelineClassMap::SHADOW[$default] ?? $fallbackEmpty),
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function aspectRatio(array $props, string $default = '16/9', string $key = 'aspect_ratio'): string
    {
        $preset = PrelineClassMap::aspectRatio(PropReader::string($props, $key, $default, 'media_aspect_ratio'))
            ?? PrelineClassMap::aspectRatio($default)
            ?? PrelineClassMap::ASPECT_RATIOS['auto'];

        return $preset['class'];
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function buttonClasses(array $props): string
    {
        $variant = PropReader::string($props, 'variant', 'solid', 'button_style', true);
        $size = PropReader::string($props, 'size', 'md', 'button_size', true);
        $color = PropReader::nullableString($props, 'link_color', 'button_color', true)
            ?? PropReader::string($props, 'color', 'primary', null, true);
        $isFullWidth = isset($props['full_width']) && true === $props['full_width'];

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::BUTTON_SIZES,
            $size,
            PrelineClassMap::BUTTON_SIZES['md'],
        );

        if ('white' === $variant) {
            $variantClasses = PrelineClassMap::BUTTON_WHITE_VARIANT;
        } else {
            if ('primary' === $variant) {
                $variant = 'solid';
            } elseif ('secondary' === $variant) {
                $variant = 'solid';
                if ('primary' === $color) {
                    $color = 'secondary';
                }
            }
            $variantMap = PrelineClassMap::BUTTON_VARIANTS[$variant] ?? PrelineClassMap::BUTTON_VARIANTS['solid'];
            $variantClasses = $variantMap[$color] ?? $variantMap['primary'];
        }

        $align = PropReader::nullableString($props, 'text_align');
        $alignClasses = match ($align) {
            'center' => 'mx-auto flex w-fit',
            'right' => 'ml-auto flex w-fit',
            default => '',
        };
        $widthClass = $isFullWidth ? 'w-full' : $alignClasses;

        return ClassList::join(
            PrelineClassMap::BUTTON_BASE,
            $sizeClasses,
            $variantClasses,
            $widthClass,
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function badgeClasses(
        array $props,
        string $defaultStyle = 'soft',
        string $defaultColor = 'primary',
        string $defaultSize = 'xs',
        string $defaultRounded = 'pill',
    ): string {
        $variant = PropReader::string($props, 'badge_style', $defaultStyle, 'variant', true);
        $color = PropReader::string($props, 'badge_color', $defaultColor, 'color', true);
        $size = PropReader::string($props, 'size', $defaultSize, null, true);
        $rounded = PropReader::string($props, 'rounded', $defaultRounded, null, true);

        $roundedClass = PrelineClassMap::resolve(
            PrelineClassMap::BADGE_ROUNDED,
            $rounded,
            PrelineClassMap::BADGE_ROUNDED['pill'],
        );

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::BADGE_SIZES,
            $size,
            PrelineClassMap::BADGE_SIZES['xs'],
        );

        if ('white' === $variant) {
            $variantClasses = PrelineClassMap::BADGE_WHITE_VARIANT;
        } elseif ('glass' === $variant) {
            $variantClasses = PrelineClassMap::BADGE_GLASS_VARIANT;
        } else {
            $variantMap = PrelineClassMap::BADGE_VARIANTS[$variant] ?? PrelineClassMap::BADGE_VARIANTS['soft'];
            $variantClasses = $variantMap[$color] ?? $variantMap['primary'];
        }

        return ClassList::join(
            PrelineClassMap::BADGE_BASE,
            $roundedClass,
            $sizeClasses,
            $variantClasses,
        );
    }

    public static function resolveIcon(mixed $icon): ?string
    {
        $raw = SuluDataNormalizer::resolveIcon($icon);
        if (null === $raw || '' === $raw) {
            return null;
        }

        if (\str_starts_with($raw, 'ti ')) {
            return $raw;
        }

        if (\str_starts_with($raw, 'ti-')) {
            return 'ti ' . $raw;
        }

        if (
            \str_contains($raw, ' ')
            || \str_starts_with($raw, 'su-')
            || \str_starts_with($raw, 'bi-')
            || \str_starts_with($raw, 'fa-')
            || \str_starts_with($raw, 'ri-')
        ) {
            return $raw;
        }

        return 'ti ti-' . $raw;
    }

    /**
     * Helper to resolve object fit utility class.
     */
    public static function resolveObjectFit(mixed $fit): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::OBJECT_FIT,
            $fit,
            PrelineClassMap::OBJECT_FIT['cover'],
        );
    }
}

