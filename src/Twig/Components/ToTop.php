<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible floating back-to-top button.
 */
#[AsTwigComponent('ToTop', template: '@SuluPrelineBlocks/components/ToTop.html.twig')]
final class ToTop
{
    public ?string $title = null;
    public string $position = 'bottom_right';
    public bool $show_label = false;

    /**
     * @var array<string, mixed>
     */
    public array $props = [];

    public function getPositionClasses(): string
    {
        return match ($this->position) {
            'bottom_left' => 'fixed bottom-6 start-6 z-50',
            default => 'fixed bottom-6 end-6 z-50',
        };
    }

    public function getButtonClasses(): string
    {
        return 'inline-flex items-center gap-x-2 py-2.5 px-3.5 rounded-full bg-primary-600 text-white font-medium text-sm shadow-xl hover:bg-primary-700 hover:shadow-2xl transition-all duration-300 focus:outline-hidden focus:ring-4 focus:ring-primary-500/30 dark:bg-primary-500 dark:hover:bg-primary-600';
    }
}
