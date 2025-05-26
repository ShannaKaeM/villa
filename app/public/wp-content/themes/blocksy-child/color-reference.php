<?php
/**
 * Template Name: Color Reference
 * Description: Visual reference for all theme colors
 */

get_header();
?>

<style>
    .color-reference {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
        font-family: system-ui, -apple-system, sans-serif;
    }
    
    .color-group {
        margin-bottom: 60px;
    }
    
    .color-group h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: var(--wp--preset--color--neutral-dark);
    }
    
    .color-scale {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 4px;
        margin-bottom: 40px;
    }
    
    .color-swatch {
        aspect-ratio: 1;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        transition: transform 0.2s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .color-swatch:hover {
        transform: scale(1.05);
        z-index: 10;
        box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    
    .color-info {
        text-align: center;
        padding: 8px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        transform: translateY(100%);
        transition: transform 0.2s ease;
    }
    
    .color-swatch:hover .color-info {
        transform: translateY(0);
    }
    
    .color-value {
        font-size: 10px;
        font-family: monospace;
        color: #333;
        margin-top: 2px;
    }
    
    .color-name {
        color: #000;
        font-weight: 700;
    }
    
    /* Light colors get dark text */
    .is-light {
        color: #000;
    }
    
    /* Dark colors get light text */
    .is-dark {
        color: #fff;
    }
    
    .sync-section {
        background: var(--wp--preset--color--base-100);
        padding: 30px;
        border-radius: 12px;
        margin: 40px 0;
    }
    
    .sync-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .sync-item {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sync-color {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        border: 2px solid var(--wp--preset--color--base-300);
    }
    
    .header-section {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, var(--wp--preset--color--primary-100) 0%, var(--wp--preset--color--secondary-100) 100%);
        margin-bottom: 40px;
    }
    
    .header-section h1 {
        font-size: 48px;
        margin-bottom: 16px;
        color: var(--wp--preset--color--primary-dark);
    }
    
    .header-section p {
        font-size: 18px;
        color: var(--wp--preset--color--neutral-dark);
    }
</style>

<div class="header-section">
    <h1>Villa Community Color System</h1>
    <p>Complete color reference with OKLCH-generated scales</p>
</div>

<div class="color-reference">
    
    <!-- Primary Colors -->
    <div class="color-group">
        <h2>Primary Colors (#5a7f80)</h2>
        <div class="color-scale">
            <?php 
            $primary_scale = [
                '50' => '#f0f4f4',
                '100' => '#d9e3e3',
                '200' => '#b3c7c8',
                '300' => '#8dabac',
                '400' => '#739596',
                '500' => '#5a7f80',
                '600' => '#4e6d6e',
                '700' => '#425a5b',
                '800' => '#364849',
                '900' => '#2a3637',
                '950' => '#1a2122'
            ];
            
            foreach ($primary_scale as $shade => $hex) {
                $is_dark = intval($shade) >= 600 ? 'is-dark' : 'is-light';
                ?>
                <div class="color-swatch <?php echo $is_dark; ?>" 
                     style="background-color: var(--wp--preset--color--primary-<?php echo $shade; ?>);"
                     onclick="navigator.clipboard.writeText('var(--wp--preset--color--primary-<?php echo $shade; ?>)')">
                    <span class="color-name"><?php echo $shade; ?></span>
                    <div class="color-info">
                        <div class="color-value"><?php echo $hex; ?></div>
                        <div class="color-value">primary-<?php echo $shade; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Secondary Colors -->
    <div class="color-group">
        <h2>Secondary Colors (#a36b57)</h2>
        <div class="color-scale">
            <?php 
            $secondary_scale = [
                '50' => '#faf6f4',
                '100' => '#f2e6e1',
                '200' => '#e5cdc3',
                '300' => '#d1a896',
                '400' => '#ba8976',
                '500' => '#a36b57',
                '600' => '#8c5c4b',
                '700' => '#744d3e',
                '800' => '#5d3e32',
                '900' => '#462f26',
                '950' => '#2a1c17'
            ];
            
            foreach ($secondary_scale as $shade => $hex) {
                $is_dark = intval($shade) >= 600 ? 'is-dark' : 'is-light';
                ?>
                <div class="color-swatch <?php echo $is_dark; ?>" 
                     style="background-color: var(--wp--preset--color--secondary-<?php echo $shade; ?>);"
                     onclick="navigator.clipboard.writeText('var(--wp--preset--color--secondary-<?php echo $shade; ?>)')">
                    <span class="color-name"><?php echo $shade; ?></span>
                    <div class="color-info">
                        <div class="color-value"><?php echo $hex; ?></div>
                        <div class="color-value">secondary-<?php echo $shade; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Neutral Colors -->
    <div class="color-group">
        <h2>Neutral Colors (#9b8974)</h2>
        <div class="color-scale">
            <?php 
            $neutral_scale = [
                '50' => '#f9f7f5',
                '100' => '#f0ebe6',
                '200' => '#e1d7cd',
                '300' => '#cdbfad',
                '400' => '#b4a490',
                '500' => '#9b8974',
                '600' => '#847563',
                '700' => '#6d6152',
                '800' => '#574e42',
                '900' => '#413a32',
                '950' => '#27231e'
            ];
            
            foreach ($neutral_scale as $shade => $hex) {
                $is_dark = intval($shade) >= 600 ? 'is-dark' : 'is-light';
                ?>
                <div class="color-swatch <?php echo $is_dark; ?>" 
                     style="background-color: var(--wp--preset--color--neutral-<?php echo $shade; ?>);"
                     onclick="navigator.clipboard.writeText('var(--wp--preset--color--neutral-<?php echo $shade; ?>)')">
                    <span class="color-name"><?php echo $shade; ?></span>
                    <div class="color-info">
                        <div class="color-value"><?php echo $hex; ?></div>
                        <div class="color-value">neutral-<?php echo $shade; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Base Colors -->
    <div class="color-group">
        <h2>Base Colors (#9c9c9c)</h2>
        <div class="color-scale">
            <?php 
            $base_scale = [
                '50' => '#fafafa',
                '100' => '#f5f5f5',
                '200' => '#e5e5e5',
                '300' => '#d4d4d4',
                '400' => '#b8b8b8',
                '500' => '#9c9c9c',
                '600' => '#858585',
                '700' => '#6e6e6e',
                '800' => '#585858',
                '900' => '#424242',
                '950' => '#262626'
            ];
            
            foreach ($base_scale as $shade => $hex) {
                $is_dark = intval($shade) >= 500 ? 'is-dark' : 'is-light';
                ?>
                <div class="color-swatch <?php echo $is_dark; ?>" 
                     style="background-color: var(--wp--preset--color--base-<?php echo $shade; ?>);"
                     onclick="navigator.clipboard.writeText('var(--wp--preset--color--base-<?php echo $shade; ?>)')">
                    <span class="color-name"><?php echo $shade; ?></span>
                    <div class="color-info">
                        <div class="color-value"><?php echo $hex; ?></div>
                        <div class="color-value">base-<?php echo $shade; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Blocksy Sync Reference -->
    <div class="sync-section">
        <h2>Blocksy Customizer Sync Reference</h2>
        <p>Set these colors in Appearance → Customize → Colors:</p>
        <div class="sync-grid">
            <div class="sync-item">
                <div class="sync-color" style="background: #5a7f80;"></div>
                <div>
                    <strong>Palette Color 1</strong><br>
                    <code>#5a7f80</code> (Primary)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #a36b57;"></div>
                <div>
                    <strong>Palette Color 2</strong><br>
                    <code>#a36b57</code> (Secondary)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #9b8974;"></div>
                <div>
                    <strong>Palette Color 3</strong><br>
                    <code>#9b8974</code> (Neutral)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #9c9c9c;"></div>
                <div>
                    <strong>Palette Color 4</strong><br>
                    <code>#9c9c9c</code> (Base)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #8dabac;"></div>
                <div>
                    <strong>Palette Color 5</strong><br>
                    <code>#8dabac</code> (Primary Light)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #425a5b;"></div>
                <div>
                    <strong>Palette Color 6</strong><br>
                    <code>#425a5b</code> (Primary Dark)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #d1a896;"></div>
                <div>
                    <strong>Palette Color 7</strong><br>
                    <code>#d1a896</code> (Secondary Light)
                </div>
            </div>
            <div class="sync-item">
                <div class="sync-color" style="background: #744d3e;"></div>
                <div>
                    <strong>Palette Color 8</strong><br>
                    <code>#744d3e</code> (Secondary Dark)
                </div>
            </div>
        </div>
    </div>
    
    <div style="text-align: center; padding: 40px 0; color: var(--wp--preset--color--neutral);">
        <p><strong>Tip:</strong> Click any color swatch to copy its CSS variable to clipboard!</p>
        <p>These colors are dynamically generated using OKLCH color space for perfect perceptual uniformity.</p>
    </div>
    
</div>

<script>
// Show notification when color is copied
document.querySelectorAll('.color-swatch').forEach(swatch => {
    swatch.addEventListener('click', function() {
        const tempDiv = document.createElement('div');
        tempDiv.textContent = 'Copied!';
        tempDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--wp--preset--color--primary);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        document.body.appendChild(tempDiv);
        setTimeout(() => tempDiv.remove(), 2000);
    });
});
</script>

<style>
@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
</style>

<?php get_footer(); ?>
