<?php
/**
 * Template Name: Design Book Dashboard
 * Front-end Design Book Interface
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue design book assets
wp_enqueue_style('villa-design-book', get_template_directory_uri() . '/assets/css/design-book.css', [], '1.0.0');
wp_enqueue_script('villa-design-book', get_template_directory_uri() . '/assets/js/design-book.js', ['jquery'], '1.0.0', true);

// Localize script for AJAX
wp_localize_script('villa-design-book', 'villaDesignBook', [
    'ajaxurl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('migv_nonce'),
    'themeUrl' => get_template_directory_uri()
]);

// Get Timber context
$context = Timber::context();
$context['post'] = new Timber\Post();

// Add design system specific context
$context['design_system'] = [
    'current_page' => 'typography',
    'navigation' => [
        ['name' => 'Typography', 'slug' => 'typography', 'active' => true],
        ['name' => 'Colors', 'slug' => 'colors', 'active' => false],
        ['name' => 'Layout', 'slug' => 'layout', 'active' => false],
        ['name' => 'Components', 'slug' => 'components', 'active' => false]
    ]
];

// Render the design system dashboard
Timber::render('design-system/design-system-dashboard.twig', $context);
?>
