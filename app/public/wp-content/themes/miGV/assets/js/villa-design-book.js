/**
 * Villa DesignBook JavaScript
 * Handles color manipulation, OKLCH conversion, and live preview updates
 */

(function($) {
    'use strict';

    // Color conversion utilities
    const ColorUtils = {
        /**
         * Convert hex to OKLCH
         */
        hexToOklch: function(hex) {
            // First convert hex to RGB
            const rgb = this.hexToRgb(hex);
            if (!rgb) return null;
            
            // Convert RGB to OKLCH
            return this.rgbToOklch(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert OKLCH to hex
         */
        oklchToHex: function(l, c, h) {
            // Convert OKLCH to RGB first
            const rgb = this.oklchToRgb(l, c, h);
            if (!rgb) return null;
            
            // Convert RGB to hex
            return this.rgbToHex(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert hex to RGB
         */
        hexToRgb: function(hex) {
            const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
            return result ? {
                r: parseInt(result[1], 16),
                g: parseInt(result[2], 16),
                b: parseInt(result[3], 16)
            } : null;
        },

        /**
         * Convert RGB to hex
         */
        rgbToHex: function(r, g, b) {
            const toHex = (c) => {
                const hex = Math.round(Math.max(0, Math.min(255, c))).toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            };
            return '#' + toHex(r) + toHex(g) + toHex(b);
        },

        /**
         * Convert RGB to OKLCH (simplified approximation)
         */
        rgbToOklch: function(r, g, b) {
            // Normalize RGB values
            r = r / 255;
            g = g / 255;
            b = b / 255;

            // Convert to linear RGB
            const toLinear = (c) => c <= 0.04045 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
            const rLin = toLinear(r);
            const gLin = toLinear(g);
            const bLin = toLinear(b);

            // Convert to XYZ (simplified D65)
            const x = 0.4124564 * rLin + 0.3575761 * gLin + 0.1804375 * bLin;
            const y = 0.2126729 * rLin + 0.7151522 * gLin + 0.0721750 * bLin;
            const z = 0.0193339 * rLin + 0.1191920 * gLin + 0.9503041 * bLin;

            // Simplified OKLCH conversion (approximation)
            const l = Math.cbrt(y) * 100; // Lightness (0-100)
            const a = (x - y) * 500; // Green-Red axis
            const bAxis = (y - z) * 200; // Blue-Yellow axis
            
            const c = Math.sqrt(a * a + bAxis * bAxis) / 100; // Chroma (0-0.4)
            let h = Math.atan2(bAxis, a) * 180 / Math.PI; // Hue (0-360)
            if (h < 0) h += 360;

            return {
                l: Math.max(0, Math.min(100, l)),
                c: Math.max(0, Math.min(0.4, c)),
                h: h
            };
        },

        /**
         * Convert OKLCH to RGB (simplified approximation)
         */
        oklchToRgb: function(l, c, h) {
            // Convert to Lab-like values
            const hRad = h * Math.PI / 180;
            const a = c * 100 * Math.cos(hRad);
            const bAxis = c * 100 * Math.sin(hRad);

            // Convert to XYZ (simplified)
            const y = Math.pow(l / 100, 3);
            const x = y + a / 500;
            const z = y - bAxis / 200;

            // Convert to linear RGB
            const rLin = 3.2404542 * x - 1.5371385 * y - 0.4985314 * z;
            const gLin = -0.9692660 * x + 1.8760108 * y + 0.0415560 * z;
            const bLin = 0.0556434 * x - 0.2040259 * y + 1.0572252 * z;

            // Convert to sRGB
            const fromLinear = (c) => c <= 0.0031308 ? c * 12.92 : 1.055 * Math.pow(c, 1 / 2.4) - 0.055;
            
            return {
                r: Math.round(Math.max(0, Math.min(255, fromLinear(rLin) * 255))),
                g: Math.round(Math.max(0, Math.min(255, fromLinear(gLin) * 255))),
                b: Math.round(Math.max(0, Math.min(255, fromLinear(bLin) * 255)))
            };
        }
    };

    // Main DesignBook functionality
    const DesignBook = {
        init: function() {
            this.bindEvents();
            this.initColorPickers();
            this.initTextBook();
            this.updatePreview();
        },

        bindEvents: function() {
            // Color events
            $(document).on('change', '.color-picker', this.onColorChange);
            $(document).on('input', '.color-slider', this.onSliderChange);
            $(document).on('click', '#save-colors', this.saveColors);
            $(document).on('click', '#reset-colors', this.resetColors);
            $(document).on('click', '#export-tokens', this.exportTokens);
            $(document).on('click', '#import-tokens', this.triggerImport);
            $(document).on('change', '#import-file', this.importTokens);
            
            // Text events
            $(document).on('change', '.text-style-control', this.onTextStyleChange);
            $(document).on('click', '#save-text-styles', this.saveTextStyles);
            $(document).on('click', '#reset-text-styles', this.resetTextStyles);
        },

        initColorPickers: function() {
            $('.color-swatch').each(function() {
                const $swatch = $(this);
                const $colorPicker = $swatch.find('.color-picker');
                const hex = $colorPicker.val();
                
                // Convert hex to OKLCH and set slider values
                const oklch = ColorUtils.hexToOklch(hex);
                if (oklch) {
                    $swatch.find('.lightness-slider').val(oklch.l);
                    $swatch.find('.chroma-slider').val(oklch.c);
                    $swatch.find('.hue-slider').val(oklch.h);
                }
                
                // Update preview box
                $swatch.find('.color-preview-box').css('background-color', hex);
            });
        },

        initTextBook: function() {
            // Initialize text style previews
            this.updateTextPreviews();
        },

        onColorChange: function(e) {
            const $colorPicker = $(e.target);
            const $swatch = $colorPicker.closest('.color-swatch');
            const hex = $colorPicker.val();
            
            // Update preview box
            $swatch.find('.color-preview-box').css('background-color', hex);
            
            // Convert to OKLCH and update sliders
            const oklch = ColorUtils.hexToOklch(hex);
            if (oklch) {
                $swatch.find('.lightness-slider').val(oklch.l);
                $swatch.find('.chroma-slider').val(oklch.c);
                $swatch.find('.hue-slider').val(oklch.h);
            }
            
            DesignBook.updatePreview();
        },

        onSliderChange: function(e) {
            const $slider = $(e.target);
            const $swatch = $slider.closest('.color-swatch');
            
            // Get OKLCH values from sliders
            const l = parseFloat($swatch.find('.lightness-slider').val());
            const c = parseFloat($swatch.find('.chroma-slider').val());
            const h = parseFloat($swatch.find('.hue-slider').val());
            
            // Convert to hex
            const hex = ColorUtils.oklchToHex(l, c, h);
            if (hex) {
                // Update color picker and preview
                $swatch.find('.color-picker').val(hex);
                $swatch.find('.color-preview-box').css('background-color', hex);
            }
            
            DesignBook.updatePreview();
        },

        onTextStyleChange: function(e) {
            const $control = $(e.target);
            const styleType = $control.closest('.text-style-card').data('style');
            
            // Update the individual card preview
            DesignBook.updateTextStylePreview(styleType);
            
            // Update the live preview showcase
            DesignBook.updateTextPreviews();
        },

        updateTextStylePreview: function(styleType) {
            const $card = $(`.text-style-card[data-style="${styleType}"]`);
            const $preview = $card.find('.preview-text');
            
            // Get current values
            const htmlTag = $card.find('select[name$="[html_tag]"]').val();
            const fontSize = $card.find('select[name$="[font_size]"]').val();
            const fontWeight = $card.find('select[name$="[font_weight]"]').val();
            const textTransform = $card.find('select[name$="[text_transform]"]').val();
            const letterSpacing = $card.find('select[name$="[letter_spacing]"]').val();
            
            // Apply styles to preview
            const styles = {
                'font-size': this.getFontSizeValue(fontSize),
                'font-weight': fontWeight,
                'text-transform': textTransform,
                'letter-spacing': this.getLetterSpacingValue(letterSpacing)
            };
            
            $preview.css(styles);
        },

        updateTextPreviews: function() {
            // Update all text previews in the live showcase
            $('.text-preview-showcase .preview-text').each(function() {
                const $preview = $(this);
                const styleType = $preview.data('style');
                const $card = $(`.text-style-card[data-style="${styleType}"]`);
                
                if ($card.length) {
                    // Get current values
                    const htmlTag = $card.find('select[name$="[html_tag]"]').val();
                    const fontSize = $card.find('select[name$="[font_size]"]').val();
                    const fontWeight = $card.find('select[name$="[font_weight]"]').val();
                    const textTransform = $card.find('select[name$="[text_transform]"]').val();
                    const letterSpacing = $card.find('select[name$="[letter_spacing]"]').val();
                    
                    // Apply styles
                    const styles = {
                        'font-size': DesignBook.getFontSizeValue(fontSize),
                        'font-weight': fontWeight,
                        'text-transform': textTransform,
                        'letter-spacing': DesignBook.getLetterSpacingValue(letterSpacing)
                    };
                    
                    $preview.css(styles);
                    
                    // Update the HTML tag if needed
                    if (htmlTag && $preview.prop('tagName').toLowerCase() !== htmlTag.toLowerCase()) {
                        const newElement = $(`<${htmlTag}>`).addClass($preview.attr('class')).html($preview.html());
                        newElement.css(styles);
                        $preview.replaceWith(newElement);
                    }
                }
            });
        },

        getFontSizeValue: function(sizeKey) {
            const sizeMap = {
                'small': 'var(--wp--preset--font-size--small)',
                'medium': 'var(--wp--preset--font-size--medium)',
                'large': 'var(--wp--preset--font-size--large)',
                'x-large': 'var(--wp--preset--font-size--x-large)',
                'xx-large': 'var(--wp--preset--font-size--xx-large)'
            };
            return sizeMap[sizeKey] || sizeKey;
        },

        getLetterSpacingValue: function(spacingKey) {
            const spacingMap = {
                'normal': 'normal',
                '0.05em': '0.05em',
                '0.1em': '0.1em',
                '0.15em': '0.15em',
                '0.2em': '0.2em'
            };
            return spacingMap[spacingKey] || spacingKey;
        },

        updatePreview: function() {
            // Update CSS custom properties for live preview
            $('.color-swatch').each(function() {
                const $swatch = $(this);
                const slug = $swatch.data('slug');
                const color = $swatch.find('.color-picker').val();
                
                // Update CSS custom property
                document.documentElement.style.setProperty(`--wp--preset--color--${slug}`, color);
            });
        },

        saveColors: function(e) {
            e.preventDefault();
            
            // Collect all color data
            const colors = [];
            $('.color-swatch').each(function() {
                const $swatch = $(this);
                const slug = $swatch.data('slug');
                const color = $swatch.find('.color-picker').val();
                const name = $swatch.find('label').first().text();
                
                colors.push({
                    slug: slug,
                    color: color,
                    name: name
                });
            });
            
            // Send to server
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_save_design_tokens',
                    nonce: villaDesignBook.nonce,
                    tokens: {
                        colors: colors
                    }
                },
                success: function(response) {
                    if (response.success) {
                        DesignBook.showNotification('Colors saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Failed to save colors: ' + response.data, 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Network error occurred', 'error');
                }
            });
        },

        resetColors: function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to reset all colors to default? This cannot be undone.')) {
                location.reload();
            }
        },

        exportTokens: function(e) {
            e.preventDefault();
            
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_export_design_tokens',
                    nonce: villaDesignBook.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Create download link
                        const dataStr = JSON.stringify(response.data, null, 2);
                        const dataBlob = new Blob([dataStr], {type: 'application/json'});
                        const url = URL.createObjectURL(dataBlob);
                        
                        const link = document.createElement('a');
                        link.href = url;
                        link.download = 'villa-design-tokens.json';
                        link.click();
                        
                        URL.revokeObjectURL(url);
                        DesignBook.showNotification('Design tokens exported successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Failed to export tokens', 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Network error occurred', 'error');
                }
            });
        },

        triggerImport: function(e) {
            e.preventDefault();
            $('#import-file').click();
        },

        importTokens: function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const tokens = JSON.parse(e.target.result);
                    
                    $.ajax({
                        url: villaDesignBook.ajax_url,
                        type: 'POST',
                        data: {
                            action: 'villa_import_design_tokens',
                            nonce: villaDesignBook.nonce,
                            tokens: JSON.stringify(tokens)
                        },
                        success: function(response) {
                            if (response.success) {
                                DesignBook.showNotification('Design tokens imported successfully!', 'success');
                                setTimeout(() => location.reload(), 1500);
                            } else {
                                DesignBook.showNotification('Failed to import tokens: ' + response.data, 'error');
                            }
                        },
                        error: function() {
                            DesignBook.showNotification('Network error occurred', 'error');
                        }
                    });
                } catch (error) {
                    DesignBook.showNotification('Invalid JSON file', 'error');
                }
            };
            reader.readAsText(file);
        },

        saveTextStyles: function(e) {
            e.preventDefault();
            
            // Collect all text style data
            const textStyles = {};
            $('.text-style-card').each(function() {
                const $card = $(this);
                const styleType = $card.data('style');
                
                textStyles[styleType] = {
                    html_tag: $card.find('select[name$="[html_tag]"]').val(),
                    font_size: $card.find('select[name$="[font_size]"]').val(),
                    font_weight: $card.find('select[name$="[font_weight]"]').val(),
                    text_transform: $card.find('select[name$="[text_transform]"]').val(),
                    letter_spacing: $card.find('select[name$="[letter_spacing]"]').val()
                };
            });
            
            // Save via AJAX
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_save_text_styles',
                    nonce: villaDesignBook.nonce,
                    text_styles: JSON.stringify(textStyles)
                },
                success: function(response) {
                    if (response.success) {
                        DesignBook.showNotification('Text styles saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Failed to save text styles', 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Network error occurred', 'error');
                }
            });
        },

        resetTextStyles: function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to reset all text styles to default values?')) {
                $.ajax({
                    url: villaDesignBook.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'villa_reset_text_styles',
                        nonce: villaDesignBook.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            DesignBook.showNotification('Text styles reset successfully!', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            DesignBook.showNotification('Failed to reset text styles', 'error');
                        }
                    },
                    error: function() {
                        DesignBook.showNotification('Network error occurred', 'error');
                    }
                });
            }
        },

        showNotification: function(message, type) {
            // Remove existing notifications
            $('.villa-notification').remove();
            
            // Create notification
            const $notification = $('<div class="villa-notification villa-notification--' + type + '">' + message + '</div>');
            $('body').append($notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                $notification.fadeOut(() => $notification.remove());
            }, 3000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        DesignBook.init();
    });

})(jQuery);

// Add notification styles
const notificationCSS = `
    .villa-notification {
        position: fixed;
        top: 32px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideInRight 0.3s ease;
    }
    
    .villa-notification--success {
        background: #10b981;
    }
    
    .villa-notification--error {
        background: #ef4444;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100%);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
`;

// Inject notification styles
const style = document.createElement('style');
style.textContent = notificationCSS;
document.head.appendChild(style);

// TextBook functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeTextBook();
});

function initializeTextBook() {
    // Tab switching functionality
    const tabs = document.querySelectorAll('.textbook-tab');
    const tabContents = document.querySelectorAll('.textbook-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.dataset.tab;
            
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            const targetContent = document.getElementById(targetTab);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });

    // Text style controls
    const textStyleControls = document.querySelectorAll('.text-style-controls select');
    textStyleControls.forEach(control => {
        control.addEventListener('change', updateTextStylePreview);
    });

    // Base style controls
    const baseStyleControls = document.querySelectorAll('.base-style-control input');
    baseStyleControls.forEach(control => {
        control.addEventListener('input', updateBaseStylePreview);
    });

    // Save and reset buttons
    const saveButton = document.getElementById('save-text-styles');
    const resetButton = document.getElementById('reset-text-styles');
    const saveBaseButton = document.getElementById('save-base-styles');
    const resetBaseButton = document.getElementById('reset-base-styles');

    if (saveButton) {
        saveButton.addEventListener('click', saveTextStyles);
    }
    if (resetButton) {
        resetButton.addEventListener('click', resetTextStyles);
    }
    if (saveBaseButton) {
        saveBaseButton.addEventListener('click', saveBaseStyles);
    }
    if (resetBaseButton) {
        resetBaseButton.addEventListener('click', resetBaseStyles);
    }

    // Initialize preview
    updateLivePreview();
}

function updateTextStylePreview() {
    const card = this.closest('.text-style-card');
    const styleType = card.dataset.style;
    const preview = card.querySelector('.preview-element');
    
    if (!preview) return;

    const htmlTag = card.querySelector('[data-control="html-tag"]').value;
    const fontSize = card.querySelector('[data-control="font-size"]').value;
    const fontWeight = card.querySelector('[data-control="font-weight"]').value;
    const textTransform = card.querySelector('[data-control="text-transform"]').value;
    const letterSpacing = card.querySelector('[data-control="letter-spacing"]').value;
    const color = card.querySelector('[data-control="color"]')?.value || '';
    const fontFamily = card.querySelector('[data-control="font-family"]')?.value || '';

    // Update preview element
    preview.style.fontSize = `var(--wp--preset--font-size--${fontSize})`;
    preview.style.fontWeight = fontWeight;
    preview.style.textTransform = textTransform;
    preview.style.letterSpacing = letterSpacing;
    
    if (color) {
        preview.style.color = color;
    }
    if (fontFamily) {
        preview.style.fontFamily = fontFamily;
    }

    // Update live preview
    updateLivePreview();
}

function updateBaseStylePreview() {
    // Update live preview when base styles change
    updateLivePreview();
}

function updateLivePreview() {
    const previewElements = document.querySelectorAll('.preview-text');
    
    previewElements.forEach(element => {
        const styleType = element.classList[1]; // e.g., 'pre-title', 'title', etc.
        const card = document.querySelector(`[data-style="${styleType}"]`);
        
        if (!card) return;

        const htmlTag = card.querySelector('[data-control="html-tag"]')?.value;
        const fontSize = card.querySelector('[data-control="font-size"]')?.value;
        const fontWeight = card.querySelector('[data-control="font-weight"]')?.value;
        const textTransform = card.querySelector('[data-control="text-transform"]')?.value;
        const letterSpacing = card.querySelector('[data-control="letter-spacing"]')?.value;
        const color = card.querySelector('[data-control="color"]')?.value || '';
        const fontFamily = card.querySelector('[data-control="font-family"]')?.value || '';

        if (fontSize) element.style.fontSize = `var(--wp--preset--font-size--${fontSize})`;
        if (fontWeight) element.style.fontWeight = fontWeight;
        if (textTransform) element.style.textTransform = textTransform;
        if (letterSpacing) element.style.letterSpacing = letterSpacing;
        if (color) element.style.color = color;
        if (fontFamily) element.style.fontFamily = fontFamily;
    });
}

function saveTextStyles() {
    const textStyles = {};
    const cards = document.querySelectorAll('.text-style-card');
    
    cards.forEach(card => {
        const styleType = card.dataset.style;
        const htmlTag = card.querySelector('[data-control="html-tag"]').value;
        const fontSize = card.querySelector('[data-control="font-size"]').value;
        const fontWeight = card.querySelector('[data-control="font-weight"]').value;
        const textTransform = card.querySelector('[data-control="text-transform"]').value;
        const letterSpacing = card.querySelector('[data-control="letter-spacing"]').value;
        const color = card.querySelector('[data-control="color"]')?.value || '';
        const fontFamily = card.querySelector('[data-control="font-family"]')?.value || '';
        
        textStyles[styleType] = {
            htmlTag,
            fontSize,
            fontWeight,
            textTransform,
            letterSpacing,
            color,
            fontFamily
        };
    });

    // Send AJAX request
    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'villa_save_text_styles',
            nonce: villa_design_book.nonce,
            text_styles: JSON.stringify(textStyles)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Text styles saved successfully!', 'success');
        } else {
            showNotification('Error saving text styles: ' + data.data, 'error');
        }
    })
    .catch(error => {
        showNotification('Error saving text styles: ' + error.message, 'error');
    });
}

function resetTextStyles() {
    if (!confirm('Are you sure you want to reset all text styles to default values?')) {
        return;
    }

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'villa_reset_text_styles',
            nonce: villa_design_book.nonce
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Text styles reset successfully!', 'success');
            location.reload(); // Reload to show default values
        } else {
            showNotification('Error resetting text styles: ' + data.data, 'error');
        }
    })
    .catch(error => {
        showNotification('Error resetting text styles: ' + error.message, 'error');
    });
}

function saveBaseStyles() {
    const baseStyles = {};
    const controls = document.querySelectorAll('.base-style-control input');
    
    controls.forEach(control => {
        const property = control.dataset.property;
        baseStyles[property] = control.value;
    });

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'villa_save_base_styles',
            nonce: villa_design_book.nonce,
            base_styles: JSON.stringify(baseStyles)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Base styles saved successfully!', 'success');
        } else {
            showNotification('Error saving base styles: ' + data.data, 'error');
        }
    })
    .catch(error => {
        showNotification('Error saving base styles: ' + error.message, 'error');
    });
}

function resetBaseStyles() {
    if (!confirm('Are you sure you want to reset all base styles to default values?')) {
        return;
    }

    fetch(ajaxurl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            action: 'villa_reset_base_styles',
            nonce: villa_design_book.nonce
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Base styles reset successfully!', 'success');
            location.reload(); // Reload to show default values
        } else {
            showNotification('Error resetting base styles: ' + data.data, 'error');
        }
    })
    .catch(error => {
        showNotification('Error resetting base styles: ' + error.message, 'error');
    });
}
