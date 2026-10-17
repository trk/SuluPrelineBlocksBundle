<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Divider', template: '@SuluPrelineBlocks/components/Divider.html.twig')]
final class Divider extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'divider_color' => 'default',
            'divider_thickness' => '1',
            'divider_align' => 'center',
        ];
    }

    public function shouldRender(): bool
    {
        return true;
    }

    public function getDividerStyle(): string
    {
        $style = $this->nullableStringProp('divider_style');

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
        return match ($this->stringProp('divider_align', 'center')) {
            'left', 'start' => 'left',
            'right', 'end' => 'right',
            default => 'center',
        };
    }

    public function getDividerColorClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::DIVIDER_COLORS,
            $this->stringProp('divider_color', 'default'),
            PrelineClassMap::DIVIDER_COLORS['default'],
        );
    }

    public function getDividerThicknessClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::DIVIDER_THICKNESSES,
            $this->stringProp('divider_thickness', '1'),
            PrelineClassMap::DIVIDER_THICKNESSES['1'],
        );
    }

    public function getDividerWidthClasses(): string
    {
        $defaultWidth = 'small' === $this->getDividerStyle() ? 'small' : 'full';

        return PrelineClassMap::resolve(
            PrelineClassMap::DIVIDER_WIDTHS,
            $this->stringProp('divider_width', $defaultWidth),
            PrelineClassMap::DIVIDER_WIDTHS['full'],
        );
    }

    public function getDividerAlignClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::DIVIDER_ALIGNS,
            $this->stringProp('divider_align', 'center'),
            PrelineClassMap::DIVIDER_ALIGNS['center'],
        );
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes(
            'w-full flex items-center',
            $this->getDividerAlignClasses(),
            $extraBase,
            ...$moreParts,
        );
    }

    public function getResolvedIcon(): ?string
    {
        return $this->getIconClass('icon');
    }

    public function getIconLineClasses(): string
    {
        $align = $this->getDividerAlign();
        $color = $this->enumProp('divider_color', ['default', 'primary', 'muted', 'dark'], 'default');
        $thickness = $this->enumProp('divider_thickness', ['1', '2', '4'], '1');
        $key = $color . '_' . $thickness;

        $beforeClass = PrelineClassMap::DIVIDER_ICON_BEFORE_LINES[$key] ?? PrelineClassMap::DIVIDER_ICON_BEFORE_LINES['default_1'];
        $afterClass = PrelineClassMap::DIVIDER_ICON_AFTER_LINES[$key] ?? PrelineClassMap::DIVIDER_ICON_AFTER_LINES['default_1'];

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
        return PrelineClassMap::resolve(
            PrelineClassMap::DIVIDER_ICON_BADGE_COLORS,
            $this->stringProp('divider_color', 'default'),
            PrelineClassMap::DIVIDER_ICON_BADGE_COLORS['default'],
        );
    }
}
