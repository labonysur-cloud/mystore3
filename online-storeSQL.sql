-- Drop and recreate the database
DROP DATABASE IF EXISTS mystore;
CREATE DATABASE mystore;
USE mystore;

-- Create tables

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    UNIQUE KEY unique_name_parent (name, parent_id),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS product_tags (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Insert categories
INSERT INTO categories (name, parent_id) VALUES ('Men', NULL);
INSERT INTO categories (name, parent_id) VALUES ('Women', NULL);

-- Insert Men subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', (SELECT id FROM categories WHERE name = 'Men'));
INSERT INTO categories (name, parent_id) VALUES ('Shoes', (SELECT id FROM categories WHERE name = 'Men'));
INSERT INTO categories (name, parent_id) VALUES ('Accessories', (SELECT id FROM categories WHERE name = 'Men'));

-- Insert Women subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', (SELECT id FROM categories WHERE name = 'Women'));
INSERT INTO categories (name, parent_id) VALUES ('Bags', (SELECT id FROM categories WHERE name = 'Women'));
INSERT INTO categories (name, parent_id) VALUES ('Claw Clip', (SELECT id FROM categories WHERE name = 'Women'));

-- Insert Men Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Dress 1', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/6c/76/d2/6c76d237471413e31939add0b8eeff90.jpg', 49.99, 10, NOW()),
('Men Dress 2', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/6e/7c/38/6e7c38000e732e90e540457936a73b04.jpg', 59.99, 15, NOW()),
('Men Dress 3', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/a0/c6/36/a0c636a27031d0529580ea41bbb7e4f3.jpg', 54.99, 12, NOW()),
('Men Dress 4', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/67/9f/94/679f94991d8e9f6c7d373977c4506fd7.jpg', 44.99, 8, NOW()),
('Men Dress 5', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/5c/68/d9/5c68d9d8567e48ec1360e3bbf29e6d34.jpg', 39.99, 10, NOW());

-- Insert Men Shoes products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Shoes 1', (SELECT id FROM categories WHERE name = 'Shoes' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/2d/8f/c2/2d8fc2170069ec8d5e4403add4c44030.jpg', 69.99, 20, NOW()),
('Men Shoes 2', (SELECT id FROM categories WHERE name = 'Shoes' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/8d/37/2f/8d372f11c7378dea155499b9d6fcbb51.jpg', 79.99, 10, NOW()),
('Men Shoes 3', (SELECT id FROM categories WHERE name = 'Shoes' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/0a/a1/f1/0aa1f15952eae49e61619b68730048e2.jpg', 74.99, 15, NOW()),
('Men Shoes 4', (SELECT id FROM categories WHERE name = 'Shoes' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/d9/94/87/d99487b947a35a2cabce90b5c49e27b5.jpg', 84.99, 12, NOW()),
('Men Shoes 5', (SELECT id FROM categories WHERE name = 'Shoes' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/d5/77/4d/d5774dc455ae811e404861d20bffb6b4.jpg', 89.99, 10, NOW());

-- Insert Men Accessories products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Accessory 1', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/f3/67/d0/f367d0dc87ac7b38d4a0cba82c589eec.jpg', 19.99, 25, NOW()),
('Men Accessory 2', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/11/35/da/1135dae13c5db93287e089bd7be4a0db.jpg', 24.99, 30, NOW()),
('Men Accessory 3', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/07/e8/0b/07e80b5262738fcb0dc7ad6b8c981100.jpg', 22.99, 20, NOW()),
('Men Accessory 4', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/c1/38/2b/c1382b66b32dc04b425b648615941037.jpg', 27.99, 18, NOW()),
('Men Accessory 5', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/a6/02/b4/a602b420a45e7276457afb5a68e44df5.jpg', 29.99, 15, NOW()),
('Men Accessory 6', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/f9/ae/6b/f9ae6ba1084791f368adcaf7af39445a.jpg', 24.99, 12, NOW()),
('Men Accessory 7', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/9e/37/5e/9e375e6e53d9ebcc685dc30606a1cbd7.jpg', 19.99, 20, NOW()),
('Men Accessory 8', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/1200x/f9/80/d9/f980d9fa83a13fed52e352889555c9cb.jpg', 21.99, 18, NOW()),
('Men Accessory 9', (SELECT id FROM categories WHERE name = 'Accessories' AND parent_id = (SELECT id FROM categories WHERE name = 'Men')), 'https://i.pinimg.com/736x/b4/08/61/b408613b2aca6768bdf50d2682304235.jpg', 23.99, 15, NOW());

-- Insert Women Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Dress 1', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_dress_1.jpg', 59.99, 12, NOW()),
('Women Dress 2', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_dress_2.jpg', 69.99, 18, NOW());

-- Insert Women Bags products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Bag 1', (SELECT id FROM categories WHERE name = 'Bags' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_bag_1.jpg', 39.99, 20, NOW()),
('Women Bag 2', (SELECT id FROM categories WHERE name = 'Bags' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_bag_2.jpg', 49.99, 15, NOW());

-- Insert Women Claw Clip products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Claw Clip 1', (SELECT id FROM categories WHERE name = 'Claw Clip' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_claw_clip_1.jpg', 9.99, 30, NOW()),
('Women Claw Clip 2', (SELECT id FROM categories WHERE name = 'Claw Clip' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_claw_clip_2.jpg', 12.99, 25, NOW());

-- Insert Men category
INSERT INTO categories (name) VALUES ('Men');

-- Insert Men products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Shirt 1', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shirt_1.jpg', 29.99, 20, NOW()),
('Men Shirt 2', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shirt_2.jpg', 34.99, 15, NOW()),
('Men Shoes 1', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shoes_1.jpg', 59.99, 10, NOW()),
('Men Shoes 2', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shoes_2.jpg', 69.99, 8, NOW());

-- Insert admin user (if not exists) and update password and is_admin flag
INSERT INTO users (username, email, password, is_admin)
SELECT 'labonysur', 'labonysur6@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'labonysur');

UPDATE users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    is_admin = TRUE
WHERE username = 'labonysur';
-- Drop and recreate the database
DROP DATABASE IF EXISTS mystore;
CREATE DATABASE mystore;
USE mystore;

-- Create tables

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    UNIQUE KEY unique_name_parent (name, parent_id),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS product_tags (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Insert categories
INSERT INTO categories (name, parent_id) VALUES ('Men', NULL);
INSERT INTO categories (name, parent_id) VALUES ('Women', NULL);

-- Insert Men subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', (SELECT id FROM categories WHERE name = 'Men'));
INSERT INTO categories (name, parent_id) VALUES ('Shoes', (SELECT id FROM categories WHERE name = 'Men'));
INSERT INTO categories (name, parent_id) VALUES ('Accessories', (SELECT id FROM categories WHERE name = 'Men'));

-- Insert Women subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', (SELECT id FROM categories WHERE name = 'Women'));
INSERT INTO categories (name, parent_id) VALUES ('Bags', (SELECT id FROM categories WHERE name = 'Women'));
INSERT INTO categories (name, parent_id) VALUES ('Claw Clip', (SELECT id FROM categories WHERE name = 'Women'));

-- Insert Men Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Dress 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/6c/76/d2/6c76d237471413e31939add0b8eeff90.jpg', 49.99, 10, NOW()),
('Men Dress 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/6e/7c/38/6e7c38000e732e90e540457936a73b04.jpg', 59.99, 15, NOW()),
('Men Dress 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/a0/c6/36/a0c636a27031d0529580ea41bbb7e4f3.jpg', 54.99, 12, NOW()),
('Men Dress 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/67/9f/94/679f94991d8e9f6c7d373977c4506fd7.jpg', 44.99, 8, NOW()),
('Men Dress 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/5c/68/d9/5c68d9d8567e48ec1360e3bbf29e6d34.jpg', 39.99, 10, NOW());

-- Insert Men Shoes products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Shoes 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/2d/8f/c2/2d8fc2170069ec8d5e4403add4c44030.jpg', 69.99, 20, NOW()),
('Men Shoes 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/8d/37/2f/8d372f11c7378dea155499b9d6fcbb51.jpg', 79.99, 10, NOW()),
('Men Shoes 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/0a/a1/f1/0aa1f15952eae49e61619b68730048e2.jpg', 74.99, 15, NOW()),
('Men Shoes 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/d9/94/87/d99487b947a35a2cabce90b5c49e27b5.jpg', 84.99, 12, NOW()),
('Men Shoes 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/d5/77/4d/d5774dc455ae811e404861d20bffb6b4.jpg', 89.99, 10, NOW());

-- Insert Men Accessories products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Accessory 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/f3/67/d0/f367d0dc87ac7b38d4a0cba82c589eec.jpg', 19.99, 25, NOW()),
('Men Accessory 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/11/35/da/1135dae13c5db93287e089bd7be4a0db.jpg', 24.99, 30, NOW()),
('Men Accessory 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/07/e8/0b/07e80b5262738fcb0dc7ad6b8c981100.jpg', 22.99, 20, NOW()),
('Men Accessory 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/c1/38/2b/c1382b66b32dc04b425b648615941037.jpg', 27.99, 18, NOW()),
('Men Accessory 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/a6/02/b4/a602b420a45e7276457afb5a68e44df5.jpg', 29.99, 15, NOW()),
('Men Accessory 6', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/f9/ae/6b/f9ae6ba1084791f368adcaf7af39445a.jpg', 24.99, 12, NOW()),
('Men Accessory 7', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/9e/37/5e/9e375e6e53d9ebcc685dc30606a1cbd7.jpg', 19.99, 20, NOW()),
('Men Accessory 8', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/f9/80/d9/f980d9fa83a13fed52e352889555c9cb.jpg', 21.99, 18, NOW()),
('Men Accessory 9', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/b4/08/61/b408613b2aca6768bdf50d2682304235.jpg', 23.99, 15, NOW());

-- Insert Women Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Dress 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_dress_1.jpg', 59.99, 12, NOW()),
('Women Dress 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_dress_2.jpg', 69.99, 18, NOW());

-- Insert Women Bags products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Bag 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Bags' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_bag_1.jpg', 39.99, 20, NOW()),
('Women Bag 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Bags' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_bag_2.jpg', 49.99, 15, NOW());

-- Insert Women Claw Clip products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Claw Clip 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Claw Clip' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_claw_clip_1.jpg', 9.99, 30, NOW()),
('Women Claw Clip 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Claw Clip' AND p.name = 'Women' LIMIT 1), 'https://example.com/images/women_claw_clip_2.jpg', 12.99, 25, NOW());

-- Insert admin user (if not exists) and update password and is_admin flag
INSERT INTO users (username, email, password, is_admin)
SELECT 'labonysur', 'labonysur6@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'labonysur');

UPDATE users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    is_admin = TRUE
WHERE username = 'labonysur';

-- Drop and recreate the database
DROP DATABASE IF EXISTS mystore;
CREATE DATABASE mystore;
USE mystore;

-- Create tables

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    parent_id INT DEFAULT NULL,
    UNIQUE KEY unique_name_parent (name, parent_id),
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    payment_method VARCHAR(50) NOT NULL DEFAULT 'Cash on Delivery',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS product_tags (
    product_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (product_id, tag_id),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Insert categories
INSERT INTO categories (name, parent_id) VALUES ('Men', NULL);
INSERT INTO categories (name, parent_id) VALUES ('Women', NULL);

-- Get parent category IDs
SET @men_id = (SELECT id FROM categories WHERE name = 'Men' LIMIT 1);
SET @women_id = (SELECT id FROM categories WHERE name = 'Women' LIMIT 1);

-- Insert Men subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', @men_id);
INSERT INTO categories (name, parent_id) VALUES ('Shoes', @men_id);
INSERT INTO categories (name, parent_id) VALUES ('Accessories', @men_id);

-- Insert Women subcategories
INSERT INTO categories (name, parent_id) VALUES ('Dress', @women_id);
INSERT INTO categories (name, parent_id) VALUES ('Bags', @women_id);
INSERT INTO categories (name, parent_id) VALUES ('Claw Clip', @women_id);

-- Insert Men Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Dress 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/6c/76/d2/6c76d237471413e31939add0b8eeff90.jpg', 49.99, 10, NOW()),
('Men Dress 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/6e/7c/38/6e7c38000e732e90e540457936a73b04.jpg', 59.99, 15, NOW()),
('Men Dress 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/a0/c6/36/a0c636a27031d0529580ea41bbb7e4f3.jpg', 54.99, 12, NOW()),
('Men Dress 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/67/9f/94/679f94991d8e9f6c7d373977c4506fd7.jpg', 44.99, 8, NOW()),
('Men Dress 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Dress' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/5c/68/d9/5c68d9d8567e48ec1360e3bbf29e6d34.jpg', 39.99, 10, NOW());

-- Insert Men Shoes products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Shoes 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/2d/8f/c2/2d8fc2170069ec8d5e4403add4c44030.jpg', 69.99, 20, NOW()),
('Men Shoes 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/8d/37/2f/8d372f11c7378dea155499b9d6fcbb51.jpg', 79.99, 10, NOW()),
('Men Shoes 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/0a/a1/f1/0aa1f15952eae49e61619b68730048e2.jpg', 74.99, 15, NOW()),
('Men Shoes 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/d9/94/87/d99487b947a35a2cabce90b5c49e27b5.jpg', 84.99, 12, NOW()),
('Men Shoes 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Shoes' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/d5/77/4d/d5774dc455ae811e404861d20bffb6b4.jpg', 89.99, 10, NOW());

-- Insert Men Accessories products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Accessory 1', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/f3/67/d0/f367d0dc87ac7b38d4a0cba82c589eec.jpg', 19.99, 25, NOW()),
('Men Accessory 2', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/11/35/da/1135dae13c5db93287e089bd7be4a0db.jpg', 24.99, 30, NOW()),
('Men Accessory 3', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/07/e8/0b/07e80b5262738fcb0dc7ad6b8c981100.jpg', 22.99, 20, NOW()),
('Men Accessory 4', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/c1/38/2b/c1382b66b32dc04b425b648615941037.jpg', 27.99, 18, NOW()),
('Men Accessory 5', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/a6/02/b4/a602b420a45e7276457afb5a68e44df5.jpg', 29.99, 15, NOW()),
('Men Accessory 6', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/f9/ae/6b/f9ae6ba1084791f368adcaf7af39445a.jpg', 24.99, 12, NOW()),
('Men Accessory 7', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/9e/37/5e/9e375e6e53d9ebcc685dc30606a1cbd7.jpg', 19.99, 20, NOW()),
('Men Accessory 8', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/1200x/f9/80/d9/f980d9fa83a13fed52e352889555c9cb.jpg', 21.99, 18, NOW()),
('Men Accessory 9', (SELECT c.id FROM categories c JOIN categories p ON c.parent_id = p.id WHERE c.name = 'Accessories' AND p.name = 'Men' LIMIT 1), 'https://i.pinimg.com/736x/b4/08/61/b408613b2aca6768bdf50d2682304235.jpg', 23.99, 15, NOW());

-- Insert Women Dress products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Dress 1', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_dress_1.jpg', 59.99, 12, NOW()),
('Women Dress 2', (SELECT id FROM categories WHERE name = 'Dress' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_dress_2.jpg', 69.99, 18, NOW());

-- Insert Women Bags products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Bag 1', (SELECT id FROM categories WHERE name = 'Bags' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_bag_1.jpg', 39.99, 20, NOW()),
('Women Bag 2', (SELECT id FROM categories WHERE name = 'Bags' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_bag_2.jpg', 49.99, 15, NOW());

-- Insert Women Claw Clip products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Women Claw Clip 1', (SELECT id FROM categories WHERE name = 'Claw Clip' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_claw_clip_1.jpg', 9.99, 30, NOW()),
('Women Claw Clip 2', (SELECT id FROM categories WHERE name = 'Claw Clip' AND parent_id = (SELECT id FROM categories WHERE name = 'Women')), 'https://example.com/images/women_claw_clip_2.jpg', 12.99, 25, NOW());

-- Insert Men category
INSERT INTO categories (name) VALUES ('Men');

-- Insert Men products
INSERT INTO products (name, category_id, image, price, stock, created_at) VALUES
('Men Shirt 1', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shirt_1.jpg', 29.99, 20, NOW()),
('Men Shirt 2', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shirt_2.jpg', 34.99, 15, NOW()),
('Men Shoes 1', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shoes_1.jpg', 59.99, 10, NOW()),
('Men Shoes 2', (SELECT id FROM categories WHERE name = 'Men'), 'https://example.com/images/men_shoes_2.jpg', 69.99, 8, NOW());

-- Insert admin user (if not exists) and update password and is_admin flag
INSERT INTO users (username, email, password, is_admin)
SELECT 'labonysur', 'labonysur6@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', TRUE
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'labonysur');

UPDATE users
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: "password"
    is_admin = TRUE
WHERE username = 'labonysur';
