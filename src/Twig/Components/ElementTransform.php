<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Security\AttributeSanitizer;
use Totoglu\SuluBlockSuiteBundle\Security\CssScoper;
use Totoglu\SuluBlockSuiteBundle\Util\ClassList;
use Totoglu\SuluBlockSuiteBundle\Util\PropReader;

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
        $joined = ClassList::join(
            $extraBase,
            self::margin($props),
            $includePadding ? self::padding($props) : null,
            self::containerPaddingRemove($props),
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

        return \implode(' ', ClassList::tokens($joined));
    }

    /**
     * Resolve vertical margin classes (`margin_top`, `margin_bottom`, or legacy `margin`).
     * Disabled when `position === 'absolute'`.
     *
     * @param array<string, mixed> $props
     */
    public static function margin(array $props): string
    {
        if ('absolute' === PropReader::nullableString($props, 'position')) {
            return '';
        }

        $top = PropReader::nullableString($props, 'margin_top');
        $bottom = PropReader::nullableString($props, 'margin_bottom');
        $vertical = PropReader::nullableString($props, 'margin');
        $removeTop = PropReader::bool($props, 'margin_remove_top', false);
        $removeBottom = PropReader::bool($props, 'margin_remove_bottom', false);

        if (null !== $top || null !== $bottom) {
            $topClass = $removeTop ? 'mt-0' : PrelineClassMap::resolve(PrelineClassMap::MARGIN_TOP, $top);
            $bottomClass = $removeBottom ? 'mb-0' : PrelineClassMap::resolve(PrelineClassMap::MARGIN_BOTTOM, $bottom);

            return ClassList::join($topClass, $bottomClass);
        }

        if ('none' === $vertical) {
            return 'my-0';
        }

        if (null !== $vertical && '' !== $vertical) {
            if ($removeTop && $removeBottom) {
                return 'my-0';
            }
            if ($removeTop) {
                return ClassList::join(
                    'mt-0',
                    PrelineClassMap::resolve(PrelineClassMap::MARGIN_BOTTOM, $vertical),
                );
            }
            if ($removeBottom) {
                return ClassList::join(
                    PrelineClassMap::resolve(PrelineClassMap::MARGIN_TOP, $vertical),
                    'mb-0',
                );
            }

            return PrelineClassMap::resolve(PrelineClassMap::MARGIN_VERTICAL, $vertical);
        }

        if ($removeTop && $removeBottom) {
            return 'my-0';
        }
        if ($removeTop) {
            return 'mt-0';
        }
        if ($removeBottom) {
            return 'mb-0';
        }

        return '';
    }

    /**
     * Resolve padding classes (`padding` with `padding_remove_top` and `padding_remove_bottom`).
     *
     * @param array<string, mixed> $props
     */
    public static function padding(
        array $props,
        string $default = 'none',
        string $key = 'padding',
        string $preset = 'default',
    ): string {
        $raw = PropReader::string($props, $key, $default);
        if ('padding' !== $key && $raw === $default) {
            $raw = PropReader::string($props, 'padding', $default);
        }

        $removeTop = PropReader::bool($props, 'padding_remove_top', false);
        $removeBottom = PropReader::bool($props, 'padding_remove_bottom', false);

        $map = match ($preset) {
            'card' => PrelineClassMap::CARD_PADDING,
            'overlay' => PrelineClassMap::OVERLAY_PADDING,
            'section_x' => PrelineClassMap::SECTION_PADDING_X,
            'section_y', 'section' => PrelineClassMap::SECTION_PADDING_Y,
            default => PrelineClassMap::PADDING,
        };

        $baseClass = PrelineClassMap::resolve($map, $raw, $map[$default] ?? '');
        if ('none' === $raw || 'section_x' === $preset) {
            return $baseClass;
        }

        if (('section_y' === $preset || 'section' === $preset) && $removeTop && $removeBottom) {
            return 'py-0';
        }

        return ClassList::join(
            $baseClass,
            $removeTop ? 'pt-0 sm:pt-0' : null,
            $removeBottom ? 'pb-0 sm:pb-0' : null,
        );
    }

    /**
     * Resolve horizontal container padding removal (`container_padding_remove`).
     *
     * @param array<string, mixed> $props
     */
    public static function containerPaddingRemove(array $props): string
    {
        if ('absolute' === PropReader::nullableString($props, 'position')) {
            return '';
        }

        return PropReader::bool($props, 'container_padding_remove', false) ? 'px-0' : '';
    }

    /**
     * Resolve max-width constraint classes (`max_width` / `maxwidth` with optional `max_width_breakpoint`).
     *
     * @param array<string, mixed> $props
     */
    public static function maxWidth(array $props, string $default = 'none', string $preset = 'default'): string
    {
        $width = PropReader::string($props, 'max_width', $default, 'maxwidth');
        if ('section' === $preset) {
            return PrelineClassMap::sectionMaxWidth($width)['class'];
        }

        $breakpoint = PropReader::string($props, 'max_width_breakpoint', '', 'maxwidth_breakpoint');
        if ('' !== $breakpoint && isset(PrelineClassMap::MAX_WIDTH_BREAKPOINTS[$breakpoint][$width])) {
            return PrelineClassMap::MAX_WIDTH_BREAKPOINTS[$breakpoint][$width];
        }

        return PrelineClassMap::blockMaxWidth($width)['class'];
    }

    /**
     * Resolve block alignment (`block_align`, `block_align_breakpoint`, `block_align_fallback`).
     * Disabled when `position === 'absolute'`.
     *
     * @param array<string, mixed> $props
     */
    public static function blockAlign(array $props): string
    {
        if ('absolute' === PropReader::nullableString($props, 'position')) {
            return '';
        }

        $align = PropReader::nullableString($props, 'block_align', 'block_alignment');
        if (null === $align || '' === $align) {
            return '';
        }

        $breakpoint = PropReader::string($props, 'block_align_breakpoint', '');
        if ('' !== $breakpoint && isset(PrelineClassMap::BLOCK_ALIGN_BREAKPOINTS[$breakpoint][$align])) {
            $fallback = PropReader::nullableString($props, 'block_align_fallback');

            return ClassList::join(
                PrelineClassMap::resolve(PrelineClassMap::BLOCK_ALIGN, $fallback),
                PrelineClassMap::BLOCK_ALIGN_BREAKPOINTS[$breakpoint][$align],
            );
        }

        return PrelineClassMap::resolve(PrelineClassMap::BLOCK_ALIGN, $align);
    }

    /**
     * Resolve text alignment classes (`text_align`, `text_align_breakpoint`, `text_align_fallback`).
     *
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props, string $default = '', bool $logical = false): string
    {
        $align = PropReader::string($props, 'text_align', $default);
        if ('' === $align || 'none' === $align) {
            return '';
        }

        $map = $logical ? PrelineClassMap::TEXT_ALIGN_LOGICAL : PrelineClassMap::TEXT_ALIGN;
        $breakpoint = PropReader::string($props, 'text_align_breakpoint', '');

        if ('justify' !== $align && '' !== $breakpoint) {
            $bpMap = $logical ? PrelineClassMap::TEXT_ALIGN_LOGICAL_BREAKPOINTS : PrelineClassMap::TEXT_ALIGN_BREAKPOINTS;
            if (isset($bpMap[$breakpoint][$align])) {
                $fallback = PropReader::string($props, 'text_align_fallback', '');
                $fallbackClass = ('' !== $fallback && 'none' !== $fallback)
                    ? PrelineClassMap::resolve($map, $fallback)
                    : '';

                return ClassList::join($fallbackClass, $bpMap[$breakpoint][$align]);
            }
        }

        return PrelineClassMap::resolve($map, $align, '' !== $default ? ($map[$default] ?? '') : '');
    }

    /**
     * Resolve positioning and z-index classes (`position`, `position_z_index`).
     *
     * @param array<string, mixed> $props
     */
    public static function position(array $props): string
    {
        $pos = PropReader::nullableString($props, 'position');
        $posClass = PrelineClassMap::resolve(PrelineClassMap::POSITION, $pos);
        if ('' === $posClass) {
            return '';
        }

        $zIndex = PropReader::nullableString($props, 'position_z_index');

        return ClassList::join(
            $posClass,
            PrelineClassMap::resolve(PrelineClassMap::POSITION_Z_INDEX, $zIndex),
        );
    }

    /**
     * Resolve inline CSS position offsets (`position_left`, `position_right`, `position_top`, `position_bottom`)
     * when `position` is active (`relative`, `absolute`, `fixed`, `sticky`).
     *
     * @param array<string, mixed> $props
     */
    public static function resolvePositionStyle(array $props): string
    {
        $pos = PropReader::nullableString($props, 'position');
        if (null === $pos || '' === $pos || 'static' === $pos) {
            return '';
        }

        $declarations = [];

        $left = self::normalizePositionOffset($props['position_left'] ?? null);
        $right = (null === $left) ? self::normalizePositionOffset($props['position_right'] ?? null) : null;
        $top = self::normalizePositionOffset($props['position_top'] ?? null);
        $bottom = (null === $top) ? self::normalizePositionOffset($props['position_bottom'] ?? null) : null;

        if (null !== $left) {
            $declarations[] = 'left: ' . $left . ';';
        } elseif (null !== $right) {
            $declarations[] = 'right: ' . $right . ';';
        }

        if (null !== $top) {
            $declarations[] = 'top: ' . $top . ';';
        } elseif (null !== $bottom) {
            $declarations[] = 'bottom: ' . $bottom . ';';
        }

        return \implode(' ', $declarations);
    }

    private static function normalizePositionOffset(mixed $raw): ?string
    {
        if (\is_int($raw) || \is_float($raw)) {
            return $raw . 'px';
        }

        if (!\is_string($raw)) {
            return null;
        }

        $trimmed = \trim($raw);
        if ('' === $trimmed) {
            return null;
        }

        if (1 === \preg_match('/^-?\d+(?:\.\d+)?$/', $trimmed)) {
            return $trimmed . 'px';
        }

        if (1 === \preg_match('/^-?\d+(?:\.\d+)?(?:px|%|vw|vh|rem|em)$/i', $trimmed)) {
            return $trimmed;
        }

        return null;
    }

    /**
     * Resolve responsive visibility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function visibility(array $props): string
    {
        return PrelineClassMap::resolve(PrelineClassMap::VISIBILITY, PropReader::nullableString($props, 'visibility'));
    }

    /**
     * Resolve entrance animation utility classes.
     *
     * @param array<string, mixed> $props
     */
    public static function animation(array $props): string
    {
        return PrelineClassMap::resolve(PrelineClassMap::ANIMATION, PropReader::nullableString($props, 'animation'));
    }

    /**
     * Resolve CSS mix-blend-mode utility classes (`blend` / `blend_mode` / `mix_blend_mode`).
     *
     * @param array<string, mixed> $props
     */
    public static function blendMode(array $props): string
    {
        $blend = $props['blend'] ?? ($props['blend_mode'] ?? ($props['mix_blend_mode'] ?? null));
        if (\is_bool($blend)) {
            return $blend ? 'mix-blend-multiply' : '';
        }

        return PrelineClassMap::resolve(PrelineClassMap::BLEND_MODE, $blend);
    }

    /**
     * Resolve parallax utility classes (`parallax` or `animation === 'parallax'`).
     *
     * @param array<string, mixed> $props
     */
    public static function parallax(array $props): string
    {
        $raw = $props['parallax'] ?? null;
        if ((null === $raw || '' === $raw) && 'parallax' === ($props['animation'] ?? null)) {
            $raw = 'medium';
        }

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
        if ((null === $raw || '' === $raw) && 'parallax' === ($props['animation'] ?? null)) {
            $raw = 'medium';
        }

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
     * Resolve custom class names: Core's normalized `_class` when the block went through the
     * transform pipeline, otherwise the raw `class` / `custom_class` prop tokenized by Core's `ClassList`.
     *
     * @param array<string, mixed> $props
     */
    public static function customClass(array $props): string
    {
        if (isset($props['_class']) && \is_string($props['_class'])) {
            return $props['_class'];
        }

        return \implode(' ', ClassList::tokens(PropReader::nullableString($props, 'class', 'custom_class')));
    }

    /**
     * Resolve the HTML id: Core's `_id` when the block went through the transform pipeline, otherwise a
     * valid explicit `id`, otherwise a stable Core-generated id when custom CSS is defined.
     *
     * @param array<string, mixed> $props
     * @param array<int|string, mixed> $seed
     */
    public static function resolveId(array $props, array $seed = []): ?string
    {
        $resolvedId = AttributeSanitizer::sanitizeId($props['_id'] ?? null)
            ?? AttributeSanitizer::sanitizeId($props['id'] ?? null);
        if (null !== $resolvedId) {
            return $resolvedId;
        }

        if (null !== PropReader::nullableString($props, 'css', 'custom_css')) {
            return CssScoper::generateElementId([] === $seed ? $props : [$seed, $props]);
        }

        return null;
    }

    /**
     * Resolve custom HTML attributes merged with parallax attributes: Core's `_attributes` when the block
     * went through the transform pipeline, otherwise the raw `attributes` prop parsed by Core.
     *
     * @param array<string, mixed> $props
     *
     * @return array<string, string|bool>
     */
    public static function resolveCustomAttributes(array $props): array
    {
        $parallaxAttrs = self::resolveParallaxAttributes($props);

        if (isset($props['_attributes']) && \is_array($props['_attributes'])) {
            return \array_merge($parallaxAttrs, AttributeSanitizer::sanitize($props['_attributes']));
        }

        $raw = PropReader::nullableString($props, 'attributes');
        if (null === $raw) {
            return $parallaxAttrs;
        }

        return \array_merge($parallaxAttrs, AttributeSanitizer::parse($raw));
    }

    /**
     * Resolve scoped CSS: Core's `_scoped_css` when the block went through the transform pipeline,
     * otherwise the raw `css` / `custom_css` prop scoped by Core to the element id.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveScopedCss(array $props, ?string $elementId = null): ?string
    {
        $preScoped = PropReader::nullableString($props, '_scoped_css');
        if (null !== $preScoped) {
            $sanitized = CssScoper::sanitize($preScoped);

            return '' !== $sanitized ? $sanitized : null;
        }

        $rawCss = PropReader::nullableString($props, 'css', 'custom_css');
        if (null === $rawCss) {
            return null;
        }

        $elementId ??= self::resolveId($props);
        if (null === $elementId || '' === $elementId) {
            return null;
        }

        $scoped = CssScoper::scope($rawCss, '#' . $elementId);

        return '' !== $scoped ? $scoped : null;
    }
}
