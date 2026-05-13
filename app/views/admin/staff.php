<style>
.page-title{font-size:1.6rem;font-weight:800;color:#1a1a1a;margin:0 0 .35rem;}
.page-sub{color:#888;font-size:.9rem;margin-bottom:2rem;}
.empty-panel{background:#fff;border-radius:8px;padding:3rem 2rem;box-shadow:0 2px 8px rgba(0,0,0,.05);text-align:center;color:#888;}
.empty-panel i{font-size:3rem;color:#ddd;margin-bottom:1rem;display:block;}
.empty-panel h5{font-weight:800;color:#1a1a1a;margin-bottom:.4rem;}
.staff-panel{background:#fff;border-radius:8px;padding:1.25rem 1.5rem 1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.staff-table{width:100%;border-collapse:collapse;font-size:.9rem;}
.staff-table th{text-align:left;font-size:.72rem;font-weight:800;color:#888;text-transform:uppercase;letter-spacing:.5px;padding:.65rem .75rem;border-bottom:2px solid #eee;}
.staff-table td{padding:.85rem .75rem;border-bottom:1px solid #f0f0f0;color:#333;}
.staff-table tr:last-child td{border-bottom:none;}
.role-badge{display:inline-block;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:999px;background:#f3f3f3;color:#444;text-transform:uppercase;letter-spacing:.4px;}
.btn-action{background:none;border:none;color:#bbb;padding:.25rem .4rem;cursor:pointer;font-size:1rem;border-radius:4px;transition:color .15s,background .15s;}
.btn-action.edit:hover{color:#1f6feb;background:#eaf2fd;}
.btn-action.delete:hover{color:var(--sk-red);background:#fbecec;}
.flash{padding:.7rem 1rem;border-radius:6px;font-size:.88rem;font-weight:600;margin-bottom:1.25rem;}
.flash.success{background:#e7f6ec;color:#1f7a3a;border:1px solid #c8e8d3;}
.flash.error{background:#fbecec;color:#9b1015;border:1px solid #f0c8c8;}
.modal-content{border:none;border-radius:10px;}
.modal-header{border-bottom:1px solid #eee;padding:1rem 1.25rem;}
.modal-title{font-weight:800;color:#1a1a1a;}
.modal-body{padding:1.25rem;}
.modal-footer{border-top:1px solid #eee;padding:.85rem 1.25rem;}
.form-label{font-size:.78rem;font-weight:700;color:#444;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.3rem;}
.form-control,.form-select{font-size:.9rem;border-radius:6px;border:1px solid #ddd;padding:.55rem .75rem;}
.form-control:focus,.form-select:focus{border-color:var(--sk-red);box-shadow:0 0 0 .15rem rgba(200,24,30,.15);}
.btn-primary-sk{background:var(--sk-red);border:none;color:#fff;font-weight:700;padding:.55rem 1.25rem;border-radius:6px;}
.btn-primary-sk:hover{background:var(--sk-dark-red);color:#fff;}
.btn-secondary-sk{background:#eee;border:none;color:#333;font-weight:600;padding:.55rem 1.1rem;border-radius:6px;}
.btn-secondary-sk:hover{background:#ddd;color:#1a1a1a;}
</style>

<button type="button" class="page-add-btn" data-bs-toggle="modal" data-bs-target="#addStaffModal">
  <i class="bi bi-plus-lg"></i><span>Add Employee</span>
</button>

<h2 class="page-title">Employee Management</h2>
<p class="page-sub">Manage branch employees and their roles.</p>

<?php if (!empty($flash)): ?>
  <div class="flash <?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<?php if (empty($staff)): ?>
  <div class="empty-panel">
    <i class="bi bi-people"></i>
    <h5>No employees yet</h5>
    <p>Click <strong>+ Add Employee</strong> to register your first employee.</p>
  </div>
<?php else: ?>
  <div class="staff-panel">
    <table class="staff-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Role</th>
          <th>Branch</th>
          <th style="width:100px;text-align:right;">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($staff as $row): ?>
          <tr>
            <td><?= (int)$row['Emp_ID'] ?></td>
            <td><?= e($row['Emp_FirstName'] . ' ' . $row['Emp_LastName']) ?></td>
            <td><?= e($row['Emp_Phone']) ?></td>
            <td><span class="role-badge"><?= e($row['Emp_Role']) ?></span></td>
            <td><?= e($row['Brnch_Name'] ?? '—') ?></td>
            <td style="text-align:right;white-space:nowrap;">
              <button type="button" class="btn-action edit js-edit-staff"
                      data-id="<?= (int)$row['Emp_ID'] ?>"
                      data-first="<?= e($row['Emp_FirstName']) ?>"
                      data-last="<?= e($row['Emp_LastName']) ?>"
                      data-phone="<?= e($row['Emp_Phone']) ?>"
                      data-role="<?= e($row['Emp_Role']) ?>"
                      data-branch="<?= (int)$row['Emp_BrnchID'] ?>"
                      aria-label="Edit">
                <i class="bi bi-pencil"></i>
              </button>
              <form method="post" action="<?= e(url('/admin/staff/' . $row['Emp_ID'] . '/delete')) ?>"
                    onsubmit="return confirm('Remove this employee?');" style="display:inline;">
                <button type="submit" class="btn-action delete" aria-label="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" method="post" action="<?= e(url('/admin/staff')) ?>">
      <div class="modal-header">
        <h5 class="modal-title" id="addStaffLabel">Add Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required maxlength="20" placeholder="+639XXXXXXXXX">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="role">Role</label>
            <select class="form-select" id="role" name="role" required>
              <option value="">Select role…</option>
              <option value="Branch Manager">Branch Manager</option>
              <option value="Staff">Staff</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label" for="branch_id">Branch</label>
            <select class="form-select" id="branch_id" name="branch_id" required>
              <option value="">Select branch…</option>
              <?php foreach (($branches ?? []) as $b): ?>
                <option value="<?= (int)$b['Brnch_ID'] ?>"><?= e($b['Brnch_Name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary-sk" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-primary-sk">Add Employee</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <form class="modal-content" id="editStaffForm" method="post" action="">
      <div class="modal-header">
        <h5 class="modal-title" id="editStaffLabel">Edit Employee</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label" for="edit_first_name">First Name</label>
            <input type="text" class="form-control" id="edit_first_name" name="first_name" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="edit_last_name">Last Name</label>
            <input type="text" class="form-control" id="edit_last_name" name="last_name" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="edit_phone">Phone</label>
            <input type="text" class="form-control" id="edit_phone" name="phone" required maxlength="20">
          </div>
          <div class="col-md-6">
            <label class="form-label" for="edit_role">Role</label>
            <select class="form-select" id="edit_role" name="role" required>
              <option value="Branch Manager">Branch Manager</option>
              <option value="Staff">Staff</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label" for="edit_branch_id">Branch</label>
            <select class="form-select" id="edit_branch_id" name="branch_id" required>
              <?php foreach (($branches ?? []) as $b): ?>
                <option value="<?= (int)$b['Brnch_ID'] ?>"><?= e($b['Brnch_Name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary-sk" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-primary-sk">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const editModalEl = document.getElementById('editStaffModal');
  const editModal   = new bootstrap.Modal(editModalEl);
  const form        = document.getElementById('editStaffForm');
  const baseUrl     = <?= json_encode(url('/admin/staff')) ?>;

  document.querySelectorAll('.js-edit-staff').forEach(function (btn) {
    btn.addEventListener('click', function () {
      form.action = baseUrl + '/' + btn.dataset.id + '/update';
      document.getElementById('edit_first_name').value = btn.dataset.first;
      document.getElementById('edit_last_name').value  = btn.dataset.last;
      document.getElementById('edit_phone').value      = btn.dataset.phone;
      document.getElementById('edit_role').value       = btn.dataset.role;
      document.getElementById('edit_branch_id').value  = btn.dataset.branch;
      editModal.show();
    });
  });
});
</script>

<?php if (!empty($flash) && $flash['type'] === 'error'): ?>
<script>document.addEventListener('DOMContentLoaded',()=>new bootstrap.Modal(document.getElementById('addStaffModal')).show());</script>
<?php endif; ?>
