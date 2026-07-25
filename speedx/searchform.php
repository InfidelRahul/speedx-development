<?php
/**
 * Custom search form template
 *
 * @package SpeedX
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="screen-reader-text"><?php _e('Search for:', 'speedx'); ?></span>
        <input 
            type="search" 
            class="search-field" 
            placeholder="<?php esc_attr_e('Search &hellip;', 'speedx'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s"
        />
    </label>
    <button type="submit" class="btn-neu search-submit">
        <span><?php _e('Search', 'speedx'); ?></span>
    </button>
</form>
