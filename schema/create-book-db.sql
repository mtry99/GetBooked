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
    `name` NVARCHAR(30) NOT NULL , 
    PRIMARY KEY (`genre_id`)
);

-- set up publisher table --------------------------------------------------
CREATE TABLE `publisher` ( 
    `publisher_id` INT NOT NULL AUTO_INCREMENT , 
    `name` NVARCHAR(50) NOT NULL , 
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
    `count` INT NOT NULL ,
    `rarity` INT NOT NULL ,
    PRIMARY KEY (`book_id`)
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

-- set up book_publisher table --

CREATE TABLE `book_publisher` ( 
    `book_id` INT NOT NULL , 
    `publisher_id` INT NOT NULL , 
    `publish_year` INT NOT NULL , 
    PRIMARY KEY (`book_id`, `publisher_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`),
    FOREIGN KEY (`publisher_id`) REFERENCES `publisher`(`publisher_id`)
);

-- set up user table --------------------------------------------------

CREATE TABLE user(
	user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    is_admin BOOLEAN,
    bbuck BIGINT NOT NULL DEFAULT '0',
    bbuck_last_updated BIGINT NOT NULL DEFAULT '0'
);

-- set up log history table -------------------------------------------

CREATE TABLE `log` (
    `log_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `book_id` INT NOT NULL,
    `borrow_date` DATE NOT NULL,
    `return_date` DATE,
    `return_by_date` DATE NOT NULL,
    PRIMARY KEY (`log_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`)
);

-- set collection -------------------------------------------

CREATE TABLE `collection` (
	`collection_id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `collection_book` (
	`collection_id` INT NOT NULL,
	`book_id` INT NOT NULL,
    PRIMARY KEY (`collection_id`, `book_id`),
    FOREIGN KEY (`collection_id`) REFERENCES `collection`(`collection_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`)
);

CREATE TABLE `user_inventory` (
	`user_id` INT NOT NULL,
	`book_id` INT NOT NULL,
	`amount` INT NOT NULL,
    PRIMARY KEY (`user_id`, `book_id`),
    FOREIGN KEY (`user_id`) REFERENCES `user`(`user_id`),
    FOREIGN KEY (`book_id`) REFERENCES `book`(`book_id`)
);

-- fine types: 0 - late; 1 - replacement
CREATE TABLE fine(
    fine_id INT NOT NULL AUTO_INCREMENT,
    log_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    outstanding_amount DECIMAL(10, 2) NOT NULL,
    last_updated DATE NOT NULL,
    fine_type INT NOT NULL,
    PRIMARY KEY (fine_id),
    FOREIGN KEY (log_id) REFERENCES log(log_id),
    UNIQUE (log_id, fine_type)
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

ALTER TABLE `book` ADD FULLTEXT `book_title_index` (`title`);

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
-- DROP PROCEDURE IF EXISTS `ADD_BOOK`; DELIMITER //
CREATE PROCEDURE ADD_BOOK(
    in bookname VARCHAR(255),
    in authorName VARCHAR(255),
    in pages INT,
    in lang VARCHAR(255),
    in publisherName VARCHAR(255),
    in publishedYear INT,
    in ISBN VARCHAR(255))
BEGIN
	
    INSERT INTO Author (name) 
    SELECT aname
    FROM (SELECT (authorName) AS aname) AS t
    WHERE NOT EXISTS (SELECT name FROM Author WHERE name=authorName);
    
    INSERT INTO Publisher (name) 
    SELECT pname
    FROM (SELECT (publisherName) AS pname) AS t
    WHERE NOT EXISTS (SELECT name FROM Publisher WHERE name=publisherName); 
    

    
    INSERT INTO book (title, isbn, number_of_pages, language, count, rarity) 
    SELECT bookname, ISBN, pages, lang, 1, 3;

    INSERT INTO book_publisher (book_id, publisher_id, publish_year)
    SELECT book_id, publisher_id, publishedYear From book, publisher p
    WHERE title = bookName and p.name = publisherName;

    INSERT INTO book_author (book_id, author_id)
    SELECT book_id, author_id From book, author
    WHERE title = bookName and name = authorName;
END//





-- get rows of outstanding fines
CREATE PROCEDURE GET_OUTSTANDING_FINES(
    in u_uid INT
)
BEGIN
    SELECT 
        l.return_by_date, 
        l.return_date,
        b.title,
        f.fine_type,
        f.total_amount,
        f.outstanding_amount
	FROM fine f
	LEFT JOIN log l ON f.log_id = l.log_id
	LEFT JOIN user u ON l.user_id = u.user_id
	LEFT JOIN book b ON l.book_id = b.book_id
	WHERE u_uid = u.user_id AND f.outstanding_amount > 0
	ORDER BY l.return_by_date DESC;
END//


-- calculate fines for specified user
CREATE PROCEDURE CALCULATE_FINES(
    in u_uid INT
)
BEGIN
    DROP TEMPORARY TABLE IF EXISTS existing_fines;
    CREATE TEMPORARY TABLE existing_fines 
        (SELECT l.log_id, l.return_by_date FROM log l
	    WHERE u_uid = l.user_id AND 
		l.return_date IS NULL AND
        l.return_by_date < CURDATE() AND 
        l.log_id IN 
            (SELECT fine.log_id FROM fine 
            LEFT JOIN log l ON l.log_id = fine.log_id 
            WHERE u_uid = l.user_id));
    
    -- update late fines    
    SET SQL_SAFE_UPDATES = 0;
    UPDATE fine
    SET 
        outstanding_amount = outstanding_amount + DATEDIFF(CURDATE(), last_updated) * 0.3,
        total_amount = total_amount + DATEDIFF(CURDATE(), last_updated) * 0.3,
        last_updated = CURDATE()
        WHERE log_id IN (SELECT log_id FROM existing_fines) AND fine_type = 0;
    SET SQL_SAFE_UPDATES = 1;

    DROP TEMPORARY TABLE IF EXISTS new_fines;
    CREATE TEMPORARY TABLE new_fines 
        (SELECT l.log_id, l.return_by_date, 
            DATEDIFF(CURDATE(), l.return_by_date) * 0.3 AS amount FROM log l
	    WHERE u_uid = l.user_id AND 
            l.return_date IS NULL AND
            l.return_by_date < CURDATE() AND 
            l.log_id NOT IN 
                (SELECT fine.log_id FROM fine 
                LEFT JOIN log l ON l.log_id = fine.log_id 
                WHERE u_uid = l.user_id));

    -- insert late fines
    INSERT INTO fine
        SELECT NULL, log_id, amount, amount, CURDATE(), 0 FROM new_fines;
END//

-- get all fines of specified user
CREATE PROCEDURE GET_FINES_AMOUNT(
    in u_uid INT
)
BEGIN
    CALL CALCULATE_FINES(u_uid);
    SELECT IFNULL(SUM(fine.outstanding_amount), 0)
        FROM fine
        LEFT JOIN log on fine.log_id = log.log_id
        LEFT JOIN user on log.user_id = user.user_id
        WHERE u_uid = user.user_id;
END//

-- process payment for user with payment amount
CREATE PROCEDURE PAY_FINES(
    in u_uid INT,
    in payment_amount DECIMAL(10,2)
)
BEGIN
    CALL CALCULATE_FINES(u_uid);
    
    SELECT IFNULL(SUM(fine.outstanding_amount), 0) INTO @total_outstanding
        FROM fine
        LEFT JOIN log on fine.log_id = log.log_id
        LEFT JOIN user on log.user_id = user.user_id
        WHERE u_uid = user.user_id;
    
	SET @payment_left = payment_amount;
    
    deduct_payment: WHILE @payment_left > 0 AND @total_outstanding > 0 DO
		SELECT fine_id, outstanding_amount 
			INTO @fine_id, @outstanding_amount 
			FROM fine f
			LEFT JOIN log l ON f.log_id = l.log_id
			LEFT JOIN user u ON l.user_id = u.user_id
			WHERE u_uid = u.user_id AND f.outstanding_amount > 0
			ORDER BY l.return_by_date DESC
			LIMIT 1;
		IF @outstanding_amount >= @payment_left THEN
			UPDATE fine 
				SET outstanding_amount = @outstanding_amount - @payment_left
                WHERE fine_id = @fine_id;
			SET @payment_left = 0;
            SET @total_outstanding = @total_outstanding - @payment_left;
		ELSE
			UPDATE fine
				SET outstanding_amount = 0
                WHERE fine_id = @fine_id;
			SET @payment_left = @payment_left - @outstanding_amount;
            SET @total_outstanding = @total_outstanding - @outstanding_amount;
        END IF;
    END WHILE deduct_payment;

    SELECT ROUND(payment_amount - @payment_left, 2);
END//

DELIMITER ;

-- ----------------------------------------------------------------
-- trigger ----------------------------------------------------
-- ----------------------------------------------------------------

CREATE TRIGGER user_bbuck_last_updated BEFORE INSERT ON user FOR EACH ROW SET new.bbuck_last_updated = UNIX_TIMESTAMP();

-- ----------------------------------------------------------------
-- user setup ----------------------------------------------------
-- ----------------------------------------------------------------

CREATE USER 'bookperson'@'localhost' IDENTIFIED BY 'genericpassword';
GRANT ALL ON bookDB.* TO 'bookperson'@'localhost';
ALTER USER 'bookperson'@'localhost' IDENTIFIED WITH mysql_native_password by 'genericpassword';