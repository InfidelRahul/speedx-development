<?php
/**
 * SpeedX Single Post Template
 * 
 * Displays individual blog posts.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="main-content">
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'sx-surface-raised' ); ?>>
		
		<header class="entry-header" style="padding: 2rem 2rem 0;">
			<div class="post-meta-row">
				<?php $categories = get_the_category(); if ( ! empty( $categories ) ) : ?>
					<span class="category-chip sx-surface-pressed sx-surface-pill">
						<span class="dot" style="background: var(--sx-cat-design);"></span>
						<?php echo esc_html( $categories[0]->name ); ?>
					</span>
				<?php endif; ?>
				<span class="meta-text">&middot;</span>
				<time datetime="<?php echo get_the_date( 'c' ); ?>" class="meta-text"><?php echo get_the_date( 'F j, Y' ); ?></time>
			</div>
			
			<h1 class="entry-title" style="margin-top: 1rem;"><?php the_title(); ?></h1>
			
			<div class="post-meta-row" style="margin-bottom: 2rem;">
				<div class="author-avatar">
					<?php echo esc_html( substr( get_the_author_meta( 'display_name' ), 0, 2 ) ); ?>
				</div>
				<span><?php the_author(); ?></span>
				<span>&middot;</span>
				<span class="meta-text"><?php echo esc_html( human_time_diff( get_post_time( 'U', true ), time() ) . ' ' . __( 'ago', 'speedx' ) ); ?></span>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-image-well" style="margin: 0 2rem;">
				<?php the_post_thumbnail( 'large' ); ?>
			</div>
		<?php endif; ?>

		<div class="entry-content" style="padding: 2rem;">
			<?php
			the_content();

			wp_link_pages( [
				'before'      => '<div class="page-links">' . esc_html__( 'Pages:', 'speedx' ),
				'after'       => '</div>',
				'link_before' => '<span class="btn-neu">',
				'link_after'  => '</span>',
			] );
			?>
		</div>

		<footer class="entry-footer" style="padding: 0 2rem 2rem;">
			<?php
			$tags = get_the_tags();
			if ( $tags ) :
			?>
				<div class="tag-cloud">
					<?php foreach ( $tags as $tag ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>" class="tag-link sx-surface-pressed sx-surface-pill meta-text">
							#<?php echo esc_html( $tag->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<div class="post-actions" style="margin-top: 2rem;">
				<button class="icon-btn" aria-label="<?php esc_attr_e( 'Share', 'speedx' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="18" cy="5" r="3"></circle>
						<circle cx="6" cy="12" r="3"></circle>
						<circle cx="18" cy="19" r="3"></circle>
						<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
						<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
					</svg>
				</button>
				<button class="icon-btn" aria-label="<?php esc_attr_e( 'Like', 'speedx' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
					</svg>
				</button>
				<button class="icon-btn" aria-label="<?php esc_attr_e( 'Bookmark', 'speedx' ); ?>">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
					</svg>
				</button>
			</div>
		</footer>
	</article>

	<?php
	// Post navigation.
	the_post_navigation( [
		'prev_text' => '<span class="btn-neu">&larr; %title</span>',
		'next_text' => '<span class="btn-neu">%title &rarr;</span>',
	] );

	// Comments template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
