<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('DescriptionList', template: '@SuluPrelineBlocks/components/DescriptionList.html.twig')]
final class DescriptionList extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return ['title', 'items'];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'layout' => 'horizontal',
            'term_width' => '1-3',
            'divider' => true,
            'size' => 'md',
            'term_style' => 'bold',
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
            'horizontal' => fn (): bool => 'stacked' !== $this->getLayout(),
            'vertical' => fn (): bool => 'stacked' === $this->getLayout(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];
        foreach ($this->items('items') as $item) {
            $normalized[] = $this->normalizeItemMediaAndLinks($item);
        }

        return $normalized;
    }

    public function getLayout(): string
    {
        return $this->enumProp('layout', ['horizontal', 'stacked', 'striped', 'bordered'], 'horizontal');
    }

    public function isDivider(): bool
    {
        return $this->boolProp('divider', true);
    }

    public function getSize(): string
    {
        return $this->enumProp('size', ['sm', 'md', 'lg'], 'md');
    }

    public function getTermWidthClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::DESCRIPTION_LIST_TERM_WIDTHS,
            $this->stringProp('term_width', '1-3'),
            PrelineClassMap::DESCRIPTION_LIST_TERM_WIDTHS['1-3'],
        );
    }

    public function getDescWidthClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::DESCRIPTION_LIST_DESC_WIDTHS,
            $this->stringProp('term_width', '1-3'),
            PrelineClassMap::DESCRIPTION_LIST_DESC_WIDTHS['1-3'],
        );
    }

    public function getListClasses(): string
    {
        return match ($this->getLayout()) {
            'bordered' => 'border border-card-line rounded-xl overflow-hidden divide-y divide-card-divider',
            'striped' => 'divide-y divide-border border-t border-b border-border',
            default => $this->isDivider() ? 'divide-y divide-border' : 'space-y-4',
        };
    }

    public function getItemRowClasses(): string
    {
        $padding = PrelineClassMap::resolve(
            PrelineClassMap::DESCRIPTION_LIST_ROW_PADDINGS,
            $this->getSize(),
            PrelineClassMap::DESCRIPTION_LIST_ROW_PADDINGS['md'],
        );

        return match ($this->getLayout()) {
            'horizontal' => \sprintf('%s sm:flex sm:items-baseline gap-x-4', $padding),
            'striped' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 even:bg-background-1', $padding),
            'bordered' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 bg-card', $padding),
            default => \sprintf('%s flex flex-col gap-y-1', $padding),
        };
    }

    public function getTermClasses(): string
    {
        $termWidth = 'stacked' === $this->getLayout() ? 'w-full' : $this->getTermWidthClasses();
        $font = PrelineClassMap::resolve(
            PrelineClassMap::DESCRIPTION_LIST_TERM_STYLES,
            $this->stringProp('term_style', 'bold'),
            PrelineClassMap::DESCRIPTION_LIST_TERM_STYLES['bold'],
        );

        return \trim(\sprintf('%s %s shrink-0 inline-flex items-center gap-x-2 text-sm', $termWidth, $font));
    }

    public function getDescClasses(): string
    {
        $descWidth = 'stacked' === $this->getLayout() ? 'w-full' : $this->getDescWidthClasses();

        return \trim(\sprintf('%s text-sm text-muted-foreground-2', $descWidth));
    }
}
