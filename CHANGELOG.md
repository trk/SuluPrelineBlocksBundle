# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.1.0] - 2026-10-18

### Added
- **Complete Preline UI 5 & Tailwind CSS 4 Block Library**: 31 top-level Sulu CMS block XML definitions (`config/templates/blocks/*.xml`), 16 nested block-item definitions (`config/templates/block-items/*.xml`), and 12 reusable content/settings field fragments (`config/templates/fields/*.xml` plus `config/templates/includes/media.xml`).
- **Native `xi:include` `general` Styling Tab (`config/forms/general/*.xml` & `config/forms/fields/*.xml`)**: 9 `<section name="general">` variants (`general.xml`, `general_justify.xml`, `general_media.xml`, `general_collection.xml`, `general_slider.xml`, `general_code.xml`, `general_map.xml`, `general_divider.xml`, `general_countdown.xml`) and 10 styling field fragments (`margin.xml`, `maxwidth.xml`, `block_align.xml`, `text_align.xml`, `text_align_justify.xml`, `position.xml`, `animation.xml`, `blend.xml`, `visibility.xml`, `container_padding_remove.xml`).
- **Explicit Column Child Block List (`column_item.xml`)**: Explicit Sulu-native `<type ref="..."/>` references in `config/templates/block-items/column_item.xml`.
- **`SectionFlattener` (`NestedBlockFlattenerInterface`)**: `Totoglu\SuluPrelineBlocksBundle\Transformer\SectionFlattener` to unwrap nested `section > rows > columns > blocks` hierarchies when `reusable` blocks are expanded inside columns.
- **35 Symfony UX Twig Components (`src/Twig/Components/*` & `templates/components/*.html.twig`)**: Built on `PrelineComponent` (`AbstractBlockComponent`) consuming Core's `ClassList`, `PropReader`, `AttributeSanitizer`, `CssScoper`, and precomputed `_id`, `_class`, `_attributes`, `_scoped_css`, `<field>_url`, `<field>_srcset`, and `<field>_focus_style` values.
- **Decoupled Sulu Block Adapter Templates (`templates/blocks/*.html.twig`) & `blocks_renderer.html.twig`**: Prioritized `@SuluPrelineBlocks/blocks` (`priority: 200`) namespace registration and auto-section wrapping with `disable_auto_section` context flag.
- **Schema-Driven Hotspot Picker Options (`popover_item.xml`)**: Explicit `<param>` collections (`types`, `colors`, `triggers`) on the `hotspot_picker` property in `config/templates/block-items/popover_item.xml`.
- **Starter Project Templates & Visual Presets (`docs/examples/`)**: Ready-to-copy `includes/content_block_types.xml`, `snippets/{page_layout,reusable_block,block_preset}.xml`, and 16 visual preset YAML files (`presets/*.yaml`), automatically scaffolded via `php bin/console sulu:block-suite:init`.
- **Full 6-Language Internationalization (`en`, `tr`, `de`, `ru`, `es`, `fr`)**: Complete XML metadata translations and 100% XSD schema validation test coverage (`XmlSchemaValidationTest`, `PrelinePageReindexAndDynamicContentTest`, `SectionFlattenerTest`).
