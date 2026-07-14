=== TETA+PI ===
Contributors: tetapi
Tags: trust, verification, badge, domain verification, security
Requires at least: 5.8
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Connect your WordPress site to a TETA+PI verified entity, prove domain ownership, and display a trust badge.

== Description ==

TETA+PI is trust infrastructure for digital entities — verified people,
companies, APIs, AI models, MCP servers and agents, discoverable by AI agents.

This plugin lets a site owner:

* Connect the site to their TETA+PI entity with a personal API key.
* Prove domain ownership (DNS TXT record or a well-known file, whichever is
  easier) directly from the WordPress admin.
* Display a verified-entity badge anywhere via the `[tetapi_badge]` shortcode
  or the "TETA+PI Badge" widget.

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
you provide. No data is sent anywhere else.

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

== Screenshots ==

1. Settings > TETA+PI — connect your entity with a personal API key.
2. Domain ownership verification status and controls.
3. The TETA+PI trust badge, rendered via shortcode.

== Changelog ==

= 1.0.0 =
* Initial release: settings/connect page, domain ownership verification,
  badge shortcode + widget, Premium module teasers + free promo-code redeem.
