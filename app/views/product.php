<?php
$base       = (float)$product['Prod_BasePrice'];
$prodId     = (int)$product['Prod_ID'];
$prodName   = $product['Prod_Name'];
$crusts     = PizzaOptions::CRUSTS;
$sizes      = PizzaOptions::SIZES;
$toppings   = PizzaOptions::TOPPINGS;
$error      = $_GET['error'] ?? '';

$editing      = $editing ?? null;
$editKey      = $editKey ?? '';
$selCrust     = $editing['crust'] ?? $crusts[0];
$selSize      = $editing['size']  ?? array_key_first($sizes);
$selToppings  = $editing['toppings'] ?? [];
$selQty       = $editing['qty'] ?? 1;
?>
<style>
.pd-wrap{max-width:1100px;margin:3rem auto 6rem;padding:0 1rem;}
.pd-card{background:#fff;border-radius:14px;padding:2rem;box-shadow:0 4px 18px rgba(0,0,0,.06);}
.pd-grid{display:grid;grid-template-columns:1fr 1fr;gap:2.5rem;align-items:start;}
@media (max-width:900px){.pd-grid{grid-template-columns:1fr;}}
.pd-image{width:100%;aspect-ratio:1/1;border-radius:14px;background:#fdf0f0 url('https://www.shakeyspizza.ph/images/manager-choice.png') center/contain no-repeat;display:flex;align-items:center;justify-content:center;font-size:9rem;}
.pd-image.no-img{font-size:9rem;}
.pd-name{font-size:1.6rem;font-weight:800;margin:1rem 0 .25rem;color:#1a1a1a;}
.pd-starts{font-size:.85rem;color:#888;margin:0;}
.pd-base{color:var(--sk-red);font-weight:800;font-size:1.15rem;}
.pd-section{margin-bottom:1.6rem;}
.pd-label{font-weight:800;font-size:1rem;color:#1a1a1a;margin-bottom:.7rem;}
.pd-label .req{color:var(--sk-red);margin-right:2px;}
.pd-options{display:flex;gap:.8rem;flex-wrap:wrap;}
.pd-opt{flex:1 1 140px;border:2px solid #e0e0e0;border-radius:10px;padding:.85rem 1rem;background:#fff;cursor:pointer;font-weight:700;font-size:.95rem;text-align:center;transition:border-color .15s,background .15s;color:#1a1a1a;}
.pd-opt:hover{border-color:#bbb;}
.pd-opt input{display:none;}
.pd-opt.selected{border-color:var(--sk-red);background:#fff;}
.pd-size-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.8rem;}
@media (max-width:560px){.pd-size-grid{grid-template-columns:1fr;}}
.pd-size{border:2px solid #e0e0e0;border-radius:10px;padding:1.2rem .75rem;text-align:center;cursor:pointer;background:#fff;transition:border-color .15s;}
.pd-size:hover{border-color:#bbb;}
.pd-size input{display:none;}
.pd-size.selected{border-color:var(--sk-red);}
.pd-size-icon{font-size:2.6rem;line-height:1;color:#bbb;margin-bottom:.4rem;}
.pd-size-name{font-weight:800;font-size:1rem;color:#1a1a1a;}
.pd-size-desc{font-size:.78rem;color:#888;margin-top:.2rem;}
.pd-topping{display:flex;align-items:center;justify-content:space-between;padding:.8rem 1rem;border:1px solid #eee;border-radius:10px;margin-bottom:.55rem;cursor:pointer;background:#fff;transition:border-color .15s,background .15s;}
.pd-topping:hover{border-color:#ccc;}
.pd-topping input{margin-right:.7rem;accent-color:var(--sk-red);}
.pd-topping.selected{border-color:var(--sk-red);background:#fff8f8;}
.pd-topping-name{font-weight:600;font-size:.92rem;color:#1a1a1a;}
.pd-topping-price{color:var(--sk-red);font-weight:700;font-size:.9rem;}
.pd-bar{position:fixed;left:0;right:0;bottom:0;background:#fff;border-top:1px solid #eee;box-shadow:0 -4px 16px rgba(0,0,0,.06);padding:.85rem 1.25rem;display:flex;align-items:center;gap:1.25rem;justify-content:flex-end;z-index:40;}
.pd-qty{display:flex;align-items:center;gap:.5rem;}
.pd-qty button{width:34px;height:34px;border-radius:50%;border:1px solid #ddd;background:#fff;font-size:1.1rem;cursor:pointer;}
.pd-qty button:hover{background:#f5f5f5;}
.pd-qty input{width:42px;text-align:center;border:none;font-weight:800;font-size:1.05rem;background:transparent;}
.pd-total{display:flex;flex-direction:column;align-items:flex-end;line-height:1;}
.pd-total small{color:#888;font-size:.78rem;}
.pd-total strong{color:#1a1a1a;font-size:1.3rem;font-weight:800;margin-top:.15rem;}
.pd-add{background:var(--sk-red);color:#fff;border:none;border-radius:999px;font-weight:800;font-size:.85rem;letter-spacing:1px;padding:.85rem 2rem;cursor:pointer;transition:background .15s;}
.pd-add:hover{background:var(--sk-dark-red);}
.pd-add:disabled{background:#ccc;cursor:not-allowed;}
.pd-error{background:#fdecea;border:1px solid #f5c6cb;color:#7a1b1b;padding:.7rem 1rem;border-radius:8px;margin-bottom:1rem;font-size:.9rem;}
.pd-back{display:inline-flex;align-items:center;gap:.4rem;color:#555;text-decoration:none;font-weight:600;font-size:.9rem;margin-bottom:.75rem;}
.pd-back:hover{color:var(--sk-red);}
</style>

<div class="pd-wrap">
  <?php if ($editKey !== ''): ?>
    <a href="<?= e(url('/cart')) ?>" class="pd-back"><i class="bi bi-arrow-left"></i> Back to cart</a>
  <?php else: ?>
    <a href="<?= e(url('/menu')) ?>" class="pd-back"><i class="bi bi-arrow-left"></i> Back to menu</a>
  <?php endif; ?>

  <?php if ($error === 'invalid'): ?>
    <div class="pd-error">Please select a valid crust and size.</div>
  <?php endif; ?>

  <div class="pd-card">
    <form method="POST" action="<?= e(url('/product?id=' . $prodId)) ?>" id="pdForm">
      <?php if ($editKey !== ''): ?>
        <input type="hidden" name="edit_key" value="<?= e($editKey) ?>">
      <?php endif; ?>
      <div class="pd-grid">
        <div>
          <div class="pd-image">🍕</div>
          <h2 class="pd-name"><?= e($prodName) ?></h2>
          <p class="pd-starts">Starts at <span class="pd-base">₱<?= number_format($base, 2) ?></span></p>
        </div>

        <div>
          <div class="pd-section">
            <div class="pd-label"><span class="req">*</span>Crust</div>
            <div class="pd-options">
              <?php foreach ($crusts as $c): $isSel = ($c === $selCrust); ?>
              <label class="pd-opt <?= $isSel ? 'selected' : '' ?>" data-group="crust">
                <input type="radio" name="crust" value="<?= e($c) ?>" <?= $isSel ? 'checked' : '' ?>>
                <?= e($c) ?>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="pd-section">
            <div class="pd-label"><span class="req">*</span>Size</div>
            <div class="pd-size-grid">
              <?php foreach ($sizes as $name => $info): $isSel = ($name === $selSize); ?>
              <label class="pd-size <?= $isSel ? 'selected' : '' ?>" data-group="size" data-mult="<?= $info['multiplier'] ?>">
                <input type="radio" name="size" value="<?= e($name) ?>" <?= $isSel ? 'checked' : '' ?>>
                <div class="pd-size-icon"><i class="bi bi-pie-chart-fill"></i></div>
                <div class="pd-size-name"><?= e($name) ?></div>
                <div class="pd-size-desc"><?= e($info['desc']) ?></div>
              </label>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="pd-section">
            <div class="pd-label">Additional Toppings <span id="topSizeLabel" style="font-weight:600;color:#888;">(<?= e($selSize) ?>)</span></div>
            <div id="toppingList">
              <?php foreach ($toppings as $tname => $tprice):
                $isSel = in_array($tname, $selToppings, true);
              ?>
              <label class="pd-topping <?= $isSel ? 'selected' : '' ?>" data-base="<?= $tprice ?>">
                <span style="display:flex;align-items:center;">
                  <input type="checkbox" name="toppings[]" value="<?= e($tname) ?>" <?= $isSel ? 'checked' : '' ?>>
                  <span class="pd-topping-name"><?= e($selSize) ?> <?= e($tname) ?></span>
                </span>
                <span class="pd-topping-price">+₱<span class="t-price"><?= number_format($tprice, 2) ?></span></span>
              </label>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>

      <div class="pd-bar">
        <div class="pd-qty">
          <button type="button" id="qtyMinus">−</button>
          <input type="text" name="qty" id="qty" value="<?= (int)$selQty ?>" readonly>
          <button type="button" id="qtyPlus">+</button>
        </div>
        <div class="pd-total">
          <small>Total</small>
          <strong>₱<span id="pdTotal"><?= number_format($base, 2) ?></span></strong>
        </div>
        <button type="submit" class="pd-add"><?= $editKey !== '' ? 'UPDATE CART' : 'ADD TO CART' ?></button>
      </div>
    </form>
  </div>
</div>

<script>
(function(){
  const BASE = <?= json_encode($base) ?>;
  const SIZES = <?= json_encode($sizes) ?>;
  const form = document.getElementById('pdForm');

  function selectedRadio(name){ return form.querySelector(`input[name="${name}"]:checked`); }
  function selectedSize(){ return selectedRadio('size')?.value || 'Regular'; }
  function sizeMult(){ return parseFloat(SIZES[selectedSize()]?.multiplier ?? 1); }

  function refresh(){
    const mult = sizeMult();
    const sizeName = selectedSize();
    document.getElementById('topSizeLabel').textContent = '(' + sizeName + ')';

    let toppingsTotal = 0;
    document.querySelectorAll('#toppingList .pd-topping').forEach(label => {
      const baseT = parseFloat(label.dataset.base);
      const scaled = +(baseT * mult).toFixed(2);
      label.querySelector('.t-price').textContent = scaled.toFixed(2);
      const nameEl = label.querySelector('.pd-topping-name');
      const tname  = label.querySelector('input').value;
      nameEl.textContent = sizeName + ' ' + tname;
      const cb = label.querySelector('input');
      label.classList.toggle('selected', cb.checked);
      if (cb.checked) toppingsTotal += scaled;
    });

    const unit = +(BASE * mult).toFixed(2);
    const qty  = parseInt(document.getElementById('qty').value, 10) || 1;
    const total = +((unit + toppingsTotal) * qty).toFixed(2);
    document.getElementById('pdTotal').textContent = total.toLocaleString('en-PH', {minimumFractionDigits:2, maximumFractionDigits:2});
  }

  // Selected styling for crust + size
  form.querySelectorAll('.pd-opt input, .pd-size input').forEach(input => {
    input.addEventListener('change', () => {
      const group = input.name;
      form.querySelectorAll(`[data-group="${group}"]`).forEach(el => el.classList.remove('selected'));
      input.closest('[data-group]').classList.add('selected');
      refresh();
    });
  });

  form.querySelectorAll('#toppingList input').forEach(cb => cb.addEventListener('change', refresh));

  // Quantity stepper
  const qtyEl = document.getElementById('qty');
  document.getElementById('qtyMinus').addEventListener('click', () => {
    let v = parseInt(qtyEl.value, 10) || 1;
    if (v > 1) qtyEl.value = v - 1;
    refresh();
  });
  document.getElementById('qtyPlus').addEventListener('click', () => {
    let v = parseInt(qtyEl.value, 10) || 1;
    if (v < 99) qtyEl.value = v + 1;
    refresh();
  });

  refresh();
})();
</script>
