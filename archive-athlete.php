<?php
/**
 * Athlete Archive Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue archive styles
wp_enqueue_style(
    "ballstreet-archive",
    BALLSTREET_URI . "/css/archive.css",
    ["ballstreet-base"],
    BALLSTREET_VERSION,
);
wp_enqueue_style(
    "ballstreet-athletes-table",
    BALLSTREET_URI . "/css/athletes-table.css",
    ["ballstreet-base"],
    BALLSTREET_VERSION,
);

$total_athletes = wp_count_posts("athlete")->publish;
?>

<header class="archive-header fade-in">
    <div class="archive-header-content">
        <h1 class="archive-title">Athlete Database</h1>
        <p class="archive-description">Browse NIL valuations, contracts, and profiles for college and professional athletes across all sports.</p>
    </div>
    <div class="archive-header-stats">
        <div class="stat-card">
            <span class="stat-value"><?php echo number_format(
                $total_athletes,
            ); ?></span>
            <span class="stat-label">Athletes Tracked</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">$2.8B</span>
            <span class="stat-label">Total NIL Value</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">150+</span>
            <span class="stat-label">Schools</span>
        </div>
    </div>
</header>

<?php if (have_posts()): ?>
    <section class="athletes-section">
        <?php ballstreet_render_athletes_table([
            "athletes" => null,
            "view" => "table",
            "show_rank" => true,
            "show_search" => true,
            "show_filters" => true,
            "show_view_toggle" => true,
        ]); ?>

        <nav class="pagination">
            <?php the_posts_pagination([
                "mid_size" => 2,
                "prev_text" => "← Previous",
                "next_text" => "Next →",
            ]); ?>
        </nav>
    </section>

<?php else: ?>
    <div class="empty-state fade-in">
        <div class="empty-state-icon">👤</div>
        <h2 class="empty-state-title">No Athletes Found</h2>
        <p class="empty-state-text">Check back soon for athlete profiles and NIL valuations.</p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
