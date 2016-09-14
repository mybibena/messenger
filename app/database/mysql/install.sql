DELIMITER $$

DROP TABLE IF EXISTS users$$
CREATE TABLE users (
  id MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
  login VARCHAR (50) NOT NULL,
  password CHAR (40) NOT NULL,
  first_name VARCHAR (50) NOT NULL,
  last_name VARCHAR (50) NOT NULL,
  is_admin TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
  is_deleted TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
  update_date DATETIME DEFAULT NULL,
  create_date DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX login (login)
)
ENGINE = InnoDB
CHARACTER SET utf8
COLLATE utf8_general_ci$$

DROP TABLE IF EXISTS messages$$
CREATE TABLE messages (
  id MEDIUMINT(9) UNSIGNED NOT NULL AUTO_INCREMENT,
  sender MEDIUMINT(9) UNSIGNED NOT NULL,
  receiver MEDIUMINT(9) UNSIGNED NOT NULL,
  text TEXT,
  is_read TINYINT(2) UNSIGNED NOT NULL DEFAULT 0,
  create_date DATETIME DEFAULT NULL,
  PRIMARY KEY (id),
  INDEX sender_receiver (sender, receiver),
  INDEX is_read (is_read),
  FOREIGN KEY (sender) REFERENCES users(id),
  FOREIGN KEY (receiver) REFERENCES users(id)
)
  ENGINE = InnoDB
  CHARACTER SET utf8
  COLLATE utf8_general_ci$$

DROP TRIGGER IF EXISTS `users_insert`$$
CREATE TRIGGER `users_insert`
BEFORE INSERT
ON `users`
FOR EACH ROW
  BEGIN
    SET time_zone = '+0:00';
    SET NEW.update_date = NOW();
    SET NEW.create_date = NOW();
  END$$

DROP TRIGGER IF EXISTS `users_update`$$
CREATE TRIGGER `users_update`
BEFORE UPDATE
ON `users`
FOR EACH ROW
  BEGIN
    SET time_zone = '+0:00';
    SET NEW.update_date = NOW();
  END$$

DROP TRIGGER IF EXISTS `messages_insert`$$
CREATE TRIGGER `messages_insert`
BEFORE INSERT
ON `messages`
FOR EACH ROW
  BEGIN
    SET time_zone = '+0:00';
    SET NEW.create_date = NOW();
  END$$