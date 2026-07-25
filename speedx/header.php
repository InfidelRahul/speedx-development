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
<div id="sx-progress-bar"></div>

<!-- Loading Indicator -->
<div id="sx-loader">
	<div class="spinner"></div>
</div>

<header class="site-header sx-surface-raised">
	<div class="header-inner">
		<div class="site-branding">
			<div class="brand-monogram">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} else {
					echo esc_html( substr( get_bloginfo( 'name' ), 0, 2 ) );
				}
				?>
			</div>
			<span class="site-title" style="font-family: var(--sx-font-heading); font-weight: 700; font-size: 1.25rem;">
				<?php bloginfo( 'name' ); ?>
			</span>
		</div>

		<nav class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'speedx' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'primary',
				'menu_class'     => '',
				'container'      => false,
				'depth'          => 1,
			] );
			?>
		</nav>

		<div class="header-actions">
			<?php get_search_form(); ?>
		</div>
	</div>
</header>

<div class="site-content" id="content-container">
