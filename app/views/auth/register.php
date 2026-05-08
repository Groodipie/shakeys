<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Create Account — Shakey's Delivery</title>
<link rel="icon" type="image/png" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="apple-touch-icon" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root { --sk-red:#C8181E; --sk-dark-red:#9B1015; --sk-black:#1a1a1a; --sk-gold:#D4A017; }
*,body { font-family:'Nunito',sans-serif; margin:0; }
body { display:flex; flex-direction:column; min-height:100vh; }
.sk-nav { background:var(--sk-black); padding:.55rem 1.5rem; }
.sk-logo { width:58px; height:58px; border-radius:50%; background:var(--sk-black); border:2.5px solid var(--sk-gold); display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; line-height:1.15; text-decoration:none; flex-shrink:0; }
.sk-logo small { color:var(--sk-gold); font-size:5.5px; font-weight:700; letter-spacing:.8px; display:block; }
.sk-logo span  { color:var(--sk-red);  font-size:12.5px; font-weight:900; font-family:Georgia,serif; }
.nav-links a { color:#ccc; font-size:13px; font-weight:700; text-decoration:none; padding:6px 13px; transition:color .18s; white-space:nowrap; }
.nav-links a:hover { color:#fff; }
.btn-login-nav { background:var(--sk-red); color:#fff; border:none; border-radius:3px; font-size:13px; font-weight:700; padding:6px 18px; cursor:pointer; }
.btn-login-nav:hover { background:var(--sk-dark-red); }
.auth-bg { flex:1; background:#C8181E; background-image: radial-gradient(ellipse at 10% 60%, rgba(0,0,0,.22) 0%, transparent 45%), radial-gradient(ellipse at 90% 40%, rgba(0,0,0,.15) 0%, transparent 45%); display:flex; align-items:center; justify-content:center; padding:2.5rem 1rem; position:relative; overflow:hidden; }
.auth-bg::after { content:'🍕'; position:absolute; right:3%; top:50%; transform:translateY(-50%); font-size:300px; opacity:.1; pointer-events:none; line-height:1; }
.auth-card { background:#fff; border-radius:4px; padding:48px 44px; max-width:500px; width:100%; box-shadow:0 12px 50px rgba(0,0,0,.22); position:relative; z-index:1; }
@media(max-width:520px){ .auth-card{ padding:32px 20px; } }
.field-wrap { margin-bottom:1.5rem; }
.field-wrap label { display:block; font-size:.76rem; font-weight:800; color:#444; margin-bottom:4px; letter-spacing:.3px; }
.field-wrap input,.field-wrap select { display:block; width:100%; border:none; border-bottom:1.5px solid #d0d0d0; outline:none; padding:.45rem 0; font-size:.9rem; font-family:'Nunito',sans-serif; background:transparent; color:#222; transition:border-color .18s; border-radius:0; -webkit-appearance:none; }
.field-wrap input::placeholder { color:#bbb; font-size:.88rem; }
.field-wrap input:focus,.field-wrap select:focus { border-bottom-color:var(--sk-red); }
.field-wrap.has-eye { position:relative; }
.field-wrap.has-eye input { padding-right:28px; }
.field-wrap .eye-btn { position:absolute; right:2px; bottom:10px; background:none; border:none; padding:0; cursor:pointer; color:#bbb; font-size:1rem; line-height:1; }
.phone-row { display:flex; align-items:flex-end; border-bottom:1.5px solid #d0d0d0; transition:border-color .18s; margin-bottom:1.5rem; }
.phone-row:focus-within { border-bottom-color:var(--sk-red); }
.phone-prefix { display:flex; align-items:center; gap:4px; color:var(--sk-red); font-weight:800; font-size:.9rem; padding:.45rem .5rem .45rem 0; white-space:nowrap; cursor:pointer; flex-shrink:0; }
.phone-prefix i { font-size:.7rem; }
.phone-prefix select { position:absolute; opacity:0; width:60px; cursor:pointer; }
.phone-input { flex:1; border:none; outline:none; padding:.45rem 0; font-size:.9rem; font-family:'Nunito',sans-serif; background:transparent; color:#222; }
.phone-input::placeholder { color:#bbb; font-size:.88rem; }
.btn-sk { display:block; width:100%; background:var(--sk-dark-red); color:#fff; border:none; border-radius:25px; font-weight:800; font-size:.95rem; padding:.75rem; cursor:pointer; transition:background .18s; letter-spacing:.3px; }
.btn-sk:hover { background:#7a0c10; }
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
    <small>EST. 1954</small>
    <span>Shakey's</span>
    <small>PIZZA PARLOR</small>
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
    <a href="<?= e(url('/login')) ?>" class="btn-login-nav">Login</a>
    <i class="bi bi-search text-white" style="font-size:1.1rem;cursor:pointer;"></i>
    <a href="<?= e(url('/cart')) ?>" class="text-white text-decoration-none"><i class="bi bi-cart3" style="font-size:1.2rem;"></i></a>
  </div>
</nav>

<div class="auth-bg">
  <div class="auth-card">
    <h4 class="fw-black text-center mb-1" style="font-size:1.55rem;">Create a Shakey's Account</h4>
    <p class="text-center text-muted mb-4" style="font-size:.85rem;">
      Already have an account? <a href="<?= e(url('/login')) ?>" style="color:var(--sk-red);font-weight:800;text-decoration:none;">Login</a>
    </p>

    <?php if ($error): ?>
    <div class="alert alert-danger py-2 mb-3" style="font-size:.84rem;border-radius:4px;"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
    <div class="alert alert-success py-2 mb-3" style="font-size:.84rem;border-radius:4px;"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="row g-3 mb-0">
        <div class="col-6">
          <div class="field-wrap">
            <label>First name</label>
            <input type="text" name="first_name" placeholder="Enter your first name"
                   value="<?= e($_POST['first_name'] ?? '') ?>" required>
          </div>
        </div>
        <div class="col-6">
          <div class="field-wrap">
            <label>Last name</label>
            <input type="text" name="last_name" placeholder="Enter your last name"
                   value="<?= e($_POST['last_name'] ?? '') ?>" required>
          </div>
        </div>
      </div>

      <div>
        <label style="display:block;font-size:.76rem;font-weight:800;color:#444;margin-bottom:4px;letter-spacing:.3px;">Mobile number</label>
        <div class="phone-row">
          <div class="phone-prefix position-relative">
            +63 <i class="bi bi-chevron-down"></i>
            <select aria-label="Country code" tabindex="-1">
              <option>+63</option>
            </select>
          </div>
          <input type="text" name="phone" class="phone-input" maxlength="10"
                 placeholder="Enter mobile number (Ex: 9171234567)"
                 value="<?= e($_POST['phone'] ?? '') ?>" required>
        </div>
      </div>

      <div class="field-wrap">
        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email address"
               value="<?= e($_POST['email'] ?? '') ?>" required>
      </div>

      <div class="row g-3">
        <div class="col-6">
          <div class="field-wrap has-eye">
            <label>Password</label>
            <input type="password" name="password" id="passA" placeholder="Enter your password" required>
            <button type="button" class="eye-btn" onclick="toggleEye('passA','eyeA')"><i class="bi bi-eye" id="eyeA"></i></button>
          </div>
        </div>
        <div class="col-6">
          <div class="field-wrap has-eye">
            <label>Confirm password</label>
            <input type="password" name="confirm_password" id="passB" placeholder="Confirm password" required>
            <button type="button" class="eye-btn" onclick="toggleEye('passB','eyeB')"><i class="bi bi-eye" id="eyeB"></i></button>
          </div>
        </div>
      </div>

      <p class="text-center mb-3" style="font-size:.78rem;color:#4285F4;line-height:1.5;">
        Your password must be at least 6 characters long and must contain letters, numbers<br>
        and special characters. Cannot contain whitespace.
      </p>

      <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="agree" id="agree"
               style="accent-color:var(--sk-red);" <?= isset($_POST['agree']) ? 'checked' : '' ?>>
        <label class="form-check-label" for="agree" style="font-size:.83rem;color:#555;line-height:1.5;">
          By signing up you agree to our
          <a href="#" style="color:var(--sk-red);font-weight:700;text-decoration:none;">Terms and Conditions</a> and
          <a href="#" style="color:var(--sk-red);font-weight:700;text-decoration:none;">Privacy Policy</a>
        </label>
      </div>

      <button type="submit" class="btn-sk">Create account</button>
    </form>
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
function toggleEye(inputId, iconId) {
  const inp = document.getElementById(inputId);
  const ico = document.getElementById(iconId);
  if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
  else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
}
</script>
</body>
</html>
