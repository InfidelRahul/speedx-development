<?php
/**
 * SpeedX Search Form Template
 * 
 * Custom search form with neumorphic styling.
 * 
 * @package SpeedX
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div style="position: relative;">
		<label for="search-field-<?php echo esc_attr( wp_unique_id() ); ?>" class="screen-reader-text">
			<?php esc_html_e( 'Search for:', 'speedx' ); ?>
		</label>
		<input
			type="search"
			id="search-field-<?php echo esc_attr( wp_unique_id() ); ?>"
			class="input-neu search-field"
			placeholder="<?php esc_attr_e( 'Search...', 'speedx' ); ?>"
			value="<?php echo get_search_query(); ?>"
			name="s"
		/>
		<button type="submit" class="btn-neu" style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); padding: 0.5rem 1rem;">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
			</svg>
		</button>
	</div>
</form>
