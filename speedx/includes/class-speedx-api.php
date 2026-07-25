<?php
/**
 * SpeedX REST API
 * 
 * Registers custom REST API endpoints for SPA fragment loading.
 * Validates templates and returns sanitized HTML.
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SpeedX_API {

	/**
	 * Allowed templates for fragment loading.
	 */
	private $allowed_templates = array(
		'index',
		'single',
		'page',
		'archive',
		'search',
		'404',
	);

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST API routes.
	 */
	public function register_routes() {
		register_rest_route( 'speedx/v1', '/fragment', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_fragment' ),
			'permission_callback' => '__return_true',
			'args'                => array(
				'url' => array(
					'required'          => true,
					'validate_callback' => array( $this, 'validate_url' ),
					'sanitize_callback' => 'esc_url_raw',
				),
				'template' => array(
					'required'          => false,
					'default'           => 'index',
					'validate_callback' => array( $this, 'validate_template' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
		) );
	}

	/**
	 * Get HTML fragment for a given URL.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_fragment( $request ) {
		$url      = $request->get_param( 'url' );
		$template = $request->get_param( 'template' );

		// Parse URL to get query vars.
		$parsed_url = parse_url( $url );
		$path       = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '/';
		
		// Simulate WordPress query based on path.
		$this->simulate_query( $path );

		// Start output buffering.
		ob_start();

		// Load appropriate template part.
		if ( is_404() ) {
			get_template_part( '404' );
		} elseif ( is_search() ) {
			get_template_part( 'search' );
		} elseif ( is_single() ) {
			get_template_part( 'single' );
		} elseif ( is_page() ) {
			get_template_part( 'page' );
		} elseif ( is_archive() ) {
			get_template_part( 'archive' );
		} else {
			get_template_part( 'index' );
		}

		$content = ob_get_clean();

		return rest_ensure_response( array(
			'success' => true,
			'content' => $content,
			'title'   => wp_get_document_title(),
		) );
	}

	/**
	 * Simulate WordPress query based on URL path.
	 *
	 * @param string $path URL path.
	 */
	private function simulate_query( $path ) {
		global $wp_query;

		// Reset query.
		wp_reset_query();

		// Basic path matching logic.
		if ( strpos( $path, '/search/' ) !== false || isset( $_GET['s'] ) ) {
			$wp_query->is_search = true;
		} elseif ( preg_match( '#/\d{4}/\d{2}/#', $path ) ) {
			$wp_query->is_date = true;
		} elseif ( strpos( $path, '/category/' ) !== false ) {
			$wp_query->is_category = true;
		} elseif ( strpos( $path, '/tag/' ) !== false ) {
			$wp_query->is_tag = true;
		} elseif ( preg_match( '#^/[^/]+/?$#', $path ) && ! is_numeric( basename( $path ) ) ) {
			// Check if page exists.
			$page = get_page_by_path( trim( $path, '/' ) );
			if ( $page ) {
				$wp_query->is_page = true;
				$wp_query->queried_object = $page;
			} else {
				$wp_query->is_404 = true;
			}
		} else {
			// Default to posts index or single.
			if ( is_numeric( basename( $path ) ) ) {
				$post_id = absint( basename( $path ) );
				$post = get_post( $post_id );
				if ( $post ) {
					$wp_query->is_single = true;
					$wp_query->queried_object = $post;
				} else {
					$wp_query->is_404 = true;
				}
			} else {
				$wp_query->is_home = true;
			}
		}
	}

	/**
	 * Validate URL is internal.
	 *
	 * @param string $url URL to validate.
	 * @return bool
	 */
	public function validate_url( $url ) {
		$home_url = home_url( '/' );
		return strpos( $url, $home_url ) === 0;
	}

	/**
	 * Validate template is allowed.
	 *
	 * @param string $template Template name.
	 * @return bool
	 */
	public function validate_template( $template ) {
		return in_array( $template, $this->allowed_templates, true );
	}
}

return new SpeedX_API();
