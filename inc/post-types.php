<?php
/**
 * Theme-specific CPT Query Modifications
 *
 * CPT and taxonomy registrations live in mu-plugins/ballstreet-post-types.php
 * so they persist across theme changes. This file only contains theme-specific
 * query modifications for those post types.
 */

defined("ABSPATH") || exit();

/**
 * Sort deals by value (descending) on archive pages
 *
 * Uses a simple meta_key + orderby approach. Deals without a deal_value
 * will appear after those with one, sorted by date.
 */
function ballstreet_sort_deals_by_value($query): void
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (is_post_type_archive("deal")) {
        $query->set("meta_key", "deal_value");
        $query->set("orderby", [
            "meta_value_num" => "DESC",
            "date" => "DESC",
        ]);
    }
}
add_action("pre_get_posts", "ballstreet_sort_deals_by_value");
