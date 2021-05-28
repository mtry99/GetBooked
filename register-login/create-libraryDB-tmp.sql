-- create database --------------------------------------------------

CREATE DATABASE libraryDBtmp;
USE libraryDBtmp;


-- set up user table --------------------------------------------------

CREATE TABLE user(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    is_admin BOOLEAN
);


-- set up author table --------------------------------------------------

CREATE TABLE author(
	author_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL 
);


-- set up book table --------------------------------------------------

CREATE TABLE book(
	book_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
	current_copies_count INT NOT NULL
);


-- user setup --------------------------------------------------

CREATE USER 'genericperson'@'localhost' IDENTIFIED BY 'genericpassword'; -- may have already created this user
GRANT ALL ON libraryDBtmp.* TO 'genericperson'@'localhost';
ALTER USER 'genericperson'@'localhost' IDENTIFIED WITH mysql_native_password by 'genericpassword';


-- create stored procedures --------------------------------------------------

DELIMITER //

CREATE PROCEDURE VALIDATE_LOGIN(
	in username VARCHAR(255),
    in password VARCHAR(255))
BEGIN
    declare uid int;
     set uid = (SELECT user.user_id FROM user WHERE user.username = username AND user.password = password);
     if (uid is not null) then
		select 'TRUE' AS is_valid, uid AS user_id;
	else
		select 'FALSE' AS is_valid, null AS user_id;
	end if;
END//

DELIMITER ;



-- insert some basic values --------------------------------------------------

INSERT INTO author(name) VALUES('Neil Gaiman');
INSERT INTO author(name) VALUES('Brandon Sanderson');

INSERT INTO book(title, current_copies_count) VALUES('Good Omens', 2);
INSERT INTO book(title, current_copies_count) VALUES('Mistborn: The Final Empire', 1);
INSERT INTO book(title, current_copies_count) VALUES('The Well of Ascension', 1);

INSERT INTO user(username, password, name, is_admin) VALUES
	('account1', 'password1', 'user1', FALSE);
INSERT INTO user(username, password, name, is_admin) VALUES
	('account2', 'password2', 'user2', TRUE);

SELECT * FROM author;
SELECT * FROM book;
SELECT * FROM user;

