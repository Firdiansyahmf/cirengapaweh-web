-- --------------------------------------------------------
-- Database Updates for Midtrans & Biteship Integration
-- --------------------------------------------------------

-- 1. Remove user_id from orders
ALTER TABLE `orders` 
DROP FOREIGN KEY `orders_user_id_foreign`;

ALTER TABLE `orders` 
DROP COLUMN `user_id`;

-- 2. Add Biteship required columns to orders
ALTER TABLE `orders`
ADD COLUMN `postal_code` varchar(10) NULL AFTER `shipping_address`,
ADD COLUMN `subtotal_amount` int(10) UNSIGNED NOT NULL AFTER `shipping_address`,
ADD COLUMN `shipping_cost` int(10) UNSIGNED NOT NULL AFTER `subtotal_amount`;

-- 3. Add Midtrans required columns to payments
ALTER TABLE `payments` 
ADD COLUMN `snap_token` varchar(255) NULL AFTER `amount`,
ADD COLUMN `payment_url` varchar(255) NULL AFTER `snap_token`;

-- 4. Update deliveries for Biteship data and flexible statuses
ALTER TABLE `deliveries`
ADD COLUMN `biteship_order_id` varchar(100) NULL AFTER `order_id`,
ADD COLUMN `courier_service` varchar(100) NULL AFTER `courier_name`,
MODIFY COLUMN `status` varchar(50) NOT NULL DEFAULT 'preparing'; 

-- 5. Create delivery_histories table for granular tracking timeline
CREATE TABLE `delivery_histories` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `delivery_histories_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
