<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Subnav', template: '@SuluPrelineBlocks/components/Subnav.html.twig')]
final class Subnav extends AbstractPrelineComponent
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
            if (isset($item['link'])) {
                $item['link_href'] = PropsResolver::resolveUrl($item['link']);
            }
            $normalized[] = $item;
        }

        return $normalized;
    }

    public function getStyle(): string
    {
        return match ($this->stringProp('style', 'pills')) {
            'underline' => 'underline',
            'segmented' => 'segmented',
            'divider' => 'divider',
            default => 'pills',
        };
    }

    public function getAlignment(): string
    {
        return match ($this->stringProp('alignment', 'start')) {
            'center' => 'center',
            'end' => 'end',
            'between' => 'between',
            default => 'start',
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

    public function getNavClasses(): string
    {
        $classes = ['flex items-center flex-wrap overflow-x-auto'];
        $align = $this->getAlignment();
        $style = $this->getStyle();

        $classes[] = match ($align) {
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };

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
        $size = $this->getSize();

        $classes[] = match ($size) {
            'sm' => 'text-xs py-1 px-2.5',
            'lg' => 'text-base py-3 px-5',
            default => 'text-sm py-2 px-3.5',
        };

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
