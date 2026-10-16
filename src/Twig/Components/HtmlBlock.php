<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Custom HTML / Embed snippet component.
 */
#[AsTwigComponent('Html', template: '@SuluPrelineBlocks/components/Html.html.twig')]
final class HtmlBlock extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'raw_output' => false,
            'card' => false,
        ];
    }

    protected function capabilities(): array
    {
        return [
            'raw' => fn (): bool => $this->boolProp('raw_output', false),
            'card' => fn (): bool => $this->boolProp('card', false),
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasContent();
    }

    public function getContent(): string
    {
        return $this->nullableString('content') ?? $this->string('html', '');
    }

    public function hasContent(): bool
    {
        return '' !== $this->getContent();
    }

    public function isRaw(): bool
    {
        return $this->is('raw');
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses(
            'w-full my-4',
            $this->is('card') ? 'p-6 bg-card border border-card-line rounded-xl shadow-xs' : null,
            $extraBase,
            ...$moreParts,
        );
    }
}
