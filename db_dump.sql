-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: fdb13.awardspace.net
-- Generation Time: Nov 03, 2017 at 11:52 AM
-- Server version: 5.7.20-log
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `2491827_ispa`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `color` varchar(32) NOT NULL,
  `spz` varchar(10) NOT NULL,
  `date_stk` date NOT NULL,
  `car_condition` varchar(250) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `color`, `spz`, `date_stk`, `car_condition`, `school_id`) VALUES
(1, 'asfsa', 'asfafs', '2017-10-13', 'fasa', 3),
(2, 'černá', 'moje auto', '2017-11-23', 'dobrá', 1);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `lector_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `school_id`, `lector_id`, `name`) VALUES
(1, 3, 0, 'test'),
(2, 1, 0, 'test2');

-- --------------------------------------------------------

--
-- Table structure for table `lectors`
--

CREATE TABLE `lectors` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(25) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `phone` int(40) NOT NULL,
  `date_medical` date NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lectors`
--

INSERT INTO `lectors` (`id`, `email`, `name`, `surname`, `phone`, `date_medical`, `school_id`) VALUES
(1, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 71465478, '2017-11-01', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`) VALUES
(1, 'test'),
(3, 'test2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `username_canonical` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_canonical` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `school_id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(7, NULL, 'admin', 'admin', 'inrpo.ispa@gmail.com', 'inrpo.ispa@gmail.com', 1, 'XzEC/F1nOhHyW2jSdu72nvlx0V9bEp6BIBhU2Bp.CzU', 'DFJ/AaSjmwX6cqvT7BR9MRwKdlpERX+THI7x1F7wDXXq7XbQe5+XBVfFiNvULZbd/65MMz9HXYK/Sd/KeNdjAg==', '2017-11-03 11:34:04', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL),
(8, NULL, 'test', 'test', 'test@test.cz', 'test@test.cz', 1, 'PgDhfuRaMmIn9hnDLY1HC6AoWfauc0xz2qB3OJFvkKA', 'U3SWqetEGSpl58eU50WJHuEv+xgSRxXXprrZT6F/T1jKzcp4gyV5TCKMEbnMxcVG3BpGk6cr/K6GiuYL977pow==', '2017-10-24 19:01:37', 0, 0, NULL, NULL, NULL, 'a:3:{i:0;s:17:"ROLE_SONATA_ADMIN";i:1;s:32:"ROLE_SONATA_USER_ADMIN_USER_VIEW";i:2;s:32:"ROLE_SONATA_USER_ADMIN_USER_EDIT";}', 0, NULL),
(9, 1, 'skola', 'skola', 'skola@skola.cz', 'skola@skola.cz', 1, 'PlGYrWDrAf8kWVrsrwD3xGmwdpTL5iFPAU5L1VyDRO4', 'J2ZEVSYWXrzjLTAdzsY3QkXBOJsl0x95cPc++ja4FzRnnveO/Wrj/MpFHW0kl5K3FBgH2NXO2pCKPBZxXqP2mQ==', '2017-11-03 11:34:32', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school` (`school_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `lector_id` (`lector_id`);

--
-- Indexes for table `lectors`
--
ALTER TABLE `lectors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4689B75692FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_4689B756A0D96FBF` (`email_canonical`),
  ADD KEY `school_id` (`school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lectors`
--
ALTER TABLE `lectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lectors`
--
ALTER TABLE `lectors`
  ADD CONSTRAINT `lectors_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;