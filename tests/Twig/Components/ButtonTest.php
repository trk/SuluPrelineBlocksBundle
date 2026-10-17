<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Button;

final class ButtonTest extends TestCase
{
    public function testButtonDefaults(): void
    {
        $button = new Button();

        self::assertFalse($button->shouldRender());
        self::assertSame('', $button->getTitle());
        self::assertSame('#', $button->getUrl());
        self::assertNull($button->getLinkTitle());
        self::assertStringContainsString('bg-primary', $button->getClasses());
    }

    public function testTitleAndContentResolution(): void
    {
        $buttonTitle = new Button();
        $buttonTitle->load(data: ['title' => 'Primary Action']);
        self::assertTrue($buttonTitle->shouldRender());
        self::assertSame('Primary Action', $buttonTitle->getTitle());

        $buttonContentFallback = new Button();
        $buttonContentFallback->load(data: ['content' => 'Fallback Action']);
        self::assertTrue($buttonContentFallback->shouldRender());
        self::assertSame('Fallback Action', $buttonContentFallback->getTitle());

        $buttonBoth = new Button();
        $buttonBoth->load(data: ['title' => 'Primary Title', 'content' => 'Fallback Content']);
        self::assertSame('Primary Title', $buttonBoth->getTitle());
    }

    public function testLinkTitleResolution(): void
    {
        $buttonCamel = new Button(['linkTitle' => 'Camel Title']);
        self::assertSame('Camel Title', $buttonCamel->getLinkTitle());

        $buttonSnake = new Button(['link_title' => 'Snake Title']);
        self::assertSame('Snake Title', $buttonSnake->getLinkTitle());
    }

    public function testUrlAndProps(): void
    {
        $button = new Button();
        $button->load(
            data: ['link' => ['url' => 'https://totoglu.com', 'anchor' => 'portfolio']],
            props: [
                'variant' => 'outline',
                'size' => 'lg',
                'full_width' => true,
            ],
        );

        self::assertSame('https://totoglu.com#portfolio', $button->getUrl());
        self::assertStringContainsString('border', $button->getClasses());
        self::assertStringContainsString('text-base', $button->getClasses());
        self::assertStringContainsString('w-full', $button->getClasses());
    }

    public function testIndividualPropsTakePrecedence(): void
    {
        $button = new Button([
            'props' => ['variant' => 'solid', 'size' => 'sm'],
            'variant' => 'outline',
            'size' => 'lg',
            'linkColor' => 'secondary',
        ]);

        $classes = $button->getClasses();
        self::assertStringContainsString('border', $classes);
        self::assertStringContainsString('border-secondary-line', $classes);
        self::assertStringContainsString('text-base', $classes);
    }

    public function testWrapperClassesAndId(): void
    {
        $button = new Button();
        $button->load(
            props: [
                'margin_top' => 'md',
                'margin_bottom' => 'lg',
                'id' => 'my-button',
                'custom_class' => 'shadow-lg',
            ],
        );

        self::assertSame('my-button', $button->getElementId());
        self::assertFalse($button->isBare());
        $wrapper = $button->getWrapperClasses();
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('shadow-lg', $wrapper);
        self::assertStringNotContainsString('bg-primary', $wrapper);

        $blockClasses = $button->getBlockClasses();
        self::assertStringContainsString('inline-flex flex-wrap items-center', $blockClasses);
        self::assertStringContainsString('justify-start text-left', $blockClasses);

        $bareButton = new Button(['bare' => true, 'title' => 'Bare']);
        self::assertTrue($bareButton->isBare());
    }

    public function testButtonGroupSeparated(): void
    {
        $button = new Button();
        $button->load(
            data: [
                'items' => [
                    [
                        'title' => 'Get Started',
                        'link' => 'https://example.com/start',
                        'button_style' => 'solid',
                        'button_color' => 'primary',
                        'icon' => 'arrow-right',
                        'icon_align' => 'right',
                    ],
                    [
                        'title' => 'Learn More',
                        'link' => 'https://example.com/docs',
                        'button_style' => 'outline',
                        'button_color' => 'dark',
                        'icon' => 'book',
                        'icon_align' => 'left',
                    ],
                ],
            ],
            props: [
                'group_style' => 'gap',
                'align' => 'center',
                'gap' => 'sm',
                'margin' => 'md',
            ],
        );

        self::assertTrue($button->isGroup());
        self::assertTrue($button->is('group'));
        self::assertTrue($button->is('multiple'));
        self::assertTrue($button->shouldRender());
        self::assertSame('gap', $button->getGroupStyle());
        self::assertSame('center', $button->getAlign());
        self::assertSame('sm', $button->getGap());

        self::assertSame('my-6', $button->getWrapperClasses());
        $wrapper = $button->getBlockClasses();
        self::assertStringContainsString('flex flex-wrap items-center', $wrapper);
        self::assertStringContainsString('gap-1.5', $wrapper);
        self::assertStringContainsString('justify-center', $wrapper);

        $normalized = $button->getNormalizedItems();
        self::assertCount(2, $normalized);

        self::assertSame('Get Started', $normalized[0]['title']);
        self::assertSame('https://example.com/start', $normalized[0]['url']);
        self::assertSame('ti ti-arrow-right', $normalized[0]['icon']);
        self::assertSame('right', $normalized[0]['icon_align']);
        self::assertStringContainsString('bg-primary', $normalized[0]['classes']);
        self::assertTrue($normalized[0]['bare']);

        self::assertSame('Learn More', $normalized[1]['title']);
        self::assertSame('https://example.com/docs', $normalized[1]['url']);
        self::assertSame('ti ti-book', $normalized[1]['icon']);
        self::assertSame('left', $normalized[1]['icon_align']);
        self::assertStringContainsString('border', $normalized[1]['classes']);
        self::assertStringContainsString('text-foreground', $normalized[1]['classes']);
        self::assertTrue($normalized[1]['bare']);
    }

    public function testButtonGroupConnectedSegmented(): void
    {
        $button = new Button();
        $button->load(
            data: [
                'items' => [
                    ['title' => 'Left', 'link' => '#left'],
                    ['title' => 'Middle', 'link' => '#mid'],
                    ['title' => 'Right', 'link' => '#right'],
                ],
            ],
            props: [
                'group_style' => 'connected',
                'orientation' => 'horizontal',
            ],
        );

        self::assertTrue($button->isGroup());
        self::assertTrue($button->is('connected'));
        self::assertSame('connected', $button->getGroupStyle());
        self::assertSame('horizontal', $button->getOrientation());

        $wrapper = $button->getGroupWrapperClasses();
        self::assertStringContainsString('inline-flex rounded-lg shadow-2xs', $wrapper);

        $normalized = $button->getNormalizedItems();
        self::assertCount(3, $normalized);

        self::assertStringContainsString('rounded-s-lg rounded-e-none', $normalized[0]['classes']);
        self::assertStringContainsString('rounded-none -ms-px', $normalized[1]['classes']);
        self::assertStringContainsString('rounded-e-lg rounded-s-none -ms-px', $normalized[2]['classes']);
    }

    public function testButtonGroupVerticalConnected(): void
    {
        $button = new Button();
        $button->load(
            data: [
                'items' => [
                    ['title' => 'Top', 'link' => '#top'],
                    ['title' => 'Bottom', 'link' => '#bot'],
                ],
            ],
            props: [
                'group_style' => 'connected',
                'orientation' => 'vertical',
            ],
        );

        self::assertTrue($button->is('vertical'));
        $wrapper = $button->getGroupWrapperClasses();
        self::assertStringContainsString('inline-flex flex-col rounded-lg shadow-2xs', $wrapper);

        $normalized = $button->getNormalizedItems();
        self::assertCount(2, $normalized);
        self::assertStringContainsString('rounded-t-lg rounded-b-none', $normalized[0]['classes']);
        self::assertStringContainsString('rounded-b-lg rounded-t-none -mt-px', $normalized[1]['classes']);
    }

    public function testPrelineSoftAndWhiteVariants(): void
    {
        $buttonSoft = new Button();
        $buttonSoft->load(props: ['variant' => 'soft', 'link_color' => 'primary', 'size' => 'xs']);
        $softClasses = $buttonSoft->getClasses();
        self::assertStringContainsString('bg-primary-100', $softClasses);
        self::assertStringContainsString('text-primary-800', $softClasses);
        self::assertStringContainsString('py-1.5 px-2.5', $softClasses);

        $buttonWhite = new Button();
        $buttonWhite->load(props: ['variant' => 'white', 'size' => 'xl']);
        $whiteClasses = $buttonWhite->getClasses();
        self::assertStringContainsString('bg-layer text-layer-foreground', $whiteClasses);
        self::assertStringContainsString('py-4 px-6', $whiteClasses);
    }
}
