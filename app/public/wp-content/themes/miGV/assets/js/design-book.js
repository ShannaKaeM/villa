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
        console.log('🎨 Villa Typography Book initialized');
        
        // Initialize tab system
        this.initTabs();
        
        // Initialize typography scale
        this.initTypographyScale();
        
        // Initialize text styles
        this.initTextStyles();
        
        // Initialize tokens display
        this.initTokensDisplay();
    }

    bindEvents() {
        // Tab navigation
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', (e) => this.switchTab(e));
        });

        // Scale controls
        const baseFontSize = document.getElementById('base-font-size');
        const baseFontSizeValue = document.getElementById('base-font-size-value');
        const scaleRatio = document.getElementById('scale-ratio');
        const primaryFont = document.getElementById('primary-font');

        if (baseFontSize) {
            baseFontSize.addEventListener('input', (e) => this.updateBaseFontSize(e));
        }
        
        if (baseFontSizeValue) {
            baseFontSizeValue.addEventListener('input', (e) => this.updateBaseFontSizeFromInput(e));
        }
        
        if (scaleRatio) {
            scaleRatio.addEventListener('change', (e) => this.updateScaleRatio(e));
        }
        
        if (primaryFont) {
            primaryFont.addEventListener('change', (e) => this.updatePrimaryFont(e));
        }

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

        // Save and reset buttons
        const saveButton = document.getElementById('save-typography');
        const resetButton = document.getElementById('reset-typography');
        
        if (saveButton) {
            saveButton.addEventListener('click', () => this.saveTypography());
        }
        
        if (resetButton) {
            resetButton.addEventListener('click', () => this.resetTypography());
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

    initTypographyScale() {
        this.currentScale = {
            baseSize: 16,
            ratio: 1.125,
            font: 'inter'
        };
        
        this.updateScalePreview();
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
        
        // Show success message
        this.showNotification('Typography settings saved successfully!', 'success');
    }

    saveToWordPress(settings) {
        // Prepare data for WordPress AJAX
        const formData = new FormData();
        formData.append('action', 'villa_save_typography_settings');
        formData.append('nonce', window.migv_nonce || '');
        formData.append('typography_settings', JSON.stringify(settings));
        
        fetch(window.ajaxurl || '/wp-admin/admin-ajax.php', {
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
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.villa-design-book.typography-book')) {
        new VillaTypographyBook();
    }
});

// Export for potential external use
window.VillaTypographyBook = VillaTypographyBook;
