<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Slideshow', template: '@SuluPrelineBlocks/components/Slideshow.html.twig')]
final class Slideshow
{
    public ?string $title = null;

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public ?string $height = null;
    public ?bool $autoplay = null;
    public ?int $interval = null;
    public ?bool $loop = null;
    public ?string $arrows = null;
    public ?string $indicators = null;
    public ?string $contentAlign = null;
    public ?string $content_align = null;

    public ?string $marginTop = null;
    public ?string $margin_top = null;
    public ?string $marginBottom = null;
    public ?string $margin_bottom = null;
    public ?string $id = null;
    public ?string $customClass = null;
    public ?string $custom_class = null;

    /**
     * @return array<string, mixed>
     */
    public function getResolvedProps(): array
    {
        $resolved = $this->props;
        if (isset($resolved['props']) && \is_array($resolved['props'])) {
            /** @var array<string, mixed> $nested */
            $nested = $resolved['props'];
            $resolved = \array_merge($nested, $resolved);
            unset($resolved['props']);
        }

        if (null !== $this->height) {
            $resolved['height'] = $this->height;
        }
        if (null !== $this->autoplay) {
            $resolved['autoplay'] = $this->autoplay;
        }
        if (null !== $this->interval) {
            $resolved['interval'] = $this->interval;
        }
        if (null !== $this->loop) {
            $resolved['loop'] = $this->loop;
        }
        if (null !== $this->arrows) {
            $resolved['arrows'] = $this->arrows;
        }
        if (null !== $this->indicators) {
            $resolved['indicators'] = $this->indicators;
        }
        if (null !== ($this->contentAlign ?? $this->content_align)) {
            $resolved['content_align'] = $this->contentAlign ?? $this->content_align;
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

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $itemArr */
                $itemArr = $item;

                if (isset($itemArr['image'])) {
                    if (\is_array($itemArr['image']) && isset($itemArr['image']['url']) && \is_string($itemArr['image']['url'])) {
                        $itemArr['image_url'] = $itemArr['image']['url'];
                    } elseif (\is_string($itemArr['image'])) {
                        $itemArr['image_url'] = $itemArr['image'];
                    }
                }

                if (isset($itemArr['link'])) {
                    if (\is_array($itemArr['link']) && isset($itemArr['link']['url']) && \is_string($itemArr['link']['url'])) {
                        $itemArr['link_href'] = $itemArr['link']['url'];
                    } elseif (\is_string($itemArr['link'])) {
                        $itemArr['link_href'] = $itemArr['link'];
                    }
                }

                $normalized[] = $itemArr;
            }
        }

        return $normalized;
    }

    public function getHeightClass(): string
    {
        $props = $this->getResolvedProps();
        $height = $props['height'] ?? 'medium';

        return match ($height) {
            'full' => 'min-h-screen',
            'large' => 'min-h-[550px] sm:min-h-[650px]',
            'small' => 'min-h-[300px] sm:min-h-[380px]',
            default => 'min-h-[420px] sm:min-h-[500px]',
        };
    }

    public function isAutoplay(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['autoplay'] ?? true);
    }

    public function getInterval(): int
    {
        $props = $this->getResolvedProps();
        $interval = $props['interval'] ?? 5000;

        return \is_numeric($interval) ? (int) $interval : 5000;
    }

    public function isLoop(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['loop'] ?? true);
    }

    public function getArrows(): string
    {
        $props = $this->getResolvedProps();
        $arrows = $props['arrows'] ?? 'inside';

        return \is_string($arrows) ? $arrows : 'inside';
    }

    public function getIndicators(): string
    {
        $props = $this->getResolvedProps();
        $indicators = $props['indicators'] ?? 'dots';

        return \is_string($indicators) ? $indicators : 'dots';
    }

    public function getContentAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['content_align'] ?? 'center';

        return match ($align) {
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
        $props = $this->getResolvedProps();
        $classes = [];

        $top = $props['margin_top'] ?? null;
        if (\is_string($top) && '' !== $top) {
            $classes[] = match ($top) {
                'sm' => 'mt-4',
                'md' => 'mt-8',
                'lg' => 'mt-12',
                'xl' => 'mt-16',
                default => '',
            };
        }

        $bottom = $props['margin_bottom'] ?? null;
        if (\is_string($bottom) && '' !== $bottom) {
            $classes[] = match ($bottom) {
                'sm' => 'mb-4',
                'md' => 'mb-8',
                'lg' => 'mb-12',
                'xl' => 'mb-16',
                default => '',
            };
        }

        return \trim(\implode(' ', \array_filter($classes)));
    }

    public function getCustomId(): ?string
    {
        $props = $this->getResolvedProps();
        $id = $props['id'] ?? null;

        return \is_string($id) && '' !== \trim($id) ? \trim($id) : null;
    }

    public function getCustomClass(): string
    {
        $props = $this->getResolvedProps();
        $class = $props['custom_class'] ?? null;

        return \is_string($class) ? \trim($class) : '';
    }
}
