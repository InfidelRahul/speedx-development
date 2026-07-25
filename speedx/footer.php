        </div>
    </main>

    <!-- Footer -->
    <footer class="site-footer" id="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-1')) : ?>
                <div class="footer-widgets" style="display: grid; gap: 2rem; margin-bottom: 2rem;">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <!-- Footer Menu -->
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_id'        => 'footer-menu',
                'menu_class'     => 'footer-menu-list',
                'container'      => false,
                'depth'          => 1,
                'fallback_cb'    => false,
            ));
            ?>

            <div class="site-info" style="margin-top: 1.5rem; color: #6b7280; font-size: 0.875rem;">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                <?php printf(
                    __('Powered by %s', 'speedx'),
                    '<a href="https://wordpress.org" target="_blank" rel="noopener">WordPress</a>'
                ); ?>
                <span style="margin-left: 0.5rem;">&middot;</span>
                <span>SpeedX Theme</span>
            </div>
        </div>
    </footer>
</div><!-- .site-wrapper -->

<?php wp_footer(); ?>

</body>
</html>
