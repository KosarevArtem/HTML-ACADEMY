<?=$header?>
<section class="lot-item container">
      <?php if ($lot['status'] == "open") : ?>
      <h2><?= $lot['title'] ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?= $lot['img'] ?>" width="730" height="548" alt="<?= $lot['title'] ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?= $lot['cat_name'] ?></span></p>
          <p class="lot-item__description"><?= $lot['lot_description'] ?></p>
        </div>
        <div class="lot-item__right">
        <?php if (isset($_SESSION['id'])) : ?>
          <div class="lot-item__state">
          <?php $res = timer_left(htmlspecialchars($lot["date_finish"])) ?>
            <div class="lot-item__timer timer <?php if ($res[0] < 1) : ?>timer--finishing<?php endif; ?>">
                <?= "$res[0] : $res[1]"; ?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?= price_format(htmlspecialchars($current_price)) ?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?= price_format($min_bet) ?></span>
              </div>
            </div>
            <?php $classname = isset($errors) ? "form__item--invalid" : ""; ?>
            <form class="lot-item__form" action="lot.php?id=<?=$lot_id?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item <?=$classname?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?= $min_bet ?>">
                <span class="form__error"><?=$errors["cost"]?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
          <?php if (!empty($bets)) : ?>
          <div class="history">
          <h3>История ставок (<span><?=count($bets)?></span>)</h3>
            <table class="history__list">
            <?php foreach ($bets as $bet) : ?>
              <tr class="history__item">
                <td class="history__name"><?=$bet["user_name"];?></td>
                <td class="history__price"><?=$bet["price_bet"];?></td>
                <td class="history__time"><?=$bet["date_bet"];?></td>
              </tr>
            <?php endforeach; ?>
            </table>
          </div>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <?php else : ?>
      <div>
        <h2><?= $lot['title'] ?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?= $lot['img'] ?>" width="730" height="548" alt="<?= $lot['title'] ?>">
          </div>
          <p class="lot-item__category">Категория: <span><?= $lot['cat_name'] ?></span></p>
          <p class="lot-item__description"><?= $lot['lot_description'] ?></p>
        </div>
        <div class="lot-item__right">
          <h2>Лот закрыт</h2>
        </div>
      </div>
      <?php endif; ?>
</section>