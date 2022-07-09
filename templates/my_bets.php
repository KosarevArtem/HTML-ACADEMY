<section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach ($user_bets as $bets): ?>
        <?php $res = timer_left(htmlspecialchars($bets["date_finish"])) ?>
        <tr class="rates__item <?php if ($bets["winner_id"] == $_SESSION["id"]) : ?>rates__item--win<?php endif; ?>">
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=$bets["img"]?>" width="54" height="40" alt="<?=$bets["title"]?>">
            </div>
            <h3 class="rates__title"><a href="lot.php?id=<?=$bets["id"]?>"><?=$bets["title"]?></a></h3>
          </td>
          <td class="rates__category">
            <?=$bets["cat_name"]?>
          </td>
          <td class="rates__timer">
            <div class="timer <?php if ($bets["winner_id"] == $_SESSION["id"]) : ?>timer--win<?php endif; ?>">
              <?php if ($res[0] != 0): ?>
              <?= "$res[0] : $res[1]"; ?>
              <?php elseif ($bets["winner_id"] == $_SESSION["id"]) : ?>
                Ставка выиграла
              <?php else : ?>
              <?php if ($bets["winner_id"] != $_SESSION["id"]) : ?>
                Торги закончились
              <?php endif; ?>
              <?php endif; ?>
            </div>
          </td>
          <td class="rates__price">
            <?=price_format(htmlspecialchars($bets["price_bet"]))?>
          </td>
          <td class="rates__time">
            <?=$bets["date_bet"]?>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
</section>