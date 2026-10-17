<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('PanelSlider', template: '@SuluPrelineBlocks/components/PanelSlider.html.twig')]
final class PanelSlider extends PrelineComponent
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
            'height' => 'small',
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
        $layout = $this->getSliderItemSlotWidth($this->getColumns())->toArray();
        $list = [];
        foreach ($this->items('items') as $item) {
            $item[self::SLOT_KEY] = $layout;
            $list[] = $item;
        }

        return $list;
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
        return $this->resolveSliderColumns();
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
