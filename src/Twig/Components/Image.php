<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Image', template: '@SuluPrelineBlocks/components/Image.html.twig')]
final class Image extends AbstractPrelineComponent
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

    public function getImageUrl(string $key = 'image'): ?string
    {
        return parent::getImageUrl($key) ?? $this->nullableString('image_url');
    }

    public function getImageSizes(string $default = '(min-width: 1280px) 1024px, 100vw'): string
    {
        return $default;
    }

    public function getHoverImageUrl(): ?string
    {
        return parent::getImageUrl('hover_image') ?? $this->nullableString('hover_image_url');
    }

    public function getHoverImageSrcSet(): ?string
    {
        return $this->getImageSrcSet('hover_image');
    }

    public function getHoverImageFocusPointStyle(): ?string
    {
        return $this->getImageFocusPointStyle('hover_image');
    }

    public function getObjectFitClass(): string
    {
        return PropsResolver::resolveObjectFit($this->stringProp('object_fit', 'cover'));
    }

    public function getMediaAspectRatioStyle(): string
    {
        if ('manual' === $this->stringProp('aspect_ratio', 'auto')) {
            $widthVal = $this->floatProp('aspect_ratio_width', 0.0);
            $heightVal = $this->floatProp('aspect_ratio_height', 0.0);

            if ($widthVal > 0.0 && $heightVal > 0.0) {
                return \sprintf('aspect-ratio: %s / %s;', $widthVal, $heightVal);
            }
        }

        return '';
    }

    public function getUrl(): ?string
    {
        $link = $this->get('link');
        if (null === $link || '' === $link) {
            return null;
        }

        return PropsResolver::resolveUrl($link);
    }

    public function getAspectRatio(): string
    {
        return match ($this->stringProp('aspect_ratio', 'auto')) {
            '16/9', '16x9', '16:9' => 'aspect-video',
            '4/3', '4x3', '4:3' => 'aspect-4/3',
            '1/1', '1x1', '1:1', 'square' => 'aspect-square',
            '9/16', '9x16', '9:16' => 'aspect-[9/16]',
            '21/9', '21x9', '21:9' => 'aspect-[21/9]',
            'manual' => 'aspect-auto',
            default => 'aspect-auto',
        };
    }

    public function getRounded(): string
    {
        return match ($this->stringProp('rounded', 'lg')) {
            'none' => 'rounded-none',
            'sm' => 'rounded-sm',
            'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'xl' => 'rounded-xl',
            '2xl' => 'rounded-2xl',
            'full' => 'rounded-full',
            default => 'rounded-lg',
        };
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

    private function extractMediaAlt(mixed $media): ?string
    {
        if (\is_array($media)) {
            $alt = $media['description'] ?? ($media['title'] ?? null);
            if (\is_string($alt) && '' !== \trim($alt)) {
                return \trim($alt);
            }
        } elseif (\is_object($media)) {
            if (\method_exists($media, 'getDescription')) {
                $desc = $media->getDescription();
                if (\is_string($desc) && '' !== \trim($desc)) {
                    return \trim($desc);
                }
            }
            if (\method_exists($media, 'getTitle')) {
                $title = $media->getTitle();
                if (\is_string($title) && '' !== \trim($title)) {
                    return \trim($title);
                }
            }
        }

        return null;
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $maxWidth = $this->nullableStringProp('max_width');
        $hasMaxWidth = null !== $maxWidth && 'none' !== $maxWidth;
        $base = \trim(($hasMaxWidth ? 'block w-full mx-auto' : 'block w-full max-w-5xl mx-auto') . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
