<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Section;

final class SectionTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $sec = new Section();

        self::assertSame('85rem', $sec->max_width);
        self::assertSame('default', $sec->padding_y);
        self::assertSame('default', $sec->padding_x);
        self::assertSame('transparent', $sec->bg_style);
        self::assertFalse($sec->has_border);
        self::assertEmpty($sec->rows);

        self::assertFalse($sec->hasRows());

        self::assertSame('relative w-full overflow-hidden bg-transparent', $sec->getSectionClasses());
        self::assertStringContainsString('max-w-[85rem]', $sec->getContainerClasses());
        self::assertStringContainsString('py-10 sm:py-14', $sec->getContainerClasses());
    }

    public function testStylesAndContainersFromProps(): void
    {
        $sec = new Section();
        $sec->props = [
            'bg_style' => 'muted',
            'has_border' => true,
            'max_width' => '7xl',
            'padding_y' => 'lg',
            'padding_x' => 'none',
            'id' => 'features-section',
            'custom_class' => 'my-custom-sec',
        ];

        self::assertSame('features-section', $sec->getElementId());
        self::assertStringContainsString('bg-background-1', $sec->getSectionClasses());
        self::assertStringContainsString('border-t border-b', $sec->getSectionClasses());
        self::assertStringContainsString('my-custom-sec', $sec->getSectionClasses());
        self::assertStringContainsString('max-w-7xl', $sec->getContainerClasses());
        self::assertStringContainsString('py-14 sm:py-20', $sec->getContainerClasses());
        self::assertStringContainsString('px-0', $sec->getContainerClasses());
    }

    public function testRowsSupport(): void
    {
        $sec = new Section();
        self::assertFalse($sec->hasRows());
        self::assertEmpty($sec->getNormalizedRows());

        $sec->rows = [
            [
                'props' => ['layout' => 'thirds', 'gap' => 'lg'],
                'columns' => [
                    ['props' => ['col_span' => '4']],
                    ['props' => ['col_span' => '8']],
                ],
            ],
        ];

        self::assertTrue($sec->hasRows());
        $rows = $sec->getNormalizedRows();
        self::assertCount(1, $rows);
        self::assertSame('thirds', $rows[0]['layout']);
        self::assertSame('lg', $rows[0]['gap']);
    }
}
