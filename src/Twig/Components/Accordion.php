<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Accordion', template: '@SuluPrelineBlocks/components/Accordion.html.twig')]
final class Accordion
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
    public ?string $iconStyle = null;
    public ?string $icon_style = null;
    public ?bool $multiple = null;
    public ?bool $collapsible = null;
    public ?string $titleSize = null;
    public ?string $title_size = null;
    public ?string $imageAlign = null;
    public ?string $image_align = null;

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
        if (null !== ($this->iconStyle ?? $this->icon_style)) {
            $resolved['icon_style'] = $this->iconStyle ?? $this->icon_style;
        }
        if (null !== $this->multiple) {
            $resolved['multiple'] = $this->multiple;
        }
        if (null !== $this->collapsible) {
            $resolved['collapsible'] = $this->collapsible;
        }
        if (null !== ($this->titleSize ?? $this->title_size)) {
            $resolved['title_size'] = $this->titleSize ?? $this->title_size;
        }
        if (null !== ($this->imageAlign ?? $this->image_align)) {
            $resolved['image_align'] = $this->imageAlign ?? $this->image_align;
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
                    $itemArr['image_url'] = PropsResolver::resolveImageUrl($itemArr['image']);
                    $itemArr['image_srcset'] = PropsResolver::resolveSrcSet($itemArr['image']);
                    $itemArr['image_focus_style'] = PropsResolver::resolveFocusPointStyle($itemArr['image']);
                }

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
        $style = $props['style'] ?? 'default';

        return match ($style) {
            'bordered' => 'bordered',
            'soft' => 'soft',
            default => 'default',
        };
    }

    public function getIconStyle(): string
    {
        $props = $this->getResolvedProps();
        $icon = $props['icon_style'] ?? 'chevron';

        return match ($icon) {
            'plus' => 'plus',
            'none' => 'none',
            default => 'chevron',
        };
    }

    public function isMultiple(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['multiple'] ?? false);
    }

    public function isCollapsible(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['collapsible'] ?? true);
    }

    public function getTitleSizeClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['title_size'] ?? 'base';

        return match ($size) {
            'sm' => 'text-sm font-medium',
            'lg' => 'text-lg font-semibold',
            'xl' => 'text-xl font-bold',
            default => 'text-base font-semibold',
        };
    }

    public function getImageAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['image_align'] ?? 'top';

        return match ($align) {
            'bottom' => 'bottom',
            'left' => 'left',
            'right' => 'right',
            default => 'top',
        };
    }

    public function getContainerClasses(): string
    {
        $style = $this->getStyle();

        return match ($style) {
            'bordered' => 'space-y-3',
            'soft' => 'space-y-3',
            default => 'divide-y divide-gray-200 dark:divide-neutral-700',
        };
    }

    public function getItemClasses(): string
    {
        $style = $this->getStyle();

        return match ($style) {
            'bordered' => 'hs-accordion border border-gray-200 rounded-xl p-4 dark:border-neutral-700 dark:bg-neutral-900',
            'soft' => 'hs-accordion bg-gray-100 rounded-xl p-4 dark:bg-neutral-800',
            default => 'hs-accordion pt-4 pb-3',
        };
    }

    public function getToggleClasses(): string
    {
        return 'hs-accordion-toggle hs-accordion-active:text-primary-600 dark:hs-accordion-active:text-primary-500 inline-flex items-center justify-between gap-x-3 w-full text-start text-gray-800 hover:text-gray-500 rounded-lg disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-200 dark:hover:text-neutral-400 focus:outline-hidden';
    }

    public function getContentWrapperClasses(): string
    {
        $style = $this->getStyle();

        return match ($style) {
            'bordered', 'soft' => 'pt-3',
            default => 'pt-2 pb-1',
        };
    }

    public function getMarginClass(): string
    {
        $props = $this->getResolvedProps();

        $top = match ($props['margin_top'] ?? 'none') {
            'sm' => 'mt-4',
            'md' => 'mt-8',
            'lg' => 'mt-12',
            'xl' => 'mt-16',
            default => '',
        };

        $bottom = match ($props['margin_bottom'] ?? 'none') {
            'sm' => 'mb-4',
            'md' => 'mb-8',
            'lg' => 'mb-12',
            'xl' => 'mb-16',
            default => '',
        };

        return \trim($top . ' ' . $bottom);
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
        $class = $props['custom_class'] ?? '';

        return \is_string($class) ? \trim($class) : '';
    }
}
