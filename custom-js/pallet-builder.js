(function($){'use strict';
var state={products:[],filtered:[],pallet:{},activeCategory:'all',searchTerm:'',FREE_SHIP_THRESHOLD:1500};

function init(){fetchProducts();bindEvents();}

function fetchProducts(){
  $('#opb-loading').show();
  $('#opb-product-grid').hide();
  $.ajax({
    url:ofo_pallet_data.ajax_url,
    method:'POST',
    data:{action:'ofo_get_pallet_products',nonce:ofo_pallet_data.nonce},
    success:function(res){
      $('#opb-loading').hide();
      if(res.success && res.data && res.data.length){
        state.products=res.data;
        applyFilters();
        renderGrid();
      } else {
        $('#opb-empty').show();
      }
    },
    error:function(){
      $('#opb-loading').hide();
      $('#opb-empty').text('Failed to load products. Please refresh.').show();
    }
  });
}

function applyFilters(){
  var list=state.products;
  if(state.activeCategory!=='all'){
    list=list.filter(function(p){return p.categories && p.categories.indexOf(state.activeCategory)!==-1;});
  }
  if(state.searchTerm.trim()){
    var term=state.searchTerm.toLowerCase();
    list=list.filter(function(p){return p.name.toLowerCase().indexOf(term)!==-1;});
  }
  state.filtered=list;
}

function renderGrid(){
  var $grid=$('#opb-product-grid');
  $grid.empty();
  if(!state.filtered.length){$('#opb-empty').show();$grid.hide();return;}
  $('#opb-empty').hide();
  $grid.show();
  for(var i=0;i<state.filtered.length;i++){
    var product=state.filtered[i];
    var pid=String(product.id);
    var qty=state.pallet[pid]||0;
    var caseSize=parseInt(product.case_pack)||1;
    var price=parseFloat(product.price)||0;
    var regularPrice=parseFloat(product.regular_price)||price;
    var perUnit=caseSize>1?(price/caseSize).toFixed(2):null;
    var shots=parseInt(product.shot_count)||0;
    var imgHtml=product.image?'<img class="opb-product-img" src="'+esc(product.image)+'" alt="'+esc(product.name)+'" loading="lazy">':'<div class="opb-product-img-placeholder">🎆</div>';
    var shotTag=shots>0?'<span class="opb-meta-tag opb-meta-tag--shots">💥 '+shots+' shots</span>':'';
    var piecesTag=caseSize>1?'<span class="opb-meta-tag opb-meta-tag--pieces">📦 '+caseSize+'/case</span>':'';
    var perUnitHtml=perUnit?'<span class="opb-product-per-unit">≈ $'+perUnit+' per unit</span>':'';
    var catBadge=product.category_label?'<span class="opb-product-category-badge">'+esc(product.category_label)+'</span>':'';
    var html='<div class="opb-product-card'+(qty>0?' in-pallet':'')+'" data-pid="'+pid+'">'
      +'<div class="opb-product-img-wrap">'+imgHtml+catBadge+'</div>'
      +'<div class="opb-product-body">'
      +'<p class="opb-product-name">'+esc(product.name)+'</p>'
      +'<div class="opb-product-meta">'+shotTag+piecesTag+'</div>'
      +'<div class="opb-product-pricing"><span class="opb-product-price">$'+price.toFixed(2)+'</span>'+(regularPrice>price?'<span class="opb-product-retail">$'+regularPrice.toFixed(2)+'</span>':'')+''+perUnitHtml+'</div>'
      +'<div class="opb-qty-controls">'
      +'<button class="opb-qty-btn opb-qty-minus" data-pid="'+pid+'">−</button>'
      +'<input class="opb-qty-input" type="number" min="0" value="'+qty+'" data-pid="'+pid+'" readonly>'
      +'<button class="opb-qty-btn opb-qty-plus" data-pid="'+pid+'">+</button>'
      +'</div></div></div>';
    $grid.append(html);
  }
}

function setQty(id,qty){
  id=String(id);
  qty=Math.max(0,parseInt(qty)||0);
  if(qty===0){delete state.pallet[id];}else{state.pallet[id]=qty;}
  $('.opb-qty-input[data-pid="'+id+'"]').val(qty);
  $('.opb-product-card[data-pid="'+id+'"]').toggleClass('in-pallet',qty>0);
  updateSidebar();
}

function updateSidebar(){
  var ids=Object.keys(state.pallet);
  var ti=0,tp=0,ts=0,yt=0,rt=0;
  for(var i=0;i<ids.length;i++){
    var id=ids[i];
    var p=findProduct(id);
    if(!p)continue;
    var q=state.pallet[id];
    var pr=parseFloat(p.price)||0;
    var rp=parseFloat(p.regular_price)||pr;
    var cs=parseInt(p.case_pack)||1;
    var sh=parseInt(p.shot_count)||0;
    ti+=q;
    tp+=q*cs;
    ts+=q*cs*sh;
    yt+=q*pr;
    rt+=q*rp;
  }
  var sv=rt-yt;
  var pct=rt>0?Math.round((sv/rt)*100):0;

  $('#opb-stat-items').text(ti);
  $('#opb-stat-pieces').text(tp.toLocaleString());
  $('#opb-stat-shots').text(ts.toLocaleString());
  $('#opb-your-price').text('$'+yt.toFixed(2));
  $('#opb-retail-price').text('$'+rt.toFixed(2));
  $('#opb-savings-amount').text('$'+sv.toFixed(2));
  $('#opb-savings-pct').text(pct>0?pct+'% off':'');

  var bar=Math.min(100,(yt/state.FREE_SHIP_THRESHOLD)*100);
  $('#opb-progress-fill').css('width',bar+'%').toggleClass('complete',bar>=100);
  if(bar>=100){
    $('#opb-ship-status').text('🎉 FREE Shipping Unlocked!');
    $('#opb-ship-amount').text('');
  } else {
    $('#opb-ship-status').text('Add $'+(state.FREE_SHIP_THRESHOLD-yt).toFixed(2)+' for FREE shipping');
    $('#opb-ship-amount').text('$'+yt.toFixed(2)+' / $'+state.FREE_SHIP_THRESHOLD);
  }
  renderSelectedItems(ids);
  $('#opb-add-to-cart-btn').prop('disabled',ids.length===0);
}

function findProduct(id){
  id=String(id);
  for(var i=0;i<state.products.length;i++){
    if(String(state.products[i].id)===id) return state.products[i];
  }
  return null;
}

function renderSelectedItems(ids){
  var $l=$('#opb-selected-items');
  $l.empty();
  if(!ids.length){
    $l.append('<p class="opb-empty-cart">No items added yet.<br>Pick products from the left →</p>');
    return;
  }
  for(var i=0;i<ids.length;i++){
    var id=ids[i];
    var p=findProduct(id);
    if(!p)continue;
    var q=state.pallet[id];
    var lt=(q*(parseFloat(p.price)||0)).toFixed(2);
    $l.append('<div class="opb-selected-item" data-pid="'+id+'">'
      +'<div class="opb-selected-item-name">'+esc(p.name)+'</div>'
      +'<span class="opb-selected-item-qty">×'+q+'</span>'
      +'<span class="opb-selected-item-price">$'+lt+'</span>'
      +'<button class="opb-selected-item-remove" data-pid="'+id+'" title="Remove">×</button>'
      +'</div>');
  }
}

function addPalletToCart(){
  var $b=$('#opb-add-to-cart-btn');
  var ids=Object.keys(state.pallet);
  if(!ids.length)return;
  $b.prop('disabled',true).html('<span>⏳</span> Adding to Cart...');
  var items=[];
  for(var i=0;i<ids.length;i++){
    items.push({product_id:ids[i],quantity:state.pallet[ids[i]]});
  }
  $.ajax({
    url:ofo_pallet_data.ajax_url,
    method:'POST',
    data:{action:'ofo_add_pallet_to_cart',nonce:ofo_pallet_data.nonce,items:JSON.stringify(items)},
    success:function(res){
      if(res.success){
        showToast('🎉 Pallet added! Redirecting...');
        setTimeout(function(){window.location.href=ofo_pallet_data.cart_url;},1500);
      } else {
        showToast('❌ '+(res.data||'Error'),true);
        $b.prop('disabled',false).html('<span>🛒</span> Add Pallet to Cart');
      }
    },
    error:function(){
      showToast('❌ Something went wrong.',true);
      $b.prop('disabled',false).html('<span>🛒</span> Add Pallet to Cart');
    }
  });
}

function showToast(msg,isErr){
  var $t=$('#opb-toast');
  $t.text(msg).css('background',isErr?'#dc3545':'').addClass('show');
  setTimeout(function(){$t.removeClass('show');},3000);
}

function bindEvents(){
  $(document).on('click','.opb-filter-btn',function(){
    $('.opb-filter-btn').removeClass('active');
    $(this).addClass('active');
    state.activeCategory=$(this).attr('data-category');
    applyFilters();
    renderGrid();
  });
  $('#opb-search').on('input',debounce(function(){
    state.searchTerm=$(this).val();
    applyFilters();
    renderGrid();
  },300));
  $(document).on('click','.opb-qty-plus',function(){
    var id=$(this).attr('data-pid');
    setQty(id,(state.pallet[id]||0)+1);
  });
  $(document).on('click','.opb-qty-minus',function(){
    var id=$(this).attr('data-pid');
    setQty(id,(state.pallet[id]||0)-1);
  });
  $(document).on('click','.opb-selected-item-remove',function(){
    setQty($(this).attr('data-pid'),0);
  });
  $('#opb-add-to-cart-btn').on('click',addPalletToCart);
  $('#opb-clear-btn').on('click',function(){
    state.pallet={};
    applyFilters();
    renderGrid();
    updateSidebar();
  });
}

function esc(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
function debounce(fn,d){var t;return function(){var a=arguments,c=this;clearTimeout(t);t=setTimeout(function(){fn.apply(c,a);},d);};}

$(document).ready(init);
})(jQuery);
