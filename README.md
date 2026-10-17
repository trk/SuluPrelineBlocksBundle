# Sulu Preline Blocks Bundle

[![Latest Version](https://img.shields.io/badge/version-2.0.0-blue.svg)](https://github.com/trk/SuluPrelineBlocksBundle)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2%20%7C%208.5-8892BF.svg)](https://php.net)
[![Sulu CMS](https://img.shields.io/badge/Sulu%20CMS-%5E3.0-orange.svg)](https://sulu.io)
[![Symfony](https://img.shields.io/badge/Symfony-%5E7.4-black.svg)](https://symfony.com)
[![Preline UI](https://img.shields.io/badge/Preline%20UI-v5.0-blueviolet.svg)](https://preline.co)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.x-38bdf8.svg)](https://tailwindcss.com)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

An enterprise-grade, accessible, and fully customizable **Preline UI 5 & Tailwind CSS 4** block and component library bundle for **Sulu CMS 3.x**, powered by **Symfony UX Twig Components** and **`trk/sulu-block-suite-bundle`**.

---

## Table of Contents

- [Overview & Philosophy](#overview--philosophy)
- [Key Features](#key-features)
- [Included Blocks, Block Items & XML Fragments](#included-blocks-block-items--xml-fragments)
- [Requirements](#requirements)
- [Installation](#installation)
- [Architecture & Override Mechanics](#architecture--override-mechanics)
- [Scoped CSS & Custom Attributes](#scoped-css--custom-attributes)
- [Sulu Live Preview Integration](#sulu-live-preview-integration)
- [Testing & Quality Assurance](#testing--quality-assurance)
- [License](#license)

---

## Overview & Philosophy

`SuluPrelineBlocksBundle` provides a complete library of **31 top-level Sulu CMS blocks, 16 nested block-items, 9 `general` styling section variants, 10 styling field fragments, and 15 content/settings field fragments** alongside **35 Symfony UX Twig Components** (including `Container`) built with **Tailwind CSS 4** and **Preline UI 5**, engineered with strict separation of concerns:

1. **100% Native Sulu XML Block Definitions (`config/templates/blocks/*.xml`, `config/templates/block-items/*.xml`, `config/templates/fields/*.xml`, `config/forms/general/*.xml`, and `config/forms/fields/*.xml`)**:
   - Every block defines its editorial fields in `<section name="content">` and its block-specific options in `<section name="settings">`.
   - Each block explicitly includes its own `<section name="general">` styling variant (`general.xml`, `general_justify.xml`, `general_media.xml`, `general_collection.xml`, `general_slider.xml`, `general_code.xml`, `general_map.xml`, `general_divider.xml`, or `general_countdown.xml`) via standard `xi:include` from `config/forms/general/*.xml`.
2. **Compile-Time Shared Tab Inheritance**: Through `trk/sulu-block-suite-bundle`, every block compiles with inline `Content`, `Settings`, `General`, `Dynamic Content` (for data-driven blocks), and `Advanced` tabs in Sulu Admin (`FormMetadata`), with all non-`content` fields automatically namespaced under `props/*`.
3. **Explicit Sulu-Native `<type ref="..."/>` Composition**:
   - `config/templates/block-items/column_item.xml` declares an explicit `<types><type ref="..."/></types>` list of allowed column child blocks (excluding layout-level blocks like `section` and `breadcrumbs`).
   - `config/templates/block-items/popover_item.xml` configures the `hotspot_picker` field via explicit XML `<params>` (`types`, `colors`, and `triggers`).
4. **Prioritized Template Namespace & Nested Container Flattening**:
   - `SuluPrelineBlocksExtension` prepends `'@SuluPrelineBlocks/blocks' => 200` into `sulu_block_suite.rendering.template_namespaces` (between Core's `@SuluBlockSuite/blocks: 0` and the host project's `blocks: 1000`).
   - `SectionFlattener` implements `NestedBlockFlattenerInterface` to unwrap `section > rows > columns > blocks` hierarchies into flat leaf blocks when a `reusable` block is rendered inside a column.
5. **Decoupled Symfony UX Twig Components (`src/Twig/Components/` & `templates/components/`)**: Type-safe PHP component classes and accessible Preline UI 5 templates. Sulu block templates (`templates/blocks/*.html.twig`) act purely as thin data adapters forwarding block properties and `props` to Twig Components.

---

## Key Features

- **31 Top-Level Blocks, 16 Block Items & 35 Twig Components**:
  - Layout containers (`section` + `Container`, `row_item`, `column_item`)
  - Typography & content (`headline`, `text`, `quotation`, `code`, `html`, `divider`)
  - Cards & collections (`card`, `grid`, `list`, `description_list`, `table`)
  - Media & sliders (`image`, `video`, `gallery`, `slideshow`, `overlay`, `overlay_slider`, `panel_slider`)
  - Interactive & navigation (`accordion`, `switcher`, `popover`, `button`, `alert`, `nav`, `subnav`, `breadcrumbs`, `icon`, `countdown`, `map`, `scroll_to`)
- **Native `xi:include` General Styling Sections (`config/forms/general/*.xml`)**: Each of the 31 top-level blocks includes its tailored `general` section (`margin`, `maxwidth`, `block_align`, `text_align` / `text_align_justify`, `position`, `animation`, `blend`, `visibility`, `container_padding_remove`).
- **Explicit Column Child Block List (`column_item.xml`)**: Uses native `<type ref="..."/>` references for deterministic column block selection.
- **Schema-Driven Hotspot Picker (`popover_item.xml`)**: Configures marker `types` (`pulse`, `plus`, `number`, `dot`, `pin`, `icon`), `colors` (`primary`, `dark`, `light`, `accent`, `success`, `danger`), and `triggers` (`click`, `hover`) directly via XML `<param>` collections.
- **`SectionFlattener` (`NestedBlockFlattenerInterface`)**: Automatically unwraps nested `section` containers when reusable blocks are expanded inside columns.
- **Scoped `.el-element` CSS & Safe Custom Attributes**: Consumes Core's precomputed `_id`, `_class`, `_attributes`, and `_scoped_css` as well as metadata-derived `<field>_url`, `<field>_srcset`, and `<field>_focus_style`.
- **Complete 6-Language Translations**: 100% translated XML metadata labels, info texts, and select options for `en`, `tr`, `de`, `ru`, `es`, and `fr`.

---

## Included Blocks, Block Items & XML Fragments

| Category | Top-Level Blocks (31) | Nested Block Items (16) |
| --- | --- | --- |
| **Layout Containers** | `section` | `row_item`, `column_item` |
| **Typography & Content** | `headline`, `text`, `quotation`, `code`, `html`, `divider` | — |
| **Cards & Collections** | `card`, `grid`, `list`, `description_list`, `table` | `card`, `list_item`, `description_list_item`, `table_item` |
| **Media & Sliders** | `image`, `video`, `gallery`, `slideshow`, `overlay`, `overlay_slider`, `panel_slider` | `slideshow_item`, `overlay` |
| **Interactive & Navigation** | `accordion`, `switcher`, `popover`, `button`, `alert`, `nav`, `subnav`, `breadcrumbs`, `icon`, `countdown`, `map`, `scroll_to` | `accordion_item`, `switcher_item`, `popover_item`, `button`, `badge`, `nav_item`, `subnav_item`, `map_item` |

### Styling & Content XML Fragments

- **General Tab Variants (`config/forms/general/*.xml`)**: `general.xml`, `general_justify.xml`, `general_media.xml`, `general_collection.xml`, `general_slider.xml`, `general_code.xml`, `general_map.xml`, `general_divider.xml`, `general_countdown.xml`.
- **General Styling Fields (`config/forms/fields/*.xml`)**: `margin.xml`, `maxwidth.xml`, `block_align.xml`, `text_align.xml`, `text_align_justify.xml`, `position.xml`, `animation.xml`, `blend.xml`, `visibility.xml`, `container_padding_remove.xml`.
- **Content & Settings Fields (`config/templates/fields/*.xml` & `includes/media.xml`)**: `title.xml`, `meta.xml`, `content.xml`, `image.xml`, `hover_image.xml`, `video.xml`, `hover_video.xml`, `media.xml`, `media_display.xml`, `image_loading.xml`, `icon.xml`, `badge.xml`, `link.xml`, `link_title.xml`, `padding.xml`.

---

## Requirements

- **PHP**: `>= 8.2` (Fully tested on PHP 8.5 with `declare(strict_types=1);`)
- **Sulu CMS**: `~3.0.10`
- **Symfony**: `^7.4`
- **Preline UI**: `>= 5.0`
- **Tailwind CSS**: `>= 4.0`
- **`trk/sulu-block-suite-bundle`**: `^2.0`

---

## Installation

### 1. Composer Package

```bash
composer require trk/sulu-preline-blocks-bundle
```

### 2. Register the Bundles

```php
// config/bundles.php
return [
    // ...
    Totoglu\SuluBlockSuiteBundle\SuluBlockSuiteBundle::class => ['all' => true],
    Totoglu\SuluPrelineBlocksBundle\SuluPrelineBlocksBundle::class => ['all' => true],
];
```

### 3. Tailwind CSS 4 Configuration

In your website stylesheet (e.g. `assets/website/css/app.css`):

```css
@import "tailwindcss";
@import "preline/variants.css";

@source "../node_modules/preline/dist/*.js";
@source "../../templates/**/*.twig";
@source "../../src/Twig/Components/**/*.php";
@source "../../packages/sulu-preline-blocks-bundle/templates/**/*.twig";
@source "../../packages/sulu-preline-blocks-bundle/src/Twig/Components/**/*.php";
```

---

## Architecture & Override Mechanics

- **`PrelineComponent` Base Class & `PrelineClassMap` Registry**: All 35 Twig component PHP classes (including `Container`) extend `PrelineComponent` (which extends `AbstractBlockComponent` from `trk/sulu-block-suite-bundle` and overrides `dataKeys()` and `capabilityMap()`). Shared Tailwind CSS 4 / Preline UI 5 token maps are centralized in `PrelineClassMap` and `PropsResolver`, built on Core's `ClassList`, `PropReader`, `AttributeSanitizer`, and `CssScoper`.
- **Prioritized Template Namespace (`@SuluPrelineBlocks/blocks: 200`)**: `SuluPrelineBlocksExtension` prepends `'@SuluPrelineBlocks/blocks' => 200` into `sulu_block_suite.rendering.template_namespaces`. Project templates in `templates/blocks/{block}.html.twig` (`blocks: 1000`) automatically take precedence.
- **3-Layer Block DOM Structure (`div (block container) > div (block) > context`)**: Content blocks render a consistent 3-layer DOM hierarchy where Layer 1 (`getRootAttributes()` / `getWrapperClasses()`) handles universal outer positioning, margins, max-width, alignment, visibility, custom attributes, and ID; Layer 2 (`getBlockClasses()`) handles block-specific visual framing (backgrounds, borders, rounding, shadows, aspect ratios); and Layer 3 renders the inner semantic content and sub-components.
- **Sub-Component Composition**: Complex components (`Card`, `Overlay`, `Popover`, `Gallery`, `Slideshow`, `OverlaySlider`, `PanelSlider`, `ListBlock`, `DescriptionList`, `Table`, `Subnav`, `Switcher`, `Alert`) compose foundational `<twig:Badge>`, `<twig:Button>`, `<twig:Image :bare="true">`, and `<twig:Video :bare="true">` components as well as shared carousel/filter partials (`@SuluPrelineBlocks/includes/carousel_controls.html.twig`, `tag_filter_bar.html.twig`) instead of duplicating raw HTML markup.
- **Override Block XML**: Place `config/templates/blocks/{block}.xml` in your application to customize `<section name="content">`, `<section name="settings">`, or `<section name="general">`.
- **Define Project Presets**: Create `config/blocks/presets/{block}.yaml` in your application.
- **Override Twig Templates**: Place `templates/blocks/{block}.html.twig` or `templates/bundles/SuluPrelineBlocksBundle/components/{Component}.html.twig` in your application.

---

## Scoped CSS & Custom Attributes

Every Preline Twig Component (`Section`, `Container`, `Row`, `Column`, `Card`, `Headline`, `Button`, etc.) consumes Core's pre-resolved `_id`, `_class`, `_attributes`, and `_scoped_css` through `PrelineComponent::getRootAttributes()`, `ElementTransform`, and `BlockRenderer`:
- Root wrapper classes (`margin`, `padding`, `max_width`, `block_align`, `text_align`, `position`, `z_index`, `animation`, `blend`, `visibility`, `custom_class`), element `id`, parallax attributes (`data-parallax`), and custom HTML attributes (`data-*`, `aria-*`, `role`, etc.) from `props.attributes` are automatically resolved and merged onto the root element via `{{ attributes.defaults(this.getRootAttributes()) }}`.
- Custom CSS from `props.css` using `.el-element` (or bare CSS properties) is scoped to the component's unique `#id` and emitted as an inline `<style>` tag.

---

## Sulu Live Preview Integration

Whenever Sulu Live Preview updates page DOM via postMessage or partial reload, invoke:

```javascript
if (typeof window.HSStaticMethods !== 'undefined') {
    window.HSStaticMethods.autoInit();
}
```

---

## Testing & Quality Assurance

```bash
# Run PHPUnit 12 test suite (component unit tests, integration tests, and XML schema validation)
../../vendor/bin/phpunit packages/sulu-preline-blocks-bundle/tests

# Run PHPStan static analysis at level max (from project root)
../../vendor/bin/phpstan analyse --debug --memory-limit=1G packages/sulu-preline-blocks-bundle/src packages/sulu-preline-blocks-bundle/tests
```

---

## License

This bundle is open-source software licensed under the [MIT License](LICENSE).
