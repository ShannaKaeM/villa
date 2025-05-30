<?php
/**
 * Villa Community Core Plugin
 * 
 * @package VillaCommunity
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load Composer autoloader for CMB2
$autoloader_path = dirname(__FILE__) . '/../../../../vendor/autoload.php';
if (file_exists($autoloader_path)) {
    require_once $autoloader_path;
}

// Load CMB2 setup (replaces Carbon Fields)
require_once __DIR__ . '/cmb2-setup.php';

// Load property fields
require_once __DIR__ . '/cmb2-property-fields.php';

/**
 * Initialize Villa Community features
 */
function villa_community_init() {
    // Add any additional initialization here
    
    // Flush rewrite rules on activation (only once)
    if (get_option('villa_community_flush_rewrite_rules')) {
        flush_rewrite_rules();
        delete_option('villa_community_flush_rewrite_rules');
    }
}
add_action('init', 'villa_community_init');

/**
 * Activation hook
 */
function villa_community_activate() {
    // Set flag to flush rewrite rules
    add_option('villa_community_flush_rewrite_rules', true);
}
register_activation_hook(__FILE__, 'villa_community_activate');
