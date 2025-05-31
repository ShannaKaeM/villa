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
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_villa_save_design_tokens', array($this, 'save_design_tokens'));
        add_action('wp_ajax_villa_export_design_tokens', array($this, 'export_design_tokens'));
        add_action('wp_ajax_villa_import_design_tokens', array($this, 'import_design_tokens'));
        add_action('wp_ajax_villa_save_text_styles', array($this, 'save_text_styles'));
        add_action('wp_ajax_villa_reset_text_styles', array($this, 'reset_text_styles'));
        add_action('wp_ajax_villa_save_base_styles', array($this, 'save_base_styles'));
        add_action('wp_ajax_villa_reset_base_styles', array($this, 'reset_base_styles'));
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
     * Enqueue scripts and styles
     */
    public function enqueue_scripts($hook) {
        if (strpos($hook, 'villa-design-book') === false && strpos($hook, 'villa-color-book') === false && 
            strpos($hook, 'villa-text-book') === false && strpos($hook, 'villa-component-book') === false &&
            strpos($hook, 'villa-base-options') === false) {
            return;
        }
        
        wp_enqueue_style('villa-design-book-css', get_template_directory_uri() . '/assets/css/villa-design-book.css', array(), '1.0.0');
        wp_enqueue_script('villa-design-book-js', get_template_directory_uri() . '/assets/js/villa-design-book.js', array('jquery'), '1.0.0', true);
        
        // Localize script for AJAX
        wp_localize_script('villa-design-book-js', 'villaDesignBook', array(
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
                        <p>Manage your color palette with OKLCH precision. Create light, medium, and dark variations of your brand colors.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-color-book'); ?>" class="button button-primary">Open ColorBook</a>
                    </div>
                    
                    <div class="design-book-card">
                        <div class="card-icon">📝</div>
                        <h3>TextBook</h3>
                        <p>Define semantic text styles: pre-title, title, subtitle, description, and body text with proper HTML tags.</p>
                        <a href="<?php echo admin_url('admin.php?page=villa-text-book'); ?>" class="button button-primary">Open TextBook</a>
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
            <p class="description">Manage your color palette with OKLCH precision. Adjust lightness, chroma, and hue for perfect color harmony.</p>
            
            <div class="color-book-container">
                <div class="color-palette-editor">
                    <?php $this->render_color_palette($colors); ?>
                </div>
                
                <div class="color-preview">
                    <h3>Live Preview</h3>
                    <div class="preview-components">
                        <?php $this->render_color_preview(); ?>
                    </div>
                </div>
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
        // Group colors by type
        $color_groups = array(
            'primary' => array('primary-light', 'primary', 'primary-dark'),
            'secondary' => array('secondary-light', 'secondary', 'secondary-dark'),
            'neutral' => array('neutral-light', 'neutral', 'neutral-dark'),
            'base-1' => array('base-white', 'base-lightest', 'base-light'),
            'base-2' => array('base', 'base-dark', 'base-darkest', 'base-black')
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
        ?>
        <div class="color-swatch" data-slug="<?php echo esc_attr($color['slug']); ?>">
            <div class="color-preview-box" style="background-color: <?php echo esc_attr($color['color']); ?>"></div>
            <div class="color-controls">
                <label><?php echo esc_html($color['name']); ?></label>
                <input type="color" value="<?php echo esc_attr($color['color']); ?>" class="color-picker">
                <div class="oklch-controls">
                    <label>Lightness</label>
                    <input type="range" min="0" max="100" class="lightness-slider">
                    <label>Chroma</label>
                    <input type="range" min="0" max="0.4" step="0.01" class="chroma-slider">
                    <label>Hue</label>
                    <input type="range" min="0" max="360" class="hue-slider">
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render color preview components
     */
    private function render_color_preview() {
        ?>
        <div class="preview-card villa-card">
            <div class="villa-card__image">
                <div style="height: 200px; background: var(--wp--preset--color--primary); display: flex; align-items: center; justify-content: center; color: white;">
                    Sample Image Area
                </div>
            </div>
            <div class="villa-card__content">
                <div class="villa-card__text">
                    <h4 class="villa-card__title">Sample Card Title</h4>
                    <p class="villa-card__description">This is a sample description to show how colors work together in your design system.</p>
                    <div class="villa-card__meta">
                        <span class="villa-card__tag">Primary Tag</span>
                        <span class="villa-card__tag villa-card__tag--secondary">Secondary Tag</span>
                    </div>
                </div>
                <div class="villa-card__actions">
                    <button class="button button-primary">Primary Button</button>
                    <button class="button button-secondary">Secondary Button</button>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render TextBook page
     */
    public function render_text_book() {
        // Get saved text styles
        $saved_styles = get_option('villa_text_styles', []);
        
        // Get base styles
        $base_styles = villa_get_base_styles();
        
        // Get theme colors for color options
        $theme_json_path = get_template_directory() . '/theme.json';
        $theme_colors = [];
        if (file_exists($theme_json_path)) {
            $theme_json = json_decode(file_get_contents($theme_json_path), true);
            if (isset($theme_json['settings']['color']['palette'])) {
                foreach ($theme_json['settings']['color']['palette'] as $color) {
                    $theme_colors[$color['slug']] = $color['color'];
                }
            }
        }
        
        // Get theme font families
        $font_families = [
            'Inter' => 'Inter, sans-serif',
            'Playfair Display' => 'Playfair Display, serif',
            'Roboto' => 'Roboto, sans-serif',
            'Open Sans' => 'Open Sans, sans-serif',
            'Lato' => 'Lato, sans-serif'
        ];
        
        $text_styles = [
            'pre-title' => [
                'label' => 'Pre-Title',
                'description' => 'Small text that appears above main titles',
                'sample' => 'VILLA COMMUNITY',
                'defaults' => [
                    'htmlTag' => 'span',
                    'fontSize' => 'small',
                    'fontWeight' => '500',
                    'textTransform' => 'uppercase',
                    'letterSpacing' => '0.1em',
                    'color' => '#5A7F80',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ],
            'title' => [
                'label' => 'Title',
                'description' => 'Main page and section titles',
                'sample' => 'Welcome Home',
                'defaults' => [
                    'htmlTag' => 'h1',
                    'fontSize' => 'xx-large',
                    'fontWeight' => '700',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#1A1A1A',
                    'fontFamily' => 'Playfair Display, serif'
                ]
            ],
            'subtitle' => [
                'label' => 'Subtitle',
                'description' => 'Secondary headings and subtitles',
                'sample' => 'Discover Your Perfect Villa',
                'defaults' => [
                    'htmlTag' => 'h2',
                    'fontSize' => 'x-large',
                    'fontWeight' => '600',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#2D2D2D',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ],
            'section-title' => [
                'label' => 'Section Title',
                'description' => 'Titles for content sections',
                'sample' => 'Featured Properties',
                'defaults' => [
                    'htmlTag' => 'h3',
                    'fontSize' => 'large',
                    'fontWeight' => '600',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#1A1A1A',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ],
            'body' => [
                'label' => 'Body Text',
                'description' => 'Main content and paragraph text',
                'sample' => 'Experience luxury living in our carefully curated collection of premium villas.',
                'defaults' => [
                    'htmlTag' => 'p',
                    'fontSize' => 'medium',
                    'fontWeight' => '400',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#4A4A4A',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ],
            'caption' => [
                'label' => 'Caption',
                'description' => 'Small text for captions and metadata',
                'sample' => 'Updated 2 hours ago',
                'defaults' => [
                    'htmlTag' => 'span',
                    'fontSize' => 'small',
                    'fontWeight' => '400',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#6B6B6B',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ],
            'button' => [
                'label' => 'Button Text',
                'description' => 'Text for buttons and call-to-action elements',
                'sample' => 'View Details',
                'defaults' => [
                    'htmlTag' => 'span',
                    'fontSize' => 'medium',
                    'fontWeight' => '500',
                    'textTransform' => 'none',
                    'letterSpacing' => 'normal',
                    'color' => '#FFFFFF',
                    'fontFamily' => 'Inter, sans-serif'
                ]
            ]
        ];

        ?>
        <div class="design-book-content">
            <!-- Tabbed Navigation -->
            <div class="textbook-tabs">
                <button class="textbook-tab active" data-tab="semantic-styles">Semantic Styles</button>
                <button class="textbook-tab" data-tab="base-styles">Base Styles</button>
            </div>

            <!-- Semantic Styles Tab -->
            <div id="semantic-styles" class="textbook-tab-content active">
                <div class="text-styles-grid">
                    <?php foreach ($text_styles as $style_key => $style_config): 
                        $current_style = isset($saved_styles[$style_key]) ? $saved_styles[$style_key] : $style_config['defaults'];
                    ?>
                        <div class="text-style-card" data-style="<?php echo esc_attr($style_key); ?>">
                            <div class="text-style-header">
                                <h3><?php echo esc_html($style_config['label']); ?></h3>
                                <p><?php echo esc_html($style_config['description']); ?></p>
                            </div>
                            
                            <div class="text-style-controls">
                                <div class="control-row">
                                    <div class="control-group">
                                        <label>HTML Tag</label>
                                        <select data-control="html-tag">
                                            <option value="h1" <?php selected($current_style['htmlTag'], 'h1'); ?>>H1</option>
                                            <option value="h2" <?php selected($current_style['htmlTag'], 'h2'); ?>>H2</option>
                                            <option value="h3" <?php selected($current_style['htmlTag'], 'h3'); ?>>H3</option>
                                            <option value="h4" <?php selected($current_style['htmlTag'], 'h4'); ?>>H4</option>
                                            <option value="h5" <?php selected($current_style['htmlTag'], 'h5'); ?>>H5</option>
                                            <option value="h6" <?php selected($current_style['htmlTag'], 'h6'); ?>>H6</option>
                                            <option value="p" <?php selected($current_style['htmlTag'], 'p'); ?>>Paragraph</option>
                                            <option value="span" <?php selected($current_style['htmlTag'], 'span'); ?>>Span</option>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Font Size</label>
                                        <select data-control="font-size">
                                            <option value="small" <?php selected($current_style['fontSize'], 'small'); ?>>Small</option>
                                            <option value="medium" <?php selected($current_style['fontSize'], 'medium'); ?>>Medium</option>
                                            <option value="large" <?php selected($current_style['fontSize'], 'large'); ?>>Large</option>
                                            <option value="x-large" <?php selected($current_style['fontSize'], 'x-large'); ?>>X Large</option>
                                            <option value="xx-large" <?php selected($current_style['fontSize'], 'xx-large'); ?>>XX Large</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="control-row">
                                    <div class="control-group">
                                        <label>Font Weight</label>
                                        <select data-control="font-weight">
                                            <option value="400" <?php selected($current_style['fontWeight'], '400'); ?>>Regular (400)</option>
                                            <option value="500" <?php selected($current_style['fontWeight'], '500'); ?>>Medium (500)</option>
                                            <option value="600" <?php selected($current_style['fontWeight'], '600'); ?>>Semi Bold (600)</option>
                                            <option value="700" <?php selected($current_style['fontWeight'], '700'); ?>>Bold (700)</option>
                                            <option value="800" <?php selected($current_style['fontWeight'], '800'); ?>>Extra Bold (800)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Text Transform</label>
                                        <select data-control="text-transform">
                                            <option value="none" <?php selected($current_style['textTransform'], 'none'); ?>>None</option>
                                            <option value="uppercase" <?php selected($current_style['textTransform'], 'uppercase'); ?>>Uppercase</option>
                                            <option value="lowercase" <?php selected($current_style['textTransform'], 'lowercase'); ?>>Lowercase</option>
                                            <option value="capitalize" <?php selected($current_style['textTransform'], 'capitalize'); ?>>Capitalize</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="control-row">
                                    <div class="control-group">
                                        <label>Letter Spacing</label>
                                        <select data-control="letter-spacing">
                                            <option value="normal" <?php selected($current_style['letterSpacing'], 'normal'); ?>>Normal</option>
                                            <option value="0.05em" <?php selected($current_style['letterSpacing'], '0.05em'); ?>>0.05em</option>
                                            <option value="0.1em" <?php selected($current_style['letterSpacing'], '0.1em'); ?>>0.1em</option>
                                            <option value="0.15em" <?php selected($current_style['letterSpacing'], '0.15em'); ?>>0.15em</option>
                                            <option value="0.2em" <?php selected($current_style['letterSpacing'], '0.2em'); ?>>0.2em</option>
                                        </select>
                                    </div>
                                    
                                    <div class="control-group">
                                        <label>Color</label>
                                        <select data-control="color">
                                            <?php foreach ($theme_colors as $color_slug => $color_value): ?>
                                                <option value="<?php echo esc_attr($color_value); ?>" <?php selected($current_style['color'], $color_value); ?>>
                                                    <?php echo esc_html(ucwords(str_replace('-', ' ', $color_slug))); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="control-row">
                                    <div class="control-group">
                                        <label>Font Family</label>
                                        <select data-control="font-family">
                                            <?php foreach ($font_families as $font_name => $font_value): ?>
                                                <option value="<?php echo esc_attr($font_value); ?>" <?php selected($current_style['fontFamily'], $font_value); ?>>
                                                    <?php echo esc_html($font_name); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-style-preview">
                                <div class="preview-element"><?php echo esc_html($style_config['sample']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Actions for Semantic Styles -->
                <div class="textbook-actions">
                    <button id="save-text-styles" class="button button-primary">
                        <span class="dashicons dashicons-yes"></span>
                        Save Text Styles
                    </button>
                    <button id="reset-text-styles" class="button button-secondary">
                        <span class="dashicons dashicons-undo"></span>
                        Reset to Default
                    </button>
                </div>
            </div>

            <!-- Base Styles Tab -->
            <div id="base-styles" class="textbook-tab-content">
                <div class="base-styles-grid">
                    <!-- Font Sizes Section -->
                    <div class="base-style-section">
                        <h3>Font Sizes</h3>
                        <p>Define the base font sizes used throughout the theme</p>
                        
                        <div class="base-style-control">
                            <label>Base Font Size</label>
                            <input type="text" data-property="base-font-size" value="<?php echo esc_attr($base_styles['base-font-size']); ?>" placeholder="16px">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Small Font Size</label>
                            <input type="text" data-property="small-font-size" value="<?php echo esc_attr($base_styles['small-font-size']); ?>" placeholder="14px">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Medium Font Size</label>
                            <input type="text" data-property="medium-font-size" value="<?php echo esc_attr($base_styles['medium-font-size']); ?>" placeholder="16px">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Large Font Size</label>
                            <input type="text" data-property="large-font-size" value="<?php echo esc_attr($base_styles['large-font-size']); ?>" placeholder="20px">
                        </div>
                        
                        <div class="base-style-control">
                            <label>X-Large Font Size</label>
                            <input type="text" data-property="x-large-font-size" value="<?php echo esc_attr($base_styles['x-large-font-size']); ?>" placeholder="24px">
                        </div>
                        
                        <div class="base-style-control">
                            <label>XX-Large Font Size</label>
                            <input type="text" data-property="xx-large-font-size" value="<?php echo esc_attr($base_styles['xx-large-font-size']); ?>" placeholder="32px">
                        </div>
                    </div>

                    <!-- Font Weights Section -->
                    <div class="base-style-section">
                        <h3>Font Weights</h3>
                        <p>Define the font weights available in the theme</p>
                        
                        <div class="base-style-control">
                            <label>Regular Weight</label>
                            <input type="text" data-property="regular-font-weight" value="<?php echo esc_attr($base_styles['regular-font-weight']); ?>" placeholder="400">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Medium Weight</label>
                            <input type="text" data-property="medium-font-weight" value="<?php echo esc_attr($base_styles['medium-font-weight']); ?>" placeholder="500">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Semi Bold Weight</label>
                            <input type="text" data-property="semi-bold-font-weight" value="<?php echo esc_attr($base_styles['semi-bold-font-weight']); ?>" placeholder="600">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Bold Weight</label>
                            <input type="text" data-property="bold-font-weight" value="<?php echo esc_attr($base_styles['bold-font-weight']); ?>" placeholder="700">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Extra Bold Weight</label>
                            <input type="text" data-property="extra-bold-font-weight" value="<?php echo esc_attr($base_styles['extra-bold-font-weight']); ?>" placeholder="800">
                        </div>
                    </div>

                    <!-- Line Heights & Spacing Section -->
                    <div class="base-style-section">
                        <h3>Line Heights & Spacing</h3>
                        <p>Define line heights and letter spacing values</p>
                        
                        <div class="base-style-control">
                            <label>Base Line Height</label>
                            <input type="text" data-property="base-line-height" value="<?php echo esc_attr($base_styles['base-line-height']); ?>" placeholder="1.6">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Heading Line Height</label>
                            <input type="text" data-property="heading-line-height" value="<?php echo esc_attr($base_styles['heading-line-height']); ?>" placeholder="1.2">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Normal Letter Spacing</label>
                            <input type="text" data-property="normal-letter-spacing" value="<?php echo esc_attr($base_styles['normal-letter-spacing']); ?>" placeholder="0">
                        </div>
                        
                        <div class="base-style-control">
                            <label>Wide Letter Spacing</label>
                            <input type="text" data-property="wide-letter-spacing" value="<?php echo esc_attr($base_styles['wide-letter-spacing']); ?>" placeholder="0.1em">
                        </div>
                    </div>
                </div>

                <!-- Actions for Base Styles -->
                <div class="textbook-actions">
                    <button id="save-base-styles" class="button button-primary">
                        <span class="dashicons dashicons-yes"></span>
                        Save Base Styles
                    </button>
                    <button id="reset-base-styles" class="button button-secondary">
                        <span class="dashicons dashicons-undo"></span>
                        Reset to Default
                    </button>
                </div>
            </div>

            <!-- Live Preview -->
            <div class="textbook-live-preview">
                <h2>Live Preview</h2>
                <div class="preview-content">
                    <div class="preview-text pre-title">VILLA COMMUNITY</div>
                    <div class="preview-text title">Welcome Home</div>
                    <div class="preview-text subtitle">Discover Your Perfect Villa</div>
                    <div class="preview-text section-title">Featured Properties</div>
                    <div class="preview-text body">Experience luxury living in our carefully curated collection of premium villas. Each property offers stunning views, modern amenities, and the perfect blend of comfort and elegance.</div>
                    <div class="preview-text caption">Updated 2 hours ago</div>
                    <div class="preview-text button">View Details</div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render ComponentBook page
     */
    public function render_component_book() {
        echo '<div class="wrap"><h1>🧩 ComponentBook</h1><p>Coming soon...</p></div>';
    }
    
    /**
     * Render Base Options page
     */
    public function render_base_options() {
        echo '<div class="wrap"><h1>⚙️ Base Options</h1><p>Coming soon...</p></div>';
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
    
    public function save_text_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $text_styles = json_decode(stripslashes($_POST['text_styles']), true);
        
        if (!$text_styles) {
            wp_send_json_error('Invalid text style data');
        }
        
        // Save text styles to WordPress options
        update_option('villa_text_styles', $text_styles);
        
        // Also update theme.json typography settings
        $theme_json_path = get_template_directory() . '/theme.json';
        $theme_json = json_decode(file_get_contents($theme_json_path), true);
        
        // Update typography settings based on text styles
        if (!isset($theme_json['settings']['typography'])) {
            $theme_json['settings']['typography'] = [];
        }
        
        // Map text styles to theme.json structure
        $theme_json['settings']['typography']['customTextStyles'] = $text_styles;
        
        // Save theme.json
        file_put_contents($theme_json_path, json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        wp_send_json_success('Text styles saved successfully');
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
    
    public function save_base_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $base_styles = json_decode(stripslashes($_POST['base_styles']), true);
        
        if (!$base_styles) {
            wp_send_json_error('Invalid base style data');
        }
        
        // Validate and sanitize base styles
        $allowed_properties = [
            'base-font-size',
            'small-font-size',
            'medium-font-size',
            'large-font-size',
            'x-large-font-size',
            'xx-large-font-size',
            'regular-font-weight',
            'medium-font-weight',
            'semi-bold-font-weight',
            'bold-font-weight',
            'extra-bold-font-weight',
            'base-line-height',
            'heading-line-height',
            'normal-letter-spacing',
            'wide-letter-spacing'
        ];

        $sanitized_styles = [];
        foreach ($base_styles as $property => $value) {
            if (in_array($property, $allowed_properties)) {
                $sanitized_styles[$property] = sanitize_text_field($value);
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
        
        // Update font sizes in theme.json
        if (isset($theme_json['settings']['typography']['fontSizes'])) {
            foreach ($theme_json['settings']['typography']['fontSizes'] as &$font_size) {
                switch ($font_size['slug']) {
                    case 'small':
                        if (isset($sanitized_styles['small-font-size'])) {
                            $font_size['size'] = $sanitized_styles['small-font-size'];
                        }
                        break;
                    case 'medium':
                        if (isset($sanitized_styles['medium-font-size'])) {
                            $font_size['size'] = $sanitized_styles['medium-font-size'];
                        }
                        break;
                    case 'large':
                        if (isset($sanitized_styles['large-font-size'])) {
                            $font_size['size'] = $sanitized_styles['large-font-size'];
                        }
                        break;
                    case 'x-large':
                        if (isset($sanitized_styles['x-large-font-size'])) {
                            $font_size['size'] = $sanitized_styles['x-large-font-size'];
                        }
                        break;
                    case 'xx-large':
                        if (isset($sanitized_styles['xx-large-font-size'])) {
                            $font_size['size'] = $sanitized_styles['xx-large-font-size'];
                        }
                        break;
                }
            }
        }
        
        // Add custom properties for font weights and other base styles
        if (!isset($theme_json['settings']['custom'])) {
            $theme_json['settings']['custom'] = [];
        }
        
        if (!isset($theme_json['settings']['custom']['typography'])) {
            $theme_json['settings']['custom']['typography'] = [];
        }
        
        // Store base styles in custom properties
        $theme_json['settings']['custom']['typography']['baseStyles'] = [
            'baseFontSize' => $sanitized_styles['base-font-size'] ?? '16px',
            'fontWeights' => [
                'regular' => $sanitized_styles['regular-font-weight'] ?? '400',
                'medium' => $sanitized_styles['medium-font-weight'] ?? '500',
                'semiBold' => $sanitized_styles['semi-bold-font-weight'] ?? '600',
                'bold' => $sanitized_styles['bold-font-weight'] ?? '700',
                'extraBold' => $sanitized_styles['extra-bold-font-weight'] ?? '800'
            ],
            'lineHeights' => [
                'base' => $sanitized_styles['base-line-height'] ?? '1.6',
                'heading' => $sanitized_styles['heading-line-height'] ?? '1.2'
            ],
            'letterSpacing' => [
                'normal' => $sanitized_styles['normal-letter-spacing'] ?? '0',
                'wide' => $sanitized_styles['wide-letter-spacing'] ?? '0.1em'
            ]
        ];
        
        // Write updated theme.json back to file
        $updated_json = json_encode($theme_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if (file_put_contents($theme_json_path, $updated_json) === false) {
            wp_send_json_error('Failed to update theme.json file');
        }
        
        wp_send_json_success('Base styles saved to theme.json successfully');
    }
    
    public function reset_base_styles() {
        check_ajax_referer('villa_design_book_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Default base styles
        $defaults = [
            'base-font-size' => '16px',
            'small-font-size' => '14px',
            'medium-font-size' => '16px',
            'large-font-size' => '20px',
            'x-large-font-size' => '24px',
            'xx-large-font-size' => '32px',
            'regular-font-weight' => '400',
            'medium-font-weight' => '500',
            'semi-bold-font-weight' => '600',
            'bold-font-weight' => '700',
            'extra-bold-font-weight' => '800',
            'base-line-height' => '1.6',
            'heading-line-height' => '1.2',
            'normal-letter-spacing' => '0',
            'wide-letter-spacing' => '0.1em'
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
                    case 'small':
                        $font_size['size'] = $defaults['small-font-size'];
                        break;
                    case 'medium':
                        $font_size['size'] = $defaults['medium-font-size'];
                        break;
                    case 'large':
                        $font_size['size'] = $defaults['large-font-size'];
                        break;
                    case 'x-large':
                        $font_size['size'] = $defaults['x-large-font-size'];
                        break;
                    case 'xx-large':
                        $font_size['size'] = $defaults['xx-large-font-size'];
                        break;
                }
            }
        }
        
        // Reset custom base styles
        if (isset($theme_json['settings']['custom']['typography']['baseStyles'])) {
            unset($theme_json['settings']['custom']['typography']['baseStyles']);
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
}

// Initialize the DesignBook
new VillaDesignBook();

// Helper function to get base styles with defaults
function villa_get_base_styles() {
    $defaults = [
        'base-font-size' => '16px',
        'small-font-size' => '14px',
        'medium-font-size' => '16px',
        'large-font-size' => '20px',
        'x-large-font-size' => '24px',
        'xx-large-font-size' => '32px',
        'regular-font-weight' => '400',
        'medium-font-weight' => '500',
        'semi-bold-font-weight' => '600',
        'bold-font-weight' => '700',
        'extra-bold-font-weight' => '800',
        'base-line-height' => '1.6',
        'heading-line-height' => '1.2',
        'normal-letter-spacing' => '0',
        'wide-letter-spacing' => '0.1em'
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
                case 'small':
                    $styles['small-font-size'] = $font_size['size'];
                    break;
                case 'medium':
                    $styles['medium-font-size'] = $font_size['size'];
                    break;
                case 'large':
                    $styles['large-font-size'] = $font_size['size'];
                    break;
                case 'x-large':
                    $styles['x-large-font-size'] = $font_size['size'];
                    break;
                case 'xx-large':
                    $styles['xx-large-font-size'] = $font_size['size'];
                    break;
            }
        }
    }
    
    // Get custom base styles from theme.json
    if (isset($theme_json['settings']['custom']['typography']['baseStyles'])) {
        $base_styles = $theme_json['settings']['custom']['typography']['baseStyles'];
        
        if (isset($base_styles['baseFontSize'])) {
            $styles['base-font-size'] = $base_styles['baseFontSize'];
        }
        
        if (isset($base_styles['fontWeights'])) {
            $weights = $base_styles['fontWeights'];
            $styles['regular-font-weight'] = $weights['regular'] ?? $defaults['regular-font-weight'];
            $styles['medium-font-weight'] = $weights['medium'] ?? $defaults['medium-font-weight'];
            $styles['semi-bold-font-weight'] = $weights['semiBold'] ?? $defaults['semi-bold-font-weight'];
            $styles['bold-font-weight'] = $weights['bold'] ?? $defaults['bold-font-weight'];
            $styles['extra-bold-font-weight'] = $weights['extraBold'] ?? $defaults['extra-bold-font-weight'];
        }
        
        if (isset($base_styles['lineHeights'])) {
            $line_heights = $base_styles['lineHeights'];
            $styles['base-line-height'] = $line_heights['base'] ?? $defaults['base-line-height'];
            $styles['heading-line-height'] = $line_heights['heading'] ?? $defaults['heading-line-height'];
        }
        
        if (isset($base_styles['letterSpacing'])) {
            $letter_spacing = $base_styles['letterSpacing'];
            $styles['normal-letter-spacing'] = $letter_spacing['normal'] ?? $defaults['normal-letter-spacing'];
            $styles['wide-letter-spacing'] = $letter_spacing['wide'] ?? $defaults['wide-letter-spacing'];
        }
    }

    return array_merge($defaults, $styles);
}
