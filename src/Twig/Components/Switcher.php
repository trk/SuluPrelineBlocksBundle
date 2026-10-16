<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Switcher', template: '@SuluPrelineBlocks/components/Switcher.html.twig')]
final class Switcher extends PrelineComponent
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
            'nav_position' => 'top',
            'nav_style' => 'pills',
            'alignment' => 'start',
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
            'vertical' => fn (): bool => $this->isVertical(),
            'horizontal' => fn (): bool => !$this->isVertical(),
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

    public function getNavPosition(): string
    {
        return $this->enumProp('nav_position', ['top', 'left', 'right', 'bottom'], 'top');
    }

    public function getNavStyle(): string
    {
        return $this->enumProp('nav_style', ['pills', 'underline', 'bordered', 'segmented'], 'pills');
    }

    public function getAlignment(): string
    {
        return $this->enumProp('alignment', ['start', 'center', 'end', 'full'], 'start');
    }

    public function isVertical(): bool
    {
        $pos = $this->getNavPosition();

        return 'left' === $pos || 'right' === $pos;
    }

    public function getTabNavClasses(): string
    {
        $classes = ['flex'];
        $isVert = $this->isVertical();
        $style = $this->getNavStyle();
        $align = $this->getAlignment();

        if ($isVert) {
            $classes[] = 'flex-col space-y-1.5';
        } else {
            $classes[] = 'flex-wrap';
            $classes[] = match ($align) {
                'center' => 'justify-center',
                'end' => 'justify-end',
                'full' => 'grid grid-flow-col auto-cols-fr text-center',
                default => 'justify-start',
            };

            if ('segmented' === $style) {
                $classes[] = 'p-1.5 bg-surface rounded-xl gap-1';
            } elseif ('underline' === $style) {
                $classes[] = 'gap-x-6 border-b border-border';
            } else {
                $classes[] = 'gap-2';
            }
        }

        return \implode(' ', $classes);
    }

    public function getTabButtonClasses(): string
    {
        $classes = [
            'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium transition-colors focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none',
        ];
        $style = $this->getNavStyle();
        $isVert = $this->isVertical();
        $align = $this->getAlignment();

        if ('full' === $align && !$isVert) {
            $classes[] = 'justify-center';
        }

        if ('pills' === $style) {
            $classes[] = 'rounded-lg text-muted-foreground-2 hover:text-foreground hover:bg-muted';
            $classes[] = 'hs-tab-active:bg-primary hs-tab-active:text-primary-foreground';
        } elseif ('segmented' === $style) {
            $classes[] = 'rounded-lg text-muted-foreground-2 hover:text-foreground';
            $classes[] = 'hs-tab-active:bg-layer hs-tab-active:text-layer-foreground hs-tab-active:shadow-2xs hs-tab-active:font-semibold';
        } elseif ('underline' === $style) {
            $classes[] = 'border-b-2 -mb-px border-transparent text-muted-foreground-1 hover:text-foreground';
            $classes[] = 'hs-tab-active:border-primary hs-tab-active:text-primary hs-tab-active:font-semibold';
        } elseif ('bordered' === $style) {
            $classes[] = 'rounded-lg border border-card-line text-foreground hover:bg-muted';
            $classes[] = 'hs-tab-active:border-primary hs-tab-active:bg-primary-50 hs-tab-active:text-primary dark:hs-tab-active:bg-primary-950/40';
        }

        return \implode(' ', $classes);
    }
}
