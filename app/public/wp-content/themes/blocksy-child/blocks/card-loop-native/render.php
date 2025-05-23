<?php
/**
 * Card Loop Block - Server-side rendering
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The block content.
 * @param WP_Block $block      The block instance.
 */

// Extract attributes with defaults
$attributes = $attributes ?? [];
$post_type = $attributes['postType'] ?? 'property';
$posts_per_page = $attributes['postsPerPage'] ?? 6;
$columns = $attributes['columns'] ?? 3;
$show_filter = $attributes['showFilter'] ?? true;
$filter_position = $attributes['filterPosition'] ?? 'left';
$card_style = $attributes['cardStyle'] ?? 'default';
$show_pagination = $attributes['showPagination'] ?? true;
$selected_categories = $attributes['selectedCategories'] ?? [];
$selected_tags = $attributes['selectedTags'] ?? [];

// Get current page for pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Build query arguments
$query_args = [
    'post_type' => $post_type,
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'post_status' => 'publish',
];

// Add taxonomy filters if selected
if (!empty($selected_categories)) {
    $query_args['category__in'] = $selected_categories;
}

if (!empty($selected_tags)) {
    $query_args['tag__in'] = $selected_tags;
}

// Execute query
$query = new WP_Query($query_args);

// Debug: Check if we're in the editor
$is_editor = defined('REST_REQUEST') && REST_REQUEST;

// Add wrapper classes
$wrapper_classes = ['mi-card-loop', 'wp-block-miblocks-card-loop', 'columns-' . $columns, 'card-style-' . $card_style];

// Check if className is set in the block attributes
if (isset($attributes['className'])) {
    $wrapper_classes[] = $attributes['className'];
}

// Add data attributes for JavaScript
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => implode(' ', $wrapper_classes),
    'data-post-type' => $post_type,
    'data-posts-per-page' => $posts_per_page,
    'data-card-style' => $card_style
]);

// Start output
ob_start();
?>

<div <?php echo $wrapper_attrs; ?>>
    <?php if ($query->have_posts()) : ?>
        <!-- View Controls -->
        <div class="view-controls">
            <div class="container container--xl">
                <div class="view-controls__count">
                    Showing <strong><?php echo $query->found_posts; ?></strong> results
                </div>
            </div>
        </div>
        
        <!-- Main Layout -->
        <div class="container container--xl">
            <div class="content-grid <?php echo $show_filter && $filter_position === 'left' ? 'content-grid--sidebar-left' : ''; ?>">
                <?php if ($show_filter && $filter_position === 'left') : ?>
                    <!-- Filter Sidebar (Left) -->
                    <aside class="sidebar filter-sidebar">
                        <div class="filter-header">
                            <h2 class="filter-header__title"><?php _e('Filter', 'miblocks'); ?></h2>
                        </div>
                        <?php include __DIR__ . '/partials/filters.php'; ?>
                    </aside>
                <?php endif; ?>
                
                <!-- Main Content Area -->
                <div class="content-grid__main">
                    <?php if ($show_filter && $filter_position === 'top') : ?>
                        <!-- Filter Bar (Top) -->
                        <div class="filter-bar filter-bar--horizontal">
                            <div class="filter-header">
                                <h2 class="filter-header__title"><?php _e('Filter', 'miblocks'); ?></h2>
                            </div>
                            <div class="filter-bar__content">
                                <?php include __DIR__ . '/partials/filters.php'; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Cards Grid -->
                    <div class="cards-container">
                        <div class="view-grid view-grid--fixed-<?php echo $columns; ?>">
                            <?php while ($query->have_posts()) : $query->the_post(); ?>
                                <article class="card card--<?php echo $card_style; ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="card__image">
                                            <?php 
                                            // Get property price if it's a property
                                            if ($post_type === 'property') : 
                                                $price = get_post_meta(get_the_ID(), 'property_price', true);
                                                if ($price) : ?>
                                                    <span class="card__price">$<?php echo number_format($price); ?>/night</span>
                                                <?php endif;
                                                
                                                // Get property type for badge
                                                $property_type = get_the_terms(get_the_ID(), 'property-type');
                                                if ($property_type && !is_wp_error($property_type)) : ?>
                                                    <span class="card__badge"><?php echo esc_html($property_type[0]->name); ?></span>
                                                <?php endif;
                                            endif; ?>
                                            
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', ['class' => 'card__img']); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="card__content">
                                        <h3 class="card__title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        
                                        <?php if (has_excerpt()) : ?>
                                            <p class="card__description"><?php echo get_the_excerpt(); ?></p>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        // Display property details if it's a property
                                        if ($post_type === 'property') : 
                                            $beds = get_post_meta(get_the_ID(), 'property_bedrooms', true);
                                            $baths = get_post_meta(get_the_ID(), 'property_bathrooms', true);
                                            $guests = get_post_meta(get_the_ID(), 'property_guests', true);
                                            
                                            if ($beds || $baths || $guests) : ?>
                                                <div class="card__details">
                                                    <?php if ($beds) : ?>
                                                        <span class="card__detail">
                                                            <svg class="card__detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                <path d="M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9-4 9 4M3 7h18M12 3v18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            <?php echo $beds; ?> Beds
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($baths) : ?>
                                                        <span class="card__detail">
                                                            <svg class="card__detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                <path d="M5 12V7a1 1 0 011-1h12a1 1 0 011 1v5M3 12h18M7 12v7a2 2 0 002 2h6a2 2 0 002-2v-7M12 12v7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            <?php echo $baths; ?> Baths
                                                        </span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($guests) : ?>
                                                        <span class="card__detail">
                                                            <svg class="card__detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 7a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            <?php echo $guests; ?> Guests
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif;
                                        endif; ?>
                                        
                                        <?php 
                                        // Display location for properties
                                        if ($post_type === 'property') : 
                                            $location = get_the_terms(get_the_ID(), 'location');
                                            if ($location && !is_wp_error($location)) : ?>
                                                <div class="card__meta">
                                                    <span class="card__location"><?php echo esc_html($location[0]->name); ?></span>
                                                </div>
                                            <?php endif;
                                        endif; ?>
                                        
                                        <div class="card__actions">
                                            <a href="<?php the_permalink(); ?>" class="btn btn--primary">
                                                <?php _e('View Details', 'miblocks'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    
                    <?php if ($show_pagination && $query->max_num_pages > 1) : ?>
                        <!-- Pagination -->
                        <div class="pagination">
                            <?php
                            echo paginate_links([
                                'total' => $query->max_num_pages,
                                'current' => $paged,
                                'prev_text' => __('← Previous', 'miblocks'),
                                'next_text' => __('Next →', 'miblocks'),
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else : ?>
        <!-- Empty State -->
        <div class="container container--xl">
            <div class="empty-state">
                <h3 class="empty-state__title"><?php _e('No items found', 'miblocks'); ?></h3>
                <p class="empty-state__description"><?php _e('Try adjusting your filters or search criteria.', 'miblocks'); ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <?php wp_reset_postdata(); ?>
</div>

<?php
// Return output
echo ob_get_clean();
