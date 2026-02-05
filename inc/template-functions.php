<?php
/**
 * Template Functions
 *
 * Helper functions for rendering theme components
 */

defined("ABSPATH") || exit();

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
 * Render ticker bar items
 */
function ballstreet_render_ticker(): void
{
    // Static ticker items for now - can be replaced with CPT query or ACF options
    $ticker_items = [
        [
            "type" => "CONTRACT",
            "name" => "Patrick Mahomes",
            "value" => '$450M',
            "change" => "12",
            "direction" => "up",
        ],
        [
            "type" => "NIL",
            "name" => "Caitlin Clark",
            "value" => '$28M',
            "change" => "340",
            "direction" => "up",
        ],
        [
            "type" => "CONTRACT",
            "name" => "Shohei Ohtani",
            "value" => '$700M',
            "change" => "8",
            "direction" => "up",
        ],
        [
            "type" => "NIL",
            "name" => "Travis Hunter",
            "value" => '$4.8M',
            "change" => "89",
            "direction" => "up",
        ],
        [
            "type" => "CONTRACT",
            "name" => "Jaylen Brown",
            "value" => '$304M',
            "change" => "5",
            "direction" => "up",
        ],
        [
            "type" => "NIL",
            "name" => "Angel Reese",
            "value" => '$1.8M',
            "change" => "156",
            "direction" => "up",
        ],
    ];

    // Render twice for seamless loop
    for ($i = 0; $i < 2; $i++) {
        foreach ($ticker_items as $item) {
            $arrow = $item["direction"] === "up" ? "↑" : "↓"; ?>
            <div class="ticker-item">
                <span class="ticker-type"><?php echo esc_html(
                    $item["type"],
                ); ?></span>
                <span class="ticker-name"><?php echo esc_html(
                    $item["name"],
                ); ?></span>
                <span class="ticker-value"><?php echo esc_html(
                    $item["value"],
                ); ?></span>
                <span class="ticker-change <?php echo esc_attr(
                    $item["direction"],
                ); ?>"><?php echo $arrow; ?> <?php echo esc_html(
     $item["change"],
 ); ?>%</span>
            </div>
            <?php
        }
    }
}

/**
 * Render featured article (hero main)
 *
 * @param WP_Post|null $post The post object (uses global if null)
 */
function ballstreet_render_hero_article($post = null): void
{
    if (!$post) {
        // Query for featured post
        $args = [
            "post_type" => "post",
            "posts_per_page" => 1,
            "meta_query" => [
                [
                    "key" => "is_featured",
                    "value" => "1",
                    "compare" => "=",
                ],
            ],
        ];
        $featured = new WP_Query($args);

        if (!$featured->have_posts()) {
            // Fall back to most recent post
            $args = [
                "post_type" => "post",
                "posts_per_page" => 1,
            ];
            $featured = new WP_Query($args);
        }

        if ($featured->have_posts()) {
            $featured->the_post();
            $post = get_post();
        } else {
            wp_reset_postdata();
            return;
        }
    }

    $category = get_the_category($post->ID);
    $badge_text = !empty($category)
        ? strtoupper($category[0]->name)
        : "FEATURED";
    $author = get_the_author_meta("display_name", $post->post_author);
    $read_time = ballstreet_get_read_time($post->post_content);
    $time_ago =
        human_time_diff(get_the_time("U", $post), current_time("timestamp")) .
        " ago";
    ?>
    <a href="<?php echo get_permalink(
        $post,
    ); ?>" class="hero-main fade-in fade-in-delay-1">
        <?php if (has_post_thumbnail($post)): ?>
            <div class="hero-main-image">
                <?php echo get_the_post_thumbnail($post, "ballstreet-hero"); ?>
            </div>
        <?php endif; ?>
        <span class="hero-symbol">$$$</span>
        <div class="hero-content">
            <span class="hero-badge"><?php echo esc_html($badge_text); ?></span>
            <h1 class="hero-title"><?php echo get_the_title($post); ?></h1>
            <?php if (has_excerpt($post)): ?>
                <p class="hero-subtitle"><?php echo get_the_excerpt(
                    $post,
                ); ?></p>
            <?php endif; ?>
            <div class="hero-meta">
                <span class="hero-author"><?php echo esc_html(
                    $author,
                ); ?></span>
                <span>•</span>
                <span><?php echo esc_html($read_time); ?> min read</span>
                <span>•</span>
                <span><?php echo esc_html($time_ago); ?></span>
            </div>
        </div>
    </a>
    <?php wp_reset_postdata();
}

/**
 * Render sidebar cards
 *
 * @param int $count Number of cards to show
 */
function ballstreet_render_sidebar_cards(int $count = 3): void
{
    $args = [
        "post_type" => "post",
        "posts_per_page" => $count,
        "offset" => 1, // Skip the featured post
    ];

    $query = new WP_Query($args);
    $delay = 2;

    if ($query->have_posts()):
        while ($query->have_posts()):

            $query->the_post();
            $category = get_the_category();
            $cat_slug = !empty($category) ? $category[0]->slug : "nil";
            $cat_name = !empty($category)
                ? strtoupper($category[0]->name)
                : "NEWS";
            $badge_class = ballstreet_get_category_class($cat_slug);
            $time_ago =
                human_time_diff(get_the_time("U"), current_time("timestamp")) .
                " ago";
            ?>
            <article class="sidebar-card fade-in fade-in-delay-<?php echo $delay; ?>">
                <a href="<?php the_permalink(); ?>">
                    <div class="sidebar-badge <?php echo esc_attr(
                        $badge_class,
                    ); ?>">
                        <span class="dot"></span>
                        <?php echo esc_html($cat_name); ?>
                    </div>
                    <h3 class="sidebar-title"><?php the_title(); ?></h3>
                    <p class="sidebar-meta"><?php echo esc_html(
                        $time_ago,
                    ); ?></p>
                </a>
            </article>
            <?php $delay++;
        endwhile;
        wp_reset_postdata();
    endif;
}

/**
 * Render deals grid
 *
 * @param int $count Number of deals to show
 * @param bool $featured_only Only show featured deals
 */
function ballstreet_render_deals_grid(
    int $count = 3,
    bool $featured_only = true,
): void {
    // Query Deal CPT
    $args = [
        "post_type" => "deal",
        "posts_per_page" => $count,
        "orderby" => "date",
        "order" => "DESC",
    ];

    // If featured only, add meta query
    if ($featured_only) {
        $args["meta_query"] = [
            [
                "key" => "deal_featured",
                "value" => "1",
                "compare" => "=",
            ],
        ];
    }

    $deals_query = new WP_Query($args);

    // Fallback to static content if no deals found
    if (!$deals_query->have_posts()) {
        ballstreet_render_deals_grid_static($count);
        return;
    }

    $delay = 3;
    while ($deals_query->have_posts()):

        $deals_query->the_post();
        $deal_id = get_the_ID();

        // Get deal fields
        $deal_value = get_field("deal_value", $deal_id) ?: 0;
        $deal_trend = get_field("deal_trend", $deal_id) ?: "up";
        $deal_trend_percent = get_field("deal_trend_percent", $deal_id) ?: "";
        $deal_details = get_field("deal_details", $deal_id) ?: "";
        $deal_tags_raw = get_field("deal_tags", $deal_id) ?: "";

        // Get deal type from taxonomy
        $deal_types = get_the_terms($deal_id, "deal_type");
        $deal_type = !empty($deal_types) ? $deal_types[0]->name : "Deal";
        $deal_type_class = ballstreet_get_deal_class($deal_type);

        // Get linked athlete
        $athlete = get_field("deal_athlete", $deal_id);
        $player_name = "";
        if ($athlete) {
            // Handle different ACF return formats
            if (is_array($athlete)) {
                // If it's an array of post objects (relationship field returns array)
                $first_athlete = reset($athlete);
                if (is_object($first_athlete)) {
                    $player_name = $first_athlete->post_title;
                } elseif (is_numeric($first_athlete)) {
                    $player_name = get_the_title($first_athlete);
                }
            } elseif (is_object($athlete)) {
                // Single post object
                $player_name = $athlete->post_title;
            } elseif (is_numeric($athlete)) {
                // Just the ID
                $player_name = get_the_title($athlete);
            }
        }

        // Fallback to deal title if no athlete name found
        if (empty($player_name)) {
            $player_name = get_the_title($deal_id);
        }

        // Parse tags (comma-separated text field)
        $tags = [];
        if ($deal_tags_raw) {
            $tags = array_map("trim", explode(",", $deal_tags_raw));
        }

        $arrow = $deal_trend === "up" ? "↑" : "↓";
        ?>
        <article class="deal-card <?php echo esc_attr(
            $deal_type_class,
        ); ?> fade-in fade-in-delay-<?php echo $delay; ?>">
            <a href="<?php the_permalink(); ?>" class="deal-card-link">
                <div class="deal-header">
                    <span class="deal-type <?php echo esc_attr(
                        $deal_type_class,
                    ); ?>"><?php echo esc_html(
    strtoupper($deal_type),
); ?></span>
                    <?php if ($deal_trend_percent): ?>
                        <span class="deal-trend <?php echo esc_attr(
                            $deal_trend,
                        ); ?>"><?php echo $arrow; ?> <?php echo esc_html(
     $deal_trend_percent,
 ); ?></span>
                    <?php endif; ?>
                </div>
                <h3 class="deal-player"><?php echo esc_html(
                    $player_name,
                ); ?></h3>
                <div class="deal-amount"><?php echo ballstreet_format_value(
                    $deal_value,
                ); ?></div>
                <?php if ($deal_details): ?>
                    <p class="deal-details"><?php echo esc_html(
                        $deal_details,
                    ); ?></p>
                <?php endif; ?>
                <?php if (!empty($tags)): ?>
                    <div class="deal-tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="deal-tag"><?php echo esc_html(
                                $tag,
                            ); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </a>
        </article>
        <?php $delay++;
    endwhile;
    wp_reset_postdata();
}

/**
 * Render static deals grid (fallback when no Deal CPT entries exist)
 *
 * @param int $count Number of deals to show
 */
function ballstreet_render_deals_grid_static(int $count = 3): void
{
    $deals = [
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
            "type" => "CONTRACT",
            "class" => "contract",
            "player" => "Tyreek Hill",
            "amount" => '$120M',
            "trend" => "up",
            "trend_value" => "12%",
            "details" =>
                '4-year extension with Miami Dolphins, $72.2M guaranteed',
            "tags" => ['$72.2M GTD', "4 Years", "No-Trade"],
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
    ];

    $delay = 3;
    foreach (array_slice($deals, 0, $count) as $deal):
        $arrow = $deal["trend"] === "up" ? "↑" : "↓"; ?>
        <article class="deal-card <?php echo esc_attr(
            $deal["class"],
        ); ?> fade-in fade-in-delay-<?php echo $delay; ?>">
            <div class="deal-header">
                <span class="deal-type <?php echo esc_attr(
                    $deal["class"],
                ); ?>"><?php echo esc_html($deal["type"]); ?></span>
                <span class="deal-trend <?php echo esc_attr(
                    $deal["trend"],
                ); ?>"><?php echo $arrow; ?> <?php echo esc_html(
     $deal["trend_value"],
 ); ?></span>
            </div>
            <h3 class="deal-player"><?php echo esc_html(
                $deal["player"],
            ); ?></h3>
            <div class="deal-amount"><?php echo esc_html(
                $deal["amount"],
            ); ?></div>
            <p class="deal-details"><?php echo esc_html(
                $deal["details"],
            ); ?></p>
            <div class="deal-tags">
                <?php foreach ($deal["tags"] as $tag): ?>
                    <span class="deal-tag"><?php echo esc_html($tag); ?></span>
                <?php endforeach; ?>
            </div>
        </article>
        <?php $delay++;
    endforeach;
}

/**
 * Render article rows
 *
 * @param int $count Number of articles to show
 */
function ballstreet_render_article_rows(int $count = 5): void
{
    $args = [
        "post_type" => "post",
        "posts_per_page" => $count,
        "offset" => 4, // Skip featured and sidebar posts
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()):
        while ($query->have_posts()):

            $query->the_post();
            $category = get_the_category();
            $cat_name = !empty($category)
                ? strtoupper($category[0]->name)
                : "NEWS";
            $read_time = ballstreet_get_read_time(get_the_content());
            $is_hot = get_post_meta(get_the_ID(), "is_hot", true);
            ?>
            <article class="article-row fade-in">
                <a href="<?php the_permalink(); ?>">
                    <span class="article-category"><?php echo esc_html(
                        $cat_name,
                    ); ?></span>
                    <h3 class="article-title">
                        <?php the_title(); ?>
                        <?php if ($is_hot): ?>
                            <span class="hot-badge">HOT</span>
                        <?php endif; ?>
                    </h3>
                    <span class="article-time"><?php echo esc_html(
                        $read_time,
                    ); ?> min read</span>
                </a>
            </article>
            <?php
        endwhile;
        wp_reset_postdata();
        // Show placeholder content
    else:
        $placeholders = [
            [
                "category" => "BETTING MARKETS",
                "title" =>
                    "Super Bowl Odds Shift Dramatically After Chiefs Clinch",
                "hot" => true,
                "time" => "8",
            ],
            [
                "category" => "NIL ANALYSIS",
                "title" =>
                    'The $50M Question: Are College Athletes Overvalued?',
                "hot" => false,
                "time" => "15",
            ],
            [
                "category" => "CONTRACTS",
                "title" =>
                    'NBA Max Contracts: Who\'s Actually Worth It in 2025?',
                "hot" => true,
                "time" => "11",
            ],
            [
                "category" => "BUSINESS",
                "title" =>
                    'Private Equity\'s Quiet Takeover of Minor League Baseball',
                "hot" => false,
                "time" => "18",
            ],
            [
                "category" => "TRADE ANALYSIS",
                "title" =>
                    "Breaking Down the Cap Implications of the Davante Adams Trade",
                "hot" => false,
                "time" => "9",
            ],
        ];

        foreach ($placeholders as $item): ?>
            <article class="article-row fade-in">
                <span class="article-category"><?php echo esc_html(
                    $item["category"],
                ); ?></span>
                <h3 class="article-title">
                    <?php echo esc_html($item["title"]); ?>
                    <?php if ($item["hot"]): ?>
                        <span class="hot-badge">HOT</span>
                    <?php endif; ?>
                </h3>
                <span class="article-time"><?php echo esc_html(
                    $item["time"],
                ); ?> min read</span>
            </article>
            <?php endforeach;
    endif;
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
 * Render category tabs
 *
 * @param string $active Currently active category slug
 */
function ballstreet_render_category_tabs(string $active = "all"): void
{
    $tabs = [
        ["slug" => "all", "label" => "All"],
        ["slug" => "nil-deals", "label" => "NIL Deals"],
        ["slug" => "contracts", "label" => "Contracts"],
        ["slug" => "betting-markets", "label" => "Betting Markets"],
        ["slug" => "business", "label" => "Business Analysis"],
        ["slug" => "trades", "label" => "Trades"],
    ];

    foreach ($tabs as $tab):
        $is_active = $tab["slug"] === $active ? "active" : ""; ?>
        <button class="category-btn <?php echo esc_attr(
            $is_active,
        ); ?>" data-category="<?php echo esc_attr($tab["slug"]); ?>">
            <?php echo esc_html($tab["label"]); ?>
        </button>
        <?php
    endforeach;
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
        "nil_valuation" => get_field("nil_valuation", $athlete_id) ?: 0,
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
        $school_id = is_object($school) ? $school->ID : $school;
        $fields["school_id"] = $school_id;
        $fields["school_name"] = get_the_title($school_id);
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

/**
 * Render athletes table component
 *
 * @param array $args Configuration options
 */
function ballstreet_render_athletes_table(array $args = []): void
{
    $defaults = [
        "athletes" => null,
        "view" => "table",
        "show_rank" => true,
        "show_search" => true,
        "show_filters" => true,
        "show_view_toggle" => true,
        "per_page" => 20,
        "title" => "",
    ];

    $args = wp_parse_args($args, $defaults);

    // Get athletes if not provided
    if ($args["athletes"] === null) {
        global $wp_query;
        $args["athletes"] = wp_list_pluck($wp_query->posts, "ID");
    }

    if (empty($args["athletes"])) {
        echo '<div class="athletes-empty"><p>No athletes found.</p></div>';
        return;
    }
    ?>
    <div class="athletes-table-container" data-athletes-table data-view="<?php echo esc_attr(
        $args["view"],
    ); ?>">

        <?php if (
            $args["show_search"] ||
            $args["show_filters"] ||
            $args["show_view_toggle"]
        ): ?>
        <div class="athletes-toolbar">
            <?php if ($args["show_search"]): ?>
            <div class="athletes-search">
                <span class="athletes-search-icon"><?php echo ballstreet_icon(
                    "search",
                    18,
                ); ?></span>
                <input
                    type="text"
                    class="athletes-search-input"
                    placeholder="Search athletes..."
                    data-athletes-search
                >
            </div>
            <?php endif; ?>

            <?php if ($args["show_filters"]): ?>
            <div class="athletes-filters">
                <select class="athletes-filter" data-athletes-filter="position">
                    <option value="">All Positions</option>
                    <option value="qb">Quarterback</option>
                    <option value="rb">Running Back</option>
                    <option value="wr">Wide Receiver</option>
                    <option value="te">Tight End</option>
                    <option value="ol">Offensive Line</option>
                    <option value="dl">Defensive Line</option>
                    <option value="lb">Linebacker</option>
                    <option value="db">Defensive Back</option>
                    <option value="k">Kicker</option>
                    <option value="p">Punter</option>
                </select>
                <select class="athletes-filter" data-athletes-filter="school">
                    <option value="">All Schools</option>
                    <?php
                    $schools = get_posts([
                        "post_type" => "school",
                        "posts_per_page" => -1,
                        "orderby" => "title",
                        "order" => "ASC",
                    ]);
                    foreach ($schools as $school): ?>
                        <option value="<?php echo esc_attr(
                            strtolower($school->post_title),
                        ); ?>">
                            <?php echo esc_html($school->post_title); ?>
                        </option>
                    <?php endforeach;
                    ?>
                </select>
            </div>
            <?php endif; ?>

            <?php if ($args["show_view_toggle"]): ?>
            <div class="athletes-view-toggle">
                <button class="view-btn <?php echo $args["view"] === "table"
                    ? "active"
                    : ""; ?>" data-view="table" aria-label="Table view">
                    <?php echo ballstreet_icon("table", 18); ?>
                </button>
                <button class="view-btn <?php echo $args["view"] === "cards"
                    ? "active"
                    : ""; ?>" data-view="cards" aria-label="Card view">
                    <?php echo ballstreet_icon("grid", 18); ?>
                </button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Table View -->
        <div class="athletes-view athletes-view-table <?php echo $args[
            "view"
        ] === "table"
            ? "is-active"
            : ""; ?>">
            <div class="athletes-table-wrapper">
                <table class="athletes-table">
                    <thead>
                        <tr>
                            <?php if ($args["show_rank"]): ?>
                            <th class="col-rank">
                                <button class="sort-btn" data-athletes-sort="rank">#</button>
                            </th>
                            <?php endif; ?>
                            <th class="col-player">
                                <button class="sort-btn" data-athletes-sort="name">Player</button>
                            </th>
                            <th class="col-nil">
                                <button class="sort-btn" data-athletes-sort="nil">NIL Value</button>
                            </th>
                            <th class="col-school">School</th>
                            <th class="col-sponsors">Sponsors</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rank = 1;
                        foreach ($args["athletes"] as $athlete_id):
                            $fields = ballstreet_get_athlete_fields(
                                $athlete_id,
                            ); ?>
                        <tr class="athlete-row"
                            data-athlete-row
                            data-name="<?php echo esc_attr(
                                strtolower(get_the_title($athlete_id)),
                            ); ?>"
                            data-position="<?php echo esc_attr(
                                strtolower($fields["position"]),
                            ); ?>"
                            data-school="<?php echo esc_attr(
                                strtolower($fields["school_name"]),
                            ); ?>"
                            data-nil="<?php echo esc_attr(
                                $fields["nil_valuation"],
                            ); ?>"
                            data-rank="<?php echo $rank; ?>">

                            <?php if ($args["show_rank"]): ?>
                            <td class="col-rank">
                                <span class="athlete-rank"><?php echo $rank; ?></span>
                            </td>
                            <?php endif; ?>

                            <td class="col-player">
                                <a href="<?php echo get_permalink(
                                    $athlete_id,
                                ); ?>" class="athlete-player">
                                    <div class="athlete-avatar">
                                        <?php if (
                                            has_post_thumbnail($athlete_id)
                                        ): ?>
                                            <?php echo get_the_post_thumbnail(
                                                $athlete_id,
                                                "thumbnail",
                                                ["class" => "athlete-photo"],
                                            ); ?>
                                        <?php else: ?>
                                            <span class="athlete-initials"><?php echo esc_html(
                                                substr(
                                                    get_the_title($athlete_id),
                                                    0,
                                                    2,
                                                ),
                                            ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="athlete-info">
                                        <span class="athlete-name"><?php echo get_the_title(
                                            $athlete_id,
                                        ); ?></span>
                                        <?php if ($fields["position"]): ?>
                                            <span class="athlete-position"><?php echo esc_html(
                                                $fields["position"],
                                            ); ?></span>
                                        <?php endif; ?>
                                        <?php if (
                                            !empty($fields["player_info"])
                                        ): ?>
                                            <span class="athlete-details"><?php echo esc_html(
                                                implode(
                                                    " · ",
                                                    $fields["player_info"],
                                                ),
                                            ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </td>

                            <td class="col-nil">
                                <?php if ($fields["nil_valuation"]): ?>
                                    <div class="nil-value">
                                        <span class="nil-amount"><?php echo ballstreet_format_value(
                                            $fields["nil_valuation"],
                                        ); ?></span>
                                        <span class="nil-trend up">↑ 12%</span>
                                    </div>
                                <?php else: ?>
                                    <span class="nil-empty">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="col-school">
                                <?php if ($fields["school_id"]): ?>
                                    <div class="athlete-school">
                                        <?php if (
                                            has_post_thumbnail(
                                                $fields["school_id"],
                                            )
                                        ): ?>
                                            <?php echo get_the_post_thumbnail(
                                                $fields["school_id"],
                                                "thumbnail",
                                                ["class" => "school-logo"],
                                            ); ?>
                                        <?php endif; ?>
                                        <span class="school-name"><?php echo esc_html(
                                            $fields["school_name"],
                                        ); ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="school-empty">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="col-sponsors">
                                <?php if (!empty($fields["sponsor_images"])): ?>
                                    <div class="athlete-sponsors">
                                        <?php foreach (
                                            array_slice(
                                                $fields["sponsor_images"],
                                                0,
                                                4,
                                            )
                                            as $image
                                        ): ?>
                                            <?php echo $image; ?>
                                        <?php endforeach; ?>
                                        <?php if (
                                            count($fields["sponsor_images"]) > 4
                                        ): ?>
                                            <span class="sponsors-more">+<?php echo count(
                                                $fields["sponsor_images"],
                                            ) - 4; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="sponsors-empty">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php $rank++;
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cards View -->
        <div class="athletes-view athletes-view-cards <?php echo $args[
            "view"
        ] === "cards"
            ? "is-active"
            : ""; ?>">
            <div class="athletes-cards-grid">
                <?php
                $rank = 1;
                foreach ($args["athletes"] as $athlete_id):
                    $fields = ballstreet_get_athlete_fields($athlete_id); ?>
                <article class="athlete-card"
                    data-athlete-row
                    data-name="<?php echo esc_attr(
                        strtolower(get_the_title($athlete_id)),
                    ); ?>"
                    data-position="<?php echo esc_attr(
                        strtolower($fields["position"]),
                    ); ?>"
                    data-school="<?php echo esc_attr(
                        strtolower($fields["school_name"]),
                    ); ?>"
                    data-nil="<?php echo esc_attr($fields["nil_valuation"]); ?>"
                    data-rank="<?php echo $rank; ?>">

                    <a href="<?php echo get_permalink(
                        $athlete_id,
                    ); ?>" class="athlete-card-link">
                        <?php if ($args["show_rank"]): ?>
                            <span class="athlete-card-rank">#<?php echo $rank; ?></span>
                        <?php endif; ?>

                        <div class="athlete-card-header">
                            <div class="athlete-card-avatar">
                                <?php if (has_post_thumbnail($athlete_id)): ?>
                                    <?php echo get_the_post_thumbnail(
                                        $athlete_id,
                                        "medium",
                                        ["class" => "athlete-card-photo"],
                                    ); ?>
                                <?php else: ?>
                                    <span class="athlete-card-initials"><?php echo esc_html(
                                        substr(
                                            get_the_title($athlete_id),
                                            0,
                                            2,
                                        ),
                                    ); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($fields["nil_valuation"]): ?>
                            <div class="athlete-card-nil">
                                <span class="nil-label">NIL Value</span>
                                <span class="nil-amount"><?php echo ballstreet_format_value(
                                    $fields["nil_valuation"],
                                ); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="athlete-card-body">
                            <h3 class="athlete-card-name"><?php echo get_the_title(
                                $athlete_id,
                            ); ?></h3>

                            <?php if ($fields["position"]): ?>
                                <span class="athlete-card-position"><?php echo esc_html(
                                    $fields["position"],
                                ); ?></span>
                            <?php endif; ?>

                            <?php if ($fields["school_name"]): ?>
                            <div class="athlete-card-school">
                                <?php if (
                                    has_post_thumbnail($fields["school_id"])
                                ): ?>
                                    <?php echo get_the_post_thumbnail(
                                        $fields["school_id"],
                                        "thumbnail",
                                        ["class" => "school-logo-sm"],
                                    ); ?>
                                <?php endif; ?>
                                <span><?php echo esc_html(
                                    $fields["school_name"],
                                ); ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($fields["sponsor_images"])): ?>
                        <div class="athlete-card-sponsors">
                            <?php foreach (
                                array_slice($fields["sponsor_images"], 0, 3)
                                as $image
                            ): ?>
                                <?php echo $image; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </a>
                </article>
                <?php $rank++;
                endforeach;?>
            </div>
        </div>

        <div class="athletes-no-results" style="display: none;">
            <p>No athletes found matching your search.</p>
        </div>
    </div>
    <?php
}
