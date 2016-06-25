DELIMITER $$
CREATE PROCEDURE `AddItemToBasket`(IN user_id INT, IN book_id INT)
BEGIN
	IF EXISTS (SELECT * FROM basket WHERE basket.user_id = user_id) THEN
		INSERT INTO basketitem (basket_id, book_id, quantity, cost)
		SELECT 	basket.basket_id,
				book_id,
				1,
				books.price
		FROM basket, books
		WHERE basket.user_id = user_id
		AND books.book_id = book_id;
	ELSE
		INSERT INTO basket (basket.user_id) VALUES (user_id);
		INSERT INTO basketitem (basket_id, book_id, quantity, cost)
		SELECT 	basket.basket_id,
				book_id,
				1,
				books.price
		FROM basket, books
		WHERE basket.user_id = user_id
		AND books.book_id = book_id;
	END IF;
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
			COUNT(bi.book_id) AS 'quantity',
			SUM(bi.cost) AS 'price'
	FROM basket bsk
	INNER JOIN basketitem bi
		ON bi.basket_id = bsk.basket_id
	INNER JOIN books b
		ON b.book_id = bi.book_id
	WHERE bsk.user_id = user_id
	GROUP BY book_id;
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
CREATE PROCEDURE `RemoveItemFromBasket`(IN user_id INT, IN book_id INT, IN new_amount INT)
BEGIN
	DECLARE remove_limit INT;
	-- Get the current number of item in the basket
	SET @current_amount = 
	(
		SELECT count(*) FROM basketitem
		INNER JOIN basket
		ON basket.basket_id = basketitem.basket_id
		WHERE basket.user_id = user_id
		AND basketitem.book_id = book_id
	);
	SET remove_limit = @current_amount - new_amount;
	IF remove_limit > 0 THEN
		DELETE FROM basketitem
		WHERE basket_id IN
		(
			SELECT b.basket_id FROM basket b
			WHERE b.user_id = user_id
			AND basketitem.book_id = book_id
		) LIMIT remove_limit;
	END IF;
END$$

GRANT EXECUTE ON PROCEDURE book_store.AddItemToBasket TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBasketByUserId TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBookById TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.GetBooksByCategory TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.RemoveItemFromBasket TO 'bs_user'@'localhost';
GRANT EXECUTE ON PROCEDURE book_store.EmptyBasket TO 'bs_user'@'localhost';