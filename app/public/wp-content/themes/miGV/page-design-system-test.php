<?php
/**
 * Template Name: Design System Test Page
 * Simple test page to verify the design system works
 */

// Security check
if (!defined('ABSPATH')) {
    exit;
}

get_header(); ?>

<div style="padding: 40px; max-width: 1200px; margin: 0 auto;">
    <h1>Villa Design System Test</h1>
    <p>This is a test page to verify the design system integration.</p>
    
    <div style="margin: 20px 0;">
        <h2>Navigation Links:</h2>
        <ul>
            <li><a href="/design-system">Design System Dashboard</a> (Main interface)</li>
            <li><a href="?tab=design-system">Dashboard with Design System Tab</a></li>
        </ul>
    </div>
    
    <div style="margin: 20px 0; padding: 20px; background: #f5f5f5; border-radius: 8px;">
        <h3>CSS & JS Files Status:</h3>
        <p><strong>New CSS:</strong> <?php echo file_exists(get_template_directory() . '/assets/css/design-book.css') ? '✅ Active' : '❌ Missing'; ?></p>
        <p><strong>New JS:</strong> <?php echo file_exists(get_template_directory() . '/assets/js/design-book.js') ? '✅ Active' : '❌ Missing'; ?></p>
        <p><strong>Old CSS Backup:</strong> <?php echo file_exists(get_template_directory() . '/assets/css/design-book-old.css') ? '✅ Backed up' : '❌ Missing'; ?></p>
        <p><strong>Old JS Backup:</strong> <?php echo file_exists(get_template_directory() . '/assets/js/design-book-old.js') ? '✅ Backed up' : '❌ Missing'; ?></p>
    </div>
    
    <div style="margin: 20px 0; padding: 20px; background: #e8f4fd; border-radius: 8px;">
        <h3>Theme.json Integration Test:</h3>
        <div style="font-size: var(--wp--preset--font-size--large); color: var(--wp--preset--color--primary); margin: 10px 0;">
            Large text using theme.json font size token
        </div>
        <div style="font-size: var(--wp--preset--font-size--medium); color: var(--wp--preset--color--secondary); margin: 10px 0;">
            Medium text using theme.json color token
        </div>
        <div style="padding: var(--wp--preset--spacing--medium); background: var(--wp--preset--color--tertiary); border-radius: var(--wp--preset--border-radius--medium); margin: 10px 0;">
            Box using theme.json spacing and border radius tokens
        </div>
    </div>
</div>

<?php get_footer(); ?>
