<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

final class PropsResolver
{
    /**
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props): string
    {
        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : 'left';

        return match ($align) {
            'center' => 'text-center',
            'right' => 'text-right',
            'justify' => 'text-justify',
            default => 'text-left',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function titleColor(array $props): string
    {
        $color = isset($props['title_color']) && \is_string($props['title_color']) ? $props['title_color'] : 'default';

        return match ($color) {
            'primary' => 'text-primary',
            'secondary' => 'text-secondary',
            'muted' => 'text-muted-foreground-1',
            'white' => 'text-white',
            default => 'text-foreground',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function linkColor(array $props): string
    {
        $color = isset($props['link_color']) && \is_string($props['link_color']) ? $props['link_color'] : 'primary';

        return match ($color) {
            'secondary' => 'text-secondary hover:text-secondary-hover',
            'dark' => 'text-foreground hover:text-muted-foreground-2',
            'white' => 'text-white hover:text-white/80',
            default => 'text-primary hover:text-primary-hover',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function textColor(array $props): string
    {
        $color = isset($props['text_color']) && \is_string($props['text_color'])
            ? $props['text_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'default');

        return match ($color) {
            'muted' => 'text-muted-foreground-1',
            'white' => 'text-white',
            'primary' => 'text-primary',
            default => 'text-muted-foreground-2',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function bgColor(array $props): string
    {
        $color = isset($props['bg_color']) && \is_string($props['bg_color'])
            ? $props['bg_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'transparent');

        return match ($color) {
            'white' => 'bg-card',
            'muted' => 'bg-surface',
            'primary' => 'bg-primary text-primary-foreground',
            'dark' => 'bg-inverse text-foreground-inverse',
            default => 'bg-transparent',
        };
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

        $base = 'inline-flex items-center justify-center gap-x-2 font-medium rounded-lg transition-colors focus:outline-hidden focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';

        $sizeClasses = match ($size) {
            'xs', 'xsmall' => 'py-1.5 px-2.5 text-xs',
            'sm', 'small' => 'py-2 px-3 text-xs',
            'lg', 'large' => 'py-3.5 px-5 text-base',
            'xl', 'xlarge' => 'py-4 px-6 text-base font-semibold',
            default => 'py-2.5 px-4 text-sm',
        };

        $variantClasses = match ($variant) {
            'outline' => match ($color) {
                'secondary' => 'border border-secondary-line text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
                'dark' => 'border border-line-8 text-foreground hover:bg-muted focus:ring-line-8',
                'white' => 'border border-white text-white hover:bg-white/10 focus:ring-white',
                default => 'border border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            },
            'soft' => match ($color) {
                'secondary' => 'border border-transparent bg-secondary-100 text-secondary-800 hover:bg-secondary-200 dark:bg-secondary-900/30 dark:text-secondary-400 dark:hover:bg-secondary-900/50 focus:ring-secondary',
                'dark' => 'border border-transparent bg-surface text-surface-foreground hover:bg-surface-hover focus:ring-surface-focus',
                'white' => 'border border-transparent bg-white/10 text-white hover:bg-white/20 focus:ring-white',
                default => 'border border-transparent bg-primary-100 text-primary-800 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 focus:ring-primary',
            },
            'ghost' => match ($color) {
                'secondary' => 'text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
                'dark' => 'text-foreground hover:bg-muted focus:ring-muted-focus',
                'white' => 'text-white hover:bg-white/10 focus:ring-white',
                default => 'text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            },
            'link' => match ($color) {
                'secondary' => 'text-secondary hover:underline p-0 focus:ring-0',
                'dark' => 'text-foreground hover:underline p-0 focus:ring-0',
                'white' => 'text-white hover:underline p-0 focus:ring-0',
                default => 'text-primary hover:underline p-0 focus:ring-0',
            },
            'white' => 'border border-layer-line bg-layer text-layer-foreground shadow-2xs hover:bg-layer-hover focus:ring-layer-focus',
            default => match ($color) {
                'secondary' => 'border border-transparent bg-secondary text-secondary-foreground hover:bg-secondary-hover focus:ring-secondary focus:ring-offset-background',
                'dark' => 'border border-transparent bg-inverse text-foreground-inverse hover:opacity-90 focus:ring-inverse focus:ring-offset-background',
                'white' => 'border border-transparent bg-layer text-layer-foreground hover:bg-layer-hover shadow-2xs focus:ring-layer-focus focus:ring-offset-background',
                default => 'border border-transparent bg-primary text-primary-foreground hover:bg-primary-hover focus:ring-primary focus:ring-offset-background',
            },
        };

        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : null;
        $alignClasses = match ($align) {
            'center' => 'mx-auto flex w-fit',
            'right' => 'ml-auto flex w-fit',
            default => '',
        };
        $widthClass = $isFullWidth ? 'w-full' : $alignClasses;

        return \trim(\sprintf('%s %s %s %s', $base, $sizeClasses, $variantClasses, $widthClass));
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

        $base = 'inline-flex items-center gap-x-1.5 font-medium';

        $roundedClass = match ($rounded) {
            'rounded', 'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'square', 'none' => 'rounded-none',
            default => 'rounded-full',
        };

        $sizeClasses = match ($size) {
            'sm' => 'py-1 px-2.5 text-xs',
            'md' => 'py-1.5 px-3 text-sm',
            'lg' => 'py-2 px-3.5 text-sm font-semibold',
            default => 'py-0.5 px-2 text-xs',
        };

        $variantClasses = match ($variant) {
            'solid' => match ($color) {
                'secondary' => 'bg-secondary text-secondary-foreground',
                'dark' => 'bg-inverse text-foreground-inverse',
                'white' => 'bg-layer text-layer-foreground shadow-2xs',
                'muted' => 'bg-surface text-surface-foreground',
                'success' => 'bg-teal-500 text-white',
                'warning' => 'bg-yellow-500 text-white',
                'danger' => 'bg-destructive text-destructive-foreground',
                'info' => 'bg-sky-500 text-white',
                default => 'bg-primary text-primary-foreground',
            },
            'outline' => match ($color) {
                'secondary' => 'border border-secondary-line text-secondary',
                'dark' => 'border border-line-8 text-foreground',
                'white' => 'border border-white/60 text-white',
                'muted' => 'border border-border text-muted-foreground-2',
                'success' => 'border border-teal-500 text-teal-500',
                'warning' => 'border border-yellow-500 text-yellow-500',
                'danger' => 'border border-destructive text-destructive',
                'info' => 'border border-sky-500 text-sky-500',
                default => 'border border-primary-line text-primary',
            },
            'white' => 'border border-layer-line bg-layer text-layer-foreground shadow-2xs',
            'glass' => 'bg-layer/80 text-layer-foreground backdrop-blur-md border border-layer-line/40 shadow-xs',
            default => match ($color) {
                'secondary' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-500/20 dark:text-secondary-400',
                'dark' => 'bg-surface text-surface-foreground',
                'white' => 'bg-white/20 text-white backdrop-blur-md',
                'muted' => 'bg-muted text-muted-foreground-2',
                'success' => 'bg-teal-100 text-teal-800 dark:bg-teal-500/20 dark:text-teal-400',
                'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
                'danger' => 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
                'info' => 'bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-400',
                default => 'bg-primary-100 text-primary-800 dark:bg-primary-500/20 dark:text-primary-400',
            },
        };

        return \trim(\sprintf('%s %s %s %s', $base, $roundedClass, $sizeClasses, $variantClasses));
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

        $variantClasses = match ($variant) {
            'group' => 'border-0 rounded-none shadow-none flex-1',
            'shadow' => 'shadow-lg border border-card-line hover:shadow-xl',
            'flat' => 'border-0 bg-surface',
            'glass' => 'backdrop-blur-md bg-card/70 border border-card-line/40 shadow-lg',
            default => 'border border-card-line shadow-2xs',
        };

        $layoutClasses = match ($layout) {
            'horizontal' => 'flex-col md:flex-row',
            default => 'flex-col',
        };

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
        if (!\is_string($fit) || '' === \trim($fit)) {
            return 'object-cover';
        }

        return match (\trim($fit)) {
            'contain' => 'object-contain',
            'fill' => 'object-fill',
            'scale-down' => 'object-scale-down',
            'none' => 'object-none',
            default => 'object-cover',
        };
    }
}
