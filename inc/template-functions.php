<?php
/**
 * Template Functions
 *
 * Utility and helper functions for the theme. Rendering functions are in
 * template-render.php, and placeholder data is in dummy-data.php.
 */

defined("ABSPATH") || exit();

/**
 * Safely get ACF field with post_meta fallback
 *
 * @param string $field_name The field name
 * @param int|false $post_id The post ID (defaults to current post)
 * @return mixed Field value
 */
function ballstreet_get_field(string $field_name, $post_id = false)
{
    if (function_exists("get_field")) {
        return get_field($field_name, $post_id);
    }
    return get_post_meta($post_id ?: get_the_ID(), $field_name, true);
}

/**
 * Format deal value (e.g., $1.5M, $500K)
 *
 * @param int|float $value The value in dollars
 * @return string Formatted value
 */
function ballstreet_format_value($value): string
{
    if (!$value) {
        return "—";
    }

    if ($value >= 1000000000) {
        return '$' . number_format($value / 1000000000, 1) . "B";
    } elseif ($value >= 1000000) {
        return '$' . number_format($value / 1000000, 1) . "M";
    } elseif ($value >= 1000) {
        return '$' . number_format($value / 1000, 0) . "K";
    }

    return '$' . number_format($value);
}

/**
 * Get deal type CSS class
 *
 * @param string $type The deal type
 * @return string CSS class name
 */
function ballstreet_get_deal_class(string $type): string
{
    $map = [
        "NIL Deal" => "nil",
        "NIL" => "nil",
        "Contract" => "contract",
        "CONTRACT" => "contract",
        "Trade" => "trade",
        "TRADE" => "trade",
        "Extension" => "contract",
    ];

    return $map[$type] ?? "nil";
}

/**
 * Get category badge class based on category slug
 *
 * @param string $slug Category slug
 * @return string CSS class name
 */
function ballstreet_get_category_class(string $slug): string
{
    $map = [
        "nil-deals" => "nil",
        "nil" => "nil",
        "contracts" => "contracts",
        "betting" => "betting",
        "betting-markets" => "betting",
        "analysis" => "contracts",
        "business" => "nil",
        "trades" => "betting",
    ];

    return $map[$slug] ?? "nil";
}

/**
 * Calculate read time for content
 *
 * @param string $content Post content
 * @return int Read time in minutes
 */
function ballstreet_get_read_time(string $content): int
{
    $word_count = str_word_count(strip_tags($content));
    $read_time = ceil($word_count / 200); // Assume 200 words per minute
    return max(1, $read_time);
}

/**
 * Get athlete fields by ID
 *
 * @param int $athlete_id The athlete post ID
 * @return array Athlete fields
 */
function ballstreet_get_athlete_fields(int $athlete_id): array
{
    $fields = [
        "position" => get_field("position", $athlete_id) ?: "",
        "nil_valuation" =>
            get_field("nil_valuation", $athlete_id) ?:
            get_field("valuation", $athlete_id) ?:
            0,
        "class_year" => get_field("class_year", $athlete_id) ?: "",
        "height" => get_field("height", $athlete_id) ?: "",
        "weight" => get_field("weight", $athlete_id) ?: "",
        "hometown" => get_field("hometown", $athlete_id) ?: "",
        "school_id" => null,
        "school_name" => "",
        "sponsors" => [],
        "sponsor_images" => [],
    ];

    // Get school relationship
    $school = get_field("school", $athlete_id);
    if ($school) {
        // Handle array (multi-select relationship)
        if (is_array($school)) {
            $school = $school[0];
        }
        // Handle object or ID
        if (is_object($school) && isset($school->ID)) {
            $fields["school_id"] = $school->ID;
            $fields["school_name"] = $school->post_title;
        } elseif (is_numeric($school)) {
            $fields["school_id"] = $school;
            $fields["school_name"] = get_the_title($school);
        }
    }

    // Get sponsors relationship
    $sponsors = get_field("sponsors", $athlete_id);
    if ($sponsors && is_array($sponsors)) {
        foreach ($sponsors as $sponsor) {
            $sponsor_id = is_object($sponsor) ? $sponsor->ID : $sponsor;
            $fields["sponsors"][] = [
                "id" => $sponsor_id,
                "name" => get_the_title($sponsor_id),
            ];
            if (has_post_thumbnail($sponsor_id)) {
                $fields["sponsor_images"][] = get_the_post_thumbnail(
                    $sponsor_id,
                    "thumbnail",
                    [
                        "class" => "athlete-sponsor-logo",
                        "alt" => get_the_title($sponsor_id),
                    ],
                );
            }
        }
    }

    // Build player info array
    $fields["player_info"] = array_filter([
        $fields["class_year"],
        $fields["height"],
        $fields["weight"] ? $fields["weight"] . " lbs" : "",
    ]);

    return $fields;
}
