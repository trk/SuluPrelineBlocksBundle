<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Quotation', template: '@SuluPrelineBlocks/components/Quotation.html.twig')]
final class Quotation
{
    public ?string $content = null;
    public ?string $author = null;
    public ?string $authorTitle = null;
    public ?string $author_title = null;
    public mixed $image = null;
    public mixed $link = null;

    public ?string $quoteStyle = null;
    public ?string $quote_style = null;
    public ?string $quoteSize = null;
    public ?string $quote_size = null;
    public ?string $accentColor = null;
    public ?string $accent_color = null;
    public ?string $align = null;

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

        if (null !== ($this->quoteStyle ?? $this->quote_style)) {
            $resolved['quote_style'] = $this->quoteStyle ?? $this->quote_style;
        }
        if (null !== ($this->quoteSize ?? $this->quote_size)) {
            $resolved['quote_size'] = $this->quoteSize ?? $this->quote_size;
        }
        if (null !== ($this->accentColor ?? $this->accent_color)) {
            $resolved['accent_color'] = $this->accentColor ?? $this->accent_color;
        }
        if (null !== $this->align) {
            $resolved['align'] = $this->align;
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

    public function getAuthor(): ?string
    {
        if (null !== $this->author && '' !== \trim($this->author)) {
            return \trim($this->author);
        }

        return null;
    }

    public function getAuthorTitle(): ?string
    {
        $title = $this->authorTitle ?? $this->author_title;
        if (null !== $title && '' !== \trim($title)) {
            return \trim($title);
        }

        return null;
    }

    public function getAvatarUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getAuthorUrl(): ?string
    {
        if (null === $this->link) {
            return null;
        }

        $url = PropsResolver::resolveUrl($this->link);

        return ('#' === $url || '' === $url) ? null : $url;
    }

    public function hasAuthor(): bool
    {
        return null !== $this->getAuthor() || null !== $this->getAuthorTitle() || null !== $this->getAvatarUrl();
    }

    public function getQuoteStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['quote_style'] ?? 'bordered';

        return match ($style) {
            'card' => 'card',
            'large' => 'large',
            'plain' => 'plain',
            default => 'bordered',
        };
    }

    public function getQuoteTextClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['quote_size'] ?? 'md';
        $style = $this->getQuoteStyle();

        $sizeClasses = match ($size) {
            'sm' => 'text-sm sm:text-base',
            'lg' => 'text-xl sm:text-2xl',
            'xl' => 'text-2xl sm:text-3xl font-semibold',
            default => 'text-base sm:text-lg',
        };

        if ('large' === $style) {
            return 'text-xl sm:text-2xl lg:text-3xl font-medium tracking-tight text-gray-900 dark:text-white leading-relaxed';
        }

        return \sprintf('%s text-gray-800 dark:text-neutral-200 leading-relaxed italic', $sizeClasses);
    }

    public function getBorderAccentClasses(): string
    {
        $props = $this->getResolvedProps();
        $color = $props['accent_color'] ?? 'primary';

        return match ($color) {
            'secondary' => 'border-s-4 border-secondary-600 dark:border-secondary-500 ps-4 sm:ps-6',
            'dark' => 'border-s-4 border-gray-900 dark:border-white ps-4 sm:ps-6',
            'muted' => 'border-s-4 border-gray-300 dark:border-neutral-700 ps-4 sm:ps-6',
            default => 'border-s-4 border-primary-600 dark:border-primary-500 ps-4 sm:ps-6',
        };
    }

    public function getContainerClasses(): string
    {
        $style = $this->getQuoteStyle();
        if ('card' === $style) {
            return 'p-6 sm:p-8 rounded-2xl bg-gray-50 border border-gray-200/80 shadow-2xs dark:bg-neutral-800/60 dark:border-neutral-700';
        }

        if ('bordered' === $style) {
            return $this->getBorderAccentClasses();
        }

        return '';
    }

    public function getAlignClasses(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['align'] ?? ($props['text_align'] ?? 'left');

        return match ($align) {
            'center' => 'text-center items-center',
            'right', 'end' => 'text-end items-end',
            default => 'text-start items-start',
        };
    }

    public function getWrapperClasses(): string
    {
        $base = 'w-full flex flex-col ' . $this->getAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
