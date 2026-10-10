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

    public ?string $columnsDefault = null;
    public ?string $columns_default = null;
    public ?string $columnsSm = null;
    public ?string $columns_sm = null;
    public ?string $columnsMd = null;
    public ?string $columns_md = null;
    public ?string $columnsLg = null;
    public ?string $columns_lg = null;
    public ?string $gap = null;
    public ?string $masonry = null;

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

        if (null !== ($this->columnsDefault ?? $this->columns_default)) {
            $resolved['columns_default'] = $this->columnsDefault ?? $this->columns_default;
        }
        if (null !== ($this->columnsSm ?? $this->columns_sm)) {
            $resolved['columns_sm'] = $this->columnsSm ?? $this->columns_sm;
        }
        if (null !== ($this->columnsMd ?? $this->columns_md)) {
            $resolved['columns_md'] = $this->columnsMd ?? $this->columns_md;
        }
        if (null !== ($this->columnsLg ?? $this->columns_lg)) {
            $resolved['columns_lg'] = $this->columnsLg ?? $this->columns_lg;
        }
        if (null !== $this->gap) {
            $resolved['gap'] = $this->gap;
        }
        if (null !== $this->masonry) {
            $resolved['masonry'] = $this->masonry;
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

    public function getContainerClasses(): string
    {
        $props = $this->getResolvedProps();

        $gap = isset($props['gap']) && \is_string($props['gap']) ? $props['gap'] : 'md';
        $gapClass = match ($gap) {
            'collapse' => 'gap-0',
            'sm' => 'gap-4',
            'lg' => 'gap-8',
            'xl' => 'gap-12',
            default => 'gap-6',
        };

        if ($this->isMasonry()) {
            $colsDefault = isset($props['columns_default']) && \is_string($props['columns_default']) ? $props['columns_default'] : '1';
            $colsSm = isset($props['columns_sm']) && \is_string($props['columns_sm']) ? $props['columns_sm'] : '2';
            $colsMd = isset($props['columns_md']) && \is_string($props['columns_md']) ? $props['columns_md'] : '3';
            $colsLg = isset($props['columns_lg']) && \is_string($props['columns_lg']) ? $props['columns_lg'] : '3';

            $defaultColClass = match ($colsDefault) {
                '2' => 'columns-2',
                default => 'columns-1',
            };

            $smColClass = match ($colsSm) {
                '1' => 'sm:columns-1',
                '3' => 'sm:columns-3',
                default => 'sm:columns-2',
            };

            $mdColClass = match ($colsMd) {
                '1' => 'md:columns-1',
                '2' => 'md:columns-2',
                '4' => 'md:columns-4',
                default => 'md:columns-3',
            };

            $lgColClass = match ($colsLg) {
                '1' => 'lg:columns-1',
                '2' => 'lg:columns-2',
                '4' => 'lg:columns-4',
                '5' => 'lg:columns-5',
                '6' => 'lg:columns-6',
                default => 'lg:columns-3',
            };

            return \sprintf('%s %s %s %s %s', $defaultColClass, $smColClass, $mdColClass, $lgColClass, $gapClass);
        }

        $colsDefault = isset($props['columns_default']) && \is_string($props['columns_default']) ? $props['columns_default'] : '1';
        $colsSm = isset($props['columns_sm']) && \is_string($props['columns_sm']) ? $props['columns_sm'] : '2';
        $colsMd = isset($props['columns_md']) && \is_string($props['columns_md']) ? $props['columns_md'] : '3';
        $colsLg = isset($props['columns_lg']) && \is_string($props['columns_lg']) ? $props['columns_lg'] : '3';

        $defaultGridClass = match ($colsDefault) {
            '2' => 'grid-cols-2',
            default => 'grid-cols-1',
        };

        $smGridClass = match ($colsSm) {
            '1' => 'sm:grid-cols-1',
            '3' => 'sm:grid-cols-3',
            default => 'sm:grid-cols-2',
        };

        $mdGridClass = match ($colsMd) {
            '1' => 'md:grid-cols-1',
            '2' => 'md:grid-cols-2',
            '4' => 'md:grid-cols-4',
            default => 'md:grid-cols-3',
        };

        $lgGridClass = match ($colsLg) {
            '1' => 'lg:grid-cols-1',
            '2' => 'lg:grid-cols-2',
            '4' => 'lg:grid-cols-4',
            '5' => 'lg:grid-cols-5',
            '6' => 'lg:grid-cols-6',
            default => 'lg:grid-cols-3',
        };

        return \sprintf('grid %s %s %s %s %s', $defaultGridClass, $smGridClass, $mdGridClass, $lgGridClass, $gapClass);
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

        // Determine card variant (item panel_style override takes priority if not 'inherit')
        $cardVariant = isset($props['card_variant']) && \is_string($props['card_variant']) ? $props['card_variant'] : 'default';
        if (isset($item['panel_style']) && \is_string($item['panel_style']) && 'inherit' !== $item['panel_style']) {
            $cardVariant = $item['panel_style'];
        }

        // Determine button style (item link_style override takes priority if not 'inherit')
        $buttonStyle = isset($props['button_style']) && \is_string($props['button_style']) ? $props['button_style'] : 'primary';
        if (isset($item['link_style']) && \is_string($item['link_style']) && 'inherit' !== $item['link_style']) {
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
