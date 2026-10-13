# Contributing

Thank you for contributing to Variable Thema.

## Before you start

- Search existing issues before opening a new one.
- Use the issue form that best matches the report.
- Do not include credentials, private site URLs, customer information, or other
  sensitive data in issues, pull requests, screenshots, or logs.
- Report security vulnerabilities privately according to
  [SECURITY.md](SECURITY.md).

## Architecture requirements

Contributions must preserve the responsibility boundaries in
[ARCHITECTURE.md](ARCHITECTURE.md):

- The parent theme owns generic WordPress presentation.
- A Skin owns site-specific presentation.
- Plugins own persistent data, business functionality, and external APIs.
- Existing public functions, hooks, filters, template paths, and CSS contracts
  must remain backward compatible unless a deprecation plan is approved.

Do not add a site-specific dependency or make a Soleil service agreement a
condition of using the theme.

## Development workflow

1. Create a focused branch from the current development baseline.
2. Make the smallest change that resolves the documented problem.
3. Update documentation and the changelog when behavior changes.
4. Run the relevant checks and regression tests.
5. Open a pull request with the reason, scope, compatibility impact, and test
   results.

Avoid unrelated formatting, generated-file churn, and broad refactoring in the
same pull request.

## Coding standards

- Follow the WordPress PHP, JavaScript, CSS, documentation, and accessibility
  coding standards.
- Use the `variable-thema` text domain for user-facing strings.
- Escape output, sanitize input, verify nonces, and check capabilities where
  appropriate.
- Prefer WordPress APIs over custom replacements.
- Keep the minimum supported versions declared in `style.css` and `readme.txt`.

## Required checks

Run checks appropriate to the change, including:

- PHP syntax checks for all PHP files.
- WordPress Coding Standards.
- Theme Check with zero REQUIRED findings.
- JavaScript syntax and coding-standard checks for changed scripts.
- Translation placeholder and PO/MO validation for i18n changes.
- `git diff --check`.
- Browser regression testing for affected templates and interactions.

Document checks that could not be run. Do not report an untested item as
passing.

## Pull requests

A pull request should include:

- A concise problem statement.
- The reason for the chosen implementation.
- Files and public contracts affected.
- Backward-compatibility and Plugin Territory assessment.
- Test environment and results.
- Remaining risks or manual verification.

Pull requests must not contain production credentials, customer data, database
exports, local environment configuration, logs with sensitive values, or
unrelated binary artifacts.

## Licensing

Variable Thema is licensed under the GNU GPL version 2 or later. By submitting
a contribution, you agree that your contribution is provided under the same
GPLv2-or-later terms and that you have the right to submit it.

Third-party assets must be GPL-compatible and include clear provenance and
license information.
