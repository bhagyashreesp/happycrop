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


-- 14 Dec 2024

CREATE TABLE `external_products` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `product_name` VARCHAR(255) NULL DEFAULT NULL , `hsn` VARCHAR(255) NULL DEFAULT NULL , `quantity` INT(11) NULL DEFAULT NULL , `price` DOUBLE NULL DEFAULT NULL , `gst` DOUBLE NULL DEFAULT NULL , `amount` DOUBLE NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `external_purchase_bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `party_name` varchar(255) DEFAULT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `order_number` varchar(255) DEFAULT NULL,
  `phone_no` int(20) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `place_supply` varchar(255) DEFAULT NULL,
  `gstn` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `external_purchase_bill` ADD `in_words` VARCHAR(255) NULL DEFAULT NULL AFTER `document`;
ALTER TABLE `external_purchase_bill` ADD `user_id` INT(11) NOT NULL AFTER `id`;
ALTER TABLE `external_products` CHANGE `user_id` `purchase_id` INT(11) NULL DEFAULT NULL;
CREATE TABLE `external_payment_out` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `party_name` VARCHAR(255) NULL DEFAULT NULL , `receipt_number` VARCHAR(255) NULL DEFAULT NULL , `order_number` VARCHAR(255) NULL DEFAULT NULL , `address` TEXT NULL DEFAULT NULL , `phone_no` INT(20) NULL DEFAULT NULL , `email_id` VARCHAR(255) NULL DEFAULT NULL , `gstn` VARCHAR(255) NULL DEFAULT NULL , `date` DATE NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `external_payment_out` ADD `ref_no` VARCHAR(255) NULL DEFAULT NULL AFTER `date`, ADD `received` DOUBLE NULL DEFAULT NULL AFTER `ref_no`, ADD `description` TEXT NULL DEFAULT NULL AFTER `received`, ADD `transaction_receipt` VARCHAR(255) NULL DEFAULT NULL AFTER `description`;
ALTER TABLE `external_purchase_bill` ADD `type` ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1=>\'Purchase Bill\',2=>\'Purchase Order\'' AFTER `user_id`;
ALTER TABLE `external_purchase_bill` ADD `due_date` DATE NULL DEFAULT NULL AFTER `in_words`;
ALTER TABLE `external_purchase_bill` ADD `description` TEXT NULL DEFAULT NULL AFTER `due_date`;
CREATE TABLE `external_purchase_return` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `seller_name` VARCHAR(255) NULL DEFAULT NULL , `return_number` VARCHAR(255) NULL DEFAULT NULL , `invoice_number` VARCHAR(255) NULL DEFAULT NULL , `address` TEXT NULL DEFAULT NULL , `phone_no` INT(20) NULL DEFAULT NULL , `email_id` VARCHAR(255) NULL DEFAULT NULL , `gstn` VARCHAR(255) NULL DEFAULT NULL , `invoice_date` DATE NULL DEFAULT NULL , `state_supply` VARCHAR(255) NULL DEFAULT NULL , `payment_type` VARCHAR(255) NULL DEFAULT NULL , `paid_amount` VARCHAR(255) NULL DEFAULT NULL , `description` TEXT NULL DEFAULT NULL , `image` VARCHAR(255) NULL DEFAULT NULL , `document` VARCHAR(255) NULL DEFAULT NULL , `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `external_purchase_return` CHANGE `paid_amount` `paid_amount` DOUBLE NULL DEFAULT NULL;
ALTER TABLE `external_products` ADD `type` ENUM('1','2') NOT NULL DEFAULT '1' COMMENT '1=>Purchase Bill and order,2=>Purchase Order' AFTER `amount`;
ALTER TABLE `external_purchase_return` ADD `date` DATE NULL DEFAULT NULL AFTER `document`;
ALTER TABLE `external_purchase_return` CHANGE `phone_no` `phone_no` INT(40) NULL DEFAULT NULL;
ALTER TABLE `external_purchase_return` CHANGE `invoice_number` `order_number` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL;

--15 Dec 2024

CREATE TABLE `external_parties` (`id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NULL DEFAULT NULL , `party_name` VARCHAR(255) NULL DEFAULT NULL , `address` TEXT NULL DEFAULT NULL , `gst` VARCHAR(255) NULL DEFAULT NULL , `fertilizer_licence_no` VARCHAR(255) NULL DEFAULT NULL , `pesticide_licence_no` VARCHAR(255) NULL DEFAULT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `external_parties` ADD `mobile` INT(40) NULL DEFAULT NULL AFTER `party_name`;
ALTER TABLE `external_parties` ADD `email` VARCHAR(255) NOT NULL AFTER `mobile`;

--25 Dec 2024
ALTER TABLE `external_parties` ADD `state` VARCHAR(255) NULL DEFAULT NULL AFTER `gst`;
ALTER TABLE `external_products` ADD `batch_no` VARCHAR(255) NULL DEFAULT NULL AFTER `type`;
ALTER TABLE `external_products` ADD `expiry_date` DATE NULL DEFAULT NULL AFTER `batch_no`;
ALTER TABLE `external_parties` ADD `seed_license_no` VARCHAR(255) NULL DEFAULT NULL AFTER `pesticide_licence_no`;