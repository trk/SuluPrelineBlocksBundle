<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Video', template: '@SuluPrelineBlocks/components/Video.html.twig')]
final class Video
{
    public mixed $video = null;
    public mixed $link = null;
    public mixed $image = null;
    public mixed $icon = null;

    public ?string $aspectRatio = null;
    public ?string $aspect_ratio = null;
    public bool|string|null $autoplay = null;
    public ?bool $controls = null;
    public ?bool $loop = null;
    public ?bool $muted = null;
    public ?string $shadow = null;
    public ?string $boxShadow = null;
    public ?string $box_shadow = null;

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
        if (null !== $this->autoplay) {
            $resolved['autoplay'] = $this->autoplay;
        }
        if (null !== $this->controls) {
            $resolved['controls'] = $this->controls;
        }
        if (null !== $this->loop) {
            $resolved['loop'] = $this->loop;
        }
        if (null !== $this->muted) {
            $resolved['muted'] = $this->muted;
        }
        if (null !== ($this->shadow ?? $this->boxShadow ?? $this->box_shadow)) {
            $resolved['box_shadow'] = $this->shadow ?? $this->boxShadow ?? $this->box_shadow;
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

    public function getVideoUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->video);
    }

    public function getPosterUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getPosterSrcSet(): ?string
    {
        return PropsResolver::resolveSrcSet($this->image);
    }

    public function getPosterFocusPointStyle(): ?string
    {
        return PropsResolver::resolveFocusPointStyle($this->image);
    }

    public function getPosterAlt(): string
    {
        if (\is_array($this->image)) {
            $alt = $this->image['description'] ?? ($this->image['title'] ?? null);
            if (\is_string($alt) && '' !== \trim($alt)) {
                return \trim($alt);
            }
        } elseif (\is_object($this->image)) {
            if (\method_exists($this->image, 'getDescription')) {
                $desc = $this->image->getDescription();
                if (\is_string($desc) && '' !== \trim($desc)) {
                    return \trim($desc);
                }
            }
            if (\method_exists($this->image, 'getTitle')) {
                $title = $this->image->getTitle();
                if (\is_string($title) && '' !== \trim($title)) {
                    return \trim($title);
                }
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
        $ratio = isset($props['aspect_ratio']) && \is_string($props['aspect_ratio']) ? $props['aspect_ratio'] : '16/9';

        return match ($ratio) {
            '4/3', '4x3' => 'aspect-4/3',
            '9/16', '9x16' => 'aspect-[9/16]',
            '1/1', '1x1' => 'aspect-square',
            '21/9', '21x9' => 'aspect-[21/9]',
            default => 'aspect-video',
        };
    }

    public function getRounded(): string
    {
        $props = $this->getResolvedProps();
        $rounded = isset($props['rounded']) && \is_string($props['rounded']) ? $props['rounded'] : 'xl';

        return match ($rounded) {
            'none' => 'rounded-none',
            'sm' => 'rounded-sm',
            'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'xl' => 'rounded-xl',
            '2xl' => 'rounded-2xl',
            'full' => 'rounded-full',
            default => 'rounded-xl',
        };
    }

    public function isAutoplay(): bool
    {
        $props = $this->getResolvedProps();
        $autoplay = $props['autoplay'] ?? null;

        return true === $autoplay || 'true' === $autoplay || '1' === $autoplay || 1 === $autoplay || 'on' === $autoplay || 'inview' === $autoplay || 'hover' === $autoplay;
    }

    public function getAutoplayMode(): ?string
    {
        $props = $this->getResolvedProps();
        $autoplay = $props['autoplay'] ?? null;
        if (\is_string($autoplay) && \in_array($autoplay, ['inview', 'hover'], true)) {
            return $autoplay;
        }

        return null;
    }

    public function isControls(): bool
    {
        $props = $this->getResolvedProps();

        return !isset($props['controls']) || (false !== $props['controls'] && 'false' !== $props['controls'] && 0 !== $props['controls']);
    }

    public function isLoop(): bool
    {
        $props = $this->getResolvedProps();

        return isset($props['loop']) && (true === $props['loop'] || 'true' === $props['loop'] || 1 === $props['loop']);
    }

    public function isMuted(): bool
    {
        $props = $this->getResolvedProps();
        if (isset($props['muted'])) {
            return true === $props['muted'] || 'true' === $props['muted'] || 1 === $props['muted'];
        }

        return $this->isAutoplay();
    }

    public function getShadowClasses(): string
    {
        $props = $this->getResolvedProps();
        $shadow = $props['box_shadow'] ?? $props['shadow'] ?? 'none';
        if (!\is_string($shadow)) {
            return '';
        }

        return match ($shadow) {
            'sm' => 'shadow-sm',
            'md', 'medium' => 'shadow-md',
            'lg', 'large' => 'shadow-lg',
            'xl', 'xlarge' => 'shadow-xl',
            '2xl' => 'shadow-2xl',
            default => '',
        };
    }

    public function getWrapperClasses(): string
    {
        $shadow = $this->getShadowClasses();
        $shadowPart = '' !== $shadow ? ' ' . $shadow : '';
        $base = 'relative w-full overflow-hidden ' . $this->getRounded() . $shadowPart . ' bg-black ' . $this->getAspectRatio();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getIconClass(): ?string
    {
        return PropsResolver::resolveIcon($this->icon);
    }
}
