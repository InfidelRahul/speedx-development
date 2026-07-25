<?php
/**
 * Main template file for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="content-wrapper fade-enter-active">
    <?php if (is_home() && !is_front_page()) : ?>
        <header class="page-header neu-raised" style="padding: 2rem; margin-bottom: 2rem;">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <?php if (have_posts()) : ?>
        <div class="posts-list">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('article-card neu-raised'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail" style="margin-bottom: 1.5rem;">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                            </a>
                        </div>
                    <?php endif; ?>

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
                            <span class="byline">
                                <?php esc_html_e('by', 'speedx'); ?> 
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php the_author(); ?>
                                </a>
                            </span>
                            <?php if (comments_open()) : ?>
                                <span class="comments-link">
                                    <a href="<?php the_permalink(); ?>#comments">
                                        <?php comments_number(__('0 Comments', 'speedx'), __('1 Comment', 'speedx'), __('% Comments', 'speedx')); ?>
                                    </a>
                                </span>
                            <?php endif; ?>
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
            'class'     => 'pagination neu-flat',
        )); ?>

    <?php else : ?>
        <section class="no-results neu-raised" style="padding: 3rem; text-align: center;">
            <header class="page-header" style="margin-bottom: 1.5rem;">
                <h1 class="page-title"><?php esc_html_e('Nothing Found', 'speedx'); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for.', 'speedx'); ?></p>
                <?php get_search_form(); ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
