CREATE DATABASE IF NOT EXISTS eshka_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE eshka_db;


CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    image VARCHAR(255),
    sort_order INT DEFAULT 0,
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
    sort_order INT DEFAULT 0,
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

INSERT INTO categories (name, slug, description, sort_order) VALUES
('Напитки', 'drinks', 'Прохладительные напитки', 1),
('Молочные напитки', 'milk-drinks', 'Молочные коктейли и латте', 2),
('Пиццы', 'pizza', 'Различные виды пиццы', 3),
('Сеты пицц', 'pizza-sets', 'Комбо-наборы из пиццы', 4),
('Роллы', 'rolls', 'Различные виды роллов', 5),
('Сеты роллов', 'roll-sets', 'Комбо-наборы из роллов', 6),
('Шаурма', 'shawarma', 'Различные виды шаурмы', 7),
('Хот-доги', 'hot-dogs', 'Различные виды хот-догов', 8),
('Бургеры', 'burgers', 'Различные виды бургеров', 9),
('Шашлык', 'shashlik', 'Различные виды шашлыка', 10),
('Крылышки', 'wings', 'Куриные крылышки', 11),
('Фри', 'fries', 'Картофель фри и другие гарниры', 12),
('Wok', 'wok', 'Блюда в стиле вок', 13);
