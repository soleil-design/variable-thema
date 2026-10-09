=== Variable Thema ===
Contributors: soleil
Requires at least: 6.0
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A flexible classic WordPress theme with reusable presentation components and extension points.

== Description ==

Variable Thema provides accessible classic-theme templates, reusable cards, calls to action, navigation, and block patterns. Persistent content models and site-specific integrations are intentionally left to plugins or child themes.

== Installation ==

1. Upload the theme folder to `/wp-content/themes/`.
2. Activate Variable Thema from Appearance > Themes.
3. Assign the Primary and Footer menu locations as needed.
4. Configure the site identity and theme appearance in the Customizer.

== Features ==

* Responsive classic-theme templates.
* Keyboard-accessible navigation and skip link.
* Reusable presentation components and block patterns.
* Extension points for site-specific CTA and content presentation.
* No required third-party service or framework.

== Frequently Asked Questions ==

= Does this theme register custom post types? =

No. Persistent content models belong in plugins. The theme can present plugin-provided content through the standard template hierarchy.

== Changelog ==

= 1.0.0 =

* Prevented long titles and URLs from causing horizontal overflow.
* Completed the accessible primary navigation, including keyboard support, a no-JavaScript fallback, and hover-then-click handling for submenus.
* Added a generic search results template.
* Converted source strings to English and bundled Japanese translations.
* Removed the legacy Google Maps API key helpers; map features and API keys belong in a plugin.
* Added the Hero / Main Visual block pattern and a Landing page template.
* Matched the block editor canvas to the front end: content width, full-width blocks on the default and Landing templates, and centered CTA buttons on small screens.
* Loaded the block editor styles into the editor canvas through enqueue_block_assets.
* Fixed bundled block pattern layouts that collapsed into a single column on the front end, and list markup that the editor updated on insert.
* Replaced the theme screenshot.

= 0.9.1 =

* Addressed Required compliance issues identified during the initial WordPress.org human review.
* Moved theme administration pages under Appearance and corrected their capability checks.
* Stopped registering legacy Site Profile fields in the Customizer and made block pattern content translatable.
* Guaranteed identifiable inline content links and removed horizontal overflow from the full-width Latest Posts section.

= 0.9.0 =

* Moved Latest Posts shortcode ownership to the companion content plugin.
* Removed site-specific CTA and SWD fallback behavior from the parent theme.
* Added required packaging metadata, feed support, a skip link, and comments support.

== Copyright ==

Variable Thema, Copyright 2026 Soleil.
Variable Thema is distributed under the terms of the GNU GPL version 2 or later.

== Resources ==

This theme uses WordPress Dashicons, licensed under GPLv2 or later.
The bundled dummy avatar SVG was created specifically for Variable Thema in 2026 and is distributed under the same GPLv2-or-later license as the theme.
The background photograph shown in screenshot.png is an original photograph by Soleil, Copyright 2026 Soleil, and is distributed under the GNU GPL version 2 or later.
The VARIABLE logo shown in screenshot.png is an original work by Soleil, Copyright 2026 Soleil, and is distributed under the GNU GPL version 2 or later.
