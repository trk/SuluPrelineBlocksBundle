<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Divider', template: '@SuluPrelineBlocks/components/Divider.html.twig')]
final class Divider extends AbstractPrelineComponent
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
        return match ($this->stringProp('divider_color', 'default')) {
            'primary' => 'border-primary bg-primary',
            'muted' => 'border-line-1 bg-surface',
            'dark' => 'border-line-8 bg-inverse',
            default => 'border-border bg-border',
        };
    }

    public function getDividerThicknessClasses(): string
    {
        return match ($this->stringProp('divider_thickness', '1')) {
            '2' => 'border-t-2 h-0.5',
            '4' => 'border-t-4 h-1',
            default => 'border-t h-px',
        };
    }

    public function getDividerWidthClasses(): string
    {
        $defaultWidth = 'small' === $this->getDividerStyle() ? 'small' : 'full';
        $width = $this->stringProp('divider_width', $defaultWidth);

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
        return match ($this->stringProp('divider_align', 'center')) {
            'left' => 'me-auto justify-start text-start',
            'right' => 'ms-auto justify-end text-end',
            default => 'mx-auto justify-center text-center',
        };
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim('w-full flex items-center ' . $this->getDividerAlignClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }

    public function getResolvedIcon(): ?string
    {
        return $this->getIconClass('icon');
    }

    public function getIconLineClasses(): string
    {
        $align = $this->getDividerAlign();
        $thicknessStr = $this->stringProp('divider_thickness', '1');
        $color = $this->stringProp('divider_color', 'default');

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
        return match ($this->stringProp('divider_color', 'default')) {
            'primary' => 'border-primary-200 bg-layer text-primary dark:border-primary-900/50',
            'dark' => 'border-line-8 bg-inverse text-foreground-inverse',
            'muted' => 'border-border bg-surface text-muted-foreground-1',
            default => 'border-layer-line bg-layer text-muted-foreground-2',
        };
    }
}
