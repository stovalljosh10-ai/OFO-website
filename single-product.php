<?php
/*
 * Enhanced WooCommerce Single Product Template
 * @version 2.0.0
 */

use BRTheme\WishlistHandler;

// Ensure wishlist script (and its localised data) is loaded
if (function_exists('wp_enqueue_script')) {
    // Enqueue element styles and scripts
    wp_enqueue_script('brtheme-wishlist-script', BRTHEME_PLUGIN_URL . 'inc/Elements/Wishlist.js', array('jquery'), time(), true);
    wp_enqueue_style('brtheme-wishlist-style', BRTHEME_PLUGIN_URL . 'inc/Elements/Wishlist.css', array(), time());
    wp_localize_script(
        'brtheme-wishlist-script',
        'brthemeWishlist',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonces'  => array(
                'add'    => wp_create_nonce('brtheme-add-wishlist'),
                'remove' => wp_create_nonce('brtheme-remove-wishlist'),
            ),
        )
    );
}

get_header();

if (have_posts()) {
    while (have_posts()) {
        the_post();
        global $product;

        // Get product data
        $product_id = get_the_ID();
        $attachment_ids = $product->get_gallery_image_ids();
        $categories = get_the_terms($product_id, 'product_cat');
        $rating = $product->get_average_rating();
        $review_count = $product->get_review_count();

        $wishlist_items = array();

        if (class_exists('\BRTheme\WishlistHandler')) {
            $wishlist_items = WishlistHandler::get_user_wishlist_items();
        }

        // Cast product ID to string because wishlist stores IDs as strings
        $is_in_wishlist = in_array((string) $product_id, $wishlist_items, true);
?>

        <!-- Enhanced Product Styles -->
        <style>
            :root {
                --primary: #2563eb;
                --primary-dark: #1d4ed8;
                --success: #10b981;
                --danger: #ef4444;
                --warning: #f59e0b;
                --dark: #1f2937;
                --light: #6b7280;
                --border: #e5e7eb;
                --bg-light: #f9fafb;
            }

            #photoswipe-fullscreen-dialog {
                display: none;
            }

            .woocommerce-error,
            .woocommerce-info,
            .woocommerce-message {
                display: flex;
                margin-bottom: 2rem;
                align-items: center;
                gap: 0.5rem;
            }

            .woocommerce-error:before,
            .woocommerce-info:before,
            .woocommerce-message:before {
                position: static;
            }

            .enhanced-product-page {
                max-width: 1400px;
                margin: 0 auto;
                padding: 40px 20px;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            }

            /* Breadcrumb */
            .ep-breadcrumb {
                display: flex;
                gap: 10px;
                margin-bottom: 30px;
                font-size: 14px;
                color: var(--light);
                flex-wrap: wrap;
            }

            .ep-breadcrumb a {
                color: var(--light);
                text-decoration: none;
                transition: color 0.3s;
            }

            .ep-breadcrumb a:hover {
                color: var(--primary);
            }

            /* Main Grid */
            .ep-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 60px;
                margin-bottom: 60px;
            }

            /* Images Section */
            .ep-images {
                position: sticky;
                top: 20px;
                height: auto;
            }

            .ep-main-image {
                position: relative;
                background: #fff;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                aspect-ratio: 1;
                width: 100%;
                max-width: 640px;
                margin-inline: auto;
            }

            .ep-main-image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
                transition: transform 0.6s ease;
            }

            .ep-main-image:hover img {
                transform: scale(1.08);
            }

            .ep-badge {
                position: absolute;
                top: 20px;
                left: 20px;
                background: var(--danger);
                color: white;
                padding: 10px 20px;
                border-radius: 50px;
                font-weight: 700;
                font-size: 14px;
                z-index: 10;
                box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            }

            .ep-badge.featured {
                background: var(--primary);
                box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            }

            .ep-zoom-hint {
                position: absolute;
                bottom: 20px;
                right: 20px;
                background: rgba(0, 0, 0, 0.7);
                color: white;
                padding: 10px 16px;
                border-radius: 10px;
                font-size: 13px;
                opacity: 0;
                transition: opacity 0.3s;
            }

            .ep-main-image:hover .ep-zoom-hint {
                opacity: 1;
            }

            /* Thumbnails */
            .ep-thumbnails {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
                gap: 15px;
            }

            .ep-thumb {
                aspect-ratio: 1;
                border-radius: 15px;
                overflow: hidden;
                cursor: pointer;
                border: 3px solid transparent;
                transition: all 0.3s;
                background: #fff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .ep-thumb:hover,
            .ep-thumb.active {
                border-color: var(--primary);
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(37, 99, 235, 0.2);
            }

            .ep-thumb img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            /* Product Info */
            .ep-info {
                display: flex;
                flex-direction: column;
                gap: 25px;
            }

            .ep-tags {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .ep-tag {
                padding: 8px 16px;
                background: var(--bg-light);
                border-radius: 50px;
                font-size: 13px;
                color: var(--light);
                font-weight: 500;
            }

            .ep-title {
                font-size: 40px;
                font-weight: 800;
                color: var(--dark);
                line-height: 1.2;
                margin: 0;
            }

            /* Rating */
            .ep-rating-box {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .ep-stars {
                display: flex;
                gap: 3px;
                font-size: 20px;
                color: #fbbf24;
            }

            .ep-rating-text {
                color: var(--light);
                font-size: 15px;
            }

            /* Price Box */
            .ep-price-box {
                background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                padding: 30px;
                border-radius: 20px;
                border: 2px solid #bae6fd;
                display: flex;
                align-items: center;
                gap: 15px;
                flex-wrap: wrap;
            }

            .ep-price {
                font-size: 48px;
                font-weight: 900;
                color: var(--primary);
                line-height: 1;
            }

            .ep-price-old {
                font-size: 28px;
                color: var(--light);
                text-decoration: line-through;
            }

            .ep-discount {
                background: var(--danger);
                color: white;
                padding: 8px 16px;
                border-radius: 10px;
                font-weight: 700;
                font-size: 15px;
            }

            /* Description */
            .ep-description {
                font-size: 17px;
                line-height: 1.8;
                color: var(--light);
            }

            /* Stock */
            .ep-stock {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 16px 20px;
                background: #ecfdf5;
                border-radius: 15px;
                border-left: 5px solid var(--success);
            }

            .ep-stock.out {
                background: #fef2f2;
                border-left-color: var(--danger);
            }

            .ep-stock-icon {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                background: var(--success);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 700;
            }

            .ep-stock-text {
                font-weight: 600;
                color: var(--dark);
                font-size: 15px;
            }

            /* Cart Form */
            .ep-cart-form {
                display: flex;
                gap: 15px;
                flex-wrap: wrap;
            }

            .ep-quantity {
                display: flex;
                align-items: center;
                background: white;
                border: 2px solid var(--border);
                border-radius: 15px;
                overflow: hidden;
                height: 60px;
            }

            .ep-qty-btn {
                width: 50px;
                height: 100%;
                border: none;
                background: transparent;
                font-size: 24px;
                font-weight: 700;
                cursor: pointer;
                transition: background 0.3s;
                color: var(--dark);
                text-align: center;
            }

            .ep-qty-btn:hover {
                background: var(--bg-light);
            }

            .ep-qty-input {
                width: 70px;
                text-align: center;
                border: none;
                font-size: 20px;
                font-weight: 700;
                color: var(--dark);
            }

            .ep-add-cart {
                flex: 1;
                min-width: 200px;
                background: var(--primary);
                color: white;
                border: none;
                border-radius: 15px;
                padding: 0 40px;
                font-size: 18px;
                font-weight: 700;
                cursor: pointer;
                transition: all 0.3s;
                box-shadow: 0 10px 30px rgba(37, 99, 235, 0.3);
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
            }

            .ep-add-cart:hover {
                background: var(--primary-dark);
                transform: translateY(-3px);
                box-shadow: 0 15px 40px rgba(37, 99, 235, 0.4);
            }

            .ep-add-cart:active {
                transform: translateY(-1px);
            }

            /* Action Buttons */
            .ep-actions {
                display: grid;
                /* grid-template-columns: 1fr 1fr; */
                /* gap: 15px; */
            }

            .ep-action-btn {
                padding: 16px;
                background: white;
                border: 2px solid var(--border);
                border-radius: 15px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                color: var(--dark);
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            .ep-action-btn:hover {
                border-color: var(--primary);
                color: var(--primary);
                background: rgba(37, 99, 235, 0.05);
                transform: translateY(-2px);
            }

            /* Features */
            .ep-features {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 30px;
                background: var(--bg-light);
                border-radius: 20px;
            }

            .ep-feature {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .ep-feature-icon {
                width: 50px;
                height: 50px;
                background: white;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .ep-feature-text strong {
                display: block;
                font-size: 15px;
                color: var(--dark);
                margin-bottom: 3px;
            }

            .ep-feature-text span {
                font-size: 14px;
                color: var(--light);
            }

            /* Tabs */
            .ep-tabs {
                background: white;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .ep-tabs-nav {
                display: flex;
                background: var(--bg-light);
                border-bottom: 2px solid var(--border);
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .ep-tab-btn {
                flex: 1 1 auto;
                padding: 20px 30px;
                background: transparent;
                border: none;
                font-size: 17px;
                font-weight: 700;
                color: var(--light);
                cursor: pointer;
                transition: all 0.3s;
                position: relative;
                white-space: nowrap;
            }

            .ep-tab-btn:hover {
                color: var(--primary);
                background: rgba(37, 99, 235, 0.05);
            }

            .ep-tab-btn.active {
                color: var(--primary);
            }

            .ep-tab-btn.active::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 0;
                right: 0;
                height: 3px;
                background: var(--primary);
            }

            .ep-tab-content {
                padding: 40px;
                display: none;
                animation: fadeIn 0.4s;
            }

            .ep-tab-content.active {
                display: block;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Responsive */
            @media (max-width: 968px) {
                .ep-grid {
                    grid-template-columns: 1fr;
                    gap: 40px;
                }

                .ep-images {
                    position: relative;
                    top: 0;
                }

                .ep-title {
                    font-size: 32px;
                }

                .ep-price {
                    font-size: 38px;
                }

                .ep-features {
                    grid-template-columns: 1fr;
                }

                .ep-cart-form {
                    flex-direction: column;
                }

                .ep-add-cart {
                    width: 100%;
                    min-height: 55px;
                }
            }

            @media (max-width: 640px) {
                .enhanced-product-page {
                    padding: 20px 15px;
                }

                .ep-actions {
                    grid-template-columns: 1fr;
                }

                .ep-price-box {
                    padding: 20px;
                }

                .ep-tab-btn {
                    padding: 16px 20px;
                    font-size: 15px;
                }
            }

            .ep-loading {
                pointer-events: none;
                opacity: 0.7;
            }

            .ep-loading::after {
                content: '';
                width: 18px;
                height: 18px;
                border: 3px solid rgba(255, 255, 255, 0.3);
                border-top-color: white;
                border-radius: 50%;
                animation: spin 0.6s linear infinite;
                margin-left: 10px;
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }

            /* Video Section */
            .ep-video-section {
                background: white;
                border-radius: 20px;
                padding: 40px;
                margin-bottom: 60px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            }

            .ep-video-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .ep-video-title {
                font-size: 32px;
                font-weight: 800;
                color: var(--dark);
                margin: 0 0 10px 0;
            }

            .ep-video-subtitle {
                font-size: 16px;
                color: var(--light);
                margin: 0;
            }

            .ep-video-container {
                position: relative;
                width: 100%;
                max-width: 900px;
                margin: 0 auto;
                border-radius: 16px;
                overflow: hidden;
                background: #000;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            }

            .ep-video-container iframe,
            .ep-video-container video {
                width: 100%;
                aspect-ratio: 16/9;
                display: block;
                border: none;
            }

            @media (max-width: 640px) {
                .ep-video-section {
                    padding: 25px 20px;
                }

                .ep-video-title {
                    font-size: 24px;
                }

                .ep-video-subtitle {
                    font-size: 14px;
                }
            }

            /* Features */
            .ep-features {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                padding: 30px;
                background: var(--bg-light);
                border-radius: 20px;
            }

            .ep-feature {
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .ep-feature-icon {
                width: 50px;
                height: 50px;
                min-width: 50px;
                background: white;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            }

            .ep-feature-text {
                flex: 1;
                min-width: 0;
            }

            .ep-feature-text strong {
                display: block;
                font-size: 15px;
                color: var(--dark);
                margin-bottom: 3px;
                line-height: 1.3;
            }

            .ep-feature-text span {
                font-size: 14px;
                color: var(--light);
                line-height: 1.4;
            }

            /* Simple ACF Accordion */
            .ep-accordion {
                margin-top: 40px;
                background: #ffffff;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
                overflow: hidden;
            }

            .ep-accordion-title {
                margin: 0;
                padding: 24px 30px 10px 30px;
                font-size: 22px;
                font-weight: 800;
                color: var(--dark);
            }

            .ep-accordion-subtitle {
                margin: 0 30px 20px 30px;
                font-size: 14px;
                color: var(--light);
            }

            .ep-accordion-list {
                border-top: 1px solid var(--border);
            }

            .ep-accordion-item {
                border-bottom: 1px solid var(--border);
                background: #ffffff;
                transition: background 0.2s ease;
            }

            .ep-accordion-item.is-active {
                background: var(--bg-light);
            }

            .ep-accordion-header {
                width: 100%;
                text-align: left;
                padding: 18px 30px;
                border: none;
                background: transparent;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                color: var(--dark);
            }

            .ep-accordion-heading-text {
                flex: 1;
            }

            .ep-accordion-icon {
                width: 24px;
                height: 24px;
                border-radius: 999px;
                border: 1px solid var(--border);
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
                font-weight: 700;
                transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
            }

            .ep-accordion-item.is-active .ep-accordion-icon {
                transform: rotate(45deg);
                background: var(--primary);
                border-color: var(--primary);
                color: #ffffff;
            }

            .ep-accordion-body {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.25s ease, opacity 0.25s ease;
                opacity: 0;
            }

            .ep-accordion-item.is-active .ep-accordion-body {
                opacity: 1;
            }

            .ep-accordion-body-inner {
                padding: 0 30px 20px 30px;
                font-size: 15px;
                line-height: 1.7;
            }

            .ep-accordion-body-inner ul {
                list-style-position: inside;
                padding-left: 0;
            }

            @media (max-width: 640px) {

                .ep-accordion-title,
                .ep-accordion-subtitle {
                    padding-left: 20px;
                    padding-right: 20px;
                }

                .ep-accordion-header {
                    padding: 16px 20px;
                }

                .ep-accordion-body-inner {
                    padding: 0 20px 18px 20px;
                }
            }

            .ep-read-more-link {
                margin-top: 10px;
                padding: 6px 12px;
                border-radius: 999px;
                border: 1px solid transparent;
                background: transparent;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 14px;
                font-weight: 600;
                color: var(--primary);
                text-decoration: none;
                transition:
                    background 0.2s ease,
                    border-color 0.2s ease,
                    transform 0.15s ease,
                    box-shadow 0.2s ease;
                border-color: rgba(37, 99, 235, 0.25);
            }

            .ep-read-more-link:hover {
                background: rgba(37, 99, 235, 0.06);
                transform: translateY(-1px);
                box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
            }

            .ep-read-more-link:active {
                transform: translateY(0);
                box-shadow: none;
            }

            .ep-read-more-icon {
                font-size: 13px;
            }

            /* ================== EXTRA RESPONSIVE TWEAKS ================== */

            /* Medium screens: tighten layout, avoid huge gaps */
            @media (max-width: 1024px) {
                .enhanced-product-page {
                    padding: 30px 16px;
                }

                .ep-grid {
                    gap: 36px;
                }

                .ep-price-box {
                    flex-direction: column;
                    align-items: flex-start;
                }

                .ep-title {
                    font-size: 34px;
                }

                .ep-description {
                    font-size: 16px;
                }

                .ep-video-section {
                    margin-bottom: 40px;
                }
            }

            /* Tablets & small laptops (you already had some rules; these extend them) */
            @media (max-width: 968px) {
                .ep-grid {
                    grid-template-columns: 1fr;
                    gap: 32px;
                }

                .ep-images {
                    position: relative;
                    top: 0;
                    max-width: 520px;
                    margin: 0 auto;
                }

                .ep-main-image {
                    aspect-ratio: 4 / 3;
                    border-radius: 16px;
                }

                .ep-thumbnails {
                    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                    max-width: 520px;
                    margin: 0 auto;
                }

                .ep-title {
                    font-size: 30px;
                }

                .ep-price-box {
                    padding: 20px;
                }

                .ep-price {
                    font-size: 34px;
                }

                .ep-features {
                    grid-template-columns: 1fr;
                    padding: 20px;
                }

                .ep-tabs {
                    margin-top: 32px;
                }
            }

            /* Mobiles */
            @media (max-width: 640px) {
                .enhanced-product-page {
                    padding: 20px 12px;
                }

                .ep-breadcrumb {
                    font-size: 12px;
                    row-gap: 4px;
                }

                .ep-title {
                    font-size: 26px;
                }

                .ep-rating-box {
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .ep-price-box {
                    padding: 18px 16px;
                    border-radius: 16px;
                }

                .ep-price {
                    font-size: 30px;
                }

                .ep-description {
                    font-size: 15px;
                }

                .ep-stock {
                    padding: 12px 14px;
                    border-radius: 12px;
                }

                .ep-cart-form {
                    flex-direction: column;
                    align-items: stretch;
                }

                .ep-quantity {
                    width: 100%;
                    justify-content: space-between;
                }

                .ep-qty-input {
                    flex: 1;
                }

                .ep-add-cart {
                    width: 100%;
                    min-height: 52px;
                }

                .ep-features {
                    padding: 18px 16px;
                    gap: 14px;
                }

                .ep-feature-icon {
                    width: 42px;
                    height: 42px;
                    min-width: 42px;
                    font-size: 20px;
                }

                .ep-tabs-nav {
                    padding: 0 8px;
                }

                .ep-tab-btn {
                    padding: 14px 16px;
                    font-size: 14px;
                }

                .ep-tab-content {
                    padding: 24px 16px;
                }

                .ep-video-section {
                    padding: 20px 14px;
                    margin-bottom: 32px;
                }

                .ep-accordion {
                    margin-top: 28px;
                    border-radius: 16px;
                }
            }

            /* Very small devices */
            @media (max-width: 480px) {
                .ep-title {
                    font-size: 22px;
                }

                .ep-price {
                    font-size: 26px;
                }

                .ep-description {
                    font-size: 14px;
                }

                .ep-accordion-title {
                    font-size: 18px;
                }

                .ep-accordion-header {
                    font-size: 14px;
                }

                .ep-read-more-link {
                    font-size: 13px;
                }
            }

            /* Tablets & small laptops */
            @media (max-width: 1024px) {
                .ep-images {
                    position: relative;
                    top: 0;
                    width: 100%;
                    max-width: 100%;
                    margin: 0 auto 0;
                }

                .ep-main-image {
                    aspect-ratio: 4 / 3;
                    max-width: 100%;
                    border-radius: 18px;
                }

                .ep-thumbnails {
                    max-width: 520px;
                    margin: 0 auto;
                    grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
                }
            }

            /* Mobiles */
            @media (max-width: 640px) {
                .ep-images {
                    margin-top: 10px;
                }

                .ep-main-image {
                    aspect-ratio: 4 / 3;
                    border-radius: 16px;
                    box-shadow: 0 10px 24px rgba(0, 0, 0, 0.08);
                }

                .ep-thumbnails {
                    gap: 10px;
                    grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
                }

                .ep-thumb {
                    border-radius: 10px;
                }
            }

            /* Tablets: make tabs more compact */
            @media (max-width: 968px) {
                .ep-tabs {
                    margin-top: 30px;
                }

                .ep-tabs-nav {
                    padding: 0 8px;
                }

                .ep-tab-btn {
                    padding: 14px 18px;
                    font-size: 15px;
                }
            }

            /* Mobiles: stack tabs vertically so they don’t overflow */
            @media (max-width: 640px) {
                .ep-tabs-nav {
                    flex-wrap: wrap;
                    overflow-x: visible;
                    border-bottom: 1px solid var(--border);
                }

                .ep-tab-btn {
                    flex: 1 1 100%;
                    text-align: left;
                    padding: 12px 16px;
                    font-size: 14px;
                    white-space: normal;
                    border-bottom: 1px solid var(--border);
                }

                .ep-tab-btn.active::after {
                    bottom: 0;
                    height: 2px;
                }

                .ep-tab-content {
                    padding: 20px 14px;
                }
            }

            /* Tiny phones: even smaller tab text */
            @media (max-width: 480px) {
                .ep-tab-btn {
                    font-size: 13px;
                    padding: 10px 14px;
                }
            }

            /* Ultra-small devices: fix ep-images block */
            @media (max-width: 500px) {
                .enhanced-product-page {
                    padding: 16px 8px;
                    overflow-x: hidden;
                }

                .ep-grid {
                    grid-template-columns: 1fr;
                    gap: 24px;
                }

                .ep-images {
                    position: relative;
                    /* disable sticky on tiny screens */
                    top: 0;
                    width: 100%;
                    max-width: 100%;
                    margin: 0 auto 8px;
                }

                .ep-main-image {
                    width: 100%;
                    max-width: 100%;
                    margin: 0 auto 10px;
                    border-radius: 12px;
                    aspect-ratio: 3 / 4;
                    /* a bit taller, fits phones better */
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                }

                .ep-main-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }

                .ep-thumbnails {
                    max-width: 100%;
                    margin: 0 auto;
                    grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
                    gap: 8px;
                }

                .ep-thumb {
                    border-radius: 8px;
                }
            }

            /* Bundled Products Section */
            .ep-bundled-products {
                margin-top: 40px;
                padding-top: 40px;
                border-top: 2px solid var(--border);
            }

            .ep-bundled-title {
                font-size: 24px;
                font-weight: 800;
                color: var(--dark);
                margin: 0 0 30px 0;
            }

            .ep-bundled-items {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 25px;
            }

            .ep-bundled-item {
                display: flex;
                gap: 15px;
                padding: 20px;
                background: var(--bg-light);
                border-radius: 15px;
                border: 1px solid var(--border);
                transition: all 0.3s ease;
            }

            .ep-bundled-item:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                border-color: var(--primary);
            }

            .ep-bundled-image {
                flex-shrink: 0;
                width: 100px;
                height: 100px;
                border-radius: 12px;
                overflow: hidden;
                background: white;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .ep-bundled-image img {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .ep-bundled-image a {
                display: block;
                width: 100%;
                height: 100%;
            }

            .ep-bundled-info {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                min-width: 0;
            }

            .ep-bundled-name {
                margin: 0 0 8px 0;
                font-size: 16px;
                font-weight: 600;
                line-height: 1.4;
            }

            .ep-bundled-name a {
                color: var(--dark);
                text-decoration: none;
                transition: color 0.3s;
            }

            .ep-bundled-name a:hover {
                color: var(--primary);
            }

            .ep-bundled-case-pack {
                font-size: 14px;
                color: var(--light);
            }

            .ep-bundled-case-pack strong {
                color: var(--dark);
                font-weight: 700;
            }

            @media (max-width: 640px) {
                .ep-bundled-products {
                    margin-top: 30px;
                    padding-top: 30px;
                }

                .ep-bundled-title {
                    font-size: 20px;
                    margin-bottom: 20px;
                }

                .ep-bundled-items {
                    grid-template-columns: 1fr;
                    gap: 15px;
                }

                .ep-bundled-item {
                    padding: 15px;
                }

                .ep-bundled-image {
                    width: 80px;
                    height: 80px;
                }

                .ep-bundled-name {
                    font-size: 15px;
                }
            }
        </style>

        <div class="enhanced-product-page">
            <!-- Breadcrumb -->
            <nav class="ep-breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a>
                <span>/</span>
                <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>">Shop</a>
                <span>/</span>
                <?php if ($categories && ! is_wp_error($categories)) : ?>
                    <a href="<?php echo get_term_link($categories[0]); ?>"><?php echo esc_html($categories[0]->name); ?></a>
                    <span>/</span>
                <?php endif; ?>
                <span><?php the_title(); ?></span>
            </nav>

            <?php wc_print_notices(); ?>

            <!-- Main Grid -->
            <div class="ep-grid">
                <!-- Images -->
                <div class="ep-images">
                    <div class="ep-main-image" id="epMainImage">
                        <?php if ($product->is_on_sale()) : ?>
                            <div class="ep-badge">
                                SALE -<?php echo round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100); ?>%
                            </div>
                        <?php elseif ($product->get_featured()) : ?>
                            <div class="ep-badge featured">FEATURED</div>
                        <?php endif; ?>

                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url($product_id, 'full'); ?>" alt="<?php the_title(); ?>" id="epMainImg">
                        <?php else : ?>
                            <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php the_title(); ?>" id="epMainImg">
                        <?php endif; ?>

                        <div class="ep-zoom-hint">🔍 Hover to zoom</div>
                    </div>

                    <?php if ($attachment_ids || has_post_thumbnail()) : ?>
                        <div class="ep-thumbnails">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="ep-thumb active" data-image="<?php echo get_the_post_thumbnail_url($product_id, 'full'); ?>">
                                    <img src="<?php echo get_the_post_thumbnail_url($product_id, 'thumbnail'); ?>" alt="Main">
                                </div>
                            <?php endif; ?>

                            <?php foreach ($attachment_ids as $attachment_id) : ?>
                                <div class="ep-thumb" data-image="<?php echo wp_get_attachment_url($attachment_id); ?>">
                                    <img src="<?php echo wp_get_attachment_image_url($attachment_id, 'thumbnail'); ?>" alt="Gallery">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info -->
                <div class="ep-info">
                    <!-- Tags -->
                    <?php if ($categories && ! is_wp_error($categories)) : ?>
                        <div class="ep-tags">
                            <?php foreach ($categories as $category) : ?>
                                <span class="ep-tag">📁 <?php echo esc_html($category->name); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Title -->
                    <h1 class="ep-title"><?php the_title(); ?></h1>

                    <!-- Rating -->
                    <?php if ($rating > 0) : ?>
                        <div class="ep-rating-box">
                            <div class="ep-stars">
                                <?php for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $rating ? '★' : '☆';
                                } ?>
                            </div>
                            <span class="ep-rating-text"><?php echo number_format($rating, 1); ?> (<?php echo $review_count; ?> reviews)</span>
                        </div>
                    <?php endif; ?>

                    <!-- Price -->
                    <div class="ep-price-box">
                        <?php if ($product->is_on_sale()) : ?>
                            <span class="ep-price-old"><?php echo wc_price($product->get_regular_price()); ?></span>
                            <span class="ep-price"><?php echo wc_price($product->get_sale_price()); ?></span>
                            <span class="ep-discount">
                                SAVE <?php echo round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100); ?>%
                            </span>
                        <?php else : ?>
                            <span class="ep-price"><?php echo wc_price($product->get_price()); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Description -->
                    <?php if ($product->get_short_description()) : ?>
                        <div class='ep-description'>
                            <?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?>

                            <button
                                type='button'
                                class='ep-read-more-link'
                                data-scroll-target='#epTabDesc'>
                                <span class='ep-read-more-label'>Read full description</span>
                                <span class='ep-read-more-icon'>↓</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Stock -->
                    <div class="ep-stock <?php echo $product->is_in_stock() ? '' : 'out'; ?>">
                        <div class="ep-stock-icon">✓</div>
                        <span class="ep-stock-text">
                            <?php echo $product->is_in_stock() ? 'In Stock - Ready to Ship' : 'Out of Stock'; ?>
                        </span>
                    </div>

                    <!-- Add to Cart -->
                    <?php if ($product->is_in_stock()) : ?>
                        <form class="cart ep-cart-form" method="post" enctype="multipart/form-data">
                            <div class="ep-quantity">
                                <button type="button" class="ep-qty-btn" id="epQtyMinus">−</button>
                                <input type="number" class="ep-qty-input" name="quantity" value="1" min="1" id="epQtyInput">
                                <button type="button" class="ep-qty-btn" id="epQtyPlus">+</button>
                            </div>
                            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="ep-add-cart" id="epAddCart">
                                <span style="font-size: 24px;">🛒</span>
                                <span>Add to Cart</span>
                            </button>
                        </form>
                    <?php else : ?>
                        <button class="ep-add-cart" style="background: var(--light); cursor: not-allowed;" disabled>
                            Out of Stock
                        </button>
                    <?php endif; ?>

                    <!-- Actions -->
                    <div class='ep-actions'>
                        <button
                            class='ep-action-btn<?php echo $is_in_wishlist ? ' added' : ''; ?>'
                            id='epWishlist'
                            type='button'
                            data-product-id='<?php echo (int) $product_id; ?>'
                            data-in-wishlist='<?php echo $is_in_wishlist ? '1' : '0'; ?>'>
                            <span class='ep-wishlist-icon' aria-hidden='true'>
                                <?php echo $is_in_wishlist ? '♥' : '♡'; ?>
                            </span>
                            <span class='ep-wishlist-label'>
                                <?php echo $is_in_wishlist ? 'Added!' : 'Wishlist'; ?>
                            </span>
                        </button>
                    </div>

                    <!-- Features -->
                    <div class="ep-features">
                        <div class="ep-feature">
                            <div class="ep-feature-icon">🚚</div>
                            <div class="ep-feature-text">
                                <strong>Free Shipping</strong>
                                <span>On orders over $3000</span>
                            </div>
                        </div>
                        <div class="ep-feature">
                            <div class="ep-feature-icon">🚚</div>
                            <div class="ep-feature-text">
                                <strong>$99 Shipping</strong>
                                <span>On orders over $2000</span>
                            </div>
                        </div>
                        <div class="ep-feature">
                            <div class="ep-feature-icon">✓</div>
                            <div class="ep-feature-text">
                                <strong>Quality Guaranteed</strong>
                                <span>Top-rated products</span>
                            </div>
                        </div>
                        <div class="ep-feature">
                            <div class="ep-feature-icon">📞</div>
                            <div class="ep-feature-text">
                                <strong>Customer Service</strong>
                                <span>Contact us anytime</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Video Section -->
            <?php
            // Check for ACF field first, fallback to post meta
            $product_video_url = function_exists('get_field') ? get_field('product_video_url') : get_post_meta($product_id, '_product_video_url', true);

            if ($product_video_url) :
            ?>
                <div class="ep-video-section">
                    <div class="ep-video-header">
                        <h2 class="ep-video-title">🎥 Product Video</h2>
                        <p class="ep-video-subtitle">Watch our detailed product demonstration</p>
                    </div>
                    <div class="ep-video-container">
                        <?php
                        // Check if it's a Wistia URL and tb_wistia_embed_from_url function exists
                        if (strpos($product_video_url, 'wistia.com') !== false || strpos($product_video_url, 'wi.st') !== false) {
                            if (function_exists('tb_wistia_embed_from_url')) {
                                echo tb_wistia_embed_from_url($product_video_url, 56.25); // 16:9 aspect ratio
                            } else {
                                echo '<p>Wistia embed function not available.</p>';
                            }
                        }
                        // Check if it's a YouTube URL
                        elseif (strpos($product_video_url, 'youtube.com') !== false || strpos($product_video_url, 'youtu.be') !== false) {
                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $product_video_url, $match);
                            $youtube_id = isset($match[1]) ? $match[1] : '';
                            if ($youtube_id) {
                                echo '<iframe src="https://www.youtube.com/embed/' . esc_attr($youtube_id) . '?rel=0" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                            }
                        }
                        // Check if it's a Vimeo URL
                        elseif (strpos($product_video_url, 'vimeo.com') !== false) {
                            preg_match('/vimeo\.com\/(\d+)/', $product_video_url, $match);
                            $vimeo_id = isset($match[1]) ? $match[1] : '';
                            if ($vimeo_id) {
                                echo '<iframe src="https://player.vimeo.com/video/' . esc_attr($vimeo_id) . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
                            }
                        }
                        // Direct video file
                        else {
                            echo '<video controls><source src="' . esc_url($product_video_url) . '" type="video/mp4">Your browser does not support the video tag.</video>';
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="ep-tabs">
                <div id="epTabsNav" class="ep-tabs-nav">
                    <button class="ep-tab-btn active" data-tab="desc">📝 Description</button>
                    <button class="ep-tab-btn" data-tab="specs">📊 Specifications</button>
                    <button class="ep-tab-btn" data-tab="reviews">⭐ Reviews (<?php echo $review_count; ?>)</button>
                </div>

                <div class="ep-tab-content active" id="epTabDesc">
                    <?php the_content(); ?>
                    
                    <?php
                    // Display bundled products for bundle type products
                    if ($product->get_type() === 'woosb' && method_exists($product, 'get_items')) {
                        $bundled_items = $product->get_items();
                        
                        if (!empty($bundled_items)) :
                    ?>
                        <div class="ep-bundled-products">
                            <h3 class="ep-bundled-title">What's Included in This Bundle</h3>
                            <div class="ep-bundled-items">
                                <?php foreach ($bundled_items as $item) :
                                    $bundled_product_id = isset($item['id']) ? $item['id'] : 0;
                                    
                                    if ($bundled_product_id) {
                                        $bundled_product = wc_get_product($bundled_product_id);
                                        
                                        if ($bundled_product && $bundled_product->is_visible()) :
                                            $product_image = $bundled_product->get_image('woocommerce_thumbnail');
                                            $product_name = $bundled_product->get_name();
                                            $product_permalink = $bundled_product->get_permalink();
                                            
                                            // Get case pack custom field
                                            $case_pack = function_exists('get_field') ? get_field('case_pack', $bundled_product_id) : '';
                                            $case_pack_display = $case_pack ? esc_html($case_pack) : '';
                                ?>
                                    <div class="ep-bundled-item">
                                        <div class="ep-bundled-image">
                                            <a href="<?php echo esc_url($product_permalink); ?>">
                                                <?php echo $product_image; ?>
                                            </a>
                                        </div>
                                        <div class="ep-bundled-info">
                                            <h4 class="ep-bundled-name">
                                                <a href="<?php echo esc_url($product_permalink); ?>">
                                                    <?php echo esc_html($product_name); ?>
                                                </a>
                                            </h4>
                                            <?php if ($case_pack_display) : ?>
                                                <div class="ep-bundled-case-pack">
                                                    Case Pack: <strong><?php echo $case_pack_display; ?></strong>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php
                                        endif;
                                    }
                                endforeach; ?>
                            </div>
                        </div>
                    <?php
                        endif;
                    }
                    ?>
                </div>

                <div class="ep-tab-content" id="epTabSpecs">
                    <?php
                    do_action('woocommerce_product_additional_information', $product);
                    wc_display_product_attributes($product);
                    ?>
                </div>

                <div class="ep-tab-content" id="epTabReviews">
                    <?php comments_template(); ?>
                </div>
            </div>

            <?php
            // Simple ACF accordion (no repeater, fixed fields)
            $color        = function_exists('get_field') ? get_field('color', $product_id) : '';
            $effects      = function_exists('get_field') ? get_field('effects', $product_id) : '';
            $duration     = function_exists('get_field') ? get_field('duration', $product_id) : '';
            $case_pack    = function_exists('get_field') ? get_field('case_pack', $product_id) : '';
            $what_you_get = function_exists('get_field') ? get_field('what_you_get', $product_id) : '';

            if ($color || $effects || $duration || $case_pack || $what_you_get) :
            ?>
                <div class='ep-accordion' id='epSimpleAccordion'>
                    <h2 class='ep-accordion-title'>More product details</h2>
                    <p class='ep-accordion-subtitle'>Tap a section to see more info.</p>

                    <div class='ep-accordion-list'>
                        <?php if ($color) : ?>
                            <div class='ep-accordion-item is-active'>
                                <button class='ep-accordion-header' type='button' aria-expanded='true'>
                                    <span class='ep-accordion-heading-text'>Color</span>
                                    <span class='ep-accordion-icon'>+</span>
                                </button>
                                <div class='ep-accordion-body'>
                                    <div class='ep-accordion-body-inner'>
                                        <?php echo $color; // WYSIWYG, no escaping 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($effects) : ?>
                            <div class='ep-accordion-item<?php echo $color ? '' : ' is-active'; ?>'>
                                <button class='ep-accordion-header' type='button' aria-expanded='<?php echo $color ? 'false' : 'true'; ?>'>
                                    <span class='ep-accordion-heading-text'>Effects</span>
                                    <span class='ep-accordion-icon'>+</span>
                                </button>
                                <div class='ep-accordion-body'>
                                    <div class='ep-accordion-body-inner'>
                                        <?php echo $effects; // WYSIWYG, no escaping 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($duration) : ?>
                            <div class='ep-accordion-item<?php echo (! $color && ! $effects) ? ' is-active' : ''; ?>'>
                                <button class='ep-accordion-header' type='button' aria-expanded='<?php echo (! $color && ! $effects) ? 'true' : 'false'; ?>'>
                                    <span class='ep-accordion-heading-text'>Duration</span>
                                    <span class='ep-accordion-icon'>+</span>
                                </button>
                                <div class='ep-accordion-body'>
                                    <div class='ep-accordion-body-inner'>
                                        <?php echo $duration; // Text, no escaping as requested 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($case_pack) : ?>
                            <div class='ep-accordion-item<?php echo (! $color && ! $effects && ! $duration) ? ' is-active' : ''; ?>'>
                                <button class='ep-accordion-header' type='button' aria-expanded='<?php echo (! $color && ! $effects && ! $duration) ? 'true' : 'false'; ?>'>
                                    <span class='ep-accordion-heading-text'>Case Pack</span>
                                    <span class='ep-accordion-icon'>+</span>
                                </button>
                                <div class='ep-accordion-body'>
                                    <div class='ep-accordion-body-inner'>
                                        <?php echo $case_pack; // Text 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if ($what_you_get) : ?>
                            <div class='ep-accordion-item<?php echo (! $color && ! $effects && ! $duration && ! $case_pack) ? ' is-active' : ''; ?>'>
                                <button class='ep-accordion-header' type='button' aria-expanded='<?php echo (! $color && ! $effects && ! $duration && ! $case_pack) ? 'true' : 'false'; ?>'>
                                    <span class='ep-accordion-heading-text'>What You Get</span>
                                    <span class='ep-accordion-icon'>+</span>
                                </button>
                                <div class='ep-accordion-body'>
                                    <div class='ep-accordion-body-inner'>
                                        <?php echo $what_you_get; // Textarea 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>


        </div>

        <script>
            (function() {
                // Quantity Controls
                const qtyInput = document.getElementById('epQtyInput');
                const qtyMinus = document.getElementById('epQtyMinus');
                const qtyPlus = document.getElementById('epQtyPlus');

                if (qtyMinus && qtyInput) {
                    qtyMinus.addEventListener('click', function() {
                        const val = parseInt(qtyInput.value);
                        if (val > 1) qtyInput.value = val - 1;
                    });
                }

                if (qtyPlus && qtyInput) {
                    qtyPlus.addEventListener('click', function() {
                        qtyInput.value = parseInt(qtyInput.value) + 1;
                    });
                }

                // Thumbnail Gallery
                const thumbs = document.querySelectorAll('.ep-thumb');
                const mainImg = document.getElementById('epMainImg');

                thumbs.forEach(function(thumb) {
                    thumb.addEventListener('click', function() {
                        thumbs.forEach(function(t) {
                            t.classList.remove('active');
                        });
                        this.classList.add('active');
                        if (mainImg) {
                            mainImg.src = this.getAttribute('data-image');
                        }
                    });
                });

                // Tabs
                const tabBtns = document.querySelectorAll('.ep-tab-btn');
                const tabContents = document.querySelectorAll('.ep-tab-content');

                tabBtns.forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const tab = this.getAttribute('data-tab');

                        tabBtns.forEach(function(b) {
                            b.classList.remove('active');
                        });
                        tabContents.forEach(function(c) {
                            c.classList.remove('active');
                        });

                        this.classList.add('active');

                        if (tab === 'desc') document.getElementById('epTabDesc').classList.add('active');
                        if (tab === 'specs') document.getElementById('epTabSpecs').classList.add('active');
                        if (tab === 'reviews') document.getElementById('epTabReviews').classList.add('active');
                    });
                });

                // Wishlist: add/remove via Bricks AJAX
                document.addEventListener('DOMContentLoaded', function() {

                    // Wishlist: add/remove via direct AJAX
                    var wishlistBtn = document.getElementById('epWishlist');

                    if (wishlistBtn) {
                        console.log('Wishlist init: button found');

                        var icon = wishlistBtn.querySelector('.ep-wishlist-icon');
                        var label = wishlistBtn.querySelector('.ep-wishlist-label');

                        // Initial state from data attribute (set in PHP)
                        var inWishlist = wishlistBtn.getAttribute('data-in-wishlist') === '1';

                        // Apply initial UI state
                        if (inWishlist) {
                            wishlistBtn.classList.add('added');
                            wishlistBtn.style.borderColor = '#ef4444';
                            wishlistBtn.style.color = '#ef4444';
                            if (icon) {
                                icon.textContent = '♥';
                                icon.style.color = '#ef4444';
                            }
                            if (label) {
                                label.textContent = 'Added!';
                            }
                        }

                        wishlistBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('Wishlist button clicked, inWishlist =', inWishlist);

                            if (wishlistBtn.classList.contains('ep-wishlist-loading')) {
                                return;
                            }

                            var productId = wishlistBtn.getAttribute('data-product-id');
                            var addNonce = window.brthemeWishlist.nonces.add;
                            var removeNonce = window.brthemeWishlist.nonces.remove;

                            if (!productId || !addNonce || !removeNonce) {
                                console.warn('Wishlist: missing data attributes');
                                return;
                            }

                            // ✅ use real remove action from Wishlist.js
                            var action = inWishlist ? 'brtheme_remove_wishlist_item' : 'brtheme_add_to_wishlist';
                            var nonce = inWishlist ? removeNonce : addNonce;

                            var params = new URLSearchParams();
                            params.append('action', action);
                            params.append('nonce', nonce);
                            params.append('productId', productId);

                            wishlistBtn.classList.add('ep-wishlist-loading');

                            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                                    method: 'POST',
                                    credentials: 'include',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    },
                                    body: params.toString()
                                })
                                .then(function(response) {
                                    return response.json().catch(function() {
                                        return {
                                            success: response.ok
                                        };
                                    });
                                })
                                .then(function(data) {
                                    console.log('Wishlist response:', data);

                                    if (!data || data.success === false) {
                                        console.warn('Wishlist error', data);
                                        return;
                                    }

                                    // Toggle local state
                                    inWishlist = !inWishlist;

                                    if (inWishlist) {
                                        wishlistBtn.classList.add('added');
                                        wishlistBtn.style.borderColor = '#ef4444';
                                        wishlistBtn.style.color = '#ef4444';

                                        if (icon) {
                                            icon.textContent = '♥';
                                            icon.style.color = '#ef4444';
                                        }
                                        if (label) {
                                            label.textContent = 'Added!';
                                        }
                                    } else {
                                        wishlistBtn.classList.remove('added');
                                        wishlistBtn.style.borderColor = '';
                                        wishlistBtn.style.color = '';

                                        if (icon) {
                                            icon.textContent = '♡';
                                            icon.style.color = '';
                                        }
                                        if (label) {
                                            label.textContent = 'Wishlist';
                                        }
                                    }
                                })
                                .catch(function(err) {
                                    console.error('Wishlist AJAX failed', err);
                                })
                                .finally(function() {
                                    wishlistBtn.classList.remove('ep-wishlist-loading');
                                });
                        });
                    } else {
                        console.log('Wishlist init: button not found');
                    }

                });

                // Compare
                const compareBtn = document.getElementById('epCompare');
                if (compareBtn) {
                    compareBtn.addEventListener('click', function() {
                        if (this.classList.contains('added')) {
                            this.innerHTML = '<span style="font-size: 20px;">⚖</span><span>Compare</span>';
                            this.classList.remove('added');
                            this.style.borderColor = '';
                            this.style.color = '';
                        } else {
                            this.innerHTML = '<span style="font-size: 20px;">✓</span><span>Compared!</span>';
                            this.classList.add('added');
                            this.style.borderColor = '#10b981';
                            this.style.color = '#10b981';
                        }
                    });
                }

                // Add to Cart Loading
                const addCartBtn = document.getElementById('epAddCart');
                if (addCartBtn) {
                    addCartBtn.addEventListener('click', function() {
                        this.classList.add('ep-loading');
                    });
                }
                // Simple ACF Accordion
                var simpleAccordion = document.getElementById('epSimpleAccordion');
                if (simpleAccordion) {
                    var accItems = simpleAccordion.querySelectorAll('.ep-accordion-item');

                    accItems.forEach(function(item) {
                        var header = item.querySelector('.ep-accordion-header');
                        var body = item.querySelector('.ep-accordion-body');

                        if (!header || !body) {
                            return;
                        }

                        // Set initial height for active items
                        if (item.classList.contains('is-active')) {
                            body.style.maxHeight = body.scrollHeight + 'px';
                        }

                        header.addEventListener('click', function() {
                            var isOpen = item.classList.contains('is-active');

                            // Close all
                            accItems.forEach(function(other) {
                                var otherBody = other.querySelector('.ep-accordion-body');
                                var otherHeader = other.querySelector('.ep-accordion-header');
                                if (!otherBody || !otherHeader) {
                                    return;
                                }
                                other.classList.remove('is-active');
                                otherBody.style.maxHeight = null;
                                otherBody.style.opacity = 0;
                                otherHeader.setAttribute('aria-expanded', 'false');
                            });

                            // If previously closed, open this one
                            if (!isOpen) {
                                item.classList.add('is-active');
                                body.style.maxHeight = body.scrollHeight + 'px';
                                body.style.opacity = 1;
                                header.setAttribute('aria-expanded', 'true');
                            }
                        });
                    });

                    // Recalculate height on resize
                    window.addEventListener('resize', function() {
                        accItems.forEach(function(item) {
                            if (item.classList.contains('is-active')) {
                                var body = item.querySelector('.ep-accordion-body');
                                if (body) {
                                    body.style.maxHeight = body.scrollHeight + 'px';
                                }
                            }
                        });
                    });
                }

                // Smooth scroll for 'Read full description' with offset
                var readMoreBtn = document.querySelector('.ep-read-more-link');
                if (readMoreBtn) {
                    readMoreBtn.addEventListener('click', function(e) {
                        var targetSelector = this.getAttribute('data-scroll-target');
                        if (!targetSelector) {
                            return;
                        }

                        var targetEl = document.querySelector(targetSelector);
                        if (!targetEl) {
                            return;
                        }

                        e.preventDefault();

                        var offset = 120; // adjust this if needed (px)
                        var targetPosition = targetEl.getBoundingClientRect().top + window.pageYOffset - offset;

                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    });
                }

            })();
        </script>

<?php
    }
}

get_footer();
?>