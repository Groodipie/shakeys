<?php
$current = $current ?? '';
$username = $_SESSION['admin_username'] ?? 'admin';
$nav = [
  'dashboard' => ['label' => 'Dashboard',         'icon' => 'bi-speedometer2', 'href' => '/admin/dashboard'],
  'staff'     => ['label' => 'Staff Management',  'icon' => 'bi-people',       'href' => '/admin/staff'],
  'riders'    => ['label' => 'Rider Management',  'icon' => 'bi-bicycle',      'href' => '/admin/riders'],
  'products'  => ['label' => 'Add Product',       'icon' => 'bi-plus-square',  'href' => '/admin/products'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle ?? "Admin — Shakey's") ?></title>
<link rel="icon" type="image/png" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sk-red:#C8181E;--sk-dark-red:#9B1015;}
body{margin:0;font-family:'Segoe UI',system-ui,sans-serif;background:#f5f5f5;min-height:100vh;display:flex;flex-direction:column;}
.logo-bar{background:#121212;padding:.85rem 1.5rem;display:flex;align-items:center;justify-content:space-between;}
.logo-bar img{height:60px;width:auto;display:block;}
.avatar-btn{width:40px;height:40px;border-radius:50%;background:var(--sk-red);color:#fff;border:none;font-weight:800;font-size:1rem;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s;text-transform:uppercase;}
.avatar-btn:hover,.avatar-btn:focus{background:var(--sk-dark-red);outline:none;}
.avatar-menu.dropdown-menu{min-width:200px;padding:.4rem 0;border:none;box-shadow:0 8px 24px rgba(0,0,0,.15);border-radius:6px;}
.avatar-menu .menu-header{padding:.6rem 1rem .7rem;border-bottom:1px solid #eee;}
.avatar-menu .menu-header .label{font-size:.72rem;color:#888;text-transform:uppercase;font-weight:700;letter-spacing:.5px;}
.avatar-menu .menu-header .name{font-size:.92rem;font-weight:700;color:#1a1a1a;margin-top:2px;}
.avatar-menu .dropdown-item{padding:.55rem 1rem;font-size:.88rem;font-weight:600;color:#444;display:flex;align-items:center;gap:.6rem;}
.avatar-menu .dropdown-item:hover{background:#f5f5f5;color:var(--sk-red);}
.avatar-menu .dropdown-item i{font-size:1rem;}
.admin-shell{flex:1;display:flex;align-items:stretch;}
.admin-sidebar{width:240px;background:#1a1a1a;color:#cfcfcf;padding:1.5rem 0;flex-shrink:0;}
.admin-sidebar .nav-label{font-size:.7rem;font-weight:800;letter-spacing:1.2px;color:#666;padding:0 1.5rem .6rem;text-transform:uppercase;}
.admin-sidebar a{display:flex;align-items:center;gap:.75rem;padding:.75rem 1.5rem;color:#cfcfcf;text-decoration:none;font-size:.9rem;font-weight:600;border-left:3px solid transparent;transition:background .15s,color .15s,border-color .15s;}
.admin-sidebar a:hover{background:#252525;color:#fff;}
.admin-sidebar a.active{background:#252525;color:#fff;border-left-color:var(--sk-red);}
.admin-sidebar a i{font-size:1.05rem;width:18px;text-align:center;}
.admin-main{flex:1;padding:2rem;min-width:0;}
@media (max-width:768px){
  .admin-shell{flex-direction:column;}
  .admin-sidebar{width:100%;padding:.5rem 0;}
  .admin-sidebar a{padding:.6rem 1rem;}
}
</style>
</head>
<body>

<div class="logo-bar">
  <img src="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png" alt="Shakey's Pizza">
  <div class="dropdown">
    <button class="avatar-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
      <?= e(strtoupper($username[0] ?? 'A')) ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end avatar-menu">
      <li class="menu-header">
        <div class="label">Signed in as</div>
        <div class="name"><?= e($username) ?></div>
      </li>
      <li>
        <a class="dropdown-item" href="<?= e(url('/admin/logout')) ?>">
          <i class="bi bi-box-arrow-right"></i>Logout
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="nav-label">Menu</div>
    <?php foreach ($nav as $key => $item): ?>
      <a href="<?= e(url($item['href'])) ?>" class="<?= $current === $key ? 'active' : '' ?>">
        <i class="bi <?= e($item['icon']) ?>"></i><?= e($item['label']) ?>
      </a>
    <?php endforeach; ?>
  </aside>
  <main class="admin-main">
