<?php
/**
 * Page template for SpeedX theme
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
            <header class="entry-header" style="margin-bottom: 2rem;">
                <h1 class="entry-title" style="font-size: 2.5rem;">
                    <?php the_title(); ?>
                </h1>
            </header>

            <div class="entry-content" style="line-height: 1.8; font-size: 1.1rem;">
                <?php the_content(); ?>
                
                <?php wp_link_pages(array(
                    'before' => '<div class="page-links" style="margin: 2rem 0;">' . __('Pages:', 'speedx'),
                    'after'  => '</div>',
                )); ?>
            </div>

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
