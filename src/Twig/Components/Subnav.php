<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Subnav', template: '@SuluPrelineBlocks/components/Subnav.html.twig')]
final class Subnav
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

    public ?string $style = null;
    public ?string $alignment = null;
    public ?string $size = null;

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

        if (null !== $this->style) {
            $resolved['style'] = $this->style;
        }
        if (null !== $this->alignment) {
            $resolved['alignment'] = $this->alignment;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
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

                if (isset($itemArr['link'])) {
                    $itemArr['link_href'] = PropsResolver::resolveUrl($itemArr['link']);
                }

                $normalized[] = $itemArr;
            }
        }

        return $normalized;
    }

    public function getStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['style'] ?? 'pills';

        return match ($style) {
            'underline' => 'underline',
            'segmented' => 'segmented',
            'divider' => 'divider',
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
            'between' => 'between',
            default => 'start',
        };
    }

    public function getSize(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['size'] ?? 'md';

        return match ($size) {
            'sm' => 'sm',
            'lg' => 'lg',
            default => 'md',
        };
    }

    public function getNavClasses(): string
    {
        $classes = ['flex items-center flex-wrap overflow-x-auto'];
        $align = $this->getAlignment();
        $style = $this->getStyle();

        $classes[] = match ($align) {
            'center' => 'justify-center',
            'end' => 'justify-end',
            'between' => 'justify-between',
            default => 'justify-start',
        };

        if ('segmented' === $style) {
            $classes[] = 'p-1.5 bg-gray-100 rounded-xl dark:bg-neutral-800 gap-1';
        } elseif ('underline' === $style) {
            $classes[] = 'gap-x-6 border-b border-gray-200 dark:border-neutral-700';
        } elseif ('divider' === $style) {
            $classes[] = 'gap-x-4 divide-x divide-gray-200 dark:divide-neutral-700';
        } else {
            // pills
            $classes[] = 'gap-2';
        }

        return \implode(' ', $classes);
    }

    public function getItemClasses(bool $isActive): string
    {
        $classes = ['inline-flex items-center gap-x-2 font-medium transition-colors focus:outline-hidden whitespace-nowrap'];
        $style = $this->getStyle();
        $size = $this->getSize();

        $classes[] = match ($size) {
            'sm' => 'text-xs py-1 px-2.5',
            'lg' => 'text-base py-3 px-5',
            default => 'text-sm py-2 px-3.5',
        };

        if ('pills' === $style) {
            $classes[] = 'rounded-lg';
            if ($isActive) {
                $classes[] = 'bg-primary-600 text-white shadow-2xs dark:bg-primary-500';
            } else {
                $classes[] = 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-neutral-400 dark:hover:text-white dark:hover:bg-neutral-800';
            }
        } elseif ('segmented' === $style) {
            $classes[] = 'rounded-lg';
            if ($isActive) {
                $classes[] = 'bg-white text-gray-900 shadow-2xs font-semibold dark:bg-neutral-700 dark:text-white';
            } else {
                $classes[] = 'text-gray-600 hover:text-gray-900 dark:text-neutral-400 dark:hover:text-white';
            }
        } elseif ('underline' === $style) {
            $classes[] = 'border-b-2 -mb-px px-1';
            if ($isActive) {
                $classes[] = 'border-primary-600 text-primary-600 font-semibold dark:border-primary-500 dark:text-primary-400';
            } else {
                $classes[] = 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-300 dark:text-neutral-400 dark:hover:text-neutral-200 dark:hover:border-neutral-600';
            }
        } elseif ('divider' === $style) {
            $classes[] = 'first:ps-0 ps-4';
            if ($isActive) {
                $classes[] = 'text-primary-600 font-semibold dark:text-primary-400';
            } else {
                $classes[] = 'text-gray-600 hover:text-primary-600 dark:text-neutral-400 dark:hover:text-primary-400';
            }
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
