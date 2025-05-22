<?php
/**
 * Card Loop Block Registration
 * 
 * Registers the Card Loop block with Carbon Fields
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

use Carbon_Fields\Block;
use Carbon_Fields\Field;

/**
 * Register the Card Loop Block
 */
function mi_register_card_loop_block() {
    Block::make(__('Card Loop'))
        ->add_fields([
            // General Settings
            Field::make('separator', 'general_settings', __('General Settings')),
            Field::make('text', 'title', __('Title'))
                ->set_help_text('Main title for the block'),
            Field::make('select', 'post_type', __('Post Type'))
                ->set_options([
                    'property' => 'Properties',
                    'business' => 'Businesses',
                    'article' => 'Articles',
                    'user_profile' => 'User Profiles',
                ])
                ->set_default_value('property')
                ->set_help_text('Select which post type to display'),
            Field::make('checkbox', 'show_filters', __('Show Filters'))
                ->set_default_value(true)
                ->set_help_text('Whether to show the filter sidebar'),
            
            // Query Settings
            Field::make('separator', 'query_settings', __('Query Settings')),
            Field::make('text', 'posts_per_page', __('Posts Per Page'))
                ->set_attribute('type', 'number')
                ->set_default_value(6)
                ->set_help_text('Number of items to display'),
            Field::make('multiselect', 'taxonomies', __('Filter by Taxonomies'))
                ->set_options(function() {
                    $options = [];
                    $taxonomies = get_object_taxonomies('property', 'objects');
                    
                    foreach ($taxonomies as $taxonomy) {
                        $options[$taxonomy->name] = $taxonomy->label;
                    }
                    
                    return $options;
                })
                ->set_help_text('Select which taxonomies to use for filtering'),
            
            // Layout Settings
            Field::make('separator', 'layout_settings', __('Layout Settings')),
            Field::make('select', 'columns', __('Columns'))
                ->set_options([
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                ])
                ->set_default_value('3')
                ->set_help_text('Number of columns in the grid'),
            Field::make('select', 'card_style', __('Card Style'))
                ->set_options([
                    'default' => 'Default',
                    'compact' => 'Compact',
                    'expanded' => 'Expanded',
                ])
                ->set_default_value('default')
                ->set_help_text('Style of the property cards'),
        ])
        ->set_description(__('Displays a filterable grid of property cards'))
        ->set_category('miblocks')  // IMPORTANT: Use 'miblocks' (lowercase) to match the category slug
        ->set_icon('grid-view')
        ->set_render_callback(function($fields, $attributes, $inner_blocks) {
            // Extract fields with defaults
            $title = $fields['title'] ?? '';
            $post_type = $fields['post_type'] ?? 'property';
            $show_filters = $fields['show_filters'] ?? true;
            $posts_per_page = $fields['posts_per_page'] ?? 6;
            $taxonomies = $fields['taxonomies'] ?? [];
            $columns = $fields['columns'] ?? '3';
            $card_style = $fields['card_style'] ?? 'default';
            
            // Query for properties
            $args = [
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'post_status' => 'publish',
            ];
            
            $query = new WP_Query($args);
            $properties = [];
            
            // Process query results
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    
                    // Get property data
                    $property = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'description' => get_the_excerpt(),
                        'image' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
                        'location' => 'North Topsail Beach', // This would come from custom fields
                        'location_icon' => '📍',
                        'property_type' => 'House', // This would come from taxonomy
                        'property_type_icon' => '🏠',
                        'bedrooms' => 3, // This would come from custom fields
                        'bathrooms' => 2, // This would come from custom fields
                        'guests' => 6, // This would come from custom fields
                        'price' => '$200/night', // This would come from custom fields
                        'amenities' => [
                            ['name' => 'Pool', 'icon' => '🏊'],
                            ['name' => 'WiFi', 'icon' => '📶'],
                        ],
                    ];
                    
                    $properties[] = $property;
                }
                wp_reset_postdata();
            }
            
            // Get filter data
            $filters = [
                'property_types' => [
                    ['name' => 'House', 'count' => 12, 'icon' => '🏠'],
                    ['name' => 'Condo', 'count' => 8, 'icon' => '🏢'],
                    ['name' => 'Villa', 'count' => 6, 'icon' => '🏛️'],
                ],
                'locations' => [
                    ['name' => 'North Topsail Beach', 'count' => 15, 'icon' => '🏖️'],
                    ['name' => 'Surf City', 'count' => 8, 'icon' => '🌊'],
                    ['name' => 'Topsail Beach', 'count' => 3, 'icon' => '🏝️'],
                ],
                'amenities' => [
                    ['name' => 'Pool', 'icon' => '🏊'],
                    ['name' => 'Ocean View', 'icon' => '🌅'],
                    ['name' => 'Pet Friendly', 'icon' => '🐕'],
                    ['name' => 'WiFi', 'icon' => '📶'],
                ],
            ];
            
            // Set up template variables
            $template_vars = [
                'properties' => $properties,
                'filters' => $filters,
                'count' => count($properties),
                'show_filters' => $show_filters,
                'columns' => $columns,
                'card_style' => $card_style,
                'wrapper_attributes' => 'class="mi-card-loop"',
            ];
            
            // Extract variables for the template
            extract($template_vars);
            
            // Include the template
            include(__DIR__ . '/template.php');
        });
}
add_action('carbon_fields_register_fields', 'mi_register_card_loop_block');
