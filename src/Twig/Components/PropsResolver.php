<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluBlockSuiteBundle\Twig\Component\AbstractBlockComponent;

/**
 * Centralized resolver for Preline UI 5 & Tailwind CSS 4 component properties,
 * delegating all static utility class lookups to `PrelineClassMap`.
 */
final class PropsResolver
{
    /**
     * Reads a trimmed string value from `$props[$key]` (with optional `$fallbackKey`),
     * optionally ignoring `'inherit'`, and falling back to `$default`.
     *
     * @param array<string, mixed> $props
     */
    public static function str(
        array $props,
        string $key,
        string $default = '',
        ?string $fallbackKey = null,
        bool $ignoreInherit = false,
    ): string {
        $primary = self::nullableStr($props, $key, null, $ignoreInherit);
        if (null !== $primary) {
            return $primary;
        }

        if (null !== $fallbackKey) {
            $fallback = self::nullableStr($props, $fallbackKey, null, $ignoreInherit);
            if (null !== $fallback) {
                return $fallback;
            }
        }

        return $default;
    }

    /**
     * Reads a trimmed non-empty string value from `$props[$key]` (with optional `$fallbackKey`),
     * optionally ignoring `'inherit'`, or returns `null`.
     *
     * @param array<string, mixed> $props
     */
    public static function nullableStr(
        array $props,
        string $key,
        ?string $fallbackKey = null,
        bool $ignoreInherit = false,
    ): ?string {
        foreach ([$key, $fallbackKey] as $candidateKey) {
            if (null === $candidateKey || !isset($props[$candidateKey]) || !\is_string($props[$candidateKey])) {
                continue;
            }

            $trimmed = \trim($props[$candidateKey]);
            if ('' === $trimmed || ($ignoreInherit && 'inherit' === $trimmed)) {
                continue;
            }

            return $trimmed;
        }

        return null;
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props, string $default = 'left'): string
    {
        $align = self::str($props, 'text_align', $default);

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
        $color = self::str($props, 'title_color', $default);

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
        $color = self::str($props, 'link_color', $default);

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
        $color = self::str($props, 'text_color', $default, 'color');

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
        $color = self::str($props, 'bg_color', $default, 'color');

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
        $val = self::str($props, $key, $default);

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
        $val = self::str($props, 'box_shadow', $default, 'shadow');

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
        $val = self::str($props, $key, $default, 'media_aspect_ratio');

        return PrelineClassMap::resolve(
            PrelineClassMap::ASPECT_RATIOS,
            $val,
            PrelineClassMap::ASPECT_RATIOS[$default] ?? 'aspect-video',
        );
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function buttonClasses(array $props): string
    {
        $variant = self::str($props, 'variant', 'solid', 'button_style', true);
        $size = self::str($props, 'size', 'md', 'button_size', true);
        $color = self::nullableStr($props, 'link_color', 'button_color', true)
            ?? self::str($props, 'color', 'primary', null, true);
        $isFullWidth = isset($props['full_width']) && true === $props['full_width'];

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::BUTTON_SIZES,
            $size,
            PrelineClassMap::BUTTON_SIZES['md'],
        );

        if ('white' === $variant) {
            $variantClasses = PrelineClassMap::BUTTON_WHITE_VARIANT;
        } else {
            $variantMap = PrelineClassMap::BUTTON_VARIANTS[$variant] ?? PrelineClassMap::BUTTON_VARIANTS['solid'];
            $variantClasses = $variantMap[$color] ?? $variantMap['primary'];
        }

        $align = self::nullableStr($props, 'text_align');
        $alignClasses = match ($align) {
            'center' => 'mx-auto flex w-fit',
            'right' => 'ml-auto flex w-fit',
            default => '',
        };
        $widthClass = $isFullWidth ? 'w-full' : $alignClasses;

        return AbstractBlockComponent::joinClasses(
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
        $variant = self::str($props, 'badge_style', $defaultStyle, 'variant', true);
        $color = self::str($props, 'badge_color', $defaultColor, 'color', true);
        $size = self::str($props, 'size', $defaultSize, null, true);
        $rounded = self::str($props, 'rounded', $defaultRounded, null, true);

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

        return AbstractBlockComponent::joinClasses(
            PrelineClassMap::BADGE_BASE,
            $roundedClass,
            $sizeClasses,
            $variantClasses,
        );
    }

    /**
     * Helper to resolve a Sulu link structure or plain URL string to an href string.
     */
    public static function resolveUrl(mixed $link): string
    {
        return SuluDataNormalizer::resolveUrl($link);
    }

    /**
     * Helper to resolve a Sulu Media structure, object, or string to an image URL.
     */
    public static function resolveImageUrl(mixed $media): ?string
    {
        return SuluDataNormalizer::resolveImageUrl($media);
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

        if (\str_contains($raw, ' ') || \str_starts_with($raw, 'su-') || \str_starts_with($raw, 'bi-') || \str_starts_with($raw, 'fa-')) {
            return $raw;
        }

        return 'ti ti-' . $raw;
    }

    /**
     * Helper to resolve responsive srcset from a Sulu Media structure.
     */
    public static function resolveSrcSet(mixed $media): ?string
    {
        return SuluDataNormalizer::resolveSrcSet($media);
    }

    /**
     * Helper to resolve focal/focus point to CSS object-position.
     */
    public static function resolveFocusPointStyle(mixed $media): ?string
    {
        return SuluDataNormalizer::resolveFocusPointStyle($media);
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

