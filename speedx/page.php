<?php
/**
 * Template for displaying pages
 *
 * @package SpeedX
 */

get_header(); ?>

<div class="content-wrapper fade-in">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <?php
            wp_link_pages(array(
                'before'      => '<div class="page-links"><span class="page-links-title">Pages:</span>',
                'after'       => '</div>',
                'link_before' => '<span class="btn-neu btn-circle">',
                'link_after'  => '</span>',
            ));
            ?>
        </article>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
    endwhile; ?>
</div>

<?php get_footer();
