<?php
/**
 * Search results template for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="content-wrapper fade-enter-active">
    <header class="page-header neu-raised" style="padding: 2rem; margin-bottom: 2rem;">
        <h1 class="page-title" style="font-size: 2rem;">
            <?php printf(esc_html__('Search Results for: %s', 'speedx'), '<span>' . get_search_query() . '</span>'); ?>
        </h1>
    </header>

    <?php if (have_posts()) : ?>
        <div class="posts-list">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('article-card neu-raised'); ?>>
                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        
                        <div class="meta">
                            <span class="posted-on">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                            </span>
                        </div>
                    </header>

                    <div class="entry-summary" style="margin-top: 1rem; color: var(--text-muted);">
                        <?php the_excerpt(); ?>
                    </div>

                    <footer class="entry-footer" style="margin-top: 1.5rem;">
                        <a href="<?php the_permalink(); ?>" class="btn-neu">
                            <?php esc_html_e('Read More', 'speedx'); ?>
                        </a>
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>

        <?php the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => __('Previous', 'speedx'),
            'next_text' => __('Next', 'speedx'),
        )); ?>

    <?php else : ?>
        <section class="no-results neu-raised" style="padding: 3rem; text-align: center;">
            <header class="page-header" style="margin-bottom: 1.5rem;">
                <h2 class="page-title"><?php esc_html_e('Nothing Found', 'speedx'); ?></h2>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('Sorry, but nothing matched your search terms.', 'speedx'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
