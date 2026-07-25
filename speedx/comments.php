<?php
/**
 * Comments template for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area neu-flat" style="padding: 2rem; margin-top: 3rem; border-radius: var(--radius-md);">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title" style="margin-bottom: 1.5rem; font-size: 1.5rem;">
            <?php
            $comment_count = get_comments_number();
            printf(
                esc_html(_n('%d Comment', '%d Comments', $comment_count, 'speedx')),
                $comment_count
            );
            ?>
        </h2>

        <ol class="comment-list" style="list-style: none; padding: 0;">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'speedx_comment_callback',
            ));
            ?>
        </ol>

        <?php
        the_comments_pagination(array(
            'prev_text' => __('← Older', 'speedx'),
            'next_text' => __('Newer →', 'speedx'),
        ));
        ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments" style="color: var(--text-muted);">
            <?php esc_html_e('Comments are closed.', 'speedx'); ?>
        </p>
    <?php endif; ?>

    <?php
    comment_form(array(
        'class_submit'  => 'btn-neu',
        'title_reply'   => __('Leave a Comment', 'speedx'),
        'label_submit'  => __('Post Comment', 'speedx'),
        'comment_field' => '<p class="comment-form-comment"><label for="comment">' . __('Comment', 'speedx') . '</label><textarea id="comment" name="comment" class="neu-pressed" required></textarea></p>',
    ));
    ?>
</div>

<?php
/**
 * Custom comment callback function
 */
function speedx_comment_callback($comment, $args, $depth) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('neu-raised', null, null, false); ?> style="padding: 1.5rem; margin-bottom: 1rem; list-style: none;">
        <article class="comment-body">
            <header class="comment-meta" style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div class="comment-author-avatar" style="flex-shrink: 0;">
                    <?php echo get_avatar($comment, 50, '', '', array('class' => 'neu-circle')); ?>
                </div>
                <div class="comment-author-info">
                    <span class="comment-author" style="font-weight: 600;">
                        <?php comment_author_link(); ?>
                    </span>
                    <span class="comment-date" style="display: block; font-size: 0.875rem; color: var(--text-muted);">
                        <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php printf(__('%1$s at %2$s', 'speedx'), get_comment_date(), get_comment_time()); ?>
                            </time>
                        </a>
                    </span>
                </div>
            </header>

            <div class="comment-content" style="line-height: 1.6;">
                <?php comment_text(); ?>
            </div>

            <footer class="comment-actions" style="margin-top: 1rem;">
                <?php
                edit_comment_link(__('Edit', 'speedx'), '<span class="edit-link" style="margin-right: 1rem;">', '</span>');
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="reply-link">',
                    'after'     => '</span>',
                )));
                ?>
            </footer>
        </article>
    <?php
}
