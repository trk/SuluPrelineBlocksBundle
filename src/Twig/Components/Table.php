<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

/**
 * Preline UI 5 / Tailwind CSS 4 accessible responsive data table component.
 */
#[AsTwigComponent('Table', template: '@SuluPrelineBlocks/components/Table.html.twig')]
final class Table extends PrelineComponent
{
    /**
     * @return list<string>
     */
    protected function dataKeys(): array
    {
        return [
            'title',
            'header_title',
            'header_meta',
            'header_content',
            'header_badge',
            'header_link',
            'items',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'style' => 'default',
            'size' => 'md',
            'sticky_header' => false,
            'bordered' => false,
        ];
    }

    public function shouldRender(): bool
    {
        return [] !== $this->getNormalizedItems() || $this->hasHeader();
    }

    /**
     * @return array<string, bool|\Closure>
     */
    protected function capabilities(): array
    {
        return [
            'multiple' => true,
        ];
    }

    public function getStyle(): string
    {
        return $this->stringProp('style', 'default');
    }

    public function getSize(): string
    {
        return $this->stringProp('size', 'md');
    }

    public function isStickyHeader(): bool
    {
        return $this->boolProp('sticky_header', false);
    }

    public function isHover(): bool
    {
        $style = $this->getStyle();

        return 'hover' === $style || 'striped' === $style;
    }

    public function isBordered(): bool
    {
        return $this->boolProp('bordered', false);
    }

    public function hasHeader(): bool
    {
        return $this->hasAny('header_title', 'header_meta', 'header_content', 'header_badge', 'header_link');
    }

    /**
     * @param string|array<int, string|null|false>|null|false ...$moreParts
     */
    public function getTableContainerClasses(string $extraBase = '', string|array|null|false ...$moreParts): string
    {
        $border = $this->isBordered()
            ? 'border border-card-line rounded-2xl overflow-hidden shadow-2xs bg-card'
            : '';

        return $this->classes(
            'overflow-x-auto w-full',
            $border,
            $extraBase,
            ...$moreParts,
        );
    }

    public function getTableClasses(): string
    {
        return 'min-w-full divide-y divide-table-line text-start';
    }

    public function getTheadClasses(): string
    {
        return $this->classes(
            'bg-background-1',
            $this->isStickyHeader() ? 'sticky top-0 z-10' : null,
        );
    }

    public function getCellPaddingClasses(): string
    {
        return PrelineClassMap::resolve(
            PrelineClassMap::TABLE_CELL_PADDINGS,
            $this->getSize(),
            PrelineClassMap::TABLE_CELL_PADDINGS['md'],
        );
    }

    public function getThClasses(): string
    {
        return $this->classes($this->getCellPaddingClasses(), 'font-semibold text-muted-foreground-2 uppercase tracking-wider text-start');
    }

    public function getTdClasses(): string
    {
        return $this->classes($this->getCellPaddingClasses(), 'text-foreground align-middle');
    }

    public function getRowClasses(int $index): string
    {
        return $this->classes(
            'transition-colors',
            'striped' === $this->getStyle()
                ? (0 === $index % 2 ? 'bg-card' : 'bg-background-1')
                : 'bg-card',
            $this->isHover() ? 'hover:bg-muted-hover' : null,
        );
    }

    /**
     * Normalize items with safe fallbacks.
     *
     * @return list<array{
     *     title: string,
     *     meta: string,
     *     content: string,
     *     badge: string,
     *     image_url: ?string,
     *     image_focus_style: ?string,
     *     link: ?string,
     *     link_title: string
     * }>
     */
    public function getNormalizedItems(): array
    {
        $normalized = [];

        foreach ($this->items('items') as $item) {
            $title = $this->itemString($item, 'title', '', trim: false);
            $meta = $this->itemString($item, 'meta', '', trim: false);
            $content = $this->itemString($item, 'content', '', trim: false);
            $badge = $this->itemString($item, 'badge', '', trim: false);

            $imageUrl = isset($item['image']) ? PropsResolver::resolveImageUrl($item['image']) : null;
            $imageFocusStyle = isset($item['image']) ? PropsResolver::resolveFocusPointStyle($item['image']) : null;
            $link = isset($item['link']) ? PropsResolver::resolveUrl($item['link']) : null;
            if ('#' === $link || '' === $link) {
                $link = null;
            }

            $linkTitle = $this->itemString($item, 'link_title', 'View', trim: false);

            $normalized[] = [
                'title' => $title,
                'meta' => $meta,
                'content' => $content,
                'badge' => $badge,
                'image_url' => $imageUrl,
                'image_focus_style' => $imageFocusStyle,
                'link' => $link,
                'link_title' => $linkTitle,
            ];
        }

        return $normalized;
    }
}
