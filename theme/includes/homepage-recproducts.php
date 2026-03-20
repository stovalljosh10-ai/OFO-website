<?php

use BRTheme\WishlistHandler;

function custom_slider_shortcode_recommended_products()
{
    ob_start();

    // Get WooCommerce products (exclude out of stock, limit to 9)
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 9,
        'orderby' => 'rand',
        'post_status' => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',     // or 'term_id'
                'terms'    => array('new') // replace with your category slug(s)
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => '!='
            ),
        )
    );

    $products = new WP_Query($args);

    if (!$products->have_posts()) {
        return '<p>No products found</p>';
    }

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
?>

    <div class="rec-products-slider-wrapper">
        <div class="rec-slider-header">
            <h2 class="rec-slider-title">Recommended Products</h2>
            <div class="rec-slider-nav">
                <button class="rec-nav-btn rec-prev" aria-label="Previous">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <button class="rec-nav-btn rec-next" aria-label="Next">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="rec-slider-container">
            <div class="rec-slider-track">
                <?php
                $count = 0;
                while ($products->have_posts()) : $products->the_post();
                    global $product;
                    $product_id = get_the_ID();
                    $image_id = $product->get_image_id();
                    $image_url = wp_get_attachment_image_url($image_id, 'large');
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $is_on_sale = $product->is_on_sale();

                    $wishlist_items = array();

                    if (class_exists('\BRTheme\WishlistHandler')) {
                        $wishlist_items = WishlistHandler::get_user_wishlist_items();
                    }

                    // Cast product ID to string because wishlist stores IDs as strings
                    $is_in_wishlist = in_array((string) $product_id, $wishlist_items, true);
                ?>

                    <div class="rec-product-card">
                        <div class="rec-product-image-wrapper">
                            <?php if ($is_on_sale) : ?>
                                <span class="rec-sale-badge">SALE</span>
                            <?php endif; ?>

                            <a href="<?php echo get_permalink(); ?>" class="rec-product-image-link">
                                <div class="rec-product-image">
                                    <?php if ($image_url) : ?>
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                                    <?php else : ?>
                                        <img src="<?php echo wc_placeholder_img_src(); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php endif; ?>
                                </div>
                            </a>

                            <div class="rec-overlay-actions">
                                <button
                                    class="rec-wishlist-btn<?php echo $is_in_wishlist ? ' active' : ''; ?>"
                                    data-product-id="<?php echo (int) $product_id; ?>"
                                    data-in-wishlist="<?php echo $is_in_wishlist ? '1' : '0'; ?>"
                                    title="<?php echo $is_in_wishlist ? 'Remove from Wishlist' : 'Add to Wishlist'; ?>">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>

                            <div class="rec-quick-add-wrapper">
                                <button class="rec-quick-add-btn" data-product-id="<?php echo $product_id; ?>" data-product-url="<?php echo esc_url($product->add_to_cart_url()); ?>">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
                                    </svg>
                                    QUICK ADD
                                </button>
                            </div>
                        </div>

                        <div class="rec-product-info">
                            <h3 class="rec-product-title">
                                <a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
                            </h3>

                            <div class="rec-product-price">
                                <?php if ($is_on_sale && $regular_price) : ?>
                                    <span class="rec-price-regular">$<?php echo number_format($regular_price, 2); ?></span>
                                    <span class="rec-price-sale">$<?php echo number_format($sale_price, 2); ?></span>
                                <?php else : ?>
                                    <span class="rec-price-current">$<?php echo number_format($product->get_price(), 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                                class="rec-add-to-cart-normal"
                                data-product-id="<?php echo $product_id; ?>">
                                Add to Cart
                            </a>
                        </div>
                    </div>

                <?php endwhile;
                wp_reset_postdata(); ?>
            </div>
        </div>

        <div class="rec-slider-pagination"></div>
    </div>

    <style>
        * {
            box-sizing: border-box;
        }

        .rec-products-slider-wrapper {
            max-width: 1400px;
            margin: 50px auto;
            padding: 0 24px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
        }

        .rec-slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .rec-slider-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: #1a1a1a;
            letter-spacing: -0.5px;
        }

        .rec-slider-nav {
            display: flex;
            gap: 12px;
        }

        .rec-nav-btn {
            width: 48px;
            height: 48px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: #1a1a1a;
        }

        .rec-nav-btn:hover:not(:disabled) {
            background: #1a1a1a;
            border-color: #1a1a1a;
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .rec-nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .rec-slider-container {
            overflow: hidden;
            position: relative;
            margin-bottom: 32px;
        }

        .rec-slider-track {
            display: flex;
            gap: 24px;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .rec-product-card {
            flex: 0 0 calc((100% - 48px) / 3);
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f0f0f0;
        }

        .rec-product-card:hover {
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            transform: translateY(-8px);
            border-color: #e0e0e0;
        }

        .rec-product-image-wrapper {
            position: relative;
            overflow: hidden;
            background: #f8f8f8;
        }

        .rec-product-image-link {
            display: block;
        }

        .rec-product-image {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }

        .rec-product-image img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .rec-product-card:hover .rec-product-image img {
            transform: scale(1.1);
        }

        .rec-sale-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: linear-gradient(135deg, #ff4444, #ff6b6b);
            color: white;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 20px;
            z-index: 3;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(255, 68, 68, 0.3);
        }

        .rec-overlay-actions {
            position: absolute;
            top: 16px;
            right: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 3;
        }

        .rec-product-card:hover .rec-overlay-actions {
            opacity: 1;
            transform: translateX(0);
        }

        .rec-wishlist-btn {
            width: 44px;
            height: 44px;
            background: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: #1a1a1a;
        }

        .rec-wishlist-btn:hover {
            background: #ff4444;
            color: white;
            transform: scale(1.1);
        }

        .rec-wishlist-btn:hover svg path {
            fill: white;
        }

        .rec-quick-add-wrapper {
            position: absolute;
            bottom: 16px;
            right: 16px;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 3;
        }

        .rec-product-card:hover .rec-quick-add-wrapper {
            opacity: 1;
            transform: scale(1);
        }

        .rec-quick-add-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            background: #ff3b3b;
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 24px rgba(255, 59, 59, 0.4);
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rec-quick-add-btn:hover {
            background: #ff1a1a;
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(255, 59, 59, 0.5);
        }

        .rec-quick-add-btn:active {
            transform: translateY(0);
        }

        .rec-quick-add-btn svg {
            transition: transform 0.3s ease;
        }

        .rec-quick-add-btn:hover svg {
            transform: rotate(90deg);
        }

        .rec-product-info {
            padding: 24px;
        }

        .rec-product-title {
            margin: 0 0 12px;
            font-size: 16px;
            font-weight: 600;
            line-height: 1.4;
            min-height: 44px;
        }

        .rec-product-title a {
            color: #1a1a1a;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.3s ease;
        }

        .rec-product-title a:hover {
            color: #2563eb;
        }

        .rec-product-price {
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 700;
        }

        .rec-price-regular {
            color: #999;
            text-decoration: line-through;
            font-size: 16px;
            font-weight: 500;
        }

        .rec-price-sale {
            color: #ff4444;
            font-size: 20px;
        }

        .rec-price-current {
            color: #1a1a1a;
        }

        .rec-add-to-cart-normal {
            display: block;
            width: 100%;
            padding: 14px 24px;
            background: white;
            color: #1a1a1a;
            text-align: center;
            text-decoration: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: 2px solid #1a1a1a;
        }

        .rec-add-to-cart-normal:hover {
            background: #1a1a1a;
            color: white;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .rec-slider-pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 32px;
        }

        .rec-pagination-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #e0e0e0;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            padding: 0;
        }

        .rec-pagination-dot.active {
            width: 32px;
            border-radius: 5px;
            background: #1a1a1a;
        }

        .rec-pagination-dot:hover:not(.active) {
            background: #bbb;
        }

        .rec-wishlist-btn.active {
            background: #ff4444;
            color: #fff;
        }

        .rec-wishlist-btn.active svg path {
            fill: #fff;
            stroke: #ff4444;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .rec-product-card {
                flex: 0 0 calc((100% - 24px) / 2);
            }

            .rec-slider-title {
                font-size: 28px;
            }
        }

        @media (max-width: 768px) {
            .rec-slider-title {
                font-size: 24px;
            }

            .rec-product-card {
                flex: 0 0 100%;
            }

            .rec-nav-btn {
                width: 40px;
                height: 40px;
            }

            .rec-product-info {
                padding: 20px;
            }
        }
    </style>

    <script>
        (function() {
            const wrapper = document.querySelector('.rec-products-slider-wrapper');
            if (!wrapper) return;

            const track = wrapper.querySelector('.rec-slider-track');
            const prevBtn = wrapper.querySelector('.rec-prev');
            const nextBtn = wrapper.querySelector('.rec-next');
            const pagination = wrapper.querySelector('.rec-slider-pagination');

            let currentIndex = 0;
            let itemsPerView = 3;
            const totalItems = 9;

            function updateItemsPerView() {
                const width = window.innerWidth;
                if (width <= 768) itemsPerView = 1;
                else if (width <= 1024) itemsPerView = 2;
                else itemsPerView = 3;

                createPagination();
                updateSlider();
            }

            function createPagination() {
                const totalPages = Math.ceil(totalItems / itemsPerView);
                pagination.innerHTML = '';

                for (let i = 0; i < totalPages; i++) {
                    const dot = document.createElement('button');
                    dot.classList.add('rec-pagination-dot');
                    if (i === 0) dot.classList.add('active');
                    dot.addEventListener('click', () => goToSlide(i));
                    pagination.appendChild(dot);
                }
            }

            function updateSlider() {
                const cardWidth = track.children[0].offsetWidth;
                const gap = 24;
                const offset = -(currentIndex * itemsPerView * (cardWidth + gap));
                track.style.transform = `translateX(${offset}px)`;

                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = (currentIndex + 1) * itemsPerView >= totalItems;

                const dots = pagination.querySelectorAll('.rec-pagination-dot');
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('active', idx === currentIndex);
                });
            }

            function goToSlide(index) {
                currentIndex = index;
                updateSlider();
            }

            prevBtn.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn.addEventListener('click', () => {
                if ((currentIndex + 1) * itemsPerView < totalItems) {
                    currentIndex++;
                    updateSlider();
                }
            });

            // Quick add functionality - adds to cart
            document.querySelectorAll('.rec-quick-add-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productUrl = this.getAttribute('data-product-url');
                    const productId = this.getAttribute('data-product-id');

                    // Add loading state
                    const originalText = this.innerHTML;
                    this.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-dasharray="60" stroke-dashoffset="60"><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/></circle></svg> ADDING...';
                    this.disabled = true;

                    // Add to cart via AJAX
                    fetch(productUrl, {
                            method: 'GET',
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            this.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg> ADDED!';
                            this.style.background = '#22c55e';

                            // Trigger cart update event
                            jQuery(document.body).trigger('wc_fragment_refresh');

                            setTimeout(() => {
                                this.innerHTML = originalText;
                                this.disabled = false;
                                this.style.background = '';
                            }, 2000);
                        })
                        .catch(error => {
                            console.error('Error adding to cart:', error);
                            this.innerHTML = originalText;
                            this.disabled = false;
                        });
                });
            });

            // Touch support
            let touchStartX = 0;
            let touchEndX = 0;

            track.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, {
                passive: true
            });

            track.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                if (touchStartX - touchEndX > 50) nextBtn.click();
                if (touchEndX - touchStartX > 50) prevBtn.click();
            }, {
                passive: true
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowLeft') prevBtn.click();
                if (e.key === 'ArrowRight') nextBtn.click();
            });

            updateItemsPerView();
            window.addEventListener('resize', updateItemsPerView);

            document.addEventListener('DOMContentLoaded', function() {

                // ==================== WISHLIST BUTTONS ====================
                const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
                const wishlistAddNonce = window.brthemeWishlist.nonces.add;
                const wishlistRemoveNonce = window.brthemeWishlist.nonces.remove;

                function updateWishlistButtonUI(btn, inWishlist) {
                    if (!btn) return;
                    const svgPath = btn.querySelector('svg path');

                    if (inWishlist) {
                        btn.classList.add('active');
                        btn.setAttribute('data-in-wishlist', '1');
                        btn.title = 'Remove from Wishlist';
                        if (svgPath) {
                            svgPath.style.fill = '#fff';
                            svgPath.style.stroke = '#ff4444';
                        }
                    } else {
                        btn.classList.remove('active');
                        btn.setAttribute('data-in-wishlist', '0');
                        btn.title = 'Add to Wishlist';
                        if (svgPath) {
                            svgPath.style.fill = 'none';
                            svgPath.style.stroke = 'currentColor';
                        }
                    }
                }

                wrapper.querySelectorAll('.rec-wishlist-btn').forEach(function(btn) {
                    let inWishlist = btn.getAttribute('data-in-wishlist') === '1';
                    updateWishlistButtonUI(btn, inWishlist);

                    btn.addEventListener('click', function(e) {
                        e.preventDefault();

                        if (btn.classList.contains('rec-wishlist-loading')) {
                            return;
                        }

                        const productId = btn.getAttribute('data-product-id');
                        if (!productId || !wishlistAddNonce || !wishlistRemoveNonce) {
                            console.warn('Wishlist: missing productId or nonces');
                            return;
                        }

                        const action = inWishlist ? 'brtheme_remove_wishlist_item' : 'brtheme_add_to_wishlist';
                        const nonce = inWishlist ? wishlistRemoveNonce : wishlistAddNonce;

                        const params = new URLSearchParams();
                        params.append('action', action);
                        params.append('nonce', nonce);
                        params.append('productId', productId);

                        btn.classList.add('rec-wishlist-loading');

                        fetch(ajaxUrl, {
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
                                console.log('Rec slider wishlist response:', data);

                                if (!data || data.success === false) {
                                    console.warn('Wishlist error', data);
                                    return;
                                }

                                const wasInWishlist = inWishlist;
                                inWishlist = !inWishlist;
                                updateWishlistButtonUI(btn, inWishlist);

                                // Fire events so header badge updates
                                if (window.jQuery) {
                                    const $ = window.jQuery;
                                    if (wasInWishlist) {
                                        $(document).trigger('removed_from_wishlist');
                                    } else {
                                        $(document).trigger('added_to_wishlist');
                                    }
                                }
                            })
                            .catch(function(err) {
                                console.error('Wishlist AJAX failed', err);
                            })
                            .finally(function() {
                                btn.classList.remove('rec-wishlist-loading');
                            });
                    });
                });
            });

        })();
    </script>

<?php
    return ob_get_clean();
}
add_shortcode('custom_slider_rec', 'custom_slider_shortcode_recommended_products');
?>

