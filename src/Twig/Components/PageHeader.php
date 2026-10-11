<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('PageHeader', template: '@SuluPrelineBlocks/components/PageHeader.html.twig')]
final class PageHeader
{
    public ?string $title = '';
    public ?string $headline = null;
    public ?string $summary = null;
    public ?string $content = null;
    public ?string $meta = null;

    public mixed $image = null;
    public mixed $hoverImage = null;
    public mixed $hover_image = null;

    public mixed $video = null;
    public mixed $hoverVideo = null;
    public mixed $hover_video = null;

    public mixed $icon = null;

    public bool $showBreadcrumbs = true;
    public bool $show_breadcrumbs = true;

    public string $layout = 'standard';
    public string $maxWidth = '85rem';
    public string $max_width = '85rem';
    public string $paddingY = 'default';
    public string $padding_y = 'default';

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getHeading(): string
    {
        if (null !== $this->headline && '' !== \trim($this->headline)) {
            return \trim($this->headline);
        }

        return $this->title ?? '';
    }

    public function getTitle(): string
    {
        return $this->getHeading();
    }

    public function getRawTitle(): string
    {
        return $this->title ?? '';
    }

    public function getHeadline(): ?string
    {
        return null !== $this->headline && '' !== \trim($this->headline) ? \trim($this->headline) : null;
    }

    public function getSummary(): ?string
    {
        return null !== $this->summary && '' !== \trim($this->summary) ? \trim($this->summary) : null;
    }

    public function getMeta(): ?string
    {
        return null !== $this->meta && '' !== \trim($this->meta) ? \trim($this->meta) : null;
    }

    public function getContent(): ?string
    {
        return null !== $this->content && '' !== \trim($this->content) ? \trim($this->content) : null;
    }

    public function isShowBreadcrumbs(): bool
    {
        return $this->showBreadcrumbs && $this->show_breadcrumbs;
    }

    public function getLayout(): string
    {
        return match ($this->layout) {
            'split' => 'split',
            'minimal' => 'minimal',
            default => 'standard',
        };
    }

    public function getImageUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->image);
    }

    public function getHoverImageUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->hoverImage ?? $this->hover_image);
    }

    public function getVideoUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->video);
    }

    public function getHoverVideoUrl(): ?string
    {
        return PropsResolver::resolveImageUrl($this->hoverVideo ?? $this->hover_video);
    }

    public function getIconClass(): ?string
    {
        return PropsResolver::resolveIcon($this->icon);
    }

    public function isImage(): bool
    {
        return null !== $this->getImageUrl();
    }

    public function isVideo(): bool
    {
        return null !== $this->getVideoUrl();
    }

    public function hasIcon(): bool
    {
        return null !== $this->getIconClass();
    }

    public function hasMedia(): bool
    {
        return $this->isImage() || $this->isVideo() || $this->hasIcon();
    }

    public function getMaxWidth(): string
    {
        return '85rem' !== $this->maxWidth ? $this->maxWidth : $this->max_width;
    }

    public function getPaddingY(): string
    {
        return 'default' !== $this->paddingY ? $this->paddingY : $this->padding_y;
    }
}
