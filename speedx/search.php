<?php
/**
 * SpeedX Search Results Template
 * 
 * Displays search results.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="main-content">
	<header class="search-header" style="margin-bottom: 2rem;">
		<span class="eyebrow-pill sx-surface-pressed"><?php esc_html_e( 'Search Results', 'speedx' ); ?></span>
		<h1><?php printf( esc_html__( 'Search for: %s', 'speedx' ), '<span style="color: var(--sx-accent);">' . get_search_query() . '</span>' ); ?></h1>
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
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo wp_trim_words( get_the_excerpt(), 25 ); ?></p>
					
					<div class="post-meta-row meta-text">
						<time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date( 'M j, Y' ); ?></time>
					</div>
				</div>
			</article>
		<?php
			endwhile;
		else :
		?>
			<div class="sx-surface-raised" style="padding: 3rem; text-align: center;">
				<h3><?php esc_html_e( 'Nothing Found', 'speedx' ); ?></h3>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'speedx' ); ?></p>
				<?php get_search_form(); ?>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
