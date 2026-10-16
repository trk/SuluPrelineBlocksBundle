<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Transformer\DefaultAttributesTransformer;
use Totoglu\SuluBlockSuiteBundle\Twig\Component\AbstractBlockComponent;

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
        $joined = AbstractBlockComponent::joinClasses(
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
        );

        if ('' === $joined) {
            return '';
        }

        return \implode(' ', \array_unique(\explode(' ', $joined)));
    }

    /**
     * Resolve margin classes (`margin_top`, `margin_bottom`, or vertical `margin`).
     *
     * @param array<string, mixed> $props
     */
    public static function margin(array $props): string
    {
        $top = PropsResolver::nullableStr($props, 'margin_top');
        $bottom = PropsResolver::nullableStr($props, 'margin_bottom');

        $classes = AbstractBlockComponent::joinClasses(
            null !== $top ? PrelineClassMap::resolve(PrelineClassMap::MARGIN_TOP, $top) : null,
            null !== $bottom ? PrelineClassMap::resolve(PrelineClassMap::MARGIN_BOTTOM, $bottom) : null,
        );

        if ('' !== $classes) {
            return $classes;
        }

        $vertical = PropsResolver::nullableStr($props, 'margin');

        return null !== $vertical ? PrelineClassMap::resolve(PrelineClassMap::MARGIN_VERTICAL, $vertical) : '';
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
        $raw = PropsResolver::str($props, $key, $default);

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
        $width = PropsResolver::nullableStr($props, 'max_width', 'maxwidth')
            ?? PropsResolver::str($props, 'width', $default);

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
        $align = PropsResolver::nullableStr($props, 'block_align', 'block_alignment');

        return PrelineClassMap::resolve(PrelineClassMap::BLOCK_ALIGN, $align);
    }

    /**
     * Resolve text alignment classes.
     *
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props, string $default = '', bool $logical = false): string
    {
        $align = PropsResolver::str($props, 'text_align', $default);
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
        return PrelineClassMap::resolve(PrelineClassMap::POSITION, PropsResolver::nullableStr($props, 'position'));
    }

    /**
     * Resolve responsive visibility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function visibility(array $props): string
    {
        return PrelineClassMap::resolve(PrelineClassMap::VISIBILITY, PropsResolver::nullableStr($props, 'visibility'));
    }

    /**
     * Resolve entrance animation utility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function animation(array $props): string
    {
        return PrelineClassMap::resolve(PrelineClassMap::ANIMATION, PropsResolver::nullableStr($props, 'animation'));
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

        $mode = \is_string($raw) ? \trim($raw) : '';
        if ('' === $mode || \in_array($mode, ['none', 'false', '0'], true)) {
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
        return PropsResolver::str($props, 'custom_class', '', 'class');
    }

    /**
     * Resolve an HTML id attribute if provided, or auto-generate a stable id when custom CSS is defined.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveId(array $props): ?string
    {
        $explicitId = PropsResolver::nullableStr($props, 'id', '_id');
        if (null !== $explicitId) {
            return $explicitId;
        }

        $rawCss = PropsResolver::nullableStr($props, 'css', 'custom_css');
        if (null !== $rawCss) {
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
            $sanitized = DefaultAttributesTransformer::sanitizeAttributesArray($props['_attributes']);

            return \array_merge($parallaxAttrs, $sanitized);
        }

        $raw = PropsResolver::nullableStr($props, 'attributes');
        if (null === $raw) {
            return $parallaxAttrs;
        }

        $parsed = DefaultAttributesTransformer::parseAttributes($raw);

        return \array_merge($parallaxAttrs, $parsed);
    }

    /**
     * Resolve scoped CSS rules for the element when custom CSS (`css` or `custom_css`) is defined.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveScopedCss(array $props): ?string
    {
        $preScoped = PropsResolver::nullableStr($props, '_scoped_css');
        if (null !== $preScoped) {
            $sanitized = DefaultAttributesTransformer::sanitizeCssFragment($preScoped);

            return '' !== $sanitized ? $sanitized : null;
        }

        $rawCss = PropsResolver::nullableStr($props, 'css', 'custom_css');
        if (null === $rawCss) {
            return null;
        }

        $elementId = self::resolveId($props);
        if (null === $elementId || '' === $elementId) {
            return null;
        }

        $scoped = DefaultAttributesTransformer::scopeCss($rawCss, '#' . $elementId);

        return '' !== $scoped ? $scoped : null;
    }
}

