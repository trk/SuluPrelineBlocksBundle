<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible Column component.
 */
#[AsTwigComponent('Column', template: '@SuluPrelineBlocks/components/Column.html.twig')]
final class Column
{
    public ?string $title = null;
    public ?string $subtitle = null;
    public ?string $meta = null;
    public ?string $content = null;

    public mixed $image = null;

    public mixed $link = null;

    public ?string $link_title = null;
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

    public function getColSpanClasses(): string
    {
        return match ($this->col_span) {
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
        return match ($this->style) {
            'card' => 'bg-white border border-gray-200 rounded-2xl shadow-xs dark:bg-neutral-900 dark:border-neutral-700',
            'muted' => 'bg-gray-50 rounded-2xl dark:bg-neutral-800/80',
            'subtle' => 'bg-gray-100/70 rounded-2xl dark:bg-neutral-800/50',
            'primary_subtle' => 'bg-primary-50/60 border border-primary-100 rounded-2xl dark:bg-primary-950/20 dark:border-primary-900/30',
            default => '',
        };
    }

    public function getPaddingClasses(): string
    {
        return match ($this->padding) {
            'sm' => 'p-4',
            'md' => 'p-6 sm:p-8',
            'lg' => 'p-8 sm:p-10',
            default => '',
        };
    }

    public function getVerticalAlignClasses(): string
    {
        return match ($this->vertical_align) {
            'center' => 'justify-center',
            'bottom' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };
    }

    public function getTextAlignClasses(): string
    {
        return match ($this->text_align) {
            'center' => 'text-center',
            'end' => 'text-end',
            default => 'text-start',
        };
    }

    public function getOrderClasses(): string
    {
        return match ($this->order) {
            'first' => 'order-first md:order-none',
            'last' => 'order-last md:order-none',
            default => '',
        };
    }

    public function getStickyClasses(): string
    {
        return $this->is_sticky ? 'sticky top-20 self-start' : '';
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

        return \implode(' ', $classes);
    }

    public function getImageUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getLinkUrl(): ?string
    {
        $url = PropsResolver::resolveUrl($this->link);
        if ('#' === $url) {
            return null;
        }

        return $url;
    }

    public function getSubtitle(): ?string
    {
        if (null !== $this->subtitle && '' !== \trim($this->subtitle)) {
            return $this->subtitle;
        }

        if (null !== $this->meta && '' !== \trim($this->meta)) {
            return $this->meta;
        }

        return null;
    }

    public function hasContent(): bool
    {
        return null !== $this->content && '' !== \trim($this->content);
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
