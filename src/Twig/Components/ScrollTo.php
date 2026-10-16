<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('ScrollTo', template: '@SuluPrelineBlocks/components/ScrollTo.html.twig')]
final class ScrollTo extends PrelineComponent
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

        $clean = $this->ltrim($raw, "# \t\n\r\0\x0B");
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

    public function getAlignClasses(string $default = 'right', string $key = 'align'): string
    {
        return parent::getAlignClasses($default, $key);
    }

    public function getButtonClasses(): string
    {
        $mode = $this->getDisplayMode();
        $style = $this->stringProp('button_style', 'solid');
        $color = $this->stringProp('button_color', 'primary');
        $size = $this->stringProp('size', 'md');

        if ('totop_circle' === $mode || 'floating' === $mode) {
            $sizeClass = PrelineClassMap::resolve(
                PrelineClassMap::SCROLL_TO_CIRCLE_SIZES,
                $size,
                PrelineClassMap::SCROLL_TO_CIRCLE_SIZES['md'],
            );

            $colorClass = PrelineClassMap::resolve(
                PrelineClassMap::SCROLL_TO_CIRCLE_COLORS,
                $color,
                PrelineClassMap::SCROLL_TO_CIRCLE_COLORS['primary'],
            );

            return $this->classes(
                'inline-flex items-center justify-center',
                $sizeClass,
                'rounded-full shadow-lg transition-all duration-300 hover:-translate-y-0.5 focus:outline-hidden focus:ring-2 focus:ring-primary/50',
                $colorClass,
            );
        }

        return PropsResolver::buttonClasses([
            'variant' => $style,
            'color' => $color,
            'size' => $size,
        ]);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        if ('floating' === $this->getDisplayMode()) {
            return parent::getWrapperClasses('fixed bottom-6 end-6 z-50', $extraBase, ...$moreParts);
        }

        return parent::getWrapperClasses('w-full flex items-center', $this->getAlignClasses(), $extraBase, ...$moreParts);
    }
}
