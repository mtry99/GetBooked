-- create database --------------------------------------------------
CREATE DATABASE bookDB;

USE bookDB;

-- set up author table --------------------------------------------------
CREATE TABLE `author` ( 
    `author_id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(30) , 
    `original_key` VARCHAR(30) , 
    PRIMARY KEY (`author_id`)
);

-- set up genre table --------------------------------------------------
CREATE TABLE `genre` ( 
    `genre_id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(30) NOT NULL , 
    PRIMARY KEY (`genre_id`)
);

-- set up publisher table --------------------------------------------------
CREATE TABLE `publisher` ( 
    `publisher_id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(50) NOT NULL , 
    PRIMARY KEY (`publisher_id`)
);

-- set up book table --------------------------------------------------
CREATE TABLE `book` ( 
    `book_id` INT NOT NULL AUTO_INCREMENT , 
    `title` VARCHAR(70) NOT NULL , 
    `original_key` VARCHAR(20), 
    `isbn` VARCHAR(13) NOT NULL, 
    `number_of_pages` INT NOT NULL, 
    `language` VARCHAR(5) NOT NULL , 
    `publisher_id` INT NOT NULL , 
    `publish_year` INT NOT NULL , 
    `count` INT NOT NULL ,
    PRIMARY KEY (`book_id`),
    FOREIGN KEY (`publisher_id`) REFERENCES `publisher`(`publisher_id`)
);

-- set up book_author table --------------------------------------------------
CREATE TABLE `book_author` ( 
    `book_id` INT NOT NULL , 
    `author_id` INT NOT NULL , 
    PRIMARY KEY (`book_id`, `author_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`),
    FOREIGN KEY (`author_id`) REFERENCES `author`(`author_id`)
);

-- set up book_genre table --------------------------------------------------
CREATE TABLE `book_genre` ( 
    `book_id` INT NOT NULL , 
    `genre_id` INT NOT NULL , 
    PRIMARY KEY (`book_id`, `genre_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`),
    FOREIGN KEY (`genre_id`) REFERENCES `genre`(`genre_id`)
);

CREATE TABLE `log` (
    `log_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `borrow_date` DATE NOT NULL,
    `return_date` DATE,
    `return_by_date` DATE NOT NULL,
    PRIMARY KEY (`log_id`, `user_id`, `book_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`)
);

-- add unique key constraint to original_key  --------------------------------------------------
ALTER TABLE `book`
ADD CONSTRAINT `book_unique_original_key_constraint` UNIQUE KEY(`original_key`);

ALTER TABLE `book`
ADD CONSTRAINT `book_unique_isbn_constraint` UNIQUE KEY(`isbn`);

ALTER TABLE `author`
ADD CONSTRAINT `author_unique_original_key_constraint` UNIQUE KEY(`original_key`);

ALTER TABLE `genre`
ADD CONSTRAINT `genre_unique_name_constraint` UNIQUE KEY(`name`);

ALTER TABLE `publisher`
ADD CONSTRAINT `publisher_unique_name_constraint` UNIQUE KEY(`name`);

-- add index to original_key  --------------------------------------------------
CREATE UNIQUE INDEX `book_original_key_index`
ON `book`(`original_key`);

CREATE UNIQUE INDEX `book_isbn_index`
ON `book`(`isbn`);

CREATE UNIQUE INDEX `author_original_key_index`
ON `author`(`original_key`);

CREATE UNIQUE INDEX `genre_name_key_index`
ON `genre`(`name`);

CREATE UNIQUE INDEX `publisher_name_key_index`
ON `publisher`(`name`);


-- set up user table --------------------------------------------------

CREATE TABLE user(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    is_admin BOOLEAN
);


-- create stored procedures --------------------------------------------------

DELIMITER //

-- get user's ID, username, and admin status when logging in
CREATE PROCEDURE VALIDATE_LOGIN(
	in username VARCHAR(255),
    in password VARCHAR(255))
BEGIN
    SELECT user.user_id, user.username, user.is_admin
        FROM user 
        WHERE user.username = username AND 
            user.password = password;
END//



-- register user
CREATE PROCEDURE REGISTER(
    in u_username VARCHAR(255),
    in u_password VARCHAR(255),
    in u_name VARCHAR(255))
BEGIN
    INSERT INTO user(username, password, name, is_admin) VALUES
	    (u_username, u_password, u_name, FALSE);
END//


-- add book
CREATE PROCEDURE ADD_BOOK(
    in bookname VARCHAR(255),
    in authorName VARCHAR(255),
    in pages INT,
    in lang VARCHAR(255),
    in publisherName VARCHAR(255),
    in publishedYear INT,
    in ISBN VARCHAR(255))
BEGIN
	INSERT INTO author(name) VALUES (authorName);
    INSERT INTO publisher(name) VALUES (publisherName);

    
    INSERT INTO book (title, isbn, publisher_id, number_of_pages, language, count, publish_year) 
    SELECT bookname, ISBN, publisher_id, pages, lang, 1, publishedYear
    FROM publisher WHERE name = publisherName;

    INSERT INTO book_author (book_id, author_id)
    SELECT book_id, author_id From book, author
    WHERE title = bookName and name = authorName;
END//

-- delete book
CREATE PROCEDURE DELETE_BOOK()


BEGIN

END//

DELIMITER ;


-- insert some basic values --------------------------------------------------

INSERT INTO user(username, password, name, is_admin) VALUES
	('account1', 'password1', 'user1', FALSE);
INSERT INTO user(username, password, name, is_admin) VALUES
	('account2', 'password2', 'user2', TRUE);


SELECT * FROM user;


------------------------------------------------------------------
-- db changes ----------------------------------------------------
------------------------------------------------------------------

-- added full text index to book title
ALTER TABLE `book` ADD FULLTEXT `book_title_index` (`title`);

-- 6/26/2021 --------------------------------------------------
CREATE TABLE `collection` (
	`collection_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `collection_book` (
	`collection_id` INT NOT NULL,
	`book_id` INT NOT NULL,
	`weight` INT NOT NULL,
    PRIMARY KEY (`collection_id`, `book_id`),
    FOREIGN KEY (`collection_id`) REFERENCES `collection`(`collection_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`)
);

INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Aristotle and Plato');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '11', '50');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '4583', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '6733', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '7946', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '9662', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '9766', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '10429', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '12002', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '16403', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '19745', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '30298', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '34671', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '34875', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '35959', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '36293', '50');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '38673', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '43322', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '45346', '50');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '46983', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '49112', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '52259', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '59906', '10');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '60875', '20');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '63870', '50');
INSERT INTO `collection_book` (`collection_id`, `book_id`, `weight`) VALUES ('1', '64967', '10');

INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 1');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 2');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 3');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 4');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 5');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 6');
INSERT INTO `collection` (`collection_id`, `name`) VALUES (NULL, 'Test Collection 7');

------------------------------------------------------------------
-- user setup ----------------------------------------------------
------------------------------------------------------------------

CREATE USER 'bookperson'@'localhost' IDENTIFIED BY 'genericpassword';
GRANT ALL ON bookDB.* TO 'bookperson'@'localhost';
ALTER USER 'bookperson'@'localhost' IDENTIFIED WITH mysql_native_password by 'genericpassword';