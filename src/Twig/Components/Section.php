<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Section / Container component.
 */
#[AsTwigComponent('Section', template: '@SuluPrelineBlocks/components/Section.html.twig')]
final class Section
{
    public string $max_width = '85rem';
    public string $padding_y = 'default';
    public string $padding_x = 'default';
    public string $bg_style = 'transparent';
    public bool $has_border = false;

    /**
     * @var list<array<string, mixed>>|array<string|int, mixed>
     */
    public array $rows = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    /**
     * @var array<string, mixed>
     */
    public array $context = [];

    private function resolveStringProp(string $key, string $current, string $default): string
    {
        if ($current !== $default) {
            return $current;
        }

        $val = $this->props[$key] ?? null;

        return \is_string($val) && '' !== $val ? $val : $current;
    }

    private function resolveBoolProp(string $key, bool $current, bool $default = false): bool
    {
        if ($current !== $default) {
            return $current;
        }

        if (\array_key_exists($key, $this->props)) {
            $val = $this->props[$key];
            if (\is_bool($val)) {
                return $val;
            }
            if (\is_string($val)) {
                return \in_array(\strtolower($val), ['true', '1', 'yes'], true);
            }
            if (\is_int($val)) {
                return 1 === $val;
            }
        }

        return $current;
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->props);
    }

    /**
     * @return array<string, string|bool>
     */
    public function getCustomAttributes(): array
    {
        return ElementTransform::resolveCustomAttributes($this->props);
    }

    public function getScopedCss(): ?string
    {
        return ElementTransform::resolveScopedCss($this->props);
    }

    public function getSectionClasses(): string
    {
        $classes = [
            'relative',
            'w-full',
            'overflow-hidden',
        ];

        $bgStyle = $this->resolveStringProp('bg_style', $this->bg_style, 'transparent');

        $classes[] = match ($bgStyle) {
            'default' => 'bg-background text-foreground',
            'muted' => 'bg-background-1 text-foreground',
            'subtle' => 'bg-surface/70 text-foreground',
            'primary' => 'bg-primary text-primary-foreground dark:bg-primary-950/40 dark:text-foreground',
            'secondary' => 'bg-secondary text-secondary-foreground dark:bg-secondary-950/40 dark:text-foreground',
            'dark' => 'bg-inverse text-foreground-inverse',
            default => 'bg-transparent',
        };

        if ($this->resolveBoolProp('has_border', $this->has_border, false)) {
            $classes[] = 'border-t border-b border-border';
        }

        $extra = [
            ElementTransform::margin($this->props),
            ElementTransform::position($this->props),
            ElementTransform::textAlign($this->props),
            ElementTransform::visibility($this->props),
            ElementTransform::animation($this->props),
            ElementTransform::customClass($this->props),
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

        $maxWidth = $this->resolveStringProp('max_width', $this->max_width, '85rem');
        $classes[] = match ($maxWidth) {
            '7xl', 'xlarge' => 'max-w-7xl',
            '5xl', 'large' => 'max-w-5xl',
            '3xl', 'medium' => 'max-w-3xl',
            'small' => 'max-w-xl',
            'full', '2xlarge' => 'max-w-full',
            default => 'max-w-[85rem]',
        };

        $paddingX = $this->resolveStringProp('padding_x', $this->padding_x, 'default');
        $classes[] = match ($paddingX) {
            'none' => 'px-0',
            default => 'px-4 sm:px-6 lg:px-8',
        };

        $paddingY = $this->resolveStringProp('padding_y', $this->padding_y, 'default');
        $classes[] = match ($paddingY) {
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
        return !empty($this->getNormalizedRows());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedRows(): array
    {
        $raw = !empty($this->rows) ? $this->rows : ($this->props['rows'] ?? []);
        if (!\is_array($raw)) {
            return [];
        }

        $list = [];
        foreach ($raw as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $item */
                $rowProps = \Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer::extractProps($item);
                if ([] !== $rowProps) {
                    $item = \array_merge($item, $rowProps);
                }
                $list[] = $item;
            }
        }

        return $list;
    }
}
