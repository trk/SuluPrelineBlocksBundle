# Sulu Preline Blocks Bundle

[![Latest Version](https://img.shields.io/badge/version-1.0.0-blue.svg)](https://github.com/trk/SuluPrelineBlocksBundle)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2%20%7C%208.5-8892BF.svg)](https://php.net)
[![Sulu CMS](https://img.shields.io/badge/Sulu%20CMS-%5E2.5%20%7C%20%5E3.0-orange.svg)](https://sulu.io)
[![Symfony](https://img.shields.io/badge/Symfony-%5E6.4%20%7C%20%5E7.0-black.svg)](https://symfony.com)
[![Preline UI](https://img.shields.io/badge/Preline%20UI-v5.0-blueviolet.svg)](https://preline.co)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4.x-38bdf8.svg)](https://tailwindcss.com)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)

An enterprise-grade, accessible, and fully customizable **Preline UI 5 & Tailwind CSS 4** component library bundle for **Sulu CMS 3.x / 2.5+**, powered by **Symfony UX Twig Components** and **`trk/sulu-block-suite-bundle`**.

---

## Table of Contents

- [Overview & Philosophy](#overview--philosophy)
- [Key Features](#key-features)
- [Core Blocks](#core-blocks)
- [Requirements](#requirements)
- [Installation](#installation)
  - [1. Composer Package](#1-composer-package)
  - [2. Register the Bundle](#2-register-the-bundle)
  - [3. Tailwind CSS 4 Configuration](#3-tailwind-css-4-configuration)
  - [4. Preline UI JavaScript Setup](#4-preline-ui-javascript-setup)
  - [5. Enable Blocks in Page Templates](#5-enable-blocks-in-page-templates)
- [Architecture & Override Mechanics](#architecture--override-mechanics)
  - [1. Overriding Block XML Structures](#1-overriding-block-xml-structures)
  - [2. Overriding Prop Schemas & Presets](#2-overriding-prop-schemas--presets)
  - [3. Overriding Twig Components & HTML](#3-overriding-twig-components--html)
  - [4. Creating Custom Project Blocks](#4-creating-custom-project-blocks)
- [Sulu Live Preview Integration](#sulu-live-preview-integration)
- [Testing & Quality Assurance](#testing--quality-assurance)
- [License](#license)

---

## Overview & Philosophy

Modern CMS websites often suffer from tight coupling: hardcoded HTML in page templates, scattered CSS utilities, and rigid admin block schemas.

`SuluPrelineBlocksBundle` solves this by introducing a **decoupled, 3-tier architecture**:

```
┌────────────────────────────────────────────────────────┐
│  Host Project (Application / Brand Layer)              │
│  - Custom blocks (Hero, Pricing, etc.)                 │
│  - Brand style presets & custom schema overrides       │
│  - Tailwind CSS 4 theme & color tokens                 │
└───────────────────────────┬────────────────────────────┘
                            │
┌───────────────────────────▼────────────────────────────┐
│  trk/sulu-preline-blocks-bundle (UI Component Layer)   │
│  - 9 out-of-the-box Preline UI 5 components            │
│  - Accessible Twig Components & semantic markup        │
│  - Curated default YAML prop schemas                   │
│  - Preline JS lifecycle & Live Preview autoInit        │
└───────────────────────────┬────────────────────────────┘
                            │
┌───────────────────────────▼────────────────────────────┐
│  trk/sulu-block-suite-bundle (Engine Layer)            │
│  - Reusable Global Blocks (shared snippets)            │
│  - Dual-Source Style & Prop Presets (YAML + CMS)       │
│  - Dynamic React BlockProps modal in Sulu Admin        │
│  - Central Layout Engine (layout_render())             │
└────────────────────────────────────────────────────────┘
```

### Zero Hardcoding Guarantee
1. **Never hardcode markup**: Block templates delegate directly to Twig Components (`<twig:Card ... />`).
2. **Never hardcode styles**: Visual styling is driven by structured YAML prop schemas, customizable via the visual admin modal.
3. **100% Overridable**: Every XML block structure, Twig template, prop schema, and preset can be overridden in the host application without modifying vendor code.

---

## Key Features

- **9 Preline UI 5 Blocks**: Complete accessible implementations for Card, Headline, Text, Alert, Button, Divider, Image, Video, and Overlay.
- **Tailwind CSS 4 CSS-First Scanning**: Seamless `@source` integration with zero stylesheet bloat.
- **Symfony UX Twig Components**: Type-safe, isolated PHP component classes with dedicated props resolvers and element transforms.
- **Accessible & Responsive**: Full WCAG compliance, keyboard focus rings, dark mode support (`dark:` modifiers), and responsive variants.
- **Sulu Live Preview Auto-Rebind**: Automatically invokes Preline's `HSStaticMethods.autoInit()` during Sulu preview postMessage events.
- **Interactive Props Modal**: Fully integrated with `trk/sulu-block-suite-bundle` for real-time visual styling in the Sulu Admin panel.

---

## Core Blocks

| Block | Description | Interactive Features |
|---|---|---|
| **Card** | Feature cards, blog cards, profile cards, product cards | Media banner, badges, hover zoom, multiple buttons, icon cards |
| **Headline** | Semantic headings (`h1`-`h6`, `div`, `span`) | Visual style scaling, accent badges, alignments, letter spacing |
| **Text** | Rich text & lead paragraphs | Multi-column layouts, lead styles, line-height, text colors |
| **Alert** | Accessible notification callouts | Dismiss button (`data-hs-remove-element`), 5 semantic variants, soft/outline styles |
| **Button** | CTAs, links, and action buttons | 8 visual variants, icons, rounded-full, pill, sizes (`xs`-`xl`) |
| **Divider** | Section separators | Solid, dashed, dotted, gradients, embedded text/icons |
| **Image** | Single and gallery images | Aspect ratios, lazy loading, lightbox triggers, hover zoom |
| **Video** | Responsive video players | YouTube/Vimeo embeds, HTML5 video, autoplay, controls, modal trigger |
| **Overlay** | Full-featured modals and drawers | Preline `data-hs-overlay`, backdrop blur, size controls, customizable actions |

---

## Requirements

- **PHP**: `>= 8.2` (Fully tested on PHP 8.2, 8.3, 8.4, and 8.5)
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

In `config/bundles.php`:

```php
return [
    // ...
    Totoglu\SuluBlockSuiteBundle\SuluBlockSuiteBundle::class => ['all' => true],
    Totoglu\SuluPrelineBlocksBundle\SuluPrelineBlocksBundle::class => ['all' => true],
];
```

### 3. Tailwind CSS 4 Configuration

In your website's main stylesheet (e.g. `assets/website/src/app.css`):

```css
@import "tailwindcss";
@import "preline/variants.css";

/* Preline JS plugins */
@source "../node_modules/preline/dist/*.js";

/* Host templates */
@source "../../templates/**/*.twig";
@source "../../src/Twig/Components/**/*.php";

/* SuluPrelineBlocksBundle templates & components */
@source "../../vendor/trk/sulu-preline-blocks-bundle/templates/**/*.twig";
@source "../../vendor/trk/sulu-preline-blocks-bundle/src/Twig/Components/**/*.php";
```

### 4. Preline UI JavaScript Setup

In your frontend entry point (e.g. `assets/website/src/app.js`):

```javascript
import 'preline/preline';

// Initial load
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.HSStaticMethods !== 'undefined') {
        window.HSStaticMethods.autoInit();
    }
});

// Re-bind when Sulu CMS Live Preview updates page content
window.addEventListener('message', (event) => {
    if (typeof window.HSStaticMethods !== 'undefined') {
        window.HSStaticMethods.autoInit();
    }
});
```

### 5. Enable Blocks in Page Templates

1. Reference the bundle's blocks in your page XML or include file (e.g. `config/templates/includes/blocks.xml`):

```xml
<property name="blocks" type="block" default-type="headline">
    <meta>
        <title lang="en">Page Content Blocks</title>
        <title lang="tr">Sayfa İçerik Blokları</title>
    </meta>
    <types>
        <type ref="headline"/>
        <type ref="text"/>
        <type ref="image"/>
        <type ref="card"/>
        <type ref="button"/>
        <type ref="video"/>
        <type ref="alert"/>
        <type ref="divider"/>
        <type ref="overlay"/>
    </types>
</property>
```

2. Render the blocks in your page template (e.g. `templates/pages/default.html.twig`):

```twig
{% for block in content.blocks|default([]) %}
    {{ include('blocks/' ~ block.type ~ '.html.twig', { block: block }, with_context: false) }}
{% endfor %}
```

The bundle's block templates automatically resolve styling props, merge presets, and render the underlying accessible Twig Components (`<twig:Card ... />`, `<twig:Button ... />`, etc.).

---

## Architecture & Override Mechanics

### 1. Overriding Block XML Structures
Sulu automatically checks the host application's `config/templates/blocks/` directory before consulting bundle directories. To customize any block's admin fields, place an XML file with the same name in your project:

```
config/templates/blocks/card.xml  <-- Overrides vendor card.xml!
```

### 2. Overriding Prop Schemas & Defining Presets
`SuluPrelineBlocksBundle` provides the foundational prop schemas in `config/blocks/props/` and deliberately contains **no presets**. Presets are 100% project-driven:

- **Schemas**: `config/blocks/props/card.yaml` in your project overrides the bundle's default `card.yaml`.
- **Presets**: Create `config/blocks/presets/card.yaml` in your project to define brand-specific visual style presets.

### 3. Overriding Twig Components & HTML
To override the HTML markup of any component:
- **Symfony UX Twig Override**: Place a template at `templates/bundles/SuluPrelineBlocksBundle/components/Card.html.twig`.
- **Block Template Override**: Place a template at `templates/blocks/card.html.twig`.

### 4. Creating Custom Project Blocks
Adding a brand-specific block (e.g. `hero`) is straightforward:

1. Create `config/templates/blocks/hero.xml`.
2. Add `<type ref="hero"/>` to your `config/templates/includes/blocks.xml`.
3. Create `templates/blocks/hero.html.twig` or `<twig:Hero ... />`.
4. (Optional) Create `config/blocks/props/hero.yaml` and `config/blocks/presets/hero.yaml` for visual styling in the Sulu modal!

---

## Sulu Live Preview Integration

Preline UI relies on DOM attributes to initialize collapses, modals, dropdowns, and accordions. Because Sulu CMS Live Preview updates the page dynamically via iframe messages, `window.HSStaticMethods.autoInit()` is required to re-bind interactive behaviors.

The bundle's included templates and components are designed to re-initialize seamlessly without memory leaks or duplicate event listeners.

---

## Testing & Quality Assurance

`SuluPrelineBlocksBundle` is tested against a rigorous quality matrix:

- **PHP 8.5 Strict Typing**: Every PHP file enforces `declare(strict_types=1);`.
- **PHPStan Level Max**: 100% clean static analysis with zero errors.
- **PHPUnit 12 Tested**: 67 tests, 481 assertions passing with 0 notices.
- **Cross-Version Matrix**: Tested across PHP 8.2, 8.3, 8.4, and 8.5 on Symfony 6.4 and 7.4.

```bash
# Run unit tests
composer test

# Run PHPStan static analysis at level max
composer phpstan

# Run coding standards check
composer cs-check
```

CI workflows automatically execute the test suite across PHP 8.2, 8.3, 8.4, and 8.5 on every commit and pull request.

---

## License

This bundle is open-source software licensed under the [MIT License](LICENSE).
