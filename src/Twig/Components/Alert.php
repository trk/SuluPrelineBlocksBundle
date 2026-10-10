<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Alert', template: '@SuluPrelineBlocks/components/Alert.html.twig')]
final class Alert
{
    public ?string $title = null;
    public ?string $content = null;
    public mixed $icon = null;
    public mixed $link = null;
    public ?string $linkTitle = null;
    public ?string $link_title = null;

    public ?string $alertStyle = null;
    public ?string $alert_style = null;
    public ?string $alertVariant = null;
    public ?string $alert_variant = null;
    public ?string $dismissible = null;
    public ?string $iconDisplay = null;
    public ?string $icon_display = null;
    public ?string $titleSize = null;
    public ?string $title_size = null;
    public ?string $rounded = null;

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

        if (null !== ($this->alertStyle ?? $this->alert_style)) {
            $resolved['alert_style'] = $this->alertStyle ?? $this->alert_style;
        }
        if (null !== ($this->alertVariant ?? $this->alert_variant)) {
            $resolved['alert_variant'] = $this->alertVariant ?? $this->alert_variant;
        }
        if (null !== $this->dismissible) {
            $resolved['dismissible'] = $this->dismissible;
        }
        if (null !== ($this->iconDisplay ?? $this->icon_display)) {
            $resolved['icon_display'] = $this->iconDisplay ?? $this->icon_display;
        }
        if (null !== ($this->titleSize ?? $this->title_size)) {
            $resolved['title_size'] = $this->titleSize ?? $this->title_size;
        }
        if (null !== $this->rounded) {
            $resolved['rounded'] = $this->rounded;
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

    public function getAlertStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['alert_style'] ?? 'info';

        return match ($style) {
            'success' => 'success',
            'warning' => 'warning',
            'danger' => 'danger',
            'dark' => 'dark',
            default => 'info',
        };
    }

    public function getAlertVariant(): string
    {
        $props = $this->getResolvedProps();
        $variant = $props['alert_variant'] ?? 'soft';

        return match ($variant) {
            'solid' => 'solid',
            'outline' => 'outline',
            'glass' => 'glass',
            default => 'soft',
        };
    }

    public function isDismissible(): bool
    {
        $props = $this->getResolvedProps();
        $d = $props['dismissible'] ?? 'no';

        return 'yes' === $d || true === $d;
    }

    public function getAlertClasses(): string
    {
        $style = $this->getAlertStyle();
        $variant = $this->getAlertVariant();

        return match ("{$style}_{$variant}") {
            'info_solid' => 'bg-primary-600 text-white shadow-xs',
            'info_outline' => 'border-2 border-primary-500 text-primary-700 bg-transparent dark:text-primary-400',
            'info_glass' => 'backdrop-blur-md bg-primary-500/10 border border-primary-500/30 text-primary-900 dark:text-primary-200',

            'success_solid' => 'bg-emerald-600 text-white shadow-xs',
            'success_outline' => 'border-2 border-emerald-500 text-emerald-700 bg-transparent dark:text-emerald-400',
            'success_glass' => 'backdrop-blur-md bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-200',

            'warning_solid' => 'bg-amber-600 text-white shadow-xs',
            'warning_outline' => 'border-2 border-amber-500 text-amber-700 bg-transparent dark:text-amber-400',
            'warning_glass' => 'backdrop-blur-md bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200',

            'danger_solid' => 'bg-red-600 text-white shadow-xs',
            'danger_outline' => 'border-2 border-red-500 text-red-700 bg-transparent dark:text-red-400',
            'danger_glass' => 'backdrop-blur-md bg-red-500/10 border border-red-500/30 text-red-900 dark:text-red-200',

            'dark_solid' => 'bg-gray-900 text-white shadow-xs dark:bg-white dark:text-gray-900',
            'dark_outline' => 'border-2 border-gray-400 text-gray-800 bg-transparent dark:border-neutral-600 dark:text-neutral-200',
            'dark_glass' => 'backdrop-blur-md bg-white/70 border border-gray-200 text-gray-900 dark:bg-neutral-900/70 dark:border-neutral-700 dark:text-white',

            'success_soft' => 'bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300',
            'warning_soft' => 'bg-amber-50 border border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300',
            'danger_soft' => 'bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-300',
            'dark_soft' => 'bg-gray-100 border border-gray-200 text-gray-800 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200',
            default => 'bg-primary-50 border border-primary-200 text-primary-800 dark:bg-primary-950/40 dark:border-primary-800 dark:text-primary-300',
        };
    }

    public function getResolvedIcon(): ?string
    {
        $props = $this->getResolvedProps();
        $display = $props['icon_display'] ?? 'auto';

        if ('none' === $display) {
            return null;
        }

        if (null !== $this->icon && '' !== $this->icon) {
            if (\is_string($this->icon)) {
                return \str_starts_with($this->icon, 'ti ') ? $this->icon : 'ti ti-' . $this->icon;
            }
            if (\is_array($this->icon) && isset($this->icon['icon']) && \is_string($this->icon['icon'])) {
                $name = $this->icon['icon'];

                return \str_starts_with($name, 'ti ') ? $name : 'ti ti-' . $name;
            }
        }

        if ('custom' === $display) {
            return null;
        }

        return match ($this->getAlertStyle()) {
            'success' => 'ti ti-circle-check',
            'warning' => 'ti ti-alert-triangle',
            'danger' => 'ti ti-alert-circle',
            'dark' => 'ti ti-bell',
            default => 'ti ti-info-circle',
        };
    }

    public function getTitle(): ?string
    {
        return (null !== $this->title && '' !== \trim($this->title)) ? \trim($this->title) : null;
    }

    public function getContent(): ?string
    {
        return (null !== $this->content && '' !== \trim($this->content)) ? \trim($this->content) : null;
    }

    public function getLinkUrl(): ?string
    {
        if (null !== $this->link) {
            $url = PropsResolver::resolveUrl($this->link);
            if ('#' !== $url && '' !== $url) {
                return $url;
            }
        }

        return null;
    }

    public function getLinkTarget(): ?string
    {
        if (\is_array($this->link) && isset($this->link['target']) && \is_string($this->link['target']) && '' !== \trim($this->link['target'])) {
            return \trim($this->link['target']);
        }

        return null;
    }

    public function getLinkTitle(): ?string
    {
        if (\is_array($this->link) && isset($this->link['title']) && \is_string($this->link['title']) && '' !== \trim($this->link['title'])) {
            return \trim($this->link['title']);
        }

        $text = $this->linkTitle ?? $this->link_title;
        if (null !== $text && '' !== \trim($text)) {
            return \trim($text);
        }

        return null;
    }

    public function getButtonText(): ?string
    {
        $text = $this->linkTitle ?? $this->link_title;
        if (null !== $text && '' !== \trim($text)) {
            return \trim($text);
        }

        if (\is_array($this->link) && isset($this->link['title']) && \is_string($this->link['title']) && '' !== \trim($this->link['title'])) {
            return \trim($this->link['title']);
        }

        return null;
    }

    public function getTitleSizeClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['title_size'] ?? 'md';

        return match ($size) {
            'sm' => 'text-sm font-semibold',
            'lg' => 'text-lg font-bold',
            default => 'text-base font-semibold',
        };
    }

    public function getRoundedClasses(): string
    {
        $props = $this->getResolvedProps();
        $rounded = $props['rounded'] ?? 'xl';

        return match ($rounded) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            '2xl' => 'rounded-2xl',
            default => 'rounded-xl',
        };
    }

    public function getWrapperClasses(): string
    {
        $base = \sprintf('relative flex items-start gap-x-3 p-4 sm:p-5 transition-all %s %s', $this->getRoundedClasses(), $this->getAlertClasses());

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
