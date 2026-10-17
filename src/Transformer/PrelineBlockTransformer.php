<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Transformer;

use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluBlockSuiteBundle\Transformer\BlockTransformerInterface;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\ElementTransform;

/**
 * Preline UI 5 & Tailwind CSS 4 Block Transformer.
 * Normalizes framework-specific layout wrapper classes.
 * Priority: 50 (Runs after core dynamic content and universal default attributes).
 */
final class PrelineBlockTransformer implements BlockTransformerInterface
{
    public static function getDefaultPriority(): int
    {
        return 50;
    }

    public function supports(array $block, array $context): bool
    {
        return true;
    }

    public function transform(array $block, array $context): array
    {
        $props = SuluDataNormalizer::extractProps($block);

        $extraClass = isset($block['_class']) && \is_string($block['_class']) ? $block['_class'] : '';
        $wrapperClasses = ElementTransform::wrapperClasses($props, $extraClass);

        if ('' !== $wrapperClasses) {
            $block['wrapper_class'] = $wrapperClasses;
        }

        return $block;
    }
}
