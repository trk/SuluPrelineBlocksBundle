<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible Grid Row component.
 */
#[AsTwigComponent('Row', template: '@SuluPrelineBlocks/components/Row.html.twig')]
final class Row
{
    public ?string $title = null;
    public string $layout = 'halves';
    public string $gap = 'md';
    public string $align_items = 'stretch';
    public bool $has_divider = false;

    /**
     * @var list<array<string, mixed>>|array<string|int, mixed>
     */
    public array $columns = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getLayoutClasses(): string
    {
        return match ($this->layout) {
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
        return match ($this->gap) {
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
        return match ($this->align_items) {
            'start' => 'items-start',
            'center' => 'items-center',
            'end' => 'items-end',
            default => 'items-stretch',
        };
    }

    public function getDividerClasses(): string
    {
        return $this->has_divider
            ? 'divide-y md:divide-y-0 md:divide-x divide-gray-200 dark:divide-neutral-700'
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

        return \implode(' ', \array_filter($classes));
    }

    /**
     * Normalize columns with responsive spans adjusted to the chosen row layout preset.
     *
     * @return list<array<string, mixed>>
     */
    public function getNormalizedColumns(): array
    {
        $cols = !empty($this->columns) ? $this->columns : ($this->props['items'] ?? []);
        if (!\is_array($cols)) {
            return [];
        }

        $normalized = [];
        $index = 0;

        foreach ($cols as $col) {
            if (!\is_array($col)) {
                continue;
            }

            /** @var array<string, mixed> $colArr */
            $colArr = $col;

            $span = \is_string($colArr['col_span'] ?? null) ? $colArr['col_span'] : 'auto';

            // Automatic span presets for asymmetric layouts when col_span is 'auto'
            if ('auto' === $span) {
                if ('thirds-2-1' === $this->layout) {
                    $span = 0 === $index ? '8' : '4';
                } elseif ('thirds-1-2' === $this->layout) {
                    $span = 0 === $index ? '4' : '8';
                } elseif ('quarters-3-1' === $this->layout) {
                    $span = 0 === $index ? '9' : '3';
                } elseif ('quarters-1-3' === $this->layout) {
                    $span = 0 === $index ? '3' : '9';
                } elseif ('12-col' === $this->layout) {
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
        return !empty($this->getNormalizedColumns());
    }
}
