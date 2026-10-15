<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible responsive Map component.
 */
#[AsTwigComponent('Map', template: '@SuluPrelineBlocks/components/Map.html.twig')]
final class Map
{
    public ?string $title = null;
    public ?string $location_query = null;
    public int|string $zoom = 14;
    public string $aspect_ratio = '16/9';

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getZoom(): int
    {
        $z = (int) $this->zoom;
        if ($z < 1) {
            return 14;
        }

        return \min($z, 20);
    }

    public function getEmbedUrl(): string
    {
        $query = $this->location_query;

        if (null === $query || '' === \trim($query)) {
            $items = $this->getNormalizedItems();
            if (!empty($items)) {
                $query = $items[0]['location'];
            }
        }

        if (null === $query || '' === \trim($query)) {
            return '';
        }

        $query = \trim($query);

        // Check if raw iframe code was pasted
        if (\str_contains($query, '<iframe') && 1 === \preg_match('/src="([^"]+)"/i', $query, $matches)) {
            return $matches[1];
        }

        // Check if already an embed URL
        if (\str_starts_with($query, 'https://') && (\str_contains($query, 'google.com/maps') || \str_contains($query, 'openstreetmap.org'))) {
            return $query;
        }

        return 'https://maps.google.com/maps?q=' . \rawurlencode($query) . '&z=' . $this->getZoom() . '&output=embed';
    }

    public function hasMap(): bool
    {
        return '' !== $this->getEmbedUrl();
    }

    public function getAspectRatioClasses(): string
    {
        return match ($this->aspect_ratio) {
            '16/9' => 'aspect-video',
            '4/3' => 'aspect-4/3',
            '21/9' => 'aspect-21/9',
            '1/1' => 'aspect-square',
            default => 'h-96 md:h-[500px]',
        };
    }

    public function getWrapperClasses(): string
    {
        $classes = [
            'relative',
            'w-full',
            'rounded-2xl',
            'overflow-hidden',
            'border',
            'border-card-line',
            'shadow-xs',
            'bg-surface',
        ];

        return \implode(' ', $classes);
    }

    /**
     * Normalize items with safe fallbacks and directions link.
     *
     * @return list<array{
     *     location: string,
     *     title: string,
     *     meta: string,
     *     content: string,
     *     image_url: ?string,
     *     image_srcset: ?string,
     *     image_focus_style: ?string,
     *     link: ?string,
     *     link_title: string,
     *     directions_url: string
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            $location = \is_string($item['location'] ?? null) ? \trim($item['location']) : '';
            if ('' === $location) {
                continue;
            }

            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $meta = \is_string($item['meta'] ?? null) ? $item['meta'] : '';
            $content = \is_string($item['content'] ?? null) ? $item['content'] : '';

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageSrcSet = isset($item['image']) ? PropsResolver::resolveSrcSet($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link) {
                $link = null;
            }

            $linkTitle = \is_string($item['link_title'] ?? null) && '' !== $item['link_title']
                ? $item['link_title']
                : 'View';

            $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . \rawurlencode($location);

            $normalized[] = [
                'location' => $location,
                'title' => $title,
                'meta' => $meta,
                'content' => $content,
                'image_url' => $imageUrl,
                'image_srcset' => $imageSrcSet,
                'image_focus_style' => $imageFocusStyle,
                'link' => $link,
                'link_title' => $linkTitle,
                'directions_url' => $directionsUrl,
            ];
        }

        return $normalized;
    }

    public function hasItems(): bool
    {
        return !empty($this->getNormalizedItems());
    }
}
