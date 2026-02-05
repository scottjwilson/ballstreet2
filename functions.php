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

// Template helper functions
require_once get_template_directory() . "/inc/template-functions.php";
