DROP DATABASE IF EXISTS quicksell;
CREATE DATABASE quicksell CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quicksell;

CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255)
);

INSERT INTO roles (role_name, description) VALUES
('super_admin', 'Full access to everything'),
('admin', 'Manages users, listings and categories'),
('moderator', 'Reviews listings and reported items'),
('seller', 'Can list items for sale'),
('buyer', 'Can browse and purchase items');

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    role_id INT NOT NULL DEFAULT 5,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    parent_id INT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(category_id)
);

INSERT INTO categories (name) VALUES
('Electronics'), ('Fashion'), ('Home & Garden'),
('Books'), ('Sports'), ('Vehicles'), ('Other');

CREATE TABLE listings (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    condition_type ENUM('new','used','refurbished') DEFAULT 'used',
    location VARCHAR(100),
    image VARCHAR(255),
    status ENUM('active','sold','removed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

CREATE TABLE orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','paid','shipped','completed','cancelled') DEFAULT 'pending',
    ordered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id),
    FOREIGN KEY (buyer_id) REFERENCES users(user_id),
    FOREIGN KEY (seller_id) REFERENCES users(user_id)
);

CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    listing_id INT,
    body TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id),
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (reviewer_id) REFERENCES users(user_id)
);

INSERT INTO users (first_name, last_name, email, password_hash, role_id) VALUES
('Site','Admin','admin@quicksell.co.za','$2y$10$abcdefghijklmnopqrstuv', 1),
('Thabo','Mokoena','thabo@quicksell.co.za','$2y$10$abcdefghijklmnopqrstuv', 4),
('Anam','Nake','anam@quicksell.co.za','$2y$10$abcdefghijklmnopqrstuv', 5),
('Jody', 'Roux', 'jody@quicksell.co.za', '$2y$10$abcdefghijklmnopqrstuv', 5);

INSERT INTO listings (seller_id, category_id, title, description, price, condition_type, location)
VALUES
(2, 1, 'iPhone 12 - 128GB', 'Good condition, no scratches, comes with charger', 6500.00, 'used', 'Cape Town'),
(2, 2, 'Nike Air Max sneakers', 'Worn twice, size 9', 850.00, 'used', 'Johannesburg');
