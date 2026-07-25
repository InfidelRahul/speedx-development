<?php
/**
 * Footer template for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
    </main><!-- #content-container -->

    <footer id="colophon" class="site-footer">
        <div class="site-info neu-flat" style="padding: 2rem; border-radius: var(--radius-md);">
            <span class="copyright">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                <?php esc_html_e('Powered by WordPress', 'speedx'); ?>
            </span>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
