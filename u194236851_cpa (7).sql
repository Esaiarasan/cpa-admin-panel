-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 23, 2025 at 07:33 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u194236851_cpa`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `administrator_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_access_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`administrator_id`, `user_id`, `first_name`, `last_name`, `email`, `password`, `role_access_id`, `profile_image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(6, 'USD002', 'Kieu', 'Perform', 'mohan.weeras22ooriya@coats.com', '1111', 2, '1762921301_669a0cfd30923_TECKZY LOGO.png', '2025-10-25 04:36:43', 1, '2025-11-12 09:51:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `banner_images`
--

CREATE TABLE `banner_images` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brand_manager`
--

CREATE TABLE `brand_manager` (
  `brand_manager_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_access_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `brand_manager`
--

INSERT INTO `brand_manager` (`brand_manager_id`, `user_id`, `first_name`, `last_name`, `email`, `password`, `role_access_id`, `profile_image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'USD001', 'Esaiarasan', 'M', 'esaiarasan3112@gmail.com', '777777', 15, NULL, '2025-10-25 07:11:46', 1, '2025-10-25 07:11:46', NULL),
(2, 'USD001', 'Esaiarasan', 'M', 'esaiarasan3112@gmail.com', '777777', 15, NULL, '2025-10-25 07:12:11', 1, '2025-10-25 07:12:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `business_manager`
--

CREATE TABLE `business_manager` (
  `business_manager_id` int(11) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_access_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_manager`
--

INSERT INTO `business_manager` (`business_manager_id`, `user_id`, `first_name`, `last_name`, `email`, `password`, `role_access_id`, `profile_image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'USD001', 'EsaiArasan', 'M', 'esaiarasan3112@gmail.com', '7777', 4, '1762864942_Esai_img (1).png', '2025-10-25 08:02:28', 1, '2025-11-11 12:42:22', NULL),
(3, 'USD006', 'Esaiarasan', 'M', 'esaiarasanu3112@gmail.com', '1234', 4, '1762864461_Gemini_Generated_Image_cn9dj6cn9dj6cn9d.png', '2025-11-06 01:58:01', 1, '2025-11-11 12:34:21', NULL),
(5, 'USD001', 'Esaiarasan', 'M', 'esaiarasan31120@gmail.com', '1224', 4, NULL, '2025-11-11 06:36:38', 1, '2025-11-11 06:36:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `create_pathway`
--

CREATE TABLE `create_pathway` (
  `clpa_id` int(11) NOT NULL,
  `appt_id` int(11) DEFAULT NULL,
  `appointment_type` varchar(255) NOT NULL,
  `age_group` varchar(50) NOT NULL,
  `goal_domain` varchar(100) NOT NULL,
  `pathway_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `create_pathway`
--

INSERT INTO `create_pathway` (`clpa_id`, `appt_id`, `appointment_type`, `age_group`, `goal_domain`, `pathway_name`, `created_at`, `created_by`) VALUES
(31, 1, 'initial', '45', 'ewfewfef', 'fewfewf', '2025-12-17 06:09:34', '1'),
(32, 1, 'initial', '0-3', 'Movements', '0-3-M', '2025-12-18 03:42:09', '1'),
(33, 1, 'initial', '24-36', 'Floormobility', '2312', '2025-12-23 04:15:16', '1'),
(34, 1, 'initial', '0-3', 'Floormobility', '1', '2025-12-23 04:28:50', '1'),
(35, 1, 'initial', '0-3', 'Floormobility', '1', '2025-12-23 04:41:31', '1'),
(36, 1, 'initial', '0-3', 'Floormobility', '1', '2025-12-23 04:42:20', '1'),
(37, 1, 'initial', '0-3', 'Floormobility', '1', '2025-12-23 04:57:48', '1'),
(38, 1, 'initial', '0-3', 'Floormobility', '1', '2025-12-23 05:15:17', '1');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`) VALUES
(1, 'Demographics'),
(2, 'Background Informations & Assessments'),
(3, 'Comorbidities'),
(4, 'Goal Domain'),
(5, 'Intervention Suggestions');

-- --------------------------------------------------------

--
-- Table structure for table `help_upload_content`
--

CREATE TABLE `help_upload_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video` varchar(255) NOT NULL,
  `steps` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `it_managers`
--

CREATE TABLE `it_managers` (
  `it_manager_id` int(10) UNSIGNED NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_access_id` int(10) UNSIGNED DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `it_managers`
--

INSERT INTO `it_managers` (`it_manager_id`, `user_id`, `first_name`, `last_name`, `email`, `password`, `role_access_id`, `profile_image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'USD001', 'EsaiArasan', 'M', 'esaiarasan3112@gmail.com', '7777', 1, '1762919640_Gemini_Generated_Image_cn9dj6cn9dj6cn9d.png', '2025-10-25 08:04:16', 1, '2025-11-12 03:54:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lib_banner_upload`
--

CREATE TABLE `lib_banner_upload` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lib_upload_content`
--

CREATE TABLE `lib_upload_content` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `sub_heading` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `page_link` varchar(500) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `navigation_items`
--

CREATE TABLE `navigation_items` (
  `nav_id` int(11) NOT NULL,
  `nav_name` varchar(100) NOT NULL,
  `nav_icon` varchar(100) DEFAULT NULL,
  `nav_link` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sub_nav_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navigation_items`
--

INSERT INTO `navigation_items` (`nav_id`, `nav_name`, `nav_icon`, `nav_link`, `parent_id`, `created_at`, `updated_at`, `sub_nav_link`) VALUES
(1, 'Dashboard', 'bxs-dashboard', 'pages/dashboard.php', 0, '2025-09-29 08:05:15', '2025-11-08 07:14:18', NULL),
(2, 'Role Management', 'streamline-freehand:shopping-bag-target', 'pages/role_management.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:39:40', 'pages/add_roles.php,pages/edit_role.php,pages/view_role.php'),
(3, 'Pathways', 'ix:route-target', 'pages/pathways.php', NULL, '2025-09-29 08:05:15', '2025-10-18 08:34:00', NULL),
(4, 'Create Pathways', 'streamline-flex:star-badge', 'pages/create_pathways.php', NULL, '2025-09-29 08:05:15', '2025-11-20 05:24:40', 'pages/pathway_page.php'),
(5, 'Therapist', 'tabler:physotherapist', 'pages/therapist.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:40:44', 'pages/add_therapist.php,pages/edit_therapist.php,pages/view_therapist.php'),
(6, 'IT Managers', 'hugeicons:computer-user', 'pages/it_managers.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:40:59', 'pages/add_it_manager.php,pages/edit_it_manager.php,pages/view_it_manager.php'),
(7, 'Business Managers', 'mdi:user-tie', 'pages/business_managers.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:41:12', 'pages/add_business_manager.php,pages/edit_business_manager.php,pages/view_business_manager.php'),
(8, 'Administrators', 'hugeicons:user-settings-01', 'pages/administrators.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:41:27', 'pages/add_administrator.php,pages/edit_administrator.php,pages/view_administrator.php'),
(9, 'Brand Managers', 'oui:user', 'pages/brand_managers.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:41:41', 'pages/add_brandmanager.php,pages/edit_brandmanager.php,pages/view_brandmanager.php'),
(10, 'Send Notifications', 'bx bx-bell', 'pages/send_notifications.php', NULL, '2025-09-29 08:05:15', '2025-10-04 10:00:06', NULL),
(11, 'Bulk Upload', 'fa-upload', 'pages/bulk_upload.php', NULL, '2025-09-29 08:05:15', '2025-09-29 09:56:43', NULL),
(12, 'User Activity Log', 'bx bx-time-five', 'pages/user_activity_log.php', NULL, '2025-09-29 08:05:15', '2025-11-08 08:41:53', 'pages/view_user_activity.php'),
(13, 'Analytics', 'bx bx-line-chart', 'pages/analytics.php', NULL, '2025-10-04 09:50:26', '2025-10-04 09:53:52', NULL),
(14, 'Content Upload', 'bx bx-export', 'pages/content_upload.php', NULL, '2025-09-29 08:05:15', '2025-10-04 09:56:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_to` text DEFAULT NULL,
  `sent_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `message`, `sent_to`, `sent_date`, `created_by`, `created_at`) VALUES
(26, 'Test for Managers', NULL, '2025-11-10 08:19:22', 0, '2025-11-10 08:19:22'),
(34, 'Good morning', 'all', '2025-11-12 06:31:11', 0, '2025-11-12 11:01:11'),
(36, 'ADsdffgmtegfffvxfv uadbjsbdssbjdbsakdbkbbasdcsdfcsedeadasda   adaedead wasdadzxcz wadaa', NULL, '2025-11-26 13:22:14', 0, '2025-11-26 17:52:14');

-- --------------------------------------------------------

--
-- Table structure for table `notification_recipients`
--

CREATE TABLE `notification_recipients` (
  `id` int(11) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `recipient_type` enum('user','role','all') NOT NULL,
  `recipient_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification_recipients`
--

INSERT INTO `notification_recipients` (`id`, `notification_id`, `recipient_type`, `recipient_id`) VALUES
(2, 26, 'role', 1),
(3, 26, 'role', 2),
(11, 34, '', NULL),
(12, 34, 'role', 1),
(13, 34, 'role', 2),
(14, 34, 'role', 3),
(15, 34, 'role', 4),
(16, 34, 'role', 5),
(18, 36, 'role', 2);

-- --------------------------------------------------------

--
-- Table structure for table `pathways`
--

CREATE TABLE `pathways` (
  `pathway_id` int(11) NOT NULL,
  `pathway_name_id` int(11) NOT NULL,
  `node_number` varchar(50) DEFAULT NULL,
  `parent_node` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_for_therapist` tinyint(1) DEFAULT 1,
  `p_node_id` varchar(150) DEFAULT NULL,
  `group_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `update_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pathways`
--

INSERT INTO `pathways` (`pathway_id`, `pathway_name_id`, `node_number`, `parent_node`, `sort_order`, `is_for_therapist`, `p_node_id`, `group_name`, `created_at`, `created_by`, `update_at`, `update_by`, `deleted_at`, `deleted_by`) VALUES
(18, 31, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-17 06:09:34', '1', NULL, NULL, NULL, NULL),
(19, 32, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-18 03:42:09', '1', NULL, NULL, NULL, NULL),
(20, 32, '2', NULL, 0, 1, NULL, 'Comorbidities', '2025-12-18 03:42:59', NULL, NULL, NULL, NULL, NULL),
(21, 33, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 04:15:16', '1', NULL, NULL, NULL, NULL),
(22, 34, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 04:28:50', '1', NULL, NULL, NULL, NULL),
(23, 35, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 04:41:31', '1', NULL, NULL, NULL, NULL),
(24, 36, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 04:42:20', '1', NULL, NULL, NULL, NULL),
(25, 36, '2', NULL, 0, 1, NULL, 'Demographics', '2025-12-23 04:42:33', NULL, NULL, NULL, NULL, NULL),
(26, 37, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 04:57:48', '1', NULL, NULL, NULL, NULL),
(27, 37, '2', NULL, 0, 1, NULL, 'Demographics', '2025-12-23 05:12:49', NULL, NULL, NULL, NULL, NULL),
(28, 38, '1', NULL, 0, 1, NULL, 'Background Information & Assessments', '2025-12-23 05:15:17', '1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pathway_questions`
--

CREATE TABLE `pathway_questions` (
  `question_id` int(11) NOT NULL,
  `pathway_id` int(11) NOT NULL,
  `parent_node` varchar(50) NOT NULL,
  `node_number` varchar(50) NOT NULL,
  `question_text` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `is_for_therapist` tinyint(1) DEFAULT 1,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pathway_questions`
--

INSERT INTO `pathway_questions` (`question_id`, `pathway_id`, `parent_node`, `node_number`, `question_text`, `keywords`, `is_for_therapist`, `sort_order`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(11, 26, '1', '1.1', 'A test demographics group question?', 'demographics group', 1, 0, '2025-12-23 05:09:03', '1', NULL, NULL, NULL, NULL),
(12, 27, '2', '2.1', 'some random question?', 'Test demographics', 1, 0, '2025-12-23 05:13:21', '1', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pathway_question_options`
--

CREATE TABLE `pathway_question_options` (
  `option_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_management`
--

CREATE TABLE `role_management` (
  `r_id` int(11) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `access_navigations` varchar(155) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL,
  `viewed_at` datetime NOT NULL,
  `deleted_at` datetime NOT NULL,
  `controls` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_management`
--

INSERT INTO `role_management` (`r_id`, `role`, `department`, `access_navigations`, `created_at`, `updated_at`, `viewed_at`, `deleted_at`, `controls`) VALUES
(1, 'Senior IT Manager', 'IT Department', '2,14,1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"2\":\"view,edit\",\"1\":\"view\",\"14\":\"view,edit,delete\"}'),
(2, 'Senior Business Manager', 'Business Manager', '3,4,5,6,7,8,9,10', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"3\":\"view,edit\",\"4\":\"view,edit\",\"5\":\"view,edit\",\"6\":\"view,edit\",\"7\":\"view,edit\",\"8\":\"view,edit,delete\",\"9\":\"view,edit,delete\",\"10\":\"view,edit,delete\"}'),
(3, 'Therapist PracticnorTest', 'Therapist', '10,13,1', '2025-11-10 07:31:05', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"10\":[\"delete\"],\"13\":[\"view\"],\"1\":[\"view\"]}'),
(4, 'Associate Business Manager', ' Business Manager', '2,3,4,5,9,1', '2025-11-10 07:32:37', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"3\":\"view,edit\",\"4\":\"view,edit\",\"5\":\"view,edit\",\"2\":\"view,edit,delete\",\"9\":\"view,edit,delete\",\"1\":\"view\"}'),
(5, 'Junior Administrator', 'Administrator', '2,3,4,5', '2025-11-10 08:40:26', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"3\":\"view,edit\",\"2\":\"view,edit\",\"5\":\"view,edit,delete\",\"4\":\"view,edit,delete\"}'),
(6, 'Therapist', 'Therapist Practitioner', '2,4,5', '2025-11-17 06:16:48', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '{\"2\":\"view,edit,delete\",\"4\":\"view,edit\",\"5\":\"view,edit\"}');

-- --------------------------------------------------------

--
-- Table structure for table `therapists`
--

CREATE TABLE `therapists` (
  `therapist_id` int(11) NOT NULL,
  `moodle_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(10) DEFAULT NULL,
  `role_access_id` int(11) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `therapists`
--

INSERT INTO `therapists` (`therapist_id`, `moodle_id`, `first_name`, `last_name`, `email`, `password`, `role_access_id`, `profile_image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(13, 'MID001', 'Esaiarasan', 'M', 'esaiarasan3112@gmail.com', '1234', 2, '1762838969_generated-image (3).png', '2025-11-11 05:28:33', 1, '2025-11-11 06:28:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `toolbar_icons`
--

CREATE TABLE `toolbar_icons` (
  `id` int(11) NOT NULL,
  `icon_name` varchar(100) DEFAULT NULL,
  `icon_code` varchar(255) DEFAULT NULL,
  `tooltip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `toolbar_icons`
--

INSERT INTO `toolbar_icons` (`id`, `icon_name`, `icon_code`, `tooltip`) VALUES
(1, 'grouping', 'mdi:group', 'Grouping'),
(2, 'add_question', 'bi:question-square', 'Add Question'),
(3, 'add_sub_question', 'mdi:playlist-plus', 'Add Sub Question'),
(4, 'add_options', 'hugeicons:settings-05', 'Add Options'),
(5, 'add_sub_options', 'mdi:format-list-checks', 'Add Sub Options'),
(6, 'select_instruction', 'ic:twotone-code', 'Select Instruction'),
(7, 'delete', 'mdi:delete-outline', 'Delete'),
(8, 'edit_text', 'mdi:pencil-outline', 'Edit Text'),
(9, 'hai_score', 'mdi:robot-happy-outline', 'HAI Score'),
(10, 'add_intervention', 'mdi:plus-circle-outline', 'Add Intervention'),
(11, 'add_information', 'mdi:information-outline', 'Add Information'),
(12, 'upload_factsheet', 'mdi:upload-outline', 'Upload FactSheet'),
(13, 'add_suggestions', 'solar:forward-2-outline', 'Add Suggestions'),
(14, 'add_consideration', 'tabler:copy', 'Add Consideration'),
(15, 'risk_alert', 'octicon:alert-16', 'Risk Alert'),
(16, 'quick_link', 'streamline-pixel:interface-essential-hyperlink', 'Quick Link'),
(17, 'add_key_acquisition', 'solar:key-outline', 'Add Key Acquisition'),
(18, 'hine_score', 'icon-park-twotone:parenting-book', 'HINE Score');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Admin', 'test@example.com', 'admin@123', 'admin', '2025-09-30 06:12:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`administrator_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `banner_images`
--
ALTER TABLE `banner_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brand_manager`
--
ALTER TABLE `brand_manager`
  ADD PRIMARY KEY (`brand_manager_id`);

--
-- Indexes for table `business_manager`
--
ALTER TABLE `business_manager`
  ADD PRIMARY KEY (`business_manager_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `create_pathway`
--
ALTER TABLE `create_pathway`
  ADD PRIMARY KEY (`clpa_id`) USING BTREE;

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_upload_content`
--
ALTER TABLE `help_upload_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_managers`
--
ALTER TABLE `it_managers`
  ADD PRIMARY KEY (`it_manager_id`),
  ADD UNIQUE KEY `unique_email` (`email`),
  ADD KEY `role_access_idx` (`role_access_id`),
  ADD KEY `user_idx` (`user_id`);

--
-- Indexes for table `lib_banner_upload`
--
ALTER TABLE `lib_banner_upload`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lib_upload_content`
--
ALTER TABLE `lib_upload_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `navigation_items`
--
ALTER TABLE `navigation_items`
  ADD PRIMARY KEY (`nav_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notification_id` (`notification_id`);

--
-- Indexes for table `pathways`
--
ALTER TABLE `pathways`
  ADD PRIMARY KEY (`pathway_id`),
  ADD KEY `p_id` (`pathway_name_id`),
  ADD KEY `idx_node_number` (`node_number`);

--
-- Indexes for table `pathway_questions`
--
ALTER TABLE `pathway_questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `idx_pathway` (`pathway_id`),
  ADD KEY `idx_parent_node` (`parent_node`),
  ADD KEY `idx_node_number` (`node_number`);

--
-- Indexes for table `pathway_question_options`
--
ALTER TABLE `pathway_question_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `role_management`
--
ALTER TABLE `role_management`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `therapists`
--
ALTER TABLE `therapists`
  ADD PRIMARY KEY (`therapist_id`),
  ADD KEY `role_access_id` (`role_access_id`);

--
-- Indexes for table `toolbar_icons`
--
ALTER TABLE `toolbar_icons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `administrator_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `banner_images`
--
ALTER TABLE `banner_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brand_manager`
--
ALTER TABLE `brand_manager`
  MODIFY `brand_manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `business_manager`
--
ALTER TABLE `business_manager`
  MODIFY `business_manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `create_pathway`
--
ALTER TABLE `create_pathway`
  MODIFY `clpa_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `help_upload_content`
--
ALTER TABLE `help_upload_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `it_managers`
--
ALTER TABLE `it_managers`
  MODIFY `it_manager_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lib_banner_upload`
--
ALTER TABLE `lib_banner_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lib_upload_content`
--
ALTER TABLE `lib_upload_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `navigation_items`
--
ALTER TABLE `navigation_items`
  MODIFY `nav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pathways`
--
ALTER TABLE `pathways`
  MODIFY `pathway_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pathway_questions`
--
ALTER TABLE `pathway_questions`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pathway_question_options`
--
ALTER TABLE `pathway_question_options`
  MODIFY `option_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_management`
--
ALTER TABLE `role_management`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `therapists`
--
ALTER TABLE `therapists`
  MODIFY `therapist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `toolbar_icons`
--
ALTER TABLE `toolbar_icons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notification_recipients`
--
ALTER TABLE `notification_recipients`
  ADD CONSTRAINT `notification_recipients_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`);

--
-- Constraints for table `pathways`
--
ALTER TABLE `pathways`
  ADD CONSTRAINT `pathways_ibfk_1` FOREIGN KEY (`pathway_name_id`) REFERENCES `create_pathway` (`clpa_id`);

--
-- Constraints for table `pathway_questions`
--
ALTER TABLE `pathway_questions`
  ADD CONSTRAINT `fk_pq_pathway` FOREIGN KEY (`pathway_id`) REFERENCES `pathways` (`pathway_id`) ON DELETE CASCADE;

--
-- Constraints for table `pathway_question_options`
--
ALTER TABLE `pathway_question_options`
  ADD CONSTRAINT `pathway_question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `pathway_questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `therapists`
--
ALTER TABLE `therapists`
  ADD CONSTRAINT `therapists_ibfk_1` FOREIGN KEY (`role_access_id`) REFERENCES `navigation_items` (`nav_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
