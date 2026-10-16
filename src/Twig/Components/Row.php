<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Grid Row component.
 */
#[AsTwigComponent('Row', template: '@SuluPrelineBlocks/components/Row.html.twig')]
final class Row extends AbstractPrelineComponent
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
        return match ($this->getResolvedLayout()) {
            '12-col' => 'grid-cols-1 md:grid-cols-12',
            'thirds' => 'grid-cols-1 md:grid-cols-3',
            'quarters' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
            'sixths' => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-6',
            'thirds-2-1', 'thirds-1-2' => 'grid-cols-1 md:grid-cols-3',
            'quarters-3-1', 'quarters-1-3' => 'grid-cols-1 lg:grid-cols-4',
            'auto' => 'grid-flow-row sm:grid-flow-col',
            default => 'grid-cols-1 sm:grid-cols-2',
        };
    }

    public function getGapClasses(): string
    {
        return match ($this->stringProp('gap', 'md')) {
            'none' => 'gap-0',
            'xs' => 'gap-2 sm:gap-3',
            'sm' => 'gap-4 sm:gap-6',
            'lg' => 'gap-8 sm:gap-12',
            'xl' => 'gap-12 sm:gap-16',
            default => 'gap-6 sm:gap-8',
        };
    }

    public function getAlignItemsClasses(): string
    {
        return match ($this->stringProp('align_items', 'stretch')) {
            'start' => 'items-start',
            'center' => 'items-center',
            'end' => 'items-end',
            default => 'items-stretch',
        };
    }

    public function getDividerClasses(): string
    {
        return $this->boolProp('has_divider', false)
            ? 'divide-y md:divide-y-0 md:divide-x divide-border'
            : '';
    }

    public function getRowClasses(): string
    {
        $classes = [
            'grid',
            'w-full',
            $this->getLayoutClasses(),
            $this->getGapClasses(),
            $this->getAlignItemsClasses(),
        ];

        $divider = $this->getDividerClasses();
        if ('' !== $divider) {
            $classes[] = $divider;
        }

        return ElementTransform::wrapperClasses($this->props(), \implode(' ', \array_filter($classes)));
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

            $span = \is_string($colArr['col_span'] ?? null) && '' !== $colArr['col_span'] ? $colArr['col_span'] : 'auto';

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
