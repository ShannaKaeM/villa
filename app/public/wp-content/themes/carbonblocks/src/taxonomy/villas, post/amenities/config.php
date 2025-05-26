<?php
/**
 * Amenities Taxonomy Configuration
 * Applies to: villas, posts
 */

// Get taxonomy slug from directory name
global $current_taxonomy_slug, $current_post_types;

carbon_create_taxonomy($current_taxonomy_slug, $current_post_types, [
    'labels' => [
        'name' => 'Amenities',
        'singular_name' => 'Amenity',
        'search_items' => 'Search Amenities',
        'all_items' => 'All Amenities',
        'edit_item' => 'Edit Amenity',
        'update_item' => 'Update Amenity',
        'add_new_item' => 'Add New Amenity',
        'new_item_name' => 'New Amenity Name',
        'menu_name' => 'Amenities'
    ],
    'public' => true,
    'hierarchical' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => ['slug' => 'amenities'],
    'show_in_rest' => true,
    'show_in_nav_menus' => true,
    'show_tagcloud' => true
]);