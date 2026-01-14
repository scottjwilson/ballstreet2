<?php get_header(); ?>

<main class="site-main">
    <section class="page-section">
        <div class="container">
            <?php 
            while (have_posts()) {
                the_post(); 
                ?>
                <h1 class="page-title"><?php the_title(); ?></h1>
                <div class="page-content">
                    <?php the_content(); ?>
                </div>
                <?php 
            }
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>