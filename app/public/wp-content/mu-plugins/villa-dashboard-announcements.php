<?php
/**
 * Villa Dashboard - Announcements Module
 * Handles community announcements and owner portal roadmap
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render announcements dashboard tab
 */
function villa_render_dashboard_announcements($user) {
    $announcements = villa_get_announcements_for_user($user->ID);
    $user_roles = villa_get_user_villa_roles($user->ID);
    
    ?>
    <div class="announcements-dashboard">
        <div class="announcements-header">
            <h2>Community Announcements</h2>
            <div class="header-actions">
                <button id="mark-all-read" class="button">Mark All as Read</button>
            </div>
        </div>
        
        <div class="announcements-filters">
            <label for="announcement-filter">Filter by Type:</label>
            <select id="announcement-filter" onchange="filterAnnouncements(this.value)">
                <option value="">All Announcements</option>
                <option value="general">General</option>
                <option value="maintenance">Maintenance</option>
                <option value="events">Events</option>
                <option value="policy">Policy Updates</option>
                <option value="emergency">Emergency</option>
                <?php if (in_array('owner', $user_roles) || in_array('bod', $user_roles)): ?>
                <option value="owner_only">Owner Only</option>
                <?php endif; ?>
            </select>
        </div>
        
        <?php if (empty($announcements)): ?>
            <div class="no-announcements">
                <p>No announcements available at this time.</p>
            </div>
        <?php else: ?>
            <div class="announcements-list">
                <?php foreach ($announcements as $announcement): ?>
                    <?php
                    $announcement_type = get_post_meta($announcement->ID, 'announcement_type', true);
                    $announcement_priority = get_post_meta($announcement->ID, 'announcement_priority', true);
                    $target_roles = get_post_meta($announcement->ID, 'announcement_target_roles', true);
                    $expiry_date = get_post_meta($announcement->ID, 'announcement_expiry_date', true);
                    $is_read = villa_is_announcement_read($user->ID, $announcement->ID);
                    $is_pinned = get_post_meta($announcement->ID, 'announcement_pinned', true);
                    ?>
                    
                    <div class="announcement-card <?php echo !$is_read ? 'unread' : 'read'; ?> type-<?php echo esc_attr($announcement_type); ?> priority-<?php echo esc_attr($announcement_priority); ?>" data-type="<?php echo esc_attr($announcement_type); ?>">
                        <?php if ($is_pinned): ?>
                            <div class="pinned-badge">📌 Pinned</div>
                        <?php endif; ?>
                        
                        <div class="announcement-header">
                            <h3>
                                <a href="#" onclick="toggleAnnouncement(<?php echo $announcement->ID; ?>); return false;">
                                    <?php echo esc_html($announcement->post_title); ?>
                                    <?php if (!$is_read): ?>
                                        <span class="new-badge">NEW</span>
                                    <?php endif; ?>
                                </a>
                            </h3>
                            
                            <div class="announcement-meta">
                                <span class="announcement-date"><?php echo get_the_date('M j, Y', $announcement); ?></span>
                                <span class="announcement-type type-<?php echo esc_attr($announcement_type); ?>">
                                    <?php echo esc_html(ucwords(str_replace('_', ' ', $announcement_type))); ?>
                                </span>
                                <?php if ($announcement_priority === 'high' || $announcement_priority === 'urgent'): ?>
                                    <span class="priority-badge priority-<?php echo esc_attr($announcement_priority); ?>">
                                        <?php echo esc_html(ucwords($announcement_priority)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="announcement-excerpt">
                            <?php echo wp_trim_words($announcement->post_content, 30); ?>
                        </div>
                        
                        <div class="announcement-content" id="announcement-content-<?php echo $announcement->ID; ?>" style="display: none;">
                            <?php echo wpautop($announcement->post_content); ?>
                            
                            <?php
                            $attachments = get_attached_media('', $announcement->ID);
                            if (!empty($attachments)):
                            ?>
                                <div class="announcement-attachments">
                                    <h4>Attachments:</h4>
                                    <?php foreach ($attachments as $attachment): ?>
                                        <div class="attachment-item">
                                            <a href="<?php echo wp_get_attachment_url($attachment->ID); ?>" target="_blank">
                                                <?php echo esc_html(get_the_title($attachment)); ?>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="announcement-actions">
                            <button onclick="toggleAnnouncement(<?php echo $announcement->ID; ?>)" class="expand-btn">
                                <span class="expand-text">Read More</span>
                                <span class="collapse-text" style="display: none;">Read Less</span>
                            </button>
                            
                            <?php if (!$is_read): ?>
                                <button onclick="markAsRead(<?php echo $announcement->ID; ?>)" class="mark-read-btn">Mark as Read</button>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($expiry_date && strtotime($expiry_date) < time()): ?>
                            <div class="expiry-notice">This announcement has expired.</div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
    function toggleAnnouncement(announcementId) {
        const content = document.getElementById('announcement-content-' + announcementId);
        const expandText = content.parentElement.querySelector('.expand-text');
        const collapseText = content.parentElement.querySelector('.collapse-text');
        
        if (content.style.display === 'none') {
            content.style.display = 'block';
            expandText.style.display = 'none';
            collapseText.style.display = 'inline';
            
            // Mark as read when expanded
            if (!content.parentElement.parentElement.classList.contains('read')) {
                markAsRead(announcementId);
            }
        } else {
            content.style.display = 'none';
            expandText.style.display = 'inline';
            collapseText.style.display = 'none';
        }
    }
    
    function markAsRead(announcementId) {
        fetch(villa_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'villa_mark_announcement_read',
                announcement_id: announcementId,
                nonce: villa_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const card = document.querySelector(`[data-announcement-id="${announcementId}"]`);
                if (card) {
                    card.classList.remove('unread');
                    card.classList.add('read');
                    const newBadge = card.querySelector('.new-badge');
                    if (newBadge) newBadge.remove();
                    const markReadBtn = card.querySelector('.mark-read-btn');
                    if (markReadBtn) markReadBtn.remove();
                }
            }
        });
    }
    
    function filterAnnouncements(type) {
        const cards = document.querySelectorAll('.announcement-card');
        cards.forEach(card => {
            if (!type || card.dataset.type === type) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    document.getElementById('mark-all-read').addEventListener('click', function() {
        const unreadCards = document.querySelectorAll('.announcement-card.unread');
        unreadCards.forEach(card => {
            const announcementId = card.dataset.announcementId;
            if (announcementId) {
                markAsRead(announcementId);
            }
        });
    });
    </script>
    <?php
}

/**
 * Get announcements for user based on their roles
 */
function villa_get_announcements_for_user($user_id) {
    $user_roles = villa_get_user_villa_roles($user_id);
    
    $args = array(
        'post_type' => 'announcement',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'announcement_target_roles',
                'compare' => 'NOT EXISTS'
            ),
            array(
                'key' => 'announcement_target_roles',
                'value' => '',
                'compare' => '='
            )
        )
    );
    
    // Add role-specific query if user has roles
    if (!empty($user_roles)) {
        foreach ($user_roles as $role) {
            $args['meta_query'][] = array(
                'key' => 'announcement_target_roles',
                'value' => $role,
                'compare' => 'LIKE'
            );
        }
    }
    
    return get_posts($args);
}

/**
 * Check if announcement is read by user
 */
function villa_is_announcement_read($user_id, $announcement_id) {
    $read_announcements = get_user_meta($user_id, 'read_announcements', true);
    return is_array($read_announcements) && in_array($announcement_id, $read_announcements);
}

/**
 * Get upcoming meetings
 */
function villa_get_upcoming_meetings() {
    return get_posts(array(
        'post_type' => 'meeting',
        'post_status' => 'publish',
        'posts_per_page' => 5,
        'meta_query' => array(
            array(
                'key' => 'meeting_date',
                'value' => date('Y-m-d'),
                'compare' => '>='
            )
        ),
        'orderby' => 'meta_value',
        'meta_key' => 'meeting_date',
        'order' => 'ASC'
    ));
}

/**
 * Get financial reports
 */
function villa_get_financial_reports() {
    return get_posts(array(
        'post_type' => 'financial_report',
        'post_status' => 'publish',
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC'
    ));
}

/**
 * AJAX handler for marking announcements as read
 */
function villa_mark_announcement_read() {
    if (!wp_verify_nonce($_POST['nonce'], 'villa_dashboard_nonce')) {
        wp_die('Security check failed');
    }
    
    $user_id = get_current_user_id();
    $announcement_id = intval($_POST['announcement_id']);
    
    $read_announcements = get_user_meta($user_id, 'read_announcements', true);
    if (!is_array($read_announcements)) {
        $read_announcements = array();
    }
    
    if (!in_array($announcement_id, $read_announcements)) {
        $read_announcements[] = $announcement_id;
        update_user_meta($user_id, 'read_announcements', $read_announcements);
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_villa_mark_announcement_read', 'villa_mark_announcement_read');
