=== EWEB - Starter Helper ===
Contributors: yisus-develop
Tags: svg, elementor, optimization, security, branding
Requires at least: 6.0
Tested up to: 6.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Essential initial setup for WordPress projects: Safe SVGs, Elementor cleanup, and performance optimizations.

== Description ==

This plugin provides a standardized "Starter Kit" for new WordPress installations. It handles common tasks that are usually performed manually:

* **Safe SVG Support:** Enables SVG uploads with MIME type detection and admin display fixes.
* **Elementor Widget & Optimizations:** Native "EWEB Copyright" widget with full styling and dynamic year.
* **Master Duplicator:** Single-click duplication for Posts, Pages, and Elementor Templates.
* **Security & Performance:** Global toggles for XML-RPC, Head bloat, Emojis, and Environment badges.
* **i18n Ready:** Fully translatable with English as base language and POT included.
* **Agency Branding:** Custom login branding and agency attribution settings.

== Installation ==

1. Upload the `eweb-starter-helper` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Changelog ==

= 1.1.1 =
* Improvement: Granular controls in Elementor Copyright widget (Symbol/Year/Agency toggles).
* Improvement: Added `prefix` attribute to [eweb_copyright] and internal defaults update.
* New: Added [eweb_copy_year] shortcode for pure symbol + year output.
* Fix: Standardized placeholders and defaults for agency branding.

= 1.1.0 =
* Major release: Modular architecture with central settings panel.
* Added [eweb_copyright] and [eweb_year] shortcodes with global data support.
* Added native Elementor Copyright Widget with dual link support.
* Added Universal Duplicator for CPTs and Elementor templates.
* Added Environment Badge and Dashboard Cleanup modules.
* Implemented full i18n support (English base) and consolidated branding.
* Added "Crafted with love" agency footer settings.

= 1.0.0 =
* Initial internal release.
