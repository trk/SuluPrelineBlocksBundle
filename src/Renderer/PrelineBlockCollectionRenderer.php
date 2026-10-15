<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Renderer;

use Totoglu\SuluBlockSuiteBundle\Renderer\BlockCollectionRendererInterface;
use Twig\Environment;

/**
 * Preline UI 5 block collection renderer that automatically groups consecutive
 * non-section blocks into a responsive `<twig:Section>` container while rendering
 * top-level layout blocks (`section`, `breadcrumbs`) directly.
 */
final class PrelineBlockCollectionRenderer implements BlockCollectionRendererInterface
{
    public function __construct(
        private readonly Environment $twig,
    ) {
    }

    public function renderCollection(array $blocks, array $context = []): string
    {
        if ([] === $blocks) {
            return '';
        }

        return $this->twig->render(
            '@SuluPrelineBlocks/includes/blocks_renderer.html.twig',
            \array_merge($context, [
                'blocks' => $blocks,
            ]),
        );
    }
}
