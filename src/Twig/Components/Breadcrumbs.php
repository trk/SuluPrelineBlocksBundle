<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Breadcrumbs', template: '@SuluPrelineBlocks/components/Breadcrumbs.html.twig')]
final class Breadcrumbs extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'show_home' => true,
            'home_title' => 'Home',
            'home_url' => '/',
            'align' => 'left',
        ];
    }

    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'linked' => true,
        ];
    }

    public function isShowHome(): bool
    {
        return $this->boolProp('show_home', true);
    }

    public function getHomeTitle(): string
    {
        return $this->stringProp('home_title', 'Home');
    }

    public function getHomeUrl(): string
    {
        return $this->stringProp('home_url', '/');
    }

    /**
     * @return list<array{title: string, url: string|null}>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items('items') as $item) {
            $title = $this->itemNullableString($item, 'title');
            if (null === $title) {
                continue;
            }

            $normalized[] = [
                'title' => $title,
                'url' => $this->itemNullableString($item, 'url') ?? $this->itemNullableString($item, 'link'),
            ];
        }

        return $normalized;
    }
}
