<?php
$emojiMap = ['Pizza'=>'🍕','Chicken'=>'🍗','Pasta'=>'🍝','Beverage'=>'🥤','Bundle'=>'🎉','Sides'=>'🍟','Default'=>'🍽️'];
?>

<div class="container-fluid px-3 px-md-4 py-3">

  <!-- Hero Banner Carousel -->
  <div id="heroBanner" class="carousel slide mb-4 rounded-3 overflow-hidden" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php foreach ($activePromos as $i => $p): ?>
      <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
        <div class="hero-banner d-flex align-items-center justify-content-between" style="min-height:220px;">
          <div style="flex:1;">
            <p class="fw-bold mb-1" style="color:var(--sk-gold);font-size:.85rem;letter-spacing:2px;text-transform:uppercase;">
              <?= e($p['Promo_Category']) ?>
            </p>
            <h2 class="display-6 fw-black mb-2" style="font-family:Georgia,serif;color:#fff;">
              <?= e($p['Promo_Code']) ?>
            </h2>
            <p class="text-secondary mb-3" style="font-size:.9rem;"><?= e($p['Promo_Description']) ?></p>
            <div class="d-flex align-items-center gap-3">
              <span style="color:var(--sk-gold);font-size:2rem;font-weight:900;">
                <?= $p['Promo_Discount'] === 'Fixed' ? '₱'.number_format($p['Promo_DiscountValue'],2).' OFF' : $p['Promo_DiscountValue'].'% OFF' ?>
              </span>
              <a href="menu.php?category=Promos" class="btn px-4 py-2 fw-bold" style="background:var(--sk-red);color:#fff;border-radius:8px;">Order Now</a>
            </div>
          </div>
          <div class="d-none d-md-flex align-items-center justify-content-center" style="width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.06);font-size:5rem;">🍕</div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($activePromos)): ?>
      <div class="carousel-item active">
        <div class="hero-banner d-flex align-items-center justify-content-between" style="min-height:220px;">
          <div>
            <p class="fw-bold mb-1" style="color:var(--sk-gold);font-size:.85rem;letter-spacing:2px;">DELIVERY EXCLUSIVE</p>
            <h2 class="display-6 fw-black mb-2" style="font-family:Georgia,serif;color:#fff;">H·B·O</h2>
            <p class="text-secondary mb-3" style="font-size:.9rem;">Home Bonding Offer — 1 Large Pizza + 4pcs Chicken + Garlic Bread + 1.5L Coke</p>
            <div class="d-flex align-items-center gap-3">
              <span style="color:var(--sk-gold);font-size:2rem;font-weight:900;">₱999</span>
              <span class="badge px-3 py-2" style="background:var(--sk-red);font-size:.8rem;">Save ₱689</span>
            </div>
          </div>
          <div class="d-none d-md-flex align-items-center justify-content-center" style="width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,.06);font-size:5rem;">🍕</div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php if (count($activePromos) > 1): ?>
    <div class="carousel-indicators" style="bottom:-30px;">
      <?php for ($i = 0; $i < count($activePromos); $i++): ?>
      <button type="button" data-bs-target="#heroBanner" data-bs-slide-to="<?= $i ?>" <?= $i === 0 ? 'class="active"' : '' ?> style="width:8px;height:8px;border-radius:50%;background:var(--sk-red);"></button>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- Supercard Banner -->
  <div class="supercard-bar mb-4">
    <div>
      <h6 class="fw-bold mb-0" style="color:var(--sk-gold);">Free Pizza. Chicken. <span style="color:var(--sk-red);">Plus</span> More!</h6>
      <small class="text-secondary">Supercard Members enjoy exclusive benefits from Shakey's.</small>
    </div>
    <a href="promos.php" class="btn btn-sm fw-bold" style="border:2px solid #ccc;color:#ccc;background:none;">KNOW MORE</a>
  </div>

  <!-- Recommended -->
  <h5 class="section-title mb-3">Recommended for you</h5>
  <div class="row g-3 mb-4">
    <?php foreach ($recommended as $prod):
      $emoji = $emojiMap[$prod['Prod_Type']] ?? $emojiMap['Default'];
    ?>
    <div class="col-6 col-md-3">
      <div class="food-card">
        <div class="thumb"><?= $emoji ?></div>
        <div class="p-3">
          <h6 class="fw-bold mb-1" style="font-size:.9rem;"><?= e($prod['Prod_Name']) ?></h6>
          <p class="text-muted mb-2" style="font-size:.78rem;"><?= e($prod['Prod_Category']) ?></p>
          <div class="d-flex align-items-center justify-content-between">
            <span class="price">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
              <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
              <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
              <input type="hidden" name="redirect"   value="home.php">
              <button type="submit" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.78rem;">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Pizzas -->
  <h5 class="section-title mb-3">Our Pizzas</h5>
  <div class="row g-3 mb-5">
    <?php foreach ($pizzas as $prod): ?>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="food-card">
        <div class="thumb">🍕</div>
        <div class="p-3">
          <h6 class="fw-bold mb-1" style="font-size:.85rem;"><?= e($prod['Prod_Name']) ?></h6>
          <div class="d-flex align-items-center justify-content-between mt-2">
            <span class="price" style="font-size:.9rem;">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
              <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
              <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
              <input type="hidden" name="redirect"   value="home.php">
              <button type="submit" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.75rem;padding:.25rem .6rem;">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>
