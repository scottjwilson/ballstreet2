<?php
/**
 * Dummy / Placeholder Data
 *
 * Static fallback data used when there aren't enough real CPT entries.
 * This file can be removed once the site has sufficient real content.
 */

defined("ABSPATH") || exit();

/**
 * Get static/dummy deals data with mixed categories
 *
 * @return array Array of deal data
 */
function ballstreet_get_dummy_deals(): array
{
    return [
        [
            "type" => "CONTRACT",
            "class" => "contract",
            "player" => "Joe Burrow",
            "amount" => '$275M',
            "trend" => "up",
            "trend_value" => "18%",
            "details" =>
                "5-year extension with Cincinnati Bengals, $219M guaranteed",
            "tags" => ['$219M GTD', "5 Years", "Bengals"],
        ],
        [
            "type" => "NIL DEAL",
            "class" => "nil",
            "player" => "Travis Hunter",
            "amount" => '$4.8M',
            "trend" => "up",
            "trend_value" => "89%",
            "details" =>
                "Multi-year endorsement portfolio spanning apparel, beverages, and tech",
            "tags" => ["Nike", "Gatorade", "Beats"],
        ],
        [
            "type" => "TRADE",
            "class" => "trade",
            "player" => "Juan Soto",
            "amount" => '$765M',
            "trend" => "up",
            "trend_value" => "Historic",
            "details" => "Record-breaking 15-year deal with New York Mets",
            "tags" => ["15 Years", "Record", "Full NTC"],
        ],
        [
            "type" => "CONTRACT",
            "class" => "contract",
            "player" => "Lamar Jackson",
            "amount" => '$260M',
            "trend" => "up",
            "trend_value" => "15%",
            "details" => "5-year fully guaranteed deal with Baltimore Ravens",
            "tags" => ["Fully GTD", "5 Years", "Ravens"],
        ],
        [
            "type" => "EXTENSION",
            "class" => "contract",
            "player" => "Justin Jefferson",
            "amount" => '$140M',
            "trend" => "up",
            "trend_value" => "22%",
            "details" => "4-year extension, highest-paid non-QB in NFL history",
            "tags" => ['$110M GTD', "4 Years", "Vikings"],
        ],
        [
            "type" => "NIL DEAL",
            "class" => "nil",
            "player" => "Arch Manning",
            "amount" => '$3.2M',
            "trend" => "up",
            "trend_value" => "156%",
            "details" =>
                "Texas QB lands deals with Panini, EA Sports, and TikTok",
            "tags" => ["Panini", "EA Sports", "Texas"],
        ],
        [
            "type" => "TRADE",
            "class" => "trade",
            "player" => "Davante Adams",
            "amount" => '$140M',
            "trend" => "down",
            "trend_value" => "8%",
            "details" => "Traded to Jets, restructured 5-year deal",
            "tags" => ["Jets", "Restructured", "5 Years"],
        ],
        [
            "type" => "CONTRACT",
            "class" => "contract",
            "player" => "CeeDee Lamb",
            "amount" => '$136M',
            "trend" => "up",
            "trend_value" => "25%",
            "details" => "4-year extension with Dallas Cowboys after holdout",
            "tags" => ['$100M GTD', "4 Years", "Cowboys"],
        ],
        [
            "type" => "NIL DEAL",
            "class" => "nil",
            "player" => "Caitlin Clark",
            "amount" => '$28M',
            "trend" => "up",
            "trend_value" => "340%",
            "details" =>
                "Record NIL portfolio includes Nike, State Farm, Gatorade",
            "tags" => ["Nike", "State Farm", "Indiana"],
        ],
    ];
}

/**
 * Get dummy ticker items from the dummy deals data
 *
 * @return array Array of ticker items
 */
function ballstreet_get_ticker_dummy_items(): array
{
    $dummy_deals = ballstreet_get_dummy_deals();
    $ticker_items = [];

    foreach ($dummy_deals as $deal) {
        // Convert deal type to short ticker format
        $type_map = [
            "NIL DEAL" => "NIL",
            "CONTRACT" => "CONTRACT",
            "TRADE" => "TRADE",
            "EXTENSION" => "EXT",
        ];

        $ticker_items[] = [
            "type" => $type_map[$deal["type"]] ?? $deal["type"],
            "name" => $deal["player"],
            "value" => $deal["amount"],
            "change" => str_replace("%", "", $deal["trend_value"]),
            "direction" => $deal["trend"],
        ];
    }

    return $ticker_items;
}
