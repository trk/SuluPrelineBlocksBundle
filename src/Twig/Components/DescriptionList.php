<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('DescriptionList', template: '@SuluPrelineBlocks/components/DescriptionList.html.twig')]
final class DescriptionList
{
    public ?string $title = null;

    /**
     * @var array<int|string, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public ?string $layout = null;
    public ?string $termWidth = null;
    public ?string $term_width = null;
    public ?bool $divider = null;
    public ?string $size = null;
    public ?string $termStyle = null;
    public ?string $term_style = null;

    public ?string $marginTop = null;
    public ?string $margin_top = null;
    public ?string $marginBottom = null;
    public ?string $margin_bottom = null;
    public ?string $id = null;
    public ?string $customClass = null;
    public ?string $custom_class = null;

    /**
     * @return array<string, mixed>
     */
    public function getResolvedProps(): array
    {
        $resolved = $this->props;
        if (isset($resolved['props']) && \is_array($resolved['props'])) {
            /** @var array<string, mixed> $nested */
            $nested = $resolved['props'];
            $resolved = \array_merge($nested, $resolved);
            unset($resolved['props']);
        }

        if (null !== $this->layout) {
            $resolved['layout'] = $this->layout;
        }
        if (null !== ($this->termWidth ?? $this->term_width)) {
            $resolved['term_width'] = $this->termWidth ?? $this->term_width;
        }
        if (null !== $this->divider) {
            $resolved['divider'] = $this->divider;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== ($this->termStyle ?? $this->term_style)) {
            $resolved['term_style'] = $this->termStyle ?? $this->term_style;
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

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items as $item) {
            if (\is_array($item)) {
                /** @var array<string, mixed> $itemArr */
                $itemArr = $item;
                $normalized[] = $itemArr;
            }
        }

        return $normalized;
    }

    public function getLayout(): string
    {
        $props = $this->getResolvedProps();
        $layout = $props['layout'] ?? 'horizontal';

        return match ($layout) {
            'stacked' => 'stacked',
            'striped' => 'striped',
            'bordered' => 'bordered',
            default => 'horizontal',
        };
    }

    public function isDivider(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['divider'] ?? true);
    }

    public function getSize(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['size'] ?? 'md';

        return match ($size) {
            'sm' => 'sm',
            'lg' => 'lg',
            default => 'md',
        };
    }

    public function getTermWidthClasses(): string
    {
        $props = $this->getResolvedProps();
        $width = $props['term_width'] ?? '1-3';

        return match ($width) {
            '1-4', '1/4' => 'sm:w-1/4',
            '1-2', '1/2' => 'sm:w-1/2',
            'auto' => 'sm:w-auto',
            default => 'sm:w-1/3',
        };
    }

    public function getDescWidthClasses(): string
    {
        $props = $this->getResolvedProps();
        $width = $props['term_width'] ?? '1-3';

        return match ($width) {
            '1-4', '1/4' => 'sm:w-3/4',
            '1-2', '1/2' => 'sm:w-1/2',
            'auto' => 'sm:flex-1',
            default => 'sm:w-2/3',
        };
    }

    public function getListClasses(): string
    {
        $layout = $this->getLayout();
        $divider = $this->isDivider();

        return match ($layout) {
            'bordered' => 'border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-200 dark:border-neutral-700 dark:divide-neutral-700',
            'striped' => 'divide-y divide-gray-200 dark:divide-neutral-700 border-t border-b border-gray-200 dark:border-neutral-700',
            default => $divider ? 'divide-y divide-gray-200 dark:divide-neutral-700' : 'space-y-4',
        };
    }

    public function getItemRowClasses(): string
    {
        $layout = $this->getLayout();
        $size = $this->getSize();

        $padding = match ($size) {
            'sm' => 'py-2 px-3',
            'lg' => 'py-4 px-4',
            default => 'py-3 px-3.5',
        };

        return match ($layout) {
            'horizontal' => \sprintf('%s sm:flex sm:items-baseline gap-x-4', $padding),
            'striped' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 even:bg-gray-50 dark:even:bg-neutral-800/50', $padding),
            'bordered' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 bg-white dark:bg-neutral-900', $padding),
            default => \sprintf('%s flex flex-col gap-y-1', $padding),
        };
    }

    public function getTermClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['term_style'] ?? 'bold';
        $layout = $this->getLayout();
        $termWidth = 'stacked' === $layout ? 'w-full' : $this->getTermWidthClasses();

        $font = match ($style) {
            'medium' => 'font-medium text-gray-800 dark:text-neutral-200',
            'muted' => 'font-normal text-gray-500 dark:text-neutral-400',
            'primary' => 'font-semibold text-blue-600 dark:text-blue-500',
            default => 'font-semibold text-gray-900 dark:text-white',
        };

        return \trim(\sprintf('%s %s shrink-0 inline-flex items-center gap-x-2 text-sm', $termWidth, $font));
    }

    public function getDescClasses(): string
    {
        $layout = $this->getLayout();
        $descWidth = 'stacked' === $layout ? 'w-full' : $this->getDescWidthClasses();

        return \trim(\sprintf('%s text-sm text-gray-600 dark:text-neutral-400', $descWidth));
    }

    public function getMarginClass(): string
    {
        $props = $this->getResolvedProps();

        $top = match ($props['margin_top'] ?? 'none') {
            'sm' => 'mt-4',
            'md' => 'mt-8',
            'lg' => 'mt-12',
            'xl' => 'mt-16',
            default => '',
        };

        $bottom = match ($props['margin_bottom'] ?? 'none') {
            'sm' => 'mb-4',
            'md' => 'mb-8',
            'lg' => 'mb-12',
            'xl' => 'mb-16',
            default => '',
        };

        return \trim($top . ' ' . $bottom);
    }

    public function getCustomId(): ?string
    {
        $props = $this->getResolvedProps();
        $id = $props['id'] ?? null;

        return \is_string($id) && '' !== \trim($id) ? \trim($id) : null;
    }

    public function getCustomClass(): string
    {
        $props = $this->getResolvedProps();
        $class = $props['custom_class'] ?? '';

        return \is_string($class) ? \trim($class) : '';
    }
}
