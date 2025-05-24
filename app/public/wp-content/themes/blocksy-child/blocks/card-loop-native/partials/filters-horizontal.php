<?php
/**
 * Horizontal filters partial template
 */

// Get post type from attributes or default to property
$post_type = isset($attributes['postType']) ? $attributes['postType'] : 'property';

// Also check if it's passed as a variable (for AJAX context)
if (!isset($attributes) && isset($post_type_param)) {
    $post_type = $post_type_param;
}
?>

<div class="m-filter-bar">
    <div class="m-filter-bar__inner">
        <?php if ($post_type === 'business') : ?>
            <!-- Business Type Filter -->
            <?php 
            $business_types = get_terms(array(
                'taxonomy' => 'business_type',
                'hide_empty' => true
            ));
            
            if (!empty($business_types) && !is_wp_error($business_types)) : ?>
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Business Type', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
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
            
            <!-- Location Filter -->
            <?php 
            $locations = get_terms(array(
                'taxonomy' => 'location',
                'hide_empty' => true
            ));
            
            if (!empty($locations) && !is_wp_error($locations)) : ?>
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Location', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
                        <?php foreach ($locations as $location) : ?>
                            <label class="m-filter-checkbox">
                                <input type="checkbox" 
                                       class="m-filter-checkbox__input" 
                                       data-taxonomy="location" 
                                       data-term="<?php echo esc_attr($location->term_id); ?>">
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
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Property Type', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
                        <?php foreach ($property_types as $type) : ?>
                            <label class="m-filter-checkbox">
                                <input type="checkbox" 
                                       class="m-filter-checkbox__input" 
                                       data-taxonomy="property_type" 
                                       data-term="<?php echo esc_attr($type->term_id); ?>">
                                <span class="m-filter-checkbox__label"><?php echo esc_html($type->name); ?></span>
                                <span class="m-filter-checkbox__count"><?php echo $type->count; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Range Filters for Properties -->
            <div class="m-filter-dropdown">
                <button class="m-filter-dropdown__toggle">
                    <?php _e('Bedrooms', 'miblocks'); ?>
                    <span class="m-filter-dropdown__arrow">▼</span>
                </button>
                <div class="m-filter-dropdown__content">
                    <div class="m-filter-range">
                        <div class="m-filter-range__label">
                            <span><?php _e('Minimum', 'miblocks'); ?></span>
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
            </div>
            
            <div class="m-filter-dropdown">
                <button class="m-filter-dropdown__toggle">
                    <?php _e('Bathrooms', 'miblocks'); ?>
                    <span class="m-filter-dropdown__arrow">▼</span>
                </button>
                <div class="m-filter-dropdown__content">
                    <div class="m-filter-range">
                        <div class="m-filter-range__label">
                            <span><?php _e('Minimum', 'miblocks'); ?></span>
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
            </div>
            
            <!-- Amenities Filter -->
            <?php 
            $amenities = get_terms(array(
                'taxonomy' => 'amenity',
                'hide_empty' => true
            ));
            
            if (!empty($amenities) && !is_wp_error($amenities)) : ?>
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Amenities', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
                        <?php foreach ($amenities as $amenity) : ?>
                            <label class="m-filter-checkbox">
                                <input type="checkbox" 
                                       class="m-filter-checkbox__input" 
                                       data-taxonomy="amenity" 
                                       data-term="<?php echo esc_attr($amenity->term_id); ?>">
                                <span class="m-filter-checkbox__label"><?php echo esc_html($amenity->name); ?></span>
                                <span class="m-filter-checkbox__count"><?php echo $amenity->count; ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php elseif ($post_type === 'article') : ?>
            <!-- Category Filter -->
            <?php 
            $categories = get_terms(array(
                'taxonomy' => 'category',
                'hide_empty' => true
            ));
            
            if (!empty($categories) && !is_wp_error($categories)) : ?>
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Categories', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
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
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Article Type', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
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

            <!-- Location Filter -->
            <?php 
            $locations = get_terms(array(
                'taxonomy' => 'location', // Assuming 'location' taxonomy
                'hide_empty' => true
            ));
            
            if (!empty($locations) && !is_wp_error($locations)) : ?>
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Location', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
                        <?php foreach ($locations as $location) : ?>
                            <label class="m-filter-checkbox">
                                <input type="checkbox" 
                                       class="m-filter-checkbox__input" 
                                       data-taxonomy="location" 
                                       data-term="<?php echo esc_attr($location->term_id); ?>">
                                <span class="m-filter-checkbox__label"><?php echo esc_html($location->name); ?></span>
                                <span class="m-filter-checkbox__count"><?php echo $location->count; ?></span>
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
                <div class="m-filter-dropdown">
                    <button class="m-filter-dropdown__toggle">
                        <?php _e('Role', 'miblocks'); ?>
                        <span class="m-filter-dropdown__arrow">▼</span>
                    </button>
                    <div class="m-filter-dropdown__content">
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
        <?php endif; ?>
        
        <button class="m-btn m-btn--secondary m-btn--sm m-filter-clear">
            <?php _e('Clear Filters', 'miblocks'); ?>
        </button>
    </div>
</div>
