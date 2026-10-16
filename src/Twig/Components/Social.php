<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible social icons & share block component.
 */
#[AsTwigComponent('Social', template: '@SuluPrelineBlocks/components/Social.html.twig')]
final class Social extends AbstractPrelineComponent
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
        return match ($this->stringProp('alignment', 'start')) {
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };
    }

    public function isOpenNewTab(): bool
    {
        return $this->boolProp('open_new_tab', true);
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

        $classes[] = match ($this->getSize()) {
            'sm' => 'size-8 text-xs',
            'lg' => 'size-11 text-base',
            default => 'size-9 text-sm',
        };

        $classes[] = match ($this->getShape()) {
            'circle' => 'rounded-full',
            'square' => 'rounded-none',
            default => 'rounded-lg',
        };

        $classes[] = match ($this->getStyle()) {
            'bordered' => 'border border-layer-line bg-layer text-layer-foreground hover:bg-layer-hover hover:text-primary shadow-2xs',
            'buttons' => 'bg-surface text-surface-foreground hover:bg-primary hover:text-primary-foreground shadow-2xs',
            'pills' => 'border border-transparent bg-primary-50 text-primary hover:bg-primary-100 dark:bg-primary-950/40 dark:hover:bg-primary-900/40',
            default => 'text-muted-foreground-1 hover:bg-muted hover:text-primary',
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

        foreach ($this->items('items') as $item) {
            $network = \is_string($item['network'] ?? null) && '' !== $item['network']
                ? \strtolower($item['network'])
                : 'website';

            $link = '';
            $rawLink = $item['link'] ?? null;
            if (\is_string($rawLink)) {
                $link = $rawLink;
            } elseif (\is_array($rawLink) && isset($rawLink['url']) && \is_string($rawLink['url'])) {
                $link = $rawLink['url'];
            }

            if ('' === $link) {
                continue;
            }

            $title = \is_string($item['title'] ?? null) && '' !== $item['title']
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
}
