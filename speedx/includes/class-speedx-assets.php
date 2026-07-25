<?php
/**
 * SpeedX Assets Manager
 * 
 * Handles enqueueing of stylesheets and JavaScript files.
 * Implements performance optimizations like defer loading and resource hints.
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SpeedX_Assets {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'emoji_svg_url', '__return_false' );
		add_action( 'init', array( $this, 'remove_emoji_scripts' ) );
		add_action( 'wp_print_styles', array( $this, 'dequeue_block_library' ), 100 );
	}

	/**
	 * Enqueue all theme assets.
	 */
	public function enqueue_assets() {
		$theme_version = wp_get_theme()->get( 'Version' );

		// Preconnect to Google Fonts if used.
		wp_add_inline_script( 'head', "
			<link rel='preconnect' href='https://fonts.googleapis.com'>
			<link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
		", 'before' );

		// Main stylesheet.
		wp_enqueue_style( 'speedx-style', get_stylesheet_uri(), array(), $theme_version );

		// SPA Router script with defer.
		wp_enqueue_script( 'speedx-router', 
			get_template_directory_uri() . '/assets/js/router.js', 
			array(), 
			$theme_version, 
			true 
		);

		// Localize script with config data.
		wp_localize_script( 'speedx-router', 'speedxConfig', array(
			'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
			'restUrl'       => esc_url_raw( rest_url() ),
			'restNonce'     => wp_create_nonce( 'wp_rest' ),
			'homeUrl'       => esc_url( home_url( '/' ) ),
			'spaEnabled'    => true,
			'transitionSpeed' => get_theme_mod( 'transition_speed', 300 ),
			'loadingEffect'   => get_theme_mod( 'loading_effect', 'fade' ),
		) );
	}

	/**
	 * Remove WordPress emoji scripts for performance.
	 */
	public function remove_emoji_scripts() {
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	}

	/**
	 * Dequeue block library CSS for non-block themes.
	 */
	public function dequeue_block_library() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
	}
}

return new SpeedX_Assets();
