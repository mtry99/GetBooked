import json
import mysql.connector
from string import punctuation

mydb = mysql.connector.connect(
  host="localhost",
  user="importperson",
  password="genericpassword",
  database="importdb"
)

print(mydb)

mycursor = mydb.cursor()

update_author_sql = "UPDATE `author` SET `author`.`name` = %s WHERE `author`.`original_key`=%s;"

lineCnt = 1

with open("og_authors/og_authors.txt") as infile:
    for line in infile:
        lineCnt += 1

        if lineCnt < 0 - 1050:
            continue
        if lineCnt % 1000 == 0:
            print(lineCnt)
            mydb.commit()
        #if lineCnt > 1000:
            #break

        #print(line)

        index = line.find("{")
        lineJson = json.loads(line[index:])

        if ("key" in lineJson) and ("name" in lineJson):

            val = (lineJson["name"], lineJson["key"])
            #print("title:", val)
            mycursor.execute(update_author_sql, val)

    mydb.commit()
