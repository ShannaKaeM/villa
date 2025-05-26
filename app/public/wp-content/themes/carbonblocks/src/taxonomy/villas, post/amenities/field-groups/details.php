<?php
/**
 * Amenities Taxonomy Field Group
 */

use Carbon_Fields\Field;

// Get taxonomy slug from global variable
global $current_taxonomy_slug;

carbon_create_taxonomy_meta($current_taxonomy_slug, 'Amenity Details', [
    Field::make('image', 'amenity_icon', 'Icon')
        ->set_help_text('Upload an icon for this amenity'),
    Field::make('color', 'amenity_color', 'Color Theme')
        ->set_help_text('Choose a color to represent this amenity'),
    Field::make('text', 'amenity_short_description', 'Short Description')
        ->set_help_text('Brief description for listings'),
    Field::make('rich_text', 'amenity_full_description', 'Full Description')
        ->set_help_text('Detailed description of the amenity'),
    Field::make('checkbox', 'amenity_featured', 'Featured Amenity')
        ->set_help_text('Show this amenity prominently'),
    Field::make('text', 'amenity_price_modifier', 'Price Modifier')
        ->set_help_text('Additional cost for this amenity (if applicable)'),
    Field::make('select', 'amenity_category', 'Category')
        ->set_options([
            'comfort' => 'Comfort & Convenience',
            'entertainment' => 'Entertainment',
            'wellness' => 'Wellness & Spa',
            'outdoor' => 'Outdoor Activities',
            'technology' => 'Technology',
            'services' => 'Services',
            'dining' => 'Dining & Kitchen',
            'transport' => 'Transportation'
        ]),
    Field::make('text', 'amenity_availability', 'Availability')
        ->set_help_text('e.g., "24/7", "9 AM - 6 PM", "By appointment"')
]);