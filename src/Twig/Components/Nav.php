<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible vertical Navigation / Sidebar component.
 */
#[AsTwigComponent('Nav', template: '@SuluPrelineBlocks/components/Nav.html.twig')]
final class Nav extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'card' => false,
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasItems();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'vertical' => true,
            'linked' => true,
        ];
    }

    public function getNavClasses(): string
    {
        return $this->classes(
            'flex flex-col space-y-1 w-full',
            $this->boolProp('card', false) ? 'p-3 bg-card border border-card-line rounded-xl shadow-xs' : null,
        );
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

        foreach ($this->items('items') as $item) {
            $itemType = $this->itemString($item, 'item_type', 'link', trim: false);
            $title = $this->itemString($item, 'title', '', trim: false);
            $meta = $this->itemString($item, 'meta', '', trim: false);
            $icon = $this->itemString($item, 'icon', '', trim: false);
            $badge = $this->itemString($item, 'badge', '', trim: false);

            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link || '' === $link) {
                $link = null;
            }

            $isActive = $this->itemBool($item, 'is_active', false);
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
        return [] !== $this->getNormalizedItems();
    }
}
