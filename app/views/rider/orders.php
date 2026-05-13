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
.status-ready{background:#e8f4ff;color:#1f4f99;}
.status-out{background:#e8e1ff;color:#5a3fbf;}
.status-delivered{background:#e7f6ec;color:#1f7a3a;}
.status-cancelled{background:#fbecec;color:#9b1015;}
.status-default{background:#f3f3f3;color:#444;}
.amount{font-weight:700;color:#1a1a1a;white-space:nowrap;}
.muted{color:#888;font-size:.82rem;}
.flash{padding:.7rem 1rem;border-radius:6px;font-size:.88rem;font-weight:600;margin-bottom:1.25rem;}
.flash.success{background:#e7f6ec;color:#1f7a3a;border:1px solid #c8e8d3;}
.flash.error{background:#fbecec;color:#9b1015;border:1px solid #f0c8c8;}
.btn-out{background:var(--sk-red);color:#fff;border:none;font-size:.78rem;font-weight:700;padding:.4rem .75rem;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:.35rem;white-space:nowrap;transition:background .15s;}
.btn-out:hover{background:var(--sk-dark-red);color:#fff;}
.btn-delivered{background:#1f7a3a;color:#fff;border:none;font-size:.78rem;font-weight:700;padding:.4rem .75rem;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:.35rem;white-space:nowrap;transition:background .15s;}
.btn-delivered:hover{background:#175d2c;color:#fff;}
.action-none{color:#bbb;font-size:.78rem;}
</style>

<h2 class="page-title">Assigned Orders</h2>
<p class="page-sub">Orders assigned to <strong><?= e($_SESSION['rider_name'] ?? 'you') ?></strong>.</p>

<?php if (!empty($flash)): ?>
  <div class="flash <?= e($flash['type']) ?>"><?= e($flash['msg']) ?></div>
<?php endif; ?>

<?php if (empty($orders)): ?>
  <div class="empty-panel">
    <i class="bi bi-receipt"></i>
    <h5>No orders assigned</h5>
    <p>Orders assigned to you will appear here.</p>
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
          <th>Branch</th>
          <th>Payment</th>
          <th>Status</th>
          <th style="text-align:right;">Total</th>
          <th style="width:160px;">Action</th>
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
            'ready'           => 'status-ready',
            'out-for-delivery'=> 'status-out',
            'delivered',
            'completed'       => 'status-delivered',
            'cancelled',
            'canceled'        => 'status-cancelled',
            default           => 'status-default',
          };
          $total = (float)($o['Order_TotalAmount'] ?? 0) + (float)($o['Order_DeliveryFee'] ?? 0);
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
            <td><?= e($o['Brnch_Name'] ?? '—') ?></td>
            <td>
              <div><?= e($o['Pay_Method'] ?? '—') ?></div>
              <?php if (!empty($o['Pay_Status'])): ?>
                <div class="muted"><?= e($o['Pay_Status']) ?></div>
              <?php endif; ?>
            </td>
            <td><span class="status-badge <?= e($statusClass) ?>"><?= e($status ?: '—') ?></span></td>
            <td class="amount" style="text-align:right;">&#8369;<?= number_format($total, 2) ?></td>
            <td>
              <?php if ($status === 'Ready'): ?>
                <form method="post" action="<?= e(url('/rider/orders/' . (int)$o['Order_ID'] . '/out_for_delivery')) ?>">
                  <button type="submit" class="btn-out">
                    <i class="bi bi-truck"></i>Out for Delivery
                  </button>
                </form>
              <?php elseif ($status === 'In Transit'): ?>
                <form method="post" action="<?= e(url('/rider/orders/' . (int)$o['Order_ID'] . '/delivered')) ?>">
                  <button type="submit" class="btn-delivered">
                    <i class="bi bi-check2-circle"></i>Mark as Delivered
                  </button>
                </form>
              <?php else: ?>
                <span class="action-none">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
