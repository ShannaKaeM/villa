<?php
/**
 * Template Name: Semantic Color Reference
 * Description: Visual reference for 14 semantic colors with OKLCH editing
 */

get_header();
?>

<style>
    .semantic-reference {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: system-ui, -apple-system, sans-serif;
    }
    
    .color-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }
    
    .color-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .color-preview {
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        position: relative;
    }
    
    .color-info {
        padding: 20px;
    }
    
    .color-name {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .color-values {
        display: grid;
        gap: 8px;
        font-size: 13px;
        font-family: monospace;
    }
    
    .color-hex {
        background: #f5f5f5;
        padding: 8px 12px;
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .copy-btn {
        background: var(--theme-primary);
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 11px;
        cursor: pointer;
    }
    
    .copy-btn:hover {
        background: var(--theme-primary-dark);
    }
    
    .oklch-editor {
        margin-top: 12px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 6px;
    }
    
    .slider-group {
        margin-bottom: 8px;
    }
    
    .slider-group label {
        display: block;
        font-size: 12px;
        margin-bottom: 4px;
        color: #666;
    }
    
    .slider {
        width: 100%;
        height: 6px;
        -webkit-appearance: none;
        appearance: none;
        background: #ddd;
        border-radius: 3px;
        outline: none;
    }
    
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 16px;
        height: 16px;
        background: var(--theme-primary);
        border-radius: 50%;
        cursor: pointer;
    }
    
    .oklch-value {
        font-size: 11px;
        color: #888;
        margin-top: 4px;
    }
    
    .sync-preview {
        background: #f0f4f4;
        padding: 30px;
        border-radius: 12px;
        margin-top: 40px;
    }
    
    .sync-preview h2 {
        margin-bottom: 20px;
    }
    
    .sync-colors {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    
    .sync-item {
        background: white;
        padding: 16px;
        border-radius: 8px;
        text-align: center;
    }
    
    .sync-swatch {
        width: 60px;
        height: 60px;
        margin: 0 auto 12px;
        border-radius: 8px;
        border: 2px solid #e5e5e5;
    }
    
    .is-light { color: #000; }
    .is-dark { color: #fff; }
</style>

<div class="semantic-reference">
    <h1 style="text-align: center; margin-bottom: 40px;">Semantic Color System</h1>
    
    <div class="color-grid">
        <?php
        $colors = [
            'Primary' => [
                'primary-light' => ['name' => 'Primary Light', 'oklch' => [70, 0.045, 194]],
                'primary' => ['name' => 'Primary', 'oklch' => [56, 0.064, 194]],
                'primary-dark' => ['name' => 'Primary Dark', 'oklch' => [40, 0.075, 194]]
            ],
            'Secondary' => [
                'secondary-light' => ['name' => 'Secondary Light', 'oklch' => [75, 0.065, 64]],
                'secondary' => ['name' => 'Secondary', 'oklch' => [60, 0.089, 64]],
                'secondary-dark' => ['name' => 'Secondary Dark', 'oklch' => [45, 0.095, 64]]
            ],
            'Neutral' => [
                'neutral-light' => ['name' => 'Neutral Light', 'oklch' => [75, 0.035, 73]],
                'neutral' => ['name' => 'Neutral', 'oklch' => [62, 0.051, 73]],
                'neutral-dark' => ['name' => 'Neutral Dark', 'oklch' => [45, 0.055, 73]]
            ],
            'Base' => [
                'base-lightest' => ['name' => 'Base Lightest', 'oklch' => [98, 0, 0]],
                'base-light' => ['name' => 'Base Light', 'oklch' => [90, 0, 0]],
                'base' => ['name' => 'Base', 'oklch' => [66, 0, 0]],
                'base-dark' => ['name' => 'Base Dark', 'oklch' => [35, 0, 0]],
                'base-darkest' => ['name' => 'Base Darkest', 'oklch' => [15, 0, 0]]
            ]
        ];
        
        foreach ($colors as $group => $groupColors) {
            foreach ($groupColors as $key => $color) {
                $isLight = $color['oklch'][0] > 60;
                $textClass = $isLight ? 'is-light' : 'is-dark';
                $cssVar = "--theme-{$key}";
                ?>
                <div class="color-card">
                    <div class="color-preview <?php echo $textClass; ?>" 
                         style="background: var(<?php echo $cssVar; ?>);"
                         data-color-key="<?php echo $key; ?>">
                        <?php echo $color['name']; ?>
                    </div>
                    <div class="color-info">
                        <div class="color-name"><?php echo $color['name']; ?></div>
                        <div class="color-values">
                            <div class="color-hex">
                                <span class="hex-value" id="hex-<?php echo $key; ?>">Calculating...</span>
                                <button class="copy-btn" onclick="copyHex('<?php echo $key; ?>')">Copy</button>
                            </div>
                            <div class="oklch-editor">
                                <div class="slider-group">
                                    <label>Lightness: <span id="l-val-<?php echo $key; ?>"><?php echo $color['oklch'][0]; ?>%</span></label>
                                    <input type="range" class="slider" id="l-<?php echo $key; ?>" 
                                           min="0" max="100" value="<?php echo $color['oklch'][0]; ?>"
                                           data-color="<?php echo $key; ?>" data-prop="l">
                                </div>
                                <div class="slider-group">
                                    <label>Chroma: <span id="c-val-<?php echo $key; ?>"><?php echo $color['oklch'][1]; ?></span></label>
                                    <input type="range" class="slider" id="c-<?php echo $key; ?>" 
                                           min="0" max="0.4" step="0.001" value="<?php echo $color['oklch'][1]; ?>"
                                           data-color="<?php echo $key; ?>" data-prop="c">
                                </div>
                                <div class="slider-group">
                                    <label>Hue: <span id="h-val-<?php echo $key; ?>"><?php echo $color['oklch'][2]; ?>°</span></label>
                                    <input type="range" class="slider" id="h-<?php echo $key; ?>" 
                                           min="0" max="360" value="<?php echo $color['oklch'][2]; ?>"
                                           data-color="<?php echo $key; ?>" data-prop="h">
                                </div>
                                <div class="oklch-value" id="oklch-<?php echo $key; ?>">
                                    oklch(<?php echo $color['oklch'][0]; ?>% <?php echo $color['oklch'][1]; ?> <?php echo $color['oklch'][2]; ?>)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        
        <!-- Black & White -->
        <div class="color-card">
            <div class="color-preview is-dark" style="background: var(--theme-black);">
                Black
            </div>
            <div class="color-info">
                <div class="color-name">Black</div>
                <div class="color-values">
                    <div class="color-hex">
                        <span>#000000</span>
                        <button class="copy-btn" onclick="navigator.clipboard.writeText('#000000')">Copy</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="color-card">
            <div class="color-preview is-light" style="background: var(--theme-white);">
                White
            </div>
            <div class="color-info">
                <div class="color-name">White</div>
                <div class="color-values">
                    <div class="color-hex">
                        <span>#FFFFFF</span>
                        <button class="copy-btn" onclick="navigator.clipboard.writeText('#FFFFFF')">Copy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sync-preview">
        <h2>Blocksy Customizer Sync Values</h2>
        <p>These are the hex values to manually set in Blocksy's color palette:</p>
        <div class="sync-colors" id="sync-colors">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<script>
// Convert OKLCH to RGB (simplified conversion)
function oklchToRgb(l, c, h) {
    // This is a simplified conversion - for production use a proper color library
    const canvas = document.createElement('canvas');
    canvas.width = 1;
    canvas.height = 1;
    const ctx = canvas.getContext('2d');
    ctx.fillStyle = `oklch(${l}% ${c} ${h})`;
    ctx.fillRect(0, 0, 1, 1);
    const data = ctx.getImageData(0, 0, 1, 1).data;
    return `#${[data[0], data[1], data[2]].map(x => x.toString(16).padStart(2, '0')).join('')}`;
}

// Update color display
function updateColor(colorKey) {
    const l = document.getElementById(`l-${colorKey}`).value;
    const c = document.getElementById(`c-${colorKey}`).value;
    const h = document.getElementById(`h-${colorKey}`).value;
    
    // Update value displays
    document.getElementById(`l-val-${colorKey}`).textContent = l + '%';
    document.getElementById(`c-val-${colorKey}`).textContent = c;
    document.getElementById(`h-val-${colorKey}`).textContent = h + '°';
    
    // Update OKLCH display
    document.getElementById(`oklch-${colorKey}`).textContent = `oklch(${l}% ${c} ${h})`;
    
    // Update CSS variable
    document.documentElement.style.setProperty(`--theme-${colorKey}`, `oklch(${l}% ${c} ${h})`);
    
    // Update hex value
    const hex = oklchToRgb(l, c, h);
    document.getElementById(`hex-${colorKey}`).textContent = hex.toUpperCase();
    
    // Update sync preview
    updateSyncPreview();
}

// Copy hex value
function copyHex(colorKey) {
    const hex = document.getElementById(`hex-${colorKey}`).textContent;
    navigator.clipboard.writeText(hex);
    
    // Show notification
    const btn = event.target;
    const originalText = btn.textContent;
    btn.textContent = 'Copied!';
    setTimeout(() => btn.textContent = originalText, 1000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set up sliders
    document.querySelectorAll('.slider').forEach(slider => {
        slider.addEventListener('input', function() {
            updateColor(this.dataset.color);
        });
    });
    
    // Calculate initial hex values
    const colorKeys = ['primary-light', 'primary', 'primary-dark', 'secondary-light', 'secondary', 'secondary-dark', 'neutral-light', 'neutral', 'neutral-dark', 'base-lightest', 'base-light', 'base', 'base-dark', 'base-darkest'];
    colorKeys.forEach(key => updateColor(key));
});

// Update sync preview
function updateSyncPreview() {
    const syncColors = [
        { name: 'Palette Color 1', key: 'primary', desc: 'Primary' },
        { name: 'Palette Color 2', key: 'secondary', desc: 'Secondary' },
        { name: 'Palette Color 3', key: 'neutral', desc: 'Neutral' },
        { name: 'Palette Color 4', key: 'base', desc: 'Base' },
        { name: 'Palette Color 5', key: 'primary-light', desc: 'Primary Light' },
        { name: 'Palette Color 6', key: 'primary-dark', desc: 'Primary Dark' },
        { name: 'Palette Color 7', key: 'secondary-light', desc: 'Secondary Light' },
        { name: 'Palette Color 8', key: 'secondary-dark', desc: 'Secondary Dark' }
    ];
    
    const container = document.getElementById('sync-colors');
    container.innerHTML = syncColors.map(color => {
        const hex = document.getElementById(`hex-${color.key}`).textContent;
        return `
            <div class="sync-item">
                <div class="sync-swatch" style="background: ${hex};"></div>
                <strong>${color.name}</strong><br>
                <code>${hex}</code><br>
                <small>${color.desc}</small>
            </div>
        `;
    }).join('');
}
</script>

<?php get_footer(); ?>
