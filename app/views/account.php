<div class="container-fluid px-3 px-md-4 py-3"
     style="background:radial-gradient(ellipse at top,#8b0000 0%,var(--sk-red) 50%,#a01010 100%);min-height:calc(100vh - 120px);">

  <h4 class="text-white fw-bold text-center mb-4 pt-2">My Account</h4>

  <div class="mx-auto" style="max-width:560px;">

    <?php if ($success): ?>
    <div class="alert alert-success py-2 mb-3" style="font-size:.85rem;"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
    <div class="alert alert-danger py-2 mb-3" style="font-size:.85rem;"><?= e($error) ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-4 overflow-hidden shadow-lg mb-3">
      <div class="py-4 px-4 text-center" style="background:var(--sk-gold);">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold"
             style="width:64px;height:64px;background:#555;color:#fff;font-size:1.5rem;">
          <?= e(strtoupper(($cust['Cust_FirstName'][0] ?? 'U'))) ?>
        </div>
        <h5 class="fw-bold mb-0"><?= e($cust['Cust_FirstName'] . ' ' . $cust['Cust_LastName']) ?></h5>
        <small><?= e($cust['Cust_Phone']) ?></small><br>
        <small><?= e($cust['Cust_Email']) ?></small>
      </div>

      <div class="d-flex border-bottom">
        <div class="flex-fill text-center py-3 border-end">
          <div class="fw-bold fs-5" style="color:var(--sk-red);"><?= $totalOrders ?></div>
          <small class="text-muted">Total Orders</small>
        </div>
        <div class="flex-fill text-center py-3">
          <div class="fw-bold fs-5" style="color:var(--sk-red);"><?= date('Y', strtotime($cust['Cust_CreatedDate'])) ?></div>
          <small class="text-muted">Member Since</small>
        </div>
      </div>

      <div class="px-4 pt-3 pb-1">
        <h6 class="fw-bold mb-3">My Account</h6>
        <?php
        $rows = [
          ['bi-person',   'Name',          $cust['Cust_FirstName'] . ' ' . $cust['Cust_LastName']],
          ['bi-phone',    'Mobile Number', $cust['Cust_Phone']],
          ['bi-envelope', 'Email Address', $cust['Cust_Email']],
          ['bi-geo-alt',  'Address',       $cust['Cust_Address']],
        ];
        foreach ($rows as [$icon, $label, $val]): ?>
        <div class="d-flex gap-3 align-items-center py-2 border-bottom">
          <i class="bi <?= $icon ?> text-muted" style="width:20px;"></i>
          <div>
            <div style="font-size:.75rem;color:#aaa;"><?= e($label) ?></div>
            <div class="fw-semibold" style="font-size:.9rem;"><?= e($val) ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="px-4 py-3">
        <h6 class="fw-bold mb-3">Account Settings</h6>
        <div class="d-grid gap-2">
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
            <i class="bi bi-pencil-square"></i> Edit Profile
          </button>
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#changePassModal">
            <i class="bi bi-lock"></i> Change Password
          </button>
          <a href="/logout" class="btn d-flex align-items-center gap-2 justify-content-center" style="background:var(--sk-red);color:#fff;border-radius:8px;">
            <i class="bi bi-box-arrow-right"></i> Log Out
          </a>
          <button class="btn d-flex align-items-center gap-2 justify-content-center" style="border:1px solid var(--sk-red);color:var(--sk-red);background:none;" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash"></i> Delete Account
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Edit Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="action" value="update_profile">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.84rem;">First name</label>
              <input type="text" name="first_name" class="form-control" value="<?= e($cust['Cust_FirstName']) ?>" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.84rem;">Last name</label>
              <input type="text" name="last_name" class="form-control" value="<?= e($cust['Cust_LastName']) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold" style="font-size:.84rem;">Phone</label>
              <input type="text" name="phone" class="form-control" value="<?= e($cust['Cust_Phone']) ?>" required>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold" style="font-size:.84rem;">Address</label>
              <input type="text" name="address" class="form-control" value="<?= e($cust['Cust_Address']) ?>" required>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn fw-bold px-4" style="background:var(--sk-red);color:#fff;">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePassModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="action" value="change_password">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.84rem;">New Password</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-0">
            <label class="form-label fw-semibold" style="font-size:.84rem;">Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn fw-bold px-4" style="background:var(--sk-red);color:#fff;">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-danger">Delete Account</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted">Are you sure you want to delete your account? This action cannot be undone.</p>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="/logout?delete=1" class="btn btn-danger fw-bold">Yes, Delete</a>
      </div>
    </div>
  </div>
</div>
