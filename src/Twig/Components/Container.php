<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Container component.
 *
 * Manages horizontal max-width (`85rem`, `container`, `7xl`, `5xl`, `3xl`, `full`),
 * standard horizontal padding, centering (`mx-auto`), and propagates the resolved
 * container slot width (`_slot`) to child `Row` components.
 */
#[AsTwigComponent('Container', template: '@SuluPrelineBlocks/components/Container.html.twig')]
final class Container extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['rows'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'max_width' => '85rem',
        ];
    }

    public function shouldRender(): bool
    {
        return true;
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'container' => true,
            'multiple' => true,
        ];
    }

    /**
     * @return array{class: string, px: int|null}
     */
    public function getMaxWidthPreset(): array
    {
        $widthKey = $this->stringProp('container_width', $this->stringProp('max_width', '85rem'));

        return PrelineClassMap::sectionMaxWidth($widthKey);
    }

    public function getContainerClasses(): string
    {
        $base = $this->classes(
            'mx-auto',
            $this->getMaxWidthPreset()['class'],
            PrelineClassMap::SECTION_PADDING_X['default'],
        );

        $containerProps = $this->props();
        unset(
            $containerProps['max_width'],
            $containerProps['maxwidth'],
            $containerProps['block_align'],
            $containerProps['block_alignment'],
            $containerProps['padding'],
            $containerProps['padding_remove_top'],
            $containerProps['padding_remove_bottom'],
            $containerProps['block_padding'],
            $containerProps['text_align'],
        );

        return ElementTransform::wrapperClasses($containerProps, $base);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes($this->getContainerClasses(), $extraBase, ...$moreParts);
    }

    /**
     * The container slot: the container preset width, nested inside a parent slot when
     * rendered within another layout.
     */
    public function getSlotWidth(): ResponsiveWidth
    {
        $containerPx = $this->getMaxWidthPreset()['px'];
        $parent = ResponsiveWidth::fromArray($this->prop(self::SLOT_KEY))
            ?? ResponsiveWidth::fromArray($this->context()[self::SLOT_KEY] ?? null);

        return null !== $parent ? $parent->cap($containerPx) : ResponsiveWidth::container($containerPx);
    }

    public function hasRows(): bool
    {
        return [] !== $this->getNormalizedRows();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedRows(): array
    {
        $raw = $this->items('rows');
        if ([] === $raw) {
            $propRows = $this->prop('rows', []);
            if (\is_array($propRows)) {
                foreach ($propRows as $item) {
                    if (\is_array($item)) {
                        /** @var array<string, mixed> $item */
                        $raw[] = $item;
                    }
                }
            }
        }

        $layout = $this->getSlotWidth()->toArray();
        $list = [];
        foreach ($raw as $item) {
            $rowProps = SuluDataNormalizer::extractProps($item);
            if ([] !== $rowProps) {
                $item = \array_merge($item, $rowProps);
            }
            $item[self::SLOT_KEY] = $layout;
            $list[] = $item;
        }

        return $list;
    }
}
