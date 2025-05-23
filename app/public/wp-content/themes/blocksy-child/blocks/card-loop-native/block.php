<?php
/**
 * Card Loop Block - Native WordPress Implementation
 * 
 * This is a native WordPress block implementation of the Card Loop
 * that uses the existing CSS architecture.
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register the Card Loop Native block
 */
function mi_register_card_loop_native_block() {
    // Skip block registration if Gutenberg is not available
    if (!function_exists('register_block_type')) {
        return;
    }
    
    // Register the block
    register_block_type('mi/card-loop', [
        'editor_script' => 'mi-card-loop-editor',
        'editor_style' => 'mi-card-loop-editor',
        'style' => 'mi-card-loop',
        'render_callback' => 'mi_render_card_loop_block',
        'attributes' => [
            'title' => [
                'type' => 'string',
                'default' => ''
            ],
            'post_type' => [
                'type' => 'string',
                'default' => 'property'
            ],
            'show_filters' => [
                'type' => 'boolean',
                'default' => true
            ],
            'posts_per_page' => [
                'type' => 'string',
                'default' => '9'
            ],
            'columns' => [
                'type' => 'string',
                'default' => '3'
            ],
            'card_style' => [
                'type' => 'string',
                'default' => 'default'
            ]
        ]
    ]);
    
    // Register block editor script
    wp_register_script(
        'mi-card-loop-editor',
        get_stylesheet_directory_uri() . '/blocks/card-loop-native/editor.js',
        ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components'],
        filemtime(get_stylesheet_directory() . '/blocks/card-loop-native/editor.js')
    );
    
    // Register editor styles
    wp_register_style(
        'mi-card-loop-editor',
        get_stylesheet_directory_uri() . '/blocks/card-loop-native/editor.css',
        [],
        filemtime(get_stylesheet_directory() . '/blocks/card-loop-native/editor.css')
    );
    
    // Register frontend styles
    wp_register_style(
        'mi-card-loop',
        get_stylesheet_directory_uri() . '/blocks/card-loop-native/style.css',
        [],
        filemtime(get_stylesheet_directory() . '/blocks/card-loop-native/style.css')
    );
    
    // Register additional styles
    wp_register_style(
        'mi-card-loop-additional',
        get_stylesheet_directory_uri() . '/blocks/card-loop-native/additional-styles.css',
        ['mi-card-loop'],
        filemtime(get_stylesheet_directory() . '/blocks/card-loop-native/additional-styles.css')
    );
    
    // Add the additional styles to the block
    wp_enqueue_style('mi-card-loop-additional');
}
add_action('init', 'mi_register_card_loop_native_block');

/**
 * Render the Card Loop block
 */
function mi_render_card_loop_block($attributes) {
    // Extract attributes with defaults
    $title = $attributes['title'] ?? '';
    $post_type = $attributes['post_type'] ?? 'property';
    $show_filters = $attributes['show_filters'] ?? true;
    $posts_per_page = $attributes['posts_per_page'] ?? 6;
    $columns = $attributes['columns'] ?? 3;
    $card_style = $attributes['card_style'] ?? 'default';
    
    // Query for items
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $posts_per_page,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $query = new WP_Query($args);
    $items = [];
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            // Get featured image
            $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            
            // Get excerpt
            $excerpt = get_the_excerpt();
            if (empty($excerpt)) {
                $excerpt = wp_trim_words(get_the_content(), 20);
            }
            
            // Get property metadata if this is a property post type
            $meta = [];
            if ($post_type === 'property') {
                // These would typically come from custom fields
                $meta = [
                    'beds' => get_post_meta(get_the_ID(), 'property_beds', true) ?: rand(1, 5),
                    'baths' => get_post_meta(get_the_ID(), 'property_baths', true) ?: rand(1, 4),
                    'guests' => get_post_meta(get_the_ID(), 'property_guests', true) ?: rand(2, 10),
                    'type' => get_post_meta(get_the_ID(), 'property_type', true) ?: (
                        rand(0, 4) === 0 ? 'Villa' : (
                        rand(0, 3) === 0 ? 'Apartment' : (
                        rand(0, 2) === 0 ? 'Bungalow' : (
                        rand(0, 1) === 0 ? 'House' : 'Condo'
                        )))
                    ),
                    'price' => get_post_meta(get_the_ID(), 'property_price', true) ?: ('$' . rand(100, 500)),
                ];
            }
            
            // Build item data
            $items[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => $excerpt,
                'image' => $image,
                'link' => get_permalink(),
                'meta' => $meta,
            ];
        }
        wp_reset_postdata();
    }
    
    // Set up filters based on post type
    $filters = [];
    if ($show_filters) {
        if ($post_type === 'property') {
            $filters['amenities'] = [
                ['name' => 'Beach', 'icon' => '🏖️'],
                ['name' => 'Family', 'icon' => '👪'],
                ['name' => 'Food', 'icon' => '🍽️'],
                ['name' => 'Tips', 'icon' => '💡'],
            ];
        } else if ($post_type === 'business') {
            $filters['categories'] = [
                ['name' => 'Restaurant', 'icon' => '🍽️'],
                ['name' => 'Shopping', 'icon' => '🛍️'],
                ['name' => 'Entertainment', 'icon' => '🎭'],
                ['name' => 'Services', 'icon' => '🔧'],
            ];
        } else if ($post_type === 'article') {
            $filters['topics'] = [
                ['name' => 'Travel', 'icon' => '✈️'],
                ['name' => 'Food', 'icon' => '🍽️'],
                ['name' => 'Activities', 'icon' => '🏄‍♂️'],
                ['name' => 'Local', 'icon' => '📍'],
            ];
        } else if ($post_type === 'user_profile') {
            $filters['expertise'] = [
                ['name' => 'Sales', 'icon' => '💸'],
                ['name' => 'Management', 'icon' => '📈'],
                ['name' => 'Marketing', 'icon' => '📊'],
                ['name' => 'Customer Service', 'icon' => '👍'],
            ];
        }
    }
    
    // Start output buffer
    ob_start();
    
    // Include the template
    include(get_stylesheet_directory() . '/blocks/card-loop-native/template.php');
    
    // Return the output
    return ob_get_clean();
}
