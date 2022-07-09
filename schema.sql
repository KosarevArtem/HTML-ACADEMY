DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE yeticave
DEFAULT CHARACTER SET utf8 
DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cat_name VARCHAR(128),
  code VARCHAR(128) UNIQUE
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  user_name VARCHAR(128),
  email VARCHAR(128) NOT NULL UNIQUE,
  user_password CHAR(255),
  contacts TEXT
);

CREATE TABLE lots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_finish DATE,
  title VARCHAR(255),
  lot_description TEXT,
  start_price INT,
  img VARCHAR(255),
  bet_step INT,
  category_id INT,
  author_id INT,
  winner_id INT,
  FOREIGN KEY (author_id) REFERENCES user(id),
  FOREIGN KEY (winner_id) REFERENCES user(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);


CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_bet TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  price_bet INT,
  user_id INT,
  lot_id INT,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);


CREATE FULLTEXT INDEX lot_ft_search ON lots(title, lot_description);