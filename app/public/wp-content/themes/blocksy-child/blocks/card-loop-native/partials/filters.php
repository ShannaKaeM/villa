<?php
/**
 * Filters partial template
 */

// Get taxonomies for the post type
$post_type = isset($attributes['postType']) ? $attributes['postType'] : 'property';
?>

<div class="m-filter-sidebar">
    <h3 class="m-filter-sidebar__title"><?php _e('Filter Properties', 'miblocks'); ?></h3>
    
    <!-- Property Type Filter -->
    <?php 
    $property_types = get_terms(array(
        'taxonomy' => 'property_type',
        'hide_empty' => true
    ));
    
    if (!empty($property_types) && !is_wp_error($property_types)) : ?>
        <div class="m-filter-section">
            <h4 class="m-filter-section__title"><?php _e('Property Type', 'miblocks'); ?></h4>
            <div class="m-filter-group">
                <?php foreach ($property_types as $type) : 
                    $icon = '';
                    if (function_exists('carbon_get_term_meta')) {
                        $icon = carbon_get_term_meta($type->term_id, 'icon');
                    }
                    
                    // Fallback emoji icons for property types
                    if (!$icon) {
                        $fallback_icons = [
                            'condo' => '🏢',
                            'cottage' => '🏡',
                            'house' => '🏠',
                        ];
                        $icon = isset($fallback_icons[$type->slug]) ? $fallback_icons[$type->slug] : '🏠';
                    }
                ?>
                    <label class="m-filter-checkbox">
                        <input type="checkbox" 
                               class="m-filter-checkbox__input" 
                               data-taxonomy="property_type" 
                               data-term="<?php echo esc_attr($type->term_id); ?>">
                        <span class="m-filter-checkbox__icon">
                            <?php if (filter_var($icon, FILTER_VALIDATE_URL)) : ?>
                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($type->name); ?>">
                            <?php else : ?>
                                <?php echo $icon; ?>
                            <?php endif; ?>
                        </span>
                        <span class="m-filter-checkbox__label"><?php echo esc_html($type->name); ?></span>
                        <span class="m-filter-checkbox__count"><?php echo $type->count; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Range Filters for Properties -->
    <?php if ($post_type === 'property') : ?>
        <div class="m-filter-section">
            <h4 class="m-filter-section__title"><?php _e('Bedrooms', 'miblocks'); ?></h4>
            <div class="m-filter-range">
                <div class="m-filter-range__label">
                    <span><?php _e('Minimum Bedrooms', 'miblocks'); ?></span>
                    <span class="m-filter-range__value"><span>0</span>+</span>
                </div>
                <input type="range" 
                       class="m-filter-range__input" 
                       data-range="bedrooms"
                       min="0" 
                       max="10" 
                       value="0" 
                       step="1">
            </div>
        </div>
        
        <div class="m-filter-section">
            <h4 class="m-filter-section__title"><?php _e('Bathrooms', 'miblocks'); ?></h4>
            <div class="m-filter-range">
                <div class="m-filter-range__label">
                    <span><?php _e('Minimum Bathrooms', 'miblocks'); ?></span>
                    <span class="m-filter-range__value"><span>0</span>+</span>
                </div>
                <input type="range" 
                       class="m-filter-range__input" 
                       data-range="bathrooms"
                       min="0" 
                       max="10" 
                       value="0" 
                       step="1">
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Amenities Filter -->
    <?php 
    $amenities = get_terms(array(
        'taxonomy' => 'amenity',
        'hide_empty' => true
    ));
    
    if (!empty($amenities) && !is_wp_error($amenities)) : ?>
        <div class="m-filter-section">
            <h4 class="m-filter-section__title"><?php _e('Amenities', 'miblocks'); ?></h4>
            <div class="m-filter-group">
                <?php foreach ($amenities as $amenity) : 
                    $icon = '';
                    if (function_exists('carbon_get_term_meta')) {
                        $icon = carbon_get_term_meta($amenity->term_id, 'icon');
                    }
                    
                    // Fallback emoji icons for amenities
                    if (!$icon) {
                        $fallback_icons = [
                            'hot-tub' => '🛁',
                            'ocean-view' => '🌊',
                            'pet-friendly' => '🐶',
                            'pool' => '🏊',
                            'wifi' => '📶',
                        ];
                        $icon = isset($fallback_icons[$amenity->slug]) ? $fallback_icons[$amenity->slug] : '✨';
                    }
                ?>
                    <label class="m-filter-checkbox">
                        <input type="checkbox" 
                               class="m-filter-checkbox__input" 
                               data-taxonomy="amenity" 
                               data-term="<?php echo esc_attr($amenity->term_id); ?>">
                        <span class="m-filter-checkbox__icon">
                            <?php if (filter_var($icon, FILTER_VALIDATE_URL)) : ?>
                                <img src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($amenity->name); ?>">
                            <?php else : ?>
                                <?php echo $icon; ?>
                            <?php endif; ?>
                        </span>
                        <span class="m-filter-checkbox__label"><?php echo esc_html($amenity->name); ?></span>
                        <span class="m-filter-checkbox__count"><?php echo $amenity->count; ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
