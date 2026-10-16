<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Card;

final class CardTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $card = new Card();

        self::assertFalse($card->shouldRender());
        self::assertNull($card->get('title'));
        self::assertNull($card->get('badge'));
        self::assertNull($card->get('meta'));
        self::assertNull($card->get('content'));
        self::assertNull($card->get('image'));
        self::assertNull($card->get('video'));
        self::assertNull($card->get('icon'));
        self::assertSame([], $card->items('buttons'));
        self::assertNull($card->get('hoverImage'));
        self::assertNull($card->get('hover_image'));
        self::assertNull($card->get('hoverVideo'));
        self::assertNull($card->get('hover_video'));
        self::assertNull($card->getImageUrl());
        self::assertNull($card->getHoverImageUrl());
        self::assertNull($card->getVideoUrl());
        self::assertNull($card->getHoverVideoUrl());
        self::assertSame([], $card->getNormalizedButtons());
    }

    public function testHoverImageAndVideoResolution(): void
    {
        $card = new Card();
        $card->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $card->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        self::assertSame('https://cdn.example.com/hover.jpg', $card->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $card->getHoverVideoUrl());

        // Snake case fallback
        $cardSnake = new Card();
        $cardSnake->assign('hover_image', 'https://cdn.example.com/hover-snake.jpg');
        $cardSnake->assign('hover_video', 'https://cdn.example.com/hover-snake.mp4');

        self::assertSame('https://cdn.example.com/hover-snake.jpg', $cardSnake->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover-snake.mp4', $cardSnake->getHoverVideoUrl());
    }

    public function testMediaSourceAndExternalUrls(): void
    {
        $card = new Card();
        $card->assign('mediaType', 'image');
        $card->assign('mediaSource', 'url');
        $card->assign('imageUrl', 'https://cdn.example.com/external-image.jpg');
        $card->assign('hoverImageUrl', 'https://cdn.example.com/external-hover.jpg');

        self::assertSame('https://cdn.example.com/external-image.jpg', $card->getImageUrl());
        self::assertSame('https://cdn.example.com/external-hover.jpg', $card->getHoverImageUrl());
        self::assertTrue($card->isImage());
        self::assertFalse($card->isVideo());

        // Snake case fallback
        $cardSnake = new Card();
        $cardSnake->assign('media_type', 'video');
        $cardSnake->assign('media_source', 'url');
        $cardSnake->assign('video_url', 'https://cdn.example.com/external-video.mp4');
        $cardSnake->assign('hover_video_url', 'https://cdn.example.com/external-hover-video.mp4');

        self::assertSame('https://cdn.example.com/external-video.mp4', $cardSnake->getVideoUrl());
        self::assertSame('https://cdn.example.com/external-hover-video.mp4', $cardSnake->getHoverVideoUrl());
        self::assertTrue($cardSnake->isVideo());
        self::assertFalse($cardSnake->isImage());

        // Icon exclusivity
        $cardIcon = new Card();
        $cardIcon->assign('mediaType', 'icon');
        $cardIcon->assign('icon', 'ti ti-star');
        self::assertSame('ti ti-star', $cardIcon->getIconClass());

        $cardImageWithIcon = new Card();
        $cardImageWithIcon->assign('mediaType', 'image');
        $cardImageWithIcon->assign('icon', 'ti ti-star');
        self::assertNull($cardImageWithIcon->getIconClass());
    }

    public function testNormalizedButtons(): void
    {
        $card = new Card();
        $card->assign('buttons', [
            ['content' => 'Click Me', 'link' => '/test'],
            ['content' => 'Secondary', 'link' => '#contact'],
        ]);

        $normalized = $card->getNormalizedButtons();
        self::assertCount(2, $normalized);
        self::assertSame('Click Me', $normalized[0]['content']);
        self::assertSame('/test', $normalized[0]['link']);
    }

    public function testCardClassesAndProps(): void
    {
        $card = new Card();
        $card->mergeProps([
            'card_variant' => 'glass',
            'layout' => 'horizontal',
            'text_align' => 'center',
            'title_color' => 'primary',
        ]);

        self::assertStringContainsString('backdrop-blur', $card->getCardClasses());
        self::assertStringContainsString('md:flex-row', $card->getCardClasses());
        self::assertSame('text-center', $card->getTextAlign());
        self::assertSame('text-primary', $card->getTitleColor());

        $raisedCard = new Card();
        $raisedCard->mergeProps([
            'panel_style' => 'raised',
            'link_style' => 'secondary',
        ]);
        $raisedCard->assign('link', '/pricing');
        $raisedCard->assign('link_title', 'View Plans');

        self::assertStringContainsString('shadow-md hover:shadow-lg', $raisedCard->getCardClasses());
        $buttons = $raisedCard->getNormalizedButtons();
        self::assertCount(1, $buttons);
        self::assertIsString($buttons[0]['classes']);
        self::assertStringContainsString('bg-secondary', $buttons[0]['classes']);
    }

    public function testMutualExclusivityImageOnly(): void
    {
        $card = new Card();
        $card->assign('image', 'https://cdn.example.com/main.jpg');
        $card->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $card->assign('video', null);
        $card->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        self::assertTrue($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertSame('https://cdn.example.com/main.jpg', $card->getActiveImageUrl());
        self::assertSame('https://cdn.example.com/hover.jpg', $card->getActiveHoverImageUrl());
        self::assertNull($card->getActiveVideoUrl());
        self::assertNull($card->getActiveHoverVideoUrl());
    }

    public function testMutualExclusivityVideoOnly(): void
    {
        $card = new Card();
        $card->assign('image', null);
        $card->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $card->assign('video', 'https://cdn.example.com/main.mp4');
        $card->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        self::assertFalse($card->isImage());
        self::assertTrue($card->isVideo());
        self::assertSame('https://cdn.example.com/main.mp4', $card->getActiveVideoUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $card->getActiveHoverVideoUrl());
        self::assertNull($card->getActiveImageUrl());
        self::assertNull($card->getActiveHoverImageUrl());
    }

    public function testHoverWithoutMainObjectIsIgnored(): void
    {
        $card = new Card();
        // Hover set without main object
        $card->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $card->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        self::assertFalse($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertNull($card->getActiveImageUrl());
        self::assertNull($card->getActiveHoverImageUrl());
        self::assertNull($card->getActiveVideoUrl());
        self::assertNull($card->getActiveHoverVideoUrl());
    }

    public function testMediaTypePropOverrides(): void
    {
        $card = new Card();
        $card->assign('image', 'https://cdn.example.com/main.jpg');
        $card->assign('hoverImage', 'https://cdn.example.com/hover.jpg');
        $card->assign('video', 'https://cdn.example.com/main.mp4');
        $card->assign('hoverVideo', 'https://cdn.example.com/hover.mp4');

        // Auto mode defaults to image
        self::assertTrue($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertSame('https://cdn.example.com/main.jpg', $card->getActiveImageUrl());

        // Override to video
        $card->mergeProps(['media_type' => 'video']);
        self::assertFalse($card->isImage());
        self::assertTrue($card->isVideo());
        self::assertSame('https://cdn.example.com/main.mp4', $card->getActiveVideoUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $card->getActiveHoverVideoUrl());
        self::assertNull($card->getActiveImageUrl());

        // Override to none
        $card->mergeProps(['media_type' => 'none']);
        self::assertFalse($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertNull($card->getActiveImageUrl());
        self::assertNull($card->getActiveVideoUrl());
    }

    public function testWrapperClassesAndId(): void
    {
        $card = new Card();
        $card->mergeProps([
            'card_variant' => 'shadow',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
            'id' => 'card-section',
            'custom_class' => 'backdrop-blur-sm',
        ]);

        self::assertSame('card-section', $card->getElementId());
        $wrapper = $card->getWrapperClasses();
        self::assertStringContainsString('group relative', $wrapper);
        self::assertStringContainsString('shadow-lg', $wrapper);
        self::assertStringContainsString('mt-10', $wrapper);
        self::assertStringContainsString('mb-16', $wrapper);
        self::assertStringContainsString('backdrop-blur-sm', $wrapper);
    }

    public function testBadgeAndBadgeStyles(): void
    {
        $card = new Card();
        $card->assign('badge', 'New Feature');

        self::assertSame('New Feature', $card->get('badge'));
        self::assertStringContainsString('backdrop-blur-md', $card->getBadgeClasses());

        $card->mergeProps(['badge_style' => 'primary']);
        self::assertStringContainsString('bg-primary', $card->getBadgeClasses());

        $card->mergeProps(['badge_style' => 'success']);
        self::assertStringContainsString('bg-emerald-600', $card->getBadgeClasses());

        $card->mergeProps(['badge_style' => 'dark']);
        self::assertStringContainsString('bg-inverse', $card->getBadgeClasses());
    }

    public function testBadgePositions(): void
    {
        $card = new Card();
        self::assertSame('top-left', $card->getBadgePosition());
        self::assertSame('absolute top-3 start-3 z-10 flex flex-wrap gap-1.5', $card->getBadgePositionClasses());

        $card->assign('badgePosition', 'top-right');
        self::assertSame('top-right', $card->getBadgePosition());
        self::assertSame('absolute top-3 end-3 z-10 flex flex-wrap gap-1.5', $card->getBadgePositionClasses());

        $card = new Card();
        $card->assign('badge_position', 'bottom-left');
        self::assertSame('bottom-left', $card->getBadgePosition());
        self::assertSame('absolute bottom-3 start-3 z-10 flex flex-wrap gap-1.5', $card->getBadgePositionClasses());

        $card = new Card();
        $card->mergeProps(['badge_position' => 'bottom-right']);
        self::assertSame('bottom-right', $card->getBadgePosition());
        self::assertSame('absolute bottom-3 end-3 z-10 flex flex-wrap gap-1.5', $card->getBadgePositionClasses());

        $card->mergeProps(['badge_position' => 'inline']);
        self::assertSame('inline', $card->getBadgePosition());
        self::assertSame('', $card->getBadgePositionClasses());
    }

    public function testMultipleBadgesAndButtons(): void
    {
        $card = new Card();
        $card->mergeProps([
            'badge_style' => 'soft',
            'badge_color' => 'primary',
            'button_style' => 'solid',
            'button_color' => 'primary',
            'button_size' => 'md',
        ]);

        $card->assign('badges', [
            [
                'title' => 'Inherited Badge',
                'badge_style' => 'inherit',
                'badge_color' => 'inherit',
            ],
            [
                'title' => 'Overridden Badge',
                'badge_style' => 'solid',
                'badge_color' => 'danger',
                'has_dot' => true,
            ],
        ]);

        $card->assign('buttons', [
            [
                'title' => 'Explore',
                'link' => '/explore',
                'button_style' => 'inherit',
                'button_color' => 'inherit',
            ],
            [
                'title' => 'Contact Us',
                'link' => '/contact',
                'button_style' => 'outline',
                'button_color' => 'secondary',
            ],
        ]);

        self::assertTrue($card->hasBadges());
        $badges = $card->getNormalizedBadges();
        self::assertCount(2, $badges);
        self::assertSame('Inherited Badge', $badges[0]['title']);
        self::assertIsString($badges[0]['classes']);
        self::assertStringContainsString('bg-primary-100', $badges[0]['classes']);
        self::assertStringContainsString('text-primary-800', $badges[0]['classes']);

        self::assertSame('Overridden Badge', $badges[1]['title']);
        self::assertIsString($badges[1]['classes']);
        self::assertStringContainsString('bg-destructive', $badges[1]['classes']);
        self::assertTrue($badges[1]['has_dot']);

        self::assertTrue($card->hasButtons());
        $buttons = $card->getNormalizedButtons();
        self::assertCount(2, $buttons);
        self::assertSame('Explore', $buttons[0]['title']);
        self::assertSame('/explore', $buttons[0]['url']);
        self::assertIsString($buttons[0]['classes']);
        self::assertStringContainsString('bg-primary', $buttons[0]['classes']);

        self::assertSame('Contact Us', $buttons[1]['title']);
        self::assertSame('/contact', $buttons[1]['url']);
        self::assertIsString($buttons[1]['classes']);
        self::assertStringContainsString('border-secondary-line', $buttons[1]['classes']);
    }

    public function testCardGroupVariant(): void
    {
        $card = new Card();
        $card->mergeProps(['card_variant' => 'group']);

        $classes = $card->getCardClasses();
        self::assertStringContainsString('border-0', $classes);
        self::assertStringContainsString('rounded-none', $classes);
        self::assertStringContainsString('shadow-none', $classes);
        self::assertStringContainsString('flex-1', $classes);
    }

    public function testUnifiedElementsSeparation(): void
    {
        $card = new Card();
        $card->mergeProps([
            'badge_style' => 'solid',
            'badge_color' => 'primary',
            'button_style' => 'solid',
            'button_color' => 'secondary',
        ]);

        $card->assign('elements', [
            [
                'type' => 'badge',
                'title' => 'Top Badge',
                'badge_style' => 'inherit',
                'badge_color' => 'inherit',
            ],
            [
                'type' => 'button',
                'title' => 'First Action',
                'link' => '/action-1',
                'button_style' => 'inherit',
                'button_color' => 'inherit',
            ],
            [
                'type' => 'badge',
                'title' => 'Second Badge',
                'badge_style' => 'outline',
                'badge_color' => 'danger',
            ],
            [
                'type' => 'button',
                'title' => 'Second Action',
                'link' => '/action-2',
                'button_style' => 'outline',
                'button_color' => 'primary',
            ],
        ]);

        self::assertTrue($card->hasBadges());
        $badges = $card->getNormalizedBadges();
        self::assertCount(2, $badges);
        self::assertSame('Top Badge', $badges[0]['title']);
        self::assertIsString($badges[0]['classes']);
        self::assertStringContainsString('bg-primary', $badges[0]['classes']);
        self::assertSame('Second Badge', $badges[1]['title']);
        self::assertIsString($badges[1]['classes']);
        self::assertStringContainsString('border-destructive', $badges[1]['classes']);

        self::assertTrue($card->hasButtons());
        $buttons = $card->getNormalizedButtons();
        self::assertCount(2, $buttons);
        self::assertSame('First Action', $buttons[0]['title']);
        self::assertSame('/action-1', $buttons[0]['url']);
        self::assertIsString($buttons[0]['classes']);
        self::assertStringContainsString('bg-secondary', $buttons[0]['classes']);
        self::assertSame('Second Action', $buttons[1]['title']);
        self::assertSame('/action-2', $buttons[1]['url']);

        // Check fallback getLinkUrl and getButtonText from elements
        self::assertSame('/action-1', $card->getLinkUrl());
        self::assertSame('First Action', $card->getButtonText());
    }

    public function testAspectRatioAndResponsiveImages(): void
    {
        $card = new Card();
        self::assertSame('aspect-video', $card->getMediaAspectRatioClass());
        self::assertSame('', $card->getMediaAspectRatioStyle());
        self::assertSame('object-cover', $card->getObjectFitClass());

        // Test standard aspect ratios
        $card->mergeProps(['aspect_ratio' => 'auto']);
        self::assertSame('aspect-auto', $card->getMediaAspectRatioClass());

        $card->mergeProps(['aspect_ratio' => '16:9']);
        self::assertSame('aspect-video', $card->getMediaAspectRatioClass());

        $card->mergeProps(['aspect_ratio' => '4:3']);
        self::assertSame('aspect-4/3', $card->getMediaAspectRatioClass());

        $card->mergeProps(['aspect_ratio' => '1:1']);
        self::assertSame('aspect-square', $card->getMediaAspectRatioClass());

        // Test manual custom ratio
        $card->mergeProps([
            'aspect_ratio' => 'manual',
            'aspect_ratio_width' => 21,
            'aspect_ratio_height' => 9,
            'object_fit' => 'contain',
        ]);
        self::assertSame('aspect-auto', $card->getMediaAspectRatioClass());
        self::assertSame('aspect-ratio: 21 / 9;', $card->getMediaAspectRatioStyle());
        self::assertSame('object-contain', $card->getObjectFitClass());

        // Test responsive srcset and focal point
        $card->assign('image', [
            'url' => '/uploads/media/original.jpg',
            'formats' => [
                'col-sm' => '/uploads/media/col-sm.webp',
                'col-md' => '/uploads/media/col-md.webp',
                'col-lg' => '/uploads/media/col-lg.webp',
                'col-xl' => '/uploads/media/col-xl.webp',
            ],
            'focusPoint' => ['x' => 50, 'y' => 25],
        ]);

        self::assertSame('/uploads/media/original.jpg', $card->getActiveImageUrl());
        self::assertNotNull($card->getImageSrcSet());
        self::assertStringContainsString('/uploads/media/col-sm.webp 400w', (string) $card->getImageSrcSet());
        self::assertStringContainsString('/uploads/media/col-md.webp 640w', (string) $card->getImageSrcSet());
        self::assertStringContainsString('/uploads/media/col-lg.webp 960w', (string) $card->getImageSrcSet());
        self::assertStringContainsString('/uploads/media/col-xl.webp 1280w', (string) $card->getImageSrcSet());
        self::assertSame('object-position: 50% 25%;', $card->getImageFocusPointStyle());
        self::assertNotEmpty($card->getImageSizes());
    }
}
