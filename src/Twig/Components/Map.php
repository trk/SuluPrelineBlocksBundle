<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible responsive Map component.
 */
#[AsTwigComponent('Map', template: '@SuluPrelineBlocks/components/Map.html.twig')]
final class Map extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'zoom' => 14,
            'aspect_ratio' => '16/9',
        ];
    }

    protected function dataKeys(): array
    {
        return ['location_query'];
    }

    public function shouldRender(): bool
    {
        return $this->hasMap() || $this->hasItems();
    }

    public function getZoom(): int
    {
        $z = $this->intProp('zoom', 14);
        if ($z < 1) {
            return 14;
        }

        return (int) $this->clamp($z, 1, 20);
    }

    public function getEmbedUrl(): string
    {
        $query = $this->nullableString('location_query');

        if (null === $query) {
            $items = $this->getNormalizedItems();
            if ([] !== $items) {
                $query = $items[0]['location'];
            }
        }

        if (null === $query || '' === $query) {
            return '';
        }

        if (\str_contains($query, '<iframe') && 1 === \preg_match('/src="([^"]+)"/i', $query, $matches)) {
            return $matches[1];
        }

        if (\str_starts_with($query, 'https://') && (\str_contains($query, 'google.com/maps') || \str_contains($query, 'openstreetmap.org'))) {
            return $query;
        }

        return 'https://maps.google.com/maps?q=' . \rawurlencode($query) . '&z=' . $this->getZoom() . '&output=embed';
    }

    public function hasMap(): bool
    {
        return '' !== $this->getEmbedUrl();
    }

    public function getAspectRatioClasses(string $default = '16/9', string $key = 'aspect_ratio'): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::MAP_ASPECT_RATIOS,
            $this->stringProp($key, $default),
            PrelineClassMap::MAP_ASPECT_RATIOS['auto'],
        );
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses(
            'relative w-full rounded-2xl overflow-hidden border border-card-line shadow-xs bg-surface',
            $extraBase,
            ...$moreParts,
        );
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

        foreach ($this->items('items') as $item) {
            $location = $this->itemString($item, 'location');
            if ('' === $location) {
                continue;
            }

            $title = $this->itemString($item, 'title', '', trim: false);
            $meta = $this->itemString($item, 'meta', '', trim: false);
            $content = $this->itemString($item, 'content', '', trim: false);

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageSrcSet = isset($item['image']) ? PropsResolver::resolveSrcSet($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link || '' === $link) {
                $link = null;
            }

            $linkTitle = $this->itemString($item, 'link_title', 'View', trim: false);
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
        return [] !== $this->getNormalizedItems();
    }
}
