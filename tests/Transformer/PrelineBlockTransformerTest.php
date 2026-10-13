<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Transformer;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Transformer\PrelineBlockTransformer;

final class PrelineBlockTransformerTest extends TestCase
{
    public function testTransformComputesWrapperClasses(): void
    {
        $transformer = new PrelineBlockTransformer();

        $block = [
            'type' => 'card',
            '_class' => 'custom-user-class',
            'props' => [
                'margin_top' => 'md',
                'margin_bottom' => 'lg',
                'max_width' => 'large',
                'block_align' => 'center',
            ],
        ];

        $transformed = $transformer->transform($block, []);

        $this->assertArrayHasKey('wrapper_class', $transformed);
        /** @var string $wrapper */
        $wrapper = $transformed['wrapper_class'];
        $this->assertStringContainsString('custom-user-class', $wrapper);
        $this->assertStringContainsString('mt-6', $wrapper);
        $this->assertStringContainsString('mb-10', $wrapper);
        $this->assertStringContainsString('max-w-lg', $wrapper);
        $this->assertStringContainsString('mx-auto', $wrapper);
    }

    public function testTransformResolvesMediaAndLinks(): void
    {
        $transformer = new PrelineBlockTransformer();

        $block = [
            'type' => 'button',
            'link' => ['url' => 'https://example.com/about'],
            'icon' => 'arrow-right',
            'image' => [
                'url' => 'https://example.com/image.jpg',
                'focusPoint' => ['x' => 50, 'y' => 50],
            ],
        ];

        $transformed = $transformer->transform($block, []);

        $this->assertSame('https://example.com/about', $transformed['link_url']);
        $this->assertSame('ti ti-arrow-right', $transformed['icon_class']);
        $this->assertSame('https://example.com/image.jpg', $transformed['image_url']);

        /** @var string $focusStyle */
        $focusStyle = isset($transformed['image_focus_style']) && \is_string($transformed['image_focus_style']) ? $transformed['image_focus_style'] : '';
        $this->assertStringContainsString('object-position: 50% 50%;', $focusStyle);
    }

    public function testTransformComputesGridClasses(): void
    {
        $transformer = new PrelineBlockTransformer();

        $gridBlock = [
            'type' => 'grid',
            'props' => [
                'cols_default' => '1',
                'cols_md' => '2',
                'cols_lg' => '3',
                'gap' => 'lg',
            ],
        ];

        $transformed = $transformer->transform($gridBlock, []);

        $this->assertArrayHasKey('grid_classes', $transformed);
        /** @var string $gridClasses */
        $gridClasses = $transformed['grid_classes'];
        $this->assertStringContainsString('grid', $gridClasses);
        $this->assertStringContainsString('grid-cols-1', $gridClasses);
        $this->assertStringContainsString('md:grid-cols-2', $gridClasses);
        $this->assertStringContainsString('lg:grid-cols-3', $gridClasses);
        $this->assertStringContainsString('gap-8', $gridClasses);
    }

    public function testTransformComputesColumnClasses(): void
    {
        $transformer = new PrelineBlockTransformer();

        $colBlock = [
            'type' => 'column',
            'col_span' => '6',
            'props' => [
                'is_sticky' => true,
            ],
        ];

        $transformed = $transformer->transform($colBlock, []);

        $this->assertArrayHasKey('column_classes', $transformed);
        /** @var string $columnClasses */
        $columnClasses = $transformed['column_classes'];
        $this->assertStringContainsString('col-span-12 md:col-span-6', $columnClasses);
        $this->assertStringContainsString('sticky top-24', $columnClasses);
    }
}
