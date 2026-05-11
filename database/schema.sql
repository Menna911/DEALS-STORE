CREATE DATABASE deals_store;

USE deals_store;

CREATE TABLE users 
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    age INT,
    created_at DATETIME DEFAULT GETDATE()
);

CREATE Table offers 
(
    id INT IDENTITY(1,1) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description VARCHAR(MAX),
    category VARCHAR(100),
    old_price DECIMAL(10, 2),
    discount_percentage DECIMAL(5, 2),
    image_url VARCHAR(255),
    final_price AS (old_price - (old_price * discount_percentage / 100)),
    expiry_date DATE,
    added_at DATETIME DEFAULT GETDATE()
);

CREATE Table favorites 
(
    user_id INT,
    offer_id INT,
    PRIMARY KEY (user_id, offer_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE
);

-- MYSQL VERSION
-- CREATE DATABASE deals_store;

-- USE deals_store;

-- -- =====================================
-- -- Users Table
-- -- =====================================

-- CREATE TABLE users (
--     id INT AUTO_INCREMENT PRIMARY KEY,

--     name VARCHAR(100) NOT NULL,

--     email VARCHAR(100) NOT NULL UNIQUE,

--     password_hash VARCHAR(255) NOT NULL,

--     age INT,

--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- =====================================
-- -- Offers Table
-- -- =====================================

-- CREATE TABLE offers (
--     id INT AUTO_INCREMENT PRIMARY KEY,

--     title VARCHAR(255) NOT NULL,

--     description VARCHAR(1000),

--     category VARCHAR(100),

--     old_price DECIMAL(10,2),

--     discount_percentage DECIMAL(5,2),

--     final_price DECIMAL(10,2)
--     GENERATED ALWAYS AS (
--         old_price - (old_price * discount_percentage / 100)
--     ) STORED,

--     image_url VARCHAR(255),

--     expiry_date DATE,

--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

-- -- =====================================
-- -- Favorites Table
-- -- =====================================

-- CREATE TABLE favorites (
--     user_id INT,

--     offer_id INT,

--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

--     PRIMARY KEY (user_id, offer_id),

--     FOREIGN KEY (user_id)
--         REFERENCES users(id)
--         ON DELETE CASCADE,

--     FOREIGN KEY (offer_id)
--         REFERENCES offers(id)
--         ON DELETE CASCADE
-- );