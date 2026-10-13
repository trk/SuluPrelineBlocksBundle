<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Column component.
 */
#[AsTwigComponent('Column', template: '@SuluPrelineBlocks/components/Column.html.twig')]
final class Column
{
    public string $col_span = 'auto';
    public string $style = 'none';
    public string $padding = 'none';
    public string $vertical_align = 'top';
    public string $text_align = 'start';
    public bool $is_sticky = false;
    public string $order = 'default';

    /**
     * @var list<array<string, mixed>>|array<string|int, mixed>
     */
    public array $blocks = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    private function resolveStringProp(string $key, string $current, string $default): string
    {
        if ($current !== $default) {
            return $current;
        }

        $val = $this->props[$key] ?? null;

        return \is_string($val) && '' !== $val ? $val : $current;
    }

    private function resolveBoolProp(string $key, bool $current, bool $default = false): bool
    {
        if ($current !== $default) {
            return $current;
        }

        if (\array_key_exists($key, $this->props)) {
            $val = $this->props[$key];
            if (\is_bool($val)) {
                return $val;
            }
            if (\is_string($val)) {
                return \in_array(\strtolower($val), ['true', '1', 'yes'], true);
            }
            if (\is_int($val)) {
                return 1 === $val;
            }
        }

        return $current;
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->props);
    }

    public function getColSpanClasses(): string
    {
        $colSpan = $this->resolveStringProp('col_span', $this->col_span, 'auto');

        return match ($colSpan) {
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
        $style = $this->resolveStringProp('style', $this->style, 'none');

        return match ($style) {
            'card' => 'bg-white border border-gray-200 rounded-2xl shadow-xs dark:bg-neutral-900 dark:border-neutral-700',
            'muted' => 'bg-gray-50 rounded-2xl dark:bg-neutral-800/80',
            'subtle' => 'bg-gray-100/70 rounded-2xl dark:bg-neutral-800/50',
            'primary_subtle' => 'bg-primary-50/60 border border-primary-100 rounded-2xl dark:bg-primary-950/20 dark:border-primary-900/30',
            default => '',
        };
    }

    public function getPaddingClasses(): string
    {
        $padding = $this->resolveStringProp('padding', $this->padding, 'none');

        return match ($padding) {
            'sm' => 'p-4',
            'md' => 'p-6 sm:p-8',
            'lg' => 'p-8 sm:p-10',
            default => '',
        };
    }

    public function getVerticalAlignClasses(): string
    {
        $verticalAlign = $this->resolveStringProp('vertical_align', $this->vertical_align, 'top');

        return match ($verticalAlign) {
            'center' => 'justify-center',
            'bottom' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };
    }

    public function getTextAlignClasses(): string
    {
        $textAlign = $this->resolveStringProp('text_align', $this->text_align, 'start');

        return match ($textAlign) {
            'center' => 'text-center',
            'end', 'right' => 'text-end',
            'justify' => 'text-justify',
            default => 'text-start',
        };
    }

    public function getOrderClasses(): string
    {
        $order = $this->resolveStringProp('order', $this->order, 'default');

        return match ($order) {
            'first' => 'order-first md:order-none',
            'last' => 'order-last md:order-none',
            default => '',
        };
    }

    public function getStickyClasses(): string
    {
        return $this->resolveBoolProp('is_sticky', $this->is_sticky, false) ? 'sticky top-20 self-start' : '';
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

        $extra = [
            ElementTransform::margin($this->props),
            ElementTransform::position($this->props),
            ElementTransform::visibility($this->props),
            ElementTransform::animation($this->props),
            ElementTransform::customClass($this->props),
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
        return !empty($this->getNormalizedBlocks());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedBlocks(): array
    {
        $raw = !empty($this->blocks) ? $this->blocks : ($this->props['blocks'] ?? []);
        if (!\is_array($raw)) {
            return [];
        }

        $list = [];
        foreach ($raw as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $item */
                $list[] = $item;
            }
        }

        return $list;
    }
}
