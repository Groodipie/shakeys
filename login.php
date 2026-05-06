<?php
// login.php
session_start();
if (isset($_SESSION['cust_id'])) { header('Location: home.php'); exit; }

require_once 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM Customer WHERE Cust_Email = ?");
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer && password_verify($password, $customer['Cust_Password'])) {
            $_SESSION['cust_id']        = $customer['Cust_ID'];
            $_SESSION['cust_firstname'] = $customer['Cust_FirstName'];
            $_SESSION['cust_lastname']  = $customer['Cust_LastName'];
            $_SESSION['cust_email']     = $customer['Cust_Email'];
            $_SESSION['cust_phone']     = $customer['Cust_Phone'];
            $_SESSION['cust_address']   = $customer['Cust_Address'];
            header('Location: home.php'); exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
$postedEmail = htmlspecialchars($_POST['email'] ?? '');
$showStep2   = $error && !empty($_POST['password']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — Shakey's Delivery</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>
:root { --sk-red:#C8181E; --sk-dark-red:#9B1015; --sk-black:#1a1a1a; --sk-gold:#D4A017; }
*,body { font-family:'Nunito',sans-serif; margin:0; }
body { display:flex; flex-direction:column; min-height:100vh; }

/* ── NAVBAR ── */
.sk-nav { background:var(--sk-black); padding:.55rem 1.5rem; }
.sk-logo {
  width:58px; height:58px; border-radius:50%;
  background:var(--sk-black); border:2.5px solid var(--sk-gold);
  display:flex; flex-direction:column;
  align-items:center; justify-content:center;
  text-align:center; line-height:1.15; text-decoration:none; flex-shrink:0;
}
.sk-logo small { color:var(--sk-gold); font-size:5.5px; font-weight:700; letter-spacing:.8px; display:block; }
.sk-logo span  { color:var(--sk-red);  font-size:12.5px; font-weight:900; font-family:Georgia,serif; }
.nav-links a { color:#ccc; font-size:13px; font-weight:700; text-decoration:none; padding:6px 13px; transition:color .18s; white-space:nowrap; }
.nav-links a:hover { color:#fff; }
.btn-login-nav { background:var(--sk-red); color:#fff; border:none; border-radius:3px; font-size:13px; font-weight:700; padding:6px 18px; cursor:pointer; }
.btn-login-nav:hover { background:var(--sk-dark-red); }

/* ── BACKGROUND ── */
.auth-bg {
  flex:1;
  background:#C8181E;
  background-image:
    radial-gradient(ellipse at 10% 60%, rgba(0,0,0,.22) 0%, transparent 45%),
    radial-gradient(ellipse at 90% 40%, rgba(0,0,0,.15) 0%, transparent 45%);
  display:flex; align-items:center; justify-content:center;
  padding:2.5rem 1rem; position:relative; overflow:hidden;
}
.auth-bg::after {
  content:'🍕';
  position:absolute; right:3%; top:50%; transform:translateY(-50%);
  font-size:300px; opacity:.1; pointer-events:none; line-height:1;
}

/* ── CARD ── */
.auth-card {
  background:#fff; border-radius:4px;
  padding:52px 48px; max-width:420px; width:100%;
  box-shadow:0 12px 50px rgba(0,0,0,.22); position:relative; z-index:1;
}
@media(max-width:480px){ .auth-card{ padding:36px 24px; } }

/* ── UNDERLINE FIELDS ── */
.field-wrap { margin-bottom:1.8rem; }
.field-wrap label { display:block; font-size:.76rem; font-weight:800; color:#444; margin-bottom:4px; letter-spacing:.3px; }
.field-wrap input {
  display:block; width:100%; border:none; border-bottom:1.5px solid #d0d0d0;
  outline:none; padding:.45rem 0; font-size:.9rem; font-family:'Nunito',sans-serif;
  background:transparent; color:#222; transition:border-color .18s;
}
.field-wrap input::placeholder { color:#bbb; font-size:.88rem; }
.field-wrap input:focus { border-bottom-color:var(--sk-red); }
.field-wrap.has-eye { position:relative; }
.field-wrap.has-eye input { padding-right:28px; }
.field-wrap .eye-btn {
  position:absolute; right:2px; bottom:10px;
  background:none; border:none; padding:0; cursor:pointer; color:#bbb; font-size:1rem; line-height:1;
}

/* ── BUTTONS ── */
.btn-sk {
  display:block; width:100%; background:var(--sk-dark-red); color:#fff;
  border:none; border-radius:25px; font-weight:800; font-size:.95rem;
  padding:.75rem; cursor:pointer; transition:background .18s; letter-spacing:.3px;
}
.btn-sk:hover { background:#7a0c10; }
.btn-fb { background:#1877f2; color:#fff; border:none; border-radius:25px; font-weight:700; font-size:.88rem; padding:.62rem 1rem; cursor:pointer; width:100%; }
.btn-fb:hover { background:#1466d8; }
.btn-gg { background:#fff; color:#444; border:1.5px solid #ddd; border-radius:25px; font-weight:700; font-size:.88rem; padding:.62rem 1rem; cursor:pointer; width:100%; display:flex; align-items:center; justify-content:center; gap:6px; }
.btn-gg:hover { background:#f5f5f5; }

/* ── DIVIDER ── */
.or-divider { display:flex; align-items:center; gap:10px; margin:1.4rem 0 1rem; }
.or-divider::before,.or-divider::after { content:''; flex:1; height:1px; background:#eee; }
.or-divider span { font-size:.78rem; color:#aaa; white-space:nowrap; }

/* ── FOOTER ── */
.sk-footer { background:#111; padding:1rem 2rem; }
.sk-footer a { color:#888; text-decoration:none; font-size:.72rem; }
.sk-footer a:hover { color:#fff; }
.sk-footer .fi { color:#888; font-size:1.15rem; }
.sk-footer .fi:hover { color:#fff; }
.sk-hotline-num { color:var(--sk-gold); font-weight:900; font-size:1rem; letter-spacing:1px; }
</style>
</head>
<body>

<!-- ── NAVBAR ── -->
<nav class="sk-nav d-flex align-items-center gap-3">
  <a href="home.php" class="sk-logo">
    <small>EST. 1954</small>
    <span>Shakey's</span>
    <small>PIZZA PARLOR</small>
  </a>
  <div class="nav-links d-none d-lg-flex flex-grow-1 justify-content-center">
    <a href="home.php">Home</a>
    <a href="menu.php">Menu</a>
    <a href="promos.php">Promos</a>
    <a href="order_tracking.php">Order Tracking</a>
    <a href="account.php">Supercard</a>
    <a href="book_party.php">Book a Party</a>
  </div>
  <div class="ms-auto d-flex align-items-center gap-3">
    <button class="btn-login-nav">Login</button>
    <i class="bi bi-search text-white" style="font-size:1.1rem;cursor:pointer;"></i>
    <a href="cart.php" class="text-white text-decoration-none"><i class="bi bi-cart3" style="font-size:1.2rem;"></i></a>
  </div>
</nav>

<!-- ── CONTENT ── -->
<div class="auth-bg">
  <div class="auth-card">
    <h4 class="fw-black text-center mb-1" style="font-size:1.55rem;">Login to Shakey's</h4>
    <p class="text-center text-muted mb-4" style="font-size:.85rem;">
      New user? <a href="register.php" style="color:var(--sk-red);font-weight:800;text-decoration:none;">Create an account</a>
    </p>

    <?php if ($error): ?>
    <div class="alert alert-danger py-2 text-center mb-3" style="font-size:.84rem;border-radius:4px;"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="loginForm" novalidate>

      <!-- Step 1: Email -->
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
          <a href="forgot_password.php" style="color:var(--sk-red);font-size:.82rem;font-weight:700;text-decoration:none;">Forgot password?</a>
        </div>
      </div>

      <!-- Step 2: Password -->
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
          <a href="forgot_password.php" style="color:var(--sk-red);font-size:.82rem;font-weight:700;text-decoration:none;">Forgot password?</a>
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

<!-- ── FOOTER ── -->
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
  if (inp.type === 'password') {
    inp.type = 'text';
    ico.className = 'bi bi-eye-slash';
  } else {
    inp.type = 'password';
    ico.className = 'bi bi-eye';
  }
}
</script>
</body>
</html>
