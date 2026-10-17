# Sulu Preline Blocks Bundle

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/trk/SuluPrelineBlocksBundle)
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
- [Included Blocks & Block Items (47 Block XMLs + 24 Field Fragments)](#included-blocks--block-items-47-block-xmls--24-field-fragments)
- [Requirements](#requirements)
- [Installation](#installation)
- [Architecture & Override Mechanics](#architecture--override-mechanics)
- [Scoped CSS & Custom Attributes](#scoped-css--custom-attributes)
- [Sulu Live Preview Integration](#sulu-live-preview-integration)
- [Testing & Quality Assurance](#testing--quality-assurance)
- [License](#license)

---

## Overview & Philosophy

`SuluPrelineBlocksBundle` provides a complete library of **31 top-level Sulu CMS blocks, 16 nested block-items, and 24 reusable field fragments** alongside **35 Symfony UX Twig Components** (including `Container`) built with **Tailwind CSS 4** and **Preline UI 5**, engineered with strict separation of concerns:

1. **100% Native Sulu XML Block Definitions (`config/templates/blocks/*.xml`, `config/templates/block-items/*.xml` & `config/templates/fields/*.xml`)**: Every block defines its editorial fields in `<section name="content">` and its visual/layout options directly in `<section name="settings">` using native Sulu field types (`single_select`, `checkbox`, `text_line`, `color`, `number`). No external YAML prop schemas are needed.
2. **Compile-Time Shared Tab Inheritance**: Through `trk/sulu-block-suite-bundle`, every block automatically compiles with inline `Content`, `Settings`, `General`, `Dynamic Content` (for data-driven blocks), and `Advanced` tabs in Sulu Admin (`FormMetadata`), with all non-content fields automatically namespaced under `props/*`.
3. **Zero Bundled Opinionated Presets**: Presets belong strictly to the host project (`config/blocks/presets/*.yaml`) or CMS editors (`block_preset` snippets managed via the `Advanced` tab).
4. **Decoupled Symfony UX Twig Components (`src/Twig/Components/` & `templates/components/`)**: Type-safe PHP component classes and accessible Preline UI 5 templates. Sulu block templates (`templates/blocks/*.html.twig`) act purely as thin data adapters forwarding block properties and `props` to Twig Components.

---

## Key Features

- **31 Top-Level Blocks, 16 Block Items, 24 Field Fragments & 35 Twig Components**:
  - Layout containers (`section` + `Container`, `row_item`, `column_item`)
  - Typography & content (`headline`, `text`, `quotation`, `code`, `html`, `divider`)
  - Cards & collections (`card`, `grid`, `list`, `description_list`, `table`)
  - Media & sliders (`image`, `video`, `gallery`, `slideshow`, `overlay`, `overlay_slider`, `panel_slider`)
  - Interactive & navigation (`accordion`, `switcher`, `popover`, `button`, `alert`, `nav`, `subnav`, `breadcrumbs`, `icon`, `countdown`, `map`, `scroll_to`)
- **Auto-Injected Column & Page Block Lists**: `column_item.xml` uses `<tag name="sulu_block_suite.auto_blocks" default="headline" exclude="section,row,column,row_item,column_item"/>`, automatically supporting all registered content blocks without hardcoded `<type ref="..."/>` lists.
- **Scoped `.el-element` CSS & Safe Custom Attributes**: `ElementTransform` and `Section`/`Container`/`Row`/`Column` components natively render `props.attributes` (`data-*`, `aria-*`) and scoped `.el-element` `<style>` blocks inside the root element.
- **Complete 6-Language Translations**: 100% translated XML metadata labels, info texts, and select options for `en`, `tr`, `de`, `ru`, `es`, and `fr`.

---

## Included Blocks & Block Items (47 Block XMLs + 24 Field Fragments)

| Category | Top-Level Blocks (31) | Nested Block Items (16) |
| --- | --- | --- |
| **Layout Containers** | `section` | `row_item`, `column_item` |
| **Typography & Content** | `headline`, `text`, `quotation`, `code`, `html`, `divider` | — |
| **Cards & Collections** | `card`, `grid`, `list`, `description_list`, `table` | `card`, `list_item`, `description_list_item`, `table_item` |
| **Media & Sliders** | `image`, `video`, `gallery`, `slideshow`, `overlay`, `overlay_slider`, `panel_slider` | `slideshow_item`, `overlay` |
| **Interactive & Navigation** | `accordion`, `switcher`, `popover`, `button`, `alert`, `nav`, `subnav`, `breadcrumbs`, `icon`, `countdown`, `map`, `scroll_to` | `accordion_item`, `switcher_item`, `popover_item`, `button`, `badge`, `nav_item`, `subnav_item`, `map_item` |

---

## Requirements

- **PHP**: `>= 8.2` (Fully tested on PHP 8.5 with `declare(strict_types=1);`)
- **Sulu CMS**: `~3.0.10`
- **Symfony**: `^7.4`
- **Preline UI**: `>= 5.0`
- **Tailwind CSS**: `>= 4.0`
- **`trk/sulu-block-suite-bundle`**: `^1.0`

---

## Installation

### 1. Composer Package

```bash
composer require trk/sulu-preline-blocks-bundle
```

### 2. Register the Bundle

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

- **`PrelineComponent` Base Class & `PrelineClassMap` Registry**: All 35 Twig component PHP classes (including `Container`) extend `PrelineComponent` (which extends `AbstractBlockComponent` from `trk/sulu-block-suite-bundle`). Shared Tailwind CSS 4 / Preline UI 5 token maps (colors, typography, badges, buttons, shadows, borders, radii, aspect ratios, grid columns, animations, blend modes) are centralized in `PrelineClassMap` and `PropsResolver`.
- **3-Layer Block DOM Structure (`div (block container) > div (block) > context`)**: Content blocks render a consistent 3-layer DOM hierarchy where Layer 1 (`getRootAttributes()` / `getWrapperClasses()`) handles universal outer positioning, margins, max-width, alignment, visibility, custom attributes, and ID; Layer 2 (`getBlockClasses()`) handles block-specific visual framing (backgrounds, borders, rounding, shadows, aspect ratios); and Layer 3 renders the inner semantic content and sub-components.
- **Sub-Component Composition**: Complex components (`Card`, `Overlay`, `Popover`, `Gallery`, `Slideshow`, `OverlaySlider`, `PanelSlider`, `ListBlock`, `DescriptionList`, `Table`, `Subnav`, `Switcher`, `Alert`) compose foundational `<twig:Badge>`, `<twig:Button>`, `<twig:Image :bare="true">`, and `<twig:Video :bare="true">` components as well as shared carousel/filter partials (`@SuluPrelineBlocks/includes/carousel_controls.html.twig`, `tag_filter_bar.html.twig`) instead of duplicating raw HTML markup.
- **Override Block XML**: Place `config/templates/blocks/{block}.xml` in your application to customize `<section name="content">`, `<section name="settings">`, or add custom tab sections (`<section name="style">`, `<section name="layout">`).
- **Define Project Presets**: Create `config/blocks/presets/{block}.yaml` in your application.
- **Override Twig Templates**: Place `templates/blocks/{block}.html.twig` or `templates/bundles/SuluPrelineBlocksBundle/components/{Component}.html.twig` in your application.

---

## Scoped CSS & Custom Attributes

Every Preline Twig Component (`Section`, `Container`, `Row`, `Column`, `Card`, `Headline`, `Button`, etc.) processes `props` through `PrelineComponent::getRootAttributes()`, `ElementTransform`, and `BlockRenderer`:
- Root wrapper classes (`margin`, `padding`, `max_width`, `block_align`, `text_align`, `position`, `z_index`, `animation`, `blend`, `visibility`, `custom_class`), element `id`, parallax attributes (`data-parallax`), and custom HTML attributes (`data-*`, `aria-*`, `role`, etc.) from `props.attributes` are automatically resolved and merged onto the root element via `{{ attributes.defaults(this.getRootAttributes()) }}`.
- Custom CSS from `props.css` using `.el-element` (or bare CSS properties) is automatically scoped to the component's unique `#id` and emitted as an inline `<style>` tag inside the component root element.

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
# Run PHPUnit 12 test suite (35/35 component unit tests + XML schema validation)
../../vendor/bin/phpunit

# Run PHPStan static analysis at level max (from project root)
../../vendor/bin/phpstan analyse packages/sulu-preline-blocks-bundle --debug --memory-limit=1G
```

---

## License

This bundle is open-source software licensed under the [MIT License](LICENSE).
