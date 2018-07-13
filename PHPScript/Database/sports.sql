-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2018 at 02:45 PM
-- Server version: 5.6.14
-- PHP Version: 5.5.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sports`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE IF NOT EXISTS `tbl_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `image` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaint`
--

CREATE TABLE IF NOT EXISTS `tbl_complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `description` longtext NOT NULL,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_complaint_reply`
--

CREATE TABLE IF NOT EXISTS `tbl_complaint_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `message` longtext NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contact`
--

CREATE TABLE IF NOT EXISTS `tbl_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_us` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_course`
--

CREATE TABLE IF NOT EXISTS `tbl_course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trainer_id` int(11) NOT NULL,
  `course_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `course_detail` text CHARACTER SET utf8 NOT NULL,
  `course_price` float NOT NULL,
  `course_time` varchar(30) CHARACTER SET utf8 NOT NULL,
  `hours_number` varchar(10) CHARACTER SET utf8 NOT NULL,
  `course_location` varchar(50) CHARACTER SET utf8 NOT NULL,
  `course_city` varchar(50) CHARACTER SET utf8 NOT NULL,
  `allowed_trainee` int(11) NOT NULL,
  `image` varchar(122) CHARACTER SET utf8 NOT NULL,
  `course_category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_firebase_keys`
--

CREATE TABLE IF NOT EXISTS `tbl_firebase_keys` (
  `f_id` int(11) NOT NULL AUTO_INCREMENT,
  `androidkey` text NOT NULL,
  `ioskey` text NOT NULL,
  PRIMARY KEY (`f_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gallery`
--

CREATE TABLE IF NOT EXISTS `tbl_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(100) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_lang`
--

CREATE TABLE IF NOT EXISTS `tbl_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(500) NOT NULL,
  `english` varchar(500) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=189 ;

--
-- Dumping data for table `tbl_lang`
--

INSERT INTO `tbl_lang` (`id`, `keyword`, `english`) VALUES
(64, 'welcome', 'Welcome'),
(65, 'dashboard', 'Dashboard'),
(66, 'logout', 'Logout'),
(67, 'trainee', 'Trainee'),
(68, 'add_trainee', 'Add Trainee'),
(69, 'edit', 'Edit'),
(70, 'view_trainee', 'View Trainee'),
(71, 'delete', 'Delete'),
(72, 'trainer', 'Trainer'),
(73, 'add_trainer', 'Add Trainer'),
(74, 'view_trainer', 'View Trainer'),
(75, 'notification', 'Notification'),
(76, 'category', 'Category'),
(77, 'admin_panel', 'Admin Panel'),
(78, 'name', 'Name'),
(79, 'email', 'Email'),
(80, 'password', 'Password'),
(81, 'confirm_password', 'Confirm Password'),
(82, 'gender', 'Gender'),
(83, 'age', 'Age'),
(84, 'training_level', 'Training Level'),
(85, 'section', 'Section'),
(86, 's_no', 'S.No'),
(87, 'action', 'Action'),
(88, 'mobile', 'Mobile'),
(89, 'cancel', 'Cancel'),
(90, 'save', 'Save'),
(91, 'select_gender', 'Select Gender'),
(92, 'select_sport', 'Select Sport'),
(93, 'select_age', 'Select Age'),
(94, 'select_level', 'Select Level'),
(95, 'view_category', 'View Category'),
(96, 'category_name', 'Category Name'),
(97, 'image', 'Image'),
(98, 'add_category', 'Add Category'),
(99, 'edit_trainer_profile', 'Edit Trainer Profile'),
(100, 'coaching_experience', 'Coaching Experience'),
(101, 'college', 'College'),
(102, 'university', 'University'),
(103, 'coach_courses', 'Coach Courses'),
(104, 'qualification', 'Qualification'),
(105, 'achievement', 'Achievement'),
(106, 'has_sport_license', 'Has Sport License'),
(107, 'photos', 'Photos'),
(108, 'videos', 'Videos'),
(109, 'allow_private', 'Allow Private'),
(110, 'allow_client_location', 'Allow Client Location'),
(111, 'course_location', 'Course Location'),
(112, 'edit_profile', 'Edit Profile'),
(113, 'update_gallery', 'Update Gallery'),
(114, 'view_gallery', 'View Gallery'),
(115, 'search', 'Search'),
(116, 'trainer_name', 'Trainer Name'),
(117, 'trainer_no', 'Trainer No'),
(118, 'all', 'All'),
(119, 'add_course', 'Add Course'),
(120, 'view_course', 'View Course'),
(121, 'course_name', 'Course Name'),
(122, 'course_detail', 'Course Detail'),
(123, 'course_price', 'Course Price'),
(124, 'select_trainer', 'Select Trainer'),
(125, 'course', 'Course'),
(126, 'view_rating', 'View Rating'),
(127, 'rating', 'Rating'),
(128, 'review_text', 'Review Text'),
(129, 'trainee_name', 'Trainee Name'),
(130, 'coach_name', 'Coach Name'),
(131, 'course', 'Course'),
(132, 'category', 'Category'),
(133, 'trainer', 'Trainer'),
(134, 'trainer', 'Trainer'),
(135, 'total_trainer', 'Total Trainer'),
(136, 'total_trainee', 'Total Trainee'),
(137, 'total_sports', 'Total Sports'),
(138, 'request_order', 'Request Order'),
(139, 'response_order', 'Response Order'),
(140, 'weight', 'Weight'),
(141, 'height', 'Height'),
(142, 'select_trainee', 'Select Trainee'),
(143, 'status', 'Status'),
(144, 'complete', 'Complete'),
(145, 'approval', 'Approval'),
(146, 'under_process', 'Under process'),
(147, 'cancel_request', 'Cancel request'),
(148, 'course_time', 'Course Time'),
(149, 'Android FirebaseKey', 'Android FirebaseKey'),
(150, 'Ios FirebaseKey', 'Ios FirebaseKey'),
(151, 'Notification', 'Notification'),
(152, 'add_notification', 'Add Notification'),
(153, 'content', 'Content'),
(154, 'date', 'Date'),
(155, 'send', 'Send'),
(156, 'notification_setting', 'Notification Setting'),
(157, 'contact', 'Contact'),
(158, 'album', 'Album'),
(159, 'album_name', 'Album Name'),
(160, 'abb_album', 'Add Album'),
(161, 'tol_image', 'Total Image'),
(162, 'add_album', 'Add Album'),
(163, 'total_coach', 'Total Coach'),
(164, 'coach_no', 'Coach No'),
(165, 'coach', 'Coach'),
(166, 'complaint', 'Complaint'),
(167, 'about_us', 'About Us'),
(168, 'view_complaint', 'View Complaint'),
(169, 'description', 'Description'),
(170, 'select_coach', 'Select Coach'),
(171, 'course_category', 'Course Category'),
(172, 'course_address', 'Course Address'),
(173, 'course_city', 'Course City'),
(174, 'experience', 'Experience'),
(175, 'number_of_hours', 'Number of Hours'),
(176, 'total_trainee_allowed', 'Total Trainee Allowed'),
(177, 'select_category', 'Select Category'),
(178, 'add_coach', 'Add Coach'),
(179, 'country', 'Country'),
(180, 'city', 'City'),
(181, 'certificate', 'Certificate'),
(182, 'view_coach', 'View Coach'),
(183, 'apply', 'Apply'),
(184, 'set_status', 'Set Status'),
(185, 'edit_coach_profile', 'Edit Coach Profile'),
(186, 'private_lesson_price', 'Private Lession Price'),
(187, 'private_lesson_duration', 'Private Lesson Duration'),
(188, 'about_coach', 'About Coach');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE IF NOT EXISTS `tbl_notifications` (
  `noti_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `updated_at` bigint(20) NOT NULL,
  PRIMARY KEY (`noti_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notification_users`
--

CREATE TABLE IF NOT EXISTS `tbl_notification_users` (
  `u_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `device_id` text NOT NULL,
  `reg_id` varchar(250) NOT NULL,
  `device` varchar(10) NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`u_id`),
  UNIQUE KEY `reg_id` (`reg_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE IF NOT EXISTS `tbl_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `age` varchar(20) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `height` varchar(20) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  `hours` varchar(50) NOT NULL,
  `od_date` varchar(50) NOT NULL,
  `od_time` varchar(50) NOT NULL,
  `location` varchar(100) NOT NULL,
  `notes` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders_old`
--

CREATE TABLE IF NOT EXISTS `tbl_orders_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `height` varchar(20) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(30) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rating`
--

CREATE TABLE IF NOT EXISTS `tbl_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_trainer_profile`
--

CREATE TABLE IF NOT EXISTS `tbl_trainer_profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `age` varchar(20) CHARACTER SET latin1 NOT NULL,
  `level` varchar(50) CHARACTER SET latin1 NOT NULL,
  `sport_id` int(11) NOT NULL,
  `photo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `video` varchar(100) CHARACTER SET latin1 NOT NULL,
  `experience` text NOT NULL,
  `qualification` text NOT NULL,
  `about_coach` longtext NOT NULL,
  `achievement` text NOT NULL,
  `license` varchar(50) NOT NULL,
  `joining_date` date NOT NULL,
  `college` varchar(100) NOT NULL,
  `location` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `certificate` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_name` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) NOT NULL,
  `user_password` varchar(50) CHARACTER SET utf8 NOT NULL,
  `gender` varchar(50) CHARACTER SET utf8 NOT NULL,
  `usertype` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_mobile` varchar(50) CHARACTER SET utf8 NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `user_fullname`, `user_name`, `email`, `user_password`, `gender`, `usertype`, `user_mobile`, `status`, `token`) VALUES
(1, '', 'admin', 'admin@gmail.com', '123', 'male', 'admin', '9632587410', 1, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
