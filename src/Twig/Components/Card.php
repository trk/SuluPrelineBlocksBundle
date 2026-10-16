<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Card', template: '@SuluPrelineBlocks/components/Card.html.twig')]
final class Card extends AbstractPrelineComponent
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
            'card_link',
            'link',
            'link_title',
            'link_text',
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
            'media_width' => '1-2',
            'object_fit' => 'cover',
            'meta_align' => 'above-title',
            'title_element' => 'h3',
            'title_size' => 'md',
            'title_decoration' => 'none',
            'meta_badge_style' => 'pill',
            'badge_style' => 'glass',
            'badge_color' => 'primary',
            'badge_position' => 'top-left',
            'padding' => 'normal',
            'rounded' => 'xl',
            'button_style' => 'solid',
            'button_color' => 'primary',
            'button_size' => 'sm',
            'link_card' => 'none',
            'media_source' => 'media',
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasAny('title', 'summary', 'content', 'meta', 'badge', 'badges', 'buttons', 'elements')
            || $this->hasMedia()
            || null !== $this->getIconClass();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'overlay' => fn (): bool => $this->isOverlay(),
            'horizontal' => fn (): bool => $this->isHorizontal(),
            'vertical' => fn (): bool => !$this->isHorizontal() && !$this->isOverlay(),
            'image' => fn (): bool => $this->isImage(),
            'video' => fn (): bool => $this->isVideo(),
            'media' => fn (): bool => $this->hasMedia(),
            'linked' => fn (): bool => null !== $this->getResolvedCardUrl(),
        ];
    }

    public function getMediaAlign(): string
    {
        $align = $this->nullableStringProp('media_align') ?? $this->stringProp('layout', 'top');
        if ('horizontal' === $align) {
            return 'left';
        }

        return \in_array($align, ['top', 'bottom', 'left', 'right', 'between', 'overlay'], true)
            ? $align
            : 'top';
    }

    public function isOverlay(): bool
    {
        return 'overlay' === $this->getMediaAlign();
    }

    public function isHorizontal(): bool
    {
        return \in_array($this->getMediaAlign(), ['left', 'right'], true);
    }

    public function getMediaGridWidthClasses(): string
    {
        return match ($this->stringProp('media_width', '1-2')) {
            '1-3' => 'md:w-1/3',
            '2-5' => 'md:w-2/5',
            default => 'md:w-1/2',
        };
    }

    public function getContentGridWidthClasses(): string
    {
        return match ($this->stringProp('media_width', '1-2')) {
            '1-3' => 'md:w-2/3',
            '2-5' => 'md:w-3/5',
            default => 'md:w-1/2',
        };
    }

    public function getMediaAspectRatioClass(): string
    {
        $aspect = $this->nullableStringProp('aspect_ratio') ?? $this->stringProp('media_aspect_ratio', '16-9');

        return match ($aspect) {
            '4-3', '4/3', '4:3' => 'aspect-4/3',
            '1-1', '1/1', '1:1', 'square' => 'aspect-square',
            '21-9', '21/9', '21:9' => 'aspect-21/9',
            'auto', 'manual' => 'aspect-auto',
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
        if (\in_array($this->getMediaAlign(), ['left', 'right'], true)) {
            return '(min-width: 1024px) 25vw, (min-width: 768px) 33vw, 100vw';
        }

        return '(min-width: 1280px) 33vw, (min-width: 768px) 50vw, 100vw';
    }

    public function getMetaAlign(): string
    {
        return $this->enumProp('meta_align', ['above-title', 'below-title', 'below-content'], 'above-title');
    }

    public function getTitleElement(): string
    {
        $el = $this->nullableStringProp('title_element') ?? $this->stringProp('tag', 'h3');

        return \in_array($el, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'], true) ? $el : 'h3';
    }

    public function getTitleSizeClasses(): string
    {
        $size = $this->nullableStringProp('title_size') ?? $this->stringProp('size', 'md');

        return match ($size) {
            'sm' => 'text-base sm:text-lg font-semibold',
            'lg' => 'text-xl sm:text-2xl font-bold',
            'xl' => 'text-2xl sm:text-3xl font-extrabold tracking-tight',
            default => 'text-lg sm:text-xl font-bold',
        };
    }

    public function getTitleDecorationClasses(): string
    {
        return match ($this->stringProp('title_decoration', 'none')) {
            'underline' => 'underline decoration-primary-500 decoration-2 underline-offset-4',
            default => '',
        };
    }

    public function getMetaBadgeClasses(): string
    {
        return match ($this->stringProp('meta_badge_style', 'pill')) {
            'primary_pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary-100 text-primary-800 rounded-full dark:bg-primary-950/40 dark:text-primary-300',
            'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-surface text-surface-foreground rounded-full',
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
            'top-right' => 'absolute top-3 end-3 z-10 flex flex-wrap gap-1.5',
            'bottom-left' => 'absolute bottom-3 start-3 z-10 flex flex-wrap gap-1.5',
            'bottom-right' => 'absolute bottom-3 end-3 z-10 flex flex-wrap gap-1.5',
            'inline' => '',
            default => 'absolute top-3 start-3 z-10 flex flex-wrap gap-1.5',
        };
    }

    public function getCardPaddingClasses(): string
    {
        return match ($this->stringProp('padding', 'normal')) {
            'none' => 'p-0',
            'compact' => 'p-4 sm:p-5',
            'spacious' => 'p-6 sm:p-8',
            default => 'p-5 sm:p-6',
        };
    }

    public function getRoundedClasses(): string
    {
        $variant = $this->nullableStringProp('card_variant') ?? $this->stringProp('variant', 'bordered');
        if ('group' === $variant) {
            return 'rounded-none';
        }

        return match ($this->stringProp('rounded', 'xl')) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            '2xl' => 'rounded-2xl',
            '3xl' => 'rounded-3xl',
            'full' => 'rounded-full',
            default => 'rounded-xl',
        };
    }

    public function getLinkUrl(string $key = 'link'): ?string
    {
        $link = $this->get('link') ?? $this->get('card_link');
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
        $text = $this->nullableString('link_title') ?? $this->nullableString('link_text');
        if (null !== $text) {
            return $text;
        }

        $link = $this->get('link') ?? $this->get('card_link');
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

    public function getButtonStyleClasses(): string
    {
        return match ($this->stringProp('button_style', 'primary')) {
            'secondary' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-layer-line bg-layer text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden focus:bg-layer-focus',
            'outline' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 transition-colors focus:outline-hidden',
            'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-primary hover:underline focus:outline-hidden',
            default => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-primary',
        };
    }

    public function getResolvedCardUrl(): ?string
    {
        $linkCard = $this->stringProp('link_card', 'none');
        $url = $this->getLinkUrl();

        if (null === $url) {
            return null;
        }

        if (\in_array($linkCard, ['card', 'card_link', 'first_button'], true)) {
            return $url;
        }

        if ('none' === $linkCard && null === $this->getButtonText()) {
            return $url;
        }

        return null;
    }

    public function hasMedia(): bool
    {
        return (null !== $this->getActiveImageUrl() && $this->isImage())
            || (null !== $this->getActiveVideoUrl() && $this->isVideo());
    }

    public function getCardClasses(): string
    {
        $props = $this->props();
        $variant = $this->nullableStringProp('card_variant') ?? $this->stringProp('variant', 'bordered');
        $bg = PropsResolver::bgColor($props);
        $rounded = $this->getRoundedClasses();
        $mediaAlign = $this->getMediaAlign();

        $base = 'w-full flex overflow-hidden transition-all';

        if ('group' === $variant) {
            return \trim(\sprintf('%s rounded-none border-0 shadow-none flex-col flex-1 %s', $base, $bg));
        }

        $variantClasses = match ($variant) {
            'shadow' => 'shadow-md hover:shadow-lg border border-card-line',
            'flat' => 'border-0',
            'interactive' => 'border border-card-line shadow-2xs hover:-translate-y-1 hover:shadow-lg transition-all duration-300',
            'glass' => 'backdrop-blur-md bg-card/70 border border-card-line/40 shadow-lg',
            default => 'border border-card-line shadow-2xs',
        };

        if ('overlay' === $mediaAlign) {
            return \trim(\sprintf('%s %s relative text-white shadow-md', $base, $rounded));
        }

        $flexDirection = match ($mediaAlign) {
            'left' => 'flex-col md:flex-row',
            'right' => 'flex-col md:flex-row-reverse',
            default => 'flex-col',
        };

        return \trim(\sprintf('%s %s %s %s %s', $base, $rounded, $variantClasses, $flexDirection, $bg));
    }

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->props());
    }

    public function getTitleColor(): string
    {
        if ($this->isOverlay()) {
            return 'text-white';
        }

        return PropsResolver::titleColor($this->props());
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

    public function getMediaType(): ?string
    {
        $forced = $this->stringProp('media_type', 'auto');

        if ('none' === $forced) {
            return null;
        }

        if ('video' === $forced && null !== $this->getVideoUrl()) {
            return 'video';
        }

        if ('image' === $forced && null !== $this->getImageUrl()) {
            return 'image';
        }

        if (null !== $this->getImageUrl()) {
            return 'image';
        }

        if (null !== $this->getVideoUrl()) {
            return 'video';
        }

        return null;
    }

    public function isImage(): bool
    {
        return 'image' === $this->getMediaType();
    }

    public function isVideo(): bool
    {
        return 'video' === $this->getMediaType();
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
        $base = \trim('group relative w-full ' . $this->getCardClasses() . ' ' . $this->getTextAlign() . ' ' . $extraBase);

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
        $singleLink = $this->get('link') ?? $this->get('card_link');
        $singleLinkTitle = $this->nullableString('link_title') ?? $this->nullableString('link_text');

        return $this->resolveButtonsList('solid', 'primary', 'sm', $singleLink, $this->getButtonText(), $singleLinkTitle);
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
