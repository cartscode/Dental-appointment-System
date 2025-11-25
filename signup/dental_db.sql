CREATE TABLE users (
  id INT(11) AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50),
  last_name VARCHAR(50),
  number VARCHAR(20),
  email VARCHAR(100),
  emergency VARCHAR(20),
  username VARCHAR(50),
  password VARCHAR(255),
  month VARCHAR(20),
  day INT(2),
  year INT(4),
  gender VARCHAR(10),
  terms_accepted TINYINT(1) DEFAULT 0
);
