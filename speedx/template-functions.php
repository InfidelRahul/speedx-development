<?php
/**
 * Fallback menu when no menu is assigned
 */
function speedx_fallback_menu() {
    echo '<ul id="primary-menu" class="primary-menu-list">';
    
    // Home link
    echo '<li><a href="' . esc_url(home_url('/')) . '" class="spa-link">Home</a></li>';
    
    // Pages
    $pages = wp_list_pages(array(
        'title_li' => '',
        'echo'     => 0,
        'depth'    => 1,
    ));
    
    if ($pages) {
        echo $pages;
    }
    
    echo '</ul>';
}
