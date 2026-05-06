<?php // includes/navbar.php
$base      = (strpos($_SERVER['PHP_SELF'], '/includes/') !== false) ? '../' : '';
$cartCount = isset($cartCount) ? $cartCount : array_sum(array_column($_SESSION['cart'] ?? [], 'qty'));
$isLogged  = isset($_SESSION['cust_id']);
$firstName = $_SESSION['cust_firstname'] ?? '';
?>
<nav class="navbar navbar-expand-lg navbar-dark sk-navbar sticky-top">
  <div class="container-fluid px-4">

    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= $base ?>home.php">
      <div class="sk-logo-badge">
        <small>EST. 1954</small>
        <span>Shakey's</span>
        <small>PIZZA PARLOR</small>
      </div>
    </a>

    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <?php
        $links = [
          'home'           => ['Home',          'bi-house'],
          'menu'           => ['Menu',           'bi-grid'],
          'promos'         => ['Promos',         'bi-tag'],
          'order_tracking' => ['Order Tracking', 'bi-truck'],
          'account'        => ['Supercard',      'bi-credit-card'],
          'book_party'     => ['Book a Party',   'bi-calendar-event'],
        ];
        $cur = $current ?? basename($_SERVER['PHP_SELF'], '.php');
        foreach ($links as $file => [$label, $icon]):
          $active = ($cur === $file) ? 'active' : '';
        ?>
        <li class="nav-item">
          <a class="nav-link sk-nav-link <?= $active ?>" href="<?= $base ?><?= $file ?>.php">
            <i class="bi <?= $icon ?> me-1"></i><?= $label ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>

      <div class="d-flex align-items-center gap-3">
        <?php if ($isLogged): ?>
          <a href="<?= $base ?>account.php" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="sk-avatar"><?= strtoupper(substr($firstName, 0, 1) ?: 'U') ?></div>
            <span class="text-white fw-semibold" style="font-size:13px">Hi, <?= htmlspecialchars($firstName) ?>!</span>
          </a>
          <a href="<?= $base ?>logout.php" class="btn btn-outline-light btn-sm px-3">Logout</a>
        <?php else: ?>
          <a href="<?= $base ?>login.php" class="btn sk-btn-red px-4">Login</a>
        <?php endif; ?>

        <a href="<?= $base ?>cart.php" class="position-relative text-white text-decoration-none fs-5">
          <i class="bi bi-cart3"></i>
          <?php if ($cartCount > 0): ?>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px"><?= $cartCount ?></span>
          <?php endif; ?>
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- Category Strip -->
<div class="sk-cat-strip">
  <div class="container-fluid px-4">
    <div class="d-flex align-items-center gap-0 overflow-auto">
      <?php
      $cats = ['Promos','Supercard Exclusives','Pizza','Group Meals','Chicken \'N Mojos','Combos','Sides','Pasta','Beverages'];
      foreach ($cats as $cat):
      ?>
      <a href="<?= $base ?>menu.php?category=<?= urlencode($cat) ?>" class="sk-cat-link">
        <?= htmlspecialchars($cat) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
