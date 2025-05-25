<?php
/**
 * Hero Block Template
 *
 * @param array $attributes The block attributes.
 * @param string $content The block content.
 * @param WP_Block $block The block instance.
 */

// Extract attributes
$backgroundImage = $attributes['backgroundImage'] ?? '';
$overlayColor = $attributes['overlayColor'] ?? '#000000';
$overlayOpacity = $attributes['overlayOpacity'] ?? 50;
$title = $attributes['title'] ?? '';
$titleColor = $attributes['titleColor'] ?? '#ffffff';
$titleSize = $attributes['titleSize'] ?? '2.25rem';
$titleWeight = $attributes['titleWeight'] ?? 'var(--wp--custom--font-weight--bold)';
$titleTracking = $attributes['titleTracking'] ?? 'var(--wp--custom--letter-spacing--normal)';
$subtitle = $attributes['subtitle'] ?? '';
$subtitleColor = $attributes['subtitleColor'] ?? '#ffffff';
$subtitleSize = $attributes['subtitleSize'] ?? '1.25rem';
$subtitleWeight = $attributes['subtitleWeight'] ?? 'var(--wp--custom--font-weight--normal)';
$subtitleTracking = $attributes['subtitleTracking'] ?? 'var(--wp--custom--letter-spacing--normal)';
$minHeight = $attributes['minHeight'] ?? 400;
$padding = $attributes['padding'] ?? ['top' => '3rem', 'right' => '1rem', 'bottom' => '3rem', 'left' => '1rem'];
$borderRadius = $attributes['borderRadius'] ?? 'var(--wp--custom--border-radius--lg)';
$width = $attributes['width'] ?? 'wide';

// Build wrapper classes
$wrapperClasses = ['hero-block', 'hero-block--' . $width];

// Build inline styles
$wrapperStyles = [
    '--hero-min-height' => $minHeight . 'px',
    '--hero-overlay-color' => $overlayColor,
    '--hero-overlay-opacity' => $overlayOpacity / 100,
    '--hero-title-color' => $titleColor,
    '--hero-title-size' => $titleSize,
    '--hero-title-weight' => $titleWeight,
    '--hero-title-tracking' => $titleTracking,
    '--hero-subtitle-color' => $subtitleColor,
    '--hero-subtitle-size' => $subtitleSize,
    '--hero-subtitle-weight' => $subtitleWeight,
    '--hero-subtitle-tracking' => $subtitleTracking,
    '--hero-padding-top' => $padding['top'] ?? '3rem',
    '--hero-padding-right' => $padding['right'] ?? '1rem',
    '--hero-padding-bottom' => $padding['bottom'] ?? '3rem',
    '--hero-padding-left' => $padding['left'] ?? '1rem',
    '--hero-border-radius' => $width === 'full-width' ? '0' : $borderRadius
];

$styleString = '';
foreach ($wrapperStyles as $property => $value) {
    $styleString .= $property . ': ' . $value . '; ';
}

?>

<div class="<?php echo esc_attr(implode(' ', $wrapperClasses)); ?>" style="<?php echo esc_attr($styleString); ?>">
    <div class="hero-block__container" <?php if ($backgroundImage): ?>style="background-image: url('<?php echo esc_url($backgroundImage); ?>');"<?php endif; ?>>
        <div class="hero-block__overlay"></div>
        <div class="hero-block__content">
            <?php if (!empty($title)): ?>
                <h1 class="hero-block__title"><?php echo wp_kses_post($title); ?></h1>
            <?php endif; ?>
            <?php if (!empty($subtitle)): ?>
                <p class="hero-block__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
