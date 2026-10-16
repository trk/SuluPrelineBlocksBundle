<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Badge', template: '@SuluPrelineBlocks/components/Badge.html.twig')]
final class Badge extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'badge_style' => 'soft',
            'badge_color' => 'primary',
            'size' => 'xs',
            'rounded' => 'pill',
            'icon_align' => 'left',
            'has_dot' => false,
        ];
    }

    protected function capabilities(): array
    {
        return [
            'clickable' => fn (): bool => null !== $this->getUrl(),
            'dot' => fn (): bool => $this->boolProp('has_dot', false),
        ];
    }

    public function shouldRender(): bool
    {
        return null !== $this->getTitle() || null !== $this->getResolvedIcon();
    }

    public function getTitle(): ?string
    {
        return $this->nullableString('title') ?? $this->nullableString('content');
    }

    public function getUrl(): ?string
    {
        $url = $this->getLinkUrl('link');

        return ('#' === $url || null === $url || '' === $url) ? null : $url;
    }

    public function isClickable(): bool
    {
        return $this->is('clickable');
    }

    public function getClasses(): string
    {
        $base = PropsResolver::badgeClasses($this->props());
        $custom = $this->getCustomClass();

        return '' !== $custom ? $base . ' ' . $custom : $base;
    }

    public function getResolvedIcon(): ?string
    {
        return $this->getIconClass('icon');
    }

    public function getIconAlign(): string
    {
        return 'right' === $this->stringProp('icon_align', 'left') ? 'right' : 'left';
    }

    public function hasDot(): bool
    {
        return $this->is('dot');
    }
}
