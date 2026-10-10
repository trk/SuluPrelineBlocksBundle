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

        self::assertSame('', $button->content);
        self::assertSame('#', $button->link);
        self::assertNull($button->linkTitle);
        self::assertNull($button->link_title);
        self::assertSame('#', $button->getUrl());
        self::assertNull($button->getLinkTitle());
        self::assertStringContainsString('bg-blue-600', $button->getClasses());
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
        self::assertStringContainsString('border-indigo-600', $classes);
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
}
