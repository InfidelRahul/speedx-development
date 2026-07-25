<?php
/**
 * Single post template
 */

get_header();

?>

<div class="content-area">
    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <!-- Post Header -->
            <header class="entry-header" style="margin-bottom: 2rem;">
                <?php the_title('<h1 class="entry-title" style="font-size: 2.5rem; margin-bottom: 1rem;">', '</h1>'); ?>

                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-meta article-meta" style="color: #6b7280; font-size: 0.875rem;">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        
                        <?php if (has_category()) : ?>
                            <span style="margin-left: 0.5rem;">&middot;</span>
                            <?php the_category(', '); ?>
                        <?php endif; ?>
                        
                        <?php if (comments_open()) : ?>
                            <span style="margin-left: 0.5rem;">&middot;</span>
                            <a href="#comments" class="spa-link">
                                <?php comments_number('0 Comments', '1 Comment', '% Comments'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Featured Image -->
            <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail" style="margin: 2rem 0;">
                    <?php the_post_thumbnail('large', array(
                        'style' => 'max-width: 100%; height: auto; border-radius: 0.5rem;',
                    )); ?>
                </div>
            <?php endif; ?>

            <!-- Post Content -->
            <div class="entry-content" style="line-height: 1.8; font-size: 1.125rem;">
                <?php the_content(); ?>
                
                <?php wp_link_pages(array(
                    'before'      => '<div class="page-links" style="margin: 2rem 0;">' . __('Pages:', 'speedx'),
                    'after'       => '</div>',
                    'link_before' => '<span class="page-link" style="display: inline-block; padding: 0.5rem 1rem; margin: 0.25rem; background: var(--light-gray); border-radius: 0.25rem;">',
                    'link_after'  => '</span>',
                )); ?>
            </div>

            <!-- Post Footer -->
            <footer class="entry-footer" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                
                <!-- Tags -->
                <?php if (has_tag()) : ?>
                    <div class="tags-links" style="margin-bottom: 1.5rem;">
                        <strong><?php _e('Tags:', 'speedx'); ?></strong>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                <?php endif; ?>

                <!-- Author Bio -->
                <?php if (get_the_author_meta('description')) : ?>
                    <div class="author-bio" style="background: var(--light-gray); padding: 1.5rem; border-radius: 0.5rem; margin: 2rem 0;">
                        <div style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div style="flex-shrink: 0;">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', array('class' => 'avatar', 'style' => 'border-radius: 50%;')); ?>
                            </div>
                            <div>
                                <h3 style="margin: 0 0 0.5rem;"><?php the_author(); ?></h3>
                                <p style="margin: 0;"><?php echo get_the_author_meta('description'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Post Navigation -->
                <nav class="post-navigation" style="display: flex; justify-content: space-between; gap: 2rem; margin-top: 2rem;">
                    <div class="nav-previous">
                        <?php previous_post_link('%link', '<span style="font-size: 0.875rem; color: #6b7280;">&larr; Previous Post</span><br><span style="font-weight: 500;">%title</span>'); ?>
                    </div>
                    <div class="nav-next" style="text-align: right;">
                        <?php next_post_link('%link', '<span style="font-size: 0.875rem; color: #6b7280;">Next Post &rarr;</span><br><span style="font-weight: 500;">%title</span>'); ?>
                    </div>
                </nav>

            </footer>

            <!-- Comments -->
            <?php if (comments_open() || get_comments_number()) : ?>
                <section id="comments" class="comments-area" style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                    <?php comments_template(); ?>
                </section>
            <?php endif; ?>

        </article>

    <?php endwhile; ?>
</div>

<?php
get_footer();
