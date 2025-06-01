<?php
/**
 * Template Name: Design Book Dashboard
 * Front-end Design Book Interface
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue Design Book assets
wp_enqueue_style('design-book-css', get_template_directory_uri() . '/assets/css/design-book.css', array(), '1.0.3');
wp_enqueue_script('design-book-js', get_template_directory_uri() . '/assets/js/design-book.js', array('jquery'), '1.0.3', true);

// Localize script for AJAX
wp_localize_script('design-book-js', 'migv_ajax', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('migv_nonce'),
    'themeUrl' => get_template_directory_uri()
]);

// Get Timber context
$context = Timber::context();
$context['post'] = Timber::get_post();

// Simulate user for Design Book (since it's a design tool, not user-specific)
$context['user'] = (object) [
    'display_name' => 'Design Book User',
    'user_email' => 'designer@villa.com'
];

// Add design book specific context
$context['design_book'] = [
    'current_section' => 'dashboard',
    'navigation' => [
        ['name' => 'Typography', 'slug' => 'typography', 'url' => '/design-book/typography/', 'active' => false],
        ['name' => 'Colors', 'slug' => 'colors', 'url' => '/design-book/colors/', 'active' => false],
        ['name' => 'Layout', 'slug' => 'layout', 'url' => '/design-book/layout/', 'active' => false],
        ['name' => 'Components', 'slug' => 'components', 'url' => '/design-book/components/', 'active' => false]
    ],
    'breadcrumbs' => [
        ['name' => 'Design Book', 'url' => '/design-book/', 'active' => true]
    ]
];

// Set current tab for dashboard layout
$context['current_tab'] = 'design-book';

// Render the design book dashboard
Timber::render('design-book/dashboard.twig', $context);
?>
