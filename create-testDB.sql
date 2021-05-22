-- create database --------------------------------------------------
CREATE DATABASE testDB;

-- set up student table --------------------------------------------------
USE testDB;
CREATE TABLE student(uid DECIMAL(3, 0) NOT NULL PRIMARY KEY, name VARCHAR(30), score DECIMAL(3, 2));
INSERT INTO student VALUES(1, 'xi', 0.1);
INSERT INTO student VALUES(2, 'yi', 0.4);
SELECT * FROM student;

-- user setup --------------------------------------------------
-- CREATE USER '[replace_with_user]'@'localhost' IDENTIFIED BY 'mysqluser';
-- GRANT ALL ON testDB.* TO '[replace_with_user]'@'localhost';
-- ALTER USER '[replace_with_user]'@'localhost' IDENTIFIED WITH mysql_native_password by 'mysqluser';
