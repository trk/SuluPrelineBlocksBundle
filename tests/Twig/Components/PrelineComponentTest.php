<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Card;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Headline;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PrelineClassMap;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PrelineComponent;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Section;

final class PrelineComponentTest extends TestCase
{
    public function testPrelineClassMapResolution(): void
    {
        self::assertSame('text-primary', PrelineClassMap::resolve(PrelineClassMap::TEXT_COLORS, 'primary'));
        self::assertSame(
            'text-muted-foreground-2',
            PrelineClassMap::resolve(PrelineClassMap::TEXT_COLORS, 'unknown', PrelineClassMap::TEXT_COLORS['default']),
        );
        self::assertSame('', PrelineClassMap::resolve(PrelineClassMap::TEXT_COLORS, null));

        self::assertSame(
            'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6',
            PrelineClassMap::resolveResponsiveGridClasses([
                'columns_default' => '1',
                'columns_sm' => '2',
                'columns_lg' => '3',
            ]),
        );
    }

    public function testUniversalControlsInPrelineComponent(): void
    {
        $component = new Headline();

        $component->mergeProps([
            'title' => 'Universal Controls Test',
            'props' => [
                'margin' => 'lg',
                'margin_remove_top' => true,
                'padding' => 'md',
                'padding_remove_bottom' => true,
                'max_width' => '3xl',
                'block_align' => 'center',
                'text_align' => 'center',
                'position' => 'relative',
                'visibility' => 'visible-md',
                'animation' => 'fade',
                'blend' => 'multiply',
                'parallax' => 'slow',
                'custom_class' => 'custom-test-block',
                'id' => 'hero-heading',
                'attributes' => "data-track=\"hero\"\ndata-section=\"top\"",
                'custom_css' => '.el { letter-spacing: 0.05em; }',
            ],
        ]);

        self::assertSame('mt-0 mb-10', $component->getMarginClass());
        self::assertSame('p-6 sm:p-8 pb-0 sm:pb-0', $component->getPaddingClass());
        self::assertSame('max-w-3xl', $component->getMaxWidthClass());
        self::assertSame('mx-auto', $component->getBlockAlignClass());
        self::assertSame('text-center', $component->getTextAlignClass());
        self::assertSame('relative', $component->getPositionClass());
        self::assertSame('block md:hidden', $component->getVisibilityClass());
        self::assertSame('transition-opacity duration-500 ease-out', $component->getAnimationClass());
        self::assertSame('mix-blend-multiply', $component->getBlendModeClass());
        self::assertSame('will-change-transform transition-transform duration-300 ease-out', $component->getParallaxClass());
        self::assertSame(['data-parallax' => 'slow'], $component->getParallaxAttributes());
        self::assertSame('custom-test-block', $component->getCustomClass());
        self::assertSame('hero-heading', $component->getElementId());

        $wrapperClasses = $component->getWrapperClasses();
        self::assertStringContainsString('mt-0 mb-10', $wrapperClasses);
        self::assertStringContainsString('p-6 sm:p-8 pb-0 sm:pb-0', $wrapperClasses);
        self::assertStringContainsString('max-w-3xl', $wrapperClasses);
        self::assertStringContainsString('mx-auto', $wrapperClasses);
        self::assertStringContainsString('text-center', $wrapperClasses);
        self::assertStringContainsString('relative', $wrapperClasses);
        self::assertStringContainsString('block md:hidden', $wrapperClasses);
        self::assertStringContainsString('transition-opacity', $wrapperClasses);
        self::assertStringContainsString('mix-blend-multiply', $wrapperClasses);
        self::assertStringContainsString('will-change-transform', $wrapperClasses);
        self::assertStringContainsString('custom-test-block', $wrapperClasses);

        $attrs = $component->getCustomAttributes();
        self::assertSame('hero', $attrs['data-track'] ?? null);
        self::assertSame('top', $attrs['data-section'] ?? null);
        self::assertSame('slow', $attrs['data-parallax'] ?? null);

        self::assertSame('#hero-heading .el { letter-spacing: 0.05em; }', $component->getScopedCss());
    }

    public function testCardInnerPaddingNotDuplicatedOnWrapper(): void
    {
        $card = new Card();
        $card->mergeProps([
            'title' => 'Card Title',
            'padding' => 'lg',
            'props' => [
                'blend' => 'overlay',
            ],
        ]);

        self::assertSame('p-8 sm:p-10', $card->getPaddingClasses());
        self::assertStringNotContainsString('p-8', $card->getWrapperClasses());
        self::assertStringContainsString('mix-blend-overlay', $card->getWrapperClasses());
    }

    public function testSectionFullBleedOuterWrapperAndInnerContainerMaxWidth(): void
    {
        $section = new Section();
        $section->mergeProps([
            'max_width' => '3xl',
            'padding' => 'md',
            'props' => [
                'blend' => 'screen',
                'margin' => 'sm',
            ],
        ]);

        self::assertStringNotContainsString('max-w-3xl', $section->getSectionClasses());
        self::assertStringContainsString('mix-blend-screen', $section->getSectionClasses());
        self::assertStringContainsString('my-4', $section->getSectionClasses());
        self::assertStringContainsString('max-w-3xl', $section->getContainerClasses());
    }

    public function testVariadicGetWrapperClassesNormalizesExtraParts(): void
    {
        $headline = new Headline();
        $headline->mergeProps([
            'title' => 'Hello',
            'props' => [
                'margin' => 'sm',
            ],
        ]);

        $classes = $headline->getWrapperClasses('custom-a', ['custom-b', null, ''], false, 'custom-c');
        self::assertStringContainsString('custom-a', $classes);
        self::assertStringContainsString('custom-b', $classes);
        self::assertStringContainsString('custom-c', $classes);
        self::assertStringContainsString('my-4', $classes);
        self::assertStringNotContainsString('tracking-tight', $classes);

        $blockClasses = $headline->getBlockClasses('inner-a', ['inner-b', null]);
        self::assertSame('w-full inner-a inner-b', $blockClasses);
    }

    public function testRootAttributesAndSubComponentResolvers(): void
    {
        $card = new Card();
        $card->mergeProps([
            'title' => 'Card',
            'badges' => [
                ['title' => 'Featured', 'badge_style' => 'solid', 'badge_color' => 'primary', 'size' => 'md'],
            ],
            'buttons' => [
                ['title' => 'Read More', 'link' => '/read', 'button_style' => 'outline', 'button_color' => 'secondary', 'size' => 'lg'],
            ],
            'props' => [
                'id' => 'card-root-1',
                'custom_class' => 'extra-card',
                'attributes' => 'data-card="true"',
            ],
        ]);

        $rootAttrs = $card->getRootAttributes('base-card');
        self::assertSame('card-root-1', $rootAttrs['id'] ?? null);
        self::assertSame('true', $rootAttrs['data-card'] ?? null);
        self::assertStringContainsString('base-card', (string) ($rootAttrs['class'] ?? ''));
        self::assertStringContainsString('extra-card', (string) ($rootAttrs['class'] ?? ''));

        $explicitClassAttrs = $card->getRootAttributesWithClass('explicit-class-only');
        self::assertSame('card-root-1', $explicitClassAttrs['id'] ?? null);
        self::assertSame('explicit-class-only', $explicitClassAttrs['class'] ?? null);

        $badges = $card->getNormalizedBadges();
        self::assertCount(1, $badges);
        self::assertSame('Featured', $badges[0]['title'] ?? null);
        self::assertSame('solid', $badges[0]['badge_style'] ?? null);
        self::assertSame('primary', $badges[0]['badge_color'] ?? null);
        self::assertSame('md', $badges[0]['size'] ?? null);

        $buttons = $card->getNormalizedButtons();
        self::assertCount(1, $buttons);
        self::assertSame('Read More', $buttons[0]['title'] ?? null);
        self::assertSame('/read', $buttons[0]['url'] ?? null);
        self::assertSame('outline', $buttons[0]['button_style'] ?? null);
        self::assertSame('secondary', $buttons[0]['button_color'] ?? null);
        self::assertSame('lg', $buttons[0]['size'] ?? null);
        self::assertTrue($buttons[0]['bare'] ?? false);
    }

    public function testMediaLinkAndIconHelpers(): void
    {
        $card = new Card();
        $card->load(
            data: [
                'title' => 'Fallback Alt Title',
                'image' => [
                    'url' => 'https://cdn.example.com/hero.jpg',
                    'formats' => [
                        '400w' => 'https://cdn.example.com/hero-400.webp',
                    ],
                    'focalPoint' => ['x' => 25, 'y' => 75],
                ],
                'video' => 'https://cdn.example.com/clip.mp4',
                'icon' => 'sparkles',
                'link' => ['url' => 'https://example.com/target', 'target' => '_blank'],
            ],
            props: [
                'max_width' => 'md',
            ],
        );

        self::assertSame('https://cdn.example.com/hero.jpg', $card->getImageUrl());
        self::assertNotNull($card->getImageSrcSet());
        self::assertStringContainsString('hero-400.webp 400w', (string) $card->getImageSrcSet());
        self::assertSame('object-position: 25% 75%;', $card->getImageFocusPointStyle());
        self::assertSame('Fallback Alt Title', $card->getImageAlt());
        self::assertSame('https://cdn.example.com/clip.mp4', $card->getVideoUrl());
        self::assertSame('ti ti-sparkles', $card->getIconClass());
        self::assertSame('https://example.com/target', $card->getLinkUrl());
        self::assertSame('_blank', $card->getLinkTarget());
    }
}
