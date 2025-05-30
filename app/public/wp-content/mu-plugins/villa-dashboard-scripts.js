/**
 * Villa Dashboard JavaScript
 * Handles all frontend interactions for the dashboard
 */

(function($) {
    'use strict';

    // Initialize dashboard when DOM is ready
    $(document).ready(function() {
        VillaDashboard.init();
    });

    // Main dashboard object
    window.VillaDashboard = {
        
        // Initialize all dashboard functionality
        init: function() {
            this.bindEvents();
            this.initTabSwitching();
            this.initFilters();
            this.initForms();
            this.initFileUploads();
            this.initProjectsFilters();
        },

        // Bind all event handlers
        bindEvents: function() {
            // Tab switching
            $(document).on('click', '.dashboard-tab', this.handleTabSwitch);
            
            // Property actions
            $(document).on('click', '.property-action', this.handlePropertyAction);
            
            // Ticket actions
            $(document).on('click', '.ticket-action', this.handleTicketAction);
            
            // Announcement actions
            $(document).on('click', '.announcement-action', this.handleAnnouncementAction);
            
            // Form submissions
            $(document).on('submit', '.villa-dashboard-form', this.handleFormSubmit);
            
            // Filter changes
            $(document).on('change', '.dashboard-filter', this.handleFilterChange);
            
            // File uploads
            $(document).on('change', '.file-upload-input', this.handleFileUpload);
            
            // Modal actions
            $(document).on('click', '[data-modal]', this.openModal);
            $(document).on('click', '.modal-close', this.closeModal);
            $(document).on('click', '.modal-overlay', this.closeModal);
        },

        // Initialize tab switching functionality
        initTabSwitching: function() {
            // Get current tab from URL or default to first tab
            var urlParams = new URLSearchParams(window.location.search);
            var currentTab = urlParams.get('tab') || $('.dashboard-tab').first().data('tab');
            
            // Show current tab
            this.showTab(currentTab);
        },

        // Initialize filter functionality
        initFilters: function() {
            // Property filters
            $('#property-filter').on('change', function() {
                VillaDashboard.filterProperties($(this).val());
            });
            
            // Ticket filters
            $('#ticket-status-filter, #ticket-property-filter, #ticket-category-filter').on('change', function() {
                VillaDashboard.filterTickets();
            });
            
            // Announcement filters
            $('#announcement-type-filter').on('change', function() {
                VillaDashboard.filterAnnouncements($(this).val());
            });
        },

        // Initialize form functionality
        initForms: function() {
            // Property form validation
            $('#property-form').on('submit', this.validatePropertyForm);
            
            // Ticket form validation
            $('#ticket-form').on('submit', this.validateTicketForm);
            
            // Profile form validation
            $('#profile-form').on('submit', this.validateProfileForm);
        },

        // Initialize file upload functionality
        initFileUploads: function() {
            // Property image uploads
            $('.property-image-upload').each(function() {
                VillaDashboard.initImageUpload($(this));
            });
            
            // Ticket attachment uploads
            $('.ticket-attachment-upload').each(function() {
                VillaDashboard.initFileUpload($(this));
            });
        },

        // Initialize projects filter functionality
        initProjectsFilters: function() {
            $(document).on('click', '.filter-tab', this.handleProjectFilter);
        },

        // Handle tab switching
        handleTabSwitch: function(e) {
            e.preventDefault();
            var tab = $(this).data('tab');
            VillaDashboard.showTab(tab);
            
            // Update URL without page reload
            var url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        },

        // Show specific tab
        showTab: function(tabName) {
            // Hide all tab content
            $('.dashboard-tab-content').hide();
            
            // Remove active class from all tabs
            $('.dashboard-tab').removeClass('active');
            
            // Show selected tab content
            $('#tab-' + tabName).show();
            
            // Add active class to selected tab
            $('.dashboard-tab[data-tab="' + tabName + '"]').addClass('active');
            
            // Load tab content if needed
            this.loadTabContent(tabName);
        },

        // Load tab content dynamically
        loadTabContent: function(tabName) {
            var $tabContent = $('#tab-' + tabName);
            
            // Check if content needs to be loaded
            if ($tabContent.hasClass('needs-loading')) {
                this.showLoading($tabContent);
                
                $.ajax({
                    url: villa_dashboard_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'villa_load_tab_content',
                        tab: tabName,
                        nonce: villa_dashboard_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $tabContent.html(response.data.content);
                            $tabContent.removeClass('needs-loading');
                        } else {
                            VillaDashboard.showError($tabContent, response.data.message);
                        }
                    },
                    error: function() {
                        VillaDashboard.showError($tabContent, 'Failed to load content');
                    }
                });
            }
        },

        // Handle property actions
        handlePropertyAction: function(e) {
            e.preventDefault();
            var action = $(this).data('action');
            var propertyId = $(this).data('property-id');
            
            switch(action) {
                case 'edit':
                    VillaDashboard.editProperty(propertyId);
                    break;
                case 'delete':
                    VillaDashboard.deleteProperty(propertyId);
                    break;
                case 'toggle-listing':
                    VillaDashboard.togglePropertyListing(propertyId);
                    break;
            }
        },

        // Handle ticket actions
        handleTicketAction: function(e) {
            e.preventDefault();
            var action = $(this).data('action');
            var ticketId = $(this).data('ticket-id');
            
            switch(action) {
                case 'edit':
                    VillaDashboard.editTicket(ticketId);
                    break;
                case 'close':
                    VillaDashboard.closeTicket(ticketId);
                    break;
                case 'add-comment':
                    VillaDashboard.addTicketComment(ticketId);
                    break;
            }
        },

        // Handle announcement actions
        handleAnnouncementAction: function(e) {
            e.preventDefault();
            var action = $(this).data('action');
            var announcementId = $(this).data('announcement-id');
            
            switch(action) {
                case 'mark-read':
                    VillaDashboard.markAnnouncementRead(announcementId);
                    break;
                case 'mark-unread':
                    VillaDashboard.markAnnouncementUnread(announcementId);
                    break;
            }
        },

        // Handle form submissions
        handleFormSubmit: function(e) {
            e.preventDefault();
            var $form = $(this);
            var formData = new FormData(this);
            
            // Add action and nonce
            formData.append('action', $form.data('action'));
            formData.append('nonce', villa_dashboard_ajax.nonce);
            
            // Show loading state
            VillaDashboard.showFormLoading($form);
            
            $.ajax({
                url: villa_dashboard_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    VillaDashboard.hideFormLoading($form);
                    
                    if (response.success) {
                        VillaDashboard.showFormSuccess($form, response.data.message);
                        
                        // Refresh content if needed
                        if (response.data.refresh) {
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        VillaDashboard.showFormError($form, response.data.message);
                    }
                },
                error: function() {
                    VillaDashboard.hideFormLoading($form);
                    VillaDashboard.showFormError($form, 'An error occurred. Please try again.');
                }
            });
        },

        // Filter properties
        filterProperties: function(filter) {
            $('.property-card').each(function() {
                var $card = $(this);
                var show = true;
                
                if (filter && filter !== 'all') {
                    var propertyType = $card.data('property-type');
                    var listingStatus = $card.data('listing-status');
                    
                    if (filter === 'listed') {
                        show = listingStatus === 'for_sale' || listingStatus === 'for_rent';
                    } else if (filter === 'not_listed') {
                        show = listingStatus === 'not_listed';
                    } else {
                        show = propertyType === filter;
                    }
                }
                
                $card.toggle(show);
            });
        },

        // Filter tickets
        filterTickets: function() {
            var statusFilter = $('#ticket-status-filter').val();
            var propertyFilter = $('#ticket-property-filter').val();
            var categoryFilter = $('#ticket-category-filter').val();
            
            $('.ticket-item').each(function() {
                var $ticket = $(this);
                var show = true;
                
                if (statusFilter && statusFilter !== 'all') {
                    show = show && $ticket.data('status') === statusFilter;
                }
                
                if (propertyFilter && propertyFilter !== 'all') {
                    show = show && $ticket.data('property-id') == propertyFilter;
                }
                
                if (categoryFilter && categoryFilter !== 'all') {
                    show = show && $ticket.data('category') === categoryFilter;
                }
                
                $ticket.toggle(show);
            });
        },

        // Filter announcements
        filterAnnouncements: function(filter) {
            $('.announcement-item').each(function() {
                var $announcement = $(this);
                var show = true;
                
                if (filter && filter !== 'all') {
                    if (filter === 'unread') {
                        show = $announcement.hasClass('unread');
                    } else {
                        show = $announcement.data('type') === filter;
                    }
                }
                
                $announcement.toggle(show);
            });
        },

        // Edit property
        editProperty: function(propertyId) {
            window.location.href = '?tab=properties&action=edit&property_id=' + propertyId;
        },

        // Delete property
        deleteProperty: function(propertyId) {
            if (confirm('Are you sure you want to delete this property?')) {
                $.ajax({
                    url: villa_dashboard_ajax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'villa_delete_property',
                        property_id: propertyId,
                        nonce: villa_dashboard_ajax.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.property-card[data-property-id="' + propertyId + '"]').fadeOut();
                            VillaDashboard.showNotification('Property deleted successfully', 'success');
                        } else {
                            VillaDashboard.showNotification(response.data.message, 'error');
                        }
                    }
                });
            }
        },

        // Toggle property listing
        togglePropertyListing: function(propertyId) {
            $.ajax({
                url: villa_dashboard_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_toggle_property_listing',
                    property_id: propertyId,
                    nonce: villa_dashboard_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload(); // Refresh to show updated status
                    } else {
                        VillaDashboard.showNotification(response.data.message, 'error');
                    }
                }
            });
        },

        // Mark announcement as read
        markAnnouncementRead: function(announcementId) {
            $.ajax({
                url: villa_dashboard_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_mark_announcement_read',
                    announcement_id: announcementId,
                    nonce: villa_dashboard_ajax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.announcement-item[data-announcement-id="' + announcementId + '"]').removeClass('unread');
                    }
                }
            });
        },

        // Initialize image upload
        initImageUpload: function($element) {
            $element.on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $element.siblings('.image-preview').html('<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;">');
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        // Show loading state
        showLoading: function($element) {
            $element.html('<div class="loading">Loading...</div>');
        },

        // Show error message
        showError: function($element, message) {
            $element.html('<div class="error">' + message + '</div>');
        },

        // Show form loading state
        showFormLoading: function($form) {
            $form.find('button[type="submit"]').prop('disabled', true).text('Saving...');
        },

        // Hide form loading state
        hideFormLoading: function($form) {
            $form.find('button[type="submit"]').prop('disabled', false).text('Save');
        },

        // Show form success message
        showFormSuccess: function($form, message) {
            $form.prepend('<div class="success">' + message + '</div>');
            setTimeout(function() {
                $form.find('.success').fadeOut();
            }, 5000);
        },

        // Show form error message
        showFormError: function($form, message) {
            $form.prepend('<div class="error">' + message + '</div>');
            setTimeout(function() {
                $form.find('.error').fadeOut();
            }, 5000);
        },

        // Show notification
        showNotification: function(message, type) {
            var $notification = $('<div class="notification notification-' + type + '">' + message + '</div>');
            $('body').append($notification);
            
            setTimeout(function() {
                $notification.fadeOut(function() {
                    $(this).remove();
                });
            }, 5000);
        },

        // Open modal
        openModal: function(e) {
            e.preventDefault();
            var modalId = $(this).data('modal');
            $('#' + modalId).addClass('active');
        },

        // Close modal
        closeModal: function(e) {
            e.preventDefault();
            $('.modal').removeClass('active');
        },

        // Validate property form
        validatePropertyForm: function(e) {
            var $form = $(this);
            var isValid = true;
            
            // Check required fields
            $form.find('[required]').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                VillaDashboard.showFormError($form, 'Please fill in all required fields.');
            }
        },

        // Validate ticket form
        validateTicketForm: function(e) {
            var $form = $(this);
            var isValid = true;
            
            // Check required fields
            if (!$form.find('#ticket_title').val()) {
                isValid = false;
                $form.find('#ticket_title').addClass('error');
            }
            
            if (!$form.find('#ticket_description').val()) {
                isValid = false;
                $form.find('#ticket_description').addClass('error');
            }
            
            if (!isValid) {
                e.preventDefault();
                VillaDashboard.showFormError($form, 'Please fill in all required fields.');
            }
        },

        // Validate profile form
        validateProfileForm: function(e) {
            var $form = $(this);
            var isValid = true;
            
            // Validate email
            var email = $form.find('#email').val();
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                isValid = false;
                $form.find('#email').addClass('error');
                VillaDashboard.showFormError($form, 'Please enter a valid email address.');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        },

        // Handle project filter clicks
        handleProjectFilter: function(e) {
            e.preventDefault();
            
            var $tab = $(this);
            var filter = $tab.data('filter');
            
            // Update active tab
            $('.filter-tab').removeClass('active');
            $tab.addClass('active');
            
            // Filter project cards
            VillaDashboard.filterProjects(filter);
        },

        // Filter projects based on selected criteria
        filterProjects: function(filter) {
            var $cards = $('.project-card');
            
            if (filter === 'all') {
                $cards.removeClass('hidden').show();
            } else if (filter === 'my') {
                $cards.each(function() {
                    var $card = $(this);
                    if ($card.data('my') === true || $card.data('my') === 'true') {
                        $card.removeClass('hidden').show();
                    } else {
                        $card.addClass('hidden').hide();
                    }
                });
            } else {
                $cards.each(function() {
                    var $card = $(this);
                    var cardType = $card.data('type');
                    
                    if (cardType === filter) {
                        $card.removeClass('hidden').show();
                    } else {
                        $card.addClass('hidden').hide();
                    }
                });
            }
            
            // Show/hide no results message
            var visibleCards = $('.project-card:visible').length;
            if (visibleCards === 0) {
                if ($('.no-filtered-projects').length === 0) {
                    $('.projects-grid').append('<div class="no-filtered-projects no-projects"><h3>No Projects Found</h3><p>No projects match the selected filter.</p></div>');
                }
                $('.no-filtered-projects').show();
            } else {
                $('.no-filtered-projects').hide();
            }
        }
    };

})(jQuery);

// CSS for notifications and loading states
var dashboardStyles = `
<style>
.loading {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 4px;
    color: #fff;
    font-weight: 500;
    z-index: 9999;
    max-width: 300px;
}

.notification-success {
    background: #28a745;
}

.notification-error {
    background: #dc3545;
}

.notification-warning {
    background: #ffc107;
    color: #212529;
}

.form-row input.error,
.form-row textarea.error,
.form-row select.error {
    border-color: #dc3545;
    box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.25);
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    z-index: 9999;
}

.modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 8px;
    max-width: 500px;
    width: 90%;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e1e5e9;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #6c757d;
}

.modal-close:hover {
    color: #495057;
}
</style>
`;

// Inject styles
$('head').append(dashboardStyles);
