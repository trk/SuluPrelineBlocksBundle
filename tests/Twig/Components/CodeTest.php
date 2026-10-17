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

        self::assertFalse($code->shouldRender());
        self::assertNull($code->get('title'));
        self::assertSame('', $code->string('code'));
        self::assertSame('plaintext', $code->getLanguage());
        self::assertTrue($code->boolProp('show_copy'));
        self::assertTrue($code->shouldShowCopy());
        self::assertFalse($code->boolProp('show_line_numbers'));
        self::assertFalse($code->shouldShowLineNumbers());
        self::assertSame('Code', $code->getLanguageLabel());
        self::assertStringStartsWith('hs-code-', $code->getCodeElementId());
        self::assertSame('', $code->getWrapperClasses());
        self::assertStringContainsString('rounded-2xl bg-card', $code->getBlockClasses());
        self::assertStringContainsString('rounded-2xl bg-card', $code->getContainerClasses());
        self::assertStringContainsString('bg-card-header', $code->getHeaderClasses());
        self::assertStringContainsString('overflow-x-auto text-sm', $code->getPreClasses());
    }

    public function testCustomElementIdDoesNotCollideWithCodeElementId(): void
    {
        $code = new Code();
        $code->load(data: ['code' => 'echo 1;'], props: ['id' => 'custom-root-id', 'show_copy' => false, 'show_line_numbers' => true]);

        self::assertSame('custom-root-id', $code->getElementId());
        self::assertStringStartsWith('hs-code-', $code->getCodeElementId());
        self::assertNotSame($code->getElementId(), $code->getCodeElementId());
        self::assertFalse($code->shouldShowCopy());
        self::assertTrue($code->shouldShowLineNumbers());
    }

    public function testLanguageLabels(): void
    {
        $code = new Code();
        $code->setProp('language', 'php');
        self::assertSame('PHP', $code->getLanguageLabel());

        $code->setProp('language', 'javascript');
        self::assertSame('JavaScript', $code->getLanguageLabel());

        $code->setProp('language', 'bash');
        self::assertSame('Bash', $code->getLanguageLabel());
    }

    public function testLinesSplitting(): void
    {
        $code = new Code();
        $code->load(data: ['code' => "line1\nline2\nline3"]);

        self::assertTrue($code->shouldRender());
        self::assertSame(['line1', 'line2', 'line3'], $code->getLines());
    }
}
