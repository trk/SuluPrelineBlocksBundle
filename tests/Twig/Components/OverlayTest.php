<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Overlay;

final class OverlayTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $overlay = new Overlay();

        self::assertNull($overlay->title);
        self::assertNull($overlay->badge);
        self::assertNull($overlay->meta);
        self::assertNull($overlay->content);
        self::assertNull($overlay->image);
        self::assertNull($overlay->video);
        self::assertNull($overlay->icon);
        self::assertSame([], $overlay->buttons);
        self::assertNull($overlay->hoverImage);
        self::assertNull($overlay->hover_image);
        self::assertNull($overlay->hoverVideo);
        self::assertNull($overlay->hover_video);
        self::assertSame([], $overlay->props);
        self::assertNull($overlay->getImageUrl());
        self::assertNull($overlay->getHoverImageUrl());
        self::assertNull($overlay->getVideoUrl());
        self::assertNull($overlay->getHoverVideoUrl());
        self::assertSame([], $overlay->getNormalizedButtons());
        self::assertSame('bottom', $overlay->getOverlayPosition());
        self::assertSame('justify-end items-stretch', $overlay->getOverlayPositionClasses());
        self::assertSame('always', $overlay->getOverlayMode());
        self::assertSame('aspect-video', $overlay->getMediaAspectRatioClass());
        self::assertSame('rounded-2xl', $overlay->getRoundedClasses());
        self::assertSame('shadow-md', $overlay->getShadowClasses());
    }

    public function testHoverImageAndVideoResolution(): void
    {
        $overlay = new Overlay();
        $overlay->hoverImage = 'https://cdn.example.com/hover.jpg';
        $overlay->hoverVideo = 'https://cdn.example.com/hover.mp4';

        self::assertSame('https://cdn.example.com/hover.jpg', $overlay->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $overlay->getHoverVideoUrl());

        // Snake case fallback
        $overlaySnake = new Overlay();
        $overlaySnake->hover_image = 'https://cdn.example.com/hover-snake.jpg';
        $overlaySnake->hover_video = 'https://cdn.example.com/hover-snake.mp4';

        self::assertSame('https://cdn.example.com/hover-snake.jpg', $overlaySnake->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover-snake.mp4', $overlaySnake->getHoverVideoUrl());
    }

    public function testNormalizedButtons(): void
    {
        $overlay = new Overlay();
        $overlay->buttons = [
            ['content' => 'Explore', 'link' => '/explore'],
            ['content' => 'Watch Demo', 'link' => '#demo'],
        ];

        $normalized = $overlay->getNormalizedButtons();
        self::assertCount(2, $normalized);
        self::assertSame('Explore', $normalized[0]['content']);
        self::assertSame('/explore', $normalized[0]['link']);
    }

    public function testOverlayPositionsAndClasses(): void
    {
        $overlay = new Overlay();

        $overlay->props = ['overlay_position' => 'center'];
        self::assertSame('center', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-center', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'top'];
        self::assertSame('top', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-stretch', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'bottom-left'];
        self::assertSame('bottom-left', $overlay->getOverlayPosition());
        self::assertSame('justify-end items-start', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'bottom-right'];
        self::assertSame('bottom-right', $overlay->getOverlayPosition());
        self::assertSame('justify-end items-end', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'top-left'];
        self::assertSame('top-left', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-start', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'top-right'];
        self::assertSame('top-right', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-end', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'left'];
        self::assertSame('left', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-start', $overlay->getOverlayPositionClasses());

        $overlay->props = ['overlay_position' => 'right'];
        self::assertSame('right', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-end', $overlay->getOverlayPositionClasses());
    }

    public function testOverlayModesAndTransitions(): void
    {
        $overlay = new Overlay();
        self::assertSame('always', $overlay->getOverlayMode());
        self::assertStringContainsString('transition-all', $overlay->getOverlayTransitionClasses());

        $overlay->props = ['overlay_mode' => 'hover', 'overlay_transition' => 'slide-up'];
        self::assertSame('hover', $overlay->getOverlayMode());
        self::assertStringContainsString('translate-y-4', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:translate-y-0', $overlay->getOverlayTransitionClasses());

        $overlay->props = ['overlay_mode' => 'hover', 'overlay_transition' => 'slide-down'];
        self::assertStringContainsString('-translate-y-4', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:translate-y-0', $overlay->getOverlayTransitionClasses());

        $overlay->props = ['overlay_mode' => 'hover', 'overlay_transition' => 'zoom'];
        self::assertStringContainsString('scale-95', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:scale-100', $overlay->getOverlayTransitionClasses());

        $overlay->props = ['overlay_mode' => 'hover', 'overlay_transition' => 'fade'];
        self::assertStringContainsString('transition-opacity', $overlay->getOverlayTransitionClasses());
    }

    public function testOverlayStyles(): void
    {
        $overlay = new Overlay();
        self::assertStringContainsString('bg-gradient-to-t', $overlay->getOverlayStyle());

        $overlay->props = ['overlay_style' => 'dark-glass'];
        self::assertStringContainsString('backdrop-blur-md bg-gray-950/60', $overlay->getOverlayStyle());

        $overlay->props = ['overlay_style' => 'light-glass'];
        self::assertStringContainsString('bg-white/80', $overlay->getOverlayStyle());

        $overlay->props = ['overlay_style' => 'primary'];
        self::assertStringContainsString('bg-primary-600/80', $overlay->getOverlayStyle());

        $overlay->props = ['overlay_style' => 'none'];
        self::assertSame('bg-transparent', $overlay->getOverlayStyle());
    }

    public function testAspectRatioAndTransitions(): void
    {
        $overlay = new Overlay();
        self::assertSame('aspect-video', $overlay->getMediaAspectRatioClass());

        $overlay->props = ['media_aspect_ratio' => '4-3'];
        self::assertSame('aspect-4/3', $overlay->getMediaAspectRatioClass());

        $overlay->props = ['media_aspect_ratio' => '1-1'];
        self::assertSame('aspect-square', $overlay->getMediaAspectRatioClass());

        $overlay->props = ['media_aspect_ratio' => '21-9'];
        self::assertSame('aspect-21/9', $overlay->getMediaAspectRatioClass());

        $overlay->props = ['media_aspect_ratio' => 'auto'];
        self::assertStringContainsString('aspect-auto', $overlay->getMediaAspectRatioClass());

        $overlay->props = ['image_transition' => 'scale-up'];
        self::assertStringContainsString('group-hover:scale-105', $overlay->getImageTransitionClasses());

        $overlay->props = ['image_transition' => 'none'];
        self::assertSame('transition-opacity duration-300 ease-out', $overlay->getImageTransitionClasses());
    }

    public function testBadgeAndPositions(): void
    {
        $overlay = new Overlay();
        $overlay->badge = 'Featured Story';

        self::assertSame('Featured Story', $overlay->badge);
        self::assertStringContainsString('backdrop-blur-md', $overlay->getBadgeClasses());

        $overlay->props = ['badge_style' => 'primary'];
        self::assertStringContainsString('bg-primary-600', $overlay->getBadgeClasses());

        $overlay->props = ['badge_position' => 'top-right'];
        self::assertSame('top-right', $overlay->getBadgePosition());
        self::assertSame('absolute top-3 end-3 z-20', $overlay->getBadgePositionClasses());

        $overlay->props = ['badge_position' => 'bottom-left'];
        self::assertSame('bottom-left', $overlay->getBadgePosition());
        self::assertSame('absolute bottom-3 start-3 z-20', $overlay->getBadgePositionClasses());

        $overlay->props = ['badge_position' => 'inline'];
        self::assertSame('inline', $overlay->getBadgePosition());
        self::assertSame('', $overlay->getBadgePositionClasses());
    }

    public function testLinkAndClickableOverlay(): void
    {
        $overlay = new Overlay();
        $overlay->link = '/case-studies/enterprise';
        $overlay->linkTitle = 'Case Study';

        self::assertSame('/case-studies/enterprise', $overlay->getLinkUrl());
        self::assertSame('Case Study', $overlay->getButtonText());
        self::assertTrue($overlay->isEntireOverlayClickable());

        // Button only
        $overlay->props = ['link_overlay' => 'none'];
        self::assertFalse($overlay->isEntireOverlayClickable());
    }

    public function testMediaMutualExclusivity(): void
    {
        $overlay = new Overlay();
        $overlay->image = 'https://cdn.example.com/cover.jpg';
        $overlay->video = 'https://cdn.example.com/cover.mp4';

        // Video takes priority
        self::assertTrue($overlay->isVideo());
        self::assertFalse($overlay->isImage());
        self::assertSame('https://cdn.example.com/cover.mp4', $overlay->getActiveVideoUrl());
        self::assertNull($overlay->getActiveImageUrl());

        // Image only
        $overlayImage = new Overlay();
        $overlayImage->image = 'https://cdn.example.com/cover.jpg';
        self::assertTrue($overlayImage->isImage());
        self::assertFalse($overlayImage->isVideo());
        self::assertSame('https://cdn.example.com/cover.jpg', $overlayImage->getActiveImageUrl());
        self::assertNull($overlayImage->getActiveVideoUrl());
    }

    public function testWrapperClassesAndId(): void
    {
        $overlay = new Overlay();
        $overlay->props = [
            'id' => 'hero-overlay',
            'custom_class' => 'my-custom-overlay',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
            'rounded' => '3xl',
            'shadow' => 'xl',
        ];

        self::assertSame('hero-overlay', $overlay->getElementId());
        $wrapper = $overlay->getWrapperClasses();
        self::assertStringContainsString('group relative overflow-hidden', $wrapper);
        self::assertStringContainsString('rounded-3xl', $wrapper);
        self::assertStringContainsString('shadow-xl', $wrapper);
        self::assertStringContainsString('mt-10', $wrapper);
        self::assertStringContainsString('mb-16', $wrapper);
        self::assertStringContainsString('my-custom-overlay', $wrapper);
    }
}
