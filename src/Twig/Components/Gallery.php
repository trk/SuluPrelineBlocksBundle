<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Gallery', template: '@SuluPrelineBlocks/components/Gallery.html.twig')]
final class Gallery extends AbstractPrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'gap' => 'md',
            'masonry' => 'none',
            'overlay_position' => 'bottom',
            'overlay_mode' => 'cover',
            'overlay_style' => 'gradient',
            'overlay_transition' => 'fade',
            'image_transition' => 'zoom',
            'media_aspect_ratio' => '4/3',
            'rounded' => 'xl',
            'lightbox' => false,
            'filter' => false,
            'filter_style' => 'pills',
            'filter_align' => 'center',
            'filter_all_label' => 'All',
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'masonry' => fn (): bool => $this->isMasonry(),
            'filterable' => fn (): bool => $this->isFilterEnabled(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        return $this->items('items');
    }

    public function isMasonry(): bool
    {
        return 'columns' === $this->stringProp('masonry', 'none');
    }

    public function getContainerClasses(): string
    {
        $props = $this->props();

        $gapClass = match ($this->stringProp('gap', 'md')) {
            'none', 'collapse', '0' => 'gap-0',
            'xs', 'xsmall' => 'gap-2',
            'sm', 'small' => 'gap-4',
            'lg', 'large' => 'gap-8',
            'xl', 'xlarge' => 'gap-12',
            '2xl', '2xlarge' => 'gap-16',
            default => 'gap-6',
        };

        $rawDefault = $this->nullableStringProp('columns_default');
        $colsDefault = null !== $rawDefault && 'inherit' !== $rawDefault && 'none' !== $rawDefault
            ? $rawDefault
            : '1';

        $hasExplicitResponsive = \array_key_exists('columns_sm', $props)
            || \array_key_exists('columns_md', $props)
            || \array_key_exists('columns_lg', $props)
            || \array_key_exists('columns_xl', $props);

        $rawSm = $this->nullableStringProp('columns_sm');
        $colsSm = null !== $rawSm && 'inherit' !== $rawSm && 'none' !== $rawSm ? $rawSm : '';

        $rawMd = $this->nullableStringProp('columns_md');
        $colsMd = null !== $rawMd && 'inherit' !== $rawMd && 'none' !== $rawMd
            ? $rawMd
            : (!$hasExplicitResponsive ? '3' : '');

        $rawLg = $this->nullableStringProp('columns_lg');
        $colsLg = null !== $rawLg && 'inherit' !== $rawLg && 'none' !== $rawLg ? $rawLg : '';

        $rawXl = $this->nullableStringProp('columns_xl');
        $colsXl = null !== $rawXl && 'inherit' !== $rawXl && 'none' !== $rawXl ? $rawXl : '';

        if ($this->isMasonry()) {
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
                '5' => 'sm:columns-5',
                '6' => 'sm:columns-6',
                default => '',
            };

            $mdColClass = match ($colsMd) {
                '1' => 'md:columns-1',
                '2' => 'md:columns-2',
                '3' => 'md:columns-3',
                '4' => 'md:columns-4',
                '5' => 'md:columns-5',
                '6' => 'md:columns-6',
                default => '',
            };

            $lgColClass = match ($colsLg) {
                '1' => 'lg:columns-1',
                '2' => 'lg:columns-2',
                '3' => 'lg:columns-3',
                '4' => 'lg:columns-4',
                '5' => 'lg:columns-5',
                '6' => 'lg:columns-6',
                default => '',
            };

            $xlColClass = match ($colsXl) {
                '1' => 'xl:columns-1',
                '2' => 'xl:columns-2',
                '3' => 'xl:columns-3',
                '4' => 'xl:columns-4',
                '5' => 'xl:columns-5',
                '6' => 'xl:columns-6',
                default => '',
            };

            $classes = \array_values(\array_filter([
                $defaultColClass,
                $smColClass,
                $mdColClass,
                $lgColClass,
                $xlColClass,
                $gapClass,
            ], static fn (string $c): bool => '' !== $c));

            return \implode(' ', $classes);
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
            '5' => 'sm:grid-cols-5',
            '6' => 'sm:grid-cols-6',
            default => '',
        };

        $mdGridClass = match ($colsMd) {
            '1' => 'md:grid-cols-1',
            '2' => 'md:grid-cols-2',
            '3' => 'md:grid-cols-3',
            '4' => 'md:grid-cols-4',
            '5' => 'md:grid-cols-5',
            '6' => 'md:grid-cols-6',
            default => '',
        };

        $lgGridClass = match ($colsLg) {
            '1' => 'lg:grid-cols-1',
            '2' => 'lg:grid-cols-2',
            '3' => 'lg:grid-cols-3',
            '4' => 'lg:grid-cols-4',
            '5' => 'lg:grid-cols-5',
            '6' => 'lg:grid-cols-6',
            default => '',
        };

        $xlGridClass = match ($colsXl) {
            '1' => 'xl:grid-cols-1',
            '2' => 'xl:grid-cols-2',
            '3' => 'xl:grid-cols-3',
            '4' => 'xl:grid-cols-4',
            '5' => 'xl:grid-cols-5',
            '6' => 'xl:grid-cols-6',
            default => '',
        };

        $classes = \array_values(\array_filter([
            'grid',
            $defaultGridClass,
            $smGridClass,
            $mdGridClass,
            $lgGridClass,
            $xlGridClass,
            $gapClass,
        ], static fn (string $c): bool => '' !== $c));

        return \implode(' ', $classes);
    }

    public function isFilterEnabled(): bool
    {
        return $this->boolProp('filter', false);
    }

    public function isLightboxEnabled(): bool
    {
        return $this->boolProp('lightbox', false);
    }

    /**
     * @return array<string, string> Key: slug, Value: label
     */
    public function getFilterTags(): array
    {
        $tags = [];
        foreach ($this->getNormalizedItems() as $item) {
            $itemTags = isset($item['tags']) && \is_string($item['tags']) ? $item['tags'] : '';
            if ('' === \trim($itemTags)) {
                continue;
            }

            foreach (\explode(',', $itemTags) as $rawTag) {
                $trimmed = \trim($rawTag);
                if ('' === $trimmed) {
                    continue;
                }

                $slug = \strtolower((string) \preg_replace('/[^a-zA-Z0-9_-]/', '-', $trimmed));
                if ('' !== $slug && !isset($tags[$slug])) {
                    $tags[$slug] = $trimmed;
                }
            }
        }

        return $tags;
    }

    public function getFilterAlignClass(): string
    {
        return match ($this->stringProp('filter_align', 'center')) {
            'left' => 'justify-start',
            'right' => 'justify-end',
            default => 'justify-center',
        };
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return string Space-separated tag slugs for HTML data attribute
     */
    public function getItemTagSlugs(array $item): string
    {
        $itemTags = isset($item['tags']) && \is_string($item['tags']) ? $item['tags'] : '';
        if ('' === \trim($itemTags)) {
            return '';
        }

        $slugs = [];
        foreach (\explode(',', $itemTags) as $rawTag) {
            $trimmed = \trim($rawTag);
            if ('' !== $trimmed) {
                $slug = \strtolower((string) \preg_replace('/[^a-zA-Z0-9_-]/', '-', $trimmed));
                if ('' !== $slug) {
                    $slugs[] = $slug;
                }
            }
        }

        return \implode(' ', $slugs);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function resolveItemProps(array $item): array
    {
        $itemProps = isset($item['props']) && \is_array($item['props']) ? $item['props'] : [];

        $overlayPosition = $this->stringProp('overlay_position', 'bottom');
        $overlayMode = $this->stringProp('overlay_mode', 'cover');

        $overlayStyle = $this->stringProp('overlay_style', 'gradient');
        if (isset($item['overlay_style']) && \is_string($item['overlay_style']) && 'inherit' !== $item['overlay_style'] && '' !== \trim($item['overlay_style'])) {
            $overlayStyle = $item['overlay_style'];
        }

        $overlayTransition = $this->stringProp('overlay_transition', 'fade');
        $imageTransition = $this->stringProp('image_transition', 'zoom');
        $mediaAspectRatio = $this->stringProp('media_aspect_ratio', '4/3');
        $rounded = $this->stringProp('rounded', 'xl');

        $buttonStyle = 'primary';
        if (isset($item['link_style']) && \is_string($item['link_style']) && 'inherit' !== $item['link_style'] && '' !== \trim($item['link_style'])) {
            $buttonStyle = $item['link_style'];
        }

        /** @var array<string, mixed> $merged */
        $merged = \array_merge([
            'overlay_position' => $overlayPosition,
            'overlay_mode' => $overlayMode,
            'overlay_style' => $overlayStyle,
            'overlay_transition' => $overlayTransition,
            'image_transition' => $imageTransition,
            'media_aspect_ratio' => $mediaAspectRatio,
            'rounded' => $rounded,
            'button_style' => $buttonStyle,
        ], $itemProps);

        return $merged;
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageUrl(array $item): ?string
    {
        return PropsResolver::resolveImageUrl($item['image'] ?? null);
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageSrcSet(array $item): ?string
    {
        return PropsResolver::resolveSrcSet($item['image'] ?? null);
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageFocusPointStyle(array $item): ?string
    {
        return PropsResolver::resolveFocusPointStyle($item['image'] ?? null);
    }
}
