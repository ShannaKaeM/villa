<?php
/**
 * Villa Dashboard - Additional Modules
 * Business listings, groups/committees, and profile management
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
        'post_type' => 'business',
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
                
                <div class="form-actions">
                    <button type="submit" class="villa-btn villa-btn-primary">Add Business</button>
                    <button type="button" class="villa-btn villa-btn-secondary" onclick="hideAddBusinessForm()">Cancel</button>
                </div>
            </form>
        </div>
        
        <!-- Business List -->
        <div class="business-list">
            <?php if (empty($user_businesses)): ?>
                <div class="no-businesses">
                    <p>You haven't added any businesses yet.</p>
                </div>
            <?php else: ?>
                <div class="business-grid">
                    <?php foreach ($user_businesses as $business): ?>
                        <div class="business-card">
                            <div class="business-header">
                                <h3><?php echo esc_html($business->post_title); ?></h3>
                                <span class="business-status status-<?php echo $business->post_status; ?>">
                                    <?php echo ucfirst($business->post_status); ?>
                                </span>
                            </div>
                            
                            <div class="business-content">
                                <?php if ($business->post_excerpt): ?>
                                    <p><?php echo esc_html($business->post_excerpt); ?></p>
                                <?php endif; ?>
                                
                                <div class="business-meta">
                                    <?php 
                                    $business_type = get_post_meta($business->ID, 'business_type', true);
                                    $business_phone = get_post_meta($business->ID, 'business_phone', true);
                                    $business_website = get_post_meta($business->ID, 'business_website', true);
                                    ?>
                                    
                                    <?php if ($business_type): ?>
                                        <div class="meta-item">
                                            <strong>Type:</strong> <?php echo esc_html(ucfirst(str_replace('_', ' ', $business_type))); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($business_phone): ?>
                                        <div class="meta-item">
                                            <strong>Phone:</strong> <?php echo esc_html($business_phone); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($business_website): ?>
                                        <div class="meta-item">
                                            <strong>Website:</strong> 
                                            <a href="<?php echo esc_url($business_website); ?>" target="_blank">
                                                <?php echo esc_html($business_website); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="business-actions">
                                <a href="<?php echo get_edit_post_link($business->ID); ?>" class="villa-btn villa-btn-small">Edit</a>
                                <?php if ($business->post_status === 'publish'): ?>
                                    <a href="<?php echo get_permalink($business->ID); ?>" class="villa-btn villa-btn-small villa-btn-secondary" target="_blank">View</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

/**
 * Render groups dashboard tab (committees and staff groups)
 */
function villa_render_dashboard_committees($user) {
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // Check if user has access to groups
    if (empty($user_roles)) {
        echo '<div class="access-denied">You do not have access to groups.</div>';
        return;
    }
    
    // Get user's groups using the updated function
    $user_groups = villa_get_user_groups($user->ID);
    
    // Separate committees (volunteers) from staff groups
    $committees = array();
    $staff_groups = array();
    
    foreach ($user_groups as $group) {
        $group_types = wp_get_post_terms($group->ID, 'group_type');
        $is_volunteer = false;
        $is_staff = false;
        
        foreach ($group_types as $type) {
            if ($type->slug === 'volunteers') {
                $is_volunteer = true;
                break;
            } elseif ($type->slug === 'staff') {
                $is_staff = true;
                break;
            }
        }
        
        if ($is_volunteer) {
            $committees[] = $group;
        } elseif ($is_staff) {
            $staff_groups[] = $group;
        }
    }
    
    ?>
    <div class="groups-dashboard">
        <div class="dashboard-header">
            <h2>My Groups</h2>
        </div>
        
        <div class="groups-stats">
            <div class="stat-card">
                <h3><?php echo count($committees); ?></h3>
                <p>Committees</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($staff_groups); ?></h3>
                <p>Staff Groups</p>
            </div>
            <div class="stat-card">
                <h3><?php echo count($user_groups); ?></h3>
                <p>Total Groups</p>
            </div>
        </div>
        
        <?php if (!empty($committees)): ?>
            <div class="groups-section">
                <h3>Volunteer Committees</h3>
                <div class="groups-grid">
                    <?php foreach ($committees as $committee): ?>
                        <?php
                        $committee_name = get_post_meta($committee->ID, 'group_name', true);
                        $committee_mission = get_post_meta($committee->ID, 'group_mission', true);
                        $committee_focus = get_post_meta($committee->ID, 'group_focus_areas', true);
                        $meeting_schedule = get_post_meta($committee->ID, 'group_meeting_schedule', true);
                        $coordinator_id = get_post_meta($committee->ID, 'group_coordinator', true);
                        $member_count = villa_get_group_member_count($committee->ID);
                        $user_role = villa_get_user_role_in_group($user->ID, $committee->ID);
                        ?>
                        <div class="villa-card group-card committee-card">
                            <?php if (has_post_thumbnail($committee->ID)): ?>
                                <div class="villa-card__image">
                                    <?php echo get_the_post_thumbnail($committee->ID, 'medium'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="villa-card__content">
                                <div class="villa-card__text">
                                    <h4 class="villa-card__title"><?php echo esc_html($committee_name ?: $committee->post_title); ?></h4>
                                    
                                    <?php if ($committee_mission): ?>
                                        <p class="villa-card__description"><?php echo esc_html($committee_mission); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($committee_focus): ?>
                                        <div class="villa-card__meta">
                                            <strong>Focus Areas:</strong> 
                                            <?php 
                                            if (is_array($committee_focus)) {
                                                echo esc_html(implode(', ', $committee_focus));
                                            } else {
                                                echo esc_html($committee_focus);
                                            }
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="villa-card__meta">
                                        <?php if ($meeting_schedule): ?>
                                            <span class="villa-card__tag">
                                                <strong>Meetings:</strong> <?php echo esc_html($meeting_schedule); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <span class="villa-card__tag">
                                            <strong>Members:</strong> <?php echo $member_count; ?>
                                        </span>
                                        
                                        <?php if ($coordinator_id): ?>
                                            <?php $coordinator = get_userdata($coordinator_id); ?>
                                            <?php if ($coordinator): ?>
                                                <span class="villa-card__tag">
                                                    <strong>Coordinator:</strong> <?php echo esc_html($coordinator->display_name); ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <span class="villa-card__tag villa-card__tag--status user-role"><?php echo esc_html($user_role); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($staff_groups)): ?>
            <div class="groups-section">
                <h3>Staff Groups</h3>
                <div class="groups-grid">
                    <?php foreach ($staff_groups as $staff_group): ?>
                        <?php
                        $group_name = get_post_meta($staff_group->ID, 'group_name', true);
                        $group_mission = get_post_meta($staff_group->ID, 'group_mission', true);
                        $group_focus = get_post_meta($staff_group->ID, 'group_focus_areas', true);
                        $coordinator_id = get_post_meta($staff_group->ID, 'group_coordinator', true);
                        $member_count = villa_get_group_member_count($staff_group->ID);
                        $user_role = villa_get_user_role_in_group($user->ID, $staff_group->ID);
                        ?>
                        <div class="group-card staff-card">
                            <div class="group-header">
                                <h4><?php echo esc_html($group_name ?: $staff_group->post_title); ?></h4>
                                <span class="user-role"><?php echo esc_html($user_role); ?></span>
                            </div>
                            
                            <div class="group-content">
                                <?php if ($group_mission): ?>
                                    <p class="group-mission"><?php echo esc_html($group_mission); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($group_focus): ?>
                                    <div class="group-focus">
                                        <strong>Responsibilities:</strong> <?php echo esc_html($group_focus); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="group-meta">
                                    <div class="meta-item">
                                        <strong>Team Size:</strong> <?php echo $member_count; ?>
                                    </div>
                                    
                                    <?php if ($coordinator_id): ?>
                                        <?php $coordinator = get_userdata($coordinator_id); ?>
                                        <?php if ($coordinator): ?>
                                            <div class="meta-item">
                                                <strong>Manager:</strong> <?php echo esc_html($coordinator->display_name); ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (empty($user_groups)): ?>
            <div class="no-groups">
                <p>You are not currently assigned to any groups.</p>
                <p>Contact an administrator if you believe this is an error.</p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Render profile dashboard tab
 */
function villa_render_dashboard_profile($user) {
    // Get user profile data
    $profile = villa_get_user_profile($user->ID);
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    // Get profile meta data
    $villa_address = $profile ? get_post_meta($profile->ID, 'profile_villa_address', true) : '';
    $phone = $profile ? get_post_meta($profile->ID, 'profile_phone', true) : '';
    $emergency_contact = $profile ? get_post_meta($profile->ID, 'profile_emergency_contact', true) : '';
    $emergency_phone = $profile ? get_post_meta($profile->ID, 'profile_emergency_phone', true) : '';
    $company = $profile ? get_post_meta($profile->ID, 'profile_company', true) : '';
    $job_title = $profile ? get_post_meta($profile->ID, 'profile_job_title', true) : '';
    $bio = $profile ? get_post_meta($profile->ID, 'profile_bio', true) : '';
    
    ?>
    <div class="profile-dashboard">
        <div class="dashboard-header">
            <h2>My Profile</h2>
        </div>
        
        <form id="profile-form" method="post" action="">
            <?php wp_nonce_field('villa_profile_update', 'villa_profile_nonce'); ?>
            <input type="hidden" name="action" value="update_profile">
            
            <div class="profile-section">
                <h3>Basic Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo esc_attr($user->first_name); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo esc_attr($user->last_name); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="villa_address">Villa Address</label>
                        <input type="text" id="villa_address" name="villa_address" value="<?php echo esc_attr($villa_address); ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>">
                    </div>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Emergency Contact</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="emergency_contact">Emergency Contact Name</label>
                        <input type="text" id="emergency_contact" name="emergency_contact" value="<?php echo esc_attr($emergency_contact); ?>">
                    </div>
                    <div class="form-group">
                        <label for="emergency_phone">Emergency Contact Phone</label>
                        <input type="tel" id="emergency_phone" name="emergency_phone" value="<?php echo esc_attr($emergency_phone); ?>">
                    </div>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Professional Information</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" value="<?php echo esc_attr($company); ?>">
                    </div>
                    <div class="form-group">
                        <label for="job_title">Job Title</label>
                        <input type="text" id="job_title" name="job_title" value="<?php echo esc_attr($job_title); ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself..."><?php echo esc_textarea($bio); ?></textarea>
                </div>
            </div>
            
            <div class="profile-section">
                <h3>Villa Roles</h3>
                <div class="roles-display">
                    <?php if (!empty($user_roles)): ?>
                        <?php foreach ($user_roles as $role): ?>
                            <span class="role-badge"><?php echo esc_html(ucfirst(str_replace('_', ' ', $role))); ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="no-roles">No roles assigned</span>
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
        'post_type' => 'business',
        'author' => $user_id,
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));
}

function villa_get_user_committees($user_id) {
    // Get groups where user is coordinator or member
    $user_groups = villa_get_user_groups($user_id);
    
    // Filter to only get volunteer committees (not staff groups)
    $committees = array();
    foreach ($user_groups as $group) {
        $group_types = wp_get_post_terms($group->ID, 'group_type');
        foreach ($group_types as $type) {
            if ($type->slug === 'volunteers') {
                $committees[] = $group;
                break;
            }
        }
    }
    
    return $committees;
}

function villa_get_committee_member_count($committee_id) {
    return villa_get_group_member_count($committee_id);
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
