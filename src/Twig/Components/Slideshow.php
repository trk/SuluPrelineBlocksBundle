<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Slideshow', template: '@SuluPrelineBlocks/components/Slideshow.html.twig')]
final class Slideshow extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'height' => 'medium',
            'autoplay' => true,
            'interval' => 5000,
            'loop' => true,
            'arrows' => 'inside',
            'indicators' => 'dots',
            'content_align' => 'center',
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'media' => true,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items('items') as $item) {
            $normalized[] = $this->normalizeItemMediaAndLinks($item);
        }

        return $normalized;
    }

    public function isAutoplay(): bool
    {
        return $this->boolProp('autoplay', true);
    }

    public function getInterval(): int
    {
        return $this->intProp('interval', 5000);
    }

    public function isLoop(): bool
    {
        return $this->boolProp('loop', true);
    }

    public function getArrows(): string
    {
        return $this->stringProp('arrows', 'inside');
    }

    public function getIndicators(): string
    {
        return $this->stringProp('indicators', 'dots');
    }

    public function getContentAlign(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::CONTENT_ALIGN,
            $this->stringProp('content_align', 'center'),
            PrelineClassMap::CONTENT_ALIGN['center'],
        );
    }

    public function getCarouselConfigJson(): string
    {
        return $this->resolveSliderConfigJson(
            $this->isAutoplay(),
            $this->getInterval(),
            $this->isLoop(),
        );
    }

    public function getOverlayTintClasses(string $style): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::SLIDESHOW_OVERLAY_TINTS,
            $style,
            PrelineClassMap::SLIDESHOW_OVERLAY_TINTS['dark_medium'],
        );
    }

    public function getSlideContentClasses(): string
    {
        return $this->classes(PrelineClassMap::SLIDESHOW_CONTENT, $this->getContentAlign());
    }

    public function getTextMeasureClass(): string
    {
        return PrelineClassMap::SLIDESHOW_TEXT_MEASURE;
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes('relative w-full', $extraBase, ...$moreParts);
    }
}
