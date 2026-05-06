<?php
$cart      = $_SESSION['cart']     ?? [];
$cartCount = array_sum(array_column($cart, 'qty'));
$isLogged  = isset($_SESSION['cust_id']);
$firstName = $_SESSION['cust_firstname'] ?? '';
$current   = $current ?? basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? "Shakey's Delivery") ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sk-red:#C8181E;--sk-dark-red:#9B1015;--sk-black:#1a1a1a;--sk-gold:#D4A017;--sk-bg:#f5f5f5;}
body{background:var(--sk-bg);font-family:'Segoe UI',system-ui,sans-serif;}
.navbar-shakeys{background:rgb(18,18,18)!important;padding:.55rem 1.5rem;position:relative;z-index:50;}
.navbar-shakeys .nav-link{color:#fff!important;font-size:14px;font-weight:700;transition:color .2s;padding:.4rem .8rem;border-bottom:2px solid transparent;}
.navbar-shakeys .nav-link:hover,.navbar-shakeys .nav-link.active{color:var(--sk-red)!important;border-bottom-color:var(--sk-red);}
.brand-badge{display:flex;align-items:center;text-decoration:none;flex-shrink:0;position:relative;z-index:10;}
.brand-badge img{height:120px;width:auto;display:block;margin:15px 0 -50px;}
.cart-badge{position:absolute;top:-4px;right:-6px;background:var(--sk-red);color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;}
.cat-carousel{background:linear-gradient(rgba(0,0,0,.4),rgba(0,0,0,.4)),#C8181E url('https://www.shakeyspizza.ph/images/bg-image.png') center top / 130% auto no-repeat;padding:2rem 1rem;}
.cat-inner{position:relative;max-width:1100px;margin:0 auto;padding:0 3rem;}
.cat-track{display:flex;gap:.9rem;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;}
.cat-track::-webkit-scrollbar{display:none;}
.cat-pill{flex:0 0 auto;width:120px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:.5rem;background:rgba(0,0,0,.35);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);border-radius:8px;padding:.9rem .6rem;text-decoration:none;color:#fff;font-weight:700;font-size:.85rem;text-align:center;transition:transform .18s,background .18s,color .18s;border:2px solid transparent;}
.cat-pill:hover{transform:translateY(-2px);background:#fff;color:var(--sk-red);}
.cat-pill.active{background:#fff;color:var(--sk-red);}
.cat-pill .cat-icon{width:54px;height:54px;border-radius:50%;background:rgba(255,255,255,.95);display:flex;align-items:center;justify-content:center;font-size:1.7rem;flex-shrink:0;}
.cat-arrow{position:absolute;top:50%;transform:translateY(-50%);width:36px;height:36px;border-radius:50%;background:rgba(255,255,255,.9);border:none;color:#222;font-size:1.3rem;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(0,0,0,.25);z-index:5;transition:background .15s;}
.cat-arrow:hover{background:#fff;}
.cat-arrow-left{left:0;}
.cat-arrow-right{right:0;}
.btn-shakeys{background:var(--sk-dark-red);color:#fff;border:none;border-radius:8px;font-weight:700;padding:.75rem;}
.btn-shakeys:hover{background:#7a0c10;color:#fff;}
.food-card{background:#fff;border-radius:12px;border:1px solid #eee;transition:transform .2s,box-shadow .2s;height:100%;}
.food-card:hover{transform:translateY(-4px);box-shadow:0 8px 24px rgba(0,0,0,.12);}
.food-card .thumb{background:#fdf0f0;height:140px;border-radius:12px 12px 0 0;display:flex;align-items:center;justify-content:center;font-size:3.5rem;}
.food-card .price{color:var(--sk-red);font-weight:700;font-size:1.05rem;}
.hero-banner{background:#1a1a1a;border-radius:12px;padding:3rem 3.5rem;color:#fff;}
.section-title{font-size:1.2rem;font-weight:700;color:#222;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-preparing{background:#cfe2ff;color:#0a3981;}
.badge-delivered{background:#d1e7dd;color:#0a3622;}
.badge-cancelled{background:#f8d7da;color:#58151c;}
.promo-card{background:#fff;border-radius:10px;border:1px solid #eee;padding:1.2rem;transition:box-shadow .2s;cursor:pointer;}
.promo-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.1);}
.supercard-bar{background:#1a1a1a;border-radius:10px;padding:1.2rem 1.8rem;display:flex;align-items:center;justify-content:space-between;}
</style>
</head>
<body>

<?php if($isLogged): ?>
<nav class="navbar navbar-expand-lg navbar-shakeys sticky-top">
  <div class="container-fluid px-3">
    <a class="brand-badge me-3" href="home.php">
      <img src="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png" alt="Shakey's Pizza">
    </a>
    <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <i class="bi bi-list text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto gap-1">
        <?php
        $links = [
          'home.php'           => 'Home',
          'menu.php'           => 'Menu',
          'promos.php'         => 'Promos',
          'order_tracking.php' => 'Order Tracking',
          'account.php'        => 'Supercard',
          'book_party.php'     => 'Book a Party',
        ];
        foreach ($links as $f => $l):
          $active = ($current === $f) ? 'active' : '';
        ?>
        <li class="nav-item"><a class="nav-link <?= $active ?>" href="<?= $f ?>"><?= $l ?></a></li>
        <?php endforeach; ?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <a href="account.php" class="text-white text-decoration-none d-flex align-items-center gap-2" style="font-size:.88rem;">
          Hi, <?= e($firstName) ?>!
          <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;background:var(--sk-red);color:#fff;font-size:.85rem;"><?= e(strtoupper($firstName[0] ?? 'U')) ?></div>
        </a>
        <a href="cart.php" class="position-relative text-decoration-none">
          <i class="bi bi-cart3 fs-5 text-white"></i>
          <?php if ($cartCount > 0): ?><span class="cart-badge"><?= $cartCount ?></span><?php endif; ?>
        </a>
      </div>
    </div>
  </div>
</nav>
<div class="cat-carousel">
  <div class="cat-inner">
  <button type="button" class="cat-arrow cat-arrow-left" onclick="scrollCats(-1)" aria-label="Previous">&#8249;</button>
  <div class="cat-track" id="catTrack">
    <?php
    $cats = [
      'Promos'               => '🎉',
      'Supercard Exclusives' => '💳',
      'Pizza'                => '🍕',
      'Group Meals'          => '🍽️',
      "Chicken 'N Mojos"     => '🍗',
      'Combos'               => '🍱',
      'Hero Sandwiches'      => '🥪',
      'Pasta'                => '🍝',
      'Sides'                => '🍟',
      'Salad'                => '🥗',
      'Desserts'             => '🍰',
      'Drinks'               => '🥤',
    ];
    foreach ($cats as $c => $icon):
      $active = (isset($_GET['category']) && $_GET['category'] === $c) ? 'active' : '';
    ?>
    <a href="menu.php?category=<?= urlencode($c) ?>" class="cat-pill <?= $active ?>">
      <span class="cat-icon"><?= $icon ?></span>
      <span class="cat-label"><?= e($c) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
  <button type="button" class="cat-arrow cat-arrow-right" onclick="scrollCats(1)" aria-label="Next">&#8250;</button>
  </div>
</div>
<script>
function scrollCats(dir){
  const t=document.getElementById('catTrack');
  if(t) t.scrollBy({left:dir*320,behavior:'smooth'});
}
</script>
<?php endif; ?>
