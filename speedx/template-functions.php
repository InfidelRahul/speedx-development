<?php
/**
 * Template functions and helpers for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display SVG icons for the theme
 * 
 * @param string $icon_name Name of the icon to display.
 * @return void
 */
function speedx_display_icon($icon_name) {
    $icons = array(
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
        'menu'   => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
        'close'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>',
    );

    if (isset($icons[$icon_name])) {
        echo $icons[$icon_name];
    }
}

/**
 * Get theme color scheme from Customizer
 * 
 * @return array Color values
 */
function speedx_get_color_scheme() {
    return array(
        'bg_color'     => get_theme_mod('speedx_bg_color', '#e0e5ec'),
        'text_main'    => get_theme_mod('speedx_text_main', '#4a5568'),
        'accent'       => get_theme_mod('speedx_accent_color', '#3b82f6'),
        'shadow_light' => get_theme_mod('speedx_shadow_light', '#ffffff'),
        'shadow_dark'  => get_theme_mod('speedx_shadow_dark', '#a3b1c6'),
    );
}

/**
 * Output critical inline CSS for faster FCP
 * 
 * @return void
 */
function speedx_critical_css() {
    ?>
    <style>
        .critical-loaded{opacity:1;transition:opacity .3s}
        #spa-loader{display:none}
    </style>
    <?php
}
add_action('wp_head', 'speedx_critical_css', 1);

/**
 * Add async/defer attributes to scripts
 * 
 * @param string $tag    The script tag.
 * @param string $handle The script handle.
 * @return string Modified script tag
 */
function speedx_script_async_defer($tag, $handle) {
    $async_handles = apply_filters('speedx_async_scripts', array());
    $defer_handles = apply_filters('speedx_defer_scripts', array('speedx-router'));

    if (in_array($handle, $async_handles, true)) {
        return str_replace(' src', ' async="async" src', $tag);
    }

    if (in_array($handle, $defer_handles, true)) {
        return str_replace(' src', ' defer="defer" src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'speedx_script_async_defer', 10, 2);

/**
 * Preload critical resources
 * 
 * @return void
 */
function speedx_preload_resources() {
    $router_url = get_template_directory_uri() . '/assets/js/router.js';
    echo '<link rel="preload" href="' . esc_url($router_url) . '" as="script">' . "\n";
}
add_action('wp_head', 'speedx_preload_resources', 2);

/**
 * Generate neumorphic shadow CSS based on settings
 * 
 * @param string $type Shadow type: 'raised', 'pressed', or 'flat'.
 * @param int    $intensity Shadow intensity (1-3).
 * @return string CSS box-shadow value
 */
function speedx_get_neu_shadow($type = 'raised', $intensity = 2) {
    $colors = speedx_get_color_scheme();
    $multiplier = $intensity * 4;

    switch ($type) {
        case 'pressed':
            return "inset {$multiplier}px {$multiplier}px " . ($multiplier * 2) . "px {$colors['shadow_dark']}, inset -{$multiplier}px -{$multiplier}px " . ($multiplier * 2) . "px {$colors['shadow_light']}";
        case 'flat':
            return 'none';
        case 'raised':
        default:
            return "{$multiplier}px {$multiplier}px " . ($multiplier * 2) . "px {$colors['shadow_dark']}, -{$multiplier}px -{$multiplier}px " . ($multiplier * 2) . "px {$colors['shadow_light']}";
    }
}
