<?php
/**
 * 404 Error Page
 */

get_header();

?>

<div class="content-area">
    <section class="error-404 not-found" style="text-align: center; padding: 4rem 0;">
        
        <header class="page-header">
            <h1 class="page-title" style="font-size: 6rem; margin: 0; color: var(--primary-color); line-height: 1;">404</h1>
            <h2 style="font-size: 2rem; margin: 1.5rem 0;"><?php _e('Page Not Found', 'speedx'); ?></h2>
        </header>

        <div class="page-content" style="max-width: 600px; margin: 0 auto;">
            <p style="font-size: 1.125rem; color: #6b7280; margin-bottom: 2rem;">
                <?php _e('Oops! The page you&rsquo;re looking for doesn&rsquo;t exist or has been moved.', 'speedx'); ?>
            </p>

            <!-- Search Form -->
            <div style="margin-bottom: 2rem;">
                <label for="search-404" class="sr-only"><?php _e('Search', 'speedx'); ?></label>
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="display: flex; gap: 0.5rem; max-width: 500px; margin: 0 auto;">
                    <input 
                        type="search" 
                        id="search-404"
                        class="search-field" 
                        placeholder="<?php esc_attr_e('Search &hellip;', 'speedx'); ?>" 
                        value="<?php echo get_search_query(); ?>" 
                        name="s"
                        style="flex: 1;"
                    />
                    <button type="submit" class="search-submit btn">
                        <?php _e('Search', 'speedx'); ?>
                    </button>
                </form>
            </div>

            <!-- Helpful Links -->
            <div style="margin-top: 3rem;">
                <h3 style="font-size: 1.25rem; margin-bottom: 1.5rem;"><?php _e('Try these links:', 'speedx'); ?></h3>
                
                <ul style="list-style: none; padding: 0; display: grid; gap: 0.75rem; max-width: 400px; margin: 0 auto;">
                    <li>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="spa-link" style="font-weight: 500;">
                            &larr; <?php _e('Back to Homepage', 'speedx'); ?>
                        </a>
                    </li>
                    <?php if (get_option('show_on_front') === 'posts') : ?>
                        <li>
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="spa-link">
                                <?php _e('View Blog Posts', 'speedx'); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                    $pages = get_pages(array('number' => 5));
                    if ($pages) :
                        foreach ($pages as $page) :
                    ?>
                        <li>
                            <a href="<?php echo esc_url(get_permalink($page->ID)); ?>" class="spa-link">
                                <?php echo esc_html($page->post_title); ?>
                            </a>
                        </li>
                    <?php 
                        endforeach;
                    endif; 
                    ?>
                </ul>
            </div>

        </div>

    </section>
</div>

<?php
get_footer();
