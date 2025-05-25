<?php
/**
 * Carbon Fields Property Fields
 * 
 * Registers all Carbon Fields for the property post type and other custom post types
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

// Import Carbon Fields classes
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Register property fields
 */
function mi_register_property_fields() {
    Container::make('post_meta', __('Property Details', 'blocksy-child'))
        ->where('post_type', '=', 'property')
        ->set_context('normal')
        ->set_priority('high')
        ->add_tab(__('Location', 'blocksy-child'), [
            Field::make('text', 'property_address', __('Address', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('Full street address of the property', 'blocksy-child')),
            
            Field::make('text', 'property_city', __('City', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('City where the property is located', 'blocksy-child')),
            
            Field::make('text', 'property_state', __('State/Province', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('State or province where the property is located', 'blocksy-child')),
            
            Field::make('text', 'property_zip_code', __('ZIP/Postal Code', 'blocksy-child'))
                ->set_help_text(__('ZIP or postal code of the property', 'blocksy-child')),
            
            Field::make('text', 'property_latitude', __('Latitude', 'blocksy-child'))
                ->set_help_text(__('Latitude coordinates for map display', 'blocksy-child')),
            
            Field::make('text', 'property_longitude', __('Longitude', 'blocksy-child'))
                ->set_help_text(__('Longitude coordinates for map display', 'blocksy-child')),
        ])
        ->add_tab(__('Property Details', 'blocksy-child'), [
            Field::make('text', 'property_bedrooms', __('Bedrooms', 'blocksy-child'))
                ->set_required(true)
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('step', '1')
                ->set_help_text(__('Number of bedrooms in the property', 'blocksy-child')),
            
            Field::make('text', 'property_bathrooms', __('Bathrooms', 'blocksy-child'))
                ->set_required(true)
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('step', '0.5')
                ->set_help_text(__('Number of bathrooms in the property', 'blocksy-child')),
            
            Field::make('text', 'property_max_guests', __('Maximum Guests', 'blocksy-child'))
                ->set_required(true)
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('step', '1')
                ->set_help_text(__('Maximum number of guests allowed', 'blocksy-child')),
        ])
        ->add_tab(__('Pricing & Booking', 'blocksy-child'), [
            Field::make('text', 'property_nightly_rate', __('Nightly Rate ($)', 'blocksy-child'))
                ->set_required(true)
                ->set_attribute('type', 'number')
                ->set_attribute('min', '0')
                ->set_attribute('step', '0.01')
                ->set_help_text(__('Base nightly rate for the property', 'blocksy-child')),
            
            Field::make('text', 'property_booking_url', __('Booking URL', 'blocksy-child'))
                ->set_attribute('type', 'url')
                ->set_help_text(__('External URL for booking this property', 'blocksy-child')),
            
            Field::make('text', 'property_ical_url', __('iCal URL', 'blocksy-child'))
                ->set_attribute('type', 'url')
                ->set_help_text(__('iCal URL for syncing availability', 'blocksy-child')),
            
            Field::make('checkbox', 'property_has_direct_booking', __('Has Direct Booking', 'blocksy-child'))
                ->set_help_text(__('Check if this property can be booked directly on the site', 'blocksy-child')),
        ])
        ->add_tab(__('Gallery', 'blocksy-child'), [
            Field::make('image', 'property_featured_image', __('Featured Image', 'blocksy-child'))
                ->set_help_text(__('Main image for this property', 'blocksy-child')),
            
            Field::make('media_gallery', 'property_gallery', __('Property Gallery', 'blocksy-child'))
                ->set_type(['image'])
                ->set_help_text(__('Additional images of the property', 'blocksy-child')),
        ])
        ->add_tab(__('Features', 'blocksy-child'), [
            Field::make('checkbox', 'property_is_featured', __('Featured Property', 'blocksy-child'))
                ->set_help_text(__('Check to mark this property as featured', 'blocksy-child')),
            
            Field::make('complex', 'property_amenities_details', __('Amenity Details', 'blocksy-child'))
                ->add_fields([
                    Field::make('text', 'name', __('Amenity Name', 'blocksy-child'))
                        ->set_required(true),
                    Field::make('textarea', 'description', __('Description', 'blocksy-child')),
                    Field::make('image', 'icon', __('Icon', 'blocksy-child')),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_help_text(__('Add detailed information about specific amenities', 'blocksy-child')),
        ]);
}
add_action('carbon_fields_register_fields', 'mi_register_property_fields');

/**
 * Register business fields
 */
function mi_register_business_fields() {
    Container::make('post_meta', __('Business Details', 'blocksy-child'))
        ->where('post_type', '=', 'business')
        ->set_context('normal')
        ->set_priority('high')
        ->add_tab(__('Contact Information', 'blocksy-child'), [
            Field::make('text', 'business_address', __('Address', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('Street address of the business', 'blocksy-child')),
            
            Field::make('text', 'business_city', __('City', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('City where the business is located', 'blocksy-child')),
            
            Field::make('text', 'business_state', __('State/Province', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('State or province where the business is located', 'blocksy-child')),
            
            Field::make('text', 'business_zip_code', __('ZIP/Postal Code', 'blocksy-child'))
                ->set_help_text(__('ZIP or postal code of the business', 'blocksy-child')),
            
            Field::make('text', 'business_phone', __('Phone', 'blocksy-child'))
                ->set_help_text(__('Business phone number', 'blocksy-child')),
            
            Field::make('text', 'business_email', __('Email', 'blocksy-child'))
                ->set_attribute('type', 'email')
                ->set_help_text(__('Business email address', 'blocksy-child')),
        ])
        ->add_tab(__('Online Presence', 'blocksy-child'), [
            Field::make('text', 'business_website', __('Website', 'blocksy-child'))
                ->set_attribute('type', 'url')
                ->set_help_text(__('Business website URL', 'blocksy-child')),
            
            Field::make('complex', 'business_social_media', __('Social Media', 'blocksy-child'))
                ->add_fields([
                    Field::make('select', 'platform', __('Platform', 'blocksy-child'))
                        ->set_options([
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'pinterest' => 'Pinterest',
                            'other' => 'Other'
                        ]),
                    Field::make('text', 'url', __('URL', 'blocksy-child'))
                        ->set_attribute('type', 'url')
                        ->set_required(true),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_help_text(__('Add social media links for the business', 'blocksy-child')),
        ])
        ->add_tab(__('Business Hours', 'blocksy-child'), [
            Field::make('complex', 'business_hours', __('Hours of Operation', 'blocksy-child'))
                ->add_fields([
                    Field::make('select', 'day', __('Day', 'blocksy-child'))
                        ->set_options([
                            'monday' => 'Monday',
                            'tuesday' => 'Tuesday',
                            'wednesday' => 'Wednesday',
                            'thursday' => 'Thursday',
                            'friday' => 'Friday',
                            'saturday' => 'Saturday',
                            'sunday' => 'Sunday'
                        ])
                        ->set_required(true),
                    Field::make('text', 'open', __('Opening Time', 'blocksy-child'))
                        ->set_attribute('type', 'time')
                        ->set_required(true),
                    Field::make('text', 'close', __('Closing Time', 'blocksy-child'))
                        ->set_attribute('type', 'time')
                        ->set_required(true),
                    Field::make('checkbox', 'closed', __('Closed', 'blocksy-child')),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_help_text(__('Set business hours for each day of the week', 'blocksy-child')),
        ])
        ->add_tab(__('Location', 'blocksy-child'), [
            Field::make('text', 'business_latitude', __('Latitude', 'blocksy-child'))
                ->set_help_text(__('Latitude coordinates for map display', 'blocksy-child')),
            
            Field::make('text', 'business_longitude', __('Longitude', 'blocksy-child'))
                ->set_help_text(__('Longitude coordinates for map display', 'blocksy-child')),
        ])
        ->add_tab(__('Additional Details', 'blocksy-child'), [
            Field::make('checkbox', 'business_is_featured', __('Featured Business', 'blocksy-child'))
                ->set_help_text(__('Check to mark this business as featured', 'blocksy-child')),
            
            Field::make('complex', 'business_special_offers', __('Special Offers', 'blocksy-child'))
                ->add_fields([
                    Field::make('text', 'title', __('Offer Title', 'blocksy-child'))
                        ->set_required(true),
                    Field::make('textarea', 'description', __('Offer Description', 'blocksy-child')),
                    Field::make('date', 'valid_until', __('Valid Until', 'blocksy-child')),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_help_text(__('Add special offers or promotions for this business', 'blocksy-child')),
        ]);
}
add_action('carbon_fields_register_fields', 'mi_register_business_fields');

/**
 * Register article fields
 */
function mi_register_article_fields() {
    Container::make('post_meta', __('Article Details', 'blocksy-child'))
        ->where('post_type', '=', 'article')
        ->set_context('normal')
        ->set_priority('high')
        ->add_fields([
            Field::make('checkbox', 'article_is_featured', __('Featured Article', 'blocksy-child'))
                ->set_help_text(__('Check to mark this article as featured', 'blocksy-child')),
            
            Field::make('date', 'article_publish_date', __('Publish Date', 'blocksy-child'))
                ->set_help_text(__('Date when this article was published', 'blocksy-child')),
            
            Field::make('text', 'article_read_time', __('Read Time (minutes)', 'blocksy-child'))
                ->set_attribute('type', 'number')
                ->set_attribute('min', '1')
                ->set_attribute('step', '1')
                ->set_help_text(__('Estimated reading time in minutes', 'blocksy-child')),
            
            Field::make('text', 'article_author_name', __('Author Name', 'blocksy-child'))
                ->set_help_text(__('Name of the article author (if different from post author)', 'blocksy-child')),
            
            Field::make('image', 'article_author_image', __('Author Image', 'blocksy-child'))
                ->set_help_text(__('Profile image of the article author', 'blocksy-child')),
        ]);
}
add_action('carbon_fields_register_fields', 'mi_register_article_fields');

/**
 * Register user profile fields
 */
function mi_register_user_profile_fields() {
    Container::make('post_meta', __('User Profile Details', 'blocksy-child'))
        ->where('post_type', '=', 'user_profile')
        ->set_context('normal')
        ->set_priority('high')
        ->add_tab(__('Personal Information', 'blocksy-child'), [
            Field::make('text', 'user_profile_first_name', __('First Name', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('First name of the user', 'blocksy-child')),
            
            Field::make('text', 'user_profile_last_name', __('Last Name', 'blocksy-child'))
                ->set_required(true)
                ->set_help_text(__('Last name of the user', 'blocksy-child')),
            
            Field::make('text', 'user_profile_email', __('Email', 'blocksy-child'))
                ->set_attribute('type', 'email')
                ->set_help_text(__('Email address of the user', 'blocksy-child')),
            
            Field::make('text', 'user_profile_phone', __('Phone', 'blocksy-child'))
                ->set_help_text(__('Phone number of the user', 'blocksy-child')),
        ])
        ->add_tab(__('Social Media', 'blocksy-child'), [
            Field::make('complex', 'user_profile_social_media', __('Social Media', 'blocksy-child'))
                ->add_fields([
                    Field::make('select', 'platform', __('Platform', 'blocksy-child'))
                        ->set_options([
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'pinterest' => 'Pinterest',
                            'other' => 'Other'
                        ]),
                    Field::make('text', 'url', __('URL', 'blocksy-child'))
                        ->set_attribute('type', 'url')
                        ->set_required(true),
                ])
                ->set_layout('tabbed-horizontal')
                ->set_help_text(__('Add social media links for the user', 'blocksy-child')),
        ])
        ->add_tab(__('Additional Details', 'blocksy-child'), [
            Field::make('textarea', 'user_profile_bio', __('Biography', 'blocksy-child'))
                ->set_help_text(__('Short biography of the user', 'blocksy-child')),
            
            Field::make('checkbox', 'user_profile_is_featured', __('Featured Profile', 'blocksy-child'))
                ->set_help_text(__('Check to mark this profile as featured', 'blocksy-child')),
        ]);
}
add_action('carbon_fields_register_fields', 'mi_register_user_profile_fields');
