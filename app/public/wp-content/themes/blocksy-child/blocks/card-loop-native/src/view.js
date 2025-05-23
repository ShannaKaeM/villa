/**
 * Frontend JavaScript for Card Loop block
 * Handles filter functionality
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Card Loop JS loaded');
    
    // Find all card loop blocks
    const blocks = document.querySelectorAll('.wp-block-miblocks-card-loop');
    console.log('Found blocks:', blocks.length);
    
    blocks.forEach(block => {
        initializeFilters(block);
    });
});

function initializeFilters(block) {
    const filterCheckboxes = block.querySelectorAll('.filter-checkbox__input');
    const rangeInputs = block.querySelectorAll('.filter-range__input');
    const cardsContainer = block.querySelector('.cards-container');
    
    console.log('Filter checkboxes:', filterCheckboxes.length);
    console.log('Range inputs:', rangeInputs.length);
    
    if (!cardsContainer) {
        console.error('Cards container not found');
        return;
    }
    
    // Store original content for potential reset
    const originalCards = cardsContainer.innerHTML;
    
    // Handle checkbox changes
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            console.log('Filter changed:', checkbox.dataset.taxonomy, checkbox.dataset.term);
            applyFilters(block);
        });
    });
    
    // Handle range slider changes
    rangeInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            // Update the value display
            const valueDisplay = input.parentElement.querySelector('.filter-range__value span');
            if (valueDisplay) {
                valueDisplay.textContent = e.target.value;
            }
        });
        
        input.addEventListener('change', () => {
            console.log('Range changed:', input.dataset.range, input.value);
            applyFilters(block);
        });
    });
}

function applyFilters(block) {
    const activeFilters = {};
    const rangeFilters = {};
    
    // Collect active checkbox filters
    block.querySelectorAll('.filter-checkbox__input:checked').forEach(checkbox => {
        const taxonomy = checkbox.dataset.taxonomy;
        const term = checkbox.dataset.term;
        
        if (!activeFilters[taxonomy]) {
            activeFilters[taxonomy] = [];
        }
        activeFilters[taxonomy].push(term);
    });
    
    // Collect range filters
    block.querySelectorAll('.filter-range__input').forEach(input => {
        const rangeType = input.dataset.range;
        const value = parseInt(input.value);
        
        if (value > 0) {
            rangeFilters[rangeType] = value;
        }
    });
    
    console.log('Active filters:', activeFilters);
    console.log('Range filters:', rangeFilters);
    
    // Make AJAX request
    const params = new URLSearchParams({
        action: 'filter_properties',
        nonce: miblocks_ajax.nonce
    });
    
    // Add checkbox filters
    Object.keys(activeFilters).forEach(taxonomy => {
        activeFilters[taxonomy].forEach(term => {
            params.append(`filter[${taxonomy}][]`, term);
        });
    });
    
    // Add range filters
    Object.keys(rangeFilters).forEach(rangeType => {
        params.append(`range[${rangeType}]`, rangeFilters[rangeType]);
    });
    
    // Add block attributes
    params.append('post_type', block.dataset.postType || 'property');
    params.append('posts_per_page', block.dataset.postsPerPage || '12');
    params.append('card_style', block.dataset.cardStyle || 'default');
    
    console.log('AJAX params:', params.toString());
    
    // Show loading state
    const cardsContainer = block.querySelector('.cards-container');
    if (cardsContainer) {
        cardsContainer.style.opacity = '0.5';
    }
    
    // Make the request
    fetch(miblocks_ajax.ajax_url, {
        method: 'POST',
        body: params
    })
    .then(response => response.json())
    .then(data => {
        console.log('AJAX response:', data);
        
        if (data.success && cardsContainer) {
            cardsContainer.innerHTML = data.data.html;
            cardsContainer.style.opacity = '1';
            
            // Update results count
            const countElement = block.querySelector('.view-controls__count strong');
            if (countElement) {
                countElement.textContent = data.data.found_posts;
            }
        }
    })
    .catch(error => {
        console.error('AJAX error:', error);
        if (cardsContainer) {
            cardsContainer.style.opacity = '1';
        }
    });
}
