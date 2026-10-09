# Template Parts Placement

Current files stay flat for now to avoid churn while the theme foundation is still small.

Future placement rule:
- `layout/`: site-wide shell parts such as site header, site footer, and navigation.
- `content/`: loop content parts such as page content, single content, empty states, and cards tied to WordPress posts.
- `components/`: reusable UI parts such as section headers, cards, link cards, balloons, maps, and CTA parts.

Move files only when the number of parts makes the flat structure hard to scan.
