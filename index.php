<?php
/**
 * Main Template File
 *
 * The blog/news listing page
 *
 * @package BallStreet
 */

get_header(); ?>

<div class="section-header fade-in">
    <h1 class="section-title">
        <span class="icon">📰</span>
        <?php if (is_category()) {
            single_cat_title("");
        } elseif (is_tag()) {
            single_tag_title("");
        } elseif (is_search()) {
            printf(
                __("Search Results for: %s", "ballstreet"),
                get_search_query(),
            );
        } else {
            _e("Latest News", "ballstreet");
        } ?>
    </h1>
</div>

<?php if (have_posts()): ?>
    <div class="articles-section">
        <?php while (have_posts()):
            the_post(); ?>
            <article class="article-row fade-in">
                <a href="<?php the_permalink(); ?>">
                    <?php
                    $category = get_the_category();
                    $cat_name = !empty($category)
                        ? strtoupper($category[0]->name)
                        : "NEWS";
                    $read_time = ballstreet_get_read_time(get_the_content());
                    ?>
                    <span class="article-category"><?php echo esc_html(
                        $cat_name,
                    ); ?></span>
                    <h3 class="article-title"><?php the_title(); ?></h3>
                    <span class="article-time"><?php echo esc_html(
                        $read_time,
                    ); ?> min read</span>
                </a>
            </article>
        <?php
        endwhile; ?>
    </div>

    <nav class="pagination">
        <?php the_posts_pagination([
            "mid_size" => 2,
            "prev_text" => "← Previous",
            "next_text" => "Next →",
        ]); ?>
    </nav>

<?php else: ?>
    <div class="empty-state fade-in">
        <div class="empty-state-icon">📭</div>
        <h2 class="empty-state-title">Nothing Found</h2>
        <p class="empty-state-text">We couldn't find what you're looking for.</p>
        <a href="<?php echo esc_url(
            home_url("/"),
        ); ?>" class="btn btn-primary" style="margin-top: 20px;">Back to Home</a>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
