<?php
/**
 * The main template file
 *
 * @package miGV
 * @version 1.0.0
 */

$context = Timber\Timber::context();
$context['posts'] = Timber\Timber::get_posts();

if (is_home()) {
    $context['title'] = __('Latest Posts', 'migv');
} elseif (is_archive()) {
    $context['title'] = get_the_archive_title();
    $context['description'] = get_the_archive_description();
} elseif (is_search()) {
    $context['title'] = sprintf(__('Search Results for: %s', 'migv'), get_search_query());
}

// Add pagination
global $wp_query;
$context['pagination'] = Timber\Timber::get_pagination();

Timber\Timber::render('index.twig', $context);

get_sidebar();
get_footer();
?>
