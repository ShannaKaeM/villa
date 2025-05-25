<?php
/**
 * Hero Block Template
 *
 * @param array $attributes The block attributes.
 * @param string $content The block content.
 * @param WP_Block $block The block instance.
 */

// Get attributes with defaults
$title = $attributes['title'] ?? '';
$subtitle = $attributes['subtitle'] ?? '';
$backgroundImage = $attributes['backgroundImage'] ?? '';
$minHeight = $attributes['minHeight'] ?? '500px';
$widthOption = $attributes['widthOption'] ?? '90';
$borderRadius = $attributes['borderRadius'] ?? 'none';
$overlayColor = $attributes['overlayColor'] ?? 'primary';
$overlayVariation = $attributes['overlayVariation'] ?? 'med';
$overlayOpacity = $attributes['overlayOpacity'] ?? 0.5;
$titleSize = $attributes['titleSize'] ?? '3xl';
$titleWeight = $attributes['titleWeight'] ?? 'bold';
$titleColor = $attributes['titleColor'] ?? 'white';
$titleColorVariation = $attributes['titleColorVariation'] ?? 'med';
$titleTracking = $attributes['titleTracking'] ?? 'normal';
$titleTransform = $attributes['titleTransform'] ?? 'none';
$titleSpacing = $attributes['titleSpacing'] ?? 'md';
$subtitleSize = $attributes['subtitleSize'] ?? 'lg';
$subtitleWeight = $attributes['subtitleWeight'] ?? 'normal';
$subtitleColor = $attributes['subtitleColor'] ?? 'white';
$subtitleColorVariation = $attributes['subtitleColorVariation'] ?? 'med';
$subtitleTracking = $attributes['subtitleTracking'] ?? 'normal';
$subtitleTransform = $attributes['subtitleTransform'] ?? 'none';

// Helper function to get color CSS variable
if (!function_exists('get_hero_color_var')) {
    function get_hero_color_var($color, $variation = 'med') {
        if ($color === 'white' || $color === 'black') {
            return "var(--color-{$color})";
        }
        return $variation === 'med' 
            ? "var(--color-{$color}-med)" 
            : "var(--color-{$color}-{$variation})";
    }
}

// Build wrapper classes
$wrapper_classes = ['hero-block'];
if ($widthOption === 'content') {
    $wrapper_classes[] = 'hero-block--content-width';
}

// Build container classes
$container_classes = ['hero-block__container'];
if ($borderRadius !== 'none') {
    $container_classes[] = "hero-block__container--radius-{$borderRadius}";
}

// Build inline styles
$container_style = '';
if (!empty($backgroundImage)) {
    $container_style .= "background-image: url({$backgroundImage});";
}
if (!empty($minHeight)) {
    $container_style .= "min-height: {$minHeight};";
}

// Build overlay style with color-mix for opacity
$overlay_color_var = get_hero_color_var($overlayColor, $overlayVariation);
$overlay_opacity_percent = $overlayOpacity * 100;
$overlay_style = "background-color: color-mix(in srgb, {$overlay_color_var} {$overlay_opacity_percent}%, transparent);";

// Build title styles
$title_style = '';
$title_style .= "font-size: var(--font-size-{$titleSize});";
$title_style .= "font-weight: var(--font-weight-{$titleWeight});";
$title_style .= "color: " . get_hero_color_var($titleColor, $titleColorVariation) . ";";
$title_style .= "letter-spacing: var(--letter-spacing-{$titleTracking});";
$title_style .= "text-transform: var(--text-transform-{$titleTransform});";
$title_style .= "margin-bottom: var(--spacing-{$titleSpacing});";

// Build subtitle styles
$subtitle_style = '';
$subtitle_style .= "font-size: var(--font-size-{$subtitleSize});";
$subtitle_style .= "font-weight: var(--font-weight-{$subtitleWeight});";
$subtitle_style .= "color: " . get_hero_color_var($subtitleColor, $subtitleColorVariation) . ";";
$subtitle_style .= "letter-spacing: var(--letter-spacing-{$subtitleTracking});";
$subtitle_style .= "text-transform: var(--text-transform-{$subtitleTransform});";

// Get block wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => implode(' ', $wrapper_classes)
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="<?php echo esc_attr(implode(' ', $container_classes)); ?>" style="<?php echo esc_attr($container_style); ?>">
        <div class="hero-block__overlay" style="<?php echo esc_attr($overlay_style); ?>"></div>
        <div class="hero-block__content">
            <div class="hero-block__text-wrapper">
                <?php if (!empty($title)) : ?>
                    <h1 class="hero-block__title" style="<?php echo esc_attr($title_style); ?>">
                        <?php echo wp_kses_post($title); ?>
                    </h1>
                <?php endif; ?>
                
                <?php if (!empty($subtitle)) : ?>
                    <p class="hero-block__subtitle" style="<?php echo esc_attr($subtitle_style); ?>">
                        <?php echo wp_kses_post($subtitle); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
