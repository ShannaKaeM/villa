<?php
/**
 * Property Card Template
 */

// Get property meta
$price = get_post_meta(get_the_ID(), 'property_price', true);
$beds = get_post_meta(get_the_ID(), 'property_bedrooms', true);
$baths = get_post_meta(get_the_ID(), 'property_bathrooms', true);
$guests = get_post_meta(get_the_ID(), 'property_max_guests', true);

// Get taxonomies
$property_type = get_the_terms(get_the_ID(), 'property_type');
$location = get_the_terms(get_the_ID(), 'location');
$amenities = get_the_terms(get_the_ID(), 'amenity');
?>

<article class="card card--property">
    <?php if (has_post_thumbnail()) : ?>
        <div class="card__image">
            <?php if ($price) : ?>
                <span class="badge badge--primary card__price">
                    $<?php echo number_format($price); ?>/night
                </span>
            <?php endif; ?>
            
            <?php if ($property_type && !is_wp_error($property_type)) : ?>
                <span class="badge badge--secondary card__type">
                    <?php echo esc_html($property_type[0]->name); ?>
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
        
        <?php if ($location && !is_wp_error($location)) : ?>
            <div class="card__location">
                <span class="icon icon--sm">📍</span>
                <?php echo esc_html($location[0]->name); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($beds || $baths || $guests) : ?>
            <div class="card__details">
                <?php if ($beds) : ?>
                    <span class="card__detail">
                        <span class="icon icon--xs">🛏️</span>
                        <?php echo $beds; ?> Beds
                    </span>
                <?php endif; ?>
                
                <?php if ($baths) : ?>
                    <span class="card__detail">
                        <span class="icon icon--xs">🛁</span>
                        <?php echo $baths; ?> Baths
                    </span>
                <?php endif; ?>
                
                <?php if ($guests) : ?>
                    <span class="card__detail">
                        <span class="icon icon--xs">👥</span>
                        <?php echo $guests; ?> Guests
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($amenities && !is_wp_error($amenities) && count($amenities) > 0) : ?>
            <div class="card__amenities">
                <?php 
                $max_amenities = 3;
                $shown = 0;
                foreach ($amenities as $amenity) : 
                    if ($shown >= $max_amenities) break;
                    $shown++;
                ?>
                    <span class="badge badge--outline badge--sm">
                        <?php echo esc_html($amenity->name); ?>
                    </span>
                <?php endforeach; ?>
                <?php if (count($amenities) > $max_amenities) : ?>
                    <span class="badge badge--outline badge--sm">
                        +<?php echo count($amenities) - $max_amenities; ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="card__footer">
        <a href="<?php the_permalink(); ?>" class="btn btn--primary btn--sm btn--block">
            View Details
        </a>
    </div>
</article>
