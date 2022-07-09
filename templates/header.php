<nav class="nav">
  <ul class="nav__list container">
    <?php foreach($cats_arr as $key => $cat): ?>
    <li class="nav__item">
      <a href="all_lots.php?category=<?=$cat["id"]?>"><?= $cat["cat_name"]; ?></a>
    </li>
    <?php endforeach; ?>
  </ul>
</nav>