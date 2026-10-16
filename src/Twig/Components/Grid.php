<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Grid', template: '@SuluPrelineBlocks/components/Grid.html.twig')]
final class Grid extends PrelineComponent
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
            'filter' => false,
            'filter_style' => 'pills',
            'filter_align' => 'center',
            'filter_all_label' => 'All',
            'card_variant' => 'default',
            'media_align' => 'top',
            'text_align' => 'left',
            'button_style' => 'primary',
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
            'card_group' => fn (): bool => $this->isCardGroup(),
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

    public function isCardGroup(): bool
    {
        $layout = $this->nullableStringProp('layout_type')
            ?? ($this->boolProp('card_group', false) ? 'card_group' : 'grid');

        return 'card_group' === $layout;
    }

    public function getContainerClasses(): string
    {
        if ($this->isCardGroup()) {
            return PrelineClassMap::CARD_GROUP_CONTAINER;
        }

        return $this->resolveResponsiveGridClasses($this->isMasonry());
    }

    public function isFilterEnabled(): bool
    {
        return $this->boolProp('filter', false);
    }

    /**
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function resolveItemProps(array $item): array
    {
        $itemProps = $this->itemArray($item, 'props');

        $cardVariant = $this->isCardGroup()
            ? 'group'
            : $this->itemInheritedString($item, 'panel_style', $this->stringProp('card_variant', 'default'));

        $buttonStyle = $this->itemInheritedString($item, 'link_style', $this->stringProp('button_style', 'primary'));
        $mediaAlign = $this->stringProp('media_align', 'top');
        $textAlign = $this->stringProp('text_align', 'left');

        /** @var array<string, mixed> $merged */
        $merged = \array_merge([
            'card_variant' => $cardVariant,
            'button_style' => $buttonStyle,
            'media_align' => $mediaAlign,
            'text_align' => $textAlign,
        ], $itemProps);

        return $merged;
    }
}
