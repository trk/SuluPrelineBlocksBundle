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

        self::assertFalse($overlay->shouldRender());
        self::assertTrue($overlay->is('overlay'));
        self::assertNull($overlay->get('title'));
        self::assertNull($overlay->get('badge'));
        self::assertNull($overlay->get('meta'));
        self::assertNull($overlay->get('content'));
        self::assertNull($overlay->get('image'));
        self::assertNull($overlay->get('video'));
        self::assertNull($overlay->get('icon'));
        self::assertSame([], $overlay->items('buttons'));
        self::assertNull($overlay->get('hoverImage'));
        self::assertNull($overlay->get('hover_image'));
        self::assertNull($overlay->get('hoverVideo'));
        self::assertNull($overlay->get('hover_video'));
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
        $overlay->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $overlay->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        self::assertSame('https://cdn.example.com/hover.jpg', $overlay->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $overlay->getHoverVideoUrl());

        // Snake case fallback
        $overlaySnake = new Overlay();
        $overlaySnake->assign('hover_image', 'https://cdn.example.com/hover-snake.jpg');
        $overlaySnake->assign('hover_video', 'https://cdn.example.com/hover-snake.mp4');

        self::assertSame('https://cdn.example.com/hover-snake.jpg', $overlaySnake->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover-snake.mp4', $overlaySnake->getHoverVideoUrl());
    }

    public function testMediaSourceAndExternalUrls(): void
    {
        $overlay = new Overlay();
        $overlay->assign('mediaType', 'image');
        $overlay->assign('mediaSource', 'url');
        $overlay->assign('imageUrl', 'https://cdn.example.com/overlay-image.jpg');
        $overlay->assign('hoverImageUrl', 'https://cdn.example.com/overlay-hover.jpg');

        self::assertSame('https://cdn.example.com/overlay-image.jpg', $overlay->getImageUrl());
        self::assertSame('https://cdn.example.com/overlay-hover.jpg', $overlay->getHoverImageUrl());
        self::assertTrue($overlay->isImage());
        self::assertFalse($overlay->isVideo());

        // Snake case fallback
        $overlaySnake = new Overlay();
        $overlaySnake->assign('media_type', 'video');
        $overlaySnake->assign('media_source', 'url');
        $overlaySnake->assign('video_url', 'https://cdn.example.com/overlay-video.mp4');
        $overlaySnake->assign('hover_video_url', 'https://cdn.example.com/overlay-hover-video.mp4');

        self::assertSame('https://cdn.example.com/overlay-video.mp4', $overlaySnake->getVideoUrl());
        self::assertSame('https://cdn.example.com/overlay-hover-video.mp4', $overlaySnake->getHoverVideoUrl());
        self::assertTrue($overlaySnake->isVideo());
        self::assertFalse($overlaySnake->isImage());

        // Icon exclusivity
        $overlayIcon = new Overlay();
        $overlayIcon->assign('mediaType', 'icon');
        $overlayIcon->assign('icon', 'ti ti-star');
        self::assertSame('ti ti-star', $overlayIcon->getIconClass());

        $overlayImageWithIcon = new Overlay();
        $overlayImageWithIcon->assign('mediaType', 'image');
        $overlayImageWithIcon->assign('icon', 'ti ti-star');
        self::assertNull($overlayImageWithIcon->getIconClass());
    }

    public function testNormalizedButtons(): void
    {
        $overlay = new Overlay();
        $overlay->assign('buttons', [
            ['content' => 'Explore', 'link' => '/explore'],
            ['content' => 'Watch Demo', 'link' => '#demo'],
        ]);

        $normalized = $overlay->getNormalizedButtons();
        self::assertCount(2, $normalized);
        self::assertSame('Explore', $normalized[0]['content']);
        self::assertSame('/explore', $normalized[0]['link']);
    }

    public function testOverlayPositionsAndClasses(): void
    {
        $overlay = new Overlay();

        $overlay->mergeProps(['overlay_position' => 'center']);
        self::assertSame('center', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-center', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'top']);
        self::assertSame('top', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-stretch', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'bottom-left']);
        self::assertSame('bottom-left', $overlay->getOverlayPosition());
        self::assertSame('justify-end items-start', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'bottom-right']);
        self::assertSame('bottom-right', $overlay->getOverlayPosition());
        self::assertSame('justify-end items-end', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'top-left']);
        self::assertSame('top-left', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-start', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'top-right']);
        self::assertSame('top-right', $overlay->getOverlayPosition());
        self::assertSame('justify-start items-end', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'left']);
        self::assertSame('left', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-start', $overlay->getOverlayPositionClasses());

        $overlay->mergeProps(['overlay_position' => 'right']);
        self::assertSame('right', $overlay->getOverlayPosition());
        self::assertSame('justify-center items-end', $overlay->getOverlayPositionClasses());
    }

    public function testOverlayModesAndTransitions(): void
    {
        $overlay = new Overlay();
        self::assertSame('always', $overlay->getOverlayMode());
        self::assertStringContainsString('transition-all', $overlay->getOverlayTransitionClasses());

        $overlay->mergeProps(['overlay_mode' => 'hover', 'overlay_transition' => 'slide-up']);
        self::assertSame('hover', $overlay->getOverlayMode());
        self::assertStringContainsString('translate-y-4', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:translate-y-0', $overlay->getOverlayTransitionClasses());

        $overlay->mergeProps(['overlay_mode' => 'hover', 'overlay_transition' => 'slide-down']);
        self::assertStringContainsString('-translate-y-4', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:translate-y-0', $overlay->getOverlayTransitionClasses());

        $overlay->mergeProps(['overlay_mode' => 'hover', 'overlay_transition' => 'zoom']);
        self::assertStringContainsString('scale-95', $overlay->getOverlayTransitionClasses());
        self::assertStringContainsString('group-hover:scale-100', $overlay->getOverlayTransitionClasses());

        $overlay->mergeProps(['overlay_mode' => 'hover', 'overlay_transition' => 'fade']);
        self::assertStringContainsString('transition-opacity', $overlay->getOverlayTransitionClasses());
    }

    public function testOverlayStyles(): void
    {
        $overlay = new Overlay();
        self::assertStringContainsString('bg-gradient-to-t', $overlay->getOverlayStyle());

        $overlay->mergeProps(['overlay_style' => 'dark-glass']);
        self::assertStringContainsString('backdrop-blur-md bg-black/60', $overlay->getOverlayStyle());

        $overlay->mergeProps(['overlay_style' => 'light-glass']);
        self::assertStringContainsString('bg-layer/80', $overlay->getOverlayStyle());

        $overlay->mergeProps(['overlay_style' => 'primary']);
        self::assertStringContainsString('bg-primary/80', $overlay->getOverlayStyle());

        $overlay->mergeProps(['overlay_style' => 'none']);
        self::assertSame('bg-transparent', $overlay->getOverlayStyle());
    }

    public function testAspectRatioAndTransitions(): void
    {
        $overlay = new Overlay();
        self::assertSame('aspect-video', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps(['media_aspect_ratio' => '4-3']);
        self::assertSame('aspect-4/3', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps(['media_aspect_ratio' => '1-1']);
        self::assertSame('aspect-square', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps(['media_aspect_ratio' => '21-9']);
        self::assertSame('aspect-21/9', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps(['media_aspect_ratio' => 'auto']);
        self::assertStringContainsString('aspect-auto', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps(['aspect_ratio' => '16:9']);
        self::assertSame('aspect-video', $overlay->getMediaAspectRatioClass());

        $overlay->mergeProps([
            'aspect_ratio' => 'manual',
            'aspect_ratio_width' => 16,
            'aspect_ratio_height' => 10,
            'object_fit' => 'contain',
        ]);
        self::assertSame('aspect-auto', $overlay->getMediaAspectRatioClass());
        self::assertSame('aspect-ratio: 16 / 10;', $overlay->getMediaAspectRatioStyle());
        self::assertSame('aspect-ratio: 16 / 10;', $overlay->getWrapperStyles());
        self::assertSame('object-contain', $overlay->getObjectFitClass());

        $overlay->assign('image', [
            'url' => '/uploads/media/overlay.jpg',
            'formats' => [
                'col-sm' => '/uploads/media/col-sm.webp',
                'col-md' => '/uploads/media/col-md.webp',
            ],
            'focalPoint' => ['x' => 40, 'y' => 60],
        ]);
        self::assertNotNull($overlay->getImageSrcSet());
        self::assertStringContainsString('/uploads/media/col-sm.webp 400w', (string) $overlay->getImageSrcSet());
        self::assertSame('object-position: 40% 60%;', $overlay->getImageFocusPointStyle());

        $overlay->mergeProps(['image_transition' => 'scale-up']);
        self::assertStringContainsString('group-hover:scale-105', $overlay->getImageTransitionClasses());

        $overlay->mergeProps(['image_transition' => 'none']);
        self::assertSame('transition-opacity duration-300 ease-out', $overlay->getImageTransitionClasses());
    }

    public function testBadgeAndPositions(): void
    {
        $overlay = new Overlay();
        $overlay->assign('badge', 'Featured Story');

        self::assertSame('Featured Story', $overlay->get('badge'));
        self::assertStringContainsString('backdrop-blur-md', $overlay->getBadgeClasses());

        $overlay->mergeProps(['badge_style' => 'primary']);
        self::assertStringContainsString('bg-primary', $overlay->getBadgeClasses());

        $overlay->mergeProps(['badge_position' => 'top-right']);
        self::assertSame('top-right', $overlay->getBadgePosition());
        self::assertSame('absolute top-3 end-3 z-20 flex flex-wrap gap-1.5', $overlay->getBadgePositionClasses());

        $overlay->mergeProps(['badge_position' => 'bottom-left']);
        self::assertSame('bottom-left', $overlay->getBadgePosition());
        self::assertSame('absolute bottom-3 start-3 z-20 flex flex-wrap gap-1.5', $overlay->getBadgePositionClasses());

        $overlay->mergeProps(['badge_position' => 'inline']);
        self::assertSame('inline', $overlay->getBadgePosition());
        self::assertSame('', $overlay->getBadgePositionClasses());
    }

    public function testMultipleBadgesAndButtons(): void
    {
        $overlay = new Overlay();
        $overlay->mergeProps([
            'badge_style' => 'glass',
            'badge_color' => 'primary',
            'button_style' => 'solid',
            'button_color' => 'white',
            'button_size' => 'sm',
        ]);

        $overlay->assign('badges', [
            [
                'title' => 'Hot Deal',
                'badge_style' => 'inherit',
                'badge_color' => 'inherit',
            ],
            [
                'title' => 'New',
                'badge_style' => 'solid',
                'badge_color' => 'success',
            ],
        ]);

        $overlay->assign('buttons', [
            [
                'title' => 'View Overlay',
                'link' => '/details',
                'button_style' => 'inherit',
                'button_color' => 'inherit',
            ],
        ]);

        self::assertTrue($overlay->hasBadges());
        $badges = $overlay->getNormalizedBadges();
        self::assertCount(2, $badges);
        self::assertSame('Hot Deal', $badges[0]['title']);
        self::assertIsString($badges[0]['classes']);
        self::assertStringContainsString('backdrop-blur-md', $badges[0]['classes']);

        self::assertSame('New', $badges[1]['title']);
        self::assertIsString($badges[1]['classes']);
        self::assertStringContainsString('bg-teal-500', $badges[1]['classes']);

        $buttons = $overlay->getNormalizedButtons();
        self::assertCount(1, $buttons);
        self::assertSame('View Overlay', $buttons[0]['title']);
        self::assertSame('/details', $buttons[0]['url']);
        self::assertIsString($buttons[0]['classes']);
        self::assertStringContainsString('bg-layer', $buttons[0]['classes']);
    }

    public function testLinkAndClickableOverlay(): void
    {
        $overlay = new Overlay();
        $overlay->assign('link', '/case-studies/enterprise');
        $overlay->assign('linkTitle', 'Case Study');

        self::assertSame('/case-studies/enterprise', $overlay->getLinkUrl());
        self::assertSame('Case Study', $overlay->getButtonText());
        self::assertTrue($overlay->isEntireOverlayClickable());

        // Button only
        $overlay->mergeProps(['link_overlay' => 'none']);
        self::assertFalse($overlay->isEntireOverlayClickable());
    }

    public function testMediaMutualExclusivity(): void
    {
        $overlay = new Overlay();
        $overlay->assign('image', 'https://cdn.example.com/cover.jpg');
        $overlay->assign('video', 'https://cdn.example.com/cover.mp4');

        // Video takes priority
        self::assertTrue($overlay->isVideo());
        self::assertFalse($overlay->isImage());
        self::assertSame('https://cdn.example.com/cover.mp4', $overlay->getActiveVideoUrl());
        self::assertNull($overlay->getActiveImageUrl());

        // Image only
        $overlayImage = new Overlay();
        $overlayImage->assign('image', 'https://cdn.example.com/cover.jpg');
        self::assertTrue($overlayImage->isImage());
        self::assertFalse($overlayImage->isVideo());
        self::assertSame('https://cdn.example.com/cover.jpg', $overlayImage->getActiveImageUrl());
        self::assertNull($overlayImage->getActiveVideoUrl());
    }

    public function testWrapperClassesAndId(): void
    {
        $overlay = new Overlay();
        $overlay->mergeProps([
            'id' => 'hero-overlay',
            'custom_class' => 'my-custom-overlay',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
            'rounded' => '3xl',
            'shadow' => 'xl',
        ]);

        self::assertSame('hero-overlay', $overlay->getElementId());
        $wrapper = $overlay->getWrapperClasses();
        self::assertStringContainsString('group relative overflow-hidden', $wrapper);
        self::assertStringContainsString('rounded-3xl', $wrapper);
        self::assertStringContainsString('shadow-xl', $wrapper);
        self::assertStringContainsString('mt-10', $wrapper);
        self::assertStringContainsString('mb-16', $wrapper);
        self::assertStringContainsString('my-custom-overlay', $wrapper);
    }

    public function testUnifiedElementsSeparation(): void
    {
        $overlay = new Overlay();
        $overlay->mergeProps([
            'badge_style' => 'glass',
            'badge_color' => 'primary',
            'button_style' => 'solid',
            'button_color' => 'white',
        ]);

        $overlay->assign('elements', [
            [
                'type' => 'badge',
                'title' => 'Top Overlay Badge',
                'badge_style' => 'inherit',
                'badge_color' => 'inherit',
            ],
            [
                'type' => 'button',
                'title' => 'Overlay Action 1',
                'link' => '/overlay-1',
                'button_style' => 'inherit',
                'button_color' => 'inherit',
            ],
            [
                'type' => 'badge',
                'title' => 'Second Overlay Badge',
                'badge_style' => 'solid',
                'badge_color' => 'success',
            ],
            [
                'type' => 'button',
                'title' => 'Overlay Action 2',
                'link' => '/overlay-2',
                'button_style' => 'outline',
                'button_color' => 'white',
            ],
        ]);

        self::assertTrue($overlay->hasBadges());
        $badges = $overlay->getNormalizedBadges();
        self::assertCount(2, $badges);
        self::assertSame('Top Overlay Badge', $badges[0]['title']);
        self::assertIsString($badges[0]['classes']);
        self::assertStringContainsString('backdrop-blur-md', $badges[0]['classes']);
        self::assertSame('Second Overlay Badge', $badges[1]['title']);
        self::assertIsString($badges[1]['classes']);
        self::assertStringContainsString('bg-teal-500', $badges[1]['classes']);

        self::assertTrue($overlay->hasButtons());
        $buttons = $overlay->getNormalizedButtons();
        self::assertCount(2, $buttons);
        self::assertSame('Overlay Action 1', $buttons[0]['title']);
        self::assertSame('/overlay-1', $buttons[0]['url']);
        self::assertIsString($buttons[0]['classes']);
        self::assertStringContainsString('bg-layer', $buttons[0]['classes']);
        self::assertSame('Overlay Action 2', $buttons[1]['title']);
        self::assertSame('/overlay-2', $buttons[1]['url']);

        // Check fallback getLinkUrl and getButtonText from elements
        self::assertSame('/overlay-1', $overlay->getLinkUrl());
        self::assertSame('Overlay Action 1', $overlay->getButtonText());
    }
}
