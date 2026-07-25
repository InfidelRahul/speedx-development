<?php
/**
 * SpeedX 404 Error Template
 * 
 * Displays when content is not found.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="main-content">
	<div class="sx-surface-raised" style="padding: 4rem; text-align: center;">
		<span class="eyebrow-pill sx-surface-pressed"><?php esc_html_e( 'Error 404', 'speedx' ); ?></span>
		
		<h1 style="font-size: 6rem; margin: 1rem 0; color: var(--sx-accent);">404</h1>
		
		<h2><?php esc_html_e( 'Page Not Found', 'speedx' ); ?></h2>
		
		<p class="dek"><?php esc_html_e( "The page you're looking for doesn't exist or has been moved.", 'speedx' ); ?></p>
		
		<div style="margin-top: 2rem;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-neu primary">
				<?php esc_html_e( 'Back to Home', 'speedx' ); ?>
			</a>
			<button class="btn-neu" onclick="history.back()" style="margin-left: 1rem;">
				<?php esc_html_e( 'Go Back', 'speedx' ); ?>
			</button>
		</div>
		
		<div style="margin-top: 3rem; padding-top: 2rem; border-top: 2px dashed rgba(0,0,0,0.05);">
			<p class="meta-text"><?php esc_html_e( 'Or try searching:', 'speedx' ); ?></p>
			<div style="max-width: 400px; margin: 1rem auto;">
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
