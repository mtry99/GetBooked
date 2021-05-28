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

