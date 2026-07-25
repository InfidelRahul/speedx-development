<?php
/**
 * SpeedX Customizer
 * 
 * Handles theme customization panel with live preview.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SpeedX_Customizer {

	/**
	 * Register customizer hooks.
	 */
	public static function register() {
		add_action( 'customize_register', [ __CLASS__, 'register_settings' ] );
		add_action( 'customize_preview_init', [ __CLASS__, 'enqueue_preview_js' ] );
	}

	/**
	 * Register customizer settings and controls.
	 * 
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public static function register_settings( $wp_customize ) {
		
		// === SPA & Interaction Panel ===
		$wp_customize->add_panel( 'speedx_spa_panel', [
			'title'       => esc_html__( 'SPA & Interaction', 'speedx' ),
			'description' => esc_html__( 'Configure single-page application behavior and transitions.', 'speedx' ),
			'priority'    => 30,
		] );

		// Enable/Disable SPA
		$wp_customize->add_setting( 'speedx_spa_enabled', [
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'postMessage',
		] );

		$wp_customize->add_control( 'speedx_spa_enabled', [
			'type'        => 'checkbox',
			'label'       => esc_html__( 'Enable SPA Navigation', 'speedx' ),
			'description' => esc_html__( 'Load pages without refresh using AJAX.', 'speedx' ),
			'section'     => 'speedx_spa_panel',
		] );

		// Transition Effect
		$wp_customize->add_setting( 'speedx_transition_effect', [
			'default'           => 'fade',
			'sanitize_callback' => 'sanitize_text_field',
		] );

		$wp_customize->add_control( 'speedx_transition_effect', [
			'type'    => 'select',
			'label'   => esc_html__( 'Transition Effect', 'speedx' ),
			'section' => 'speedx_spa_panel',
			'choices' => [
				'fade'  => esc_html__( 'Fade', 'speedx' ),
				'slide' => esc_html__( 'Slide', 'speedx' ),
				'zoom'  => esc_html__( 'Zoom', 'speedx' ),
			],
		] );

		// Transition Speed
		$wp_customize->add_setting( 'speedx_transition_speed', [
			'default'           => 300,
			'sanitize_callback' => 'absint',
		] );

		$wp_customize->add_control( 'speedx_transition_speed', [
			'type'              => 'range',
			'label'             => esc_html__( 'Transition Speed (ms)', 'speedx' ),
			'section'           => 'speedx_spa_panel',
			'input_attrs'       => [
				'min'  => 100,
				'max'  => 1000,
				'step' => 50,
			],
		] );

		// Show Loading Animation
		$wp_customize->add_setting( 'speedx_show_loader', [
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
		] );

		$wp_customize->add_control( 'speedx_show_loader', [
			'type'    => 'checkbox',
			'label'   => esc_html__( 'Show Loading Animation', 'speedx' ),
			'section' => 'speedx_spa_panel',
		] );

		// === Neumorphic Design Panel ===
		$wp_customize->add_panel( 'speedx_design_panel', [
			'title'    => esc_html__( 'Neumorphic Design', 'speedx' ),
			'priority' => 35,
		] );

		// Shadow Depth
		$wp_customize->add_setting( 'speedx_shadow_depth', [
			'default'           => 18,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		] );

		$wp_customize->add_control( 'speedx_shadow_depth', [
			'type'              => 'range',
			'label'             => esc_html__( 'Shadow Depth (px)', 'speedx' ),
			'section'           => 'speedx_design_panel',
			'input_attrs'       => [
				'min'  => 8,
				'max'  => 30,
				'step' => 2,
			],
		] );

		// Corner Radius
		$wp_customize->add_setting( 'speedx_corner_radius', [
			'default'           => 22,
			'sanitize_callback' => 'absint',
			'transport'         => 'postMessage',
		] );

		$wp_customize->add_control( 'speedx_corner_radius', [
			'type'              => 'range',
			'label'             => esc_html__( 'Corner Radius (px)', 'speedx' ),
			'section'           => 'speedx_design_panel',
			'input_attrs'       => [
				'min'  => 0,
				'max'  => 40,
				'step' => 2,
			],
		] );

		// === Colors Panel (Native) ===
		$wp_customize->add_setting( 'speedx_accent_color', [
			'default'           => '#0D9488',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		] );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'speedx_accent_color',
			[
				'label'   => esc_html__( 'Accent Color', 'speedx' ),
				'section' => 'colors',
			]
		) );
	}

	/**
	 * Enqueue customizer preview JavaScript.
	 */
	public static function enqueue_preview_js() {
		wp_enqueue_script(
			'speedx-customizer-preview',
			SPEEDX_URI . '/assets/js/customizer-preview.js',
			[ 'customize-preview' ],
			SPEEDX_VERSION,
			true
		);
	}
}

SpeedX_Customizer::register();
