# TETA+PI for WordPress

A WordPress plugin that links a site to an entity in the [TETA+PI](https://tetapi.dev) registry. It shows a verified badge, proves domain ownership through a well-known file, and serves JSON-LD, `llms.txt` and `agent.json` for AI agents. The wordpress.org listing text is in [`readme.txt`](readme.txt).

## Status

Version 1.1.1, 15 commits (July–October 2026). On every push and pull request, CI runs `php -l` on all files and WordPress Plugin Check (`.github/workflows/check.yml`). There are no unit tests.

## How it works

| File | Responsibility |
|---|---|
| `teta-pi.php` | Plugin header and constants (`TETAPI_API_BASE`, `TETAPI_WK_BASE`); boots `Tetapi_Plugin` |
| `includes/class-tetapi-api.php` | HTTP client over `wp_remote_*`: entity list, public profile, domain-verification start and check, well-known file fetch |
| `includes/class-tetapi-settings.php` | Settings → TETA+PI page; API key stored encrypted (AES-256-CBC, key from `wp_salt('auth')`) |
| `includes/class-tetapi-domain.php` | Rewrite rule for `/.well-known/tetapi-verify.txt`, admin-post handlers for start and check |
| `includes/class-tetapi-badge.php` | `[tetapi_badge]` shortcode and widget, with a 15-minute transient cache |
| `includes/class-tetapi-agent.php` | JSON-LD in `wp_head`; rewrite rules for llms.txt, agent.json and agent-card.json, proxied and cached for 15 minutes |
| `includes/class-tetapi-premium.php` | A list of features not yet for sale, and a local promotional-code check against SHA-256 hashes |
| `uninstall.php` | Deletes every `tetapi_*` option and the cached transient |

The plugin turns off WordPress's canonical trailing-slash redirect for its own rewrite endpoints, because the registry's verifier and many agent clients do not follow redirects.

## Development

```bash
# lint, as CI does
find . -name '*.php' -not -path './.git/*' -exec php -l {} \;
```

To try it, copy or symlink the folder into `wp-content/plugins/tetapi` on a local WordPress (5.8 or newer, PHP 7.4 or newer), activate it, and open Settings → TETA+PI.

## Licence

GPL-2.0-or-later, as declared in the plugin header.
