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
        $headline->assign('title', 'Welcome to Totoglu Studio');
        $headline->mergeProps([
            'tag' => 'h1',
            'title_color' => 'primary',
            'text_align' => 'center',
            'margin_top' => 'md',
            'margin_bottom' => 'sm',
            'id' => 'hero-title',
        ]);

        self::assertSame('Welcome to Totoglu Studio', $headline->getTitle());
        self::assertSame('h1', $headline->getTag());
        self::assertSame('hero-title', $headline->getElementId());
        self::assertSame('text-center', $headline->getTextAlign());
        self::assertSame('text-primary', $headline->getTitleColor());

        $classes = $headline->getWrapperClasses();
        self::assertStringContainsString('text-center', $classes);
        self::assertStringContainsString('mt-6', $classes);
        self::assertStringContainsString('mb-4', $classes);
        self::assertStringContainsString('text-3xl', $headline->getHeadingClasses());
        self::assertStringContainsString('text-primary', $headline->getHeadingClasses());
    }

    public function testHeadlineDefaults(): void
    {
        $headline = new Headline();
        $headline->assign('content', 'Hello World !');

        self::assertSame('Hello World !', $headline->getTitle());
        self::assertSame('h2', $headline->getTag());
        self::assertNull($headline->getElementId());
        self::assertSame('text-left', $headline->getTextAlign());
        self::assertSame('text-foreground', $headline->getTitleColor());

        $classes = $headline->getWrapperClasses();
        self::assertStringContainsString('text-left', $classes);
        self::assertStringContainsString('text-2xl', $headline->getHeadingClasses());
        self::assertStringContainsString('text-foreground', $headline->getHeadingClasses());

        $headline->assign('image', [
            'url' => 'https://cdn.example.com/icon.png',
            'focalPoint' => ['x' => 25, 'y' => 75],
        ]);
        self::assertSame('object-position: 25% 75%;', $headline->getImageFocusPointStyle());
    }

    public function testHeadlineDecorationsAndStroke(): void
    {
        $headline = new Headline();
        $headline->mergeProps([
            'size' => '2xl',
            'title_decoration' => 'bullet',
            'title_text_stroke' => true,
        ]);

        self::assertSame('bullet', $headline->getTitleDecoration());
        self::assertStringContainsString('before:w-1.5', $headline->getDecorationClasses());
        self::assertTrue($headline->isTextStroke());
        self::assertStringContainsString('-webkit-text-stroke', $headline->getTextStrokeClasses());

        $headline->setProp('title_decoration', 'divider');
        self::assertStringContainsString('border-b-2', $headline->getDecorationClasses());

        $headline->setProp('title_decoration', 'line');
        self::assertStringContainsString('after:content', $headline->getDecorationClasses());

        $classes = $headline->getHeadingClasses();
        self::assertStringContainsString('text-4xl', $classes);
    }

    public function testText(): void
    {
        $text = new Text();
        $text->assign('content', '<p>Sample text paragraph.</p>');
        $text->mergeProps([
            'font_size' => 'lg',
            'text_color' => 'muted',
            'text_align' => 'justify',
            'margin_bottom' => 'lg',
            'id' => 'bio-text',
        ]);

        self::assertSame('bio-text', $text->getElementId());
        self::assertSame('text-lg', $text->getFontSize());
        self::assertSame('text-muted-foreground-1', $text->getTextColor());
        self::assertSame('text-justify', $text->getTextAlign());

        $classes = $text->getWrapperClasses();
        self::assertStringContainsString('text-justify', $classes);
        self::assertStringContainsString('mb-10', $classes);

        $blockClasses = $text->getBlockClasses();
        self::assertStringContainsString('prose', $blockClasses);
        self::assertStringContainsString('text-lg', $blockClasses);
    }

    public function testImage(): void
    {
        $image = new Image();
        $image->assign('image', 'https://cdn.example.com/banner.jpg');
        $image->mergeProps([
            'aspect_ratio' => '16/9',
            'rounded' => 'xl',
            'margin_top' => 'sm',
            'caption' => 'Studio Banner',
            'id' => 'main-banner',
        ]);

        self::assertSame('main-banner', $image->getElementId());
        self::assertSame('https://cdn.example.com/banner.jpg', $image->getImageUrl());
        self::assertSame('aspect-video', $image->getAspectRatio());
        self::assertSame('rounded-xl', $image->getRounded());
        self::assertSame('Studio Banner', $image->getCaption());
        self::assertNull($image->getImageSrcSet());
        self::assertNull($image->getImageFocusPointStyle());

        // Test with responsive formats, focal point, and hover image
        $image->assign('image', [
            'url' => 'https://cdn.example.com/banner.jpg',
            'formats' => [
                '400w' => 'https://cdn.example.com/banner-400.webp',
                '960w' => 'https://cdn.example.com/banner-960.webp',
            ],
            'focalPoint' => ['x' => 30, 'y' => 70],
        ]);
        $image->assign('hover_image', [
            'url' => 'https://cdn.example.com/banner-hover.jpg',
            'formats' => [
                '400w' => 'https://cdn.example.com/banner-hover-400.webp',
            ],
            'focalPoint' => ['x' => 50, 'y' => 50],
        ]);

        self::assertNotNull($image->getImageSrcSet());
        self::assertStringContainsString('banner-400.webp 400w', $image->getImageSrcSet());
        self::assertSame('object-position: 30% 70%;', $image->getImageFocusPointStyle());
        self::assertSame('https://cdn.example.com/banner-hover.jpg', $image->getHoverImageUrl());
        self::assertNotNull($image->getHoverImageSrcSet());
        self::assertStringContainsString('banner-hover-400.webp 400w', $image->getHoverImageSrcSet());
        self::assertSame('object-position: 50% 50%;', $image->getHoverImageFocusPointStyle());

        $classes = $image->getWrapperClasses();
        self::assertStringContainsString('mt-4', $classes);
        self::assertSame('block w-full', $image->getBlockClasses());
    }

    public function testVideo(): void
    {
        $video = new Video();
        $video->assign('video', 'https://cdn.example.com/intro.mp4');
        $video->assign('image', [
            'url' => 'https://cdn.example.com/poster.jpg',
            'formats' => [
                '640w' => 'https://cdn.example.com/poster-640.webp',
                '1280w' => 'https://cdn.example.com/poster-1280.webp',
            ],
            'focalPoint' => ['x' => 40, 'y' => 60],
        ]);
        $video->mergeProps([
            'aspect_ratio' => '16/9',
            'autoplay' => true,
            'controls' => false,
            'loop' => true,
            'margin_bottom' => 'xl',
            'id' => 'intro-video',
        ]);

        self::assertSame('intro-video', $video->getElementId());
        self::assertSame('https://cdn.example.com/intro.mp4', $video->getVideoUrl());
        self::assertSame('https://cdn.example.com/poster.jpg', $video->getPosterUrl());
        self::assertNotNull($video->getPosterSrcSet());
        self::assertStringContainsString('poster-640.webp 640w', $video->getPosterSrcSet());
        self::assertSame('object-position: 40% 60%;', $video->getPosterFocusPointStyle());
        self::assertSame('aspect-video', $video->getAspectRatio());
        self::assertTrue($video->isAutoplay());
        self::assertNull($video->getAutoplayMode());
        self::assertTrue($video->isMuted());
        self::assertFalse($video->isControls());
        self::assertTrue($video->isLoop());
        self::assertSame('rounded-xl', $video->getRounded());

        $classes = $video->getWrapperClasses();
        self::assertStringContainsString('mb-16', $classes);
        $blockClasses = $video->getBlockClasses();
        self::assertStringContainsString('relative w-full overflow-hidden rounded-xl', $blockClasses);
        self::assertStringContainsString('aspect-video', $blockClasses);

        // Test autoplay inview/hover modes and shadow
        $video->setProp('autoplay', 'inview');
        self::assertFalse($video->isAutoplay());
        self::assertSame('inview', $video->getAutoplayMode());
        self::assertTrue($video->isMuted());

        $video->setProp('box_shadow', 'xl');
        self::assertSame('shadow-xl', $video->getShadowClasses());
        self::assertStringContainsString('shadow-xl', $video->getBlockClasses());

        $video->setProp('muted', false);
        self::assertFalse($video->isMuted());

        // Aspect ratio variations and alias test
        $video->setProp('aspect_ratio', '9/16');
        self::assertSame('aspect-9/16', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '9x16');
        self::assertSame('aspect-9/16', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '21/9');
        self::assertSame('aspect-21/9', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '21x9');
        self::assertSame('aspect-21/9', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '4/3');
        self::assertSame('aspect-4/3', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '4x3');
        self::assertSame('aspect-4/3', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '1/1');
        self::assertSame('aspect-square', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '1x1');
        self::assertSame('aspect-square', $video->getAspectRatio());
        $video->setProp('aspect_ratio', '16x9');
        self::assertSame('aspect-video', $video->getAspectRatio());

        // Video rounded variations
        $video->setProp('rounded', 'full');
        self::assertSame('rounded-full', $video->getRounded());
        self::assertStringContainsString('rounded-full', $video->getBlockClasses());
    }

    public function testImageAspectRatiosAndRounded(): void
    {
        $image = new Image();
        $image->assign('image', 'https://cdn.example.com/banner.jpg');

        $image->mergeProps(['aspect_ratio' => '9/16', 'rounded' => '2xl']);
        self::assertSame('aspect-9/16', $image->getAspectRatio());
        self::assertSame('rounded-2xl', $image->getRounded());

        $image->mergeProps(['aspect_ratio' => '9x16', 'rounded' => 'full']);
        self::assertSame('aspect-9/16', $image->getAspectRatio());
        self::assertSame('rounded-full', $image->getRounded());

        $image->mergeProps(['aspect_ratio' => '21/9']);
        self::assertSame('aspect-21/9', $image->getAspectRatio());

        $image->mergeProps(['aspect_ratio' => '21x9']);
        self::assertSame('aspect-21/9', $image->getAspectRatio());

        $image->mergeProps(['aspect_ratio' => '16x9']);
        self::assertSame('aspect-video', $image->getAspectRatio());

        $image->mergeProps(['aspect_ratio' => '1x1']);
        self::assertSame('aspect-square', $image->getAspectRatio());

        $image->mergeProps(['aspect_ratio' => '4x3']);
        self::assertSame('aspect-4/3', $image->getAspectRatio());
    }

    public function testTextFallbacks(): void
    {
        $text = new Text();
        $text->mergeProps([
            'size' => 'xl',
            'color' => 'primary',
        ]);

        self::assertSame('text-xl', $text->getFontSize());
        self::assertSame('text-primary', $text->getTextColor());
    }

    public function testTextAdvancedFeatures(): void
    {
        $text = new Text();
        $text->mergeProps([
            'dropcap' => true,
            'column' => '1-2',
            'column_divider' => true,
            'text_style' => 'lead',
        ]);

        self::assertTrue($text->isDropcap());
        self::assertStringContainsString('first-letter:float-left', $text->getDropcapClasses());
        self::assertSame('md:columns-2 gap-8', $text->getColumnClasses());
        self::assertTrue($text->isColumnDivider());
        self::assertStringContainsString('column-rule', $text->getColumnDividerClasses());
        self::assertStringContainsString('text-xl sm:text-2xl', $text->getTextStyleClasses());

        $blockClasses = $text->getBlockClasses();
        self::assertStringContainsString('md:columns-2', $blockClasses);
        self::assertStringContainsString('first-letter:float-left', $blockClasses);
    }

    public function testCardLayoutAndAlignments(): void
    {
        $card = new Card();
        $card->assign('title', 'Modern Card');
        $card->assign('meta', 'Technology');
        $card->assign('content', '<p>Card content text.</p>');
        $card->assign('image', 'https://cdn.example.com/cover.jpg');
        $card->mergeProps([
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
        ]);

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
        self::assertStringContainsString('bg-primary-100', $card->getMetaBadgeClasses());
        self::assertSame('p-6 sm:p-8', $card->getCardPaddingClasses());
        self::assertSame('rounded-2xl', $card->getRoundedClasses());

        $classes = $card->getCardClasses();
        self::assertStringContainsString('flex-col md:flex-row', $classes);
        self::assertStringContainsString('rounded-2xl', $classes);
        self::assertStringContainsString('hover:-translate-y-1', $classes);

        // Overlay layout test
        $card->setProp('media_align', 'overlay');
        self::assertSame('overlay', $card->getMediaAlign());
        self::assertTrue($card->isOverlay());
        self::assertFalse($card->isHorizontal());
        self::assertSame('text-white', $card->getTitleColor());
        self::assertStringContainsString('relative text-white', $card->getCardClasses());
    }

    public function testCardClickableUrl(): void
    {
        $card = new Card();
        $card->assign('link', ['url' => 'https://example.com/article']);
        $card->assign('linkTitle', 'Read More');
        $card->mergeProps([
            'link_card' => 'card',
            'button_style' => 'outline',
        ]);

        self::assertSame('https://example.com/article', $card->getLinkUrl());
        self::assertSame('Read More', $card->getButtonText());
        self::assertSame('https://example.com/article', $card->getResolvedCardUrl());
        self::assertStringContainsString('border-primary-line text-primary', $card->getButtonStyleClasses());

        // Legacy cardLink compatibility test
        $legacyCard = new Card();
        $legacyCard->assign('cardLink', ['url' => 'https://example.com/legacy']);
        $legacyCard->mergeProps(['link_card' => 'card_link']);
        self::assertSame('https://example.com/legacy', $legacyCard->getResolvedCardUrl());

        // First button link fallback test
        $btnCard = new Card();
        $btnCard->assign('buttons', [
            ['link' => 'https://example.com/first-btn', 'content' => 'Read'],
            ['link' => 'https://example.com/second-btn', 'content' => 'Secondary'],
        ]);
        $btnCard->setProp('link_card', 'first_button');

        self::assertSame('https://example.com/first-btn', $btnCard->getResolvedCardUrl());
        self::assertSame('Read', $btnCard->getButtonText());
    }
}
