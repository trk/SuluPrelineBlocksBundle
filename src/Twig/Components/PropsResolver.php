<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

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
        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : $default;

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
        $color = isset($props['title_color']) && \is_string($props['title_color']) ? $props['title_color'] : $default;

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
        $color = isset($props['link_color']) && \is_string($props['link_color']) ? $props['link_color'] : $default;

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
        $color = isset($props['text_color']) && \is_string($props['text_color'])
            ? $props['text_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : $default);

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
        $color = isset($props['bg_color']) && \is_string($props['bg_color'])
            ? $props['bg_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : $default);

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
        $val = isset($props[$key]) && \is_string($props[$key]) ? $props[$key] : $default;

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
        $val = isset($props['box_shadow']) && \is_string($props['box_shadow']) && '' !== \trim($props['box_shadow'])
            ? $props['box_shadow']
            : (isset($props['shadow']) && \is_string($props['shadow']) && '' !== \trim($props['shadow'])
                ? $props['shadow']
                : $default);

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
        $val = isset($props[$key]) && \is_string($props[$key])
            ? $props[$key]
            : (isset($props['media_aspect_ratio']) && \is_string($props['media_aspect_ratio']) ? $props['media_aspect_ratio'] : $default);

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
        $variant = isset($props['variant']) && \is_string($props['variant']) ? $props['variant'] : 'solid';
        $size = isset($props['size']) && \is_string($props['size']) ? $props['size'] : 'md';
        $color = isset($props['link_color']) && \is_string($props['link_color'])
            ? $props['link_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'primary');
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

        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : null;
        $alignClasses = match ($align) {
            'center' => 'mx-auto flex w-fit',
            'right' => 'ml-auto flex w-fit',
            default => '',
        };
        $widthClass = $isFullWidth ? 'w-full' : $alignClasses;

        return \trim(\sprintf('%s %s %s %s', PrelineClassMap::BUTTON_BASE, $sizeClasses, $variantClasses, $widthClass));
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
        $variant = isset($props['badge_style']) && \is_string($props['badge_style']) && 'inherit' !== $props['badge_style']
            ? $props['badge_style']
            : (isset($props['variant']) && \is_string($props['variant']) && 'inherit' !== $props['variant'] ? $props['variant'] : $defaultStyle);

        $color = isset($props['badge_color']) && \is_string($props['badge_color']) && 'inherit' !== $props['badge_color']
            ? $props['badge_color']
            : (isset($props['color']) && \is_string($props['color']) && 'inherit' !== $props['color'] ? $props['color'] : $defaultColor);

        $size = isset($props['size']) && \is_string($props['size']) && 'inherit' !== $props['size']
            ? $props['size']
            : $defaultSize;

        $rounded = isset($props['rounded']) && \is_string($props['rounded']) && 'inherit' !== $props['rounded']
            ? $props['rounded']
            : $defaultRounded;

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

        return \trim(\sprintf('%s %s %s %s', PrelineClassMap::BADGE_BASE, $roundedClass, $sizeClasses, $variantClasses));
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function cardClasses(array $props): string
    {
        $variant = isset($props['card_variant']) && \is_string($props['card_variant'])
            ? $props['card_variant']
            : (isset($props['variant']) && \is_string($props['variant']) ? $props['variant'] : 'bordered');
        $layout = isset($props['layout']) && \is_string($props['layout']) ? $props['layout'] : 'vertical';
        $bg = self::bgColor($props);

        $base = 'flex rounded-xl overflow-hidden transition-all';

        $variantClasses = PrelineClassMap::resolve(
            PrelineClassMap::PROPS_RESOLVER_CARD_VARIANTS,
            $variant,
            PrelineClassMap::PROPS_RESOLVER_CARD_VARIANTS['bordered'],
        );

        $layoutClasses = 'horizontal' === $layout ? 'flex-col md:flex-row' : 'flex-col';

        return \trim(\sprintf('%s %s %s %s', $base, $variantClasses, $layoutClasses, $bg));
    }

    /**
     * Helper to resolve a Sulu link structure or plain URL string to an href string.
     */
    public static function resolveUrl(mixed $link): string
    {
        return \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::resolveUrl($link);
    }

    /**
     * Helper to resolve a Sulu Media structure, object, or string to an image URL.
     */
    public static function resolveImageUrl(mixed $media): ?string
    {
        return \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::resolveImageUrl($media);
    }

    public static function resolveIcon(mixed $icon): ?string
    {
        $raw = \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::resolveIcon($icon);
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
        return \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::resolveSrcSet($media);
    }

    /**
     * Helper to resolve focal/focus point to CSS object-position.
     */
    public static function resolveFocusPointStyle(mixed $media): ?string
    {
        return \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::resolveFocusPointStyle($media);
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
