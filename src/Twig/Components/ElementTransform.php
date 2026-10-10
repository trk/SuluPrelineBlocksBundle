<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

/**
 * Universal pre-render element transformation pipeline.
 *
 * Inspired by YOOtheme Pro's ElementTransform, this class normalizes
 * universal layout, spacing, alignment, and visibility properties into
 * explicit Tailwind CSS 4 utility classes for enclosing elements ($el).
 */
final class ElementTransform
{
    /**
     * Compute the full set of wrapper classes for an element.
     *
     * @param array<string, mixed> $props
     */
    public static function wrapperClasses(array $props, string $extraBase = ''): string
    {
        $classes = [];

        if ('' !== $extraBase) {
            $classes[] = $extraBase;
        }

        $margin = self::margin($props);
        if ('' !== $margin) {
            $classes[] = $margin;
        }

        $maxWidth = self::maxWidth($props);
        if ('' !== $maxWidth) {
            $classes[] = $maxWidth;
        }

        $blockAlign = self::blockAlign($props);
        if ('' !== $blockAlign) {
            $classes[] = $blockAlign;
        }

        $textAlign = self::textAlign($props);
        if ('' !== $textAlign) {
            $classes[] = $textAlign;
        }

        $visibility = self::visibility($props);
        if ('' !== $visibility) {
            $classes[] = $visibility;
        }

        $custom = self::customClass($props);
        if ('' !== $custom) {
            $classes[] = $custom;
        }

        return \trim(\implode(' ', \array_filter($classes)));
    }

    /**
     * Resolve margin classes (top and bottom).
     *
     * @param array<string, mixed> $props
     */
    public static function margin(array $props): string
    {
        $classes = [];

        $top = isset($props['margin_top']) && \is_string($props['margin_top']) ? $props['margin_top'] : null;
        if (null !== $top && '' !== $top) {
            $classes[] = match ($top) {
                'none', 'remove' => 'mt-0',
                'xs', 'xsmall' => 'mt-2',
                'sm', 'small' => 'mt-4',
                'md', 'medium' => 'mt-6',
                'lg', 'large' => 'mt-10',
                'xl', 'xlarge' => 'mt-16',
                'auto' => 'mt-auto',
                default => '',
            };
        }

        $bottom = isset($props['margin_bottom']) && \is_string($props['margin_bottom']) ? $props['margin_bottom'] : null;
        if (null !== $bottom && '' !== $bottom) {
            $classes[] = match ($bottom) {
                'none', 'remove' => 'mb-0',
                'xs', 'xsmall' => 'mb-2',
                'sm', 'small' => 'mb-4',
                'md', 'medium' => 'mb-6',
                'lg', 'large' => 'mb-10',
                'xl', 'xlarge' => 'mb-16',
                'auto' => 'mb-auto',
                default => '',
            };
        }

        return \trim(\implode(' ', \array_filter($classes)));
    }

    /**
     * Resolve max-width constraint classes.
     *
     * @param array<string, mixed> $props
     */
    public static function maxWidth(array $props): string
    {
        $width = isset($props['max_width']) && \is_string($props['max_width'])
            ? $props['max_width']
            : (isset($props['maxwidth']) && \is_string($props['maxwidth']) ? $props['maxwidth'] : null);

        if (null === $width || '' === $width || 'none' === $width) {
            return '';
        }

        return match ($width) {
            'xs' => 'max-w-xs',
            'sm', 'small' => 'max-w-sm',
            'md', 'medium' => 'max-w-md',
            'lg', 'large' => 'max-w-lg',
            'xl', 'xlarge' => 'max-w-xl',
            '2xl', '2xlarge' => 'max-w-2xl',
            '3xl' => 'max-w-3xl',
            '4xl' => 'max-w-4xl',
            '5xl' => 'max-w-5xl',
            'full' => 'max-w-full',
            default => '',
        };
    }

    /**
     * Resolve block alignment (auto-margin centering or alignment when max_width is set).
     *
     * @param array<string, mixed> $props
     */
    public static function blockAlign(array $props): string
    {
        $align = isset($props['block_align']) && \is_string($props['block_align']) ? $props['block_align'] : null;
        if (null === $align || '' === $align) {
            return '';
        }

        return match ($align) {
            'center' => 'mx-auto',
            'right' => 'ml-auto',
            'left' => 'mr-auto',
            default => '',
        };
    }

    /**
     * Resolve text alignment classes.
     *
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props): string
    {
        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : null;
        if (null === $align || '' === $align) {
            return '';
        }

        return match ($align) {
            'center' => 'text-center',
            'right' => 'text-right',
            'justify' => 'text-justify',
            'left' => 'text-left',
            default => '',
        };
    }

    /**
     * Resolve responsive visibility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function visibility(array $props): string
    {
        $vis = isset($props['visibility']) && \is_string($props['visibility']) ? $props['visibility'] : null;
        if (null === $vis || '' === $vis || 'all' === $vis) {
            return '';
        }

        return match ($vis) {
            'hidden_sm', 'hidden-s' => 'hidden sm:block',
            'hidden_md', 'hidden-m' => 'hidden md:block',
            'hidden_lg', 'hidden-l' => 'hidden lg:block',
            'visible_sm', 's' => 'block sm:hidden',
            'visible_md', 'm' => 'block md:hidden',
            'visible_lg', 'l' => 'block lg:hidden',
            default => '',
        };
    }

    /**
     * Resolve custom class names.
     *
     * @param array<string, mixed> $props
     */
    public static function customClass(array $props): string
    {
        if (isset($props['custom_class']) && \is_string($props['custom_class']) && '' !== \trim($props['custom_class'])) {
            return \trim($props['custom_class']);
        }

        if (isset($props['class']) && \is_string($props['class']) && '' !== \trim($props['class'])) {
            return \trim($props['class']);
        }

        return '';
    }

    /**
     * Resolve an HTML id attribute if provided.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveId(array $props): ?string
    {
        if (isset($props['id']) && \is_string($props['id']) && '' !== \trim($props['id'])) {
            return \trim($props['id']);
        }

        return null;
    }
}
