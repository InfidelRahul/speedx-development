<?php
/**
 * Template for displaying archives
 *
 * @package SpeedX
 */

get_header(); ?>

<div class="content-wrapper fade-in">
    <header class="page-header">
        <?php
        the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="posts-grid">
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('article-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <header class="entry-header">
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <div class="entry-meta">
                            <span class="posted-on"><?php echo get_the_date(); ?></span>
                            <span class="byline"> <?php echo get_the_author(); ?></span>
                        </div>
                    </header>

                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>

                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="btn-neu">Read More</a>
                    </footer>
                </article>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_pagination(array(
            'mid_size'  => 2,
            'prev_text' => '&larr;',
            'next_text' => '&rarr;',
        ));
        ?>

    <?php else : ?>
        <div class="no-posts">
            <h2>No posts found</h2>
            <p>It seems we can't find any posts in this archive. Try searching for something else!</p>
            <?php get_search_form(); ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer();
