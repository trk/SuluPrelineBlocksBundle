<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Transformer;

use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluBlockSuiteBundle\Transformer\BlockTransformerInterface;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ElementTransform;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PropsResolver;

/**
 * Preline UI 5 & Tailwind CSS 4 Block Transformer.
 * Normalizes framework-specific layout, grid, column, spacing, media, and interactive attributes.
 * Priority: 50 (Runs after core dynamic content and universal default attributes).
 */
final class PrelineBlockTransformer implements BlockTransformerInterface
{
    public static function getDefaultPriority(): int
    {
        return 50;
    }

    public function supports(array $block, array $context): bool
    {
        return true;
    }

    public function transform(array $block, array $context): array
    {
        $props = SuluDataNormalizer::extractProps($block);

        // 1. Compute Tailwind utility wrapper classes (margin, max-width, alignment, visibility)
        $extraClass = isset($block['_class']) && \is_string($block['_class']) ? $block['_class'] : '';
        $wrapperClasses = ElementTransform::wrapperClasses($props, $extraClass);

        if ('' !== $wrapperClasses) {
            $block['_wrapper_class'] = $wrapperClasses;
            $block['wrapper_class'] = $wrapperClasses;
        }

        // 2. Pre-resolve media URLs and responsive srcset + Tailwind object-fit
        if (isset($block['image'])) {
            $block['image_url'] ??= SuluDataNormalizer::resolveImageUrl($block['image']);
            $block['image_srcset'] ??= SuluDataNormalizer::resolveSrcSet($block['image']);
            $block['image_focus_style'] ??= SuluDataNormalizer::resolveFocusPointStyle($block['image']);
            $block['image_object_fit'] = PropsResolver::resolveObjectFit($props['object_fit'] ?? ($block['object_fit'] ?? null));
        }

        if (isset($block['hover_image'])) {
            $block['hover_image_url'] ??= SuluDataNormalizer::resolveImageUrl($block['hover_image']);
        }

        // 3. Pre-resolve link URL
        $link = $block['link'] ?? ($block['card_link'] ?? null);
        if (null !== $link) {
            $block['link_url'] ??= SuluDataNormalizer::resolveUrl($link);
        }

        // 4. Pre-resolve Preline/Tabler icon classes
        if (isset($block['icon'])) {
            $block['icon_class'] = PropsResolver::resolveIcon($block['icon']);
        }

        // 5. Pre-compute Grid classes if this is a grid or gallery block
        $type = isset($block['type']) && \is_scalar($block['type']) ? (string) $block['type'] : '';
        if (\in_array($type, ['grid', 'gallery'], true)) {
            $block['grid_classes'] = $this->computeGridClasses($props);
        }

        // 6. Pre-compute Column classes if this is a column block
        if (\in_array($type, ['column', 'column_item'], true)) {
            $block['column_classes'] = $this->computeColumnClasses($props, $block);
        }

        return $block;
    }

    /**
     * @param array<string, mixed> $props
     */
    private function computeGridClasses(array $props): string
    {
        $colsDefault = self::readColumnProp($props, 'columns_default', 'cols_default', '1');
        $colsSm = self::readColumnProp($props, 'columns_sm', 'cols_sm', '');
        $colsMd = self::readColumnProp($props, 'columns_md', 'cols_md', '2');
        $colsLg = self::readColumnProp($props, 'columns_lg', 'cols_lg', '3');
        $colsXl = self::readColumnProp($props, 'columns_xl', 'cols_xl', '');
        $gap = isset($props['gap']) && \is_string($props['gap']) ? $props['gap'] : 'md';

        $gapClass = match ($gap) {
            'none' => 'gap-0',
            'xs', 'xsmall' => 'gap-2',
            'sm', 'small' => 'gap-4',
            'md', 'medium' => 'gap-6',
            'lg', 'large' => 'gap-8',
            'xl', 'xlarge' => 'gap-10',
            default => 'gap-6',
        };

        $isMasonry = !empty($props['masonry']);
        if ($isMasonry) {
            $defaultColClass = match ($colsDefault) {
                '2' => 'columns-2',
                '3' => 'columns-3',
                '4' => 'columns-4',
                '5' => 'columns-5',
                '6' => 'columns-6',
                default => 'columns-1',
            };

            $smColClass = match ($colsSm) {
                '1' => 'sm:columns-1',
                '2' => 'sm:columns-2',
                '3' => 'sm:columns-3',
                '4' => 'sm:columns-4',
                default => '',
            };

            $mdColClass = match ($colsMd) {
                '1' => 'md:columns-1',
                '2' => 'md:columns-2',
                '3' => 'md:columns-3',
                '4' => 'md:columns-4',
                default => '',
            };

            $lgColClass = match ($colsLg) {
                '1' => 'lg:columns-1',
                '2' => 'lg:columns-2',
                '3' => 'lg:columns-3',
                '4' => 'lg:columns-4',
                default => '',
            };

            $xlColClass = match ($colsXl) {
                '1' => 'xl:columns-1',
                '2' => 'xl:columns-2',
                '3' => 'xl:columns-3',
                '4' => 'xl:columns-4',
                default => '',
            };

            return \trim(\implode(' ', \array_filter([$defaultColClass, $smColClass, $mdColClass, $lgColClass, $xlColClass, $gapClass])));
        }

        $defaultGridClass = match ($colsDefault) {
            '2' => 'grid-cols-2',
            '3' => 'grid-cols-3',
            '4' => 'grid-cols-4',
            '5' => 'grid-cols-5',
            '6' => 'grid-cols-6',
            default => 'grid-cols-1',
        };

        $smGridClass = match ($colsSm) {
            '1' => 'sm:grid-cols-1',
            '2' => 'sm:grid-cols-2',
            '3' => 'sm:grid-cols-3',
            '4' => 'sm:grid-cols-4',
            default => '',
        };

        $mdGridClass = match ($colsMd) {
            '1' => 'md:grid-cols-1',
            '2' => 'md:grid-cols-2',
            '3' => 'md:grid-cols-3',
            '4' => 'md:grid-cols-4',
            default => '',
        };

        $lgGridClass = match ($colsLg) {
            '1' => 'lg:grid-cols-1',
            '2' => 'lg:grid-cols-2',
            '3' => 'lg:grid-cols-3',
            '4' => 'lg:grid-cols-4',
            default => '',
        };

        $xlGridClass = match ($colsXl) {
            '1' => 'xl:grid-cols-1',
            '2' => 'xl:grid-cols-2',
            '3' => 'xl:grid-cols-3',
            '4' => 'xl:grid-cols-4',
            default => '',
        };

        return \trim(\implode(' ', \array_filter(['grid', $defaultGridClass, $smGridClass, $mdGridClass, $lgGridClass, $xlGridClass, $gapClass])));
    }

    /**
     * @param array<string, mixed> $props
     * @param array<string, mixed> $block
     */
    private function computeColumnClasses(array $props, array $block): string
    {
        $colSpan = isset($props['col_span']) && \is_string($props['col_span'])
            ? $props['col_span']
            : (isset($block['col_span']) && \is_string($block['col_span']) ? $block['col_span'] : '12');

        $spanClass = match ($colSpan) {
            '1' => 'col-span-12 sm:col-span-1',
            '2' => 'col-span-12 sm:col-span-2',
            '3' => 'col-span-12 sm:col-span-6 md:col-span-3',
            '4' => 'col-span-12 sm:col-span-6 md:col-span-4',
            '5' => 'col-span-12 sm:col-span-6 md:col-span-5',
            '6' => 'col-span-12 md:col-span-6',
            '7' => 'col-span-12 md:col-span-7',
            '8' => 'col-span-12 md:col-span-8',
            '9' => 'col-span-12 md:col-span-9',
            '10' => 'col-span-12 md:col-span-10',
            '11' => 'col-span-12 md:col-span-11',
            default => 'col-span-12',
        };

        $isSticky = !empty($props['is_sticky']) || !empty($block['is_sticky']);
        $stickyClass = $isSticky ? 'sticky top-24' : '';

        return \trim(\implode(' ', \array_filter([$spanClass, $stickyClass])));
    }

    /**
     * @param array<string, mixed> $props
     */
    private static function readColumnProp(array $props, string $primaryKey, string $fallbackKey, string $default): string
    {
        foreach ([$primaryKey, $fallbackKey] as $key) {
            $val = $props[$key] ?? null;
            if (\is_string($val) && '' !== \trim($val)) {
                return \trim($val);
            }
            if (\is_int($val)) {
                return (string) $val;
            }
        }

        return $default;
    }
}
