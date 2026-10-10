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
        $style = $props['divider_style'] ?? 'line';

        return match ($style) {
            'dashed' => 'dashed',
            'dotted' => 'dotted',
            'small' => 'small',
            'icon' => 'icon',
            default => 'line',
        };
    }

    public function getDividerColorClasses(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['divider_color'] ?? 'default';

        return match ($color) {
            'primary' => 'border-blue-600 dark:border-blue-500 bg-blue-600',
            'muted' => 'border-gray-100 dark:border-neutral-800 bg-gray-100 dark:bg-neutral-800',
            'dark' => 'border-gray-900 dark:border-white bg-gray-900 dark:bg-white',
            default => 'border-gray-200 dark:border-neutral-700 bg-gray-200 dark:bg-neutral-700',
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
        $base = 'flex items-center ' . $this->getDividerAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getResolvedIcon(): ?string
    {
        if (null === $this->icon || '' === $this->icon) {
            return null;
        }

        if (\is_string($this->icon)) {
            return \str_starts_with($this->icon, 'ti ') ? $this->icon : 'ti ti-' . $this->icon;
        }

        if (\is_array($this->icon) && isset($this->icon['icon']) && \is_string($this->icon['icon'])) {
            $name = $this->icon['icon'];

            return \str_starts_with($name, 'ti ') ? $name : 'ti ti-' . $name;
        }

        return null;
    }
}
