<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Popover', template: '@SuluPrelineBlocks/components/Popover.html.twig')]
final class Popover extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'image', 'items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'trigger' => 'click',
            'marker_type' => 'pulse',
            'marker_color' => 'primary',
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getImageUrl();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'image' => fn (): bool => null !== $this->getImageUrl(),
            'media' => fn (): bool => null !== $this->getImageUrl(),
        ];
    }

    public function getImageSizes(string $default = '100vw'): string
    {
        return '100vw';
    }

    public function getResolvedTrigger(): string
    {
        return $this->enumProp('trigger', ['click', 'hover'], 'click');
    }

    public function getResolvedDefaultMarkerType(): string
    {
        return $this->enumProp('marker_type', ['pulse', 'plus', 'number', 'pin', 'icon', 'dot'], 'pulse');
    }

    public function getResolvedDefaultMarkerColor(): string
    {
        return $this->enumProp('marker_color', ['primary', 'dark', 'light', 'accent', 'success', 'danger'], 'primary');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $blockTrigger = $this->getResolvedTrigger();
        $blockMarkerType = $this->getResolvedDefaultMarkerType();
        $blockMarkerColor = $this->getResolvedDefaultMarkerColor();

        /** @var list<array<string, mixed>> $result */
        $result = [];

        foreach ($this->items('items') as $index => $item) {
            $hotspot = $item['hotspot'] ?? null;

            $x = 50.0;
            $y = 50.0;

            if (\is_array($hotspot)) {
                $rawX = $hotspot['x'] ?? 50;
                $rawY = $hotspot['y'] ?? 50;
                $x = \is_numeric($rawX) ? (float) $rawX : 50.0;
                $y = \is_numeric($rawY) ? (float) $rawY : 50.0;
            } else {
                $rawX = $item['position_x'] ?? 50;
                $rawY = $item['position_y'] ?? 50;
                $x = \is_numeric($rawX) ? (float) $rawX : 50.0;
                $y = \is_numeric($rawY) ? (float) $rawY : 50.0;
            }

            $x = \max(0.0, \min(100.0, $x));
            $y = \max(0.0, \min(100.0, $y));

            $itemMarkerType = $item['marker_type'] ?? 'inherit';
            $markerType = (\is_string($itemMarkerType) && 'inherit' !== $itemMarkerType && \in_array($itemMarkerType, ['pulse', 'plus', 'number', 'pin', 'icon', 'dot'], true))
                ? $itemMarkerType
                : $blockMarkerType;

            $itemMarkerColor = $item['marker_color'] ?? 'inherit';
            $markerColor = (\is_string($itemMarkerColor) && 'inherit' !== $itemMarkerColor && \in_array($itemMarkerColor, ['primary', 'dark', 'light', 'accent', 'success', 'danger'], true))
                ? $itemMarkerColor
                : $blockMarkerColor;

            $iconClass = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageSrcSet = isset($item['image']) ? PropsResolver::resolveSrcSet($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $videoUrl = isset($item['video']) ? PropsResolver::resolveImageUrl($item['video']) : null;

            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : '';
            $linkTitle = isset($item['link_title']) && \is_string($item['link_title']) && '' !== \trim($item['link_title'])
                ? $item['link_title']
                : 'Learn More';

            $result[] = [
                'index' => $index,
                'x' => $x,
                'y' => $y,
                'title' => isset($item['title']) && \is_string($item['title']) ? $item['title'] : '',
                'meta' => isset($item['meta']) && \is_string($item['meta']) ? $item['meta'] : '',
                'content' => isset($item['content']) && \is_string($item['content']) ? $item['content'] : '',
                'badge' => isset($item['badge']) && \is_string($item['badge']) ? $item['badge'] : '',
                'image_url' => $imageUrl,
                'image_srcset' => $imageSrcSet,
                'image_focus_style' => $imageFocusStyle,
                'video_url' => $videoUrl,
                'link' => '#' !== $link && '' !== $link ? $link : null,
                'link_title' => $linkTitle,
                'marker_type' => $markerType,
                'marker_color' => $markerColor,
                'icon_class' => $iconClass,
                'trigger' => $blockTrigger,
            ];
        }

        return $result;
    }

    /**
     * @return array{ping: string, button: string, inner: string}
     */
    public function getMarkerColorClasses(string $color): array
    {
        return PrelineClassMap::POPOVER_MARKER_COLORS[$color] ?? PrelineClassMap::POPOVER_MARKER_COLORS['primary'];
    }

    public function getHotspotClasses(): string
    {
        $colors = $this->getMarkerColorClasses($this->getResolvedDefaultMarkerColor());

        return 'hs-dropdown-toggle flex items-center justify-center size-8 rounded-full transition-transform hover:scale-110 focus:outline-hidden focus:ring-4 ' . $colors['button'] . ' ' . $colors['inner'];
    }
}
