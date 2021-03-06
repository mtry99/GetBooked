**Demo of the project:**
https://drive.google.com/file/d/1OQk70zmkWzQlz7p54TaatKIqkjIXNg0n/view 

To create and load the test database to MySQL, create the bookDB database based on "schema/create-book-db.sql" to create the database and "schema/import-data.sql" to import the data.
To run the application, use `php -S localhost:3000` and open `localhost:3000/login.php` in the browser.

Use phpmyadmin to directly import the file "schema/bookdb.sql.zip".

Run queries in "schema/test-book-db.sql" to test the data.

Open login.php to start the app.

Admin can login in using username: account2 password: password2

Data from https://openlibrary.org/developers/dumps

Currently Supported Features:
1. Account creation: Users that wish to use the library system will have the ability to self-register and create a library account. The sign-up page will prompt users to enter their name, username, and password. Provided that the username is unique, the account will be created by adding an entry to the user table in the database. If the account creation fails due to duplicate username or the new user information cannot be added to the database, an error message will appear. Account creation and login is implemented in login.php, register.php, create-book-db.sql.

2. Dynamic search: Users will be able to search for books in the system through a variety of parameters including title, author, ISBN, and genre. A dynamic search bar at the top of  the user’s account page will facilitate this. 

3. Borrowing books: Once users have found a book they like and it is available in the system, the user can borrow the book by clicking a ‘checkout’ button. This will create an entry in the book log for that particular user along with a  return date for the book. 

4. Log history and returning books: Users can check the complete history of books they have borrowed in the past with options to return or renew them. Administrators can check the book history for any user.  

5. Adding a new book: If an administrator logs into the library system, there is a tab for them to add a new book. This will lead them to another page/form where they can add a new book by specifying parameters like ‘Book name’, ‘ISBN number’, ‘Author Name’, ‘genres’. The header.php, new-book.php, create-book-db.sql files implement this feature.

6. Late returns and fines: When a user borrows a book, there should be a return by date given to them indicating when the book is due. If a user doesn’t return a book by its due date, a per day fine will incur on their account calculated by finding the difference between the actual return date and return by date. If the fines are greater than $20, the user will be unable to borrow any books. The SQL queries for these functions can be found in schema/create-book-db.sql. They are implemented in fines.php and pay-fine.php. 

7. Opening Collections: Users are able to unbox random books from a selected collection, and the book that was unboxed will be added to their inventory.

8. Viewing Inventory: Users are able to view all the books that they unboxed in a cool interactive format. Users can also choose to trade-up five equal rarity books into one book of one higher rarity. Users can also view their BBuck on this page. BBuck is a point system used to keep track of how good an user’s inventory is. Each book in an user’s inventory increased the rate at which their BBuck count goes up, with a higher rarity increasing the rate more.




