<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('DescriptionList', template: '@SuluPrelineBlocks/components/DescriptionList.html.twig')]
final class DescriptionList extends AbstractPrelineComponent
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
            if (isset($item['link'])) {
                $item['link_href'] = PropsResolver::resolveUrl($item['link']);
            }
            $normalized[] = $item;
        }

        return $normalized;
    }

    public function getLayout(): string
    {
        return match ($this->stringProp('layout', 'horizontal')) {
            'stacked' => 'stacked',
            'striped' => 'striped',
            'bordered' => 'bordered',
            default => 'horizontal',
        };
    }

    public function isDivider(): bool
    {
        return $this->boolProp('divider', true);
    }

    public function getSize(): string
    {
        return match ($this->stringProp('size', 'md')) {
            'sm' => 'sm',
            'lg' => 'lg',
            default => 'md',
        };
    }

    public function getTermWidthClasses(): string
    {
        return match ($this->stringProp('term_width', '1-3')) {
            '1-4', '1/4' => 'sm:w-1/4',
            '1-2', '1/2' => 'sm:w-1/2',
            'auto' => 'sm:w-auto',
            default => 'sm:w-1/3',
        };
    }

    public function getDescWidthClasses(): string
    {
        return match ($this->stringProp('term_width', '1-3')) {
            '1-4', '1/4' => 'sm:w-3/4',
            '1-2', '1/2' => 'sm:w-1/2',
            'auto' => 'sm:flex-1',
            default => 'sm:w-2/3',
        };
    }

    public function getListClasses(): string
    {
        $layout = $this->getLayout();
        $divider = $this->isDivider();

        return match ($layout) {
            'bordered' => 'border border-card-line rounded-xl overflow-hidden divide-y divide-card-divider',
            'striped' => 'divide-y divide-border border-t border-b border-border',
            default => $divider ? 'divide-y divide-border' : 'space-y-4',
        };
    }

    public function getItemRowClasses(): string
    {
        $layout = $this->getLayout();

        $padding = match ($this->getSize()) {
            'sm' => 'py-2 px-3',
            'lg' => 'py-4 px-4',
            default => 'py-3 px-3.5',
        };

        return match ($layout) {
            'horizontal' => \sprintf('%s sm:flex sm:items-baseline gap-x-4', $padding),
            'striped' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 even:bg-background-1', $padding),
            'bordered' => \sprintf('%s sm:flex sm:items-baseline gap-x-4 bg-card', $padding),
            default => \sprintf('%s flex flex-col gap-y-1', $padding),
        };
    }

    public function getTermClasses(): string
    {
        $style = $this->stringProp('term_style', 'bold');
        $layout = $this->getLayout();
        $termWidth = 'stacked' === $layout ? 'w-full' : $this->getTermWidthClasses();

        $font = match ($style) {
            'medium' => 'font-medium text-foreground',
            'muted' => 'font-normal text-muted-foreground-1',
            'primary' => 'font-semibold text-primary',
            default => 'font-semibold text-foreground',
        };

        return \trim(\sprintf('%s %s shrink-0 inline-flex items-center gap-x-2 text-sm', $termWidth, $font));
    }

    public function getDescClasses(): string
    {
        $layout = $this->getLayout();
        $descWidth = 'stacked' === $layout ? 'w-full' : $this->getDescWidthClasses();

        return \trim(\sprintf('%s text-sm text-muted-foreground-2', $descWidth));
    }

    public function getMarginClass(): string
    {
        $top = match ($this->stringProp('margin_top', 'none')) {
            'sm' => 'mt-4',
            'md' => 'mt-8',
            'lg' => 'mt-12',
            'xl' => 'mt-16',
            default => '',
        };

        $bottom = match ($this->stringProp('margin_bottom', 'none')) {
            'sm' => 'mb-4',
            'md' => 'mb-8',
            'lg' => 'mb-12',
            'xl' => 'mb-16',
            default => '',
        };

        return \trim($top . ' ' . $bottom);
    }
}
