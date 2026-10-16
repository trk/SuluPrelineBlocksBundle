<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('ScrollTo', template: '@SuluPrelineBlocks/components/ScrollTo.html.twig')]
final class ScrollTo extends AbstractPrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'target_id', 'icon'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'display_mode' => 'inline_button',
            'button_style' => 'solid',
            'button_color' => 'primary',
            'size' => 'md',
            'icon_position' => 'left',
            'align' => 'right',
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
            'linked' => true,
        ];
    }

    public function getTargetId(): string
    {
        $raw = $this->nullableString('target_id');
        if (null === $raw) {
            return 'top';
        }

        $clean = \ltrim($raw, "# \t\n\r\0\x0B");
        if ('' === $clean || 1 !== \preg_match('/^[a-zA-Z0-9_-]+$/', $clean)) {
            return 'top';
        }

        return $clean;
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
        $explicit = $this->getIconClass('icon');
        if (null !== $explicit) {
            return $explicit;
        }

        return $this->isTop() ? 'ti ti-arrow-up' : 'ti ti-arrow-down';
    }

    public function getDisplayMode(): string
    {
        return match ($this->stringProp('display_mode', 'inline_button')) {
            'totop_circle', 'circle' => 'totop_circle',
            'floating', 'fixed' => 'floating',
            default => 'inline_button',
        };
    }

    public function getAlignClasses(): string
    {
        return match ($this->stringProp('align', 'right')) {
            'left' => 'justify-start',
            'center' => 'justify-center',
            default => 'justify-end',
        };
    }

    public function getButtonClasses(): string
    {
        $mode = $this->getDisplayMode();
        $style = $this->stringProp('button_style', 'solid');
        $color = $this->stringProp('button_color', 'primary');
        $size = $this->stringProp('size', 'md');

        if ('totop_circle' === $mode || 'floating' === $mode) {
            $sizeClass = match ($size) {
                'sm' => 'size-9',
                'lg' => 'size-13',
                default => 'size-11',
            };

            $colorClass = match ($color) {
                'dark' => 'bg-inverse text-foreground-inverse hover:bg-inverse/90',
                'light' => 'bg-layer text-layer-foreground border border-layer-line hover:bg-layer-hover',
                'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover',
                default => 'bg-primary text-primary-foreground hover:bg-primary-hover',
            };

            return \sprintf(
                'inline-flex items-center justify-center %s rounded-full shadow-lg transition-all duration-300 hover:-translate-y-0.5 focus:outline-hidden focus:ring-2 focus:ring-primary/50 %s',
                $sizeClass,
                $colorClass,
            );
        }

        return PropsResolver::buttonClasses([
            'variant' => $style,
            'color' => $color,
            'size' => $size,
        ]);
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $mode = $this->getDisplayMode();

        if ('floating' === $mode) {
            $base = \trim('fixed bottom-6 end-6 z-50 ' . $extraBase);

            return parent::getWrapperClasses($base);
        }

        $base = \trim('w-full flex items-center ' . $this->getAlignClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
