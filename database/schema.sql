-- CookAI Database Schema
-- MySQL 8.0+

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET COLLATION_CONNECTION = utf8mb4_unicode_ci;

-- Drop existing tables
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `comments`;
DROP TABLE IF EXISTS `recipes`;
DROP TABLE IF EXISTS `users`;

-- Users Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar_path` VARCHAR(255),
    `bio` TEXT,
    `role` ENUM('user', 'admin', 'moderator') DEFAULT 'user',
    `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_username` (`username`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Recipes Table
CREATE TABLE `recipes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` LONGTEXT,
    `ingredients` JSON NOT NULL COMMENT 'Array of ingredients',
    `instructions` JSON NOT NULL COMMENT 'Array of instruction steps',
    `time_minutes` INT DEFAULT 30,
    `difficulty` ENUM('easy', 'medium', 'hard') DEFAULT 'medium',
    `servings` INT DEFAULT 1,
    `calories` INT,
    `image_path` VARCHAR(255),
    `is_seasonal` TINYINT(1) DEFAULT 0,
    `status` ENUM('draft', 'active', 'archived') DEFAULT 'draft',
    `views` INT DEFAULT 0,
    `rating_avg` DECIMAL(3, 2) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_difficulty` (`difficulty`),
    INDEX `idx_created_at` (`created_at`),
    FULLTEXT INDEX `ft_search` (`title`, `description`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments Table
CREATE TABLE `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `recipe_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `text` TEXT NOT NULL,
    `rating` INT CHECK(rating >= 1 AND rating <= 5),
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_recipe_id` (`recipe_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Favorites Table
CREATE TABLE `favorites` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `recipe_id` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`recipe_id`) REFERENCES `recipes`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_favorite` (`user_id`, `recipe_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_recipe_id` (`recipe_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create sample data
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `status`) VALUES
('admin', 'admin@cookai.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/laSm', 'admin', 'active'),
('user1', 'user1@cookai.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/laSm', 'user', 'active'),
('user2', 'user2@cookai.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36P4/laSm', 'user', 'active');

INSERT INTO `recipes` (`user_id`, `title`, `description`, `ingredients`, `instructions`, `time_minutes`, `difficulty`, `servings`, `calories`, `status`, `is_seasonal`) VALUES
(
    1,
    'Паста Карбонара',
    'Классическая итальянская паста с беконом, яйцами и сливочным сыром',
    '["Спагетти 400г", "Бекон 200г", "Яйца 3 шт", "Пармезан 100г", "Соль", "Черный перец"]',
    '["Отварить пасту в подсоленной воде", "Обжарить нарезанный бекон", "Смешать яйца с тертым сыром", "Соединить все компоненты и подать"]',
    20,
    'easy',
    4,
    650,
    'active',
    0
),
(
    1,
    'Гриллованный лосось',
    'Нежный лосось на гриле с лимоном и травами',
    '["Филе лосося 600г", "Лимон 1 ��т", "Оливковое масло", "Розмарин", "Тимьян", "Чеснок"]',
    '["Подготовить лосось", "Замариновать в масле и травах", "Гриль на среднем огне 12-15 минут", "Подать с лимоном"]',
    25,
    'medium',
    2,
    450,
    'active',
    1
),
(
    2,
    'Овощной салат',
    'Свежий салат из сезонных овощей с бальзамическим соусом',
    '["Помидоры 300г", "Салат 150г", "Огурец 1 шт", "Болгарский перец", "Оливковое масло", "Бальзамический уксус"]',
    '["Нарезать овощи", "Смешать в салатнице", "Заправить маслом и уксусом", "Подать охлажденным"]',
    10,
    'easy',
    2,
    120,
    'active',
    1
);

-- Sample comments
INSERT INTO `comments` (`recipe_id`, `user_id`, `text`, `rating`, `status`) VALUES
(1, 2, 'Отличный рецепт! Сделал в выходной, очень вкусно!', 5, 'approved'),
(2, 3, 'Лосось получился нежным и сочным', 5, 'approved'),
(3, 2, 'Простой и полезный рецепт', 4, 'approved');

-- Sample favorites
INSERT INTO `favorites` (`user_id`, `recipe_id`) VALUES
(2, 1),
(2, 2),
(3, 1),
(3, 3);

-- Create views for statistics
CREATE VIEW `recipe_stats` AS
SELECT 
    r.id,
    r.title,
    r.user_id,
    COUNT(DISTINCT f.id) as favorites_count,
    COUNT(DISTINCT c.id) as comments_count,
    AVG(c.rating) as avg_rating,
    r.views
FROM recipes r
LEFT JOIN favorites f ON r.id = f.recipe_id
LEFT JOIN comments c ON r.id = c.recipe_id AND c.status = 'approved'
WHERE r.status = 'active'
GROUP BY r.id, r.title, r.user_id, r.views;

CREATE VIEW `user_stats` AS
SELECT 
    u.id,
    u.username,
    COUNT(DISTINCT r.id) as recipes_count,
    COUNT(DISTINCT c.id) as comments_count,
    COUNT(DISTINCT f.id) as favorites_count
FROM users u
LEFT JOIN recipes r ON u.id = r.user_id AND r.status = 'active'
LEFT JOIN comments c ON u.id = c.user_id AND c.status = 'approved'
LEFT JOIN favorites f ON u.id = f.user_id
WHERE u.status = 'active'
GROUP BY u.id, u.username;
