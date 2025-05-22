<?php
/**
 * Export Script for Custom Post Types
 * 
 * This file provides instructions for exporting custom post types
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add export script page to admin menu
 */
function mi_add_export_script_menu() {
    add_submenu_page(
        'tools.php',
        'Export CPTs',
        'Export CPTs',
        'export',
        'mi-export-cpts',
        'mi_export_cpts_page'
    );
}
add_action('admin_menu', 'mi_add_export_script_menu');

/**
 * Display the export CPTs page
 */
function mi_export_cpts_page() {
    // Check user capabilities
    if (!current_user_can('export')) {
        return;
    }
    
    $message = '';
    
    // Handle form submission
    if (isset($_POST['mi_export_cpts'])) {
        check_admin_referer('mi_export_cpts_action', 'mi_export_cpts_nonce');
        
        // Set up export options
        $post_types = array();
        if (isset($_POST['export_property']) && $_POST['export_property'] == '1') {
            $post_types[] = 'property';
        }
        if (isset($_POST['export_business']) && $_POST['export_business'] == '1') {
            $post_types[] = 'business';
        }
        if (isset($_POST['export_article']) && $_POST['export_article'] == '1') {
            $post_types[] = 'article';
        }
        if (isset($_POST['export_user_profile']) && $_POST['export_user_profile'] == '1') {
            $post_types[] = 'user_profile';
        }
        
        if (empty($post_types)) {
            $message = 'Please select at least one post type to export.';
        } else {
            // Generate export file
            $args = array(
                'content' => 'custom',
                'author' => false,
                'category' => false,
                'start_date' => false,
                'end_date' => false,
                'status' => 'any',
                'post_type' => $post_types,
            );
            
            // Load WordPress export API
            if (!function_exists('export_wp')) {
                require_once(ABSPATH . 'wp-admin/includes/export.php');
            }
            
            // Get export data
            $export_data = export_wp($args);
            
            // Set headers for download
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=villa-community-export-' . date('Y-m-d') . '.xml');
            header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
            
            // Output export data
            echo $export_data;
            exit;
        }
    }
    
    // Display the export form
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <?php if (!empty($message)): ?>
            <div class="notice notice-error is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Export Custom Post Types</h2>
            <p>Use this tool to export your custom post types to an XML file that you can import into another WordPress site.</p>
            
            <form method="post">
                <?php wp_nonce_field('mi_export_cpts_action', 'mi_export_cpts_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">Post Types to Export</th>
                        <td>
                            <label>
                                <input type="checkbox" name="export_property" value="1" checked />
                                Properties
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="export_business" value="1" checked />
                                Businesses
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="export_article" value="1" checked />
                                Articles
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="export_user_profile" value="1" checked />
                                User Profiles
                            </label>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="mi_export_cpts" class="button button-primary" value="Download Export File" />
                </p>
            </form>
        </div>
        
        <div class="card">
            <h2>Import Instructions</h2>
            <p>After downloading the export file, you can import it into your new site:</p>
            
            <ol>
                <li>Go to <a href="<?php echo esc_url(admin_url('import.php')); ?>">Tools &rarr; Import</a> on your new site</li>
                <li>Click "WordPress" (install the importer if prompted)</li>
                <li>Upload the XML file you downloaded</li>
                <li>Follow the prompts to complete the import</li>
            </ol>
            
            <p><strong>Note:</strong> The standard WordPress importer doesn't handle Carbon Fields meta data. You'll need to manually recreate this data after importing.</p>
        </div>
    </div>
    <?php
}
