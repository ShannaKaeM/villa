<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

$component = basename(dirname(__FILE__));
$category = basename(dirname(dirname(__FILE__)));

Block::make(__('Hero'))
    ->set_category('carbon-blocks-' . strtolower($category))
    ->set_icon('cover-image')
    ->set_description(__('A full-width hero section with background image, title and subtitle'))
    ->add_fields([
        Field::make('text', 'title', __('Title'))
            ->set_default_value('Welcome to Villa Community'),
        Field::make('textarea', 'subtitle', __('Subtitle'))
            ->set_default_value('Discover your perfect home in our vibrant community')
            ->set_rows(2),
        Field::make('image', 'background_image', __('Background Image'))
            ->set_value_type('url'),
        Field::make('select', 'min_height', __('Minimum Height'))
            ->set_options([
                '300px' => '300px',
                '400px' => '400px',
                '500px' => '500px',
                '600px' => '600px',
                '100vh' => 'Full Screen'
            ])
            ->set_default_value('500px'),
        Field::make('select', 'overlay_opacity', __('Overlay Opacity'))
            ->set_options([
                '0' => 'None',
                '0.3' => '30%',
                '0.5' => '50%',
                '0.7' => '70%',
                '0.9' => '90%'
            ])
            ->set_default_value('0.5'),
        Field::make('select', 'text_alignment', __('Text Alignment'))
            ->set_options([
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right'
            ])
            ->set_default_value('center'),
        Field::make('select', 'title_size', __('Title Size'))
            ->set_options([
                '3xl' => '3XL',
                '4xl' => '4XL',
                '5xl' => '5XL',
                '6xl' => '6XL'
            ])
            ->set_default_value('5xl'),
        Field::make('select', 'title_color', __('Title Color'))
            ->set_options([
                'white' => 'White',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'accent' => 'Accent'
            ])
            ->set_default_value('white'),
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
