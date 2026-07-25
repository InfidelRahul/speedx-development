<?php
/**
 * SpeedX Template Functions
 * 
 * Global helper functions for templates.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display reading time for a post.
 * 
 * @param int $post_id Optional. Post ID. Default is current post.
 * @return string Formatted reading time.
 */
function speedx_reading_time( $post_id = null ) {
	$content = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Average 200 words per minute.
	
	return sprintf(
		esc_html( _n( '%d min read', '%d min read', $reading_time, 'speedx' ) ),
		$reading_time
	);
}

/**
 * Get category color based on slug or name.
 * 
 * @param string $category_slug Category slug.
 * @return string CSS color value.
 */
function speedx_get_category_color( $category_slug ) {
	$colors = [
		'design'       => 'var(--sx-cat-design)',
		'development'  => 'var(--sx-cat-dev)',
		'writing'      => 'var(--sx-cat-writing)',
		'productivity' => 'var(--sx-cat-productivity)',
	];
	
	return $colors[ $category_slug ] ?? 'var(--sx-cat-design)';
}

/**
 * Display author monogram initials.
 * 
 * @param int $user_id User ID. Default is current user.
 * @return string Initials.
 */
function speedx_author_monogram( $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_the_author_meta( 'ID' );
	}
	
	$first_name = get_the_author_meta( 'first_name', $user_id );
	$last_name  = get_the_author_meta( 'last_name', $user_id );
	
	if ( empty( $first_name ) && empty( $last_name ) ) {
		$display_name = get_the_author_meta( 'display_name', $user_id );
		$parts = explode( ' ', $display_name );
		$initials = strtoupper( substr( $parts[0], 0, 1 ) . substr( end( $parts ), 0, 1 ) );
	} else {
		$initials = strtoupper( substr( $first_name, 0, 1 ) . substr( $last_name, 0, 1 ) );
	}
	
	return esc_html( $initials );
}

/**
 * Check if current page is being loaded via SPA navigation.
 * 
 * @return bool True if SPA request.
 */
function speedx_is_spa_request() {
	return isset( $_SERVER['HTTP_X_SPEEDX_SPA'] ) && $_SERVER['HTTP_X_SPEEDX_SPA'] === 'true';
}

/**
 * Output SVG icon by name.
 * 
 * @param string $icon_name Icon name.
 * @param array  $attrs     SVG attributes.
 */
function speedx_icon( $icon_name, $attrs = [] ) {
	$icons = [
		'search'   => '<circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line>',
		'heart'    => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>',
		'comment'  => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>',
		'bookmark' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>',
		'share'    => '<circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>',
		'grid'     => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
		'list'     => '<line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line>',
	];
	
	if ( ! isset( $icons[ $icon_name ] ) ) {
		return;
	}
	
	$default_attrs = [
		'width'       => '24',
		'height'      => '24',
		'viewBox'     => '0 0 24 24',
		'fill'        => 'none',
		'stroke'      => 'currentColor',
		'stroke-width'=> '2',
	];
	
	$final_attrs = array_merge( $default_attrs, $attrs );
	$attr_string = '';
	foreach ( $final_attrs as $key => $value ) {
		$attr_string .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}
	
	echo '<svg' . $attr_string . '>' . $icons[ $icon_name ] . '</svg>';
}
