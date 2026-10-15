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

    public ?string $trigger = 'click';

    public ?string $markerType = 'pulse';

    public ?string $marker_type = null;

    public ?string $markerColor = 'primary';

    public ?string $marker_color = null;

    public mixed $icon = null;

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

    public function getResolvedTrigger(): string
    {
        $propTrigger = isset($this->props['trigger']) && \is_string($this->props['trigger']) ? $this->props['trigger'] : null;
        $val = ('click' !== $this->trigger && null !== $this->trigger) ? $this->trigger : ($propTrigger ?? $this->trigger ?? 'click');

        return 'hover' === $val ? 'hover' : 'click';
    }

    public function getResolvedDefaultMarkerType(): string
    {
        $propType = isset($this->props['marker_type']) && \is_string($this->props['marker_type']) ? $this->props['marker_type'] : null;
        $val = $this->marker_type ?? (('pulse' !== $this->markerType && null !== $this->markerType) ? $this->markerType : ($propType ?? 'pulse'));

        return '' !== \trim($val) ? \trim($val) : 'pulse';
    }

    public function getResolvedDefaultMarkerColor(): string
    {
        $propColor = isset($this->props['marker_color']) && \is_string($this->props['marker_color']) ? $this->props['marker_color'] : null;
        $val = $this->marker_color ?? (('primary' !== $this->markerColor && null !== $this->markerColor) ? $this->markerColor : ($propColor ?? 'primary'));

        return '' !== \trim($val) ? \trim($val) : 'primary';
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
                'button' => 'text-foreground-inverse focus:ring-line-8/30',
                'inner' => 'bg-inverse text-foreground-inverse',
                'ping' => 'bg-inverse/60',
            ],
            'light' => [
                'button' => 'text-layer-foreground focus:ring-white/40',
                'inner' => 'bg-layer text-layer-foreground border border-layer-line',
                'ping' => 'bg-layer/80',
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
                'button' => 'text-destructive-foreground focus:ring-destructive/30',
                'inner' => 'bg-destructive text-destructive-foreground',
                'ping' => 'bg-destructive/75',
            ],
            default => [
                'button' => 'text-primary-foreground focus:ring-primary/30',
                'inner' => 'bg-primary text-primary-foreground',
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
     *     icon_class: ?string,
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
        $defaultType = $this->getResolvedDefaultMarkerType();
        $defaultColor = $this->getResolvedDefaultMarkerColor();
        $blockTrigger = $this->getResolvedTrigger();

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

            // Marker Type: Item override -> Hotspot data -> Block default
            $itemType = isset($item['marker_type']) && \is_string($item['marker_type']) ? \trim($item['marker_type']) : null;
            if (null !== $itemType && '' !== $itemType && 'inherit' !== $itemType && 'default' !== $itemType) {
                $markerType = $itemType;
            } elseif (isset($hotspot['marker_type']) && \is_string($hotspot['marker_type']) && '' !== \trim($hotspot['marker_type'])) {
                $markerType = \trim($hotspot['marker_type']);
            } else {
                $markerType = $defaultType;
            }

            // Marker Color: Item override -> Hotspot data -> Block default
            $itemColor = isset($item['marker_color']) && \is_string($item['marker_color']) ? \trim($item['marker_color']) : null;
            if (null !== $itemColor && '' !== $itemColor && 'inherit' !== $itemColor && 'default' !== $itemColor) {
                $markerColor = $itemColor;
            } elseif (isset($hotspot['marker_color']) && \is_string($hotspot['marker_color']) && '' !== \trim($hotspot['marker_color'])) {
                $markerColor = \trim($hotspot['marker_color']);
            } else {
                $markerColor = $defaultColor;
            }

            // Custom Icon: Item icon -> Hotspot icon -> Block default icon
            $rawIcon = $item['icon'] ?? $hotspot['marker_icon'] ?? $this->icon ?? null;
            $iconClass = PropsResolver::resolveIcon($rawIcon);

            // Trigger: Block level default (with item fallback)
            $trigger = isset($hotspot['trigger']) && \is_string($hotspot['trigger']) && '' !== \trim($hotspot['trigger'])
                ? \trim($hotspot['trigger'])
                : $blockTrigger;

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
                'icon_class' => $iconClass,
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
