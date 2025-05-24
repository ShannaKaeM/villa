<?php
/**
 * Property Card Template
 */

// Get property meta
$price = get_post_meta(get_the_ID(), 'property_price', true);
$beds = get_post_meta(get_the_ID(), 'property_bedrooms', true);
$baths = get_post_meta(get_the_ID(), 'property_bathrooms', true);
$guests = get_post_meta(get_the_ID(), 'property_guests', true);

// Get taxonomies
$property_type = get_the_terms(get_the_ID(), 'property_type');
$location = get_the_terms(get_the_ID(), 'location');
$amenities = get_the_terms(get_the_ID(), 'amenity');
?>

<article class="m-card m-card--property">
    <?php if (has_post_thumbnail()) : ?>
        <div class="m-card__image">
            <?php if ($price) : ?>
                <span class="m-badge m-badge--primary m-card__price">
                    $<?php echo number_format($price); ?>/night
                </span>
            <?php endif; ?>
            
            <?php if ($property_type && !is_wp_error($property_type)) : ?>
                <span class="m-badge m-badge--secondary m-card__type">
                    <?php echo esc_html($property_type[0]->name); ?>
                </span>
            <?php endif; ?>
            
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="m-card__content">
        <h3 class="m-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if ($location && !is_wp_error($location)) : ?>
            <div class="m-card__location">
                <span class="m-icon m-icon--sm">📍</span>
                <?php echo esc_html($location[0]->name); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($beds || $baths || $guests) : ?>
            <div class="m-card__details">
                <?php if ($beds) : ?>
                    <span class="m-card__detail">
                        <span class="m-icon m-icon--xs">🛏️</span>
                        <?php echo $beds; ?> Beds
                    </span>
                <?php endif; ?>
                
                <?php if ($baths) : ?>
                    <span class="m-card__detail">
                        <span class="m-icon m-icon--xs">🛁</span>
                        <?php echo $baths; ?> Baths
                    </span>
                <?php endif; ?>
                
                <?php if ($guests) : ?>
                    <span class="m-card__detail">
                        <span class="m-icon m-icon--xs">👥</span>
                        <?php echo $guests; ?> Guests
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($amenities && !is_wp_error($amenities) && count($amenities) > 0) : ?>
            <div class="m-card__amenities">
                <?php 
                $max_amenities = 3;
                $shown = 0;
                foreach ($amenities as $amenity) : 
                    if ($shown >= $max_amenities) break;
                    $shown++;
                ?>
                    <span class="m-badge m-badge--outline m-badge--sm">
                        <?php echo esc_html($amenity->name); ?>
                    </span>
                <?php endforeach; ?>
                <?php if (count($amenities) > $max_amenities) : ?>
                    <span class="m-badge m-badge--outline m-badge--sm">
                        +<?php echo count($amenities) - $max_amenities; ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="m-card__footer">
        <a href="<?php the_permalink(); ?>" class="m-btn m-btn--primary m-btn--sm m-btn--block">
            View Details
        </a>
    </div>
</article>
