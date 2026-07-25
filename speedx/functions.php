<?php
/**
 * SpeedX Theme Bootstrap
 * 
 * Main entry point that loads all theme modules.
 * 
 * @package SpeedX
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SPEEDX_VERSION', '2.0.0' );
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
require_once SPEEDX_DIR . '/includes/class-speedx-search-widget.php';

// Load template helper functions.
require_once SPEEDX_DIR . '/template-functions.php';

// Initialize modules.
SpeedX_Setup::register();
SpeedX_Assets::register();
SpeedX_Customizer::register();
SpeedX_API::register();
SpeedX_Security::register();
