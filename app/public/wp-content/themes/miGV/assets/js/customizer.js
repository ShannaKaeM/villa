/**
 * Customizer JS for miGV theme
 * 
 * @package miGV
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Site title and description
    wp.customize('blogname', function(value) {
        value.bind(function(to) {
            $('.site-title a').text(to);
        });
    });

    wp.customize('blogdescription', function(value) {
        value.bind(function(to) {
            $('.site-description').text(to);
        });
    });

    // Header text color
    wp.customize('header_textcolor', function(value) {
        value.bind(function(to) {
            if ('blank' === to) {
                $('.site-title, .site-description').css({
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                });
            } else {
                $('.site-title, .site-description').css({
                    'clip': 'auto',
                    'position': 'relative'
                });
                $('.site-title a, .site-description').css({
                    'color': to
                });
            }
        });
    });

    // Primary color
    wp.customize('migv_primary_color', function(value) {
        value.bind(function(to) {
            updateColorProperty('--migv-primary', to);
            updateColorProperty('--migv-primary-light', colorMix(to, '#ffffff', 60));
            updateColorProperty('--migv-primary-dark', colorMix(to, '#000000', 80));
        });
    });

    // Secondary color
    wp.customize('migv_secondary_color', function(value) {
        value.bind(function(to) {
            updateColorProperty('--migv-secondary', to);
            updateColorProperty('--migv-secondary-light', colorMix(to, '#ffffff', 60));
            updateColorProperty('--migv-secondary-dark', colorMix(to, '#000000', 80));
        });
    });

    // Body font size
    wp.customize('migv_body_font_size', function(value) {
        value.bind(function(to) {
            updateProperty('--migv-font-size-base', to + 'px');
        });
    });

    // Container width
    wp.customize('migv_container_width', function(value) {
        value.bind(function(to) {
            updateProperty('--migv-container-width', to + 'px');
        });
    });

    // Footer copyright
    wp.customize('migv_footer_copyright', function(value) {
        value.bind(function(to) {
            $('.footer-copyright').html(to);
        });
    });

    /**
     * Update CSS custom property
     */
    function updateProperty(property, value) {
        document.documentElement.style.setProperty(property, value);
    }

    /**
     * Update color CSS custom property
     */
    function updateColorProperty(property, value) {
        document.documentElement.style.setProperty(property, value);
    }

    /**
     * Simple color mixing function
     * Approximates CSS color-mix() for preview
     */
    function colorMix(color1, color2, percentage) {
        // Convert hex to RGB
        const hex1 = color1.replace('#', '');
        const hex2 = color2.replace('#', '');
        
        const r1 = parseInt(hex1.substr(0, 2), 16);
        const g1 = parseInt(hex1.substr(2, 2), 16);
        const b1 = parseInt(hex1.substr(4, 2), 16);
        
        const r2 = parseInt(hex2.substr(0, 2), 16);
        const g2 = parseInt(hex2.substr(2, 2), 16);
        const b2 = parseInt(hex2.substr(4, 2), 16);
        
        // Mix colors
        const ratio = percentage / 100;
        const r = Math.round(r1 * ratio + r2 * (1 - ratio));
        const g = Math.round(g1 * ratio + g2 * (1 - ratio));
        const b = Math.round(b1 * ratio + b2 * (1 - ratio));
        
        // Convert back to hex
        return '#' + 
            ('0' + r.toString(16)).slice(-2) +
            ('0' + g.toString(16)).slice(-2) +
            ('0' + b.toString(16)).slice(-2);
    }

})(jQuery);
