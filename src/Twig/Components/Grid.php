<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

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

    /**
     * Rendered width of one item: grid / masonry columns, or equal flex cells for card groups
     * (side by side from `sm`, matching `CARD_GROUP_CONTAINER`).
     */
    public function getItemSlotWidth(): ResponsiveWidth
    {
        if ($this->isCardGroup()) {
            return $this->getSlotWidth()->divide(['base' => 1, 'sm' => \max(1, \count($this->getNormalizedItems()))]);
        }

        return $this->getGridItemSlotWidth();
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
    public function resolveItemProps(array $item, bool $isFirstItem = true, bool $isOffscreenSlide = false): array
    {
        $itemProps = $this->itemArray($item, 'props');

        $cardVariant = $this->isCardGroup()
            ? 'group'
            : $this->itemInheritedString($item, 'panel_style', $this->stringProp('card_variant', 'default'));

        $buttonStyle = $this->itemInheritedString($item, 'link_style', $this->stringProp('button_style', 'primary'));
        $mediaAlign = $this->stringProp('media_align', 'top');
        $textAlign = $this->stringProp('text_align', 'left');
        $fetchPriority = $this->getImageFetchPriority($isFirstItem, $isOffscreenSlide);

        $loadingProps = [
            'image_loading' => $isFirstItem && $this->isImageEager(),
            'loading' => $this->getImageLoading($isFirstItem),
        ];
        if (null !== $fetchPriority) {
            $loadingProps['fetchpriority'] = $fetchPriority;
        }

        /** @var array<string, mixed> $merged */
        $merged = \array_merge(
            [self::SLOT_KEY => $this->getItemSlotWidth()->toArray()],
            [
                'card_variant' => $cardVariant,
                'button_style' => $buttonStyle,
                'media_align' => $mediaAlign,
                'text_align' => $textAlign,
            ],
            $loadingProps,
            $this->extractItemForwardedProps($item),
            $itemProps,
        );

        return $merged;
    }
}
