<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Grid', template: '@SuluPrelineBlocks/components/Grid.html.twig')]
final class Grid extends AbstractPrelineComponent
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
            'filter' => false,
            'filter_style' => 'pills',
            'filter_align' => 'center',
            'filter_all_label' => 'All',
            'card_variant' => 'default',
            'media_align' => 'top',
            'text_align' => 'left',
            'button_style' => 'primary',
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
            'card_group' => fn (): bool => $this->isCardGroup(),
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

    public function isCardGroup(): bool
    {
        $layout = $this->nullableStringProp('layout_type')
            ?? ($this->boolProp('card_group', false) ? 'card_group' : 'grid');

        return 'card_group' === $layout;
    }

    public function getContainerClasses(): string
    {
        if ($this->isCardGroup()) {
            return 'grid border border-card-line bg-card rounded-xl shadow-2xs divide-y overflow-hidden sm:flex sm:divide-y-0 sm:divide-x divide-card-divider';
        }

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

        $cardVariant = $this->stringProp('card_variant', 'default');
        if ($this->isCardGroup()) {
            $cardVariant = 'group';
        } elseif (isset($item['panel_style']) && \is_string($item['panel_style']) && 'inherit' !== $item['panel_style'] && '' !== \trim($item['panel_style'])) {
            $cardVariant = $item['panel_style'];
        }

        $buttonStyle = $this->stringProp('button_style', 'primary');
        if (isset($item['link_style']) && \is_string($item['link_style']) && 'inherit' !== $item['link_style'] && '' !== \trim($item['link_style'])) {
            $buttonStyle = $item['link_style'];
        }

        $mediaAlign = $this->stringProp('media_align', 'top');
        $textAlign = $this->stringProp('text_align', 'left');

        /** @var array<string, mixed> $merged */
        $merged = \array_merge([
            'card_variant' => $cardVariant,
            'button_style' => $buttonStyle,
            'media_align' => $mediaAlign,
            'text_align' => $textAlign,
        ], $itemProps);

        return $merged;
    }
}
