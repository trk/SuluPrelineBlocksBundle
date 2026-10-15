<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible responsive data table component.
 */
#[AsTwigComponent('Table', template: '@SuluPrelineBlocks/components/Table.html.twig')]
final class Table
{
    public ?string $title = null;
    public ?string $header_title = null;
    public ?string $header_meta = null;
    public ?string $header_content = null;
    public ?string $header_badge = null;
    public ?string $header_link = null;

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getStyle(): string
    {
        $resolvedProps = $this->resolveProps();
        $style = $resolvedProps['style'] ?? 'default';

        return \is_string($style) && '' !== $style ? $style : 'default';
    }

    public function getSize(): string
    {
        $resolvedProps = $this->resolveProps();
        $size = $resolvedProps['size'] ?? 'md';

        return \is_string($size) && '' !== $size ? $size : 'md';
    }

    public function isStickyHeader(): bool
    {
        $resolvedProps = $this->resolveProps();
        $sticky = $resolvedProps['sticky_header'] ?? false;

        return (bool) $sticky;
    }

    public function isHover(): bool
    {
        $style = $this->getStyle();

        return 'hover' === $style || 'striped' === $style;
    }

    public function hasHeader(): bool
    {
        return null !== $this->header_title && '' !== $this->header_title
            || null !== $this->header_meta && '' !== $this->header_meta
            || null !== $this->header_content && '' !== $this->header_content
            || null !== $this->header_badge && '' !== $this->header_badge
            || null !== $this->header_link && '' !== $this->header_link;
    }

    public function getWrapperClasses(): string
    {
        $classes = ['overflow-x-auto', 'w-full'];

        $resolvedProps = $this->resolveProps();
        if (!empty($resolvedProps['bordered'])) {
            $classes[] = 'border border-table-line rounded-xl shadow-xs';
        }

        return \implode(' ', $classes);
    }

    public function getTableClasses(): string
    {
        $classes = ['min-w-full', 'divide-y', 'divide-table-line', 'text-start'];

        return \implode(' ', $classes);
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
        $padding = $this->getCellPaddingClasses();

        return $padding . ' font-semibold text-muted-foreground-2 uppercase tracking-wider text-start';
    }

    public function getTdClasses(): string
    {
        $padding = $this->getCellPaddingClasses();

        return $padding . ' text-foreground align-middle';
    }

    public function getRowClasses(int $index): string
    {
        $classes = ['transition-colors'];

        $style = $this->getStyle();

        if ('striped' === $style) {
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

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $meta = \is_string($item['meta'] ?? null) ? $item['meta'] : '';
            $content = \is_string($item['content'] ?? null) ? $item['content'] : '';
            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link) {
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

    /**
     * @return array<string, mixed>
     */
    private function resolveProps(): array
    {
        if (isset($this->props['props']) && \is_array($this->props['props'])) {
            /** @var array<string, mixed> $inner */
            $inner = $this->props['props'];

            return $inner;
        }

        return $this->props;
    }
}
