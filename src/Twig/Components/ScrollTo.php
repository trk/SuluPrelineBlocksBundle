<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('ScrollTo', template: '@SuluPrelineBlocks/components/ScrollTo.html.twig')]
final class ScrollTo
{
    public ?string $title = null;
    public ?string $targetId = null;
    public ?string $target_id = null;
    public mixed $icon = null;

    public ?string $displayMode = null;
    public ?string $display_mode = null;
    public ?string $buttonVariant = null;
    public ?string $button_variant = null;
    public ?string $buttonColor = null;
    public ?string $button_color = null;
    public ?string $align = null;
    public ?string $iconPosition = null;
    public ?string $icon_position = null;

    public ?string $marginTop = null;
    public ?string $margin_top = null;
    public ?string $marginBottom = null;
    public ?string $margin_bottom = null;
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

        if (null !== ($this->targetId ?? $this->target_id)) {
            $resolved['target_id'] = $this->targetId ?? $this->target_id;
        }
        if (null !== ($this->displayMode ?? $this->display_mode)) {
            $resolved['display_mode'] = $this->displayMode ?? $this->display_mode;
        }
        if (null !== ($this->buttonVariant ?? $this->button_variant)) {
            $resolved['button_variant'] = $this->buttonVariant ?? $this->button_variant;
        }
        if (null !== ($this->buttonColor ?? $this->button_color)) {
            $resolved['button_color'] = $this->buttonColor ?? $this->button_color;
        }
        if (null !== $this->align) {
            $resolved['align'] = $this->align;
        }
        if (null !== ($this->iconPosition ?? $this->icon_position)) {
            $resolved['icon_position'] = $this->iconPosition ?? $this->icon_position;
        }

        if (null !== ($this->marginTop ?? $this->margin_top)) {
            $resolved['margin_top'] = $this->marginTop ?? $this->margin_top;
        }
        if (null !== ($this->marginBottom ?? $this->margin_bottom)) {
            $resolved['margin_bottom'] = $this->marginBottom ?? $this->margin_bottom;
        }
        if (null !== $this->id) {
            $resolved['id'] = $this->id;
        }
        if (null !== ($this->customClass ?? $this->custom_class)) {
            $resolved['custom_class'] = $this->customClass ?? $this->custom_class;
        }

        return $resolved;
    }

    public function getTargetId(): string
    {
        $props = $this->getResolvedProps();
        $target = $props['target_id'] ?? null;

        if (!\is_scalar($target) || '' === \trim((string) $target)) {
            return 'top';
        }

        $clean = \ltrim(\trim((string) $target), '#');

        return '' === $clean ? 'top' : $clean;
    }

    public function getTargetHref(): string
    {
        return '#' . $this->getTargetId();
    }

    public function isTop(): bool
    {
        return 'top' === $this->getTargetId();
    }

    public function getResolvedIcon(): string
    {
        $custom = PropsResolver::resolveIcon($this->icon ?? ($this->props['icon'] ?? null));
        if (null !== $custom) {
            return $custom;
        }

        return $this->isTop() ? 'ti ti-arrow-up' : 'ti ti-arrow-down';
    }

    public function getDisplayMode(): string
    {
        $props = $this->getResolvedProps();
        $mode = $props['display_mode'] ?? 'inline_button';

        return match ($mode) {
            'totop_circle' => 'totop_circle',
            'floating' => 'floating',
            default => 'inline_button',
        };
    }

    public function getAlignClasses(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['align'] ?? ($props['text_align'] ?? ($this->isTop() ? 'right' : 'center'));

        return match ($align) {
            'left', 'start' => 'justify-start text-start',
            'center' => 'justify-center text-center',
            default => 'justify-end text-end',
        };
    }

    public function getButtonClasses(): string
    {
        $mode = $this->getDisplayMode();
        $props = $this->getResolvedProps();
        $variant = $props['button_variant'] ?? 'solid';
        $color = $props['button_color'] ?? 'primary';

        // 1. Circle / Icon Button (totop_circle or floating)
        if ('totop_circle' === $mode || 'floating' === $mode) {
            $baseCircle = 'inline-flex items-center justify-center w-11 h-11 rounded-full shadow-md transition-all cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-offset-2';

            $colorClasses = match ($color) {
                'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover focus:ring-secondary',
                'dark' => 'bg-inverse text-foreground-inverse hover:opacity-90 focus:ring-inverse',
                'white' => 'bg-layer text-layer-foreground hover:bg-layer-hover focus:ring-layer-focus',
                'muted' => 'bg-surface text-surface-foreground hover:bg-surface-hover focus:ring-surface-focus',
                default => 'bg-primary text-primary-foreground hover:bg-primary-hover focus:ring-primary',
            };

            return \sprintf('%s %s hover:scale-105', $baseCircle, $colorClasses);
        }

        // 2. Inline Button
        $base = 'inline-flex items-center gap-x-2 py-2.5 px-4 text-sm font-semibold rounded-lg transition-colors cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-offset-2';

        $variantClasses = match ($variant) {
            'outline' => match ($color) {
                'secondary' => 'border border-secondary-line text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
                'dark' => 'border border-line-8 text-foreground hover:bg-muted focus:ring-line-8',
                'white' => 'border border-white text-white hover:bg-white/10 focus:ring-white',
                default => 'border border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            },
            'ghost' => match ($color) {
                'secondary' => 'text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
                'dark' => 'text-foreground hover:bg-muted focus:ring-muted-focus',
                default => 'text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            },
            'soft' => match ($color) {
                'secondary' => 'bg-secondary-50 text-secondary hover:bg-secondary-100 dark:bg-secondary-950/30',
                'dark' => 'bg-surface text-surface-foreground hover:bg-surface-hover',
                default => 'bg-primary-50 text-primary hover:bg-primary-100 dark:bg-primary-950/30',
            },
            default => match ($color) {
                'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover focus:ring-secondary',
                'dark' => 'bg-inverse text-foreground-inverse hover:opacity-90 focus:ring-inverse',
                'white' => 'bg-layer text-layer-foreground hover:bg-layer-hover focus:ring-layer-focus shadow-sm',
                default => 'bg-primary text-primary-foreground hover:bg-primary-hover focus:ring-primary',
            },
        };

        return \sprintf('%s %s', $base, $variantClasses);
    }

    public function getWrapperClasses(): string
    {
        $mode = $this->getDisplayMode();
        if ('floating' === $mode) {
            return 'fixed bottom-6 end-6 z-40 transition-all';
        }

        $base = 'flex items-center ' . $this->getAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
