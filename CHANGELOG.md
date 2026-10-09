# Changelog

## 1.0.0 - 2026-10-09

- Prevented long unbroken titles and URLs from causing horizontal overflow in generic title, breadcrumb, card, post-navigation, header, and footer presentation.
- Completed the generic primary navigation with independent submenu controls, keyboard focus restoration, responsive state reset, and a no-JavaScript fallback.
- Converted user-facing source strings to English and added a generated translation template with bundled Japanese translations.
- Removed the legacy Google Maps API key helpers (`variable_thema_get_google_maps_api_key()`, `variable_thema_has_google_maps_api_key()`, `variable_thema_get_google_maps_api_key_source()`, `variable_thema_get_google_maps_api_key_source_label()`), their `variable_thema_google_maps_api_key` and `variable_thema_google_maps_api_key_source` filters, and the unused `variable_thema_get_admin_guide_maps_state()` helper. Google Maps features and API key management belong in a plugin.
- Hid the mobile navigation toggle until the navigation script is ready so it is never shown without JavaScript.
- Added the Hero / Main Visual block pattern built from core Cover, Group, Heading, Paragraph, and Button blocks.
- Added a Landing page template that keeps the page title for screen readers, lets full-width blocks span the main area directly below the site header, and keeps the front page hero; the Hero / Main Visual pattern now uses full-width alignment.
- Replaced the theme screenshot with a parent-theme-only view and documented the screenshot photograph and logo in readme.txt.

## 0.9.1 - 2026-09-17

- Addressed Required compliance issues identified during the initial WordPress.org human review.
- Moved the Guide and Parts pages under Appearance and changed their capability checks to `edit_theme_options`.
- Stopped registering legacy Site Profile company fields in the Customizer without deleting existing theme mods.
- Made bundled block pattern display strings translatable without changing their markup or structure.
- Normalized the distributed text files to LF line endings.
- Guaranteed underlines for ordinary links in post, page, comment, and caption content without changing navigation or button-like links.
- Prevented the full-width Latest Posts section from causing document-level horizontal overflow.

## 0.9.0 - 2026-09-04

- Moved `[variable_latest_posts]` registration and normalization ownership to Soleil Content Core while retaining the optional theme renderer and public compatibility callback.
- Removed Soleil CTA and SWD behavior from parent fallbacks; providers and final public filters remain supported.
- Stopped reading Google Maps credentials from theme-owned constants or options and retained filter-only compatibility helpers.
- Added GPL metadata, public readme, feed support, a keyboard skip link, and a standard comments template.
- Replaced the avatar placeholder with documented original project artwork.
- Removed an unused Soleil-specific SWD image from the public theme package.
- Added minimal WordPress core alignment, caption, gallery, sticky-post, and comment-author styles.
- Documented PHP file responsibilities and the public compatibility API surface.
- Renamed template-local variables to avoid WordPress global-name collisions without changing template arguments or rendered markup.
- Added reproducible Local plans for non-provider Skin and comments regression testing.
- Documented all global function contracts without changing signatures or runtime behavior.
- Reached a WPCS baseline of zero errors; retained ten documented compatibility/query warnings.
- Restored the generic `variable_thema_has_primary_navigation` extension contract used by child themes that provide a compatible navigation fallback.
- Aligned the public theme headers with WordPress.org upload requirements by using a numeric version, supported feature tags, and a single author URL.

## 0.7.0-alpha - 2026-09-04

- Added provider extension points for site-owned CTA registries and SWD page conditions while preserving legacy fallbacks and public APIs.
- Kept `variable_cta_registry` as the final registry filter and preserved CTA renderer, arguments, markup, and CSS contracts.
- Moved site-specific admin-guide sections behind a child-theme extension hook.
- Deprecated non-functional Parts Activation gateway settings without deleting their stored option values.
- Removed the postcode gateway stub from frontend execution and marked Google Maps, postcode, and AI Counter registry entries as deprecated.
- Replaced manual archive-prefix string stripping with the WordPress archive-title prefix filter.

## 0.6.0-alpha - 2026-09-04

- Switched Service presentation reads from ACF `get_field()` to the Content Core normalized API.
- Added a WordPress post-meta compatibility reader for installations without Content Core or ACF.
- Preserved Service archive ordering, featured filtering, relationships, HTML, and CSS contracts.

## 0.5.0-alpha - 2026-09-03

- Transferred Contact Form 7 success redirect ownership and its frontend asset to Soleil Content Core.
- Removed the Theme-owned CF7 redirect enqueue and listener.
- Preserved form markup and the public `data-thanks-url` contract.

## 0.4.0-alpha - 2026-09-03

- Transferred Site Profile schema, defaults, sanitization, storage, and administration to Soleil Content Core.
- Retained `variable_get_site_profile()` as a deprecated display compatibility wrapper.
- Preserved the existing option key, field keys, getter return contract, footer markup, and legacy admin page slug.
- Added a fatal-safe empty fallback when Content Core is unavailable.

## 0.3.0-alpha - 2026-09-03

- Transferred ownership of the FAQ post type, taxonomy, shortcode, initial-term migration, and persistent migration state to Soleil Content Core.
- Retained FAQ archive and template-part presentation in the theme.
- Replaced former theme providers with unhooked deprecated compatibility wrappers.
- Preserved the existing FAQ slugs, shortcode contract, URLs, REST exposure, and relationships.
