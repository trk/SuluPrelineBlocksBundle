<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

/**
 * Universal pre-render element transformation pipeline.
 *
 * Centralizes universal layout, spacing (`margin`, `padding`), sizing (`max_width`),
 * alignment (`block_align`, `text_align`), positioning, responsive visibility,
 * entrance animation, blend mode (`blend`), parallax, custom classes, HTML IDs,
 * custom attributes, and scoped CSS into explicit Tailwind CSS 4 utility classes.
 */
final class ElementTransform
{
    /**
     * Compute the full set of wrapper classes for an element.
     *
     * @param array<string, mixed> $props
     */
    public static function wrapperClasses(array $props, string $extraBase = '', bool $includePadding = false): string
    {
        $segments = [
            $extraBase,
            self::margin($props),
            $includePadding ? self::padding($props) : self::padding($props, 'none', 'block_padding'),
            self::maxWidth($props),
            self::blockAlign($props),
            self::textAlign($props),
            self::position($props),
            self::visibility($props),
            self::animation($props),
            self::blendMode($props),
            self::parallax($props),
            self::customClass($props),
        ];

        $classes = [];
        foreach ($segments as $segment) {
            if ('' === $segment) {
                continue;
            }
            foreach (\explode(' ', \trim($segment)) as $c) {
                if ('' !== $c) {
                    $classes[] = $c;
                }
            }
        }

        return \trim(\implode(' ', \array_unique(\array_filter($classes))));
    }

    /**
     * Resolve margin classes (`margin_top`, `margin_bottom`, or vertical `margin`).
     *
     * @param array<string, mixed> $props
     */
    public static function margin(array $props): string
    {
        $classes = [];

        $top = isset($props['margin_top']) && \is_string($props['margin_top']) ? $props['margin_top'] : null;
        if (null !== $top && '' !== $top) {
            $classes[] = PrelineClassMap::resolve(PrelineClassMap::MARGIN_TOP, $top);
        }

        $bottom = isset($props['margin_bottom']) && \is_string($props['margin_bottom']) ? $props['margin_bottom'] : null;
        if (null !== $bottom && '' !== $bottom) {
            $classes[] = PrelineClassMap::resolve(PrelineClassMap::MARGIN_BOTTOM, $bottom);
        }

        if ([] === $classes && isset($props['margin']) && \is_string($props['margin']) && '' !== $props['margin']) {
            $classes[] = PrelineClassMap::resolve(PrelineClassMap::MARGIN_VERTICAL, $props['margin']);
        }

        return \trim(\implode(' ', \array_filter($classes)));
    }

    /**
     * Resolve padding classes from `padding`, `card_padding`, `overlay_padding`, or `block_padding`.
     *
     * @param array<string, mixed> $props
     */
    public static function padding(
        array $props,
        string $default = 'none',
        string $key = 'padding',
        string $preset = 'default',
    ): string {
        $raw = isset($props[$key]) && \is_string($props[$key]) && '' !== \trim($props[$key])
            ? \trim($props[$key])
            : $default;

        $map = match ($preset) {
            'card' => PrelineClassMap::CARD_PADDING,
            'overlay' => PrelineClassMap::OVERLAY_PADDING,
            'section_x' => PrelineClassMap::SECTION_PADDING_X,
            'section_y' => PrelineClassMap::SECTION_PADDING_Y,
            default => PrelineClassMap::PADDING,
        };

        return PrelineClassMap::resolve($map, $raw, $map[$default] ?? '');
    }

    /**
     * Resolve max-width constraint classes (`max_width` / `maxwidth` / `width`).
     *
     * @param array<string, mixed> $props
     */
    public static function maxWidth(array $props, string $default = 'none', string $preset = 'default'): string
    {
        $width = isset($props['max_width']) && \is_string($props['max_width'])
            ? $props['max_width']
            : (isset($props['maxwidth']) && \is_string($props['maxwidth'])
                ? $props['maxwidth']
                : (isset($props['width']) && \is_string($props['width']) ? $props['width'] : $default));

        if ('section' === $preset) {
            return PrelineClassMap::resolve(
                PrelineClassMap::SECTION_MAX_WIDTH,
                $width,
                PrelineClassMap::SECTION_MAX_WIDTH[$default] ?? 'max-w-[85rem]',
            );
        }

        if ('' === $width || 'none' === $width) {
            return '';
        }

        return PrelineClassMap::resolve(PrelineClassMap::MAX_WIDTH, $width);
    }

    /**
     * Resolve block alignment (auto-margin centering or alignment when max_width is set).
     *
     * @param array<string, mixed> $props
     */
    public static function blockAlign(array $props): string
    {
        $align = isset($props['block_align']) && \is_string($props['block_align'])
            ? $props['block_align']
            : (isset($props['block_alignment']) && \is_string($props['block_alignment']) ? $props['block_alignment'] : null);

        return PrelineClassMap::resolve(PrelineClassMap::BLOCK_ALIGN, $align);
    }

    /**
     * Resolve text alignment classes.
     *
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props, string $default = '', bool $logical = false): string
    {
        $align = isset($props['text_align']) && \is_string($props['text_align']) && '' !== \trim($props['text_align'])
            ? \trim($props['text_align'])
            : $default;

        if ('' === $align) {
            return '';
        }

        $map = $logical ? PrelineClassMap::TEXT_ALIGN_LOGICAL : PrelineClassMap::TEXT_ALIGN;

        return PrelineClassMap::resolve($map, $align, '' !== $default ? ($map[$default] ?? '') : '');
    }

    /**
     * Resolve positioning classes.
     *
     * @param array<string, mixed> $props
     */
    public static function position(array $props): string
    {
        $pos = isset($props['position']) && \is_string($props['position']) ? $props['position'] : null;

        return PrelineClassMap::resolve(PrelineClassMap::POSITION, $pos);
    }

    /**
     * Resolve responsive visibility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function visibility(array $props): string
    {
        $vis = isset($props['visibility']) && \is_string($props['visibility']) ? $props['visibility'] : null;

        return PrelineClassMap::resolve(PrelineClassMap::VISIBILITY, $vis);
    }

    /**
     * Resolve entrance animation utility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function animation(array $props): string
    {
        $anim = isset($props['animation']) && \is_string($props['animation']) ? $props['animation'] : null;

        return PrelineClassMap::resolve(PrelineClassMap::ANIMATION, $anim);
    }

    /**
     * Resolve CSS mix-blend-mode utility classes (`blend` / `blend_mode` / `mix_blend_mode`).
     *
     * @param array<string, mixed> $props
     */
    public static function blendMode(array $props): string
    {
        $blend = $props['blend'] ?? ($props['blend_mode'] ?? ($props['mix_blend_mode'] ?? null));

        return PrelineClassMap::resolve(PrelineClassMap::BLEND_MODE, $blend);
    }

    /**
     * Resolve parallax utility classes (`parallax`).
     *
     * @param array<string, mixed> $props
     */
    public static function parallax(array $props): string
    {
        $raw = $props['parallax'] ?? null;
        if (\is_bool($raw)) {
            $raw = $raw ? 'true' : 'none';
        } elseif (\is_int($raw)) {
            $raw = (string) $raw;
        }

        return PrelineClassMap::resolve(PrelineClassMap::PARALLAX, $raw);
    }

    /**
     * Resolve declarative parallax data attributes when `parallax` is enabled.
     *
     * @param array<string, mixed> $props
     *
     * @return array<string, string>
     */
    public static function resolveParallaxAttributes(array $props): array
    {
        $raw = $props['parallax'] ?? null;
        if (\is_bool($raw)) {
            $raw = $raw ? 'medium' : 'none';
        }

        if (!\is_string($raw)) {
            return [];
        }

        $mode = \trim($raw);
        if ('' === $mode || 'none' === $mode || 'false' === $mode || '0' === $mode) {
            return [];
        }

        return ['data-parallax' => $mode];
    }

    /**
     * Resolve custom class names (`custom_class` or `class`).
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
     * Resolve an HTML id attribute if provided, or auto-generate a stable id when custom CSS is defined.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveId(array $props): ?string
    {
        if (isset($props['id']) && \is_string($props['id']) && '' !== \trim($props['id'])) {
            return \trim($props['id']);
        }

        if (isset($props['_id']) && \is_string($props['_id']) && '' !== \trim($props['_id'])) {
            return \trim($props['_id']);
        }

        $rawCss = $props['css'] ?? ($props['custom_css'] ?? null);
        if (\is_string($rawCss) && '' !== \trim($rawCss)) {
            $encoded = \json_encode($props, \JSON_PARTIAL_OUTPUT_ON_ERROR);

            return 'el-' . \substr(\md5(false !== $encoded ? $encoded : ''), 0, 8);
        }

        return null;
    }

    /**
     * Resolve custom HTML attributes (key=value lines) from props, merged with parallax attributes.
     *
     * @param array<string, mixed> $props
     *
     * @return array<string, string|bool>
     */
    public static function resolveCustomAttributes(array $props): array
    {
        $parallaxAttrs = self::resolveParallaxAttributes($props);

        if (isset($props['_attributes']) && \is_array($props['_attributes'])) {
            $sanitized = \Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer::sanitizeAttributesArray($props['_attributes']);

            return \array_merge($parallaxAttrs, $sanitized);
        }

        $raw = $props['attributes'] ?? null;
        if (!\is_string($raw) || '' === \trim($raw)) {
            return $parallaxAttrs;
        }

        $parsed = \Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer::parseAttributes($raw);

        return \array_merge($parallaxAttrs, $parsed);
    }

    /**
     * Resolve scoped CSS rules for the element when custom CSS (`css` or `custom_css`) is defined.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveScopedCss(array $props): ?string
    {
        if (isset($props['_scoped_css']) && \is_string($props['_scoped_css']) && '' !== \trim($props['_scoped_css'])) {
            $sanitized = \Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer::sanitizeCssFragment($props['_scoped_css']);

            return '' !== $sanitized ? $sanitized : null;
        }

        $rawCss = $props['css'] ?? ($props['custom_css'] ?? null);
        if (!\is_string($rawCss) || '' === \trim($rawCss)) {
            return null;
        }

        $elementId = self::resolveId($props);
        if (null === $elementId || '' === $elementId) {
            return null;
        }

        $scoped = \Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer::scopeCss($rawCss, '#' . $elementId);

        return '' !== $scoped ? $scoped : null;
    }
}
