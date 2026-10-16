<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Column component.
 */
#[AsTwigComponent('Column', template: '@SuluPrelineBlocks/components/Column.html.twig')]
final class Column extends PrelineComponent
{
    protected bool $applyWrapperPadding = false;

    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['blocks'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'col_span' => 'auto',
            'style' => 'none',
            'padding' => 'none',
            'vertical_align' => 'top',
            'text_align' => 'start',
            'is_sticky' => false,
            'order' => 'default',
        ];
    }

    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'container' => true,
            'multiple' => true,
            'vertical' => true,
        ];
    }

    public function getColSpanClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::COLUMN_COL_SPANS,
            $this->stringProp('col_span', 'auto'),
            '',
        );
    }

    public function getStyleClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::COLUMN_STYLES,
            $this->stringProp('style', 'none'),
            '',
        );
    }

    public function getOrderClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::COLUMN_ORDERS,
            $this->stringProp('order', 'default'),
            '',
        );
    }

    public function getStickyClasses(): string
    {
        return $this->boolProp('is_sticky', false) ? 'sticky top-20 self-start' : '';
    }

    public function getColumnClasses(): string
    {
        return parent::getWrapperClasses(
            'flex flex-col w-full transition-all',
            $this->getColSpanClasses(),
            $this->getStyleClasses(),
            $this->getPaddingClasses(),
            $this->getVerticalAlignClasses(),
            $this->getTextAlignClasses(),
            $this->getOrderClasses(),
            $this->getStickyClasses(),
        );
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes($this->getColumnClasses(), $extraBase, ...$moreParts);
    }

    public function hasBlocks(): bool
    {
        return [] !== $this->getNormalizedBlocks();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedBlocks(): array
    {
        $raw = $this->items('blocks');
        if ([] === $raw) {
            $propBlocks = $this->prop('blocks', []);
            if (\is_array($propBlocks)) {
                foreach ($propBlocks as $item) {
                    if (\is_array($item)) {
                        /** @var array<string, mixed> $item */
                        $raw[] = $item;
                    }
                }
            }
        }

        return $raw;
    }
}
