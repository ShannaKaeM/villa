<?php
/**
 * Default Card Template (for posts/articles)
 */

// Get categories
$categories = get_the_category();
$author_id = get_the_author_meta('ID');
?>

<article class="card card--post">
    <?php if (has_post_thumbnail()) : ?>
        <div class="card__image">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('medium_large'); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="card__content">
        <?php if (!empty($categories)) : ?>
            <div class="card__meta">
                <span class="card__category">
                    <?php echo esc_html($categories[0]->name); ?>
                </span>
                <span class="card__date">
                    <?php echo get_the_date(); ?>
                </span>
            </div>
        <?php endif; ?>
        
        <h3 class="card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <?php if (has_excerpt()) : ?>
            <p class="card__description"><?php echo get_the_excerpt(); ?></p>
        <?php else : ?>
            <p class="card__description"><?php echo wp_trim_words(get_the_content(), 20); ?></p>
        <?php endif; ?>
        
        <div class="card__author">
            <div class="card__author-avatar">
                <?php echo get_avatar($author_id, 32); ?>
            </div>
            <div class="card__author-info">
                <span class="card__author-name"><?php the_author(); ?></span>
                <span class="card__read-time"><?php echo mi_get_reading_time(); ?> min read</span>
            </div>
        </div>
    </div>
</article>
