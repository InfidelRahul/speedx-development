<?php
/**
 * Comments template
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>

        <h2 class="comments-title" style="font-size: 1.5rem; margin-bottom: 1.5rem;">
            <?php
            $comment_count = get_comments_number();
            printf(
                _n('%d Comment', '%d Comments', $comment_count, 'speedx'),
                $comment_count
            );
            ?>
        </h2>

        <!-- Comment List -->
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

        <!-- Comment Pagination -->
        <?php
        the_comments_navigation(array(
            'prev_text' => __('&larr; Older Comments', 'speedx'),
            'next_text' => __('Newer Comments &rarr;', 'speedx'),
        ));
        ?>

        <?php if (!comments_open()) : ?>
            <p class="no-comments" style="color: #6b7280; margin-top: 2rem;">
                <?php _e('Comments are closed.', 'speedx'); ?>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form(array(
        'class_form'         => 'comment-form',
        'title_reply'        => __('Leave a Comment', 'speedx'),
        'title_reply_before' => '<h3 id="reply-title" style="font-size: 1.5rem; margin-bottom: 1.5rem;">',
        'title_reply_after'  => '</h3>',
        'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn">%4$s</button>',
        'submit_field'       => '<div class="form-submit">%1$s %2$s</div>',
        'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . _x('Comment', 'noun', 'speedx') . '</label><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" required></textarea></p>',
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published.', 'speedx') . '</p>',
    ));
    ?>

</div>

<?php
/**
 * Custom comment callback function
 */
function speedx_comment_callback($comment, $args, $depth) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment-item', null, null, $args['has_children']); ?> style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-color);">
        <article class="comment-body">
            <header class="comment-meta" style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                <div class="comment-author-avatar" style="flex-shrink: 0;">
                    <?php echo get_avatar($comment, $args['avatar_size'], '', '', array('class' => 'avatar', 'style' => 'border-radius: 50%;')); ?>
                </div>
                <div class="comment-author-info">
                    <cite class="fn" style="font-weight: 600; font-style: normal;">
                        <?php comment_author_link(); ?>
                    </cite>
                    <div class="comment-metadata" style="font-size: 0.875rem; color: #6b7280;">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                __('%1$s at %2$s', 'speedx'),
                                get_comment_date('', $comment),
                                get_comment_time()
                            );
                            ?>
                        </time>
                        <?php edit_comment_link(__('Edit', 'speedx'), ' &middot; ', '', $comment->comment_ID); ?>
                    </div>
                </div>
            </header>

            <div class="comment-content" style="line-height: 1.7;">
                <?php comment_text(); ?>
            </div>

            <div class="reply" style="margin-top: 1rem;">
                <?php
                comment_reply_link(array_merge($args, array(
                    'depth'     => $depth,
                    'max_depth' => $args['max_depth'],
                    'before'    => '<span class="reply-link">',
                    'after'     => '</span>',
                )));
                ?>
            </div>
        </article>
    <?php
}
