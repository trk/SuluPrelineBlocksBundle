<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible responsive data table component.
 */
#[AsTwigComponent('Table', template: '@SuluPrelineBlocks/components/Table.html.twig')]
final class Table extends AbstractPrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return [
            'title',
            'header_title',
            'header_meta',
            'header_content',
            'header_badge',
            'header_link',
            'items',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'style' => 'default',
            'size' => 'md',
            'sticky_header' => false,
            'bordered' => false,
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems() || $this->hasHeader();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
        ];
    }

    public function getStyle(): string
    {
        return $this->stringProp('style', 'default');
    }

    public function getSize(): string
    {
        return $this->stringProp('size', 'md');
    }

    public function isStickyHeader(): bool
    {
        return $this->boolProp('sticky_header', false);
    }

    public function isHover(): bool
    {
        $style = $this->getStyle();

        return 'hover' === $style || 'striped' === $style;
    }

    public function hasHeader(): bool
    {
        return $this->hasAny('header_title', 'header_meta', 'header_content', 'header_badge', 'header_link');
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $classes = ['overflow-x-auto', 'w-full'];

        if ($this->boolProp('bordered', false)) {
            $classes[] = 'border border-table-line rounded-xl shadow-xs';
        }

        if ('' !== $extraBase) {
            $classes[] = $extraBase;
        }

        return \implode(' ', $classes);
    }

    public function getTableClasses(): string
    {
        return 'min-w-full divide-y divide-table-line text-start';
    }

    public function getTheadClasses(): string
    {
        $classes = ['bg-background-1'];

        if ($this->isStickyHeader()) {
            $classes[] = 'sticky top-0 z-10';
        }

        return \implode(' ', $classes);
    }

    public function getCellPaddingClasses(): string
    {
        return match ($this->getSize()) {
            'sm' => 'px-3 py-2 text-xs',
            'lg' => 'px-6 py-4 text-base',
            default => 'px-4 py-3 text-sm',
        };
    }

    public function getThClasses(): string
    {
        return $this->getCellPaddingClasses() . ' font-semibold text-muted-foreground-2 uppercase tracking-wider text-start';
    }

    public function getTdClasses(): string
    {
        return $this->getCellPaddingClasses() . ' text-foreground align-middle';
    }

    public function getRowClasses(int $index): string
    {
        $classes = ['transition-colors'];

        if ('striped' === $this->getStyle()) {
            $classes[] = 0 === $index % 2
                ? 'bg-card'
                : 'bg-background-1';
        } else {
            $classes[] = 'bg-card';
        }

        if ($this->isHover()) {
            $classes[] = 'hover:bg-muted-hover';
        }

        return \implode(' ', $classes);
    }

    /**
     * Normalize items with safe fallbacks.
     *
     * @return list<array{
     *     title: string,
     *     meta: string,
     *     content: string,
     *     badge: string,
     *     image_url: ?string,
     *     image_focus_style: ?string,
     *     link: ?string,
     *     link_title: string
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items('items') as $item) {
            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $meta = \is_string($item['meta'] ?? null) ? $item['meta'] : '';
            $content = \is_string($item['content'] ?? null) ? $item['content'] : '';
            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link || '' === $link) {
                $link = null;
            }

            $linkTitle = \is_string($item['link_title'] ?? null) && '' !== $item['link_title']
                ? $item['link_title']
                : 'View';

            $normalized[] = [
                'title' => $title,
                'meta' => $meta,
                'content' => $content,
                'badge' => $badge,
                'image_url' => $imageUrl,
                'image_focus_style' => $imageFocusStyle,
                'link' => $link,
                'link_title' => $linkTitle,
            ];
        }

        return $normalized;
    }
}
