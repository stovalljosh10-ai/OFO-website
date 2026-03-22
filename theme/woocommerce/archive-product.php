<?php
/**
 * The Template for displaying product archives, including the main shop page
 * which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 */
do_action( 'woocommerce_shop_loop_header' );
?>

<?php
/**
 * Hook: woocommerce_archive_description.
 *
 * @hooked woocommerce_taxonomy_archive_description - 10
 * @hooked woocommerce_product_archive_description - 10
 */
do_action( 'woocommerce_archive_description' );
?>

<div class="woocommerce-notices-wrapper">
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop.
	 */
	?>
</div>

<?php
// OFO Category SEO Descriptions
if (is_product_category()) {
    $category = get_queried_object();
    $descriptions = array(
        '500g-cakes' => 'Shop our full selection of 500 gram fireworks cakes — the most powerful consumer fireworks allowed by federal law. Each 500g cake delivers up to 500 shots of color, sound, and spectacular aerial effects from a single fuse. We sell by the case at wholesale pricing, making OFO the best place to buy 500 gram fireworks online. Free shipping on orders over $1,500. No minimum order required.',
        '200g-cakes' => 'Our 200 gram aerial cake fireworks are the perfect foundation for any backyard show. Multi-shot repeaters with vivid colors and reliable performance, sold at wholesale case prices. Shop our full selection of 200g cakes and ship direct to your door.',
        'artillery' => 'Artillery shells deliver the biggest booms and highest bursts of any consumer firework. Our wholesale artillery shell kits are sold by the case — more shells, bigger show, dramatically lower cost per shot than buying retail.',
        'pallet-packs' => 'Build the ultimate fireworks display with our pre-configured pallet packs — or build your own custom pallet and save even more. OFO pallet packs are curated for maximum show value, combining 500g cakes, artillery shells, and ground effects at prices you won\'t find anywhere else.',
        'sparklers' => 'Wholesale sparklers sold by the case. Perfect for weddings, parties, and 4th of July celebrations. Our sparklers ship fast and arrive ready to light.',
        'ground-fireworks' => 'Ground fireworks and novelties sold at wholesale case pricing. Fountains, spinners, snakes, and more — everything you need to fill out a complete backyard show.',
        'parachutes' => 'Parachute fireworks sold by the case at wholesale pricing. These crowd favorites float down slowly for maximum effect and are a guaranteed hit at any fireworks show.',
    );
    if (isset($descriptions[$category->slug])) {
        echo '<div class="ofo-category-description">' . esc_html($descriptions[$category->slug]) . '</div>';
    }
}
?>

<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
