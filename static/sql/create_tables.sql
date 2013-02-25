CREATE TABLE suppliers (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(400) NOT NULL,
  tel VARCHAR(100),
  user_id INT,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

