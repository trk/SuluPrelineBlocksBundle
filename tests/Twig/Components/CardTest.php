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

        self::assertNull($card->title);
        self::assertNull($card->badge);
        self::assertNull($card->meta);
        self::assertNull($card->content);
        self::assertNull($card->image);
        self::assertNull($card->video);
        self::assertNull($card->icon);
        self::assertSame([], $card->buttons);
        self::assertNull($card->hoverImage);
        self::assertNull($card->hover_image);
        self::assertNull($card->hoverVideo);
        self::assertNull($card->hover_video);
        self::assertSame([], $card->props);
        self::assertNull($card->getImageUrl());
        self::assertNull($card->getHoverImageUrl());
        self::assertNull($card->getVideoUrl());
        self::assertNull($card->getHoverVideoUrl());
        self::assertSame([], $card->getNormalizedButtons());
    }

    public function testHoverImageAndVideoResolution(): void
    {
        $card = new Card();
        $card->hoverImage = 'https://cdn.example.com/hover.jpg';
        $card->hoverVideo = 'https://cdn.example.com/hover.mp4';

        self::assertSame('https://cdn.example.com/hover.jpg', $card->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $card->getHoverVideoUrl());

        // Snake case fallback
        $cardSnake = new Card();
        $cardSnake->hover_image = 'https://cdn.example.com/hover-snake.jpg';
        $cardSnake->hover_video = 'https://cdn.example.com/hover-snake.mp4';

        self::assertSame('https://cdn.example.com/hover-snake.jpg', $cardSnake->getHoverImageUrl());
        self::assertSame('https://cdn.example.com/hover-snake.mp4', $cardSnake->getHoverVideoUrl());
    }

    public function testNormalizedButtons(): void
    {
        $card = new Card();
        $card->buttons = [
            ['content' => 'Click Me', 'link' => '/test'],
            ['content' => 'Secondary', 'link' => '#contact'],
        ];

        $normalized = $card->getNormalizedButtons();
        self::assertCount(2, $normalized);
        self::assertSame('Click Me', $normalized[0]['content']);
        self::assertSame('/test', $normalized[0]['link']);
    }

    public function testCardClassesAndProps(): void
    {
        $card = new Card();
        $card->props = [
            'card_variant' => 'glass',
            'layout' => 'horizontal',
            'text_align' => 'center',
            'title_color' => 'primary',
        ];

        self::assertStringContainsString('backdrop-blur', $card->getCardClasses());
        self::assertStringContainsString('md:flex-row', $card->getCardClasses());
        self::assertSame('text-center', $card->getTextAlign());
        self::assertSame('text-blue-600 dark:text-blue-400', $card->getTitleColor());
    }

    public function testMutualExclusivityImageOnly(): void
    {
        $card = new Card();
        $card->image = 'https://cdn.example.com/main.jpg';
        $card->hoverImage = 'https://cdn.example.com/hover.jpg';
        $card->video = null;
        $card->hoverVideo = 'https://cdn.example.com/hover.mp4';

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
        $card->image = null;
        $card->hoverImage = 'https://cdn.example.com/hover.jpg';
        $card->video = 'https://cdn.example.com/main.mp4';
        $card->hoverVideo = 'https://cdn.example.com/hover.mp4';

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
        $card->hoverImage = 'https://cdn.example.com/hover.jpg';
        $card->hoverVideo = 'https://cdn.example.com/hover.mp4';

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
        $card->image = 'https://cdn.example.com/main.jpg';
        $card->hoverImage = 'https://cdn.example.com/hover.jpg';
        $card->video = 'https://cdn.example.com/main.mp4';
        $card->hoverVideo = 'https://cdn.example.com/hover.mp4';

        // Auto mode defaults to image
        self::assertTrue($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertSame('https://cdn.example.com/main.jpg', $card->getActiveImageUrl());

        // Override to video
        $card->props = ['media_type' => 'video'];
        self::assertFalse($card->isImage());
        self::assertTrue($card->isVideo());
        self::assertSame('https://cdn.example.com/main.mp4', $card->getActiveVideoUrl());
        self::assertSame('https://cdn.example.com/hover.mp4', $card->getActiveHoverVideoUrl());
        self::assertNull($card->getActiveImageUrl());

        // Override to none
        $card->props = ['media_type' => 'none'];
        self::assertFalse($card->isImage());
        self::assertFalse($card->isVideo());
        self::assertNull($card->getActiveImageUrl());
        self::assertNull($card->getActiveVideoUrl());
    }

    public function testWrapperClassesAndId(): void
    {
        $card = new Card();
        $card->props = [
            'card_variant' => 'shadow',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
            'id' => 'card-section',
            'custom_class' => 'backdrop-blur-sm',
        ];

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
        $card->badge = 'New Feature';

        self::assertSame('New Feature', $card->badge);
        self::assertStringContainsString('backdrop-blur-md', $card->getBadgeClasses());

        $card->props = ['badge_style' => 'primary'];
        self::assertStringContainsString('bg-blue-600', $card->getBadgeClasses());

        $card->props = ['badge_style' => 'success'];
        self::assertStringContainsString('bg-emerald-600', $card->getBadgeClasses());

        $card->props = ['badge_style' => 'dark'];
        self::assertStringContainsString('bg-gray-900', $card->getBadgeClasses());
    }

    public function testBadgePositions(): void
    {
        $card = new Card();
        self::assertSame('top-left', $card->getBadgePosition());
        self::assertSame('absolute top-3 start-3 z-10', $card->getBadgePositionClasses());

        $card->badgePosition = 'top-right';
        self::assertSame('top-right', $card->getBadgePosition());
        self::assertSame('absolute top-3 end-3 z-10', $card->getBadgePositionClasses());

        $card = new Card();
        $card->badge_position = 'bottom-left';
        self::assertSame('bottom-left', $card->getBadgePosition());
        self::assertSame('absolute bottom-3 start-3 z-10', $card->getBadgePositionClasses());

        $card = new Card();
        $card->props = ['badge_position' => 'bottom-right'];
        self::assertSame('bottom-right', $card->getBadgePosition());
        self::assertSame('absolute bottom-3 end-3 z-10', $card->getBadgePositionClasses());

        $card->props = ['badge_position' => 'inline'];
        self::assertSame('inline', $card->getBadgePosition());
        self::assertSame('', $card->getBadgePositionClasses());
    }
}
