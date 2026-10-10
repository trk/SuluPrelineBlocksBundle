<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible social icons & share block component.
 */
#[AsTwigComponent('Social', template: '@SuluPrelineBlocks/components/Social.html.twig')]
final class Social
{
    public ?string $title = null;

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
        $style = $resolvedProps['style'] ?? 'icon_only';

        return \is_string($style) && $style !== '' ? $style : 'icon_only';
    }

    public function getSize(): string
    {
        $resolvedProps = $this->resolveProps();
        $size = $resolvedProps['size'] ?? 'md';

        return \is_string($size) && $size !== '' ? $size : 'md';
    }

    public function getShape(): string
    {
        $resolvedProps = $this->resolveProps();
        $shape = $resolvedProps['shape'] ?? 'rounded';

        return \is_string($shape) && $shape !== '' ? $shape : 'rounded';
    }

    public function getAlignment(): string
    {
        $resolvedProps = $this->resolveProps();
        $alignment = $resolvedProps['alignment'] ?? 'start';

        return match ($alignment) {
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };
    }

    public function isOpenNewTab(): bool
    {
        $resolvedProps = $this->resolveProps();
        $openNewTab = $resolvedProps['open_new_tab'] ?? true;

        return (bool) $openNewTab;
    }

    public function getUlClasses(): string
    {
        return 'flex flex-wrap items-center gap-2 ' . $this->getAlignment();
    }

    public function getItemClasses(): string
    {
        $classes = [
            'inline-flex',
            'items-center',
            'justify-center',
            'transition-all',
            'focus:outline-hidden',
            'focus:ring-2',
            'focus:ring-primary-500',
        ];

        // Size
        $classes[] = match ($this->getSize()) {
            'sm' => 'size-8 text-xs',
            'lg' => 'size-11 text-base',
            default => 'size-9 text-sm',
        };

        // Shape
        $classes[] = match ($this->getShape()) {
            'circle' => 'rounded-full',
            'square' => 'rounded-none',
            default => 'rounded-lg',
        };

        // Style
        $style = $this->getStyle();
        $classes[] = match ($style) {
            'bordered' => 'border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:text-primary-600 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:hover:text-primary-400 shadow-2xs',
            'buttons' => 'bg-gray-100 text-gray-700 hover:bg-primary-600 hover:text-white dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-primary-600 dark:hover:text-white shadow-2xs',
            'pills' => 'border border-transparent bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-950/40 dark:text-primary-400 dark:hover:bg-primary-900/40',
            default => 'text-gray-500 hover:bg-gray-100 hover:text-primary-600 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:hover:text-primary-400',
        };

        return \implode(' ', $classes);
    }

    /**
     * @return list<array{
     *     network: string,
     *     link: string,
     *     title: string,
     *     badge: string
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            $network = \is_string($item['network'] ?? null) && $item['network'] !== ''
                ? \strtolower($item['network'])
                : 'website';

            $link = '';
            $rawLink = $item['link'] ?? null;
            if (\is_string($rawLink)) {
                $link = $rawLink;
            } elseif (\is_array($rawLink) && isset($rawLink['url']) && \is_string($rawLink['url'])) {
                $link = $rawLink['url'];
            }

            if ($link === '') {
                continue;
            }

            $title = \is_string($item['title'] ?? null) && $item['title'] !== ''
                ? $item['title']
                : \ucfirst($network);

            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $normalized[] = [
                'network' => $network,
                'link' => $link,
                'title' => $title,
                'badge' => $badge,
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
