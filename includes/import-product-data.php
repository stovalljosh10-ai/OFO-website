<?php
/**
 * Product Data Import — Case Pack & Shot Count
 * Visit: yoursite.com/?ofo_import_product_data=1 (must be logged in as admin)
 * Run once, then remove.
 */
add_action('init', 'ofo_import_product_data');
function ofo_import_product_data() {
    if (!isset($_GET['ofo_import_product_data'])) return;
    if (!current_user_can('manage_options')) { wp_die('Admin access required.'); }

    $data = array(
        13117 => array('case_pack' => 40, 'shot_count' => 100),
        13119 => array('case_pack' => 12, 'shot_count' => 20),
        13113 => array('case_pack' => 12, 'shot_count' => 300),
        13115 => array('case_pack' => 2, 'shot_count' => 750),
        13125 => array('case_pack' => 40, 'shot_count' => 96),
        13131 => array('case_pack' => 8, 'shot_count' => 14),
        14065 => array('case_pack' => 18, 'shot_count' => 16),
        14069 => array('case_pack' => 30, 'shot_count' => 6),
        14098 => array('case_pack' => 24, 'shot_count' => 16),
        13135 => array('case_pack' => 12, 'shot_count' => 100),
        14088 => array('case_pack' => 30, 'shot_count' => 7),
        13123 => array('case_pack' => 12, 'shot_count' => 25),
        13121 => array('case_pack' => 40, 'shot_count' => 6),
        13111 => array('case_pack' => 36, 'shot_count' => 9),
        13105 => array('case_pack' => 40, 'shot_count' => 6),
        14084 => array('case_pack' => 16, 'shot_count' => 16),
        13129 => array('case_pack' => 12, 'shot_count' => 25),
        14072 => array('case_pack' => 18, 'shot_count' => 19),
        13127 => array('case_pack' => 12, 'shot_count' => 96),
        13066 => array('case_pack' => 12, 'shot_count' => 100),
        13137 => array('case_pack' => 16, 'shot_count' => 25),
        14090 => array('case_pack' => 18, 'shot_count' => 49),
        14080 => array('case_pack' => 16, 'shot_count' => 25),
        13109 => array('case_pack' => 24, 'shot_count' => 7),
        13133 => array('case_pack' => 16, 'shot_count' => 16),
        14076 => array('case_pack' => 24, 'shot_count' => 20),
        14082 => array('case_pack' => 30, 'shot_count' => 7),
        14078 => array('case_pack' => 12, 'shot_count' => 60),
        14096 => array('case_pack' => 24, 'shot_count' => 16),
        14086 => array('case_pack' => 12, 'shot_count' => 16),
        14074 => array('case_pack' => 0, 'shot_count' => 324),
        14102 => array('case_pack' => 12, 'shot_count' => 25),
        12968 => array('case_pack' => 24, 'shot_count' => 9),
        12971 => array('case_pack' => 24, 'shot_count' => 49),
        12965 => array('case_pack' => 24, 'shot_count' => 9),
        12974 => array('case_pack' => 12, 'shot_count' => 9),
        12962 => array('case_pack' => 36, 'shot_count' => 49),
        13200 => array('case_pack' => 4, 'shot_count' => 24),
        13171 => array('case_pack' => 4, 'shot_count' => 53),
        13206 => array('case_pack' => 6, 'shot_count' => 9),
        13198 => array('case_pack' => 4, 'shot_count' => 25),
        13214 => array('case_pack' => 2, 'shot_count' => 18),
        13173 => array('case_pack' => 6, 'shot_count' => 9),
        13040 => array('case_pack' => 2, 'shot_count' => 93),
        13167 => array('case_pack' => 4, 'shot_count' => 24),
        13097 => array('case_pack' => 4, 'shot_count' => 12),
        13204 => array('case_pack' => 6, 'shot_count' => 9),
        13245 => array('case_pack' => 4, 'shot_count' => 196),
        12922 => array('case_pack' => 6, 'shot_count' => 9),
        13099 => array('case_pack' => 6, 'shot_count' => 9),
        13179 => array('case_pack' => 4, 'shot_count' => 25),
        13188 => array('case_pack' => 6, 'shot_count' => 207),
        13175 => array('case_pack' => 4, 'shot_count' => 3),
        13153 => array('case_pack' => 4, 'shot_count' => 30),
        13247 => array('case_pack' => 4, 'shot_count' => 44),
        13037 => array('case_pack' => 4, 'shot_count' => 25),
        13208 => array('case_pack' => 6, 'shot_count' => 9),
        13155 => array('case_pack' => 4, 'shot_count' => 30),
        13163 => array('case_pack' => 4, 'shot_count' => 30),
        14092 => array('case_pack' => 1, 'shot_count' => 176),
        13411 => array('case_pack' => 4, 'shot_count' => 30),
        13399 => array('case_pack' => 1, 'shot_count' => 196),
        13196 => array('case_pack' => 4, 'shot_count' => 28),
        13186 => array('case_pack' => 6, 'shot_count' => 162),
        13183 => array('case_pack' => 4, 'shot_count' => 9),
        13249 => array('case_pack' => 4, 'shot_count' => 24),
        13251 => array('case_pack' => 4, 'shot_count' => 28),
        13101 => array('case_pack' => 3, 'shot_count' => 33),
        13202 => array('case_pack' => 4, 'shot_count' => 36),
        13402 => array('case_pack' => 4, 'shot_count' => 36),
        13165 => array('case_pack' => 4, 'shot_count' => 12),
        13253 => array('case_pack' => 0, 'shot_count' => 44),
        13157 => array('case_pack' => 4, 'shot_count' => 16),
        13161 => array('case_pack' => 4, 'shot_count' => 16),
        13409 => array('case_pack' => 6, 'shot_count' => 14),
        13404 => array('case_pack' => 3, 'shot_count' => 52),
        13210 => array('case_pack' => 2, 'shot_count' => 9),
        13181 => array('case_pack' => 4, 'shot_count' => 25),
        13212 => array('case_pack' => 2, 'shot_count' => 9),
        13194 => array('case_pack' => 6, 'shot_count' => 163),
        13043 => array('case_pack' => 4, 'shot_count' => 25),
        13398 => array('case_pack' => 1, 'shot_count' => 260),
        14093 => array('case_pack' => 1, 'shot_count' => 196),
        13046 => array('case_pack' => 4, 'shot_count' => 25),
        13159 => array('case_pack' => 4, 'shot_count' => 9),
        13169 => array('case_pack' => 2, 'shot_count' => 60),
        14071 => array('case_pack' => 1, 'shot_count' => 260),
        13190 => array('case_pack' => 2, 'shot_count' => 5),
        13192 => array('case_pack' => 4, 'shot_count' => 16),
        13333 => array('case_pack' => 3, 'shot_count' => 46),
        13177 => array('case_pack' => 6, 'shot_count' => 16),
        13035 => array('case_pack' => 4, 'shot_count' => 30),
        14100 => array('case_pack' => 2, 'shot_count' => 120),
        14094 => array('case_pack' => 4, 'shot_count' => 9),
        12910 => array('case_pack' => 4, 'shot_count' => 28),
        12949 => array('case_pack' => 6, 'shot_count' => 8),
        12944 => array('case_pack' => 6, 'shot_count' => 12),
        12941 => array('case_pack' => 6, 'shot_count' => 8),
        12935 => array('case_pack' => 6, 'shot_count' => 8),
        12938 => array('case_pack' => 6, 'shot_count' => 8),
        12919 => array('case_pack' => 4, 'shot_count' => 25),
        12916 => array('case_pack' => 4, 'shot_count' => 12),
        12932 => array('case_pack' => 6, 'shot_count' => 8),
        13049 => array('case_pack' => 4, 'shot_count' => 20),
        12913 => array('case_pack' => 4, 'shot_count' => 25),
        13145 => array('case_pack' => 12, 'shot_count' => 6),
        13384 => array('case_pack' => 4, 'shot_count' => 24),
        13386 => array('case_pack' => 3, 'shot_count' => 24),
        13396 => array('case_pack' => 4, 'shot_count' => 84),
        13390 => array('case_pack' => 3, 'shot_count' => 24),
        13264 => array('case_pack' => 3, 'shot_count' => 24),
        13400 => array('case_pack' => 3, 'shot_count' => 24),
        13388 => array('case_pack' => 3, 'shot_count' => 24),
        13147 => array('case_pack' => 12, 'shot_count' => 12),
        13392 => array('case_pack' => 12, 'shot_count' => 24),
        13394 => array('case_pack' => 11, 'shot_count' => 14),
        13095 => array('case_pack' => 1, 'shot_count' => 486),
        13107 => array('case_pack' => 4, 'shot_count' => 36),
        13141 => array('case_pack' => 72, 'shot_count' => 0),
        13143 => array('case_pack' => 72, 'shot_count' => 0),
        13323 => array('case_pack' => 36, 'shot_count' => 0),
        13325 => array('case_pack' => 12, 'shot_count' => 0),
        13234 => array('case_pack' => 36, 'shot_count' => 0),
        13327 => array('case_pack' => 18, 'shot_count' => 0),
        13329 => array('case_pack' => 18, 'shot_count' => 0),
        13232 => array('case_pack' => 12, 'shot_count' => 0),
        13236 => array('case_pack' => 36, 'shot_count' => 0),
        13149 => array('case_pack' => 4, 'shot_count' => 0),
        13218 => array('case_pack' => 36, 'shot_count' => 0),
        13220 => array('case_pack' => 12, 'shot_count' => 0),
        13216 => array('case_pack' => 12, 'shot_count' => 0),
        13103 => array('case_pack' => 16, 'shot_count' => 0),
        13238 => array('case_pack' => 36, 'shot_count' => 0),
        13240 => array('case_pack' => 24, 'shot_count' => 0),
        13230 => array('case_pack' => 12, 'shot_count' => 0),
        13224 => array('case_pack' => 24, 'shot_count' => 0),
        13226 => array('case_pack' => 24, 'shot_count' => 0),
        13228 => array('case_pack' => 36, 'shot_count' => 0),
        13255 => array('case_pack' => 12, 'shot_count' => 0),
        13151 => array('case_pack' => 6, 'shot_count' => 0),
        13243 => array('case_pack' => 36, 'shot_count' => 0),
        12987 => array('case_pack' => 18, 'shot_count' => 0),
        12994 => array('case_pack' => 4, 'shot_count' => 5),
        14009 => array('case_pack' => 0, 'shot_count' => 750),
        13321 => array('case_pack' => 16, 'shot_count' => 0),
        13266 => array('case_pack' => 12, 'shot_count' => 382),
        13271 => array('case_pack' => 12, 'shot_count' => 814),
        13406 => array('case_pack' => 12, 'shot_count' => 814),
        13272 => array('case_pack' => 12, 'shot_count' => 961),
        13408 => array('case_pack' => 12, 'shot_count' => 961),
        13279 => array('case_pack' => 16, 'shot_count' => 259),
        13283 => array('case_pack' => 8, 'shot_count' => 258),
        13317 => array('case_pack' => 12, 'shot_count' => 0),
        13017 => array('case_pack' => 8, 'shot_count' => 0),
        13357 => array('case_pack' => 144, 'shot_count' => 0),
    );

    header('Content-Type: text/html; charset=utf-8');
    echo '<h1>OFO Product Data Import</h1>';
    echo '<pre>';

    $updated = 0;
    $skipped = 0;

    foreach ($data as $product_id => $values) {
        $product = wc_get_product($product_id);
        if (!$product) {
            echo "SKIP: Product $product_id not found\n";
            $skipped++;
            continue;
        }

        $name = $product->get_name();

        if (!empty($values['case_pack'])) {
            update_post_meta($product_id, '_case_pack', intval($values['case_pack']));
        }
        if (!empty($values['shot_count'])) {
            update_post_meta($product_id, '_shot_count', intval($values['shot_count']));
        }

        echo "OK: $name (ID: $product_id) — case_pack: {$values['case_pack']}, shot_count: {$values['shot_count']}\n";
        $updated++;
    }

    echo "\n\nDone! Updated: $updated, Skipped: $skipped\n";
    echo '</pre>';
    exit;
}
