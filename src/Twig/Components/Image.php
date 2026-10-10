<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Image', template: '@SuluPrelineBlocks/components/Image.html.twig')]
final class Image
{
    public mixed $image = null;
    public mixed $link = null;
    public ?string $alt = null;

    public ?string $aspectRatio = null;
    public ?string $aspect_ratio = null;
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
        return PropsResolver::resolveImageUrl($this->image);
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
            '16/9', '16x9' => 'aspect-video',
            '4/3', '4x3' => 'aspect-4/3',
            '1/1', '1x1' => 'aspect-square',
            '9/16', '9x16' => 'aspect-[9/16]',
            '21/9', '21x9' => 'aspect-[21/9]',
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

    public function getWrapperClasses(): string
    {
        return ElementTransform::wrapperClasses($this->getResolvedProps(), 'inline-block max-w-full');
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
