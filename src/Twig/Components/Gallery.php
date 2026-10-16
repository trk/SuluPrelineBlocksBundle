<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Gallery', template: '@SuluPrelineBlocks/components/Gallery.html.twig')]
final class Gallery extends PrelineComponent
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
            'gap' => 'md',
            'masonry' => 'none',
            'overlay_position' => 'bottom',
            'overlay_mode' => 'cover',
            'overlay_style' => 'gradient',
            'overlay_transition' => 'fade',
            'image_transition' => 'zoom',
            'media_aspect_ratio' => '4/3',
            'rounded' => 'xl',
            'lightbox' => false,
            'filter' => false,
            'filter_style' => 'pills',
            'filter_align' => 'center',
            'filter_all_label' => 'All',
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
            'masonry' => fn (): bool => $this->isMasonry(),
            'filterable' => fn (): bool => $this->isFilterEnabled(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        return $this->items('items');
    }

    public function isMasonry(): bool
    {
        return 'columns' === $this->stringProp('masonry', 'none');
    }

    public function getContainerClasses(): string
    {
        return $this->resolveResponsiveGridClasses($this->isMasonry());
    }

    public function isFilterEnabled(): bool
    {
        return $this->boolProp('filter', false);
    }

    public function isLightboxEnabled(): bool
    {
        return $this->boolProp('lightbox', false);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function resolveItemProps(array $item): array
    {
        $itemProps = $this->itemArray($item, 'props');

        $overlayPosition = $this->stringProp('overlay_position', 'bottom');
        $overlayMode = $this->stringProp('overlay_mode', 'cover');
        $overlayStyle = $this->itemInheritedString($item, 'overlay_style', $this->stringProp('overlay_style', 'gradient'));
        $overlayTransition = $this->stringProp('overlay_transition', 'fade');
        $imageTransition = $this->stringProp('image_transition', 'zoom');
        $mediaAspectRatio = $this->stringProp('media_aspect_ratio', '4/3');
        $rounded = $this->stringProp('rounded', 'xl');
        $buttonStyle = $this->itemInheritedString($item, 'link_style', 'primary');

        /** @var array<string, mixed> $merged */
        $merged = \array_merge(
            [
                'overlay_position' => $overlayPosition,
                'overlay_mode' => $overlayMode,
                'overlay_style' => $overlayStyle,
                'overlay_transition' => $overlayTransition,
                'image_transition' => $imageTransition,
                'media_aspect_ratio' => $mediaAspectRatio,
                'rounded' => $rounded,
                'button_style' => $buttonStyle,
            ],
            $this->extractItemForwardedProps($item),
            $itemProps,
        );

        return $merged;
    }
}
