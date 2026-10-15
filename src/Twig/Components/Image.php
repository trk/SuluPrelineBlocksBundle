<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Image', template: '@SuluPrelineBlocks/components/Image.html.twig')]
final class Image
{
    public mixed $image = null;
    public ?string $imageUrl = null;
    public ?string $image_url = null;
    public mixed $hoverImage = null;
    public mixed $hover_image = null;
    public ?string $hoverImageUrl = null;
    public ?string $hover_image_url = null;
    public mixed $link = null;
    public ?string $alt = null;

    public ?string $aspectRatio = null;
    public ?string $aspect_ratio = null;
    public ?string $objectFit = null;
    public ?string $object_fit = null;
    public int|string|null $aspectRatioWidth = null;
    public int|string|null $aspect_ratio_width = null;
    public int|string|null $aspectRatioHeight = null;
    public int|string|null $aspect_ratio_height = null;
    public ?string $rounded = null;
    public ?string $caption = null;

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
        if (null !== ($this->aspectRatio ?? $this->aspect_ratio)) {
            $resolved['aspect_ratio'] = $this->aspectRatio ?? $this->aspect_ratio;
        }
        if (null !== ($this->objectFit ?? $this->object_fit)) {
            $resolved['object_fit'] = $this->objectFit ?? $this->object_fit;
        }
        if (null !== ($this->aspectRatioWidth ?? $this->aspect_ratio_width)) {
            $resolved['aspect_ratio_width'] = $this->aspectRatioWidth ?? $this->aspect_ratio_width;
        }
        if (null !== ($this->aspectRatioHeight ?? $this->aspect_ratio_height)) {
            $resolved['aspect_ratio_height'] = $this->aspectRatioHeight ?? $this->aspect_ratio_height;
        }
        if (null !== $this->rounded) {
            $resolved['rounded'] = $this->rounded;
        }
        if (null !== $this->caption) {
            $resolved['caption'] = $this->caption;
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

    public function getImageUrl(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveImageUrl($this->image ?? $props['image'] ?? null)
            ?? ($this->imageUrl ?? $this->image_url ?? (isset($props['image_url']) && \is_string($props['image_url']) ? $props['image_url'] : null));
    }

    public function getImageSrcSet(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveSrcSet($this->image ?? $props['image'] ?? null);
    }

    public function getImageSizes(): string
    {
        return '(min-width: 1280px) 1024px, 100vw';
    }

    public function getImageFocusPointStyle(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveFocusPointStyle($this->image ?? $props['image'] ?? null);
    }

    public function getHoverImageUrl(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveImageUrl($this->hoverImage ?? $this->hover_image ?? $props['hover_image'] ?? null)
            ?? ($this->hoverImageUrl ?? $this->hover_image_url ?? (isset($props['hover_image_url']) && \is_string($props['hover_image_url']) ? $props['hover_image_url'] : null));
    }

    public function getHoverImageSrcSet(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveSrcSet($this->hoverImage ?? $this->hover_image ?? $props['hover_image'] ?? null);
    }

    public function getHoverImageFocusPointStyle(): ?string
    {
        $props = $this->getResolvedProps();

        return PropsResolver::resolveFocusPointStyle($this->hoverImage ?? $this->hover_image ?? $props['hover_image'] ?? null);
    }

    public function getObjectFitClass(): string
    {
        $props = $this->getResolvedProps();
        $fit = $props['object_fit'] ?? ($this->objectFit ?? $this->object_fit ?? 'cover');

        return PropsResolver::resolveObjectFit($fit);
    }

    public function getMediaAspectRatioStyle(): string
    {
        $props = $this->getResolvedProps();
        $aspect = $props['aspect_ratio'] ?? null;

        if ('manual' === $aspect) {
            $w = $props['aspect_ratio_width'] ?? ($this->aspectRatioWidth ?? $this->aspect_ratio_width ?? null);
            $h = $props['aspect_ratio_height'] ?? ($this->aspectRatioHeight ?? $this->aspect_ratio_height ?? null);

            $widthVal = \is_numeric($w) ? (float) $w : 0.0;
            $heightVal = \is_numeric($h) ? (float) $h : 0.0;

            if ($widthVal > 0.0 && $heightVal > 0.0) {
                return \sprintf('aspect-ratio: %s / %s;', $widthVal, $heightVal);
            }
        }

        return '';
    }

    public function getUrl(): ?string
    {
        if (null === $this->link || '' === $this->link) {
            return null;
        }

        return PropsResolver::resolveUrl($this->link);
    }

    public function getAspectRatio(): string
    {
        $props = $this->getResolvedProps();
        $ratio = isset($props['aspect_ratio']) && \is_string($props['aspect_ratio']) ? $props['aspect_ratio'] : 'auto';

        return match ($ratio) {
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
        $props = $this->getResolvedProps();
        $rounded = isset($props['rounded']) && \is_string($props['rounded']) ? $props['rounded'] : 'lg';

        return match ($rounded) {
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
        $props = $this->getResolvedProps();

        return isset($props['caption']) && \is_string($props['caption']) && '' !== $props['caption']
            ? $props['caption']
            : null;
    }

    public function getAlt(): string
    {
        if (null !== $this->alt && '' !== \trim($this->alt)) {
            return \trim($this->alt);
        }

        $props = $this->getResolvedProps();
        if (isset($props['alt']) && \is_string($props['alt']) && '' !== \trim($props['alt'])) {
            return \trim($props['alt']);
        }

        return $this->extractMediaAlt($this->image ?? ($props['image'] ?? null)) ?? ($this->getCaption() ?? '');
    }

    public function getHoverAlt(): string
    {
        $props = $this->getResolvedProps();

        return $this->extractMediaAlt($this->hoverImage ?? $this->hover_image ?? ($props['hover_image'] ?? null))
            ?? $this->getAlt();
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

    public function getWrapperClasses(): string
    {
        $props = $this->getResolvedProps();
        $hasMaxWidth = isset($props['max_width']) && '' !== $props['max_width'] && 'none' !== $props['max_width'];
        $base = $hasMaxWidth ? 'block w-full mx-auto' : 'block w-full max-w-5xl mx-auto';

        return ElementTransform::wrapperClasses($props, $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
