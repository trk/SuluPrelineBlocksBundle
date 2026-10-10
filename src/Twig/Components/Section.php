<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible Section / Container component.
 */
#[AsTwigComponent('Section', template: '@SuluPrelineBlocks/components/Section.html.twig')]
final class Section
{
    public ?string $title = null;
    public ?string $subtitle = null;
    public string $title_align = 'center';
    public string $max_width = '85rem';
    public string $padding_y = 'default';
    public string $padding_x = 'default';
    public string $bg_style = 'transparent';

    public mixed $bg_image = null;

    public bool $has_border = false;
    public string $row_layout = 'halves';
    public string $gap = 'md';
    public ?string $content = null;

    /**
     * @var list<array<string, mixed>>|array<string|int, mixed>
     */
    public array $items = [];

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getSectionClasses(): string
    {
        $classes = [
            'relative',
            'w-full',
            'overflow-hidden',
        ];

        $classes[] = match ($this->bg_style) {
            'default' => 'bg-white dark:bg-neutral-900 text-gray-800 dark:text-neutral-200',
            'muted' => 'bg-gray-50 dark:bg-neutral-800 text-gray-800 dark:text-neutral-200',
            'subtle' => 'bg-gray-100/70 dark:bg-neutral-800/60 text-gray-800 dark:text-neutral-200',
            'primary' => 'bg-primary-600 text-white dark:bg-primary-950/40 dark:text-neutral-100',
            'secondary' => 'bg-secondary-600 text-white dark:bg-secondary-950/40 dark:text-neutral-100',
            'dark' => 'bg-neutral-900 text-white dark:bg-neutral-950',
            default => 'bg-transparent',
        };

        if ($this->has_border) {
            $classes[] = 'border-t border-b border-gray-200 dark:border-neutral-700';
        }

        return \implode(' ', $classes);
    }

    public function getContainerClasses(): string
    {
        $classes = [
            'mx-auto',
            'w-full',
        ];

        $classes[] = match ($this->max_width) {
            '7xl' => 'max-w-7xl',
            '5xl' => 'max-w-5xl',
            '3xl' => 'max-w-3xl',
            'full' => 'max-w-full',
            default => 'max-w-[85rem]',
        };

        $classes[] = match ($this->padding_x) {
            'none' => 'px-0',
            default => 'px-4 sm:px-6 lg:px-8',
        };

        $classes[] = match ($this->padding_y) {
            'none' => 'py-0',
            'xs' => 'py-4 sm:py-6',
            'sm' => 'py-6 sm:py-8',
            'lg' => 'py-14 sm:py-20',
            'xl' => 'py-20 sm:py-28',
            default => 'py-10 sm:py-14',
        };

        return \implode(' ', $classes);
    }

    public function getHeaderAlignmentClasses(): string
    {
        return match ($this->title_align) {
            'start' => 'text-start',
            'end' => 'text-end',
            default => 'text-center mx-auto',
        };
    }

    public function getBgImageUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->bg_image);
    }

    public function hasHeader(): bool
    {
        return (null !== $this->title && '' !== \trim($this->title))
            || (null !== $this->subtitle && '' !== \trim($this->subtitle));
    }

    public function hasContent(): bool
    {
        return null !== $this->content && '' !== \trim($this->content);
    }

    public function hasItems(): bool
    {
        return !empty($this->items);
    }

    public function isGridEnabled(): bool
    {
        return 'none' !== $this->row_layout && $this->hasItems();
    }
}
