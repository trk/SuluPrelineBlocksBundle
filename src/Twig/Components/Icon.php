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
                'secondary' => 'text-secondary-600 dark:text-secondary-400',
                'dark' => 'text-gray-900 dark:text-white',
                'muted' => 'text-gray-400 dark:text-neutral-500',
                'success' => 'text-emerald-600 dark:text-emerald-400',
                'danger' => 'text-red-600 dark:text-red-400',
                'white' => 'text-white',
                default => 'text-primary-600 dark:text-primary-400',
            };

            $hover = $this->hasLink() ? 'hover:opacity-80 transition-opacity' : '';

            return \sprintf('inline-flex items-center justify-center %s %s', $textColor, $hover);
        }

        if ('circle_solid' === $style) {
            $bg = match ($color) {
                'secondary' => 'bg-secondary-600 text-white hover:bg-secondary-700',
                'dark' => 'bg-gray-900 text-white hover:bg-black dark:bg-white dark:text-gray-900',
                'muted' => 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-neutral-300 hover:bg-gray-200',
                'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
                'danger' => 'bg-red-600 text-white hover:bg-red-700',
                'white' => 'bg-white text-gray-900 hover:bg-gray-100 shadow-sm',
                default => 'bg-primary-600 text-white hover:bg-primary-700',
            };

            return \sprintf('inline-flex items-center justify-center rounded-full shadow-xs transition-colors %s %s', $dimensionClass, $bg);
        }

        if ('circle_outline' === $style) {
            $border = match ($color) {
                'secondary' => 'border-secondary-600 text-secondary-600 hover:bg-secondary-50 dark:hover:bg-secondary-950/30',
                'dark' => 'border-gray-900 text-gray-900 hover:bg-gray-100 dark:border-white dark:text-white',
                'muted' => 'border-gray-300 text-gray-500 hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-400',
                'success' => 'border-emerald-600 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30',
                'danger' => 'border-red-600 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30',
                'white' => 'border-white text-white hover:bg-white/10',
                default => 'border-primary-600 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-950/30',
            };

            return \sprintf('inline-flex items-center justify-center rounded-full border transition-colors %s %s', $dimensionClass, $border);
        }

        // square_soft
        $soft = match ($color) {
            'secondary' => 'bg-secondary-50 text-secondary-600 dark:bg-secondary-950/40 dark:text-secondary-400',
            'dark' => 'bg-gray-100 text-gray-900 dark:bg-neutral-800 dark:text-white',
            'muted' => 'bg-gray-50 text-gray-500 dark:bg-neutral-800/60 dark:text-neutral-400',
            'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
            'danger' => 'bg-red-50 text-red-600 dark:bg-red-950/40 dark:text-red-400',
            'white' => 'bg-white/20 text-white backdrop-blur-xs',
            default => 'bg-primary-50 text-primary-600 dark:bg-primary-950/40 dark:text-primary-400',
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
