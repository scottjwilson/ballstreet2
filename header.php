<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<script>
(function(){var t=localStorage.getItem("ballstreet-theme");if(t){document.documentElement.setAttribute("data-theme",t)}else if(window.matchMedia("(prefers-color-scheme:light)").matches){document.documentElement.setAttribute("data-theme","light")}else{document.documentElement.setAttribute("data-theme","dark")}})();
</script>
<?php wp_body_open(); ?>

<!-- LIVE TICKER -->
<div class="ticker-bar">
    <div class="ticker-wrapper">
        <?php ballstreet_render_ticker(); ?>
    </div>
</div>

<!-- HEADER -->
<header class="header">
    <div class="header-inner">
        <a href="<?php echo esc_url(home_url("/")); ?>" class="logo">
            <span class="logo-mark">Ball Street</span>
            <span class="logo-text">Sports Journal</span>
        </a>

        <nav class="nav">
            <a href="<?php echo esc_url(
                home_url("/news"),
            ); ?>" class="nav-link">News</a>
            <a href="<?php echo esc_url(
                home_url("/deals"),
            ); ?>" class="nav-link">Deals</a>
            <a href="<?php echo esc_url(
                home_url("/athletes"),
            ); ?>" class="nav-link">Athletes</a>

            <!-- Theme Toggle -->
            <button class="theme-toggle" type="button" aria-label="Switch to light mode">
                <span class="icon-sun"><?php echo ballstreet_icon(
                    "sun",
                    20,
                ); ?></span>
                <span class="icon-moon"><?php echo ballstreet_icon(
                    "moon",
                    20,
                ); ?></span>
            </button>

            <a href="<?php echo esc_url(
                home_url("/subscribe"),
            ); ?>" class="nav-cta">Subscribe</a>
        </nav>

    </div>
</header>

<main class="main">
