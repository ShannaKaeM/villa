<?php
/**
 * Business Card Template
 */

// Get business meta
$business_type = get_the_terms(get_the_ID(), 'business_type');
$business_location = get_post_meta(get_the_ID(), 'business_location', true);
$business_phone = get_post_meta(get_the_ID(), 'business_phone', true);
$business_hours = get_post_meta(get_the_ID(), 'business_hours', true);
$business_rating = get_post_meta(get_the_ID(), 'business_rating', true);
?>

<article class="card card--business">
    <?php if (has_post_thumbnail()) : ?>
        <div class="card__image">
            <?php if ($business_type && !is_wp_error($business_type)) : ?>
                <span class="badge badge--secondary card__category">
                    <?php echo esc_html($business_type[0]->name); ?>
                </span>
            <?php endif; ?>
            
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="card__content">
        <h3 class="card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if ($business_rating) : ?>
            <div class="card__rating">
                <?php 
                $full_stars = floor($business_rating);
                $half_star = ($business_rating - $full_stars) >= 0.5;
                for ($i = 0; $i < $full_stars; $i++) {
                    echo '<span class="icon icon--xs">⭐</span>';
                }
                if ($half_star) {
                    echo '<span class="icon icon--xs">⭐</span>';
                }
                ?>
                <span class="card__rating-value"><?php echo number_format($business_rating, 1); ?></span>
            </div>
        <?php endif; ?>
        
        <?php if (has_excerpt()) : ?>
            <p class="card__description"><?php echo get_the_excerpt(); ?></p>
        <?php else : ?>
            <p class="card__description"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        <?php endif; ?>
        
        <div class="card__info">
            <?php if ($business_location) : ?>
                <div class="card__info-item">
                    <span class="icon icon--xs">📍</span>
                    <?php echo esc_html($business_location); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($business_phone) : ?>
                <div class="card__info-item">
                    <span class="icon icon--xs">📞</span>
                    <a href="tel:<?php echo esc_attr($business_phone); ?>"><?php echo esc_html($business_phone); ?></a>
                </div>
            <?php endif; ?>
            
            <?php if ($business_hours) : ?>
                <div class="card__info-item">
                    <span class="icon icon--xs">🕐</span>
                    <?php echo esc_html($business_hours); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card__footer">
        <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm">
            View Details
        </a>
        <?php if ($business_phone) : ?>
            <a href="tel:<?php echo esc_attr($business_phone); ?>" class="btn btn--outline btn--sm">
                Call Now
            </a>
        <?php endif; ?>
    </div>
</article>
