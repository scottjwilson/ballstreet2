<?php get_header(); ?>

<main class="site-main">
    <div class="container">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h1>
                        <div class="entry-meta">
                            <span class="posted-on"><?php echo get_the_date('F j, Y'); ?></span>
                        </div>
                    </header>
                    <div class="entry-content">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium');
                        }
                        the_excerpt();
                        ?>
                    </div>
                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="read-more">Read More →</a>
                    </footer>
                </article>
            <?php endwhile; ?>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('← Previous', 'the-theme'),
                    'next_text' => __('Next →', 'the-theme'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('No content found.', 'the-theme'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
