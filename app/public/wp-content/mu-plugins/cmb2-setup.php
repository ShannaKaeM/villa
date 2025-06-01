<?php
/**
 * CMB2 setup and configuration
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load CMB2 from vendor directory immediately
$cmb2_init_path = dirname(__FILE__) . '/../../../../vendor/cmb2/cmb2/init.php';
if (file_exists($cmb2_init_path)) {
    require_once $cmb2_init_path;
} else {
    // Add admin notice about missing CMB2
    add_action('admin_notices', function() {
        echo '<div class="error"><p>CMB2 is not available. Please make sure it is installed via Composer or as a plugin.</p></div>';
    });
}

/**
 * Initialize CMB2 (kept for compatibility)
 */
function mi_init_cmb2() {
    return true;
}

/**
 * Register theme options page
 */
function mi_register_theme_options() {
    if (!function_exists('new_cmb2_box')) {
        return;
    }

    $cmb = new_cmb2_box(array(
        'id'           => 'mi_theme_options',
        'title'        => __('Theme Options', 'migv'),
        'object_types' => array('options-page'),
        'option_key'   => 'mi_theme_options',
        'parent_slug'  => 'themes.php',
        'capability'   => 'manage_options',
    ));

    $cmb->add_field(array(
        'name'    => __('Copyright Text', 'migv'),
        'desc'    => __('Enter the copyright text for the footer', 'migv'),
        'id'      => 'mi_copyright_text',
        'type'    => 'textarea_small',
        'default' => ' ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
    ));

    $cmb->add_field(array(
        'name'    => __('Contact Email', 'migv'),
        'desc'    => __('Enter the main contact email', 'migv'),
        'id'      => 'mi_contact_email',
        'type'    => 'text_email',
    ));

    $cmb->add_field(array(
        'name'    => __('Phone Number', 'migv'),
        'desc'    => __('Enter the main phone number', 'migv'),
        'id'      => 'mi_phone_number',
        'type'    => 'text',
    ));

    $cmb->add_field(array(
        'name'    => __('Address', 'migv'),
        'desc'    => __('Enter the business address', 'migv'),
        'id'      => 'mi_address',
        'type'    => 'textarea_small',
    ));
}
add_action('cmb2_admin_init', 'mi_register_theme_options');

/**
 * Helper function to get CMB2 theme option
 */
function mi_get_theme_option($option_name, $default = '') {
    if (!function_exists('cmb2_get_option')) {
        return $default;
    }
    return cmb2_get_option('mi_theme_options', $option_name, $default);
}

/**
 * Helper function to get CMB2 meta value
 */
function mi_get_meta($post_id, $field_id, $single = true) {
    if (!function_exists('get_post_meta')) {
        return '';
    }
    return get_post_meta($post_id, $field_id, $single);
}
