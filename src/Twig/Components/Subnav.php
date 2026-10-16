<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Subnav', template: '@SuluPrelineBlocks/components/Subnav.html.twig')]
final class Subnav extends PrelineComponent
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
            'style' => 'pills',
            'alignment' => 'start',
            'size' => 'md',
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
            'horizontal' => true,
            'linked' => true,
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

    public function getStyle(): string
    {
        return $this->enumProp('style', ['pills', 'underline', 'segmented', 'divider'], 'pills');
    }

    public function getAlignment(): string
    {
        return $this->enumProp('alignment', ['start', 'center', 'end', 'between'], 'start');
    }

    public function getSize(): string
    {
        return $this->enumProp('size', ['sm', 'md', 'lg'], 'md');
    }

    public function getNavClasses(): string
    {
        $classes = ['flex items-center flex-wrap overflow-x-auto'];
        $style = $this->getStyle();

        $classes[] = PrelineClassMap::resolve(
            PrelineClassMap::FLEX_ALIGN,
            $this->getAlignment(),
            PrelineClassMap::FLEX_ALIGN['start'],
        );

        if ('segmented' === $style) {
            $classes[] = 'p-1.5 bg-surface rounded-xl gap-1';
        } elseif ('underline' === $style) {
            $classes[] = 'gap-x-6 border-b border-border';
        } elseif ('divider' === $style) {
            $classes[] = 'gap-x-4 divide-x divide-border';
        } else {
            $classes[] = 'gap-2';
        }

        return \implode(' ', $classes);
    }

    public function getItemClasses(bool $isActive): string
    {
        $classes = ['inline-flex items-center gap-x-2 font-medium transition-colors focus:outline-hidden whitespace-nowrap'];
        $style = $this->getStyle();

        $classes[] = PrelineClassMap::resolve(
            PrelineClassMap::SUBNAV_SIZES,
            $this->getSize(),
            PrelineClassMap::SUBNAV_SIZES['md'],
        );

        if ('pills' === $style) {
            $classes[] = 'rounded-lg';
            if ($isActive) {
                $classes[] = 'bg-primary text-primary-foreground shadow-2xs';
            } else {
                $classes[] = 'text-muted-foreground-2 hover:text-foreground hover:bg-muted';
            }
        } elseif ('segmented' === $style) {
            $classes[] = 'rounded-lg';
            if ($isActive) {
                $classes[] = 'bg-layer text-layer-foreground shadow-2xs font-semibold';
            } else {
                $classes[] = 'text-muted-foreground-2 hover:text-foreground';
            }
        } elseif ('underline' === $style) {
            $classes[] = 'border-b-2 -mb-px px-1';
            if ($isActive) {
                $classes[] = 'border-primary text-primary font-semibold';
            } else {
                $classes[] = 'border-transparent text-muted-foreground-1 hover:text-foreground hover:border-line-3';
            }
        } elseif ('divider' === $style) {
            $classes[] = 'first:ps-0 ps-4';
            if ($isActive) {
                $classes[] = 'text-primary font-semibold';
            } else {
                $classes[] = 'text-muted-foreground-2 hover:text-primary';
            }
        }

        return \implode(' ', $classes);
    }
}
