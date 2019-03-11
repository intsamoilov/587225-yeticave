create database yeticave
    default character set utf8
    default collate utf8_general_ci;

use yeticave;

create table categories
(
    id   int unsigned auto_increment primary key,
    name varchar(50) not null unique,
    class varchar(50)
);

create table users
(
    id       int unsigned auto_increment primary key,
    reg_date timestamp   not null default current_timestamp,
    email    varchar(50) not null unique,
    name     varchar(50) not null,
    password varchar(200) not null,
    avatar   varchar(200),
    contact  varchar(200)
);

create table lots
(
    id          int unsigned auto_increment primary key,
    date        timestamp    not null default current_timestamp,
    name        varchar(100) not null,
    description text         not null,
    image       varchar(200) not null,
    price       int unsigned not null,
    date_end    timestamp    not null,
    bet_step    int unsigned not null,
    user_id     int unsigned not null,
    winner_id   int unsigned          default null,
    category_id int unsigned not null,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (category_id) REFERENCES categories (id)
);

create table bets
(
    id      int unsigned auto_increment primary key,
    date    timestamp    not null default current_timestamp,
    bid     int unsigned not null,
    user_id int unsigned not null,
    lot_id  int unsigned not null,
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (lot_id) REFERENCES lots (id)
);

CREATE FULLTEXT INDEX search_by_lots ON lots(name, description);
CREATE INDEX search_by_winner ON lots(winner_id);
CREATE INDEX search_index ON lots(winner_id, category_id);