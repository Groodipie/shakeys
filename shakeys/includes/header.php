<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$cart      = $_SESSION['cart']     ?? [];
$cartCount = array_sum(array_column($cart, 'qty'));
$isLogged  = isset($_SESSION['cust_id']);
$firstName = $_SESSION['cust_firstname'] ?? '';
$base      = (strpos($_SERVER['PHP_SELF'], '/includes/') !== false) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $pageTitle ?? "Shakey's Delivery" ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sk-red:#C8181E;--sk-dark-red:#9B1015;--sk-black:#1a1a1a;--sk-gold:#D4A017;--sk-bg:#f5f5f5;}
body{background:var(--sk-bg);font-family:'Segoe UI',system-ui,sans-serif;}
.navbar-shakeys{background:var(--sk-black)!important;padding:.6rem 1.5rem;}
.navbar-shakeys .nav-link{color:#ccc!important;font-size:.88rem;transition:color .2s;padding:.4rem .7rem;border-bottom:2px solid transparent;}
.navbar-shakeys .nav-link:hover,.navbar-shakeys .nav-link.active{color:var(--sk-gold)!important;border-bottom-color:var(--sk-gold);}
.brand-badge{display:flex;align-items:center;justify-content:center;flex-direction:column;width:60px;height:60px;border-radius:50%;background:var(--sk-black);border:2.5px solid var(--sk-gold);text-decoration:none;}
.brand-badge .est{color:var(--sk-gold);font-size:7px;font-weight:700;letter-spacing:1px;}
.brand-badge .name{color:var(--sk-red);font-size:13px;font-weight:900;line-height:1;font-family:Georgia,serif;}
.brand-badge .sub{color:#fff;font-size:6px;letter-spacing:1.5px;}
.cart-badge{position:absolute;top:-4px;right:-6px;background:var(--sk-red);color:#fff;border-radius:50%;width:16px;height:16px;font-size:9px;font-weight:700;display:flex;align-items:center;justify-content:center;}
.cat-bar{background:#2a0505;overflow-x:auto;white-space:nowrap;padding:.15rem 1rem;}
.cat-bar a{color:#ddd;font-size:.82rem;padding:.65rem 1.1rem;display:inline-block;text-decoration:none;border-bottom:2px solid transparent;transition:all .2s;}
.cat-bar a:hover,.cat-bar a.active{color:var(--sk-gold);border-bottom-color:var(--sk-gold);}
.auth-wrapper{min-height:100vh;background:radial-gradient(ellipse at top,#8b0000 0%,var(--sk-red) 50%,#a01010 100%);display:flex;align-items:center;justify-content:center;padding:2rem;}
.auth-card{background:#fff;border-radius:16px;padding:2.5rem 2rem;max-width:480px;width:100%;box-shadow:0 24px 80px rgba(0,0,0,.3);}
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
    <a class="brand-badge me-3" href="<?=$base?>home.php">
      <span class="est">EST. 1954</span>
      <span class="name">Shakey's</span>
      <span class="sub">PIZZA PARLOR</span>
    </a>
    <button class="navbar-toggler border-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <i class="bi bi-list text-white"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav me-auto gap-1">
        <?php
        $cur=$current??basename($_SERVER['PHP_SELF']);
        $links=['home.php'=>'Home','menu.php'=>'Menu','promos.php'=>'Promos','order_tracking.php'=>'Order Tracking','account.php'=>'Supercard','book_party.php'=>'Book a Party'];
        foreach($links as $f=>$l):$a=($cur===$f)?'active':'';?>
        <li class="nav-item"><a class="nav-link <?=$a?>" href="<?=$base.$f?>"><?=$l?></a></li>
        <?php endforeach;?>
      </ul>
      <div class="d-flex align-items-center gap-3">
        <a href="<?=$base?>account.php" class="text-white text-decoration-none d-flex align-items-center gap-2" style="font-size:.88rem;">
          Hi, <?=htmlspecialchars($firstName)?>!
          <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;background:var(--sk-red);color:#fff;font-size:.85rem;"><?=strtoupper($firstName[0]??'U')?></div>
        </a>
        <a href="<?=$base?>cart.php" class="position-relative text-decoration-none">
          <i class="bi bi-cart3 fs-5 text-white"></i>
          <?php if($cartCount>0):?><span class="cart-badge"><?=$cartCount?></span><?php endif;?>
        </a>
      </div>
    </div>
  </div>
</nav>
<div class="cat-bar">
  <?php $cats=['Promos','Supercard Exclusives','Pizza','Group Meals',"Chicken 'N Mojos",'Combos','Hero Sandwiches','Pasta','Sides','Salad','Desserts','Drinks'];
  foreach($cats as $c):$a=(isset($_GET['category'])&&$_GET['category']==$c)?'active':'';?>
  <a href="<?=$base?>menu.php?category=<?=urlencode($c)?>" class="<?=$a?>"><?=$c?></a>
  <?php endforeach;?>
</div>
<?php endif;?>
