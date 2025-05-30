<?php
/**
 * Villa Dashboard - Additional Modules
 * Business listings, committees, billing, and profile management
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render business listings dashboard tab (for partners)
 */
function villa_render_dashboard_business($user) {
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // Check if user has access to business management
    if (!in_array('partner', $user_roles) && !in_array('bod', $user_roles) && !in_array('staff', $user_roles)) {
        echo '<div class="access-denied">You do not have access to business management.</div>';
        return;
    }
    
    // Get user's businesses (using existing business CPT)
    $user_businesses = get_posts(array(
        'post_type' => 'business', // Use existing business CPT instead of business_listing
        'author' => $user->ID,
        'post_status' => array('publish', 'draft', 'pending'),
        'numberposts' => -1
    ));
    
    ?>
    <div class="business-management-dashboard">
        <div class="dashboard-header">
            <h2>Business Management</h2>
            <button class="villa-btn villa-btn-primary" onclick="showAddBusinessForm()">Add New Business</button>
        </div>
        
        <div class="business-stats">
            <div class="stat-card">
                <h3><?php echo count($user_businesses); ?></h3>
                <p>Your Businesses</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($user_businesses, function($b) { return $b->post_status === 'publish'; })); ?></h3>
                <p>Published</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count(array_filter($user_businesses, function($b) { return $b->post_status === 'draft'; })); ?></h3>
                <p>Drafts</p>
            </div>
        </div>
        
        <!-- Add Business Form -->
        <div id="add-business-form" class="form-container" style="display: none;">
            <h3>Add New Business</h3>
            <form id="business-form" method="post">
                <?php wp_nonce_field('villa_business_action', 'villa_business_nonce'); ?>
                <input type="hidden" name="action" value="add_business">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="business_name">Business Name *</label>
                        <input type="text" id="business_name" name="business_name" required>
                    </div>
                    <div class="form-group">
                        <label for="business_type">Business Type</label>
                        <select id="business_type" name="business_type">
                            <option value="">Select type...</option>
                            <option value="restaurant">Restaurant/Food Service</option>
                            <option value="retail">Retail/Shopping</option>
                            <option value="service">Professional Service</option>
                            <option value="healthcare">Healthcare</option>
                            <option value="recreation">Recreation/Entertainment</option>
                            <option value="maintenance">Maintenance/Repair</option>
                            <option value="real_estate">Real Estate</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="business_description">Description</label>
                    <textarea id="business_description" name="business_description" rows="4"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="business_phone">Phone</label>
                        <input type="tel" id="business_phone" name="business_phone">
                    </div>
                    <div class="form-group">
                        <label for="business_email">Email</label>
                        <input type="email" id="business_email" name="business_email">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="business_website">Website</label>
                    <input type="url" id="business_website" name="business_website">
                </div>
                
                <div class="form-group">
                    <label for="business_address">Address</label>
                    <textarea id="business_address" name="business_address" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="business_hours">Business Hours</label>
                    <textarea id="business_hours" name="business_hours" rows="3" placeholder="Mon-Fri: 9AM-5PM&#10;Sat: 10AM-3PM&#10;Sun: Closed"></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="villa-btn villa-btn-primary">Add Business</button>
                    <button type="button" class="villa-btn villa-btn-secondary" onclick="hideAddBusinessForm()">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Business Listings -->
        <div class="businesses-list">
            <h3>Your Businesses</h3>
            
            <?php if (empty($user_businesses)): ?>
                <div class="empty-state">
                    <p>You haven't added any businesses yet.</p>
                    <button class="villa-btn villa-btn-primary" onclick="showAddBusinessForm()">Add Your First Business</button>
                </div>
            <?php else: ?>
                <div class="businesses-grid">
                    <?php foreach ($user_businesses as $business): ?>
                        <?php
                        // Get business meta data using your existing business CPT structure
                        $business_type = get_post_meta($business->ID, 'business_type', true);
                        $business_phone = get_post_meta($business->ID, 'business_phone', true);
                        $business_email = get_post_meta($business->ID, 'business_email', true);
                        $business_website = get_post_meta($business->ID, 'business_website', true);
                        $business_address = get_post_meta($business->ID, 'business_address', true);
                        $business_hours = get_post_meta($business->ID, 'business_hours', true);
                        ?>
                        
                        <div class="business-card" data-business-id="<?php echo $business->ID; ?>">
                            <div class="business-header">
                                <h4><?php echo esc_html($business->post_title); ?></h4>
                                <div class="business-status status-<?php echo $business->post_status; ?>">
                                    <?php echo ucfirst($business->post_status); ?>
                                </div>
                            </div>
                            
                            <div class="business-meta">
                                <?php if ($business_type): ?>
                                    <div class="meta-item">
                                        <strong>Type:</strong> <?php echo esc_html(ucwords(str_replace('_', ' ', $business_type))); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($business_phone): ?>
                                    <div class="meta-item">
                                        <strong>Phone:</strong> <?php echo esc_html($business_phone); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($business_email): ?>
                                    <div class="meta-item">
                                        <strong>Email:</strong> <a href="mailto:<?php echo esc_attr($business_email); ?>"><?php echo esc_html($business_email); ?></a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($business_website): ?>
                                    <div class="meta-item">
                                        <strong>Website:</strong> <a href="<?php echo esc_url($business_website); ?>" target="_blank"><?php echo esc_html($business_website); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ($business->post_content): ?>
                                <div class="business-description">
                                    <?php echo wp_trim_words($business->post_content, 20); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="business-actions">
                                <button class="villa-btn villa-btn-small villa-btn-secondary" onclick="editBusiness(<?php echo $business->ID; ?>)">Edit</button>
                                <a href="<?php echo get_permalink($business->ID); ?>" class="villa-btn villa-btn-small villa-btn-outline" target="_blank">View</a>
                                <?php if ($business->post_status === 'draft'): ?>
                                    <button class="villa-btn villa-btn-small villa-btn-primary" onclick="publishBusiness(<?php echo $business->ID; ?>)">Publish</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
    function showAddBusinessForm() {
        document.getElementById('add-business-form').style.display = 'block';
        document.getElementById('business_name').focus();
    }
    
    function hideAddBusinessForm() {
        document.getElementById('add-business-form').style.display = 'none';
        document.getElementById('business-form').reset();
    }
    
    function editBusiness(businessId) {
        // Redirect to WordPress edit page for the business
        window.open('<?php echo admin_url('post.php?action=edit&post='); ?>' + businessId, '_blank');
    }
    
    function publishBusiness(businessId) {
        if (confirm('Are you sure you want to publish this business?')) {
            // AJAX call to publish business
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'villa_publish_business',
                    business_id: businessId,
                    nonce: '<?php echo wp_create_nonce('villa_business_action'); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error publishing business: ' + data.data);
                }
            });
        }
    }
    
    // Handle business form submission
    document.getElementById('business-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'villa_add_business');
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error adding business: ' + data.data);
            }
        });
    });
    </script>
    <?php
}

/**
 * Render committees dashboard tab
 */
function villa_render_dashboard_committees($user) {
    $user_roles = villa_get_user_villa_roles($user->ID);
    $user_committees = villa_get_user_committees($user->ID);
    
    ?>
    <div class="committees-dashboard">
        <div class="committees-header">
            <h2>My Committees</h2>
        </div>
        
        <?php if (empty($user_committees)): ?>
            <div class="no-committees">
                <p>You are not currently assigned to any committees.</p>
            </div>
        <?php else: ?>
            <div class="committees-list">
                <?php foreach ($user_committees as $committee): ?>
                    <?php
                    $committee_type = get_post_meta($committee->ID, 'committee_type', true);
                    $meeting_schedule = get_post_meta($committee->ID, 'committee_meeting_schedule', true);
                    $next_meeting = get_post_meta($committee->ID, 'committee_next_meeting', true);
                    $member_count = villa_get_committee_member_count($committee->ID);
                    ?>
                    
                    <div class="committee-card">
                        <div class="committee-header">
                            <h3><?php echo esc_html($committee->post_title); ?></h3>
                            <span class="committee-type"><?php echo esc_html(ucwords(str_replace('_', ' ', $committee_type))); ?></span>
                        </div>
                        
                        <div class="committee-info">
                            <p><?php echo wp_trim_words($committee->post_content, 30); ?></p>
                            
                            <div class="committee-details">
                                <?php if ($member_count): ?>
                                    <p><strong>Members:</strong> <?php echo $member_count; ?></p>
                                <?php endif; ?>
                                
                                <?php if ($meeting_schedule): ?>
                                    <p><strong>Meetings:</strong> <?php echo esc_html($meeting_schedule); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($next_meeting): ?>
                                    <p><strong>Next Meeting:</strong> <?php echo date('F j, Y', strtotime($next_meeting)); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="committee-actions">
                            <a href="?tab=committees&committee_id=<?php echo $committee->ID; ?>" class="button">View Details</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render billing dashboard tab
 */
function villa_render_dashboard_billing($user) {
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    if (!villa_user_can_view_billing($user->ID)) {
        echo '<div class="access-denied">You do not have access to billing information.</div>';
        return;
    }
    
    $user_properties = villa_get_user_properties($user->ID);
    $billing_statements = villa_get_user_billing_statements($user->ID);
    $payment_methods = villa_get_user_payment_methods($user->ID);
    
    ?>
    <div class="billing-dashboard">
        <div class="billing-header">
            <h2>Billing & Payments</h2>
        </div>
        
        <div class="billing-grid">
            <!-- Account Summary -->
            <div class="billing-section account-summary">
                <h3>Account Summary</h3>
                
                <?php
                $current_balance = villa_get_user_current_balance($user->ID);
                $next_due_date = villa_get_user_next_due_date($user->ID);
                ?>
                
                <div class="balance-info">
                    <div class="balance-amount <?php echo $current_balance > 0 ? 'positive' : ($current_balance < 0 ? 'negative' : 'zero'); ?>">
                        <span class="balance-label">Current Balance:</span>
                        <span class="balance-value">$<?php echo number_format(abs($current_balance), 2); ?></span>
                        <?php if ($current_balance < 0): ?>
                            <span class="balance-status">Due</span>
                        <?php elseif ($current_balance > 0): ?>
                            <span class="balance-status">Credit</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($next_due_date): ?>
                        <div class="next-due">
                            <span class="due-label">Next Payment Due:</span>
                            <span class="due-date"><?php echo date('F j, Y', strtotime($next_due_date)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ($current_balance < 0): ?>
                    <div class="payment-actions">
                        <a href="#" class="button button-primary" onclick="openPaymentModal()">Make Payment</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Recent Statements -->
            <div class="billing-section statements-section">
                <h3>Recent Statements</h3>
                
                <?php if (empty($billing_statements)): ?>
                    <p>No billing statements available.</p>
                <?php else: ?>
                    <div class="statements-list">
                        <?php foreach (array_slice($billing_statements, 0, 5) as $statement): ?>
                            <?php
                            $statement_date = get_post_meta($statement->ID, 'statement_date', true);
                            $statement_amount = get_post_meta($statement->ID, 'statement_amount', true);
                            $statement_status = get_post_meta($statement->ID, 'statement_status', true);
                            $statement_file = get_post_meta($statement->ID, 'statement_file', true);
                            ?>
                            
                            <div class="statement-item">
                                <div class="statement-info">
                                    <span class="statement-date"><?php echo date('M Y', strtotime($statement_date)); ?></span>
                                    <span class="statement-amount">$<?php echo number_format($statement_amount, 2); ?></span>
                                    <span class="statement-status status-<?php echo esc_attr($statement_status); ?>">
                                        <?php echo esc_html(ucwords(str_replace('_', ' ', $statement_status))); ?>
                                    </span>
                                </div>
                                
                                <?php if ($statement_file): ?>
                                    <a href="<?php echo esc_url($statement_file); ?>" class="button small" target="_blank">Download</a>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="?tab=billing&view=statements" class="view-all-link">View All Statements</a>
                <?php endif; ?>
            </div>
            
            <!-- Payment Methods -->
            <div class="billing-section payment-methods-section">
                <h3>Payment Methods</h3>
                
                <?php if (empty($payment_methods)): ?>
                    <p>No payment methods on file.</p>
                    <a href="#" class="button" onclick="openAddPaymentMethodModal()">Add Payment Method</a>
                <?php else: ?>
                    <div class="payment-methods-list">
                        <?php foreach ($payment_methods as $method): ?>
                            <div class="payment-method-item">
                                <span class="method-type"><?php echo esc_html($method['type']); ?></span>
                                <span class="method-details"><?php echo esc_html($method['details']); ?></span>
                                <?php if ($method['is_default']): ?>
                                    <span class="default-badge">Default</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="#" class="button" onclick="openAddPaymentMethodModal()">Add New Method</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Render profile dashboard tab
 */
function villa_render_dashboard_profile($user) {
    $user_profile = villa_get_user_profile($user->ID);
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // Handle form submission
    if ($_POST && wp_verify_nonce($_POST['villa_profile_nonce'], 'villa_profile_form')) {
        $result = villa_save_profile_form($user->ID, $_POST);
        if ($result['success']) {
            echo '<div class="success">Profile updated successfully!</div>';
        } else {
            echo '<div class="error">' . esc_html($result['message']) . '</div>';
        }
    }
    
    // Get profile data
    $profile_data = array();
    if ($user_profile) {
        $profile_data = array(
            'villa_address' => get_post_meta($user_profile->ID, 'profile_villa_address', true),
            'phone' => get_post_meta($user_profile->ID, 'profile_phone', true),
            'emergency_contact' => get_post_meta($user_profile->ID, 'profile_emergency_contact', true),
            'emergency_phone' => get_post_meta($user_profile->ID, 'profile_emergency_phone', true),
            'company' => get_post_meta($user_profile->ID, 'profile_company', true),
            'job_title' => get_post_meta($user_profile->ID, 'profile_job_title', true),
            'bio' => get_post_meta($user_profile->ID, 'profile_bio', true),
        );
    }
    
    ?>
    <div class="profile-dashboard">
        <div class="profile-header">
            <h2>My Profile</h2>
        </div>
        
        <form method="post" class="profile-form" enctype="multipart/form-data">
            <?php wp_nonce_field('villa_profile_form', 'villa_profile_nonce'); ?>
            
            <div class="form-section">
                <h3>Basic Information</h3>
                
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($user->first_name); ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($user->last_name); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo esc_attr($user->user_email); ?>">
                </div>
                
                <div class="form-row">
                    <label for="villa_address">Villa Address</label>
                    <input type="text" id="villa_address" name="villa_address" value="<?php echo esc_attr($profile_data['villa_address'] ?? ''); ?>">
                </div>
                
                <div class="form-row">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($profile_data['phone'] ?? ''); ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h3>Emergency Contact</h3>
                
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="emergency_contact">Emergency Contact Name</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo esc_attr($profile_data['emergency_contact'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="emergency_phone">Emergency Contact Phone</label>
                        <input type="tel" id="emergency_phone" name="emergency_phone" value="<?php echo esc_attr($profile_data['emergency_phone'] ?? ''); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Professional Information</h3>
                
                <div class="form-row-group">
                    <div class="form-row">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?php echo esc_attr($profile_data['company'] ?? ''); ?>">
                    </div>
                    
                    <div class="form-row">
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="<?php echo esc_attr($profile_data['job_title'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="form-row">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us a little about yourself..."><?php echo esc_textarea($profile_data['bio'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Current Roles</h3>
                <div class="current-roles">
                    <?php if (!empty($user_roles)): ?>
                        <?php foreach ($user_roles as $role): ?>
                            <span class="role-badge role-<?php echo esc_attr($role); ?>">
                                <?php echo esc_html(ucwords(str_replace('_', ' ', $role))); ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No roles assigned.</p>
                    <?php endif; ?>
                </div>
                <p class="form-help">Contact an administrator to change your roles.</p>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="button button-primary">Update Profile</button>
            </div>
        </form>
    </div>
    <?php
}

/**
 * Helper functions
 */
function villa_get_user_business_listings($user_id) {
    return get_posts(array(
        'post_type' => 'business', // Use existing business CPT instead of business_listing
        'author' => $user_id,
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
}

function villa_get_user_committees($user_id) {
    return get_posts(array(
        'post_type' => 'committee',
        'meta_query' => array(
            array(
                'key' => 'committee_members',
                'value' => $user_id,
                'compare' => 'LIKE'
            )
        ),
        'posts_per_page' => -1
    ));
}

function villa_get_committee_member_count($committee_id) {
    $members = get_post_meta($committee_id, 'committee_members', true);
    return is_array($members) ? count($members) : 0;
}

function villa_get_user_billing_statements($user_id) {
    return get_posts(array(
        'post_type' => 'billing_statement',
        'meta_query' => array(
            array(
                'key' => 'statement_user_id',
                'value' => $user_id,
                'compare' => '='
            )
        ),
        'posts_per_page' => -1,
        'orderby' => 'meta_value',
        'meta_key' => 'statement_date',
        'order' => 'DESC'
    ));
}

function villa_get_user_payment_methods($user_id) {
    // This would typically come from a payment processor API
    // For now, return sample data
    return array();
}

function villa_get_user_current_balance($user_id) {
    // Calculate from billing statements and payments
    return 0;
}

function villa_get_user_next_due_date($user_id) {
    // Get next due date from billing system
    return null;
}

function villa_save_profile_form($user_id, $form_data) {
    // Update WordPress user data
    $user_data = array(
        'ID' => $user_id,
        'first_name' => sanitize_text_field($form_data['first_name']),
        'last_name' => sanitize_text_field($form_data['last_name']),
        'user_email' => sanitize_email($form_data['email'])
    );
    
    $result = wp_update_user($user_data);
    if (is_wp_error($result)) {
        return array('success' => false, 'message' => 'Error updating profile.');
    }
    
    // Update or create user profile CPT
    $profile = villa_get_user_profile($user_id);
    if (!$profile) {
        // Create new profile
        $profile_id = wp_insert_post(array(
            'post_type' => 'user_profile',
            'post_title' => $form_data['first_name'] . ' ' . $form_data['last_name'],
            'post_status' => 'publish',
            'meta_input' => array(
                'profile_user_id' => $user_id
            )
        ));
    } else {
        $profile_id = $profile->ID;
    }
    
    // Update profile meta
    update_post_meta($profile_id, 'profile_villa_address', sanitize_text_field($form_data['villa_address']));
    update_post_meta($profile_id, 'profile_phone', sanitize_text_field($form_data['phone']));
    update_post_meta($profile_id, 'profile_emergency_contact', sanitize_text_field($form_data['emergency_contact']));
    update_post_meta($profile_id, 'profile_emergency_phone', sanitize_text_field($form_data['emergency_phone']));
    update_post_meta($profile_id, 'profile_company', sanitize_text_field($form_data['company']));
    update_post_meta($profile_id, 'profile_job_title', sanitize_text_field($form_data['job_title']));
    update_post_meta($profile_id, 'profile_bio', sanitize_textarea_field($form_data['bio']));
    
    return array('success' => true);
}
