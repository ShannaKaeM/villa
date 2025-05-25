<?php
/**
 * The main template file
 *
 * @package mi-condotel
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container">
        <?php
        if (have_posts()) :
            
            if (is_home() && !is_front_page()) :
                ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;
            
            // Start the Loop
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php
                        if (is_singular()) :
                            the_title('<h1 class="entry-title">', '</h1>');
                        else :
                            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                        endif;
                        ?>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="post-thumbnail">
                            <?php the_post_thumbnail(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content">
                        <?php
                        if (is_singular()) :
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'mi-condotel'),
                                'after'  => '</div>',
                            ));
                        else :
                            the_excerpt();
                        endif;
                        ?>
                    </div>
                </article>
                
                <?php
            endwhile;
            
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'mi-condotel'),
                'next_text' => __('Next', 'mi-condotel'),
            ));
            
        else :
            ?>
            <p><?php esc_html_e('Sorry, no posts matched your criteria.', 'mi-condotel'); ?></p>
            <?php
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
