<?php
/**
 * Search Results Template
 *
 * @package BallStreet
 */

get_header(); ?>

<div class="section-header fade-in">
    <h1 class="section-title">
        <?php printf(
            __('Search Results for: "%s"', "ballstreet"),
            esc_html(get_search_query()),
        ); ?>
    </h1>
    <p class="section-subtitle" style="color: var(--text-secondary); margin-top: 8px;">
        <?php printf(
            _n(
                "%d result found",
                "%d results found",
                (int) $wp_query->found_posts,
                "ballstreet",
            ),
            (int) $wp_query->found_posts,
        ); ?>
    </p>
</div>

<?php if (have_posts()): ?>
    <div class="articles-section">
        <div class="articles-list">
            <?php while (have_posts()):

                the_post();
                $post_type = get_post_type();
                $has_thumbnail = has_post_thumbnail();
                $time_ago =
                    human_time_diff(get_the_time("U"), time()) . " ago";

                // Determine badge based on post type
                if ($post_type === "athlete") {
                    $badge_name = "ATHLETE";
                    $badge_class = "nil";
                } elseif ($post_type === "deal") {
                    $badge_name = "DEAL";
                    $badge_class = "contracts";
                } elseif ($post_type === "school") {
                    $badge_name = "SCHOOL";
                    $badge_class = "betting";
                } elseif ($post_type === "sponsor") {
                    $badge_name = "SPONSOR";
                    $badge_class = "nil";
                } else {
                    $category = get_the_category();
                    $badge_name = !empty($category)
                        ? strtoupper($category[0]->name)
                        : "NEWS";
                    $cat_slug = !empty($category) ? $category[0]->slug : "news";
                    $badge_class = ballstreet_get_category_class($cat_slug);
                }
                ?>
                <article class="article-row <?php echo $has_thumbnail
                    ? "has-thumbnail"
                    : ""; ?> fade-in">
                    <a href="<?php the_permalink(); ?>" class="article-row-link">
                        <?php if ($has_thumbnail): ?>
                        <div class="article-thumbnail">
                            <?php the_post_thumbnail("medium", [
                                "class" => "article-thumb-img",
                                "loading" => "lazy",
                                "decoding" => "async",
                            ]); ?>
                        </div>
                        <?php endif; ?>
                        <div class="article-content">
                            <div class="article-meta-top">
                                <span class="article-category <?php echo esc_attr(
                                    $badge_class,
                                ); ?>"><?php echo esc_html($badge_name); ?></span>
                            </div>
                            <h3 class="article-title"><?php the_title(); ?></h3>
                            <?php if (has_excerpt()): ?>
                                <p class="article-excerpt"><?php echo wp_trim_words(
                                    get_the_excerpt(),
                                    20,
                                ); ?></p>
                            <?php endif; ?>
                            <div class="article-meta-bottom">
                                <span class="article-author"><?php echo get_the_author(); ?></span>
                                <span class="article-meta-sep">·</span>
                                <span class="article-time"><?php echo esc_html(
                                    $time_ago,
                                ); ?></span>
                            </div>
                        </div>
                        <div class="article-arrow">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                </article>
            <?php
            endwhile; ?>
        </div>
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
        <div class="empty-state-icon">🔍</div>
        <h2 class="empty-state-title">No Results Found</h2>
        <p class="empty-state-text">No results for "<?php echo esc_html(
            get_search_query(),
        ); ?>". Try a different search term.</p>
        <div style="margin-top: 24px;">
            <?php get_search_form(); ?>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
