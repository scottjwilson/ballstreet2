<?php
/**
 * JSON-LD Structured Data
 *
 * Outputs schema.org structured data in <head> for SEO.
 * Covers: WebSite, Article, Person (athletes), SportsOrganization (schools),
 * Organization (sponsors), and Event (deals).
 */

defined("ABSPATH") || exit();

/**
 * Output JSON-LD structured data based on current page
 */
function ballstreet_json_ld(): void
{
    $schema = null;

    if (is_front_page() || is_home()) {
        $schema = ballstreet_schema_website();
    } elseif (is_singular("post")) {
        $schema = ballstreet_schema_article();
    } elseif (is_singular("athlete")) {
        $schema = ballstreet_schema_athlete();
    } elseif (is_singular("school")) {
        $schema = ballstreet_schema_school();
    } elseif (is_singular("sponsor")) {
        $schema = ballstreet_schema_sponsor();
    } elseif (is_singular("deal")) {
        $schema = ballstreet_schema_deal();
    }

    if (!$schema) {
        return;
    }

    echo '<script type="application/ld+json">' .
        wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) .
        "</script>\n";
}
add_action("wp_head", "ballstreet_json_ld", 5);

/**
 * WebSite + Organization schema for the front page
 */
function ballstreet_schema_website(): array
{
    $site_url = home_url("/");
    $logo_id = get_theme_mod("custom_logo");
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, "full") : "";

    $org = [
        "@type" => "Organization",
        "name" => get_bloginfo("name"),
        "url" => $site_url,
    ];

    if ($logo_url) {
        $org["logo"] = $logo_url;
    }

    return [
        "@context" => "https://schema.org",
        "@graph" => [
            [
                "@type" => "WebSite",
                "name" => get_bloginfo("name"),
                "description" => get_bloginfo("description"),
                "url" => $site_url,
                "publisher" => $org,
                "potentialAction" => [
                    "@type" => "SearchAction",
                    "target" => [
                        "@type" => "EntryPoint",
                        "urlTemplate" => $site_url . "?s={search_term_string}",
                    ],
                    "query-input" => "required name=search_term_string",
                ],
            ],
            $org,
        ],
    ];
}

/**
 * Article schema for single blog posts
 */
function ballstreet_schema_article(): ?array
{
    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Article",
        "headline" => get_the_title($post),
        "url" => get_permalink($post),
        "datePublished" => get_the_date("c", $post),
        "dateModified" => get_the_modified_date("c", $post),
        "author" => [
            "@type" => "Person",
            "name" => get_the_author_meta("display_name", $post->post_author),
        ],
        "publisher" => [
            "@type" => "Organization",
            "name" => get_bloginfo("name"),
        ],
    ];

    if (has_post_thumbnail($post)) {
        $img = wp_get_attachment_image_src(
            get_post_thumbnail_id($post),
            "ballstreet-hero",
        );
        if ($img) {
            $schema["image"] = [
                "@type" => "ImageObject",
                "url" => $img[0],
                "width" => $img[1],
                "height" => $img[2],
            ];
        }
    }

    if (has_excerpt($post)) {
        $schema["description"] = wp_strip_all_tags(get_the_excerpt($post));
    }

    $logo_id = get_theme_mod("custom_logo");
    if ($logo_id) {
        $schema["publisher"]["logo"] = wp_get_attachment_image_url(
            $logo_id,
            "full",
        );
    }

    return $schema;
}

/**
 * Person schema for single athlete pages
 */
function ballstreet_schema_athlete(): ?array
{
    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Person",
        "name" => get_the_title($post),
        "url" => get_permalink($post),
    ];

    if (has_post_thumbnail($post)) {
        $schema["image"] = get_the_post_thumbnail_url($post, "ballstreet-hero");
    }

    if (has_excerpt($post)) {
        $schema["description"] = wp_strip_all_tags(get_the_excerpt($post));
    }

    // Position / job title
    $position = ballstreet_get_field("position", $post->ID);
    if ($position) {
        $schema["jobTitle"] = $position;
    }

    // Hometown
    $hometown = ballstreet_get_field("hometown", $post->ID);
    if ($hometown) {
        $schema["homeLocation"] = [
            "@type" => "Place",
            "name" => $hometown,
        ];
    }

    // School affiliation
    $school = ballstreet_get_field("school", $post->ID);
    if ($school) {
        $school_obj = is_array($school) ? $school[0] : $school;
        $school_id = is_object($school_obj) ? $school_obj->ID : $school_obj;
        if ($school_id && is_numeric($school_id)) {
            $schema["affiliation"] = [
                "@type" => "SportsOrganization",
                "name" => get_the_title($school_id),
                "url" => get_permalink($school_id),
            ];
        }
    }

    // Sport taxonomy
    $sports = get_the_terms($post->ID, "sport");
    if ($sports && !is_wp_error($sports)) {
        $sport_names = wp_list_pluck($sports, "name");
        if (count($sport_names) === 1) {
            $schema["knowsAbout"] = $sport_names[0];
        } else {
            $schema["knowsAbout"] = $sport_names;
        }
    }

    return $schema;
}

/**
 * SportsOrganization schema for single school pages
 */
function ballstreet_schema_school(): ?array
{
    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "SportsOrganization",
        "name" => get_the_title($post),
        "url" => get_permalink($post),
    ];

    if (has_post_thumbnail($post)) {
        $schema["logo"] = get_the_post_thumbnail_url($post, "ballstreet-hero");
    }

    if (has_excerpt($post)) {
        $schema["description"] = wp_strip_all_tags(get_the_excerpt($post));
    }

    // Conference taxonomy
    $conferences = get_the_terms($post->ID, "conference");
    if ($conferences && !is_wp_error($conferences)) {
        $schema["memberOf"] = array_map(function ($conf) {
            return [
                "@type" => "SportsOrganization",
                "name" => $conf->name,
            ];
        }, $conferences);
        if (count($schema["memberOf"]) === 1) {
            $schema["memberOf"] = $schema["memberOf"][0];
        }
    }

    // Sport taxonomy
    $sports = get_the_terms($post->ID, "sport");
    if ($sports && !is_wp_error($sports)) {
        $schema["sport"] = wp_list_pluck($sports, "name");
        if (count($schema["sport"]) === 1) {
            $schema["sport"] = $schema["sport"][0];
        }
    }

    return $schema;
}

/**
 * Organization schema for single sponsor pages
 */
function ballstreet_schema_sponsor(): ?array
{
    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Organization",
        "name" => get_the_title($post),
        "url" => get_permalink($post),
    ];

    if (has_post_thumbnail($post)) {
        $schema["logo"] = get_the_post_thumbnail_url($post, "ballstreet-hero");
    }

    if (has_excerpt($post)) {
        $schema["description"] = wp_strip_all_tags(get_the_excerpt($post));
    }

    return $schema;
}

/**
 * Schema for single deal pages
 *
 * Uses a combination of Event + MonetaryAmount to represent deals,
 * which is the closest fit for NIL deals and contract signings.
 */
function ballstreet_schema_deal(): ?array
{
    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $schema = [
        "@context" => "https://schema.org",
        "@type" => "Event",
        "name" => get_the_title($post),
        "url" => get_permalink($post),
        "startDate" => get_the_date("c", $post),
        "eventAttendanceMode" => "https://schema.org/OnlineEventAttendanceMode",
    ];

    if (has_post_thumbnail($post)) {
        $schema["image"] = get_the_post_thumbnail_url($post, "ballstreet-hero");
    }

    if (has_excerpt($post)) {
        $schema["description"] = wp_strip_all_tags(get_the_excerpt($post));
    }

    // Deal value
    $value = ballstreet_get_field("deal_value", $post->ID);
    if ($value && is_numeric($value)) {
        $schema["offers"] = [
            "@type" => "Offer",
            "price" => (float) $value,
            "priceCurrency" => "USD",
        ];
    }

    // Deal type taxonomy
    $deal_types = get_the_terms($post->ID, "deal_type");
    if ($deal_types && !is_wp_error($deal_types)) {
        $schema["eventType"] = wp_list_pluck($deal_types, "name");
        if (count($schema["eventType"]) === 1) {
            $schema["eventType"] = $schema["eventType"][0];
        }
    }

    // Athlete relationship
    $athlete = ballstreet_get_field("athlete", $post->ID);
    if ($athlete) {
        $athlete_obj = is_array($athlete) ? $athlete[0] : $athlete;
        $athlete_id = is_object($athlete_obj) ? $athlete_obj->ID : $athlete_obj;
        if ($athlete_id && is_numeric($athlete_id)) {
            $schema["performer"] = [
                "@type" => "Person",
                "name" => get_the_title($athlete_id),
                "url" => get_permalink($athlete_id),
            ];
        }
    }

    // Sponsor relationship
    $sponsor = ballstreet_get_field("sponsor", $post->ID);
    if ($sponsor) {
        $sponsor_obj = is_array($sponsor) ? $sponsor[0] : $sponsor;
        $sponsor_id = is_object($sponsor_obj) ? $sponsor_obj->ID : $sponsor_obj;
        if ($sponsor_id && is_numeric($sponsor_id)) {
            $schema["organizer"] = [
                "@type" => "Organization",
                "name" => get_the_title($sponsor_id),
                "url" => get_permalink($sponsor_id),
            ];
        }
    }

    return $schema;
}
