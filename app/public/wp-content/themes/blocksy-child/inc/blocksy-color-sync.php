<?php
/**
 * Sync theme.json colors to Blocksy Customizer defaults
 * 
 * This file helps transition from development (theme.json) to production (Blocksy Customizer)
 */

/**
 * Set Blocksy's default palette colors to match our theme.json
 * Run this once when setting up the theme for a client
 */
function sync_theme_colors_to_blocksy() {
    // Map our theme.json colors to Blocksy's palette slots
    $color_mapping = [
        'paletteColor1' => '#5a7f80', // Primary 500
        'paletteColor2' => '#a36b57', // Secondary 500
        'paletteColor3' => '#9b8974', // Neutral 500
        'paletteColor4' => '#9c9c9c', // Base 500
        'paletteColor5' => '#8dabac', // Primary 300 (light)
        'paletteColor6' => '#425a5b', // Primary 700 (dark)
        'paletteColor7' => '#d1a896', // Secondary 300 (light)
        'paletteColor8' => '#744d3e', // Secondary 700 (dark)
    ];
    
    // Get current Blocksy options
    $options = get_option('blocksy_ext_customizer_options', []);
    
    // Update color palette
    foreach ($color_mapping as $palette_key => $color_value) {
        $options[$palette_key] = [
            'color' => $color_value
        ];
    }
    
    // Save back to database
    update_option('blocksy_ext_customizer_options', $options);
    
    return true;
}

/**
 * Alternative: Set Blocksy default colors via filter
 * This runs every time and sets the defaults (but users can still override)
 */
add_filter('blocksy_customizer_options_defaults', function($defaults) {
    // Set default colors that match our theme.json
    $defaults['colorPalette'] = [
        'paletteColor1' => [
            'color' => '#5a7f80', // Primary
        ],
        'paletteColor2' => [
            'color' => '#a36b57', // Secondary
        ],
        'paletteColor3' => [
            'color' => '#9b8974', // Neutral
        ],
        'paletteColor4' => [
            'color' => '#9c9c9c', // Base
        ],
        'paletteColor5' => [
            'color' => '#8dabac', // Primary Light
        ],
        'paletteColor6' => [
            'color' => '#425a5b', // Primary Dark
        ],
        'paletteColor7' => [
            'color' => '#d1a896', // Secondary Light
        ],
        'paletteColor8' => [
            'color' => '#744d3e', // Secondary Dark
        ],
    ];
    
    return $defaults;
});

/**
 * Create an admin tool to sync colors
 */
add_action('admin_menu', function() {
    add_submenu_page(
        'themes.php',
        'Sync Theme Colors',
        'Sync Theme Colors',
        'manage_options',
        'sync-theme-colors',
        'render_sync_colors_page'
    );
});

function render_sync_colors_page() {
    if (isset($_POST['sync_colors'])) {
        sync_theme_colors_to_blocksy();
        echo '<div class="notice notice-success"><p>Colors synced to Blocksy!</p></div>';
    }
    ?>
    <div class="wrap">
        <h1>Sync Theme Colors to Blocksy</h1>
        <p>This will set Blocksy's color palette to match your theme.json colors.</p>
        <form method="post">
            <?php wp_nonce_field('sync_colors_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th>Color Mapping</th>
                    <td>
                        <ul>
                            <li>Palette Color 1 → Primary (#5a7f80)</li>
                            <li>Palette Color 2 → Secondary (#a36b57)</li>
                            <li>Palette Color 3 → Neutral (#9b8974)</li>
                            <li>Palette Color 4 → Base (#9c9c9c)</li>
                            <li>Palette Color 5 → Primary Light</li>
                            <li>Palette Color 6 → Primary Dark</li>
                            <li>Palette Color 7 → Secondary Light</li>
                            <li>Palette Color 8 → Secondary Dark</li>
                        </ul>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="sync_colors" class="button-primary" value="Sync Colors to Blocksy">
            </p>
        </form>
    </div>
    <?php
}
