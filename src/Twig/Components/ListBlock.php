<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('List', template: '@SuluPrelineBlocks/components/List.html.twig')]
final class ListBlock extends PrelineComponent
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
            $normalized[] = $this->normalizeItemMediaAndLinks($item);
        }

        return $normalized;
    }

    public function getListType(): string
    {
        return 'horizontal' === $this->stringProp('list_type', 'vertical') ? 'horizontal' : 'vertical';
    }

    public function getMarker(): string
    {
        return $this->enumProp('marker', ['none', 'disc', 'circle', 'decimal', 'checkmark', 'icon'], 'none');
    }

    public function getMarkerColor(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::MARKER_COLORS,
            $this->stringProp('marker_color', 'primary'),
            PrelineClassMap::MARKER_COLORS['primary'],
        );
    }

    public function getStyle(): string
    {
        return $this->enumProp('style', ['default', 'divider', 'striped'], 'default');
    }

    public function getSize(): string
    {
        return $this->enumProp('size', ['sm', 'md', 'lg'], 'md');
    }

    public function getSpacingClasses(): string
    {
        $spacing = $this->stringProp('spacing', 'md');

        if ('horizontal' === $this->getListType()) {
            return PrelineClassMap::resolve(
                PrelineClassMap::LIST_SPACING_HORIZONTAL,
                $spacing,
                PrelineClassMap::LIST_SPACING_HORIZONTAL['md'],
            );
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::LIST_SPACING_VERTICAL,
            $spacing,
            PrelineClassMap::LIST_SPACING_VERTICAL['md'],
        );
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
        $isHoriz = 'horizontal' === $this->getListType();

        $classes[] = PrelineClassMap::resolve(
            PrelineClassMap::LIST_ITEM_SIZES,
            $this->getSize(),
            PrelineClassMap::LIST_ITEM_SIZES['md'],
        );

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
}
