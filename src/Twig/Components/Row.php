<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

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
        $layout = $this->stringProp('layout', 'halves');

        return 'auto' === $layout || isset(PrelineClassMap::ROW_LAYOUT_SPANS[$layout]) ? $layout : 'halves';
    }

    public function isAutoLayout(): bool
    {
        return 'auto' === $this->getResolvedLayout();
    }

    public function getLayoutClasses(): string
    {
        if ($this->isAutoLayout()) {
            return $this->classes('grid', PrelineClassMap::responsiveClasses(PrelineClassMap::GRID_COLUMNS, $this->getAutoTrackCounts()));
        }

        return PrelineClassMap::ROW_GRID;
    }

    public function getGapClasses(string $default = 'md', string $preset = 'row', string $key = 'gap'): string
    {
        return parent::getGapClasses($default, $preset, $key);
    }

    public function getDividerClasses(): string
    {
        if (!$this->boolProp('has_divider', false)) {
            return '';
        }

        return PrelineClassMap::ROW_DIVIDERS[$this->getSideBySideBreakpoint() ?? 'stacked'];
    }

    public function getRowClasses(): string
    {
        $base = $this->classes(
            $this->getLayoutClasses(),
            $this->getGapClasses(),
            $this->getAlignItemsClasses(),
            $this->getDividerClasses(),
        );

        $rowProps = $this->props();
        unset(
            $rowProps['max_width'],
            $rowProps['maxwidth'],
            $rowProps['block_align'],
            $rowProps['block_alignment'],
            $rowProps['padding'],
            $rowProps['padding_remove_top'],
            $rowProps['padding_remove_bottom'],
            $rowProps['block_padding'],
            $rowProps['text_align'],
        );

        return ElementTransform::wrapperClasses($rowProps, $base);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes($this->getRowClasses(), $extraBase, ...$moreParts);
    }

    /**
     * Row has no width layer of its own; it forwards the container's slot width directly
     * to be divided among its columns.
     */
    protected function narrowSlotWidth(ResponsiveWidth $inherited): ResponsiveWidth
    {
        return $inherited;
    }

    /**
     * Columns with their resolved span map (`_spans`, out of 12 tracks per breakpoint) and
     * layout slot (`_slot`), both derived from the same row layout preset.
     *
     * @return list<array<string, mixed>>
     */
    public function getNormalizedColumns(): array
    {
        $rowLayout = $this->getSlotWidth();
        $sideBySide = $this->getSideBySideBreakpoint() ?? 'stacked';
        $normalized = [];

        foreach ($this->getRawColumns() as $index => $colArr) {
            $spans = $this->resolveColumnSpans($colArr, $index);

            $colArr['_spans'] = $spans ?? [];
            $colArr['_side_by_side'] = $sideBySide;
            $colArr[self::SLOT_KEY] = (null !== $spans
                ? $rowLayout->scale(\array_map(static fn (int $span): float => $span / 12, $spans))
                : $rowLayout->divide($this->getAutoTrackCounts())
            )->toArray();
            $normalized[] = $colArr;
        }

        return $normalized;
    }

    public function hasColumns(): bool
    {
        return [] !== $this->getRawColumns();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function getRawColumns(): array
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

        $list = [];
        foreach ($cols as $colArr) {
            $colProps = SuluDataNormalizer::extractProps($colArr);
            $list[] = [] !== $colProps ? \array_merge($colArr, $colProps) : $colArr;
        }

        return $list;
    }

    /**
     * Span map for a column: an explicit `col_span` preset wins, otherwise the row layout pattern
     * applies. Returns `null` for the `auto` layout (equal tracks, no spans).
     *
     * @param array<string, mixed> $column
     *
     * @return array<string, int>|null
     */
    private function resolveColumnSpans(array $column, int $index): ?array
    {
        if ($this->isAutoLayout()) {
            return null;
        }

        $explicit = $this->itemString($column, 'col_span', 'auto');
        if (isset(PrelineClassMap::COLUMN_SPAN_PRESETS[$explicit])) {
            return PrelineClassMap::COLUMN_SPAN_PRESETS[$explicit];
        }

        $pattern = PrelineClassMap::ROW_LAYOUT_SPANS[$this->getResolvedLayout()];

        return $pattern[$index % \count($pattern)];
    }

    /**
     * Equal track counts for the `auto` layout: stacked on mobile, one track per column from `sm`.
     *
     * @return array<string, int>
     */
    private function getAutoTrackCounts(): array
    {
        $count = \max(1, \min(\count($this->getRawColumns()), PrelineClassMap::ROW_AUTO_MAX_COLUMNS));

        return ['base' => 1, 'sm' => $count];
    }

    /**
     * First breakpoint at which at least two columns share a row; `null` when always stacked.
     */
    private function getSideBySideBreakpoint(): ?string
    {
        $columns = $this->getRawColumns();
        if (\count($columns) < 2) {
            return null;
        }

        if ($this->isAutoLayout()) {
            return 'sm';
        }

        $first = null;
        foreach ($columns as $index => $column) {
            $spans = ResponsiveWidth::cascade($this->resolveColumnSpans($column, $index) ?? ['base' => 12], 12);
            foreach ($spans as $bp => $span) {
                if ($span < 12) {
                    if (null === $first || ResponsiveWidth::BREAKPOINTS[$bp] < ResponsiveWidth::BREAKPOINTS[$first]) {
                        $first = $bp;
                    }
                    break;
                }
            }
        }

        return $first;
    }
}
