<?php
// Safe filemtime — returns fallback version if file doesn't exist
function ofo_filemtime($path) {
    return file_exists($path) ? filemtime($path) : '1.0.0';
}

/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
	if ( ! bricks_is_builder_main() ) {
		wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], ofo_filemtime( get_stylesheet_directory() . '/style.css' ) );
	}
} );

/**
 * Register custom elements
 */
add_action( 'init', function() {
  $element_files = [
    __DIR__ . '/elements/title.php',
  ];

  foreach ( $element_files as $file ) {
    \Bricks\Elements::register_element( $file );
  }
}, 11 );

/**
 * Add text strings to builder
 */
add_filter( 'bricks/builder/i18n', function( $i18n ) {
  // For element category 'custom'
  $i18n['custom'] = esc_html__( 'Custom', 'bricks' );

  return $i18n;
} );

// Product CSV export tool (admin only) — visit ?ofo_export_csv=1
add_action('init', 'ofo_export_products_csv');
function ofo_export_products_csv() {
    if (!isset($_GET['ofo_export_csv'])) return;
    if (!current_user_can('manage_options')) { wp_die('Admin access required.'); }
    $args = array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC');
    $query = new WP_Query($args);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ofo-products-' . date('Y-m-d') . '.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, array('Product ID', 'Product Name', 'Price', 'Case Pack (current)', 'Case Pack (from description)', 'Shot Count (current)', 'Shot Count (from description)', 'Categories', 'Product URL'));
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product) continue;
            $desc = $product->get_description() . ' ' . $product->get_short_description() . ' ' . $product->get_name();
            $cp_cur = $product->get_attribute('case_pack'); if (!$cp_cur) $cp_cur = get_post_meta(get_the_ID(), '_case_pack', true);
            $cp_desc = ''; if (preg_match('/(\d+)\s*\/\s*1\b/', $desc, $m)) $cp_desc = intval($m[1]);
            $sc_cur = $product->get_attribute('shot_count'); if (!$sc_cur) $sc_cur = get_post_meta(get_the_ID(), '_shot_count', true);
            $sc_desc = ''; if (preg_match('/(\d+)\s*[-\s]?\s*shots?/i', $desc, $m)) $sc_desc = intval($m[1]);
            $terms = get_the_terms(get_the_ID(), 'product_cat'); $cats = '';
            if ($terms && !is_wp_error($terms)) { $cn = array(); foreach ($terms as $t) { if ($t->slug !== 'uncategorized') $cn[] = $t->name; } $cats = implode(', ', $cn); }
            fputcsv($out, array($product->get_id(), $product->get_name(), $product->get_price(), $cp_cur ?: '', $cp_desc, $sc_cur ?: '', $sc_desc, $cats, get_permalink(get_the_ID())));
        }
        wp_reset_postdata();
    }
    fclose($out);
    exit;
}

// Only load custom tweaks if ?notest is not present in the URL.
if (! isset($_GET['notest'])) {
  require_once get_stylesheet_directory() . '/includes/footer-main.php';
  require_once get_stylesheet_directory() . '/includes/header-main.php';
  require_once get_stylesheet_directory() . '/includes/homepage-slider.php';
  require_once get_stylesheet_directory() . '/includes/homepage-recproducts.php';
  require_once get_stylesheet_directory() . '/includes/homepage-content.php';
  require_once get_stylesheet_directory() . '/includes/homepage-review.php';
}

function tb_wistia_embed_from_url( $url, $ratio = 56.25 ) {
    $url = trim((string)$url);
    if (empty($url)) return '';

    $id = '';
    if ( preg_match('~medias/([a-z0-9]+)~i', $url, $m) ) {
        $id = $m[1];
    } elseif ( preg_match('~wistia.*?/([a-z0-9]{10})~i', $url, $m) ) {
        $id = $m[1];
    }
    if ( empty($id) ) return '';

    static $script_done = false;
    $script = '';
    if ( ! $script_done ) {
        $script = '<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>';
        $script_done = true;
    }

    $ratio = floatval($ratio);

    return $script . '
    <div class="wistia_responsive_padding" style="padding: ' . esc_attr($ratio) . '% 0 0 0; position: relative;">
      <div class="wistia_responsive_wrapper" style="height:100%; left:0; position:absolute; top:0; width:100%;">
        <div class="wistia_embed wistia_async_' . esc_attr($id) . ' seo=false videoFoam=true" style="height:100%; width:100%">&nbsp;</div>
      </div>
    </div>';
}

add_filter('the_content', function ($content) {
  if (is_admin() || ! is_singular()) return $content;

  if (! preg_match('/<h1[^>]*>/i', $content)) {
    $title = '<h1 class="seo-only-h1">' . get_the_title() . '</h1>';
    return $title . $content;
  }

  return $content;
}, 1);


use BRTheme\WishlistHandler;

// Wishlist count AJAX
add_action('wp_ajax_get_wishlist_count', 'custom_get_wishlist_count');
add_action('wp_ajax_nopriv_get_wishlist_count', 'custom_get_wishlist_count');

function custom_get_wishlist_count()
{
  if (! class_exists('\BRTheme\WishlistHandler')) {
    wp_send_json_error(
      array(
        'message' => 'WishlistHandler not available',
      )
    );
  }

  $count = WishlistHandler::count(); // uses get_user_wishlist_items() internally

  wp_send_json_success(
    array(
      'count' => (int) $count,
    )
  );
}

// === Cart count AJAX ===
add_action('wp_ajax_get_cart_count', 'custom_get_cart_count');
add_action('wp_ajax_nopriv_get_cart_count', 'custom_get_cart_count');

function custom_get_cart_count()
{
  if (!function_exists('WC')) {
    wp_send_json_error(array('message' => 'WooCommerce not loaded'));
  }

  // Make sure cart is loaded (WC 8+ safe)
  if (is_null(WC()->cart) && function_exists('wc_load_cart')) {
    wc_load_cart();
  }

  $count = 0;

  if (WC()->cart) {
    $count = WC()->cart->get_cart_contents_count();
  }

  wp_send_json_success(array(
    'count' => (int) $count,
  ));
}

// === Add Loyalty Rewards endpoint to WooCommerce My Account ===
add_action('init', function() {
    // Add endpoint for my-account page only (not EP_ROOT to avoid conflicts)
    add_rewrite_endpoint('loyalty-rewards', EP_PAGES);
});

// Add to My Account menu
add_filter('woocommerce_account_menu_items', function($items) {
    // Insert after Dashboard
    $new_items = array();
    foreach ($items as $key => $item) {
        $new_items[$key] = $item;
        if ($key === 'dashboard') {
            $new_items['loyalty-rewards'] = 'Loyalty Rewards';
        }
    }
    return $new_items;
}, 99);

// Add content for the endpoint (my-account/loyalty-rewards/)
add_action('woocommerce_account_loyalty-rewards_endpoint', function() {
    // Check if WPGENS shortcode exists
    if (shortcode_exists('WPGENS_POINTS_PAGE')) {
        echo do_shortcode('[WPGENS_POINTS_PAGE]');
    } else {
        // Fallback: Show loyalty rewards page content
        $loyalty_page = get_page_by_path('loyalty-rewards');
        if ($loyalty_page) {
            echo apply_filters('the_content', $loyalty_page->post_content);
        } else {
            echo '<p>Loyalty rewards content will be displayed here.</p>';
        }
    }
});

// === Handle /loyalty-rewards/ URL display ===
// This works even if the page doesn't exist in WordPress
add_action('template_redirect', function() {
    // Get the current request URI
    $request_uri = isset($_SERVER['REQUEST_URI']) ? trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') : '';
    
    // Only handle if it's exactly /loyalty-rewards/ and not my-account/loyalty-rewards/
    if ($request_uri === 'loyalty-rewards' && !is_account_page() && !is_admin()) {
        // Prevent 404
        global $wp_query;
        status_header(200);
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $wp_query->is_home = false;
        $wp_query->is_archive = false;
        
        // Set up fake post data so WordPress thinks it's a page
        $wp_query->queried_object = (object) array(
            'ID' => 0,
            'post_title' => 'Loyalty Rewards',
            'post_name' => 'loyalty-rewards',
            'post_type' => 'page',
            'post_status' => 'publish',
            'post_content' => '[loyalty_rewards_content]'
        );
        $wp_query->queried_object_id = 0;
        $wp_query->post_count = 1;
        $wp_query->posts = array($wp_query->queried_object);
        
        // Display the content
        get_header();
        echo do_shortcode('[loyalty_rewards_content]');
        get_footer();
        exit;
    }
}, 1);

// Flush rewrite rules on theme activation (one-time)
add_action('after_switch_theme', function() {
    flush_rewrite_rules();
});

// === Add Bricks support for Loyalty Rewards endpoint ===
// Hook into Bricks' add_my_account_content method to add loyalty-rewards support
add_action('woocommerce_account_content', function() {
    // Only process if we're on the loyalty-rewards endpoint
    if (!is_wc_endpoint_url('loyalty-rewards')) {
        return;
    }
    
    // Try to get Bricks template for loyalty rewards
    $template_data = \Bricks\Woocommerce::get_template_data_by_type('wc_account_loyalty_rewards');
    
    if ($template_data) {
        // Remove default WooCommerce content
        remove_action('woocommerce_account_content', 'woocommerce_account_content');
        echo $template_data;
        return;
    }
    // If no Bricks template, let the default endpoint action handle it
}, 1);

// Add loyalty-rewards to Bricks account menu item classes
add_filter('woocommerce_account_menu_item_classes', function($classes, $endpoint) {
    if (is_wc_endpoint_url('loyalty-rewards') && $endpoint === 'loyalty-rewards') {
        $classes[] = 'is-active';
    }
    return $classes;
}, 10, 2);

// === Loyalty Rewards Content Shortcode ===
add_shortcode('loyalty_rewards_content', function($atts) {
    ob_start();
    // Include the loyalty rewards content file
    include get_stylesheet_directory() . '/includes/loyalty-rewards-content.php';
    return ob_get_clean();
});

// === Floating Loyalty Rewards Button on Homepage ===
add_action('wp_footer', function() {
    include get_stylesheet_directory() . '/includes/loyalty-floating-button.php';
});

// === Homepage Slider Configuration ===
// Edit this array to add, remove, or modify slider slides
// All slider content is managed here for easy editing
function get_homepage_slider_data() {
    return array(
        // Slide 1 — America's 250th Birthday
        array(
            'desktop_image' => '/wp-content/uploads/2026/02/2OFO-Hero-Background.jpg',
            'mobile_image' => '/wp-content/uploads/2026/02/1OFO-Hero-Background-Mobile.jpg',
            'small_text' => '🇺🇸 JULY 4TH, 2026 — AMERICA TURNS 250',
            'heading' => "250 YEARS OF FREEDOM.<br>CELEBRATE LIKE IT.",
            'description' => 'The Biggest Birthday in American History Deserves the Biggest Fireworks Show — Wholesale Pricing, No Minimum Order',
            'button_text' => 'SHOP THE 250TH COLLECTION',
            'button_url' => '/product-category/aerial-fireworks/500g-cakes/',
            'button2_text' => 'BUILD YOUR PALLET',
            'button2_url' => '/build-your-custom-pallet/',
        ),
        // Slide 2 — Price punch
        array(
            'desktop_image' => '/wp-content/uploads/2026/02/2OFO-Hero-Background.jpg',
            'mobile_image' => '/wp-content/uploads/2026/02/1OFO-Hero-Background-Mobile.jpg',
            'small_text' => '💥 WHOLESALE CASE PRICING — UP TO 87% CHEAPER',
            'heading' => "DON'T BUY RETAIL.<br>BUY WHOLESALE.",
            'description' => 'Why Pay $67 Per Firework When You Can Pay $8? Same Products. Case Pricing. Massive Savings.',
            'button_text' => 'SEE THE SAVINGS',
            'button_url' => '/build-your-custom-pallet/',
            'button2_text' => 'SHOP ALL FIREWORKS',
            'button2_url' => '/shop/',
        ),
        // Slide 3 — Pallet builder push
        array(
            'desktop_image' => '/wp-content/uploads/2026/02/2OFO-Hero-Background.jpg',
            'mobile_image' => '/wp-content/uploads/2026/02/1OFO-Hero-Background-Mobile.jpg',
            'small_text' => 'AMERICA\'S #1 WHOLESALE FIREWORKS',
            'heading' => "BUILD YOUR<br>DREAM SHOW.",
            'description' => 'Mix 500g Cakes, Artillery & Ground Effects — See Your Savings Live as You Build',
            'button_text' => 'BUILD YOUR PALLET NOW',
            'button_url' => '/build-your-custom-pallet/',
        ),
    );
}

// ============================================================
// Product Schema for Google Rich Results
// ============================================================
add_action('wp_head', 'ofo_add_product_schema');
function ofo_add_product_schema() {
    if (!is_product()) return;
    global $product;
    if (!$product) return;
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => get_the_title(),
        'description' => wp_strip_all_tags($product->get_description()),
        'image' => wp_get_attachment_url($product->get_image_id()),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => 'USD',
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
        ),
    );
    $avg_rating = $product->get_average_rating();
    $review_count = $product->get_review_count();
    if ($avg_rating && $review_count) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $avg_rating,
            'reviewCount' => $review_count,
        );
    }
    echo '<script type="application/ld+json">' . json_encode($schema) . '</script>';
}

// Per-unit price display below WooCommerce price
add_filter('woocommerce_get_price_html', 'ofo_add_per_unit_price', 10, 2);
function ofo_add_per_unit_price($price_html, $product) {
    if (is_admin()) return $price_html;
    $case_pack = $product->get_attribute('case_pack');
    if (!$case_pack) $case_pack = get_post_meta($product->get_id(), '_case_pack', true);
    if (!$case_pack || !is_numeric($case_pack) || $case_pack <= 1) return $price_html;
    $unit_price = $product->get_price() / intval($case_pack);
    $formatted = wc_price($unit_price);
    $callout = '<span class="ofo-per-unit-price">≈ ' . $formatted . ' per unit (case of ' . intval($case_pack) . ')</span>';
    return $price_html . $callout;
}

// Cart savings display
add_action('woocommerce_cart_totals_before_order_total', 'ofo_cart_savings_display');
function ofo_cart_savings_display() {
    $savings = 0;
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $qty = $cart_item['quantity'];
        $regular = $product->get_regular_price();
        $sale = $product->get_price();
        if ($regular && $sale && $regular > $sale) {
            $savings += ($regular - $sale) * $qty;
        }
    }
    if ($savings > 0) {
        echo '<tr class="ofo-cart-savings">
            <th>🎉 You\'re saving</th>
            <td><strong style="color:#1a8a3c;font-size:1.1em;">' . wc_price($savings) . '</strong></td>
        </tr>';
    }
}

// SEO meta title format for products
add_filter('wpseo_title', 'ofo_product_seo_title');
add_filter('rank_math/frontend/title', 'ofo_product_seo_title');
function ofo_product_seo_title($title) {
    if (!is_product()) return $title;
    global $product;
    if (!$product) return $title;
    $shot_count = get_post_meta($product->get_id(), '_shot_count', true);
    $case_pack = get_post_meta($product->get_id(), '_case_pack', true);
    $name = $product->get_name();
    if ($shot_count) {
        return $name . ' | ' . $shot_count . '-Shot Firework | Buy Online — OFO';
    }
    return $name . ' | Buy Fireworks Online — OFO';
}

// ============================================================
// PHASE 6: Custom Pallet Builder shortcode (v2 — AJAX-powered)
// ============================================================
add_shortcode('ofo_pallet_builder', 'ofo_render_pallet_builder');
function ofo_render_pallet_builder($atts) {
    ofo_enqueue_pallet_builder_assets();
    ob_start();
    $template = get_stylesheet_directory() . '/templates/pallet-builder.php';
    if (file_exists($template)) { include $template; } else { echo '<p>Template not found.</p>'; }
    return ob_get_clean();
}
function ofo_enqueue_pallet_builder_assets() {
    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();
    wp_enqueue_style('ofo-pallet-builder', $theme_uri . '/custom-css/pallet-builder.css', array(), ofo_filemtime($theme_dir . '/custom-css/pallet-builder.css'));
    wp_enqueue_script('ofo-pallet-builder', $theme_uri . '/custom-js/pallet-builder.js', array('jquery'), ofo_filemtime($theme_dir . '/custom-js/pallet-builder.js'), true);
    wp_localize_script('ofo-pallet-builder', 'ofo_pallet_data', array('ajax_url' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('ofo_pallet_nonce'), 'cart_url' => wc_get_cart_url()));
}
add_action('wp_ajax_ofo_get_pallet_products', 'ofo_ajax_get_pallet_products');
add_action('wp_ajax_nopriv_ofo_get_pallet_products', 'ofo_ajax_get_pallet_products');
function ofo_ajax_get_pallet_products() {
    check_ajax_referer('ofo_pallet_nonce', 'nonce');
    $args = array('post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => 80, 'orderby' => 'menu_order', 'order' => 'ASC');
    $query = new WP_Query($args);
    $products = array();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product || !$product->is_purchasable()) continue;
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            $cats = array(); $cat_label = '';
            if ($terms && !is_wp_error($terms)) {
                foreach ($terms as $term) { $cats[] = $term->slug; }
                foreach ($terms as $term) { if ($term->slug !== 'uncategorized') { $cat_label = $term->name; break; } }
            }
            $desc = $product->get_description() . ' ' . $product->get_short_description() . ' ' . $product->get_name();

            // Case pack: check attribute, custom field, then auto-extract from description
            $case_pack = $product->get_attribute('case_pack');
            if (!$case_pack) $case_pack = get_post_meta(get_the_ID(), '_case_pack', true);
            if (!$case_pack || intval($case_pack) <= 0) {
                // Auto-extract: "6/1", "4/1", "12/1", "Packed 6/1", "Case 4/1"
                if (preg_match('/(\d+)\s*\/\s*1\b/', $desc, $m)) {
                    $case_pack = intval($m[1]);
                }
            }
            if (!$case_pack || intval($case_pack) <= 0) $case_pack = 1;

            // Shot count: check attribute, custom field, then auto-extract from description
            $shot_count = $product->get_attribute('shot_count');
            if (!$shot_count) $shot_count = get_post_meta(get_the_ID(), '_shot_count', true);
            if (!$shot_count || intval($shot_count) <= 0) {
                // Auto-extract: "25 shots", "100-shot", "16 Shot", "9-Shot"
                if (preg_match('/(\d+)\s*[-\s]?\s*shots?/i', $desc, $m)) {
                    $shot_count = intval($m[1]);
                }
            }
            $retail_price = get_post_meta(get_the_ID(), '_retail_price', true);
            $image_id = $product->get_image_id();
            $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'woocommerce_thumbnail') : '';
            $products[] = array('id' => $product->get_id(), 'name' => $product->get_name(), 'price' => $product->get_price(), 'retail_price' => $retail_price ? $retail_price : '', 'case_pack' => intval($case_pack), 'shot_count' => intval($shot_count), 'categories' => $cats, 'category_label' => $cat_label, 'image' => $image_url);
        }
        wp_reset_postdata();
    }
    if (empty($products)) { wp_send_json_error('No products found'); }
    wp_send_json_success($products);
}
add_action('wp_ajax_ofo_add_pallet_to_cart', 'ofo_ajax_add_pallet_to_cart');
add_action('wp_ajax_nopriv_ofo_add_pallet_to_cart', 'ofo_ajax_add_pallet_to_cart');
function ofo_ajax_add_pallet_to_cart() {
    check_ajax_referer('ofo_pallet_nonce', 'nonce');
    $items = json_decode(wp_unslash($_POST['items']), true);
    if (empty($items)) { wp_send_json_error('No items provided'); }
    $errors = array();
    foreach ($items as $item) {
        $added = WC()->cart->add_to_cart(intval($item['product_id']), intval($item['quantity']));
        if (!$added) { $p = wc_get_product(intval($item['product_id'])); $errors[] = $p ? $p->get_name() : 'Unknown'; }
    }
    if (!empty($errors)) { wp_send_json_error('Could not add: ' . implode(', ', $errors)); }
    wp_send_json_success(array('cart_url' => wc_get_cart_url()));
}

// ============================================================
// FEATURE 1: Price Match Guarantee Badge on product pages
// ============================================================
add_action('woocommerce_single_product_summary', 'ofo_price_match_badge', 25);
function ofo_price_match_badge() {
    echo '<div class="ofo-price-match-badge">
        <span class="ofo-pm-icon">&#10003;</span>
        <span><strong>Price Match Guarantee</strong> — Found it cheaper? We will match it.</span>
    </div>';
}

// ============================================================
// FEATURE 2: New Arrivals badge (auto on products under 60 days old)
// ============================================================
add_action('woocommerce_before_shop_loop_item_title', 'ofo_new_arrival_badge', 8);
function ofo_new_arrival_badge() {
    $created = get_the_date('U');
    $days_old = (time() - $created) / (60 * 60 * 24);
    if ($days_old <= 60) {
        echo '<span class="ofo-new-badge">NEW</span>';
    }
}

// ============================================================
// FEATURE 3: FAQ Shortcode [ofo_faq]
// ============================================================
add_shortcode('ofo_faq', 'ofo_render_faq');
function ofo_render_faq($atts) {
    $faqs = array(
        array('q' => 'Do you offer wholesale pricing?', 'a' => 'Yes — all products on OFO are sold at wholesale case pricing. No membership required, no minimum order. You get the same prices whether you buy 1 case or 100.'),
        array('q' => 'What is your shipping policy?', 'a' => '\$99 flat rate shipping on orders over \$1,500. FREE shipping on orders over \$3,000. Orders ship via freight carrier directly to your door.'),
        array('q' => 'How much cheaper are your prices vs retail?', 'a' => 'Our case pricing works out to roughly \$6-\$25 per unit depending on the product. Retail stores charge \$50-\$150+ per unit for the same items. You can save up to 87% buying wholesale from OFO.'),
        array('q' => 'What is a case pack?', 'a' => 'A case pack is a bulk quantity of the same product. For example, a 6/1 case pack means 6 individual fireworks in one case. The price shown is for the entire case.'),
        array('q' => 'Is there a minimum order?', 'a' => 'No minimum order required. Buy as little as one case of any product.'),
        array('q' => 'What states do you ship to?', 'a' => 'We ship to most US states where consumer fireworks are legal. Contact us at 803-849-0221 to confirm your state before ordering.'),
        array('q' => 'Do you offer a price match guarantee?', 'a' => 'Yes. If you find the same product cheaper at another wholesale fireworks retailer, contact us and we will match or beat their price.'),
        array('q' => 'How do I track my order?', 'a' => 'Once your order ships you will receive a tracking number via email. Log into your account at any time to check your order status.'),
        array('q' => 'What is your return policy?', 'a' => 'Due to the nature of fireworks, we do not accept returns on opened cases. Damaged or defective items will be replaced. Contact us within 7 days of delivery.'),
        array('q' => 'Can I pick up my order?', 'a' => 'Yes — local pickup is available at our Georgia location. Call 803-849-0221 to arrange.'),
    );
    ob_start();
    echo '<div class="ofo-faq-container">';
    foreach ($faqs as $i => $faq) {
        echo '<div class="ofo-faq-item">';
        echo '<button class="ofo-faq-question" onclick="ofoToggleFaq(' . $i . ')">';
        echo '<span>' . esc_html($faq['q']) . '</span>';
        echo '<span class="ofo-faq-arrow" id="ofo-arrow-' . $i . '">+</span>';
        echo '</button>';
        echo '<div class="ofo-faq-answer" id="ofo-faq-' . $i . '"><p>' . esc_html($faq['a']) . '</p></div>';
        echo '</div>';
    }
    echo '</div>';
    echo '<script>function ofoToggleFaq(i){var el=document.getElementById("ofo-faq-"+i);var arrow=document.getElementById("ofo-arrow-"+i);var isOpen=el.style.display==="block";document.querySelectorAll(".ofo-faq-answer").forEach(function(e){e.style.display="none";});document.querySelectorAll(".ofo-faq-arrow").forEach(function(a){a.textContent="+";});if(!isOpen){el.style.display="block";arrow.textContent="−";}}</script>';
    return ob_get_clean();
}

// ============================================================
// FEATURE 4: Competitor price comparison on single product pages
// ============================================================
add_action('woocommerce_single_product_summary', 'ofo_competitor_comparison', 26);
function ofo_competitor_comparison() {
    global $product;
    $case_pack = $product->get_attribute('case_pack');
    if (!$case_pack) $case_pack = get_post_meta($product->get_id(), '_case_pack', true);
    if (!$case_pack || !is_numeric($case_pack) || $case_pack <= 1) return;
    $our_price = $product->get_price();
    $our_unit = $our_price / intval($case_pack);
    $retail_unit = $our_unit * 8;
    echo '<div class="ofo-price-comparison">
        <div class="ofo-pc-title">Price Comparison (per unit)</div>
        <div class="ofo-pc-row"><span class="ofo-pc-store ofo-pc-us">OFO Wholesale</span><span class="ofo-pc-price ofo-pc-us-price">$' . number_format($our_unit, 2) . '</span></div>
        <div class="ofo-pc-row"><span class="ofo-pc-store">Retail Store Avg</span><span class="ofo-pc-price ofo-pc-retail">~$' . number_format($retail_unit, 2) . '</span></div>
        <div class="ofo-pc-savings">You save ~' . round((1 - $our_unit/$retail_unit) * 100) . '% buying wholesale from OFO</div>
    </div>';
}

// ============================================================
// FEATURE 5: New Arrivals category — auto query products under 60 days
// ============================================================
add_shortcode('ofo_new_arrivals', 'ofo_render_new_arrivals');
function ofo_render_new_arrivals($atts) {
    $atts = shortcode_atts(array('limit' => 8), $atts);
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => intval($atts['limit']),
        'post_status' => 'publish',
        'date_query' => array(array('after' => '60 days ago')),
        'orderby' => 'date',
        'order' => 'DESC',
    );
    $query = new WP_Query($args);
    if (!$query->have_posts()) return '<p>No new arrivals yet.</p>';
    ob_start();
    echo '<div class="ofo-new-arrivals-grid">';
    while ($query->have_posts()) {
        $query->the_post();
        $product = wc_get_product(get_the_ID());
        $img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: wc_placeholder_img_src();
        echo '<div class="ofo-na-card">';
        echo '<span class="ofo-new-badge">NEW</span>';
        echo '<a href="' . get_permalink() . '"><img src="' . esc_url($img) . '" alt="' . get_the_title() . '"></a>';
        echo '<div class="ofo-na-body"><h4><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
        echo '<span class="ofo-na-price">' . $product->get_price_html() . '</span></div>';
        echo '</div>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}

// ============================================================
// FEATURE 6: Help Center shortcode [ofo_help_center]
// ============================================================
add_shortcode('ofo_help_center', 'ofo_render_help_center');
function ofo_render_help_center($atts) {
    $topics = array(
        array('icon' => '&#128666;', 'title' => 'Orders & Shipping', 'desc' => 'Track orders, shipping rates, delivery times', 'link' => '/faq/#shipping'),
        array('icon' => '&#128176;', 'title' => 'Pricing & Savings', 'desc' => 'Wholesale pricing, case packs, how to save', 'link' => '/faq/#pricing'),
        array('icon' => '&#128722;', 'title' => 'Returns & Damage', 'desc' => 'Damaged items, return policy, replacements', 'link' => '/faq/#returns'),
        array('icon' => '&#128222;', 'title' => 'Contact Us', 'desc' => '803-849-0221 · Weekdays 8am-4pm EST', 'link' => '/contact/'),
        array('icon' => '&#128293;', 'title' => 'Product Questions', 'desc' => 'Case packs, shot counts, effects explained', 'link' => '/faq/#products'),
        array('icon' => '&#128508;', 'title' => 'State Shipping Laws', 'desc' => 'Which states we can ship fireworks to', 'link' => '/faq/#states'),
    );
    ob_start();
    echo '<div class="ofo-help-grid">';
    foreach ($topics as $t) {
        echo '<a href="' . $t['link'] . '" class="ofo-help-card">';
        echo '<div class="ofo-help-icon">' . $t['icon'] . '</div>';
        echo '<h3>' . $t['title'] . '</h3>';
        echo '<p>' . $t['desc'] . '</p>';
        echo '</a>';
    }
    echo '</div>';
    return ob_get_clean();
}

// ============================================================
// FEATURE 7: Merica's Birthday Bash Countdown [ofo_countdown]
// Also auto-renders on the homepage via wp_footer
// ============================================================
add_shortcode('ofo_countdown', 'ofo_render_countdown');

// Auto-inject countdown on the homepage after the content
add_action('wp_footer', 'ofo_auto_inject_countdown', 5);
function ofo_auto_inject_countdown() {
    // Render on all pages — the JS will position it after the slider if present
    echo ofo_render_countdown(array());
    echo '<script>
    (function(){
        var slider = document.querySelector(".slider-container");
        var countdown = document.getElementById("ofo-countdown-section");
        if (slider && countdown) {
            slider.parentNode.insertBefore(countdown, slider.nextSibling);
        }
    })();
    </script>';
}
function ofo_render_countdown($atts) {
    $atts = shortcode_atts(array(
        'target' => '2026-07-04T00:00:00',
        'timezone' => 'America/New_York',
    ), $atts);

    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();

    wp_enqueue_style('ofo-countdown', $theme_uri . '/custom-css/countdown.css', array(), ofo_filemtime($theme_dir . '/custom-css/countdown.css'));
    wp_enqueue_script('ofo-countdown', $theme_uri . '/custom-js/countdown.js', array(), ofo_filemtime($theme_dir . '/custom-js/countdown.js'), true);

    // Search media library for eagle image
    $eagle_url = '';
    $eagle_args = array(
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        's'              => 'eagle',
    );
    $eagle_query = new WP_Query($eagle_args);
    if ($eagle_query->have_posts()) {
        $eagle_query->the_post();
        $eagle_url = wp_get_attachment_image_url(get_the_ID(), 'medium_large');
        wp_reset_postdata();
    }
    if (!$eagle_url) {
        // Fallback: search by filename pattern
        global $wpdb;
        $row = $wpdb->get_row("SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_mime_type LIKE 'image/%' AND guid LIKE '%eagle%' LIMIT 1");
        if ($row) {
            $eagle_url = wp_get_attachment_image_url($row->ID, 'medium_large');
        }
    }

    ob_start();
    ?>
    <section class="ofo-countdown-section" id="ofo-countdown-section" data-target="<?php echo esc_attr($atts['target']); ?>">
      <div class="ofo-cd-stars" aria-hidden="true"></div>
      <div class="ofo-cd-inner">
        <?php if ($eagle_url) : ?>
        <div class="ofo-cd-eagle-wrap">
          <img src="<?php echo esc_url($eagle_url); ?>" alt="OFO Eagle Mascot" class="ofo-cd-eagle">
        </div>
        <?php endif; ?>
        <h2 class="ofo-cd-title">MERICA'S BIRTHDAY BASH</h2>
        <p class="ofo-cd-subtitle">The biggest fireworks sale of the year is here!</p>
        <div class="ofo-cd-timer" id="ofo-cd-timer">
          <div class="ofo-cd-unit">
            <div class="ofo-cd-flip" id="ofo-cd-days"><span class="ofo-cd-num">00</span></div>
            <span class="ofo-cd-label">Days</span>
          </div>
          <div class="ofo-cd-sep">:</div>
          <div class="ofo-cd-unit">
            <div class="ofo-cd-flip" id="ofo-cd-hours"><span class="ofo-cd-num">00</span></div>
            <span class="ofo-cd-label">Hours</span>
          </div>
          <div class="ofo-cd-sep">:</div>
          <div class="ofo-cd-unit">
            <div class="ofo-cd-flip" id="ofo-cd-mins"><span class="ofo-cd-num">00</span></div>
            <span class="ofo-cd-label">Minutes</span>
          </div>
          <div class="ofo-cd-sep">:</div>
          <div class="ofo-cd-unit">
            <div class="ofo-cd-flip" id="ofo-cd-secs"><span class="ofo-cd-num">00</span></div>
            <span class="ofo-cd-label">Seconds</span>
          </div>
        </div>
        <div class="ofo-cd-live" id="ofo-cd-live" style="display:none;">
          <span class="ofo-cd-live-text">🎉 THE SALE IS LIVE!</span>
        </div>
        <a href="/shop/" class="ofo-cd-cta" id="ofo-cd-cta">Shop the Sale</a>
      </div>
    </section>
    <?php
    return ob_get_clean();
}

// ============================================================
// Unified shipping message — removes conflicting messages
// ============================================================
add_filter('woocommerce_free_shipping_threshold', 'ofo_unified_shipping_threshold');
function ofo_unified_shipping_threshold($threshold) {
    return 1500;
}

// Override any hardcoded free shipping notice strings
add_filter('gettext', 'ofo_unified_shipping_text', 20, 3);
function ofo_unified_shipping_text($translated, $original, $domain) {
    $replacements = array(
        'Free Shipping above $2000' => '🚚 $99 Flat Rate Shipping · FREE on Orders Over $1,500',
        'Free shipping on orders over $2000' => '🚚 $99 Flat Rate Shipping · FREE on Orders Over $1,500',
        'Free shipping on orders over $2,000' => '🚚 $99 Flat Rate Shipping · FREE on Orders Over $1,500',
        '$99 Shipping on Orders Over $1500' => '🚚 $99 Flat Rate Shipping · FREE on Orders Over $1,500',
        '$99 Shipping on Orders Over $1,500' => '🚚 $99 Flat Rate Shipping · FREE on Orders Over $1,500',
    );
    foreach ($replacements as $old => $new) {
        if (strpos($translated, $old) !== false) {
            $translated = str_replace($old, $new, $translated);
        }
    }
    return $translated;
}

// ============================================================
// Rotating announcement bar
// ============================================================
add_action('wp_body_open', 'ofo_announcement_bar');
function ofo_announcement_bar() {
    ?>
    <div id="ofo-announcement-bar">
        <div class="ofo-announcement-track">
            <div class="ofo-announcement-slide active">🚚 FREE Shipping on Orders Over $1,500 — No Minimum Order</div>
            <div class="ofo-announcement-slide">💥 Up to 87% Cheaper Per Unit Than Buying Retail</div>
            <div class="ofo-announcement-slide">🏆 Wholesale Pricing on 500g Cakes, Artillery &amp; Pallet Packs</div>
        </div>
    </div>
    <?php
}

// Enqueue announcement bar assets
add_action('wp_enqueue_scripts', 'ofo_enqueue_announcement_assets');
function ofo_enqueue_announcement_assets() {
    $theme_uri = get_stylesheet_directory_uri();
    $theme_dir = get_stylesheet_directory();
    wp_enqueue_style('ofo-announcement-bar', $theme_uri . '/custom-css/announcement-bar.css', array(), ofo_filemtime($theme_dir . '/custom-css/announcement-bar.css'));
    wp_enqueue_script('ofo-announcement-bar', $theme_uri . '/custom-js/announcement-bar.js', array(), ofo_filemtime($theme_dir . '/custom-js/announcement-bar.js'), true);
    wp_enqueue_style('ofo-hero', $theme_uri . '/custom-css/hero.css', array(), ofo_filemtime($theme_dir . '/custom-css/hero.css'));
    wp_enqueue_style('ofo-woocommerce-overrides', $theme_uri . '/custom-css/woocommerce-overrides.css', array(), ofo_filemtime($theme_dir . '/custom-css/woocommerce-overrides.css'));
}

// ============================================================
// Hero banner shortcodes
// ============================================================
add_shortcode('ofo_hero_main', 'ofo_render_hero_main');
function ofo_render_hero_main($atts) {
    ob_start(); ?>
    <div class="ofo-hero ofo-hero--main">
        <div class="ofo-hero__overlay"></div>
        <div class="ofo-hero__content">
            <p class="ofo-hero__eyebrow">🇺🇸 America's #1 Wholesale Fireworks</p>
            <h1 class="ofo-hero__headline">AMERICA'S BEST PRICE ON FIREWORKS</h1>
            <p class="ofo-hero__subhead">Wholesale Case Pricing — Up to 87% Cheaper Than Local Stores</p>
            <div class="ofo-hero__proof">
                <span>✅ No Minimum Order</span>
                <span>✅ Free Ship Over $1,500</span>
                <span>✅ Case Pricing = Massive Savings</span>
            </div>
            <div class="ofo-hero__ctas">
                <a href="/product-category/500g-cakes/" class="ofo-hero__btn ofo-hero__btn--primary">SHOP 500G CAKES</a>
                <a href="/build-your-pallet/" class="ofo-hero__btn ofo-hero__btn--secondary">BUILD YOUR PALLET</a>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
}

add_shortcode('ofo_hero_sale', 'ofo_render_hero_sale');
function ofo_render_hero_sale($atts) {
    ob_start(); ?>
    <div class="ofo-hero ofo-hero--sale">
        <div class="ofo-hero__overlay"></div>
        <div class="ofo-hero__content">
            <p class="ofo-hero__eyebrow">🔥 Limited Time Sale</p>
            <h2 class="ofo-hero__headline">GOING FOR GOLD — 10% OFF SITEWIDE</h2>
            <p class="ofo-hero__subhead">+ Win a $500 OFO Store Credit · Ends July 4th</p>
            <div class="ofo-hero__ctas">
                <a href="/shop/" class="ofo-hero__btn ofo-hero__btn--primary">CLAIM YOUR DISCOUNT</a>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
}

// ============================================================
// WooCommerce webhook trigger for social automation
// ============================================================
add_action('woocommerce_new_product', 'ofo_trigger_social_automation', 10, 1);
function ofo_trigger_social_automation($product_id) {
    $product = wc_get_product($product_id);
    if (!$product) return;

    $case_pack = $product->get_attribute('case_pack');
    if (!$case_pack) $case_pack = get_post_meta($product_id, '_case_pack', true);

    $shot_count = $product->get_attribute('shot_count');
    if (!$shot_count) $shot_count = get_post_meta($product_id, '_shot_count', true);

    $terms = get_the_terms($product_id, 'product_cat');
    $category = '';
    if ($terms && !is_wp_error($terms)) {
        foreach ($terms as $term) {
            if ($term->slug !== 'uncategorized') {
                $category = $term->name;
                break;
            }
        }
    }

    $image_id = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_url($image_id) : '';

    // Log the product data for Make.com to pick up via webhook
    $payload = array(
        'product_id'   => $product_id,
        'product_name' => $product->get_name(),
        'price'        => $product->get_price(),
        'case_pack'    => $case_pack ? $case_pack : 1,
        'shot_count'   => $shot_count ? $shot_count : 0,
        'category'     => $category,
        'image_url'    => $image_url,
        'permalink'    => get_permalink($product_id),
    );

    // Store as transient so it can be retrieved if needed
    set_transient('ofo_new_product_' . $product_id, $payload, 24 * HOUR_IN_SECONDS);
}

// ============================================================
// ELEVATE 2026 TRADESHOW LANDING PAGE
// ============================================================
add_shortcode('ofo_elevate_landing', 'ofo_render_elevate_landing');
function ofo_render_elevate_landing($atts) {
    ob_start();
    ?>
    <style>
    .ofo-elevate{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif!important;color:#fff!important;background:#0a0d1a!important;margin:0 -50px!important;padding:0!important;width:calc(100% + 100px)!important;max-width:none!important}
    .brxe-post-title{display:none!important}
    .ofo-elv-hero{position:relative!important;background:linear-gradient(135deg,#003087 0%,#0a1628 40%,#CC1C2E 100%)!important;padding:80px 24px 60px!important;text-align:center!important;overflow:hidden!important}
    .ofo-elv-hero::before{content:''!important;position:absolute!important;inset:0!important;background:radial-gradient(circle at 50% 0%,rgba(255,255,255,0.08) 0%,transparent 60%)!important;pointer-events:none!important}
    .ofo-elv-hero-inner{position:relative!important;z-index:2!important;max-width:800px!important;margin:0 auto!important}
    .ofo-elv-badge{display:inline-block!important;background:rgba(255,255,255,0.15)!important;border:1px solid rgba(255,255,255,0.3)!important;color:#F5A623!important;font-size:0.85rem!important;font-weight:700!important;text-transform:uppercase!important;letter-spacing:0.1em!important;padding:8px 20px!important;border-radius:50px!important;margin-bottom:24px!important}
    .ofo-elv-hero h1{font-size:clamp(2rem,5vw,3.5rem)!important;font-weight:900!important;text-transform:uppercase!important;letter-spacing:-0.02em!important;line-height:1.05!important;margin:0 0 16px!important;color:#fff!important;text-shadow:2px 4px 12px rgba(0,0,0,0.4)!important}
    .ofo-elv-hero h1 span{color:#F5A623!important}
    .ofo-elv-hero-sub{font-size:clamp(1rem,2vw,1.25rem)!important;color:rgba(255,255,255,0.85)!important;margin:0 0 10px!important;font-weight:500!important}
    .ofo-elv-date{font-size:1.1rem!important;font-weight:700!important;color:#F5A623!important;margin:0 0 30px!important}
    .ofo-elv-coupon{background:linear-gradient(135deg,#1a2744 0%,#0f1a2c 100%)!important;padding:60px 24px!important;text-align:center!important}
    .ofo-elv-coupon-inner{max-width:700px!important;margin:0 auto!important}
    .ofo-elv-coupon h2{font-size:clamp(1.5rem,3vw,2.2rem)!important;font-weight:800!important;color:#fff!important;margin:0 0 12px!important;text-transform:uppercase!important}
    .ofo-elv-coupon-desc{font-size:1.05rem!important;color:rgba(255,255,255,0.8)!important;margin:0 0 28px!important;line-height:1.6!important}
    .ofo-elv-code-box{display:inline-block!important;background:rgba(204,28,46,0.15)!important;border:3px dashed #CC1C2E!important;border-radius:12px!important;padding:24px 48px!important;margin-bottom:16px!important}
    .ofo-elv-code-label{font-size:0.8rem!important;font-weight:600!important;text-transform:uppercase!important;letter-spacing:0.1em!important;color:rgba(255,255,255,0.6)!important;margin:0 0 8px!important}
    .ofo-elv-code{font-size:clamp(2rem,5vw,3rem)!important;font-weight:900!important;color:#F5A623!important;letter-spacing:0.08em!important;font-family:'Courier New',monospace!important}
    .ofo-elv-code-note{font-size:0.85rem!important;color:rgba(255,255,255,0.5)!important;margin-top:12px!important}
    .ofo-elv-savings-badges{display:flex!important;flex-wrap:wrap!important;justify-content:center!important;gap:16px!important;margin-top:30px!important}
    .ofo-elv-savings-badge{background:rgba(255,255,255,0.08)!important;border:1px solid rgba(255,255,255,0.15)!important;border-radius:10px!important;padding:16px 24px!important;text-align:center!important;min-width:160px!important}
    .ofo-elv-savings-badge strong{display:block!important;font-size:1.4rem!important;color:#F5A623!important;margin-bottom:4px!important}
    .ofo-elv-savings-badge span{font-size:0.8rem!important;color:rgba(255,255,255,0.6)!important}
    .ofo-elv-products{background:#0f1420!important;padding:60px 24px!important}
    .ofo-elv-products-inner{max-width:1100px!important;margin:0 auto!important}
    .ofo-elv-products h2{text-align:center!important;font-size:clamp(1.5rem,3vw,2rem)!important;font-weight:800!important;color:#fff!important;margin:0 0 12px!important;text-transform:uppercase!important}
    .ofo-elv-products-sub{text-align:center!important;color:rgba(255,255,255,0.6)!important;margin:0 0 36px!important;font-size:1rem!important}
    .ofo-elv-cat-grid{display:grid!important;grid-template-columns:repeat(auto-fit,minmax(240px,1fr))!important;gap:20px!important;margin-bottom:36px!important}
    .ofo-elv-cat-card{background:linear-gradient(135deg,#1a2744 0%,#152238 100%)!important;border:1px solid rgba(255,255,255,0.1)!important;border-radius:12px!important;padding:30px 24px!important;text-align:center!important;text-decoration:none!important;color:#fff!important;transition:all 0.2s ease!important;display:block!important}
    .ofo-elv-cat-card:hover{border-color:#F5A623!important;transform:translateY(-4px)!important;box-shadow:0 8px 30px rgba(245,166,35,0.2)!important;color:#fff!important}
    .ofo-elv-cat-icon{font-size:2.5rem!important;margin-bottom:12px!important;display:block!important}
    .ofo-elv-cat-card h3{font-size:1.1rem!important;font-weight:700!important;margin:0 0 8px!important;text-transform:uppercase!important;color:#fff!important}
    .ofo-elv-cat-card p{font-size:0.85rem!important;color:rgba(255,255,255,0.6)!important;margin:0!important;line-height:1.5!important}
    .ofo-elv-shop-btn{display:block!important;max-width:400px!important;margin:0 auto!important;padding:18px 36px!important;background:linear-gradient(135deg,#CC1C2E 0%,#FF4500 100%)!important;color:#fff!important;font-size:1.1rem!important;font-weight:800!important;text-transform:uppercase!important;letter-spacing:0.06em!important;text-align:center!important;text-decoration:none!important;border-radius:50px!important;box-shadow:0 4px 20px rgba(204,28,46,0.5)!important;transition:all 0.2s ease!important}
    .ofo-elv-shop-btn:hover{transform:translateY(-2px)!important;box-shadow:0 8px 30px rgba(204,28,46,0.65)!important;color:#fff!important}
    .ofo-elv-info{background:linear-gradient(135deg,#003087 0%,#1a2744 100%)!important;padding:60px 24px!important}
    .ofo-elv-info-inner{max-width:900px!important;margin:0 auto!important}
    .ofo-elv-info-grid{display:grid!important;grid-template-columns:1fr 1fr!important;gap:24px!important}
    .ofo-elv-info-card{background:rgba(255,255,255,0.06)!important;border:1px solid rgba(255,255,255,0.12)!important;border-radius:12px!important;padding:30px 24px!important}
    .ofo-elv-info-card h3{font-size:1.1rem!important;font-weight:700!important;color:#F5A623!important;margin:0 0 12px!important;text-transform:uppercase!important}
    .ofo-elv-info-card p{font-size:0.9rem!important;color:rgba(255,255,255,0.8)!important;margin:0 0 8px!important;line-height:1.6!important}
    .ofo-elv-info-card ul{list-style:none!important;padding:0!important;margin:0!important}
    .ofo-elv-info-card ul li{font-size:0.9rem!important;color:rgba(255,255,255,0.8)!important;padding:6px 0!important;border-bottom:1px solid rgba(255,255,255,0.06)!important}
    .ofo-elv-info-card ul li:last-child{border-bottom:none!important}
    .ofo-elv-footer{background:#CC1C2E!important;padding:30px 24px!important;text-align:center!important}
    .ofo-elv-footer p{font-size:1.1rem!important;font-weight:700!important;color:#fff!important;margin:0!important}
    .ofo-elv-footer a{color:#F5A623!important;text-decoration:underline!important}
    @media(max-width:600px){.ofo-elv-info-grid{grid-template-columns:1fr!important}.ofo-elv-code-box{padding:16px 24px!important}.ofo-elv-savings-badges{flex-direction:column!important;align-items:center!important}.ofo-elevate{margin:0 -20px!important;width:calc(100% + 40px)!important}}
    </style>
    <div class="ofo-elevate">

      <!-- HERO -->
      <div class="ofo-elv-hero">
        <div class="ofo-elv-hero-inner">
          <span class="ofo-elv-badge">Elevate Trade Show Exclusive</span>
          <h1>AMERICA'S <span>250TH BIRTHDAY</span><br>DESERVES THE BIGGEST SHOW</h1>
          <p class="ofo-elv-hero-sub">Wholesale fireworks at prices your competitors can't touch</p>
          <p class="ofo-elv-date">Elevate 2026 &middot; April 9&ndash;11, 2026</p>
        </div>
      </div>

      <!-- COUPON -->
      <div class="ofo-elv-coupon">
        <div class="ofo-elv-coupon-inner">
          <h2>Your Exclusive Show Discount</h2>
          <p class="ofo-elv-coupon-desc">All products are already marked 10% off sitewide. Use this code at checkout for an <strong>extra 5% off</strong> &mdash; exclusively for Elevate attendees.</p>
          <div class="ofo-elv-code-box">
            <p class="ofo-elv-code-label">Your Promo Code</p>
            <div class="ofo-elv-code">ELEVATE2026</div>
          </div>
          <p class="ofo-elv-code-note">Valid through April 30, 2026 &middot; Stacks on top of current sale prices</p>

          <div class="ofo-elv-savings-badges">
            <div class="ofo-elv-savings-badge">
              <strong>10% OFF</strong>
              <span>Already Applied Sitewide</span>
            </div>
            <div class="ofo-elv-savings-badge">
              <strong>+ 5% OFF</strong>
              <span>With Code ELEVATE2026</span>
            </div>
            <div class="ofo-elv-savings-badge">
              <strong>Up to 87%</strong>
              <span>Cheaper Than Retail</span>
            </div>
          </div>
        </div>
      </div>

      <!-- PRODUCTS -->
      <div class="ofo-elv-products">
        <div class="ofo-elv-products-inner">
          <h2>Shop Our Best-Selling Categories</h2>
          <p class="ofo-elv-products-sub">Wholesale case pricing &mdash; no minimum order required</p>

          <div class="ofo-elv-cat-grid">
            <a href="/product-category/aerial-fireworks/500g-cakes/" class="ofo-elv-cat-card">
              <span class="ofo-elv-cat-icon">💥</span>
              <h3>500g Cakes</h3>
              <p>The most powerful consumer fireworks. Up to 500 shots of color, sound &amp; aerial effects from a single fuse.</p>
            </a>
            <a href="/product-category/aerial-fireworks/200g-cakes/" class="ofo-elv-cat-card">
              <span class="ofo-elv-cat-icon">🎆</span>
              <h3>200g Cakes</h3>
              <p>Multi-shot repeaters with vivid colors and reliable performance. Perfect foundation for any show.</p>
            </a>
            <a href="/product-category/aerial-fireworks/artillery/" class="ofo-elv-cat-card">
              <span class="ofo-elv-cat-icon">🚀</span>
              <h3>Artillery Shells</h3>
              <p>The crown jewel of any display. Massive bursts of color that rival professional shows.</p>
            </a>
            <a href="/product-category/pallet-packs/" class="ofo-elv-cat-card">
              <span class="ofo-elv-cat-icon">📦</span>
              <h3>Pallet Packs</h3>
              <p>Pre-configured pallets or build your own. Maximum show value at the lowest cost per shot.</p>
            </a>
          </div>

          <a href="/shop/" class="ofo-elv-shop-btn">Shop All Fireworks &amp; Save</a>
        </div>
      </div>

      <!-- SHIPPING + LOYALTY -->
      <div class="ofo-elv-info">
        <div class="ofo-elv-info-inner">
          <div class="ofo-elv-info-grid">
            <div class="ofo-elv-info-card">
              <h3>🚚 Shipping</h3>
              <ul>
                <li><strong>$99 flat rate shipping</strong> on all orders</li>
                <li><strong>FREE shipping</strong> on orders over $1,500</li>
                <li>Ships via freight carrier direct to your door</li>
                <li>Most orders ship within 3&ndash;5 business days</li>
                <li>No minimum order required</li>
              </ul>
            </div>
            <div class="ofo-elv-info-card">
              <h3>🏆 OFO Loyalty Rewards</h3>
              <p>Earn points on every purchase and redeem them for discounts on future orders.</p>
              <ul>
                <li><strong>1 point per $1 spent</strong></li>
                <li>Redeem points for store credit</li>
                <li>Exclusive member-only deals</li>
                <li>Early access to new products</li>
                <li><a href="/loyalty-rewards/" style="color:#F5A623;">Join the program &rarr;</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- URGENCY FOOTER -->
      <div class="ofo-elv-footer">
        <p>Code <strong>ELEVATE2026</strong> expires April 30, 2026 &mdash; <a href="/shop/">Shop now and save</a></p>
      </div>

    </div>
    <?php
    return ob_get_clean();
}

// Auto-create Elevate2026 coupon if it doesn't exist
add_action('init', 'ofo_create_elevate_coupon');
function ofo_create_elevate_coupon() {
    if (get_option('ofo_elevate_coupon_created')) return;
    if (!function_exists('WC')) return;

    $coupon_code = 'Elevate2026';
    $existing = wc_get_coupon_id_by_code($coupon_code);
    if ($existing) {
        update_option('ofo_elevate_coupon_created', true);
        return;
    }

    $coupon = new WC_Coupon();
    $coupon->set_code($coupon_code);
    $coupon->set_description('Elevate 2026 Tradeshow - Extra 5% off for attendees');
    $coupon->set_discount_type('percent');
    $coupon->set_amount(5);
    $coupon->set_individual_use(false);
    $coupon->set_date_expires('2026-04-30');
    $coupon->save();

    update_option('ofo_elevate_coupon_created', true);
}

// Auto-create Elevate landing page if it doesn't exist
add_action('init', 'ofo_create_elevate_page');
function ofo_create_elevate_page() {
    if (get_option('ofo_elevate_page_created')) return;

    $existing = get_page_by_path('elevate');
    if ($existing) {
        update_option('ofo_elevate_page_created', true);
        return;
    }

    $page_id = wp_insert_post(array(
        'post_title'   => 'Elevate 2026',
        'post_name'    => 'elevate',
        'post_content' => '[ofo_elevate_landing]',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => 1,
    ));

    if ($page_id && !is_wp_error($page_id)) {
        update_option('ofo_elevate_page_created', $page_id);
    }
}
