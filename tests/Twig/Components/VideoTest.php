<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Video;

final class VideoTest extends TestCase
{
    public function testStandaloneAndBareVideoModes(): void
    {
        $video = new Video();
        self::assertFalse($video->shouldRender());

        $video->mergeProps([
            'video' => ['url' => '/uploads/promo.mp4'],
            'hover_video' => ['url' => '/uploads/promo-hover.mp4'],
            'image' => [
                'url' => '/uploads/poster.jpg',
                'title' => 'Promo Poster',
                'focusPoint' => ['x' => 50, 'y' => 50],
            ],
            'link' => 'https://example.com/watch',
            'props' => [
                'autoplay' => 'inview',
                'controls' => false,
                'loop' => true,
                'aspect_ratio' => '16/9',
                'rounded' => '2xl',
            ],
        ]);

        self::assertTrue($video->shouldRender());
        self::assertFalse($video->isBare());
        self::assertTrue($video->isAutoplay());
        self::assertSame('inview', $video->getAutoplayMode());
        self::assertFalse($video->isControls());
        self::assertTrue($video->isLoop());
        self::assertTrue($video->isMuted());
        self::assertSame('/uploads/promo.mp4', $video->getVideoUrl());
        self::assertSame('/uploads/promo-hover.mp4', $video->getHoverVideoUrl());
        self::assertSame('/uploads/poster.jpg', $video->getPosterUrl());
        self::assertSame('Promo Poster', $video->getPosterAlt());
        self::assertSame('object-position: 50% 50%;', $video->getPosterFocusPointStyle());
        self::assertSame('https://example.com/watch', $video->getUrl());
        self::assertStringContainsString('rounded-2xl', $video->getWrapperClasses());
        self::assertStringContainsString('aspect-video', $video->getWrapperClasses());

        // Bare mode with custom classes and preload
        $bareVideo = new Video();
        $bareVideo->mergeProps([
            'bare' => true,
            'video_url' => '/uploads/clip.mp4',
            'hover_video_url' => '/uploads/clip-hover.mp4',
            'preload' => 'metadata',
            'video_class' => 'custom-bare-video',
            'hover_video_class' => 'custom-hover-video',
        ]);

        self::assertTrue($bareVideo->isBare());
        self::assertSame('metadata', $bareVideo->getPreload());
        self::assertSame('custom-bare-video', $bareVideo->getVideoClasses());
        self::assertSame('custom-hover-video', $bareVideo->getHoverVideoClasses());
    }
}
