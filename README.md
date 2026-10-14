# Variable Thema — WordPress制作の共通フレーム

案件ごとに異なるデザインを制作しながら、共通のテーマ構造を利用できる無料のWordPressテーマです。
デザインは子テーマ（Skin）で自由に制作。共通部分は親テーマで管理できます。

# Variable Thema

Variable Thema is a free, GPL-licensed classic WordPress theme that provides a
reusable presentation frame for standard WordPress content. Site-specific
visual design can be supplied by a child theme (a **Skin**) without moving
persistent content or business functionality into the parent theme.

Current version: `1.0.0`

> [!NOTE]
> Variable Thema has been submitted for review on WordPress.org, but it has not
> yet been approved for the official Theme Directory.

## Design principles

Variable Thema separates responsibilities into three layers:

- **Frame (Variable Thema):** generic templates, layout, navigation,
  accessibility, reusable presentation components, and extension contracts.
- **Skin (child theme):** site-specific visual design and presentation choices.
- **Plugin:** persistent content models, business functionality, external API
  integrations, and settings that must survive a theme change.

Variable Thema and third-party Skins may be used, modified, and distributed
under the GNU GPL. A Soleil service agreement is not required to use the theme
or create, modify, distribute, or commercially use a Skin.

## Features

- Responsive classic-theme templates.
- Keyboard-accessible primary navigation and skip link.
- Reusable cards, calls to action, and 16 block patterns.
- Default and Landing page templates with matching editor layouts.
- English source strings with bundled Japanese translations.
- Extension points for Skin-provided CTA definitions and site conditions.
- No required third-party service, framework, or companion plugin.

## Requirements

- WordPress 6.5 or later.
- PHP 8.0 or later.
- The latest stable WordPress release is recommended.

## Installation

1. Download or clone the repository into `wp-content/themes/variable-thema`.
2. In WordPress, open **Appearance > Themes**.
3. Activate **Variable Thema**.
4. Assign the Primary and Footer menu locations as needed.
5. Configure the site identity and presentation settings in the Customizer.

No plugin is required for the parent theme's standard templates and
presentation features. Plugin-provided content types are displayed only when
the relevant plugin is installed.

## Developing a Skin

A Skin is a standard WordPress child theme. It may override presentation,
templates, styles, and supported extension points while leaving persistent data
and business functionality to plugins.

Public compatibility contracts and responsibility boundaries are documented in
[ARCHITECTURE.md](ARCHITECTURE.md). Backward-compatible hooks and functions
listed there should not be removed or have their signatures changed without a
documented deprecation path.

The GPL license does not restrict who may create or sell a Skin. “Variable
Thema” and “Soleil” names and branding should not be used to imply endorsement
or official status without permission.

## Translation

User-facing source strings use the `variable-thema` text domain. The
`languages/` directory contains the translation template and bundled Japanese
translation. WordPress.org language packs may override bundled translations
through WordPress's standard translation system.

## Documentation

- [Architecture and compatibility contracts](ARCHITECTURE.md)
- [Contributing](CONTRIBUTING.md)
- [Support](SUPPORT.md)
- [Security policy](SECURITY.md)
- [Changelog](CHANGELOG.md)
- [WordPress.org theme readme](readme.txt)

`readme.txt` is the distribution metadata and user-facing description for the
WordPress.org theme package. This README is the project overview for GitHub.

## Contributing and support

Bug reports and contributions are welcome. Read [CONTRIBUTING.md](CONTRIBUTING.md)
before opening a pull request and [SUPPORT.md](SUPPORT.md) before requesting
help. Do not disclose security vulnerabilities in a public issue; follow
[SECURITY.md](SECURITY.md) instead.

## License

Variable Thema is Copyright 2026 株式会社Soleil and is distributed under the GNU
General Public License version 2 or later. See [license.txt](license.txt).

Bundled resource licensing and attribution are documented in
[readme.txt](readme.txt).
