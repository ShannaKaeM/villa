<?php
/**
 * Example Admin Page
 */

use Carbon_Fields\Field;

// Get page slug from directory name
$page_slug = basename(dirname(__FILE__));

carbon_create_admin_page([
    'title' => 'Example Settings',
    'menu_title' => 'Example',
    'slug' => $page_slug,
    'icon' => 'dashicons-admin-settings',
    'position' => 30,
    'fields' => [
        Field::make('text', 'site_title', 'Site Title'),
        Field::make('textarea', 'site_description', 'Site Description'),
        Field::make('image', 'site_logo', 'Site Logo'),
        Field::make('select', 'theme_style', 'Theme Style')
            ->set_options([
                'light' => 'Light',
                'dark' => 'Dark',
                'auto' => 'Auto'
            ])
            ->set_default_value('light'),
        Field::make('complex', 'social_links', 'Social Links')
            ->add_fields([
                Field::make('text', 'platform', 'Platform'),
                Field::make('text', 'url', 'URL'),
                Field::make('text', 'icon', 'Icon Class')
            ])
    ]
]);