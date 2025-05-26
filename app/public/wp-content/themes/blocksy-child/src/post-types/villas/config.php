<?php
/**
 * Villas Post Type Configuration
 */

// Get post type slug from directory name
$post_type_slug = basename(dirname(__FILE__));

carbon_create_post_type($post_type_slug, [
    'labels' => [
        'name' => 'Villas',
        'singular_name' => 'Villa',
        'add_new' => 'Add New Villa',
        'add_new_item' => 'Add New Villa',
        'edit_item' => 'Edit Villa',
        'new_item' => 'New Villa',
        'view_item' => 'View Villa',
        'search_items' => 'Search Villas',
        'not_found' => 'No villas found',
        'not_found_in_trash' => 'No villas found in trash',
        'all_items' => 'All Villas',
        'menu_name' => 'Villas',
        'name_admin_bar' => 'Villa'
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-admin-home',
    'menu_position' => 25,
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
    'rewrite' => ['slug' => 'villas'],
    'show_in_rest' => true
]);