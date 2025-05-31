
CREATE TABLE police_officers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  gl_number VARCHAR(50),
  rank VARCHAR(50),
  phone VARCHAR(15),
  email VARCHAR(100),
  password VARCHAR(255),
  role ENUM('admin','officer') DEFAULT 'officer',
  status ENUM('active','inactive') DEFAULT 'active'
);
CREATE TABLE leaves (
  id INT AUTO_INCREMENT PRIMARY KEY,
  officer_id INT,
  from_date DATE,
  to_date DATE,
  reason TEXT,
  status ENUM('pending','approved','rejected') DEFAULT 'pending'
);
CREATE TABLE duty_schedule (
  id INT AUTO_INCREMENT PRIMARY KEY,
  officer_id INT,
  duty_type VARCHAR(50),
  duty_date DATE
);
CREATE TABLE cases (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  description TEXT,
  officer_id INT,
  assigned_date DATE
);
