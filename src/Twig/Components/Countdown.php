<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('Countdown', template: '@SuluPrelineBlocks/components/Countdown.html.twig')]
final class Countdown extends PrelineComponent
{
    protected function defaults(): array
    {
        return [
            'countdown_style' => 'boxed',
            'show_label' => true,
            'show_separator' => true,
            'size' => 'md',
            'color' => 'default',
            'align' => 'center',
            'on_expire' => 'message',
            'expire_message' => 'Countdown expired!',
            'label_days' => 'Days',
            'label_hours' => 'Hours',
            'label_minutes' => 'Minutes',
            'label_seconds' => 'Seconds',
        ];
    }

    protected function dataKeys(): array
    {
        return ['date'];
    }

    public function shouldRender(): bool
    {
        return $this->hasAny('date', 'target_date');
    }

    public function getTargetIsoDate(): string
    {
        $date = $this->get('date') ?? $this->get('target_date');
        if ($date instanceof \DateTimeInterface) {
            return $date->format(\DateTimeInterface::ATOM);
        }

        if (\is_string($date) && '' !== \trim($date)) {
            $trimmed = \trim($date);
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
        return $this->enumProp('countdown_style', ['boxed', 'minimal', 'pill'], 'boxed');
    }

    public function isShowLabel(): bool
    {
        return $this->boolProp('show_label', true);
    }

    public function isShowSeparator(): bool
    {
        return $this->boolProp('show_separator', true);
    }

    public function getAlignClasses(string $default = 'center', string $key = 'align'): string
    {
        return $this->getAlignWithTextClasses($default, $key);
    }

    public function getNumberClasses(): string
    {
        $size = $this->stringProp('size', 'md');
        $color = $this->stringProp('color', 'default');

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::COUNTDOWN_NUMBER_SIZES,
            $size,
            PrelineClassMap::COUNTDOWN_NUMBER_SIZES['md'],
        );

        $colorClasses = match ($color) {
            'primary' => 'text-primary',
            'white' => 'text-white',
            default => 'text-foreground',
        };

        return \sprintf('%s %s tabular-nums tracking-tight', $sizeClasses, $colorClasses);
    }

    public function getCardClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::COUNTDOWN_CARD_STYLES,
            $this->getCountdownStyle(),
            PrelineClassMap::COUNTDOWN_CARD_STYLES['boxed'],
        );
    }

    public function getSeparatorClasses(): string
    {
        $size = $this->stringProp('size', 'md');
        $color = $this->stringProp('color', 'default');

        $sizeClasses = PrelineClassMap::resolve(
            PrelineClassMap::COUNTDOWN_SEPARATOR_SIZES,
            $size,
            PrelineClassMap::COUNTDOWN_SEPARATOR_SIZES['md'],
        );

        $colorClasses = match ($color) {
            'primary' => 'text-primary-400 dark:text-primary-500',
            'white' => 'text-white/60',
            default => 'text-muted-foreground',
        };

        return \sprintf('flex items-center %s %s px-1 select-none self-center', $sizeClasses, $colorClasses);
    }

    public function getLabelClasses(): string
    {
        return 'mt-1 text-xs sm:text-sm font-medium uppercase tracking-wider text-muted-foreground-1';
    }

    public function getOnExpire(): string
    {
        return $this->enumProp('on_expire', ['message', 'reload', 'hide'], 'message');
    }

    public function getExpireMessage(): string
    {
        return $this->stringProp('expire_message', 'Countdown expired!');
    }

    /**
     * @return array<string, string>
     */
    public function getLabels(): array
    {
        return [
            'days' => $this->stringProp('label_days', 'Days'),
            'hours' => $this->stringProp('label_hours', 'Hours'),
            'minutes' => $this->stringProp('label_minutes', 'Minutes'),
            'seconds' => $this->stringProp('label_seconds', 'Seconds'),
        ];
    }

    public function getWrapperClasses(string $extraBase = ''): string
    {
        $base = \trim('w-full flex flex-col ' . $this->getAlignClasses() . ' ' . $extraBase);

        return parent::getWrapperClasses($base);
    }
}
