<?php
/**
 * Twig Demo Block
 * Demonstrates using Twig templates with Carbon Fields
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;
use MiAgency\twig;

Block::make(__('Twig Demo', 'blocksy-child'))
    ->add_fields([
        Field::make('text', 'title', __('Title', 'blocksy-child'))
            ->set_default_value('Welcome to Twig Integration'),
        
        Field::make('textarea', 'content', __('Content', 'blocksy-child'))
            ->set_default_value('This block demonstrates how to use Twig templates with WordPress blocks.'),
        
        Field::make('select', 'layout', __('Layout', 'blocksy-child'))
            ->add_options([
                'cards' => 'Card Grid',
                'buttons' => 'Button Examples',
                'mixed' => 'Mixed Components'
            ])
            ->set_default_value('mixed'),
        
        Field::make('checkbox', 'show_code', __('Show Template Code', 'blocksy-child'))
            ->set_default_value(false)
    ])
    ->set_icon('layout')
    ->set_category('mi-blocks')
    ->set_keywords([__('twig', 'blocksy-child'), __('demo', 'blocksy-child'), __('template', 'blocksy-child')])
    ->set_description(__('A demonstration of Twig template integration', 'blocksy-child'))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        // Get Twig instance
        $twig = twig();
        
        // Prepare data for template
        $data = [
            'fields' => $fields,
            'attributes' => $attributes,
            'layout' => $fields['layout'] ?? 'mixed'
        ];
        
        // Render the appropriate template
        $twig->display('blocks/twig-demo/template.twig', $data);
    });
