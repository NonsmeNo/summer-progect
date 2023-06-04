CREATE TABLE persons (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(128) NOT NULL DEFAULT '',
  position varchar(128) NOT NULL DEFAULT '',
  tel varchar(128) NOT NULL DEFAULT ''
);

CREATE TABLE categories (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name varchar(128) NOT NULL DEFAULT '',
  life int(15) unsigned NOT NULL
);

CREATE TABLE fixed_assets (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  name varchar(128) NOT NULL DEFAULT '',
  date varchar(10) NOT NULL DEFAULT '',
  price int(15) unsigned NOT NULL
);


CREATE TABLE accounting_journal (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  id_fixed_assets int(10) unsigned NOT NULL,
  id_categories int(10) unsigned NOT NULL,
  id_persons int(10) unsigned NOT NULL
);
