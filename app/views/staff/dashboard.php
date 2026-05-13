<style>
.dash-title{font-size:1.6rem;font-weight:800;color:#1a1a1a;margin:0 0 .35rem;}
.dash-sub{color:#888;font-size:.9rem;margin-bottom:2rem;}
.welcome-card{background:#fff;border-radius:8px;padding:2rem;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;}
.info-item{background:#fafafa;border-left:3px solid var(--sk-red);padding:.85rem 1rem;border-radius:4px;}
.info-item .label{font-size:.7rem;color:#888;text-transform:uppercase;font-weight:700;letter-spacing:.5px;margin-bottom:.25rem;}
.info-item .value{font-size:1rem;font-weight:700;color:#1a1a1a;}
</style>

<h2 class="dash-title">Welcome, <?= e($_SESSION['staff_name'] ?? 'Staff') ?>!</h2>
<p class="dash-sub">You're signed in to your staff workspace.</p>

<div class="welcome-card">
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
