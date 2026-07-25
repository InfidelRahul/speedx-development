<?php
/**
 * SpeedX Security
 * 
 * Implements security hardening measures.
 * Handles sanitization, nonce verification, and XSS protection.
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SpeedX_Security {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'disable_xmlrpc' ) );
		add_filter( 'wp_headers', array( $this, 'remove_headers' ) );
		add_filter( 'login_errors', array( $this, 'sanitize_login_errors' ) );
	}

	/**
	 * Disable XML-RPC for security.
	 */
	public function disable_xmlrpc() {
		add_filter( 'xmlrpc_enabled', '__return_false' );
		remove_action( 'wp_head', 'wp_shortlink_wp_head' );
		remove_action( 'template_redirect', 'wp_shortlink_header', 11, 0 );
	}

	/**
	 * Remove revealing HTTP headers.
	 *
	 * @param array $headers Current headers.
	 * @return array Modified headers.
	 */
	public function remove_headers( $headers ) {
		unset( $headers['X-Pingback'] );
		unset( $headers['X-WP-Admin-Bar-Skip-Nonce'] );
		return $headers;
	}

	/**
	 * Sanitize login error messages to prevent user enumeration.
	 *
	 * @param string $error Original error message.
	 * @return string Sanitized error message.
	 */
	public function sanitize_login_errors( $error ) {
		if ( is_wp_error( $error ) ) {
			return __( 'Invalid username or password.', 'speedx' );
		}
		return __( 'Invalid username or password.', 'speedx' );
	}

	/**
	 * Sanitize output for safe display.
	 *
	 * @param string $data Data to sanitize.
	 * @return string Sanitized data.
	 */
	public static function sanitize_output( $data ) {
		return htmlspecialchars( $data, ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Verify nonce for REST API requests.
	 *
	 * @param string $nonce Nonce value.
	 * @param string $action Nonce action.
	 * @return bool
	 */
	public static function verify_nonce( $nonce, $action = 'wp_rest' ) {
		return wp_verify_nonce( $nonce, $action );
	}

	/**
	 * Validate and sanitize redirect URL.
	 *
	 * @param string $url URL to validate.
	 * @return string|false Validated URL or false.
	 */
	public static function validate_redirect( $url ) {
		if ( wp_validate_redirect( $url, false ) ) {
			return esc_url_raw( $url );
		}
		return false;
	}
}

return new SpeedX_Security();
