<?php
/**
 * Direct test of helper functions
 */

// Load WordPress
require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

// Include helper functions
require_once(get_stylesheet_directory() . '/inc/theme-json-helpers.php');

header('Content-Type: text/plain');

echo "=== THEME HELPER FUNCTIONS TEST ===\n\n";

echo "1. COLORS (Carbon Fields Format):\n";
$colors = get_theme_colors_for_blocks();
foreach ($colors as $value => $label) {
    echo "   - [$value] => $label\n";
}

echo "\n2. FONT SIZES (Carbon Fields Format):\n";
$font_sizes = get_theme_font_sizes_for_blocks();
foreach ($font_sizes as $value => $label) {
    echo "   - [$value] => $label\n";
}

echo "\n3. SPACING SIZES (Carbon Fields Format):\n";
$spacing = get_theme_spacing_sizes_for_blocks();
foreach ($spacing as $value => $label) {
    echo "   - [$value] => $label\n";
}

echo "\n4. GET COLOR VALUE TEST:\n";
echo "   - Primary color: " . get_theme_color_value('primary') . "\n";
echo "   - Black color: " . get_theme_color_value('black') . "\n";

echo "\n5. GET FONT SIZE VALUE TEST:\n";
echo "   - Small size: " . get_theme_font_size_value('small') . "\n";
echo "   - Large size: " . get_theme_font_size_value('large') . "\n";

echo "\n6. GET SPACING VALUE TEST:\n";
echo "   - Spacing 10: " . get_theme_spacing_value('10') . "\n";
echo "   - Spacing 20: " . get_theme_spacing_value('20') . "\n";

echo "\n=== TEST COMPLETE ===\n";
