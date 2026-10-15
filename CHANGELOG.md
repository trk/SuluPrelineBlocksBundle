# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-10-15

### Added
- Complete library of 33 top-level Sulu CMS block XML definitions (`config/templates/blocks/*.xml`), 17 nested block-item definitions (`config/templates/block-items/*.xml`), and 12 shared field fragments (`config/templates/fields/*.xml`).
- Native `<section name="content">` and `<section name="settings">` tab structure integrated with `trk/sulu-block-suite-bundle`.
- Symfony UX Twig Components (`src/Twig/Components/*` & `templates/components/*.html.twig`) built for Preline UI 5 and Tailwind CSS 4.
- Decoupled Sulu block adapter templates (`templates/blocks/*.html.twig`) and `blocks_renderer.html.twig` preserving outer Twig context across auto-section boundaries for Dynamic Content resolution.
- Scoped `.el-element` CSS and XSS-safe custom attribute support via `ElementTransform`.
- Full 6-language internationalization (`en`, `tr`, `de`, `ru`, `es`, `fr`) across all XML schemas and UI labels.
- 100% XSD schema validation test coverage against Sulu `template-1.0.xsd` and `properties-1.0.xsd`.
