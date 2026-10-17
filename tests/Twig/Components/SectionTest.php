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

        self::assertSame('85rem', $sec->get('max_width'));
        self::assertSame('default', $sec->get('padding'));
        self::assertSame('transparent', $sec->get('bg_style'));
        self::assertEmpty($sec->get('rows'));

        self::assertFalse($sec->hasRows());

        self::assertSame('relative overflow-hidden bg-transparent py-10 sm:py-14', $sec->getSectionClasses());
        self::assertSame('mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8', $sec->getContainerClasses());
    }

    public function testStylesAndContainersFromProps(): void
    {
        $sec = new Section();
        $sec->mergeProps([
            'bg_style' => 'muted',
            'max_width' => '7xl',
            'padding' => 'lg',
            'padding_remove_top' => true,
            'margin' => 'md',
            'margin_remove_bottom' => true,
            'id' => 'features-section',
            'custom_class' => 'my-custom-sec',
            'maxwidth' => 'small',
            'block_align' => 'right',
        ]);

        self::assertSame('features-section', $sec->getElementId());
        self::assertStringContainsString('bg-background-1', $sec->getSectionClasses());
        self::assertStringContainsString('py-14 sm:py-20', $sec->getSectionClasses());
        self::assertStringContainsString('pt-0 sm:pt-0', $sec->getSectionClasses());
        self::assertStringContainsString('mt-6 mb-0', $sec->getSectionClasses());
        self::assertStringContainsString('my-custom-sec', $sec->getSectionClasses());
        self::assertStringNotContainsString('max-w-', $sec->getSectionClasses());
        self::assertStringNotContainsString('ml-auto', $sec->getSectionClasses());

        self::assertSame('mx-auto max-w-7xl px-4 sm:px-6 lg:px-8', $sec->getContainerClasses());
        self::assertSame([
            'max_width' => '7xl',
        ], $sec->getContainerProps());
    }

    public function testPrelineContainerPreset(): void
    {
        $sec = new Section();
        $sec->mergeProps(['max_width' => 'container']);

        self::assertSame(['class' => 'container', 'px' => 1536], $sec->getMaxWidthPreset());
        self::assertSame('mx-auto container px-4 sm:px-6 lg:px-8', $sec->getContainerClasses());
        self::assertSame(1536, $sec->getSlotWidth()->toArray()['container']);
    }

    public function testRowsSupport(): void
    {
        $sec = new Section();
        self::assertFalse($sec->hasRows());
        self::assertEmpty($sec->getNormalizedRows());

        $sec->assign('rows', [
            [
                'props' => ['layout' => 'thirds', 'gap' => 'lg'],
                'columns' => [
                    ['props' => ['col_span' => '4']],
                    ['props' => ['col_span' => '8']],
                ],
            ],
        ]);

        self::assertTrue($sec->hasRows());
        $rows = $sec->getNormalizedRows();
        self::assertCount(1, $rows);
        self::assertSame('thirds', $rows[0]['layout']);
        self::assertSame('lg', $rows[0]['gap']);
    }
}
