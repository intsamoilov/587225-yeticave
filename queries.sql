insert into categories (name)
values ('Доски и лыжи'),
	   ('Крепления'),
	   ('Ботинки'),
	   ('Одежда'),
	   ('Инструменты'),
	   ('Разное');

insert into users (email, name, password, avatar, contact)
values ('marina.user@gmail.com', 'Марина', 'qwerty1', '/img/avatar.jpg', 'Moscow city'),
	   ('vladimir.user@gmail.com', 'Владимир', 'qwerty2', '/img/avatar.jpg', 'Novgorod city');

insert into lots (name, description, image, price, date_end, bet_step, user_id, winner_id, category_id)
values ('2014 Rossignol District Snowboard', 'Отличная доска', 'img/lot-1.jpg', 10999, '2019-02-22 00:00:00', 1000, 1,
		2, 1),
	   ('DC Ply Mens 2016/2017 Snowboard', 'Проф. доска', 'img/lot-2.jpg', 159999, '2019-02-22 00:00:00', 10000, 2, 1,
		1),
	   ('Крепления Union Contact Pro 2015 года размер L/XL', 'Супер крепления', 'img/lot-3.jpg', 8000,
		'2019-02-22 00:00:00', 800, 3, 0, 2),
	   ('Ботинки для сноуборда DC Mutiny Charocal', 'Мега ботинки', 'img/lot-4.jpg', 10999, '2019-02-22 00:00:00', 900,
		4, 0, 3),
	   ('Куртка для сноуборда DC Mutiny Charocal', 'Теплая куртка', 'img/lot-5.jpg', 7500, '2019-02-22 00:00:00', 700,
		5, 0, 4),
	   ('Маска Oakley Canopy', 'Обычная маска', 'img/lot-6.jpg', 5400, '2019-02-22 00:00:00', 500, 6, 0, 6);

insert into bets (bid, user_id, lot_id)
values (11999, 2, 1),
	   (169999, 1, 2);

-- получить все категории
select *
from categories;

-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение,
-- цену, название категории
select l.name, l.image, g.name, l.price
from lots l
		 join categories g on g.id = l.category_id
where l.winner_id = 0;

-- показать лот по его id. Получите также название категории, к которой принадлежит лот
select l.name, l.description, g.name, l.price
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
where bets.id = 1
order by bets.date desc;



