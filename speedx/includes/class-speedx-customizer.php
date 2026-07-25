<?php
/**
 * SpeedX Customizer
 * 
 * Registers theme options in the WordPress Customizer.
 * Includes SPA settings, Neumorphic design controls, and Color palette.
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SpeedX_Customizer {

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'wp_head', array( $this, 'output_custom_css' ) );
	}

	/**
	 * Register all customizer settings and controls.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	public function register_settings( $wp_customize ) {
		
		// --- Section: SPA & Interaction ---
		$wp_customize->add_section( 'speedx_spa_section', array(
			'title'    => __( 'SPA & Interaction', 'speedx' ),
			'priority' => 30,
		) );

		// Enable/Disable SPA
		$wp_customize->add_setting( 'spa_enabled', array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'refresh',
		) );
		$wp_customize->add_control( 'spa_enabled', array(
			'label'   => __( 'Enable SPA Navigation', 'speedx' ),
			'section' => 'speedx_spa_section',
			'type'    => 'checkbox',
		) );

		// Transition Effect
		$wp_customize->add_setting( 'transition_effect', array(
			'default'           => 'fade',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'transition_effect', array(
			'label'   => __( 'Transition Effect', 'speedx' ),
			'section' => 'speedx_spa_section',
			'type'    => 'select',
			'choices' => array(
				'fade'  => __( 'Fade', 'speedx' ),
				'slide' => __( 'Slide', 'speedx' ),
				'zoom'  => __( 'Zoom', 'speedx' ),
			),
		) );

		// Transition Speed
		$wp_customize->add_setting( 'transition_speed', array(
			'default'           => 300,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'transition_speed', array(
			'label'   => __( 'Transition Speed (ms)', 'speedx' ),
			'section' => 'speedx_spa_section',
			'type'    => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 1000,
				'step' => 50,
			),
		) );

		// Loading Animation
		$wp_customize->add_setting( 'loading_effect', array(
			'default'           => 'spinner',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( 'loading_effect', array(
			'label'   => __( 'Loading Animation', 'speedx' ),
			'section' => 'speedx_spa_section',
			'type'    => 'select',
			'choices' => array(
				'spinner' => __( 'Spinner', 'speedx' ),
				'bar'     => __( 'Progress Bar', 'speedx' ),
				'none'    => __( 'None', 'speedx' ),
			),
		) );

		// --- Section: Neumorphic Design ---
		$wp_customize->add_section( 'speedx_neumorph_section', array(
			'title'    => __( 'Neumorphic Design', 'speedx' ),
			'priority' => 35,
		) );

		// Shadow Depth
		$wp_customize->add_setting( 'shadow_depth', array(
			'default'           => 10,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'shadow_depth', array(
			'label'   => __( 'Shadow Depth (px)', 'speedx' ),
			'section' => 'speedx_neumorph_section',
			'type'    => 'range',
			'input_attrs' => array(
				'min'  => 2,
				'max'  => 30,
				'step' => 1,
			),
		) );

		// Corner Radius
		$wp_customize->add_setting( 'corner_radius', array(
			'default'           => 20,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'corner_radius', array(
			'label'   => __( 'Corner Radius (px)', 'speedx' ),
			'section' => 'speedx_neumorph_section',
			'type'    => 'range',
			'input_attrs' => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
		) );

		// Content Pop Depth
		$wp_customize->add_setting( 'content_pop', array(
			'default'           => 15,
			'sanitize_callback' => 'absint',
		) );
		$wp_customize->add_control( 'content_pop', array(
			'label'   => __( 'Content Pop Depth', 'speedx' ),
			'section' => 'speedx_neumorph_section',
			'type'    => 'range',
			'input_attrs' => array(
				'min'  => 5,
				'max'  => 40,
				'step' => 1,
			),
		) );

		// --- Section: Colors ---
		$wp_customize->add_section( 'speedx_colors_section', array(
			'title'    => __( 'Color Palette', 'speedx' ),
			'priority' => 40,
		) );

		// Background Color
		$wp_customize->add_setting( 'color_bg', array(
			'default'           => '#e3e7eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_bg', array(
			'label'   => __( 'Background Color', 'speedx' ),
			'section' => 'speedx_colors_section',
		) ) );

		// Text Color
		$wp_customize->add_setting( 'color_text', array(
			'default'           => '#1f2937',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_text', array(
			'label'   => __( 'Text Color', 'speedx' ),
			'section' => 'speedx_colors_section',
		) ) );

		// Accent Color
		$wp_customize->add_setting( 'color_accent', array(
			'default'           => '#2563eb',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_accent', array(
			'label'   => __( 'Accent Color', 'speedx' ),
			'section' => 'speedx_colors_section',
		) ) );

		// Shadow Color
		$wp_customize->add_setting( 'color_shadow', array(
			'default'           => '#aab6c4',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_shadow', array(
			'label'   => __( 'Shadow Color', 'speedx' ),
			'section' => 'speedx_colors_section',
		) ) );
	}

	/**
	 * Output dynamic CSS based on customizer settings.
	 */
	public function output_custom_css() {
		$bg      = get_theme_mod( 'color_bg', '#e3e7eb' );
		$text    = get_theme_mod( 'color_text', '#1f2937' );
		$accent  = get_theme_mod( 'color_accent', '#2563eb' );
		$shadow  = get_theme_mod( 'color_shadow', '#aab6c4' );
		$depth   = absint( get_theme_mod( 'shadow_depth', 10 ) );
		$radius  = absint( get_theme_mod( 'corner_radius', 20 ) );
		$pop     = absint( get_theme_mod( 'content_pop', 15 ) );

		$light_shadow = $this->adjust_brightness( $bg, 20 );
		$dark_shadow  = $shadow;

		echo "<style type='text/css'>\n";
		echo ":root {\n";
		echo "  --speedx-bg: {$bg};\n";
		echo "  --speedx-text: {$text};\n";
		echo "  --speedx-accent: {$accent};\n";
		echo "  --speedx-shadow-light: {$light_shadow};\n";
		echo "  --speedx-shadow-dark: {$dark_shadow};\n";
		echo "  --speedx-radius: {$radius}px;\n";
		echo "  --speedx-depth: {$depth}px;\n";
		echo "  --speedx-pop: {$pop}px;\n";
		echo "}\n";
		echo "</style>\n";
	}

	/**
	 * Adjust hex color brightness.
	 *
	 * @param string $hex Hex color code.
	 * @param int    $steps Brightness adjustment (-255 to 255).
	 * @return string Adjusted hex color.
	 */
	private function adjust_brightness( $hex, $steps ) {
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}

		$r = max( 0, min( 255, $r + $steps ) );
		$g = max( 0, min( 255, $g + $steps ) );
		$b = max( 0, min( 255, $b + $steps ) );

		return sprintf( '#%02x%02x%02x', $r, $g, $b );
	}
}

return new SpeedX_Customizer();
