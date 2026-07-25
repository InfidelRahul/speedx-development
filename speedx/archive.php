<?php
/**
 * SpeedX Archive Template
 * 
 * Displays category, tag, date, and author archives.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="main-content">
	<header class="archive-header" style="margin-bottom: 2rem;">
		<span class="eyebrow-pill sx-surface-pressed"><?php esc_html_e( 'Archive', 'speedx' ); ?></span>
		<h1>
			<?php
			if ( is_category() ) {
				single_cat_title();
			} elseif ( is_tag() ) {
				single_tag_title();
			} elseif ( is_author() ) {
				the_author();
			} elseif ( is_year() ) {
				get_the_date( 'Y' );
			} elseif ( is_month() ) {
				get_the_date( 'F Y' );
			} elseif ( is_day() ) {
				get_the_date( 'F j, Y' );
			} else {
				esc_html_e( 'Archives', 'speedx' );
			}
			?>
		</h1>
		<?php
		$archive_description = get_the_archive_description();
		if ( $archive_description ) :
		?>
			<p class="dek"><?php echo wp_kses_post( $archive_description ); ?></p>
		<?php endif; ?>
	</header>

	<div class="post-grid">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) : the_post();
		?>
			<article class="post-card sx-surface-raised fade-up">
				<div class="post-image-well">
					<a href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'medium' ); ?>
						<?php else : ?>
							<div style="height: 220px; background: var(--sx-bg-base);"></div>
						<?php endif; ?>
					</a>
				</div>
				
				<div class="post-content">
					<div class="post-meta-row">
						<?php $categories = get_the_category(); if ( ! empty( $categories ) ) : ?>
							<span class="category-chip sx-surface-pressed sx-surface-pill">
								<span class="dot" style="background: var(--sx-cat-design);"></span>
								<?php echo esc_html( $categories[0]->name ); ?>
							</span>
						<?php endif; ?>
					</div>
					
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
					
					<div class="post-meta-row meta-text">
						<div class="author-avatar">
							<?php echo esc_html( substr( get_the_author_meta( 'display_name' ), 0, 2 ) ); ?>
						</div>
						<span><?php the_author(); ?></span>
						<span>&middot;</span>
						<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date( 'M j, Y' ); ?></time>
					</div>
				</div>
			</article>
		<?php
			endwhile;
		else :
		?>
			<p><?php esc_html_e( 'No posts found in this archive.', 'speedx' ); ?></p>
		<?php endif; ?>
	</div>

	<?php
	the_posts_pagination( [
		'mid_size'  => 2,
		'prev_text' => '<span class="btn-neu">&larr;</span>',
		'next_text' => '<span class="btn-neu">&rarr;</span>',
	] );
	?>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
