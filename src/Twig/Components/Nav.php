<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible vertical Navigation / Sidebar component.
 */
#[AsTwigComponent('Nav', template: '@SuluPrelineBlocks/components/Nav.html.twig')]
final class Nav
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

    public function getNavClasses(): string
    {
        $classes = ['flex', 'flex-col', 'space-y-1', 'w-full'];

        $resolved = $this->resolveProps();
        if (!empty($resolved['card'])) {
            $classes[] = 'p-3 bg-white border border-gray-200 rounded-xl shadow-xs dark:bg-neutral-900 dark:border-neutral-700';
        }

        return \implode(' ', $classes);
    }

    /**
     * Normalize nav items.
     *
     * @return list<array{
     *     item_type: string,
     *     title: string,
     *     meta: string,
     *     icon: string,
     *     badge: string,
     *     link: ?string,
     *     is_active: bool,
     *     is_external: bool
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            $itemType = \is_string($item['item_type'] ?? null) && '' !== $item['item_type']
                ? $item['item_type']
                : 'link';

            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $meta = \is_string($item['meta'] ?? null) ? $item['meta'] : '';
            $icon = \is_string($item['icon'] ?? null) ? $item['icon'] : '';
            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link) {
                $link = null;
            }

            $isActive = !empty($item['is_active']);
            $isExternal = null !== $link && (\str_starts_with($link, 'http://') || \str_starts_with($link, 'https://'));

            $normalized[] = [
                'item_type' => $itemType,
                'title' => $title,
                'meta' => $meta,
                'icon' => $icon,
                'badge' => $badge,
                'link' => $link,
                'is_active' => $isActive,
                'is_external' => $isExternal,
            ];
        }

        return $normalized;
    }

    public function hasItems(): bool
    {
        return !empty($this->getNormalizedItems());
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
