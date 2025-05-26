<?php
/**
 * Property Post Type Configuration
 */

// Get post type slug from directory name
$post_type_slug = basename(dirname(__FILE__));

carbon_create_post_type($post_type_slug, [
    'labels' => [
        'name' => _x('Properties', 'post type general name', 'carbonblocks'),
        'singular_name' => _x('Property', 'post type singular name', 'carbonblocks'),
        'add_new' => _x('Add New', 'property', 'carbonblocks'),
        'add_new_item' => __('Add New Property', 'carbonblocks'),
        'edit_item' => __('Edit Property', 'carbonblocks'),
        'new_item' => __('New Property', 'carbonblocks'),
        'view_item' => __('View Property', 'carbonblocks'),
        'search_items' => __('Search Properties', 'carbonblocks'),
        'not_found' => __('No properties found', 'carbonblocks'),
        'not_found_in_trash' => __('No properties found in Trash', 'carbonblocks'),
        'parent_item_colon' => '',
        'menu_name' => __('Properties', 'carbonblocks')
    ],
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'properties'),
    'capability_type' => 'post',
    'has_archive' => true,
    'hierarchical' => false,
    'menu_position' => 5,
    'menu_icon' => 'dashicons-admin-home',
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    'show_in_rest' => true
]);
