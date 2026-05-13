<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Staff Dashboard — Shakey's</title>
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
.wrap{flex:1;padding:2.5rem 1.5rem;max-width:880px;margin:0 auto;width:100%;}
.welcome-card{background:#fff;border-radius:8px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.welcome-card h2{font-weight:800;color:#1a1a1a;margin:0 0 .35rem;}
.welcome-card .sub{color:#888;font-size:.92rem;margin-bottom:1.5rem;}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;}
.info-item{background:#fafafa;border-left:3px solid var(--sk-red);padding:.85rem 1rem;border-radius:4px;}
.info-item .label{font-size:.7rem;color:#888;text-transform:uppercase;font-weight:700;letter-spacing:.5px;margin-bottom:.25rem;}
.info-item .value{font-size:1rem;font-weight:700;color:#1a1a1a;}
</style>
</head>
<body>

<?php $staffName = $_SESSION['staff_name'] ?? 'Staff'; ?>
<div class="logo-bar">
  <img src="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png" alt="Shakey's Pizza">
  <div class="dropdown">
    <button class="avatar-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
      <?= e(strtoupper($staffName[0] ?? 'S')) ?>
    </button>
    <ul class="dropdown-menu dropdown-menu-end avatar-menu">
      <li class="menu-header">
        <div class="label">Signed in as</div>
        <div class="name"><?= e($staffName) ?></div>
      </li>
      <li>
        <a class="dropdown-item" href="<?= e(url('/staff/logout')) ?>">
          <i class="bi bi-box-arrow-right"></i>Logout
        </a>
      </li>
    </ul>
  </div>
</div>

<div class="wrap">
  <div class="welcome-card">
    <h2>Welcome, <?= e($_SESSION['staff_name'] ?? 'Staff') ?>!</h2>
    <p class="sub">You're signed in to your staff workspace.</p>

    <div class="info-grid">
      <div class="info-item">
        <div class="label">Employee ID</div>
        <div class="value">#<?= (int)($_SESSION['staff_id'] ?? 0) ?></div>
      </div>
      <div class="info-item">
        <div class="label">Role</div>
        <div class="value"><?= e($_SESSION['staff_role'] ?? '—') ?></div>
      </div>
      <div class="info-item">
        <div class="label">Branch</div>
        <div class="value"><?= e($_SESSION['staff_branch'] ?? '—') ?></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
