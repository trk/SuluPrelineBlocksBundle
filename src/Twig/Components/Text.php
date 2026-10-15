<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Text', template: '@SuluPrelineBlocks/components/Text.html.twig')]
final class Text
{
    public ?string $content = '';

    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?string $fontSize = null;
    public ?string $font_size = null;
    public ?string $textColor = null;
    public ?string $text_color = null;
    public ?bool $prose = null;
    public ?bool $dropcap = null;
    public ?string $column = null;
    public ?bool $columnDivider = null;
    public ?bool $column_divider = null;
    public ?string $textStyle = null;
    public ?string $text_style = null;

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
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->fontSize ?? $this->font_size)) {
            $resolved['font_size'] = $this->fontSize ?? $this->font_size;
        }
        if (null !== ($this->textColor ?? $this->text_color)) {
            $resolved['text_color'] = $this->textColor ?? $this->text_color;
        }
        if (null !== $this->prose) {
            $resolved['prose'] = $this->prose;
        }
        if (null !== $this->dropcap) {
            $resolved['dropcap'] = $this->dropcap;
        }
        if (null !== $this->column) {
            $resolved['column'] = $this->column;
        }
        if (null !== ($this->columnDivider ?? $this->column_divider)) {
            $resolved['column_divider'] = $this->columnDivider ?? $this->column_divider;
        }
        if (null !== ($this->textStyle ?? $this->text_style)) {
            $resolved['text_style'] = $this->textStyle ?? $this->text_style;
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

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->getResolvedProps());
    }

    public function getFontSize(): string
    {
        $props = $this->getResolvedProps();
        $size = isset($props['font_size']) && \is_string($props['font_size'])
            ? $props['font_size']
            : (isset($props['size']) && \is_string($props['size']) ? $props['size'] : 'base');

        return match ($size) {
            'sm' => 'text-sm',
            'lg' => 'text-lg',
            'xl' => 'text-xl',
            default => 'text-base',
        };
    }

    public function getTextColor(): string
    {
        return PropsResolver::textColor($this->getResolvedProps());
    }

    public function isProse(): bool
    {
        $props = $this->getResolvedProps();

        return !isset($props['prose']) || false !== $props['prose'];
    }

    public function isDropcap(): bool
    {
        $props = $this->getResolvedProps();

        return true === ($props['dropcap'] ?? false);
    }

    public function getDropcapClasses(): string
    {
        return $this->isDropcap()
            ? 'first-letter:float-left first-letter:text-5xl first-letter:font-bold first-letter:me-3 first-letter:leading-none first-letter:text-foreground'
            : '';
    }

    public function getColumnClasses(): string
    {
        $props = $this->getResolvedProps();
        $col = $props['column'] ?? 'none';

        return match ($col) {
            '1-2' => 'md:columns-2 gap-8',
            '1-3' => 'md:columns-3 gap-8',
            '1-4' => 'md:columns-2 lg:columns-4 gap-8',
            default => '',
        };
    }

    public function isColumnDivider(): bool
    {
        $props = $this->getResolvedProps();

        return true === ($props['column_divider'] ?? false);
    }

    public function getColumnDividerClasses(): string
    {
        if (!$this->isColumnDivider() || '' === $this->getColumnClasses()) {
            return '';
        }

        return '[column-rule:1px_solid_var(--border)]';
    }

    public function getTextStyleClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['text_style'] ?? 'none';

        return match ($style) {
            'lead' => 'text-xl sm:text-2xl text-muted-foreground-2 font-normal leading-relaxed',
            'meta' => 'text-xs uppercase tracking-wider text-muted-foreground-1 font-medium',
            'large' => 'text-lg sm:text-xl text-foreground leading-relaxed',
            'small' => 'text-sm text-muted-foreground-2 leading-normal',
            default => '',
        };
    }

    public function getWrapperClasses(): string
    {
        $base = $this->isProse() ? 'prose dark:prose-invert max-w-none' : '';
        $base = \trim(\sprintf(
            '%s %s %s %s %s %s %s',
            $base,
            $this->getFontSize(),
            $this->getTextColor(),
            $this->getTextStyleClasses(),
            $this->getColumnClasses(),
            $this->getColumnDividerClasses(),
            $this->getDropcapClasses(),
        ));

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
