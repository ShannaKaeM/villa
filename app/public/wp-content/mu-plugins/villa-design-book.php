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
        
        // Add submenu pages
        add_submenu_page(
            'villa-design-book',
            'ColorBook',
            'ColorBook',
            'manage_options',
            'villa-color-book',
            array($this, 'render_color_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'TextBook',
            'TextBook',
            'manage_options',
            'villa-text-book',
            array($this, 'render_text_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'ButtonBook',
            'ButtonBook',
            'manage_options',
            'villa-button-book',
            array($this, 'render_button_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'ComponentBook',
            'ComponentBook',
            'manage_options',
            'villa-component-book',
            array($this, 'render_component_book')
        );
        
        add_submenu_page(
            'villa-design-book',
            'Base Options',
            'Base Options',
            'manage_options',
            'villa-base-options',
            array($this, 'render_base_options')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'villa-design-book') === false && 
            strpos($hook, 'villa-color-book') === false && 
            strpos($hook, 'villa-text-book') === false && 
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
                <div class="design-book-grid">
                    
                    <div class="design-book-card">
                        <div class="card-icon">🎨</div>
                        <h3>ColorBook</h3>
                        <p>Manage your color palette with precision. Create light, medium, and dark variations of your brand colors.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-color-book'); ?>" class="button button-primary">Open ColorBook</a>
                    </div>
                    
                    <div class="design-book-card">
                        <div class="card-icon">📝</div>
                        <h3>TextBook</h3>
                        <p>Define semantic text styles: pre-title, title, subtitle, description, and body text with proper HTML tags.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-text-book'); ?>" class="button button-primary">Open TextBook</a>
                    </div>
                    
                    <div class="design-book-card">
                        <div class="card-icon">🖋️</div>
                        <h3>ButtonBook</h3>
                        <p>Design and customize buttons with various styles, sizes, and colors.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-button-book'); ?>" class="button button-primary">Open ButtonBook</a>
                    </div>
                    
                    <div class="design-book-card">
                        <div class="card-icon">🧩</div>
                        <h3>ComponentBook</h3>
                        <p>Build and customize UI components like cards, buttons, and sections with live preview.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-component-book'); ?>" class="button button-primary">Open ComponentBook</a>
                    </div>
                    
                    <div class="design-book-card">
                        <div class="card-icon">⚙️</div>
                        <h3>Base Options</h3>
                        <p>Configure core design tokens: font sizes, weights, spacing, line heights, and font families.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-base-options'); ?>" class="button button-primary">Open Base Options</a>
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
            'base' => array('extreme-light', 'base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest', 'extreme-dark')
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
        // Always convert from hex to get fresh CMYK values
        $cmyk_data = $this->hex_to_cmyk_approximate($color['color']);
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
                
                <!-- CMYK Controls -->
                <div class="cmyk-controls">
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
     * Render TextBook page
     */
    public function render_text_book() {
        $theme_json = $this->get_theme_json_data();
        $base_styles = villa_get_base_styles();
        
        // Get current text styles from theme.json
        $text_styles = [];
        if (isset($theme_json['settings']['typography']['customTextStyles'])) {
            $text_styles = $theme_json['settings']['typography']['customTextStyles'];
        }
        
        // Default text styles structure
        $default_styles = [
            'pretitle' => [
                'htmlTag' => 'span',
                'fontSize' => 'small',
                'fontWeight' => '600',
                'fontFamily' => 'inter',
                'textTransform' => 'uppercase',
                'letterSpacing' => 'wide',
                'lineHeight' => '1.2',
                'color' => 'primary'
            ],
            'title' => [
                'htmlTag' => 'h1',
                'fontSize' => 'xx-large',
                'fontWeight' => '700',
                'fontFamily' => 'playfair-display',
                'textTransform' => 'none',
                'letterSpacing' => 'tight',
                'lineHeight' => '1.2',
                'color' => 'base-darkest'
            ],
            'subtitle' => [
                'htmlTag' => 'h2',
                'fontSize' => 'x-large',
                'fontWeight' => '600',
                'fontFamily' => 'playfair-display',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => '1.4',
                'color' => 'base-dark'
            ],
            'description' => [
                'htmlTag' => 'p',
                'fontSize' => 'large',
                'fontWeight' => '400',
                'fontFamily' => 'inter',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => '1.6',
                'color' => 'base-dark'
            ],
            'body' => [
                'htmlTag' => 'p',
                'fontSize' => 'medium',
                'fontWeight' => '400',
                'fontFamily' => 'inter',
                'textTransform' => 'none',
                'letterSpacing' => 'normal',
                'lineHeight' => '1.6',
                'color' => 'base-darkest'
            ]
        ];
        
        // Merge with saved styles
        $text_styles = array_merge($default_styles, $text_styles);
        
        ?>
        <div class="wrap villa-text-book">
            <h1>📝 TextBook</h1>
            <p class="description">Manage your site's typography with semantic text styles and proportional base font scaling.</p>
            
            <div class="textbook-container">
                <div class="textbook-tabs">
                    <button class="textbook-tab active" data-tab="semantic-styles">Semantic Styles</button>
                    <button class="textbook-tab" data-tab="base-styles">Base Styles</button>
                </div>
                
                <div id="semantic-styles" class="textbook-tab-content active">
                    <div class="semantic-styles-header">
                        <h2>Semantic Text Styles</h2>
                        <p>Define your site's semantic text styles that can be applied to any content. These styles define the visual hierarchy and meaning of your text content.</p>
                    </div>
                    
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
                                            'description' => 'Descriptive text that provides context',
                                            'body' => 'Standard paragraph text for content'
                                        ];
                                        echo $descriptions[$style_key] ?? 'Custom text style';
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
                                            'description' => 'This is how your description text will appear with the current styling applied.',
                                            'body' => 'This is how your body text will appear. It should be comfortable to read and well-balanced with the overall design.'
                                        ];
                                        echo $preview_texts[$style_key] ?? 'Preview text';
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="text-style-controls">
                                    <div class="control-row">
                                        <div class="control-group">
                                            <label>HTML Tag</label>
                                            <select name="<?php echo $style_key; ?>_html_tag">
                                                <option value="h1" <?php selected($style_data['htmlTag'], 'h1'); ?>>H1</option>
                                                <option value="h2" <?php selected($style_data['htmlTag'], 'h2'); ?>>H2</option>
                                                <option value="h3" <?php selected($style_data['htmlTag'], 'h3'); ?>>H3</option>
                                                <option value="h4" <?php selected($style_data['htmlTag'], 'h4'); ?>>H4</option>
                                                <option value="h5" <?php selected($style_data['htmlTag'], 'h5'); ?>>H5</option>
                                                <option value="h6" <?php selected($style_data['htmlTag'], 'h6'); ?>>H6</option>
                                                <option value="p" <?php selected($style_data['htmlTag'], 'p'); ?>>Paragraph</option>
                                                <option value="span" <?php selected($style_data['htmlTag'], 'span'); ?>>Span</option>
                                                <option value="div" <?php selected($style_data['htmlTag'], 'div'); ?>>Div</option>
                                            </select>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label>Font Size</label>
                                            <select name="<?php echo $style_key; ?>_font_size">
                                                <option value="small" <?php selected($style_data['fontSize'], 'small'); ?>>Small</option>
                                                <option value="medium" <?php selected($style_data['fontSize'], 'medium'); ?>>Medium</option>
                                                <option value="large" <?php selected($style_data['fontSize'], 'large'); ?>>Large</option>
                                                <option value="x-large" <?php selected($style_data['fontSize'], 'x-large'); ?>>X-Large</option>
                                                <option value="xx-large" <?php selected($style_data['fontSize'], 'xx-large'); ?>>XX-Large</option>
                                                <option value="huge" <?php selected($style_data['fontSize'], 'huge'); ?>>Huge</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="control-row">
                                        <div class="control-group">
                                            <label>Font Weight</label>
                                            <select name="<?php echo $style_key; ?>_font_weight">
                                                <option value="300" <?php selected($style_data['fontWeight'], '300'); ?>>Light (300)</option>
                                                <option value="400" <?php selected($style_data['fontWeight'], '400'); ?>>Regular (400)</option>
                                                <option value="500" <?php selected($style_data['fontWeight'], '500'); ?>>Medium (500)</option>
                                                <option value="600" <?php selected($style_data['fontWeight'], '600'); ?>>Semi Bold (600)</option>
                                                <option value="700" <?php selected($style_data['fontWeight'], '700'); ?>>Bold (700)</option>
                                                <option value="800" <?php selected($style_data['fontWeight'], '800'); ?>>Extra Bold (800)</option>
                                                <option value="900" <?php selected($style_data['fontWeight'], '900'); ?>>Black (900)</option>
                                            </select>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label>Font Family</label>
                                            <select name="<?php echo $style_key; ?>_font_family">
                                                <option value="inter" <?php selected($style_data['fontFamily'] ?? '', 'inter'); ?>>Inter</option>
                                                <option value="playfair-display" <?php selected($style_data['fontFamily'] ?? '', 'playfair-display'); ?>>Playfair Display</option>
                                                <option value="roboto" <?php selected($style_data['fontFamily'] ?? '', 'roboto'); ?>>Roboto</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="control-row">
                                        <div class="control-group">
                                            <label>Text Transform</label>
                                            <select name="<?php echo $style_key; ?>_text_transform">
                                                <option value="none" <?php selected($style_data['textTransform'], 'none'); ?>>None</option>
                                                <option value="uppercase" <?php selected($style_data['textTransform'], 'uppercase'); ?>>Uppercase</option>
                                                <option value="lowercase" <?php selected($style_data['textTransform'], 'lowercase'); ?>>Lowercase</option>
                                                <option value="capitalize" <?php selected($style_data['textTransform'], 'capitalize'); ?>>Capitalize</option>
                                            </select>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label>Letter Spacing</label>
                                            <select name="<?php echo $style_key; ?>_letter_spacing">
                                                <option value="tight" <?php selected($style_data['letterSpacing'], 'tight'); ?>>Tight</option>
                                                <option value="normal" <?php selected($style_data['letterSpacing'], 'normal'); ?>>Normal</option>
                                                <option value="wide" <?php selected($style_data['letterSpacing'], 'wide'); ?>>Wide</option>
                                                <option value="wider" <?php selected($style_data['letterSpacing'], 'wider'); ?>>Wider</option>
                                                <option value="widest" <?php selected($style_data['letterSpacing'], 'widest'); ?>>Widest</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="control-row">
                                        <div class="control-group">
                                            <label>Text Color</label>
                                            <select name="<?php echo $style_key; ?>_color">
                                                <option value="primary-light" <?php selected($style_data['color'], 'primary-light'); ?>>Primary Light</option>
                                                <option value="primary" <?php selected($style_data['color'], 'primary'); ?>>Primary</option>
                                                <option value="primary-dark" <?php selected($style_data['color'], 'primary-dark'); ?>>Primary Dark</option>
                                                <option value="secondary-light" <?php selected($style_data['color'], 'secondary-light'); ?>>Secondary Light</option>
                                                <option value="secondary" <?php selected($style_data['color'], 'secondary'); ?>>Secondary</option>
                                                <option value="secondary-dark" <?php selected($style_data['color'], 'secondary-dark'); ?>>Secondary Dark</option>
                                                <option value="neutral-light" <?php selected($style_data['color'], 'neutral-light'); ?>>Neutral Light</option>
                                                <option value="neutral" <?php selected($style_data['color'], 'neutral'); ?>>Neutral</option>
                                                <option value="neutral-dark" <?php selected($style_data['color'], 'neutral-dark'); ?>>Neutral Dark</option>
                                                <option value="extreme-light" <?php selected($style_data['color'], 'extreme-light'); ?>>Extreme Light</option>
                                                <option value="base-lightest" <?php selected($style_data['color'], 'base-lightest'); ?>>Base Lightest</option>
                                                <option value="base-light" <?php selected($style_data['color'], 'base-light'); ?>>Base Light</option>
                                                <option value="base" <?php selected($style_data['color'], 'base'); ?>>Base</option>
                                                <option value="base-dark" <?php selected($style_data['color'], 'base-dark'); ?>>Base Dark</option>
                                                <option value="base-darkest" <?php selected($style_data['color'], 'base-darkest'); ?>>Base Darkest</option>
                                                <option value="extreme-dark" <?php selected($style_data['color'], 'extreme-dark'); ?>>Extreme Dark</option>
                                            </select>
                                        </div>
                                        
                                        <div class="control-group">
                                            <label>Line Height</label>
                                            <select name="<?php echo $style_key; ?>_line_height">
                                                <option value="1" <?php selected($style_data['lineHeight'] ?? '', '1'); ?>>Tight (1.0)</option>
                                                <option value="1.2" <?php selected($style_data['lineHeight'] ?? '', '1.2'); ?>>Snug (1.2)</option>
                                                <option value="1.4" <?php selected($style_data['lineHeight'] ?? '', '1.4'); ?>>Normal (1.4)</option>
                                                <option value="1.6" <?php selected($style_data['lineHeight'] ?? '', '1.6'); ?>>Relaxed (1.6)</option>
                                                <option value="1.8" <?php selected($style_data['lineHeight'] ?? '', '1.8'); ?>>Loose (1.8)</option>
                                                <option value="2" <?php selected($style_data['lineHeight'] ?? '', '2'); ?>>Very Loose (2.0)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="action-buttons">
                        <button id="save-text-styles" class="button button-primary">Save Semantic Styles</button>
                        <button id="reset-text-styles" class="button">Reset to Defaults</button>
                    </div>
                </div>
                
                <div id="base-styles" class="textbook-tab-content">
                    <div class="base-styles-header">
                        <h2>Base Font Scaling</h2>
                        <p>Control the proportional font scaling system. Changing the base font size will automatically scale all other font sizes proportionally.</p>
                    </div>
                    
                    <div class="base-styles-controls">
                        <div class="control-group">
                            <label for="base-font-size">Base Font Size</label>
                            <input type="text" id="base-font-size" value="<?php echo esc_attr($base_styles['base-font-size']); ?>" placeholder="1rem">
                            <p class="description">This is the foundation size that all other font sizes are calculated from.</p>
                        </div>
                    </div>
                    
                    <div class="font-scale-preview">
                        <h3>Font Scale Preview</h3>
                        <div class="scale-items">
                            <div class="scale-item">
                                <span class="scale-label">Small</span>
                                <span class="scale-value">0.8125rem (0.8125× base)</span>
                            </div>
                            <div class="scale-item">
                                <span class="scale-label">Medium</span>
                                <span class="scale-value">1rem (1.0× base)</span>
                            </div>
                            <div class="scale-item">
                                <span class="scale-label">Large</span>
                                <span class="scale-value">1.25rem (1.25× base)</span>
                            </div>
                            <div class="scale-item">
                                <span class="scale-label">X-Large</span>
                                <span class="scale-value">1.5rem (1.5× base)</span>
                            </div>
                            <div class="scale-item">
                                <span class="scale-label">XX-Large</span>
                                <span class="scale-value">2rem (2.0× base)</span>
                            </div>
                            <div class="scale-item">
                                <span class="scale-label">Huge</span>
                                <span class="scale-value">6.25rem (6.25× base)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button id="save-base-styles" class="button button-primary">Save Base Styles</button>
                        <button id="reset-base-styles" class="button">Reset to Defaults</button>
                    </div>
                </div>
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
            <p class="description">Design and customize reusable components for your Villa Community theme.</p>
            
            <div class="component-library">
                <div class="component-section">
                    <h2>Card Component</h2>
                    <p>A versatile card component with image, pretitle, title, and description.</p>
                    
                    <div class="component-editor">
                        <div class="component-controls">
                            <h3>Card Settings</h3>
                            
                            <div class="control-group">
                                <label for="card-image">Image</label>
                                <div class="media-upload-wrapper">
                                    <input type="hidden" id="card-image" class="card-control" data-property="image" 
                                           value="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=250&fit=crop">
                                    <div class="media-preview">
                                        <img id="card-image-preview" src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=250&fit=crop" alt="Card Image Preview">
                                    </div>
                                    <div class="media-buttons">
                                        <button type="button" id="upload-card-image" class="button">Choose Image</button>
                                        <button type="button" id="remove-card-image" class="button">Remove</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label for="card-pretitle">Pretitle</label>
                                <input type="text" id="card-pretitle" class="card-control" data-property="pretitle" 
                                       value="Featured Property" placeholder="Enter pretitle">
                            </div>
                            
                            <div class="control-group">
                                <label for="card-title">Title</label>
                                <input type="text" id="card-title" class="card-control" data-property="title" 
                                       value="Modern Villa with Ocean View" placeholder="Enter title">
                            </div>
                            
                            <div class="control-group">
                                <label for="card-description">Description</label>
                                <textarea id="card-description" class="card-control" data-property="description" 
                                          placeholder="Enter description">Experience luxury living in this stunning modern villa featuring panoramic ocean views, contemporary design, and premium amenities.</textarea>
                            </div>
                            
                            <div class="control-group">
                                <label for="card-border-radius">Border Radius</label>
                                <input type="range" id="card-border-radius" class="card-control" data-property="borderRadius" 
                                       min="0" max="30" value="12" step="1">
                                <span class="range-value">12px</span>
                            </div>
                            
                            <div class="control-group">
                                <label for="card-shadow">Shadow Intensity</label>
                                <input type="range" id="card-shadow" class="card-control" data-property="shadow" 
                                       min="0" max="5" value="2" step="1">
                                <span class="range-value">2</span>
                            </div>
                            
                            <div class="component-actions">
                                <button type="button" id="save-card-component" class="button button-primary">Save Card Component</button>
                                <button type="button" id="reset-card-component" class="button">Reset to Default</button>
                            </div>
                        </div>
                        
                        <div class="component-preview">
                            <h3>Live Preview</h3>
                            <div class="preview-container">
                                <div class="villa-card" id="card-preview">
                                    <div class="card-image">
                                        <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=250&fit=crop" alt="Card Image">
                                    </div>
                                    <div class="card-content">
                                        <div class="card-pretitle">Featured Property</div>
                                        <h3 class="card-title">Modern Villa with Ocean View</h3>
                                        <p class="card-description">Experience luxury living in this stunning modern villa featuring panoramic ocean views, contemporary design, and premium amenities.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="code-output">
                                <h4>Generated CSS</h4>
                                <textarea readonly id="card-css-output" class="code-textarea"></textarea>
                            </div>
                        </div>
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
                
                <div class="base-actions">
                    <button type="button" id="save-layout-tokens" class="button button-primary">Save Layout Tokens</button>
                    <button type="button" id="reset-layout-tokens" class="button">Reset to Defaults</button>
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
        
        if (!$colors) {
            wp_send_json_error('Invalid color data');
        }
        
        // Save CMYK data to WordPress options for each color
        if ($cmyk_data) {
            foreach ($cmyk_data as $slug => $cmyk) {
                update_option('villa_color_cmyk_' . $slug, $cmyk);
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
        
        // Write updated theme.json back to file
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to update theme.json file');
        }
        
        wp_send_json_success('Colors and CMYK data saved successfully');
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
            if (in_array($category, ['spacing', 'borderRadius', 'borderWidth', 'shadows', 'sizes'])) {
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
