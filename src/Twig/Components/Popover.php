<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible image hotspot popover component with UIkit-style markers.
 */
#[AsTwigComponent('Popover', template: '@SuluPrelineBlocks/components/Popover.html.twig')]
final class Popover
{
    public ?string $title = null;

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
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getImageSrcSet(): ?string
    {
        return PropsResolver::resolveSrcSet($this->image);
    }

    public function getImageFocusPointStyle(): ?string
    {
        return PropsResolver::resolveFocusPointStyle($this->image);
    }

    public function getImageSizes(): string
    {
        return '(min-width: 1280px) 1200px, 100vw';
    }

    public function getHotspotClasses(): string
    {
        return 'group size-8 rounded-full flex items-center justify-center shadow-lg transition-transform hover:scale-110 focus:outline-hidden focus:ring-4';
    }

    /**
     * @return array{
     *     button: string,
     *     inner: string,
     *     ping: string
     * }
     */
    public function getMarkerColorClasses(string $color): array
    {
        return match ($color) {
            'dark' => [
                'button' => 'text-white focus:ring-gray-800/30',
                'inner' => 'bg-gray-900 text-white dark:bg-white dark:text-gray-900',
                'ping' => 'bg-gray-800/60 dark:bg-white/60',
            ],
            'light' => [
                'button' => 'text-gray-900 focus:ring-white/40',
                'inner' => 'bg-white text-gray-900 border border-gray-200',
                'ping' => 'bg-white/80',
            ],
            'accent' => [
                'button' => 'text-white focus:ring-amber-500/30',
                'inner' => 'bg-amber-500 text-white',
                'ping' => 'bg-amber-400/75',
            ],
            'success' => [
                'button' => 'text-white focus:ring-emerald-500/30',
                'inner' => 'bg-emerald-600 text-white',
                'ping' => 'bg-emerald-400/75',
            ],
            'danger' => [
                'button' => 'text-white focus:ring-rose-500/30',
                'inner' => 'bg-rose-600 text-white',
                'ping' => 'bg-rose-400/75',
            ],
            default => [
                'button' => 'text-white focus:ring-primary-500/30',
                'inner' => 'bg-primary-600 text-white',
                'ping' => 'bg-primary-400/75',
            ],
        };
    }

    /**
     * @return list<array{
     *     x: float,
     *     y: float,
     *     title: string,
     *     meta: string,
     *     content: string,
     *     badge: string,
     *     link: ?string,
     *     link_title: string,
     *     marker_type: string,
     *     marker_color: string,
     *     marker_icon: ?string,
     *     trigger: string,
     *     image_url: ?string,
     *     image_srcset: ?string,
     *     image_focus_style: ?string,
     *     video_url: ?string
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items as $item) {
            if (!\is_array($item)) {
                continue;
            }

            /** @var mixed $rawHotspot */
            $rawHotspot = $item['hotspot'] ?? null;
            if (\is_string($rawHotspot) && '' !== \trim($rawHotspot)) {
                try {
                    /** @var mixed $decoded */
                    $decoded = \json_decode($rawHotspot, true, 512, \JSON_THROW_ON_ERROR);
                    if (\is_array($decoded)) {
                        $rawHotspot = $decoded;
                    }
                } catch (\JsonException) {
                    // Ignore malformed JSON
                }
            }

            $hotspot = \is_array($rawHotspot) ? $rawHotspot : [];

            $x = isset($hotspot['x']) && \is_numeric($hotspot['x'])
                ? (float) $hotspot['x']
                : (isset($item['position_x']) && \is_numeric($item['position_x']) ? (float) $item['position_x'] : 50.0);

            $y = isset($hotspot['y']) && \is_numeric($hotspot['y'])
                ? (float) $hotspot['y']
                : (isset($item['position_y']) && \is_numeric($item['position_y']) ? (float) $item['position_y'] : 50.0);

            // Constrain between 0 and 100
            $x = \round(\max(0.0, \min(100.0, $x)), 1);
            $y = \round(\max(0.0, \min(100.0, $y)), 1);

            $markerType = isset($hotspot['marker_type']) && \is_string($hotspot['marker_type']) && '' !== \trim($hotspot['marker_type'])
                ? \trim($hotspot['marker_type'])
                : 'pulse';

            $markerColor = isset($hotspot['marker_color']) && \is_string($hotspot['marker_color']) && '' !== \trim($hotspot['marker_color'])
                ? \trim($hotspot['marker_color'])
                : 'primary';

            $markerIcon = isset($hotspot['marker_icon']) && \is_string($hotspot['marker_icon']) && '' !== \trim($hotspot['marker_icon'])
                ? \trim($hotspot['marker_icon'])
                : null;

            $trigger = isset($hotspot['trigger']) && \is_string($hotspot['trigger']) && '' !== \trim($hotspot['trigger'])
                ? \trim($hotspot['trigger'])
                : 'click';

            $title = \is_string($item['title'] ?? null) ? $item['title'] : '';
            $meta = \is_string($item['meta'] ?? null) ? $item['meta'] : '';
            $content = \is_string($item['content'] ?? null) ? $item['content'] : '';
            $badge = \is_string($item['badge'] ?? null) ? $item['badge'] : '';

            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link) {
                $link = null;
            }

            $linkTitle = \is_string($item['link_title'] ?? null) && '' !== $item['link_title']
                ? $item['link_title']
                : 'Learn More';

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageSrcset = isset($item['image']) ? PropsResolver::resolveSrcSet($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $videoUrl = isset($item['video']) ? PropsResolver::resolveImageUrl($item['video']) : null;

            $normalized[] = [
                'x' => $x,
                'y' => $y,
                'title' => $title,
                'meta' => $meta,
                'content' => $content,
                'badge' => $badge,
                'link' => $link,
                'link_title' => $linkTitle,
                'marker_type' => $markerType,
                'marker_color' => $markerColor,
                'marker_icon' => $markerIcon,
                'trigger' => $trigger,
                'image_url' => $imageUrl,
                'image_srcset' => $imageSrcset,
                'image_focus_style' => $imageFocusStyle,
                'video_url' => $videoUrl,
            ];
        }

        return $normalized;
    }
}
