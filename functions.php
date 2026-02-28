<?php
/**
 * Ball Street Sports Journal Theme
 *
 * @package BallStreet
 */

defined("ABSPATH") || exit();

// Theme setup: menus, supports, and base assets
require_once get_template_directory() . "/inc/theme-setup.php";

// Vite integration: dev server detection and production asset loading
require_once get_template_directory() . "/inc/vite.php";

// Custom post types
require_once get_template_directory() . "/inc/post-types.php";

// Template helper functions (utilities, formatting, field accessors)
require_once get_template_directory() . "/inc/template-functions.php";

// Placeholder/dummy data (can be removed once real content exists)
require_once get_template_directory() . "/inc/dummy-data.php";

// Template rendering functions (HTML output for components)
require_once get_template_directory() . "/inc/template-render.php";

// JSON-LD structured data for SEO
require_once get_template_directory() . "/inc/json-ld.php";
