<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Transformer;

use Totoglu\SuluBlockSuiteBundle\Transformer\NestedBlockFlattenerInterface;

/**
 * Unwraps a `section > rows > columns > blocks` hierarchy into flat leaf blocks
 * when a reusable block is expanded inside an already active nested container or column,
 * preventing nested `<section>` elements.
 */
final class SectionFlattener implements NestedBlockFlattenerInterface
{
    private const MAX_DEPTH = 8;

    public function supports(array $block): bool
    {
        return 'section' === ($block['type'] ?? null);
    }

    public function flatten(array $block, callable $expandChild): array
    {
        return $this->flattenAtDepth($block, $expandChild, 0);
    }

    /**
     * @param array<string, mixed> $block
     * @param callable(array<string, mixed>): list<array<string, mixed>> $expandChild
     *
     * @return list<array<string, mixed>>
     */
    private function flattenAtDepth(array $block, callable $expandChild, int $depth): array
    {
        if ($depth >= self::MAX_DEPTH) {
            return [];
        }

        $rows = $block['rows'] ?? null;
        if (!\is_iterable($rows)) {
            return [];
        }

        /** @var list<array<string, mixed>> $leafBlocks */
        $leafBlocks = [];
        foreach ($rows as $row) {
            $normalizedRow = $this->normalizeNode($row);
            if (null === $normalizedRow) {
                continue;
            }

            $columns = $normalizedRow['columns'] ?? null;
            if (!\is_iterable($columns)) {
                continue;
            }

            foreach ($columns as $column) {
                $normalizedCol = $this->normalizeNode($column);
                if (null === $normalizedCol) {
                    continue;
                }

                $blocks = $normalizedCol['blocks'] ?? null;
                if (!\is_iterable($blocks)) {
                    continue;
                }

                foreach ($blocks as $leaf) {
                    $normalizedLeaf = $this->normalizeNode($leaf);
                    if (null === $normalizedLeaf) {
                        continue;
                    }

                    if ($this->supports($normalizedLeaf)) {
                        foreach ($this->flattenAtDepth($normalizedLeaf, $expandChild, $depth + 1) as $nestedLeaf) {
                            $leafBlocks[] = $nestedLeaf;
                        }

                        continue;
                    }

                    foreach ($expandChild($normalizedLeaf) as $expanded) {
                        $leafBlocks[] = $expanded;
                    }
                }
            }
        }

        return $leafBlocks;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function normalizeNode(mixed $node): ?array
    {
        if (!\is_array($node) && !\is_object($node)) {
            return null;
        }

        if (\is_object($node)) {
            if (\method_exists($node, 'getContent')) {
                /** @var mixed $node */
                $node = $node->getContent();
            } elseif (\method_exists($node, 'toArray')) {
                /** @var mixed $node */
                $node = $node->toArray();
            } else {
                $node = (array) $node;
            }
        }

        if (!\is_array($node)) {
            return null;
        }

        /** @var array<string, mixed> $node */
        return $node;
    }
}
