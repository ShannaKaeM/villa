<?php
/**
 * Template Name: Twig Demo Page
 * Description: A demo page showing Twig integration
 */

use MiAgency\twig;

get_header();

// Prepare data for the template
$data = [
    'page_title' => get_the_title(),
    'page_content' => get_the_content(),
    'demo_sections' => [
        [
            'title' => 'Component Examples',
            'description' => 'See how our Twig components work with the semantic color system.'
        ],
        [
            'title' => 'Color System',
            'description' => 'OKLCH-based colors with semantic naming for better maintainability.'
        ],
        [
            'title' => 'WordPress Integration',
            'description' => 'Full access to WordPress functions within Twig templates.'
        ]
    ]
];

// Render the page template
twig()->display('page-twig-demo.twig', $data);

get_footer();
