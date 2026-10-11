<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PageHeader;

final class PageHeaderTest extends TestCase
{
    public function testPageHeaderDefaults(): void
    {
        $header = new PageHeader();

        self::assertSame('', $header->getTitle());
        self::assertNull($header->getMeta());
        self::assertNull($header->getContent());
        self::assertTrue($header->isShowBreadcrumbs());
        self::assertSame('standard', $header->getLayout());
        self::assertSame('85rem', $header->getMaxWidth());
        self::assertSame('default', $header->getPaddingY());
        self::assertFalse($header->hasMedia());
        self::assertFalse($header->isImage());
        self::assertFalse($header->isVideo());
        self::assertFalse($header->hasIcon());
    }

    public function testPageHeaderLayouts(): void
    {
        $header = new PageHeader();

        $header->layout = 'split';
        self::assertSame('split', $header->getLayout());

        $header->layout = 'minimal';
        self::assertSame('minimal', $header->getLayout());

        $header->layout = 'unknown';
        self::assertSame('standard', $header->getLayout());
    }

    public function testPageHeaderContentAndMetaResolution(): void
    {
        $header = new PageHeader();
        $header->title = 'About Us';
        $header->meta = 'Company Profile';
        $header->content = '<p>Welcome to Totoglu.</p>';

        self::assertSame('About Us', $header->getTitle());
        self::assertSame('Company Profile', $header->getMeta());
        self::assertSame('<p>Welcome to Totoglu.</p>', $header->getContent());
    }

    public function testPageHeaderMediaResolution(): void
    {
        $header = new PageHeader();
        $header->image = ['url' => '/uploads/media/hero.jpg'];
        $header->hover_image = ['url' => '/uploads/media/hero-hover.jpg'];
        $header->video = ['url' => '/uploads/media/intro.mp4'];
        $header->icon = 'ti-star';

        self::assertTrue($header->hasMedia());
        self::assertTrue($header->isImage());
        self::assertTrue($header->isVideo());
        self::assertTrue($header->hasIcon());
        self::assertSame('/uploads/media/hero.jpg', $header->getImageUrl());
        self::assertSame('/uploads/media/hero-hover.jpg', $header->getHoverImageUrl());
        self::assertSame('/uploads/media/intro.mp4', $header->getVideoUrl());
        self::assertSame('ti ti-star', $header->getIconClass());
    }

    public function testBreadcrumbVisibilityToggle(): void
    {
        $header = new PageHeader();
        self::assertTrue($header->isShowBreadcrumbs());

        $header->show_breadcrumbs = false;
        self::assertFalse($header->isShowBreadcrumbs());
    }
}
