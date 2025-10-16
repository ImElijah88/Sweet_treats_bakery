CREATE DATABASE IF NOT EXISTS `sweet_treats_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sweet_treats_db`;

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `feedback` text NOT NULL,
  `admin_reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `replied_at` timestamp NULL DEFAULT NULL,
  `menu_item_id` int(11) DEFAULT NULL,
  `rating` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_status` varchar(20) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `menu_items` (`id`, `item_name`, `description`, `price`, `quantity`, `stock_status`, `image_url`) VALUES
(1, 'Traditional Cornish Pasty', 'A classic Cornish pasty filled with beef, potato, and vegetables.', '4.50', 10, 'In Stock', 'images/Traditional Cornish pasty.webp'),
(2, 'Victoria Sponge Cake Slice', 'A slice of classic Victoria sponge cake with jam and cream.', '3.50', 5, 'Low Stock', 'images/Victoria sponge cake slice.webp'),
(3, 'Bakewell Tart with Almonds', 'A delicious Bakewell tart with a cherry on top and flaked almonds.', '3.00', 0, 'Out of Stock', 'images/Bakewell tart with almonds.webp'),
(4, 'Jam Roly-Poly', 'A traditional suet pudding with a jam filling.', '4.00', 15, 'In Stock', 'images/Jam Roly-Poly.webp'),
(5, 'Scone', 'A freshly baked scone, perfect with jam and clotted cream.', '2.50', 20, 'In Stock', 'images/Scone.webp'),
(6, 'Chelsea Bun', 'A sweet bun filled with currants.', '2.00', 8, 'In Stock', 'images/Chelsea Bun.webp'),
(7, 'Banoffee Pie', 'A delicious pie with a caramel and banana filling.', '4.50', 12, 'In Stock', 'images/Banoffee Pie.webp'),
(8, 'Eccles Cake with Currants', 'A flaky pastry cake filled with currants.', '2.50', 0, 'Out of Stock', 'images/Eccles cake with currants.webp');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_blocked` tinyint(1) NOT NULL DEFAULT 0,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `dietary_preferences` text DEFAULT NULL,
  `allergens` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `full_name`, `is_admin`, `is_blocked`, `profile_picture`, `phone`, `address`, `dietary_preferences`, `allergens`, `created_at`) VALUES
(1, 'admin', 'admin123', 'admin@sweettreats.co.uk', 'Admin User', 1, 0, NULL, NULL, NULL, NULL, NULL, '2025-10-15 08:00:00');

CREATE TABLE `user_replies` (
  `id` int(11) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `user_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_id` (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `user_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE SET NULL;

ALTER TABLE `user_replies`
  ADD CONSTRAINT `user_replies_ibfk_1` FOREIGN KEY (`feedback_id`) REFERENCES `feedback` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
