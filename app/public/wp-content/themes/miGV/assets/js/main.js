/**
 * Main JavaScript for miGV theme
 * 
 * @package miGV
 * @version 1.0.0
 */

(function() {
    'use strict';

    // DOM Ready
    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initSmoothScroll();
        initBackToTop();
        initAccessibility();
    });

    /**
     * Initialize mobile menu functionality
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.main-navigation');
        const primaryMenu = document.querySelector('#primary-menu');

        if (!menuToggle || !navigation) {
            return;
        }

        menuToggle.addEventListener('click', function() {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
            
            menuToggle.setAttribute('aria-expanded', !isExpanded);
            navigation.classList.toggle('toggled');
            
            // Toggle menu icon animation
            menuToggle.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!navigation.contains(event.target) && !menuToggle.contains(event.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('toggled');
                menuToggle.classList.remove('active');
            }
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && navigation.classList.contains('toggled')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('toggled');
                menuToggle.classList.remove('active');
                menuToggle.focus();
            }
        });

        // Handle submenu toggles on mobile
        if (primaryMenu) {
            const submenuToggles = primaryMenu.querySelectorAll('.menu-item-has-children > a');
            
            submenuToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function(event) {
                    if (window.innerWidth <= 768) {
                        event.preventDefault();
                        const submenu = this.nextElementSibling;
                        const isExpanded = this.getAttribute('aria-expanded') === 'true';
                        
                        this.setAttribute('aria-expanded', !isExpanded);
                        submenu.classList.toggle('toggled');
                    }
                });
            });
        }
    }

    /**
     * Initialize smooth scrolling for anchor links
     */
    function initSmoothScroll() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    event.preventDefault();
                    
                    const headerHeight = document.querySelector('.site-header').offsetHeight;
                    const targetPosition = targetElement.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    /**
     * Initialize back to top functionality
     */
    function initBackToTop() {
        // Create back to top button
        const backToTop = document.createElement('button');
        backToTop.className = 'back-to-top';
        backToTop.innerHTML = '↑';
        backToTop.setAttribute('aria-label', 'Back to top');
        backToTop.setAttribute('title', 'Back to top');
        
        document.body.appendChild(backToTop);

        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        // Scroll to top when clicked
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    /**
     * Initialize accessibility enhancements
     */
    function initAccessibility() {
        // Skip link functionality
        const skipLink = document.querySelector('.skip-link');
        if (skipLink) {
            skipLink.addEventListener('click', function(event) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.focus();
                    target.scrollIntoView();
                }
            });
        }

        // Focus management for dropdowns
        const dropdownToggles = document.querySelectorAll('.menu-item-has-children > a');
        
        dropdownToggles.forEach(function(toggle) {
            toggle.addEventListener('keydown', function(event) {
                const submenu = this.nextElementSibling;
                
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', !isExpanded);
                    submenu.classList.toggle('toggled');
                    
                    if (!isExpanded) {
                        const firstLink = submenu.querySelector('a');
                        if (firstLink) {
                            firstLink.focus();
                        }
                    }
                }
            });
        });

        // Trap focus in mobile menu
        const navigation = document.querySelector('.main-navigation');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (navigation && menuToggle) {
            navigation.addEventListener('keydown', function(event) {
                if (event.key === 'Tab' && this.classList.contains('toggled')) {
                    const focusableElements = this.querySelectorAll('a, button');
                    const firstElement = focusableElements[0];
                    const lastElement = focusableElements[focusableElements.length - 1];
                    
                    if (event.shiftKey && document.activeElement === firstElement) {
                        event.preventDefault();
                        lastElement.focus();
                    } else if (!event.shiftKey && document.activeElement === lastElement) {
                        event.preventDefault();
                        firstElement.focus();
                    }
                }
            });
        }
    }

    /**
     * Utility function to debounce events
     */
    function debounce(func, wait, immediate) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    /**
     * Handle window resize events
     */
    window.addEventListener('resize', debounce(function() {
        const navigation = document.querySelector('.main-navigation');
        const menuToggle = document.querySelector('.menu-toggle');
        
        // Reset mobile menu on desktop
        if (window.innerWidth > 768 && navigation && menuToggle) {
            navigation.classList.remove('toggled');
            menuToggle.setAttribute('aria-expanded', 'false');
            menuToggle.classList.remove('active');
        }
    }, 250));

})();
