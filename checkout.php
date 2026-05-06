<?php
// checkout.php
require_once 'includes/auth_check.php';
require_once 'db.php';
$pageTitle = "Checkout — Shakey's Delivery";

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) { header('Location: cart.php'); exit; }

$custId  = $_SESSION['cust_id'];
$custStmt = $pdo->prepare("SELECT * FROM Customer WHERE Cust_ID=?");
$custStmt->execute([$custId]);
$cust = $custStmt->fetch();

// Fetch branches
$branches = $pdo->query("SELECT * FROM Branch ORDER BY Brnch_Name")->fetchAll();

// Calculate totals
$subtotal = array_sum(array_map(fn($i)=>$i['price']*$i['qty'], $cart));
$today = date('Y-m-d');

// Handle promo code check (AJAX-like via GET)
$promoData = null; $promoError = '';
if (!empty($_POST['promo_code'])) {
    $pStmt = $pdo->prepare("SELECT * FROM Promotion WHERE Promo_Code=? AND Promo_ValidFrom<=? AND Promo_ValidTo>=?");
    $pStmt->execute([$_POST['promo_code'], $today, $today]);
    $promoData = $pStmt->fetch();
    if (!$promoData) $promoError = 'Invalid or expired promo code.';
}

$discount = 0;
if ($promoData) {
    $discount = $promoData['Promo_Discount']==='Fixed'
        ? $promoData['Promo_DiscountValue']
        : round($subtotal * $promoData['Promo_DiscountValue'] / 100, 2);
}
$deliveryFee = 60;
$total = max(0, $subtotal - $discount) + $deliveryFee;

include 'includes/header.php';
?>

<div class="container-fluid px-3 px-md-4 py-4">
  <h5 class="section-title mb-4">Checkout</h5>

  <form method="POST" action="place_order.php">
    <div class="row g-4">

      <!-- Left: Delivery + Payment -->
      <div class="col-lg-8">

        <!-- Delivery info -->
        <div class="bg-white rounded-4 p-4 shadow-sm mb-3">
          <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2" style="color:var(--sk-red);"></i>Delivery Details</h6>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Delivery Address</label>
            <input type="text" name="delivery_address" class="form-control"
                   value="<?= htmlspecialchars($cust['Cust_Address']) ?>" required>
          </div>
          <div class="mb-0">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Branch</label>
            <select name="branch_id" class="form-select" required>
              <option value="">— Select nearest branch —</option>
              <?php foreach($branches as $b): ?>
              <option value="<?= $b['Brnch_ID'] ?>"><?= htmlspecialchars($b['Brnch_Name']) ?> (<?= $b['Brnch_Location'] ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Pizza customization -->
        <?php
        $hasPizza = false;
        foreach($cart as $item) if(stripos($item['name'],'pizza')!==false||stripos($item['name'],'pepperoni')!==false||stripos($item['name'],'hawaiian')!==false||stripos($item['name'],"manager's")!==false) $hasPizza=true;
        if($hasPizza): ?>
        <div class="bg-white rounded-4 p-4 shadow-sm mb-3">
          <h6 class="fw-bold mb-3"><i class="bi bi-sliders me-2" style="color:var(--sk-red);"></i>Pizza Options</h6>
          <div class="mb-2">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Crust Type</label>
            <div class="d-flex gap-3">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="crust_type" value="Thin Crust" id="thin" checked>
                <label class="form-check-label" for="thin">Thin Crust</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="crust_type" value="Hand Tossed" id="hand">
                <label class="form-check-label" for="hand">Hand Tossed</label>
              </div>
            </div>
          </div>
          <div class="mb-0">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Special Instructions</label>
            <textarea name="instructions" class="form-control" rows="2" placeholder="Extra cheese, less salt, etc."></textarea>
          </div>
        </div>
        <?php endif; ?>

        <!-- Promo code -->
        <div class="bg-white rounded-4 p-4 shadow-sm mb-3">
          <h6 class="fw-bold mb-3"><i class="bi bi-tag me-2" style="color:var(--sk-red);"></i>Promo Code</h6>
          <?php if($promoData): ?>
          <div class="alert alert-success py-2 d-flex align-items-center justify-content-between mb-2">
            <span style="font-size:.85rem;"><strong><?= htmlspecialchars($promoData['Promo_Code']) ?></strong> — <?= htmlspecialchars($promoData['Promo_Description']) ?></span>
            <span class="fw-bold" style="color:var(--sk-red);">-₱<?= number_format($discount,2) ?></span>
          </div>
          <input type="hidden" name="promo_id" value="<?= $promoData['Promo_ID'] ?>">
          <input type="hidden" name="discount" value="<?= $discount ?>">
          <?php endif; ?>
          <?php if($promoError): ?>
          <div class="alert alert-danger py-2 mb-2" style="font-size:.85rem;"><?= htmlspecialchars($promoError) ?></div>
          <?php endif; ?>
          <div class="input-group">
            <input type="text" name="promo_code" class="form-control" placeholder="Enter promo code"
                   value="<?= htmlspecialchars($_POST['promo_code']??'') ?>"
                   style="font-family:monospace;letter-spacing:1px;text-transform:uppercase;">
            <button type="submit" name="apply_promo" class="btn fw-bold" style="background:var(--sk-red);color:#fff;">Apply</button>
          </div>
        </div>

        <!-- Payment method -->
        <div class="bg-white rounded-4 p-4 shadow-sm">
          <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2" style="color:var(--sk-red);"></i>Payment Method</h6>
          <div class="row g-2">
            <?php
            $methods = [
              ['cod',    'bi-cash-coin',     'Cash on Delivery',  'Pay when your order arrives'],
              ['card',   'bi-credit-card-2-front','Credit/Debit Card','Visa, Mastercard'],
              ['online', 'bi-phone',          'Online Payment',    'GCash, Maya, PayMaya'],
            ];
            foreach($methods as [$val,$icon,$label,$sub]): ?>
            <div class="col-12 col-md-4">
              <label class="d-block" style="cursor:pointer;">
                <input type="radio" name="pay_method" value="<?= $val ?>" class="visually-hidden payment-radio" <?= $val==='cod'?'checked':'' ?>>
                <div class="border rounded-3 p-3 payment-option <?= $val==='cod'?'border-danger':'' ?>"
                     style="transition:all .2s;">
                  <i class="bi <?= $icon ?> fs-4 mb-1 d-block" style="color:var(--sk-red);"></i>
                  <div class="fw-bold" style="font-size:.85rem;"><?= $label ?></div>
                  <div class="text-muted" style="font-size:.75rem;"><?= $sub ?></div>
                </div>
              </label>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>

      <!-- Right: Order summary -->
      <div class="col-lg-4">
        <div class="bg-white rounded-4 p-4 shadow-sm sticky-top" style="top:80px;">
          <h6 class="fw-bold mb-3">Order Summary</h6>

          <?php foreach($cart as $item): ?>
          <div class="d-flex justify-content-between align-items-center mb-2" style="font-size:.85rem;">
            <span class="text-truncate me-2">
              <span class="text-muted me-1">×<?= $item['qty'] ?></span>
              <?= htmlspecialchars($item['name']) ?>
            </span>
            <span class="fw-semibold flex-shrink-0">₱<?= number_format($item['price']*$item['qty'],2) ?></span>
          </div>
          <?php endforeach; ?>

          <hr>
          <div class="d-flex justify-content-between mb-1" style="font-size:.88rem;">
            <span class="text-muted">Subtotal</span>
            <span>₱<?= number_format($subtotal,2) ?></span>
          </div>
          <?php if($discount>0): ?>
          <div class="d-flex justify-content-between mb-1" style="font-size:.88rem;">
            <span class="text-muted">Promo Discount</span>
            <span class="text-success">-₱<?= number_format($discount,2) ?></span>
          </div>
          <?php endif; ?>
          <div class="d-flex justify-content-between mb-2" style="font-size:.88rem;">
            <span class="text-muted">Delivery Fee</span>
            <span>₱<?= number_format($deliveryFee,2) ?></span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-4">
            <span class="fw-bold">Total</span>
            <span class="fw-bold fs-5" style="color:var(--sk-red);">₱<?= number_format($total,2) ?></span>
          </div>

          <input type="hidden" name="subtotal"     value="<?= $subtotal ?>">
          <input type="hidden" name="delivery_fee" value="<?= $deliveryFee ?>">
          <input type="hidden" name="total"        value="<?= $total ?>">

          <?php if(!isset($_POST['apply_promo'])): ?>
          <button type="submit" name="place_order" class="btn fw-bold w-100 py-2"
                  style="background:var(--sk-red);color:#fff;border-radius:8px;">
            <i class="bi bi-bag-check me-1"></i> Place Order
          </button>
          <?php endif; ?>
          <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2">← Back to Cart</a>
        </div>
      </div>
    </div>
  </form>
</div>

<?php include 'includes/footer.php'; ?>
<script>
document.querySelectorAll('.payment-radio').forEach(r => {
    r.addEventListener('change', function() {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('border-danger'));
        if(this.checked) this.closest('label').querySelector('.payment-option').classList.add('border-danger');
    });
});
</script>
