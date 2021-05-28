
import json
import mysql.connector
from string import punctuation
from dateutil.parser import parse
import re

mydb = mysql.connector.connect(
  host="localhost",
  user="importperson",
  password="genericpassword",
  database="importdb"
)

print(mydb)

mycursor = mydb.cursor()

insert_book_sql = "INSERT IGNORE INTO `book` (`book_id`, `title`, `original_key`, `isbn`, `number_of_pages`, `language`, `publisher_id`, `publish_year`) VALUES (NULL, %s, %s, %s, %s, %s, %s, %s);"
insert_author_sql = "INSERT IGNORE INTO `author` (`author_id`, `name`, `original_key`) VALUES (NULL, NULL, %s);"
insert_genre_sql = "INSERT IGNORE INTO `genre` (`genre_id`, `name`) VALUES (NULL, %s);"
insert_publisher_sql = "INSERT IGNORE INTO `publisher` (`publisher_id`, `name`) VALUES (NULL, %s);"

insert_book_author_sql = "INSERT IGNORE INTO `book_author` (`book_id`, `author_id`) VALUES (%s, %s);"
insert_book_genre_sql = "INSERT IGNORE INTO `book_genre` (`book_id`, `genre_id`) VALUES (%s, %s);"

select_author_sql = "SELECT `author_id` FROM `author` WHERE `author`.`original_key`=%s"
select_genre_sql = "SELECT `genre_id` FROM `genre` WHERE `genre`.`name`=%s"
select_publisher_sql = "SELECT `publisher_id` FROM `publisher` WHERE `publisher`.`name`=%s"

lineCnt = 1

with open("og_editions/og_editions.txt") as infile:
    for line in infile:
        lineCnt += 1

        if lineCnt < 399000 - 1350:
            continue
        if lineCnt % 1000 == 0:
            print(lineCnt)
            mydb.commit()
        if lineCnt > 500000:
            break
        
        #print(lineCnt)

        index = line.find("{")
        lineJson = json.loads(line[index:])

        if ("publishers" in lineJson) and \
            ("isbn_10" in lineJson) and \
            ("key" in lineJson) and \
            ("authors" in lineJson) and \
            ("subjects" in lineJson) and \
            ("title" in lineJson) and \
            ("number_of_pages" in lineJson) and \
            ("languages" in lineJson) and \
            ("publish_date" in lineJson):

            p_year = 0000
            try:
                p_year = parse(lineJson["publish_date"], fuzzy=True).year
            except:
                continue

            val = (lineJson["publishers"][0].strip(),)
                
            mycursor.execute(select_publisher_sql, val)
            myresult = mycursor.fetchone()

            publisher_lastrowid = 0                

            if myresult == None:
                mycursor.execute(insert_publisher_sql, val)
                publisher_lastrowid = mycursor.lastrowid
            else:
                publisher_lastrowid = myresult[0]

            try:
                val = (lineJson["title"], lineJson["key"][7:], lineJson["isbn_10"][0], str(lineJson["number_of_pages"]),
                   lineJson["languages"][0]["key"][11:], publisher_lastrowid, str(p_year))
            except:
                continue
            
            #print("title:", val)
            #continue
            mycursor.execute(insert_book_sql, val)
            book_lastrowid = mycursor.lastrowid

            for author in lineJson["authors"]:
                val = (author["key"],)
                #print("author:", val)
                
                mycursor.execute(select_author_sql, val)
                myresult = mycursor.fetchone()

                author_lastrowid = 0                

                if myresult == None:
                    mycursor.execute(insert_author_sql, val)
                    author_lastrowid = mycursor.lastrowid
                else:
                    author_lastrowid = myresult[0]
                
                val = (book_lastrowid, author_lastrowid)
                mycursor.execute(insert_book_author_sql, val)

            for subject in lineJson["subjects"]:
                gen_str = re.split('[\\/\s;,.\-\%]', subject.strip())[0].translate(str.maketrans('', '', punctuation))
                if gen_str.isnumeric():
                    continue
                val = (gen_str.lower(),)
                #print("genre:", val)
                
                mycursor.execute(select_genre_sql, val)
                myresult = mycursor.fetchone()

                genre_lastrowid = 0                

                if myresult == None:
                    mycursor.execute(insert_genre_sql, val)
                    genre_lastrowid = mycursor.lastrowid
                else:
                    genre_lastrowid = myresult[0]
                
                val = (book_lastrowid, genre_lastrowid)
                mycursor.execute(insert_book_genre_sql, val)

    mydb.commit()
