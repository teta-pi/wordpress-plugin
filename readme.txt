=== TETA+PI ===
Contributors: tetapi
Tags: trust, verification, badge, ai agents, llms.txt, ai agent discoverability, aeo, geo
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect your WordPress site to a TETA+PI verified entity, prove domain ownership, and make your site readable by AI agents — not just human visitors.

== Description ==

TETA+PI is trust infrastructure for digital entities — verified people,
companies, APIs, AI models, MCP servers and agents, discoverable by AI agents.

This plugin lets a site owner:

* Connect the site to their TETA+PI entity with a personal API key.
* Prove domain ownership (DNS TXT record or a well-known file, whichever is
  easier) directly from the WordPress admin.
* Display a verified-entity badge anywhere via the `[tetapi_badge]` shortcode
  or the "TETA+PI Badge" widget.
* Make the site agent-readable: once connected, the plugin adds a
  machine-readable JSON-LD block to every page's `<head>`, and serves
  `/.well-known/agent.json`, `/.well-known/agent-card.json` and `/llms.txt`
  from your own domain — so AI agents and crawlers (including ones that
  don't execute JavaScript) can read your verified-entity data directly,
  not just see a visual badge.

Makes your site discoverable and verifiable by AI agents, LLMs and answer
engines (AEO/GEO) — not just search engines.

This release is 100% free — every feature above is fully functional with no
payment required. Two premium modules are planned for later: **Module #1
($25)** — additional badge styles, automatic placement, multi-entity support,
WooCommerce integration — and **Module #2 ($52)**, a further tier coming
after that. Neither is for sale yet; the settings page shows what's planned
and has a field to redeem a free code if TetaPi gives you one (e.g. as a
thank-you for a social-media shoutout).

Learn more at [tetapi.dev](https://tetapi.dev).

== Installation ==

1. Install and activate the plugin.
2. Go to Settings > TETA+PI.
3. Paste your personal API key from your TETA+PI account and choose the
   entity to connect.
4. Click "Start verification", then "Check now" once the DNS record or
   well-known file is in place.
5. Add `[tetapi_badge]` to any page or post, or add the "TETA+PI Badge"
   widget to a sidebar.

== Frequently Asked Questions ==

= Does this plugin send any data to third parties? =

The plugin only talks to `api.tetapi.dev`, the TETA+PI API, using the API key
you provide. No data is sent anywhere else. See "External services" below
for exactly what is sent and when.

= Do I need a TETA+PI account? =

Yes — create a free entity at [app.tetapi.dev](https://app.tetapi.dev) first,
then generate a personal API key from your account settings.

= Is my API key stored in plain text? =

No, it is encrypted at rest using your site's own WordPress salts.

= Do I have to pay for anything? =

No. Every feature in this release is free. Two premium modules are planned
for later (Module #1 / Module #2) but neither is for sale yet.

= How do I unlock Premium early? =

Premium isn't for sale yet. TetaPi occasionally gives out free redeemable
codes to early users, e.g. as a thank-you for a social-media shoutout. If you
have one, enter it under Settings > TETA+PI > Premium to unlock it on your
site.

= Does this help with AI/LLM discoverability (AEO/GEO)? =

Yes. AEO (Answer Engine Optimization) and GEO (Generative Engine
Optimization) are about being findable and verifiable inside AI-generated
answers, not just ranked in a search results page. This plugin's JSON-LD,
`/.well-known/agent.json`, `agent-card.json` and `/llms.txt` are exactly
that: real, structured, machine-readable proof of who you are that an LLM
or AI agent can read directly — the same category of signal as `robots.txt`
or a sitemap, but for agents instead of search crawlers.

= Does this plugin help with AI/agent discoverability, not just a visual badge? =

Yes. Once your entity is connected, the plugin adds a `schema.org` JSON-LD
block to every page's `<head>`, and serves `/.well-known/agent.json`,
`/.well-known/agent-card.json` and `/llms.txt` on your own domain — real
structured data an AI agent can read directly, without needing to parse the
visible badge text or execute any JavaScript. Before an entity is connected,
none of this is served (no default/placeholder data — those URLs 404).

== External services ==

This plugin connects to `api.tetapi.dev`, the TETA+PI API. It is operated by
TetaPi GmbH, the same company that develops this plugin, and is required for
the plugin's core functionality: connecting your site to your TETA+PI
entity, proving domain ownership, and displaying your trust badge.

What is sent, and when:

* When you save your Personal API Key under Settings > TETA+PI, that key is
  sent to `GET /businesses` to list the entities it can access. The key is
  stored encrypted at rest (using your site's own WordPress salts), not in
  plain text.
* When you click "Start verification" or "Check now", your site's domain
  (auto-detected from your site URL) and your chosen entity ID are sent to
  `POST /businesses/{id}/verify/domain/start` and
  `POST /businesses/{id}/verify/domain/check` respectively, to prove you
  control the domain.
* Whenever the `[tetapi_badge]` shortcode or the "TETA+PI Badge" widget is
  displayed on any page, your connected entity's slug is sent to the public,
  unauthenticated `GET /businesses/by-slug/{slug}/public` endpoint to fetch
  your badge data. This response is cached for 15 minutes (WordPress
  transient) to limit requests. No visitor-identifying data is sent — every
  visitor triggers the same request for the same public entity data.
* On every page load (for the JSON-LD block) and whenever
  `/.well-known/agent.json`, `/.well-known/agent-card.json` or `/llms.txt`
  is requested on your site, your connected entity's ID is sent to the
  public, unauthenticated `GET /wk/{entity_id}/*` endpoints to fetch
  agent-readable data about your entity. Also cached for 15 minutes. No
  visitor-identifying data is sent.

No data is sent to any other third party.

Terms of Service: https://tetapi.dev/terms
Privacy Policy: https://tetapi.dev/privacy

== Screenshots ==

1. Settings > TETA+PI — connect your entity with a personal API key.
2. Domain ownership verification status and controls.
3. The TETA+PI trust badge, rendered via the `[tetapi_badge]` shortcode on a page.
4. The "TETA+PI Badge" widget added to a sidebar.

== Changelog ==

= 1.1.0 =
* New: agent-readable output. A `schema.org` JSON-LD block is now added to
  every page's `<head>` once your entity is connected, and the plugin serves
  `/.well-known/agent.json`, `/.well-known/agent-card.json` and `/llms.txt`
  on your own domain, proxied from TETA+PI's agent-readable API and cached
  for 15 minutes. Nothing is served for a site that hasn't connected an
  entity yet.

= 1.0.1 =
* Fix: domain ownership verification could fail even with a correct
  well-known file, because WordPress redirects the verification URL to
  add a trailing slash and the verifier does not follow redirects
  (by design, to prevent SSRF). The plugin no longer lets WordPress
  redirect that one URL.

= 1.0.0 =
* Initial release: settings/connect page, domain ownership verification,
  badge shortcode + widget, Premium module teasers + free promo-code redeem.

== Upgrade Notice ==

= 1.1.0 =
Adds agent-readable JSON-LD + /.well-known/agent.json, agent-card.json,
and /llms.txt — real structured data for AI agents, not just a visual
badge. No action needed if you're already connected.

= 1.0.1 =
Fixes domain verification (via well-known file) failing on default
permalink settings. Update if "Check now" kept saying not verified.

= 1.0.0 =
Initial release.
