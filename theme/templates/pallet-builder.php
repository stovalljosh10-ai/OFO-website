<?php
/**
 * OFO Pallet Builder Template
 * Used by [ofo_pallet_builder] shortcode
 */
if (!defined('ABSPATH')) exit;
?>
<div id="ofo-pallet-builder">
  <div class="pallet-grid">
    <?php if ($products->have_posts()) : while ($products->have_posts()) : $products->the_post();
      $product = wc_get_product(get_the_ID());
      $price = $product->get_price();
      $regular = $product->get_regular_price();
      $case_pack = $product->get_attribute('case_pack') ?: get_post_meta(get_the_ID(), '_case_pack', true) ?: 1;
      $img = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: wc_placeholder_img_src();
    ?>
    <div class="pallet-item" 
         data-id="<?php echo get_the_ID(); ?>"
         data-price="<?php echo esc_attr($price); ?>"
         data-regular="<?php echo esc_attr($regular ?: $price); ?>"
         data-name="<?php echo esc_attr(get_the_title()); ?>"
         data-case="<?php echo esc_attr($case_pack); ?>">
      <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
      <h4><?php the_title(); ?></h4>
      <div class="pallet-item-price"><?php echo $product->get_price_html(); ?></div>
      <div class="pallet-item-controls">
        <button class="pallet-qty-btn minus" onclick="palletQty(<?php echo get_the_ID(); ?>, -1)">−</button>
        <span class="pallet-qty" id="qty-<?php echo get_the_ID(); ?>">0</span>
        <button class="pallet-qty-btn plus" onclick="palletQty(<?php echo get_the_ID(); ?>, 1)">+</button>
      </div>
    </div>
    <?php endwhile; wp_reset_postdata(); endif; ?>
  </div>

  <div class="pallet-sidebar" id="palletSidebar">
    <h3>🎆 Your Custom Pallet</h3>
    <div id="palletItems"><p class="empty-msg">Add products to get started</p></div>
    <div class="pallet-totals">
      <div class="pallet-total-row">
        <span>Your Price:</span>
        <strong id="palletTotal">$0.00</strong>
      </div>
      <div class="pallet-total-row retail">
        <span>Retail Value:</span>
        <span id="palletRetail">$0.00</span>
      </div>
      <div class="pallet-total-row savings" id="palletSavingsRow" style="display:none">
        <span>🎉 You Save:</span>
        <strong id="palletSavings" style="color:#28a745"></strong>
      </div>
      <div class="pallet-shipping-bar">
        <div class="shipping-bar-label">
          <span>Free Shipping Progress</span>
          <span id="shippingLeft">$1,500 away</span>
        </div>
        <div class="shipping-bar-track">
          <div class="shipping-bar-fill" id="shippingBarFill" style="width:0%"></div>
        </div>
      </div>
    </div>
    <button class="pallet-add-all" id="palletAddAll" onclick="palletAddAllToCart()">
      Add All to Cart
    </button>
  </div>
</div>

<style>
#ofo-pallet-builder { display: flex; gap: 30px; align-items: flex-start; }
.pallet-grid { flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
.pallet-item { background: #fff; border: 1px solid #eee; border-radius: 10px; padding: 15px; text-align: center; transition: box-shadow 0.2s; }
.pallet-item:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
.pallet-item img { width: 100%; height: 150px; object-fit: cover; border-radius: 6px; margin-bottom: 10px; }
.pallet-item h4 { font-size: 0.9em; margin: 8px 0 5px; }
.pallet-item-price { color: #e60000; font-weight: 700; margin-bottom: 10px; }
.pallet-item-controls { display: flex; align-items: center; justify-content: center; gap: 12px; }
.pallet-qty-btn { background: #e60000; color: #fff; border: none; width: 28px; height: 28px; border-radius: 50%; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1; }
.pallet-qty { font-weight: 700; font-size: 1.1em; min-width: 24px; text-align: center; }
.pallet-sidebar { width: 320px; background: #fff; border: 2px solid #e60000; border-radius: 12px; padding: 20px; position: sticky; top: 100px; }
.pallet-sidebar h3 { margin: 0 0 15px; font-size: 1.2em; }
.pallet-total-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
.pallet-total-row.retail span:last-child { text-decoration: line-through; color: #999; }
.pallet-shipping-bar { margin: 15px 0; }
.shipping-bar-label { display: flex; justify-content: space-between; font-size: 0.8em; color: #666; margin-bottom: 6px; }
.shipping-bar-track { background: #eee; border-radius: 10px; height: 8px; }
.shipping-bar-fill { background: #28a745; border-radius: 10px; height: 8px; transition: width 0.3s; }
.pallet-add-all { width: 100%; background: #e60000; color: #fff; border: none; padding: 14px; border-radius: 8px; font-size: 1em; font-weight: 700; cursor: pointer; margin-top: 15px; }
.pallet-add-all:hover { background: #c00; }
.empty-msg { color: #999; font-size: 0.9em; text-align: center; padding: 20px 0; }
#palletItems .pallet-line { display: flex; justify-content: space-between; font-size: 0.85em; padding: 5px 0; }
@media (max-width: 768px) { #ofo-pallet-builder { flex-direction: column; } .pallet-sidebar { width: 100%; position: static; } }
</style>

<script>
var palletState = {};
var FREE_SHIP_THRESHOLD = 1500;

function palletQty(id, delta) {
  if (!palletState[id]) palletState[id] = 0;
  palletState[id] = Math.max(0, palletState[id] + delta);
  document.getElementById('qty-' + id).textContent = palletState[id];
  updatePalletSidebar();
}

function updatePalletSidebar() {
  var total = 0, retail = 0;
  var lines = '';
  var items = document.querySelectorAll('.pallet-item');
  
  items.forEach(function(item) {
    var id = item.dataset.id;
    var qty = palletState[id] || 0;
    if (qty > 0) {
      var price = parseFloat(item.dataset.price);
      var reg = parseFloat(item.dataset.regular);
      var name = item.dataset.name;
      total += price * qty;
      retail += reg * qty;
      lines += '<div class="pallet-line"><span>' + name + ' x' + qty + '</span><span> + (price * qty).toFixed(2) + '</span></div>';
    }
  });

  document.getElementById('palletItems').innerHTML = lines || '<p class="empty-msg">Add products to get started</p>';
  document.getElementById('palletTotal').textContent = ' + total.toFixed(2);
  document.getElementById('palletRetail').textContent = ' + retail.toFixed(2);
  
  var savings = retail - total;
  if (savings > 0) {
    document.getElementById('palletSavingsRow').style.display = 'flex';
    document.getElementById('palletSavings').textContent = ' + savings.toFixed(2) + ' (' + Math.round((savings/retail)*100) + '% off)';
  } else {
    document.getElementById('palletSavingsRow').style.display = 'none';
  }

  var pct = Math.min(100, (total / FREE_SHIP_THRESHOLD) * 100);
  document.getElementById('shippingBarFill').style.width = pct + '%';
  var left = FREE_SHIP_THRESHOLD - total;
  document.getElementById('shippingLeft').textContent = left > 0 ? ' + left.toFixed(2) + ' away' : '🎉 Free Shipping Unlocked!';
}

function palletAddAllToCart() {
  var items = document.querySelectorAll('.pallet-item');
  var promises = [];
  items.forEach(function(item) {
    var id = item.dataset.id;
    var qty = palletState[id] || 0;
    if (qty > 0) {
      promises.push(fetch('/?add-to-cart=' + id + '&quantity=' + qty, {credentials: 'same-origin'}));
    }
  });
  if (promises.length === 0) { alert('Add some products first!'); return; }
  Promise.all(promises).then(function() {
    window.location.href = '/cart/';
  });
}
</script>
