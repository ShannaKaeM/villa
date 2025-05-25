<?php
/**
 * Site Migration Tool
 * 
 * Migrates data from an existing WordPress site to this site
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class to handle site migration
 */
class MI_Site_Migration {
    /**
     * Source site database credentials
     */
    private $source_db_host;
    private $source_db_name;
    private $source_db_user;
    private $source_db_pass;
    private $source_table_prefix;
    
    /**
     * Source site path
     */
    private $source_site_path;
    
    /**
     * Log of migration actions
     */
    private $log = [];
    
    /**
     * Constructor
     */
    public function __construct($config = array()) {
        // Set default values
        $defaults = array(
            'db_host' => 'localhost',
            'db_name' => '',
            'db_user' => '',
            'db_pass' => '',
            'table_prefix' => 'wp_',
            'site_path' => '',
        );
        
        $config = wp_parse_args($config, $defaults);
        
        $this->source_db_host = $config['db_host'];
        $this->source_db_name = $config['db_name'];
        $this->source_db_user = $config['db_user'];
        $this->source_db_pass = $config['db_pass'];
        $this->source_table_prefix = $config['table_prefix'];
        $this->source_site_path = $config['site_path'];
    }
    
    /**
     * Connect to source database
     */
    private function connect_to_source_db() {
        // If no database credentials provided, return false
        if (empty($this->source_db_name) || empty($this->source_db_user)) {
            $this->log[] = 'Error: Source database credentials not provided.';
            return false;
        }
        
        // Create a new database connection
        $conn = new mysqli($this->source_db_host, $this->source_db_user, $this->source_db_pass, $this->source_db_name);
        
        // Check connection
        if ($conn->connect_error) {
            $this->log[] = 'Error: Connection to source database failed - ' . $conn->connect_error;
            return false;
        }
        
        return $conn;
    }
    
    /**
     * Migrate taxonomy terms
     */
    public function migrate_taxonomies() {
        $conn = $this->connect_to_source_db();
        if (!$conn) {
            return false;
        }
        
        $this->log[] = 'Starting taxonomy migration...';
        
        // Get taxonomies to migrate
        $taxonomies = array('property_type', 'location', 'amenity', 'business_type', 'article_type', 'user_type');
        $total_terms = 0;
        
        foreach ($taxonomies as $taxonomy) {
            $this->log[] = "Migrating {$taxonomy} terms...";
            
            // Get terms from source database
            $query = "
                SELECT t.term_id, t.name, t.slug, tt.description, tt.parent
                FROM {$this->source_table_prefix}terms t
                JOIN {$this->source_table_prefix}term_taxonomy tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy = '{$taxonomy}'
                ORDER BY tt.parent ASC
            ";
            
            $result = $conn->query($query);
            
            if (!$result) {
                $this->log[] = "Error fetching {$taxonomy} terms: " . $conn->error;
                continue;
            }
            
            if ($result->num_rows === 0) {
                $this->log[] = "No {$taxonomy} terms found in source database.";
                continue;
            }
            
            // Store term ID mappings (old ID => new ID)
            $term_id_map = array();
            
            // First pass: insert terms with no parent
            while ($row = $result->fetch_assoc()) {
                // Skip if term has a parent (will handle in second pass)
                if ($row['parent'] > 0) {
                    continue;
                }
                
                // Check if term already exists
                $existing_term = term_exists($row['name'], $taxonomy);
                
                if ($existing_term) {
                    // Term already exists, update it
                    $args = array(
                        'slug' => $row['slug'],
                        'description' => $row['description']
                    );
                    
                    wp_update_term($existing_term['term_id'], $taxonomy, $args);
                    $term_id_map[$row['term_id']] = $existing_term['term_id'];
                    $this->log[] = "Updated existing term: {$row['name']} in {$taxonomy}";
                } else {
                    // Insert new term
                    $args = array(
                        'slug' => $row['slug'],
                        'description' => $row['description']
                    );
                    
                    $new_term = wp_insert_term($row['name'], $taxonomy, $args);
                    
                    if (!is_wp_error($new_term)) {
                        $term_id_map[$row['term_id']] = $new_term['term_id'];
                        $total_terms++;
                        $this->log[] = "Imported term: {$row['name']} to {$taxonomy}";
                    } else {
                        $this->log[] = "Error importing term {$row['name']}: " . $new_term->get_error_message();
                    }
                }
            }
            
            // Reset result pointer
            $result->data_seek(0);
            
            // Second pass: insert terms with parents
            while ($row = $result->fetch_assoc()) {
                // Skip if term has no parent (already handled)
                if ($row['parent'] == 0) {
                    continue;
                }
                
                // Skip if parent term wasn't imported
                if (!isset($term_id_map[$row['parent']])) {
                    $this->log[] = "Skipping term {$row['name']} because parent term (ID: {$row['parent']}) wasn't imported.";
                    continue;
                }
                
                // Map to new parent ID
                $new_parent_id = $term_id_map[$row['parent']];
                
                // Check if term already exists
                $existing_term = term_exists($row['name'], $taxonomy, $new_parent_id);
                
                if ($existing_term) {
                    // Term already exists, update it
                    $args = array(
                        'slug' => $row['slug'],
                        'description' => $row['description'],
                        'parent' => $new_parent_id
                    );
                    
                    wp_update_term($existing_term['term_id'], $taxonomy, $args);
                    $term_id_map[$row['term_id']] = $existing_term['term_id'];
                    $this->log[] = "Updated existing term: {$row['name']} in {$taxonomy}";
                } else {
                    // Insert new term
                    $args = array(
                        'slug' => $row['slug'],
                        'description' => $row['description'],
                        'parent' => $new_parent_id
                    );
                    
                    $new_term = wp_insert_term($row['name'], $taxonomy, $args);
                    
                    if (!is_wp_error($new_term)) {
                        $term_id_map[$row['term_id']] = $new_term['term_id'];
                        $total_terms++;
                        $this->log[] = "Imported term: {$row['name']} to {$taxonomy} with parent ID: {$new_parent_id}";
                    } else {
                        $this->log[] = "Error importing term {$row['name']}: " . $new_term->get_error_message();
                    }
                }
            }
            
            $result->free();
        }
        
        $conn->close();
        $this->log[] = "Taxonomy migration complete. Imported {$total_terms} terms.";
        return true;
    }
    
    /**
     * Migrate posts and their meta data
     */
    public function migrate_posts($post_type) {
        $conn = $this->connect_to_source_db();
        if (!$conn) {
            return false;
        }
        
        $this->log[] = "Starting {$post_type} migration...";
        
        // Get posts from source database
        $query = "
            SELECT p.ID, p.post_title, p.post_content, p.post_excerpt, p.post_status, p.post_date, p.post_modified, p.post_author
            FROM {$this->source_table_prefix}posts p
            WHERE p.post_type = '{$post_type}' AND p.post_status IN ('publish', 'draft')
            ORDER BY p.ID ASC
        ";
        
        $result = $conn->query($query);
        
        if (!$result) {
            $this->log[] = "Error fetching {$post_type} posts: " . $conn->error;
            $conn->close();
            return false;
        }
        
        if ($result->num_rows === 0) {
            $this->log[] = "No {$post_type} posts found in source database.";
            $conn->close();
            return false;
        }
        
        // Store post ID mappings (old ID => new ID)
        $post_id_map = array();
        $total_posts = 0;
        
        while ($row = $result->fetch_assoc()) {
            $source_post_id = $row['ID'];
            
            // Check if post already exists by title
            $existing_post = get_page_by_title($row['post_title'], OBJECT, $post_type);
            
            if ($existing_post) {
                $this->log[] = "Post already exists: {$row['post_title']}";
                $post_id_map[$source_post_id] = $existing_post->ID;
                continue;
            }
            
            // Create post
            $post_data = array(
                'post_title'    => $row['post_title'],
                'post_content'  => $row['post_content'],
                'post_excerpt'  => $row['post_excerpt'],
                'post_status'   => $row['post_status'],
                'post_date'     => $row['post_date'],
                'post_modified' => $row['post_modified'],
                'post_author'   => get_current_user_id(), // Use current user as author
                'post_type'     => $post_type,
            );
            
            $new_post_id = wp_insert_post($post_data);
            
            if (!$new_post_id || is_wp_error($new_post_id)) {
                $this->log[] = "Error creating post: {$row['post_title']}";
                continue;
            }
            
            $post_id_map[$source_post_id] = $new_post_id;
            $total_posts++;
            $this->log[] = "Imported post: {$row['post_title']} (ID: {$new_post_id})";
            
            // Get post meta
            $meta_query = "
                SELECT meta_key, meta_value
                FROM {$this->source_table_prefix}postmeta
                WHERE post_id = {$source_post_id}
            ";
            
            $meta_result = $conn->query($meta_query);
            
            if (!$meta_result) {
                $this->log[] = "Error fetching meta for post ID {$source_post_id}: " . $conn->error;
                continue;
            }
            
            // Import post meta
            $carbon_fields_meta = array();
            
            while ($meta_row = $meta_result->fetch_assoc()) {
                $meta_key = $meta_row['meta_key'];
                $meta_value = $meta_row['meta_value'];
                
                // Skip WordPress internal meta
                if (in_array($meta_key, array('_edit_lock', '_edit_last'))) {
                    continue;
                }
                
                // Handle Carbon Fields meta (they start with _)
                if (strpos($meta_key, '_') === 0 && strpos($meta_key, '_carbon_') !== false) {
                    $carbon_key = substr($meta_key, 1); // Remove leading underscore
                    $carbon_fields_meta[$carbon_key] = $meta_value;
                } else {
                    // Regular meta
                    update_post_meta($new_post_id, $meta_key, $meta_value);
                }
            }
            
            $meta_result->free();
            
            // Set Carbon Fields meta
            foreach ($carbon_fields_meta as $key => $value) {
                carbon_set_post_meta($new_post_id, $key, maybe_unserialize($value));
            }
            
            // Get taxonomies for this post type
            $taxonomies = get_object_taxonomies($post_type);
            
            foreach ($taxonomies as $taxonomy) {
                // Get terms for this post
                $term_query = "
                    SELECT tt.term_id
                    FROM {$this->source_table_prefix}term_relationships tr
                    JOIN {$this->source_table_prefix}term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE tr.object_id = {$source_post_id} AND tt.taxonomy = '{$taxonomy}'
                ";
                
                $term_result = $conn->query($term_query);
                
                if (!$term_result) {
                    $this->log[] = "Error fetching terms for post ID {$source_post_id}: " . $conn->error;
                    continue;
                }
                
                $term_ids = array();
                
                while ($term_row = $term_result->fetch_assoc()) {
                    $source_term_id = $term_row['term_id'];
                    
                    // Get term by name
                    $term_name_query = "
                        SELECT name
                        FROM {$this->source_table_prefix}terms
                        WHERE term_id = {$source_term_id}
                    ";
                    
                    $term_name_result = $conn->query($term_name_query);
                    
                    if ($term_name_result && $term_name_row = $term_name_result->fetch_assoc()) {
                        $term_name = $term_name_row['name'];
                        $term = get_term_by('name', $term_name, $taxonomy);
                        
                        if ($term) {
                            $term_ids[] = $term->term_id;
                        }
                        
                        $term_name_result->free();
                    }
                }
                
                $term_result->free();
                
                // Set terms for the post
                if (!empty($term_ids)) {
                    wp_set_object_terms($new_post_id, $term_ids, $taxonomy);
                }
            }
            
            // Handle featured image
            $thumbnail_id_query = "
                SELECT meta_value
                FROM {$this->source_table_prefix}postmeta
                WHERE post_id = {$source_post_id} AND meta_key = '_thumbnail_id'
            ";
            
            $thumbnail_result = $conn->query($thumbnail_id_query);
            
            if ($thumbnail_result && $thumbnail_row = $thumbnail_result->fetch_assoc()) {
                $source_thumbnail_id = $thumbnail_row['meta_value'];
                
                // TODO: Copy the actual image file if needed
                // For now, we'll just note that a featured image exists
                $this->log[] = "Post {$row['post_title']} has a featured image (ID: {$source_thumbnail_id}) that needs to be manually copied.";
                
                $thumbnail_result->free();
            }
        }
        
        $result->free();
        $conn->close();
        
        $this->log[] = "{$post_type} migration complete. Imported {$total_posts} posts.";
        return true;
    }
    
    /**
     * Run the full migration
     */
    public function run_migration() {
        // Migrate taxonomies first
        $this->migrate_taxonomies();
        
        // Migrate post types
        $post_types = array('property', 'business', 'article', 'user_profile');
        
        foreach ($post_types as $post_type) {
            $this->migrate_posts($post_type);
        }
        
        $this->log[] = "Migration complete.";
        return true;
    }
    
    /**
     * Get the migration log
     */
    public function get_log() {
        return $this->log;
    }
}

/**
 * Admin page for site migration
 */
function mi_site_migration_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $message = '';
    $log = array();
    
    // Handle form submission
    if (isset($_POST['mi_run_migration'])) {
        check_admin_referer('mi_migration_action', 'mi_migration_nonce');
        
        $config = array(
            'db_host' => isset($_POST['source_db_host']) ? sanitize_text_field($_POST['source_db_host']) : 'localhost',
            'db_name' => isset($_POST['source_db_name']) ? sanitize_text_field($_POST['source_db_name']) : '',
            'db_user' => isset($_POST['source_db_user']) ? sanitize_text_field($_POST['source_db_user']) : '',
            'db_pass' => isset($_POST['source_db_pass']) ? sanitize_text_field($_POST['source_db_pass']) : '',
            'table_prefix' => isset($_POST['source_table_prefix']) ? sanitize_text_field($_POST['source_table_prefix']) : 'wp_',
            'site_path' => isset($_POST['source_site_path']) ? sanitize_text_field($_POST['source_site_path']) : '',
        );
        
        $migration = new MI_Site_Migration($config);
        
        if (isset($_POST['migrate_taxonomies']) && $_POST['migrate_taxonomies'] == '1') {
            $migration->migrate_taxonomies();
        }
        
        if (isset($_POST['migrate_properties']) && $_POST['migrate_properties'] == '1') {
            $migration->migrate_posts('property');
        }
        
        if (isset($_POST['migrate_businesses']) && $_POST['migrate_businesses'] == '1') {
            $migration->migrate_posts('business');
        }
        
        if (isset($_POST['migrate_articles']) && $_POST['migrate_articles'] == '1') {
            $migration->migrate_posts('article');
        }
        
        if (isset($_POST['migrate_user_profiles']) && $_POST['migrate_user_profiles'] == '1') {
            $migration->migrate_posts('user_profile');
        }
        
        $message = 'Migration tasks completed.';
        $log = $migration->get_log();
    }
    
    // Get the database credentials from wp-config.php of the source site
    $source_site_path = '/Users/shannamiddleton/Local Drive Mac/mi agency/miProjects/villa-community/app/public/';
    $source_config_path = $source_site_path . 'wp-config.php';
    
    $db_host = 'localhost';
    $db_name = '';
    $db_user = '';
    $db_pass = '';
    $table_prefix = 'wp_';
    
    if (file_exists($source_config_path)) {
        $config_content = file_get_contents($source_config_path);
        
        // Extract database credentials
        preg_match("/define\(\s*'DB_NAME',\s*'([^']*)'\s*\)/", $config_content, $db_name_matches);
        preg_match("/define\(\s*'DB_USER',\s*'([^']*)'\s*\)/", $config_content, $db_user_matches);
        preg_match("/define\(\s*'DB_PASSWORD',\s*'([^']*)'\s*\)/", $config_content, $db_pass_matches);
        preg_match("/define\(\s*'DB_HOST',\s*'([^']*)'\s*\)/", $config_content, $db_host_matches);
        preg_match("/\\\$table_prefix\s*=\s*'([^']*)'/", $config_content, $table_prefix_matches);
        
        if (!empty($db_name_matches[1])) $db_name = $db_name_matches[1];
        if (!empty($db_user_matches[1])) $db_user = $db_user_matches[1];
        if (!empty($db_pass_matches[1])) $db_pass = $db_pass_matches[1];
        if (!empty($db_host_matches[1])) $db_host = $db_host_matches[1];
        if (!empty($table_prefix_matches[1])) $table_prefix = $table_prefix_matches[1];
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
            <h2>Site Migration Tool</h2>
            <p>Use this tool to migrate data from your existing WordPress site.</p>
            
            <form method="post">
                <?php wp_nonce_field('mi_migration_action', 'mi_migration_nonce'); ?>
                
                <h3>Source Database Connection</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">Database Host</th>
                        <td>
                            <input type="text" name="source_db_host" value="<?php echo esc_attr($db_host); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Database Name</th>
                        <td>
                            <input type="text" name="source_db_name" value="<?php echo esc_attr($db_name); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Database User</th>
                        <td>
                            <input type="text" name="source_db_user" value="<?php echo esc_attr($db_user); ?>" class="regular-text" required />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Database Password</th>
                        <td>
                            <input type="password" name="source_db_pass" value="<?php echo esc_attr($db_pass); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Table Prefix</th>
                        <td>
                            <input type="text" name="source_table_prefix" value="<?php echo esc_attr($table_prefix); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Source Site Path</th>
                        <td>
                            <input type="text" name="source_site_path" value="<?php echo esc_attr($source_site_path); ?>" class="regular-text" />
                            <p class="description">Full path to the source WordPress installation (for file copying)</p>
                        </td>
                    </tr>
                </table>
                
                <h3>Migration Options</h3>
                <table class="form-table">
                    <tr>
                        <th scope="row">Content to Migrate</th>
                        <td>
                            <label>
                                <input type="checkbox" name="migrate_taxonomies" value="1" checked />
                                Migrate Taxonomies
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="migrate_properties" value="1" checked />
                                Migrate Properties
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="migrate_businesses" value="1" checked />
                                Migrate Businesses
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="migrate_articles" value="1" checked />
                                Migrate Articles
                            </label><br>
                            
                            <label>
                                <input type="checkbox" name="migrate_user_profiles" value="1" checked />
                                Migrate User Profiles
                            </label>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="mi_run_migration" class="button button-primary" value="Run Migration" />
                </p>
            </form>
        </div>
        
        <?php if (!empty($log)): ?>
            <div class="card">
                <h2>Migration Log</h2>
                <div class="mi-migration-log" style="max-height: 400px; overflow-y: scroll; padding: 10px; background: #f8f8f8; border: 1px solid #ddd;">
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
 * Add site migration to admin menu
 */
function mi_add_site_migration_menu() {
    add_submenu_page(
        'tools.php',
        'Site Migration',
        'Site Migration',
        'manage_options',
        'mi-site-migration',
        'mi_site_migration_admin_page'
    );
}
add_action('admin_menu', 'mi_add_site_migration_menu');
