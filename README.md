# Alenu Media WordPress Theme

Custom WordPress theme for the Alenu Media website. The theme provides a futuristic, animated front-end experience for showcasing services, selected works, articles, AI integrations, mobile app capabilities, and quote-driven lead generation.

## Features

- Custom homepage experience with hero, process, works, about, AI, pricing, testimonials, FAQ, and quote sections.
- WordPress Customizer-style admin options page for editing homepage copy, media, metrics, and contact details.
- Quote request form backed by a custom `quote_request` post type.
- REST API endpoints for quote submissions and dynamic content loading.
- SPA-style front-page navigation for key site views.
- Bundled front-end and admin CSS/JS assets, including minified production files.
- Theme support for custom logos, featured images, title tags, HTML5 markup, and custom image sizes.

## Theme Structure

```text
.
|-- assets/
|   |-- css/
|   `-- js/
|-- inc/
|   |-- home-options.php
|   |-- quote-requests.php
|   `-- theme-setup.php
|-- archive-works.php
|-- front-page.php
|-- functions.php
|-- header.php
|-- footer.php
|-- home.php
|-- index.php
|-- page.php
|-- single.php
|-- single-works.php
`-- style.css
```

## Requirements

- WordPress 6.4 or newer.
- PHP 8.0 or newer.
- A WordPress install with this folder placed inside `wp-content/themes/`.

## Installation

1. Copy the `alenumedia` folder into `wp-content/themes/`.
2. In WordPress admin, go to **Appearance > Themes**.
3. Activate **Alenu Media**.
4. Configure the homepage content and quote recipient from the theme's admin options.

## Development Notes

- Theme bootstrap starts in `functions.php`.
- Asset registration, theme support, SPA routing helpers, and front-end setup live in `inc/theme-setup.php`.
- Homepage editing fields and defaults live in `inc/home-options.php`.
- Quote request handling lives in `inc/quote-requests.php`.
- Production assets are loaded from `assets/css/main.min.css`, `assets/js/main.min.js`, and their admin equivalents.

## Deployment

Deploy the theme folder to the WordPress site's `wp-content/themes/alenumedia` directory, then confirm that the active theme, homepage settings, menus, media assets, and quote recipient email are configured in WordPress admin.
