<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Button', template: '@SuluPrelineBlocks/components/Button.html.twig')]
final class Button
{
    /**
     * Multiple items for button group.
     *
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public mixed $items = [];

    /**
     * Group layout style: 'gap' (separated) or 'connected' (segmented Preline button group).
     */
    public ?string $groupStyle = null;
    public ?string $group_style = null;

    /**
     * Group orientation: 'horizontal' or 'vertical'.
     */
    public ?string $orientation = null;

    /**
     * Group alignment: 'start', 'center', 'end', 'justify'.
     */
    public ?string $align = null;

    /**
     * Gap size between buttons in 'gap' mode: 'none', 'xs', 'sm', 'md', 'lg'.
     */
    public ?string $gap = null;

    /**
     * Single button properties (for standalone use or backwards compatibility).
     */
    public ?string $title = '';
    public ?string $content = '';
    public mixed $link = '#';
    public ?string $linkTitle = null;
    public ?string $link_title = null;
    public mixed $icon = null;
    public ?string $iconAlign = null;
    public ?string $icon_align = null;

    public ?string $variant = null;
    public ?string $size = null;
    public ?string $linkColor = null;
    public ?string $link_color = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?bool $fullWidth = null;
    public ?bool $full_width = null;

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
        if (null !== $this->variant) {
            $resolved['variant'] = $this->variant;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== ($this->linkColor ?? $this->link_color)) {
            $resolved['link_color'] = $this->linkColor ?? $this->link_color;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->fullWidth ?? $this->full_width)) {
            $resolved['full_width'] = $this->fullWidth ?? $this->full_width;
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
        if (null !== ($this->groupStyle ?? $this->group_style)) {
            $resolved['group_style'] = $this->groupStyle ?? $this->group_style;
        }
        if (null !== $this->orientation) {
            $resolved['orientation'] = $this->orientation;
        }
        if (null !== $this->align) {
            $resolved['align'] = $this->align;
        }
        if (null !== $this->gap) {
            $resolved['gap'] = $this->gap;
        }

        return $resolved;
    }

    public function isGroup(): bool
    {
        return [] !== $this->items;
    }

    public function getGroupStyle(): string
    {
        $props = $this->getResolvedProps();

        return (isset($props['group_style']) && \is_string($props['group_style']))
            ? $props['group_style']
            : ($this->groupStyle ?? ($this->group_style ?? 'gap'));
    }

    public function getOrientation(): string
    {
        $props = $this->getResolvedProps();

        return (isset($props['orientation']) && \is_string($props['orientation']))
            ? $props['orientation']
            : ($this->orientation ?? 'horizontal');
    }

    public function getAlign(): string
    {
        $props = $this->getResolvedProps();

        return (isset($props['align']) && \is_string($props['align']))
            ? $props['align']
            : ($this->align ?? 'start');
    }

    public function getGap(): string
    {
        $props = $this->getResolvedProps();

        return (isset($props['gap']) && \is_string($props['gap']))
            ? $props['gap']
            : ($this->gap ?? 'md');
    }

    public function getGroupWrapperClasses(): string
    {
        $props = $this->getResolvedProps();
        $isConnected = 'connected' === $this->getGroupStyle();
        $isVertical = 'vertical' === $this->getOrientation();
        $align = $this->getAlign();
        $gap = $this->getGap();

        $classes = [];

        if ($isConnected) {
            $classes[] = $isVertical ? 'inline-flex flex-col rounded-lg shadow-2xs' : 'inline-flex rounded-lg shadow-2xs';
        } else {
            $gapClasses = match ($gap) {
                'none' => 'gap-0',
                'xs' => 'gap-1',
                'sm' => 'gap-1.5 sm:gap-2',
                'lg' => 'gap-3 sm:gap-4',
                default => 'gap-2 sm:gap-3',
            };

            $classes[] = $isVertical ? \sprintf('inline-flex flex-col %s', $gapClasses) : \sprintf('flex flex-wrap items-center %s', $gapClasses);
        }

        $alignClasses = match ($align) {
            'center' => 'justify-center mx-auto text-center',
            'end', 'right' => 'justify-end ml-auto text-right',
            'justify' => 'w-full *:flex-1',
            default => 'justify-start text-left',
        };

        $classes[] = $alignClasses;

        return ElementTransform::wrapperClasses($props, \trim(\implode(' ', $classes)));
    }

    /**
     * @return list<array{
     *     title: string,
     *     url: string,
     *     link_title: ?string,
     *     icon: ?string,
     *     icon_align: string,
     *     classes: string,
     *     id: ?string
     * }>
     */
    public function getNormalizedItems(): array
    {
        if ([] === $this->items) {
            return [];
        }

        $isConnected = 'connected' === $this->getGroupStyle();
        $isVertical = 'vertical' === $this->getOrientation();
        $groupProps = $this->getResolvedProps();

        /** @var list<array<string, mixed>> $rawItems */
        $rawItems = \array_values(\array_filter($this->items, '\is_array'));
        $total = \count($rawItems);
        $result = [];

        foreach ($rawItems as $index => $item) {
            $title = isset($item['title']) && \is_string($item['title']) && '' !== $item['title']
                ? $item['title']
                : (isset($item['content']) && \is_string($item['content']) ? $item['content'] : '');

            /** @var mixed $itemLink */
            $itemLink = $item['link'] ?? '#';
            $url = PropsResolver::resolveUrl($itemLink);

            $linkTitle = isset($item['link_title']) && \is_string($item['link_title']) && '' !== $item['link_title']
                ? $item['link_title']
                : (isset($item['linkTitle']) && \is_string($item['linkTitle']) ? $item['linkTitle'] : null);

            $icon = PropsResolver::resolveIcon($item['icon'] ?? null);
            $iconAlign = isset($item['icon_align']) && \is_string($item['icon_align']) ? $item['icon_align'] : 'left';

            // Resolve item style and color (override or inherit from group props)
            $itemStyle = isset($item['button_style']) && \is_string($item['button_style']) && 'inherit' !== $item['button_style']
                ? $item['button_style']
                : ($item['variant'] ?? ($groupProps['variant'] ?? 'solid'));

            $itemColor = isset($item['button_color']) && \is_string($item['button_color']) && 'inherit' !== $item['button_color']
                ? $item['button_color']
                : ($item['link_color'] ?? ($groupProps['link_color'] ?? 'primary'));

            $itemProps = \array_merge($groupProps, $item, [
                'variant' => $itemStyle,
                'link_color' => $itemColor,
            ]);

            $buttonClasses = PropsResolver::buttonClasses($itemProps);

            if ($isConnected) {
                // Remove rounded-lg and apply connected segmented classes
                $buttonClasses = \str_replace('rounded-lg', '', $buttonClasses);

                if ($total > 1) {
                    if ($isVertical) {
                        $segmentClass = match (true) {
                            0 === $index => 'rounded-t-lg rounded-b-none focus:z-10',
                            $index === $total - 1 => 'rounded-b-lg rounded-t-none -mt-px focus:z-10',
                            default => 'rounded-none -mt-px focus:z-10',
                        };
                    } else {
                        $segmentClass = match (true) {
                            0 === $index => 'rounded-s-lg rounded-e-none first:ms-0 focus:z-10',
                            $index === $total - 1 => 'rounded-e-lg rounded-s-none -ms-px focus:z-10',
                            default => 'rounded-none -ms-px focus:z-10',
                        };
                    }
                    $buttonClasses .= ' ' . $segmentClass;
                } else {
                    $buttonClasses .= ' rounded-lg';
                }
            }

            $id = isset($item['id']) && \is_string($item['id']) && '' !== $item['id'] ? $item['id'] : null;

            $result[] = [
                'title' => $title,
                'url' => $url,
                'link_title' => $linkTitle,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'classes' => \trim(\preg_replace('/\s+/', ' ', $buttonClasses) ?? $buttonClasses),
                'id' => $id,
            ];
        }

        return $result;
    }

    public function getTitle(): string
    {
        if (null !== $this->title && '' !== $this->title) {
            return $this->title;
        }

        return $this->content ?? '';
    }

    public function getUrl(): string
    {
        return PropsResolver::resolveUrl($this->link);
    }

    public function getResolvedIcon(): ?string
    {
        return PropsResolver::resolveIcon($this->icon ?? ($this->props['icon'] ?? null));
    }

    public function getIconAlign(): string
    {
        $align = $this->iconAlign ?? ($this->icon_align ?? ($this->props['icon_align'] ?? 'left'));

        return \is_string($align) ? $align : 'left';
    }

    public function getClasses(): string
    {
        return PropsResolver::buttonClasses($this->getResolvedProps());
    }

    public function getWrapperClasses(): string
    {
        return ElementTransform::wrapperClasses($this->getResolvedProps(), $this->getClasses());
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getLinkTitle(): ?string
    {
        return $this->linkTitle ?? $this->link_title;
    }
}
