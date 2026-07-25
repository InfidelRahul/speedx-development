<?php
/**
 * SpeedX Footer Template
 * 
 * Displays the site footer with navigation and copyright.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
exit;
}
?>
</main><!-- #content-container -->

<footer class="site-footer sx-surface-raised">
<div class="footer-grid">
<!-- Brand Column -->
<div class="fbrand">
<h4 style="font-family: var(--sx-font-heading);"><?php bloginfo( 'name' ); ?></h4>
<p class="meta-text"><?php esc_html_e( 'A calm, tactile, editorial experience.', 'speedx' ); ?></p>
<div class="social-links" style="display: flex; gap: 0.75rem; margin-top: 1rem;">
<a href="#" class="btn-neu" aria-label="Twitter">𝕏</a>
<a href="#" class="btn-neu" aria-label="Facebook">f</a>
<a href="#" class="btn-neu" aria-label="Instagram">📷</a>
</div>
</div>

<!-- Pages Column -->
<div class="footer-links">
<h5><?php esc_html_e( 'Pages', 'speedx' ); ?></h5>
<ul style="list-style: none; padding: 0;">
<?php
wp_list_pages( [
'title_li' => '',
'depth'    => 1,
] );
?>
</ul>
</div>

<!-- Categories Column -->
<div class="footer-links">
<h5><?php esc_html_e( 'Categories', 'speedx' ); ?></h5>
<ul style="list-style: none; padding: 0;">
<?php
wp_list_categories( [
'title_li' => '',
'depth'    => 1,
] );
?>
</ul>
</div>

<!-- Meta Column -->
<div class="footer-links">
<h5><?php esc_html_e( 'Meta', 'speedx' ); ?></h5>
<ul style="list-style: none; padding: 0;">
<li><a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Log in', 'speedx' ); ?></a></li>
<li><a href="<?php echo esc_url( get_feed_link() ); ?>"><?php esc_html_e( 'RSS', 'speedx' ); ?></a></li>
<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy', 'speedx' ); ?></a></li>
</ul>
</div>
</div>

<!-- Copyright Row -->
<div class="footer-copyright">
<p class="meta-text">
&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'speedx' ); ?>
</p>
</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
