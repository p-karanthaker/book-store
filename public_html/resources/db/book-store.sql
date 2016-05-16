-- Tables to create in DB
-- user (userID, password, type, balance)
-- books (bookID, title, authors, catID, quantity, price, description, photo)
-- basket (orderID, userID, bookID, quantity, cost, date)
-- category (catID, name)

CREATE TABLE user 
(
	user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
  username VARCHAR(12), 
  password_hash CHAR(60),
  password_salt CHAR(44),
  type ENUM('STUDENT', 'STAFF'), 
  balance DECIMAL(13,2) NOT NULL DEFAULT '0.00';
);

CREATE TABLE category
(
	cat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50)
);

CREATE TABLE books
(
	book_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  authors VARCHAR(100),
  cat_id INT NOT NULL,
  FOREIGN KEY(cat_id) REFERENCES category(cat_id),
  quantity INT NOT NULL DEFAULT '0',
  price DECIMAL(13,2) NOT NULL DEFAULT '0.00',
  description VARCHAR(500),
  photo VARCHAR(100)
);

CREATE TABLE basket
(
	order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  book_id INT NOT NULL,
  FOREIGN KEY(user_id) REFERENCES user(user_id),
  FOREIGN KEY(book_id) REFERENCES books(book_id),
  quantity INT NOT NULL DEFAULT '0',
  cost DECIMAL(13,2) NOT NULL DEFAULT '0.00',
  date DATETIME
);