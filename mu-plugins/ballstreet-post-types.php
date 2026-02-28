<?php
/**
 * Ball Street Custom Post Types
 *
 * Registers custom post types and taxonomies for the Ball Street platform.
 * Lives in mu-plugins so CPTs persist across theme changes.
 *
 * @package BallStreet
 */

defined("ABSPATH") || exit();

/**
 * Register Athletes Custom Post Type
 */
function ballstreet_register_athletes_post_type(): void
{
    $labels = [
        "name" => _x("Athletes", "Post Type General Name", "ballstreet"),
        "singular_name" => _x(
            "Athlete",
            "Post Type Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Athletes", "ballstreet"),
        "name_admin_bar" => __("Athlete", "ballstreet"),
        "archives" => __("Athlete Archives", "ballstreet"),
        "all_items" => __("All Athletes", "ballstreet"),
        "add_new_item" => __("Add New Athlete", "ballstreet"),
        "add_new" => __("Add New", "ballstreet"),
        "new_item" => __("New Athlete", "ballstreet"),
        "edit_item" => __("Edit Athlete", "ballstreet"),
        "update_item" => __("Update Athlete", "ballstreet"),
        "view_item" => __("View Athlete", "ballstreet"),
        "view_items" => __("View Athletes", "ballstreet"),
        "search_items" => __("Search Athlete", "ballstreet"),
        "not_found" => __("Not found", "ballstreet"),
        "not_found_in_trash" => __("Not found in Trash", "ballstreet"),
    ];

    $args = [
        "label" => __("Athlete", "ballstreet"),
        "description" => __("Athlete profiles and information", "ballstreet"),
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "thumbnail",
            "excerpt",
            "custom-fields",
            "revisions",
        ],
        "taxonomies" => ["sport", "conference", "post_tag"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-groups",
        "show_in_admin_bar" => true,
        "show_in_nav_menus" => true,
        "can_export" => true,
        "has_archive" => "athletes",
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "rest_base" => "athletes",
    ];

    register_post_type("athlete", $args);
}
add_action("init", "ballstreet_register_athletes_post_type", 0);

/**
 * Register Schools Custom Post Type
 */
function ballstreet_register_schools_post_type(): void
{
    $labels = [
        "name" => _x("Schools", "Post Type General Name", "ballstreet"),
        "singular_name" => _x(
            "School",
            "Post Type Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Schools", "ballstreet"),
        "name_admin_bar" => __("School", "ballstreet"),
        "all_items" => __("All Schools", "ballstreet"),
        "add_new_item" => __("Add New School", "ballstreet"),
        "add_new" => __("Add New", "ballstreet"),
        "new_item" => __("New School", "ballstreet"),
        "edit_item" => __("Edit School", "ballstreet"),
        "update_item" => __("Update School", "ballstreet"),
        "view_item" => __("View School", "ballstreet"),
        "search_items" => __("Search School", "ballstreet"),
        "not_found" => __("Not found", "ballstreet"),
        "not_found_in_trash" => __("Not found in Trash", "ballstreet"),
    ];

    $args = [
        "label" => __("School", "ballstreet"),
        "description" => __("School profiles and information", "ballstreet"),
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "thumbnail",
            "excerpt",
            "custom-fields",
            "revisions",
        ],
        "taxonomies" => ["sport", "conference", "post_tag"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "menu_position" => 6,
        "menu_icon" => "dashicons-welcome-learn-more",
        "show_in_admin_bar" => true,
        "show_in_nav_menus" => true,
        "can_export" => true,
        "has_archive" => "schools",
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "rest_base" => "schools",
    ];

    register_post_type("school", $args);
}
add_action("init", "ballstreet_register_schools_post_type", 0);

/**
 * Register Sponsors Custom Post Type
 */
function ballstreet_register_sponsors_post_type(): void
{
    $labels = [
        "name" => _x("Sponsors", "Post Type General Name", "ballstreet"),
        "singular_name" => _x(
            "Sponsor",
            "Post Type Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Sponsors", "ballstreet"),
        "name_admin_bar" => __("Sponsor", "ballstreet"),
        "all_items" => __("All Sponsors", "ballstreet"),
        "add_new_item" => __("Add New Sponsor", "ballstreet"),
        "add_new" => __("Add New", "ballstreet"),
        "new_item" => __("New Sponsor", "ballstreet"),
        "edit_item" => __("Edit Sponsor", "ballstreet"),
        "update_item" => __("Update Sponsor", "ballstreet"),
        "view_item" => __("View Sponsor", "ballstreet"),
        "search_items" => __("Search Sponsor", "ballstreet"),
        "not_found" => __("Not found", "ballstreet"),
        "not_found_in_trash" => __("Not found in Trash", "ballstreet"),
    ];

    $args = [
        "label" => __("Sponsor", "ballstreet"),
        "description" => __("Sponsor profiles and information", "ballstreet"),
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "thumbnail",
            "excerpt",
            "custom-fields",
            "revisions",
        ],
        "taxonomies" => ["category", "post_tag"],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "menu_position" => 7,
        "menu_icon" => "dashicons-star-filled",
        "show_in_admin_bar" => true,
        "show_in_nav_menus" => true,
        "can_export" => true,
        "has_archive" => "sponsors",
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "rest_base" => "sponsors",
    ];

    register_post_type("sponsor", $args);
}
add_action("init", "ballstreet_register_sponsors_post_type", 0);

/**
 * Register Deals Custom Post Type
 */
function ballstreet_register_deals_post_type(): void
{
    $labels = [
        "name" => _x("Deals", "Post Type General Name", "ballstreet"),
        "singular_name" => _x("Deal", "Post Type Singular Name", "ballstreet"),
        "menu_name" => __("Deals", "ballstreet"),
        "name_admin_bar" => __("Deal", "ballstreet"),
        "archives" => __("Deal Archives", "ballstreet"),
        "all_items" => __("All Deals", "ballstreet"),
        "add_new_item" => __("Add New Deal", "ballstreet"),
        "add_new" => __("Add New", "ballstreet"),
        "new_item" => __("New Deal", "ballstreet"),
        "edit_item" => __("Edit Deal", "ballstreet"),
        "update_item" => __("Update Deal", "ballstreet"),
        "view_item" => __("View Deal", "ballstreet"),
        "view_items" => __("View Deals", "ballstreet"),
        "search_items" => __("Search Deal", "ballstreet"),
        "not_found" => __("Not found", "ballstreet"),
        "not_found_in_trash" => __("Not found in Trash", "ballstreet"),
    ];

    $args = [
        "label" => __("Deal", "ballstreet"),
        "description" => __(
            "Contract deals, NIL agreements, and trades",
            "ballstreet",
        ),
        "labels" => $labels,
        "supports" => [
            "title",
            "editor",
            "thumbnail",
            "excerpt",
            "custom-fields",
            "revisions",
        ],
        "hierarchical" => false,
        "public" => true,
        "show_ui" => true,
        "show_in_menu" => true,
        "menu_position" => 4,
        "menu_icon" => "dashicons-money-alt",
        "show_in_admin_bar" => true,
        "show_in_nav_menus" => true,
        "can_export" => true,
        "has_archive" => "deals",
        "exclude_from_search" => false,
        "publicly_queryable" => true,
        "capability_type" => "post",
        "show_in_rest" => true,
        "rest_base" => "deals",
    ];

    register_post_type("deal", $args);
}
add_action("init", "ballstreet_register_deals_post_type", 0);

/**
 * Register Deal Type Taxonomy
 */
function ballstreet_register_deal_type_taxonomy(): void
{
    $labels = [
        "name" => _x("Deal Types", "Taxonomy General Name", "ballstreet"),
        "singular_name" => _x(
            "Deal Type",
            "Taxonomy Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Deal Types", "ballstreet"),
        "all_items" => __("All Deal Types", "ballstreet"),
        "parent_item" => __("Parent Deal Type", "ballstreet"),
        "parent_item_colon" => __("Parent Deal Type:", "ballstreet"),
        "new_item_name" => __("New Deal Type Name", "ballstreet"),
        "add_new_item" => __("Add New Deal Type", "ballstreet"),
        "edit_item" => __("Edit Deal Type", "ballstreet"),
        "update_item" => __("Update Deal Type", "ballstreet"),
        "view_item" => __("View Deal Type", "ballstreet"),
        "search_items" => __("Search Deal Types", "ballstreet"),
    ];

    $args = [
        "labels" => $labels,
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_nav_menus" => true,
        "show_in_rest" => true,
        "rewrite" => ["slug" => "deal-type"],
    ];

    register_taxonomy("deal_type", ["deal"], $args);
}
add_action("init", "ballstreet_register_deal_type_taxonomy", 0);

/**
 * Register Sport Taxonomy
 */
function ballstreet_register_sport_taxonomy(): void
{
    $labels = [
        "name" => _x("Sports", "Taxonomy General Name", "ballstreet"),
        "singular_name" => _x(
            "Sport",
            "Taxonomy Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Sports", "ballstreet"),
        "all_items" => __("All Sports", "ballstreet"),
        "new_item_name" => __("New Sport Name", "ballstreet"),
        "add_new_item" => __("Add New Sport", "ballstreet"),
        "edit_item" => __("Edit Sport", "ballstreet"),
        "update_item" => __("Update Sport", "ballstreet"),
        "view_item" => __("View Sport", "ballstreet"),
        "search_items" => __("Search Sports", "ballstreet"),
    ];

    $args = [
        "labels" => $labels,
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_nav_menus" => true,
        "show_in_rest" => true,
        "rewrite" => ["slug" => "sport"],
    ];

    register_taxonomy("sport", ["athlete", "school"], $args);
}
add_action("init", "ballstreet_register_sport_taxonomy", 0);

/**
 * Register Conference Taxonomy
 */
function ballstreet_register_conference_taxonomy(): void
{
    $labels = [
        "name" => _x("Conferences", "Taxonomy General Name", "ballstreet"),
        "singular_name" => _x(
            "Conference",
            "Taxonomy Singular Name",
            "ballstreet",
        ),
        "menu_name" => __("Conferences", "ballstreet"),
        "all_items" => __("All Conferences", "ballstreet"),
        "new_item_name" => __("New Conference Name", "ballstreet"),
        "add_new_item" => __("Add New Conference", "ballstreet"),
        "edit_item" => __("Edit Conference", "ballstreet"),
        "update_item" => __("Update Conference", "ballstreet"),
        "view_item" => __("View Conference", "ballstreet"),
        "search_items" => __("Search Conferences", "ballstreet"),
    ];

    $args = [
        "labels" => $labels,
        "hierarchical" => true,
        "public" => true,
        "show_ui" => true,
        "show_admin_column" => true,
        "show_in_nav_menus" => true,
        "show_in_rest" => true,
        "rewrite" => ["slug" => "conference"],
    ];

    register_taxonomy("conference", ["athlete", "school"], $args);
}
add_action("init", "ballstreet_register_conference_taxonomy", 0);

/**
 * Pre-populate Deal Type terms (runs once, tracked via option)
 */
function ballstreet_create_default_deal_types(): void
{
    if (get_option("ballstreet_deal_types_created")) {
        return;
    }

    $terms = [
        "NIL Deal",
        "Contract",
        "Trade",
        "Extension",
        "Free Agent Signing",
    ];

    foreach ($terms as $term) {
        if (!term_exists($term, "deal_type")) {
            wp_insert_term($term, "deal_type");
        }
    }

    update_option("ballstreet_deal_types_created", true);
}
add_action("init", "ballstreet_create_default_deal_types", 10);

/**
 * Pre-populate Sport terms (runs once, tracked via option)
 */
function ballstreet_create_default_sports(): void
{
    if (get_option("ballstreet_sports_created")) {
        return;
    }

    $terms = [
        "Football",
        "Basketball",
        "Baseball",
        "Soccer",
        "Track & Field",
        "Swimming",
        "Golf",
        "Tennis",
        "Volleyball",
        "Softball",
        "Hockey",
        "Lacrosse",
        "Wrestling",
        "Gymnastics",
    ];

    foreach ($terms as $term) {
        if (!term_exists($term, "sport")) {
            wp_insert_term($term, "sport");
        }
    }

    update_option("ballstreet_sports_created", true);
}
add_action("init", "ballstreet_create_default_sports", 10);

/**
 * Pre-populate Conference terms (runs once, tracked via option)
 */
function ballstreet_create_default_conferences(): void
{
    if (get_option("ballstreet_conferences_created")) {
        return;
    }

    $terms = [
        "SEC",
        "Big Ten",
        "Big 12",
        "ACC",
        "Pac-12",
        "AAC",
        "Mountain West",
        "Sun Belt",
        "Conference USA",
        "MAC",
        "Independent",
    ];

    foreach ($terms as $term) {
        if (!term_exists($term, "conference")) {
            wp_insert_term($term, "conference");
        }
    }

    update_option("ballstreet_conferences_created", true);
}
add_action("init", "ballstreet_create_default_conferences", 10);

/**
 * Flush rewrite rules when this file changes (one-time)
 */
function ballstreet_maybe_flush_rewrites(): void
{
    $version = "1.1.0";
    if (get_option("ballstreet_cpt_rewrite_version") !== $version) {
        flush_rewrite_rules();
        update_option("ballstreet_cpt_rewrite_version", $version);
    }
}
add_action("init", "ballstreet_maybe_flush_rewrites", 99);
