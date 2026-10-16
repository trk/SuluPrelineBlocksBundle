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
        ];
    }

    protected function dataKeys(): array
    {
        return ['image_url', 'hover_image_url'];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getImageUrl();
    }

    public function getImageSizes(string $default = '(min-width: 1280px) 1024px, 100vw'): string
    {
        return $default;
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
