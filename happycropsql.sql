Retail Shop Login
ID - 9923544381
Password - Shri_Ganesh


Manufacturer Login
ID - 9970628343
Password - Criyagensatara



ID - 8149383344
Password - Criyagensatara


Admin
User name: 8297363789
Password:   



Email - bhagyashree4680@gmail.com
Pass - Bhag@123



ALTER TABLE `order_items` ADD `transaction_id` VARCHAR(255) NULL DEFAULT NULL AFTER `order_id`;
ALTER TABLE `order_items` ADD `receipt_no` VARCHAR(255) NULL DEFAULT NULL AFTER `transaction_id`;
UPDATE order_items 
SET receipt_no = CONCAT('RC', LPAD(id, 5, '0'));
ALTER TABLE `order_items` ADD `description` TEXT NULL DEFAULT NULL AFTER `batch_no`;
CREATE TABLE `terms_conditions` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `terms_conditions` LONGTEXT NULL DEFAULT NULL , `created_by` INT(11) NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
INSERT INTO `terms_conditions` (`id`, `user_id`, `terms_conditions`, `created_by`, `created_at`, `updated_at`) VALUES (NULL, '157', 'Goods once sold cannot be taken back', '157', current_timestamp(), current_timestamp()), (NULL, '157', '18% PA interest will charged on delayed payment', '157', current_timestamp(), current_timestamp()), (NULL, '157', 'All disputes are under kolhapur jurisdiction only', '157', current_timestamp(), current_timestamp()))
ALTER TABLE `order_items` ADD `admin_transaction_id` VARCHAR(255) NULL DEFAULT NULL AFTER `transaction_id`;


CREATE TABLE `tbl_order_queries` (`id` INT(11) NOT NULL AUTO_INCREMENT , `order_id` VARCHAR(255) NULL DEFAULT NULL , `from_user_id` INT(11) NULL DEFAULT NULL , `to_user_id` INT(11) NULL DEFAULT NULL , `message` LONGTEXT NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;


16 NOV


CREATE TABLE `expenses` (`id` INT(11) NOT NULL AUTO_INCREMENT , `expense_category` VARCHAR(255) NULL DEFAULT NULL , `expense_number` VARCHAR(255) NULL DEFAULT NULL , `date` DATE NULL DEFAULT NULL , `payment_type` VARCHAR(255) NULL DEFAULT NULL , `total` DOUBLE NULL DEFAULT NULL , `total_amount` DOUBLE NULL DEFAULT NULL , `descritpion` TEXT NULL DEFAULT NULL , `image` TEXT NULL DEFAULT NULL , `document` TEXT NULL DEFAULT NULL , `user_id` INT(11) NULL DEFAULT NULL , `created_by` INT(11) NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `expenses` CHANGE `user_id` `user_id` INT(11) NULL DEFAULT NULL AFTER `id`
ALTER TABLE `expenses` CHANGE `total_amount` `paid_amount` DOUBLE NULL DEFAULT NULL;
CREATE TABLE `expenses_items` (`id` INT(11) NOT NULL AUTO_INCREMENT , `expense_id` INT(11) NULL DEFAULT NULL , `name` TEXT NULL DEFAULT NULL , `quantity` INT(11) NULL DEFAULT NULL , `price_unit` DOUBLE NULL DEFAULT NULL , `amount` DOUBLE NULL DEFAULT NULL , `created_by` INT(11) NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `expenses` CHANGE `descritpion` `description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

http://localhost/happy_crop/my-account/order-query/100294


Purchase Bill =>Tax Invoice
Purchase Out => Payment receipt
purchase Order => Purchase Order/placed orders
Purchase return => Return order


-- 1 Dec 2024

CREATE TABLE `tbl_quick_bill_customers` (`id` INT(11) NOT NULL AUTO_INCREMENT , `customer_name` VARCHAR(255) NULL DEFAULT NULL , `phone_number` INT(20) NULL DEFAULT NULL , `biiling_address` TEXT NULL DEFAULT NULL , `shipping_address` TEXT NULL DEFAULT NULL , `user_id` INT(11) NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `quick_bill_products` (`id` INT(11) NOT NULL , `user_id` INT(11) NULL DEFAULT NULL , `customer_id` INT(11) NULL DEFAULT NULL , `payment_mode` ENUM('1','2','3') NOT NULL DEFAULT '1' COMMENT '1=>Cash,2=>UPI,3=>Bank Transfer' , `subtotal` DOUBLE NULL DEFAULT NULL , `amount_received` DOUBLE NULL DEFAULT NULL , `discount` DOUBLE NULL DEFAULT NULL , `tax_applied` DOUBLE NULL DEFAULT NULL , `total_amt` DOUBLE NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ) ENGINE = InnoDB;
CREATE TABLE `tbl_quick_bill_products` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `bill_id` INT(11) NULL DEFAULT NULL , `item_code` VARCHAR(255) NULL DEFAULT NULL , `item_name` VARCHAR(255) NULL DEFAULT NULL , `quantity` INT(11) NULL DEFAULT NULL , `price` DOUBLE NULL DEFAULT NULL , `discount` DOUBLE NULL DEFAULT NULL , `tax_applied` DOUBLE NULL DEFAULT NULL , `total` DOUBLE NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `quick_bill_products` ADD `balance` DOUBLE NULL DEFAULT NULL AFTER `subtotal`;
ALTER TABLE `quick_bill_products` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `quick_bill_products` ADD `remark` LONGTEXT NULL DEFAULT NULL AFTER `total_amt`;