<?php
/**
 * SpeedX Theme Functions
 * 
 * Ultra-lightweight SPA theme with neumorphic design
 * 
 * @package SpeedX
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

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
    
    // Switch default core markup for various elements to HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add support for custom background
    add_theme_support('custom-background');
    
    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add support for editor styles
    add_theme_support('editor-styles');
}
add_action('after_setup_theme', 'speedx_setup');

/**
 * Set content width
 */
function speedx_content_width() {
    $GLOBALS['content_width'] = apply_filters('speedx_content_width', 800);
}
add_action('after_setup_theme', 'speedx_content_width', 0);

/**
 * Register widget areas
 */
function speedx_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'speedx'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'speedx'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'speedx_widgets_init');

/**
 * Enqueue scripts and styles
 */
function speedx_scripts() {
    // Main stylesheet
    wp_enqueue_style('speedx-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // SPA Router - only if SPA is enabled
    if (get_theme_mod('speedx_spa_enabled', true)) {
        wp_enqueue_script(
            'speedx-router',
            get_template_directory_uri() . '/assets/js/router.js',
            array(),
            '1.0.0',
            true
        );
        
        // Pass AJAX URL and settings to JS
        wp_localize_script('speedx-router', 'speedxAjax', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('speedx/v1/fragment'),
            'nonce'   => wp_create_nonce('wp_rest'),
            'homeUrl' => home_url('/'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'speedx_scripts');

/**
 * Register REST API endpoint for fragment loading
 */
function speedx_register_rest_routes() {
    register_rest_route('speedx/v1', '/fragment', array(
        'methods'             => 'GET',
        'callback'            => 'speedx_get_fragment',
        'permission_callback' => '__return_true',
        'args'                => array(
            'path' => array(
                'required'          => true,
                'sanitize_callback' => 'sanitize_text_field',
                'validate_callback' => function($param) {
                    return !empty($param);
                },
            ),
        ),
    ));
}
add_action('rest_api_init', 'speedx_register_rest_routes');

/**
 * Get page fragment for SPA loading
 */
function speedx_get_fragment($request) {
    $path = $request->get_param('path');
    
    // Convert path to WordPress query
    $url = home_url($path);
    
    // Parse the URL to get query vars
    $parsed = parse_url($url);
    $path_part = isset($parsed['path']) ? $parsed['path'] : '/';
    
    // Set up WordPress query based on path
    global $wp;
    $wp->query_vars = array();
    
    // Handle different types of requests
    if ($path_part === '/' || $path_part === '') {
        // Home page
        $wp->query_vars['pagename'] = '';
    } elseif (preg_match('#^/page/(\d+)/?$#', $path_part, $matches)) {
        // Paginated posts
        $wp->query_vars['paged'] = intval($matches[1]);
    } else {
        // Remove leading/trailing slashes
        $clean_path = trim($path_part, '/');
        
        // Check if it's a post type
        $post_types = get_post_types(array('public' => true), 'objects');
        foreach ($post_types as $post_type) {
            if (strpos($clean_path, $post_type->rewrite['slug']) === 0) {
                $post_name = str_replace($post_type->rewrite['slug'] . '/', '', $clean_path);
                $wp->query_vars['post_type'] = $post_type->name;
                $wp->query_vars['name'] = $post_name;
                break;
            }
        }
        
        // If no post type matched, try as page or post
        if (empty($wp->query_vars)) {
            $wp->query_vars['name'] = $clean_path;
        }
    }
    
    // Run the query
    $wp->main_query();
    
    // Determine which template to load
    ob_start();
    
    if (is_404()) {
        locate_template('404.php', true);
    } elseif (is_singular()) {
        locate_template('singular.php', true);
    } elseif (is_home()) {
        locate_template('index.php', true);
    } elseif (is_page()) {
        locate_template('page.php', true);
    } elseif (is_archive()) {
        locate_template('archive.php', true);
    } elseif (is_search()) {
        locate_template('search.php', true);
    } else {
        locate_template('index.php', true);
    }
    
    $content = ob_get_clean();
    
    // Get the title
    ob_start();
    wp_title('|', false);
    $title = ob_get_clean();
    if (empty($title)) {
        $title = get_bloginfo('name');
    }
    
    return new WP_REST_Response(array(
        'success' => true,
        'content' => $content,
        'title'   => $title,
    ), 200);
}

/**
 * Customizer Settings
 */
function speedx_customize_register($wp_customize) {
    // SPA Settings Section
    $wp_customize->add_section('speedx_spa_settings', array(
        'title'    => __('SPA Settings', 'speedx'),
        'priority' => 30,
    ));
    
    // Enable/Disable SPA
    $wp_customize->add_setting('speedx_spa_enabled', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('speedx_spa_enabled', array(
        'label'   => __('Enable SPA Navigation', 'speedx'),
        'section' => 'speedx_spa_settings',
        'type'    => 'checkbox',
    ));
    
    // Loading Animation
    $wp_customize->add_setting('speedx_loading_animation', array(
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ));
    
    $wp_customize->add_control('speedx_loading_animation', array(
        'label'   => __('Show Loading Animation', 'speedx'),
        'section' => 'speedx_spa_settings',
        'type'    => 'checkbox',
    ));
    
    // Transition Speed
    $wp_customize->add_setting('speedx_transition_speed', array(
        'default'           => 400,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('speedx_transition_speed', array(
        'label'       => __('Transition Speed (ms)', 'speedx'),
        'section'     => 'speedx_spa_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 100,
            'max'  => 1000,
            'step' => 50,
        ),
    ));
}
add_action('customize_register', 'speedx_customize_register');

/**
 * Performance Optimizations
 */
function speedx_performance_optimizations() {
    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');
    
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove REST API links from head (we use it internally)
    remove_action('wp_head', 'rest_output_link_wp_head');
    
    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    
    // Disable XML-RPC
    add_filter('xmlrpc_enabled', '__return_false');
    
    // Remove jQuery Migrate
    wp_deregister_script('jquery-migrate');
    
    // Dequeue block library CSS if not using Gutenberg
    if (!is_admin()) {
        wp_dequeue_style('wp-block-library');
        wp_dequeue_style('wp-block-library-theme');
    }
}
add_action('init', 'speedx_performance_optimizations');

/**
 * Add resource hints for performance
 */
function speedx_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'speedx_resource_hints', 10, 2);

/**
 * Excerpt length
 */
function speedx_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'speedx_excerpt_length');

/**
 * Excerpt more
 */
function speedx_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'speedx_excerpt_more');

/**
 * Body classes
 */
function speedx_body_classes($classes) {
    // Add class if SPA is enabled
    if (get_theme_mod('speedx_spa_enabled', true)) {
        $classes[] = 'spa-enabled';
    }
    
    // Add singular class
    if (is_singular()) {
        $classes[] = 'singular';
    }
    
    return $classes;
}
add_filter('body_class', 'speedx_body_classes');

/**
 * Preload key resources
 */
function speedx_preload_resources() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
}
add_action('wp_head', 'speedx_preload_resources', 1);
