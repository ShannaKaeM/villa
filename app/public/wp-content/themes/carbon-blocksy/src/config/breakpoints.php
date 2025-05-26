<?php
/**
 * Breakpoint definitions for Carbon Blocks responsive CSS compilation
 *
 * @package CarbonBlocks
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get breakpoint media queries
 * LG is the base breakpoint (no media query)
 */
function carbon_blocks_get_breakpoints() {
    return [
        'XS' => '@media (max-width: 575.98px)',
        'SM' => '@media (min-width: 576px) and (max-width: 767.98px)', 
        'MD' => '@media (min-width: 768px) and (max-width: 991.98px)',
        'LG' => '', // Base breakpoint - no media query
        'XL' => '@media (min-width: 1200px) and (max-width: 1399.98px)',
        '2XL' => '@media (min-width: 1400px)'
    ];
}