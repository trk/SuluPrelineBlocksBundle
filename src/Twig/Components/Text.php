<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Text', template: '@SuluPrelineBlocks/components/Text.html.twig')]
final class Text extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'text_align' => 'left',
            'font_size' => 'base',
            'text_color' => 'default',
            'prose' => true,
            'dropcap' => false,
            'column' => 'none',
            'column_divider' => false,
            'text_style' => 'none',
        ];
    }

    protected function capabilities(): array
    {
        return [
            'prose' => fn (): bool => $this->boolProp('prose', true),
            'dropcap' => fn (): bool => $this->boolProp('dropcap', false),
            'column_divider' => fn (): bool => $this->boolProp('column_divider', false),
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasRichText('content') || $this->has('text');
    }

    public function getContent(): string
    {
        $content = $this->string('content', '');

        return '' !== $content ? $content : $this->string('text', '');
    }

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->props());
    }

    public function getFontSize(): string
    {
        $size = $this->nullableStringProp('font_size');
        if (null === $size || 'base' === $size) {
            $size = $this->stringProp('size', $size ?? 'base');
        }

        return match ($size) {
            'sm' => 'text-sm',
            'lg' => 'text-lg',
            'xl' => 'text-xl',
            default => 'text-base',
        };
    }

    public function getTextColor(): string
    {
        $props = $this->props();
        if (isset($props['color']) && ('default' === ($props['text_color'] ?? 'default'))) {
            unset($props['text_color']);
        }

        return PropsResolver::textColor($props);
    }

    public function isProse(): bool
    {
        return $this->is('prose');
    }

    public function isDropcap(): bool
    {
        return $this->is('dropcap');
    }

    public function getDropcapClasses(): string
    {
        return $this->isDropcap()
            ? 'first-letter:float-left first-letter:text-5xl first-letter:font-bold first-letter:me-3 first-letter:leading-none first-letter:text-foreground'
            : '';
    }

    public function getColumnClasses(): string
    {
        return match ($this->stringProp('column', 'none')) {
            '1-2' => 'md:columns-2 gap-8',
            '1-3' => 'md:columns-3 gap-8',
            '1-4' => 'md:columns-2 lg:columns-4 gap-8',
            default => '',
        };
    }

    public function isColumnDivider(): bool
    {
        return $this->is('column_divider');
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
        return match ($this->stringProp('text_style', 'none')) {
            'lead' => 'text-xl sm:text-2xl text-muted-foreground-2 font-normal leading-relaxed',
            'meta' => 'text-xs uppercase tracking-wider text-muted-foreground-1 font-medium',
            'large' => 'text-lg sm:text-xl text-foreground leading-relaxed',
            'small' => 'text-sm text-muted-foreground-2 leading-normal',
            default => '',
        };
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = $this->isProse() ? 'prose dark:prose-invert max-w-none' : '';
        $base = \trim(\sprintf(
            '%s %s %s %s %s %s %s %s',
            $base,
            $this->getFontSize(),
            $this->getTextColor(),
            $this->getTextStyleClasses(),
            $this->getColumnClasses(),
            $this->getColumnDividerClasses(),
            $this->getDropcapClasses(),
            $extraBase,
        ));

        return parent::getWrapperClasses($base);
    }
}
