/**
 * MI Card Loop Block JavaScript
 * Following HTML/CSS/JS first approach with mock data
 */

document.addEventListener('DOMContentLoaded', function() {
  // Elements
  const priceSlider = document.getElementById('price-slider');
  const priceValue = document.getElementById('price-value');
  const bedroomsSlider = document.getElementById('bedrooms-slider');
  const bedroomsValue = document.getElementById('bedrooms-value');
  const bathroomsSlider = document.getElementById('bathrooms-slider');
  const bathroomsValue = document.getElementById('bathrooms-value');
  const guestsSlider = document.getElementById('guests-slider');
  const guestsValue = document.getElementById('guests-value');
  const filterCheckboxes = document.querySelectorAll('input[type="checkbox"]');
  const viewButtons = document.querySelectorAll('.view-btn');
  const resetButton = document.getElementById('reset-filters');
  
  // Initialize price slider
  if (priceSlider && priceValue) {
    priceSlider.addEventListener('input', function() {
      priceValue.textContent = this.value;
      applyFilters();
    });
  }
  
  // Initialize bedrooms slider
  if (bedroomsSlider && bedroomsValue) {
    bedroomsSlider.addEventListener('input', function() {
      const value = parseInt(this.value);
      bedroomsValue.textContent = value === 0 ? 'Any' : value + '+';
      applyFilters();
    });
  }
  
  // Initialize bathrooms slider
  if (bathroomsSlider && bathroomsValue) {
    bathroomsSlider.addEventListener('input', function() {
      const value = parseInt(this.value);
      bathroomsValue.textContent = value === 0 ? 'Any' : value + '+';
      applyFilters();
    });
  }
  
  // Initialize guests slider
  if (guestsSlider && guestsValue) {
    guestsSlider.addEventListener('input', function() {
      const value = parseInt(this.value);
      guestsValue.textContent = value === 0 ? 'Any' : value + '+';
      applyFilters();
    });
  }
  
  // Initialize filter checkboxes
  filterCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', applyFilters);
  });
  
  // Initialize view buttons (grid/list)
  viewButtons.forEach(button => {
    button.addEventListener('click', function() {
      viewButtons.forEach(btn => {
        btn.classList.remove('active', 'bg-[--color-neutral-light]', 'text-[--color-secondary-med]');
      });
      
      this.classList.add('active', 'bg-[--color-neutral-light]', 'text-[--color-secondary-med]');
      
      // Toggle grid/list view
      const itemsGrid = document.querySelector('.items-grid');
      if (itemsGrid) {
        if (this.dataset.view === 'list') {
          itemsGrid.classList.remove('md:grid-cols-2', 'lg:grid-cols-3');
          itemsGrid.classList.add('grid-cols-1');
        } else {
          itemsGrid.classList.remove('grid-cols-1');
          itemsGrid.classList.add('md:grid-cols-2', 'lg:grid-cols-3');
        }
      }
      
      applyFilters();
    });
  });
  
  // Initialize reset button
  if (resetButton) {
    resetButton.addEventListener('click', resetFilters);
  }
  
  // Apply filters based on selected options
  function applyFilters() {
    // Get selected types (property or business types)
    const selectedTypes = Array.from(
      document.querySelectorAll('input[type="checkbox"].type-filter:checked')
    ).map(checkbox => checkbox.value);
    
    // Get selected locations
    const selectedLocations = Array.from(
      document.querySelectorAll('input[type="checkbox"].location-filter:checked')
    ).map(checkbox => checkbox.value);
    
    // Get selected amenities
    const selectedAmenities = Array.from(
      document.querySelectorAll('input[type="checkbox"].amenity-filter:checked')
    ).map(checkbox => checkbox.value);
    
    // Get price range
    const selectedPrice = priceSlider ? parseInt(priceSlider.value) : 0;
    
    // Get bedrooms (only applies to properties)
    const selectedBedrooms = bedroomsSlider ? parseInt(bedroomsSlider.value) : 0;
    
    // Get bathrooms (only applies to properties)
    const selectedBathrooms = bathroomsSlider ? parseInt(bathroomsSlider.value) : 0;
    
    // Get guests (only applies to properties)
    const selectedGuests = guestsSlider ? parseInt(guestsSlider.value) : 0;
    
    // Get all cards
    const cards = document.querySelectorAll('.card');
    
    // Loop through each card and check if it matches the filters
    cards.forEach(card => {
      let showCard = true;
      
      // Check type (property type or business type)
      if (selectedTypes.length > 0) {
        const cardType = card.getAttribute('data-type');
        if (!selectedTypes.includes(cardType)) {
          showCard = false;
        }
      }
      
      // Check location
      if (showCard && selectedLocations.length > 0) {
        const cardLocation = card.getAttribute('data-location');
        if (!selectedLocations.includes(cardLocation)) {
          showCard = false;
        }
      }
      
      // Check bedrooms (only for properties)
      if (showCard && selectedBedrooms > 0 && card.hasAttribute('data-bedrooms')) {
        const cardBedrooms = parseInt(card.getAttribute('data-bedrooms') || '0');
        if (cardBedrooms < selectedBedrooms) {
          showCard = false;
        }
      }
      
      // Check bathrooms (only for properties)
      if (showCard && selectedBathrooms > 0 && card.hasAttribute('data-bathrooms')) {
        const cardBathrooms = parseInt(card.getAttribute('data-bathrooms') || '0');
        if (cardBathrooms < selectedBathrooms) {
          showCard = false;
        }
      }
      
      // Check guests (only for properties)
      if (showCard && selectedGuests > 0 && card.hasAttribute('data-guests')) {
        const cardGuests = parseInt(card.getAttribute('data-guests') || '0');
        if (cardGuests < selectedGuests) {
          showCard = false;
        }
      }
      
      // Check amenities
      if (showCard && selectedAmenities.length > 0) {
        const cardAmenities = (card.getAttribute('data-amenities') || '').split(',').filter(Boolean);
        // Check if the card has ALL selected amenities
        const hasAllAmenities = selectedAmenities.every(amenity => 
          cardAmenities.some(cardAmenity => cardAmenity.trim().toLowerCase() === amenity.trim().toLowerCase())
        );
        if (!hasAllAmenities) {
          showCard = false;
        }
      }
      
      // Show or hide the card
      card.style.display = showCard ? '' : 'none';
    });
  }
  
  // Reset all filters to default values
  function resetFilters() {
    // Reset checkboxes
    filterCheckboxes.forEach(checkbox => {
      checkbox.checked = false;
    });
    
    // Reset price slider
    if (priceSlider && priceValue) {
      priceSlider.value = priceSlider.min;
      priceValue.textContent = priceSlider.min;
    }
    
    // Reset bedrooms slider
    if (bedroomsSlider && bedroomsValue) {
      bedroomsSlider.value = 0;
      bedroomsValue.textContent = 'Any';
    }
    
    // Reset bathrooms slider
    if (bathroomsSlider && bathroomsValue) {
      bathroomsSlider.value = 0;
      bathroomsValue.textContent = 'Any';
    }
    
    // Reset guests slider
    if (guestsSlider && guestsValue) {
      guestsSlider.value = 0;
      guestsValue.textContent = 'Any';
    }
    
    // Apply filters (which will now show all properties)
    applyFilters();
  }
  
  // Initialize filters on page load
  applyFilters();
});
