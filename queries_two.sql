--получить все категории;
SELECT cat_name AS 'Категории' FROM categories;

--получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT title, start_price, img, price_bet, cat_name FROM bets b 
JOIN lots l ON b.lot_id = l.id 
JOIN categories c ON l.category_id = c.id 
WHERE date_finish > CURRENT_TIMESTAMP;
--другой вариант записи
SELECT lots.title, lots.start_price, lots.img, bets.price_bet, categories.cat_name FROM bets
JOIN lots ON bets.lot_id = lots.id 
JOIN categories ON lots.category_id = categories.id
--

--показать лот по его ID. Получите также название категории, к которой принадлежит лот;
SELECT title, cat_name FROM lots l 
JOIN categories c ON l.category_id = c.id 
WHERE l.id = 2;

--обновить название лота по его идентификатору;
UPDATE lots SET title = '2014 Rossignol District Snowboard1' 
WHERE id = 1;

--получить список ставок для лота по его идентификатору с сортировкой по дате.
SELECT date_bet, price_bet, user_id, lot_id FROM bets b 
JOIN lots l ON b.lot_id = l.id 
WHERE l.id = 2 
ORDER BY date_bet ASC;