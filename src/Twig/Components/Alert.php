<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Alert', template: '@SuluPrelineBlocks/components/Alert.html.twig')]
final class Alert extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'alert_style' => 'info',
            'alert_variant' => 'soft',
            'dismissible' => 'no',
            'icon_display' => 'auto',
            'title_size' => 'md',
            'rounded' => 'xl',
        ];
    }

    protected function capabilities(): array
    {
        return [
            'dismissible' => fn (): bool => $this->boolProp('dismissible', false),
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasAny('title', 'content', 'description');
    }

    public function getAlertStyle(): string
    {
        return $this->enumProp('alert_style', ['info', 'success', 'warning', 'danger', 'dark'], 'info');
    }

    public function getAlertVariant(): string
    {
        return $this->enumProp('alert_variant', ['soft', 'solid', 'outline', 'glass'], 'soft');
    }

    public function isDismissible(): bool
    {
        return $this->is('dismissible');
    }

    public function getAlertClasses(): string
    {
        $style = $this->getAlertStyle();
        $variant = $this->getAlertVariant();

        return match ("{$style}_{$variant}") {
            'info_solid' => 'bg-primary text-primary-foreground shadow-xs',
            'info_outline' => 'border-2 border-primary-line text-primary bg-transparent',
            'info_glass' => 'backdrop-blur-md bg-primary/10 border border-primary/30 text-primary-900 dark:text-primary-200',

            'success_solid' => 'bg-emerald-600 text-white shadow-xs',
            'success_outline' => 'border-2 border-emerald-500 text-emerald-700 bg-transparent dark:text-emerald-400',
            'success_glass' => 'backdrop-blur-md bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-200',

            'warning_solid' => 'bg-amber-600 text-white shadow-xs',
            'warning_outline' => 'border-2 border-amber-500 text-amber-700 bg-transparent dark:text-amber-400',
            'warning_glass' => 'backdrop-blur-md bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200',

            'danger_solid' => 'bg-destructive text-destructive-foreground shadow-xs',
            'danger_outline' => 'border-2 border-destructive text-destructive bg-transparent',
            'danger_glass' => 'backdrop-blur-md bg-destructive/10 border border-destructive/30 text-red-900 dark:text-red-200',

            'dark_solid' => 'bg-inverse text-foreground-inverse shadow-xs',
            'dark_outline' => 'border-2 border-line-4 text-foreground bg-transparent',
            'dark_glass' => 'backdrop-blur-md bg-card/70 border border-card-line text-foreground',

            'success_soft' => 'bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300',
            'warning_soft' => 'bg-amber-50 border border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300',
            'danger_soft' => 'bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-300',
            'dark_soft' => 'bg-surface border border-surface-line text-surface-foreground',
            default => 'bg-primary-50 border border-primary-200 text-primary-800 dark:bg-primary-950/40 dark:border-primary-800 dark:text-primary-300',
        };
    }

    public function getResolvedIcon(): ?string
    {
        $display = $this->stringProp('icon_display', 'auto');

        if ('none' === $display) {
            return null;
        }

        $icon = $this->get('icon');
        if (null !== $icon && '' !== $icon) {
            if (\is_string($icon)) {
                return \str_starts_with($icon, 'ti ') ? $icon : 'ti ti-' . $icon;
            }
            if (\is_array($icon) && isset($icon['icon']) && \is_string($icon['icon'])) {
                $name = $icon['icon'];

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
        return $this->nullableString('title');
    }

    public function getContent(): ?string
    {
        return $this->nullableString('content');
    }

    public function getLinkUrl(string $key = 'link'): ?string
    {
        $url = parent::getLinkUrl($key);

        return ('#' === $url || null === $url || '' === $url) ? null : $url;
    }

    public function getLinkTitle(string $key = 'link', string $fallbackKey = 'link_title'): ?string
    {
        $link = $this->get($key);
        if (\is_array($link) && isset($link['title']) && \is_string($link['title']) && '' !== \trim($link['title'])) {
            return \trim($link['title']);
        }

        return $this->nullableString($fallbackKey);
    }

    public function getButtonText(): ?string
    {
        $explicit = $this->nullableString('link_title');
        if (null !== $explicit) {
            return $explicit;
        }

        $link = $this->get('link');
        if (\is_array($link) && isset($link['title']) && \is_string($link['title']) && '' !== \trim($link['title'])) {
            return \trim($link['title']);
        }

        return null;
    }

    public function getTitleSizeClasses(): string
    {
        return match ($this->stringProp('title_size', 'md')) {
            'sm' => 'text-sm font-semibold',
            'lg' => 'text-lg font-bold',
            default => 'text-base font-semibold',
        };
    }

    public function getRoundedClasses(): string
    {
        return match ($this->stringProp('rounded', 'xl')) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            '2xl' => 'rounded-2xl',
            default => 'rounded-xl',
        };
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim(\sprintf(
            'relative flex items-start gap-x-3 p-4 sm:p-5 transition-all %s %s %s',
            $this->getRoundedClasses(),
            $this->getAlertClasses(),
            $extraBase,
        ));

        return parent::getWrapperClasses($base);
    }
}
