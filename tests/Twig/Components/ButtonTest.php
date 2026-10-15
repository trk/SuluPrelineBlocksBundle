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

        self::assertSame('', $button->title);
        self::assertSame('', $button->content);
        self::assertSame('', $button->getTitle());
        self::assertSame('#', $button->link);
        self::assertNull($button->linkTitle);
        self::assertNull($button->link_title);
        self::assertSame('#', $button->getUrl());
        self::assertNull($button->getLinkTitle());
        self::assertStringContainsString('bg-primary', $button->getClasses());
    }

    public function testTitleAndContentResolution(): void
    {
        $buttonTitle = new Button();
        $buttonTitle->title = 'Primary Action';
        self::assertSame('Primary Action', $buttonTitle->getTitle());

        $buttonContentFallback = new Button();
        $buttonContentFallback->content = 'Fallback Action';
        self::assertSame('Fallback Action', $buttonContentFallback->getTitle());

        $buttonBoth = new Button();
        $buttonBoth->title = 'Primary Title';
        $buttonBoth->content = 'Fallback Content';
        self::assertSame('Primary Title', $buttonBoth->getTitle());
    }

    public function testLinkTitleResolution(): void
    {
        $buttonCamel = new Button();
        $buttonCamel->linkTitle = 'Camel Title';
        self::assertSame('Camel Title', $buttonCamel->getLinkTitle());

        $buttonSnake = new Button();
        $buttonSnake->link_title = 'Snake Title';
        self::assertSame('Snake Title', $buttonSnake->getLinkTitle());
    }

    public function testUrlAndProps(): void
    {
        $button = new Button();
        $button->link = ['url' => 'https://totoglu.com', 'anchor' => 'portfolio'];
        $button->props = [
            'variant' => 'outline',
            'size' => 'lg',
            'full_width' => true,
        ];

        self::assertSame('https://totoglu.com#portfolio', $button->getUrl());
        self::assertStringContainsString('border', $button->getClasses());
        self::assertStringContainsString('text-base', $button->getClasses());
        self::assertStringContainsString('w-full', $button->getClasses());
    }

    public function testIndividualPropsTakePrecedence(): void
    {
        $button = new Button();
        $button->props = ['variant' => 'solid', 'size' => 'sm'];
        $button->variant = 'outline';
        $button->size = 'lg';
        $button->linkColor = 'secondary';

        $classes = $button->getClasses();
        self::assertStringContainsString('border', $classes);
        self::assertStringContainsString('border-secondary-line', $classes);
        self::assertStringContainsString('text-base', $classes);
    }

    public function testWrapperClassesAndId(): void
    {
        $button = new Button();
        $button->props = [
            'margin_top' => 'md',
            'margin_bottom' => 'lg',
            'id' => 'my-button',
            'custom_class' => 'shadow-lg',
        ];

        self::assertSame('my-button', $button->getElementId());
        $wrapper = $button->getWrapperClasses();
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-10', $wrapper);
        self::assertStringContainsString('shadow-lg', $wrapper);
    }

    public function testButtonGroupSeparated(): void
    {
        $button = new Button();
        $button->groupStyle = 'gap';
        $button->align = 'center';
        $button->gap = 'sm';
        $button->items = [
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
        ];

        self::assertTrue($button->isGroup());
        self::assertSame('gap', $button->getGroupStyle());
        self::assertSame('center', $button->getAlign());
        self::assertSame('sm', $button->getGap());

        $wrapper = $button->getGroupWrapperClasses();
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

        self::assertSame('Learn More', $normalized[1]['title']);
        self::assertSame('https://example.com/docs', $normalized[1]['url']);
        self::assertSame('ti ti-book', $normalized[1]['icon']);
        self::assertSame('left', $normalized[1]['icon_align']);
        self::assertStringContainsString('border', $normalized[1]['classes']);
        self::assertStringContainsString('text-foreground', $normalized[1]['classes']);
    }

    public function testButtonGroupConnectedSegmented(): void
    {
        $button = new Button();
        $button->groupStyle = 'connected';
        $button->orientation = 'horizontal';
        $button->items = [
            ['title' => 'Left', 'link' => '#left'],
            ['title' => 'Middle', 'link' => '#mid'],
            ['title' => 'Right', 'link' => '#right'],
        ];

        self::assertTrue($button->isGroup());
        self::assertSame('connected', $button->getGroupStyle());
        self::assertSame('horizontal', $button->getOrientation());

        $wrapper = $button->getGroupWrapperClasses();
        self::assertStringContainsString('inline-flex rounded-lg shadow-2xs', $wrapper);

        $normalized = $button->getNormalizedItems();
        self::assertCount(3, $normalized);

        // First item segmented class
        self::assertStringContainsString('rounded-s-lg rounded-e-none', $normalized[0]['classes']);
        // Middle item segmented class
        self::assertStringContainsString('rounded-none -ms-px', $normalized[1]['classes']);
        // Last item segmented class
        self::assertStringContainsString('rounded-e-lg rounded-s-none -ms-px', $normalized[2]['classes']);
    }

    public function testButtonGroupVerticalConnected(): void
    {
        $button = new Button();
        $button->groupStyle = 'connected';
        $button->orientation = 'vertical';
        $button->items = [
            ['title' => 'Top', 'link' => '#top'],
            ['title' => 'Bottom', 'link' => '#bot'],
        ];

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
        $buttonSoft->props = ['variant' => 'soft', 'link_color' => 'primary', 'size' => 'xs'];
        $softClasses = $buttonSoft->getClasses();
        self::assertStringContainsString('bg-primary-100', $softClasses);
        self::assertStringContainsString('text-primary-800', $softClasses);
        self::assertStringContainsString('py-1.5 px-2.5', $softClasses);

        $buttonWhite = new Button();
        $buttonWhite->props = ['variant' => 'white', 'size' => 'xl'];
        $whiteClasses = $buttonWhite->getClasses();
        self::assertStringContainsString('bg-layer text-layer-foreground', $whiteClasses);
        self::assertStringContainsString('py-4 px-6', $whiteClasses);
    }
}
