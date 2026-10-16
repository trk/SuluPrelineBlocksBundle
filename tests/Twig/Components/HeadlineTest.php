<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Headline;

final class HeadlineTest extends TestCase
{
    public function testDefaultValuesAndRenderCheck(): void
    {
        $headline = new Headline();
        self::assertFalse($headline->shouldRender());
        self::assertSame('', $headline->getTitle());
        self::assertSame('h2', $headline->getTag());
        self::assertFalse($headline->isTextStroke());
        self::assertNull($headline->getUrl());

        $headline->mergeProps([
            'title' => 'Enterprise Solutions',
        ]);

        self::assertTrue($headline->shouldRender());
        self::assertSame('Enterprise Solutions', $headline->getTitle());
        self::assertStringContainsString('text-3xl', $headline->getWrapperClasses());
    }

    public function testTagsDecorationsAndTextStroke(): void
    {
        $headline = new Headline();
        $headline->mergeProps([
            'title' => 'Hero Heading',
            'link' => '/enterprise',
            'props' => [
                'tag' => 'h1',
                'size' => '2xl',
                'title_color' => 'primary',
                'title_decoration' => 'underline',
                'title_text_stroke' => true,
            ],
        ]);

        self::assertSame('h1', $headline->getTag());
        self::assertSame('/enterprise', $headline->getUrl());
        self::assertSame('underline', $headline->getTitleDecoration());
        self::assertTrue($headline->isTextStroke());
        self::assertStringContainsString('-webkit-text-stroke', $headline->getTextStrokeClasses());
        self::assertStringContainsString('text-primary', $headline->getWrapperClasses());
    }
}
