<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo("charset"); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
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
                home_url("/category/nil-deals"),
            ); ?>" class="nav-link">NIL Deals</a>
            <a href="<?php echo esc_url(
                home_url("/category/contracts"),
            ); ?>" class="nav-link">Contracts</a>
            <a href="<?php echo esc_url(
                home_url("/category/betting"),
            ); ?>" class="nav-link">Betting</a>
            <a href="<?php echo esc_url(
                home_url("/category/analysis"),
            ); ?>" class="nav-link">Analysis</a>
            <a href="<?php echo esc_url(
                home_url("/athletes"),
            ); ?>" class="nav-link">Database</a>

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

        <!-- Mobile Menu Toggle -->
        <button class="menu-toggle" aria-expanded="false" aria-label="Toggle menu">
            <span class="icon-menu"><?php echo ballstreet_icon(
                "menu",
                24,
            ); ?></span>
            <span class="icon-close"><?php echo ballstreet_icon(
                "close",
                24,
            ); ?></span>
        </button>
    </div>
</header>

<!-- Mobile Navigation Overlay -->
<div class="nav-overlay"></div>

<!-- Mobile Navigation -->
<nav class="nav-mobile">
    <a href="<?php echo esc_url(
        home_url("/category/nil-deals"),
    ); ?>" class="nav-link">NIL Deals</a>
    <a href="<?php echo esc_url(
        home_url("/category/contracts"),
    ); ?>" class="nav-link">Contracts</a>
    <a href="<?php echo esc_url(
        home_url("/category/betting"),
    ); ?>" class="nav-link">Betting</a>
    <a href="<?php echo esc_url(
        home_url("/category/analysis"),
    ); ?>" class="nav-link">Analysis</a>
    <a href="<?php echo esc_url(
        home_url("/athletes"),
    ); ?>" class="nav-link">Database</a>

    <!-- Theme Toggle (Mobile) -->
    <button class="theme-toggle theme-toggle-mobile" type="button" aria-label="Switch to light mode">
        <span class="icon-sun"><?php echo ballstreet_icon("sun", 20); ?></span>
        <span class="icon-moon"><?php echo ballstreet_icon(
            "moon",
            20,
        ); ?></span>
        <span class="theme-toggle-label">Toggle Theme</span>
    </button>

    <a href="<?php echo esc_url(
        home_url("/subscribe"),
    ); ?>" class="nav-cta">Subscribe</a>
</nav>

<main class="main">
