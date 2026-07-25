<?php
/**
 * Archive template for SpeedX theme
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
        <?php
        the_archive_title('<h1 class="page-title" style="font-size: 2rem;">', '</h1>');
        the_archive_description('<div class="archive-description" style="margin-top: 1rem; color: var(--text-muted);">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="posts-list">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('article-card neu-raised'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail" style="margin-bottom: 1.5rem;">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
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
            <p><?php esc_html_e('No posts found in this archive.', 'speedx'); ?></p>
        </section>
    <?php endif; ?>
</div>

<?php
get_footer();
