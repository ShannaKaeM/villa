<?php
/**
 * Export Helper
 * 
 * Provides a UI for exporting custom post types using WordPress's built-in export
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add export helper to admin menu
 */
function mi_add_export_helper_menu() {
    add_submenu_page(
        'tools.php',
        'Export Helper',
        'Export Helper',
        'export',
        'mi-export-helper',
        'mi_export_helper_admin_page'
    );
}
add_action('admin_menu', 'mi_add_export_helper_menu');

/**
 * Admin page for export helper
 */
function mi_export_helper_admin_page() {
    // Check user capabilities
    if (!current_user_can('export')) {
        return;
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="card">
            <h2>Export Custom Post Types</h2>
            <p>Use this tool to easily export your custom post types.</p>
            
            <p>To export your custom post types:</p>
            <ol>
                <li>Go to <a href="<?php echo esc_url(admin_url('export.php')); ?>">Tools &rarr; Export</a></li>
                <li>Select "All content" or choose a specific custom post type</li>
                <li>Click "Download Export File" to get an XML file with your content</li>
            </ol>
            
            <p><a href="<?php echo esc_url(admin_url('export.php')); ?>" class="button button-primary">Go to Export Tool</a></p>
        </div>
        
        <div class="card">
            <h2>Import Custom Post Types</h2>
            <p>After exporting, you can import the content into your new site:</p>
            
            <ol>
                <li>Go to <a href="<?php echo esc_url(admin_url('import.php')); ?>">Tools &rarr; Import</a></li>
                <li>Click "WordPress" (install the importer if prompted)</li>
                <li>Upload the XML file you exported</li>
                <li>Map the authors as needed</li>
                <li>Choose whether to import attachments</li>
                <li>Click "Submit" to import your content</li>
            </ol>
            
            <p><a href="<?php echo esc_url(admin_url('import.php')); ?>" class="button button-primary">Go to Import Tool</a></p>
        </div>
        
        <div class="card">
            <h2>Important Notes</h2>
            <p><strong>Carbon Fields Data:</strong> The standard WordPress exporter/importer doesn't handle Carbon Fields meta data. You'll need to manually recreate this data or use a specialized plugin.</p>
            
            <p><strong>Taxonomies:</strong> While the export will include taxonomy assignments, you'll need to ensure the taxonomies exist in the target site before importing.</p>
            
            <p><strong>Media:</strong> If your posts reference media files, you can choose to import these during the import process.</p>
        </div>
    </div>
    <?php
}

/**
 * Add custom post types to export options
 */
function mi_add_cpts_to_export_options($post_types) {
    $custom_post_types = array('property', 'business', 'article', 'user_profile');
    
    foreach ($custom_post_types as $cpt) {
        if (post_type_exists($cpt)) {
            $post_types[$cpt] = get_post_type_object($cpt)->labels->name;
        }
    }
    
    return $post_types;
}
add_filter('export_post_types', 'mi_add_cpts_to_export_options');
