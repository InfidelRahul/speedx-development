<?php
/**
 * SpeedX Theme Functions
 * Ultra-lightweight SPA theme with zero dependencies
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define theme constants
define('SPEEDX_VERSION', '1.0.0');
define('SPEEDX_DIR', get_template_directory());
define('SPEEDX_URI', get_template_directory_uri());
define('SPEEDX_ASSETS_URI', SPEEDX_URI . '/assets');

/**
 * Theme Setup
 */
function speedx_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'speedx'),
        'footer'  => __('Footer Menu', 'speedx'),
    ));

    // HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Custom logo support
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Custom background support
    add_theme_support('custom-background');

    // Responsive embeds
    add_theme_support('responsive-embeds');

    // Align wide and full blocks
    add_theme_support('align-wide');

    // Editor styles
    add_theme_support('editor-styles');
}
add_action('after_setup_theme', 'speedx_setup');

/**
 * Set content width
 */
function speedx_content_width() {
    $GLOBALS['content_width'] = apply_filters('speedx_content_width', 1200);
}
add_action('after_setup_theme', 'speedx_content_width', 0);

/**
 * Enqueue scripts and styles
 */
function speedx_scripts() {
    // Main stylesheet - loaded in head for critical rendering path
    wp_enqueue_style('speedx-style', get_stylesheet_uri(), array(), SPEEDX_VERSION);

    // SPA Router - vanilla JS, no dependencies, deferred to footer
    wp_enqueue_script('speedx-router', 
        SPEEDX_ASSETS_URI . '/js/router.js', 
        array(), 
        SPEEDX_VERSION, 
        true
    );

    // Localize script with config
    wp_localize_script('speedx-router', 'speedxConfig', array(
        'ajaxUrl'      => admin_url('admin-ajax.php'),
        'restUrl'      => esc_url_raw(rest_url()),
        'nonce'        => wp_create_nonce('wp_rest'),
        'homeUrl'      => home_url('/'),
        'siteName'     => get_bloginfo('name'),
        'spaEnabled'   => get_theme_mod('speedx_enable_spa', true),
        'loadingType'  => get_theme_mod('speedx_loading_animation', 'bar'),
        'transitionSpeed' => (float) get_theme_mod('speedx_transition_speed', 0.3),
    ));

    // Remove emoji scripts for performance
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove WordPress default scripts we don't need
    wp_deregister_script('jquery');
    wp_deregister_script('jquery-migrate');
    
    // Remove block library CSS if not using Gutenberg blocks heavily
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
}
add_action('wp_enqueue_scripts', 'speedx_scripts');

/**
 * Inline critical CSS for faster FCP
 */
function speedx_inline_critical_css() {
    ?>
    <style id="speedx-critical-css">
        /* Critical above-the-fold CSS only */
        body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;margin:0;line-height:1.6}
        .site-wrapper{min-height:100vh;display:flex;flex-direction:column}
        .site-header{position:sticky;top:0;background:#fff;border-bottom:1px solid #e5e7eb;padding:1rem 0;z-index:100}
        .container{max-width:1200px;margin:0 auto;padding:0 1.5rem}
        .main-navigation ul{list-style:none;display:flex;gap:1.5rem}
        .main-navigation a{text-decoration:none;color:#1f2937;font-weight:500}
        .site-content{flex:1;padding:2rem 0}
    </style>
    <?php
}
add_action('wp_head', 'speedx_inline_critical_css', 1);

/**
 * Add preload hints for performance
 */
function speedx_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type || 'dns-prefetch' === $relation_type) {
        $urls[] = array(
            'href' => get_template_directory_uri(),
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'speedx_resource_hints', 10, 2);

/**
 * Register widget areas
 */
function speedx_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'speedx'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'speedx'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer', 'speedx'),
        'id'            => 'footer-1',
        'description'   => __('Footer widgets.', 'speedx'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'speedx_widgets_init');

/**
 * Custom excerpt length
 */
function speedx_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'speedx_excerpt_length');

/**
 * Custom excerpt more
 */
function speedx_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'speedx_excerpt_more');

/**
 * Add body classes for SPA
 */
function speedx_body_classes($classes) {
    $classes[] = 'speedx-theme';
    
    if (is_singular()) {
        $classes[] = 'singular';
    } else {
        $classes[] = 'archive';
    }
    
    return $classes;
}
add_filter('body_class', 'speedx_body_classes');

/**
 * Preload key resources
 */
function speedx_preload_resources() {
    echo '<link rel="preconnect" href="' . esc_url(get_template_directory_uri()) . '">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
}
add_action('wp_head', 'speedx_preload_resources', 2);

/**
 * Remove unnecessary WordPress head elements for performance and security
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');

/**
 * Disable XML-RPC to prevent brute force attacks
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Custom template for SPA fragment requests via AJAX
 */
function speedx_ajax_load_fragment() {
    if (!isset($_GET['template']) || !isset($_GET['path'])) {
        wp_send_json_error(array('message' => 'Missing parameters'), 400);
    }

    $template = sanitize_text_field($_GET['template']);
    $path     = sanitize_text_field($_GET['path']);
    
    // Parse the path to set up WordPress query
    $path = str_replace(home_url('/'), '', $path);
    $path = '/' . trim($path, '/');
    
    // Set up the query based on path
    $_SERVER['REQUEST_URI'] = $path;
    
    // Load the appropriate template
    locate_template($template . '.php', true);
    
    wp_die();
}
add_action('wp_ajax_speedx_load_fragment', 'speedx_ajax_load_fragment');
add_action('wp_ajax_nopriv_speedx_load_fragment', 'speedx_ajax_load_fragment');

/**
 * REST API endpoint for content fragments
 */
function speedx_register_rest_routes() {
    register_rest_route('speedx/v1', '/fragment', array(
        'methods'             => 'GET',
        'callback'            => 'speedx_rest_fragment_callback',
        'permission_callback' => '__return_true',
        'args'                => array(
            'template' => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => 'speedx_validate_template',
            ),
            'path'     => array(
                'required'          => true,
                'type'              => 'string',
                'sanitize_callback' => 'esc_url_raw',
            ),
        ),
    ));
}
add_action('rest_api_init', 'speedx_register_rest_routes');

/**
 * Validate template name against allowed templates
 */
function speedx_validate_template($template, $request, $param) {
    $allowed_templates = array(
        'index',
        'single',
        'page',
        'archive',
        'category',
        'tag',
        'author',
        'date',
        'search',
        '404',
    );
    
    return in_array($template, $allowed_templates, true);
}

/**
 * REST API callback for fragment loading
 */
function speedx_rest_fragment_callback($request) {
    $template = $request->get_param('template');
    $path     = $request->get_param('path');
    
    if (!$template || !$path) {
        return new WP_Error('missing_params', 'Missing template or path', array('status' => 400));
    }
    
    // Setup WordPress environment for the requested path
    $path = str_replace(home_url('/'), '', $path);
    $_SERVER['REQUEST_URI'] = '/' . trim($path, '/');
    
    // Capture template output
    ob_start();
    locate_template($template . '.php', true);
    $content = ob_get_clean();
    
    return new WP_REST_Response(array(
        'content' => $content,
        'title'   => wp_get_document_title(),
    ), 200);
}

/**
 * Theme Customizer Settings
 */
function speedx_customize_register($wp_customize) {
    // Colors Section
    $wp_customize->add_section('speedx_colors', array(
        'title'    => __('SpeedX Colors', 'speedx'),
        'priority' => 30,
    ));

    // Primary Color
    $wp_customize->add_setting('speedx_primary_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'speedx_primary_color', array(
        'label'    => __('Primary Color', 'speedx'),
        'section'  => 'speedx_colors',
        'settings' => 'speedx_primary_color',
    )));

    // SPA Settings Section
    $wp_customize->add_section('speedx_spa', array(
        'title'    => __('SPA Settings', 'speedx'),
        'priority' => 35,
    ));

    // Enable/Disable SPA
    $wp_customize->add_setting('speedx_enable_spa', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));

    $wp_customize->add_control('speedx_enable_spa', array(
        'label'       => __('Enable SPA Mode', 'speedx'),
        'description' => __('Load pages without full refresh', 'speedx'),
        'section'     => 'speedx_spa',
        'type'        => 'checkbox',
    ));

    // Loading Animation
    $wp_customize->add_setting('speedx_loading_animation', array(
        'default'           => 'bar',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('speedx_loading_animation', array(
        'label'   => __('Loading Animation', 'speedx'),
        'section' => 'speedx_spa',
        'type'    => 'select',
        'choices' => array(
            'bar'     => __('Progress Bar', 'speedx'),
            'spinner' => __('Spinner', 'speedx'),
            'none'    => __('None', 'speedx'),
        ),
    ));

    // Transition Speed
    $wp_customize->add_setting('speedx_transition_speed', array(
        'default'           => '0.3',
        'sanitize_callback' => 'speedx_sanitize_transition_speed',
    ));

    $wp_customize->add_control('speedx_transition_speed', array(
        'label'       => __('Transition Speed (seconds)', 'speedx'),
        'section'     => 'speedx_spa',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 0,
            'max'  => 2,
            'step' => 0.1,
        ),
    ));
}
add_action('customize_register', 'speedx_customize_register');

/**
 * Sanitize transition speed value
 */
function speedx_sanitize_transition_speed($value) {
    $float_val = (float) $value;
    return max(0, min(2, $float_val));
}

/**
 * Output customizer CSS
 */
function speedx_customizer_css() {
    $primary_color = get_theme_mod('speedx_primary_color', '#2563eb');
    $transition_speed = get_theme_mod('speedx_transition_speed', '0.3');
    
    if ($primary_color !== '#2563eb' || $transition_speed !== '0.3') {
        echo '<style>';
        if ($primary_color !== '#2563eb') {
            echo ':root{--primary-color:' . esc_attr($primary_color) . '}';
        }
        if ($transition_speed !== '0.3') {
            echo ':root{--transition-speed:' . esc_attr($transition_speed) . 's}';
        }
        echo '</style>';
    }
}
add_action('wp_head', 'speedx_customizer_css', 100);
