CREATE DATABASE IF NOT EXISTS eshka_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE eshka_db;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE dishes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (name, slug, description, image) VALUES
('Напитки', 'drinks', 'Освежающие напитки', 'drinks.jpg'),
('Фастфуд', 'fastfood', 'Шаурма, бургеры, роллы', 'fastfood.jpg');

INSERT INTO dishes (category_id, name, slug, description, price, image) VALUES
(1, 'Кола 0.5л', 'cola-05l', 'Газированный напиток', 60.00, 'cola.jpg'),
(1, 'Фанта 0.5л', 'fanta-05l', 'Апельсиновый напиток', 60.00, 'fanta.jpg'),
(1, 'Сок яблочный', 'apple-juice', '100% натуральный сок', 80.00, 'juice.jpg'),
(2, 'Шаурма классическая', 'shawarma-classic', 'Курица, огурцы, помидоры, соус', 150.00, 'shawarma.jpg'),
(2, 'Шаурма с говядиной', 'shawarma-beef', 'Говядина, овощи, острый соус', 180.00, 'shawarma-beef.jpg'),
(2, 'Чикен бургер', 'chicken-burger', 'Куриная котлета, сыр, салат', 170.00, 'burger.jpg'),
(2, 'Картошка фри', 'fries', 'Золотистая картошка фри', 90.00, 'fries.jpg');

INSERT INTO slides (title, description, image, sort_order) VALUES
('Наш ресторан', 'Фасад заведения', 'slide1.jpg', 1),
('Уютный зал', 'Интерьер ресторана', 'slide2.jpg', 2),
('Своя кухня', 'Профессиональная кухня', 'slide3.jpg', 3),
('Готовим с любовью', 'Свежие ингредиенты', 'slide4.jpg', 4);