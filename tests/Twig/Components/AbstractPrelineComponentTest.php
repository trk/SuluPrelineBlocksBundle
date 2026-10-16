<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\AbstractPrelineComponent;

final class AbstractPrelineComponentTest extends TestCase
{
    public function testWrapperAndElementTransformHelpers(): void
    {
        $component = new DummyPrelineComponent();
        $component->load(
            data: ['title' => 'Preline Block'],
            props: [
                'id' => 'custom-el-id',
                'margin_top' => 'md',
                'margin_bottom' => 'sm',
                'text_align' => 'center',
                'custom_class' => 'extra-user-class',
            ],
        );

        self::assertSame('custom-el-id', $component->getElementId());
        self::assertSame('custom-el-id', $component->getCustomId());
        self::assertSame('extra-user-class', $component->getCustomClass());
        self::assertSame('mt-6 mb-4', $component->getMarginClass());

        $wrapper = $component->getWrapperClasses('base-block');
        self::assertStringContainsString('base-block', $wrapper);
        self::assertStringContainsString('mt-6', $wrapper);
        self::assertStringContainsString('mb-4', $wrapper);
        self::assertStringContainsString('text-center', $wrapper);
        self::assertStringContainsString('extra-user-class', $wrapper);
    }

    public function testMediaLinkAndIconHelpers(): void
    {
        $component = new DummyPrelineComponent();
        $component->load(
            data: [
                'title' => 'Fallback Alt Title',
                'image' => [
                    'url' => 'https://cdn.example.com/hero.jpg',
                    'formats' => [
                        'col-sm' => 'https://cdn.example.com/hero-400.webp',
                    ],
                    'focalPoint' => ['x' => 25, 'y' => 75],
                ],
                'video' => 'https://cdn.example.com/clip.mp4',
                'icon' => 'sparkles',
                'link' => ['url' => 'https://example.com/target', 'target' => '_blank'],
            ],
            props: [
                'max_width' => 'md',
            ],
        );

        self::assertSame('https://cdn.example.com/hero.jpg', $component->getImageUrl());
        self::assertNotNull($component->getImageSrcSet());
        self::assertStringContainsString('hero-400.webp 400w', (string) $component->getImageSrcSet());
        self::assertSame('object-position: 25% 75%;', $component->getImageFocusPointStyle());
        self::assertSame('(max-width: 768px) 100vw, 448px', $component->getImageSizes());
        self::assertSame('Fallback Alt Title', $component->getImageAlt());
        self::assertSame('https://cdn.example.com/clip.mp4', $component->getVideoUrl());
        self::assertSame('ti ti-sparkles', $component->getIconClass());
        self::assertSame('https://example.com/target', $component->getLinkUrl());
        self::assertSame('_blank', $component->getLinkTarget());
    }
}

final class DummyPrelineComponent extends AbstractPrelineComponent
{
    public function shouldRender(): bool
    {
        return $this->has('title');
    }
}
