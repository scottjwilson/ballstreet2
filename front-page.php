<?php
/**
 * Front Page Template
 *
 * The main homepage for Ball Street Sports Journal
 *
 * @package BallStreet
 */

get_header(); ?>

<!-- HERO SECTION -->
<section class="hero">
    <?php ballstreet_render_hero_article(); ?>

    <aside class="hero-sidebar">
        <?php ballstreet_render_sidebar_cards(3); ?>
    </aside>
</section>

<!-- DEALS SECTION -->
<section class="deals-section">
    <div class="section-header fade-in fade-in-delay-3">
        <h2 class="section-title">
            <span class="icon">💰</span>
            Today's Top Deals
        </h2>
        <a href="<?php echo esc_url(
            home_url("/deals"),
        ); ?>" class="section-link">
            View all deals
            <span>→</span>
        </a>
    </div>

    <div class="deals-grid">
        <?php ballstreet_render_deals_grid(3); ?>
    </div>
</section>

<!-- ARTICLES SECTION -->
<section class="articles-section">
    <div class="section-header fade-in">
        <h2 class="section-title">
            <span class="icon">📊</span>
            Latest Analysis
        </h2>
        <a href="<?php echo esc_url(
            home_url("/category/analysis"),
        ); ?>" class="section-link">
            View all articles
            <span>→</span>
        </a>
    </div>

    <?php ballstreet_render_article_rows(5); ?>
</section>

<!-- NEWSLETTER -->
<section class="newsletter fade-in">
    <div class="newsletter-content">
        <h2 class="newsletter-title">The Morning Briefing</h2>
        <p class="newsletter-desc">Get the day's biggest deals, contract moves, and market analysis delivered to your inbox before the markets open. Join 15,000+ sports business professionals.</p>
    </div>
    <form class="newsletter-form" action="<?php echo esc_url(
        admin_url("admin-post.php"),
    ); ?>" method="post">
        <input type="hidden" name="action" value="ballstreet_newsletter">
        <?php wp_nonce_field("ballstreet_newsletter", "newsletter_nonce"); ?>
        <input type="email" name="email" class="newsletter-input" placeholder="Enter your email" required>
        <button type="submit" class="newsletter-btn">Subscribe Free</button>
    </form>
</section>

<?php get_footer(); ?>
