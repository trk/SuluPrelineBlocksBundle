<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Breadcrumbs;

final class BreadcrumbsTest extends TestCase
{
    public function testBreadcrumbsDefaults(): void
    {
        $breadcrumbs = new Breadcrumbs();

        self::assertTrue($breadcrumbs->isShowHome());
        self::assertSame('Home', $breadcrumbs->getHomeTitle());
        self::assertSame('/', $breadcrumbs->getHomeUrl());
        self::assertSame('justify-start', $breadcrumbs->getAlignClasses());
        self::assertSame([], $breadcrumbs->getNormalizedItems());
    }

    public function testBreadcrumbsAlignment(): void
    {
        $breadcrumbsCenter = new Breadcrumbs();
        $breadcrumbsCenter->align = 'center';
        self::assertSame('justify-center', $breadcrumbsCenter->getAlignClasses());

        $breadcrumbsRight = new Breadcrumbs();
        $breadcrumbsRight->align = 'right';
        self::assertSame('justify-end', $breadcrumbsRight->getAlignClasses());

        $breadcrumbsLeft = new Breadcrumbs();
        $breadcrumbsLeft->align = 'left';
        self::assertSame('justify-start', $breadcrumbsLeft->getAlignClasses());
    }

    public function testBreadcrumbsItemNormalization(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->items = [
            ['title' => 'Blog', 'url' => '/blog'],
            ['title' => 'Articles', 'link' => '/blog/articles'],
            ['title' => 'Current Post'],
            ['title' => ''], // should be skipped
            ['invalid' => 'no title'], // should be skipped
        ];

        $normalized = $breadcrumbs->getNormalizedItems();

        self::assertCount(3, $normalized);
        self::assertSame('Blog', $normalized[0]['title']);
        self::assertSame('/blog', $normalized[0]['url']);
        self::assertSame('Articles', $normalized[1]['title']);
        self::assertSame('/blog/articles', $normalized[1]['url']);
        self::assertSame('Current Post', $normalized[2]['title']);
        self::assertNull($normalized[2]['url']);
    }

    public function testCustomHomeSettings(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->home_title = 'Anasayfa';
        $breadcrumbs->home_url = '/tr';
        $breadcrumbs->show_home = false;

        self::assertFalse($breadcrumbs->isShowHome());
        self::assertSame('Anasayfa', $breadcrumbs->getHomeTitle());
        self::assertSame('/tr', $breadcrumbs->getHomeUrl());
    }
}
