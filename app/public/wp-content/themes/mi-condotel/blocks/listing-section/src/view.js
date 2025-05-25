/**
 * Frontend JavaScript for Card Loop block
 * Handles filter functionality
 */

document.addEventListener('DOMContentLoaded', () => {
    console.log('Listing Section JS loaded');
    
    // Find all listing section blocks
    const blocks = document.querySelectorAll('.wp-block-miblocks-listing-section');
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
    const originalContent = cardsContainer.innerHTML;
    
    // Add change listeners to checkboxes
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            console.log('Filter changed:', checkbox.dataset.taxonomy, checkbox.dataset.term);
            applyFilters(block);
        });
    });
    
    // Add change listeners to range inputs
    rangeInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const valueDisplay = input.closest('.filter-range').querySelector('.filter-range__value span');
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
    const filters = {};
    const ranges = {};
    
    // Collect active filters
    block.querySelectorAll('.filter-checkbox__input:checked').forEach(checkbox => {
        const taxonomy = checkbox.dataset.taxonomy;
        const term = checkbox.dataset.term;
        
        if (!filters[taxonomy]) {
            filters[taxonomy] = [];
        }
        filters[taxonomy].push(term);
    });
    
    // Collect range filters
    block.querySelectorAll('.filter-range__input').forEach(input => {
        const range = input.dataset.range;
        const value = parseInt(input.value);
        
        if (value > 0) {
            ranges[range] = value;
        }
    });
    
    console.log('Active filters:', filters);
    console.log('Range filters:', ranges);
    
    // Prepare AJAX request
    const formData = new URLSearchParams({
        action: 'filter_properties',
        nonce: miblocks_ajax.nonce
    });
    
    // Add filters to form data
    Object.keys(filters).forEach(taxonomy => {
        filters[taxonomy].forEach(term => {
            formData.append(`filter[${taxonomy}][]`, term);
        });
    });
    
    // Add ranges to form data
    Object.keys(ranges).forEach(range => {
        formData.append(`range[${range}]`, ranges[range]);
    });
    
    // Add block attributes
    formData.append('post_type', block.dataset.postType || 'property');
    formData.append('posts_per_page', block.dataset.postsPerPage || '12');
    formData.append('card_style', block.dataset.cardStyle || 'default');
    formData.append('columns', block.dataset.columns || '3');
    
    console.log('AJAX params:', formData.toString());
    
    // Show loading state
    const cardsContainer = block.querySelector('.cards-container');
    if (cardsContainer) {
        cardsContainer.style.opacity = '0.5';
    }
    
    // Send AJAX request
    fetch(miblocks_ajax.ajax_url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('AJAX response:', data);
        
        if (data.success && cardsContainer) {
            cardsContainer.innerHTML = data.data.html;
            cardsContainer.style.opacity = '1';
            
            // Update count if present
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
