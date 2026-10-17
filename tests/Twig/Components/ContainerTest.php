<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Container;

final class ContainerTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $container = new Container();

        self::assertSame('85rem', $container->get('max_width'));
        self::assertEmpty($container->get('rows'));
        self::assertFalse($container->hasRows());

        self::assertSame('mx-auto max-w-[85rem] px-4 sm:px-6 lg:px-8', $container->getContainerClasses());
        self::assertSame(['class' => 'max-w-[85rem]', 'px' => 1360], $container->getMaxWidthPreset());
        self::assertSame(1360, $container->getSlotWidth()->toArray()['container']);
    }

    public function testContainerPresetsAndRootClasses(): void
    {
        $container = new Container();
        $container->mergeProps([
            'max_width' => 'container',
            'id' => 'inner-container',
            'custom_class' => 'my-container-root',
        ]);

        self::assertSame('inner-container', $container->getElementId());
        self::assertSame(['class' => 'container', 'px' => 1536], $container->getMaxWidthPreset());
        self::assertSame('mx-auto container px-4 sm:px-6 lg:px-8 my-container-root', $container->getContainerClasses());
        self::assertSame(1536, $container->getSlotWidth()->toArray()['container']);
    }

    public function testRowsPropagation(): void
    {
        $container = new Container();
        $container->mergeProps(['max_width' => '5xl']);
        $container->assign('rows', [
            [
                'props' => ['layout' => 'halves', 'gap' => 'md'],
                'columns' => [
                    ['props' => ['col_span' => '6']],
                    ['props' => ['col_span' => '6']],
                ],
            ],
        ]);

        self::assertTrue($container->hasRows());
        $rows = $container->getNormalizedRows();
        self::assertCount(1, $rows);
        self::assertSame('halves', $rows[0]['layout']);
        self::assertIsArray($rows[0]['_slot']);
        self::assertSame(1024, $rows[0]['_slot']['container']);
    }
}
