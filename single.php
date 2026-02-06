<?php
/**
 * Single Post Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue single post styles
wp_enqueue_style(
    "ballstreet-single",
    BALLSTREET_URI . "/css/single.css",
    ["ballstreet-base"],
    BALLSTREET_VERSION,
);

while (have_posts()):

    the_post();
    $category = get_the_category();
    $cat_name = !empty($category) ? strtoupper($category[0]->name) : "NEWS";
    $author = get_the_author();
    $read_time = ballstreet_get_read_time(get_the_content());
    $date = get_the_date("F j, Y");
    ?>

<article class="single-article">
    <!-- Article Header -->
    <header class="article-header fade-in">
        <span class="article-header-category"><?php echo esc_html(
            $cat_name,
        ); ?></span>
        <h1 class="article-header-title"><?php the_title(); ?></h1>

        <?php if (has_excerpt()): ?>
            <p class="article-header-excerpt"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>

        <div class="article-header-meta">
            <div class="article-author">
                <?php echo get_avatar(get_the_author_meta("ID"), 40, "", "", [
                    "class" => "article-author-avatar",
                ]); ?>
                <div>
                    <div class="article-author-name"><?php echo esc_html(
                        $author,
                    ); ?></div>
                </div>
            </div>
            <span><?php echo esc_html($date); ?></span>
            <span>•</span>
            <span><?php echo esc_html($read_time); ?> min read</span>
        </div>
    </header>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()): ?>
        <div class="article-featured-image fade-in fade-in-delay-1">
            <?php the_post_thumbnail("ballstreet-hero", [
                "fetchpriority" => "high",
                "loading" => false,
                "decoding" => "async",
            ]); ?>
        </div>
    <?php endif; ?>

    <!-- Article Content -->
    <div class="article-content fade-in fade-in-delay-2">
        <?php the_content(); ?>
    </div>

    <!-- Tags -->
    <?php
    $tags = get_the_tags();
    if ($tags): ?>
        <div class="article-tags">
            <?php foreach ($tags as $tag): ?>
                <a href="<?php echo get_tag_link(
                    $tag->term_id,
                ); ?>" class="article-tag"><?php echo esc_html(
    $tag->name,
); ?></a>
            <?php endforeach; ?>
        </div>
    <?php endif;
    ?>

    <!-- Related Articles -->
    <?php
    $related_args = [
        "post_type" => "post",
        "posts_per_page" => 3,
        "post__not_in" => [get_the_ID()],
        "orderby" => "rand",
    ];

    if (!empty($category)) {
        $related_args["cat"] = $category[0]->term_id;
    }

    $related = new WP_Query($related_args);

    if ($related->have_posts()): ?>
        <section class="related-articles">
            <h2 class="related-articles-title">Related Articles</h2>
            <div class="articles-section">
                <?php while ($related->have_posts()):
                    $related->the_post(); ?>
                    <?php
                    $rel_category = get_the_category();
                    $rel_cat_name = !empty($rel_category)
                        ? strtoupper($rel_category[0]->name)
                        : "NEWS";
                    $rel_read_time = ballstreet_get_read_time(
                        get_the_content(),
                    );
                    ?>
                    <article class="article-row fade-in">
                        <a href="<?php the_permalink(); ?>">
                            <span class="article-category"><?php echo esc_html(
                                $rel_cat_name,
                            ); ?></span>
                            <h3 class="article-title"><?php the_title(); ?></h3>
                            <span class="article-time"><?php echo esc_html(
                                $rel_read_time,
                            ); ?> min read</span>
                        </a>
                    </article>
                <?php
                endwhile; ?>
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
