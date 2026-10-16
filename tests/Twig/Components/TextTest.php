<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Text;

final class TextTest extends TestCase
{
    public function testDefaultValuesAndRenderCheck(): void
    {
        $text = new Text();
        self::assertFalse($text->shouldRender());
        self::assertTrue($text->isProse());
        self::assertFalse($text->isDropcap());
        self::assertFalse($text->isColumnDivider());

        $text->mergeProps([
            'content' => '<p>Rich body paragraph</p>',
        ]);

        self::assertTrue($text->shouldRender());
        self::assertSame('<p>Rich body paragraph</p>', $text->getContent());
        self::assertStringContainsString('prose dark:prose-invert', $text->getWrapperClasses());
    }

    public function testColumnsDropcapAndTextStyle(): void
    {
        $text = new Text();
        $text->mergeProps([
            'text' => 'Fallback plain text',
            'props' => [
                'prose' => false,
                'dropcap' => true,
                'column' => '1-2',
                'column_divider' => true,
                'text_style' => 'lead',
                'font_size' => 'lg',
                'text_color' => 'muted',
            ],
        ]);

        self::assertTrue($text->shouldRender());
        self::assertFalse($text->isProse());
        self::assertTrue($text->isDropcap());
        self::assertTrue($text->isColumnDivider());
        self::assertStringContainsString('first-letter:float-left', $text->getDropcapClasses());
        self::assertStringContainsString('md:columns-2', $text->getColumnClasses());
        self::assertStringContainsString('column-rule', $text->getColumnDividerClasses());
        self::assertStringContainsString('text-muted-foreground-1', $text->getWrapperClasses());
    }
}
