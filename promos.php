<?php
// promos.php
require_once 'includes/auth_check.php';
require_once 'db.php';
$pageTitle = "Promos — Shakey's Delivery";
$tab = $_GET['tab'] ?? 'promo';
$today = date('Y-m-d');

$promos = $pdo->prepare(
    "SELECT * FROM Promotion WHERE Promo_ValidFrom <= ? AND Promo_ValidTo >= ? ORDER BY Promo_ID DESC"
);
$promos->execute([$today, $today]);
$activePromos = $promos->fetchAll();

$expired = $pdo->prepare(
    "SELECT * FROM Promotion WHERE Promo_ValidTo < ? ORDER BY Promo_ValidTo DESC LIMIT 6"
);
$expired->execute([$today]);
$expiredPromos = $expired->fetchAll();

include 'includes/header.php';
?>

<div class="container-fluid px-3 px-md-4 py-3">

  <!-- Supercard banner -->
  <div class="supercard-bar mb-4">
    <div>
      <h6 class="fw-bold mb-0" style="color:var(--sk-gold);">Free Pizza. Chicken. <span style="color:var(--sk-red);">Plus</span> More!</h6>
      <small class="text-secondary">Supercard Members enjoy these benefits from Shakey's.</small>
    </div>
    <button class="btn btn-sm fw-bold" style="border:2px solid #ccc;color:#ccc;background:none;">KNOW MORE</button>
  </div>

  <!-- Tabs -->
  <div class="border-bottom mb-4">
    <nav class="d-flex gap-0">
      <a href="?tab=promo" class="text-decoration-none px-4 py-2 fw-bold" style="font-size:.95rem;color:<?= $tab==='promo'?'var(--sk-red)':'#888' ?>;border-bottom:<?= $tab==='promo'?'3px solid var(--sk-red)':'3px solid transparent' ?>;">
        Promo
      </a>
      <a href="?tab=news" class="text-decoration-none px-4 py-2 fw-bold" style="font-size:.95rem;color:<?= $tab==='news'?'var(--sk-red)':'#888' ?>;border-bottom:<?= $tab==='news'?'3px solid var(--sk-red)':'3px solid transparent' ?>;">
        News
      </a>
    </nav>
  </div>

  <?php if($tab === 'promo'): ?>

  <!-- Active promotions -->
  <?php if(empty($activePromos)): ?>
  <div class="text-center py-5">
    <div style="font-size:3.5rem;">🎉</div>
    <h5 class="mt-3 text-muted">No active promotions right now</h5>
    <p class="text-secondary" style="font-size:.88rem;">Check back soon for exciting deals!</p>
  </div>
  <?php else: ?>
  <div class="row g-3 mb-5">
    <?php foreach($activePromos as $p): 
      $discLabel = $p['Promo_Discount']==='Fixed'
        ? '₱'.number_format($p['Promo_DiscountValue'],2).' OFF'
        : $p['Promo_DiscountValue'].'% OFF';
      $validUntil = date('M d, Y', strtotime($p['Promo_ValidTo']));
      $catBg = ['Bundle'=>'#fff3cd','Supercard'=>'#cfe2ff','All'=>'#d1e7dd'];
      $catColor = ['Bundle'=>'#856404','Supercard'=>'#0a3981','All'=>'#0a3622'];
    ?>
    <div class="col-12 col-md-6">
      <div class="promo-card d-flex gap-3 align-items-start">
        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
             style="width:80px;height:80px;background:#fdf0f0;font-size:2.5rem;">🍕</div>
        <div class="flex-grow-1">
          <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
            <h6 class="fw-bold mb-0" style="font-size:.95rem;"><?= htmlspecialchars($p['Promo_Code']) ?></h6>
            <span class="badge px-2 py-1" style="background:<?= $catBg[$p['Promo_Category']] ?? '#eee' ?>;color:<?= $catColor[$p['Promo_Category']] ?? '#333' ?>;font-size:.72rem;">
              <?= htmlspecialchars($p['Promo_Category']) ?>
            </span>
          </div>
          <p class="text-muted mb-2" style="font-size:.82rem;line-height:1.4;"><?= htmlspecialchars($p['Promo_Description']) ?></p>
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span class="fw-bold" style="color:var(--sk-red);font-size:1rem;"><?= $discLabel ?></span>
            <div class="d-flex gap-2 align-items-center">
              <small class="text-muted">Valid until <?= $validUntil ?></small>
              <span class="badge px-3 py-2 fw-bold" style="background:var(--sk-red);font-size:.72rem;font-family:monospace;letter-spacing:1px;">
                <?= htmlspecialchars($p['Promo_Code']) ?>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <?php else: /* news tab */ ?>
  <div class="text-center py-5">
    <div style="font-size:3.5rem;">📰</div>
    <h5 class="mt-3 text-muted">Latest News</h5>
    <p class="text-secondary" style="font-size:.88rem;">Stay tuned for updates from Shakey's!</p>
  </div>
  <?php endif; ?>

</div>
<?php include 'includes/footer.php'; ?>
