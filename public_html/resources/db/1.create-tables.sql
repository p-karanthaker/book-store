CREATE TABLE IF NOT EXISTS `thakerp_db`.`user` (
    `user_id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(12) NULL DEFAULT NULL,
    `password_hash` CHAR(60) NULL DEFAULT NULL,
    `type` ENUM('STUDENT', 'STAFF') NULL DEFAULT NULL,
    `balance` DECIMAL(13 , 2 ) NOT NULL DEFAULT '0.00',
    PRIMARY KEY (`user_id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`books` (
    `book_id` INT(11) NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100) NULL DEFAULT NULL,
    `authors` VARCHAR(100) NULL DEFAULT NULL,
    `quantity` INT(11) NOT NULL DEFAULT '0',
    `price` DECIMAL(13 , 2 ) NOT NULL DEFAULT '0.00',
    `description` VARCHAR(5000) NULL DEFAULT NULL,
    PRIMARY KEY (`book_id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`category` (
    `cat_id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(50) NULL DEFAULT NULL,
    PRIMARY KEY (`cat_id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`bookcategory` (
    `book_id` INT(11) NOT NULL,
    `cat_id` INT(11) NOT NULL,
    INDEX `book_id` (`book_id` ASC),
    INDEX `cat_id` (`cat_id` ASC),
    CONSTRAINT `bookcategory_ibfk_1` FOREIGN KEY (`book_id`)
        REFERENCES `thakerp_db`.`books` (`book_id`),
    CONSTRAINT `bookcategory_ibfk_2` FOREIGN KEY (`cat_id`)
        REFERENCES `thakerp_db`.`category` (`cat_id`)
)  ENGINE=InnoDB DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`basket` (
    `basket_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    PRIMARY KEY (`basket_id`),
    UNIQUE INDEX `user_id_UNIQUE` (`user_id` ASC),
    CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`user_id`)
        REFERENCES `thakerp_db`.`user` (`user_id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`basketitem` (
    `basket_id` INT(11) NOT NULL,
    `book_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL DEFAULT '0',
    `cost` DECIMAL(13 , 2 ) NOT NULL DEFAULT '0.00',
    UNIQUE INDEX `book_basket_UNIQUE` (`basket_id` ASC , `book_id` ASC),
    INDEX `book_id` (`book_id` ASC),
    CONSTRAINT `basketitem_ibfk_1` FOREIGN KEY (`basket_id`)
        REFERENCES `thakerp_db`.`basket` (`basket_id`),
    CONSTRAINT `basketitem_ibfk_2` FOREIGN KEY (`book_id`)
        REFERENCES `thakerp_db`.`books` (`book_id`)
)  ENGINE=InnoDB DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`orders` (
    `order_id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `active` TINYINT(1) NOT NULL DEFAULT '1',
    `date` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`order_id`),
    INDEX `user_id` (`user_id` ASC),
    CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`)
        REFERENCES `thakerp_db`.`user` (`user_id`)
)  ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARACTER SET=latin1;

CREATE TABLE IF NOT EXISTS `thakerp_db`.`orderitem` (
    `order_id` INT(11) NOT NULL,
    `book_id` INT(11) NOT NULL,
    `quantity` INT(11) NOT NULL,
    `cost` DECIMAL(13 , 2 ) NOT NULL,
    INDEX `order_id` (`order_id` ASC),
    INDEX `book_id` (`book_id` ASC),
    CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`)
        REFERENCES `thakerp_db`.`orders` (`order_id`),
    CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`book_id`)
        REFERENCES `thakerp_db`.`books` (`book_id`)
)  ENGINE=InnoDB DEFAULT CHARACTER SET=latin1
