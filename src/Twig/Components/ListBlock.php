<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('List', template: '@SuluPrelineBlocks/components/List.html.twig')]
final class ListBlock extends AbstractPrelineComponent
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
            'list_type' => 'vertical',
            'marker' => 'none',
            'marker_color' => 'primary',
            'style' => 'default',
            'size' => 'md',
            'columns' => '1',
            'spacing' => 'md',
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
            'horizontal' => fn (): bool => 'horizontal' === $this->getListType(),
            'vertical' => fn (): bool => 'vertical' === $this->getListType(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items('items') as $item) {
            if (isset($item['image'])) {
                $item['image_url'] = PropsResolver::resolveImageUrl($item['image']);
                $item['image_focus_style'] = PropsResolver::resolveFocusPointStyle($item['image']);
            }

            if (isset($item['link'])) {
                $item['link_href'] = PropsResolver::resolveUrl($item['link']);
            }

            $normalized[] = $item;
        }

        return $normalized;
    }

    public function getListType(): string
    {
        return 'horizontal' === $this->stringProp('list_type', 'vertical') ? 'horizontal' : 'vertical';
    }

    public function getMarker(): string
    {
        return match ($this->stringProp('marker', 'none')) {
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
        return match ($this->stringProp('marker_color', 'primary')) {
            'secondary' => 'text-secondary',
            'muted', 'neutral' => 'text-muted-foreground',
            default => 'text-primary',
        };
    }

    public function getStyle(): string
    {
        return match ($this->stringProp('style', 'default')) {
            'divider' => 'divider',
            'striped' => 'striped',
            default => 'default',
        };
    }

    public function getSize(): string
    {
        return match ($this->stringProp('size', 'md')) {
            'sm' => 'sm',
            'lg' => 'lg',
            default => 'md',
        };
    }

    public function getSpacingClasses(): string
    {
        $spacing = $this->stringProp('spacing', 'md');

        if ('horizontal' === $this->getListType()) {
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
        $isHoriz = 'horizontal' === $this->getListType();
        $style = $this->getStyle();
        $marker = $this->getMarker();

        if ($isHoriz) {
            $classes[] = 'flex flex-wrap items-center';
            $classes[] = $this->getSpacingClasses();
            if ('divider' === $style) {
                $classes[] = 'divide-x divide-border';
            }
        } else {
            $columns = $this->stringProp('columns', '1');
            if ('2' === $columns) {
                $classes[] = 'grid sm:grid-cols-2 gap-x-6';
            } elseif ('3' === $columns) {
                $classes[] = 'grid sm:grid-cols-2 lg:grid-cols-3 gap-x-6';
            }

            if ('1' === $columns) {
                $classes[] = $this->getSpacingClasses();
            } else {
                $classes[] = 'gap-y-3';
            }

            if ('divider' === $style) {
                $classes[] = 'divide-y divide-border';
            }

            if ('disc' === $marker) {
                $classes[] = 'list-disc list-inside';
            } elseif ('circle' === $marker) {
                $classes[] = 'list-[circle] list-inside';
            } elseif ('decimal' === $marker) {
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
        $isHoriz = 'horizontal' === $this->getListType();

        $classes[] = match ($size) {
            'sm' => 'text-xs',
            'lg' => 'text-base sm:text-lg',
            default => 'text-sm sm:text-base',
        };

        $classes[] = 'text-foreground';

        if ('striped' === $style) {
            $classes[] = 'p-3 rounded-lg odd:bg-transparent even:bg-background-1';
        } elseif ('divider' === $style && !$isHoriz) {
            $classes[] = 'pt-2.5 pb-2.5 first:pt-0 last:pb-0';
        } elseif ('divider' === $style && $isHoriz) {
            $classes[] = 'px-3 first:ps-0 last:pe-0';
        }

        return \implode(' ', $classes);
    }

    public function getMarginClass(): string
    {
        $top = match ($this->stringProp('margin_top', 'none')) {
            'sm' => 'mt-4',
            'md' => 'mt-8',
            'lg' => 'mt-12',
            'xl' => 'mt-16',
            default => '',
        };

        $bottom = match ($this->stringProp('margin_bottom', 'none')) {
            'sm' => 'mb-4',
            'md' => 'mb-8',
            'lg' => 'mb-12',
            'xl' => 'mb-16',
            default => '',
        };

        return \trim($top . ' ' . $bottom);
    }
}
