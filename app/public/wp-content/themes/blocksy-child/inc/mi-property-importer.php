<?php
/**
 * Property Importer
 * 
 * Imports property listings from a CSV file or creates sample properties
 */

// Don't allow direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Import properties from CSV or create sample properties
 */
class MI_Property_Importer {
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
        // Default to the Properties.csv in the docs/SITE DATA directory
        if (empty($csv_file)) {
            $csv_file = get_stylesheet_directory() . '/docs/SITE DATA/Properties.csv';
        }
        
        $this->csv_file = $csv_file;
    }
    
    /**
     * Run the import from CSV
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
            
            // Skip if no title
            if (empty($row['title'])) {
                $this->log[] = 'Warning: Skipping row with missing title';
                continue;
            }
            
            // Check if property already exists
            $existing = get_page_by_title($row['title'], OBJECT, 'property');
            
            if ($existing) {
                $this->log[] = 'Property already exists: ' . $row['title'];
                continue;
            }
            
            // Create property post
            $post_data = array(
                'post_title'    => $row['title'],
                'post_content'  => isset($row['content']) ? $row['content'] : '',
                'post_excerpt'  => isset($row['excerpt']) ? $row['excerpt'] : '',
                'post_status'   => 'publish',
                'post_type'     => 'property',
                'post_author'   => get_current_user_id(),
            );
            
            $post_id = wp_insert_post($post_data);
            
            if (!$post_id || is_wp_error($post_id)) {
                $this->log[] = 'Error creating property: ' . $row['title'];
                continue;
            }
            
            // Set property meta fields
            $meta_fields = array(
                'property_address'         => 'address',
                'property_city'            => 'city',
                'property_state'           => 'state',
                'property_zip_code'        => 'zip_code',
                'property_latitude'        => 'latitude',
                'property_longitude'       => 'longitude',
                'property_bedrooms'        => 'bedrooms',
                'property_bathrooms'       => 'bathrooms',
                'property_max_guests'      => 'max_guests',
                'property_nightly_rate'    => 'nightly_rate',
                'property_booking_url'     => 'booking_url',
                'property_is_featured'     => 'is_featured',
            );
            
            foreach ($meta_fields as $meta_key => $csv_key) {
                if (isset($row[$csv_key]) && $row[$csv_key] !== '') {
                    carbon_set_post_meta($post_id, $meta_key, $row[$csv_key]);
                }
            }
            
            // Set taxonomies
            $taxonomies = array(
                'property_type' => 'property_type',
                'location'      => 'location',
                'amenity'       => 'amenities',
            );
            
            foreach ($taxonomies as $taxonomy => $csv_key) {
                if (isset($row[$csv_key]) && !empty($row[$csv_key])) {
                    $terms = explode(',', $row[$csv_key]);
                    foreach ($terms as $term_name) {
                        $term_name = trim($term_name);
                        if (!empty($term_name)) {
                            $term = term_exists($term_name, $taxonomy);
                            if (!$term) {
                                $term = wp_insert_term($term_name, $taxonomy);
                            }
                            if (!is_wp_error($term)) {
                                wp_set_object_terms($post_id, (int)$term['term_id'], $taxonomy, true);
                            }
                        }
                    }
                }
            }
            
            $count++;
            $this->log[] = 'Imported property: ' . $row['title'];
        }
        
        fclose($handle);
        $this->log[] = 'Import complete. Imported ' . $count . ' properties.';
        return true;
    }
    
    /**
     * Create sample properties
     */
    public function create_sample_properties() {
        $this->log[] = 'Creating sample properties...';
        
        // Get existing terms
        $property_types = get_terms(array(
            'taxonomy' => 'property_type',
            'hide_empty' => false,
        ));
        
        $locations = get_terms(array(
            'taxonomy' => 'location',
            'hide_empty' => false,
        ));
        
        $amenities = get_terms(array(
            'taxonomy' => 'amenity',
            'hide_empty' => false,
        ));
        
        // If no terms exist, create some
        if (empty($property_types) || empty($locations) || empty($amenities)) {
            $this->log[] = 'Please run the Taxonomy Importer first to create taxonomy terms.';
            return false;
        }
        
        // Sample property data
        $sample_properties = array(
            array(
                'title' => 'Oceanfront Villa with Pool',
                'content' => 'This stunning oceanfront villa offers breathtaking views and luxurious amenities. Enjoy the private pool, spacious living areas, and direct beach access. Perfect for families or groups looking for a premium vacation experience.',
                'excerpt' => 'Luxury oceanfront villa with private pool and direct beach access.',
                'bedrooms' => 4,
                'bathrooms' => 3.5,
                'max_guests' => 10,
                'nightly_rate' => 750,
                'address' => '123 Oceanfront Drive',
                'city' => 'Poipu',
                'state' => 'HI',
                'zip_code' => '96756',
                'is_featured' => true,
                'property_type' => array('Villa', 'Beachfront'),
                'location' => array('South Shore', 'Poipu'),
                'amenities' => array('Swimming Pool', 'Beach Access', 'Air Conditioning', 'WiFi', 'Full Kitchen', 'Ocean View')
            ),
            array(
                'title' => 'Mountain View Condo',
                'content' => 'Cozy condo with stunning mountain views. This recently renovated unit features modern appliances, comfortable furnishings, and a lanai perfect for enjoying your morning coffee while taking in the breathtaking scenery.',
                'excerpt' => 'Cozy condo with stunning mountain views and modern amenities.',
                'bedrooms' => 2,
                'bathrooms' => 2,
                'max_guests' => 4,
                'nightly_rate' => 225,
                'address' => '456 Mountain View Lane',
                'city' => 'Princeville',
                'state' => 'HI',
                'zip_code' => '96722',
                'is_featured' => false,
                'property_type' => array('Condo'),
                'location' => array('North Shore', 'Princeville'),
                'amenities' => array('WiFi', 'Full Kitchen', 'Mountain View', 'Parking')
            ),
            array(
                'title' => 'Luxury Beachfront Penthouse',
                'content' => 'Experience the ultimate in luxury with this stunning penthouse apartment. Featuring panoramic ocean views, high-end furnishings, and exclusive amenities. The perfect choice for discerning travelers seeking a premium vacation experience.',
                'excerpt' => 'Luxury penthouse with panoramic ocean views and premium amenities.',
                'bedrooms' => 3,
                'bathrooms' => 3,
                'max_guests' => 6,
                'nightly_rate' => 550,
                'address' => '789 Beachfront Avenue',
                'city' => 'Kapaa',
                'state' => 'HI',
                'zip_code' => '96746',
                'is_featured' => true,
                'property_type' => array('Penthouse', 'Beachfront'),
                'location' => array('East Side', 'Kapaa'),
                'amenities' => array('Swimming Pool', 'Hot Tub', 'Beach Access', 'Air Conditioning', 'WiFi', 'Ocean View')
            ),
            array(
                'title' => 'Charming Garden Cottage',
                'content' => 'Escape to this charming garden cottage surrounded by lush tropical landscaping. This private retreat offers a peaceful setting while still being close to beaches, shopping, and dining. Perfect for couples or small families.',
                'excerpt' => 'Private garden cottage surrounded by tropical landscaping.',
                'bedrooms' => 1,
                'bathrooms' => 1,
                'max_guests' => 3,
                'nightly_rate' => 175,
                'address' => '101 Garden Path',
                'city' => 'Hanalei',
                'state' => 'HI',
                'zip_code' => '96714',
                'is_featured' => false,
                'property_type' => array('Villa'),
                'location' => array('North Shore', 'Hanalei'),
                'amenities' => array('WiFi', 'Full Kitchen', 'Washer/Dryer', 'Parking')
            ),
            array(
                'title' => 'Family-Friendly Townhouse',
                'content' => 'Spacious townhouse perfect for families. Features include a fully equipped kitchen, comfortable living spaces, and a private patio. Located in a quiet community with a shared pool and easy access to beaches and attractions.',
                'excerpt' => 'Spacious townhouse in a family-friendly community with shared pool.',
                'bedrooms' => 3,
                'bathrooms' => 2.5,
                'max_guests' => 8,
                'nightly_rate' => 275,
                'address' => '202 Family Circle',
                'city' => 'Koloa',
                'state' => 'HI',
                'zip_code' => '96756',
                'is_featured' => false,
                'property_type' => array('Townhouse'),
                'location' => array('South Shore', 'Koloa'),
                'amenities' => array('Swimming Pool', 'Air Conditioning', 'WiFi', 'Full Kitchen', 'Washer/Dryer', 'Parking')
            ),
        );
        
        $count = 0;
        foreach ($sample_properties as $property_data) {
            // Check if property already exists
            $existing = get_page_by_title($property_data['title'], OBJECT, 'property');
            
            if ($existing) {
                $this->log[] = 'Property already exists: ' . $property_data['title'];
                continue;
            }
            
            // Create property post
            $post_data = array(
                'post_title'    => $property_data['title'],
                'post_content'  => $property_data['content'],
                'post_excerpt'  => $property_data['excerpt'],
                'post_status'   => 'publish',
                'post_type'     => 'property',
                'post_author'   => get_current_user_id(),
            );
            
            $post_id = wp_insert_post($post_data);
            
            if (!$post_id || is_wp_error($post_id)) {
                $this->log[] = 'Error creating property: ' . $property_data['title'];
                continue;
            }
            
            // Set property meta fields
            carbon_set_post_meta($post_id, 'property_address', $property_data['address']);
            carbon_set_post_meta($post_id, 'property_city', $property_data['city']);
            carbon_set_post_meta($post_id, 'property_state', $property_data['state']);
            carbon_set_post_meta($post_id, 'property_zip_code', $property_data['zip_code']);
            carbon_set_post_meta($post_id, 'property_bedrooms', $property_data['bedrooms']);
            carbon_set_post_meta($post_id, 'property_bathrooms', $property_data['bathrooms']);
            carbon_set_post_meta($post_id, 'property_max_guests', $property_data['max_guests']);
            carbon_set_post_meta($post_id, 'property_nightly_rate', $property_data['nightly_rate']);
            carbon_set_post_meta($post_id, 'property_is_featured', $property_data['is_featured']);
            
            // Set taxonomies
            if (!empty($property_data['property_type'])) {
                foreach ($property_data['property_type'] as $term_name) {
                    $term = term_exists($term_name, 'property_type');
                    if ($term) {
                        wp_set_object_terms($post_id, (int)$term['term_id'], 'property_type', true);
                    }
                }
            }
            
            if (!empty($property_data['location'])) {
                foreach ($property_data['location'] as $term_name) {
                    $term = term_exists($term_name, 'location');
                    if ($term) {
                        wp_set_object_terms($post_id, (int)$term['term_id'], 'location', true);
                    }
                }
            }
            
            if (!empty($property_data['amenities'])) {
                foreach ($property_data['amenities'] as $term_name) {
                    $term = term_exists($term_name, 'amenity');
                    if ($term) {
                        wp_set_object_terms($post_id, (int)$term['term_id'], 'amenity', true);
                    }
                }
            }
            
            $count++;
            $this->log[] = 'Created sample property: ' . $property_data['title'];
        }
        
        $this->log[] = 'Sample property creation complete. Created ' . $count . ' properties.';
        return true;
    }
    
    /**
     * Get the import log
     */
    public function get_log() {
        return $this->log;
    }
}

/**
 * Admin page for property import
 */
function mi_property_importer_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    $importer = new MI_Property_Importer();
    $message = '';
    $log = array();
    
    // Handle form submission
    if (isset($_POST['mi_import_properties'])) {
        check_admin_referer('mi_property_import_action', 'mi_property_import_nonce');
        
        if (isset($_POST['mi_create_sample_properties']) && $_POST['mi_create_sample_properties'] == '1') {
            // Create sample properties
            $importer->create_sample_properties();
            $message = 'Sample properties created successfully.';
        } else {
            // Import from CSV
            if (isset($_FILES['mi_property_csv']) && $_FILES['mi_property_csv']['error'] == 0) {
                $tmp_file = $_FILES['mi_property_csv']['tmp_name'];
                $importer = new MI_Property_Importer($tmp_file);
                $result = $importer->import();
                $message = $result ? 'Property import completed successfully.' : 'Error during property import.';
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
            <h2>Import Properties</h2>
            <p>Use this tool to import properties from a CSV file or create sample properties.</p>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('mi_property_import_action', 'mi_property_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">CSV File</th>
                        <td>
                            <input type="file" name="mi_property_csv" />
                            <p class="description">CSV file should have columns for property data</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Create Sample Properties</th>
                        <td>
                            <label>
                                <input type="checkbox" name="mi_create_sample_properties" value="1" />
                                Create sample properties instead of importing from CSV
                            </label>
                            <p class="description">This will create a set of predefined properties</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="mi_import_properties" class="button button-primary" value="Import Properties" />
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
 * Add property importer to admin menu
 */
function mi_add_property_importer_menu() {
    add_submenu_page(
        'edit.php?post_type=property',
        'Property Importer',
        'Property Importer',
        'manage_options',
        'mi-property-importer',
        'mi_property_importer_admin_page'
    );
}
add_action('admin_menu', 'mi_add_property_importer_menu');
