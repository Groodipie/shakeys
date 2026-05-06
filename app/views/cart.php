<div class="container-fluid px-3 px-md-4 py-4">
  <h5 class="section-title mb-4">Your Cart</h5>

  <?php if (empty($cart)): ?>
  <div class="bg-white rounded-4 p-5 text-center shadow-sm" style="max-width:480px;margin:0 auto;">
    <div style="font-size:4rem;" class="mb-3">🛒</div>
    <h5 class="fw-bold">Your cart is empty</h5>
    <p class="text-muted mb-4" style="font-size:.9rem;">Add delicious items from our menu!</p>
    <a href="menu.php" class="btn fw-bold px-4 py-2" style="background:var(--sk-red);color:#fff;border-radius:8px;">Browse Menu</a>
  </div>

  <?php else: ?>
  <div class="row g-4">
    <div class="col-lg-8">
      <form method="POST">
        <div class="bg-white rounded-4 overflow-hidden shadow-sm">
          <?php foreach ($cart as $key => $item):
            $itemTotal = $item['price'] * $item['qty'];
          ?>
          <div class="d-flex align-items-center gap-3 p-3 border-bottom">
            <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                 style="width:64px;height:64px;background:#fdf0f0;font-size:2.2rem;">🍕</div>
            <div class="flex-grow-1 min-w-0">
              <h6 class="fw-bold mb-0 text-truncate" style="font-size:.9rem;"><?= e($item['name']) ?></h6>
              <small class="text-muted">₱<?= number_format($item['price'],2) ?> each</small>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-sm border-0 rounded-circle d-flex align-items-center justify-content-center"
                      style="width:28px;height:28px;background:#f5f5f5;font-size:1rem;"
                      onclick="changeQty('<?= $key ?>',this,-1)">−</button>
              <input type="number" name="qty[<?= $key ?>]" id="qty_<?= $key ?>"
                     value="<?= $item['qty'] ?>" min="1" max="99"
                     class="form-control form-control-sm text-center fw-bold border-0"
                     style="width:40px;background:transparent;">
              <button type="button" class="btn btn-sm border-0 rounded-circle d-flex align-items-center justify-content-center"
                      style="width:28px;height:28px;background:#f5f5f5;font-size:1rem;"
                      onclick="changeQty('<?= $key ?>',this,1)">+</button>
            </div>
            <div class="text-end" style="min-width:70px;">
              <div class="fw-bold" style="color:var(--sk-red);font-size:.9rem;">₱<?= number_format($itemTotal,2) ?></div>
              <a href="cart.php?remove=<?= urlencode($key) ?>" class="text-danger" style="font-size:.75rem;text-decoration:none;">
                <i class="bi bi-x-circle"></i> Remove
              </a>
            </div>
          </div>
          <?php endforeach; ?>

          <div class="p-3 d-flex gap-2 justify-content-between">
            <a href="menu.php" class="btn btn-sm" style="border:1px solid var(--sk-red);color:var(--sk-red);">
              <i class="bi bi-plus-circle me-1"></i>Add More Items
            </a>
            <button type="submit" name="update_cart" class="btn btn-sm btn-secondary">
              <i class="bi bi-arrow-clockwise me-1"></i>Update Cart
            </button>
          </div>
        </div>
      </form>
    </div>

    <div class="col-lg-4">
      <div class="bg-white rounded-4 p-4 shadow-sm">
        <h6 class="fw-bold mb-3">Order Summary</h6>
        <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
          <span class="text-muted">Subtotal (<?= array_sum(array_column($cart,'qty')) ?> items)</span>
          <span class="fw-semibold">₱<?= number_format($subtotal,2) ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2" style="font-size:.9rem;">
          <span class="text-muted">Delivery fee</span>
          <span class="fw-semibold">₱<?= number_format($deliveryFee,2) ?></span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
          <span class="fw-bold">Total</span>
          <span class="fw-bold fs-5" style="color:var(--sk-red);">₱<?= number_format($total,2) ?></span>
        </div>
        <a href="checkout.php" class="btn fw-bold w-100 py-2" style="background:var(--sk-red);color:#fff;border-radius:8px;">
          Proceed to Checkout <i class="bi bi-arrow-right ms-1"></i>
        </a>
        <a href="menu.php" class="btn btn-outline-secondary w-100 mt-2 py-2">Continue Shopping</a>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>
function changeQty(key, btn, delta) {
    const input = document.getElementById('qty_' + key);
    let v = parseInt(input.value) + delta;
    if (v < 1) v = 1;
    input.value = v;
}
</script>
