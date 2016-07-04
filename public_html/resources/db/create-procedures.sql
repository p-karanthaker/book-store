DELIMITER $$
CREATE PROCEDURE `AddItemToBasket`(IN user_id INT, IN book_id INT, IN quantity INT)
BEGIN
	-- Create a basket for the user, ignore if they already have one
	INSERT IGNORE INTO basket (basket.user_id) VALUES (user_id);
    
    -- Add the book to the basket, if already in the basket then update the quantity
    INSERT INTO basketitem (basket_id, book_id, quantity, cost)
		SELECT 	basket.basket_id,
				book_id,
				quantity,
				books.price
		FROM basket, books
		WHERE basket.user_id = user_id
		AND books.book_id = book_id
	ON DUPLICATE KEY UPDATE basketitem.quantity=basketitem.quantity+quantity;
END$$

DELIMITER $$
CREATE PROCEDURE `EmptyBasket`(IN user_id INT)
BEGIN
	DELETE FROM basketitem
	WHERE basket_id IN
	(
		SELECT b.basket_id FROM basket b
		WHERE b.user_id = user_id
	);
END$$

DELIMITER $$
CREATE PROCEDURE `GetBasketByUserId`(IN user_id INT)
BEGIN
	SELECT  bi.book_id,
			b.title,
			bi.quantity,
			bi.cost,
			bi.cost * bi.quantity AS 'subtotal'
	FROM basket bsk
	INNER JOIN basketitem bi
		ON bi.basket_id = bsk.basket_id
	INNER JOIN books b
		ON b.book_id = bi.book_id
	WHERE bsk.user_id = user_id;
END$$

DELIMITER $$
CREATE PROCEDURE `GetBookById`(IN bookId INT)
BEGIN
	SELECT  b.book_id,
			b.title,
			b.authors,
			b.description,
			group_concat(' ', c.name) AS 'category',
			b.quantity,
			b.price
	FROM
	(
		SELECT book_id, cat_id FROM bookcategory
	) AS bc
	INNER JOIN books b
		ON b.book_id = bc.book_id
	INNER JOIN category c
		ON c.cat_id = bc.cat_id
	WHERE b.book_id = bookId
	GROUP BY b.book_id;
END$$

DELIMITER $$
CREATE PROCEDURE `GetBooksByCategory`(IN categoryName VARCHAR(50))
BEGIN
	SELECT  b.book_id,
			b.title,
			b.authors,
			b.description,
			group_concat(' ', c.name) AS 'category',
			b.quantity,
			b.price
	FROM
	(
		SELECT book_id, cat_id FROM bookcategory
	) AS bc
	INNER JOIN books b
		ON b.book_id = bc.book_id
	INNER JOIN category c
		ON c.cat_id = bc.cat_id
	WHERE c.name LIKE CONCAT("%", categoryName, "%")
	GROUP BY b.book_id;
END$$

DELIMITER $$
CREATE PROCEDURE `GetOrderById`(IN order_id INT)
BEGIN
	SELECT 
		o.order_id,
		b.title,
		oi.quantity,
		oi.cost,
		oi.cost * oi.quantity AS 'subtotal',
		o.`date`
	FROM
		orders o
	INNER JOIN orderitem oi
		ON oi.order_id = o.order_id
	INNER JOIN books b
		ON b.book_id = oi.book_id
	WHERE
		oi.order_id LIKE order_id;
END$$

DELIMITER $$
CREATE PROCEDURE `UpdateBasket`(IN userid INT, IN bookid INT, IN new_amount INT)
BEGIN
	DECLARE remove_limit INT;
	-- Get the current number of item in the basket
	SET @current_amount = 
	(
		SELECT quantity FROM basketitem
		INNER JOIN basket
		ON basket.basket_id = basketitem.basket_id
		WHERE basket.user_id = userid
		AND basketitem.book_id = bookid
	);
    IF new_amount > @current_amount THEN
		SET @add_amount = new_amount - @current_amount;
		CALL AddItemToBasket(userid, bookid, @add_amount);
	ELSEIF new_amount < @current_amount AND new_amount > 0 THEN
		UPDATE basketitem AS bi
        INNER JOIN basket b
			ON b.basket_id = bi.basket_id
        SET quantity=new_amount
			WHERE bi.book_id=bookid
			AND b.user_id=userid;
	ELSEIF new_amount = 0 THEN
		SET @basket_id = 
		(
			SELECT MAX(basket_id) FROM basket
			WHERE basket.user_id = userid
		);
		SET @bookid = bookid;
		DELETE FROM basketitem
		WHERE basketitem.book_id = @bookid
		AND basketitem.basket_id = @basket_id;
	END IF;
END$$

DELIMITER $$
CREATE FUNCTION `PlaceOrder`(user_id INT) RETURNS int(11)
BEGIN
	-- Check if basket has items
	SET @basketItemCount = 
	(
		SELECT count(*) FROM basketitem
		INNER JOIN basket
		ON basket.basket_id = basketitem.basket_id
		WHERE basket.user_id = user_id
	);

	-- If basket has items then...
	IF @basketItemCount > 0 THEN
		-- Insert a new order into the order table
		SET @orderTime = NOW();
		INSERT INTO orders (user_id, active, `date`)
		VALUES (user_id, true, @orderTime);
		
		SET @orderId = LAST_INSERT_ID();
		-- Insert items from basket into the order item table
		INSERT INTO orderitem (order_id, book_id, quantity, cost)
		SELECT  @orderId,
				bi.book_id,
				bi.quantity,
				bi.cost
		FROM basket bsk
		INNER JOIN basketitem bi
			ON bi.basket_id = bsk.basket_id
		INNER JOIN books b
			ON b.book_id = bi.book_id
		WHERE bsk.user_id = user_id
		GROUP BY book_id;
		
		-- Empty the basket and return the order id
		CALL EmptyBasket(user_id);
		RETURN @orderId;
	END IF;
	RETURN NULL;
END$$

DELIMITER$$
CREATE FUNCTION `CompleteOrder`(orderid INT) RETURNS tinyint(1)
BEGIN
	SET @user = 
    (
		SELECT
			user_id
		FROM orders
        WHERE order_id = orderid
	);
	SET @totalcost =
    (
		SELECT 
			SUM(oi.quantity * oi.cost) AS 'total'
		FROM orderitem oi
		INNER JOIN orders o
		ON o.order_id = oi.order_id
		WHERE oi.order_id = orderid
	);
    SET @balance = 
    (
		SELECT 
			balance
		FROM `user`
        WHERE user_id = @user
	);
    
    SET @newbalance = @balance - @totalcost;
    IF @newbalance < 0 THEN
		RETURN FALSE;
	ELSE 
		UPDATE `user` SET balance = @newbalance WHERE user_id = @user;
        UPDATE orders SET active = FALSE WHERE order_id = orderid;
        RETURN TRUE;
	END IF;
END$$

GRANT EXECUTE ON PROCEDURE book_store.AddItemToBasket TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBasketByUserId TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBookById TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBooksByCategory TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetOrderById TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.UpdateBasket TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.EmptyBasket TO 'bs_user'@'localhost';
GRANT EXECUTE ON FUNCTION book_store.PlaceOrder TO 'bs_user'@'localhost';
GRANT EXECUTE ON FUNCTION book_store.CompleteOrder TO 'bs_user'@'localhost';
