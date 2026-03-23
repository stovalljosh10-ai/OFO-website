<?php
/**
 * Product Data Export
 * Visit: yoursite.com/?ofo_export_csv=1 (must be logged in as admin)
 */
add_action('init', 'ofo_export_products_csv');
function ofo_export_products_csv() {
    if (!isset($_GET['ofo_export_csv'])) return;
    if (!current_user_can('manage_options')) {
        wp_die('Admin access required.');
    }

    $args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $query = new WP_Query($args);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=ofo-products-' . date('Y-m-d') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, array(
        'Product ID',
        'Product Name',
        'Price',
        'Case Pack (current)',
        'Case Pack (from description)',
        'Shot Count (current)',
        'Shot Count (from description)',
        'Categories',
        'Product URL',
    ));

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $product = wc_get_product(get_the_ID());
            if (!$product) continue;

            $desc = $product->get_description() . ' ' . $product->get_short_description() . ' ' . $product->get_name();

            // Current case pack value
            $case_pack_current = $product->get_attribute('case_pack');
            if (!$case_pack_current) $case_pack_current = get_post_meta(get_the_ID(), '_case_pack', true);
            if (!$case_pack_current) $case_pack_current = '';

            // Auto-extracted case pack
            $case_pack_desc = '';
            if (preg_match('/(\d+)\s*\/\s*1\b/', $desc, $m)) {
                $case_pack_desc = intval($m[1]);
            }

            // Current shot count value
            $shot_count_current = $product->get_attribute('shot_count');
            if (!$shot_count_current) $shot_count_current = get_post_meta(get_the_ID(), '_shot_count', true);
            if (!$shot_count_current) $shot_count_current = '';

            // Auto-extracted shot count
            $shot_count_desc = '';
            if (preg_match('/(\d+)\s*[-\s]?\s*shots?/i', $desc, $m)) {
                $shot_count_desc = intval($m[1]);
            }

            // Categories
            $terms = get_the_terms(get_the_ID(), 'product_cat');
            $cats = '';
            if ($terms && !is_wp_error($terms)) {
                $cat_names = array();
                foreach ($terms as $term) {
                    if ($term->slug !== 'uncategorized') $cat_names[] = $term->name;
                }
                $cats = implode(', ', $cat_names);
            }

            fputcsv($output, array(
                $product->get_id(),
                $product->get_name(),
                $product->get_price(),
                $case_pack_current,
                $case_pack_desc,
                $shot_count_current,
                $shot_count_desc,
                $cats,
                get_permalink(get_the_ID()),
            ));
        }
        wp_reset_postdata();
    }

    fclose($output);
    exit;
}
