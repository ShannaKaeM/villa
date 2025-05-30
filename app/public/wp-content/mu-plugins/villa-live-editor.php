<?php
/**
 * Villa Live Editor - AI-Powered Visual Editor Proof of Concept
 * 
 * This is a prototype for the AI-powered live editing system
 * Based on CMB2 + React + Web Components architecture
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Villa_Live_Editor {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_villa_live_update', array($this, 'handle_live_update'));
        add_action('wp_ajax_nopriv_villa_live_update', array($this, 'handle_live_update'));
    }
    
    public function init() {
        // Register live editor blocks
        $this->register_live_blocks();
    }
    
    /**
     * Register blocks with live editing capabilities
     */
    public function register_live_blocks() {
        
        // Check if CMB2 is available
        if (!function_exists('new_cmb2_box')) {
            return; // CMB2 not loaded yet, skip registration
        }
        
        // Hero Section Block
        $this->register_hero_block();
        
        // Property Card Block  
        $this->register_property_card_block();
        
        // Dashboard Section Block
        $this->register_dashboard_section_block();
    }
    
    /**
     * Hero Block with Live Editing
     */
    private function register_hero_block() {
        $cmb = new_cmb2_box(array(
            'id'           => 'villa_live_hero',
            'title'        => __('Live Hero Section', 'migv'),
            'object_types' => array('page', 'post'),
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true,
            'classes'      => 'villa-live-editor-block',
        ));

        $cmb->add_field(array(
            'name' => __('Hero Title', 'migv'),
            'id'   => 'hero_title',
            'type' => 'text',
            'default' => 'Welcome to Villa Community',
            'attributes' => array(
                'data-live-target' => '.hero-title',
                'data-live-property' => 'textContent',
            ),
        ));

        $cmb->add_field(array(
            'name' => __('Hero Subtitle', 'migv'),
            'id'   => 'hero_subtitle',
            'type' => 'textarea_small',
            'default' => 'Your premium property management solution',
            'attributes' => array(
                'data-live-target' => '.hero-subtitle',
                'data-live-property' => 'textContent',
            ),
        ));

        $cmb->add_field(array(
            'name' => __('Background Image', 'migv'),
            'id'   => 'hero_bg_image',
            'type' => 'file',
            'attributes' => array(
                'data-live-target' => '.hero-section',
                'data-live-property' => 'backgroundImage',
                'data-live-format' => 'url({value})',
            ),
        ));

        $cmb->add_field(array(
            'name' => __('Button Text', 'migv'),
            'id'   => 'hero_button_text',
            'type' => 'text',
            'default' => 'Get Started',
            'attributes' => array(
                'data-live-target' => '.hero-button',
                'data-live-property' => 'textContent',
            ),
        ));

        $cmb->add_field(array(
            'name' => __('Button Color', 'migv'),
            'id'   => 'hero_button_color',
            'type' => 'colorpicker',
            'default' => '#007cba',
            'attributes' => array(
                'data-live-target' => '.hero-button',
                'data-live-property' => 'backgroundColor',
            ),
        ));
    }
    
    /**
     * Property Card Block with Live Editing
     */
    private function register_property_card_block() {
        $cmb = new_cmb2_box(array(
            'id'           => 'villa_live_property_card',
            'title'        => __('Live Property Card', 'migv'),
            'object_types' => array('property'),
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true,
            'classes'      => 'villa-live-editor-block',
        ));

        $cmb->add_field(array(
            'name' => __('Card Style', 'migv'),
            'id'   => 'card_style',
            'type' => 'select',
            'options' => array(
                'modern' => __('Modern', 'migv'),
                'classic' => __('Classic', 'migv'),
                'minimal' => __('Minimal', 'migv'),
            ),
            'default' => 'modern',
            'attributes' => array(
                'data-live-target' => '.property-card',
                'data-live-property' => 'className',
                'data-live-format' => 'property-card property-card--{value}',
            ),
        ));

        $cmb->add_field(array(
            'name' => __('Show Price Badge', 'migv'),
            'id'   => 'show_price_badge',
            'type' => 'checkbox',
            'default' => true,
            'attributes' => array(
                'data-live-target' => '.price-badge',
                'data-live-property' => 'display',
                'data-live-format' => '{value ? "block" : "none"}',
            ),
        ));
    }
    
    /**
     * Dashboard Section Block
     */
    private function register_dashboard_section_block() {
        $cmb = new_cmb2_box(array(
            'id'           => 'villa_live_dashboard_section',
            'title'        => __('Live Dashboard Section', 'migv'),
            'object_types' => array('page'),
            'context'      => 'normal',
            'priority'     => 'high',
            'show_names'   => true,
            'classes'      => 'villa-live-editor-block',
        ));

        $cmb->add_field(array(
            'name' => __('Section Layout', 'migv'),
            'id'   => 'section_layout',
            'type' => 'radio',
            'options' => array(
                'sidebar' => __('Sidebar Layout', 'migv'),
                'fullwidth' => __('Full Width', 'migv'),
                'grid' => __('Grid Layout', 'migv'),
            ),
            'default' => 'sidebar',
            'attributes' => array(
                'data-live-target' => '.dashboard-section',
                'data-live-property' => 'className',
                'data-live-format' => 'dashboard-section dashboard-section--{value}',
            ),
        ));
    }
    
    /**
     * Enqueue live editor scripts
     */
    public function enqueue_scripts() {
        if (is_admin() || !current_user_can('edit_posts')) {
            return;
        }
        
        wp_enqueue_script(
            'villa-live-editor',
            plugin_dir_url(__FILE__) . 'assets/villa-live-editor.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_enqueue_style(
            'villa-live-editor',
            plugin_dir_url(__FILE__) . 'assets/villa-live-editor.css',
            array(),
            '1.0.0'
        );
        
        wp_localize_script('villa-live-editor', 'villaLiveEditor', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('villa_live_editor'),
            'postId' => get_the_ID(),
        ));
    }
    
    /**
     * Handle live update AJAX requests
     */
    public function handle_live_update() {
        if (!wp_verify_nonce($_POST['nonce'], 'villa_live_editor')) {
            wp_die('Security check failed');
        }
        
        $post_id = intval($_POST['post_id']);
        $field_id = sanitize_text_field($_POST['field_id']);
        $value = sanitize_text_field($_POST['value']);
        
        // Update the meta field
        update_post_meta($post_id, $field_id, $value);
        
        // Return success response
        wp_send_json_success(array(
            'message' => 'Field updated successfully',
            'field_id' => $field_id,
            'value' => $value,
        ));
    }
    
    /**
     * Render live editor interface
     */
    public function render_live_editor_interface() {
        if (!current_user_can('edit_posts')) {
            return;
        }
        
        ?>
        <div id="villa-live-editor" class="villa-live-editor">
            <div class="live-editor-sidebar">
                <div class="live-editor-header">
                    <h3><?php _e('Live Editor', 'migv'); ?></h3>
                    <button class="toggle-editor"><?php _e('Toggle', 'migv'); ?></button>
                </div>
                
                <div class="live-editor-fields">
                    <!-- Fields will be populated by JavaScript -->
                </div>
                
                <div class="live-editor-breakpoints">
                    <h4><?php _e('Preview Breakpoints', 'migv'); ?></h4>
                    <div class="breakpoint-controls">
                        <button data-breakpoint="mobile">📱</button>
                        <button data-breakpoint="tablet">📱</button>
                        <button data-breakpoint="desktop">🖥️</button>
                    </div>
                </div>
            </div>
            
            <div class="live-editor-overlay">
                <!-- Click targets for live editing -->
            </div>
        </div>
        
        <style>
        .villa-live-editor {
            position: fixed;
            top: 0;
            right: 0;
            width: 300px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        
        .villa-live-editor.active {
            transform: translateX(0);
        }
        
        .live-editor-sidebar {
            padding: 20px;
            height: 100%;
            overflow-y: auto;
        }
        
        .breakpoint-controls button {
            margin: 5px;
            padding: 10px;
            border: none;
            background: #f0f0f0;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .breakpoint-controls button.active {
            background: #007cba;
            color: white;
        }
        </style>
        <?php
    }
}

// Initialize the live editor
new Villa_Live_Editor();

/**
 * Add live editor interface to frontend
 */
add_action('wp_footer', function() {
    if (current_user_can('edit_posts')) {
        $editor = new Villa_Live_Editor();
        $editor->render_live_editor_interface();
    }
});
