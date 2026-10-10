<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('List', template: '@SuluPrelineBlocks/components/List.html.twig')]
final class ListBlock
{
    public ?string $title = null;

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public ?string $listType = null;
    public ?string $list_type = null;
    public ?string $marker = null;
    public ?string $markerColor = null;
    public ?string $marker_color = null;
    public ?string $style = null;
    public ?string $size = null;
    public ?string $columns = null;
    public ?string $spacing = null;

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
            /** @var array<string, mixed> $nested */
            $nested = $resolved['props'];
            $resolved = \array_merge($nested, $resolved);
            unset($resolved['props']);
        }

        if (null !== ($this->listType ?? $this->list_type)) {
            $resolved['list_type'] = $this->listType ?? $this->list_type;
        }
        if (null !== $this->marker) {
            $resolved['marker'] = $this->marker;
        }
        if (null !== ($this->markerColor ?? $this->marker_color)) {
            $resolved['marker_color'] = $this->markerColor ?? $this->marker_color;
        }
        if (null !== $this->style) {
            $resolved['style'] = $this->style;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== $this->columns) {
            $resolved['columns'] = $this->columns;
        }
        if (null !== $this->spacing) {
            $resolved['spacing'] = $this->spacing;
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
        $normalized = [];
        foreach ($this->items as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $itemArr */
                $itemArr = $item;

                if (isset($itemArr['image'])) {
                    $itemArr['image_url'] = PropsResolver::resolveImageUrl($itemArr['image']);
                }

                if (isset($itemArr['link'])) {
                    $itemArr['link_href'] = PropsResolver::resolveUrl($itemArr['link']);
                }

                $normalized[] = $itemArr;
            }
        }

        return $normalized;
    }

    public function getListType(): string
    {
        $props = $this->getResolvedProps();

        return ($props['list_type'] ?? 'vertical') === 'horizontal' ? 'horizontal' : 'vertical';
    }

    public function getMarker(): string
    {
        $props = $this->getResolvedProps();
        $marker = $props['marker'] ?? 'none';

        return match ($marker) {
            'disc' => 'disc',
            'circle' => 'circle',
            'decimal' => 'decimal',
            'checkmark' => 'checkmark',
            'icon' => 'icon',
            default => 'none',
        };
    }

    public function getMarkerColor(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['marker_color'] ?? 'primary';

        return match ($color) {
            'secondary' => 'text-secondary-600 dark:text-secondary-400',
            'muted', 'neutral' => 'text-gray-400 dark:text-neutral-500',
            default => 'text-primary-600 dark:text-primary-500',
        };
    }

    public function getStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['style'] ?? 'default';

        return match ($style) {
            'divider' => 'divider',
            'striped' => 'striped',
            default => 'default',
        };
    }

    public function getSize(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['size'] ?? 'md';

        return match ($size) {
            'sm' => 'sm',
            'lg' => 'lg',
            default => 'md',
        };
    }

    public function getSpacingClasses(): string
    {
        $props = $this->getResolvedProps();
        $spacing = $props['spacing'] ?? 'md';
        $isHoriz = $this->getListType() === 'horizontal';

        if ($isHoriz) {
            return match ($spacing) {
                'sm' => 'gap-x-3 gap-y-2',
                'lg' => 'gap-x-8 gap-y-4',
                default => 'gap-x-5 gap-y-3',
            };
        }

        return match ($spacing) {
            'sm' => 'space-y-1.5',
            'lg' => 'space-y-4',
            default => 'space-y-2.5',
        };
    }

    public function getListClasses(): string
    {
        $classes = [];
        $isHoriz = $this->getListType() === 'horizontal';
        $style = $this->getStyle();
        $marker = $this->getMarker();
        $props = $this->getResolvedProps();

        if ($isHoriz) {
            $classes[] = 'flex flex-wrap items-center';
            $classes[] = $this->getSpacingClasses();
            if ($style === 'divider') {
                $classes[] = 'divide-x divide-gray-200 dark:divide-neutral-700';
            }
        } else {
            $columns = $props['columns'] ?? '1';
            if ($columns === '2') {
                $classes[] = 'grid sm:grid-cols-2 gap-x-6';
            } elseif ($columns === '3') {
                $classes[] = 'grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6';
            }

            if ($columns === '1') {
                $classes[] = $this->getSpacingClasses();
            } else {
                $classes[] = 'gap-y-3';
            }

            if ($style === 'divider') {
                $classes[] = 'divide-y divide-gray-200 dark:divide-neutral-700';
            }

            if ($marker === 'disc') {
                $classes[] = 'list-disc list-inside';
            } elseif ($marker === 'circle') {
                $classes[] = 'list-[circle] list-inside';
            } elseif ($marker === 'decimal') {
                $classes[] = 'list-decimal list-inside';
            }
        }

        return \implode(' ', \array_filter($classes));
    }

    public function getItemClasses(): string
    {
        $classes = [];
        $style = $this->getStyle();
        $size = $this->getSize();
        $isHoriz = $this->getListType() === 'horizontal';

        $classes[] = match ($size) {
            'sm' => 'text-xs',
            'lg' => 'text-base sm:text-lg',
            default => 'text-sm sm:text-base',
        };

        $classes[] = 'text-gray-800 dark:text-neutral-200';

        if ($style === 'striped') {
            $classes[] = 'p-3 rounded-lg odd:bg-transparent even:bg-gray-50 dark:even:bg-neutral-800/60';
        } elseif ($style === 'divider' && !$isHoriz) {
            $classes[] = 'pt-2.5 pb-2.5 first:pt-0 last:pb-0';
        } elseif ($style === 'divider' && $isHoriz) {
            $classes[] = 'px-3 first:ps-0 last:pe-0';
        }

        return \implode(' ', $classes);
    }

    public function getMarginClass(): string
    {
        $props = $this->getResolvedProps();
        $classes = [];

        $top = $props['margin_top'] ?? null;
        if (\is_string($top) && '' !== $top) {
            $classes[] = match ($top) {
                'sm' => 'mt-4',
                'md' => 'mt-8',
                'lg' => 'mt-12',
                'xl' => 'mt-16',
                default => '',
            };
        }

        $bottom = $props['margin_bottom'] ?? null;
        if (\is_string($bottom) && '' !== $bottom) {
            $classes[] = match ($bottom) {
                'sm' => 'mb-4',
                'md' => 'mb-8',
                'lg' => 'mb-12',
                'xl' => 'mb-16',
                default => '',
            };
        }

        return \trim(\implode(' ', \array_filter($classes)));
    }

    public function getCustomId(): ?string
    {
        $props = $this->getResolvedProps();
        $id = $props['id'] ?? null;

        return \is_string($id) && '' !== \trim($id) ? \trim($id) : null;
    }

    public function getCustomClass(): string
    {
        $props = $this->getResolvedProps();
        $class = $props['custom_class'] ?? null;

        return \is_string($class) ? \trim($class) : '';
    }
}
