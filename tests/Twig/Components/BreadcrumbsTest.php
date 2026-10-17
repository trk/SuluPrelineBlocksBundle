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
        self::assertSame('', $breadcrumbs->getWrapperClasses());
        self::assertSame('w-full', $breadcrumbs->getBlockClasses());
        self::assertSame('flex items-center whitespace-nowrap justify-start', $breadcrumbs->getListClasses());
        self::assertSame([], $breadcrumbs->getNormalizedItems());
    }

    public function testBreadcrumbsAlignment(): void
    {
        $breadcrumbsCenter = new Breadcrumbs();
        $breadcrumbsCenter->assign('align', 'center');
        self::assertSame('justify-center', $breadcrumbsCenter->getAlignClasses());

        $breadcrumbsRight = new Breadcrumbs();
        $breadcrumbsRight->assign('align', 'right');
        self::assertSame('justify-end', $breadcrumbsRight->getAlignClasses());

        $breadcrumbsLeft = new Breadcrumbs();
        $breadcrumbsLeft->assign('align', 'left');
        self::assertSame('justify-start', $breadcrumbsLeft->getAlignClasses());
    }

    public function testBreadcrumbsItemNormalization(): void
    {
        $breadcrumbs = new Breadcrumbs();
        $breadcrumbs->assign('items', [
            ['title' => 'Blog', 'url' => '/blog'],
            ['title' => 'Articles', 'link' => '/blog/articles'],
            ['title' => 'Current Post'],
            ['title' => ''], // should be skipped
            ['invalid' => 'no title'], // should be skipped
        ]);

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
        $breadcrumbs->assign('home_title', 'Anasayfa');
        $breadcrumbs->assign('home_url', '/tr');
        $breadcrumbs->assign('show_home', false);

        self::assertFalse($breadcrumbs->isShowHome());
        self::assertSame('Anasayfa', $breadcrumbs->getHomeTitle());
        self::assertSame('/tr', $breadcrumbs->getHomeUrl());

        $breadcrumbs->assign('home_url', 'javascript:alert(1)');
        self::assertSame('/', $breadcrumbs->getHomeUrl());
    }

    public function testPageUuidResolutionAndItemSanitization(): void
    {
        $breadcrumbs = new Breadcrumbs(['context' => ['uuid' => 'page-uuid-123']]);
        self::assertSame('page-uuid-123', $breadcrumbs->getPageUuid());

        $fromView = new Breadcrumbs(['context' => ['view' => ['uuid' => 'view-uuid-456']]]);
        self::assertSame('view-uuid-456', $fromView->getPageUuid());

        $fromContent = new Breadcrumbs(['context' => ['content' => ['id' => 'content-id-789']]]);
        self::assertSame('content-id-789', $fromContent->getPageUuid());

        $breadcrumbs->assign('items', [
            ['title' => 'Safe', 'url' => '/safe'],
            ['title' => 'Unsafe', 'url' => 'javascript:alert(1)'],
        ]);
        $items = $breadcrumbs->getNormalizedItems();
        self::assertSame('/safe', $items[0]['url']);
        self::assertNull($items[1]['url']);
    }
}
