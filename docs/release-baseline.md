# Variable Thema 0.9.1 Local Release Baseline

This document records the local source baseline established before the
WordPress.org Theme Review Ticket #291928 remediation work.

## Source evidence

- Expected SHA-256 of the submitted ZIP:
  `cc4c6c1d816e04dc848b36cffcd1cce0d96fb23442e8cc7cb3a381be6d6dabb9`
- Measured SHA-256 of the locally stored ZIP:
  `e2318595b85e52d4357e2d7d894c8c4417c0265c17d9e05a865a2a560a0c4cb0`
- Content manifest SHA-256 of the 60 theme files:
  `a8929e22477fb0e02510fedbe93cad0b70a392cea18cfa59e041a6064f76c552`
- Path manifest SHA-256 of the 60 theme files:
  `5c3be2884762fc40ffc83008e1362c78310ff09aad6f71e1cf7f5b9f0edfc60e`

The 60 payload files extracted from the locally stored ZIP were byte-for-byte
identical to both the preserved pre-development local source and the active
Soleil-Official parent theme source.

The locally stored ZIP does not match the expected submitted ZIP SHA-256.
Therefore, the authenticity of that ZIP as the exact WordPress.org submission
artifact is unconfirmed. This Git baseline is based on the verified local
source content, not on ZIP artifact identity.

## Distribution policy

Development-only files must not be included in the WordPress.org release ZIP.
The `.gitattributes` rules exclude this `docs` directory, `.gitignore`, and
`.gitattributes` from archives produced with `git archive`. Every release
artifact must also be inspected independently before submission to confirm
that Git metadata, OS metadata, temporary files, and development-only
documentation are absent.
