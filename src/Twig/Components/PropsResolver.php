<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

final class PropsResolver
{
    /**
     * @param array<string, mixed> $props
     */
    public static function textAlign(array $props): string
    {
        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : 'left';

        return match ($align) {
            'center' => 'text-center',
            'right' => 'text-right',
            'justify' => 'text-justify',
            default => 'text-left',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function titleColor(array $props): string
    {
        $color = isset($props['title_color']) && \is_string($props['title_color']) ? $props['title_color'] : 'default';

        return match ($color) {
            'primary' => 'text-primary-600 dark:text-primary-400',
            'secondary' => 'text-secondary-600 dark:text-secondary-400',
            'muted' => 'text-gray-500 dark:text-neutral-400',
            'white' => 'text-white',
            default => 'text-gray-900 dark:text-white',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function linkColor(array $props): string
    {
        $color = isset($props['link_color']) && \is_string($props['link_color']) ? $props['link_color'] : 'primary';

        return match ($color) {
            'secondary' => 'text-secondary-600 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-300',
            'dark' => 'text-gray-900 hover:text-gray-700 dark:text-white dark:hover:text-neutral-300',
            'white' => 'text-white hover:text-gray-200',
            default => 'text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function textColor(array $props): string
    {
        $color = isset($props['text_color']) && \is_string($props['text_color'])
            ? $props['text_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'default');

        return match ($color) {
            'muted' => 'text-gray-500 dark:text-neutral-400',
            'white' => 'text-white',
            'primary' => 'text-primary-600 dark:text-primary-400',
            default => 'text-gray-700 dark:text-neutral-300',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function bgColor(array $props): string
    {
        $color = isset($props['bg_color']) && \is_string($props['bg_color'])
            ? $props['bg_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'transparent');

        return match ($color) {
            'white' => 'bg-white dark:bg-neutral-900',
            'muted' => 'bg-gray-50 dark:bg-neutral-800',
            'primary' => 'bg-primary-600 text-white',
            'dark' => 'bg-gray-900 text-white dark:bg-black',
            default => 'bg-transparent',
        };
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function buttonClasses(array $props): string
    {
        $variant = isset($props['variant']) && \is_string($props['variant']) ? $props['variant'] : 'solid';
        $size = isset($props['size']) && \is_string($props['size']) ? $props['size'] : 'md';
        $color = isset($props['link_color']) && \is_string($props['link_color'])
            ? $props['link_color']
            : (isset($props['color']) && \is_string($props['color']) ? $props['color'] : 'primary');
        $isFullWidth = isset($props['full_width']) && true === $props['full_width'];

        $base = 'inline-flex items-center justify-center gap-x-2 font-medium rounded-lg transition-colors focus:outline-hidden focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';

        $sizeClasses = match ($size) {
            'xs', 'xsmall' => 'py-1.5 px-2.5 text-xs',
            'sm', 'small' => 'py-2 px-3 text-xs',
            'lg', 'large' => 'py-3.5 px-5 text-base',
            'xl', 'xlarge' => 'py-4 px-6 text-base font-semibold',
            default => 'py-2.5 px-4 text-sm',
        };

        $variantClasses = match ($variant) {
            'outline' => match ($color) {
                'secondary' => 'border border-secondary-600 text-secondary-600 hover:bg-secondary-50 dark:border-secondary-400 dark:text-secondary-400 dark:hover:bg-secondary-950/30 focus:ring-secondary-500',
                'dark' => 'border border-gray-900 text-gray-900 hover:bg-gray-100 dark:border-white dark:text-white dark:hover:bg-neutral-800 focus:ring-gray-900',
                'white' => 'border border-white text-white hover:bg-white/10 focus:ring-white',
                default => 'border border-primary-600 text-primary-600 hover:bg-primary-50 dark:border-primary-400 dark:text-primary-400 dark:hover:bg-primary-950/30 focus:ring-primary-500',
            },
            'soft' => match ($color) {
                'secondary' => 'border border-transparent bg-secondary-100 text-secondary-800 hover:bg-secondary-200 dark:bg-secondary-900/30 dark:text-secondary-400 dark:hover:bg-secondary-900/50 focus:ring-secondary-500',
                'dark' => 'border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-neutral-800 dark:text-neutral-200 dark:hover:bg-neutral-700 focus:ring-gray-900',
                'white' => 'border border-transparent bg-white/10 text-white hover:bg-white/20 focus:ring-white',
                default => 'border border-transparent bg-primary-100 text-primary-800 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 focus:ring-primary-500',
            },
            'ghost' => match ($color) {
                'secondary' => 'text-secondary-600 hover:bg-secondary-50 dark:text-secondary-400 dark:hover:bg-secondary-950/30 focus:ring-secondary-500',
                'dark' => 'text-gray-900 hover:bg-gray-100 dark:text-white dark:hover:bg-neutral-800 focus:ring-gray-900',
                'white' => 'text-white hover:bg-white/10 focus:ring-white',
                default => 'text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950/30 focus:ring-primary-500',
            },
            'link' => match ($color) {
                'secondary' => 'text-secondary-600 hover:underline p-0 focus:ring-0 dark:text-secondary-400',
                'dark' => 'text-gray-900 hover:underline p-0 focus:ring-0 dark:text-white',
                'white' => 'text-white hover:underline p-0 focus:ring-0',
                default => 'text-primary-600 hover:underline p-0 focus:ring-0 dark:text-primary-400',
            },
            'white' => 'border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:ring-gray-300 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800',
            default => match ($color) {
                'secondary' => 'border border-transparent bg-secondary-600 text-white hover:bg-secondary-700 focus:ring-secondary-500 dark:focus:ring-offset-neutral-800',
                'dark' => 'border border-transparent bg-gray-900 text-white hover:bg-gray-800 focus:ring-gray-900 dark:bg-white dark:text-gray-900 dark:hover:bg-neutral-200 dark:focus:ring-offset-neutral-800',
                'white' => 'border border-transparent bg-white text-gray-800 hover:bg-gray-100 shadow-2xs focus:ring-white dark:focus:ring-offset-neutral-800',
                default => 'border border-transparent bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 dark:focus:ring-offset-neutral-800',
            },
        };

        $align = isset($props['text_align']) && \is_string($props['text_align']) ? $props['text_align'] : null;
        $alignClasses = match ($align) {
            'center' => 'mx-auto flex w-fit',
            'right' => 'ml-auto flex w-fit',
            default => '',
        };
        $widthClass = $isFullWidth ? 'w-full' : $alignClasses;

        return \trim(\sprintf('%s %s %s %s', $base, $sizeClasses, $variantClasses, $widthClass));
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function badgeClasses(
        array $props,
        string $defaultStyle = 'soft',
        string $defaultColor = 'primary',
        string $defaultSize = 'xs',
        string $defaultRounded = 'pill',
    ): string {
        $variant = isset($props['badge_style']) && \is_string($props['badge_style']) && 'inherit' !== $props['badge_style']
            ? $props['badge_style']
            : (isset($props['variant']) && \is_string($props['variant']) && 'inherit' !== $props['variant'] ? $props['variant'] : $defaultStyle);

        $color = isset($props['badge_color']) && \is_string($props['badge_color']) && 'inherit' !== $props['badge_color']
            ? $props['badge_color']
            : (isset($props['color']) && \is_string($props['color']) && 'inherit' !== $props['color'] ? $props['color'] : $defaultColor);

        $size = isset($props['size']) && \is_string($props['size']) && 'inherit' !== $props['size']
            ? $props['size']
            : $defaultSize;

        $rounded = isset($props['rounded']) && \is_string($props['rounded']) && 'inherit' !== $props['rounded']
            ? $props['rounded']
            : $defaultRounded;

        $base = 'inline-flex items-center gap-x-1.5 font-medium';

        $roundedClass = match ($rounded) {
            'rounded', 'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'square', 'none' => 'rounded-none',
            default => 'rounded-full',
        };

        $sizeClasses = match ($size) {
            'sm' => 'py-1 px-2.5 text-xs',
            'md' => 'py-1.5 px-3 text-sm',
            'lg' => 'py-2 px-3.5 text-sm font-semibold',
            default => 'py-0.5 px-2 text-xs',
        };

        $variantClasses = match ($variant) {
            'solid' => match ($color) {
                'secondary' => 'bg-secondary-600 text-white',
                'dark' => 'bg-gray-800 text-white dark:bg-neutral-200 dark:text-neutral-800',
                'white' => 'bg-white text-gray-800 shadow-2xs',
                'muted' => 'bg-gray-500 text-white',
                'success' => 'bg-teal-500 text-white',
                'warning' => 'bg-yellow-500 text-white',
                'danger' => 'bg-red-500 text-white',
                'info' => 'bg-sky-500 text-white',
                default => 'bg-primary-600 text-white',
            },
            'outline' => match ($color) {
                'secondary' => 'border border-secondary-600 text-secondary-600 dark:border-secondary-400 dark:text-secondary-400',
                'dark' => 'border border-gray-800 text-gray-800 dark:border-neutral-200 dark:text-neutral-200',
                'white' => 'border border-white/60 text-white',
                'muted' => 'border border-gray-200 text-gray-600 dark:border-neutral-700 dark:text-neutral-400',
                'success' => 'border border-teal-500 text-teal-500',
                'warning' => 'border border-yellow-500 text-yellow-500',
                'danger' => 'border border-red-500 text-red-500',
                'info' => 'border border-sky-500 text-sky-500',
                default => 'border border-primary-600 text-primary-600 dark:border-primary-400 dark:text-primary-400',
            },
            'white' => 'border border-gray-200 bg-white text-gray-800 shadow-2xs dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-200',
            'glass' => 'bg-white/80 text-gray-800 backdrop-blur-md border border-white/20 shadow-xs dark:bg-neutral-900/80 dark:text-neutral-200 dark:border-neutral-700/30',
            default => match ($color) {
                'secondary' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-500/20 dark:text-secondary-400',
                'dark' => 'bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-white',
                'white' => 'bg-white/20 text-white backdrop-blur-md',
                'muted' => 'bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-neutral-300',
                'success' => 'bg-teal-100 text-teal-800 dark:bg-teal-500/20 dark:text-teal-400',
                'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
                'danger' => 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
                'info' => 'bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-400',
                default => 'bg-primary-100 text-primary-800 dark:bg-primary-500/20 dark:text-primary-400',
            },
        };

        return \trim(\sprintf('%s %s %s %s', $base, $roundedClass, $sizeClasses, $variantClasses));
    }

    /**
     * @param array<string, mixed> $props
     */
    public static function cardClasses(array $props): string
    {
        $variant = isset($props['card_variant']) && \is_string($props['card_variant'])
            ? $props['card_variant']
            : (isset($props['variant']) && \is_string($props['variant']) ? $props['variant'] : 'bordered');
        $layout = isset($props['layout']) && \is_string($props['layout']) ? $props['layout'] : 'vertical';
        $bg = self::bgColor($props);

        $base = 'flex rounded-xl overflow-hidden transition-all';

        $variantClasses = match ($variant) {
            'group' => 'border-0 rounded-none shadow-none flex-1',
            'shadow' => 'shadow-lg border border-gray-100 dark:border-neutral-800 hover:shadow-xl',
            'flat' => 'border-0 bg-gray-50 dark:bg-neutral-800',
            'glass' => 'backdrop-blur-md bg-white/70 border border-white/20 dark:bg-neutral-900/70 dark:border-neutral-700/30 shadow-lg',
            default => 'border border-gray-200 shadow-2xs dark:border-neutral-700',
        };

        $layoutClasses = match ($layout) {
            'horizontal' => 'flex-col md:flex-row',
            default => 'flex-col',
        };

        return \trim(\sprintf('%s %s %s %s', $base, $variantClasses, $layoutClasses, $bg));
    }

    /**
     * Helper to resolve a Sulu link structure or plain URL string to an href string.
     */
    public static function resolveUrl(mixed $link): string
    {
        if (\is_string($link)) {
            return $link;
        }

        if (\is_array($link)) {
            if (isset($link['url']) && \is_string($link['url'])) {
                $url = $link['url'];
                if (isset($link['anchor']) && \is_string($link['anchor']) && '' !== $link['anchor']) {
                    $url .= '#' . \ltrim($link['anchor'], '#');
                }

                return $url;
            }

            if (isset($link['href']) && \is_string($link['href'])) {
                return $link['href'];
            }
        }

        if (\is_object($link)) {
            if (\method_exists($link, 'getUrl')) {
                /** @var mixed $url */
                $url = $link->getUrl();
                if (\is_string($url) && '' !== $url) {
                    return $url;
                }
            }
        }

        return '#';
    }

    /**
     * Helper to resolve a Sulu Media structure, object, or string to an image URL.
     */
    public static function resolveImageUrl(mixed $media): ?string
    {
        if (null === $media) {
            return null;
        }

        if (\is_string($media)) {
            return $media;
        }

        if (\is_array($media)) {
            if (isset($media['url']) && \is_string($media['url'])) {
                return $media['url'];
            }
            if (isset($media['formats']) && \is_array($media['formats'])) {
                $first = \reset($media['formats']);
                if (\is_string($first)) {
                    return $first;
                }
            }
            if (isset($media['id']) && (\is_int($media['id']) || \is_string($media['id']))) {
                return (string) $media['id'];
            }
        }

        if (\is_object($media)) {
            if (\method_exists($media, 'getUrl')) {
                /** @var mixed $url */
                $url = $media->getUrl();
                if (\is_string($url) && '' !== $url) {
                    return $url;
                }
            }

            if (\method_exists($media, 'getFormats')) {
                /** @var mixed $formats */
                $formats = $media->getFormats();
                if (\is_array($formats) && [] !== $formats) {
                    $first = \reset($formats);
                    if (\is_string($first) && '' !== $first) {
                        return $first;
                    }
                }
            }
        }

        return null;
    }

    public static function resolveIcon(mixed $icon): ?string
    {
        if (null === $icon || '' === $icon) {
            return null;
        }

        $raw = null;
        if (\is_string($icon)) {
            $raw = \trim($icon);
        } elseif (\is_array($icon)) {
            $candidate = $icon['name'] ?? $icon['icon'] ?? $icon['id'] ?? null;
            if (\is_string($candidate)) {
                $raw = \trim($candidate);
            }
        } elseif (\is_object($icon)) {
            if (\method_exists($icon, 'getName')) {
                /** @var mixed $name */
                $name = $icon->getName();
                if (\is_string($name)) {
                    $raw = \trim($name);
                }
            } elseif (\method_exists($icon, 'getIcon')) {
                /** @var mixed $name */
                $name = $icon->getIcon();
                if (\is_string($name)) {
                    $raw = \trim($name);
                }
            } elseif (\method_exists($icon, '__toString')) {
                $raw = \trim((string) $icon);
            }
        }

        if (null === $raw || '' === $raw) {
            return null;
        }

        if (\str_starts_with($raw, 'ti ')) {
            return $raw;
        }

        if (\str_starts_with($raw, 'ti-')) {
            return 'ti ' . $raw;
        }

        if (\str_contains($raw, ' ') || \str_starts_with($raw, 'su-') || \str_starts_with($raw, 'bi-') || \str_starts_with($raw, 'fa-')) {
            return $raw;
        }

        return 'ti ti-' . $raw;
    }

    /**
     * Helper to resolve responsive srcset from a Sulu Media structure.
     */
    public static function resolveSrcSet(mixed $media): ?string
    {
        if (null === $media || (!\is_array($media) && !\is_object($media))) {
            return null;
        }

        /** @var array<string, mixed> $formats */
        $formats = [];
        if (\is_array($media) && isset($media['formats']) && \is_array($media['formats'])) {
            $formats = $media['formats'];
        } elseif (\is_object($media) && \method_exists($media, 'getFormats')) {
            /** @var mixed $extracted */
            $extracted = $media->getFormats();
            if (\is_array($extracted)) {
                $formats = $extracted;
            }
        }

        if ([] === $formats) {
            return null;
        }

        /** @var array<string, int> $standardWidths */
        $standardWidths = [
            'col-sm' => 400,
            'col-md' => 640,
            'col-lg' => 960,
            'col-xl' => 1280,
            'col-2xl' => 1600,
        ];

        /** @var array<int, string> $sources */
        $sources = [];

        foreach ($formats as $formatKey => $formatUrl) {
            if (!\is_string($formatKey) || !\is_string($formatUrl) || '' === \trim($formatUrl)) {
                continue;
            }

            $width = $standardWidths[$formatKey] ?? null;

            if (null === $width) {
                // Match patterns like sulu-400x400, 400x, 640w
                if (1 === \preg_match('/(?:^|[^\d])(\d+)(?:x\d*|w)?$/', $formatKey, $matches)) {
                    $width = (int) $matches[1];
                }
            }

            if (null !== $width && $width > 0) {
                $sources[$width] = \sprintf('%s %dw', $formatUrl, $width);
            }
        }

        if ([] === $sources) {
            return null;
        }

        \ksort($sources);

        return \implode(', ', $sources);
    }

    /**
     * Helper to resolve focal/focus point to CSS object-position.
     */
    public static function resolveFocusPointStyle(mixed $media): ?string
    {
        if (null === $media) {
            return null;
        }

        $x = null;
        $y = null;

        if (\is_array($media)) {
            $point = $media['focusPoint'] ?? ($media['focalPoint'] ?? null);
            if (\is_array($point)) {
                $x = $point['x'] ?? null;
                $y = $point['y'] ?? null;
            }
        } elseif (\is_object($media)) {
            if (\method_exists($media, 'getFocusPoint')) {
                /** @var mixed $point */
                $point = $media->getFocusPoint();
                if (\is_array($point)) {
                    $x = $point['x'] ?? null;
                    $y = $point['y'] ?? null;
                } elseif (\is_object($point)) {
                    if (\method_exists($point, 'getX') && \method_exists($point, 'getY')) {
                        /** @var mixed $xVal */
                        $xVal = $point->getX();
                        /** @var mixed $yVal */
                        $yVal = $point->getY();
                        $x = $xVal;
                        $y = $yVal;
                    }
                }
            }
        }

        if (null !== $x && null !== $y && \is_numeric($x) && \is_numeric($y)) {
            return \sprintf('object-position: %s%% %s%%;', $x, $y);
        }

        return null;
    }

    /**
     * Helper to resolve object fit utility class.
     */
    public static function resolveObjectFit(mixed $fit): string
    {
        if (!\is_string($fit) || '' === \trim($fit)) {
            return 'object-cover';
        }

        return match (\trim($fit)) {
            'contain' => 'object-contain',
            'fill' => 'object-fill',
            'scale-down' => 'object-scale-down',
            'none' => 'object-none',
            default => 'object-cover',
        };
    }
}
