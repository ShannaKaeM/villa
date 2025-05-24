<?php
/**
 * Filters partial template
 */

// Get post type from attributes or default to property
$post_type = isset($attributes['postType']) ? $attributes['postType'] : 'property';

// Also check if it's passed as a variable (for AJAX context)
if (!isset($attributes) && isset($post_type_param)) {
    $post_type = $post_type_param;
}
?>

<div class="m-filter-sidebar">
    <h3 class="m-filter-sidebar__title">
        <?php 
        if ($post_type === 'business') {
            _e('Filter Businesses', 'miblocks');
        } elseif ($post_type === 'property') {
            _e('Filter Properties', 'miblocks');
        } elseif ($post_type === 'profile') {
            _e('Filter Profiles', 'miblocks');
        } elseif ($post_type === 'article') {
            _e('Filter Articles', 'miblocks');
        } else {
            _e('Filter Results', 'miblocks');
        }
        ?>
    </h3>
    
    <?php if ($post_type === 'business') : ?>
        <!-- Business Type Filter -->
        <?php 
        $business_types = get_terms(array(
            'taxonomy' => 'business_type',
            'hide_empty' => true
        ));
        
        if (!empty($business_types) && !is_wp_error($business_types)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Business Type', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($business_types as $type) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="business_type" 
                                   data-term="<?php echo esc_attr($type->term_id); ?>">
                            <span class="m-filter-checkbox__label"><?php echo esc_html($type->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $type->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Location Filter for Businesses -->
        <?php 
        $locations = get_terms(array(
            'taxonomy' => 'location',
            'hide_empty' => true
        ));
        
        if (!empty($locations) && !is_wp_error($locations)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Location', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($locations as $location) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="location" 
                                   data-term="<?php echo esc_attr($location->term_id); ?>">
                            <span class="m-filter-checkbox__icon">📍</span>
                            <span class="m-filter-checkbox__label"><?php echo esc_html($location->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $location->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
    <?php elseif ($post_type === 'property') : ?>
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
        
    <?php elseif ($post_type === 'profile') : ?>
        <!-- Profile Role Filter -->
        <?php 
        $roles = get_terms(array(
            'taxonomy' => 'profile_role',
            'hide_empty' => true
        ));
        
        if (!empty($roles) && !is_wp_error($roles)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Role', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($roles as $role) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="profile_role" 
                                   data-term="<?php echo esc_attr($role->term_id); ?>">
                            <span class="m-filter-checkbox__label"><?php echo esc_html($role->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $role->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
    <?php elseif ($post_type === 'article') : ?>
        <!-- Category Filter for Articles -->
        <?php 
        $categories = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => true
        ));
        
        if (!empty($categories) && !is_wp_error($categories)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Categories', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($categories as $category) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="category" 
                                   data-term="<?php echo esc_attr($category->term_id); ?>">
                            <span class="m-filter-checkbox__label"><?php echo esc_html($category->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $category->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Article Type Filter -->
        <?php 
        $article_types = get_terms(array(
            'taxonomy' => 'article_type', // Assuming 'article_type' taxonomy
            'hide_empty' => true
        ));
        
        if (!empty($article_types) && !is_wp_error($article_types)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Article Type', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($article_types as $type) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="article_type" 
                                   data-term="<?php echo esc_attr($type->term_id); ?>">
                            <span class="m-filter-checkbox__label"><?php echo esc_html($type->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $type->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Location Filter for Articles -->
        <?php 
        $locations = get_terms(array(
            'taxonomy' => 'location', // Assuming 'location' taxonomy
            'hide_empty' => true
        ));
        
        if (!empty($locations) && !is_wp_error($locations)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Location', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($locations as $location) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="location" 
                                   data-term="<?php echo esc_attr($location->term_id); ?>">
                            <span class="m-filter-checkbox__icon">📍</span>
                            <span class="m-filter-checkbox__label"><?php echo esc_html($location->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $location->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Tag Filter for Articles -->
        <?php 
        $tags = get_terms(array(
            'taxonomy' => 'post_tag',
            'hide_empty' => true,
            'number' => 20 // Limit to top 20 tags
        ));
        
        if (!empty($tags) && !is_wp_error($tags)) : ?>
            <div class="m-filter-section">
                <h4 class="m-filter-section__title"><?php _e('Tags', 'miblocks'); ?></h4>
                <div class="m-filter-group">
                    <?php foreach ($tags as $tag) : ?>
                        <label class="m-filter-checkbox">
                            <input type="checkbox" 
                                   class="m-filter-checkbox__input" 
                                   data-taxonomy="post_tag" 
                                   data-term="<?php echo esc_attr($tag->term_id); ?>">
                            <span class="m-filter-checkbox__label"><?php echo esc_html($tag->name); ?></span>
                            <span class="m-filter-checkbox__count"><?php echo $tag->count; ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
