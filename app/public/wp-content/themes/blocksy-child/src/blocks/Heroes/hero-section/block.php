<?php
/**
 * Dynamic Carbon Block
 *
 * @package CarbonBlocks
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

if (! defined('ABSPATH')) {
    exit;
}

// Get component name from parent directory
$component = basename(dirname(__FILE__));

// Get category from grandparent directory
$category = basename(dirname(dirname(__FILE__)));

/**
 * Register Gutenberg Block dynamically
 */
Block::make(__(ucwords(str_replace('-', ' ', $component))))
    ->add_fields([
        Field::make('text', 'title', __('Title')),
        Field::make('textarea', 'subtitle', __('Subtitle')),
        Field::make('text', 'button_text', __('Button Text')),
        Field::make('text', 'button_url', __('Button URL')),
        Field::make('image', 'background_image', __('Background Image')),
        Field::make('select', 'text_alignment', __('Text Alignment'))
            ->set_options([
                'left'   => 'Left',
                'center' => 'Center',
                'right'  => 'Right',
            ])
            ->set_default_value('center'),
    ])
    ->set_category('carbon-blocks-' . $category)
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
