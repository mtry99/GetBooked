-- truncate all tables first, then --
TRUNCATE `book_author`;
DROP TABLE `book_author`;
TRUNCATE `book_genre`;
DROP TABLE `book_genre`;
TRUNCATE `book`;
DROP TABLE `book`;
TRUNCATE `author`;
TRUNCATE `genre`;
TRUNCATE `publisher`;

DROP DATABASE importDB;
