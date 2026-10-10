<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Countdown', template: '@SuluPrelineBlocks/components/Countdown.html.twig')]
final class Countdown
{
    public mixed $date = null;
    public ?string $title = null;

    public ?string $countdownStyle = null;
    public ?string $countdown_style = null;
    public ?bool $showLabel = null;
    public ?bool $show_label = null;
    public ?bool $showSeparator = null;
    public ?bool $show_separator = null;
    public ?string $size = null;
    public ?string $color = null;
    public ?string $align = null;
    public ?string $onExpire = null;
    public ?string $on_expire = null;
    public ?string $expireMessage = null;
    public ?string $expire_message = null;

    public ?string $labelDays = null;
    public ?string $label_days = null;
    public ?string $labelHours = null;
    public ?string $label_hours = null;
    public ?string $labelMinutes = null;
    public ?string $label_minutes = null;
    public ?string $labelSeconds = null;
    public ?string $label_seconds = null;

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

        if (null !== ($this->countdownStyle ?? $this->countdown_style)) {
            $resolved['countdown_style'] = $this->countdownStyle ?? $this->countdown_style;
        }
        if (null !== ($this->showLabel ?? $this->show_label)) {
            $resolved['show_label'] = $this->showLabel ?? $this->show_label;
        }
        if (null !== ($this->showSeparator ?? $this->show_separator)) {
            $resolved['show_separator'] = $this->showSeparator ?? $this->show_separator;
        }
        if (null !== $this->size) {
            $resolved['size'] = $this->size;
        }
        if (null !== $this->color) {
            $resolved['color'] = $this->color;
        }
        if (null !== $this->align) {
            $resolved['align'] = $this->align;
        }
        if (null !== ($this->onExpire ?? $this->on_expire)) {
            $resolved['on_expire'] = $this->onExpire ?? $this->on_expire;
        }
        if (null !== ($this->expireMessage ?? $this->expire_message)) {
            $resolved['expire_message'] = $this->expireMessage ?? $this->expire_message;
        }
        if (null !== ($this->labelDays ?? $this->label_days)) {
            $resolved['label_days'] = $this->labelDays ?? $this->label_days;
        }
        if (null !== ($this->labelHours ?? $this->label_hours)) {
            $resolved['label_hours'] = $this->labelHours ?? $this->label_hours;
        }
        if (null !== ($this->labelMinutes ?? $this->label_minutes)) {
            $resolved['label_minutes'] = $this->labelMinutes ?? $this->label_minutes;
        }
        if (null !== ($this->labelSeconds ?? $this->label_seconds)) {
            $resolved['label_seconds'] = $this->labelSeconds ?? $this->label_seconds;
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

    public function getTargetIsoDate(): string
    {
        if ($this->date instanceof \DateTimeInterface) {
            return $this->date->format(\DateTimeInterface::ATOM);
        }

        if (\is_string($this->date) && '' !== \trim($this->date)) {
            $trimmed = \trim($this->date);
            $parsed = \strtotime($trimmed);
            if (false !== $parsed) {
                return \date(\DateTimeInterface::ATOM, $parsed);
            }

            return $trimmed;
        }

        return \date(\DateTimeInterface::ATOM, \time() + 604800);
    }

    public function getCountdownStyle(): string
    {
        $props = $this->getResolvedProps();
        $style = $props['countdown_style'] ?? 'boxed';

        return match ($style) {
            'minimal' => 'minimal',
            'pill' => 'pill',
            default => 'boxed',
        };
    }

    public function isShowLabel(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['show_label'] ?? true);
    }

    public function isShowSeparator(): bool
    {
        $props = $this->getResolvedProps();

        return (bool) ($props['show_separator'] ?? true);
    }

    public function getAlignClasses(): string
    {
        $props = $this->getResolvedProps();
        $align = $props['align'] ?? ($props['text_align'] ?? 'center');

        return match ($align) {
            'left', 'start' => 'justify-start text-start',
            'right', 'end' => 'justify-end text-end',
            default => 'justify-center text-center',
        };
    }

    public function getNumberClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['size'] ?? 'md';
        $color = $props['color'] ?? 'default';

        $sizeClasses = match ($size) {
            'sm' => 'text-2xl sm:text-3xl font-bold',
            'lg' => 'text-4xl sm:text-5xl lg:text-6xl font-extrabold',
            'xl' => 'text-5xl sm:text-6xl lg:text-7xl font-black',
            default => 'text-3xl sm:text-4xl lg:text-5xl font-extrabold',
        };

        $colorClasses = match ($color) {
            'primary' => 'text-primary-600 dark:text-primary-400',
            'white' => 'text-white',
            'dark' => 'text-gray-900 dark:text-white',
            default => 'text-gray-900 dark:text-white',
        };

        return \sprintf('%s %s tabular-nums tracking-tight', $sizeClasses, $colorClasses);
    }

    public function getCardClasses(): string
    {
        $style = $this->getCountdownStyle();
        if ('minimal' === $style) {
            return 'flex flex-col items-center justify-center p-2 min-w-[70px] sm:min-w-[85px]';
        }

        if ('pill' === $style) {
            return 'flex flex-col items-center justify-center py-3 px-4 sm:px-6 rounded-2xl bg-gray-100 dark:bg-neutral-800 min-w-[75px] sm:min-w-[95px] shadow-2xs';
        }

        return 'flex flex-col items-center justify-center p-3 sm:p-5 rounded-xl border border-gray-200 bg-white shadow-xs dark:bg-neutral-800 dark:border-neutral-700 min-w-[80px] sm:min-w-[100px]';
    }

    public function getSeparatorClasses(): string
    {
        $props = $this->getResolvedProps();
        $size = $props['size'] ?? 'md';
        $color = $props['color'] ?? 'default';

        $sizeClasses = match ($size) {
            'sm' => 'text-xl sm:text-2xl font-bold',
            'lg' => 'text-3xl sm:text-4xl font-extrabold',
            'xl' => 'text-4xl sm:text-5xl font-black',
            default => 'text-2xl sm:text-3xl font-extrabold',
        };

        $colorClasses = match ($color) {
            'primary' => 'text-primary-400 dark:text-primary-500',
            'white' => 'text-white/60',
            'dark' => 'text-gray-400 dark:text-neutral-500',
            default => 'text-gray-400 dark:text-neutral-500',
        };

        return \sprintf('flex items-center %s %s px-1 select-none self-center', $sizeClasses, $colorClasses);
    }

    public function getLabelClasses(): string
    {
        return 'mt-1 text-xs sm:text-sm font-medium uppercase tracking-wider text-gray-500 dark:text-neutral-400';
    }

    public function getOnExpire(): string
    {
        $props = $this->getResolvedProps();
        $action = $props['on_expire'] ?? 'message';

        return match ($action) {
            'reload' => 'reload',
            'hide' => 'hide',
            default => 'message',
        };
    }

    public function getExpireMessage(): string
    {
        $props = $this->getResolvedProps();
        $msg = $props['expire_message'] ?? null;

        if (\is_string($msg) && '' !== \trim($msg)) {
            return \trim($msg);
        }

        return 'Countdown expired!';
    }

    /**
     * @return array<string, string>
     */
    public function getLabels(): array
    {
        $props = $this->getResolvedProps();
        $days = $props['label_days'] ?? 'Days';
        $hours = $props['label_hours'] ?? 'Hours';
        $minutes = $props['label_minutes'] ?? 'Minutes';
        $seconds = $props['label_seconds'] ?? 'Seconds';

        return [
            'days' => \is_scalar($days) ? (string) $days : 'Days',
            'hours' => \is_scalar($hours) ? (string) $hours : 'Hours',
            'minutes' => \is_scalar($minutes) ? (string) $minutes : 'Minutes',
            'seconds' => \is_scalar($seconds) ? (string) $seconds : 'Seconds',
        ];
    }

    public function getWrapperClasses(): string
    {
        $base = 'w-full flex flex-col ' . $this->getAlignClasses();

        return ElementTransform::wrapperClasses($this->getResolvedProps(), $base);
    }

    public function getElementId(): ?string
    {
        return ElementTransform::resolveId($this->getResolvedProps());
    }
}
