<?php
use Carbon_Fields\Block;
use Carbon_Fields\Field;

$component = basename(dirname(__FILE__));
$category = basename(dirname(dirname(__FILE__)));

Block::make(__('Listing Section'))
    ->set_category('carbon-blocks-' . strtolower($category))
    ->set_icon('grid-view')
    ->set_description(__('Display listings in a customizable card layout with filtering options'))
    ->add_fields([
        Field::make('select', 'post_type', __('Post Type'))
            ->set_options([
                'property' => 'Properties',
                'business' => 'Businesses',
                'article' => 'Articles',
                'user_profile' => 'User Profiles'
            ])
            ->set_default_value('property'),
        Field::make('text', 'posts_per_page', __('Posts Per Page'))
            ->set_attribute('type', 'number')
            ->set_default_value('12'),
        Field::make('select', 'columns', __('Columns'))
            ->set_options([
                '1' => '1 Column',
                '2' => '2 Columns',
                '3' => '3 Columns',
                '4' => '4 Columns'
            ])
            ->set_default_value('3'),
        Field::make('checkbox', 'show_filter', __('Show Filter'))
            ->set_default_value(true),
        Field::make('select', 'filter_position', __('Filter Position'))
            ->set_options([
                'left' => 'Left Sidebar',
                'top' => 'Top Bar',
                'right' => 'Right Sidebar'
            ])
            ->set_default_value('left')
            ->set_conditional_logic([[
                'field' => 'show_filter',
                'value' => true,
                'compare' => '='
            ]]),
        Field::make('select', 'card_style', __('Card Style'))
            ->set_options([
                'default' => 'Default',
                'minimal' => 'Minimal',
                'detailed' => 'Detailed'
            ])
            ->set_default_value('default'),
        Field::make('checkbox', 'show_pagination', __('Show Pagination'))
            ->set_default_value(true),
        Field::make('association', 'selected_categories', __('Filter by Categories'))
            ->set_types([
                [
                    'type' => 'term',
                    'taxonomy' => 'category',
                ]
            ]),
        Field::make('association', 'selected_tags', __('Filter by Tags'))
            ->set_types([
                [
                    'type' => 'term',
                    'taxonomy' => 'post_tag',
                ]
            ])
    ])
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) use ($component, $category) {
        carbon_blocks_render_gutenberg($category . '/' . $component, $fields, $attributes, $inner_blocks);
    });
