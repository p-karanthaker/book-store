SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `book_store` DEFAULT CHARACTER SET utf8 ;
USE `book_store` ;

-- -----------------------------------------------------
-- Table `book_store`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`user` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(12) NULL DEFAULT NULL,
  `password_hash` CHAR(60) NULL DEFAULT NULL,
  `type` ENUM('STUDENT','STAFF') NULL DEFAULT NULL,
  `balance` DECIMAL(13,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`user_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`basket`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`basket` (
  `basket_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`basket_id`),
  UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
  CONSTRAINT `basket_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `book_store`.`user` (`user_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`books`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`books` (
  `book_id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NULL DEFAULT NULL,
  `authors` VARCHAR(100) NULL DEFAULT NULL,
  `quantity` INT(11) NOT NULL DEFAULT '0',
  `price` DECIMAL(13,2) NOT NULL DEFAULT '0.00',
  `description` VARCHAR(5000) NULL DEFAULT NULL,
  `photo` VARCHAR(500) NULL DEFAULT NULL,
  PRIMARY KEY (`book_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 71
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`basketitem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`basketitem` (
  `basket_id` INT(11) NOT NULL,
  `book_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT '0',
  `cost` DECIMAL(13,2) NOT NULL DEFAULT '0.00',
  INDEX `basket_id` (`basket_id` ASC),
  INDEX `book_id` (`book_id` ASC),
  CONSTRAINT `basketitem_ibfk_1`
    FOREIGN KEY (`basket_id`)
    REFERENCES `book_store`.`basket` (`basket_id`),
  CONSTRAINT `basketitem_ibfk_2`
    FOREIGN KEY (`book_id`)
    REFERENCES `book_store`.`books` (`book_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`category` (
  `cat_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`cat_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`bookcategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`bookcategory` (
  `book_id` INT(11) NOT NULL,
  `cat_id` INT(11) NOT NULL,
  INDEX `book_id` (`book_id` ASC),
  INDEX `cat_id` (`cat_id` ASC),
  CONSTRAINT `bookcategory_ibfk_1`
    FOREIGN KEY (`book_id`)
    REFERENCES `book_store`.`books` (`book_id`),
  CONSTRAINT `bookcategory_ibfk_2`
    FOREIGN KEY (`cat_id`)
    REFERENCES `book_store`.`category` (`cat_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`orders`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT '1',
  `date` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  INDEX `user_id` (`user_id` ASC),
  CONSTRAINT `orders_ibfk_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `book_store`.`user` (`user_id`))
ENGINE = InnoDB
AUTO_INCREMENT = 44
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `book_store`.`orderitem`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `book_store`.`orderitem` (
  `order_id` INT(11) NOT NULL,
  `book_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `cost` DECIMAL(13,2) NOT NULL,
  INDEX `order_id` (`order_id` ASC),
  INDEX `book_id` (`book_id` ASC),
  CONSTRAINT `orderitem_ibfk_1`
    FOREIGN KEY (`order_id`)
    REFERENCES `book_store`.`orders` (`order_id`),
  CONSTRAINT `orderitem_ibfk_2`
    FOREIGN KEY (`book_id`)
    REFERENCES `book_store`.`books` (`book_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

USE `book_store` ;

-- -----------------------------------------------------
-- procedure AddItemToBasket
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AddItemToBasket`(IN user_id INT(11), IN book_id INT(11))
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure EmptyBasket
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EmptyBasket`(IN user_id INT)
BEGIN
	DELETE FROM basketitem
	WHERE basket_id IN
	(
		SELECT b.basket_id FROM basket b
		WHERE b.user_id = user_id
	);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure GetBasketByUserId
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetBasketByUserId`(IN user_id INT(11))
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure GetBookById
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetBookById`(IN bookId INT(11))
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure GetBooksByCategory
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetBooksByCategory`(IN categoryName VARCHAR(50))
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

DELIMITER ;

-- -----------------------------------------------------
-- procedure GetOrderById
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetOrderById`(IN order_id INT)
BEGIN
	SELECT 
		o.order_id,
		b.title,
		oi.quantity,
		oi.cost,
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

DELIMITER ;

-- -----------------------------------------------------
-- function PlaceOrder
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `PlaceOrder`(user_id INT) RETURNS int(11)
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
				COUNT(bi.book_id) AS 'quantity',
				SUM(bi.cost) AS 'price'
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
	RETURN null;
END$$

DELIMITER ;

-- -----------------------------------------------------
-- procedure RemoveItemFromBasket
-- -----------------------------------------------------

DELIMITER $$
USE `book_store`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `RemoveItemFromBasket`(IN user_id INT, IN book_id INT, IN new_amount INT)
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

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
