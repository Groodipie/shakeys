<?php
$steps = ['Pending'=>0,'Preparing'=>1,'Ready'=>2,'In Transit'=>3,'Delivered'=>4];
$badgeClass = [
    'Pending'    => 'badge-pending',
    'Preparing'  => 'badge-preparing',
    'Ready'      => 'bg-info text-dark',
    'In Transit' => 'bg-warning text-dark',
    'Delivered'  => 'badge-delivered',
    'Cancelled'  => 'badge-cancelled',
];
?>

<div class="container-fluid px-3 px-md-4 py-3"
     style="background:radial-gradient(ellipse at top,#8b0000 0%,var(--sk-red) 50%,#a01010 100%);min-height:calc(100vh - 120px);">

  <h4 class="text-white fw-bold text-center mb-4 pt-2">Order Tracker</h4>

  <?php if (empty($orderList)): ?>
  <div class="mx-auto" style="max-width:480px;">
    <div class="bg-white rounded-4 p-5 text-center shadow-lg">
      <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
           style="width:100px;height:100px;background:#f0f0f5;font-size:3.5rem;">📦</div>
      <h5 class="fw-bold mb-2">You currently have no ongoing orders</h5>
      <p class="text-muted mb-4" style="font-size:.88rem;">Craving for something delicious? Order from us below!</p>
      <a href="menu.php" class="btn fw-bold px-4 py-2" style="background:var(--sk-red);color:#fff;border-radius:8px;">
        Add a New Order
      </a>
    </div>
  </div>

  <?php else: ?>
  <div class="mx-auto" style="max-width:800px;">
    <?php foreach ($orderList as $ord):
      $step  = $steps[$ord['Order_Status']] ?? 0;
      $badge = $badgeClass[$ord['Order_Status']] ?? 'bg-secondary';
      $orderItems = $orderModel->items($ord['Order_ID']);
    ?>
    <div class="bg-white rounded-4 p-4 mb-3 shadow-sm">
      <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
        <div>
          <h6 class="fw-bold mb-1">Order #<?= str_pad($ord['Order_ID'],5,'0',STR_PAD_LEFT) ?></h6>
          <small class="text-muted"><?= date('M d, Y h:i A', strtotime($ord['Order_Date'])) ?></small>
          <?php if ($ord['Brnch_Name']): ?>
          <div class="text-muted mt-1" style="font-size:.8rem;"><i class="bi bi-shop me-1"></i><?= e($ord['Brnch_Name']) ?></div>
          <?php endif; ?>
        </div>
        <span class="badge px-3 py-2 <?= $badge ?>" style="font-size:.8rem;"><?= e($ord['Order_Status']) ?></span>
      </div>

      <?php if ($ord['Order_Status'] !== 'Cancelled'): ?>
      <div class="mb-3">
        <div class="d-flex justify-content-between text-muted mb-1" style="font-size:.72rem;">
          <?php foreach (['Pending','Preparing','Ready','In Transit','Delivered'] as $i => $s): ?>
          <span class="<?= $i <= $step ? 'fw-bold' : '' ?>" style="color:<?= $i <= $step ? 'var(--sk-red)' : 'inherit' ?>;"><?= $s ?></span>
          <?php endforeach; ?>
        </div>
        <div class="progress" style="height:6px;">
          <div class="progress-bar" style="width:<?= ($step/4)*100 ?>%;background:var(--sk-red);border-radius:10px;"></div>
        </div>
      </div>
      <?php endif; ?>

      <div class="mb-3">
        <?php foreach ($orderItems as $oi): ?>
        <div class="d-flex justify-content-between align-items-center py-1 border-bottom">
          <span style="font-size:.88rem;">
            <span class="me-2">🍕</span>
            <?= e($oi['Prod_Name']) ?>
            <small class="text-muted ms-1"><?= $oi['OItem_CrustType'] ? '(' . e($oi['OItem_CrustType']) . ')' : '' ?></small>
            × <?= $oi['OItem_Qty'] ?>
          </span>
          <span class="fw-bold" style="font-size:.88rem;">₱<?= number_format(($oi['OItem_UnitPrice']+$oi['OItem_AddToppings'])*$oi['OItem_Qty'],2) ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div style="font-size:.82rem;" class="text-muted">
          <?php if ($ord['Pay_Method']): ?>
          <i class="bi bi-credit-card me-1"></i><?= e($ord['Pay_Method']) ?>
          <span class="badge ms-1 <?= $ord['Pay_Status'] === 'Paid' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size:.7rem;"><?= e($ord['Pay_Status'] ?? 'Pending') ?></span>
          <?php endif; ?>
          <?php if ($ord['Rider_Name']): ?>
          <span class="ms-3"><i class="bi bi-person-circle me-1"></i><?= e($ord['Rider_Name']) ?></span>
          <?php endif; ?>
        </div>
        <div class="text-end">
          <div class="text-muted" style="font-size:.78rem;">Delivery: ₱<?= number_format($ord['Order_DeliveryFee'],2) ?></div>
          <div class="fw-bold" style="color:var(--sk-red);">Total: ₱<?= number_format($ord['Order_TotalAmount'],2) ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="text-center mt-3 pb-4">
      <a href="menu.php" class="btn fw-bold px-4" style="background:var(--sk-red);color:#fff;border-radius:8px;">+ Add a New Order</a>
    </div>
  </div>
  <?php endif; ?>
</div>
