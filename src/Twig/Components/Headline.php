<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Headline', template: '@SuluPrelineBlocks/components/Headline.html.twig')]
final class Headline extends PrelineComponent
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
        return '' !== $this->getTitle();
    }

    public function getTitle(): string
    {
        return $this->nullableString('title') ?? $this->string('content', '');
    }

    public function getTag(): string
    {
        return $this->enumProp('tag', ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'], 'h2');
    }

    public function getTitleDecoration(): ?string
    {
        return $this->nullableStringProp('title_decoration');
    }

    public function getDecorationClasses(): string
    {
        return $this->getTitleDecorationClasses();
    }

    public function isTextStroke(): bool
    {
        return $this->is('text_stroke');
    }

    public function getTextStrokeClasses(): string
    {
        return $this->isTextStroke() ? '[-webkit-text-stroke:1.5px_currentColor] text-transparent' : '';
    }

    /**
     * @param string|array<int|string, mixed>|null|false ...$moreParts
     */
    public function getHeadingClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        $customSize = $this->nullableStringProp('size') ?? $this->nullableStringProp('title_size');
        $fallbackTagSize = PrelineClassMap::HEADLINE_TAG_SIZES[$this->getTag()] ?? PrelineClassMap::HEADLINE_TAG_SIZES['h2'];

        $sizeClasses = null !== $customSize
            ? PrelineClassMap::resolve(PrelineClassMap::HEADLINE_SIZES, $customSize, $fallbackTagSize)
            : $fallbackTagSize;

        return $this->classes(
            'tracking-tight',
            $sizeClasses,
            $this->getTitleColor(),
            $this->getDecorationClasses(),
            $this->getTextStrokeClasses(),
            $extraBase,
            ...$moreParts,
        );
    }

    public function getUrl(): ?string
    {
        return $this->getLinkUrl('link');
    }

    public function getImageAlt(string $key = 'image', string $fallbackKey = ''): string
    {
        return parent::getImageAlt($key, $fallbackKey);
    }
}
