<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Overlay', template: '@SuluPrelineBlocks/components/Overlay.html.twig')]
final class Overlay extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return [
            'title',
            'badge',
            'badges',
            'meta',
            'summary',
            'content',
            'image',
            'video',
            'icon',
            'link',
            'link_title',
            'buttons',
            'elements',
            'hover_image',
            'hover_video',
            'image_url',
            'hover_image_url',
            'video_url',
            'hover_video_url',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'overlay_position' => 'bottom',
            'overlay_mode' => 'always',
            'overlay_style' => 'gradient-bottom',
            'overlay_transition' => 'fade',
            'image_transition' => 'scale-up',
            'object_fit' => 'cover',
            'overlay_padding' => 'normal',
            'rounded' => '2xl',
            'shadow' => 'md',
            'badge_style' => 'glass',
            'badge_color' => 'primary',
            'badge_position' => 'top-left',
            'title_element' => 'h3',
            'title_size' => 'lg',
            'title_color' => 'white',
            'meta_badge_style' => 'primary_pill',
            'button_style' => 'primary',
            'button_color' => 'primary',
            'button_size' => 'sm',
            'text_align' => 'left',
            'meta_align' => 'above-title',
            'link_overlay' => 'card',
            'media_source' => 'media',
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasAny('title', 'summary', 'content', 'meta', 'badge', 'badges', 'buttons', 'elements')
            || $this->hasMedia();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'overlay' => true,
            'image' => fn (): bool => $this->isImage(),
            'video' => fn (): bool => $this->isVideo(),
            'media' => fn (): bool => $this->hasMedia(),
            'linked' => fn (): bool => null !== $this->getLinkUrl(),
        ];
    }

    public function getOverlayPosition(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_POSITIONS,
            $this->stringProp('overlay_position', 'bottom'),
            'bottom',
        );
    }

    public function getOverlayPositionClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_POSITION_CLASSES,
            $this->getOverlayPosition(),
            PrelineClassMap::OVERLAY_POSITION_CLASSES['bottom'],
        );
    }

    public function getOverlayMode(): string
    {
        return 'hover' === $this->stringProp('overlay_mode', 'always') ? 'hover' : 'always';
    }

    public function getOverlayTransitionClasses(): string
    {
        if ('hover' !== $this->getOverlayMode()) {
            return 'transition-all duration-300 ease-out';
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_TRANSITIONS,
            $this->stringProp('overlay_transition', 'fade'),
            PrelineClassMap::OVERLAY_TRANSITIONS['fade'],
        );
    }

    public function getOverlayStyle(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_STYLES,
            $this->stringProp('overlay_style', 'gradient-bottom'),
            PrelineClassMap::OVERLAY_STYLES['gradient-bottom'],
        );
    }

    public function getImageTransitionClasses(): string
    {
        return 'none' === $this->stringProp('image_transition', 'scale-up')
            ? 'transition-opacity duration-300 ease-out'
            : 'transition-all duration-500 ease-out group-hover:scale-105';
    }

    public function getMediaAspectRatioClass(string $default = '16-9', string $autoClass = 'aspect-auto min-h-[300px]'): string
    {
        return parent::getMediaAspectRatioClass($default, $autoClass);
    }

    public function getImageSizes(string $default = '100vw'): string
    {
        return '(min-width: 1280px) 50vw, 100vw';
    }

    public function getWrapperStyles(): string
    {
        return $this->getMediaAspectRatioStyle();
    }

    public function getRoundedClasses(string $default = '2xl', string $key = 'rounded'): string
    {
        return parent::getRoundedClasses($default, $key);
    }

    public function getShadowClasses(string $default = 'md', string $fallbackEmpty = 'shadow-md'): string
    {
        return parent::getShadowClasses($default, $fallbackEmpty);
    }

    public function getBadgePositionClasses(string $zIndex = 'z-20'): string
    {
        return parent::getBadgePositionClasses($zIndex);
    }

    public function getTitleSizeClasses(string $default = 'lg', string $preset = 'overlay'): string
    {
        return parent::getTitleSizeClasses($default, $preset);
    }

    public function getTitleColor(string $default = 'white'): string
    {
        $color = $this->stringProp('title_color', $default);
        $style = $this->stringProp('overlay_style', 'gradient-bottom');

        if (\in_array($style, ['light-glass', 'light_glass'], true)) {
            return PrelineClassMap::resolve(
                PrelineClassMap::OVERLAY_LIGHT_GLASS_TITLE_COLORS,
                $color,
                PrelineClassMap::OVERLAY_LIGHT_GLASS_TITLE_COLORS['default'],
            );
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_TITLE_COLORS,
            $color,
            PrelineClassMap::OVERLAY_TITLE_COLORS['default'],
        );
    }

    public function getMetaBadgeClasses(string $default = 'primary_pill', string $preset = 'overlay'): string
    {
        return parent::getMetaBadgeClasses($default, $preset);
    }

    public function getButtonStyleClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::OVERLAY_BUTTON_STYLES,
            $this->stringProp('button_style', 'primary'),
            PrelineClassMap::OVERLAY_BUTTON_STYLES['primary'],
        );
    }

    public function getLinkUrl(string $key = 'link'): ?string
    {
        $link = $this->get($key);
        if (null !== $link) {
            $url = PropsResolver::resolveUrl($link);
            if ('#' !== $url && '' !== $url) {
                return $url;
            }
        }

        $buttonList = $this->items('buttons');
        if ([] === $buttonList) {
            $buttonList = $this->items('elements');
        }

        foreach ($buttonList as $button) {
            if (('badge' !== ($button['type'] ?? '')) && isset($button['link'])) {
                $url = PropsResolver::resolveUrl($button['link']);
                if ('#' !== $url && '' !== $url) {
                    return $url;
                }
            }
        }

        return null;
    }

    public function getButtonText(): ?string
    {
        $text = $this->nullableString('link_title');
        if (null !== $text) {
            return $text;
        }

        $link = $this->get('link');
        if (\is_array($link) && isset($link['title']) && \is_string($link['title']) && '' !== \trim($link['title'])) {
            return \trim($link['title']);
        }

        $buttonList = $this->items('buttons');
        if ([] === $buttonList) {
            $buttonList = $this->items('elements');
        }

        foreach ($buttonList as $button) {
            if ('badge' !== ($button['type'] ?? '')) {
                $content = $button['title'] ?? ($button['content'] ?? null);
                if (\is_string($content) && '' !== \trim($content)) {
                    return \trim($content);
                }
            }
        }

        return null;
    }

    public function isEntireOverlayClickable(): bool
    {
        return 'card' === $this->stringProp('link_overlay', 'card') && null !== $this->getLinkUrl();
    }

    public function isImage(): bool
    {
        if (null !== $this->getVideoUrl()) {
            return false;
        }

        return null !== $this->getImageUrl();
    }

    public function isVideo(): bool
    {
        return null !== $this->getVideoUrl();
    }

    public function hasMedia(): bool
    {
        return $this->isImage() || $this->isVideo();
    }

    public function getActiveImageUrl(): ?string
    {
        return $this->isImage() ? $this->getImageUrl() : null;
    }

    public function getActiveHoverImageUrl(): ?string
    {
        return $this->isImage() && null !== $this->getImageUrl() ? $this->getHoverImageUrl() : null;
    }

    public function getActiveVideoUrl(): ?string
    {
        return $this->isVideo() ? $this->getVideoUrl() : null;
    }

    public function getActiveHoverVideoUrl(): ?string
    {
        return $this->isVideo() && null !== $this->getVideoUrl() ? $this->getHoverVideoUrl() : null;
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $aspect = $this->getMediaAspectRatioClass();
        $rounded = $this->getRoundedClasses();
        $shadow = $this->getShadowClasses();

        $base = \trim(\sprintf('group relative overflow-hidden flex flex-col w-full min-h-[220px] %s %s %s %s', $aspect, $rounded, $shadow, $extraBase));

        return parent::getWrapperClasses($base);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedBadges(): array
    {
        return $this->resolveBadgesList('glass', 'primary');
    }

    public function hasBadges(): bool
    {
        return [] !== $this->getNormalizedBadges();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedButtons(): array
    {
        return $this->resolveButtonsList('primary', 'primary', 'sm', $this->get('link'), $this->getButtonText(), $this->nullableString('link_title'));
    }

    public function hasButtons(): bool
    {
        return [] !== $this->getNormalizedButtons();
    }
}
