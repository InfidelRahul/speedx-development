<?php
/**
 * Single post template for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="content-wrapper fade-enter-active">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('article-card neu-raised'); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail" style="margin-bottom: 2rem;">
                    <?php the_post_thumbnail('large', array('loading' => 'lazy')); ?>
                </div>
            <?php endif; ?>

            <header class="entry-header" style="margin-bottom: 2rem;">
                <h1 class="entry-title" style="font-size: 2.5rem; margin-bottom: 1rem;">
                    <?php the_title(); ?>
                </h1>
                
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
                    <?php 
                    $categories = get_the_category();
                    if ($categories) :
                    ?>
                        <span class="cat-links">
                            <?php esc_html_e('in', 'speedx'); ?> 
                            <?php echo esc_html($categories[0]->name); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if (comments_open()) : ?>
                        <span class="comments-link">
                            <a href="#comments">
                                <?php comments_number(__('0 Comments', 'speedx'), __('1 Comment', 'speedx'), __('% Comments', 'speedx')); ?>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
            </header>

            <div class="entry-content" style="line-height: 1.8; font-size: 1.1rem;">
                <?php the_content(); ?>
                
                <?php wp_link_pages(array(
                    'before' => '<div class="page-links" style="margin: 2rem 0;">' . __('Pages:', 'speedx'),
                    'after'  => '</div>',
                )); ?>
            </div>

            <footer class="entry-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(0,0,0,0.05);">
                <?php
                $tags_list = get_the_tag_list('', ', ');
                if ($tags_list) :
                ?>
                    <div class="tags-links" style="margin-bottom: 1.5rem;">
                        <strong><?php esc_html_e('Tags:', 'speedx'); ?></strong> 
                        <?php echo $tags_list; ?>
                    </div>
                <?php endif; ?>

                <nav class="post-navigation" style="display: flex; justify-content: space-between; gap: 1rem; margin-top: 2rem;">
                    <div class="nav-previous">
                        <?php previous_post_link('%link', '<span class="btn-neu">' . __('← Previous', 'speedx') . '</span>'); ?>
                    </div>
                    <div class="nav-next">
                        <?php next_post_link('%link', '<span class="btn-neu">' . __('Next →', 'speedx') . '</span>'); ?>
                    </div>
                </nav>
            </footer>

            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
