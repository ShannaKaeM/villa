<?php
/**
 * Compile Styles and Scripts for Carbon Blocks
 *
 * @package CarbonBlocks
 */

if (! defined('ABSPATH')) {
    exit;
}

require_once get_stylesheet_directory() . '/src/config/breakpoints.php';

/**
 * Compile CSS styles for a Carbon Block with media query wrapping
 *
 * @param string $block_name The block directory name
 * @return string Compiled CSS with media queries
 */
function carbon_blocks_compile_styles($block_name)
{
    // Handle both category/block-name and block-name formats
    if (strpos($block_name, '/') !== false) {
        $block_path = get_stylesheet_directory() . '/src/blocks/' . $block_name;
    } else {
        $block_path = get_stylesheet_directory() . '/src/blocks/' . $block_name;
    }
    $styles_path = $block_path . '/styles/';

    if (! is_dir($styles_path)) {
        return '';
    }

    $breakpoints  = carbon_blocks_get_breakpoints();
    $compiled_css = '';

    foreach ($breakpoints as $breakpoint => $media_query) {
        $css_file = $styles_path . $breakpoint . '.css';

        if (file_exists($css_file)) {
            $css_content = file_get_contents($css_file);
            $css_content = trim($css_content);

            if (! empty($css_content)) {
                if (empty($media_query)) {
                    // LG breakpoint - no media query wrapper
                    $compiled_css .= $css_content . "\n";
                } else {
                    // Wrap with media query
                    $compiled_css .= $media_query . " {\n" . $css_content . "\n}\n";
                }
            }
        }
    }

    return $compiled_css;
}

/**
 * Compile JavaScript for a Carbon Block
 *
 * @param string $block_name The block directory name
 * @return string Compiled JavaScript
 */
function carbon_blocks_compile_scripts($block_name)
{
    // Handle both category/block-name and block-name formats
    if (strpos($block_name, '/') !== false) {
        $block_path = get_stylesheet_directory() . '/src/blocks/' . $block_name;
    } else {
        $block_path = get_stylesheet_directory() . '/src/blocks/' . $block_name;
    }
    $scripts_path = $block_path . '/scripts/';

    if (! is_dir($scripts_path)) {
        return '';
    }

    $compiled_js = '';
    $js_files    = glob($scripts_path . '*.js');

    foreach ($js_files as $js_file) {
        $js_content = file_get_contents($js_file);
        $compiled_js .= trim($js_content) . "\n";
    }

    return $compiled_js;
}

/**
 * Create block context for Timber rendering
 *
 * @param string $block_name The block directory name
 * @param array $fields Carbon Fields data
 * @param array $additional_context Additional context data
 * @return array Block context
 */
function carbon_blocks_create_context($block_name, $fields = [], $additional_context = [])
{
    // Extract just the component name for CSS classes (remove category/)
    $component_name = strpos($block_name, '/') !== false
    ? basename($block_name)
    : $block_name;

    $context = [
        'block'  => [
            'name'      => $component_name,
            'css_class' => 'carbon-block carbon-block--' . $component_name,
            'styles'    => carbon_blocks_compile_styles($block_name),
            'scripts'   => carbon_blocks_compile_scripts($block_name),
        ],
        'fields' => $fields,
    ];

    return array_merge($context, $additional_context);
}

/**
 * Render a Carbon Block with Timber
 *
 * @param string $block_name The block directory name
 * @param array $fields Carbon Fields data
 * @param array $additional_context Additional context data
 * @param bool $echo Whether to echo output or return it
 * @return string|void Rendered block HTML or echoed output
 */
function carbon_blocks_render($block_name, $fields = [], $additional_context = [], $echo = false)
{
    $context = carbon_blocks_create_context($block_name, $fields, $additional_context);

    // Template path should include category if present
    $template_path = '@blocks/' . $block_name . '/block.twig';

    if ($echo) {
        Timber::render($template_path, $context);
    } else {
        return Timber::compile($template_path, $context);
    }
}

/**
 * Render a Carbon Gutenberg Block with Timber (echoes output)
 *
 * @param string $block_name The block directory name
 * @param array $fields Carbon Fields data from Gutenberg block
 * @param array $attributes Block attributes
 * @param string $inner_blocks Inner block content
 */
function carbon_blocks_render_gutenberg($block_name, $fields = [], $attributes = [], $inner_blocks = '')
{
    $additional_context = [
        'attributes'   => $attributes,
        'inner_blocks' => $inner_blocks,
    ];

    carbon_blocks_render($block_name, $fields, $additional_context, true);
}
