<div class="cat-carousel">
  <div class="cat-inner">
  <button type="button" class="cat-arrow cat-arrow-left" onclick="scrollCats(-1)" aria-label="Previous">&#8249;</button>
  <div class="cat-track" id="catTrack">
    <?php
    $cats = [
      'Promos'               => '🎉',
      'Supercard Exclusives' => '💳',
      'Pizza'                => '🍕',
      'Group Meals'          => '🍽️',
      "Chicken 'N Mojos"     => '🍗',
      'Combos'               => '🍱',
      'Hero Sandwiches'      => '🥪',
      'Pasta'                => '🍝',
      'Sides'                => '🍟',
      'Salad'                => '🥗',
      'Desserts'             => '🍰',
      'Drinks'               => '🥤',
    ];
    foreach ($cats as $c => $icon):
      $active = (isset($_GET['category']) && $_GET['category'] === $c) ? 'active' : '';
    ?>
    <a href="/menu?category=<?= urlencode($c) ?>" class="cat-pill <?= $active ?>">
      <span class="cat-icon"><?= $icon ?></span>
      <span class="cat-label"><?= e($c) ?></span>
    </a>
    <?php endforeach; ?>
  </div>
  <button type="button" class="cat-arrow cat-arrow-right" onclick="scrollCats(1)" aria-label="Next">&#8250;</button>
  </div>
</div>
<script>
function scrollCats(dir){
  const t=document.getElementById('catTrack');
  if(t) t.scrollBy({left:dir*320,behavior:'smooth'});
}
</script>
