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

        return \is_string($style) && $style !== '' ? $style : 'default';
    }

    public function getSize(): string
    {
        $resolvedProps = $this->resolveProps();
        $size = $resolvedProps['size'] ?? 'md';

        return \is_string($size) && $size !== '' ? $size : 'md';
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

        return $style === 'hover' || $style === 'striped';
    }

    public function hasHeader(): bool
    {
        return $this->header_title !== null && $this->header_title !== ''
            || $this->header_meta !== null && $this->header_meta !== ''
            || $this->header_content !== null && $this->header_content !== ''
            || $this->header_badge !== null && $this->header_badge !== ''
            || $this->header_link !== null && $this->header_link !== '';
    }

    public function getWrapperClasses(): string
    {
        $classes = ['overflow-x-auto', 'w-full'];

        $resolvedProps = $this->resolveProps();
        if (!empty($resolvedProps['bordered'])) {
            $classes[] = 'border border-gray-200 rounded-xl dark:border-neutral-700 shadow-xs';
        }

        return \implode(' ', $classes);
    }

    public function getTableClasses(): string
    {
        $classes = ['min-w-full', 'divide-y', 'divide-gray-200', 'dark:divide-neutral-700', 'text-start'];

        return \implode(' ', $classes);
    }

    public function getTheadClasses(): string
    {
        $classes = ['bg-gray-50', 'dark:bg-neutral-800'];

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

        return $padding . ' font-semibold text-gray-700 dark:text-neutral-300 uppercase tracking-wider text-start';
    }

    public function getTdClasses(): string
    {
        $padding = $this->getCellPaddingClasses();

        return $padding . ' text-gray-800 dark:text-neutral-200 align-middle';
    }

    public function getRowClasses(int $index): string
    {
        $classes = ['transition-colors'];

        $style = $this->getStyle();

        if ($style === 'striped') {
            $classes[] = $index % 2 === 0
                ? 'bg-white dark:bg-neutral-900'
                : 'bg-gray-50/70 dark:bg-neutral-800/50';
        } else {
            $classes[] = 'bg-white dark:bg-neutral-900';
        }

        if ($this->isHover()) {
            $classes[] = 'hover:bg-primary-50/50 dark:hover:bg-neutral-800/80';
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

            $imageUrl = null;
            $image = $item['image'] ?? null;
            if (\is_array($image) && isset($image['url']) && \is_string($image['url'])) {
                $imageUrl = $image['url'];
            } elseif (\is_string($image) && $image !== '') {
                $imageUrl = $image;
            }

            $link = null;
            $rawLink = $item['link'] ?? null;
            if (\is_string($rawLink) && $rawLink !== '') {
                $link = $rawLink;
            } elseif (\is_array($rawLink) && isset($rawLink['url']) && \is_string($rawLink['url'])) {
                $link = $rawLink['url'];
            }

            $linkTitle = \is_string($item['link_title'] ?? null) && $item['link_title'] !== ''
                ? $item['link_title']
                : 'View';

            $normalized[] = [
                'title' => $title,
                'meta' => $meta,
                'content' => $content,
                'badge' => $badge,
                'image_url' => $imageUrl,
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
