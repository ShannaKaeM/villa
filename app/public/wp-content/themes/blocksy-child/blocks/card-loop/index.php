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
        ->set_mode('edit')  // This ensures the block is always in edit mode
        ->set_inner_blocks(false)  // No inner blocks allowed
        ->set_preview_mode('auto')  // Auto preview mode
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
                ->set_width(100)
                ->set_help_text('Select which post type to display'),
            Field::make('checkbox', 'show_filters', __('Show Filters'))
                ->set_default_value(true)
                ->set_help_text('Whether to show the filter sidebar'),
            
            // Query Settings
            Field::make('separator', 'query_settings', __('Query Settings')),
            Field::make('select', 'posts_per_page', __('Number of Properties'))
                ->set_options([
                    '3' => '3 Properties',
                    '6' => '6 Properties',
                    '9' => '9 Properties',
                    '12' => '12 Properties',
                    '15' => '15 Properties',
                    '18' => '18 Properties',
                    '24' => '24 Properties',
                    '-1' => 'All Properties',
                ])
                ->set_default_value('6')
                ->set_width(100)
                ->set_help_text('Select how many properties to display per page'),
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
                    'sm' => 'Small',
                    'lg' => 'Large',
                    'primary' => 'Primary',
                    'secondary' => 'Secondary',
                    'neutral' => 'Neutral'
                ])
                ->set_default_value('default')
                ->set_help_text('Style of the cards - size or color variants'),
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
                    $post_id = get_the_ID();
                    
                    // Get property data from custom fields
                    // Location
                    $location = '';
                    $location_terms = get_the_terms($post_id, 'property_location');
                    if (!empty($location_terms) && !is_wp_error($location_terms)) {
                        $location = $location_terms[0]->name;
                    }
                    
                    // Property Type
                    $property_type = '';
                    $property_type_terms = get_the_terms($post_id, 'property_type');
                    if (!empty($property_type_terms) && !is_wp_error($property_type_terms)) {
                        $property_type = $property_type_terms[0]->name;
                    }
                    
                    // Get property type icon
                    $property_type_icon = '🏠'; // Default icon
                    if (stripos($property_type, 'house') !== false) {
                        $property_type_icon = '🏠';
                    } elseif (stripos($property_type, 'condo') !== false) {
                        $property_type_icon = '🏢';
                    } elseif (stripos($property_type, 'villa') !== false) {
                        $property_type_icon = '🏛️';
                    } elseif (stripos($property_type, 'apartment') !== false) {
                        $property_type_icon = '🏘️';
                    }
                    
                    // Get amenities
                    $amenities = [];
                    $amenity_terms = get_the_terms($post_id, 'property_amenity');
                    if (!empty($amenity_terms) && !is_wp_error($amenity_terms)) {
                        foreach ($amenity_terms as $term) {
                            $icon = '✨'; // Default icon
                            
                            // Assign icons based on amenity name
                            if (stripos($term->name, 'pool') !== false) {
                                $icon = '🏊';
                            } elseif (stripos($term->name, 'wifi') !== false || stripos($term->name, 'internet') !== false) {
                                $icon = '📶';
                            } elseif (stripos($term->name, 'pet') !== false) {
                                $icon = '🐕';
                            } elseif (stripos($term->name, 'view') !== false || stripos($term->name, 'ocean') !== false) {
                                $icon = '🌅';
                            } elseif (stripos($term->name, 'kitchen') !== false) {
                                $icon = '🍳';
                            } elseif (stripos($term->name, 'air') !== false || stripos($term->name, 'ac') !== false) {
                                $icon = '❄️';
                            } elseif (stripos($term->name, 'parking') !== false) {
                                $icon = '🅿️';
                            }
                            
                            $amenities[] = [
                                'name' => $term->name,
                                'icon' => $icon
                            ];
                        }
                    }
                    
                    // Get custom field values using Carbon Fields
                    $bedrooms = carbon_get_post_meta($post_id, 'property_bedrooms') ?: 0;
                    $bathrooms = carbon_get_post_meta($post_id, 'property_bathrooms') ?: 0;
                    $guests = carbon_get_post_meta($post_id, 'property_max_guests') ?: 0;
                    $price = carbon_get_post_meta($post_id, 'property_price') ?: '';
                    
                    // Format price if it exists
                    if (!empty($price)) {
                        $price = '$' . number_format($price) . '/night';
                    }
                    
                    // Build the property data array
                    $property = [
                        'id' => $post_id,
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'description' => get_the_excerpt(),
                        'image' => get_the_post_thumbnail_url($post_id, 'large'),
                        'location' => $location,
                        'location_icon' => '📍',
                        'property_type' => $property_type,
                        'property_type_icon' => $property_type_icon,
                        'bedrooms' => $bedrooms,
                        'bathrooms' => $bathrooms,
                        'guests' => $guests,
                        'price' => $price,
                        'amenities' => $amenities,
                    ];
                    
                    $properties[] = $property;
                }
                wp_reset_postdata();
            }
            
            // Get filter data from actual taxonomies
            $filters = [
                'property_types' => [],
                'locations' => [],
                'amenities' => [],
            ];
            
            // Get property types
            $property_type_terms = get_terms([
                'taxonomy' => 'property_type',
                'hide_empty' => true,
            ]);
            
            if (!empty($property_type_terms) && !is_wp_error($property_type_terms)) {
                foreach ($property_type_terms as $term) {
                    $icon = '🏠'; // Default icon
                    
                    // Assign icons based on term name
                    if (stripos($term->name, 'house') !== false) {
                        $icon = '🏠';
                    } elseif (stripos($term->name, 'condo') !== false) {
                        $icon = '🏢';
                    } elseif (stripos($term->name, 'villa') !== false) {
                        $icon = '🏛️';
                    } elseif (stripos($term->name, 'apartment') !== false) {
                        $icon = '🏘️';
                    }
                    
                    $filters['property_types'][] = [
                        'name' => $term->name,
                        'count' => $term->count,
                        'icon' => $icon,
                    ];
                }
            }
            
            // Get locations
            $location_terms = get_terms([
                'taxonomy' => 'property_location',
                'hide_empty' => true,
            ]);
            
            if (!empty($location_terms) && !is_wp_error($location_terms)) {
                foreach ($location_terms as $term) {
                    $icon = '📍'; // Default icon
                    
                    // Assign beach-related icons if relevant
                    if (stripos($term->name, 'beach') !== false) {
                        $icon = '🏖️';
                    } elseif (stripos($term->name, 'city') !== false) {
                        $icon = '🌆';
                    } elseif (stripos($term->name, 'mountain') !== false) {
                        $icon = '⛰️';
                    } elseif (stripos($term->name, 'lake') !== false) {
                        $icon = '🏞️';
                    }
                    
                    $filters['locations'][] = [
                        'name' => $term->name,
                        'count' => $term->count,
                        'icon' => $icon,
                    ];
                }
            }
            
            // Get amenities
            $amenity_terms = get_terms([
                'taxonomy' => 'property_amenity',
                'hide_empty' => true,
            ]);
            
            if (!empty($amenity_terms) && !is_wp_error($amenity_terms)) {
                foreach ($amenity_terms as $term) {
                    $icon = '✨'; // Default icon
                    
                    // Assign icons based on amenity name
                    if (stripos($term->name, 'pool') !== false) {
                        $icon = '🏊';
                    } elseif (stripos($term->name, 'wifi') !== false || stripos($term->name, 'internet') !== false) {
                        $icon = '📶';
                    } elseif (stripos($term->name, 'pet') !== false) {
                        $icon = '🐕';
                    } elseif (stripos($term->name, 'view') !== false || stripos($term->name, 'ocean') !== false) {
                        $icon = '🌅';
                    } elseif (stripos($term->name, 'kitchen') !== false) {
                        $icon = '🍳';
                    } elseif (stripos($term->name, 'air') !== false || stripos($term->name, 'ac') !== false) {
                        $icon = '❄️';
                    } elseif (stripos($term->name, 'parking') !== false) {
                        $icon = '🅿️';
                    }
                    
                    $filters['amenities'][] = [
                        'name' => $term->name,
                        'icon' => $icon,
                    ];
                }
            }
            
            // If any filter categories are empty, add some defaults
            if (empty($filters['property_types'])) {
                $filters['property_types'] = [
                    ['name' => 'House', 'count' => 0, 'icon' => '🏠'],
                    ['name' => 'Condo', 'count' => 0, 'icon' => '🏢'],
                    ['name' => 'Villa', 'count' => 0, 'icon' => '🏛️'],
                ];
            }
            
            if (empty($filters['locations'])) {
                $filters['locations'] = [
                    ['name' => 'North Topsail Beach', 'count' => 0, 'icon' => '🏖️'],
                    ['name' => 'Surf City', 'count' => 0, 'icon' => '🌊'],
                    ['name' => 'Topsail Beach', 'count' => 0, 'icon' => '🏝️'],
                ];
            }
            
            if (empty($filters['amenities'])) {
                $filters['amenities'] = [
                    ['name' => 'Pool', 'icon' => '🏊'],
                    ['name' => 'Ocean View', 'icon' => '🌅'],
                    ['name' => 'Pet Friendly', 'icon' => '🐕'],
                    ['name' => 'WiFi', 'icon' => '📶'],
                ];
            }
            
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
