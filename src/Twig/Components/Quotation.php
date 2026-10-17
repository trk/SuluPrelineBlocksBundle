<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Quotation', template: '@SuluPrelineBlocks/components/Quotation.html.twig')]
final class Quotation extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'quote_style' => 'bordered',
            'quote_size' => 'md',
            'accent_color' => 'primary',
            'align' => 'left',
        ];
    }

    public function shouldRender(): bool
    {
        return $this->hasAny('content', 'quote', 'text');
    }

    public function getAuthor(): ?string
    {
        return $this->nullableString('author');
    }

    public function getAuthorTitle(): ?string
    {
        return $this->nullableString('author_title');
    }

    public function getAvatarUrl(): ?string
    {
        return $this->getImageUrl('image');
    }

    public function getAvatarFocusPointStyle(): ?string
    {
        return $this->getImageFocusPointStyle('image');
    }

    public function getAuthorUrl(): ?string
    {
        return $this->getLinkUrl('link');
    }

    public function hasAuthor(): bool
    {
        return null !== $this->getAuthor() || null !== $this->getAuthorTitle() || null !== $this->getAvatarUrl();
    }

    public function getQuoteStyle(): string
    {
        return $this->enumProp('quote_style', ['bordered', 'card', 'large', 'plain'], 'bordered');
    }

    public function getQuoteTextClasses(): string
    {
        if ('large' === $this->getQuoteStyle()) {
            return 'text-xl sm:text-2xl lg:text-3xl font-medium tracking-tight text-foreground leading-relaxed';
        }

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::QUOTE_SIZES,
            $this->stringProp('quote_size', 'md'),
            PrelineClassMap::QUOTE_SIZES['md'],
        );

        return $this->classes($sizeClasses, 'text-foreground leading-relaxed italic');
    }

    public function getBorderAccentClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::ACCENT_BORDER_COLORS,
            $this->stringProp('accent_color', 'primary'),
            PrelineClassMap::ACCENT_BORDER_COLORS['primary'],
        );
    }

    public function getContainerClasses(): string
    {
        $style = $this->getQuoteStyle();
        if ('card' === $style) {
            return 'p-6 sm:p-8 rounded-2xl bg-card border border-card-line shadow-2xs';
        }

        if ('bordered' === $style) {
            return $this->getBorderAccentClasses();
        }

        return '';
    }

    public function getAlignClasses(string $default = 'left', string $key = 'align'): string
    {
        $align = $this->nullableStringProp($key);
        if (null === $align || $default === $align) {
            $align = $this->stringProp('text_align', $align ?? $default);
        }

        return PrelineClassMap::resolve(
            PrelineClassMap::CONTENT_ALIGN_LOGICAL,
            $align,
            PrelineClassMap::CONTENT_ALIGN_LOGICAL[$default] ?? 'text-start items-start',
        );
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getBlockClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes(
            'relative w-full flex flex-col',
            $this->getAlignClasses(),
            $extraBase,
            ...$moreParts,
        );
    }
}
