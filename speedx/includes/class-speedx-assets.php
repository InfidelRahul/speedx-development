<?php
/**
 * SpeedX Assets Manager
 * 
 * Handles enqueuing of styles, scripts, and performance optimizations.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpeedX_Assets {

	/**
	 * Register asset hooks.
	 */
	public static function register() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
		add_action( 'init', [ __CLASS__, 'performance_tweaks' ] );
	}

	/**
	 * Enqueue theme styles and scripts.
	 */
	public static function enqueue_assets() {
		// Main stylesheet.
		wp_enqueue_style(
			'speedx-style',
			get_stylesheet_uri(),
			[ 'speedx-google-fonts' ],
			SPEEDX_VERSION
		);

		// SPA Router script.
		wp_enqueue_script(
			'speedx-router',
			SPEEDX_URI . '/assets/js/router.js',
			[],
			SPEEDX_VERSION,
			true
		);

		// Pass data to JavaScript.
		wp_localize_script( 'speedx-router', 'speedxAjax', [
			'url'         => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'speedx_nonce' ),
			'restUrl'     => rest_url( 'speedx/v1/fragment' ),
			'restNonce'   => wp_create_nonce( 'wp_rest' ),
			'homeUrl'     => home_url( '/' ),
			'loadingText' => esc_html__( 'Loading...', 'speedx' ),
		] );
	}

	/**
	 * Performance optimizations: remove unnecessary scripts and styles.
	 */
	public static function performance_tweaks() {
		// Remove WordPress emoji scripts.
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		// Remove WP Embed script.
		wp_deregister_script( 'wp-embed' );

		// Remove Gutenberg block library CSS if not using blocks.
		remove_action( 'wp_enqueue_scripts', 'wp_common_block_scripts_and_styles' );

		// Remove DNS prefetch for emojis.
		remove_filter( 'wp_resource_hints', 'wp_resource_hints', 10, 2 );
	}
}

SpeedX_Assets::register();
