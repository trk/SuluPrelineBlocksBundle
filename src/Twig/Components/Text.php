<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Text', template: '@SuluPrelineBlocks/components/Text.html.twig')]
final class Text extends PrelineComponent
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
        return PrelineClassMap::resolve(
            PrelineClassMap::TEXT_COLUMNS,
            $this->stringProp('column', 'none'),
            '',
        );
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
        return PrelineClassMap::resolve(
            PrelineClassMap::TEXT_STYLES,
            $this->stringProp('text_style', 'none'),
            '',
        );
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
