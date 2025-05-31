/**
 * Villa DesignBook JavaScript
 * Handles color manipulation, OKLCH conversion, and live preview updates
 */

(function($) {
    'use strict';

    // Color conversion utilities
    const ColorUtils = {
        /**
         * Convert hex to CMYK
         */
        hexToCmyk: function(hex) {
            const rgb = this.hexToRgb(hex);
            if (!rgb) return null;
            return this.rgbToCmyk(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert CMYK to hex
         */
        cmykToHex: function(c, m, y, k) {
            const rgb = this.cmykToRgb(c, m, y, k);
            if (!rgb) return null;
            return this.rgbToHex(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert RGB to CMYK
         */
        rgbToCmyk: function(r, g, b) {
            // Normalize RGB values to 0-1
            r = r / 255;
            g = g / 255;
            b = b / 255;

            // Calculate K (black)
            const k = 1 - Math.max(r, g, b);
            
            // Calculate CMY
            const c = k === 1 ? 0 : (1 - r - k) / (1 - k);
            const m = k === 1 ? 0 : (1 - g - k) / (1 - k);
            const y = k === 1 ? 0 : (1 - b - k) / (1 - k);

            return {
                c: Math.round(c * 100),
                m: Math.round(m * 100),
                y: Math.round(y * 100),
                k: Math.round(k * 100)
            };
        },

        /**
         * Convert CMYK to RGB
         */
        cmykToRgb: function(c, m, y, k) {
            // Normalize CMYK values to 0-1
            c = c / 100;
            m = m / 100;
            y = y / 100;
            k = k / 100;

            // Calculate RGB
            const r = 255 * (1 - c) * (1 - k);
            const g = 255 * (1 - m) * (1 - k);
            const b = 255 * (1 - y) * (1 - k);

            return {
                r: Math.round(Math.max(0, Math.min(255, r))),
                g: Math.round(Math.max(0, Math.min(255, g))),
                b: Math.round(Math.max(0, Math.min(255, b)))
            };
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
         * Convert hex to HSLA
         */
        hexToHsla: function(hex) {
            const rgb = this.hexToRgb(hex);
            if (!rgb) return null;
            return this.rgbToHsla(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert HSLA to hex
         */
        hslaToHex: function(h, s, l, a) {
            const rgb = this.hslaToRgb(h, s, l, a);
            if (!rgb) return null;
            return this.rgbToHex(rgb.r, rgb.g, rgb.b);
        },

        /**
         * Convert RGB to HSLA
         */
        rgbToHsla: function(r, g, b) {
            // Normalize RGB values to 0-1
            r = r / 255;
            g = g / 255;
            b = b / 255;

            // Calculate HSL
            const max = Math.max(r, g, b);
            const min = Math.min(r, g, b);
            const delta = max - min;
            let h = 0;
            let s = 0;
            let l = (max + min) / 2;

            if (delta !== 0) {
                s = l > 0.5 ? delta / (2 - max - min) : delta / (max + min);
                switch (max) {
                    case r:
                        h = (g - b) / delta + (g < b ? 6 : 0);
                        break;
                    case g:
                        h = (b - r) / delta + 2;
                        break;
                    case b:
                        h = (r - g) / delta + 4;
                        break;
                }
                h = h / 6;
            }

            // Calculate A (alpha)
            const a = 1;

            return {
                h: Math.round(h * 360),
                s: Math.round(s * 100),
                l: Math.round(l * 100),
                a: Math.round(a * 100)
            };
        },

        /**
         * Convert HSLA to RGB
         */
        hslaToRgb: function(h, s, l, a) {
            // Normalize HSLA values to 0-1
            h = h / 360;
            s = s / 100;
            l = l / 100;
            a = a / 100;

            // Calculate RGB
            let r, g, b;
            if (s === 0) {
                r = g = b = l;
            } else {
                const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                const p = 2 * l - q;
                r = this.hueToRgb(p, q, h + 1/3);
                g = this.hueToRgb(p, q, h);
                b = this.hueToRgb(p, q, h - 1/3);
            }

            return {
                r: Math.round(r * 255),
                g: Math.round(g * 255),
                b: Math.round(b * 255)
            };
        },

        /**
         * Helper function for HSLA to RGB conversion
         */
        hueToRgb: function(p, q, t) {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        },
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
                
                // Convert hex to CMYK and set slider values
                const cmyk = ColorUtils.hexToCmyk(hex);
                if (cmyk) {
                    $swatch.find('.cyan-slider').val(cmyk.c);
                    $swatch.find('.magenta-slider').val(cmyk.m);
                    $swatch.find('.yellow-slider').val(cmyk.y);
                    $swatch.find('.black-slider').val(cmyk.k);
                }
                
                // Convert hex to HSLA and set slider values
                const hsla = ColorUtils.hexToHsla(hex);
                if (hsla) {
                    $swatch.find('.hue-slider').val(hsla.h);
                    $swatch.find('.saturation-slider').val(hsla.s);
                    $swatch.find('.lightness-slider').val(hsla.l);
                    $swatch.find('.alpha-slider').val(hsla.a);
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
            
            // Convert to CMYK and update sliders
            const cmyk = ColorUtils.hexToCmyk(hex);
            if (cmyk) {
                $swatch.find('.cyan-slider').val(cmyk.c);
                $swatch.find('.magenta-slider').val(cmyk.m);
                $swatch.find('.yellow-slider').val(cmyk.y);
                $swatch.find('.black-slider').val(cmyk.k);
            }
            
            // Convert to HSLA and update sliders
            const hsla = ColorUtils.hexToHsla(hex);
            if (hsla) {
                $swatch.find('.hue-slider').val(hsla.h);
                $swatch.find('.saturation-slider').val(hsla.s);
                $swatch.find('.lightness-slider').val(hsla.l);
                $swatch.find('.alpha-slider').val(hsla.a);
            }
            
            DesignBook.updatePreview();
        },

        onSliderChange: function(e) {
            const $slider = $(e.target);
            const $swatch = $slider.closest('.color-swatch');
            
            // Get CMYK values from sliders
            const c = parseFloat($swatch.find('.cyan-slider').val());
            const m = parseFloat($swatch.find('.magenta-slider').val());
            const y = parseFloat($swatch.find('.yellow-slider').val());
            const k = parseFloat($swatch.find('.black-slider').val());
            
            // Convert to hex
            const hex = ColorUtils.cmykToHex(c, m, y, k);
            if (hex) {
                // Update color picker and preview
                $swatch.find('.color-picker').val(hex);
                $swatch.find('.color-preview-box').css('background-color', hex);
            }
            
            DesignBook.updatePreview();
        },

        onHslaSliderChange: function(e) {
            const $slider = $(e.target);
            const $swatch = $slider.closest('.color-swatch');
            
            // Get HSLA values from sliders
            const h = parseFloat($swatch.find('.hue-slider').val());
            const s = parseFloat($swatch.find('.saturation-slider').val());
            const l = parseFloat($swatch.find('.lightness-slider').val());
            const a = parseFloat($swatch.find('.alpha-slider').val());
            
            // Update value displays
            $swatch.find('.hue-slider').siblings('.hsla-value').text(h + '°');
            $swatch.find('.saturation-slider').siblings('.hsla-value').text(s + '%');
            $swatch.find('.lightness-slider').siblings('.hsla-value').text(l + '%');
            $swatch.find('.alpha-slider').siblings('.hsla-value').text(a + '%');
            
            // Convert to hex
            const hex = ColorUtils.hslaToHex(h, s, l, a);
            if (hex) {
                // Update color picker, hex input, and preview
                $swatch.find('.color-picker').val(hex);
                $swatch.find('.hex-input').val(hex);
                $swatch.find('.color-preview-box').css('background-color', hex);
                
                // Convert to CMYK and update sliders
                const cmyk = ColorUtils.hexToCmyk(hex);
                if (cmyk) {
                    $swatch.find('.cyan-slider').val(cmyk.c);
                    $swatch.find('.magenta-slider').val(cmyk.m);
                    $swatch.find('.yellow-slider').val(cmyk.y);
                    $swatch.find('.black-slider').val(cmyk.k);
                    
                    // Update value displays
                    $swatch.find('.cyan-slider').siblings('.cmyk-value').text(cmyk.c + '%');
                    $swatch.find('.magenta-slider').siblings('.cmyk-value').text(cmyk.m + '%');
                    $swatch.find('.yellow-slider').siblings('.cmyk-value').text(cmyk.y + '%');
                    $swatch.find('.black-slider').siblings('.cmyk-value').text(cmyk.k + '%');
                }
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
            const letterSpacingMap = {
                'tight': '-0.025em',
                'normal': '0',
                'wide': '0.025em',
                'wider': '0.05em',
                'widest': '0.1em'
            };
            return letterSpacingMap[spacingKey] || spacingKey;
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
            
            // Update background preview
            $('.background-swatch').each(function() {
                const $swatch = $(this);
                const slug = $swatch.data('slug');
                const background = $swatch.find('.background-picker').val();
                
                // Update CSS custom property
                document.documentElement.style.setProperty(`--wp--preset--background--${slug}`, background);
            });
            
            // Update background preview handling for colors, gradients, overlays, and patterns
            $('.background-preview').each(function() {
                const $preview = $(this);
                const category = $preview.data('category');
                const key = $preview.data('key');
                const value = $preview.val();
                
                switch (category) {
                    case 'sizes':
                        $preview.css({
                            'width': value,
                            'height': value
                        });
                        break;
                        
                    case 'backgrounds':
                        if ($preview.hasClass('color-preview')) {
                            $preview.css('background-color', value);
                        } else if ($preview.hasClass('overlay-preview')) {
                            $preview.find('::after').css('background-color', value);
                        }
                        break;
                }
            });
        },

        saveColors: function(e) {
            e.preventDefault();
            
            // Collect all color data
            const colors = [];
            const cmykData = {};
            const hslaData = {};

            $('.color-swatch').each(function() {
                const $swatch = $(this);
                const slug = $swatch.data('slug');
                const name = $swatch.find('label').first().text();
                const hex = $swatch.find('.hex-input').val();
                
                // Get CMYK values
                const c = parseFloat($swatch.find('.cyan-slider').val());
                const m = parseFloat($swatch.find('.magenta-slider').val());
                const y = parseFloat($swatch.find('.yellow-slider').val());
                const k = parseFloat($swatch.find('.black-slider').val());

                // Get HSLA values
                const h = parseFloat($swatch.find('.hue-slider').val());
                const s = parseFloat($swatch.find('.saturation-slider').val());
                const l = parseFloat($swatch.find('.lightness-slider').val());
                const a = parseFloat($swatch.find('.alpha-slider').val());

                colors.push({
                    slug: slug,
                    name: name,
                    color: hex
                });
                
                cmykData[slug] = { c: c, m: m, y: y, k: k };
                hslaData[slug] = { h: h, s: s, l: l, a: a };
            });

            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_save_design_tokens',
                    nonce: villaDesignBook.nonce,
                    tokens: {
                        colors: colors,
                        cmyk_data: JSON.stringify(cmykData),
                        hsla_data: JSON.stringify(hslaData)
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

    DesignBook.TextBook = {
        /**
         * Initialize Typography Book
         */
        init: function() {
            this.bindEvents();
            this.updatePreviews();
            this.loadTokens();
        },
        
        /**
         * Bind event handlers
         */
        bindEvents: function() {
            // Tab switching
            $(document).on('click', '.textbook-tab', this.switchTab.bind(this));
            
            // Text style controls
            $(document).on('change', '.text-style-controls select', this.onStyleChange.bind(this));
            
            // Base typography controls
            $(document).on('input', '#base-font-size', this.onBaseFontSizeChange.bind(this));
            $(document).on('input', '#base-line-height', this.onBaseLineHeightChange.bind(this));
            
            // Save/Reset buttons
            $(document).on('click', '#save-text-styles', this.saveTextStyles.bind(this));
            $(document).on('click', '#reset-text-styles', this.resetTextStyles.bind(this));
            $(document).on('click', '#save-base-styles', this.saveBaseStyles.bind(this));
            $(document).on('click', '#reset-base-styles', this.resetBaseStyles.bind(this));
        },
        
        /**
         * Switch between tabs
         */
        switchTab: function(e) {
            const $tab = $(e.currentTarget);
            const targetTab = $tab.data('tab');
            
            // Update tab states
            $('.textbook-tab').removeClass('active');
            $tab.addClass('active');
            
            // Show/hide content
            $('.textbook-content').hide();
            $(`#${targetTab}-tab`).show();
            
            // Load specific tab content
            if (targetTab === 'tokens') {
                this.loadTokens();
            }
        },
        
        /**
         * Handle style control changes
         */
        onStyleChange: function(e) {
            const $control = $(e.currentTarget);
            const $card = $control.closest('.text-style-card');
            const styleType = $card.data('style');
            
            this.updatePreview(styleType);
        },
        
        /**
         * Handle base font size changes
         */
        onBaseFontSizeChange: function(e) {
            const value = $(e.currentTarget).val();
            $(e.currentTarget).siblings('.value-display').text(value + 'px');
            
            // Update CSS custom property
            document.documentElement.style.setProperty('--base-font-size', value + 'px');
        },
        
        /**
         * Handle base line height changes
         */
        onBaseLineHeightChange: function(e) {
            const value = $(e.currentTarget).val();
            $(e.currentTarget).siblings('.value-display').text(value);
            
            // Update CSS custom property
            document.documentElement.style.setProperty('--base-line-height', value);
        },
        
        /**
         * Update preview for specific style type
         */
        updatePreview: function(styleType) {
            const $card = $(`.text-style-card[data-style="${styleType}"]`);
            const $preview = $card.find('.preview-text');
            
            // Get current values
            const fontSize = $card.find(`[name$="_font_size"]`).val();
            const fontWeight = $card.find(`[name$="_font_weight"]`).val();
            const letterSpacing = $card.find(`[name$="_letter_spacing"]`).val();
            const textTransform = $card.find(`[name$="_text_transform"]`).val();
            
            // Apply styles to preview
            $preview.css({
                'font-size': `var(--font-size-${fontSize})`,
                'font-weight': `var(--font-weight-${fontWeight})`,
                'letter-spacing': `var(--letter-spacing-${letterSpacing})`,
                'text-transform': textTransform
            });
        },
        
        /**
         * Update all previews
         */
        updatePreviews: function() {
            $('.text-style-card').each((index, card) => {
                const styleType = $(card).data('style');
                this.updatePreview(styleType);
            });
        },
        
        /**
         * Save text styles
         */
        saveTextStyles: function(e) {
            e.preventDefault();
            
            const styles = {};
            
            // Collect data from each style card
            $('.text-style-card').each(function() {
                const $card = $(this);
                const styleKey = $card.data('style');
                
                styles[styleKey] = {
                    fontSize: $card.find(`[name$="_font_size"]`).val(),
                    fontWeight: $card.find(`[name$="_font_weight"]`).val(),
                    letterSpacing: $card.find(`[name$="_letter_spacing"]`).val(),
                    textTransform: $card.find(`[name$="_text_transform"]`).val()
                };
            });
            
            // Send AJAX request
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_save_text_styles',
                    nonce: villaDesignBook.nonce,
                    text_styles: JSON.stringify(styles)
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Text styles saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Error saving text styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error saving text styles', 'error');
                }
            });
        },
        
        /**
         * Reset text styles to defaults
         */
        resetTextStyles: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset all text styles to defaults?')) {
                return;
            }
            
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'villa_reset_text_styles',
                    nonce: villaDesignBook.nonce
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Text styles reset successfully!', 'success');
                        location.reload(); // Reload to show default values
                    } else {
                        DesignBook.showNotification('Error resetting text styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error resetting text styles', 'error');
                }
            });
        },
        
        /**
         * Save base typography styles
         */
        saveBaseStyles: function(e) {
            e.preventDefault();
            
            const baseStyles = {
                baseFontSize: $('#base-font-size').val() + 'px',
                baseLineHeight: $('#base-line-height').val()
            };
            
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_base_styles',
                    nonce: villaDesignBook.nonce,
                    base_styles: baseStyles
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Base styles saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Error saving base styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error saving base styles', 'error');
                }
            });
        },
        
        /**
         * Reset base typography styles
         */
        resetBaseStyles: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset base typography to defaults?')) {
                return;
            }
            
            $.ajax({
                url: villaDesignBook.ajax_url,
                type: 'POST',
                data: {
                    action: 'reset_base_styles',
                    nonce: villaDesignBook.nonce
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Base styles reset successfully!', 'success');
                        
                        // Reset UI controls
                        $('#base-font-size').val(16).siblings('.value-display').text('16px');
                        $('#base-line-height').val(1.5).siblings('.value-display').text('1.5');
                        
                        // Reset CSS properties
                        document.documentElement.style.setProperty('--base-font-size', '16px');
                        document.documentElement.style.setProperty('--base-line-height', '1.5');
                    } else {
                        DesignBook.showNotification('Error resetting base styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error resetting base styles', 'error');
                }
            });
        },
        
        /**
         * Load and display design tokens
         */
        loadTokens: function() {
            // Font Size Tokens
            const fontSizeTokens = [
                { name: 'Small', value: 'var(--font-size-small)' },
                { name: 'Medium', value: 'var(--font-size-medium)' },
                { name: 'Large', value: 'var(--font-size-large)' },
                { name: 'X-Large', value: 'var(--font-size-x-large)' },
                { name: 'XX-Large', value: 'var(--font-size-xx-large)' },
                { name: 'Huge', value: 'var(--font-size-huge)' }
            ];
            
            // Font Weight Tokens
            const fontWeightTokens = [
                { name: 'Regular', value: 'var(--font-weight-regular)' },
                { name: 'Medium', value: 'var(--font-weight-medium)' },
                { name: 'Semi Bold', value: 'var(--font-weight-semiBold)' },
                { name: 'Bold', value: 'var(--font-weight-bold)' },
                { name: 'Extra Bold', value: 'var(--font-weight-extraBold)' }
            ];
            
            // Letter Spacing Tokens
            const letterSpacingTokens = [
                { name: 'Tight', value: 'var(--letter-spacing-tight)' },
                { name: 'Normal', value: 'var(--letter-spacing-normal)' },
                { name: 'Wide', value: 'var(--letter-spacing-wide)' },
                { name: 'Wider', value: 'var(--letter-spacing-wider)' }
            ];
            
            // Render tokens
            this.renderTokenList('#font-size-tokens', fontSizeTokens);
            this.renderTokenList('#font-weight-tokens', fontWeightTokens);
            this.renderTokenList('#letter-spacing-tokens', letterSpacingTokens);
        },
        
        /**
         * Render token list
         */
        renderTokenList: function(selector, tokens) {
            const $container = $(selector);
            $container.empty();
            
            tokens.forEach(token => {
                const $tokenItem = $(`
                    <div class="token-item">
                        <div class="token-name">${token.name}</div>
                        <div class="token-value">${token.value}</div>
                    </div>
                `);
                
                $container.append($tokenItem);
            });
        }
    };

    // ColorBook module
    const ColorBook = {
        // ...
    };

    // TextBook module
    const TextBook = {
        // ...
    };

    // ComponentBook module
    const ComponentBook = {
        // ...
    };

    // ButtonBook module
    const ButtonBook = {
        // ...
    };

    // BaseOptions module
    const BaseOptions = {
        // ...
    };

    // CardBook module
    const CardBook = {
        // ...
    };

    // Initialize when document is ready
    $(document).ready(function() {
        DesignBook.init();
        ColorBook.init();
        TextBook.init();
        ComponentBook.init();
        ButtonBook.init();
        BaseOptions.init();
        CardBook.init();
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
