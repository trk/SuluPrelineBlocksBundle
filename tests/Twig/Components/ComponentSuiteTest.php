<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Card;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Headline;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Image;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Text;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Video;

final class ComponentSuiteTest extends TestCase
{
    public function testHeadline(): void
    {
        $headline = new Headline();
        $headline->content = 'Welcome to Totoglu Studio';
        $headline->props = [
            'tag' => 'h1',
            'title_color' => 'primary',
            'text_align' => 'center',
            'margin_top' => 'md',
            'margin_bottom' => 'sm',
            'id' => 'hero-title',
        ];

        self::assertSame('h1', $headline->getTag());
        self::assertSame('hero-title', $headline->getElementId());
        self::assertSame('text-center', $headline->getTextAlign());
        self::assertSame('text-blue-600 dark:text-blue-400', $headline->getTitleColor());

        $classes = $headline->getWrapperClasses();
        self::assertStringContainsString('text-3xl', $classes);
        self::assertStringContainsString('text-center', $classes);
        self::assertStringContainsString('mt-6', $classes);
        self::assertStringContainsString('mb-4', $classes);
    }

    public function testHeadlineDefaults(): void
    {
        $headline = new Headline();
        $headline->content = 'Hello World !';

        self::assertSame('h2', $headline->getTag());
        self::assertNull($headline->getElementId());
        self::assertSame('text-left', $headline->getTextAlign());
        self::assertSame('text-gray-900 dark:text-white', $headline->getTitleColor());

        $classes = $headline->getWrapperClasses();
        self::assertStringContainsString('text-2xl', $classes);
        self::assertStringContainsString('text-gray-900', $classes);
    }

    public function testHeadlineDecorationsAndStroke(): void
    {
        $headline = new Headline();
        $headline->props = [
            'size' => '2xl',
            'title_decoration' => 'bullet',
            'title_text_stroke' => true,
        ];

        self::assertSame('bullet', $headline->getTitleDecoration());
        self::assertStringContainsString('before:w-1.5', $headline->getDecorationClasses());
        self::assertTrue($headline->isTextStroke());
        self::assertStringContainsString('-webkit-text-stroke', $headline->getTextStrokeClasses());

        $headline->props['title_decoration'] = 'divider';
        self::assertStringContainsString('border-b-2', $headline->getDecorationClasses());

        $headline->props['title_decoration'] = 'line';
        self::assertStringContainsString('after:content', $headline->getDecorationClasses());

        $classes = $headline->getWrapperClasses();
        self::assertStringContainsString('text-4xl', $classes);
    }

    public function testText(): void
    {
        $text = new Text();
        $text->content = '<p>Sample text paragraph.</p>';
        $text->props = [
            'font_size' => 'lg',
            'text_color' => 'muted',
            'text_align' => 'justify',
            'margin_bottom' => 'lg',
            'id' => 'bio-text',
        ];

        self::assertSame('bio-text', $text->getElementId());
        self::assertSame('text-lg', $text->getFontSize());
        self::assertSame('text-gray-500 dark:text-neutral-400', $text->getTextColor());
        self::assertSame('text-justify', $text->getTextAlign());

        $classes = $text->getWrapperClasses();
        self::assertStringContainsString('prose', $classes);
        self::assertStringContainsString('text-lg', $classes);
        self::assertStringContainsString('text-justify', $classes);
        self::assertStringContainsString('mb-10', $classes);
    }

    public function testImage(): void
    {
        $image = new Image();
        $image->image = 'https://cdn.example.com/banner.jpg';
        $image->props = [
            'aspect_ratio' => '16/9',
            'rounded' => 'xl',
            'margin_top' => 'sm',
            'caption' => 'Studio Banner',
            'id' => 'main-banner',
        ];

        self::assertSame('main-banner', $image->getElementId());
        self::assertSame('https://cdn.example.com/banner.jpg', $image->getImageUrl());
        self::assertSame('aspect-video', $image->getAspectRatio());
        self::assertSame('rounded-xl', $image->getRounded());
        self::assertSame('Studio Banner', $image->getCaption());

        $classes = $image->getWrapperClasses();
        self::assertStringContainsString('inline-block', $classes);
        self::assertStringContainsString('mt-4', $classes);
    }

    public function testVideo(): void
    {
        $video = new Video();
        $video->video = 'https://cdn.example.com/intro.mp4';
        $video->image = 'https://cdn.example.com/poster.jpg';
        $video->props = [
            'aspect_ratio' => '16/9',
            'autoplay' => true,
            'controls' => false,
            'loop' => true,
            'margin_bottom' => 'xl',
            'id' => 'intro-video',
        ];

        self::assertSame('intro-video', $video->getElementId());
        self::assertSame('https://cdn.example.com/intro.mp4', $video->getVideoUrl());
        self::assertSame('https://cdn.example.com/poster.jpg', $video->getPosterUrl());
        self::assertSame('aspect-video', $video->getAspectRatio());
        self::assertTrue($video->isAutoplay());
        self::assertNull($video->getAutoplayMode());
        self::assertTrue($video->isMuted());
        self::assertFalse($video->isControls());
        self::assertTrue($video->isLoop());
        self::assertSame('rounded-xl', $video->getRounded());

        $classes = $video->getWrapperClasses();
        self::assertStringContainsString('relative w-full overflow-hidden rounded-xl', $classes);
        self::assertStringContainsString('aspect-video', $classes);
        self::assertStringContainsString('mb-16', $classes);

        // Test autoplay inview/hover modes and shadow
        $video->props['autoplay'] = 'inview';
        self::assertTrue($video->isAutoplay());
        self::assertSame('inview', $video->getAutoplayMode());
        self::assertTrue($video->isMuted());

        $video->props['box_shadow'] = 'xl';
        self::assertSame('shadow-xl', $video->getShadowClasses());
        self::assertStringContainsString('shadow-xl', $video->getWrapperClasses());

        $video->props['muted'] = false;
        self::assertFalse($video->isMuted());

        // Aspect ratio variations and alias test
        $video->props['aspect_ratio'] = '9/16';
        self::assertSame('aspect-[9/16]', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '9x16';
        self::assertSame('aspect-[9/16]', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '21/9';
        self::assertSame('aspect-[21/9]', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '21x9';
        self::assertSame('aspect-[21/9]', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '4/3';
        self::assertSame('aspect-4/3', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '4x3';
        self::assertSame('aspect-4/3', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '1/1';
        self::assertSame('aspect-square', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '1x1';
        self::assertSame('aspect-square', $video->getAspectRatio());
        $video->props['aspect_ratio'] = '16x9';
        self::assertSame('aspect-video', $video->getAspectRatio());

        // Video rounded variations
        $video->props['rounded'] = 'full';
        self::assertSame('rounded-full', $video->getRounded());
        self::assertStringContainsString('rounded-full', $video->getWrapperClasses());
    }

    public function testImageAspectRatiosAndRounded(): void
    {
        $image = new Image();
        $image->image = 'https://cdn.example.com/banner.jpg';

        $image->props = ['aspect_ratio' => '9/16', 'rounded' => '2xl'];
        self::assertSame('aspect-[9/16]', $image->getAspectRatio());
        self::assertSame('rounded-2xl', $image->getRounded());

        $image->props = ['aspect_ratio' => '9x16', 'rounded' => 'full'];
        self::assertSame('aspect-[9/16]', $image->getAspectRatio());
        self::assertSame('rounded-full', $image->getRounded());

        $image->props = ['aspect_ratio' => '21/9'];
        self::assertSame('aspect-[21/9]', $image->getAspectRatio());

        $image->props = ['aspect_ratio' => '21x9'];
        self::assertSame('aspect-[21/9]', $image->getAspectRatio());

        $image->props = ['aspect_ratio' => '16x9'];
        self::assertSame('aspect-video', $image->getAspectRatio());

        $image->props = ['aspect_ratio' => '1x1'];
        self::assertSame('aspect-square', $image->getAspectRatio());

        $image->props = ['aspect_ratio' => '4x3'];
        self::assertSame('aspect-4/3', $image->getAspectRatio());
    }

    public function testTextFallbacks(): void
    {
        $text = new Text();
        $text->props = [
            'size' => 'xl',
            'color' => 'primary',
        ];

        self::assertSame('text-xl', $text->getFontSize());
        self::assertSame('text-blue-600 dark:text-blue-400', $text->getTextColor());
    }

    public function testTextAdvancedFeatures(): void
    {
        $text = new Text();
        $text->props = [
            'dropcap' => true,
            'column' => '1-2',
            'column_divider' => true,
            'text_style' => 'lead',
        ];

        self::assertTrue($text->isDropcap());
        self::assertStringContainsString('first-letter:float-left', $text->getDropcapClasses());
        self::assertSame('md:columns-2 gap-8', $text->getColumnClasses());
        self::assertTrue($text->isColumnDivider());
        self::assertStringContainsString('column-rule', $text->getColumnDividerClasses());
        self::assertStringContainsString('text-xl sm:text-2xl', $text->getTextStyleClasses());

        $wrapper = $text->getWrapperClasses();
        self::assertStringContainsString('md:columns-2', $wrapper);
        self::assertStringContainsString('first-letter:float-left', $wrapper);
    }

    public function testCardLayoutAndAlignments(): void
    {
        $card = new Card();
        $card->title = 'Modern Card';
        $card->meta = 'Technology';
        $card->content = '<p>Card content text.</p>';
        $card->image = 'https://cdn.example.com/cover.jpg';
        $card->props = [
            'media_align' => 'left',
            'media_width' => '1-3',
            'media_aspect_ratio' => '16-9',
            'meta_align' => 'above-title',
            'title_element' => 'h2',
            'title_size' => 'xl',
            'title_decoration' => 'underline',
            'meta_badge_style' => 'primary_pill',
            'variant' => 'interactive',
            'rounded' => '2xl',
            'padding' => 'spacious',
        ];

        self::assertSame('left', $card->getMediaAlign());
        self::assertTrue($card->isHorizontal());
        self::assertFalse($card->isOverlay());
        self::assertSame('md:w-1/3', $card->getMediaGridWidthClasses());
        self::assertSame('md:w-2/3', $card->getContentGridWidthClasses());
        self::assertSame('aspect-video', $card->getMediaAspectRatioClass());
        self::assertSame('above-title', $card->getMetaAlign());
        self::assertSame('h2', $card->getTitleElement());
        self::assertStringContainsString('text-3xl', $card->getTitleSizeClasses());
        self::assertStringContainsString('underline', $card->getTitleDecorationClasses());
        self::assertStringContainsString('bg-blue-100', $card->getMetaBadgeClasses());
        self::assertSame('p-6 sm:p-8', $card->getCardPaddingClasses());
        self::assertSame('rounded-2xl', $card->getRoundedClasses());

        $classes = $card->getCardClasses();
        self::assertStringContainsString('flex-col md:flex-row', $classes);
        self::assertStringContainsString('rounded-2xl', $classes);
        self::assertStringContainsString('hover:-translate-y-1', $classes);

        // Overlay layout test
        $card->props['media_align'] = 'overlay';
        self::assertSame('overlay', $card->getMediaAlign());
        self::assertTrue($card->isOverlay());
        self::assertFalse($card->isHorizontal());
        self::assertSame('text-white', $card->getTitleColor());
        self::assertStringContainsString('relative text-white', $card->getCardClasses());
    }

    public function testCardClickableUrl(): void
    {
        $card = new Card();
        $card->link = ['url' => 'https://example.com/article'];
        $card->linkTitle = 'Read More';
        $card->props = [
            'link_card' => 'card',
            'button_style' => 'outline',
        ];

        self::assertSame('https://example.com/article', $card->getLinkUrl());
        self::assertSame('Read More', $card->getButtonText());
        self::assertSame('https://example.com/article', $card->getResolvedCardUrl());
        self::assertStringContainsString('border-primary text-primary', $card->getButtonStyleClasses());

        // Legacy cardLink compatibility test
        $legacyCard = new Card();
        $legacyCard->cardLink = ['url' => 'https://example.com/legacy'];
        $legacyCard->props = ['link_card' => 'card_link'];
        self::assertSame('https://example.com/legacy', $legacyCard->getResolvedCardUrl());

        // First button link fallback test
        $btnCard = new Card();
        $btnCard->buttons = [
            ['link' => 'https://example.com/first-btn', 'content' => 'Read'],
            ['link' => 'https://example.com/second-btn', 'content' => 'Secondary'],
        ];
        $btnCard->props['link_card'] = 'first_button';

        self::assertSame('https://example.com/first-btn', $btnCard->getResolvedCardUrl());
        self::assertSame('Read', $btnCard->getButtonText());
    }
}
