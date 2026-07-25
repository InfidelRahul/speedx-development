<?php
/**
 * SpeedX Index Template
 * 
 * Main blog listing template with editorial hero and bento grid.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="main-content">
	
	<?php if ( is_home() && ! is_paged() ) : ?>
	<!-- Editorial Hero -->
	<section class="editorial-hero">
		<div class="hero-content">
			<span class="eyebrow-pill"><?php esc_html_e( 'Featured', 'speedx' ); ?></span>
			<h1><?php bloginfo( 'name' ); ?></h1>
			<p class="dek"><?php bloginfo( 'description' ); ?></p>
			
			<div class="hero-actions">
				<a href="#posts" class="btn-neu primary"><?php esc_html_e( 'Browse Posts', 'speedx' ); ?></a>
				<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'about' ) ) ); ?>" class="btn-neu"><?php esc_html_e( 'Learn More', 'speedx' ); ?></a>
			</div>
			
			<div class="hero-stats">
				<div class="stat-chip sx-surface-raised">
					<span><?php echo wp_count_posts()->publish; ?></span>
					<span class="meta-text"><?php esc_html_e( 'Articles', 'speedx' ); ?></span>
				</div>
				<div class="stat-chip sx-surface-raised">
					<span><?php echo wp_count_comments()->total_comments; ?></span>
					<span class="meta-text"><?php esc_html_e( 'Discussions', 'speedx' ); ?></span>
				</div>
			</div>
		</div>
		
		<?php
		// Get featured post (most recent).
		$featured_args = [
			'posts_per_page'      => 1,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		];
		$featured_query = new WP_Query( $featured_args );
		
		if ( $featured_query->have_posts() ) :
			while ( $featured_query->have_posts() ) : $featured_query->the_post();
		?>
		<article class="hero-card sx-surface-raised">
			<div class="hero-image-well">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'large' ); ?>
					</a>
				<?php endif; ?>
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
				<p class="meta-text"><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
			</div>
		</article>
		<?php
			endwhile;
			wp_reset_postdata();
		endif;
		?>
	</section>
	<?php endif; ?>

	<!-- Feed Toolbar -->
	<div class="feed-toolbar sx-surface-raised">
		<div class="filter-chips">
			<span class="category-chip sx-surface-pressed sx-surface-pill active">
				<?php esc_html_e( 'All', 'speedx' ); ?>
			</span>
			<?php
			$categories = get_categories( [ 'number' => 5 ] );
			foreach ( $categories as $category ) :
			?>
				<span class="category-chip sx-surface-raised sx-surface-pill">
					<span class="dot" style="background: var(--sx-cat-design);"></span>
					<?php echo esc_html( $category->name ); ?>
				</span>
			<?php endforeach; ?>
		</div>
		
		<div class="view-toggle">
			<button class="btn-neu active" aria-label="<?php esc_attr_e( 'Grid View', 'speedx' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<rect x="3" y="3" width="7" height="7"></rect>
					<rect x="14" y="3" width="7" height="7"></rect>
					<rect x="14" y="14" width="7" height="7"></rect>
					<rect x="3" y="14" width="7" height="7"></rect>
				</svg>
			</button>
			<button class="btn-neu" aria-label="<?php esc_attr_e( 'List View', 'speedx' ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<line x1="8" y1="6" x2="21" y2="6"></line>
					<line x1="8" y1="12" x2="21" y2="12"></line>
					<line x1="8" y1="18" x2="21" y2="18"></line>
					<line x1="3" y1="6" x2="3.01" y2="6"></line>
					<line x1="3" y1="12" x2="3.01" y2="12"></line>
					<line x1="3" y1="18" x2="3.01" y2="18"></line>
				</svg>
			</button>
		</div>
	</div>

	<!-- Post Grid -->
	<div class="post-grid" id="posts">
		<?php
		if ( have_posts() ) :
			$post_index = 0;
			while ( have_posts() ) : the_post();
				$post_index++;
				$is_featured = ( $post_index === 2 && ! is_paged() );
			?>
			<article class="post-card <?php echo $is_featured ? 'featured sx-surface-raised' : 'sx-surface-raised'; ?> fade-up" style="animation-delay: <?php echo ( $post_index * 0.1 ); ?>s;">
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
					
					<div class="post-actions">
						<button class="icon-btn" aria-label="<?php esc_attr_e( 'Like', 'speedx' ); ?>">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
							</svg>
						</button>
						<button class="icon-btn" aria-label="<?php esc_attr_e( 'Comment', 'speedx' ); ?>">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
							</svg>
						</button>
						<button class="icon-btn" aria-label="<?php esc_attr_e( 'Bookmark', 'speedx' ); ?>">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
								<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
							</svg>
						</button>
					</div>
				</div>
			</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php esc_html_e( 'No posts found.', 'speedx' ); ?></p>
		<?php endif; ?>
	</div>

	<!-- Load More -->
	<div style="text-align: center; margin-top: 3rem;">
		<button class="btn-neu"><?php esc_html_e( 'Load More Posts', 'speedx' ); ?></button>
	</div>

</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
