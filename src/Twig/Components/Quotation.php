<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Quotation', template: '@SuluPrelineBlocks/components/Quotation.html.twig')]
final class Quotation extends AbstractPrelineComponent
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
        $url = $this->getLinkUrl('link');

        return ('#' === $url || null === $url || '' === $url) ? null : $url;
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
        $size = $this->stringProp('quote_size', 'md');
        $style = $this->getQuoteStyle();

        $sizeClasses = match ($size) {
            'sm' => 'text-sm sm:text-base',
            'lg' => 'text-xl sm:text-2xl',
            'xl' => 'text-2xl sm:text-3xl font-semibold',
            default => 'text-base sm:text-lg',
        };

        if ('large' === $style) {
            return 'text-xl sm:text-2xl lg:text-3xl font-medium tracking-tight text-foreground leading-relaxed';
        }

        return \sprintf('%s text-foreground leading-relaxed italic', $sizeClasses);
    }

    public function getBorderAccentClasses(): string
    {
        return match ($this->stringProp('accent_color', 'primary')) {
            'secondary' => 'border-s-4 border-secondary ps-4 sm:ps-6',
            'dark' => 'border-s-4 border-line-8 ps-4 sm:ps-6',
            'muted' => 'border-s-4 border-line-3 ps-4 sm:ps-6',
            default => 'border-s-4 border-primary ps-4 sm:ps-6',
        };
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

    public function getAlignClasses(): string
    {
        $align = $this->nullableStringProp('align');
        if (null === $align || 'left' === $align) {
            $align = $this->stringProp('text_align', $align ?? 'left');
        }

        return match ($align) {
            'center' => 'text-center items-center',
            'right', 'end' => 'text-end items-end',
            default => 'text-start items-start',
        };
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim('w-full flex flex-col ' . $this->getAlignClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
