# Sulu Preline Blocks Bundle

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/trk/SuluPrelineBlocksBundle)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2%20%7C%208.5-8892BF.svg)](https://php.net)
[![Sulu CMS](https://img.shields.io/badge/Sulu%20CMS-%5E2.5%20%7C%20%5E3.0-orange.svg)](https://sulu.io)
[![Symfony](https://img.shields.io/badge/Symfony-%5E6.4%20%7C%20%5E7.0-black.svg)](https://symfony.com)
[![Preline UI](https://img.shields.io/badge/Preline%20UI-v5.0-blueviolet.svg)](https://preline.co)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.x-38bdf8.svg)](https://tailwindcss.com)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

An enterprise-grade, accessible, and fully customizable **Preline UI 5 & Tailwind CSS 4** block and component library bundle for **Sulu CMS 3.x / 2.5+**, powered by **Symfony UX Twig Components** and **`trk/sulu-block-suite-bundle`**.

---

## Table of Contents

- [Overview & Philosophy](#overview--philosophy)
- [Key Features](#key-features)
- [Included Blocks (31 Components)](#included-blocks-31-components)
- [Requirements](#requirements)
- [Installation](#installation)
- [Architecture & Override Mechanics](#architecture--override-mechanics)
- [Scoped CSS & Custom Attributes](#scoped-css--custom-attributes)
- [Sulu Live Preview Integration](#sulu-live-preview-integration)
- [Testing & Quality Assurance](#testing--quality-assurance)
- [License](#license)

---

## Overview & Philosophy

`SuluPrelineBlocksBundle` provides a complete library of **31 Sulu CMS blocks and Symfony UX Twig Components** built with **Tailwind CSS 4** and **Preline UI 5**, engineered with strict separation of concerns:

1. **Sulu XML Block Definitions (`config/templates/blocks/*.xml`)**: Pure content schemas for editors. Layout containers (`section`, `row_item`, `column_item`) carry zero unnecessary data fields and configure all visual layout options through `block_props`.
2. **Standardized 4-Tab Prop Schemas (`config/blocks/props/*.yaml`)**: Every block defines its visual options under the `settings` tab while inheriting `general` (spacing, width, alignment, visibility, position, animation), `advanced` (`name`, `id`, `class`, `attributes`, `.el-element` `css`), and `dynamic_content` from `trk/sulu-block-suite-bundle`.
3. **Zero Bundled Presets**: The bundle ships **zero opinionated presets**. Presets belong strictly to the host project (`config/blocks/presets/*.yaml`) or CMS editors (`block_preset` snippets).
4. **Decoupled Symfony UX Twig Components (`src/Twig/Components/` & `templates/components/`)**: Type-safe PHP component classes and accessible Preline UI 5 templates.

---

## Key Features

- **31 Ready-to-Use Blocks**: Layout containers (`section`, `row_item`, `column_item`), typography & media (`headline`, `text`, `page_header`, `quotation`, `code`, `html`, `image`, `video`, `gallery`, `slideshow`), interactive Preline components (`accordion`, `switcher`, `popover`, `overlay`, `overlay_slider`, `panel_slider`, `countdown`), and UI elements (`card`, `grid`, `button`, `badge`, `alert`, `divider`, `list`, `description_list`, `table`, `nav`, `subnav`, `breadcrumbs`, `social`, `icon`, `map`, `scroll_to`).
- **Auto-Injected Column & Page Block Lists**: `column_item.xml` uses `<param name="auto_inject" value="true"/>` and `<param name="exclude_blocks" value="section,row_item,column_item"/>`, automatically supporting all registered content blocks without hardcoded `<type ref="..."/>` lists.
- **Scoped `.el-element` CSS & Safe Custom Attributes**: `ElementTransform` and `Section`/`Row`/`Column` components natively render `props.attributes` (`data-*`, `aria-*`) and scoped `.el-element` `<style>` blocks.
- **Complete 6-Language Translations**: 100% translated admin labels and prop options for `en`, `tr`, `de`, `ru`, `es`, and `fr`.

---

## Included Blocks (31 Components)

| Category | Blocks |
| --- | --- |
| **Layout Containers** | `section`, `row_item`, `column_item` |
| **Typography & Content** | `headline`, `text`, `page_header`, `quotation`, `code`, `html`, `divider` |
| **Cards & Collections** | `card`, `grid`, `list`, `description_list`, `table` |
| **Media & Sliders** | `image`, `video`, `gallery`, `slideshow`, `overlay`, `overlay_slider`, `panel_slider` |
| **Interactive & Navigation** | `accordion`, `switcher`, `popover`, `button`, `badge`, `alert`, `nav`, `subnav`, `breadcrumbs`, `social`, `icon`, `countdown`, `map`, `scroll_to` |

---

## Requirements

- **PHP**: `>= 8.2` (Fully tested on PHP 8.5 with `declare(strict_types=1);`)
- **Sulu CMS**: `^2.5 || ^3.0`
- **Symfony**: `^6.4 || ^7.0`
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

- **Override Block XML**: Place `config/templates/blocks/{block}.xml` in your application.
- **Override Prop Schemas**: Place `config/blocks/props/{block}.yaml` in your application.
- **Define Project Presets**: Create `config/blocks/presets/{block}.yaml` in your application (1–3 curated presets per block recommended).
- **Override Twig Templates**: Place `templates/blocks/{block}.html.twig` or `templates/bundles/SuluPrelineBlocksBundle/components/{Component}.html.twig` in your application.

---

## Scoped CSS & Custom Attributes

Every Preline Twig Component (`Section`, `Row`, `Column`, `Card`, `Headline`, `Button`, etc.) processes `props` through `ElementTransform` and `BlockRenderer`:
- Custom HTML attributes (`data-*`, `aria-*`, `role`, etc.) from `props.attributes` are escaped and applied to the component wrapper.
- Custom CSS from `props.css` using `.el-element` (or bare CSS properties) is automatically scoped to the component's unique `#id` and emitted as an inline `<style>` tag before the component root element.

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
# Run PHPUnit 12 test suite
php ../../bin/phpunit -c phpunit.xml.dist

# Run PHPStan static analysis at level max
../../vendor/bin/phpstan analyse --debug -c phpstan.neon
```

---

## License

This bundle is open-source software licensed under the [MIT License](LICENSE).
