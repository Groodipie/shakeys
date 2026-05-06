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
/* Big peek-style hero carousel */
.bigcar{position:relative;}
.bigcar-viewport{overflow:hidden;}
.bigcar-track{display:flex;gap:18px;transition:transform .55s cubic-bezier(.4,0,.2,1);will-change:transform;}
.bigcar-slide{flex:0 0 auto;width:min(82%, 1100px);aspect-ratio:5/2.5;border-radius:18px;position:relative;overflow:hidden;color:#fff;display:flex;align-items:center;padding:2.5rem 3rem;box-shadow:0 12px 32px rgba(0,0,0,.18);transform:scale(.94);opacity:.55;transition:transform .45s ease, opacity .45s ease;text-decoration:none;}
.bigcar-slide.is-active{transform:scale(1);opacity:1;}
.bigcar-slide-img{background-size:cover;background-position:center;background-repeat:no-repeat;padding:0;}
.bigcar-slide-missing{background:repeating-linear-gradient(45deg,#f1f1f1 0 12px,#e6e6e6 12px 24px);color:#666;align-items:center;justify-content:center;}
.bigcar-placeholder{text-align:center;}
.bigcar-placeholder-icon{font-size:3rem;margin-bottom:.6rem;}
.bigcar-placeholder-text{font-size:.9rem;}
.bigcar-placeholder-text code{background:#fff;padding:.15rem .4rem;border-radius:4px;font-size:.8rem;color:#333;}
.bigcar-content{position:relative;z-index:2;max-width:60%;}
.bigcar-eyebrow{font-size:.78rem;font-weight:800;letter-spacing:3px;text-transform:uppercase;margin-bottom:.6rem;color:var(--sk-gold);}
.bigcar-title{font-family:Georgia,'Times New Roman',serif;font-size:clamp(2.4rem, 5vw, 4rem);font-weight:900;line-height:1;margin-bottom:.8rem;letter-spacing:1px;}
.bigcar-desc{font-size:.95rem;opacity:.85;margin-bottom:1.6rem;max-width:36ch;}
.bigcar-cta{display:flex;align-items:center;gap:.85rem;flex-wrap:wrap;}
.bigcar-price{font-size:2.4rem;font-weight:900;color:var(--sk-gold);line-height:1;}
.bigcar-save{background:var(--sk-red);color:#fff;font-weight:800;font-size:.82rem;padding:.45rem .85rem;border-radius:6px;}
.bigcar-btn{background:#fff;color:#111;font-weight:800;font-size:.85rem;padding:.65rem 1.4rem;border-radius:8px;text-decoration:none;transition:transform .15s, background .15s;}
.bigcar-btn:hover{transform:translateY(-1px);background:var(--sk-gold);color:#111;}
.bigcar-art{position:absolute;right:2.2rem;top:50%;transform:translateY(-50%);width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;font-size:6rem;z-index:1;}
.bigcar-deco{position:absolute;z-index:0;pointer-events:none;}
.bigcar-deco-tl{top:0;left:0;width:140px;height:140px;}
.bigcar-deco-br{bottom:0;right:0;width:160px;height:160px;}

/* Theme: late-night purple */
.bigcar-theme-late{background:linear-gradient(135deg,#2a0e3d 0%,#4d1a5e 60%,#6b2475 100%);}
.bigcar-theme-late .bigcar-deco-tl{background:repeating-linear-gradient(45deg,#C8181E 0 12px,#fff 12px 24px);clip-path:polygon(0 0,100% 0,0 100%);opacity:.85;}
.bigcar-theme-late .bigcar-deco-br{background:radial-gradient(circle,#ffd54a 0 6px,transparent 7px) 0 0/24px 24px;opacity:.45;}

/* Theme: HBO yellow */
.bigcar-theme-hbo{background:linear-gradient(135deg,#fbbf24 0%,#f59e0b 100%);color:#2a1500;}
.bigcar-theme-hbo .bigcar-eyebrow{color:#7a0c10;}
.bigcar-theme-hbo .bigcar-price{color:#C8181E;}
.bigcar-theme-hbo .bigcar-art{background:rgba(0,0,0,.08);}
.bigcar-theme-hbo .bigcar-btn{background:var(--sk-red);color:#fff;}
.bigcar-theme-hbo .bigcar-btn:hover{background:#7a0c10;color:#fff;}
.bigcar-theme-hbo .bigcar-deco-tl{background:repeating-linear-gradient(45deg,#C8181E 0 12px,#fff 12px 24px);clip-path:polygon(0 0,100% 0,0 100%);opacity:.9;}
.bigcar-theme-hbo .bigcar-deco-br{background:radial-gradient(circle,#C8181E 0 7px,transparent 8px) 0 0/26px 26px;opacity:.35;}

/* Theme: Shakey's red / supercard */
.bigcar-theme-super{background:linear-gradient(135deg,#C8181E 0%,#7a0c10 100%);}
.bigcar-theme-super .bigcar-deco-tl{background:repeating-linear-gradient(45deg,#1a1a1a 0 12px,#ffd54a 12px 24px);clip-path:polygon(0 0,100% 0,0 100%);opacity:.85;}
.bigcar-theme-super .bigcar-deco-br{background:radial-gradient(circle,#ffd54a 0 6px,transparent 7px) 0 0/24px 24px;opacity:.5;}

/* Arrows */
.bigcar-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:10;width:48px;height:48px;border-radius:50%;background:rgba(255,255,255,.95);border:none;color:#222;font-size:1.6rem;font-weight:700;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 6px 18px rgba(0,0,0,.25);transition:background .15s, transform .15s;}
.bigcar-arrow:hover{background:#fff;transform:translateY(-50%) scale(1.06);}
.bigcar-prev{left:1rem;}
.bigcar-next{right:1rem;}

/* Dots */
.bigcar-dots{display:flex;gap:8px;justify-content:center;margin-top:1rem;}
.bigcar-dot{width:9px;height:9px;border-radius:50%;border:none;background:#d8b9bb;padding:0;cursor:pointer;transition:background .2s, transform .2s;}
.bigcar-dot.active{background:var(--sk-red);transform:scale(1.25);}

@media (max-width: 768px){
  .bigcar-slide{aspect-ratio:16/9;padding:1.5rem 1.6rem;width:88%;}
  .bigcar-content{max-width:100%;}
  .bigcar-art{display:none;}
  .bigcar-arrow{width:36px;height:36px;font-size:1.2rem;}
  .bigcar-prev{left:.4rem;}
  .bigcar-next{right:.4rem;}
}
.section-title{font-size:1.2rem;font-weight:700;color:#222;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-preparing{background:#cfe2ff;color:#0a3981;}
.badge-delivered{background:#d1e7dd;color:#0a3622;}
.badge-cancelled{background:#f8d7da;color:#58151c;}
.promo-card{background:#fff;border-radius:10px;border:1px solid #eee;padding:1.2rem;transition:box-shadow .2s;cursor:pointer;}
.promo-card:hover{box-shadow:0 4px 16px rgba(0,0,0,.1);}
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
      <ul class="navbar-nav mx-auto gap-1">
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
