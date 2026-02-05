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
        <div class="articles-list">
            <?php while (have_posts()):

                the_post();
                $category = get_the_category();
                $cat_name = !empty($category)
                    ? strtoupper($category[0]->name)
                    : "NEWS";
                $cat_slug = !empty($category) ? $category[0]->slug : "news";
                $cat_class = ballstreet_get_category_class($cat_slug);
                $read_time = ballstreet_get_read_time(get_the_content());
                $is_hot = get_post_meta(get_the_ID(), "is_hot", true);
                $has_thumbnail = has_post_thumbnail();
                $time_ago =
                    human_time_diff(
                        get_the_time("U"),
                        current_time("timestamp"),
                    ) . " ago";
                ?>
                <article class="article-row <?php echo $has_thumbnail
                    ? "has-thumbnail"
                    : ""; ?> fade-in">
                    <a href="<?php the_permalink(); ?>" class="article-row-link">
                        <?php if ($has_thumbnail): ?>
                        <div class="article-thumbnail">
                            <?php the_post_thumbnail("medium", [
                                "class" => "article-thumb-img",
                            ]); ?>
                        </div>
                        <?php endif; ?>
                        <div class="article-content">
                            <div class="article-meta-top">
                                <span class="article-category <?php echo esc_attr(
                                    $cat_class,
                                ); ?>"><?php echo esc_html($cat_name); ?></span>
                                <?php if ($is_hot): ?>
                                    <span class="hot-badge">HOT</span>
                                <?php endif; ?>
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
                                <span class="article-meta-sep">·</span>
                                <span class="article-read-time"><?php echo esc_html(
                                    $read_time,
                                ); ?> min read</span>
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
        <div class="empty-state-icon">📭</div>
        <h2 class="empty-state-title">Nothing Found</h2>
        <p class="empty-state-text">We couldn't find what you're looking for.</p>
        <a href="<?php echo esc_url(
            home_url("/"),
        ); ?>" class="btn btn-primary" style="margin-top: 20px;">Back to Home</a>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
