<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Overlay', template: '@SuluPrelineBlocks/components/Overlay.html.twig')]
final class Overlay
{
    public ?string $title = null;
    public ?string $badge = null;
    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $badges = [];
    public ?string $badgePosition = null;
    public ?string $badge_position = null;
    public ?string $badgeStyle = null;
    public ?string $badge_style = null;
    public ?string $badgeColor = null;
    public ?string $badge_color = null;
    public ?string $meta = null;
    public ?string $content = null;
    public mixed $image = null;
    public mixed $video = null;
    public mixed $icon = null;
    public mixed $link = null;
    public ?string $linkTitle = null;
    public ?string $link_title = null;
    public ?string $buttonStyle = null;
    public ?string $button_style = null;
    public ?string $buttonColor = null;
    public ?string $button_color = null;
    public ?string $buttonSize = null;
    public ?string $button_size = null;

    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $buttons = [];

    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $elements = [];

    public mixed $hoverImage = null;
    public mixed $hover_image = null;
    public mixed $hoverVideo = null;
    public mixed $hover_video = null;
    public ?string $mediaType = null;
    public ?string $media_type = null;
    public ?string $mediaSource = null;
    public ?string $media_source = null;
    public ?string $imageUrl = null;
    public ?string $image_url = null;
    public ?string $hoverImageUrl = null;
    public ?string $hover_image_url = null;
    public ?string $videoUrl = null;
    public ?string $video_url = null;
    public ?string $hoverVideoUrl = null;
    public ?string $hover_video_url = null;

    public ?string $overlayPosition = null;
    public ?string $overlay_position = null;
    public ?string $overlayMode = null;
    public ?string $overlay_mode = null;
    public ?string $overlayStyle = null;
    public ?string $overlay_style = null;
    public ?string $overlayTransition = null;
    public ?string $overlay_transition = null;
    public ?string $imageTransition = null;
    public ?string $image_transition = null;
    public ?string $mediaAspectRatio = null;
    public ?string $media_aspect_ratio = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?string $titleColor = null;
    public ?string $title_color = null;
    public ?string $titleSize = null;
    public ?string $title_size = null;
    public ?string $titleElement = null;
    public ?string $title_element = null;
    public ?string $rounded = null;
    public ?string $shadow = null;
    public ?string $overlayPadding = null;
    public ?string $overlay_padding = null;
    public ?string $linkOverlay = null;
    public ?string $link_overlay = null;

    public ?string $marginTop = null;
    public ?string $margin_top = null;
    public ?string $marginBottom = null;
    public ?string $margin_bottom = null;
    public ?string $id = null;
    public ?string $customClass = null;
    public ?string $custom_class = null;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    /**
     * @return array<string, mixed>
     */
    public function getResolvedProps(): array
    {
        $resolved = $this->props;

        if (null !== ($this->overlayPosition ?? $this->overlay_position)) {
            $resolved['overlay_position'] = $this->overlayPosition ?? $this->overlay_position;
        }
        if (null !== ($this->overlayMode ?? $this->overlay_mode)) {
            $resolved['overlay_mode'] = $this->overlayMode ?? $this->overlay_mode;
        }
        if (null !== ($this->overlayStyle ?? $this->overlay_style)) {
            $resolved['overlay_style'] = $this->overlayStyle ?? $this->overlay_style;
        }
        if (null !== ($this->overlayTransition ?? $this->overlay_transition)) {
            $resolved['overlay_transition'] = $this->overlayTransition ?? $this->overlay_transition;
        }
        if (null !== ($this->imageTransition ?? $this->image_transition)) {
            $resolved['image_transition'] = $this->imageTransition ?? $this->image_transition;
        }
        if (null !== ($this->mediaAspectRatio ?? $this->media_aspect_ratio)) {
            $resolved['media_aspect_ratio'] = $this->mediaAspectRatio ?? $this->media_aspect_ratio;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->titleColor ?? $this->title_color)) {
            $resolved['title_color'] = $this->titleColor ?? $this->title_color;
        }
        if (null !== ($this->titleSize ?? $this->title_size)) {
            $resolved['title_size'] = $this->titleSize ?? $this->title_size;
        }
        if (null !== ($this->titleElement ?? $this->title_element)) {
            $resolved['title_element'] = $this->titleElement ?? $this->title_element;
        }
        if (null !== $this->rounded) {
            $resolved['rounded'] = $this->rounded;
        }
        if (null !== $this->shadow) {
            $resolved['shadow'] = $this->shadow;
        }
        if (null !== ($this->overlayPadding ?? $this->overlay_padding)) {
            $resolved['overlay_padding'] = $this->overlayPadding ?? $this->overlay_padding;
        }
        if (null !== ($this->linkOverlay ?? $this->link_overlay)) {
            $resolved['link_overlay'] = $this->linkOverlay ?? $this->link_overlay;
        }
        if (null !== ($this->buttonStyle ?? $this->button_style)) {
            $resolved['button_style'] = $this->buttonStyle ?? $this->button_style;
        }
        if (null !== ($this->buttonColor ?? $this->button_color)) {
            $resolved['button_color'] = $this->buttonColor ?? $this->button_color;
        }
        if (null !== ($this->buttonSize ?? $this->button_size)) {
            $resolved['button_size'] = $this->buttonSize ?? $this->button_size;
        }
        if (null !== ($this->badgeStyle ?? $this->badge_style)) {
            $resolved['badge_style'] = $this->badgeStyle ?? $this->badge_style;
        }
        if (null !== ($this->badgeColor ?? $this->badge_color)) {
            $resolved['badge_color'] = $this->badgeColor ?? $this->badge_color;
        }
        if (null !== ($this->badgePosition ?? $this->badge_position)) {
            $resolved['badge_position'] = $this->badgePosition ?? $this->badge_position;
        }
        if (null !== ($this->marginTop ?? $this->margin_top)) {
            $resolved['margin_top'] = $this->marginTop ?? $this->margin_top;
        }
        if (null !== ($this->marginBottom ?? $this->margin_bottom)) {
            $resolved['margin_bottom'] = $this->marginBottom ?? $this->margin_bottom;
        }
        if (null !== $this->id) {
            $resolved['id'] = $this->id;
        }
        if (null !== ($this->customClass ?? $this->custom_class)) {
            $resolved['custom_class'] = $this->customClass ?? $this->custom_class;
        }

        return $resolved;
    }

    public function getOverlayPosition(): string
    {
        $props = $this->getResolvedProps();
        $pos = $props['overlay_position'] ?? 'bottom';

        return match ($pos) {
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
        $props = $this->getResolvedProps();
        $mode = $props['overlay_mode'] ?? 'always';

        return 'hover' === $mode ? 'hover' : 'always';
    }

    public function getOverlayTransitionClasses(): string
    {
        if ('hover' !== $this->getOverlayMode()) {
            return 'transition-all duration-300 ease-out';
        }

        $props = $this->getResolvedProps();
        $transition = $props['overlay_transition'] ?? 'fade';

        return match ($transition) {
            'slide-up', 'slide_up' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
            'slide-down', 'slide_down' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
            'zoom' => 'opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 ease-out',
            default => 'opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out',
        };
    }

    public function getOverlayStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['overlay_style'] ?? 'gradient-bottom';

        return match ($style) {
            'gradient-full', 'gradient_full' => 'bg-gray-950/60 backdrop-brightness-75',
            'dark-glass', 'dark_glass' => 'backdrop-blur-md bg-gray-950/60 border border-white/10 shadow-lg',
            'light-glass', 'light_glass' => 'backdrop-blur-md bg-white/80 border border-white/40 shadow-lg text-gray-900',
            'primary' => 'bg-primary-600/80 backdrop-blur-xs',
            'none', 'transparent' => 'bg-transparent',
            default => 'bg-gradient-to-t from-gray-950/90 via-gray-950/40 to-transparent',
        };
    }

    public function getImageTransitionClasses(): string
    {
        $props = $this->getResolvedProps();
        $transition = $props['image_transition'] ?? 'scale-up';

        return match ($transition) {
            'none' => 'transition-opacity duration-300 ease-out',
            default => 'transition-all duration-500 ease-out group-hover:scale-105',
        };
    }

    public function getMediaAspectRatioClass(): string
    {
        $props = $this->getResolvedProps();
        $aspect = $props['media_aspect_ratio'] ?? ($props['aspect_ratio'] ?? '16-9');

        return match ($aspect) {
            '4-3', '4/3' => 'aspect-4/3',
            '1-1', '1/1', 'square' => 'aspect-square',
            '21-9', '21/9' => 'aspect-21/9',
            'auto' => 'aspect-auto min-h-[300px]',
            default => 'aspect-video',
        };
    }

    public function getOverlayPaddingClasses(): string
    {
        $props = $this->getResolvedProps();
        $padding = $props['overlay_padding'] ?? 'normal';

        return match ($padding) {
            'none' => 'p-0',
            'compact' => 'p-4 sm:p-5',
            'spacious' => 'p-8 sm:p-10',
            default => 'p-6 sm:p-8',
        };
    }

    public function getRoundedClasses(): string
    {
        $props = $this->getResolvedProps();
        $rounded = $props['rounded'] ?? '2xl';

        return match ($rounded) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            'xl' => 'rounded-xl',
            '3xl' => 'rounded-3xl',
            default => 'rounded-2xl',
        };
    }

    public function getShadowClasses(): string
    {
        $props = $this->getResolvedProps();
        $shadow = $props['shadow'] ?? 'md';

        return match ($shadow) {
            'none' => 'shadow-none',
            'sm' => 'shadow-sm',
            'lg' => 'shadow-lg',
            'xl' => 'shadow-xl',
            default => 'shadow-md',
        };
    }

    public function getBadgeClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['badge_style'] ?? 'glass';

        return match ($style) {
            'primary' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-primary-600 text-white shadow-xs',
            'success' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-emerald-600 text-white shadow-xs',
            'dark' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-gray-900 text-white shadow-xs dark:bg-white dark:text-gray-900',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-white/90 text-gray-800 backdrop-blur-md shadow-xs dark:bg-neutral-900/90 dark:text-neutral-200',
        };
    }

    public function getBadgePosition(): string
    {
        $props = $this->getResolvedProps();
        $pos = $props['badge_position'] ?? 'top-left';

        return match ($pos) {
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
        $props = $this->getResolvedProps();
        $el = $props['title_element'] ?? 'h3';

        return \in_array($el, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'], true) ? (string) $el : 'h3';
    }

    public function getTitleSizeClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['title_size'] ?? 'lg';

        return match ($size) {
            'sm' => 'text-base sm:text-lg font-semibold',
            'md' => 'text-lg sm:text-xl font-bold',
            'xl' => 'text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight',
            default => 'text-xl sm:text-2xl font-bold',
        };
    }

    public function getTitleColor(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['title_color'] ?? 'white';

        if ('light-glass' === ($props['overlay_style'] ?? '')) {
            return match ($color) {
                'primary' => 'text-primary-600',
                'white' => 'text-gray-900',
                default => 'text-gray-900',
            };
        }

        return match ($color) {
            'light' => 'text-gray-200',
            'primary' => 'text-primary-400',
            'default' => 'text-white',
            default => 'text-white',
        };
    }

    public function getMetaBadgeClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['meta_badge_style'] ?? 'primary_pill';

        return match ($style) {
            'pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-white/20 backdrop-blur-sm text-white rounded-full',
            'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary-300',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary-500/80 backdrop-blur-sm text-white rounded-full',
        };
    }

    public function getButtonStyleClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['button_style'] ?? 'primary';

        return match ($style) {
            'white' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-white text-gray-900 hover:bg-gray-100 focus:outline-hidden focus:ring-2 focus:ring-white shadow-sm transition-all',
            'secondary' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-hidden focus:ring-2 focus:ring-white/50 transition-all',
            'outline' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-white/60 text-white hover:bg-white hover:text-gray-900 focus:outline-hidden focus:ring-2 focus:ring-white transition-all',
            'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-white hover:text-primary-300 transition-colors',
            default => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-primary-600 text-white hover:bg-primary-700 focus:outline-hidden focus:ring-2 focus:ring-primary-500 shadow-sm transition-all',
        };
    }

    public function getTextAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['text_align'] ?? 'left';

        return match ($align) {
            'center' => 'text-center',
            'right' => 'text-right',
            default => 'text-left',
        };
    }

    public function getMetaAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['meta_align'] ?? 'above-title';

        return \in_array($align, ['above-title', 'below-title', 'below-content'], true) ? (string) $align : 'above-title';
    }

    public function getLinkUrl(): ?string
    {
        if (null !== $this->link) {
            $url = PropsResolver::resolveUrl($this->link);
            if ('#' !== $url && '' !== $url) {
                return $url;
            }
        }

        $buttonList = [] !== $this->buttons ? $this->buttons : $this->elements;
        if ([] !== $buttonList) {
            foreach ($buttonList as $button) {
                if (\is_array($button) && ('badge' !== ($button['type'] ?? '')) && isset($button['link'])) {
                    $url = PropsResolver::resolveUrl($button['link']);
                    if ('#' !== $url && '' !== $url) {
                        return $url;
                    }
                }
            }
        }

        return null;
    }

    public function getButtonText(): ?string
    {
        $text = $this->linkTitle ?? $this->link_title;
        if (null !== $text && '' !== \trim($text)) {
            return \trim($text);
        }

        if (\is_array($this->link) && isset($this->link['title']) && \is_string($this->link['title']) && '' !== \trim($this->link['title'])) {
            return \trim($this->link['title']);
        }

        $buttonList = [] !== $this->buttons ? $this->buttons : $this->elements;
        if ([] !== $buttonList) {
            foreach ($buttonList as $button) {
                if (\is_array($button) && ('badge' !== ($button['type'] ?? ''))) {
                    $content = $button['title'] ?? ($button['content'] ?? null);
                    if (\is_string($content) && '' !== \trim($content)) {
                        return \trim($content);
                    }
                }
            }
        }

        return null;
    }

    public function isEntireOverlayClickable(): bool
    {
        $props = $this->getResolvedProps();
        $linkOverlay = $props['link_overlay'] ?? 'card';

        return 'card' === $linkOverlay && null !== $this->getLinkUrl();
    }

    public function getImageUrl(): ?string
    {
        $source = $this->mediaSource ?? $this->media_source ?? 'media';
        if ('url' === $source) {
            $url = $this->imageUrl ?? $this->image_url;
            if (null !== $url && '' !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->image)
            ?? ($this->imageUrl ?? $this->image_url);
    }

    public function getHoverImageUrl(): ?string
    {
        $source = $this->mediaSource ?? $this->media_source ?? 'media';
        if ('url' === $source) {
            $url = $this->hoverImageUrl ?? $this->hover_image_url;
            if (null !== $url && '' !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->hoverImage ?? $this->hover_image)
            ?? ($this->hoverImageUrl ?? $this->hover_image_url);
    }

    public function getVideoUrl(): ?string
    {
        $source = $this->mediaSource ?? $this->media_source ?? 'media';
        if ('url' === $source) {
            $url = $this->videoUrl ?? $this->video_url;
            if (null !== $url && '' !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->video)
            ?? ($this->videoUrl ?? $this->video_url);
    }

    public function getHoverVideoUrl(): ?string
    {
        $source = $this->mediaSource ?? $this->media_source ?? 'media';
        if ('url' === $source) {
            $url = $this->hoverVideoUrl ?? $this->hover_video_url;
            if (null !== $url && '' !== $url) {
                return $url;
            }
        }

        return PropsResolver::resolveImageUrl($this->hoverVideo ?? $this->hover_video)
            ?? ($this->hoverVideoUrl ?? $this->hover_video_url);
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

    public function getWrapperClasses(): string
    {
        $aspect = $this->getMediaAspectRatioClass();
        $rounded = $this->getRoundedClasses();
        $shadow = $this->getShadowClasses();

        $base = \sprintf('group relative overflow-hidden flex flex-col w-full min-h-[220px] %s %s %s', $aspect, $rounded, $shadow);

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedBadges(): array
    {
        $props = $this->getResolvedProps();
        $defaultStyle = isset($props['badge_style']) && \is_string($props['badge_style']) && '' !== \trim($props['badge_style']) ? $props['badge_style'] : 'glass';
        $defaultColor = isset($props['badge_color']) && \is_string($props['badge_color']) && '' !== \trim($props['badge_color']) ? $props['badge_color'] : 'primary';

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $badgeItems */
        $badgeItems = [];
        if ([] !== $this->badges) {
            foreach ($this->badges as $item) {
                if (\is_array($item)) {
                    $badgeItems[] = $item;
                }
            }
        } elseif ([] !== $this->elements) {
            foreach ($this->elements as $item) {
                if (\is_array($item) && 'badge' === ($item['type'] ?? '')) {
                    $badgeItems[] = $item;
                }
            }
        }

        if ([] !== $badgeItems) {
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
        }

        // Backward compatibility: If no badges block items, check single $this->badge string
        if ([] === $result && null !== $this->badge && '' !== \trim($this->badge)) {
            $classes = PropsResolver::badgeClasses([
                'badge_style' => $defaultStyle,
                'badge_color' => $defaultColor,
                'size' => 'xs',
                'rounded' => 'pill',
            ], $defaultStyle, $defaultColor, 'xs', 'pill');

            $result[] = [
                'title' => \trim($this->badge),
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

    public function hasBadges(): bool
    {
        return [] !== $this->getNormalizedBadges();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedButtons(): array
    {
        $props = $this->getResolvedProps();
        $defaultStyle = isset($props['button_style']) && \is_string($props['button_style']) ? $props['button_style'] : 'primary';
        $defaultColor = isset($props['button_color']) && \is_string($props['button_color']) ? $props['button_color'] : 'primary';
        $defaultSize = isset($props['button_size']) && \is_string($props['button_size']) ? $props['button_size'] : 'sm';

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $buttonItems */
        $buttonItems = [];
        if ([] !== $this->buttons) {
            foreach ($this->buttons as $item) {
                if (\is_array($item)) {
                    $buttonItems[] = $item;
                }
            }
        } elseif ([] !== $this->elements) {
            foreach ($this->elements as $item) {
                if (\is_array($item) && ('button' === ($item['type'] ?? 'button') || 'badge' !== ($item['type'] ?? ''))) {
                    $buttonItems[] = $item;
                }
            }
        }

        if ([] !== $buttonItems) {
            foreach ($buttonItems as $item) {
                $itemStyle = isset($item['button_style']) && \is_string($item['button_style']) && 'inherit' !== $item['button_style'] && '' !== \trim($item['button_style'])
                    ? $item['button_style']
                    : $defaultStyle;

                $itemColor = isset($item['button_color']) && \is_string($item['button_color']) && 'inherit' !== $item['button_color'] && '' !== \trim($item['button_color'])
                    ? $item['button_color']
                    : $defaultColor;

                $itemSize = isset($item['size']) && \is_string($item['size']) && 'inherit' !== $item['size'] && '' !== \trim($item['size'])
                    ? $item['size']
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
        }

        // Backward compatibility: If no buttons block items, check single button / link
        if ([] === $result) {
            $linkUrl = null !== $this->link ? PropsResolver::resolveUrl($this->link) : null;
            $buttonText = $this->getButtonText();

            if (null !== $linkUrl && '#' !== $linkUrl && '' !== $linkUrl && null !== $buttonText && '' !== $buttonText) {
                $classes = PropsResolver::buttonClasses([
                    'variant' => $defaultStyle,
                    'color' => $defaultColor,
                    'size' => $defaultSize,
                ]);

                $result[] = [
                    'title' => $buttonText,
                    'content' => $buttonText,
                    'url' => $linkUrl,
                    'link' => $this->link,
                    'link_title' => $this->linkTitle ?? $this->link_title,
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

    public function hasButtons(): bool
    {
        return [] !== $this->getNormalizedButtons();
    }

    public function getIconClass(): ?string
    {
        $props = $this->getResolvedProps();
        $mediaType = isset($props['media_type']) && \is_string($props['media_type'])
            ? $props['media_type']
            : ($this->mediaType ?? $this->media_type);

        if (null !== $mediaType && 'icon' !== $mediaType) {
            return null;
        }

        return PropsResolver::resolveIcon($this->icon);
    }
}
