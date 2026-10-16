<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Grid Row component.
 */
#[AsTwigComponent('Row', template: '@SuluPrelineBlocks/components/Row.html.twig')]
final class Row extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['columns'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'layout' => 'halves',
            'gap' => 'md',
            'align_items' => 'stretch',
            'has_divider' => false,
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
            'horizontal' => true,
        ];
    }

    public function getResolvedLayout(): string
    {
        return $this->stringProp('layout', 'halves');
    }

    public function getLayoutClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::ROW_LAYOUTS,
            $this->getResolvedLayout(),
            PrelineClassMap::ROW_LAYOUTS['halves'],
        );
    }

    public function getGapClasses(string $default = 'md', string $preset = 'row', string $key = 'gap'): string
    {
        return parent::getGapClasses($default, $preset, $key);
    }

    public function getDividerClasses(): string
    {
        return $this->boolProp('has_divider', false)
            ? 'divide-y md:divide-y-0 md:divide-x divide-border'
            : '';
    }

    public function getRowClasses(): string
    {
        return parent::getWrapperClasses(
            'grid w-full',
            $this->getLayoutClasses(),
            $this->getGapClasses(),
            $this->getAlignItemsClasses(),
            $this->getDividerClasses(),
        );
    }

    /**
     * Normalize columns with responsive spans adjusted to the chosen row layout preset.
     *
     * @return list<array<string, mixed>>
     */
    public function getNormalizedColumns(): array
    {
        $cols = $this->items('columns');
        if ([] === $cols) {
            $propCols = $this->prop('columns', []);
            if (\is_array($propCols)) {
                foreach ($propCols as $col) {
                    if (\is_array($col)) {
                        /** @var array<string, mixed> $col */
                        $cols[] = $col;
                    }
                }
            }
        }

        $resolvedLayout = $this->getResolvedLayout();
        $normalized = [];
        $index = 0;

        foreach ($cols as $colArr) {
            $colProps = SuluDataNormalizer::extractProps($colArr);
            if ([] !== $colProps) {
                $colArr = \array_merge($colArr, $colProps);
            }

            $span = $this->itemString($colArr, 'col_span', 'auto');

            if ('auto' === $span) {
                if ('thirds-2-1' === $resolvedLayout) {
                    $span = 0 === $index ? '8' : '4';
                } elseif ('thirds-1-2' === $resolvedLayout) {
                    $span = 0 === $index ? '4' : '8';
                } elseif ('quarters-3-1' === $resolvedLayout) {
                    $span = 0 === $index ? '9' : '3';
                } elseif ('quarters-1-3' === $resolvedLayout) {
                    $span = 0 === $index ? '3' : '9';
                } elseif ('12-col' === $resolvedLayout) {
                    $span = '12';
                }
            }

            $colArr['col_span'] = $span;
            $normalized[] = $colArr;
            ++$index;
        }

        return $normalized;
    }

    public function hasColumns(): bool
    {
        return [] !== $this->getNormalizedColumns();
    }
}
