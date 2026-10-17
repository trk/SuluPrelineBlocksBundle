<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

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
        $url = SuluDataNormalizer::sanitizeUrl($this->stringProp('home_url', '/'), '/');

        return ('#' === $url || '' === $url) ? '/' : $url;
    }

    public function getPageUuid(): ?string
    {
        $uuid = $this->context['uuid'] ?? null;

        if (!\is_string($uuid) || '' === \trim($uuid)) {
            $view = $this->context['view'] ?? null;
            if (\is_array($view) && \is_string($view['uuid'] ?? null)) {
                $uuid = $view['uuid'];
            }
        }

        if (!\is_string($uuid) || '' === \trim($uuid)) {
            $content = $this->context['content'] ?? null;
            if (\is_array($content) && \is_string($content['id'] ?? null)) {
                $uuid = $content['id'];
            }
        }

        if (!\is_string($uuid) || '' === \trim($uuid)) {
            $uuid = $this->nullableStringProp('uuid');
        }

        if (!\is_string($uuid)) {
            return null;
        }

        $trimmed = \trim($uuid);

        return '' !== $trimmed ? $trimmed : null;
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

            $rawUrl = $this->itemValue($item, 'url') ?? $this->itemValue($item, 'link');
            $url = null !== $rawUrl ? SuluDataNormalizer::resolveUrl($rawUrl, '') : null;
            if ('' === $url || '#' === $url) {
                $url = null;
            }

            $normalized[] = [
                'title' => $title,
                'url' => $url,
            ];
        }

        return $normalized;
    }

    public function getListClasses(): string
    {
        return $this->classes('flex items-center whitespace-nowrap', $this->getAlignClasses());
    }
}
