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
        return $this->string('content', $this->string('html', ''));
    }

    public function hasContent(): bool
    {
        return '' !== \trim($this->getContent());
    }

    public function isRaw(): bool
    {
        return $this->is('raw');
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $classes = ['w-full', 'my-4'];

        if ($this->is('card')) {
            $classes[] = 'p-6 bg-card border border-card-line rounded-xl shadow-xs';
        }

        if ('' !== $extraBase) {
            $classes[] = $extraBase;
        }

        return parent::getWrapperClasses(\implode(' ', $classes));
    }
}
