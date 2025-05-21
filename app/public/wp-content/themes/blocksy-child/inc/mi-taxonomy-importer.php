<?php
/**
 * Taxonomy Importer
 * 
 * Imports taxonomy terms from a CSV file
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import taxonomy terms from CSV
 */
class MI_Taxonomy_Importer {
    /**
     * CSV file path
     */
    private $csv_file;
    
    /**
     * Log of import actions
     */
    private $log = [];
    
    /**
     * Constructor
     */
    public function __construct($csv_file = '') {
        // Default to the Categories.csv in the docs/SITE DATA directory
        if (empty($csv_file)) {
            $csv_file = get_stylesheet_directory() . '/docs/SITE DATA/Categories.csv';
        }
        
        $this->csv_file = $csv_file;
    }
    
    /**
     * Run the import
     */
    public function import() {
        // Check if file exists
        if (!file_exists($this->csv_file)) {
            $this->log[] = 'Error: CSV file not found at ' . $this->csv_file;
            return false;
        }
        
        // Open the CSV file
        $handle = fopen($this->csv_file, 'r');
        if (!$handle) {
            $this->log[] = 'Error: Could not open CSV file';
            return false;
        }
        
        // Get the header row
        $header = fgetcsv($handle, 1000, ',');
        if (!$header) {
            $this->log[] = 'Error: Could not read CSV header';
            fclose($handle);
            return false;
        }
        
        // Process each row
        $count = 0;
        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $row = array_combine($header, $data);
            
            // Skip if no taxonomy or name
            if (empty($row['taxonomy']) || empty($row['name'])) {
                $this->log[] = 'Warning: Skipping row with missing taxonomy or name';
                continue;
            }
            
            // Get parent term ID if specified
            $parent = 0;
            if (!empty($row['parent'])) {
                $parent_term = get_term_by('name', $row['parent'], $row['taxonomy']);
                if ($parent_term) {
                    $parent = $parent_term->term_id;
                }
            }
            
            // Prepare term args
            $args = array(
                'description' => isset($row['description']) ? $row['description'] : '',
                'slug' => isset($row['slug']) ? $row['slug'] : '',
                'parent' => $parent
            );
            
            // Check if term exists
            $existing_term = term_exists($row['name'], $row['taxonomy'], $parent);
            
            if ($existing_term) {
                // Update existing term
                wp_update_term($existing_term['term_id'], $row['taxonomy'], $args);
                $this->log[] = 'Updated term: ' . $row['name'] . ' in taxonomy ' . $row['taxonomy'];
            } else {
                // Insert new term
                $result = wp_insert_term($row['name'], $row['taxonomy'], $args);
                if (!is_wp_error($result)) {
                    $count++;
                    $this->log[] = 'Imported term: ' . $row['name'] . ' to taxonomy ' . $row['taxonomy'];
                } else {
                    $this->log[] = 'Error importing term: ' . $row['name'] . ' - ' . $result->get_error_message();
                }
            }
        }
        
        fclose($handle);
        $this->log[] = 'Import complete. Imported ' . $count . ' terms.';
        return true;
    }
    
    /**
     * Get the import log
     */
    public function get_log() {
        return $this->log;
    }
    
    /**
     * Create sample taxonomy terms
     */
    public function create_sample_terms() {
        $this->log[] = 'Creating sample taxonomy terms...';
        
        // Property Types
        $property_types = array(
            'Villa' => 'Luxurious standalone properties with private amenities',
            'Apartment' => 'Multi-unit residential buildings with shared facilities',
            'Condo' => 'Individually owned units in a larger complex',
            'Townhouse' => 'Multi-floor homes sharing walls with adjacent properties',
            'Beachfront' => 'Properties located directly on or with access to the beach',
            'Penthouse' => 'Luxury apartments on the top floor of high-rise buildings'
        );
        
        foreach ($property_types as $name => $description) {
            $existing = term_exists($name, 'property_type');
            if (!$existing) {
                $result = wp_insert_term($name, 'property_type', array('description' => $description));
                if (!is_wp_error($result)) {
                    $this->log[] = 'Created property type: ' . $name;
                }
            }
        }
        
        // Locations
        $locations = array(
            'North Shore' => array(
                'description' => 'The northern coastal area of the island',
                'children' => array(
                    'Princeville' => 'Upscale planned community with golf courses',
                    'Hanalei' => 'Historic town with beautiful bay views',
                    'Kilauea' => 'Small town with lighthouse and wildlife refuge'
                )
            ),
            'East Side' => array(
                'description' => 'The eastern coastal region',
                'children' => array(
                    'Kapaa' => 'Largest town on the east side with shopping',
                    'Wailua' => 'Area with river and waterfall access',
                    'Lihue' => 'Main commercial center and airport location'
                )
            ),
            'South Shore' => array(
                'description' => 'The sunny southern coast',
                'children' => array(
                    'Poipu' => 'Popular resort area with beautiful beaches',
                    'Koloa' => 'Historic plantation town',
                    'Lawai' => 'Small community with botanical gardens'
                )
            ),
            'West Side' => array(
                'description' => 'The western region of the island',
                'children' => array(
                    'Waimea' => 'Historic town near Waimea Canyon',
                    'Hanapepe' => 'Artistic town with Friday night art walk',
                    'Kekaha' => 'Sunny beach town near Pacific Missile Range'
                )
            )
        );
        
        foreach ($locations as $parent => $data) {
            // Add parent term
            $parent_term = term_exists($parent, 'location');
            if (!$parent_term) {
                $result = wp_insert_term($parent, 'location', array('description' => $data['description']));
                if (!is_wp_error($result)) {
                    $parent_term = $result;
                    $this->log[] = 'Created location: ' . $parent;
                } else {
                    continue;
                }
            }
            
            // Add child terms
            if (isset($data['children']) && is_array($data['children'])) {
                foreach ($data['children'] as $child => $child_desc) {
                    $existing = term_exists($child, 'location', $parent_term['term_id']);
                    if (!$existing) {
                        $result = wp_insert_term($child, 'location', array(
                            'description' => $child_desc,
                            'parent' => $parent_term['term_id']
                        ));
                        if (!is_wp_error($result)) {
                            $this->log[] = 'Created location: ' . $parent . ' > ' . $child;
                        }
                    }
                }
            }
        }
        
        // Amenities
        $amenities = array(
            'Swimming Pool' => 'Private or shared swimming pool',
            'Hot Tub' => 'Private or shared hot tub or jacuzzi',
            'Beach Access' => 'Direct or easy access to the beach',
            'Air Conditioning' => 'Central or split AC units',
            'WiFi' => 'High-speed wireless internet',
            'Full Kitchen' => 'Complete kitchen with appliances',
            'Washer/Dryer' => 'In-unit laundry facilities',
            'Ocean View' => 'Views of the ocean from the property',
            'Mountain View' => 'Views of the mountains from the property',
            'Parking' => 'Dedicated parking space or garage',
            'Gym' => 'Fitness center or gym access',
            'Pet Friendly' => 'Allows pets with possible restrictions'
        );
        
        foreach ($amenities as $name => $description) {
            $existing = term_exists($name, 'amenity');
            if (!$existing) {
                $result = wp_insert_term($name, 'amenity', array('description' => $description));
                if (!is_wp_error($result)) {
                    $this->log[] = 'Created amenity: ' . $name;
                }
            }
        }
        
        // Business Types
        $business_types = array(
            'Restaurant' => 'Food service establishments',
            'Retail' => 'Shops and stores selling goods',
            'Tour Operator' => 'Companies offering guided tours and activities',
            'Accommodation' => 'Hotels, resorts, and other lodging',
            'Wellness' => 'Spas, massage, and health services',
            'Entertainment' => 'Venues for shows and activities',
            'Transportation' => 'Rental cars, shuttles, and other transport services'
        );
        
        foreach ($business_types as $name => $description) {
            $existing = term_exists($name, 'business_type');
            if (!$existing) {
                $result = wp_insert_term($name, 'business_type', array('description' => $description));
                if (!is_wp_error($result)) {
                    $this->log[] = 'Created business type: ' . $name;
                }
            }
        }
        
        // Article Types
        $article_types = array(
            'Guide' => 'Informational articles about specific topics',
            'News' => 'Current events and announcements',
            'Review' => 'Evaluations of properties, businesses, or services',
            'Feature' => 'In-depth stories about people, places, or events',
            'How-To' => 'Step-by-step instructions for activities'
        );
        
        foreach ($article_types as $name => $description) {
            $existing = term_exists($name, 'article_type');
            if (!$existing) {
                $result = wp_insert_term($name, 'article_type', array('description' => $description));
                if (!is_wp_error($result)) {
                    $this->log[] = 'Created article type: ' . $name;
                }
            }
        }
        
        // User Types
        $user_types = array(
            'Property Owner' => 'Owners of rental properties',
            'Business Owner' => 'Owners of local businesses',
            'Resident' => 'Full-time residents of the community',
            'Visitor' => 'Tourists and temporary visitors',
            'Agent' => 'Real estate or property management professionals'
        );
        
        foreach ($user_types as $name => $description) {
            $existing = term_exists($name, 'user_type');
            if (!$existing) {
                $result = wp_insert_term($name, 'user_type', array('description' => $description));
                if (!is_wp_error($result)) {
                    $this->log[] = 'Created user type: ' . $name;
                }
            }
        }
        
        $this->log[] = 'Sample taxonomy terms creation complete.';
        return true;
    }
}

/**
 * Admin page for taxonomy import
 */
function mi_taxonomy_importer_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $importer = new MI_Taxonomy_Importer();
    $message = '';
    $log = array();
    
    // Handle form submission
    if (isset($_POST['mi_import_taxonomies'])) {
        check_admin_referer('mi_taxonomy_import_action', 'mi_taxonomy_import_nonce');
        
        if (isset($_POST['mi_create_sample_terms']) && $_POST['mi_create_sample_terms'] == '1') {
            // Create sample terms
            $importer->create_sample_terms();
            $message = 'Sample taxonomy terms created successfully.';
        } else {
            // Import from CSV
            if (isset($_FILES['mi_taxonomy_csv']) && $_FILES['mi_taxonomy_csv']['error'] == 0) {
                $tmp_file = $_FILES['mi_taxonomy_csv']['tmp_name'];
                $importer = new MI_Taxonomy_Importer($tmp_file);
                $result = $importer->import();
                $message = $result ? 'Taxonomy import completed successfully.' : 'Error during taxonomy import.';
            } else {
                $message = 'Please select a valid CSV file.';
            }
        }
        
        $log = $importer->get_log();
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
            <h2>Import Taxonomies</h2>
            <p>Use this tool to import taxonomy terms from a CSV file or create sample terms.</p>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('mi_taxonomy_import_action', 'mi_taxonomy_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">CSV File</th>
                        <td>
                            <input type="file" name="mi_taxonomy_csv" />
                            <p class="description">CSV file should have columns: taxonomy, name, slug, description, parent</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Create Sample Terms</th>
                        <td>
                            <label>
                                <input type="checkbox" name="mi_create_sample_terms" value="1" />
                                Create sample taxonomy terms instead of importing from CSV
                            </label>
                            <p class="description">This will create a set of predefined terms for all taxonomies</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="mi_import_taxonomies" class="button button-primary" value="Import Taxonomies" />
                </p>
            </form>
        </div>
        
        <?php if (!empty($log)): ?>
            <div class="card">
                <h2>Import Log</h2>
                <div class="mi-import-log" style="max-height: 300px; overflow-y: scroll; padding: 10px; background: #f8f8f8; border: 1px solid #ddd;">
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
 * Add taxonomy importer to admin menu
 */
function mi_add_taxonomy_importer_menu() {
    add_submenu_page(
        'tools.php',
        'Taxonomy Importer',
        'Taxonomy Importer',
        'manage_options',
        'mi-taxonomy-importer',
        'mi_taxonomy_importer_admin_page'
    );
}
add_action('admin_menu', 'mi_add_taxonomy_importer_menu');
