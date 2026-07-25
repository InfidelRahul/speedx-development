<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper" id="site-wrapper">
    <!-- Loading Indicator -->
    <div class="loading-indicator" id="loading-indicator"></div>

    <!-- Header -->
    <header class="site-header" id="site-header">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <!-- Site Branding -->
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <?php the_custom_logo(); ?>
                    <?php else : ?>
                        <h1 class="site-title" style="margin: 0; font-size: 1.5rem;">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="spa-link">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h1>
                        <?php $description = get_bloginfo('description', 'display');
                        if ($description || is_customize_preview()) : ?>
                            <p class="site-description" style="margin: 0.25rem 0 0; color: #6b7280; font-size: 0.875rem;">
                                <?php echo esc_html($description); ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Primary Navigation -->
                <nav class="main-navigation" id="site-navigation" aria-label="<?php esc_attr_e('Primary Menu', 'speedx'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'primary-menu-list',
                        'container'      => false,
                        'fallback_cb'    => 'speedx_fallback_menu',
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content Area (SPA Target) -->
    <main class="site-content" id="site-content">
        <div class="container" id="content-container">
