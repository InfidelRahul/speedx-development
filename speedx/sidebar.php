<?php
/**
 * SpeedX Sidebar Template
 * 
 * Displays sidebar widgets.
 * 
 * @package SpeedX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( is_active_sidebar( 'sidebar-1' ) ) :
?>
	<aside class="sidebar" id="secondary-sidebar">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside>
<?php
else :
?>
	<aside class="sidebar" id="secondary-sidebar">
		
		<!-- Search Widget -->
		<div class="widget sx-surface-raised">
			<h4 class="widget-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<circle cx="11" cy="11" r="8"></circle>
					<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				</svg>
				<?php esc_html_e( 'Search', 'speedx' ); ?>
			</h4>
			<?php get_search_form(); ?>
		</div>

		<!-- Categories Widget -->
		<div class="widget sx-surface-raised">
			<h4 class="widget-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
				</svg>
				<?php esc_html_e( 'Categories', 'speedx' ); ?>
			</h4>
			<ul style="list-style: none;">
				<?php
				wp_list_categories( [
					'title_li'  => '',
					'style'     => 'list',
				] );
				?>
			</ul>
		</div>

		<!-- Tag Cloud Widget -->
		<div class="widget sx-surface-raised">
			<h4 class="widget-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
					<line x1="3" y1="9" x2="21" y2="9"></line>
					<line x1="9" y1="21" x2="9" y2="9"></line>
				</svg>
				<?php esc_html_e( 'Tags', 'speedx' ); ?>
			</h4>
			<div class="tag-cloud">
				<?php
				wp_tag_cloud( [
					'smallest' => 12,
					'largest'  => 12,
					'unit'     => 'px',
					'format'   => '<a href="%1$s" class="tag-link sx-surface-pressed sx-surface-pill">%3$s</a>',
				] );
				?>
			</div>
		</div>

		<!-- Reading Goal Progress Widget -->
		<div class="widget sx-surface-raised">
			<h4 class="widget-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
				</svg>
				<?php esc_html_e( 'Reading Progress', 'speedx' ); ?>
			</h4>
			<div class="progress-ring-container">
				<svg width="100" height="100" viewBox="0 0 100 100">
					<circle cx="50" cy="50" r="40" fill="none" stroke="var(--sx-bg-base)" stroke-width="8"/>
					<circle cx="50" cy="50" r="40" fill="none" stroke="var(--sx-accent)" stroke-width="8"
						stroke-dasharray="251.2" stroke-dashoffset="100" transform="rotate(-90 50 50)"/>
				</svg>
			</div>
			<p class="meta-text" style="text-align: center;"><?php esc_html_e( '4 of 10 articles this week', 'speedx' ); ?></p>
		</div>

		<!-- Newsletter Widget -->
		<div class="widget sx-surface-raised">
			<h4 class="widget-title">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
					<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
					<polyline points="22,6 12,13 2,6"></polyline>
				</svg>
				<?php esc_html_e( 'Newsletter', 'speedx' ); ?>
			</h4>
			<p class="meta-text"><?php esc_html_e( 'Get the latest posts delivered to your inbox.', 'speedx' ); ?></p>
			<form class="newsletter-form">
				<input type="email" class="input-neu" placeholder="<?php esc_attr_e( 'Your email', 'speedx' ); ?>" style="margin-bottom: 1rem;">
				<button type="submit" class="btn-neu primary" style="width: 100%;"><?php esc_html_e( 'Subscribe', 'speedx' ); ?></button>
			</form>
		</div>

	</aside>
<?php
endif;
?>
