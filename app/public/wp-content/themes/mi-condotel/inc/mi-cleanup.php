<?php
/**
 * Cleanup Script
 * 
 * Removes all data from custom post types and taxonomies
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class to handle cleanup operations
 */
class MI_Cleanup {
    /**
     * Log of cleanup actions
     */
    private $log = [];
    
    /**
     * Delete all posts of a specific post type
     */
    public function delete_all_posts($post_type) {
        $this->log[] = "Starting deletion of all {$post_type} posts...";
        
        $args = array(
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'post_status' => 'any',
            'fields' => 'ids',
        );
        
        $posts = get_posts($args);
        
        if (empty($posts)) {
            $this->log[] = "No {$post_type} posts found to delete.";
            return 0;
        }
        
        $count = 0;
        foreach ($posts as $post_id) {
            $result = wp_delete_post($post_id, true); // Force delete (skip trash)
            if ($result) {
                $count++;
            }
        }
        
        $this->log[] = "Deleted {$count} {$post_type} posts.";
        return $count;
    }
    
    /**
     * Delete all terms from a taxonomy
     */
    public function delete_all_terms($taxonomy) {
        $this->log[] = "Starting deletion of all {$taxonomy} terms...";
        
        $terms = get_terms(array(
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
            'fields' => 'ids',
        ));
        
        if (empty($terms) || is_wp_error($terms)) {
            $this->log[] = "No {$taxonomy} terms found to delete.";
            return 0;
        }
        
        $count = 0;
        foreach ($terms as $term_id) {
            $result = wp_delete_term($term_id, $taxonomy);
            if ($result && !is_wp_error($result)) {
                $count++;
            }
        }
        
        $this->log[] = "Deleted {$count} {$taxonomy} terms.";
        return $count;
    }
    
    /**
     * Clean up all custom content
     */
    public function cleanup_all() {
        // Delete all custom post types
        $post_types = array('property', 'business', 'article', 'user_profile');
        $total_posts = 0;
        
        foreach ($post_types as $post_type) {
            $count = $this->delete_all_posts($post_type);
            $total_posts += $count;
        }
        
        // Delete all custom taxonomies
        $taxonomies = array('property_type', 'location', 'amenity', 'business_type', 'article_type', 'user_type');
        $total_terms = 0;
        
        foreach ($taxonomies as $taxonomy) {
            $count = $this->delete_all_terms($taxonomy);
            $total_terms += $count;
        }
        
        $this->log[] = "Cleanup complete. Deleted {$total_posts} posts and {$total_terms} taxonomy terms.";
        return array(
            'posts' => $total_posts,
            'terms' => $total_terms,
        );
    }
    
    /**
     * Get the cleanup log
     */
    public function get_log() {
        return $this->log;
    }
}

/**
 * Admin page for cleanup operations
 */
function mi_cleanup_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $cleanup = new MI_Cleanup();
    $message = '';
    $log = array();
    
    // Handle form submission
    if (isset($_POST['mi_run_cleanup'])) {
        check_admin_referer('mi_cleanup_action', 'mi_cleanup_nonce');
        
        $result = $cleanup->cleanup_all();
        $message = "Cleanup completed. Deleted {$result['posts']} posts and {$result['terms']} taxonomy terms.";
        $log = $cleanup->get_log();
    }
    
    // Display the admin page
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php if (!empty($message)): ?>
            <div class="notice notice-success is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Cleanup Operations</h2>
            <p><strong>Warning:</strong> This will permanently delete all content from custom post types and taxonomies. This action cannot be undone.</p>
            
            <form method="post">
                <?php wp_nonce_field('mi_cleanup_action', 'mi_cleanup_nonce'); ?>
                
                <p class="submit">
                    <input type="submit" name="mi_run_cleanup" class="button button-primary" value="Run Cleanup" onclick="return confirm('Are you sure you want to delete all custom content? This cannot be undone.');" />
                </p>
            </form>
        </div>
        
        <?php if (!empty($log)): ?>
            <div class="card">
                <h2>Cleanup Log</h2>
                <div class="mi-cleanup-log" style="max-height: 300px; overflow-y: scroll; padding: 10px; background: #f8f8f8; border: 1px solid #ddd;">
                    <?php foreach ($log as $entry): ?>
                        <div><?php echo esc_html($entry); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add cleanup tool to admin menu
 */
function mi_add_cleanup_menu() {
    add_submenu_page(
        'tools.php',
        'Content Cleanup',
        'Content Cleanup',
        'manage_options',
        'mi-cleanup',
        'mi_cleanup_admin_page'
    );
}
add_action('admin_menu', 'mi_add_cleanup_menu');
