<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Overlay', template: '@SuluPrelineBlocks/components/Overlay.html.twig')]
final class Overlay extends AbstractPrelineComponent
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
        return match ($this->stringProp('overlay_position', 'bottom')) {
            'center' => 'center',
            'top' => 'top',
            'bottom-left', 'bottom_left' => 'bottom-left',
            'bottom-right', 'bottom_right' => 'bottom-right',
            'top-left', 'top_left' => 'top-left',
            'top-right', 'top_right' => 'top-right',
            'left' => 'left',
            'right' => 'right',
            default => 'bottom',
        };
    }

    public function getOverlayPositionClasses(): string
    {
        return match ($this->getOverlayPosition()) {
            'center' => 'justify-center items-center',
            'top' => 'justify-start items-stretch',
            'bottom-left' => 'justify-end items-start',
            'bottom-right' => 'justify-end items-end',
            'top-left' => 'justify-start items-start',
            'top-right' => 'justify-start items-end',
            'left' => 'justify-center items-start',
            'right' => 'justify-center items-end',
            default => 'justify-end items-stretch',
        };
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

        return match ($this->stringProp('overlay_transition', 'fade')) {
            'slide-up', 'slide_up' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
            'slide-down', 'slide_down' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
            'zoom' => 'opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 ease-out',
            default => 'opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out',
        };
    }

    public function getOverlayStyle(): string
    {
        return match ($this->stringProp('overlay_style', 'gradient-bottom')) {
            'gradient-full', 'gradient_full' => 'bg-black/60 backdrop-brightness-75',
            'dark-glass', 'dark_glass' => 'backdrop-blur-md bg-black/60 border border-white/10 shadow-lg',
            'light-glass', 'light_glass' => 'backdrop-blur-md bg-layer/80 border border-layer-line/40 shadow-lg text-layer-foreground',
            'primary' => 'bg-primary/80 backdrop-blur-xs',
            'none', 'transparent' => 'bg-transparent',
            default => 'bg-gradient-to-t from-black/90 via-black/40 to-transparent',
        };
    }

    public function getImageTransitionClasses(): string
    {
        return match ($this->stringProp('image_transition', 'scale-up')) {
            'none' => 'transition-opacity duration-300 ease-out',
            default => 'transition-all duration-500 ease-out group-hover:scale-105',
        };
    }

    public function getMediaAspectRatioClass(): string
    {
        $aspect = $this->nullableStringProp('aspect_ratio') ?? $this->stringProp('media_aspect_ratio', '16-9');

        return match ($aspect) {
            '4-3', '4/3', '4:3' => 'aspect-4/3',
            '1-1', '1/1', '1:1', 'square' => 'aspect-square',
            '21-9', '21/9', '21:9' => 'aspect-21/9',
            'auto' => 'aspect-auto min-h-[300px]',
            'manual' => 'aspect-auto',
            default => 'aspect-video',
        };
    }

    public function getMediaAspectRatioStyle(): string
    {
        $aspect = $this->nullableStringProp('aspect_ratio') ?? $this->nullableStringProp('media_aspect_ratio');

        if ('manual' === $aspect) {
            $widthVal = $this->floatProp('aspect_ratio_width', 0.0);
            $heightVal = $this->floatProp('aspect_ratio_height', 0.0);

            if ($widthVal > 0.0 && $heightVal > 0.0) {
                return \sprintf('aspect-ratio: %s / %s;', $widthVal, $heightVal);
            }
        }

        return '';
    }

    public function getObjectFitClass(): string
    {
        return PropsResolver::resolveObjectFit($this->stringProp('object_fit', 'cover'));
    }

    public function getImageFocusPointStyle(string $key = 'image'): ?string
    {
        return PropsResolver::resolveFocusPointStyle($this->get($key));
    }

    public function getHoverImageFocusPointStyle(): ?string
    {
        return PropsResolver::resolveFocusPointStyle($this->get('hover_image'));
    }

    public function getImageSrcSet(string $key = 'image'): ?string
    {
        return PropsResolver::resolveSrcSet($this->get($key));
    }

    public function getHoverImageSrcSet(): ?string
    {
        return PropsResolver::resolveSrcSet($this->get('hover_image'));
    }

    public function getImageSizes(string $default = '100vw'): string
    {
        return '(min-width: 1280px) 50vw, 100vw';
    }

    public function getWrapperStyles(): string
    {
        return $this->getMediaAspectRatioStyle();
    }

    public function getOverlayPaddingClasses(): string
    {
        return match ($this->stringProp('overlay_padding', 'normal')) {
            'none' => 'p-0',
            'compact' => 'p-4 sm:p-5',
            'spacious' => 'p-8 sm:p-10',
            default => 'p-6 sm:p-8',
        };
    }

    public function getRoundedClasses(): string
    {
        return match ($this->stringProp('rounded', '2xl')) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            'xl' => 'rounded-xl',
            '3xl' => 'rounded-3xl',
            default => 'rounded-2xl',
        };
    }

    public function getShadowClasses(): string
    {
        return match ($this->stringProp('shadow', 'md')) {
            'none' => 'shadow-none',
            'sm' => 'shadow-sm',
            'lg' => 'shadow-lg',
            'xl' => 'shadow-xl',
            default => 'shadow-md',
        };
    }

    public function getBadgeClasses(): string
    {
        return match ($this->stringProp('badge_style', 'glass')) {
            'primary' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-primary text-primary-foreground shadow-xs',
            'success' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-emerald-600 text-white shadow-xs',
            'dark' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-inverse text-foreground-inverse shadow-xs',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-layer/90 text-layer-foreground backdrop-blur-md shadow-xs',
        };
    }

    public function getBadgePosition(): string
    {
        return match ($this->stringProp('badge_position', 'top-left')) {
            'top-right', 'top_right' => 'top-right',
            'bottom-left', 'bottom_left' => 'bottom-left',
            'bottom-right', 'bottom_right' => 'bottom-right',
            'inline' => 'inline',
            default => 'top-left',
        };
    }

    public function getBadgePositionClasses(): string
    {
        return match ($this->getBadgePosition()) {
            'top-right' => 'absolute top-3 end-3 z-20 flex flex-wrap gap-1.5',
            'bottom-left' => 'absolute bottom-3 start-3 z-20 flex flex-wrap gap-1.5',
            'bottom-right' => 'absolute bottom-3 end-3 z-20 flex flex-wrap gap-1.5',
            'inline' => '',
            default => 'absolute top-3 start-3 z-20 flex flex-wrap gap-1.5',
        };
    }

    public function getTitleElement(): string
    {
        return $this->enumProp('title_element', ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'], 'h3');
    }

    public function getTitleSizeClasses(): string
    {
        return match ($this->stringProp('title_size', 'lg')) {
            'sm' => 'text-base sm:text-lg font-semibold',
            'md' => 'text-lg sm:text-xl font-bold',
            'xl' => 'text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight',
            default => 'text-xl sm:text-2xl font-bold',
        };
    }

    public function getTitleColor(): string
    {
        $color = $this->stringProp('title_color', 'white');
        $style = $this->stringProp('overlay_style', 'gradient-bottom');

        if (\in_array($style, ['light-glass', 'light_glass'], true)) {
            return match ($color) {
                'primary' => 'text-primary',
                default => 'text-layer-foreground',
            };
        }

        return match ($color) {
            'light' => 'text-white/80',
            'primary' => 'text-primary-400',
            default => 'text-white',
        };
    }

    public function getMetaBadgeClasses(): string
    {
        return match ($this->stringProp('meta_badge_style', 'primary_pill')) {
            'pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-white/20 backdrop-blur-sm text-white rounded-full',
            'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary-300',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary/80 backdrop-blur-sm text-primary-foreground rounded-full',
        };
    }

    public function getButtonStyleClasses(): string
    {
        return match ($this->stringProp('button_style', 'primary')) {
            'white' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-layer text-layer-foreground hover:bg-layer-hover focus:outline-hidden focus:ring-2 focus:ring-layer-focus shadow-sm transition-all',
            'secondary' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-hidden focus:ring-2 focus:ring-white/50 transition-all',
            'outline' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-white/60 text-white hover:bg-layer hover:text-layer-foreground focus:outline-hidden focus:ring-2 focus:ring-white transition-all',
            'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-white hover:text-primary-300 transition-colors',
            default => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-primary shadow-sm transition-all',
        };
    }

    public function getTextAlign(): string
    {
        return match ($this->stringProp('text_align', 'left')) {
            'center' => 'text-center',
            'right' => 'text-right',
            default => 'text-left',
        };
    }

    public function getMetaAlign(): string
    {
        return $this->enumProp('meta_align', ['above-title', 'below-title', 'below-content'], 'above-title');
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

    public function getImageUrl(string $key = 'image'): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = $this->nullableString('image_url') ?? $this->nullableStringProp('image_url');
            if (null !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->get($key))
            ?? $this->nullableString('image_url')
            ?? $this->nullableStringProp('image_url');
    }

    public function getHoverImageUrl(): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = $this->nullableString('hover_image_url') ?? $this->nullableStringProp('hover_image_url');
            if (null !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->get('hover_image'))
            ?? $this->nullableString('hover_image_url')
            ?? $this->nullableStringProp('hover_image_url');
    }

    public function getVideoUrl(string $key = 'video'): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = $this->nullableString('video_url') ?? $this->nullableStringProp('video_url');
            if (null !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->get($key))
            ?? $this->nullableString('video_url')
            ?? $this->nullableStringProp('video_url');
    }

    public function getHoverVideoUrl(): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = $this->nullableString('hover_video_url') ?? $this->nullableStringProp('hover_video_url');
            if (null !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->get('hover_video'))
            ?? $this->nullableString('hover_video_url')
            ?? $this->nullableStringProp('hover_video_url');
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

    public function getIconClass(string $key = 'icon'): ?string
    {
        $mediaType = $this->nullableStringProp('media_type');
        if (null !== $mediaType && 'icon' !== $mediaType) {
            return null;
        }

        return PropsResolver::resolveIcon($this->get($key));
    }
}
