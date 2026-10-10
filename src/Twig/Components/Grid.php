<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Grid', template: '@SuluPrelineBlocks/components/Grid.html.twig')]
final class Grid
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
    public ?string $layoutType = null;
    public ?string $layout_type = null;

    public ?bool $filter = null;
    public ?string $filterStyle = null;
    public ?string $filter_style = null;
    public ?string $filterAlign = null;
    public ?string $filter_align = null;
    public ?string $filterAllLabel = null;
    public ?string $filter_all_label = null;

    public ?string $cardVariant = null;
    public ?string $card_variant = null;
    public ?string $mediaAlign = null;
    public ?string $media_align = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?string $buttonStyle = null;
    public ?string $button_style = null;

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
        if (null !== ($this->layoutType ?? $this->layout_type)) {
            $resolved['layout_type'] = $this->layoutType ?? $this->layout_type;
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
        if (null !== ($this->cardVariant ?? $this->card_variant)) {
            $resolved['card_variant'] = $this->cardVariant ?? $this->card_variant;
        }
        if (null !== ($this->mediaAlign ?? $this->media_align)) {
            $resolved['media_align'] = $this->mediaAlign ?? $this->media_align;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->buttonStyle ?? $this->button_style)) {
            $resolved['button_style'] = $this->buttonStyle ?? $this->button_style;
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

    public function isCardGroup(): bool
    {
        $props = $this->getResolvedProps();
        $layout = isset($props['layout_type']) && \is_string($props['layout_type'])
            ? $props['layout_type']
            : (isset($props['card_group']) && !empty($props['card_group']) ? 'card_group' : 'grid');

        return 'card_group' === $layout;
    }

    public function getContainerClasses(): string
    {
        if ($this->isCardGroup()) {
            return 'grid border border-gray-200 dark:border-neutral-700 rounded-xl shadow-2xs divide-y overflow-hidden sm:flex sm:divide-y-0 sm:divide-x divide-gray-200 dark:divide-neutral-700';
        }

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

        $colsDefault = isset($props['columns_default']) && \is_scalar($props['columns_default']) && '' !== (string) $props['columns_default'] && 'inherit' !== (string) $props['columns_default'] && 'none' !== (string) $props['columns_default']
            ? (string) $props['columns_default']
            : '1';

        $hasExplicitResponsive = \array_key_exists('columns_sm', $props)
            || \array_key_exists('columns_md', $props)
            || \array_key_exists('columns_lg', $props)
            || \array_key_exists('columns_xl', $props);

        $colsSm = isset($props['columns_sm']) && \is_scalar($props['columns_sm']) && 'inherit' !== (string) $props['columns_sm'] && 'none' !== (string) $props['columns_sm']
            ? (string) $props['columns_sm']
            : '';
        $colsMd = isset($props['columns_md']) && \is_scalar($props['columns_md']) && 'inherit' !== (string) $props['columns_md'] && 'none' !== (string) $props['columns_md']
            ? (string) $props['columns_md']
            : (!$hasExplicitResponsive ? '3' : '');
        $colsLg = isset($props['columns_lg']) && \is_scalar($props['columns_lg']) && 'inherit' !== (string) $props['columns_lg'] && 'none' !== (string) $props['columns_lg']
            ? (string) $props['columns_lg']
            : '';
        $colsXl = isset($props['columns_xl']) && \is_scalar($props['columns_xl']) && 'inherit' !== (string) $props['columns_xl'] && 'none' !== (string) $props['columns_xl']
            ? (string) $props['columns_xl']
            : '';

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
        $props = $this->getResolvedProps();

        return !empty($props['filter']);
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

        // Determine card variant (item panel_style override takes priority if not 'inherit' and not empty)
        $cardVariant = isset($props['card_variant']) && \is_string($props['card_variant']) ? $props['card_variant'] : 'default';
        if ($this->isCardGroup()) {
            $cardVariant = 'group';
        } elseif (isset($item['panel_style']) && \is_string($item['panel_style']) && 'inherit' !== $item['panel_style'] && '' !== \trim($item['panel_style'])) {
            $cardVariant = $item['panel_style'];
        }

        // Determine button style (item link_style override takes priority if not 'inherit' and not empty)
        $buttonStyle = isset($props['button_style']) && \is_string($props['button_style']) ? $props['button_style'] : 'primary';
        if (isset($item['link_style']) && \is_string($item['link_style']) && 'inherit' !== $item['link_style'] && '' !== \trim($item['link_style'])) {
            $buttonStyle = $item['link_style'];
        }

        $mediaAlign = isset($props['media_align']) && \is_string($props['media_align']) ? $props['media_align'] : 'top';
        $textAlign = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : 'left';

        /** @var array<string, mixed> $merged */
        $merged = \array_merge([
            'card_variant' => $cardVariant,
            'button_style' => $buttonStyle,
            'media_align' => $mediaAlign,
            'text_align' => $textAlign,
        ], $itemProps);

        return $merged;
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
