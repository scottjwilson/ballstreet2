<?php
/**
 * Deal Archive Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue archive styles
wp_enqueue_style('ballstreet-archive', BALLSTREET_URI . '/css/archive.css', ['ballstreet-base'], BALLSTREET_VERSION);

$total_deals = wp_count_posts('deal')->publish;

// Get deal types for filtering
$deal_types = get_terms([
    'taxonomy' => 'deal_type',
    'hide_empty' => true,
]);
?>

<header class="archive-header fade-in">
    <h1 class="archive-title">Deals & Contracts</h1>
    <p class="archive-description">Track the latest NIL deals, contract extensions, trades, and free agent signings across all sports.</p>
    <p class="archive-count"><?php echo number_format($total_deals); ?> deals tracked</p>
</header>

<?php if (!empty($deal_types) && !is_wp_error($deal_types)) : ?>
    <div class="archive-filters fade-in">
        <a href="<?php echo get_post_type_archive_link('deal'); ?>" class="archive-filter <?php echo !is_tax('deal_type') ? 'active' : ''; ?>">All Deals</a>
        <?php foreach ($deal_types as $type) : ?>
            <a href="<?php echo get_term_link($type); ?>" class="archive-filter <?php echo is_tax('deal_type', $type->slug) ? 'active' : ''; ?>">
                <?php echo esc_html($type->name); ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (have_posts()) : ?>
    <div class="deals-grid">
        <?php
        $delay = 1;
        while (have_posts()) : the_post();
            $deal = get_post();
            $animation_class = 'fade-in fade-in-delay-' . min($delay, 5);
            include get_template_directory() . '/template-parts/deal-card.php';
            $delay++;
        endwhile;
        ?>
    </div>

    <nav class="pagination">
        <?php
        the_posts_pagination([
            'mid_size' => 2,
            'prev_text' => '← Previous',
            'next_text' => 'Next →',
        ]);
        ?>
    </nav>

<?php else : ?>
    <div class="empty-state fade-in">
        <div class="empty-state-icon">💰</div>
        <h2 class="empty-state-title">No Deals Found</h2>
        <p class="empty-state-text">Check back soon for the latest contracts, NIL deals, and trades.</p>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
