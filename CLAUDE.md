# TETA+PI — WordPress plugin

This repo is one component of TETA+PI (Trust Infrastructure for Digital
Entities), extracted from the platform monorepo 2026-07-14 (see
[decisions.md](https://github.com/teta-pi/infra/blob/main/docs/decisions.md)
in `teta-pi/infra` for the original split plan — this repo was the one
component "noted, not gated" for a later cutover, now done).

**Canonical docs, roadmap, changelog, and coding rules live in
[`teta-pi/infra`](https://github.com/teta-pi/infra)** — read `docs/api.md`
and `docs/verification-rework.md` there before touching this repo. This file
is a thin pointer plus plugin-specific rules; do not duplicate cross-cutting
rules here.

## Plugin-specific rules
- **PHP 7.4+ compatible** — no enums, no readonly props, no `match`. WP-core
  APIs only (`WP_Http`/`wp_remote_*`, Settings API, Shortcode API, Widgets
  API, transients for caching) — no Composer, no build step, no JS framework.
- **wp.org Plugin Check / review readiness**: escape all output
  (`esc_html`/`esc_attr`/`esc_url`), sanitize + nonce-check all input, text
  domain `tetapi` everywhere, `ABSPATH` guard at the top of every file,
  `manage_options` capability check on the settings page, `uninstall.php`
  must remove every `tetapi_*` option.
- **No payment or license-server code** (owner decision 2026-07-14): the
  plugin launches 100% free. Premium ($25/$52 module packs) stays
  "coming soon" teaser copy only in the settings page. If a promo/gift-unlock
  mechanic is added, it must not call any payment provider — a simple
  redeemable code checked server-side is the only sanctioned shape until a
  real paid launch is separately decided.
- This repo has **no deploy pipeline** — the plugin is distributed via
  wp.org (task 12.3, owner-executed), not rsynced to our server. CI here is
  lint/Plugin-Check only.
- Commits: end message with `Co-Authored-By: Claude Fable 5 <noreply@anthropic.com>`.
