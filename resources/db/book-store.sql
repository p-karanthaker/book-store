-- Tables to create in DB
-- user (userID, password, type, balance)
-- books (bookID, title, authors, catID, quantity, price, description, photo)
-- basket (orderID, userID, bookID, quantity, cost, date)
-- category (catID, name)

CREATE TABLE user 
(
	user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(12), 
    password CHAR(40), 
    type ENUM('STUDENT', 'STAFF'), 
    balance DECIMAL(13,2)
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
    quantity INT,
    price DECIMAL(13,2),
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
    quantity INT,
    cost DECIMAL(13,2),
    date DATETIME
);