<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;

/**
 * Preline UI 5 / Tailwind CSS 4 pure layout Section / Container component.
 */
#[AsTwigComponent('Section', template: '@SuluPrelineBlocks/components/Section.html.twig')]
final class Section extends PrelineComponent
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
            'padding' => 'default',
            'bg_style' => 'transparent',
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

    public function getSectionClasses(): string
    {
        $base = $this->classes(
            'relative overflow-hidden',
            PrelineClassMap::resolve(
                PrelineClassMap::SECTION_BG_STYLES,
                $this->stringProp('bg_style', 'transparent'),
                PrelineClassMap::SECTION_BG_STYLES['transparent'],
            ),
            $this->getPaddingClasses('default', 'padding', 'section_y'),
        );

        $sectionProps = $this->props();
        unset(
            $sectionProps['max_width'],
            $sectionProps['maxwidth'],
            $sectionProps['block_align'],
            $sectionProps['block_alignment'],
            $sectionProps['padding'],
            $sectionProps['padding_remove_top'],
            $sectionProps['padding_remove_bottom'],
            $sectionProps['block_padding'],
            $sectionProps['text_align'],
        );

        return ElementTransform::wrapperClasses($sectionProps, $base);
    }

    public function getWrapperClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        return $this->classes($this->getSectionClasses(), $extraBase, ...$moreParts);
    }

    /**
     * Extracts the inner Container props (`max_width`, `container_width`, `_slot`)
     * from the Section's combined Sulu settings so `<twig:Container>` only receives its own layer props.
     *
     * @return array<string, mixed>
     */
    public function getContainerProps(): array
    {
        $props = [
            'max_width' => $this->stringProp('max_width', '85rem'),
        ];

        $containerWidth = $this->stringProp('container_width');
        if ('' !== $containerWidth) {
            $props['container_width'] = $containerWidth;
        }

        $slot = $this->prop(self::SLOT_KEY) ?? ($this->context()[self::SLOT_KEY] ?? null);
        if (\is_array($slot)) {
            $props[self::SLOT_KEY] = $slot;
        }

        return $props;
    }

    /**
     * Raw rows assigned to this section, passed down to `<twig:Container>`.
     *
     * @return list<array<string, mixed>>
     */
    public function getRows(): array
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

        return $raw;
    }

    public function getContainerClasses(): string
    {
        return $this->createContainer()->getContainerClasses();
    }

    /**
     * @return array{class: string, px: int|null}
     */
    public function getMaxWidthPreset(): array
    {
        return $this->createContainer()->getMaxWidthPreset();
    }

    /**
     * The section's inner container slot width.
     */
    public function getSlotWidth(): ResponsiveWidth
    {
        return $this->createContainer()->getSlotWidth();
    }

    public function hasRows(): bool
    {
        return [] !== $this->getRows();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedRows(): array
    {
        return $this->createContainer()->getNormalizedRows();
    }

    private function createContainer(): Container
    {
        return new Container([
            'props' => $this->getContainerProps(),
            'rows' => $this->getRows(),
            'context' => $this->context(),
        ]);
    }
}
