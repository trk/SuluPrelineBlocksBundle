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

    public function getImageSizes(string $default = '(min-width: 1280px) 1024px, 100vw'): string
    {
        return $this->nullableStringProp('sizes') ?? $default;
    }

    public function getLoading(): string
    {
        return $this->stringProp('loading', 'lazy');
    }

    public function getImgClasses(): string
    {
        $explicit = $this->nullableStringProp('img_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes(
            'w-full h-full transition-all duration-500 group-hover:scale-[1.01]',
            $this->getObjectFitClass(),
        );
    }

    public function getHoverImgClasses(): string
    {
        $explicit = $this->nullableStringProp('hover_img_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes(
            'absolute inset-0 w-full h-full transition-all duration-500 opacity-0 group-hover:opacity-100 group-hover:scale-[1.01]',
            $this->getObjectFitClass(),
        );
    }

    public function getUrl(): ?string
    {
        $link = $this->get('link');
        if (null === $link || '' === $link) {
            return null;
        }

        return PropsResolver::resolveUrl($link);
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

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        $maxWidth = $this->nullableStringProp('max_width');
        $hasMaxWidth = null !== $maxWidth && 'none' !== $maxWidth;

        return parent::getWrapperClasses(
            $hasMaxWidth ? 'block w-full mx-auto' : 'block w-full max-w-5xl mx-auto',
            $extraBase,
            ...$moreParts,
        );
    }
}
