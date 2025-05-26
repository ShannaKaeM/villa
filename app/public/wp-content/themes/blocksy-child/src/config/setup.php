<?php
/**
 * Basic theme setup and dependency initialization
 *
 * @package ExampleTheme
 */

// Prevent direct access
if (! defined('ABSPATH')) {
    exit;
}

/* Require Vendor Autoload and Config Files */
require_once get_stylesheet_directory() . '/vendor/autoload.php';
require_once get_stylesheet_directory() . '/src/config/compile_helpers.php';
require_once get_stylesheet_directory() . '/src/config/timber/paths.php';
require_once get_stylesheet_directory() . '/src/config/admin_pages.php';
require_once get_stylesheet_directory() . '/src/config/post_types.php';
require_once get_stylesheet_directory() . '/src/config/taxonomy.php';

/**
 * Load Carbon Fields via Composer autoloader
 */
function crb_load()
{
    $autoload_path = get_stylesheet_directory() . '/vendor/autoload.php';

    if (file_exists($autoload_path)) {
        require_once $autoload_path;
        \Carbon_Fields\Carbon_Fields::boot();
    }
}

/**
 * Initialize Timber
 */
function example_theme_init_timber()
{
    // Initialize Timber.
    Timber\Timber::init();

}

/**
 * Auto-discover and register Carbon Blocks with categories
 */
function carbon_blocks_register_all()
{
    $blocks_dir = get_stylesheet_directory() . '/src/blocks/';

    if (! is_dir($blocks_dir)) {
        return;
    }

    // Get category directories
    $category_dirs = glob($blocks_dir . '*', GLOB_ONLYDIR);

    foreach ($category_dirs as $category_dir) {
        $category_name = basename($category_dir);

        // Get block directories within each category
        $block_dirs = glob($category_dir . '/*', GLOB_ONLYDIR);

        foreach ($block_dirs as $block_dir) {
            $block_name = basename($block_dir);
            $block_file = $block_dir . '/block.php';

            if (file_exists($block_file)) {
                require_once $block_file;
            }
        }
    }
}

/**
 * Get all available block categories
 */
function carbon_blocks_get_categories()
{
    $blocks_dir = get_stylesheet_directory() . '/src/blocks/';
    $categories = [];

    if (! is_dir($blocks_dir)) {
        return $categories;
    }

    $category_dirs = glob($blocks_dir . '*', GLOB_ONLYDIR);

    foreach ($category_dirs as $category_dir) {
        $category_name              = basename($category_dir);
        $categories[$category_name] = [
            'slug'  => $category_name,
            'title' => ucwords(str_replace('-', ' ', $category_name)),
            'icon'  => 'admin-generic', // Default icon
        ];
    }

    return $categories;
}

/**
 * Register block categories with Gutenberg
 */
function carbon_blocks_register_categories()
{
    $categories = carbon_blocks_get_categories();

    if (empty($categories)) {
        return;
    }

    add_filter('block_categories_all', function ($block_categories) use ($categories) {
        foreach ($categories as $category) {
            $block_categories[] = [
                'slug'  => 'carbon-blocks-' . $category['slug'],
                'title' => $category['title'],
                'icon'  => $category['icon'],
            ];
        }
        return $block_categories;
    });
}

/**
 * Load compile helpers for Carbon Blocks
 */
function carbon_blocks_load_helpers()
{
}

// Load Carbon Fields via composer autoloader
add_action('after_setup_theme', 'crb_load');

// Initialize Timber and helpers
add_action('after_setup_theme', function () {
    example_theme_init_timber();
    carbon_blocks_load_helpers();
    carbon_blocks_register_categories();
}, 1);

// Register post types and taxonomies early
add_action('init', function() {
    carbon_post_types_register_all();
    carbon_taxonomies_register_all();
});

// Register Carbon Blocks, Admin Pages, Post Type Field Groups, and Taxonomy Field Groups after Carbon Fields is ready
add_action('carbon_fields_register_fields', function () {
    carbon_blocks_register_all();
    carbon_admin_pages_register_all();
    carbon_post_types_register_field_groups_all();
    carbon_taxonomies_register_field_groups_all();
});
