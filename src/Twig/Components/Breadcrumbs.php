<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Breadcrumbs', template: '@SuluPrelineBlocks/components/Breadcrumbs.html.twig')]
final class Breadcrumbs
{
    /**
     * @var list<array{title?: string, url?: string, link?: string}>
     */
    public array $items = [];

    public bool $showHome = true;
    public bool $show_home = true;

    public string $homeTitle = 'Home';
    public string $home_title = 'Home';

    public string $homeUrl = '/';
    public string $home_url = '/';

    public string $align = 'left';

    public ?string $class = null;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function isShowHome(): bool
    {
        return $this->showHome && $this->show_home;
    }

    public function getHomeTitle(): string
    {
        return 'Home' !== $this->homeTitle ? $this->homeTitle : $this->home_title;
    }

    public function getHomeUrl(): string
    {
        return '/' !== $this->homeUrl ? $this->homeUrl : $this->home_url;
    }

    public function getAlignClasses(): string
    {
        return match ($this->align) {
            'center' => 'justify-center',
            'right' => 'justify-end',
            default => 'justify-start',
        };
    }

    /**
     * @return list<array{title: string, url: string|null}>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items as $item) {
            $title = $item['title'] ?? null;
            if (!\is_string($title) || '' === \trim($title)) {
                continue;
            }

            $rawUrl = $item['url'] ?? ($item['link'] ?? null);
            $url = \is_string($rawUrl) && '' !== \trim($rawUrl) ? \trim($rawUrl) : null;

            $normalized[] = [
                'title' => \trim($title),
                'url' => $url,
            ];
        }

        return $normalized;
    }
}
