<?php
/**
 * SpeedX REST API
 * 
 * Handles custom REST API endpoints for SPA fragment loading.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpeedX_API {

	/**
	 * Register API hooks.
	 */
	public static function register() {
		add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
	}

	/**
	 * Register REST API routes.
	 */
	public static function register_routes() {
		register_rest_route( 'speedx/v1', '/fragment', [
			'methods'             => 'GET',
			'callback'            => [ __CLASS__, 'get_fragment' ],
			'permission_callback' => [ __CLASS__, 'verify_nonce' ],
			'args'                => [
				'url' => [
					'required'          => true,
					'sanitize_callback' => 'esc_url_raw',
					'validate_callback' => [ __CLASS__, 'validate_url' ],
				],
				'template' => [
					'required'          => false,
					'sanitize_callback' => 'sanitize_text_field',
					'validate_callback' => [ __CLASS__, 'validate_template' ],
				],
			],
		] );
	}

	/**
	 * Get HTML fragment for a given URL.
	 * 
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public static function get_fragment( $request ) {
		$url      = $request->get_param( 'url' );
		$template = $request->get_param( 'template' ) ?? 'index';

		// Parse URL to get path.
		$parsed = wp_parse_url( $url );
		$path   = isset( $parsed['path'] ) ? $parsed['path'] : '/';

		// Query WordPress for the content.
		query_posts( "pagename={$path}" );

		if ( ! have_posts() ) {
			return new WP_REST_Response( [
				'success' => false,
				'error'   => 'Content not found',
			], 404 );
		}

		// Start output buffering to capture template output.
		ob_start();

		// Load appropriate template part.
		if ( is_single() ) {
			get_header();
			get_template_part( 'single' );
			get_footer();
		} elseif ( is_page() ) {
			get_header();
			get_template_part( 'page' );
			get_footer();
		} else {
			get_header();
			get_template_part( 'index' );
			get_footer();
		}

		$html = ob_get_clean();

		// Extract only the content container.
		preg_match( '/<div id="content-container"(.*?)>(.*)<\/div>\s*<!-- #content-container -->/s', $html, $matches );

		$content = $matches[0] ?? $html;

		return new WP_REST_Response( [
			'success' => true,
			'html'    => $content,
			'title'   => wp_get_document_title(),
		], 200 );
	}

	/**
	 * Verify REST API nonce.
	 * 
	 * @param WP_REST_Request $request Request object.
	 * @return bool Whether nonce is valid.
	 */
	public static function verify_nonce( $request ) {
		$nonce = $request->get_header( 'X-WP-Nonce' );
		return wp_verify_nonce( $nonce, 'wp_rest' );
	}

	/**
	 * Validate URL is internal.
	 * 
	 * @param string $url URL to validate.
	 * @return bool Whether URL is valid.
	 */
	public static function validate_url( $url ) {
		$home_url = home_url( '/' );
		return strpos( $url, $home_url ) === 0;
	}

	/**
	 * Validate template name against whitelist.
	 * 
	 * @param string $template Template name.
	 * @return bool Whether template is valid.
	 */
	public static function validate_template( $template ) {
		$allowed = [ 'index', 'single', 'page', 'archive', 'search', '404' ];
		return in_array( $template, $allowed, true );
	}
}

SpeedX_API::register();
