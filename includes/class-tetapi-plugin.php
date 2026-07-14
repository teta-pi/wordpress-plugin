<?php
/**
 * Bootstrap singleton — wires up the settings page, domain verification,
 * badge, and premium modules.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tetapi_Plugin {

	/** @var Tetapi_Plugin|null */
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		register_activation_hook( TETAPI_PLUGIN_FILE, array( 'Tetapi_Domain', 'activate' ) );
		register_deactivation_hook( TETAPI_PLUGIN_FILE, array( 'Tetapi_Domain', 'deactivate' ) );

		new Tetapi_Settings();
		new Tetapi_Domain();
		new Tetapi_Badge();
	}
}
