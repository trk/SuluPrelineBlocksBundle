<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Divider', template: '@SuluPrelineBlocks/components/Divider.html.twig')]
final class Divider
{
    public mixed $icon = null;

    public ?string $dividerStyle = null;
    public ?string $divider_style = null;
    public ?string $dividerColor = null;
    public ?string $divider_color = null;
    public ?string $dividerThickness = null;
    public ?string $divider_thickness = null;
    public ?string $dividerWidth = null;
    public ?string $divider_width = null;
    public ?string $dividerAlign = null;
    public ?string $divider_align = null;

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

        if (null !== ($this->dividerStyle ?? $this->divider_style)) {
            $resolved['divider_style'] = $this->dividerStyle ?? $this->divider_style;
        }
        if (null !== ($this->dividerColor ?? $this->divider_color)) {
            $resolved['divider_color'] = $this->dividerColor ?? $this->divider_color;
        }
        if (null !== ($this->dividerThickness ?? $this->divider_thickness)) {
            $resolved['divider_thickness'] = $this->dividerThickness ?? $this->divider_thickness;
        }
        if (null !== ($this->dividerWidth ?? $this->divider_width)) {
            $resolved['divider_width'] = $this->dividerWidth ?? $this->divider_width;
        }
        if (null !== ($this->dividerAlign ?? $this->divider_align)) {
            $resolved['divider_align'] = $this->dividerAlign ?? $this->divider_align;
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

    public function getDividerStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['divider_style'] ?? null;

        if (null === $style || 'line' === $style) {
            return null !== $this->getResolvedIcon() ? 'icon' : 'line';
        }

        return match ($style) {
            'dashed' => 'dashed',
            'dotted' => 'dotted',
            'small' => 'small',
            'icon' => 'icon',
            default => null !== $this->getResolvedIcon() ? 'icon' : 'line',
        };
    }

    public function getDividerAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['divider_align'] ?? 'center';

        return match ($align) {
            'left', 'start' => 'left',
            'right', 'end' => 'right',
            default => 'center',
        };
    }

    public function getDividerColorClasses(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['divider_color'] ?? 'default';

        return match ($color) {
            'primary' => 'border-primary bg-primary',
            'muted' => 'border-line-1 bg-surface',
            'dark' => 'border-line-8 bg-inverse',
            default => 'border-border bg-border',
        };
    }

    public function getDividerThicknessClasses(): string
    {
        $props = $this->getResolvedProps();
        $thickness = $props['divider_thickness'] ?? '1';
        $thicknessStr = \is_scalar($thickness) ? (string) $thickness : '1';

        return match ($thicknessStr) {
            '2' => 'border-t-2 h-0.5',
            '4' => 'border-t-4 h-1',
            default => 'border-t h-px',
        };
    }

    public function getDividerWidthClasses(): string
    {
        $props = $this->getResolvedProps();
        $width = $props['divider_width'] ?? ('small' === $this->getDividerStyle() ? 'small' : 'full');

        return match ($width) {
            '3-4' => 'w-3/4',
            '1-2' => 'w-1/2',
            '1-3' => 'w-1/3',
            'small' => 'w-16',
            default => 'w-full',
        };
    }

    public function getDividerAlignClasses(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['divider_align'] ?? 'center';

        return match ($align) {
            'left' => 'me-auto justify-start text-start',
            'right' => 'ms-auto justify-end text-end',
            default => 'mx-auto justify-center text-center',
        };
    }

    public function getWrapperClasses(): string
    {
        $base = 'w-full flex items-center ' . $this->getDividerAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getResolvedIcon(): ?string
    {
        return PropsResolver::resolveIcon($this->icon ?? ($this->props['icon'] ?? null));
    }

    public function getIconLineClasses(): string
    {
        $align = $this->getDividerAlign();
        $props = $this->getResolvedProps();
        $thickness = $props['divider_thickness'] ?? '1';
        $thicknessStr = \is_scalar($thickness) ? (string) $thickness : '1';
        $color = $props['divider_color'] ?? 'default';

        $afterClass = match ($color) {
            'primary' => match ($thicknessStr) {
                '2' => 'after:flex-1 after:border-t-2 after:border-primary after:ms-4',
                '4' => 'after:flex-1 after:border-t-4 after:border-primary after:ms-4',
                default => 'after:flex-1 after:border-t after:border-primary after:ms-4',
            },
            'muted' => match ($thicknessStr) {
                '2' => 'after:flex-1 after:border-t-2 after:border-line-1 after:ms-4',
                '4' => 'after:flex-1 after:border-t-4 after:border-line-1 after:ms-4',
                default => 'after:flex-1 after:border-t after:border-line-1 after:ms-4',
            },
            'dark' => match ($thicknessStr) {
                '2' => 'after:flex-1 after:border-t-2 after:border-line-8 after:ms-4',
                '4' => 'after:flex-1 after:border-t-4 after:border-line-8 after:ms-4',
                default => 'after:flex-1 after:border-t after:border-line-8 after:ms-4',
            },
            default => match ($thicknessStr) {
                '2' => 'after:flex-1 after:border-t-2 after:border-border after:ms-4',
                '4' => 'after:flex-1 after:border-t-4 after:border-border after:ms-4',
                default => 'after:flex-1 after:border-t after:border-border after:ms-4',
            },
        };

        $beforeClass = match ($color) {
            'primary' => match ($thicknessStr) {
                '2' => 'before:flex-1 before:border-t-2 before:border-primary before:me-4',
                '4' => 'before:flex-1 before:border-t-4 before:border-primary before:me-4',
                default => 'before:flex-1 before:border-t before:border-primary before:me-4',
            },
            'muted' => match ($thicknessStr) {
                '2' => 'before:flex-1 before:border-t-2 before:border-line-1 before:me-4',
                '4' => 'before:flex-1 before:border-t-4 before:border-line-1 before:me-4',
                default => 'before:flex-1 before:border-t before:border-line-1 before:me-4',
            },
            'dark' => match ($thicknessStr) {
                '2' => 'before:flex-1 before:border-t-2 before:border-line-8 before:me-4',
                '4' => 'before:flex-1 before:border-t-4 before:border-line-8 before:me-4',
                default => 'before:flex-1 before:border-t before:border-line-8 before:me-4',
            },
            default => match ($thicknessStr) {
                '2' => 'before:flex-1 before:border-t-2 before:border-border before:me-4',
                '4' => 'before:flex-1 before:border-t-4 before:border-border before:me-4',
                default => 'before:flex-1 before:border-t before:border-border before:me-4',
            },
        };

        if ('left' === $align) {
            return $afterClass;
        }

        if ('right' === $align) {
            return $beforeClass;
        }

        return $beforeClass . ' ' . $afterClass;
    }

    public function getIconBadgeColorClasses(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['divider_color'] ?? 'default';

        return match ($color) {
            'primary' => 'border-primary-200 bg-layer text-primary dark:border-primary-900/50',
            'dark' => 'border-line-8 bg-inverse text-foreground-inverse',
            'muted' => 'border-border bg-surface text-muted-foreground-1',
            default => 'border-layer-line bg-layer text-muted-foreground-2',
        };
    }
}
