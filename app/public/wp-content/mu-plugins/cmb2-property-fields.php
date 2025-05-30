<?php
/**
 * CMB2 Property Fields
 * 
 * Registers all CMB2 fields for the property post type and other custom post types
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Property Fields
 */
function mi_register_property_fields() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    // Property Details
    $property_details = new_cmb2_box(array(
        'id'           => 'property_details',
        'title'        => __('Property Details', 'migv'),
        'object_types' => array('property'),
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    // Basic Information
    $property_details->add_field(array(
        'name' => __('Property Type', 'migv'),
        'id'   => 'property_type',
        'type' => 'select',
        'options' => array(
            'villa'     => __('Villa', 'migv'),
            'apartment' => __('Apartment', 'migv'),
            'condo'     => __('Condo', 'migv'),
            'townhouse' => __('Townhouse', 'migv'),
            'land'      => __('Land', 'migv'),
            'commercial' => __('Commercial', 'migv'),
        ),
    ));

    $property_details->add_field(array(
        'name' => __('Status', 'migv'),
        'id'   => 'property_status',
        'type' => 'select',
        'options' => array(
            'available'   => __('Available', 'migv'),
            'sold'        => __('Sold', 'migv'),
            'pending'     => __('Pending', 'migv'),
            'rented'      => __('Rented', 'migv'),
            'off_market'  => __('Off Market', 'migv'),
        ),
        'default' => 'available',
    ));

    $property_details->add_field(array(
        'name' => __('Price', 'migv'),
        'id'   => 'property_price',
        'type' => 'text_money',
        'before_field' => '$',
    ));

    $property_details->add_field(array(
        'name' => __('Price Per Month (for rentals)', 'migv'),
        'id'   => 'property_price_monthly',
        'type' => 'text_money',
        'before_field' => '$',
    ));

    // Property Specifications
    $property_details->add_field(array(
        'name' => __('Bedrooms', 'migv'),
        'id'   => 'property_bedrooms',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '0',
            'max'  => '20',
        ),
    ));

    $property_details->add_field(array(
        'name' => __('Bathrooms', 'migv'),
        'id'   => 'property_bathrooms',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '0',
            'max'  => '20',
            'step' => '0.5',
        ),
    ));

    $property_details->add_field(array(
        'name' => __('Square Feet', 'migv'),
        'id'   => 'property_sqft',
        'type' => 'text',
        'attributes' => array(
            'type' => 'number',
            'min'  => '0',
        ),
    ));

    $property_details->add_field(array(
        'name' => __('Lot Size (sq ft)', 'migv'),
        'id'   => 'property_lot_size',
        'type' => 'text',
        'attributes' => array(
            'type' => 'number',
            'min'  => '0',
        ),
    ));

    $property_details->add_field(array(
        'name' => __('Year Built', 'migv'),
        'id'   => 'property_year_built',
        'type' => 'text_small',
        'attributes' => array(
            'type' => 'number',
            'min'  => '1800',
            'max'  => date('Y') + 5,
        ),
    ));

    // Location Information
    $location_box = new_cmb2_box(array(
        'id'           => 'property_location',
        'title'        => __('Location Information', 'migv'),
        'object_types' => array('property'),
        'context'      => 'normal',
        'priority'     => 'high',
    ));

    $location_box->add_field(array(
        'name' => __('Address', 'migv'),
        'id'   => 'property_address',
        'type' => 'text',
    ));

    $location_box->add_field(array(
        'name' => __('City', 'migv'),
        'id'   => 'property_city',
        'type' => 'text',
    ));

    $location_box->add_field(array(
        'name' => __('State/Province', 'migv'),
        'id'   => 'property_state',
        'type' => 'text',
    ));

    $location_box->add_field(array(
        'name' => __('ZIP/Postal Code', 'migv'),
        'id'   => 'property_zip',
        'type' => 'text_small',
    ));

    $location_box->add_field(array(
        'name' => __('Country', 'migv'),
        'id'   => 'property_country',
        'type' => 'text',
        'default' => 'United States',
    ));

    $location_box->add_field(array(
        'name' => __('Latitude', 'migv'),
        'id'   => 'property_latitude',
        'type' => 'text_small',
        'desc' => __('For map display', 'migv'),
    ));

    $location_box->add_field(array(
        'name' => __('Longitude', 'migv'),
        'id'   => 'property_longitude',
        'type' => 'text_small',
        'desc' => __('For map display', 'migv'),
    ));

    // Features and Amenities
    $features_box = new_cmb2_box(array(
        'id'           => 'property_features',
        'title'        => __('Features & Amenities', 'migv'),
        'object_types' => array('property'),
        'context'      => 'normal',
        'priority'     => 'default',
    ));

    $features_box->add_field(array(
        'name' => __('Features', 'migv'),
        'id'   => 'property_features',
        'type' => 'multicheck',
        'options' => array(
            'pool'           => __('Swimming Pool', 'migv'),
            'garage'         => __('Garage', 'migv'),
            'garden'         => __('Garden', 'migv'),
            'balcony'        => __('Balcony', 'migv'),
            'terrace'        => __('Terrace', 'migv'),
            'fireplace'      => __('Fireplace', 'migv'),
            'ac'             => __('Air Conditioning', 'migv'),
            'heating'        => __('Central Heating', 'migv'),
            'security'       => __('Security System', 'migv'),
            'elevator'       => __('Elevator', 'migv'),
            'gym'            => __('Gym/Fitness Center', 'migv'),
            'spa'            => __('Spa', 'migv'),
            'ocean_view'     => __('Ocean View', 'migv'),
            'mountain_view'  => __('Mountain View', 'migv'),
            'furnished'      => __('Furnished', 'migv'),
            'pet_friendly'   => __('Pet Friendly', 'migv'),
        ),
    ));

    $features_box->add_field(array(
        'name' => __('Additional Features', 'migv'),
        'id'   => 'property_additional_features',
        'type' => 'textarea',
        'desc' => __('List any additional features not covered above', 'migv'),
    ));

    // Media Gallery
    $media_box = new_cmb2_box(array(
        'id'           => 'property_media',
        'title'        => __('Property Media', 'migv'),
        'object_types' => array('property'),
        'context'      => 'normal',
        'priority'     => 'default',
    ));

    $media_box->add_field(array(
        'name' => __('Property Gallery', 'migv'),
        'id'   => 'property_gallery',
        'type' => 'file_list',
        'desc' => __('Upload multiple images for the property gallery', 'migv'),
        'options' => array(
            'url' => false,
        ),
        'text' => array(
            'add_upload_files_text' => __('Add Images', 'migv'),
            'remove_image_text'     => __('Remove Image', 'migv'),
            'file_text'             => __('File:', 'migv'),
            'file_download_text'    => __('Download', 'migv'),
            'remove_text'           => __('Remove', 'migv'),
        ),
    ));

    $media_box->add_field(array(
        'name' => __('Virtual Tour URL', 'migv'),
        'id'   => 'property_virtual_tour',
        'type' => 'text_url',
        'desc' => __('Link to virtual tour or 360° view', 'migv'),
    ));

    $media_box->add_field(array(
        'name' => __('Video Tour URL', 'migv'),
        'id'   => 'property_video_tour',
        'type' => 'text_url',
        'desc' => __('YouTube, Vimeo, or other video URL', 'migv'),
    ));

    // Agent Information
    $agent_box = new_cmb2_box(array(
        'id'           => 'property_agent',
        'title'        => __('Agent Information', 'migv'),
        'object_types' => array('property'),
        'context'      => 'side',
        'priority'     => 'default',
    ));

    $agent_box->add_field(array(
        'name' => __('Listing Agent', 'migv'),
        'id'   => 'property_agent_name',
        'type' => 'text',
    ));

    $agent_box->add_field(array(
        'name' => __('Agent Email', 'migv'),
        'id'   => 'property_agent_email',
        'type' => 'text_email',
    ));

    $agent_box->add_field(array(
        'name' => __('Agent Phone', 'migv'),
        'id'   => 'property_agent_phone',
        'type' => 'text',
    ));

    $agent_box->add_field(array(
        'name' => __('Agent Photo', 'migv'),
        'id'   => 'property_agent_photo',
        'type' => 'file',
        'options' => array(
            'url' => false,
        ),
        'text' => array(
            'add_upload_file_text' => __('Add Agent Photo', 'migv'),
        ),
    ));
}
add_action('cmb2_admin_init', 'mi_register_property_fields');

/**
 * Register Business Fields
 */
function mi_register_business_fields() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    $business_box = new_cmb2_box(array(
        'id'           => 'business_details',
        'title'        => __('Business Details', 'migv'),
        'object_types' => array('business'),
        'context'      => 'normal',
        'priority'     => 'high',
    ));

    $business_box->add_field(array(
        'name' => __('Business Type', 'migv'),
        'id'   => 'business_type',
        'type' => 'select',
        'options' => array(
            'restaurant'    => __('Restaurant', 'migv'),
            'retail'        => __('Retail', 'migv'),
            'service'       => __('Service', 'migv'),
            'entertainment' => __('Entertainment', 'migv'),
            'health'        => __('Health & Wellness', 'migv'),
            'other'         => __('Other', 'migv'),
        ),
    ));

    $business_box->add_field(array(
        'name' => __('Phone Number', 'migv'),
        'id'   => 'business_phone',
        'type' => 'text',
    ));

    $business_box->add_field(array(
        'name' => __('Email', 'migv'),
        'id'   => 'business_email',
        'type' => 'text_email',
    ));

    $business_box->add_field(array(
        'name' => __('Website', 'migv'),
        'id'   => 'business_website',
        'type' => 'text_url',
    ));

    $business_box->add_field(array(
        'name' => __('Address', 'migv'),
        'id'   => 'business_address',
        'type' => 'textarea_small',
    ));

    $business_box->add_field(array(
        'name' => __('Hours of Operation', 'migv'),
        'id'   => 'business_hours',
        'type' => 'textarea',
    ));
}
add_action('cmb2_admin_init', 'mi_register_business_fields');

/**
 * Register Article Fields
 */
function mi_register_article_fields() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    $article_box = new_cmb2_box(array(
        'id'           => 'article_details',
        'title'        => __('Article Details', 'migv'),
        'object_types' => array('article'),
        'context'      => 'normal',
        'priority'     => 'high',
    ));

    $article_box->add_field(array(
        'name' => __('Article Type', 'migv'),
        'id'   => 'article_type',
        'type' => 'select',
        'options' => array(
            'news'      => __('News', 'migv'),
            'guide'     => __('Guide', 'migv'),
            'review'    => __('Review', 'migv'),
            'interview' => __('Interview', 'migv'),
            'feature'   => __('Feature', 'migv'),
        ),
    ));

    $article_box->add_field(array(
        'name' => __('Author Bio', 'migv'),
        'id'   => 'article_author_bio',
        'type' => 'textarea',
    ));

    $article_box->add_field(array(
        'name' => __('Featured', 'migv'),
        'id'   => 'article_featured',
        'type' => 'checkbox',
        'desc' => __('Mark as featured article', 'migv'),
    ));
}
add_action('cmb2_admin_init', 'mi_register_article_fields');

/**
 * Register User Profile fields for User Profile CPT
 */
function mi_register_user_profile_fields() {
    $user_box = new_cmb2_box(array(
        'id'           => 'user_profile_metabox',
        'title'        => __('Profile Information', 'migv'),
        'object_types' => array('user_profile'), 
        'context'      => 'normal',
        'priority'     => 'high',
        'show_names'   => true,
    ));

    // Basic Contact Information
    $user_box->add_field(array(
        'name' => __('Phone Number', 'migv'),
        'id'   => 'profile_phone',
        'type' => 'text',
    ));

    $user_box->add_field(array(
        'name' => __('Emergency Contact', 'migv'),
        'id'   => 'profile_emergency_contact',
        'type' => 'text',
    ));

    $user_box->add_field(array(
        'name' => __('Emergency Phone', 'migv'),
        'id'   => 'profile_emergency_phone',
        'type' => 'text',
    ));

    // Villa Community Specific
    $user_box->add_field(array(
        'name' => __('Villa Community Roles', 'migv'),
        'id'   => 'profile_villa_roles',
        'type' => 'multicheck',
        'options' => array(
            'community_member' => __('Community Member', 'migv'),
            'partner' => __('Business Partner', 'migv'),
            'staff' => __('Staff Member', 'migv'),
            'committee' => __('Committee Member', 'migv'),
            'dov' => __('Department of Villages', 'migv'),
            'bod' => __('Board of Directors', 'migv'),
            'owner' => __('Owner', 'migv'),
        ),
        'desc' => __('Select all roles that apply to this user', 'migv'),
    ));

    $user_box->add_field(array(
        'name' => __('Villa Address/Unit', 'migv'),
        'id'   => 'profile_villa_address',
        'type' => 'text',
        'desc' => __('Your villa address or unit number', 'migv'),
    ));

    $user_box->add_field(array(
        'name' => __('Move-in Date', 'migv'),
        'id'   => 'profile_move_in_date',
        'type' => 'text_date',
    ));

    $user_box->add_field(array(
        'name' => __('Property Interest', 'migv'),
        'id'   => 'profile_property_interest',
        'type' => 'select',
        'options' => array(
            'owner' => __('Property Owner', 'migv'),
            'buyer' => __('Looking to Buy', 'migv'),
            'seller' => __('Looking to Sell', 'migv'),
            'renter' => __('Looking to Rent', 'migv'),
            'investor' => __('Property Investment', 'migv'),
            'resident' => __('Current Resident', 'migv'),
        ),
    ));

    // Professional Information
    $user_box->add_field(array(
        'name' => __('Company', 'migv'),
        'id'   => 'profile_company',
        'type' => 'text',
    ));

    $user_box->add_field(array(
        'name' => __('Job Title', 'migv'),
        'id'   => 'profile_job_title',
        'type' => 'text',
    ));

    $user_box->add_field(array(
        'name' => __('Business Type', 'migv'),
        'id'   => 'profile_business_type',
        'type' => 'select',
        'options' => array(
            '' => __('Select business type...', 'migv'),
            'restaurant' => __('Restaurant/Food Service', 'migv'),
            'retail' => __('Retail/Shopping', 'migv'),
            'service' => __('Professional Service', 'migv'),
            'healthcare' => __('Healthcare', 'migv'),
            'education' => __('Education', 'migv'),
            'recreation' => __('Recreation/Entertainment', 'migv'),
            'maintenance' => __('Maintenance/Repair', 'migv'),
            'real_estate' => __('Real Estate', 'migv'),
            'other' => __('Other', 'migv'),
        ),
    ));

    // Community Involvement
    $user_box->add_field(array(
        'name' => __('Community Involvement Level', 'migv'),
        'id'   => 'profile_community_involvement',
        'type' => 'select',
        'options' => array(
            'active' => __('Very Active', 'migv'),
            'moderate' => __('Moderately Active', 'migv'),
            'occasional' => __('Occasional Participation', 'migv'),
            'observer' => __('Observer/Lurker', 'migv'),
            'new' => __('New to Community', 'migv'),
        ),
    ));

    $user_box->add_field(array(
        'name' => __('Committee Memberships', 'migv'),
        'id'   => 'profile_committees',
        'type' => 'multicheck',
        'options' => array(
            'technology' => __('Technology & Marketing Committee', 'migv'),
            'legal' => __('Legal & Governance Committee', 'migv'),
            'grounds' => __('Grounds & Appearance Committee', 'migv'),
            'budget' => __('Budget & Revenue Committee', 'migv'),
            'operations' => __('Operations & Maintenance Committee', 'migv'),
        ),
    ));

    $user_box->add_field(array(
        'name' => __('Interests & Hobbies', 'migv'),
        'id'   => 'profile_interests',
        'type' => 'textarea_small',
        'desc' => __('Share your interests and hobbies with the community', 'migv'),
    ));

    // Social Media
    $user_box->add_field(array(
        'name' => __('Social Media Links', 'migv'),
        'id'   => 'profile_social_media',
        'type' => 'group',
        'repeatable' => true,
        'options' => array(
            'group_title'   => __('Social Media {#}', 'migv'),
            'add_button'    => __('Add Social Media', 'migv'),
            'remove_button' => __('Remove', 'migv'),
            'sortable'      => true,
        ),
    ));

    $user_box->add_group_field('profile_social_media', array(
        'name' => __('Platform', 'migv'),
        'id'   => 'platform',
        'type' => 'select',
        'options' => array(
            'facebook'  => __('Facebook', 'migv'),
            'twitter'   => __('Twitter', 'migv'),
            'instagram' => __('Instagram', 'migv'),
            'linkedin'  => __('LinkedIn', 'migv'),
            'youtube'   => __('YouTube', 'migv'),
            'other'     => __('Other', 'migv'),
        ),
    ));

    $user_box->add_group_field('profile_social_media', array(
        'name' => __('URL', 'migv'),
        'id'   => 'url',
        'type' => 'text_url',
    ));

    // Privacy Settings
    $user_box->add_field(array(
        'name' => __('Profile Visibility', 'migv'),
        'id'   => 'profile_profile_visibility',
        'type' => 'select',
        'options' => array(
            'public' => __('Public (visible to all)', 'migv'),
            'members' => __('Members Only', 'migv'),
            'private' => __('Private (only me)', 'migv'),
        ),
        'default' => 'members',
    ));

    $user_box->add_field(array(
        'name' => __('Show Contact Information', 'migv'),
        'id'   => 'profile_show_contact',
        'type' => 'checkbox',
        'desc' => __('Allow other members to see your contact information', 'migv'),
    ));
}
add_action('cmb2_admin_init', 'mi_register_user_profile_fields');
