<?php
/**
 * Search results template
 */

get_header();

?>

<div class="content-area">
    <!-- Search Header -->
    <header class="page-header" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--border-color);">
        <h1 class="page-title" style="font-size: 2rem; margin-bottom: 0.5rem;">
            <?php printf(
                __('Search Results for: %s', 'speedx'),
                '<span style="color: var(--primary-color);">' . get_search_query() . '</span>'
            ); ?>
        </h1>
        <?php if (have_posts()) : ?>
            <p class="search-results-count" style="color: #6b7280;">
                <?php printf(
                    _n('Found %d result', 'Found %d results', $wp_query->found_posts, 'speedx'),
                    $wp_query->found_posts
                ); ?>
            </p>
        <?php endif; ?>
    </header>

    <!-- Search Form -->
    <div style="margin-bottom: 2rem;">
        <?php get_search_form(); ?>
    </div>

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
                        </div>
                    <?php endif; ?>

                    <!-- Post Excerpt with Search Highlight -->
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
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

        <!-- No Results Found -->
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php _e('Nothing Found', 'speedx'); ?></h1>
            </header>

            <div class="page-content">
                <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'speedx'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>

    <?php endif; ?>
</div>

<?php
get_footer();
