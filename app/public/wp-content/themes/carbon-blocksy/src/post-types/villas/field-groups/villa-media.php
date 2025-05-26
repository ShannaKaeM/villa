<?php
/**
 * Villa Media Field Group
 */

use Carbon_Fields\Field;

// Get post type slug from parent directory
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta($post_type_slug, 'Villa Media', [
    Field::make('media_gallery', 'villa_gallery', 'Photo Gallery')
        ->set_type(['image'])
        ->set_help_text('Upload villa photos - first image will be the featured image'),
    Field::make('complex', 'villa_videos', 'Videos')
        ->add_fields([
            Field::make('text', 'video_title', 'Video Title'),
            Field::make('text', 'video_url', 'Video URL (YouTube, Vimeo, etc.)'),
            Field::make('textarea', 'video_description', 'Description')
        ])
        ->set_help_text('Add virtual tours or promotional videos'),
    Field::make('file', 'villa_brochure', 'Villa Brochure')
        ->set_type(['application/pdf'])
        ->set_help_text('Upload PDF brochure or fact sheet'),
    Field::make('file', 'villa_floor_plan', 'Floor Plan')
        ->set_type(['image', 'application/pdf'])
        ->set_help_text('Upload floor plan image or PDF')
], [
    'context' => 'side',
    'priority' => 'default'
]);