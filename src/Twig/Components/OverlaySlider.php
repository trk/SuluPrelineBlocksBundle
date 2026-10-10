<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('OverlaySlider', template: '@SuluPrelineBlocks/components/OverlaySlider.html.twig')]
final class OverlaySlider
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

    public ?bool $autoplay = null;
    public ?int $interval = null;
    public ?bool $loop = null;
    public ?string $arrows = null;
    public ?string $dots = null;
    public ?string $columns = null;
    public ?string $gap = null;

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
        if (null !== $this->dots) {
            $resolved['dots'] = $this->dots;
        }
        if (null !== $this->columns) {
            $resolved['columns'] = $this->columns;
        }
        if (null !== $this->gap) {
            $resolved['gap'] = $this->gap;
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
                $normalized[] = $itemArr;
            }
        }

        return $normalized;
    }

    public function isAutoplay(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['autoplay'] ?? false);
    }

    public function getInterval(): int
    {
        $props = $this->getResolvedProps();
        $interval = $props['interval'] ?? 4000;

        return \is_numeric($interval) ? (int) $interval : 4000;
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

    public function getDots(): string
    {
        $props = $this->getResolvedProps();
        $dots = $props['dots'] ?? 'bottom';

        return \is_string($dots) ? $dots : 'bottom';
    }

    public function getColumns(): string
    {
        $props = $this->getResolvedProps();
        $columns = $props['columns'] ?? '3';

        return \is_string($columns) || \is_int($columns) ? (string) $columns : '3';
    }

    public function getSlideWidthClass(): string
    {
        return match ($this->getColumns()) {
            '1' => 'w-full',
            '2' => 'w-full sm:w-1/2',
            '4' => 'w-full sm:w-1/2 lg:w-1/4',
            default => 'w-full sm:w-1/2 lg:w-1/3',
        };
    }

    public function getGapClass(): string
    {
        $props = $this->getResolvedProps();
        $gap = $props['gap'] ?? 'md';

        return match ($gap) {
            'none' => 'px-0',
            'sm' => 'px-1.5',
            'lg' => 'px-4',
            default => 'px-2.5',
        };
    }

    public function getCarouselConfigJson(): string
    {
        $cols = match ($this->getColumns()) {
            '1' => ['xs' => 1, 'sm' => 1, 'md' => 1, 'lg' => 1],
            '2' => ['xs' => 1, 'sm' => 2, 'md' => 2, 'lg' => 2],
            '4' => ['xs' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4],
            default => ['xs' => 1, 'sm' => 2, 'md' => 3, 'lg' => 3],
        };

        $config = [
            'loadingClasses' => 'opacity-0',
            'isAutoPlay' => $this->isAutoplay(),
            'speed' => $this->getInterval(),
            'isInfiniteLoop' => $this->isLoop(),
            'slidesQty' => $cols,
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
