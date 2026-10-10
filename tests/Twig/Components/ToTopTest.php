<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ToTop;

final class ToTopTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $toTop = new ToTop();

        self::assertNull($toTop->title);
        self::assertSame('bottom_right', $toTop->position);
        self::assertFalse($toTop->show_label);
        self::assertSame('fixed bottom-6 end-6 z-50', $toTop->getPositionClasses());
        self::assertStringContainsString('rounded-full bg-primary-600', $toTop->getButtonClasses());
    }

    public function testBottomLeftPosition(): void
    {
        $toTop = new ToTop();
        $toTop->position = 'bottom_left';

        self::assertSame('fixed bottom-6 start-6 z-50', $toTop->getPositionClasses());
    }
}
