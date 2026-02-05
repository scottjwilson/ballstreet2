<?php
/**
 * Default Page Template
 *
 * @package BallStreet
 */

get_header();

// Enqueue single post styles for content formatting
wp_enqueue_style(
    "ballstreet-single",
    BALLSTREET_URI . "/css/single.css",
    ["ballstreet-base"],
    BALLSTREET_VERSION,
);
?>

<?php while (have_posts()):
    the_post(); ?>

<article class="page-content">
    <!-- Page Header -->
    <header class="article-header fade-in">
        <h1 class="article-header-title"><?php the_title(); ?></h1>
        <?php if (has_excerpt()): ?>
            <p class="article-header-excerpt"><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </header>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()): ?>
        <div class="article-featured-image fade-in fade-in-delay-1">
            <?php the_post_thumbnail("ballstreet-hero"); ?>
        </div>
    <?php endif; ?>

    <!-- Page Content -->
    <div class="article-content fade-in fade-in-delay-2">
        <?php the_content(); ?>
    </div>
</article>

<?php
endwhile; ?>

<?php get_footer(); ?>
