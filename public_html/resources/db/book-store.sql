-- Tables to create in DB
-- user (userID, password, type, balance)
-- books (bookID, title, authors, catID, quantity, price, description, photo)
-- basket (orderID, userID, bookID, quantity, cost, date)
-- category (catID, name)

CREATE TABLE User 
(
	user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	username VARCHAR(12), 
	password_hash CHAR(60),
	password_salt CHAR(44),
	type ENUM('STUDENT', 'STAFF'), 
	balance DECIMAL(13,2) NOT NULL DEFAULT '0.00'
);

CREATE TABLE Category
(
	cat_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50)
);

CREATE TABLE Books
(
	book_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(100),
	authors VARCHAR(100),
	quantity INT NOT NULL DEFAULT '0',
	price DECIMAL(13,2) NOT NULL DEFAULT '0.00',
	description VARCHAR(5000),
	photo VARCHAR(500)
);

CREATE TABLE BookCategory
(
	book_id INT NOT NULL,
	cat_id INT NOT NULL,
	FOREIGN KEY(book_id) REFERENCES Books(book_id),
	FOREIGN KEY(cat_id) REFERENCES Category(cat_id)
);

CREATE TABLE Basket
(
	basket_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	FOREIGN KEY(user_id) REFERENCES User(user_id)
);

CREATE TABLE BasketItem
(
	basket_id INT NOT NULL,
	book_id INT NOT NULL,
	FOREIGN KEY(basket_id) REFERENCES Basket(basket_id),
	FOREIGN KEY(book_id) REFERENCES Books(book_id),
	quantity INT NOT NULL DEFAULT '0',
	cost DECIMAL(13,2) NOT NULL DEFAULT '0.00'
);

CREATE TABLE Orders
(
	order_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	user_id INT NOT NULL,
	FOREIGN KEY(user_id) REFERENCES User(user_id),
	active BOOLEAN NOT NULL DEFAULT true,
	date DATETIME
);

CREATE TABLE OrderItem
(
	order_id INT NOT NULL,
	book_id INT NOT NULL,
	quantity INT NOT NULL,
	cost DECIMAL(13,2) NOT NULL,
	FOREIGN KEY(order_id) REFERENCES Orders(order_id),
	FOREIGN KEY(book_id) REFERENCES Books(book_id),
	FOREIGN KEY(quantity) REFERENCES BasketItem(quantity),
	FOREIGN KEY(cost) REFERENCES BasketItem(cost)
);
