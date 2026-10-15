<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Icon', template: '@SuluPrelineBlocks/components/Icon.html.twig')]
final class Icon
{
    public mixed $icon = null;
    public mixed $link = null;

    public ?string $iconStyle = null;
    public ?string $icon_style = null;
    public ?string $iconSize = null;
    public ?string $icon_size = null;
    public ?string $iconColor = null;
    public ?string $icon_color = null;
    public ?string $align = null;

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

        if (null !== ($this->iconStyle ?? $this->icon_style)) {
            $resolved['icon_style'] = $this->iconStyle ?? $this->icon_style;
        }
        if (null !== ($this->iconSize ?? $this->icon_size)) {
            $resolved['icon_size'] = $this->iconSize ?? $this->icon_size;
        }
        if (null !== ($this->iconColor ?? $this->icon_color)) {
            $resolved['icon_color'] = $this->iconColor ?? $this->icon_color;
        }
        if (null !== $this->align) {
            $resolved['align'] = $this->align;
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

    public function getResolvedIcon(): ?string
    {
        return PropsResolver::resolveIcon($this->icon ?? ($this->props['icon'] ?? null));
    }

    public function getUrl(): ?string
    {
        if (null === $this->link) {
            return null;
        }

        $url = PropsResolver::resolveUrl($this->link);

        return ('#' === $url || '' === $url) ? null : $url;
    }

    public function hasLink(): bool
    {
        return null !== $this->getUrl();
    }

    public function getIconStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['icon_style'] ?? 'plain';

        return match ($style) {
            'circle_solid' => 'circle_solid',
            'circle_outline' => 'circle_outline',
            'square_soft' => 'square_soft',
            default => 'plain',
        };
    }

    public function getIconSize(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['icon_size'] ?? 'md';

        return \is_scalar($size) ? (string) $size : 'md';
    }

    public function getAlignClasses(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['align'] ?? ($props['text_align'] ?? 'left');

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

        $size = $this->getIconSize();
        $sizeClass = match ($size) {
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
        $props = $this->getResolvedProps();
        $color = $props['icon_color'] ?? 'primary';

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

        // square_soft
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

    public function getWrapperClasses(): string
    {
        $base = 'flex items-center ' . $this->getAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
