# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-10-17

### Added
- **Ownership of `general` Styling Tab (`config/forms/general/*.xml` & `config/forms/fields/*.xml`)**: Added 9 `<section name="general">` variants (`general.xml`, `general_justify.xml`, `general_media.xml`, `general_collection.xml`, `general_slider.xml`, `general_code.xml`, `general_map.xml`, `general_divider.xml`, `general_countdown.xml`) and 10 styling field fragments (`margin.xml`, `maxwidth.xml`, `block_align.xml`, `text_align.xml`, `text_align_justify.xml`, `position.xml`, `animation.xml`, `blend.xml`, `visibility.xml`, `container_padding_remove.xml`) included via native `xi:include` across all 31 top-level block XMLs.
- **Explicit Column Child Block List (`column_item.xml`)**: Replaced `sulu_block_suite.auto_blocks` with explicit Sulu-native `<type ref="..."/>` references in `config/templates/block-items/column_item.xml`.
- **`SectionFlattener` (`NestedBlockFlattenerInterface`)**: Added `Totoglu\SuluPrelineBlocksBundle\Transformer\SectionFlattener` to unwrap nested `section > rows > columns > blocks` hierarchies when `reusable` blocks are expanded inside columns.
- **Prioritized Template Namespace**: `SuluPrelineBlocksExtension` now prepends `'@SuluPrelineBlocks/blocks' => 200` to `sulu_block_suite.rendering.template_namespaces`.
- **Schema-Driven Hotspot Picker Options (`popover_item.xml`)**: Added explicit `<param>` collections (`types`, `colors`, `triggers`) to the `hotspot_picker` property in `config/templates/block-items/popover_item.xml`.
- **`PrelineComponent` Vocabulary & Capabilities**: Implemented `PrelineComponent::dataKeys()` and `PrelineComponent::capabilityMap()` on top of `AbstractBlockComponent`.
- **Integration Test Coverage**: Added `PrelineDynamicAndSearchIntegrationTest` and `SectionFlattenerTest`.

### Changed
- **Unified Core Attribute & Security Pipeline**: `ElementTransform`, `PropsResolver`, `PrelineClassMap`, and `PrelineComponent` now consume Core's `ClassList`, `PropReader`, `AttributeSanitizer`, `CssScoper`, and precomputed `_id`, `_class`, `_attributes`, `_scoped_css`, `<field>_url`, `<field>_srcset`, and `<field>_focus_style` values.
- **Auto-Section Wrapping Flag**: Standardized `blocks_renderer.html.twig` on Core's `disable_auto_section` context flag and removed redundant `hydrate_blocks` calls.

### Removed
- Removed duplicate attribute/CSS sanitization and media/link URL derivation logic from `PropsResolver` and `PrelineBlockTransformer`.
- Removed dead duplicate field XML fragments from `config/templates/fields/` that were superseded by `config/forms/fields/`.
- Removed dead `sulu_block_suite.block_*` and `sulu_block_suite.opt_soft` translation keys from `translations/admin.*.json`.
- Removed redundant `'%kernel.project_dir%/templates' => null` Twig path prepend from `SuluPrelineBlocksExtension`.

## [1.0.0] - 2026-10-15

### Added
- Complete library of 31 top-level Sulu CMS block XML definitions (`config/templates/blocks/*.xml`), 16 nested block-item definitions (`config/templates/block-items/*.xml`), and reusable field fragments (`config/templates/fields/*.xml`).
- Native `<section name="content">` and `<section name="settings">` tab structure integrated with `trk/sulu-block-suite-bundle`.
- 35 Symfony UX Twig Components (`src/Twig/Components/*` & `templates/components/*.html.twig`) built for Preline UI 5 and Tailwind CSS 4.
- Decoupled Sulu block adapter templates (`templates/blocks/*.html.twig`) and `blocks_renderer.html.twig` preserving outer Twig context across auto-section boundaries for Dynamic Content resolution.
- Scoped `.el-element` CSS and XSS-safe custom attribute support via `ElementTransform`.
- Full 6-language internationalization (`en`, `tr`, `de`, `ru`, `es`, `fr`) across all XML schemas and UI labels.
- 100% XSD schema validation test coverage against Sulu `template-1.0.xsd` and `properties-1.0.xsd`.
