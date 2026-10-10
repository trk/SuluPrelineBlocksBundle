<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Card', template: '@SuluPrelineBlocks/components/Card.html.twig')]
final class Card
{
    public ?string $title = null;
    public ?string $badge = null;
    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $badges = [];
    public ?string $badgePosition = null;
    public ?string $badge_position = null;
    public ?string $badgeStyle = null;
    public ?string $badge_style = null;
    public ?string $badgeColor = null;
    public ?string $badge_color = null;
    public ?string $meta = null;
    public ?string $content = null;
    public mixed $image = null;
    public mixed $video = null;
    public mixed $icon = null;
    public mixed $cardLink = null;
    public mixed $card_link = null;
    public mixed $link = null;
    public ?string $linkTitle = null;
    public ?string $link_title = null;
    public ?string $linkText = null;
    public ?string $link_text = null;
    public ?string $buttonStyle = null;
    public ?string $button_style = null;
    public ?string $buttonColor = null;
    public ?string $button_color = null;
    public ?string $buttonSize = null;
    public ?string $button_size = null;

    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $buttons = [];

    /**
     * @var list<array<string, mixed>>|array<string, mixed>
     */
    public array $elements = [];

    public mixed $hoverImage = null;
    public mixed $hover_image = null;
    public mixed $hoverVideo = null;
    public mixed $hover_video = null;

    public ?string $cardVariant = null;
    public ?string $card_variant = null;
    public ?string $layout = null;
    public ?string $mediaAlign = null;
    public ?string $media_align = null;
    public ?string $bgColor = null;
    public ?string $bg_color = null;
    public ?string $textAlign = null;
    public ?string $text_align = null;
    public ?string $titleColor = null;
    public ?string $title_color = null;
    public ?string $mediaType = null;
    public ?string $media_type = null;

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
        if (null !== ($this->cardVariant ?? $this->card_variant)) {
            $resolved['card_variant'] = $this->cardVariant ?? $this->card_variant;
        }
        if (null !== ($this->mediaAlign ?? $this->media_align)) {
            $resolved['media_align'] = $this->mediaAlign ?? $this->media_align;
        }
        if (null !== $this->layout) {
            $resolved['layout'] = $this->layout;
        }
        if (null !== ($this->bgColor ?? $this->bg_color)) {
            $resolved['bg_color'] = $this->bgColor ?? $this->bg_color;
        }
        if (null !== ($this->textAlign ?? $this->text_align)) {
            $resolved['text_align'] = $this->textAlign ?? $this->text_align;
        }
        if (null !== ($this->titleColor ?? $this->title_color)) {
            $resolved['title_color'] = $this->titleColor ?? $this->title_color;
        }
        if (null !== ($this->mediaType ?? $this->media_type)) {
            $resolved['media_type'] = $this->mediaType ?? $this->media_type;
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
        if (null !== ($this->buttonStyle ?? $this->button_style)) {
            $resolved['button_style'] = $this->buttonStyle ?? $this->button_style;
        }
        if (null !== ($this->buttonColor ?? $this->button_color)) {
            $resolved['button_color'] = $this->buttonColor ?? $this->button_color;
        }
        if (null !== ($this->buttonSize ?? $this->button_size)) {
            $resolved['button_size'] = $this->buttonSize ?? $this->button_size;
        }
        if (null !== ($this->badgeStyle ?? $this->badge_style)) {
            $resolved['badge_style'] = $this->badgeStyle ?? $this->badge_style;
        }
        if (null !== ($this->badgeColor ?? $this->badge_color)) {
            $resolved['badge_color'] = $this->badgeColor ?? $this->badge_color;
        }
        if (null !== ($this->badgePosition ?? $this->badge_position)) {
            $resolved['badge_position'] = $this->badgePosition ?? $this->badge_position;
        }

        return $resolved;
    }

    public function getMediaAlign(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['media_align'] ?? ($props['layout'] ?? 'top');
        if ('horizontal' === $align) {
            return 'left';
        }

        return \in_array($align, ['top', 'bottom', 'left', 'right', 'between', 'overlay'], true)
            ? (string) $align
            : 'top';
    }

    public function isOverlay(): bool
    {
        return 'overlay' === $this->getMediaAlign();
    }

    public function isHorizontal(): bool
    {
        return \in_array($this->getMediaAlign(), ['left', 'right'], true);
    }

    public function getMediaGridWidthClasses(): string
    {
        $props = $this->getResolvedProps();
        $width = $props['media_width'] ?? '1-2';

        return match ($width) {
            '1-3' => 'md:w-1/3',
            '2-5' => 'md:w-2/5',
            default => 'md:w-1/2',
        };
    }

    public function getContentGridWidthClasses(): string
    {
        $props = $this->getResolvedProps();
        $width = $props['media_width'] ?? '1-2';

        return match ($width) {
            '1-3' => 'md:w-2/3',
            '2-5' => 'md:w-3/5',
            default => 'md:w-1/2',
        };
    }

    public function getMediaAspectRatioClass(): string
    {
        $props = $this->getResolvedProps();
        $aspect = $props['media_aspect_ratio'] ?? ($props['aspect_ratio'] ?? '16-9');

        return match ($aspect) {
            '4-3', '4/3' => 'aspect-4/3',
            '1-1', '1/1', 'square' => 'aspect-square',
            'auto' => 'aspect-auto',
            default => 'aspect-video',
        };
    }

    public function getMetaAlign(): string
    {
        $props = $this->getResolvedProps();
        $metaAlign = $props['meta_align'] ?? 'above-title';

        return \in_array($metaAlign, ['above-title', 'below-title', 'below-content'], true)
            ? (string) $metaAlign
            : 'above-title';
    }

    public function getTitleElement(): string
    {
        $props = $this->getResolvedProps();
        $el = $props['title_element'] ?? ($props['tag'] ?? 'h3');

        return \in_array($el, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'], true) ? (string) $el : 'h3';
    }

    public function getTitleSizeClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['title_size'] ?? ($props['size'] ?? 'md');

        return match ($size) {
            'sm' => 'text-base sm:text-lg font-semibold',
            'lg' => 'text-xl sm:text-2xl font-bold',
            'xl' => 'text-2xl sm:text-3xl font-extrabold tracking-tight',
            default => 'text-lg sm:text-xl font-bold',
        };
    }

    public function getTitleDecorationClasses(): string
    {
        $props = $this->getResolvedProps();
        $decoration = $props['title_decoration'] ?? 'none';

        return match ($decoration) {
            'underline' => 'underline decoration-primary-500 decoration-2 underline-offset-4',
            default => '',
        };
    }

    public function getMetaBadgeClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['meta_badge_style'] ?? 'pill';

        return match ($style) {
            'primary_pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary-100 text-primary-800 rounded-full dark:bg-primary-950/40 dark:text-primary-300',
            'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full dark:bg-neutral-800 dark:text-neutral-300',
        };
    }

    public function getBadgeClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['badge_style'] ?? 'glass';

        return match ($style) {
            'primary' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-primary-600 text-white shadow-xs',
            'success' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-emerald-600 text-white shadow-xs',
            'dark' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-gray-900 text-white shadow-xs dark:bg-white dark:text-gray-900',
            default => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-white/90 text-gray-800 backdrop-blur-md shadow-xs dark:bg-neutral-900/90 dark:text-neutral-200',
        };
    }

    public function getBadgePosition(): string
    {
        $props = $this->getResolvedProps();
        $pos = $props['badge_position'] ?? 'top-left';

        return match ($pos) {
            'top-right', 'top_right' => 'top-right',
            'bottom-left', 'bottom_left' => 'bottom-left',
            'bottom-right', 'bottom_right' => 'bottom-right',
            'inline' => 'inline',
            default => 'top-left',
        };
    }

    public function getBadgePositionClasses(): string
    {
        return match ($this->getBadgePosition()) {
            'top-right' => 'absolute top-3 end-3 z-10 flex flex-wrap gap-1.5',
            'bottom-left' => 'absolute bottom-3 start-3 z-10 flex flex-wrap gap-1.5',
            'bottom-right' => 'absolute bottom-3 end-3 z-10 flex flex-wrap gap-1.5',
            'inline' => '',
            default => 'absolute top-3 start-3 z-10 flex flex-wrap gap-1.5',
        };
    }

    public function getCardPaddingClasses(): string
    {
        $props = $this->getResolvedProps();
        $padding = $props['padding'] ?? 'normal';

        return match ($padding) {
            'none' => 'p-0',
            'compact' => 'p-4 sm:p-5',
            'spacious' => 'p-6 sm:p-8',
            default => 'p-5 sm:p-6',
        };
    }

    public function getRoundedClasses(): string
    {
        $props = $this->getResolvedProps();
        $variant = $props['card_variant'] ?? ($props['variant'] ?? 'bordered');
        if ('group' === $variant) {
            return 'rounded-none';
        }

        $rounded = $props['rounded'] ?? 'xl';

        return match ($rounded) {
            'none' => 'rounded-none',
            'md' => 'rounded-md',
            '2xl' => 'rounded-2xl',
            '3xl' => 'rounded-3xl',
            'full' => 'rounded-full',
            default => 'rounded-xl',
        };
    }

    public function getLinkUrl(): ?string
    {
        $link = $this->link ?? ($this->cardLink ?? $this->card_link);
        if (null !== $link) {
            $url = PropsResolver::resolveUrl($link);
            if ('#' !== $url && '' !== $url) {
                return $url;
            }
        }

        $buttonList = [] !== $this->buttons ? $this->buttons : $this->elements;
        if ([] !== $buttonList) {
            foreach ($buttonList as $button) {
                if (\is_array($button) && ('badge' !== ($button['type'] ?? '')) && isset($button['link'])) {
                    $url = PropsResolver::resolveUrl($button['link']);
                    if ('#' !== $url && '' !== $url) {
                        return $url;
                    }
                }
            }
        }

        return null;
    }

    public function getButtonText(): ?string
    {
        $text = $this->linkTitle ?? ($this->link_title ?? ($this->linkText ?? $this->link_text));
        if (null !== $text && '' !== \trim($text)) {
            return \trim($text);
        }

        $link = $this->link ?? ($this->cardLink ?? $this->card_link);
        if (\is_array($link) && isset($link['title']) && \is_string($link['title']) && '' !== \trim($link['title'])) {
            return \trim($link['title']);
        }

        $buttonList = [] !== $this->buttons ? $this->buttons : $this->elements;
        if ([] !== $buttonList) {
            foreach ($buttonList as $button) {
                if (\is_array($button) && ('badge' !== ($button['type'] ?? ''))) {
                    $content = $button['title'] ?? ($button['content'] ?? null);
                    if (\is_string($content) && '' !== \trim($content)) {
                        return \trim($content);
                    }
                }
            }
        }

        return null;
    }

    public function getButtonStyleClasses(): string
    {
        $props = $this->getResolvedProps();
        $style = $this->buttonStyle ?? ($this->button_style ?? ($props['button_style'] ?? 'primary'));

        return match ($style) {
            'secondary' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700',
            'outline' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-primary-600 text-primary-600 hover:bg-primary-50 dark:border-primary-400 dark:text-primary-400 dark:hover:bg-primary-950/30 transition-colors focus:outline-hidden',
            'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-primary-600 hover:underline focus:outline-hidden dark:text-primary-400',
            default => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary-600 text-white hover:bg-primary-700 focus:outline-hidden focus:ring-2 focus:ring-primary-500',
        };
    }

    public function getResolvedCardUrl(): ?string
    {
        $props = $this->getResolvedProps();
        $linkCard = $props['link_card'] ?? 'none';
        $url = $this->getLinkUrl();

        if (null === $url) {
            return null;
        }

        if (\in_array($linkCard, ['card', 'card_link', 'first_button'], true)) {
            return $url;
        }

        // When linkCard is none or button, but no visible button text was provided, clicking the card navigates
        if ('none' === $linkCard && null === $this->getButtonText()) {
            return $url;
        }

        return null;
    }

    public function hasMedia(): bool
    {
        return (null !== $this->getActiveImageUrl() && $this->isImage())
            || (null !== $this->getActiveVideoUrl() && $this->isVideo());
    }

    public function getCardClasses(): string
    {
        $props = $this->getResolvedProps();
        $variant = $props['card_variant'] ?? ($props['variant'] ?? 'bordered');
        $bg = PropsResolver::bgColor($props);
        $rounded = $this->getRoundedClasses();
        $mediaAlign = $this->getMediaAlign();

        $base = 'w-full flex overflow-hidden transition-all';

        if ('group' === $variant) {
            return \trim(\sprintf('%s rounded-none border-0 shadow-none flex-col flex-1 %s', $base, $bg));
        }

        $variantClasses = match ($variant) {
            'shadow' => 'shadow-md hover:shadow-lg border border-gray-100 dark:border-neutral-800',
            'flat' => 'border-0',
            'interactive' => 'border border-gray-200 dark:border-neutral-700 shadow-2xs hover:-translate-y-1 hover:shadow-lg transition-all duration-300',
            'glass' => 'backdrop-blur-md bg-white/70 border border-white/20 dark:bg-neutral-900/70 dark:border-neutral-700/30 shadow-lg',
            default => 'border border-gray-200 shadow-2xs dark:border-neutral-700',
        };

        if ('overlay' === $mediaAlign) {
            return \trim(\sprintf('%s %s relative text-white shadow-md', $base, $rounded));
        }

        $flexDirection = match ($mediaAlign) {
            'left' => 'flex-col md:flex-row',
            'right' => 'flex-col md:flex-row-reverse',
            default => 'flex-col',
        };

        return \trim(\sprintf('%s %s %s %s %s', $base, $rounded, $variantClasses, $flexDirection, $bg));
    }

    public function getTextAlign(): string
    {
        return PropsResolver::textAlign($this->getResolvedProps());
    }

    public function getTitleColor(): string
    {
        if ($this->isOverlay()) {
            return 'text-white';
        }

        return PropsResolver::titleColor($this->getResolvedProps());
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

    public function getMediaType(): ?string
    {
        $props = $this->getResolvedProps();
        $forced = isset($props['media_type']) && \is_string($props['media_type'])
            ? $props['media_type']
            : 'auto';

        if ('none' === $forced) {
            return null;
        }

        if ('video' === $forced && null !== $this->getVideoUrl()) {
            return 'video';
        }

        if ('image' === $forced && null !== $this->getImageUrl()) {
            return 'image';
        }

        if (null !== $this->getImageUrl()) {
            return 'image';
        }

        if (null !== $this->getVideoUrl()) {
            return 'video';
        }

        return null;
    }

    public function isImage(): bool
    {
        return 'image' === $this->getMediaType();
    }

    public function isVideo(): bool
    {
        return 'video' === $this->getMediaType();
    }

    public function getActiveImageUrl(): ?string
    {
        return $this->isImage() ? $this->getImageUrl() : null;
    }

    public function getActiveHoverImageUrl(): ?string
    {
        return $this->isImage() && null !== $this->getImageUrl() ? $this->getHoverImageUrl() : null;
    }

    public function getActiveVideoUrl(): ?string
    {
        return $this->isVideo() ? $this->getVideoUrl() : null;
    }

    public function getActiveHoverVideoUrl(): ?string
    {
        return $this->isVideo() && null !== $this->getVideoUrl() ? $this->getHoverVideoUrl() : null;
    }

    public function getWrapperClasses(): string
    {
        $base = 'group relative w-full ' . $this->getCardClasses() . ' ' . $this->getTextAlign();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedBadges(): array
    {
        $props = $this->getResolvedProps();
        $defaultStyle = isset($props['badge_style']) && \is_string($props['badge_style']) && '' !== \trim($props['badge_style']) ? $props['badge_style'] : 'glass';
        $defaultColor = isset($props['badge_color']) && \is_string($props['badge_color']) && '' !== \trim($props['badge_color']) ? $props['badge_color'] : 'primary';

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $badgeItems */
        $badgeItems = [];
        if ([] !== $this->badges) {
            foreach ($this->badges as $item) {
                if (\is_array($item)) {
                    $badgeItems[] = $item;
                }
            }
        } elseif ([] !== $this->elements) {
            foreach ($this->elements as $item) {
                if (\is_array($item) && 'badge' === ($item['type'] ?? '')) {
                    $badgeItems[] = $item;
                }
            }
        }

        if ([] !== $badgeItems) {
            foreach ($badgeItems as $item) {
                $itemStyle = isset($item['badge_style']) && \is_string($item['badge_style']) && 'inherit' !== $item['badge_style'] && '' !== \trim($item['badge_style'])
                    ? $item['badge_style']
                    : $defaultStyle;

                $itemColor = isset($item['badge_color']) && \is_string($item['badge_color']) && 'inherit' !== $item['badge_color'] && '' !== \trim($item['badge_color'])
                    ? $item['badge_color']
                    : $defaultColor;

                $itemSize = isset($item['size']) && \is_string($item['size']) && 'inherit' !== $item['size'] && '' !== \trim($item['size'])
                    ? $item['size']
                    : 'xs';

                $itemRounded = isset($item['rounded']) && \is_string($item['rounded']) && 'inherit' !== $item['rounded'] && '' !== \trim($item['rounded'])
                    ? $item['rounded']
                    : 'pill';

                $title = '';
                if (isset($item['title']) && \is_string($item['title'])) {
                    $title = \trim($item['title']);
                } elseif (isset($item['text']) && \is_string($item['text'])) {
                    $title = \trim($item['text']);
                }

                if ('' === $title && !isset($item['icon'])) {
                    continue;
                }

                $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
                $iconAlign = isset($item['icon_align']) && 'right' === $item['icon_align'] ? 'right' : 'left';
                $hasDot = !empty($item['has_dot']);
                $link = $item['link'] ?? null;
                $url = PropsResolver::resolveUrl($link);
                $isClickable = '#' !== $url && '' !== $url;

                $classes = PropsResolver::badgeClasses([
                    'badge_style' => $itemStyle,
                    'badge_color' => $itemColor,
                    'size' => $itemSize,
                    'rounded' => $itemRounded,
                ], $defaultStyle, $defaultColor, $itemSize, $itemRounded);

                $result[] = [
                    'title' => $title,
                    'url' => $url,
                    'is_clickable' => $isClickable,
                    'link_title' => isset($item['link_title']) && \is_string($item['link_title']) ? $item['link_title'] : null,
                    'classes' => $classes,
                    'icon' => $icon,
                    'icon_align' => $iconAlign,
                    'has_dot' => $hasDot,
                ];
            }
        }

        // Backward compatibility: If no badges block items, check single $this->badge string
        if ([] === $result && null !== $this->badge && '' !== \trim($this->badge)) {
            $classes = PropsResolver::badgeClasses([
                'badge_style' => $defaultStyle,
                'badge_color' => $defaultColor,
                'size' => 'xs',
                'rounded' => 'pill',
            ], $defaultStyle, $defaultColor, 'xs', 'pill');

            $result[] = [
                'title' => \trim($this->badge),
                'url' => '#',
                'is_clickable' => false,
                'link_title' => null,
                'classes' => $classes,
                'icon' => null,
                'icon_align' => 'left',
                'has_dot' => false,
            ];
        }

        return $result;
    }

    public function hasBadges(): bool
    {
        return [] !== $this->getNormalizedBadges();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedButtons(): array
    {
        $props = $this->getResolvedProps();
        $defaultStyle = isset($props['button_style']) && \is_string($props['button_style']) ? $props['button_style'] : 'primary';
        $defaultColor = isset($props['button_color']) && \is_string($props['button_color']) ? $props['button_color'] : 'primary';
        $defaultSize = isset($props['button_size']) && \is_string($props['button_size']) ? $props['button_size'] : 'sm';

        /** @var list<array<string, mixed>> $result */
        $result = [];

        /** @var list<array<string, mixed>> $buttonItems */
        $buttonItems = [];
        if ([] !== $this->buttons) {
            foreach ($this->buttons as $item) {
                if (\is_array($item)) {
                    $buttonItems[] = $item;
                }
            }
        } elseif ([] !== $this->elements) {
            foreach ($this->elements as $item) {
                if (\is_array($item) && ('button' === ($item['type'] ?? 'button') || 'badge' !== ($item['type'] ?? ''))) {
                    $buttonItems[] = $item;
                }
            }
        }

        if ([] !== $buttonItems) {
            foreach ($buttonItems as $item) {
                $itemStyle = isset($item['button_style']) && \is_string($item['button_style']) && 'inherit' !== $item['button_style'] && '' !== \trim($item['button_style'])
                    ? $item['button_style']
                    : $defaultStyle;

                $itemColor = isset($item['button_color']) && \is_string($item['button_color']) && 'inherit' !== $item['button_color'] && '' !== \trim($item['button_color'])
                    ? $item['button_color']
                    : $defaultColor;

                $itemSize = isset($item['size']) && \is_string($item['size']) && 'inherit' !== $item['size'] && '' !== \trim($item['size'])
                    ? $item['size']
                    : $defaultSize;

                $title = '';
                if (isset($item['title']) && \is_string($item['title'])) {
                    $title = \trim($item['title']);
                } elseif (isset($item['content']) && \is_string($item['content'])) {
                    $title = \trim($item['content']);
                }

                $link = $item['link'] ?? null;
                $url = PropsResolver::resolveUrl($link);
                $icon = isset($item['icon']) ? PropsResolver::resolveIcon($item['icon']) : null;
                $iconAlign = isset($item['icon_align']) && 'right' === $item['icon_align'] ? 'right' : 'left';

                $classes = PropsResolver::buttonClasses([
                    'variant' => $itemStyle,
                    'color' => $itemColor,
                    'size' => $itemSize,
                ]);

                $result[] = [
                    'title' => $title,
                    'content' => $title,
                    'url' => $url,
                    'link' => $link,
                    'link_title' => isset($item['link_title']) && \is_string($item['link_title']) ? $item['link_title'] : null,
                    'classes' => $classes,
                    'icon' => $icon,
                    'icon_align' => $iconAlign,
                    'button_style' => $itemStyle,
                    'button_color' => $itemColor,
                ];
            }
        }

        // Backward compatibility: If no buttons block items, check single button / link
        if ([] === $result) {
            $singleLink = $this->link ?? ($this->cardLink ?? $this->card_link);
            $linkUrl = null !== $singleLink ? PropsResolver::resolveUrl($singleLink) : null;
            $buttonText = $this->getButtonText();

            if (null !== $linkUrl && '#' !== $linkUrl && '' !== $linkUrl && null !== $buttonText && '' !== $buttonText) {
                $classes = PropsResolver::buttonClasses([
                    'variant' => $defaultStyle,
                    'color' => $defaultColor,
                    'size' => $defaultSize,
                ]);

                $result[] = [
                    'title' => $buttonText,
                    'content' => $buttonText,
                    'url' => $linkUrl,
                    'link' => $singleLink,
                    'link_title' => $this->linkTitle ?? ($this->link_title ?? ($this->linkText ?? $this->link_text)),
                    'classes' => $classes,
                    'icon' => null,
                    'icon_align' => 'right',
                    'button_style' => $defaultStyle,
                    'button_color' => $defaultColor,
                ];
            }
        }

        return $result;
    }

    public function hasButtons(): bool
    {
        return [] !== $this->getNormalizedButtons();
    }

    public function getIconClass(): ?string
    {
        return PropsResolver::resolveIcon($this->icon);
    }
}
