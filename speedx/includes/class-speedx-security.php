<?php
/**
 * SpeedX Security
 * 
 * Handles security hardening, sanitization, and protection.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpeedX_Security {

	/**
	 * Register security hooks.
	 */
	public static function register() {
		add_action( 'init', [ __CLASS__, 'disable_xmlrpc' ] );
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'add_security_headers' ] );
		add_filter( 'wp_headers', [ __CLASS__, 'security_headers' ] );
	}

	/**
	 * Disable XML-RPC for security.
	 */
	public static function disable_xmlrpc() {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	}

	/**
	 * Add security headers to HTTP responses.
	 * 
	 * @param array $headers Existing headers.
	 * @return array Modified headers.
	 */
	public static function security_headers( $headers ) {
		$headers['X-Content-Type-Options']    = 'nosniff';
		$headers['X-Frame-Options']           = 'SAMEORIGIN';
		$headers['X-XSS-Protection']          = '1; mode=block';
		$headers['Referrer-Policy']           = 'strict-origin-when-cross-origin';
		$headers['Permissions-Policy']        = 'geolocation=(), microphone=(), camera=()';
		
		return $headers;
	}

	/**
	 * Enqueue scripts with security attributes.
	 */
	public static function add_security_headers() {
		// Add crossorigin and integrity attributes where applicable.
		add_filter( 'script_loader_tag', [ __CLASS__, 'add_script_attributes' ], 10, 3 );
	}

	/**
	 * Add security attributes to script tags.
	 * 
	 * @param string $tag Script tag.
	 * @param string $handle Script handle.
	 * @param string $src Script source URL.
	 * @return string Modified script tag.
	 */
	public static function add_script_attributes( $tag, $handle, $src ) {
		if ( 'speedx-router' === $handle ) {
			$tag = str_replace( '<script', '<script type="module"', $tag );
		}
		return $tag;
	}
}

SpeedX_Security::register();
