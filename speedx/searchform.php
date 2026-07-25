<?php
/**
 * Search form template
 */

$unique_id = wp_unique_id('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label>
        <span class="sr-only"><?php _e('Search for:', 'speedx'); ?></span>
        <input 
            type="search" 
            id="<?php echo esc_attr($unique_id); ?>" 
            class="search-field" 
            placeholder="<?php esc_attr_e('Search &hellip;', 'speedx'); ?>" 
            value="<?php echo get_search_query(); ?>" 
            name="s"
        />
    </label>
    <button type="submit" class="search-submit btn">
        <?php _e('Search', 'speedx'); ?>
    </button>
</form>
