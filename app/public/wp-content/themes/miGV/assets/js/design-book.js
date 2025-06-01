/**
 * VILLA DESIGN BOOK - FRESH START
 * Typography System Interface
 * Uses theme.json tokens for consistency
 */

class VillaTypographyBook {
    constructor() {
        this.init();
        this.bindEvents();
        this.loadCurrentSettings();
    }

    init() {
        // Initialize tab system
        this.initTabs();
        
        // Initialize typography scale
        this.initTypographyScale();
        
        // Initialize text styles
        this.initTextStyles();
        
        // Initialize tokens display
        this.initTokensDisplay();
        
        // Initialize color book
        this.initColorBook();
    }

    bindEvents() {
        // Tab navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => this.switchTab(e));
        });

        // Tab switching
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => {
                this.switchTab(e.target.dataset.tab);
            });
        });

        // Font family change events
        const primaryFontSelect = document.getElementById('primary-font');
        const displayFontSelect = document.getElementById('display-font');
        const monoFontSelect = document.getElementById('mono-font');

        if (primaryFontSelect) {
            primaryFontSelect.addEventListener('change', (e) => {
                this.updateFontFamilyPreview('primary', e.target.value);
            });
        }

        if (displayFontSelect) {
            displayFontSelect.addEventListener('change', (e) => {
                this.updateFontFamilyPreview('display', e.target.value);
            });
        }

        if (monoFontSelect) {
            monoFontSelect.addEventListener('change', (e) => {
                this.updateFontFamilyPreview('mono', e.target.value);
            });
        }

        // Scale controls
        const baseFontSize = document.getElementById('base-font-size');
        const baseFontSizeValue = document.getElementById('base-font-size-value');
        const scaleRatio = document.getElementById('scale-ratio');
        const primaryFont = document.getElementById('primary-font');

        if (baseFontSize) {
            baseFontSize.addEventListener('input', (e) => this.updateScale(e));
        }
        if (baseFontSizeValue) {
            baseFontSizeValue.addEventListener('input', (e) => this.updateScale(e));
        }
        if (scaleRatio) {
            scaleRatio.addEventListener('change', (e) => this.updateScale(e));
        }
        if (primaryFont) {
            primaryFont.addEventListener('change', (e) => this.updatePrimaryFont(e));
        }

        // Font size change events (existing)
        document.querySelectorAll('.size-control input[data-size]').forEach(input => {
            input.addEventListener('input', (e) => {
                this.updateFontSizePreview(e.target.dataset.size, e.target.value);
            });
        });

        // Typography primitive controls (font sizes, weights, etc.)
        document.querySelectorAll('.size-control input').forEach(input => {
            input.addEventListener('input', (e) => this.updateFontSizePreview(e));
        });
        
        document.querySelectorAll('.weight-control select').forEach(select => {
            select.addEventListener('change', (e) => this.updateFontWeightPreview(e));
        });
        
        document.querySelectorAll('.line-height-control input').forEach(input => {
            input.addEventListener('input', (e) => this.updateLineHeightPreview(e));
        });
        
        document.querySelectorAll('.letter-spacing-control input').forEach(input => {
            input.addEventListener('input', (e) => this.updateLetterSpacingPreview(e));
        });

        // Text style controls
        document.querySelectorAll('.font-size-control').forEach(control => {
            control.addEventListener('change', (e) => this.updateTextStyle(e));
        });
        
        document.querySelectorAll('.font-weight-control').forEach(control => {
            control.addEventListener('change', (e) => this.updateTextStyle(e));
        });
        
        document.querySelectorAll('.line-height-control').forEach(control => {
            control.addEventListener('change', (e) => this.updateTextStyle(e));
        });
        
        document.querySelectorAll('.letter-spacing-control').forEach(control => {
            control.addEventListener('change', (e) => this.updateTextStyle(e));
        });

        // Save and Reset buttons
        this.saveButton = document.getElementById('save-typography');
        this.resetButton = document.getElementById('reset-typography');
        
        if (this.saveButton) {
            this.saveButton.addEventListener('click', () => this.saveTypography());
        }
        
        if (this.resetButton) {
            this.resetButton.addEventListener('click', () => this.resetToDefaults());
        }
    }

    initTabs() {
        // Set first tab as active by default
        const firstTab = document.querySelector('.tab-button');
        const firstContent = document.querySelector('.tab-content');
        
        if (firstTab && firstContent) {
            firstTab.classList.add('active');
            firstContent.classList.add('active');
        }
    }

    switchTab(event) {
        const targetTab = event.target.dataset.tab;
        
        // Remove active class from all tabs and content
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.classList.remove('active');
        });
        
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Add active class to clicked tab and corresponding content
        event.target.classList.add('active');
        const targetContent = document.getElementById(`${targetTab}-tab`);
        if (targetContent) {
            targetContent.classList.add('active');
            targetContent.classList.add('fade-in');
        }
    }

    switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active');
        });
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');

        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.getElementById(`${tabName}-tab`).classList.add('active');

        // Update preview content
        document.querySelectorAll('.preview-content').forEach(preview => {
            preview.classList.remove('active');
        });
        const previewElement = document.querySelector(`.${tabName}-preview`);
        if (previewElement) {
            previewElement.classList.add('active');
        }
    }

    initTypographyScale() {
        this.currentScale = {
            baseSize: 16,
            ratio: 1.125,
            font: 'inter'
        };
        
        this.updateScalePreview();
    }

    updateScale(event) {
        const input = event.target;
        const value = parseFloat(input.value);
        
        if (input.id === 'base-font-size' || input.id === 'base-font-size-value') {
            this.currentScale.baseSize = value;
            // Update the corresponding input if they're different
            const baseFontSize = document.getElementById('base-font-size');
            const baseFontSizeValue = document.getElementById('base-font-size-value');
            if (baseFontSize && baseFontSize !== input) {
                baseFontSize.value = value;
            }
            if (baseFontSizeValue && baseFontSizeValue !== input) {
                baseFontSizeValue.value = value;
            }
        } else if (input.id === 'scale-ratio') {
            this.currentScale.ratio = value;
        }
        
        // Recalculate all font sizes based on new scale
        this.calculateFontSizes();
        this.updateLivePreview();
    }

    calculateFontSizes() {
        const base = this.currentScale.baseSize / 16; // Convert px to rem (16px = 1rem)
        const ratio = this.currentScale.ratio;
        
        // Calculate sizes based on scale in rem
        const sizes = {
            xs: Math.round((base / (ratio * ratio)) * 1000) / 1000, // Round to 3 decimal places
            sm: Math.round((base / ratio) * 1000) / 1000,
            base: Math.round(base * 1000) / 1000,
            lg: Math.round((base * ratio) * 1000) / 1000,
            xl: Math.round((base * ratio * ratio) * 1000) / 1000,
            '2xl': Math.round((base * Math.pow(ratio, 2.5)) * 1000) / 1000,
            '3xl': Math.round((base * Math.pow(ratio, 3)) * 1000) / 1000,
            '4xl': Math.round((base * Math.pow(ratio, 3.5)) * 1000) / 1000,
            '5xl': Math.round((base * Math.pow(ratio, 4)) * 1000) / 1000,
            '6xl': Math.round((base * Math.pow(ratio, 4.5)) * 1000) / 1000
        };
        
        // Update CSS custom properties with rem units
        Object.entries(sizes).forEach(([size, value]) => {
            const cssProperty = `--db-text-${size}`;
            document.documentElement.style.setProperty(cssProperty, `${value}rem`);
        });
        
        // Update input values in the font sizes tab
        Object.entries(sizes).forEach(([size, value]) => {
            const input = document.querySelector(`.size-control input[data-size="${size}"]`);
            if (input) {
                input.value = value;
            }
        });
    }

    updateBaseFontSize(event) {
        const value = parseInt(event.target.value);
        this.currentScale.baseSize = value;
        
        // Sync with number input
        const numberInput = document.getElementById('base-font-size-value');
        if (numberInput) {
            numberInput.value = value;
        }
        
        this.updateScalePreview();
    }

    updateBaseFontSizeFromInput(event) {
        const value = parseInt(event.target.value);
        this.currentScale.baseSize = value;
        
        // Sync with range input
        const rangeInput = document.getElementById('base-font-size');
        if (rangeInput) {
            rangeInput.value = value;
        }
        
        this.updateScalePreview();
    }

    updateScaleRatio(event) {
        this.currentScale.ratio = parseFloat(event.target.value);
        this.updateScalePreview();
    }

    updatePrimaryFont(event) {
        this.currentScale.font = event.target.value;
        this.updateScalePreview();
    }

    updateScalePreview() {
        const { baseSize, ratio, font } = this.currentScale;
        
        // Calculate scale sizes
        const sizes = {
            small: baseSize * 0.8125,
            medium: baseSize,
            large: baseSize * ratio,
            'x-large': baseSize * Math.pow(ratio, 2),
            'xx-large': baseSize * Math.pow(ratio, 3),
            huge: baseSize * Math.pow(ratio, 6)
        };

        // Update scale items
        document.querySelectorAll('.scale-item').forEach(item => {
            const sizeKey = item.dataset.size;
            const scaleText = item.querySelector('.scale-text');
            const scaleLabel = item.querySelector('.scale-label');
            
            if (scaleText && sizes[sizeKey]) {
                const sizeInRem = (sizes[sizeKey] / 16).toFixed(3);
                scaleText.style.fontSize = `${sizeInRem}rem`;
                scaleText.style.fontFamily = `var(--wp--preset--font-family--${font})`;
                
                // Update label with calculated size
                if (scaleLabel) {
                    const displayName = sizeKey.charAt(0).toUpperCase() + sizeKey.slice(1).replace('-', '-');
                    scaleLabel.textContent = `${displayName} (${sizeInRem}rem)`;
                }
            }
        });
    }

    initTextStyles() {
        this.textStyles = {
            h1: { fontSize: 'xx-large', fontWeight: 'bold', lineHeight: 'tight' },
            h2: { fontSize: 'x-large', fontWeight: 'semiBold', lineHeight: 'normal' },
            h3: { fontSize: 'large', fontWeight: 'semiBold', lineHeight: 'normal' },
            body: { fontSize: 'medium', fontWeight: 'regular', lineHeight: 'relaxed' },
            caption: { fontSize: 'small', fontWeight: 'regular', lineHeight: 'normal' },
            button: { fontSize: 'medium', fontWeight: 'medium', letterSpacing: 'normal' }
        };
        
        this.updateTextStylePreviews();
    }

    updateTextStyle(event) {
        const control = event.target;
        const styleType = control.dataset.style;
        const controlType = control.className.split('-')[0]; // font, line, letter
        const value = control.value;
        
        if (!this.textStyles[styleType]) {
            this.textStyles[styleType] = {};
        }
        
        // Map control types to style properties
        const propertyMap = {
            'font-size': 'fontSize',
            'font-weight': 'fontWeight',
            'line-height': 'lineHeight',
            'letter-spacing': 'letterSpacing'
        };
        
        const property = propertyMap[control.className.replace('-control', '')];
        if (property) {
            this.textStyles[styleType][property] = value;
            this.updateTextStylePreview(styleType);
        }
    }

    updateTextStylePreview(styleType) {
        const preview = document.getElementById(`${styleType}-preview`);
        if (!preview) return;
        
        const style = this.textStyles[styleType];
        
        // Apply font size
        if (style.fontSize) {
            preview.style.fontSize = `var(--wp--preset--font-size--${style.fontSize})`;
        }
        
        // Apply font weight
        if (style.fontWeight) {
            const weightMap = {
                regular: '400',
                medium: '500',
                semiBold: '600',
                bold: '700',
                extraBold: '800'
            };
            preview.style.fontWeight = weightMap[style.fontWeight] || '400';
        }
        
        // Apply line height
        if (style.lineHeight) {
            const lineHeightMap = {
                tight: '1.1',
                normal: '1.2',
                relaxed: '1.4',
                loose: '1.6'
            };
            preview.style.lineHeight = lineHeightMap[style.lineHeight] || '1.2';
        }
        
        // Apply letter spacing
        if (style.letterSpacing) {
            const letterSpacingMap = {
                tight: '-0.025em',
                normal: '0',
                wide: '0.025em',
                wider: '0.05em'
            };
            preview.style.letterSpacing = letterSpacingMap[style.letterSpacing] || '0';
        }
    }

    updateTextStylePreviews() {
        Object.keys(this.textStyles).forEach(styleType => {
            this.updateTextStylePreview(styleType);
        });
    }

    initTokensDisplay() {
        this.generateTokensDisplay();
    }

    generateTokensDisplay() {
        // Font Size Tokens
        const fontSizeTokens = document.getElementById('font-size-tokens');
        if (fontSizeTokens) {
            const fontSizes = [
                { name: '--wp--preset--font-size--small', value: '0.8125rem' },
                { name: '--wp--preset--font-size--medium', value: '1rem' },
                { name: '--wp--preset--font-size--large', value: '1.25rem' },
                { name: '--wp--preset--font-size--x-large', value: '1.5rem' },
                { name: '--wp--preset--font-size--xx-large', value: '2rem' },
                { name: '--wp--preset--font-size--huge', value: '6.25rem' }
            ];
            
            fontSizeTokens.innerHTML = fontSizes.map(token => 
                `<div class="token-item">
                    <span class="token-name">${token.name}</span>
                    <span class="token-value">${token.value}</span>
                </div>`
            ).join('');
        }

        // Font Weight Tokens
        const fontWeightTokens = document.getElementById('font-weight-tokens');
        if (fontWeightTokens) {
            const fontWeights = [
                { name: 'regular', value: '400' },
                { name: 'medium', value: '500' },
                { name: 'semiBold', value: '600' },
                { name: 'bold', value: '700' },
                { name: 'extraBold', value: '800' }
            ];
            
            fontWeightTokens.innerHTML = fontWeights.map(token => 
                `<div class="token-item">
                    <span class="token-name">--font-weight-${token.name}</span>
                    <span class="token-value">${token.value}</span>
                </div>`
            ).join('');
        }

        // Line Height Tokens
        const lineHeightTokens = document.getElementById('line-height-tokens');
        if (lineHeightTokens) {
            const lineHeights = [
                { name: 'tight', value: '1.1' },
                { name: 'normal', value: '1.2' },
                { name: 'relaxed', value: '1.4' },
                { name: 'loose', value: '1.6' }
            ];
            
            lineHeightTokens.innerHTML = lineHeights.map(token => 
                `<div class="token-item">
                    <span class="token-name">--line-height-${token.name}</span>
                    <span class="token-value">${token.value}</span>
                </div>`
            ).join('');
        }

        // Letter Spacing Tokens
        const letterSpacingTokens = document.getElementById('letter-spacing-tokens');
        if (letterSpacingTokens) {
            const letterSpacings = [
                { name: 'tight', value: '-0.025em' },
                { name: 'normal', value: '0' },
                { name: 'wide', value: '0.025em' },
                { name: 'wider', value: '0.05em' }
            ];
            
            letterSpacingTokens.innerHTML = letterSpacings.map(token => 
                `<div class="token-item">
                    <span class="token-name">--letter-spacing-${token.name}</span>
                    <span class="token-value">${token.value}</span>
                </div>`
            ).join('');
        }
    }

    loadCurrentSettings() {
        // Load current typography settings from theme.json or localStorage
        const savedSettings = localStorage.getItem('villa-typography-settings');
        if (savedSettings) {
            try {
                const settings = JSON.parse(savedSettings);
                this.applySettings(settings);
            } catch (e) {
                console.warn('Failed to load saved typography settings:', e);
            }
        }
    }

    applySettings(settings) {
        if (settings.scale) {
            this.currentScale = { ...this.currentScale, ...settings.scale };
            this.updateScaleControls();
            this.updateScalePreview();
        }
        
        if (settings.textStyles) {
            this.textStyles = { ...this.textStyles, ...settings.textStyles };
            this.updateTextStyleControls();
            this.updateTextStylePreviews();
        }
    }

    updateScaleControls() {
        const baseFontSize = document.getElementById('base-font-size');
        const baseFontSizeValue = document.getElementById('base-font-size-value');
        const scaleRatio = document.getElementById('scale-ratio');
        const primaryFont = document.getElementById('primary-font');
        
        if (baseFontSize) baseFontSize.value = this.currentScale.baseSize;
        if (baseFontSizeValue) baseFontSizeValue.value = this.currentScale.baseSize;
        if (scaleRatio) scaleRatio.value = this.currentScale.ratio;
        if (primaryFont) primaryFont.value = this.currentScale.font;
    }

    updateTextStyleControls() {
        Object.keys(this.textStyles).forEach(styleType => {
            const style = this.textStyles[styleType];
            
            // Update font size control
            const fontSizeControl = document.querySelector(`.font-size-control[data-style="${styleType}"]`);
            if (fontSizeControl && style.fontSize) {
                fontSizeControl.value = style.fontSize;
            }
            
            // Update font weight control
            const fontWeightControl = document.querySelector(`.font-weight-control[data-style="${styleType}"]`);
            if (fontWeightControl && style.fontWeight) {
                fontWeightControl.value = style.fontWeight;
            }
            
            // Update line height control
            const lineHeightControl = document.querySelector(`.line-height-control[data-style="${styleType}"]`);
            if (lineHeightControl && style.lineHeight) {
                lineHeightControl.value = style.lineHeight;
            }
            
            // Update letter spacing control
            const letterSpacingControl = document.querySelector(`.letter-spacing-control[data-style="${styleType}"]`);
            if (letterSpacingControl && style.letterSpacing) {
                letterSpacingControl.value = style.letterSpacing;
            }
        });
    }

    saveTypography() {
        const settings = {
            scale: this.currentScale,
            textStyles: this.textStyles,
            timestamp: new Date().toISOString()
        };
        
        // Save to localStorage
        localStorage.setItem('villa-typography-settings', JSON.stringify(settings));
        
        // Send to WordPress backend via AJAX
        this.saveToWordPress(settings);
        
        // Reset save button state
        this.resetSaveButton();
        
        // Show success message
        this.showNotification('Typography settings saved successfully!', 'success');
    }

    resetSaveButton() {
        const saveButton = document.getElementById('save-typography');
        if (saveButton) {
            saveButton.classList.remove('has-changes');
            saveButton.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17,21 17,13 7,13 7,21"/>
                    <polyline points="7,3 7,8 15,8"/>
                </svg>
                Save Changes
            `;
        }
    }

    saveToWordPress(settings) {
        // Prepare data for WordPress AJAX
        const formData = new FormData();
        formData.append('action', 'villa_save_typography_settings');
        formData.append('nonce', window.migv_ajax?.nonce || '');
        formData.append('typography_settings', JSON.stringify(settings));
        
        fetch(window.migv_ajax?.ajax_url || '/wp-admin/admin-ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('✅ Typography settings saved to WordPress');
            } else {
                console.error('❌ Failed to save typography settings:', data.data);
                this.showNotification('Failed to save settings to server', 'error');
            }
        })
        .catch(error => {
            console.error('❌ AJAX error:', error);
            this.showNotification('Network error while saving', 'error');
        });
    }

    resetTypography() {
        if (confirm('Are you sure you want to reset all typography settings to defaults?')) {
            // Reset to default values
            this.currentScale = {
                baseSize: 16,
                ratio: 1.125,
                font: 'inter'
            };
            
            this.textStyles = {
                h1: { fontSize: 'xx-large', fontWeight: 'bold', lineHeight: 'tight' },
                h2: { fontSize: 'x-large', fontWeight: 'semiBold', lineHeight: 'normal' },
                h3: { fontSize: 'large', fontWeight: 'semiBold', lineHeight: 'normal' },
                body: { fontSize: 'medium', fontWeight: 'regular', lineHeight: 'relaxed' },
                caption: { fontSize: 'small', fontWeight: 'regular', lineHeight: 'normal' },
                button: { fontSize: 'medium', fontWeight: 'medium', letterSpacing: 'normal' }
            };
            
            // Update UI
            this.updateScaleControls();
            this.updateScalePreview();
            this.updateTextStyleControls();
            this.updateTextStylePreviews();
            
            // Clear localStorage
            localStorage.removeItem('villa-typography-settings');
            
            this.showNotification('Typography settings reset to defaults', 'success');
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '12px 20px',
            backgroundColor: type === 'success' ? 'var(--db-primary)' : type === 'error' ? '#dc3545' : '#6c757d',
            color: 'white',
            borderRadius: 'var(--db-radius-md)',
            boxShadow: 'var(--db-shadow-lg)',
            zIndex: '9999',
            fontSize: 'var(--db-text-sm)',
            fontWeight: '500',
            opacity: '0',
            transform: 'translateY(-10px)',
            transition: 'all 0.3s ease-in-out'
        });
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        requestAnimationFrame(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        });
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    updateFontSizePreview(event) {
        const input = event.target;
        const label = input.parentElement.querySelector('label').textContent.toLowerCase();
        const value = input.value;
        
        // Map labels to CSS custom property names
        const sizeMap = {
            'xs': '--db-text-xs',
            'sm': '--db-text-sm', 
            'base': '--db-text-base',
            'lg': '--db-text-lg',
            'xl': '--db-text-xl',
            '2xl': '--db-text-2xl',
            '3xl': '--db-text-3xl',
            '4xl': '--db-text-4xl',
            '5xl': '--db-text-5xl',
            '6xl': '--db-text-6xl'
        };
        
        const cssProperty = sizeMap[label];
        if (cssProperty) {
            // Update CSS custom property on document root
            document.documentElement.style.setProperty(cssProperty, `${value}rem`);
            
            // Update any preview elements that use this size
            this.updateLivePreview();
        }
    }

    updateFontWeightPreview(event) {
        const select = event.target;
        const weightType = select.dataset.weight; // light, regular, medium, semibold, bold, extrabold, black
        const weightValue = select.value;
        
        // Update the corresponding CSS custom property
        const cssProperty = `--db-weight-${weightType}`;
        document.documentElement.style.setProperty(cssProperty, weightValue);
        
        // Find and update the specific preview element for this weight type
        const weightSections = document.querySelectorAll('.weight-section');
        
        weightSections.forEach(section => {
            const label = section.querySelector('.weight-label');
            if (label) {
                const labelText = label.textContent.toLowerCase().trim();
                let expectedType = '';
                
                // Map label text to weight type
                switch(labelText) {
                    case 'light': expectedType = 'light'; break;
                    case 'regular': expectedType = 'regular'; break;
                    case 'medium': expectedType = 'medium'; break;
                    case 'semibold': expectedType = 'semibold'; break;
                    case 'bold': expectedType = 'bold'; break;
                    case 'extra bold': expectedType = 'extrabold'; break;
                    case 'black': expectedType = 'black'; break;
                }
                
                // If this section matches our weight type, update it
                if (expectedType === weightType) {
                    const weightDisplay = section.querySelector('.weight-display');
                    if (weightDisplay) {
                        // Update both the CSS variable and direct style for immediate feedback
                        weightDisplay.style.fontWeight = weightValue;
                    }
                }
            }
        });
        
        // Update live preview
        this.updateLivePreview();
    }

    updateLineHeightPreview(event) {
        const input = event.target;
        const value = input.value;
        // Update line height preview logic here
        this.updateLivePreview();
    }

    updateLetterSpacingPreview(event) {
        const input = event.target;
        const value = input.value;
        // Update letter spacing preview logic here
        this.updateLivePreview();
    }

    updateLivePreview() {
        // Force a repaint to show the updated CSS custom properties
        const previewElements = document.querySelectorAll('#preview-tab .element-preview *');
        previewElements.forEach(element => {
            // Trigger a style recalculation
            element.style.display = 'none';
            element.offsetHeight; // Trigger reflow
            element.style.display = '';
        });
    }

    updateFontFamilyPreview(type, fontName) {
        // Update the main CSS custom property directly (no more isolation)
        document.documentElement.style.setProperty(`--db-font-${type}`, fontName);

        // Update preview card display text and font family
        const displayElement = document.getElementById(`${type}-font-display`);
        
        if (displayElement) {
            displayElement.style.fontFamily = fontName;
            displayElement.textContent = fontName;
        }

        // Show visual indicator for unsaved changes
        this.showUnsavedChanges();
    }

    showUnsavedChanges() {
        const saveButton = document.getElementById('save-typography');
        if (saveButton) {
            saveButton.classList.add('has-changes');
            saveButton.innerHTML = `
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                    <polyline points="17,21 17,13 7,13 7,21"/>
                    <polyline points="7,3 7,8 15,8"/>
                </svg>
                Save Changes *
            `;
        }
    }

    // Color Book functionality
    initColorBook() {
        // Load saved colors from database first
        this.loadSavedColors();
        
        // Color input event listeners
        document.querySelectorAll('.hex-input').forEach(input => {
            input.addEventListener('input', (e) => this.updateColorFromHex(e));
            input.addEventListener('focus', (e) => this.selectColorForPreview(e));
        });

        // Color swatch click listeners
        document.querySelectorAll('.color-swatch').forEach(swatch => {
            swatch.addEventListener('click', (e) => this.selectColorFromSwatch(e));
        });

        // HSLA input listeners
        document.querySelectorAll('.hsla-input').forEach(input => {
            input.addEventListener('input', (e) => this.updateColorFromHSLA(e));
        });

        // CMYK input listeners
        document.querySelectorAll('.cmyk-input').forEach(input => {
            input.addEventListener('input', (e) => this.updateColorFromCMYK(e));
        });

        // Action button listeners
        document.getElementById('save-colors')?.addEventListener('click', () => this.saveColors());
        document.getElementById('reset-colors')?.addEventListener('click', () => this.resetColors());
        document.getElementById('export-palette')?.addEventListener('click', () => this.exportPalette());

        // Initialize with primary color selected
        this.currentColor = 'primary';
        this.updatePreviewFromCurrentColor();
    }

    loadSavedColors() {
        // Load saved colors via AJAX
        fetch(migv_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_design_book_colors',
                nonce: migv_ajax.nonce
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.colors) {
                this.applySavedColors(data.data.colors);
            }
        })
        .catch(error => {
            console.log('No saved colors found, using defaults');
        });
    }

    applySavedColors(savedColors) {
        // Update all inputs and swatches with saved values
        Object.entries(savedColors).forEach(([slug, color]) => {
            const input = document.querySelector(`[data-color-slug="${slug}"]`);
            const swatch = document.querySelector(`[data-color="${slug}"] .color-swatch`);
            
            if (input) input.value = color;
            if (swatch) swatch.style.backgroundColor = color;
            
            // Update CSS custom property
            document.documentElement.style.setProperty(`--wp--preset--color--${slug}`, color);
        });

        // Update preview if current color was loaded
        if (savedColors[this.currentColor]) {
            this.updateColorPreview(savedColors[this.currentColor]);
        }
    }

    updateColorFromHex(event) {
        const input = event.target;
        const colorSlug = input.dataset.colorSlug;
        const hexValue = input.value;

        // Validate hex format
        if (!/^#[0-9A-Fa-f]{6}$/.test(hexValue)) {
            return;
        }

        // Update the color swatch
        const colorItem = input.closest('.color-item');
        const swatch = colorItem.querySelector('.color-swatch');
        swatch.style.backgroundColor = hexValue;

        // Update CSS custom property
        document.documentElement.style.setProperty(`--wp--preset--color--${colorSlug}`, hexValue);

        // Update preview if this is the current color
        if (colorSlug === this.currentColor) {
            this.updateColorPreview(hexValue);
        }
    }

    selectColorForPreview(event) {
        const input = event.target;
        const colorSlug = input.dataset.colorSlug;
        this.currentColor = colorSlug;
        this.updateColorPreview(input.value);
    }

    selectColorFromSwatch(event) {
        const swatch = event.target;
        const colorItem = swatch.closest('.color-item');
        const colorSlug = colorItem.dataset.color;
        const hexInput = colorItem.querySelector('.hex-input');
        
        this.currentColor = colorSlug;
        this.updateColorPreview(hexInput.value);
    }

    updateColorPreview(hexValue) {
        const previewElement = document.getElementById('color-preview-main');
        const hexDisplay = document.getElementById('preview-hex');
        const hslaDisplay = document.getElementById('preview-hsla');
        const cmykDisplay = document.getElementById('preview-cmyk');

        if (previewElement) {
            previewElement.style.backgroundColor = hexValue;
        }

        if (hexDisplay) {
            hexDisplay.textContent = hexValue;
        }

        // Convert and display HSLA
        const hsla = this.hexToHSLA(hexValue);
        if (hslaDisplay && hsla) {
            hslaDisplay.textContent = `hsla(${hsla.h}, ${hsla.s}%, ${hsla.l}%, ${hsla.a})`;
        }

        // Convert and display CMYK
        const cmyk = this.hexToCMYK(hexValue);
        if (cmykDisplay && cmyk) {
            cmykDisplay.textContent = `cmyk(${cmyk.c}%, ${cmyk.m}%, ${cmyk.y}%, ${cmyk.k}%)`;
        }

        // Update modifier inputs
        this.updateModifierInputs(hexValue);
    }

    updateModifierInputs(hexValue) {
        const hsla = this.hexToHSLA(hexValue);
        const cmyk = this.hexToCMYK(hexValue);

        // Update HSLA inputs
        if (hsla) {
            document.querySelector('.hsla-input[data-component="h"]').value = hsla.h;
            document.querySelector('.hsla-input[data-component="s"]').value = hsla.s;
            document.querySelector('.hsla-input[data-component="l"]').value = hsla.l;
            document.querySelector('.hsla-input[data-component="a"]').value = hsla.a;
        }

        // Update CMYK inputs
        if (cmyk) {
            document.querySelector('.cmyk-input[data-component="c"]').value = cmyk.c;
            document.querySelector('.cmyk-input[data-component="m"]').value = cmyk.m;
            document.querySelector('.cmyk-input[data-component="y"]').value = cmyk.y;
            document.querySelector('.cmyk-input[data-component="k"]').value = cmyk.k;
        }
    }

    updateColorFromHSLA(event) {
        const h = parseInt(document.querySelector('.hsla-input[data-component="h"]').value) || 0;
        const s = parseInt(document.querySelector('.hsla-input[data-component="s"]').value) || 0;
        const l = parseInt(document.querySelector('.hsla-input[data-component="l"]').value) || 0;
        const a = parseFloat(document.querySelector('.hsla-input[data-component="a"]').value) || 1;

        const hexValue = this.hslaToHex(h, s, l, a);
        this.updateCurrentColorFromModifier(hexValue);
    }

    updateColorFromCMYK(event) {
        const c = parseInt(document.querySelector('.cmyk-input[data-component="c"]').value) || 0;
        const m = parseInt(document.querySelector('.cmyk-input[data-component="m"]').value) || 0;
        const y = parseInt(document.querySelector('.cmyk-input[data-component="y"]').value) || 0;
        const k = parseInt(document.querySelector('.cmyk-input[data-component="k"]').value) || 0;

        const hexValue = this.cmykToHex(c, m, y, k);
        this.updateCurrentColorFromModifier(hexValue);
    }

    updateCurrentColorFromModifier(hexValue) {
        if (!this.currentColor) return;

        // Update the current color's input and swatch
        const colorItem = document.querySelector(`[data-color="${this.currentColor}"]`);
        if (colorItem) {
            const hexInput = colorItem.querySelector('.hex-input');
            const swatch = colorItem.querySelector('.color-swatch');
            
            hexInput.value = hexValue;
            swatch.style.backgroundColor = hexValue;
            
            // Update CSS custom property
            document.documentElement.style.setProperty(`--wp--preset--color--${this.currentColor}`, hexValue);
        }

        // Update preview
        this.updateColorPreview(hexValue);
    }

    updatePreviewFromCurrentColor() {
        if (!this.currentColor) return;
        
        const colorItem = document.querySelector(`[data-color="${this.currentColor}"]`);
        if (colorItem) {
            const hexInput = colorItem.querySelector('.hex-input');
            this.updateColorPreview(hexInput.value);
        }
    }

    // Color conversion utilities
    hexToHSLA(hex) {
        const r = parseInt(hex.slice(1, 3), 16) / 255;
        const g = parseInt(hex.slice(3, 5), 16) / 255;
        const b = parseInt(hex.slice(5, 7), 16) / 255;

        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
        let h, s, l = (max + min) / 2;

        if (max === min) {
            h = s = 0;
        } else {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch (max) {
                case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                case g: h = (b - r) / d + 2; break;
                case b: h = (r - g) / d + 4; break;
            }
            h /= 6;
        }

        return {
            h: Math.round(h * 360),
            s: Math.round(s * 100),
            l: Math.round(l * 100),
            a: 1
        };
    }

    hslaToHex(h, s, l, a) {
        l /= 100;
        const c = (1 - Math.abs(2 * l - 1)) * s / 100;
        const x = c * (1 - Math.abs((h / 60) % 2 - 1));
        const m = l - c / 2;
        let r = 0, g = 0, b = 0;

        if (0 <= h && h < 60) {
            r = c; g = x; b = 0;
        } else if (60 <= h && h < 120) {
            r = x; g = c; b = 0;
        } else if (120 <= h && h < 180) {
            r = 0; g = c; b = x;
        } else if (180 <= h && h < 240) {
            r = 0; g = x; b = c;
        } else if (240 <= h && h < 300) {
            r = x; g = 0; b = c;
        } else if (300 <= h && h < 360) {
            r = c; g = 0; b = x;
        }

        r = Math.round((r + m) * 255);
        g = Math.round((g + m) * 255);
        b = Math.round((b + m) * 255);

        return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
    }

    hexToCMYK(hex) {
        const r = parseInt(hex.slice(1, 3), 16) / 255;
        const g = parseInt(hex.slice(3, 5), 16) / 255;
        const b = parseInt(hex.slice(5, 7), 16) / 255;

        const k = 1 - Math.max(r, g, b);
        const c = (1 - r - k) / (1 - k) || 0;
        const m = (1 - g - k) / (1 - k) || 0;
        const y = (1 - b - k) / (1 - k) || 0;

        return {
            c: Math.round(c * 100),
            m: Math.round(m * 100),
            y: Math.round(y * 100),
            k: Math.round(k * 100)
        };
    }

    cmykToHex(c, m, y, k) {
        c /= 100;
        m /= 100;
        y /= 100;
        k /= 100;

        const r = Math.round(255 * (1 - c) * (1 - k));
        const g = Math.round(255 * (1 - m) * (1 - k));
        const b = Math.round(255 * (1 - y) * (1 - k));

        return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
    }

    saveColors() {
        const colorData = {};
        
        // Collect all color values
        document.querySelectorAll('.hex-input').forEach(input => {
            const colorSlug = input.dataset.colorSlug;
            colorData[colorSlug] = input.value;
        });

        // Save via AJAX
        fetch(migv_ajax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'save_design_book_colors',
                nonce: migv_ajax.nonce,
                colors: JSON.stringify(colorData)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Colors saved successfully!', 'success');
            } else {
                this.showNotification('Failed to save colors.', 'error');
            }
        })
        .catch(error => {
            console.error('Error saving colors:', error);
            this.showNotification('Error saving colors.', 'error');
        });
    }

    resetColors() {
        if (confirm('Are you sure you want to reset all colors to their default values?')) {
            // Reset to theme.json defaults
            const defaults = {
                'primary-light': '#d6dcd6',
                'primary': '#5a7b7c',
                'primary-dark': '#3a5a59',
                'secondary-light': '#c38484',
                'secondary': '#975d55',
                'secondary-dark': '#853d2d',
                'neutral-light': '#d1cfc2',
                'neutral': '#b5b09f',
                'neutral-dark': '#9e9983',
                'base-lightest': '#e6e6e6',
                'base-light': '#b3b3b3',
                'base': '#808080',
                'base-dark': '#676765',
                'base-darkest': '#4d4d4d',
                'extreme-light': '#ffffff',
                'extreme-dark': '#000000'
            };

            // Update all inputs and swatches
            Object.entries(defaults).forEach(([slug, color]) => {
                const input = document.querySelector(`[data-color-slug="${slug}"]`);
                const swatch = document.querySelector(`[data-color="${slug}"] .color-swatch`);
                
                if (input) input.value = color;
                if (swatch) swatch.style.backgroundColor = color;
                
                // Update CSS custom property
                document.documentElement.style.setProperty(`--wp--preset--color--${slug}`, color);
            });

            // Update preview
            this.updatePreviewFromCurrentColor();
            this.showNotification('Colors reset to defaults.', 'success');
        }
    }

    exportPalette() {
        const colorData = {};
        
        document.querySelectorAll('.hex-input').forEach(input => {
            const colorSlug = input.dataset.colorSlug;
            colorData[colorSlug] = input.value;
        });

        const dataStr = JSON.stringify(colorData, null, 2);
        const dataBlob = new Blob([dataStr], {type: 'application/json'});
        const url = URL.createObjectURL(dataBlob);
        
        const link = document.createElement('a');
        link.href = url;
        link.download = 'villa-color-palette.json';
        link.click();
        
        URL.revokeObjectURL(url);
        this.showNotification('Color palette exported!', 'success');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.villa-design-book.typography-book')) {
        new VillaTypographyBook();
    }
});

// Export for potential external use
window.VillaTypographyBook = VillaTypographyBook;
