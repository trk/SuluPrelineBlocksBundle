<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

#[AsTwigComponent('Badge', template: '@SuluPrelineBlocks/components/Badge.html.twig')]
final class Badge extends PrelineComponent
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
        return $this->nullableString('title')
            ?? $this->nullableString('content')
            ?? $this->nullableString('text');
    }

    public function getUrl(): ?string
    {
        $fromLink = $this->getLinkUrl('link');
        if (null !== $fromLink) {
            return $fromLink;
        }

        $explicitUrl = $this->nullableString('url') ?? $this->nullableStringProp('url');
        if (null === $explicitUrl) {
            return null;
        }

        $clean = SuluDataNormalizer::sanitizeUrl(\trim($explicitUrl), '');

        return ('' !== $clean && '#' !== $clean) ? $clean : null;
    }

    public function isClickable(): bool
    {
        return $this->is('clickable');
    }

    public function getClasses(): string
    {
        $precomputed = $this->nullableStringProp('classes');
        $base = null !== $precomputed ? $precomputed : PropsResolver::badgeClasses($this->props());

        return $this->classes($base, $this->getCustomClass());
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return parent::getWrapperClasses($this->getClasses(), $extraBase, ...$moreParts);
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
