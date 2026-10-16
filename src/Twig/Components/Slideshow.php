<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Slideshow', template: '@SuluPrelineBlocks/components/Slideshow.html.twig')]
final class Slideshow extends AbstractPrelineComponent
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

    public function getHeightClass(): string
    {
        return match ($this->stringProp('height', 'medium')) {
            'full' => 'min-h-screen',
            'large' => 'min-h-[550px] sm:min-h-[650px]',
            'small' => 'min-h-[300px] sm:min-h-[380px]',
            default => 'min-h-[420px] sm:min-h-[500px]',
        };
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
        return match ($this->stringProp('content_align', 'center')) {
            'left' => 'text-left items-start',
            'right' => 'text-right items-end',
            default => 'text-center items-center',
        };
    }

    public function getCarouselConfigJson(): string
    {
        $config = [
            'loadingClasses' => 'opacity-0',
            'isAutoPlay' => $this->isAutoplay(),
            'speed' => $this->getInterval(),
            'isInfiniteLoop' => $this->isLoop(),
        ];

        return (string) \json_encode($config, \JSON_THROW_ON_ERROR);
    }

    public function getMarginClass(): string
    {
        $top = match ($this->stringProp('margin_top', 'none')) {
            'sm' => 'mt-4',
            'md' => 'mt-8',
            'lg' => 'mt-12',
            'xl' => 'mt-16',
            default => '',
        };

        $bottom = match ($this->stringProp('margin_bottom', 'none')) {
            'sm' => 'mb-4',
            'md' => 'mb-8',
            'lg' => 'mb-12',
            'xl' => 'mb-16',
            default => '',
        };

        return \trim($top . ' ' . $bottom);
    }
}
