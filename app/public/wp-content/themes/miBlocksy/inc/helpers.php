<?php
/**
 * Helper Functions for miBlocksy Theme
 * 
 * @package miBlocksy
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme color by name
 * 
 * @param string $color_name The color name (primary, secondary, neutral, base)
 * @return string The color value
 */
function miblocksy_get_color($color_name) {
    $colors = array(
        'primary'   => '#4d6a6d',
        'secondary' => '#9c5961',
        'neutral'   => '#a69f95',
        'base'      => '#808080',
        'white'     => '#ffffff',
        'black'     => '#000000',
    );
    
    return isset($colors[$color_name]) ? $colors[$color_name] : $colors['primary'];
}

/**
 * Get theme spacing value
 * 
 * @param string $size The spacing size (xs, sm, md, lg, xl, 2xl)
 * @return string The spacing value
 */
function miblocksy_get_spacing($size) {
    $spacing = array(
        'xs'  => '0.25rem',
        'sm'  => '0.5rem',
        'md'  => '1rem',
        'lg'  => '1.5rem',
        'xl'  => '2rem',
        '2xl' => '3rem',
    );
    
    return isset($spacing[$size]) ? $spacing[$size] : $spacing['md'];
}

/**
 * Generate CSS custom properties for colors
 * 
 * @return string CSS custom properties
 */
function miblocksy_get_color_css() {
    $colors = array(
        'primary'   => '#4d6a6d',
        'secondary' => '#9c5961',
        'neutral'   => '#a69f95',
        'base'      => '#808080',
    );
    
    $css = ':root {';
    
    foreach ($colors as $name => $color) {
        $css .= "--mi-{$name}: {$color};";
        $css .= "--mi-{$name}-light: color-mix(in srgb, {$color} 60%, white);";
        $css .= "--mi-{$name}-dark: color-mix(in srgb, {$color} 80%, black);";
    }
    
    $css .= '}';
    
    return $css;
}

/**
 * Check if we're in the block editor
 * 
 * @return bool
 */
function miblocksy_is_block_editor() {
    if (function_exists('get_current_screen')) {
        $screen = get_current_screen();
        return $screen && $screen->is_block_editor();
    }
    
    return false;
}

/**
 * Get post excerpt with custom length
 * 
 * @param int $length Number of words
 * @param int $post_id Post ID (optional)
 * @return string
 */
function miblocksy_get_excerpt($length = 25, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $excerpt = get_post_field('post_excerpt', $post_id);
    
    if (empty($excerpt)) {
        $excerpt = get_post_field('post_content', $post_id);
    }
    
    return wp_trim_words($excerpt, $length, '...');
}

/**
 * Generate responsive image HTML
 * 
 * @param int $attachment_id Image attachment ID
 * @param string $size Image size
 * @param array $attributes Additional attributes
 * @return string
 */
function miblocksy_get_responsive_image($attachment_id, $size = 'large', $attributes = array()) {
    if (!$attachment_id) {
        return '';
    }
    
    $default_attributes = array(
        'loading' => 'lazy',
        'decoding' => 'async',
    );
    
    $attributes = array_merge($default_attributes, $attributes);
    
    return wp_get_attachment_image($attachment_id, $size, false, $attributes);
}
