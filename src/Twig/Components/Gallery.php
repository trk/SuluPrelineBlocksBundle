<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Gallery', template: '@SuluPrelineBlocks/components/Gallery.html.twig')]
final class Gallery
{
    public ?string $title = null;

    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public string|int|null $columnsDefault = null;
    public string|int|null $columns_default = null;
    public string|int|null $columnsSm = null;
    public string|int|null $columns_sm = null;
    public string|int|null $columnsMd = null;
    public string|int|null $columns_md = null;
    public string|int|null $columnsLg = null;
    public string|int|null $columns_lg = null;
    public string|int|null $columnsXl = null;
    public string|int|null $columns_xl = null;
    public string|int|null $gap = null;
    public ?string $masonry = null;

    public ?string $overlayPosition = null;
    public ?string $overlay_position = null;
    public ?string $overlayMode = null;
    public ?string $overlay_mode = null;
    public ?string $overlayStyle = null;
    public ?string $overlay_style = null;
    public ?string $overlayTransition = null;
    public ?string $overlay_transition = null;
    public ?string $imageTransition = null;
    public ?string $image_transition = null;
    public ?string $mediaAspectRatio = null;
    public ?string $media_aspect_ratio = null;
    public ?string $rounded = null;

    public ?bool $lightbox = null;

    public ?bool $filter = null;
    public ?string $filterStyle = null;
    public ?string $filter_style = null;
    public ?string $filterAlign = null;
    public ?string $filter_align = null;
    public ?string $filterAllLabel = null;
    public ?string $filter_all_label = null;

    public ?string $marginTop = null;
    public ?string $margin_top = null;
    public ?string $marginBottom = null;
    public ?string $margin_bottom = null;
    public ?string $id = null;
    public ?string $customClass = null;
    public ?string $custom_class = null;

    /**
     * @return array<string, mixed>
     */
    public function getResolvedProps(): array
    {
        $resolved = $this->props;
        if (isset($resolved['props']) && \is_array($resolved['props'])) {
            $resolved = \array_merge($resolved['props'], $resolved);
        }

        $defaultCol = $this->columnsDefault ?? $this->columns_default;
        if (null !== $defaultCol) {
            $resolved['columns_default'] = (string) $defaultCol;
        }
        $smCol = $this->columnsSm ?? $this->columns_sm;
        if (null !== $smCol) {
            $resolved['columns_sm'] = (string) $smCol;
        }
        $mdCol = $this->columnsMd ?? $this->columns_md;
        if (null !== $mdCol) {
            $resolved['columns_md'] = (string) $mdCol;
        }
        $lgCol = $this->columnsLg ?? $this->columns_lg;
        if (null !== $lgCol) {
            $resolved['columns_lg'] = (string) $lgCol;
        }
        $xlCol = $this->columnsXl ?? $this->columns_xl;
        if (null !== $xlCol) {
            $resolved['columns_xl'] = (string) $xlCol;
        }
        if (null !== $this->gap) {
            $resolved['gap'] = (string) $this->gap;
        }
        if (null !== $this->masonry) {
            $resolved['masonry'] = $this->masonry;
        }
        if (null !== ($this->overlayPosition ?? $this->overlay_position)) {
            $resolved['overlay_position'] = $this->overlayPosition ?? $this->overlay_position;
        }
        if (null !== ($this->overlayMode ?? $this->overlay_mode)) {
            $resolved['overlay_mode'] = $this->overlayMode ?? $this->overlay_mode;
        }
        if (null !== ($this->overlayStyle ?? $this->overlay_style)) {
            $resolved['overlay_style'] = $this->overlayStyle ?? $this->overlay_style;
        }
        if (null !== ($this->overlayTransition ?? $this->overlay_transition)) {
            $resolved['overlay_transition'] = $this->overlayTransition ?? $this->overlay_transition;
        }
        if (null !== ($this->imageTransition ?? $this->image_transition)) {
            $resolved['image_transition'] = $this->imageTransition ?? $this->image_transition;
        }
        if (null !== ($this->mediaAspectRatio ?? $this->media_aspect_ratio)) {
            $resolved['media_aspect_ratio'] = $this->mediaAspectRatio ?? $this->media_aspect_ratio;
        }
        if (null !== $this->rounded) {
            $resolved['rounded'] = $this->rounded;
        }
        if (null !== $this->lightbox) {
            $resolved['lightbox'] = $this->lightbox;
        }
        if (null !== $this->filter) {
            $resolved['filter'] = $this->filter;
        }
        if (null !== ($this->filterStyle ?? $this->filter_style)) {
            $resolved['filter_style'] = $this->filterStyle ?? $this->filter_style;
        }
        if (null !== ($this->filterAlign ?? $this->filter_align)) {
            $resolved['filter_align'] = $this->filterAlign ?? $this->filter_align;
        }
        if (null !== ($this->filterAllLabel ?? $this->filter_all_label)) {
            $resolved['filter_all_label'] = $this->filterAllLabel ?? $this->filter_all_label;
        }
        if (null !== ($this->marginTop ?? $this->margin_top)) {
            $resolved['margin_top'] = $this->marginTop ?? $this->margin_top;
        }
        if (null !== ($this->marginBottom ?? $this->margin_bottom)) {
            $resolved['margin_bottom'] = $this->marginBottom ?? $this->margin_bottom;
        }
        if (null !== $this->id) {
            $resolved['id'] = $this->id;
        }
        if (null !== ($this->customClass ?? $this->custom_class)) {
            $resolved['custom_class'] = $this->customClass ?? $this->custom_class;
        }

        return $resolved;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $items = [];
        foreach ($this->items as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $typedItem */
                $typedItem = $item;
                $items[] = $typedItem;
            }
        }

        return $items;
    }

    public function isMasonry(): bool
    {
        $props = $this->getResolvedProps();
        $masonry = isset($props['masonry']) && \is_string($props['masonry']) ? $props['masonry'] : 'none';

        return 'columns' === $masonry;
    }

    public function getContainerClasses(): string
    {
        $props = $this->getResolvedProps();

        $gap = isset($props['gap']) && \is_scalar($props['gap']) ? (string) $props['gap'] : 'md';
        $gapClass = match ($gap) {
            'none', 'collapse', '0' => 'gap-0',
            'xs', 'xsmall' => 'gap-2',
            'sm', 'small' => 'gap-4',
            'lg', 'large' => 'gap-8',
            'xl', 'xlarge' => 'gap-12',
            '2xl', '2xlarge' => 'gap-16',
            default => 'gap-6',
        };

        $colsDefault = isset($props['columns_default']) && \is_scalar($props['columns_default']) ? (string) $props['columns_default'] : '1';
        $colsSm = isset($props['columns_sm']) && \is_scalar($props['columns_sm']) ? (string) $props['columns_sm'] : '2';
        $colsMd = isset($props['columns_md']) && \is_scalar($props['columns_md']) ? (string) $props['columns_md'] : '3';
        $colsLg = isset($props['columns_lg']) && \is_scalar($props['columns_lg']) ? (string) $props['columns_lg'] : '4';
        $colsXl = isset($props['columns_xl']) && \is_scalar($props['columns_xl']) ? (string) $props['columns_xl'] : '';

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
                '3' => 'sm:columns-3',
                '4' => 'sm:columns-4',
                '5' => 'sm:columns-5',
                '6' => 'sm:columns-6',
                default => 'sm:columns-2',
            };

            $mdColClass = match ($colsMd) {
                '1' => 'md:columns-1',
                '2' => 'md:columns-2',
                '4' => 'md:columns-4',
                '5' => 'md:columns-5',
                '6' => 'md:columns-6',
                default => 'md:columns-3',
            };

            $lgColClass = match ($colsLg) {
                '1' => 'lg:columns-1',
                '2' => 'lg:columns-2',
                '3' => 'lg:columns-3',
                '5' => 'lg:columns-5',
                '6' => 'lg:columns-6',
                default => 'lg:columns-4',
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
            '3' => 'sm:grid-cols-3',
            '4' => 'sm:grid-cols-4',
            '5' => 'sm:grid-cols-5',
            '6' => 'sm:grid-cols-6',
            default => 'sm:grid-cols-2',
        };

        $mdGridClass = match ($colsMd) {
            '1' => 'md:grid-cols-1',
            '2' => 'md:grid-cols-2',
            '4' => 'md:grid-cols-4',
            '5' => 'md:grid-cols-5',
            '6' => 'md:grid-cols-6',
            default => 'md:grid-cols-3',
        };

        $lgGridClass = match ($colsLg) {
            '1' => 'lg:grid-cols-1',
            '2' => 'lg:grid-cols-2',
            '3' => 'lg:grid-cols-3',
            '5' => 'lg:grid-cols-5',
            '6' => 'lg:grid-cols-6',
            default => 'lg:grid-cols-4',
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
        $props = $this->getResolvedProps();

        return !empty($props['filter']);
    }

    public function isLightboxEnabled(): bool
    {
        $props = $this->getResolvedProps();

        return !empty($props['lightbox']);
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
        $props = $this->getResolvedProps();
        $align = isset($props['filter_align']) && \is_string($props['filter_align']) ? $props['filter_align'] : 'center';

        return match ($align) {
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
        $props = $this->getResolvedProps();
        $itemProps = isset($item['props']) && \is_array($item['props']) ? $item['props'] : [];

        $overlayPosition = isset($props['overlay_position']) && \is_string($props['overlay_position']) ? $props['overlay_position'] : 'bottom';
        $overlayMode = isset($props['overlay_mode']) && \is_string($props['overlay_mode']) ? $props['overlay_mode'] : 'cover';

        $overlayStyle = isset($props['overlay_style']) && \is_string($props['overlay_style']) ? $props['overlay_style'] : 'gradient';
        if (isset($item['overlay_style']) && \is_string($item['overlay_style']) && 'inherit' !== $item['overlay_style'] && '' !== \trim($item['overlay_style'])) {
            $overlayStyle = $item['overlay_style'];
        }

        $overlayTransition = isset($props['overlay_transition']) && \is_string($props['overlay_transition']) ? $props['overlay_transition'] : 'fade';
        $imageTransition = isset($props['image_transition']) && \is_string($props['image_transition']) ? $props['image_transition'] : 'zoom';
        $mediaAspectRatio = isset($props['media_aspect_ratio']) && \is_string($props['media_aspect_ratio']) ? $props['media_aspect_ratio'] : '4/3';
        $rounded = isset($props['rounded']) && \is_string($props['rounded']) ? $props['rounded'] : 'xl';

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
        $image = $item['image'] ?? null;

        return PropsResolver::resolveImageUrl($image);
    }

    public function getMarginClass(): string
    {
        return ElementTransform::margin($this->getResolvedProps());
    }

    public function getCustomId(): ?string
    {
        $props = $this->getResolvedProps();

        return isset($props['id']) && \is_string($props['id']) && '' !== \trim($props['id']) ? \trim($props['id']) : null;
    }

    public function getCustomClass(): string
    {
        $props = $this->getResolvedProps();

        return isset($props['custom_class']) && \is_string($props['custom_class']) ? \trim($props['custom_class']) : '';
    }
}
