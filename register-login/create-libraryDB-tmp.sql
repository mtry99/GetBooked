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

INSERT INTO user(username, password, name, is_admin) VALUES
	('account1', 'password1', 'user1', FALSE);
INSERT INTO user(username, password, name, is_admin) VALUES
	('account2', 'password2', 'user2', TRUE);


SELECT * FROM user;

