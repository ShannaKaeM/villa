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

// Get wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes(array(
    'class' => 'page-header-block',
    'style' => sprintf(
        'background-image: url(%s); min-height: %s;',
        esc_url($backgroundImage),
        esc_attr($minHeight)
    )
));
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="page-header-overlay" style="opacity: <?php echo esc_attr($overlayOpacity); ?>"></div>
    <div class="page-header-content">
        <div class="page-header-inner">
            <h1 class="page-header-title"><?php echo esc_html($title); ?></h1>
            <?php if (!empty($subtitle)) : ?>
                <p class="page-header-subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>
