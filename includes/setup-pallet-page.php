<?php
/**
 * One-time setup: Create "Build Your Custom Pallet" page
 * Add this to functions.php temporarily, load any page, then remove it.
 * Or run via WP-CLI: wp eval-file wp-content/themes/YOUR-CHILD/includes/setup-pallet-page.php
 */
if (!defined('ABSPATH')) exit;

add_action('init', function() {
    // Only run once
    if (get_option('ofo_pallet_page_created')) return;

    $page_id = wp_insert_post(array(
        'post_title'   => 'Build Your Custom Pallet',
        'post_name'    => 'build-your-pallet',
        'post_content' => '[ofo_pallet_builder]',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => 1,
    ));

    if ($page_id && !is_wp_error($page_id)) {
        update_option('ofo_pallet_page_created', $page_id);
    }
});
