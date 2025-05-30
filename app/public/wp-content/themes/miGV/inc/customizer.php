<?php
/**
 * miGV Theme Customizer
 *
 * @package miGV
 * @version 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function migv_customize_register($wp_customize) {
    $wp_customize->get_setting('blogname')->transport         = 'postMessage';
    $wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
    $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    if (isset($wp_customize->selective_refresh)) {
        $wp_customize->selective_refresh->add_partial(
            'blogname',
            array(
                'selector'        => '.site-title a',
                'render_callback' => 'migv_customize_partial_blogname',
            )
        );
        $wp_customize->selective_refresh->add_partial(
            'blogdescription',
            array(
                'selector'        => '.site-description',
                'render_callback' => 'migv_customize_partial_blogdescription',
            )
        );
    }

    // Theme Options Panel
    $wp_customize->add_panel('migv_theme_options', array(
        'title'       => __('miGV Theme Options', 'migv'),
        'description' => __('Customize your miGV theme settings.', 'migv'),
        'priority'    => 30,
    ));

    // Colors Section
    $wp_customize->add_section('migv_colors', array(
        'title'    => __('Colors', 'migv'),
        'panel'    => 'migv_theme_options',
        'priority' => 10,
    ));

    // Primary Color
    $wp_customize->add_setting('migv_primary_color', array(
        'default'           => '#2563eb',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'migv_primary_color', array(
        'label'    => __('Primary Color', 'migv'),
        'section'  => 'migv_colors',
        'settings' => 'migv_primary_color',
    )));

    // Secondary Color
    $wp_customize->add_setting('migv_secondary_color', array(
        'default'           => '#7c3aed',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'migv_secondary_color', array(
        'label'    => __('Secondary Color', 'migv'),
        'section'  => 'migv_colors',
        'settings' => 'migv_secondary_color',
    )));

    // Typography Section
    $wp_customize->add_section('migv_typography', array(
        'title'    => __('Typography', 'migv'),
        'panel'    => 'migv_theme_options',
        'priority' => 20,
    ));

    // Body Font Size
    $wp_customize->add_setting('migv_body_font_size', array(
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('migv_body_font_size', array(
        'label'       => __('Body Font Size (px)', 'migv'),
        'section'     => 'migv_typography',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 12,
            'max'  => 24,
            'step' => 1,
        ),
    ));

    // Layout Section
    $wp_customize->add_section('migv_layout', array(
        'title'    => __('Layout', 'migv'),
        'panel'    => 'migv_theme_options',
        'priority' => 30,
    ));

    // Container Width
    $wp_customize->add_setting('migv_container_width', array(
        'default'           => '1280',
        'sanitize_callback' => 'absint',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('migv_container_width', array(
        'label'       => __('Container Width (px)', 'migv'),
        'section'     => 'migv_layout',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 960,
            'max'  => 1920,
            'step' => 20,
        ),
    ));

    // Sidebar Position
    $wp_customize->add_setting('migv_sidebar_position', array(
        'default'           => 'right',
        'sanitize_callback' => 'migv_sanitize_sidebar_position',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('migv_sidebar_position', array(
        'label'   => __('Sidebar Position', 'migv'),
        'section' => 'migv_layout',
        'type'    => 'select',
        'choices' => array(
            'left'  => __('Left', 'migv'),
            'right' => __('Right', 'migv'),
            'none'  => __('No Sidebar', 'migv'),
        ),
    ));

    // Header Section
    $wp_customize->add_section('migv_header', array(
        'title'    => __('Header', 'migv'),
        'panel'    => 'migv_theme_options',
        'priority' => 40,
    ));

    // Header Style
    $wp_customize->add_setting('migv_header_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'migv_sanitize_header_style',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('migv_header_style', array(
        'label'   => __('Header Style', 'migv'),
        'section' => 'migv_header',
        'type'    => 'select',
        'choices' => array(
            'default' => __('Default', 'migv'),
            'minimal' => __('Minimal', 'migv'),
            'centered' => __('Centered', 'migv'),
        ),
    ));

    // Show Search in Header
    $wp_customize->add_setting('migv_header_search', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('migv_header_search', array(
        'label'   => __('Show Search in Header', 'migv'),
        'section' => 'migv_header',
        'type'    => 'checkbox',
    ));

    // Footer Section
    $wp_customize->add_section('migv_footer', array(
        'title'    => __('Footer', 'migv'),
        'panel'    => 'migv_theme_options',
        'priority' => 50,
    ));

    // Footer Copyright Text
    $wp_customize->add_setting('migv_footer_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'postMessage',
    ));

    $wp_customize->add_control('migv_footer_copyright', array(
        'label'   => __('Footer Copyright Text', 'migv'),
        'section' => 'migv_footer',
        'type'    => 'textarea',
    ));

    // Show Footer Widgets
    $wp_customize->add_setting('migv_footer_widgets', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control('migv_footer_widgets', array(
        'label'   => __('Show Footer Widgets', 'migv'),
        'section' => 'migv_footer',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'migv_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function migv_customize_partial_blogname() {
    bloginfo('name');
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function migv_customize_partial_blogdescription() {
    bloginfo('description');
}

/**
 * Sanitize sidebar position.
 */
function migv_sanitize_sidebar_position($input) {
    $valid = array('left', 'right', 'none');
    return in_array($input, $valid, true) ? $input : 'right';
}

/**
 * Sanitize header style.
 */
function migv_sanitize_header_style($input) {
    $valid = array('default', 'minimal', 'centered');
    return in_array($input, $valid, true) ? $input : 'default';
}

/**
 * Output customizer CSS
 */
function migv_customizer_css() {
    $primary_color = get_theme_mod('migv_primary_color', '#2563eb');
    $secondary_color = get_theme_mod('migv_secondary_color', '#7c3aed');
    $body_font_size = get_theme_mod('migv_body_font_size', 16);
    $container_width = get_theme_mod('migv_container_width', 1280);

    $css = '';

    if ($primary_color !== '#2563eb' || $secondary_color !== '#7c3aed' || $body_font_size !== 16 || $container_width !== 1280) {
        $css .= ':root {';
        
        if ($primary_color !== '#2563eb') {
            $css .= '--migv-primary: ' . esc_attr($primary_color) . ';';
            $css .= '--migv-primary-light: color-mix(in srgb, ' . esc_attr($primary_color) . ' 60%, white);';
            $css .= '--migv-primary-dark: color-mix(in srgb, ' . esc_attr($primary_color) . ' 80%, black);';
        }
        
        if ($secondary_color !== '#7c3aed') {
            $css .= '--migv-secondary: ' . esc_attr($secondary_color) . ';';
            $css .= '--migv-secondary-light: color-mix(in srgb, ' . esc_attr($secondary_color) . ' 60%, white);';
            $css .= '--migv-secondary-dark: color-mix(in srgb, ' . esc_attr($secondary_color) . ' 80%, black);';
        }
        
        if ($body_font_size !== 16) {
            $css .= '--migv-font-size-base: ' . absint($body_font_size) . 'px;';
        }
        
        if ($container_width !== 1280) {
            $css .= '--migv-container-width: ' . absint($container_width) . 'px;';
        }
        
        $css .= '}';
    }

    if (!empty($css)) {
        echo '<style type="text/css" id="migv-customizer-css">' . $css . '</style>';
    }
}
add_action('wp_head', 'migv_customizer_css');
