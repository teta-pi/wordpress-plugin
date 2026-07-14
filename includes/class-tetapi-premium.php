<?php
/**
 * Premium teaser + promo-unlock — no payment code, no license-server calls.
 * Premium isn't for sale yet (owner decision 2026-07-14: free launch). This
 * class only (a) shows what's planned, and (b) lets the plugin owner GIFT
 * premium to a site by handing out a redeemable code — e.g. as a reward for
 * a social-media action — with zero server-side infrastructure.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tetapi_Premium {

	/**
	 * SHA-256 hashes of valid promo codes, keyed by hash so the plaintext
	 * code never sits in the public plugin source (wp.org listings are
	 * fully readable). Empty by default — is_licensed() stays false until
	 * the plugin owner adds a hash and ships an update.
	 *
	 * To hand out a new code: pick a code (e.g. "TETAPI-LAUNCH-2026"), hash
	 * it, and add the hash below:
	 *   php -r "echo hash('sha256', strtoupper('YOURCODE'));"
	 */
	private static $promo_code_hashes = array(
		// 'paste-sha256-hash-here',
	);

	/**
	 * True only if the site owner entered a code matching one of the hashes
	 * above. No payment, no license-server call — a purely local, per-site
	 * check against a code the plugin owner handed out manually.
	 */
	public static function is_licensed() {
		$entered = trim( (string) get_option( 'tetapi_license_key', '' ) );
		if ( '' === $entered || empty( self::$promo_code_hashes ) ) {
			return false;
		}

		$hash = hash( 'sha256', strtoupper( $entered ) );
		foreach ( self::$promo_code_hashes as $valid_hash ) {
			if ( hash_equals( $valid_hash, $hash ) ) {
				return true;
			}
		}
		return false;
	}

	public static function render_locked_sections() {
		$sections = array(
			__( 'Badge style pack', 'tetapi' )           => __( '5 additional badge layouts + color/theme picker.', 'tetapi' ),
			__( 'Auto-insert placement', 'tetapi' )       => __( 'Show the badge in header/footer/post-end automatically, no shortcode needed.', 'tetapi' ),
			__( 'Multi-entity sites', 'tetapi' )          => __( 'Connect more than one TETA+PI entity to this site.', 'tetapi' ),
			__( 'WooCommerce integration', 'tetapi' )     => __( 'Show the badge on product, checkout, and order-email pages.', 'tetapi' ),
			__( 'Verification nudges', 'tetapi' )         => __( 'Dashboard reminders to complete email/registry verification.', 'tetapi' ),
			__( 'Priority badge refresh', 'tetapi' )      => __( 'Near-live trust level instead of the 15-minute cache.', 'tetapi' ),
		);
		$licensed = self::is_licensed();
		?>
		<?php if ( ! $licensed ) : ?>
			<p class="description">
				<?php esc_html_e( 'Two premium modules are planned — Module #1 ($25) covers the features below, Module #2 ($52) is a further tier coming later. Not for sale yet; nothing to buy here.', 'tetapi' ); ?>
			</p>
		<?php endif; ?>
		<ul class="tetapi-premium-list">
			<?php foreach ( $sections as $title => $description ) : ?>
				<li class="tetapi-premium-list__item">
					<span class="tetapi-premium-list__lock" aria-hidden="true"><?php echo $licensed ? '✅' : '🔒'; ?></span>
					<strong><?php echo esc_html( $title ); ?></strong>
					<span class="tetapi-premium-list__desc"><?php echo esc_html( $description ); ?></span>
					<span class="tetapi-premium-list__badge">
						<?php echo $licensed ? esc_html__( 'Unlocked', 'tetapi' ) : esc_html__( 'Module #1 — $25, coming soon', 'tetapi' ); ?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
