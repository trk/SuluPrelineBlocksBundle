<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Section / Container component.
 */
#[AsTwigComponent('Section', template: '@SuluPrelineBlocks/components/Section.html.twig')]
final class Section extends AbstractPrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['rows'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'max_width' => '85rem',
            'padding_y' => 'default',
            'padding_x' => 'default',
            'bg_style' => 'transparent',
            'has_border' => false,
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
            'container' => true,
            'multiple' => true,
        ];
    }

    public function getSectionClasses(): string
    {
        $classes = [
            'relative',
            'w-full',
            'overflow-hidden',
        ];

        $classes[] = match ($this->stringProp('bg_style', 'transparent')) {
            'default' => 'bg-background text-foreground',
            'muted' => 'bg-background-1 text-foreground',
            'subtle' => 'bg-surface/70 text-foreground',
            'primary' => 'bg-primary text-primary-foreground dark:bg-primary-950/40 dark:text-foreground',
            'secondary' => 'bg-secondary text-secondary-foreground dark:bg-secondary-950/40 dark:text-foreground',
            'dark' => 'bg-inverse text-foreground-inverse',
            default => 'bg-transparent',
        };

        if ($this->boolProp('has_border', false)) {
            $classes[] = 'border-t border-b border-border';
        }

        $props = $this->props();
        $extra = [
            ElementTransform::margin($props),
            ElementTransform::position($props),
            ElementTransform::textAlign($props),
            ElementTransform::visibility($props),
            ElementTransform::animation($props),
            ElementTransform::customClass($props),
        ];

        foreach ($extra as $item) {
            if ('' !== $item) {
                $classes[] = $item;
            }
        }

        return \implode(' ', \array_filter($classes));
    }

    public function getContainerClasses(): string
    {
        $classes = [
            'mx-auto',
            'w-full',
        ];

        $classes[] = match ($this->stringProp('max_width', '85rem')) {
            '7xl', 'xlarge' => 'max-w-7xl',
            '5xl', 'large' => 'max-w-5xl',
            '3xl', 'medium' => 'max-w-3xl',
            'small' => 'max-w-xl',
            'full', '2xlarge' => 'max-w-full',
            default => 'max-w-[85rem]',
        };

        $classes[] = match ($this->stringProp('padding_x', 'default')) {
            'none' => 'px-0',
            default => 'px-4 sm:px-6 lg:px-8',
        };

        $classes[] = match ($this->stringProp('padding_y', 'default')) {
            'none' => 'py-0',
            'xs' => 'py-4 sm:py-6',
            'sm' => 'py-6 sm:py-8',
            'lg' => 'py-14 sm:py-20',
            'xl' => 'py-20 sm:py-28',
            default => 'py-10 sm:py-14',
        };

        return \implode(' ', $classes);
    }

    public function hasRows(): bool
    {
        return [] !== $this->getNormalizedRows();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedRows(): array
    {
        $raw = $this->items('rows');
        if ([] === $raw) {
            $propRows = $this->prop('rows', []);
            if (\is_array($propRows)) {
                foreach ($propRows as $item) {
                    if (\is_array($item)) {
                        /** @var array<string, mixed> $item */
                        $raw[] = $item;
                    }
                }
            }
        }

        $list = [];
        foreach ($raw as $item) {
            $rowProps = SuluDataNormalizer::extractProps($item);
            if ([] !== $rowProps) {
                $item = \array_merge($item, $rowProps);
            }
            $list[] = $item;
        }

        return $list;
    }
}
