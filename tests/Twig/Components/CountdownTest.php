<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Countdown;

final class CountdownTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $countdown = new Countdown();

        self::assertNull($countdown->date);
        self::assertNull($countdown->title);
        self::assertSame('boxed', $countdown->getCountdownStyle());
        self::assertTrue($countdown->isShowLabel());
        self::assertTrue($countdown->isShowSeparator());
        self::assertStringContainsString('justify-center', $countdown->getAlignClasses());
        self::assertStringContainsString('min-w-', $countdown->getCardClasses());
        self::assertStringContainsString('tabular-nums', $countdown->getNumberClasses());
        self::assertSame('message', $countdown->getOnExpire());
    }

    public function testDateConversion(): void
    {
        $countdown = new Countdown();
        $date = new \DateTimeImmutable('2028-12-31 23:59:59+00:00');
        $countdown->date = $date;

        self::assertSame('2028-12-31T23:59:59+00:00', $countdown->getTargetIsoDate());

        $countdownStr = new Countdown();
        $countdownStr->date = '2029-01-01 12:00:00';
        self::assertStringContainsString('2029-01-01', $countdownStr->getTargetIsoDate());
    }

    public function testStylesAndSizes(): void
    {
        $countdown = new Countdown();
        $countdown->props = [
            'countdown_style' => 'minimal',
            'size' => 'xl',
            'color' => 'primary',
            'show_label' => false,
            'show_separator' => false,
            'align' => 'left',
            'on_expire' => 'reload',
            'expire_message' => 'Done!',
        ];

        self::assertSame('minimal', $countdown->getCountdownStyle());
        self::assertFalse($countdown->isShowLabel());
        self::assertFalse($countdown->isShowSeparator());
        self::assertSame('reload', $countdown->getOnExpire());
        self::assertSame('Done!', $countdown->getExpireMessage());
        self::assertStringContainsString('justify-start', $countdown->getAlignClasses());
        self::assertStringContainsString('text-primary', $countdown->getNumberClasses());
        self::assertStringContainsString('text-5xl', $countdown->getNumberClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $countdown = new Countdown();
        $countdown->props = [
            'id' => 'sale-countdown',
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'custom_class' => 'my-countdown',
        ];

        self::assertSame('sale-countdown', $countdown->getElementId());
        $wrapper = $countdown->getWrapperClasses();
        self::assertStringContainsString('w-full flex flex-col', $wrapper);
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('my-countdown', $wrapper);
    }
}
