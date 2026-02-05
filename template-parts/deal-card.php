<?php
/**
 * Template Part: Deal Card
 *
 * @package BallStreet
 *
 * Expected variables:
 * @var WP_Post $deal The deal post object
 * @var string $animation_class Optional fade-in class
 */

$deal = $deal ?? get_post();
$animation_class = $animation_class ?? '';

// Get deal meta (ACF fields or post meta)
$deal_value = get_field('deal_value', $deal->ID) ?: get_post_meta($deal->ID, 'deal_value', true);
$deal_trend = get_field('deal_trend', $deal->ID) ?: get_post_meta($deal->ID, 'deal_trend', true) ?: 'up';
$deal_trend_percent = get_field('deal_trend_percent', $deal->ID) ?: get_post_meta($deal->ID, 'deal_trend_percent', true);
$deal_details = get_field('deal_details', $deal->ID) ?: get_the_excerpt($deal);
$deal_tags = get_field('deal_tags', $deal->ID) ?: [];
$deal_player = get_field('deal_player', $deal->ID);

// Get deal type taxonomy
$deal_types = get_the_terms($deal->ID, 'deal_type');
$deal_type = !empty($deal_types) ? $deal_types[0]->name : 'Deal';
$deal_class = ballstreet_get_deal_class($deal_type);

// Format values
$formatted_value = ballstreet_format_value($deal_value);
$player_name = $deal_player ? get_the_title($deal_player) : get_the_title($deal);
$arrow = $deal_trend === 'up' ? '↑' : '↓';
?>

<article class="deal-card <?php echo esc_attr($deal_class); ?> <?php echo esc_attr($animation_class); ?>">
    <a href="<?php echo get_permalink($deal); ?>">
        <div class="deal-header">
            <span class="deal-type <?php echo esc_attr($deal_class); ?>"><?php echo esc_html(strtoupper($deal_type)); ?></span>
            <?php if ($deal_trend_percent) : ?>
                <span class="deal-trend <?php echo esc_attr($deal_trend); ?>"><?php echo $arrow; ?> <?php echo esc_html($deal_trend_percent); ?>%</span>
            <?php endif; ?>
        </div>
        <h3 class="deal-player"><?php echo esc_html($player_name); ?></h3>
        <div class="deal-amount"><?php echo esc_html($formatted_value); ?></div>
        <?php if ($deal_details) : ?>
            <p class="deal-details"><?php echo esc_html(wp_trim_words($deal_details, 15)); ?></p>
        <?php endif; ?>
        <?php if (!empty($deal_tags)) : ?>
            <div class="deal-tags">
                <?php foreach ($deal_tags as $tag) :
                    $tag_text = is_array($tag) ? $tag['tag'] : $tag;
                ?>
                    <span class="deal-tag"><?php echo esc_html($tag_text); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </a>
</article>
