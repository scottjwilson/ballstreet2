<?php
/**
 * Template Part: Sidebar Card
 *
 * @package BallStreet
 *
 * Expected variables:
 * @var WP_Post $post The post object (uses global if not set)
 * @var string $animation_class Optional animation class
 */

$animation_class = $animation_class ?? 'fade-in';

$category = get_the_category();
$cat_slug = !empty($category) ? $category[0]->slug : 'nil';
$cat_name = !empty($category) ? strtoupper($category[0]->name) : 'NEWS';
$badge_class = ballstreet_get_category_class($cat_slug);
$time_ago = human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
?>

<article class="sidebar-card <?php echo esc_attr($animation_class); ?>">
    <a href="<?php the_permalink(); ?>">
        <div class="sidebar-badge <?php echo esc_attr($badge_class); ?>">
            <span class="dot"></span>
            <?php echo esc_html($cat_name); ?>
        </div>
        <h3 class="sidebar-title"><?php the_title(); ?></h3>
        <p class="sidebar-meta"><?php echo esc_html($time_ago); ?></p>
    </a>
</article>
