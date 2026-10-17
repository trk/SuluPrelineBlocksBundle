<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluBlockSuiteBundle\Twig\Component\AbstractBlockComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

/**
 * Unified Preline UI 5 & Tailwind CSS 4 base component.
 *
 * Centralizes all universal block controls (`margin`, `padding`, `animation`, `position`,
 * `max_width`, `block_align`, `text_align`, `blend`, `parallax`, `custom_css`, `attributes`,
 * `id`, `visibility`) and shared visual tokens (`colors`, `rounded`, `shadow`, `aspect_ratio`,
 * `object_fit`, `typography`, `gaps`, `grids`, `sliders`, `badges`, `buttons`, `media`, `links`).
 */
abstract class PrelineComponent extends AbstractBlockComponent
{
    /**
     * Prop / render-context key carrying the serialized `ResponsiveWidth` of the parent slot.
     */
    public const SLOT_KEY = '_slot';

    /**
     * When true, `getWrapperClasses()` includes `props['padding']` in addition to `props['block_padding']`.
     * Components that apply `props['padding']` to an inner container (e.g. `Card`, `Column`) set this to false.
     */
    protected bool $applyWrapperPadding = true;

    // =========================================================================
    // 1. Universal Element Controls (Margin, Padding, Width, Align, Position,
    //    Visibility, Animation, Blend, Parallax, Custom Class/ID/Attrs/CSS)
    // =========================================================================

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->props(), [static::class, $this->data, $this->extractRenderLoopIndex()]);
    }

    public function getComponentUid(): string
    {
        return $this->getElementId()
            ?? ('hs-' . \substr(\md5((string) \json_encode([static::class, $this->data, $this->props, $this->extractRenderLoopIndex()])), 0, 10));
    }

    private function extractRenderLoopIndex(): ?int
    {
        $loop = $this->context['loop'] ?? null;
        if (\is_array($loop) && isset($loop['index0']) && \is_numeric($loop['index0'])) {
            return (int) $loop['index0'];
        }

        return null;
    }

    public function getCustomClass(): string
    {
        return ElementTransform::customClass($this->props());
    }

    public function getMarginClass(): string
    {
        return ElementTransform::margin($this->props());
    }

    public function getPaddingClass(
        string $default = 'none',
        string $key = 'padding',
        string $preset = 'default',
    ): string {
        return ElementTransform::padding($this->props(), $default, $key, $preset);
    }

    public function getPaddingClasses(
        string $default = 'none',
        string $key = 'padding',
        string $preset = 'default',
    ): string {
        return $this->getPaddingClass($default, $key, $preset);
    }

    public function getCardPaddingClasses(string $default = 'normal', string $key = 'padding'): string
    {
        return ElementTransform::padding($this->props(), $default, $key, 'card');
    }

    public function getOverlayPaddingClasses(string $default = 'normal', string $key = 'padding'): string
    {
        return ElementTransform::padding($this->props(), $default, $key, 'overlay');
    }

    public function getMaxWidthClass(string $default = 'none', string $preset = 'default'): string
    {
        return ElementTransform::maxWidth($this->props(), $default, $preset);
    }

    public function getBlockAlignClass(): string
    {
        return ElementTransform::blockAlign($this->props());
    }

    public function getTextAlign(string $default = 'left'): string
    {
        return PropsResolver::textAlign($this->props(), $default);
    }

    public function getTextAlignClass(string $default = 'start'): string
    {
        return ElementTransform::textAlign($this->props(), $default, true);
    }

    public function getAlignClasses(string $default = 'left', string $key = 'align'): string
    {
        $val = $this->stringProp($key, $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::FLEX_ALIGN,
            $val,
            PrelineClassMap::FLEX_ALIGN[$default] ?? 'justify-start',
        );
    }

    public function getFilterAlignClass(string $default = 'center', string $key = 'filter_align'): string
    {
        return $this->getAlignClasses($default, $key);
    }

    public function getAlignWithTextClasses(string $default = 'left', string $key = 'align'): string
    {
        $align = $this->nullableStringProp($key);
        if (null === $align || $default === $align) {
            $align = $this->stringProp('text_align', $align ?? $default);
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::ALIGN_WITH_TEXT,
            $align,
            PrelineClassMap::ALIGN_WITH_TEXT[$default] ?? 'justify-start text-start',
        );
    }

    public function getVerticalAlignClasses(string $default = 'top', string $key = 'vertical_align'): string
    {
        $val = $this->stringProp($key, $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::VERTICAL_ALIGN,
            $val,
            PrelineClassMap::VERTICAL_ALIGN[$default] ?? 'justify-start',
        );
    }

    public function getAlignItemsClasses(string $default = 'stretch', string $key = 'align_items'): string
    {
        $val = $this->stringProp($key, $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::ALIGN_ITEMS,
            $val,
            PrelineClassMap::ALIGN_ITEMS[$default] ?? 'items-stretch',
        );
    }

    public function getPositionClass(): string
    {
        return ElementTransform::position($this->props());
    }

    public function getVisibilityClass(): string
    {
        return ElementTransform::visibility($this->props());
    }

    public function getAnimationClass(): string
    {
        return ElementTransform::animation($this->props());
    }

    public function getBlendModeClass(): string
    {
        return ElementTransform::blendMode($this->props());
    }

    public function getParallaxClass(): string
    {
        return ElementTransform::parallax($this->props());
    }

    /**
     * @return array<string, string>
     */
    public function getParallaxAttributes(): array
    {
        return ElementTransform::resolveParallaxAttributes($this->props());
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
        return ElementTransform::resolveScopedCss($this->props(), $this->getElementId());
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return ElementTransform::wrapperClasses(
            $this->props(),
            $this->classes($extraBase, ...$moreParts),
            $this->applyWrapperPadding,
        );
    }

    /**
     * Standard block-level classes for the inner block <div> inside the block container.
     *
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes('w-full', $extraBase, ...$moreParts);
    }

    /**
     * Builds the root HTML attribute dictionary (`id`, `class`, and custom attributes).
     *
     * @param string|array<int|string, mixed>|null|false ...$moreClasses
     *
     * @return array<string, string|bool>
     */
    public function getRootAttributes(string $extraClass = '', string|array|null|false ...$moreClasses): array
    {
        return $this->getRootAttributesWithClass($this->getWrapperClasses($extraClass, ...$moreClasses));
    }

    /**
     * Builds the root HTML attribute dictionary (`id`, `class`, and custom attributes) using an explicit class string.
     *
     * @return array<string, string|bool>
     */
    public function getRootAttributesWithClass(string $class): array
    {
        $props = $this->props();
        $attrs = $this->getCustomAttributes();

        $id = $this->getElementId();
        if (null !== $id && '' !== $id) {
            $attrs['id'] = $id;
        }

        if ('' !== $class) {
            $attrs['class'] = $class;
        }

        $positionStyle = ElementTransform::resolvePositionStyle($props);
        if ('' !== $positionStyle) {
            $existingStyle = isset($attrs['style']) && \is_string($attrs['style']) ? \rtrim(\trim($attrs['style']), ';') : '';
            $attrs['style'] = '' !== $existingStyle ? $existingStyle . '; ' . $positionStyle : $positionStyle;
        }

        if ($this->boolProp('item_animation', false) && '' !== $this->getAnimationClass()) {
            $attrs['data-item-animation'] = 'true';
        }

        return $attrs;
    }

    public function getResolvedContent(): ?string
    {
        return $this->nullableString('summary')
            ?? $this->nullableString('content')
            ?? $this->nullableString('text');
    }

    public function getTitle(): ?string
    {
        return $this->nullableString('title');
    }

    public function getSubtitle(): ?string
    {
        return $this->nullableString('subtitle') ?? $this->nullableString('subheading');
    }

    public function getMeta(): ?string
    {
        return $this->nullableString('meta');
    }

    // =========================================================================
    // 2. Shared Colors, Shapes, Shadows & Typography
    // =========================================================================

    public function getTitleColor(string $default = 'default'): string
    {
        return PropsResolver::titleColor($this->props(), $default);
    }

    public function getTextColor(string $default = 'default'): string
    {
        $props = $this->props();
        if (isset($props['color']) && ('default' === ($props['text_color'] ?? 'default'))) {
            unset($props['text_color']);
        }

        return PropsResolver::textColor($props, $default);
    }

    public function getLinkColor(string $default = 'primary'): string
    {
        return PropsResolver::linkColor($this->props(), $default);
    }

    public function getBgColor(string $default = 'transparent'): string
    {
        return PropsResolver::bgColor($this->props(), $default);
    }

    public function getRoundedClasses(string $default = 'xl', string $key = 'rounded'): string
    {
        return PropsResolver::rounded($this->props(), $default, $key);
    }

    public function getRounded(string $default = 'lg', string $key = 'rounded'): string
    {
        return PropsResolver::rounded($this->props(), $default, $key);
    }

    public function getShadowClasses(string $default = 'none', string $fallbackEmpty = ''): string
    {
        return PropsResolver::shadow($this->props(), $default, $fallbackEmpty);
    }

    /**
     * @param list<string> $allowed
     */
    public function getTitleElement(string $default = 'h3', array $allowed = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div']): string
    {
        $el = $this->nullableStringProp('title_element') ?? $this->stringProp('tag', $default);

        return $this->oneOf($el, $allowed) ? $el : $default;
    }

    public function getTitleSizeClasses(string $default = 'md', string $preset = 'default'): string
    {
        $size = $this->nullableStringProp('title_size') ?? $this->stringProp('size', $default);

        $map = match ($preset) {
            'overlay' => PrelineClassMap::OVERLAY_TITLE_SIZES,
            'alert' => PrelineClassMap::ALERT_TITLE_SIZES,
            'accordion' => PrelineClassMap::ACCORDION_TITLE_SIZES,
            'headline' => PrelineClassMap::HEADLINE_SIZES,
            default => PrelineClassMap::TITLE_SIZES,
        };

        return PrelineClassMap::resolve($map, $size, $map[$default] ?? PrelineClassMap::TITLE_SIZES['md']);
    }

    public function getTitleDecorationClasses(string $default = 'none'): string
    {
        $dec = $this->stringProp('title_decoration', $default);

        return PrelineClassMap::resolve(PrelineClassMap::TITLE_DECORATIONS, $dec);
    }

    public function getFontSize(string $default = 'base'): string
    {
        $size = $this->nullableStringProp('font_size');
        if (null === $size || $default === $size) {
            $size = $this->stringProp('size', $size ?? $default);
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::FONT_SIZES,
            $size,
            PrelineClassMap::FONT_SIZES[$default] ?? 'text-base',
        );
    }

    public function getGapClasses(string $default = 'md', string $preset = 'grid', string $key = 'gap'): string
    {
        $val = $this->stringProp($key, $default);
        $map = match ($preset) {
            'row' => PrelineClassMap::ROW_GAPS,
            'slider' => PrelineClassMap::SLIDER_GAPS,
            'button_group' => PrelineClassMap::BUTTON_GROUP_GAPS,
            default => PrelineClassMap::GRID_GAPS,
        };

        return PrelineClassMap::resolve($map, $val, $map[$default] ?? '');
    }

    // =========================================================================
    // 3. Shared Media, Aspect Ratio, Object Fit, Focal Point & Links
    // =========================================================================

    public function getAspectRatio(string $default = 'auto', string $key = 'aspect_ratio'): string
    {
        return PropsResolver::aspectRatio($this->props(), $default, $key);
    }

    public function getAspectRatioClasses(string $default = '16/9', string $key = 'aspect_ratio'): string
    {
        return PropsResolver::aspectRatio($this->props(), $default, $key);
    }

    /**
     * Active aspect-ratio key (`aspect_ratio`, falling back to `media_aspect_ratio`).
     */
    public function getAspectRatioKey(string $default = 'auto'): string
    {
        return $this->nullableStringProp('aspect_ratio') ?? $this->stringProp('media_aspect_ratio', $default);
    }

    public function getMediaAspectRatioClass(string $default = '16/9', string $autoClass = 'aspect-auto'): string
    {
        $aspect = $this->getAspectRatioKey($default);
        if ('auto' === $aspect) {
            return $autoClass;
        }

        $preset = PrelineClassMap::aspectRatio($aspect) ?? PrelineClassMap::aspectRatio($default);

        return null !== $preset ? $preset['class'] : $autoClass;
    }

    /**
     * Custom ratio entered by the editor when the aspect ratio is `manual`.
     *
     * @return array{float, float}|null
     */
    public function getManualAspectRatio(): ?array
    {
        if ('manual' !== $this->getAspectRatioKey()) {
            return null;
        }

        $width = $this->floatProp('aspect_ratio_width', 0.0);
        $height = $this->floatProp('aspect_ratio_height', 0.0);

        return $width > 0.0 && $height > 0.0 ? [$width, $height] : null;
    }

    /**
     * Numeric width/height ratio of the media (preset or manual); `null` when intrinsic (`auto`).
     *
     * @return array{float, float}|null
     */
    public function getAspectRatioValue(string $default = 'auto'): ?array
    {
        $manual = $this->getManualAspectRatio();
        if (null !== $manual) {
            return $manual;
        }

        $ratio = PrelineClassMap::aspectRatio($this->getAspectRatioKey($default))['ratio'] ?? null;

        return null !== $ratio ? [(float) $ratio[0], (float) $ratio[1]] : null;
    }

    public function getMediaAspectRatioStyle(): string
    {
        $manual = $this->getManualAspectRatio();
        if (null === $manual) {
            return '';
        }

        return \sprintf('aspect-ratio: %s / %s;', self::formatRatioNumber($manual[0]), self::formatRatioNumber($manual[1]));
    }

    /**
     * Intrinsic `width`/`height` attributes for `<img>` (reserves space and prevents layout shift):
     * width is the largest rendered slot width, height follows the active aspect ratio.
     *
     * @return array{width: int, height: int}|null
     */
    public function getIntrinsicImageDimensions(string $defaultAspect = 'auto'): ?array
    {
        $ratio = $this->getAspectRatioValue($defaultAspect);
        if (null === $ratio) {
            return null;
        }

        $width = $this->getMediaSlotWidth()->maxSlotPx();

        return ['width' => $width, 'height' => (int) \round($width * $ratio[1] / $ratio[0])];
    }

    /**
     * Rendered width of this block at every breakpoint: the layout slot inherited from the
     * parent container (`_slot` prop or render context) capped by the block's own `max_width`.
     * Without a parent container, the default Section container is assumed.
     */
    public function getSlotWidth(): ResponsiveWidth
    {
        return $this->narrowSlotWidth($this->resolveInheritedSlotWidth())
            ->cap(PrelineClassMap::blockMaxWidth($this->prop('max_width'))['px']);
    }

    /**
     * Slot provided by the parent (`_slot` prop, then render context), or the default Section
     * container when the block is rendered standalone.
     */
    protected function resolveInheritedSlotWidth(): ResponsiveWidth
    {
        return ResponsiveWidth::fromArray($this->prop(self::SLOT_KEY))
            ?? ResponsiveWidth::fromArray($this->context()[self::SLOT_KEY] ?? null)
            ?? ResponsiveWidth::container(PrelineClassMap::sectionMaxWidth('default')['px']);
    }

    /**
     * Hook for components that occupy only part of the inherited slot (e.g. a standalone column span).
     */
    protected function narrowSlotWidth(ResponsiveWidth $inherited): ResponsiveWidth
    {
        return $inherited;
    }

    /**
     * Rendered width of the block's media slot. Components with split layouts
     * (e.g. horizontal cards, side images) narrow it further.
     */
    public function getMediaSlotWidth(): ResponsiveWidth
    {
        return $this->getSlotWidth();
    }

    /**
     * Render-context entries that pass this block's layout slot to nested blocks.
     *
     * @return array<string, mixed>
     */
    public function getChildContext(): array
    {
        return \array_merge($this->context(), [self::SLOT_KEY => $this->getSlotWidth()->toArray()]);
    }

    /**
     * Frame around an embedded image: fixed by the aspect ratio (inline style for `manual`).
     */
    public function getMediaFrameClasses(string $defaultAspect = '16/9'): string
    {
        return $this->classes('relative w-full overflow-hidden', $this->getMediaAspectRatioClass($defaultAspect, ''));
    }

    /**
     * `<img>` inside a media frame: fills the frame when a ratio is set, natural height otherwise.
     */
    public function getMediaImgClasses(string $defaultAspect = '16/9'): string
    {
        return $this->classes(
            'block w-full',
            null !== $this->getAspectRatioValue($defaultAspect) ? 'h-full' : 'h-auto',
            $this->getObjectFitClass(),
        );
    }

    private static function formatRatioNumber(float $value): string
    {
        return \rtrim(\rtrim(\number_format($value, 4, '.', ''), '0'), '.');
    }

    public function getObjectFitClass(string $default = 'cover', string $key = 'object_fit'): string
    {
        return PropsResolver::resolveObjectFit($this->stringProp($key, $default));
    }

    private static function sanitizeMediaUrl(?string $url): ?string
    {
        if (null === $url) {
            return null;
        }

        $clean = SuluDataNormalizer::sanitizeUrl(\trim($url), '');

        return ('' !== $clean && '#' !== $clean) ? $clean : null;
    }

    public function getImageUrl(string $key = 'image'): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source && 'image' === $key) {
            $url = self::sanitizeMediaUrl($this->nullableString('image_url') ?? $this->nullableStringProp('image_url'));
            if (null !== $url) {
                return $url;
            }
        }

        $resolved = SuluDataNormalizer::resolveImageUrl($this->get($key));
        if (null !== $resolved || 'image' !== $key) {
            return $resolved;
        }

        return self::sanitizeMediaUrl($this->nullableString('image_url') ?? $this->nullableStringProp('image_url'));
    }

    public function getHoverImageUrl(): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = self::sanitizeMediaUrl($this->nullableString('hover_image_url') ?? $this->nullableStringProp('hover_image_url'));
            if (null !== $url) {
                return $url;
            }
        }

        return SuluDataNormalizer::resolveImageUrl($this->get('hover_image'))
            ?? self::sanitizeMediaUrl($this->nullableString('hover_image_url') ?? $this->nullableStringProp('hover_image_url'));
    }

    public function getImageSrcSet(string $key = 'image'): ?string
    {
        return SuluDataNormalizer::resolveSrcSet($this->get($key));
    }

    public function getHoverImageSrcSet(): ?string
    {
        return SuluDataNormalizer::resolveSrcSet($this->get('hover_image'));
    }

    public function getImageSizes(): string
    {
        return $this->nullableStringProp('sizes') ?? $this->getMediaSlotWidth()->toSizes();
    }

    public function getImageFocusPointStyle(string $key = 'image'): ?string
    {
        return SuluDataNormalizer::resolveFocusPointStyle($this->get($key));
    }

    public function getHoverImageFocusPointStyle(): ?string
    {
        return SuluDataNormalizer::resolveFocusPointStyle($this->get('hover_image'));
    }

    public function isImageEager(): bool
    {
        return $this->boolProp('image_loading', false)
            || 'eager' === $this->stringProp('loading', 'lazy');
    }

    public function getImageLoading(bool $isFirstItem = true): string
    {
        return ($isFirstItem && $this->isImageEager()) ? 'eager' : 'lazy';
    }

    public function getImageFetchPriority(bool $isFirstItem = true, bool $isHiddenOrOffscreen = false): ?string
    {
        $explicit = $this->nullableStringProp('fetchpriority');
        if ('low' === $explicit) {
            return 'low';
        }
        if ('high' === $explicit && $isFirstItem && $this->isImageEager()) {
            return 'high';
        }

        if ($isFirstItem && $this->isImageEager()) {
            return 'high';
        }

        if ($isHiddenOrOffscreen) {
            return 'low';
        }

        return null;
    }

    public function getImageAlt(string $key = 'image', string $fallbackKey = 'title'): string
    {
        $explicitAlt = $this->nullableString('alt') ?? $this->nullableStringProp('alt');
        if (null !== $explicitAlt) {
            return $explicitAlt;
        }

        $extracted = $this->extractMediaAlt($this->get($key));
        if (null !== $extracted) {
            return $extracted;
        }

        return '' !== $fallbackKey ? $this->string($fallbackKey, '') : '';
    }

    protected function extractMediaAlt(mixed $media): ?string
    {
        if (\is_array($media)) {
            foreach (['alt', 'description', 'title'] as $field) {
                $candidate = $this->itemNullableString($media, $field);
                if (null !== $candidate) {
                    return $candidate;
                }
            }
        } elseif (\is_object($media)) {
            if (\method_exists($media, 'getDescription')) {
                $desc = $this->trim($media->getDescription());
                if ('' !== $desc) {
                    return $desc;
                }
            }
            if (\method_exists($media, 'getTitle')) {
                $title = $this->trim($media->getTitle());
                if ('' !== $title) {
                    return $title;
                }
            }
        }

        return null;
    }

    public function getVideoUrl(string $key = 'video'): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source && 'video' === $key) {
            $url = self::sanitizeMediaUrl($this->nullableString('video_url') ?? $this->nullableStringProp('video_url'));
            if (null !== $url) {
                return $url;
            }
        }

        $resolved = SuluDataNormalizer::resolveImageUrl($this->get($key));
        if (null !== $resolved || 'video' !== $key) {
            return $resolved;
        }

        return self::sanitizeMediaUrl($this->nullableString('video_url') ?? $this->nullableStringProp('video_url'));
    }

    public function getHoverVideoUrl(): ?string
    {
        $source = $this->stringProp('media_source', 'media');
        if ('url' === $source) {
            $url = self::sanitizeMediaUrl($this->nullableString('hover_video_url') ?? $this->nullableStringProp('hover_video_url'));
            if (null !== $url) {
                return $url;
            }
        }

        return SuluDataNormalizer::resolveImageUrl($this->get('hover_video'))
            ?? self::sanitizeMediaUrl($this->nullableString('hover_video_url') ?? $this->nullableStringProp('hover_video_url'));
    }

    public function getIconClass(string $key = 'icon'): ?string
    {
        $mediaType = $this->nullableStringProp('media_type');
        if (null !== $mediaType && 'icon' !== $mediaType) {
            return null;
        }

        return PropsResolver::resolveIcon($this->get($key));
    }

    public function getLinkUrl(string $key = 'link'): ?string
    {
        $resolved = SuluDataNormalizer::resolveUrl($this->get($key));

        return '#' !== $resolved && '' !== $resolved ? $resolved : null;
    }

    public function getLinkTarget(string $key = 'link'): ?string
    {
        $explicit = $this->nullableStringProp('target');
        if (null !== $explicit) {
            return $explicit;
        }

        $link = $this->get($key);
        if (\is_array($link)) {
            return $this->itemNullableString($link, 'target');
        }

        return null;
    }

    public function getLinkTitle(
        string $key = 'link',
        string $fallbackKey = 'link_title',
        bool $fallbackToFirstButton = false,
    ): ?string {
        $explicit = $this->nullableString($fallbackKey);
        if (null !== $explicit) {
            return $explicit;
        }

        $link = $this->get($key);
        if (\is_array($link)) {
            $fromLink = $this->itemNullableString($link, 'title');
            if (null !== $fromLink) {
                return $fromLink;
            }
        }

        if ($fallbackToFirstButton) {
            $buttonList = $this->items('buttons');
            if ([] === $buttonList) {
                $buttonList = $this->items('elements');
            }

            foreach ($buttonList as $button) {
                if ('badge' === $this->itemString($button, 'type')) {
                    continue;
                }

                $buttonLabel = $this->itemNullableString($button, 'title')
                    ?? $this->itemNullableString($button, 'content')
                    ?? $this->itemNullableString($button, 'link_title');
                if (null !== $buttonLabel) {
                    return $buttonLabel;
                }
            }
        }

        return null;
    }

    // =========================================================================
    // 4. Shared Card / Overlay / Badge / Button / Grid / Slider Helpers
    // =========================================================================

    public function getMetaAlign(string $default = 'above-title'): string
    {
        return $this->enumProp('meta_align', ['above-title', 'below-title', 'below-content'], $default);
    }

    public function getMetaBadgeClasses(string $default = 'pill', string $preset = 'default'): string
    {
        $style = $this->stringProp('meta_badge_style', $default);
        $map = 'overlay' === $preset ? PrelineClassMap::OVERLAY_META_BADGE_STYLES : PrelineClassMap::META_BADGE_STYLES;

        return PrelineClassMap::resolve($map, $style, $map[$default] ?? PrelineClassMap::META_BADGE_STYLES['pill']);
    }

    public function getBadgeClasses(string $default = 'glass'): string
    {
        $style = $this->stringProp('badge_style', $default);

        return PrelineClassMap::resolve(
            PrelineClassMap::MEDIA_BADGE_STYLES,
            $style,
            PrelineClassMap::MEDIA_BADGE_STYLES['glass'],
        );
    }

    public function getBadgePosition(string $default = 'top-left'): string
    {
        $pos = $this->stringProp('badge_position', $default);

        return PrelineClassMap::resolve(PrelineClassMap::BADGE_POSITIONS, $pos, $default);
    }

    public function getBadgePositionClasses(string $zIndex = 'z-10'): string
    {
        $map = 'z-20' === $zIndex
            ? PrelineClassMap::BADGE_POSITION_CLASSES_Z20
            : PrelineClassMap::BADGE_POSITION_CLASSES_Z10;

        return $map[$this->getBadgePosition()] ?? $map['top-left'];
    }

    protected function resolveResponsiveGridClasses(
        bool $masonry = false,
        int $defaultCols = 1,
        int $defaultMdCols = 3,
        string $defaultGap = 'md',
    ): string {
        return PrelineClassMap::resolveResponsiveGridClasses(
            $this->props(),
            $masonry,
            $defaultCols,
            $defaultMdCols,
            $defaultGap,
        );
    }

    /**
     * Rendered width of a single grid / masonry item, derived from the same `columns_*` data as
     * the grid classes.
     */
    public function getGridItemSlotWidth(int $defaultCols = 1, int $defaultMdCols = 3): ResponsiveWidth
    {
        return $this->getSlotWidth()->divide(PrelineClassMap::gridColumnCounts($this->props(), $defaultCols, $defaultMdCols));
    }

    /**
     * Extracts unique tag slug => label pairs across collection items (`tags` array/string or `tag` string).
     *
     * @return array<string, string>
     */
    public function getFilterTags(string $itemsKey = 'items'): array
    {
        $uniqueTags = [];

        foreach ($this->items($itemsKey) as $item) {
            foreach (['tags', 'tag'] as $field) {
                foreach ($this->split($item[$field] ?? null) as $label) {
                    $slug = $this->slug($label);
                    if ('' !== $slug && !isset($uniqueTags[$slug])) {
                        $uniqueTags[$slug] = $label;
                    }
                }
            }
        }

        return $uniqueTags;
    }

    /**
     * @param array<string, mixed> $item
     */
    public function getItemTagSlugs(array $item): string
    {
        $slugs = [];

        foreach (['tags', 'tag'] as $field) {
            foreach ($this->split($item[$field] ?? null) as $label) {
                $slug = $this->slug($label);
                if ('' !== $slug) {
                    $slugs[] = $slug;
                }
            }
        }

        return $this->join(\array_unique($slugs));
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageUrl(array $item, string $key = 'image'): ?string
    {
        return SuluDataNormalizer::resolveImageUrl($item[$key] ?? null);
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageSrcSet(array $item, string $key = 'image'): ?string
    {
        return SuluDataNormalizer::resolveSrcSet($item[$key] ?? null);
    }

    /**
     * @param array<string, mixed> $item
     */
    public function resolveItemImageFocusPointStyle(array $item, string $key = 'image'): ?string
    {
        return SuluDataNormalizer::resolveFocusPointStyle($item[$key] ?? null);
    }

    /**
     * Slider `columns` preset. `columns` is also a universal content key (row columns), so the
     * scalar setting may arrive as content data; it is read from data first, then props, and
     * validated against `SLIDER_SLIDES_PER_VIEW`.
     */
    protected function resolveSliderColumns(string $default = '3'): string
    {
        $value = $this->get('columns');
        $columns = \is_int($value) || \is_string($value) ? \trim((string) $value) : '';

        return isset(PrelineClassMap::SLIDER_SLIDES_PER_VIEW[$columns]) ? $columns : $default;
    }

    /**
     * Slides per view per breakpoint for a slider `columns` preset.
     *
     * @return array<string, int> sparse mobile-first map
     */
    public function getSlidesPerView(string $columns): array
    {
        return PrelineClassMap::SLIDER_SLIDES_PER_VIEW[$columns] ?? PrelineClassMap::SLIDER_SLIDES_PER_VIEW['3'];
    }

    /**
     * Carousel viewport height (`height` prop); the carousel body is absolutely positioned, so the
     * viewport needs an explicit height preset.
     */
    public function getHeightClass(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::CAROUSEL_HEIGHTS,
            $this->prop('height'),
            PrelineClassMap::CAROUSEL_HEIGHTS['medium'],
        );
    }

    protected function resolveSliderSlideWidthClass(string $columns): string
    {
        return PrelineClassMap::responsiveClasses(PrelineClassMap::SLIDE_WIDTHS, $this->getSlidesPerView($columns));
    }

    /**
     * Rendered width of a single slide for a slider `columns` preset.
     */
    public function getSliderItemSlotWidth(string $columns): ResponsiveWidth
    {
        return $this->getSlotWidth()->divide($this->getSlidesPerView($columns));
    }

    protected function resolveSliderConfigJson(
        bool $autoplay,
        int $interval,
        bool $loop,
        ?string $columns = null,
    ): string {
        $config = [
            'loadingClasses' => 'opacity-0',
            'isAutoPlay' => $autoplay,
            'speed' => $interval,
            'isInfiniteLoop' => $loop,
        ];

        if (null !== $columns) {
            $slidesQty = [];
            foreach (ResponsiveWidth::cascade($this->getSlidesPerView($columns), 1) as $bp => $qty) {
                $slidesQty['base' === $bp ? 'xs' : $bp] = (int) $qty;
            }
            $config['slidesQty'] = $slidesQty;
        }

        return \json_encode(
            $config,
            \JSON_THROW_ON_ERROR | \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT,
        );
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
            $item['image_url'] = SuluDataNormalizer::resolveImageUrl($item['image']);
            $item['image_srcset'] = SuluDataNormalizer::resolveSrcSet($item['image']);
            $item['image_focus_style'] = SuluDataNormalizer::resolveFocusPointStyle($item['image']);
        }

        if (isset($item['hover_image'])) {
            $item['hover_image_url'] = SuluDataNormalizer::resolveImageUrl($item['hover_image']);
            $item['hover_image_srcset'] = SuluDataNormalizer::resolveSrcSet($item['hover_image']);
            $item['hover_image_focus_style'] = SuluDataNormalizer::resolveFocusPointStyle($item['hover_image']);
        }

        if (isset($item['video'])) {
            $item['video_url'] = SuluDataNormalizer::resolveImageUrl($item['video']);
        }

        if (isset($item['hover_video'])) {
            $item['hover_video_url'] = SuluDataNormalizer::resolveImageUrl($item['hover_video']);
        }

        if (isset($item['icon'])) {
            $resolvedIcon = PropsResolver::resolveIcon($item['icon']);
            $item['icon_class'] = $resolvedIcon;
            if (null !== $resolvedIcon) {
                $item['icon'] = $resolvedIcon;
            }
        }

        if (isset($item['link'])) {
            $item['link_href'] = SuluDataNormalizer::resolveUrl($item['link']);
            $item['link_url'] = $item['link_href'];
            if (\is_array($item['link'])) {
                if (!isset($item['link_target']) && '_blank' === ($item['link']['target'] ?? null)) {
                    $item['link_target'] = '_blank';
                }
                if (!isset($item['link_title']) && \is_string($item['link']['title'] ?? null) && '' !== \trim($item['link']['title'])) {
                    $item['link_title'] = \trim($item['link']['title']);
                }
            }
        }

        return $item;
    }

    /**
     * Extracts item-level media & style configuration keys so parent collection blocks can merge them into child component props.
     *
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    protected function extractItemForwardedProps(array $item): array
    {
        $forwarded = [];
        $keys = [
            'media_type',
            'media_source',
            'aspect_ratio',
            'media_aspect_ratio',
            'object_fit',
            'aspect_ratio_width',
            'aspect_ratio_height',
            'image_url',
            'hover_image_url',
            'video_url',
            'hover_video_url',
        ];

        foreach ($keys as $key) {
            if (isset($item[$key]) && '' !== $item[$key]) {
                $forwarded[$key] = $item[$key];
            }
        }

        return $forwarded;
    }

    /**
     * Resolves per-item props for collection blocks (`PanelSlider`, `OverlaySlider`, `Grid`),
     * propagating slot width and first-item image loading / fetchpriority rules.
     *
     * @param array<string, mixed> $item
     *
     * @return array<string, mixed>
     */
    public function resolveItemProps(array $item, bool $isFirstItem = true, bool $isOffscreenSlide = false): array
    {
        $itemProps = $this->itemArray($item, 'props');
        $fetchPriority = $this->getImageFetchPriority($isFirstItem, $isOffscreenSlide);

        $loadingProps = [
            'image_loading' => $isFirstItem && $this->isImageEager(),
            'loading' => $this->getImageLoading($isFirstItem),
        ];
        if (null !== $fetchPriority) {
            $loadingProps['fetchpriority'] = $fetchPriority;
        }

        /** @var array<string, mixed> $merged */
        $merged = \array_merge(
            isset($item[self::SLOT_KEY]) ? [self::SLOT_KEY => $item[self::SLOT_KEY]] : [],
            $loadingProps,
            $this->extractItemForwardedProps($item),
            $itemProps,
        );

        return $merged;
    }

    /**
     * Normalizes `badges` / `elements` (type=badge) / single `badge` into displayable badge items.
     *
     * @return list<array<string, mixed>>
     */
    protected function resolveBadgesList(string $fallbackStyle = 'glass', string $fallbackColor = 'primary'): array
    {
        $defaultStyle = $this->stringProp('badge_style', $fallbackStyle);
        $defaultColor = $this->stringProp('badge_color', $fallbackColor);

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $badgeItems */
        $badgeItems = $this->items('badges');
        if ([] === $badgeItems) {
            foreach ($this->items('elements') as $item) {
                if ('badge' === $this->itemString($item, 'type')) {
                    $badgeItems[] = $item;
                }
            }
        }

        foreach ($badgeItems as $item) {
            $itemStyle = $this->itemInheritedString($item, 'badge_style', $defaultStyle);
            $itemColor = $this->itemInheritedString($item, 'badge_color', $defaultColor);
            $itemSize = $this->itemInheritedString($item, 'size', 'xs');
            $itemRounded = $this->itemInheritedString($item, 'rounded', 'pill');

            $title = $this->itemString($item, 'title', $this->itemString($item, 'text'));

            if ('' === $title && !isset($item['icon'])) {
                continue;
            }

            $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
            $iconAlign = 'right' === $this->itemString($item, 'icon_align') ? 'right' : 'left';
            $hasDot = $this->itemBool($item, 'has_dot');
            $link = $item['link'] ?? null;
            $url = SuluDataNormalizer::resolveUrl($link);
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
                'link' => $link,
                'is_clickable' => $isClickable,
                'link_title' => $this->itemNullableString($item, 'link_title'),
                'classes' => $classes,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'has_dot' => $hasDot,
                'badge_style' => $itemStyle,
                'badge_color' => $itemColor,
                'size' => $itemSize,
                'rounded' => $itemRounded,
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
                'link' => null,
                'is_clickable' => false,
                'link_title' => null,
                'classes' => $classes,
                'icon' => null,
                'icon_align' => 'left',
                'has_dot' => false,
                'badge_style' => $defaultStyle,
                'badge_color' => $defaultColor,
                'size' => 'xs',
                'rounded' => 'pill',
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
        $linkStyle = $this->nullableStringProp('link_style');
        $rawButtonStyle = (null !== $linkStyle && 'inherit' !== $linkStyle)
            ? $linkStyle
            : $this->nullableStringProp('button_style');
        $linkColor = $this->nullableStringProp('link_color');
        $buttonColor = $this->nullableStringProp('button_color');
        if (null !== $rawButtonStyle && \in_array($rawButtonStyle, ['primary', 'secondary', 'dark', 'muted'], true)) {
            $defaultStyle = 'solid';
            $defaultColor = (null !== $linkColor && 'inherit' !== $linkColor) ? $linkColor : $rawButtonStyle;
        } else {
            $explicitColor = (null !== $linkColor && 'inherit' !== $linkColor) ? $linkColor : $buttonColor;
            $defaultStyle = (null !== $rawButtonStyle && 'inherit' !== $rawButtonStyle) ? $rawButtonStyle : $fallbackStyle;
            $defaultColor = (null !== $explicitColor && 'inherit' !== $explicitColor) ? $explicitColor : $fallbackColor;
        }
        $defaultSize = $this->stringProp('button_size', $fallbackSize);

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $buttonItems */
        $buttonItems = $this->items('buttons');
        if ([] === $buttonItems) {
            foreach ($this->items('elements') as $item) {
                $type = $this->itemString($item, 'type', 'button');
                if ('button' === $type || 'badge' !== $type) {
                    $buttonItems[] = $item;
                }
            }
        }

        foreach ($buttonItems as $item) {
            $itemStyle = $this->itemInheritedString(
                $item,
                'button_style',
                $this->itemInheritedString($item, 'link_style', $defaultStyle),
                'variant',
            );
            $itemColor = $this->itemInheritedString($item, 'button_color', $defaultColor, 'link_color');
            $itemSize = $this->itemInheritedString($item, 'size', $defaultSize);

            $title = $this->itemString($item, 'title', $this->itemString($item, 'content'));

            $link = $item['link'] ?? null;
            $url = SuluDataNormalizer::resolveUrl($link);
            $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
            $iconAlign = 'right' === $this->itemString($item, 'icon_align') ? 'right' : 'left';

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
                'link_title' => $this->itemNullableString($item, 'link_title'),
                'classes' => $classes,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'button_style' => $itemStyle,
                'button_color' => $itemColor,
                'variant' => $itemStyle,
                'link_color' => $itemColor,
                'size' => $itemSize,
                'bare' => true,
            ];
        }

        if ([] === $result && null !== $singleLink && null !== $singleButtonText && '' !== $singleButtonText) {
            $linkUrl = SuluDataNormalizer::resolveUrl($singleLink);
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
                    'variant' => $defaultStyle,
                    'link_color' => $defaultColor,
                    'size' => $defaultSize,
                    'bare' => true,
                ];
            }
        }

        return $result;
    }
}
