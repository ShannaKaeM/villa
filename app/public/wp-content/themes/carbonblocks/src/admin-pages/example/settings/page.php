<?php
/**
 * Example Settings Sub-page
 */

use Carbon_Fields\Field;

// Get parent page slug
$parent_slug = basename(dirname(dirname(__FILE__)));

carbon_create_admin_page([
    'title' => 'Advanced Settings',
    'menu_title' => 'Advanced',
    'parent' => $parent_slug,
    'slug' => $parent_slug . '_advanced',
    'fields' => [
        Field::make('checkbox', 'enable_cache', 'Enable Caching'),
        Field::make('text', 'cache_duration', 'Cache Duration (hours)')
            ->set_default_value('24'),
        Field::make('checkbox', 'enable_debug', 'Enable Debug Mode'),
        Field::make('textarea', 'custom_css', 'Custom CSS'),
        Field::make('textarea', 'custom_js', 'Custom JavaScript'),
        Field::make('separator', 'sep1', 'Performance Settings'),
        Field::make('checkbox', 'optimize_images', 'Optimize Images'),
        Field::make('checkbox', 'minify_css', 'Minify CSS'),
        Field::make('checkbox', 'minify_js', 'Minify JavaScript')
    ]
]);