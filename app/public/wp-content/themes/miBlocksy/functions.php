<?php
/**
 * miBlocksy Child Theme Functions
 * 
 * @package miBlocksy
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include helper functions
require_once get_stylesheet_directory() . '/inc/helpers.php';

/**
 * Enqueue parent and child theme styles
 */
function miblocksy_enqueue_styles() {
    // Get parent theme version
    $parent_style = 'blocksy-style';
    
    // Enqueue parent theme stylesheet
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    
    // Enqueue child theme stylesheet
    wp_enqueue_style(
        'miblocksy-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'miblocksy_enqueue_styles');

/**
 * Theme setup
 */
function miblocksy_setup() {
    // Add theme support for various features
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');
}
add_action('after_setup_theme', 'miblocksy_setup');

/**
 * Customize Blocksy options
 */
function miblocksy_customize_blocksy() {
    // Add custom colors to Blocksy palette
    add_filter('blocksy:customizer:options', function($options) {
        // You can modify Blocksy's customizer options here
        return $options;
    });
}
add_action('init', 'miblocksy_customize_blocksy');

/**
 * Add custom CSS variables to frontend
 */
function miblocksy_custom_css_variables() {
    ?>
    <style>
        :root {
            /* Override or extend Blocksy's CSS variables */
            --theme-palette-color-1: var(--mi-primary);
            --theme-palette-color-2: var(--mi-secondary);
            --theme-palette-color-3: var(--mi-neutral);
            --theme-palette-color-4: var(--mi-base);
        }
    </style>
    <?php
}
add_action('wp_head', 'miblocksy_custom_css_variables');

/**
 * Register custom block category
 */
function miblocksy_block_categories($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'miblocksy',
                'title' => __('miBlocksy Blocks', 'miblocksy'),
                'icon'  => 'layout',
            ),
        )
    );
}
add_filter('block_categories_all', 'miblocksy_block_categories', 10, 2);

/**
 * Load custom blocks
 */
function miblocksy_load_blocks() {
    // Register custom blocks here
    // Example:
    // register_block_type(__DIR__ . '/blocks/custom-block');
}
add_action('init', 'miblocksy_load_blocks');

/**
 * Customize excerpt length
 */
function miblocksy_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'miblocksy_excerpt_length');

/**
 * Customize excerpt more text
 */
function miblocksy_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'miblocksy_excerpt_more');
