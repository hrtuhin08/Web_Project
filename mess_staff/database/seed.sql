-- ===================================================
-- HOSTEL SEAT & MESS MANAGEMENT SYSTEM
-- Comprehensive Seed Data
-- ===================================================

USE `hostel_management`;

-- Password hash for 'password123': $2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq

-- 1. USERS
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `status`, `profile_image`, `created_at`) VALUES
(1, 'Super Admin', 'admin@hostel.com', '+8801711000001', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'admin', 'active', 'default-avatar.png', NOW()),
(2, 'Tanvir Ahmed (Padma)', 'manager1@hostel.com', '+8801711000002', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'manager', 'active', 'default-avatar.png', NOW()),
(3, 'Arif Hossain (Meghna)', 'manager2@hostel.com', '+8801711000003', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'manager', 'active', 'default-avatar.png', NOW()),
(4, 'Kabir Mess Supervisor', 'mess@hostel.com', '+8801711000004', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'mess_staff', 'active', 'default-avatar.png', NOW()),
(5, 'Salam Chef (Meghna)', 'mess2@hostel.com', '+8801711000005', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'mess_staff', 'active', 'default-avatar.png', NOW()),
(6, 'Mehedi Hasan Tuhin', 'boarder@hostel.com', '+8801711000010', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(7, 'Rahim Uddin', 'rahim@hostel.com', '+8801711000011', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(8, 'Karim Sheikh', 'karim@hostel.com', '+8801711000012', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(9, 'Sadikur Rahman', 'sadik@hostel.com', '+8801711000013', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(10, 'Fahim Faisal', 'fahim@hostel.com', '+8801711000014', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(11, 'Nafis Iqbal', 'nafis@hostel.com', '+8801711000015', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(12, 'Zubair Al Mahmud', 'zubair@hostel.com', '+8801711000016', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(13, 'Shahriar Shanto', 'shanto@hostel.com', '+8801711000017', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(14, 'Arafat Rahman', 'arafat@hostel.com', '+8801711000018', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(15, 'Sabbir Hossain', 'sabbir@hostel.com', '+8801711000019', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(16, 'Mahir Asif', 'mahir@hostel.com', '+8801711000020', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(17, 'Tariqul Islam', 'tariqul@hostel.com', '+8801711000021', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'active', 'default-avatar.png', NOW()),
(18, 'Ashraful Alam', 'ashraful@hostel.com', '+8801711000022', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'pending', 'default-avatar.png', NOW()),
(19, 'Imran Nazir', 'imran@hostel.com', '+8801711000023', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'pending', 'default-avatar.png', NOW()),
(20, 'Kazi Tanvir', 'kazi@hostel.com', '+8801711000024', '$2y$10$fABuBOxp8TIZhElQO.QDXOD3HNlHSmudoVf0tjw.1u8f1i8B3ipxq', 'boarder', 'pending', 'default-avatar.png', NOW());

-- 2. HOSTELS
INSERT INTO `hostels` (`id`, `name`, `address`, `description`, `total_capacity`) VALUES
(1, 'Padma International Residence Hall', 'Campus Avenue, Sector 5, Dhaka-1212', 'Premium student residential hall equipped with modern dining, study lounges, 24/7 high speed optical fiber WiFi, backup generator, and CCTV security.', 32),
(2, 'Meghna Scholars Hall', 'North Campus Ring Road, Dhaka-1212', 'Comfortable academic residence hall with dedicated library space, fitness zone, and hygienic mess facilities.', 24);

-- 3. BLOCKS
INSERT INTO `blocks` (`id`, `hostel_id`, `name`) VALUES
(1, 1, 'Block A (East Wing)'),
(2, 1, 'Block B (West Wing)'),
(3, 2, 'Main Block');

-- 4. FLOORS
INSERT INTO `floors` (`id`, `block_id`, `floor_number`) VALUES
(1, 1, '1st Floor'),
(2, 1, '2nd Floor'),
(3, 2, '1st Floor'),
(4, 3, '1st Floor'),
(5, 3, '2nd Floor');

-- 5. ROOMS
INSERT INTO `rooms` (`id`, `floor_id`, `room_number`, `room_type`, `rent`, `status`) VALUES
-- Padma Block A Floor 1
(1, 1, 'Room 101', 'quad', 3500.00, 'active'),
(2, 1, 'Room 102', 'quad', 3500.00, 'active'),
-- Padma Block A Floor 2
(3, 2, 'Room 201', 'double', 4500.00, 'active'),
(4, 2, 'Room 202', 'double', 4500.00, 'active'),
-- Padma Block B Floor 1
(5, 3, 'Room 103', 'triple', 3800.00, 'active'),
(6, 3, 'Room 104', 'triple', 3800.00, 'active'),
-- Meghna Main Block Floor 1
(7, 4, 'Room M-101', 'double', 4200.00, 'active'),
(8, 4, 'Room M-102', 'double', 4200.00, 'active'),
-- Meghna Main Block Floor 2
(9, 5, 'Room M-201', 'single', 6000.00, 'active'),
(10, 5, 'Room M-202', 'triple', 3800.00, 'active');

-- 6. BEDS
INSERT INTO `beds` (`id`, `room_id`, `bed_number`, `status`) VALUES
-- Room 1 (Room 101, Padma - Quad)
(1, 1, 'B1', 'occupied'),
(2, 1, 'B2', 'occupied'),
(3, 1, 'B3', 'vacant'),
(4, 1, 'B4', 'maintenance'),
-- Room 2 (Room 102, Padma - Quad)
(5, 2, 'B1', 'occupied'),
(6, 2, 'B2', 'occupied'),
(7, 2, 'B3', 'vacant'),
(8, 2, 'B4', 'vacant'),
-- Room 3 (Room 201, Padma - Double)
(9, 3, 'B1', 'occupied'),
(10, 3, 'B2', 'vacant'),
-- Room 4 (Room 202, Padma - Double)
(11, 4, 'B1', 'occupied'),
(12, 4, 'B2', 'vacant'),
-- Room 5 (Room 103, Padma - Triple)
(13, 5, 'B1', 'occupied'),
(14, 5, 'B2', 'occupied'),
(15, 5, 'B3', 'vacant'),
-- Room 6 (Room 104, Padma - Triple)
(16, 6, 'B1', 'vacant'),
(17, 6, 'B2', 'vacant'),
(18, 6, 'B3', 'maintenance'),
-- Room 7 (Room M-101, Meghna - Double)
(19, 7, 'B1', 'occupied'),
(20, 7, 'B2', 'occupied'),
-- Room 8 (Room M-102, Meghna - Double)
(21, 8, 'B1', 'occupied'),
(22, 8, 'B2', 'vacant'),
-- Room 9 (Room M-201, Meghna - Single)
(23, 9, 'B1', 'occupied'),
-- Room 10 (Room M-202, Meghna - Triple)
(24, 10, 'B1', 'vacant'),
(25, 10, 'B2', 'vacant'),
(26, 10, 'B3', 'vacant');

-- 7. BOARDERS
INSERT INTO `boarders` (`id`, `user_id`, `student_id`, `institution`, `department`, `guardian_name`, `guardian_phone`, `address`) VALUES
(1, 6, '0112330826', 'United International University', 'Computer Science & Engineering', 'Md. Nurul Islam', '+8801811223344', 'Bagerhat, Khulna'),
(2, 7, '0112330827', 'United International University', 'Electrical & Electronic Engineering', 'Abdul Jalil', '+8801811223345', 'Cumilla, Chattogram'),
(3, 8, '0112330828', 'United International University', 'Computer Science & Engineering', 'Shamsul Alam', '+8801811223346', 'Sylhet Sadar'),
(4, 9, '0112330829', 'United International University', 'Software Engineering', 'Rashidul Haque', '+8801811223347', 'Rajshahi'),
(5, 10, '0112330830', 'United International University', 'BBA - Finance', 'Kamal Uddin', '+8801811223348', 'Chattogram'),
(6, 11, '0112330831', 'United International University', 'Computer Science & Engineering', 'Iqbal Ahmed', '+8801811223349', 'Mymensingh'),
(7, 12, '0112330832', 'United International University', 'Civil Engineering', 'Mahmudul Hasan', '+8801811223350', 'Bogura'),
(8, 13, '0112330833', 'United International University', 'Computer Science & Engineering', 'Mustafizur Rahman', '+8801811223351', 'Jessore'),
(9, 14, '0112330834', 'United International University', 'Data Science', 'Ziaur Rahman', '+8801811223352', 'Dhaka'),
(10, 15, '0112330835', 'United International University', 'Economics', 'Altaf Hossain', '+8801811223353', 'Barishal'),
(11, 16, '0112330836', 'United International University', 'Computer Science & Engineering', 'Asifur Rahman', '+8801811223354', 'Khulna'),
(12, 17, '0112330837', 'United International University', 'Electrical & Electronic Engineering', 'Sirajul Islam', '+8801811223355', 'Faridpur'),
(13, 18, '0112330838', 'United International University', 'Software Engineering', 'Mokhlesur Rahman', '+8801811223356', 'Tangail'),
(14, 19, '0112330839', 'United International University', 'BBA - Marketing', 'Nazir Hossain', '+8801811223357', 'Rangpur'),
(15, 20, '0112330840', 'United International University', 'Computer Science & Engineering', 'Tanvir Kazi', '+8801811223358', 'Kushtia');

-- 8. MANAGER ASSIGNMENTS
INSERT INTO `manager_assignments` (`manager_id`, `hostel_id`) VALUES
(2, 1), -- Tanvir manages Padma
(3, 2); -- Arif manages Meghna

-- 9. MESS STAFF ASSIGNMENTS
INSERT INTO `mess_staff_assignments` (`mess_staff_id`, `hostel_id`) VALUES
(4, 1), -- Kabir mess supervisor at Padma
(5, 2); -- Salam chef at Meghna

-- 10. SEAT ALLOCATIONS (1 bed = 1 active boarder)
INSERT INTO `seat_allocations` (`boarder_id`, `bed_id`, `allocated_by`, `allocation_date`, `status`) VALUES
(1, 1, 2, '2026-01-01', 'active'), -- Boarder 1 in Padma Room 101 Bed 1
(2, 2, 2, '2026-01-01', 'active'), -- Boarder 2 in Padma Room 101 Bed 2
(3, 5, 2, '2026-01-05', 'active'), -- Boarder 3 in Padma Room 102 Bed 1
(4, 6, 2, '2026-01-05', 'active'), -- Boarder 4 in Padma Room 102 Bed 2
(5, 9, 2, '2026-01-10', 'active'), -- Boarder 5 in Padma Room 201 Bed 1
(6, 11, 2, '2026-01-15', 'active'), -- Boarder 6 in Padma Room 202 Bed 1
(7, 13, 2, '2026-02-01', 'active'), -- Boarder 7 in Padma Room 103 Bed 1
(8, 14, 2, '2026-02-01', 'active'), -- Boarder 8 in Padma Room 103 Bed 2
(9, 19, 3, '2026-02-05', 'active'), -- Boarder 9 in Meghna Room M-101 Bed 1
(10, 20, 3, '2026-02-05', 'active'), -- Boarder 10 in Meghna Room M-101 Bed 2
(11, 21, 3, '2026-02-10', 'active'), -- Boarder 11 in Meghna Room M-102 Bed 1
(12, 23, 3, '2026-02-15', 'active'); -- Boarder 12 in Meghna Room M-201 Bed 1

-- 11. MEAL PREFERENCES (Sample for today 2026-09-07 and tomorrow 2026-09-08)
INSERT INTO `meal_preferences` (`boarder_id`, `meal_date`, `breakfast`, `lunch`, `dinner`) VALUES
(1, '2026-09-07', 1, 1, 1),
(1, '2026-09-08', 1, 0, 1),
(2, '2026-09-07', 1, 1, 0),
(2, '2026-09-08', 1, 1, 1),
(3, '2026-09-07', 0, 1, 1),
(3, '2026-09-08', 0, 1, 1),
(4, '2026-09-07', 1, 1, 1),
(4, '2026-09-08', 1, 1, 1),
(5, '2026-09-07', 1, 0, 1),
(6, '2026-09-07', 1, 1, 1),
(7, '2026-09-07', 0, 1, 1),
(8, '2026-09-07', 1, 1, 1),
(9, '2026-09-07', 1, 1, 1),
(10, '2026-09-07', 1, 0, 1),
(11, '2026-09-07', 1, 1, 1),
(12, '2026-09-07', 0, 1, 1);

-- 12. WEEKLY MENU (Hostel 1 Padma Hall)
INSERT INTO `weekly_menus` (`id`, `hostel_id`, `week_start_date`, `status`, `created_by`, `created_at`) VALUES
(1, 1, '2026-09-05', 'published', 4, NOW()),
(2, 2, '2026-09-05', 'published', 5, NOW());

-- 13. MENU ITEMS (Saturday to Friday)
INSERT INTO `menu_items` (`weekly_menu_id`, `day`, `meal_type`, `menu_description`) VALUES
(1, 'Saturday', 'Breakfast', 'Crispy Paratha (2 pcs), Fried Egg, Vegetable Bhaji, Milk Tea'),
(1, 'Saturday', 'Lunch', 'Steamed Katari Rice, Rui Fish Curry with Potato, Mixed Dal, Salad'),
(1, 'Saturday', 'Dinner', 'Rice, Deshi Chicken Curry, Eggplant Fry, Masoor Dal'),
(1, 'Sunday', 'Breakfast', 'Chicken Khichuri, Boiled Egg, Pickle, Tea'),
(1, 'Sunday', 'Lunch', 'Rice, Beef Bhuna / Egg Curry (Opt), Thick Dal, Cucumber Salad'),
(1, 'Sunday', 'Dinner', 'Rice, Pangas/Telapia Fish Fry with Onion Gravy, Mixed Veggie, Dal'),
(1, 'Monday', 'Breakfast', 'Ruti (3 pcs), Vegetable Medley, Omelet, Tea'),
(1, 'Monday', 'Lunch', 'Rice, Broiler Chicken Curry, Seasonal Shak Bhaji, Dal'),
(1, 'Monday', 'Dinner', 'Rice, Fried Fish, Tomato Chutney, Lemon, Masoor Dal'),
(1, 'Tuesday', 'Breakfast', 'Dal Puri (3 pcs), Halwa, Fried Egg, Tea'),
(1, 'Tuesday', 'Lunch', 'Fragrant Basmati Polao, Roast Chicken, Salad, Borhani'),
(1, 'Tuesday', 'Dinner', 'Rice, Egg Potato Curry, Cabbage Bhaji, Dal'),
(1, 'Wednesday', 'Breakfast', 'Paratha, Chickpeas Dal (Booter Dal), Boiled Egg, Tea'),
(1, 'Wednesday', 'Lunch', 'Rice, Shorshe Ilish / Rui Fish, Potato Bhorta, Dal'),
(1, 'Wednesday', 'Dinner', 'Rice, Chicken Jhal Fry, Vegetable Medley, Dal'),
(1, 'Thursday', 'Breakfast', 'Luchi with Alur Dom, Fried Egg, Sweet Tea'),
(1, 'Thursday', 'Lunch', 'Rice, Beef Curry with Potatoes, Green Shak, Thick Dal'),
(1, 'Thursday', 'Dinner', 'Rice, Egg Curry with Cauliflower, Dal, Pickle'),
(1, 'Friday', 'Breakfast', 'Special Bhuna Khichuri, Boiled Egg, Begun Bhaja, Tea'),
(1, 'Friday', 'Lunch', 'Special Mutton/Beef Biryani with Boiled Egg, Fresh Salad, Cold Drink'),
(1, 'Friday', 'Dinner', 'Light Rice, Chicken Soup Curry, Mixed Vegetables, Dal');

-- 14. BAZAR EXPENSES
INSERT INTO `bazar_expenses` (`hostel_id`, `expense_date`, `item_name`, `category`, `quantity`, `amount`, `notes`, `created_by`) VALUES
(1, '2026-09-01', 'Miniket Rice 50kg bag (2x)', 'Rice', '100 kg', 7200.00, 'Purchased from Karwan Bazar wholesale', 4),
(1, '2026-09-02', 'Broiler Chicken', 'Meat', '25 kg', 4800.00, 'Fresh broiler poultry', 4),
(1, '2026-09-03', 'Rui & Katla Fresh Fish', 'Fish', '18 kg', 6100.00, 'Purchased from local fish market', 4),
(1, '2026-09-04', 'Seasonal Vegetables & Potatoes', 'Vegetables', '40 kg', 2200.00, 'Potatoes, onions, tomatoes, green chilies', 4),
(1, '2026-09-05', 'Soybean Oil 5L (2x) & Spices', 'Oil', '10 Liters', 2600.00, 'Fresh oil and cooking spices', 4),
(1, '2026-09-06', 'LP Cooking Gas Cylinder 12kg (2x)', 'Gas', '2 cylinders', 3100.00, 'Kitchen supply refill', 4),
(1, '2026-09-07', 'Beef & Chicken Combo', 'Meat', '20 kg', 8500.00, 'For weekly special dinner', 4),
(2, '2026-09-02', 'Rice and Essentials', 'Rice', '50 kg', 3600.00, 'Monthly supply', 5),
(2, '2026-09-04', 'Poultry and Veggies', 'Meat', '15 kg', 3200.00, 'Kitchen inventory', 5);

-- 15. BILLS
INSERT INTO `bills` (`id`, `boarder_id`, `billing_month`, `room_rent`, `meal_cost`, `other_charges`, `discount`, `total_amount`, `paid_amount`, `due_amount`, `status`, `generated_by`, `created_at`) VALUES
-- Boarder 1 (Mehedi Hasan Tuhin) - September 2026 (Unpaid)
(1, 1, '2026-09', 3500.00, 2400.00, 200.00, 100.00, 6000.00, 0.00, 6000.00, 'unpaid', 2, '2026-09-01 10:00:00'),
-- Boarder 1 - August 2026 (Paid)
(2, 1, '2026-08', 3500.00, 2350.00, 200.00, 0.00, 6050.00, 6050.00, 0.00, 'paid', 2, '2026-08-01 10:00:00'),
-- Boarder 2 - September 2026 (Partially Paid)
(3, 2, '2026-09', 3500.00, 2200.00, 200.00, 0.00, 5900.00, 3000.00, 2900.00, 'partially_paid', 2, '2026-09-01 10:00:00'),
-- Boarder 3 - September 2026 (Paid)
(4, 3, '2026-09', 3500.00, 2500.00, 200.00, 0.00, 6200.00, 6200.00, 0.00, 'paid', 2, '2026-09-01 10:00:00'),
-- Boarder 4 - September 2026 (Overdue)
(5, 4, '2026-09', 3500.00, 2400.00, 200.00, 0.00, 6100.00, 0.00, 6100.00, 'unpaid', 2, '2026-09-01 10:00:00'),
-- Boarder 5 - September 2026
(6, 5, '2026-09', 4500.00, 2100.00, 200.00, 0.00, 6800.00, 6800.00, 0.00, 'paid', 2, '2026-09-01 10:00:00'),
-- Boarder 6 - September 2026
(7, 6, '2026-09', 4500.00, 2400.00, 200.00, 0.00, 7100.00, 0.00, 7100.00, 'unpaid', 2, '2026-09-01 10:00:00'),
-- Boarder 9 (Meghna) - September 2026
(8, 9, '2026-09', 4200.00, 2300.00, 200.00, 0.00, 6700.00, 6700.00, 0.00, 'paid', 3, '2026-09-01 10:00:00');

-- 16. PAYMENTS
INSERT INTO `payments` (`id`, `bill_id`, `boarder_id`, `amount`, `payment_method`, `transaction_id`, `payment_date`, `received_by`) VALUES
(1, 2, 1, 6050.00, 'bKash', 'TRXB7891001', '2026-08-05 14:30:00', 2),
(2, 3, 2, 3000.00, 'Nagad', 'TRXN9920112', '2026-09-03 16:15:00', 2),
(3, 4, 3, 6200.00, 'Card', 'TRXC4400192', '2026-09-02 11:45:00', 2),
(4, 6, 5, 6800.00, 'bKash', 'TRXB5511223', '2026-09-04 18:20:00', 2),
(5, 8, 9, 6700.00, 'Bank', 'TRXK8812301', '2026-09-05 09:10:00', 3);

-- 17. COMPLAINTS
INSERT INTO `complaints` (`id`, `boarder_id`, `hostel_id`, `category`, `description`, `response_note`, `status`, `created_at`) VALUES
(1, 1, 1, 'Internet', 'High ping and intermittent disconnection on the 1st floor corridor WiFi router.', 'Technician contacted. Router firmware will be flashed today.', 'In Progress', '2026-09-06 14:00:00'),
(2, 2, 1, 'Plumbing', 'Washroom 101 sink faucet has a small leak causing water dripping sound.', 'Plumber replaced rubber washer. Issue resolved.', 'Resolved', '2026-09-03 09:30:00'),
(3, 3, 1, 'Electrical', 'Ceiling fan regulator in Room 102 stuck at speed 2.', NULL, 'Pending', '2026-09-07 19:15:00'),
(4, 9, 2, 'Furniture', 'Study table drawer lock is jammed.', 'Carpenter scheduled for inspection.', 'In Progress', '2026-09-05 11:20:00');

-- 18. NOTICES
INSERT INTO `notices` (`id`, `title`, `description`, `priority`, `target_role`, `created_by`, `publish_date`) VALUES
(1, 'Revised Mess Dining Timings for Mid-Term Exams', 'Please note that starting next Sunday, Breakfast will be served from 7:00 AM to 9:30 AM, Lunch from 12:30 PM to 2:30 PM, and Dinner from 7:30 PM to 10:00 PM. Kindly update your meal preferences before 9:00 PM cutoff.', 'Important', 'All', 1, '2026-09-05'),
(2, 'Monthly Hostel Room Safety & Cleanliness Inspection', 'The hostel management committee will conduct general safety and cleanliness inspections on September 15 between 10:00 AM and 1:00 PM. Please keep personal study areas neat.', 'Normal', 'Boarder', 2, '2026-09-04'),
(3, 'High Voltage Water Geyser Emergency Maintenance', 'Maintenance work on the water heater backup boiler will take place tomorrow from 2:00 PM to 4:00 PM. Hot water supply may be temporarily restricted during this period.', 'Urgent', 'All', 1, '2026-09-07');

-- 19. CONVERSATIONS
INSERT INTO `conversations` (`id`, `user_one_id`, `user_two_id`, `updated_at`) VALUES
(1, 2, 6, NOW()), -- Manager 1 (Tanvir) <-> Boarder 1 (Mehedi Hasan Tuhin)
(2, 4, 2, NOW()); -- Mess Staff 1 (Kabir) <-> Manager 1 (Tanvir)

-- 20. MESSAGES
INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, 6, 'Hello Sir, could you please verify if the WiFi technician will visit today?', 1, '2026-09-06 14:05:00'),
(2, 1, 2, 'Yes Tuhin, the ISP engineer is scheduled to inspect Floor 1 router at 4:30 PM.', 1, '2026-09-06 14:15:00'),
(3, 1, 6, 'Thank you so much Sir for the swift update!', 0, '2026-09-06 14:20:00'),
(4, 2, 4, 'Tanvir bhai, tomorrow is special dinner day. Bazar estimate is 8500 BDT.', 1, '2026-09-06 16:00:00'),
(5, 2, 2, 'Approved Kabir, please make sure quality meat and fresh vegetables are purchased.', 1, '2026-09-06 16:10:00');

-- 21. CONTACT MESSAGES
INSERT INTO `contact_messages` (`name`, `email`, `phone`, `message`, `status`, `created_at`) VALUES
('Sultana Parvin', 'sultana@gmail.com', '+8801911223344', 'Can I inquire about seat availability for new undergraduate admission in Fall 2026?', 'unread', '2026-09-06 18:40:00'),
('Md. Al-Amin', 'alamin.cse@gmail.com', '+8801722334455', 'Is there any single room vacant in Padma Hall for international postgraduate students?', 'read', '2026-09-05 10:15:00');

-- 22. ACTIVITIES LOG
INSERT INTO `activities` (`user_id`, `action`, `details`, `created_at`) VALUES
(1, 'System Initialized', 'Hostel Seat & Mess Management System database generated with initial configurations.', '2026-09-01 08:00:00'),
(2, 'Seat Allocated', 'Allocated Room 101 Bed 1 to Boarder Mehedi Hasan Tuhin.', '2026-09-01 09:00:00'),
(2, 'Bill Generated', 'Generated monthly hostel and meal bills for September 2026.', '2026-09-01 10:00:00'),
(4, 'Menu Published', 'Published weekly dining menu for September 05 - 11, 2026.', '2026-09-05 11:30:00'),
(1, 'Notice Published', 'Broadcasted urgent notice on exam timings and dining schedule.', '2026-09-05 12:00:00');
