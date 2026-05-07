<?php
$emojiMap = ['Pizza'=>'🍕','Chicken'=>'🍗','Pasta'=>'🍝','Beverage'=>'🥤','Bundle'=>'🎉','Sides'=>'🍟','Drinks'=>'🥤','Desserts'=>'🍰','Salad'=>'🥗','Combos'=>'🥡','Default'=>'🍽️'];
$catImageMap = [
  'Pizza'           => 'pizza.png',
  "Chicken 'N Mojos"=> 'chicken.png',
  'Sides'           => 'sides.png',
  'Pasta'           => 'pasta.png',
  'Group Meals'     => 'group-meals.png',
  'Promos'          => 'promos.png',
  'Drinks'          => 'drinks.png',
  'Combos'          => 'combos.png',
  'Desserts'        => 'desserts.png',
  'Soup & Salad'    => 'salad.png',
  'Hero Sandwiches' => 'sandwich.png',
  'Starters'        => 'sides.png',
  'Extras'          => 'extras.png',
  'Supercard Exclusives' => 'supercard.png',
];
?>

<div class="container-fluid px-3 px-md-4 py-3" style="max-width:1100px;margin:0 auto;">

  <a href="/account" class="supercard-cta mb-4">
    <div class="supercard-cta-art supercard-cta-art-left"></div>
    <div class="supercard-cta-art supercard-cta-art-right"></div>
    <div class="supercard-cta-body">
      <h3 class="supercard-cta-title">Free Pizza. Chicken. <span class="hl">Plus</span> More!</h3>
      <p class="supercard-cta-sub">Supercard Members enjoy these benefits from Shakey's and Peri-Peri Charcoal Chicken and Sauce Bar.</p>
    </div>
    <span class="supercard-cta-btn">KNOW MORE</span>
  </a>

  <form method="GET" class="mb-4">
    <div class="input-group" style="max-width:400px;">
      <input type="text" name="search" class="form-control" placeholder="Search menu items..." value="<?= e($search) ?>">
      <?php if ($category): ?><input type="hidden" name="category" value="<?= e($category) ?>"><?php endif; ?>
      <button class="btn" style="background:var(--sk-red);color:#fff;" type="submit"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="row g-3 g-md-4 mb-5">
    <div class="col-6 col-md-4 col-lg-3">
      <a href="/menu" class="cat-tile <?= !$category ? 'is-active' : '' ?>">
        <div class="cat-tile-art">
          <img src="/assets/img/categories/all.png" alt="All Items" class="cat-tile-img">
        </div>
        <div class="cat-tile-label">All Items</div>
      </a>
    </div>
    <?php foreach ($categories as $cat):
      $catName = $cat['Prod_Category'];
      $img = $catImageMap[$catName] ?? null;
      $emoji = $emojiMap[$catName] ?? $emojiMap['Default'];
      $active = $category === $catName;
    ?>
    <div class="col-6 col-md-4 col-lg-3">
      <a href="/menu?category=<?= urlencode($catName) ?>" class="cat-tile <?= $active ? 'is-active' : '' ?>">
        <div class="cat-tile-art">
          <?php if ($img): ?>
            <img src="/assets/img/categories/<?= $img ?>" alt="<?= e($catName) ?>" class="cat-tile-img">
          <?php else: ?>
            <span class="cat-tile-emoji"><?= $emoji ?></span>
          <?php endif; ?>
        </div>
        <div class="cat-tile-label"><?= e($catName) ?></div>
      </a>
    </div>
    <?php endforeach; ?>
  </div>

  <h5 class="section-title mb-3">
    <?= $category ? e($category) : ($search ? 'Results for "' . e($search) . '"' : 'All Menu Items') ?>
    <span class="text-muted fw-normal" style="font-size:.9rem;">(<?= count($products) ?> items)</span>
  </h5>

  <?php if (empty($products)): ?>
  <div class="text-center py-5">
    <div style="font-size:4rem;">🍕</div>
    <h5 class="mt-3 text-muted">No items found</h5>
    <a href="/menu" class="btn mt-2" style="background:var(--sk-red);color:#fff;">View All Items</a>
  </div>
  <?php else: ?>
  <div class="row g-3 mb-5">
    <?php foreach ($products as $prod):
      $emoji = $emojiMap[$prod['Prod_Type']] ?? $emojiMap['Default'];
      $isPizza = ($prod['Prod_Type'] ?? '') === 'Pizza';
    ?>
    <div class="col-6 col-md-4 col-lg-3">
      <div class="food-card">
        <div class="thumb"><?= $emoji ?></div>
        <div class="p-3">
          <span class="badge mb-2" style="background:#fdf0f0;color:var(--sk-red);font-size:.72rem;"><?= e($prod['Prod_Category']) ?></span>
          <h6 class="fw-bold mb-1" style="font-size:.9rem;"><?= e($prod['Prod_Name']) ?></h6>
          <p class="text-muted mb-2" style="font-size:.78rem;"><?= e($prod['Prod_Type']) ?></p>
          <div class="d-flex align-items-center justify-content-between">
            <span class="price">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
            <?php if ($isPizza): ?>
              <a href="/product/<?= (int)$prod['Prod_ID'] ?>" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.78rem;">
                Order
              </a>
            <?php else: ?>
            <form method="POST" action="/add_to_cart">
              <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
              <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
              <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
              <input type="hidden" name="redirect"   value="/menu<?= $category ? '?category=' . urlencode($category) : '' ?>">
              <button type="submit" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.78rem;">
                Add to Cart
              </button>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>
