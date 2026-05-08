<?php
$postedEmail = e($_POST['email'] ?? '');
$showStep2   = $error && !empty($_POST['password']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Shakey's Delivery</title>
<link rel="icon" type="image/png" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="apple-touch-icon" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root { --sk-red:#C8181E; --sk-dark-red:#9B1015; --sk-black:#1a1a1a; --sk-gold:#D4A017; }
*,body { font-family:'Nunito',sans-serif; margin:0; }
body { display:flex; flex-direction:column; min-height:100vh; }
.sk-nav { background:rgb(18, 18, 18); padding:.55rem 1.5rem; position:sticky; top:0; z-index:50; }
.sk-logo { display:flex; align-items:center; text-decoration:none; flex-shrink:0; position:relative; z-index:10; }
.sk-logo img { height:120px; width:auto; display:block; margin:15px 0 -50px; }
.nav-links a { color:#fff; font-size:14px; font-weight:700; text-decoration:none; padding:6px 13px; transition:color .18s; white-space:nowrap; }
.nav-links a:hover { color:var(--sk-red); }
.btn-login-nav { background:var(--sk-red); color:#fff; border:none; border-radius:3px; font-size:13px; font-weight:700; padding:6px 18px; cursor:pointer; }
.btn-login-nav:hover { background:var(--sk-dark-red); }
.auth-bg { flex:1; min-height:calc(100vh - 76px); background:#C8181E url('https://www.shakeyspizza.ph/images/bg-image.png') center top / 150% auto no-repeat; display:flex; align-items:flex-start; justify-content:center; padding:3rem 1rem 2.5rem; position:relative; overflow:hidden; }
.auth-card { background:#fff; border-radius:4px; padding:60px 56px; max-width:520px; width:100%; box-shadow:0 12px 50px rgba(0,0,0,.22); position:relative; z-index:1; }
@media(max-width:480px){ .auth-card{ padding:36px 24px; } }
.field-wrap { margin-bottom:1.8rem; }
.field-wrap label { display:block; font-size:.76rem; font-weight:800; color:#444; margin-bottom:4px; letter-spacing:.3px; }
.field-wrap input { display:block; width:100%; border:none; border-bottom:1.5px solid #d0d0d0; outline:none; padding:.45rem 0; font-size:.9rem; font-family:'Nunito',sans-serif; background:transparent; color:#222; transition:border-color .18s; }
.field-wrap input::placeholder { color:#bbb; font-size:.88rem; }
.field-wrap input:focus { border-bottom-color:var(--sk-red); }
.field-wrap.has-eye { position:relative; }
.field-wrap.has-eye input { padding-right:28px; }
.field-wrap .eye-btn { position:absolute; right:2px; bottom:10px; background:none; border:none; padding:0; cursor:pointer; color:#bbb; font-size:1rem; line-height:1; }
.btn-sk { display:block; width:100%; background:var(--sk-dark-red); color:#fff; border:none; border-radius:25px; font-weight:800; font-size:.95rem; padding:.75rem; cursor:pointer; transition:background .18s; letter-spacing:.3px; }
.btn-sk:hover { background:#7a0c10; }
.btn-fb { background:#1877f2; color:#fff; border:none; border-radius:25px; font-weight:700; font-size:.88rem; padding:.62rem 1rem; cursor:pointer; width:100%; }
.btn-fb:hover { background:#1466d8; }
.btn-gg { background:#fff; color:#444; border:1.5px solid #ddd; border-radius:25px; font-weight:700; font-size:.88rem; padding:.62rem 1rem; cursor:pointer; width:100%; display:flex; align-items:center; justify-content:center; gap:6px; }
.btn-gg:hover { background:#f5f5f5; }
.or-divider { display:flex; align-items:center; gap:10px; margin:1.4rem 0 1rem; }
.or-divider::before,.or-divider::after { content:''; flex:1; height:1px; background:#eee; }
.or-divider span { font-size:.78rem; color:#aaa; white-space:nowrap; }
.sk-footer { background:#111; padding:1rem 2rem; }
.sk-footer a { color:#888; text-decoration:none; font-size:.72rem; }
.sk-footer a:hover { color:#fff; }
.sk-footer .fi { color:#888; font-size:1.15rem; }
.sk-footer .fi:hover { color:#fff; }
.sk-hotline-num { color:var(--sk-gold); font-weight:900; font-size:1rem; letter-spacing:1px; }
</style>
</head>
<body>

<nav class="sk-nav d-flex align-items-center gap-3">
  <a href="<?= e(url('/home')) ?>" class="sk-logo">
    <img src="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png" alt="Shakey's Pizza">
  </a>
  <div class="nav-links d-none d-lg-flex flex-grow-1 justify-content-center">
    <a href="<?= e(url('/home')) ?>">Home</a>
    <a href="<?= e(url('/menu')) ?>">Menu</a>
    <a href="<?= e(url('/promos')) ?>">Promos</a>
    <a href="<?= e(url('/order_tracking')) ?>">Order Tracking</a>
    <a href="<?= e(url('/account')) ?>">Supercard</a>
    <a href="<?= e(url('/book_party')) ?>">Book a Party</a>
  </div>
  <div class="ms-auto d-flex align-items-center gap-3">
    <button class="btn-login-nav">Login</button>
    <i class="bi bi-search text-white" style="font-size:1.1rem;cursor:pointer;"></i>
    <a href="<?= e(url('/cart')) ?>" class="text-white text-decoration-none"><i class="bi bi-cart3" style="font-size:1.2rem;"></i></a>
  </div>
</nav>

<div class="auth-bg">
  <div class="auth-card">
    <h4 class="fw-black text-center mb-1" style="font-size:1.55rem;">Login to Shakey's</h4>
    <p class="text-center text-muted mb-4" style="font-size:.85rem;">
      New user? <a href="<?= e(url('/register')) ?>" style="color:var(--sk-red);font-weight:800;text-decoration:none;">Create an account</a>
    </p>

    <?php if ($error): ?>
    <div class="alert alert-danger py-2 text-center mb-3" style="font-size:.84rem;border-radius:4px;"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm" novalidate>
      <div id="step1" <?= $showStep2 ? 'style="display:none"' : '' ?>>
        <div class="field-wrap">
          <label>Email address</label>
          <input type="email" name="email" id="emailInput"
                 placeholder="Enter your email address"
                 value="<?= $postedEmail ?>">
        </div>
        <button type="button" class="btn-sk mb-3" onclick="goNext()">Next</button>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="keep1" style="accent-color:var(--sk-red);">
            <label class="form-check-label" for="keep1" style="font-size:.82rem;color:#555;">Keep me logged in</label>
          </div>
          <a href="<?= e(url('/forgot_password')) ?>" style="color:var(--sk-red);font-size:.82rem;font-weight:700;text-decoration:none;">Forgot password?</a>
        </div>
      </div>

      <div id="step2" <?= $showStep2 ? '' : 'style="display:none"' ?>>
        <p class="mb-3" style="font-size:.85rem;color:#555;">
          <button type="button" onclick="goBack()" style="background:none;border:none;padding:0;color:var(--sk-red);font-size:.85rem;cursor:pointer;font-weight:700;">
            <i class="bi bi-arrow-left me-1"></i>
          </button>
          <span id="emailDisplay" style="font-weight:700;"><?= $postedEmail ?></span>
        </p>
        <div class="field-wrap has-eye">
          <label>Password</label>
          <input type="password" name="password" id="passInput" placeholder="Enter your password">
          <button type="button" class="eye-btn" onclick="togglePass()"><i class="bi bi-eye" id="eyeIcon"></i></button>
        </div>
        <button type="submit" class="btn-sk mb-3">Login</button>
        <div class="d-flex justify-content-between align-items-center mt-2">
          <div class="form-check m-0">
            <input class="form-check-input" type="checkbox" id="keep2" style="accent-color:var(--sk-red);">
            <label class="form-check-label" for="keep2" style="font-size:.82rem;color:#555;">Keep me logged in</label>
          </div>
          <a href="<?= e(url('/forgot_password')) ?>" style="color:var(--sk-red);font-size:.82rem;font-weight:700;text-decoration:none;">Forgot password?</a>
        </div>
      </div>
    </form>

    <div class="or-divider"><span>Login with</span></div>

    <div class="d-flex gap-2">
      <button class="btn-fb"><i class="bi bi-facebook me-1"></i> Facebook</button>
      <button class="btn-gg">
        <svg width="16" height="16" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
        Google
      </button>
    </div>
  </div>
</div>

<footer class="sk-footer">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex gap-3">
      <a href="#" class="fi"><i class="bi bi-facebook"></i></a>
      <a href="#" class="fi"><i class="bi bi-twitter-x"></i></a>
      <a href="#" class="fi"><i class="bi bi-instagram"></i></a>
    </div>
    <div class="d-none d-md-flex flex-wrap gap-1 align-items-center justify-content-center">
      <a href="#">HOME</a><span style="color:#555;">·</span>
      <a href="#">ABOUT US</a><span style="color:#555;">·</span>
      <a href="#">WHAT'S IN YOUR MEAL</a><span style="color:#555;">·</span>
      <a href="#">FRANCHISING</a><span style="color:#555;">·</span>
      <a href="#">CONTACT US</a><span style="color:#555;">·</span>
      <a href="#">LEGAL TERMS</a>
    </div>
    <div class="text-end">
      <div style="color:#888;font-size:.7rem;"><i class="bi bi-phone me-1"></i>7777-7777</div>
      <div style="color:#888;font-size:.68rem;font-weight:700;">DELIVERY HOTLINE</div>
      <div class="sk-hotline-num">#77-777</div>
      <div style="color:var(--sk-gold);font-size:.65rem;font-weight:700;">If it's late, it's FREE!</div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function goNext() {
  const email = document.getElementById('emailInput').value.trim();
  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    document.getElementById('emailInput').style.borderBottomColor = '#C8181E';
    document.getElementById('emailInput').focus();
    return;
  }
  document.getElementById('emailDisplay').textContent = email;
  document.getElementById('step1').style.display = 'none';
  document.getElementById('step2').style.display = 'block';
  document.getElementById('passInput').focus();
}
function goBack() {
  document.getElementById('step2').style.display = 'none';
  document.getElementById('step1').style.display = 'block';
  document.getElementById('emailInput').focus();
}
function togglePass() {
  const inp = document.getElementById('passInput');
  const ico = document.getElementById('eyeIcon');
  if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
  else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
}
</script>
</body>
</html>
