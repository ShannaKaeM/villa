<?php
/**
 * Card Loop Block - Server-side rendering
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The block content.
 * @param WP_Block $block      The block instance.
 */

// Extract attributes with defaults - WordPress already provides defaults from block.json
$post_type = isset($attributes['postType']) ? $attributes['postType'] : 'property';
$posts_per_page = isset($attributes['postsPerPage']) ? $attributes['postsPerPage'] : 12;
$columns = isset($attributes['columns']) ? $attributes['columns'] : 3;
$show_filter = isset($attributes['showFilter']) ? $attributes['showFilter'] : true;
$filter_position = isset($attributes['filterPosition']) ? $attributes['filterPosition'] : 'left';
$card_style = isset($attributes['cardStyle']) ? $attributes['cardStyle'] : 'default';
$show_pagination = isset($attributes['showPagination']) ? $attributes['showPagination'] : false;
$selected_categories = isset($attributes['selectedCategories']) ? $attributes['selectedCategories'] : [];
$selected_tags = isset($attributes['selectedTags']) ? $attributes['selectedTags'] : [];

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
$wrapper_classes = ['listing-section'];
if ($show_filter) {
    $wrapper_classes[] = 'has-filter';
    $wrapper_classes[] = 'filter-position-' . $filter_position;
}

// Check if className is set in the block attributes
if (isset($attributes['className'])) {
    $wrapper_classes[] = $attributes['className'];
}

// Add data attributes for JavaScript
$wrapper_attrs = get_block_wrapper_attributes([
    'class' => implode(' ', $wrapper_classes),
    'data-post-type' => $post_type,
    'data-posts-per-page' => $posts_per_page,
    'data-card-style' => $card_style,
    'data-columns' => $columns
]);

// Start output
ob_start();
?>

<div <?php echo $wrapper_attrs; ?>>
    <?php if ($show_filter && $filter_position === 'left') : ?>
        <aside class="listing-section__sidebar">
            <?php include __DIR__ . '/partials/filters.php'; ?>
        </aside>
    <?php endif; ?>
    
    <div class="listing-section__main">
        <?php if ($show_filter && $filter_position === 'top') : ?>
            <div class="listing-section__filters-top">
                <?php include __DIR__ . '/partials/filters-horizontal.php'; ?>
            </div>
        <?php endif; ?>
        
        <div class="listing-section__header">
            <div class="view-controls">
                <div class="view-controls__count">
                    <?php printf(
                        _n('Found <strong>%s</strong> result', 'Found <strong>%s</strong> results', $query->found_posts, 'miblocks'),
                        $query->found_posts
                    ); ?>
                </div>
                <div class="view-controls__sort">
                    <select class="select select--sm">
                        <option value="date"><?php _e('Newest First', 'miblocks'); ?></option>
                        <option value="price_asc"><?php _e('Price: Low to High', 'miblocks'); ?></option>
                        <option value="price_desc"><?php _e('Price: High to Low', 'miblocks'); ?></option>
                        <option value="title"><?php _e('Name: A-Z', 'miblocks'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="cards-container">
            <?php if ($query->have_posts()) : ?>
                <div class="view-grid view-grid--fixed-<?php echo esc_attr($columns); ?>">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <?php 
                        // Include the appropriate card template based on post type
                        $card_template = __DIR__ . '/partials/card-' . $post_type . '.php';
                        if (file_exists($card_template)) {
                            include $card_template;
                        } else {
                            // Default card template
                            include __DIR__ . '/partials/card-default.php';
                        }
                        ?>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <div class="empty-state__icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.35-4.35"/>
                        </svg>
                    </div>
                    <h3 class="empty-state__title"><?php _e('No results found', 'miblocks'); ?></h3>
                    <p class="empty-state__description"><?php _e('Try adjusting your filters or search criteria.', 'miblocks'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($show_pagination && $query->max_num_pages > 1) : ?>
            <div class="listing-section__pagination">
                <?php
                echo paginate_links(array(
                    'total' => $query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '←',
                    'next_text' => '→',
                    'type' => 'list'
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Return output
echo ob_get_clean();
