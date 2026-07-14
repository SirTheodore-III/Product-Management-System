-- Run this once in phpMyAdmin (or mysql CLI) to set up the database.

CREATE DATABASE IF NOT EXISTS product_system;
USE product_system;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Separate table for images, linked to products (one product -> one image row here,
-- but structured as a proper one-to-many relationship via product_id foreign key).
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
