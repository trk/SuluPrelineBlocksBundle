<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Button', template: '@SuluPrelineBlocks/components/Button.html.twig')]
final class Button
{
    public ?string $content = '';
    public mixed $link = '#';
    public ?string $linkTitle = null;
    public ?string $link_title = null;

    public ?string $variant = null;
    public ?string $size = null;
    public ?string $linkColor = null;
    public ?string $link_color = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?bool $fullWidth = null;
    public ?bool $full_width = null;

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
        if (null !== $this->variant) {
            $resolved['variant'] = $this->variant;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== ($this->linkColor ?? $this->link_color)) {
            $resolved['link_color'] = $this->linkColor ?? $this->link_color;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->fullWidth ?? $this->full_width)) {
            $resolved['full_width'] = $this->fullWidth ?? $this->full_width;
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

    public function getUrl(): string
    {
        return PropsResolver::resolveUrl($this->link);
    }

    public function getClasses(): string
    {
        return PropsResolver::buttonClasses($this->getResolvedProps());
    }

    public function getWrapperClasses(): string
    {
        return ElementTransform::wrapperClasses($this->getResolvedProps(), $this->getClasses());
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getLinkTitle(): ?string
    {
        return $this->linkTitle ?? $this->link_title;
    }
}
