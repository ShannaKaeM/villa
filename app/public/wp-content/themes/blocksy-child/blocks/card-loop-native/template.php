<?php
/**
 * Template for Card Loop Block - Native Version
 * 
 * This template uses the same semantic class names as the original
 * to maintain compatibility with the global CSS architecture.
 */

// Ensure variables are defined
$title = $title ?? '';
$post_type = $post_type ?? 'property';
$items = $items ?? [];
$filters = $filters ?? [];
$show_filters = $show_filters ?? true;
$columns = $columns ?? 3;
$card_style = $card_style ?? 'default';
?>

<div class="mi-card-loop" style="--view-grid-columns: <?php echo esc_attr($columns); ?>">
    <?php if (!empty($title)): ?>
        <h2 class="mi-card-loop__title"><?php echo esc_html($title); ?></h2>
    <?php endif; ?>

    <div class="mi-card-loop__container<?php echo $show_filters ? ' mi-card-loop__container--with-filters' : ''; ?>">
        <!-- Add a debug message to show current settings -->
        <div class="debug-info" style="display: none;">
            <p>Post Type: <?php echo esc_html($post_type); ?></p>
            <p>Columns: <?php echo esc_html($columns); ?></p>
            <p>Items: <?php echo esc_html(count($items)); ?></p>
            <p>Show Filters: <?php echo $show_filters ? 'Yes' : 'No'; ?></p>
        </div>
        <?php if ($show_filters): ?>
            <div class="filter-sidebar">
                <h3 class="filter-sidebar__title">Filter</h3>
                <a href="#" class="filter-sidebar__advanced">Advanced</a>
                
                <?php if ($post_type === 'property'): ?>
                    <!-- Property Type Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section__title">Property Type</h4>
                        <div class="filter-section__items">
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="Condo">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">🏢</span>
                                    Condo
                                </span>
                                <span class="filter-checkbox__count">4</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="Cottage">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">🏡</span>
                                    Cottage
                                </span>
                                <span class="filter-checkbox__count">3</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="House">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">🏠</span>
                                    House
                                </span>
                                <span class="filter-checkbox__count">3</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Location Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section__title">Location</h4>
                        <div class="filter-section__items">
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="North Topsail Beach">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">📍</span>
                                    North Topsail Beach
                                </span>
                                <span class="filter-checkbox__count">6</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="Surf City">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">🏄</span>
                                    Surf City
                                </span>
                                <span class="filter-checkbox__count">9</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="filter-checkbox__input" data-filter="Topsail Beach">
                                <span class="filter-checkbox__label">
                                    <span class="filter-checkbox__icon">🏖️</span>
                                    Topsail Beach
                                </span>
                                <span class="filter-checkbox__count">9</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Bedrooms Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section__title">Bedrooms</h4>
                        <div class="filter-section__slider-container">
                            <div class="filter-section__slider-track">
                                <div class="filter-section__slider-fill"></div>
                                <div class="filter-section__slider-handle" style="left: 50%"></div>
                            </div>
                            <div class="filter-section__slider-label">Selected: Any</div>
                        </div>
                    </div>
                    
                    <!-- Bathrooms Filter -->
                    <div class="filter-section">
                        <h4 class="filter-section__title">Bathrooms</h4>
                        <div class="filter-section__slider-container">
                            <div class="filter-section__slider-track">
                                <div class="filter-section__slider-fill"></div>
                                <div class="filter-section__slider-handle" style="left: 50%"></div>
                            </div>
                            <div class="filter-section__slider-label">Selected: Any</div>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Generic filters for other post types -->
                    <?php foreach ($filters as $filter_key => $filter_items): ?>
                        <div class="filter-section">
                            <h4 class="filter-section__title"><?php echo esc_html(ucfirst($filter_key)); ?></h4>
                            <div class="filter-section__items">
                                <?php foreach ($filter_items as $item): ?>
                                    <label class="filter-checkbox">
                                        <input type="checkbox" class="filter-checkbox__input" data-filter="<?php echo esc_attr($item['name']); ?>">
                                        <span class="filter-checkbox__label">
                                            <?php if (!empty($item['icon'])): ?>
                                                <span class="filter-checkbox__icon"><?php echo $item['icon']; ?></span>
                                            <?php endif; ?>
                                            <?php echo esc_html($item['name']); ?>
                                        </span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="mi-card-loop__main">
            <div class="view-controls">
                <div class="view-controls__count">
                    <span class="view-controls__count-number"><?php echo count($items); ?></span>
                    <span class="view-controls__count-label"><?php echo _n('Item', 'Items', count($items), 'blocksy-child'); ?></span>
                </div>
                
                <div class="view-switcher">
                    <button class="view-switcher__button view-switcher__button--grid view-switcher__button--active" data-view="grid" aria-label="Grid view">
                        <span class="view-switcher__icon view-switcher__icon--grid"></span>
                    </button>
                    <button class="view-switcher__button view-switcher__button--list" data-view="list" aria-label="List view">
                        <span class="view-switcher__icon view-switcher__icon--list"></span>
                    </button>
                </div>
            </div>

            <?php if (!empty($items)): ?>
                <div class="view-grid view-grid--responsive">
                    <?php foreach ($items as $item): ?>
                        <div class="card card--<?php echo esc_attr($card_style); ?>">
                            <?php if (!empty($item['image'])): ?>
                                <div class="card__image">
                                    <!-- Price Tag (top-left) -->
                                    <?php if ($post_type === 'property' && !empty($item['meta']['price'])): ?>
                                        <div class="card__price-tag">
                                            <?php echo esc_html($item['meta']['price']); ?>/night
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Property Type Tag (top-right) -->
                                    <?php if ($post_type === 'property' && !empty($item['meta']['type'])): ?>
                                        <div class="card__type-tag">
                                            <?php echo esc_html($item['meta']['type']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <a href="<?php echo esc_url($item['link']); ?>" class="card__image-link">
                                        <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="card__img">
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="card__content">
                                <h3 class="card__title">
                                    <a href="<?php echo esc_url($item['link']); ?>" class="card__title-link">
                                        <?php echo esc_html($item['title']); ?>
                                    </a>
                                </h3>
                                
                                <div class="card__excerpt">
                                    <?php if (!empty($item['excerpt'])): ?>
                                        <?php echo wp_kses_post($item['excerpt']); ?>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($post_type === 'property' && !empty($item['meta'])): ?>
                                    <div class="card__features-row">
                                        <?php if (!empty($item['meta']['beds'])): ?>
                                            <div class="card__feature">
                                                <span class="card__feature-number"><?php echo esc_html($item['meta']['beds']); ?></span>
                                                <span class="card__feature-label">BEDS</span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['meta']['baths'])): ?>
                                            <div class="card__feature">
                                                <span class="card__feature-number"><?php echo esc_html($item['meta']['baths']); ?></span>
                                                <span class="card__feature-label">BATHS</span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($item['meta']['guests'])): ?>
                                            <div class="card__feature">
                                                <span class="card__feature-number"><?php echo esc_html($item['meta']['guests']); ?></span>
                                                <span class="card__feature-label">GUESTS</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['meta']['owner'])): ?>
                                    <div class="card__owner">
                                        <span class="card__owner-icon">👤</span>
                                        <span class="card__owner-name"><?php echo esc_html($item['meta']['owner']); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="card__footer">
                                    <a href="<?php echo esc_url($item['link']); ?>" class="card__more-link">
                                        <?php _e('View Details', 'blocksy-child'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-state__icon"></div>
                    <h3 class="empty-state__title"><?php _e('No items found', 'blocksy-child'); ?></h3>
                    <p class="empty-state__message"><?php _e('No items were found matching your selection.', 'blocksy-child'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Simple script to toggle filter sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.filter-sidebar__toggle');
        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const sidebar = this.closest('.filter-sidebar');
                sidebar.classList.toggle('filter-sidebar--open');
            });
        }
        
        // View switcher functionality
        const viewButtons = document.querySelectorAll('.view-switcher__button');
        if (viewButtons.length) {
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    viewButtons.forEach(btn => btn.classList.remove('view-switcher__button--active'));
                    
                    // Add active class to clicked button
                    this.classList.add('view-switcher__button--active');
                    
                    // Get the view type
                    const viewType = this.dataset.view;
                    
                    // Get the container
                    const container = this.closest('.mi-card-loop__main').querySelector('.view-grid');
                    
                    // Remove all view classes
                    container.classList.remove('view-grid', 'view-list');
                    
                    // Add the appropriate class
                    container.classList.add('view-' + viewType);
                });
            });
        }
    });
</script>
