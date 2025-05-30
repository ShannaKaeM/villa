<?php
/**
 * The template for displaying all single posts
 *
 * @package miGV
 * @version 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                    </div>
                <?php endif; ?>

                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                    <?php if ('post' === get_post_type()) : ?>
                        <div class="entry-meta">
                            <span class="posted-on">
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                <?php if (get_the_time('U') !== get_the_modified_time('U')) : ?>
                                    <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                                        <?php echo esc_html(get_the_modified_date()); ?>
                                    </time>
                                <?php endif; ?>
                            </span>
                            <span class="byline">
                                <?php _e('by', 'migv'); ?>
                                <span class="author vcard">
                                    <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </span>
                            </span>
                            <?php
                            $categories_list = get_the_category_list(esc_html__(', ', 'migv'));
                            if ($categories_list) :
                                ?>
                                <span class="cat-links">
                                    <?php printf(esc_html__('in %1$s', 'migv'), $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="entry-content">
                    <?php
                    the_content(
                        sprintf(
                            wp_kses(
                                /* translators: %s: Name of current post. Only visible to screen readers */
                                __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'migv'),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post(get_the_title())
                        )
                    );

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'migv'),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div>

                <footer class="entry-footer">
                    <?php
                    $tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'migv'));
                    if ($tags_list) :
                        ?>
                        <span class="tags-links">
                            <?php printf(esc_html__('Tagged %1$s', 'migv'), $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </span>
                    <?php endif; ?>

                    <?php if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) : ?>
                        <span class="comments-link">
                            <?php
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
                            ?>
                        </span>
                    <?php endif; ?>

                    <?php
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
                    ?>
                </footer>
            </article>

            <?php
            the_post_navigation(
                array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'migv') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'migv') . '</span> <span class="nav-title">%title</span>',
                )
            );

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>
