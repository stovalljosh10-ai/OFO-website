/**
 * OFO Custom Scripts
 * Served via Vercel CDN
 * orderfireworksonline.com
 */

(function() {
  'use strict';

  // ============================================================
  // SHIPPING THRESHOLD PROGRESS BAR
  // Shows progress toward free shipping in cart
  // ============================================================
  function initShippingBar() {
    var cartTotal = document.querySelector('.cart-subtotal .woocommerce-Price-amount');
    if (!cartTotal) return;

    var total = parseFloat(cartTotal.textContent.replace(/[^0-9.]/g, ''));
    var threshold = 1500;
    var pct = Math.min(100, (total / threshold) * 100);
    var remaining = threshold - total;

    var bar = document.getElementById('ofo-shipping-bar');
    if (!bar) {
      bar = document.createElement('div');
      bar.id = 'ofo-shipping-bar';
      bar.style.cssText = 'background:#f5f5f5;border-radius:10px;padding:12px 16px;margin:10px 0;font-size:0.9em;';
      var cartTotals = document.querySelector('.cart_totals');
      if (cartTotals) cartTotals.prepend(bar);
    }

    if (remaining <= 0) {
      bar.innerHTML = '<strong style="color:#28a745">\ud83c\udf89 Free Shipping Unlocked!</strong>';
    } else {
      bar.innerHTML = '<div style="margin-bottom:6px">Add <strong>$' + remaining.toFixed(2) + '</strong> more for FREE shipping</div>' +
        '<div style="background:#ddd;border-radius:10px;height:8px">' +
        '<div style="background:#28a745;border-radius:10px;height:8px;width:' + pct + '%;transition:width 0.3s"></div></div>';
    }
  }

  // ============================================================
  // PRODUCT PAGE ENHANCEMENTS
  // ============================================================
  function initProductPage() {
    // Add urgency badge to in-stock products
    var stockBadge = document.querySelector('.in-stock');
    if (stockBadge) {
      stockBadge.style.cssText = 'color:#28a745;font-weight:700;';
    }

    // Highlight per-unit price
    var perUnit = document.querySelector('.ofo-per-unit-price');
    if (perUnit) {
      perUnit.style.cssText = 'display:block;font-size:0.85em;color:#666;margin-top:4px;';
    }
  }

  // ============================================================
  // ANNOUNCEMENT BAR CLICK TRACKING
  // ============================================================
  function initBannerTracking() {
    var banner = document.querySelector('.ep-banner-slider');
    if (banner) {
      banner.style.cursor = 'pointer';
      banner.addEventListener('click', function() {
        window.location.href = '/product-category/pallet-packs/';
      });
    }
  }

  // ============================================================
  // INIT
  // ============================================================
  function init() {
    initShippingBar();
    initProductPage();
    initBannerTracking();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // Re-run on WooCommerce cart updates
  if (window.jQuery) {
    jQuery(document.body).on('updated_cart_totals wc_fragments_refreshed', function() {
      setTimeout(initShippingBar, 300);
    });
  }

})();
