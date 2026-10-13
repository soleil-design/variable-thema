# Architecture

Variable Thema is organized around a strict separation between presentation,
site-specific design, and persistent functionality.

## Responsibility boundaries

### WordPress Core

WordPress owns standard content, menus, template hierarchy, the Customizer,
block editor behavior, and public APIs used by the theme.

### Variable Thema (Frame)

The parent theme owns generic presentation:

- Standard templates and template parts.
- Layout, responsive behavior, and presentation CSS.
- Accessible navigation and interaction behavior.
- Generic renderers and block patterns.
- Public hooks, filters, template arguments, and CSS contracts.
- Graceful presentation when optional plugin-provided content is unavailable.

The parent theme does not own site-specific business rules, external API
credentials, persistent custom content models, or data that must survive a
theme change.

### Skin (child theme)

A Skin owns site-specific presentation:

- Branding, typography, color, and visual composition.
- Site-specific template overrides and assets.
- Site-specific CTA definitions and conditional presentation.
- Presentation choices supplied through the parent theme's extension points.

A Skin should not require changes to Variable Thema's public contracts. Skins
are independent GPL child themes and may be created, modified, distributed, or
used commercially by third parties.

### Plugin

Plugins own persistent data and functionality, including:

- Custom post types and taxonomies.
- Shortcodes that provide persistent or functional behavior.
- Business rules and data normalization.
- External API integration and credentials.
- Settings and content that must remain available after switching themes.

## Public compatibility contracts

### Navigation availability

The header applies `variable_thema_has_primary_navigation` to the standard
`has_nav_menu( 'primary' )` result before rendering the primary navigation
wrapper. A child theme may return `true` when it can provide a menu through the
normal `wp_nav_menu()` rendering path. The filter must not update menu
assignments or theme mods.

Parent links retain their navigation behavior. When JavaScript is available,
items with children receive a separate submenu toggle. Without JavaScript,
nested menu links remain visible and usable.

### CTA

The following remain public compatibility contracts:

- `variable_get_cta_registry()`
- `variable_get_cta_args()`
- `variable_cta_registry`
- `variable_get_cta_icon_svg()`
- `variable_thema_cta_registry_provider`
- `variable_thema_before_site_footer`
- `template-parts/cta.php`
- Template arguments: `title`, `description`, and `buttons`
- Button keys: `label`, `url`, `variant`, and `icon`
- The `vt-cta*` CSS class family

A Skin or site layer may supply CTA definitions through
`variable_thema_cta_registry_provider`. Without a valid provider, the generic
registry is empty before the final `variable_cta_registry` filter runs.

### Site conditions and guide sections

Site-specific SWD detection may be supplied through
`variable_thema_swd_page_provider`. The public
`variable_thema_is_swd_page()` function remains available and returns `false`
when no provider exists.

Site-specific guide cards may be appended with
`variable_thema_admin_guide_site_sections`.

### Latest Posts

The persistent `[variable_latest_posts]` shortcode is owned by a plugin.
Variable Thema supplies optional presentation through
`soleil_content_core_latest_posts_renderer` and retains
`variable_thema_render_latest_posts_shortcode()` as an unhooked compatibility
callback. The theme does not register the shortcode.

### Service presentation

Service templates read normalized values through a content plugin when one is
available. Otherwise, the theme may read existing `service_*` post meta using
WordPress APIs for backward-compatible presentation. ACF is not required for
frontend rendering.

### FAQ

The `faq` post type, `faq_category` taxonomy, `[variable_faq_list]` shortcode,
and migration state belong to a plugin. Variable Thema retains FAQ templates
and presentation styles. Legacy FAQ functions are deprecated, unhooked
compatibility wrappers and do not register the content model.

### Site Profile

The `variable_site_profile` option, schema, sanitization, saving,
administration, and formal read API belong to a plugin. The parent theme keeps
the deprecated `variable_get_site_profile()` display wrapper. Without a
provider, it returns a safe empty profile or caller-provided keyed defaults.

### Contact Form 7 redirect

Contact Form 7 success-event redirect behavior and its frontend asset belong to
a plugin. The theme does not register a CF7 event listener or redirect enqueue.
Existing form markup may use the public `data-thanks-url` attribute.

## Deprecated compatibility surface

Legacy postcode-address and AI Counter option values remain readable for
compatibility, but the theme does not present a saving UI or use them to enable
runtime features. Legacy gateway entry points are compatibility-only; the
postcode enqueue stub is a no-op.

Variable Thema does not provide Google Maps features or manage Google Maps API
keys. Map rendering, credentials, and external API loading belong in a plugin.

## Backward compatibility policy

- Preserve documented public functions, hooks, filters, template arguments,
  template-part paths, and CSS contracts whenever practical.
- Add a deprecation and compatibility period before removing a public contract.
- Do not move site-specific behavior into the parent theme to preserve one
  implementation.
- Do not change persistent identifiers, slugs, option keys, or meta formats as
  part of a presentation update.
- Document intentional breaking changes in `CHANGELOG.md`.

## Distribution boundary

GitHub contains development and governance documentation. WordPress.org release
archives exclude development-only files according to `.gitattributes` and are
independently inspected before submission.
