<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Button', template: '@SuluPrelineBlocks/components/Button.html.twig')]
final class Button extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'group_style' => 'gap',
            'orientation' => 'horizontal',
            'align' => 'start',
            'gap' => 'md',
            'variant' => 'solid',
            'size' => 'md',
            'link_color' => 'primary',
            'icon_align' => 'left',
            'full_width' => false,
        ];
    }

    protected function capabilities(): array
    {
        return [
            'group' => fn (): bool => [] !== $this->items('items'),
            'connected' => fn (): bool => 'connected' === $this->getGroupStyle(),
            'vertical' => fn (): bool => 'vertical' === $this->getOrientation(),
        ];
    }

    public function shouldRender(): bool
    {
        return $this->isGroup() || '' !== \trim($this->getTitle()) || null !== $this->getResolvedIcon();
    }

    public function isGroup(): bool
    {
        return $this->is('group');
    }

    public function getGroupStyle(): string
    {
        return $this->stringProp('group_style', 'gap');
    }

    public function getOrientation(): string
    {
        return $this->stringProp('orientation', 'horizontal');
    }

    public function getAlign(): string
    {
        return $this->stringProp('align', 'start');
    }

    public function getGap(): string
    {
        return $this->stringProp('gap', 'md');
    }

    public function getGroupWrapperClasses(): string
    {
        $isConnected = $this->is('connected');
        $isVertical = $this->is('vertical');
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

        return parent::getWrapperClasses(\trim(\implode(' ', $classes)));
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
        $rawItems = $this->items('items');
        if ([] === $rawItems) {
            return [];
        }

        $isConnected = $this->is('connected');
        $isVertical = $this->is('vertical');
        $groupProps = $this->props();

        $total = \count($rawItems);
        $result = [];

        foreach ($rawItems as $index => $item) {
            $title = isset($item['title']) && \is_string($item['title']) && '' !== $item['title']
                ? $item['title']
                : (isset($item['content']) && \is_string($item['content']) ? $item['content'] : '');

            $itemLink = $item['link'] ?? '#';
            $url = PropsResolver::resolveUrl($itemLink);

            $linkTitle = isset($item['link_title']) && \is_string($item['link_title']) && '' !== $item['link_title']
                ? $item['link_title']
                : (isset($item['linkTitle']) && \is_string($item['linkTitle']) ? $item['linkTitle'] : null);

            $icon = PropsResolver::resolveIcon($item['icon'] ?? null);
            $iconAlign = isset($item['icon_align']) && \is_string($item['icon_align']) ? $item['icon_align'] : 'left';

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
        $title = $this->string('title', '');
        if ('' !== $title) {
            return $title;
        }

        return $this->string('content', '');
    }

    public function getUrl(): string
    {
        $link = $this->get('link', '#');

        return PropsResolver::resolveUrl($link);
    }

    public function getResolvedIcon(): ?string
    {
        return $this->getIconClass('icon');
    }

    public function getIconAlign(): string
    {
        return $this->stringProp('icon_align', 'left');
    }

    public function getClasses(): string
    {
        return PropsResolver::buttonClasses($this->props());
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim($this->getClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
