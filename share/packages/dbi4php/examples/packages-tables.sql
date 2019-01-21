CREATE TABLE package (
  descr varchar(100),
  type varchar(25),
  date_entered INT,
  code varchar(75) NOT NULL,
  received char(1) default 'N',
  PRIMARY KEY (code)
);
