<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Custom HTML / Embed snippet component.
 */
#[AsTwigComponent('Html', template: '@SuluPrelineBlocks/components/Html.html.twig')]
final class HtmlBlock
{
    public ?string $title = null;
    public ?string $content = null;
    public bool $raw_output = false;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getContent(): string
    {
        return $this->content ?? '';
    }

    public function hasContent(): bool
    {
        return '' !== \trim($this->getContent());
    }

    public function isRaw(): bool
    {
        return $this->raw_output;
    }

    public function getWrapperClasses(): string
    {
        $classes = ['w-full', 'my-4'];

        $resolved = $this->resolveProps();
        if (!empty($resolved['card'])) {
            $classes[] = 'p-6 bg-card border border-card-line rounded-xl shadow-xs';
        }

        return \implode(' ', $classes);
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveProps(): array
    {
        if (isset($this->props['props']) && \is_array($this->props['props'])) {
            /** @var array<string, mixed> $inner */
            $inner = $this->props['props'];

            return $inner;
        }

        return $this->props;
    }
}
