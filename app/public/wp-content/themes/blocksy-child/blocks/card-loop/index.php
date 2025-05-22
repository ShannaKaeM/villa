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
            Field::make('select', 'posts_per_page', __('Number of Cards'))
                ->set_options([
                    '3' => '3 Cards',
                    '6' => '6 Cards',
                    '9' => '9 Cards',
                    '12' => '12 Cards',
                    '15' => '15 Cards',
                    '18' => '18 Cards',
                    '24' => '24 Cards',
                    '-1' => 'All Cards',
                ])
                ->set_default_value('6')
                ->set_width(100)
                ->set_help_text('Select how many items to display per page'),
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
            $posts_per_page = $fields['posts_per_page'] ?? 6;
            $show_filters = $fields['show_filters'] ?? true;
            $columns = $fields['columns'] ?? '3';
            $card_style = $fields['card_style'] ?? 'default';
            
            // Set up WP_Query
            $args = [
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'post_status' => 'publish',
            ];
            
            $query = new WP_Query($args);
            $items = [];
            
            // Process query results
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $post_id = get_the_ID();
                    
                    // Base item data for all post types
                    $item = [
                        'id' => $post_id,
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'description' => get_the_excerpt(),
                        'image' => get_the_post_thumbnail_url($post_id, 'large'),
                    ];
                    
                    if ($post_type === 'property') {
                        // Get property meta
                        $item['price'] = carbon_get_post_meta($post_id, 'property_nightly_rate') ? '$' . number_format(carbon_get_post_meta($post_id, 'property_nightly_rate')) . '/night' : '';
                        $item['bedrooms'] = carbon_get_post_meta($post_id, 'property_bedrooms') ?: 0;
                        $item['bathrooms'] = carbon_get_post_meta($post_id, 'property_bathrooms') ?: 0;
                        $item['guests'] = carbon_get_post_meta($post_id, 'property_max_guests') ?: 0;
                        
                        // Get property type
                        $type_terms = get_the_terms($post_id, 'property_type');
                        if (!empty($type_terms) && !is_wp_error($type_terms)) {
                            $item['type'] = $type_terms[0]->name;
                            
                            // Assign icon based on property type
                            $item['type_icon'] = '🏠'; // Default icon
                            
                            if (stripos($item['type'], 'house') !== false) {
                                $item['type_icon'] = '🏠';
                            } elseif (stripos($item['type'], 'condo') !== false || stripos($item['type'], 'apartment') !== false) {
                                $item['type_icon'] = '🏢';
                            } elseif (stripos($item['type'], 'villa') !== false) {
                                $item['type_icon'] = '🏛️';
                            } elseif (stripos($item['type'], 'cabin') !== false) {
                                $item['type_icon'] = '🏡';
                            } elseif (stripos($item['type'], 'beach') !== false) {
                                $item['type_icon'] = '🏖️';
                            }
                        } else {
                            $item['type'] = 'Property';
                            $item['type_icon'] = '🏠';
                        }
                        
                        // Get location
                        $location_terms = get_the_terms($post_id, 'property_location');
                        if (!empty($location_terms) && !is_wp_error($location_terms)) {
                            $item['location'] = $location_terms[0]->name;
                        } else {
                            $item['location'] = '';
                        }
                        
                        // Get amenities
                        $amenity_terms = get_the_terms($post_id, 'property_amenity');
                        if (!empty($amenity_terms) && !is_wp_error($amenity_terms)) {
                            $item['amenities'] = [];
                            
                            foreach ($amenity_terms as $term) {
                                $icon = '✨'; // Default icon
                                
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
                                
                                $item['amenities'][] = [
                                    'name' => $term->name,
                                    'icon' => $icon
                                ];
                            }
                        }
                    } elseif ($post_type === 'article') {
                        // Get article meta
                        $item['reading_time'] = carbon_get_post_meta($post_id, 'article_reading_time') ?: '5 min';
                        $item['author'] = get_the_author_meta('display_name', get_post_field('post_author', $post_id));
                        $item['date'] = get_the_date('F j, Y', $post_id);
                        
                        // Get article type from category
                        $categories = get_the_category($post_id);
                        if (!empty($categories)) {
                            $item['type'] = $categories[0]->name;
                            
                            // Assign icon based on category
                            $item['type_icon'] = '📝'; // Default icon
                            
                            if (stripos($item['type'], 'news') !== false) {
                                $item['type_icon'] = '📰';
                            } elseif (stripos($item['type'], 'guide') !== false || stripos($item['type'], 'how-to') !== false) {
                                $item['type_icon'] = '📖';
                            } elseif (stripos($item['type'], 'review') !== false) {
                                $item['type_icon'] = '💬';
                            } elseif (stripos($item['type'], 'event') !== false) {
                                $item['type_icon'] = '🎉';
                            } elseif (stripos($item['type'], 'travel') !== false) {
                                $item['type_icon'] = '✈️';
                            }
                        } else {
                            $item['type'] = 'Article';
                            $item['type_icon'] = '📝';
                        }
                        
                        // Get tags as amenities
                        $tags = get_the_tags($post_id);
                        if (!empty($tags)) {
                            $item['amenities'] = [];
                            
                            foreach ($tags as $tag) {
                                $icon = '🏷️'; // Default icon
                                
                                // Assign specific icons based on common tag names
                                if (stripos($tag->name, 'tip') !== false) {
                                    $icon = '💡';
                                } elseif (stripos($tag->name, 'beach') !== false) {
                                    $icon = '🏖️';
                                } elseif (stripos($tag->name, 'food') !== false || stripos($tag->name, 'restaurant') !== false) {
                                    $icon = '🍽️';
                                } elseif (stripos($tag->name, 'family') !== false) {
                                    $icon = '👪';
                                } elseif (stripos($tag->name, 'activity') !== false) {
                                    $icon = '🏟️';
                                }
                                
                                $item['amenities'][] = [
                                    'name' => $tag->name,
                                    'icon' => $icon
                                ];
                            }
                        }
                        
                        // Set location if available (could be from a custom field or taxonomy)
                        $item['location'] = carbon_get_post_meta($post_id, 'article_location') ?: '';
                        
                        // Use reading time as price equivalent for display
                        $item['price'] = $item['reading_time'] . ' read';
                    } elseif ($post_type === 'user_profile') {
                        // Get user profile meta
                        $item['role'] = carbon_get_post_meta($post_id, 'user_role') ?: 'Member';
                        $item['specialty'] = carbon_get_post_meta($post_id, 'user_specialty') ?: '';
                        $item['contact_email'] = carbon_get_post_meta($post_id, 'user_contact_email') ?: '';
                        $item['contact_phone'] = carbon_get_post_meta($post_id, 'user_contact_phone') ?: '';
                        $item['years_experience'] = carbon_get_post_meta($post_id, 'user_years_experience') ?: 0;
                        
                        // Get user profile type from taxonomy or custom field
                        $type_terms = get_the_terms($post_id, 'user_type');
                        if (!empty($type_terms) && !is_wp_error($type_terms)) {
                            $item['type'] = $type_terms[0]->name;
                            
                            // Assign icon based on user type
                            $item['type_icon'] = '👤'; // Default icon
                            
                            if (stripos($item['type'], 'agent') !== false || stripos($item['type'], 'realtor') !== false) {
                                $item['type_icon'] = '🏠';
                            } elseif (stripos($item['type'], 'owner') !== false) {
                                $item['type_icon'] = '🔑';
                            } elseif (stripos($item['type'], 'manager') !== false) {
                                $item['type_icon'] = '💼';
                            } elseif (stripos($item['type'], 'staff') !== false) {
                                $item['type_icon'] = '👷';
                            } elseif (stripos($item['type'], 'contractor') !== false) {
                                $item['type_icon'] = '🔨';
                            }
                        } else {
                            // If no taxonomy, use the role field
                            $item['type'] = $item['role'];
                            $item['type_icon'] = '👤';
                        }
                        
                        // Get skills as amenities
                        $skills = carbon_get_post_meta($post_id, 'user_skills');
                        if (!empty($skills)) {
                            $item['amenities'] = [];
                            
                            foreach ($skills as $skill) {
                                $icon = '✨'; // Default icon
                                $skill_name = $skill['name'] ?? $skill;
                                
                                // Assign specific icons based on common skill names
                                if (stripos($skill_name, 'sales') !== false) {
                                    $icon = '💸';
                                } elseif (stripos($skill_name, 'management') !== false) {
                                    $icon = '📈';
                                } elseif (stripos($skill_name, 'marketing') !== false) {
                                    $icon = '📊';
                                } elseif (stripos($skill_name, 'design') !== false) {
                                    $icon = '🎨';
                                } elseif (stripos($skill_name, 'customer') !== false) {
                                    $icon = '👍';
                                }
                                
                                $item['amenities'][] = [
                                    'name' => $skill_name,
                                    'icon' => $icon
                                ];
                            }
                        }
                        
                        // Set location if available
                        $item['location'] = carbon_get_post_meta($post_id, 'user_location') ?: '';
                        
                        // Use years of experience or role as price equivalent for display
                        if (!empty($item['years_experience'])) {
                            $item['price'] = $item['years_experience'] . '+ years';
                        } else {
                            $item['price'] = $item['role'];
                        }
                    } elseif ($post_type === 'business') {
                        // Get business meta
                        $item['phone'] = carbon_get_post_meta($post_id, 'business_phone') ?: '';
                        $item['website'] = carbon_get_post_meta($post_id, 'business_website') ?: '';
                        $item['address'] = carbon_get_post_meta($post_id, 'business_address') ?: '';
                        $item['city'] = carbon_get_post_meta($post_id, 'business_city') ?: '';
                        $item['state'] = carbon_get_post_meta($post_id, 'business_state') ?: '';
                        $item['is_featured'] = carbon_get_post_meta($post_id, 'business_is_featured') ?: false;
                        
                        // Format full address
                        $item['location'] = $item['address'];
                        if (!empty($item['city'])) {
                            $item['location'] .= ', ' . $item['city'];
                        }
                        if (!empty($item['state'])) {
                            $item['location'] .= ', ' . $item['state'];
                        }
                        
                        // Get business hours
                        $business_hours = carbon_get_post_meta($post_id, 'business_hours');
                        if (!empty($business_hours)) {
                            $item['hours'] = $business_hours;
                            
                            // Check if open now
                            $current_day = strtolower(date('l'));
                            $current_time = date('H:i');
                            $is_open = false;
                            
                            foreach ($business_hours as $hours) {
                                if ($hours['day'] === $current_day && !isset($hours['closed'])) {
                                    $open_time = $hours['open'];
                                    $close_time = $hours['close'];
                                    
                                    if ($current_time >= $open_time && $current_time <= $close_time) {
                                        $is_open = true;
                                        break;
                                    }
                                }
                            }
                            
                            $item['is_open'] = $is_open;
                            $item['price'] = $is_open ? 'Open Now' : 'Closed';
                        }
                        
                        // Get business type
                        $type_terms = get_the_terms($post_id, 'business_type');
                        if (!empty($type_terms) && !is_wp_error($type_terms)) {
                            $item['type'] = $type_terms[0]->name;
                            
                            // Assign icon based on business type
                            $item['type_icon'] = '🏪'; // Default icon
                            
                            if (stripos($item['type'], 'restaurant') !== false || stripos($item['type'], 'food') !== false) {
                                $item['type_icon'] = '🍽️';
                            } elseif (stripos($item['type'], 'bar') !== false || stripos($item['type'], 'pub') !== false) {
                                $item['type_icon'] = '🍸';
                            } elseif (stripos($item['type'], 'shop') !== false || stripos($item['type'], 'store') !== false) {
                                $item['type_icon'] = '🛍️';
                            } elseif (stripos($item['type'], 'hotel') !== false || stripos($item['type'], 'lodging') !== false) {
                                $item['type_icon'] = '🏨';
                            } elseif (stripos($item['type'], 'activity') !== false || stripos($item['type'], 'attraction') !== false) {
                                $item['type_icon'] = '🎯';
                            } elseif (stripos($item['type'], 'service') !== false) {
                                $item['type_icon'] = '🔧';
                            }
                        } else {
                            $item['type'] = 'Business';
                            $item['type_icon'] = '🏪';
                        }
                        
                        // Get special offers
                        $special_offers = carbon_get_post_meta($post_id, 'business_special_offers');
                        if (!empty($special_offers)) {
                            $item['amenities'] = [];
                            
                            foreach ($special_offers as $offer) {
                                // Only include valid offers
                                if (empty($offer['valid_until']) || strtotime($offer['valid_until']) >= time()) {
                                    $item['amenities'][] = [
                                        'name' => $offer['title'],
                                        'icon' => '🏷️'
                                    ];
                                }
                            }
                        }
                    }
                    
                    $items[] = $item;
                }
                wp_reset_postdata();
            }
            
            // Get filter data from actual taxonomies based on post type
            $filters = [
                'types' => [],
                'locations' => [],
                'amenities' => [],
            ];
            
            if ($post_type === 'property') {
                // Get property types
                $type_terms = get_terms([
                    'taxonomy' => 'property_type',
                    'hide_empty' => true,
                ]);
                
                if (!empty($type_terms) && !is_wp_error($type_terms)) {
                    foreach ($type_terms as $term) {
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
                        } elseif (stripos($term->name, 'cabin') !== false) {
                            $icon = '🏡';
                        }
                        
                        $filters['types'][] = [
                            'name' => $term->name,
                            'count' => $term->count,
                            'icon' => $icon,
                        ];
                    }
                }
                
                // Get property locations
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
                
                // Get property amenities
                $amenity_terms = get_terms([
                    'taxonomy' => 'property_amenity',
                    'hide_empty' => true,
                ]);
            } elseif ($post_type === 'article') {
                // Get article categories as types
                $categories = get_categories([
                    'hide_empty' => true,
                ]);
                
                if (!empty($categories)) {
                    foreach ($categories as $category) {
                        $icon = '📝'; // Default icon
                        
                        // Assign icons based on category name
                        if (stripos($category->name, 'news') !== false) {
                            $icon = '📰';
                        } elseif (stripos($category->name, 'guide') !== false || stripos($category->name, 'how-to') !== false) {
                            $icon = '📖';
                        } elseif (stripos($category->name, 'review') !== false) {
                            $icon = '💬';
                        } elseif (stripos($category->name, 'event') !== false) {
                            $icon = '🎉';
                        } elseif (stripos($category->name, 'travel') !== false) {
                            $icon = '✈️';
                        }
                        
                        $filters['types'][] = [
                            'name' => $category->name,
                            'count' => $category->count,
                            'icon' => $icon,
                        ];
                    }
                }
                
                // Get article locations from custom field or taxonomy
                // This could be from a custom field or a custom taxonomy
                // For this example, we'll get unique locations from a custom field
                $locations = [];
                $article_query = new WP_Query([
                    'post_type' => 'post',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);
                
                if ($article_query->have_posts()) {
                    foreach ($article_query->posts as $article_id) {
                        $location = carbon_get_post_meta($article_id, 'article_location');
                        if (!empty($location) && !isset($locations[$location])) {
                            $locations[$location] = 1;
                        } elseif (!empty($location)) {
                            $locations[$location]++;
                        }
                    }
                }
                wp_reset_postdata();
                
                foreach ($locations as $location => $count) {
                    $filters['locations'][] = [
                        'name' => $location,
                        'count' => $count,
                        'icon' => '📍',
                    ];
                }
                
                // Get article tags as amenities
                $tags = get_tags([
                    'hide_empty' => true,
                ]);
                $amenity_terms = $tags;
            } elseif ($post_type === 'user_profile') {
                // Get user types as types
                $type_terms = get_terms([
                    'taxonomy' => 'user_type',
                    'hide_empty' => true,
                ]);
                
                if (!empty($type_terms) && !is_wp_error($type_terms)) {
                    foreach ($type_terms as $term) {
                        $icon = '👤'; // Default icon
                        
                        // Assign icons based on user type
                        if (stripos($term->name, 'agent') !== false || stripos($term->name, 'realtor') !== false) {
                            $icon = '🏠';
                        } elseif (stripos($term->name, 'owner') !== false) {
                            $icon = '🔑';
                        } elseif (stripos($term->name, 'manager') !== false) {
                            $icon = '💼';
                        } elseif (stripos($term->name, 'staff') !== false) {
                            $icon = '👷';
                        } elseif (stripos($term->name, 'contractor') !== false) {
                            $icon = '🔨';
                        }
                        
                        $filters['types'][] = [
                            'name' => $term->name,
                            'count' => $term->count,
                            'icon' => $icon,
                        ];
                    }
                }
                
                // Get user locations from custom field
                $locations = [];
                $user_query = new WP_Query([
                    'post_type' => 'user_profile',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);
                
                if ($user_query->have_posts()) {
                    foreach ($user_query->posts as $user_id) {
                        $location = carbon_get_post_meta($user_id, 'user_location');
                        if (!empty($location) && !isset($locations[$location])) {
                            $locations[$location] = 1;
                        } elseif (!empty($location)) {
                            $locations[$location]++;
                        }
                    }
                }
                wp_reset_postdata();
                
                foreach ($locations as $location => $count) {
                    $filters['locations'][] = [
                        'name' => $location,
                        'count' => $count,
                        'icon' => '📍',
                    ];
                }
                
                // Get user specialties as amenities
                $specialties = [];
                $specialty_query = new WP_Query([
                    'post_type' => 'user_profile',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);
                
                if ($specialty_query->have_posts()) {
                    foreach ($specialty_query->posts as $user_id) {
                        $skills = carbon_get_post_meta($user_id, 'user_skills');
                        if (!empty($skills)) {
                            foreach ($skills as $skill) {
                                $skill_name = $skill['name'] ?? $skill;
                                if (!empty($skill_name) && !isset($specialties[$skill_name])) {
                                    $specialties[$skill_name] = 1;
                                } elseif (!empty($skill_name)) {
                                    $specialties[$skill_name]++;
                                }
                            }
                        }
                    }
                }
                wp_reset_postdata();
                
                $amenity_terms = [];
                foreach ($specialties as $specialty => $count) {
                    $icon = '✨'; // Default icon
                    
                    // Assign specific icons based on common skill names
                    if (stripos($specialty, 'sales') !== false) {
                        $icon = '💸';
                    } elseif (stripos($specialty, 'management') !== false) {
                        $icon = '📈';
                    } elseif (stripos($specialty, 'marketing') !== false) {
                        $icon = '📊';
                    } elseif (stripos($specialty, 'design') !== false) {
                        $icon = '🎨';
                    } elseif (stripos($specialty, 'customer') !== false) {
                        $icon = '👍';
                    }
                    
                    $amenity_terms[] = (object) [
                        'name' => $specialty,
                        'count' => $count,
                        'icon' => $icon,
                    ];
                }
            } elseif ($post_type === 'business') {
                // Get business types
                $type_terms = get_terms([
                    'taxonomy' => 'business_type',
                    'hide_empty' => true,
                ]);
                
                if (!empty($type_terms) && !is_wp_error($type_terms)) {
                    foreach ($type_terms as $term) {
                        $icon = '🏪'; // Default icon
                        
                        // Assign icons based on business type
                        if (stripos($term->name, 'restaurant') !== false || stripos($term->name, 'food') !== false) {
                            $icon = '🍽️';
                        } elseif (stripos($term->name, 'bar') !== false || stripos($term->name, 'pub') !== false) {
                            $icon = '🍸';
                        } elseif (stripos($term->name, 'shop') !== false || stripos($term->name, 'store') !== false) {
                            $icon = '🛍️';
                        } elseif (stripos($term->name, 'hotel') !== false || stripos($term->name, 'lodging') !== false) {
                            $icon = '🏨';
                        } elseif (stripos($term->name, 'activity') !== false || stripos($term->name, 'attraction') !== false) {
                            $icon = '🎯';
                        } elseif (stripos($term->name, 'service') !== false) {
                            $icon = '🔧';
                        }
                        
                        $filters['types'][] = [
                            'name' => $term->name,
                            'count' => $term->count,
                            'icon' => $icon,
                        ];
                    }
                }
                
                // For businesses, we can use cities as locations
                $cities = [];
                $business_query = new WP_Query([
                    'post_type' => 'business',
                    'posts_per_page' => -1,
                    'fields' => 'ids',
                ]);
                
                if ($business_query->have_posts()) {
                    foreach ($business_query->posts as $business_id) {
                        $city = carbon_get_post_meta($business_id, 'business_city');
                        if (!empty($city) && !in_array($city, $cities)) {
                            $cities[$city] = isset($cities[$city]) ? $cities[$city] + 1 : 1;
                        }
                    }
                }
                wp_reset_postdata();
                
                foreach ($cities as $city => $count) {
                    $filters['locations'][] = [
                        'name' => $city,
                        'count' => $count,
                        'icon' => '🏙️',
                    ];
                }
                
                // Get business features as amenities
                $feature_terms = get_terms([
                    'taxonomy' => 'business_feature',
                    'hide_empty' => true,
                ]);
                $amenity_terms = $feature_terms;
            }
            
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
            
            // If any filter categories are empty, add some defaults based on post type
            if (empty($filters['types'])) {
                if ($post_type === 'property') {
                    $filters['types'] = [
                        ['name' => 'House', 'count' => 0, 'icon' => '🏠'],
                        ['name' => 'Condo', 'count' => 0, 'icon' => '🏢'],
                        ['name' => 'Villa', 'count' => 0, 'icon' => '🏛️'],
                    ];
                } else if ($post_type === 'business') {
                    $filters['types'] = [
                        ['name' => 'Restaurant', 'count' => 0, 'icon' => '🍽️'],
                        ['name' => 'Shop', 'count' => 0, 'icon' => '🛍️'],
                        ['name' => 'Service', 'count' => 0, 'icon' => '🔧'],
                    ];
                } else if ($post_type === 'article') {
                    $filters['types'] = [
                        ['name' => 'News', 'count' => 0, 'icon' => '📰'],
                        ['name' => 'Guide', 'count' => 0, 'icon' => '📖'],
                        ['name' => 'Travel', 'count' => 0, 'icon' => '✈️'],
                    ];
                } else if ($post_type === 'user_profile') {
                    $filters['types'] = [
                        ['name' => 'Agent', 'count' => 0, 'icon' => '🏠'],
                        ['name' => 'Manager', 'count' => 0, 'icon' => '💼'],
                        ['name' => 'Staff', 'count' => 0, 'icon' => '👷'],
                    ];
                }
            }
            
            if (empty($filters['locations'])) {
                if ($post_type === 'property') {
                    $filters['locations'] = [
                        ['name' => 'North Topsail Beach', 'count' => 0, 'icon' => '🏖️'],
                        ['name' => 'Surf City', 'count' => 0, 'icon' => '🌊'],
                        ['name' => 'Topsail Beach', 'count' => 0, 'icon' => '🏝️'],
                    ];
                } else if ($post_type === 'business') {
                    $filters['locations'] = [
                        ['name' => 'Surf City', 'count' => 0, 'icon' => '🌊'],
                        ['name' => 'Topsail Beach', 'count' => 0, 'icon' => '🏝️'],
                        ['name' => 'Hampstead', 'count' => 0, 'icon' => '🏙️'],
                    ];
                } else if ($post_type === 'article') {
                    $filters['locations'] = [
                        ['name' => 'Topsail Island', 'count' => 0, 'icon' => '🏝️'],
                        ['name' => 'Coastal NC', 'count' => 0, 'icon' => '🏖️'],
                        ['name' => 'Local Area', 'count' => 0, 'icon' => '📍'],
                    ];
                } else if ($post_type === 'user_profile') {
                    $filters['locations'] = [
                        ['name' => 'Topsail Office', 'count' => 0, 'icon' => '🏢'],
                        ['name' => 'Surf City', 'count' => 0, 'icon' => '🌊'],
                        ['name' => 'Remote', 'count' => 0, 'icon' => '💻'],
                    ];
                }
            }
            
            if (empty($filters['amenities'])) {
                if ($post_type === 'property') {
                    $filters['amenities'] = [
                        ['name' => 'Pool', 'icon' => '🏊'],
                        ['name' => 'Ocean View', 'icon' => '🌅'],
                        ['name' => 'Pet Friendly', 'icon' => '🐕'],
                        ['name' => 'WiFi', 'icon' => '📶'],
                    ];
                } else if ($post_type === 'business') {
                    $filters['amenities'] = [
                        ['name' => 'Free Wifi', 'icon' => '📶'],
                        ['name' => 'Parking', 'icon' => '🅿️'],
                        ['name' => 'Delivery', 'icon' => '🚚'],
                        ['name' => 'Online Booking', 'icon' => '💻'],
                    ];
                } else if ($post_type === 'article') {
                    $filters['amenities'] = [
                        ['name' => 'Beach', 'icon' => '🏖️'],
                        ['name' => 'Family', 'icon' => '👪'],
                        ['name' => 'Food', 'icon' => '🍽️'],
                        ['name' => 'Tips', 'icon' => '💡'],
                    ];
                } else if ($post_type === 'user_profile') {
                    $filters['amenities'] = [
                        ['name' => 'Sales', 'icon' => '💸'],
                        ['name' => 'Management', 'icon' => '📈'],
                        ['name' => 'Marketing', 'icon' => '📊'],
                        ['name' => 'Customer Service', 'icon' => '👍'],
                    ];
                }
            }
            
            // Set up template variables
            $template_vars = [
                'post_type' => $post_type,
                'title' => $title,
                'items' => $items,
                'filters' => $filters,
                'count' => count($items),
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
