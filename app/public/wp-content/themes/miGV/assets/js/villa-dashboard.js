/**
 * Villa Dashboard JavaScript
 * Handles interactive functionality for the dashboard
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Projects Filter Functionality
    const filterTabs = document.querySelectorAll('.filter-tab');
    const projectCards = document.querySelectorAll('.project-card');
    
    if (filterTabs.length > 0 && projectCards.length > 0) {
        filterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');
                
                // Update active tab
                filterTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter project cards
                projectCards.forEach(card => {
                    const cardType = card.getAttribute('data-type');
                    const isMyProject = card.getAttribute('data-my') === 'true';
                    
                    let shouldShow = false;
                    
                    switch(filter) {
                        case 'all':
                            shouldShow = true;
                            break;
                        case 'my':
                            shouldShow = isMyProject;
                            break;
                        case 'roadmap':
                            shouldShow = cardType === 'roadmap';
                            break;
                        case 'committee_board':
                            shouldShow = cardType === 'committee_board';
                            break;
                        case 'survey':
                            shouldShow = cardType === 'survey';
                            break;
                        case 'event':
                            shouldShow = cardType === 'event';
                            break;
                        default:
                            shouldShow = cardType === filter;
                    }
                    
                    if (shouldShow) {
                        card.style.display = 'block';
                        card.style.opacity = '1';
                    } else {
                        card.style.display = 'none';
                        card.style.opacity = '0';
                    }
                });
                
                // Update visible count
                updateVisibleCount();
            });
        });
    }
    
    function updateVisibleCount() {
        const visibleCards = document.querySelectorAll('.project-card[style*="display: block"], .project-card:not([style*="display: none"])');
        const totalCards = document.querySelectorAll('.project-card');
        
        // Update any count displays if they exist
        const countDisplay = document.querySelector('.projects-count');
        if (countDisplay) {
            countDisplay.textContent = `Showing ${visibleCards.length} of ${totalCards.length} projects`;
        }
    }
    
    // Initialize with all projects visible
    updateVisibleCount();
    
    // Smooth scroll for dashboard navigation
    const dashboardTabs = document.querySelectorAll('.dashboard-tab');
    dashboardTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            // Add smooth transition effect
            const targetSection = document.querySelector('.dashboard-content');
            if (targetSection) {
                targetSection.style.opacity = '0.7';
                setTimeout(() => {
                    targetSection.style.opacity = '1';
                }, 150);
            }
        });
    });
    
    // Add loading states for AJAX interactions
    const ajaxButtons = document.querySelectorAll('[data-ajax]');
    ajaxButtons.forEach(button => {
        button.addEventListener('click', function() {
            this.classList.add('loading');
            this.disabled = true;
            
            // Remove loading state after 3 seconds as fallback
            setTimeout(() => {
                this.classList.remove('loading');
                this.disabled = false;
            }, 3000);
        });
    });
    
    // Enhanced card hover effects
    const cards = document.querySelectorAll('.project-card, .property-card, .ticket-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
});
