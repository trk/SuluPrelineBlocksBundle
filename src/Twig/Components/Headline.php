<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Headline', template: '@SuluPrelineBlocks/components/Headline.html.twig')]
final class Headline extends AbstractPrelineComponent
{
    protected function defaults(): array
    {
        return [
            'tag' => 'h2',
            'text_align' => 'left',
            'title_color' => 'default',
            'title_decoration' => 'none',
            'title_text_stroke' => false,
        ];
    }

    protected function capabilities(): array
    {
        return [
            'text_stroke' => fn (): bool => $this->boolProp('title_text_stroke', false),
        ];
    }

    public function shouldRender(): bool
    {
        return '' !== \trim($this->getTitle());
    }

    public function getTitle(): string
    {
        $title = $this->string('title', '');
        if ('' !== $title) {
            return $title;
        }

        return $this->string('content', '');
    }

    public function getTag(): string
    {
        return $this->enumProp('tag', ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], 'h2');
    }

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->props());
    }

    public function getTitleColor(): string
    {
        return PropsResolver::titleColor($this->props());
    }

    public function getTitleDecoration(): ?string
    {
        return $this->nullableStringProp('title_decoration');
    }

    public function getDecorationClasses(): string
    {
        return match ($this->stringProp('title_decoration', 'none')) {
            'divider' => 'border-b-2 border-primary pb-2 inline-block',
            'bullet' => "flex items-center gap-3 before:content-[''] before:w-1.5 before:h-6 before:bg-primary before:rounded-full",
            'line' => "flex items-center gap-4 before:content-[''] before:flex-1 before:h-px before:bg-border after:content-[''] after:flex-1 after:h-px after:bg-border",
            default => '',
        };
    }

    public function isTextStroke(): bool
    {
        return $this->is('text_stroke');
    }

    public function getTextStrokeClasses(): string
    {
        return $this->isTextStroke() ? '[-webkit-text-stroke:1.5px_currentColor] text-transparent' : '';
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $customSize = $this->nullableStringProp('size') ?? $this->nullableStringProp('title_size');

        $sizeClasses = match ($customSize) {
            'sm' => 'text-base sm:text-lg font-semibold',
            'md' => 'text-xl sm:text-2xl font-bold',
            'lg' => 'text-2xl sm:text-3xl font-bold',
            'xl' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
            '2xl' => 'text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter',
            default => match ($this->getTag()) {
                'h1' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
                'h2' => 'text-2xl sm:text-3xl font-bold',
                'h3' => 'text-xl sm:text-2xl font-bold',
                'h4' => 'text-lg sm:text-xl font-semibold',
                'h5' => 'text-base sm:text-lg font-semibold',
                'h6' => 'text-sm sm:text-base font-semibold',
                default => 'text-2xl sm:text-3xl font-bold',
            },
        };

        $base = \trim(\sprintf(
            'tracking-tight %s %s %s %s %s',
            $sizeClasses,
            $this->getTitleColor(),
            $this->getDecorationClasses(),
            $this->getTextStrokeClasses(),
            $extraBase,
        ));

        return parent::getWrapperClasses($base);
    }

    public function getUrl(): ?string
    {
        $link = $this->get('link');
        if (null === $link || '' === $link) {
            return null;
        }

        return PropsResolver::resolveUrl($link);
    }

    public function getImageAlt(string $key = 'image', string $fallbackKey = ''): string
    {
        $media = $this->get($key);
        if (\is_array($media)) {
            $alt = $media['description'] ?? ($media['title'] ?? null);
            if (\is_string($alt) && '' !== \trim($alt)) {
                return \trim($alt);
            }
        } elseif (\is_object($media)) {
            if (\method_exists($media, 'getDescription')) {
                $desc = $media->getDescription();
                if (\is_string($desc) && '' !== \trim($desc)) {
                    return \trim($desc);
                }
            }
            if (\method_exists($media, 'getTitle')) {
                $title = $media->getTitle();
                if (\is_string($title) && '' !== \trim($title)) {
                    return \trim($title);
                }
            }
        }

        return '' !== $fallbackKey ? $this->string($fallbackKey, '') : '';
    }
}
