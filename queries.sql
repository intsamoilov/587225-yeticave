insert into categories (id, name, class)
values (1, 'Доски и лыжи', 'boards'),
	   (2, 'Крепления', 'attachment'),
	   (3, 'Ботинки', 'boots'),
	   (4, 'Одежда', 'clothing'),
	   (5, 'Инструменты', 'tools'),
	   (6, 'Разное', 'other');

insert into users (id, email, name, password, avatar, contact)
values (1, 'marina.user@gmail.com', 'Марина', 'qwerty1', '/img/avatar.jpg', 'Moscow city'),
	   (2, 'vladimir.user@gmail.com', 'Владимир', 'qwerty2', '/img/avatar.jpg', 'Novgorod city');

insert into lots (id, date, name, description, image, price, date_end, bet_step, user_id, winner_id, category_id)
values (1, '2019-02-12 00:00:00', '2014 Rossignol District Snowboard', 'Отличная доска',
		'img/lot-1.jpg', 10999, '2019-03-22 00:00:00', 1000, 1, NULL, 1),
	   (2, '2019-02-12 00:00:00', 'DC Ply Mens 2016/2017 Snowboard', 'Проф. доска',
		'img/lot-2.jpg', 159999, '2019-03-22 00:00:00', 10000, 2, NULL, 1),
	   (3, '2019-02-13 00:00:00', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Супер крепления',
		'img/lot-3.jpg', 8000, '2019-03-22 00:00:00', 800, 1, NULL, 2),
	   (4, '2019-02-12 00:00:00', 'Ботинки для сноуборда DC Mutiny Charocal', 'Мега ботинки',
		'img/lot-4.jpg', 10999, '2019-03-22 00:00:00', 900, 1, NULL, 3),
	   (5, '2019-02-16 00:00:00', 'Куртка для сноуборда DC Mutiny Charocal', 'Теплая куртка',
		'img/lot-5.jpg', 7500, '2019-03-22 00:00:00', 700, 2, NULL, 4),
	   (6, '2019-02-15 00:00:00', 'Маска Oakley Canopy', 'Обычная маска',
		'img/lot-6.jpg', 5400, '2019-03-22 00:00:00', 500, 2, NULL, 6);

insert into bets (bid, user_id, lot_id)
values (11999, 2, 1),
	   (169999, 1, 2);

-- получить все категории
select *
from categories;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение,
-- цену, название категории
select l.name, l.image, g.name, l.price, l.date
from lots l
		 join categories g on g.id = l.category_id
where l.winner_id is null
order by l.date desc;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот
select l.name, l.description, g.name as category_name, l.price
from lots l
		 join categories g on g.id = l.category_id
where l.id = 4;

-- обновить название лота по его идентификатору
update lots
set name = 'Ботинки для сноуборда DC Mutiny'
where id = 4;

-- получить список самых свежих ставок для лота по его идентификатору
select bets.bid, bets.date
from bets
where bets.lot_id = 1
order by bets.date desc;



