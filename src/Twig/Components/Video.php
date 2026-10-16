<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Video', template: '@SuluPrelineBlocks/components/Video.html.twig')]
final class Video extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'aspect_ratio' => '16/9',
            'rounded' => 'xl',
            'autoplay' => false,
            'controls' => true,
            'loop' => false,
        ];
    }

    protected function capabilities(): array
    {
        return [
            'autoplay' => fn (): bool => $this->isAutoplay(),
            'controls' => fn (): bool => $this->isControls(),
            'loop' => fn (): bool => $this->isLoop(),
            'muted' => fn (): bool => $this->isMuted(),
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getVideoUrl() || null !== $this->getUrl();
    }

    public function getPosterUrl(): ?string
    {
        return $this->getImageUrl('image');
    }

    public function getPosterSrcSet(): ?string
    {
        return $this->getImageSrcSet('image');
    }

    public function getPosterFocusPointStyle(): ?string
    {
        return $this->getImageFocusPointStyle('image');
    }

    public function getPosterAlt(): string
    {
        $media = $this->get('image');
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
        return match ($this->stringProp('aspect_ratio', '16/9')) {
            '4/3', '4x3' => 'aspect-4/3',
            '9/16', '9x16' => 'aspect-[9/16]',
            '1/1', '1x1' => 'aspect-square',
            '21/9', '21x9' => 'aspect-[21/9]',
            default => 'aspect-video',
        };
    }

    public function getRounded(): string
    {
        return match ($this->stringProp('rounded', 'xl')) {
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
        $autoplay = $this->prop('autoplay');

        return true === $autoplay
            || 'true' === $autoplay
            || '1' === $autoplay
            || 1 === $autoplay
            || 'on' === $autoplay
            || 'inview' === $autoplay
            || 'hover' === $autoplay;
    }

    public function getAutoplayMode(): ?string
    {
        $autoplay = $this->prop('autoplay');
        if (\is_string($autoplay) && \in_array($autoplay, ['inview', 'hover'], true)) {
            return $autoplay;
        }

        return null;
    }

    public function isControls(): bool
    {
        return $this->boolProp('controls', true);
    }

    public function isLoop(): bool
    {
        return $this->boolProp('loop', false);
    }

    public function isMuted(): bool
    {
        $props = $this->props();
        if (\array_key_exists('muted', $props) && null !== $props['muted']) {
            return $this->boolProp('muted', false);
        }

        return $this->isAutoplay();
    }

    public function getShadowClasses(): string
    {
        $shadow = $this->nullableStringProp('box_shadow') ?? $this->stringProp('shadow', 'none');

        return match ($shadow) {
            'sm' => 'shadow-sm',
            'md', 'medium' => 'shadow-md',
            'lg', 'large' => 'shadow-lg',
            'xl', 'xlarge' => 'shadow-xl',
            '2xl' => 'shadow-2xl',
            default => '',
        };
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $shadow = $this->getShadowClasses();
        $shadowPart = '' !== $shadow ? ' ' . $shadow : '';
        $base = \trim('relative w-full overflow-hidden ' . $this->getRounded() . $shadowPart . ' bg-black ' . $this->getAspectRatio() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
