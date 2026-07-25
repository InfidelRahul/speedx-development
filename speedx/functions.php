<?php
/**
 * SpeedX Theme
 * 
 * A ultra-lightweight SPA WordPress theme with neumorphic design.
 * 
 * @package SpeedX
 * @version 1.0.0
 * @author SpeedX Team
 * @license GPL v2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly.
}

define( 'SPEEDX_VERSION', '1.0.0' );
define( 'SPEEDX_DIR', get_template_directory() );
define( 'SPEEDX_URI', get_template_directory_uri() );

/**
 * Autoload theme classes.
 */
require_once SPEEDX_DIR . '/includes/class-speedx-setup.php';
require_once SPEEDX_DIR . '/includes/class-speedx-assets.php';
require_once SPEEDX_DIR . '/includes/class-speedx-customizer.php';
require_once SPEEDX_DIR . '/includes/class-speedx-api.php';
require_once SPEEDX_DIR . '/includes/class-speedx-security.php';

/**
 * Load template helper functions.
 */
require_once SPEEDX_DIR . '/template-functions.php';
