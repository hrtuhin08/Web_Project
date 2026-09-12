-- ===================================================
-- HOSTEL SEAT & MESS MANAGEMENT SYSTEM
-- Complete Database Schema (DDL)
-- ===================================================

CREATE DATABASE IF NOT EXISTS `hostel_management` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hostel_management`;

-- 1. Users table
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL UNIQUE,
    `phone` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'manager', 'mess_staff', 'boarder') NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected', 'active', 'inactive') DEFAULT 'active',
    `profile_image` VARCHAR(255) DEFAULT 'default-avatar.png',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Hostels table
CREATE TABLE IF NOT EXISTS `hostels` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `address` TEXT NOT NULL,
    `description` TEXT NULL,
    `total_capacity` INT DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Blocks table
CREATE TABLE IF NOT EXISTS `blocks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hostel_id` INT NOT NULL,
    `name` VARCHAR(50) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Floors table
CREATE TABLE IF NOT EXISTS `floors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `block_id` INT NOT NULL,
    `floor_number` VARCHAR(20) NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`block_id`) REFERENCES `blocks`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Rooms table
CREATE TABLE IF NOT EXISTS `rooms` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `floor_id` INT NOT NULL,
    `room_number` VARCHAR(30) NOT NULL,
    `room_type` ENUM('single', 'double', 'triple', 'quad') DEFAULT 'double',
    `rent` DECIMAL(10,2) NOT NULL DEFAULT 3500.00,
    `status` ENUM('active', 'maintenance') DEFAULT 'active',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`floor_id`) REFERENCES `floors`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Beds table
CREATE TABLE IF NOT EXISTS `beds` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `room_id` INT NOT NULL,
    `bed_number` VARCHAR(20) NOT NULL,
    `status` ENUM('vacant', 'occupied', 'reserved', 'maintenance') DEFAULT 'vacant',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`room_id`) REFERENCES `rooms`(`id`) ON DELETE CASCADE,
    INDEX `idx_beds_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Boarders table
CREATE TABLE IF NOT EXISTS `boarders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL UNIQUE,
    `student_id` VARCHAR(50) NOT NULL UNIQUE,
    `institution` VARCHAR(150) NOT NULL,
    `department` VARCHAR(100) NOT NULL,
    `guardian_name` VARCHAR(100) NOT NULL,
    `guardian_phone` VARCHAR(20) NOT NULL,
    `address` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Manager assignments
CREATE TABLE IF NOT EXISTS `manager_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `manager_id` INT NOT NULL,
    `hostel_id` INT NOT NULL,
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_manager_hostel` (`manager_id`, `hostel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Mess staff assignments
CREATE TABLE IF NOT EXISTS `mess_staff_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `mess_staff_id` INT NOT NULL,
    `hostel_id` INT NOT NULL,
    `assigned_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`mess_staff_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_mess_hostel` (`mess_staff_id`, `hostel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Seat allocations
CREATE TABLE IF NOT EXISTS `seat_allocations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `boarder_id` INT NOT NULL,
    `bed_id` INT NOT NULL,
    `allocated_by` INT NULL,
    `allocation_date` DATE NOT NULL,
    `end_date` DATE NULL,
    `status` ENUM('active', 'transferred', 'vacated') DEFAULT 'active',
    FOREIGN KEY (`boarder_id`) REFERENCES `boarders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`bed_id`) REFERENCES `beds`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`allocated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_allocations_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Meal preferences
CREATE TABLE IF NOT EXISTS `meal_preferences` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `boarder_id` INT NOT NULL,
    `meal_date` DATE NOT NULL,
    `breakfast` TINYINT(1) DEFAULT 1,
    `lunch` TINYINT(1) DEFAULT 1,
    `dinner` TINYINT(1) DEFAULT 1,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`boarder_id`) REFERENCES `boarders`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_boarder_meal_date` (`boarder_id`, `meal_date`),
    INDEX `idx_meal_date` (`meal_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Weekly menus
CREATE TABLE IF NOT EXISTS `weekly_menus` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hostel_id` INT NOT NULL,
    `week_start_date` DATE NOT NULL,
    `status` ENUM('draft', 'published') DEFAULT 'draft',
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Menu items
CREATE TABLE IF NOT EXISTS `menu_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `weekly_menu_id` INT NOT NULL,
    `day` ENUM('Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday') NOT NULL,
    `meal_type` ENUM('Breakfast', 'Lunch', 'Dinner') NOT NULL,
    `menu_description` TEXT NOT NULL,
    FOREIGN KEY (`weekly_menu_id`) REFERENCES `weekly_menus`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_menu_day_meal` (`weekly_menu_id`, `day`, `meal_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Bills
CREATE TABLE IF NOT EXISTS `bills` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `boarder_id` INT NOT NULL,
    `billing_month` VARCHAR(7) NOT NULL,
    `room_rent` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `meal_cost` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `other_charges` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `discount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `paid_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `due_amount` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `status` ENUM('paid', 'partially_paid', 'unpaid', 'overdue') DEFAULT 'unpaid',
    `generated_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`boarder_id`) REFERENCES `boarders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`generated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `unique_boarder_month_bill` (`boarder_id`, `billing_month`),
    INDEX `idx_bills_month` (`billing_month`),
    INDEX `idx_bills_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 15. Payments
CREATE TABLE IF NOT EXISTS `payments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `bill_id` INT NOT NULL,
    `boarder_id` INT NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `payment_method` ENUM('bKash', 'Nagad', 'Rocket', 'Card', 'Bank', 'Cash') DEFAULT 'bKash',
    `transaction_id` VARCHAR(100) NOT NULL,
    `payment_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `received_by` INT NULL,
    FOREIGN KEY (`bill_id`) REFERENCES `bills`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`boarder_id`) REFERENCES `boarders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`received_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_payments_date` (`payment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 16. Complaints
CREATE TABLE IF NOT EXISTS `complaints` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `boarder_id` INT NOT NULL,
    `hostel_id` INT NOT NULL,
    `category` ENUM('Electrical', 'Plumbing', 'Internet', 'Furniture', 'Cleaning', 'Security', 'Other') NOT NULL,
    `description` TEXT NOT NULL,
    `response_note` TEXT NULL,
    `status` ENUM('Pending', 'In Progress', 'Resolved') DEFAULT 'Pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`boarder_id`) REFERENCES `boarders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE,
    INDEX `idx_complaints_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 17. Notices
CREATE TABLE IF NOT EXISTS `notices` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `priority` ENUM('Normal', 'Important', 'Urgent') DEFAULT 'Normal',
    `target_role` ENUM('All', 'Manager', 'Mess Staff', 'Boarder') DEFAULT 'All',
    `created_by` INT NULL,
    `publish_date` DATE NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_notices_priority` (`priority`),
    INDEX `idx_notices_target` (`target_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 18. Conversations
CREATE TABLE IF NOT EXISTS `conversations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_one_id` INT NOT NULL,
    `user_two_id` INT NOT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_one_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_two_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_pair` (`user_one_id`, `user_two_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 19. Messages
CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `conversation_id` INT NOT NULL,
    `sender_id` INT NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`conversation_id`) REFERENCES `conversations`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_messages_read` (`is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 20. Bazar expenses
CREATE TABLE IF NOT EXISTS `bazar_expenses` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hostel_id` INT NOT NULL,
    `expense_date` DATE NOT NULL,
    `item_name` VARCHAR(150) NOT NULL,
    `category` ENUM('Rice', 'Vegetables', 'Fish', 'Meat', 'Oil', 'Spices', 'Gas', 'Other') NOT NULL,
    `quantity` VARCHAR(50) NOT NULL,
    `amount` DECIMAL(10,2) NOT NULL,
    `notes` TEXT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`hostel_id`) REFERENCES `hostels`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_expenses_date` (`expense_date`),
    INDEX `idx_expenses_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 21. Contact messages
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phone` VARCHAR(20) NOT NULL,
    `message` TEXT NOT NULL,
    `status` ENUM('unread', 'read') DEFAULT 'unread',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 22. Activities log
CREATE TABLE IF NOT EXISTS `activities` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NULL,
    `action` VARCHAR(100) NOT NULL,
    `details` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_activities_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
