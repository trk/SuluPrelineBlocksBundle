<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Social;

final class SocialTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $social = new Social();

        self::assertNull($social->title);
        self::assertEmpty($social->getNormalizedItems());
        self::assertSame('icon_only', $social->getStyle());
        self::assertSame('md', $social->getSize());
        self::assertSame('rounded', $social->getShape());
        self::assertSame('justify-start', $social->getAlignment());
        self::assertTrue($social->isOpenNewTab());
        self::assertStringContainsString('size-9', $social->getItemClasses());
        self::assertStringContainsString('rounded-lg', $social->getItemClasses());
    }

    public function testCustomProps(): void
    {
        $social = new Social();
        $social->props = [
            'style' => 'buttons',
            'size' => 'lg',
            'shape' => 'circle',
            'alignment' => 'center',
            'open_new_tab' => false,
        ];

        self::assertSame('buttons', $social->getStyle());
        self::assertSame('lg', $social->getSize());
        self::assertSame('circle', $social->getShape());
        self::assertSame('justify-center', $social->getAlignment());
        self::assertFalse($social->isOpenNewTab());
        self::assertStringContainsString('size-11', $social->getItemClasses());
        self::assertStringContainsString('rounded-full', $social->getItemClasses());
        self::assertStringContainsString('hover:bg-primary-600', $social->getItemClasses());
    }

    public function testNormalizedItems(): void
    {
        $social = new Social();
        $social->items = [
            [
                'network' => 'github',
                'link' => 'https://github.com/trk',
                'title' => 'GitHub Profile',
                'badge' => '1.2k',
            ],
            [
                'network' => 'x',
                'link' => ['url' => 'https://x.com/trk'],
            ],
            [
                // Item without link should be excluded
                'network' => 'linkedin',
                'link' => '',
            ],
        ];

        $items = $social->getNormalizedItems();

        self::assertCount(2, $items);
        self::assertSame('github', $items[0]['network']);
        self::assertSame('https://github.com/trk', $items[0]['link']);
        self::assertSame('GitHub Profile', $items[0]['title']);
        self::assertSame('1.2k', $items[0]['badge']);

        self::assertSame('x', $items[1]['network']);
        self::assertSame('https://x.com/trk', $items[1]['link']);
        self::assertSame('X', $items[1]['title']);
    }
}
