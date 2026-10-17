<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Twig\Components;

use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Accordion;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Card;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Column;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Gallery;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Grid;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Image;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Overlay;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\OverlaySlider;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PanelSlider;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Row;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Section;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\Switcher;

/**
 * Verifies that the layout chain Section → Row → Column → Grid / Gallery / Slider → Card / Overlay / Image
 * derives every width (classes, `sizes`, intrinsic dimensions) from the same breakpoint data.
 */
final class ResponsiveImageSizingTest extends TestCase
{
    private const COLUMN_8_OF_12 = '(min-width: 1360px) 907px, (min-width: 768px) 66.67vw, 100vw';

    public function testSectionRowColumnImage(): void
    {
        $column = $this->column('thirds-2-1', 0);

        self::assertSame('col-span-12 md:col-span-8', $column->getColSpanClasses());

        $image = new Image();
        $image->load(block: $column->getNormalizedBlocks()[0]);

        self::assertSame(self::COLUMN_8_OF_12, $image->getImageSizes());
        // The figure only fills its slot: no implicit max width or centering.
        self::assertStringNotContainsString('max-w-', $image->getWrapperClasses());
        self::assertStringNotContainsString('mx-auto', $image->getWrapperClasses());

        // Editor-chosen max width + alignment drive both the classes and the image size.
        $image->mergeProps(['max_width' => 'medium', 'block_align' => 'right']);
        self::assertStringContainsString('max-w-md', $image->getWrapperClasses());
        self::assertStringContainsString('ml-auto', $image->getWrapperClasses());
        self::assertSame('(min-width: 448px) 448px, 100vw', $image->getImageSizes());
    }

    public function testRowLayoutsProduceConsistentSpansAndSlots(): void
    {
        $row = new Row();
        $row->load(props: ['layout' => 'quarters', 'columns' => [['blocks' => []], ['blocks' => []]]]);

        $columns = $row->getNormalizedColumns();
        self::assertSame(['base' => 12, 'sm' => 6, 'lg' => 3], $columns[0]['_spans']);

        $column = new Column();
        $column->load(props: $columns[0]);
        self::assertSame('col-span-12 sm:col-span-6 lg:col-span-3', $column->getColSpanClasses());
        self::assertSame('(min-width: 1360px) 340px, (min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw', $column->getSlotWidth()->toSizes());

        // An explicit column span overrides the row pattern.
        $row->load(props: ['layout' => 'halves', 'columns' => [['col_span' => '4', 'blocks' => []]]]);
        self::assertSame(['base' => 12, 'md' => 4], $row->getNormalizedColumns()[0]['_spans']);
    }

    public function testColumnOrderOnlyAppliesWhileStacked(): void
    {
        $row = new Row();
        $row->load(props: ['layout' => 'halves', 'columns' => [
            ['order' => 'last', 'blocks' => [['type' => 'text', 'content' => 'A']]],
            ['blocks' => [['type' => 'text', 'content' => 'B']]],
        ]]);

        $column = new Column();
        $column->load(props: $row->getNormalizedColumns()[0]);

        // Halves place columns side by side from `sm`, so the reorder must end at `sm`, not `md`.
        self::assertSame('order-last sm:order-none', $column->getOrderClasses());
    }

    public function testCardMediaTopVersusSide(): void
    {
        $column = $this->column('halves', 0);
        $slot = $column->getChildContext();

        $top = new Card();
        $top->load(block: ['title' => 'Top', 'image' => ['url' => '/a.jpg'], 'media_align' => 'top'], context: $slot);
        self::assertSame('(min-width: 1360px) 680px, (min-width: 640px) 50vw, 100vw', $top->getImageSizes());

        $side = new Card();
        $side->load(block: ['title' => 'Side', 'image' => ['url' => '/a.jpg'], 'media_align' => 'left', 'media_width' => '1-3'], context: $slot);
        self::assertSame('md:w-1/3', $side->getMediaGridWidthClasses());
        self::assertSame(
            '(min-width: 1360px) 227px, (min-width: 768px) 16.67vw, (min-width: 640px) 50vw, 100vw',
            $side->getImageSizes(),
        );
    }

    public function testGridCardsAndGalleryOverlaysInsideAColumn(): void
    {
        $column = $this->column('thirds-2-1', 0);

        $grid = new Grid();
        $grid->load(
            block: ['items' => [['title' => 'A', 'image' => ['url' => '/a.jpg']]], 'columns_md' => '2', 'columns_lg' => '3', 'media_align' => 'left'],
            context: $column->getChildContext(),
        );
        self::assertSame('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6', $grid->getContainerClasses());

        $card = new Card();
        $card->load(data: ['title' => 'A', 'image' => ['url' => '/a.jpg']], props: $grid->resolveItemProps(['title' => 'A', 'props' => ['media_width' => '1-2']]));
        self::assertSame(
            '(min-width: 1360px) 152px, (min-width: 1024px) 11.11vw, (min-width: 768px) 16.67vw, 100vw',
            $card->getImageSizes(),
        );

        $gallery = new Gallery();
        $gallery->load(
            block: ['items' => [['title' => 'G', 'image' => ['url' => '/g.jpg']]], 'columns_default' => '2', 'columns_lg' => '4'],
            context: $column->getChildContext(),
        );
        self::assertSame('grid grid-cols-2 lg:grid-cols-4 gap-6', $gallery->getContainerClasses());

        $overlay = new Overlay();
        $overlay->load(data: ['title' => 'G', 'image' => ['url' => '/g.jpg']], props: $gallery->resolveItemProps(['title' => 'G']));
        self::assertSame(
            '(min-width: 1360px) 227px, (min-width: 1024px) 16.67vw, (min-width: 768px) 33.33vw, 50vw',
            $overlay->getImageSizes(),
        );
    }

    public function testCardGroupItemsShareTheRowEqually(): void
    {
        $grid = new Grid();
        $grid->load(block: ['items' => [['title' => 'A'], ['title' => 'B'], ['title' => 'C'], ['title' => 'D']], 'layout_type' => 'card_group']);

        self::assertSame('(min-width: 1360px) 340px, (min-width: 640px) 25vw, 100vw', $grid->getItemSlotWidth()->toSizes());
    }

    public function testSliderSlidesMatchPrelineSlidesPerView(): void
    {
        $panel = new PanelSlider();
        $panel->load(block: ['items' => [['title' => 'S', 'image' => ['url' => '/s.jpg']]], 'columns' => '4']);

        self::assertSame('w-full sm:w-1/2 md:w-1/3 lg:w-1/4', $panel->getSlideWidthClass());
        self::assertStringContainsString('"slidesQty":{"xs":1,"sm":2,"md":3,"lg":4,"xl":4,"2xl":4}', $panel->getCarouselConfigJson());

        $card = new Card();
        $card->load(block: $panel->getNormalizedItems()[0]);
        self::assertSame(
            '(min-width: 1360px) 340px, (min-width: 1024px) 25vw, (min-width: 768px) 33.33vw, (min-width: 640px) 50vw, 100vw',
            $card->getImageSizes(),
        );

        $overlaySlider = new OverlaySlider();
        $overlaySlider->load(block: ['items' => [['title' => 'O', 'image' => ['url' => '/o.jpg']]], 'columns' => '2', 'height' => 'large']);
        self::assertSame('min-h-[550px] sm:min-h-[650px]', $overlaySlider->getHeightClass());
        self::assertSame('w-full sm:w-1/2', $overlaySlider->getSlideWidthClass());
    }

    public function testManualAspectRatioIsARatioNotAPixelCap(): void
    {
        $image = new Image();
        $image->load(block: [
            'image' => ['url' => '/m.jpg'],
            'aspect_ratio' => 'manual',
            'aspect_ratio_width' => 16,
            'aspect_ratio_height' => 9,
        ]);

        self::assertSame('aspect-ratio: 16 / 9;', $image->getMediaAspectRatioStyle());
        // The slot is not shrunk to "16px": sizing still follows the layout.
        self::assertSame('(min-width: 1360px) 1360px, 100vw', $image->getImageSizes());
        self::assertSame(['width' => 1360, 'height' => 765], $image->getDimensions());
        self::assertStringContainsString('h-full', $image->getImgClasses());

        $image->mergeProps(['aspect_ratio' => 'auto']);
        self::assertNull($image->getDimensions());
        self::assertStringContainsString('h-auto', $image->getImgClasses());
    }

    public function testPresetAspectRatioDrivesIntrinsicDimensions(): void
    {
        $card = new Card();
        $card->load(block: ['title' => 'C', 'image' => ['url' => '/c.jpg'], 'aspect_ratio' => '4:3'], context: $this->column('halves', 0)->getChildContext());

        self::assertSame('aspect-4/3', $card->getMediaAspectRatioClass());
        self::assertSame(['width' => 680, 'height' => 510], $card->getIntrinsicImageDimensions());
    }

    public function testAccordionAndSwitcherImagesFollowTheirPanels(): void
    {
        $accordion = new Accordion();
        $accordion->load(block: ['items' => [['title' => 'Q', 'content' => 'A']], 'image_align' => 'left'], context: $this->column('halves', 0)->getChildContext());

        self::assertSame('(min-width: 1360px) 227px, (min-width: 640px) 16.67vw, 100vw', $accordion->getImageSizes());
        self::assertSame('relative w-full overflow-hidden aspect-video', $accordion->getMediaFrameClasses());

        $switcher = new Switcher();
        $switcher->load(block: ['items' => [['title' => 'T', 'content' => 'C']], 'nav_position' => 'left', 'aspect_ratio' => '1/1']);

        self::assertSame('(min-width: 1360px) 907px, (min-width: 768px) 66.67vw, 100vw', $switcher->getImageSizes());
        self::assertStringContainsString('aspect-square', $switcher->getMediaFrameClasses());
    }

    public function testFullWidthSectionPropagatesViewportRelativeSlots(): void
    {
        $section = new Section();
        $section->load(props: ['max_width' => 'full', 'rows' => [['layout' => 'halves', 'columns' => [['blocks' => []]]]]]);

        self::assertStringContainsString('max-w-full', $section->getContainerClasses());

        $row = new Row();
        $row->load(props: $section->getNormalizedRows()[0]);
        $column = new Column();
        $column->load(props: $row->getNormalizedColumns()[0]);

        self::assertSame('(min-width: 640px) 50vw, 100vw', $column->getSlotWidth()->toSizes());
    }

    private function column(string $layout, int $index): Column
    {
        $section = new Section();
        $section->load(props: [
            'max_width' => '85rem',
            'rows' => [[
                'layout' => $layout,
                'columns' => [
                    ['blocks' => [['type' => 'image', 'image' => ['url' => '/first.jpg']]]],
                    ['blocks' => [['type' => 'image', 'image' => ['url' => '/second.jpg']]]],
                ],
            ]],
        ]);

        $row = new Row();
        $row->load(props: $section->getNormalizedRows()[0], context: $section->getChildContext());

        $column = new Column();
        $column->load(props: $row->getNormalizedColumns()[$index]);

        return $column;
    }
}
