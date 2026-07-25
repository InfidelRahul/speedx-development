<?php
/**
 * SpeedX Theme Setup
 * 
 * Registers core theme features like menus, thumbnails, and editor support.
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SpeedX_Setup {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_setup' ) );
	}

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	public function theme_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1200, 675, true );

		// Register navigation menus.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'speedx' ),
			'footer'  => esc_html__( 'Footer Menu', 'speedx' ),
		) );

		// Switch default core markup for search form, comment form, and comments
		// to output valid HTML5.
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for core custom logo.
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Remove core block patterns.
		remove_theme_support( 'core-block-patterns' );
	}
}

return new SpeedX_Setup();
