create table admin (
  email varchar(255) primary key,
  pass varchar(255)
);

create table book (
  id int auto_increment primary key,
  title varchar(255),
  author varchar(255),
  publisher varchar(255),
  info varchar(1023),
  pages int,
  total int,
  available int
);

create table checkin (
  id int auto_increment primary key,
  requestee int,
  book int,
  issue_id int
);

create table currently_issued (
  id int auto_increment primary key,
  bearer int,
  book int,
  time_issued bigint
);

create table history (
  id int auto_increment primary key,
  bearer int,
  book int,
  time_issued bigint,
  time_returned bigint
);

create table transaction (
  id int auto_increment primary key,
  requestee int,
  book int
);

create table user (
  id int auto_increment primary key,
  email varchar(255),
  name varchar(255),
  pass varchar(255),
  active tinyint(1)
);