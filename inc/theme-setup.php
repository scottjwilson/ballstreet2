<?php
/**
 * Theme Setup
 *
 * Core theme configuration, menus, and theme supports.
 */

defined("ABSPATH") || exit();

define("BALLSTREET_VERSION", "1.0.0");
define("BALLSTREET_DIR", get_template_directory());
define("BALLSTREET_URI", get_template_directory_uri());

/**
 * Register theme supports and navigation menus
 */
function ballstreet_setup(): void
{
    add_theme_support("automatic-feed-links");
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
    add_theme_support("custom-logo", [
        "height" => 40,
        "width" => 160,
        "flex-width" => true,
        "flex-height" => true,
    ]);
    add_theme_support("align-wide");
    add_theme_support("responsive-embeds");
    add_theme_support("html5", [
        "search-form",
        "comment-form",
        "comment-list",
        "gallery",
        "caption",
    ]);

    // Custom image sizes
    add_image_size("ballstreet-card", 600, 400, true);
    add_image_size("ballstreet-hero", 1200, 800, true);
    add_image_size("ballstreet-thumb", 300, 200, true);

    // Navigation menus
    register_nav_menus([
        "primary" => __("Primary Menu", "ballstreet"),
        "footer" => __("Footer Menu", "ballstreet"),
    ]);
}
add_action("after_setup_theme", "ballstreet_setup");

/**
 * Preload critical fonts (DM Sans is used for all body text)
 */
function ballstreet_preload_fonts(): void
{
    // Use hashed font path from Vite manifest when available
    $font_url = BALLSTREET_URI . "/fonts/dm-sans-latin.woff2";
    $manifest_path = get_theme_file_path("dist/.vite/manifest.json");

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (isset($manifest["fonts/dm-sans-latin.woff2"]["file"])) {
            $font_url =
                BALLSTREET_URI .
                "/dist/" .
                $manifest["fonts/dm-sans-latin.woff2"]["file"];
        }
    }

    echo '<link rel="preload" href="' .
        esc_url($font_url) .
        '" as="font" type="font/woff2" crossorigin>' .
        "\n";
}
add_action("wp_head", "ballstreet_preload_fonts", 1);

/**
 * Enqueue base styles and scripts
 */
function ballstreet_enqueue_assets(): void
{
    // Main stylesheet (required by WordPress)
    wp_enqueue_style(
        "ballstreet-style",
        get_stylesheet_uri(),
        [],
        BALLSTREET_VERSION,
    );

    // Check if Vite handles assets
    if (function_exists("ballstreet_detect_vite_server")) {
        $vite = ballstreet_detect_vite_server();
        $has_manifest = file_exists(
            get_theme_file_path("dist/.vite/manifest.json"),
        );

        if ($vite["running"] || $has_manifest) {
            return;
        }
    }

    // Fallback: enqueue CSS directly if Vite is not available
    wp_enqueue_style(
        "ballstreet-fonts",
        BALLSTREET_URI . "/css/fonts.css",
        [],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-variables",
        BALLSTREET_URI . "/css/variables.css",
        ["ballstreet-fonts"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-base",
        BALLSTREET_URI . "/css/base.css",
        ["ballstreet-variables"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-layout",
        BALLSTREET_URI . "/css/layout.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-header",
        BALLSTREET_URI . "/css/header.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-footer",
        BALLSTREET_URI . "/css/footer.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-ticker",
        BALLSTREET_URI . "/css/ticker.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-buttons",
        BALLSTREET_URI . "/css/buttons.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );
    wp_enqueue_style(
        "ballstreet-cards",
        BALLSTREET_URI . "/css/cards.css",
        ["ballstreet-base"],
        BALLSTREET_VERSION,
    );

    if (is_front_page()) {
        wp_enqueue_style(
            "ballstreet-hero",
            BALLSTREET_URI . "/css/hero.css",
            ["ballstreet-cards"],
            BALLSTREET_VERSION,
        );
        wp_enqueue_style(
            "ballstreet-deals",
            BALLSTREET_URI . "/css/deals.css",
            ["ballstreet-cards"],
            BALLSTREET_VERSION,
        );
        wp_enqueue_style(
            "ballstreet-articles",
            BALLSTREET_URI . "/css/articles.css",
            ["ballstreet-base"],
            BALLSTREET_VERSION,
        );
        wp_enqueue_style(
            "ballstreet-newsletter",
            BALLSTREET_URI . "/css/newsletter.css",
            ["ballstreet-base"],
            BALLSTREET_VERSION,
        );
        wp_enqueue_style(
            "ballstreet-front-page",
            BALLSTREET_URI . "/css/front-page.css",
            ["ballstreet-base"],
            BALLSTREET_VERSION,
        );
    }
}
add_action("wp_enqueue_scripts", "ballstreet_enqueue_assets");

/**
 * Custom excerpt length
 */
function ballstreet_excerpt_length(int $length): int
{
    return 20;
}
add_filter("excerpt_length", "ballstreet_excerpt_length", 999);

/**
 * Custom excerpt more
 */
function ballstreet_excerpt_more(string $more): string
{
    return "...";
}
add_filter("excerpt_more", "ballstreet_excerpt_more");

/**
 * SVG Icons
 */
function ballstreet_icon(string $name, int $size = 20): string
{
    $icons = [
        "arrow-right" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M4.167 10h11.666M10 4.167L15.833 10 10 15.833" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "arrow-up" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M10 15.833V4.167M4.167 10L10 4.167 15.833 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "arrow-down" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M10 4.167v11.666M4.167 10L10 15.833 15.833 10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "chart" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M15 16.667V8.333M10 16.667V3.333M5 16.667v-5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "dollar" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M10 1.667v16.666M14.167 5H7.917a2.917 2.917 0 000 5.833h4.166a2.917 2.917 0 010 5.834H5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "trending-up" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><path d="M19.167 5.833l-7.5 7.5-4.167-4.166-6.667 6.666" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.167 5.833h5v5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "menu" =>
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "close" =>
            '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "search" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 20 20" fill="none"><circle cx="9.167" cy="9.167" r="5.833" stroke="currentColor" stroke-width="1.5"/><path d="M17.5 17.5l-3.625-3.625" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
        "sun" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
        "moon" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 24 24" fill="none"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "table" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 24 24" fill="none"><path d="M3 3h18v18H3V3zM3 9h18M3 15h18M9 3v18M15 3v18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        "grid" =>
            '<svg width="' .
            $size .
            '" height="' .
            $size .
            '" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="1" stroke="currentColor" stroke-width="2"/></svg>',
    ];

    return $icons[$name] ?? "";
}

/**
 * Body Classes
 */
function ballstreet_body_classes(array $classes): array
{
    if (is_front_page()) {
        $classes[] = "is-front-page";
    }
    if (is_singular("athlete")) {
        $classes[] = "is-athlete";
    }
    if (is_singular("deal")) {
        $classes[] = "is-deal";
    }
    return $classes;
}
add_filter("body_class", "ballstreet_body_classes");

/**
 * Remove WordPress default bloat for better Lighthouse scores
 */
function ballstreet_remove_bloat(): void
{
    // Remove emoji scripts and styles
    remove_action("wp_head", "print_emoji_detection_script", 7);
    remove_action("wp_print_styles", "print_emoji_styles");
    remove_action("admin_print_scripts", "print_emoji_detection_script");
    remove_action("admin_print_styles", "print_emoji_styles");

    // Remove oEmbed discovery
    remove_action("wp_head", "wp_oembed_add_discovery_links");
    remove_action("wp_head", "wp_oembed_add_host_js");

    // Remove unnecessary meta tags
    remove_action("wp_head", "wp_generator");
    remove_action("wp_head", "wlwmanifest_link");
    remove_action("wp_head", "rsd_link");
    remove_action("wp_head", "wp_shortlink_wp_head");
    remove_action("wp_head", "rest_output_link_wp_head", 10);

    // Remove DNS prefetch for WordPress.org (emoji CDN)
    add_filter("emoji_svg_url", "__return_false");
}
add_action("after_setup_theme", "ballstreet_remove_bloat");

/**
 * Remove jQuery migrate (not needed with vanilla JS theme)
 */
function ballstreet_dequeue_unnecessary_scripts(): void
{
    if (!is_admin()) {
        wp_deregister_script("jquery");
        wp_dequeue_style("wp-block-library");
        wp_dequeue_style("classic-theme-styles");
        wp_dequeue_style("global-styles");
    }
}
add_action("wp_enqueue_scripts", "ballstreet_dequeue_unnecessary_scripts", 20);
