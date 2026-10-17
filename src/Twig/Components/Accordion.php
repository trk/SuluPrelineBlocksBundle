<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

#[AsTwigComponent('Accordion', template: '@SuluPrelineBlocks/components/Accordion.html.twig')]
final class Accordion extends PrelineComponent
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
            'aspect_ratio' => '16/9',
            'object_fit' => 'cover',
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
        return $this->enumProp('style', ['default', 'bordered', 'soft'], 'default');
    }

    public function getIconStyle(): string
    {
        return $this->enumProp('icon_style', ['chevron', 'plus', 'none'], 'chevron');
    }

    public function isMultiple(): bool
    {
        return $this->boolProp('multiple', false);
    }

    public function isCollapsible(): bool
    {
        return $this->boolProp('collapsible', true);
    }

    public function getTitleSizeClasses(string $default = 'base', string $preset = 'accordion'): string
    {
        return parent::getTitleSizeClasses($default, $preset);
    }

    public function getImageAlign(): string
    {
        return $this->enumProp('image_align', ['top', 'bottom', 'left', 'right'], 'top');
    }

    public function hasSideImage(): bool
    {
        return $this->oneOf($this->getImageAlign(), ['left', 'right']);
    }

    /**
     * Image slot: the full panel width, or the side column once side images sit beside the text.
     */
    public function getMediaSlotWidth(): ResponsiveWidth
    {
        $layout = $this->getSlotWidth();
        if (!$this->hasSideImage()) {
            return $layout;
        }

        return $layout->scale([
            'base' => 1.0,
            PrelineClassMap::ACCORDION_SIDE_IMAGE['breakpoint'] => PrelineClassMap::ACCORDION_SIDE_IMAGE['fraction'],
        ]);
    }

    /**
     * @return array{breakpoint: string, row: string, media_left: string, media_right: string, text: string, fraction: float}
     */
    public function getSideImageLayout(): array
    {
        return PrelineClassMap::ACCORDION_SIDE_IMAGE;
    }

    public function getContainerClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::ACCORDION_CONTAINER_STYLES,
            $this->getStyle(),
            PrelineClassMap::ACCORDION_CONTAINER_STYLES['default'],
        );
    }

    public function getItemClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::ACCORDION_ITEM_STYLES,
            $this->getStyle(),
            PrelineClassMap::ACCORDION_ITEM_STYLES['default'],
        );
    }

    public function getToggleClasses(): string
    {
        return PrelineClassMap::ACCORDION_TOGGLE_CLASSES;
    }

    public function getContentWrapperClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::ACCORDION_CONTENT_STYLES,
            $this->getStyle(),
            PrelineClassMap::ACCORDION_CONTENT_STYLES['default'],
        );
    }
}
