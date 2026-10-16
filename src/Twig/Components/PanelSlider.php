<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('PanelSlider', template: '@SuluPrelineBlocks/components/PanelSlider.html.twig')]
final class PanelSlider extends AbstractPrelineComponent
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
            'autoplay' => false,
            'interval' => 4000,
            'loop' => true,
            'arrows' => 'inside',
            'dots' => 'bottom',
            'columns' => '3',
            'gap' => 'md',
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
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        return $this->items('items');
    }

    public function isAutoplay(): bool
    {
        return $this->boolProp('autoplay', false);
    }

    public function getInterval(): int
    {
        return $this->intProp('interval', 4000);
    }

    public function isLoop(): bool
    {
        return $this->boolProp('loop', true);
    }

    public function getArrows(): string
    {
        return $this->stringProp('arrows', 'inside');
    }

    public function getDots(): string
    {
        return $this->stringProp('dots', 'bottom');
    }

    public function getColumns(): string
    {
        return $this->stringProp('columns', '3');
    }

    public function getSlideWidthClass(): string
    {
        return match ($this->getColumns()) {
            '1' => 'w-full',
            '2' => 'w-full sm:w-1/2',
            '4' => 'w-full sm:w-1/2 lg:w-1/4',
            default => 'w-full sm:w-1/2 lg:w-1/3',
        };
    }

    public function getGapClass(): string
    {
        return match ($this->stringProp('gap', 'md')) {
            'none' => 'px-0',
            'sm' => 'px-1.5',
            'lg' => 'px-4',
            default => 'px-2.5',
        };
    }

    public function getCarouselConfigJson(): string
    {
        $cols = match ($this->getColumns()) {
            '1' => ['xs' => 1, 'sm' => 1, 'md' => 1, 'lg' => 1],
            '2' => ['xs' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2],
            '4' => ['xs' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4],
            default => ['xs' => 1, 'sm' => 2, 'md' => 3, 'lg' => 3],
        };

        $config = [
            'loadingClasses' => 'opacity-0',
            'isAutoPlay' => $this->isAutoplay(),
            'speed' => $this->getInterval(),
            'isInfiniteLoop' => $this->isLoop(),
            'slidesQty' => $cols,
        ];

        return (string) \json_encode($config, \JSON_THROW_ON_ERROR);
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
