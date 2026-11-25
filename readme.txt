=== TETA+PI ===
Contributors: tetapi
Tags: verification, trust badge, llms.txt, json-ld, ai agents
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Shows a TETA+PI verified-entity badge, proves you own the domain, and publishes agent-readable JSON-LD, llms.txt and agent.json.

== Description ==

TETA+PI is a public registry of verified entities (companies, people, services). This plugin links a WordPress site to one entry in that registry and does three things with it.

**Badge.** Put `[tetapi_badge]` into content, or place the widget (listed as "TETA+PI Badge") in a sidebar. Either one displays the entity's name and trust level and links to its public profile on app.tetapi.dev. The data comes from the registry's public endpoint and is cached for 15 minutes.

**Domain ownership.** From the settings page you request a verification token. The plugin serves it at `/.well-known/tetapi-verify.txt`, and the registry then checks that file (or a DNS TXT record) to confirm that the entity controls the domain. The result is shown on the settings page.

**Agent-readable output.** Three URLs are answered by the plugin itself (`/llms.txt`, `/.well-known/agent.json`, `/.well-known/agent-card.json`), and each page's `<head>` receives a schema.org script. All four are fetched from the registry for the connected entity and cached for 15 minutes.

The plugin uses only WordPress core HTTP functions and has no third-party PHP dependencies. A "Premium" section lists features that are not for sale; the only thing it accepts is a promotional code, checked locally against hashes in the plugin source.

== Installation ==

1. Install the plugin from the Plugins screen, or upload the `tetapi` folder to `/wp-content/plugins/`, then activate it.
2. Open **Settings → TETA+PI** and paste an API key (it starts with `pk_live_`). You create keys in your account on app.tetapi.dev.
3. Choose the entity this site represents.
4. Under **Domain ownership**, click **Start verification**, then **Check now**.
5. Insert `[tetapi_badge]` where the badge should appear, or use the widget in any widget area.

If `/llms.txt` or a `/.well-known/` URL returns 404 after activation, re-save **Settings → Permalinks** to rebuild the rewrite rules.

== Frequently Asked Questions ==

= Is an account required? =

Yes: the badge and the agent-readable files describe an entity in the TETA+PI registry, and an API key links the site to it.

= How is the API key stored? =

Encrypted with AES-256-CBC, with a key derived from your site's `AUTH` salt, in the option `tetapi_api_key_enc`. The settings form never displays the stored value.

= What happens on uninstall? =

All `tetapi_*` options and the cached entity list are deleted. Deactivating the plugin only removes its rewrite rules.

= Does the badge slow down page loads? =

Registry responses are cached in a transient for 15 minutes, so most page views make no outgoing request.

== External services ==

Requests go to the TETA+PI API at `https://api.tetapi.dev`, operated by TETA+PI. The requests are:

* **Your entities list**: `GET /api/v1/businesses`, sent with your API key when you open the settings page and choose an entity.
* **Public profile**: `GET /api/v1/businesses/by-slug/{slug}/public`, without authentication, when the badge renders and the cache is empty. It sends the entity's public slug.
* **Domain verification**: `POST /api/v1/businesses/{id}/verify/domain/start` and `.../check`, sent with your API key when you click the buttons. They send the entity id and your site's domain. The registry then fetches `https://<your-domain>/.well-known/tetapi-verify.txt` or looks up DNS.
* **Agent-readable files**: `GET /wk/{entity_id}/{file}`, without authentication, on a request for one of the three agent-readable URLs, or when a page renders the JSON-LD script, and the cache is empty.

The badge links visitors to `https://app.tetapi.dev/e/{slug}`.

Terms of service: https://tetapi.dev/terms.html
Privacy policy: https://tetapi.dev/privacy.html

== Screenshots ==

1. Settings page: API key and the Premium section with a promotional code field.
2. Settings page after domain verification has succeeded.
3. Front end: a page showing the badge rendered by the shortcode.
4. The badge as a sidebar widget.

== Changelog ==

= 1.1.1 =
* Current release.

= 1.1.0 =
* Agent-readable output: JSON-LD in `<head>`, `/llms.txt`, `/.well-known/agent.json`, `/.well-known/agent-card.json`.

= 1.0.1 =
* The verification file is served without WordPress's trailing-slash redirect, which the registry's verifier does not follow.

= 1.0.0 =
* First release: settings page, badge shortcode and widget, domain-ownership verification.

== Upgrade Notice ==

= 1.1.1 =
Re-save Settings → Permalinks after upgrading if `/llms.txt` returns 404.
