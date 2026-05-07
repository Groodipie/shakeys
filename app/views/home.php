<?php
$emojiMap = ['Pizza'=>'🍕','Chicken'=>'🍗','Pasta'=>'🍝','Beverage'=>'🥤','Bundle'=>'🎉','Sides'=>'🍟','Default'=>'🍽️'];

// Product descriptions keyed by Prod_Name (case-insensitive lookup below).
$descMap = [
  "manager's choice"    => "Shakey's no. 1 pizza. Ham, beef, Italian sausage, green bell pepper and onions.",
  "pepperoni"           => "Fully-loaded for that sumptuous pepperoni taste.",
  "shakey's special"    => "Loaded with beef, Italian sausage, pepperoni, salami, mushrooms, green bell pepper, and onions.",
  "belly buster"        => "11 toppings: beef, Italian sausage, pepperoni, ham, salami, salami bits, mushrooms, red & green bell pepper, and onions.",
  "truffle four cheese" => "Irresistibly rich and creamy pizza topped with four types of cheese: Mozzarella, Parmesan, Cheddar and Truffle.",
  "hawaiian"            => "Sweet pineapple chunks and savory ham — the classic crowd favorite.",
  "garlic 'n cheese"    => "A simple but satisfying blend of garlic and melted mozzarella cheese.",
];
$descFor = function(array $prod) use ($descMap) {
  $key = strtolower(trim($prod['Prod_Name'] ?? ''));
  return $descMap[$key] ?? ($prod['Prod_Category'] ?? '');
};
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
              <a href="/menu?category=Promos" class="bigcar-btn">Order Now</a>
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
  <section class="home-section mb-5 pb-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="section-title mb-0">Recommended for you</h5>
    </div>
    <div class="row g-3">
      <?php foreach ($recommended as $prod):
        $emoji = $emojiMap[$prod['Prod_Type']] ?? $emojiMap['Default'];
        $isPizza = ($prod['Prod_Type'] ?? '') === 'Pizza';
      ?>
      <div class="col-12 col-md-4">
        <div class="food-card food-card-h">
          <div class="thumb"><?= $emoji ?></div>
          <div class="body">
            <h6 class="title"><?= e($prod['Prod_Name']) ?></h6>
            <p class="desc"><?= e($descFor($prod)) ?></p>
            <div class="foot">
              <div>
                <p class="starts">Starts at</p>
                <span class="price">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
              </div>
              <?php if ($isPizza): ?>
                <a href="/product/<?= (int)$prod['Prod_ID'] ?>" class="order-btn">ORDER</a>
              <?php else: ?>
              <form method="POST" action="/add_to_cart">
                <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
                <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
                <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
                <input type="hidden" name="redirect"   value="/home">
                <button type="submit" class="order-btn">ORDER</button>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Featured products -->
  <section class="home-section mb-5">
    <div class="d-flex align-items-center justify-content-between mb-3">
      <h5 class="section-title mb-0">Featured products</h5>
      <a href="/menu" class="view-menu-link">VIEW MENU</a>
    </div>
    <div class="row g-3">
      <?php foreach ($featured as $prod):
        $isPizza = ($prod['Prod_Type'] ?? '') === 'Pizza';
      ?>
      <div class="col-12 col-md-4">
        <div class="food-card food-card-h">
          <div class="thumb">🍕</div>
          <div class="body">
            <h6 class="title"><?= e($prod['Prod_Name']) ?></h6>
            <p class="desc"><?= e($descFor($prod)) ?></p>
            <div class="foot">
              <div>
                <p class="starts">Starts at</p>
                <span class="price">₱<?= number_format($prod['Prod_BasePrice'],2) ?></span>
              </div>
              <?php if ($isPizza): ?>
                <a href="/product/<?= (int)$prod['Prod_ID'] ?>" class="order-btn">ORDER</a>
              <?php else: ?>
              <form method="POST" action="/add_to_cart">
                <input type="hidden" name="prod_id"    value="<?= $prod['Prod_ID'] ?>">
                <input type="hidden" name="prod_name"  value="<?= e($prod['Prod_Name']) ?>">
                <input type="hidden" name="prod_price" value="<?= $prod['Prod_BasePrice'] ?>">
                <input type="hidden" name="redirect"   value="/home">
                <button type="submit" class="order-btn">ORDER</button>
              </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Supercard CTA banner -->
  <section class="home-section mt-5 mb-4">
    <a href="/account" class="supercard-cta">
      <div class="supercard-cta-art supercard-cta-art-left"></div>
      <div class="supercard-cta-art supercard-cta-art-right"></div>
      <div class="supercard-cta-body">
        <h3 class="supercard-cta-title">Free Pizza. Chicken. <span class="hl">Plus</span> More!</h3>
        <p class="supercard-cta-sub">Supercard Members enjoy these benefits from Shakey's and Peri-Peri Charcoal Chicken and Sauce Bar.</p>
      </div>
      <span class="supercard-cta-btn">KNOW MORE</span>
    </a>
  </section>

  <!-- Explore More -->
  <section class="home-section mb-2">
    <h5 class="section-title mb-3">Explore More</h5>
    <div class="app-promo">
      <div class="app-promo-body">
        <h4 class="app-promo-title">Download the NEW Shakey's Super App!</h4>
        <p class="app-promo-desc">Get the new Shakey's Super App for seamless order experience plus get to know more about our products and promo!</p>
        <div class="app-promo-actions">
          <a href="#" class="store-btn" aria-label="Get it on Google Play">
            <img src="https://www.shakeyspizza.ph/images/explore-more/google-play-lq.png" alt="Get it on Google Play">
          </a>
          <a href="#" class="store-btn" aria-label="Download on the App Store">
            <img src="https://www.shakeyspizza.ph/images/explore-more/app-store-lq.png" alt="Download on the App Store">
          </a>
          <img src="https://www.shakeyspizza.ph/images/explore-more/qr-code.png" alt="Super App QR code" class="app-qr">
        </div>
      </div>
      <div class="app-promo-art">
        <img src="https://www.shakeyspizza.ph/images/explore-more/gta-home.png" alt="Shakey's Super App home screen" class="app-phone app-phone-1">
        <img src="https://www.shakeyspizza.ph/images/explore-more/gta-rider.png" alt="Shakey's Super App rider tracking" class="app-phone app-phone-2">
      </div>
    </div>
  </section>

</div>
