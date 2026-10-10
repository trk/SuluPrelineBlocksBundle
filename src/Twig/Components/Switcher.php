<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Switcher', template: '@SuluPrelineBlocks/components/Switcher.html.twig')]
final class Switcher
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

    public ?string $navPosition = null;
    public ?string $nav_position = null;
    public ?string $navStyle = null;
    public ?string $nav_style = null;
    public ?string $alignment = null;

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

        if (null !== ($this->navPosition ?? $this->nav_position)) {
            $resolved['nav_position'] = $this->navPosition ?? $this->nav_position;
        }
        if (null !== ($this->navStyle ?? $this->nav_style)) {
            $resolved['nav_style'] = $this->navStyle ?? $this->nav_style;
        }
        if (null !== $this->alignment) {
            $resolved['alignment'] = $this->alignment;
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

    public function getNavPosition(): string
    {
        $props = $this->getResolvedProps();
        $pos = $props['nav_position'] ?? 'top';

        return match ($pos) {
            'left' => 'left',
            'right' => 'right',
            'bottom' => 'bottom',
            default => 'top',
        };
    }

    public function getNavStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['nav_style'] ?? 'pills';

        return match ($style) {
            'underline' => 'underline',
            'bordered' => 'bordered',
            'segmented' => 'segmented',
            default => 'pills',
        };
    }

    public function getAlignment(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['alignment'] ?? 'start';

        return match ($align) {
            'center' => 'center',
            'end' => 'end',
            'full' => 'full',
            default => 'start',
        };
    }

    public function isVertical(): bool
    {
        $pos = $this->getNavPosition();

        return 'left' === $pos || 'right' === $pos;
    }

    public function getTabNavClasses(): string
    {
        $classes = ['flex'];
        $isVert = $this->isVertical();
        $style = $this->getNavStyle();
        $align = $this->getAlignment();

        if ($isVert) {
            $classes[] = 'flex-col space-y-1.5';
        } else {
            $classes[] = 'flex-wrap';
            $classes[] = match ($align) {
                'center' => 'justify-center',
                'end' => 'justify-end',
                'full' => 'grid grid-flow-col auto-cols-fr text-center',
                default => 'justify-start',
            };

            if ($style === 'segmented') {
                $classes[] = 'p-1.5 bg-gray-100 rounded-xl dark:bg-neutral-800 gap-1';
            } elseif ($style === 'underline') {
                $classes[] = 'gap-x-6 border-b border-gray-200 dark:border-neutral-700';
            } elseif ($style === 'bordered') {
                $classes[] = 'gap-2';
            } else {
                // pills
                $classes[] = 'gap-2';
            }
        }

        return \implode(' ', $classes);
    }

    public function getTabButtonClasses(): string
    {
        $classes = [
            'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-medium transition-colors focus:outline-hidden disabled:opacity-50 disabled:pointer-events-none',
        ];
        $style = $this->getNavStyle();
        $isVert = $this->isVertical();
        $align = $this->getAlignment();

        if ($align === 'full' && !$isVert) {
            $classes[] = 'justify-center';
        }

        if ($style === 'pills') {
            $classes[] = 'rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800';
            $classes[] = 'hs-tab-active:bg-primary-600 hs-tab-active:text-white dark:hs-tab-active:bg-primary-600 dark:hs-tab-active:text-white';
        } elseif ($style === 'segmented') {
            $classes[] = 'rounded-lg text-gray-600 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-white';
            $classes[] = 'hs-tab-active:bg-white hs-tab-active:text-gray-900 hs-tab-active:shadow-2xs hs-tab-active:font-semibold dark:hs-tab-active:bg-neutral-700 dark:hs-tab-active:text-white';
        } elseif ($style === 'underline') {
            $classes[] = 'border-b-2 -mb-px border-transparent text-gray-500 hover:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200';
            $classes[] = 'hs-tab-active:border-primary-600 hs-tab-active:text-primary-600 hs-tab-active:font-semibold dark:hs-tab-active:border-primary-500 dark:hs-tab-active:text-primary-400';
        } elseif ($style === 'bordered') {
            $classes[] = 'rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800';
            $classes[] = 'hs-tab-active:border-primary-600 hs-tab-active:bg-primary-50 hs-tab-active:text-primary-700 dark:hs-tab-active:border-primary-500 dark:hs-tab-active:bg-primary-950/40 dark:hs-tab-active:text-primary-300';
        }

        return \implode(' ', $classes);
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
