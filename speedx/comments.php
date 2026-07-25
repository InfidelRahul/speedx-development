<?php
/**
 * SpeedX Comments Template
 * 
 * Displays comments and comment form with neumorphic styling.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area sx-surface-raised">
	
	<?php if ( have_comments() ) : ?>
		<header class="comments-title" style="margin-bottom: 2rem;">
			<h3 style="display: flex; align-items: center; gap: 0.75rem;">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
				</svg>
				<?php
				$comment_count = get_comments_number();
				printf(
					esc_html( _n( '%d Comment', '%d Comments', $comment_count, 'speedx' ) ),
					number_format_i18n( $comment_count )
				);
				?>
			</h3>
			
			<div class="comment-sort" style="margin-top: 1rem;">
				<button class="btn-neu active"><?php esc_html_e( 'Newest', 'speedx' ); ?></button>
				<button class="btn-neu"><?php esc_html_e( 'Top', 'speedx' ); ?></button>
			</div>
		</header>

		<ul class="comment-list">
			<?php
			wp_list_comments( [
				'style'       => 'ul',
				'short_ping'  => true,
				'avatar_size' => 48,
				'callback'    => 'speedx_comment_callback',
			] );
			?>
		</ul>

		<?php
		the_comments_pagination( [
			'prev_text' => '<span class="btn-neu">&larr;</span>',
			'next_text' => '<span class="btn-neu">&rarr;</span>',
		] );
		?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments meta-text"><?php esc_html_e( 'Comments are closed.', 'speedx' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form( [
		'class_form'         => 'comment-form',
		'title_reply'        => esc_html__( 'Leave a Comment', 'speedx' ),
		'title_reply_before' => '<h3 id="reply-title" style="margin-bottom: 1.5rem;">',
		'title_reply_after'  => '</h3>',
		'comment_field'      => '
			<div class="sx-surface-pressed" style="padding: 1.5rem; margin-bottom: 1.5rem;">
				<div class="comment-meta" style="margin-bottom: 1rem;">
					<div class="author-avatar">' . esc_html( substr( wp_get_current_user()->display_name, 0, 2 ) ) . '</div>
					<span>' . ( is_user_logged_in() ? wp_get_current_user()->display_name : esc_html__( 'Guest', 'speedx' ) ) . '</span>
				</div>
				<p class="comment-form-comment" style="margin: 0;">
					<label for="comment" class="screen-reader-text">' . esc_html__( 'Comment', 'speedx' ) . '</label>
					<textarea id="comment" name="comment" class="input-neu" placeholder="' . esc_attr__( 'Write your thoughts...', 'speedx' ) . '" rows="4" required></textarea>
				</p>
			</div>',
		'fields' => array(
			'author' => '
				<p class="comment-form-author">
					<input id="author" name="author" type="text" class="input-neu" placeholder="' . esc_attr__( 'Your Name', 'speedx' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '"' . ( $req ? ' required' : '' ) . '>
				</p>',
			'email' => '
				<p class="comment-form-email">
					<input id="email" name="email" type="email" class="input-neu" placeholder="' . esc_attr__( 'Your Email', 'speedx' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . ( $req ? ' required' : '' ) . '>
				</p>',
		),
		'submit_button' => '<button type="submit" id="submit" class="btn-neu primary">%s</button>',
		'submit_field'  => '<div class="form-submit">%1$s %2$s</div>',
	] );
	?>
</div>

<?php
/**
 * Custom comment callback function.
 */
function speedx_comment_callback( $comment, $args, $depth ) {
	?>
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment-item sx-surface-raised' ); ?>>
		<article>
			<header class="comment-meta">
				<div class="author-avatar">
					<?php echo get_avatar( $comment, 48, '', '', [ 'class' => 'avatar' ] ); ?>
				</div>
				<div>
					<strong><?php comment_author_link(); ?></strong>
					<?php if ( user_can( $comment->user_id, 'edit_posts' ) ) : ?>
						<span class="comment-author-badge sx-surface-pressed sx-surface-pill"><?php esc_html_e( 'Author', 'speedx' ); ?></span>
					<?php endif; ?>
					<br>
					<time datetime="<?php comment_time( 'c' ); ?>" class="meta-text">
						<?php
						printf(
							esc_html__( '%1$s ago', 'speedx' ),
							human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) )
						);
						?>
					</time>
				</div>
			</header>

			<div class="comment-content">
				<?php comment_text(); ?>
			</div>

			<footer class="comment-actions" style="margin-top: 1rem;">
				<?php
				comment_reply_link( array_merge( $args, [
					'depth'     => $depth,
					'max_depth' => $args['max_depth'],
					'before'    => '<button class="btn-neu">',
					'after'     => '</button>',
				] ) );
				?>
				<button class="icon-btn" aria-label="<?php esc_attr_e( 'Like', 'speedx' ); ?>">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
					</svg>
				</button>
			</footer>
		</article>
	<?php
}
?>
