<?php
/**
 * SpeedX Theme Setup
 * 
 * Handles core theme registration, menu setup, and feature support.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpeedX_Setup {

	/**
	 * Register theme supports and features.
	 */
	public static function register() {
		add_action( 'after_setup_theme', [ __CLASS__, 'theme_setup' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'register_fonts' ] );
		add_action( 'widgets_init', [ __CLASS__, 'register_widgets' ] );
	}

	/**
	 * Set up theme defaults and registers support for various WordPress features.
	 */
	public static function theme_setup() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 675, true );

		register_nav_menus( [
			'primary'   => esc_html__( 'Primary Menu', 'speedx' ),
			'footer'    => esc_html__( 'Footer Menu', 'speedx' ),
			'social'    => esc_html__( 'Social Links Menu', 'speedx' ),
		] );

		add_theme_support( 'html5', [
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		] );

		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'custom-logo', [
			'height'      => 80,
			'width'       => 80,
			'flex-width'  => true,
			'flex-height' => true,
		] );
		add_theme_support( 'custom-background', [
			'default-color' => 'E8ECF3',
		] );
	}

	/**
	 * Register theme widgets.
	 */
	public static function register_widgets() {
		register_widget( 'SpeedX_Search_Widget' );
	}

	/**
	 * Register Google Fonts (Space Grotesk & Manrope).
	 */
	public static function register_fonts() {
		wp_register_style(
			'speedx-google-fonts',
			'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600&family=Space+Grotesk:wght@500;700&display=swap',
			[],
			null
		);
		wp_enqueue_style( 'speedx-google-fonts' );
	}
}

SpeedX_Setup::register();
