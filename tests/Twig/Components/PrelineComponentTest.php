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
                'margin_top' => 'md',
                'margin_bottom' => 'lg',
                'padding' => 'md',
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

        self::assertSame('mt-6 mb-10', $component->getMarginClass());
        self::assertSame('p-6 sm:p-8', $component->getPaddingClass());
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
        self::assertStringContainsString('mt-6 mb-10', $wrapperClasses);
        self::assertStringContainsString('p-6 sm:p-8', $wrapperClasses);
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
            'max_width' => 'md',
            'padding_y' => 'md',
            'props' => [
                'blend' => 'screen',
                'margin_top' => 'sm',
            ],
        ]);

        self::assertStringNotContainsString('max-w-3xl', $section->getSectionClasses());
        self::assertStringContainsString('mix-blend-screen', $section->getSectionClasses());
        self::assertStringContainsString('mt-4', $section->getSectionClasses());
        self::assertStringContainsString('max-w-3xl', $section->getContainerClasses());
    }

    public function testVariadicGetWrapperClassesNormalizesExtraParts(): void
    {
        $headline = new Headline();
        $headline->mergeProps([
            'title' => 'Hello',
            'props' => [
                'margin_top' => 'sm',
            ],
        ]);

        $classes = $headline->getWrapperClasses('custom-a', ['custom-b', null, ''], false, 'custom-c');
        self::assertStringContainsString('custom-a', $classes);
        self::assertStringContainsString('custom-b', $classes);
        self::assertStringContainsString('custom-c', $classes);
        self::assertStringContainsString('mt-4', $classes);
    }
}
