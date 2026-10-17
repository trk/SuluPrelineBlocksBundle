<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Layout;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

final class ResponsiveWidthTest extends TestCase
{
    public function testContainerFollowsViewportUntilItsMaximumWidth(): void
    {
        $slot = ResponsiveWidth::container(1360);

        self::assertSame('(min-width: 1360px) 1360px, 100vw', $slot->toSizes());
        self::assertSame(1360, $slot->maxSlotPx());
    }

    public function testFullWidthContainerIsAlwaysViewportRelative(): void
    {
        $slot = ResponsiveWidth::container(null)->scale(['base' => 1.0, 'md' => 0.5]);

        self::assertSame('(min-width: 768px) 50vw, 100vw', $slot->toSizes());
    }

    public function testNestedFractionsMultiplyPerBreakpoint(): void
    {
        // Section 1360 → column 8/12 from md → grid 2 cols (md) / 3 cols (lg) → horizontal card media 1/2 from md.
        $slot = ResponsiveWidth::container(1360)
            ->scale(['base' => 1.0, 'md' => 8 / 12])
            ->divide(['base' => 1, 'md' => 2, 'lg' => 3])
            ->scale(['base' => 1.0, 'md' => 0.5]);

        self::assertSame(
            '(min-width: 1360px) 152px, (min-width: 1024px) 11.11vw, (min-width: 768px) 16.67vw, 100vw',
            $slot->toSizes(),
        );
        // The widest rendering is the stacked tablet layout (full width just below `md`).
        self::assertSame(768, $slot->maxSlotPx());
    }

    public function testCapSwitchesToPixelsExactlyWhereItIsReached(): void
    {
        self::assertSame('(min-width: 448px) 448px, 100vw', ResponsiveWidth::container(1360)->cap(448)->toSizes());

        // Capped column: full width until 400px, half width from md until 50% of the viewport reaches 400px.
        self::assertSame(
            '(min-width: 800px) 400px, (min-width: 768px) 50vw, (min-width: 400px) 400px, 100vw',
            ResponsiveWidth::container(1360)->scale(['md' => 0.5])->cap(400)->toSizes(),
        );
    }

    public function testCapNeverWidensTheSlot(): void
    {
        $slot = ResponsiveWidth::container(1360)->cap(400)->cap(800);

        self::assertSame(400, $slot->maxSlotPx());
    }

    public function testFixedSizesForLayoutIndependentElements(): void
    {
        self::assertSame(
            '(min-width: 768px) 352px, (min-width: 640px) 320px, 288px',
            ResponsiveWidth::fixedSizes(['base' => 288, 'sm' => 320, 'md' => 352]),
        );
    }

    public function testSerializationRoundTripAndMalformedInput(): void
    {
        $slot = ResponsiveWidth::container(1280)->divide(['base' => 1, 'lg' => 4])->cap(300);
        $restored = ResponsiveWidth::fromArray($slot->toArray());

        self::assertNotNull($restored);
        self::assertSame($slot->toSizes(), $restored->toSizes());
        self::assertNull(ResponsiveWidth::fromArray(null));
        self::assertNull(ResponsiveWidth::fromArray(['container' => '1360', 'fractions' => []]));
        self::assertNull(ResponsiveWidth::fromArray(['container' => 1360, 'fractions' => ['base' => 1.0]]));
    }

    public function testCascadeInheritsFromTheNearestSmallerBreakpoint(): void
    {
        self::assertSame(
            ['base' => 1, 'sm' => 2, 'md' => 2, 'lg' => 4, 'xl' => 4, '2xl' => 4],
            ResponsiveWidth::cascade(['sm' => 2, 'lg' => 4], 1),
        );
    }
}
