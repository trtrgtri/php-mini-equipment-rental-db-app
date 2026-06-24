DROP DATABASE IF EXISTS mini_equipment_rental;

CREATE DATABASE mini_equipment_rental
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE mini_equipment_rental;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff',
  status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE equipments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  equipment_code VARCHAR(50) NOT NULL,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(50),
  status VARCHAR(30) NOT NULL DEFAULT 'available',
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  UNIQUE KEY unique_equipment_code (equipment_code),
  INDEX idx_equipments_created_at (created_at),
  INDEX idx_equipments_status_created_at (status, created_at),
  INDEX idx_equipments_name (name)
);

CREATE TABLE rental_slips (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slip_code VARCHAR(50) NOT NULL,
  equipment_id INT NOT NULL,
  borrower_name VARCHAR(100) NOT NULL,
  borrower_email VARCHAR(150),
  status VARCHAR(30) NOT NULL DEFAULT 'borrowed',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL,
  UNIQUE KEY unique_slip_code (slip_code),
  INDEX idx_rental_slips_created_at (created_at),
  INDEX idx_rental_slips_status_created_at (status, created_at),
  INDEX idx_rental_slips_borrower_email (borrower_email),
  FOREIGN KEY (equipment_id) REFERENCES equipments(id) ON DELETE RESTRICT
);