<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Headline', template: '@SuluPrelineBlocks/components/Headline.html.twig')]
final class Headline
{
    public ?string $title = '';
    public ?string $content = '';
    public mixed $link = null;
    public mixed $image = null;
    public mixed $icon = null;

    public function getTitle(): string
    {
        if (null !== $this->title && '' !== $this->title) {
            return $this->title;
        }

        return $this->content ?? '';
    }

    public ?string $tag = null;
    public ?string $size = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?string $titleColor = null;
    public ?string $title_color = null;
    public ?string $titleDecoration = null;
    public ?string $title_decoration = null;
    public ?bool $titleTextStroke = null;
    public ?bool $title_text_stroke = null;

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
        if (null !== $this->tag) {
            $resolved['tag'] = $this->tag;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->titleColor ?? $this->title_color)) {
            $resolved['title_color'] = $this->titleColor ?? $this->title_color;
        }
        if (null !== ($this->titleDecoration ?? $this->title_decoration)) {
            $resolved['title_decoration'] = $this->titleDecoration ?? $this->title_decoration;
        }
        if (null !== ($this->titleTextStroke ?? $this->title_text_stroke)) {
            $resolved['title_text_stroke'] = $this->titleTextStroke ?? $this->title_text_stroke;
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

    public function getTag(): string
    {
        $props = $this->getResolvedProps();
        $tag = isset($props['tag']) && \is_string($props['tag']) ? $props['tag'] : 'h2';

        return \in_array($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? $tag : 'h2';
    }

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->getResolvedProps());
    }

    public function getTitleColor(): string
    {
        return PropsResolver::titleColor($this->getResolvedProps());
    }

    public function getTitleDecoration(): ?string
    {
        $props = $this->getResolvedProps();
        $decoration = $props['title_decoration'] ?? null;

        return \is_string($decoration) && '' !== $decoration ? $decoration : null;
    }

    public function getDecorationClasses(): string
    {
        $props = $this->getResolvedProps();
        $decoration = $props['title_decoration'] ?? 'none';

        return match ($decoration) {
            'divider' => 'border-b-2 border-primary-600 dark:border-primary-500 pb-2 inline-block',
            'bullet' => "flex items-center gap-3 before:content-[''] before:w-1.5 before:h-6 before:bg-primary-600 before:rounded-full",
            'line' => "flex items-center gap-4 before:content-[''] before:flex-1 before:h-px before:bg-gray-200 dark:before:bg-neutral-700 after:content-[''] after:flex-1 after:h-px after:bg-gray-200 dark:after:bg-neutral-700",
            default => '',
        };
    }

    public function isTextStroke(): bool
    {
        $props = $this->getResolvedProps();

        return true === ($props['title_text_stroke'] ?? false);
    }

    public function getTextStrokeClasses(): string
    {
        return $this->isTextStroke() ? '[-webkit-text-stroke:1.5px_currentColor] text-transparent' : '';
    }

    public function getWrapperClasses(): string
    {
        $props = $this->getResolvedProps();
        $customSize = $props['size'] ?? ($props['title_size'] ?? null);

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
            'tracking-tight %s %s %s %s',
            $sizeClasses,
            $this->getTitleColor(),
            $this->getDecorationClasses(),
            $this->getTextStrokeClasses(),
        ));

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    public function getUrl(): ?string
    {
        if (null === $this->link || '' === $this->link) {
            return null;
        }

        return PropsResolver::resolveUrl($this->link);
    }

    public function getImageUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getIconClass(): ?string
    {
        return PropsResolver::resolveIcon($this->icon);
    }
}
