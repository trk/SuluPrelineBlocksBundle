<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

#[AsTwigComponent('Card', template: '@SuluPrelineBlocks/components/Card.html.twig')]
final class Card extends PrelineComponent
{
    protected bool $applyWrapperPadding = false;

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

        return $this->matchEnum($align, ['top', 'bottom', 'left', 'right', 'between', 'overlay'], 'top');
    }

    public function isOverlay(): bool
    {
        return 'overlay' === $this->getMediaAlign();
    }

    public function isHorizontal(): bool
    {
        return $this->oneOf($this->getMediaAlign(), ['left', 'right']);
    }

    /**
     * @return array{media: string, content: string, fraction: float}
     */
    public function getMediaWidthPreset(): array
    {
        return PrelineClassMap::CARD_MEDIA_WIDTHS[$this->stringProp('media_width', '1-2')]
            ?? PrelineClassMap::CARD_MEDIA_WIDTHS['1-2'];
    }

    public function getMediaGridWidthClasses(): string
    {
        return $this->getMediaWidthPreset()['media'];
    }

    public function getContentGridWidthClasses(): string
    {
        return $this->getMediaWidthPreset()['content'];
    }

    /**
     * Media slot: the full card width when stacked, the `media_width` fraction once a horizontal
     * card switches to side-by-side.
     */
    public function getMediaSlotWidth(): ResponsiveWidth
    {
        $layout = $this->getSlotWidth();
        if (!$this->isHorizontal()) {
            return $layout;
        }

        return $layout->scale([
            'base' => 1.0,
            PrelineClassMap::CARD_HORIZONTAL_BREAKPOINT => $this->getMediaWidthPreset()['fraction'],
        ]);
    }

    /**
     * Media column for horizontal cards; never collapses when the aspect ratio is `auto`.
     */
    public function getMediaColumnClasses(): string
    {
        return $this->classes(
            'relative shrink-0 overflow-hidden',
            $this->getMediaGridWidthClasses(),
            $this->getMediaAspectRatioClass('16/9', PrelineClassMap::MEDIA_AUTO_MIN_HEIGHT . ' md:min-h-full'),
        );
    }

    /**
     * Content layer of overlay-mode cards. The card height comes from the aspect ratio, or from the
     * minimum media height when the ratio is `auto`.
     */
    public function getOverlayContentClasses(): string
    {
        return $this->classes(
            'relative flex flex-1 flex-col justify-end text-white',
            'auto' === $this->getAspectRatioKey('16/9') ? PrelineClassMap::MEDIA_AUTO_MIN_HEIGHT : null,
            $this->getCardPaddingClasses(),
        );
    }

    public function getIconTileClasses(): string
    {
        return $this->isOverlay() ? PrelineClassMap::CARD_ICON_TILE_ON_MEDIA : PrelineClassMap::CARD_ICON_TILE;
    }

    public function getRoundedClasses(string $default = 'xl', string $key = 'rounded'): string
    {
        $panelStyle = $this->nullableStringProp('panel_style');
        $rawVariant = (null !== $panelStyle && 'inherit' !== $panelStyle)
            ? $panelStyle
            : $this->nullableStringProp('card_variant');
        $variant = (null !== $rawVariant && 'inherit' !== $rawVariant) ? $rawVariant : $this->stringProp('variant', 'bordered');
        if ('group' === $variant) {
            return 'rounded-none';
        }

        return parent::getRoundedClasses($default, $key);
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
            if ('badge' !== $this->itemString($button, 'type') && isset($button['link'])) {
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
        if (\is_array($link)) {
            $fromLink = $this->itemNullableString($link, 'title');
            if (null !== $fromLink) {
                return $fromLink;
            }
        }

        $buttonList = $this->items('buttons');
        if ([] === $buttonList) {
            $buttonList = $this->items('elements');
        }

        foreach ($buttonList as $button) {
            if ('badge' !== $this->itemString($button, 'type')) {
                $content = $this->itemNullableString($button, 'title') ?? $this->itemNullableString($button, 'content');
                if (null !== $content) {
                    return $content;
                }
            }
        }

        return null;
    }

    public function getButtonStyleClasses(): string
    {
        $linkStyle = $this->nullableStringProp('link_style');
        $rawStyle = (null !== $linkStyle && 'inherit' !== $linkStyle)
            ? $linkStyle
            : $this->nullableStringProp('button_style');
        $style = (null !== $rawStyle && 'inherit' !== $rawStyle) ? $rawStyle : 'primary';

        return PrelineClassMap::resolve(
            PrelineClassMap::CARD_BUTTON_STYLES,
            $style,
            PrelineClassMap::CARD_BUTTON_STYLES['primary'],
        );
    }

    public function getResolvedCardUrl(): ?string
    {
        $linkCard = $this->stringProp('link_card', 'none');
        $url = $this->getLinkUrl();

        if (null === $url) {
            return null;
        }

        if ($this->oneOf($linkCard, ['card', 'card_link', 'first_button'])) {
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
        $panelStyle = $this->nullableStringProp('panel_style');
        $rawVariant = (null !== $panelStyle && 'inherit' !== $panelStyle)
            ? $panelStyle
            : $this->nullableStringProp('card_variant');
        $variant = (null !== $rawVariant && 'inherit' !== $rawVariant) ? $rawVariant : $this->stringProp('variant', 'bordered');
        $bg = PropsResolver::bgColor($props);
        $rounded = $this->getRoundedClasses();
        $mediaAlign = $this->getMediaAlign();

        $base = 'w-full flex overflow-hidden transition-all';

        if ('group' === $variant) {
            return $this->classes($base, 'rounded-none border-0 shadow-none flex-col flex-1', $bg);
        }

        $variantClasses = PrelineClassMap::resolve(
            PrelineClassMap::CARD_VARIANTS,
            $variant,
            PrelineClassMap::CARD_VARIANTS['bordered'],
        );

        if ('overlay' === $mediaAlign) {
            return $this->classes(
                $base,
                $rounded,
                'relative text-white shadow-md',
                $this->getMediaAspectRatioClass('16/9', ''),
            );
        }

        $flexDirection = PrelineClassMap::CARD_HORIZONTAL_DIRECTIONS[$mediaAlign] ?? 'flex-col';

        return $this->classes($base, $rounded, $variantClasses, $flexDirection, $bg);
    }

    public function getTitleColor(string $default = 'default'): string
    {
        if ($this->isOverlay()) {
            return 'text-white';
        }

        return parent::getTitleColor($default);
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

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes(
            'group relative w-full',
            $this->getCardClasses(),
            $extraBase,
            ...$moreParts,
        );
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
}
