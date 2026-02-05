<?php
/**
 * Vite Integration
 *
 * Handles Vite dev server detection and asset loading for both
 * development (HMR) and production (manifest-based) environments.
 */

defined("ABSPATH") || exit();

/**
 * Detect if Vite dev server is running and get the base path
 *
 * @return array{running: bool, base: string, server: string}
 */
function ballstreet_detect_vite_server(): array
{
    $vite_server = "http://localhost:3000";

    $response = @wp_remote_get($vite_server . "/js/main.js", [
        "timeout" => 1,
        "sslverify" => false,
        "redirection" => 0,
    ]);

    if (
        is_wp_error($response) ||
        wp_remote_retrieve_response_code($response) !== 200
    ) {
        return ["running" => false, "base" => "/", "server" => $vite_server];
    }

    // Try @vite/client at root
    $client_response = @wp_remote_get($vite_server . "/@vite/client", [
        "timeout" => 1,
        "sslverify" => false,
        "redirection" => 0,
    ]);

    if (
        !is_wp_error($client_response) &&
        wp_remote_retrieve_response_code($client_response) === 200
    ) {
        return ["running" => true, "base" => "/", "server" => $vite_server];
    }

    // Try with theme base path
    $client_response = @wp_remote_get(
        $vite_server . "/wp-content/themes/ballstreet2/@vite/client",
        [
            "timeout" => 1,
            "sslverify" => false,
            "redirection" => 0,
        ],
    );

    if (
        !is_wp_error($client_response) &&
        wp_remote_retrieve_response_code($client_response) === 200
    ) {
        return [
            "running" => true,
            "base" => "/wp-content/themes/ballstreet2/",
            "server" => $vite_server,
        ];
    }

    return ["running" => true, "base" => "/", "server" => $vite_server];
}

/**
 * Check if we're in a local development environment
 *
 * @return bool
 */
function ballstreet_is_local_environment(): bool
{
    $home_url = home_url();
    return strpos($home_url, "localhost") !== false ||
        strpos($home_url, "127.0.0.1") !== false ||
        strpos($home_url, ".local") !== false ||
        strpos($home_url, ".dev") !== false;
}

/**
 * Output Vite client scripts in head for HMR
 */
function ballstreet_output_vite_scripts(): void
{
    $vite = ballstreet_detect_vite_server();

    if (!$vite["running"] && !ballstreet_is_local_environment()) {
        return;
    }

    $vite_base = $vite["running"] ? $vite["base"] : "/";
    $vite_client_url = $vite["server"] . $vite_base . "@vite/client";
    $vite_main_url = $vite["server"] . $vite_base . "js/main.js";

    echo '<script type="module" src="' .
        esc_url($vite_client_url) .
        '"></script>' .
        "\n";
    echo '<script type="module" src="' .
        esc_url($vite_main_url) .
        '"></script>' .
        "\n";
}
add_action("wp_head", "ballstreet_output_vite_scripts", 1);

/**
 * Load Vite assets from manifest in production
 */
function ballstreet_load_vite_production_assets(): void
{
    $vite = ballstreet_detect_vite_server();

    if ($vite["running"]) {
        return;
    }

    $manifest_path = get_theme_file_path("dist/.vite/manifest.json");

    if (!file_exists($manifest_path)) {
        return;
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (!$manifest || !isset($manifest["js/main.js"])) {
        return;
    }

    $entry = $manifest["js/main.js"];

    // Enqueue CSS files
    if (isset($entry["css"]) && is_array($entry["css"])) {
        foreach ($entry["css"] as $index => $css_file) {
            $css_path = get_theme_file_path("dist/" . $css_file);
            wp_enqueue_style(
                "ballstreet-vite-style-" . $index,
                get_theme_file_uri("dist/" . $css_file),
                [],
                file_exists($css_path) ? filemtime($css_path) : null,
            );
        }
    }

    // Enqueue JS
    $js_path = get_theme_file_path("dist/" . $entry["file"]);
    wp_enqueue_script(
        "ballstreet-vite-main",
        get_theme_file_uri("dist/" . $entry["file"]),
        [],
        file_exists($js_path) ? filemtime($js_path) : null,
        true,
    );
}
add_action("wp_enqueue_scripts", "ballstreet_load_vite_production_assets", 100);

/**
 * Add type="module" attribute to Vite scripts
 *
 * @param string $tag    Script HTML tag
 * @param string $handle Script handle
 * @param string $src    Script source URL
 * @return string Modified script tag
 */
function ballstreet_vite_script_module_type(
    string $tag,
    string $handle,
    string $src,
): string {
    if (strpos($handle, "ballstreet-vite-") !== 0) {
        return $tag;
    }

    if (
        strpos($tag, 'type="module"') !== false ||
        strpos($tag, "type='module'") !== false
    ) {
        return $tag;
    }

    return str_replace("<script ", '<script type="module" ', $tag);
}
add_filter("script_loader_tag", "ballstreet_vite_script_module_type", 10, 3);
