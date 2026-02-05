<?php
/**
 * Single Deal Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue styles
wp_enqueue_style(
    "ballstreet-single-deal",
    BALLSTREET_URI . "/css/single-deal.css",
    ["ballstreet-base"],
    BALLSTREET_VERSION
);

while (have_posts()):
    the_post();
    $deal_id = get_the_ID();

    // Get deal fields
    $deal_value = get_field("deal_value", $deal_id) ?: 0;
    $deal_trend = get_field("deal_trend", $deal_id) ?: "up";
    $deal_trend_percent = get_field("deal_trend_percent", $deal_id) ?: "";
    $deal_details = get_field("deal_details", $deal_id) ?: "";
    $deal_duration = get_field("deal_duration", $deal_id) ?: "";
    $deal_guaranteed = get_field("deal_guaranteed", $deal_id) ?: 0;
    $deal_tags_raw = get_field("deal_tags", $deal_id) ?: "";
    $deal_date = get_the_date("F j, Y");

    // Get deal type from taxonomy
    $deal_types = get_the_terms($deal_id, "deal_type");
    $deal_type = !empty($deal_types) ? $deal_types[0]->name : "Deal";
    $deal_type_class = ballstreet_get_deal_class($deal_type);

    // Get linked athlete
    $athlete = get_field("deal_athlete", $deal_id);
    $athlete_id = null;
    $athlete_name = "";
    $athlete_position = "";
    $athlete_school = "";
    $athlete_thumbnail = "";

    if ($athlete) {
        if (is_array($athlete) && !empty($athlete[0])) {
            $athlete = $athlete[0];
        }
        if (is_object($athlete) && isset($athlete->ID)) {
            $athlete_id = $athlete->ID;
        } elseif (is_numeric($athlete)) {
            $athlete_id = $athlete;
        }

        if ($athlete_id) {
            $athlete_name = get_the_title($athlete_id);
            $athlete_position = get_field("position", $athlete_id) ?: "";

            // Get athlete's school
            $school = get_field("school", $athlete_id);
            if ($school) {
                if (is_array($school)) $school = $school[0];
                if (is_object($school)) {
                    $athlete_school = $school->post_title;
                } elseif (is_numeric($school)) {
                    $athlete_school = get_the_title($school);
                }
            }

            if (has_post_thumbnail($athlete_id)) {
                $athlete_thumbnail = get_the_post_thumbnail_url($athlete_id, "medium");
            }
        }
    }

    // Fallback to deal title if no athlete
    if (empty($athlete_name)) {
        $athlete_name = get_the_title($deal_id);
    }

    // Parse tags
    $tags = [];
    if ($deal_tags_raw) {
        $tags = array_map("trim", explode(",", $deal_tags_raw));
    }

    $arrow = $deal_trend === "up" ? "↑" : "↓";
    $formatted_value = ballstreet_format_value($deal_value);
    $formatted_guaranteed = $deal_guaranteed ? ballstreet_format_value($deal_guaranteed) : "";
?>

<article class="single-deal <?php echo esc_attr($deal_type_class); ?>">

    <!-- Deal Header -->
    <header class="deal-header fade-in">
        <div class="deal-header-top">
            <span class="deal-type-badge <?php echo esc_attr($deal_type_class); ?>">
                <?php echo esc_html(strtoupper($deal_type)); ?>
            </span>
            <?php if ($deal_trend_percent): ?>
            <span class="deal-trend-badge <?php echo esc_attr($deal_trend); ?>">
                <?php echo $arrow; ?> <?php echo esc_html($deal_trend_percent); ?>%
            </span>
            <?php endif; ?>
        </div>

        <div class="deal-header-main">
            <?php if ($athlete_thumbnail): ?>
            <div class="deal-athlete-avatar">
                <img src="<?php echo esc_url($athlete_thumbnail); ?>" alt="<?php echo esc_attr($athlete_name); ?>">
            </div>
            <?php elseif ($athlete_name): ?>
            <div class="deal-athlete-avatar deal-athlete-initials">
                <?php echo esc_html(substr($athlete_name, 0, 2)); ?>
            </div>
            <?php endif; ?>

            <div class="deal-header-info">
                <h1 class="deal-athlete-name"><?php echo esc_html($athlete_name); ?></h1>
                <?php if ($athlete_position || $athlete_school): ?>
                <p class="deal-athlete-meta">
                    <?php if ($athlete_position): ?>
                        <span><?php echo esc_html($athlete_position); ?></span>
                    <?php endif; ?>
                    <?php if ($athlete_position && $athlete_school): ?>
                        <span class="sep">·</span>
                    <?php endif; ?>
                    <?php if ($athlete_school): ?>
                        <span><?php echo esc_html($athlete_school); ?></span>
                    <?php endif; ?>
                </p>
                <?php endif; ?>
            </div>
        </div>

        <div class="deal-value-display">
            <span class="deal-value-amount"><?php echo esc_html($formatted_value); ?></span>
            <span class="deal-value-label">Total Value</span>
        </div>
    </header>

    <!-- Deal Stats -->
    <section class="deal-stats fade-in fade-in-delay-1">
        <?php if ($deal_duration): ?>
        <div class="deal-stat">
            <span class="deal-stat-value"><?php echo esc_html($deal_duration); ?></span>
            <span class="deal-stat-label">Duration</span>
        </div>
        <?php endif; ?>

        <?php if ($formatted_guaranteed): ?>
        <div class="deal-stat">
            <span class="deal-stat-value"><?php echo esc_html($formatted_guaranteed); ?></span>
            <span class="deal-stat-label">Guaranteed</span>
        </div>
        <?php endif; ?>

        <div class="deal-stat">
            <span class="deal-stat-value"><?php echo esc_html($deal_date); ?></span>
            <span class="deal-stat-label">Announced</span>
        </div>

        <?php if ($deal_value && $deal_duration):
            // Try to calculate annual value
            preg_match('/(\d+)/', $deal_duration, $matches);
            if (!empty($matches[1])) {
                $years = intval($matches[1]);
                if ($years > 0) {
                    $annual = $deal_value / $years;
        ?>
        <div class="deal-stat">
            <span class="deal-stat-value"><?php echo ballstreet_format_value($annual); ?></span>
            <span class="deal-stat-label">Per Year</span>
        </div>
        <?php
                }
            }
        endif; ?>
    </section>

    <!-- Deal Details -->
    <?php if ($deal_details): ?>
    <section class="deal-details fade-in fade-in-delay-2">
        <h2 class="deal-section-title">Deal Details</h2>
        <p class="deal-details-text"><?php echo esc_html($deal_details); ?></p>
    </section>
    <?php endif; ?>

    <!-- Deal Content (from editor) -->
    <?php if (get_the_content()): ?>
    <section class="deal-content fade-in fade-in-delay-2">
        <h2 class="deal-section-title">Analysis</h2>
        <div class="deal-content-text">
            <?php the_content(); ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Tags -->
    <?php if (!empty($tags)): ?>
    <section class="deal-tags-section fade-in fade-in-delay-3">
        <div class="deal-tags">
            <?php foreach ($tags as $tag): ?>
                <span class="deal-tag"><?php echo esc_html($tag); ?></span>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Linked Athlete Card -->
    <?php if ($athlete_id): ?>
    <section class="deal-athlete-section fade-in fade-in-delay-3">
        <h2 class="deal-section-title">About the Athlete</h2>
        <a href="<?php echo get_permalink($athlete_id); ?>" class="deal-athlete-card">
            <?php if ($athlete_thumbnail): ?>
            <div class="deal-athlete-card-avatar">
                <img src="<?php echo esc_url($athlete_thumbnail); ?>" alt="<?php echo esc_attr($athlete_name); ?>">
            </div>
            <?php endif; ?>
            <div class="deal-athlete-card-info">
                <h3 class="deal-athlete-card-name"><?php echo esc_html($athlete_name); ?></h3>
                <?php if ($athlete_position || $athlete_school): ?>
                <p class="deal-athlete-card-meta">
                    <?php echo esc_html(implode(" · ", array_filter([$athlete_position, $athlete_school]))); ?>
                </p>
                <?php endif; ?>
            </div>
            <span class="deal-athlete-card-arrow">→</span>
        </a>
    </section>
    <?php endif; ?>

    <!-- Related Deals -->
    <?php
    $related_args = [
        "post_type" => "deal",
        "posts_per_page" => 3,
        "post__not_in" => [$deal_id],
        "orderby" => "date",
        "order" => "DESC",
    ];

    // Try to get deals of same type
    if (!empty($deal_types)) {
        $related_args["tax_query"] = [
            [
                "taxonomy" => "deal_type",
                "field" => "term_id",
                "terms" => $deal_types[0]->term_id,
            ],
        ];
    }

    $related = new WP_Query($related_args);

    if ($related->have_posts()):
    ?>
    <section class="deal-related fade-in fade-in-delay-4">
        <h2 class="deal-section-title">Related Deals</h2>
        <div class="deal-related-grid">
            <?php while ($related->have_posts()): $related->the_post();
                $rel_deal_id = get_the_ID();
                $rel_value = get_field("deal_value", $rel_deal_id) ?: 0;
                $rel_types = get_the_terms($rel_deal_id, "deal_type");
                $rel_type = !empty($rel_types) ? $rel_types[0]->name : "Deal";
                $rel_class = ballstreet_get_deal_class($rel_type);

                // Get athlete name
                $rel_athlete = get_field("deal_athlete", $rel_deal_id);
                $rel_athlete_name = "";
                if ($rel_athlete) {
                    if (is_array($rel_athlete)) $rel_athlete = $rel_athlete[0];
                    if (is_object($rel_athlete)) {
                        $rel_athlete_name = $rel_athlete->post_title;
                    } elseif (is_numeric($rel_athlete)) {
                        $rel_athlete_name = get_the_title($rel_athlete);
                    }
                }
                if (empty($rel_athlete_name)) {
                    $rel_athlete_name = get_the_title($rel_deal_id);
                }
            ?>
            <a href="<?php the_permalink(); ?>" class="deal-related-card <?php echo esc_attr($rel_class); ?>">
                <span class="deal-related-type <?php echo esc_attr($rel_class); ?>"><?php echo esc_html(strtoupper($rel_type)); ?></span>
                <h3 class="deal-related-name"><?php echo esc_html($rel_athlete_name); ?></h3>
                <span class="deal-related-value"><?php echo ballstreet_format_value($rel_value); ?></span>
            </a>
            <?php endwhile; ?>
        </div>
    </section>
    <?php
    wp_reset_postdata();
    endif;
    ?>

</article>

<?php
endwhile;

get_footer();
?>
