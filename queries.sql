INSERT INTO categories (code, cat_name) VALUES 
    ('boards', 'Доски и лыжи'),
    ('attachments', 'Крепления'),
    ('boots', 'Ботинки'),
    ('clothing', 'Одежда'),
    ('tools', 'Инструменты'),
    ('other', 'Разное');

INSERT INTO user (user_name, email, user_password, contacts) VALUES 
    ('Vasya', 'vasya@mail.ru', '12345q', '+71234567890'),
    ('Oleg', 'oleg@mail.ru', '12345q', '+70987654321');

INSERT INTO lots (title, lot_description, start_price, img, date_finish, bet_step, category_id, author_id, winner_id) VALUES 
    ('2014 Rossignol District Snowboard', 'Доска', 10999, 'img/lot-1.jpg', '2022-06-08', 1000, 1, 1, 2),
    ('DC Ply Mens 2016/2017 Snowboard', 'Доска', 159999, 'img/lot-2.jpg', '2022-06-09', 2000, 1, 2, 1),
    ('Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления', 8000, 'img/lot-3.jpg', '2022-06-10', 1000, 2, 1, 2),
    ('Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки', 10999, 'img/lot-4.jpg', '2022-06-11', 1000, 3, 1, 2),
    ('Куртка для сноуборда DC Mutiny Charocal', 'Куртка', 7500, 'img/lot-5.jpg', '2022-06-12', 500, 4, 1, 2),
    ('Маска Oakley Canopy', 'Маска', 5400, 'img/lot-6.jpg', '2022-06-13', 500, 6, 1, 2);

INSERT INTO bets (price_bet, user_id, lot_id) VALUES 
    (11999, 2, 1),
    (161999, 1, 2);