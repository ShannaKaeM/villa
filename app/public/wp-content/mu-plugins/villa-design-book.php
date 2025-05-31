<?php
/**
 * Villa DesignBook - Visual Design System Manager
 * 
 * A comprehensive design token management system that allows visual editing
 * of colors, typography, and components with live preview capabilities.
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class VillaDesignBook {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_ajax_villa_save_design_tokens', array($this, 'save_design_tokens'));
        add_action('wp_ajax_villa_export_design_tokens', array($this, 'export_design_tokens'));
        add_action('wp_ajax_villa_import_design_tokens', array($this, 'import_design_tokens'));
        add_action('wp_ajax_villa_save_colors', array($this, 'save_colors'));
        add_action('wp_ajax_villa_reset_colors', array($this, 'reset_colors'));
        add_action('wp_ajax_villa_save_text_styles', array($this, 'save_text_styles'));
        add_action('wp_ajax_villa_reset_text_styles', array($this, 'reset_text_styles'));
        add_action('wp_ajax_villa_save_base_styles', array($this, 'save_base_styles'));
        add_action('wp_ajax_villa_reset_base_styles', array($this, 'reset_base_styles'));
        add_action('wp_ajax_villa_save_card_component', array($this, 'save_card_component')); // Add AJAX handler for saving card component settings
        add_action('wp_ajax_villa_save_button_styles', array($this, 'save_button_styles'));
        add_action('wp_ajax_villa_reset_button_styles', array($this, 'reset_button_styles'));
        add_action('wp_ajax_villa_save_layout_tokens', array($this, 'save_layout_tokens')); // Add AJAX handler for saving layout tokens
        add_action('wp_ajax_villa_reset_layout_tokens', array($this, 'reset_layout_tokens')); // Add AJAX handler for resetting layout tokens
    }
    
    /**
     * Add DesignBook to admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'DesignBook',
            'DesignBook',
            'manage_options',
            'villa-design-book',
            array($this, 'render_main_page'),
            'dashicons-art',
            30
        );
        
        // PRIMITIVES SUBMENU PAGES
        add_submenu_page(
            'villa-design-book',
            'Color Book',
            '🎨 Color Book',
            'manage_options',
            'villa-color-book',
            array($this, 'render_color_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'Typography Book',
            '📝 Typography Book',
            'manage_options',
            'villa-typography-book',
            array($this, 'render_typography_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'Layout Book',
            '📐 Layout Book',
            'manage_options',
            'villa-layout-book',
            array($this, 'render_layout_book')
        );
        
        // ELEMENTS SUBMENU PAGES
        add_submenu_page(
            'villa-design-book',
            'Button Book',
            '🖋️ Button Book',
            'manage_options',
            'villa-button-book',
            array($this, 'render_button_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'Text Book',
            '📄 Text Book',
            'manage_options',
            'villa-text-book',
            array($this, 'render_text_book')
        );
        
        // COMPONENTS SUBMENU PAGES
        add_submenu_page(
            'villa-design-book',
            'Card Book',
            '🃏 Card Book',
            'manage_options',
            'villa-card-book',
            array($this, 'render_card_book')
        );
        
        // SECTIONS SUBMENU PAGES
        add_submenu_page(
            'villa-design-book',
            'Hero Book',
            '🦸 Hero Book',
            'manage_options',
            'villa-hero-book',
            array($this, 'render_hero_book')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'villa-design-book') === false && 
            strpos($hook, 'villa-color-book') === false && 
            strpos($hook, 'villa-typography-book') === false && 
            strpos($hook, 'villa-button-book') === false && 
            strpos($hook, 'villa-component-book') === false && 
            strpos($hook, 'villa-base-options') === false) {
            return;
        }
        
        // Enqueue WordPress media scripts
        wp_enqueue_media();
        
        // Enqueue our scripts and styles
        wp_enqueue_style(
            'villa-design-book-css',
            get_template_directory_uri() . '/assets/css/villa-design-book.css',
            array(),
            '1.0.6' // Incremented for Base Options styling
        );
        
        wp_enqueue_script(
            'villa-design-book-js',
            get_template_directory_uri() . '/assets/js/villa-design-book.js',
            array('jquery', 'media-upload', 'media-views'),
            '1.0.6', // Incremented for Base Options functionality
            true
        );
        
        // Localize script with AJAX data
        wp_localize_script('villa-design-book-js', 'villa_design_book', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('villa_design_book_nonce')
        ));
    }
    
    /**
     * Get current theme.json data
     */
    private function get_theme_json_data() {
        $theme_json_path = get_template_directory() . '/theme.json';
        if (file_exists($theme_json_path)) {
            $content = file_get_contents($theme_json_path);
            return json_decode($content, true);
        }
        return array();
    }
    
    /**
     * Save theme.json data
     */
    private function save_theme_json_data($data) {
        $theme_json_path = get_template_directory() . '/theme.json';
        return file_put_contents($theme_json_path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    /**
     * Render main DesignBook dashboard
     */
    public function render_main_page() {
        ?>
        <div class="wrap villa-design-book">
            <h1>🎨 DesignBook</h1>
            <p class="description">Visual design system manager for Villa Community. Create, edit, and manage your design tokens with live preview.</p>
            
            <div class="design-book-dashboard">
                
                <!-- PRIMITIVES SECTION -->
                <div class="design-book-section">
                    <h2>🔧 Primitives</h2>
                    <p class="section-description">Core design tokens - the foundation of your design system</p>
                    <div class="design-book-grid">
                        
                        <div class="design-book-card">
                            <div class="card-icon">🎨</div>
                            <h3>Color Book</h3>
                            <p>Pure color tokens - primary, secondary, neutral palettes with light, medium, and dark variations.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-color-book'); ?>" class="button button-primary">Open Color Book</a>
                        </div>
                        
                        <div class="design-book-card">
                            <div class="card-icon">📝</div>
                            <h3>Typography Book</h3>
                            <p>Font sizes, weights, line heights, and letter spacing tokens.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-typography-book'); ?>" class="button button-primary">Open Typography Book</a>
                        </div>
                        
                        <div class="design-book-card">
                            <div class="card-icon">📐</div>
                            <h3>Layout Book</h3>
                            <p>Spacing, border radius, shadows, and grid layout tokens.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-layout-book'); ?>" class="button button-primary">Open Layout Book</a>
                        </div>
                        
                    </div>
                </div>
                
                <!-- ELEMENTS SECTION -->
                <div class="design-book-section">
                    <h2>🧱 Elements</h2>
                    <p class="section-description">Reusable components built with primitives</p>
                    <div class="design-book-grid">
                        
                        <div class="design-book-card">
                            <div class="card-icon">🖋️</div>
                            <h3>Button Book</h3>
                            <p>Button variants and styles using color, typography, and layout primitives.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-button-book'); ?>" class="button button-primary">Open Button Book</a>
                        </div>
                        
                        <div class="design-book-card">
                            <div class="card-icon">📄</div>
                            <h3>Text Book</h3>
                            <p>Semantic text elements: pretitle, title, subtitle, description using typography primitives.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-text-book'); ?>" class="button button-primary">Open Text Book</a>
                        </div>
                        
                    </div>
                </div>
                
                <!-- COMPONENTS SECTION -->
                <div class="design-book-section">
                    <h2>🧩 Components</h2>
                    <p class="section-description">Complex compositions using elements and primitives</p>
                    <div class="design-book-grid">
                        
                        <div class="design-book-card">
                            <div class="card-icon">🃏</div>
                            <h3>Card Book</h3>
                            <p>Card components combining text elements, buttons, and layout primitives.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-card-book'); ?>" class="button button-primary">Open Card Book</a>
                        </div>
                        
                    </div>
                </div>
                
                <!-- SECTIONS SECTION -->
                <div class="design-book-section">
                    <h2>🏗️ Sections</h2>
                    <p class="section-description">Large layout compositions using components, elements, and primitives</p>
                    <div class="design-book-grid">
                        
                        <div class="design-book-card">
                            <div class="card-icon">🦸</div>
                            <h3>Hero Book</h3>
                            <p>Hero sections combining cards, text elements, buttons, and layout primitives.</p>
                            <a href="<?php echo admin_url('admin.php?page=villa-hero-book'); ?>" class="button button-primary">Open Hero Book</a>
                        </div>
                        
                    </div>
                </div>
                
                <div class="design-book-actions">
                    <button class="button button-secondary" id="export-design-tokens">📤 Export Design Tokens</button>
                    <button class="button button-secondary" id="import-design-tokens">📥 Import Design Tokens</button>
                    <input type="file" id="import-file" accept=".json" style="display: none;">
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render ColorBook page
     */
    public function render_color_book() {
        $theme_data = $this->get_theme_json_data();
        $colors = $theme_data['settings']['color']['palette'] ?? array();
        
        ?>
        <div class="wrap villa-color-book">
            <h1>🎨 ColorBook</h1>
            <p class="description">Manage your color palette with precision. Adjust Cyan, Magenta, Yellow, and Black (CMYK) values for perfect color harmony.</p>
            
            <div class="color-palette-editor-full">
                <?php $this->render_color_palette($colors); ?>
            </div>
            
            <div class="color-book-actions">
                <button class="button button-primary" id="save-colors">💾 Save Colors</button>
                <button class="button button-secondary" id="reset-colors">🔄 Reset to Default</button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render color palette editor
     */
    private function render_color_palette($colors) {
        // Group colors by type - matching theme.json order
        $color_groups = array(
            'primary' => array('primary-light', 'primary', 'primary-dark'),
            'secondary' => array('secondary-light', 'secondary', 'secondary-dark'),
            'neutral' => array('neutral-light', 'neutral', 'neutral-dark'),
            'base' => array('base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest'),
            'extreme' => array('extreme-light', 'extreme-dark')
        );
        
        echo '<div class="color-palette-grid">';
        
        foreach ($color_groups as $group_name => $group_colors) {
            echo '<div class="color-group" data-group="' . esc_attr($group_name) . '">';
            echo '<h4>' . ucfirst(str_replace('-', ' ', $group_name)) . '</h4>';
            echo '<div class="color-row">';
            
            foreach ($group_colors as $color_slug) {
                $color_data = $this->find_color_by_slug($colors, $color_slug);
                if ($color_data) {
                    $this->render_color_swatch($color_data);
                }
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    /**
     * Find color by slug
     */
    private function find_color_by_slug($colors, $slug) {
        foreach ($colors as $color) {
            if ($color['slug'] === $slug) {
                return $color;
            }
        }
        return null;
    }
    
    /**
     * Render individual color swatch
     */
    private function render_color_swatch($color) {
        // Try to get saved CMYK data, fallback to conversion from hex
        $saved_cmyk = get_option('villa_color_cmyk_' . $color['slug'], null);
        $cmyk_data = $saved_cmyk ? $saved_cmyk : $this->hex_to_cmyk_approximate($color['color']);
        
        // Try to get saved HSLA data, fallback to conversion from hex
        $saved_hsla = get_option('villa_color_hsla_' . $color['slug'], null);
        $hsla_data = $saved_hsla ? $saved_hsla : $this->hex_to_hsla($color['color']);
        ?>
        <div class="color-swatch" data-slug="<?php echo esc_attr($color['slug']); ?>">
            <div class="color-preview-box" style="background-color: <?php echo esc_attr($color['color']); ?>"></div>
            <div class="color-controls">
                <label><?php echo esc_html($color['name']); ?></label>
                
                <!-- Hex Input -->
                <div class="hex-input-group">
                    <label>Hex Color</label>
                    <input type="text" value="<?php echo esc_attr($color['color']); ?>" class="hex-input" placeholder="#000000">
                </div>
                
                <!-- Color Picker -->
                <input type="color" value="<?php echo esc_attr($color['color']); ?>" class="color-picker">
                
                <!-- Color Mode Tabs -->
                <div class="color-mode-tabs">
                    <button type="button" class="color-mode-tab active" data-mode="hsla">HSLA</button>
                    <button type="button" class="color-mode-tab" data-mode="cmyk">CMYK</button>
                </div>
                
                <!-- HSLA Controls -->
                <div class="hsla-controls color-controls-panel active">
                    <div class="hsla-input-group">
                        <label>Hue</label>
                        <input type="range" min="0" max="360" value="<?php echo esc_attr($hsla_data['h'] ?? 0); ?>" class="hue-slider">
                        <span class="hsla-value"><?php echo esc_attr($hsla_data['h'] ?? 0); ?>°</span>
                    </div>
                    
                    <div class="hsla-input-group">
                        <label>Saturation</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($hsla_data['s'] ?? 0); ?>" class="saturation-slider">
                        <span class="hsla-value"><?php echo esc_attr($hsla_data['s'] ?? 0); ?>%</span>
                    </div>
                    
                    <div class="hsla-input-group">
                        <label>Lightness</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($hsla_data['l'] ?? 0); ?>" class="lightness-slider">
                        <span class="hsla-value"><?php echo esc_attr($hsla_data['l'] ?? 0); ?>%</span>
                    </div>
                    
                    <div class="hsla-input-group">
                        <label>Alpha</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($hsla_data['a'] ?? 100); ?>" class="alpha-slider">
                        <span class="hsla-value"><?php echo esc_attr($hsla_data['a'] ?? 100); ?>%</span>
                    </div>
                </div>
                
                <!-- CMYK Controls -->
                <div class="cmyk-controls color-controls-panel">
                    <div class="cmyk-input-group">
                        <label>Cyan</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($cmyk_data['c'] ?? 0); ?>" class="cyan-slider">
                        <span class="cmyk-value"><?php echo esc_attr($cmyk_data['c'] ?? 0); ?>%</span>
                    </div>
                    
                    <div class="cmyk-input-group">
                        <label>Magenta</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($cmyk_data['m'] ?? 0); ?>" class="magenta-slider">
                        <span class="cmyk-value"><?php echo esc_attr($cmyk_data['m'] ?? 0); ?>%</span>
                    </div>
                    
                    <div class="cmyk-input-group">
                        <label>Yellow</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($cmyk_data['y'] ?? 0); ?>" class="yellow-slider">
                        <span class="cmyk-value"><?php echo esc_attr($cmyk_data['y'] ?? 0); ?>%</span>
                    </div>
                    
                    <div class="cmyk-input-group">
                        <label>Black</label>
                        <input type="range" min="0" max="100" value="<?php echo esc_attr($cmyk_data['k'] ?? 0); ?>" class="black-slider">
                        <span class="cmyk-value"><?php echo esc_attr($cmyk_data['k'] ?? 0); ?>%</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Simple hex to CMYK approximation for initial values
     */
    private function hex_to_cmyk_approximate($hex) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        
        // Simple approximation to CMYK
        $k = 1 - max($r, $g, $b);
        
        if ($k == 1) {
            // Pure black
            $c = $m = $y = 0;
        } else {
            $c = (1 - $r - $k) / (1 - $k);
            $m = (1 - $g - $k) / (1 - $k);
            $y = (1 - $b - $k) / (1 - $k);
        }
        
        return [
            'c' => round($c * 100, 1),
            'm' => round($m * 100, 1),
            'y' => round($y * 100, 1),
            'k' => round($k * 100, 1)
        ];
    }
    
    /**
     * Simple hex to HSLA conversion
     */
    private function hex_to_hsla($hex) {
        // Remove # if present
        $hex = ltrim($hex, '#');
        
        $r = hexdec(substr($hex, 0, 2)) / 255;
        $g = hexdec(substr($hex, 2, 2)) / 255;
        $b = hexdec(substr($hex, 4, 2)) / 255;
        
        // Calculate HSLA values
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $delta = $max - $min;
        
        $h = 0;
        $s = 0;
        $l = ($max + $min) / 2;
        
        if ($delta != 0) {
            $s = $delta / (1 - abs(2 * $l - 1));
            
            if ($r == $max) {
                $h = ($g - $b) / $delta;
            } elseif ($g == $max) {
                $h = 2 + ($b - $r) / $delta;
            } else {
                $h = 4 + ($r - $g) / $delta;
            }
            
            $h *= 60;
            if ($h < 0) {
                $h += 360;
            }
        }
        
        $a = 1; // Alpha is always 1 for solid colors
        
        return [
            'h' => round($h, 1),
            's' => round($s * 100, 1),
            'l' => round($l * 100, 1),
            'a' => round($a * 100, 1)
        ];
    }
    
    /**
     * Render TextBook page
     */
    public function render_text_book() {
        $theme_json = $this->get_theme_json_data();
        
        // Get current text styles from theme.json
        $text_styles = [];
        if (isset($theme_json['settings']['typography']['customTextStyles'])) {
            $text_styles = $theme_json['settings']['typography']['customTextStyles'];
        }
        
        // Default semantic text styles that use typography primitives
        $default_styles = [
            'pretitle' => [
                'htmlTag' => 'span',
                'fontSize' => 'sm',
                'fontWeight' => '600',
                'fontFamily' => 'inter',
                'textTransform' => 'uppercase',
                'letterSpacing' => 'wide',
                'lineHeight' => 'tight',
                'color' => 'primary'
            ],
            'title' => [
                'htmlTag' => 'h1',
                'fontSize' => '4xl',
                'fontWeight' => '700',
                'fontFamily' => 'playfair-display',
                'textTransform' => 'none',
                'letterSpacing' => 'tight',
                'lineHeight' => 'tight',
                'color' => 'base-darkest'
            ],
            'subtitle' => [
                'htmlTag' => 'h2',
                'fontSize' => '2xl',
                'fontWeight' => '600',
                'fontFamily' => 'playfair-display',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => 'snug',
                'color' => 'base-dark'
            ],
            'description' => [
                'htmlTag' => 'p',
                'fontSize' => 'lg',
                'fontWeight' => '400',
                'fontFamily' => 'inter',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => 'relaxed',
                'color' => 'base-dark'
            ],
            'body' => [
                'htmlTag' => 'p',
                'fontSize' => 'base',
                'fontWeight' => '400',
                'fontFamily' => 'inter',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => 'relaxed',
                'color' => 'base-darkest'
            ],
            'caption' => [
                'htmlTag' => 'span',
                'fontSize' => 'sm',
                'fontWeight' => '400',
                'fontFamily' => 'inter',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => 'normal',
                'color' => 'base-medium'
            ]
        ];
        
        // Merge with saved styles
        $text_styles = array_merge($default_styles, $text_styles);
        
        ?>
        <div class="wrap villa-text-book">
            <h1>🧱 Text Book</h1>
            <p class="description">Semantic text elements that use typography primitives for consistent text styling across your site.</p>
            
            <div class="text-elements-container">
                
                <!-- Semantic Text Elements -->
                <div class="text-elements-section">
                    <h2>📝 Semantic Text Elements</h2>
                    <p class="section-description">Reusable text components that combine typography primitives with semantic meaning.</p>
                    
                    <div class="text-styles-grid">
                        <?php foreach ($text_styles as $style_key => $style_data): ?>
                            <div class="text-style-card" data-style="<?php echo esc_attr($style_key); ?>">
                                <div class="card-header">
                                    <h3><?php echo ucfirst($style_key); ?></h3>
                                    <p class="style-description">
                                        <?php 
                                        $descriptions = [
                                            'pretitle' => 'Small, uppercase text that appears above the main title',
                                            'title' => 'Primary heading for pages and sections',
                                            'subtitle' => 'Secondary heading that supports the main title',
                                            'description' => 'Descriptive text that provides context and details',
                                            'body' => 'Standard paragraph text for main content',
                                            'caption' => 'Small explanatory text for images and details'
                                        ];
                                        echo $descriptions[$style_key] ?? 'Custom semantic text style';
                                        ?>
                                    </p>
                                </div>
                                
                                <div class="preview-section">
                                    <div class="preview-text" data-style="<?php echo esc_attr($style_key); ?>">
                                        <?php 
                                        $preview_texts = [
                                            'pretitle' => 'VILLA COMMUNITY',
                                            'title' => 'Beautiful Typography',
                                            'subtitle' => 'Crafted for readability',
                                            'description' => 'This is how your descriptive text will appear. It provides context and additional information to support your content.',
                                            'body' => 'This is standard body text that makes up the majority of your content. It should be highly readable and comfortable for extended reading.',
                                            'caption' => 'Image caption or small detail text'
                                        ];
                                        echo $preview_texts[$style_key] ?? 'Sample text';
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="controls-section">
                                    <div class="style-controls">
                                        <div class="control-row">
                                            <div class="control-group">
                                                <label>HTML Tag</label>
                                                <select name="htmlTag" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="h1" <?php selected($style_data['htmlTag'], 'h1'); ?>>H1</option>
                                                    <option value="h2" <?php selected($style_data['htmlTag'], 'h2'); ?>>H2</option>
                                                    <option value="h3" <?php selected($style_data['htmlTag'], 'h3'); ?>>H3</option>
                                                    <option value="h4" <?php selected($style_data['htmlTag'], 'h4'); ?>>H4</option>
                                                    <option value="p" <?php selected($style_data['htmlTag'], 'p'); ?>>P</option>
                                                    <option value="span" <?php selected($style_data['htmlTag'], 'span'); ?>>Span</option>
                                                </select>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label>Font Size (Primitive)</label>
                                                <select name="fontSize" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="xs" <?php selected($style_data['fontSize'], 'xs'); ?>>Extra Small</option>
                                                    <option value="sm" <?php selected($style_data['fontSize'], 'sm'); ?>>Small</option>
                                                    <option value="base" <?php selected($style_data['fontSize'], 'base'); ?>>Base</option>
                                                    <option value="lg" <?php selected($style_data['fontSize'], 'lg'); ?>>Large</option>
                                                    <option value="xl" <?php selected($style_data['fontSize'], 'xl'); ?>>Extra Large</option>
                                                    <option value="2xl" <?php selected($style_data['fontSize'], '2xl'); ?>>2X Large</option>
                                                    <option value="3xl" <?php selected($style_data['fontSize'], '3xl'); ?>>3X Large</option>
                                                    <option value="4xl" <?php selected($style_data['fontSize'], '4xl'); ?>>4X Large</option>
                                                    <option value="5xl" <?php selected($style_data['fontSize'], '5xl'); ?>>5X Large</option>
                                                    <option value="6xl" <?php selected($style_data['fontSize'], '6xl'); ?>>6X Large</option>
                                                </select>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label>Font Weight (Primitive)</label>
                                                <select name="fontWeight" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="100" <?php selected($style_data['fontWeight'], '100'); ?>>Thin (100)</option>
                                                    <option value="300" <?php selected($style_data['fontWeight'], '300'); ?>>Light (300)</option>
                                                    <option value="400" <?php selected($style_data['fontWeight'], '400'); ?>>Normal (400)</option>
                                                    <option value="500" <?php selected($style_data['fontWeight'], '500'); ?>>Medium (500)</option>
                                                    <option value="600" <?php selected($style_data['fontWeight'], '600'); ?>>Semibold (600)</option>
                                                    <option value="700" <?php selected($style_data['fontWeight'], '700'); ?>>Bold (700)</option>
                                                    <option value="800" <?php selected($style_data['fontWeight'], '800'); ?>>Extra Bold (800)</option>
                                                    <option value="900" <?php selected($style_data['fontWeight'], '900'); ?>>Black (900)</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="control-row">
                                            <div class="control-group">
                                                <label>Line Height (Primitive)</label>
                                                <select name="lineHeight" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="tight" <?php selected($style_data['lineHeight'], 'tight'); ?>>Tight (1.1)</option>
                                                    <option value="snug" <?php selected($style_data['lineHeight'], 'snug'); ?>>Snug (1.2)</option>
                                                    <option value="normal" <?php selected($style_data['lineHeight'], 'normal'); ?>>Normal (1.4)</option>
                                                    <option value="relaxed" <?php selected($style_data['lineHeight'], 'relaxed'); ?>>Relaxed (1.6)</option>
                                                    <option value="loose" <?php selected($style_data['lineHeight'], 'loose'); ?>>Loose (1.8)</option>
                                                </select>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label>Letter Spacing (Primitive)</label>
                                                <select name="letterSpacing" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="tighter" <?php selected($style_data['letterSpacing'], 'tighter'); ?>>Tighter</option>
                                                    <option value="tight" <?php selected($style_data['letterSpacing'], 'tight'); ?>>Tight</option>
                                                    <option value="normal" <?php selected($style_data['letterSpacing'], 'normal'); ?>>Normal</option>
                                                    <option value="wide" <?php selected($style_data['letterSpacing'], 'wide'); ?>>Wide</option>
                                                    <option value="wider" <?php selected($style_data['letterSpacing'], 'wider'); ?>>Wider</option>
                                                    <option value="widest" <?php selected($style_data['letterSpacing'], 'widest'); ?>>Widest</option>
                                                </select>
                                            </div>
                                            
                                            <div class="control-group">
                                                <label>Color (Primitive)</label>
                                                <select name="color" class="style-control" data-style="<?php echo esc_attr($style_key); ?>">
                                                    <option value="primary" <?php selected($style_data['color'], 'primary'); ?>>Primary</option>
                                                    <option value="secondary" <?php selected($style_data['color'], 'secondary'); ?>>Secondary</option>
                                                    <option value="base-darkest" <?php selected($style_data['color'], 'base-darkest'); ?>>Base Darkest</option>
                                                    <option value="base-dark" <?php selected($style_data['color'], 'base-dark'); ?>>Base Dark</option>
                                                    <option value="base-medium" <?php selected($style_data['color'], 'base-medium'); ?>>Base Medium</option>
                                                    <option value="base-light" <?php selected($style_data['color'], 'base-light'); ?>>Base Light</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Primitive Usage Display -->
                                <div class="primitives-used">
                                    <h4>🔧 Primitives Used</h4>
                                    <div class="primitive-tokens">
                                        <code>--wp--preset--font-size--<?php echo $style_data['fontSize']; ?></code>
                                        <code>font-weight: <?php echo $style_data['fontWeight']; ?></code>
                                        <code>line-height: <?php echo $style_data['lineHeight']; ?></code>
                                        <code>letter-spacing: <?php echo $style_data['letterSpacing']; ?></code>
                                        <code>--wp--preset--color--<?php echo $style_data['color']; ?></code>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Usage Examples -->
                <div class="usage-examples-section">
                    <h2>💡 Usage Examples</h2>
                    <p class="section-description">How to use these semantic text elements in your templates:</p>
                    
                    <div class="usage-examples-grid">
                        <div class="usage-example">
                            <h4>Twig Template Usage</h4>
                            <pre><code>&lt;span class="text-pretitle"&gt;{{ pretitle }}&lt;/span&gt;
&lt;h1 class="text-title"&gt;{{ title }}&lt;/h1&gt;
&lt;h2 class="text-subtitle"&gt;{{ subtitle }}&lt;/h2&gt;
&lt;p class="text-description"&gt;{{ description }}&lt;/p&gt;
&lt;p class="text-body"&gt;{{ content }}&lt;/p&gt;</code></pre>
                        </div>
                        
                        <div class="usage-example">
                            <h4>CSS Class Generation</h4>
                            <pre><code>.text-pretitle {
  font-size: var(--wp--preset--font-size--sm);
  font-weight: 600;
  line-height: 1.1;
  letter-spacing: 0.025em;
  color: var(--wp--preset--color--primary);
  text-transform: uppercase;
}</code></pre>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="text-actions">
                <button type="button" id="save-text-elements" class="button button-primary">💾 Save Text Elements</button>
                <button type="button" id="reset-text-elements" class="button">🔄 Reset to Defaults</button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render ButtonBook page
     */
    public function render_button_book() {
        ?>
        <div class="wrap villa-button-book">
            <h1>🖋️ ButtonBook</h1>
            <p class="description">Design and customize buttons with various styles, sizes, and colors.</p>
            
            <div class="button-book-container">
                <div class="button-book-editor">
                    <div class="button-controls">
                        <div class="variant-group">
                            <h3>Button Settings</h3>
                            
                            <div class="variant-controls">
                                <div class="control-group">
                                    <label for="button-text">Text</label>
                                    <input type="text" id="button-text" class="variant-control" value="Default Button" placeholder="Enter button text">
                                </div>
                                
                                <div class="control-group">
                                    <label for="button-style">Style</label>
                                    <select name="button-style" class="variant-control">
                                        <option value="primary">Primary</option>
                                        <option value="secondary">Secondary</option>
                                        <option value="tertiary">Tertiary</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="button-size">Size</label>
                                    <select name="button-size" class="variant-control">
                                        <option value="small">Small</option>
                                        <option value="medium">Medium</option>
                                        <option value="large">Large</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="button-color">Color</label>
                                    <select name="button-color" class="variant-control">
                                        <option value="primary">Primary</option>
                                        <option value="secondary">Secondary</option>
                                        <option value="neutral">Neutral</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="button-actions">
                                <button type="button" id="save-button" class="button button-primary">Save Button</button>
                                <button type="button" id="reset-button" class="button">Reset to Default</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="button-preview">
                        <h3>Live Preview</h3>
                        <div class="button-preview-container">
                            <button class="villa-button" id="button-preview">Default Button</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render ComponentBook page
     */
    public function render_component_book() {
        ?>
        <div class="wrap villa-component-book">
            <h1>🧩 ComponentBook</h1>
            <p class="description">Design system architecture showcasing primitives, elements, and components built with design tokens.</p>
            
            <!-- Architecture Overview -->
            <div class="architecture-overview">
                <h2>🏗️ Architecture Overview</h2>
                <div class="architecture-grid">
                    <div class="architecture-card">
                        <h3>🔧 Primitives</h3>
                        <p>Pure design token utilities</p>
                        <ul>
                            <li>color.twig</li>
                            <li>typography.twig</li>
                            <li>spacing.twig</li>
                            <li>layout.twig</li>
                        </ul>
                    </div>
                    <div class="architecture-card">
                        <h3>📦 Elements</h3>
                        <p>Reusable components with variants</p>
                        <ul>
                            <li>button-book.twig</li>
                            <li>text-book.twig</li>
                        </ul>
                    </div>
                    <div class="architecture-card">
                        <h3>🏢 Components</h3>
                        <p>Complex compositions</p>
                        <ul>
                            <li>card-book.twig</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Primitives Section -->
            <div class="component-section">
                <h2>🔧 Primitives</h2>
                <p>Pure design token utilities that provide the foundation for all components.</p>
                
                <div class="primitive-showcase">
                    <div class="primitive-demo">
                        <h3>Color Primitive</h3>
                        <div class="demo-grid">
                            <div class="color-demo" style="background: var(--wp--preset--color--primary); color: white; padding: 1rem; border-radius: 8px;">
                                Primary Color
                            </div>
                            <div class="color-demo" style="background: var(--wp--preset--color--secondary); color: white; padding: 1rem; border-radius: 8px;">
                                Secondary Color
                            </div>
                            <div class="color-demo" style="background: var(--wp--preset--color--neutral); color: white; padding: 1rem; border-radius: 8px;">
                                Neutral Color
                            </div>
                        </div>
                        <code>{% include 'components/primitives/color.twig' with { 'color': 'primary', 'property': 'background-color' } %}</code>
                    </div>

                    <div class="primitive-demo">
                        <h3>Typography Primitive</h3>
                        <div class="demo-grid">
                            <div style="font-size: var(--wp--preset--font-size--small);">Small Text</div>
                            <div style="font-size: var(--wp--preset--font-size--medium);">Medium Text</div>
                            <div style="font-size: var(--wp--preset--font-size--large); font-weight: 600;">Large Bold Text</div>
                        </div>
                        <code>{% include 'components/primitives/typography.twig' with { 'size': 'large', 'weight': '600' } %}</code>
                    </div>

                    <div class="primitive-demo">
                        <h3>Spacing Primitive</h3>
                        <div class="demo-grid">
                            <div style="padding: var(--wp--custom--layout--spacing--sm); background: #f0f0f0; margin-bottom: 8px;">Small Padding</div>
                            <div style="padding: var(--wp--custom--layout--spacing--md); background: #f0f0f0; margin-bottom: 8px;">Medium Padding</div>
                            <div style="padding: var(--wp--custom--layout--spacing--lg); background: #f0f0f0;">Large Padding</div>
                        </div>
                        <code>{% include 'components/primitives/spacing.twig' with { 'type': 'padding', 'size': 'lg' } %}</code>
                    </div>

                    <div class="primitive-demo">
                        <h3>Layout Primitive</h3>
                        <div class="demo-grid">
                            <div style="border-radius: var(--wp--custom--layout--border-radius--sm); background: #e0e0e0; padding: 1rem; margin-bottom: 8px;">Small Radius</div>
                            <div style="border-radius: var(--wp--custom--layout--border-radius--md); background: #e0e0e0; padding: 1rem; margin-bottom: 8px;">Medium Radius</div>
                            <div style="border-radius: var(--wp--custom--layout--border-radius--lg); background: #e0e0e0; padding: 1rem;">Large Radius</div>
                        </div>
                        <code>{% include 'components/primitives/layout.twig' with { 'property': 'border-radius', 'size': 'lg' } %}</code>
                    </div>
                </div>
            </div>

            <!-- Elements Section -->
            <div class="component-section">
                <h2>📦 Elements</h2>
                <p>Reusable components built using primitives with variants and semantic meaning.</p>
                
                <div class="element-showcase">
                    <div class="element-demo">
                        <h3>Button Element (button-book.twig)</h3>
                        <p>Unified button element built with primitives:</p>
                        <div class="button-variants">
                            <button class="villa-button villa-button--primary villa-button--medium" style="
                                background-color: var(--wp--preset--color--primary);
                                color: white;
                                padding: var(--wp--custom--layout--spacing--md);
                                border-radius: var(--wp--custom--layout--border-radius--md);
                                border: none;
                                font-size: var(--wp--preset--font-size--medium);
                                font-weight: 500;
                                margin-right: 8px;
                            ">Primary Button</button>
                            
                            <button class="villa-button villa-button--secondary villa-button--medium" style="
                                background-color: var(--wp--preset--color--secondary);
                                color: white;
                                padding: var(--wp--custom--layout--spacing--md);
                                border-radius: var(--wp--custom--layout--border-radius--md);
                                border: none;
                                font-size: var(--wp--preset--font-size--medium);
                                font-weight: 500;
                                margin-right: 8px;
                            ">Secondary Button</button>
                            
                            <button class="villa-button villa-button--outline villa-button--medium" style="
                                background-color: transparent;
                                color: var(--wp--preset--color--primary);
                                padding: var(--wp--custom--layout--spacing--md);
                                border-radius: var(--wp--custom--layout--border-radius--md);
                                border: 2px solid var(--wp--preset--color--primary);
                                font-size: var(--wp--preset--font-size--medium);
                                font-weight: 500;
                            ">Outline Button</button>
                        </div>
                        <div class="code-example">
                            <strong>Uses primitives:</strong>
                            <ul>
                                <li>typography.twig for font styling</li>
                                <li>color.twig for background, text, and border colors</li>
                                <li>spacing.twig for padding</li>
                                <li>layout.twig for border radius</li>
                            </ul>
                        </div>
                        <code>{% include 'elements/button-book.twig' with { 'variant': 'primary', 'size': 'medium', 'content': 'Button Text' } %}</code>
                    </div>

                    <div class="element-demo">
                        <h3>Text Element (text-book.twig)</h3>
                        <p>Semantic text element with typography variants:</p>
                        <div class="text-variants">
                            <div style="font-size: var(--wp--preset--font-size--small); color: var(--wp--preset--color--neutral); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px;">Pretitle Text</div>
                            <div style="font-size: var(--wp--preset--font-size--x-large); font-weight: 700; color: var(--wp--preset--color--base); margin-bottom: 8px;">Title Text</div>
                            <div style="font-size: var(--wp--preset--font-size--large); font-weight: 600; color: var(--wp--preset--color--neutral); margin-bottom: 8px;">Subtitle Text</div>
                            <div style="font-size: var(--wp--preset--font-size--medium); color: var(--wp--preset--color--neutral); line-height: 1.6;">Body text with proper line height and readable color for optimal user experience.</div>
                        </div>
                        <code>{% include 'elements/text-book.twig' with { 'type': 'title', 'content': 'Your Text' } %}</code>
                    </div>
                </div>
            </div>

            <!-- Design Token Integration -->
            <div class="component-section">
                <h2>🎨 Design Token Integration</h2>
                <p>All components use design tokens from theme.json as the single source of truth.</p>
                
                <div class="token-flow">
                    <div class="token-step">
                        <h4>1. DesignBook Admin</h4>
                        <p>ColorBook, TextBook, ButtonBook</p>
                    </div>
                    <div class="token-arrow">→</div>
                    <div class="token-step">
                        <h4>2. theme.json</h4>
                        <p>CSS Custom Properties</p>
                    </div>
                    <div class="token-arrow">→</div>
                    <div class="token-step">
                        <h4>3. Primitives</h4>
                        <p>color.twig, typography.twig, etc.</p>
                    </div>
                    <div class="token-arrow">→</div>
                    <div class="token-step">
                        <h4>4. Elements & Components</h4>
                        <p>button-book.twig, card-book.twig</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Base Options page
     */
    public function render_base_options() {
        $theme_json = $this->get_theme_json_data();
        $layout_tokens = $theme_json['settings']['custom']['layout'] ?? [];
        
        // Default layout tokens if not set
        $default_layout = [
            'spacing' => [
                'xs' => '4px', 'sm' => '8px', 'md' => '12px', 'lg' => '16px',
                'xl' => '20px', '2xl' => '24px', '3xl' => '32px', '4xl' => '40px',
                '5xl' => '48px', '6xl' => '64px'
            ],
            'borderRadius' => [
                'none' => '0', 'sm' => '4px', 'md' => '6px', 'lg' => '8px',
                'xl' => '12px', '2xl' => '16px', '3xl' => '24px', 'full' => '9999px'
            ],
            'borderWidth' => [
                'none' => '0', 'thin' => '1px', 'medium' => '2px', 'thick' => '3px', 'heavy' => '4px'
            ],
            'shadows' => [
                'none' => 'none',
                'sm' => '0 1px 2px rgba(0, 0, 0, 0.05)',
                'md' => '0 2px 4px rgba(0, 0, 0, 0.1)',
                'lg' => '0 4px 8px rgba(0, 0, 0, 0.15)',
                'xl' => '0 8px 16px rgba(0, 0, 0, 0.2)',
                '2xl' => '0 16px 32px rgba(0, 0, 0, 0.25)',
                'inner' => 'inset 0 2px 4px rgba(0, 0, 0, 0.1)'
            ],
            'sizes' => [
                'xs' => '20px', 'sm' => '24px', 'md' => '32px', 'lg' => '40px',
                'xl' => '48px', '2xl' => '56px', '3xl' => '64px'
            ],
            'backgrounds' => [
                'colors' => [
                    'transparent' => 'transparent',
                    'white' => '#ffffff',
                    'light' => '#f8f9fa',
                    'neutral' => '#e9ecef',
                    'dark' => '#343a40',
                    'darker' => '#212529',
                    'primary' => 'var(--wp--preset--color--primary)',
                    'secondary' => 'var(--wp--preset--color--secondary)',
                    'accent' => 'var(--wp--preset--color--accent)'
                ],
                'overlays' => [
                    'none' => 'none',
                    'light' => 'rgba(255, 255, 255, 0.1)',
                    'medium' => 'rgba(255, 255, 255, 0.2)',
                    'heavy' => 'rgba(255, 255, 255, 0.4)',
                    'dark-light' => 'rgba(0, 0, 0, 0.1)',
                    'dark-medium' => 'rgba(0, 0, 0, 0.3)',
                    'dark-heavy' => 'rgba(0, 0, 0, 0.5)',
                    'dark-intense' => 'rgba(0, 0, 0, 0.7)'
                ]
            ]
        ];
        
        $layout_tokens = array_merge($default_layout, $layout_tokens);
        ?>
        <div class="wrap villa-base-options">
            <h1>⚙️ Base Options</h1>
            <p class="description">Manage foundational design tokens that all components share: spacing, border radius, shadows, and sizes.</p>
            
            <div class="base-options-container">
                <div class="base-options-tabs">
                    <button class="base-tab active" data-tab="spacing">Spacing</button>
                    <button class="base-tab" data-tab="border-radius">Border Radius</button>
                    <button class="base-tab" data-tab="border-width">Border Width</button>
                    <button class="base-tab" data-tab="shadows">Shadows</button>
                    <button class="base-tab" data-tab="sizes">Sizes</button>
                    <button class="base-tab" data-tab="backgrounds">Backgrounds</button>
                </div>
                
                <!-- Spacing Tab -->
                <div id="spacing" class="base-tab-content active">
                    <h2>Spacing Scale</h2>
                    <p>Define the spacing scale used for padding, margins, and gaps throughout your design system.</p>
                    <div class="token-grid">
                        <?php foreach ($layout_tokens['spacing'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="spacing-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="spacing-<?php echo $key; ?>" class="layout-control" 
                                       data-category="spacing" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., 12px">
                                <div class="token-preview spacing-preview" style="width: <?php echo $value; ?>; height: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Border Radius Tab -->
                <div id="border-radius" class="base-tab-content">
                    <h2>Border Radius Scale</h2>
                    <p>Define border radius values for consistent rounded corners across components.</p>
                    <div class="token-grid">
                        <?php foreach ($layout_tokens['borderRadius'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="border-radius-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="border-radius-<?php echo $key; ?>" class="layout-control" 
                                       data-category="borderRadius" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., 6px">
                                <div class="token-preview radius-preview" style="border-radius: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Border Width Tab -->
                <div id="border-width" class="base-tab-content">
                    <h2>Border Width Scale</h2>
                    <p>Define border width values for consistent stroke weights across components.</p>
                    <div class="token-grid">
                        <?php foreach ($layout_tokens['borderWidth'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="border-width-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="border-width-<?php echo $key; ?>" class="layout-control" 
                                       data-category="borderWidth" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., 2px">
                                <div class="token-preview border-preview" style="border: <?php echo $value; ?> solid #333"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Shadows Tab -->
                <div id="shadows" class="base-tab-content">
                    <h2>Shadow Scale</h2>
                    <p>Define box shadow values for consistent elevation and depth across components.</p>
                    <div class="token-grid">
                        <?php foreach ($layout_tokens['shadows'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="shadows-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="shadows-<?php echo $key; ?>" class="layout-control" 
                                       data-category="shadows" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., 0 2px 4px rgba(0,0,0,0.1)">
                                <div class="token-preview shadow-preview" style="box-shadow: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Sizes Tab -->
                <div id="sizes" class="base-tab-content">
                    <h2>Component Sizes</h2>
                    <p>Define size values for consistent component dimensions and heights.</p>
                    <div class="token-grid">
                        <?php foreach ($layout_tokens['sizes'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="sizes-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="sizes-<?php echo $key; ?>" class="layout-control" 
                                       data-category="sizes" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., 32px">
                                <div class="token-preview size-preview" style="width: <?php echo $value; ?>; height: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Backgrounds Tab -->
                <div id="backgrounds" class="base-tab-content">
                    <h2>Backgrounds</h2>
                    <p>Define background styles for components.</p>
                    <div class="token-grid">
                        <h3>Colors</h3>
                        <?php foreach ($layout_tokens['backgrounds']['colors'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="background-color-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="background-color-<?php echo $key; ?>" class="layout-control" 
                                       data-category="backgrounds" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., #ffffff">
                                <div class="token-preview color-preview" style="background-color: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                        
                        <h3>Overlays</h3>
                        <?php foreach ($layout_tokens['backgrounds']['overlays'] as $key => $value): ?>
                            <div class="token-item">
                                <label for="background-overlay-<?php echo $key; ?>"><?php echo strtoupper($key); ?></label>
                                <input type="text" id="background-overlay-<?php echo $key; ?>" class="layout-control" 
                                       data-category="backgrounds" data-key="<?php echo $key; ?>" 
                                       value="<?php echo esc_attr($value); ?>" placeholder="e.g., rgba(255, 255, 255, 0.1)">
                                <div class="token-preview overlay-preview" style="background-color: <?php echo $value; ?>"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="base-actions">
                    <button type="button" id="save-layout-tokens" class="button button-primary">Save Layout Tokens</button>
                    <button type="button" id="reset-layout-tokens" class="button">Reset to Defaults</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Typography Book page (Primitives)
     */
    public function render_typography_book() {
        $theme_json = $this->get_theme_json_data();
        $base_styles = villa_get_base_styles();
        
        // Get typography settings from theme.json
        $font_sizes = $theme_json['settings']['typography']['fontSizes'] ?? [];
        $font_families = $theme_json['settings']['typography']['fontFamilies'] ?? [];
        
        ?>
        <div class="wrap villa-typography-book">
            <h1>📝 Typography Book</h1>
            <p class="description">Core typography primitives - font sizes, weights, line heights, and letter spacing tokens.</p>
            
            <div class="typography-primitives-container">
                
                <!-- Font Sizes Section -->
                <div class="primitive-section">
                    <h2>📏 Font Sizes</h2>
                    <p class="section-description">Base font size tokens used throughout the design system.</p>
                    
                    <div class="font-sizes-grid">
                        <?php 
                        $default_sizes = [
                            'xs' => ['name' => 'Extra Small', 'size' => '0.75rem', 'pixels' => '12px'],
                            'sm' => ['name' => 'Small', 'size' => '0.875rem', 'pixels' => '14px'],
                            'base' => ['name' => 'Base', 'size' => '1rem', 'pixels' => '16px'],
                            'lg' => ['name' => 'Large', 'size' => '1.125rem', 'pixels' => '18px'],
                            'xl' => ['name' => 'Extra Large', 'size' => '1.25rem', 'pixels' => '20px'],
                            '2xl' => ['name' => '2X Large', 'size' => '1.5rem', 'pixels' => '24px'],
                            '3xl' => ['name' => '3X Large', 'size' => '1.875rem', 'pixels' => '30px'],
                            '4xl' => ['name' => '4X Large', 'size' => '2.25rem', 'pixels' => '36px'],
                            '5xl' => ['name' => '5X Large', 'size' => '3rem', 'pixels' => '48px'],
                            '6xl' => ['name' => '6X Large', 'size' => '3.75rem', 'pixels' => '60px']
                        ];
                        
                        foreach ($default_sizes as $size_key => $size_data): ?>
                            <div class="font-size-card">
                                <div class="size-info">
                                    <h4><?php echo $size_data['name']; ?></h4>
                                    <span class="size-value"><?php echo $size_data['size']; ?> / <?php echo $size_data['pixels']; ?></span>
                                </div>
                                <div class="size-preview" style="font-size: <?php echo $size_data['size']; ?>;">
                                    Text
                                </div>
                                <div class="size-token">
                                    <code>--wp--preset--font-size--<?php echo $size_key; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Font Weights Section -->
                <div class="primitive-section">
                    <h2>💪 Font Weights</h2>
                    <p class="section-description">Font weight tokens for typography hierarchy.</p>
                    
                    <div class="font-weights-grid">
                        <?php 
                        $font_weights = [
                            'thin' => ['name' => 'Thin', 'weight' => '100'],
                            'light' => ['name' => 'Light', 'weight' => '300'],
                            'normal' => ['name' => 'Normal', 'weight' => '400'],
                            'medium' => ['name' => 'Medium', 'weight' => '500'],
                            'semibold' => ['name' => 'Semibold', 'weight' => '600'],
                            'bold' => ['name' => 'Bold', 'weight' => '700'],
                            'extrabold' => ['name' => 'Extra Bold', 'weight' => '800'],
                            'black' => ['name' => 'Black', 'weight' => '900']
                        ];
                        
                        foreach ($font_weights as $weight_key => $weight_data): ?>
                            <div class="font-weight-card">
                                <div class="weight-info">
                                    <h4><?php echo $weight_data['name']; ?></h4>
                                    <span class="weight-value"><?php echo $weight_data['weight']; ?></span>
                                </div>
                                <div class="weight-preview" style="font-weight: <?php echo $weight_data['weight']; ?>;">
                                    Typography Sample
                                </div>
                                <div class="weight-token">
                                    <code>font-weight: <?php echo $weight_data['weight']; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Line Heights Section -->
                <div class="primitive-section">
                    <h2>📐 Line Heights</h2>
                    <p class="section-description">Line height tokens for text readability and spacing.</p>
                    
                    <div class="line-heights-grid">
                        <?php 
                        $line_heights = [
                            'tight' => ['name' => 'Tight', 'value' => '1.1'],
                            'snug' => ['name' => 'Snug', 'value' => '1.2'],
                            'normal' => ['name' => 'Normal', 'value' => '1.4'],
                            'relaxed' => ['name' => 'Relaxed', 'value' => '1.6'],
                            'loose' => ['name' => 'Loose', 'value' => '1.8']
                        ];
                        
                        foreach ($line_heights as $height_key => $height_data): ?>
                            <div class="line-height-card">
                                <div class="height-info">
                                    <h4><?php echo $height_data['name']; ?></h4>
                                    <span class="height-value"><?php echo $height_data['value']; ?></span>
                                </div>
                                <div class="height-preview" style="line-height: <?php echo $height_data['value']; ?>;">
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                </div>
                                <div class="height-token">
                                    <code>line-height: <?php echo $height_data['value']; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Letter Spacing Section -->
                <div class="primitive-section">
                    <h2>🔤 Letter Spacing</h2>
                    <p class="section-description">Letter spacing tokens for typography fine-tuning.</p>
                    
                    <div class="letter-spacing-grid">
                        <?php 
                        $letter_spacings = [
                            'tighter' => ['name' => 'Tighter', 'value' => '-0.05em'],
                            'tight' => ['name' => 'Tight', 'value' => '-0.025em'],
                            'normal' => ['name' => 'Normal', 'value' => '0'],
                            'wide' => ['name' => 'Wide', 'value' => '0.025em'],
                            'wider' => ['name' => 'Wider', 'value' => '0.05em'],
                            'widest' => ['name' => 'Widest', 'value' => '0.1em']
                        ];
                        
                        foreach ($letter_spacings as $spacing_key => $spacing_data): ?>
                            <div class="letter-spacing-card">
                                <div class="spacing-info">
                                    <h4><?php echo $spacing_data['name']; ?></h4>
                                    <span class="spacing-value"><?php echo $spacing_data['value']; ?></span>
                                </div>
                                <div class="spacing-preview" style="letter-spacing: <?php echo $spacing_data['value']; ?>;">
                                    TYPOGRAPHY SAMPLE
                                </div>
                                <div class="spacing-token">
                                    <code>letter-spacing: <?php echo $spacing_data['value']; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </div>
            
            <div class="typography-actions">
                <button type="button" id="save-typography" class="button button-primary">💾 Save Typography Tokens</button>
                <button type="button" id="reset-typography" class="button">🔄 Reset to Defaults</button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Layout Book page (Primitives)
     */
    public function render_layout_book() {
        $theme_json = $this->get_theme_json_data();
        
        ?>
        <div class="wrap villa-layout-book">
            <h1>📐 Layout Book</h1>
            <p class="description">Core layout primitives - spacing, border radius, shadows, and grid layout tokens.</p>
            
            <div class="layout-primitives-container">
                
                <!-- Spacing Section -->
                <div class="primitive-section">
                    <h2>📏 Spacing</h2>
                    <p class="section-description">Spacing tokens for consistent margins, padding, and gaps.</p>
                    
                    <div class="spacing-grid">
                        <?php 
                        $spacing_tokens = [
                            'xs' => ['name' => 'Extra Small', 'value' => '0.25rem', 'pixels' => '4px'],
                            'sm' => ['name' => 'Small', 'value' => '0.5rem', 'pixels' => '8px'],
                            'md' => ['name' => 'Medium', 'value' => '1rem', 'pixels' => '16px'],
                            'lg' => ['name' => 'Large', 'value' => '1.5rem', 'pixels' => '24px'],
                            'xl' => ['name' => 'Extra Large', 'value' => '2rem', 'pixels' => '32px'],
                            '2xl' => ['name' => '2X Large', 'value' => '3rem', 'pixels' => '48px'],
                            '3xl' => ['name' => '3X Large', 'value' => '4rem', 'pixels' => '64px'],
                            '4xl' => ['name' => '4X Large', 'value' => '6rem', 'pixels' => '96px'],
                            '5xl' => ['name' => '5X Large', 'value' => '8rem', 'pixels' => '128px']
                        ];
                        
                        foreach ($spacing_tokens as $spacing_key => $spacing_data): ?>
                            <div class="spacing-card">
                                <div class="spacing-info">
                                    <h4><?php echo $spacing_data['name']; ?> (<?php echo $spacing_key; ?>)</h4>
                                    <span class="spacing-value"><?php echo $spacing_data['value']; ?> / <?php echo $spacing_data['pixels']; ?></span>
                                </div>
                                <div class="spacing-preview">
                                    <div class="spacing-demo" style="padding: <?php echo $spacing_data['value']; ?>; background: #e3f2fd; border: 1px solid #2196f3;">
                                        Padding: <?php echo $spacing_data['value']; ?>
                                    </div>
                                </div>
                                <div class="spacing-token">
                                    <code>--wp--custom--layout--spacing--<?php echo $spacing_key; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Border Radius Section -->
                <div class="primitive-section">
                    <h2>🔄 Border Radius</h2>
                    <p class="section-description">Border radius tokens for consistent rounded corners.</p>
                    
                    <div class="border-radius-grid">
                        <?php 
                        $radius_tokens = [
                            'none' => ['name' => 'None', 'value' => '0'],
                            'sm' => ['name' => 'Small', 'value' => '0.125rem'],
                            'md' => ['name' => 'Medium', 'value' => '0.375rem'],
                            'lg' => ['name' => 'Large', 'value' => '0.5rem'],
                            'xl' => ['name' => 'Extra Large', 'value' => '0.75rem'],
                            '2xl' => ['name' => '2X Large', 'value' => '1rem'],
                            '3xl' => ['name' => '3X Large', 'value' => '1.5rem'],
                            'full' => ['name' => 'Full', 'value' => '9999px']
                        ];
                        
                        foreach ($radius_tokens as $radius_key => $radius_data): ?>
                            <div class="radius-card">
                                <div class="radius-info">
                                    <h4><?php echo $radius_data['name']; ?> (<?php echo $radius_key; ?>)</h4>
                                    <span class="radius-value"><?php echo $radius_data['value']; ?></span>
                                </div>
                                <div class="radius-preview">
                                    <div class="radius-demo" style="border-radius: <?php echo $radius_data['value']; ?>; background: #f3e5f5; border: 1px solid #9c27b0; padding: 1rem; width: 80px; height: 60px;">
                                    </div>
                                </div>
                                <div class="radius-token">
                                    <code>--wp--custom--layout--border-radius--<?php echo $radius_key; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Shadows Section -->
                <div class="primitive-section">
                    <h2>🌫️ Shadows</h2>
                    <p class="section-description">Shadow tokens for depth and elevation.</p>
                    
                    <div class="shadows-grid">
                        <?php 
                        $shadow_tokens = [
                            'none' => ['name' => 'None', 'value' => 'none'],
                            'sm' => ['name' => 'Small', 'value' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)'],
                            'md' => ['name' => 'Medium', 'value' => '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)'],
                            'lg' => ['name' => 'Large', 'value' => '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)'],
                            'xl' => ['name' => 'Extra Large', 'value' => '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)'],
                            '2xl' => ['name' => '2X Large', 'value' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)'],
                            'inner' => ['name' => 'Inner', 'value' => 'inset 0 2px 4px 0 rgba(0, 0, 0, 0.1)']
                        ];
                        
                        foreach ($shadow_tokens as $shadow_key => $shadow_data): ?>
                            <div class="shadow-card">
                                <div class="shadow-info">
                                    <h4><?php echo $shadow_data['name']; ?> (<?php echo $shadow_key; ?>)</h4>
                                </div>
                                <div class="shadow-preview">
                                    <div class="shadow-demo" style="box-shadow: <?php echo $shadow_data['value']; ?>; background: white; padding: 1.5rem; border-radius: 0.5rem; width: 120px; height: 80px; display: flex; align-items: center; justify-content: center;">
                                        Shadow Demo
                                    </div>
                                </div>
                                <div class="shadow-token">
                                    <code>--wp--custom--layout--shadows--<?php echo $shadow_key; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Grid & Flex Section -->
                <div class="primitive-section">
                    <h2>📊 Grid & Flex</h2>
                    <p class="section-description">Layout system tokens for grid and flexbox layouts.</p>
                    
                    <div class="layout-system-grid">
                        <?php 
                        $layout_tokens = [
                            'grid-cols-1' => ['name' => '1 Column', 'value' => 'repeat(1, minmax(0, 1fr))'],
                            'grid-cols-2' => ['name' => '2 Columns', 'value' => 'repeat(2, minmax(0, 1fr))'],
                            'grid-cols-3' => ['name' => '3 Columns', 'value' => 'repeat(3, minmax(0, 1fr))'],
                            'grid-cols-4' => ['name' => '4 Columns', 'value' => 'repeat(4, minmax(0, 1fr))'],
                            'grid-cols-6' => ['name' => '6 Columns', 'value' => 'repeat(6, minmax(0, 1fr))'],
                            'grid-cols-12' => ['name' => '12 Columns', 'value' => 'repeat(12, minmax(0, 1fr))']
                        ];
                        
                        foreach ($layout_tokens as $layout_key => $layout_data): ?>
                            <div class="layout-card">
                                <div class="layout-info">
                                    <h4><?php echo $layout_data['name']; ?></h4>
                                </div>
                                <div class="layout-preview">
                                    <div class="grid-demo" style="display: grid; grid-template-columns: <?php echo $layout_data['value']; ?>; gap: 0.25rem; height: 60px;">
                                        <?php 
                                        $col_count = (int) str_replace(['grid-cols-'], '', $layout_key);
                                        for ($i = 0; $i < $col_count; $i++): ?>
                                            <div style="background: #e8f5e8; border: 1px solid #4caf50; border-radius: 2px;"></div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="layout-token">
                                    <code>--wp--custom--layout--<?php echo $layout_key; ?></code>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </div>
            
            <div class="layout-actions">
                <button type="button" id="save-layout" class="button button-primary">💾 Save Layout Tokens</button>
                <button type="button" id="reset-layout" class="button">🔄 Reset to Defaults</button>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Card Book page (Components)
     */
    public function render_card_book() {
        ?>
        <div class="wrap villa-card-book">
            <h1>🧩 Card Book</h1>
            <p class="description">Design and customize card components using elements and primitives from the design system.</p>
            
            <div class="card-book-container">
                <div class="card-book-editor">
                    
                    <!-- Card Variants Section -->
                    <div class="card-variants-section">
                        <h2>🎨 Card Variants</h2>
                        <p class="section-description">Different card styles for various use cases.</p>
                        
                        <div class="variant-tabs">
                            <button class="variant-tab active" data-variant="basic">Basic Card</button>
                            <button class="variant-tab" data-variant="feature">Feature Card</button>
                            <button class="variant-tab" data-variant="testimonial">Testimonial Card</button>
                            <button class="variant-tab" data-variant="product">Product Card</button>
                        </div>
                        
                        <!-- Basic Card Variant -->
                        <div id="basic-card" class="variant-content active">
                            <div class="variant-controls">
                                <h3>Basic Card Settings</h3>
                                
                                <div class="control-group">
                                    <label for="basic-title">Title</label>
                                    <input type="text" id="basic-title" class="variant-control" value="Card Title" placeholder="Enter card title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="basic-description">Description</label>
                                    <textarea id="basic-description" class="variant-control" placeholder="Enter card description">This is a sample card description that explains the content.</textarea>
                                </div>
                                
                                <div class="control-group">
                                    <label for="basic-shadow">Shadow</label>
                                    <select id="basic-shadow" class="variant-control">
                                        <option value="none">None</option>
                                        <option value="sm">Small</option>
                                        <option value="md" selected>Medium</option>
                                        <option value="lg">Large</option>
                                        <option value="xl">Extra Large</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="basic-radius">Border Radius</label>
                                    <select id="basic-radius" class="variant-control">
                                        <option value="none">None</option>
                                        <option value="sm">Small</option>
                                        <option value="md" selected>Medium</option>
                                        <option value="lg">Large</option>
                                        <option value="xl">Extra Large</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="basic-padding">Padding</label>
                                    <select id="basic-padding" class="variant-control">
                                        <option value="sm">Small</option>
                                        <option value="md" selected>Medium</option>
                                        <option value="lg">Large</option>
                                        <option value="xl">Extra Large</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="card-preview-container">
                                    <div class="villa-card basic-card" id="basic-card-preview">
                                        <h3 class="card-title">Card Title</h3>
                                        <p class="card-description">This is a sample card description that explains the content.</p>
                                        <button class="villa-button primary">Learn More</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Feature Card Variant -->
                        <div id="feature-card" class="variant-content">
                            <div class="variant-controls">
                                <h3>Feature Card Settings</h3>
                                
                                <div class="control-group">
                                    <label for="feature-icon">Icon</label>
                                    <select id="feature-icon" class="variant-control">
                                        <option value="🎨">🎨 Design</option>
                                        <option value="⚡">⚡ Performance</option>
                                        <option value="🔒">🔒 Security</option>
                                        <option value="📱">📱 Mobile</option>
                                        <option value="🚀">🚀 Launch</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="feature-title">Title</label>
                                    <input type="text" id="feature-title" class="variant-control" value="Feature Title" placeholder="Enter feature title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="feature-description">Description</label>
                                    <textarea id="feature-description" class="variant-control" placeholder="Enter feature description">Describe the key benefits and features of this item.</textarea>
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="card-preview-container">
                                    <div class="villa-card feature-card" id="feature-card-preview">
                                        <div class="card-icon">🎨</div>
                                        <h3 class="card-title">Feature Title</h3>
                                        <p class="card-description">Describe the key benefits and features of this item.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Testimonial Card Variant -->
                        <div id="testimonial-card" class="variant-content">
                            <div class="variant-controls">
                                <h3>Testimonial Card Settings</h3>
                                
                                <div class="control-group">
                                    <label for="testimonial-quote">Quote</label>
                                    <textarea id="testimonial-quote" class="variant-control" placeholder="Enter testimonial quote">"This product has transformed our business completely. Highly recommended!"</textarea>
                                </div>
                                
                                <div class="control-group">
                                    <label for="testimonial-author">Author</label>
                                    <input type="text" id="testimonial-author" class="variant-control" value="John Smith" placeholder="Enter author name">
                                </div>
                                
                                <div class="control-group">
                                    <label for="testimonial-role">Role/Company</label>
                                    <input type="text" id="testimonial-role" class="variant-control" value="CEO, Tech Company" placeholder="Enter role and company">
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="card-preview-container">
                                    <div class="villa-card testimonial-card" id="testimonial-card-preview">
                                        <blockquote class="testimonial-quote">"This product has transformed our business completely. Highly recommended!"</blockquote>
                                        <div class="testimonial-author">
                                            <strong>John Smith</strong>
                                            <span class="author-role">CEO, Tech Company</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Product Card Variant -->
                        <div id="product-card" class="variant-content">
                            <div class="variant-controls">
                                <h3>Product Card Settings</h3>
                                
                                <div class="control-group">
                                    <label for="product-name">Product Name</label>
                                    <input type="text" id="product-name" class="variant-control" value="Premium Package" placeholder="Enter product name">
                                </div>
                                
                                <div class="control-group">
                                    <label for="product-price">Price</label>
                                    <input type="text" id="product-price" class="variant-control" value="$99" placeholder="Enter price">
                                </div>
                                
                                <div class="control-group">
                                    <label for="product-features">Features (one per line)</label>
                                    <textarea id="product-features" class="variant-control" placeholder="Enter features">Feature 1
Feature 2
Feature 3</textarea>
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="card-preview-container">
                                    <div class="villa-card product-card" id="product-card-preview">
                                        <h3 class="product-name">Premium Package</h3>
                                        <div class="product-price">$99</div>
                                        <ul class="product-features">
                                            <li>Feature 1</li>
                                            <li>Feature 2</li>
                                            <li>Feature 3</li>
                                        </ul>
                                        <button class="villa-button primary">Choose Plan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Design Token Usage -->
                    <div class="token-usage-section">
                        <h2>🔧 Design Token Usage</h2>
                        <p class="section-description">Cards use primitives and elements from the design system:</p>
                        
                        <div class="token-usage-grid">
                            <div class="token-category">
                                <h4>🔧 Primitives Used</h4>
                                <ul>
                                    <li><code>--wp--custom--layout--spacing--*</code> - Padding & margins</li>
                                    <li><code>--wp--custom--layout--border-radius--*</code> - Rounded corners</li>
                                    <li><code>--wp--custom--layout--shadows--*</code> - Card elevation</li>
                                    <li><code>--wp--preset--color--*</code> - Background & text colors</li>
                                    <li><code>--wp--preset--font-size--*</code> - Typography sizes</li>
                                </ul>
                            </div>
                            
                            <div class="token-category">
                                <h4>🧱 Elements Used</h4>
                                <ul>
                                    <li><code>.villa-button</code> - Button element</li>
                                    <li><code>.text-book-*</code> - Text elements (title, description)</li>
                                    <li><code>.icon-*</code> - Icon elements</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="card-actions">
                    <button type="button" id="save-card" class="button button-primary">💾 Save Card Components</button>
                    <button type="button" id="reset-card" class="button">🔄 Reset to Defaults</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render Hero Book page (Sections)
     */
    public function render_hero_book() {
        ?>
        <div class="wrap villa-hero-book">
            <h1>🏗️ Hero Book</h1>
            <p class="description">Design and customize hero sections using components, elements, and primitives from the design system.</p>
            
            <div class="hero-book-container">
                <div class="hero-book-editor">
                    
                    <!-- Hero Variants Section -->
                    <div class="hero-variants-section">
                        <h2>🎭 Hero Variants</h2>
                        <p class="section-description">Different hero section layouts for various page types.</p>
                        
                        <div class="variant-tabs">
                            <button class="variant-tab active" data-variant="centered">Centered Hero</button>
                            <button class="variant-tab" data-variant="split">Split Hero</button>
                            <button class="variant-tab" data-variant="minimal">Minimal Hero</button>
                            <button class="variant-tab" data-variant="video">Video Hero</button>
                        </div>
                        
                        <!-- Centered Hero Variant -->
                        <div id="centered-hero" class="variant-content active">
                            <div class="variant-controls">
                                <h3>Centered Hero Settings</h3>
                                
                                <div class="control-group">
                                    <label for="centered-pretitle">Pretitle</label>
                                    <input type="text" id="centered-pretitle" class="variant-control" value="VILLA COMMUNITY" placeholder="Enter pretitle">
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-title">Main Title</label>
                                    <input type="text" id="centered-title" class="variant-control" value="Beautiful Design System" placeholder="Enter main title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-subtitle">Subtitle</label>
                                    <input type="text" id="centered-subtitle" class="variant-control" value="Crafted for modern web experiences" placeholder="Enter subtitle">
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-description">Description</label>
                                    <textarea id="centered-description" class="variant-control" placeholder="Enter description">Build stunning websites with our comprehensive design system that combines beautiful aesthetics with powerful functionality.</textarea>
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-cta-primary">Primary CTA Text</label>
                                    <input type="text" id="centered-cta-primary" class="variant-control" value="Get Started" placeholder="Primary button text">
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-cta-secondary">Secondary CTA Text</label>
                                    <input type="text" id="centered-cta-secondary" class="variant-control" value="Learn More" placeholder="Secondary button text">
                                </div>
                                
                                <div class="control-group">
                                    <label for="centered-background">Background Style</label>
                                    <select id="centered-background" class="variant-control">
                                        <option value="gradient">Gradient</option>
                                        <option value="solid">Solid Color</option>
                                        <option value="image">Background Image</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="hero-preview-container">
                                    <div class="villa-hero centered-hero" id="centered-hero-preview">
                                        <div class="hero-content">
                                            <span class="hero-pretitle">VILLA COMMUNITY</span>
                                            <h1 class="hero-title">Beautiful Design System</h1>
                                            <h2 class="hero-subtitle">Crafted for modern web experiences</h2>
                                            <p class="hero-description">Build stunning websites with our comprehensive design system that combines beautiful aesthetics with powerful functionality.</p>
                                            <div class="hero-actions">
                                                <button class="villa-button primary large">Get Started</button>
                                                <button class="villa-button secondary large">Learn More</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Split Hero Variant -->
                        <div id="split-hero" class="variant-content">
                            <div class="variant-controls">
                                <h3>Split Hero Settings</h3>
                                
                                <div class="control-group">
                                    <label for="split-title">Title</label>
                                    <input type="text" id="split-title" class="variant-control" value="Transform Your Business" placeholder="Enter title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="split-description">Description</label>
                                    <textarea id="split-description" class="variant-control" placeholder="Enter description">Discover how our innovative solutions can help you achieve your goals and grow your business to new heights.</textarea>
                                </div>
                                
                                <div class="control-group">
                                    <label for="split-image-side">Image Side</label>
                                    <select id="split-image-side" class="variant-control">
                                        <option value="right">Right</option>
                                        <option value="left">Left</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="split-cta">CTA Text</label>
                                    <input type="text" id="split-cta" class="variant-control" value="Start Today" placeholder="Button text">
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="hero-preview-container">
                                    <div class="villa-hero split-hero" id="split-hero-preview">
                                        <div class="hero-content">
                                            <h1 class="hero-title">Transform Your Business</h1>
                                            <p class="hero-description">Discover how our innovative solutions can help you achieve your goals and grow your business to new heights.</p>
                                            <button class="villa-button primary large">Start Today</button>
                                        </div>
                                        <div class="hero-image">
                                            <div class="image-placeholder">📊 Image Placeholder</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Minimal Hero Variant -->
                        <div id="minimal-hero" class="variant-content">
                            <div class="variant-controls">
                                <h3>Minimal Hero Settings</h3>
                                
                                <div class="control-group">
                                    <label for="minimal-title">Title</label>
                                    <input type="text" id="minimal-title" class="variant-control" value="Simple. Elegant. Effective." placeholder="Enter title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="minimal-subtitle">Subtitle</label>
                                    <input type="text" id="minimal-subtitle" class="variant-control" value="Less is more" placeholder="Enter subtitle">
                                </div>
                                
                                <div class="control-group">
                                    <label for="minimal-alignment">Text Alignment</label>
                                    <select id="minimal-alignment" class="variant-control">
                                        <option value="center">Center</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="hero-preview-container">
                                    <div class="villa-hero minimal-hero" id="minimal-hero-preview">
                                        <div class="hero-content">
                                            <h1 class="hero-title">Simple. Elegant. Effective.</h1>
                                            <p class="hero-subtitle">Less is more</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Video Hero Variant -->
                        <div id="video-hero" class="variant-content">
                            <div class="variant-controls">
                                <h3>Video Hero Settings</h3>
                                
                                <div class="control-group">
                                    <label for="video-title">Title</label>
                                    <input type="text" id="video-title" class="variant-control" value="Experience Innovation" placeholder="Enter title">
                                </div>
                                
                                <div class="control-group">
                                    <label for="video-description">Description</label>
                                    <textarea id="video-description" class="variant-control" placeholder="Enter description">Watch how our technology transforms the way you work and live.</textarea>
                                </div>
                                
                                <div class="control-group">
                                    <label for="video-overlay">Overlay Opacity</label>
                                    <select id="video-overlay" class="variant-control">
                                        <option value="light">Light (30%)</option>
                                        <option value="medium" selected>Medium (50%)</option>
                                        <option value="dark">Dark (70%)</option>
                                    </select>
                                </div>
                                
                                <div class="control-group">
                                    <label for="video-cta">CTA Text</label>
                                    <input type="text" id="video-cta" class="variant-control" value="Watch Demo" placeholder="Button text">
                                </div>
                            </div>
                            
                            <div class="variant-preview">
                                <h4>Live Preview</h4>
                                <div class="hero-preview-container">
                                    <div class="villa-hero video-hero" id="video-hero-preview">
                                        <div class="video-background">
                                            <div class="video-placeholder">🎥 Video Background</div>
                                            <div class="video-overlay"></div>
                                        </div>
                                        <div class="hero-content">
                                            <h1 class="hero-title">Experience Innovation</h1>
                                            <p class="hero-description">Watch how our technology transforms the way you work and live.</p>
                                            <button class="villa-button primary large">Watch Demo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Architecture Breakdown -->
                    <div class="architecture-breakdown-section">
                        <h2>🏗️ Architecture Breakdown</h2>
                        <p class="section-description">Hero sections demonstrate the full design system hierarchy:</p>
                        
                        <div class="architecture-breakdown-grid">
                            <div class="architecture-level">
                                <h4>🏗️ Sections (This Level)</h4>
                                <p>Large layout compositions combining multiple components</p>
                                <ul>
                                    <li><code>.villa-hero</code> - Hero section container</li>
                                    <li><code>.hero-content</code> - Content area layout</li>
                                    <li><code>.hero-actions</code> - Button group layout</li>
                                </ul>
                            </div>
                            
                            <div class="architecture-level">
                                <h4>🧩 Components Used</h4>
                                <p>Complex UI parts composed of elements and primitives</p>
                                <ul>
                                    <li><code>.villa-card</code> - Card components (if used)</li>
                                    <li><code>.content-block</code> - Content groupings</li>
                                    <li><code>.media-object</code> - Image/text combinations</li>
                                </ul>
                            </div>
                            
                            <div class="architecture-level">
                                <h4>🧱 Elements Used</h4>
                                <p>Reusable UI building blocks with variants</p>
                                <ul>
                                    <li><code>.villa-button</code> - Button elements</li>
                                    <li><code>.hero-title</code> - Title text elements</li>
                                    <li><code>.hero-description</code> - Description text elements</li>
                                </ul>
                            </div>
                            
                            <div class="architecture-level">
                                <h4>🔧 Primitives Used</h4>
                                <p>Core design tokens from theme.json</p>
                                <ul>
                                    <li><code>--wp--preset--color--*</code> - Color tokens</li>
                                    <li><code>--wp--preset--font-size--*</code> - Typography tokens</li>
                                    <li><code>--wp--custom--layout--spacing--*</code> - Spacing tokens</li>
                                    <li><code>--wp--custom--layout--shadows--*</code> - Shadow tokens</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="hero-actions">
                    <button type="button" id="save-hero" class="button button-primary">💾 Save Hero Sections</button>
                    <button type="button" id="reset-hero" class="button">🔄 Reset to Defaults</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX handlers
     */
    public function save_design_tokens() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $tokens = $_POST['tokens'] ?? array();
        
        // Update theme.json
        $theme_data = $this->get_theme_json_data();
        
        // Update colors if provided
        if (isset($tokens['colors'])) {
            $theme_data['settings']['color']['palette'] = $tokens['colors'];
        }
        
        // Save to theme.json
        $result = $this->save_theme_json_data($theme_data);
        
        if ($result) {
            wp_send_json_success('Design tokens saved successfully');
        } else {
            wp_send_json_error('Failed to save design tokens');
        }
    }
    
    public function export_design_tokens() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $theme_data = $this->get_theme_json_data();
        wp_send_json_success($theme_data);
    }
    
    public function import_design_tokens() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $tokens = json_decode(stripslashes($_POST['tokens']), true);
        
        if ($tokens) {
            $result = $this->save_theme_json_data($tokens);
            
            if ($result) {
                wp_send_json_success('Design tokens imported successfully');
            } else {
                wp_send_json_error('Failed to import design tokens');
            }
        } else {
            wp_send_json_error('Invalid JSON data');
        }
    }
    
    public function save_colors() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $colors = json_decode(stripslashes($_POST['colors']), true);
        $cmyk_data = json_decode(stripslashes($_POST['cmyk_data'] ?? '[]'), true);
        $hsla_data = json_decode(stripslashes($_POST['hsla_data'] ?? '[]'), true);
        
        if (!$colors) {
            wp_send_json_error('Invalid color data');
        }
        
        // Save CMYK data to WordPress options for each color
        if ($cmyk_data) {
            foreach ($cmyk_data as $slug => $cmyk) {
                update_option('villa_color_cmyk_' . $slug, $cmyk);
            }
        }
        
        // Save HSLA data to WordPress options for each color
        if ($hsla_data) {
            foreach ($hsla_data as $slug => $hsla) {
                update_option('villa_color_hsla_' . $slug, $hsla);
            }
        }
        
        // Update theme.json file
        $theme_json_path = get_template_directory() . '/theme.json';
        
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json file not found');
        }
        
        // Read current theme.json
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json format');
        }
        
        // Update colors in theme.json (hex values for WordPress compatibility)
        $theme_json['settings']['color']['palette'] = $colors;
        
        // Save theme.json
        file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        wp_send_json_success('Colors, CMYK, and HSLA data saved successfully');
    }
    
    public function reset_colors() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Default colors
        $defaults = [
            [
                'slug' => 'primary-light',
                'name' => 'Primary Light',
                'color' => '#F7F7F7'
            ],
            [
                'slug' => 'primary',
                'name' => 'Primary',
                'color' => '#1A1A1A'
            ],
            [
                'slug' => 'primary-dark',
                'name' => 'Primary Dark',
                'color' => '#0A0A0A'
            ],
            [
                'slug' => 'secondary-light',
                'name' => 'Secondary Light',
                'color' => '#F2F2F2'
            ],
            [
                'slug' => 'secondary',
                'name' => 'Secondary',
                'color' => '#2D2D2D'
            ],
            [
                'slug' => 'secondary-dark',
                'name' => 'Secondary Dark',
                'color' => '#1A1A1A'
            ],
            [
                'slug' => 'neutral-light',
                'name' => 'Neutral Light',
                'color' => '#F7F7F7'
            ],
            [
                'slug' => 'neutral',
                'name' => 'Neutral',
                'color' => '#E5E5E5'
            ],
            [
                'slug' => 'neutral-dark',
                'name' => 'Neutral Dark',
                'color' => '#D3D3D3'
            ],
            [
                'slug' => 'base-white',
                'name' => 'Base White',
                'color' => '#FFFFFF'
            ],
            [
                'slug' => 'base-lightest',
                'name' => 'Base Lightest',
                'color' => '#F7F7F7'
            ],
            [
                'slug' => 'base-light',
                'name' => 'Base Light',
                'color' => '#F2F2F2'
            ],
            [
                'slug' => 'base',
                'name' => 'Base',
                'color' => '#E5E5E5'
            ],
            [
                'slug' => 'base-dark',
                'name' => 'Base Dark',
                'color' => '#D3D3D3'
            ],
            [
                'slug' => 'base-darkest',
                'name' => 'Base Darkest',
                'color' => '#C0C0C0'
            ],
            [
                'slug' => 'base-black',
                'name' => 'Base Black',
                'color' => '#000000'
            ]
        ];
        
        // Update theme.json file with defaults
        $theme_json_path = get_template_directory() . '/theme.json';
        
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json file not found');
        }
        
        // Read current theme.json
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json format');
        }
        
        // Reset colors in theme.json
        $theme_json['settings']['color']['palette'] = $defaults;
        
        // Write updated theme.json back to file
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to update theme.json file');
        }
        
        wp_send_json_success([
            'message' => 'Colors reset to defaults in theme.json',
            'colors' => $defaults
        ]);
    }
    
    public function save_text_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $styles = [];
        
        // Get all semantic style data from POST
        $semantic_styles = ['pretitle', 'title', 'subtitle', 'description', 'body'];
        
        foreach ($semantic_styles as $style_key) {
            $styles[$style_key] = [];
            
            if (isset($_POST[$style_key . '_html_tag'])) {
                $styles[$style_key]['htmlTag'] = sanitize_text_field($_POST[$style_key . '_html_tag']);
            }
            
            if (isset($_POST[$style_key . '_font_size'])) {
                $styles[$style_key]['fontSize'] = sanitize_text_field($_POST[$style_key . '_font_size']);
            }
            
            if (isset($_POST[$style_key . '_font_weight'])) {
                $styles[$style_key]['fontWeight'] = sanitize_text_field($_POST[$style_key . '_font_weight']);
            }
            
            if (isset($_POST[$style_key . '_font_family'])) {
                $styles[$style_key]['fontFamily'] = sanitize_text_field($_POST[$style_key . '_font_family']);
            }
            
            if (isset($_POST[$style_key . '_text_transform'])) {
                $styles[$style_key]['textTransform'] = sanitize_text_field($_POST[$style_key . '_text_transform']);
            }
            
            if (isset($_POST[$style_key . '_letter_spacing'])) {
                $styles[$style_key]['letterSpacing'] = sanitize_text_field($_POST[$style_key . '_letter_spacing']);
            }
            
            if (isset($_POST[$style_key . '_color'])) {
                $styles[$style_key]['color'] = sanitize_text_field($_POST[$style_key . '_color']);
            }
            
            if (isset($_POST[$style_key . '_line_height'])) {
                $styles[$style_key]['lineHeight'] = sanitize_text_field($_POST[$style_key . '_line_height']);
            }
        }
        
        // Read current theme.json
        $theme_json_path = get_template_directory() . '/theme.json';
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json file not found');
            return;
        }
        
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json file');
            return;
        }
        
        // Update semantic styles in theme.json
        if (!isset($theme_json['settings']['typography']['customTextStyles'])) {
            $theme_json['settings']['typography']['customTextStyles'] = [];
        }
        
        foreach ($styles as $element => $element_styles) {
            if (!isset($theme_json['settings']['typography']['customTextStyles'][$element])) {
                $theme_json['settings']['typography']['customTextStyles'][$element] = [];
            }
            
            // Store all the style properties
            if (isset($element_styles['htmlTag'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['htmlTag'] = $element_styles['htmlTag'];
            }
            
            if (isset($element_styles['fontSize'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['fontSize'] = $element_styles['fontSize'];
            }
            
            if (isset($element_styles['fontWeight'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['fontWeight'] = $element_styles['fontWeight'];
            }
            
            if (isset($element_styles['fontFamily'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['fontFamily'] = $element_styles['fontFamily'];
            }
            
            if (isset($element_styles['textTransform'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['textTransform'] = $element_styles['textTransform'];
            }
            
            if (isset($element_styles['letterSpacing'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['letterSpacing'] = $element_styles['letterSpacing'];
            }
            
            if (isset($element_styles['color'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['color'] = $element_styles['color'];
            }
            
            if (isset($element_styles['lineHeight'])) {
                $theme_json['settings']['typography']['customTextStyles'][$element]['lineHeight'] = $element_styles['lineHeight'];
            }
        }
        
        // Write updated theme.json
        $json_string = wp_json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $json_string) === false) {
            wp_send_json_error('Failed to write theme.json file');
            return;
        }
        
        wp_send_json_success('Semantic text styles saved successfully');
    }
    
    public function reset_text_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Delete custom text styles
        delete_option('villa_text_styles');
        
        // Reset theme.json typography settings
        $theme_json_path = get_template_directory() . '/theme.json';
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
        
        // Remove custom text styles from theme.json
        if (isset($theme_json['settings']['typography']['customTextStyles'])) {
            unset($theme_json['settings']['typography']['customTextStyles']);
        }
        
        // Save theme.json
        file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        wp_send_json_success('Text styles reset successfully');
    }
    
    public static function save_base_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $styles = $_POST['styles'] ?? [];
        
        // Validate and sanitize base styles
        $allowed_properties = [
            'base-font-size'
        ];

        $sanitized_styles = [];
        foreach ($allowed_properties as $property) {
            if (isset($styles[$property])) {
                $sanitized_styles[$property] = sanitize_text_field($styles[$property]);
            }
        }
        
        // Read current theme.json
        $theme_json_path = get_template_directory() . '/theme.json';
        
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json file not found');
            return;
        }
        
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            wp_send_json_error('Invalid theme.json format');
            return;
        }
        
        // Update font sizes in theme.json
        if (isset($theme_json['settings']['typography']['fontSizes'])) {
            // Calculate font sizes based on base font size
            $base_size_value = $sanitized_styles['base-font-size'] ?? '1rem';
            $base_numeric = floatval($base_size_value);
            $unit = preg_replace('/[0-9.]+/', '', $base_size_value);
            
            $font_scale = [
                'small' => 0.8125,
                'medium' => 1.0,
                'large' => 1.25,
                'x-large' => 1.5,
                'xx-large' => 2.0,
                'huge' => 6.25
            ];
            
            foreach ($theme_json['settings']['typography']['fontSizes'] as &$font_size) {
                if (isset($font_scale[$font_size['slug']])) {
                    $calculated_size = $base_numeric * $font_scale[$font_size['slug']];
                    $font_size['size'] = $calculated_size . $unit;
                }
            }
        }
        
        // Write updated theme.json back to file
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to save theme.json');
            return;
        }
        
        wp_send_json_success('Base styles saved successfully');
    }
    
    public function reset_base_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Default base styles
        $defaults = [
            'base-font-size' => '1rem'
        ];
        
        // Update theme.json file with defaults
        $theme_json_path = get_template_directory() . '/theme.json';
        
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json file not found');
        }
        
        // Read current theme.json
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json format');
        }
        
        // Reset font sizes in theme.json
        if (isset($theme_json['settings']['typography']['fontSizes'])) {
            foreach ($theme_json['settings']['typography']['fontSizes'] as &$font_size) {
                switch ($font_size['slug']) {
                    case 'medium':
                        $font_size['size'] = $defaults['base-font-size'];
                        break;
                }
            }
        }
        
        // Write updated theme.json back to file
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to update theme.json file');
        }
        
        wp_send_json_success([
            'message' => 'Base styles reset to defaults in theme.json',
            'base_styles' => $defaults
        ]);
    }
    
    /**
     * Save card component settings
     */
    public function save_card_component() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'villa_design_book_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        // Get and sanitize settings
        $settings_json = stripslashes($_POST['settings']);
        $settings = json_decode($settings_json, true);
        
        if (!$settings) {
            wp_send_json_error('Invalid settings data');
            return;
        }
        
        // Sanitize the settings
        $sanitized_settings = [
            'image' => esc_url_raw($settings['image']),
            'pretitle' => sanitize_text_field($settings['pretitle']),
            'title' => sanitize_text_field($settings['title']),
            'description' => sanitize_textarea_field($settings['description']),
            'borderRadius' => intval($settings['borderRadius']),
            'shadow' => intval($settings['shadow'])
        ];
        
        // Save to WordPress options
        $result = update_option('villa_card_component', $sanitized_settings);
        
        if ($result) {
            wp_send_json_success('Card component saved successfully');
        } else {
            wp_send_json_error('Failed to save card component');
        }
    }
    
    public function save_button_styles() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'villa_design_book_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        // Get and sanitize settings
        $settings_json = stripslashes($_POST['settings']);
        $settings = json_decode($settings_json, true);
        
        if (!$settings) {
            wp_send_json_error('Invalid settings data');
            return;
        }
        
        // Sanitize the settings
        $sanitized_settings = [
            'text' => sanitize_text_field($settings['text']),
            'style' => sanitize_text_field($settings['style']),
            'size' => sanitize_text_field($settings['size']),
            'color' => sanitize_text_field($settings['color'])
        ];
        
        // Save to WordPress options
        $result = update_option('villa_button_component', $sanitized_settings);
        
        if ($result) {
            wp_send_json_success('Button component saved successfully');
        } else {
            wp_send_json_error('Failed to save button component');
        }
    }
    
    public function reset_button_styles() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'villa_design_book_nonce')) {
            wp_die('Security check failed');
        }
        
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
            return;
        }
        
        // Default button styles
        $defaults = [
            'text' => 'Default Button',
            'style' => 'primary',
            'size' => 'medium',
            'color' => 'primary'
        ];
        
        // Save to WordPress options
        $result = update_option('villa_button_component', $defaults);
        
        if ($result) {
            wp_send_json_success('Button component reset to defaults');
        } else {
            wp_send_json_error('Failed to reset button component');
        }
    }
    
    /**
     * Save layout tokens to theme.json
     */
    public function save_layout_tokens() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $layout_data = $_POST['layout_data'] ?? [];
        
        // Sanitize layout data
        $sanitized_layout = [];
        foreach ($layout_data as $category => $tokens) {
            if (in_array($category, ['spacing', 'borderRadius', 'borderWidth', 'shadows', 'sizes', 'backgrounds'])) {
                $sanitized_layout[$category] = [];
                foreach ($tokens as $key => $value) {
                    $sanitized_layout[$category][sanitize_key($key)] = sanitize_text_field($value);
                }
            }
        }
        
        // Read current theme.json
        $theme_json_path = get_template_directory() . '/theme.json';
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json not found');
            return;
        }
        
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json format');
            return;
        }
        
        // Update layout tokens
        if (!isset($theme_json['settings'])) {
            $theme_json['settings'] = [];
        }
        if (!isset($theme_json['settings']['custom'])) {
            $theme_json['settings']['custom'] = [];
        }
        
        $theme_json['settings']['custom']['layout'] = $sanitized_layout;
        
        // Write back to theme.json
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to save theme.json');
            return;
        }
        
        wp_send_json_success('Layout tokens saved successfully');
    }
    
    /**
     * Reset layout tokens to defaults
     */
    public function reset_layout_tokens() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $default_layout = [
            'spacing' => [
                'xs' => '4px', 'sm' => '8px', 'md' => '12px', 'lg' => '16px',
                'xl' => '20px', '2xl' => '24px', '3xl' => '32px', '4xl' => '40px',
                '5xl' => '48px', '6xl' => '64px'
            ],
            'borderRadius' => [
                'none' => '0', 'sm' => '4px', 'md' => '6px', 'lg' => '8px',
                'xl' => '12px', '2xl' => '16px', '3xl' => '24px', 'full' => '9999px'
            ],
            'borderWidth' => [
                'none' => '0', 'thin' => '1px', 'medium' => '2px', 'thick' => '3px', 'heavy' => '4px'
            ],
            'shadows' => [
                'none' => 'none',
                'sm' => '0 1px 2px rgba(0, 0, 0, 0.05)',
                'md' => '0 2px 4px rgba(0, 0, 0, 0.1)',
                'lg' => '0 4px 8px rgba(0, 0, 0, 0.15)',
                'xl' => '0 8px 16px rgba(0, 0, 0, 0.2)',
                '2xl' => '0 16px 32px rgba(0, 0, 0, 0.25)',
                'inner' => 'inset 0 2px 4px rgba(0, 0, 0, 0.1)'
            ],
            'sizes' => [
                'xs' => '20px', 'sm' => '24px', 'md' => '32px', 'lg' => '40px',
                'xl' => '48px', '2xl' => '56px', '3xl' => '64px'
            ],
            'backgrounds' => [
                'colors' => [
                    'transparent' => 'transparent',
                    'white' => '#ffffff',
                    'light' => '#f8f9fa',
                    'neutral' => '#e9ecef',
                    'dark' => '#343a40',
                    'darker' => '#212529',
                    'primary' => 'var(--wp--preset--color--primary)',
                    'secondary' => 'var(--wp--preset--color--secondary)',
                    'accent' => 'var(--wp--preset--color--accent)'
                ],
                'overlays' => [
                    'none' => 'none',
                    'light' => 'rgba(255, 255, 255, 0.1)',
                    'medium' => 'rgba(255, 255, 255, 0.2)',
                    'heavy' => 'rgba(255, 255, 255, 0.4)',
                    'dark-light' => 'rgba(0, 0, 0, 0.1)',
                    'dark-medium' => 'rgba(0, 0, 0, 0.3)',
                    'dark-heavy' => 'rgba(0, 0, 0, 0.5)',
                    'dark-intense' => 'rgba(0, 0, 0, 0.7)'
                ]
            ]
        ];
        
        // Read current theme.json
        $theme_json_path = get_template_directory() . '/theme.json';
        if (!file_exists($theme_json_path)) {
            wp_send_json_error('theme.json not found');
            return;
        }
        
        $theme_json_content = file_get_contents($theme_json_path);
        $theme_json = json_decode($theme_json_content, true);
        
        if (!$theme_json) {
            wp_send_json_error('Invalid theme.json format');
            return;
        }
        
        // Reset layout tokens
        if (!isset($theme_json['settings'])) {
            $theme_json['settings'] = [];
        }
        if (!isset($theme_json['settings']['custom'])) {
            $theme_json['settings']['custom'] = [];
        }
        
        $theme_json['settings']['custom']['layout'] = $default_layout;
        
        // Write back to theme.json
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to save theme.json');
            return;
        }
        
        wp_send_json_success(['message' => 'Layout tokens reset to defaults', 'layout' => $default_layout]);
    }
}

// Initialize the DesignBook
new VillaDesignBook();

// Helper function to get base styles with defaults
function villa_get_base_styles() {
    $defaults = [
        'base-font-size' => '1rem'
    ];

    // Read from theme.json
    $theme_json_path = get_template_directory() . '/theme.json';
    
    if (!file_exists($theme_json_path)) {
        return $defaults;
    }
    
    $theme_json_content = file_get_contents($theme_json_path);
    $theme_json = json_decode($theme_json_content, true);
    
    if (!$theme_json) {
        return $defaults;
    }
    
    $styles = [];
    
    // Get font sizes from theme.json
    if (isset($theme_json['settings']['typography']['fontSizes'])) {
        foreach ($theme_json['settings']['typography']['fontSizes'] as $font_size) {
            switch ($font_size['slug']) {
                case 'medium':
                    $styles['base-font-size'] = $font_size['size'];
                    break;
            }
        }
    }

    return array_merge($defaults, $styles);
}
