CREATE TABLE fragments(
    id int PRIMARY KEY AUTO_INCREMENT,
    filename varchar(255),
    tekst varchar(255),
    counter int default 0
)