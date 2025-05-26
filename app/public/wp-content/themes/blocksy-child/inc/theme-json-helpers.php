<?php
/**
 * Theme JSON Helper Functions
 * Provides easy access to theme.json values for dynamic block options
 */

/**
 * Get theme colors for block options
 */
function get_theme_colors_for_blocks() {
    $settings = wp_get_global_settings();
    $colors = [];
    
    // Check for palette in different possible locations
    $palette = null;
    
    // Try nested structure first (Blocksy style)
    if (isset($settings['color']['palette']['default'])) {
        $palette = $settings['color']['palette']['default'];
    } 
    // Try direct palette
    elseif (isset($settings['color']['palette']) && is_array($settings['color']['palette']) && isset($settings['color']['palette'][0])) {
        $palette = $settings['color']['palette'];
    }
    
    if ($palette) {
        foreach ($palette as $color) {
            if (isset($color['name']) && isset($color['slug'])) {
                $colors[$color['slug']] = $color['name'];
            }
        }
    }
    
    // If no colors found, provide some defaults
    if (empty($colors)) {
        $colors = [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'black' => 'Black',
            'white' => 'White',
        ];
    }
    
    return $colors;
}

/**
 * Get font sizes for block options
 */
function get_theme_font_sizes_for_blocks() {
    $settings = wp_get_global_settings();
    $sizes = [];
    
    // Check for font sizes in different possible locations
    $font_sizes = null;
    
    // Try nested structure first (Blocksy style)
    if (isset($settings['typography']['fontSizes']['default'])) {
        $font_sizes = $settings['typography']['fontSizes']['default'];
    }
    // Try direct fontSizes
    elseif (isset($settings['typography']['fontSizes']) && is_array($settings['typography']['fontSizes']) && isset($settings['typography']['fontSizes'][0])) {
        $font_sizes = $settings['typography']['fontSizes'];
    }
    
    if ($font_sizes) {
        foreach ($font_sizes as $size) {
            if (isset($size['name']) && isset($size['slug'])) {
                $sizes[$size['slug']] = $size['name'];
            }
        }
    }
    
    // If no font sizes found, provide defaults
    if (empty($sizes)) {
        $sizes = [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
            'x-large' => 'X-Large',
        ];
    }
    
    return $sizes;
}

/**
 * Get spacing sizes for block options
 */
function get_theme_spacing_sizes_for_blocks() {
    $settings = wp_get_global_settings();
    $spacing_sizes = [];
    
    // Check for spacing sizes in different possible locations
    $sizes = null;
    
    // Try nested structure first (Blocksy style)
    if (isset($settings['spacing']['spacingSizes']['default'])) {
        $sizes = $settings['spacing']['spacingSizes']['default'];
    }
    // Try direct spacingSizes
    elseif (isset($settings['spacing']['spacingSizes']) && is_array($settings['spacing']['spacingSizes']) && isset($settings['spacing']['spacingSizes'][0])) {
        $sizes = $settings['spacing']['spacingSizes'];
    }
    
    if ($sizes) {
        foreach ($sizes as $size) {
            if (isset($size['name']) && isset($size['slug'])) {
                $spacing_sizes[$size['slug']] = $size['name'];
            }
        }
    }
    
    // If no spacing sizes found, provide defaults
    if (empty($spacing_sizes)) {
        $spacing_sizes = [
            '10' => 'Small (10px)',
            '20' => 'Medium (20px)',
            '30' => 'Large (30px)',
            '40' => 'X-Large (40px)',
            '60' => 'XX-Large (60px)',
        ];
    }
    
    return $spacing_sizes;
}

/**
 * Get CSS variable for color
 */
function get_color_css_var($slug) {
    if (strpos($slug, 'palette-color-') === 0) {
        $number = str_replace('palette-color-', '', $slug);
        return "var(--theme-palette-color-{$number})";
    }
    return "var(--wp--preset--color--{$slug})";
}

/**
 * Get CSS variable for font size
 */
function get_font_size_css_var($slug) {
    return "var(--wp--preset--font-size--{$slug})";
}

/**
 * Get CSS variable for spacing
 */
function get_spacing_css_var($size) {
    // For the generated scale
    if (strpos($size, 'size-') === 0) {
        $number = str_replace('size-', '', $size);
        $settings = wp_get_global_settings();
        $spacing_scale = $settings['spacing']['spacingScale']['default'];
        $value = pow($spacing_scale['increment'], $number);
        return $value . 'rem';
    }
    return "var(--wp--preset--spacing--{$size})";
}

/**
 * Compile block CSS using Twig
 * @param string $template CSS template with Twig syntax
 * @param array $data Data to pass to template
 * @return string Compiled CSS
 */
function compile_block_css($template, $data) {
    // For now, we'll use a simple string replacement approach
    // In production, you'd use Timber/Twig for proper compilation
    
    $css = $template;
    
    // Replace block_id
    $css = str_replace('{{ block_id }}', $data['block_id'] ?? '', $css);
    
    // Replace color variables
    if (preg_match_all('/{{ color_var\((.*?)\) }}/', $css, $matches)) {
        foreach ($matches[0] as $i => $match) {
            $field_path = trim($matches[1][$i]);
            $color_slug = get_nested_value($data, $field_path);
            $color_var = $color_slug ? "var(--wp--preset--color--{$color_slug})" : 'inherit';
            $css = str_replace($match, $color_var, $css);
        }
    }
    
    // Replace font size variables
    if (preg_match_all('/{{ font_size_var\((.*?)\) }}/', $css, $matches)) {
        foreach ($matches[0] as $i => $match) {
            $field_path = trim($matches[1][$i]);
            $size_slug = get_nested_value($data, $field_path);
            $size_var = $size_slug ? "var(--wp--preset--font-size--{$size_slug})" : 'inherit';
            $css = str_replace($match, $size_var, $css);
        }
    }
    
    // Replace spacing variables
    if (preg_match_all('/{{ spacing_var\((.*?)\) }}/', $css, $matches)) {
        foreach ($matches[0] as $i => $match) {
            $field_path = trim($matches[1][$i]);
            $spacing_slug = get_nested_value($data, $field_path);
            $spacing_var = $spacing_slug ? "var(--wp--preset--spacing--{$spacing_slug})" : '0';
            $css = str_replace($match, $spacing_var, $css);
        }
    }
    
    // Process conditionals (simplified version)
    $css = preg_replace_callback('/{% if (.*?) %}(.*?){% endif %}/s', function($matches) use ($data) {
        $condition = trim($matches[1]);
        $content = $matches[2];
        
        // Handle elseif blocks
        if (strpos($content, '{% elseif') !== false) {
            $parts = preg_split('/{% elseif (.*?) %}/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
            $if_content = $parts[0];
            
            // Check main if condition
            if (evaluate_simple_condition($condition, $data)) {
                return $if_content;
            }
            
            // Check elseif conditions
            for ($i = 1; $i < count($parts); $i += 2) {
                if (isset($parts[$i]) && isset($parts[$i + 1])) {
                    if (evaluate_simple_condition($parts[$i], $data)) {
                        return $parts[$i + 1];
                    }
                }
            }
            
            return '';
        }
        
        // Simple if condition
        if (evaluate_simple_condition($condition, $data)) {
            return $content;
        }
        
        return '';
    }, $css);
    
    // Clean up extra whitespace
    $css = preg_replace('/\s+/', ' ', $css);
    $css = trim($css);
    
    return $css;
}

/**
 * Helper function to get nested value from array
 */
function get_nested_value($data, $path) {
    $keys = explode('.', $path);
    $value = $data;
    
    foreach ($keys as $key) {
        if (is_array($value) && isset($value[$key])) {
            $value = $value[$key];
        } else {
            return null;
        }
    }
    
    return $value;
}

/**
 * Evaluate simple conditions for CSS compilation
 */
function evaluate_simple_condition($condition, $data) {
    // Handle == comparisons
    if (strpos($condition, '==') !== false) {
        list($left, $right) = explode('==', $condition);
        $left_value = get_nested_value($data, trim($left));
        $right_value = trim($right, ' "\'');
        return $left_value == $right_value;
    }
    
    // Handle != comparisons
    if (strpos($condition, '!=') !== false) {
        list($left, $right) = explode('!=', $condition);
        $left_value = get_nested_value($data, trim($left));
        $right_value = trim($right, ' "\'');
        return $left_value != $right_value;
    }
    
    // Default to false for unsupported conditions
    return false;
}

/**
 * Get a specific color value by slug
 */
function get_theme_color_value($slug) {
    $settings = wp_get_global_settings();
    
    // Check nested structure
    if (isset($settings['color']['palette']['default'])) {
        foreach ($settings['color']['palette']['default'] as $color) {
            if (isset($color['slug']) && $color['slug'] === $slug && isset($color['color'])) {
                return $color['color'];
            }
        }
    }
    
    // Check direct palette
    if (isset($settings['color']['palette'])) {
        foreach ($settings['color']['palette'] as $color) {
            if (isset($color['slug']) && $color['slug'] === $slug && isset($color['color'])) {
                return $color['color'];
            }
        }
    }
    
    // Return CSS variable as fallback
    return 'var(--wp--preset--color--' . $slug . ')';
}

/**
 * Get a specific font size value by slug
 */
function get_theme_font_size_value($slug) {
    $settings = wp_get_global_settings();
    
    // Check nested structure
    if (isset($settings['typography']['fontSizes']['default'])) {
        foreach ($settings['typography']['fontSizes']['default'] as $size) {
            if (isset($size['slug']) && $size['slug'] === $slug && isset($size['size'])) {
                return $size['size'];
            }
        }
    }
    
    // Check direct fontSizes
    if (isset($settings['typography']['fontSizes'])) {
        foreach ($settings['typography']['fontSizes'] as $size) {
            if (isset($size['slug']) && $size['slug'] === $slug && isset($size['size'])) {
                return $size['size'];
            }
        }
    }
    
    // Return CSS variable as fallback
    return 'var(--wp--preset--font-size--' . $slug . ')';
}

/**
 * Get a specific spacing value by slug
 */
function get_theme_spacing_value($slug) {
    $settings = wp_get_global_settings();
    
    // Check nested structure
    if (isset($settings['spacing']['spacingSizes']['default'])) {
        foreach ($settings['spacing']['spacingSizes']['default'] as $size) {
            if (isset($size['slug']) && $size['slug'] === $slug && isset($size['size'])) {
                return $size['size'];
            }
        }
    }
    
    // Check direct spacingSizes
    if (isset($settings['spacing']['spacingSizes'])) {
        foreach ($settings['spacing']['spacingSizes'] as $size) {
            if (isset($size['slug']) && $size['slug'] === $slug && isset($size['size'])) {
                return $size['size'];
            }
        }
    }
    
    // Return the slug with px as fallback
    return $slug . 'px';
}
