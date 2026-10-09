# Variable Thema

Variable Thema is the presentation layer for standard WordPress content and reusable theme components.

Current release: `0.9.1`.

Ordinary links inside post, page, comment, and caption content retain a visible underline independently of browser default styles.

User-facing source strings are written in English with the `variable-thema` text domain. Bundled Japanese translations are loaded from `languages/`, while WordPress.org language packs can override them through WordPress's standard translation system.

Long unbroken titles and URLs wrap within page headers, breadcrumbs, cards, post navigation, and site identity areas without creating document-level horizontal scrolling.

## Navigation availability

The header applies `variable_thema_has_primary_navigation` to the standard `has_nav_menu( 'primary' )` result before rendering the primary navigation wrapper. A child theme may return true when it can provide a menu through WordPress's normal `wp_nav_menu()` rendering path. The filter must not update menu assignments or theme mods.

When JavaScript is available, parent menu items receive a separate accessible submenu toggle while their links retain normal navigation behavior. Without JavaScript, WordPress's nested menu links remain visible and usable.

## Latest Posts ownership

The persistent `[variable_latest_posts]` shortcode is owned by Soleil Content Core. Variable Thema supplies optional presentation through `soleil_content_core_latest_posts_renderer` and keeps `variable_thema_render_latest_posts_shortcode()` as an unhooked compatibility callback. The theme does not register the shortcode.

## CTA and site conditions

`variable_get_cta_registry()`, `variable_get_cta_args()`, the `variable_cta_registry` filter, the CTA icon API, renderer, template arguments, hooks, and CSS classes remain public compatibility contracts. A site layer may supply definitions through `variable_thema_cta_registry_provider`; without a valid provider, the generic registry is empty before the final public filter runs.

Site-specific SWD detection may be supplied through `variable_thema_swd_page_provider`. The public `variable_thema_is_swd_page()` function remains available and returns false when no provider exists. Site-specific guide cards may be appended with `variable_thema_admin_guide_site_sections`.

## Deprecated gateway settings

The postcode-address and AI Counter gateway records never enabled runtime features. Their Parts Activation option values remain readable for compatibility, but the theme no longer presents a saving UI or writes new values. Former gateway helper functions remain available as compatibility entry points; the postcode enqueue stub is a no-op.

The theme does not provide Google Maps features or manage Google Maps API keys. The former Google Maps compatibility helpers and their filters were removed in 0.9.2; map output and API key management belong in a plugin.

## Service data access

Service presentation reads normalized values through Soleil Content Core when available. If Core is unavailable, the theme reads the existing `service_*` post meta with WordPress APIs, so ACF is not required for frontend rendering. Service formatting helpers and template markup remain backward compatible.

## FAQ ownership

Since version 0.3.0, Soleil Content Core owns the `faq` post type, `faq_category` taxonomy, `[variable_faq_list]` shortcode, and FAQ migration state. Variable Thema retains only `archive-faq.php`, `template-parts/faq-list.php`, and the related presentation styles.

The legacy FAQ functions remain as deprecated, unhooked compatibility wrappers. Without Soleil Content Core, the theme does not register the FAQ content model and falls back safely to standard WordPress templates.

## Site Profile ownership

Since version 0.4.0, Soleil Content Core owns the `variable_site_profile` option, field schema, sanitization, saving, administration, and formal read API. Variable Thema retains only the deprecated `variable_get_site_profile()` display compatibility wrapper. When Core is unavailable, the wrapper returns an empty profile (or the caller-provided keyed default) without a fatal error.

## Contact Form 7 redirect ownership

Since version 0.5.0, Soleil Content Core owns the Contact Form 7 success-event redirect and its frontend asset. Variable Thema contains no CF7 event listener or redirect-specific enqueue. Existing form markup may continue to use the public `data-thanks-url` attribute.

## Bundled resources

- WordPress Dashicons: GPLv2 or later.
- `assets/images/dummy-avatar.svg`: original project artwork created for Variable Thema in 2026; GPLv2 or later.
