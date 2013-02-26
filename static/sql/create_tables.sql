CREATE TABLE suppliers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(400) NOT NULL,
  tel VARCHAR(100), # Convert this to INT
  email VARCHAR(400),
  purchase_area VARCHAR(200),
  user_id INT,
  deleted INT default 0
);

# Yes, i am aware that i am not using foreign keys. I will get to that once MySQL seems to
# want to accept it

CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  subtype_id INT UNSIGNED,
  supplier_id INT UNSIGNED,
  stock INT,
  date_entered TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  user_id INT UNSIGNED ,
  deleted INT DEFAULT 0
);

CREATE TABLE subtypes (
  id INT PRIMARY KEY AUTO_INCREMENT ,
  name VARCHAR (400),
  type_id INT UNSIGNED ,
  deleted INT DEFAULT 0
);

CREATE TABLE types (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR (400),
  deleted INT DEFAULT 0
);

CREATE TABLE users (
  id INT PRIMARY KEY AUTO_INCREMENT ,
  username VARCHAR (25),
  first_name VARCHAR  (30),
  password VARCHAR (48) # May need to use password field
);
