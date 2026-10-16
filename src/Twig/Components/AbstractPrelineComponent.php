<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Twig\Component\AbstractBlockComponent;

/**
 * Preline UI 5 & Tailwind CSS 4 base component.
 *
 * Extends `AbstractBlockComponent` with universal ElementTransform wrapper,
 * spacing, custom ID/class/CSS/attributes, and Sulu media/link/icon helpers.
 */
abstract class AbstractPrelineComponent extends AbstractBlockComponent
{
    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->props());
    }

    public function getCustomId(): ?string
    {
        return $this->nullableStringProp('id');
    }

    public function getCustomClass(): string
    {
        return ElementTransform::customClass($this->props());
    }

    public function getMarginClass(): string
    {
        return ElementTransform::margin($this->props());
    }

    /**
     * @return array<string, string|bool>
     */
    public function getCustomAttributes(): array
    {
        return ElementTransform::resolveCustomAttributes($this->props());
    }

    public function getScopedCss(): ?string
    {
        return ElementTransform::resolveScopedCss($this->props());
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        return ElementTransform::wrapperClasses($this->props(), $extraBase);
    }

    public function getImageUrl(string $key = 'image'): ?string
    {
        return PropsResolver::resolveImageUrl($this->get($key));
    }

    public function getImageSrcSet(string $key = 'image'): ?string
    {
        return PropsResolver::resolveSrcSet($this->get($key));
    }

    public function getImageSizes(string $default = '100vw'): string
    {
        return match ($this->stringProp('max_width', 'none')) {
            'xs' => '(max-width: 640px) 100vw, 320px',
            'sm', 'small' => '(max-width: 640px) 100vw, 384px',
            'md', 'medium' => '(max-width: 768px) 100vw, 448px',
            'lg', 'large' => '(max-width: 1024px) 100vw, 512px',
            'xl', 'xlarge' => '(max-width: 1280px) 100vw, 576px',
            '2xl', '2xlarge' => '(max-width: 1536px) 100vw, 672px',
            '3xl' => '(max-width: 1536px) 100vw, 768px',
            '4xl' => '(max-width: 1536px) 100vw, 896px',
            '5xl' => '(max-width: 1536px) 100vw, 1024px',
            default => $default,
        };
    }

    public function getImageFocusPointStyle(string $key = 'image'): ?string
    {
        return PropsResolver::resolveFocusPointStyle($this->get($key));
    }

    public function getImageAlt(string $key = 'image', string $fallbackKey = 'title'): string
    {
        $explicitAlt = $this->nullableString('alt') ?? $this->nullableStringProp('alt');
        if (null !== $explicitAlt) {
            return $explicitAlt;
        }

        $media = $this->get($key);
        if (\is_array($media)) {
            if (isset($media['alt']) && \is_string($media['alt']) && '' !== \trim($media['alt'])) {
                return \trim($media['alt']);
            }
            if (isset($media['title']) && \is_string($media['title']) && '' !== \trim($media['title'])) {
                return \trim($media['title']);
            }
            if (isset($media['description']) && \is_string($media['description']) && '' !== \trim($media['description'])) {
                return \trim($media['description']);
            }
        }

        return $this->string($fallbackKey, '');
    }

    public function getVideoUrl(string $key = 'video'): ?string
    {
        return PropsResolver::resolveImageUrl($this->get($key));
    }

    public function getIconClass(string $key = 'icon'): ?string
    {
        return PropsResolver::resolveIcon($this->get($key));
    }

    public function getLinkUrl(string $key = 'link'): ?string
    {
        $resolved = PropsResolver::resolveUrl($this->get($key));

        return '' !== $resolved ? $resolved : null;
    }

    public function getLinkTarget(string $key = 'link'): ?string
    {
        $explicit = $this->nullableStringProp('target');
        if (null !== $explicit) {
            return $explicit;
        }

        $link = $this->get($key);
        if (\is_array($link) && isset($link['target']) && \is_string($link['target']) && '' !== \trim($link['target'])) {
            return \trim($link['target']);
        }

        return null;
    }

    public function getLinkTitle(string $key = 'link', string $fallbackKey = 'link_title'): ?string
    {
        $explicit = $this->nullableString($fallbackKey);
        if (null !== $explicit) {
            return $explicit;
        }

        $link = $this->get($key);
        if (\is_array($link) && isset($link['title']) && \is_string($link['title']) && '' !== \trim($link['title'])) {
            return \trim($link['title']);
        }

        return null;
    }

    /**
     * Normalizes media (`image`, `hover_image`, `video`), `icon`, and `link` keys inside a collection item array.
     *
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function normalizeItemMediaAndLinks(array $item): array
    {
        if (isset($item['image'])) {
            $item['image_url'] = PropsResolver::resolveImageUrl($item['image']);
            $item['image_srcset'] = PropsResolver::resolveSrcSet($item['image']);
            $item['image_focus_style'] = PropsResolver::resolveFocusPointStyle($item['image']);
        }

        if (isset($item['hover_image'])) {
            $item['hover_image_url'] = PropsResolver::resolveImageUrl($item['hover_image']);
            $item['hover_image_srcset'] = PropsResolver::resolveSrcSet($item['hover_image']);
            $item['hover_image_focus_style'] = PropsResolver::resolveFocusPointStyle($item['hover_image']);
        }

        if (isset($item['video'])) {
            $item['video_url'] = PropsResolver::resolveImageUrl($item['video']);
        }

        if (isset($item['icon'])) {
            $item['icon_class'] = PropsResolver::resolveIcon($item['icon']);
        }

        if (isset($item['link'])) {
            $item['link_href'] = PropsResolver::resolveUrl($item['link']);
            $item['link_url'] = $item['link_href'];
        }

        return $item;
    }

    /**
     * Normalizes `badges` / `elements` (type=badge) / single `badge` into displayable badge items.
     *
     * @return list<array<string, mixed>>
     */
    protected function resolveBadgesList(string $fallbackStyle = 'glass', string $fallbackColor = 'primary'): array
    {
        $defaultStyle = $this->stringProp('badge_style', $fallbackStyle);
        if ('' === $defaultStyle) {
            $defaultStyle = $fallbackStyle;
        }
        $defaultColor = $this->stringProp('badge_color', $fallbackColor);
        if ('' === $defaultColor) {
            $defaultColor = $fallbackColor;
        }

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $badgeItems */
        $badgeItems = $this->items('badges');
        if ([] === $badgeItems) {
            foreach ($this->items('elements') as $item) {
                if ('badge' === ($item['type'] ?? '')) {
                    $badgeItems[] = $item;
                }
            }
        }

        foreach ($badgeItems as $item) {
            $itemStyle = isset($item['badge_style']) && \is_string($item['badge_style']) && 'inherit' !== $item['badge_style'] && '' !== \trim($item['badge_style'])
                ? $item['badge_style']
                : $defaultStyle;

            $itemColor = isset($item['badge_color']) && \is_string($item['badge_color']) && 'inherit' !== $item['badge_color'] && '' !== \trim($item['badge_color'])
                ? $item['badge_color']
                : $defaultColor;

            $itemSize = isset($item['size']) && \is_string($item['size']) && 'inherit' !== $item['size'] && '' !== \trim($item['size'])
                ? $item['size']
                : 'xs';

            $itemRounded = isset($item['rounded']) && \is_string($item['rounded']) && 'inherit' !== $item['rounded'] && '' !== \trim($item['rounded'])
                ? $item['rounded']
                : 'pill';

            $title = '';
            if (isset($item['title']) && \is_string($item['title'])) {
                $title = \trim($item['title']);
            } elseif (isset($item['text']) && \is_string($item['text'])) {
                $title = \trim($item['text']);
            }

            if ('' === $title && !isset($item['icon'])) {
                continue;
            }

            $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
            $iconAlign = isset($item['icon_align']) && 'right' === $item['icon_align'] ? 'right' : 'left';
            $hasDot = !empty($item['has_dot']);
            $link = $item['link'] ?? null;
            $url = PropsResolver::resolveUrl($link);
            $isClickable = '#' !== $url && '' !== $url;

            $classes = PropsResolver::badgeClasses([
                'badge_style' => $itemStyle,
                'badge_color' => $itemColor,
                'size' => $itemSize,
                'rounded' => $itemRounded,
            ], $defaultStyle, $defaultColor, $itemSize, $itemRounded);

            $result[] = [
                'title' => $title,
                'url' => $url,
                'is_clickable' => $isClickable,
                'link_title' => isset($item['link_title']) && \is_string($item['link_title']) ? $item['link_title'] : null,
                'classes' => $classes,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'has_dot' => $hasDot,
            ];
        }

        $singleBadge = $this->nullableString('badge');
        if ([] === $result && null !== $singleBadge) {
            $classes = PropsResolver::badgeClasses([
                'badge_style' => $defaultStyle,
                'badge_color' => $defaultColor,
                'size' => 'xs',
                'rounded' => 'pill',
            ], $defaultStyle, $defaultColor, 'xs', 'pill');

            $result[] = [
                'title' => $singleBadge,
                'url' => '#',
                'is_clickable' => false,
                'link_title' => null,
                'classes' => $classes,
                'icon' => null,
                'icon_align' => 'left',
                'has_dot' => false,
            ];
        }

        return $result;
    }

    /**
     * Normalizes `buttons` / `elements` (type=button) / single `link` into displayable button items.
     *
     * @return list<array<string, mixed>>
     */
    protected function resolveButtonsList(
        string $fallbackStyle = 'solid',
        string $fallbackColor = 'primary',
        string $fallbackSize = 'sm',
        mixed $singleLink = null,
        ?string $singleButtonText = null,
        ?string $singleLinkTitle = null,
    ): array {
        $defaultStyle = $this->stringProp('button_style', $fallbackStyle);
        $defaultColor = $this->stringProp('button_color', $fallbackColor);
        $defaultSize = $this->stringProp('button_size', $fallbackSize);

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $buttonItems */
        $buttonItems = $this->items('buttons');
        if ([] === $buttonItems) {
            foreach ($this->items('elements') as $item) {
                if ('button' === ($item['type'] ?? 'button') || 'badge' !== ($item['type'] ?? '')) {
                    $buttonItems[] = $item;
                }
            }
        }

        foreach ($buttonItems as $item) {
            $itemProps = isset($item['props']) && \is_array($item['props']) ? $item['props'] : [];

            $rawStyle = $item['button_style'] ?? ($item['variant'] ?? ($itemProps['button_style'] ?? ($itemProps['variant'] ?? null)));
            $itemStyle = \is_string($rawStyle) && 'inherit' !== $rawStyle && '' !== \trim($rawStyle)
                ? $rawStyle
                : $defaultStyle;

            $rawColor = $item['button_color'] ?? ($item['link_color'] ?? ($itemProps['button_color'] ?? ($itemProps['link_color'] ?? null)));
            $itemColor = \is_string($rawColor) && 'inherit' !== $rawColor && '' !== \trim($rawColor)
                ? $rawColor
                : $defaultColor;

            $rawSize = $item['size'] ?? ($itemProps['size'] ?? null);
            $itemSize = \is_string($rawSize) && 'inherit' !== $rawSize && '' !== \trim($rawSize)
                ? $rawSize
                : $defaultSize;

            $title = '';
            if (isset($item['title']) && \is_string($item['title'])) {
                $title = \trim($item['title']);
            } elseif (isset($item['content']) && \is_string($item['content'])) {
                $title = \trim($item['content']);
            }

            $link = $item['link'] ?? null;
            $url = PropsResolver::resolveUrl($link);
            $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
            $iconAlign = isset($item['icon_align']) && 'right' === $item['icon_align'] ? 'right' : 'left';

            $classes = PropsResolver::buttonClasses([
                'variant' => $itemStyle,
                'color' => $itemColor,
                'size' => $itemSize,
            ]);

            $result[] = [
                'title' => $title,
                'content' => $title,
                'url' => $url,
                'link' => $link,
                'link_title' => isset($item['link_title']) && \is_string($item['link_title']) ? $item['link_title'] : null,
                'classes' => $classes,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'button_style' => $itemStyle,
                'button_color' => $itemColor,
            ];
        }

        if ([] === $result && null !== $singleLink && null !== $singleButtonText && '' !== $singleButtonText) {
            $linkUrl = PropsResolver::resolveUrl($singleLink);
            if ('#' !== $linkUrl && '' !== $linkUrl) {
                $classes = PropsResolver::buttonClasses([
                    'variant' => $defaultStyle,
                    'color' => $defaultColor,
                    'size' => $defaultSize,
                ]);

                $result[] = [
                    'title' => $singleButtonText,
                    'content' => $singleButtonText,
                    'url' => $linkUrl,
                    'link' => $singleLink,
                    'link_title' => $singleLinkTitle,
                    'classes' => $classes,
                    'icon' => null,
                    'icon_align' => 'right',
                    'button_style' => $defaultStyle,
                    'button_color' => $defaultColor,
                ];
            }
        }

        return $result;
    }
}
