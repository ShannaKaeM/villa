<?php
/**
 * CTA Section Block Template
 *
 * @param array $attributes The block attributes.
 * @param string $content The block content.
 * @param WP_Block $block The block instance.
 */

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'cta-section',
    'style' => sprintf(
        '--cta-title-color: %s; --cta-title-size: %s; --cta-title-weight: %s; --cta-title-tracking: %s; ' .
        '--cta-description-color: %s; --cta-description-size: %s; --cta-description-weight: %s; --cta-description-tracking: %s; ' .
        '--cta-bg-color: %s; --cta-overlay-color: %s; --cta-overlay-opacity: %s; ' .
        '--cta-padding-top: %s; --cta-padding-right: %s; --cta-padding-bottom: %s; --cta-padding-left: %s;',
        esc_attr($attributes['titleColor']),
        esc_attr($attributes['titleSize']),
        esc_attr($attributes['titleWeight']),
        esc_attr($attributes['titleTracking']),
        esc_attr($attributes['descriptionColor']),
        esc_attr($attributes['descriptionSize']),
        esc_attr($attributes['descriptionWeight']),
        esc_attr($attributes['descriptionTracking']),
        esc_attr($attributes['backgroundColor']),
        esc_attr($attributes['overlayColor']),
        esc_attr($attributes['overlayOpacity'] / 100),
        esc_attr($attributes['padding']['top'] ?? '4rem'),
        esc_attr($attributes['padding']['right'] ?? '2rem'),
        esc_attr($attributes['padding']['bottom'] ?? '4rem'),
        esc_attr($attributes['padding']['left'] ?? '2rem')
    )
]);

$container_style = '';
if (!empty($attributes['backgroundImage'])) {
    $container_style = sprintf('background-image: url(%s);', esc_url($attributes['backgroundImage']));
}
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="cta-section__container" style="<?php echo esc_attr($container_style); ?>">
        <?php if (!empty($attributes['backgroundImage'])) : ?>
            <div class="cta-section__overlay"></div>
        <?php endif; ?>
        
        <div class="cta-section__content">
            <?php if (!empty($attributes['title'])) : ?>
                <h2 class="cta-section__title"><?php echo wp_kses_post($attributes['title']); ?></h2>
            <?php endif; ?>
            
            <?php if (!empty($attributes['description'])) : ?>
                <p class="cta-section__description"><?php echo wp_kses_post($attributes['description']); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($attributes['buttonText'])) : ?>
                <div class="cta-section__button-wrapper">
                    <a href="<?php echo esc_url($attributes['buttonUrl']); ?>" class="cta-section__button">
                        <?php echo esc_html($attributes['buttonText']); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
