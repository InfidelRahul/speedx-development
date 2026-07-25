<?php
/**
 * Template for displaying single posts
 *
 * @package SpeedX
 */

get_header(); ?>

<div class="content-wrapper fade-in">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
                <div class="entry-meta">
                    <span class="posted-on">Published on <?php echo get_the_date(); ?></span>
                    <span class="byline"> by <?php echo get_the_author(); ?></span>
                    <?php if (has_category()) : ?>
                        <span class="cat-links"> in <?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>
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

            <footer class="entry-footer">
                <?php if (has_tag()) : ?>
                    <div class="tags-links">
                        <span>Tags: </span>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                <?php endif; ?>
            </footer>
        </article>

        <?php
        // Post navigation
        the_post_navigation(array(
            'prev_text' => '&larr; %title',
            'next_text' => '%title &rarr;',
        ));

        // Comments
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;

    endwhile; ?>
</div>

<?php get_footer();
