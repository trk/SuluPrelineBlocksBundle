<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Code;

final class CodeTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $code = new Code();

        self::assertNull($code->title);
        self::assertSame('', $code->code);
        self::assertSame('plaintext', $code->language);
        self::assertTrue($code->show_copy);
        self::assertFalse($code->show_line_numbers);
        self::assertSame('Code', $code->getLanguageLabel());
        self::assertStringStartsWith('hs-code-', $code->getId());
        self::assertStringContainsString('rounded-2xl bg-card', $code->getContainerClasses());
        self::assertStringContainsString('bg-card-header', $code->getHeaderClasses());
        self::assertStringContainsString('overflow-x-auto text-sm', $code->getPreClasses());
    }

    public function testLanguageLabels(): void
    {
        $code = new Code();
        $code->language = 'php';
        self::assertSame('PHP', $code->getLanguageLabel());

        $code->language = 'javascript';
        self::assertSame('JavaScript', $code->getLanguageLabel());

        $code->language = 'bash';
        self::assertSame('Bash', $code->getLanguageLabel());
    }

    public function testLinesSplitting(): void
    {
        $code = new Code();
        $code->code = "line1\nline2\nline3";

        self::assertSame(['line1', 'line2', 'line3'], $code->getLines());
    }
}
