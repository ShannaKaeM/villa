// Hero Section Block JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const heroSections = document.querySelectorAll('.carbon-block--hero-section');
    
    heroSections.forEach(function(hero) {
        // Add a fade-in animation
        hero.style.opacity = '0';
        hero.style.transition = 'opacity 1s ease-in-out';
        
        // Trigger fade-in
        setTimeout(function() {
            hero.style.opacity = '1';
        }, 100);
        
        // Button hover effects
        const button = hero.querySelector('.carbon-block--hero-section__button');
        if (button) {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        }
    });
});