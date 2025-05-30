<?php
/**
 * Temporary script to flush rewrite rules and clear CPT cache
 * Delete this file after running once
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Flush rewrite rules on admin init (run once)
 */
function villa_flush_rewrites_once() {
    if (is_admin() && current_user_can('manage_options')) {
        if (get_option('villa_flush_rewrites_needed')) {
            flush_rewrite_rules();
            delete_option('villa_flush_rewrites_needed');
            
            // Add admin notice
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>Villa Community:</strong> Rewrite rules flushed successfully! You can now delete the flush-rewrites.php file.</p>';
                echo '</div>';
            });
        }
    }
}
add_action('admin_init', 'villa_flush_rewrites_once');

// Set the flag to flush on next admin page load
add_option('villa_flush_rewrites_needed', true);
