<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Badge', template: '@SuluPrelineBlocks/components/Badge.html.twig')]
final class Badge
{
    public ?string $title = null;
    public ?string $content = null;
    public mixed $link = null;
    public ?string $linkTitle = null;
    public ?string $link_title = null;
    public mixed $icon = null;
    public ?string $iconAlign = null;
    public ?string $icon_align = null;
    public ?bool $hasDot = null;
    public ?bool $has_dot = null;

    public ?string $badgeStyle = null;
    public ?string $badge_style = null;
    public ?string $badgeColor = null;
    public ?string $badge_color = null;
    public ?string $size = null;
    public ?string $rounded = null;

    public ?string $id = null;
    public ?string $customClass = null;
    public ?string $custom_class = null;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    /**
     * @return array<string, mixed>
     */
    public function getResolvedProps(): array
    {
        $resolved = $this->props;

        if (null !== ($this->badgeStyle ?? $this->badge_style)) {
            $resolved['badge_style'] = $this->badgeStyle ?? $this->badge_style;
        }
        if (null !== ($this->badgeColor ?? $this->badge_color)) {
            $resolved['badge_color'] = $this->badgeColor ?? $this->badge_color;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== $this->rounded) {
            $resolved['rounded'] = $this->rounded;
        }
        if (null !== ($this->iconAlign ?? $this->icon_align)) {
            $resolved['icon_align'] = $this->iconAlign ?? $this->icon_align;
        }
        if (null !== ($this->hasDot ?? $this->has_dot)) {
            $resolved['has_dot'] = $this->hasDot ?? $this->has_dot;
        }
        if (null !== $this->id) {
            $resolved['id'] = $this->id;
        }
        if (null !== ($this->customClass ?? $this->custom_class)) {
            $resolved['custom_class'] = $this->customClass ?? $this->custom_class;
        }

        return $resolved;
    }

    public function getTitle(): ?string
    {
        $t = $this->title ?? $this->content;
        if (null !== $t && '' !== \trim($t)) {
            return \trim($t);
        }

        return null;
    }

    public function getUrl(): ?string
    {
        if (null !== $this->link) {
            $url = PropsResolver::resolveUrl($this->link);
            if ('#' !== $url && '' !== $url) {
                return $url;
            }
        }

        return null;
    }

    public function isClickable(): bool
    {
        return null !== $this->getUrl();
    }

    public function getClasses(): string
    {
        $props = $this->getResolvedProps();
        $base = PropsResolver::badgeClasses($props);

        $custom = isset($props['custom_class']) && \is_string($props['custom_class']) ? \trim($props['custom_class']) : '';
        if ('' !== $custom) {
            $base .= ' ' . $custom;
        }

        return $base;
    }

    public function getResolvedIcon(): ?string
    {
        return PropsResolver::resolveIcon($this->icon);
    }

    public function getIconAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['icon_align'] ?? ($this->iconAlign ?? ($this->icon_align ?? 'left'));

        return 'right' === $align ? 'right' : 'left';
    }

    public function hasDot(): bool
    {
        $props = $this->getResolvedProps();

        return !empty($props['has_dot']);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
