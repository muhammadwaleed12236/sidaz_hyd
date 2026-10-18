-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 16, 2026 at 06:08 PM
-- Server version: 8.0.46
-- PHP Version: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binsult1_sidaz`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `head_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `current_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Debit',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `head_id`, `title`, `account_code`, `opening_balance`, `current_balance`, `type`, `status`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Accounts Payable', 'AP', 0.00, -565349.64, 'Credit', 1, 1, '2026-08-13 07:54:12', '2026-09-30 13:16:08'),
(2, 1, 'Cash in Hand', 'ACC-0002', 0.00, -3713523.18, 'Debit', 1, 1, '2026-08-22 12:13:58', '2026-09-30 05:22:43'),
(3, 2, 'UBL (Sir Jhanzaib)', 'ACC-0003', 5000.00, 5800.00, 'Debit', 0, 1, '2026-08-22 12:14:26', '2026-09-12 09:11:32'),
(4, NULL, 'Accounts Receivable', 'AR', 0.00, 0.00, 'Debit', 1, 1, '2026-08-22 12:18:31', '2026-08-22 12:20:00'),
(5, NULL, 'Sales Revenue', 'SALES', 0.00, -67777.00, 'Credit', 1, 1, '2026-08-31 10:48:58', '2026-08-31 10:48:58'),
(6, NULL, 'Purchase Expense', 'PURCHASE', 0.00, 4145649.82, 'Debit', 1, 1, '2026-09-01 09:29:31', '2026-09-30 13:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `account_heads`
--

CREATE TABLE `account_heads` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Asset','Liability','Equity','Revenue','Expense') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `level` tinyint NOT NULL DEFAULT '1' COMMENT '1=Group, 2=Control, 3=Detail',
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_heads`
--

INSERT INTO `account_heads` (`id`, `code`, `parent_id`, `name`, `type`, `level`, `opening_balance`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Cash', NULL, 1, 0.00, '2026-08-22 12:13:40', '2026-08-22 12:13:40'),
(2, NULL, NULL, 'Bank', NULL, 1, 0.00, '2026-08-22 12:13:45', '2026-08-22 12:13:45');

-- --------------------------------------------------------

--
-- Table structure for table `account_histories`
--

CREATE TABLE `account_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `old_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `new_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_histories`
--

INSERT INTO `account_histories` (`id`, `account_id`, `old_balance`, `new_balance`, `user_id`, `user_name`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 0.00, 0.00, 1, 'Super Admin', 'Account created with initial balance: Rs 0.00', '2026-08-22 12:13:58', '2026-08-22 12:13:58'),
(2, 3, 0.00, 5000.00, 1, 'Super Admin', 'Account created with initial balance: Rs 5,000.00', '2026-08-22 12:14:26', '2026-08-22 12:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `biometric_devices`
--

CREATE TABLE `biometric_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int NOT NULL DEFAULT '4370',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `biometric_devices`
--

INSERT INTO `biometric_devices` (`id`, `name`, `ip_address`, `port`, `username`, `password`, `model`, `is_active`, `last_sync_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'office device', '103.79.16.254', 4370, NULL, NULL, 'zk60', 1, '2026-10-06 11:46:28', NULL, '2026-09-12 10:57:57', '2026-10-06 11:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Samsung', '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(2, 'Shan', '2026-08-17 10:22:06', '2026-08-17 10:22:06');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Electronics', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(2, 'machine', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(3, 'Tools', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(4, 'Plumbing', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(5, 'Hardware', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(6, 'Electrical', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(7, 'Automotive', '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(8, 'Inventory', '2026-09-19 11:05:52', '2026-09-19 11:13:28'),
(9, 'Production', '2026-09-19 11:10:07', '2026-09-19 11:12:18'),
(10, 'Genial Pharma', '2026-09-30 13:03:14', '2026-09-30 13:03:14');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_synced` tinyint NOT NULL DEFAULT '0',
  `customer_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name_ur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_officer_id` bigint UNSIGNED DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL,
  `previous_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_range` decimal(12,2) NOT NULL DEFAULT '0.00',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `payment_reminder_date` date DEFAULT NULL,
  `reminder_day` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reminder_snoozed_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `uuid`, `is_synced`, `customer_id`, `customer_name`, `customer_name_ur`, `cnic`, `filer_type`, `zone`, `contact_person`, `mobile`, `email_address`, `contact_person_2`, `mobile_2`, `email_address_2`, `customer_type`, `sales_officer_id`, `opening_balance`, `previous_balance`, `balance_range`, `address`, `created_at`, `updated_at`, `status`, `payment_reminder_date`, `reminder_day`, `reminder_snoozed_at`) VALUES
(1, '7a413407-2a6b-49fd-896d-b446f9a2b918', 0, 'CUST-0001', 'Waleed', NULL, NULL, NULL, NULL, NULL, '04123456789', NULL, NULL, NULL, NULL, 'Main Customer', NULL, 1000.00, 0.00, 0.00, 'hyd', '2026-08-22 12:18:31', '2026-08-22 12:18:31', 'active', NULL, 'Wednesday', NULL),
(2, '05cbcc30-3870-43fb-a78a-761099012acf', 0, 'CUST-0002', 'Muhammad Waleed', NULL, NULL, NULL, NULL, NULL, '030000000', NULL, NULL, NULL, NULL, 'Main Customer', NULL, 0.00, 0.00, 0.00, 'Plot #A65 Sindh Small Industrial Extension Hyderabad Sindh', '2026-09-16 10:55:08', '2026-09-16 10:55:08', 'active', NULL, NULL, NULL),
(3, 'ab6a7066-b11b-4b00-bd75-f291deed0f8f', 0, 'CUST-0003', 'Sidaz Pharmaceutical', NULL, NULL, NULL, NULL, NULL, '09000', NULL, NULL, NULL, NULL, 'Main Customer', NULL, 0.00, 0.00, 0.00, 'Plot #A65 Sindh Small Industrial Extension Hyderabad Sindh', '2026-09-19 10:01:07', '2026-09-19 10:01:07', 'active', NULL, NULL, NULL),
(4, '72e2bab0-8453-469e-98df-44377d20a010', 0, 'CUST-0004', 'Genial Pharma', NULL, NULL, NULL, NULL, NULL, '031000000', NULL, NULL, NULL, NULL, 'Main Customer', NULL, 0.00, 0.00, 0.00, NULL, '2026-09-30 13:13:25', '2026-09-30 13:13:25', 'active', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_ledgers`
--

CREATE TABLE `customer_ledgers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `admin_or_user_id` bigint UNSIGNED NOT NULL,
  `previous_balance` decimal(12,2) NOT NULL,
  `closing_balance` decimal(12,2) NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_ledgers`
--

INSERT INTO `customer_ledgers` (`id`, `customer_id`, `admin_or_user_id`, `previous_balance`, `closing_balance`, `opening_balance`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0.00, 1000.00, 1000.00, NULL, '2026-08-22 12:18:31', '2026-08-22 12:18:31'),
(2, 1, 1, 1000.00, 0.00, 0.00, 'Receipt Voucher RVID-001', '2026-08-22 12:20:00', '2026-08-22 12:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `customer_payments`
--

CREATE TABLE `customer_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `admin_or_user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `day_closings`
--

CREATE TABLE `day_closings` (
  `id` bigint UNSIGNED NOT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `inflow_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `outflow_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `expected_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `actual_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `difference` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opened_at` timestamp NULL DEFAULT NULL,
  `closed_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `day_closings`
--

INSERT INTO `day_closings` (`id`, `opening_balance`, `inflow_amount`, `outflow_amount`, `expected_balance`, `actual_balance`, `difference`, `opened_at`, `closed_at`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 0.00, 77977.00, 366600.18, -288623.18, 4900.00, 293523.18, '2026-08-13 08:19:06', '2026-09-19 10:52:02', 'closed', 'Today Day Close', '2026-08-13 08:19:06', '2026-09-19 10:52:02'),
(2, 10100.00, 5100.00, 3420100.00, -3404900.00, 1000.00, 3405900.00, '2026-09-19 10:52:39', '2026-10-01 05:44:20', 'closed', NULL, '2026-09-19 10:52:39', '2026-10-01 05:44:20'),
(3, 10000.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2026-10-01 05:44:29', NULL, 'open', NULL, '2026-10-01 05:44:29', '2026-10-01 05:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `drawer_transactions`
--

CREATE TABLE `drawer_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `day_closing_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'settled',
  `returned_in_closing_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drawer_transactions`
--

INSERT INTO `drawer_transactions` (`id`, `day_closing_id`, `type`, `category`, `amount`, `description`, `status`, `returned_in_closing_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'in', 'expense', 10000.00, 'Cash In Hand', 'settled', NULL, '2026-09-19 10:49:22', '2026-09-19 10:49:22'),
(2, 1, 'out', 'temporary_market', 500.00, 'Daniyal Bhai Local Purchasing', 'returned', 2, '2026-09-19 10:50:00', '2026-09-19 10:52:39'),
(3, 1, 'out', 'temporary_market', 4600.00, 'Shabir Bhai For Bilty Charges & Auto Rent', 'returned', 2, '2026-09-19 10:50:50', '2026-09-19 10:52:39'),
(4, 2, 'out', 'expense', 100.00, 'Ahmed', 'settled', NULL, '2026-09-19 10:52:56', '2026-09-19 10:52:56'),
(5, 3, 'out', 'expense', 100.00, 'Daniyal Bhai Purchase', 'settled', NULL, '2026-10-01 05:45:17', '2026-10-01 05:45:17');

-- --------------------------------------------------------

--
-- Table structure for table `dummy_invoices`
--

CREATE TABLE `dummy_invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_date` date NOT NULL,
  `order_date` date DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `gate_pass_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `licence_expiry` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `warranty_text` text COLLATE utf8mb4_unicode_ci,
  `signatory_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'SEEMA KHAN',
  `signatory_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Production Incharge',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dummy_invoices`
--

INSERT INTO `dummy_invoices` (`id`, `invoice_no`, `invoice_date`, `order_date`, `customer_name`, `customer_address`, `gate_pass_no`, `licence_no`, `licence_expiry`, `total_amount`, `warranty_text`, `signatory_name`, `signatory_title`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SP01507092026001', '2026-09-22', '2026-09-22', 'MS Waleed', 'asdfghjkl', NULL, '13467890', '123466', 4344.00, 'It is herby certified & I (SEEMA KHAN D/O AHMED KHAN) undertakes the above mentioned finished products of specified batch number supplied by me do not contravene any provision of production related DRAP Act, 2012 and rules framed thereunder. The authorized agent (with valid distribution authorized letter) shall pass on this warranty to the retailers in his area of jurisdiction during the supply of medicine and health product.', 'Waleed shaikh', 'Production Incharge', 1, '2026-09-22 11:15:53', '2026-09-22 11:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `dummy_invoice_items`
--

CREATE TABLE `dummy_invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `dummy_invoice_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pack` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mfg_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exp_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` decimal(15,2) NOT NULL DEFAULT '0.00',
  `mrp` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tp` decimal(15,2) NOT NULL DEFAULT '0.00',
  `dp` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dummy_invoice_items`
--

INSERT INTO `dummy_invoice_items` (`id`, `dummy_invoice_id`, `product_id`, `product_name`, `pack`, `batch_no`, `mfg_date`, `exp_date`, `qty`, `mrp`, `tp`, `dp`, `total_amount`, `created_at`, `updated_at`) VALUES
(2, 1, 3, 'Panadol serup', '12', '232', '20-12-2026', '20-12-2030', 12.00, 150.00, 200.00, 200.00, 2400.00, '2026-09-22 11:17:53', '2026-09-22 11:17:53'),
(3, 1, 5, 'Reviter Syrup', '12', '1212', '12-12-2025', '12-2026', 12.00, 12.00, 50.00, 50.00, 600.00, '2026-09-22 11:17:53', '2026-09-22 11:17:53'),
(4, 1, 4, 'Si-Cid Syrup', '12', '1223', '12-12-2023', '12-12-2040', 12.00, 250.00, 37.00, 37.00, 444.00, '2026-09-22 11:17:53', '2026-09-22 11:17:53'),
(5, 1, NULL, 'teting prodcut', '12', '12', '20-12-2026', '12-20', 30.00, 340.00, 30.00, 30.00, 900.00, '2026-09-22 11:17:53', '2026-09-22 11:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `employee_salary_structures`
--

CREATE TABLE `employee_salary_structures` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_structure_id` bigint UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_custom` tinyint(1) NOT NULL DEFAULT '0',
  `assigned_by` bigint UNSIGNED DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_salary_structures`
--

INSERT INTO `employee_salary_structures` (`id`, `employee_id`, `salary_structure_id`, `start_date`, `end_date`, `is_active`, `is_custom`, `assigned_by`, `notes`, `created_at`, `updated_at`, `updated_by`) VALUES
(1, 1, 1, '2026-08-31', '2026-08-30', 0, 0, 1, NULL, '2026-08-31 08:59:35', '2026-08-31 09:16:36', NULL),
(2, 1, 2, '2026-08-31', '2026-08-30', 0, 1, 1, 'Individual Update: ', '2026-08-31 09:16:36', '2026-08-31 09:21:04', 1),
(3, 2, 1, '2026-08-31', '2026-08-31', 0, 0, 1, NULL, '2026-08-31 09:21:04', '2026-08-31 13:11:28', NULL),
(4, 1, 1, '2026-08-31', '2026-08-30', 0, 0, 1, NULL, '2026-08-31 09:21:04', '2026-08-31 13:04:30', NULL),
(5, 4, 4, '2026-08-31', NULL, 1, 0, 1, NULL, '2026-08-31 13:04:11', '2026-08-31 13:04:11', NULL),
(6, 1, 3, '2026-08-31', NULL, 1, 0, 1, NULL, '2026-08-31 13:04:30', '2026-08-31 13:04:30', NULL),
(7, 5, 6, '2026-08-31', NULL, 1, 0, 1, NULL, '2026-08-31 13:06:53', '2026-08-31 13:06:53', NULL),
(8, 3, 1, '2026-08-31', NULL, 1, 0, 1, NULL, '2026-08-31 13:11:58', '2026-08-31 13:11:58', NULL),
(9, 24, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(10, 25, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(11, 27, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(12, 18, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(13, 30, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(14, 31, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(15, 32, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(16, 33, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(17, 14, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(18, 23, 7, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:57:29', '2026-09-12 12:57:29', NULL),
(19, 10, 8, '2026-09-12', NULL, 1, 0, 1, NULL, '2026-09-12 12:58:17', '2026-09-12 12:58:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `code`, `description`, `created_at`, `updated_at`) VALUES
(1, 'utility bills', 'ut-01', NULL, '2026-10-03 11:31:29', '2026-10-03 11:31:29');

-- --------------------------------------------------------

--
-- Table structure for table `expense_vouchers`
--

CREATE TABLE `expense_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `evid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_no` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `narration_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_head` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formulations`
--

CREATE TABLE `formulations` (
  `id` bigint UNSIGNED NOT NULL,
  `formulation_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_of_dropper` int DEFAULT NULL,
  `weight_per_bottle` decimal(10,2) DEFAULT NULL,
  `bmr_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_packs` int DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `mfg_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `batch_size` decimal(10,2) NOT NULL,
  `batch_unit_id` bigint UNSIGNED DEFAULT NULL,
  `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1.0',
  `effective_date` date DEFAULT NULL,
  `status` enum('draft','active','inactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulations`
--

INSERT INTO `formulations` (`id`, `formulation_code`, `doc_no`, `batch_no`, `qty_of_dropper`, `weight_per_bottle`, `bmr_no`, `total_packs`, `issue_date`, `mfg_date`, `exp_date`, `company_name`, `product_id`, `department_id`, `batch_size`, `batch_unit_id`, `version`, `effective_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'FORM-0006', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 6, 1, 1.00, 1, '1.0', '2026-09-30', 'active', 'Composition profile created via Product Wizard', '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(2, 'FORM-0007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, 1, 875.00, 1, '1.0', '2026-09-30', 'active', 'Composition profile created via Product Wizard', '2026-09-30 13:08:31', '2026-09-30 13:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `formulation_packaging_materials`
--

CREATE TABLE `formulation_packaging_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `formulation_id` bigint UNSIGNED NOT NULL,
  `packaging_material_id` bigint UNSIGNED NOT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulation_packaging_materials`
--

INSERT INTO `formulation_packaging_materials` (`id`, `formulation_id`, `packaging_material_id`, `quantity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1.0000, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55');

-- --------------------------------------------------------

--
-- Table structure for table `formulation_raw_materials`
--

CREATE TABLE `formulation_raw_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `formulation_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(12,4) NOT NULL,
  `waste_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `formulation_raw_materials`
--

INSERT INTO `formulation_raw_materials` (`id`, `formulation_id`, `raw_material_id`, `unit_id`, `quantity`, `waste_percent`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 97, 2, 60.0000, 0.00, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(2, 1, 95, 2, 60.0000, 0.00, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(3, 1, 96, 2, 60.0000, 0.00, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(4, 1, 112, 2, 60.0000, 0.00, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(5, 2, 17, 2, 9.1900, 0.00, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31'),
(6, 2, 142, 2, 0.0035, 0.00, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31'),
(7, 2, 144, 2, 0.0003, 0.00, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31'),
(8, 2, 150, 2, 0.4400, 0.00, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `hr_attendances`
--

CREATE TABLE `hr_attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `check_in_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_latitude` decimal(10,8) DEFAULT NULL,
  `check_in_longitude` decimal(11,8) DEFAULT NULL,
  `check_in_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_latitude` decimal(10,8) DEFAULT NULL,
  `check_out_longitude` decimal(11,8) DEFAULT NULL,
  `check_out_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `status` enum('present','absent','late','leave') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `is_late` tinyint(1) NOT NULL DEFAULT '0',
  `late_minutes` int NOT NULL DEFAULT '0',
  `is_early_in` tinyint(1) NOT NULL DEFAULT '0',
  `early_in_minutes` int NOT NULL DEFAULT '0',
  `is_early_leave` tinyint(1) NOT NULL DEFAULT '0',
  `early_leave_minutes` int NOT NULL DEFAULT '0',
  `total_hours` decimal(5,2) DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `device_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_attendances`
--

INSERT INTO `hr_attendances` (`id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `check_in_photo`, `check_in_latitude`, `check_in_longitude`, `check_in_location`, `check_out_photo`, `check_out_latitude`, `check_out_longitude`, `check_out_location`, `clock_in`, `clock_out`, `status`, `is_late`, `late_minutes`, `is_early_in`, `early_in_minutes`, `is_early_leave`, `early_leave_minutes`, `total_hours`, `notes`, `device_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-08-31', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'late', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-08-31 08:52:02', '2026-08-31 18:23:50'),
(2, 2, '2026-08-31', '08:55:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:55:00', '18:30:00', 'late', 1, 25, 0, 0, 0, 0, 9.58, NULL, NULL, '2026-08-31 10:24:36', '2026-08-31 10:25:41'),
(3, 15, '2026-08-31', NULL, NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-08-31 13:19:12', '2026-08-31 13:19:54'),
(4, 5, '2026-08-31', NULL, NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-08-31 13:19:12', '2026-08-31 13:19:54'),
(5, 3, '2026-08-31', NULL, NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-08-31 13:19:12', '2026-08-31 13:19:54'),
(6, 29, '2026-09-01', NULL, NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-01 03:29:50', '2026-09-01 04:12:52'),
(7, 20, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:29:50', '2026-09-01 03:29:50'),
(8, 21, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:29:50', '2026-09-01 03:29:50'),
(9, 24, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:29:50', '2026-09-01 03:29:50'),
(10, 25, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:33:29', '2026-09-01 03:33:29'),
(11, 26, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:33:29', '2026-09-01 03:33:29'),
(12, 27, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:33:29', '2026-09-01 03:33:29'),
(13, 33, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:33:29', '2026-09-01 03:33:29'),
(14, 18, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(15, 30, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(16, 31, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(17, 32, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(18, 16, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(19, 14, '2026-09-01', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(20, 13, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(21, 12, '2026-09-01', '08:30:00', '17:33:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '17:33:00', 'present', 0, 0, 1, 30, 0, 0, 9.05, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(22, 11, '2026-09-01', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 04:41:31'),
(23, 10, '2026-09-01', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-01 03:37:56', '2026-09-01 03:37:56'),
(24, 8, '2026-09-01', '08:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '08:00:00', 'present', 0, 0, 1, 30, 1, 30, 0.00, NULL, NULL, '2026-09-01 03:39:30', '2026-09-01 03:39:30'),
(25, 2, '2026-09-01', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-01 03:39:30', '2026-09-01 03:39:30'),
(26, 19, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:39:30', '2026-09-01 03:39:30'),
(27, 23, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:39:30', '2026-09-01 03:39:30'),
(28, 17, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:39:48', '2026-09-01 03:41:44'),
(29, 22, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:41:44', '2026-09-01 03:41:44'),
(30, 4, '2026-09-01', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-01 03:41:44', '2026-09-01 03:41:44'),
(31, 7, '2026-09-01', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-01 03:41:44', '2026-09-01 03:41:44'),
(32, 1, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 03:41:44', '2026-09-01 03:41:44'),
(33, 15, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 04:02:49', '2026-09-01 04:02:49'),
(34, 5, '2026-09-01', '09:00:00', '05:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '05:30:00', 'present', 0, 0, 0, 0, 1, 720, 0.00, NULL, NULL, '2026-09-01 04:02:49', '2026-09-01 04:02:49'),
(35, 3, '2026-09-01', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-01 04:02:49', '2026-09-01 04:02:49'),
(36, 9, '2026-09-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-01 04:02:49', '2026-09-01 04:02:49'),
(37, 15, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 05:47:34'),
(38, 5, '2026-09-02', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(39, 3, '2026-09-02', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(40, 9, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(41, 29, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(42, 20, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(43, 21, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(44, 24, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(45, 25, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(46, 26, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:18:27'),
(47, 27, '2026-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:26:31'),
(48, 33, '2026-09-02', '13:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:00:00', 'present', 0, 0, 0, 0, 1, 30, 4.50, NULL, NULL, '2026-09-02 04:18:27', '2026-09-02 04:30:31'),
(49, 18, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(50, 30, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(51, 31, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(52, 32, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(53, 16, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(54, 14, '2026-09-02', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(55, 13, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(56, 12, '2026-09-02', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(57, 11, '2026-09-02', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(58, 10, '2026-09-02', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-02 04:22:36', '2026-09-02 04:22:36'),
(59, 8, '2026-09-02', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(60, 2, '2026-09-02', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(61, 19, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(62, 23, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(63, 22, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(64, 17, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(65, 4, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(66, 7, '2026-09-02', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(67, 1, '2026-09-02', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-02 04:25:07', '2026-09-02 04:25:07'),
(69, 6, '2026-09-02', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-02 09:18:14', '2026-09-02 09:18:38'),
(70, 29, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(71, 20, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(72, 21, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(73, 24, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:47:29'),
(74, 25, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(75, 26, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(76, 27, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:42:24', '2026-09-03 03:42:24'),
(77, 18, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(78, 30, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(79, 31, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(80, 32, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(81, 16, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(82, 14, '2026-09-03', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:55:34'),
(83, 13, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(84, 11, '2026-09-03', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:46:04'),
(85, 10, '2026-09-03', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-03 03:45:27', '2026-09-03 03:45:27'),
(87, 8, '2026-09-03', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(88, 2, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 1, 30, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(89, 19, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(90, 23, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(91, 22, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(92, 17, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(93, 4, '2026-09-03', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(94, 7, '2026-09-03', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(95, 1, '2026-09-03', '08:50:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:50:00', '18:00:00', 'late', 1, 20, 0, 0, 0, 0, 9.17, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(96, 6, '2026-09-03', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-03 03:50:08', '2026-09-03 03:50:08'),
(97, 33, '2026-09-03', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-03 03:51:19', '2026-09-03 03:51:19'),
(98, 9, '2026-09-03', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-03 03:51:49', '2026-09-03 03:57:14'),
(99, 15, '2026-09-03', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-03 03:56:51', '2026-09-03 03:56:51'),
(100, 5, '2026-09-03', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-03 04:04:58', '2026-09-03 04:04:58'),
(101, 3, '2026-09-03', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-03 04:04:58', '2026-09-03 04:04:58'),
(102, 12, '2026-09-03', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-03 04:05:42', '2026-09-03 04:05:42'),
(103, 15, '2026-09-05', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(104, 9, '2026-09-05', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(105, 29, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(106, 20, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(107, 21, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(108, 24, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(109, 25, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(110, 26, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(111, 27, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(112, 33, '2026-09-05', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-05 04:00:48', '2026-09-05 04:00:48'),
(113, 18, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(114, 30, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(115, 31, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(116, 32, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(118, 16, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(119, 14, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(120, 13, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(121, 12, '2026-09-05', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(122, 11, '2026-09-05', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(123, 10, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:02:45', '2026-09-05 04:02:45'),
(124, 8, '2026-09-05', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(125, 2, '2026-09-05', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(126, 19, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(127, 23, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(128, 22, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(129, 17, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(130, 4, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(131, 7, '2026-09-05', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(132, 1, '2026-09-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(133, 6, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:04:42', '2026-09-05 04:04:42'),
(134, 5, '2026-09-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-05 04:16:18', '2026-09-05 04:16:18'),
(135, 3, '2026-09-05', '09:10:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:10:00', '17:30:00', 'late', 1, 10, 0, 0, 0, 0, 8.33, NULL, NULL, '2026-09-05 04:16:18', '2026-09-05 04:16:18'),
(136, 15, '2026-09-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(137, 5, '2026-09-07', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(138, 3, '2026-09-07', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(139, 9, '2026-09-07', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(140, 29, '2026-09-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(141, 20, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(142, 21, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(143, 24, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(144, 25, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(145, 26, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(146, 27, '2026-09-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(147, 33, '2026-09-07', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-07 04:43:28', '2026-09-07 04:43:28'),
(148, 18, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(149, 30, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(150, 31, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(151, 32, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(153, 16, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(154, 14, '2026-09-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(155, 13, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(156, 12, '2026-09-07', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(157, 11, '2026-09-07', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(158, 10, '2026-09-07', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-07 04:47:35', '2026-09-07 04:47:35'),
(159, 8, '2026-09-07', '08:30:00', '20:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '20:30:00', 'present', 0, 0, 0, 0, 0, 0, 12.00, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(160, 2, '2026-09-07', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(161, 19, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(162, 23, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(163, 22, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(164, 17, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(165, 4, '2026-09-07', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(166, 7, '2026-09-07', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(167, 1, '2026-09-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(168, 6, '2026-09-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 04:50:40', '2026-09-07 04:50:40'),
(169, 15, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(170, 5, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(171, 3, '2026-08-07', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(172, 9, '2026-08-07', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(173, 29, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(174, 20, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(175, 21, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(176, 24, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(177, 25, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(178, 26, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(179, 27, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(180, 33, '2026-08-07', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-07 05:47:45', '2026-09-07 05:47:45'),
(181, 18, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(182, 30, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(183, 31, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(184, 32, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(186, 16, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(187, 14, '2026-08-07', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(188, 13, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(189, 12, '2026-08-07', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(190, 11, '2026-08-07', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(191, 10, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:50:36', '2026-09-07 05:50:36'),
(192, 8, '2026-08-07', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(193, 2, '2026-08-07', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(194, 19, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(195, 23, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(196, 22, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(197, 17, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(198, 4, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 1, 30, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(199, 7, '2026-08-07', '07:30:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(200, 1, '2026-08-07', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 05:52:43'),
(201, 6, '2026-08-07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-07 05:52:43', '2026-09-07 06:00:28'),
(202, 9, '2026-09-08', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(203, 29, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(204, 20, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(205, 21, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(206, 24, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(207, 25, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(208, 26, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(209, 27, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(210, 33, '2026-09-08', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-08 04:11:58', '2026-09-08 04:11:58'),
(212, 16, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:13:59', '2026-09-08 04:13:59'),
(213, 14, '2026-09-08', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-08 04:13:59', '2026-09-08 04:13:59'),
(214, 13, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:13:59', '2026-09-08 04:13:59'),
(215, 12, '2026-09-08', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-08 04:13:59', '2026-09-08 04:13:59'),
(216, 11, '2026-09-08', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:14:00', '2026-09-08 04:14:21'),
(217, 10, '2026-09-08', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-08 04:14:00', '2026-09-08 04:14:00'),
(218, 18, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:15:05', '2026-09-08 04:15:05'),
(219, 30, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:15:05', '2026-09-08 04:15:05'),
(220, 31, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:15:05', '2026-09-08 04:15:05'),
(221, 32, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:15:05', '2026-09-08 04:15:05'),
(222, 8, '2026-09-08', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(223, 2, '2026-09-08', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(224, 19, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(225, 23, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(226, 22, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50');
INSERT INTO `hr_attendances` (`id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `check_in_photo`, `check_in_latitude`, `check_in_longitude`, `check_in_location`, `check_out_photo`, `check_out_latitude`, `check_out_longitude`, `check_out_location`, `clock_in`, `clock_out`, `status`, `is_late`, `late_minutes`, `is_early_in`, `early_in_minutes`, `is_early_leave`, `early_leave_minutes`, `total_hours`, `notes`, `device_id`, `created_at`, `updated_at`) VALUES
(227, 17, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(228, 4, '2026-09-08', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(229, 7, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(230, 1, '2026-09-08', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(231, 6, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:16:50', '2026-09-08 04:16:50'),
(232, 15, '2026-09-08', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-08 04:17:46', '2026-09-08 04:17:46'),
(233, 5, '2026-09-08', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-08 04:17:46', '2026-09-08 04:24:59'),
(234, 3, '2026-09-08', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-08 04:17:46', '2026-09-08 04:25:00'),
(235, 15, '2026-09-09', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(236, 5, '2026-09-09', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(237, 3, '2026-09-09', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(238, 9, '2026-09-09', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(239, 29, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(240, 20, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(241, 21, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(242, 24, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(243, 25, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(244, 26, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(245, 27, '2026-09-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(246, 33, '2026-09-09', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-09 05:12:23', '2026-09-09 05:12:23'),
(247, 18, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:14:21', '2026-09-09 05:14:21'),
(248, 30, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:14:21', '2026-09-09 05:14:21'),
(249, 31, '2026-09-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-09 05:14:21', '2026-09-09 05:14:21'),
(250, 32, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(252, 16, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(253, 14, '2026-09-09', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(254, 13, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(255, 12, '2026-09-09', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(256, 11, '2026-09-09', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(257, 10, '2026-09-09', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-09 05:14:22', '2026-09-09 05:14:22'),
(258, 8, '2026-09-09', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(259, 2, '2026-09-09', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(260, 19, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(261, 23, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(262, 22, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(263, 17, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(264, 4, '2026-09-09', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:16:34'),
(265, 7, '2026-09-09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:19:13'),
(266, 1, '2026-09-09', '17:30:48', '20:30:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 540, 0, 0, 0, 0, 2.98, NULL, NULL, '2026-09-09 05:16:34', '2026-10-06 11:46:12'),
(267, 6, '2026-09-09', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-09 05:16:34', '2026-09-09 05:18:21'),
(268, 15, '2026-09-10', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(269, 9, '2026-09-10', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(270, 29, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(271, 20, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(272, 21, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(273, 24, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(274, 25, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(275, 26, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(276, 27, '2026-09-10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 03:54:13'),
(277, 33, '2026-09-10', NULL, NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-10 03:54:13', '2026-09-10 08:38:12'),
(278, 18, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(279, 30, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(280, 31, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(281, 32, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(282, 16, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(283, 14, '2026-09-10', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(284, 13, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(285, 12, '2026-09-10', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(286, 11, '2026-09-10', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(287, 10, '2026-09-10', '08:00:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.50, NULL, NULL, '2026-09-10 03:56:24', '2026-09-10 03:56:24'),
(288, 8, '2026-09-10', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(289, 2, '2026-09-10', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(290, 23, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(291, 22, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(292, 17, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(293, 4, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 1, 30, 9.50, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(294, 1, '2026-09-10', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-10 03:58:14', '2026-09-10 03:58:14'),
(295, 5, '2026-09-10', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-10 03:58:48', '2026-09-10 03:58:48'),
(296, 3, '2026-09-10', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-10 03:58:48', '2026-09-10 03:58:48'),
(297, 15, '2026-09-12', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(298, 5, '2026-09-12', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(299, 3, '2026-09-12', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(300, 9, '2026-09-12', '09:00:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(301, 29, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(302, 20, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:12'),
(303, 21, '2026-09-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-12 04:05:32', '2026-09-12 04:05:59'),
(304, 24, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(305, 25, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:12'),
(306, 26, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:12'),
(307, 27, '2026-09-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-12 04:05:32', '2026-09-12 04:05:41'),
(308, 33, '2026-09-12', '13:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 5.00, NULL, NULL, '2026-09-12 04:05:32', '2026-10-06 11:46:13'),
(309, 18, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(310, 30, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:12'),
(311, 31, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(312, 32, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(314, 16, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:12'),
(315, 14, '2026-09-12', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(316, 13, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:08:05', '2026-09-12 04:08:05'),
(317, 12, '2026-09-12', '09:00:00', '18:12:43', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 9.20, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(318, 11, '2026-09-12', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-12 04:08:05', '2026-09-12 04:08:05'),
(319, 10, '2026-09-12', '08:00:00', '18:49:20', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:00:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.82, NULL, NULL, '2026-09-12 04:08:05', '2026-10-06 11:46:13'),
(320, 8, '2026-09-12', '08:30:00', '21:22:24', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 12.87, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:13'),
(321, 2, '2026-09-12', '20:14:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:14:00', '18:00:00', 'late', 1, 704, 0, 0, 1, 30, 0.00, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(322, 19, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(323, 23, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(324, 22, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(325, 17, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:13'),
(326, 4, '2026-09-12', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(327, 7, '2026-09-12', '07:30:00', '21:08:38', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '07:30:00', '21:00:00', 'present', 0, 0, 0, 0, 0, 0, 13.63, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:13'),
(328, 1, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:13'),
(329, 6, '2026-09-12', '16:27:47', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 477, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-12 04:09:53', '2026-10-06 11:46:12'),
(330, 1, '2026-09-06', '11:24:55', '13:27:50', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 174, 0, 0, 1, 272, 2.03, NULL, NULL, '2026-09-12 11:07:07', '2026-10-06 11:46:12'),
(331, 2, '2026-09-06', '13:30:41', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 300, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-12 11:07:07', '2026-10-06 11:46:12'),
(332, 28, '2026-09-12', '16:34:40', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 484, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-12 12:50:47', '2026-10-06 11:46:12'),
(333, 7, '2026-09-13', '07:09:52', '20:54:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 20, 1, 5, 13.73, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:14'),
(334, 4, '2026-09-13', '08:11:48', '18:25:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 18, 1, 4, 10.22, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:14'),
(335, 15, '2026-09-13', '08:12:08', '18:19:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 47, 0, 0, 10.12, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(336, 10, '2026-09-13', '08:14:49', '18:35:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 14, 0, 0, 0, 0, 10.33, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:14'),
(337, 20, '2026-09-13', '08:14:56', '18:04:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 15, 0, 0, 9.82, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(338, 8, '2026-09-13', '08:16:57', '20:54:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 13, 0, 0, 12.63, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:14'),
(339, 2, '2026-09-13', '08:20:02', '18:26:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 9, 1, 3, 10.10, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:14'),
(340, 1, '2026-09-13', '08:22:54', '17:58:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 7, 1, 1, 9.58, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(341, 30, '2026-09-13', '08:23:34', '18:01:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, 9.62, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(342, 23, '2026-09-13', '08:24:15', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, 9.60, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(343, 32, '2026-09-13', '08:24:25', '18:00:01', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, 9.58, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(344, 13, '2026-09-13', '08:26:22', '17:59:27', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 3, 1, 0, 9.55, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(345, 17, '2026-09-13', '08:27:46', '18:00:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 2, 0, 0, 9.53, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(346, 22, '2026-09-13', '08:29:24', '17:59:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(347, 24, '2026-09-13', '08:29:33', '17:59:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(348, 29, '2026-09-13', '08:29:49', '17:59:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(349, 26, '2026-09-13', '08:29:55', '17:59:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(350, 16, '2026-09-13', '08:29:59', '17:59:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(351, 28, '2026-09-13', '08:30:06', '18:00:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(352, 18, '2026-09-13', '08:30:11', '17:59:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(353, 25, '2026-09-13', '08:30:21', '18:00:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(354, 19, '2026-09-13', '08:31:21', '18:01:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(355, 27, '2026-09-13', '08:32:17', '18:00:42', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-13 04:23:03', '2026-10-06 11:46:13'),
(356, 9, '2026-09-13', '08:42:03', '18:19:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 17, 0, 0, 9.62, NULL, NULL, '2026-09-13 04:23:04', '2026-10-06 11:46:13'),
(357, 6, '2026-09-13', '08:45:51', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 15, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-13 04:23:04', '2026-10-06 11:46:13'),
(358, 3, '2026-09-13', '08:58:15', '17:38:17', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 8.67, NULL, NULL, '2026-09-13 04:23:04', '2026-10-06 11:46:13'),
(359, 5, '2026-09-13', '09:00:09', '17:36:27', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.60, NULL, NULL, '2026-09-13 04:23:04', '2026-10-06 11:46:13'),
(360, 12, '2026-09-13', '09:00:00', NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, '09:00:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-13 04:24:12', '2026-09-13 04:40:39'),
(361, 11, '2026-09-13', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-13 04:24:24', '2026-09-13 04:40:39'),
(363, 14, '2026-09-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-13 04:41:13', '2026-09-13 04:41:13'),
(364, 33, '2026-09-13', '11:08:16', '18:31:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 141, 0, 0, 7.38, NULL, NULL, '2026-09-13 04:42:24', '2026-10-06 11:46:14'),
(365, 31, '2026-09-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-13 04:43:56', '2026-09-13 04:43:56'),
(366, 21, '2026-09-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-13 04:44:18', '2026-09-13 04:44:18'),
(367, 8, '2026-09-14', '07:05:50', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 84, 0, 0, NULL, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(368, 7, '2026-09-14', '07:39:15', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(369, 14, '2026-09-14', '07:52:25', '18:16:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 7, 1, 43, 10.40, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(370, 2, '2026-09-14', '08:13:00', '18:16:26', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:13:00', NULL, 'present', 0, 0, 1, 17, 1, 13, 10.05, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(371, 1, '2026-09-14', '08:23:41', '17:59:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 1, 0, 9.58, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(372, 19, '2026-09-14', '08:29:51', '17:59:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(373, 22, '2026-09-14', '08:30:05', '17:59:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(374, 26, '2026-09-14', '08:30:10', '17:59:41', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(375, 16, '2026-09-14', '08:30:26', '17:59:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(376, 18, '2026-09-14', '08:30:30', '17:59:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.47, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(377, 28, '2026-09-14', '08:30:41', '18:00:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(378, 31, '2026-09-14', '08:30:47', '17:59:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(379, 32, '2026-09-14', '08:31:01', '18:01:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(380, 25, '2026-09-14', '08:31:06', '18:01:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(381, 23, '2026-09-14', '08:31:17', '18:00:50', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(382, 17, '2026-09-14', '08:31:23', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(383, 29, '2026-09-14', '08:31:27', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(384, 27, '2026-09-14', '08:32:16', '18:02:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(385, 21, '2026-09-14', '08:32:23', '18:00:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.45, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:14'),
(386, 4, '2026-09-14', '08:32:48', '18:10:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 19, 9.63, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(387, 15, '2026-09-14', '08:32:51', '18:10:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 27, 0, 0, 9.63, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(388, 20, '2026-09-14', '08:33:37', '18:11:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.62, NULL, NULL, '2026-09-14 04:16:21', '2026-10-06 11:46:15'),
(389, 13, '2026-09-14', '08:33:57', '18:00:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.43, NULL, NULL, '2026-09-14 04:16:22', '2026-10-06 11:46:14'),
(390, 10, '2026-09-14', '08:41:43', '18:00:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 41, 0, 0, 1, 29, 9.32, NULL, NULL, '2026-09-14 04:16:22', '2026-10-06 11:46:14'),
(391, 9, '2026-09-14', '08:58:45', '18:05:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.10, NULL, NULL, '2026-09-14 04:16:22', '2026-10-06 11:46:15'),
(392, 5, '2026-09-14', '09:01:55', '17:33:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.52, NULL, NULL, '2026-09-14 04:16:22', '2026-10-06 11:46:14'),
(393, 3, '2026-09-14', '09:02:20', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-14 04:16:22', '2026-10-06 11:46:14'),
(394, 12, '2026-09-14', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-14 04:36:30', '2026-09-14 04:36:30'),
(395, 11, '2026-09-14', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-14 04:36:30', '2026-09-14 04:36:30'),
(396, 11, '2026-09-15', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-15 03:46:06', '2026-09-15 03:46:06'),
(397, 12, '2026-09-15', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-15 03:46:36', '2026-09-15 03:46:36'),
(398, 33, '2026-09-14', '13:15:05', '18:00:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 14, 1, 29, 4.75, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:14'),
(399, 8, '2026-09-15', '06:58:00', '20:00:18', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '06:58:00', '08:30:00', 'present', 0, 0, 1, 92, 0, 0, 13.03, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(400, 7, '2026-09-15', '07:38:00', '21:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:38:00', '21:00:00', 'late', 1, 8, 0, 0, 0, 0, 13.37, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(401, 14, '2026-09-15', '07:46:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '07:46:00', '19:00:00', 'present', 0, 0, 1, 14, 0, 0, 11.23, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(402, 2, '2026-09-15', '08:11:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:11:00', '18:30:00', 'present', 0, 0, 1, 19, 0, 0, 10.32, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(403, 10, '2026-09-15', '08:24:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:24:00', '18:30:00', 'late', 1, 24, 0, 0, 0, 0, 10.10, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(404, 13, '2026-09-15', '08:25:00', '18:00:13', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:25:00', '18:00:00', 'present', 0, 0, 1, 5, 0, 0, 9.58, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(405, 1, '2026-09-15', '08:26:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:26:00', '18:00:00', 'present', 0, 0, 1, 4, 0, 0, 9.57, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(406, 31, '2026-09-15', '08:28:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:28:00', '18:00:00', 'present', 0, 0, 1, 2, 0, 0, 9.53, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(407, 22, '2026-09-15', '08:29:00', '18:02:16', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.55, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(408, 24, '2026-09-15', '08:29:10', '18:00:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(409, 26, '2026-09-15', '08:29:18', '18:01:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.53, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(410, 18, '2026-09-15', '08:29:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(411, 19, '2026-09-15', '08:29:00', '18:02:50', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.55, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(412, 20, '2026-09-15', '08:29:00', '18:01:26', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.53, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(413, 21, '2026-09-15', '08:29:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(414, 23, '2026-09-15', '08:29:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:29:00', '18:00:00', 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(415, 30, '2026-09-15', '08:30:00', '17:59:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(416, 28, '2026-09-15', '08:30:00', '18:07:12', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.62, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(417, 32, '2026-09-15', '08:30:00', '18:00:22', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(418, 16, '2026-09-15', '08:30:00', '18:10:13', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.67, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(419, 29, '2026-09-15', '08:30:18', '18:01:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(420, 25, '2026-09-15', '08:30:00', '18:00:40', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(421, 17, '2026-09-15', '08:41:00', '18:10:17', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:41:00', '18:00:00', 'late', 1, 11, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(422, 15, '2026-09-15', '08:48:00', '18:07:59', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:48:00', '18:00:00', 'present', 0, 0, 1, 12, 0, 0, 9.32, NULL, NULL, '2026-09-15 04:11:14', '2026-10-06 11:46:15'),
(423, 27, '2026-09-15', '08:57:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:57:00', '18:00:00', 'late', 1, 27, 0, 0, 0, 0, 9.05, NULL, NULL, '2026-09-15 04:11:15', '2026-10-06 11:46:15'),
(424, 5, '2026-09-15', '09:06:00', '17:33:46', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:06:00', '17:30:00', 'late', 1, 6, 0, 0, 0, 0, 8.45, NULL, NULL, '2026-09-15 04:11:15', '2026-10-06 11:46:15'),
(425, 3, '2026-09-15', '09:07:00', '17:32:50', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:07:00', '17:30:00', 'late', 1, 7, 0, 0, 0, 0, 8.42, NULL, NULL, '2026-09-15 04:11:15', '2026-10-06 11:46:15'),
(426, 9, '2026-09-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-15 04:12:00', '2026-09-15 04:12:00'),
(427, 4, '2026-09-15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-15 04:12:25', '2026-09-15 04:12:25'),
(428, 6, '2026-09-15', '10:35:00', '18:07:29', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '10:35:00', '18:00:00', 'late', 1, 125, 0, 0, 0, 0, 7.53, NULL, NULL, '2026-09-15 04:12:36', '2026-10-06 11:46:15'),
(429, 33, '2026-09-15', '13:40:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:40:00', '18:30:00', 'late', 1, 10, 0, 0, 0, 0, 4.83, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:15'),
(430, 7, '2026-09-16', '07:28:00', '21:32:45', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '07:28:00', '21:00:00', 'present', 0, 0, 1, 2, 0, 0, 14.07, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(431, 2, '2026-09-16', '08:17:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:17:00', '19:00:00', 'present', 0, 0, 1, 13, 0, 0, 10.72, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:15'),
(432, 10, '2026-09-16', '08:23:00', '19:02:30', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:23:00', '19:00:00', 'late', 1, 23, 0, 0, 0, 0, 10.65, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(433, 1, '2026-09-16', '08:24:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:24:00', '18:00:00', 'present', 0, 0, 1, 6, 0, 0, 9.60, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:15'),
(434, 13, '2026-09-16', '08:28:00', '18:01:21', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:28:00', '18:00:00', 'present', 0, 0, 1, 2, 0, 0, 9.55, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(435, 31, '2026-09-16', '08:30:00', '18:00:48', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(436, 26, '2026-09-16', '08:30:11', '18:01:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(437, 19, '2026-09-16', '08:30:00', '15:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '15:00:00', 'present', 0, 0, 0, 0, 1, 180, 6.50, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(438, 16, '2026-09-16', '08:30:00', '18:00:35', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(439, 18, '2026-09-16', '08:30:00', '18:00:39', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(440, 22, '2026-09-16', '08:30:00', '18:03:28', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.55, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(441, 20, '2026-09-16', '08:30:00', '18:01:42', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(442, 28, '2026-09-16', '08:30:00', '18:01:03', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(443, 30, '2026-09-16', '08:30:44', '19:05:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.57, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(444, 23, '2026-09-16', '08:30:00', '18:02:24', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.53, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(445, 17, '2026-09-16', '08:31:00', '18:21:05', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:31:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.83, NULL, NULL, '2026-09-17 03:34:23', '2026-10-06 11:46:16'),
(446, 25, '2026-09-16', '08:31:00', '18:00:56', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:31:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(447, 27, '2026-09-16', '08:37:00', '13:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:37:00', '13:00:00', 'late', 1, 7, 0, 0, 1, 300, 4.38, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(448, 32, '2026-09-16', '08:38:00', '18:01:12', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:38:00', '18:00:00', 'late', 1, 8, 0, 0, 0, 0, 9.38, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16');
INSERT INTO `hr_attendances` (`id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `check_in_photo`, `check_in_latitude`, `check_in_longitude`, `check_in_location`, `check_out_photo`, `check_out_latitude`, `check_out_longitude`, `check_out_location`, `clock_in`, `clock_out`, `status`, `is_late`, `late_minutes`, `is_early_in`, `early_in_minutes`, `is_early_leave`, `early_leave_minutes`, `total_hours`, `notes`, `device_id`, `created_at`, `updated_at`) VALUES
(449, 6, '2026-09-16', '08:44:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:44:00', '18:00:00', 'late', 1, 14, 0, 0, 0, 0, 9.27, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(450, 9, '2026-09-16', '08:45:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:45:00', '18:00:00', 'present', 0, 0, 1, 15, 0, 0, 9.25, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(451, 15, '2026-09-16', '08:49:00', '19:05:45', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:49:00', '19:00:00', 'present', 0, 0, 1, 11, 0, 0, 10.27, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(452, 5, '2026-09-16', '09:06:00', '17:35:28', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:06:00', '17:30:00', 'late', 1, 6, 0, 0, 0, 0, 8.48, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(453, 3, '2026-09-16', '09:07:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:07:00', '17:30:00', 'late', 1, 7, 0, 0, 0, 0, 8.38, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(454, 24, '2026-09-16', '09:37:02', '19:04:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 67, 0, 0, 0, 0, 9.45, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(455, 29, '2026-09-16', '09:45:58', '19:05:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 75, 0, 0, 0, 0, 9.32, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(456, 33, '2026-09-16', '13:32:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '13:32:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 4.97, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(457, 14, '2026-09-16', '18:58:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '18:58:00', '19:00:00', 'late', 1, 658, 0, 0, 0, 0, 0.03, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(458, 8, '2026-09-16', '08:30:00', '21:18:38', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 12.80, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(459, 8, '2026-09-17', '06:44:51', '21:23:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 105, 0, 0, 14.65, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(460, 7, '2026-09-17', '07:48:24', '21:23:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 18, 0, 0, 0, 0, 13.57, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(461, 14, '2026-09-17', '08:06:14', '20:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 11.90, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(462, 2, '2026-09-17', '08:22:07', '20:00:16', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 7, 0, 0, 11.63, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(463, 4, '2026-09-17', '08:24:15', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, NULL, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(464, 15, '2026-09-17', '08:24:18', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 35, 0, 0, NULL, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:16'),
(465, 20, '2026-09-17', '08:26:36', '18:02:27', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 3, 0, 0, 9.58, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(466, 22, '2026-09-17', '08:28:21', '18:00:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(467, 28, '2026-09-17', '08:28:27', '18:01:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.53, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(468, 21, '2026-09-17', '08:28:41', '18:01:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.53, NULL, NULL, '2026-09-17 03:34:24', '2026-10-06 11:46:17'),
(469, 24, '2026-09-17', '08:28:46', '18:00:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:16'),
(470, 32, '2026-09-17', '08:28:54', '18:01:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.53, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(471, 23, '2026-09-17', '08:29:02', '18:00:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(472, 17, '2026-09-17', '08:29:05', '18:01:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.53, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(473, 16, '2026-09-17', '08:29:20', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(474, 1, '2026-09-17', '08:29:24', '17:58:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 1, 9.47, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:16'),
(475, 31, '2026-09-17', '08:29:32', '18:00:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(476, 13, '2026-09-17', '08:30:01', '18:19:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.82, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(477, 19, '2026-09-17', '08:30:45', '18:00:31', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(478, 26, '2026-09-17', '08:31:15', '18:00:51', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(479, 10, '2026-09-17', '08:32:40', '18:44:16', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 32, 0, 0, 0, 0, 10.18, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(480, 18, '2026-09-17', '08:32:56', '18:00:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.45, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:16'),
(481, 29, '2026-09-17', '08:39:42', '18:01:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, 9.35, NULL, NULL, '2026-09-17 03:34:25', '2026-10-06 11:46:17'),
(482, 5, '2026-09-17', '09:04:05', '17:31:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.45, NULL, NULL, '2026-09-17 06:04:21', '2026-10-06 11:46:16'),
(483, 25, '2026-09-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:04:22', '2026-09-17 06:04:22'),
(484, 27, '2026-09-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:04:22', '2026-09-17 06:04:22'),
(485, 12, '2026-09-17', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-17 06:05:09', '2026-09-17 06:05:09'),
(486, 11, '2026-09-17', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:05:09', '2026-09-17 06:05:09'),
(487, 36, '2026-09-17', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:05:51', '2026-09-17 06:05:51'),
(488, 38, '2026-09-17', '08:28:33', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:05:51', '2026-10-06 11:46:16'),
(489, 21, '2026-09-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:07:40', '2026-09-17 06:07:40'),
(490, 12, '2026-09-16', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-17 06:09:22', '2026-09-17 06:09:22'),
(491, 11, '2026-09-16', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:09:22', '2026-09-17 06:09:22'),
(492, 36, '2026-09-16', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:11:17', '2026-09-17 06:11:17'),
(493, 37, '2026-09-16', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-17 06:11:17', '2026-09-17 06:11:17'),
(494, 38, '2026-09-16', '08:30:00', '19:05:03', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 10.58, NULL, NULL, '2026-09-17 06:11:17', '2026-10-06 11:46:16'),
(495, 4, '2026-09-16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:11:17', '2026-09-17 06:11:17'),
(496, 36, '2026-09-15', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:28:52', '2026-09-17 06:28:52'),
(497, 37, '2026-09-15', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-17 06:28:52', '2026-09-17 06:28:52'),
(498, 38, '2026-09-15', '08:29:40', '18:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:28:52', '2026-10-06 11:46:15'),
(499, 36, '2026-09-14', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:29:25', '2026-09-17 06:29:25'),
(500, 37, '2026-09-14', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-17 06:29:25', '2026-09-17 06:29:25'),
(501, 36, '2026-09-13', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:30:04', '2026-09-17 06:30:04'),
(502, 37, '2026-09-13', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:30:04', '2026-09-17 06:30:04'),
(503, 38, '2026-09-13', '10:05:49', '17:59:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 95, 0, 0, 1, 0, 7.88, NULL, NULL, '2026-09-17 06:30:04', '2026-10-06 11:46:14'),
(504, 36, '2026-09-12', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-17 06:30:29', '2026-09-17 06:30:29'),
(505, 37, '2026-09-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:30:29', '2026-09-17 06:30:29'),
(506, 38, '2026-09-12', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:30:29', '2026-09-17 06:30:29'),
(507, 38, '2026-09-14', '08:30:53', '18:00:42', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-17 06:31:12', '2026-10-06 11:46:14'),
(508, 37, '2026-09-17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-17 06:32:29', '2026-09-17 06:32:29'),
(509, 30, '2026-09-17', '08:50:22', '18:00:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 20, 0, 0, 0, 0, 9.17, NULL, NULL, '2026-09-19 10:37:15', '2026-10-06 11:46:17'),
(510, 9, '2026-09-17', '08:59:18', '19:20:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.35, NULL, NULL, '2026-09-19 10:37:15', '2026-10-06 11:46:17'),
(511, 3, '2026-09-17', '09:04:14', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:15', '2026-10-06 11:46:16'),
(512, 6, '2026-09-17', '09:05:38', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 35, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:15', '2026-10-06 11:46:16'),
(513, 33, '2026-09-17', '18:45:26', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 315, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(514, 7, '2026-09-18', '08:13:41', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 43, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(515, 28, '2026-09-18', '08:30:39', '13:15:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 284, 4.73, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(516, 21, '2026-09-18', '08:30:47', '13:15:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 284, 4.73, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(517, 29, '2026-09-18', '08:30:55', '13:15:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 284, 4.73, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(518, 18, '2026-09-18', '08:32:12', '13:15:46', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 284, 4.72, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(519, 8, '2026-09-18', '08:35:44', '21:26:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 5, 0, 0, 0, 0, 12.83, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(520, 4, '2026-09-18', '08:50:53', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 20, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(521, 7, '2026-09-19', '07:39:43', '21:59:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, 14.32, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(522, 14, '2026-09-19', '08:02:04', '19:15:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 11.22, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(523, 2, '2026-09-19', '08:16:59', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 13, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(524, 4, '2026-09-19', '08:17:46', '19:12:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 0, 0, 10.90, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(525, 20, '2026-09-19', '08:19:16', '19:11:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 10, 0, 0, 10.87, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(526, 10, '2026-09-19', '08:22:48', '18:40:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 22, 0, 0, 0, 0, 10.28, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(527, 19, '2026-09-19', '08:28:29', '17:59:58', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 1, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(528, 1, '2026-09-19', '08:28:34', '17:59:35', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 1, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(529, 17, '2026-09-19', '08:28:40', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(530, 21, '2026-09-19', '08:28:56', '18:00:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(531, 30, '2026-09-19', '08:29:00', '18:00:01', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(532, 26, '2026-09-19', '08:29:16', '18:00:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(533, 28, '2026-09-19', '08:29:22', '18:00:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(534, 24, '2026-09-19', '08:29:35', '18:00:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(535, 23, '2026-09-19', '08:29:43', '18:01:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(536, 22, '2026-09-19', '08:29:48', '18:00:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(537, 16, '2026-09-19', '08:29:58', '17:59:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.48, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(538, 31, '2026-09-19', '08:30:02', '17:59:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(539, 13, '2026-09-19', '08:30:07', '18:02:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.53, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(540, 18, '2026-09-19', '08:30:11', '18:00:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(541, 32, '2026-09-19', '08:31:15', '18:01:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(542, 15, '2026-09-19', '08:35:56', '19:11:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 24, 0, 0, 10.60, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:18'),
(543, 9, '2026-09-19', '08:40:40', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 19, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(544, 6, '2026-09-19', '08:45:02', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 15, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(545, 3, '2026-09-19', '08:56:38', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 3, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(546, 33, '2026-09-19', '13:06:23', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 23, 0, 0, NULL, NULL, NULL, '2026-09-19 10:37:16', '2026-10-06 11:46:17'),
(547, 36, '2026-09-19', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-19 10:40:55', '2026-09-19 10:40:55'),
(548, 37, '2026-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:40:55', '2026-09-19 10:40:55'),
(549, 38, '2026-09-19', '08:29:08', '18:01:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.53, NULL, NULL, '2026-09-19 10:40:55', '2026-10-06 11:46:18'),
(550, 12, '2026-09-19', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-19 10:42:00', '2026-09-19 10:42:00'),
(551, 11, '2026-09-19', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:42:00', '2026-09-19 10:42:00'),
(552, 8, '2026-09-19', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:42:00', '2026-09-19 10:42:00'),
(553, 5, '2026-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:42:51', '2026-09-19 10:42:51'),
(554, 25, '2026-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:42:51', '2026-09-19 10:42:51'),
(555, 27, '2026-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:42:51', '2026-09-19 10:42:51'),
(556, 29, '2026-09-19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-19 10:45:30', '2026-09-19 10:45:30'),
(557, 7, '2026-09-20', '07:33:09', '21:27:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 13.90, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(558, 2, '2026-09-20', '08:17:54', '18:08:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 1, 21, 9.83, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(559, 20, '2026-09-20', '08:29:12', '18:01:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.53, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(560, 1, '2026-09-20', '08:29:35', '17:57:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 2, 9.45, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(561, 10, '2026-09-20', '08:30:02', '16:37:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 30, 0, 0, 1, 112, 8.12, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(562, 21, '2026-09-20', '08:30:11', '18:01:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(563, 31, '2026-09-20', '08:30:44', '18:00:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(564, 24, '2026-09-20', '08:30:52', '18:00:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(565, 28, '2026-09-20', '08:31:14', '18:00:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(566, 19, '2026-09-20', '08:31:18', '18:00:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(567, 22, '2026-09-20', '08:31:22', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(568, 26, '2026-09-20', '08:31:30', '18:00:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(569, 29, '2026-09-20', '08:31:35', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(570, 30, '2026-09-20', '08:31:38', '18:00:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(571, 25, '2026-09-20', '08:31:47', '18:01:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(572, 23, '2026-09-20', '08:31:55', '18:01:36', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(573, 32, '2026-09-20', '08:32:03', '18:02:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(574, 17, '2026-09-20', '08:41:42', '18:01:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 9.32, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(575, 9, '2026-09-20', '08:51:29', '18:08:51', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 8, 0, 0, 9.28, NULL, NULL, '2026-09-20 03:46:47', '2026-10-06 11:46:18'),
(576, 3, '2026-09-20', '09:05:54', '17:31:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 5, 0, 0, 0, 0, 8.42, NULL, NULL, '2026-09-20 04:24:28', '2026-10-06 11:46:18'),
(577, 4, '2026-09-20', '09:06:16', '18:07:43', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 36, 0, 0, 1, 22, 9.02, NULL, NULL, '2026-09-20 04:24:28', '2026-10-06 11:46:18'),
(578, 15, '2026-09-20', '09:08:17', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 8, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-20 04:24:28', '2026-10-06 11:46:18'),
(579, 8, '2026-09-20', '09:29:55', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 59, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-20 04:24:28', '2026-10-06 11:46:18'),
(580, 27, '2026-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:32:19', '2026-09-20 04:32:19'),
(581, 36, '2026-09-20', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-20 04:32:19', '2026-09-20 04:32:19'),
(582, 18, '2026-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:33:42', '2026-09-20 04:33:42'),
(583, 38, '2026-09-20', '08:30:00', NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, '08:30:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:33:42', '2026-10-06 11:46:18'),
(584, 16, '2026-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:33:42', '2026-09-20 04:33:42'),
(585, 14, '2026-09-20', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-20 04:33:42', '2026-10-06 11:46:18'),
(586, 13, '2026-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:33:42', '2026-09-20 04:33:42'),
(587, 12, '2026-09-20', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-20 04:33:42', '2026-09-20 04:33:42'),
(588, 11, '2026-09-20', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:33:42', '2026-09-20 04:33:42'),
(589, 6, '2026-09-20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-20 04:34:28', '2026-09-20 04:34:28'),
(590, 5, '2026-09-20', '09:42:29', '17:33:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 42, 0, 0, 0, 0, 7.83, NULL, NULL, '2026-09-20 13:01:06', '2026-10-06 11:46:18'),
(591, 33, '2026-09-20', '11:14:58', '18:04:27', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 135, 1, 25, 6.82, NULL, NULL, '2026-09-20 13:01:06', '2026-10-06 11:46:18'),
(592, 7, '2026-09-21', '07:19:45', '21:32:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 10, 0, 0, 14.20, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(593, 14, '2026-09-21', '08:08:23', '18:12:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 8, 0, 0, 1, 47, 10.07, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(594, 2, '2026-09-21', '08:18:04', '18:12:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 11, 1, 17, 9.90, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(595, 1, '2026-09-21', '08:25:33', '17:58:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 1, 1, 9.53, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:18'),
(596, 16, '2026-09-21', '08:29:18', '17:59:41', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:18'),
(597, 18, '2026-09-21', '08:29:22', '18:00:01', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(598, 31, '2026-09-21', '08:29:25', '17:59:50', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(599, 26, '2026-09-21', '08:29:29', '18:00:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(600, 22, '2026-09-21', '08:29:33', '18:00:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(601, 21, '2026-09-21', '08:29:42', '18:00:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(602, 17, '2026-09-21', '08:29:46', '18:06:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.60, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(603, 19, '2026-09-21', '08:29:49', '17:59:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(604, 32, '2026-09-21', '08:29:57', '18:01:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(605, 24, '2026-09-21', '08:30:01', '18:00:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(606, 13, '2026-09-21', '08:30:06', '18:01:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-21 03:45:04', '2026-10-06 11:46:19'),
(607, 4, '2026-09-21', '08:30:14', '18:01:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 28, 9.52, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(608, 29, '2026-09-21', '08:30:18', '18:00:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(609, 23, '2026-09-21', '08:30:27', '18:00:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(610, 25, '2026-09-21', '08:30:35', '18:00:46', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(611, 28, '2026-09-21', '08:31:34', '18:00:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(612, 20, '2026-09-21', '08:45:21', '18:03:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 15, 0, 0, 0, 0, 9.30, NULL, NULL, '2026-09-21 03:45:05', '2026-10-06 11:46:19'),
(613, 30, '2026-09-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-21 04:26:53', '2026-09-21 04:26:53'),
(614, 27, '2026-09-21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-21 04:26:53', '2026-09-21 04:26:53'),
(615, 36, '2026-09-21', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 04:26:53', '2026-09-21 04:26:53'),
(616, 38, '2026-09-21', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-21 04:28:26', '2026-09-21 04:28:26'),
(617, 12, '2026-09-21', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-21 04:28:26', '2026-09-21 04:28:26'),
(618, 11, '2026-09-21', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-21 04:28:26', '2026-09-21 04:28:26'),
(619, 9, '2026-09-21', '08:58:03', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:18'),
(620, 3, '2026-09-21', '09:02:24', '17:35:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.53, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:18'),
(621, 15, '2026-09-21', '09:02:31', '18:03:17', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(622, 5, '2026-09-21', '09:04:58', '17:36:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.52, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:18'),
(623, 6, '2026-09-21', '09:16:49', '18:03:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 46, 0, 0, 0, 0, 8.77, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(624, 33, '2026-09-21', '13:17:46', '18:03:27', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 1, 26, 4.75, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(625, 8, '2026-09-21', '21:38:18', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 720, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(626, 7, '2026-09-22', '07:46:47', '21:10:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 16, 0, 0, 0, 0, 13.38, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(627, 14, '2026-09-22', '08:02:26', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(628, 10, '2026-09-22', '08:09:42', '18:25:35', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 1, 4, 10.25, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(629, 2, '2026-09-22', '08:16:19', '18:05:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 13, 1, 24, 9.82, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(630, 4, '2026-09-22', '08:23:11', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(631, 20, '2026-09-22', '08:23:43', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(632, 38, '2026-09-22', '08:26:14', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 3, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(633, 1, '2026-09-22', '08:30:11', '17:58:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 1, 9.47, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(634, 26, '2026-09-22', '08:30:24', '18:00:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(635, 36, '2026-09-22', '08:30:32', '18:00:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(636, 17, '2026-09-22', '08:30:37', '18:01:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(637, 21, '2026-09-22', '08:30:45', '18:00:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(638, 19, '2026-09-22', '08:30:49', '18:01:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(639, 13, '2026-09-22', '08:31:01', '18:03:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.53, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(640, 22, '2026-09-22', '08:31:05', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(641, 31, '2026-09-22', '08:31:09', '18:00:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(642, 28, '2026-09-22', '08:31:16', '18:00:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(643, 29, '2026-09-22', '08:31:20', '18:00:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(644, 16, '2026-09-22', '08:31:23', '18:00:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(645, 23, '2026-09-22', '08:31:32', '18:00:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(646, 24, '2026-09-22', '08:31:35', '18:00:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(647, 30, '2026-09-22', '08:31:37', '18:01:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(648, 32, '2026-09-22', '08:31:45', '18:01:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:25', '2026-10-06 11:46:19'),
(649, 18, '2026-09-22', '08:31:59', '18:00:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(650, 25, '2026-09-22', '08:32:14', '18:01:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(651, 15, '2026-09-22', '08:35:46', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 24, 0, 0, NULL, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(652, 6, '2026-09-22', '08:57:29', '18:01:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 27, 0, 0, 0, 0, 9.05, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(653, 9, '2026-09-22', '08:58:42', '18:03:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.07, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(654, 27, '2026-09-22', '09:04:12', '17:11:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 34, 0, 0, 1, 48, 8.10, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(655, 3, '2026-09-22', '09:09:07', '17:30:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, 8.35, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(656, 5, '2026-09-22', '09:10:26', '17:31:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 10, 0, 0, 0, 0, 8.33, NULL, NULL, '2026-09-22 04:16:26', '2026-10-06 11:46:19'),
(657, 8, '2026-09-22', '08:30:00', '21:42:18', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 13.20, NULL, NULL, '2026-09-22 04:17:39', '2026-10-06 11:46:20'),
(658, 12, '2026-09-22', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-22 04:22:06', '2026-09-22 04:22:06'),
(659, 11, '2026-09-22', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-22 04:22:06', '2026-09-22 04:22:06'),
(660, 33, '2026-09-22', '13:19:18', '18:02:41', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 10, 1, 27, 4.72, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:19'),
(661, 14, '2026-09-23', '07:55:13', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, NULL, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(662, 10, '2026-09-23', '08:12:28', '18:54:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 12, 0, 0, 0, 0, 10.70, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(663, 2, '2026-09-23', '08:17:11', '18:53:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 0, 0, 10.58, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(664, 1, '2026-09-23', '08:25:14', '18:00:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.57, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(665, 7, '2026-09-23', '08:26:43', '21:33:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 56, 0, 0, 0, 0, 13.12, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(666, 18, '2026-09-23', '08:30:13', '18:00:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(667, 31, '2026-09-23', '08:30:19', '18:00:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(668, 22, '2026-09-23', '08:30:21', '18:00:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-23 08:14:12', '2026-10-06 11:46:20'),
(669, 13, '2026-09-23', '08:30:28', '18:05:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.57, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(670, 26, '2026-09-23', '08:30:32', '18:00:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20');
INSERT INTO `hr_attendances` (`id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `check_in_photo`, `check_in_latitude`, `check_in_longitude`, `check_in_location`, `check_out_photo`, `check_out_latitude`, `check_out_longitude`, `check_out_location`, `clock_in`, `clock_out`, `status`, `is_late`, `late_minutes`, `is_early_in`, `early_in_minutes`, `is_early_leave`, `early_leave_minutes`, `total_hours`, `notes`, `device_id`, `created_at`, `updated_at`) VALUES
(671, 16, '2026-09-23', '08:30:38', '18:00:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(672, 23, '2026-09-23', '08:30:42', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(673, 21, '2026-09-23', '08:30:51', '18:00:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(674, 36, '2026-09-23', '08:30:59', '18:00:36', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(675, 32, '2026-09-23', '08:31:08', '18:03:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.53, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(676, 30, '2026-09-23', '08:31:43', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(677, 27, '2026-09-23', '08:31:51', '18:01:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(678, 17, '2026-09-23', '08:31:59', '18:01:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(679, 25, '2026-09-23', '08:32:03', '18:01:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(680, 29, '2026-09-23', '08:35:48', '18:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 5, 0, 0, 0, 0, 9.40, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(681, 19, '2026-09-23', '08:41:42', '18:02:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 9.33, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(682, 20, '2026-09-23', '08:41:58', '18:00:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 9.30, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(683, 28, '2026-09-23', '08:42:03', '18:04:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 12, 0, 0, 0, 0, 9.37, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(684, 9, '2026-09-23', '08:48:24', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 11, 0, 0, NULL, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(685, 24, '2026-09-23', '08:52:58', '18:00:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 22, 0, 0, 0, 0, 9.12, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(686, 3, '2026-09-23', '09:05:27', '17:31:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 5, 0, 0, 0, 0, 8.43, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(687, 5, '2026-09-23', '09:06:48', '17:32:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 8.42, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(688, 6, '2026-09-23', '09:10:28', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 40, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(689, 33, '2026-09-23', '13:09:44', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 20, 0, 0, NULL, NULL, NULL, '2026-09-23 08:14:13', '2026-10-06 11:46:20'),
(690, 15, '2026-09-23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-23 08:14:39', '2026-09-23 08:14:39'),
(691, 38, '2026-09-23', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-23 08:15:39', '2026-09-23 08:15:39'),
(692, 12, '2026-09-23', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-23 08:15:39', '2026-09-23 08:15:39'),
(693, 11, '2026-09-23', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-23 08:15:57', '2026-09-23 08:15:57'),
(694, 8, '2026-09-23', '08:30:00', '22:04:24', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 13.57, NULL, NULL, '2026-09-23 08:20:49', '2026-10-06 11:46:20'),
(695, 8, '2026-09-24', '07:11:46', '21:43:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 78, 0, 0, 14.52, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(696, 14, '2026-09-24', '08:06:51', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:20'),
(697, 10, '2026-09-24', '08:08:06', '18:51:43', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 8, 0, 0, 0, 0, 10.72, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(698, 2, '2026-09-24', '08:17:29', '19:37:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 0, 0, 11.33, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(699, 4, '2026-09-24', '08:19:41', '19:34:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 10, 0, 0, 11.23, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(700, 20, '2026-09-24', '08:21:30', '19:07:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 8, 0, 0, 10.77, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(701, 1, '2026-09-24', '08:29:28', '17:58:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 1, 9.47, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(702, 16, '2026-09-24', '08:30:46', '18:00:16', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(703, 26, '2026-09-24', '08:30:50', '18:01:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(704, 18, '2026-09-24', '08:30:54', '18:00:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(705, 31, '2026-09-24', '08:30:58', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(706, 21, '2026-09-24', '08:31:04', '18:01:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(707, 36, '2026-09-24', '08:31:09', '18:01:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(708, 22, '2026-09-24', '08:31:14', '18:00:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(709, 19, '2026-09-24', '08:31:20', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:20'),
(710, 28, '2026-09-24', '08:31:25', '18:00:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(711, 32, '2026-09-24', '08:31:33', '18:00:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(712, 23, '2026-09-24', '08:31:38', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(713, 29, '2026-09-24', '08:31:43', '18:00:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(714, 30, '2026-09-24', '08:31:52', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:20'),
(715, 25, '2026-09-24', '08:32:00', '18:08:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.60, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(716, 13, '2026-09-24', '08:32:18', '18:01:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(717, 17, '2026-09-24', '08:39:34', '18:01:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, 9.35, NULL, NULL, '2026-09-24 04:09:02', '2026-10-06 11:46:21'),
(718, 15, '2026-09-24', '08:46:05', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 13, 0, 0, 0.00, NULL, NULL, '2026-09-24 04:09:03', '2026-10-06 11:46:20'),
(719, 9, '2026-09-24', '08:55:30', '18:11:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.25, NULL, NULL, '2026-09-24 04:09:03', '2026-10-06 11:46:21'),
(720, 3, '2026-09-24', '09:02:54', '17:33:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-24 04:09:03', '2026-10-06 11:46:21'),
(721, 5, '2026-09-24', '09:03:36', '17:32:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.48, NULL, NULL, '2026-09-24 04:09:03', '2026-10-06 11:46:21'),
(722, 24, '2026-09-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-24 04:09:35', '2026-09-24 04:09:35'),
(723, 27, '2026-09-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-24 04:09:35', '2026-09-24 04:09:35'),
(724, 38, '2026-09-24', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-24 04:10:20', '2026-09-24 04:10:20'),
(725, 12, '2026-09-24', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-24 04:10:20', '2026-09-24 04:10:20'),
(726, 11, '2026-09-24', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-24 04:10:20', '2026-09-24 04:10:20'),
(727, 7, '2026-09-24', '07:30:00', '21:19:24', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '07:30:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 13.82, NULL, NULL, '2026-09-24 04:10:54', '2026-10-06 11:46:21'),
(728, 6, '2026-09-24', '09:46:32', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 76, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:20'),
(729, 33, '2026-09-24', '13:16:34', '18:56:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 13, 0, 0, 5.65, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(730, 7, '2026-09-25', '08:14:35', '20:50:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 44, 0, 0, 1, 9, 12.58, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(731, 10, '2026-09-25', '08:14:45', '10:48:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 14, 0, 0, 1, 461, 2.55, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(732, 9, '2026-09-25', '08:47:55', '14:40:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 1, 199, 5.87, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(733, 17, '2026-09-25', '09:21:32', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 51, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(734, 8, '2026-09-26', '06:12:07', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 137, 0, 0, NULL, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(735, 7, '2026-09-26', '07:52:55', '20:21:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 22, 0, 0, 1, 38, 12.47, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:22'),
(736, 20, '2026-09-26', '08:13:57', '18:13:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 16, 0, 0, 9.98, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:22'),
(737, 4, '2026-09-26', '08:17:18', '18:28:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 1, 1, 10.18, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:22'),
(738, 10, '2026-09-26', '08:19:11', '18:28:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 19, 0, 0, 1, 1, 10.15, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:22'),
(739, 1, '2026-09-26', '08:26:38', '17:59:50', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 3, 1, 0, 9.55, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(740, 18, '2026-09-26', '08:29:07', '18:00:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(741, 31, '2026-09-26', '08:29:15', '18:00:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(742, 19, '2026-09-26', '08:29:19', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(743, 16, '2026-09-26', '08:29:22', '18:00:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(744, 26, '2026-09-26', '08:29:26', '18:00:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(745, 22, '2026-09-26', '08:29:30', '18:00:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(746, 36, '2026-09-26', '08:29:35', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(747, 24, '2026-09-26', '08:29:40', '18:00:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(748, 23, '2026-09-26', '08:29:45', '18:00:51', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(749, 21, '2026-09-26', '08:29:48', '18:01:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(750, 28, '2026-09-26', '08:29:53', '18:00:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(751, 29, '2026-09-26', '08:30:02', '18:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(752, 25, '2026-09-26', '08:30:39', '18:01:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:18', '2026-10-06 11:46:21'),
(753, 17, '2026-09-26', '08:30:56', '18:01:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(754, 30, '2026-09-26', '08:31:06', '18:00:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(755, 13, '2026-09-26', '08:33:27', '18:03:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(756, 9, '2026-09-26', '08:34:50', '18:01:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 25, 0, 0, 9.43, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(757, 5, '2026-09-26', '09:11:29', '17:33:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 8.37, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(758, 3, '2026-09-26', '09:11:43', '17:32:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 8.33, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(759, 33, '2026-09-26', '14:19:28', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 49, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:21'),
(760, 7, '2026-09-27', '07:49:53', '20:27:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 19, 0, 0, 1, 32, 12.62, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(761, 20, '2026-09-27', '08:16:17', '15:57:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 13, 1, 122, 7.68, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(762, 4, '2026-09-27', '08:17:11', '18:16:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 1, 13, 9.98, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(763, 2, '2026-09-27', '08:21:05', '18:18:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 8, 1, 11, 9.95, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(764, 10, '2026-09-27', '08:27:24', '18:31:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 27, 0, 0, 0, 0, 10.05, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(765, 1, '2026-09-27', '08:29:34', '17:52:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 7, 9.37, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(766, 19, '2026-09-27', '08:30:09', '18:00:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(767, 36, '2026-09-27', '08:30:18', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(768, 18, '2026-09-27', '08:30:26', '17:59:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(769, 22, '2026-09-27', '08:30:31', '18:00:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(770, 16, '2026-09-27', '08:30:35', '18:00:00', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(771, 13, '2026-09-27', '08:30:39', '18:01:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(772, 30, '2026-09-27', '08:30:41', '18:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(773, 23, '2026-09-27', '08:30:44', '18:00:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(774, 31, '2026-09-27', '08:30:48', '18:00:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(775, 28, '2026-09-27', '08:30:52', '18:01:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(776, 32, '2026-09-27', '08:31:07', '18:01:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(777, 26, '2026-09-27', '08:31:16', '18:00:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(778, 21, '2026-09-27', '08:31:26', '18:00:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(779, 29, '2026-09-27', '08:31:32', '18:00:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(780, 25, '2026-09-27', '08:31:40', '18:01:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(781, 24, '2026-09-27', '08:31:46', '18:00:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(782, 17, '2026-09-27', '08:36:05', '18:01:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 9.42, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(783, 15, '2026-09-27', '08:53:39', '18:16:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, 9.37, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(784, 38, '2026-09-27', '09:12:42', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 42, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(785, 3, '2026-09-27', '09:13:40', '17:37:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 13, 0, 0, 0, 0, 8.40, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(786, 5, '2026-09-27', '09:13:51', '17:37:50', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 13, 0, 0, 0, 0, 8.38, NULL, NULL, '2026-09-27 04:20:19', '2026-10-06 11:46:22'),
(787, 27, '2026-09-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 04:20:41', '2026-09-27 04:20:41'),
(788, 14, '2026-09-27', '08:00:00', NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, '08:00:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 04:21:19', '2026-09-27 04:21:19'),
(789, 12, '2026-09-27', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-27 04:21:19', '2026-09-27 04:21:19'),
(790, 11, '2026-09-27', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 04:21:20', '2026-09-27 04:21:20'),
(791, 8, '2026-09-27', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 04:21:47', '2026-09-27 04:21:47'),
(792, 6, '2026-09-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 04:21:47', '2026-09-27 04:21:47'),
(793, 9, '2026-09-27', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-27 07:27:04', '2026-09-27 07:27:04'),
(794, 33, '2026-09-27', '18:00:16', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 270, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:22'),
(795, 7, '2026-09-28', '07:10:41', '20:49:20', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 19, 1, 10, 13.63, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(796, 14, '2026-09-28', '07:55:05', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, NULL, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:22'),
(797, 2, '2026-09-28', '08:14:46', '18:10:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 15, 1, 19, 9.92, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(798, 38, '2026-09-28', '08:21:50', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 8, 0, 0, NULL, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:22'),
(799, 10, '2026-09-28', '08:24:03', '18:20:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 24, 0, 0, 1, 9, 9.93, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(800, 1, '2026-09-28', '08:27:35', '17:57:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 2, 1, 2, 9.48, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(801, 21, '2026-09-28', '08:28:38', '18:01:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.53, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(802, 24, '2026-09-28', '08:28:46', '18:00:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(803, 17, '2026-09-28', '08:28:49', '18:00:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:46', '2026-10-06 11:46:23'),
(804, 29, '2026-09-28', '08:28:53', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(805, 36, '2026-09-28', '08:29:01', '18:00:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(806, 31, '2026-09-28', '08:29:06', '17:59:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 1, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(807, 26, '2026-09-28', '08:29:10', '18:00:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(808, 28, '2026-09-28', '08:29:19', '18:01:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.53, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(809, 30, '2026-09-28', '08:29:21', '18:03:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.55, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(810, 23, '2026-09-28', '08:29:30', '18:00:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(811, 19, '2026-09-28', '08:29:40', '18:00:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(812, 25, '2026-09-28', '08:30:03', '18:01:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(813, 18, '2026-09-28', '08:30:08', '18:00:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(814, 22, '2026-09-28', '08:30:14', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(815, 16, '2026-09-28', '08:30:19', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(816, 32, '2026-09-28', '08:30:35', '18:00:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(817, 9, '2026-09-28', '08:39:51', '18:05:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 20, 0, 0, 9.42, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(818, 15, '2026-09-28', '08:54:29', '18:06:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, 9.20, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(819, 3, '2026-09-28', '09:02:31', '17:30:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.47, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(820, 5, '2026-09-28', '09:02:46', '17:32:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.48, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(821, 4, '2026-09-28', '09:06:47', '18:06:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 36, 0, 0, 1, 23, 9.00, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(822, 8, '2026-09-28', '09:34:38', '22:34:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 64, 0, 0, 0, 0, 13.00, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(823, 6, '2026-09-28', '09:46:32', '18:01:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 76, 0, 0, 0, 0, 8.25, NULL, NULL, '2026-09-28 05:32:47', '2026-10-06 11:46:23'),
(824, 20, '2026-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-28 05:34:20', '2026-09-28 05:34:20'),
(825, 27, '2026-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-28 05:34:20', '2026-09-28 05:34:20'),
(826, 33, '2026-09-28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-28 05:34:58', '2026-09-28 05:34:58'),
(827, 12, '2026-09-28', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-28 05:34:58', '2026-09-28 05:34:58'),
(828, 11, '2026-09-28', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-28 05:34:58', '2026-09-28 05:34:58'),
(829, 7, '2026-09-29', '07:42:44', '21:41:35', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 12, 0, 0, 0, 0, 13.97, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(830, 14, '2026-09-29', '07:48:03', '18:44:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 11, 1, 15, 10.93, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(831, 2, '2026-09-29', '08:10:08', '18:42:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 19, 0, 0, 10.53, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(832, 20, '2026-09-29', '08:23:46', '18:46:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, 10.37, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(833, 1, '2026-09-29', '08:24:38', '18:00:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, 9.58, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(834, 17, '2026-09-29', '08:29:13', '18:40:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(835, 19, '2026-09-29', '08:29:17', '18:40:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(836, 31, '2026-09-29', '08:29:21', '18:40:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(837, 36, '2026-09-29', '08:29:25', '18:40:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.17, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(838, 26, '2026-09-29', '08:29:30', '18:41:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(839, 21, '2026-09-29', '08:29:34', '18:30:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.02, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(840, 24, '2026-09-29', '08:29:37', '18:31:57', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.03, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(841, 28, '2026-09-29', '08:29:46', '18:43:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.22, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(842, 29, '2026-09-29', '08:29:49', '18:30:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.00, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(843, 22, '2026-09-29', '08:29:53', '18:43:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 10.22, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(844, 23, '2026-09-29', '08:30:00', '18:42:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.20, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(845, 30, '2026-09-29', '08:30:03', '18:41:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(846, 32, '2026-09-29', '08:30:11', '18:41:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.18, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(847, 18, '2026-09-29', '08:30:14', '18:40:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.17, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(848, 16, '2026-09-29', '08:30:17', '18:40:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 10.15, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:24'),
(849, 25, '2026-09-29', '08:30:29', '12:26:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 333, 3.93, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(850, 10, '2026-09-29', '08:32:18', '18:32:35', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 32, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(851, 3, '2026-09-29', '08:59:22', '17:32:42', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 8.55, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(852, 5, '2026-09-29', '08:59:59', '17:34:01', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 8.57, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(853, 4, '2026-09-29', '09:02:07', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 32, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(854, 38, '2026-09-29', '09:13:22', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 43, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-29 04:53:48', '2026-10-06 11:46:23'),
(855, 15, '2026-09-29', '09:00:00', '18:46:39', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:00:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 9.77, NULL, NULL, '2026-09-29 04:55:36', '2026-10-06 11:46:24'),
(856, 9, '2026-09-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-29 04:55:36', '2026-09-29 04:55:36'),
(857, 27, '2026-09-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-29 04:55:36', '2026-09-29 04:55:36'),
(858, 13, '2026-09-29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-29 04:56:10', '2026-09-29 04:56:10'),
(859, 12, '2026-09-29', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-29 04:56:10', '2026-09-29 04:56:10'),
(860, 11, '2026-09-29', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-29 04:56:10', '2026-09-29 04:56:10'),
(861, 8, '2026-09-29', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-29 04:56:35', '2026-09-29 04:56:35'),
(862, 6, '2026-09-29', '10:17:59', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 107, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:23'),
(863, 33, '2026-09-29', '13:16:38', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 13, 0, 0, NULL, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:23'),
(864, 7, '2026-09-30', '07:54:13', '21:21:56', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 24, 0, 0, 0, 0, 13.45, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(865, 10, '2026-09-30', '08:14:43', '18:33:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 14, 0, 0, 0, 0, 10.32, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(866, 2, '2026-09-30', '08:17:48', '18:32:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 0, 0, 10.23, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(867, 1, '2026-09-30', '08:25:08', '18:00:17', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.58, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(868, 30, '2026-09-30', '08:25:14', '18:00:39', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.58, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(869, 17, '2026-09-30', '08:25:21', '18:01:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.60, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(870, 20, '2026-09-30', '08:28:28', '18:03:43', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.58, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(871, 19, '2026-09-30', '08:29:41', '18:00:51', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(872, 36, '2026-09-30', '08:30:09', '18:00:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(873, 22, '2026-09-30', '08:30:26', '18:00:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(874, 16, '2026-09-30', '08:30:29', '18:00:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(875, 23, '2026-09-30', '08:30:35', '18:01:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(876, 28, '2026-09-30', '08:30:45', '18:01:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(877, 21, '2026-09-30', '08:30:50', '18:01:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(878, 18, '2026-09-30', '08:30:53', '18:00:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(879, 31, '2026-09-30', '08:30:56', '18:00:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(880, 29, '2026-09-30', '08:31:02', '18:00:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(881, 32, '2026-09-30', '08:31:10', '18:00:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(882, 26, '2026-09-30', '08:31:25', '18:01:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(883, 25, '2026-09-30', '08:31:34', '18:01:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(884, 6, '2026-09-30', '08:55:33', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 25, 0, 0, 0, 0, NULL, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(885, 15, '2026-09-30', '08:55:43', '18:04:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.13, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(886, 9, '2026-09-30', '08:58:12', '18:04:17', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.10, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:25'),
(887, 3, '2026-09-30', '09:06:25', '15:35:46', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 1, 114, 6.48, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(888, 5, '2026-09-30', '09:06:33', '17:33:01', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 8.43, NULL, NULL, '2026-09-30 04:45:55', '2026-10-06 11:46:24'),
(889, 24, '2026-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:46:10', '2026-09-30 04:46:10'),
(890, 27, '2026-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:46:10', '2026-09-30 04:46:10'),
(891, 38, '2026-09-30', '08:30:00', NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, '08:30:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:46:57', '2026-09-30 04:46:57'),
(892, 14, '2026-09-30', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-09-30 04:46:57', '2026-10-06 11:46:25');
INSERT INTO `hr_attendances` (`id`, `employee_id`, `date`, `check_in_time`, `check_out_time`, `check_in_photo`, `check_in_latitude`, `check_in_longitude`, `check_in_location`, `check_out_photo`, `check_out_latitude`, `check_out_longitude`, `check_out_location`, `clock_in`, `clock_out`, `status`, `is_late`, `late_minutes`, `is_early_in`, `early_in_minutes`, `is_early_leave`, `early_leave_minutes`, `total_hours`, `notes`, `device_id`, `created_at`, `updated_at`) VALUES
(893, 13, '2026-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:46:57', '2026-09-30 04:46:57'),
(894, 12, '2026-09-30', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-09-30 04:46:57', '2026-09-30 04:46:57'),
(895, 11, '2026-09-30', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:46:57', '2026-09-30 04:46:57'),
(896, 8, '2026-09-30', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:47:21', '2026-09-30 04:47:21'),
(897, 4, '2026-09-30', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-09-30 04:47:21', '2026-09-30 04:47:21'),
(898, 40, '2026-09-14', '08:43:39', '17:59:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 13, 0, 0, 1, 0, 9.25, NULL, NULL, '2026-10-01 04:07:14', '2026-10-06 11:46:14'),
(899, 40, '2026-09-15', '08:39:04', '17:57:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 1, 2, 9.30, NULL, NULL, '2026-10-01 04:07:15', '2026-10-06 11:46:15'),
(900, 40, '2026-09-16', '08:41:01', '17:31:31', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 1, 28, 8.83, NULL, NULL, '2026-10-01 04:07:16', '2026-10-06 11:46:16'),
(901, 33, '2026-09-30', '18:02:53', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 272, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:24'),
(902, 7, '2026-10-01', '07:26:52', '21:21:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 3, 0, 0, 13.90, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(903, 2, '2026-10-01', '08:15:02', '18:19:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 14, 1, 10, 10.07, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(904, 10, '2026-10-01', '08:17:42', '18:21:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 17, 0, 0, 1, 8, 10.05, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(905, 20, '2026-10-01', '08:20:51', '18:04:59', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 9, 0, 0, 9.73, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(906, 1, '2026-10-01', '08:30:17', '17:58:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 1, 9.45, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(907, 22, '2026-10-01', '08:30:40', '18:00:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(908, 36, '2026-10-01', '08:30:45', '18:00:06', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(909, 18, '2026-10-01', '08:30:48', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(910, 26, '2026-10-01', '08:30:51', '18:00:36', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(911, 21, '2026-10-01', '08:30:56', '18:02:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(912, 30, '2026-10-01', '08:31:00', '18:00:40', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(913, 17, '2026-10-01', '08:31:03', '18:00:19', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(914, 23, '2026-10-01', '08:31:18', '18:00:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(915, 16, '2026-10-01', '08:31:21', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(916, 31, '2026-10-01', '08:31:25', '18:00:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(917, 19, '2026-10-01', '08:31:31', '18:00:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(918, 28, '2026-10-01', '08:31:52', '18:00:55', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(919, 32, '2026-10-01', '08:32:12', '18:01:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.47, NULL, NULL, '2026-10-01 04:07:22', '2026-10-06 11:46:25'),
(920, 29, '2026-10-01', '08:38:25', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 8, 0, 0, 0, 0, 9.37, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(921, 4, '2026-10-01', '08:43:02', '18:14:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 15, 9.52, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(922, 15, '2026-10-01', '08:51:47', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 8, 0, 0, NULL, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(923, 9, '2026-10-01', '08:55:11', '18:03:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, 9.13, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(924, 3, '2026-10-01', '09:01:47', '17:32:32', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(925, 5, '2026-10-01', '09:02:00', '17:32:41', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-01 04:07:23', '2026-10-06 11:46:25'),
(926, 25, '2026-10-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-01 04:12:53', '2026-10-01 04:12:53'),
(927, 27, '2026-10-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-01 04:12:53', '2026-10-01 04:12:53'),
(928, 14, '2026-10-01', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-10-01 04:14:02', '2026-10-01 04:14:02'),
(929, 13, '2026-10-01', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-01 04:14:02', '2026-10-01 04:14:02'),
(930, 12, '2026-10-01', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-01 04:14:02', '2026-10-01 04:14:02'),
(931, 11, '2026-10-01', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-01 04:14:02', '2026-10-01 04:14:02'),
(932, 8, '2026-10-01', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-01 04:14:02', '2026-10-01 04:14:02'),
(933, 40, '2026-10-01', '08:30:00', '18:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:30:00', 'present', 0, 0, 0, 0, 0, 0, 10.00, NULL, NULL, '2026-10-01 04:17:32', '2026-10-01 04:17:32'),
(934, 38, '2026-10-01', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-01 04:17:52', '2026-10-01 04:17:52'),
(935, 6, '2026-10-01', '10:30:45', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 120, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:25'),
(936, 33, '2026-10-01', '13:09:43', '18:04:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 20, 1, 25, 4.92, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:25'),
(937, 7, '2026-10-02', '07:32:30', '21:06:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 13.55, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:25'),
(938, 8, '2026-10-02', '21:23:21', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 300, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-16 12:44:11'),
(939, 7, '2026-10-03', '07:40:50', '21:29:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 10, 0, 0, 0, 0, 13.80, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(940, 10, '2026-10-03', '08:09:23', '18:37:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 9, 0, 0, 0, 0, 10.45, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(941, 2, '2026-10-03', '08:19:05', '18:37:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 10, 0, 0, 10.30, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(942, 4, '2026-10-03', '08:22:10', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 7, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:25'),
(943, 20, '2026-10-03', '08:26:18', '18:36:48', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 3, 0, 0, 10.17, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(944, 1, '2026-10-03', '08:28:01', '17:59:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 1, 0, 9.52, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(945, 22, '2026-10-03', '08:29:04', '18:04:00', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.57, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(946, 16, '2026-10-03', '08:29:12', '18:00:11', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(947, 18, '2026-10-03', '08:29:17', '18:00:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(948, 36, '2026-10-03', '08:29:21', '18:00:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(949, 24, '2026-10-03', '08:29:26', '18:00:17', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(950, 26, '2026-10-03', '08:29:30', '18:00:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(951, 31, '2026-10-03', '08:29:33', '18:00:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(952, 21, '2026-10-03', '08:29:36', '18:00:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(953, 23, '2026-10-03', '08:29:44', '18:00:49', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.52, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(954, 29, '2026-10-03', '08:29:53', '18:00:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(955, 28, '2026-10-03', '08:30:00', '18:01:00', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.52, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(956, 30, '2026-10-03', '08:30:03', '18:00:30', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(957, 25, '2026-10-03', '08:30:15', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(958, 17, '2026-10-03', '08:34:26', '18:08:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.55, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(959, 15, '2026-10-03', '08:50:26', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 9, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(960, 9, '2026-10-03', '08:54:41', '18:36:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 5, 0, 0, 9.68, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(961, 5, '2026-10-03', '09:06:15', '17:32:42', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 8.43, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(962, 3, '2026-10-03', '09:06:23', '17:32:26', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 8.43, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(963, 6, '2026-10-03', '09:15:14', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 45, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(964, 33, '2026-10-03', '13:28:07', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:26'),
(965, 8, '2026-10-03', '21:56:52', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 300, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:29', '2026-10-16 12:44:11'),
(966, 7, '2026-10-04', '07:20:36', '21:22:29', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 9, 0, 0, 14.02, NULL, NULL, '2026-10-04 04:05:29', '2026-10-06 11:46:27'),
(967, 20, '2026-10-04', '08:20:13', '18:26:08', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 9, 0, 0, 10.08, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(968, 2, '2026-10-04', '08:20:18', '18:29:38', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 9, 1, 0, 10.15, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(969, 1, '2026-10-04', '08:28:32', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:26'),
(970, 18, '2026-10-04', '08:29:42', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:26'),
(971, 16, '2026-10-04', '08:29:45', '18:00:02', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(972, 19, '2026-10-04', '08:29:53', '18:00:45', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(973, 36, '2026-10-04', '08:29:57', '18:00:05', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(974, 26, '2026-10-04', '08:30:03', '18:00:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(975, 28, '2026-10-04', '08:30:06', '18:00:15', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(976, 22, '2026-10-04', '08:30:12', '18:00:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(977, 24, '2026-10-04', '08:30:21', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:26'),
(978, 31, '2026-10-04', '08:30:38', '18:00:53', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(979, 17, '2026-10-04', '08:30:41', '18:00:31', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(980, 30, '2026-10-04', '08:30:45', '18:05:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.57, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(981, 29, '2026-10-04', '08:37:51', '18:00:10', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 7, 0, 0, 0, 0, 9.37, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(982, 9, '2026-10-04', '08:53:41', '18:01:47', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, 9.13, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(983, 5, '2026-10-04', '09:04:07', '17:33:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 8.48, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(984, 3, '2026-10-04', '09:04:16', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-04 04:05:30', '2026-10-06 11:46:27'),
(985, 15, '2026-10-04', '09:00:00', '18:01:39', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '09:00:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 9.02, NULL, NULL, '2026-10-04 04:05:47', '2026-10-06 11:46:27'),
(986, 32, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:06:12', '2026-10-04 04:06:12'),
(987, 21, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:06:12', '2026-10-04 04:06:12'),
(988, 25, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:06:12', '2026-10-04 04:06:12'),
(989, 27, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:06:12', '2026-10-04 04:06:12'),
(990, 14, '2026-10-04', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-10-04 04:08:59', '2026-10-06 11:46:27'),
(991, 13, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:08:59', '2026-10-04 04:08:59'),
(992, 12, '2026-10-04', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-04 04:08:59', '2026-10-04 04:08:59'),
(993, 11, '2026-10-04', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:08:59', '2026-10-04 04:08:59'),
(994, 10, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:08:59', '2026-10-04 04:08:59'),
(995, 8, '2026-10-04', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:08:59', '2026-10-04 04:08:59'),
(996, 38, '2026-10-04', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-04 04:09:35', '2026-10-04 04:09:35'),
(997, 23, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:09:35', '2026-10-04 04:09:35'),
(998, 40, '2026-10-04', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:09:35', '2026-10-04 04:09:35'),
(999, 6, '2026-10-04', '11:34:22', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 184, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-04 04:09:35', '2026-10-06 11:46:27'),
(1000, 4, '2026-10-04', '10:29:36', '18:25:34', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 119, 0, 0, 1, 4, 7.92, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1001, 33, '2026-10-04', '12:11:43', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 78, 0, 0, NULL, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1002, 7, '2026-10-05', '07:12:55', '21:37:46', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 17, 0, 0, 14.40, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1003, 10, '2026-10-05', '08:11:13', '18:44:24', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 11, 0, 0, 0, 0, 10.55, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1004, 20, '2026-10-05', '08:17:38', '18:22:58', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 12, 0, 0, 10.08, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1005, 2, '2026-10-05', '08:18:24', '18:34:43', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 11, 0, 0, 10.27, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1006, 4, '2026-10-05', '08:18:26', '18:14:04', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 11, 1, 15, 9.92, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1007, 1, '2026-10-05', '08:28:45', '18:00:09', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, 9.52, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1008, 31, '2026-10-05', '08:30:03', '17:59:03', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 1, 0, 9.48, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1009, 26, '2026-10-05', '08:30:07', '18:00:52', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1010, 22, '2026-10-05', '08:30:17', '18:00:25', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1011, 17, '2026-10-05', '08:30:22', '18:11:28', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.68, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:28'),
(1012, 24, '2026-10-05', '08:30:26', '18:00:18', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1013, 28, '2026-10-05', '08:30:34', '18:00:44', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1014, 18, '2026-10-05', '08:30:43', '18:00:12', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1015, 16, '2026-10-05', '08:30:57', '18:00:22', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1016, 21, '2026-10-05', '08:31:14', '18:00:37', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.48, NULL, NULL, '2026-10-05 03:32:10', '2026-10-06 11:46:27'),
(1017, 19, '2026-10-05', '08:40:17', '18:00:33', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 10, 0, 0, 0, 0, 9.33, NULL, NULL, '2026-10-05 03:43:22', '2026-10-06 11:46:27'),
(1018, 30, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 03:45:55', '2026-10-05 03:45:55'),
(1019, 36, '2026-10-05', '08:30:00', '18:00:56', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Biometric Device', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 03:45:55', '2026-10-06 11:46:28'),
(1020, 40, '2026-10-05', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 03:45:55', '2026-10-05 03:45:55'),
(1021, 13, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 03:45:55', '2026-10-05 03:45:55'),
(1022, 11, '2026-10-05', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 03:45:55', '2026-10-05 03:45:55'),
(1023, 8, '2026-10-05', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 03:46:54', '2026-10-05 03:46:54'),
(1024, 23, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 03:46:54', '2026-10-05 03:46:54'),
(1025, 9, '2026-10-05', '08:53:12', '18:01:13', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 6, 0, 0, 9.13, NULL, NULL, '2026-10-05 04:17:03', '2026-10-06 11:46:28'),
(1026, 15, '2026-10-05', '09:00:32', '18:01:21', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, 9.00, NULL, NULL, '2026-10-05 04:17:03', '2026-10-06 11:46:28'),
(1027, 5, '2026-10-05', '09:06:49', '17:32:54', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 6, 0, 0, 0, 0, 8.43, NULL, NULL, '2026-10-05 04:17:03', '2026-10-06 11:46:27'),
(1028, 3, '2026-10-05', '09:07:11', '17:31:23', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 7, 0, 0, 0, 0, 8.40, NULL, NULL, '2026-10-05 04:17:03', '2026-10-06 11:46:27'),
(1029, 6, '2026-10-05', '09:13:16', '18:44:14', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'late', 1, 43, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-05 04:17:03', '2026-10-06 11:46:28'),
(1030, 25, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 04:24:16', '2026-10-05 04:24:16'),
(1031, 38, '2026-10-05', '08:45:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:45:00', '18:00:00', 'late', 1, 15, 0, 0, 0, 0, 9.25, NULL, NULL, '2026-10-05 04:24:16', '2026-10-05 04:24:16'),
(1032, 32, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 04:25:03', '2026-10-05 04:30:21'),
(1033, 14, '2026-10-05', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-10-05 04:25:03', '2026-10-05 04:25:03'),
(1034, 12, '2026-10-05', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-05 04:25:03', '2026-10-05 04:25:03'),
(1035, 29, '2026-10-05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-05 04:34:22', '2026-10-05 04:34:22'),
(1036, 33, '2026-10-05', '13:18:35', '18:44:07', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, 'Biometric Device', NULL, NULL, 'present', 0, 0, 1, 11, 0, 0, 5.42, NULL, NULL, '2026-10-06 03:32:04', '2026-10-06 11:46:28'),
(1037, 7, '2026-10-06', '07:25:41', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 4, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:04', '2026-10-06 03:32:04'),
(1038, 2, '2026-10-06', '08:15:18', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 14, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:04', '2026-10-06 03:32:04'),
(1039, 10, '2026-10-06', '08:22:33', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 22, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:04', '2026-10-06 03:32:04'),
(1040, 20, '2026-10-06', '08:22:43', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 7, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:04', '2026-10-06 03:32:04'),
(1041, 4, '2026-10-06', '08:27:47', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 2, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1042, 1, '2026-10-06', '08:28:22', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 1, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1043, 36, '2026-10-06', '08:29:28', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1044, 26, '2026-10-06', '08:29:46', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1045, 22, '2026-10-06', '08:29:51', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1046, 19, '2026-10-06', '08:29:59', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1047, 16, '2026-10-06', '08:30:04', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1048, 18, '2026-10-06', '08:30:13', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1049, 24, '2026-10-06', '08:30:21', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1050, 13, '2026-10-06', '08:30:29', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1051, 21, '2026-10-06', '08:30:33', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1052, 28, '2026-10-06', '08:30:35', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1053, 29, '2026-10-06', '08:30:39', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1054, 30, '2026-10-06', '08:30:48', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1055, 17, '2026-10-06', '08:33:02', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 03:32:05', '2026-10-06 03:32:05'),
(1056, 31, '2026-10-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:32:29', '2026-10-06 03:32:29'),
(1057, 32, '2026-10-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:34:17', '2026-10-06 03:34:17'),
(1058, 40, '2026-10-06', '08:30:00', '18:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '18:00:00', 'present', 0, 0, 0, 0, 0, 0, 9.50, NULL, NULL, '2026-10-06 03:34:17', '2026-10-06 03:42:52'),
(1059, 14, '2026-10-06', '08:00:00', '19:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:00:00', '19:00:00', 'present', 0, 0, 0, 0, 0, 0, 11.00, NULL, NULL, '2026-10-06 03:34:17', '2026-10-06 03:34:17'),
(1060, 12, '2026-10-06', '09:00:00', '17:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '09:00:00', '17:30:00', 'present', 0, 0, 0, 0, 0, 0, 8.50, NULL, NULL, '2026-10-06 03:34:17', '2026-10-06 03:34:17'),
(1061, 11, '2026-10-06', '20:00:00', '08:00:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '20:00:00', '08:00:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:34:17', '2026-10-06 03:34:17'),
(1062, 8, '2026-10-06', '08:30:00', '08:30:00', NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, 'Manual (HR)', '08:30:00', '08:30:00', 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:34:54', '2026-10-06 03:34:54'),
(1063, 23, '2026-10-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:34:54', '2026-10-06 03:34:54'),
(1064, 15, '2026-10-06', '08:42:22', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 17, 0, 0, NULL, NULL, NULL, '2026-10-06 03:38:07', '2026-10-06 03:38:07'),
(1065, 25, '2026-10-06', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'absent', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:42:06', '2026-10-06 03:42:06'),
(1066, 38, '2026-10-06', '08:35:00', NULL, NULL, NULL, NULL, 'Manual (HR)', NULL, NULL, NULL, NULL, '08:35:00', NULL, 'present', 0, 0, 0, 0, 0, 0, 0.00, NULL, NULL, '2026-10-06 03:42:06', '2026-10-06 03:42:06'),
(1067, 9, '2026-10-06', '08:52:41', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 7, 0, 0, NULL, NULL, NULL, '2026-10-06 11:46:28', '2026-10-06 11:46:28'),
(1068, 5, '2026-10-06', '09:01:08', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 11:46:28', '2026-10-06 11:46:28'),
(1069, 3, '2026-10-06', '09:01:31', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 11:46:28', '2026-10-06 11:46:28'),
(1070, 6, '2026-10-06', '09:24:23', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'late', 1, 54, 0, 0, 0, 0, NULL, NULL, NULL, '2026-10-06 11:46:28', '2026-10-06 11:46:28'),
(1071, 33, '2026-10-06', '13:10:15', NULL, NULL, NULL, NULL, 'Biometric Device', NULL, NULL, NULL, NULL, NULL, NULL, 'present', 0, 0, 1, 19, 0, 0, NULL, NULL, NULL, '2026-10-06 11:46:28', '2026-10-06 11:46:28');

-- --------------------------------------------------------

--
-- Table structure for table `hr_departments`
--

CREATE TABLE `hr_departments` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_departments`
--

INSERT INTO `hr_departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Production', NULL, '2026-08-19 09:18:41', '2026-08-31 08:45:42'),
(2, 'QC', NULL, '2026-08-31 08:41:29', '2026-08-31 08:41:29'),
(3, 'Administration Office', NULL, '2026-08-31 08:45:48', '2026-08-31 08:46:43'),
(4, 'Electrician', NULL, '2026-08-31 08:46:12', '2026-08-31 08:46:12'),
(5, 'Security Guard', NULL, '2026-08-31 08:46:18', '2026-08-31 08:46:26'),
(6, 'Rider', NULL, '2026-08-31 12:32:17', '2026-08-31 12:38:43'),
(7, 'Sweeper', NULL, '2026-08-31 12:38:51', '2026-08-31 12:38:51');

-- --------------------------------------------------------

--
-- Table structure for table `hr_designations`
--

CREATE TABLE `hr_designations` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `requires_location` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_designations`
--

INSERT INTO `hr_designations` (`id`, `name`, `description`, `requires_location`, `created_at`, `updated_at`) VALUES
(1, 'Manager', NULL, 0, '2026-08-31 08:43:00', '2026-08-31 08:43:00'),
(2, 'Assistant Manager', NULL, 0, '2026-08-31 08:43:10', '2026-08-31 08:43:10'),
(3, 'Supervisor', NULL, 0, '2026-08-31 08:43:15', '2026-08-31 08:43:20'),
(4, 'Labor', NULL, 0, '2026-08-31 08:43:36', '2026-08-31 08:43:36'),
(5, 'QA Manager', NULL, 0, '2026-08-31 08:47:13', '2026-08-31 08:47:13'),
(6, 'Production Incharge', NULL, 0, '2026-08-31 08:47:27', '2026-08-31 08:47:27'),
(7, 'Production Manager', NULL, 0, '2026-08-31 10:14:47', '2026-08-31 10:14:47'),
(8, 'GATEKEEPER', NULL, 0, '2026-08-31 12:23:21', '2026-08-31 12:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employees`
--

CREATE TABLE `hr_employees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `designation_id` bigint UNSIGNED NOT NULL,
  `shift_id` bigint UNSIGNED DEFAULT NULL,
  `custom_start_time` time DEFAULT NULL,
  `custom_end_time` time DEFAULT NULL,
  `face_encoding` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `face_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `biometric_device_id` bigint UNSIGNED DEFAULT NULL,
  `device_user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fingerprint_enrolled_at` timestamp NULL DEFAULT NULL,
  `last_device_sync_at` timestamp NULL DEFAULT NULL,
  `punch_gap_minutes` int UNSIGNED DEFAULT NULL,
  `weekly_off_days` json DEFAULT NULL COMMENT 'Per-employee weekly off day override. Overrides shift weekly_off_days if set.',
  `pending_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_docs_submitted` tinyint(1) NOT NULL DEFAULT '0',
  `date_of_birth` date DEFAULT NULL,
  `joining_date` date NOT NULL,
  `status` enum('active','non-active','terminated') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_employees`
--

INSERT INTO `hr_employees` (`id`, `user_id`, `department_id`, `designation_id`, `shift_id`, `custom_start_time`, `custom_end_time`, `face_encoding`, `face_photo`, `biometric_device_id`, `device_user_id`, `fingerprint_enrolled_at`, `last_device_sync_at`, `punch_gap_minutes`, `weekly_off_days`, `pending_deductions`, `first_name`, `last_name`, `email`, `phone`, `address`, `is_docs_submitted`, `date_of_birth`, `joining_date`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 2, 5, NULL, '08:30:00', '18:00:00', NULL, NULL, 1, '1', NULL, NULL, NULL, NULL, 0.00, 'MR. SARWAR', 'MUGHAL', 'mrsarwarmughal.4169@system.local', '00000000', 'Hyderabad', 1, NULL, '2025-08-31', 'active', '2026-08-31 08:50:10', '2026-09-12 10:58:07'),
(2, NULL, 3, 2, NULL, '08:30:00', '18:30:00', NULL, NULL, 1, '2', NULL, NULL, NULL, NULL, 0.00, 'MR. AHMED', 'ALI', 'mrahmedali.8666@system.local', '0312345789', NULL, 0, NULL, '2026-08-01', 'active', '2026-08-31 09:02:47', '2026-09-12 10:58:08'),
(3, NULL, 1, 7, NULL, '09:00:00', '17:30:00', NULL, NULL, 1, '3', NULL, NULL, NULL, NULL, 0.00, 'MISS. SEEMA', 'KHAN', 'missseemakhan.9415@system.local', NULL, NULL, 1, NULL, '2025-01-01', 'active', '2026-08-31 10:19:19', '2026-09-12 10:58:09'),
(4, NULL, 3, 1, 1, NULL, NULL, NULL, NULL, 1, '4', NULL, NULL, NULL, NULL, 0.00, 'MR. NAZIM', 'SATTAR SHAIKH', 'mrnazimsattarshaikh.4360@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:04:06', '2026-09-12 10:58:09'),
(5, NULL, 1, 2, NULL, '09:00:00', '17:30:00', NULL, NULL, 1, '5', NULL, NULL, NULL, NULL, 0.00, 'MISS. LARAIB', 'ABBASI', 'misslaraibabbasi.5559@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:06:31', '2026-09-12 10:58:10'),
(6, NULL, 1, 3, 2, NULL, NULL, NULL, NULL, 1, '6', NULL, NULL, NULL, NULL, 0.00, 'MR. SHAKIR', 'HUSSAIN', 'mrshakirhussain.1036@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:07:29', '2026-09-12 10:58:10'),
(7, NULL, 5, 8, NULL, '07:30:00', '21:00:00', NULL, NULL, 1, '7', NULL, NULL, NULL, NULL, 0.00, 'MR. RAFIQUE', 'THEBO', 'mrrafiquethebo.4894@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:20:41', '2026-09-12 10:58:11'),
(8, NULL, 5, 8, NULL, '08:30:00', '08:30:00', NULL, NULL, 1, '8', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SAJJAN', 'mrsajjan.2888@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:22:18', '2026-09-12 10:58:12'),
(9, NULL, 4, 4, NULL, '09:00:00', '18:00:00', NULL, NULL, 1, '9', NULL, NULL, NULL, NULL, 0.00, 'MR .RAO', 'ASHIQUE ALI', 'mrraoashiqueali.6226@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:35:49', '2026-09-12 10:58:12'),
(10, NULL, 7, 4, NULL, '08:00:00', '18:30:00', NULL, NULL, 1, '10', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'PARKASH', 'mrparkash.1000@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:40:00', '2026-09-12 10:58:13'),
(11, NULL, 5, 4, NULL, '20:00:00', '08:00:00', NULL, NULL, 1, '11', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'MANO', 'mrmano.4291@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:42:25', '2026-09-12 10:58:14'),
(12, NULL, 6, 4, NULL, '09:00:00', '17:30:00', NULL, NULL, 1, '12', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SHABBIR', 'mrshabbir.5411@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:43:24', '2026-09-12 10:58:14'),
(13, NULL, 5, 4, 2, NULL, NULL, NULL, NULL, 1, '13', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'AZEEM', 'mrazeem.9716@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:44:11', '2026-09-12 10:58:15'),
(14, NULL, 5, 4, NULL, '08:00:00', '19:00:00', NULL, NULL, 1, '14', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SHEHRYAR', 'mrshehryar.7567@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:45:21', '2026-09-12 10:58:15'),
(15, NULL, 4, 4, NULL, '09:00:00', '18:00:00', NULL, NULL, 1, '15', NULL, NULL, NULL, NULL, 0.00, 'ALI', 'NAWAZ', 'alinawaz.2797@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:45:58', '2026-09-12 10:58:16'),
(16, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '16', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SHAHNAWAZ', 'mrshahnawaz.9491@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:51:01', '2026-09-12 10:58:17'),
(17, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '17', NULL, NULL, NULL, NULL, 0.00, 'MR. HDYER', 'ALI', 'mrhdyerali.2396@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:51:33', '2026-09-12 10:58:17'),
(18, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '18', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'MUBASHIR', 'mrmubashir.2402@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:52:09', '2026-09-12 10:58:18'),
(19, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '19', NULL, NULL, NULL, NULL, 0.00, 'MR. AKBAR', 'ALI', 'mrakbarali.1530@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:52:30', '2026-09-12 10:58:19'),
(20, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '20', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'NADEEM', 'mrnadeem.8853@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:53:22', '2026-09-12 10:58:19'),
(21, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '21', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'ASAD', 'mrasad.5366@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:54:00', '2026-09-12 10:58:20'),
(22, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '22', NULL, NULL, NULL, NULL, 0.00, 'MR. ASHIR', 'AZEEM', 'mrashirazeem.3349@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:54:23', '2026-09-12 10:58:20'),
(23, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '23', NULL, NULL, NULL, NULL, 0.00, 'MR. AMIR', 'ALI', 'mramirali.2054@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:55:04', '2026-09-12 10:58:21'),
(24, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '24', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SAMEER', 'mrsameer.9043@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:56:26', '2026-09-12 10:58:22'),
(25, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '25', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'KAHLID', 'mrkahlid.1609@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:56:47', '2026-09-12 10:58:22'),
(26, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '26', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'ASGHAR', 'mrasghar.1240@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:57:05', '2026-09-12 10:58:23'),
(27, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '27', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SAMEER RAFIQUE', 'mrsameerrafique.3431@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'terminated', '2026-08-31 12:57:28', '2026-10-05 03:34:17'),
(28, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '28', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'NAFEES', 'mrnafees.7397@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:57:54', '2026-09-12 10:58:24'),
(29, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '29', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'QURBAN', 'mrqurban.6192@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:58:20', '2026-09-12 10:58:25'),
(30, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '30', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'AMIR RAO', 'mramirrao.1448@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:58:37', '2026-09-12 10:58:26'),
(31, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '31', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'ABDULLAH', 'mrabdullah.5198@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:59:04', '2026-09-12 10:58:26'),
(32, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '32', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'RASHID', 'mrrashid.5812@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 12:59:38', '2026-09-12 10:58:27'),
(33, NULL, 6, 4, NULL, '13:30:00', '18:30:00', NULL, NULL, 1, '33', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'DANIYAL', 'mrdaniyal.7455@system.local', NULL, NULL, 0, NULL, '2025-01-01', 'active', '2026-08-31 13:00:16', '2026-09-12 10:58:27'),
(36, NULL, 1, 4, 2, NULL, NULL, NULL, NULL, 1, '34', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'FARHAN', 'mrfarhan.3832@system.local', NULL, NULL, 0, NULL, '2026-09-14', 'active', '2026-09-17 03:30:06', '2026-09-21 07:01:46'),
(37, NULL, 2, 5, NULL, '09:00:00', '17:30:00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 'MR.', 'KHURRAM SHEHZAD', 'mrkhurramshehzad.1030@system.local', NULL, NULL, 0, NULL, '2026-09-15', 'terminated', '2026-09-17 03:30:53', '2026-09-19 10:46:06'),
(38, NULL, 1, 2, 2, NULL, NULL, NULL, NULL, 1, '35', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'FAROOQ', 'mrfarooq.1615@system.local', NULL, NULL, 0, NULL, '2026-09-15', 'active', '2026-09-17 03:31:27', '2026-09-21 07:01:46'),
(40, NULL, 3, 2, 2, NULL, NULL, NULL, NULL, 1, '36', NULL, NULL, NULL, NULL, 0.00, 'MR.', 'SANWAL', 'mrsanwal.9308@system.local', NULL, NULL, 0, NULL, '2026-10-01', 'active', '2026-10-01 04:02:06', '2026-10-05 03:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `hr_employee_documents`
--

CREATE TABLE `hr_employee_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_holidays`
--

CREATE TABLE `hr_holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `type` enum('public','company','optional') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_leaves`
--

CREATE TABLE `hr_leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `leave_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_leaves`
--

INSERT INTO `hr_leaves` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Casual', '2026-08-14', '2026-08-14', 'Casual Leave assigned via Employee Form', 'approved', '2026-08-31 09:02:47', '2026-08-31 09:02:47'),
(2, 1, 'Sick', '2026-09-01', '2026-09-02', 'Testing', 'approved', '2026-08-31 09:24:36', '2026-08-31 09:25:25');

-- --------------------------------------------------------

--
-- Table structure for table `hr_loans`
--

CREATE TABLE `hr_loans` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `installment_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'Monthly deductible amount, 0 for manual/large sum',
  `status` enum('pending','approved','rejected','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_loans`
--

INSERT INTO `hr_loans` (`id`, `employee_id`, `amount`, `installment_amount`, `status`, `reason`, `paid_amount`, `created_at`, `updated_at`) VALUES
(1, 1, 2000.00, 1000.00, 'approved', NULL, 0.00, '2026-08-31 09:39:03', '2026-08-31 09:41:12'),
(2, 2, 5000.00, 1000.00, 'approved', 'ok', 0.00, '2026-08-31 10:29:11', '2026-08-31 10:30:06'),
(3, 2, 5000.00, 1000.00, 'approved', NULL, 0.00, '2026-08-31 18:25:57', '2026-08-31 18:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `hr_loan_payments`
--

CREATE TABLE `hr_loan_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'salary_deduction',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_loan_scheduled_deductions`
--

CREATE TABLE `hr_loan_scheduled_deductions` (
  `id` bigint UNSIGNED NOT NULL,
  `loan_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `deduction_month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','deducted','skipped') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_loan_scheduled_deductions`
--

INSERT INTO `hr_loan_scheduled_deductions` (`id`, `loan_id`, `amount`, `deduction_month`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1000.00, '2026-08', 'pending', NULL, '2026-08-31 09:42:27', '2026-08-31 09:42:27');

-- --------------------------------------------------------

--
-- Table structure for table `hr_payrolls`
--

CREATE TABLE `hr_payrolls` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `payroll_type` enum('monthly','daily') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `month` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL,
  `gross_salary` decimal(10,2) NOT NULL DEFAULT '0.00',
  `allowances` decimal(10,2) NOT NULL DEFAULT '0.00',
  `attendance_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `manual_deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `loan_deduction` decimal(12,2) NOT NULL DEFAULT '0.00',
  `manual_allowances` decimal(10,2) NOT NULL DEFAULT '0.00',
  `carried_forward_deduction` decimal(10,2) NOT NULL DEFAULT '0.00',
  `carried_forward_to_next` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deductions` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bonuses` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(10,2) NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `auto_generated` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('generated','reviewed','paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'generated',
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `account_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'cash',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_payrolls`
--

INSERT INTO `hr_payrolls` (`id`, `employee_id`, `payroll_type`, `month`, `basic_salary`, `gross_salary`, `allowances`, `attendance_deductions`, `manual_deductions`, `loan_deduction`, `manual_allowances`, `carried_forward_deduction`, `carried_forward_to_next`, `deductions`, `bonuses`, `net_salary`, `notes`, `auto_generated`, `status`, `payment_date`, `created_at`, `updated_at`, `reviewed_by`, `reviewed_at`, `account_id`, `payment_method`, `payment_reference`) VALUES
(1, 1, 'monthly', '2026-08', 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 20000.00, NULL, 1, 'paid', '2026-08-31', '2026-08-31 09:00:23', '2026-08-31 09:00:41', NULL, NULL, NULL, 'cash', NULL),
(23, 3, 'monthly', '2026-09', 80000.00, 80000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 80000.00, NULL, 1, 'paid', '2026-09-16', '2026-09-16 10:41:31', '2026-09-16 10:43:40', 1, '2026-09-16 10:42:29', NULL, 'cash', NULL),
(37, 4, 'monthly', '2026-09', 56000.00, 56000.00, 0.00, 5598.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50402.00, NULL, 1, 'generated', NULL, '2026-09-21 09:03:15', '2026-09-21 09:03:15', NULL, NULL, NULL, 'cash', NULL),
(38, 1, 'monthly', '2026-09', 135000.00, 135000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 135000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(39, 5, 'monthly', '2026-09', 35000.00, 35000.00, 0.00, 2332.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 32668.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(40, 10, 'monthly', '2026-09', 20000.00, 20000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 20000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(41, 14, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(42, 18, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(43, 23, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(44, 24, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(45, 25, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(46, 27, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(47, 30, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(48, 31, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(49, 32, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(50, 33, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-09-30 05:37:12', '2026-09-30 05:37:12', NULL, NULL, NULL, 'cash', NULL),
(51, 2, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(52, 6, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 3733.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 12266.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(53, 7, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 1066.67, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14933.33, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(54, 8, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(55, 9, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 1600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14400.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(56, 11, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(57, 12, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(58, 13, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 2133.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 13866.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(59, 15, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 1600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14400.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(60, 16, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 533.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15466.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(61, 17, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 533.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15466.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(62, 19, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'paid', '2026-10-04', '2026-10-04 06:50:52', '2026-10-04 07:09:11', NULL, NULL, NULL, 'cash', NULL),
(63, 20, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 533.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15466.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(64, 21, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 1600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14400.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(65, 22, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 533.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15466.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(66, 26, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(67, 28, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(68, 29, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 1600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 14400.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(69, 36, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(70, 38, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 533.33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 15466.67, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL),
(71, 40, 'monthly', '2026-09', 16000.00, 16000.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 16000.00, NULL, 1, 'generated', NULL, '2026-10-04 06:50:52', '2026-10-04 06:50:52', NULL, NULL, NULL, 'cash', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hr_payroll_details`
--

CREATE TABLE `hr_payroll_details` (
  `id` bigint UNSIGNED NOT NULL,
  `payroll_id` bigint UNSIGNED NOT NULL,
  `type` enum('allowance','deduction') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_payroll_details`
--

INSERT INTO `hr_payroll_details` (`id`, `payroll_id`, `type`, `name`, `amount`, `description`, `created_at`, `updated_at`) VALUES
(5, 37, 'deduction', 'Absence Deduction (3 days)', 5598.00, '3 absent days × Rs. 1866.00', '2026-09-21 09:03:15', '2026-09-21 09:03:15'),
(6, 39, 'deduction', 'Absence Deduction (2 days)', 2332.00, '2 absent days × Rs. 1166.00', '2026-09-30 05:37:12', '2026-09-30 05:37:12'),
(7, 52, 'deduction', 'Absence & Leave Deduction (7 days)', 3733.33, '7 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(8, 53, 'deduction', 'Absence & Leave Deduction (2 days)', 1066.67, '2 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(9, 55, 'deduction', 'Absence & Leave Deduction (3 days)', 1600.00, '3 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(10, 58, 'deduction', 'Absence & Leave Deduction (4 days)', 2133.33, '4 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(11, 59, 'deduction', 'Absence & Leave Deduction (3 days)', 1600.00, '3 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(12, 60, 'deduction', 'Absence & Leave Deduction (1 days)', 533.33, '1 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(13, 61, 'deduction', 'Absence & Leave Deduction (1 days)', 533.33, '1 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(14, 63, 'deduction', 'Absence & Leave Deduction (1 days)', 533.33, '1 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(15, 64, 'deduction', 'Absence & Leave Deduction (3 days)', 1600.00, '3 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(16, 65, 'deduction', 'Absence & Leave Deduction (1 days)', 533.33, '1 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(17, 68, 'deduction', 'Absence & Leave Deduction (3 days)', 1600.00, '3 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52'),
(18, 70, 'deduction', 'Absence & Leave Deduction (1 days)', 533.33, '1 absent days × Rs. 533.33 (Salary / 30)', '2026-10-04 06:50:52', '2026-10-04 06:50:52');

-- --------------------------------------------------------

--
-- Table structure for table `hr_policies`
--

CREATE TABLE `hr_policies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `applies_to` enum('all','specific') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `employee_ids` json DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `overtime_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `overtime_min_minutes` int NOT NULL DEFAULT '60',
  `overtime_rate_type` enum('hourly_formula','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hourly_formula',
  `overtime_multiplier` decimal(5,2) NOT NULL DEFAULT '1.50',
  `overtime_fixed_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `late_grace_minutes` int NOT NULL DEFAULT '15',
  `late_penalty_type` enum('3_lates_1_day','fixed_per_instance','none') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '3_lates_1_day',
  `late_penalty_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `absence_deduction_type` enum('salary_divided_by_30','fixed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'salary_divided_by_30',
  `absence_fixed_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `early_checkout_penalty_type` enum('none','fixed_per_instance','3_earlies_1_day') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `early_checkout_penalty_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `min_hours_half_day` decimal(5,2) NOT NULL DEFAULT '4.00',
  `half_day_deduction_type` enum('half_day_salary','fixed','none') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'half_day_salary',
  `half_day_fixed_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_policies`
--

INSERT INTO `hr_policies` (`id`, `name`, `applies_to`, `employee_ids`, `is_default`, `is_active`, `overtime_enabled`, `overtime_min_minutes`, `overtime_rate_type`, `overtime_multiplier`, `overtime_fixed_rate`, `late_grace_minutes`, `late_penalty_type`, `late_penalty_amount`, `absence_deduction_type`, `absence_fixed_amount`, `early_checkout_penalty_type`, `early_checkout_penalty_amount`, `min_hours_half_day`, `half_day_deduction_type`, `half_day_fixed_amount`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'For All', 'all', NULL, 0, 1, 1, 60, 'fixed', 1.00, 60.00, 5, '3_lates_1_day', 200.00, 'fixed', 1000.00, '3_earlies_1_day', 0.00, 5.00, 'half_day_salary', 500.00, NULL, '2026-10-04 13:26:18', '2026-10-04 13:26:18');

-- --------------------------------------------------------

--
-- Table structure for table `hr_salary_structures`
--

CREATE TABLE `hr_salary_structures` (
  `id` bigint UNSIGNED NOT NULL,
  `parent_structure_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `salary_type` enum('salary','commission','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'salary',
  `base_salary` decimal(12,2) NOT NULL DEFAULT '0.00',
  `daily_wages` decimal(10,2) DEFAULT NULL,
  `use_daily_wages` tinyint(1) NOT NULL DEFAULT '0',
  `commission_percentage` decimal(5,2) DEFAULT NULL,
  `sales_target` decimal(12,2) DEFAULT NULL,
  `commission_tiers` json DEFAULT NULL,
  `allowances` json DEFAULT NULL,
  `deductions` json DEFAULT NULL,
  `attendance_deduction_policy` json DEFAULT NULL,
  `carry_forward_deductions` tinyint(1) NOT NULL DEFAULT '0',
  `leave_salary_per_day` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_salary_structures`
--

INSERT INTO `hr_salary_structures` (`id`, `parent_structure_id`, `name`, `employee_id`, `salary_type`, `base_salary`, `daily_wages`, `use_daily_wages`, `commission_percentage`, `sales_target`, `commission_tiers`, `allowances`, `deductions`, `attendance_deduction_policy`, `carry_forward_deductions`, `leave_salary_per_day`, `created_at`, `updated_at`) VALUES
(1, NULL, 'PRODCUTION INCHARGE', NULL, 'salary', 80000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, 2666.00, '2026-08-31 08:59:02', '2026-08-31 13:05:27'),
(2, 1, 'Custom: Production (Sarwar)', 1, 'salary', 22000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, 666.00, '2026-08-31 09:16:36', '2026-08-31 09:16:36'),
(3, NULL, 'QA MANAGER', NULL, 'salary', 135000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, 4500.00, '2026-08-31 13:03:04', '2026-08-31 13:03:04'),
(4, NULL, 'MANAGER', NULL, 'salary', 56000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, 1866.00, '2026-08-31 13:03:44', '2026-08-31 13:03:44'),
(6, NULL, 'QA OFFICER', NULL, 'salary', 35000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, 1166.00, '2026-08-31 13:06:33', '2026-08-31 13:06:33'),
(7, NULL, 'Labor', NULL, 'salary', 16000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, NULL, '2026-09-12 12:56:01', '2026-09-12 12:56:01'),
(8, NULL, 'PARKASH', NULL, 'salary', 20000.00, 0.00, 0, NULL, NULL, NULL, NULL, NULL, '{\"late_rules\": [], \"early_rules\": []}', 0, NULL, '2026-09-12 12:57:57', '2026-09-12 12:57:57');

-- --------------------------------------------------------

--
-- Table structure for table `hr_settings`
--

CREATE TABLE `hr_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_settings`
--

INSERT INTO `hr_settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'attendance_punch_gap_minutes', '20', 'integer', 'attendance', 'Punch Gap (Minutes)', 'Minimum minutes between punches to be considered as separate check-in/check-out. Punches within this gap will be ignored as duplicates.', '2026-08-13 07:51:16', '2026-08-13 07:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `hr_shifts`
--

CREATE TABLE `hr_shifts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `grace_minutes` int NOT NULL DEFAULT '15',
  `weekly_off_days` json DEFAULT NULL COMMENT 'Array of day numbers (0=Sun,1=Mon..6=Sat) that are weekly off for this shift',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_shifts`
--

INSERT INTO `hr_shifts` (`id`, `name`, `start_time`, `end_time`, `break_start`, `break_end`, `grace_minutes`, `weekly_off_days`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'Morning', '08:30:00', '18:30:00', NULL, NULL, 15, NULL, 0, '2026-08-31 09:05:59', '2026-08-31 10:21:22'),
(2, 'Morning', '08:30:00', '18:00:00', NULL, NULL, 5, NULL, 1, '2026-08-31 10:21:22', '2026-08-31 10:21:22');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_series`
--

CREATE TABLE `invoice_series` (
  `id` bigint UNSIGNED NOT NULL,
  `prefix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `next_number` bigint UNSIGNED NOT NULL DEFAULT '1',
  `padding` int NOT NULL DEFAULT '4',
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_series`
--

INSERT INTO `invoice_series` (`id`, `prefix`, `next_number`, `padding`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'INV', 4, 4, 1, '2026-08-13 07:52:11', '2026-09-30 13:14:17'),
(2, 'INVSLE', 1, 4, 0, '2026-08-13 07:52:11', '2026-08-13 07:52:11'),
(3, 'SQ', 1, 6, 0, '2026-08-13 07:52:11', '2026-08-13 07:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `inward_gatepasses`
--

CREATE TABLE `inward_gatepasses` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED DEFAULT NULL,
  `gatepass_date` date DEFAULT NULL,
  `gatepass_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','linked','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inward_gatepass_items`
--

CREATE TABLE `inward_gatepass_items` (
  `id` bigint UNSIGNED NOT NULL,
  `inward_gatepass_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_entries`
--

CREATE TABLE `journal_entries` (
  `id` bigint UNSIGNED NOT NULL,
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_id` bigint UNSIGNED DEFAULT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `entry_date` date NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_reconciled` tinyint(1) NOT NULL DEFAULT '0',
  `reconciled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `party_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_entries`
--

INSERT INTO `journal_entries` (`id`, `source_type`, `source_id`, `account_id`, `entry_date`, `debit`, `credit`, `description`, `is_reconciled`, `reconciled_at`, `created_at`, `updated_at`, `party_type`, `party_id`) VALUES
(1, 'App\\Models\\Customer', 1, 4, '2026-08-22', 1000.00, 0.00, 'Opening Balance', 0, NULL, '2026-08-22 12:18:31', '2026-08-22 12:18:31', 'App\\Models\\Customer', 1),
(2, 'App\\Models\\VoucherMaster', 1, 2, '2026-08-22', 200.00, 0.00, 'Receipt', 0, NULL, '2026-08-22 12:20:00', '2026-08-22 12:20:00', NULL, NULL),
(3, 'App\\Models\\VoucherMaster', 1, 3, '2026-08-22', 800.00, 0.00, 'Receipt', 0, NULL, '2026-08-22 12:20:00', '2026-08-22 12:20:00', NULL, NULL),
(4, 'App\\Models\\VoucherMaster', 1, 4, '2026-08-22', 0.00, 1000.00, 'Receipt from customer', 0, NULL, '2026-08-22 12:20:00', '2026-08-22 12:20:00', 'App\\Models\\Customer', 1),
(5, 'App\\Models\\VoucherMaster', 2, 2, '2026-08-31', 67777.00, 0.00, 'Payment received from Invoice #INV-0001', 0, NULL, '2026-08-31 10:48:58', '2026-08-31 10:48:58', NULL, NULL),
(6, 'App\\Models\\VoucherMaster', 2, 5, '2026-08-31', 0.00, 67777.00, 'Payment for Invoice #INV-0001', 0, NULL, '2026-08-31 10:48:58', '2026-08-31 10:48:58', NULL, NULL),
(7, 'App\\Models\\MaterialPurchase', 2, 6, '2026-09-01', 208069.64, 0.00, 'Material Purchase Invoice #MPUR-001', 0, NULL, '2026-09-01 09:29:31', '2026-09-01 09:29:31', NULL, NULL),
(8, 'App\\Models\\MaterialPurchase', 2, 1, '2026-09-01', 0.00, 208069.64, 'Material Purchase Invoice #MPUR-001', 0, NULL, '2026-09-01 09:29:31', '2026-09-01 09:29:31', 'App\\Models\\Vendor', 1),
(9, 'App\\Models\\MaterialPurchase', 3, 6, '2026-09-03', 7080.00, 0.00, 'Material Purchase Invoice #MPUR-002', 0, NULL, '2026-09-05 05:41:15', '2026-09-05 05:41:15', NULL, NULL),
(10, 'App\\Models\\MaterialPurchase', 3, 1, '2026-09-03', 0.00, 7080.00, 'Material Purchase Invoice #MPUR-002', 0, NULL, '2026-09-05 05:41:15', '2026-09-05 05:41:15', 'App\\Models\\Vendor', 1),
(11, 'App\\Models\\MaterialPurchase', 5, 6, '2026-09-05', 134500.00, 0.00, 'Material Purchase Invoice #MPUR-003', 0, NULL, '2026-09-05 05:49:59', '2026-09-05 05:49:59', NULL, NULL),
(12, 'App\\Models\\MaterialPurchase', 5, 1, '2026-09-05', 0.00, 134500.00, 'Material Purchase Invoice #MPUR-003', 0, NULL, '2026-09-05 05:49:59', '2026-09-05 05:49:59', 'App\\Models\\Vendor', 2),
(13, 'App\\Models\\MaterialPurchase', 5, 1, '2026-09-05', 134500.00, 0.00, 'Payment for Material Purchase #MPUR-003', 0, NULL, '2026-09-05 05:49:59', '2026-09-05 05:49:59', 'App\\Models\\Vendor', 2),
(14, 'App\\Models\\MaterialPurchase', 5, 2, '2026-09-05', 0.00, 134500.00, 'Payment for Material Purchase #MPUR-003', 0, NULL, '2026-09-05 05:49:59', '2026-09-05 05:49:59', NULL, NULL),
(15, 'App\\Models\\MaterialPurchase', 6, 6, '2026-09-01', 78000.18, 0.00, 'Material Purchase Invoice #MPUR-004', 0, NULL, '2026-09-05 05:54:31', '2026-09-05 05:54:31', NULL, NULL),
(16, 'App\\Models\\MaterialPurchase', 6, 1, '2026-09-01', 0.00, 78000.18, 'Material Purchase Invoice #MPUR-004', 0, NULL, '2026-09-05 05:54:31', '2026-09-05 05:54:31', 'App\\Models\\Vendor', 3),
(17, 'App\\Models\\MaterialPurchase', 6, 1, '2026-09-01', 78000.18, 0.00, 'Payment for Material Purchase #MPUR-004', 0, NULL, '2026-09-05 05:54:31', '2026-09-05 05:54:31', 'App\\Models\\Vendor', 3),
(18, 'App\\Models\\MaterialPurchase', 6, 2, '2026-09-01', 0.00, 78000.18, 'Payment for Material Purchase #MPUR-004', 0, NULL, '2026-09-05 05:54:31', '2026-09-05 05:54:31', NULL, NULL),
(19, 'App\\Models\\MaterialPurchase', 7, 6, '2026-09-02', 14000.00, 0.00, 'Material Purchase Invoice #MPUR-005', 0, NULL, '2026-09-05 06:02:27', '2026-09-05 06:02:27', NULL, NULL),
(20, 'App\\Models\\MaterialPurchase', 7, 1, '2026-09-02', 0.00, 14000.00, 'Material Purchase Invoice #MPUR-005', 0, NULL, '2026-09-05 06:02:27', '2026-09-05 06:02:27', 'App\\Models\\Vendor', 4),
(21, 'App\\Models\\MaterialPurchase', 7, 1, '2026-09-02', 14000.00, 0.00, 'Payment for Material Purchase #MPUR-005', 0, NULL, '2026-09-05 06:02:27', '2026-09-05 06:02:27', 'App\\Models\\Vendor', 4),
(22, 'App\\Models\\MaterialPurchase', 7, 2, '2026-09-02', 0.00, 14000.00, 'Payment for Material Purchase #MPUR-005', 0, NULL, '2026-09-05 06:02:27', '2026-09-05 06:02:27', NULL, NULL),
(23, 'App\\Models\\MaterialPurchase', 8, 6, '2026-09-02', 29500.00, 0.00, 'Material Purchase Invoice #MPUR-006', 0, NULL, '2026-09-05 06:06:14', '2026-09-05 06:06:14', NULL, NULL),
(24, 'App\\Models\\MaterialPurchase', 8, 1, '2026-09-02', 0.00, 29500.00, 'Material Purchase Invoice #MPUR-006', 0, NULL, '2026-09-05 06:06:14', '2026-09-05 06:06:14', 'App\\Models\\Vendor', 5),
(25, 'App\\Models\\MaterialPurchase', 8, 1, '2026-09-02', 29500.00, 0.00, 'Payment for Material Purchase #MPUR-006', 0, NULL, '2026-09-05 06:06:14', '2026-09-05 06:06:14', 'App\\Models\\Vendor', 5),
(26, 'App\\Models\\MaterialPurchase', 8, 2, '2026-09-02', 0.00, 29500.00, 'Payment for Material Purchase #MPUR-006', 0, NULL, '2026-09-05 06:06:14', '2026-09-05 06:06:14', NULL, NULL),
(27, 'App\\Models\\MaterialPurchase', 9, 6, '2026-09-05', 58000.00, 0.00, 'Material Purchase Invoice #MPUR-007', 0, NULL, '2026-09-05 06:11:21', '2026-09-05 06:11:21', NULL, NULL),
(28, 'App\\Models\\MaterialPurchase', 9, 1, '2026-09-05', 0.00, 58000.00, 'Material Purchase Invoice #MPUR-007', 0, NULL, '2026-09-05 06:11:21', '2026-09-05 06:11:21', 'App\\Models\\Vendor', 6),
(29, 'App\\Models\\MaterialPurchase', 9, 1, '2026-09-05', 58000.00, 0.00, 'Payment for Material Purchase #MPUR-007', 0, NULL, '2026-09-05 06:11:21', '2026-09-05 06:11:21', 'App\\Models\\Vendor', 6),
(30, 'App\\Models\\MaterialPurchase', 9, 2, '2026-09-05', 0.00, 58000.00, 'Payment for Material Purchase #MPUR-007', 0, NULL, '2026-09-05 06:11:21', '2026-09-05 06:11:21', NULL, NULL),
(31, 'App\\Models\\MaterialPurchase', 10, 6, '2026-09-12', 13500.00, 0.00, 'Material Purchase Invoice #MPUR-008', 0, NULL, '2026-09-12 04:21:42', '2026-09-12 04:21:42', NULL, NULL),
(32, 'App\\Models\\MaterialPurchase', 10, 1, '2026-09-12', 0.00, 13500.00, 'Material Purchase Invoice #MPUR-008', 0, NULL, '2026-09-12 04:21:42', '2026-09-12 04:21:42', 'App\\Models\\Vendor', 5),
(33, 'App\\Models\\MaterialPurchase', 10, 1, '2026-09-12', 13500.00, 0.00, 'Payment for Material Purchase #MPUR-008', 0, NULL, '2026-09-12 04:21:42', '2026-09-12 04:21:42', 'App\\Models\\Vendor', 5),
(34, 'App\\Models\\MaterialPurchase', 10, 2, '2026-09-12', 0.00, 13500.00, 'Payment for Material Purchase #MPUR-008', 0, NULL, '2026-09-12 04:21:42', '2026-09-12 04:21:42', NULL, NULL),
(35, 'App\\Models\\MaterialPurchase', 11, 6, '2026-09-09', 20000.00, 0.00, 'Material Purchase Invoice #MPUR-009', 0, NULL, '2026-09-12 04:23:33', '2026-09-12 04:23:33', NULL, NULL),
(36, 'App\\Models\\MaterialPurchase', 11, 1, '2026-09-09', 0.00, 20000.00, 'Material Purchase Invoice #MPUR-009', 0, NULL, '2026-09-12 04:23:33', '2026-09-12 04:23:33', 'App\\Models\\Vendor', 2),
(37, 'App\\Models\\MaterialPurchase', 11, 1, '2026-09-09', 20000.00, 0.00, 'Payment for Material Purchase #MPUR-009', 0, NULL, '2026-09-12 04:23:33', '2026-09-12 04:23:33', 'App\\Models\\Vendor', 2),
(38, 'App\\Models\\MaterialPurchase', 11, 2, '2026-09-09', 0.00, 20000.00, 'Payment for Material Purchase #MPUR-009', 0, NULL, '2026-09-12 04:23:33', '2026-09-12 04:23:33', NULL, NULL),
(39, 'App\\Models\\Vendor', 8, 1, '2026-09-12', 0.00, 201200.00, 'Opening Balance', 0, NULL, '2026-09-12 04:26:53', '2026-09-12 04:26:53', 'App\\Models\\Vendor', 8),
(40, 'App\\Models\\MaterialPurchase', 12, 6, '2026-09-09', 84000.00, 0.00, 'Material Purchase Invoice #MPUR-010', 0, NULL, '2026-09-12 04:30:41', '2026-09-12 04:30:41', NULL, NULL),
(41, 'App\\Models\\MaterialPurchase', 12, 1, '2026-09-09', 0.00, 84000.00, 'Material Purchase Invoice #MPUR-010', 0, NULL, '2026-09-12 04:30:41', '2026-09-12 04:30:41', 'App\\Models\\Vendor', 2),
(42, 'App\\Models\\MaterialPurchase', 13, 6, '2026-09-09', 14000.00, 0.00, 'Material Purchase Invoice #MPUR-011', 0, NULL, '2026-09-12 04:31:45', '2026-09-12 04:31:45', NULL, NULL),
(43, 'App\\Models\\MaterialPurchase', 13, 1, '2026-09-09', 0.00, 14000.00, 'Material Purchase Invoice #MPUR-011', 0, NULL, '2026-09-12 04:31:45', '2026-09-12 04:31:45', 'App\\Models\\Vendor', 4),
(44, 'App\\Models\\MaterialPurchase', 13, 1, '2026-09-09', 14000.00, 0.00, 'Payment for Material Purchase #MPUR-011', 0, NULL, '2026-09-12 04:31:45', '2026-09-12 04:31:45', 'App\\Models\\Vendor', 4),
(45, 'App\\Models\\MaterialPurchase', 13, 2, '2026-09-09', 0.00, 14000.00, 'Payment for Material Purchase #MPUR-011', 0, NULL, '2026-09-12 04:31:45', '2026-09-12 04:31:45', NULL, NULL),
(46, 'App\\Models\\MaterialPurchase', 14, 6, '2026-09-30', 3420000.00, 0.00, 'Material Purchase Invoice #MPUR-012', 0, NULL, '2026-09-30 05:22:43', '2026-09-30 05:22:43', NULL, NULL),
(47, 'App\\Models\\MaterialPurchase', 14, 1, '2026-09-30', 0.00, 3420000.00, 'Material Purchase Invoice #MPUR-012', 0, NULL, '2026-09-30 05:22:43', '2026-09-30 05:22:43', 'App\\Models\\Vendor', 5),
(48, 'App\\Models\\MaterialPurchase', 14, 1, '2026-09-30', 3420000.00, 0.00, 'Payment for Material Purchase #MPUR-012', 0, NULL, '2026-09-30 05:22:43', '2026-09-30 05:22:43', 'App\\Models\\Vendor', 5),
(49, 'App\\Models\\MaterialPurchase', 14, 2, '2026-09-30', 0.00, 3420000.00, 'Payment for Material Purchase #MPUR-012', 0, NULL, '2026-09-30 05:22:43', '2026-09-30 05:22:43', NULL, NULL),
(50, 'App\\Models\\MaterialPurchase', 15, 6, '2026-09-30', 65000.00, 0.00, 'Material Purchase Invoice #MPUR-013', 0, NULL, '2026-09-30 13:16:08', '2026-09-30 13:16:08', NULL, NULL),
(51, 'App\\Models\\MaterialPurchase', 15, 1, '2026-09-30', 0.00, 65000.00, 'Material Purchase Invoice #MPUR-013', 0, NULL, '2026-09-30 13:16:08', '2026-09-30 13:16:08', 'App\\Models\\Vendor', 9);

-- --------------------------------------------------------

--
-- Table structure for table `material_purchases`
--

CREATE TABLE `material_purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_date` date NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transport_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transport_charges` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_purchases`
--

INSERT INTO `material_purchases` (`id`, `invoice_no`, `purchase_date`, `vendor_id`, `purchase_type`, `payment_method`, `payment_status`, `transport_name`, `driver_name`, `driver_contact`, `vehicle_no`, `transport_charges`, `remarks`, `subtotal`, `total_discount`, `total_tax`, `total_amount`, `paid_amount`, `balance_amount`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'MPUR-001', '2026-09-01', 1, 'Raw Material', 'Credit', 'Pending', 'DAEWOO', 'SALMAN', NULL, '010101', 1.00, 'TESTING', 208068.64, 0.00, 0.00, 208069.64, 0.00, 208069.64, 'completed', '2026-09-01 09:29:31', '2026-09-01 09:29:31', NULL),
(3, 'MPUR-002', '2026-09-03', 1, 'Raw Material', 'Credit', 'Pending', 'Shalimar', 'Unknow', NULL, '0000', 0.00, 'Bilty Charges Not Added.', 7080.00, 0.00, 0.00, 7080.00, 0.00, 7080.00, 'completed', '2026-09-05 05:41:15', '2026-09-05 05:41:15', NULL),
(5, 'MPUR-003', '2026-09-05', 2, 'Raw Material', 'Cash', 'Paid', 'Shalimar', 'Unknow', NULL, '0000', 0.00, NULL, 134500.00, 0.00, 0.00, 134500.00, 134500.00, 0.00, 'completed', '2026-09-05 05:49:59', '2026-09-05 05:49:59', NULL),
(6, 'MPUR-004', '2026-09-01', 3, 'Raw Material', 'Cash', 'Paid', 'New Al Hyder', 'Al-Hyder', NULL, '0000', 0.00, NULL, 78000.18, 0.00, 0.00, 78000.18, 78000.18, 0.00, 'completed', '2026-09-05 05:54:31', '2026-09-05 05:54:31', NULL),
(7, 'MPUR-005', '2026-09-02', 4, 'Raw Material', 'Cash', 'Paid', 'Daewoo', 'Unknow', NULL, '0000', 0.00, NULL, 14000.00, 0.00, 0.00, 14000.00, 14000.00, 0.00, 'completed', '2026-09-05 06:02:27', '2026-09-05 06:02:27', NULL),
(8, 'MPUR-006', '2026-09-02', 5, 'Raw Material', 'Cash', 'Paid', 'Shalimar', 'Unknow', NULL, '0000', 0.00, NULL, 29500.00, 0.00, 0.00, 29500.00, 29500.00, 0.00, 'completed', '2026-09-05 06:06:14', '2026-09-05 06:06:14', NULL),
(9, 'MPUR-007', '2026-09-05', 6, 'Raw Material', 'Cash', 'Paid', 'Leaopards', 'Unknow', NULL, '0000', 0.00, NULL, 58000.00, 0.00, 0.00, 58000.00, 58000.00, 0.00, 'completed', '2026-09-05 06:11:21', '2026-09-05 06:11:21', NULL),
(10, 'MPUR-008', '2026-09-12', 5, 'Raw Material', 'Cash', 'Paid', NULL, NULL, NULL, NULL, 0.00, NULL, 13500.00, 0.00, 0.00, 13500.00, 13500.00, 0.00, 'completed', '2026-09-12 04:21:42', '2026-09-12 04:21:42', NULL),
(11, 'MPUR-009', '2026-09-09', 2, 'Raw Material', 'Cash', 'Paid', NULL, NULL, NULL, NULL, 0.00, NULL, 20000.00, 0.00, 0.00, 20000.00, 20000.00, 0.00, 'completed', '2026-09-12 04:23:33', '2026-09-12 04:23:33', NULL),
(12, 'MPUR-010', '2026-09-09', 2, 'Raw Material', 'Cash', 'Paid', NULL, NULL, NULL, NULL, 0.00, NULL, 84000.00, 0.00, 0.00, 84000.00, 84000.00, 0.00, 'completed', '2026-09-12 04:30:41', '2026-09-12 04:30:41', NULL),
(13, 'MPUR-011', '2026-09-09', 4, 'Raw Material', 'Cash', 'Paid', NULL, NULL, NULL, NULL, 0.00, NULL, 14000.00, 0.00, 0.00, 14000.00, 14000.00, 0.00, 'completed', '2026-09-12 04:31:45', '2026-09-12 04:31:45', NULL),
(14, 'MPUR-012', '2026-09-30', 5, 'Mixed', 'Bank', 'Paid', NULL, NULL, NULL, NULL, 0.00, NULL, 3420000.00, 0.00, 0.00, 3420000.00, 3420000.00, 0.00, 'completed', '2026-09-30 05:22:43', '2026-09-30 05:22:43', NULL),
(15, 'MPUR-013', '2026-09-30', 9, 'Raw Material', 'Credit', 'Pending', NULL, NULL, NULL, NULL, 0.00, NULL, 65000.00, 0.00, 0.00, 65000.00, 0.00, 65000.00, 'completed', '2026-09-30 13:16:08', '2026-09-30 13:16:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_purchase_items`
--

CREATE TABLE `material_purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `material_purchase_id` bigint UNSIGNED NOT NULL,
  `item_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `qty` decimal(15,4) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(15,2) NOT NULL DEFAULT '0.00',
  `batch_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mfg_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_purchase_items`
--

INSERT INTO `material_purchase_items` (`id`, `material_purchase_id`, `item_type`, `item_id`, `qty`, `unit_price`, `discount`, `tax`, `batch_no`, `mfg_date`, `exp_date`, `subtotal`, `created_at`, `updated_at`) VALUES
(2, 2, 'App\\Models\\RawMaterial', 132, 0.0169, 16530.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 278.94, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(3, 2, 'App\\Models\\RawMaterial', 141, 0.2687, 690000.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 185403.00, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(4, 2, 'App\\Models\\RawMaterial', 144, 0.0013, 19000.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 23.75, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(5, 2, 'App\\Models\\RawMaterial', 150, 0.2250, 750.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 168.75, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(6, 2, 'App\\Models\\RawMaterial', 139, 0.0002, 9000.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 1.35, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(7, 2, 'App\\Models\\RawMaterial', 22, 0.2250, 2600.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 585.00, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(8, 2, 'App\\Models\\RawMaterial', 28, 0.1190, 2600.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 309.40, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(9, 2, 'App\\Models\\RawMaterial', 25, 0.2250, 850.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 191.25, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(10, 2, 'App\\Models\\RawMaterial', 40, 70.0000, 283.60, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 19852.00, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(11, 2, 'App\\Models\\RawMaterial', 159, 20.0000, 15.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 300.00, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(12, 2, 'App\\Models\\RawMaterial', 52, 0.2400, 3980.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 955.20, '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(13, 3, 'App\\Models\\RawMaterial', 50, 2.0000, 1790.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 3580.00, '2026-09-05 05:41:15', '2026-09-05 05:41:15'),
(14, 3, 'App\\Models\\RawMaterial', 77, 1.0000, 3500.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 3500.00, '2026-09-05 05:41:15', '2026-09-05 05:41:15'),
(18, 5, 'App\\Models\\RawMaterial', 26, 50.0000, 750.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 37500.00, '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(19, 5, 'App\\Models\\RawMaterial', 22, 25.0000, 2200.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 55000.00, '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(20, 5, 'App\\Models\\RawMaterial', 12, 100.0000, 420.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 42000.00, '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(21, 6, 'App\\Models\\RawMaterial', 40, 275.0000, 283.64, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 78000.18, '2026-09-05 05:54:31', '2026-09-05 05:54:31'),
(22, 7, 'App\\Models\\RawMaterial', 117, 10.0000, 1400.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 14000.00, '2026-09-05 06:02:27', '2026-09-05 06:02:27'),
(23, 8, 'App\\Models\\RawMaterial', 87, 5.0000, 3400.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 17000.00, '2026-09-05 06:06:14', '2026-09-05 06:06:14'),
(24, 8, 'App\\Models\\RawMaterial', 101, 5.0000, 2500.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 12500.00, '2026-09-05 06:06:14', '2026-09-05 06:06:14'),
(25, 9, 'App\\Models\\RawMaterial', 142, 0.1000, 580000.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 58000.00, '2026-09-05 06:11:21', '2026-09-05 06:11:21'),
(26, 10, 'App\\Models\\RawMaterial', 147, 3.0000, 4500.00, 0.00, 0.00, '001', '2026-01-01', '2028-01-01', 13500.00, '2026-09-12 04:21:42', '2026-09-12 04:21:42'),
(27, 11, 'App\\Models\\RawMaterial', 36, 25.0000, 800.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 20000.00, '2026-09-12 04:23:33', '2026-09-12 04:23:33'),
(28, 12, 'App\\Models\\RawMaterial', 12, 200.0000, 420.00, 0.00, 0.00, '001', '2026-01-01', '2028-01-01', 84000.00, '2026-09-12 04:30:41', '2026-09-12 04:30:41'),
(29, 13, 'App\\Models\\RawMaterial', 117, 10.0000, 1400.00, 0.00, 0.00, '01', '2026-01-01', '2028-01-01', 14000.00, '2026-09-12 04:31:45', '2026-09-12 04:31:45'),
(30, 14, 'App\\Models\\RawMaterial', 97, 240000.0000, 3.50, 0.00, 0.00, NULL, NULL, NULL, 840000.00, '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(31, 14, 'App\\Models\\RawMaterial', 95, 240000.0000, 3.50, 0.00, 0.00, NULL, NULL, NULL, 840000.00, '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(32, 14, 'App\\Models\\RawMaterial', 96, 240000.0000, 3.50, 0.00, 0.00, NULL, NULL, NULL, 840000.00, '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(33, 14, 'App\\Models\\RawMaterial', 112, 240000.0000, 3.50, 0.00, 0.00, NULL, NULL, NULL, 840000.00, '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(34, 14, 'App\\Models\\PackagingMaterial', 2, 4000.0000, 15.00, 0.00, 0.00, NULL, NULL, NULL, 60000.00, '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(35, 15, 'App\\Models\\RawMaterial', 17, 1000.0000, 65.00, 0.00, 0.00, NULL, '2026-09-30', '2028-09-30', 65000.00, '2026-09-30 13:16:08', '2026-09-30 13:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `material_requisitions`
--

CREATE TABLE `material_requisitions` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED NOT NULL,
  `requisition_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','in_production','fulfilled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_requisitions`
--

INSERT INTO `material_requisitions` (`id`, `sale_id`, `requisition_no`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 4, 'REQ-00001', 'fulfilled', 'Production Fully Completed (4000 / 4000 Pcs) via Batch #BATCH-2026-0001', '2026-09-30 05:08:51', '2026-09-30 05:28:13'),
(2, 5, 'REQ-00002', 'pending', 'Auto-generated for Sale Order #INV-0003', '2026-09-30 13:14:17', '2026-09-30 13:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `material_requisition_items`
--

CREATE TABLE `material_requisition_items` (
  `id` bigint UNSIGNED NOT NULL,
  `material_requisition_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `required_qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `available_qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `shortage_qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material_stock_movements`
--

CREATE TABLE `material_stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `item_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(15,4) NOT NULL,
  `ref_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_id` bigint UNSIGNED NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `material_stock_movements`
--

INSERT INTO `material_stock_movements` (`id`, `item_type`, `item_id`, `type`, `qty`, `ref_type`, `ref_id`, `note`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\RawMaterial', 132, 'in', 0.0169, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(2, 'App\\Models\\RawMaterial', 141, 'in', 0.2687, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(3, 'App\\Models\\RawMaterial', 144, 'in', 0.0013, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(4, 'App\\Models\\RawMaterial', 150, 'in', 0.2250, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(5, 'App\\Models\\RawMaterial', 139, 'in', 0.0002, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(6, 'App\\Models\\RawMaterial', 22, 'in', 0.2250, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(7, 'App\\Models\\RawMaterial', 28, 'in', 0.1190, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(8, 'App\\Models\\RawMaterial', 25, 'in', 0.2250, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(9, 'App\\Models\\RawMaterial', 40, 'in', 70.0000, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(10, 'App\\Models\\RawMaterial', 159, 'in', 20.0000, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(11, 'App\\Models\\RawMaterial', 52, 'in', 0.2400, 'PURCHASE', 2, 'Purchase Invoice MPUR-001', '2026-09-01 09:29:31', '2026-09-01 09:29:31'),
(12, 'App\\Models\\RawMaterial', 50, 'in', 2.0000, 'PURCHASE', 3, 'Purchase Invoice MPUR-002', '2026-09-05 05:41:15', '2026-09-05 05:41:15'),
(13, 'App\\Models\\RawMaterial', 77, 'in', 1.0000, 'PURCHASE', 3, 'Purchase Invoice MPUR-002', '2026-09-05 05:41:15', '2026-09-05 05:41:15'),
(17, 'App\\Models\\RawMaterial', 26, 'in', 50.0000, 'PURCHASE', 5, 'Purchase Invoice MPUR-003', '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(18, 'App\\Models\\RawMaterial', 22, 'in', 25.0000, 'PURCHASE', 5, 'Purchase Invoice MPUR-003', '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(19, 'App\\Models\\RawMaterial', 12, 'in', 100.0000, 'PURCHASE', 5, 'Purchase Invoice MPUR-003', '2026-09-05 05:49:59', '2026-09-05 05:49:59'),
(20, 'App\\Models\\RawMaterial', 40, 'in', 275.0000, 'PURCHASE', 6, 'Purchase Invoice MPUR-004', '2026-09-05 05:54:31', '2026-09-05 05:54:31'),
(21, 'App\\Models\\RawMaterial', 117, 'in', 10.0000, 'PURCHASE', 7, 'Purchase Invoice MPUR-005', '2026-09-05 06:02:27', '2026-09-05 06:02:27'),
(22, 'App\\Models\\RawMaterial', 87, 'in', 5.0000, 'PURCHASE', 8, 'Purchase Invoice MPUR-006', '2026-09-05 06:06:14', '2026-09-05 06:06:14'),
(23, 'App\\Models\\RawMaterial', 101, 'in', 5.0000, 'PURCHASE', 8, 'Purchase Invoice MPUR-006', '2026-09-05 06:06:14', '2026-09-05 06:06:14'),
(24, 'App\\Models\\RawMaterial', 142, 'in', 0.1000, 'PURCHASE', 9, 'Purchase Invoice MPUR-007', '2026-09-05 06:11:21', '2026-09-05 06:11:21'),
(25, 'App\\Models\\RawMaterial', 147, 'in', 3.0000, 'PURCHASE', 10, 'Purchase Invoice MPUR-008', '2026-09-12 04:21:42', '2026-09-12 04:21:42'),
(26, 'App\\Models\\RawMaterial', 36, 'in', 25.0000, 'PURCHASE', 11, 'Purchase Invoice MPUR-009', '2026-09-12 04:23:33', '2026-09-12 04:23:33'),
(27, 'App\\Models\\RawMaterial', 12, 'in', 200.0000, 'PURCHASE', 12, 'Purchase Invoice MPUR-010', '2026-09-12 04:30:41', '2026-09-12 04:30:41'),
(28, 'App\\Models\\RawMaterial', 117, 'in', 10.0000, 'PURCHASE', 13, 'Purchase Invoice MPUR-011', '2026-09-12 04:31:45', '2026-09-12 04:31:45'),
(29, 'App\\Models\\RawMaterial', 97, 'in', 240000.0000, 'PURCHASE', 14, 'Purchase Invoice MPUR-012', '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(30, 'App\\Models\\RawMaterial', 95, 'in', 240000.0000, 'PURCHASE', 14, 'Purchase Invoice MPUR-012', '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(31, 'App\\Models\\RawMaterial', 96, 'in', 240000.0000, 'PURCHASE', 14, 'Purchase Invoice MPUR-012', '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(32, 'App\\Models\\RawMaterial', 112, 'in', 240000.0000, 'PURCHASE', 14, 'Purchase Invoice MPUR-012', '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(33, 'App\\Models\\PackagingMaterial', 2, 'in', 4000.0000, 'PURCHASE', 14, 'Purchase Invoice MPUR-012', '2026-09-30 05:22:43', '2026-09-30 05:22:43'),
(34, 'App\\Models\\RawMaterial', 97, 'out', 240000.0000, 'PRODUCTION', 1, 'Consumed for Batch #BATCH-2026-0001 (sencid syrp 120ml)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(35, 'App\\Models\\RawMaterial', 95, 'out', 240000.0000, 'PRODUCTION', 1, 'Consumed for Batch #BATCH-2026-0001 (sencid syrp 120ml)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(36, 'App\\Models\\RawMaterial', 96, 'out', 240000.0000, 'PRODUCTION', 1, 'Consumed for Batch #BATCH-2026-0001 (sencid syrp 120ml)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(37, 'App\\Models\\RawMaterial', 112, 'out', 240000.0000, 'PRODUCTION', 1, 'Consumed for Batch #BATCH-2026-0001 (sencid syrp 120ml)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(38, 'App\\Models\\PackagingMaterial', 2, 'out', 4000.0000, 'PRODUCTION', 1, 'Consumed for Batch #BATCH-2026-0001 (sencid syrp 120ml)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(39, 'App\\Models\\RawMaterial', 17, 'in', 1000.0000, 'PURCHASE', 15, 'Purchase Invoice MPUR-013', '2026-09-30 13:16:08', '2026-09-30 13:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_07_18_203013_create_categories_table', 1),
(6, '2025_07_18_215154_create_brands_table', 1),
(7, '2025_07_18_220702_create_units_table', 1),
(8, '2025_07_18_231906_create_subcategories_table', 1),
(9, '2025_07_19_074903_create_products_table', 1),
(10, '2025_07_20_220859_create_vendors_table', 1),
(11, '2025_07_20_220905_create_warehouses_table', 1),
(12, '2025_07_20_232951_create_branches_table', 1),
(13, '2025_07_21_125354_create_customers_table', 1),
(14, '2025_07_21_135411_add_status_to_customers_table', 1),
(15, '2025_07_21_181654_create_zones_table', 1),
(16, '2025_07_21_185626_create_sales_officers_table', 1),
(17, '2025_07_21_195751_create_permission_tables', 1),
(18, '2025_07_21_203215_create_transports_table', 1),
(19, '2025_07_21_213504_create_purchases_table', 1),
(20, '2025_07_21_214736_add_urdu_fields_to_transports_table', 1),
(21, '2025_08_09_220420_create_narrations_table', 1),
(22, '2025_08_09_231230_create_vouchers_table', 1),
(23, '2025_08_11_071804_add_barcode_path_to_products_table', 1),
(24, '2025_08_11_092258_add_missing_columns_to_products_table', 1),
(25, '2025_08_11_133922_add_brand_id_to_products_table', 1),
(26, '2025_08_13_225511_create_purchase_items_table', 1),
(27, '2025_08_13_225620_create_stocks_table', 1),
(28, '2025_08_17_213427_create_table_vendor_ledgers', 1),
(29, '2025_08_17_221914_create_vendor_payments_table', 1),
(30, '2025_08_17_222748_create_vendor_bilties_table', 1),
(31, '2025_08_17_225451_create_customer_ledgers_table', 1),
(32, '2025_08_17_225912_create_customer_payments_table', 1),
(33, '2025_08_20_235830_create_table_sales', 1),
(34, '2025_08_22_122351_create_product_discounts_table', 1),
(35, '2025_08_22_213952_create_warehouse_stocks_table', 1),
(36, '2025_08_22_214859_create_stock_transfers_table', 1),
(37, '2025_08_28_223934_create_inward_gatepasses_table', 1),
(38, '2025_08_28_224111_create_inward_gatepass_items_table', 1),
(39, '2025_08_31_093412_create_product_bookings_table', 1),
(40, '2025_09_02_164836_create_purchase_returns_table', 1),
(41, '2025_09_02_164843_create_purchase_return_items_table', 1),
(42, '2025_09_10_181016_add_opening_balance_to_customer_ledgers_table', 1),
(43, '2025_09_13_012942_add_part_fields_to_products_table', 1),
(44, '2025_09_13_022223_create_product_boms_table', 1),
(45, '2025_09_13_022411_create_stock_movements_table', 1),
(46, '2025_09_13_055335_add_index_and_view_for_onhand', 1),
(47, '2025_11_09_135454_add_is_auto_pluck_to_stock_movements_table', 1),
(48, '2025_11_09_135841_add_ref_uuid_to_stock_movements_table', 1),
(49, '2025_12_19_072030_create_package_types_table', 1),
(50, '2025_12_22_190616_add_columns_to_products_table', 1),
(51, '2025_12_30_020855_create_sessions_table', 1),
(52, '2026_01_16_000000_create_modules_table', 1),
(53, '2026_01_17_000000_create_hr_module_tables', 1),
(54, '2026_01_17_000001_create_designations_table', 1),
(55, '2026_01_17_000002_add_details_to_hr_employees_table', 1),
(56, '2026_01_17_000003_create_hr_employee_documents_table', 1),
(57, '2026_01_17_000004_create_hr_salary_structures_table', 1),
(58, '2026_01_17_000005_add_commission_tiers_to_hr_salary_structures', 1),
(59, '2026_01_17_000006_create_hr_shifts_table', 1),
(60, '2026_01_17_000007_create_hr_holidays_table', 1),
(61, '2026_01_17_000008_update_hr_attendance_system', 1),
(62, '2026_01_17_000009_add_location_to_hr_attendances', 1),
(63, '2026_01_19_140000_change_employee_status_inactive_to_non_active', 1),
(64, '2026_01_19_150000_add_requires_location_to_designations', 1),
(65, '2026_01_19_163000_create_hr_loans_tables', 1),
(66, '2026_01_19_185007_add_face_encoding_to_hr_employees_table', 1),
(67, '2026_01_20_162642_create_biometric_devices_table', 1),
(68, '2026_01_20_162647_add_biometric_fields_to_hr_employees_table', 1),
(69, '2026_01_21_024920_create_hr_settings_table', 1),
(70, '2026_01_21_025224_add_punch_gap_minutes_to_hr_employees_table', 1),
(71, '2026_01_21_162017_add_early_in_to_hr_attendances_table', 1),
(72, '2026_01_22_000000_remove_parts_and_bom', 1),
(73, '2026_01_22_152000_add_name_to_modules_table', 1),
(74, '2026_01_22_204519_add_daily_wages_to_salary_structures_table', 1),
(75, '2026_01_22_211414_add_attendance_deduction_policy_to_salary_structures_table', 1),
(76, '2026_01_23_004309_enhance_hr_payrolls_table', 1),
(77, '2026_01_23_004533_create_hr_payroll_details_table', 1),
(78, '2026_01_23_004536_add_pending_deductions_to_hr_employees', 1),
(79, '2026_01_23_012507_add_carried_forward_to_next_to_hr_payrolls', 1),
(80, '2026_01_23_154045_create_employee_salary_structures_table', 1),
(81, '2026_01_23_164530_update_salary_structures_for_standalone_and_naming', 1),
(82, '2026_01_23_180926_add_is_custom_to_employee_salary_structures', 1),
(83, '2026_01_23_182108_add_parent_id_to_salary_structures', 1),
(84, '2026_01_23_230000_drop_basic_salary_from_employees', 1),
(85, '2026_01_24_161500_modify_products_table_for_m2_pricing', 1),
(86, '2026_01_24_165800_add_extended_size_mode_fields', 1),
(87, '2026_01_24_170900_add_purchase_fields', 1),
(88, '2026_01_25_000000_migrate_legacy_stocks_to_warehouse_stocks', 1),
(89, '2026_01_26_013600_rename_column_in_products_table', 1),
(90, '2026_01_26_183704_add_description_to_customer_ledgers_table', 1),
(91, '2026_01_27_000000_add_extra_columns_to_sales_table', 1),
(92, '2026_01_27_000001_add_loose_pieces_to_sales_table', 1),
(93, '2026_01_27_134746_add_stock_details_to_warehouse_stocks_table', 1),
(94, '2026_01_27_135604_remove_stock_columns_from_products_table', 1),
(95, '2026_01_27_135755_remove_price_from_warehouse_stocks_table', 1),
(96, '2026_01_27_141644_simplify_warehouse_stocks_table', 1),
(97, '2026_01_27_143933_add_pricing_columns_to_products_table', 1),
(98, '2026_01_27_144520_add_boxes_quantity_to_warehouse_stocks', 1),
(99, '2026_01_27_144857_rename_pieces_per_box_to_total_pieces_in_warehouse_stocks', 1),
(100, '2026_01_27_190000_update_sales_and_create_sale_items', 1),
(101, '2026_01_27_191000_align_sale_items_columns', 1),
(102, '2026_01_28_014000_make_legacy_sales_columns_nullable', 1),
(103, '2026_01_28_015500_drop_product_column_from_sales', 1),
(104, '2026_01_28_020000_drop_legacy_sales_columns', 1),
(105, '2026_01_28_030000_add_ids_to_sale_items', 1),
(106, '2026_01_28_040000_refactor_sales_table_final', 1),
(107, '2026_01_28_050000_reorder_sales_columns', 1),
(108, '2026_01_28_070000_add_balance_range_to_customers', 1),
(109, '2026_01_28_194600_add_previous_balance_to_customers', 1),
(110, '2026_01_30_000000_refactor_sale_status_and_items', 1),
(111, '2026_01_30_000001_create_financial_accounts_tables', 1),
(112, '2026_01_30_000002_create_voucher_tables', 1),
(113, '2026_01_31_000003_create_settings_table', 1),
(114, '2026_01_31_000004_create_erp_voucher_system', 1),
(115, '2026_01_31_000005_create_system_notifications_table', 1),
(116, '2026_01_31_030620_add_party_to_journal_entries', 1),
(117, '2026_01_31_205213_add_opening_balance_to_vendors_table', 1),
(118, '2026_02_01_011152_fix_sale_status_column_type', 1),
(119, '2026_02_02_010450_add_voucher_id_to_customer_payments_table', 1),
(120, '2026_02_02_013524_create_system_settings_table', 1),
(121, '2026_02_02_015156_add_can_approve_returns_to_users_table', 1),
(122, '2026_02_02_153600_add_pieces_per_m2_to_products', 1),
(123, '2026_02_02_175600_add_due_date_to_sales_table', 1),
(124, '2026_02_02_181246_create_notifications_table', 1),
(125, '2026_02_02_181921_add_credit_days_to_sales_table', 1),
(126, '2026_02_03_135824_change_total_m2_column_type_in_products_table', 1),
(127, '2026_02_04_184220_add_size_mode_to_sale_items_table', 1),
(128, '2026_02_05_040428_add_stock_fields_to_products_table', 1),
(129, '2026_02_05_042018_change_total_m2_decimal_precision', 1),
(130, '2026_02_06_170933_add_snapshot_columns_to_purchase_items_table', 1),
(131, '2026_02_07_133524_add_purchase_id_to_purchase_returns_table', 1),
(132, '2026_02_07_142845_create_sale_returns_table', 1),
(133, '2026_02_07_143037_create_sale_return_items_table', 1),
(134, '2026_02_07_143751_drop_old_sales_returns_table', 1),
(135, '2026_02_18_110000_add_sales_officer_id_to_customers_table', 1),
(136, '2026_02_22_173050_fix_purchase_items_columns', 1),
(137, '2026_02_24_022723_add_default_discounts_to_products_table', 1),
(138, '2026_02_28_040416_add_reminder_fields_to_customers_table', 1),
(139, '2026_03_06_214616_add_reminder_day_to_customers_table', 1),
(140, '2026_05_01_213524_add_is_active_to_products_table', 1),
(141, '2026_05_20_231443_add_alert_quantity_to_products_table', 1),
(142, '2026_05_21_043429_create_expense_categories_table', 1),
(143, '2026_05_21_043430_add_reference_no_to_expense_vouchers_table', 1),
(144, '2026_05_30_192619_add_alert_carton_quantity_to_products_table', 1),
(145, '2026_06_01_171926_add_is_booking_to_sales_table', 1),
(146, '2026_06_02_190558_add_additional_discount_to_purchases_table', 1),
(147, '2026_07_08_083556_add_walkin_name_to_sales_table', 1),
(148, '2026_07_08_235807_add_social_links_to_settings', 1),
(149, '2026_07_09_044903_add_color_to_sale_return_items_table', 1),
(150, '2026_07_09_050550_create_day_closings_table', 1),
(151, '2026_07_09_054826_create_drawer_transactions_table', 1),
(152, '2026_07_09_165000_add_color_to_purchase_items_and_purchase_return_items', 1),
(153, '2026_07_11_141000_add_weight_and_convert_stock_columns_to_decimal', 1),
(154, '2026_07_13_120000_add_sync_columns_to_sales_and_customers', 1),
(155, '2026_07_17_150000_make_customer_id_nullable_in_sale_returns', 1),
(156, '2026_07_19_020202_add_manual_product_columns', 1),
(157, '2026_07_19_024330_add_manual_product_columns_to_sale_return_items', 1),
(158, '2026_08_03_000000_create_stock_adjustments_table', 1),
(159, '2026_08_05_050000_create_account_histories_table', 1),
(160, '2026_08_06_050000_create_invoice_series_table', 1),
(161, '2026_08_11_164619_create_departments_table', 1),
(162, '2026_08_11_184313_add_columns_to_units_table', 1),
(163, '2026_08_11_215110_create_raw_materials_table', 1),
(164, '2026_08_12_000809_create_packaging_materials_table', 1),
(165, '2026_08_12_002820_create_suppliers_table', 1),
(166, '2026_08_12_003454_add_supplier_fields_to_vendors_table', 1),
(167, '2026_08_12_032114_add_current_stock_to_materials_tables', 1),
(168, '2026_08_12_032137_create_material_purchases_table', 1),
(169, '2026_08_12_032205_create_material_purchase_items_table', 1),
(170, '2026_08_12_032217_create_material_stock_movements_table', 1),
(171, '2026_08_12_220026_add_extended_fields_to_material_purchases_table', 1),
(172, '2026_08_12_220037_add_extended_fields_to_material_purchase_items_table', 1),
(173, '2026_08_12_221101_add_transport_charges_to_material_purchases_table', 1),
(174, '2026_08_13_011655_create_formulations_table', 1),
(175, '2026_08_13_011656_create_formulation_raw_materials_table', 1),
(176, '2026_08_13_011657_create_formulation_packaging_materials_table', 1),
(177, '2026_09_03_034014_update_department_foreign_keys', 2),
(178, '2026_09_03_041016_add_product_type_to_products_table', 2),
(179, '2026_09_03_215313_add_price_to_raw_materials_table', 2),
(180, '2026_09_03_223048_add_bmr_fields_to_formulations_table', 2),
(181, '2026_09_01_064817_add_weekly_off_days_to_hr_shifts_and_employees', 3),
(182, '2026_09_19_000001_create_material_requisitions_table', 4),
(183, '2026_09_19_000002_create_production_batches_table', 4),
(184, '2026_09_19_000003_create_dummy_invoices_tables', 4),
(185, '2026_09_30_010841_make_department_id_nullable_in_raw_materials_table', 5),
(186, '2026_09_30_011400_make_department_id_nullable_in_packaging_materials_table', 5),
(187, '2026_09_30_081600_update_sale_status_column_to_varchar', 6),
(188, '2026_10_03_000000_create_hr_policies_table', 7),
(189, '2026_10_03_000001_add_half_day_deduction_columns_to_hr_policies', 7),
(190, '2026_10_04_000001_add_payment_details_to_hr_payrolls_table', 7),
(191, '2026_10_04_000002_add_loan_deduction_to_hr_payrolls_table', 7);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'home', NULL, NULL),
(2, 'profile', NULL, NULL),
(3, 'products', NULL, NULL),
(4, 'product.bookings', NULL, NULL),
(5, 'discount.products', NULL, NULL),
(6, 'categories', NULL, NULL),
(7, 'subcategories', NULL, NULL),
(8, 'brands', NULL, NULL),
(9, 'units', NULL, NULL),
(10, 'warehouse', NULL, NULL),
(11, 'warehouse.stock', NULL, NULL),
(12, 'stock.transfer', NULL, NULL),
(13, 'stock.adjust', NULL, NULL),
(14, 'stocks', NULL, NULL),
(15, 'purchases', NULL, NULL),
(16, 'purchase.returns', NULL, NULL),
(17, 'vendors', NULL, NULL),
(18, 'vendor.bilties', NULL, NULL),
(19, 'inward.gatepass', NULL, NULL),
(20, 'sales', NULL, NULL),
(21, 'sales.returns', NULL, NULL),
(22, 'customers', NULL, NULL),
(23, 'customer.ledger', NULL, NULL),
(24, 'bookings', NULL, NULL),
(25, 'checkbook', NULL, NULL),
(26, 'chart.of.accounts', NULL, NULL),
(27, 'expense.voucher', NULL, NULL),
(28, 'receipts.voucher', NULL, NULL),
(29, 'journal.voucher', NULL, NULL),
(30, 'payment.voucher', NULL, NULL),
(31, 'income.voucher', NULL, NULL),
(32, 'item.stock.report', NULL, NULL),
(33, 'purchase.report', NULL, NULL),
(34, 'sale.report', NULL, NULL),
(35, 'reporting', NULL, NULL),
(36, 'inventory.onhand', NULL, NULL),
(37, 'users', NULL, NULL),
(38, 'roles', NULL, NULL),
(39, 'permissions', NULL, NULL),
(40, 'branches', NULL, NULL),
(41, 'zones', NULL, NULL),
(42, 'sales.officers', NULL, NULL),
(43, 'narrations', NULL, NULL),
(44, 'package.types', NULL, NULL),
(45, 'hr.departments', NULL, NULL),
(46, 'hr.employees', NULL, NULL),
(47, 'hr.attendance', NULL, NULL),
(48, 'hr.payroll', NULL, NULL),
(49, 'hr.leaves', NULL, NULL),
(50, 'hr.designations', NULL, NULL),
(51, 'hr.shifts', NULL, NULL),
(52, 'hr.holidays', NULL, NULL),
(53, 'hr.salary.structure', NULL, NULL),
(54, 'hr.loans', NULL, NULL),
(55, 'hr.biometric.devices', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `narrations`
--

CREATE TABLE `narrations` (
  `id` bigint UNSIGNED NOT NULL,
  `expense_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `narration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_types`
--

CREATE TABLE `package_types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packaging_materials`
--

CREATE TABLE `packaging_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `packaging_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `variant` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `unit_id` bigint UNSIGNED NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `capacity` decimal(15,4) DEFAULT NULL,
  `capacity_unit_id` bigint UNSIGNED DEFAULT NULL,
  `min_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packaging_materials`
--

INSERT INTO `packaging_materials` (`id`, `name`, `code`, `packaging_type`, `variant`, `current_stock`, `unit_id`, `department_id`, `capacity`, `capacity_unit_id`, `min_stock`, `description`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '15 ML Dropper', 'DROPPER 15ML', 'Bottle', 'tesing', 0.0000, 1, 1, 100000.0000, 1, 1000.0000, NULL, 1, '2026-08-17 10:28:31', '2026-09-19 11:18:21', NULL),
(2, 'Amber Bottle 120 ML', 'PM0001', 'Bottle', 'Plastic', 0.0000, 1, 1, 100000.0000, 1, 5000.0000, NULL, 1, '2026-09-17 10:15:56', '2026-09-30 05:28:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_vouchers`
--

CREATE TABLE `payment_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `pvid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `narration_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_no` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_head` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'home.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(2, 'home.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(3, 'home.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(4, 'home.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(5, 'profile.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(6, 'profile.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(7, 'profile.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(8, 'profile.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(9, 'products.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(10, 'products.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(11, 'products.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(12, 'products.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(13, 'product.bookings.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(14, 'product.bookings.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(15, 'product.bookings.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(16, 'product.bookings.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(17, 'discount.products.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(18, 'discount.products.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(19, 'discount.products.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(20, 'discount.products.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(21, 'categories.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(22, 'categories.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(23, 'categories.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(24, 'categories.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(25, 'subcategories.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(26, 'subcategories.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(27, 'subcategories.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(28, 'subcategories.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(29, 'brands.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(30, 'brands.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(31, 'brands.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(32, 'brands.delete', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(33, 'units.view', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(34, 'units.create', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(35, 'units.edit', 'web', '2026-08-13 07:52:24', '2026-08-13 07:52:24'),
(36, 'units.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(37, 'warehouse.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(38, 'warehouse.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(39, 'warehouse.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(40, 'warehouse.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(41, 'warehouse.stock.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(42, 'warehouse.stock.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(43, 'warehouse.stock.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(44, 'warehouse.stock.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(45, 'stock.transfer.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(46, 'stock.transfer.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(47, 'stock.transfer.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(48, 'stock.transfer.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(49, 'stock.adjust.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(50, 'stock.adjust.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(51, 'stock.adjust.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(52, 'stock.adjust.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(53, 'stocks.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(54, 'stocks.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(55, 'stocks.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(56, 'stocks.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(57, 'purchases.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(58, 'purchases.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(59, 'purchases.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(60, 'purchases.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(61, 'purchase.returns.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(62, 'purchase.returns.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(63, 'purchase.returns.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(64, 'purchase.returns.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(65, 'vendors.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(66, 'vendors.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(67, 'vendors.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(68, 'vendors.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(69, 'vendor.bilties.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(70, 'vendor.bilties.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(71, 'vendor.bilties.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(72, 'vendor.bilties.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(73, 'inward.gatepass.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(74, 'inward.gatepass.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(75, 'inward.gatepass.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(76, 'inward.gatepass.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(77, 'sales.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(78, 'sales.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(79, 'sales.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(80, 'sales.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(81, 'sales.returns.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(82, 'sales.returns.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(83, 'sales.returns.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(84, 'sales.returns.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(85, 'customers.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(86, 'customers.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(87, 'customers.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(88, 'customers.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(89, 'customer.ledger.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(90, 'customer.ledger.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(91, 'customer.ledger.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(92, 'customer.ledger.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(93, 'bookings.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(94, 'bookings.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(95, 'bookings.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(96, 'bookings.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(97, 'checkbook.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(98, 'checkbook.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(99, 'checkbook.edit', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(100, 'checkbook.delete', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(101, 'chart.of.accounts.view', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(102, 'chart.of.accounts.create', 'web', '2026-08-13 07:52:25', '2026-08-13 07:52:25'),
(103, 'chart.of.accounts.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(104, 'chart.of.accounts.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(105, 'expense.voucher.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(106, 'expense.voucher.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(107, 'expense.voucher.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(108, 'expense.voucher.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(109, 'receipts.voucher.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(110, 'receipts.voucher.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(111, 'receipts.voucher.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(112, 'receipts.voucher.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(113, 'journal.voucher.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(114, 'journal.voucher.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(115, 'journal.voucher.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(116, 'journal.voucher.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(117, 'payment.voucher.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(118, 'payment.voucher.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(119, 'payment.voucher.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(120, 'payment.voucher.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(121, 'income.voucher.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(122, 'income.voucher.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(123, 'income.voucher.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(124, 'income.voucher.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(125, 'item.stock.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(126, 'item.stock.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(127, 'item.stock.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(128, 'item.stock.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(129, 'purchase.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(130, 'purchase.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(131, 'purchase.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(132, 'purchase.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(133, 'sale.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(134, 'sale.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(135, 'sale.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(136, 'sale.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(137, 'reporting.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(138, 'reporting.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(139, 'reporting.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(140, 'reporting.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(141, 'recovery.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(142, 'recovery.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(143, 'recovery.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(144, 'recovery.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(145, 'payable.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(146, 'payable.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(147, 'payable.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(148, 'payable.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(149, 'parties.balance.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(150, 'parties.balance.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(151, 'parties.balance.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(152, 'parties.balance.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(153, 'aging.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(154, 'aging.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(155, 'aging.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(156, 'aging.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(157, 'balance.sheet.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(158, 'balance.sheet.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(159, 'balance.sheet.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(160, 'balance.sheet.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(161, 'profit.loss.report.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(162, 'profit.loss.report.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(163, 'profit.loss.report.edit', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(164, 'profit.loss.report.delete', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(165, 'inventory.onhand.view', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(166, 'inventory.onhand.create', 'web', '2026-08-13 07:52:26', '2026-08-13 07:52:26'),
(167, 'inventory.onhand.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(168, 'inventory.onhand.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(169, 'vendor.ledger.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(170, 'vendor.ledger.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(171, 'vendor.ledger.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(172, 'vendor.ledger.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(173, 'users.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(174, 'users.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(175, 'users.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(176, 'users.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(177, 'roles.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(178, 'roles.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(179, 'roles.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(180, 'roles.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(181, 'permissions.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(182, 'permissions.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(183, 'permissions.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(184, 'permissions.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(185, 'branches.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(186, 'branches.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(187, 'branches.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(188, 'branches.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(189, 'zones.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(190, 'zones.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(191, 'zones.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(192, 'zones.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(193, 'sales.officers.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(194, 'sales.officers.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(195, 'sales.officers.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(196, 'sales.officers.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(197, 'narrations.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(198, 'narrations.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(199, 'narrations.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(200, 'narrations.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(201, 'executive.report.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(202, 'executive.report.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(203, 'executive.report.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(204, 'executive.report.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(205, 'package.types.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(206, 'package.types.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(207, 'package.types.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(208, 'package.types.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(209, 'hr.departments.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(210, 'hr.departments.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(211, 'hr.departments.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(212, 'hr.departments.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(213, 'hr.employees.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(214, 'hr.employees.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(215, 'hr.employees.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(216, 'hr.employees.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(217, 'hr.attendance.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(218, 'hr.attendance.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(219, 'hr.attendance.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(220, 'hr.attendance.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(221, 'hr.payroll.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(222, 'hr.payroll.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(223, 'hr.payroll.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(224, 'hr.payroll.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(225, 'hr.leaves.view', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(226, 'hr.leaves.create', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(227, 'hr.leaves.edit', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(228, 'hr.leaves.delete', 'web', '2026-08-13 07:52:27', '2026-08-13 07:52:27'),
(229, 'hr.designations.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(230, 'hr.designations.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(231, 'hr.designations.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(232, 'hr.designations.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(233, 'hr.shifts.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(234, 'hr.shifts.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(235, 'hr.shifts.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(236, 'hr.shifts.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(237, 'hr.holidays.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(238, 'hr.holidays.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(239, 'hr.holidays.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(240, 'hr.holidays.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(241, 'hr.salary.structure.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(242, 'hr.salary.structure.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(243, 'hr.salary.structure.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(244, 'hr.salary.structure.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(245, 'hr.loans.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(246, 'hr.loans.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(247, 'hr.loans.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(248, 'hr.loans.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(249, 'hr.biometric.devices.view', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(250, 'hr.biometric.devices.create', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(251, 'hr.biometric.devices.edit', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28'),
(252, 'hr.biometric.devices.delete', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `production_batches`
--

CREATE TABLE `production_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL DEFAULT '1',
  `quantity` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `mfg_date` date DEFAULT NULL,
  `exp_date` date DEFAULT NULL,
  `status` enum('produced','ready_for_delivery','dispatched') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'produced',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_batches`
--

INSERT INTO `production_batches` (`id`, `batch_no`, `product_id`, `sale_id`, `warehouse_id`, `quantity`, `mfg_date`, `exp_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'BATCH-2026-0001', 6, 4, 1, 4000.0000, '2026-09-30', '2028-09-30', 'dispatched', NULL, '2026-09-30 05:28:13', '2026-09-30 05:29:04');

-- --------------------------------------------------------

--
-- Table structure for table `production_batch_items`
--

CREATE TABLE `production_batch_items` (
  `id` bigint UNSIGNED NOT NULL,
  `production_batch_id` bigint UNSIGNED NOT NULL,
  `raw_material_id` bigint UNSIGNED NOT NULL,
  `consumed_qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `production_batch_items`
--

INSERT INTO `production_batch_items` (`id`, `production_batch_id`, `raw_material_id`, `consumed_qty`, `unit`, `created_at`, `updated_at`) VALUES
(1, 1, 97, 240000.0000, 'KG', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(2, 1, 95, 240000.0000, 'KG', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(3, 1, 96, 240000.0000, 'KG', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(4, 1, 112, 240000.0000, 'KG', '2026-09-30 05:28:13', '2026-09-30 05:28:13');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `creater_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `sub_category_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `is_part` tinyint(1) NOT NULL DEFAULT '0',
  `is_assembled` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `item_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Finished Good',
  `size_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'by_size',
  `height` decimal(8,2) DEFAULT NULL COMMENT 'Height in cm',
  `width` decimal(8,2) DEFAULT NULL COMMENT 'Width in cm',
  `weight_per_piece` decimal(12,4) DEFAULT '0.0000',
  `pieces_per_box` int NOT NULL DEFAULT '0',
  `pieces_per_m2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `alert_quantity` int DEFAULT NULL,
  `alert_carton_quantity` int DEFAULT NULL,
  `total_m2` decimal(10,2) NOT NULL,
  `price_per_m2` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sale_price_per_box` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Used for By Cartons and By Pieces',
  `purchase_price_per_piece` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT 'Used for By Cartons and By Pieces',
  `purchase_price_per_box` decimal(15,2) DEFAULT '0.00',
  `sale_price_per_piece` decimal(15,2) DEFAULT '0.00',
  `wholesale_price` decimal(12,2) DEFAULT '0.00',
  `purchase_price_per_m2` decimal(12,2) NOT NULL DEFAULT '0.00',
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `barcode_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hs_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `boxes_quantity` int DEFAULT '0',
  `loose_pieces` int DEFAULT '0',
  `piece_quantity` int DEFAULT '0',
  `total_stock_qty` decimal(10,2) DEFAULT '0.00',
  `purchase_discount_percent` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sale_discount_percent` decimal(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `creater_id`, `category_id`, `sub_category_id`, `brand_id`, `is_part`, `is_assembled`, `is_active`, `item_code`, `unit_id`, `item_name`, `product_type`, `size_mode`, `height`, `width`, `weight_per_piece`, `pieces_per_box`, `pieces_per_m2`, `alert_quantity`, `alert_carton_quantity`, `total_m2`, `price_per_m2`, `sale_price_per_box`, `purchase_price_per_piece`, `purchase_price_per_box`, `sale_price_per_piece`, `wholesale_price`, `purchase_price_per_m2`, `color`, `created_at`, `updated_at`, `deleted_at`, `barcode_path`, `image`, `model`, `hs_code`, `boxes_quantity`, `loose_pieces`, `piece_quantity`, `total_stock_qty`, `purchase_discount_percent`, `sale_discount_percent`) VALUES
(1, '1', '1', '5', 1, 0, 0, 0, 'ITEM-0001', '1', 'Formal Shirt', 'Finished Good', 'by_cartons', 0.00, 0.00, 0.0000, 12, 0.00, NULL, NULL, 0.00, 0.00, 5000.00, 375.00, 4500.00, 416.67, 0.00, 0.00, '[\"Black\"]', '2026-08-13 07:52:23', '2026-09-02 20:37:30', NULL, '317811185474', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(2, '1', '2', NULL, 2, 0, 0, 0, 'ITEM-0002', NULL, 'Wheat Flour', 'Finished Good', 'by_pieces', 0.00, 0.00, 1000.0000, 1, 0.00, NULL, 0, 0.00, 0.00, 1000.00, 0.00, 0.00, 1000.00, 900.00, 0.00, '[{\"name\":\"Wheat Flour\",\"size\":\"10kg\",\"color\":\"-\",\"stock\":10,\"sale_price\":\"1000\",\"wholesale_price\":\"900\",\"weight_per_piece\":\"1000\",\"purch_price\":\"0\",\"alert\":\"0\",\"barcode\":\"987296\",\"conv_factor\":1,\"is_base_variant\":\"1\",\"unit\":\"Pcs\"},{\"name\":\"Wheat Flour\",\"size\":\"15kg\",\"color\":\"-\",\"stock\":10,\"sale_price\":\"1500\",\"wholesale_price\":\"1400\",\"weight_per_piece\":0,\"purch_price\":\"0\",\"alert\":\"0\",\"barcode\":\"205140\",\"conv_factor\":1,\"is_base_variant\":\"0\",\"unit\":\"Pcs\"},{\"name\":\"Wheat Flour\",\"size\":\"20kg\",\"color\":\"-\",\"stock\":20,\"sale_price\":\"2000\",\"wholesale_price\":\"1900\",\"weight_per_piece\":0,\"purch_price\":\"0\",\"alert\":\"0\",\"barcode\":\"690326\",\"conv_factor\":1,\"is_base_variant\":\"0\",\"unit\":\"Pcs\"},{\"name\":\"Wheat Flour\",\"size\":\"5kg\",\"color\":\"-\",\"stock\":20,\"sale_price\":\"600\",\"wholesale_price\":\"500\",\"weight_per_piece\":0,\"purch_price\":\"0\",\"alert\":\"0\",\"barcode\":\"800001\",\"conv_factor\":1,\"is_base_variant\":\"0\",\"unit\":\"Pcs\"}]', '2026-08-17 10:26:27', '2026-09-02 20:37:34', NULL, '487262906889', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(3, '1', '3', '14', 2, 0, 0, 1, 'ITEM-0003', 'mL', 'Panadol serup', 'Finished Good', 'by_pieces', 0.00, 0.00, 0.0000, 1, 0.00, 10, 5, 0.00, 0.00, 200.00, 100.00, 100.00, 200.00, 150.00, 0.00, NULL, '2026-09-05 20:12:07', '2026-09-05 20:12:07', NULL, '504646116566', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(4, '1', '7', '36', 1, 0, 0, 1, 'ITEM-0004', '1', 'Si-Cid Syrup', 'Finished Good', 'by_pieces', 0.00, 0.00, 0.0000, 1, 0.00, 50, 5, 0.00, 0.00, 37.00, 0.00, 0.00, 37.00, 250.00, 0.00, NULL, '2026-09-19 09:59:51', '2026-09-19 09:59:51', NULL, '298963016674', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(5, '1', '9', NULL, 1, 0, 0, 1, 'ITEM-0005', 'Liter', 'Reviter Syrup', 'Finished Good', 'by_pieces', 0.00, 0.00, 0.0000, 1, 0.00, 100, 5, 0.00, 0.00, 50.00, 0.00, 0.00, 50.00, 0.00, 0.00, NULL, '2026-09-19 11:33:02', '2026-09-19 11:33:02', NULL, '600113586120', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(6, '1', '6', NULL, 1, 0, 0, 1, 'ITEM-0006', '1', 'sencid syrp 120ml', 'Raw Material', 'by_pieces', 0.00, 0.00, 0.0000, 1, 0.00, 10, 5, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55', NULL, '632624275560', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00),
(7, '1', '9', NULL, 2, 0, 0, 1, 'ITEM-0007', '1', 'Zor-D Tablet', 'Finished Good', 'by_pieces', 0.00, 0.00, 0.0000, 1, 0.00, 10, 5, 0.00, 0.00, 110.00, 0.00, 0.00, 110.00, 0.00, 0.00, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31', NULL, '633861123536', NULL, NULL, NULL, 0, 0, 0, 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_bookings`
--

CREATE TABLE `product_bookings` (
  `id` bigint UNSIGNED NOT NULL,
  `customer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `product` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_discount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `per_total` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_amount_Words` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_bill_amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_extradiscount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_net` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cash` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `card` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `change` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_date` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_discounts`
--

CREATE TABLE `product_discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `actual_price` decimal(10,2) DEFAULT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) NOT NULL,
  `total_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `date` date NOT NULL DEFAULT '2026-08-13',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_date` date DEFAULT NULL,
  `invoice_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `additional_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `extra_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `due_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status_purchase` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `size_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pieces_per_box` decimal(12,2) NOT NULL DEFAULT '1.00',
  `pieces_per_m2` decimal(12,2) NOT NULL DEFAULT '0.00',
  `boxes_qty` decimal(12,2) NOT NULL DEFAULT '0.00',
  `loose_qty` decimal(12,2) NOT NULL DEFAULT '0.00',
  `length` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `width` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `item_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `qty` int NOT NULL DEFAULT '0',
  `line_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `return_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_date` date NOT NULL,
  `return_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `transport` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `driver_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `bill_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `item_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `extra_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(12,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(12,2) NOT NULL DEFAULT '0.00',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_items`
--

CREATE TABLE `purchase_return_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_return_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `item_discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_materials`
--

CREATE TABLE `raw_materials` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `min_stock` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `reorder_level` decimal(15,4) NOT NULL DEFAULT '0.0000',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `raw_materials`
--

INSERT INTO `raw_materials` (`id`, `name`, `code`, `department_id`, `unit_id`, `type`, `current_stock`, `min_stock`, `reorder_level`, `description`, `price`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'WHEAT', 'WH-12', 1, 2, 'Other', 0.0000, 100.0000, 100.0000, NULL, 0.00, 1, '2026-08-17 10:21:15', '2026-08-31 11:50:15', '2026-08-31 11:50:15'),
(2, 'Sugar', 'SP0001', 1, 2, 'Ingredient', 0.0000, 1.0000, 18.0000, NULL, 0.00, 1, '2026-08-19 09:38:59', '2026-08-31 11:50:08', '2026-08-31 11:50:08'),
(3, 'Citric Acid', 'SP0002', 1, 2, 'Chemical', 0.0000, 1.0000, 25.0000, 's', 0.00, 1, '2026-08-19 09:39:44', '2026-08-31 11:50:03', '2026-08-31 11:50:03'),
(4, 'Ginger Extract', 'SP0003', 1, 2, 'Liquid', 0.0000, 1.0000, 20.0000, NULL, 0.00, 1, '2026-08-19 09:42:50', '2026-08-31 11:49:59', '2026-08-31 11:49:59'),
(5, 'citric Acid', 'CA', 1, 2, 'Chemical', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-08-31 11:12:32', '2026-08-31 11:49:54', '2026-08-31 11:49:54'),
(6, 'Polysucralose', 'RM-004', 1, 2, 'Powder', 0.0000, 10.0000, 40.0000, NULL, 0.00, 1, '2026-08-31 11:17:49', '2026-08-31 11:49:50', '2026-08-31 11:49:50'),
(7, 'Aerosil', 'RM001', 1, 2, 'Powder', 0.0000, 10.0000, 5.0000, NULL, 0.00, 1, '2026-08-31 11:51:31', '2026-09-01 04:31:14', NULL),
(8, 'Aspartame', 'RM002', 1, 2, 'Powder', 0.0000, 20.0000, 1000.0000, NULL, 0.00, 1, '2026-08-31 11:52:37', '2026-08-31 12:10:06', NULL),
(9, 'Benzoic Acid', 'RM003', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-08-31 12:00:32', '2026-08-31 12:22:58', NULL),
(10, 'Citric Acid (Monohydrous)', 'RM005', 1, 2, 'Powder', 0.0000, 25.0000, 5.0000, NULL, 0.00, 1, '2026-08-31 12:05:03', '2026-08-31 12:21:36', NULL),
(11, 'Citric Acid (Anhydrous)', 'RM006', 1, 2, 'Powder', 0.0000, 100.0000, 3.0000, NULL, 0.00, 1, '2026-08-31 12:08:29', '2026-09-01 04:38:20', NULL),
(12, 'Dextrose Anhydrous', 'RM007', 1, 2, 'Powder', 300.0000, 200.0000, 10.0000, NULL, 0.00, 1, '2026-08-31 12:12:51', '2026-09-12 04:30:41', NULL),
(13, 'Gelatin', 'RM009', 1, 2, 'Powder', 0.0000, 25.0000, 4.0000, NULL, 0.00, 1, '2026-08-31 12:14:38', '2026-09-01 04:43:15', NULL),
(14, 'HPMC', 'RM010', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-08-31 12:17:27', '2026-09-01 04:43:37', NULL),
(15, 'Iso Propyl Alcohal', 'RM012', 1, 4, 'Liquid', 0.0000, 165.0000, 15.0000, NULL, 0.00, 1, '2026-08-31 12:19:10', '2026-09-01 04:49:06', NULL),
(16, 'Liquid Glucose', 'RM013', 1, 2, 'Liquid', 0.0000, 100.0000, 2.0000, NULL, 0.00, 1, '2026-08-31 12:25:26', '2026-09-01 04:46:29', NULL),
(17, 'Calcium Carbonate', 'RM004', 1, 2, 'Powder', 1000.0000, 2000.0000, 50.0000, NULL, 0.00, 1, '2026-09-01 04:32:34', '2026-09-30 13:16:08', NULL),
(18, 'EDTA', 'RM008', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:42:02', '2026-09-01 04:42:02', NULL),
(19, 'Icing Sugar', 'RM011', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:48:42', '2026-09-01 04:48:42', NULL),
(20, 'Magnesium Stearate', 'RM014', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:50:02', '2026-09-01 04:50:02', NULL),
(21, 'Menthol Crystals', 'RM015', 1, 2, 'Powder', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:51:21', '2026-09-01 04:51:21', NULL),
(22, 'Methyl Paraben', 'RM016', 1, 2, 'Powder', 25.2250, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:54:34', '2026-09-05 05:49:59', NULL),
(23, 'Maltodextrin', 'RM017', 1, 2, 'Powder', 0.0000, 50.0000, 10.0000, NULL, 0.00, 1, '2026-09-01 04:56:07', '2026-09-01 04:56:07', NULL),
(24, 'Oil', 'RM018', 1, 4, 'Powder', 0.0000, 30.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:57:27', '2026-09-01 04:57:27', NULL),
(25, 'Potassium Sorbate', 'RM019', 1, 2, 'Powder', 0.2250, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 04:59:16', '2026-09-01 09:29:31', NULL),
(26, 'Polysucralose', 'RM020', 1, 2, 'Powder', 50.0000, 50.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:00:29', '2026-09-05 05:49:59', NULL),
(27, 'PVPK30', 'RM021', 1, 2, 'Powder', 0.0000, 252.0000, 0.0000, NULL, 0.00, 1, '2026-09-01 05:02:01', '2026-09-01 05:02:01', NULL),
(28, 'Propyl Paraben', 'RM022', 1, 2, 'Powder', 0.1190, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:05:13', '2026-09-01 09:29:31', NULL),
(29, 'P&G', 'RM023', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:06:07', '2026-09-01 05:06:07', NULL),
(30, 'Sodium Benzoate', 'RM024', 1, 2, 'Powder', 0.0000, 25.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 05:07:07', '2026-09-01 05:07:07', NULL),
(31, 'Sodium CMC', 'RM025', 1, 2, 'Powder', 0.0000, 100.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:08:34', '2026-09-01 05:08:34', NULL),
(32, 'Starch', 'RM026', 1, 2, 'Powder', 0.0000, 200.0000, 15.0000, NULL, 0.00, 1, '2026-09-01 05:10:42', '2026-09-01 05:10:42', NULL),
(33, 'Sodium Hydroxide', 'RM027', 1, 2, 'Other', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:12:31', '2026-09-01 05:12:31', NULL),
(34, 'Sodium Bicarbonate', 'RM028', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:14:10', '2026-09-01 05:14:10', NULL),
(35, 'Sodium Saccharine', 'RM029', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:15:20', '2026-09-01 05:15:20', NULL),
(36, 'Sodium Cyclamate', 'RM030', 1, 2, 'Powder', 25.0000, 25.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 05:16:19', '2026-09-12 04:23:33', NULL),
(37, 'Sugar', 'RM031', 1, 2, 'Other', 0.0000, 50.0000, 10.0000, NULL, 0.00, 1, '2026-09-01 05:17:28', '2026-09-01 05:17:28', NULL),
(38, 'Skimmed Milk', 'RM032', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:18:30', '2026-09-01 05:18:30', NULL),
(39, 'Sodium Citrate', 'RM033', 1, 2, 'Powder', 0.0000, 50.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:20:02', '2026-09-01 05:20:02', NULL),
(40, 'Sorbitol Liquid', 'RM034', 1, 4, 'Liquid', 345.0000, 200.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:23:10', '2026-09-05 05:54:31', NULL),
(41, 'Titanium Dioxide', 'RM035', 1, 2, 'Powder', 0.0000, 25.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:24:09', '2026-09-01 05:24:09', NULL),
(42, 'Tartaric Acid', 'RM036', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:25:09', '2026-09-01 05:25:09', NULL),
(43, 'Talcum Powder', 'RM037', 1, 2, 'Powder', 0.0000, 25.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 05:26:04', '2026-09-01 05:26:04', NULL),
(44, 'Xanthan GUM', 'RM038', 1, 2, 'Powder', 0.0000, 100.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:26:57', '2026-09-01 05:26:57', NULL),
(45, 'Apple # 1 Flavor Syrup', 'RM039', 1, 4, 'Liquid', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:35:12', '2026-09-01 05:35:12', NULL),
(46, 'Apple Green Color for Syrup', 'RM40', 1, 2, 'Powder', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 05:36:32', '2026-09-01 05:36:32', NULL),
(47, 'Banana # 1 Syrup Flavor', 'RM041', 1, 4, 'Liquid', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:39:11', '2026-09-01 05:39:11', NULL),
(48, 'Banana Dry Flavor', 'RM042', 1, 2, 'Powder', 0.0000, 3.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:40:45', '2026-09-01 05:40:45', NULL),
(49, 'Caramel Color Liquid', 'RM043', 1, 4, 'Liquid', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:41:43', '2026-09-01 05:41:43', NULL),
(50, 'Chocolate 50 flavor for syrup', 'RM044', 1, 4, 'Liquid', 2.0000, 2.0000, 5.0000, NULL, 0.00, 1, '2026-09-01 05:43:14', '2026-09-05 05:41:15', NULL),
(51, 'Chocolate Brown Color for Syrup', 'RM045', 1, 2, 'Powder', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 05:44:37', '2026-09-01 05:44:37', NULL),
(52, 'Condensed Milk Flavor For Syrup', 'RM046', 1, 4, 'Liquid', 0.2400, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:45:58', '2026-09-01 09:29:31', NULL),
(53, 'Falsa Dry Flavor', 'RM047', 1, 2, 'Powder', 0.0000, 6.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:47:00', '2026-09-01 05:47:00', NULL),
(54, 'Fanty Dry Flavor', 'RM448', 1, 2, 'Powder', 0.0000, 1.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 05:50:26', '2026-09-01 05:50:26', NULL),
(55, 'Grape # 1 Flavor For Syrup', 'RM049', 1, 4, 'Liquid', 0.0000, 1.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:51:39', '2026-09-01 05:51:39', NULL),
(56, 'Grape Dry Flavor', 'RM050', 1, 2, 'Powder', 0.0000, 1.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:52:48', '2026-09-01 05:52:48', NULL),
(57, 'Honey SP Flavor For Syrup', 'RM051', 1, 4, 'Liquid', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 05:54:06', '2026-09-01 05:54:06', NULL),
(58, 'Lemon SP Flavor For Syrup', 'RM052', 1, 4, 'Liquid', 0.0000, 10.5000, 0.0000, NULL, 0.00, 1, '2026-09-01 05:55:09', '2026-09-01 05:55:09', NULL),
(59, 'Lemon Dry Flavor', 'RM053', 1, 2, 'Powder', 0.0000, 3.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 05:56:23', '2026-09-01 05:56:23', NULL),
(60, 'Lake Brown Color For Tablet', 'RM054', 1, 2, 'Powder', 0.0000, 1.0000, 0.3000, NULL, 0.00, 1, '2026-09-01 05:57:30', '2026-09-01 05:57:30', NULL),
(61, 'Lake Sunset Yellow Color For Tablet', 'RM055', 1, 2, 'Powder', 0.0000, 1.0000, 0.3000, NULL, 0.00, 1, '2026-09-01 05:59:04', '2026-09-01 05:59:04', NULL),
(62, 'Lake Tartrazine Color For Tablet', 'RM056', 1, 2, 'Powder', 0.0000, 1.0000, 0.3000, NULL, 0.00, 1, '2026-09-01 06:00:16', '2026-09-01 06:00:16', NULL),
(63, 'Lime Juice Yellow Color For Tablet', 'RM057', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:01:28', '2026-09-01 06:01:28', NULL),
(64, 'Lime Juice & Soda Color For Syrup', 'RM058', 1, 2, 'Powder', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 06:03:11', '2026-09-01 06:03:11', NULL),
(65, 'Mango SP Flavor For Syrup', 'RM059', 1, 4, 'Liquid', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:04:11', '2026-09-01 06:04:11', NULL),
(66, 'Orange Dry Flavor', 'RM060', 1, 2, 'Powder', 0.0000, 6.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:05:15', '2026-09-01 06:05:15', NULL),
(67, 'Orange Red Color For Syrup', 'RM061', 1, 2, 'Powder', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:06:37', '2026-09-01 06:06:37', NULL),
(68, 'Peppermint Flavor For Syrup', 'RM062', 1, 4, 'Liquid', 0.0000, 2.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 06:07:49', '2026-09-01 06:07:49', NULL),
(69, 'Pineapple SP Flavor For Syrup', 'RM063', 1, 4, 'Liquid', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 06:09:08', '2026-09-01 06:09:08', NULL),
(70, 'Raspberry Red Color For Syrup', 'RM064', 1, 2, 'Powder', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:12:42', '2026-09-01 06:12:42', NULL),
(71, 'Raspberry Red Flavor For Syrup', 'RM065', 1, 4, 'Liquid', 0.0000, 1.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:14:14', '2026-09-01 06:14:14', NULL),
(72, 'Spearmint Gum Flavor For Syrup', 'RMO66', 1, 4, 'Liquid', 0.0000, 2.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 06:15:39', '2026-09-01 06:15:39', NULL),
(73, 'Strawberry NO-1 Flavor For Syrup', 'RM067', 1, 4, 'Liquid', 0.0000, 1.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:16:59', '2026-09-01 06:16:59', NULL),
(74, 'Sweet Orange #1 Flavor For Syrup', 'RM068', 1, 4, 'Liquid', 0.0000, 10.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:18:37', '2026-09-01 06:18:37', NULL),
(75, 'Strawberry Dry Flavor', 'RM069', 1, 2, 'Powder', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 06:19:33', '2026-09-01 06:19:33', NULL),
(76, 'Tutti Frutti # 1 Flavor For Syrup', 'RM070', 1, 4, 'Liquid', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:20:52', '2026-09-01 06:20:52', NULL),
(77, 'Vanilla Dry Flavor', 'RM071', 1, 2, 'Powder', 1.0000, 3.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:22:11', '2026-09-05 05:41:15', NULL),
(78, 'Acetyl-L-Carnitine', 'RM072', 1, 2, 'Powder', 0.0000, 5.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 06:29:03', '2026-09-01 06:29:03', NULL),
(79, 'Alpha Lipoic Acid', 'RM073', 1, 2, 'Powder', 0.0000, 3.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:30:15', '2026-09-01 06:30:15', NULL),
(80, 'Bacopa Ext', 'RM074', 1, 2, 'Powder', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:31:13', '2026-09-01 06:31:13', NULL),
(81, 'Bifidobacterium Animalis', 'RM075', 1, 2, 'Powder', 0.0000, 3.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:33:16', '2026-09-01 06:33:16', NULL),
(82, 'Chamomile Ext', 'RM076', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:34:21', '2026-09-01 06:34:21', NULL),
(83, 'Curcumin Ext', 'RMO77', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:35:10', '2026-09-01 06:35:10', NULL),
(84, 'Colustrum API', 'RM078', 1, 2, 'Powder', 0.0000, 3.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:36:31', '2026-09-01 06:36:31', NULL),
(85, 'Co-Enzyme Q10', 'RM079', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:37:39', '2026-09-01 06:37:39', NULL),
(86, 'Calcium Lactate', 'RM80', 1, 4, 'Powder', 0.0000, 25.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 06:38:41', '2026-09-01 06:38:41', NULL),
(87, 'Cranberry Extract', 'RM081', 1, 2, 'Powder', 5.0000, 10.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 06:40:01', '2026-09-05 06:06:14', NULL),
(88, 'Choline Bitratrate', 'RM082', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:41:50', '2026-09-01 06:41:50', NULL),
(89, 'Chromium Picolinate', 'RM083', 1, 2, 'Powder', 0.0000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 06:43:43', '2026-09-01 06:43:43', NULL),
(90, 'Eggshell Membrane', 'RM084', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:44:58', '2026-09-01 06:44:58', NULL),
(91, 'Damiana Extract', 'RM085', 1, 2, 'Powder', 0.0000, 3.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:46:05', '2026-09-01 06:46:05', NULL),
(92, 'Omega-3', 'RM086', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:47:21', '2026-09-01 06:47:21', NULL),
(93, 'D-Chiro Inositol', 'RM087', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:48:29', '2026-09-01 06:48:29', NULL),
(94, 'Dil Seed Ext Oil', 'RM088', 1, 4, 'Liquid', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 06:49:50', '2026-09-01 06:49:50', NULL),
(95, 'Fennel Extract', 'RMO89', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 06:50:37', '2026-09-30 05:28:13', NULL),
(96, 'Fenugreek Extract', 'RM090', 1, 2, 'Powder', 0.0000, 1.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 06:51:44', '2026-09-30 05:28:13', NULL),
(97, 'Ginger Extract', 'RM091', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:53:55', '2026-09-30 05:28:13', NULL),
(98, 'Ginseng Extract', 'RM092', 1, 2, 'Powder', 0.0000, 5.0000, 0.7000, NULL, 0.00, 1, '2026-09-01 06:54:48', '2026-09-01 06:54:48', NULL),
(99, 'Ginkgo Biloba Ext', 'RM093', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:56:08', '2026-09-01 06:56:08', NULL),
(100, 'Horse Chestnut Ext', 'RM094', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 06:57:04', '2026-09-01 06:57:04', NULL),
(101, 'Ivy Leaf Extract', 'RM095', 1, 2, 'Powder', 5.0000, 6.0000, 1.5000, NULL, 0.00, 1, '2026-09-01 06:58:22', '2026-09-05 06:06:14', NULL),
(102, 'Iron Bisglycinate', 'RM096', 1, 2, 'Powder', 0.0000, 10.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 07:00:09', '2026-09-01 07:00:09', NULL),
(103, 'L-Taurine', 'RM097', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:01:06', '2026-09-01 07:01:06', NULL),
(104, 'Lactase Enzyme', 'RM098', 1, 2, 'Powder', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 07:02:27', '2026-09-01 07:02:27', NULL),
(105, 'L-Lysine Mono HCL', 'RM099', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:03:16', '2026-09-01 07:03:16', NULL),
(106, 'Licorice Extract', 'RM100', 1, 2, 'Powder', 0.0000, 3.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:04:11', '2026-09-01 07:04:11', NULL),
(107, 'L-Arginine', 'RM101', 1, 2, 'Powder', 0.0000, 15.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:05:13', '2026-09-01 07:05:13', NULL),
(108, 'L-Methlfolate', 'RM102', 1, 2, 'Powder', 0.0000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:06:37', '2026-09-01 07:06:37', NULL),
(109, 'Lactobacillus Reutri', 'RM103', 1, 2, 'Powder', 0.0000, 3.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 07:08:21', '2026-09-01 07:08:21', NULL),
(110, 'Lactobacillus Rhamnosus', 'RM104', 1, 2, 'Powder', 0.0000, 3.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 07:10:34', '2026-09-01 07:10:34', NULL),
(111, 'Lactobacillus Acidophilus', 'RM105', 1, 2, 'Powder', 0.0000, 3.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 07:11:47', '2026-09-01 07:11:47', NULL),
(112, 'Mentha Extract', 'RM106', 1, 2, 'Powder', 0.0000, 3.0000, 0.3000, NULL, 0.00, 1, '2026-09-01 07:12:48', '2026-09-30 05:28:13', NULL),
(113, 'Milk Thistle Extract', 'RM107', 1, 2, 'Powder', 0.0000, 2.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 07:13:47', '2026-09-01 07:13:47', NULL),
(114, 'Maca Root Extract', 'RM108', 1, 2, 'Powder', 0.0000, 2.0000, 0.3000, NULL, 0.00, 1, '2026-09-01 07:14:43', '2026-09-01 07:14:43', NULL),
(115, 'MSM', 'RM109', 1, 2, 'Powder', 0.0000, 7.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:15:28', '2026-09-01 07:15:28', NULL),
(116, 'Magnesium Oxide', 'RM110', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:16:42', '2026-09-01 07:16:42', NULL),
(117, 'Myo-Inositol', 'RM111', 1, 2, 'Powder', 20.0000, 25.0000, 3.0000, NULL, 0.00, 1, '2026-09-01 07:18:25', '2026-09-12 04:31:45', NULL),
(118, 'Magnesium Sulfate', 'RM112', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:20:15', '2026-09-01 07:20:15', NULL),
(119, 'Myristica Fragrance Oil', 'RM113', 1, 4, 'Liquid', 0.0000, 0.2000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:21:44', '2026-09-01 07:23:11', NULL),
(120, 'Potassium Chloride', 'RM114', 1, 2, 'Powder', 0.0000, 100.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 07:24:24', '2026-09-01 07:24:24', NULL),
(121, 'Probiotics 3 Blend', 'RM115', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:25:48', '2026-09-01 07:25:48', NULL),
(122, 'Potassium Citrate', 'RM116', 1, 2, 'Powder', 0.0000, 100.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:27:08', '2026-09-01 07:27:08', NULL),
(123, 'Royal Jelly', 'RM117', 1, 2, 'Powder', 0.0000, 3.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 07:28:35', '2026-09-01 07:28:35', NULL),
(124, 'Sodium Chloride Anhydrous', 'RM118', 1, 2, 'Powder', 0.0000, 100.0000, 2.0000, NULL, 0.00, 1, '2026-09-01 07:29:49', '2026-09-01 07:29:49', NULL),
(125, 'Sodium Selenium', 'RM119', 1, 2, 'Powder', 0.0000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:32:53', '2026-09-01 07:32:53', NULL),
(126, 'Sodium Borate', 'RM120', 1, 2, 'Powder', 0.0000, 1.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 07:33:47', '2026-09-01 07:33:47', NULL),
(127, 'Trachysperammi Extract', 'RM121', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 07:35:30', '2026-09-01 07:35:30', NULL),
(128, 'Thyme Extract', 'RM122', 1, 2, 'Powder', 0.0000, 3.0000, 1.5000, NULL, 0.00, 1, '2026-09-01 07:36:52', '2026-09-01 07:36:52', NULL),
(129, 'Tribulus Extract', 'RM123', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:37:51', '2026-09-01 07:37:51', NULL),
(130, 'Vitex Agnus Ext', 'RM124', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:39:28', '2026-09-01 07:39:28', NULL),
(131, 'Valerian Extract', 'RM125', 1, 2, 'Powder', 0.0000, 2.0000, 0.2000, NULL, 0.00, 1, '2026-09-01 07:40:11', '2026-09-01 07:40:11', NULL),
(132, 'Vitamin A', 'RM126', 1, 2, 'Powder', 0.0169, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:41:00', '2026-09-01 09:29:31', NULL),
(133, 'Vitamin B1 Thiamine', 'RM127', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:41:51', '2026-09-01 07:41:51', NULL),
(134, 'Vitamin B2 Riboflavin', 'RM128', 1, 2, 'Powder', 0.0000, 5.0000, 0.5000, NULL, 0.00, 1, '2026-09-01 07:43:32', '2026-09-01 07:43:32', NULL),
(135, 'Vitamin B3 Niacinamide', 'RM129', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:44:28', '2026-09-01 07:44:28', NULL),
(136, 'Vitamin B5 Pentothenic Acid', 'RM130', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:45:51', '2026-09-01 07:45:51', NULL),
(137, 'Vitamin B6 Pyrodxine', 'RM131', 1, 2, 'Powder', 0.0000, 25.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:47:15', '2026-09-01 07:47:15', NULL),
(138, 'Vitamin B9 Folic Acid', 'RM132', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:48:14', '2026-09-01 07:48:14', NULL),
(139, 'Vitamin E Tocopherol', 'RM133', 1, 2, 'Powder', 0.0002, 1.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:49:11', '2026-09-01 09:29:31', NULL),
(140, 'Vitamin C Ascorbic Acid', 'RM134', 1, 2, 'Powder', 0.0000, 25.0000, 3.0000, NULL, 0.00, 1, '2026-09-01 07:50:26', '2026-09-01 07:50:26', NULL),
(141, 'Vitamin D3 For Syrup 5lakh IU', 'RM135', 1, 2, 'Powder', 0.2687, 4.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 07:51:56', '2026-09-01 09:29:31', NULL),
(142, 'Vitamin D3 For Tab/Caps/Sac 4 Lakh IU', 'RM136', 1, 2, 'Powder', 0.1000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:53:41', '2026-09-05 06:11:21', NULL),
(143, 'Vitamin B12 Cynacobalamin', 'RM137', 1, 2, 'Powder', 0.0000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:55:44', '2026-09-01 07:55:44', NULL),
(144, 'Vitamin K2 MK-7', 'RM138', 1, 2, 'Powder', 0.0013, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:57:05', '2026-09-01 09:29:31', NULL),
(145, 'Vitamin E Oil', 'RM139', 1, 4, 'Liquid', 0.0000, 2.0000, 0.1000, NULL, 0.00, 1, '2026-09-01 07:58:07', '2026-09-01 07:58:07', NULL),
(146, 'Vitamin B7 Biotin', 'RM140', 1, 2, 'Powder', 0.0000, 0.1000, 0.0100, NULL, 0.00, 1, '2026-09-01 07:59:27', '2026-09-01 07:59:27', NULL),
(147, 'Wild Yam Ext', 'RM141', 1, 2, 'Powder', 3.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 08:00:31', '2026-09-12 04:21:42', NULL),
(148, 'Withania Somnifera Ext', 'RM142', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 08:01:36', '2026-09-01 08:01:36', NULL),
(149, 'Yohimbe Ext', 'RM143', 1, 2, 'Powder', 0.0000, 5.0000, 1.0000, NULL, 0.00, 1, '2026-09-01 08:02:36', '2026-09-01 08:02:36', NULL),
(150, 'Zinc Sulphate', 'RM144', 1, 2, 'Powder', 0.2250, 25.0000, 3.0000, NULL, 0.00, 1, '2026-09-01 08:03:40', '2026-09-01 09:29:31', NULL),
(151, 'Shell Green/White', 'RM145', 1, 1, 'Other', 0.0000, 120000.0000, 20000.0000, NULL, 0.00, 1, '2026-09-01 08:21:29', '2026-09-01 08:21:29', NULL),
(152, 'Shell Orange/Orange', 'RM146', 1, 1, 'Other', 0.0000, 100000.0000, 30000.0000, NULL, 0.00, 1, '2026-09-01 08:25:46', '2026-09-01 08:25:46', NULL),
(153, 'Shell Dark Blue/Light Blue', 'RM147', 1, 1, 'Other', 0.0000, 100000.0000, 30000.0000, NULL, 0.00, 1, '2026-09-01 08:27:55', '2026-09-01 08:27:55', NULL),
(154, 'Shell Purple/Purple', 'RM148', 1, 1, 'Other', 0.0000, 100000.0000, 30000.0000, NULL, 0.00, 1, '2026-09-01 08:29:34', '2026-09-01 08:29:34', NULL),
(155, 'Shell Pink/White', 'RM149', 1, 1, 'Other', 0.0000, 75000.0000, 20000.0000, NULL, 0.00, 1, '2026-09-01 08:30:59', '2026-09-01 08:30:59', NULL),
(156, 'Shell White/White', 'RM150', 1, 1, 'Other', 0.0000, 75000.0000, 20000.0000, NULL, 0.00, 1, '2026-09-01 08:32:26', '2026-09-01 08:32:26', NULL),
(157, 'Shell Maroon/Maroon', 'RM151', 1, 1, 'Other', 0.0000, 75000.0000, 20000.0000, NULL, 0.00, 1, '2026-09-01 08:33:51', '2026-09-01 08:33:51', NULL),
(158, 'Shell Red/Red', 'RM152', 1, 1, 'Other', 0.0000, 75000.0000, 20000.0000, NULL, 0.00, 1, '2026-09-01 08:35:10', '2026-09-01 08:35:10', NULL),
(159, 'DI WATER', 'RM153', 1, 4, 'Liquid', 20.0000, 100.0000, 10000.0000, NULL, 0.00, 1, '2026-09-01 09:19:32', '2026-09-01 09:29:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `receipts_vouchers`
--

CREATE TABLE `receipts_vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `rvid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `entry_date` date DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `narration_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `reference_no` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_head` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `row_account_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `discount_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `kg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rate` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `processed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts_vouchers`
--

INSERT INTO `receipts_vouchers` (`id`, `rvid`, `receipt_date`, `entry_date`, `type`, `party_id`, `tel`, `remarks`, `narration_id`, `reference_no`, `row_account_head`, `row_account_id`, `discount_value`, `kg`, `rate`, `amount`, `total_amount`, `processed`, `created_at`, `updated_at`) VALUES
(1, 'RVID-001', '2026-08-22', '2026-08-22', 'customer', '1', '04123456789', 'Receipt from Waleed (Bal: 1,000 Dr)', '[\"\"]', '[null]', '[\"1\",\"2\"]', '[\"2\",\"3\"]', '[\"0\"]', NULL, '[\"0\"]', '[\"200\",\"800\"]', 1000.00, 1, '2026-08-22 12:20:00', '2026-08-22 12:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2026-08-13 07:52:28', '2026-08-13 07:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1),
(69, 1),
(70, 1),
(71, 1),
(72, 1),
(73, 1),
(74, 1),
(75, 1),
(76, 1),
(77, 1),
(78, 1),
(79, 1),
(80, 1),
(81, 1),
(82, 1),
(83, 1),
(84, 1),
(85, 1),
(86, 1),
(87, 1),
(88, 1),
(89, 1),
(90, 1),
(91, 1),
(92, 1),
(93, 1),
(94, 1),
(95, 1),
(96, 1),
(97, 1),
(98, 1),
(99, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1),
(118, 1),
(119, 1),
(120, 1),
(121, 1),
(122, 1),
(123, 1),
(124, 1),
(125, 1),
(126, 1),
(127, 1),
(128, 1),
(129, 1),
(130, 1),
(131, 1),
(132, 1),
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(144, 1),
(145, 1),
(146, 1),
(147, 1),
(148, 1),
(149, 1),
(150, 1),
(151, 1),
(152, 1),
(153, 1),
(154, 1),
(155, 1),
(156, 1),
(157, 1),
(158, 1),
(159, 1),
(160, 1),
(161, 1),
(162, 1),
(163, 1),
(164, 1),
(165, 1),
(166, 1),
(167, 1),
(168, 1),
(169, 1),
(170, 1),
(171, 1),
(172, 1),
(173, 1),
(174, 1),
(175, 1),
(176, 1),
(177, 1),
(178, 1),
(179, 1),
(180, 1),
(181, 1),
(182, 1),
(183, 1),
(184, 1),
(185, 1),
(186, 1),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(192, 1),
(193, 1),
(194, 1),
(195, 1),
(196, 1),
(197, 1),
(198, 1),
(199, 1),
(200, 1),
(201, 1),
(202, 1),
(203, 1),
(204, 1),
(205, 1),
(206, 1),
(207, 1),
(208, 1),
(209, 1),
(210, 1),
(211, 1),
(212, 1),
(213, 1),
(214, 1),
(215, 1),
(216, 1),
(217, 1),
(218, 1),
(219, 1),
(220, 1),
(221, 1),
(222, 1),
(223, 1),
(224, 1),
(225, 1),
(226, 1),
(227, 1),
(228, 1),
(229, 1),
(230, 1),
(231, 1),
(232, 1),
(233, 1),
(234, 1),
(235, 1),
(236, 1),
(237, 1),
(238, 1),
(239, 1),
(240, 1),
(241, 1),
(242, 1),
(243, 1),
(244, 1),
(245, 1),
(246, 1),
(247, 1),
(248, 1),
(249, 1),
(250, 1),
(251, 1),
(252, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_synced` tinyint NOT NULL DEFAULT '0',
  `invoice_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `walkin_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'booked',
  `is_booking` tinyint(1) NOT NULL DEFAULT '0',
  `credit_days` int DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total_items` decimal(12,2) DEFAULT NULL,
  `total_bill_amount` decimal(12,2) DEFAULT NULL,
  `total_extradiscount` decimal(12,2) DEFAULT NULL,
  `total_net` decimal(12,2) DEFAULT NULL,
  `cash` decimal(12,2) DEFAULT NULL,
  `card` decimal(12,2) DEFAULT NULL,
  `change` decimal(12,2) DEFAULT NULL,
  `total_amount_Words` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `uuid`, `is_synced`, `invoice_no`, `customer_id`, `walkin_name`, `reference`, `sale_status`, `is_booking`, `credit_days`, `due_date`, `total_items`, `total_bill_amount`, `total_extradiscount`, `total_net`, `cash`, `card`, `change`, `total_amount_Words`, `created_at`, `updated_at`) VALUES
(1, 'b9d328ae-155f-49e6-a84e-d89f5360f6c3', 0, 'INV-0001', NULL, 'Walk-in Customer', NULL, 'posted', 0, NULL, NULL, 2.00, 1200.00, 0.00, 1200.00, 0.00, NULL, -1200.00, NULL, '2026-08-30 19:00:00', '2026-08-31 10:48:57'),
(4, 'a3d53f4f-bb4f-4ba0-9c1c-43ad9efa42bc', 0, 'INV-0002', 1, NULL, NULL, 'delivered', 1, NULL, NULL, 4000.00, 260000.00, 0.00, 260000.00, 0.00, NULL, -260000.00, NULL, '2026-09-29 19:00:00', '2026-09-30 05:29:04'),
(5, 'f6af960b-2d69-4ada-b8e4-3848b95ffb9f', 0, 'INV-0003', 4, NULL, NULL, 'booked', 1, NULL, NULL, 3000.00, 330000.00, 0.00, 330000.00, 0.00, NULL, -330000.00, NULL, '2026-09-29 19:00:00', '2026-09-30 13:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `sales_officers`
--

CREATE TABLE `sales_officers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_urdu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `mobile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint UNSIGNED NOT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `sale_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `size_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_id` bigint UNSIGNED DEFAULT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint UNSIGNED DEFAULT NULL,
  `unit_id` bigint UNSIGNED DEFAULT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price_level` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price` decimal(12,2) DEFAULT '0.00',
  `purchase_price` decimal(12,2) DEFAULT NULL,
  `price_per_piece` decimal(12,2) NOT NULL DEFAULT '0.00',
  `price_per_m2` decimal(12,2) NOT NULL DEFAULT '0.00',
  `qty` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `total_pieces` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `loose_pieces` int NOT NULL DEFAULT '0',
  `retail_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `is_manual`, `sale_id`, `warehouse_id`, `product_id`, `vendor_id`, `size_mode`, `product_name`, `brand_id`, `category_id`, `sub_category_id`, `unit_id`, `color`, `stock`, `price_level`, `price`, `purchase_price`, `price_per_piece`, `price_per_m2`, `qty`, `total_pieces`, `loose_pieces`, `retail_price`, `discount_percent`, `discount_amount`, `total`, `created_at`, `updated_at`) VALUES
(2, 0, 1, 1, 2, NULL, 'by_pieces', 'Wheat Flour', 2, NULL, NULL, NULL, 'eyJuYW1lIjoiV2hlYXQgRmxvdXIiLCJzaXplIjoiNWtnIiwiY29sb3IiOiItIiwic3RvY2siOjIwLCJzYWxlX3ByaWNlIjoiNjAwIiwid2hvbGVzYWxlX3ByaWNlIjoiNTAwIiwid2VpZ2h0X3Blcl9waWVjZSI6MCwicHVyY2hfcHJpY2UiOiIwIiwiYWxlcnQiOiIwIiwiYmFyY29kZSI6IjgwMDAwMSIsImNvbnZfZmFjdG9yIjoxLCJpc19iYXNlX3ZhcmlhbnQiOiIwIiwidW5pdCI6IlBjcyIsImN1cnJlbnRfc3RvY2siOjIwfQ==', 0.00, 0.00, 600.00, NULL, 0.00, 0.00, 2.0000, 2.0000, 0, 0.00, 0.00, 0.00, 1200.00, '2026-08-31 10:48:57', '2026-08-31 10:48:57'),
(3, 0, 4, 1, 6, NULL, 'by_pieces', 'sencid syrp 120ml', 1, NULL, NULL, 1, NULL, 0.00, 0.00, 65.00, NULL, 0.00, 0.00, 4000.0000, 4000.0000, 0, 0.00, 0.00, 0.00, 260000.00, '2026-09-30 05:08:51', '2026-09-30 05:08:51'),
(4, 0, 5, 1, 7, NULL, 'by_pieces', 'Zor-D Tablet', 2, NULL, NULL, 1, NULL, 0.00, 0.00, 110.00, NULL, 0.00, 0.00, 3000.0000, 3000.0000, 0, 0.00, 0.00, 0.00, 330000.00, '2026-09-30 13:14:17', '2026-09-30 13:14:17');

-- --------------------------------------------------------

--
-- Table structure for table `sale_returns`
--

CREATE TABLE `sale_returns` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_id` bigint UNSIGNED DEFAULT NULL,
  `return_invoice` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `bill_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `item_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `extra_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `paid` decimal(15,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'posted',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_items`
--

CREATE TABLE `sale_return_items` (
  `id` bigint UNSIGNED NOT NULL,
  `sale_return_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `purchase_price` decimal(15,2) DEFAULT NULL,
  `color` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `qty` decimal(15,2) NOT NULL COMMENT 'Total pieces returned',
  `boxes` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Box quantity (can be decimal like 1.2)',
  `loose_pieces` int NOT NULL DEFAULT '0' COMMENT 'Loose pieces',
  `price` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Price per piece',
  `item_discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pc',
  `line_total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('EubG14MEf2cNcMcouNo5u6JPuLKfBYnzaPO6W20D', NULL, '43.159.36.180', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOXlpWDBxVVVCM05Hb290MjlsUThkYUJoTTlydkxHeGtMVG9KRmlYOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuc2lkYXpwaGFybWFjZXV0aWNhbC5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792155797),
('HMxgz3AahZAM3Hnbb5L68KWNs7Ntfs0WYpqc8PcN', NULL, '119.157.79.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTDIwdnRlQmZJaXZ5TFZla3ZzV2V2NDVyNmpTbGZmZXRKWkhGSXM4cSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vc2lkYXpwaGFybWFjZXV0aWNhbC5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792155547),
('MkOUVsNDFxcUUVU9CzF26b3grA4QGxAaqfpSoQ5k', 1, '119.157.79.240', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN3VVa0N0MDc5amRjWnV4blRZU25BYTUwd2x1elNpQWc1eE9zU2tBQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vc2lkYXouYmluc3VsdGFuc3dlZXQuY29tL2hvbWUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1792156066),
('sUGQDSwHzm9xFALLZsj2fbyK05QqjF9jJRVKZUag', NULL, '43.165.186.188', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQmZaZkx1Mk1oNDluOUtNZm13anBqWUtSYnFlZkRocVM3RmM5YzhGeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuc2lkYXpwaGFybWFjZXV0aWNhbC5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792152619),
('Y4k6Sn91mwaYOMI0QUaVvKHQ3rZt4AFFugCngFwp', NULL, '43.153.67.21', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaFNLZHZ0OGlRMG1rWlNaMGFFejNSV0p0ckdMelFxV0I2dnVSVGtoMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9zaWRhenBoYXJtYWNldXRpY2FsLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792149573),
('zGKcMgBJHwKnyIARvDtOpKW9bvG65E5kdWAN4PF0', NULL, '43.166.242.189', 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia01zbDFzc0g3c21vUmNKZ1Mzb2pTcjVyYmdXZHlNWDU5ckg0NVhIQSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9zaWRhenBoYXJtYWNldXRpY2FsLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792155529);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Sidaz Pharmaceutical', 'string', 'company', 'Company Name', 'Official company name displayed on invoices and reports', '2026-08-13 07:51:50', '2026-08-31 09:56:39'),
(2, 'company_address', 'Plot A-64/65 Sindh Small Industrial Extension Hyderabad', 'text', 'company', 'Company Address', 'Full company address', '2026-08-13 07:51:50', '2026-08-31 09:56:39'),
(3, 'company_phone', '03003409006', 'string', 'company', 'Phone Number', 'Primary contact number', '2026-08-13 07:51:50', '2026-08-31 09:56:39'),
(4, 'currency_symbol', 'PKR', 'string', 'company', 'Currency Symbol', 'Currency used in the system', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(5, 'debt_warning_days', '7', 'integer', 'sales', 'Debt Warning Days', 'Number of days after which a warning notification is sent for unpaid invoices', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(6, 'debt_critical_days', '10', 'integer', 'sales', 'Debt Critical Days', 'Number of days after which a critical notification is sent for unpaid invoices', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(7, 'invoice_terms', 'Payment due within 30 days. Late payments may incur additional charges.', 'text', 'sales', 'Invoice Terms & Conditions', 'Default terms and conditions displayed on invoices', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(8, 'low_stock_threshold', '10', 'integer', 'inventory', 'Low Stock Threshold', 'Minimum quantity before low stock warning', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(9, 'expiry_alert_days', '30', 'integer', 'inventory', 'Expiry Alert Days', 'Number of days before expiry to show warning', '2026-08-13 07:51:50', '2026-08-13 07:51:50'),
(10, 'facebook_link', NULL, 'string', 'company', 'Facebook Link', 'Facebook page URL for receipts', '2026-08-13 07:52:04', '2026-08-13 07:52:04'),
(11, 'tiktok_link', NULL, 'string', 'company', 'TikTok Link', 'TikTok profile URL for receipts', '2026-08-13 07:52:04', '2026-08-13 07:52:04'),
(12, 'instagram_link', NULL, 'string', 'company', 'Instagram Link', 'Instagram profile URL for receipts', '2026-08-13 07:52:04', '2026-08-13 07:52:04'),
(13, 'website_link', NULL, 'string', 'company', 'Website Link', 'Website URL for receipts', '2026-08-13 07:52:04', '2026-08-13 07:52:04');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `reserved_qty` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_adjustments`
--

CREATE TABLE `stock_adjustments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('add','subtract','set') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'add',
  `qty` decimal(12,2) NOT NULL DEFAULT '0.00',
  `old_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `new_stock` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `type` enum('in','out','assembly_in','assembly_out','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(12,3) NOT NULL,
  `ref_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint UNSIGNED DEFAULT NULL,
  `ref_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_auto_pluck` tinyint NOT NULL DEFAULT '0',
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `type`, `qty`, `ref_type`, `ref_id`, `ref_uuid`, `is_auto_pluck`, `note`, `created_at`, `updated_at`) VALUES
(1, 2, 'adjustment', 60.000, 'INIT', NULL, NULL, 0, 'Initial Stock', '2026-08-17 10:26:27', '2026-08-17 10:26:27'),
(2, 2, 'out', -2.000, 'sale', 1, NULL, 0, 'Sale Posted #INV-0001', '2026-08-31 10:48:58', '2026-08-31 10:48:58'),
(3, 6, 'in', 4000.000, 'PRODUCTION', 1, NULL, 0, 'Produced Batch #BATCH-2026-0001 (4000 pcs)', '2026-09-30 05:28:13', '2026-09-30 05:28:13'),
(4, 6, 'out', -200.000, 'TRANSFER_OUT', 1, NULL, 0, 'Transfer to Warehouse 2', '2026-10-01 05:41:51', '2026-10-01 05:41:51'),
(5, 6, 'in', 200.000, 'TRANSFER_IN', 1, NULL, 0, 'Transfer from Warehouse 1', '2026-10-01 05:41:51', '2026-10-01 05:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint UNSIGNED NOT NULL,
  `from_warehouse_id` bigint UNSIGNED NOT NULL,
  `to_warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `to_shop` tinyint(1) NOT NULL DEFAULT '0',
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_transfers`
--

INSERT INTO `stock_transfers` (`id`, `from_warehouse_id`, `to_warehouse_id`, `to_shop`, `product_id`, `quantity`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 0, 6, 200, NULL, '2026-10-01 05:41:51', '2026-10-01 05:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Fan', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(2, 'ceiling  Fan', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(3, 'Pedestal  Fan', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(4, 'Fridge', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(5, 'Air-Condition(AC)', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(7, 'Microwave Oven', 1, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(8, 'Drill Machine', 2, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(9, '15 ML Dropper', 8, '2026-08-13 07:52:22', '2026-09-19 11:16:09'),
(10, 'Lathe Machine', 2, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(11, 'Milling Machine', 2, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(12, 'Shaper Machine', 2, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(13, 'Hammer', 3, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(14, 'Screwdriver', 3, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(15, 'Wrench', 3, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(16, 'Pliers', 3, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(17, 'Tape Measure', 3, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(18, 'Pipe', 4, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(19, 'Faucet', 4, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(20, 'Valve', 4, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(21, 'Toilet', 4, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(22, 'Sink', 4, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(23, 'Nails', 5, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(24, 'Screws', 5, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(25, 'Bolts', 5, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(26, 'Hinges', 5, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(27, 'Brackets', 5, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(28, 'Light', 6, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(29, 'Switch', 6, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(30, 'Wire', 6, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(31, 'Cable', 6, '2026-08-13 07:52:22', '2026-08-13 07:52:22'),
(32, 'Engine Oil', 7, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(33, 'Brake Pads', 7, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(34, 'Tires', 7, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(35, 'Batteries', 7, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(36, 'Filters', 7, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(37, 'SubCategory', 8, '2026-09-19 11:06:47', '2026-09-19 11:06:47'),
(38, 'Amber Bottle 120 ML', 8, '2026-09-19 11:15:13', '2026-09-19 11:15:13'),
(39, 'Tablet', 9, '2026-09-30 13:03:31', '2026-09-30 13:03:31');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ntn_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opening_balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_notifications`
--

CREATE TABLE `system_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
  `source_id` bigint UNSIGNED DEFAULT NULL,
  `source_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_notifications`
--

INSERT INTO `system_notifications` (`id`, `user_id`, `title`, `message`, `type`, `source_id`, `source_type`, `action_url`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, '⚠️ Stock Alert: Low Stock', 'Product \'Panadol serup\' (SKU: ITEM-0003) is low on stock. Current quantity: 0 pieces, Alert threshold: 10 pieces.', 'critical', 3, 'App\\Models\\Product', '/product', 1, '2026-09-20 10:19:54', '2026-09-05 20:12:07', '2026-09-20 10:19:54'),
(2, 1, '⚠️ Stock Alert: Low Stock', 'Product \'Si-Cid Syrup\' (SKU: ITEM-0004) is low on stock. Current quantity: 0 pieces, Alert threshold: 50 pieces.', 'critical', 4, 'App\\Models\\Product', '/product', 1, '2026-09-20 10:19:50', '2026-09-19 09:59:51', '2026-09-20 10:19:50'),
(3, 1, '⚠️ Stock Alert: Low Stock', 'Product \'Reviter Syrup\' (SKU: ITEM-0005) is low on stock. Current quantity: 0 pieces, Alert threshold: 100 pieces.', 'critical', 5, 'App\\Models\\Product', '/product', 1, '2026-09-20 10:19:46', '2026-09-19 11:33:02', '2026-09-20 10:19:46'),
(4, 1, '⚠️ Stock Alert: Low Stock', 'Product \'sencid syrp 120ml\' (SKU: ITEM-0006) is low on stock. Current quantity: 0 pieces, Alert threshold: 10 pieces.', 'critical', 6, 'App\\Models\\Product', '/product', 0, NULL, '2026-09-30 05:06:55', '2026-09-30 05:06:55'),
(5, 1, '⚠️ Stock Alert: Low Stock', 'Product \'Zor-D Tablet\' (SKU: ITEM-0007) is low on stock. Current quantity: 0 pieces, Alert threshold: 10 pieces.', 'critical', 7, 'App\\Models\\Product', '/product', 0, NULL, '2026-09-30 13:08:31', '2026-09-30 13:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `key`, `value`, `type`, `group`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'return_deadline_days', '30', 'integer', 'returns', 'Return Deadline (Days)', 'Number of days customers have to return items after purchase. Set to 0 to disable returns.', '2026-08-13 07:51:55', '2026-08-13 07:51:55'),
(2, 'return_require_approval', '1', 'boolean', 'returns', 'Require Manager Approval', 'If enabled, all returns must be approved by a manager before processing.', '2026-08-13 07:51:55', '2026-08-13 07:51:55'),
(3, 'return_auto_approve_threshold', '0', 'integer', 'returns', 'Auto-Approve Threshold', 'Returns under this amount will be auto-approved. Set to 0 to disable auto-approval.', '2026-08-13 07:51:55', '2026-08-13 07:51:55');

-- --------------------------------------------------------

--
-- Table structure for table `transports`
--

CREATE TABLE `transports` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_or_user_id` bigint UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_ur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `address_ur` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_unit` bigint UNSIGNED DEFAULT NULL,
  `conversion_factor` decimal(15,4) DEFAULT '1.0000',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `short_code`, `unit_type`, `base_unit`, `conversion_factor`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Piece', NULL, NULL, NULL, 1.0000, 1, '2026-08-13 07:52:23', '2026-08-13 07:52:23'),
(2, 'KG', 'KG', 'Weight', NULL, 1.0000, 1, '2026-08-17 10:20:12', '2026-08-17 10:20:12'),
(3, 'international unit', 'IU', 'Weight', NULL, 1.0000, 1, '2026-08-31 11:14:20', '2026-08-31 11:14:20'),
(4, 'litter', 'LTR', 'Volume', NULL, 1.0000, 1, '2026-08-31 11:15:07', '2026-08-31 11:15:07'),
(5, 'MG', 'MILIGRAM', 'Weight', NULL, 1.0000, 1, '2026-09-19 10:05:32', '2026-09-19 10:05:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `can_approve_returns` tinyint(1) NOT NULL DEFAULT '0',
  `can_approve_past_deadline_returns` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `usertype` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `can_approve_returns`, `can_approve_past_deadline_returns`, `email_verified_at`, `usertype`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@example.com', 0, 0, NULL, 'admin', '$2y$10$vb9wV42gEEfY7jHn5eFuyOCoaHarm4p1ka1MT.Pf8U5rOwjt./T2G', 'mmJP6C2LJ5feZPXgb8uJOOc6g3BkDWeAcg8IXSVGQlzXfaehat76rUU6IVhq', '2026-08-13 07:52:28', '2026-08-13 07:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ntn_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_terms` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `opening_balance` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `company_name`, `contact_person`, `email`, `phone`, `address`, `ntn_number`, `payment_terms`, `credit_limit`, `opening_balance`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'MSK', 'MSK', 'Kashif', NULL, '03413543556', 'Karachi', NULL, NULL, 0.00, NULL, 1, '2026-08-19 09:05:57', '2026-09-05 06:07:30', NULL),
(2, 'Saad Chemicals', 'Saad', 'Saad Naseem', NULL, '03322304189', 'Karachi', NULL, 'Cash', 0.00, '00', 1, '2026-09-05 05:35:40', '2026-09-05 05:36:33', NULL),
(3, 'Habib Rice Product LTD', 'Habib Rice', 'Habib', NULL, '03323784238', NULL, NULL, NULL, 0.00, '0', 1, '2026-09-05 05:52:36', '2026-09-05 05:52:36', NULL),
(4, 'Atif', 'Atif', 'Atif', NULL, '00000000', NULL, NULL, NULL, 0.00, '0', 1, '2026-09-05 06:01:37', '2026-09-05 06:01:37', NULL),
(5, 'Al-Gronic', 'Al-Gronic', 'Talha', NULL, '03142788874', 'Karachi', NULL, NULL, 0.00, '0', 1, '2026-09-05 06:04:31', '2026-09-05 06:07:44', NULL),
(6, 'AA Enterprises', 'AA', 'Abdul Moeid', NULL, '03363488467', NULL, NULL, NULL, 0.00, '0', 1, '2026-09-05 06:09:56', '2026-09-05 06:09:56', NULL),
(7, 'Hunain Arq', 'Hunain Bhai', 'Hunain', NULL, '03112861350', NULL, NULL, NULL, 0.00, '0', 1, '2026-09-12 04:25:37', '2026-09-12 04:25:37', NULL),
(8, 'Hammad Shakil Capsule', 'Hammad Shakil Capsule', 'Hammad Shakil Capsule', NULL, '03243227932', NULL, NULL, NULL, 0.00, '201200', 1, '2026-09-12 04:26:53', '2026-09-12 04:26:53', NULL),
(9, 'Junaid Calcium', 'Junaid Calcium', NULL, NULL, '00000000', NULL, NULL, NULL, 0.00, '0', 1, '2026-09-30 13:15:23', '2026-09-30 13:15:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bilties`
--

CREATE TABLE `vendor_bilties` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED DEFAULT NULL,
  `bilty_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehicle_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transporter_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_ledgers`
--

CREATE TABLE `vendor_ledgers` (
  `id` bigint UNSIGNED NOT NULL,
  `admin_or_user_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `opening_balance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `previous_balance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `closing_balance` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_ledgers`
--

INSERT INTO `vendor_ledgers` (`id`, `admin_or_user_id`, `vendor_id`, `opening_balance`, `previous_balance`, `closing_balance`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', 1, '0', '208069.64375', '215149.64375', '2026-08-19 09:05:57', '2026-09-05 05:41:15', NULL),
(2, '1', 2, '00', '0', '0', '2026-09-05 05:35:40', '2026-09-12 04:23:33', NULL),
(3, '1', 3, '0', '0', '-0.0049999999901047', '2026-09-05 05:52:36', '2026-09-05 05:54:31', NULL),
(4, '1', 4, '0', '0', '0', '2026-09-05 06:01:37', '2026-09-05 06:01:37', NULL),
(5, '1', 5, '0', '0', '0', '2026-09-05 06:04:32', '2026-09-05 06:04:32', NULL),
(6, '1', 6, '0', '0', '0', '2026-09-05 06:09:56', '2026-09-05 06:09:56', NULL),
(7, '1', 7, '0', '0', '0', '2026-09-12 04:25:37', '2026-09-12 04:25:37', NULL),
(8, '1', 8, '201200', '0', '201200', '2026-09-12 04:26:53', '2026-09-12 04:26:53', NULL),
(9, '1', 9, '0', '0', '65000', '2026-09-30 13:15:23', '2026-09-30 13:16:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `admin_or_user_id` bigint UNSIGNED NOT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint UNSIGNED NOT NULL,
  `voucher_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sales_officer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_head` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `narration` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('draft','posted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher_details`
--

CREATE TABLE `voucher_details` (
  `id` bigint UNSIGNED NOT NULL,
  `voucher_master_id` bigint UNSIGNED NOT NULL,
  `account_id` bigint UNSIGNED NOT NULL,
  `debit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `narration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_details`
--

INSERT INTO `voucher_details` (`id`, `voucher_master_id`, `account_id`, `debit`, `credit`, `narration`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 200.00, 0.00, 'Receipt', '2026-08-22 12:20:00', '2026-08-22 12:20:00'),
(2, 1, 3, 800.00, 0.00, 'Receipt', '2026-08-22 12:20:00', '2026-08-22 12:20:00'),
(3, 1, 4, 0.00, 1000.00, 'Receipt from customer', '2026-08-22 12:20:00', '2026-08-22 12:20:00'),
(4, 2, 2, 67777.00, 0.00, 'Payment received from Invoice #INV-0001', '2026-08-31 10:48:58', '2026-08-31 10:48:58'),
(5, 2, 5, 0.00, 67777.00, 'Payment for Invoice #INV-0001', '2026-08-31 10:48:58', '2026-08-31 10:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_masters`
--

CREATE TABLE `voucher_masters` (
  `id` bigint UNSIGNED NOT NULL,
  `voucher_type` enum('receipt','payment','expense','journal','contra') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('draft','posted','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `voucher_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `fiscal_year` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `party_id` bigint UNSIGNED DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `voucher_masters`
--

INSERT INTO `voucher_masters` (`id`, `voucher_type`, `status`, `voucher_no`, `date`, `fiscal_year`, `party_type`, `party_id`, `remarks`, `total_amount`, `created_by`, `posted_at`, `created_at`, `updated_at`) VALUES
(1, 'receipt', 'posted', 'RE-2026-0001', '2026-08-22', '2025-2026', 'App\\Models\\Customer', 1, 'Receipt from Waleed (Bal: 1,000 Dr) (Ref: RVID-001)', 1000.00, 1, NULL, '2026-08-22 12:20:00', '2026-08-22 12:20:00'),
(2, 'receipt', 'posted', 'RE-2026-0002', '2026-08-31', '2025-2026', NULL, NULL, 'Auto-Receipt for Sale Invoice #INV-0001. Total: 67777', 67777.00, 1, NULL, '2026-08-31 10:48:58', '2026-08-31 10:48:58');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stock_onhand`
-- (See below for the actual view)
--
CREATE TABLE `v_stock_onhand` (
`product_id` bigint unsigned
,`onhand_qty` decimal(34,3)
);

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `warehouse_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creater_id` bigint UNSIGNED DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `branch_id`, `warehouse_name`, `creater_id`, `location`, `remarks`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Main Store', 1, 'Karachi', 'Main stock storage', '2026-08-13 07:52:23', '2026-08-13 07:52:23', NULL),
(2, 1, 'Branch A', 1, 'Lahore', 'North region store', '2026-08-13 07:52:23', '2026-08-13 07:52:23', NULL),
(3, 1, 'Branch B', 1, 'Islamabad', 'Capital branch', '2026-08-13 07:52:23', '2026-08-13 07:52:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_stocks`
--

CREATE TABLE `warehouse_stocks` (
  `id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `total_pieces` decimal(12,4) NOT NULL DEFAULT '0.0000',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_stocks`
--

INSERT INTO `warehouse_stocks` (`id`, `warehouse_id`, `product_id`, `quantity`, `total_pieces`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 58, 58.0000, 'Initial Stock', '2026-08-17 10:26:27', '2026-08-31 10:48:58'),
(2, 1, 3, 0, 0.0000, 'Initial Stock', '2026-09-05 20:12:07', '2026-09-05 20:12:07'),
(3, 1, 4, 0, 0.0000, 'Initial Stock', '2026-09-19 09:59:51', '2026-09-19 09:59:51'),
(4, 1, 5, 0, 0.0000, 'Initial Stock', '2026-09-19 11:33:02', '2026-09-19 11:33:02'),
(5, 1, 6, 3800, 4000.0000, 'Initial Stock', '2026-09-30 05:06:55', '2026-10-01 05:41:51'),
(6, 1, 7, 0, 0.0000, 'Initial Stock', '2026-09-30 13:08:31', '2026-09-30 13:08:31'),
(7, 2, 6, 200, 0.0000, NULL, '2026-10-01 05:41:51', '2026-10-01 05:41:51');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint UNSIGNED NOT NULL,
  `zone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_head_id_foreign` (`head_id`);

--
-- Indexes for table `account_heads`
--
ALTER TABLE `account_heads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_heads_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `account_histories`
--
ALTER TABLE `account_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_histories_account_id_foreign` (`account_id`);

--
-- Indexes for table `biometric_devices`
--
ALTER TABLE `biometric_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `biometric_devices_is_active_index` (`is_active`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_user_id_unique` (`user_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_customer_id_unique` (`customer_id`),
  ADD UNIQUE KEY `customers_uuid_unique` (`uuid`);

--
-- Indexes for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_ledgers_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_payments_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `day_closings`
--
ALTER TABLE `day_closings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drawer_transactions`
--
ALTER TABLE `drawer_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `drawer_transactions_day_closing_id_foreign` (`day_closing_id`),
  ADD KEY `drawer_transactions_returned_in_closing_id_foreign` (`returned_in_closing_id`);

--
-- Indexes for table `dummy_invoices`
--
ALTER TABLE `dummy_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dummy_invoices_invoice_no_unique` (`invoice_no`),
  ADD KEY `dummy_invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `dummy_invoice_items`
--
ALTER TABLE `dummy_invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dummy_invoice_items_dummy_invoice_id_foreign` (`dummy_invoice_id`),
  ADD KEY `dummy_invoice_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `employee_salary_structures`
--
ALTER TABLE `employee_salary_structures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_salary_structures_assigned_by_foreign` (`assigned_by`),
  ADD KEY `employee_salary_structures_employee_id_is_active_index` (`employee_id`,`is_active`),
  ADD KEY `employee_salary_structures_salary_structure_id_is_active_index` (`salary_structure_id`,`is_active`),
  ADD KEY `employee_salary_structures_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_vouchers`
--
ALTER TABLE `expense_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `formulations`
--
ALTER TABLE `formulations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `formulations_formulation_code_unique` (`formulation_code`),
  ADD KEY `formulations_product_id_foreign` (`product_id`),
  ADD KEY `formulations_batch_unit_id_foreign` (`batch_unit_id`),
  ADD KEY `formulations_department_id_foreign` (`department_id`);

--
-- Indexes for table `formulation_packaging_materials`
--
ALTER TABLE `formulation_packaging_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formulation_packaging_materials_formulation_id_foreign` (`formulation_id`),
  ADD KEY `formulation_packaging_materials_packaging_material_id_foreign` (`packaging_material_id`);

--
-- Indexes for table `formulation_raw_materials`
--
ALTER TABLE `formulation_raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formulation_raw_materials_formulation_id_foreign` (`formulation_id`),
  ADD KEY `formulation_raw_materials_raw_material_id_foreign` (`raw_material_id`),
  ADD KEY `formulation_raw_materials_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `hr_attendances`
--
ALTER TABLE `hr_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_attendances_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hr_departments`
--
ALTER TABLE `hr_departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_designations`
--
ALTER TABLE `hr_designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_designations_name_unique` (`name`);

--
-- Indexes for table `hr_employees`
--
ALTER TABLE `hr_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_employees_email_unique` (`email`),
  ADD KEY `hr_employees_user_id_foreign` (`user_id`),
  ADD KEY `hr_employees_department_id_foreign` (`department_id`),
  ADD KEY `hr_employees_designation_id_foreign` (`designation_id`),
  ADD KEY `hr_employees_shift_id_foreign` (`shift_id`),
  ADD KEY `hr_employees_biometric_device_id_foreign` (`biometric_device_id`),
  ADD KEY `hr_employees_device_user_id_index` (`device_user_id`);

--
-- Indexes for table `hr_employee_documents`
--
ALTER TABLE `hr_employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_employee_documents_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hr_holidays`
--
ALTER TABLE `hr_holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_holidays_date_unique` (`date`);

--
-- Indexes for table `hr_leaves`
--
ALTER TABLE `hr_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_leaves_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hr_loans`
--
ALTER TABLE `hr_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_loans_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `hr_loan_payments`
--
ALTER TABLE `hr_loan_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_loan_payments_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `hr_loan_scheduled_deductions`
--
ALTER TABLE `hr_loan_scheduled_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_loan_scheduled_deductions_loan_id_foreign` (`loan_id`);

--
-- Indexes for table `hr_payrolls`
--
ALTER TABLE `hr_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_payrolls_employee_id_foreign` (`employee_id`),
  ADD KEY `hr_payrolls_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `hr_payrolls_account_id_foreign` (`account_id`);

--
-- Indexes for table `hr_payroll_details`
--
ALTER TABLE `hr_payroll_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_payroll_details_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `hr_policies`
--
ALTER TABLE `hr_policies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hr_salary_structures`
--
ALTER TABLE `hr_salary_structures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_salary_structures_employee_id_index` (`employee_id`),
  ADD KEY `hr_salary_structures_parent_structure_id_foreign` (`parent_structure_id`);

--
-- Indexes for table `hr_settings`
--
ALTER TABLE `hr_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hr_settings_key_unique` (`key`);

--
-- Indexes for table `hr_shifts`
--
ALTER TABLE `hr_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_series`
--
ALTER TABLE `invoice_series`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_series_prefix_unique` (`prefix`);

--
-- Indexes for table `inward_gatepasses`
--
ALTER TABLE `inward_gatepasses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inward_gatepass_items`
--
ALTER TABLE `inward_gatepass_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inward_gatepass_items_inward_gatepass_id_foreign` (`inward_gatepass_id`);

--
-- Indexes for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `journal_entries_source_type_source_id_index` (`source_type`,`source_id`),
  ADD KEY `journal_entries_account_id_entry_date_index` (`account_id`,`entry_date`),
  ADD KEY `journal_entries_entry_date_index` (`entry_date`),
  ADD KEY `journal_entries_party_type_party_id_index` (`party_type`,`party_id`);

--
-- Indexes for table `material_purchases`
--
ALTER TABLE `material_purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_purchases_invoice_no_unique` (`invoice_no`),
  ADD KEY `material_purchases_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `material_purchase_items`
--
ALTER TABLE `material_purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_purchase_items_material_purchase_id_foreign` (`material_purchase_id`);

--
-- Indexes for table `material_requisitions`
--
ALTER TABLE `material_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `material_requisitions_requisition_no_unique` (`requisition_no`),
  ADD KEY `material_requisitions_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `material_requisition_items`
--
ALTER TABLE `material_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requisition_items_material_requisition_id_foreign` (`material_requisition_id`),
  ADD KEY `material_requisition_items_raw_material_id_foreign` (`raw_material_id`);

--
-- Indexes for table `material_stock_movements`
--
ALTER TABLE `material_stock_movements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `modules_name_unique` (`name`);

--
-- Indexes for table `narrations`
--
ALTER TABLE `narrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `package_types`
--
ALTER TABLE `package_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package_types_name_unique` (`name`);

--
-- Indexes for table `packaging_materials`
--
ALTER TABLE `packaging_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packaging_materials_code_unique` (`code`),
  ADD KEY `packaging_materials_unit_id_foreign` (`unit_id`),
  ADD KEY `packaging_materials_capacity_unit_id_foreign` (`capacity_unit_id`),
  ADD KEY `packaging_materials_department_id_foreign` (`department_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `production_batches`
--
ALTER TABLE `production_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `production_batches_batch_no_unique` (`batch_no`),
  ADD KEY `production_batches_product_id_foreign` (`product_id`),
  ADD KEY `production_batches_sale_id_foreign` (`sale_id`),
  ADD KEY `production_batches_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `production_batch_items`
--
ALTER TABLE `production_batch_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `production_batch_items_production_batch_id_foreign` (`production_batch_id`),
  ADD KEY `production_batch_items_raw_material_id_foreign` (`raw_material_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`);

--
-- Indexes for table `product_bookings`
--
ALTER TABLE `product_bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_discounts_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_product_id_index` (`product_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_returns_return_invoice_unique` (`return_invoice`),
  ADD KEY `purchase_returns_vendor_id_foreign` (`vendor_id`),
  ADD KEY `purchase_returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_items_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `raw_materials_code_unique` (`code`),
  ADD KEY `raw_materials_unit_id_foreign` (`unit_id`),
  ADD KEY `raw_materials_department_id_foreign` (`department_id`);

--
-- Indexes for table `receipts_vouchers`
--
ALTER TABLE `receipts_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_no_unique` (`invoice_no`),
  ADD UNIQUE KEY `sales_uuid_unique` (`uuid`),
  ADD KEY `sales_due_date_index` (`due_date`);

--
-- Indexes for table `sales_officers`
--
ALTER TABLE `sales_officers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sale_returns_return_invoice_unique` (`return_invoice`),
  ADD KEY `sale_returns_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_returns_customer_id_foreign` (`customer_id`),
  ADD KEY `sale_returns_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_return_items_sale_return_id_foreign` (`sale_return_id`),
  ADD KEY `sale_return_items_product_id_foreign` (`product_id`),
  ADD KEY `sale_return_items_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stocks_unique_triplet` (`branch_id`,`warehouse_id`,`product_id`);

--
-- Indexes for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_adjustments_user_id_foreign` (`user_id`),
  ADD KEY `stock_adjustments_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_adjustments_product_id_foreign` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sm_product_created` (`product_id`,`created_at`),
  ADD KEY `stock_movements_ref_uuid_index` (`ref_uuid`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `stock_transfers_to_warehouse_id_foreign` (`to_warehouse_id`),
  ADD KEY `stock_transfers_product_id_foreign` (`product_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subcategories_category_id_foreign` (`category_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_notifications`
--
ALTER TABLE `system_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `system_notifications_user_id_is_read_index` (`user_id`,`is_read`),
  ADD KEY `system_notifications_type_created_at_index` (`type`,`created_at`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `system_settings_key_unique` (`key`);

--
-- Indexes for table `transports`
--
ALTER TABLE `transports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `units_base_unit_foreign` (`base_unit`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_bilties`
--
ALTER TABLE `vendor_bilties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_bilties_vendor_id_foreign` (`vendor_id`),
  ADD KEY `vendor_bilties_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `vendor_ledgers`
--
ALTER TABLE `vendor_ledgers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_ledgers_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_payments_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_details_voucher_master_id_foreign` (`voucher_master_id`),
  ADD KEY `voucher_details_account_id_foreign` (`account_id`);

--
-- Indexes for table `voucher_masters`
--
ALTER TABLE `voucher_masters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `voucher_masters_voucher_no_unique` (`voucher_no`),
  ADD KEY `voucher_masters_party_type_party_id_index` (`party_type`,`party_id`),
  ADD KEY `voucher_masters_voucher_type_index` (`voucher_type`),
  ADD KEY `voucher_masters_status_index` (`status`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_creater_id_index` (`creater_id`);

--
-- Indexes for table `warehouse_stocks`
--
ALTER TABLE `warehouse_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_stocks_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `warehouse_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `account_heads`
--
ALTER TABLE `account_heads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `account_histories`
--
ALTER TABLE `account_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `biometric_devices`
--
ALTER TABLE `biometric_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_payments`
--
ALTER TABLE `customer_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `day_closings`
--
ALTER TABLE `day_closings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `drawer_transactions`
--
ALTER TABLE `drawer_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dummy_invoices`
--
ALTER TABLE `dummy_invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dummy_invoice_items`
--
ALTER TABLE `dummy_invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_salary_structures`
--
ALTER TABLE `employee_salary_structures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expense_vouchers`
--
ALTER TABLE `expense_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formulations`
--
ALTER TABLE `formulations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `formulation_packaging_materials`
--
ALTER TABLE `formulation_packaging_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `formulation_raw_materials`
--
ALTER TABLE `formulation_raw_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hr_attendances`
--
ALTER TABLE `hr_attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1072;

--
-- AUTO_INCREMENT for table `hr_departments`
--
ALTER TABLE `hr_departments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hr_designations`
--
ALTER TABLE `hr_designations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `hr_employees`
--
ALTER TABLE `hr_employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `hr_employee_documents`
--
ALTER TABLE `hr_employee_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_holidays`
--
ALTER TABLE `hr_holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_leaves`
--
ALTER TABLE `hr_leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `hr_loans`
--
ALTER TABLE `hr_loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `hr_loan_payments`
--
ALTER TABLE `hr_loan_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_loan_scheduled_deductions`
--
ALTER TABLE `hr_loan_scheduled_deductions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_payrolls`
--
ALTER TABLE `hr_payrolls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `hr_payroll_details`
--
ALTER TABLE `hr_payroll_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `hr_policies`
--
ALTER TABLE `hr_policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_salary_structures`
--
ALTER TABLE `hr_salary_structures`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `hr_settings`
--
ALTER TABLE `hr_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hr_shifts`
--
ALTER TABLE `hr_shifts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `invoice_series`
--
ALTER TABLE `invoice_series`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inward_gatepasses`
--
ALTER TABLE `inward_gatepasses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inward_gatepass_items`
--
ALTER TABLE `inward_gatepass_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_entries`
--
ALTER TABLE `journal_entries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `material_purchases`
--
ALTER TABLE `material_purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `material_purchase_items`
--
ALTER TABLE `material_purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `material_requisitions`
--
ALTER TABLE `material_requisitions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `material_requisition_items`
--
ALTER TABLE `material_requisition_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `material_stock_movements`
--
ALTER TABLE `material_stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `narrations`
--
ALTER TABLE `narrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_types`
--
ALTER TABLE `package_types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packaging_materials`
--
ALTER TABLE `packaging_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `production_batches`
--
ALTER TABLE `production_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `production_batch_items`
--
ALTER TABLE `production_batch_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_bookings`
--
ALTER TABLE `product_bookings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw_materials`
--
ALTER TABLE `raw_materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `receipts_vouchers`
--
ALTER TABLE `receipts_vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sales_officers`
--
ALTER TABLE `sales_officers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sale_returns`
--
ALTER TABLE `sale_returns`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_notifications`
--
ALTER TABLE `system_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transports`
--
ALTER TABLE `transports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_bilties`
--
ALTER TABLE `vendor_bilties`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_ledgers`
--
ALTER TABLE `vendor_ledgers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher_details`
--
ALTER TABLE `voucher_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `voucher_masters`
--
ALTER TABLE `voucher_masters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouse_stocks`
--
ALTER TABLE `warehouse_stocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `v_stock_onhand`
--
DROP TABLE IF EXISTS `v_stock_onhand`;

CREATE ALGORITHM=UNDEFINED DEFINER=`binsult1_sidaz`@`localhost` SQL SECURITY DEFINER VIEW `v_stock_onhand`  AS SELECT `stock_movements`.`product_id` AS `product_id`, round(coalesce(sum((case when (`stock_movements`.`type` in ('in','assembly_in')) then abs(`stock_movements`.`qty`) when (`stock_movements`.`type` in ('out','assembly_out')) then -(abs(`stock_movements`.`qty`)) when (`stock_movements`.`type` = 'adjustment') then `stock_movements`.`qty` else 0 end)),0),3) AS `onhand_qty` FROM `stock_movements` GROUP BY `stock_movements`.`product_id` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `account_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_heads`
--
ALTER TABLE `account_heads`
  ADD CONSTRAINT `account_heads_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `account_heads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `account_histories`
--
ALTER TABLE `account_histories`
  ADD CONSTRAINT `account_histories_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_ledgers`
--
ALTER TABLE `customer_ledgers`
  ADD CONSTRAINT `customer_ledgers_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_payments`
--
ALTER TABLE `customer_payments`
  ADD CONSTRAINT `customer_payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drawer_transactions`
--
ALTER TABLE `drawer_transactions`
  ADD CONSTRAINT `drawer_transactions_day_closing_id_foreign` FOREIGN KEY (`day_closing_id`) REFERENCES `day_closings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drawer_transactions_returned_in_closing_id_foreign` FOREIGN KEY (`returned_in_closing_id`) REFERENCES `day_closings` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dummy_invoices`
--
ALTER TABLE `dummy_invoices`
  ADD CONSTRAINT `dummy_invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dummy_invoice_items`
--
ALTER TABLE `dummy_invoice_items`
  ADD CONSTRAINT `dummy_invoice_items_dummy_invoice_id_foreign` FOREIGN KEY (`dummy_invoice_id`) REFERENCES `dummy_invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dummy_invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employee_salary_structures`
--
ALTER TABLE `employee_salary_structures`
  ADD CONSTRAINT `employee_salary_structures_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `employee_salary_structures_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_salary_structures_salary_structure_id_foreign` FOREIGN KEY (`salary_structure_id`) REFERENCES `hr_salary_structures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_salary_structures_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `formulations`
--
ALTER TABLE `formulations`
  ADD CONSTRAINT `formulations_batch_unit_id_foreign` FOREIGN KEY (`batch_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `formulations_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hr_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `formulations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formulation_packaging_materials`
--
ALTER TABLE `formulation_packaging_materials`
  ADD CONSTRAINT `formulation_packaging_materials_formulation_id_foreign` FOREIGN KEY (`formulation_id`) REFERENCES `formulations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `formulation_packaging_materials_packaging_material_id_foreign` FOREIGN KEY (`packaging_material_id`) REFERENCES `packaging_materials` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `formulation_raw_materials`
--
ALTER TABLE `formulation_raw_materials`
  ADD CONSTRAINT `formulation_raw_materials_formulation_id_foreign` FOREIGN KEY (`formulation_id`) REFERENCES `formulations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `formulation_raw_materials_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `formulation_raw_materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_attendances`
--
ALTER TABLE `hr_attendances`
  ADD CONSTRAINT `hr_attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_employees`
--
ALTER TABLE `hr_employees`
  ADD CONSTRAINT `hr_employees_biometric_device_id_foreign` FOREIGN KEY (`biometric_device_id`) REFERENCES `biometric_devices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hr_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_employees_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `hr_designations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_employees_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `hr_shifts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_employee_documents`
--
ALTER TABLE `hr_employee_documents`
  ADD CONSTRAINT `hr_employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_leaves`
--
ALTER TABLE `hr_leaves`
  ADD CONSTRAINT `hr_leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_loans`
--
ALTER TABLE `hr_loans`
  ADD CONSTRAINT `hr_loans_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_loan_payments`
--
ALTER TABLE `hr_loan_payments`
  ADD CONSTRAINT `hr_loan_payments_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hr_loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_loan_scheduled_deductions`
--
ALTER TABLE `hr_loan_scheduled_deductions`
  ADD CONSTRAINT `hr_loan_scheduled_deductions_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `hr_loans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_payrolls`
--
ALTER TABLE `hr_payrolls`
  ADD CONSTRAINT `hr_payrolls_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hr_payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_payrolls_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `hr_payroll_details`
--
ALTER TABLE `hr_payroll_details`
  ADD CONSTRAINT `hr_payroll_details_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `hr_payrolls` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hr_salary_structures`
--
ALTER TABLE `hr_salary_structures`
  ADD CONSTRAINT `hr_salary_structures_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `hr_salary_structures_parent_structure_id_foreign` FOREIGN KEY (`parent_structure_id`) REFERENCES `hr_salary_structures` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `inward_gatepass_items`
--
ALTER TABLE `inward_gatepass_items`
  ADD CONSTRAINT `inward_gatepass_items_inward_gatepass_id_foreign` FOREIGN KEY (`inward_gatepass_id`) REFERENCES `inward_gatepasses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `journal_entries`
--
ALTER TABLE `journal_entries`
  ADD CONSTRAINT `journal_entries_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `material_purchases`
--
ALTER TABLE `material_purchases`
  ADD CONSTRAINT `material_purchases_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `material_purchase_items`
--
ALTER TABLE `material_purchase_items`
  ADD CONSTRAINT `material_purchase_items_material_purchase_id_foreign` FOREIGN KEY (`material_purchase_id`) REFERENCES `material_purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_requisitions`
--
ALTER TABLE `material_requisitions`
  ADD CONSTRAINT `material_requisitions_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_requisition_items`
--
ALTER TABLE `material_requisition_items`
  ADD CONSTRAINT `material_requisition_items_material_requisition_id_foreign` FOREIGN KEY (`material_requisition_id`) REFERENCES `material_requisitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_requisition_items_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packaging_materials`
--
ALTER TABLE `packaging_materials`
  ADD CONSTRAINT `packaging_materials_capacity_unit_id_foreign` FOREIGN KEY (`capacity_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `packaging_materials_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hr_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `packaging_materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `production_batches`
--
ALTER TABLE `production_batches`
  ADD CONSTRAINT `production_batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_batches_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `production_batches_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `production_batch_items`
--
ALTER TABLE `production_batch_items`
  ADD CONSTRAINT `production_batch_items_production_batch_id_foreign` FOREIGN KEY (`production_batch_id`) REFERENCES `production_batches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_batch_items_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD CONSTRAINT `product_discounts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_returns_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD CONSTRAINT `purchase_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_items_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `raw_materials`
--
ALTER TABLE `raw_materials`
  ADD CONSTRAINT `raw_materials_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `hr_departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `raw_materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_returns`
--
ALTER TABLE `sale_returns`
  ADD CONSTRAINT `sale_returns_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sale_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_return_items`
--
ALTER TABLE `sale_return_items`
  ADD CONSTRAINT `sale_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_items_sale_return_id_foreign` FOREIGN KEY (`sale_return_id`) REFERENCES `sale_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_return_items_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_adjustments`
--
ALTER TABLE `stock_adjustments`
  ADD CONSTRAINT `stock_adjustments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_adjustments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_adjustments_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `system_notifications`
--
ALTER TABLE `system_notifications`
  ADD CONSTRAINT `system_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `units_base_unit_foreign` FOREIGN KEY (`base_unit`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendor_bilties`
--
ALTER TABLE `vendor_bilties`
  ADD CONSTRAINT `vendor_bilties_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vendor_bilties_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_ledgers`
--
ALTER TABLE `vendor_ledgers`
  ADD CONSTRAINT `vendor_ledgers_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD CONSTRAINT `vendor_payments_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `voucher_details`
--
ALTER TABLE `voucher_details`
  ADD CONSTRAINT `voucher_details_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `voucher_details_voucher_master_id_foreign` FOREIGN KEY (`voucher_master_id`) REFERENCES `voucher_masters` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_stocks`
--
ALTER TABLE `warehouse_stocks`
  ADD CONSTRAINT `warehouse_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
