<?php
/**
 * SpeedX Header Template
 * 
 * Displays the site header with navigation.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Reading Progress Bar -->
<div class="reading-progress" id="sx-progress-bar"></div>

<!-- Loading Indicator -->
<div id="sx-loader" aria-hidden="true">
<div class="spinner"></div>
</div>

<header class="site-header sx-surface-raised">
<div class="header-inner">
<!-- Brand -->
<div class="site-branding">
<div class="brand-monogram sx-surface-pressed">
<?php
if ( has_custom_logo() ) {
the_custom_logo();
} else {
echo esc_html( substr( get_bloginfo( 'name' ), 0, 2 ) );
}
?>
</div>
<span class="site-title" style="font-family: var(--sx-font-heading); font-weight: 700; font-size: 1.25rem; margin-left: 0.75rem;">
<?php bloginfo( 'name' ); ?>
</span>
</div>

<!-- Primary Navigation (Desktop only - hidden on mobile via CSS) -->
<nav class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'speedx' ); ?>">
<?php
wp_nav_menu( [
'theme_location' => 'primary',
'menu_class'     => 'primary-menu',
'container'      => false,
'depth'          => 1,
] );
?>
</nav>

<!-- Mobile Hamburger Button (visible only on mobile via CSS) -->
<button class="hamburger-btn" id="hamburger-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'speedx' ); ?>" aria-expanded="false" aria-controls="mobile-nav-drawer">
<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<line x1="3" y1="6" x2="21" y2="6"></line>
<line x1="3" y1="12" x2="21" y2="12"></line>
<line x1="3" y1="18" x2="21" y2="18"></line>
</svg>
</button>
</div>
</header>

<!-- Mobile Navigation Drawer (hidden by default, shown on mobile when active) -->
<div class="mobile-nav-drawer" id="mobile-nav-drawer" aria-hidden="true" aria-label="<?php esc_attr_e( 'Mobile Menu', 'speedx' ); ?>">
<div class="mobile-nav-inner">
<?php
wp_nav_menu( [
'theme_location' => 'primary',
'menu_class'     => 'mobile-primary-menu',
'container'      => false,
'depth'          => 1,
] );
?>
</div>

<main class="site-content" id="content-container">
