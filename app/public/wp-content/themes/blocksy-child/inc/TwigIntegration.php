<?php
namespace MiAgency;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use Twig\Extension\DebugExtension;

class TwigIntegration {
    private static $instance = null;
    private $twig;
    private $loader;
    
    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Private constructor
     */
    private function __construct() {
        $this->initializeTwig();
        $this->addWordPressFunctions();
        $this->addThemeFunctions();
    }
    
    /**
     * Initialize Twig environment
     */
    private function initializeTwig() {
        // Set up template directories
        $templateDirs = [
            get_stylesheet_directory() . '/templates',
            get_stylesheet_directory() . '/blocks',
            get_stylesheet_directory() . '/partials'
        ];
        
        // Create directories if they don't exist
        foreach ($templateDirs as $dir) {
            if (!file_exists($dir)) {
                wp_mkdir_p($dir);
            }
        }
        
        // Initialize loader
        $this->loader = new FilesystemLoader($templateDirs);
        
        // Initialize Twig environment
        $this->twig = new Environment($this->loader, [
            'cache' => WP_CONTENT_DIR . '/cache/twig',
            'auto_reload' => true,
            'debug' => WP_DEBUG
        ]);
        
        // Add debug extension if in debug mode
        if (WP_DEBUG) {
            $this->twig->addExtension(new DebugExtension());
        }
    }
    
    /**
     * Add WordPress functions to Twig
     */
    private function addWordPressFunctions() {
        // Common WordPress functions
        $wpFunctions = [
            'wp_head', 'wp_footer', 'body_class', 'post_class',
            'get_header', 'get_footer', 'get_sidebar',
            'wp_title', 'the_title', 'the_content', 'the_excerpt',
            'get_permalink', 'home_url', 'site_url',
            'wp_nav_menu', 'dynamic_sidebar',
            'is_home', 'is_front_page', 'is_page', 'is_single',
            'get_post_meta', 'get_option', 'get_theme_mod',
            'esc_html', 'esc_attr', 'esc_url', 'wp_kses_post',
            '__', '_e', 'esc_html__', 'esc_html_e'
        ];
        
        foreach ($wpFunctions as $function) {
            $this->twig->addFunction(new TwigFunction($function, $function));
        }
        
        // Add WordPress functions that need special handling
        $this->twig->addFunction(new TwigFunction('get_template_part', function($slug, $name = null) {
            ob_start();
            get_template_part($slug, $name);
            return ob_get_clean();
        }));
        
        $this->twig->addFunction(new TwigFunction('wp_get_attachment_image', 'wp_get_attachment_image', ['is_safe' => ['html']]));
        $this->twig->addFunction(new TwigFunction('get_field', function($field, $post_id = null) {
            if (function_exists('get_field')) {
                return get_field($field, $post_id);
            }
            return null;
        }));
    }
    
    /**
     * Add theme-specific functions
     */
    private function addThemeFunctions() {
        // Add theme color function
        $this->twig->addFunction(new TwigFunction('theme_color', function($color) {
            return "var(--theme-{$color})";
        }));
        
        // Add theme spacing function
        $this->twig->addFunction(new TwigFunction('theme_spacing', function($size) {
            return "var(--wp--preset--spacing--{$size})";
        }));
        
        // Add Blocksy-specific functions
        $this->twig->addFunction(new TwigFunction('blocksy_color', function($palette) {
            return "var(--paletteColor{$palette})";
        }));
        
        // Add semantic color function
        $this->twig->addFunction(new TwigFunction('semantic_color', function($name) {
            $semanticColors = [
                'text-primary' => 'var(--theme-text-primary)',
                'text-secondary' => 'var(--theme-text-secondary)',
                'bg-primary' => 'var(--theme-bg-primary)',
                'bg-secondary' => 'var(--theme-bg-secondary)',
                'border' => 'var(--theme-border)',
                'primary' => 'var(--theme-primary)',
                'secondary' => 'var(--theme-secondary)',
                'neutral' => 'var(--theme-neutral)'
            ];
            
            return $semanticColors[$name] ?? "var(--theme-{$name})";
        }));
        
        // Add component include function
        $this->twig->addFunction(new TwigFunction('component', function($name, $data = []) {
            return $this->render("components/{$name}.twig", $data);
        }, ['is_safe' => ['html']]));
    }
    
    /**
     * Render a Twig template
     */
    public function render($template, $data = []) {
        try {
            // Add global data
            $data = array_merge($this->getGlobalData(), $data);
            
            // Ensure template has .twig extension
            if (!str_ends_with($template, '.twig')) {
                $template .= '.twig';
            }
            
            return $this->twig->render($template, $data);
        } catch (\Exception $e) {
            if (WP_DEBUG) {
                return '<div class="twig-error" style="background: #f00; color: #fff; padding: 20px;">' . 
                       'Twig Error: ' . esc_html($e->getMessage()) . '</div>';
            }
            return '';
        }
    }
    
    /**
     * Display a Twig template
     */
    public function display($template, $data = []) {
        echo $this->render($template, $data);
    }
    
    /**
     * Get global template data
     */
    private function getGlobalData() {
        return [
            'site' => [
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => home_url(),
                'charset' => get_bloginfo('charset'),
                'language' => get_bloginfo('language')
            ],
            'theme' => [
                'url' => get_stylesheet_directory_uri(),
                'path' => get_stylesheet_directory()
            ],
            'is' => [
                'home' => is_home(),
                'front_page' => is_front_page(),
                'single' => is_single(),
                'page' => is_page(),
                'archive' => is_archive(),
                'search' => is_search(),
                '404' => is_404()
            ],
            'user' => wp_get_current_user()
        ];
    }
    
    /**
     * Get Twig environment (for extensions)
     */
    public function getEnvironment() {
        return $this->twig;
    }
}

// Helper function for easy access
function twig() {
    return TwigIntegration::getInstance();
}
