<?php
/**
 * Template for the Card Loop Block
 * Following HTML/CSS/JS first approach
 */

// Extract variables from the context
$post_type = $post_type ?? 'property';
$title = $title ?? '';
$items = $items ?? [];
$filters = $filters ?? [];
$count = $count ?? count($items);
$show_filters = $show_filters ?? true;
$columns = $columns ?? '3';
$card_style = $card_style ?? 'default';
$wrapper_attributes = $wrapper_attributes ?? 'class="mi-card-loop"';
?>

<div <?php echo $wrapper_attributes; ?>>
  <div class="container mx-auto px-4 py-8">
    <!-- View Switcher Controls -->
    <div class="flex justify-end mb-6">

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      <?php if ($show_filters) : ?>
      <!-- Filter Sidebar -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-semibold">Filter</h2>
            <button class="text-[--color-secondary-med] hover:text-[--color-secondary-dark] text-sm">Advanced</button>
          </div>

          <!-- Type Filter -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3 text-[--color-primary-med]">
              <span>
                <?php echo $post_type === 'property' ? 'Property Type' : 'Business Type'; ?>
              </span>
            </h5>
            <div class="space-y-2">
              <?php if (!empty($filters['types'])) : 
                foreach ($filters['types'] as $type) : ?>
                <label class="flex items-center">
                  <input type="checkbox" class="form-checkbox h-4 w-4 accent-[--color-secondary-med] type-filter" value="<?php echo esc_attr($type['name']); ?>">
                  <span class="ml-2 flex items-center">
                    <span class="mr-1"><?php echo esc_html($type['icon']); ?></span>
                    <?php echo esc_html($type['name']); ?>
                  </span>
                  <span class="ml-auto text-xs text-[--color-neutral-med]"><?php echo esc_html($type['count']); ?></span>
                </label>
              <?php endforeach; endif; ?>
            </div>
          </div>

          <!-- Location Filter -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3 text-[--color-primary-med]">
              <span>
                Location
              </span>
            </h5>
            <div class="space-y-2">
              <?php if (!empty($filters['locations'])) : 
                foreach ($filters['locations'] as $location) : ?>
                <label class="flex items-center">
                  <input type="checkbox" class="form-checkbox h-4 w-4 accent-[--color-secondary-med] location-filter" value="<?php echo esc_attr($location['name']); ?>">
                  <span class="ml-2 flex items-center">
                    <span class="mr-1"><?php echo esc_html($location['icon']); ?></span>
                    <?php echo esc_html($location['name']); ?>
                  </span>
                  <span class="ml-auto text-xs text-[--color-neutral-med]"><?php echo esc_html($location['count']); ?></span>
                </label>
              <?php endforeach; endif; ?>
            </div>
          </div>

          <?php if ($post_type === 'property') : ?>
          <!-- Bedrooms Slider (Property only) -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3">
              <span>
                Bedrooms
              </span>
            </h5>
            <div class="mb-2">
              <input type="range" id="bedrooms-slider" min="0" max="5" value="0" class="w-full" style="--thumb-color: var(--color-secondary-med); --thumb-border: white;">
              <div class="mt-1 text-sm">
                <span>Selected: <span id="bedrooms-value">Any</span></span>
              </div>
            </div>
          </div>

          <!-- Bathrooms Slider (Property only) -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3">
              <span>
                Bathrooms
              </span>
            </h5>
            <div class="mb-2">
              <input type="range" id="bathrooms-slider" min="0" max="5" value="0" class="w-full" style="--thumb-color: var(--color-secondary-med); --thumb-border: white;">
              <div class="mt-1 text-sm">
                <span>Selected: <span id="bathrooms-value">Any</span></span>
              </div>
            </div>
          </div>

          <!-- Guests Slider (Property only) -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3">
              <span>
                Guests
              </span>
            </h5>
            <div class="mb-2">
              <input type="range" id="guests-slider" min="0" max="10" value="0" class="w-full" style="--thumb-color: var(--color-secondary-med); --thumb-border: white;">
              <div class="mt-1 text-sm">
                <span>Selected: <span id="guests-value">Any</span></span>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <!-- Amenities Filter -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3 text-[--color-primary-med]">
              <span>
                Amenities
              </span>
            </h5>
            <div class="space-y-2">
              <?php if (!empty($filters['amenities'])) : 
                foreach ($filters['amenities'] as $amenity) : ?>
                <label class="flex items-center">
                  <input type="checkbox" class="form-checkbox h-4 w-4 accent-[--color-secondary-med] amenity-filter" value="<?php echo esc_attr($amenity['name']); ?>">
                  <span class="ml-2 flex items-center">
                    <span class="mr-1"><?php echo esc_html($amenity['icon']); ?></span>
                    <?php echo esc_html($amenity['name']); ?>
                  </span>
                </label>
              <?php endforeach; endif; ?>
            </div>
          </div>

          <!-- Reset Button -->
          <div class="mt-6">
            <button id="reset-filters" class="w-full py-2 px-4 bg-[--color-neutral-light] text-[--color-neutral-dark] text-sm font-medium rounded-md transition-colors hover:bg-[--color-neutral-med] hover:text-white">
              Reset Filters
            </button>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Property Cards -->
      <div class="<?php echo $show_filters ? 'lg:col-span-3' : 'lg:col-span-4'; ?>">
        <!-- View Switcher -->
        <div class="flex justify-between items-center mb-6">
          <div class="flex items-center space-x-2">
            <button class="view-btn active bg-[--color-neutral-light] text-[--color-secondary-med] p-2 rounded-md" data-view="grid">
              <span class="sr-only">Grid View</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
            <button class="view-btn p-2 rounded-md" data-view="list">
              <span class="sr-only">List View</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Items Grid -->
        <div class="items-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr($columns); ?> gap-6">
          <?php if (!empty($items)) : 
            foreach ($items as $index => $item) : ?>
            <?php
              // Prepare data attributes for filtering
              $type = esc_attr($item['type'] ?? '');
              $location = esc_attr($item['location'] ?? '');
              
              // Prepare amenities for filtering
              $amenities_array = [];
              if (!empty($item['amenities'])) {
                foreach ($item['amenities'] as $amenity) {
                  $amenities_array[] = is_array($amenity) ? $amenity['name'] : $amenity;
                }
              }
              $amenities_string = esc_attr(implode(',', $amenities_array));
              
              // Prepare additional attributes based on post type
              $data_attributes = "data-type=\"{$type}\" data-location=\"{$location}\" data-amenities=\"{$amenities_string}\"";
              
              if ($post_type === 'property') {
                $bedrooms = (int)($item['bedrooms'] ?? 0);
                $bathrooms = (int)($item['bathrooms'] ?? 0);
                $guests = (int)($item['guests'] ?? 0);
                $data_attributes .= " data-bedrooms=\"{$bedrooms}\" data-bathrooms=\"{$bathrooms}\" data-guests=\"{$guests}\"";
              }
            ?>
            <div class="card <?php echo esc_attr($card_style !== 'default' ? 'card-' . $card_style : ''); ?>" <?php echo $data_attributes; ?>>
              <div class="card-image">
                <?php if (!empty($item['image'])) : ?>
                  <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                <?php else : ?>
                  <div class="w-full h-full bg-[--color-neutral-light] flex items-center justify-center">
                    <span class="text-4xl"><?php echo $post_type === 'property' ? '🏠' : '🏪'; ?></span>
                  </div>
                <?php endif; ?>
                <div class="badge badge-primary">
                  <?php echo esc_html($item['price'] ?? ''); ?>
                </div>
                <div class="badge badge-secondary">
                  <span class="icon"><?php echo esc_html($item['type_icon'] ?? ''); ?></span>
                  <?php echo esc_html($item['type'] ?? ''); ?>
                </div>
              </div>
              <div class="card-content">
                <div class="mb-auto">
                  <h3 class="card-title line-clamp-2"><?php echo esc_html($item['title']); ?></h3>
                  <p class="card-description line-clamp-2"><?php echo esc_html($item['description']); ?></p>
                </div>
                
                <?php if ($post_type === 'property') : ?>
                <!-- Property specific tags -->
                <div class="card-tags">
                  <span class="tag">
                    <span class="icon">🛏️</span>
                    <?php echo esc_html($item['bedrooms']); ?> Beds
                  </span>
                  <span class="tag">
                    <span class="icon">🛁</span>
                    <?php echo esc_html($item['bathrooms']); ?> Baths
                  </span>
                  <span class="tag">
                    <span class="icon">👥</span>
                    <?php echo esc_html($item['guests']); ?> Guests
                  </span>
                </div>
                <?php elseif ($post_type === 'business') : ?>
                <!-- Business specific tags -->
                <div class="card-tags">
                  <?php if (!empty($item['phone'])) : ?>
                  <span class="tag">
                    <span class="icon">📞</span>
                    <?php echo esc_html($item['phone']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($item['address'])) : ?>
                  <span class="tag">
                    <span class="icon">📍</span>
                    <?php echo esc_html($item['city'] ?? $item['address']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (isset($item['is_open'])) : ?>
                  <span class="tag <?php echo $item['is_open'] ? 'tag-success' : 'tag-danger'; ?>">
                    <span class="icon"><?php echo $item['is_open'] ? '✅' : '⏱️'; ?></span>
                    <?php echo $item['is_open'] ? 'Open Now' : 'Closed'; ?>
                  </span>
                  <?php endif; ?>
                </div>
                <?php elseif ($post_type === 'article') : ?>
                <!-- Article specific tags -->
                <div class="card-tags">
                  <?php if (!empty($item['author'])) : ?>
                  <span class="tag">
                    <span class="icon">👤</span>
                    <?php echo esc_html($item['author']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($item['date'])) : ?>
                  <span class="tag">
                    <span class="icon">📅</span>
                    <?php echo esc_html($item['date']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($item['reading_time'])) : ?>
                  <span class="tag">
                    <span class="icon">⏰</span>
                    <?php echo esc_html($item['reading_time']); ?> read
                  </span>
                  <?php endif; ?>
                </div>
                <?php elseif ($post_type === 'user_profile') : ?>
                <!-- User profile specific tags -->
                <div class="card-tags">
                  <?php if (!empty($item['role'])) : ?>
                  <span class="tag">
                    <span class="icon">💼</span>
                    <?php echo esc_html($item['role']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($item['specialty'])) : ?>
                  <span class="tag">
                    <span class="icon">🎓</span>
                    <?php echo esc_html($item['specialty']); ?>
                  </span>
                  <?php endif; ?>
                  <?php if (!empty($item['years_experience'])) : ?>
                  <span class="tag">
                    <span class="icon">📅</span>
                    <?php echo esc_html($item['years_experience']); ?>+ years
                  </span>
                  <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <div class="flex flex-wrap gap-1 mb-4">
                  <?php if (!empty($item['amenities'])) : 
                    foreach ($item['amenities'] as $amenity) : 
                      $amenity_name = is_array($amenity) ? $amenity['name'] : $amenity;
                      $amenity_icon = is_array($amenity) ? $amenity['icon'] : '';
                      
                      // Default icons for common amenities if not already set
                      if (empty($amenity_icon)) {
                        if ($post_type === 'property') {
                          if (stripos($amenity_name, 'pool') !== false) $amenity_icon = '🏊';
                          elseif (stripos($amenity_name, 'ocean') !== false || stripos($amenity_name, 'view') !== false) $amenity_icon = '🌅';
                          elseif (stripos($amenity_name, 'pet') !== false) $amenity_icon = '🐕';
                          elseif (stripos($amenity_name, 'wifi') !== false) $amenity_icon = '📶';
                          elseif (stripos($amenity_name, 'yard') !== false) $amenity_icon = '🏡';
                          else $amenity_icon = '✨';
                        } else {
                          if (stripos($amenity_name, 'wifi') !== false) $amenity_icon = '📶';
                          elseif (stripos($amenity_name, 'park') !== false) $amenity_icon = '🅿️';
                          elseif (stripos($amenity_name, 'delivery') !== false) $amenity_icon = '🚚';
                          elseif (stripos($amenity_name, 'online') !== false) $amenity_icon = '💻';
                          elseif (stripos($amenity_name, 'discount') !== false) $amenity_icon = '🏷️';
                          else $amenity_icon = '✨';
                        }
                      }
                    ?>
                    <span class="feature-tag">
                      <span class="icon"><?php echo esc_html($amenity_icon); ?></span>
                      <?php echo esc_html($amenity_name); ?>
                    </span>
                  <?php endforeach; endif; ?>
                </div>
                <div class="card-button">
                  <a href="<?php echo esc_url($item['permalink']); ?>" class="ct-button">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
