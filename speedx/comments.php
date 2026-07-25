<?php
/**
 * Template for displaying comments
 *
 * @package SpeedX
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf('One comment on &ldquo;%1$s&rdquo;', '<span>' . get_the_title() . '</span>');
            } else {
                printf('%1$s comments on &ldquo;%2$s&rdquo;', number_format_i18n($comment_count), '<span>' . get_the_title() . '</span>');
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => function($comment, $args, $depth) {
                    ?>
                    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('comment'); ?>>
                        <article class="comment-body">
                            <header class="comment-meta">
                                <div class="comment-author vcard">
                                    <?php echo get_avatar($comment, 50); ?>
                                    <span class="fn"><?php comment_author_link(); ?></span>
                                </div>
                                <div class="comment-metadata">
                                    <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
                                        <time><?php comment_date(); ?> at <?php comment_time(); ?></time>
                                    </a>
                                    <?php edit_comment_link('Edit', ' <span class="edit-link">', '</span>'); ?>
                                </div>
                            </header>

                            <?php if ('0' === $comment->comment_approved) : ?>
                                <p class="comment-awaiting-moderation">Your comment is awaiting moderation.</p>
                            <?php endif; ?>

                            <div class="comment-content">
                                <?php comment_text(); ?>
                            </div>

                            <footer class="comment-reply">
                                <?php
                                comment_reply_link(array_merge($args, array(
                                    'depth'     => $depth,
                                    'max_depth' => $args['max_depth'],
                                    'before'    => '<span class="btn-neu">',
                                    'after'     => '</span>',
                                )));
                                ?>
                            </footer>
                        </article>
                    <?php
                },
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation();

        if (!comments_open()) :
            ?>
            <p class="no-comments">Comments are closed.</p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form(array(
        'class_form'         => 'comment-form',
        'title_reply'        => 'Leave a Comment',
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h3>',
        'submit_button'      => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn-neu">%4$s</button>',
    ));
    ?>
</div>
