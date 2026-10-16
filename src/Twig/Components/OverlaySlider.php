<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('OverlaySlider', template: '@SuluPrelineBlocks/components/OverlaySlider.html.twig')]
final class OverlaySlider extends PrelineComponent
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
            'overlay' => true,
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
        return $this->resolveSliderSlideWidthClass($this->getColumns());
    }

    public function getGapClass(): string
    {
        return $this->getGapClasses('md', 'slider', 'gap');
    }

    public function getCarouselConfigJson(): string
    {
        return $this->resolveSliderConfigJson(
            $this->isAutoplay(),
            $this->getInterval(),
            $this->isLoop(),
            $this->getColumns(),
        );
    }
}
