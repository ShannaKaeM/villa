<?php
/**
 * Test file to check theme.json and global settings access
 * 
 * Load this file directly to see what's available
 */

// Load WordPress
require_once('../../../../wp-load.php');

echo "<h1>Theme JSON and Global Settings Test</h1>";

// Check if theme.json exists
$theme_json_path = get_stylesheet_directory() . '/theme.json';
echo "<h2>1. Checking for theme.json file:</h2>";
echo "Path: " . $theme_json_path . "<br>";
echo "Exists: " . (file_exists($theme_json_path) ? 'YES' : 'NO') . "<br><br>";

// Check parent theme
$parent_json_path = get_template_directory() . '/theme.json';
echo "<h2>2. Checking parent theme for theme.json:</h2>";
echo "Path: " . $parent_json_path . "<br>";
echo "Exists: " . (file_exists($parent_json_path) ? 'YES' : 'NO') . "<br><br>";

// Get WordPress global settings
echo "<h2>3. WordPress Global Settings (wp_get_global_settings()):</h2>";
$global_settings = wp_get_global_settings();
echo "<pre>";
print_r($global_settings);
echo "</pre>";

// Get WordPress global styles
echo "<h2>4. WordPress Global Styles (wp_get_global_styles()):</h2>";
$global_styles = wp_get_global_styles();
echo "<pre>";
print_r($global_styles);
echo "</pre>";

// Check for Blocksy customizer values
echo "<h2>5. Blocksy Theme Mods:</h2>";
$theme_mods = get_theme_mods();
echo "<pre>";
print_r($theme_mods);
echo "</pre>";

// Check Blocksy specific options
echo "<h2>6. Blocksy Options (if available):</h2>";
$blocksy_options = get_option('blocksy_options');
if ($blocksy_options) {
    echo "<pre>";
    print_r($blocksy_options);
    echo "</pre>";
} else {
    echo "No Blocksy options found<br>";
}

// Function to get theme styles (as Daniel suggested)
function get_theme_styles($key = null) {
    $settings = wp_get_global_settings();
    
    if ($key) {
        // Navigate nested arrays with dot notation
        $keys = explode('.', $key);
        $value = $settings;
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return null;
            }
        }
        return $value;
    }
    
    return $settings;
}

echo "<h2>7. Testing get_theme_styles() function:</h2>";
echo "Colors: <pre>";
print_r(get_theme_styles('color.palette'));
echo "</pre>";

echo "Spacing: <pre>";
print_r(get_theme_styles('spacing.spacingSizes'));
echo "</pre>";

echo "Typography: <pre>";
print_r(get_theme_styles('typography.fontSizes'));
echo "</pre>";
