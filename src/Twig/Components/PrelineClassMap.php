<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Totoglu\SuluBlockSuiteBundle\Twig\Component\AbstractBlockComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

/**
 * Single source of truth for shared Preline UI 5 and Tailwind CSS 4 utility class maps.
 *
 * All class strings are complete static tokens so Tailwind CSS 4's `@source` scanner
 * can discover every utility class at build time without dynamic string concatenation.
 */
final class PrelineClassMap
{
    // =========================================================================
    // 1. Universal Spacing (Margins, Paddings, Gaps)
    // =========================================================================

    /**
     * @var array<array-key, string>
     */
    public const MARGIN_TOP = [
        'none' => 'mt-0',
        'remove' => 'mt-0',
        'xs' => 'mt-2',
        'xsmall' => 'mt-2',
        'sm' => 'mt-4',
        'small' => 'mt-4',
        'md' => 'mt-6',
        'medium' => 'mt-6',
        'default' => 'mt-6',
        'lg' => 'mt-10',
        'large' => 'mt-10',
        'xl' => 'mt-16',
        'xlarge' => 'mt-16',
        '2xl' => 'mt-24',
        '2xlarge' => 'mt-24',
        'auto' => 'mt-auto',
    ];

    /**
     * @var array<array-key, string>
     */
    public const MARGIN_BOTTOM = [
        'none' => 'mb-0',
        'remove' => 'mb-0',
        'xs' => 'mb-2',
        'xsmall' => 'mb-2',
        'sm' => 'mb-4',
        'small' => 'mb-4',
        'md' => 'mb-6',
        'medium' => 'mb-6',
        'default' => 'mb-6',
        'lg' => 'mb-10',
        'large' => 'mb-10',
        'xl' => 'mb-16',
        'xlarge' => 'mb-16',
        '2xl' => 'mb-24',
        '2xlarge' => 'mb-24',
        'auto' => 'mb-auto',
    ];

    /**
     * @var array<array-key, string>
     */
    public const MARGIN_VERTICAL = [
        'none' => 'my-0',
        'xs' => 'my-2',
        'xsmall' => 'my-2',
        'sm' => 'my-4',
        'small' => 'my-4',
        'md' => 'my-6',
        'medium' => 'my-6',
        'default' => 'my-6',
        'lg' => 'my-10',
        'large' => 'my-10',
        'xl' => 'my-16',
        'xlarge' => 'my-16',
        '2xl' => 'my-24',
        '2xlarge' => 'my-24',
        'auto' => 'my-auto',
    ];

    /**
     * @var array<array-key, string>
     */
    public const PADDING = [
        'none' => '',
        '0' => 'p-0',
        'xs' => 'p-2 sm:p-3',
        'xsmall' => 'p-2 sm:p-3',
        'sm' => 'p-4',
        'small' => 'p-4',
        'compact' => 'p-4 sm:p-5',
        'normal' => 'p-5 sm:p-6',
        'md' => 'p-6 sm:p-8',
        'medium' => 'p-6 sm:p-8',
        'default' => 'p-6 sm:p-8',
        'spacious' => 'p-6 sm:p-8',
        'lg' => 'p-8 sm:p-10',
        'large' => 'p-8 sm:p-10',
        'xl' => 'p-10 sm:p-12',
        'xlarge' => 'p-10 sm:p-12',
    ];

    /**
     * @var array<array-key, string>
     */
    public const CARD_PADDING = [
        'none' => 'p-0',
        'xs' => 'p-3 sm:p-4',
        'sm' => 'p-4 sm:p-5',
        'compact' => 'p-4 sm:p-5',
        'md' => 'p-5 sm:p-6',
        'normal' => 'p-5 sm:p-6',
        'default' => 'p-5 sm:p-6',
        'lg' => 'p-6 sm:p-8',
        'spacious' => 'p-6 sm:p-8',
        'xl' => 'p-8 sm:p-10',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_PADDING = [
        'none' => 'p-0',
        'xs' => 'p-3 sm:p-4',
        'sm' => 'p-4 sm:p-5',
        'compact' => 'p-4 sm:p-5',
        'md' => 'p-6 sm:p-8',
        'normal' => 'p-6 sm:p-8',
        'default' => 'p-6 sm:p-8',
        'lg' => 'p-8 sm:p-10',
        'spacious' => 'p-8 sm:p-10',
        'xl' => 'p-10 sm:p-12',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SECTION_PADDING_X = [
        'none' => 'px-0',
        'xs' => 'px-2 sm:px-3',
        'sm' => 'px-3 sm:px-4',
        'default' => 'px-4 sm:px-6 lg:px-8',
        'md' => 'px-4 sm:px-6 lg:px-8',
        'lg' => 'px-6 sm:px-8 lg:px-12',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SECTION_PADDING_Y = [
        'none' => 'py-0',
        'xs' => 'py-4 sm:py-6',
        'sm' => 'py-6 sm:py-8',
        'default' => 'py-10 sm:py-14',
        'md' => 'py-10 sm:py-14',
        'lg' => 'py-14 sm:py-20',
        'xl' => 'py-20 sm:py-28',
    ];

    /**
     * @var array<array-key, string>
     */
    public const GRID_GAPS = [
        'none' => 'gap-0',
        'collapse' => 'gap-0',
        '0' => 'gap-0',
        'xs' => 'gap-2',
        'xsmall' => 'gap-2',
        'sm' => 'gap-4',
        'small' => 'gap-4',
        'md' => 'gap-6',
        'medium' => 'gap-6',
        'default' => 'gap-6',
        'lg' => 'gap-8',
        'large' => 'gap-8',
        'xl' => 'gap-12',
        'xlarge' => 'gap-12',
        '2xl' => 'gap-16',
        '2xlarge' => 'gap-16',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ROW_GAPS = [
        'none' => 'gap-0',
        'xs' => 'gap-2 sm:gap-3',
        'sm' => 'gap-4 sm:gap-6',
        'md' => 'gap-6 sm:gap-8',
        'default' => 'gap-6 sm:gap-8',
        'lg' => 'gap-8 sm:gap-12',
        'xl' => 'gap-12 sm:gap-16',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SLIDER_GAPS = [
        'none' => 'px-0',
        'xs' => 'px-1',
        'sm' => 'px-1.5',
        'md' => 'px-2.5',
        'default' => 'px-2.5',
        'lg' => 'px-4',
        'xl' => 'px-6',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BUTTON_GROUP_GAPS = [
        'none' => 'gap-0',
        'xs' => 'gap-1',
        'sm' => 'gap-1.5 sm:gap-2',
        'md' => 'gap-2 sm:gap-3',
        'default' => 'gap-2 sm:gap-3',
        'lg' => 'gap-3 sm:gap-4',
    ];

    // =========================================================================
    // 2. Universal Layout, Width, Alignment, Position, Visibility, Effects
    // =========================================================================

    /**
     * Block-level `max_width` presets: Tailwind class and the equivalent pixel width
     * (`null` = no pixel cap). Single source for wrapper classes and responsive image sizing.
     *
     * @var array<string, array{class: string, px: int|null}>
     */
    public const BLOCK_MAX_WIDTHS = [
        '' => ['class' => '', 'px' => null],
        'none' => ['class' => '', 'px' => null],
        'default' => ['class' => '', 'px' => null],
        'xs' => ['class' => 'max-w-xs', 'px' => 320],
        'sm' => ['class' => 'max-w-sm', 'px' => 384],
        'small' => ['class' => 'max-w-sm', 'px' => 384],
        'md' => ['class' => 'max-w-md', 'px' => 448],
        'medium' => ['class' => 'max-w-md', 'px' => 448],
        'lg' => ['class' => 'max-w-lg', 'px' => 512],
        'large' => ['class' => 'max-w-lg', 'px' => 512],
        'xl' => ['class' => 'max-w-xl', 'px' => 576],
        'xlarge' => ['class' => 'max-w-xl', 'px' => 576],
        '2xl' => ['class' => 'max-w-2xl', 'px' => 672],
        '2xlarge' => ['class' => 'max-w-2xl', 'px' => 672],
        '3xl' => ['class' => 'max-w-3xl', 'px' => 768],
        '4xl' => ['class' => 'max-w-4xl', 'px' => 896],
        '5xl' => ['class' => 'max-w-5xl', 'px' => 1024],
        '6xl' => ['class' => 'max-w-6xl', 'px' => 1152],
        '7xl' => ['class' => 'max-w-7xl', 'px' => 1280],
        '85rem' => ['class' => 'max-w-[85rem]', 'px' => 1360],
        'full' => ['class' => 'max-w-full', 'px' => null],
    ];

    /**
     * Responsive `max_width_breakpoint` x `max_width` class map.
     *
     * @var array<string, array<string, string>>
     */
    public const MAX_WIDTH_BREAKPOINTS = [
        'sm' => [
            'xs' => 'sm:max-w-xs',
            'sm' => 'sm:max-w-sm',
            'small' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'medium' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'large' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            'xlarge' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '2xlarge' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            '85rem' => 'sm:max-w-[85rem]',
            'full' => 'sm:max-w-full',
        ],
        'md' => [
            'xs' => 'md:max-w-xs',
            'sm' => 'md:max-w-sm',
            'small' => 'md:max-w-sm',
            'md' => 'md:max-w-md',
            'medium' => 'md:max-w-md',
            'lg' => 'md:max-w-lg',
            'large' => 'md:max-w-lg',
            'xl' => 'md:max-w-xl',
            'xlarge' => 'md:max-w-xl',
            '2xl' => 'md:max-w-2xl',
            '2xlarge' => 'md:max-w-2xl',
            '3xl' => 'md:max-w-3xl',
            '4xl' => 'md:max-w-4xl',
            '5xl' => 'md:max-w-5xl',
            '6xl' => 'md:max-w-6xl',
            '7xl' => 'md:max-w-7xl',
            '85rem' => 'md:max-w-[85rem]',
            'full' => 'md:max-w-full',
        ],
        'lg' => [
            'xs' => 'lg:max-w-xs',
            'sm' => 'lg:max-w-sm',
            'small' => 'lg:max-w-sm',
            'md' => 'lg:max-w-md',
            'medium' => 'lg:max-w-md',
            'lg' => 'lg:max-w-lg',
            'large' => 'lg:max-w-lg',
            'xl' => 'lg:max-w-xl',
            'xlarge' => 'lg:max-w-xl',
            '2xl' => 'lg:max-w-2xl',
            '2xlarge' => 'lg:max-w-2xl',
            '3xl' => 'lg:max-w-3xl',
            '4xl' => 'lg:max-w-4xl',
            '5xl' => 'lg:max-w-5xl',
            '6xl' => 'lg:max-w-6xl',
            '7xl' => 'lg:max-w-7xl',
            '85rem' => 'lg:max-w-[85rem]',
            'full' => 'lg:max-w-full',
        ],
        'xl' => [
            'xs' => 'xl:max-w-xs',
            'sm' => 'xl:max-w-sm',
            'small' => 'xl:max-w-sm',
            'md' => 'xl:max-w-md',
            'medium' => 'xl:max-w-md',
            'lg' => 'xl:max-w-lg',
            'large' => 'xl:max-w-lg',
            'xl' => 'xl:max-w-xl',
            'xlarge' => 'xl:max-w-xl',
            '2xl' => 'xl:max-w-2xl',
            '2xlarge' => 'xl:max-w-2xl',
            '3xl' => 'xl:max-w-3xl',
            '4xl' => 'xl:max-w-4xl',
            '5xl' => 'xl:max-w-5xl',
            '6xl' => 'xl:max-w-6xl',
            '7xl' => 'xl:max-w-7xl',
            '85rem' => 'xl:max-w-[85rem]',
            'full' => 'xl:max-w-full',
        ],
    ];

    /**
     * Section container `max_width` presets: Tailwind class and the equivalent pixel width
     * (`null` = container follows the viewport). Single source for container classes and
     * the layout width propagated to rows, columns and nested blocks.
     *
     * @var array<string, array{class: string, px: int|null}>
     */
    public const SECTION_MAX_WIDTHS = [
        'default' => ['class' => 'max-w-[85rem]', 'px' => 1360],
        '85rem' => ['class' => 'max-w-[85rem]', 'px' => 1360],
        'container' => ['class' => 'container', 'px' => 1536],
        '7xl' => ['class' => 'max-w-7xl', 'px' => 1280],
        '5xl' => ['class' => 'max-w-5xl', 'px' => 1024],
        '3xl' => ['class' => 'max-w-3xl', 'px' => 768],
        'full' => ['class' => 'max-w-full', 'px' => null],
        'none' => ['class' => 'max-w-full', 'px' => null],
    ];

    /**
     * @var array<array-key, string>
     */
    public const BLOCK_ALIGN = [
        'center' => 'mx-auto',
        'right' => 'ml-auto',
        'end' => 'ml-auto',
        'left' => 'mr-auto',
        'start' => 'mr-auto',
    ];

    /**
     * Responsive `block_align_breakpoint` x `block_align` class map.
     *
     * @var array<string, array<string, string>>
     */
    public const BLOCK_ALIGN_BREAKPOINTS = [
        'sm' => [
            'left' => 'sm:mr-auto sm:ml-0',
            'start' => 'sm:mr-auto sm:ml-0',
            'center' => 'sm:mx-auto',
            'right' => 'sm:ml-auto sm:mr-0',
            'end' => 'sm:ml-auto sm:mr-0',
        ],
        'md' => [
            'left' => 'md:mr-auto md:ml-0',
            'start' => 'md:mr-auto md:ml-0',
            'center' => 'md:mx-auto',
            'right' => 'md:ml-auto md:mr-0',
            'end' => 'md:ml-auto md:mr-0',
        ],
        'lg' => [
            'left' => 'lg:mr-auto lg:ml-0',
            'start' => 'lg:mr-auto lg:ml-0',
            'center' => 'lg:mx-auto',
            'right' => 'lg:ml-auto lg:mr-0',
            'end' => 'lg:ml-auto lg:mr-0',
        ],
        'xl' => [
            'left' => 'xl:mr-auto xl:ml-0',
            'start' => 'xl:mr-auto xl:ml-0',
            'center' => 'xl:mx-auto',
            'right' => 'xl:ml-auto xl:mr-0',
            'end' => 'xl:ml-auto xl:mr-0',
        ],
    ];

    /**
     * @var array<array-key, string>
     */
    public const TEXT_ALIGN = [
        'left' => 'text-left',
        'start' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
        'end' => 'text-right',
        'justify' => 'text-justify',
    ];

    /**
     * Responsive `text_align_breakpoint` x `text_align` class map.
     *
     * @var array<string, array<string, string>>
     */
    public const TEXT_ALIGN_BREAKPOINTS = [
        'sm' => [
            'left' => 'sm:text-left',
            'start' => 'sm:text-left',
            'center' => 'sm:text-center',
            'right' => 'sm:text-right',
            'end' => 'sm:text-right',
            'justify' => 'sm:text-justify',
        ],
        'md' => [
            'left' => 'md:text-left',
            'start' => 'md:text-left',
            'center' => 'md:text-center',
            'right' => 'md:text-right',
            'end' => 'md:text-right',
            'justify' => 'md:text-justify',
        ],
        'lg' => [
            'left' => 'lg:text-left',
            'start' => 'lg:text-left',
            'center' => 'lg:text-center',
            'right' => 'lg:text-right',
            'end' => 'lg:text-right',
            'justify' => 'lg:text-justify',
        ],
        'xl' => [
            'left' => 'xl:text-left',
            'start' => 'xl:text-left',
            'center' => 'xl:text-center',
            'right' => 'xl:text-right',
            'end' => 'xl:text-right',
            'justify' => 'xl:text-justify',
        ],
    ];

    /**
     * @var array<array-key, string>
     */
    public const TEXT_ALIGN_LOGICAL = [
        'left' => 'text-start',
        'start' => 'text-start',
        'center' => 'text-center',
        'right' => 'text-end',
        'end' => 'text-end',
        'justify' => 'text-justify',
    ];

    /**
     * Responsive `text_align_breakpoint` x logical `text_align` class map.
     *
     * @var array<string, array<string, string>>
     */
    public const TEXT_ALIGN_LOGICAL_BREAKPOINTS = [
        'sm' => [
            'left' => 'sm:text-start',
            'start' => 'sm:text-start',
            'center' => 'sm:text-center',
            'right' => 'sm:text-end',
            'end' => 'sm:text-end',
            'justify' => 'sm:text-justify',
        ],
        'md' => [
            'left' => 'md:text-start',
            'start' => 'md:text-start',
            'center' => 'md:text-center',
            'right' => 'md:text-end',
            'end' => 'md:text-end',
            'justify' => 'md:text-justify',
        ],
        'lg' => [
            'left' => 'lg:text-start',
            'start' => 'lg:text-start',
            'center' => 'lg:text-center',
            'right' => 'lg:text-end',
            'end' => 'lg:text-end',
            'justify' => 'lg:text-justify',
        ],
        'xl' => [
            'left' => 'xl:text-start',
            'start' => 'xl:text-start',
            'center' => 'xl:text-center',
            'right' => 'xl:text-end',
            'end' => 'xl:text-end',
            'justify' => 'xl:text-justify',
        ],
    ];

    /**
     * @var array<array-key, string>
     */
    public const FLEX_ALIGN = [
        'left' => 'justify-start',
        'start' => 'justify-start',
        'center' => 'justify-center',
        'right' => 'justify-end',
        'end' => 'justify-end',
        'between' => 'justify-between',
        'around' => 'justify-around',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ALIGN_WITH_TEXT = [
        'left' => 'justify-start text-start',
        'start' => 'justify-start text-start',
        'center' => 'justify-center text-center',
        'right' => 'justify-end text-end',
        'end' => 'justify-end text-end',
    ];

    /**
     * @var array<array-key, string>
     */
    public const CONTENT_ALIGN = [
        'left' => 'text-left items-start',
        'start' => 'text-left items-start',
        'center' => 'text-center items-center',
        'right' => 'text-right items-end',
        'end' => 'text-right items-end',
    ];

    /**
     * @var array<array-key, string>
     */
    public const VERTICAL_ALIGN = [
        'top' => 'justify-start',
        'start' => 'justify-start',
        'center' => 'justify-center',
        'middle' => 'justify-center',
        'bottom' => 'justify-end',
        'end' => 'justify-end',
        'between' => 'justify-between',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ALIGN_ITEMS = [
        'stretch' => 'items-stretch',
        'start' => 'items-start',
        'top' => 'items-start',
        'center' => 'items-center',
        'middle' => 'items-center',
        'end' => 'items-end',
        'bottom' => 'items-end',
        'baseline' => 'items-baseline',
    ];

    /**
     * @var array<array-key, string>
     */
    public const POSITION = [
        'static' => '',
        'relative' => 'relative',
        'absolute' => 'absolute',
        'fixed' => 'fixed',
        'sticky' => 'sticky top-6 z-10',
    ];

    /**
     * @var array<array-key, string>
     */
    public const POSITION_Z_INDEX = [
        '0' => 'z-0',
        '1' => 'z-10',
        '2' => 'z-20',
        '3' => 'z-30',
        '10' => 'z-10',
        '20' => 'z-20',
        '30' => 'z-30',
        '40' => 'z-40',
        '50' => 'z-50',
    ];

    /**
     * @var array<array-key, string>
     */
    public const VISIBILITY = [
        'all' => '',
        'default' => '',
        'sm' => 'hidden sm:block',
        's' => 'hidden sm:block',
        'md' => 'hidden md:block',
        'm' => 'hidden md:block',
        'lg' => 'hidden lg:block',
        'l' => 'hidden lg:block',
        'xl' => 'hidden xl:block',
        'hidden-sm' => 'sm:hidden',
        'hidden-s' => 'sm:hidden',
        'hidden-md' => 'md:hidden',
        'hidden-m' => 'md:hidden',
        'hidden-lg' => 'lg:hidden',
        'hidden-l' => 'lg:hidden',
        'hidden-xl' => 'xl:hidden',
        'hidden_sm' => 'hidden sm:block',
        'hidden_md' => 'hidden md:block',
        'hidden_lg' => 'hidden lg:block',
        'visible_sm' => 'block sm:hidden',
        'visible-sm' => 'block sm:hidden',
        'visible_md' => 'block md:hidden',
        'visible-md' => 'block md:hidden',
        'visible_lg' => 'block lg:hidden',
        'visible-lg' => 'block lg:hidden',
        'hidden' => 'hidden',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ANIMATION = [
        'none' => '',
        'parallax' => '',
        'fade' => 'transition-opacity duration-500 ease-out',
        'fade-in' => 'transition-opacity duration-500 ease-out',
        'scale-up' => 'transition-transform duration-500 ease-out scale-100',
        'zoom-in' => 'transition-transform duration-500 ease-out scale-100',
        'scale-down' => 'transition-transform duration-500 ease-out scale-95',
        'zoom-out' => 'transition-transform duration-500 ease-out scale-95',
        'slide-top-small' => 'transition-all duration-300 ease-out -translate-y-2',
        'slide-bottom-small' => 'transition-all duration-300 ease-out translate-y-2',
        'slide-left-small' => 'transition-all duration-300 ease-out -translate-x-2',
        'slide-right-small' => 'transition-all duration-300 ease-out translate-x-2',
        'slide-top-medium' => 'transition-all duration-500 ease-out -translate-y-6',
        'slide-bottom-medium' => 'transition-all duration-500 ease-out translate-y-6',
        'slide-left-medium' => 'transition-all duration-500 ease-out -translate-x-6',
        'slide-right-medium' => 'transition-all duration-500 ease-out translate-x-6',
        'slide-top' => 'transition-all duration-700 ease-out -translate-y-12',
        'slide-bottom' => 'transition-all duration-700 ease-out translate-y-12',
        'slide-left' => 'transition-all duration-700 ease-out -translate-x-12',
        'slide-right' => 'transition-all duration-700 ease-out translate-x-12',
        'slide-up' => 'transition-all duration-500 ease-out translate-y-0',
        'slide-down' => 'transition-all duration-500 ease-out -translate-y-0',
        'pulse' => 'animate-pulse',
        'bounce' => 'animate-bounce',
        'spin' => 'animate-spin',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BLEND_MODE = [
        'normal' => '',
        'none' => '',
        'false' => '',
        '0' => '',
        'true' => 'mix-blend-multiply',
        '1' => 'mix-blend-multiply',
        'multiply' => 'mix-blend-multiply',
        'screen' => 'mix-blend-screen',
        'overlay' => 'mix-blend-overlay',
        'darken' => 'mix-blend-darken',
        'lighten' => 'mix-blend-lighten',
        'color-dodge' => 'mix-blend-color-dodge',
        'color_dodge' => 'mix-blend-color-dodge',
        'color-burn' => 'mix-blend-color-burn',
        'color_burn' => 'mix-blend-color-burn',
        'hard-light' => 'mix-blend-hard-light',
        'hard_light' => 'mix-blend-hard-light',
        'soft-light' => 'mix-blend-soft-light',
        'soft_light' => 'mix-blend-soft-light',
        'difference' => 'mix-blend-difference',
        'exclusion' => 'mix-blend-exclusion',
        'hue' => 'mix-blend-hue',
        'saturation' => 'mix-blend-saturation',
        'color' => 'mix-blend-color',
        'luminosity' => 'mix-blend-luminosity',
        'plus-lighter' => 'mix-blend-plus-lighter',
        'plus_lighter' => 'mix-blend-plus-lighter',
    ];

    /**
     * @var array<array-key, string>
     */
    public const PARALLAX = [
        'none' => '',
        'false' => '',
        '0' => '',
        'subtle' => 'will-change-transform transition-transform duration-300 ease-out',
        'slow' => 'will-change-transform transition-transform duration-300 ease-out',
        'sm' => 'will-change-transform transition-transform duration-300 ease-out',
        'medium' => 'will-change-transform transition-transform duration-500 ease-out',
        'normal' => 'will-change-transform transition-transform duration-500 ease-out',
        'md' => 'will-change-transform transition-transform duration-500 ease-out',
        'default' => 'will-change-transform transition-transform duration-500 ease-out',
        'true' => 'will-change-transform transition-transform duration-500 ease-out',
        '1' => 'will-change-transform transition-transform duration-500 ease-out',
        'strong' => 'will-change-transform transition-transform duration-700 ease-out',
        'fast' => 'will-change-transform transition-transform duration-700 ease-out',
        'lg' => 'will-change-transform transition-transform duration-700 ease-out',
        'fixed' => 'bg-fixed',
        'bg-fixed' => 'bg-fixed',
    ];

    // =========================================================================
    // 3. Shared Colors & Surfaces
    // =========================================================================

    /**
     * @var array<array-key, string>
     */
    public const TITLE_COLORS = [
        'default' => 'text-foreground',
        'dark' => 'text-foreground',
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'muted' => 'text-muted-foreground-1',
        'white' => 'text-white',
        'light' => 'text-white/80',
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'warning' => 'text-amber-600 dark:text-amber-400',
        'danger' => 'text-destructive',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_TITLE_COLORS = [
        'white' => 'text-white',
        'default' => 'text-white',
        'light' => 'text-white/80',
        'primary' => 'text-primary-400',
        'secondary' => 'text-secondary-400',
        'muted' => 'text-white/70',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_LIGHT_GLASS_TITLE_COLORS = [
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'muted' => 'text-muted-foreground-1',
        'default' => 'text-layer-foreground',
        'white' => 'text-layer-foreground',
        'light' => 'text-layer-foreground',
    ];

    /**
     * @var array<array-key, string>
     */
    public const TEXT_COLORS = [
        'default' => 'text-muted-foreground-2',
        'muted' => 'text-muted-foreground-1',
        'neutral' => 'text-muted-foreground-1',
        'white' => 'text-white',
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'dark' => 'text-foreground',
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'warning' => 'text-amber-600 dark:text-amber-400',
        'danger' => 'text-destructive',
    ];

    /**
     * @var array<array-key, string>
     */
    public const LINK_COLORS = [
        'primary' => 'text-primary hover:text-primary-hover',
        'secondary' => 'text-secondary hover:text-secondary-hover',
        'dark' => 'text-foreground hover:text-muted-foreground-2',
        'white' => 'text-white hover:text-white/80',
        'muted' => 'text-muted-foreground-1 hover:text-foreground',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BG_COLORS = [
        'transparent' => 'bg-transparent',
        'none' => 'bg-transparent',
        'white' => 'bg-card',
        'card' => 'bg-card',
        'default' => 'bg-background text-foreground',
        'muted' => 'bg-surface',
        'surface' => 'bg-surface',
        'subtle' => 'bg-surface/70 text-foreground',
        'primary' => 'bg-primary text-primary-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground',
        'dark' => 'bg-inverse text-foreground-inverse',
        'inverse' => 'bg-inverse text-foreground-inverse',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SECTION_BG_STYLES = [
        'transparent' => 'bg-transparent',
        'default' => 'bg-background text-foreground',
        'muted' => 'bg-background-1 text-foreground',
        'subtle' => 'bg-surface/70 text-foreground',
        'primary' => 'bg-primary text-primary-foreground dark:bg-primary-950/40 dark:text-foreground',
        'secondary' => 'bg-secondary text-secondary-foreground dark:bg-secondary-950/40 dark:text-foreground',
        'dark' => 'bg-inverse text-foreground-inverse',
    ];

    /**
     * @var array<array-key, string>
     */
    public const MARKER_COLORS = [
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'muted' => 'text-muted-foreground',
        'neutral' => 'text-muted-foreground',
        'dark' => 'text-foreground',
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'danger' => 'text-destructive',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ACCENT_BORDER_COLORS = [
        'primary' => 'border-s-4 border-primary ps-4 sm:ps-6',
        'secondary' => 'border-s-4 border-secondary ps-4 sm:ps-6',
        'dark' => 'border-s-4 border-line-8 ps-4 sm:ps-6',
        'muted' => 'border-s-4 border-line-3 ps-4 sm:ps-6',
    ];

    /**
     * @var array<array-key, array{ping: string, button: string, inner: string}>
     */
    public const POPOVER_MARKER_COLORS = [
        'primary' => [
            'ping' => 'bg-primary-400',
            'button' => 'bg-primary/20 focus:ring-primary/40',
            'inner' => 'bg-primary text-primary-foreground shadow-md',
        ],
        'dark' => [
            'ping' => 'bg-inverse',
            'button' => 'bg-inverse/20 focus:ring-inverse/40',
            'inner' => 'bg-inverse text-foreground-inverse shadow-md',
        ],
        'light' => [
            'ping' => 'bg-layer',
            'button' => 'bg-layer/30 focus:ring-layer/50',
            'inner' => 'bg-layer text-layer-foreground shadow-md border border-layer-line',
        ],
        'accent' => [
            'ping' => 'bg-amber-400',
            'button' => 'bg-amber-500/20 focus:ring-amber-400/50',
            'inner' => 'bg-amber-500 text-white shadow-md',
        ],
        'success' => [
            'ping' => 'bg-emerald-400',
            'button' => 'bg-emerald-500/20 focus:ring-emerald-400/50',
            'inner' => 'bg-emerald-600 text-white shadow-md',
        ],
        'danger' => [
            'ping' => 'bg-red-400',
            'button' => 'bg-destructive/20 focus:ring-red-400/50',
            'inner' => 'bg-destructive text-destructive-foreground shadow-md',
        ],
    ];

    // =========================================================================
    // 4. Shared Shapes, Border Radiuses & Box Shadows
    // =========================================================================

    /**
     * @var array<array-key, string>
     */
    public const ROUNDED = [
        'none' => 'rounded-none',
        'square' => 'rounded-none',
        'xs' => 'rounded-xs',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'rounded' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl',
        '3xl' => 'rounded-3xl',
        'full' => 'rounded-full',
        'pill' => 'rounded-full',
        'circle' => 'rounded-full',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SHADOW = [
        'none' => 'shadow-none',
        '2xs' => 'shadow-2xs',
        'xs' => 'shadow-xs',
        'sm' => 'shadow-sm',
        'small' => 'shadow-sm',
        'md' => 'shadow-md',
        'medium' => 'shadow-md',
        'lg' => 'shadow-lg',
        'large' => 'shadow-lg',
        'xl' => 'shadow-xl',
        'xlarge' => 'shadow-xl',
        '2xl' => 'shadow-2xl',
        '2xlarge' => 'shadow-2xl',
    ];

    // =========================================================================
    // 5. Shared Typography & Decorations
    // =========================================================================

    /**
     * @var list<string>
     */
    public const ALLOWED_TITLE_ELEMENTS = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span'];

    /**
     * @var array<array-key, string>
     */
    public const TITLE_SIZES = [
        'xs' => 'text-sm sm:text-base font-semibold',
        'sm' => 'text-base sm:text-lg font-semibold',
        'md' => 'text-lg sm:text-xl font-bold',
        'base' => 'text-lg sm:text-xl font-bold',
        'lg' => 'text-xl sm:text-2xl font-bold',
        'xl' => 'text-2xl sm:text-3xl font-extrabold tracking-tight',
        '2xl' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_TITLE_SIZES = [
        'sm' => 'text-base sm:text-lg font-semibold',
        'md' => 'text-lg sm:text-xl font-bold',
        'lg' => 'text-xl sm:text-2xl font-bold',
        'xl' => 'text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight',
    ];

    /**
     * @var array<array-key, string>
     */
    public const HEADLINE_SIZES = [
        'sm' => 'text-base sm:text-lg font-semibold',
        'md' => 'text-xl sm:text-2xl font-bold',
        'lg' => 'text-2xl sm:text-3xl font-bold',
        'xl' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
        '2xl' => 'text-4xl sm:text-5xl lg:text-6xl font-black tracking-tighter',
    ];

    /**
     * @var array<array-key, string>
     */
    public const HEADLINE_TAG_SIZES = [
        'h1' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
        'h2' => 'text-2xl sm:text-3xl font-bold',
        'h3' => 'text-xl sm:text-2xl font-bold',
        'h4' => 'text-lg sm:text-xl font-semibold',
        'h5' => 'text-base sm:text-lg font-semibold',
        'h6' => 'text-sm sm:text-base font-semibold',
        'div' => 'text-2xl sm:text-3xl font-bold',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ALERT_TITLE_SIZES = [
        'sm' => 'text-sm font-semibold',
        'md' => 'text-base font-semibold',
        'lg' => 'text-lg font-bold',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ACCORDION_TITLE_SIZES = [
        'sm' => 'text-sm font-medium',
        'base' => 'text-base font-semibold',
        'md' => 'text-base font-semibold',
        'lg' => 'text-lg font-semibold',
        'xl' => 'text-xl font-bold',
    ];

    /**
     * @var array<array-key, string>
     */
    public const FONT_SIZES = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'base' => 'text-base',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
        '2xl' => 'text-2xl',
    ];

    /**
     * @var array<array-key, string>
     */
    public const TEXT_STYLES = [
        'none' => '',
        'lead' => 'text-xl sm:text-2xl text-muted-foreground-2 font-normal leading-relaxed',
        'meta' => 'text-xs uppercase tracking-wider text-muted-foreground-1 font-medium',
        'large' => 'text-lg sm:text-xl text-foreground leading-relaxed',
        'small' => 'text-sm text-muted-foreground-2 leading-normal',
    ];

    /**
     * @var array<array-key, string>
     */
    public const TITLE_DECORATIONS = [
        'none' => '',
        'underline' => 'underline decoration-primary-500 decoration-2 underline-offset-4',
        'divider' => 'border-b-2 border-primary pb-2 inline-block',
        'bullet' => "flex items-center gap-3 before:content-[''] before:w-1.5 before:h-6 before:bg-primary before:rounded-full",
        'line' => "flex items-center gap-4 before:content-[''] before:flex-1 before:h-px before:bg-border after:content-[''] after:flex-1 after:h-px after:bg-border",
    ];

    /**
     * @var array<array-key, string>
     */
    public const TEXT_COLUMNS = [
        'none' => '',
        '1-2' => 'md:columns-2 gap-8',
        '1-3' => 'md:columns-3 gap-8',
        '1-4' => 'md:columns-2 lg:columns-4 gap-8',
    ];

    // =========================================================================
    // 6. Shared Media, Aspect Ratio, Object Fit & Responsive Sizes
    // =========================================================================

    /**
     * Canonical aspect-ratio presets: Tailwind class and numeric ratio (`null` = intrinsic / manual).
     * `manual` ratios are applied through an inline `aspect-ratio` style built from
     * `aspect_ratio_width` / `aspect_ratio_height`.
     *
     * @var array<string, array{class: string, ratio: array{int, int}|null}>
     */
    public const ASPECT_RATIOS = [
        '16/9' => ['class' => 'aspect-video', 'ratio' => [16, 9]],
        '4/3' => ['class' => 'aspect-4/3', 'ratio' => [4, 3]],
        '1/1' => ['class' => 'aspect-square', 'ratio' => [1, 1]],
        '9/16' => ['class' => 'aspect-9/16', 'ratio' => [9, 16]],
        '21/9' => ['class' => 'aspect-21/9', 'ratio' => [21, 9]],
        'auto' => ['class' => 'aspect-auto', 'ratio' => null],
        'manual' => ['class' => 'aspect-auto', 'ratio' => null],
    ];

    /**
     * Accepted spellings for aspect-ratio presets, normalized to `ASPECT_RATIOS` keys.
     *
     * @var array<string, string>
     */
    public const ASPECT_RATIO_ALIASES = [
        '16x9' => '16/9',
        '16-9' => '16/9',
        '16:9' => '16/9',
        'video' => '16/9',
        '4x3' => '4/3',
        '4-3' => '4/3',
        '4:3' => '4/3',
        '1x1' => '1/1',
        '1-1' => '1/1',
        '1:1' => '1/1',
        'square' => '1/1',
        '9x16' => '9/16',
        '9-16' => '9/16',
        '9:16' => '9/16',
        '21x9' => '21/9',
        '21-9' => '21/9',
        '21:9' => '21/9',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OBJECT_FIT = [
        'cover' => 'object-cover',
        'contain' => 'object-contain',
        'fill' => 'object-fill',
        'scale-down' => 'object-scale-down',
        'none' => 'object-none',
    ];

    /**
     * Minimum height for media containers whose aspect ratio is `auto` and whose media is
     * absolutely positioned (overlay cards, horizontal card media), so they never collapse.
     */
    public const MEDIA_AUTO_MIN_HEIGHT = 'min-h-72';

    // =========================================================================
    // 7. Shared Responsive Grid, Masonry, Column Spans & Slider Columns
    //    (breakpoint => count => complete class; breakpoints match ResponsiveWidth::BREAKPOINTS)
    // =========================================================================

    /**
     * @var array<string, array<int, string>>
     */
    public const GRID_COLUMNS = [
        'base' => [1 => 'grid-cols-1', 2 => 'grid-cols-2', 3 => 'grid-cols-3', 4 => 'grid-cols-4', 5 => 'grid-cols-5', 6 => 'grid-cols-6', 12 => 'grid-cols-12'],
        'sm' => [1 => 'sm:grid-cols-1', 2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-3', 4 => 'sm:grid-cols-4', 5 => 'sm:grid-cols-5', 6 => 'sm:grid-cols-6', 12 => 'sm:grid-cols-12'],
        'md' => [1 => 'md:grid-cols-1', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-3', 4 => 'md:grid-cols-4', 5 => 'md:grid-cols-5', 6 => 'md:grid-cols-6', 12 => 'md:grid-cols-12'],
        'lg' => [1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4', 5 => 'lg:grid-cols-5', 6 => 'lg:grid-cols-6', 12 => 'lg:grid-cols-12'],
        'xl' => [1 => 'xl:grid-cols-1', 2 => 'xl:grid-cols-2', 3 => 'xl:grid-cols-3', 4 => 'xl:grid-cols-4', 5 => 'xl:grid-cols-5', 6 => 'xl:grid-cols-6', 12 => 'xl:grid-cols-12'],
        '2xl' => [1 => '2xl:grid-cols-1', 2 => '2xl:grid-cols-2', 3 => '2xl:grid-cols-3', 4 => '2xl:grid-cols-4', 5 => '2xl:grid-cols-5', 6 => '2xl:grid-cols-6', 12 => '2xl:grid-cols-12'],
    ];

    /**
     * @var array<string, array<int, string>>
     */
    public const MASONRY_COLUMNS = [
        'base' => [1 => 'columns-1', 2 => 'columns-2', 3 => 'columns-3', 4 => 'columns-4', 5 => 'columns-5', 6 => 'columns-6'],
        'sm' => [1 => 'sm:columns-1', 2 => 'sm:columns-2', 3 => 'sm:columns-3', 4 => 'sm:columns-4', 5 => 'sm:columns-5', 6 => 'sm:columns-6'],
        'md' => [1 => 'md:columns-1', 2 => 'md:columns-2', 3 => 'md:columns-3', 4 => 'md:columns-4', 5 => 'md:columns-5', 6 => 'md:columns-6'],
        'lg' => [1 => 'lg:columns-1', 2 => 'lg:columns-2', 3 => 'lg:columns-3', 4 => 'lg:columns-4', 5 => 'lg:columns-5', 6 => 'lg:columns-6'],
        'xl' => [1 => 'xl:columns-1', 2 => 'xl:columns-2', 3 => 'xl:columns-3', 4 => 'xl:columns-4', 5 => 'xl:columns-5', 6 => 'xl:columns-6'],
        '2xl' => [1 => '2xl:columns-1', 2 => '2xl:columns-2', 3 => '2xl:columns-3', 4 => '2xl:columns-4', 5 => '2xl:columns-5', 6 => '2xl:columns-6'],
    ];

    /**
     * Column spans inside the 12-track row grid.
     *
     * @var array<string, array<int, string>>
     */
    public const COLUMN_SPANS = [
        'base' => [1 => 'col-span-1', 2 => 'col-span-2', 3 => 'col-span-3', 4 => 'col-span-4', 5 => 'col-span-5', 6 => 'col-span-6', 7 => 'col-span-7', 8 => 'col-span-8', 9 => 'col-span-9', 10 => 'col-span-10', 11 => 'col-span-11', 12 => 'col-span-12'],
        'sm' => [1 => 'sm:col-span-1', 2 => 'sm:col-span-2', 3 => 'sm:col-span-3', 4 => 'sm:col-span-4', 5 => 'sm:col-span-5', 6 => 'sm:col-span-6', 7 => 'sm:col-span-7', 8 => 'sm:col-span-8', 9 => 'sm:col-span-9', 10 => 'sm:col-span-10', 11 => 'sm:col-span-11', 12 => 'sm:col-span-12'],
        'md' => [1 => 'md:col-span-1', 2 => 'md:col-span-2', 3 => 'md:col-span-3', 4 => 'md:col-span-4', 5 => 'md:col-span-5', 6 => 'md:col-span-6', 7 => 'md:col-span-7', 8 => 'md:col-span-8', 9 => 'md:col-span-9', 10 => 'md:col-span-10', 11 => 'md:col-span-11', 12 => 'md:col-span-12'],
        'lg' => [1 => 'lg:col-span-1', 2 => 'lg:col-span-2', 3 => 'lg:col-span-3', 4 => 'lg:col-span-4', 5 => 'lg:col-span-5', 6 => 'lg:col-span-6', 7 => 'lg:col-span-7', 8 => 'lg:col-span-8', 9 => 'lg:col-span-9', 10 => 'lg:col-span-10', 11 => 'lg:col-span-11', 12 => 'lg:col-span-12'],
        'xl' => [1 => 'xl:col-span-1', 2 => 'xl:col-span-2', 3 => 'xl:col-span-3', 4 => 'xl:col-span-4', 5 => 'xl:col-span-5', 6 => 'xl:col-span-6', 7 => 'xl:col-span-7', 8 => 'xl:col-span-8', 9 => 'xl:col-span-9', 10 => 'xl:col-span-10', 11 => 'xl:col-span-11', 12 => 'xl:col-span-12'],
        '2xl' => [1 => '2xl:col-span-1', 2 => '2xl:col-span-2', 3 => '2xl:col-span-3', 4 => '2xl:col-span-4', 5 => '2xl:col-span-5', 6 => '2xl:col-span-6', 7 => '2xl:col-span-7', 8 => '2xl:col-span-8', 9 => '2xl:col-span-9', 10 => '2xl:col-span-10', 11 => '2xl:col-span-11', 12 => '2xl:col-span-12'],
    ];

    /**
     * Slide width per breakpoint for a given number of slides per view.
     *
     * @var array<string, array<int, string>>
     */
    public const SLIDE_WIDTHS = [
        'base' => [1 => 'w-full', 2 => 'w-1/2', 3 => 'w-1/3', 4 => 'w-1/4'],
        'sm' => [1 => 'sm:w-full', 2 => 'sm:w-1/2', 3 => 'sm:w-1/3', 4 => 'sm:w-1/4'],
        'md' => [1 => 'md:w-full', 2 => 'md:w-1/2', 3 => 'md:w-1/3', 4 => 'md:w-1/4'],
        'lg' => [1 => 'lg:w-full', 2 => 'lg:w-1/2', 3 => 'lg:w-1/3', 4 => 'lg:w-1/4'],
        'xl' => [1 => 'xl:w-full', 2 => 'xl:w-1/2', 3 => 'xl:w-1/3', 4 => 'xl:w-1/4'],
        '2xl' => [1 => '2xl:w-full', 2 => '2xl:w-1/2', 3 => '2xl:w-1/3', 4 => '2xl:w-1/4'],
    ];

    /**
     * Slider `columns` presets: slides per view per breakpoint (mobile-first, sparse).
     * Single source for slide width classes, Preline `slidesQty` and responsive image sizing.
     *
     * @var array<array-key, array<string, int>>
     */
    public const SLIDER_SLIDES_PER_VIEW = [
        '1' => ['base' => 1],
        '2' => ['base' => 1, 'sm' => 2],
        '3' => ['base' => 1, 'sm' => 2, 'md' => 3],
        '4' => ['base' => 1, 'sm' => 2, 'md' => 3, 'lg' => 4],
    ];

    /**
     * Slideshow slide tint over the background image.
     *
     * @var array<string, string>
     */
    public const SLIDESHOW_OVERLAY_TINTS = [
        'none' => '',
        'dark_soft' => 'bg-black/30',
        'dark_medium' => 'bg-black/50',
        'dark_strong' => 'bg-black/70',
        'gradient' => 'bg-linear-to-t from-black/85 via-black/40 to-transparent',
    ];

    /**
     * Slideshow slide content layer and its text measure.
     */
    public const SLIDESHOW_CONTENT = 'relative z-10 mx-auto flex max-w-4xl flex-col px-6 py-12 sm:px-12';

    public const SLIDESHOW_TEXT_MEASURE = 'max-w-2xl';

    /**
     * Gallery lightbox: dialog classes, the dialog's maximum width in px (`sm:max-w-4xl`) and the
     * image classes (kept within the viewport height inside the dialog).
     *
     * @var array{dialog: string, max_px: int, image: string}
     */
    public const GALLERY_LIGHTBOX = [
        'dialog' => 'm-3 flex min-h-[calc(100%-3.5rem)] items-center sm:mx-auto sm:w-full sm:max-w-4xl',
        'max_px' => 896,
        'image' => 'mx-auto max-h-[75vh] rounded-lg object-contain',
    ];

    /**
     * Popover hotspot card: width classes and the matching pixel width per breakpoint.
     *
     * @var array{class: string, px: array<string, int>}
     */
    public const POPOVER_MENU_WIDTH = [
        'class' => 'w-72 max-w-[calc(100vw-2.5rem)] sm:w-80 md:w-88',
        'px' => ['base' => 288, 'sm' => 320, 'md' => 352],
    ];

    /**
     * Map location cards: columns per breakpoint.
     *
     * @var array<string, int>
     */
    public const MAP_ITEM_COLUMNS = ['base' => 1, 'md' => 2, 'lg' => 3];

    /**
     * @var array<array-key, string>
     */
    public const CAROUSEL_HEIGHTS = [
        'small' => 'min-h-[300px] sm:min-h-[380px]',
        'medium' => 'min-h-[420px] sm:min-h-[500px]',
        'large' => 'min-h-[550px] sm:min-h-[650px]',
        'full' => 'min-h-screen',
    ];

    public const CARD_GROUP_CONTAINER = 'grid border border-card-line bg-card rounded-xl shadow-2xs divide-y overflow-hidden sm:flex sm:divide-y-0 sm:divide-x divide-card-divider';

    // =========================================================================
    // 8. Shared Component Variants (Buttons, Badges, Cards, Overlays, Alerts, Icons)
    // =========================================================================

    public const BUTTON_BASE = 'inline-flex items-center justify-center gap-x-2 font-medium rounded-lg transition-colors focus:outline-hidden focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none cursor-pointer';

    /**
     * @var array<array-key, string>
     */
    public const BUTTON_SIZES = [
        'xs' => 'py-1.5 px-2.5 text-xs',
        'xsmall' => 'py-1.5 px-2.5 text-xs',
        'sm' => 'py-2 px-3 text-xs',
        'small' => 'py-2 px-3 text-xs',
        'md' => 'py-2.5 px-4 text-sm',
        'default' => 'py-2.5 px-4 text-sm',
        'lg' => 'py-3.5 px-5 text-base',
        'large' => 'py-3.5 px-5 text-base',
        'xl' => 'py-4 px-6 text-base font-semibold',
        'xlarge' => 'py-4 px-6 text-base font-semibold',
    ];

    /**
     * @var array<array-key, array<array-key, string>>
     */
    public const BUTTON_VARIANTS = [
        'solid' => [
            'primary' => 'border border-transparent bg-primary text-primary-foreground hover:bg-primary-hover focus:ring-primary focus:ring-offset-background',
            'secondary' => 'border border-transparent bg-secondary text-secondary-foreground hover:bg-secondary-hover focus:ring-secondary focus:ring-offset-background',
            'dark' => 'border border-transparent bg-inverse text-foreground-inverse hover:opacity-90 focus:ring-inverse focus:ring-offset-background',
            'white' => 'border border-transparent bg-layer text-layer-foreground hover:bg-layer-hover shadow-2xs focus:ring-layer-focus focus:ring-offset-background',
            'muted' => 'border border-transparent bg-surface text-surface-foreground hover:bg-surface-hover focus:ring-surface-focus focus:ring-offset-background',
            'success' => 'border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 focus:ring-offset-background',
            'warning' => 'border border-transparent bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500 focus:ring-offset-background',
            'danger' => 'border border-transparent bg-destructive text-destructive-foreground hover:bg-destructive-hover focus:ring-destructive focus:ring-offset-background',
        ],
        'outline' => [
            'primary' => 'border border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            'secondary' => 'border border-secondary-line text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
            'dark' => 'border border-line-8 text-foreground hover:bg-muted focus:ring-line-8',
            'white' => 'border border-white text-white hover:bg-white/10 focus:ring-white',
            'muted' => 'border border-border text-muted-foreground-2 hover:bg-muted focus:ring-muted-focus',
            'success' => 'border border-emerald-600 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 focus:ring-emerald-500',
            'warning' => 'border border-amber-500 text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/30 focus:ring-amber-500',
            'danger' => 'border border-destructive text-destructive hover:bg-red-50 dark:hover:bg-red-950/30 focus:ring-destructive',
        ],
        'soft' => [
            'primary' => 'border border-transparent bg-primary-100 text-primary-800 hover:bg-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:hover:bg-primary-900/50 focus:ring-primary',
            'secondary' => 'border border-transparent bg-secondary-100 text-secondary-800 hover:bg-secondary-200 dark:bg-secondary-900/30 dark:text-secondary-400 dark:hover:bg-secondary-900/50 focus:ring-secondary',
            'dark' => 'border border-transparent bg-surface text-surface-foreground hover:bg-surface-hover focus:ring-surface-focus',
            'white' => 'border border-transparent bg-white/10 text-white hover:bg-white/20 focus:ring-white',
            'muted' => 'border border-transparent bg-muted text-muted-foreground-2 hover:bg-surface-hover focus:ring-muted-focus',
            'success' => 'border border-transparent bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 focus:ring-emerald-500',
            'warning' => 'border border-transparent bg-amber-100 text-amber-800 hover:bg-amber-200 dark:bg-amber-900/30 dark:text-amber-400 focus:ring-amber-500',
            'danger' => 'border border-transparent bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-400 focus:ring-destructive',
        ],
        'ghost' => [
            'primary' => 'text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 focus:ring-primary',
            'secondary' => 'text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30 focus:ring-secondary',
            'dark' => 'text-foreground hover:bg-muted focus:ring-muted-focus',
            'white' => 'text-white hover:bg-white/10 focus:ring-white',
            'muted' => 'text-muted-foreground-2 hover:bg-muted focus:ring-muted-focus',
            'success' => 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 focus:ring-emerald-500',
            'warning' => 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/30 focus:ring-amber-500',
            'danger' => 'text-destructive hover:bg-red-50 dark:hover:bg-red-950/30 focus:ring-destructive',
        ],
        'link' => [
            'primary' => 'text-primary hover:underline p-0 focus:ring-0',
            'secondary' => 'text-secondary hover:underline p-0 focus:ring-0',
            'dark' => 'text-foreground hover:underline p-0 focus:ring-0',
            'white' => 'text-white hover:underline p-0 focus:ring-0',
            'muted' => 'text-muted-foreground-2 hover:underline p-0 focus:ring-0',
            'success' => 'text-emerald-600 hover:underline p-0 focus:ring-0',
            'warning' => 'text-amber-600 hover:underline p-0 focus:ring-0',
            'danger' => 'text-destructive hover:underline p-0 focus:ring-0',
        ],
    ];

    public const BUTTON_WHITE_VARIANT = 'border border-layer-line bg-layer text-layer-foreground shadow-2xs hover:bg-layer-hover focus:ring-layer-focus';

    /**
     * @var array<array-key, string>
     */
    public const CARD_BUTTON_STYLES = [
        'primary' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-primary text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-primary',
        'secondary' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-layer-line bg-layer text-layer-foreground shadow-2xs hover:bg-layer-hover focus:outline-hidden focus:bg-layer-focus',
        'outline' => 'py-2 px-3.5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30 transition-colors focus:outline-hidden',
        'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-primary hover:underline focus:outline-hidden',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_BUTTON_STYLES = [
        'primary' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-primary text-primary-foreground hover:bg-primary-hover focus:outline-hidden focus:ring-2 focus:ring-primary shadow-sm transition-all',
        'white' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-layer text-layer-foreground hover:bg-layer-hover focus:outline-hidden focus:ring-2 focus:ring-layer-focus shadow-sm transition-all',
        'secondary' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-hidden focus:ring-2 focus:ring-white/50 transition-all',
        'soft' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg bg-white/20 backdrop-blur-sm text-white hover:bg-white/30 focus:outline-hidden focus:ring-2 focus:ring-white/50 transition-all',
        'outline' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-white/60 text-white hover:bg-layer hover:text-layer-foreground focus:outline-hidden focus:ring-2 focus:ring-white transition-all',
        'ghost' => 'py-2.5 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg text-white hover:bg-white/15 focus:outline-hidden focus:ring-2 focus:ring-white/50 transition-all',
        'link' => 'inline-flex items-center gap-x-1.5 text-sm font-semibold text-white hover:text-primary-300 transition-colors',
    ];

    public const BADGE_BASE = 'inline-flex items-center gap-x-1.5 font-medium';

    /**
     * @var array<array-key, string>
     */
    public const BADGE_ROUNDED = [
        'pill' => 'rounded-full',
        'full' => 'rounded-full',
        'rounded' => 'rounded-md',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'square' => 'rounded-none',
        'none' => 'rounded-none',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BADGE_SIZES = [
        'xs' => 'py-0.5 px-2 text-xs',
        'sm' => 'py-1 px-2.5 text-xs',
        'md' => 'py-1.5 px-3 text-sm',
        'lg' => 'py-2 px-3.5 text-sm font-semibold',
    ];

    /**
     * @var array<array-key, array<array-key, string>>
     */
    public const BADGE_VARIANTS = [
        'solid' => [
            'primary' => 'bg-primary text-primary-foreground',
            'secondary' => 'bg-secondary text-secondary-foreground',
            'dark' => 'bg-inverse text-foreground-inverse',
            'white' => 'bg-layer text-layer-foreground shadow-2xs',
            'muted' => 'bg-surface text-surface-foreground',
            'success' => 'bg-teal-500 text-white',
            'warning' => 'bg-yellow-500 text-white',
            'danger' => 'bg-destructive text-destructive-foreground',
            'info' => 'bg-sky-500 text-white',
        ],
        'outline' => [
            'primary' => 'border border-primary-line text-primary',
            'secondary' => 'border border-secondary-line text-secondary',
            'dark' => 'border border-line-8 text-foreground',
            'white' => 'border border-white/60 text-white',
            'muted' => 'border border-border text-muted-foreground-2',
            'success' => 'border border-teal-500 text-teal-500',
            'warning' => 'border border-yellow-500 text-yellow-500',
            'danger' => 'border border-destructive text-destructive',
            'info' => 'border border-sky-500 text-sky-500',
        ],
        'soft' => [
            'primary' => 'bg-primary-100 text-primary-800 dark:bg-primary-500/20 dark:text-primary-400',
            'secondary' => 'bg-secondary-100 text-secondary-800 dark:bg-secondary-500/20 dark:text-secondary-400',
            'dark' => 'bg-surface text-surface-foreground',
            'white' => 'bg-white/20 text-white backdrop-blur-md',
            'muted' => 'bg-muted text-muted-foreground-2',
            'success' => 'bg-teal-100 text-teal-800 dark:bg-teal-500/20 dark:text-teal-400',
            'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400',
            'danger' => 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
            'info' => 'bg-sky-100 text-sky-800 dark:bg-sky-500/20 dark:text-sky-400',
        ],
    ];

    public const BADGE_WHITE_VARIANT = 'border border-layer-line bg-layer text-layer-foreground shadow-2xs';
    public const BADGE_GLASS_VARIANT = 'bg-layer/80 text-layer-foreground backdrop-blur-md border border-layer-line/40 shadow-xs';

    /**
     * @var array<array-key, string>
     */
    public const MEDIA_BADGE_STYLES = [
        'glass' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-layer/90 text-layer-foreground backdrop-blur-md shadow-xs',
        'primary' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-primary text-primary-foreground shadow-xs',
        'success' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-emerald-600 text-white shadow-xs',
        'dark' => 'py-1 px-2.5 inline-flex items-center gap-x-1 text-xs font-semibold rounded-full bg-inverse text-foreground-inverse shadow-xs',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BADGE_POSITIONS = [
        'top-left' => 'top-left',
        'top_left' => 'top-left',
        'top-right' => 'top-right',
        'top_right' => 'top-right',
        'bottom-left' => 'bottom-left',
        'bottom_left' => 'bottom-left',
        'bottom-right' => 'bottom-right',
        'bottom_right' => 'bottom-right',
        'inline' => 'inline',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BADGE_POSITION_CLASSES_Z10 = [
        'top-left' => 'absolute top-3 start-3 z-10 flex flex-wrap gap-1.5',
        'top-right' => 'absolute top-3 end-3 z-10 flex flex-wrap gap-1.5',
        'bottom-left' => 'absolute bottom-3 start-3 z-10 flex flex-wrap gap-1.5',
        'bottom-right' => 'absolute bottom-3 end-3 z-10 flex flex-wrap gap-1.5',
        'inline' => '',
    ];

    /**
     * @var array<array-key, string>
     */
    public const BADGE_POSITION_CLASSES_Z20 = [
        'top-left' => 'absolute top-3 start-3 z-20 flex flex-wrap gap-1.5',
        'top-right' => 'absolute top-3 end-3 z-20 flex flex-wrap gap-1.5',
        'bottom-left' => 'absolute bottom-3 start-3 z-20 flex flex-wrap gap-1.5',
        'bottom-right' => 'absolute bottom-3 end-3 z-20 flex flex-wrap gap-1.5',
        'inline' => '',
    ];

    /**
     * @var array<array-key, string>
     */
    public const META_BADGE_STYLES = [
        'pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-surface text-surface-foreground rounded-full',
        'primary_pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary-100 text-primary-800 rounded-full dark:bg-primary-950/40 dark:text-primary-300',
        'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_META_BADGE_STYLES = [
        'primary_pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-semibold bg-primary/80 backdrop-blur-sm text-primary-foreground rounded-full',
        'pill' => 'py-1 px-2.5 inline-flex items-center gap-x-1.5 text-xs font-medium bg-white/20 backdrop-blur-sm text-white rounded-full',
        'text' => 'inline-block text-xs font-bold uppercase tracking-wider text-primary-300',
    ];

    /**
     * Breakpoint at which horizontal (`left` / `right`) cards switch from stacked to side-by-side.
     * Must match the prefix used in `CARD_MEDIA_WIDTHS` and `CARD_HORIZONTAL_DIRECTIONS`.
     */
    public const CARD_HORIZONTAL_BREAKPOINT = 'md';

    /**
     * Horizontal card `media_width` presets: media column class, content column class and the
     * media fraction of the card from `CARD_HORIZONTAL_BREAKPOINT` upward.
     *
     * @var array<string, array{media: string, content: string, fraction: float}>
     */
    public const CARD_MEDIA_WIDTHS = [
        '1-2' => ['media' => 'md:w-1/2', 'content' => 'md:w-1/2', 'fraction' => 0.5],
        '2-5' => ['media' => 'md:w-2/5', 'content' => 'md:w-3/5', 'fraction' => 0.4],
        '1-3' => ['media' => 'md:w-1/3', 'content' => 'md:w-2/3', 'fraction' => 1 / 3],
    ];

    /**
     * @var array<string, string>
     */
    public const CARD_HORIZONTAL_DIRECTIONS = [
        'left' => 'flex-col md:flex-row',
        'right' => 'flex-col md:flex-row-reverse',
    ];

    /**
     * Icon tile shown in cards / overlays without media (default surface and on top of media).
     */
    public const CARD_ICON_TILE = 'mb-3 inline-flex items-center justify-center size-10 rounded-lg bg-primary-50 text-primary dark:bg-primary-950/40';

    public const CARD_ICON_TILE_ON_MEDIA = 'mb-3 inline-flex items-center justify-center size-10 rounded-lg bg-white/20 backdrop-blur-sm text-white';

    /**
     * @var array<array-key, string>
     */
    public const CARD_VARIANTS = [
        'default' => 'border border-card-line shadow-2xs',
        'bordered' => 'border border-card-line shadow-2xs',
        'shadow' => 'shadow-md hover:shadow-lg border border-card-line',
        'raised' => 'shadow-md hover:shadow-lg border border-card-line',
        'flat' => 'border-0',
        'interactive' => 'border border-card-line shadow-2xs hover:-translate-y-1 hover:shadow-lg transition-all duration-300',
        'glass' => 'backdrop-blur-md bg-card/70 border border-card-line/40 shadow-lg',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_STYLES = [
        'gradient' => 'bg-gradient-to-t from-black/90 via-black/40 to-transparent',
        'gradient-bottom' => 'bg-gradient-to-t from-black/90 via-black/40 to-transparent',
        'gradient_bottom' => 'bg-gradient-to-t from-black/90 via-black/40 to-transparent',
        'gradient-full' => 'bg-black/60 backdrop-brightness-75',
        'gradient_full' => 'bg-black/60 backdrop-brightness-75',
        'dark' => 'backdrop-blur-md bg-black/60 border border-white/10 shadow-lg',
        'dark-glass' => 'backdrop-blur-md bg-black/60 border border-white/10 shadow-lg',
        'dark_glass' => 'backdrop-blur-md bg-black/60 border border-white/10 shadow-lg',
        'light' => 'backdrop-blur-md bg-layer/80 border border-layer-line/40 shadow-lg text-layer-foreground',
        'light-glass' => 'backdrop-blur-md bg-layer/80 border border-layer-line/40 shadow-lg text-layer-foreground',
        'light_glass' => 'backdrop-blur-md bg-layer/80 border border-layer-line/40 shadow-lg text-layer-foreground',
        'primary' => 'bg-primary/80 backdrop-blur-xs',
        'none' => 'bg-transparent',
        'transparent' => 'bg-transparent',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_POSITIONS = [
        'bottom' => 'bottom',
        'center' => 'center',
        'cover' => 'cover',
        'top' => 'top',
        'bottom-left' => 'bottom-left',
        'bottom_left' => 'bottom-left',
        'bottom-right' => 'bottom-right',
        'bottom_right' => 'bottom-right',
        'top-left' => 'top-left',
        'top_left' => 'top-left',
        'top-right' => 'top-right',
        'top_right' => 'top-right',
        'left' => 'left',
        'right' => 'right',
    ];

    /**
     * Readable line length for text placed over full-bleed media (overlays).
     */
    public const OVERLAY_CONTENT_MAX_WIDTH = 'max-w-xl';

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_POSITION_CLASSES = [
        'bottom' => 'justify-end items-stretch',
        'center' => 'justify-center items-center',
        'cover' => 'inset-0 flex flex-col justify-center items-center text-center',
        'top' => 'justify-start items-stretch',
        'bottom-left' => 'justify-end items-start',
        'bottom-right' => 'justify-end items-end',
        'top-left' => 'justify-start items-start',
        'top-right' => 'justify-start items-end',
        'left' => 'justify-center items-start',
        'right' => 'justify-center items-end',
    ];

    /**
     * @var array<array-key, string>
     */
    public const OVERLAY_TRANSITIONS = [
        'fade' => 'opacity-0 group-hover:opacity-100 transition-opacity duration-300 ease-out',
        'slide-up' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide_up' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide-bottom' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide_bottom' => 'opacity-0 translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide-down' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide_down' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide-top' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'slide_top' => 'opacity-0 -translate-y-4 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 ease-out',
        'zoom' => 'opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 ease-out',
        'zoom-in' => 'opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 ease-out',
        'zoom_in' => 'opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-300 ease-out',
    ];

    /**
     * @var array<array-key, string>
     */
    public const IMAGE_HOVER_EFFECTS = [
        'none' => '',
        'zoom' => 'transition-all duration-500 ease-out group-hover:scale-105',
        'shadow' => 'transition-shadow duration-300 ease-out hover:shadow-xl',
        'brightness' => 'transition-all duration-500 ease-out group-hover:brightness-110',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ALERT_VARIANTS = [
        'info_solid' => 'bg-primary text-primary-foreground shadow-xs',
        'info_outline' => 'border-2 border-primary-line text-primary bg-transparent',
        'info_glass' => 'backdrop-blur-md bg-primary/10 border border-primary/30 text-primary-900 dark:text-primary-200',
        'info_soft' => 'bg-primary-50 border border-primary-200 text-primary-800 dark:bg-primary-950/40 dark:border-primary-800 dark:text-primary-300',
        'success_solid' => 'bg-emerald-600 text-white shadow-xs',
        'success_outline' => 'border-2 border-emerald-500 text-emerald-700 bg-transparent dark:text-emerald-400',
        'success_glass' => 'backdrop-blur-md bg-emerald-500/10 border border-emerald-500/30 text-emerald-900 dark:text-emerald-200',
        'success_soft' => 'bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-300',
        'warning_solid' => 'bg-amber-600 text-white shadow-xs',
        'warning_outline' => 'border-2 border-amber-500 text-amber-700 bg-transparent dark:text-amber-400',
        'warning_glass' => 'backdrop-blur-md bg-amber-500/10 border border-amber-500/30 text-amber-900 dark:text-amber-200',
        'warning_soft' => 'bg-amber-50 border border-amber-200 text-amber-800 dark:bg-amber-950/40 dark:border-amber-800 dark:text-amber-300',
        'danger_solid' => 'bg-destructive text-destructive-foreground shadow-xs',
        'danger_outline' => 'border-2 border-destructive text-destructive bg-transparent',
        'danger_glass' => 'backdrop-blur-md bg-destructive/10 border border-destructive/30 text-red-900 dark:text-red-200',
        'danger_soft' => 'bg-red-50 border border-red-200 text-red-800 dark:bg-red-950/40 dark:border-red-800 dark:text-red-300',
        'dark_solid' => 'bg-inverse text-foreground-inverse shadow-xs',
        'dark_outline' => 'border-2 border-line-4 text-foreground bg-transparent',
        'dark_glass' => 'backdrop-blur-md bg-card/70 border border-card-line text-foreground',
        'dark_soft' => 'bg-surface border border-surface-line text-surface-foreground',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ALERT_DEFAULT_ICONS = [
        'info' => 'ti ti-info-circle',
        'success' => 'ti ti-circle-check',
        'warning' => 'ti ti-alert-triangle',
        'danger' => 'ti ti-alert-circle',
        'dark' => 'ti ti-bell',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ACCORDION_CONTAINER_STYLES = [
        'default' => 'divide-y divide-border',
        'bordered' => 'space-y-3',
        'soft' => 'space-y-3',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ACCORDION_ITEM_STYLES = [
        'default' => 'hs-accordion pt-4 pb-3',
        'bordered' => 'hs-accordion border border-card-line bg-card rounded-xl p-4',
        'soft' => 'hs-accordion bg-surface rounded-xl p-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ACCORDION_CONTENT_STYLES = [
        'default' => 'pt-2 pb-1',
        'bordered' => 'pt-3',
        'soft' => 'pt-3',
    ];

    /**
     * Accordion items with `left` / `right` images: side-by-side breakpoint, image column class
     * and the image fraction of the item from that breakpoint upward.
     *
     * @var array{breakpoint: string, row: string, media_left: string, media_right: string, text: string, fraction: float}
     */
    public const ACCORDION_SIDE_IMAGE = [
        'breakpoint' => 'sm',
        'row' => 'my-2 items-start sm:flex sm:gap-x-6',
        'media_left' => 'mb-3 shrink-0 sm:mb-0 sm:w-1/3',
        'media_right' => 'mt-3 shrink-0 sm:mt-0 sm:w-1/3',
        'text' => 'sm:flex-1',
        'fraction' => 1 / 3,
    ];

    /**
     * Vertical switchers: navigation / panel split on the 12-track grid.
     *
     * @var array{breakpoint: string, grid: string, nav: string, panel: string, nav_end: string, panel_end: string, fraction: float}
     */
    public const SWITCHER_VERTICAL_SPLIT = [
        'breakpoint' => 'md',
        'grid' => 'grid md:grid-cols-12 gap-6 items-start',
        'nav' => 'md:col-span-4',
        'panel' => 'md:col-span-8',
        'nav_end' => 'md:order-2',
        'panel_end' => 'md:order-1',
        'fraction' => 8 / 12,
    ];

    public const ACCORDION_TOGGLE_CLASSES = 'hs-accordion-toggle hs-accordion-active:text-primary inline-flex items-center justify-between gap-x-3 w-full text-start text-foreground hover:text-muted-foreground-1 rounded-lg disabled:opacity-50 disabled:pointer-events-none focus:outline-hidden';

    /**
     * @var array<array-key, string>
     */
    public const ICON_DIMENSIONS = [
        'sm' => 'w-10 h-10',
        'md' => 'w-12 h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-20 h-20',
        '2xl' => 'w-24 h-24',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ICON_FONT_SIZES = [
        'sm' => 'text-xl',
        'md' => 'text-2xl sm:text-3xl',
        'lg' => 'text-4xl',
        'xl' => 'text-5xl',
        '2xl' => 'text-6xl',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ICON_PLAIN_COLORS = [
        'primary' => 'text-primary',
        'secondary' => 'text-secondary',
        'dark' => 'text-foreground',
        'muted' => 'text-muted-foreground',
        'success' => 'text-emerald-600 dark:text-emerald-400',
        'danger' => 'text-destructive',
        'white' => 'text-white',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ICON_CIRCLE_SOLID_COLORS = [
        'primary' => 'bg-primary text-primary-foreground hover:bg-primary-hover',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover',
        'dark' => 'bg-inverse text-foreground-inverse hover:opacity-90',
        'muted' => 'bg-surface text-surface-foreground hover:bg-surface-hover',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700',
        'danger' => 'bg-destructive text-destructive-foreground hover:bg-destructive-hover',
        'white' => 'bg-layer text-layer-foreground hover:bg-layer-hover shadow-sm',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ICON_CIRCLE_OUTLINE_COLORS = [
        'primary' => 'border-primary-line text-primary hover:bg-primary-50 dark:hover:bg-primary-950/30',
        'secondary' => 'border-secondary-line text-secondary hover:bg-secondary-50 dark:hover:bg-secondary-950/30',
        'dark' => 'border-line-8 text-foreground hover:bg-muted',
        'muted' => 'border-border text-muted-foreground-1 hover:bg-muted',
        'success' => 'border-emerald-600 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30',
        'danger' => 'border-destructive text-destructive hover:bg-red-50 dark:hover:bg-red-950/30',
        'white' => 'border-white text-white hover:bg-white/10',
    ];

    /**
     * @var array<array-key, string>
     */
    public const ICON_SOFT_COLORS = [
        'primary' => 'bg-primary-50 text-primary dark:bg-primary-950/40',
        'secondary' => 'bg-secondary-50 text-secondary dark:bg-secondary-950/40',
        'dark' => 'bg-surface text-surface-foreground',
        'muted' => 'bg-muted text-muted-foreground-1',
        'success' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400',
        'danger' => 'bg-red-50 text-destructive dark:bg-red-950/40',
        'white' => 'bg-white/20 text-white backdrop-blur-xs',
    ];

    /**
     * Explicit column `col_span` presets: span (out of 12 tracks) per breakpoint (mobile-first, sparse).
     *
     * @var array<array-key, array<string, int>>
     */
    public const COLUMN_SPAN_PRESETS = [
        '12' => ['base' => 12],
        '10' => ['base' => 12, 'lg' => 10],
        '9' => ['base' => 12, 'lg' => 9],
        '8' => ['base' => 12, 'md' => 8],
        '7' => ['base' => 12, 'md' => 7],
        '6' => ['base' => 12, 'md' => 6],
        '5' => ['base' => 12, 'md' => 5],
        '4' => ['base' => 12, 'md' => 4],
        '3' => ['base' => 12, 'sm' => 6, 'lg' => 3],
        '2' => ['base' => 12, 'sm' => 4, 'lg' => 2],
    ];

    /**
     * @var array<array-key, string>
     */
    public const COLUMN_STYLES = [
        'none' => '',
        'card' => 'bg-card border border-card-line rounded-2xl shadow-xs',
        'muted' => 'bg-background-1 rounded-2xl',
        'subtle' => 'bg-surface/70 rounded-2xl',
        'primary_subtle' => 'bg-primary-50/60 border border-primary-100 rounded-2xl dark:bg-primary-950/20 dark:border-primary-900/30',
    ];

    /**
     * Column `order` classes, keyed by the breakpoint at which the row places columns side by side
     * (`stacked` = never side by side). Reordering only applies while columns are stacked.
     *
     * @var array<string, array{first: string, last: string}>
     */
    public const COLUMN_ORDERS = [
        'stacked' => ['first' => 'order-first', 'last' => 'order-last'],
        'base' => ['first' => '', 'last' => ''],
        'sm' => ['first' => 'order-first sm:order-none', 'last' => 'order-last sm:order-none'],
        'md' => ['first' => 'order-first md:order-none', 'last' => 'order-last md:order-none'],
        'lg' => ['first' => 'order-first lg:order-none', 'last' => 'order-last lg:order-none'],
        'xl' => ['first' => 'order-first xl:order-none', 'last' => 'order-last xl:order-none'],
        '2xl' => ['first' => 'order-first 2xl:order-none', 'last' => 'order-last 2xl:order-none'],
    ];

    /**
     * Sticky column positioning (offset clears the sticky site header).
     */
    public const COLUMN_STICKY = 'sticky top-20 self-start';

    /**
     * @var array<array-key, string>
     */
    public const COUNTDOWN_NUMBER_SIZES = [
        'sm' => 'text-2xl sm:text-3xl font-bold',
        'md' => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
        'lg' => 'text-4xl sm:text-5xl lg:text-6xl font-extrabold',
        'xl' => 'text-5xl sm:text-6xl lg:text-7xl font-black',
    ];

    /**
     * @var array<array-key, string>
     */
    public const COUNTDOWN_SEPARATOR_SIZES = [
        'sm' => 'text-xl sm:text-2xl font-bold',
        'md' => 'text-2xl sm:text-3xl font-extrabold',
        'lg' => 'text-3xl sm:text-4xl font-extrabold',
        'xl' => 'text-4xl sm:text-5xl font-black',
    ];

    /**
     * @var array<array-key, string>
     */
    public const COUNTDOWN_CARD_STYLES = [
        'boxed' => 'flex flex-col items-center justify-center p-3 sm:p-5 rounded-xl border border-card-line bg-card shadow-xs min-w-[80px] sm:min-w-[100px]',
        'minimal' => 'flex flex-col items-center justify-center p-2 min-w-[70px] sm:min-w-[85px]',
        'pill' => 'flex flex-col items-center justify-center py-3 px-4 sm:px-6 rounded-2xl bg-surface min-w-[75px] sm:min-w-[95px] shadow-2xs',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DESCRIPTION_LIST_TERM_WIDTHS = [
        '1-4' => 'sm:w-1/4',
        '1/4' => 'sm:w-1/4',
        '1-3' => 'sm:w-1/3',
        '1/3' => 'sm:w-1/3',
        '1-2' => 'sm:w-1/2',
        '1/2' => 'sm:w-1/2',
        'auto' => 'sm:w-auto',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DESCRIPTION_LIST_DESC_WIDTHS = [
        '1-4' => 'sm:w-3/4',
        '1/4' => 'sm:w-3/4',
        '1-3' => 'sm:w-2/3',
        '1/3' => 'sm:w-2/3',
        '1-2' => 'sm:w-1/2',
        '1/2' => 'sm:w-1/2',
        'auto' => 'sm:flex-1',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DESCRIPTION_LIST_TERM_STYLES = [
        'bold' => 'font-semibold text-foreground',
        'medium' => 'font-medium text-foreground',
        'muted' => 'font-normal text-muted-foreground-1',
        'primary' => 'font-semibold text-primary',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DESCRIPTION_LIST_ROW_PADDINGS = [
        'sm' => 'py-2 px-3',
        'md' => 'py-3 px-3.5',
        'lg' => 'py-4 px-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_COLORS = [
        'default' => 'border-border bg-border',
        'primary' => 'border-primary bg-primary',
        'muted' => 'border-line-1 bg-surface',
        'dark' => 'border-line-8 bg-inverse',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_THICKNESSES = [
        '1' => 'border-t h-px',
        '2' => 'border-t-2 h-0.5',
        '4' => 'border-t-4 h-1',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_WIDTHS = [
        'full' => 'w-full',
        '3-4' => 'w-3/4',
        '1-2' => 'w-1/2',
        '1-3' => 'w-1/3',
        'small' => 'w-16',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_ALIGNS = [
        'center' => 'mx-auto justify-center text-center',
        'left' => 'me-auto justify-start text-start',
        'right' => 'ms-auto justify-end text-end',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_ICON_BADGE_COLORS = [
        'default' => 'border-layer-line bg-layer text-muted-foreground-2',
        'primary' => 'border-primary-200 bg-layer text-primary dark:border-primary-900/50',
        'dark' => 'border-line-8 bg-inverse text-foreground-inverse',
        'muted' => 'border-border bg-surface text-muted-foreground-1',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_ICON_BEFORE_LINES = [
        'default_1' => 'before:flex-1 before:border-t before:border-border before:me-4',
        'default_2' => 'before:flex-1 before:border-t-2 before:border-border before:me-4',
        'default_4' => 'before:flex-1 before:border-t-4 before:border-border before:me-4',
        'primary_1' => 'before:flex-1 before:border-t before:border-primary before:me-4',
        'primary_2' => 'before:flex-1 before:border-t-2 before:border-primary before:me-4',
        'primary_4' => 'before:flex-1 before:border-t-4 before:border-primary before:me-4',
        'muted_1' => 'before:flex-1 before:border-t before:border-line-1 before:me-4',
        'muted_2' => 'before:flex-1 before:border-t-2 before:border-line-1 before:me-4',
        'muted_4' => 'before:flex-1 before:border-t-4 before:border-line-1 before:me-4',
        'dark_1' => 'before:flex-1 before:border-t before:border-line-8 before:me-4',
        'dark_2' => 'before:flex-1 before:border-t-2 before:border-line-8 before:me-4',
        'dark_4' => 'before:flex-1 before:border-t-4 before:border-line-8 before:me-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const DIVIDER_ICON_AFTER_LINES = [
        'default_1' => 'after:flex-1 after:border-t after:border-border after:ms-4',
        'default_2' => 'after:flex-1 after:border-t-2 after:border-border after:ms-4',
        'default_4' => 'after:flex-1 after:border-t-4 after:border-border after:ms-4',
        'primary_1' => 'after:flex-1 after:border-t after:border-primary after:ms-4',
        'primary_2' => 'after:flex-1 after:border-t-2 after:border-primary after:ms-4',
        'primary_4' => 'after:flex-1 after:border-t-4 after:border-primary after:ms-4',
        'muted_1' => 'after:flex-1 after:border-t after:border-line-1 after:ms-4',
        'muted_2' => 'after:flex-1 after:border-t-2 after:border-line-1 after:ms-4',
        'muted_4' => 'after:flex-1 after:border-t-4 after:border-line-1 after:ms-4',
        'dark_1' => 'after:flex-1 after:border-t after:border-line-8 after:ms-4',
        'dark_2' => 'after:flex-1 after:border-t-2 after:border-line-8 after:ms-4',
        'dark_4' => 'after:flex-1 after:border-t-4 after:border-line-8 after:ms-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const LIST_SPACING_HORIZONTAL = [
        'sm' => 'gap-x-3 gap-y-2',
        'md' => 'gap-x-5 gap-y-3',
        'lg' => 'gap-x-8 gap-y-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const LIST_SPACING_VERTICAL = [
        'sm' => 'space-y-1.5',
        'md' => 'space-y-2.5',
        'lg' => 'space-y-4',
    ];

    /**
     * @var array<array-key, string>
     */
    public const LIST_ITEM_SIZES = [
        'sm' => 'text-xs',
        'md' => 'text-sm sm:text-base',
        'lg' => 'text-base sm:text-lg',
    ];

    /**
     * @var array<array-key, string>
     */
    public const MAP_ASPECT_RATIOS = [
        '16/9' => 'aspect-video',
        '4/3' => 'aspect-4/3',
        '21/9' => 'aspect-21/9',
        '1/1' => 'aspect-square',
        'auto' => 'h-96 md:h-[500px]',
    ];

    /**
     * @var array<array-key, string>
     */
    public const QUOTE_SIZES = [
        'sm' => 'text-sm sm:text-base',
        'md' => 'text-base sm:text-lg',
        'lg' => 'text-xl sm:text-2xl',
        'xl' => 'text-2xl sm:text-3xl font-semibold',
    ];

    /**
     * @var array<array-key, string>
     */
    public const CONTENT_ALIGN_LOGICAL = [
        'left' => 'text-start items-start',
        'start' => 'text-start items-start',
        'center' => 'text-center items-center',
        'right' => 'text-end items-end',
        'end' => 'text-end items-end',
    ];

    /**
     * Row layout presets: span pattern (out of 12 tracks) per breakpoint, cycled over the columns.
     * `auto` is handled separately (equal tracks, one per column).
     *
     * @var array<string, list<array<string, int>>>
     */
    public const ROW_LAYOUT_SPANS = [
        '12-col' => [['base' => 12]],
        'halves' => [['base' => 12, 'sm' => 6]],
        'thirds' => [['base' => 12, 'md' => 4]],
        'quarters' => [['base' => 12, 'sm' => 6, 'lg' => 3]],
        'sixths' => [['base' => 6, 'sm' => 4, 'lg' => 2]],
        'thirds-2-1' => [['base' => 12, 'md' => 8], ['base' => 12, 'md' => 4]],
        'thirds-1-2' => [['base' => 12, 'md' => 4], ['base' => 12, 'md' => 8]],
        'quarters-3-1' => [['base' => 12, 'lg' => 9], ['base' => 12, 'lg' => 3]],
        'quarters-1-3' => [['base' => 12, 'lg' => 3], ['base' => 12, 'lg' => 9]],
    ];

    /**
     * Row grid container for span-based layouts.
     */
    public const ROW_GRID = 'grid grid-cols-12';

    /**
     * Maximum number of equal tracks for the `auto` row layout (limited by `GRID_COLUMNS`).
     */
    public const ROW_AUTO_MAX_COLUMNS = 6;

    /**
     * Column dividers, keyed by the first breakpoint at which columns sit side by side
     * (`stacked` = columns never share a row).
     *
     * @var array<string, string>
     */
    public const ROW_DIVIDERS = [
        'stacked' => 'divide-y divide-border',
        'base' => 'divide-x divide-border',
        'sm' => 'divide-y sm:divide-y-0 sm:divide-x divide-border',
        'md' => 'divide-y md:divide-y-0 md:divide-x divide-border',
        'lg' => 'divide-y lg:divide-y-0 lg:divide-x divide-border',
        'xl' => 'divide-y xl:divide-y-0 xl:divide-x divide-border',
        '2xl' => 'divide-y 2xl:divide-y-0 2xl:divide-x divide-border',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SCROLL_TO_CIRCLE_SIZES = [
        'sm' => 'size-9',
        'md' => 'size-11',
        'lg' => 'size-13',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SCROLL_TO_CIRCLE_COLORS = [
        'primary' => 'bg-primary text-primary-foreground hover:bg-primary-hover',
        'secondary' => 'bg-secondary text-secondary-foreground hover:bg-secondary-hover',
        'dark' => 'bg-inverse text-foreground-inverse hover:bg-inverse/90',
        'light' => 'bg-layer text-layer-foreground border border-layer-line hover:bg-layer-hover',
    ];

    /**
     * @var array<array-key, string>
     */
    public const SUBNAV_SIZES = [
        'sm' => 'text-xs py-1 px-2.5',
        'md' => 'text-sm py-2 px-3.5',
        'lg' => 'text-base py-3 px-5',
    ];

    /**
     * @var array<array-key, string>
     */
    public const TABLE_CELL_PADDINGS = [
        'sm' => 'px-3 py-2 text-xs',
        'md' => 'px-4 py-3 text-sm',
        'lg' => 'px-6 py-4 text-base',
    ];

    /**
     * @var array<array-key, string>
     */
    public const CODE_LANGUAGE_LABELS = [
        'php' => 'PHP',
        'javascript' => 'JavaScript',
        'js' => 'JavaScript',
        'typescript' => 'TypeScript',
        'ts' => 'TypeScript',
        'html' => 'HTML',
        'css' => 'CSS',
        'bash' => 'Bash',
        'sh' => 'Bash',
        'shell' => 'Bash',
        'json' => 'JSON',
        'yaml' => 'YAML',
        'yml' => 'YAML',
        'sql' => 'SQL',
        'python' => 'Python',
        'py' => 'Python',
        'markdown' => 'Markdown',
        'md' => 'Markdown',
    ];

    // =========================================================================
    // Lookup Helpers
    // =========================================================================

    /**
     * @param array<array-key, string> $map
     */
    public static function resolve(array $map, mixed $value, string $fallback = ''): string
    {
        if (!\is_string($value)) {
            return $fallback;
        }

        $trimmed = \trim($value);
        if ('' === $trimmed) {
            return $fallback;
        }

        return $map[$trimmed] ?? $fallback;
    }

    /**
     * Resolves a block-level `max_width` preset (`none` when unknown or empty).
     *
     * @return array{class: string, px: int|null}
     */
    public static function blockMaxWidth(mixed $key): array
    {
        $normalized = \is_string($key) ? \trim($key) : '';

        return self::BLOCK_MAX_WIDTHS[$normalized] ?? self::BLOCK_MAX_WIDTHS['none'];
    }

    /**
     * Resolves a Section container `max_width` preset (`default` when unknown or empty).
     *
     * @return array{class: string, px: int|null}
     */
    public static function sectionMaxWidth(mixed $key): array
    {
        $normalized = \is_string($key) ? \trim($key) : '';

        return self::SECTION_MAX_WIDTHS[$normalized] ?? self::SECTION_MAX_WIDTHS['default'];
    }

    /**
     * Resolves an aspect-ratio preset by canonical key or alias; `null` when unknown.
     *
     * @return array{class: string, ratio: array{int, int}|null}|null
     */
    public static function aspectRatio(mixed $key): ?array
    {
        if (!\is_string($key)) {
            return null;
        }

        $normalized = \trim($key);
        $normalized = self::ASPECT_RATIO_ALIASES[$normalized] ?? $normalized;

        return self::ASPECT_RATIOS[$normalized] ?? null;
    }

    /**
     * Builds mobile-first responsive classes from a `breakpoint => value => class` table, emitting a
     * class only where the cascaded value changes. Unknown values are skipped (the previous value
     * keeps applying).
     *
     * @param array<string, array<int, string>> $table
     * @param array<string, int>                $valuesByBreakpoint sparse mobile-first map
     */
    public static function responsiveClasses(array $table, array $valuesByBreakpoint): string
    {
        $classes = [];
        $previous = null;

        foreach (ResponsiveWidth::cascade($valuesByBreakpoint, 0) as $bp => $value) {
            $value = (int) $value;
            if ($value === $previous || !isset($table[$bp][$value])) {
                continue;
            }
            $classes[] = $table[$bp][$value];
            $previous = $value;
        }

        return \implode(' ', $classes);
    }

    /**
     * Resolves the number of grid / masonry columns per breakpoint from `columns_*` props.
     * Values are restricted to the counts available in `GRID_COLUMNS`.
     *
     * @param array<string, mixed> $props
     *
     * @return array<string, int> sparse mobile-first map
     */
    public static function gridColumnCounts(array $props, int $defaultBase = 1, int $defaultMd = 3): array
    {
        $propKeys = ['base' => 'columns_default', 'sm' => 'columns_sm', 'md' => 'columns_md', 'lg' => 'columns_lg', 'xl' => 'columns_xl'];
        $counts = [];

        foreach ($propKeys as $bp => $propKey) {
            $count = self::parseColumnCount($props[$propKey] ?? null);
            if (null !== $count) {
                $counts[$bp] = $count;
            }
        }

        $hasResponsive = [] !== \array_intersect_key($props, \array_flip(['columns_sm', 'columns_md', 'columns_lg', 'columns_xl']));
        $counts['base'] ??= $defaultBase;
        if (!$hasResponsive && $defaultMd !== $counts['base']) {
            $counts['md'] = $defaultMd;
        }

        \uksort($counts, static fn (string $a, string $b): int => ResponsiveWidth::BREAKPOINTS[$a] <=> ResponsiveWidth::BREAKPOINTS[$b]);

        return $counts;
    }

    /**
     * Resolves a responsive grid or masonry container class list from block props.
     *
     * @param array<string, mixed> $props
     */
    public static function resolveResponsiveGridClasses(
        array $props,
        bool $masonry = false,
        int $defaultCols = 1,
        int $defaultMdCols = 3,
        string $defaultGap = 'md',
    ): string {
        $gapClass = self::resolve(self::GRID_GAPS, $props['gap'] ?? null, self::GRID_GAPS[$defaultGap] ?? '');
        $counts = self::gridColumnCounts($props, $defaultCols, $defaultMdCols);

        return AbstractBlockComponent::joinClasses(
            $masonry ? '' : 'grid',
            self::responsiveClasses($masonry ? self::MASONRY_COLUMNS : self::GRID_COLUMNS, $counts),
            $gapClass,
        );
    }

    private static function parseColumnCount(mixed $value): ?int
    {
        if (\is_string($value)) {
            $value = \trim($value);
            $value = \ctype_digit($value) ? (int) $value : null;
        }

        return \is_int($value) && isset(self::GRID_COLUMNS['base'][$value]) && 12 !== $value ? $value : null;
    }
}
