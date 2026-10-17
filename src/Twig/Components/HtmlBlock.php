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

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        if ($this->isRaw()) {
            return $this->classes($extraBase, ...$moreParts);
        }

        return $this->classes(
            'w-full',
            $this->is('card') ? 'p-6 bg-card border border-card-line rounded-2xl shadow-2xs overflow-x-auto' : null,
            $extraBase,
            ...$moreParts,
        );
    }
}
