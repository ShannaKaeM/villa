<?php
/**
 * Default Card Template (for posts/articles)
 */

// Get categories
$categories = get_the_category();
$author_id = get_the_author_meta('ID');
?>

<article class="m-card m-card--post">
    <?php if (has_post_thumbnail()) : ?>
        <div class="m-card__image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="m-card__content">
        <?php if (!empty($categories)) : ?>
            <div class="m-card__meta">
                <span class="m-card__category">
                    <?php echo esc_html($categories[0]->name); ?>
                </span>
                <span class="m-card__date">
                    <?php echo get_the_date(); ?>
                </span>
            </div>
        <?php endif; ?>
        
        <h3 class="m-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if (has_excerpt()) : ?>
            <p class="m-card__description"><?php echo get_the_excerpt(); ?></p>
        <?php else : ?>
            <p class="m-card__description"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        <?php endif; ?>
        
        <div class="m-card__author">
            <div class="m-card__author-avatar">
                <?php echo get_avatar($author_id, 32); ?>
            </div>
            <div class="m-card__author-info">
                <span class="m-card__author-name"><?php the_author(); ?></span>
                <span class="m-card__read-time"><?php echo mi_get_reading_time(); ?> min read</span>
            </div>
        </div>
    </div>
</article>
