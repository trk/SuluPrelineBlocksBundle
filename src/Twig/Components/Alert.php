<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Alert', template: '@SuluPrelineBlocks/components/Alert.html.twig')]
final class Alert extends PrelineComponent
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
        $key = $this->getAlertStyle() . '_' . $this->getAlertVariant();

        return PrelineClassMap::resolve(
            PrelineClassMap::ALERT_VARIANTS,
            $key,
            PrelineClassMap::ALERT_VARIANTS['info_soft'],
        );
    }

    public function getResolvedIcon(): ?string
    {
        $display = $this->stringProp('icon_display', 'auto');

        if ('none' === $display) {
            return null;
        }

        $icon = PropsResolver::resolveIcon($this->get('icon'));
        if (null !== $icon) {
            return $icon;
        }

        if ('custom' === $display) {
            return null;
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::ALERT_DEFAULT_ICONS,
            $this->getAlertStyle(),
            PrelineClassMap::ALERT_DEFAULT_ICONS['info'],
        );
    }

    public function getTitle(): ?string
    {
        return $this->nullableString('title');
    }

    public function getContent(): ?string
    {
        return $this->nullableString('content');
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
        return parent::getLinkTitle('link', 'link_title');
    }

    public function getTitleSizeClasses(string $default = 'md', string $preset = 'alert'): string
    {
        return parent::getTitleSizeClasses($default, $preset);
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
