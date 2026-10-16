<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Icon', template: '@SuluPrelineBlocks/components/Icon.html.twig')]
final class Icon extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'icon_style' => 'plain',
            'icon_size' => 'md',
            'icon_color' => 'primary',
            'align' => 'left',
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getResolvedIcon();
    }

    public function getResolvedIcon(): ?string
    {
        return $this->getIconClass('icon');
    }

    public function getUrl(): ?string
    {
        return $this->getLinkUrl('link');
    }

    public function hasLink(): bool
    {
        return null !== $this->getUrl();
    }

    public function getIconStyle(): string
    {
        return $this->enumProp('icon_style', ['plain', 'circle_solid', 'circle_outline', 'square_soft'], 'plain');
    }

    public function getIconSize(): string
    {
        return $this->stringProp('icon_size', 'md');
    }

    public function getAlignClasses(string $default = 'left', string $key = 'align'): string
    {
        return $this->getAlignWithTextClasses($default, $key);
    }

    public function getIconClasses(): string
    {
        $iconClass = $this->getResolvedIcon();
        if (null === $iconClass) {
            return '';
        }

        $sizeClass = PrelineClassMap::resolve(
            PrelineClassMap::ICON_FONT_SIZES,
            $this->getIconSize(),
            PrelineClassMap::ICON_FONT_SIZES['md'],
        );

        return $this->classes($iconClass, $sizeClass);
    }

    public function getContainerClasses(): string
    {
        $style = $this->getIconStyle();
        $size = $this->getIconSize();
        $color = $this->stringProp('icon_color', 'primary');

        $dimensionClass = PrelineClassMap::resolve(
            PrelineClassMap::ICON_DIMENSIONS,
            $size,
            PrelineClassMap::ICON_DIMENSIONS['md'],
        );

        if ('plain' === $style) {
            $textColor = PrelineClassMap::resolve(
                PrelineClassMap::ICON_PLAIN_COLORS,
                $color,
                PrelineClassMap::ICON_PLAIN_COLORS['primary'],
            );
            $hover = $this->hasLink() ? 'hover:opacity-80 transition-opacity' : '';

            return $this->classes('inline-flex items-center justify-center', $textColor, $hover);
        }

        if ('circle_solid' === $style) {
            $bg = PrelineClassMap::resolve(
                PrelineClassMap::ICON_CIRCLE_SOLID_COLORS,
                $color,
                PrelineClassMap::ICON_CIRCLE_SOLID_COLORS['primary'],
            );

            return $this->classes('inline-flex items-center justify-center rounded-full shadow-xs transition-colors', $dimensionClass, $bg);
        }

        if ('circle_outline' === $style) {
            $border = PrelineClassMap::resolve(
                PrelineClassMap::ICON_CIRCLE_OUTLINE_COLORS,
                $color,
                PrelineClassMap::ICON_CIRCLE_OUTLINE_COLORS['primary'],
            );

            return $this->classes('inline-flex items-center justify-center rounded-full border transition-colors', $dimensionClass, $border);
        }

        $soft = PrelineClassMap::resolve(
            PrelineClassMap::ICON_SOFT_COLORS,
            $color,
            PrelineClassMap::ICON_SOFT_COLORS['primary'],
        );
        $hover = $this->hasLink() ? 'hover:scale-105 transition-transform' : '';

        return $this->classes('inline-flex items-center justify-center rounded-xl', $dimensionClass, $soft, $hover);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses('flex items-center', $this->getAlignClasses(), $extraBase, ...$moreParts);
    }
}
