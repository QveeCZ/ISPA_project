-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: fdb13.awardspace.net
-- Generation Time: Nov 27, 2017 at 08:42 AM
-- Server version: 5.7.20-log
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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
  `car_type` varchar(255) NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `color`, `spz`, `date_stk`, `car_condition`, `car_type`, `school_id`) VALUES
(3, 'qewqwe', '12345456', '2017-11-14', 'qweqwe', '', 1),
(4, 'wqewq', 'qwe', '2017-10-30', 'qweqwe', '', 1),
(5, 'Černá', '1232', '2017-12-08', 'Ojeté', '', 4),
(6, 'Černá', '1234', '2014-05-05', 'špatná', 'škodovka', 1),
(7, 'Černá', '1234', '2014-05-05', 'špatná', 'škodovka', 1),
(8, 'qewqwe', '12345456', '2017-11-14', 'qweqwe', 'TDI', 5),
(12, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(13, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(14, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(15, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(16, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(17, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(18, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(19, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(20, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(21, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(22, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(23, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(24, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(25, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(26, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(27, 'red', '123456789', '1994-10-10', 'qweqe', 'TDI', 1),
(29, 'qewqwe', '12345456', '2017-10-14', 'qweqwe', 'TDI', 8),
(30, 'qewqwe', '12345456', '2017-10-14', 'qweqwe', 'TDI', 3),
(32, 'qewqwe', '12345456', '2017-10-14', 'qweqwe', 'TDI', 3),
(33, 'RGB', '12345456', '2017-10-14', 'bad', 'Diesel', 5),
(34, 'RGB', '12345456', '2017-10-14', 'bad', 'Diesel', 8);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(10) NOT NULL,
  `datum_zahajeni` datetime NOT NULL,
  `datum_ukonceni` datetime NOT NULL,
  `lektor_pk` int(11) NOT NULL,
  `opravneni_pk` int(11) NOT NULL,
  `kurz_pk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `school_id`, `name`, `capacity`, `datum_zahajeni`, `datum_ukonceni`, `lektor_pk`, `opravneni_pk`, `kurz_pk`) VALUES
(3, 4, 'Kurz A,B', 16, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(4, 4, 'Kurz B', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(5, 5, 'C,D', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(9, 8, 'Kurz B', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(10, 1, 'qwe', 50, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(11, 1, 'qwe', 50, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(12, 1, 'qwe', 50, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(13, 1, 'qwe', 50, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(17, 1, 'qwe', 50, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(21, 8, 'Kurz B', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(22, 3, 'Kurz B', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(23, 3, 'Kurz A', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(26, 3, 'Kurz B', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(27, 3, 'Kurz A', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(28, 5, 'Kurz C', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(29, 5, 'Kurz I', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(30, 8, 'Kurz C', 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0),
(31, 8, 'Kurz I', 20, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `course_registrations`
--

CREATE TABLE `course_registrations` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthDate` date DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `course_registrations`
--

INSERT INTO `course_registrations` (`id`, `course_id`, `name`, `surname`, `email`, `birthDate`, `created`) VALUES
(24, 3, 'a', 'b', NULL, NULL, '2017-11-21 13:14:38'),
(25, 3, 'sdf', 'drgh', NULL, NULL, '2017-11-21 13:14:38'),
(26, 3, 'as', 'we', 'weew', '2017-11-24', '2017-11-21 13:14:38'),
(27, 3, 'ad', 'ew', NULL, NULL, '2017-11-21 13:14:38'),
(28, 3, 'asew', 'gew', NULL, NULL, '2017-11-21 13:14:38'),
(29, 3, 'a', 'adw', NULL, NULL, '2017-11-21 13:14:38'),
(30, 3, 'testA', 'testA', 'aaa@bbb.cz', '1990-05-09', '2017-11-21 13:14:38'),
(32, 3, 'Ondřej', 'Komárek', 'st39751@student.upce.cz', '2017-11-09', '2017-11-21 13:24:34'),
(33, 3, 'Ondřej', 'Komárek', 'st39751@student.upce.cz', '2017-11-09', '2017-11-21 13:25:14'),
(34, 3, 'Ondřej', 'Komárek', 'st39751@student.upce.cz', '2017-11-09', '2017-11-21 13:26:22'),
(35, 3, 'Ondřej', 'Komárek', 'st39751@student.upce.cz', '2017-11-09', '2017-11-21 13:27:09'),
(36, 3, 'Ondřej', 'Komárek', 'st39751@student.upce.cz', '2017-11-09', '2017-11-21 13:27:27'),
(37, 3, 'tt', 't', 'st39751@student.upce.cz', '2017-11-08', '2017-11-21 13:44:11'),
(38, 3, 'fsasfaf', 'saf', 'ondrejk@hotmail.cz', '2017-11-01', '2017-11-21 13:44:55'),
(39, 4, 'aafsa', 'asfa', 'ondrejk@hotmail.cz', '2017-10-31', '2017-11-21 13:45:46'),
(40, 4, 'asfa', 'aff', 'okomarek@simpliko.cz', '2017-11-08', '2017-11-21 13:50:17'),
(41, 3, 'sgasasg', 'asg', 'agsa@agas.cz', '2017-11-07', '2017-11-21 13:57:42'),
(42, 3, 'tqwwet', 'we', 'wtw', '2017-11-09', '2017-11-21 14:08:09'),
(43, 3, 'tqwwet', 'we', 'wtw@aaa.cz', '2017-11-09', '2017-11-21 14:08:19'),
(44, 4, 'adsdas', 'asf', 'ondrejk@hotmail.cz', '2017-11-09', '2017-11-21 14:16:52'),
(45, 4, 'adsdas', 'asf', 'ondrejk@hotmail.cz', '2017-11-09', '2017-11-21 14:18:21'),
(46, 4, 'aafss', 'faaf', 'sss@ccc.cz', '2017-11-01', '2017-11-21 15:04:06'),
(47, 4, 'aafss', 'faaf', 'sss@ccc.cz', '2017-11-01', '2017-11-21 15:04:38'),
(48, 30, 'Tonda', 'Berkovec', NULL, NULL, '2017-11-21 18:10:05'),
(49, 30, 'Ondrej', 'Mares', NULL, NULL, '2017-11-21 18:10:05'),
(50, 5, 'asd', 'vew', 'asdvweevw@dsbve', '2017-11-07', '2017-11-21 21:42:12');

-- --------------------------------------------------------

--
-- Table structure for table `course_ride`
--

CREATE TABLE `course_ride` (
  `id` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `course_registration_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lectors`
--

CREATE TABLE `lectors` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `phone` int(40) NOT NULL,
  `date_medical` date NOT NULL,
  `school_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `lectors`
--

INSERT INTO `lectors` (`id`, `email`, `name`, `surname`, `phone`, `date_medical`, `school_id`) VALUES
(1, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 714654788, '2017-11-01', 1),
(2, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-11-07', 3),
(9, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 71465478, '2017-11-01', 5),
(10, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-11-07', 5),
(13, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 71465478, '2017-11-01', 8),
(14, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-11-07', 8),
(15, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(16, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(17, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(18, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(19, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(20, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(21, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(22, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(23, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(24, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(25, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(26, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(27, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(28, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(29, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(30, 'asddas@sa.cz', 'qwe', 'asd', 152365456, '1994-12-12', 1),
(53, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 714654784, '2017-10-14', 8),
(54, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-11-07', 8),
(55, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 714654784, '2017-10-14', 3),
(56, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-11-07', 3),
(58, 'tonik.slamin@seznam.cz', 'Tonda', 'Slama', 714654784, '2017-10-14', 3),
(59, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-10-14', 3),
(60, 'brouk.slamin@seznam.cz', 'Tonda', 'Slama', 2147483647, '2017-10-14', 5),
(61, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-10-14', 5),
(62, 'bozan@seznam.cz', 'Ota', 'Bozan', 784513785, '2017-11-01', 8),
(63, 'brouk.slamin@seznam.cz', 'Tonda', 'Slama', 2147483647, '2017-10-14', 8),
(64, 'holek@gmail.com', 'Zdenda', 'Holda', 78651324, '2017-10-14', 8);

-- --------------------------------------------------------

--
-- Table structure for table `lectures`
--

CREATE TABLE `lectures` (
  `id` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `course_registration_id` int(11) NOT NULL,
  `lecture_type_id` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lectures`
--

INSERT INTO `lectures` (`id`, `length`, `course_registration_id`, `lecture_type_id`) VALUES
(1, 2, 24, 'PPV'),
(2, 2, 24, 'PPV');

-- --------------------------------------------------------

--
-- Table structure for table `lecture_type`
--

CREATE TABLE `lecture_type` (
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lecture_type`
--

INSERT INTO `lecture_type` (`name`) VALUES
('PPV'),
('TZBJ'),
('Zdravov?da');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ico` int(11) NOT NULL,
  `web` text NOT NULL,
  `kontakt` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `ico`, `web`, `kontakt`) VALUES
(1, 'test', 1245648790, 'eqweew.cz', 'asdqwe'),
(3, 'test2', 0, '', ''),
(4, 'weqe', 0, 'qweqweqweqweqw', 'qweq'),
(5, 'qewq', 0, 'qwe', 'qwe'),
(6, 'qweqwe', 0, 'qweqweqw', 'qewwqeq'),
(7, 'qwe', 12345678, 'qweqweq', 'qweqweq'),
(8, 'wqeqwe', 11111111, 'qweqwe', 'qweqw');

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
(7, NULL, 'admin', 'admin', 'inrpo.ispa@gmail.com', 'inrpo.ispa@gmail.com', 1, 'XzEC/F1nOhHyW2jSdu72nvlx0V9bEp6BIBhU2Bp.CzU', 'DFJ/AaSjmwX6cqvT7BR9MRwKdlpERX+THI7x1F7wDXXq7XbQe5+XBVfFiNvULZbd/65MMz9HXYK/Sd/KeNdjAg==', '2017-11-27 07:11:32', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL),
(8, NULL, 'test', 'test', 'st39751@student.upce.cz', 'st39751@student.upce.cz', 1, 'PgDhfuRaMmIn9hnDLY1HC6AoWfauc0xz2qB3OJFvkKA', 'U3SWqetEGSpl58eU50WJHuEv+xgSRxXXprrZT6F/T1jKzcp4gyV5TCKMEbnMxcVG3BpGk6cr/K6GiuYL977pow==', '2017-11-20 13:00:19', 0, 0, NULL, NULL, NULL, 'a:3:{i:0;s:17:"ROLE_SONATA_ADMIN";i:1;s:32:"ROLE_SONATA_USER_ADMIN_USER_VIEW";i:2;s:32:"ROLE_SONATA_USER_ADMIN_USER_EDIT";}', 0, NULL),
(9, 1, 'skola', 'skola', 'skola@skola.cz', 'skola@skola.cz', 1, 'PlGYrWDrAf8kWVrsrwD3xGmwdpTL5iFPAU5L1VyDRO4', 'J2ZEVSYWXrzjLTAdzsY3QkXBOJsl0x95cPc++ja4FzRnnveO/Wrj/MpFHW0kl5K3FBgH2NXO2pCKPBZxXqP2mQ==', '2017-11-21 15:37:45', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(10, NULL, 'test1', 'test1', 'test1@asd.cz', 'test1@asd.cz', 1, 'CorywzM4j1zTa9LriBSaIaRwrkvwh899UJsT/euB2pw', 'GOpSor8qjHGw5LsPrMVFO/rnNK8or7r4S3yvV25Re10DCkmgSMIn9d/ZtgzJ8ZnvLq4Hvox4iT6y+wg2FNfd+Q==', '2017-11-20 13:20:04', 0, 0, NULL, NULL, NULL, 'a:11:{i:0;s:17:"ROLE_SONATA_ADMIN";i:1;s:18:"ROLE_COURSE_ACCESS";i:2;s:15:"ROLE_CAR_ACCESS";i:3;s:18:"ROLE_LECTOR_ACCESS";i:4;s:24:"ROLE_REGISTRATION_ACCESS";i:5;s:18:"ROLE_IMPORT_ACCESS";i:6;s:10:"ROLE_STAFF";i:7;s:18:"ROLE_SCHOOL_ACCESS";i:8;s:10:"ROLE_ADMIN";i:9;s:22:"ROLE_ALLOWED_TO_SWITCH";i:10;s:23:"ROLE_SONATA_USER_ACCESS";}', 0, NULL);

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
  ADD KEY `lektor_pk` (`lektor_pk`);

--
-- Indexes for table `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `course_ride`
--
ALTER TABLE `course_ride`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_registration_id` (`course_registration_id`);

--
-- Indexes for table `lectors`
--
ALTER TABLE `lectors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `lectures`
--
ALTER TABLE `lectures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_registration_id` (`course_registration_id`),
  ADD KEY `lecture_type_id` (`lecture_type_id`);

--
-- Indexes for table `lecture_type`
--
ALTER TABLE `lecture_type`
  ADD PRIMARY KEY (`name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT for table `course_registrations`
--
ALTER TABLE `course_registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `course_ride`
--
ALTER TABLE `course_ride`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lectors`
--
ALTER TABLE `lectors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;
--
-- AUTO_INCREMENT for table `lectures`
--
ALTER TABLE `lectures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
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
-- Constraints for table `course_registrations`
--
ALTER TABLE `course_registrations`
  ADD CONSTRAINT `course_registrations_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `course_ride`
--
ALTER TABLE `course_ride`
  ADD CONSTRAINT `course_ride_ibfk_1` FOREIGN KEY (`course_registration_id`) REFERENCES `course_registrations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lectors`
--
ALTER TABLE `lectors`
  ADD CONSTRAINT `lectors_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lectures`
--
ALTER TABLE `lectures`
  ADD CONSTRAINT `lectures_ibfk_1` FOREIGN KEY (`course_registration_id`) REFERENCES `course_registrations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lectures_ibfk_2` FOREIGN KEY (`lecture_type_id`) REFERENCES `lecture_type` (`name`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
