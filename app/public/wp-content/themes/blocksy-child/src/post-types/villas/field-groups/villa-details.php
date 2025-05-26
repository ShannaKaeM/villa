<?php
/**
 * Villa Details Field Group with Tabs
 */

use Carbon_Fields\Field;

// Get post type slug from parent directory
$post_type_slug = basename(dirname(dirname(__FILE__)));

carbon_create_post_meta_with_tabs($post_type_slug, 'Villa Details', [
    'basic_info' => [
        'title' => 'Basic Information',
        'fields' => [
            Field::make('text', 'villa_name', 'Villa Name'),
            Field::make('text', 'villa_price', 'Price per Night')
                ->set_help_text('Enter price in USD'),
            Field::make('select', 'villa_type', 'Villa Type')
                ->set_options([
                    'luxury' => 'Luxury Villa',
                    'beachfront' => 'Beachfront Villa',
                    'mountain' => 'Mountain Villa',
                    'city' => 'City Villa',
                    'countryside' => 'Countryside Villa'
                ]),
            Field::make('text', 'villa_size', 'Size (sq ft)'),
            Field::make('text', 'villa_bedrooms', 'Bedrooms')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'villa_bathrooms', 'Bathrooms')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1),
            Field::make('text', 'villa_guests', 'Max Guests')
                ->set_attribute('type', 'number')
                ->set_attribute('min', 1)
        ]
    ],
    'location' => [
        'title' => 'Location & Address',
        'fields' => [
            Field::make('text', 'villa_address', 'Street Address'),
            Field::make('text', 'villa_city', 'City'),
            Field::make('text', 'villa_state', 'State/Province'),
            Field::make('text', 'villa_country', 'Country'),
            Field::make('text', 'villa_zip', 'Postal Code'),
            Field::make('text', 'villa_latitude', 'Latitude'),
            Field::make('text', 'villa_longitude', 'Longitude'),
            Field::make('textarea', 'villa_directions', 'Directions')
        ]
    ],
    'amenities' => [
        'title' => 'Amenities & Features',
        'fields' => [
            Field::make('checkbox', 'villa_pool', 'Private Pool'),
            Field::make('checkbox', 'villa_spa', 'Spa/Hot Tub'),
            Field::make('checkbox', 'villa_gym', 'Fitness Center'),
            Field::make('checkbox', 'villa_wifi', 'Free WiFi'),
            Field::make('checkbox', 'villa_parking', 'Parking Available'),
            Field::make('checkbox', 'villa_ac', 'Air Conditioning'),
            Field::make('checkbox', 'villa_kitchen', 'Full Kitchen'),
            Field::make('checkbox', 'villa_garden', 'Private Garden'),
            Field::make('checkbox', 'villa_beach_access', 'Beach Access'),
            Field::make('set', 'villa_additional_amenities', 'Additional Amenities')
                ->set_options([
                    'concierge' => 'Concierge Service',
                    'chef' => 'Private Chef Available',
                    'cleaning' => 'Daily Housekeeping',
                    'laundry' => 'Laundry Service',
                    'security' => '24/7 Security',
                    'transport' => 'Airport Transfer',
                    'pets' => 'Pet Friendly',
                    'smoking' => 'Smoking Allowed'
                ])
        ]
    ]
]);