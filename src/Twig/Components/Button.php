<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Button', template: '@SuluPrelineBlocks/components/Button.html.twig')]
final class Button extends PrelineComponent
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
        return $this->isGroup() || '' !== $this->getTitle() || null !== $this->getResolvedIcon();
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

        $layoutClass = $isConnected
            ? ($isVertical ? 'inline-flex flex-col rounded-lg shadow-2xs' : 'inline-flex rounded-lg shadow-2xs')
            : $this->classes(
                $isVertical ? 'inline-flex flex-col' : 'flex flex-wrap items-center',
                $this->getGapClasses('md', 'button_group', 'gap'),
            );

        $alignClasses = match ($align) {
            'center' => 'justify-center mx-auto text-center',
            'end', 'right' => 'justify-end ml-auto text-right',
            'justify' => 'w-full *:flex-1',
            default => 'justify-start text-left',
        };

        return parent::getWrapperClasses($layoutClass, $alignClasses);
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
            $title = $this->itemString($item, 'title', $this->itemString($item, 'content'));
            $url = PropsResolver::resolveUrl($item['link'] ?? '#');
            $linkTitle = $this->itemNullableString($item, 'link_title');
            $icon = PropsResolver::resolveIcon($item['icon'] ?? null);
            $iconAlign = $this->itemString($item, 'icon_align', 'left');

            $itemStyle = $this->itemInheritedString(
                $item,
                'button_style',
                $this->stringProp('variant', 'solid'),
                'variant',
            );

            $itemColor = $this->itemInheritedString(
                $item,
                'button_color',
                $this->stringProp('link_color', 'primary'),
                'link_color',
            );

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
                    $buttonClasses = $this->classes($buttonClasses, $segmentClass);
                } else {
                    $buttonClasses = $this->classes($buttonClasses, 'rounded-lg');
                }
            }

            $result[] = [
                'title' => $title,
                'url' => $url,
                'link_title' => $linkTitle,
                'icon' => $icon,
                'icon_align' => $iconAlign,
                'classes' => $this->classes($buttonClasses),
                'id' => $this->itemNullableString($item, 'id'),
            ];
        }

        return $result;
    }

    public function getTitle(): string
    {
        return $this->string('title', $this->string('content'));
    }

    public function getUrl(): string
    {
        return PropsResolver::resolveUrl($this->get('link', '#'));
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

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses($this->getClasses(), $extraBase, ...$moreParts);
    }
}
