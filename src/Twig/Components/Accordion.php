<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Accordion', template: '@SuluPrelineBlocks/components/Accordion.html.twig')]
final class Accordion extends AbstractPrelineComponent
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
            'style' => 'default',
            'icon_style' => 'chevron',
            'multiple' => false,
            'collapsible' => true,
            'title_size' => 'base',
            'image_align' => 'top',
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
            'collapsible' => fn (): bool => $this->isCollapsible(),
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
        return match ($this->stringProp('style', 'default')) {
            'bordered' => 'bordered',
            'soft' => 'soft',
            default => 'default',
        };
    }

    public function getIconStyle(): string
    {
        return match ($this->stringProp('icon_style', 'chevron')) {
            'plus' => 'plus',
            'none' => 'none',
            default => 'chevron',
        };
    }

    public function isMultiple(): bool
    {
        return $this->boolProp('multiple', false);
    }

    public function isCollapsible(): bool
    {
        return $this->boolProp('collapsible', true);
    }

    public function getTitleSizeClasses(): string
    {
        return match ($this->stringProp('title_size', 'base')) {
            'sm' => 'text-sm font-medium',
            'lg' => 'text-lg font-semibold',
            'xl' => 'text-xl font-bold',
            default => 'text-base font-semibold',
        };
    }

    public function getImageAlign(): string
    {
        return match ($this->stringProp('image_align', 'top')) {
            'bottom' => 'bottom',
            'left' => 'left',
            'right' => 'right',
            default => 'top',
        };
    }

    public function getContainerClasses(): string
    {
        return match ($this->getStyle()) {
            'bordered', 'soft' => 'space-y-3',
            default => 'divide-y divide-border',
        };
    }

    public function getItemClasses(): string
    {
        return match ($this->getStyle()) {
            'bordered' => 'hs-accordion border border-card-line bg-card rounded-xl p-4',
            'soft' => 'hs-accordion bg-surface rounded-xl p-4',
            default => 'hs-accordion pt-4 pb-3',
        };
    }

    public function getToggleClasses(): string
    {
        return 'hs-accordion-toggle hs-accordion-active:text-primary inline-flex items-center justify-between gap-x-3 w-full text-start text-foreground hover:text-muted-foreground-1 rounded-lg disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden';
    }

    public function getContentWrapperClasses(): string
    {
        return match ($this->getStyle()) {
            'bordered', 'soft' => 'pt-3',
            default => 'pt-2 pb-1',
        };
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
