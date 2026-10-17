<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Quotation;

final class QuotationTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $quote = new Quotation();

        self::assertNull($quote->get('content'));
        self::assertNull($quote->get('author'));
        self::assertNull($quote->get('authorTitle'));
        self::assertNull($quote->getAuthor());
        self::assertNull($quote->getAuthorTitle());
        self::assertNull($quote->getAvatarUrl());
        self::assertNull($quote->getAuthorUrl());
        self::assertFalse($quote->hasAuthor());
        self::assertSame('bordered', $quote->getQuoteStyle());
        self::assertStringContainsString('border-s-4', $quote->getContainerClasses());
        self::assertStringContainsString('text-base', $quote->getQuoteTextClasses());
    }

    public function testAuthorAndAvatar(): void
    {
        $quote = new Quotation();
        $quote->assign('author', 'Steve Jobs');
        $quote->assign('authorTitle', 'CEO, Apple');
        $quote->assign('image', [
            'url' => '/uploads/avatar.jpg',
            'focalPoint' => ['x' => 50, 'y' => 20],
        ]);
        $quote->assign('link', 'https://apple.com');

        self::assertSame('Steve Jobs', $quote->getAuthor());
        self::assertSame('CEO, Apple', $quote->getAuthorTitle());
        self::assertSame('/uploads/avatar.jpg', $quote->getAvatarUrl());
        self::assertSame('object-position: 50% 20%;', $quote->getAvatarFocusPointStyle());
        self::assertSame('https://apple.com', $quote->getAuthorUrl());
        self::assertTrue($quote->hasAuthor());
    }

    public function testStyles(): void
    {
        $quoteCard = new Quotation();
        $quoteCard->mergeProps(['quote_style' => 'card', 'quote_size' => 'lg']);
        self::assertSame('card', $quoteCard->getQuoteStyle());
        self::assertStringContainsString('rounded-2xl', $quoteCard->getContainerClasses());
        self::assertStringContainsString('text-xl', $quoteCard->getQuoteTextClasses());

        $quoteLarge = new Quotation();
        $quoteLarge->mergeProps(['quote_style' => 'large']);
        self::assertSame('large', $quoteLarge->getQuoteStyle());
        self::assertStringContainsString('text-xl', $quoteLarge->getQuoteTextClasses());
    }

    public function testWrapperClassesAndId(): void
    {
        $quote = new Quotation();
        $quote->mergeProps([
            'id' => 'hero-quote',
            'margin_top' => 'lg',
            'margin_bottom' => 'xl',
            'custom_class' => 'my-quote',
        ]);

        self::assertSame('hero-quote', $quote->getElementId());
        $wrapper = $quote->getWrapperClasses();
        self::assertStringNotContainsString('w-full flex flex-col', $wrapper);
        self::assertStringContainsString('mt-10', $wrapper);
        self::assertStringContainsString('mb-16', $wrapper);
        self::assertStringContainsString('my-quote', $wrapper);

        $blockClasses = $quote->getBlockClasses();
        self::assertStringContainsString('relative w-full flex flex-col', $blockClasses);
    }
}
