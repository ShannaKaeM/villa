<?php
/**
 * Template for the Card Loop Block
 * Following HTML/CSS/JS first approach
 */

// Extract variables from the context
$properties = $properties ?? [];
$filters = $filters ?? [];
$count = $count ?? count($properties);
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

          <!-- Property Type Filter -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3 text-[--color-primary-med]">
              <span>
                Property Type
              </span>
            </h5>
            <div class="space-y-2">
              <?php if (!empty($filters['property_types'])) : 
                foreach ($filters['property_types'] as $type) : ?>
                <label class="flex items-center">
                  <input type="checkbox" class="form-checkbox h-4 w-4 accent-[--color-secondary-med] property-type-filter" value="<?php echo esc_attr($type['name']); ?>">
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

          <!-- Bedrooms Slider -->
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

          <!-- Bathrooms Slider -->
          <div class="border-b border-[--color-base-light] pb-4 mb-6">
            <h5 class="flex items-center justify-between mb-3">
              <span>
                Bathrooms
              </span>
            </h5>
            <div class="mb-2">
              <input type="range" id="bathrooms-slider" min="0" max="4" value="0" class="w-full" style="--thumb-color: var(--color-secondary-med); --thumb-border: white;">
              <div class="mt-1 text-sm">
                <span>Selected: <span id="bathrooms-value">Any</span></span>
              </div>
            </div>
          </div>

          <!-- Guests Slider -->
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

        <!-- Property Grid -->
        <div class="property-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr($columns); ?> gap-6">
          <?php if (!empty($properties)) : 
            foreach ($properties as $index => $property) : ?>
            <?php
              // Prepare data attributes for filtering
              $property_type = esc_attr($property['property_type'] ?? '');
              $location = esc_attr($property['location'] ?? '');
              $bedrooms = esc_attr($property['bedrooms'] ?? 0);
              $bathrooms = esc_attr($property['bathrooms'] ?? 0);
              $guests = esc_attr($property['guests'] ?? $property['max_guests'] ?? 0);
              
              // Prepare amenities as a comma-separated list
              $amenities_array = [];
              if (!empty($property['amenities'])) {
                foreach ($property['amenities'] as $amenity) {
                  $amenities_array[] = is_array($amenity) ? $amenity['name'] : $amenity;
                }
              }
              $amenities_string = esc_attr(implode(',', $amenities_array));
            ?>
            <div class="property-card bg-white rounded-lg shadow-md overflow-hidden"
                 data-property-type="<?php echo $property_type; ?>"
                 data-location="<?php echo $location; ?>"
                 data-bedrooms="<?php echo $bedrooms; ?>"
                 data-bathrooms="<?php echo $bathrooms; ?>"
                 data-guests="<?php echo $guests; ?>"
                 data-amenities="<?php echo $amenities_string; ?>">
              <div class="relative">
                <div class="aspect-[6/4] overflow-hidden">
                  <?php if (!empty($property['image'])) : ?>
                    <img src="<?php echo esc_url($property['image']); ?>" alt="<?php echo esc_attr($property['title']); ?>" class="w-full h-full object-cover object-center">
                  <?php else : ?>
                    <div class="w-full h-full bg-[--color-neutral-light] flex items-center justify-center">
                      <span class="text-4xl">🏠</span>
                    </div>
                  <?php endif; ?>
                </div>
                <div class="absolute top-3 left-3 bg-[--color-primary-med] text-white px-3 py-1 rounded-md text-sm font-medium">
                  <?php echo esc_html($property['price']); ?>
                </div>
                <div class="absolute top-3 right-3 bg-[--color-secondary-med] text-white px-3 py-1 rounded-md text-xs font-medium">
                  <span class="flex items-center">
                    <span class="mr-1"><?php echo esc_html($property['property_type_icon']); ?></span>
                    <?php echo esc_html($property['property_type']); ?>
                  </span>
                </div>
              </div>
              <div class="p-4 flex flex-col h-[calc(100%-240px)]">
                <div class="mb-auto">
                  <h3 class="text-lg font-semibold mb-0 h-[52px] line-clamp-2"><?php echo esc_html($property['title']); ?></h3>
                  <p class="text-xs text-[--color-neutral-med] mb-3 line-clamp-2"><?php echo esc_html($property['description']); ?></p>
                </div>
                <div class="flex flex-wrap gap-2 mb-3">
                  <span class="inline-flex items-center text-xs bg-[--color-neutral-light] px-2 py-1 rounded-md">
                    <span class="mr-1">🛏️</span>
                    <?php echo esc_html($property['bedrooms']); ?> Beds
                  </span>
                  <span class="inline-flex items-center text-xs bg-[--color-neutral-light] px-2 py-1 rounded-md">
                    <span class="mr-1">🛁</span>
                    <?php echo esc_html($property['bathrooms']); ?> Baths
                  </span>
                  <span class="inline-flex items-center text-xs bg-[--color-neutral-light] px-2 py-1 rounded-md">
                    <span class="mr-1">👥</span>
                    <?php echo esc_html($property['guests'] ?? $property['max_guests']); ?> Guests
                  </span>
                </div>
                <div class="flex flex-wrap gap-1 mb-4">
                  <?php if (!empty($property['amenities'])) : 
                    foreach ($property['amenities'] as $amenity) : 
                      $amenity_name = is_array($amenity) ? $amenity['name'] : $amenity;
                      $amenity_icon = is_array($amenity) ? $amenity['icon'] : '';
                      
                      // Default icons for common amenities
                      if (empty($amenity_icon)) {
                        if (stripos($amenity_name, 'pool') !== false) $amenity_icon = '🏊';
                        elseif (stripos($amenity_name, 'ocean') !== false || stripos($amenity_name, 'view') !== false) $amenity_icon = '🌅';
                        elseif (stripos($amenity_name, 'pet') !== false) $amenity_icon = '🐕';
                        elseif (stripos($amenity_name, 'wifi') !== false) $amenity_icon = '📶';
                        elseif (stripos($amenity_name, 'yard') !== false) $amenity_icon = '🏡';
                        else $amenity_icon = '✨';
                      }
                    ?>
                    <span class="inline-flex items-center text-xs text-[--color-primary-dark] bg-[--color-primary-light]/10 px-2 py-1 rounded-md shadow-sm">
                      <span class="mr-1"><?php echo esc_html($amenity_icon); ?></span>
                      <?php echo esc_html($amenity_name); ?>
                    </span>
                  <?php endforeach; endif; ?>
                </div>
                <div class="mt-auto">
                  <a href="<?php echo esc_url($property['permalink']); ?>" class="w-full ct-button">View Details</a>
                </div>
              </div>
            </div>
          <?php endforeach; endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
