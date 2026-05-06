<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Forgot Password — Shakey's Delivery</title>
<link rel="icon" type="image/png" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="apple-touch-icon" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sk-red:#C8181E;--sk-dark-red:#9B1015;}
body{margin:0;}
.auth-wrapper{min-height:100vh;background:radial-gradient(ellipse at top,#8b0000 0%,var(--sk-red) 50%,#a01010 100%);display:flex;align-items:center;justify-content:center;padding:2rem;}
.auth-card{background:#fff;border-radius:16px;padding:2.5rem 2rem;max-width:480px;width:100%;box-shadow:0 24px 80px rgba(0,0,0,.3);}
.btn-shakeys{background:var(--sk-dark-red);color:#fff;border:none;border-radius:8px;font-weight:700;padding:.75rem;width:100%;}
.btn-shakeys:hover{background:#7a0c10;color:#fff;}
</style>
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-card">
    <h4 class="fw-bold text-center mb-2">Forgot Password</h4>
    <p class="text-center text-muted mb-4" style="font-size:.88rem;line-height:1.5;">
      Enter your registered email address below and we'll send you a one-time PIN (OTP) to help you reset your password.
    </p>

    <?php if ($sent): ?>
    <div class="alert alert-success text-center py-3">
      <i class="bi bi-envelope-check fs-4 d-block mb-2"></i>
      <strong>OTP Sent!</strong><br>
      <span style="font-size:.85rem;">Check your email for the password reset PIN.</span>
    </div>
    <a href="login.php" class="btn btn-shakeys mt-2">Back to Login</a>
    <?php else: ?>
    <?php if ($error): ?>
    <div class="alert alert-danger py-2" style="font-size:.85rem;"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold" style="font-size:.84rem;">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
      </div>
      <button type="submit" class="btn btn-shakeys">Recover Password</button>
    </form>
    <div class="text-center mt-3">
      <a href="login.php" style="color:var(--sk-red);font-size:.85rem;font-weight:600;text-decoration:none;">
        ← Back to Login
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
