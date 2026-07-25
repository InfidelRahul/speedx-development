<?php
/**
 * SpeedX Page Template
 * 
 * Displays static pages.
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
			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header>

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
	</article>

	<?php
	// Comments for pages if enabled.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	?>
</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
