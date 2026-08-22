<?php
/**
 * Agent-readable output (roadmap 12.6, docs/universal-tag.md Part B): a
 * server-side JSON-LD block in <head>, plus /.well-known/agent.json,
 * agent-card.json and /llms.txt, all proxied from api.tetapi.dev's
 * /wk/{entity_id}/* endpoints and cached the same way as the badge (15 min
 * transient) so an agent crawl doesn't hit the API on every request.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tetapi_Agent {

	const CACHE_TTL = 15 * MINUTE_IN_SECONDS;
	const QUERY_VAR = 'tetapi_agent_file';

	/** Query-var value => remote filename under /wk/{entity_id}/. */
	const REMOTE_FILES = array(
		'agent-json'      => 'agent.json',
		'agent-card-json' => 'agent-card.json',
		'llms-txt'        => 'llms.txt',
	);

	public function __construct() {
		add_action( 'init', array( __CLASS__, 'register_rewrite_rules' ) );
		add_filter( 'query_vars', array( $this, 'add_query_var' ) );
		add_action( 'template_redirect', array( $this, 'maybe_serve_file' ) );
		add_filter( 'redirect_canonical', array( $this, 'skip_canonical_redirect' ) );
		add_action( 'wp_head', array( $this, 'print_json_ld' ), 5 );
	}

	public static function register_rewrite_rules() {
		add_rewrite_rule( '^\.well-known/agent\.json$', 'index.php?' . self::QUERY_VAR . '=agent-json', 'top' );
		add_rewrite_rule( '^\.well-known/agent-card\.json$', 'index.php?' . self::QUERY_VAR . '=agent-card-json', 'top' );
		add_rewrite_rule( '^llms\.txt$', 'index.php?' . self::QUERY_VAR . '=llms-txt', 'top' );
	}

	public static function activate() {
		self::register_rewrite_rules();
		flush_rewrite_rules();
	}

	public static function deactivate() {
		flush_rewrite_rules();
	}

	public function add_query_var( $vars ) {
		$vars[] = self::QUERY_VAR;
		return $vars;
	}

	/**
	 * WordPress's default redirect_canonical() 301s an unrecognized rewrite
	 * endpoint to add a trailing slash (the same issue fixed for domain
	 * verification in 1.0.1) — skip it for our three agent-readable routes
	 * too, since an agent client won't necessarily follow the redirect.
	 */
	public function skip_canonical_redirect( $redirect_url ) {
		if ( get_query_var( self::QUERY_VAR ) ) {
			return false;
		}
		return $redirect_url;
	}

	public function maybe_serve_file() {
		$key = get_query_var( self::QUERY_VAR );
		if ( ! $key || ! isset( self::REMOTE_FILES[ $key ] ) ) {
			return;
		}

		$entity_id = get_option( 'tetapi_entity_id', '' );
		if ( ! $entity_id ) {
			status_header( 404 );
			exit;
		}

		$payload = self::get_wk_payload( $entity_id, self::REMOTE_FILES[ $key ] );
		if ( null === $payload ) {
			status_header( 404 );
			exit;
		}

		status_header( 200 );
		header( 'Content-Type: ' . $payload['content_type'] );
		echo $payload['body']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- proxied verbatim from our own api.tetapi.dev, not user input.
		exit;
	}

	/**
	 * Server-side JSON-LD in <head> — more reliable than a client-side script
	 * for agents that don't execute JS (docs/universal-tag.md's Part A/tag.js
	 * only reaches JS-executing crawlers).
	 */
	public function print_json_ld() {
		$entity_id = get_option( 'tetapi_entity_id', '' );
		if ( ! $entity_id ) {
			return;
		}

		$payload = self::get_wk_payload( $entity_id, 'agent.json' );
		if ( null === $payload ) {
			return;
		}

		// Decode + re-encode rather than echoing the cached body directly, so
		// a corrupt cache entry can never emit unescaped content inside <head>.
		$decoded = json_decode( $payload['body'], true );
		if ( ! is_array( $decoded ) ) {
			return;
		}

		echo '<script type="application/ld+json">' . wp_json_encode( $decoded ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode produces safe JSON, not raw HTML.
	}

	/**
	 * @return array{body: string, content_type: string}|null Null if the
	 *         entity has no agent data yet (or the API call failed) — never a
	 *         default/fake payload.
	 */
	private static function get_wk_payload( $entity_id, $filename ) {
		$cache_key = 'tetapi_wk_' . md5( $entity_id . '_' . $filename );
		$cached    = get_transient( $cache_key );
		if ( false !== $cached ) {
			return $cached;
		}

		$result = Tetapi_Api::get_wk_file( $entity_id, $filename );
		if ( is_wp_error( $result ) ) {
			return null;
		}

		set_transient( $cache_key, $result, self::CACHE_TTL );
		return $result;
	}
}
