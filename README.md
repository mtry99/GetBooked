To create and load the test database to MySQL, 
create the bookDB database based on "schema/create-book-db.sql"

Use phpmyadmin to directly import the file "schema/bookdb.sql.zip".

Run queries in "schema/test-book-db.sql" to test the data.

Open login.php to start the app.

Admin can login in using username: account2 password: password2

Data from https://openlibrary.org/developers/dumps

Currently Supported Features:
1. Account creation: Users that wish to use the library system will have the ability to self-register and create a library account. The sign-up page will prompt users to enter their name, username, and password. Provided that the username is unique, the account will be created by adding an entry to the user table in the database. If the account creation fails due to duplicate username or the new user information cannot be added to the database, an error message will appear. Account creation and login is implemented in login.php, register.php, create-book-db.sql.

2. Dynamic search: Users will be able to search for books in the system through a variety of parameters including title, author, ISBN, and genre. A dynamic search bar at the top of  the user’s account page will facilitate this. 

3. Borrowing books: Once users have found a book they like and it is available in the system, the user can borrow the book by clicking a ‘checkout’ button. This will create an entry in the book log for that particular user along with a  return date for the book. 

4. Adding a new book: If an administrator logs into the library system, there is a tab for them to add a new book. This will lead them to another page/form where they can add a new book by specifying parameters like ‘Book name’, ‘ISBN number’, ‘Author Name’, ‘genres’. The header.php, new-book.php, create-book-db.sql files implement this feature.

5. Opening Collections: Users are able to unbox random books from a selected collection, and the book that was unboxxed will be added to their inventory.

6. Viewing Inventory: Users are able to view all the books that they unboxxed in a cool interactive format.