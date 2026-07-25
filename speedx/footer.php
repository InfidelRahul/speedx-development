<?php
/**
 * SpeedX Footer Template
 * 
 * Displays the site footer.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</div><!-- #content-container -->

<footer class="site-footer sx-surface-raised">
	<div class="footer-inner">
		<div class="footer-branding">
			<h4><?php bloginfo( 'name' ); ?></h4>
			<p class="meta-text"><?php bloginfo( 'description' ); ?></p>
		</div>

		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Menu', 'speedx' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location' => 'footer',
				'menu_class'     => '',
				'container'      => false,
				'depth'          => 1,
			] );
			?>
		</nav>

		<div class="footer-social">
			<?php
			wp_nav_menu( [
				'theme_location' => 'social',
				'menu_class'     => '',
				'container'      => false,
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
			] );
			?>
		</div>
	</div>

	<div class="copyright-row">
		<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
