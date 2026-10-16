<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible social icons & share block component.
 */
#[AsTwigComponent('Social', template: '@SuluPrelineBlocks/components/Social.html.twig')]
final class Social extends PrelineComponent
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
            'style' => 'icon_only',
            'size' => 'md',
            'shape' => 'rounded',
            'alignment' => 'start',
            'open_new_tab' => true,
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'linked' => true,
        ];
    }

    public function getStyle(): string
    {
        return $this->stringProp('style', 'icon_only');
    }

    public function getSize(): string
    {
        return $this->stringProp('size', 'md');
    }

    public function getShape(): string
    {
        return $this->stringProp('shape', 'rounded');
    }

    public function getAlignment(): string
    {
        return $this->getAlignClasses('start', 'alignment');
    }

    public function isOpenNewTab(): bool
    {
        return $this->boolProp('open_new_tab', true);
    }

    public function getUlClasses(): string
    {
        return $this->classes('flex flex-wrap items-center gap-2', $this->getAlignment());
    }

    public function getItemClasses(): string
    {
        return $this->classes(
            'inline-flex items-center justify-center transition-all focus:outline-hidden focus:ring-2 focus:ring-primary-500',
            PrelineClassMap::resolve(PrelineClassMap::SOCIAL_SIZES, $this->getSize(), PrelineClassMap::SOCIAL_SIZES['md']),
            PrelineClassMap::resolve(PrelineClassMap::SOCIAL_SHAPES, $this->getShape(), PrelineClassMap::SOCIAL_SHAPES['rounded']),
            PrelineClassMap::resolve(PrelineClassMap::SOCIAL_STYLES, $this->getStyle(), PrelineClassMap::SOCIAL_STYLES['icon_only']),
        );
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

        foreach ($this->items('items') as $item) {
            $network = $this->itemString($item, 'network', 'website', trim: false, fn: 'lower');

            $link = $this->itemString($item, 'link', '', trim: false);
            if ('' === $link) {
                $rawLink = $this->itemArray($item, 'link');
                $link = $this->itemString($rawLink, 'url', '', trim: false);
            }

            if ('' === $link) {
                continue;
            }

            $title = $this->itemString($item, 'title', $this->ucfirst($network), trim: false);
            $badge = $this->itemString($item, 'badge', '', trim: false);

            $normalized[] = [
                'network' => $network,
                'link' => $link,
                'title' => $title,
                'badge' => $badge,
            ];
        }

        return $normalized;
    }
}
