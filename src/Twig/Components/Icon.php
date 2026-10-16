<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Icon', template: '@SuluPrelineBlocks/components/Icon.html.twig')]
final class Icon extends AbstractPrelineComponent
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
        $url = $this->getLinkUrl('link');

        return ('#' === $url || null === $url || '' === $url) ? null : $url;
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

    public function getAlignClasses(): string
    {
        $align = $this->nullableStringProp('align');
        if (null === $align || 'left' === $align) {
            $align = $this->stringProp('text_align', $align ?? 'left');
        }

        return match ($align) {
            'center' => 'justify-center text-center',
            'right', 'end' => 'justify-end text-end',
            default => 'justify-start text-start',
        };
    }

    public function getIconClasses(): string
    {
        $iconClass = $this->getResolvedIcon();
        if (null === $iconClass) {
            return '';
        }

        $sizeClass = match ($this->getIconSize()) {
            'sm' => 'text-xl',
            'lg' => 'text-4xl',
            'xl' => 'text-5xl',
            '2xl' => 'text-6xl',
            default => 'text-2xl sm:text-3xl',
        };

        return \sprintf('%s %s', $iconClass, $sizeClass);
    }

    public function getContainerClasses(): string
    {
        $style = $this->getIconStyle();
        $size = $this->getIconSize();
        $color = $this->stringProp('icon_color', 'primary');

        $dimensionClass = match ($size) {
            'sm' => 'w-10 h-10',
            'lg' => 'w-16 h-16',
            'xl' => 'w-20 h-20',
            '2xl' => 'w-24 h-24',
            default => 'w-12 h-12',
        };

        if ('plain' === $style) {
            $textColor = match ($color) {
                'secondary' => 'text-secondary',
                'dark' => 'text-foreground',
                'muted' => 'text-muted-foreground',
                'success' => 'text-emerald-600 dark:text-emerald-400',
                'danger' => 'text-destructive',
                'white' => 'text-white',
                default => 'text-primary',
            };

            $hover = $this->hasLink() ? 'hover:opacity-80 transition-opacity' : '';

            return \sprintf('inline-flex items-center justify-center %s %s', $textColor, $hover);
        }

        if ('circle_solid' === $style) {
            $bg = match ($color) {
                'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover',
                'dark' => 'bg-inverse text-foreground-inverse hover:opacity-90',
                'muted' => 'bg-surface text-surface-foreground hover:bg-surface-hover',
                'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
                'danger' => 'bg-destructive text-destructive-foreground hover:bg-destructive-hover',
                'white' => 'bg-layer text-layer-foreground hover:bg-layer-hover shadow-sm',
                default => 'bg-primary text-primary-foreground hover:bg-primary-hover',
            };

            return \sprintf('inline-flex items-center justify-center rounded-full shadow-xs transition-colors %s %s', $dimensionClass, $bg);
        }

        if ('circle_outline' === $style) {
            $border = match ($color) {
                'secondary' => 'border-secondary-line text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30',
                'dark' => 'border-line-8 text-foreground hover:bg-muted',
                'muted' => 'border-border text-muted-foreground-1 hover:bg-muted',
                'success' => 'border-emerald-600 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30',
                'danger' => 'border-destructive text-destructive hover:bg-red-50 dark:hover:bg-red-950/30',
                'white' => 'border-white text-white hover:bg-white/10',
                default => 'border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30',
            };

            return \sprintf('inline-flex items-center justify-center rounded-full border transition-colors %s %s', $dimensionClass, $border);
        }

        $soft = match ($color) {
            'secondary' => 'bg-secondary-50 text-secondary dark:bg-secondary-950/40',
            'dark' => 'bg-surface text-surface-foreground',
            'muted' => 'bg-muted text-muted-foreground-1',
            'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
            'danger' => 'bg-red-50 text-destructive dark:bg-red-950/40',
            'white' => 'bg-white/20 text-white backdrop-blur-xs',
            default => 'bg-primary-50 text-primary dark:bg-primary-950/40',
        };

        $hover = $this->hasLink() ? 'hover:scale-105 transition-transform' : '';

        return \sprintf('inline-flex items-center justify-center rounded-xl %s %s %s', $dimensionClass, $soft, $hover);
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim('flex items-center ' . $this->getAlignClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
