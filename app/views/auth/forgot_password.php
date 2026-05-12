<style>
.auth-bg { background:#C8181E url('https://www.shakeyspizza.ph/images/bg-image.png') center top / 150% auto no-repeat; display:flex; align-items:flex-start; justify-content:center; padding:3rem 1rem 2.5rem; min-height:calc(100vh - 220px); position:relative; overflow:hidden; }
.sk-footer { margin-top:0; }
.auth-card { background:#fff; border-radius:16px; padding:2.5rem 2rem; max-width:480px; width:100%; box-shadow:0 24px 80px rgba(0,0,0,.3); position:relative; z-index:1; }
.btn-shakeys { background:var(--sk-dark-red); color:#fff; border:none; border-radius:8px; font-weight:700; padding:.75rem; width:100%; }
.btn-shakeys:hover { background:#7a0c10; color:#fff; }
</style>

<div class="auth-bg">
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
    <a href="<?= e(url('/login')) ?>" class="btn btn-shakeys mt-2">Back to Login</a>
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
      <a href="<?= e(url('/login')) ?>" style="color:var(--sk-red);font-size:.85rem;font-weight:600;text-decoration:none;">
        ← Back to Login
      </a>
    </div>
    <?php endif; ?>
  </div>
</div>
