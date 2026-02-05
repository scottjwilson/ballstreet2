<?php
/**
 * Template Part: Article Row
 *
 * @package BallStreet
 *
 * Expected variables:
 * @var WP_Post $post The post object (uses global if not set)
 * @var bool $show_thumbnail Whether to show thumbnail
 */

$show_thumbnail = $show_thumbnail ?? false;

$category = get_the_category();
$cat_name = !empty($category) ? strtoupper($category[0]->name) : 'NEWS';
$read_time = ballstreet_get_read_time(get_the_content());
$is_hot = get_post_meta(get_the_ID(), 'is_hot', true);
?>

<article class="article-row <?php echo $show_thumbnail ? 'article-row-with-image' : ''; ?> fade-in">
    <a href="<?php the_permalink(); ?>">
        <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
            <div class="article-thumbnail">
                <?php the_post_thumbnail('ballstreet-thumb'); ?>
            </div>
        <?php endif; ?>
        <span class="article-category"><?php echo esc_html($cat_name); ?></span>
        <h3 class="article-title">
            <?php the_title(); ?>
            <?php if ($is_hot) : ?>
                <span class="hot-badge">HOT</span>
            <?php endif; ?>
        </h3>
        <span class="article-time"><?php echo esc_html($read_time); ?> min read</span>
    </a>
</article>
