-- Create database
CREATE DATABASE IF NOT EXISTS noir_restaurant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE noir_restaurant;

-- Users and roles
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  name VARCHAR(190) NOT NULL,
  phone VARCHAR(64) DEFAULT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Reservations
CREATE TABLE IF NOT EXISTS reservations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  name VARCHAR(190) NOT NULL,
  phone VARCHAR(64) NOT NULL,
  email VARCHAR(190) NULL,
  date DATE NOT NULL,
  time TIME NOT NULL,
  people TINYINT UNSIGNED NOT NULL,
  message TEXT NULL,
  status ENUM('new','confirmed','cancelled') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (date, time),
  CONSTRAINT fk_res_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Seed admin
INSERT INTO users (email, password_hash, name, phone, role) VALUES
('admin@noir.local', SHA2('admin123', 256), 'Администратор', NULL, 'admin')
ON DUPLICATE KEY UPDATE email=email;