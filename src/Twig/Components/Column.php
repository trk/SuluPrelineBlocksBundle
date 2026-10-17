<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

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
            'col_span' => '12',
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
        $spans = $this->getSpans();

        return null !== $spans ? PrelineClassMap::responsiveClasses(PrelineClassMap::COLUMN_SPANS, $spans) : '';
    }

    /**
     * Span map (out of 12 tracks per breakpoint): provided by the parent `Row` as `_spans`, or
     * resolved from the column's own `col_span` preset when rendered standalone.
     *
     * @return array<string, int>|null
     */
    public function getSpans(): ?array
    {
        $provided = $this->prop('_spans');
        if (\is_array($provided)) {
            $spans = [];
            foreach ($provided as $bp => $span) {
                if (\is_string($bp) && isset(ResponsiveWidth::BREAKPOINTS[$bp]) && \is_int($span) && $span >= 1 && $span <= 12) {
                    $spans[$bp] = $span;
                }
            }

            return [] !== $spans ? $spans : null;
        }

        return PrelineClassMap::COLUMN_SPAN_PRESETS[$this->stringProp('col_span', 'auto')] ?? null;
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
        $order = $this->stringProp('order', 'default');
        if ('first' !== $order && 'last' !== $order) {
            return '';
        }

        return PrelineClassMap::COLUMN_ORDERS[$this->getSideBySideBreakpoint()][$order];
    }

    /**
     * Breakpoint at which this column sits beside its siblings (provided by `Row` as
     * `_side_by_side`, otherwise derived from the column's own spans); `stacked` when never.
     */
    public function getSideBySideBreakpoint(): string
    {
        $provided = $this->stringProp('_side_by_side');
        if (isset(PrelineClassMap::COLUMN_ORDERS[$provided])) {
            return $provided;
        }

        foreach (ResponsiveWidth::cascade($this->getSpans() ?? ['base' => 12], 12) as $bp => $span) {
            if ($span < 12) {
                return $bp;
            }
        }

        return 'stacked';
    }

    public function getStickyClasses(): string
    {
        return $this->boolProp('is_sticky', false) ? PrelineClassMap::COLUMN_STICKY : '';
    }

    public function getColumnClasses(): string
    {
        $base = $this->classes(
            'flex flex-col transition-all',
            $this->getColSpanClasses(),
            $this->getStyleClasses(),
            $this->getPaddingClasses(),
            $this->getVerticalAlignClasses(),
            $this->getTextAlignClass(),
            $this->getOrderClasses(),
            $this->getStickyClasses(),
        );

        $columnProps = $this->props();
        unset(
            $columnProps['max_width'],
            $columnProps['maxwidth'],
            $columnProps['block_align'],
            $columnProps['block_alignment'],
            $columnProps['padding'],
            $columnProps['padding_remove_top'],
            $columnProps['padding_remove_bottom'],
            $columnProps['block_padding'],
            $columnProps['text_align'],
        );

        return ElementTransform::wrapperClasses($columnProps, $base);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes($this->getColumnClasses(), $extraBase, ...$moreParts);
    }

    /**
     * A column rendered without a parent `Row` narrows the inherited slot by its own span preset;
     * inside a `Row` the provided `_slot` is already final.
     */
    protected function narrowSlotWidth(ResponsiveWidth $inherited): ResponsiveWidth
    {
        if (null !== ResponsiveWidth::fromArray($this->prop(self::SLOT_KEY))) {
            return $inherited;
        }

        $spans = $this->getSpans();

        return null !== $spans ? $inherited->scale(\array_map(static fn (int $span): float => $span / 12, $spans)) : $inherited;
    }

    public function hasBlocks(): bool
    {
        return [] !== $this->getNormalizedBlocks();
    }

    /**
     * Child blocks, each carrying this column's layout slot so nested media size themselves to it.
     *
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

        $layout = $this->getSlotWidth()->toArray();
        $list = [];
        foreach ($raw as $item) {
            $item[self::SLOT_KEY] ??= $layout;
            if (isset($item['props']) && \is_array($item['props'])) {
                $item['props'][self::SLOT_KEY] ??= $layout;
            }
            if (isset($item['_props']) && \is_array($item['_props'])) {
                $item['_props'][self::SLOT_KEY] ??= $layout;
            }
            $list[] = $item;
        }

        return $list;
    }
}
