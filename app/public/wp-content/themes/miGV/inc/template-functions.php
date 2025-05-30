<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package miGV
 * @version 1.0.0
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function migv_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar-1')) {
        $classes[] = 'no-sidebar';
    }

    return $classes;
}
add_filter('body_class', 'migv_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function migv_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'migv_pingback_header');

/**
 * Custom template tags for this theme.
 */

if (!function_exists('migv_posted_on')) :
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function migv_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = sprintf(
            /* translators: %s: post date. */
            esc_html_x('Posted on %s', 'post date', 'migv'),
            '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('migv_posted_by')) :
    /**
     * Prints HTML with meta information for the current author.
     */
    function migv_posted_by() {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'migv'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
endif;

if (!function_exists('migv_entry_footer')) :
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function migv_entry_footer() {
        // Hide category and tag text for pages.
        if ('post' === get_post_type()) {
            /* translators: used between list items, there is a space after the comma */
            $categories_list = get_the_category_list(esc_html__(', ', 'migv'));
            if ($categories_list) {
                /* translators: 1: list of categories. */
                printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'migv') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }

            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'migv'));
            if ($tags_list) {
                /* translators: 1: list of tags. */
                printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'migv') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        }

        if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) {
            echo '<span class="comments-link">';
            comments_popup_link(
                sprintf(
                    wp_kses(
                        /* translators: %s: post title */
                        __('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'migv'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post(get_the_title())
                )
            );
            echo '</span>';
        }

        edit_post_link(
            sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">"%s"</span>', 'migv'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link">',
            '</span>'
        );
    }
endif;

if (!function_exists('wp_body_open')) :
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open() {
        do_action('wp_body_open');
    }
endif;

/**
 * Get theme color
 */
function migv_get_theme_color($color_name, $default = '') {
    $colors = array(
        'primary' => '#2563eb',
        'primary-light' => '#93c5fd',
        'primary-dark' => '#1d4ed8',
        'secondary' => '#7c3aed',
        'secondary-light' => '#c4b5fd',
        'secondary-dark' => '#6d28d9',
        'neutral' => '#6b7280',
        'neutral-light' => '#d1d5db',
        'neutral-dark' => '#374151',
        'success' => '#10b981',
        'warning' => '#f59e0b',
        'error' => '#ef4444',
    );

    return isset($colors[$color_name]) ? $colors[$color_name] : $default;
}

/**
 * Generate responsive image sizes
 */
function migv_responsive_image($attachment_id, $size = 'large', $class = '') {
    if (!$attachment_id) {
        return '';
    }

    $image = wp_get_attachment_image_src($attachment_id, $size);
    if (!$image) {
        return '';
    }

    $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
    $sizes = wp_get_attachment_image_sizes($attachment_id, $size);
    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

    $html = sprintf(
        '<img src="%s" srcset="%s" sizes="%s" alt="%s" class="%s" width="%d" height="%d">',
        esc_url($image[0]),
        esc_attr($srcset),
        esc_attr($sizes),
        esc_attr($alt),
        esc_attr($class),
        absint($image[1]),
        absint($image[2])
    );

    return $html;
}
