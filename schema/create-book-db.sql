-- create database --------------------------------------------------
CREATE DATABASE bookDB;

USE bookDB;

-- set up author table --------------------------------------------------
CREATE TABLE `author` ( 
    `author_id` INT NOT NULL AUTO_INCREMENT , 
    `name` VARCHAR(30) , 
    `original_key` VARCHAR(30) NOT NULL , 
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
    `original_key` VARCHAR(20) NOT NULL , 
    `isbn` VARCHAR(13) NOT NULL , 
    `number_of_pages` INT NOT NULL , 
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

GRANT ALL ON bookdb.* TO 'bookperson'@'localhost';

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


------------------------------------------------------------------
-- user setup ----------------------------------------------------
------------------------------------------------------------------

CREATE USER 'bookperson'@'localhost' IDENTIFIED BY 'genericpassword';
GRANT ALL ON bookDB.* TO 'bookperson'@'localhost';
ALTER USER 'bookperson'@'localhost' IDENTIFIED WITH mysql_native_password by 'genericpassword';