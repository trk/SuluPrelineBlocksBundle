<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Image', template: '@SuluPrelineBlocks/components/Image.html.twig')]
final class Image extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'aspect_ratio' => 'auto',
            'object_fit' => 'cover',
            'rounded' => 'lg',
            'bare' => false,
            'loading' => 'lazy',
        ];
    }

    protected function dataKeys(): array
    {
        return [
            'image',
            'hover_image',
            'caption',
            'alt',
            'link',
            'image_url',
            'hover_image_url',
            'image_srcset',
            'hover_image_srcset',
            'image_focus_style',
            'hover_image_focus_style',
        ];
    }

    protected function capabilities(): array
    {
        return [
            'bare' => fn (): bool => $this->boolProp('bare', false),
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getImageUrl();
    }

    public function isBare(): bool
    {
        return $this->is('bare');
    }

    public function getImageSrcSet(string $key = 'image'): ?string
    {
        return parent::getImageSrcSet($key)
            ?? $this->nullableString('image_srcset')
            ?? $this->nullableStringProp('image_srcset')
            ?? $this->nullableStringProp('srcset');
    }

    public function getHoverImageSrcSet(): ?string
    {
        return parent::getHoverImageSrcSet()
            ?? $this->nullableString('hover_image_srcset')
            ?? $this->nullableStringProp('hover_image_srcset')
            ?? $this->nullableStringProp('hover_srcset');
    }

    public function getImageFocusPointStyle(string $key = 'image'): ?string
    {
        return parent::getImageFocusPointStyle($key)
            ?? $this->nullableString('image_focus_style')
            ?? $this->nullableStringProp('image_focus_style')
            ?? $this->nullableStringProp('focus_style');
    }

    public function getHoverImageFocusPointStyle(): ?string
    {
        return parent::getHoverImageFocusPointStyle()
            ?? $this->nullableString('hover_image_focus_style')
            ?? $this->nullableStringProp('hover_image_focus_style')
            ?? $this->nullableStringProp('hover_focus_style');
    }

    /**
     * Intrinsic `width`/`height` attributes: provided by the parent (`dimensions`) for bare images,
     * otherwise derived from this image's own aspect ratio and layout slot.
     *
     * @return array{width: int, height: int}|null
     */
    public function getDimensions(): ?array
    {
        $provided = $this->prop('dimensions');
        if (\is_array($provided) && \is_int($provided['width'] ?? null) && \is_int($provided['height'] ?? null)
            && $provided['width'] > 0 && $provided['height'] > 0) {
            return ['width' => $provided['width'], 'height' => $provided['height']];
        }

        return $this->getIntrinsicImageDimensions();
    }

    public function getLoading(): string
    {
        return $this->getImageLoading();
    }

    public function getFetchPriority(): ?string
    {
        return $this->getImageFetchPriority();
    }

    public function getHoverEffectClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::IMAGE_HOVER_EFFECTS,
            $this->stringProp('hover_effect', 'none'),
        );
    }

    public function getMediaContainerClasses(): string
    {
        $hover = $this->stringProp('hover_effect', 'none');
        $containerHover = 'shadow' === $hover ? $this->getHoverEffectClasses() : '';

        return $this->classes(
            'group relative block overflow-hidden',
            $this->getRoundedClasses('lg'),
            $this->getShadowClasses(),
            $this->getMediaAspectRatioClass('auto'),
            $containerHover,
        );
    }

    public function getImgClasses(): string
    {
        $explicit = $this->nullableStringProp('img_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes(
            'block w-full',
            null !== $this->getAspectRatioValue() ? 'h-full' : 'h-auto',
            $this->getObjectFitClass(),
            $this->getImageHoverTransformClasses(),
            null !== $this->getHoverImageUrl() ? 'transition-all duration-500 group-hover:opacity-0' : null,
        );
    }

    public function getHoverImgClasses(): string
    {
        $explicit = $this->nullableStringProp('hover_img_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes(
            'absolute inset-0 w-full h-full opacity-0 transition-all duration-500 group-hover:opacity-100',
            $this->getObjectFitClass(),
            $this->getImageHoverTransformClasses(),
        );
    }

    /**
     * Image-level hover transform (`shadow` is applied to the container instead).
     */
    private function getImageHoverTransformClasses(): string
    {
        $hover = $this->stringProp('hover_effect', 'none');

        return 'none' !== $hover && 'shadow' !== $hover ? $this->getHoverEffectClasses() : '';
    }

    public function getUrl(): ?string
    {
        return $this->getLinkUrl('link');
    }

    public function getCaption(): ?string
    {
        return $this->nullableString('caption') ?? $this->nullableStringProp('caption');
    }

    public function getAlt(): string
    {
        $explicit = $this->nullableString('alt') ?? $this->nullableStringProp('alt');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->extractMediaAlt($this->get('image')) ?? ($this->getCaption() ?? '');
    }

    public function getHoverAlt(): string
    {
        return $this->extractMediaAlt($this->get('hover_image')) ?? $this->getAlt();
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes('block w-full', $extraBase, ...$moreParts);
    }
}
