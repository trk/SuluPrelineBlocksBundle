<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Totoglu\SuluBlockSuiteBundle\Normalizer\SuluDataNormalizer;

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
            'padding_y' => 'default',
            'padding_x' => 'default',
            'bg_style' => 'transparent',
            'has_border' => false,
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
        $classes = [
            'relative',
            'w-full',
            'overflow-hidden',
            PrelineClassMap::resolve(
                PrelineClassMap::SECTION_BG_STYLES,
                $this->stringProp('bg_style', 'transparent'),
                PrelineClassMap::SECTION_BG_STYLES['transparent'],
            ),
        ];

        if ($this->boolProp('has_border', false)) {
            $classes[] = 'border-t border-b border-border';
        }

        $sectionProps = $this->props();
        $sectionProps['max_width'] = 'none';

        return ElementTransform::wrapperClasses($sectionProps, \implode(' ', \array_filter($classes)));
    }

    public function getContainerClasses(): string
    {
        $classes = [
            'mx-auto',
            'w-full',
            $this->getMaxWidthClasses('85rem', 'section'),
            $this->getPaddingClasses('default', 'padding_x', 'section_x'),
            $this->getPaddingClasses('default', 'padding_y', 'section_y'),
        ];

        return \implode(' ', \array_filter($classes));
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

        $list = [];
        foreach ($raw as $item) {
            $rowProps = SuluDataNormalizer::extractProps($item);
            if ([] !== $rowProps) {
                $item = \array_merge($item, $rowProps);
            }
            $list[] = $item;
        }

        return $list;
    }
}
