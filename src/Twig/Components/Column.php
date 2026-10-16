<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Column component.
 */
#[AsTwigComponent('Column', template: '@SuluPrelineBlocks/components/Column.html.twig')]
final class Column extends AbstractPrelineComponent
{
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
        return match ($this->stringProp('col_span', 'auto')) {
            '12' => 'col-span-12',
            '6' => 'col-span-12 md:col-span-6',
            '4' => 'col-span-12 md:col-span-4',
            '8' => 'col-span-12 md:col-span-8',
            '3' => 'col-span-12 sm:col-span-6 lg:col-span-3',
            '9' => 'col-span-12 lg:col-span-9',
            '2' => 'col-span-12 sm:col-span-4 lg:col-span-2',
            '10' => 'col-span-12 lg:col-span-10',
            '5' => 'col-span-12 md:col-span-5',
            '7' => 'col-span-12 md:col-span-7',
            default => '',
        };
    }

    public function getStyleClasses(): string
    {
        return match ($this->stringProp('style', 'none')) {
            'card' => 'bg-card border border-card-line rounded-2xl shadow-xs',
            'muted' => 'bg-background-1 rounded-2xl',
            'subtle' => 'bg-surface/70 rounded-2xl',
            'primary_subtle' => 'bg-primary-50/60 border border-primary-100 rounded-2xl dark:bg-primary-950/20 dark:border-primary-900/30',
            default => '',
        };
    }

    public function getPaddingClasses(): string
    {
        return match ($this->stringProp('padding', 'none')) {
            'sm' => 'p-4',
            'md' => 'p-6 sm:p-8',
            'lg' => 'p-8 sm:p-10',
            default => '',
        };
    }

    public function getVerticalAlignClasses(): string
    {
        return match ($this->stringProp('vertical_align', 'top')) {
            'center' => 'justify-center',
            'bottom' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };
    }

    public function getTextAlignClasses(): string
    {
        return match ($this->stringProp('text_align', 'start')) {
            'center' => 'text-center',
            'end', 'right' => 'text-end',
            'justify' => 'text-justify',
            default => 'text-start',
        };
    }

    public function getOrderClasses(): string
    {
        return match ($this->stringProp('order', 'default')) {
            'first' => 'order-first md:order-none',
            'last' => 'order-last md:order-none',
            default => '',
        };
    }

    public function getStickyClasses(): string
    {
        return $this->boolProp('is_sticky', false) ? 'sticky top-20 self-start' : '';
    }

    public function getColumnClasses(): string
    {
        $classes = [
            'flex',
            'flex-col',
            'w-full',
            'transition-all',
        ];

        $span = $this->getColSpanClasses();
        if ('' !== $span) {
            $classes[] = $span;
        }

        $style = $this->getStyleClasses();
        if ('' !== $style) {
            $classes[] = $style;
        }

        $padding = $this->getPaddingClasses();
        if ('' !== $padding) {
            $classes[] = $padding;
        }

        $align = $this->getVerticalAlignClasses();
        if ('' !== $align) {
            $classes[] = $align;
        }

        $textAlign = $this->getTextAlignClasses();
        if ('' !== $textAlign) {
            $classes[] = $textAlign;
        }

        $order = $this->getOrderClasses();
        if ('' !== $order) {
            $classes[] = $order;
        }

        $sticky = $this->getStickyClasses();
        if ('' !== $sticky) {
            $classes[] = $sticky;
        }

        $props = $this->props();
        $extra = [
            ElementTransform::margin($props),
            ElementTransform::position($props),
            ElementTransform::visibility($props),
            ElementTransform::animation($props),
            ElementTransform::customClass($props),
        ];

        foreach ($extra as $item) {
            if ('' !== $item) {
                $classes[] = $item;
            }
        }

        return \implode(' ', $classes);
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
