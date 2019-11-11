create database book_sc;

use book_sc;

create table customers
(
  customerid int unsigned not null auto_increment primary key,
  name char(60) not null,
  address char(80) not null,
  city char(30) not null,
  state char(20),
  zip char(10),
  country char(20) not null
);

create table orders
(
  orderid int unsigned not null auto_increment primary key,
  customerid int unsigned not null,
  amount float(6,2),
  date date not null,
  order_status char(10),
  ship_name char(60) not null,
  ship_address char(80) not null,
  ship_city char(30) not null,
  ship_state char(20),
  ship_zip char(10),
  ship_country char(20) not null
);

create table books
(
   isbn char(13) not null primary key,
   author char(80),
   title char(100),
   catid int unsigned,
   price float(4,2) not null,
   description varchar(255)
);

create table categories
(
  catid int unsigned not null auto_increment primary key,
  catname char(60) not null
);

create table order_items
(
  orderid int unsigned not null,
  isbn char(13) not null,
  item_price float(4,2) not null,
  quantity tinyint unsigned not null,
  primary key (orderid, isbn)
);

create table admin
(
  username char(16) not null primary key,
  password char(40) not null
);

grant select, insert, update, delete
on book_sc.*
to book_sc@localhost identified by 'password';


USE book_sc;

INSERT INTO books VALUES ('0672329166','Luke Welling and Laura Thomson','PHP and MySQL Web Development',1,49.99,
                          'PHP & MySQL Web Development teaches the reader to develop dynamic, secure e-commerce web sites. You will learn to integrate and implement these technologies by following real-world examples and working sample projects.');
INSERT INTO books VALUES ('067232976X','Julie Meloni','Sams Teach Yourself PHP, MySQL and Apache All-in-One',1,34.99,
                          'Using a straightforward, step-by-step approach, each lesson in this book builds on the previous ones, enabling you to learn the essentials of PHP scripting, MySQL databases, and the Apache web server from the ground up.');
INSERT INTO books VALUES ('0672319241','Sterling Hughes and Andrei Zmievski','PHP Developer\'s Cookbook',1,39.99,
                          'Provides a complete, solutions-oriented guide to the challenges most often faced by PHP developers\r\nWritten specifically for experienced Web developers, the book offers real-world solutions to real-world needs\r\n');

INSERT INTO categories VALUES (1,'Internet');
INSERT INTO categories VALUES (2,'Self-help');
INSERT INTO categories VALUES (5,'Fiction');
INSERT INTO categories VALUES (4,'Gardening');

INSERT INTO admin VALUES ('admin', sha1('admin'));
