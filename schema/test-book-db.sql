-- some example queries to test the db with --

-- select 10 books --
SELECT * 
FROM `book` 
ORDER BY b.book_id
LIMIT 10;

-- select 10 books with authors --
SELECT b.book_id, b.title, a.author_id, a.name as "author_name"
FROM 
    (SELECT * FROM book LIMIT 110,10) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
ORDER BY b.book_id;

-- select 10 books with genres --
SELECT b.book_id, b.title, g.genre_id, g.name as "genre_name"
FROM 
    (SELECT * FROM book LIMIT 110,10) as b
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
ORDER BY b.book_id;

-- select 10 books with publishers --
SELECT b.book_id, b.title, b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book LIMIT 110,10) as b
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of a specific book by book_id --
SELECT b.book_id, b.title, a.author_id, a.name as "author_name", g.genre_id, g.name as "genre_name", b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.book_id="200" LIMIT 1) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of a specific book by original_key --
SELECT b.book_id, b.title, a.author_id, a.name as "author_name", g.genre_id, g.name as "genre_name", b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.original_key="OL11971117M" LIMIT 1) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of a specific book by isbn --
SELECT b.book_id, b.title, a.author_id, a.name as "author_name", g.genre_id, g.name as "genre_name", b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.isbn="1434483673" LIMIT 1) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by language --
SELECT b.book_id, b.title, a.author_id, a.name as "author_name", g.genre_id, g.name as "genre_name", b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.language="cat" LIMIT 10) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by number of pages --
SELECT b.*, a.name as "author_name", g.name as "genre_name", p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.number_of_pages BETWEEN 1000 AND 1500 LIMIT 10) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by publish year --
SELECT b.*, a.name as "author_name", g.name as "genre_name", p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.publish_year BETWEEN 1890 AND 1900 LIMIT 10) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by author id --
SELECT b.*, a.name as "author_name", g.name as "genre_name", p.name as "publisher_name"
FROM 
    (SELECT * FROM book_author WHERE book_author.author_id=4 LIMIT 10) as ba
    LEFT JOIN book b ON ba.book_id = b.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by genre id --
SELECT b.*, a.name as "author_name", g.name as "genre_name", p.name as "publisher_name"
FROM 
    (SELECT * FROM book_genre WHERE book_genre.genre_id=864 LIMIT 10) as bgs
    LEFT JOIN book b ON bgs.book_id = b.book_id
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select details of 10 books by publisher id --
SELECT b.*, a.author_id, a.name as "author_name", g.genre_id, g.name as "genre_name", b.publisher_id, p.name as "publisher_name"
FROM 
    (SELECT * FROM book WHERE book.publisher_id="420" LIMIT 10) as b
    LEFT JOIN book_author ba ON b.book_id = ba.book_id
    LEFT JOIN author a ON ba.author_id  = a.author_id 
    LEFT JOIN book_genre bg ON b.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = b.publisher_id 
ORDER BY b.book_id;

-- select grouped details of 25 books --
SELECT *
FROM
    (SELECT c.original_key, c.isbn, c.number_of_pages, c.language, c.publish_year, c.book_id, c.title, c.author, GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, p.publisher_id, p.name as "publisher_name"
    FROM 
        (SELECT b.original_key, b.isbn, b.number_of_pages, b.language, b.publish_year, b.book_id, b.title, b.publisher_id, GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
        FROM 
            (SELECT * FROM book 
            WHERE MATCH(book.title) AGAINST('( *religion* ) ("religion")' IN NATURAL LANGUAGE MODE)
            AND book.number_of_pages BETWEEN 224 AND 814
            AND book.publish_year BETWEEN 1753 AND 2015) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id  = a.author_id
        GROUP BY b.book_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = c.publisher_id 
    GROUP BY c.book_id) as d
WHERE UPPER(d.publisher_name) LIKE UPPER("%publishing%")
AND UPPER(d.author) LIKE UPPER("%john%")
AND UPPER(d.genre) LIKE UPPER("%general%") 
LIMIT 25;

-- select multiple genres
SELECT *
FROM
    (SELECT  c.original_key, c.isbn, c.number_of_pages, c.language, c.publish_year, c.book_id, c.title, c.author, GROUP_CONCAT(g.genre_id, ":", g.name ORDER BY g.name separator "," ) as genre, p.publisher_id, p.name as "publisher_name"
    FROM 
        (SELECT  b.original_key, b.isbn, b.number_of_pages, b.language, b.publish_year, b.book_id, b.title, b.publisher_id, GROUP_CONCAT(a.author_id, ":", a.name ORDER BY a.name separator "," ) as author
        FROM 
            (SELECT *  FROM book 
            
            
            ) as b
        LEFT JOIN book_author ba ON b.book_id = ba.book_id
        LEFT JOIN author a ON ba.author_id  = a.author_id
        GROUP BY b.book_id) as c
    LEFT JOIN book_genre bg ON c.book_id = bg.book_id
    LEFT JOIN genre g ON bg.genre_id  = g.genre_id 
    LEFT JOIN publisher p ON p.publisher_id  = c.publisher_id 
    GROUP BY c.book_id) as d

WHERE UPPER(d.genre) LIKE UPPER("%children%") 
OR UPPER(d.genre) LIKE UPPER("%animal%") 


LIMIT 25;