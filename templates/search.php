<?=$header?>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search_text; ?></span>»</h2>
        <?php if (!empty($lots)): ?>
        <ul class="lots__list">
            <?php foreach ($lots as $lot): ?>
            <li class="lots__item lot">
            <div class="lot__image">
                <img src="<?= $lot["img"]; ?>" width="350" height="260" alt="<?= $lot["title"]; ?>">
            </div>
            <div class="lot__info">
                <span class="lot__category"><?= $lot["cat_name"]; ?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot["id"]; ?>"><?= htmlspecialchars($lot["title"]); ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?= price_format(htmlspecialchars($lot["start_price"])); ?></span>
                        </div>
                        <?php $res = timer_left(htmlspecialchars($lot["date_finish"])) ?>
                        <div class="lot__timer timer <?php if ($res[0] < 1): ?>timer--finishing<?php endif; ?>">
                            <?= "$res[0] : $res[1]"; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php if ($pages_count > 1): ?>
<ul class="pagination-list">
    <?php $prev = $cur_page - 1; ?>
    <?php $next = $cur_page + 1; ?>
    <li class="pagination-item pagination-item-prev">
        <a <?php if ($cur_page >= 2): ?> href="search.php?search=<?= $search_text; ?>&page=<?= $prev; ?>"<?php endif; ?>>Назад</a>
    </li>
    <?php foreach($pages as $page): ?>
        <li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>">
            <a href="search.php?search=<?= $search_text; ?>&page=<?= $page; ?>"><?= $page; ?></a>
        </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a <?php if ($cur_page < $pages_count): ?> href="search.php?search=<?= $search_text; ?>&page=<?= $next; ?>"<?php endif; ?>>Вперед</a>
        </li>
    </ul>
<?php endif; ?>
    </div>
    <!--<? //$panagination; ?>-->
    <?php else: ?>
        <h2>Ничего не найдено по вашему запросу</h2>
    <?php endif; ?>
</div>