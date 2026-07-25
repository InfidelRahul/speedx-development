<?php
/**
 * 404 error template for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="content-wrapper fade-enter-active">
    <section class="error-404 neu-raised" style="padding: 4rem 2rem; text-align: center; max-width: 600px; margin: 0 auto;">
        <header class="page-header" style="margin-bottom: 2rem;">
            <h1 class="page-title" style="font-size: 8rem; line-height: 1; color: var(--accent); margin-bottom: 1rem;">
                404
            </h1>
            <h2 class="page-subtitle" style="font-size: 2rem; margin-bottom: 1rem;">
                <?php esc_html_e('Page Not Found', 'speedx'); ?>
            </h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">
                <?php esc_html_e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'speedx'); ?>
            </p>
        </header>

        <div class="page-content" style="margin-top: 3rem;">
            <p style="margin-bottom: 1.5rem;"><?php esc_html_e('Try searching for what you need:', 'speedx'); ?></p>
            <?php get_search_form(); ?>
            
            <div style="margin-top: 2rem;">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-neu">
                    <?php esc_html_e('Back to Home', 'speedx'); ?>
                </a>
            </div>
        </div>
    </section>
</div>

<?php
get_footer();
