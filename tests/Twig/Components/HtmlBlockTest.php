<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\HtmlBlock;

final class HtmlBlockTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $html = new HtmlBlock();

        self::assertNull($html->title);
        self::assertNull($html->content);
        self::assertFalse($html->raw_output);
        self::assertFalse($html->isRaw());
        self::assertFalse($html->hasContent());
        self::assertSame('', $html->getContent());
        self::assertSame('w-full my-4', $html->getWrapperClasses());
    }

    public function testContentAndRaw(): void
    {
        $html = new HtmlBlock();
        $html->content = '<div class="custom-widget">Widget Content</div>';
        $html->raw_output = true;

        self::assertTrue($html->hasContent());
        self::assertTrue($html->isRaw());
        self::assertSame('<div class="custom-widget">Widget Content</div>', $html->getContent());
    }

    public function testCardProp(): void
    {
        $html = new HtmlBlock();
        $html->props = ['card' => true];

        self::assertStringContainsString('border border-card-line', $html->getWrapperClasses());
        self::assertStringContainsString('bg-card', $html->getWrapperClasses());
    }
}
