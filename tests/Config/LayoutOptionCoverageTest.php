<?php

declare(strict_types=1);

namespace Totoglu\SuluPrelineBlocksBundle\Tests\Config;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Totoglu\SuluPrelineBlocksBundle\Layout\ResponsiveWidth;
use Totoglu\SuluPrelineBlocksBundle\Twig\Components\PrelineClassMap;

/**
 * Guards the contract between admin XML options and the rendering tables: every option an editor
 * can select must resolve to an entry in the single-source class / layout maps.
 */
final class LayoutOptionCoverageTest extends TestCase
{
    private const TEMPLATES = __DIR__ . '/../../config/templates/';

    /**
     * @return iterable<string, array{string, string, list<string>}>
     */
    public static function optionProvider(): iterable
    {
        $bundle = self::TEMPLATES;
        $suite = __DIR__ . '/../../../sulu-block-suite-bundle/config/forms/';

        yield 'section max_width' => [$bundle . 'blocks/section.xml', 'max_width', \array_keys(PrelineClassMap::SECTION_MAX_WIDTHS)];
        yield 'block max_width' => [$suite . 'block_suite_general.xml', 'max_width', \array_keys(PrelineClassMap::BLOCK_MAX_WIDTHS)];
        yield 'row layout' => [$bundle . 'block-items/row_item.xml', 'layout', [...\array_keys(PrelineClassMap::ROW_LAYOUT_SPANS), 'auto']];
        yield 'column col_span' => [$bundle . 'block-items/column_item.xml', 'col_span', [...\array_map('strval', \array_keys(PrelineClassMap::COLUMN_SPAN_PRESETS)), 'auto']];
        yield 'column order' => [$bundle . 'block-items/column_item.xml', 'order', ['default', ...\array_keys(PrelineClassMap::COLUMN_ORDERS['md'])]];
        yield 'card media_width' => [$bundle . 'blocks/card.xml', 'media_width', \array_keys(PrelineClassMap::CARD_MEDIA_WIDTHS)];
        yield 'panel slider columns' => [$bundle . 'blocks/panel_slider.xml', 'columns', \array_map('strval', \array_keys(PrelineClassMap::SLIDER_SLIDES_PER_VIEW))];
        yield 'overlay slider columns' => [$bundle . 'blocks/overlay_slider.xml', 'columns', \array_map('strval', \array_keys(PrelineClassMap::SLIDER_SLIDES_PER_VIEW))];
        yield 'panel slider height' => [$bundle . 'blocks/panel_slider.xml', 'height', \array_keys(PrelineClassMap::CAROUSEL_HEIGHTS)];
        yield 'overlay slider height' => [$bundle . 'blocks/overlay_slider.xml', 'height', \array_keys(PrelineClassMap::CAROUSEL_HEIGHTS)];
        yield 'slideshow height' => [$bundle . 'blocks/slideshow.xml', 'height', \array_keys(PrelineClassMap::CAROUSEL_HEIGHTS)];
        yield 'slideshow overlay tint' => [$bundle . 'block-items/slideshow_item.xml', 'overlay_style', \array_keys(PrelineClassMap::SLIDESHOW_OVERLAY_TINTS)];
        yield 'media object_fit' => [$bundle . 'fields/media_display.xml', 'object_fit', \array_keys(PrelineClassMap::OBJECT_FIT)];
    }

    /**
     * @param list<string> $supported
     */
    #[DataProvider('optionProvider')]
    public function testEveryEditorOptionIsSupported(string $file, string $property, array $supported): void
    {
        $options = self::optionValues($file, $property);

        self::assertNotEmpty($options, \sprintf('No options found for "%s" in %s.', $property, \basename($file)));
        self::assertSame([], \array_values(\array_diff($options, $supported)), \sprintf('Unsupported "%s" options in %s.', $property, \basename($file)));
    }

    public function testEveryAspectRatioOptionResolves(): void
    {
        foreach (['fields/media_display.xml', 'includes/media.xml', 'blocks/video.xml'] as $file) {
            foreach (self::optionValues(self::TEMPLATES . $file, 'aspect_ratio') as $option) {
                self::assertNotNull(PrelineClassMap::aspectRatio($option), \sprintf('Aspect ratio "%s" from %s does not resolve.', $option, $file));
            }
        }
    }

    public function testBreakpointKeyedTablesCoverEveryBreakpointTheyAreUsedWith(): void
    {
        $tables = [PrelineClassMap::GRID_COLUMNS, PrelineClassMap::MASONRY_COLUMNS, PrelineClassMap::COLUMN_SPANS, PrelineClassMap::SLIDE_WIDTHS];
        foreach ($tables as $table) {
            foreach (\array_keys(ResponsiveWidth::BREAKPOINTS) as $breakpoint) {
                self::assertArrayHasKey($breakpoint, $table);
            }
        }

        foreach (PrelineClassMap::SLIDER_SLIDES_PER_VIEW as $perView) {
            foreach ($perView as $count) {
                self::assertArrayHasKey($count, PrelineClassMap::SLIDE_WIDTHS['base']);
            }
        }

        foreach ([...PrelineClassMap::ROW_LAYOUT_SPANS, [...\array_values(PrelineClassMap::COLUMN_SPAN_PRESETS)]] as $patterns) {
            foreach ($patterns as $spans) {
                foreach ($spans as $span) {
                    self::assertArrayHasKey($span, PrelineClassMap::COLUMN_SPANS['base']);
                }
            }
        }
    }

    public function testCardMediaClassesMatchTheirBreakpoint(): void
    {
        $prefix = PrelineClassMap::CARD_HORIZONTAL_BREAKPOINT . ':';

        foreach (PrelineClassMap::CARD_MEDIA_WIDTHS as $preset) {
            self::assertStringStartsWith($prefix, $preset['media']);
            self::assertStringStartsWith($prefix, $preset['content']);
        }
        foreach (PrelineClassMap::CARD_HORIZONTAL_DIRECTIONS as $direction) {
            self::assertStringContainsString($prefix, $direction);
        }
    }

    /**
     * @return list<string>
     */
    private static function optionValues(string $file, string $property): array
    {
        $dom = new \DOMDocument();
        self::assertTrue($dom->load($file), \sprintf('Failed to load %s.', $file));
        $dom->xinclude();

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('s', 'http://schemas.sulu.io/template/template');

        $values = [];
        $nodes = $xpath->query(\sprintf('//s:property[@name="%s"]/s:params/s:param[@name="values"]/s:param', $property));
        foreach ($nodes ?: [] as $node) {
            if ($node instanceof \DOMElement) {
                $values[] = $node->getAttribute('name');
            }
        }

        return \array_values(\array_unique($values));
    }
}
