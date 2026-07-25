<?php
/**
 * Custom search form for SpeedX theme
 * 
 * @package SpeedX
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="screen-reader-text" for="search-field-<?php echo esc_attr(wp_unique_id()); ?>">
        <?php esc_html_e('Search for:', 'speedx'); ?>
    </label>
    <input 
        type="search" 
        id="search-field-<?php echo esc_attr(wp_unique_id()); ?>" 
        class="search-field neu-pressed" 
        placeholder="<?php esc_attr_e('Search &hellip;', 'speedx'); ?>" 
        value="<?php echo get_search_query(); ?>" 
        name="s"
        aria-label="<?php esc_attr_e('Search', 'speedx'); ?>"
    />
    <button type="submit" class="search-submit neu-circle" aria-label="<?php esc_attr_e('Search', 'speedx'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </button>
</form>
