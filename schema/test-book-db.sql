-- some example queries to test the db with --

INSERT INTO User(username, password, name) VALUES('username', 'password', 'name');

UPDATE book SET count = 5 WHERE book_id = 7
INSERT INTO log VALUES (NULL, '6', '7', '2021-07-04', NULL, '2021-08-04')

-- display log history
SELECT c.*,
    GROUP_CONCAT(g.genre_id, ':', g.name ORDER BY g.name separator ',' ) as genre, 
    p.publisher_id, p.name as 'publisher_name', bp.publish_year
    FROM (SELECT b.*, 
         GROUP_CONCAT(a.author_id, ':', a.name ORDER BY a.name separator ',' ) as author
         FROM (SELECT user_id, borrow_date, return_date, log_id, return_by_date, book.*
              FROM book 
              INNER JOIN log 
              WHERE book.book_id = log.book_id
              AND user_id = 6) as b
         LEFT JOIN book_author ba ON b.book_id = ba.book_id
         LEFT JOIN author a ON ba.author_id = a.author_id
         GROUP BY b.book_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id = g.genre_id 
    LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
    LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
    GROUP BY c.book_id
    ORDER BY c.borrow_date;

-- renew
UPDATE log SET return_by_date = '2021-09-04' WHERE log_id = 4

-- return
UPDATE log SET return_date = '2021-07-04' WHERE log_id = 4
UPDATE book SET count = count + 1 WHERE book_id = 7


-- updated filter books
SELECT c.*,
GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, 
p.publisher_id, p.name as "publisher_name", bp.publish_year
FROM (SELECT b.score,  b.count, b.original_key, b.isbn, b.number_of_pages, 
     b.language, b.book_id, b.title, 
     GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
     FROM (SELECT * , MATCH(book.title) AGAINST('+war' IN BOOLEAN MODE) score  FROM book 
          NATURAL JOIN (SELECT bp.book_id as book_id FROM publisher as p
                       RIGHT JOIN book_publisher bp ON p.publisher_id = bp.publisher_id
                       WHERE UPPER(p.name) LIKE UPPER("%Press%") 
                       AND bp.publish_year BETWEEN 1700 AND 2021 
                       GROUP BY bp.book_id) d0 
          NATURAL JOIN (SELECT ba.book_id as book_id FROM author as a
                       RIGHT JOIN book_author ba ON a.author_id = ba.author_id
                       WHERE UPPER(a.name) LIKE UPPER("%john%") 
                       GROUP BY ba.book_id) d1 
          NATURAL JOIN (SELECT bg.book_id as book_id FROM genre as g
                       RIGHT JOIN book_genre bg ON g.genre_id = bg.genre_id
                       WHERE UPPER(g.name) LIKE UPPER("%general%") 
                       GROUP BY bg.book_id) d2 
          NATURAL JOIN (SELECT bg.book_id as book_id FROM genre as g
                       RIGHT JOIN book_genre bg ON g.genre_id = bg.genre_id
                       WHERE UPPER(g.name) LIKE UPPER("%film%") 
                       GROUP BY bg.book_id) d3
                       WHERE MATCH(book.title) AGAINST('+war' IN BOOLEAN MODE) 
          AND book.number_of_pages BETWEEN 0 AND 1500 
          AND book.language = "eng" 
          AND book.count > "0" 
          ORDER BY score DESC
          LIMIT 0, 10) as b
     LEFT JOIN book_author ba ON b.book_id = ba.book_id
     LEFT JOIN author a ON ba.author_id = a.author_id
     GROUP BY b.book_id) as c
LEFT JOIN book_genre bg ON c.book_id = bg.book_id
LEFT JOIN genre g ON bg.genre_id = g.genre_id 
LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
GROUP BY c.book_id;

SELECT c.*,
GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, 
p.publisher_id, p.name as "publisher_name", bp.publish_year
FROM (SELECT b.score,  b.count, b.original_key, b.isbn, b.number_of_pages, 
     b.language, b.book_id, b.title, 
     GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
     FROM (SELECT * , MATCH(book.title) AGAINST('+story' IN BOOLEAN MODE) score FROM book    
          NATURAL JOIN (SELECT bp.book_id as book_id FROM publisher as p
                       RIGHT JOIN book_publisher bp ON p.publisher_id = bp.publisher_id
                       WHERE UPPER(p.name) LIKE UPPER("%Kessinger%") 
                       AND bp.publish_year BETWEEN 1915 AND 2010 
                       GROUP BY bp.book_id) d0 
          NATURAL JOIN (SELECT ba.book_id as book_id FROM author as a
                       RIGHT JOIN book_author ba ON a.author_id = ba.author_id
                       WHERE UPPER(a.name) LIKE UPPER("%MacDonald%") 
                       GROUP BY ba.book_id) d1 
          NATURAL JOIN (SELECT bg.book_id as book_id FROM genre as g
                       RIGHT JOIN book_genre bg ON g.genre_id = bg.genre_id
                       WHERE UPPER(g.name) LIKE UPPER("%fiction%") 
                       GROUP BY bg.book_id) d2 
          NATURAL JOIN (SELECT bg.book_id as book_id FROM genre as g
                       RIGHT JOIN book_genre bg ON g.genre_id = bg.genre_id
                       WHERE UPPER(g.name) LIKE UPPER("%general%") 
                       GROUP BY bg.book_id) d3
          WHERE MATCH(book.title) AGAINST('+story' IN BOOLEAN MODE) 
          AND book.number_of_pages BETWEEN 0 AND 613 
          AND book.language = "eng" 
          AND book.count > "0" 
          ORDER BY score DESC
          LIMIT 0, 10) as b
     LEFT JOIN book_author ba ON b.book_id = ba.book_id
     LEFT JOIN author a ON ba.author_id = a.author_id
     GROUP BY b.book_id) as c
LEFT JOIN book_genre bg ON c.book_id = bg.book_id
LEFT JOIN genre g ON bg.genre_id = g.genre_id 
LEFT JOIN book_publisher bp ON c.book_id = bp.book_id
LEFT JOIN publisher p ON p.publisher_id = bp.publisher_id 
GROUP BY c.book_id;


-- test add book --
-- CALL ADD_BOOK(bookName, authorName, pages, language, publisher, publishedYear, isbn);
CALL ADD_BOOK("bbbb", "aaaa", "1234", "eng", "pppp", "1234", "1234");

-- get rows of outstanding fines
CALL CALCULATE_FINES("5");
CALL GET_OUTSTANDING_FINES("5");

-- clear inventory of user
DELETE FROM user_inventory WHERE user_id = (SELECT user_id FROM user WHERE username = 'alex3');

-- add to inventory of user
INSERT INTO user_inventory (user_id, book_id, amount) 
VALUES ((SELECT user_id FROM user WHERE username = 'alex3'), '4583', '150');
INSERT INTO user_inventory (user_id, book_id, amount) 
VALUES ((SELECT user_id FROM user WHERE username = 'alex3'), '16403', '15');
