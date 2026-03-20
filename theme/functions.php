<?php 
/**
 * Register/enqueue custom scripts and styles
 */
add_action( 'wp_enqueue_scripts', function() {
	// Enqueue your files on the canvas & frontend, not the builder panel. Otherwise custom CSS might affect builder)
	if ( ! bricks_is_builder_main() ) {
		wp_enqueue_style( 'bricks-child', get_stylesheet_uri(), ['bricks-frontend'], filemtime( get_stylesheet_directory() . '/style.css' ) );
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
        // Slide 1 — Main hero
        array(
            'desktop_image' => '/wp-content/uploads/2026/02/2OFO-Hero-Background.jpg',
            'mobile_image' => '/wp-content/uploads/2026/02/1OFO-Hero-Background-Mobile.jpg',
            'small_text' => 'WHOLESALE CASE PRICING — NO MINIMUM ORDER',
            'heading' => "AMERICA'S BEST PRICE<br>ON FIREWORKS",
            'description' => 'Up to 87% Cheaper Per Unit Than Local Stores',
            'button_text' => 'SHOP 500G CAKES',
            'button_url' => '/product-category/aerial-fireworks/500g-cakes/',
            'button2_text' => 'BUILD YOUR PALLET',
            'button2_url' => '/product-category/pallet-packs/',
        ),
        // Slide 2 — Sale banner
        array(
            'desktop_image' => '/wp-content/uploads/2026/01/OFO-Going-For-Gold-Sale-Hero.jpg',
            'mobile_image' => '/wp-content/uploads/2026/01/OFO-Going-For-Gold-Sale-Hero-Mobile.jpg',
            'small_text' => '10% OFF SITEWIDE + WIN A $500 OFO CREDIT',
            'heading' => 'GOING FOR GOLD<br>SALE NOW LIVE!',
            'description' => 'Limited time — claim your discount before it ends',
            'button_text' => 'CLAIM YOUR DISCOUNT',
            'button_url' => '/product-category/pallet-packs/',
        ),
    );
}

// ============================================================
// PHASE 3: WooCommerce Product Schema for Google Rich Results
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
            'availability' => $product->is_in_stock() 
                ? 'https://schema.org/InStock' 
                : 'https://schema.org/OutOfStock',
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

// ============================================================
// PHASE 4: Per-unit price display on product pages
// ============================================================
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

// ============================================================
// PHASE 5: Cart savings display
// ============================================================
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
            <td><strong style="color:#28a745;font-size:1.1em;">' . wc_price($savings) . '</strong></td>
        </tr>';
    }
}

// ============================================================
// PHASE 6: Custom Pallet Builder shortcode
// ============================================================
add_shortcode('ofo_pallet_builder', 'ofo_render_pallet_builder');
function ofo_render_pallet_builder($atts) {
    ob_start();
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 24,
        'post_status' => 'publish',
        'tax_query' => array(array(
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => array('500g-cakes', '200g-cakes', 'artillery'),
        )),
    );
    $products = new WP_Query($args);
    $template = get_stylesheet_directory() . '/templates/pallet-builder.php';
    if (file_exists($template)) {
        include $template;
    } else {
        echo '<p>Pallet builder template not found.</p>';
    }
    return ob_get_clean();
}
