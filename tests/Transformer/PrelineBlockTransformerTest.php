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
                'margin' => 'lg',
                'margin_remove_top' => true,
                'max_width' => 'large',
                'block_align' => 'center',
            ],
        ];

        $transformed = $transformer->transform($block, []);

        $this->assertArrayHasKey('wrapper_class', $transformed);
        $this->assertArrayNotHasKey('_wrapper_class', $transformed);
        $this->assertArrayNotHasKey('grid_classes', $transformed);
        $this->assertArrayNotHasKey('column_classes', $transformed);
        $this->assertArrayNotHasKey('image_object_fit', $transformed);
        $this->assertArrayNotHasKey('icon_class', $transformed);
        /** @var string $wrapper */
        $wrapper = $transformed['wrapper_class'];
        $this->assertStringContainsString('custom-user-class', $wrapper);
        $this->assertStringContainsString('mt-0', $wrapper);
        $this->assertStringContainsString('mb-10', $wrapper);
        $this->assertStringContainsString('max-w-lg', $wrapper);
        $this->assertStringContainsString('mx-auto', $wrapper);
    }
}
