<?php
/**
 * The main template file
 * This is the fallback template for all requests
 */

get_header();

?>

<div class="content-area">
    <?php if (have_posts()) : ?>

        <div class="article-list">
            <?php while (have_posts()) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
                    
                    <!-- Post Title -->
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>" class="spa-link">
                            <?php the_title(); ?>
                        </a>
                    </h2>

                    <!-- Post Meta -->
                    <?php if ('post' === get_post_type()) : ?>
                        <div class="article-meta">
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                            <?php if (has_category()) : ?>
                                <span style="margin-left: 0.5rem;">&middot;</span>
                                <?php the_category(', '); ?>
                            <?php endif; ?>
                            <?php if (comments_open()) : ?>
                                <span style="margin-left: 0.5rem;">&middot;</span>
                                <a href="<?php comments_link(); ?>" class="spa-link">
                                    <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail" style="margin: 1rem 0;">
                            <a href="<?php the_permalink(); ?>" class="spa-link">
                                <?php the_post_thumbnail('medium', array(
                                    'style' => 'max-width: 100%; height: auto; border-radius: 0.375rem;',
                                )); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Post Excerpt/Content -->
                    <div class="entry-summary">
                        <?php if (is_search() || is_archive()) : ?>
                            <?php the_excerpt(); ?>
                        <?php else : ?>
                            <?php the_content(__('Continue reading &rarr;', 'speedx')); ?>
                        <?php endif; ?>
                    </div>

                    <!-- Read More Link -->
                    <footer class="entry-footer" style="margin-top: 1.5rem;">
                        <a href="<?php the_permalink(); ?>" class="btn spa-link">
                            <?php _e('Read More', 'speedx'); ?>
                        </a>
                    </footer>

                </article>

            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <?php the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('&larr; Previous', 'speedx'),
            'next_text' => __('Next &rarr;', 'speedx'),
            'class'     => 'pagination',
        )); ?>

    <?php else : ?>

        <!-- No Posts Found -->
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php _e('Nothing Found', 'speedx'); ?></h1>
            </header>

            <div class="page-content">
                <?php if (is_home() && current_user_can('publish_posts')) : ?>
                    <p>
                        <?php printf(
                            __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'speedx'),
                            esc_url(admin_url('post-new.php'))
                        ); ?>
                    </p>
                <?php elseif (is_search()) : ?>
                    <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'speedx'); ?></p>
                    <?php get_search_form(); ?>
                <?php else : ?>
                    <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'speedx'); ?></p>
                    <?php get_search_form(); ?>
                <?php endif; ?>
            </div>
        </section>

    <?php endif; ?>
</div>

<?php
get_footer();
