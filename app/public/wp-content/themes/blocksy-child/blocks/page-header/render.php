<?php
/**
 * Page Header Block Template
 *
 * @param array $attributes The block attributes.
 * @param string $content The block default content.
 * @param WP_Block $block The block instance.
 */

// Get attributes with defaults
$title = isset($attributes['title']) ? $attributes['title'] : 'Page Title';
$subtitle = isset($attributes['subtitle']) ? $attributes['subtitle'] : '';
$backgroundImage = isset($attributes['backgroundImage']) ? $attributes['backgroundImage'] : '';
$overlayOpacity = isset($attributes['overlayOpacity']) ? $attributes['overlayOpacity'] : 0.5;
$minHeight = isset($attributes['minHeight']) ? $attributes['minHeight'] : '400px';
$borderRadius = isset($attributes['borderRadius']) ? $attributes['borderRadius'] : 'lg';
$titleSpacing = isset($attributes['titleSpacing']) ? $attributes['titleSpacing'] : '4';
$backgroundPositionY = isset($attributes['backgroundPositionY']) ? $attributes['backgroundPositionY'] : 'center';

// Typography attributes
$titleSize = isset($attributes['titleSize']) ? $attributes['titleSize'] : '4xl';
$titleWeight = isset($attributes['titleWeight']) ? $attributes['titleWeight'] : 'bold';
$titleColor = isset($attributes['titleColor']) ? $attributes['titleColor'] : 'white';
$titleTransform = isset($attributes['titleTransform']) ? $attributes['titleTransform'] : 'uppercase';
$titleTracking = isset($attributes['titleTracking']) ? $attributes['titleTracking'] : 'tight';
$subtitleSize = isset($attributes['subtitleSize']) ? $attributes['subtitleSize'] : 'xl';
$subtitleWeight = isset($attributes['subtitleWeight']) ? $attributes['subtitleWeight'] : 'normal';
$subtitleColor = isset($attributes['subtitleColor']) ? $attributes['subtitleColor'] : 'base-light';
$subtitleTransform = isset($attributes['subtitleTransform']) ? $attributes['subtitleTransform'] : 'none';
$subtitleTracking = isset($attributes['subtitleTracking']) ? $attributes['subtitleTracking'] : 'normal';

// Map border radius values to CSS variables
$radiusMap = array(
    'none' => '0',
    'xs' => 'var(--radius-xs)',
    'sm' => 'var(--radius-sm)',
    'md' => 'var(--radius-md)',
    'lg' => 'var(--radius-lg)',
    'xl' => 'var(--radius-xl)',
    '2xl' => 'var(--radius-2xl)',
    '3xl' => 'var(--radius-3xl)',
    '4xl' => 'var(--radius-4xl)',
    'full' => 'var(--radius-full)'
);

$radiusValue = isset($radiusMap[$borderRadius]) ? $radiusMap[$borderRadius] : 'var(--radius-lg)';

// Map font sizes to CSS variables
$sizeMap = array(
    'xs' => 'var(--font-size-xs)',
    'sm' => 'var(--font-size-sm)',
    'base' => 'var(--font-size-base)',
    'lg' => 'var(--font-size-lg)',
    'xl' => 'var(--font-size-xl)',
    '2xl' => 'var(--font-size-2xl)',
    '3xl' => 'var(--font-size-3xl)',
    '4xl' => 'var(--font-size-4xl)',
    '5xl' => 'var(--font-size-5xl)'
);

// Map font weights to CSS variables
$weightMap = array(
    'normal' => 'var(--font-weight-normal)',
    'medium' => 'var(--font-weight-medium)',
    'semibold' => 'var(--font-weight-semibold)',
    'bold' => 'var(--font-weight-bold)'
);

// Map text transforms to CSS variables
$transformMap = array(
    'none' => 'none',
    'uppercase' => 'uppercase',
    'lowercase' => 'lowercase',
    'capitalize' => 'capitalize'
);

// Map tracking values to CSS variables
$trackingMap = array(
    'tighter' => 'var(--letter-spacing-tighter)',
    'tight' => 'var(--letter-spacing-tight)',
    'normal' => 'var(--letter-spacing-normal)',
    'wide' => 'var(--letter-spacing-wide)',
    'wider' => 'var(--letter-spacing-wider)',
    'widest' => 'var(--letter-spacing-widest)'
);

// Map spacing values to CSS variables
$spacingMap = array(
    '0' => 'var(--spacing-0)',
    '1' => 'var(--spacing-1)',
    '2' => 'var(--spacing-2)',
    '3' => 'var(--spacing-3)',
    '4' => 'var(--spacing-4)',
    '5' => 'var(--spacing-5)',
    '6' => 'var(--spacing-6)',
    '8' => 'var(--spacing-8)',
    '10' => 'var(--spacing-10)',
    '12' => 'var(--spacing-12)',
    '16' => 'var(--spacing-16)'
);

// Map colors to CSS variables
$colorMap = array(
    'white' => 'var(--color-white)',
    'black' => 'var(--color-black)',
    'primary' => 'var(--color-primary)',
    'primary-light' => 'var(--color-primary-light)',
    'primary-dark' => 'var(--color-primary-dark)',
    'secondary' => 'var(--color-secondary)',
    'secondary-light' => 'var(--color-secondary-light)',
    'secondary-dark' => 'var(--color-secondary-dark)',
    'emphasis' => 'var(--color-emphasis)',
    'emphasis-light' => 'var(--color-emphasis-light)',
    'emphasis-dark' => 'var(--color-emphasis-dark)',
    'neutral-lightest' => 'var(--color-neutral-lightest)',
    'neutral-light' => 'var(--color-neutral-light)',
    'neutral' => 'var(--color-neutral)',
    'neutral-dark' => 'var(--color-neutral-dark)',
    'base-lightest' => 'var(--color-base-lightest)',
    'base-light' => 'var(--color-base-light)',
    'base' => 'var(--color-base)',
    'base-dark' => 'var(--color-base-dark)',
    'base-darkest' => 'var(--color-base-darkest)'
);

// Get mapped values
$titleSizeValue = isset($sizeMap[$titleSize]) ? $sizeMap[$titleSize] : 'var(--font-size-4xl)';
$titleWeightValue = isset($weightMap[$titleWeight]) ? $weightMap[$titleWeight] : 'var(--font-weight-bold)';
$titleColorValue = isset($colorMap[$titleColor]) ? $colorMap[$titleColor] : 'var(--color-white)';
$titleTransformValue = isset($transformMap[$titleTransform]) ? $transformMap[$titleTransform] : 'uppercase';
$titleTrackingValue = isset($trackingMap[$titleTracking]) ? $trackingMap[$titleTracking] : 'var(--letter-spacing-tight)';
$subtitleSizeValue = isset($sizeMap[$subtitleSize]) ? $sizeMap[$subtitleSize] : 'var(--font-size-xl)';
$subtitleWeightValue = isset($weightMap[$subtitleWeight]) ? $weightMap[$subtitleWeight] : 'var(--font-weight-normal)';
$subtitleColorValue = isset($colorMap[$subtitleColor]) ? $colorMap[$subtitleColor] : 'var(--color-base-light)';
$subtitleTransformValue = isset($transformMap[$subtitleTransform]) ? $transformMap[$subtitleTransform] : 'none';
$subtitleTrackingValue = isset($trackingMap[$subtitleTracking]) ? $trackingMap[$subtitleTracking] : 'var(--letter-spacing-normal)';
$titleSpacingValue = isset($spacingMap[$titleSpacing]) ? $spacingMap[$titleSpacing] : 'var(--spacing-4)';

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => 'page-header-block',
    'style' => sprintf(
        'background-image: url(%s); min-height: %s; --block-radius: %s; background-position-y: %s;',
        esc_url($backgroundImage),
        esc_attr($minHeight),
        esc_attr($radiusValue),
        esc_attr($backgroundPositionY)
    )
));
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="page-header-overlay" style="opacity: <?php echo esc_attr($overlayOpacity); ?>"></div>
    <div class="page-header-content">
        <div class="page-header-inner">
            <h1 class="page-header-title" style="font-size: <?php echo esc_attr($titleSizeValue); ?> !important; font-weight: <?php echo esc_attr($titleWeightValue); ?> !important; color: <?php echo esc_attr($titleColorValue); ?> !important; text-transform: <?php echo esc_attr($titleTransformValue); ?> !important; letter-spacing: <?php echo esc_attr($titleTrackingValue); ?> !important; margin-bottom: <?php echo esc_attr($titleSpacingValue); ?> !important;"><?php echo esc_html($title); ?></h1>
            <?php if (!empty($subtitle)) : ?>
                <p class="page-header-subtitle" style="font-size: <?php echo esc_attr($subtitleSizeValue); ?> !important; font-weight: <?php echo esc_attr($subtitleWeightValue); ?> !important; color: <?php echo esc_attr($subtitleColorValue); ?> !important; text-transform: <?php echo esc_attr($subtitleTransformValue); ?> !important; letter-spacing: <?php echo esc_attr($subtitleTrackingValue); ?> !important;"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
