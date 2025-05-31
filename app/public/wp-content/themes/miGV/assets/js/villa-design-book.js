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

    const ColorBook = {
        init: function() {
            this.initColorPickers();
            this.initSliders();
            this.initHexInputs();
            this.initSaveButton();
            this.initResetButton();
            this.initColorModeTabs();
        },

        initColorPickers: function() {
            $('.color-picker').on('change', this.onColorChange.bind(this));
        },

        initSliders: function() {
            $('.cmyk-controls input[type="range"]').on('input', this.onSliderChange.bind(this));
            $('.hsla-controls input[type="range"]').on('input', this.onHslaSliderChange.bind(this));
        },
        
        initHexInputs: function() {
            $('.hex-input').on('input', this.onHexInput.bind(this));
        },

        initSaveButton: function() {
            $('#save-colors').on('click', this.saveColors.bind(this));
        },

        initResetButton: function() {
            $('#reset-colors').on('click', this.resetColors.bind(this));
        },

        initColorModeTabs: function() {
            $('.color-mode-tab').on('click', this.onColorModeTabClick.bind(this));
        },

        onColorModeTabClick: function(e) {
            const $tab = $(e.target);
            const $swatch = $tab.closest('.color-swatch');
            const mode = $tab.data('mode');

            // Update tab states
            $swatch.find('.color-mode-tab').removeClass('active');
            $tab.addClass('active');

            // Update panel visibility
            $swatch.find('.color-controls-panel').removeClass('active');
            $swatch.find('.' + mode + '-controls').addClass('active');
        },

        onColorChange: function(e) {
            const $picker = $(e.target);
            const $swatch = $picker.closest('.color-swatch');
            const hex = $picker.val();

            // Update hex input
            $swatch.find('.hex-input').val(hex);
            
            // Update preview box
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

            // Convert to HSLA and update sliders
            const hsla = ColorUtils.hexToHsla(hex);
            if (hsla) {
                $swatch.find('.hue-slider').val(hsla.h);
                $swatch.find('.saturation-slider').val(hsla.s);
                $swatch.find('.lightness-slider').val(hsla.l);
                $swatch.find('.alpha-slider').val(hsla.a);
                
                // Update value displays
                $swatch.find('.hue-slider').siblings('.hsla-value').text(hsla.h + '°');
                $swatch.find('.saturation-slider').siblings('.hsla-value').text(hsla.s + '%');
                $swatch.find('.lightness-slider').siblings('.hsla-value').text(hsla.l + '%');
                $swatch.find('.alpha-slider').siblings('.hsla-value').text(hsla.a + '%');
            }

            DesignBook.updatePreview();
        },
        
        onHexInput: function(e) {
            const $input = $(e.target);
            const $swatch = $input.closest('.color-swatch');
            let hex = $input.val();
            
            // Add # if missing
            if (hex && !hex.startsWith('#')) {
                hex = '#' + hex;
                $input.val(hex);
            }
            
            // Validate hex format
            if (this.isValidHex(hex)) {
                // Update color picker
                $swatch.find('.color-picker').val(hex);
                
                // Update preview box
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
                
                // Convert to HSLA and update sliders
                const hsla = ColorUtils.hexToHsla(hex);
                if (hsla) {
                    $swatch.find('.hue-slider').val(hsla.h);
                    $swatch.find('.saturation-slider').val(hsla.s);
                    $swatch.find('.lightness-slider').val(hsla.l);
                    $swatch.find('.alpha-slider').val(hsla.a);
                    
                    // Update value displays
                    $swatch.find('.hue-slider').siblings('.hsla-value').text(hsla.h + '°');
                    $swatch.find('.saturation-slider').siblings('.hsla-value').text(hsla.s + '%');
                    $swatch.find('.lightness-slider').siblings('.hsla-value').text(hsla.l + '%');
                    $swatch.find('.alpha-slider').siblings('.hsla-value').text(hsla.a + '%');
                }
                
                DesignBook.updatePreview();
            }
        },

        onSliderChange: function(e) {
            const $slider = $(e.target);
            const $swatch = $slider.closest('.color-swatch');
            
            // Get CMYK values from sliders
            const c = parseFloat($swatch.find('.cyan-slider').val());
            const m = parseFloat($swatch.find('.magenta-slider').val());
            const y = parseFloat($swatch.find('.yellow-slider').val());
            const k = parseFloat($swatch.find('.black-slider').val());
            
            // Update value displays
            $swatch.find('.cyan-slider').siblings('.cmyk-value').text(c + '%');
            $swatch.find('.magenta-slider').siblings('.cmyk-value').text(m + '%');
            $swatch.find('.yellow-slider').siblings('.cmyk-value').text(y + '%');
            $swatch.find('.black-slider').siblings('.cmyk-value').text(k + '%');
            
            // Convert to hex
            const hex = ColorUtils.cmykToHex(c, m, y, k);
            if (hex) {
                // Update color picker, hex input, and preview
                $swatch.find('.color-picker').val(hex);
                $swatch.find('.hex-input').val(hex);
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
        
        isValidHex: function(hex) {
            return /^#[0-9A-F]{6}$/i.test(hex);
        },
        
        saveColors: function() {
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
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_save_colors',
                    nonce: villa_design_book.nonce,
                    colors: JSON.stringify(colors),
                    cmyk_data: JSON.stringify(cmykData),
                    hsla_data: JSON.stringify(hslaData)
                },
                success: function(response) {
                    if (response.success) {
                        DesignBook.showNotification('Colors saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Error: ' + response.data, 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Error saving colors', 'error');
                }
            });
        },

        resetColors: function() {
            if (confirm('Are you sure you want to reset all colors to defaults?')) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'villa_reset_colors',
                        nonce: villa_design_book.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            DesignBook.showNotification('Colors reset successfully!', 'success');
                            location.reload();
                        } else {
                            DesignBook.showNotification('Error: ' + response.data, 'error');
                        }
                    },
                    error: function() {
                        DesignBook.showNotification('Error resetting colors', 'error');
                    }
                });
            }
        }
    };

    const TextBook = {
        init() {
            this.bindEvents();
            this.initTabSwitching();
            this.updateTextPreviews();
        },

        bindEvents() {
            // Tab switching
            $(document).on('click', '.textbook-tab', this.switchTab);
            
            // Semantic style controls
            $(document).on('change', '.text-style-controls select', this.onSemanticStyleChange.bind(this));
            
            // Base style controls
            $(document).on('input', '#base-font-size', this.onBaseFontSizeChange.bind(this));
            
            // Save/Reset buttons
            $(document).on('click', '#save-text-styles', this.saveTextStyles.bind(this));
            $(document).on('click', '#reset-text-styles', this.resetTextStyles.bind(this));
            $(document).on('click', '#save-base-styles', this.saveBaseStyles.bind(this));
            $(document).on('click', '#reset-base-styles', this.resetBaseStyles.bind(this));
        },

        initTabSwitching() {
            $('.textbook-tab').on('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and content
                $('.textbook-tab').removeClass('active');
                $('.textbook-tab-content').removeClass('active');
                
                // Add active class to clicked tab
                $(this).addClass('active');
                
                // Show corresponding content
                const target = $(this).data('tab');
                $('#' + target).addClass('active');
            });
        },

        onSemanticStyleChange(e) {
            const $control = $(e.target);
            const $card = $control.closest('.text-style-card');
            const styleKey = $card.data('style');
            
            this.updateTextPreview(styleKey);
        },

        onBaseFontSizeChange(e) {
            // Update font scale preview when base font size changes
            this.updateFontScalePreview();
        },

        updateTextPreview(styleKey) {
            const $card = $(`.text-style-card[data-style="${styleKey}"]`);
            const $preview = $card.find('.preview-text');
            
            // Get current values from controls
            const fontSize = $card.find('[name$="_font_size"]').val();
            const fontWeight = $card.find('[name$="_font_weight"]').val();
            const letterSpacing = $card.find('[name$="_letter_spacing"]').val();
            const color = $card.find('[name$="_color"]').val();
            
            // Apply styles to preview
            const styles = {
                'font-size': this.getFontSizeValue(fontSize),
                'font-weight': fontWeight,
                'letter-spacing': this.getLetterSpacingValue(letterSpacing),
                'color': this.getColorValue(color)
            };
            
            $preview.css(styles);
        },

        updateTextPreviews() {
            // Update all text previews
            $('.text-style-card').each((index, card) => {
                const styleKey = $(card).data('style');
                this.updateTextPreview(styleKey);
            });
        },

        updateFontScalePreview() {
            const baseFontSize = $('#base-font-size').val() || '1rem';
            const baseNumeric = parseFloat(baseFontSize);
            const unit = baseFontSize.replace(/[0-9.]/g, '') || 'rem';
            
            const scales = {
                'Small': 0.8125,
                'Medium': 1.0,
                'Large': 1.25,
                'X-Large': 1.5,
                'XX-Large': 2.0,
                'Huge': 6.25
            };
            
            $('.scale-item').each(function() {
                const label = $(this).find('.scale-label').text();
                const scale = scales[label];
                if (scale) {
                    const calculatedSize = (baseNumeric * scale).toFixed(4);
                    $(this).find('.scale-value').text(`${calculatedSize}${unit} (${scale}× base)`);
                }
            });
        },

        getFontSizeValue(sizeKey) {
            const baseFontSize = $('#base-font-size').val() || '1rem';
            const baseNumeric = parseFloat(baseFontSize);
            const unit = baseFontSize.replace(/[0-9.]/g, '') || 'rem';
            
            const scales = {
                'small': 0.8125,
                'medium': 1.0,
                'large': 1.25,
                'x-large': 1.5,
                'xx-large': 2.0,
                'huge': 6.25
            };
            
            const scale = scales[sizeKey] || 1.0;
            return (baseNumeric * scale) + unit;
        },

        getLetterSpacingValue(spacingKey) {
            const spacings = {
                'tight': '-0.025em',
                'normal': '0',
                'wide': '0.025em',
                'wider': '0.05em',
                'widest': '0.1em'
            };
            return spacings[spacingKey] || '0';
        },

        getColorValue(colorKey) {
            // Map color keys to CSS custom properties
            const colors = {
                'primary': 'var(--wp--preset--color--primary)',
                'secondary': 'var(--wp--preset--color--secondary)',
                'base-darkest': 'var(--wp--preset--color--base-darkest)',
                'base-dark': 'var(--wp--preset--color--base-dark)',
                'base': 'var(--wp--preset--color--base)',
                'base-light': 'var(--wp--preset--color--base-light)'
            };
            return colors[colorKey] || '#000000';
        },

        saveTextStyles(e) {
            e.preventDefault();
            
            const styles = {};
            
            // Collect data from each semantic style card
            $('.text-style-card').each(function() {
                const $card = $(this);
                const styleKey = $card.data('style');
                
                styles[styleKey] = {
                    fontSize: $card.find('[name$="_font_size"]').val(),
                    fontWeight: $card.find('[name$="_font_weight"]').val(),
                    letterSpacing: $card.find('[name$="_letter_spacing"]').val(),
                    color: $card.find('[name$="_color"]').val()
                };
            });
            
            // Send AJAX request
            $.ajax({
                url: villa_design_book.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_text_styles',
                    nonce: villa_design_book.nonce,
                    styles: styles
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

        resetTextStyles(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset all text styles to default values?')) {
                return;
            }
            
            $.ajax({
                url: villa_design_book.ajax_url,
                type: 'POST',
                data: {
                    action: 'reset_text_styles',
                    nonce: villa_design_book.nonce
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Text styles reset successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        DesignBook.showNotification('Error resetting text styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error resetting text styles', 'error');
                }
            });
        },

        saveBaseStyles(e) {
            e.preventDefault();
            
            const styles = {
                'base-font-size': $('#base-font-size').val()
            };
            
            $.ajax({
                url: villa_design_book.ajax_url,
                type: 'POST',
                data: {
                    action: 'save_base_styles',
                    nonce: villa_design_book.nonce,
                    styles: styles
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Base styles saved successfully!', 'success');
                        this.updateFontScalePreview();
                        this.updateTextPreviews();
                    } else {
                        DesignBook.showNotification('Error saving base styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error saving base styles', 'error');
                }
            });
        },

        resetBaseStyles(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset base styles to default values?')) {
                return;
            }
            
            $.ajax({
                url: villa_design_book.ajax_url,
                type: 'POST',
                data: {
                    action: 'reset_base_styles',
                    nonce: villa_design_book.nonce
                },
                success: (response) => {
                    if (response.success) {
                        DesignBook.showNotification('Base styles reset successfully!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        DesignBook.showNotification('Error resetting base styles: ' + response.data, 'error');
                    }
                },
                error: () => {
                    DesignBook.showNotification('Error resetting base styles', 'error');
                }
            });
        }
    };

    const ComponentBook = {
        init: function() {
            this.bindCardEvents();
            this.initializeRangeValues();
            this.updateCardPreview();
            this.generateCardCSS();
        },
        
        bindCardEvents: function() {
            const self = this;
            
            // Bind all card control events
            $('.card-control').on('input change', function() {
                self.updateCardPreview();
                self.generateCardCSS();
            });
            
            // Range slider value display and preview update
            $('input[type="range"].card-control').on('input', function() {
                const $this = $(this);
                const value = $this.val();
                const property = $this.data('property');
                
                let displayValue = value;
                if (property === 'borderRadius') {
                    displayValue = value + 'px';
                }
                
                $this.siblings('.range-value').text(displayValue);
                
                // Update preview immediately
                self.updateCardPreview();
                self.generateCardCSS();
            });
            
            // WordPress Media Uploader
            $('#upload-card-image').on('click', function(e) {
                e.preventDefault();
                
                // Create the media frame
                const frame = wp.media({
                    title: 'Select Card Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false,
                    library: {
                        type: 'image'
                    }
                });
                
                // When an image is selected
                frame.on('select', function() {
                    const attachment = frame.state().get('selection').first().toJSON();
                    
                    // Update the hidden input and preview
                    $('#card-image').val(attachment.url).trigger('change');
                    $('#card-image-preview').attr('src', attachment.url);
                    
                    // Update the card preview
                    self.updateCardPreview();
                    self.generateCardCSS();
                });
                
                // Open the media frame
                frame.open();
            });
            
            // Remove image
            $('#remove-card-image').on('click', function(e) {
                e.preventDefault();
                
                const defaultImage = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=250&fit=crop';
                $('#card-image').val(defaultImage).trigger('change');
                $('#card-image-preview').attr('src', defaultImage);
                
                self.updateCardPreview();
                self.generateCardCSS();
            });
            
            // Save card component
            $('#save-card-component').on('click', function() {
                self.saveCardComponent();
            });
            
            // Reset card component
            $('#reset-card-component').on('click', function() {
                self.resetCardComponent();
            });
        },
        
        initializeRangeValues: function() {
            // Initialize range slider displays
            $('input[type="range"].card-control').each(function() {
                const $this = $(this);
                const value = $this.val();
                const property = $this.data('property');
                
                let displayValue = value;
                if (property === 'borderRadius') {
                    displayValue = value + 'px';
                }
                
                $this.siblings('.range-value').text(displayValue);
            });
        },
        
        updateCardPreview: function() {
            const $preview = $('#card-preview');
            const settings = this.getCardSettings();
            
            // Update content
            $preview.find('.card-image img').attr('src', settings.image);
            $preview.find('.card-pretitle').text(settings.pretitle);
            $preview.find('.card-title').text(settings.title);
            $preview.find('.card-description').text(settings.description);
            
            // Update styles
            const shadowValues = ['none', '0 2px 4px rgba(0,0,0,0.1)', '0 4px 12px rgba(0,0,0,0.1)', 
                                 '0 8px 25px rgba(0,0,0,0.15)', '0 12px 35px rgba(0,0,0,0.2)', 
                                 '0 16px 45px rgba(0,0,0,0.25)'];
            
            $preview.css({
                'border-radius': settings.borderRadius + 'px',
                'box-shadow': shadowValues[settings.shadow] || shadowValues[2]
            });
        },
        
        getCardSettings: function() {
            return {
                image: $('#card-image').val(),
                pretitle: $('#card-pretitle').val(),
                title: $('#card-title').val(),
                description: $('#card-description').val(),
                borderRadius: $('#card-border-radius').val(),
                shadow: $('#card-shadow').val()
            };
        },
        
        generateCardCSS: function() {
            const settings = this.getCardSettings();
            const shadowValues = ['none', '0 2px 4px rgba(0, 0, 0, 0.1)', '0 4px 12px rgba(0, 0, 0, 0.1)', 
                                 '0 8px 25px rgba(0, 0, 0, 0.15)', '0 12px 35px rgba(0, 0, 0, 0.2)', 
                                 '0 16px 45px rgba(0, 0, 0, 0.25)'];
            
            const css = `.villa-card {
    background: #fff;
    border-radius: ${settings.borderRadius}px;
    overflow: hidden;
    box-shadow: ${shadowValues[settings.shadow] || shadowValues[2]};
    max-width: 350px;
    transition: all 0.3s ease;
}

.villa-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.villa-card:hover .card-image img {
    transform: scale(1.05);
}

.card-content {
    padding: 20px;
}

.card-pretitle {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--wp--preset--color--primary);
    margin-bottom: 8px;
}

.card-title {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin: 0 0 12px 0;
    line-height: 1.3;
}

.card-description {
    font-size: 14px;
    color: #666;
    line-height: 1.5;
    margin: 0;
}`;
            
            $('#card-css-output').val(css);
        },
        
        saveCardComponent: function() {
            const settings = this.getCardSettings();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_save_card_component',
                    nonce: villa_design_book.nonce,
                    settings: JSON.stringify(settings)
                },
                success: function(response) {
                    if (response.success) {
                        DesignBook.showNotification('Card component saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Error saving card component: ' + response.data, 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Error saving card component', 'error');
                }
            });
        },
        
        resetCardComponent: function() {
            // Reset to default values
            const defaultImage = 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=400&h=250&fit=crop';
            $('#card-image').val(defaultImage);
            $('#card-image-preview').attr('src', defaultImage);
            $('#card-pretitle').val('Featured Property');
            $('#card-title').val('Modern Villa with Ocean View');
            $('#card-description').val('Experience luxury living in this stunning modern villa featuring panoramic ocean views, contemporary design, and premium amenities.');
            $('#card-border-radius').val(12);
            $('#card-shadow').val(2);
            
            // Update range value displays
            $('#card-border-radius').siblings('.range-value').text('12px');
            $('#card-shadow').siblings('.range-value').text('2');
            
            // Update preview and CSS
            this.updateCardPreview();
            this.generateCardCSS();
            
            DesignBook.showNotification('Card component reset to default', 'info');
        }
    };

    const ButtonBook = {
        init: function() {
            this.bindEvents();
            this.loadButtonSettings();
            this.updateButtonPreview();
        },
        
        bindEvents: function() {
            // Bind control change events
            $('#button-text').on('input', this.updateButtonPreview.bind(this));
            $('select[name="button-style"]').on('change', this.updateButtonPreview.bind(this));
            $('select[name="button-size"]').on('change', this.updateButtonPreview.bind(this));
            $('select[name="button-color"]').on('change', this.updateButtonPreview.bind(this));
            
            // Bind save and reset buttons
            $('#save-button').on('click', this.saveButtonStyles.bind(this));
            $('#reset-button').on('click', this.resetButtonStyles.bind(this));
        },
        
        updateButtonPreview: function() {
            const text = $('#button-text').val() || 'Default Button';
            const style = $('select[name="button-style"]').val();
            const size = $('select[name="button-size"]').val();
            const color = $('select[name="button-color"]').val();
            
            // Update button preview
            const $preview = $('#button-preview');
            $preview.text(text);
            
            // Remove existing classes
            $preview.removeClass('villa-button--primary villa-button--secondary villa-button--tertiary');
            $preview.removeClass('villa-button--small villa-button--medium villa-button--large');
            $preview.removeClass('villa-button--primary-color villa-button--secondary-color villa-button--neutral-color');
            
            // Add new classes
            $preview.addClass(`villa-button--${style}`);
            $preview.addClass(`villa-button--${size}`);
            $preview.addClass(`villa-button--${color}-color`);
            
            this.generateButtonCSS();
        },
        
        generateButtonCSS: function() {
            const style = $('select[name="button-style"]').val();
            const size = $('select[name="button-size"]').val();
            const color = $('select[name="button-color"]').val();
            
            // Generate CSS based on theme.json button variants
            let css = `
                .villa-button {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    text-decoration: none;
                    border: none;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    font-family: inherit;
                }
                
                .villa-button--primary {
                    background-color: var(--wp--preset--color--primary);
                    color: var(--wp--preset--color--base-white);
                    border: 2px solid var(--wp--preset--color--primary);
                    border-radius: 6px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                
                .villa-button--primary:hover {
                    background-color: var(--wp--preset--color--primary-dark);
                    border-color: var(--wp--preset--color--primary-dark);
                }
                
                .villa-button--secondary {
                    background-color: var(--wp--preset--color--secondary);
                    color: var(--wp--preset--color--base-white);
                    border: 2px solid var(--wp--preset--color--secondary);
                    border-radius: 6px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                
                .villa-button--secondary:hover {
                    background-color: var(--wp--preset--color--secondary-dark);
                    border-color: var(--wp--preset--color--secondary-dark);
                }
                
                .villa-button--tertiary {
                    background-color: transparent;
                    color: var(--wp--preset--color--primary);
                    border: 2px solid var(--wp--preset--color--primary);
                    border-radius: 6px;
                }
                
                .villa-button--tertiary:hover {
                    background-color: var(--wp--preset--color--primary);
                    color: var(--wp--preset--color--base-white);
                }
                
                .villa-button--small {
                    padding: 8px 16px;
                    font-size: var(--wp--preset--font-size--small);
                }
                
                .villa-button--medium {
                    padding: 12px 24px;
                    font-size: var(--wp--preset--font-size--medium);
                }
                
                .villa-button--large {
                    padding: 16px 32px;
                    font-size: var(--wp--preset--font-size--large);
                }
            `;
            
            // Update or create style element
            let $style = $('#button-preview-styles');
            if ($style.length === 0) {
                $style = $('<style id="button-preview-styles"></style>');
                $('head').append($style);
            }
            $style.text(css);
        },
        
        saveButtonStyles: function() {
            const settings = {
                text: $('#button-text').val(),
                style: $('select[name="button-style"]').val(),
                size: $('select[name="button-size"]').val(),
                color: $('select[name="button-color"]').val()
            };
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_save_button_styles',
                    settings: JSON.stringify(settings),
                    nonce: villa_design_book.nonce
                },
                success: function(response) {
                    if (response.success) {
                        DesignBook.showNotification('Button styles saved successfully!', 'success');
                    } else {
                        DesignBook.showNotification('Failed to save button styles: ' + response.data, 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Error saving button styles', 'error');
                }
            });
        },
        
        resetButtonStyles: function() {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_reset_button_styles',
                    nonce: villa_design_book.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Reset form values
                        $('#button-text').val('Default Button');
                        $('select[name="button-style"]').val('primary');
                        $('select[name="button-size"]').val('medium');
                        $('select[name="button-color"]').val('primary');
                        
                        // Update preview
                        ButtonBook.updateButtonPreview();
                        
                        DesignBook.showNotification('Button styles reset to defaults!', 'success');
                    } else {
                        DesignBook.showNotification('Failed to reset button styles: ' + response.data, 'error');
                    }
                },
                error: function() {
                    DesignBook.showNotification('Error resetting button styles', 'error');
                }
            });
        },
        
        loadButtonSettings: function() {
            // Load saved settings from WordPress options
            // This would typically be loaded from PHP and passed to JavaScript
            // For now, we'll use defaults
            const defaults = {
                text: 'Default Button',
                style: 'primary',
                size: 'medium',
                color: 'primary'
            };
            
            $('#button-text').val(defaults.text);
            $('select[name="button-style"]').val(defaults.style);
            $('select[name="button-size"]').val(defaults.size);
            $('select[name="button-color"]').val(defaults.color);
        }
    };

    const BaseOptions = {
        init: function() {
            this.bindEvents();
            this.initializeTabs();
        },
        
        bindEvents: function() {
            // Tab switching
            $(document).on('click', '.villa-base-options .base-tab', this.switchTab);
            
            // Input changes for live preview
            $(document).on('input', '.villa-base-options .layout-control', this.updatePreview);
            
            // Save and reset buttons
            $(document).on('click', '#save-layout-tokens', this.saveLayoutTokens);
            $(document).on('click', '#reset-layout-tokens', this.resetLayoutTokens);
        },
        
        initializeTabs: function() {
            // Show first tab by default
            $('.villa-base-options .base-tab:first').addClass('active');
            $('.villa-base-options .base-tab-content:first').addClass('active');
        },
        
        switchTab: function(e) {
            e.preventDefault();
            
            const $tab = $(this);
            const tabId = $tab.data('tab');
            
            // Update tab states
            $('.villa-base-options .base-tab').removeClass('active');
            $('.villa-base-options .base-tab-content').removeClass('active');
            
            $tab.addClass('active');
            $('#' + tabId).addClass('active');
        },
        
        updatePreview: function() {
            const $input = $(this);
            const category = $input.data('category');
            const key = $input.data('key');
            const value = $input.val();
            
            // Find the corresponding preview element
            const $preview = $input.siblings('.token-preview');
            
            // Update preview based on category
            switch (category) {
                case 'spacing':
                    $preview.css({
                        'width': value,
                        'height': value
                    });
                    break;
                    
                case 'borderRadius':
                    $preview.css('border-radius', value);
                    break;
                    
                case 'borderWidth':
                    $preview.css('border-width', value);
                    break;
                    
                case 'shadows':
                    $preview.css('box-shadow', value);
                    break;
                    
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
        },
        
        saveLayoutTokens: function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const originalText = $button.text();
            
            // Collect all layout data
            const layoutData = {
                spacing: {},
                borderRadius: {},
                borderWidth: {},
                shadows: {},
                sizes: {},
                backgrounds: {
                    colors: {},
                    overlays: {}
                }
            };
            
            $('.villa-base-options .layout-control').each(function() {
                const $input = $(this);
                const category = $input.data('category');
                const key = $input.data('key');
                const value = $input.val();
                
                if (layoutData[category]) {
                    layoutData[category][key] = value;
                }
            });
            
            // Show loading state
            $button.text('Saving...').prop('disabled', true);
            
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_save_layout_tokens',
                    nonce: villa_design_book.nonce,
                    layout_data: layoutData
                },
                success: function(response) {
                    if (response.success) {
                        $button.text('Saved!').removeClass('button-primary').addClass('button-secondary');
                        setTimeout(function() {
                            $button.text(originalText).removeClass('button-secondary').addClass('button-primary').prop('disabled', false);
                        }, 2000);
                        
                        // Show success notification
                        BaseOptions.showNotification('Layout tokens saved successfully!', 'success');
                    } else {
                        BaseOptions.showNotification('Error: ' + response.data, 'error');
                        $button.text(originalText).prop('disabled', false);
                    }
                },
                error: function() {
                    BaseOptions.showNotification('Failed to save layout tokens', 'error');
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },
        
        resetLayoutTokens: function(e) {
            e.preventDefault();
            
            if (!confirm('Are you sure you want to reset all layout tokens to defaults? This cannot be undone.')) {
                return;
            }
            
            const $button = $(this);
            const originalText = $button.text();
            
            // Show loading state
            $button.text('Resetting...').prop('disabled', true);
            
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'villa_reset_layout_tokens',
                    nonce: villa_design_book.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Update all inputs with default values
                        const defaultLayout = response.data.layout;
                        
                        Object.keys(defaultLayout).forEach(function(category) {
                            Object.keys(defaultLayout[category]).forEach(function(key) {
                                const value = defaultLayout[category][key];
                                const $input = $(`[data-category="${category}"][data-key="${key}"]`);
                                $input.val(value).trigger('input');
                            });
                        });
                        
                        BaseOptions.showNotification('Layout tokens reset to defaults!', 'success');
                    } else {
                        BaseOptions.showNotification('Error: ' + response.data, 'error');
                    }
                    
                    $button.text(originalText).prop('disabled', false);
                },
                error: function() {
                    BaseOptions.showNotification('Failed to reset layout tokens', 'error');
                    $button.text(originalText).prop('disabled', false);
                }
            });
        },
        
        showNotification: function(message, type) {
            // Create notification element
            const $notification = $('<div class="villa-notification villa-notification-' + type + '">' + message + '</div>');
            
            // Add to page
            $('.villa-base-options .wrap').prepend($notification);
            
            // Auto-remove after 3 seconds
            setTimeout(function() {
                $notification.fadeOut(function() {
                    $(this).remove();
                });
            }, 3000);
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        DesignBook.init();
        ColorBook.init();
        TextBook.init();
        ComponentBook.init();
        ButtonBook.init();
        BaseOptions.init();
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
