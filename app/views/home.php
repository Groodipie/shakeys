<?php
$emojiMap = ['Pizza'=>'🍕','Chicken'=>'🍗','Pasta'=>'🍝','Beverage'=>'🥤','Bundle'=>'🎉','Sides'=>'🍟','Default'=>'🍽️'];
?>

<div class="container-fluid px-3 px-md-4 py-3">

  <!-- Hero Banner Carousel (peek style) -->
  <?php
  // Image-based banner slides. Drop banner images into public/assets/img/promos/
  $bannerSlides = [
    ['image' => 'assets/img/promos/hbo.png',          'alt' => 'H·B·O — Home Bonding Offer ₱999', 'href' => 'menu.php?category=Promos'],
    ['image' => 'assets/img/promos/late-night.png',   'alt' => 'Satisfy your late night cravings', 'href' => 'menu.php?category=Promos'],
    ['image' => 'assets/img/promos/supercard.png',    'alt' => 'Shakey\'s Supercard — Free Pizza & More', 'href' => 'promos.php'],
    ['image' => 'assets/img/promos/banner4.png',      'alt' => 'Shakey\'s Promo', 'href' => 'promos.php'],
    ['image' => 'assets/img/promos/banner5.png',      'alt' => 'Shakey\'s Promo', 'href' => 'promos.php'],
    ['image' => 'assets/img/promos/banner6.png',      'alt' => 'Shakey\'s Promo', 'href' => 'promos.php'],
    ['image' => 'assets/img/promos/banner7.png',      'alt' => 'Shakey\'s Promo', 'href' => 'promos.php'],
    ['image' => 'assets/img/promos/banner8.png',      'alt' => 'Shakey\'s Promo', 'href' => 'promos.php'],
  ];
  $heroThemes = [
    ['t'=>'late',  'emoji'=>'🍕'],
    ['t'=>'hbo',   'emoji'=>'🍗'],
    ['t'=>'super', 'emoji'=>'🎉'],
  ];
  // Build final slide list: image banners first, then any active DB promos as themed text slides.
  $textSlides = [];
  foreach ($activePromos ?? [] as $i => $p) {
    $textSlides[] = $p;
  }
  $totalSlides = count($bannerSlides) + count($textSlides);
  ?>
  <div class="bigcar mt-4 mb-4">
    <button type="button" class="bigcar-arrow bigcar-prev" aria-label="Previous slide">&#8249;</button>
    <div class="bigcar-viewport">
      <div class="bigcar-track" id="bigcarTrack">
        <?php foreach ($bannerSlides as $i => $b):
          $imgPath = __DIR__ . '/../../public/' . $b['image'];
          $hasImage = file_exists($imgPath);
        ?>
        <a href="<?= e($b['href']) ?>" class="bigcar-slide bigcar-slide-img <?= $hasImage ? '' : 'bigcar-slide-missing' ?>"<?= $hasImage ? ' style="background-image:url(\''.e($b['image']).'\');"' : '' ?>>
          <?php if (!$hasImage): ?>
            <div class="bigcar-placeholder">
              <div class="bigcar-placeholder-icon">🖼️</div>
              <div class="bigcar-placeholder-text">Add image at<br><code>public/<?= e($b['image']) ?></code></div>
            </div>
          <?php else: ?>
            <span class="visually-hidden"><?= e($b['alt']) ?></span>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <?php foreach ($textSlides as $i => $p):
          $th = $heroThemes[$i % count($heroThemes)];
          $isFixed   = ($p['Promo_Discount'] ?? '') === 'Fixed';
          $headline  = $isFixed ? '₱'.number_format($p['Promo_DiscountValue'], 0).' OFF' : intval($p['Promo_DiscountValue']).'% OFF';
          $saveLabel = $isFixed ? 'Limited Time' : 'Save Big';
        ?>
        <div class="bigcar-slide bigcar-theme-<?= $th['t'] ?>">
          <div class="bigcar-deco bigcar-deco-tl"></div>
          <div class="bigcar-deco bigcar-deco-br"></div>
          <div class="bigcar-content">
            <p class="bigcar-eyebrow"><?= e($p['Promo_Category']) ?></p>
            <h2 class="bigcar-title"><?= e($p['Promo_Code']) ?></h2>
            <p class="bigcar-desc"><?= e($p['Promo_Description']) ?></p>
            <div class="bigcar-cta">
              <?php if ($headline): ?><span class="bigcar-price"><?= $headline ?></span><?php endif; ?>
              <span class="bigcar-save"><?= e($saveLabel) ?></span>
              <a href="menu.php?category=Promos" class="bigcar-btn">Order Now</a>
            </div>
          </div>
          <div class="bigcar-art"><?= $th['emoji'] ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <button type="button" class="bigcar-arrow bigcar-next" aria-label="Next slide">&#8250;</button>
    <?php if ($totalSlides > 1): ?>
    <div class="bigcar-dots" id="bigcarDots">
      <?php for ($i = 0; $i < $totalSlides; $i++): ?>
      <button type="button" class="bigcar-dot <?= $i === 0 ? 'active' : '' ?>" data-i="<?= $i ?>" aria-label="Go to slide <?= $i+1 ?>"></button>
      <?php endfor; ?>
    </div>
    <?php endif; ?>
  </div>
  <script>
  (function(){
    const track = document.getElementById('bigcarTrack');
    if(!track) return;
    const originals = [...track.querySelectorAll('.bigcar-slide')];
    const total = originals.length;
    if(total === 0) return;

    // Clone last and first slides for infinite peek loop
    if(total > 1){
      const firstClone = originals[0].cloneNode(true);
      const lastClone  = originals[total - 1].cloneNode(true);
      firstClone.setAttribute('aria-hidden', 'true');
      lastClone.setAttribute('aria-hidden',  'true');
      track.appendChild(firstClone);
      track.insertBefore(lastClone, originals[0]);
    }

    const slides = [...track.querySelectorAll('.bigcar-slide')];
    const dots   = document.querySelectorAll('#bigcarDots .bigcar-dot');
    const prev   = document.querySelector('.bigcar-prev');
    const next   = document.querySelector('.bigcar-next');
    let idx = total > 1 ? 1 : 0;  // first real slide
    let busy = false;

    function realIdx(){
      if(total <= 1) return 0;
      if(idx === 0)            return total - 1;
      if(idx === total + 1)    return 0;
      return idx - 1;
    }
    function layout(animate){
      const slideW = slides[0].offsetWidth;             // unscaled layout width
      const viewW  = track.parentElement.offsetWidth;   // viewport width
      const gap    = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 0;
      const offset = (viewW - slideW) / 2;
      if(!animate){
        track.style.transition = 'none';
      }
      track.style.transform = `translateX(${offset - idx * (slideW + gap)}px)`;
      if(!animate){
        track.offsetHeight;            // force reflow
        track.style.transition = '';
      }
      const r = realIdx();
      slides.forEach((s,n)=>s.classList.toggle('is-active', n === idx));
      dots.forEach((d,n)=>d.classList.toggle('active', n === r));
    }
    function go(target){
      if(busy) return;
      busy = true;
      idx = target;
      layout(true);
    }

    track.addEventListener('transitionend', ()=>{
      busy = false;
      if(total <= 1) return;
      if(idx === total + 1){ idx = 1;     layout(false); }
      else if(idx === 0)   { idx = total; layout(false); }
    });

    prev && prev.addEventListener('click', ()=>go(idx - 1));
    next && next.addEventListener('click', ()=>go(idx + 1));
    dots.forEach(d => d.addEventListener('click', ()=>go(parseInt(d.dataset.i,10) + 1)));
    window.addEventListener('resize', ()=>layout(false));

    let timer = null;
    function start(){ if(total > 1) timer = setInterval(()=>go(idx + 1), 6000); }
    function stop(){ if(timer){ clearInterval(timer); timer = null; } }
    track.parentElement.addEventListener('mouseenter', stop);
    track.parentElement.addEventListener('mouseleave', start);

    layout(false);
    start();
  })();
  </script>

  <!-- Recommended -->
  <h5 class="section-title mb-3">Recommended for you</h5>
  <div class="row g-3 mb-4">
    <?php foreach ($recommended as $prod):
      $emoji = $emojiMap[$prod['Prod_Type']] ?? $emojiMap['Default'];
    ?>
    <div class="col-6 col-md-3">
      <div class="food-card">
        <div class="thumb"><?= $emoji ?></div>
        <div class="p-3">
          <h6 class="fw-bold mb-1" style="font-size:.9rem;"><?= e($prod['Prod_Name']) ?></h6>
          <p class="text-muted mb-2" style="font-size:.78rem;"><?= e($prod['Prod_Category']) ?></p>
          <div class="d-flex align-items-center justify-content-between">
            <span class="price">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
              <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
              <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
              <input type="hidden" name="redirect"   value="home.php">
              <button type="submit" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.78rem;">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Pizzas -->
  <h5 class="section-title mb-3">Our Pizzas</h5>
  <div class="row g-3 mb-5">
    <?php foreach ($pizzas as $prod): ?>
    <div class="col-6 col-md-4 col-lg-2">
      <div class="food-card">
        <div class="thumb">🍕</div>
        <div class="p-3">
          <h6 class="fw-bold mb-1" style="font-size:.85rem;"><?= e($prod['Prod_Name']) ?></h6>
          <div class="d-flex align-items-center justify-content-between mt-2">
            <span class="price" style="font-size:.9rem;">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
            <form method="POST" action="add_to_cart.php">
              <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
              <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
              <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
              <input type="hidden" name="redirect"   value="home.php">
              <button type="submit" class="btn btn-sm fw-bold" style="background:var(--sk-red);color:#fff;border-radius:6px;font-size:.75rem;padding:.25rem .6rem;">Add</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>
