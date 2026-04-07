CREATE DATABASE IF NOT EXISTS barangay_db;
USE barangay_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('Captain', 'Kagawad', 'Secretary', 'Treasurer', 'SK Chairman') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample users (password is 'password123')
INSERT INTO users (username, password, full_name, role) VALUES 
('captain', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Hon. Juan Dela Cruz', 'Captain'),
('kagawad', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro Penduko', 'Kagawad'),
('secretary', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Clara', 'Secretary'),
('treasurer', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jose Rizal', 'Treasurer'),
('sk_chairman', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andres Bonifacio', 'SK Chairman');

CREATE TABLE IF NOT EXISTS residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    birthdate DATE,
    gender ENUM('Male', 'Female'),
    address TEXT,
    contact_no VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS financial_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('Income', 'Expense'),
    amount DECIMAL(10,2),
    description TEXT,
    category ENUM('General', 'SK Fund', 'Project', 'Salary'),
    date_recorded TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
