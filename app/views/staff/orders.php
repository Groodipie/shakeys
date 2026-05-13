<style>
.page-title{font-size:1.6rem;font-weight:800;color:#1a1a1a;margin:0 0 .35rem;}
.page-sub{color:#888;font-size:.9rem;margin-bottom:2rem;}
.empty-panel{background:#fff;border-radius:8px;padding:3rem 2rem;box-shadow:0 2px 8px rgba(0,0,0,.05);text-align:center;color:#888;}
.empty-panel i{font-size:3rem;color:#ddd;margin-bottom:1rem;display:block;}
.empty-panel h5{font-weight:800;color:#1a1a1a;margin-bottom:.4rem;}
.orders-panel{background:#fff;border-radius:8px;padding:1.25rem 1.5rem 1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.05);overflow-x:auto;}
.orders-table{width:100%;border-collapse:collapse;font-size:.9rem;}
.orders-table th{text-align:left;font-size:.72rem;font-weight:800;color:#888;text-transform:uppercase;letter-spacing:.5px;padding:.65rem .75rem;border-bottom:2px solid #eee;white-space:nowrap;}
.orders-table td{padding:.85rem .75rem;border-bottom:1px solid #f0f0f0;color:#333;vertical-align:top;}
.orders-table tr:last-child td{border-bottom:none;}
.status-badge{display:inline-block;font-size:.72rem;font-weight:700;padding:.2rem .55rem;border-radius:999px;text-transform:uppercase;letter-spacing:.4px;}
.status-pending{background:#fff4e0;color:#a86200;}
.status-preparing{background:#e0f0ff;color:#1f4f99;}
.status-out{background:#e8e1ff;color:#5a3fbf;}
.status-delivered{background:#e7f6ec;color:#1f7a3a;}
.status-cancelled{background:#fbecec;color:#9b1015;}
.status-default{background:#f3f3f3;color:#444;}
.amount{font-weight:700;color:#1a1a1a;white-space:nowrap;}
.muted{color:#888;font-size:.82rem;}
.flash{padding:.7rem 1rem;border-radius:6px;font-size:.88rem;font-weight:600;margin-bottom:1.25rem;}
.flash.success{background:#e7f6ec;color:#1f7a3a;border:1px solid #c8e8d3;}
.flash.error{background:#fbecec;color:#9b1015;border:1px solid #f0c8c8;}
.btn-assign{background:var(--sk-red);color:#fff;border:none;font-size:.78rem;font-weight:700;padding:.4rem .75rem;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:.35rem;white-space:nowrap;transition:background .15s;}
.btn-assign:hover{background:var(--sk-dark-red);color:#fff;}
.btn-assign.reassign{background:#eee;color:#444;}
.btn-assign.reassign:hover{background:#ddd;color:#1a1a1a;}
.rider-name{font-weight:700;color:#1a1a1a;display:block;margin-bottom:.2rem;}
.modal-content{border:none;border-radius:10px;}
.modal-header{border-bottom:1px solid #eee;padding:1rem 1.25rem;}
.modal-title{font-weight:800;color:#1a1a1a;}
.modal-body{padding:1.25rem;}
.modal-footer{border-top:1px solid #eee;padding:.85rem 1.25rem;}
.form-label{font-size:.78rem;font-weight:700;color:#444;text-transform:uppercase;letter-spacing:.4px;margin-bottom:.3rem;}
.form-select{font-size:.9rem;border-radius:6px;border:1px solid #ddd;padding:.55rem .75rem;}
.form-select:focus{border-color:var(--sk-red);box-shadow:0 0 0 .15rem rgba(200,24,30,.15);}
.btn-primary-sk{background:var(--sk-red);border:none;color:#fff;font-weight:700;padding:.55rem 1.25rem;border-radius:6px;}
.btn-primary-sk:hover{background:var(--sk-dark-red);color:#fff;}
.btn-secondary-sk{background:#eee;border:none;color:#333;font-weight:600;padding:.55rem 1.1rem;border-radius:6px;}
.btn-secondary-sk:hover{background:#ddd;color:#1a1a1a;}
</style>

<h2 class="page-title">Orders</h2>
<p class="page-sub">Customer orders placed at <strong><?= e($_SESSION['staff_branch'] ?? 'your branch') ?></strong>.</p>

<?php if (!empty($flash)): ?>
  <div class="flash <?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
  <div class="empty-panel">
    <i class="bi bi-receipt"></i>
    <h5>No orders yet</h5>
    <p>Placed orders for this branch will appear here.</p>
  </div>
<?php else: ?>
  <div class="orders-panel">
    <table class="orders-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Customer</th>
          <th>Delivery Address</th>
          <th>Payment</th>
          <th>Status</th>
          <th style="text-align:right;">Total</th>
          <th style="width:200px;">Rider</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $o):
          $status = trim((string)($o['Order_Status'] ?? ''));
          $key    = strtolower(str_replace(' ', '-', $status));
          $statusClass = match ($key) {
            'pending'         => 'status-pending',
            'preparing',
            'in-kitchen'      => 'status-preparing',
            'out-for-delivery'=> 'status-out',
            'delivered',
            'completed'       => 'status-delivered',
            'cancelled',
            'canceled'        => 'status-cancelled',
            default           => 'status-default',
          };
          $total       = (float)($o['Order_TotalAmount'] ?? 0) + (float)($o['Order_DeliveryFee'] ?? 0);
          $hasRider    = !empty($o['Dlvry_RiderID']);
        ?>
          <tr>
            <td>#<?= (int)$o['Order_ID'] ?></td>
            <td><?= e(date('M j, Y g:i A', strtotime($o['Order_Date']))) ?></td>
            <td>
              <div><?= e($o['Cust_Name'] ?? '—') ?></div>
              <?php if (!empty($o['Cust_Phone'])): ?>
                <div class="muted"><?= e($o['Cust_Phone']) ?></div>
              <?php endif; ?>
            </td>
            <td><?= e($o['Order_DeliveryAddress'] ?? '—') ?></td>
            <td>
              <div><?= e($o['Pay_Method'] ?? '—') ?></div>
              <?php if (!empty($o['Pay_Status'])): ?>
                <div class="muted"><?= e($o['Pay_Status']) ?></div>
              <?php endif; ?>
            </td>
            <td><span class="status-badge <?= e($statusClass) ?>"><?= e($status ?: '—') ?></span></td>
            <td class="amount" style="text-align:right;">&#8369;<?= number_format($total, 2) ?></td>
            <td>
              <?php if ($hasRider): ?>
                <span class="rider-name"><?= e($o['Rider_Name']) ?></span>
                <button type="button" class="btn-assign reassign js-assign-rider"
                        data-order-id="<?= (int)$o['Order_ID'] ?>"
                        data-current-rider="<?= (int)$o['Dlvry_RiderID'] ?>">
                  <i class="bi bi-arrow-repeat"></i>Reassign
                </button>
              <?php else: ?>
                <button type="button" class="btn-assign js-assign-rider"
                        data-order-id="<?= (int)$o['Order_ID'] ?>"
                        data-current-rider="">
                  <i class="bi bi-person-plus"></i>Assign Rider
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<div class="modal fade" id="assignRiderModal" tabindex="-1" aria-labelledby="assignRiderLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" id="assignRiderForm" method="post" action="">
      <div class="modal-header">
        <h5 class="modal-title" id="assignRiderLabel">Assign Rider</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label class="form-label" for="rider_id">Available Riders</label>
        <?php if (empty($riders)): ?>
          <p class="muted" style="margin:.5rem 0 0;">No riders are currently available.</p>
        <?php else: ?>
          <select class="form-select" id="rider_id" name="rider_id" required>
            <option value="">Select a rider…</option>
            <?php foreach ($riders as $r): ?>
              <option value="<?= (int)$r['Rider_ID'] ?>">
                <?= e($r['Rider_FirstName'] . ' ' . $r['Rider_LastName']) ?>
                — <?= e($r['Rider_ContactNumber']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn-secondary-sk" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn-primary-sk" <?= empty($riders) ? 'disabled' : '' ?>>Assign</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const modalEl  = document.getElementById('assignRiderModal');
  const formEl   = document.getElementById('assignRiderForm');
  const selectEl = document.getElementById('rider_id');
  const labelEl  = document.getElementById('assignRiderLabel');
  if (!modalEl || !formEl) return;
  const modal = new bootstrap.Modal(modalEl);
  const baseAction = <?= json_encode(url('/staff/orders')) ?>;

  document.querySelectorAll('.js-assign-rider').forEach(function (btn) {
    btn.addEventListener('click', function () {
      const orderId = btn.dataset.orderId;
      const current = btn.dataset.currentRider;
      formEl.action = baseAction + '/' + orderId + '/assign';
      labelEl.textContent = current ? 'Reassign Rider — Order #' + orderId : 'Assign Rider — Order #' + orderId;
      if (selectEl) selectEl.value = current || '';
      modal.show();
    });
  });
});
</script>
