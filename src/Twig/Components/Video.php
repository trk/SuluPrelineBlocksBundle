<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Video', template: '@SuluPrelineBlocks/components/Video.html.twig')]
final class Video extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'aspect_ratio' => '16/9',
            'object_fit' => 'cover',
            'rounded' => 'xl',
            'autoplay' => false,
            'controls' => true,
            'loop' => false,
            'bare' => false,
        ];
    }

    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return [
            'video',
            'hover_video',
            'image',
            'link',
            'icon',
            'video_url',
            'hover_video_url',
        ];
    }

    protected function capabilities(): array
    {
        return [
            'bare' => fn (): bool => $this->boolProp('bare', false),
            'autoplay' => fn (): bool => $this->isAutoplay(),
            'controls' => fn (): bool => $this->isControls(),
            'loop' => fn (): bool => $this->isLoop(),
            'muted' => fn (): bool => $this->isMuted(),
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getVideoUrl() || null !== $this->getUrl() || null !== $this->getPosterUrl();
    }

    public function isBare(): bool
    {
        return $this->is('bare');
    }

    public function getPreload(): ?string
    {
        return $this->nullableStringProp('preload');
    }

    public function getVideoClasses(): string
    {
        $explicit = $this->nullableStringProp('video_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes('w-full h-full', $this->getObjectFitClass());
    }

    public function getHoverVideoClasses(): string
    {
        $explicit = $this->nullableStringProp('hover_video_class');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->classes(
            'absolute inset-0 w-full h-full opacity-0 group-hover:opacity-100 transition-all duration-500 ease-out group-hover:scale-105',
            $this->getObjectFitClass(),
        );
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
        return $this->extractMediaAlt($this->get('image')) ?? '';
    }

    public function getUrl(): ?string
    {
        $link = $this->get('link');
        if (null === $link || '' === $link) {
            return null;
        }

        return PropsResolver::resolveUrl($link);
    }

    public function getAspectRatio(string $default = '16/9', string $key = 'aspect_ratio'): string
    {
        return parent::getAspectRatio($default, $key);
    }

    public function getRounded(string $default = 'xl', string $key = 'rounded'): string
    {
        return parent::getRounded($default, $key);
    }

    public function isAutoplay(): bool
    {
        $autoplay = $this->prop('autoplay');

        return $this->oneOf($autoplay, [true, 'true', '1', 1, 'on', 'inview', 'hover']);
    }

    public function getAutoplayMode(): ?string
    {
        $autoplay = $this->nullableStringProp('autoplay');

        return null !== $autoplay && $this->oneOf($autoplay, ['inview', 'hover']) ? $autoplay : null;
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

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses(
            'relative w-full overflow-hidden',
            $this->getRounded(),
            $this->getShadowClasses(),
            'bg-black',
            $this->getAspectRatio(),
            $extraBase,
            ...$moreParts,
        );
    }
}
