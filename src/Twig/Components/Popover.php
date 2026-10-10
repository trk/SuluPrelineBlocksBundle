<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible image hotspot popover component.
 */
#[AsTwigComponent('Popover', template: '@SuluPrelineBlocks/components/Popover.html.twig')]
final class Popover
{
    public ?string $title = null;

    /**
     * @var mixed
     */
    public mixed $image = null;

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getImageUrl(): ?string
    {
        if (\is_array($this->image) && isset($this->image['url']) && \is_string($this->image['url'])) {
            return $this->image['url'];
        }

        if (\is_string($this->image) && $this->image !== '') {
            return $this->image;
        }

        return null;
    }

    public function getHotspotClasses(): string
    {
        return 'group size-7 rounded-full bg-primary-600 text-white flex items-center justify-center shadow-lg hover:scale-115 hover:bg-primary-700 transition-all focus:outline-hidden focus:ring-4 focus:ring-primary-500/30';
    }

    /**
     * @return list<array{
     *     x: float,
     *     y: float,
     *     title: string,
     *     content: string,
     *     badge: string,
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

            $x = isset($item['position_x']) && \is_numeric($item['position_x']) ? (float) $item['position_x'] : 50.0;
            $y = isset($item['position_y']) && \is_numeric($item['position_y']) ? (float) $item['position_y'] : 50.0;

            // Constrain between 0 and 100
            $x = \max(0.0, \min(100.0, $x));
            $y = \max(0.0, \min(100.0, $y));

            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $content = \is_string($item['content'] ?? null) ? $item['content'] : '';
            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $link = null;
            $rawLink = $item['link'] ?? null;
            if (\is_string($rawLink) && $rawLink !== '') {
                $link = $rawLink;
            } elseif (\is_array($rawLink) && isset($rawLink['url']) && \is_string($rawLink['url'])) {
                $link = $rawLink['url'];
            }

            $linkTitle = \is_string($item['link_title'] ?? null) && $item['link_title'] !== ''
                ? $item['link_title']
                : 'Learn More';

            $normalized[] = [
                'x' => $x,
                'y' => $y,
                'title' => $title,
                'content' => $content,
                'badge' => $badge,
                'link' => $link,
                'link_title' => $linkTitle,
            ];
        }

        return $normalized;
    }
}
