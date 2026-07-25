<?php
/**
 * Template for 404 page
 *
 * @package SpeedX
 */

get_header(); ?>

<div class="content-wrapper fade-in">
    <div class="error-404">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist or has been moved.</p>
        
        <div class="search-box">
            <?php get_search_form(); ?>
        </div>
        
        <div class="go-home">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-neu">Go Back Home</a>
        </div>
    </div>
</div>

<?php get_footer();
