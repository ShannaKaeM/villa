<?php
/**
 * Profile Card Template
 */

// Get profile meta
$profile_role = get_post_meta(get_the_ID(), 'profile_role', true);
$profile_location = get_post_meta(get_the_ID(), 'profile_location', true);
$profile_email = get_post_meta(get_the_ID(), 'profile_email', true);
$profile_phone = get_post_meta(get_the_ID(), 'profile_phone', true);
$profile_social = get_post_meta(get_the_ID(), 'profile_social', true);
?>

<article class="m-card m-card--profile">
    <?php if (has_post_thumbnail()) : ?>
        <div class="m-card__image m-card__image--profile">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium'); ?>
            </a>
        </div>
    <?php else : ?>
        <div class="m-card__image m-card__image--profile">
            <div class="m-card__avatar-placeholder">
                <span class="m-icon m-icon--lg">👤</span>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="m-card__content m-card__content--centered">
        <h3 class="m-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if ($profile_role) : ?>
            <p class="m-card__role"><?php echo esc_html($profile_role); ?></p>
        <?php endif; ?>
        
        <?php if ($profile_location) : ?>
            <div class="m-card__location">
                <span class="m-icon m-icon--xs">📍</span>
                <?php echo esc_html($profile_location); ?>
            </div>
        <?php endif; ?>
        
        <?php if (has_excerpt()) : ?>
            <p class="m-card__description"><?php echo get_the_excerpt(); ?></p>
        <?php else : ?>
            <p class="m-card__description"><?php echo wp_trim_words(get_the_content(), 15); ?></p>
        <?php endif; ?>
        
        <div class="m-card__contact">
            <?php if ($profile_email) : ?>
                <a href="mailto:<?php echo esc_attr($profile_email); ?>" class="m-card__contact-link" title="Email">
                    <span class="m-icon m-icon--sm">✉️</span>
                </a>
            <?php endif; ?>
            
            <?php if ($profile_phone) : ?>
                <a href="tel:<?php echo esc_attr($profile_phone); ?>" class="m-card__contact-link" title="Phone">
                    <span class="m-icon m-icon--sm">📞</span>
                </a>
            <?php endif; ?>
            
            <?php if ($profile_social && is_array($profile_social)) : ?>
                <?php foreach ($profile_social as $platform => $url) : ?>
                    <a href="<?php echo esc_url($url); ?>" class="m-card__contact-link" target="_blank" rel="noopener" title="<?php echo ucfirst($platform); ?>">
                        <span class="m-icon m-icon--sm">🔗</span>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="m-card__footer">
        <a href="<?php the_permalink(); ?>" class="m-btn m-btn--primary m-btn--sm m-btn--block">
            View Profile
        </a>
    </div>
</article>
