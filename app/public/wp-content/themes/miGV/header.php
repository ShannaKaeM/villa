<?php
/**
 * The header for our theme
 *
 * @package miGV
 * @version 1.0.0
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _e('Skip to content', 'migv'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding">
                <?php
                the_custom_logo();
                if (is_front_page() && is_home()) :
                    ?>
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                    <?php
                else :
                    ?>
                    <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
                    <?php
                endif;
                $migv_description = get_bloginfo('description', 'display');
                if ($migv_description || is_customize_preview()) :
                    ?>
                    <p class="site-description"><?php echo $migv_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                <?php endif; ?>
            </div>

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="menu-toggle-text"><?php _e('Menu', 'migv'); ?></span>
                    <span class="menu-toggle-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'primary-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
        </div>
    </header>

    <?php if (is_front_page() && !is_home()) : ?>
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title"><?php bloginfo('name'); ?></h1>
                    <p class="hero-description"><?php bloginfo('description'); ?></p>
                    <div class="hero-actions">
                        <a href="#content" class="btn btn-primary"><?php _e('Get Started', 'migv'); ?></a>
                        <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-secondary"><?php _e('View Blog', 'migv'); ?></a>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <div id="content" class="site-content">
