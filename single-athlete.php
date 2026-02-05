<?php
/**
 * Single Athlete Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue styles
if (defined("BALLSTREET_URI") && defined("BALLSTREET_VERSION")) {
    wp_enqueue_style(
        "ballstreet-single-athlete",
        BALLSTREET_URI . "/css/single-athlete.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
} else {
    wp_enqueue_style(
        "ballstreet-single-athlete",
        get_template_directory_uri() . "/css/single-athlete.css",
        [],
        "1.0.0",
    );
}

// Helper function to safely get ACF fields
if (!function_exists("ballstreet_get_field")) {
    function ballstreet_get_field($field_name, $post_id = false)
    {
        if (function_exists("get_field")) {
            return get_field($field_name, $post_id);
        }
        return get_post_meta($post_id ?: get_the_ID(), $field_name, true);
    }
}

// Ensure format value function exists
if (!function_exists("ballstreet_format_value")) {
    function ballstreet_format_value($value)
    {
        if (!$value) {
            return "—";
        }
        if ($value >= 1000000) {
            return "$" . number_format($value / 1000000, 1) . "M";
        } elseif ($value >= 1000) {
            return "$" . number_format($value / 1000, 0) . "K";
        }
        return "$" . number_format($value);
    }
}

// Ensure icon function exists
if (!function_exists("ballstreet_icon")) {
    function ballstreet_icon($name, $size = 20)
    {
        return '<span class="icon icon-' . esc_attr($name) . '"></span>';
    }
}

while (have_posts()):

    the_post();
    $athlete_id = get_the_ID();

    // Get athlete fields (with ACF fallback)
    $position = ballstreet_get_field("position", $athlete_id) ?: "";
    $class_year =
        ballstreet_get_field("class_year", $athlete_id) ?:
        ballstreet_get_field("year", $athlete_id) ?:
        "";
    $height = ballstreet_get_field("height", $athlete_id) ?: "";
    $weight = ballstreet_get_field("weight", $athlete_id) ?: "";
    $high_school = ballstreet_get_field("high_school", $athlete_id) ?: "";
    $high_school_location =
        ballstreet_get_field("high_school_location", $athlete_id) ?: "";
    $hometown = ballstreet_get_field("hometown", $athlete_id) ?: "";
    $nil_valuation =
        ballstreet_get_field("nil_valuation", $athlete_id) ?:
        ballstreet_get_field("valuation", $athlete_id) ?:
        0;
    $nil_trend = ballstreet_get_field("nil_trend", $athlete_id) ?: "up";
    $nil_trend_value =
        ballstreet_get_field("nil_trend_value", $athlete_id) ?: "12";
    $bio = ballstreet_get_field("bio", $athlete_id) ?: "";

    // Get school relationship
    $school = ballstreet_get_field("school", $athlete_id);
    $school_id = null;
    $school_name = "";
    if ($school) {
        if (is_object($school)) {
            $school_id = $school->ID;
        } elseif (is_array($school) && !empty($school)) {
            $school_id = is_object($school[0]) ? $school[0]->ID : $school[0];
        } else {
            $school_id = $school;
        }
        if ($school_id) {
            $school_name = get_the_title($school_id);
        }
    }

    // Get sponsors relationship
    $sponsors = ballstreet_get_field("sponsors", $athlete_id);
    $sponsor_data = [];
    if ($sponsors) {
        // Ensure sponsors is an array
        if (!is_array($sponsors)) {
            $sponsors = [$sponsors];
        }
        foreach ($sponsors as $sponsor) {
            if (!$sponsor) {
                continue;
            }
            $sponsor_id = is_object($sponsor) ? $sponsor->ID : $sponsor;
            if (!$sponsor_id) {
                continue;
            }
            $sponsor_data[] = [
                "id" => $sponsor_id,
                "name" => get_the_title($sponsor_id),
                "logo" => has_post_thumbnail($sponsor_id)
                    ? get_the_post_thumbnail($sponsor_id, "thumbnail", [
                        "class" => "sponsor-logo",
                    ])
                    : null,
                "permalink" => get_permalink($sponsor_id),
            ];
        }
    }

    // Get related posts (posts that have this athlete in their relationship field)
    $related_posts = [];
    $related_query = new WP_Query([
        "post_type" => "post",
        "posts_per_page" => 6,
        "meta_query" => [
            [
                "key" => "related_athletes",
                "value" => '"' . $athlete_id . '"',
                "compare" => "LIKE",
            ],
        ],
    ]);

    if ($related_query->have_posts()) {
        while ($related_query->have_posts()) {
            $related_query->the_post();
            $category = get_the_category();
            $post_content = get_the_content();
            $related_posts[] = [
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "permalink" => get_permalink(),
                "date" => get_the_date("M j, Y"),
                "category" => !empty($category) ? $category[0]->name : "News",
                "excerpt" => get_the_excerpt(),
                "thumbnail" => has_post_thumbnail()
                    ? get_the_post_thumbnail(get_the_ID(), "ballstreet-card", [
                        "class" => "related-post-image",
                    ])
                    : null,
                "read_time" => function_exists("ballstreet_get_read_time")
                    ? ballstreet_get_read_time($post_content)
                    : 5,
            ];
        }
        wp_reset_postdata();
    }

    // Build stats array
    $stats = [];
    if ($position) {
        $stats[] = ["label" => "Position", "value" => $position];
    }
    if ($class_year) {
        $stats[] = ["label" => "Class", "value" => $class_year];
    }
    if ($height) {
        $stats[] = ["label" => "Height", "value" => $height];
    }
    if ($weight) {
        $stats[] = ["label" => "Weight", "value" => $weight . " lbs"];
    }
    if ($hometown) {
        $stats[] = ["label" => "Hometown", "value" => $hometown];
    }
    if ($high_school) {
        $hs_value = $high_school;
        if ($high_school_location) {
            $hs_value .= ", " . $high_school_location;
        }
        $stats[] = ["label" => "High School", "value" => $hs_value];
    }
    ?>

<article class="athlete-profile">
    <!-- Hero Header -->
    <header class="athlete-hero">
        <div class="athlete-hero-bg"></div>
        <div class="athlete-hero-content">
            <div class="athlete-hero-grid">
                <!-- Photo -->
                <div class="athlete-photo-container fade-in">
                    <?php if (has_post_thumbnail()): ?>
                        <div class="athlete-photo">
                            <?php the_post_thumbnail("ballstreet-hero", [
                                "class" => "athlete-photo-img",
                            ]); ?>
                        </div>
                    <?php else: ?>
                        <div class="athlete-photo athlete-photo-placeholder">
                            <span class="athlete-initials"><?php echo esc_html(
                                strtoupper(substr(get_the_title(), 0, 2)),
                            ); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($position): ?>
                        <span class="athlete-position-badge"><?php echo esc_html(
                            $position,
                        ); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="athlete-hero-info fade-in fade-in-delay-1">
                    <div class="athlete-meta-tags">
                        <?php if ($school_name): ?>
                            <a href="<?php echo get_permalink(
                                $school_id,
                            ); ?>" class="athlete-school-tag">
                                <?php if (has_post_thumbnail($school_id)): ?>
                                    <?php echo get_the_post_thumbnail(
                                        $school_id,
                                        "thumbnail",
                                        ["class" => "school-tag-logo"],
                                    ); ?>
                                <?php endif; ?>
                                <?php echo esc_html($school_name); ?>
                            </a>
                        <?php endif; ?>
                        <?php if ($class_year): ?>
                            <span class="athlete-class-tag"><?php echo esc_html(
                                $class_year,
                            ); ?></span>
                        <?php endif; ?>
                    </div>

                    <h1 class="athlete-name"><?php the_title(); ?></h1>

                    <?php if ($nil_valuation): ?>
                        <div class="athlete-nil-display">
                            <div class="nil-main">
                                <span class="nil-label">NIL Valuation</span>
                                <span class="nil-value"><?php echo ballstreet_format_value(
                                    $nil_valuation,
                                ); ?></span>
                            </div>
                            <div class="nil-trend <?php echo esc_attr(
                                $nil_trend,
                            ); ?>">
                                <span class="trend-arrow"><?php echo $nil_trend ===
                                "up"
                                    ? "↑"
                                    : "↓"; ?></span>
                                <span class="trend-value"><?php echo esc_html(
                                    $nil_trend_value,
                                ); ?>%</span>
                                <span class="trend-label">vs last month</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quick Stats Row -->
                    <?php if (!empty($stats)): ?>
                        <div class="athlete-quick-stats">
                            <?php foreach (
                                array_slice($stats, 0, 4)
                                as $stat
                            ): ?>
                                <div class="quick-stat">
                                    <span class="quick-stat-value"><?php echo esc_html(
                                        $stat["value"],
                                    ); ?></span>
                                    <span class="quick-stat-label"><?php echo esc_html(
                                        $stat["label"],
                                    ); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="athlete-content">
        <div class="athlete-content-grid">
            <!-- Main Column -->
            <div class="athlete-main">
                <!-- Bio Section -->
                <?php if ($bio || get_the_content()): ?>
                    <section class="athlete-section fade-in">
                        <h2 class="section-title">About</h2>
                        <div class="athlete-bio">
                            <?php if ($bio) {
                                echo wpautop($bio);
                            } else {
                                the_content();
                            } ?>
                        </div>
                    </section>
                <?php endif; ?>

                <!-- Related Posts Section -->
                <?php if (!empty($related_posts)): ?>
                    <section class="athlete-section fade-in">
                        <div class="section-header">
                            <h2 class="section-title">Latest News</h2>
                            <span class="section-count"><?php echo count(
                                $related_posts,
                            ); ?> articles</span>
                        </div>

                        <div class="related-posts-grid">
                            <?php foreach (
                                $related_posts
                                as $index => $post
                            ): ?>
                                <article class="related-post-card <?php echo $index ===
                                0
                                    ? "featured"
                                    : ""; ?>">
                                    <a href="<?php echo esc_url(
                                        $post["permalink"],
                                    ); ?>" class="related-post-link">
                                        <?php if ($post["thumbnail"]): ?>
                                            <div class="related-post-thumbnail">
                                                <?php echo $post[
                                                    "thumbnail"
                                                ]; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="related-post-content">
                                            <span class="related-post-category"><?php echo esc_html(
                                                strtoupper($post["category"]),
                                            ); ?></span>
                                            <h3 class="related-post-title"><?php echo esc_html(
                                                $post["title"],
                                            ); ?></h3>
                                            <?php if (
                                                $index === 0 &&
                                                $post["excerpt"]
                                            ): ?>
                                                <p class="related-post-excerpt"><?php echo esc_html(
                                                    wp_trim_words(
                                                        $post["excerpt"],
                                                        20,
                                                    ),
                                                ); ?></p>
                                            <?php endif; ?>
                                            <div class="related-post-meta">
                                                <span><?php echo esc_html(
                                                    $post["date"],
                                                ); ?></span>
                                                <span>•</span>
                                                <span><?php echo esc_html(
                                                    $post["read_time"],
                                                ); ?> min read</span>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="athlete-sidebar">
                <!-- Sponsors Card -->
                <?php if (!empty($sponsor_data)): ?>
                    <div class="sidebar-card fade-in">
                        <h3 class="sidebar-card-title">
                            <span class="card-icon">💰</span>
                            NIL Partners
                        </h3>
                        <div class="sponsors-list">
                            <?php foreach ($sponsor_data as $sponsor): ?>
                                <a href="<?php echo esc_url(
                                    $sponsor["permalink"],
                                ); ?>" class="sponsor-item">
                                    <?php if ($sponsor["logo"]): ?>
                                        <?php echo $sponsor["logo"]; ?>
                                    <?php else: ?>
                                        <span class="sponsor-placeholder"><?php echo esc_html(
                                            substr($sponsor["name"], 0, 1),
                                        ); ?></span>
                                    <?php endif; ?>
                                    <span class="sponsor-name"><?php echo esc_html(
                                        $sponsor["name"],
                                    ); ?></span>
                                    <span class="sponsor-arrow"><?php echo ballstreet_icon(
                                        "arrow-right",
                                        16,
                                    ); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Stats Card -->
                <?php if (!empty($stats)): ?>
                    <div class="sidebar-card fade-in fade-in-delay-1">
                        <h3 class="sidebar-card-title">
                            <span class="card-icon">📊</span>
                            Player Info
                        </h3>
                        <dl class="stats-list">
                            <?php foreach ($stats as $stat): ?>
                                <div class="stat-row">
                                    <dt class="stat-label"><?php echo esc_html(
                                        $stat["label"],
                                    ); ?></dt>
                                    <dd class="stat-value"><?php echo esc_html(
                                        $stat["value"],
                                    ); ?></dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                <?php endif; ?>

                <!-- School Card -->
                <?php if ($school_id): ?>
                    <div class="sidebar-card fade-in fade-in-delay-2">
                        <h3 class="sidebar-card-title">
                            <span class="card-icon">🏫</span>
                            School
                        </h3>
                        <a href="<?php echo get_permalink(
                            $school_id,
                        ); ?>" class="school-card-link">
                            <div class="school-card-content">
                                <?php if (has_post_thumbnail($school_id)): ?>
                                    <?php echo get_the_post_thumbnail(
                                        $school_id,
                                        "thumbnail",
                                        ["class" => "school-card-logo"],
                                    ); ?>
                                <?php endif; ?>
                                <div class="school-card-info">
                                    <span class="school-card-name"><?php echo esc_html(
                                        $school_name,
                                    ); ?></span>
                                    <span class="school-card-action">View School Profile →</span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Share Card -->
                <div class="sidebar-card fade-in fade-in-delay-3">
                    <h3 class="sidebar-card-title">
                        <span class="card-icon">📤</span>
                        Share
                    </h3>
                    <div class="share-buttons">
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(
                            get_permalink(),
                        ); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                           target="_blank"
                           rel="noopener"
                           class="share-btn twitter">
                            𝕏 Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(
                            get_permalink(),
                        ); ?>"
                           target="_blank"
                           rel="noopener"
                           class="share-btn facebook">
                            Facebook
                        </a>
                        <button class="share-btn copy-link" data-url="<?php echo esc_url(
                            get_permalink(),
                        ); ?>">
                            Copy Link
                        </button>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Related Athletes -->
    <?php
    $related_athletes_query = new WP_Query([
        "post_type" => "athlete",
        "posts_per_page" => 4,
        "post__not_in" => [$athlete_id],
        "orderby" => "rand",
        "meta_query" => $school_id
            ? [
                [
                    "key" => "school",
                    "value" => $school_id,
                    "compare" => "LIKE",
                ],
            ]
            : [],
    ]);

    // If no school teammates, get random athletes
    if (!$related_athletes_query->have_posts()) {
        $related_athletes_query = new WP_Query([
            "post_type" => "athlete",
            "posts_per_page" => 4,
            "post__not_in" => [$athlete_id],
            "orderby" => "rand",
        ]);
    }

    if ($related_athletes_query->have_posts()): ?>
        <section class="related-athletes-section">
            <div class="container">
                <h2 class="section-title fade-in">More Athletes</h2>
                <div class="related-athletes-grid">
                    <?php while ($related_athletes_query->have_posts()):

                        $related_athletes_query->the_post();
                        $rel_id = get_the_ID();
                        $rel_position =
                            ballstreet_get_field("position", $rel_id) ?: "";
                        $rel_nil =
                            ballstreet_get_field("nil_valuation", $rel_id) ?: 0;
                        $rel_school = ballstreet_get_field("school", $rel_id);
                        $rel_school_name = $rel_school
                            ? (is_object($rel_school)
                                ? $rel_school->post_title
                                : get_the_title($rel_school))
                            : "";
                        ?>
                        <article class="related-athlete-card fade-in">
                            <a href="<?php the_permalink(); ?>" class="related-athlete-link">
                                <div class="related-athlete-photo">
                                    <?php if (has_post_thumbnail()): ?>
                                        <?php the_post_thumbnail(
                                            "ballstreet-card",
                                            ["class" => "related-athlete-img"],
                                        ); ?>
                                    <?php else: ?>
                                        <span class="related-athlete-initials"><?php echo esc_html(
                                            strtoupper(
                                                substr(get_the_title(), 0, 2),
                                            ),
                                        ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="related-athlete-info">
                                    <h3 class="related-athlete-name"><?php the_title(); ?></h3>
                                    <?php if ($rel_position): ?>
                                        <span class="related-athlete-position"><?php echo esc_html(
                                            $rel_position,
                                        ); ?></span>
                                    <?php endif; ?>
                                    <?php if ($rel_school_name): ?>
                                        <span class="related-athlete-school"><?php echo esc_html(
                                            $rel_school_name,
                                        ); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($rel_nil): ?>
                                    <div class="related-athlete-nil">
                                        <?php echo ballstreet_format_value(
                                            $rel_nil,
                                        ); ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </article>
                    <?php
                    endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif;
    ?>
</article>

<?php
endwhile;

get_footer();
?>
