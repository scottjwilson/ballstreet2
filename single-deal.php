<?php
/**
 * Single Deal Template
 */

get_header();

while (have_posts()):
    the_post();

    $deal_id = get_the_ID();
    $deal_value = get_field("deal_value", $deal_id) ?: 0;
    $deal_trend = get_field("deal_trend", $deal_id) ?: "up";
    $deal_trend_percent = get_field("deal_trend_percent", $deal_id) ?: "";
    $deal_details = get_field("deal_details", $deal_id) ?: "";
    $deal_duration = get_field("deal_duration", $deal_id) ?: "";
    $deal_guaranteed = get_field("deal_guaranteed", $deal_id) ?: 0;
    $deal_tags_raw = get_field("deal_tags", $deal_id) ?: "";
    $deal_date = get_the_date("F j, Y");

    // Get deal type
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

    if (empty($athlete_name)) {
        $athlete_name = get_the_title($deal_id);
    }

    $tags = $deal_tags_raw ? array_map("trim", explode(",", $deal_tags_raw)) : [];
    $arrow = $deal_trend === "up" ? "↑" : "↓";
    $formatted_value = ballstreet_format_value($deal_value);
    $formatted_guaranteed = $deal_guaranteed ? ballstreet_format_value($deal_guaranteed) : "";
?>

<div class="single-deal-page">
    <article class="deal-article <?php echo esc_attr($deal_type_class); ?>">

        <!-- Header -->
        <header class="deal-page-header">
            <div class="deal-badges">
                <span class="deal-badge <?php echo esc_attr($deal_type_class); ?>">
                    <?php echo esc_html(strtoupper($deal_type)); ?>
                </span>
                <?php if ($deal_trend_percent): ?>
                <span class="deal-trend <?php echo esc_attr($deal_trend); ?>">
                    <?php echo $arrow; ?> <?php echo esc_html($deal_trend_percent); ?>%
                </span>
                <?php endif; ?>
            </div>

            <?php if ($athlete_thumbnail): ?>
            <div class="deal-avatar">
                <img src="<?php echo esc_url($athlete_thumbnail); ?>" alt="<?php echo esc_attr($athlete_name); ?>">
            </div>
            <?php endif; ?>

            <h1 class="deal-name"><?php echo esc_html($athlete_name); ?></h1>

            <?php if ($athlete_position || $athlete_school): ?>
            <p class="deal-meta">
                <?php echo esc_html(implode(" · ", array_filter([$athlete_position, $athlete_school]))); ?>
            </p>
            <?php endif; ?>

            <div class="deal-value-box">
                <span class="deal-value"><?php echo esc_html($formatted_value); ?></span>
                <span class="deal-value-label">Total Value</span>
            </div>
        </header>

        <!-- Stats -->
        <div class="deal-stats-grid">
            <?php if ($deal_duration): ?>
            <div class="deal-stat-item">
                <span class="stat-value"><?php echo esc_html($deal_duration); ?></span>
                <span class="stat-label">Duration</span>
            </div>
            <?php endif; ?>

            <?php if ($formatted_guaranteed): ?>
            <div class="deal-stat-item">
                <span class="stat-value"><?php echo esc_html($formatted_guaranteed); ?></span>
                <span class="stat-label">Guaranteed</span>
            </div>
            <?php endif; ?>

            <div class="deal-stat-item">
                <span class="stat-value"><?php echo esc_html($deal_date); ?></span>
                <span class="stat-label">Announced</span>
            </div>
        </div>

        <!-- Details -->
        <?php if ($deal_details): ?>
        <div class="deal-details-box">
            <h2>Deal Details</h2>
            <p><?php echo esc_html($deal_details); ?></p>
        </div>
        <?php endif; ?>

        <!-- Content -->
        <?php if (get_the_content()): ?>
        <div class="deal-content-box">
            <h2>Analysis</h2>
            <div class="deal-content-text">
                <?php the_content(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tags -->
        <?php if (!empty($tags)): ?>
        <div class="deal-tags-box">
            <?php foreach ($tags as $tag): ?>
            <span class="deal-tag-item"><?php echo esc_html($tag); ?></span>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Athlete Card -->
        <?php if ($athlete_id): ?>
        <div class="deal-athlete-box">
            <h2>About the Athlete</h2>
            <a href="<?php echo get_permalink($athlete_id); ?>" class="athlete-link-card">
                <?php if ($athlete_thumbnail): ?>
                <img src="<?php echo esc_url($athlete_thumbnail); ?>" alt="<?php echo esc_attr($athlete_name); ?>" class="athlete-card-img">
                <?php endif; ?>
                <div class="athlete-card-info">
                    <span class="athlete-card-name"><?php echo esc_html($athlete_name); ?></span>
                    <span class="athlete-card-meta"><?php echo esc_html(implode(" · ", array_filter([$athlete_position, $athlete_school]))); ?></span>
                </div>
                <span class="athlete-card-arrow">→</span>
            </a>
        </div>
        <?php endif; ?>

    </article>
</div>

<?php
endwhile;
get_footer();
