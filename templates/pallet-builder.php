<?php if (!defined('ABSPATH')) exit; ?>
<div id="ofo-pallet-builder" class="ofo-pallet-builder">
  <div class="opb-header">
    <div class="opb-header-text">
      <h1 class="opb-title">Build Your Custom Pallet</h1>
      <p class="opb-subtitle">Add products below — watch your savings grow in real time</p>
    </div>
    <div class="opb-header-badges">
      <span class="opb-badge opb-badge--fire">🔥 Wholesale Pricing</span>
      <span class="opb-badge opb-badge--ship">🚚 Free Ship at $1,500</span>
    </div>
  </div>
  <div class="opb-layout">
    <div class="opb-products-section">
      <div class="opb-filters">
        <button class="opb-filter-btn active" data-category="all">All Products</button>
        <button class="opb-filter-btn" data-category="500g-cakes">500g Cakes</button>
        <button class="opb-filter-btn" data-category="200g-cakes">200g Cakes</button>
        <button class="opb-filter-btn" data-category="artillery">Artillery</button>
        <button class="opb-filter-btn" data-category="ground-fireworks">Ground</button>
        <button class="opb-filter-btn" data-category="sparklers">Sparklers</button>
      </div>
      <div class="opb-search-wrap">
        <input type="text" id="opb-search" class="opb-search" placeholder="🔍 Search products...">
      </div>
      <div id="opb-loading" class="opb-loading"><div class="opb-spinner"></div><p>Loading products...</p></div>
      <div id="opb-product-grid" class="opb-product-grid" style="display:none;"></div>
      <div id="opb-empty" class="opb-empty" style="display:none;"><p>No products found.</p></div>
    </div>
    <div class="opb-sidebar">
      <div class="opb-sidebar-inner">
        <h2 class="opb-sidebar-title">Your Pallet</h2>
        <div class="opb-stats-grid">
          <div class="opb-stat"><span class="opb-stat-value" id="opb-stat-items">0</span><span class="opb-stat-label">Items</span></div>
          <div class="opb-stat"><span class="opb-stat-value" id="opb-stat-pieces">0</span><span class="opb-stat-label">Pieces</span></div>
          <div class="opb-stat"><span class="opb-stat-value" id="opb-stat-shots">0</span><span class="opb-stat-label">Shots</span></div>
        </div>
        <div class="opb-shipping-bar">
          <div class="opb-shipping-bar-header">
            <span id="opb-ship-status">Add $1,500 for FREE shipping</span>
            <span id="opb-ship-amount">$0 / $1,500</span>
          </div>
          <div class="opb-progress-track"><div class="opb-progress-fill" id="opb-progress-fill" style="width:0%"></div></div>
        </div>
        <div class="opb-pricing">
          <div class="opb-price-row"><span class="opb-price-label">Your Price</span><span class="opb-price-value opb-your-price" id="opb-your-price">$0.00</span></div>
          <div class="opb-price-row">
            <span class="opb-price-label">Competitor Price (per unit avg $67)</span>
            <span class="opb-price-value opb-retail-price" id="opb-retail-price">$0.00</span>
          </div>
          <div class="opb-price-row opb-competitors-note">
            <span style="font-size:0.72em;color:#888;">vs OC Fireworks, Red Apple, American Wholesale &amp; Superior avg</span>
          </div>
          <div class="opb-savings-row"><span class="opb-savings-label">🎉 You Save</span><span class="opb-savings-amount" id="opb-savings-amount">$0.00</span><span class="opb-savings-pct" id="opb-savings-pct"></span></div>
        </div>
        <div class="opb-selected-items" id="opb-selected-items">
          <p class="opb-empty-cart">No items added yet.<br>Pick products from the left →</p>
        </div>
        <div class="opb-cta-wrap">
          <button id="opb-add-to-cart-btn" class="opb-cta-btn opb-cta-btn--primary" disabled><span class="opb-cta-icon">🛒</span> Add Pallet to Cart</button>
          <button id="opb-clear-btn" class="opb-cta-btn opb-cta-btn--secondary">Clear All</button>
        </div>
        <div class="opb-trust">
          <span>✅ Wholesale pricing</span>
          <span>✅ No minimum order</span>
          <span>✅ Ships nationwide</span>
        </div>
      </div>
    </div>
  </div>
  <div id="opb-toast" class="opb-toast" aria-live="polite"></div>
</div>
