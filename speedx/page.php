<?php
/**
 * Page template
 */

get_header();

?>

<div class="content-area">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Page Header -->
            <header class="entry-header" style="margin-bottom: 2rem;">
                <?php the_title('<h1 class="entry-title" style="font-size: 2.5rem; margin-bottom: 1rem;">', '</h1>'); ?>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail" style="margin: 2rem 0;">
                    <?php the_post_thumbnail('large', array(
                        'style' => 'max-width: 100%; height: auto; border-radius: 0.5rem;',
                    )); ?>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="entry-content" style="line-height: 1.8; font-size: 1.125rem;">
                <?php the_content(); ?>
                
                <?php wp_link_pages(array(
                    'before'      => '<div class="page-links" style="margin: 2rem 0;">' . __('Pages:', 'speedx'),
                    'after'       => '</div>',
                    'link_before' => '<span class="page-link" style="display: inline-block; padding: 0.5rem 1rem; margin: 0.25rem; background: var(--light-gray); border-radius: 0.25rem;">',
                    'link_after'  => '</span>',
                )); ?>
            </div>

            <!-- Page Footer -->
            <?php if (get_edit_post_link()) : ?>
                <footer class="entry-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                    <?php edit_post_link(__('Edit', 'speedx'), '<span class="edit-link">', '</span>'); ?>
                </footer>
            <?php endif; ?>

        </article>

        <!-- Comments for pages (if enabled) -->
        <?php if (comments_open() || get_comments_number()) : ?>
            <section id="comments" class="comments-area" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <?php comments_template(); ?>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php
get_footer();
