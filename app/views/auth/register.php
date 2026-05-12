<style>
.auth-bg { background:#C8181E url('https://www.shakeyspizza.ph/images/bg-image.png') center top / 150% auto no-repeat; display:flex; align-items:flex-start; justify-content:center; padding:3rem 1rem 2.5rem; min-height:calc(100vh - 220px); position:relative; overflow:hidden; }
.sk-footer { margin-top:0; }
.auth-card { background:#fff; border-radius:4px; padding:48px 44px; max-width:500px; width:100%; box-shadow:0 12px 50px rgba(0,0,0,.22); position:relative; z-index:1; }
@media(max-width:520px){ .auth-card{ padding:32px 20px; } }
.field-wrap { margin-bottom:1.5rem; }
.field-wrap label { display:block; font-size:.76rem; font-weight:800; color:#444; margin-bottom:4px; letter-spacing:.3px; }
.field-wrap input,.field-wrap select { display:block; width:100%; border:none; border-bottom:1.5px solid #d0d0d0; outline:none; padding:.45rem 0; font-size:.9rem; background:transparent; color:#222; transition:border-color .18s; border-radius:0; -webkit-appearance:none; }
.field-wrap input::placeholder { color:#bbb; font-size:.88rem; }
.field-wrap input:focus,.field-wrap select:focus { border-bottom-color:var(--sk-red); }
.field-wrap.has-eye { position:relative; }
.field-wrap.has-eye input { padding-right:28px; }
.field-wrap .eye-btn { position:absolute; right:2px; bottom:10px; background:none; border:none; padding:0; cursor:pointer; color:#bbb; font-size:1rem; line-height:1; }
.phone-row { display:flex; align-items:flex-end; border-bottom:1.5px solid #d0d0d0; transition:border-color .18s; margin-bottom:1.5rem; }
.phone-row:focus-within { border-bottom-color:var(--sk-red); }
.phone-prefix { display:flex; align-items:center; gap:4px; color:var(--sk-red); font-weight:800; font-size:.9rem; padding:.45rem .5rem .45rem 0; white-space:nowrap; cursor:pointer; flex-shrink:0; position:relative; }
.phone-prefix i { font-size:.7rem; }
.phone-prefix select { position:absolute; opacity:0; width:60px; cursor:pointer; }
.phone-input { flex:1; border:none; outline:none; padding:.45rem 0; font-size:.9rem; background:transparent; color:#222; }
.phone-input::placeholder { color:#bbb; font-size:.88rem; }
.btn-sk { display:block; width:100%; background:var(--sk-dark-red); color:#fff; border:none; border-radius:25px; font-weight:800; font-size:.95rem; padding:.75rem; cursor:pointer; transition:background .18s; letter-spacing:.3px; }
.btn-sk:hover { background:#7a0c10; }
</style>

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
          <div class="phone-prefix">
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

<script>
function toggleEye(inputId, iconId) {
  const inp = document.getElementById(inputId);
  const ico = document.getElementById(iconId);
  if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
  else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
}
</script>
