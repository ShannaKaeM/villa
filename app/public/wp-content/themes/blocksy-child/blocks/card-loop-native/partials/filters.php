<?php
/**
 * Filters partial for Card Loop block
 */

// Get the post type to determine which taxonomies to show
$post_type = isset($post_type) ? $post_type : 'property';

// Get taxonomies for the post type
$taxonomies = get_object_taxonomies($post_type, 'objects');
?>

<?php
foreach ($taxonomies as $taxonomy) :
    if (!$taxonomy->public || !$taxonomy->show_ui) continue;
    
    $terms = get_terms([
        'taxonomy' => $taxonomy->name,
        'hide_empty' => true,
    ]);
    
    if (!empty($terms) && !is_wp_error($terms)) :
?>
    <div class="filter-group">
        <h4 class="filter-group__title"><?php echo esc_html($taxonomy->labels->name); ?></h4>
        <div class="filter-group__grid">
            <?php foreach ($terms as $term) : 
                // Get term meta for icon if using Carbon Fields
                $icon = '';
                if (function_exists('carbon_get_term_meta')) {
                    $icon = carbon_get_term_meta($term->term_id, 'icon');
                }
            ?>
                <label class="filter-checkbox">
                    <input type="checkbox" 
                           class="filter-checkbox__input" 
                           data-taxonomy="<?php echo esc_attr($taxonomy->name); ?>"
                           data-term="<?php echo esc_attr($term->term_id); ?>"
                           value="<?php echo esc_attr($term->term_id); ?>">
                    <span class="filter-checkbox__content">
                        <?php if ($icon) : ?>
                            <span class="filter-checkbox__icon"><?php echo $icon; ?></span>
                        <?php endif; ?>
                        <span class="filter-checkbox__label">
                            <?php echo esc_html($term->name); ?>
                            <span class="filter-checkbox__count"><?php echo $term->count; ?></span>
                        </span>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
<?php 
    endif;
endforeach; 
?>

<!-- Range Filters for Properties -->
<?php if ($post_type === 'property') : ?>
    <div class="filter-group">
        <h4 class="filter-group__title">Bedrooms</h4>
        <div class="filter-range">
            <input type="range" 
                   class="filter-range__input" 
                   data-range="bedrooms"
                   min="0" 
                   max="10" 
                   value="0" 
                   step="1">
            <div class="filter-range__value">
                Minimum: <span>0</span>
            </div>
        </div>
    </div>
    
    <div class="filter-group">
        <h4 class="filter-group__title">Bathrooms</h4>
        <div class="filter-range">
            <input type="range" 
                   class="filter-range__input" 
                   data-range="bathrooms"
                   min="0" 
                   max="10" 
                   value="0" 
                   step="1">
            <div class="filter-range__value">
                Minimum: <span>0</span>
            </div>
        </div>
    </div>
<?php endif; ?>
