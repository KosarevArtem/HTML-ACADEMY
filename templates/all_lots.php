<?=$header?>
<section class="lots">
    <?php $category_name = get_cur_category($sql_con); ?>
        <h2>Все лоты в категории <span>«<?=$category_name["cat_name"]?>»</span></h2>
        <ul class="lots__list">
        <?php foreach ($all_lots as $key => $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot["img"]; ?>" width="350" height="260" alt="<?= $lot["title"]; ?>">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot["cat_name"]; ?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot["id"] ?>"><?= htmlspecialchars($lot["title"]); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= price_format(htmlspecialchars($lot["start_price"])); ?></span>
                        </div>
                        <?php $res = timer_left(htmlspecialchars($lot["date_finish"])) ?>
                        <div class="lot__timer timer <?php if ($res[0] < 1) : ?>timer--finishing<?php endif; ?>">
                            <?= "$res[0] : $res[1]"; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
</section>