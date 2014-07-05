-- phpMyAdmin SQL Dump
-- version 4.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 26, 2014 at 04:51 PM
-- Server version: 5.5.37-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yongo`
--

-- --------------------------------------------------------

--
-- Table structure for table `agile_board`
--

CREATE TABLE IF NOT EXISTS `agile_board` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `filter_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `swimlane_strategy` varchar(20) NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `date_created` date NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Table structure for table `agile_board_column`
--

CREATE TABLE IF NOT EXISTS `agile_board_column` (
`id` bigint(20) unsigned NOT NULL,
  `agile_board_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `position` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=274 ;

-- --------------------------------------------------------

--
-- Table structure for table `agile_board_column_status`
--

CREATE TABLE IF NOT EXISTS `agile_board_column_status` (
`id` bigint(20) unsigned NOT NULL,
  `agile_board_column_id` bigint(20) unsigned NOT NULL,
  `issue_status_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=472 ;

-- --------------------------------------------------------

--
-- Table structure for table `agile_board_project`
--

CREATE TABLE IF NOT EXISTS `agile_board_project` (
`id` bigint(20) unsigned NOT NULL,
  `agile_board_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Table structure for table `agile_board_sprint`
--

CREATE TABLE IF NOT EXISTS `agile_board_sprint` (
`id` bigint(20) unsigned NOT NULL,
  `agile_board_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `started_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `finished_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` date NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=126 ;

-- --------------------------------------------------------

--
-- Table structure for table `agile_board_sprint_issue`
--

CREATE TABLE IF NOT EXISTS `agile_board_sprint_issue` (
`id` bigint(20) unsigned NOT NULL,
  `agile_board_sprint_id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `done_flag` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=592 ;

-- --------------------------------------------------------

--
-- Table structure for table `cal_calendar`
--

CREATE TABLE IF NOT EXISTS `cal_calendar` (
`id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `default_flag` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1516 ;

--
-- Dumping data for table `cal_calendar`
--

INSERT INTO `cal_calendar` (`id`, `user_id`, `name`, `description`, `color`, `default_flag`, `date_created`, `date_updated`) VALUES
(2, 255, 'Planning Meetings', '', '#A1FF9E', 1, '2013-09-17 10:53:40', NULL),
(3, 145, 'test', '', '#A1FF9E', 1, '2013-09-17 18:48:43', NULL),
(4, 3, 'a', 'a', '#A1FF9E', 1, '2013-09-20 22:43:55', NULL),
(5, 274, 'Reunião TCC', 'Planejamento/Desenvolvimento dos sprints', '#A1FF9E', 1, '2013-09-23 01:36:58', NULL),
(6, 309, 'test', '', '#A1FF9E', 1, '2013-09-25 11:47:17', NULL),
(7, 256, 'Calendar', '', '#A1FF9E', 1, '2013-09-25 20:27:06', NULL),
(8, 164, 'SIT', '', '#A1FF9E', 1, '2013-09-26 15:05:06', NULL),
(9, 2, 'Ubirimi Planning', 'Ubirimi Bird''s Eye Overview of Ubirimi Releases for 2013', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(10, 5, 'Christina Little Sparrow', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(11, 6, 'Naomi Teddy Bear', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(12, 7, 'Nicoara Dan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(13, 306, 'ttt ttt', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(14, 16, 'raj pulivart', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(15, 17, 'Vitor Reis', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(16, 18, 'Mahbubur Rahman', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(17, 19, 'Nicolas Bortolotti', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(18, 20, 'Art Valdivia', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(19, 21, 'RÃ´mulo Farias', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(20, 22, 'John Griffith', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(21, 40, 'James Baliko', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(22, 24, 'Damon Cowart', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(23, 25, 'Vasco Lopes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(24, 27, 'Luiz Tomaz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(25, 29, 'jennifer dankovic', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(26, 30, 'Carlos Garcia', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(27, 31, 'E Kort', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(28, 33, 'Cuauhtemoc Hohman', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(29, 34, 'Jesse Bowes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(30, 35, 'Sam Sehnert', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(31, 36, 'Alin Besnea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(32, 37, 'chii chan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(33, 38, 'Jens Smith', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(34, 39, 'Krystian Brazulewicz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(35, 41, 'JUSTEN STEPKA', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(36, 43, 'Sudarshan Balakrishnan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(37, 44, 'Kelvin Yap', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(38, 46, 'Jonathon C', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(39, 45, 'Bruno LECLERC', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(40, 47, 'Kelli Burkinshaw', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(41, 49, 'Alejandro Martinez', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(42, 50, 'Thivagar Velayutham', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(43, 51, 'Srikanth G', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(44, 64, 'Elena Shapovalova', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(45, 65, 'Richard Page', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(46, 67, 'Paragon Paragon', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(47, 105, 'Doug Uncle Bohl', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(48, 71, 'Travis Saron', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(49, 72, 'System Adminstrator', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(50, 73, 'Tomas Baade', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(51, 74, 'Khristina Thomas', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(52, 79, 'Romeco Dawkins', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(53, 80, 'Ronesha Thomas', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(54, 75, 'Tom Millar', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(55, 77, 'David Furniss', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(56, 90, 'John Scholes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(57, 78, 'David Katz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(58, 81, 'Cristi Filip', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(59, 82, '豪迈 陈', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(60, 88, 'Lukasz Grabowski', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(61, 89, 'Dev. bongwater', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(62, 91, 'Θωμάς Μαυραντζάς', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(63, 92, 'Χρήστος Αδάμος', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(64, 93, 'Κώστας Κορκόντζελος', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(65, 94, 'Nicolae-Virgil CRISTEA', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(66, 95, 'vasco lopes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(67, 96, 'Trevor Alicea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(68, 98, 'Nopcea Francisc', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(69, 102, 'francisc yahoo', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(70, 99, 'Flavius Nopcea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(71, 100, 'Nopcea Francisc', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(72, 101, 'Trevor Test', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(73, 173, 'Kiran Dass', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(74, 202, 'Artyom Desyatnikov', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(75, 304, 'Nopcea Flavius', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(76, 103, 'Ric Poolman', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(77, 104, 'Radu Ticiu', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(78, 106, 'Carl Morgan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(79, 107, 'Ana Jazmín Gaspar', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(80, 108, 'Ciupac Daniel', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(81, 109, 'Lucian Bala', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(82, 110, 'flavius nopcea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(83, 112, 'antonio carlos', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(84, 113, 'Raida Abulil', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(85, 250, 'Francisc MyLove', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(86, 251, 'Emanuel Cionca', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(87, 252, 'Ruth Abulil', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(88, 116, 'Danny Richardson', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(89, 117, 'Paul Noomen', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(90, 118, 'Roberto Belda', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(91, 119, 'Manolo Montañés', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(92, 120, 'Alvaro Peñalba', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(93, 121, 'Diogo Moutinho', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(94, 122, 'Norbert Bela', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(95, 123, 'Beatris Kurthy', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(96, 124, 'Jozsef Komlodi', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(97, 125, 'Eva Bukki', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(98, 126, 'Tom Tan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(99, 127, 'Teresa Fisher', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(100, 128, 'nopcea nopcea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(101, 129, 'Laurent Caillault', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(102, 130, 'nopcea ligia', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(103, 131, 'fds fds', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(104, 132, 'ff ff', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(105, 133, 'alex alex', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(106, 134, 'Mauro Litrenta', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(107, 135, 'Chris Le Petit', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(108, 136, 'Harjo Kompagnie', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(109, 137, 'Rick Bonkestoter', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(110, 138, 'Rick Bonkestoter', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(111, 139, 'Maurice Meijer', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(112, 140, 'Rick Bonkestoter', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(113, 150, 'Paul Krol', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(114, 141, 'Soren Andersen', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(115, 142, 'Rick Bonkestoter', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(116, 143, 'Hip Check', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(117, 144, 'Francis Fernandez', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(118, 146, 'demo2 demo2', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(119, 147, 'f103839@rmqkr.net f103839@rmqkr.net', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(120, 148, 'Abhishek Khanna', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(121, 152, 'Izabella Warner', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(122, 153, 'Pappy Onwuagbu', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(123, 154, 'Annie Doiron', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(124, 162, 'minhyung ko', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(125, 163, 'ayyoub zouak', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(126, 177, 'vasco lopes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(127, 179, 'Marco Salamea', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(128, 180, 'Jacques Frederico Klein Meinicke', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(129, 181, 'kk kk', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(130, 185, 'Xiao Ren', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(131, 189, 'Ricardo Dias', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(132, 212, 'Eisi Sig', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(133, 214, 'omer argun', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(134, 217, 'GREGOIRE THIEBAULT', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(135, 218, 'john red', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(136, 219, 'test test', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(137, 149, 'Lan Tra', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(138, 151, 'Chen Chen', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(139, 155, 'Bogdan Negru', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(140, 156, 'Ben Wong', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(141, 157, 'Helen Gaie', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(142, 158, 'demo2 demo2', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(143, 159, 'Maciej Staron', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(144, 160, 'Marco Alessio Milazzo', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(145, 161, 'Jake Trim', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(146, 165, 'KW Yap', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(147, 166, 'Fairul Adilla', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(148, 167, 'John Tee', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(149, 168, 'Prem Kumar', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(150, 169, 'Deepak Joshi', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(151, 170, 'Tat Leong Chong', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(152, 171, 'Narendra Saradhi', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(153, 172, 'Shirnita Yogeswaran', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(154, 174, 'Suntheri Gunasekaran', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(155, 176, 'Daniel Alfred', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(156, 178, 'Michael Gibbs', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(157, 182, 'test2 test2', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(158, 183, 'Xiaoxiao Liu', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(159, 184, 'ychu wang', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(160, 186, 'Admin Admin', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(161, 187, 'Divya Nigam', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(162, 188, 'SVN RO', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(163, 190, 'Ricardo Garvão', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(164, 191, 'LaCresia Coleman', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(165, 192, 'pusea cristina', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(166, 195, 'Emilian Saregard', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(167, 196, 'fsdafsafdsa fdsafdgfdyu', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(168, 200, 'fdsafds afdsafs', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(169, 201, 'Artyom Desyatnikov', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(170, 203, 'Joe Gerard', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(171, 204, 'Lucian Pacurar', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(172, 205, 'ion diaconescu', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(173, 206, 'Jessie Lin', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(174, 209, 'Levon Poghosyan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(175, 210, 'Ethan Chen', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(176, 211, 'Matt Merritt', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(177, 213, 'dw dw', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(178, 215, 'Alexandre Fattori', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(179, 216, 'Jurk Kis', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(180, 220, 'Changhoon Lee', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(181, 221, 'Jack T', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(182, 222, 'JO LU', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(183, 223, 'Neslihan Ozdemir', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(184, 224, 'Antoine GIRARD', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(185, 225, 'Ejner Galaz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(186, 226, 'Marcos Casal', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(187, 227, 'Gopal Vasudev', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(188, 228, 'Tim Neil', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(189, 229, 'Matthew Dean', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(190, 232, 'Ryan Hintze', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(191, 233, 'Cifra Lopez', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(192, 234, 'Riley Hume', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(193, 235, 'Jake Harding', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(194, 236, 'Brian Berard', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(195, 237, 'Klepto Kat', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(196, 238, 'Devon Veldhuis', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(197, 240, 'Chad Hall', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(198, 241, 'Rory Bell', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(199, 239, 'Yann DANIEL', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(200, 242, 'Néstor Ferrando', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(201, 243, 'akash bhardwaj', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(202, 245, 'hardik shah', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(203, 246, 'ankur pandit', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(204, 247, 'sonal dubey', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(205, 248, 'kamlesh chauhan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(206, 249, 'jayprakash jahngir', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(207, 244, 'hardik shah', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(208, 254, 'nume prim nume last', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(209, 257, 'Hatem Laadhari', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(210, 264, 'Mejdi Smaoui', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(211, 258, 'Augusto Roselli', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(212, 259, 'Daniel Peixoto', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(213, 260, 'Fábio Serra', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(214, 261, 'David Velayos', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(215, 262, 'Joaquín Garzón', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(216, 263, 'Tyler Mathews', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(217, 265, 'Ronan Rodrigo Nunes', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(218, 266, 'Dody Wicaksono', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(219, 267, 'Carlos R Villarroel A', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(220, 268, 'Mohamed Shiraz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(221, 269, 'Benjamin Lupton', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(222, 270, 'Dojo Interactive', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(223, 271, 'jose rivera', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(224, 272, 'Jaques Nilson Olivatto', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(225, 273, 'Sawa Programação', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(226, 276, 'Sawa Programação', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(227, 277, 'Max Luiz', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(228, 278, 'Wagner Cancela', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(229, 279, 'Marcos Dias', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(230, 280, 'Kareem Kamal', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(231, 281, 'Ahmed Yehia', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(232, 282, 'Alshimaa Ahmed', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(233, 283, 'Mohamed Abdou', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(234, 284, 'Omnia Abou El Moaty', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(235, 285, 'Farag El-Fadaly', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(236, 286, 'Heba Tarek', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(237, 288, 'Ahmed Hamdy', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(238, 289, 'Sarah Mostafa', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(239, 290, 'Noha Azab', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(240, 291, 'Sarah Tharwat', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(241, 292, 'Mina Atef', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(242, 293, 'Mohamed Abdel-Salam', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(243, 294, 'Mahmoud Shaheen', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(244, 295, 'Samar Hassan', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(245, 296, 'Sara Adel', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(246, 298, 'Raghda Saad', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(247, 297, 'Rondinelli Mesquita', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(248, 299, 'Jose Torres', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(249, 300, 'Joaquín Garzón Peña', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(250, 301, 'David Velayos', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(251, 302, 'Alberto Ciclone', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(252, 305, 'Florian B', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(253, 307, 'Jon-Andre Heiberg', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(254, 308, 'Andy Bourne', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(255, 310, 'Marc Hernández', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(256, 311, 'Victor Garcia', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(257, 312, 'provamhc provamhc', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(258, 313, 'mahmoud foroughi', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(259, 314, 'João Angelo de Franco', 'My default calendar', '#A1FF9E', 1, '2013-10-01 11:51:26', NULL),
(260, 145, 'cal', 'cal', NULL, NULL, '2013-10-04 12:43:39', NULL),
(261, 323, 'SampleCalendar', 'asdfffds', NULL, NULL, '2013-10-08 13:24:28', NULL),
(262, 324, 'winston', 'test', NULL, NULL, '2013-10-08 13:40:19', NULL),
(263, 335, 'a-voir-absolument.fr', 'Calendrier pour voir les étapes importantes du projet', NULL, NULL, '2013-10-14 15:49:05', NULL),
(264, 337, 'cal', '', NULL, NULL, '2013-10-15 15:24:58', NULL),
(265, 350, 'Oopost - Web | Dev Planning', 'This is the dev planning for Web version of Oopost', NULL, NULL, '2013-10-17 19:15:47', NULL),
(267, 371, 'test calendar', '', NULL, NULL, '2013-11-07 16:07:03', NULL),
(268, 2, 'Birthdays', 'a collection of birthdays for close ones', NULL, NULL, '2013-11-14 11:34:07', NULL),
(269, 2, 'Sprints', 'The sprints roadmap for 2013', NULL, NULL, '2013-11-14 11:41:08', NULL),
(270, 2, 'Major & Minor Releases', 'Roadmap for Ubirimi releases', NULL, NULL, '2013-11-14 11:41:54', NULL),
(271, 320, 'Rick Bonkestoter', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(272, 327, 'Ben van den Berg', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(273, 373, 'Phil Prett', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(274, 388, 'Liza Abulil', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(275, 318, 'Gazar Ajroemjan', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(276, 319, 'Stephan Kaspers', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(277, 340, 'Lex Langbroek', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(278, 341, 'Rawin Sewgobind', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(279, 411, 'Rick B', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(280, 322, 'Allabhakshu Shaik', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(282, 315, 'xiao bao', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(283, 316, 'Ondrej Holan', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(284, 317, 'Diego Castellon', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(285, 323, 'Lief Jill Colegado', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(286, 324, 'winston padilla', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(287, 325, 'Ben van den Berg', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(288, 326, 'Bernice van den Berg', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(289, 328, 'Paul Nassar', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(290, 329, 'nAPs Jr.', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(291, 330, 'Ron Buddika', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(292, 331, 'Suvimali Rajapaksha', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(293, 332, 'Ranhiru Cooray', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(295, 334, 'Samra Abdul', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(296, 335, 'alain osifre', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(297, 336, 'Daniel Matriccino', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(298, 337, 'Damiano Curreri', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(299, 338, 'Davide Zavattero', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(300, 339, 'Łukasz Gawior', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(301, 342, 'Jae-su Uhm', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(302, 343, 'Hansen Chew', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(303, 344, 'Han The Man', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(304, 345, 'Sian Yue Tan', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(305, 387, 'Yin Ouyang', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(306, 389, 'Rat Test', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(307, 392, 'Dickson Teo', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(308, 394, 'Dominic Tay Kang Rong', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(309, 395, 'Mark Hong', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(310, 346, 'guido grazioli', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(311, 347, 'test test', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(312, 348, 'Jose Antonio Diaz Damian', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(313, 350, 'Clément Hector', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(314, 351, 'Sopheap Seng', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(315, 352, 'François Giang', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(316, 353, 'Kannika Kong', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(317, 354, 'Yi Sophally', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(318, 355, 'Chan Danit', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(319, 364, 'Bruno Bandeira', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(320, 365, 'greenkey loman', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(321, 366, 'Ion Jhon', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(322, 367, 'g33k z0rd', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(323, 368, 'Tamas Soltesz', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(325, 370, 'Geza Csorba', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(326, 371, 'Almeno Soares', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(327, 375, 'Rui Peixoto', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(328, 376, 'Alberto Pereira', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(329, 372, 'Martin Vetter', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(330, 377, 'Win Beek', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(331, 378, 'Win Beek', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(332, 380, 'White Paragraph', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(333, 381, 'Davide Zavattero', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(334, 382, 'Henrique Donzelli', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(335, 384, 'Fernando Rywac', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(336, 385, 'Jeroen Meeus', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(337, 390, 'Alejandro Llermanos', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(338, 391, 'MediaTainment Group', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(339, 393, 'Matteo Origlia', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(340, 398, 'Yannic Meerbergen', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(341, 399, 'mlac jellor', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(342, 400, 'Atilio Garcia', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(343, 401, 'Luis Sztul', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(344, 402, 'Jose Miguel Peralta', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(345, 403, 'Jesse Häkli', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(346, 404, 'Kimmo Silander', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(347, 405, 'Jani Lääperi', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(348, 412, 'Mika Makkonen', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(349, 406, 'Nikita Kazeev', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(350, 407, 'Gabriel Membrillo', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(351, 413, 'Juergen Koch', 'My default calendar', '#A1FF9E', 1, '2013-11-18 16:44:38', NULL),
(353, 415, 'The Baron', 'My default calendar', '#A1FF9E', 1, '2013-11-20 11:04:16', NULL),
(354, 1878, 'damith Premakumara', 'My default calendar', '#A1FF9E', 1, '2013-11-21 10:50:19', NULL),
(355, 1885, 'jfs jfs', 'My default calendar', '#A1FF9E', 1, '2013-11-21 10:57:01', NULL),
(356, 1886, 'Mihai Gomoi', 'My default calendar', '#A1FF9E', 1, '2013-11-21 10:57:01', NULL),
(357, 1887, 'gdsfgf sdgfsdgfsd', 'My default calendar', '#A1FF9E', 1, '2013-11-21 10:57:01', NULL),
(358, 1888, 'Cristi Beres', 'My default calendar', '#A1FF9E', 1, '2013-11-21 16:51:01', NULL),
(359, 1889, 'fsdaf sdfsdafsd', 'My default calendar', '#A1FF9E', 1, '2013-11-22 16:04:01', NULL),
(360, 1890, 'Dan-Alexandru Nicoara', 'My default calendar', '#A1FF9E', 1, '2013-11-23 17:13:01', NULL),
(361, 1891, 'Raji Abulil', 'My default calendar', '#A1FF9E', 1, '2013-11-24 21:45:01', NULL),
(362, 1892, 'Demo Demo', 'My default calendar', '#A1FF9E', 1, '2013-11-25 07:54:01', NULL),
(363, 113, 'gfgb', '', '#FAE3FF', NULL, '2013-11-27 15:51:23', NULL),
(364, 1893, 'Raida Abulil', 'My default calendar', '#A1FF9E', 1, '2013-11-27 15:56:01', NULL),
(365, 1894, 'Natalie Correa', 'My default calendar', '#A1FF9E', 1, '2013-11-27 18:47:01', NULL),
(367, 400, 'Pilot Events', 'Eventos varios relacionados a proyecto pilot.', '#4576FF', NULL, '2013-11-28 20:03:17', NULL),
(368, 1895, 'Vam Chang', 'My default calendar', '#A1FF9E', 1, '2013-12-02 06:44:01', NULL),
(369, 1896, 'teste tetse', 'My default calendar', '#A1FF9E', 1, '2013-12-06 01:23:01', NULL),
(370, 1897, 'Max Luiz', 'My default calendar', '#A1FF9E', 1, '2013-12-06 01:49:01', NULL),
(371, 1898, 'Max Lynam', 'My default calendar', '#A1FF9E', 1, '2013-12-07 04:36:01', NULL),
(372, 1899, 'Mauricio Lu00f3pez', 'My default calendar', '#A1FF9E', 1, '2013-12-07 11:11:01', NULL),
(373, 1900, 'tt tt', 'My default calendar', '#A1FF9E', 1, '2013-12-07 12:41:01', NULL),
(374, 1901, 'Nayana Soman', 'My default calendar', '#A1FF9E', 1, '2013-12-09 12:03:13', NULL),
(375, 1902, 'praful patel', 'My default calendar', '#A1FF9E', 1, '2013-12-09 08:11:02', NULL),
(376, 1903, 'parthiv khatri', 'My default calendar', '#A1FF9E', 1, '2013-12-09 08:17:31', NULL),
(377, 1904, 'Ram G', 'My default calendar', '#A1FF9E', 1, '2013-12-09 20:07:01', NULL),
(378, 1905, 'Ernesto Hernu00e1ndez', 'My default calendar', '#A1FF9E', 1, '2013-12-09 23:42:01', NULL),
(379, 1906, 'Juan Gauna', 'My default calendar', '#A1FF9E', 1, '2013-12-10 03:55:02', NULL),
(380, 1907, 'Seppo Ikonen', 'My default calendar', '#A1FF9E', 1, '2013-12-10 13:02:51', NULL),
(381, 1908, 'Ken Leung', 'My default calendar', '#A1FF9E', 1, '2013-12-11 07:37:01', NULL),
(382, 1909, 'kin kin', 'My default calendar', '#A1FF9E', 1, '2013-12-11 11:33:01', NULL),
(383, 1910, 'Sangeeta Bhattacharya', 'My default calendar', '#A1FF9E', 1, '2013-12-12 15:20:06', NULL),
(384, 1892, 'test', 'test', '#A1FF9E', NULL, '2013-12-14 10:24:56', NULL),
(385, 1912, 'kk kk', 'My default calendar', '#A1FF9E', 1, '2013-12-19 08:53:01', NULL),
(386, 1913, 'Attack Nguyu1ec5n', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:25:01', NULL),
(387, 1914, 'Thanh Đặng', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:31:35', NULL),
(388, 1915, 'Triết Hoàng', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:32:19', NULL),
(389, 1916, 'Tiến Trần', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:33:01', NULL),
(390, 1917, 'Dũng Văn', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:33:57', NULL),
(391, 1918, 'Vinh Khung', 'My default calendar', '#A1FF9E', 1, '2013-12-22 20:56:20', NULL),
(392, 1919, 'Quang Pham', 'My default calendar', '#A1FF9E', 1, '2013-12-28 03:51:01', NULL),
(393, 1920, 'quang pxquang', 'My default calendar', '#A1FF9E', 1, '2013-12-28 20:14:07', NULL),
(394, 1921, 'y q', 'My default calendar', '#A1FF9E', 1, '2013-12-28 21:32:02', NULL),
(395, 1922, 'Alex Hogan', 'My default calendar', '#A1FF9E', 1, '2013-12-29 19:31:01', NULL),
(398, 1925, 'Vo Le Quynh My', 'My default calendar', '#A1FF9E', 1, '2013-12-30 17:39:01', NULL),
(399, 1926, 'Jaques Nilson Olivatto', 'My default calendar', '#A1FF9E', 1, '2014-01-03 12:07:01', NULL),
(400, 1927, 'Cristi Onaga', 'My default calendar', '#A1FF9E', 1, '2014-01-03 13:46:01', NULL),
(401, 1928, 'gfdgfds gfsdgfsd', 'My default calendar', '#A1FF9E', 1, '2014-01-03 16:42:01', NULL),
(405, 1932, 'Ciaran Crocker', 'My default calendar', '#A1FF9E', 1, '2014-01-09 15:36:02', NULL),
(406, 1933, 'Andrea Ggg', 'My default calendar', '#A1FF9E', 1, '2014-01-11 18:46:01', NULL),
(408, 1935, 'Elena Shapovalova', 'My default calendar', '#A1FF9E', 1, '2014-01-19 22:51:01', NULL),
(409, 1892, 'kj', 'nn', '#A1FF9E', NULL, '2014-01-21 21:43:03', '2014-01-21 21:43:21'),
(410, 1936, 'Senal Kumarage', 'My default calendar', '#A1FF9E', 1, '2014-01-22 14:05:05', NULL),
(411, 1937, 'Janrik Viljanen', 'My default calendar', '#A1FF9E', 1, '2014-01-22 17:21:02', NULL),
(412, 1938, 'Michał Kaźmierczyk', 'My default calendar', '#A1FF9E', 1, '2014-01-23 18:37:48', NULL),
(413, 1939, 'Ravindra M', 'My default calendar', '#A1FF9E', 1, '2014-01-24 08:48:01', NULL),
(416, 1943, 'Kareem Kamal', 'My default calendar', '#A1FF9E', 1, '2014-02-04 13:01:01', NULL),
(417, 1944, 'Jonathan Geers', 'My default calendar', '#A1FF9E', 1, '2014-02-04 15:51:01', NULL),
(419, 1947, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:04:02', NULL),
(420, 1948, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:05:01', NULL),
(421, 1949, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:06:01', NULL),
(422, 1950, 'tare tare', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:06:10', NULL),
(423, 1951, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:07:01', NULL),
(424, 1952, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:08:01', NULL),
(425, 1953, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:09:01', NULL),
(426, 1954, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:10:01', NULL),
(427, 1955, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:11:01', NULL),
(428, 1956, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:12:01', NULL),
(429, 1957, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:13:01', NULL),
(430, 1958, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:14:01', NULL),
(431, 1959, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:15:01', NULL),
(432, 1960, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:16:01', NULL),
(433, 1961, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:17:02', NULL),
(434, 1962, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:18:01', NULL),
(435, 1963, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:19:01', NULL),
(436, 1964, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:20:01', NULL),
(437, 1965, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:21:02', NULL),
(438, 1966, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:22:02', NULL),
(439, 1967, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:23:01', NULL),
(440, 1968, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:24:01', NULL),
(441, 1969, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:25:01', NULL),
(442, 1970, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:26:01', NULL),
(443, 1971, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:27:01', NULL),
(444, 1972, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:28:01', NULL),
(445, 1973, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:29:01', NULL),
(446, 1974, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:30:01', NULL),
(447, 1975, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:31:01', NULL),
(448, 1976, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:32:01', NULL),
(449, 1977, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:33:01', NULL),
(450, 1978, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:34:02', NULL),
(451, 1979, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:35:01', NULL),
(452, 1980, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:36:01', NULL),
(453, 1981, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:37:02', NULL),
(454, 1982, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:38:01', NULL),
(455, 1983, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:39:01', NULL),
(456, 1984, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:40:01', NULL),
(457, 1985, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:41:01', NULL),
(458, 1986, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:42:01', NULL),
(459, 1987, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:43:01', NULL),
(460, 1988, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:44:01', NULL),
(461, 1989, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:45:02', NULL),
(462, 1990, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:46:01', NULL),
(463, 1991, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:47:01', NULL),
(464, 1992, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:48:01', NULL),
(465, 1993, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:49:02', NULL),
(466, 1994, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:50:01', NULL),
(467, 1995, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:51:01', NULL),
(468, 1996, 'test11 test22', 'My default calendar', '#A1FF9E', 1, '2014-02-10 22:52:01', NULL),
(472, 2000, 'Amol Sharma', 'My default calendar', '#A1FF9E', 1, '2014-02-11 07:19:01', NULL),
(473, 2001, 'fdsfasdfsa ffsdafds', 'My default calendar', '#A1FF9E', 1, '2014-02-11 07:39:40', NULL),
(487, 2015, 'fsdafs fsda', 'My default calendar', '#A1FF9E', 1, '2014-02-11 11:51:01', NULL),
(488, 2016, 'gg gg', 'My default calendar', '#A1FF9E', 1, '2014-02-11 11:53:01', NULL),
(489, 2017, 'fdsafs fdsafsda', 'My default calendar', '#A1FF9E', 1, '2014-02-11 11:53:16', NULL),
(490, 2018, 'Adrian Vornic', 'My default calendar', '#A1FF9E', 1, '2014-02-11 13:01:27', NULL),
(491, 2019, 'dsadsada sdsad', 'My default calendar', '#A1FF9E', 1, '2014-02-13 14:25:01', NULL),
(493, 2021, 'weqwe wefr', 'My default calendar', '#A1FF9E', 1, '2014-02-13 15:10:42', NULL),
(494, 2022, 'Stancu Daniel', 'My default calendar', '#A1FF9E', 1, '2014-02-13 15:11:02', NULL),
(495, 1892, 'alert(3)', 'alert(3)', '#AA11FF', NULL, '2014-02-13 15:13:14', NULL),
(496, 1892, 'dsasa', 'das', '#A1FF9E', NULL, '2014-02-13 15:27:30', NULL),
(497, 2023, 'romeo man', 'My default calendar', '#A1FF9E', 1, '2014-02-13 17:35:01', NULL),
(498, 1892, 'ma-ta', '', '#7438FF', NULL, '2014-02-13 21:29:19', NULL),
(499, 2024, 'Manjit Singh', 'My default calendar', '#A1FF9E', 1, '2014-02-14 02:07:01', NULL),
(500, 2025, 'Valentin Visan', 'My default calendar', '#A1FF9E', 1, '2014-02-14 11:03:02', NULL),
(501, 2026, 'Valentin Visan', 'My default calendar', '#A1FF9E', 1, '2014-02-14 11:18:01', NULL),
(502, 2027, 'Admin Tester', 'My default calendar', '#A1FF9E', 1, '2014-02-14 20:24:01', NULL),
(503, 2028, 'Victor Bojica', 'My default calendar', '#A1FF9E', 1, '2014-02-16 21:37:01', NULL),
(504, 2029, 'Ekart Dragos-Ioan', 'My default calendar', '#A1FF9E', 1, '2014-02-17 16:02:01', NULL),
(505, 2029, 'da', 'dsa', '#A07DFF', NULL, '2014-02-17 16:05:46', NULL),
(506, 2030, 'test data', 'My default calendar', '#A1FF9E', 1, '2014-02-19 13:32:01', NULL),
(508, 2032, 'tt tt', 'My default calendar', '#A1FF9E', 1, '2014-02-20 17:55:01', NULL),
(509, 2033, 'Angel Ceballos', 'My default calendar', '#A1FF9E', 1, '2014-02-22 04:05:01', NULL),
(510, 2034, 'Levon Poghosyan', 'My default calendar', '#A1FF9E', 1, '2014-02-22 22:26:01', NULL),
(511, 2035, 'Ali sk', 'My default calendar', '#A1FF9E', 1, '2014-02-24 07:43:02', NULL),
(512, 2036, 'ali sk', 'My default calendar', '#A1FF9E', 1, '2014-02-25 05:31:01', NULL),
(513, 2037, 'Laurentiu Oprea', 'My default calendar', '#A1FF9E', 1, '2014-02-25 09:32:01', NULL),
(514, 2038, 'Sean Lim', 'My default calendar', '#A1FF9E', 1, '2014-03-04 13:53:19', NULL),
(515, 2039, 'gg gg', 'My default calendar', '#A1FF9E', 1, '2014-03-04 15:20:02', NULL),
(516, 2040, 'Jay Phua', 'My default calendar', '#A1FF9E', 1, '2014-03-04 15:21:06', NULL),
(517, 2041, 'LiangDeng Fan', 'My default calendar', '#A1FF9E', 1, '2014-03-05 03:57:04', NULL),
(518, 2042, 'Tze Yi Ang', 'My default calendar', '#A1FF9E', 1, '2014-03-05 10:02:11', NULL),
(519, 2043, 'Albert Himawan', 'My default calendar', '#A1FF9E', 1, '2014-03-05 10:02:41', NULL),
(520, 2044, 'Aswani kumar', 'My default calendar', '#A1FF9E', 1, '2014-03-09 07:05:01', NULL),
(521, 2045, 'gfdgfsd gfdsgsdf', 'My default calendar', '#A1FF9E', 1, '2014-03-11 07:54:01', NULL),
(522, 2046, 'Testing Cristi', 'My default calendar', '#A1FF9E', 1, '2014-03-11 12:42:28', NULL),
(523, 2047, 'Mustafa Fakhir', 'My default calendar', '#A1FF9E', 1, '2014-03-11 16:35:45', NULL),
(524, 2048, 'Michael Siegler', 'My default calendar', '#A1FF9E', 1, '2014-03-11 15:36:02', NULL),
(525, 2049, 'Matt Motion', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:42:01', NULL),
(526, 2050, 'George Mesaritis', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:42:02', NULL),
(527, 2051, 'Anu017ee Jenu0161terle', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:43:01', NULL),
(528, 2052, 'Keven Bouchard', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:43:01', NULL),
(529, 2053, 'Ieuan Kessels', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:47:01', NULL),
(530, 2054, 'Joseph McElroy', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:49:01', NULL),
(531, 2055, 'Marc-Julien Objois', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:50:01', NULL),
(532, 2056, 'stuart clark', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:51:02', NULL),
(533, 2057, 'Ross Masters', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:51:02', NULL),
(534, 2058, 'Anze Jensterle', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:51:03', NULL),
(535, 2059, 'Alastair McDermott', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:53:01', NULL),
(536, 2060, 'Paul Scobie', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:54:01', NULL),
(537, 2061, 'Joshua Knauer', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:55:01', NULL),
(538, 2062, 'Clifford Duke', 'My default calendar', '#A1FF9E', 1, '2014-03-12 15:55:01', NULL),
(539, 2063, 'Brendan Devenney', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:00:02', NULL),
(540, 2064, 'stefanos papadimitriou', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:01:01', NULL),
(541, 2065, 'Steven Kemper', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:01:01', NULL),
(542, 2066, 'Ayoub Khote', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:04:01', NULL),
(543, 2067, 'Lucian Petrescu', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:07:01', NULL),
(544, 2068, 'Kohei Takara', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:07:01', NULL),
(545, 2069, 'Ben Fox', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:09:01', NULL),
(546, 2070, 'Demetrius Bassoukos', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:10:01', NULL),
(547, 2071, 'matt clegg', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:13:02', NULL),
(548, 2072, 'Tobias Vandenbempt', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:14:01', NULL),
(549, 2073, 'Justin Honesto', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:17:01', NULL),
(550, 2074, 'Cameron Steele', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:17:01', NULL),
(551, 2075, 'Navjot Singh', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:17:01', NULL),
(552, 2076, 'Koen Everaert', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:18:01', NULL),
(553, 2077, 'Cameron Kennedy', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:21:01', NULL),
(554, 2078, 'Skylar Sommers', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:23:02', NULL),
(555, 2079, 'Max Feller', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:23:02', NULL),
(556, 2080, 'Larbi Benzakour', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:25:01', NULL),
(557, 2081, 'James Evans', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:25:02', NULL),
(558, 2082, 'Jeshua Rains', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:26:01', NULL),
(559, 2083, 'Ken Egbuna', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:27:01', NULL),
(560, 2084, 'nuri nurbachsch', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:29:01', NULL),
(561, 2085, 'Jesse Nowlin', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:32:01', NULL),
(562, 2086, 'Allen Seiger', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:37:01', NULL),
(563, 2087, 'Kat Sanders', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:38:01', NULL),
(564, 2088, 'Mushabab Thengat', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:47:01', NULL),
(565, 2089, 'Cosmin Jelea', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:48:01', NULL),
(566, 2090, 'Kevin Heruer', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:48:02', NULL),
(567, 2091, 'Jeremy Perez', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:54:01', NULL),
(568, 2092, 'Cameron Cummings', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:57:02', NULL),
(569, 2093, 'Tyler de Arrigunaga', 'My default calendar', '#A1FF9E', 1, '2014-03-12 16:58:01', NULL),
(570, 2094, 'Terje Tysse', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:01:01', NULL),
(571, 2095, 'Gonu00e7alo Ribeiro', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:05:01', NULL);
INSERT INTO `cal_calendar` (`id`, `user_id`, `name`, `description`, `color`, `default_flag`, `date_created`, `date_updated`) VALUES
(572, 2096, 'Mohsiur Rahman', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:07:01', NULL),
(573, 2097, 'Morten Formo', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:09:01', NULL),
(574, 2098, 'Cody Foster-Demeny', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:16:02', NULL),
(575, 2099, 'Chris Taylor', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:19:01', NULL),
(576, 2100, 'Kevin Mogridge', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:20:01', NULL),
(577, 2101, 'Karim Maassen', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:26:02', NULL),
(578, 2102, 'Wade Garrett', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:26:02', NULL),
(579, 2103, 'Ross Derewianko', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:33:01', NULL),
(580, 2104, 'Markus Scully', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:36:01', NULL),
(581, 2105, 'Jack Weber', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:44:01', NULL),
(583, 2107, 'Greg Starling', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:48:01', NULL),
(584, 2108, 'Adrian Sule', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:48:47', NULL),
(585, 2109, 'Aamir Abro', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:49:01', NULL),
(586, 2110, 'Henry Demarest', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:52:01', NULL),
(587, 2111, 'George Mesaritis', 'My default calendar', '#A1FF9E', 1, '2014-03-12 17:55:01', NULL),
(588, 2112, 'Eric Burke', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:11:01', NULL),
(589, 2113, 'Mustafa Gezen', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:14:01', NULL),
(590, 2114, 'George Mesaritis', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:15:01', NULL),
(591, 2115, 'Hsiu-Fan Wang', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:28:01', NULL),
(592, 2116, 'Paul Mead', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:34:01', NULL),
(593, 2117, 'Nathan Burgess', 'My default calendar', '#A1FF9E', 1, '2014-03-12 18:48:01', NULL),
(594, 2118, 'Brandon Costa', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:00:01', NULL),
(595, 2119, 'George Mesaritis', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:03:01', NULL),
(596, 2120, 'Robert Hurst', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:05:01', NULL),
(597, 2121, 'Agash Thamo', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:10:01', NULL),
(598, 2122, 'Eric Jaw', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:14:01', NULL),
(599, 2123, 'Ryan Clarke', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:29:01', NULL),
(600, 2124, 'Johan Lindau', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:55:01', NULL),
(601, 2125, 'Marco Deppe', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:57:02', NULL),
(602, 2126, 'John Pacific', 'My default calendar', '#A1FF9E', 1, '2014-03-12 19:58:01', NULL),
(604, 2128, 'Luka Cosic', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:20:01', NULL),
(605, 2129, 'Sean Bailey', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:22:02', NULL),
(606, 2130, 'sean greenawalt', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:27:01', NULL),
(607, 2131, 'Sam Matrouh', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:29:01', NULL),
(608, 2132, 'Zsolt K.', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:40:01', NULL),
(609, 2133, 'Daniel Twigg', 'My default calendar', '#A1FF9E', 1, '2014-03-12 20:57:01', NULL),
(610, 2134, 'Floki Toki', 'My default calendar', '#A1FF9E', 1, '2014-03-12 21:02:01', NULL),
(611, 2135, 'Andru00e9 Ramos', 'My default calendar', '#A1FF9E', 1, '2014-03-12 21:44:01', NULL),
(612, 2136, 'Timon van Spronsen', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:09:01', NULL),
(613, 2137, 'Cha Li', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:14:01', NULL),
(614, 2138, 'Kasper Hvid', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:16:01', NULL),
(615, 2139, 'Cesar Sanchez', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:19:01', NULL),
(616, 2140, 'Norma Payne', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:49:01', NULL),
(617, 2141, 'Jeremy Gower', 'My default calendar', '#A1FF9E', 1, '2014-03-12 22:59:01', NULL),
(618, 2142, 'Michelle Goodwin', 'My default calendar', '#A1FF9E', 1, '2014-03-12 23:21:01', NULL),
(619, 2143, 'Sharif Mohamed', 'My default calendar', '#A1FF9E', 1, '2014-03-12 23:30:02', NULL),
(620, 2144, 'Connor Carr', 'My default calendar', '#A1FF9E', 1, '2014-03-12 23:44:01', NULL),
(621, 2145, 'Michael Chidgey', 'My default calendar', '#A1FF9E', 1, '2014-03-13 02:20:01', NULL),
(622, 2146, 'Logan Sprole', 'My default calendar', '#A1FF9E', 1, '2014-03-13 02:39:02', NULL),
(623, 2147, 'Jake Hydie', 'My default calendar', '#A1FF9E', 1, '2014-03-13 03:07:01', NULL),
(624, 2148, 'Sam Cross', 'My default calendar', '#A1FF9E', 1, '2014-03-13 04:06:01', NULL),
(625, 2149, 'Bhuvanesh Rajanna', 'My default calendar', '#A1FF9E', 1, '2014-03-13 05:21:02', NULL),
(626, 2150, 'Michael Pilapil', 'My default calendar', '#A1FF9E', 1, '2014-03-13 06:02:01', NULL),
(627, 2151, 'Zhi Yong Fam', 'My default calendar', '#A1FF9E', 1, '2014-03-13 08:15:01', NULL),
(628, 2152, 'Julien Henrotte', 'My default calendar', '#A1FF9E', 1, '2014-03-13 08:54:02', NULL),
(629, 2153, 'Evelijn Saaltink', 'My default calendar', '#A1FF9E', 1, '2014-03-13 12:49:01', NULL),
(630, 2154, 'David B', 'My default calendar', '#A1FF9E', 1, '2014-03-13 13:02:01', NULL),
(631, 2155, 'Human Resources Department', 'My default calendar', '#A1FF9E', 1, '2014-03-13 14:04:02', NULL),
(632, 2156, 'Sameer Jaiswal', 'My default calendar', '#A1FF9E', 1, '2014-03-13 14:16:02', NULL),
(633, 2157, 'Robert Adach', 'My default calendar', '#A1FF9E', 1, '2014-03-13 14:35:01', NULL),
(634, 2158, 'bryan bailey', 'My default calendar', '#A1FF9E', 1, '2014-03-13 16:23:02', NULL),
(635, 2159, 'Luca Lolesel', 'My default calendar', '#A1FF9E', 1, '2014-03-13 16:54:01', NULL),
(636, 2160, 'Ariel Resnik', 'My default calendar', '#A1FF9E', 1, '2014-03-13 16:56:01', NULL),
(637, 2161, 'Jan Bentzen', 'My default calendar', '#A1FF9E', 1, '2014-03-13 19:30:01', NULL),
(638, 2162, 'Benjamin Lack', 'My default calendar', '#A1FF9E', 1, '2014-03-13 19:58:01', NULL),
(639, 2163, 'Daniel Hutchins', 'My default calendar', '#A1FF9E', 1, '2014-03-13 20:11:56', NULL),
(640, 2164, 'Joshua Kennedy', 'My default calendar', '#A1FF9E', 1, '2014-03-13 20:12:01', NULL),
(641, 2165, 'Aiden Lilley', 'My default calendar', '#A1FF9E', 1, '2014-03-13 20:18:36', NULL),
(642, 2166, 'Hadrian Paulo Lim', 'My default calendar', '#A1FF9E', 1, '2014-03-13 23:55:02', NULL),
(643, 2167, 'Cy Messmer', 'My default calendar', '#A1FF9E', 1, '2014-03-14 04:22:01', NULL),
(644, 2168, 'Daniel Lu00f8vbru00f8tte Olsen', 'My default calendar', '#A1FF9E', 1, '2014-03-14 07:33:01', NULL),
(645, 2169, 'praveen kumar', 'My default calendar', '#A1FF9E', 1, '2014-03-14 08:36:01', NULL),
(646, 2170, 'Daniel Ibu00e1u00f1ez', 'My default calendar', '#A1FF9E', 1, '2014-03-14 09:57:01', NULL),
(647, 2171, 'John Halligan', 'My default calendar', '#A1FF9E', 1, '2014-03-14 15:45:01', NULL),
(648, 2172, 'Joseph Vignola', 'My default calendar', '#A1FF9E', 1, '2014-03-15 00:28:01', NULL),
(649, 2172, 'My Troop Manger', 'The Team Calendar for the project', '#A1FF9E', NULL, '2014-03-15 01:19:00', NULL),
(650, 2173, 'Joe Vignola', 'My default calendar', '#A1FF9E', 1, '2014-03-15 01:22:08', NULL),
(651, 2174, 'Aubrey Smith', 'My default calendar', '#A1FF9E', 1, '2014-03-15 22:23:01', NULL),
(652, 2174, 'Planning PHase', 'This is where the planning will be laid out with deadlines for updates.', '#A1FF9E', NULL, '2014-03-15 22:47:03', NULL),
(653, 2175, 'Nathan Kinch', 'My default calendar', '#A1FF9E', 1, '2014-03-16 06:12:01', NULL),
(654, 2176, 'Stuart Hudson', 'My default calendar', '#A1FF9E', 1, '2014-03-16 11:40:20', NULL),
(655, 2177, 'Botija Guri', 'My default calendar', '#A1FF9E', 1, '2014-03-16 13:45:01', NULL),
(657, 2178, 'Richard Kim', 'My default calendar', '#A1FF9E', 1, '2014-03-17 20:01:01', NULL),
(658, 2179, 'Nicholas Harrod', 'My default calendar', '#A1FF9E', 1, '2014-03-17 22:32:22', NULL),
(659, 2180, 'Safiyy Kanjiyani', 'My default calendar', '#A1FF9E', 1, '2014-03-18 00:12:01', NULL),
(660, 2181, 'Matt Pacura', 'My default calendar', '#A1FF9E', 1, '2014-03-19 19:24:01', NULL),
(661, 2182, 'Ovidiu Halmagean', 'My default calendar', '#A1FF9E', 1, '2014-03-20 12:05:01', NULL),
(662, 2183, 'Leo Leon', 'My default calendar', '#A1FF9E', 1, '2014-03-20 13:12:21', NULL),
(664, 2185, 'Anca Zapuc', 'My default calendar', '#A1FF9E', 1, '2014-03-20 13:41:01', NULL),
(665, 2186, 'Coco Soodek', 'My default calendar', '#A1FF9E', 1, '2014-03-21 18:34:01', NULL),
(666, 2187, 'Darren Hignett', 'My default calendar', '#A1FF9E', 1, '2014-03-25 14:02:01', NULL),
(668, 2189, 'Pedro Martinez', 'My default calendar', '#A1FF9E', 1, '2014-03-26 19:15:01', NULL),
(669, 329, 'MSH', '', '#45C7FF', NULL, '2014-03-27 19:19:36', NULL),
(670, 2190, 'Karthik Ganesan', 'My default calendar', '#A1FF9E', 1, '2014-03-28 10:23:01', NULL),
(671, 2191, 'Alexey Mishin', 'My default calendar', '#A1FF9E', 1, '2014-03-28 11:49:01', NULL),
(673, 2193, 'Raida Abulil', 'My default calendar', '#A1FF9E', 1, '2014-03-28 15:49:38', NULL),
(674, 2194, 'Nick van Etten', 'My default calendar', '#A1FF9E', 1, '2014-03-30 22:15:05', NULL),
(675, 2195, 'Nouman Tayyab', 'My default calendar', '#A1FF9E', 1, '2014-04-01 02:26:07', NULL),
(676, 2196, 'Yuana Jaafar', 'My default calendar', '#A1FF9E', 1, '2014-04-04 12:33:06', NULL),
(677, 2197, 'Mazurahayati Md Yusop', 'My default calendar', '#A1FF9E', 1, '2014-04-04 14:51:31', NULL),
(678, 2198, 'u0412u043bu0430u0434 u0421u043eu043bu043eu0432u044cu0451u0432', 'My default calendar', '#A1FF9E', 1, '2014-04-04 10:16:01', NULL),
(679, 2199, 'Nithila Shanmugananthan', 'My default calendar', '#A1FF9E', 1, '2014-04-07 07:02:40', NULL),
(680, 2200, 'Happy Day', 'My default calendar', '#A1FF9E', 1, '2014-04-07 08:09:52', NULL),
(682, 2202, 'Alx K.', 'My default calendar', '#A1FF9E', 1, '2014-04-08 09:01:07', NULL),
(683, 2203, 'Andrei Ghioc', 'My default calendar', '#A1FF9E', 1, '2014-04-08 20:21:16', NULL),
(684, 2204, 'Safar Mansoor', 'My default calendar', '#A1FF9E', 1, '2014-04-08 19:49:01', NULL),
(688, 2208, 'Toby Martin', 'My default calendar', '#A1FF9E', 1, '2014-04-10 17:35:57', NULL),
(689, 2209, 'Nicolas Ulloa Olguin', 'My default calendar', '#A1FF9E', 1, '2014-04-11 09:56:01', NULL),
(690, 2210, 'Petrica GRIGORE', 'My default calendar', '#A1FF9E', 1, '2014-04-11 14:13:45', NULL),
(691, 2211, 'Sam Burns', 'My default calendar', '#A1FF9E', 1, '2014-04-15 21:46:01', NULL),
(692, 2212, 'ahmed elsayed', 'My default calendar', '#A1FF9E', 1, '2014-04-16 23:30:53', NULL),
(693, 2213, 'Yvo S.', 'My default calendar', '#A1FF9E', 1, '2014-04-17 16:56:37', NULL),
(694, 2214, 'prem kumar', 'My default calendar', '#A1FF9E', 1, '2014-04-21 07:56:01', NULL),
(695, 2215, 'rb dash', 'My default calendar', '#A1FF9E', 1, '2014-04-23 08:00:01', NULL),
(696, 2216, 'Alin Potocean', 'My default calendar', '#A1FF9E', 1, '2014-04-26 18:18:17', NULL),
(697, 369, 'Phil Prett', 'The primary calendar', '#A1FF9E', 1, '2014-04-30 12:58:48', NULL),
(698, 2217, 'qq qq', 'My default calendar', '#A1FF9E', 1, '2014-05-08 22:13:21', NULL),
(699, 2218, 'Ivan Vallejos', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:01', NULL),
(700, 2219, 'John Crosby', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:02', NULL),
(701, 2220, 'John Crosby', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:02', NULL),
(702, 2221, 'Ethan Voorhees', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:02', NULL),
(703, 2222, 'Ethan Voorhees', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:02', NULL),
(704, 2223, 'Clemente Moraleda', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:02', NULL),
(705, 2224, 'Clemente Moraleda', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:03', NULL),
(706, 2225, 'Erik Brisson', 'My default calendar', '#A1FF9E', 1, '2014-05-08 19:17:03', NULL),
(707, 2226, 'Jonah Ahvonen', 'My default calendar', '#A1FF9E', 1, '2014-05-12 12:39:24', NULL),
(708, 2227, 'Ovidiu Matan', 'My default calendar', '#A1FF9E', 1, '2014-05-14 10:00:01', NULL),
(709, 2227, 'rr', 'rr', '#A1FF9E', NULL, '2014-05-14 13:35:46', NULL),
(710, 2227, 'rrr', 'rrr', '#925CFF', NULL, '2014-05-14 13:36:21', NULL),
(711, 2228, 'Ovidiu Matan', 'My default calendar', '#A1FF9E', 1, '2014-05-14 13:41:24', NULL),
(712, 2229, 'Jamillo Santos', 'My default calendar', '#A1FF9E', 1, '2014-05-16 22:22:01', NULL),
(713, 2230, 'eom eom', 'My default calendar', '#A1FF9E', 1, '2014-05-22 16:37:02', NULL),
(714, 2231, 'aniello sgambato', 'My default calendar', '#A1FF9E', 1, '2014-05-22 17:46:26', NULL),
(715, 2232, 'Rodolfo Ramilli', 'My default calendar', '#A1FF9E', 1, '2014-05-22 19:10:37', NULL),
(716, 2233, 't Gladiator', 'My default calendar', '#A1FF9E', 1, '2014-05-23 09:10:02', NULL),
(717, 2234, 'Jason Hawks', 'My default calendar', '#A1FF9E', 1, '2014-05-25 13:55:01', NULL),
(718, 2230, 'k', '', '#A1FF9E', NULL, '2014-05-26 16:35:22', NULL),
(719, 1892, 'JOnathan', 'Absences', '#A1FF9E', NULL, '2014-05-30 14:05:27', NULL),
(720, 2235, 'Jonathan Juhasz', 'Calendrier de présence Jonathan', '#A1FF9E', 1, '2014-05-30 13:07:01', '2014-05-30 14:21:28'),
(721, 2236, 'Brad Kynoch', 'My default calendar', '#A1FF9E', 1, '2014-06-06 18:21:01', NULL),
(722, 2237, 'Vijay Marupudi', 'My default calendar', '#A1FF9E', 1, '2014-06-10 17:19:02', NULL),
(723, 2238, 'Liviu Hampau', 'My default calendar', '#A1FF9E', 1, '2014-06-17 13:34:02', NULL),
(724, 2239, 'Liviu Hampau', 'My default calendar', '#A1FF9E', 1, '2014-06-17 14:24:06', NULL),
(725, 2240, 'teo bobbie', 'My default calendar', '#A1FF9E', 1, '2014-06-19 10:52:40', NULL),
(726, 2241, 'Walter Low', 'My default calendar', '#A1FF9E', 1, '2014-06-19 10:56:08', NULL),
(730, 2245, 'hkgkjgkh jhglghlg', 'My default calendar', '#A1FF9E', 1, '2014-06-20 17:56:14', NULL),
(731, 2246, 'Daniela Daviduta', 'My default calendar', '#A1FF9E', 1, '2014-06-23 08:45:06', NULL),
(733, 2248, 'CDI 1', 'My default calendar', '#A1FF9E', 1, '2014-06-23 10:57:46', NULL),
(734, 2249, 'petrom 1', 'My default calendar', '#A1FF9E', 1, '2014-06-23 10:59:19', NULL),
(735, 2250, 'petrom 2', 'My default calendar', '#A1FF9E', 1, '2014-06-23 11:06:33', NULL),
(736, 2251, 'Vali Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(737, 2252, 'Sorin Gavril', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(738, 2253, 'Orla Ni Loinsigh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(739, 2254, 'Shixong Xu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(740, 2255, 'Lubomir Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(741, 2256, 'Andras Csermak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(742, 2257, 'Deniz Hasan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(743, 2258, 'Ruslan Filipov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(744, 2259, 'Yanko Popov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(745, 2260, 'Julian Shandorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(746, 2261, 'Emil Angelov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(747, 2262, 'Pavel Yosifov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(748, 2263, 'Yordan Tombakov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(749, 2264, 'Plamen Valev', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(750, 2265, 'Dinko Mironov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(751, 2266, 'Valeri Ivanov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:36', NULL),
(752, 2267, 'Hristo Hristov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(753, 2268, 'Lubomir Todorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(754, 2269, 'Nikolai Nikolov MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(755, 2270, 'Steve Lloyd', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(756, 2271, 'Ivan Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(757, 2272, 'Colm Keane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(758, 2273, 'Catalin Curcanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(759, 2274, 'Stephen Ryan Gmail', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(760, 2275, ' ester@mm-sol.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(761, 2276, 'Dobromir Denchev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(762, 2277, 'Robert Nicoras', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(763, 2278, 'Claudiu Steflea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(764, 2279, 'Calin Pantea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(765, 2280, 'Athena Elafrou', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(766, 2281, 'Gabor Moricz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(767, 2282, 'Po Yuan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(768, 2283, 'Adrian Hendroff', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(769, 2284, 'Tom Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(770, 2285, 'Flavio Cali', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(771, 2286, 'Jon Ewanich', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(772, 2287, 'Juan Sanchez', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(773, 2288, 'Chuck Handley', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(774, 2289, 'Hadi Sadeghi Taheri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(775, 2290, 'Stephen McDonagh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(776, 2291, 'Peter Hanos', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(777, 2292, 'Jozsef Ferencz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(778, 2293, 'Viktor  Szentgyorgyi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(779, 2294, 'Sergio Monroy', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(780, 2295, 'Zsolt Biro', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(781, 2296, 'Bogdan Camalessa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(782, 2297, 'Tomasz Szydzik', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(783, 2298, 'Emilia Murarescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(784, 2299, ' enis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(785, 2300, 'Adrian Bobocica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(786, 2301, 'Popescu Ana-Maria', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(787, 2302, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(788, 2303, 'Sebastian Stancu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(789, 2304, 'Turc Daniel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(790, 2305, 'Alexandru Pavăl', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(791, 2306, ' adu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(792, 2307, 'Emilia Pitiga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(793, 2308, 'Rolland Gal', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(794, 2309, 'Hannu Lampinen', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(795, 2310, 'VCS Users', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(796, 2311, 'Mark Cane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(797, 2312, 'Cristina Marghes', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(798, 2313, 'Gerry Griffin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(799, 2314, 'Gergely Kiss', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(800, 2315, 'Laszlo Vagasi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(801, 2316, 'Stephen Rogers', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(802, 2317, 'Voinovan Nicolae', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(803, 2318, ' rcona', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(804, 2319, ' odeplay2', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(805, 2320, ' ogdan.mavrodin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(806, 2321, 'Dragos Vlad', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(807, 2322, ' odeplay', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(808, 2323, 'Michael Doyle', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(809, 2324, 'Gilberto Muzzi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(810, 2325, 'Cristian Petruta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(811, 2326, ' ugzilla_sw', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:37', NULL),
(812, 2327, 'Bugzilla HW', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(813, 2328, 'Silvano Conte', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(814, 2329, 'Endre Papp', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(815, 2330, 'Goran Petrovity', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(816, 2331, 'Marek Havel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(817, 2332, 'Milan Tuma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(818, 2333, 'Andrea Fedeli', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(819, 2334, 'Marco Marini', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(820, 2335, 'Raffaele Pallavicino', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(821, 2336, 'Marco Stanzani', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(822, 2337, 'Andrea Rigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(823, 2338, 'Michele Borgatti', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(824, 2339, 'Gabor Molnar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(825, 2340, 'Imre Mados', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(826, 2341, 'Peter Vari', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(827, 2342, 'Balazs Bodis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(828, 2343, 'Zsolt Sogorka', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(829, 2344, 'David Kiraly', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(830, 2345, 'Balint Voros', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(831, 2346, 'Attila Hudak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(832, 2347, 'Csaba Nemeth', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(833, 2348, 'Martin O''Riordan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(834, 2349, 'Stephen Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(835, 2350, 'Attila Zigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(836, 2351, 'Martin Hoellerer', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(837, 2352, 'Sean Power', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(838, 2353, 'Alexandru Horin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(839, 2354, 'Alex Ghidan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(840, 2355, 'Natanael Cintean', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(841, 2356, 'Razvan Delibasa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(842, 2357, ' ttila.csok', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(843, 2358, 'Ted Irvine', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(844, 2359, 'Conor Mac Aoidh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(845, 2360, 'Andrei MINASTIREANU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(846, 2361, 'Marius Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(847, 2362, 'Luminita Daraban', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(848, 2363, 'Catalin Mihai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(849, 2364, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(850, 2365, 'Cristiana Crisan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(851, 2366, 'Flavia Halatiba', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(852, 2367, 'Oana Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(853, 2368, ' nca.alb@movidius.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(854, 2369, 'Cristina Dumitrascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(855, 2370, 'Diana Sipoteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(856, 2371, 'Andrei Baiasu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(857, 2372, 'Daniela Busu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(858, 2373, 'Marius Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(859, 2374, 'Claudiu Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(860, 2375, 'Tiberius Vinczi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(861, 2376, 'Cristina Prajescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(862, 2377, 'Andreea Dumitru', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(863, 2378, 'Andrei Purdea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(864, 2379, 'Ovidiu Popa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(865, 2380, 'Vlad Bunea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(866, 2381, 'Alexandru Amaricai-Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(867, 2382, 'Benjamin Lee', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(868, 2383, 'Daravith Kho', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(869, 2384, 'Dan Dunga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(870, 2385, 'Calin Precup', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(871, 2386, 'Iulia Stirb', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(872, 2387, ' uri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(873, 2388, 'Horea Pop', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(874, 2389, 'Richard Richmond', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(875, 2390, 'Mădălina Ghidoviț', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:38', NULL),
(876, 2391, 'Vesa Ovidiu-Andrei', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(877, 2392, 'Ancuta-Maria Ivascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(878, 2393, 'Alexandru Dan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(879, 2394, 'Alexandru Dura', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(880, 2395, 'Oana Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(881, 2396, 'Adelina Vig', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(882, 2397, 'Raluca Veleanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(883, 2398, 'Thomas Bohm', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(884, 2399, 'Ivan Velciov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(885, 2400, ' jit', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(886, 2401, 'Marius Truica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(887, 2402, 'Valentin STANGACIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(888, 2403, ' oards', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(889, 2404, 'Florin Cania', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(890, 2405, 'Sorin Petrusan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(891, 2406, 'Cliff Wong', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(892, 2407, 'Camelia Valuch', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(893, 2408, 'Cristina-Sorina Stangaciu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(894, 2409, 'Barry Jones', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(895, 2410, 'Stella Lau', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(896, 2411, 'Bob Tait', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(897, 2412, 'Laszlo Joo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(898, 2413, 'David Nicholls', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(899, 2414, 'David Donohoe', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(900, 2415, 'Attila Banyai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(901, 2416, 'Cristian Olar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(902, 2417, 'Bogdan MANCIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(903, 2418, 'Emanuele Petrucci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(904, 2419, 'Alin Dobre', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(905, 2420, 'Ionel Adam', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(906, 2421, 'Nicolae Olteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(907, 2422, 'Darren Bowler', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(908, 2423, 'Dorin Dragan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(909, 2424, 'Sergiu Grip', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(910, 2425, 'Alex Balogh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(911, 2426, 'Lucian Vancea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(912, 2427, 'John Scott', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(913, 2428, 'Virgil Petcu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(914, 2429, 'Daniel Mariniuc', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(915, 2430, 'Vasile Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(916, 2431, 'Denisa Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(917, 2432, 'cristian vesa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(918, 2433, ' ucian.Mirci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(919, 2434, 'Sebastian Perta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(920, 2435, 'Cristian Cuna', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(921, 2436, 'Albert Fazakas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(922, 2437, ' ircea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(923, 2438, 'David Moloney', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(924, 2439, 'Sean Mitchell', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(925, 2440, 'Vasile Toma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(926, 2441, 'Val Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(927, 2442, 'Andrei Lupas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(928, 2443, 'Fergal Connor', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(929, 2444, 'Cormac Brick', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(930, 2445, 'Brendan Barry', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:08:39', NULL),
(931, 2446, 'Vali Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:23', NULL),
(932, 2447, 'Sorin Gavril', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:23', NULL),
(933, 2448, 'Orla Ni Loinsigh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:23', NULL),
(934, 2449, 'Shixong Xu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(935, 2450, 'Lubomir Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(936, 2451, 'Andras Csermak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(937, 2452, 'Deniz Hasan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(938, 2453, 'Ruslan Filipov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(939, 2454, 'Yanko Popov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(940, 2455, 'Julian Shandorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(941, 2456, 'Emil Angelov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(942, 2457, 'Pavel Yosifov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(943, 2458, 'Yordan Tombakov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(944, 2459, 'Plamen Valev', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(945, 2460, 'Dinko Mironov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(946, 2461, 'Valeri Ivanov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(947, 2462, 'Hristo Hristov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(948, 2463, 'Lubomir Todorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(949, 2464, 'Nikolai Nikolov MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(950, 2465, 'Steve Lloyd', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(951, 2466, 'Ivan Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(952, 2467, 'Colm Keane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(953, 2468, 'Catalin Curcanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(954, 2469, 'Stephen Ryan Gmail', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(955, 2470, ' ester@mm-sol.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(956, 2471, 'Dobromir Denchev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(957, 2472, 'Robert Nicoras', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(958, 2473, 'Claudiu Steflea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(959, 2474, 'Calin Pantea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(960, 2475, 'Athena Elafrou', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(961, 2476, 'Gabor Moricz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(962, 2477, 'Po Yuan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(963, 2478, 'Adrian Hendroff', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(964, 2479, 'Tom Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(965, 2480, 'Flavio Cali', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(966, 2481, 'Jon Ewanich', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(967, 2482, 'Juan Sanchez', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(968, 2483, 'Chuck Handley', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(969, 2484, 'Hadi Sadeghi Taheri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(970, 2485, 'Stephen McDonagh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(971, 2486, 'Peter Hanos', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(972, 2487, 'Jozsef Ferencz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(973, 2488, 'Viktor  Szentgyorgyi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(974, 2489, 'Sergio Monroy', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(975, 2490, 'Zsolt Biro', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(976, 2491, 'Bogdan Camalessa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(977, 2492, 'Tomasz Szydzik', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(978, 2493, 'Emilia Murarescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(979, 2494, ' enis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(980, 2495, 'Adrian Bobocica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(981, 2496, 'Popescu Ana-Maria', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(982, 2497, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(983, 2498, 'Sebastian Stancu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(984, 2499, 'Turc Daniel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(985, 2500, 'Alexandru Pavăl', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(986, 2501, ' adu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(987, 2502, 'Emilia Pitiga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(988, 2503, 'Rolland Gal', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(989, 2504, 'Hannu Lampinen', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(990, 2505, 'VCS Users', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(991, 2506, 'Mark Cane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(992, 2507, 'Cristina Marghes', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(993, 2508, 'Gerry Griffin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(994, 2509, 'Gergely Kiss', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(995, 2510, 'Laszlo Vagasi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(996, 2511, 'Stephen Rogers', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(997, 2512, 'Voinovan Nicolae', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(998, 2513, ' rcona', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(999, 2514, ' odeplay2', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(1000, 2515, ' ogdan.mavrodin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(1001, 2516, 'Dragos Vlad', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:24', NULL),
(1002, 2517, ' odeplay', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1003, 2518, 'Michael Doyle', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1004, 2519, 'Gilberto Muzzi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1005, 2520, 'Cristian Petruta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1006, 2521, ' ugzilla_sw', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1007, 2522, 'Bugzilla HW', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1008, 2523, 'Silvano Conte', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1009, 2524, 'Endre Papp', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1010, 2525, 'Goran Petrovity', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1011, 2526, 'Marek Havel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1012, 2527, 'Milan Tuma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1013, 2528, 'Andrea Fedeli', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1014, 2529, 'Marco Marini', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1015, 2530, 'Raffaele Pallavicino', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1016, 2531, 'Marco Stanzani', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1017, 2532, 'Andrea Rigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1018, 2533, 'Michele Borgatti', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1019, 2534, 'Gabor Molnar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1020, 2535, 'Imre Mados', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1021, 2536, 'Peter Vari', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1022, 2537, 'Balazs Bodis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1023, 2538, 'Zsolt Sogorka', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1024, 2539, 'David Kiraly', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1025, 2540, 'Balint Voros', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1026, 2541, 'Attila Hudak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1027, 2542, 'Csaba Nemeth', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1028, 2543, 'Martin O''Riordan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1029, 2544, 'Stephen Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1030, 2545, 'Attila Zigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1031, 2546, 'Martin Hoellerer', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1032, 2547, 'Sean Power', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1033, 2548, 'Alexandru Horin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1034, 2549, 'Alex Ghidan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1035, 2550, 'Natanael Cintean', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1036, 2551, 'Razvan Delibasa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1037, 2552, ' ttila.csok', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1038, 2553, 'Ted Irvine', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1039, 2554, 'Conor Mac Aoidh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1040, 2555, 'Andrei MINASTIREANU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1041, 2556, 'Marius Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1042, 2557, 'Luminita Daraban', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1043, 2558, 'Catalin Mihai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1044, 2559, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1045, 2560, 'Cristiana Crisan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1046, 2561, 'Flavia Halatiba', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1047, 2562, 'Oana Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1048, 2563, ' nca.alb@movidius.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1049, 2564, 'Cristina Dumitrascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1050, 2565, 'Diana Sipoteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1051, 2566, 'Andrei Baiasu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1052, 2567, 'Daniela Busu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1053, 2568, 'Marius Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1054, 2569, 'Claudiu Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1055, 2570, 'Tiberius Vinczi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1056, 2571, 'Cristina Prajescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1057, 2572, 'Andreea Dumitru', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1058, 2573, 'Andrei Purdea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1059, 2574, 'Ovidiu Popa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1060, 2575, 'Vlad Bunea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1061, 2576, 'Alexandru Amaricai-Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1062, 2577, 'Benjamin Lee', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1063, 2578, 'Daravith Kho', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1064, 2579, 'Dan Dunga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1065, 2580, 'Calin Precup', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1066, 2581, 'Iulia Stirb', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:25', NULL),
(1067, 2582, ' uri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1068, 2583, 'Horea Pop', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1069, 2584, 'Richard Richmond', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1070, 2585, 'Mădălina Ghidoviț', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1071, 2586, 'Vesa Ovidiu-Andrei', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1072, 2587, 'Ancuta-Maria Ivascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1073, 2588, 'Alexandru Dan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1074, 2589, 'Alexandru Dura', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1075, 2590, 'Oana Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1076, 2591, 'Adelina Vig', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1077, 2592, 'Raluca Veleanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1078, 2593, 'Thomas Bohm', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1079, 2594, 'Ivan Velciov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1080, 2595, ' jit', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1081, 2596, 'Marius Truica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1082, 2597, 'Valentin STANGACIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1083, 2598, ' oards', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1084, 2599, 'Florin Cania', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1085, 2600, 'Sorin Petrusan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1086, 2601, 'Cliff Wong', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1087, 2602, 'Camelia Valuch', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1088, 2603, 'Cristina-Sorina Stangaciu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1089, 2604, 'Barry Jones', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1090, 2605, 'Stella Lau', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1091, 2606, 'Bob Tait', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1092, 2607, 'Laszlo Joo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1093, 2608, 'David Nicholls', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1094, 2609, 'David Donohoe', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1095, 2610, 'Attila Banyai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1096, 2611, 'Cristian Olar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1097, 2612, 'Bogdan MANCIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1098, 2613, 'Emanuele Petrucci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1099, 2614, 'Alin Dobre', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1100, 2615, 'Ionel Adam', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1101, 2616, 'Nicolae Olteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1102, 2617, 'Darren Bowler', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1103, 2618, 'Dorin Dragan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1104, 2619, 'Sergiu Grip', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1105, 2620, 'Alex Balogh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1106, 2621, 'Lucian Vancea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1107, 2622, 'John Scott', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1108, 2623, 'Virgil Petcu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1109, 2624, 'Daniel Mariniuc', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1110, 2625, 'Vasile Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1111, 2626, 'Denisa Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1112, 2627, 'cristian vesa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1113, 2628, ' ucian.Mirci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1114, 2629, 'Sebastian Perta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL);
INSERT INTO `cal_calendar` (`id`, `user_id`, `name`, `description`, `color`, `default_flag`, `date_created`, `date_updated`) VALUES
(1115, 2630, 'Cristian Cuna', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1116, 2631, 'Albert Fazakas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1117, 2632, ' ircea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1118, 2633, 'David Moloney', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1119, 2634, 'Sean Mitchell', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1120, 2635, 'Vasile Toma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1121, 2636, 'Val Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1122, 2637, 'Andrei Lupas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1123, 2638, 'Fergal Connor', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1124, 2639, 'Cormac Brick', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1125, 2640, 'Brendan Barry', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:11:26', NULL),
(1126, 2641, 'Vali Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1127, 2642, 'Sorin Gavril', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1128, 2643, 'Orla Ni Loinsigh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1129, 2644, 'Shixong Xu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1130, 2645, 'Lubomir Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1131, 2646, 'Andras Csermak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1132, 2647, 'Deniz Hasan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1133, 2648, 'Ruslan Filipov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1134, 2649, 'Yanko Popov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1135, 2650, 'Julian Shandorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1136, 2651, 'Emil Angelov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1137, 2652, 'Pavel Yosifov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1138, 2653, 'Yordan Tombakov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1139, 2654, 'Plamen Valev', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1140, 2655, 'Dinko Mironov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1141, 2656, 'Valeri Ivanov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1142, 2657, 'Hristo Hristov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1143, 2658, 'Lubomir Todorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1144, 2659, 'Nikolai Nikolov MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1145, 2660, 'Steve Lloyd', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1146, 2661, 'Ivan Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1147, 2662, 'Colm Keane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1148, 2663, 'Catalin Curcanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1149, 2664, 'Stephen Ryan Gmail', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1150, 2665, ' ester@mm-sol.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1151, 2666, 'Dobromir Denchev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1152, 2667, 'Robert Nicoras', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1153, 2668, 'Claudiu Steflea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1154, 2669, 'Calin Pantea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1155, 2670, 'Athena Elafrou', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1156, 2671, 'Gabor Moricz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1157, 2672, 'Po Yuan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1158, 2673, 'Adrian Hendroff', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1159, 2674, 'Tom Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1160, 2675, 'Flavio Cali', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1161, 2676, 'Jon Ewanich', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1162, 2677, 'Juan Sanchez', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1163, 2678, 'Chuck Handley', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1164, 2679, 'Hadi Sadeghi Taheri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1165, 2680, 'Stephen McDonagh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1166, 2681, 'Peter Hanos', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1167, 2682, 'Jozsef Ferencz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1168, 2683, 'Viktor  Szentgyorgyi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1169, 2684, 'Sergio Monroy', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1170, 2685, 'Zsolt Biro', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1171, 2686, 'Bogdan Camalessa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1172, 2687, 'Tomasz Szydzik', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1173, 2688, 'Emilia Murarescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1174, 2689, ' enis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1175, 2690, 'Adrian Bobocica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1176, 2691, 'Popescu Ana-Maria', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1177, 2692, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:30', NULL),
(1178, 2693, 'Sebastian Stancu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1179, 2694, 'Turc Daniel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1180, 2695, 'Alexandru Pavăl', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1181, 2696, ' adu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1182, 2697, 'Emilia Pitiga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1183, 2698, 'Rolland Gal', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1184, 2699, 'Hannu Lampinen', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1185, 2700, 'VCS Users', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1186, 2701, 'Mark Cane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1187, 2702, 'Cristina Marghes', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1188, 2703, 'Gerry Griffin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1189, 2704, 'Gergely Kiss', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1190, 2705, 'Laszlo Vagasi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1191, 2706, 'Stephen Rogers', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1192, 2707, 'Voinovan Nicolae', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1193, 2708, ' rcona', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1194, 2709, ' odeplay2', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1195, 2710, ' ogdan.mavrodin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1196, 2711, 'Dragos Vlad', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1197, 2712, ' odeplay', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1198, 2713, 'Michael Doyle', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1199, 2714, 'Gilberto Muzzi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1200, 2715, 'Cristian Petruta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1201, 2716, ' ugzilla_sw', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1202, 2717, 'Bugzilla HW', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1203, 2718, 'Silvano Conte', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1204, 2719, 'Endre Papp', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1205, 2720, 'Goran Petrovity', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1206, 2721, 'Marek Havel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1207, 2722, 'Milan Tuma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1208, 2723, 'Andrea Fedeli', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1209, 2724, 'Marco Marini', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1210, 2725, 'Raffaele Pallavicino', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1211, 2726, 'Marco Stanzani', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1212, 2727, 'Andrea Rigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1213, 2728, 'Michele Borgatti', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1214, 2729, 'Gabor Molnar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1215, 2730, 'Imre Mados', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1216, 2731, 'Peter Vari', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1217, 2732, 'Balazs Bodis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1218, 2733, 'Zsolt Sogorka', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1219, 2734, 'David Kiraly', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1220, 2735, 'Balint Voros', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1221, 2736, 'Attila Hudak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1222, 2737, 'Csaba Nemeth', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1223, 2738, 'Martin O''Riordan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1224, 2739, 'Stephen Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1225, 2740, 'Attila Zigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1226, 2741, 'Martin Hoellerer', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1227, 2742, 'Sean Power', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1228, 2743, 'Alexandru Horin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1229, 2744, 'Alex Ghidan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1230, 2745, 'Natanael Cintean', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1231, 2746, 'Razvan Delibasa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1232, 2747, ' ttila.csok', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1233, 2748, 'Ted Irvine', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1234, 2749, 'Conor Mac Aoidh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1235, 2750, 'Andrei MINASTIREANU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1236, 2751, 'Marius Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1237, 2752, 'Luminita Daraban', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1238, 2753, 'Catalin Mihai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1239, 2754, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1240, 2755, 'Cristiana Crisan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1241, 2756, 'Flavia Halatiba', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1242, 2757, 'Oana Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1243, 2758, ' nca.alb@movidius.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:31', NULL),
(1244, 2759, 'Cristina Dumitrascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1245, 2760, 'Diana Sipoteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1246, 2761, 'Andrei Baiasu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1247, 2762, 'Daniela Busu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1248, 2763, 'Marius Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1249, 2764, 'Claudiu Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1250, 2765, 'Tiberius Vinczi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1251, 2766, 'Cristina Prajescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1252, 2767, 'Andreea Dumitru', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1253, 2768, 'Andrei Purdea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1254, 2769, 'Ovidiu Popa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1255, 2770, 'Vlad Bunea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1256, 2771, 'Alexandru Amaricai-Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1257, 2772, 'Benjamin Lee', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1258, 2773, 'Daravith Kho', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1259, 2774, 'Dan Dunga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1260, 2775, 'Calin Precup', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1261, 2776, 'Iulia Stirb', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1262, 2777, ' uri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1263, 2778, 'Horea Pop', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1264, 2779, 'Richard Richmond', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1265, 2780, 'Mădălina Ghidoviț', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1266, 2781, 'Vesa Ovidiu-Andrei', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1267, 2782, 'Ancuta-Maria Ivascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1268, 2783, 'Alexandru Dan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1269, 2784, 'Alexandru Dura', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1270, 2785, 'Oana Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1271, 2786, 'Adelina Vig', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1272, 2787, 'Raluca Veleanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1273, 2788, 'Thomas Bohm', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1274, 2789, 'Ivan Velciov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1275, 2790, ' jit', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1276, 2791, 'Marius Truica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1277, 2792, 'Valentin STANGACIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1278, 2793, ' oards', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1279, 2794, 'Florin Cania', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1280, 2795, 'Sorin Petrusan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1281, 2796, 'Cliff Wong', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1282, 2797, 'Camelia Valuch', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1283, 2798, 'Cristina-Sorina Stangaciu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1284, 2799, 'Barry Jones', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1285, 2800, 'Stella Lau', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1286, 2801, 'Bob Tait', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1287, 2802, 'Laszlo Joo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1288, 2803, 'David Nicholls', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1289, 2804, 'David Donohoe', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1290, 2805, 'Attila Banyai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1291, 2806, 'Cristian Olar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1292, 2807, 'Bogdan MANCIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1293, 2808, 'Emanuele Petrucci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1294, 2809, 'Alin Dobre', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1295, 2810, 'Ionel Adam', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1296, 2811, 'Nicolae Olteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1297, 2812, 'Darren Bowler', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1298, 2813, 'Dorin Dragan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1299, 2814, 'Sergiu Grip', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1300, 2815, 'Alex Balogh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1301, 2816, 'Lucian Vancea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1302, 2817, 'John Scott', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1303, 2818, 'Virgil Petcu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1304, 2819, 'Daniel Mariniuc', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1305, 2820, 'Vasile Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1306, 2821, 'Denisa Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1307, 2822, 'cristian vesa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1308, 2823, ' ucian.Mirci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1309, 2824, 'Sebastian Perta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1310, 2825, 'Cristian Cuna', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:32', NULL),
(1311, 2826, 'Albert Fazakas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1312, 2827, ' ircea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1313, 2828, 'David Moloney', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1314, 2829, 'Sean Mitchell', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1315, 2830, 'Vasile Toma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1316, 2831, 'Val Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1317, 2832, 'Andrei Lupas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1318, 2833, 'Fergal Connor', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1319, 2834, 'Cormac Brick', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1320, 2835, 'Brendan Barry', 'My default calendar', '#A1FF9E', 1, '2014-06-26 12:17:33', NULL),
(1321, 2836, 'Vali Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1322, 2837, 'Sorin Gavril', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1323, 2838, 'Orla Ni Loinsigh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1324, 2839, 'Shixong Xu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1325, 2840, 'Lubomir Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1326, 2841, 'Andras Csermak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1327, 2842, 'Deniz Hasan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1328, 2843, 'Ruslan Filipov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1329, 2844, 'Yanko Popov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1330, 2845, 'Julian Shandorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1331, 2846, 'Emil Angelov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1332, 2847, 'Pavel Yosifov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1333, 2848, 'Yordan Tombakov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1334, 2849, 'Plamen Valev', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1335, 2850, 'Dinko Mironov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1336, 2851, 'Valeri Ivanov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1337, 2852, 'Hristo Hristov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1338, 2853, 'Lubomir Todorov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:20', NULL),
(1339, 2854, 'Nikolai Nikolov MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1340, 2855, 'Steve Lloyd', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1341, 2856, 'Ivan Vasilev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1342, 2857, 'Colm Keane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1343, 2858, 'Catalin Curcanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1344, 2859, 'Stephen Ryan Gmail', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1345, 2860, ' ester@mm-sol.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1346, 2861, 'Dobromir Denchev MMS', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1347, 2862, 'Robert Nicoras', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1348, 2863, 'Claudiu Steflea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1349, 2864, 'Calin Pantea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1350, 2865, 'Athena Elafrou', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1351, 2866, 'Gabor Moricz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1352, 2867, 'Po Yuan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1353, 2868, 'Adrian Hendroff', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1354, 2869, 'Tom Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1355, 2870, 'Flavio Cali', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1356, 2871, 'Jon Ewanich', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1357, 2872, 'Juan Sanchez', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1358, 2873, 'Chuck Handley', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1359, 2874, 'Hadi Sadeghi Taheri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1360, 2875, 'Stephen McDonagh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1361, 2876, 'Peter Hanos', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1362, 2877, 'Jozsef Ferencz', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1363, 2878, 'Viktor  Szentgyorgyi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1364, 2879, 'Sergio Monroy', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1365, 2880, 'Zsolt Biro', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1366, 2881, 'Bogdan Camalessa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1367, 2882, 'Tomasz Szydzik', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1368, 2883, 'Emilia Murarescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1369, 2884, ' enis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1370, 2885, 'Adrian Bobocica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1371, 2886, 'Popescu Ana-Maria', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1372, 2887, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1373, 2888, 'Sebastian Stancu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1374, 2889, 'Turc Daniel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1375, 2890, 'Alexandru Pavăl', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1376, 2891, ' adu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1377, 2892, 'Emilia Pitiga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1378, 2893, 'Rolland Gal', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1379, 2894, 'Hannu Lampinen', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1380, 2895, 'VCS Users', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1381, 2896, 'Mark Cane', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1382, 2897, 'Cristina Marghes', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1383, 2898, 'Gerry Griffin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1384, 2899, 'Gergely Kiss', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1385, 2900, 'Laszlo Vagasi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1386, 2901, 'Stephen Rogers', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1387, 2902, 'Voinovan Nicolae', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1388, 2903, ' rcona', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1389, 2904, ' odeplay2', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1390, 2905, ' ogdan.mavrodin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1391, 2906, 'Dragos Vlad', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1392, 2907, ' odeplay', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1393, 2908, 'Michael Doyle', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1394, 2909, 'Gilberto Muzzi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1395, 2910, 'Cristian Petruta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1396, 2911, ' ugzilla_sw', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1397, 2912, 'Bugzilla HW', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1398, 2913, 'Silvano Conte', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1399, 2914, 'Endre Papp', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1400, 2915, 'Goran Petrovity', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1401, 2916, 'Marek Havel', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1402, 2917, 'Milan Tuma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1403, 2918, 'Andrea Fedeli', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1404, 2919, 'Marco Marini', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1405, 2920, 'Raffaele Pallavicino', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:21', NULL),
(1406, 2921, 'Marco Stanzani', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1407, 2922, 'Andrea Rigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1408, 2923, 'Michele Borgatti', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1409, 2924, 'Gabor Molnar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1410, 2925, 'Imre Mados', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1411, 2926, 'Peter Vari', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1412, 2927, 'Balazs Bodis', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1413, 2928, 'Zsolt Sogorka', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1414, 2929, 'David Kiraly', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1415, 2930, 'Balint Voros', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1416, 2931, 'Attila Hudak', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1417, 2932, 'Csaba Nemeth', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1418, 2933, 'Martin O''Riordan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1419, 2934, 'Stephen Ryan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1420, 2935, 'Attila Zigo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1421, 2936, 'Martin Hoellerer', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1422, 2937, 'Sean Power', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1423, 2938, 'Alexandru Horin', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1424, 2939, 'Alex Ghidan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1425, 2940, 'Natanael Cintean', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1426, 2941, 'Razvan Delibasa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1427, 2942, ' ttila.csok', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1428, 2943, 'Ted Irvine', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1429, 2944, 'Conor Mac Aoidh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1430, 2945, 'Andrei MINASTIREANU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1431, 2946, 'Marius Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1432, 2947, 'Luminita Daraban', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1433, 2948, 'Catalin Mihai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1434, 2949, ' ', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1435, 2950, 'Cristiana Crisan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1436, 2951, 'Flavia Halatiba', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1437, 2952, 'Oana Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1438, 2953, ' nca.alb@movidius.com', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1439, 2954, 'Cristina Dumitrascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1440, 2955, 'Diana Sipoteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1441, 2956, 'Andrei Baiasu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1442, 2957, 'Daniela Busu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1443, 2958, 'Marius Ciortan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1444, 2959, 'Claudiu Cosma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1445, 2960, 'Tiberius Vinczi', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1446, 2961, 'Cristina Prajescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1447, 2962, 'Andreea Dumitru', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1448, 2963, 'Andrei Purdea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1449, 2964, 'Ovidiu Popa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1450, 2965, 'Vlad Bunea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1451, 2966, 'Alexandru Amaricai-Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1452, 2967, 'Benjamin Lee', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1453, 2968, 'Daravith Kho', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1454, 2969, 'Dan Dunga', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1455, 2970, 'Calin Precup', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1456, 2971, 'Iulia Stirb', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1457, 2972, ' uri', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1458, 2973, 'Horea Pop', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1459, 2974, 'Richard Richmond', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1460, 2975, 'Mădălina Ghidoviț', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1461, 2976, 'Vesa Ovidiu-Andrei', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1462, 2977, 'Ancuta-Maria Ivascu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1463, 2978, 'Alexandru Dan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1464, 2979, 'Alexandru Dura', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1465, 2980, 'Oana Boncalo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1466, 2981, 'Adelina Vig', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1467, 2982, 'Raluca Veleanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1468, 2983, 'Thomas Bohm', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1469, 2984, 'Ivan Velciov', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1470, 2985, ' jit', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1471, 2986, 'Marius Truica', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1472, 2987, 'Valentin STANGACIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:22', NULL),
(1473, 2988, ' oards', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1474, 2989, 'Florin Cania', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1475, 2990, 'Sorin Petrusan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1476, 2991, 'Cliff Wong', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1477, 2992, 'Camelia Valuch', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1478, 2993, 'Cristina-Sorina Stangaciu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1479, 2994, 'Barry Jones', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1480, 2995, 'Stella Lau', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1481, 2996, 'Bob Tait', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1482, 2997, 'Laszlo Joo', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1483, 2998, 'David Nicholls', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1484, 2999, 'David Donohoe', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1485, 3000, 'Attila Banyai', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1486, 3001, 'Cristian Olar', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1487, 3002, 'Bogdan MANCIU', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1488, 3003, 'Emanuele Petrucci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1489, 3004, 'Alin Dobre', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1490, 3005, 'Ionel Adam', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1491, 3006, 'Nicolae Olteanu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1492, 3007, 'Darren Bowler', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1493, 3008, 'Dorin Dragan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1494, 3009, 'Sergiu Grip', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1495, 3010, 'Alex Balogh', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1496, 3011, 'Lucian Vancea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1497, 3012, 'John Scott', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1498, 3013, 'Virgil Petcu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1499, 3014, 'Daniel Mariniuc', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1500, 3015, 'Vasile Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1501, 3016, 'Denisa Popescu', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1502, 3017, 'cristian vesa', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1503, 3018, ' ucian.Mirci', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1504, 3019, 'Sebastian Perta', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1505, 3020, 'Cristian Cuna', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1506, 3021, 'Albert Fazakas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1507, 3022, ' ircea', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1508, 3023, 'David Moloney', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1509, 3024, 'Sean Mitchell', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1510, 3025, 'Vasile Toma', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1511, 3026, 'Val Muresan', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1512, 3027, 'Andrei Lupas', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1513, 3028, 'Fergal Connor', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1514, 3029, 'Cormac Brick', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL),
(1515, 3030, 'Brendan Barry', 'My default calendar', '#A1FF9E', 1, '2014-06-26 14:00:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cal_calendar_default_reminder`
--

CREATE TABLE IF NOT EXISTS `cal_calendar_default_reminder` (
`id` bigint(20) unsigned NOT NULL,
  `cal_calendar_id` bigint(20) unsigned NOT NULL,
  `cal_event_reminder_type_id` bigint(20) unsigned NOT NULL,
  `cal_event_reminder_period_id` bigint(20) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1514 ;

--
-- Dumping data for table `cal_calendar_default_reminder`
--

INSERT INTO `cal_calendar_default_reminder` (`id`, `cal_calendar_id`, `cal_event_reminder_type_id`, `cal_event_reminder_period_id`, `value`) VALUES
(1, 351, 1, 1, 30),
(2, 350, 1, 1, 30),
(3, 349, 1, 1, 30),
(4, 348, 1, 1, 30),
(5, 347, 1, 1, 30),
(6, 346, 1, 1, 30),
(7, 345, 1, 1, 30),
(8, 344, 1, 1, 30),
(9, 343, 1, 1, 30),
(10, 342, 1, 1, 30),
(11, 341, 1, 1, 30),
(12, 340, 1, 1, 30),
(13, 339, 1, 1, 30),
(14, 338, 1, 1, 30),
(15, 337, 1, 1, 30),
(16, 336, 1, 1, 30),
(17, 335, 1, 1, 30),
(18, 334, 1, 1, 30),
(19, 333, 1, 1, 30),
(20, 332, 1, 1, 30),
(21, 331, 1, 1, 30),
(22, 330, 1, 1, 30),
(23, 329, 1, 1, 30),
(24, 328, 1, 1, 30),
(25, 327, 1, 1, 30),
(26, 326, 1, 1, 30),
(27, 325, 1, 1, 30),
(29, 323, 1, 1, 30),
(30, 322, 1, 1, 30),
(31, 321, 1, 1, 30),
(32, 320, 1, 1, 30),
(33, 319, 1, 1, 30),
(34, 318, 1, 1, 30),
(35, 317, 1, 1, 30),
(36, 316, 1, 1, 30),
(37, 315, 1, 1, 30),
(38, 314, 1, 1, 30),
(39, 313, 1, 1, 30),
(40, 312, 1, 1, 30),
(41, 311, 1, 1, 30),
(42, 310, 1, 1, 30),
(43, 309, 1, 1, 30),
(44, 308, 1, 1, 30),
(45, 307, 1, 1, 30),
(46, 306, 1, 1, 30),
(47, 305, 1, 1, 30),
(48, 304, 1, 1, 30),
(49, 303, 1, 1, 30),
(50, 302, 1, 1, 30),
(51, 301, 1, 1, 30),
(52, 300, 1, 1, 30),
(53, 299, 1, 1, 30),
(54, 298, 1, 1, 30),
(55, 297, 1, 1, 30),
(56, 296, 1, 1, 30),
(57, 295, 1, 1, 30),
(59, 293, 1, 1, 30),
(60, 292, 1, 1, 30),
(61, 291, 1, 1, 30),
(62, 290, 1, 1, 30),
(63, 289, 1, 1, 30),
(64, 288, 1, 1, 30),
(65, 287, 1, 1, 30),
(66, 286, 1, 1, 30),
(67, 285, 1, 1, 30),
(68, 284, 1, 1, 30),
(69, 283, 1, 1, 30),
(70, 282, 1, 1, 30),
(72, 280, 1, 1, 30),
(73, 279, 1, 1, 30),
(74, 278, 1, 1, 30),
(75, 277, 1, 1, 30),
(76, 276, 1, 1, 30),
(77, 275, 1, 1, 30),
(78, 274, 1, 1, 30),
(79, 273, 1, 1, 30),
(80, 272, 1, 1, 30),
(81, 271, 1, 1, 30),
(82, 270, 1, 1, 30),
(83, 269, 1, 1, 30),
(84, 268, 1, 1, 30),
(85, 267, 1, 1, 30),
(87, 265, 1, 1, 30),
(88, 264, 1, 1, 30),
(89, 263, 1, 1, 30),
(90, 262, 1, 1, 30),
(91, 261, 1, 1, 30),
(92, 260, 1, 1, 30),
(93, 259, 1, 1, 30),
(94, 258, 1, 1, 30),
(95, 257, 1, 1, 30),
(96, 256, 1, 1, 30),
(97, 255, 1, 1, 30),
(98, 254, 1, 1, 30),
(99, 253, 1, 1, 30),
(100, 252, 1, 1, 30),
(101, 251, 1, 1, 30),
(102, 250, 1, 1, 30),
(103, 249, 1, 1, 30),
(104, 248, 1, 1, 30),
(105, 247, 1, 1, 30),
(106, 246, 1, 1, 30),
(107, 245, 1, 1, 30),
(108, 244, 1, 1, 30),
(109, 243, 1, 1, 30),
(110, 242, 1, 1, 30),
(111, 241, 1, 1, 30),
(112, 240, 1, 1, 30),
(113, 239, 1, 1, 30),
(114, 238, 1, 1, 30),
(115, 237, 1, 1, 30),
(116, 236, 1, 1, 30),
(117, 235, 1, 1, 30),
(118, 234, 1, 1, 30),
(119, 233, 1, 1, 30),
(120, 232, 1, 1, 30),
(121, 231, 1, 1, 30),
(122, 230, 1, 1, 30),
(123, 229, 1, 1, 30),
(124, 228, 1, 1, 30),
(125, 227, 1, 1, 30),
(126, 226, 1, 1, 30),
(127, 225, 1, 1, 30),
(128, 224, 1, 1, 30),
(129, 223, 1, 1, 30),
(130, 222, 1, 1, 30),
(131, 221, 1, 1, 30),
(132, 220, 1, 1, 30),
(133, 219, 1, 1, 30),
(134, 218, 1, 1, 30),
(135, 217, 1, 1, 30),
(136, 216, 1, 1, 30),
(137, 215, 1, 1, 30),
(138, 214, 1, 1, 30),
(139, 213, 1, 1, 30),
(140, 212, 1, 1, 30),
(141, 211, 1, 1, 30),
(142, 210, 1, 1, 30),
(143, 209, 1, 1, 30),
(144, 208, 1, 1, 30),
(145, 207, 1, 1, 30),
(146, 206, 1, 1, 30),
(147, 205, 1, 1, 30),
(148, 204, 1, 1, 30),
(149, 203, 1, 1, 30),
(150, 202, 1, 1, 30),
(151, 201, 1, 1, 30),
(152, 200, 1, 1, 30),
(153, 199, 1, 1, 30),
(154, 198, 1, 1, 30),
(155, 197, 1, 1, 30),
(156, 196, 1, 1, 30),
(157, 195, 1, 1, 30),
(158, 194, 1, 1, 30),
(159, 193, 1, 1, 30),
(160, 192, 1, 1, 30),
(161, 191, 1, 1, 30),
(162, 190, 1, 1, 30),
(163, 189, 1, 1, 30),
(164, 188, 1, 1, 30),
(165, 187, 1, 1, 30),
(166, 186, 1, 1, 30),
(167, 185, 1, 1, 30),
(168, 184, 1, 1, 30),
(169, 183, 1, 1, 30),
(170, 182, 1, 1, 30),
(171, 181, 1, 1, 30),
(172, 180, 1, 1, 30),
(173, 179, 1, 1, 30),
(174, 178, 1, 1, 30),
(175, 177, 1, 1, 30),
(176, 176, 1, 1, 30),
(177, 175, 1, 1, 30),
(178, 174, 1, 1, 30),
(179, 173, 1, 1, 30),
(180, 172, 1, 1, 30),
(181, 171, 1, 1, 30),
(182, 170, 1, 1, 30),
(183, 169, 1, 1, 30),
(184, 168, 1, 1, 30),
(185, 167, 1, 1, 30),
(186, 166, 1, 1, 30),
(187, 165, 1, 1, 30),
(188, 164, 1, 1, 30),
(189, 163, 1, 1, 30),
(190, 162, 1, 1, 30),
(191, 161, 1, 1, 30),
(192, 160, 1, 1, 30),
(193, 159, 1, 1, 30),
(194, 158, 1, 1, 30),
(195, 157, 1, 1, 30),
(196, 156, 1, 1, 30),
(197, 155, 1, 1, 30),
(198, 154, 1, 1, 30),
(199, 153, 1, 1, 30),
(200, 152, 1, 1, 30),
(201, 151, 1, 1, 30),
(202, 150, 1, 1, 30),
(203, 149, 1, 1, 30),
(204, 148, 1, 1, 30),
(205, 147, 1, 1, 30),
(206, 146, 1, 1, 30),
(207, 145, 1, 1, 30),
(208, 144, 1, 1, 30),
(209, 143, 1, 1, 30),
(210, 142, 1, 1, 30),
(211, 141, 1, 1, 30),
(212, 140, 1, 1, 30),
(213, 139, 1, 1, 30),
(214, 138, 1, 1, 30),
(215, 137, 1, 1, 30),
(216, 136, 1, 1, 30),
(217, 135, 1, 1, 30),
(218, 134, 1, 1, 30),
(219, 133, 1, 1, 30),
(220, 132, 1, 1, 30),
(221, 131, 1, 1, 30),
(222, 130, 1, 1, 30),
(223, 129, 1, 1, 30),
(224, 128, 1, 1, 30),
(225, 127, 1, 1, 30),
(226, 126, 1, 1, 30),
(227, 125, 1, 1, 30),
(228, 124, 1, 1, 30),
(229, 123, 1, 1, 30),
(230, 122, 1, 1, 30),
(231, 121, 1, 1, 30),
(232, 120, 1, 1, 30),
(233, 119, 1, 1, 30),
(234, 118, 1, 1, 30),
(235, 117, 1, 1, 30),
(236, 116, 1, 1, 30),
(237, 115, 1, 1, 30),
(238, 114, 1, 1, 30),
(239, 113, 1, 1, 30),
(240, 112, 1, 1, 30),
(241, 111, 1, 1, 30),
(242, 110, 1, 1, 30),
(243, 109, 1, 1, 30),
(244, 108, 1, 1, 30),
(245, 107, 1, 1, 30),
(246, 106, 1, 1, 30),
(247, 105, 1, 1, 30),
(248, 104, 1, 1, 30),
(249, 103, 1, 1, 30),
(250, 102, 1, 1, 30),
(251, 101, 1, 1, 30),
(252, 100, 1, 1, 30),
(253, 99, 1, 1, 30),
(254, 98, 1, 1, 30),
(255, 97, 1, 1, 30),
(256, 96, 1, 1, 30),
(257, 95, 1, 1, 30),
(258, 94, 1, 1, 30),
(259, 93, 1, 1, 30),
(260, 92, 1, 1, 30),
(261, 91, 1, 1, 30),
(262, 90, 1, 1, 30),
(263, 89, 1, 1, 30),
(264, 88, 1, 1, 30),
(265, 87, 1, 1, 30),
(266, 86, 1, 1, 30),
(267, 85, 1, 1, 30),
(268, 84, 1, 1, 30),
(269, 83, 1, 1, 30),
(270, 82, 1, 1, 30),
(271, 81, 1, 1, 30),
(272, 80, 1, 1, 30),
(273, 79, 1, 1, 30),
(274, 78, 1, 1, 30),
(275, 77, 1, 1, 30),
(276, 76, 1, 1, 30),
(277, 75, 1, 1, 30),
(278, 74, 1, 1, 30),
(279, 73, 1, 1, 30),
(280, 72, 1, 1, 30),
(281, 71, 1, 1, 30),
(282, 70, 1, 1, 30),
(283, 69, 1, 1, 30),
(284, 68, 1, 1, 30),
(285, 67, 1, 1, 30),
(286, 66, 1, 1, 30),
(287, 65, 1, 1, 30),
(288, 64, 1, 1, 30),
(289, 63, 1, 1, 30),
(290, 62, 1, 1, 30),
(291, 61, 1, 1, 30),
(292, 60, 1, 1, 30),
(293, 59, 1, 1, 30),
(294, 58, 1, 1, 30),
(295, 57, 1, 1, 30),
(296, 56, 1, 1, 30),
(297, 55, 1, 1, 30),
(298, 54, 1, 1, 30),
(299, 53, 1, 1, 30),
(300, 52, 1, 1, 30),
(301, 51, 1, 1, 30),
(302, 50, 1, 1, 30),
(303, 49, 1, 1, 30),
(304, 48, 1, 1, 30),
(305, 47, 1, 1, 30),
(306, 46, 1, 1, 30),
(307, 45, 1, 1, 30),
(308, 44, 1, 1, 30),
(309, 43, 1, 1, 30),
(310, 42, 1, 1, 30),
(311, 41, 1, 1, 30),
(312, 40, 1, 1, 30),
(313, 39, 1, 1, 30),
(314, 38, 1, 1, 30),
(315, 37, 1, 1, 30),
(316, 36, 1, 1, 30),
(317, 35, 1, 1, 30),
(318, 34, 1, 1, 30),
(319, 33, 1, 1, 30),
(320, 32, 1, 1, 30),
(321, 31, 1, 1, 30),
(322, 30, 1, 1, 30),
(323, 29, 1, 1, 30),
(324, 28, 1, 1, 30),
(325, 27, 1, 1, 30),
(326, 26, 1, 1, 30),
(327, 25, 1, 1, 30),
(328, 24, 1, 1, 30),
(329, 23, 1, 1, 30),
(330, 22, 1, 1, 30),
(331, 21, 1, 1, 30),
(332, 20, 1, 1, 30),
(333, 19, 1, 1, 30),
(334, 18, 1, 1, 30),
(335, 17, 1, 1, 30),
(336, 16, 1, 1, 30),
(337, 15, 1, 1, 30),
(338, 14, 1, 1, 30),
(339, 13, 1, 1, 30),
(340, 12, 1, 1, 30),
(341, 11, 1, 1, 30),
(342, 10, 1, 1, 30),
(343, 9, 1, 1, 30),
(344, 8, 1, 1, 30),
(345, 7, 1, 1, 30),
(346, 6, 1, 1, 30),
(347, 5, 1, 1, 30),
(348, 4, 1, 1, 30),
(349, 3, 1, 1, 30),
(350, 2, 1, 1, 30),
(355, 353, 1, 1, 30),
(356, 354, 1, 1, 30),
(357, 355, 1, 1, 30),
(358, 356, 1, 1, 30),
(359, 357, 1, 1, 30),
(360, 358, 1, 1, 30),
(361, 359, 1, 1, 30),
(362, 360, 1, 1, 30),
(363, 361, 1, 1, 30),
(364, 362, 1, 1, 30),
(365, 363, 1, 1, 30),
(366, 364, 1, 1, 30),
(367, 365, 1, 1, 30),
(369, 367, 1, 1, 30),
(370, 368, 1, 1, 30),
(371, 369, 1, 1, 30),
(372, 370, 1, 1, 30),
(373, 371, 1, 1, 30),
(374, 372, 1, 1, 30),
(375, 373, 1, 1, 30),
(376, 374, 1, 1, 30),
(377, 375, 1, 1, 30),
(378, 376, 1, 1, 30),
(379, 377, 1, 1, 30),
(380, 378, 1, 1, 30),
(381, 379, 1, 1, 30),
(382, 380, 1, 1, 30),
(383, 381, 1, 1, 30),
(384, 382, 1, 1, 30),
(385, 383, 1, 1, 30),
(386, 384, 1, 1, 30),
(387, 385, 1, 1, 30),
(388, 386, 1, 1, 30),
(389, 387, 1, 1, 30),
(390, 388, 1, 1, 30),
(391, 389, 1, 1, 30),
(392, 390, 1, 1, 30),
(393, 391, 1, 1, 30),
(394, 392, 1, 1, 30),
(395, 393, 1, 1, 30),
(396, 394, 1, 1, 30),
(397, 395, 1, 1, 30),
(400, 398, 1, 1, 30),
(401, 399, 1, 1, 30),
(402, 400, 1, 1, 30),
(403, 401, 1, 1, 30),
(407, 405, 1, 1, 30),
(408, 406, 1, 1, 30),
(410, 408, 1, 1, 30),
(411, 409, 1, 1, 30),
(412, 410, 1, 1, 30),
(413, 411, 1, 1, 30),
(414, 412, 1, 1, 30),
(415, 413, 1, 1, 30),
(418, 416, 1, 1, 30),
(419, 417, 1, 1, 30),
(421, 419, 1, 1, 30),
(422, 420, 1, 1, 30),
(423, 421, 1, 1, 30),
(424, 422, 1, 1, 30),
(425, 423, 1, 1, 30),
(426, 424, 1, 1, 30),
(427, 425, 1, 1, 30),
(428, 426, 1, 1, 30),
(429, 427, 1, 1, 30),
(430, 428, 1, 1, 30),
(431, 429, 1, 1, 30),
(432, 430, 1, 1, 30),
(433, 431, 1, 1, 30),
(434, 432, 1, 1, 30),
(435, 433, 1, 1, 30),
(436, 434, 1, 1, 30),
(437, 435, 1, 1, 30),
(438, 436, 1, 1, 30),
(439, 437, 1, 1, 30),
(440, 438, 1, 1, 30),
(441, 439, 1, 1, 30),
(442, 440, 1, 1, 30),
(443, 441, 1, 1, 30),
(444, 442, 1, 1, 30),
(445, 443, 1, 1, 30),
(446, 444, 1, 1, 30),
(447, 445, 1, 1, 30),
(448, 446, 1, 1, 30),
(449, 447, 1, 1, 30),
(450, 448, 1, 1, 30),
(451, 449, 1, 1, 30),
(452, 450, 1, 1, 30),
(453, 451, 1, 1, 30),
(454, 452, 1, 1, 30),
(455, 453, 1, 1, 30),
(456, 454, 1, 1, 30),
(457, 455, 1, 1, 30),
(458, 456, 1, 1, 30),
(459, 457, 1, 1, 30),
(460, 458, 1, 1, 30),
(461, 459, 1, 1, 30),
(462, 460, 1, 1, 30),
(463, 461, 1, 1, 30),
(464, 462, 1, 1, 30),
(465, 463, 1, 1, 30),
(466, 464, 1, 1, 30),
(467, 465, 1, 1, 30),
(468, 466, 1, 1, 30),
(469, 467, 1, 1, 30),
(470, 468, 1, 1, 30),
(474, 472, 1, 1, 30),
(475, 473, 1, 1, 30),
(489, 487, 1, 1, 30),
(490, 488, 1, 1, 30),
(491, 489, 1, 1, 30),
(492, 490, 1, 1, 30),
(493, 491, 1, 1, 30),
(495, 493, 1, 1, 30),
(496, 494, 1, 1, 30),
(497, 495, 1, 1, 30),
(498, 496, 1, 1, 30),
(499, 497, 1, 1, 30),
(500, 498, 1, 1, 30),
(501, 499, 1, 1, 30),
(502, 500, 1, 1, 30),
(503, 501, 1, 1, 30),
(504, 502, 1, 1, 30),
(505, 503, 1, 1, 30),
(506, 504, 1, 1, 30),
(507, 505, 1, 1, 30),
(508, 506, 1, 1, 30),
(510, 508, 1, 1, 30),
(511, 509, 1, 1, 30),
(512, 510, 1, 1, 30),
(513, 511, 1, 1, 30),
(514, 512, 1, 1, 30),
(515, 513, 1, 1, 30),
(516, 514, 1, 1, 30),
(517, 515, 1, 1, 30),
(518, 516, 1, 1, 30),
(519, 517, 1, 1, 30),
(520, 518, 1, 1, 30),
(521, 519, 1, 1, 30),
(522, 520, 1, 1, 30),
(523, 521, 1, 1, 30),
(524, 522, 1, 1, 30),
(525, 523, 1, 1, 30),
(526, 524, 1, 1, 30),
(527, 525, 1, 1, 30),
(528, 526, 1, 1, 30),
(529, 527, 1, 1, 30),
(530, 528, 1, 1, 30),
(531, 529, 1, 1, 30),
(532, 530, 1, 1, 30),
(533, 531, 1, 1, 30),
(534, 532, 1, 1, 30),
(535, 533, 1, 1, 30),
(536, 534, 1, 1, 30),
(537, 535, 1, 1, 30),
(538, 536, 1, 1, 30),
(539, 537, 1, 1, 30),
(540, 538, 1, 1, 30),
(541, 539, 1, 1, 30),
(542, 540, 1, 1, 30),
(543, 541, 1, 1, 30),
(544, 542, 1, 1, 30),
(545, 543, 1, 1, 30),
(546, 544, 1, 1, 30),
(547, 545, 1, 1, 30),
(548, 546, 1, 1, 30),
(549, 547, 1, 1, 30),
(550, 548, 1, 1, 30),
(551, 549, 1, 1, 30),
(552, 550, 1, 1, 30),
(553, 551, 1, 1, 30),
(554, 552, 1, 1, 30),
(555, 553, 1, 1, 30),
(556, 554, 1, 1, 30),
(557, 555, 1, 1, 30),
(558, 556, 1, 1, 30),
(559, 557, 1, 1, 30),
(560, 558, 1, 1, 30),
(561, 559, 1, 1, 30),
(562, 560, 1, 1, 30),
(563, 561, 1, 1, 30),
(564, 562, 1, 1, 30),
(565, 563, 1, 1, 30),
(566, 564, 1, 1, 30),
(567, 565, 1, 1, 30),
(568, 566, 1, 1, 30),
(569, 567, 1, 1, 30),
(570, 568, 1, 1, 30),
(571, 569, 1, 1, 30),
(572, 570, 1, 1, 30),
(573, 571, 1, 1, 30),
(574, 572, 1, 1, 30),
(575, 573, 1, 1, 30),
(576, 574, 1, 1, 30),
(577, 575, 1, 1, 30),
(578, 576, 1, 1, 30),
(579, 577, 1, 1, 30),
(580, 578, 1, 1, 30),
(581, 579, 1, 1, 30),
(582, 580, 1, 1, 30),
(583, 581, 1, 1, 30),
(585, 583, 1, 1, 30),
(586, 584, 1, 1, 30),
(587, 585, 1, 1, 30),
(588, 586, 1, 1, 30),
(589, 587, 1, 1, 30),
(590, 588, 1, 1, 30),
(591, 589, 1, 1, 30),
(592, 590, 1, 1, 30),
(593, 591, 1, 1, 30),
(594, 592, 1, 1, 30),
(595, 593, 1, 1, 30),
(596, 594, 1, 1, 30),
(597, 595, 1, 1, 30),
(598, 596, 1, 1, 30),
(599, 597, 1, 1, 30),
(600, 598, 1, 1, 30),
(601, 599, 1, 1, 30),
(602, 600, 1, 1, 30),
(603, 601, 1, 1, 30),
(604, 602, 1, 1, 30),
(606, 604, 1, 1, 30),
(607, 605, 1, 1, 30),
(608, 606, 1, 1, 30),
(609, 607, 1, 1, 30),
(610, 608, 1, 1, 30),
(611, 609, 1, 1, 30),
(612, 610, 1, 1, 30),
(613, 611, 1, 1, 30),
(614, 612, 1, 1, 30),
(615, 613, 1, 1, 30),
(616, 614, 1, 1, 30),
(617, 615, 1, 1, 30),
(618, 616, 1, 1, 30),
(619, 617, 1, 1, 30),
(620, 618, 1, 1, 30),
(621, 619, 1, 1, 30),
(622, 620, 1, 1, 30),
(623, 621, 1, 1, 30),
(624, 622, 1, 1, 30),
(625, 623, 1, 1, 30),
(626, 624, 1, 1, 30),
(627, 625, 1, 1, 30),
(628, 626, 1, 1, 30),
(629, 627, 1, 1, 30),
(630, 628, 1, 1, 30),
(631, 629, 1, 1, 30),
(632, 630, 1, 1, 30),
(633, 631, 1, 1, 30),
(634, 632, 1, 1, 30),
(635, 633, 1, 1, 30),
(636, 634, 1, 1, 30),
(637, 635, 1, 1, 30),
(638, 636, 1, 1, 30),
(639, 637, 1, 1, 30),
(640, 638, 1, 1, 30),
(641, 639, 1, 1, 30),
(642, 640, 1, 1, 30),
(643, 641, 1, 1, 30),
(644, 642, 1, 1, 30),
(645, 643, 1, 1, 30),
(646, 644, 1, 1, 30),
(647, 645, 1, 1, 30),
(648, 646, 1, 1, 30),
(649, 647, 1, 1, 30),
(650, 648, 1, 1, 30),
(651, 649, 1, 1, 30),
(652, 650, 1, 1, 30),
(653, 651, 1, 1, 30),
(654, 652, 1, 1, 30),
(655, 653, 1, 1, 30),
(656, 654, 1, 1, 30),
(657, 655, 1, 1, 30),
(659, 657, 1, 1, 30),
(660, 658, 1, 1, 30),
(661, 659, 1, 1, 30),
(662, 660, 1, 1, 30),
(663, 661, 1, 1, 30),
(664, 662, 1, 1, 30),
(666, 664, 1, 1, 30),
(667, 665, 1, 1, 30),
(668, 666, 1, 1, 30),
(670, 668, 1, 1, 30),
(672, 669, 1, 1, 30),
(673, 670, 1, 1, 30),
(674, 671, 1, 1, 30),
(676, 673, 1, 1, 30),
(677, 674, 1, 1, 30),
(678, 675, 1, 1, 30),
(679, 676, 1, 1, 30),
(680, 677, 1, 1, 30),
(681, 678, 1, 1, 30),
(682, 679, 1, 1, 30),
(683, 680, 1, 1, 30),
(685, 682, 1, 1, 30),
(686, 683, 1, 1, 30),
(687, 684, 1, 1, 30),
(689, 688, 1, 1, 30),
(690, 689, 1, 1, 30),
(691, 690, 1, 1, 30),
(692, 691, 1, 1, 30),
(693, 692, 1, 1, 30),
(694, 693, 1, 1, 30),
(695, 694, 1, 1, 30),
(696, 695, 1, 1, 30),
(697, 696, 1, 1, 30),
(698, 699, 1, 1, 30),
(699, 700, 1, 1, 30),
(700, 701, 1, 1, 30),
(701, 702, 1, 1, 30),
(702, 703, 1, 1, 30),
(703, 704, 1, 1, 30),
(704, 705, 1, 1, 30),
(705, 706, 1, 1, 30),
(706, 707, 1, 1, 30),
(707, 708, 1, 1, 30),
(708, 709, 1, 1, 30),
(709, 710, 1, 1, 30),
(710, 711, 1, 1, 30),
(711, 712, 1, 1, 30),
(712, 713, 1, 1, 30),
(713, 714, 1, 1, 30),
(714, 715, 1, 1, 30),
(715, 716, 1, 1, 30),
(716, 717, 1, 1, 30),
(717, 718, 1, 1, 30),
(718, 719, 1, 1, 30),
(719, 720, 1, 1, 30),
(720, 721, 1, 1, 30),
(721, 722, 1, 1, 30),
(722, 723, 1, 1, 30),
(723, 725, 1, 1, 30),
(724, 726, 1, 1, 30),
(728, 730, 1, 1, 30),
(729, 731, 1, 1, 30),
(731, 733, 1, 1, 30),
(732, 734, 1, 1, 30),
(733, 735, 1, 1, 30),
(734, 736, 1, 1, 30),
(735, 737, 1, 1, 30),
(736, 738, 1, 1, 30),
(737, 739, 1, 1, 30),
(738, 740, 1, 1, 30),
(739, 741, 1, 1, 30),
(740, 742, 1, 1, 30),
(741, 743, 1, 1, 30),
(742, 744, 1, 1, 30),
(743, 745, 1, 1, 30),
(744, 746, 1, 1, 30),
(745, 747, 1, 1, 30),
(746, 748, 1, 1, 30),
(747, 749, 1, 1, 30),
(748, 750, 1, 1, 30),
(749, 751, 1, 1, 30),
(750, 752, 1, 1, 30),
(751, 753, 1, 1, 30),
(752, 754, 1, 1, 30),
(753, 755, 1, 1, 30),
(754, 756, 1, 1, 30),
(755, 757, 1, 1, 30),
(756, 758, 1, 1, 30),
(757, 759, 1, 1, 30),
(758, 760, 1, 1, 30),
(759, 761, 1, 1, 30),
(760, 762, 1, 1, 30),
(761, 763, 1, 1, 30),
(762, 764, 1, 1, 30),
(763, 765, 1, 1, 30),
(764, 766, 1, 1, 30),
(765, 767, 1, 1, 30),
(766, 768, 1, 1, 30),
(767, 769, 1, 1, 30),
(768, 770, 1, 1, 30),
(769, 771, 1, 1, 30),
(770, 772, 1, 1, 30),
(771, 773, 1, 1, 30),
(772, 774, 1, 1, 30),
(773, 775, 1, 1, 30),
(774, 776, 1, 1, 30),
(775, 777, 1, 1, 30),
(776, 778, 1, 1, 30),
(777, 779, 1, 1, 30),
(778, 780, 1, 1, 30),
(779, 781, 1, 1, 30),
(780, 782, 1, 1, 30),
(781, 783, 1, 1, 30),
(782, 784, 1, 1, 30),
(783, 785, 1, 1, 30),
(784, 786, 1, 1, 30),
(785, 787, 1, 1, 30),
(786, 788, 1, 1, 30),
(787, 789, 1, 1, 30),
(788, 790, 1, 1, 30),
(789, 791, 1, 1, 30),
(790, 792, 1, 1, 30),
(791, 793, 1, 1, 30),
(792, 794, 1, 1, 30),
(793, 795, 1, 1, 30),
(794, 796, 1, 1, 30),
(795, 797, 1, 1, 30),
(796, 798, 1, 1, 30),
(797, 799, 1, 1, 30),
(798, 800, 1, 1, 30),
(799, 801, 1, 1, 30),
(800, 802, 1, 1, 30),
(801, 803, 1, 1, 30),
(802, 804, 1, 1, 30),
(803, 805, 1, 1, 30),
(804, 806, 1, 1, 30),
(805, 807, 1, 1, 30),
(806, 808, 1, 1, 30),
(807, 809, 1, 1, 30),
(808, 810, 1, 1, 30),
(809, 811, 1, 1, 30),
(810, 812, 1, 1, 30),
(811, 813, 1, 1, 30),
(812, 814, 1, 1, 30),
(813, 815, 1, 1, 30),
(814, 816, 1, 1, 30),
(815, 817, 1, 1, 30),
(816, 818, 1, 1, 30),
(817, 819, 1, 1, 30),
(818, 820, 1, 1, 30),
(819, 821, 1, 1, 30),
(820, 822, 1, 1, 30),
(821, 823, 1, 1, 30),
(822, 824, 1, 1, 30),
(823, 825, 1, 1, 30),
(824, 826, 1, 1, 30),
(825, 827, 1, 1, 30),
(826, 828, 1, 1, 30),
(827, 829, 1, 1, 30),
(828, 830, 1, 1, 30),
(829, 831, 1, 1, 30),
(830, 832, 1, 1, 30),
(831, 833, 1, 1, 30),
(832, 834, 1, 1, 30),
(833, 835, 1, 1, 30),
(834, 836, 1, 1, 30),
(835, 837, 1, 1, 30),
(836, 838, 1, 1, 30),
(837, 839, 1, 1, 30),
(838, 840, 1, 1, 30),
(839, 841, 1, 1, 30),
(840, 842, 1, 1, 30),
(841, 843, 1, 1, 30),
(842, 844, 1, 1, 30),
(843, 845, 1, 1, 30),
(844, 846, 1, 1, 30),
(845, 847, 1, 1, 30),
(846, 848, 1, 1, 30),
(847, 849, 1, 1, 30),
(848, 850, 1, 1, 30),
(849, 851, 1, 1, 30),
(850, 852, 1, 1, 30),
(851, 853, 1, 1, 30),
(852, 854, 1, 1, 30),
(853, 855, 1, 1, 30),
(854, 856, 1, 1, 30),
(855, 857, 1, 1, 30),
(856, 858, 1, 1, 30),
(857, 859, 1, 1, 30),
(858, 860, 1, 1, 30),
(859, 861, 1, 1, 30),
(860, 862, 1, 1, 30),
(861, 863, 1, 1, 30),
(862, 864, 1, 1, 30),
(863, 865, 1, 1, 30),
(864, 866, 1, 1, 30),
(865, 867, 1, 1, 30),
(866, 868, 1, 1, 30),
(867, 869, 1, 1, 30),
(868, 870, 1, 1, 30),
(869, 871, 1, 1, 30),
(870, 872, 1, 1, 30),
(871, 873, 1, 1, 30),
(872, 874, 1, 1, 30),
(873, 875, 1, 1, 30),
(874, 876, 1, 1, 30),
(875, 877, 1, 1, 30),
(876, 878, 1, 1, 30),
(877, 879, 1, 1, 30),
(878, 880, 1, 1, 30),
(879, 881, 1, 1, 30),
(880, 882, 1, 1, 30),
(881, 883, 1, 1, 30),
(882, 884, 1, 1, 30),
(883, 885, 1, 1, 30),
(884, 886, 1, 1, 30),
(885, 887, 1, 1, 30),
(886, 888, 1, 1, 30),
(887, 889, 1, 1, 30),
(888, 890, 1, 1, 30),
(889, 891, 1, 1, 30),
(890, 892, 1, 1, 30),
(891, 893, 1, 1, 30),
(892, 894, 1, 1, 30),
(893, 895, 1, 1, 30),
(894, 896, 1, 1, 30),
(895, 897, 1, 1, 30),
(896, 898, 1, 1, 30),
(897, 899, 1, 1, 30),
(898, 900, 1, 1, 30),
(899, 901, 1, 1, 30),
(900, 902, 1, 1, 30),
(901, 903, 1, 1, 30),
(902, 904, 1, 1, 30),
(903, 905, 1, 1, 30),
(904, 906, 1, 1, 30),
(905, 907, 1, 1, 30),
(906, 908, 1, 1, 30),
(907, 909, 1, 1, 30),
(908, 910, 1, 1, 30),
(909, 911, 1, 1, 30),
(910, 912, 1, 1, 30),
(911, 913, 1, 1, 30),
(912, 914, 1, 1, 30),
(913, 915, 1, 1, 30),
(914, 916, 1, 1, 30),
(915, 917, 1, 1, 30),
(916, 918, 1, 1, 30),
(917, 919, 1, 1, 30),
(918, 920, 1, 1, 30),
(919, 921, 1, 1, 30),
(920, 922, 1, 1, 30),
(921, 923, 1, 1, 30),
(922, 924, 1, 1, 30),
(923, 925, 1, 1, 30),
(924, 926, 1, 1, 30),
(925, 927, 1, 1, 30),
(926, 928, 1, 1, 30),
(927, 929, 1, 1, 30),
(928, 930, 1, 1, 30),
(929, 931, 1, 1, 30),
(930, 932, 1, 1, 30),
(931, 933, 1, 1, 30),
(932, 934, 1, 1, 30),
(933, 935, 1, 1, 30),
(934, 936, 1, 1, 30),
(935, 937, 1, 1, 30),
(936, 938, 1, 1, 30),
(937, 939, 1, 1, 30),
(938, 940, 1, 1, 30),
(939, 941, 1, 1, 30),
(940, 942, 1, 1, 30),
(941, 943, 1, 1, 30),
(942, 944, 1, 1, 30),
(943, 945, 1, 1, 30),
(944, 946, 1, 1, 30),
(945, 947, 1, 1, 30),
(946, 948, 1, 1, 30),
(947, 949, 1, 1, 30),
(948, 950, 1, 1, 30),
(949, 951, 1, 1, 30),
(950, 952, 1, 1, 30),
(951, 953, 1, 1, 30),
(952, 954, 1, 1, 30),
(953, 955, 1, 1, 30),
(954, 956, 1, 1, 30),
(955, 957, 1, 1, 30),
(956, 958, 1, 1, 30),
(957, 959, 1, 1, 30),
(958, 960, 1, 1, 30),
(959, 961, 1, 1, 30),
(960, 962, 1, 1, 30),
(961, 963, 1, 1, 30),
(962, 964, 1, 1, 30),
(963, 965, 1, 1, 30),
(964, 966, 1, 1, 30),
(965, 967, 1, 1, 30),
(966, 968, 1, 1, 30),
(967, 969, 1, 1, 30),
(968, 970, 1, 1, 30),
(969, 971, 1, 1, 30),
(970, 972, 1, 1, 30),
(971, 973, 1, 1, 30),
(972, 974, 1, 1, 30),
(973, 975, 1, 1, 30),
(974, 976, 1, 1, 30),
(975, 977, 1, 1, 30),
(976, 978, 1, 1, 30),
(977, 979, 1, 1, 30),
(978, 980, 1, 1, 30),
(979, 981, 1, 1, 30),
(980, 982, 1, 1, 30),
(981, 983, 1, 1, 30),
(982, 984, 1, 1, 30),
(983, 985, 1, 1, 30),
(984, 986, 1, 1, 30),
(985, 987, 1, 1, 30),
(986, 988, 1, 1, 30),
(987, 989, 1, 1, 30),
(988, 990, 1, 1, 30),
(989, 991, 1, 1, 30),
(990, 992, 1, 1, 30),
(991, 993, 1, 1, 30),
(992, 994, 1, 1, 30),
(993, 995, 1, 1, 30),
(994, 996, 1, 1, 30),
(995, 997, 1, 1, 30),
(996, 998, 1, 1, 30),
(997, 999, 1, 1, 30),
(998, 1000, 1, 1, 30),
(999, 1001, 1, 1, 30),
(1000, 1002, 1, 1, 30),
(1001, 1003, 1, 1, 30),
(1002, 1004, 1, 1, 30),
(1003, 1005, 1, 1, 30),
(1004, 1006, 1, 1, 30),
(1005, 1007, 1, 1, 30),
(1006, 1008, 1, 1, 30),
(1007, 1009, 1, 1, 30),
(1008, 1010, 1, 1, 30),
(1009, 1011, 1, 1, 30),
(1010, 1012, 1, 1, 30),
(1011, 1013, 1, 1, 30),
(1012, 1014, 1, 1, 30),
(1013, 1015, 1, 1, 30),
(1014, 1016, 1, 1, 30),
(1015, 1017, 1, 1, 30),
(1016, 1018, 1, 1, 30),
(1017, 1019, 1, 1, 30),
(1018, 1020, 1, 1, 30),
(1019, 1021, 1, 1, 30),
(1020, 1022, 1, 1, 30),
(1021, 1023, 1, 1, 30),
(1022, 1024, 1, 1, 30),
(1023, 1025, 1, 1, 30),
(1024, 1026, 1, 1, 30),
(1025, 1027, 1, 1, 30),
(1026, 1028, 1, 1, 30),
(1027, 1029, 1, 1, 30),
(1028, 1030, 1, 1, 30),
(1029, 1031, 1, 1, 30),
(1030, 1032, 1, 1, 30),
(1031, 1033, 1, 1, 30),
(1032, 1034, 1, 1, 30),
(1033, 1035, 1, 1, 30),
(1034, 1036, 1, 1, 30),
(1035, 1037, 1, 1, 30),
(1036, 1038, 1, 1, 30),
(1037, 1039, 1, 1, 30),
(1038, 1040, 1, 1, 30),
(1039, 1041, 1, 1, 30),
(1040, 1042, 1, 1, 30),
(1041, 1043, 1, 1, 30),
(1042, 1044, 1, 1, 30),
(1043, 1045, 1, 1, 30),
(1044, 1046, 1, 1, 30),
(1045, 1047, 1, 1, 30),
(1046, 1048, 1, 1, 30),
(1047, 1049, 1, 1, 30),
(1048, 1050, 1, 1, 30),
(1049, 1051, 1, 1, 30),
(1050, 1052, 1, 1, 30),
(1051, 1053, 1, 1, 30),
(1052, 1054, 1, 1, 30),
(1053, 1055, 1, 1, 30),
(1054, 1056, 1, 1, 30),
(1055, 1057, 1, 1, 30),
(1056, 1058, 1, 1, 30),
(1057, 1059, 1, 1, 30),
(1058, 1060, 1, 1, 30),
(1059, 1061, 1, 1, 30),
(1060, 1062, 1, 1, 30),
(1061, 1063, 1, 1, 30),
(1062, 1064, 1, 1, 30),
(1063, 1065, 1, 1, 30),
(1064, 1066, 1, 1, 30),
(1065, 1067, 1, 1, 30),
(1066, 1068, 1, 1, 30),
(1067, 1069, 1, 1, 30),
(1068, 1070, 1, 1, 30),
(1069, 1071, 1, 1, 30),
(1070, 1072, 1, 1, 30),
(1071, 1073, 1, 1, 30),
(1072, 1074, 1, 1, 30),
(1073, 1075, 1, 1, 30),
(1074, 1076, 1, 1, 30),
(1075, 1077, 1, 1, 30),
(1076, 1078, 1, 1, 30),
(1077, 1079, 1, 1, 30),
(1078, 1080, 1, 1, 30),
(1079, 1081, 1, 1, 30),
(1080, 1082, 1, 1, 30),
(1081, 1083, 1, 1, 30),
(1082, 1084, 1, 1, 30),
(1083, 1085, 1, 1, 30),
(1084, 1086, 1, 1, 30),
(1085, 1087, 1, 1, 30),
(1086, 1088, 1, 1, 30),
(1087, 1089, 1, 1, 30),
(1088, 1090, 1, 1, 30),
(1089, 1091, 1, 1, 30),
(1090, 1092, 1, 1, 30),
(1091, 1093, 1, 1, 30),
(1092, 1094, 1, 1, 30),
(1093, 1095, 1, 1, 30),
(1094, 1096, 1, 1, 30),
(1095, 1097, 1, 1, 30),
(1096, 1098, 1, 1, 30),
(1097, 1099, 1, 1, 30),
(1098, 1100, 1, 1, 30),
(1099, 1101, 1, 1, 30),
(1100, 1102, 1, 1, 30),
(1101, 1103, 1, 1, 30),
(1102, 1104, 1, 1, 30),
(1103, 1105, 1, 1, 30),
(1104, 1106, 1, 1, 30),
(1105, 1107, 1, 1, 30),
(1106, 1108, 1, 1, 30),
(1107, 1109, 1, 1, 30),
(1108, 1110, 1, 1, 30),
(1109, 1111, 1, 1, 30),
(1110, 1112, 1, 1, 30),
(1111, 1113, 1, 1, 30),
(1112, 1114, 1, 1, 30),
(1113, 1115, 1, 1, 30),
(1114, 1116, 1, 1, 30),
(1115, 1117, 1, 1, 30),
(1116, 1118, 1, 1, 30),
(1117, 1119, 1, 1, 30),
(1118, 1120, 1, 1, 30),
(1119, 1121, 1, 1, 30),
(1120, 1122, 1, 1, 30),
(1121, 1123, 1, 1, 30),
(1122, 1124, 1, 1, 30),
(1123, 1125, 1, 1, 30),
(1124, 1126, 1, 1, 30),
(1125, 1127, 1, 1, 30),
(1126, 1128, 1, 1, 30),
(1127, 1129, 1, 1, 30),
(1128, 1130, 1, 1, 30),
(1129, 1131, 1, 1, 30),
(1130, 1132, 1, 1, 30),
(1131, 1133, 1, 1, 30),
(1132, 1134, 1, 1, 30),
(1133, 1135, 1, 1, 30),
(1134, 1136, 1, 1, 30),
(1135, 1137, 1, 1, 30),
(1136, 1138, 1, 1, 30),
(1137, 1139, 1, 1, 30),
(1138, 1140, 1, 1, 30),
(1139, 1141, 1, 1, 30),
(1140, 1142, 1, 1, 30),
(1141, 1143, 1, 1, 30),
(1142, 1144, 1, 1, 30),
(1143, 1145, 1, 1, 30),
(1144, 1146, 1, 1, 30),
(1145, 1147, 1, 1, 30),
(1146, 1148, 1, 1, 30),
(1147, 1149, 1, 1, 30),
(1148, 1150, 1, 1, 30),
(1149, 1151, 1, 1, 30),
(1150, 1152, 1, 1, 30),
(1151, 1153, 1, 1, 30),
(1152, 1154, 1, 1, 30),
(1153, 1155, 1, 1, 30),
(1154, 1156, 1, 1, 30),
(1155, 1157, 1, 1, 30),
(1156, 1158, 1, 1, 30),
(1157, 1159, 1, 1, 30),
(1158, 1160, 1, 1, 30),
(1159, 1161, 1, 1, 30),
(1160, 1162, 1, 1, 30),
(1161, 1163, 1, 1, 30),
(1162, 1164, 1, 1, 30),
(1163, 1165, 1, 1, 30),
(1164, 1166, 1, 1, 30),
(1165, 1167, 1, 1, 30),
(1166, 1168, 1, 1, 30),
(1167, 1169, 1, 1, 30),
(1168, 1170, 1, 1, 30),
(1169, 1171, 1, 1, 30),
(1170, 1172, 1, 1, 30),
(1171, 1173, 1, 1, 30),
(1172, 1174, 1, 1, 30),
(1173, 1175, 1, 1, 30),
(1174, 1176, 1, 1, 30),
(1175, 1177, 1, 1, 30),
(1176, 1178, 1, 1, 30),
(1177, 1179, 1, 1, 30),
(1178, 1180, 1, 1, 30),
(1179, 1181, 1, 1, 30),
(1180, 1182, 1, 1, 30),
(1181, 1183, 1, 1, 30),
(1182, 1184, 1, 1, 30),
(1183, 1185, 1, 1, 30),
(1184, 1186, 1, 1, 30),
(1185, 1187, 1, 1, 30),
(1186, 1188, 1, 1, 30),
(1187, 1189, 1, 1, 30),
(1188, 1190, 1, 1, 30),
(1189, 1191, 1, 1, 30),
(1190, 1192, 1, 1, 30),
(1191, 1193, 1, 1, 30),
(1192, 1194, 1, 1, 30),
(1193, 1195, 1, 1, 30),
(1194, 1196, 1, 1, 30),
(1195, 1197, 1, 1, 30),
(1196, 1198, 1, 1, 30),
(1197, 1199, 1, 1, 30),
(1198, 1200, 1, 1, 30),
(1199, 1201, 1, 1, 30),
(1200, 1202, 1, 1, 30),
(1201, 1203, 1, 1, 30),
(1202, 1204, 1, 1, 30),
(1203, 1205, 1, 1, 30),
(1204, 1206, 1, 1, 30),
(1205, 1207, 1, 1, 30),
(1206, 1208, 1, 1, 30),
(1207, 1209, 1, 1, 30),
(1208, 1210, 1, 1, 30),
(1209, 1211, 1, 1, 30),
(1210, 1212, 1, 1, 30),
(1211, 1213, 1, 1, 30),
(1212, 1214, 1, 1, 30),
(1213, 1215, 1, 1, 30),
(1214, 1216, 1, 1, 30),
(1215, 1217, 1, 1, 30),
(1216, 1218, 1, 1, 30),
(1217, 1219, 1, 1, 30),
(1218, 1220, 1, 1, 30),
(1219, 1221, 1, 1, 30),
(1220, 1222, 1, 1, 30),
(1221, 1223, 1, 1, 30),
(1222, 1224, 1, 1, 30),
(1223, 1225, 1, 1, 30),
(1224, 1226, 1, 1, 30),
(1225, 1227, 1, 1, 30),
(1226, 1228, 1, 1, 30),
(1227, 1229, 1, 1, 30),
(1228, 1230, 1, 1, 30),
(1229, 1231, 1, 1, 30),
(1230, 1232, 1, 1, 30),
(1231, 1233, 1, 1, 30),
(1232, 1234, 1, 1, 30),
(1233, 1235, 1, 1, 30),
(1234, 1236, 1, 1, 30),
(1235, 1237, 1, 1, 30),
(1236, 1238, 1, 1, 30),
(1237, 1239, 1, 1, 30),
(1238, 1240, 1, 1, 30),
(1239, 1241, 1, 1, 30),
(1240, 1242, 1, 1, 30),
(1241, 1243, 1, 1, 30),
(1242, 1244, 1, 1, 30),
(1243, 1245, 1, 1, 30),
(1244, 1246, 1, 1, 30),
(1245, 1247, 1, 1, 30),
(1246, 1248, 1, 1, 30),
(1247, 1249, 1, 1, 30),
(1248, 1250, 1, 1, 30),
(1249, 1251, 1, 1, 30),
(1250, 1252, 1, 1, 30),
(1251, 1253, 1, 1, 30),
(1252, 1254, 1, 1, 30),
(1253, 1255, 1, 1, 30),
(1254, 1256, 1, 1, 30),
(1255, 1257, 1, 1, 30),
(1256, 1258, 1, 1, 30),
(1257, 1259, 1, 1, 30),
(1258, 1260, 1, 1, 30),
(1259, 1261, 1, 1, 30),
(1260, 1262, 1, 1, 30),
(1261, 1263, 1, 1, 30),
(1262, 1264, 1, 1, 30),
(1263, 1265, 1, 1, 30),
(1264, 1266, 1, 1, 30),
(1265, 1267, 1, 1, 30),
(1266, 1268, 1, 1, 30),
(1267, 1269, 1, 1, 30),
(1268, 1270, 1, 1, 30),
(1269, 1271, 1, 1, 30),
(1270, 1272, 1, 1, 30),
(1271, 1273, 1, 1, 30),
(1272, 1274, 1, 1, 30),
(1273, 1275, 1, 1, 30),
(1274, 1276, 1, 1, 30),
(1275, 1277, 1, 1, 30),
(1276, 1278, 1, 1, 30),
(1277, 1279, 1, 1, 30),
(1278, 1280, 1, 1, 30),
(1279, 1281, 1, 1, 30),
(1280, 1282, 1, 1, 30),
(1281, 1283, 1, 1, 30),
(1282, 1284, 1, 1, 30),
(1283, 1285, 1, 1, 30),
(1284, 1286, 1, 1, 30),
(1285, 1287, 1, 1, 30),
(1286, 1288, 1, 1, 30),
(1287, 1289, 1, 1, 30),
(1288, 1290, 1, 1, 30),
(1289, 1291, 1, 1, 30),
(1290, 1292, 1, 1, 30),
(1291, 1293, 1, 1, 30),
(1292, 1294, 1, 1, 30),
(1293, 1295, 1, 1, 30),
(1294, 1296, 1, 1, 30),
(1295, 1297, 1, 1, 30),
(1296, 1298, 1, 1, 30),
(1297, 1299, 1, 1, 30),
(1298, 1300, 1, 1, 30),
(1299, 1301, 1, 1, 30),
(1300, 1302, 1, 1, 30),
(1301, 1303, 1, 1, 30),
(1302, 1304, 1, 1, 30),
(1303, 1305, 1, 1, 30),
(1304, 1306, 1, 1, 30),
(1305, 1307, 1, 1, 30),
(1306, 1308, 1, 1, 30),
(1307, 1309, 1, 1, 30),
(1308, 1310, 1, 1, 30),
(1309, 1311, 1, 1, 30),
(1310, 1312, 1, 1, 30),
(1311, 1313, 1, 1, 30),
(1312, 1314, 1, 1, 30),
(1313, 1315, 1, 1, 30),
(1314, 1316, 1, 1, 30),
(1315, 1317, 1, 1, 30),
(1316, 1318, 1, 1, 30),
(1317, 1319, 1, 1, 30),
(1318, 1320, 1, 1, 30),
(1319, 1321, 1, 1, 30),
(1320, 1322, 1, 1, 30),
(1321, 1323, 1, 1, 30),
(1322, 1324, 1, 1, 30),
(1323, 1325, 1, 1, 30),
(1324, 1326, 1, 1, 30),
(1325, 1327, 1, 1, 30),
(1326, 1328, 1, 1, 30),
(1327, 1329, 1, 1, 30),
(1328, 1330, 1, 1, 30),
(1329, 1331, 1, 1, 30),
(1330, 1332, 1, 1, 30),
(1331, 1333, 1, 1, 30),
(1332, 1334, 1, 1, 30),
(1333, 1335, 1, 1, 30),
(1334, 1336, 1, 1, 30),
(1335, 1337, 1, 1, 30),
(1336, 1338, 1, 1, 30),
(1337, 1339, 1, 1, 30),
(1338, 1340, 1, 1, 30),
(1339, 1341, 1, 1, 30),
(1340, 1342, 1, 1, 30),
(1341, 1343, 1, 1, 30),
(1342, 1344, 1, 1, 30),
(1343, 1345, 1, 1, 30),
(1344, 1346, 1, 1, 30),
(1345, 1347, 1, 1, 30),
(1346, 1348, 1, 1, 30),
(1347, 1349, 1, 1, 30),
(1348, 1350, 1, 1, 30),
(1349, 1351, 1, 1, 30),
(1350, 1352, 1, 1, 30),
(1351, 1353, 1, 1, 30),
(1352, 1354, 1, 1, 30),
(1353, 1355, 1, 1, 30),
(1354, 1356, 1, 1, 30),
(1355, 1357, 1, 1, 30),
(1356, 1358, 1, 1, 30),
(1357, 1359, 1, 1, 30),
(1358, 1360, 1, 1, 30),
(1359, 1361, 1, 1, 30),
(1360, 1362, 1, 1, 30),
(1361, 1363, 1, 1, 30),
(1362, 1364, 1, 1, 30),
(1363, 1365, 1, 1, 30),
(1364, 1366, 1, 1, 30),
(1365, 1367, 1, 1, 30),
(1366, 1368, 1, 1, 30),
(1367, 1369, 1, 1, 30),
(1368, 1370, 1, 1, 30),
(1369, 1371, 1, 1, 30),
(1370, 1372, 1, 1, 30),
(1371, 1373, 1, 1, 30),
(1372, 1374, 1, 1, 30),
(1373, 1375, 1, 1, 30),
(1374, 1376, 1, 1, 30),
(1375, 1377, 1, 1, 30),
(1376, 1378, 1, 1, 30),
(1377, 1379, 1, 1, 30),
(1378, 1380, 1, 1, 30),
(1379, 1381, 1, 1, 30),
(1380, 1382, 1, 1, 30),
(1381, 1383, 1, 1, 30),
(1382, 1384, 1, 1, 30),
(1383, 1385, 1, 1, 30),
(1384, 1386, 1, 1, 30),
(1385, 1387, 1, 1, 30),
(1386, 1388, 1, 1, 30),
(1387, 1389, 1, 1, 30),
(1388, 1390, 1, 1, 30),
(1389, 1391, 1, 1, 30),
(1390, 1392, 1, 1, 30),
(1391, 1393, 1, 1, 30),
(1392, 1394, 1, 1, 30),
(1393, 1395, 1, 1, 30),
(1394, 1396, 1, 1, 30),
(1395, 1397, 1, 1, 30),
(1396, 1398, 1, 1, 30),
(1397, 1399, 1, 1, 30),
(1398, 1400, 1, 1, 30),
(1399, 1401, 1, 1, 30),
(1400, 1402, 1, 1, 30),
(1401, 1403, 1, 1, 30),
(1402, 1404, 1, 1, 30),
(1403, 1405, 1, 1, 30),
(1404, 1406, 1, 1, 30),
(1405, 1407, 1, 1, 30),
(1406, 1408, 1, 1, 30),
(1407, 1409, 1, 1, 30),
(1408, 1410, 1, 1, 30),
(1409, 1411, 1, 1, 30),
(1410, 1412, 1, 1, 30),
(1411, 1413, 1, 1, 30),
(1412, 1414, 1, 1, 30),
(1413, 1415, 1, 1, 30),
(1414, 1416, 1, 1, 30),
(1415, 1417, 1, 1, 30),
(1416, 1418, 1, 1, 30),
(1417, 1419, 1, 1, 30),
(1418, 1420, 1, 1, 30),
(1419, 1421, 1, 1, 30),
(1420, 1422, 1, 1, 30),
(1421, 1423, 1, 1, 30),
(1422, 1424, 1, 1, 30),
(1423, 1425, 1, 1, 30),
(1424, 1426, 1, 1, 30),
(1425, 1427, 1, 1, 30),
(1426, 1428, 1, 1, 30),
(1427, 1429, 1, 1, 30),
(1428, 1430, 1, 1, 30),
(1429, 1431, 1, 1, 30),
(1430, 1432, 1, 1, 30),
(1431, 1433, 1, 1, 30),
(1432, 1434, 1, 1, 30),
(1433, 1435, 1, 1, 30),
(1434, 1436, 1, 1, 30),
(1435, 1437, 1, 1, 30),
(1436, 1438, 1, 1, 30),
(1437, 1439, 1, 1, 30),
(1438, 1440, 1, 1, 30),
(1439, 1441, 1, 1, 30),
(1440, 1442, 1, 1, 30),
(1441, 1443, 1, 1, 30),
(1442, 1444, 1, 1, 30),
(1443, 1445, 1, 1, 30),
(1444, 1446, 1, 1, 30),
(1445, 1447, 1, 1, 30),
(1446, 1448, 1, 1, 30),
(1447, 1449, 1, 1, 30),
(1448, 1450, 1, 1, 30),
(1449, 1451, 1, 1, 30),
(1450, 1452, 1, 1, 30),
(1451, 1453, 1, 1, 30),
(1452, 1454, 1, 1, 30),
(1453, 1455, 1, 1, 30),
(1454, 1456, 1, 1, 30),
(1455, 1457, 1, 1, 30),
(1456, 1458, 1, 1, 30),
(1457, 1459, 1, 1, 30),
(1458, 1460, 1, 1, 30),
(1459, 1461, 1, 1, 30),
(1460, 1462, 1, 1, 30),
(1461, 1463, 1, 1, 30),
(1462, 1464, 1, 1, 30),
(1463, 1465, 1, 1, 30),
(1464, 1466, 1, 1, 30),
(1465, 1467, 1, 1, 30),
(1466, 1468, 1, 1, 30),
(1467, 1469, 1, 1, 30),
(1468, 1470, 1, 1, 30),
(1469, 1471, 1, 1, 30),
(1470, 1472, 1, 1, 30),
(1471, 1473, 1, 1, 30),
(1472, 1474, 1, 1, 30),
(1473, 1475, 1, 1, 30),
(1474, 1476, 1, 1, 30),
(1475, 1477, 1, 1, 30),
(1476, 1478, 1, 1, 30),
(1477, 1479, 1, 1, 30),
(1478, 1480, 1, 1, 30),
(1479, 1481, 1, 1, 30),
(1480, 1482, 1, 1, 30),
(1481, 1483, 1, 1, 30),
(1482, 1484, 1, 1, 30),
(1483, 1485, 1, 1, 30),
(1484, 1486, 1, 1, 30),
(1485, 1487, 1, 1, 30),
(1486, 1488, 1, 1, 30),
(1487, 1489, 1, 1, 30),
(1488, 1490, 1, 1, 30),
(1489, 1491, 1, 1, 30),
(1490, 1492, 1, 1, 30),
(1491, 1493, 1, 1, 30),
(1492, 1494, 1, 1, 30),
(1493, 1495, 1, 1, 30),
(1494, 1496, 1, 1, 30),
(1495, 1497, 1, 1, 30),
(1496, 1498, 1, 1, 30),
(1497, 1499, 1, 1, 30),
(1498, 1500, 1, 1, 30),
(1499, 1501, 1, 1, 30),
(1500, 1502, 1, 1, 30),
(1501, 1503, 1, 1, 30),
(1502, 1504, 1, 1, 30),
(1503, 1505, 1, 1, 30),
(1504, 1506, 1, 1, 30),
(1505, 1507, 1, 1, 30),
(1506, 1508, 1, 1, 30),
(1507, 1509, 1, 1, 30),
(1508, 1510, 1, 1, 30),
(1509, 1511, 1, 1, 30),
(1510, 1512, 1, 1, 30),
(1511, 1513, 1, 1, 30),
(1512, 1514, 1, 1, 30),
(1513, 1515, 1, 1, 30);

-- --------------------------------------------------------

--
-- Table structure for table `cal_calendar_share`
--

CREATE TABLE IF NOT EXISTS `cal_calendar_share` (
`id` bigint(20) unsigned NOT NULL,
  `cal_calendar_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `cal_calendar_share`
--

INSERT INTO `cal_calendar_share` (`id`, `cal_calendar_id`, `user_id`, `date_created`) VALUES
(4, 4, 303, '2013-09-20 22:44:25'),
(5, 2, 256, '2013-09-25 14:20:26'),
(6, 2, 256, '2013-09-25 20:18:41'),
(7, 2, 256, '2013-09-25 20:18:51'),
(8, 5, 276, '2013-09-26 21:06:24'),
(9, 5, 277, '2013-09-26 21:06:24'),
(10, 5, 279, '2013-09-26 21:06:24'),
(16, 367, 401, '2013-11-28 20:04:01'),
(17, 367, 402, '2013-11-28 20:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `cal_event`
--

CREATE TABLE IF NOT EXISTS `cal_event` (
`id` bigint(20) unsigned NOT NULL,
  `cal_calendar_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) NOT NULL,
  `cal_event_repeat_id` bigint(20) unsigned DEFAULT NULL,
  `cal_event_link_id` bigint(20) unsigned DEFAULT NULL,
  `date_from` datetime NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` mediumtext,
  `location` varchar(250) DEFAULT NULL,
  `date_to` datetime NOT NULL,
  `color` varchar(7) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- Dumping data for table `cal_event`
--

INSERT INTO `cal_event` (`id`, `cal_calendar_id`, `user_created_id`, `cal_event_repeat_id`, `cal_event_link_id`, `date_from`, `name`, `description`, `location`, `date_to`, `color`, `date_created`, `date_updated`) VALUES
(2, 5, 274, NULL, NULL, '2013-09-23 20:00:00', 'Requisitos ', 'Apresentação dos requisitos do TCC a professora orientadora (Kelly Rafaela)', 'SEPT - Biblioteca', '2013-09-23 21:00:00', '#FF85FF', '2013-09-23 01:39:35', NULL),
(3, 5, 274, NULL, NULL, '2013-09-20 21:00:00', 'Definição do Sprints 1 e 2', 'Sprint 1:\n\nCategoria 1  - Prioridade - Desenvolvimento \n1 - UF\n2 - Cidade\n3 - Concurso\n4 - Vaga\n5 - Prova\n6 - Dia da prova\n7 - Peso da prova\n\nCategoria 2 \n1 - Disciplina\n2 - Área\n3 - Assunto\n4 - Assunto requisito\n\nCategoria 3\n1 - Cadastro do usuário\n2 - Linguas \n3 - Cadastro do Aluno\n\nSprint 2 \nCategoria 1\n1 - Selecionar (instituição, concurso, prova)\n2 - Cadastro de questões ( V ou F, Somatório, Multipla escolha)\n3 - Associar assuntos das questões\n4 - Texto central\n5 - Cadastro da questão discursiva\n\nCategoria 2\n1 - Resolução de questões\n2 - Mapa de questões\n3 - Gabarito final da questão\n4 -- Marcador da questão', 'SEPT - Biblioteca', '2013-09-20 22:30:00', '#0AFFFF', '2013-09-23 01:53:29', NULL),
(4, 2, 255, NULL, NULL, '2013-09-25 21:00:00', 'Project planning - skype meeting', '', 'Skype', '2013-09-25 22:00:00', '#FF3E30', '2013-09-25 14:14:52', '2013-09-25 14:15:58'),
(5, 3, 145, NULL, NULL, '2013-09-17 00:00:00', 'dsadas', 'dsds', '', '2013-09-17 00:00:00', '#99FFCC', '2013-09-25 16:18:16', NULL),
(6, 7, 256, NULL, NULL, '2013-09-25 00:00:00', 'Project Started!!', '', '', '2013-09-25 00:00:00', '#99FFCC', '2013-09-25 20:35:59', NULL),
(7, 8, 164, NULL, NULL, '2013-09-23 00:00:00', 'SIT Sanity', 'Sanity Test done by MIMOS', 'PERKESO', '2013-09-23 00:00:00', '#99FFCC', '2013-09-26 15:05:49', NULL),
(9, 4, 3, NULL, NULL, '2013-10-07 00:00:00', 'a', '', '', '2013-10-07 00:00:00', '#99FFCC', '2013-10-07 23:19:50', NULL),
(10, 261, 323, NULL, NULL, '2013-10-01 00:00:00', 'SampleDay', 'Dayss', '', '2013-10-01 00:00:00', '#99FFCC', '2013-10-08 13:25:11', NULL),
(11, 262, 324, NULL, NULL, '2013-10-01 00:00:00', 'winston', 'test\n', '', '2013-10-01 00:00:00', '#99FFCC', '2013-10-08 13:41:52', NULL),
(12, 4, 3, NULL, NULL, '2013-10-07 00:00:00', 'qqqqqqqq', '', '', '2013-10-16 00:00:00', '#99FFCC', '2013-10-09 12:33:27', NULL),
(13, 5, 274, NULL, NULL, '2013-10-10 19:00:00', 'suporte ao desenvolvimento', 'Criação do BD (Marcos e Sawamur)', '', '2013-10-10 20:30:00', '#99FFCC', '2013-10-11 01:36:12', NULL),
(14, 5, 274, NULL, NULL, '2013-10-09 19:00:00', 'Desenvolvimento', 'Marcos e Sawamur', '', '2013-10-09 21:30:00', '#99FFCC', '2013-10-11 01:36:53', NULL),
(15, 5, 274, NULL, NULL, '2013-10-10 21:00:00', 'Inicio da documenttação padrão ', '', '', '2013-10-10 21:20:00', '#99FFCC', '2013-10-11 01:37:40', NULL),
(16, 263, 335, NULL, NULL, '2013-09-28 00:00:00', 'création du site', 'Hébergement sur OVH pro, robots.txt mis en place', '', '2013-09-28 00:00:00', '#99FFCC', '2013-10-14 15:54:29', NULL),
(17, 263, 335, NULL, NULL, '2013-10-13 00:00:00', 'création du site', '', '', '2013-10-13 00:00:00', '#99FFCC', '2013-10-14 15:55:31', NULL),
(18, 3, 145, NULL, NULL, '2013-09-29 00:00:00', '', '', '', '2013-09-29 00:00:00', '#80EEFF', '2013-10-16 10:51:30', NULL),
(22, 3, 145, NULL, NULL, '2013-10-07 00:00:00', 'dsa', '', '', '2013-10-07 00:00:00', '#99FFCC', '2013-10-19 22:51:33', NULL),
(23, 3, 145, NULL, NULL, '2013-10-08 00:00:00', 'dsadsa', '', '', '2013-10-08 00:00:00', '#99FFCC', '2013-10-19 22:51:37', NULL),
(24, 260, 145, NULL, NULL, '2013-10-07 00:00:00', 'dsadsa', '', '', '2013-10-07 00:00:00', '#99FFCC', '2013-10-19 22:53:32', NULL),
(25, 3, 145, NULL, NULL, '2013-10-09 00:00:00', 'Mother', 'I''m sick!', 'CIBG/CIRB', '2013-10-09 00:00:00', '#45A2FF', '2013-10-21 14:11:59', NULL),
(26, 9, 2, NULL, NULL, '2013-10-01 00:00:00', 'ddd', 'rewrewqerew', '', '2013-10-01 00:00:00', '#99FFCC', '2013-10-23 11:42:03', '2013-10-23 11:45:57'),
(27, 9, 2, NULL, NULL, '2013-10-01 00:00:00', 'ana', 'ana', '', '2013-10-02 07:25:00', '#FF8D36', '2013-10-23 11:46:40', '2013-10-23 11:49:22'),
(28, 8, 164, NULL, NULL, '2013-11-02 00:00:00', 'Deepavali', 'Holiday', '', '2013-11-02 00:00:00', '#DFFF0F', '2013-10-31 14:25:13', NULL),
(35, 270, 2, NULL, NULL, '2013-11-06 12:00:00', 'Minor Release 249', 'Weekly minor release for Ubirimi product suite\r\nbug fixing mostly and features', 'Timisoara', '2013-11-06 13:00:00', '#99FFFF', '2013-11-14 11:47:55', '2013-11-14 12:09:48'),
(36, 270, 2, NULL, NULL, '2013-11-13 12:00:00', 'Minor Release 250', 'Release 250 of Ubirimi', 'Timisoara', '2013-11-13 14:00:00', '#FF7E33', '2013-11-14 12:09:01', '2013-11-14 12:10:00'),
(37, 9, 2, NULL, NULL, '2013-11-08 00:00:00', 'Product Page refactoring - phase 1', '', '', '2013-11-15 00:00:00', '#FFABFC', '2013-11-14 12:10:49', NULL),
(38, 9, 2, NULL, NULL, '2013-11-14 00:00:00', 'Flavius Birthday', '', '', '2013-11-14 23:00:00', '#FFFF45', '2013-11-14 12:11:23', NULL),
(39, 9, 2, NULL, NULL, '2013-11-04 00:00:00', 'Sprint 21', 'Sprint 21 of Ubirimi development', '', '2013-11-15 00:00:00', '#99FFCC', '2013-11-14 12:12:37', NULL),
(40, 9, 2, NULL, NULL, '2013-11-07 00:00:00', 'Strategy Meeting', 'Stretegy meeting for Ubirimi Marketing', 'Timisoara', '2013-11-07 00:00:00', '#FFB0B0', '2013-11-14 12:14:20', NULL),
(43, 4, 3, NULL, NULL, '2013-11-20 00:00:00', 'flavius', '', '', '2013-11-20 00:00:00', '#A1FF9E', '2013-11-14 15:06:02', NULL),
(44, 4, 3, NULL, NULL, '2013-11-15 00:00:00', 'aaaaaaaaa', '', '', '2013-11-15 00:00:00', '#A1FF9E', '2013-11-14 15:17:33', NULL),
(46, 367, 400, NULL, NULL, '2014-01-31 00:00:00', 'Finalización de Análisis', 'Esta es la fecha tentativa para que tengamos finalizado el análisis sobre el primer release del proyecto', '', '2014-01-31 00:00:00', '#4576FF', '2013-11-28 21:44:53', NULL),
(47, 4, 3, NULL, NULL, '2014-02-03 00:00:00', 'a', 'a', '', '2014-02-20 00:00:00', '#A1FF9E', '2014-01-17 16:02:11', NULL),
(48, 362, 1892, NULL, NULL, '2013-12-29 00:00:00', '213', '', 'xx', '2013-12-29 00:00:00', '#A1FF9E', '2014-01-28 14:04:40', NULL),
(49, 362, 1892, NULL, NULL, '2013-12-29 00:00:00', '123', '12312', '3123123', '2013-12-29 00:00:00', '#A1FF9E', '2014-01-28 14:04:52', NULL),
(50, 362, 1892, NULL, NULL, '2013-07-30 00:00:00', 'dgfd', '', '', '2013-07-30 00:00:00', '#A1FF9E', '2014-01-30 22:15:23', NULL),
(51, 362, 1892, NULL, NULL, '2014-01-27 00:00:00', 'event', 'event', '', '2014-01-27 00:00:00', '#A1FF9E', '2014-02-01 14:52:51', NULL),
(52, 362, 1892, NULL, NULL, '2014-01-28 00:00:00', 'Event', 'Event', '', '2014-01-29 00:00:00', '#A1FF9E', '2014-02-01 14:53:05', NULL),
(54, 362, 1892, NULL, NULL, '2014-01-25 00:00:00', '<script>alert(3)</script>', '<script>alert(3)</script>', '<script>alert(3)</script>', '2014-01-25 00:00:00', '#A1FF9E', '2014-02-13 15:13:23', NULL),
(55, 362, 1892, NULL, NULL, '2014-01-25 00:00:00', '<script>alert(3)</script>', '<script>alert(3)</script>', '<script>alert(3)</script>', '2014-01-25 00:00:00', '#A1FF9E', '2014-02-13 15:13:30', NULL),
(56, 362, 1892, NULL, NULL, '2014-01-25 00:00:00', 'dsad', 'dsa', 'dsasd', '2014-01-25 00:00:00', '#A1FF9E', '2014-02-13 15:13:37', NULL),
(57, 362, 1892, NULL, NULL, '2014-01-26 00:00:00', 'vfvfdvdf', 'vvdfvdfvfd', '', '2014-01-26 00:00:00', '#A1FF9E', '2014-02-13 21:29:28', NULL),
(58, 498, 1892, NULL, NULL, '2014-01-25 00:00:00', 'vfdvdf', 'vdfvdf', 'vdfvfdv', '2014-01-25 00:00:00', '#7438FF', '2014-02-13 21:29:44', NULL),
(59, 362, 1892, NULL, NULL, '2014-01-28 00:00:00', 'some meeting', '', 'tm', '2014-01-29 00:00:00', '#A1FF9E', '2014-02-13 22:03:22', NULL),
(60, 9, 2, NULL, NULL, '2014-03-03 00:00:00', '', '', '', '2014-03-03 00:00:00', '#A1FF9E', '2014-03-04 21:56:08', NULL),
(61, 524, 2048, NULL, NULL, '2014-03-12 14:30:00', 'Test event', '', '', '2014-03-12 16:30:00', '#A1FF9E', '2014-03-11 15:46:14', NULL),
(63, 649, 2172, NULL, NULL, '2014-03-14 00:00:00', 'Created Project', '', 'Online', '2014-03-14 00:00:00', '#A1FF9E', '2014-03-15 01:21:08', NULL),
(64, 384, 1892, NULL, NULL, '2014-03-29 00:00:00', 'SD', 'ASD', 'ASD', '2014-03-29 00:00:00', '#0000FF', '2014-04-11 21:33:11', NULL),
(65, 513, 2037, NULL, NULL, '2014-04-14 09:00:00', 'Flyere', 'Sunat la pliante.com.ro', 'birou', '2014-04-01 00:00:00', '#A1FF9E', '2014-04-13 14:01:22', NULL),
(66, 708, 2227, NULL, NULL, '2014-05-15 00:00:00', 'erqwer', 'werqwer', '', '2014-05-15 00:00:00', '#A1FF9E', '2014-05-14 12:55:27', NULL),
(67, 9, 2, NULL, NULL, '2014-05-18 00:00:00', 'alert(''ana'')', '$(''body'').remove()', '', '2014-05-18 00:00:00', '#A1FF9E', '2014-05-18 22:29:19', '2014-05-18 22:30:01'),
(68, 9, 2, NULL, NULL, '2014-04-27 00:00:00', '$(''body'').remove()', '', '', '2014-04-27 00:00:00', '#A1FF9E', '2014-05-18 22:30:40', NULL),
(69, 9, 2, NULL, NULL, '2014-05-19 00:00:00', '$(''body'').remove()', '', '', '2014-05-19 00:00:00', '#A1FF9E', '2014-05-18 22:31:11', NULL),
(70, 9, 2, NULL, NULL, '2014-05-20 00:00:00', '$(''body'').remove()', '', '', '2014-05-20 00:00:00', '#A1FF9E', '2014-05-18 22:31:30', NULL),
(71, 9, 2, NULL, NULL, '2014-05-18 00:00:00', '$(''body'').remove()', '<script>$(''body'').remove()</script>', '<script>$(''body'').remove()</script>', '2014-05-18 00:00:00', '#A1FF9E', '2014-05-18 22:31:53', NULL),
(72, 720, 2235, NULL, NULL, '2014-06-02 00:00:00', 'UNIWAY', '', '', '2014-06-03 00:00:00', '#A1FF9E', '2014-05-30 14:19:21', NULL),
(74, 720, 2235, NULL, NULL, '2014-06-09 00:00:00', 'Uniway', '', '', '2014-06-10 00:00:00', '#A1FF9E', '2014-05-30 14:20:09', NULL),
(75, 720, 2235, NULL, NULL, '2014-06-16 00:00:00', 'UNIWAY', '', '', '2014-06-17 00:00:00', '#A1FF9E', '2014-05-30 14:20:19', NULL),
(76, 720, 2235, NULL, NULL, '2014-06-23 00:00:00', 'UNIWAY', '', '', '2014-06-24 00:00:00', '#A1FF9E', '2014-05-30 14:20:29', NULL),
(77, 720, 2235, NULL, NULL, '2014-06-30 00:00:00', 'UNIWAY', '', '', '2014-06-30 00:00:00', '#A1FF9E', '2014-05-30 14:20:38', NULL),
(78, 362, 1892, NULL, NULL, '2014-06-19 00:00:00', 'stg', '', '', '2014-06-19 00:00:00', '#A1FF9E', '2014-06-26 08:38:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_reminder`
--

CREATE TABLE IF NOT EXISTS `cal_event_reminder` (
`id` bigint(20) unsigned NOT NULL,
  `cal_event_id` bigint(20) unsigned NOT NULL,
  `cal_event_reminder_type_id` bigint(20) unsigned NOT NULL,
  `cal_event_reminder_period_id` bigint(20) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `fired_flag` tinyint(3) unsigned DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Dumping data for table `cal_event_reminder`
--

INSERT INTO `cal_event_reminder` (`id`, `cal_event_id`, `cal_event_reminder_type_id`, `cal_event_reminder_period_id`, `value`, `fired_flag`) VALUES
(10, 35, 1, 1, 30, 1),
(11, 36, 1, 1, 30, 1),
(12, 37, 1, 1, 30, 1),
(13, 38, 1, 1, 30, 1),
(14, 39, 1, 1, 30, 1),
(15, 40, 1, 1, 30, 1),
(18, 43, 1, 1, 30, 1),
(19, 44, 1, 1, 30, 1),
(21, 46, 1, 1, 30, 1),
(22, 47, 1, 1, 30, 1),
(23, 48, 1, 1, 30, 1),
(24, 49, 1, 1, 30, 1),
(25, 50, 1, 1, 30, 1),
(26, 51, 1, 1, 30, 1),
(27, 52, 1, 1, 30, 1),
(29, 54, 1, 1, 30, NULL),
(30, 55, 1, 1, 30, NULL),
(31, 56, 1, 1, 30, NULL),
(32, 57, 1, 1, 30, NULL),
(33, 58, 1, 1, 30, NULL),
(34, 59, 1, 1, 30, NULL),
(35, 60, 1, 1, 30, 1),
(36, 61, 1, 1, 30, 1),
(38, 63, 1, 1, 30, 1),
(39, 64, 1, 1, 30, NULL),
(40, 65, 1, 1, 30, 1),
(41, 66, 1, 1, 30, 1),
(43, 67, 1, 1, 30, 1),
(44, 68, 1, 1, 30, 1),
(45, 69, 1, 1, 30, 1),
(46, 70, 1, 1, 30, 1),
(47, 71, 1, 1, 30, 1),
(48, 72, 1, 1, 30, 1),
(50, 74, 1, 1, 30, 1),
(51, 75, 1, 1, 30, 1),
(52, 76, 1, 1, 30, 1),
(53, 77, 1, 1, 30, NULL),
(54, 78, 1, 1, 30, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_reminder_period`
--

CREATE TABLE IF NOT EXISTS `cal_event_reminder_period` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_reminder_type`
--

CREATE TABLE IF NOT EXISTS `cal_event_reminder_type` (
`id` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_repeat`
--

CREATE TABLE IF NOT EXISTS `cal_event_repeat` (
`id` bigint(20) unsigned NOT NULL,
  `cal_event_repeat_cycle_id` bigint(20) unsigned NOT NULL,
  `repeat_every` int(10) unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_repeat_cycle`
--

CREATE TABLE IF NOT EXISTS `cal_event_repeat_cycle` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cal_event_repeat_cycle`
--

INSERT INTO `cal_event_repeat_cycle` (`id`, `name`) VALUES
(1, 'daily');

-- --------------------------------------------------------

--
-- Table structure for table `cal_event_share`
--

CREATE TABLE IF NOT EXISTS `cal_event_share` (
`id` bigint(20) unsigned NOT NULL,
  `cal_event_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cal_event_share`
--

INSERT INTO `cal_event_share` (`id`, `cal_event_id`, `user_id`, `date_created`) VALUES
(3, 2, 276, '2013-09-23 02:52:11'),
(4, 4, 256, '2013-09-25 14:15:26');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE IF NOT EXISTS `client` (
`id` bigint(20) unsigned NOT NULL,
  `sys_country_id` bigint(20) unsigned DEFAULT NULL,
  `company_name` varchar(200) NOT NULL,
  `company_domain` varchar(50) DEFAULT NULL,
  `base_url` varchar(250) NOT NULL,
  `address_1` varchar(250) DEFAULT NULL,
  `address_2` varchar(250) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `district` varchar(250) DEFAULT NULL,
  `contact_email` varchar(200) NOT NULL,
  `installed_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `instance_type` tinyint(3) unsigned NOT NULL COMMENT '1 - on demand; 2 - download',
  `timezone` varchar(100) NOT NULL,
  `language` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1960 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `sys_country_id`, `company_name`, `company_domain`, `base_url`, `address_1`, `address_2`, `city`, `district`, `contact_email`, `installed_flag`, `instance_type`, `timezone`, `language`, `date_created`) VALUES
(1959, NULL, 'Movidius', 'movidius', 'http://movidius.ubirimi_net.lan', NULL, NULL, NULL, NULL, 'contact@movidius.ro', 1, 1, '', '', '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `client_documentator_settings`
--

CREATE TABLE IF NOT EXISTS `client_documentator_settings` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `anonymous_use_flag` tinyint(4) unsigned NOT NULL,
  `anonymous_view_user_profile_flag` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1945 ;

--
-- Dumping data for table `client_documentator_settings`
--

INSERT INTO `client_documentator_settings` (`id`, `client_id`, `anonymous_use_flag`, `anonymous_view_user_profile_flag`) VALUES
(179, 0, 0, 50),
(1925, 1940, 0, 50),
(1927, 1942, 0, 50),
(1929, 1944, 0, 50),
(1944, 1959, 0, 50);

-- --------------------------------------------------------

--
-- Table structure for table `client_product`
--

CREATE TABLE IF NOT EXISTS `client_product` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sys_product_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3488 ;

--
-- Dumping data for table `client_product`
--

INSERT INTO `client_product` (`id`, `client_id`, `sys_product_id`, `date_created`) VALUES
(3383, 1940, 1, '2014-05-08 19:17:02'),
(3384, 1940, 3, '2014-05-08 19:17:02'),
(3385, 1940, 2, '2014-05-08 19:17:02'),
(3386, 1940, 4, '2014-05-08 19:17:02'),
(3387, 1940, 5, '2014-05-08 19:17:02'),
(3393, 1942, 1, '2014-05-08 19:17:02'),
(3394, 1942, 3, '2014-05-08 19:17:02'),
(3395, 1942, 2, '2014-05-08 19:17:02'),
(3396, 1942, 4, '2014-05-08 19:17:02'),
(3397, 1942, 5, '2014-05-08 19:17:02'),
(3403, 1944, 1, '2014-05-08 19:17:02'),
(3404, 1944, 3, '2014-05-08 19:17:02'),
(3405, 1944, 2, '2014-05-08 19:17:02'),
(3406, 1944, 4, '2014-05-08 19:17:02'),
(3407, 1944, 5, '2014-05-08 19:17:02'),
(3483, 1959, 1, '2014-06-26 14:00:20'),
(3484, 1959, 3, '2014-06-26 14:00:20'),
(3485, 1959, 2, '2014-06-26 14:00:20'),
(3486, 1959, 4, '2014-06-26 14:00:20'),
(3487, 1959, 5, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `client_settings`
--

CREATE TABLE IF NOT EXISTS `client_settings` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `title_name` varchar(250) DEFAULT NULL,
  `operating_mode` varchar(7) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1944 ;

--
-- Dumping data for table `client_settings`
--

INSERT INTO `client_settings` (`id`, `client_id`, `title_name`, `operating_mode`, `timezone`, `language`) VALUES
(1924, 1940, 'Ubirimi.com', 'public', 'Europe/London', 'english'),
(1926, 1942, 'Ubirimi.com', 'public', 'Europe/London', 'english'),
(1928, 1944, 'Ubirimi.com', 'public', 'Europe/London', 'english'),
(1943, 1959, 'Ubirimi.com', 'public', 'Europe/London', 'english');

-- --------------------------------------------------------

--
-- Table structure for table `client_smtp_settings`
--

CREATE TABLE IF NOT EXISTS `client_smtp_settings` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `default_ubirimi_server_flag` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `from_address` varchar(50) NOT NULL,
  `email_prefix` varchar(50) NOT NULL,
  `smtp_protocol` tinyint(3) unsigned NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `port` int(11) NOT NULL,
  `timeout` int(10) unsigned NOT NULL,
  `tls_flag` tinyint(4) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=478 ;

--
-- Dumping data for table `client_smtp_settings`
--

INSERT INTO `client_smtp_settings` (`id`, `client_id`, `default_ubirimi_server_flag`, `name`, `description`, `from_address`, `email_prefix`, `smtp_protocol`, `hostname`, `port`, `timeout`, `tls_flag`, `username`, `password`, `date_created`, `date_updated`) VALUES
(180, 0, 1, 'Ubirimi Mail Server', 'The default Ubirimi mail server', 'notification@ubirimi.com', 'UBR', 2, 'smtp.gmail.com', 587, 10000, 1, 'notification@ubirimi.com', 'cristinasinaomi1', '0000-00-00 00:00:00', NULL),
(458, 1940, 1, 'Ubirimi Mail Server', 'The default Ubirimi mail server', 'notification@ubirimi.com', 'UBR', 2, 'smtp.gmail.com', 587, 10000, 1, 'notification@ubirimi.com', 'cristinasinaomi1', '2014-05-08 19:17:02', NULL),
(460, 1942, 1, 'Ubirimi Mail Server', 'The default Ubirimi mail server', 'notification@ubirimi.com', 'UBR', 2, 'smtp.gmail.com', 587, 10000, 1, 'notification@ubirimi.com', 'cristinasinaomi1', '2014-05-08 19:17:02', NULL),
(462, 1944, 1, 'Ubirimi Mail Server', 'The default Ubirimi mail server', 'notification@ubirimi.com', 'UBR', 2, 'smtp.gmail.com', 587, 10000, 1, 'notification@ubirimi.com', 'cristinasinaomi1', '2014-05-08 19:17:02', NULL),
(477, 1959, 1, 'Ubirimi Mail Server', 'The default Ubirimi mail server', 'notification@ubirimi.com', 'UBR', 2, 'smtp.gmail.com', 587, 10000, 1, 'notification@ubirimi.com', 'cristinasinaomi1', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `client_yongo_settings`
--

CREATE TABLE IF NOT EXISTS `client_yongo_settings` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `allow_unassigned_issues_flag` tinyint(3) unsigned DEFAULT NULL,
  `issues_per_page` int(10) unsigned NOT NULL,
  `allow_attachments_flag` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `notify_own_changes_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `issue_linking_flag` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `time_tracking_flag` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `time_tracking_hours_per_day` float unsigned NOT NULL,
  `time_tracking_days_per_week` int(10) unsigned NOT NULL,
  `time_tracking_default_unit` varchar(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1960 ;

--
-- Dumping data for table `client_yongo_settings`
--

INSERT INTO `client_yongo_settings` (`id`, `client_id`, `allow_unassigned_issues_flag`, `issues_per_page`, `allow_attachments_flag`, `notify_own_changes_flag`, `issue_linking_flag`, `time_tracking_flag`, `time_tracking_hours_per_day`, `time_tracking_days_per_week`, `time_tracking_default_unit`) VALUES
(194, 0, 0, 50, 1, 0, 1, 1, 8, 5, 'm'),
(1940, 1940, 0, 50, 1, 0, 1, 1, 8, 5, 'm'),
(1942, 1942, 0, 50, 1, 0, 1, 1, 8, 5, 'm'),
(1944, 1944, 0, 50, 1, 0, 1, 1, 8, 5, 'm'),
(1959, 1959, 1, 50, 1, 0, 1, 1, 8, 5, 'm');

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity`
--

CREATE TABLE IF NOT EXISTS `documentator_entity` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_type_id` bigint(20) unsigned NOT NULL,
  `documentator_space_id` bigint(20) unsigned NOT NULL,
  `parent_entity_id` bigint(20) unsigned DEFAULT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `content` longtext,
  `in_trash_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=206 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_attachment`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_attachment` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_id` bigint(20) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_attachment_revision`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_attachment_revision` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_attachment_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_comment`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_comment` (
`id` int(10) unsigned NOT NULL,
  `documentator_entity_id` bigint(10) unsigned NOT NULL,
  `parent_comment_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `content` mediumtext NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_file`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_file` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_id` bigint(20) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=112 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_file_revision`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_file_revision` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_file_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=116 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_revision`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_revision` (
`id` bigint(20) unsigned NOT NULL,
  `entity_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=149 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_snapshot`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_snapshot` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_entity_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` longtext NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4273 ;

--
-- Dumping data for table `documentator_entity_snapshot`
--

INSERT INTO `documentator_entity_snapshot` (`id`, `documentator_entity_id`, `user_id`, `content`, `date_created`) VALUES
(599, 55, 136, '<p><span style="font-size:24px"><strong>Welcome to your new space!</strong></span></p><div class="message-content" style="font-family: Arial, sans-serif; font-size: 14px;"><p>Documentador spaces are great for sharing content and news with your team. This is your home page. You can customize this page in anyway you like.<br />Oke Dit is ff kijken of het ook goed werkt!!</p><p><img alt="" src="/assets/documentador/attachment/19/55/13/1/Schermafbeelding 2013-08-13 om 13.31.36.png" style="height:698px; width:523px" /></p><p>&nbsp;</p><p>databaseManager.saveData()</p><p>&nbsp;</p><p>&nbsp;</p></div>', '2013-09-08 16:47:31'),
(877, 118, 369, '<p>101 : New Team<br />102 : Team Closed Value<br />201 : New Team Trainer<br />202 : New Team Orga<br />203 : New Team Player<br />204 : New Team Parent<br />205 : New Team Fan<br />211 : Team Trainer Removed<br />212 : Team Orga Removed<br />213 : Team Player Removed<br />214 : Team Parent Removed<br />215 : Team Fan Removed<br />301 : New Game<br />302 : Game Changed<br />303 : Game Cancelled<br />311 : Game 1st Half Started<br />312 : Game 1st Half Ended<br />313 : Game 2nd Half Started<br />314 : Game 2nd Half Ended<br />315 : Game Goal Home<br />316 : Game Goal Guest<br />317 : Game&nbsp;Player Out<br />318 : Game&nbsp;Player In<br />319 : Game&nbsp;Yellow Card<br />320 : Game&nbsp;Red Card</p>', '2013-11-11 20:07:08'),
(978, 138, 369, '<p>&nbsp;</p><p>&nbsp;</p><p>https://www.lucidchart.com/documents/edit/4c4f-66d4-52c83707-8633-15cd0a00d707?</p>', '2014-01-04 20:17:27'),
(4220, 165, 2162, '<p><span style="font-size:24px"><strong>Welcome to the documentation for Badhabit Gaming - Altis Life</strong></span></p><div class="message-content" style="font-family: Arial, sans-serif; font-size: 14px;"><p>Documentation is for development and administrative use only.&nbsp;If there is any aditional information that is required on the site, please get in contact with GrumpyBear: ben@grumpywow.com</p><p>&nbsp;</p></div><p>&nbsp;</p>', '2014-03-20 03:54:06'),
(4222, 112, 2, '<p><span style="font-size:24px"><strong>Welcome to your new space!</strong></span></p><div class="message-content" style="font-family: Arial, sans-serif; font-size: 14px;"><p>Documentador spaces are great for sharing content and news with your team. This is your home page. You can customize this page in anyway you like.</p><p>I</p></div>', '2014-03-27 19:16:03');

-- --------------------------------------------------------

--
-- Table structure for table `documentator_entity_type`
--

CREATE TABLE IF NOT EXISTS `documentator_entity_type` (
`id` bigint(20) unsigned NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(250) NOT NULL,
  `description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `documentator_entity_type`
--

INSERT INTO `documentator_entity_type` (`id`, `code`, `name`, `description`) VALUES
(1, 'blank_page', 'Blank Page', 'Start with a blank page.'),
(2, 'file_list', 'File List', 'Upload, preview and share files with your tem.');

-- --------------------------------------------------------

--
-- Table structure for table `documentator_space`
--

CREATE TABLE IF NOT EXISTS `documentator_space` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `home_entity_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `code` varchar(6) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_space_permission`
--

CREATE TABLE IF NOT EXISTS `documentator_space_permission` (
`id` bigint(20) unsigned NOT NULL,
  `space_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `all_view_flag` tinyint(3) unsigned NOT NULL,
  `space_admin_flag` tinyint(3) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=250 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_space_permission_anonymous`
--

CREATE TABLE IF NOT EXISTS `documentator_space_permission_anonymous` (
`id` bigint(20) unsigned NOT NULL,
  `documentator_space_id` bigint(20) unsigned NOT NULL,
  `all_view_flag` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `documentator_space_permission_anonymous`
--

INSERT INTO `documentator_space_permission_anonymous` (`id`, `documentator_space_id`, `all_view_flag`) VALUES
(1, 45, 1),
(2, 50, 1),
(3, 55, 0),
(4, 69, 0),
(5, 4, 0),
(6, 88, 0);

-- --------------------------------------------------------

--
-- Table structure for table `documentator_user_entity_favourite`
--

CREATE TABLE IF NOT EXISTS `documentator_user_entity_favourite` (
`id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `entity_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `documentator_user_space_favourite`
--

CREATE TABLE IF NOT EXISTS `documentator_user_space_favourite` (
`id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `space_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `documentator_user_space_favourite`
--

INSERT INTO `documentator_user_space_favourite` (`id`, `user_id`, `space_id`, `date_created`) VALUES
(1, 164, 4, '2013-06-14 12:29:45'),
(2, 3, 5, '2013-08-13 15:22:40'),
(3, 2, 50, '2013-11-12 16:55:22'),
(5, 1892, 52, '2014-01-29 18:56:41'),
(6, 329, 88, '2014-04-25 08:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` tinyint(3) unsigned NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `system_flag` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23511 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `client_id`, `name`, `code`, `description`, `system_flag`, `date_created`, `date_updated`) VALUES
(2319, 0, 'Issue Created', 1, 'This is the ''issue created'' event.', 1, '0000-00-00 00:00:00', NULL),
(2320, 0, 'Issue Updated', 2, 'This is the ''issue updated'' event.', 1, '0000-00-00 00:00:00', NULL),
(2321, 0, 'Issue Assigned', 3, 'This is the ''issue assigned'' event.', 1, '0000-00-00 00:00:00', NULL),
(2322, 0, 'Issue Resolved', 4, 'This is the ''issue resolved'' event.', 1, '0000-00-00 00:00:00', NULL),
(2323, 0, 'Issue Closed', 5, 'This is the ''issue closed'' event.', 1, '0000-00-00 00:00:00', NULL),
(2324, 0, 'Issue Commented', 6, 'This is the ''issue commented'' event.', 1, '0000-00-00 00:00:00', NULL),
(2325, 0, 'Issue Comment Edited', 7, 'This is the ''issue comment edited'' event.', 1, '0000-00-00 00:00:00', NULL),
(2326, 0, 'Issue Reopened', 8, 'This is the ''issue reopened'' event.', 1, '0000-00-00 00:00:00', NULL),
(2327, 0, 'Issue Deleted', 9, 'This is the ''issue deleted'' event.', 1, '0000-00-00 00:00:00', NULL),
(2328, 0, 'Work Started on Issue', 10, 'This is the ''work started on issue'' event.', 1, '0000-00-00 00:00:00', NULL),
(2329, 0, 'Work Stopped on Issue', 11, 'This is the ''work stopped on issue'' event.', 1, '0000-00-00 00:00:00', NULL),
(2330, 0, 'Generic Event', 12, 'This is the ''generic event'' event.', 1, '0000-00-00 00:00:00', NULL),
(23271, 1940, 'Issue Created', 1, 'This is the ''issue created'' event.', 1, '2014-05-08 19:17:02', NULL),
(23272, 1940, 'Issue Updated', 2, 'This is the ''issue updated'' event.', 1, '2014-05-08 19:17:02', NULL),
(23273, 1940, 'Issue Assigned', 3, 'This is the ''issue assigned'' event.', 1, '2014-05-08 19:17:02', NULL),
(23274, 1940, 'Issue Resolved', 4, 'This is the ''issue resolved'' event.', 1, '2014-05-08 19:17:02', NULL),
(23275, 1940, 'Issue Closed', 5, 'This is the ''issue closed'' event.', 1, '2014-05-08 19:17:02', NULL),
(23276, 1940, 'Issue Commented', 6, 'This is the ''issue commented'' event.', 1, '2014-05-08 19:17:02', NULL),
(23277, 1940, 'Issue Comment Edited', 7, 'This is the ''issue comment edited'' event.', 1, '2014-05-08 19:17:02', NULL),
(23278, 1940, 'Issue Reopened', 8, 'This is the ''issue reopened'' event.', 1, '2014-05-08 19:17:02', NULL),
(23279, 1940, 'Issue Deleted', 9, 'This is the ''issue deleted'' event.', 1, '2014-05-08 19:17:02', NULL),
(23280, 1940, 'Work Started on Issue', 10, 'This is the ''work started on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23281, 1940, 'Work Stopped on Issue', 11, 'This is the ''work stopped on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23282, 1940, 'Generic Event', 12, 'This is the ''generic event'' event.', 1, '2014-05-08 19:17:02', NULL),
(23295, 1942, 'Issue Created', 1, 'This is the ''issue created'' event.', 1, '2014-05-08 19:17:02', NULL),
(23296, 1942, 'Issue Updated', 2, 'This is the ''issue updated'' event.', 1, '2014-05-08 19:17:02', NULL),
(23297, 1942, 'Issue Assigned', 3, 'This is the ''issue assigned'' event.', 1, '2014-05-08 19:17:02', NULL),
(23298, 1942, 'Issue Resolved', 4, 'This is the ''issue resolved'' event.', 1, '2014-05-08 19:17:02', NULL),
(23299, 1942, 'Issue Closed', 5, 'This is the ''issue closed'' event.', 1, '2014-05-08 19:17:02', NULL),
(23300, 1942, 'Issue Commented', 6, 'This is the ''issue commented'' event.', 1, '2014-05-08 19:17:02', NULL),
(23301, 1942, 'Issue Comment Edited', 7, 'This is the ''issue comment edited'' event.', 1, '2014-05-08 19:17:02', NULL),
(23302, 1942, 'Issue Reopened', 8, 'This is the ''issue reopened'' event.', 1, '2014-05-08 19:17:02', NULL),
(23303, 1942, 'Issue Deleted', 9, 'This is the ''issue deleted'' event.', 1, '2014-05-08 19:17:02', NULL),
(23304, 1942, 'Work Started on Issue', 10, 'This is the ''work started on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23305, 1942, 'Work Stopped on Issue', 11, 'This is the ''work stopped on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23306, 1942, 'Generic Event', 12, 'This is the ''generic event'' event.', 1, '2014-05-08 19:17:02', NULL),
(23319, 1944, 'Issue Created', 1, 'This is the ''issue created'' event.', 1, '2014-05-08 19:17:02', NULL),
(23320, 1944, 'Issue Updated', 2, 'This is the ''issue updated'' event.', 1, '2014-05-08 19:17:02', NULL),
(23321, 1944, 'Issue Assigned', 3, 'This is the ''issue assigned'' event.', 1, '2014-05-08 19:17:02', NULL),
(23322, 1944, 'Issue Resolved', 4, 'This is the ''issue resolved'' event.', 1, '2014-05-08 19:17:02', NULL),
(23323, 1944, 'Issue Closed', 5, 'This is the ''issue closed'' event.', 1, '2014-05-08 19:17:02', NULL),
(23324, 1944, 'Issue Commented', 6, 'This is the ''issue commented'' event.', 1, '2014-05-08 19:17:02', NULL),
(23325, 1944, 'Issue Comment Edited', 7, 'This is the ''issue comment edited'' event.', 1, '2014-05-08 19:17:02', NULL),
(23326, 1944, 'Issue Reopened', 8, 'This is the ''issue reopened'' event.', 1, '2014-05-08 19:17:02', NULL),
(23327, 1944, 'Issue Deleted', 9, 'This is the ''issue deleted'' event.', 1, '2014-05-08 19:17:02', NULL),
(23328, 1944, 'Work Started on Issue', 10, 'This is the ''work started on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23329, 1944, 'Work Stopped on Issue', 11, 'This is the ''work stopped on issue'' event.', 1, '2014-05-08 19:17:02', NULL),
(23330, 1944, 'Generic Event', 12, 'This is the ''generic event'' event.', 1, '2014-05-08 19:17:02', NULL),
(23499, 1959, 'Issue Created', 1, 'This is the ''issue created'' event.', 1, '2014-06-26 14:00:20', NULL),
(23500, 1959, 'Issue Updated', 2, 'This is the ''issue updated'' event.', 1, '2014-06-26 14:00:20', NULL),
(23501, 1959, 'Issue Assigned', 3, 'This is the ''issue assigned'' event.', 1, '2014-06-26 14:00:20', NULL),
(23502, 1959, 'Issue Resolved', 4, 'This is the ''issue resolved'' event.', 1, '2014-06-26 14:00:20', NULL),
(23503, 1959, 'Issue Closed', 5, 'This is the ''issue closed'' event.', 1, '2014-06-26 14:00:20', NULL),
(23504, 1959, 'Issue Commented', 6, 'This is the ''issue commented'' event.', 1, '2014-06-26 14:00:20', NULL),
(23505, 1959, 'Issue Comment Edited', 7, 'This is the ''issue comment edited'' event.', 1, '2014-06-26 14:00:20', NULL),
(23506, 1959, 'Issue Reopened', 8, 'This is the ''issue reopened'' event.', 1, '2014-06-26 14:00:20', NULL),
(23507, 1959, 'Issue Deleted', 9, 'This is the ''issue deleted'' event.', 1, '2014-06-26 14:00:20', NULL),
(23508, 1959, 'Work Started on Issue', 10, 'This is the ''work started on issue'' event.', 1, '2014-06-26 14:00:20', NULL),
(23509, 1959, 'Work Stopped on Issue', 11, 'This is the ''work stopped on issue'' event.', 1, '2014-06-26 14:00:20', NULL),
(23510, 1959, 'Generic Event', 12, 'This is the ''generic event'' event.', 1, '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field`
--

CREATE TABLE IF NOT EXISTS `field` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sys_field_type_id` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `system_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `all_issue_type_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `all_project_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29403 ;

--
-- Dumping data for table `field`
--

INSERT INTO `field` (`id`, `client_id`, `sys_field_type_id`, `code`, `name`, `description`, `system_flag`, `all_issue_type_flag`, `all_project_flag`, `date_created`, `date_updated`) VALUES
(2908, 0, NULL, 'resolution', 'Resolution', 'Resolution', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2909, 0, NULL, 'comment', 'Comment', 'Comment', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2910, 0, NULL, 'summary', 'Summary', 'Summary', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2911, 0, NULL, 'type', 'Issue Type', 'Issue Type', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2912, 0, NULL, 'affects_version', 'Affects version/s', 'Affects version/s', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2913, 0, NULL, 'assignee', 'Assignee', 'Assignee', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2914, 0, NULL, 'component', 'Component/s', 'Component/s', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2915, 0, NULL, 'description', 'Description', 'Description', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2916, 0, NULL, 'due_date', 'Due Date', 'Due Date', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2917, 0, NULL, 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2918, 0, NULL, 'priority', 'Priority', 'Priority', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2919, 0, NULL, 'attachment', 'Attachment', 'Attachment', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2920, 0, NULL, 'environment', 'Environment', 'Environment', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2921, 0, NULL, 'reporter', 'Reporter', 'Reporter', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(2922, 0, NULL, 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is '' *w *d *h *m '' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, 0, 0, '0000-00-00 00:00:00', NULL),
(29102, 1940, NULL, 'resolution', 'Resolution', 'Resolution', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29103, 1940, NULL, 'comment', 'Comment', 'Comment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29104, 1940, NULL, 'summary', 'Summary', 'Summary', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29105, 1940, NULL, 'type', 'Issue Type', 'Issue Type', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29106, 1940, NULL, 'affects_version', 'Affects version/s', 'Affects version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29107, 1940, NULL, 'assignee', 'Assignee', 'Assignee', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29108, 1940, NULL, 'component', 'Component/s', 'Component/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29109, 1940, NULL, 'description', 'Description', 'Description', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29110, 1940, NULL, 'due_date', 'Due Date', 'Due Date', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29111, 1940, NULL, 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29112, 1940, NULL, 'priority', 'Priority', 'Priority', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29113, 1940, NULL, 'attachment', 'Attachment', 'Attachment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29114, 1940, NULL, 'environment', 'Environment', 'Environment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29115, 1940, NULL, 'reporter', 'Reporter', 'Reporter', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29116, 1940, NULL, 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is '' *w *d *h *m '' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29132, 1942, NULL, 'resolution', 'Resolution', 'Resolution', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29133, 1942, NULL, 'comment', 'Comment', 'Comment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29134, 1942, NULL, 'summary', 'Summary', 'Summary', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29135, 1942, NULL, 'type', 'Issue Type', 'Issue Type', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29136, 1942, NULL, 'affects_version', 'Affects version/s', 'Affects version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29137, 1942, NULL, 'assignee', 'Assignee', 'Assignee', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29138, 1942, NULL, 'component', 'Component/s', 'Component/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29139, 1942, NULL, 'description', 'Description', 'Description', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29140, 1942, NULL, 'due_date', 'Due Date', 'Due Date', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29141, 1942, NULL, 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29142, 1942, NULL, 'priority', 'Priority', 'Priority', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29143, 1942, NULL, 'attachment', 'Attachment', 'Attachment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29144, 1942, NULL, 'environment', 'Environment', 'Environment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29145, 1942, NULL, 'reporter', 'Reporter', 'Reporter', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29146, 1942, NULL, 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is '' *w *d *h *m '' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29162, 1944, NULL, 'resolution', 'Resolution', 'Resolution', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29163, 1944, NULL, 'comment', 'Comment', 'Comment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29164, 1944, NULL, 'summary', 'Summary', 'Summary', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29165, 1944, NULL, 'type', 'Issue Type', 'Issue Type', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29166, 1944, NULL, 'affects_version', 'Affects version/s', 'Affects version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29167, 1944, NULL, 'assignee', 'Assignee', 'Assignee', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29168, 1944, NULL, 'component', 'Component/s', 'Component/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29169, 1944, NULL, 'description', 'Description', 'Description', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29170, 1944, NULL, 'due_date', 'Due Date', 'Due Date', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29171, 1944, NULL, 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29172, 1944, NULL, 'priority', 'Priority', 'Priority', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29173, 1944, NULL, 'attachment', 'Attachment', 'Attachment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29174, 1944, NULL, 'environment', 'Environment', 'Environment', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29175, 1944, NULL, 'reporter', 'Reporter', 'Reporter', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29176, 1944, NULL, 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is '' *w *d *h *m '' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, 0, 0, '2014-05-08 19:17:02', NULL),
(29388, 1959, NULL, 'resolution', 'Resolution', 'Resolution', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29389, 1959, NULL, 'comment', 'Comment', 'Comment', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29390, 1959, NULL, 'summary', 'Summary', 'Summary', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29391, 1959, NULL, 'type', 'Issue Type', 'Issue Type', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29392, 1959, NULL, 'affects_version', 'Affects version/s', 'Affects version/s', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29393, 1959, NULL, 'assignee', 'Assignee', 'Assignee', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29394, 1959, NULL, 'component', 'Component/s', 'Component/s', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29395, 1959, NULL, 'description', 'Description', 'Description', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29396, 1959, NULL, 'due_date', 'Due Date', 'Due Date', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29397, 1959, NULL, 'fix_version', 'Fix Version/s', 'Fix Version/s', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29398, 1959, NULL, 'priority', 'Priority', 'Priority', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29399, 1959, NULL, 'attachment', 'Attachment', 'Attachment', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29400, 1959, NULL, 'environment', 'Environment', 'Environment', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29401, 1959, NULL, 'reporter', 'Reporter', 'Reporter', 1, 0, 0, '2014-06-26 14:00:20', NULL),
(29402, 1959, NULL, 'time_tracking', 'Time Tracking', 'An estimate of how much work remains until this issue will be resolved. The format of this is '' *w *d *h *m '' (representing weeks, days, hours and minutes - where * can be any number) Examples: 4d, 5h 30m, 60m and 3w.', 1, 0, 0, '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field_configuration`
--

CREATE TABLE IF NOT EXISTS `field_configuration` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1961 ;

--
-- Dumping data for table `field_configuration`
--

INSERT INTO `field_configuration` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1941, 1940, 'Default Field Configuration', 'Default Field Configuration', '2014-05-08 19:17:02', NULL),
(1943, 1942, 'Default Field Configuration', 'Default Field Configuration', '2014-05-08 19:17:02', NULL),
(1945, 1944, 'Default Field Configuration', 'Default Field Configuration', '2014-05-08 19:17:02', NULL),
(1960, 1959, 'Default Field Configuration', 'Default Field Configuration', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `field_configuration_data`
--

CREATE TABLE IF NOT EXISTS `field_configuration_data` (
`id` bigint(20) unsigned NOT NULL,
  `field_configuration_id` bigint(20) unsigned NOT NULL,
  `field_id` bigint(20) unsigned NOT NULL,
  `visible_flag` tinyint(3) unsigned DEFAULT NULL,
  `required_flag` tinyint(3) unsigned NOT NULL,
  `field_description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29345 ;

--
-- Dumping data for table `field_configuration_data`
--

INSERT INTO `field_configuration_data` (`id`, `field_configuration_id`, `field_id`, `visible_flag`, `required_flag`, `field_description`) VALUES
(2848, 0, 2912, 1, 0, ''),
(2849, 0, 2913, 1, 0, ''),
(2850, 0, 2919, 1, 0, ''),
(2851, 0, 2909, 1, 0, ''),
(2852, 0, 2914, 1, 0, ''),
(2853, 0, 2915, 1, 0, ''),
(2854, 0, 2916, 1, 0, ''),
(2855, 0, 2920, 1, 0, ''),
(2856, 0, 2917, 1, 0, ''),
(2857, 0, 2911, 1, 1, ''),
(2858, 0, 2918, 1, 0, ''),
(2859, 0, 2921, 1, 1, ''),
(2860, 0, 2908, 1, 0, ''),
(2861, 0, 2910, 1, 1, ''),
(2862, 0, 2922, 1, 0, ''),
(29044, 1941, 29106, 1, 0, ''),
(29045, 1941, 29107, 1, 0, ''),
(29046, 1941, 29113, 1, 0, ''),
(29047, 1941, 29103, 1, 0, ''),
(29048, 1941, 29108, 1, 0, ''),
(29049, 1941, 29109, 1, 0, ''),
(29050, 1941, 29110, 1, 0, ''),
(29051, 1941, 29114, 1, 0, ''),
(29052, 1941, 29111, 1, 0, ''),
(29053, 1941, 29105, 1, 1, ''),
(29054, 1941, 29112, 1, 0, ''),
(29055, 1941, 29115, 1, 1, ''),
(29056, 1941, 29102, 1, 0, ''),
(29057, 1941, 29104, 1, 1, ''),
(29058, 1941, 29116, 1, 0, ''),
(29074, 1943, 29136, 1, 0, ''),
(29075, 1943, 29137, 1, 0, ''),
(29076, 1943, 29143, 1, 0, ''),
(29077, 1943, 29133, 1, 0, ''),
(29078, 1943, 29138, 1, 0, ''),
(29079, 1943, 29139, 1, 0, ''),
(29080, 1943, 29140, 1, 0, ''),
(29081, 1943, 29144, 1, 0, ''),
(29082, 1943, 29141, 1, 0, ''),
(29083, 1943, 29135, 1, 1, ''),
(29084, 1943, 29142, 1, 0, ''),
(29085, 1943, 29145, 1, 1, ''),
(29086, 1943, 29132, 1, 0, ''),
(29087, 1943, 29134, 1, 1, ''),
(29088, 1943, 29146, 1, 0, ''),
(29104, 1945, 29166, 1, 0, ''),
(29105, 1945, 29167, 1, 0, ''),
(29106, 1945, 29173, 1, 0, ''),
(29107, 1945, 29163, 1, 0, ''),
(29108, 1945, 29168, 1, 0, ''),
(29109, 1945, 29169, 1, 0, ''),
(29110, 1945, 29170, 1, 0, ''),
(29111, 1945, 29174, 1, 0, ''),
(29112, 1945, 29171, 1, 0, ''),
(29113, 1945, 29165, 1, 1, ''),
(29114, 1945, 29172, 1, 0, ''),
(29115, 1945, 29175, 1, 1, ''),
(29116, 1945, 29162, 1, 0, ''),
(29117, 1945, 29164, 1, 1, ''),
(29118, 1945, 29176, 1, 0, ''),
(29330, 1960, 29392, 1, 0, ''),
(29331, 1960, 29393, 1, 0, ''),
(29332, 1960, 29399, 1, 0, ''),
(29333, 1960, 29389, 1, 0, ''),
(29334, 1960, 29394, 1, 0, ''),
(29335, 1960, 29395, 1, 0, ''),
(29336, 1960, 29396, 1, 0, ''),
(29337, 1960, 29400, 1, 0, ''),
(29338, 1960, 29397, 1, 0, ''),
(29339, 1960, 29391, 1, 1, ''),
(29340, 1960, 29398, 1, 0, ''),
(29341, 1960, 29401, 1, 1, ''),
(29342, 1960, 29388, 1, 0, ''),
(29343, 1960, 29390, 1, 1, ''),
(29344, 1960, 29402, 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `field_issue_type_data`
--

CREATE TABLE IF NOT EXISTS `field_issue_type_data` (
`id` bigint(20) unsigned NOT NULL,
  `field_id` bigint(20) unsigned NOT NULL,
  `issue_type_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

--
-- Table structure for table `field_project_data`
--

CREATE TABLE IF NOT EXISTS `field_project_data` (
`id` bigint(20) unsigned NOT NULL,
  `field_id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `filter`
--

CREATE TABLE IF NOT EXISTS `filter` (
`id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(250) NOT NULL,
  `definition` mediumtext NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `filter`
--

INSERT INTO `filter` (`id`, `user_id`, `name`, `definition`, `description`, `date_created`, `date_updated`) VALUES
(2, 27, 'Filter for Sprint 1', 'project_id_arr=10$$', 'Filter created automatically for agile board Sprint 1', '2012-12-06 14:21:17', NULL),
(3, 31, 'Filter for .nkmbf', 'project_id_arr=13$$', 'Filter created automatically for agile board .nkmbf', '2012-12-12 15:03:43', NULL),
(4, 34, 'Filter for Test', 'Array', 'Filter created automatically for agile board Test', '2012-12-13 22:34:35', NULL),
(5, 39, 'Filter for ABC', 'Array', 'Filter created automatically for agile board ABC', '2012-12-17 16:22:40', NULL),
(6, 22, 'CRM', 'source=m', '', '2012-12-17 21:16:34', NULL),
(7, 44, 'Filter for Agile Yappa', 'Array', 'Filter created automatically for agile board Agile Yappa', '2012-12-19 07:31:20', NULL),
(8, 64, 'Filter for Test Board', 'project=23', 'Filter created automatically for agile board Test Board', '2013-01-04 11:54:51', NULL),
(9, 64, 'Filter for Main Board', 'project=23', 'Filter created automatically for agile board Main Board', '2013-01-04 11:55:21', NULL),
(10, 74, 'Filter for Test', 'project=42', 'Filter created automatically for agile board Test', '2013-01-19 20:41:24', NULL),
(11, 74, 'Filter for Greater New Grove Missionary Baptist', 'project=42|43', 'Filter created automatically for agile board Greater New Grove Missionary Baptist', '2013-01-19 20:42:00', NULL),
(12, 88, 'Filter for Board', 'project=46', 'Filter created automatically for agile board Board', '2013-01-27 23:26:15', NULL),
(13, 89, 'Filter for Test', 'project=47', 'Filter created automatically for agile board Test', '2013-01-28 07:23:51', NULL),
(14, 89, 'Filter for Main Workload', 'project=47', 'Filter created automatically for agile board Main Workload', '2013-01-28 07:48:41', NULL),
(17, 91, 'Τα πάντα όλα', '', '', '2013-01-30 07:51:49', NULL),
(20, 91, 'Filter for My board', 'project=48', 'Filter created automatically for agile board My board', '2013-01-30 07:53:37', NULL),
(21, 91, 'Όλα τα ανοικτά', 'project=48|49&resolution=-2&assignee=91', 'Φίλτρο για την εμφάνιση όλων των ανοικτών θεμάτων.', '2013-01-30 09:10:38', NULL),
(22, 94, 'Filter for Develop', 'project=51', 'Filter created automatically for agile board Develop', '2013-01-30 18:59:06', NULL),
(23, 3, 'Filter for qqq', 'project=2|3|4', 'Filter created automatically for agile board qqq', '2013-02-01 22:31:57', NULL),
(24, 64, 'Filter for Lubuskie SPSZ Board', 'project=52', 'Filter created automatically for agile board Lubuskie SPSZ Board', '2013-02-04 16:21:49', NULL),
(25, 96, 'Filter for Main Workload', 'project=53', 'Filter created automatically for agile board Main Workload', '2013-02-04 17:20:26', NULL),
(26, 3, 'Filter for Ubirimi', 'project=2', 'Filter created automatically for agile board Ubirimi', '2013-02-05 13:27:17', NULL),
(27, 103, 'Filter for my test board', 'project=60', 'Filter created automatically for agile board my test board', '2013-02-14 02:13:43', NULL),
(28, 67, 'Filter for Space', 'project=63', 'Filter created automatically for agile board Space', '2013-02-20 08:24:49', NULL),
(29, 118, 'Filter for Panel de prueba', 'project=67', 'Filter created automatically for agile board Panel de prueba', '2013-03-07 19:42:14', NULL),
(30, 135, 'Filter for JIRA', 'project=75', 'Filter created automatically for agile board JIRA', '2013-04-29 05:14:25', NULL),
(31, 136, 'Filter for test', 'project=77', 'Filter created automatically for agile board test', '2013-05-01 21:33:58', NULL),
(32, 138, 'Filter for Gemeente Publicaties', 'project=78', 'Filter created automatically for agile board Gemeente Publicaties', '2013-05-03 14:19:11', NULL),
(33, 140, 'Filter for iBroadcast XP', 'project=79', 'Filter created automatically for agile board iBroadcast XP', '2013-05-05 19:35:03', NULL),
(34, 147, 'Filter for hh', 'project=85', 'Filter created automatically for agile board hh', '2013-05-09 11:18:18', NULL),
(35, 145, 'Filter for ggg', 'project=84', 'Filter created automatically for agile board ggg', '2013-05-13 03:01:03', NULL),
(36, 143, 'Filter for Sprint 1', 'project=82', 'Filter created automatically for agile board Sprint 1', '2013-05-15 14:16:30', NULL),
(37, 145, 'Filter for Test Test', 'project=84', 'Filter created automatically for agile board Test Test', '2013-05-19 08:42:40', NULL),
(38, 160, 'Filter for sprint 1', 'project=89', 'Filter created automatically for agile board sprint 1', '2013-05-22 12:01:57', NULL),
(44, 191, 'Filter for Agile', 'project=98', 'Filter created automatically for agile board Agile', '2013-08-01 17:48:24', NULL),
(45, 216, 'Filter for Svetainės sekimas', 'project=107', 'Filter created automatically for agile board Svetainės sekimas', '2013-08-14 10:51:47', NULL),
(46, 224, 'Filter for Test', 'project=116', 'Filter created automatically for agile board Test', '2013-08-14 12:16:49', NULL),
(48, 239, 'All issues', 'project=118', '', '2013-08-23 09:40:12', NULL),
(49, 243, 'byme', '', '', '2013-08-26 07:44:57', NULL),
(50, 255, 'Filter for Prepare Prototype Project', 'project=124', 'Filter created automatically for agile board Prepare Prototype Project', '2013-08-29 15:39:19', NULL),
(51, 261, 'Filter for Creación de Apperture - Board', 'project=129', 'Filter created automatically for agile board Creación de Apperture - Board', '2013-08-31 12:58:39', NULL),
(52, 145, 'Filter for Evaluation', 'project=84', 'Filter created automatically for agile board Evaluation', '2013-09-01 03:55:24', NULL),
(53, 145, 'Filter for Test4', 'project=86', 'Filter created automatically for agile board Test4', '2013-09-10 09:05:12', NULL),
(54, 3, 'Filter for Yongo', 'project=2', 'Filter created automatically for agile board Yongo', '2013-09-12 12:35:10', NULL),
(55, 268, 'Filter for BABY ARABIA Scrum', 'project=136', 'Filter created automatically for agile board BABY ARABIA Scrum', '2013-09-15 20:12:52', NULL),
(56, 274, 'Filter for teste board?', 'project=140', 'Filter created automatically for agile board teste board?', '2013-09-23 02:03:49', NULL),
(57, 274, 'Filter for Completos ao sistema', 'project=140', 'Filter created automatically for agile board Completos ao sistema', '2013-09-23 02:29:43', NULL),
(58, 274, 'Filter for Docuementação', 'project=140', 'Filter created automatically for agile board Docuementação', '2013-09-23 02:32:08', NULL),
(59, 274, 'Filter for Sistema básico', 'project=140', 'Filter created automatically for agile board Sistema básico', '2013-09-23 02:39:14', NULL),
(60, 274, 'Filter for Completos ao sistema', 'project=140', 'Filter created automatically for agile board Completos ao sistema', '2013-09-23 02:39:47', NULL),
(61, 274, 'Filter for Documentação', 'project=140', 'Filter created automatically for agile board Documentação', '2013-09-23 02:40:42', NULL),
(62, 325, 'Filter for Auto Screen scaling', 'project=154', 'Filter created automatically for agile board Auto Screen scaling', '2013-10-09 12:03:57', NULL),
(63, 329, 'Filter for CW Board', 'project=157', 'Filter created automatically for agile board CW Board', '2013-10-10 14:44:13', NULL),
(64, 329, 'Filter for CW Board', 'project=158', 'Filter created automatically for agile board CW Board', '2013-10-10 17:37:19', NULL),
(65, 329, 'Filter for Project-CA', 'project=158', 'Filter created automatically for agile board Project-CA', '2013-10-10 17:38:23', NULL),
(66, 137, 'Filter for YourMoneyTool', 'project=152', 'Filter created automatically for agile board YourMoneyTool', '2013-10-14 14:01:46', NULL),
(67, 2, 'Filter for Documentador', 'project=110', 'Filter created automatically for agile board Documentador', '2013-10-19 15:05:35', NULL),
(68, 369, 'Filter for User Admin', 'project=178', 'Filter created automatically for agile board User Admin', '2013-10-22 19:34:33', NULL),
(69, 371, 'Filter for myboard', 'project=181', 'Filter created automatically for agile board myboard', '2013-10-23 00:46:22', NULL),
(70, 369, 'FuBaDu Open Issues', 'project=178&status=1014|1017|1018', '', '2013-10-23 09:29:41', NULL),
(71, 369, 'FuBaDu Features', 'project=178&type=1643', '', '2013-10-23 09:33:45', NULL),
(72, 372, 'Filter for NewBorders', 'project=182', 'Filter created automatically for agile board NewBorders', '2013-10-24 10:30:42', NULL),
(76, 145, 'Filter for test33', 'project=84', 'Filter created automatically for agile board test33', '2013-10-28 19:20:09', NULL),
(77, 332, 'Open Issues for CA', 'page=1&sort=created&order=desc&project=158', '', '2013-11-02 11:44:49', NULL),
(79, 216, 'Filter for BB Agilė', 'project=191', 'Filter created automatically for agile board BB Agilė', '2013-11-05 13:53:56', NULL),
(80, 3, 'Francisc Open Issues', 'project=2|108|109|110|111|112|125|127&assignee=2&resolution=-2', '', '2013-11-05 14:41:35', NULL),
(81, 3, 'All Open Issues', 'project=2|108|109|110|111|112|125|127&status=6', '', '2013-11-05 16:16:13', NULL),
(83, 206, 'Filter for Design', 'project=203', 'Filter created automatically for agile board Design', '2013-11-15 00:54:39', NULL),
(84, 1888, 'Filter for TestBoard', 'project=208', 'Filter created automatically for agile board TestBoard', '2013-11-21 17:14:23', NULL),
(85, 1892, 'Filter for zxc', 'project=209', 'Filter created automatically for agile board zxc', '2013-11-26 11:03:45', NULL),
(86, 330, 'Filter for test', 'project=159', 'Filter created automatically for agile board test', '2013-11-27 06:35:27', NULL),
(87, 330, 'Filter for Project-SS', 'project=159', 'Filter created automatically for agile board Project-SS', '2013-11-27 06:36:46', NULL),
(88, 1892, 'Filter for JFS Board', 'project=212', 'Filter created automatically for agile board JFS Board', '2013-11-27 09:52:51', NULL),
(89, 400, 'Filter for Entrevistas', 'project=195', 'Filter created automatically for agile board Entrevistas', '2013-11-28 19:34:37', NULL),
(90, 255, 'Filter for Vcms-prototype Board', 'project=229', 'Filter created automatically for agile board Vcms-prototype Board', '2014-01-13 20:45:03', NULL),
(93, 1943, 'Filter for Notify', 'project=242', 'Filter created automatically for agile board Notify', '2014-02-04 13:51:11', NULL),
(94, 1892, 'Filter for fgwefew', 'project=209', 'Filter created automatically for agile board fgwefew', '2014-02-09 02:47:22', NULL),
(95, 1892, 'Filter for Testing Board 1', 'project=209', 'Filter created automatically for agile board Testing Board 1', '2014-02-25 12:43:51', NULL),
(96, 1892, 'Filter for bubba Board', 'project=209', 'Filter created automatically for agile board bubba Board', '2014-03-10 23:17:12', NULL),
(97, 2048, 'Filter for New board', 'project=266', 'Filter created automatically for agile board New board', '2014-03-11 17:25:25', NULL),
(98, 2094, 'Filter for Gebyr', 'project=272', 'Filter created automatically for agile board Gebyr', '2014-03-12 18:25:33', NULL),
(99, 2129, 'Filter for Test Board', 'project=275', 'Filter created automatically for agile board Test Board', '2014-03-12 20:41:34', NULL),
(100, 2164, 'Filter for Phase 1', 'project=285', 'Filter created automatically for agile board Phase 1', '2014-03-13 20:25:34', NULL),
(101, 137, 'Filter for YourMoneyTool Rapporten', 'project=265', 'Filter created automatically for agile board YourMoneyTool Rapporten', '2014-03-14 09:22:07', NULL),
(102, 2172, 'Filter for My Troop Manager', 'project=286', 'Filter created automatically for agile board My Troop Manager', '2014-03-15 00:40:10', NULL),
(103, 1892, 'aaaa', '', 'jfujvj', '2014-03-17 21:09:44', NULL),
(104, 137, 'YMTR Open', 'filter=104&', '', '2014-03-19 10:32:55', '2014-03-19 13:26:22'),
(106, 2162, 'Filter for Altis Life', 'project=284', 'Filter created automatically for agile board Altis Life', '2014-03-27 11:52:12', NULL),
(107, 2182, 'Filter for Mahjong iOS v1.0', 'project=308', 'Filter created automatically for agile board Mahjong iOS v1.0', '2014-04-08 16:59:26', NULL),
(108, 1892, 'Filter for Pete', 'project=309', 'Filter created automatically for agile board Pete', '2014-04-11 14:15:58', NULL),
(109, 1892, 'FT REport', 'assignee=1892&project=215&status=8519', '', '2014-04-15 23:13:06', NULL),
(110, 2172, 'Filter for Agile Board 1 DC Cops', 'project=303', 'Filter created automatically for agile board Agile Board 1 DC Cops', '2014-04-15 23:03:59', NULL),
(111, 329, 'Filter for Memo IOS', 'project=316', 'Filter created automatically for agile board Memo IOS', '2014-05-06 17:37:31', NULL),
(112, 329, 'Filter for Memo Android', 'project=317', 'Filter created automatically for agile board Memo Android', '2014-05-06 17:38:15', NULL),
(113, 2227, 'Filter for Board', 'project=327', 'Filter created automatically for agile board Board', '2014-05-14 12:56:47', NULL),
(114, 2235, 'Filter for Test Agile Board', 'project=336', 'Filter created automatically for agile board Test Agile Board', '2014-05-30 14:28:27', NULL),
(115, 2238, 'Filter for opened tickets', 'project=340|341|342|343|344', 'Filter created automatically for agile board opened tickets', '2014-06-17 14:57:11', NULL),
(116, 2238, 'Filter for total asset', 'project=340|341|342|343|344|345|346|347|348|349', 'Filter created automatically for agile board total asset', '2014-06-23 08:42:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `general_invoice`
--

CREATE TABLE IF NOT EXISTS `general_invoice` (
`id` bigint(20) unsigned NOT NULL,
  `general_payment_id` bigint(20) unsigned NOT NULL,
  `number` bigint(20) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `general_invoice`
--

INSERT INTO `general_invoice` (`id`, `general_payment_id`, `number`, `date_created`) VALUES
(1, 2, 1, '2014-05-01 08:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `general_log`
--

CREATE TABLE IF NOT EXISTS `general_log` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sys_product_id` bigint(10) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `message` text NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10546 ;

--
-- Dumping data for table `general_log`
--

INSERT INTO `general_log` (`id`, `client_id`, `sys_product_id`, `user_id`, `message`, `date_created`) VALUES
(3, 96, -1, 145, 'LOG IN', '2013-10-04 12:29:44'),
(4, 96, 5, 145, 'ADD EVENTS calendar cal', '2013-10-04 12:43:39'),
(5, 96, -1, 145, 'LOG IN', '2013-10-04 12:57:44'),
(7, 96, -1, 145, 'LOG IN', '2013-10-04 15:53:54'),
(8, 96, -1, 145, 'LOG IN', '2013-10-04 15:55:25'),
(9, 96, -1, 145, 'LOG IN', '2013-10-04 17:02:06'),
(10, 96, -1, 145, 'LOG IN', '2013-10-04 18:01:51'),
(11, 96, -1, 145, 'LOG IN', '2013-10-04 18:10:52'),
(12, 96, -1, 145, 'LOG IN', '2013-10-04 18:16:39'),
(13, 96, -1, 145, 'LOG IN', '2013-10-04 18:34:22'),
(14, 96, -1, 145, 'LOG IN', '2013-10-04 21:46:35'),
(15, 96, -1, 145, 'LOG IN', '2013-10-05 00:04:54'),
(19, 96, -1, 145, 'LOG IN', '2013-10-05 11:16:49'),
(20, 96, -1, 145, 'LOG OUT', '2013-10-05 11:24:23'),
(22, 96, -1, 145, 'LOG IN', '2013-10-05 13:34:25'),
(23, 96, -1, 145, 'LOG IN', '2013-10-05 16:02:50'),
(25, 96, -1, 145, 'LOG IN', '2013-10-06 12:23:22'),
(26, 96, -1, 145, 'LOG IN', '2013-10-06 18:42:04'),
(27, 96, -1, 145, 'LOG IN', '2013-10-06 19:38:39'),
(36, 96, -1, 145, 'LOG IN', '2013-10-06 23:05:14'),
(37, 96, 1, 145, 'ADD Yongo Project Category flyer', '2013-10-06 23:05:46'),
(45, 96, -1, 145, 'LOG IN', '2013-10-07 05:44:50'),
(118, 96, -1, 145, 'LOG IN', '2013-10-07 20:53:58'),
(150, 96, -1, 145, 'LOG IN', '2013-10-08 08:26:08'),
(151, 96, -1, 145, 'LOG OUT', '2013-10-08 08:28:15'),
(155, 96, -1, 145, 'LOG IN', '2013-10-08 08:40:57'),
(173, 96, -1, 145, 'LOG IN', '2013-10-08 12:50:17'),
(185, 96, -1, 145, 'LOG IN', '2013-10-08 20:42:27'),
(188, 96, -1, 145, 'LOG IN', '2013-10-09 04:01:44'),
(195, 96, -1, 145, 'LOG IN', '2013-10-09 08:48:51'),
(223, 96, -1, 145, 'LOG IN', '2013-10-09 10:52:35'),
(232, 96, -1, 145, 'LOG IN', '2013-10-09 12:23:52'),
(243, 96, -1, 145, 'LOG IN', '2013-10-09 14:11:09'),
(246, 96, -1, 145, 'LOG IN', '2013-10-09 18:41:47'),
(279, 96, -1, 145, 'LOG IN', '2013-10-10 10:04:11'),
(295, 96, -1, 145, 'LOG IN', '2013-10-10 11:40:04'),
(412, 96, -1, 145, 'LOG IN', '2013-10-10 16:47:13'),
(553, 96, -1, 145, 'LOG IN', '2013-10-11 14:37:29'),
(574, 96, -1, 145, 'LOG IN', '2013-10-11 19:14:25'),
(578, 96, -1, 145, 'LOG IN', '2013-10-11 22:58:22'),
(581, 96, -1, 145, 'LOG IN', '2013-10-12 16:35:16'),
(582, 96, -1, 145, 'LOG IN', '2013-10-12 17:42:45'),
(583, 96, -1, 145, 'LOG IN', '2013-10-12 23:42:08'),
(610, 96, -1, 145, 'LOG IN', '2013-10-14 14:07:37'),
(611, 96, -1, 145, 'LOG IN', '2013-10-14 14:36:39'),
(612, 96, -1, 145, 'LOG OUT', '2013-10-14 14:37:18'),
(614, 96, -1, 145, 'LOG IN', '2013-10-14 15:32:45'),
(615, 96, -1, 145, 'LOG IN', '2013-10-14 15:38:54'),
(620, 96, -1, 145, 'LOG IN', '2013-10-14 15:54:17'),
(636, 96, -1, 145, 'LOG IN', '2013-10-14 16:05:05'),
(642, 96, -1, 145, 'LOG IN', '2013-10-14 16:37:54'),
(643, 96, -1, 145, 'LOG IN', '2013-10-14 17:17:37'),
(644, 96, -1, 145, 'LOG IN', '2013-10-14 19:09:23'),
(645, 96, -1, 145, 'LOG IN', '2013-10-14 19:52:49'),
(647, 96, -1, 145, 'LOG IN', '2013-10-14 20:24:12'),
(648, 96, 1, 145, 'ADD Yongo issue DEMO-22', '2013-10-14 20:24:49'),
(649, 96, -1, 145, 'LOG IN', '2013-10-14 20:40:46'),
(650, 96, -1, 145, 'LOG IN', '2013-10-14 20:41:52'),
(651, 96, -1, 145, 'LOG IN', '2013-10-14 20:44:04'),
(652, 96, -1, 145, 'LOG IN', '2013-10-14 20:51:12'),
(653, 96, -1, 145, 'LOG IN', '2013-10-14 20:57:16'),
(654, 96, -1, 145, 'LOG IN', '2013-10-14 21:35:00'),
(655, 96, -1, 145, 'LOG IN', '2013-10-14 22:00:22'),
(656, 96, -1, 145, 'LOG OUT', '2013-10-14 22:02:01'),
(657, 96, -1, 145, 'LOG OUT', '2013-10-14 22:02:02'),
(658, 96, -1, 145, 'LOG IN', '2013-10-14 22:03:19'),
(659, 96, -1, 145, 'LOG IN', '2013-10-14 22:03:45'),
(660, 96, -1, 145, 'LOG IN', '2013-10-14 22:05:57'),
(661, 96, -1, 145, 'LOG IN', '2013-10-14 22:06:00'),
(665, 96, -1, 145, 'LOG IN', '2013-10-14 22:52:05'),
(666, 96, -1, 145, 'LOG IN', '2013-10-14 23:14:09'),
(667, 96, -1, 145, 'LOG IN', '2013-10-14 23:36:30'),
(668, 96, -1, 145, 'LOG IN', '2013-10-14 23:46:03'),
(669, 96, -1, 145, 'LOG IN', '2013-10-15 00:12:33'),
(670, 96, -1, 145, 'LOG IN', '2013-10-15 00:57:42'),
(674, 96, -1, 145, 'LOG IN', '2013-10-15 07:11:07'),
(675, 96, -1, 145, 'LOG IN', '2013-10-15 07:52:01'),
(676, 96, -1, 145, 'LOG IN', '2013-10-15 08:14:19'),
(677, 96, -1, 145, 'LOG OUT', '2013-10-15 08:14:38'),
(678, 96, -1, 145, 'LOG IN', '2013-10-15 09:24:53'),
(679, 96, -1, 145, 'LOG IN', '2013-10-15 09:51:16'),
(680, 96, -1, 145, 'LOG IN', '2013-10-15 10:21:35'),
(681, 96, -1, 145, 'LOG IN', '2013-10-15 10:40:36'),
(683, 96, -1, 145, 'LOG IN', '2013-10-15 10:51:41'),
(684, 96, -1, 145, 'LOG IN', '2013-10-15 11:08:24'),
(685, 96, -1, 145, 'LOG IN', '2013-10-15 11:13:29'),
(689, 96, -1, 145, 'LOG IN', '2013-10-15 11:59:53'),
(691, 96, -1, 145, 'LOG IN', '2013-10-15 12:46:29'),
(701, 96, -1, 145, 'LOG IN', '2013-10-15 14:12:34'),
(705, 96, -1, 145, 'LOG IN', '2013-10-15 14:38:08'),
(711, 96, -1, 145, 'LOG IN', '2013-10-15 15:05:37'),
(712, 96, -1, 145, 'LOG IN', '2013-10-15 15:07:09'),
(713, 96, -1, 145, 'LOG OUT', '2013-10-15 15:08:26'),
(720, 96, -1, 145, 'LOG IN', '2013-10-15 15:25:44'),
(723, 96, -1, 145, 'LOG IN', '2013-10-15 15:33:17'),
(725, 96, -1, 145, 'LOG IN', '2013-10-15 15:40:20'),
(729, 96, -1, 145, 'LOG IN', '2013-10-15 15:43:51'),
(730, 96, -1, 145, 'LOG IN', '2013-10-15 15:46:41'),
(731, 96, 5, 145, 'UPDATE EVENTS calendar test', '2013-10-15 15:47:24'),
(735, 96, -1, 145, 'LOG IN', '2013-10-15 16:35:23'),
(743, 96, -1, 145, 'LOG IN', '2013-10-15 17:15:54'),
(744, 96, -1, 145, 'LOG IN', '2013-10-15 17:42:12'),
(745, 96, -1, 145, 'LOG OUT', '2013-10-15 17:43:04'),
(746, 96, -1, 145, 'LOG IN', '2013-10-15 17:44:25'),
(747, 96, -1, 145, 'LOG OUT', '2013-10-15 17:44:55'),
(748, 96, -1, 145, 'LOG IN', '2013-10-15 18:04:17'),
(749, 96, -1, 145, 'LOG IN', '2013-10-15 19:04:56'),
(751, 96, -1, 145, 'LOG IN', '2013-10-15 20:46:50'),
(753, 96, -1, 145, 'LOG IN', '2013-10-15 21:55:53'),
(754, 96, -1, 145, 'LOG IN', '2013-10-15 23:22:25'),
(755, 96, -1, 145, 'LOG IN', '2013-10-16 00:10:22'),
(756, 96, -1, 145, 'LOG IN', '2013-10-16 01:45:29'),
(769, 96, -1, 145, 'LOG IN', '2013-10-16 07:20:26'),
(808, 96, -1, 145, 'LOG IN', '2013-10-16 08:34:59'),
(810, 96, -1, 145, 'LOG IN', '2013-10-16 09:01:03'),
(811, 96, -1, 145, 'LOG IN', '2013-10-16 09:01:27'),
(813, 96, -1, 145, 'LOG IN', '2013-10-16 09:55:29'),
(825, 96, -1, 145, 'LOG IN', '2013-10-16 10:42:33'),
(827, 96, -1, 145, 'LOG IN', '2013-10-16 10:50:30'),
(828, 96, 5, 145, 'ADD EVENTS event ', '2013-10-16 10:51:30'),
(829, 96, -1, 145, 'LOG IN', '2013-10-16 10:51:50'),
(830, 96, -1, 145, 'LOG OUT', '2013-10-16 10:52:27'),
(851, 96, -1, 145, 'LOG IN', '2013-10-16 12:34:33'),
(852, 96, -1, 145, 'LOG IN', '2013-10-16 12:36:35'),
(855, 96, -1, 145, 'LOG IN', '2013-10-16 13:17:54'),
(864, 96, -1, 145, 'LOG IN', '2013-10-16 13:59:50'),
(867, 96, -1, 145, 'LOG IN', '2013-10-16 14:35:47'),
(869, 96, -1, 145, 'LOG IN', '2013-10-16 14:44:14'),
(884, 96, -1, 145, 'LOG IN', '2013-10-16 16:33:45'),
(885, 96, -1, 145, 'LOG IN', '2013-10-16 16:39:14'),
(886, 96, 1, 145, 'DELETE Yongo issue DEMO-22', '2013-10-16 16:39:52'),
(887, 96, -1, 145, 'LOG OUT', '2013-10-16 16:40:42'),
(888, 96, -1, 145, 'LOG IN', '2013-10-16 16:46:02'),
(890, 96, -1, 145, 'LOG IN', '2013-10-16 17:12:31'),
(891, 96, -1, 145, 'LOG IN', '2013-10-16 17:41:28'),
(892, 96, -1, 145, 'LOG OUT', '2013-10-16 17:43:35'),
(897, 96, -1, 145, 'LOG IN', '2013-10-16 18:34:45'),
(903, 96, -1, 145, 'LOG IN', '2013-10-16 19:02:37'),
(907, 96, -1, 145, 'LOG IN', '2013-10-16 20:55:13'),
(908, 96, 1, 145, 'ADD Yongo Project YetAnotherProject', '2013-10-16 20:56:22'),
(909, 96, -1, 145, 'LOG IN', '2013-10-16 20:57:35'),
(910, 96, -1, 145, 'LOG IN', '2013-10-16 21:18:56'),
(911, 96, -1, 145, 'LOG IN', '2013-10-16 21:20:12'),
(912, 96, -1, 145, 'LOG IN', '2013-10-16 21:34:31'),
(913, 96, -1, 145, 'LOG IN', '2013-10-16 21:55:40'),
(914, 96, -1, 145, 'LOG IN', '2013-10-16 22:32:31'),
(915, 96, -1, 145, 'LOG IN', '2013-10-16 23:35:39'),
(917, 96, -1, 145, 'LOG IN', '2013-10-17 04:02:46'),
(933, 96, -1, 145, 'LOG IN', '2013-10-17 08:42:22'),
(934, 96, 1, 145, 'ADD Yongo issue DEMO-23', '2013-10-17 08:42:49'),
(935, 96, 1, 145, 'UPDATE Yongo issue DEMO-23', '2013-10-17 08:43:05'),
(942, 96, -1, 145, 'LOG IN', '2013-10-17 09:15:39'),
(948, 96, -1, 145, 'LOG IN', '2013-10-17 09:44:01'),
(957, 96, -1, 145, 'LOG IN', '2013-10-17 10:30:48'),
(962, 96, -1, 145, 'LOG IN', '2013-10-17 11:21:13'),
(967, 96, -1, 145, 'LOG IN', '2013-10-17 12:03:27'),
(976, 96, -1, 145, 'LOG IN', '2013-10-17 13:48:28'),
(982, 96, -1, 145, 'LOG IN', '2013-10-17 15:01:34'),
(983, 96, -1, 145, 'LOG IN', '2013-10-17 15:11:53'),
(996, 96, -1, 145, 'LOG IN', '2013-10-17 15:51:41'),
(1017, 96, -1, 145, 'LOG IN', '2013-10-17 18:41:12'),
(1022, 96, -1, 145, 'LOG IN', '2013-10-17 20:13:20'),
(1030, 96, -1, 145, 'LOG IN', '2013-10-17 23:09:48'),
(1031, 96, -1, 145, 'LOG IN', '2013-10-18 00:38:01'),
(1034, 96, -1, 145, 'LOG IN', '2013-10-18 02:34:56'),
(1042, 96, -1, 145, 'LOG IN', '2013-10-18 04:26:12'),
(1045, 96, -1, 145, 'LOG IN', '2013-10-18 07:22:54'),
(1061, 96, -1, 145, 'LOG IN', '2013-10-18 08:50:41'),
(1070, 195, -1, 363, 'LOG IN', '2013-10-18 10:42:15'),
(1071, 195, 1, 363, 'ADD Yongo Project urs', '2013-10-18 10:42:42'),
(1072, 195, 1, 363, 'ADD Yongo issue URS-1', '2013-10-18 10:49:53'),
(1073, 195, 1, 363, 'ADD Yongo issue URS-2', '2013-10-18 10:51:14'),
(1074, 195, 1, 363, 'ADD Yongo issue URS-3', '2013-10-18 10:52:24'),
(1083, 96, -1, 145, 'LOG IN', '2013-10-18 12:59:42'),
(1087, 96, -1, 145, 'LOG IN', '2013-10-18 13:12:48'),
(1088, 96, -1, 145, 'LOG IN', '2013-10-18 14:17:07'),
(1089, 96, -1, 145, 'LOG IN', '2013-10-18 14:33:15'),
(1090, 96, -1, 145, 'LOG IN', '2013-10-18 15:05:11'),
(1091, 96, -1, 145, 'LOG OUT', '2013-10-18 15:05:32'),
(1108, 96, -1, 145, 'LOG IN', '2013-10-18 15:57:19'),
(1123, 96, -1, 145, 'LOG IN', '2013-10-18 17:53:42'),
(1129, 96, -1, 145, 'LOG IN', '2013-10-19 05:50:21'),
(1130, 96, -1, 145, 'LOG IN', '2013-10-19 08:01:32'),
(1133, 96, -1, 145, 'LOG IN', '2013-10-19 11:54:38'),
(1135, 96, -1, 145, 'LOG IN', '2013-10-19 12:16:54'),
(1137, 96, -1, 145, 'LOG IN', '2013-10-19 12:19:03'),
(1138, 96, 1, 145, 'DELETE Yongo issue DEMO-16', '2013-10-19 12:22:45'),
(1139, 96, 1, 145, 'DELETE Yongo issue DEMO-17', '2013-10-19 12:22:59'),
(1140, 96, 1, 145, 'ADD Yongo issue comment DEMO-15', '2013-10-19 12:34:40'),
(1141, 96, -1, 145, 'LOG OUT', '2013-10-19 12:36:34'),
(1158, 96, -1, 145, 'LOG IN', '2013-10-19 14:30:02'),
(1159, 96, -1, 145, 'LOG IN', '2013-10-19 16:20:35'),
(1160, 96, -1, 145, 'LOG IN', '2013-10-19 19:11:57'),
(1161, 96, -1, 145, 'LOG OUT', '2013-10-19 19:12:34'),
(1162, 96, -1, 145, 'LOG IN', '2013-10-19 19:33:39'),
(1163, 96, -1, 145, 'LOG IN', '2013-10-19 19:57:11'),
(1164, 96, -1, 145, 'LOG IN', '2013-10-19 22:50:39'),
(1165, 96, 5, 145, 'ADD EVENTS event dsa', '2013-10-19 22:51:33'),
(1166, 96, 5, 145, 'ADD EVENTS event dsadsa', '2013-10-19 22:51:37'),
(1167, 96, 5, 145, 'ADD EVENTS event dsadsa', '2013-10-19 22:53:32'),
(1168, 96, -1, 145, 'LOG IN', '2013-10-20 04:23:43'),
(1173, 96, -1, 145, 'LOG IN', '2013-10-20 13:53:10'),
(1194, 96, -1, 145, 'LOG IN', '2013-10-20 23:12:22'),
(1197, 96, -1, 145, 'LOG IN', '2013-10-21 03:01:23'),
(1234, 96, -1, 145, 'LOG IN', '2013-10-21 07:54:03'),
(1235, 96, -1, 145, 'LOG OUT', '2013-10-21 07:54:11'),
(1244, 96, -1, 145, 'LOG IN', '2013-10-21 08:57:44'),
(1249, 96, -1, 145, 'LOG IN', '2013-10-21 09:17:01'),
(1252, 96, -1, 145, 'LOG OUT', '2013-10-21 09:21:11'),
(1267, 96, -1, 145, 'LOG IN', '2013-10-21 10:54:36'),
(1268, 96, 1, 145, 'DELETE Yongo Project test4', '2013-10-21 10:54:49'),
(1269, 96, 1, 145, 'DELETE Yongo Project TEST123', '2013-10-21 10:54:52'),
(1270, 96, 1, 145, 'DELETE Yongo Project Brazillian tester', '2013-10-21 10:54:56'),
(1271, 96, 1, 145, 'DELETE Yongo Project BK_TEST', '2013-10-21 10:54:59'),
(1272, 96, 1, 145, 'DELETE Yongo Project Troca', '2013-10-21 10:55:06'),
(1273, 96, 1, 145, 'DELETE Yongo Project YetAnotherProject', '2013-10-21 10:55:09'),
(1278, 96, -1, 145, 'LOG IN', '2013-10-21 11:36:10'),
(1281, 96, -1, 145, 'LOG IN', '2013-10-21 11:42:50'),
(1282, 96, -1, 145, 'DELETE User f103839', '2013-10-21 12:03:16'),
(1283, 96, -1, 145, 'DELETE User test1234', '2013-10-21 12:03:26'),
(1284, 96, -1, 145, 'DELETE User oar', '2013-10-21 12:03:31'),
(1285, 96, -1, 145, 'DELETE User Ungen', '2013-10-21 12:03:37'),
(1286, 96, 1, 145, 'DELETE Yongo issue BUSDE-2', '2013-10-21 12:03:58'),
(1287, 96, 1, 145, 'DELETE Yongo issue DEMO-23', '2013-10-21 12:04:14'),
(1288, 96, 1, 145, 'DELETE Yongo issue DEMO-23', '2013-10-21 12:04:21'),
(1289, 96, 1, 145, 'DELETE Yongo issue DEMO-21', '2013-10-21 12:04:26'),
(1290, 96, 1, 145, 'DELETE Yongo issue DEMO-20', '2013-10-21 12:04:38'),
(1291, 96, 1, 145, 'DELETE Yongo issue DEMO-19', '2013-10-21 12:09:32'),
(1292, 96, 1, 145, 'DELETE Yongo issue DEMO-1', '2013-10-21 12:10:52'),
(1293, 96, -1, 145, 'DELETE User kk', '2013-10-21 12:11:00'),
(1294, 96, 1, 145, 'DELETE Yongo issue DEMO-2', '2013-10-21 12:11:27'),
(1295, 96, -1, 145, 'DELETE User demo2', '2013-10-21 12:11:34'),
(1296, 96, -1, 145, 'LOG OUT', '2013-10-21 12:11:55'),
(1299, 96, -1, 145, 'LOG IN', '2013-10-21 12:18:53'),
(1307, 96, -1, 145, 'LOG IN', '2013-10-21 13:35:43'),
(1308, 96, -1, 145, 'LOG IN', '2013-10-21 13:36:47'),
(1309, 96, -1, 145, 'LOG OUT', '2013-10-21 13:38:22'),
(1311, 96, -1, 145, 'LOG IN', '2013-10-21 13:49:28'),
(1312, 96, -1, 145, 'LOG IN', '2013-10-21 14:07:57'),
(1313, 96, 1, 145, 'ADD Yongo issue DEMO-24', '2013-10-21 14:09:54'),
(1314, 96, 4, 145, 'ADD Documentador Entity Ubrimi-confluence!', '2013-10-21 14:11:25'),
(1315, 96, 5, 145, 'ADD EVENTS event Mother', '2013-10-21 14:11:59'),
(1319, 96, -1, 145, 'LOG IN', '2013-10-21 15:31:30'),
(1320, 96, -1, 145, 'LOG IN', '2013-10-21 15:38:24'),
(1324, 96, -1, 145, 'LOG IN', '2013-10-21 16:03:11'),
(1336, 96, -1, 145, 'LOG IN', '2013-10-21 16:58:33'),
(1337, 96, 1, 145, 'ADD Yongo issue comment DEMO-24', '2013-10-21 17:00:02'),
(1341, 96, -1, 145, 'LOG IN', '2013-10-21 17:42:02'),
(1348, 96, -1, 145, 'LOG IN', '2013-10-21 19:18:29'),
(1352, 96, -1, 145, 'LOG IN', '2013-10-21 20:46:05'),
(1356, 96, -1, 145, 'LOG IN', '2013-10-21 21:33:14'),
(1357, 96, -1, 145, 'LOG IN', '2013-10-21 22:24:26'),
(1358, 96, -1, 145, 'LOG OUT', '2013-10-21 22:25:46'),
(1360, 96, -1, 145, 'LOG IN', '2013-10-21 22:45:31'),
(1361, 96, -1, 145, 'LOG IN', '2013-10-21 23:05:07'),
(1362, 96, -1, 145, 'LOG IN', '2013-10-21 23:10:29'),
(1381, 96, -1, 145, 'LOG IN', '2013-10-22 08:41:38'),
(1382, 96, 4, 145, 'ADD Documentador Entity cvbcvbcv', '2013-10-22 08:43:22'),
(1387, 96, -1, 145, 'LOG IN', '2013-10-22 09:20:29'),
(1389, 96, -1, 145, 'LOG OUT', '2013-10-22 09:21:08'),
(1390, 96, -1, 145, 'LOG IN', '2013-10-22 09:34:22'),
(1395, 96, -1, 145, 'LOG IN', '2013-10-22 10:26:43'),
(1398, 96, -1, 145, 'LOG IN', '2013-10-22 11:15:20'),
(1400, 96, -1, 145, 'LOG IN', '2013-10-22 11:17:32'),
(1401, 96, -1, 145, 'LOG OUT', '2013-10-22 11:21:51'),
(1403, 96, -1, 145, 'LOG IN', '2013-10-22 11:28:29'),
(1409, 96, -1, 145, 'LOG IN', '2013-10-22 11:48:53'),
(1410, 96, -1, 145, 'LOG OUT', '2013-10-22 11:49:35'),
(1422, 96, -1, 145, 'LOG IN', '2013-10-22 13:35:38'),
(1425, 96, -1, 145, 'LOG OUT', '2013-10-22 13:37:14'),
(1436, 205, -1, 374, 'LOG IN', '2013-10-22 13:57:54'),
(1440, 96, -1, 145, 'LOG IN', '2013-10-22 13:59:59'),
(1450, 205, -1, 374, 'LOG OUT', '2013-10-22 14:17:06'),
(1452, 96, -1, 145, 'LOG IN', '2013-10-22 14:38:01'),
(1457, 96, -1, 145, 'LOG IN', '2013-10-22 15:03:08'),
(1485, 96, -1, 145, 'LOG IN', '2013-10-22 15:54:20'),
(1486, 96, -1, 145, 'LOG IN', '2013-10-22 16:16:04'),
(1488, 96, -1, 145, 'LOG IN', '2013-10-22 16:37:15'),
(1490, 96, -1, 145, 'LOG IN', '2013-10-22 17:51:20'),
(1501, 96, -1, 145, 'LOG IN', '2013-10-22 20:31:01'),
(1502, 96, -1, 145, 'LOG IN', '2013-10-22 20:31:01'),
(1503, 96, -1, 145, 'LOG IN', '2013-10-22 21:00:37'),
(1504, 96, 1, 145, 'UPDATE Yongo issue DEMO-15', '2013-10-22 21:01:50'),
(1546, 96, -1, 145, 'LOG IN', '2013-10-23 08:45:16'),
(1550, 96, -1, 145, 'LOG IN', '2013-10-23 09:09:13'),
(1564, 96, -1, 145, 'LOG IN', '2013-10-23 09:31:53'),
(1607, 96, -1, 145, 'LOG IN', '2013-10-23 10:29:47'),
(1608, 96, -1, 145, 'LOG IN', '2013-10-23 10:38:44'),
(1623, 96, -1, 145, 'LOG IN', '2013-10-23 12:37:11'),
(1690, 96, -1, 145, 'LOG IN', '2013-10-23 17:20:03'),
(1695, 96, -1, 145, 'LOG IN', '2013-10-23 19:22:03'),
(1696, 96, -1, 145, 'LOG IN', '2013-10-23 19:53:37'),
(1699, 96, -1, 145, 'LOG IN', '2013-10-23 23:11:19'),
(1722, 96, -1, 145, 'LOG IN', '2013-10-24 00:54:15'),
(1724, 96, -1, 145, 'LOG IN', '2013-10-24 04:15:14'),
(1757, 96, -1, 145, 'LOG IN', '2013-10-24 09:26:45'),
(1780, 96, -1, 145, 'LOG IN', '2013-10-24 10:34:47'),
(1794, 96, -1, 145, 'LOG IN', '2013-10-24 11:11:29'),
(1795, 96, -1, 145, 'LOG IN', '2013-10-24 11:17:53'),
(1802, 208, -1, 383, 'LOG IN', '2013-10-24 12:14:51'),
(1803, 208, 2, 383, 'ADD SVN Repository pestisor', '2013-10-24 12:15:09'),
(1812, 208, 2, 383, 'DELETE SVN Repository pestisor', '2013-10-24 12:40:02'),
(1853, 208, -1, 383, 'LOG IN', '2013-10-24 15:10:53'),
(1856, 96, -1, 145, 'LOG IN', '2013-10-24 15:37:53'),
(1858, 96, -1, 145, 'LOG IN', '2013-10-24 16:29:38'),
(1859, 96, -1, 145, 'LOG IN', '2013-10-24 16:52:13'),
(1860, 96, -1, 145, 'LOG IN', '2013-10-24 17:07:27'),
(1861, 96, -1, 145, 'LOG IN', '2013-10-24 17:09:30'),
(1864, 96, -1, 145, 'LOG IN', '2013-10-24 21:47:07'),
(1865, 96, -1, 145, 'LOG IN', '2013-10-24 23:50:15'),
(1866, 96, -1, 145, 'LOG IN', '2013-10-25 01:35:51'),
(1899, 96, -1, 145, 'LOG IN', '2013-10-25 10:40:29'),
(1900, 96, -1, 145, 'LOG IN', '2013-10-25 11:49:10'),
(1904, 96, -1, 145, 'LOG IN', '2013-10-25 12:16:45'),
(1908, 96, -1, 145, 'LOG IN', '2013-10-25 12:38:36'),
(1913, 96, -1, 145, 'LOG IN', '2013-10-25 13:16:43'),
(1914, 96, -1, 145, 'LOG OUT', '2013-10-25 13:17:29'),
(1919, 96, -1, 145, 'LOG IN', '2013-10-25 16:13:34'),
(1920, 96, -1, 145, 'LOG IN', '2013-10-25 20:29:08'),
(1921, 96, -1, 145, 'LOG IN', '2013-10-25 20:56:22'),
(1922, 96, 1, 145, 'ADD Project Version 1.7.0', '2013-10-25 20:57:43'),
(1923, 96, 1, 145, 'ADD Project Component Elig', '2013-10-25 20:58:02'),
(1924, 96, 4, 145, 'ADD Documentador space dev-docs', '2013-10-25 20:59:03'),
(1938, 96, -1, 145, 'LOG IN', '2013-10-25 23:29:21'),
(1940, 96, -1, 145, 'LOG IN', '2013-10-26 04:39:53'),
(1979, 96, -1, 145, 'LOG IN', '2013-10-26 13:35:58'),
(1980, 96, 1, 145, 'ADD Yongo Project Testing', '2013-10-26 13:40:19'),
(1982, 96, -1, 145, 'LOG IN', '2013-10-26 17:50:18'),
(1983, 96, -1, 145, 'LOG IN', '2013-10-26 18:24:06'),
(1994, 96, -1, 145, 'LOG IN', '2013-10-27 08:28:29'),
(1995, 96, -1, 145, 'LOG IN', '2013-10-27 09:26:08'),
(2004, 96, -1, 145, 'LOG IN', '2013-10-27 17:14:43'),
(2005, 96, -1, 145, 'LOG IN', '2013-10-28 00:02:14'),
(2039, 96, -1, 145, 'LOG IN', '2013-10-28 11:30:03'),
(2046, 96, -1, 145, 'LOG IN', '2013-10-28 14:03:21'),
(2051, 96, -1, 145, 'LOG IN', '2013-10-28 16:52:08'),
(2052, 96, 1, 145, 'ADD Yongo issue DEMO-25', '2013-10-28 16:54:01'),
(2053, 96, -1, 145, 'LOG IN', '2013-10-28 19:18:05'),
(2054, 96, 1, 145, 'ADD Yongo issue DEMO-26', '2013-10-28 19:19:43'),
(2055, 96, 3, 145, 'ADD Cheetah Agile Board test33', '2013-10-28 19:20:09'),
(2064, 96, -1, 145, 'LOG IN', '2013-10-29 04:23:38'),
(2065, 96, 1, 145, 'UPDATE Yongo issue DEMO-26', '2013-10-29 04:32:04'),
(2086, 96, -1, 145, 'LOG IN', '2013-10-29 12:14:05'),
(2095, 96, -1, 145, 'LOG IN', '2013-10-29 16:36:10'),
(2098, 96, -1, 145, 'LOG IN', '2013-10-29 20:52:13'),
(2099, 96, -1, 145, 'LOG OUT', '2013-10-29 20:52:35'),
(2101, 96, -1, 145, 'LOG IN', '2013-10-29 23:20:44'),
(2106, 96, -1, 145, 'LOG IN', '2013-10-30 04:22:00'),
(2107, 96, 1, 145, 'ADD Yongo issue DEMO-27', '2013-10-30 04:24:24'),
(2148, 96, -1, 145, 'LOG IN', '2013-10-30 11:54:25'),
(2240, 96, -1, 145, 'LOG IN', '2013-10-31 09:00:58'),
(2247, 96, -1, 145, 'LOG IN', '2013-10-31 10:40:16'),
(2260, 96, -1, 145, 'LOG IN', '2013-11-01 03:54:02'),
(2266, 96, -1, 145, 'LOG IN', '2013-11-01 02:43:05'),
(2274, 96, -1, 145, 'LOG IN', '2013-11-01 07:38:28'),
(2275, 96, 1, 145, 'DELETE Yongo issue DEMO-26', '2013-11-01 07:38:42'),
(2276, 96, 1, 145, 'DELETE Yongo issue DEMO-25', '2013-11-01 07:38:52'),
(2277, 96, 1, 145, 'DELETE Yongo issue DEMO-24', '2013-11-01 07:39:00'),
(2278, 96, -1, 145, 'LOG OUT', '2013-11-01 07:45:37'),
(2287, 96, -1, 145, 'LOG IN', '2013-11-01 16:19:44'),
(2288, 96, -1, 145, 'LOG IN', '2013-11-01 18:33:36'),
(2289, 96, 1, 145, 'ADD Yongo issue DEMO-28', '2013-11-01 18:37:05'),
(2335, 96, -1, 145, 'LOG IN', '2013-11-04 08:47:51'),
(2339, 96, -1, 145, 'LOG IN', '2013-11-04 09:26:47'),
(2340, 96, 1, 145, 'ADD Yongo issue DEMO-29', '2013-11-04 09:27:46'),
(2341, 96, 1, 145, 'ADD Yongo issue DEMO-30', '2013-11-04 09:28:07'),
(2367, 96, -1, 145, 'LOG IN', '2013-11-05 05:28:12'),
(2371, 96, -1, 145, 'LOG IN', '2013-11-05 07:29:19'),
(2372, 96, 1, 145, 'ADD Yongo issue comment DEMO-30', '2013-11-05 07:30:27'),
(2408, 96, -1, 145, 'LOG IN', '2013-11-05 11:17:09'),
(2409, 96, -1, 145, 'LOG OUT', '2013-11-05 11:17:13'),
(2420, 96, -1, 145, 'LOG IN', '2013-11-05 12:48:09'),
(2422, 96, -1, 145, 'LOG IN', '2013-11-05 13:15:30'),
(2423, 96, -1, 145, 'LOG IN', '2013-11-05 16:02:26'),
(2424, 96, 1, 145, 'ADD Yongo issue DEMO-31', '2013-11-05 16:03:05'),
(2452, 96, -1, 145, 'LOG IN', '2013-11-06 19:17:02'),
(2453, 96, -1, 145, 'LOG IN', '2013-11-06 19:19:02'),
(2454, 96, 1, 145, 'UPDATE Yongo issue DEMO-7', '2013-11-06 19:20:08'),
(2455, 96, 1, 145, 'ADD Yongo issue comment DEMO-7', '2013-11-06 19:21:02'),
(2456, 96, 1, 145, 'UPDATE Yongo issue DEMO-28', '2013-11-06 19:21:47'),
(2457, 96, 1, 145, 'Deactivate Yongo Time Tracking', '2013-11-06 19:23:55'),
(2458, 96, 1, 145, 'Activate Yongo Time Tracking', '2013-11-06 19:23:57'),
(2459, 96, -1, 145, 'LOG OUT', '2013-11-06 19:24:05'),
(2460, 96, -1, 145, 'LOG IN', '2013-11-06 19:25:33'),
(2461, 96, -1, 145, 'LOG IN', '2013-11-06 22:29:08'),
(2462, 96, 1, 145, 'ADD Yongo Project Main Project', '2013-11-06 22:29:47'),
(2463, 96, 1, 145, 'UPDATE Yongo Issue Type Scheme Default Issue Type Scheme', '2013-11-06 22:30:24'),
(2464, 96, 1, 145, 'UPDATE Yongo Field Configuration Default Field Configuration', '2013-11-06 22:33:19'),
(2468, 96, -1, 145, 'LOG IN', '2013-11-06 23:50:21'),
(2470, 96, -1, 145, 'LOG IN', '2013-11-07 02:38:39'),
(2471, 96, 4, 145, 'ADD Documentador space pilote', '2013-11-07 02:50:17'),
(2472, 96, 4, 145, 'ADD Documentador Entity Sources', '2013-11-07 02:50:59'),
(2473, 96, 4, 145, 'MOVE TO TRASH Documentador entity Sources', '2013-11-07 02:53:29'),
(2492, 96, -1, 145, 'LOG IN', '2013-11-07 13:47:14'),
(2501, 96, -1, 145, 'LOG IN', '2013-11-07 17:37:41'),
(2502, 96, -1, 145, 'LOG IN', '2013-11-07 17:38:14'),
(2503, 96, -1, 145, 'LOG IN', '2013-11-07 18:09:39'),
(2505, 96, -1, 145, 'LOG IN', '2013-11-07 22:32:04'),
(2515, 96, -1, 145, 'LOG IN', '2013-11-08 09:47:50'),
(2527, 96, -1, 145, 'LOG IN', '2013-11-08 11:15:28'),
(2537, 96, -1, 145, 'LOG IN', '2013-11-08 13:42:06'),
(2559, 96, -1, 145, 'LOG IN', '2013-11-08 14:03:02'),
(2585, 96, -1, 145, 'LOG IN', '2013-11-08 21:11:08'),
(2590, 96, -1, 145, 'LOG IN', '2013-11-09 18:16:18'),
(2638, 96, -1, 145, 'LOG IN', '2013-11-11 18:11:20'),
(2650, 96, -1, 145, 'LOG IN', '2013-11-11 22:39:03'),
(2651, 96, -1, 145, 'LOG IN', '2013-11-11 23:05:37'),
(2652, 96, -1, 145, 'LOG OUT', '2013-11-11 23:09:37'),
(2685, 96, -1, 145, 'LOG IN', '2013-11-12 12:45:24'),
(2750, 222, -1, 408, 'LOG IN', '2013-11-13 09:32:06'),
(2751, 222, 1, 408, 'ADD Yongo Project tt', '2013-11-13 09:32:54'),
(2752, 222, 1, 408, 'ADD Yongo issue TT-1', '2013-11-13 09:33:04'),
(2776, 96, -1, 145, 'LOG IN', '2013-11-13 13:35:47'),
(2800, 96, -1, 145, 'LOG IN', '2013-11-13 16:57:46'),
(3638, 1709, -1, 1900, 'LOG IN', '2013-12-07 12:42:24'),
(3639, 1709, 1, 1900, 'ADD Yongo Project test', '2013-12-07 12:42:43'),
(3640, 1709, 1, 1900, 'ADD Yongo issue TEST-1', '2013-12-07 12:43:05'),
(3641, 1709, -1, 1900, 'LOG OUT', '2013-12-07 12:43:23'),
(3984, 1716, -1, 1912, 'LOG IN', '2013-12-19 08:53:43'),
(3985, 1716, 1, 1912, 'ADD Yongo Project dsa', '2013-12-19 08:54:37'),
(3986, 1716, 1, 1912, 'ADD Yongo issue DSA-1', '2013-12-19 08:54:43'),
(3987, 1716, 4, 1912, 'ADD Documentador space q', '2013-12-19 08:55:10'),
(3988, 1716, 4, 1912, 'UPDATE Documentador entity q Home', '2013-12-19 08:55:22'),
(3990, 1716, -1, 1912, 'LOG OUT', '2013-12-19 09:02:46'),
(4224, 1724, -1, 1928, 'LOG IN', '2014-01-03 16:43:24'),
(4225, 1724, 1, 1928, 'ADD Yongo Project jack', '2014-01-03 16:43:38'),
(4226, 1724, 1, 1928, 'ADD Yongo issue JACK-1', '2014-01-03 17:43:53'),
(4227, 1724, -1, 1928, 'LOG OUT', '2014-01-03 16:44:31'),
(10478, 1958, -1, 1958, 'LOG OUT', '2014-06-26 12:00:27'),
(10479, 1959, -1, 2836, 'LOG IN', '2014-06-26 12:00:30'),
(10480, 1959, 1, 2836, 'ADD Yongo Issue Priority P1 (today, ASAP)', '2014-06-26 12:00:46'),
(10481, 1959, 1, 2836, 'ADD Yongo Issue Priority P2 (urgent)', '2014-06-26 12:01:11'),
(10482, 1959, 1, 2836, 'ADD Yongo Issue Priority P3 (before final delivery)', '2014-06-26 12:01:35'),
(10483, 1959, 1, 2836, 'ADD Yongo Issue Priority P4 (on customer request)', '2014-06-26 12:01:54'),
(10484, 1959, 1, 2836, 'ADD Yongo Issue Priority P5 (nice to have)', '2014-06-26 12:02:12'),
(10485, 1959, 1, 2836, 'ADD Yongo Issue Status UNCONFIRMED', '2014-06-26 12:02:38'),
(10486, 1959, 1, 2836, 'ADD Yongo Issue Status NEW', '2014-06-26 12:02:49'),
(10487, 1959, 1, 2836, 'ADD Yongo Issue Status ASSIGNED', '2014-06-26 12:02:57'),
(10488, 1959, 1, 2836, 'UPDATE Yongo Issue Status REOPENED', '2014-06-26 12:03:12'),
(10489, 1959, 1, 2836, 'UPDATE Yongo Issue Status RESOLVED', '2014-06-26 12:03:26'),
(10490, 1959, 1, 2836, 'ADD Yongo Issue Status VERIFIED', '2014-06-26 12:03:34'),
(10491, 1959, 1, 2836, 'UPDATE Yongo Issue Status CLOSED', '2014-06-26 12:03:44'),
(10492, 1959, 1, 2836, 'ADD Yongo Issue Resolution NO RESOLUTION', '2014-06-26 12:04:15'),
(10493, 1959, 1, 2836, 'UPDATE Yongo Issue Resolution FIXED', '2014-06-26 12:04:26'),
(10494, 1959, 1, 2836, 'ADD Yongo Issue Resolution INVALID', '2014-06-26 12:04:35'),
(10495, 1959, 1, 2836, 'UPDATE Yongo Issue Resolution WON''T FIX', '2014-06-26 12:04:49'),
(10496, 1959, 1, 2836, 'ADD Yongo Issue Resolution LATER', '2014-06-26 12:04:56'),
(10497, 1959, 1, 2836, 'ADD Yongo Issue Resolution REMIND', '2014-06-26 12:05:04'),
(10498, 1959, 1, 2836, 'UPDATE Yongo Issue Resolution DUPLICATE', '2014-06-26 12:05:17'),
(10499, 1959, 1, 2836, 'ADD Yongo Issue Resolution WORKSFORME', '2014-06-26 12:05:26'),
(10500, 1959, 1, 2836, 'ADD Yongo Issue Resolution MOVED', '2014-06-26 12:05:35'),
(10501, 1959, 1, 2836, 'ADD Yongo Issue Resolution Waiting Verification', '2014-06-26 12:05:45'),
(10502, 1959, 1, 2836, 'UPDATE Yongo Workflow Movidius Workflow', '2014-06-26 12:06:31'),
(10503, 1959, 1, 2836, 'ADD Yongo Workflow Step Unconfirmed', '2014-06-26 12:18:31'),
(10504, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 12:35:47'),
(10505, 1959, 1, 2836, 'UPDATE Yongo General Settings', '2014-06-26 12:40:47'),
(10506, 1959, 1, 2836, 'ADD Yongo issue code-1', '2014-06-26 14:53:00'),
(10507, 1959, 1, 2836, 'UPDATE Yongo Workflow Step Open', '2014-06-26 12:55:42'),
(10508, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 12:59:42'),
(10509, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:00:37'),
(10510, 1959, 1, 2836, 'ADD Yongo Workflow Step Move to Verification team', '2014-06-26 13:05:42'),
(10511, 1959, 1, 2836, 'UPDATE Yongo Workflow Step Move to Verification team', '2014-06-26 13:06:31'),
(10512, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:06:53'),
(10513, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Closed', '2014-06-26 13:07:26'),
(10514, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:07:49'),
(10515, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:08:17'),
(10516, 1959, 1, 2836, 'UPDATE Yongo Workflow Step Verification team', '2014-06-26 13:08:28'),
(10517, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:08:55'),
(10518, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:11:01'),
(10519, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:11:44'),
(10520, 1959, 1, 2836, 'ADD Yongo Workflow Step Assigned', '2014-06-26 13:17:43'),
(10521, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:18:06'),
(10522, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:18:25'),
(10523, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:19:31'),
(10524, 1959, 1, 2836, 'ADD Yongo issue code-2', '2014-06-26 15:20:10'),
(10525, 1959, 1, 2836, 'DELETE Yongo Issue Status Open', '2014-06-26 13:23:08'),
(10526, 1959, 1, 2836, 'DELETE Yongo Issue Resolution Cannot Reproduce', '2014-06-26 13:23:35'),
(10527, 1959, 1, 2836, 'DELETE Yongo Issue Resolution No Change Required', '2014-06-26 13:23:40'),
(10528, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:29:18'),
(10529, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:29:38'),
(10530, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:30:06'),
(10531, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Open as new', '2014-06-26 13:30:46'),
(10532, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:32:32'),
(10533, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:32:55'),
(10534, 1959, 1, 2836, 'ADD Yongo Workflow Transition', '2014-06-26 13:33:18'),
(10535, 1959, 1, 2836, 'DELETE Yongo issue code-1', '2014-06-26 15:33:43'),
(10536, 1959, 1, 2836, 'DELETE Yongo issue code-2', '2014-06-26 15:33:52'),
(10537, 1959, 1, 2836, 'ADD Yongo issue code-3', '2014-06-26 15:34:47'),
(10538, 1959, 1, 2836, 'ADD Yongo issue code-4', '2014-06-26 15:36:34'),
(10539, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Assign', '2014-06-26 13:44:58'),
(10540, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Assign', '2014-06-26 13:45:39'),
(10541, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Confirm', '2014-06-26 13:46:01'),
(10542, 1959, 1, 2836, 'ADD Yongo Screen Confirm Screen', '2014-06-26 13:53:03'),
(10543, 1959, 1, 2836, 'UPDATE Yongo Workflow Transition Confirm', '2014-06-26 13:54:08'),
(10544, 1959, -1, 2836, 'LOG IN', '2014-06-26 14:48:33'),
(10545, 1959, 1, 2836, 'ADD Yongo issue code-5', '2014-06-26 16:48:38');

-- --------------------------------------------------------

--
-- Table structure for table `general_mail_queue`
--

CREATE TABLE IF NOT EXISTS `general_mail_queue` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `from_address` varchar(50) NOT NULL,
  `to_address` varchar(50) NOT NULL,
  `reply_to_address` varchar(50) DEFAULT NULL,
  `subject` varchar(250) NOT NULL,
  `content` mediumtext NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1092 ;

--
-- Dumping data for table `general_mail_queue`
--

INSERT INTO `general_mail_queue` (`id`, `client_id`, `from_address`, `to_address`, `reply_to_address`, `subject`, `content`, `date_created`) VALUES
(1088, 1959, 'notification@ubirimi.com', 'vali@vali.com', NULL, 'UBR [Issue] - Issue UPDATED code-2', '<table>\n    <div style="padding: 10px; margin: 10px; width: 720px;">\n        <table width="100%">\n            <tr>\n                <td width="40">\n                    <a href="http://ubirimi.lan"><img src="http://ubirimi.lan/img/small.yongo.png" border="0" /></a>\n                </td>\n            </tr>\n        </table>\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n        </div>\n<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">\n    <a style="text-decoration: none; " href="http://movidius.ubirimi_net.lan/yongo/issue/3016">start</a>\n</div>\n\n<table width="100%" cellpadding="2" border="0">\n            <tr>\n                                <td>\n                Status: <del style="color: red;background: #fdd;	text-decoration: none;">NEW</del><ins style="color: green; background: #dfd; text-decoration: none;">In Progress</ins>            </td>\n            </tr>\n    </table>\n\n        <br />\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n            <br />\n            <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi 2.17.0</span>\n        </div>\n    </div>\n</table>', '2014-06-26 13:20:46'),
(1089, 1959, 'notification@ubirimi.com', 'vali@vali.com', NULL, 'UBR [Issue] - Issue UPDATED code-2', '<table>\n    <div style="padding: 10px; margin: 10px; width: 720px;">\n        <table width="100%">\n            <tr>\n                <td width="40">\n                    <a href="http://ubirimi.lan"><img src="http://ubirimi.lan/img/small.yongo.png" border="0" /></a>\n                </td>\n            </tr>\n        </table>\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n        </div>\n<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">\n    <a style="text-decoration: none; " href="http://movidius.ubirimi_net.lan/yongo/issue/3016">start</a>\n</div>\n\n<table width="100%" cellpadding="2" border="0">\n            <tr>\n                                <td>\n                Resolution: <ins style="color: green; background: #dfd; text-decoration: none;">Waiting Verification</ins>            </td>\n            </tr>\n            <tr>\n                                <td>\n                Fix version: <ins style="color: green; background: #dfd; text-decoration: none;">unspecified</ins>            </td>\n            </tr>\n            <tr>\n                                <td>\n                Status: <del style="color: red;background: #fdd;	text-decoration: none;">In Progress</del><ins style="color: green; background: #dfd; text-decoration: none;">RESOLVED</ins>            </td>\n            </tr>\n    </table>\n\n        <br />\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n            <br />\n            <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi 2.17.0</span>\n        </div>\n    </div>\n</table>', '2014-06-26 13:21:57'),
(1090, 1959, 'notification@ubirimi.com', 'vali@vali.com', NULL, 'UBR [Issue] - Issue DELETED code-1', '<table>\n    <div style="padding: 10px; margin: 10px; width: 720px;">\n        <table width="100%">\n            <tr>\n                <td width="40">\n                    <a href="http://ubirimi.lan"><img src="http://ubirimi.lan/img/small.yongo.png" border="0" /></a>\n                </td>\n            </tr>\n        </table>\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n        </div>\n<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">test</div>\n<br />\n\n<table width="100%" cellpadding="2" border="0">\n    <tr>\n        <td>This issue has been deleted.</td>\n    </tr>\n</table>\n\n        <br />\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n            <br />\n            <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi 2.17.0</span>\n        </div>\n    </div>\n</table>', '2014-06-26 13:33:43'),
(1091, 1959, 'notification@ubirimi.com', 'vali@vali.com', NULL, 'UBR [Issue] - Issue DELETED code-2', '<table>\n    <div style="padding: 10px; margin: 10px; width: 720px;">\n        <table width="100%">\n            <tr>\n                <td width="40">\n                    <a href="http://ubirimi.lan"><img src="http://ubirimi.lan/img/small.yongo.png" border="0" /></a>\n                </td>\n            </tr>\n        </table>\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n        </div>\n<div style="color: #333333;font: 17px Trebuchet MS, sans-serif;white-space: nowrap;padding-bottom: 5px;padding-top: 5px;text-align: left;padding-left: 2px;">start</div>\n<br />\n\n<table width="100%" cellpadding="2" border="0">\n    <tr>\n        <td>This issue has been deleted.</td>\n    </tr>\n</table>\n\n        <br />\n        <div>\n            <img src="http://ubirimi.lan/img/bg.page.png" />\n            <br />\n            <span style="color: #808080; font-size: 12px;">This message was sent by Ubirimi 2.17.0</span>\n        </div>\n    </div>\n</table>', '2014-06-26 13:33:52');

-- --------------------------------------------------------

--
-- Table structure for table `general_payment`
--

CREATE TABLE IF NOT EXISTS `general_payment` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `successful_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `amount` float NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `general_payment`
--

INSERT INTO `general_payment` (`id`, `client_id`, `successful_flag`, `amount`, `date_created`) VALUES
(2, 2, 1, 5, '2014-05-01 08:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `general_task_queue`
--

CREATE TABLE IF NOT EXISTS `general_task_queue` (
`id` bigint(20) unsigned NOT NULL,
  `type` int(11) NOT NULL,
  `data` mediumtext CHARACTER SET utf8 NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=253 ;

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sys_product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9547 ;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`id`, `client_id`, `sys_product_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1, 1, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(2, 1, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(3, 1, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(7, 3, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(8, 3, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(9, 3, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(10, 4, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(11, 4, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(12, 4, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(13, 5, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(14, 5, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(15, 5, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(16, 6, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(17, 6, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(18, 6, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(19, 7, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(20, 7, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(21, 7, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(22, 9, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(23, 9, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(24, 9, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(25, 10, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(26, 10, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(27, 10, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(28, 11, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(29, 11, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(30, 11, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(52, 19, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(53, 19, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(54, 19, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(61, 22, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(62, 22, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(63, 22, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(112, 40, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(113, 40, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(114, 40, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(148, 53, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(149, 53, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(150, 53, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(711, 0, 1, 'Administrators', 'The users in this group will have all the privileges', '0000-00-00 00:00:00', NULL),
(712, 0, 1, 'Developers', 'The users in this group will have some privileges', '0000-00-00 00:00:00', NULL),
(713, 0, 1, 'Users', 'The users in this group will have basic privileges', '0000-00-00 00:00:00', NULL),
(714, 0, 4, 'Documentador Administrators', 'Documentador Administrators', '0000-00-00 00:00:00', NULL),
(715, 0, 4, 'Documentador Users', 'Documentador Users', '0000-00-00 00:00:00', NULL),
(9444, 1940, 1, 'Administrators', 'The users in this group will have all the privileges', '2014-05-08 19:17:02', NULL),
(9445, 1940, 1, 'Developers', 'The users in this group will have some privileges', '2014-05-08 19:17:02', NULL),
(9446, 1940, 1, 'Users', 'The users in this group will have basic privileges', '2014-05-08 19:17:02', NULL),
(9447, 1940, 4, 'Documentador Administrators', 'Documentador Administrators', '2014-05-08 19:17:02', NULL),
(9448, 1940, 4, 'Documentador Users', 'Documentador Users', '2014-05-08 19:17:02', NULL),
(9454, 1942, 1, 'Administrators', 'The users in this group will have all the privileges', '2014-05-08 19:17:02', NULL),
(9455, 1942, 1, 'Developers', 'The users in this group will have some privileges', '2014-05-08 19:17:02', NULL),
(9456, 1942, 1, 'Users', 'The users in this group will have basic privileges', '2014-05-08 19:17:02', NULL),
(9457, 1942, 4, 'Documentador Administrators', 'Documentador Administrators', '2014-05-08 19:17:02', NULL),
(9458, 1942, 4, 'Documentador Users', 'Documentador Users', '2014-05-08 19:17:02', NULL),
(9464, 1944, 1, 'Administrators', 'The users in this group will have all the privileges', '2014-05-08 19:17:02', NULL),
(9465, 1944, 1, 'Developers', 'The users in this group will have some privileges', '2014-05-08 19:17:02', NULL),
(9466, 1944, 1, 'Users', 'The users in this group will have basic privileges', '2014-05-08 19:17:02', NULL),
(9467, 1944, 4, 'Documentador Administrators', 'Documentador Administrators', '2014-05-08 19:17:02', NULL),
(9468, 1944, 4, 'Documentador Users', 'Documentador Users', '2014-05-08 19:17:02', NULL),
(9542, 1959, 1, 'Administrators', 'The users in this group will have all the privileges', '2014-06-26 14:00:20', NULL),
(9543, 1959, 1, 'Developers', 'The users in this group will have some privileges', '2014-06-26 14:00:20', NULL),
(9544, 1959, 1, 'Users', 'The users in this group will have basic privileges', '2014-06-26 14:00:20', NULL),
(9545, 1959, 4, 'Documentador Administrators', 'Documentador Administrators', '2014-06-26 14:00:20', NULL),
(9546, 1959, 4, 'Documentador Users', 'Documentador Users', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_data`
--

CREATE TABLE IF NOT EXISTS `group_data` (
`id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10805 ;

--
-- Dumping data for table `group_data`
--

INSERT INTO `group_data` (`id`, `group_id`, `user_id`, `date_created`) VALUES
(1012, 711, 362, '0000-00-00 00:00:00'),
(1013, 712, 362, '0000-00-00 00:00:00'),
(1014, 713, 362, '0000-00-00 00:00:00'),
(1015, 714, 362, '0000-00-00 00:00:00'),
(1016, 715, 362, '0000-00-00 00:00:00'),
(9907, 9444, 2219, '2014-05-08 19:17:02'),
(9908, 9445, 2219, '2014-05-08 19:17:02'),
(9909, 9446, 2219, '2014-05-08 19:17:02'),
(9910, 9447, 2219, '2014-05-08 19:17:02'),
(9911, 9448, 2219, '2014-05-08 19:17:02'),
(9917, 9454, 2221, '2014-05-08 19:17:02'),
(9918, 9455, 2221, '2014-05-08 19:17:02'),
(9919, 9456, 2221, '2014-05-08 19:17:02'),
(9920, 9457, 2221, '2014-05-08 19:17:02'),
(9921, 9458, 2221, '2014-05-08 19:17:02'),
(9927, 9464, 2223, '2014-05-08 19:17:02'),
(9928, 9465, 2223, '2014-05-08 19:17:02'),
(9929, 9466, 2223, '2014-05-08 19:17:02'),
(9930, 9467, 2223, '2014-05-08 19:17:02'),
(9931, 9468, 2223, '2014-05-08 19:17:02'),
(10606, 9542, 2836, '2014-06-26 14:00:20'),
(10607, 9543, 2836, '2014-06-26 14:00:20'),
(10608, 9544, 2836, '2014-06-26 14:00:20'),
(10609, 9545, 2836, '2014-06-26 14:00:20'),
(10610, 9546, 2836, '2014-06-26 14:00:20'),
(10611, 9544, 2837, '2014-06-26 14:00:20'),
(10612, 9544, 2838, '2014-06-26 14:00:20'),
(10613, 9544, 2839, '2014-06-26 14:00:20'),
(10614, 9544, 2840, '2014-06-26 14:00:20'),
(10615, 9544, 2841, '2014-06-26 14:00:20'),
(10616, 9544, 2842, '2014-06-26 14:00:20'),
(10617, 9544, 2843, '2014-06-26 14:00:20'),
(10618, 9544, 2844, '2014-06-26 14:00:20'),
(10619, 9544, 2845, '2014-06-26 14:00:20'),
(10620, 9544, 2846, '2014-06-26 14:00:20'),
(10621, 9544, 2847, '2014-06-26 14:00:20'),
(10622, 9544, 2848, '2014-06-26 14:00:20'),
(10623, 9544, 2849, '2014-06-26 14:00:20'),
(10624, 9544, 2850, '2014-06-26 14:00:20'),
(10625, 9544, 2851, '2014-06-26 14:00:20'),
(10626, 9544, 2852, '2014-06-26 14:00:20'),
(10627, 9544, 2853, '2014-06-26 14:00:20'),
(10628, 9544, 2854, '2014-06-26 14:00:21'),
(10629, 9544, 2855, '2014-06-26 14:00:21'),
(10630, 9544, 2856, '2014-06-26 14:00:21'),
(10631, 9544, 2857, '2014-06-26 14:00:21'),
(10632, 9544, 2858, '2014-06-26 14:00:21'),
(10633, 9544, 2859, '2014-06-26 14:00:21'),
(10634, 9544, 2860, '2014-06-26 14:00:21'),
(10635, 9544, 2861, '2014-06-26 14:00:21'),
(10636, 9544, 2862, '2014-06-26 14:00:21'),
(10637, 9544, 2863, '2014-06-26 14:00:21'),
(10638, 9544, 2864, '2014-06-26 14:00:21'),
(10639, 9544, 2865, '2014-06-26 14:00:21'),
(10640, 9544, 2866, '2014-06-26 14:00:21'),
(10641, 9544, 2867, '2014-06-26 14:00:21'),
(10642, 9544, 2868, '2014-06-26 14:00:21'),
(10643, 9544, 2869, '2014-06-26 14:00:21'),
(10644, 9544, 2870, '2014-06-26 14:00:21'),
(10645, 9544, 2871, '2014-06-26 14:00:21'),
(10646, 9544, 2872, '2014-06-26 14:00:21'),
(10647, 9544, 2873, '2014-06-26 14:00:21'),
(10648, 9544, 2874, '2014-06-26 14:00:21'),
(10649, 9544, 2875, '2014-06-26 14:00:21'),
(10650, 9544, 2876, '2014-06-26 14:00:21'),
(10651, 9544, 2877, '2014-06-26 14:00:21'),
(10652, 9544, 2878, '2014-06-26 14:00:21'),
(10653, 9544, 2879, '2014-06-26 14:00:21'),
(10654, 9544, 2880, '2014-06-26 14:00:21'),
(10655, 9544, 2881, '2014-06-26 14:00:21'),
(10656, 9544, 2882, '2014-06-26 14:00:21'),
(10657, 9544, 2883, '2014-06-26 14:00:21'),
(10658, 9544, 2884, '2014-06-26 14:00:21'),
(10659, 9544, 2885, '2014-06-26 14:00:21'),
(10660, 9544, 2886, '2014-06-26 14:00:21'),
(10661, 9544, 2887, '2014-06-26 14:00:21'),
(10662, 9544, 2888, '2014-06-26 14:00:21'),
(10663, 9544, 2889, '2014-06-26 14:00:21'),
(10664, 9544, 2890, '2014-06-26 14:00:21'),
(10665, 9544, 2891, '2014-06-26 14:00:21'),
(10666, 9544, 2892, '2014-06-26 14:00:21'),
(10667, 9544, 2893, '2014-06-26 14:00:21'),
(10668, 9544, 2894, '2014-06-26 14:00:21'),
(10669, 9544, 2895, '2014-06-26 14:00:21'),
(10670, 9544, 2896, '2014-06-26 14:00:21'),
(10671, 9544, 2897, '2014-06-26 14:00:21'),
(10672, 9544, 2898, '2014-06-26 14:00:21'),
(10673, 9544, 2899, '2014-06-26 14:00:21'),
(10674, 9544, 2900, '2014-06-26 14:00:21'),
(10675, 9544, 2901, '2014-06-26 14:00:21'),
(10676, 9544, 2902, '2014-06-26 14:00:21'),
(10677, 9544, 2903, '2014-06-26 14:00:21'),
(10678, 9544, 2904, '2014-06-26 14:00:21'),
(10679, 9544, 2905, '2014-06-26 14:00:21'),
(10680, 9544, 2906, '2014-06-26 14:00:21'),
(10681, 9544, 2907, '2014-06-26 14:00:21'),
(10682, 9544, 2908, '2014-06-26 14:00:21'),
(10683, 9544, 2909, '2014-06-26 14:00:21'),
(10684, 9544, 2910, '2014-06-26 14:00:21'),
(10685, 9544, 2911, '2014-06-26 14:00:21'),
(10686, 9544, 2912, '2014-06-26 14:00:21'),
(10687, 9544, 2913, '2014-06-26 14:00:21'),
(10688, 9544, 2914, '2014-06-26 14:00:21'),
(10689, 9544, 2915, '2014-06-26 14:00:21'),
(10690, 9544, 2916, '2014-06-26 14:00:21'),
(10691, 9544, 2917, '2014-06-26 14:00:21'),
(10692, 9544, 2918, '2014-06-26 14:00:21'),
(10693, 9544, 2919, '2014-06-26 14:00:21'),
(10694, 9544, 2920, '2014-06-26 14:00:21'),
(10695, 9544, 2921, '2014-06-26 14:00:22'),
(10696, 9544, 2922, '2014-06-26 14:00:22'),
(10697, 9544, 2923, '2014-06-26 14:00:22'),
(10698, 9544, 2924, '2014-06-26 14:00:22'),
(10699, 9544, 2925, '2014-06-26 14:00:22'),
(10700, 9544, 2926, '2014-06-26 14:00:22'),
(10701, 9544, 2927, '2014-06-26 14:00:22'),
(10702, 9544, 2928, '2014-06-26 14:00:22'),
(10703, 9544, 2929, '2014-06-26 14:00:22'),
(10704, 9544, 2930, '2014-06-26 14:00:22'),
(10705, 9544, 2931, '2014-06-26 14:00:22'),
(10706, 9544, 2932, '2014-06-26 14:00:22'),
(10707, 9544, 2933, '2014-06-26 14:00:22'),
(10708, 9544, 2934, '2014-06-26 14:00:22'),
(10709, 9544, 2935, '2014-06-26 14:00:22'),
(10710, 9544, 2936, '2014-06-26 14:00:22'),
(10711, 9544, 2937, '2014-06-26 14:00:22'),
(10712, 9544, 2938, '2014-06-26 14:00:22'),
(10713, 9544, 2939, '2014-06-26 14:00:22'),
(10714, 9544, 2940, '2014-06-26 14:00:22'),
(10715, 9544, 2941, '2014-06-26 14:00:22'),
(10716, 9544, 2942, '2014-06-26 14:00:22'),
(10717, 9544, 2943, '2014-06-26 14:00:22'),
(10718, 9544, 2944, '2014-06-26 14:00:22'),
(10719, 9544, 2945, '2014-06-26 14:00:22'),
(10720, 9544, 2946, '2014-06-26 14:00:22'),
(10721, 9544, 2947, '2014-06-26 14:00:22'),
(10722, 9544, 2948, '2014-06-26 14:00:22'),
(10723, 9544, 2949, '2014-06-26 14:00:22'),
(10724, 9544, 2950, '2014-06-26 14:00:22'),
(10725, 9544, 2951, '2014-06-26 14:00:22'),
(10726, 9544, 2952, '2014-06-26 14:00:22'),
(10727, 9544, 2953, '2014-06-26 14:00:22'),
(10728, 9544, 2954, '2014-06-26 14:00:22'),
(10729, 9544, 2955, '2014-06-26 14:00:22'),
(10730, 9544, 2956, '2014-06-26 14:00:22'),
(10731, 9544, 2957, '2014-06-26 14:00:22'),
(10732, 9544, 2958, '2014-06-26 14:00:22'),
(10733, 9544, 2959, '2014-06-26 14:00:22'),
(10734, 9544, 2960, '2014-06-26 14:00:22'),
(10735, 9544, 2961, '2014-06-26 14:00:22'),
(10736, 9544, 2962, '2014-06-26 14:00:22'),
(10737, 9544, 2963, '2014-06-26 14:00:22'),
(10738, 9544, 2964, '2014-06-26 14:00:22'),
(10739, 9544, 2965, '2014-06-26 14:00:22'),
(10740, 9544, 2966, '2014-06-26 14:00:22'),
(10741, 9544, 2967, '2014-06-26 14:00:22'),
(10742, 9544, 2968, '2014-06-26 14:00:22'),
(10743, 9544, 2969, '2014-06-26 14:00:22'),
(10744, 9544, 2970, '2014-06-26 14:00:22'),
(10745, 9544, 2971, '2014-06-26 14:00:22'),
(10746, 9544, 2972, '2014-06-26 14:00:22'),
(10747, 9544, 2973, '2014-06-26 14:00:22'),
(10748, 9544, 2974, '2014-06-26 14:00:22'),
(10749, 9544, 2975, '2014-06-26 14:00:22'),
(10750, 9544, 2976, '2014-06-26 14:00:22'),
(10751, 9544, 2977, '2014-06-26 14:00:22'),
(10752, 9544, 2978, '2014-06-26 14:00:22'),
(10753, 9544, 2979, '2014-06-26 14:00:22'),
(10754, 9544, 2980, '2014-06-26 14:00:22'),
(10755, 9544, 2981, '2014-06-26 14:00:22'),
(10756, 9544, 2982, '2014-06-26 14:00:22'),
(10757, 9544, 2983, '2014-06-26 14:00:22'),
(10758, 9544, 2984, '2014-06-26 14:00:22'),
(10759, 9544, 2985, '2014-06-26 14:00:22'),
(10760, 9544, 2986, '2014-06-26 14:00:22'),
(10761, 9544, 2987, '2014-06-26 14:00:22'),
(10762, 9544, 2988, '2014-06-26 14:00:23'),
(10763, 9544, 2989, '2014-06-26 14:00:23'),
(10764, 9544, 2990, '2014-06-26 14:00:23'),
(10765, 9544, 2991, '2014-06-26 14:00:23'),
(10766, 9544, 2992, '2014-06-26 14:00:23'),
(10767, 9544, 2993, '2014-06-26 14:00:23'),
(10768, 9544, 2994, '2014-06-26 14:00:23'),
(10769, 9544, 2995, '2014-06-26 14:00:23'),
(10770, 9544, 2996, '2014-06-26 14:00:23'),
(10771, 9544, 2997, '2014-06-26 14:00:23'),
(10772, 9544, 2998, '2014-06-26 14:00:23'),
(10773, 9544, 2999, '2014-06-26 14:00:23'),
(10774, 9544, 3000, '2014-06-26 14:00:23'),
(10775, 9544, 3001, '2014-06-26 14:00:23'),
(10776, 9544, 3002, '2014-06-26 14:00:23'),
(10777, 9544, 3003, '2014-06-26 14:00:23'),
(10778, 9544, 3004, '2014-06-26 14:00:23'),
(10779, 9544, 3005, '2014-06-26 14:00:23'),
(10780, 9544, 3006, '2014-06-26 14:00:23'),
(10781, 9544, 3007, '2014-06-26 14:00:23'),
(10782, 9544, 3008, '2014-06-26 14:00:23'),
(10783, 9544, 3009, '2014-06-26 14:00:23'),
(10784, 9544, 3010, '2014-06-26 14:00:23'),
(10785, 9544, 3011, '2014-06-26 14:00:23'),
(10786, 9544, 3012, '2014-06-26 14:00:23'),
(10787, 9544, 3013, '2014-06-26 14:00:23'),
(10788, 9544, 3014, '2014-06-26 14:00:23'),
(10789, 9544, 3015, '2014-06-26 14:00:23'),
(10790, 9544, 3016, '2014-06-26 14:00:23'),
(10791, 9544, 3017, '2014-06-26 14:00:23'),
(10792, 9544, 3018, '2014-06-26 14:00:23'),
(10793, 9544, 3019, '2014-06-26 14:00:23'),
(10794, 9544, 3020, '2014-06-26 14:00:23'),
(10795, 9544, 3021, '2014-06-26 14:00:23'),
(10796, 9544, 3022, '2014-06-26 14:00:23'),
(10797, 9544, 3023, '2014-06-26 14:00:23'),
(10798, 9544, 3024, '2014-06-26 14:00:23'),
(10799, 9544, 3025, '2014-06-26 14:00:23'),
(10800, 9544, 3026, '2014-06-26 14:00:23'),
(10801, 9544, 3027, '2014-06-26 14:00:23'),
(10802, 9544, 3028, '2014-06-26 14:00:23'),
(10803, 9544, 3029, '2014-06-26 14:00:23'),
(10804, 9544, 3030, '2014-06-26 14:00:23');

-- --------------------------------------------------------

--
-- Table structure for table `help_customer`
--

CREATE TABLE IF NOT EXISTS `help_customer` (
`id` bigint(20) unsigned NOT NULL,
  `help_organization_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `help_filter`
--

CREATE TABLE IF NOT EXISTS `help_filter` (
`id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) NOT NULL,
  `created_user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `definition` text NOT NULL,
  `columns` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `help_organization`
--

CREATE TABLE IF NOT EXISTS `help_organization` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `help_organization`
--

INSERT INTO `help_organization` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1, 1701, 'cdi', NULL, '2014-06-17 14:23:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `help_organization_user`
--

CREATE TABLE IF NOT EXISTS `help_organization_user` (
`id` bigint(20) unsigned NOT NULL,
  `help_organization_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `help_reset_password`
--

CREATE TABLE IF NOT EXISTS `help_reset_password` (
`id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `token` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `help_sla`
--

CREATE TABLE IF NOT EXISTS `help_sla` (
`id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `start_condition` text NOT NULL,
  `stop_condition` text NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `help_sla_goal`
--

CREATE TABLE IF NOT EXISTS `help_sla_goal` (
`id` bigint(20) unsigned NOT NULL,
  `help_sla_id` bigint(20) unsigned NOT NULL,
  `definition` text NOT NULL,
  `definition_sql` text NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_attachment`
--

CREATE TABLE IF NOT EXISTS `issue_attachment` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=857 ;

--
-- Dumping data for table `issue_attachment`
--

INSERT INTO `issue_attachment` (`id`, `issue_id`, `user_id`, `name`, `size`, `date_created`) VALUES
(1, NULL, 1, 'index.php', 0, '2012-10-08 11:27:36'),
(2, NULL, 1, 'index.php', 0, '2012-10-08 11:27:51'),
(20, NULL, 33, 'S007E.pdf', 0, '2012-12-13 20:16:47'),
(21, NULL, 33, 'S007AC.pdf', 0, '2012-12-13 20:17:01'),
(22, NULL, 64, 'pit_projekt_buttons.zip', 0, '2013-01-04 16:13:41'),
(23, NULL, 64, 'pit_projekt_buttons.zip', 0, '2013-01-04 16:15:17'),
(24, NULL, 2, 'Screenshot from 2013-01-07 00:06:52.png', 0, '2013-01-06 23:54:30'),
(25, NULL, 2, 'Screenshot from 2013-01-07 00:06:52.png', 0, '2013-01-06 23:55:13'),
(26, NULL, 2, 'FD Leave requests.pdf', 0, '2013-01-07 08:46:37'),
(27, 98, 2, 'FO WerkplekPlanning v1.pdf', 508420, '2013-01-07 08:48:03'),
(28, 98, 2, 'Testare PHP 5 V20110815_NOU.docx', 35802, '2013-01-07 09:55:29'),
(30, 99, 2, 'Testare PHP 5 V20110815_NOU.docx', 35802, '2013-01-07 09:58:13'),
(42, 149, 2, 'Iosif Anca - 100 de schite pentru studiu biblic (vol. 1).pdf', 659599, '2013-01-22 12:19:52'),
(49, NULL, 75, 'Quality_Plan.accdb', 3203072, '2013-02-07 14:27:24'),
(51, NULL, 75, 'Quality_Plan.accdb', 3551232, '2013-02-08 02:34:40'),
(54, NULL, 75, 'Specification for Q.xlsx', 0, '2013-02-20 23:55:46'),
(55, NULL, 75, 'Specification for Q.doc', 0, '2013-02-20 23:56:02'),
(56, NULL, 75, 'Quality_Plan_Latest.accdb', 0, '2013-02-20 23:56:40'),
(57, NULL, 3, 'Iosif Anca - 100 de schite pentru studiu biblic (vol. 1).pdf', 0, '2013-02-21 09:29:46'),
(138, 366, 3, 'FO Zelfmatching 2.pdf', 777575, '2013-06-24 09:37:03'),
(139, 366, 3, 'yongo.sql', 1950808, '2013-06-24 09:38:35'),
(232, 452, 2, 'flavius 01.jpg', 351393, '2013-08-09 15:17:01'),
(234, 433, 3, 'DSCN1250.JPG', 6409637, '2013-08-09 15:26:46'),
(235, 433, 3, 'DSCN1212.JPG', 5906466, '2013-08-09 15:27:29'),
(239, NULL, 2, 'flavius 01.jpg', 351393, '2013-08-09 16:24:35'),
(240, NULL, 2, 'flavius 01.jpg', 351393, '2013-08-09 16:27:03'),
(241, NULL, 2, 'pass.txt', 965, '2013-08-09 16:27:30'),
(242, 266, 3, '3ad3930.jpg', 10025, '2013-08-09 22:40:09'),
(243, NULL, 164, 'Magical Snap - 2013.08.10 18.04 - 001.png', 154798, '2013-08-10 18:54:34'),
(244, NULL, 164, 'Magical Snap - 2013.08.10 18.05 - 002.png', 152251, '2013-08-10 18:54:39'),
(247, NULL, 164, 'Magical Snap - 2013.08.10 18.18 - 003.png', 163034, '2013-08-10 19:07:40'),
(249, NULL, 164, 'Magical Snap - 2013.08.10 18.35 - 004.png', 147038, '2013-08-10 19:25:15'),
(252, NULL, 174, 't1.jpg', 26801, '2013-08-12 13:22:34'),
(253, NULL, 174, 't2.jpg', 27115, '2013-08-12 13:22:34'),
(256, NULL, 174, 't6.jpg', 81350, '2013-08-12 16:06:12'),
(259, NULL, 174, 'a.jpg', 53183, '2013-08-13 10:21:02'),
(261, NULL, 174, 'art.jpg', 54693, '2013-08-13 10:26:07'),
(265, NULL, 164, 'Magical Snap - 2013.08.13 15.45 - 002.png', 162170, '2013-08-13 16:35:15'),
(273, 548, 3, 'yongo.sql', 2406441, '2013-08-13 15:28:44'),
(274, 548, 3, '1.jpg', 1066914, '2013-08-13 15:29:04'),
(278, 553, 3, 'img-1213.JPG', 3035217, '2013-08-13 18:10:55'),
(286, 555, 3, 'img-1197.JPG', 3618700, '2013-08-13 18:14:18'),
(287, 556, 2, 'francisc-01.jpg', 161753, '2013-08-13 18:14:21'),
(288, 556, 2, 'flavius-01.jpg', 351393, '2013-08-13 18:14:21'),
(289, 556, 2, 'francisc-copy.jpg', 403946, '2013-08-13 18:14:22'),
(290, 557, 3, 'img-1213.JPG', 3035217, '2013-08-13 18:14:28'),
(291, 558, 2, 'img-1543.png', 786432, '2013-08-13 18:14:49'),
(292, 559, 3, 'cimg0004.jpg', 31853, '2013-08-13 18:36:18'),
(293, 559, 3, 'cimg0006.jpg', 30767, '2013-08-13 18:36:18'),
(294, 559, 3, 'cimg0008.jpg', 34148, '2013-08-13 18:36:18'),
(295, 559, 3, 'cimg0011.jpg', 32123, '2013-08-13 18:36:18'),
(296, 559, 3, 'cimg0014.jpg', 27978, '2013-08-13 18:36:18'),
(297, 559, 3, 'cimg0002.jpg', 31955, '2013-08-13 18:36:18'),
(298, 559, 3, 'cimg0013.jpg', 27090, '2013-08-13 18:36:18'),
(319, NULL, 164, 'magical-snap-2013-08-14-16-32-012.png', 171320, '2013-08-14 17:25:01'),
(323, NULL, 2, 'img-1185.JPG', 3564641, '2013-08-14 12:49:45'),
(339, 598, 164, 'magical-snap-2013-08-19-11-03-005.png', 152590, '2013-08-19 11:52:34'),
(349, 608, 3, 'yongo.sql', 2590984, '2013-08-20 13:56:30'),
(350, 608, 3, 'vlcsnap-2013-06-30-23h06m59s13.png', 367647, '2013-08-20 13:58:43'),
(380, 525, 2, 'francisc-02.jpg', 470449, '2013-08-27 12:08:01'),
(384, 525, 2, 'flavius-01.jpg', 351393, '2013-08-27 17:49:43'),
(385, 525, 2, 'francisc.jpg', 403946, '2013-08-27 17:49:43'),
(437, 848, 3, 'captura.png', 44725, '2013-10-02 12:55:24'),
(478, 1072, 137, '2013-10-15-1548.png', 14336, '2013-10-15 16:42:53'),
(484, 1090, 164, '0000461.docx', 188649, '2013-10-17 14:00:28'),
(486, 1093, 164, '0000462.docx', 2815767, '2013-10-17 15:53:58'),
(563, NULL, 145, '1-dn006097.pdf', 2752512, '2013-10-30 04:24:32'),
(600, 1336, 255, 'bizspark-startup.jpg', 223698, '2013-10-31 08:34:38'),
(706, 1997, 2, 'family-photos04.jpg', 163704, '2014-02-10 22:53:04'),
(746, NULL, 389, '20140324-151129-00015924.mp4', 0, '2014-03-25 00:19:33'),
(756, 2494, 3, 'scan.jpg', 286253, '2014-04-03 21:44:25'),
(761, NULL, 389, '20140409-210821-00636041.mp4', 14385152, '2014-04-09 21:13:01'),
(762, NULL, 389, '20140409-224350-00194960.mp4', 0, '2014-04-09 22:49:44'),
(775, NULL, 389, '20140417-224620-00318152.mp4', 0, '2014-04-17 22:46:32'),
(776, NULL, 389, '20140417-225501-00939187.mp4', 0, '2014-04-17 22:53:29'),
(780, NULL, 389, '20140421-205623-00884735.mp4', 0, '2014-04-21 21:03:35'),
(781, NULL, 389, '20140421-204423-00729730.mp4', 0, '2014-04-21 21:04:01'),
(788, NULL, 389, '20140425-152838-00006988.mp4', 0, '2014-04-25 15:32:06'),
(790, NULL, 389, '20140428-155213-00351357.mp4', 45842432, '2014-04-28 16:01:10'),
(797, NULL, 389, '20140503-175030-00160752.mp4', 0, '2014-05-03 18:32:42'),
(798, NULL, 389, '20140503-184601-00710645.mp4', 0, '2014-05-03 18:46:19'),
(800, NULL, 389, '20140506-201456-00066062.mp4', 0, '2014-05-06 20:26:28'),
(801, NULL, 389, '20140506-222839-00292859.mp4', 116146176, '2014-05-06 22:35:19'),
(804, 2832, 2200, 'manufacturer-logos.zip', 30094, '2014-05-19 17:51:34'),
(805, 2849, 2200, 'screen-shot-2014-05-22-at-12-27-16-am.png', 12780, '2014-05-21 22:32:50'),
(855, NULL, 2238, '5160.pdf', 106530, '2014-06-17 14:03:09');

-- --------------------------------------------------------

--
-- Table structure for table `issue_comment`
--

CREATE TABLE IF NOT EXISTS `issue_comment` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `content` mediumtext NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1782 ;

--
-- Dumping data for table `issue_comment`
--

INSERT INTO `issue_comment` (`id`, `issue_id`, `user_id`, `content`, `date_created`, `date_updated`) VALUES
(93, 0, 96, 'test', '2013-02-05 06:34:35', NULL),
(94, 0, 96, 'asdfasdf', '2013-02-05 06:35:16', NULL),
(95, 0, 96, 'test', '2013-02-05 07:34:52', NULL),
(96, 0, 96, 'test', '2013-02-05 07:36:27', NULL),
(101, 0, 96, 'test', '2013-02-05 14:24:22', NULL),
(102, 0, 96, 'test', '2013-02-05 14:25:09', NULL),
(103, 0, 96, 'Test', '2013-02-05 14:43:21', NULL),
(104, 0, 96, 'test', '2013-02-05 14:47:11', NULL),
(1132, 2087, 1892, '<script>alert(1)</script>', '2014-02-13 14:30:51', NULL),
(1133, 2087, 1892, '<script>alert(1);</script>', '2014-02-13 14:32:03', NULL),
(1134, 2087, 1892, '<div><script type="text/javascript">alert(1)</script></div>', '2014-02-13 14:34:56', '2014-02-13 14:53:50'),
(1676, 2766, 1892, 'alert("did you fix this retrads?");', '2014-05-04 11:57:21', NULL),
(1677, 2767, 1892, 'alert("srsly.. all this shit is full of xss");', '2014-05-04 11:58:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_component`
--

CREATE TABLE IF NOT EXISTS `issue_component` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `project_component_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1351 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_custom_field_data`
--

CREATE TABLE IF NOT EXISTS `issue_custom_field_data` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `field_id` bigint(20) unsigned NOT NULL,
  `value` mediumtext NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=298 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_history`
--

CREATE TABLE IF NOT EXISTS `issue_history` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `by_user_id` bigint(20) unsigned NOT NULL,
  `field` varchar(20) NOT NULL,
  `old_value` mediumtext,
  `new_value` mediumtext,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9048 ;

--
-- Dumping data for table `issue_history`
--

INSERT INTO `issue_history` (`id`, `issue_id`, `by_user_id`, `field`, `old_value`, `new_value`, `date_created`) VALUES
(167, 0, 74, 'assignee', ' ', ' ', '2013-01-19 20:50:01'),
(6526, 2087, 1892, 'description', 'ewq', '<script>alert(1);</script>', '2014-02-13 14:31:31'),
(6527, 2087, 1892, 'environment', 'ewq', '<script>alert(1);</script>', '2014-02-13 14:31:31'),
(6528, 2087, 1892, 'description', '<script>alert(1);</script>', '<script>alert(1)</script>', '2014-02-13 14:31:56'),
(6529, 2087, 1892, 'environment', '<script>alert(1);</script>', '<script>alert(1)</script>', '2014-02-13 14:31:56');

-- --------------------------------------------------------

--
-- Table structure for table `issue_link`
--

CREATE TABLE IF NOT EXISTS `issue_link` (
`id` bigint(20) unsigned NOT NULL,
  `parent_issue_id` bigint(20) unsigned NOT NULL,
  `issue_link_type_id` bigint(20) unsigned NOT NULL,
  `link_type` varchar(7) NOT NULL,
  `child_issue_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=144 ;

--
-- Dumping data for table `issue_link`
--

INSERT INTO `issue_link` (`id`, `parent_issue_id`, `issue_link_type_id`, `link_type`, `child_issue_id`, `date_created`) VALUES
(1, 261, 1, 'outward', 5, '2013-03-16 13:51:56'),
(2, 267, 1, 'outward', 4, '2013-03-16 14:07:44'),
(3, 379, 345, 'outward', 340, '2013-07-03 16:05:45'),
(4, 380, 345, 'outward', 340, '2013-07-03 16:07:38'),
(5, 380, 345, 'outward', 379, '2013-07-03 16:07:38'),
(6, 593, 345, 'outward', 601, '2013-08-19 18:55:26'),
(7, 596, 345, 'outward', 485, '2013-08-20 15:46:05'),
(8, 736, 487, 'inward', 691, '2013-09-02 16:55:25'),
(9, 792, 346, 'inward', 793, '2013-09-27 16:16:59'),
(10, 830, 346, 'inward', 810, '2013-10-08 16:39:32'),
(11, 829, 345, 'outward', 793, '2013-10-08 17:48:20'),
(12, 1100, 345, 'outward', 1096, '2013-10-17 17:14:20'),
(13, 1080, 629, 'outward', 1157, '2013-10-21 21:01:10'),
(14, 1083, 629, 'outward', 1028, '2013-10-21 21:09:35'),
(15, 1083, 629, 'outward', 1029, '2013-10-21 21:09:35'),
(16, 1083, 629, 'outward', 1030, '2013-10-21 21:09:35'),
(17, 1083, 629, 'outward', 1033, '2013-10-21 21:09:35'),
(18, 1021, 629, 'outward', 1195, '2013-10-23 07:36:54'),
(19, 1252, 629, 'outward', 1253, '2013-10-24 12:18:03'),
(20, 1252, 629, 'outward', 1254, '2013-10-24 12:18:03'),
(21, 1252, 629, 'outward', 1255, '2013-10-24 12:18:03'),
(22, 1252, 629, 'outward', 1256, '2013-10-24 12:18:03'),
(24, 1247, 629, 'outward', 1249, '2013-10-24 12:38:27'),
(25, 1247, 629, 'outward', 1250, '2013-10-24 12:38:27'),
(26, 1247, 629, 'outward', 1251, '2013-10-24 12:38:27'),
(28, 1247, 629, 'outward', 1258, '2013-10-24 12:51:56'),
(29, 1239, 630, 'outward', 1179, '2013-10-24 13:04:46'),
(30, 1042, 629, 'outward', 1242, '2013-10-24 13:55:02'),
(31, 1245, 629, 'outward', 1017, '2013-10-24 14:13:42'),
(32, 1245, 629, 'outward', 1025, '2013-10-24 14:13:42'),
(33, 1245, 629, 'outward', 1033, '2013-10-24 14:13:42'),
(34, 1245, 629, 'outward', 1155, '2013-10-24 14:13:42'),
(35, 1245, 629, 'outward', 1164, '2013-10-24 14:13:42'),
(36, 1245, 629, 'outward', 1166, '2013-10-24 14:13:42'),
(37, 1245, 629, 'outward', 1168, '2013-10-24 14:13:42'),
(38, 1245, 629, 'outward', 1178, '2013-10-24 14:13:42'),
(39, 1245, 629, 'outward', 1183, '2013-10-24 14:13:42'),
(40, 1245, 629, 'outward', 1207, '2013-10-24 14:13:42'),
(41, 1245, 629, 'outward', 1209, '2013-10-24 14:13:42'),
(42, 1245, 629, 'outward', 1210, '2013-10-24 14:13:42'),
(43, 1245, 629, 'outward', 1236, '2013-10-24 14:13:42'),
(44, 1245, 629, 'outward', 1237, '2013-10-24 14:13:42'),
(45, 1245, 629, 'outward', 1238, '2013-10-24 14:13:42'),
(46, 1245, 629, 'outward', 1240, '2013-10-24 14:13:42'),
(47, 1245, 629, 'outward', 1241, '2013-10-24 14:13:42'),
(48, 1245, 629, 'outward', 1242, '2013-10-24 14:13:42'),
(49, 1258, 629, 'outward', 1011, '2013-10-24 14:15:17'),
(50, 1258, 629, 'outward', 1013, '2013-10-24 14:15:17'),
(51, 1258, 629, 'outward', 1021, '2013-10-24 14:15:17'),
(52, 1258, 629, 'outward', 1157, '2013-10-24 14:15:17'),
(53, 1258, 629, 'outward', 1193, '2013-10-24 14:15:17'),
(54, 1258, 629, 'outward', 1194, '2013-10-24 14:15:17'),
(55, 1258, 629, 'outward', 1195, '2013-10-24 14:15:17'),
(56, 1258, 629, 'outward', 1196, '2013-10-24 14:15:17'),
(57, 1258, 629, 'outward', 1198, '2013-10-24 14:15:17'),
(58, 1258, 629, 'outward', 1208, '2013-10-24 14:15:17'),
(59, 1258, 629, 'outward', 1243, '2013-10-24 14:15:17'),
(60, 1249, 629, 'outward', 1008, '2013-10-24 14:16:26'),
(61, 1249, 629, 'outward', 1019, '2013-10-24 14:16:26'),
(62, 1249, 629, 'outward', 1024, '2013-10-24 14:16:26'),
(63, 1249, 629, 'outward', 1026, '2013-10-24 14:16:26'),
(64, 1249, 629, 'outward', 1027, '2013-10-24 14:16:26'),
(65, 1249, 629, 'outward', 1037, '2013-10-24 14:16:26'),
(66, 1249, 629, 'outward', 1042, '2013-10-24 14:16:26'),
(67, 1249, 629, 'outward', 1161, '2013-10-24 14:16:26'),
(68, 1249, 629, 'outward', 1162, '2013-10-24 14:16:26'),
(69, 1249, 629, 'outward', 1179, '2013-10-24 14:16:26'),
(70, 1249, 629, 'outward', 1180, '2013-10-24 14:16:26'),
(71, 1249, 629, 'outward', 1244, '2013-10-24 14:16:26'),
(72, 1250, 629, 'outward', 1158, '2013-10-24 14:22:45'),
(73, 1250, 629, 'outward', 1257, '2013-10-24 14:22:45'),
(74, 1251, 629, 'outward', 1009, '2013-10-24 14:25:01'),
(75, 1251, 629, 'outward', 1010, '2013-10-24 14:25:01'),
(76, 1251, 629, 'outward', 1012, '2013-10-24 14:25:01'),
(77, 1251, 629, 'outward', 1018, '2013-10-24 14:25:01'),
(78, 1251, 629, 'outward', 1035, '2013-10-24 14:25:01'),
(79, 1251, 629, 'outward', 1041, '2013-10-24 14:25:01'),
(80, 1251, 629, 'outward', 1080, '2013-10-24 14:25:01'),
(81, 1251, 629, 'outward', 1083, '2013-10-24 14:25:01'),
(82, 1251, 629, 'outward', 1085, '2013-10-24 14:25:01'),
(83, 1251, 629, 'outward', 1086, '2013-10-24 14:25:01'),
(84, 1251, 629, 'outward', 1239, '2013-10-24 14:25:01'),
(85, 1253, 629, 'outward', 1182, '2013-10-24 14:26:04'),
(86, 1254, 629, 'outward', 1184, '2013-10-24 14:26:29'),
(87, 1254, 629, 'outward', 1191, '2013-10-24 14:26:29'),
(88, 1259, 629, 'outward', 1254, '2013-10-24 14:32:08'),
(89, 1261, 629, 'outward', 1258, '2013-10-25 12:50:13'),
(90, 1251, 629, 'outward', 1276, '2013-10-27 07:34:34'),
(91, 1278, 629, 'outward', 1249, '2013-10-28 10:40:29'),
(92, 1277, 629, 'outward', 1251, '2013-10-28 11:57:47'),
(93, 1287, 629, 'outward', 1245, '2013-10-29 04:54:07'),
(94, 1292, 629, 'outward', 1249, '2013-10-29 11:59:31'),
(97, 1294, 629, 'outward', 1245, '2013-10-29 12:33:39'),
(98, 1295, 629, 'outward', 1293, '2013-10-29 13:04:06'),
(99, 1295, 629, 'outward', 1249, '2013-10-29 13:05:01'),
(100, 1296, 629, 'outward', 1256, '2013-10-29 13:35:07'),
(101, 1308, 629, 'outward', 1245, '2013-10-30 09:57:17'),
(102, 1311, 629, 'outward', 1254, '2013-10-30 10:31:32'),
(103, 1325, 629, 'outward', 1251, '2013-10-30 16:26:03'),
(104, 1324, 629, 'outward', 1251, '2013-10-30 16:26:27'),
(105, 1245, 629, 'outward', 1326, '2013-10-30 16:29:32'),
(106, 1245, 629, 'outward', 1321, '2013-10-30 16:32:14'),
(107, 1245, 629, 'outward', 1327, '2013-10-30 16:37:27'),
(108, 1251, 629, 'outward', 1176, '2013-10-30 16:40:07'),
(109, 1330, 629, 'outward', 1245, '2013-10-31 05:22:42'),
(112, 1331, 629, 'outward', 1258, '2013-10-31 06:08:23'),
(113, 1339, 629, 'outward', 1245, '2013-11-01 05:51:25'),
(114, 1361, 629, 'outward', 1250, '2013-11-04 12:43:12'),
(115, 1395, 629, 'outward', 1258, '2013-11-07 11:11:40'),
(116, 1394, 629, 'outward', 1258, '2013-11-07 11:12:05'),
(117, 1396, 629, 'outward', 1251, '2013-11-07 11:13:36'),
(118, 1401, 629, 'outward', 1249, '2013-11-08 06:05:49'),
(119, 1386, 629, 'outward', 1258, '2013-11-08 11:46:47'),
(120, 1422, 629, 'outward', 1256, '2013-11-11 06:27:54'),
(121, 1428, 629, 'outward', 1250, '2013-11-12 09:57:48'),
(122, 1427, 629, 'outward', 1258, '2013-11-12 10:04:04'),
(123, 1426, 629, 'outward', 1258, '2013-11-12 10:11:59'),
(124, 1440, 345, 'outward', 1438, '2013-11-12 19:55:47'),
(125, 1441, 346, 'inward', 1433, '2013-11-12 20:04:29'),
(126, 1617, 346, 'inward', 1613, '2013-12-17 14:15:23'),
(127, 2086, 6733, 'outward', 2052, '2014-02-13 14:05:45'),
(128, 2052, 6733, 'outward', 2073, '2014-02-13 14:07:36'),
(129, 2052, 6733, 'outward', 2086, '2014-02-13 14:15:37'),
(130, 2086, 6734, 'outward', 2052, '2014-02-13 14:16:21'),
(131, 2052, 6734, 'inward', 2073, '2014-02-13 14:17:35'),
(132, 1543, 6735, 'inward', 1545, '2014-02-13 21:56:50'),
(133, 2323, 277, 'outward', 2336, '2014-03-19 10:49:22'),
(134, 2335, 277, 'outward', 2336, '2014-03-19 11:01:50'),
(135, 2321, 277, 'outward', 2325, '2014-03-19 11:13:56'),
(136, 2353, 277, 'outward', 2336, '2014-03-19 13:29:26'),
(137, 2338, 277, 'outward', 2336, '2014-03-19 15:50:42'),
(138, 2429, 346, 'inward', 2423, '2014-03-28 18:00:54'),
(139, 2431, 345, 'outward', 2430, '2014-03-28 18:07:57'),
(140, 2404, 346, 'inward', 2434, '2014-03-28 18:29:19'),
(141, 2521, 345, 'outward', 2518, '2014-04-04 12:48:29'),
(142, 2501, 346, 'inward', 2517, '2014-04-04 13:03:15'),
(143, 2876, 629, 'outward', 2790, '2014-05-23 12:30:04');

-- --------------------------------------------------------

--
-- Table structure for table `issue_link_type`
--

CREATE TABLE IF NOT EXISTS `issue_link_type` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `outward_description` varchar(200) NOT NULL,
  `inward_description` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7769 ;

--
-- Dumping data for table `issue_link_type`
--

INSERT INTO `issue_link_type` (`id`, `client_id`, `name`, `outward_description`, `inward_description`, `date_created`, `date_updated`) VALUES
(1, 2, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(2, 2, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(3, 2, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(4, 2, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(5, 12, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(6, 12, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(7, 12, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(8, 12, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(9, 13, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(10, 13, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(11, 13, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(12, 13, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(13, 14, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(14, 14, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(15, 14, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(16, 14, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(17, 15, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(18, 15, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(19, 15, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(20, 15, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(21, 16, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(22, 16, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(23, 16, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(24, 16, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(25, 17, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(26, 17, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(27, 17, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(28, 17, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(29, 18, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(30, 18, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(31, 18, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(32, 18, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(33, 20, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(34, 20, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(35, 20, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(36, 20, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(37, 21, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(38, 21, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(39, 21, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(40, 21, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(41, 23, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(42, 23, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(43, 23, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(44, 23, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(45, 24, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(46, 24, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(47, 24, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(48, 24, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(49, 25, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(50, 25, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(51, 25, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(52, 25, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(53, 26, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(54, 26, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(55, 26, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(56, 26, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(57, 28, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(58, 28, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(59, 28, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(60, 28, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(61, 29, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(62, 29, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(63, 29, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(64, 29, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(65, 30, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(66, 30, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(67, 30, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(68, 30, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(69, 31, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(70, 31, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(71, 31, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(72, 31, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(73, 32, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(74, 32, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(75, 32, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(76, 32, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(77, 33, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(78, 33, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(79, 33, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(80, 33, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(81, 34, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(82, 34, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(83, 34, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(84, 34, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(85, 35, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(86, 35, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(87, 35, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(88, 35, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(89, 36, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(90, 36, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(91, 36, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(92, 36, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(93, 37, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(94, 37, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(95, 37, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(96, 37, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(97, 38, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(98, 38, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(99, 38, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(100, 38, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(101, 39, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(102, 39, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(103, 39, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(104, 39, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(105, 41, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(106, 41, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(107, 41, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(108, 41, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(109, 42, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(110, 42, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(111, 42, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(112, 42, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(113, 43, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(114, 43, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(115, 43, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(116, 43, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(117, 44, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(118, 44, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(119, 44, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(120, 44, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(121, 45, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(122, 45, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(123, 45, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(124, 45, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(125, 47, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(126, 47, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(127, 47, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(128, 47, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(129, 48, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(130, 48, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(131, 48, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(132, 48, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(133, 49, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(134, 49, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(135, 49, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(136, 49, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(137, 50, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(138, 50, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(139, 50, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(140, 50, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(141, 51, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(142, 51, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(143, 51, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(144, 51, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(145, 52, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(146, 52, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(147, 52, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(148, 52, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(149, 54, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(150, 54, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(151, 54, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(152, 54, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(153, 55, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(154, 55, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(155, 55, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(156, 55, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(157, 56, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(158, 56, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(159, 56, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(160, 56, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(161, 57, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(162, 57, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(163, 57, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(164, 57, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(165, 58, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(166, 58, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(167, 58, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(168, 58, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(169, 59, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(170, 59, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(171, 59, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(172, 59, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(173, 60, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(174, 60, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(175, 60, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(176, 60, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(177, 61, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(178, 61, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(179, 61, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(180, 61, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(181, 62, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(182, 62, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(183, 62, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(184, 62, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(185, 63, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(186, 63, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(187, 63, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(188, 63, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(189, 64, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(190, 64, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(191, 64, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(192, 64, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(193, 65, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(194, 65, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(195, 65, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(196, 65, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(197, 66, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(198, 66, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(199, 66, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(200, 66, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(201, 67, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(202, 67, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(203, 67, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(204, 67, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(205, 68, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(206, 68, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(207, 68, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(208, 68, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(209, 69, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(210, 69, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(211, 69, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(212, 69, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(213, 70, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(214, 70, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(215, 70, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(216, 70, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(217, 71, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(218, 71, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(219, 71, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(220, 71, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(221, 72, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(222, 72, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(223, 72, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(224, 72, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(225, 73, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(226, 73, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(227, 73, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(228, 73, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(229, 74, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(230, 74, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(231, 74, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(232, 74, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(233, 75, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(234, 75, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(235, 75, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(236, 75, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(237, 76, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(238, 76, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(239, 76, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(240, 76, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(241, 77, 'Related', 'relates to', 'relates to', '2013-03-16 13:50:57', NULL),
(242, 77, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-16 13:50:57', NULL),
(243, 77, 'Block', 'blocks', 'is blocked by', '2013-03-16 13:50:57', NULL),
(244, 77, 'Clone', 'clones', 'is cloned by', '2013-03-16 13:50:57', NULL),
(245, 82, 'Relates', 'relates to', 'relates to', '2013-03-28 10:09:38', NULL),
(246, 82, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-28 10:09:38', NULL),
(247, 82, 'Blocks', 'blocks', 'is blocked by', '2013-03-28 10:09:38', NULL),
(248, 82, 'Cloners', 'clones', 'is cloned by', '2013-03-28 10:09:38', NULL),
(249, 81, 'Relates', 'relates to', 'relates to', '2013-03-27 12:12:14', NULL),
(250, 81, 'Duplicate', 'duplicates', 'is duplicated by', '2013-03-27 12:12:14', NULL),
(251, 81, 'Blocks', 'blocks', 'is blocked by', '2013-03-27 12:12:14', NULL),
(252, 81, 'Cloners', 'clones', 'is cloned by', '2013-03-27 12:12:14', NULL),
(253, 83, 'Relates', 'relates to', 'relates to', '2013-04-12 10:26:13', NULL),
(254, 83, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-12 10:26:13', NULL),
(255, 83, 'Blocks', 'blocks', 'is blocked by', '2013-04-12 10:26:13', NULL),
(256, 83, 'Cloners', 'clones', 'is cloned by', '2013-04-12 10:26:13', NULL),
(257, 84, 'Relates', 'relates to', 'relates to', '2013-04-12 10:37:17', NULL),
(258, 84, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-12 10:37:17', NULL),
(259, 84, 'Blocks', 'blocks', 'is blocked by', '2013-04-12 10:37:17', NULL),
(260, 84, 'Cloners', 'clones', 'is cloned by', '2013-04-12 10:37:17', NULL),
(261, 85, 'Relates', 'relates to', 'relates to', '2013-04-12 10:41:57', NULL),
(262, 85, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-12 10:41:57', NULL),
(263, 85, 'Blocks', 'blocks', 'is blocked by', '2013-04-12 10:41:57', NULL),
(264, 85, 'Cloners', 'clones', 'is cloned by', '2013-04-12 10:41:57', NULL),
(265, 86, 'Relates', 'relates to', 'relates to', '2013-04-12 11:04:03', NULL),
(266, 86, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-12 11:04:03', NULL),
(267, 86, 'Blocks', 'blocks', 'is blocked by', '2013-04-12 11:04:03', NULL),
(268, 86, 'Cloners', 'clones', 'is cloned by', '2013-04-12 11:04:03', NULL),
(269, 87, 'Relates', 'relates to', 'relates to', '2013-04-23 11:43:51', NULL),
(270, 87, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-23 11:43:51', NULL),
(271, 87, 'Blocks', 'blocks', 'is blocked by', '2013-04-23 11:43:51', NULL),
(272, 87, 'Cloners', 'clones', 'is cloned by', '2013-04-23 11:43:51', NULL),
(273, 88, 'Relates', 'relates to', 'relates to', '2013-04-29 01:02:06', NULL),
(274, 88, 'Duplicate', 'duplicates', 'is duplicated by', '2013-04-29 01:02:06', NULL),
(275, 88, 'Blocks', 'blocks', 'is blocked by', '2013-04-29 01:02:06', NULL),
(276, 88, 'Cloners', 'clones', 'is cloned by', '2013-04-29 01:02:06', NULL),
(277, 89, 'Relates', 'relates to', 'relates to', '2013-05-01 06:25:22', NULL),
(278, 89, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-01 06:25:22', NULL),
(279, 89, 'Blocks', 'blocks', 'is blocked by', '2013-05-01 06:25:22', NULL),
(280, 89, 'Cloners', 'clones', 'is cloned by', '2013-05-01 06:25:22', NULL),
(281, 90, 'Relates', 'relates to', 'relates to', '2013-05-03 13:08:11', NULL),
(282, 90, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-03 13:08:11', NULL),
(283, 90, 'Blocks', 'blocks', 'is blocked by', '2013-05-03 13:08:11', NULL),
(284, 90, 'Cloners', 'clones', 'is cloned by', '2013-05-03 13:08:11', NULL),
(285, 91, 'Relates', 'relates to', 'relates to', '2013-05-03 17:02:08', NULL),
(286, 91, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-03 17:02:08', NULL),
(287, 91, 'Blocks', 'blocks', 'is blocked by', '2013-05-03 17:02:08', NULL),
(288, 91, 'Cloners', 'clones', 'is cloned by', '2013-05-03 17:02:08', NULL),
(289, 92, 'Relates', 'relates to', 'relates to', '2013-05-05 20:40:01', NULL),
(290, 92, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-05 20:40:01', NULL),
(291, 92, 'Blocks', 'blocks', 'is blocked by', '2013-05-05 20:40:01', NULL),
(292, 92, 'Cloners', 'clones', 'is cloned by', '2013-05-05 20:40:01', NULL),
(293, 93, 'Relates', 'relates to', 'relates to', '2013-05-06 21:00:37', NULL),
(294, 93, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-06 21:00:37', NULL),
(295, 93, 'Blocks', 'blocks', 'is blocked by', '2013-05-06 21:00:37', NULL),
(296, 93, 'Cloners', 'clones', 'is cloned by', '2013-05-06 21:00:37', NULL),
(297, 94, 'Relates', 'relates to', 'relates to', '2013-05-08 10:05:24', NULL),
(298, 94, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-08 10:05:24', NULL),
(299, 94, 'Blocks', 'blocks', 'is blocked by', '2013-05-08 10:05:24', NULL),
(300, 94, 'Cloners', 'clones', 'is cloned by', '2013-05-08 10:05:24', NULL),
(301, 95, 'Relates', 'relates to', 'relates to', '2013-05-08 16:56:23', NULL),
(302, 95, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-08 16:56:23', NULL),
(303, 95, 'Blocks', 'blocks', 'is blocked by', '2013-05-08 16:56:23', NULL),
(304, 95, 'Cloners', 'clones', 'is cloned by', '2013-05-08 16:56:23', NULL),
(305, 96, 'Relates', 'relates to', 'relates to', '2013-05-09 08:54:23', NULL),
(306, 96, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-09 08:54:23', NULL),
(307, 96, 'Blocks', 'blocks', 'is blocked by', '2013-05-09 08:54:23', NULL),
(308, 96, 'Cloners', 'clones', 'is cloned by', '2013-05-09 08:54:23', NULL),
(309, 97, 'Relates', 'relates to', 'relates to', '2013-05-11 04:48:05', NULL),
(310, 97, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-11 04:48:05', NULL),
(311, 97, 'Blocks', 'blocks', 'is blocked by', '2013-05-11 04:48:05', NULL),
(312, 97, 'Cloners', 'clones', 'is cloned by', '2013-05-11 04:48:05', NULL),
(313, 98, 'Relates', 'relates to', 'relates to', '2013-05-15 14:43:05', NULL),
(314, 98, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-15 14:43:05', NULL),
(315, 98, 'Blocks', 'blocks', 'is blocked by', '2013-05-15 14:43:05', NULL),
(316, 98, 'Cloners', 'clones', 'is cloned by', '2013-05-15 14:43:05', NULL),
(317, 99, 'Relates', 'relates to', 'relates to', '2013-05-18 09:57:56', NULL),
(318, 99, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-18 09:57:56', NULL),
(319, 99, 'Blocks', 'blocks', 'is blocked by', '2013-05-18 09:57:56', NULL),
(320, 99, 'Cloners', 'clones', 'is cloned by', '2013-05-18 09:57:56', NULL),
(321, 100, 'Relates', 'relates to', 'relates to', '2013-05-18 16:54:42', NULL),
(322, 100, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-18 16:54:42', NULL),
(323, 100, 'Blocks', 'blocks', 'is blocked by', '2013-05-18 16:54:42', NULL),
(324, 100, 'Cloners', 'clones', 'is cloned by', '2013-05-18 16:54:42', NULL),
(325, 101, 'Relates', 'relates to', 'relates to', '2013-05-19 20:51:56', NULL),
(326, 101, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-19 20:51:56', NULL),
(327, 101, 'Blocks', 'blocks', 'is blocked by', '2013-05-19 20:51:56', NULL),
(328, 101, 'Cloners', 'clones', 'is cloned by', '2013-05-19 20:51:56', NULL),
(329, 102, 'Relates', 'relates to', 'relates to', '2013-05-20 07:59:43', NULL),
(330, 102, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-20 07:59:43', NULL),
(331, 102, 'Blocks', 'blocks', 'is blocked by', '2013-05-20 07:59:43', NULL),
(332, 102, 'Cloners', 'clones', 'is cloned by', '2013-05-20 07:59:43', NULL),
(333, 103, 'Relates', 'relates to', 'relates to', '2013-05-20 20:34:59', NULL),
(334, 103, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-20 20:34:59', NULL),
(335, 103, 'Blocks', 'blocks', 'is blocked by', '2013-05-20 20:34:59', NULL),
(336, 103, 'Cloners', 'clones', 'is cloned by', '2013-05-20 20:34:59', NULL),
(337, 104, 'Relates', 'relates to', 'relates to', '2013-05-22 10:56:37', NULL),
(338, 104, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-22 10:56:37', NULL),
(339, 104, 'Blocks', 'blocks', 'is blocked by', '2013-05-22 10:56:37', NULL),
(340, 104, 'Cloners', 'clones', 'is cloned by', '2013-05-22 10:56:37', NULL),
(341, 105, 'Relates', 'relates to', 'relates to', '2013-05-22 21:59:56', NULL),
(342, 105, 'Duplicate', 'duplicates', 'is duplicated by', '2013-05-22 21:59:56', NULL),
(343, 105, 'Blocks', 'blocks', 'is blocked by', '2013-05-22 21:59:56', NULL),
(344, 105, 'Cloners', 'clones', 'is cloned by', '2013-05-22 21:59:56', NULL),
(345, 106, 'Relates', 'relates to', 'relates to', '2013-06-11 10:12:21', NULL),
(346, 106, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-11 10:12:21', NULL),
(347, 106, 'Blocks', 'blocks', 'is blocked by', '2013-06-11 10:12:21', NULL),
(348, 106, 'Cloners', 'clones', 'is cloned by', '2013-06-11 10:12:21', NULL),
(349, 107, 'Relates', 'relates to', 'relates to', '2013-06-20 06:41:05', NULL),
(350, 107, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-20 06:41:05', NULL),
(351, 107, 'Blocks', 'blocks', 'is blocked by', '2013-06-20 06:41:05', NULL),
(352, 107, 'Cloners', 'clones', 'is cloned by', '2013-06-20 06:41:05', NULL),
(353, 108, 'Relates', 'relates to', 'relates to', '2013-06-24 12:43:50', NULL),
(354, 108, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-24 12:43:50', NULL),
(355, 108, 'Blocks', 'blocks', 'is blocked by', '2013-06-24 12:43:50', NULL),
(356, 108, 'Cloners', 'clones', 'is cloned by', '2013-06-24 12:43:50', NULL),
(357, 109, 'Relates', 'relates to', 'relates to', '2013-06-26 02:40:03', NULL),
(358, 109, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-26 02:40:03', NULL),
(359, 109, 'Blocks', 'blocks', 'is blocked by', '2013-06-26 02:40:03', NULL),
(360, 109, 'Cloners', 'clones', 'is cloned by', '2013-06-26 02:40:03', NULL),
(361, 110, 'Relates', 'relates to', 'relates to', '2013-06-26 02:45:11', NULL),
(362, 110, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-26 02:45:11', NULL),
(363, 110, 'Blocks', 'blocks', 'is blocked by', '2013-06-26 02:45:11', NULL),
(364, 110, 'Cloners', 'clones', 'is cloned by', '2013-06-26 02:45:11', NULL),
(365, 111, 'Relates', 'relates to', 'relates to', '2013-06-29 11:35:31', NULL),
(366, 111, 'Duplicate', 'duplicates', 'is duplicated by', '2013-06-29 11:35:31', NULL),
(367, 111, 'Blocks', 'blocks', 'is blocked by', '2013-06-29 11:35:31', NULL),
(368, 111, 'Cloners', 'clones', 'is cloned by', '2013-06-29 11:35:31', NULL),
(369, 112, 'Relates', 'relates to', 'relates to', '2013-07-01 02:17:01', NULL),
(370, 112, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-01 02:17:01', NULL),
(371, 112, 'Blocks', 'blocks', 'is blocked by', '2013-07-01 02:17:01', NULL),
(372, 112, 'Cloners', 'clones', 'is cloned by', '2013-07-01 02:17:01', NULL),
(373, 113, 'Relates', 'relates to', 'relates to', '2013-07-02 18:07:27', NULL),
(374, 113, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-02 18:07:27', NULL),
(375, 113, 'Blocks', 'blocks', 'is blocked by', '2013-07-02 18:07:27', NULL),
(376, 113, 'Cloners', 'clones', 'is cloned by', '2013-07-02 18:07:27', NULL),
(377, 114, 'Relates', 'relates to', 'relates to', '2013-07-12 14:49:06', NULL),
(378, 114, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-12 14:49:06', NULL),
(379, 114, 'Blocks', 'blocks', 'is blocked by', '2013-07-12 14:49:06', NULL),
(380, 114, 'Cloners', 'clones', 'is cloned by', '2013-07-12 14:49:06', NULL),
(381, 115, 'Relates', 'relates to', 'relates to', '2013-07-16 16:46:07', NULL),
(382, 115, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-16 16:46:07', NULL),
(383, 115, 'Blocks', 'blocks', 'is blocked by', '2013-07-16 16:46:07', NULL),
(384, 115, 'Cloners', 'clones', 'is cloned by', '2013-07-16 16:46:07', NULL),
(385, 116, 'Relates', 'relates to', 'relates to', '2013-07-22 09:22:56', NULL),
(386, 116, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-22 09:22:56', NULL),
(387, 116, 'Blocks', 'blocks', 'is blocked by', '2013-07-22 09:22:56', NULL),
(388, 116, 'Cloners', 'clones', 'is cloned by', '2013-07-22 09:22:56', NULL),
(389, 117, 'Relates', 'relates to', 'relates to', '2013-07-22 10:01:10', NULL),
(390, 117, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-22 10:01:10', NULL),
(391, 117, 'Blocks', 'blocks', 'is blocked by', '2013-07-22 10:01:10', NULL),
(392, 117, 'Cloners', 'clones', 'is cloned by', '2013-07-22 10:01:10', NULL),
(393, 118, 'Relates', 'relates to', 'relates to', '2013-07-23 18:12:32', NULL),
(394, 118, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-23 18:12:32', NULL),
(395, 118, 'Blocks', 'blocks', 'is blocked by', '2013-07-23 18:12:32', NULL),
(396, 118, 'Cloners', 'clones', 'is cloned by', '2013-07-23 18:12:32', NULL),
(397, 119, 'Relates', 'relates to', 'relates to', '2013-07-23 20:04:03', NULL),
(398, 119, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-23 20:04:03', NULL),
(399, 119, 'Blocks', 'blocks', 'is blocked by', '2013-07-23 20:04:03', NULL),
(400, 119, 'Cloners', 'clones', 'is cloned by', '2013-07-23 20:04:03', NULL),
(401, 120, 'Relates', 'relates to', 'relates to', '2013-07-25 14:44:25', NULL),
(402, 120, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-25 14:44:25', NULL),
(403, 120, 'Blocks', 'blocks', 'is blocked by', '2013-07-25 14:44:25', NULL),
(404, 120, 'Cloners', 'clones', 'is cloned by', '2013-07-25 14:44:25', NULL),
(405, 121, 'Relates', 'relates to', 'relates to', '2013-07-31 09:40:39', NULL),
(406, 121, 'Duplicate', 'duplicates', 'is duplicated by', '2013-07-31 09:40:39', NULL),
(407, 121, 'Blocks', 'blocks', 'is blocked by', '2013-07-31 09:40:39', NULL),
(408, 121, 'Cloners', 'clones', 'is cloned by', '2013-07-31 09:40:39', NULL),
(409, 122, 'Relates', 'relates to', 'relates to', '2013-08-03 07:17:58', NULL),
(410, 122, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-03 07:17:58', NULL),
(411, 122, 'Blocks', 'blocks', 'is blocked by', '2013-08-03 07:17:58', NULL),
(412, 122, 'Cloners', 'clones', 'is cloned by', '2013-08-03 07:17:58', NULL),
(413, 123, 'Relates', 'relates to', 'relates to', '2013-08-07 23:44:27', NULL),
(414, 123, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-07 23:44:27', NULL),
(415, 123, 'Blocks', 'blocks', 'is blocked by', '2013-08-07 23:44:27', NULL),
(416, 123, 'Cloners', 'clones', 'is cloned by', '2013-08-07 23:44:27', NULL),
(417, 124, 'Relates', 'relates to', 'relates to', '2013-08-09 07:24:54', NULL),
(418, 124, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-09 07:24:54', NULL),
(419, 124, 'Blocks', 'blocks', 'is blocked by', '2013-08-09 07:24:54', NULL),
(420, 124, 'Cloners', 'clones', 'is cloned by', '2013-08-09 07:24:54', NULL),
(421, 125, 'Relates', 'relates to', 'relates to', '2013-08-10 02:54:04', NULL),
(422, 125, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-10 02:54:04', NULL),
(423, 125, 'Blocks', 'blocks', 'is blocked by', '2013-08-10 02:54:04', NULL),
(424, 125, 'Cloners', 'clones', 'is cloned by', '2013-08-10 02:54:04', NULL),
(425, 126, 'Relates', 'relates to', 'relates to', '2013-08-10 13:21:51', NULL),
(426, 126, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-10 13:21:51', NULL),
(427, 126, 'Blocks', 'blocks', 'is blocked by', '2013-08-10 13:21:51', NULL),
(428, 126, 'Cloners', 'clones', 'is cloned by', '2013-08-10 13:21:51', NULL),
(429, 127, 'Relates', 'relates to', 'relates to', '2013-08-12 15:05:44', NULL),
(430, 127, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-12 15:05:44', NULL),
(431, 127, 'Blocks', 'blocks', 'is blocked by', '2013-08-12 15:05:44', NULL),
(432, 127, 'Cloners', 'clones', 'is cloned by', '2013-08-12 15:05:44', NULL),
(433, 128, 'Relates', 'relates to', 'relates to', '2013-08-12 22:17:43', NULL),
(434, 128, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-12 22:17:43', NULL),
(435, 128, 'Blocks', 'blocks', 'is blocked by', '2013-08-12 22:17:43', NULL),
(436, 128, 'Cloners', 'clones', 'is cloned by', '2013-08-12 22:17:43', NULL),
(437, 129, 'Relates', 'relates to', 'relates to', '2013-08-14 02:26:24', NULL),
(438, 129, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-14 02:26:24', NULL),
(439, 129, 'Blocks', 'blocks', 'is blocked by', '2013-08-14 02:26:24', NULL),
(440, 129, 'Cloners', 'clones', 'is cloned by', '2013-08-14 02:26:24', NULL),
(441, 130, 'Relates', 'relates to', 'relates to', '2013-08-14 11:12:25', NULL),
(442, 130, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-14 11:12:25', NULL),
(443, 130, 'Blocks', 'blocks', 'is blocked by', '2013-08-14 11:12:25', NULL),
(444, 130, 'Cloners', 'clones', 'is cloned by', '2013-08-14 11:12:25', NULL),
(445, 131, 'Relates', 'relates to', 'relates to', '2013-08-16 04:37:38', NULL),
(446, 131, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-16 04:37:38', NULL),
(447, 131, 'Blocks', 'blocks', 'is blocked by', '2013-08-16 04:37:38', NULL),
(448, 131, 'Cloners', 'clones', 'is cloned by', '2013-08-16 04:37:38', NULL),
(449, 132, 'Relates', 'relates to', 'relates to', '2013-08-16 13:22:59', NULL),
(450, 132, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-16 13:22:59', NULL),
(451, 132, 'Blocks', 'blocks', 'is blocked by', '2013-08-16 13:22:59', NULL),
(452, 132, 'Cloners', 'clones', 'is cloned by', '2013-08-16 13:22:59', NULL),
(453, 133, 'Relates', 'relates to', 'relates to', '2013-08-16 20:34:01', NULL),
(454, 133, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-16 20:34:01', NULL),
(455, 133, 'Blocks', 'blocks', 'is blocked by', '2013-08-16 20:34:01', NULL),
(456, 133, 'Cloners', 'clones', 'is cloned by', '2013-08-16 20:34:01', NULL),
(457, 134, 'Relates', 'relates to', 'relates to', '2013-08-17 23:01:08', NULL),
(458, 134, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-17 23:01:08', NULL),
(459, 134, 'Blocks', 'blocks', 'is blocked by', '2013-08-17 23:01:08', NULL),
(460, 134, 'Cloners', 'clones', 'is cloned by', '2013-08-17 23:01:08', NULL),
(461, 135, 'Relates', 'relates to', 'relates to', '2013-08-18 14:15:02', NULL),
(462, 135, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-18 14:15:02', NULL),
(463, 135, 'Blocks', 'blocks', 'is blocked by', '2013-08-18 14:15:02', NULL),
(464, 135, 'Cloners', 'clones', 'is cloned by', '2013-08-18 14:15:02', NULL),
(465, 136, 'Relates', 'relates to', 'relates to', '2013-08-22 14:06:34', NULL),
(466, 136, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-22 14:06:34', NULL),
(467, 136, 'Blocks', 'blocks', 'is blocked by', '2013-08-22 14:06:34', NULL),
(468, 136, 'Cloners', 'clones', 'is cloned by', '2013-08-22 14:06:34', NULL),
(469, 137, 'Relates', 'relates to', 'relates to', '2013-08-24 08:00:11', NULL),
(470, 137, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-24 08:00:11', NULL),
(471, 137, 'Blocks', 'blocks', 'is blocked by', '2013-08-24 08:00:11', NULL),
(472, 137, 'Cloners', 'clones', 'is cloned by', '2013-08-24 08:00:11', NULL),
(473, 138, 'Relates', 'relates to', 'relates to', '2013-08-24 10:54:23', NULL),
(474, 138, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-24 10:54:23', NULL),
(475, 138, 'Blocks', 'blocks', 'is blocked by', '2013-08-24 10:54:23', NULL),
(476, 138, 'Cloners', 'clones', 'is cloned by', '2013-08-24 10:54:23', NULL),
(477, 139, 'Relates', 'relates to', 'relates to', '2013-08-24 10:58:20', NULL),
(478, 139, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-24 10:58:20', NULL),
(479, 139, 'Blocks', 'blocks', 'is blocked by', '2013-08-24 10:58:20', NULL),
(480, 139, 'Cloners', 'clones', 'is cloned by', '2013-08-24 10:58:20', NULL),
(481, 140, 'Relates', 'relates to', 'relates to', '2013-08-28 11:39:09', NULL),
(482, 140, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-28 11:39:09', NULL),
(483, 140, 'Blocks', 'blocks', 'is blocked by', '2013-08-28 11:39:09', NULL),
(484, 140, 'Cloners', 'clones', 'is cloned by', '2013-08-28 11:39:09', NULL),
(485, 141, 'Relates', 'relates to', 'relates to', '2013-08-28 15:28:02', NULL),
(486, 141, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-28 15:28:02', NULL),
(487, 141, 'Blocks', 'blocks', 'is blocked by', '2013-08-28 15:28:02', NULL),
(488, 141, 'Cloners', 'clones', 'is cloned by', '2013-08-28 15:28:02', NULL),
(489, 142, 'Relates', 'relates to', 'relates to', '2013-08-29 20:06:52', NULL),
(490, 142, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-29 20:06:52', NULL),
(491, 142, 'Blocks', 'blocks', 'is blocked by', '2013-08-29 20:06:52', NULL),
(492, 142, 'Cloners', 'clones', 'is cloned by', '2013-08-29 20:06:52', NULL),
(493, 143, 'Relates', 'relates to', 'relates to', '2013-08-30 22:26:11', NULL),
(494, 143, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-30 22:26:11', NULL),
(495, 143, 'Blocks', 'blocks', 'is blocked by', '2013-08-30 22:26:11', NULL),
(496, 143, 'Cloners', 'clones', 'is cloned by', '2013-08-30 22:26:11', NULL),
(497, 144, 'Relates', 'relates to', 'relates to', '2013-08-31 11:22:47', NULL),
(498, 144, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-31 11:22:47', NULL),
(499, 144, 'Blocks', 'blocks', 'is blocked by', '2013-08-31 11:22:47', NULL),
(500, 144, 'Cloners', 'clones', 'is cloned by', '2013-08-31 11:22:47', NULL),
(501, 145, 'Relates', 'relates to', 'relates to', '2013-08-31 16:51:52', NULL),
(502, 145, 'Duplicate', 'duplicates', 'is duplicated by', '2013-08-31 16:51:52', NULL),
(503, 145, 'Blocks', 'blocks', 'is blocked by', '2013-08-31 16:51:52', NULL),
(504, 145, 'Cloners', 'clones', 'is cloned by', '2013-08-31 16:51:52', NULL),
(505, 146, 'Relates', 'relates to', 'relates to', '2013-09-02 19:29:31', NULL),
(506, 146, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-02 19:29:31', NULL),
(507, 146, 'Blocks', 'blocks', 'is blocked by', '2013-09-02 19:29:31', NULL),
(508, 146, 'Cloners', 'clones', 'is cloned by', '2013-09-02 19:29:31', NULL),
(509, 147, 'Relates', 'relates to', 'relates to', '2013-09-03 01:27:39', NULL),
(510, 147, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-03 01:27:39', NULL),
(511, 147, 'Blocks', 'blocks', 'is blocked by', '2013-09-03 01:27:39', NULL),
(512, 147, 'Cloners', 'clones', 'is cloned by', '2013-09-03 01:27:39', NULL),
(513, 148, 'Relates', 'relates to', 'relates to', '2013-09-03 02:46:50', NULL),
(514, 148, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-03 02:46:50', NULL),
(515, 148, 'Blocks', 'blocks', 'is blocked by', '2013-09-03 02:46:50', NULL),
(516, 148, 'Cloners', 'clones', 'is cloned by', '2013-09-03 02:46:50', NULL),
(517, 149, 'Relates', 'relates to', 'relates to', '2013-09-05 09:53:05', NULL),
(518, 149, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-05 09:53:05', NULL),
(519, 149, 'Blocks', 'blocks', 'is blocked by', '2013-09-05 09:53:05', NULL),
(520, 149, 'Cloners', 'clones', 'is cloned by', '2013-09-05 09:53:05', NULL),
(521, 150, 'Relates', 'relates to', 'relates to', '2013-09-08 15:19:04', NULL),
(522, 150, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-08 15:19:04', NULL),
(523, 150, 'Blocks', 'blocks', 'is blocked by', '2013-09-08 15:19:04', NULL),
(524, 150, 'Cloners', 'clones', 'is cloned by', '2013-09-08 15:19:04', NULL),
(525, 151, 'Relates', 'relates to', 'relates to', '2013-09-09 17:55:54', NULL),
(526, 151, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-09 17:55:54', NULL),
(527, 151, 'Blocks', 'blocks', 'is blocked by', '2013-09-09 17:55:54', NULL),
(528, 151, 'Cloners', 'clones', 'is cloned by', '2013-09-09 17:55:54', NULL),
(529, 152, 'Relates', 'relates to', 'relates to', '2013-09-09 21:54:23', NULL),
(530, 152, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-09 21:54:23', NULL),
(531, 152, 'Blocks', 'blocks', 'is blocked by', '2013-09-09 21:54:23', NULL),
(532, 152, 'Cloners', 'clones', 'is cloned by', '2013-09-09 21:54:23', NULL),
(533, 153, 'Relates', 'relates to', 'relates to', '2013-09-10 14:16:29', NULL),
(534, 153, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-10 14:16:29', NULL),
(535, 153, 'Blocks', 'blocks', 'is blocked by', '2013-09-10 14:16:29', NULL),
(536, 153, 'Cloners', 'clones', 'is cloned by', '2013-09-10 14:16:29', NULL),
(537, 154, 'Relates', 'relates to', 'relates to', '2013-09-12 14:58:23', NULL),
(538, 154, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-12 14:58:23', NULL),
(539, 154, 'Blocks', 'blocks', 'is blocked by', '2013-09-12 14:58:23', NULL),
(540, 154, 'Cloners', 'clones', 'is cloned by', '2013-09-12 14:58:23', NULL),
(541, 155, 'Relates', 'relates to', 'relates to', '2013-09-12 15:14:32', NULL),
(542, 155, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-12 15:14:32', NULL),
(543, 155, 'Blocks', 'blocks', 'is blocked by', '2013-09-12 15:14:32', NULL),
(544, 155, 'Cloners', 'clones', 'is cloned by', '2013-09-12 15:14:32', NULL),
(545, 156, 'Relates', 'relates to', 'relates to', '2013-09-13 15:50:37', NULL),
(546, 156, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-13 15:50:37', NULL),
(547, 156, 'Blocks', 'blocks', 'is blocked by', '2013-09-13 15:50:37', NULL),
(548, 156, 'Cloners', 'clones', 'is cloned by', '2013-09-13 15:50:37', NULL),
(549, 157, 'Relates', 'relates to', 'relates to', '2013-09-15 19:42:40', NULL),
(550, 157, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-15 19:42:40', NULL),
(551, 157, 'Blocks', 'blocks', 'is blocked by', '2013-09-15 19:42:40', NULL),
(552, 157, 'Cloners', 'clones', 'is cloned by', '2013-09-15 19:42:40', NULL),
(553, 158, 'Relates', 'relates to', 'relates to', '2013-09-17 16:20:50', NULL),
(554, 158, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-17 16:20:50', NULL),
(555, 158, 'Blocks', 'blocks', 'is blocked by', '2013-09-17 16:20:50', NULL),
(556, 158, 'Cloners', 'clones', 'is cloned by', '2013-09-17 16:20:50', NULL),
(557, 159, 'Relates', 'relates to', 'relates to', '2013-09-17 21:13:51', NULL),
(558, 159, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-17 21:13:51', NULL),
(559, 159, 'Blocks', 'blocks', 'is blocked by', '2013-09-17 21:13:51', NULL),
(560, 159, 'Cloners', 'clones', 'is cloned by', '2013-09-17 21:13:51', NULL),
(561, 161, 'Relates', 'relates to', 'relates to', '2013-09-20 09:49:35', NULL),
(562, 161, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-20 09:49:35', NULL),
(563, 161, 'Blocks', 'blocks', 'is blocked by', '2013-09-20 09:49:35', NULL),
(564, 161, 'Cloners', 'clones', 'is cloned by', '2013-09-20 09:49:35', NULL),
(565, 160, 'Relates', 'relates to', 'relates to', '2013-09-18 15:53:00', NULL),
(566, 160, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-18 15:53:00', NULL),
(567, 160, 'Blocks', 'blocks', 'is blocked by', '2013-09-18 15:53:00', NULL),
(568, 160, 'Cloners', 'clones', 'is cloned by', '2013-09-18 15:53:00', NULL),
(569, 162, 'Relates', 'relates to', 'relates to', '2013-09-22 00:06:52', NULL),
(570, 162, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-22 00:06:52', NULL),
(571, 162, 'Blocks', 'blocks', 'is blocked by', '2013-09-22 00:06:52', NULL),
(572, 162, 'Cloners', 'clones', 'is cloned by', '2013-09-22 00:06:52', NULL),
(577, 164, 'Relates', 'relates to', 'relates to', '2013-09-25 10:45:27', NULL),
(578, 164, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-25 10:45:27', NULL),
(579, 164, 'Blocks', 'blocks', 'is blocked by', '2013-09-25 10:45:27', NULL),
(580, 164, 'Cloners', 'clones', 'is cloned by', '2013-09-25 10:45:27', NULL),
(581, 165, 'Relates', 'relates to', 'relates to', '2013-09-25 15:21:17', NULL),
(582, 165, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-25 15:21:17', NULL),
(583, 165, 'Blocks', 'blocks', 'is blocked by', '2013-09-25 15:21:17', NULL),
(584, 165, 'Cloners', 'clones', 'is cloned by', '2013-09-25 15:21:17', NULL),
(585, 166, 'Relates', 'relates to', 'relates to', '2013-09-27 12:54:09', NULL),
(586, 166, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-27 12:54:09', NULL),
(587, 166, 'Blocks', 'blocks', 'is blocked by', '2013-09-27 12:54:09', NULL),
(588, 166, 'Cloners', 'clones', 'is cloned by', '2013-09-27 12:54:09', NULL),
(589, 167, 'Relates', 'relates to', 'relates to', '2013-09-29 06:40:54', NULL),
(590, 167, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-29 06:40:54', NULL),
(591, 167, 'Blocks', 'blocks', 'is blocked by', '2013-09-29 06:40:54', NULL),
(592, 167, 'Cloners', 'clones', 'is cloned by', '2013-09-29 06:40:54', NULL),
(593, 163, 'Relates', 'relates to', 'relates to', '2013-09-25 02:33:56', NULL),
(594, 163, 'Duplicate', 'duplicates', 'is duplicated by', '2013-09-25 02:33:56', NULL),
(595, 163, 'Blocks', 'blocks', 'is blocked by', '2013-09-25 02:33:56', NULL),
(596, 163, 'Cloners', 'clones', 'is cloned by', '2013-09-25 02:33:56', NULL),
(597, 168, 'Relates', 'relates to', 'relates to', '2013-10-01 01:33:41', NULL),
(598, 168, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-01 01:33:41', NULL),
(599, 168, 'Blocks', 'blocks', 'is blocked by', '2013-10-01 01:33:41', NULL),
(600, 168, 'Cloners', 'clones', 'is cloned by', '2013-10-01 01:33:41', NULL),
(601, 169, 'Relates', 'relates to', 'relates to', '2013-10-01 21:21:11', NULL),
(602, 169, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-01 21:21:11', NULL),
(603, 169, 'Blocks', 'blocks', 'is blocked by', '2013-10-01 21:21:11', NULL),
(604, 169, 'Cloners', 'clones', 'is cloned by', '2013-10-01 21:21:11', NULL),
(605, 170, 'Relates', 'relates to', 'relates to', '2013-10-06 18:41:10', NULL),
(606, 170, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-06 18:41:10', NULL),
(607, 170, 'Blocks', 'blocks', 'is blocked by', '2013-10-06 18:41:10', NULL),
(608, 170, 'Cloners', 'clones', 'is cloned by', '2013-10-06 18:41:10', NULL),
(609, 171, 'Relates', 'relates to', 'relates to', '2013-10-07 00:44:26', NULL),
(610, 171, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-07 00:44:26', NULL),
(611, 171, 'Blocks', 'blocks', 'is blocked by', '2013-10-07 00:44:26', NULL),
(612, 171, 'Cloners', 'clones', 'is cloned by', '2013-10-07 00:44:26', NULL),
(613, 172, 'Relates', 'relates to', 'relates to', '2013-10-08 11:21:37', NULL),
(614, 172, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-08 11:21:37', NULL),
(615, 172, 'Blocks', 'blocks', 'is blocked by', '2013-10-08 11:21:37', NULL),
(616, 172, 'Cloners', 'clones', 'is cloned by', '2013-10-08 11:21:37', NULL),
(617, 173, 'Relates', 'relates to', 'relates to', '2013-10-08 12:32:40', NULL),
(618, 173, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-08 12:32:40', NULL),
(619, 173, 'Blocks', 'blocks', 'is blocked by', '2013-10-08 12:32:40', NULL),
(620, 173, 'Cloners', 'clones', 'is cloned by', '2013-10-08 12:32:40', NULL),
(621, 174, 'Relates', 'relates to', 'relates to', '2013-10-09 07:58:17', NULL),
(622, 174, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-09 07:58:17', NULL),
(623, 174, 'Blocks', 'blocks', 'is blocked by', '2013-10-09 07:58:17', NULL),
(624, 174, 'Cloners', 'clones', 'is cloned by', '2013-10-09 07:58:17', NULL),
(625, 175, 'Relates', 'relates to', 'relates to', '2013-10-10 04:22:54', NULL),
(626, 175, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-10 04:22:54', NULL),
(627, 175, 'Blocks', 'blocks', 'is blocked by', '2013-10-10 04:22:54', NULL),
(628, 175, 'Cloners', 'clones', 'is cloned by', '2013-10-10 04:22:54', NULL),
(629, 176, 'Relates', 'relates to', 'relates to', '2013-10-10 13:25:40', NULL),
(630, 176, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-10 13:25:40', NULL),
(631, 176, 'Blocks', 'blocks', 'is blocked by', '2013-10-10 13:25:40', NULL),
(632, 176, 'Cloners', 'clones', 'is cloned by', '2013-10-10 13:25:40', NULL),
(633, 177, 'Relates', 'relates to', 'relates to', '2013-10-14 14:43:17', NULL),
(634, 177, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-14 14:43:17', NULL),
(635, 177, 'Blocks', 'blocks', 'is blocked by', '2013-10-14 14:43:17', NULL),
(636, 177, 'Cloners', 'clones', 'is cloned by', '2013-10-14 14:43:17', NULL),
(637, 178, 'Relates', 'relates to', 'relates to', '2013-10-14 15:17:33', NULL),
(638, 178, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-14 15:17:33', NULL),
(639, 178, 'Blocks', 'blocks', 'is blocked by', '2013-10-14 15:17:33', NULL),
(640, 178, 'Cloners', 'clones', 'is cloned by', '2013-10-14 15:17:33', NULL),
(641, 179, 'Relates', 'relates to', 'relates to', '2013-10-14 20:45:49', NULL),
(642, 179, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-14 20:45:49', NULL);
INSERT INTO `issue_link_type` (`id`, `client_id`, `name`, `outward_description`, `inward_description`, `date_created`, `date_updated`) VALUES
(643, 179, 'Blocks', 'blocks', 'is blocked by', '2013-10-14 20:45:49', NULL),
(644, 179, 'Cloners', 'clones', 'is cloned by', '2013-10-14 20:45:49', NULL),
(645, 180, 'Relates', 'relates to', 'relates to', '2013-10-14 21:20:25', NULL),
(646, 180, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-14 21:20:25', NULL),
(647, 180, 'Blocks', 'blocks', 'is blocked by', '2013-10-14 21:20:25', NULL),
(648, 180, 'Cloners', 'clones', 'is cloned by', '2013-10-14 21:20:25', NULL),
(649, 181, 'Relates', 'relates to', 'relates to', '2013-10-15 14:39:36', NULL),
(650, 181, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-15 14:39:36', NULL),
(651, 181, 'Blocks', 'blocks', 'is blocked by', '2013-10-15 14:39:36', NULL),
(652, 181, 'Cloners', 'clones', 'is cloned by', '2013-10-15 14:39:36', NULL),
(653, 182, 'Relates', 'relates to', 'relates to', '2013-10-16 09:52:58', NULL),
(654, 182, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-16 09:52:58', NULL),
(655, 182, 'Blocks', 'blocks', 'is blocked by', '2013-10-16 09:52:58', NULL),
(656, 182, 'Cloners', 'clones', 'is cloned by', '2013-10-16 09:52:58', NULL),
(657, 183, 'Relates', 'relates to', 'relates to', '2013-10-16 10:40:05', NULL),
(658, 183, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-16 10:40:05', NULL),
(659, 183, 'Blocks', 'blocks', 'is blocked by', '2013-10-16 10:40:05', NULL),
(660, 183, 'Cloners', 'clones', 'is cloned by', '2013-10-16 10:40:05', NULL),
(661, 182, 'Relates', 'relates to', 'relates to', '2013-10-16 09:52:58', NULL),
(662, 182, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-16 09:52:58', NULL),
(663, 182, 'Blocks', 'blocks', 'is blocked by', '2013-10-16 09:52:58', NULL),
(664, 182, 'Cloners', 'clones', 'is cloned by', '2013-10-16 09:52:58', NULL),
(665, 184, 'Relates', 'relates to', 'relates to', '2013-10-16 13:11:33', NULL),
(666, 184, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-16 13:11:33', NULL),
(667, 184, 'Blocks', 'blocks', 'is blocked by', '2013-10-16 13:11:33', NULL),
(668, 184, 'Cloners', 'clones', 'is cloned by', '2013-10-16 13:11:33', NULL),
(669, 185, 'Relates', 'relates to', 'relates to', '2013-10-16 18:10:30', NULL),
(670, 185, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-16 18:10:30', NULL),
(671, 185, 'Blocks', 'blocks', 'is blocked by', '2013-10-16 18:10:30', NULL),
(672, 185, 'Cloners', 'clones', 'is cloned by', '2013-10-16 18:10:30', NULL),
(673, 186, 'Relates', 'relates to', 'relates to', '2013-10-17 13:29:46', NULL),
(674, 186, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-17 13:29:46', NULL),
(675, 186, 'Blocks', 'blocks', 'is blocked by', '2013-10-17 13:29:46', NULL),
(676, 186, 'Cloners', 'clones', 'is cloned by', '2013-10-17 13:29:46', NULL),
(677, 187, 'Relates', 'relates to', 'relates to', '2013-10-17 14:34:33', NULL),
(678, 187, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-17 14:34:33', NULL),
(679, 187, 'Blocks', 'blocks', 'is blocked by', '2013-10-17 14:34:33', NULL),
(680, 187, 'Cloners', 'clones', 'is cloned by', '2013-10-17 14:34:33', NULL),
(681, 188, 'Relates', 'relates to', 'relates to', '2013-10-17 16:51:51', NULL),
(682, 188, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-17 16:51:51', NULL),
(683, 188, 'Blocks', 'blocks', 'is blocked by', '2013-10-17 16:51:51', NULL),
(684, 188, 'Cloners', 'clones', 'is cloned by', '2013-10-17 16:51:51', NULL),
(685, 189, 'Relates', 'relates to', 'relates to', '2013-10-18 07:45:43', NULL),
(686, 189, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 07:45:43', NULL),
(687, 189, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 07:45:43', NULL),
(688, 189, 'Cloners', 'clones', 'is cloned by', '2013-10-18 07:45:43', NULL),
(689, 190, 'Relates', 'relates to', 'relates to', '2013-10-18 07:59:01', NULL),
(690, 190, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 07:59:01', NULL),
(691, 190, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 07:59:01', NULL),
(692, 190, 'Cloners', 'clones', 'is cloned by', '2013-10-18 07:59:01', NULL),
(693, 191, 'Relates', 'relates to', 'relates to', '2013-10-18 08:18:02', NULL),
(694, 191, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 08:18:02', NULL),
(695, 191, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 08:18:02', NULL),
(696, 191, 'Cloners', 'clones', 'is cloned by', '2013-10-18 08:18:02', NULL),
(697, 192, 'Relates', 'relates to', 'relates to', '2013-10-18 08:25:01', NULL),
(698, 192, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 08:25:01', NULL),
(699, 192, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 08:25:01', NULL),
(700, 192, 'Cloners', 'clones', 'is cloned by', '2013-10-18 08:25:01', NULL),
(701, 193, 'Relates', 'relates to', 'relates to', '2013-10-18 08:32:01', NULL),
(702, 193, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 08:32:01', NULL),
(703, 193, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 08:32:01', NULL),
(704, 193, 'Cloners', 'clones', 'is cloned by', '2013-10-18 08:32:01', NULL),
(705, 194, 'Relates', 'relates to', 'relates to', '2013-10-18 08:33:02', NULL),
(706, 194, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 08:33:02', NULL),
(707, 194, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 08:33:02', NULL),
(708, 194, 'Cloners', 'clones', 'is cloned by', '2013-10-18 08:33:02', NULL),
(709, 195, 'Relates', 'relates to', 'relates to', '2013-10-18 09:41:01', NULL),
(710, 195, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 09:41:01', NULL),
(711, 195, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 09:41:01', NULL),
(712, 195, 'Cloners', 'clones', 'is cloned by', '2013-10-18 09:41:01', NULL),
(713, 196, 'Relates', 'relates to', 'relates to', '2013-10-18 20:52:01', NULL),
(714, 196, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-18 20:52:01', NULL),
(715, 196, 'Blocks', 'blocks', 'is blocked by', '2013-10-18 20:52:01', NULL),
(716, 196, 'Cloners', 'clones', 'is cloned by', '2013-10-18 20:52:01', NULL),
(717, 197, 'Relates', 'relates to', 'relates to', '2013-10-21 10:36:01', NULL),
(718, 197, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-21 10:36:01', NULL),
(719, 197, 'Blocks', 'blocks', 'is blocked by', '2013-10-21 10:36:01', NULL),
(720, 197, 'Cloners', 'clones', 'is cloned by', '2013-10-21 10:36:01', NULL),
(721, 198, 'Relates', 'relates to', 'relates to', '2013-10-21 11:13:01', NULL),
(722, 198, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-21 11:13:01', NULL),
(723, 198, 'Blocks', 'blocks', 'is blocked by', '2013-10-21 11:13:01', NULL),
(724, 198, 'Cloners', 'clones', 'is cloned by', '2013-10-21 11:13:01', NULL),
(725, 199, 'Relates', 'relates to', 'relates to', '2013-10-21 15:03:01', NULL),
(726, 199, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-21 15:03:01', NULL),
(727, 199, 'Blocks', 'blocks', 'is blocked by', '2013-10-21 15:03:01', NULL),
(728, 199, 'Cloners', 'clones', 'is cloned by', '2013-10-21 15:03:01', NULL),
(729, 200, 'Relates', 'relates to', 'relates to', '2013-10-21 16:54:01', NULL),
(730, 200, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-21 16:54:01', NULL),
(731, 200, 'Blocks', 'blocks', 'is blocked by', '2013-10-21 16:54:01', NULL),
(732, 200, 'Cloners', 'clones', 'is cloned by', '2013-10-21 16:54:01', NULL),
(733, 201, 'Relates', 'relates to', 'relates to', '2013-10-22 07:49:01', NULL),
(734, 201, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-22 07:49:01', NULL),
(735, 201, 'Blocks', 'blocks', 'is blocked by', '2013-10-22 07:49:01', NULL),
(736, 201, 'Cloners', 'clones', 'is cloned by', '2013-10-22 07:49:01', NULL),
(737, 202, 'Relates', 'relates to', 'relates to', '2013-10-22 08:15:01', NULL),
(738, 202, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-22 08:15:01', NULL),
(739, 202, 'Blocks', 'blocks', 'is blocked by', '2013-10-22 08:15:01', NULL),
(740, 202, 'Cloners', 'clones', 'is cloned by', '2013-10-22 08:15:01', NULL),
(741, 203, 'Relates', 'relates to', 'relates to', '2013-10-22 10:13:01', NULL),
(742, 203, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-22 10:13:01', NULL),
(743, 203, 'Blocks', 'blocks', 'is blocked by', '2013-10-22 10:13:01', NULL),
(744, 203, 'Cloners', 'clones', 'is cloned by', '2013-10-22 10:13:01', NULL),
(745, 204, 'Relates', 'relates to', 'relates to', '2013-10-22 11:30:01', NULL),
(746, 204, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-22 11:30:01', NULL),
(747, 204, 'Blocks', 'blocks', 'is blocked by', '2013-10-22 11:30:01', NULL),
(748, 204, 'Cloners', 'clones', 'is cloned by', '2013-10-22 11:30:01', NULL),
(749, 205, 'Relates', 'relates to', 'relates to', '2013-10-22 12:57:01', NULL),
(750, 205, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-22 12:57:01', NULL),
(751, 205, 'Blocks', 'blocks', 'is blocked by', '2013-10-22 12:57:01', NULL),
(752, 205, 'Cloners', 'clones', 'is cloned by', '2013-10-22 12:57:01', NULL),
(753, 206, 'Relates', 'relates to', 'relates to', '2013-10-23 22:15:01', NULL),
(754, 206, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-23 22:15:01', NULL),
(755, 206, 'Blocks', 'blocks', 'is blocked by', '2013-10-23 22:15:01', NULL),
(756, 206, 'Cloners', 'clones', 'is cloned by', '2013-10-23 22:15:01', NULL),
(757, 207, 'Relates', 'relates to', 'relates to', '2013-10-23 22:35:01', NULL),
(758, 207, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-23 22:35:01', NULL),
(759, 207, 'Blocks', 'blocks', 'is blocked by', '2013-10-23 22:35:01', NULL),
(760, 207, 'Cloners', 'clones', 'is cloned by', '2013-10-23 22:35:01', NULL),
(761, 208, 'Relates', 'relates to', 'relates to', '2013-10-24 11:13:01', NULL),
(762, 208, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-24 11:13:01', NULL),
(763, 208, 'Blocks', 'blocks', 'is blocked by', '2013-10-24 11:13:01', NULL),
(764, 208, 'Cloners', 'clones', 'is cloned by', '2013-10-24 11:13:01', NULL),
(765, 209, 'Relates', 'relates to', 'relates to', '2013-10-24 12:47:01', NULL),
(766, 209, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-24 12:47:01', NULL),
(767, 209, 'Blocks', 'blocks', 'is blocked by', '2013-10-24 12:47:01', NULL),
(768, 209, 'Cloners', 'clones', 'is cloned by', '2013-10-24 12:47:01', NULL),
(769, 210, 'Relates', 'relates to', 'relates to', '2013-10-24 13:41:01', NULL),
(770, 210, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-24 13:41:01', NULL),
(771, 210, 'Blocks', 'blocks', 'is blocked by', '2013-10-24 13:41:01', NULL),
(772, 210, 'Cloners', 'clones', 'is cloned by', '2013-10-24 13:41:01', NULL),
(773, 211, 'Relates', 'relates to', 'relates to', '2013-10-29 17:22:01', NULL),
(774, 211, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-29 17:22:01', NULL),
(775, 211, 'Blocks', 'blocks', 'is blocked by', '2013-10-29 17:22:01', NULL),
(776, 211, 'Cloners', 'clones', 'is cloned by', '2013-10-29 17:22:01', NULL),
(777, 212, 'Relates', 'relates to', 'relates to', '2013-10-30 03:27:02', NULL),
(778, 212, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-30 03:27:02', NULL),
(779, 212, 'Blocks', 'blocks', 'is blocked by', '2013-10-30 03:27:02', NULL),
(780, 212, 'Cloners', 'clones', 'is cloned by', '2013-10-30 03:27:02', NULL),
(781, 213, 'Relates', 'relates to', 'relates to', '2013-10-30 23:27:01', NULL),
(782, 213, 'Duplicate', 'duplicates', 'is duplicated by', '2013-10-30 23:27:01', NULL),
(783, 213, 'Blocks', 'blocks', 'is blocked by', '2013-10-30 23:27:01', NULL),
(784, 213, 'Cloners', 'clones', 'is cloned by', '2013-10-30 23:27:01', NULL),
(785, 214, 'Relates', 'relates to', 'relates to', '2013-11-04 12:56:01', NULL),
(786, 214, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-04 12:56:01', NULL),
(787, 214, 'Blocks', 'blocks', 'is blocked by', '2013-11-04 12:56:01', NULL),
(788, 214, 'Cloners', 'clones', 'is cloned by', '2013-11-04 12:56:01', NULL),
(789, 215, 'Relates', 'relates to', 'relates to', '2013-11-04 12:58:01', NULL),
(790, 215, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-04 12:58:01', NULL),
(791, 215, 'Blocks', 'blocks', 'is blocked by', '2013-11-04 12:58:01', NULL),
(792, 215, 'Cloners', 'clones', 'is cloned by', '2013-11-04 12:58:01', NULL),
(793, 216, 'Relates', 'relates to', 'relates to', '2013-11-05 13:25:01', NULL),
(794, 216, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-05 13:25:01', NULL),
(795, 216, 'Blocks', 'blocks', 'is blocked by', '2013-11-05 13:25:01', NULL),
(796, 216, 'Cloners', 'clones', 'is cloned by', '2013-11-05 13:25:01', NULL),
(797, 217, 'Relates', 'relates to', 'relates to', '2013-11-06 21:53:01', NULL),
(798, 217, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-06 21:53:01', NULL),
(799, 217, 'Blocks', 'blocks', 'is blocked by', '2013-11-06 21:53:01', NULL),
(800, 217, 'Cloners', 'clones', 'is cloned by', '2013-11-06 21:53:01', NULL),
(801, 218, 'Relates', 'relates to', 'relates to', '2013-11-07 03:23:01', NULL),
(802, 218, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-07 03:23:01', NULL),
(803, 218, 'Blocks', 'blocks', 'is blocked by', '2013-11-07 03:23:01', NULL),
(804, 218, 'Cloners', 'clones', 'is cloned by', '2013-11-07 03:23:01', NULL),
(805, 219, 'Relates', 'relates to', 'relates to', '2013-11-08 09:20:01', NULL),
(806, 219, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-08 09:20:01', NULL),
(807, 219, 'Blocks', 'blocks', 'is blocked by', '2013-11-08 09:20:01', NULL),
(808, 219, 'Cloners', 'clones', 'is cloned by', '2013-11-08 09:20:01', NULL),
(809, 220, 'Relates', 'relates to', 'relates to', '2013-11-09 12:26:01', NULL),
(810, 220, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-09 12:26:01', NULL),
(811, 220, 'Blocks', 'blocks', 'is blocked by', '2013-11-09 12:26:01', NULL),
(812, 220, 'Cloners', 'clones', 'is cloned by', '2013-11-09 12:26:01', NULL),
(813, 221, 'Relates', 'relates to', 'relates to', '2013-11-12 18:17:01', NULL),
(814, 221, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-12 18:17:01', NULL),
(815, 221, 'Blocks', 'blocks', 'is blocked by', '2013-11-12 18:17:01', NULL),
(816, 221, 'Cloners', 'clones', 'is cloned by', '2013-11-12 18:17:01', NULL),
(817, 222, 'Relates', 'relates to', 'relates to', '2013-11-13 09:30:01', NULL),
(818, 222, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-13 09:30:01', NULL),
(819, 222, 'Blocks', 'blocks', 'is blocked by', '2013-11-13 09:30:01', NULL),
(820, 222, 'Cloners', 'clones', 'is cloned by', '2013-11-13 09:30:01', NULL),
(821, 223, 'Relates', 'relates to', 'relates to', '2013-11-13 09:32:01', NULL),
(822, 223, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-13 09:32:01', NULL),
(823, 223, 'Blocks', 'blocks', 'is blocked by', '2013-11-13 09:32:01', NULL),
(824, 223, 'Cloners', 'clones', 'is cloned by', '2013-11-13 09:32:01', NULL),
(825, 224, 'Relates', 'relates to', 'relates to', '2013-11-14 15:35:01', NULL),
(826, 224, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-14 15:35:01', NULL),
(827, 224, 'Blocks', 'blocks', 'is blocked by', '2013-11-14 15:35:01', NULL),
(828, 224, 'Cloners', 'clones', 'is cloned by', '2013-11-14 15:35:01', NULL),
(829, 225, 'Relates', 'relates to', 'relates to', '2013-11-18 12:17:01', NULL),
(830, 225, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-18 12:17:01', NULL),
(831, 225, 'Blocks', 'blocks', 'is blocked by', '2013-11-18 12:17:01', NULL),
(832, 225, 'Cloners', 'clones', 'is cloned by', '2013-11-18 12:17:01', NULL),
(6705, 1694, 'Relates', 'relates to', 'relates to', '2013-11-21 10:57:01', NULL),
(6706, 1694, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-21 10:57:01', NULL),
(6707, 1694, 'Blocks', 'blocks', 'is blocked by', '2013-11-21 10:57:01', NULL),
(6708, 1694, 'Cloners', 'clones', 'is cloned by', '2013-11-21 10:57:01', NULL),
(6709, 1695, 'Relates', 'relates to', 'relates to', '2013-11-21 10:57:01', NULL),
(6710, 1695, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-21 10:57:01', NULL),
(6711, 1695, 'Blocks', 'blocks', 'is blocked by', '2013-11-21 10:57:01', NULL),
(6712, 1695, 'Cloners', 'clones', 'is cloned by', '2013-11-21 10:57:01', NULL),
(6713, 1696, 'Relates', 'relates to', 'relates to', '2013-11-21 10:57:01', NULL),
(6714, 1696, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-21 10:57:01', NULL),
(6715, 1696, 'Blocks', 'blocks', 'is blocked by', '2013-11-21 10:57:01', NULL),
(6716, 1696, 'Cloners', 'clones', 'is cloned by', '2013-11-21 10:57:01', NULL),
(6717, 1697, 'Relates', 'relates to', 'relates to', '2013-11-21 16:51:01', NULL),
(6718, 1697, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-21 16:51:01', NULL),
(6719, 1697, 'Blocks', 'blocks', 'is blocked by', '2013-11-21 16:51:01', NULL),
(6720, 1697, 'Cloners', 'clones', 'is cloned by', '2013-11-21 16:51:01', NULL),
(6721, 1698, 'Relates', 'relates to', 'relates to', '2013-11-22 16:04:01', NULL),
(6722, 1698, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-22 16:04:01', NULL),
(6723, 1698, 'Blocks', 'blocks', 'is blocked by', '2013-11-22 16:04:01', NULL),
(6724, 1698, 'Cloners', 'clones', 'is cloned by', '2013-11-22 16:04:01', NULL),
(6725, 1699, 'Relates', 'relates to', 'relates to', '2013-11-23 17:13:01', NULL),
(6726, 1699, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-23 17:13:01', NULL),
(6727, 1699, 'Blocks', 'blocks', 'is blocked by', '2013-11-23 17:13:01', NULL),
(6728, 1699, 'Cloners', 'clones', 'is cloned by', '2013-11-23 17:13:01', NULL),
(6729, 1700, 'Relates', 'relates to', 'relates to', '2013-11-24 21:45:01', NULL),
(6730, 1700, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-24 21:45:01', NULL),
(6731, 1700, 'Blocks', 'blocks', 'is blocked by', '2013-11-24 21:45:01', NULL),
(6732, 1700, 'Cloners', 'clones', 'is cloned by', '2013-11-24 21:45:01', NULL),
(6733, 1701, 'Relates', 'relates to', 'relates to', '2013-11-25 07:54:01', NULL),
(6734, 1701, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-25 07:54:01', NULL),
(6735, 1701, 'Blocks', 'blocks', 'is blocked by', '2013-11-25 07:54:01', NULL),
(6736, 1701, 'Cloners', 'clones', 'is cloned by', '2013-11-25 07:54:01', NULL),
(6737, 1702, 'Relates', 'relates to', 'relates to', '2013-11-27 15:56:01', NULL),
(6738, 1702, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-27 15:56:01', NULL),
(6739, 1702, 'Blocks', 'blocks', 'is blocked by', '2013-11-27 15:56:01', NULL),
(6740, 1702, 'Cloners', 'clones', 'is cloned by', '2013-11-27 15:56:01', NULL),
(6741, 1703, 'Relates', 'relates to', 'relates to', '2013-11-27 18:47:01', NULL),
(6742, 1703, 'Duplicate', 'duplicates', 'is duplicated by', '2013-11-27 18:47:01', NULL),
(6743, 1703, 'Blocks', 'blocks', 'is blocked by', '2013-11-27 18:47:01', NULL),
(6744, 1703, 'Cloners', 'clones', 'is cloned by', '2013-11-27 18:47:01', NULL),
(6745, 1704, 'Relates', 'relates to', 'relates to', '2013-12-02 06:44:01', NULL),
(6746, 1704, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-02 06:44:01', NULL),
(6747, 1704, 'Blocks', 'blocks', 'is blocked by', '2013-12-02 06:44:01', NULL),
(6748, 1704, 'Cloners', 'clones', 'is cloned by', '2013-12-02 06:44:01', NULL),
(6749, 1705, 'Relates', 'relates to', 'relates to', '2013-12-06 01:23:01', NULL),
(6750, 1705, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-06 01:23:01', NULL),
(6751, 1705, 'Blocks', 'blocks', 'is blocked by', '2013-12-06 01:23:01', NULL),
(6752, 1705, 'Cloners', 'clones', 'is cloned by', '2013-12-06 01:23:01', NULL),
(6753, 1706, 'Relates', 'relates to', 'relates to', '2013-12-06 01:49:01', NULL),
(6754, 1706, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-06 01:49:01', NULL),
(6755, 1706, 'Blocks', 'blocks', 'is blocked by', '2013-12-06 01:49:01', NULL),
(6756, 1706, 'Cloners', 'clones', 'is cloned by', '2013-12-06 01:49:01', NULL),
(6757, 1707, 'Relates', 'relates to', 'relates to', '2013-12-07 04:36:01', NULL),
(6758, 1707, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-07 04:36:01', NULL),
(6759, 1707, 'Blocks', 'blocks', 'is blocked by', '2013-12-07 04:36:01', NULL),
(6760, 1707, 'Cloners', 'clones', 'is cloned by', '2013-12-07 04:36:01', NULL),
(6761, 1708, 'Relates', 'relates to', 'relates to', '2013-12-07 11:11:01', NULL),
(6762, 1708, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-07 11:11:01', NULL),
(6763, 1708, 'Blocks', 'blocks', 'is blocked by', '2013-12-07 11:11:01', NULL),
(6764, 1708, 'Cloners', 'clones', 'is cloned by', '2013-12-07 11:11:01', NULL),
(6765, 1709, 'Relates', 'relates to', 'relates to', '2013-12-07 12:41:01', NULL),
(6766, 1709, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-07 12:41:01', NULL),
(6767, 1709, 'Blocks', 'blocks', 'is blocked by', '2013-12-07 12:41:01', NULL),
(6768, 1709, 'Cloners', 'clones', 'is cloned by', '2013-12-07 12:41:01', NULL),
(6769, 1710, 'Relates', 'relates to', 'relates to', '2013-12-09 08:11:02', NULL),
(6770, 1710, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-09 08:11:02', NULL),
(6771, 1710, 'Blocks', 'blocks', 'is blocked by', '2013-12-09 08:11:02', NULL),
(6772, 1710, 'Cloners', 'clones', 'is cloned by', '2013-12-09 08:11:02', NULL),
(6773, 1711, 'Relates', 'relates to', 'relates to', '2013-12-09 20:07:01', NULL),
(6774, 1711, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-09 20:07:01', NULL),
(6775, 1711, 'Blocks', 'blocks', 'is blocked by', '2013-12-09 20:07:01', NULL),
(6776, 1711, 'Cloners', 'clones', 'is cloned by', '2013-12-09 20:07:01', NULL),
(6777, 1712, 'Relates', 'relates to', 'relates to', '2013-12-09 23:42:01', NULL),
(6778, 1712, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-09 23:42:01', NULL),
(6779, 1712, 'Blocks', 'blocks', 'is blocked by', '2013-12-09 23:42:01', NULL),
(6780, 1712, 'Cloners', 'clones', 'is cloned by', '2013-12-09 23:42:01', NULL),
(6781, 1713, 'Relates', 'relates to', 'relates to', '2013-12-10 03:55:02', NULL),
(6782, 1713, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-10 03:55:02', NULL),
(6783, 1713, 'Blocks', 'blocks', 'is blocked by', '2013-12-10 03:55:02', NULL),
(6784, 1713, 'Cloners', 'clones', 'is cloned by', '2013-12-10 03:55:02', NULL),
(6785, 1714, 'Relates', 'relates to', 'relates to', '2013-12-11 07:37:01', NULL),
(6786, 1714, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-11 07:37:01', NULL),
(6787, 1714, 'Blocks', 'blocks', 'is blocked by', '2013-12-11 07:37:01', NULL),
(6788, 1714, 'Cloners', 'clones', 'is cloned by', '2013-12-11 07:37:01', NULL),
(6789, 1715, 'Relates', 'relates to', 'relates to', '2013-12-11 11:33:01', NULL),
(6790, 1715, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-11 11:33:01', NULL),
(6791, 1715, 'Blocks', 'blocks', 'is blocked by', '2013-12-11 11:33:01', NULL),
(6792, 1715, 'Cloners', 'clones', 'is cloned by', '2013-12-11 11:33:01', NULL),
(6793, 1716, 'Relates', 'relates to', 'relates to', '2013-12-19 08:53:01', NULL),
(6794, 1716, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-19 08:53:01', NULL),
(6795, 1716, 'Blocks', 'blocks', 'is blocked by', '2013-12-19 08:53:01', NULL),
(6796, 1716, 'Cloners', 'clones', 'is cloned by', '2013-12-19 08:53:01', NULL),
(6797, 1717, 'Relates', 'relates to', 'relates to', '2013-12-22 20:25:01', NULL),
(6798, 1717, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-22 20:25:01', NULL),
(6799, 1717, 'Blocks', 'blocks', 'is blocked by', '2013-12-22 20:25:01', NULL),
(6800, 1717, 'Cloners', 'clones', 'is cloned by', '2013-12-22 20:25:01', NULL),
(6801, 1718, 'Relates', 'relates to', 'relates to', '2013-12-28 03:51:01', NULL),
(6802, 1718, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-28 03:51:01', NULL),
(6803, 1718, 'Blocks', 'blocks', 'is blocked by', '2013-12-28 03:51:01', NULL),
(6804, 1718, 'Cloners', 'clones', 'is cloned by', '2013-12-28 03:51:01', NULL),
(6805, 1719, 'Relates', 'relates to', 'relates to', '2013-12-28 21:32:02', NULL),
(6806, 1719, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-28 21:32:02', NULL),
(6807, 1719, 'Blocks', 'blocks', 'is blocked by', '2013-12-28 21:32:02', NULL),
(6808, 1719, 'Cloners', 'clones', 'is cloned by', '2013-12-28 21:32:02', NULL),
(6809, 1720, 'Relates', 'relates to', 'relates to', '2013-12-29 19:31:01', NULL),
(6810, 1720, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-29 19:31:01', NULL),
(6811, 1720, 'Blocks', 'blocks', 'is blocked by', '2013-12-29 19:31:01', NULL),
(6812, 1720, 'Cloners', 'clones', 'is cloned by', '2013-12-29 19:31:01', NULL),
(6813, 1721, 'Relates', 'relates to', 'relates to', '2013-12-30 17:39:01', NULL),
(6814, 1721, 'Duplicate', 'duplicates', 'is duplicated by', '2013-12-30 17:39:01', NULL),
(6815, 1721, 'Blocks', 'blocks', 'is blocked by', '2013-12-30 17:39:01', NULL),
(6816, 1721, 'Cloners', 'clones', 'is cloned by', '2013-12-30 17:39:01', NULL),
(6817, 1722, 'Relates', 'relates to', 'relates to', '2014-01-03 12:07:01', NULL),
(6818, 1722, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-03 12:07:01', NULL),
(6819, 1722, 'Blocks', 'blocks', 'is blocked by', '2014-01-03 12:07:01', NULL),
(6820, 1722, 'Cloners', 'clones', 'is cloned by', '2014-01-03 12:07:01', NULL),
(6821, 1723, 'Relates', 'relates to', 'relates to', '2014-01-03 13:46:01', NULL),
(6822, 1723, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-03 13:46:01', NULL),
(6823, 1723, 'Blocks', 'blocks', 'is blocked by', '2014-01-03 13:46:01', NULL),
(6824, 1723, 'Cloners', 'clones', 'is cloned by', '2014-01-03 13:46:01', NULL),
(6825, 1724, 'Relates', 'relates to', 'relates to', '2014-01-03 16:42:01', NULL),
(6826, 1724, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-03 16:42:01', NULL),
(6827, 1724, 'Blocks', 'blocks', 'is blocked by', '2014-01-03 16:42:01', NULL),
(6828, 1724, 'Cloners', 'clones', 'is cloned by', '2014-01-03 16:42:01', NULL),
(6829, 1725, 'Relates', 'relates to', 'relates to', '2014-01-09 15:36:02', NULL),
(6830, 1725, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-09 15:36:02', NULL),
(6831, 1725, 'Blocks', 'blocks', 'is blocked by', '2014-01-09 15:36:02', NULL),
(6832, 1725, 'Cloners', 'clones', 'is cloned by', '2014-01-09 15:36:02', NULL),
(6833, 1726, 'Relates', 'relates to', 'relates to', '2014-01-11 18:46:01', NULL),
(6834, 1726, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-11 18:46:01', NULL),
(6835, 1726, 'Blocks', 'blocks', 'is blocked by', '2014-01-11 18:46:01', NULL),
(6836, 1726, 'Cloners', 'clones', 'is cloned by', '2014-01-11 18:46:01', NULL),
(6837, 1727, 'Relates', 'relates to', 'relates to', '2014-01-19 22:51:01', NULL),
(6838, 1727, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-19 22:51:01', NULL),
(6839, 1727, 'Blocks', 'blocks', 'is blocked by', '2014-01-19 22:51:01', NULL),
(6840, 1727, 'Cloners', 'clones', 'is cloned by', '2014-01-19 22:51:01', NULL),
(6841, 1728, 'Relates', 'relates to', 'relates to', '2014-01-22 17:21:02', NULL),
(6842, 1728, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-22 17:21:02', NULL),
(6843, 1728, 'Blocks', 'blocks', 'is blocked by', '2014-01-22 17:21:02', NULL),
(6844, 1728, 'Cloners', 'clones', 'is cloned by', '2014-01-22 17:21:02', NULL),
(6845, 1729, 'Relates', 'relates to', 'relates to', '2014-01-24 08:48:01', NULL),
(6846, 1729, 'Duplicate', 'duplicates', 'is duplicated by', '2014-01-24 08:48:01', NULL),
(6847, 1729, 'Blocks', 'blocks', 'is blocked by', '2014-01-24 08:48:01', NULL),
(6848, 1729, 'Cloners', 'clones', 'is cloned by', '2014-01-24 08:48:01', NULL),
(6849, 1730, 'Relates', 'relates to', 'relates to', '2014-02-04 13:01:01', NULL),
(6850, 1730, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-04 13:01:01', NULL),
(6851, 1730, 'Blocks', 'blocks', 'is blocked by', '2014-02-04 13:01:01', NULL),
(6852, 1730, 'Cloners', 'clones', 'is cloned by', '2014-02-04 13:01:01', NULL),
(6853, 1731, 'Relates', 'relates to', 'relates to', '2014-02-04 15:51:01', NULL),
(6854, 1731, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-04 15:51:01', NULL),
(6855, 1731, 'Blocks', 'blocks', 'is blocked by', '2014-02-04 15:51:01', NULL),
(6856, 1731, 'Cloners', 'clones', 'is cloned by', '2014-02-04 15:51:01', NULL),
(6857, 1732, 'Relates', 'relates to', 'relates to', '2014-02-10 22:04:02', NULL),
(6858, 1732, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:04:02', NULL),
(6859, 1732, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:04:02', NULL),
(6860, 1732, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:04:02', NULL),
(6861, 1733, 'Relates', 'relates to', 'relates to', '2014-02-10 22:05:01', NULL),
(6862, 1733, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:05:01', NULL),
(6863, 1733, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:05:01', NULL),
(6864, 1733, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:05:01', NULL),
(6865, 1734, 'Relates', 'relates to', 'relates to', '2014-02-10 22:06:01', NULL),
(6866, 1734, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:06:01', NULL),
(6867, 1734, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:06:01', NULL),
(6868, 1734, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:06:01', NULL),
(6869, 1735, 'Relates', 'relates to', 'relates to', '2014-02-10 22:07:01', NULL),
(6870, 1735, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:07:01', NULL),
(6871, 1735, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:07:01', NULL),
(6872, 1735, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:07:01', NULL),
(6873, 1736, 'Relates', 'relates to', 'relates to', '2014-02-10 22:08:01', NULL),
(6874, 1736, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:08:01', NULL),
(6875, 1736, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:08:01', NULL),
(6876, 1736, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:08:01', NULL),
(6877, 1737, 'Relates', 'relates to', 'relates to', '2014-02-10 22:09:01', NULL),
(6878, 1737, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:09:01', NULL),
(6879, 1737, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:09:01', NULL),
(6880, 1737, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:09:01', NULL),
(6881, 1738, 'Relates', 'relates to', 'relates to', '2014-02-10 22:10:01', NULL),
(6882, 1738, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:10:01', NULL),
(6883, 1738, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:10:01', NULL),
(6884, 1738, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:10:01', NULL),
(6885, 1739, 'Relates', 'relates to', 'relates to', '2014-02-10 22:11:01', NULL),
(6886, 1739, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:11:01', NULL),
(6887, 1739, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:11:01', NULL),
(6888, 1739, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:11:01', NULL),
(6889, 1740, 'Relates', 'relates to', 'relates to', '2014-02-10 22:12:01', NULL),
(6890, 1740, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:12:01', NULL),
(6891, 1740, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:12:01', NULL),
(6892, 1740, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:12:01', NULL),
(6893, 1741, 'Relates', 'relates to', 'relates to', '2014-02-10 22:13:01', NULL),
(6894, 1741, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:13:01', NULL),
(6895, 1741, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:13:01', NULL),
(6896, 1741, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:13:01', NULL),
(6897, 1742, 'Relates', 'relates to', 'relates to', '2014-02-10 22:14:01', NULL),
(6898, 1742, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:14:01', NULL),
(6899, 1742, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:14:01', NULL),
(6900, 1742, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:14:01', NULL),
(6901, 1743, 'Relates', 'relates to', 'relates to', '2014-02-10 22:15:01', NULL),
(6902, 1743, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:15:01', NULL),
(6903, 1743, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:15:01', NULL),
(6904, 1743, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:15:01', NULL),
(6905, 1744, 'Relates', 'relates to', 'relates to', '2014-02-10 22:16:01', NULL),
(6906, 1744, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:16:01', NULL),
(6907, 1744, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:16:01', NULL),
(6908, 1744, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:16:01', NULL),
(6909, 1745, 'Relates', 'relates to', 'relates to', '2014-02-10 22:17:02', NULL),
(6910, 1745, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:17:02', NULL),
(6911, 1745, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:17:02', NULL),
(6912, 1745, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:17:02', NULL),
(6913, 1746, 'Relates', 'relates to', 'relates to', '2014-02-10 22:18:01', NULL),
(6914, 1746, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:18:01', NULL),
(6915, 1746, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:18:01', NULL),
(6916, 1746, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:18:01', NULL),
(6917, 1747, 'Relates', 'relates to', 'relates to', '2014-02-10 22:19:01', NULL),
(6918, 1747, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:19:01', NULL),
(6919, 1747, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:19:01', NULL),
(6920, 1747, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:19:01', NULL),
(6921, 1748, 'Relates', 'relates to', 'relates to', '2014-02-10 22:20:01', NULL),
(6922, 1748, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:20:01', NULL),
(6923, 1748, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:20:01', NULL),
(6924, 1748, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:20:01', NULL),
(6925, 1749, 'Relates', 'relates to', 'relates to', '2014-02-10 22:21:02', NULL),
(6926, 1749, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:21:02', NULL),
(6927, 1749, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:21:02', NULL),
(6928, 1749, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:21:02', NULL),
(6929, 1750, 'Relates', 'relates to', 'relates to', '2014-02-10 22:22:02', NULL),
(6930, 1750, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:22:02', NULL),
(6931, 1750, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:22:02', NULL),
(6932, 1750, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:22:02', NULL),
(6933, 1751, 'Relates', 'relates to', 'relates to', '2014-02-10 22:23:01', NULL),
(6934, 1751, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:23:01', NULL),
(6935, 1751, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:23:01', NULL),
(6936, 1751, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:23:01', NULL),
(6937, 1752, 'Relates', 'relates to', 'relates to', '2014-02-10 22:24:01', NULL),
(6938, 1752, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:24:01', NULL),
(6939, 1752, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:24:01', NULL),
(6940, 1752, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:24:01', NULL),
(6941, 1753, 'Relates', 'relates to', 'relates to', '2014-02-10 22:25:01', NULL),
(6942, 1753, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:25:01', NULL),
(6943, 1753, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:25:01', NULL),
(6944, 1753, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:25:01', NULL),
(6945, 1754, 'Relates', 'relates to', 'relates to', '2014-02-10 22:26:01', NULL),
(6946, 1754, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:26:01', NULL),
(6947, 1754, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:26:01', NULL),
(6948, 1754, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:26:01', NULL),
(6949, 1755, 'Relates', 'relates to', 'relates to', '2014-02-10 22:27:01', NULL),
(6950, 1755, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:27:01', NULL),
(6951, 1755, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:27:01', NULL),
(6952, 1755, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:27:01', NULL),
(6953, 1756, 'Relates', 'relates to', 'relates to', '2014-02-10 22:28:01', NULL),
(6954, 1756, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:28:01', NULL),
(6955, 1756, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:28:01', NULL),
(6956, 1756, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:28:01', NULL),
(6957, 1757, 'Relates', 'relates to', 'relates to', '2014-02-10 22:29:01', NULL),
(6958, 1757, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:29:01', NULL),
(6959, 1757, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:29:01', NULL),
(6960, 1757, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:29:01', NULL),
(6961, 1758, 'Relates', 'relates to', 'relates to', '2014-02-10 22:30:01', NULL),
(6962, 1758, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:30:01', NULL),
(6963, 1758, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:30:01', NULL),
(6964, 1758, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:30:01', NULL),
(6965, 1759, 'Relates', 'relates to', 'relates to', '2014-02-10 22:31:01', NULL),
(6966, 1759, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:31:01', NULL),
(6967, 1759, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:31:01', NULL),
(6968, 1759, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:31:01', NULL),
(6969, 1760, 'Relates', 'relates to', 'relates to', '2014-02-10 22:32:01', NULL),
(6970, 1760, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:32:01', NULL),
(6971, 1760, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:32:01', NULL),
(6972, 1760, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:32:01', NULL),
(6973, 1761, 'Relates', 'relates to', 'relates to', '2014-02-10 22:33:01', NULL),
(6974, 1761, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:33:01', NULL),
(6975, 1761, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:33:01', NULL),
(6976, 1761, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:33:01', NULL),
(6977, 1762, 'Relates', 'relates to', 'relates to', '2014-02-10 22:34:02', NULL),
(6978, 1762, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:34:02', NULL),
(6979, 1762, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:34:02', NULL),
(6980, 1762, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:34:02', NULL),
(6981, 1763, 'Relates', 'relates to', 'relates to', '2014-02-10 22:35:01', NULL),
(6982, 1763, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:35:01', NULL),
(6983, 1763, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:35:01', NULL),
(6984, 1763, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:35:01', NULL),
(6985, 1764, 'Relates', 'relates to', 'relates to', '2014-02-10 22:36:01', NULL),
(6986, 1764, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:36:01', NULL),
(6987, 1764, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:36:01', NULL),
(6988, 1764, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:36:01', NULL),
(6989, 1765, 'Relates', 'relates to', 'relates to', '2014-02-10 22:37:02', NULL),
(6990, 1765, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:37:02', NULL),
(6991, 1765, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:37:02', NULL),
(6992, 1765, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:37:02', NULL),
(6993, 1766, 'Relates', 'relates to', 'relates to', '2014-02-10 22:38:01', NULL),
(6994, 1766, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:38:01', NULL),
(6995, 1766, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:38:01', NULL),
(6996, 1766, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:38:01', NULL),
(6997, 1767, 'Relates', 'relates to', 'relates to', '2014-02-10 22:39:01', NULL),
(6998, 1767, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:39:01', NULL),
(6999, 1767, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:39:01', NULL),
(7000, 1767, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:39:01', NULL),
(7001, 1768, 'Relates', 'relates to', 'relates to', '2014-02-10 22:40:01', NULL),
(7002, 1768, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:40:01', NULL),
(7003, 1768, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:40:01', NULL),
(7004, 1768, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:40:01', NULL),
(7005, 1769, 'Relates', 'relates to', 'relates to', '2014-02-10 22:41:01', NULL),
(7006, 1769, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:41:01', NULL),
(7007, 1769, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:41:01', NULL),
(7008, 1769, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:41:01', NULL),
(7009, 1770, 'Relates', 'relates to', 'relates to', '2014-02-10 22:42:01', NULL),
(7010, 1770, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:42:01', NULL),
(7011, 1770, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:42:01', NULL),
(7012, 1770, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:42:01', NULL),
(7013, 1771, 'Relates', 'relates to', 'relates to', '2014-02-10 22:43:01', NULL),
(7014, 1771, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:43:01', NULL),
(7015, 1771, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:43:01', NULL),
(7016, 1771, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:43:01', NULL),
(7017, 1772, 'Relates', 'relates to', 'relates to', '2014-02-10 22:44:01', NULL),
(7018, 1772, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:44:01', NULL),
(7019, 1772, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:44:01', NULL),
(7020, 1772, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:44:01', NULL),
(7021, 1773, 'Relates', 'relates to', 'relates to', '2014-02-10 22:45:02', NULL),
(7022, 1773, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:45:02', NULL),
(7023, 1773, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:45:02', NULL),
(7024, 1773, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:45:02', NULL),
(7025, 1774, 'Relates', 'relates to', 'relates to', '2014-02-10 22:46:01', NULL),
(7026, 1774, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:46:01', NULL),
(7027, 1774, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:46:01', NULL),
(7028, 1774, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:46:01', NULL),
(7029, 1775, 'Relates', 'relates to', 'relates to', '2014-02-10 22:47:01', NULL),
(7030, 1775, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:47:01', NULL),
(7031, 1775, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:47:01', NULL),
(7032, 1775, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:47:01', NULL),
(7033, 1776, 'Relates', 'relates to', 'relates to', '2014-02-10 22:48:01', NULL),
(7034, 1776, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:48:01', NULL),
(7035, 1776, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:48:01', NULL),
(7036, 1776, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:48:01', NULL),
(7037, 1777, 'Relates', 'relates to', 'relates to', '2014-02-10 22:49:02', NULL),
(7038, 1777, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:49:02', NULL),
(7039, 1777, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:49:02', NULL),
(7040, 1777, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:49:02', NULL),
(7041, 1778, 'Relates', 'relates to', 'relates to', '2014-02-10 22:50:01', NULL),
(7042, 1778, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:50:01', NULL),
(7043, 1778, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:50:01', NULL),
(7044, 1778, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:50:01', NULL),
(7045, 1779, 'Relates', 'relates to', 'relates to', '2014-02-10 22:51:01', NULL),
(7046, 1779, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:51:01', NULL),
(7047, 1779, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:51:01', NULL),
(7048, 1779, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:51:01', NULL),
(7049, 1780, 'Relates', 'relates to', 'relates to', '2014-02-10 22:52:01', NULL),
(7050, 1780, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-10 22:52:01', NULL),
(7051, 1780, 'Blocks', 'blocks', 'is blocked by', '2014-02-10 22:52:01', NULL),
(7052, 1780, 'Cloners', 'clones', 'is cloned by', '2014-02-10 22:52:01', NULL),
(7053, 1781, 'Relates', 'relates to', 'relates to', '2014-02-11 07:19:01', NULL),
(7054, 1781, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-11 07:19:01', NULL),
(7055, 1781, 'Blocks', 'blocks', 'is blocked by', '2014-02-11 07:19:01', NULL),
(7056, 1781, 'Cloners', 'clones', 'is cloned by', '2014-02-11 07:19:01', NULL),
(7057, 1782, 'Relates', 'relates to', 'relates to', '2014-02-11 11:51:01', NULL),
(7058, 1782, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-11 11:51:01', NULL),
(7059, 1782, 'Blocks', 'blocks', 'is blocked by', '2014-02-11 11:51:01', NULL),
(7060, 1782, 'Cloners', 'clones', 'is cloned by', '2014-02-11 11:51:01', NULL),
(7061, 1783, 'Relates', 'relates to', 'relates to', '2014-02-11 11:53:01', NULL),
(7062, 1783, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-11 11:53:01', NULL),
(7063, 1783, 'Blocks', 'blocks', 'is blocked by', '2014-02-11 11:53:01', NULL),
(7064, 1783, 'Cloners', 'clones', 'is cloned by', '2014-02-11 11:53:01', NULL),
(7065, 1784, 'Relates', 'relates to', 'relates to', '2014-02-13 14:25:01', NULL),
(7066, 1784, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-13 14:25:01', NULL),
(7067, 1784, 'Blocks', 'blocks', 'is blocked by', '2014-02-13 14:25:01', NULL),
(7068, 1784, 'Cloners', 'clones', 'is cloned by', '2014-02-13 14:25:01', NULL),
(7069, 1785, 'Relates', 'relates to', 'relates to', '2014-02-13 15:11:02', NULL),
(7070, 1785, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-13 15:11:02', NULL),
(7071, 1785, 'Blocks', 'blocks', 'is blocked by', '2014-02-13 15:11:02', NULL),
(7072, 1785, 'Cloners', 'clones', 'is cloned by', '2014-02-13 15:11:02', NULL),
(7073, 1786, 'Relates', 'relates to', 'relates to', '2014-02-13 17:35:01', NULL),
(7074, 1786, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-13 17:35:01', NULL),
(7075, 1786, 'Blocks', 'blocks', 'is blocked by', '2014-02-13 17:35:01', NULL),
(7076, 1786, 'Cloners', 'clones', 'is cloned by', '2014-02-13 17:35:01', NULL),
(7077, 1787, 'Relates', 'relates to', 'relates to', '2014-02-14 02:07:01', NULL),
(7078, 1787, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-14 02:07:01', NULL),
(7079, 1787, 'Blocks', 'blocks', 'is blocked by', '2014-02-14 02:07:01', NULL),
(7080, 1787, 'Cloners', 'clones', 'is cloned by', '2014-02-14 02:07:01', NULL),
(7081, 1788, 'Relates', 'relates to', 'relates to', '2014-02-14 11:18:01', NULL),
(7082, 1788, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-14 11:18:01', NULL),
(7083, 1788, 'Blocks', 'blocks', 'is blocked by', '2014-02-14 11:18:01', NULL),
(7084, 1788, 'Cloners', 'clones', 'is cloned by', '2014-02-14 11:18:01', NULL),
(7085, 1789, 'Relates', 'relates to', 'relates to', '2014-02-14 20:24:01', NULL),
(7086, 1789, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-14 20:24:01', NULL),
(7087, 1789, 'Blocks', 'blocks', 'is blocked by', '2014-02-14 20:24:01', NULL),
(7088, 1789, 'Cloners', 'clones', 'is cloned by', '2014-02-14 20:24:01', NULL),
(7089, 1790, 'Relates', 'relates to', 'relates to', '2014-02-16 21:37:01', NULL),
(7090, 1790, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-16 21:37:01', NULL),
(7091, 1790, 'Blocks', 'blocks', 'is blocked by', '2014-02-16 21:37:01', NULL),
(7092, 1790, 'Cloners', 'clones', 'is cloned by', '2014-02-16 21:37:01', NULL),
(7093, 1791, 'Relates', 'relates to', 'relates to', '2014-02-17 16:02:01', NULL),
(7094, 1791, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-17 16:02:01', NULL),
(7095, 1791, 'Blocks', 'blocks', 'is blocked by', '2014-02-17 16:02:01', NULL),
(7096, 1791, 'Cloners', 'clones', 'is cloned by', '2014-02-17 16:02:01', NULL),
(7097, 1792, 'Relates', 'relates to', 'relates to', '2014-02-19 13:32:01', NULL),
(7098, 1792, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-19 13:32:01', NULL),
(7099, 1792, 'Blocks', 'blocks', 'is blocked by', '2014-02-19 13:32:01', NULL),
(7100, 1792, 'Cloners', 'clones', 'is cloned by', '2014-02-19 13:32:01', NULL),
(7101, 1793, 'Relates', 'relates to', 'relates to', '2014-02-20 17:55:01', NULL),
(7102, 1793, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-20 17:55:01', NULL),
(7103, 1793, 'Blocks', 'blocks', 'is blocked by', '2014-02-20 17:55:01', NULL),
(7104, 1793, 'Cloners', 'clones', 'is cloned by', '2014-02-20 17:55:01', NULL),
(7105, 1794, 'Relates', 'relates to', 'relates to', '2014-02-22 04:05:01', NULL),
(7106, 1794, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-22 04:05:01', NULL),
(7107, 1794, 'Blocks', 'blocks', 'is blocked by', '2014-02-22 04:05:01', NULL),
(7108, 1794, 'Cloners', 'clones', 'is cloned by', '2014-02-22 04:05:01', NULL),
(7109, 1795, 'Relates', 'relates to', 'relates to', '2014-02-22 22:26:01', NULL),
(7110, 1795, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-22 22:26:01', NULL),
(7111, 1795, 'Blocks', 'blocks', 'is blocked by', '2014-02-22 22:26:01', NULL),
(7112, 1795, 'Cloners', 'clones', 'is cloned by', '2014-02-22 22:26:01', NULL),
(7113, 1796, 'Relates', 'relates to', 'relates to', '2014-02-24 07:43:02', NULL),
(7114, 1796, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-24 07:43:02', NULL),
(7115, 1796, 'Blocks', 'blocks', 'is blocked by', '2014-02-24 07:43:02', NULL),
(7116, 1796, 'Cloners', 'clones', 'is cloned by', '2014-02-24 07:43:02', NULL),
(7117, 1797, 'Relates', 'relates to', 'relates to', '2014-02-25 09:32:01', NULL),
(7118, 1797, 'Duplicate', 'duplicates', 'is duplicated by', '2014-02-25 09:32:01', NULL),
(7119, 1797, 'Blocks', 'blocks', 'is blocked by', '2014-02-25 09:32:01', NULL),
(7120, 1797, 'Cloners', 'clones', 'is cloned by', '2014-02-25 09:32:01', NULL),
(7121, 1798, 'Relates', 'relates to', 'relates to', '2014-03-04 15:20:02', NULL),
(7122, 1798, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-04 15:20:02', NULL),
(7123, 1798, 'Blocks', 'blocks', 'is blocked by', '2014-03-04 15:20:02', NULL),
(7124, 1798, 'Cloners', 'clones', 'is cloned by', '2014-03-04 15:20:02', NULL),
(7125, 1799, 'Relates', 'relates to', 'relates to', '2014-03-09 07:05:01', NULL),
(7126, 1799, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-09 07:05:01', NULL),
(7127, 1799, 'Blocks', 'blocks', 'is blocked by', '2014-03-09 07:05:01', NULL),
(7128, 1799, 'Cloners', 'clones', 'is cloned by', '2014-03-09 07:05:01', NULL),
(7129, 1800, 'Relates', 'relates to', 'relates to', '2014-03-11 07:54:01', NULL),
(7130, 1800, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-11 07:54:01', NULL),
(7131, 1800, 'Blocks', 'blocks', 'is blocked by', '2014-03-11 07:54:01', NULL),
(7132, 1800, 'Cloners', 'clones', 'is cloned by', '2014-03-11 07:54:01', NULL),
(7133, 1801, 'Relates', 'relates to', 'relates to', '2014-03-11 15:36:02', NULL),
(7134, 1801, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-11 15:36:02', NULL);
INSERT INTO `issue_link_type` (`id`, `client_id`, `name`, `outward_description`, `inward_description`, `date_created`, `date_updated`) VALUES
(7135, 1801, 'Blocks', 'blocks', 'is blocked by', '2014-03-11 15:36:02', NULL),
(7136, 1801, 'Cloners', 'clones', 'is cloned by', '2014-03-11 15:36:02', NULL),
(7137, 1802, 'Relates', 'relates to', 'relates to', '2014-03-12 15:42:01', NULL),
(7138, 1802, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:42:01', NULL),
(7139, 1802, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:42:01', NULL),
(7140, 1802, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:42:01', NULL),
(7141, 1803, 'Relates', 'relates to', 'relates to', '2014-03-12 15:42:02', NULL),
(7142, 1803, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:42:02', NULL),
(7143, 1803, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:42:02', NULL),
(7144, 1803, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:42:02', NULL),
(7145, 1804, 'Relates', 'relates to', 'relates to', '2014-03-12 15:43:01', NULL),
(7146, 1804, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:43:01', NULL),
(7147, 1804, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:43:01', NULL),
(7148, 1804, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:43:01', NULL),
(7149, 1805, 'Relates', 'relates to', 'relates to', '2014-03-12 15:43:01', NULL),
(7150, 1805, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:43:01', NULL),
(7151, 1805, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:43:01', NULL),
(7152, 1805, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:43:01', NULL),
(7153, 1806, 'Relates', 'relates to', 'relates to', '2014-03-12 15:47:01', NULL),
(7154, 1806, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:47:01', NULL),
(7155, 1806, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:47:01', NULL),
(7156, 1806, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:47:01', NULL),
(7157, 1807, 'Relates', 'relates to', 'relates to', '2014-03-12 15:49:01', NULL),
(7158, 1807, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:49:01', NULL),
(7159, 1807, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:49:01', NULL),
(7160, 1807, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:49:01', NULL),
(7161, 1808, 'Relates', 'relates to', 'relates to', '2014-03-12 15:50:01', NULL),
(7162, 1808, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:50:01', NULL),
(7163, 1808, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:50:01', NULL),
(7164, 1808, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:50:01', NULL),
(7165, 1809, 'Relates', 'relates to', 'relates to', '2014-03-12 15:51:02', NULL),
(7166, 1809, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:51:02', NULL),
(7167, 1809, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:51:02', NULL),
(7168, 1809, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:51:02', NULL),
(7169, 1810, 'Relates', 'relates to', 'relates to', '2014-03-12 15:51:02', NULL),
(7170, 1810, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:51:02', NULL),
(7171, 1810, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:51:02', NULL),
(7172, 1810, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:51:02', NULL),
(7173, 1811, 'Relates', 'relates to', 'relates to', '2014-03-12 15:53:01', NULL),
(7174, 1811, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:53:01', NULL),
(7175, 1811, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:53:01', NULL),
(7176, 1811, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:53:01', NULL),
(7177, 1812, 'Relates', 'relates to', 'relates to', '2014-03-12 15:54:01', NULL),
(7178, 1812, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:54:01', NULL),
(7179, 1812, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:54:01', NULL),
(7180, 1812, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:54:01', NULL),
(7181, 1813, 'Relates', 'relates to', 'relates to', '2014-03-12 15:55:01', NULL),
(7182, 1813, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:55:01', NULL),
(7183, 1813, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:55:01', NULL),
(7184, 1813, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:55:01', NULL),
(7185, 1814, 'Relates', 'relates to', 'relates to', '2014-03-12 15:55:01', NULL),
(7186, 1814, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 15:55:01', NULL),
(7187, 1814, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 15:55:01', NULL),
(7188, 1814, 'Cloners', 'clones', 'is cloned by', '2014-03-12 15:55:01', NULL),
(7189, 1815, 'Relates', 'relates to', 'relates to', '2014-03-12 16:00:02', NULL),
(7190, 1815, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:00:02', NULL),
(7191, 1815, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:00:02', NULL),
(7192, 1815, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:00:02', NULL),
(7193, 1816, 'Relates', 'relates to', 'relates to', '2014-03-12 16:01:01', NULL),
(7194, 1816, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:01:01', NULL),
(7195, 1816, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:01:01', NULL),
(7196, 1816, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:01:01', NULL),
(7197, 1817, 'Relates', 'relates to', 'relates to', '2014-03-12 16:01:01', NULL),
(7198, 1817, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:01:01', NULL),
(7199, 1817, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:01:01', NULL),
(7200, 1817, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:01:01', NULL),
(7201, 1818, 'Relates', 'relates to', 'relates to', '2014-03-12 16:04:01', NULL),
(7202, 1818, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:04:01', NULL),
(7203, 1818, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:04:01', NULL),
(7204, 1818, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:04:01', NULL),
(7205, 1819, 'Relates', 'relates to', 'relates to', '2014-03-12 16:07:01', NULL),
(7206, 1819, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:07:01', NULL),
(7207, 1819, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:07:01', NULL),
(7208, 1819, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:07:01', NULL),
(7209, 1820, 'Relates', 'relates to', 'relates to', '2014-03-12 16:07:01', NULL),
(7210, 1820, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:07:01', NULL),
(7211, 1820, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:07:01', NULL),
(7212, 1820, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:07:01', NULL),
(7213, 1821, 'Relates', 'relates to', 'relates to', '2014-03-12 16:09:01', NULL),
(7214, 1821, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:09:01', NULL),
(7215, 1821, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:09:01', NULL),
(7216, 1821, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:09:01', NULL),
(7217, 1822, 'Relates', 'relates to', 'relates to', '2014-03-12 16:10:01', NULL),
(7218, 1822, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:10:01', NULL),
(7219, 1822, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:10:01', NULL),
(7220, 1822, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:10:01', NULL),
(7221, 1823, 'Relates', 'relates to', 'relates to', '2014-03-12 16:13:02', NULL),
(7222, 1823, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:13:02', NULL),
(7223, 1823, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:13:02', NULL),
(7224, 1823, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:13:02', NULL),
(7225, 1824, 'Relates', 'relates to', 'relates to', '2014-03-12 16:14:01', NULL),
(7226, 1824, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:14:01', NULL),
(7227, 1824, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:14:01', NULL),
(7228, 1824, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:14:01', NULL),
(7229, 1825, 'Relates', 'relates to', 'relates to', '2014-03-12 16:17:01', NULL),
(7230, 1825, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:17:01', NULL),
(7231, 1825, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:17:01', NULL),
(7232, 1825, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:17:01', NULL),
(7233, 1826, 'Relates', 'relates to', 'relates to', '2014-03-12 16:17:01', NULL),
(7234, 1826, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:17:01', NULL),
(7235, 1826, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:17:01', NULL),
(7236, 1826, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:17:01', NULL),
(7237, 1827, 'Relates', 'relates to', 'relates to', '2014-03-12 16:17:01', NULL),
(7238, 1827, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:17:01', NULL),
(7239, 1827, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:17:01', NULL),
(7240, 1827, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:17:01', NULL),
(7241, 1828, 'Relates', 'relates to', 'relates to', '2014-03-12 16:18:01', NULL),
(7242, 1828, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:18:01', NULL),
(7243, 1828, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:18:01', NULL),
(7244, 1828, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:18:01', NULL),
(7245, 1829, 'Relates', 'relates to', 'relates to', '2014-03-12 16:21:01', NULL),
(7246, 1829, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:21:01', NULL),
(7247, 1829, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:21:01', NULL),
(7248, 1829, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:21:01', NULL),
(7249, 1830, 'Relates', 'relates to', 'relates to', '2014-03-12 16:23:02', NULL),
(7250, 1830, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:23:02', NULL),
(7251, 1830, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:23:02', NULL),
(7252, 1830, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:23:02', NULL),
(7253, 1831, 'Relates', 'relates to', 'relates to', '2014-03-12 16:23:02', NULL),
(7254, 1831, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:23:02', NULL),
(7255, 1831, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:23:02', NULL),
(7256, 1831, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:23:02', NULL),
(7257, 1832, 'Relates', 'relates to', 'relates to', '2014-03-12 16:25:01', NULL),
(7258, 1832, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:25:01', NULL),
(7259, 1832, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:25:01', NULL),
(7260, 1832, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:25:01', NULL),
(7261, 1833, 'Relates', 'relates to', 'relates to', '2014-03-12 16:25:02', NULL),
(7262, 1833, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:25:02', NULL),
(7263, 1833, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:25:02', NULL),
(7264, 1833, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:25:02', NULL),
(7265, 1834, 'Relates', 'relates to', 'relates to', '2014-03-12 16:26:01', NULL),
(7266, 1834, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:26:01', NULL),
(7267, 1834, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:26:01', NULL),
(7268, 1834, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:26:01', NULL),
(7269, 1835, 'Relates', 'relates to', 'relates to', '2014-03-12 16:27:01', NULL),
(7270, 1835, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:27:01', NULL),
(7271, 1835, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:27:01', NULL),
(7272, 1835, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:27:01', NULL),
(7273, 1836, 'Relates', 'relates to', 'relates to', '2014-03-12 16:29:01', NULL),
(7274, 1836, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:29:01', NULL),
(7275, 1836, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:29:01', NULL),
(7276, 1836, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:29:01', NULL),
(7277, 1837, 'Relates', 'relates to', 'relates to', '2014-03-12 16:32:01', NULL),
(7278, 1837, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:32:01', NULL),
(7279, 1837, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:32:01', NULL),
(7280, 1837, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:32:01', NULL),
(7281, 1838, 'Relates', 'relates to', 'relates to', '2014-03-12 16:37:01', NULL),
(7282, 1838, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:37:01', NULL),
(7283, 1838, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:37:01', NULL),
(7284, 1838, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:37:01', NULL),
(7285, 1839, 'Relates', 'relates to', 'relates to', '2014-03-12 16:38:01', NULL),
(7286, 1839, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:38:01', NULL),
(7287, 1839, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:38:01', NULL),
(7288, 1839, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:38:01', NULL),
(7289, 1840, 'Relates', 'relates to', 'relates to', '2014-03-12 16:47:01', NULL),
(7290, 1840, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:47:01', NULL),
(7291, 1840, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:47:01', NULL),
(7292, 1840, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:47:01', NULL),
(7293, 1841, 'Relates', 'relates to', 'relates to', '2014-03-12 16:48:01', NULL),
(7294, 1841, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:48:01', NULL),
(7295, 1841, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:48:01', NULL),
(7296, 1841, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:48:01', NULL),
(7297, 1842, 'Relates', 'relates to', 'relates to', '2014-03-12 16:48:02', NULL),
(7298, 1842, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:48:02', NULL),
(7299, 1842, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:48:02', NULL),
(7300, 1842, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:48:02', NULL),
(7301, 1843, 'Relates', 'relates to', 'relates to', '2014-03-12 16:54:01', NULL),
(7302, 1843, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:54:01', NULL),
(7303, 1843, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:54:01', NULL),
(7304, 1843, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:54:01', NULL),
(7305, 1844, 'Relates', 'relates to', 'relates to', '2014-03-12 16:57:02', NULL),
(7306, 1844, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:57:02', NULL),
(7307, 1844, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:57:02', NULL),
(7308, 1844, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:57:02', NULL),
(7309, 1845, 'Relates', 'relates to', 'relates to', '2014-03-12 16:58:01', NULL),
(7310, 1845, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 16:58:01', NULL),
(7311, 1845, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 16:58:01', NULL),
(7312, 1845, 'Cloners', 'clones', 'is cloned by', '2014-03-12 16:58:01', NULL),
(7313, 1846, 'Relates', 'relates to', 'relates to', '2014-03-12 17:01:01', NULL),
(7314, 1846, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:01:01', NULL),
(7315, 1846, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:01:01', NULL),
(7316, 1846, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:01:01', NULL),
(7317, 1847, 'Relates', 'relates to', 'relates to', '2014-03-12 17:05:01', NULL),
(7318, 1847, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:05:01', NULL),
(7319, 1847, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:05:01', NULL),
(7320, 1847, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:05:01', NULL),
(7321, 1848, 'Relates', 'relates to', 'relates to', '2014-03-12 17:07:01', NULL),
(7322, 1848, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:07:01', NULL),
(7323, 1848, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:07:01', NULL),
(7324, 1848, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:07:01', NULL),
(7325, 1849, 'Relates', 'relates to', 'relates to', '2014-03-12 17:09:01', NULL),
(7326, 1849, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:09:01', NULL),
(7327, 1849, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:09:01', NULL),
(7328, 1849, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:09:01', NULL),
(7329, 1850, 'Relates', 'relates to', 'relates to', '2014-03-12 17:16:02', NULL),
(7330, 1850, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:16:02', NULL),
(7331, 1850, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:16:02', NULL),
(7332, 1850, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:16:02', NULL),
(7333, 1851, 'Relates', 'relates to', 'relates to', '2014-03-12 17:19:01', NULL),
(7334, 1851, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:19:01', NULL),
(7335, 1851, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:19:01', NULL),
(7336, 1851, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:19:01', NULL),
(7337, 1852, 'Relates', 'relates to', 'relates to', '2014-03-12 17:20:01', NULL),
(7338, 1852, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:20:01', NULL),
(7339, 1852, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:20:01', NULL),
(7340, 1852, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:20:01', NULL),
(7341, 1853, 'Relates', 'relates to', 'relates to', '2014-03-12 17:26:02', NULL),
(7342, 1853, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:26:02', NULL),
(7343, 1853, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:26:02', NULL),
(7344, 1853, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:26:02', NULL),
(7345, 1854, 'Relates', 'relates to', 'relates to', '2014-03-12 17:26:02', NULL),
(7346, 1854, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:26:02', NULL),
(7347, 1854, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:26:02', NULL),
(7348, 1854, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:26:02', NULL),
(7349, 1855, 'Relates', 'relates to', 'relates to', '2014-03-12 17:33:01', NULL),
(7350, 1855, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:33:01', NULL),
(7351, 1855, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:33:01', NULL),
(7352, 1855, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:33:01', NULL),
(7353, 1856, 'Relates', 'relates to', 'relates to', '2014-03-12 17:36:01', NULL),
(7354, 1856, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:36:01', NULL),
(7355, 1856, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:36:01', NULL),
(7356, 1856, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:36:01', NULL),
(7357, 1857, 'Relates', 'relates to', 'relates to', '2014-03-12 17:44:01', NULL),
(7358, 1857, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:44:01', NULL),
(7359, 1857, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:44:01', NULL),
(7360, 1857, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:44:01', NULL),
(7361, 1858, 'Relates', 'relates to', 'relates to', '2014-03-12 17:48:01', NULL),
(7362, 1858, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:48:01', NULL),
(7363, 1858, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:48:01', NULL),
(7364, 1858, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:48:01', NULL),
(7365, 1859, 'Relates', 'relates to', 'relates to', '2014-03-12 17:49:01', NULL),
(7366, 1859, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:49:01', NULL),
(7367, 1859, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:49:01', NULL),
(7368, 1859, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:49:01', NULL),
(7369, 1860, 'Relates', 'relates to', 'relates to', '2014-03-12 17:52:01', NULL),
(7370, 1860, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:52:01', NULL),
(7371, 1860, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:52:01', NULL),
(7372, 1860, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:52:01', NULL),
(7373, 1861, 'Relates', 'relates to', 'relates to', '2014-03-12 17:55:01', NULL),
(7374, 1861, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 17:55:01', NULL),
(7375, 1861, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 17:55:01', NULL),
(7376, 1861, 'Cloners', 'clones', 'is cloned by', '2014-03-12 17:55:01', NULL),
(7377, 1862, 'Relates', 'relates to', 'relates to', '2014-03-12 18:11:01', NULL),
(7378, 1862, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:11:01', NULL),
(7379, 1862, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:11:01', NULL),
(7380, 1862, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:11:01', NULL),
(7381, 1863, 'Relates', 'relates to', 'relates to', '2014-03-12 18:14:01', NULL),
(7382, 1863, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:14:01', NULL),
(7383, 1863, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:14:01', NULL),
(7384, 1863, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:14:01', NULL),
(7385, 1864, 'Relates', 'relates to', 'relates to', '2014-03-12 18:15:01', NULL),
(7386, 1864, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:15:01', NULL),
(7387, 1864, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:15:01', NULL),
(7388, 1864, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:15:01', NULL),
(7389, 1865, 'Relates', 'relates to', 'relates to', '2014-03-12 18:28:01', NULL),
(7390, 1865, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:28:01', NULL),
(7391, 1865, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:28:01', NULL),
(7392, 1865, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:28:01', NULL),
(7393, 1866, 'Relates', 'relates to', 'relates to', '2014-03-12 18:34:01', NULL),
(7394, 1866, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:34:01', NULL),
(7395, 1866, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:34:01', NULL),
(7396, 1866, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:34:01', NULL),
(7397, 1867, 'Relates', 'relates to', 'relates to', '2014-03-12 18:48:01', NULL),
(7398, 1867, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 18:48:01', NULL),
(7399, 1867, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 18:48:01', NULL),
(7400, 1867, 'Cloners', 'clones', 'is cloned by', '2014-03-12 18:48:01', NULL),
(7401, 1868, 'Relates', 'relates to', 'relates to', '2014-03-12 19:00:01', NULL),
(7402, 1868, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:00:01', NULL),
(7403, 1868, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:00:01', NULL),
(7404, 1868, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:00:01', NULL),
(7405, 1869, 'Relates', 'relates to', 'relates to', '2014-03-12 19:03:01', NULL),
(7406, 1869, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:03:01', NULL),
(7407, 1869, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:03:01', NULL),
(7408, 1869, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:03:01', NULL),
(7409, 1870, 'Relates', 'relates to', 'relates to', '2014-03-12 19:05:01', NULL),
(7410, 1870, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:05:01', NULL),
(7411, 1870, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:05:01', NULL),
(7412, 1870, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:05:01', NULL),
(7413, 1871, 'Relates', 'relates to', 'relates to', '2014-03-12 19:10:01', NULL),
(7414, 1871, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:10:01', NULL),
(7415, 1871, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:10:01', NULL),
(7416, 1871, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:10:01', NULL),
(7417, 1872, 'Relates', 'relates to', 'relates to', '2014-03-12 19:14:01', NULL),
(7418, 1872, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:14:01', NULL),
(7419, 1872, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:14:01', NULL),
(7420, 1872, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:14:01', NULL),
(7421, 1873, 'Relates', 'relates to', 'relates to', '2014-03-12 19:29:01', NULL),
(7422, 1873, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:29:01', NULL),
(7423, 1873, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:29:01', NULL),
(7424, 1873, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:29:01', NULL),
(7425, 1874, 'Relates', 'relates to', 'relates to', '2014-03-12 19:55:01', NULL),
(7426, 1874, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:55:01', NULL),
(7427, 1874, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:55:01', NULL),
(7428, 1874, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:55:01', NULL),
(7429, 1875, 'Relates', 'relates to', 'relates to', '2014-03-12 19:57:02', NULL),
(7430, 1875, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:57:02', NULL),
(7431, 1875, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:57:02', NULL),
(7432, 1875, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:57:02', NULL),
(7433, 1876, 'Relates', 'relates to', 'relates to', '2014-03-12 19:58:01', NULL),
(7434, 1876, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 19:58:01', NULL),
(7435, 1876, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 19:58:01', NULL),
(7436, 1876, 'Cloners', 'clones', 'is cloned by', '2014-03-12 19:58:01', NULL),
(7437, 1877, 'Relates', 'relates to', 'relates to', '2014-03-12 20:20:01', NULL),
(7438, 1877, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:20:01', NULL),
(7439, 1877, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:20:01', NULL),
(7440, 1877, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:20:01', NULL),
(7441, 1878, 'Relates', 'relates to', 'relates to', '2014-03-12 20:22:02', NULL),
(7442, 1878, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:22:02', NULL),
(7443, 1878, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:22:02', NULL),
(7444, 1878, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:22:02', NULL),
(7445, 1879, 'Relates', 'relates to', 'relates to', '2014-03-12 20:27:01', NULL),
(7446, 1879, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:27:01', NULL),
(7447, 1879, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:27:01', NULL),
(7448, 1879, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:27:01', NULL),
(7449, 1880, 'Relates', 'relates to', 'relates to', '2014-03-12 20:29:01', NULL),
(7450, 1880, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:29:01', NULL),
(7451, 1880, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:29:01', NULL),
(7452, 1880, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:29:01', NULL),
(7453, 1881, 'Relates', 'relates to', 'relates to', '2014-03-12 20:40:01', NULL),
(7454, 1881, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:40:01', NULL),
(7455, 1881, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:40:01', NULL),
(7456, 1881, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:40:01', NULL),
(7457, 1882, 'Relates', 'relates to', 'relates to', '2014-03-12 20:57:01', NULL),
(7458, 1882, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 20:57:01', NULL),
(7459, 1882, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 20:57:01', NULL),
(7460, 1882, 'Cloners', 'clones', 'is cloned by', '2014-03-12 20:57:01', NULL),
(7461, 1883, 'Relates', 'relates to', 'relates to', '2014-03-12 21:02:01', NULL),
(7462, 1883, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 21:02:01', NULL),
(7463, 1883, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 21:02:01', NULL),
(7464, 1883, 'Cloners', 'clones', 'is cloned by', '2014-03-12 21:02:01', NULL),
(7465, 1884, 'Relates', 'relates to', 'relates to', '2014-03-12 21:44:01', NULL),
(7466, 1884, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 21:44:01', NULL),
(7467, 1884, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 21:44:01', NULL),
(7468, 1884, 'Cloners', 'clones', 'is cloned by', '2014-03-12 21:44:01', NULL),
(7469, 1885, 'Relates', 'relates to', 'relates to', '2014-03-12 22:09:01', NULL),
(7470, 1885, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:09:01', NULL),
(7471, 1885, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:09:01', NULL),
(7472, 1885, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:09:01', NULL),
(7473, 1886, 'Relates', 'relates to', 'relates to', '2014-03-12 22:14:01', NULL),
(7474, 1886, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:14:01', NULL),
(7475, 1886, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:14:01', NULL),
(7476, 1886, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:14:01', NULL),
(7477, 1887, 'Relates', 'relates to', 'relates to', '2014-03-12 22:16:01', NULL),
(7478, 1887, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:16:01', NULL),
(7479, 1887, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:16:01', NULL),
(7480, 1887, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:16:01', NULL),
(7481, 1888, 'Relates', 'relates to', 'relates to', '2014-03-12 22:19:01', NULL),
(7482, 1888, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:19:01', NULL),
(7483, 1888, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:19:01', NULL),
(7484, 1888, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:19:01', NULL),
(7485, 1889, 'Relates', 'relates to', 'relates to', '2014-03-12 22:49:01', NULL),
(7486, 1889, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:49:01', NULL),
(7487, 1889, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:49:01', NULL),
(7488, 1889, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:49:01', NULL),
(7489, 1890, 'Relates', 'relates to', 'relates to', '2014-03-12 22:59:01', NULL),
(7490, 1890, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 22:59:01', NULL),
(7491, 1890, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 22:59:01', NULL),
(7492, 1890, 'Cloners', 'clones', 'is cloned by', '2014-03-12 22:59:01', NULL),
(7493, 1891, 'Relates', 'relates to', 'relates to', '2014-03-12 23:21:01', NULL),
(7494, 1891, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 23:21:01', NULL),
(7495, 1891, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 23:21:01', NULL),
(7496, 1891, 'Cloners', 'clones', 'is cloned by', '2014-03-12 23:21:01', NULL),
(7497, 1892, 'Relates', 'relates to', 'relates to', '2014-03-12 23:30:02', NULL),
(7498, 1892, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 23:30:02', NULL),
(7499, 1892, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 23:30:02', NULL),
(7500, 1892, 'Cloners', 'clones', 'is cloned by', '2014-03-12 23:30:02', NULL),
(7501, 1893, 'Relates', 'relates to', 'relates to', '2014-03-12 23:44:01', NULL),
(7502, 1893, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-12 23:44:01', NULL),
(7503, 1893, 'Blocks', 'blocks', 'is blocked by', '2014-03-12 23:44:01', NULL),
(7504, 1893, 'Cloners', 'clones', 'is cloned by', '2014-03-12 23:44:01', NULL),
(7505, 1894, 'Relates', 'relates to', 'relates to', '2014-03-13 02:20:01', NULL),
(7506, 1894, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 02:20:01', NULL),
(7507, 1894, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 02:20:01', NULL),
(7508, 1894, 'Cloners', 'clones', 'is cloned by', '2014-03-13 02:20:01', NULL),
(7509, 1895, 'Relates', 'relates to', 'relates to', '2014-03-13 02:39:02', NULL),
(7510, 1895, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 02:39:02', NULL),
(7511, 1895, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 02:39:02', NULL),
(7512, 1895, 'Cloners', 'clones', 'is cloned by', '2014-03-13 02:39:02', NULL),
(7513, 1896, 'Relates', 'relates to', 'relates to', '2014-03-13 03:07:01', NULL),
(7514, 1896, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 03:07:01', NULL),
(7515, 1896, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 03:07:01', NULL),
(7516, 1896, 'Cloners', 'clones', 'is cloned by', '2014-03-13 03:07:01', NULL),
(7517, 1897, 'Relates', 'relates to', 'relates to', '2014-03-13 04:06:01', NULL),
(7518, 1897, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 04:06:01', NULL),
(7519, 1897, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 04:06:01', NULL),
(7520, 1897, 'Cloners', 'clones', 'is cloned by', '2014-03-13 04:06:01', NULL),
(7521, 1898, 'Relates', 'relates to', 'relates to', '2014-03-13 05:21:02', NULL),
(7522, 1898, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 05:21:02', NULL),
(7523, 1898, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 05:21:02', NULL),
(7524, 1898, 'Cloners', 'clones', 'is cloned by', '2014-03-13 05:21:02', NULL),
(7525, 1899, 'Relates', 'relates to', 'relates to', '2014-03-13 06:02:01', NULL),
(7526, 1899, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 06:02:01', NULL),
(7527, 1899, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 06:02:01', NULL),
(7528, 1899, 'Cloners', 'clones', 'is cloned by', '2014-03-13 06:02:01', NULL),
(7529, 1900, 'Relates', 'relates to', 'relates to', '2014-03-13 08:15:01', NULL),
(7530, 1900, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 08:15:01', NULL),
(7531, 1900, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 08:15:01', NULL),
(7532, 1900, 'Cloners', 'clones', 'is cloned by', '2014-03-13 08:15:01', NULL),
(7533, 1901, 'Relates', 'relates to', 'relates to', '2014-03-13 08:54:02', NULL),
(7534, 1901, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 08:54:02', NULL),
(7535, 1901, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 08:54:02', NULL),
(7536, 1901, 'Cloners', 'clones', 'is cloned by', '2014-03-13 08:54:02', NULL),
(7537, 1902, 'Relates', 'relates to', 'relates to', '2014-03-13 12:49:01', NULL),
(7538, 1902, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 12:49:01', NULL),
(7539, 1902, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 12:49:01', NULL),
(7540, 1902, 'Cloners', 'clones', 'is cloned by', '2014-03-13 12:49:01', NULL),
(7541, 1903, 'Relates', 'relates to', 'relates to', '2014-03-13 13:02:01', NULL),
(7542, 1903, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 13:02:01', NULL),
(7543, 1903, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 13:02:01', NULL),
(7544, 1903, 'Cloners', 'clones', 'is cloned by', '2014-03-13 13:02:01', NULL),
(7545, 1904, 'Relates', 'relates to', 'relates to', '2014-03-13 14:04:02', NULL),
(7546, 1904, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 14:04:02', NULL),
(7547, 1904, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 14:04:02', NULL),
(7548, 1904, 'Cloners', 'clones', 'is cloned by', '2014-03-13 14:04:02', NULL),
(7549, 1905, 'Relates', 'relates to', 'relates to', '2014-03-13 14:16:02', NULL),
(7550, 1905, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 14:16:02', NULL),
(7551, 1905, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 14:16:02', NULL),
(7552, 1905, 'Cloners', 'clones', 'is cloned by', '2014-03-13 14:16:02', NULL),
(7553, 1906, 'Relates', 'relates to', 'relates to', '2014-03-13 14:35:01', NULL),
(7554, 1906, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 14:35:01', NULL),
(7555, 1906, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 14:35:01', NULL),
(7556, 1906, 'Cloners', 'clones', 'is cloned by', '2014-03-13 14:35:01', NULL),
(7557, 1907, 'Relates', 'relates to', 'relates to', '2014-03-13 16:23:02', NULL),
(7558, 1907, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 16:23:02', NULL),
(7559, 1907, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 16:23:02', NULL),
(7560, 1907, 'Cloners', 'clones', 'is cloned by', '2014-03-13 16:23:02', NULL),
(7561, 1908, 'Relates', 'relates to', 'relates to', '2014-03-13 16:54:01', NULL),
(7562, 1908, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 16:54:01', NULL),
(7563, 1908, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 16:54:01', NULL),
(7564, 1908, 'Cloners', 'clones', 'is cloned by', '2014-03-13 16:54:01', NULL),
(7565, 1909, 'Relates', 'relates to', 'relates to', '2014-03-13 16:56:01', NULL),
(7566, 1909, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 16:56:01', NULL),
(7567, 1909, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 16:56:01', NULL),
(7568, 1909, 'Cloners', 'clones', 'is cloned by', '2014-03-13 16:56:01', NULL),
(7569, 1910, 'Relates', 'relates to', 'relates to', '2014-03-13 19:30:01', NULL),
(7570, 1910, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 19:30:01', NULL),
(7571, 1910, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 19:30:01', NULL),
(7572, 1910, 'Cloners', 'clones', 'is cloned by', '2014-03-13 19:30:01', NULL),
(7573, 1911, 'Relates', 'relates to', 'relates to', '2014-03-13 19:58:01', NULL),
(7574, 1911, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 19:58:01', NULL),
(7575, 1911, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 19:58:01', NULL),
(7576, 1911, 'Cloners', 'clones', 'is cloned by', '2014-03-13 19:58:01', NULL),
(7577, 1912, 'Relates', 'relates to', 'relates to', '2014-03-13 20:12:01', NULL),
(7578, 1912, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 20:12:01', NULL),
(7579, 1912, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 20:12:01', NULL),
(7580, 1912, 'Cloners', 'clones', 'is cloned by', '2014-03-13 20:12:01', NULL),
(7581, 1913, 'Relates', 'relates to', 'relates to', '2014-03-13 23:55:02', NULL),
(7582, 1913, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-13 23:55:02', NULL),
(7583, 1913, 'Blocks', 'blocks', 'is blocked by', '2014-03-13 23:55:02', NULL),
(7584, 1913, 'Cloners', 'clones', 'is cloned by', '2014-03-13 23:55:02', NULL),
(7585, 1914, 'Relates', 'relates to', 'relates to', '2014-03-14 04:22:01', NULL),
(7586, 1914, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-14 04:22:01', NULL),
(7587, 1914, 'Blocks', 'blocks', 'is blocked by', '2014-03-14 04:22:01', NULL),
(7588, 1914, 'Cloners', 'clones', 'is cloned by', '2014-03-14 04:22:01', NULL),
(7589, 1915, 'Relates', 'relates to', 'relates to', '2014-03-14 07:33:01', NULL),
(7590, 1915, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-14 07:33:01', NULL),
(7591, 1915, 'Blocks', 'blocks', 'is blocked by', '2014-03-14 07:33:01', NULL),
(7592, 1915, 'Cloners', 'clones', 'is cloned by', '2014-03-14 07:33:01', NULL),
(7593, 1916, 'Relates', 'relates to', 'relates to', '2014-03-14 08:36:01', NULL),
(7594, 1916, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-14 08:36:01', NULL),
(7595, 1916, 'Blocks', 'blocks', 'is blocked by', '2014-03-14 08:36:01', NULL),
(7596, 1916, 'Cloners', 'clones', 'is cloned by', '2014-03-14 08:36:01', NULL),
(7597, 1917, 'Relates', 'relates to', 'relates to', '2014-03-14 09:57:01', NULL),
(7598, 1917, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-14 09:57:01', NULL),
(7599, 1917, 'Blocks', 'blocks', 'is blocked by', '2014-03-14 09:57:01', NULL),
(7600, 1917, 'Cloners', 'clones', 'is cloned by', '2014-03-14 09:57:01', NULL),
(7601, 1918, 'Relates', 'relates to', 'relates to', '2014-03-14 15:45:01', NULL),
(7602, 1918, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-14 15:45:01', NULL),
(7603, 1918, 'Blocks', 'blocks', 'is blocked by', '2014-03-14 15:45:01', NULL),
(7604, 1918, 'Cloners', 'clones', 'is cloned by', '2014-03-14 15:45:01', NULL),
(7605, 1919, 'Relates', 'relates to', 'relates to', '2014-03-15 00:28:01', NULL),
(7606, 1919, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-15 00:28:01', NULL),
(7607, 1919, 'Blocks', 'blocks', 'is blocked by', '2014-03-15 00:28:01', NULL),
(7608, 1919, 'Cloners', 'clones', 'is cloned by', '2014-03-15 00:28:01', NULL),
(7609, 1920, 'Relates', 'relates to', 'relates to', '2014-03-15 22:23:01', NULL),
(7610, 1920, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-15 22:23:01', NULL),
(7611, 1920, 'Blocks', 'blocks', 'is blocked by', '2014-03-15 22:23:01', NULL),
(7612, 1920, 'Cloners', 'clones', 'is cloned by', '2014-03-15 22:23:01', NULL),
(7613, 1921, 'Relates', 'relates to', 'relates to', '2014-03-16 06:12:01', NULL),
(7614, 1921, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-16 06:12:01', NULL),
(7615, 1921, 'Blocks', 'blocks', 'is blocked by', '2014-03-16 06:12:01', NULL),
(7616, 1921, 'Cloners', 'clones', 'is cloned by', '2014-03-16 06:12:01', NULL),
(7617, 1922, 'Relates', 'relates to', 'relates to', '2014-03-16 13:45:01', NULL),
(7618, 1922, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-16 13:45:01', NULL),
(7619, 1922, 'Blocks', 'blocks', 'is blocked by', '2014-03-16 13:45:01', NULL),
(7620, 1922, 'Cloners', 'clones', 'is cloned by', '2014-03-16 13:45:01', NULL),
(7621, 1923, 'Relates', 'relates to', 'relates to', '2014-03-17 20:01:01', NULL),
(7622, 1923, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-17 20:01:01', NULL),
(7623, 1923, 'Blocks', 'blocks', 'is blocked by', '2014-03-17 20:01:01', NULL),
(7624, 1923, 'Cloners', 'clones', 'is cloned by', '2014-03-17 20:01:01', NULL),
(7625, 1924, 'Relates', 'relates to', 'relates to', '2014-03-18 00:12:01', NULL),
(7626, 1924, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-18 00:12:01', NULL),
(7627, 1924, 'Blocks', 'blocks', 'is blocked by', '2014-03-18 00:12:01', NULL),
(7628, 1924, 'Cloners', 'clones', 'is cloned by', '2014-03-18 00:12:01', NULL),
(7629, 1925, 'Relates', 'relates to', 'relates to', '2014-03-19 19:24:01', NULL),
(7630, 1925, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-19 19:24:01', NULL),
(7631, 1925, 'Blocks', 'blocks', 'is blocked by', '2014-03-19 19:24:01', NULL),
(7632, 1925, 'Cloners', 'clones', 'is cloned by', '2014-03-19 19:24:01', NULL),
(7633, 1926, 'Relates', 'relates to', 'relates to', '2014-03-20 12:05:01', NULL),
(7634, 1926, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-20 12:05:01', NULL),
(7635, 1926, 'Blocks', 'blocks', 'is blocked by', '2014-03-20 12:05:01', NULL),
(7636, 1926, 'Cloners', 'clones', 'is cloned by', '2014-03-20 12:05:01', NULL),
(7637, 1927, 'Relates', 'relates to', 'relates to', '2014-03-20 13:41:01', NULL),
(7638, 1927, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-20 13:41:01', NULL),
(7639, 1927, 'Blocks', 'blocks', 'is blocked by', '2014-03-20 13:41:01', NULL),
(7640, 1927, 'Cloners', 'clones', 'is cloned by', '2014-03-20 13:41:01', NULL),
(7641, 1928, 'Relates', 'relates to', 'relates to', '2014-03-21 18:34:01', NULL),
(7642, 1928, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-21 18:34:01', NULL),
(7643, 1928, 'Blocks', 'blocks', 'is blocked by', '2014-03-21 18:34:01', NULL),
(7644, 1928, 'Cloners', 'clones', 'is cloned by', '2014-03-21 18:34:01', NULL),
(7645, 1929, 'Relates', 'relates to', 'relates to', '2014-03-25 14:02:01', NULL),
(7646, 1929, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-25 14:02:01', NULL),
(7647, 1929, 'Blocks', 'blocks', 'is blocked by', '2014-03-25 14:02:01', NULL),
(7648, 1929, 'Cloners', 'clones', 'is cloned by', '2014-03-25 14:02:01', NULL),
(7649, 1930, 'Relates', 'relates to', 'relates to', '2014-03-26 19:15:01', NULL),
(7650, 1930, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-26 19:15:01', NULL),
(7651, 1930, 'Blocks', 'blocks', 'is blocked by', '2014-03-26 19:15:01', NULL),
(7652, 1930, 'Cloners', 'clones', 'is cloned by', '2014-03-26 19:15:01', NULL),
(7653, 1931, 'Relates', 'relates to', 'relates to', '2014-03-28 10:23:01', NULL),
(7654, 1931, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-28 10:23:01', NULL),
(7655, 1931, 'Blocks', 'blocks', 'is blocked by', '2014-03-28 10:23:01', NULL),
(7656, 1931, 'Cloners', 'clones', 'is cloned by', '2014-03-28 10:23:01', NULL),
(7657, 1932, 'Relates', 'relates to', 'relates to', '2014-03-28 11:49:01', NULL),
(7658, 1932, 'Duplicate', 'duplicates', 'is duplicated by', '2014-03-28 11:49:01', NULL),
(7659, 1932, 'Blocks', 'blocks', 'is blocked by', '2014-03-28 11:49:01', NULL),
(7660, 1932, 'Cloners', 'clones', 'is cloned by', '2014-03-28 11:49:01', NULL),
(7661, 1933, 'Relates', 'relates to', 'relates to', '2014-04-04 10:16:01', NULL),
(7662, 1933, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-04 10:16:01', NULL),
(7663, 1933, 'Blocks', 'blocks', 'is blocked by', '2014-04-04 10:16:01', NULL),
(7664, 1933, 'Cloners', 'clones', 'is cloned by', '2014-04-04 10:16:01', NULL),
(7665, 1934, 'Relates', 'relates to', 'relates to', '2014-04-08 19:49:01', NULL),
(7666, 1934, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-08 19:49:01', NULL),
(7667, 1934, 'Blocks', 'blocks', 'is blocked by', '2014-04-08 19:49:01', NULL),
(7668, 1934, 'Cloners', 'clones', 'is cloned by', '2014-04-08 19:49:01', NULL),
(7669, 1935, 'Relates', 'relates to', 'relates to', '2014-04-11 09:56:01', NULL),
(7670, 1935, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-11 09:56:01', NULL),
(7671, 1935, 'Blocks', 'blocks', 'is blocked by', '2014-04-11 09:56:01', NULL),
(7672, 1935, 'Cloners', 'clones', 'is cloned by', '2014-04-11 09:56:01', NULL),
(7673, 1936, 'Relates', 'relates to', 'relates to', '2014-04-15 21:46:01', NULL),
(7674, 1936, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-15 21:46:01', NULL),
(7675, 1936, 'Blocks', 'blocks', 'is blocked by', '2014-04-15 21:46:01', NULL),
(7676, 1936, 'Cloners', 'clones', 'is cloned by', '2014-04-15 21:46:01', NULL),
(7677, 1937, 'Relates', 'relates to', 'relates to', '2014-04-21 07:56:01', NULL),
(7678, 1937, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-21 07:56:01', NULL),
(7679, 1937, 'Blocks', 'blocks', 'is blocked by', '2014-04-21 07:56:01', NULL),
(7680, 1937, 'Cloners', 'clones', 'is cloned by', '2014-04-21 07:56:01', NULL),
(7681, 1938, 'Relates', 'relates to', 'relates to', '2014-04-23 08:00:01', NULL),
(7682, 1938, 'Duplicate', 'duplicates', 'is duplicated by', '2014-04-23 08:00:01', NULL),
(7683, 1938, 'Blocks', 'blocks', 'is blocked by', '2014-04-23 08:00:01', NULL),
(7684, 1938, 'Cloners', 'clones', 'is cloned by', '2014-04-23 08:00:01', NULL),
(7685, 1939, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:01', NULL),
(7686, 1939, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:01', NULL),
(7687, 1939, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:01', NULL),
(7688, 1939, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:01', NULL),
(7689, 1940, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:02', NULL),
(7690, 1940, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:02', NULL),
(7691, 1940, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:02', NULL),
(7692, 1940, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:02', NULL),
(7693, 1941, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:02', NULL),
(7694, 1941, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:02', NULL),
(7695, 1941, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:02', NULL),
(7696, 1941, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:02', NULL),
(7697, 1942, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:02', NULL),
(7698, 1942, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:02', NULL),
(7699, 1942, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:02', NULL),
(7700, 1942, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:02', NULL),
(7701, 1943, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:02', NULL),
(7702, 1943, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:02', NULL),
(7703, 1943, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:02', NULL),
(7704, 1943, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:02', NULL),
(7705, 1944, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:02', NULL),
(7706, 1944, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:02', NULL),
(7707, 1944, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:02', NULL),
(7708, 1944, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:02', NULL),
(7709, 1945, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:03', NULL),
(7710, 1945, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:03', NULL),
(7711, 1945, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:03', NULL),
(7712, 1945, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:03', NULL),
(7713, 1946, 'Relates', 'relates to', 'relates to', '2014-05-08 19:17:03', NULL),
(7714, 1946, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-08 19:17:03', NULL),
(7715, 1946, 'Blocks', 'blocks', 'is blocked by', '2014-05-08 19:17:03', NULL),
(7716, 1946, 'Cloners', 'clones', 'is cloned by', '2014-05-08 19:17:03', NULL),
(7717, 1947, 'Relates', 'relates to', 'relates to', '2014-05-14 10:00:01', NULL),
(7718, 1947, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-14 10:00:01', NULL),
(7719, 1947, 'Blocks', 'blocks', 'is blocked by', '2014-05-14 10:00:01', NULL),
(7720, 1947, 'Cloners', 'clones', 'is cloned by', '2014-05-14 10:00:01', NULL),
(7721, 1948, 'Relates', 'relates to', 'relates to', '2014-05-16 22:22:01', NULL),
(7722, 1948, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-16 22:22:01', NULL),
(7723, 1948, 'Blocks', 'blocks', 'is blocked by', '2014-05-16 22:22:01', NULL),
(7724, 1948, 'Cloners', 'clones', 'is cloned by', '2014-05-16 22:22:01', NULL),
(7725, 1949, 'Relates', 'relates to', 'relates to', '2014-05-22 16:37:02', NULL),
(7726, 1949, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-22 16:37:02', NULL),
(7727, 1949, 'Blocks', 'blocks', 'is blocked by', '2014-05-22 16:37:02', NULL),
(7728, 1949, 'Cloners', 'clones', 'is cloned by', '2014-05-22 16:37:02', NULL),
(7729, 1950, 'Relates', 'relates to', 'relates to', '2014-05-23 09:10:02', NULL),
(7730, 1950, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-23 09:10:02', NULL),
(7731, 1950, 'Blocks', 'blocks', 'is blocked by', '2014-05-23 09:10:02', NULL),
(7732, 1950, 'Cloners', 'clones', 'is cloned by', '2014-05-23 09:10:02', NULL),
(7733, 1951, 'Relates', 'relates to', 'relates to', '2014-05-25 13:55:01', NULL),
(7734, 1951, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-25 13:55:01', NULL),
(7735, 1951, 'Blocks', 'blocks', 'is blocked by', '2014-05-25 13:55:01', NULL),
(7736, 1951, 'Cloners', 'clones', 'is cloned by', '2014-05-25 13:55:01', NULL),
(7737, 1952, 'Relates', 'relates to', 'relates to', '2014-05-30 13:07:01', NULL),
(7738, 1952, 'Duplicate', 'duplicates', 'is duplicated by', '2014-05-30 13:07:01', NULL),
(7739, 1952, 'Blocks', 'blocks', 'is blocked by', '2014-05-30 13:07:01', NULL),
(7740, 1952, 'Cloners', 'clones', 'is cloned by', '2014-05-30 13:07:01', NULL),
(7741, 1953, 'Relates', 'relates to', 'relates to', '2014-06-06 18:21:01', NULL),
(7742, 1953, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-06 18:21:01', NULL),
(7743, 1953, 'Blocks', 'blocks', 'is blocked by', '2014-06-06 18:21:01', NULL),
(7744, 1953, 'Cloners', 'clones', 'is cloned by', '2014-06-06 18:21:01', NULL),
(7745, 1954, 'Relates', 'relates to', 'relates to', '2014-06-10 17:19:02', NULL),
(7746, 1954, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-10 17:19:02', NULL),
(7747, 1954, 'Blocks', 'blocks', 'is blocked by', '2014-06-10 17:19:02', NULL),
(7748, 1954, 'Cloners', 'clones', 'is cloned by', '2014-06-10 17:19:02', NULL),
(7749, 1955, 'Relates', 'relates to', 'relates to', '2014-06-17 13:34:02', NULL);
INSERT INTO `issue_link_type` (`id`, `client_id`, `name`, `outward_description`, `inward_description`, `date_created`, `date_updated`) VALUES
(7750, 1955, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-17 13:34:02', NULL),
(7751, 1955, 'Blocks', 'blocks', 'is blocked by', '2014-06-17 13:34:02', NULL),
(7752, 1955, 'Cloners', 'clones', 'is cloned by', '2014-06-17 13:34:02', NULL),
(7753, 1956, 'Relates', 'relates to', 'relates to', '2014-06-26 12:08:36', NULL),
(7754, 1956, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-26 12:08:36', NULL),
(7755, 1956, 'Blocks', 'blocks', 'is blocked by', '2014-06-26 12:08:36', NULL),
(7756, 1956, 'Cloners', 'clones', 'is cloned by', '2014-06-26 12:08:36', NULL),
(7757, 1957, 'Relates', 'relates to', 'relates to', '2014-06-26 12:11:23', NULL),
(7758, 1957, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-26 12:11:23', NULL),
(7759, 1957, 'Blocks', 'blocks', 'is blocked by', '2014-06-26 12:11:23', NULL),
(7760, 1957, 'Cloners', 'clones', 'is cloned by', '2014-06-26 12:11:23', NULL),
(7761, 1958, 'Relates', 'relates to', 'relates to', '2014-06-26 12:17:30', NULL),
(7762, 1958, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-26 12:17:30', NULL),
(7763, 1958, 'Blocks', 'blocks', 'is blocked by', '2014-06-26 12:17:30', NULL),
(7764, 1958, 'Cloners', 'clones', 'is cloned by', '2014-06-26 12:17:30', NULL),
(7765, 1959, 'Relates', 'relates to', 'relates to', '2014-06-26 14:00:20', NULL),
(7766, 1959, 'Duplicate', 'duplicates', 'is duplicated by', '2014-06-26 14:00:20', NULL),
(7767, 1959, 'Blocks', 'blocks', 'is blocked by', '2014-06-26 14:00:20', NULL),
(7768, 1959, 'Cloners', 'clones', 'is cloned by', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_priority`
--

CREATE TABLE IF NOT EXISTS `issue_priority` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `icon_name` varchar(50) NOT NULL,
  `color` varchar(7) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9786 ;

--
-- Dumping data for table `issue_priority`
--

INSERT INTO `issue_priority` (`id`, `client_id`, `name`, `description`, `icon_name`, `color`, `date_created`, `date_updated`) VALUES
(1, 1, 'Minor', NULL, 'minor.png', '#006600', '2012-10-04 16:37:37', NULL),
(2, 1, 'Major', NULL, 'major.png', '#009900', '2012-10-04 16:37:37', NULL),
(3, 1, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-04 16:37:37', NULL),
(4, 1, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-04 16:37:37', NULL),
(9, 3, 'Minor', NULL, 'minor.png', '#006600', '2012-10-10 15:34:05', NULL),
(10, 3, 'Major', NULL, 'major.png', '#009900', '2012-10-10 15:34:05', NULL),
(11, 3, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-10 15:34:05', NULL),
(12, 3, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-10 15:34:05', NULL),
(13, 4, 'Minor', NULL, 'minor.png', '#006600', '2012-10-16 00:08:09', NULL),
(14, 4, 'Major', NULL, 'major.png', '#009900', '2012-10-16 00:08:09', NULL),
(15, 4, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-16 00:08:09', NULL),
(16, 4, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-16 00:08:09', NULL),
(17, 5, 'Minor', NULL, 'minor.png', '#006600', '2012-10-17 14:31:15', NULL),
(18, 5, 'Major', NULL, 'major.png', '#009900', '2012-10-17 14:31:15', NULL),
(19, 5, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-17 14:31:15', NULL),
(20, 5, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-17 14:31:15', NULL),
(21, 6, 'Minor', NULL, 'minor.png', '#006600', '2012-10-21 11:23:16', NULL),
(22, 6, 'Major', NULL, 'major.png', '#009900', '2012-10-21 11:23:16', NULL),
(23, 6, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-21 11:23:16', NULL),
(24, 6, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-21 11:23:16', NULL),
(25, 7, 'Minor', NULL, 'minor.png', '#006600', '2012-10-27 15:35:58', NULL),
(26, 7, 'Major', NULL, 'major.png', '#009900', '2012-10-27 15:35:58', NULL),
(27, 7, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-27 15:35:58', NULL),
(28, 7, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-27 15:35:58', NULL),
(29, 9, 'Minor', NULL, 'minor.png', '#006600', '2012-10-27 15:40:22', NULL),
(30, 9, 'Major', NULL, 'major.png', '#009900', '2012-10-27 15:40:22', NULL),
(31, 9, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-27 15:40:22', NULL),
(32, 9, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-27 15:40:22', NULL),
(33, 10, 'Minor', NULL, 'minor.png', '#006600', '2012-10-30 15:41:29', NULL),
(34, 10, 'Major', NULL, 'major.png', '#009900', '2012-10-30 15:41:29', NULL),
(35, 10, 'Critical', NULL, 'critical.png', '#ff0000', '2012-10-30 15:41:29', NULL),
(36, 10, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-10-30 15:41:29', NULL),
(37, 11, 'Minor', NULL, 'minor.png', '#006600', '2012-11-22 14:21:54', NULL),
(38, 11, 'Major', NULL, 'major.png', '#009900', '2012-11-22 14:21:54', NULL),
(39, 11, 'Critical', NULL, 'critical.png', '#ff0000', '2012-11-22 14:21:54', NULL),
(40, 11, 'Blocker', NULL, 'blocker.png', '#cc0000', '2012-11-22 14:21:54', NULL),
(69, 19, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2012-12-05 16:05:42', NULL),
(70, 19, 'Major', 'Major loss of function.', 'major.png', '#009900', '2012-12-05 16:05:42', NULL),
(71, 19, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#ff0000', '2012-12-05 16:05:42', NULL),
(72, 19, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#cc0000', '2012-12-05 16:05:42', NULL),
(73, 19, 'Trivial', 'Cosmetic problem like misspelt words or misaligned text.', 'trivial.png', '#003300', '2012-12-05 16:05:42', NULL),
(84, 22, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2012-12-06 09:11:41', NULL),
(85, 22, 'Major', 'Major loss of function.', 'major.png', '#009900', '2012-12-06 09:11:41', NULL),
(86, 22, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#ff0000', '2012-12-06 09:11:41', NULL),
(87, 22, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#cc0000', '2012-12-06 09:11:41', NULL),
(88, 22, 'Trivial', 'Cosmetic problem like misspelt words or misaligned text.', 'trivial.png', '#003300', '2012-12-06 09:11:41', NULL),
(169, 40, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2012-12-21 07:07:35', NULL),
(170, 40, 'Major', 'Major loss of function.', 'major.png', '#009900', '2012-12-21 07:07:35', NULL),
(171, 40, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#ff0000', '2012-12-21 07:07:35', NULL),
(172, 40, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#cc0000', '2012-12-21 07:07:35', NULL),
(173, 40, 'Trivial', 'Cosmetic problem like misspelt words or misaligned text.', 'trivial.png', '#003300', '2012-12-21 07:07:35', NULL),
(229, 53, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '', '2013-01-16 21:25:53', NULL),
(230, 53, 'Major', 'Major loss of function.', 'major.png', '', '2013-01-16 21:25:53', NULL),
(231, 53, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '', '2013-01-16 21:25:53', NULL),
(232, 53, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '', '2013-01-16 21:25:53', NULL),
(233, 53, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '', '2013-01-16 21:25:53', NULL),
(951, 0, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '', '0000-00-00 00:00:00', NULL),
(952, 0, 'Major', 'Major loss of function.', 'major.png', '', '0000-00-00 00:00:00', NULL),
(953, 0, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '', '0000-00-00 00:00:00', NULL),
(954, 0, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '', '0000-00-00 00:00:00', NULL),
(955, 0, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '', '0000-00-00 00:00:00', NULL),
(9681, 1940, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2014-05-08 19:17:02', NULL),
(9682, 1940, 'Major', 'Major loss of function.', 'major.png', '#009900', '2014-05-08 19:17:02', NULL),
(9683, 1940, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#FF0000', '2014-05-08 19:17:02', NULL),
(9684, 1940, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#CC0000', '2014-05-08 19:17:02', NULL),
(9685, 1940, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '#003300', '2014-05-08 19:17:02', NULL),
(9691, 1942, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2014-05-08 19:17:02', NULL),
(9692, 1942, 'Major', 'Major loss of function.', 'major.png', '#009900', '2014-05-08 19:17:02', NULL),
(9693, 1942, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#FF0000', '2014-05-08 19:17:02', NULL),
(9694, 1942, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#CC0000', '2014-05-08 19:17:02', NULL),
(9695, 1942, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '#003300', '2014-05-08 19:17:02', NULL),
(9701, 1944, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2014-05-08 19:17:02', NULL),
(9702, 1944, 'Major', 'Major loss of function.', 'major.png', '#009900', '2014-05-08 19:17:02', NULL),
(9703, 1944, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#FF0000', '2014-05-08 19:17:02', NULL),
(9704, 1944, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#CC0000', '2014-05-08 19:17:02', NULL),
(9705, 1944, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '#003300', '2014-05-08 19:17:02', NULL),
(9776, 1959, 'Minor', 'Minor loss of function, or other problem where easy workaround is present.', 'minor.png', '#006600', '2014-06-26 14:00:20', NULL),
(9777, 1959, 'Major', 'Major loss of function.', 'major.png', '#009900', '2014-06-26 14:00:20', NULL),
(9778, 1959, 'Critical', 'Crashes, loss of data, severe memory leak.', 'critical.png', '#FF0000', '2014-06-26 14:00:20', NULL),
(9779, 1959, 'Blocker', 'Blocks development and/or testing work, production could not run.', 'blocker.png', '#CC0000', '2014-06-26 14:00:20', NULL),
(9780, 1959, 'Trivial', 'Cosmetic problem like misspelled words or misaligned text.', 'trivial.png', '#003300', '2014-06-26 14:00:20', NULL),
(9781, 1959, 'P1 (today, ASAP)', '', 'generic.png', '#FF0000', '2014-06-26 12:00:46', NULL),
(9782, 1959, 'P2 (urgent)', '', 'generic.png', '#0831FF', '2014-06-26 12:01:11', NULL),
(9783, 1959, 'P3 (before final delivery)', '', 'generic.png', '#FFE600', '2014-06-26 12:01:35', NULL),
(9784, 1959, 'P4 (on customer request)', '', 'generic.png', '#BFFBFF', '2014-06-26 12:01:54', NULL),
(9785, 1959, 'P5 (nice to have)', '', 'generic.png', '#F2C7FF', '2014-06-26 12:02:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_resolution`
--

CREATE TABLE IF NOT EXISTS `issue_resolution` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9808 ;

--
-- Dumping data for table `issue_resolution`
--

INSERT INTO `issue_resolution` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1, 1, 'Fixed', NULL, '2012-10-04 16:37:37', NULL),
(2, 1, 'Unable to reproduce', NULL, '2012-10-04 16:37:37', NULL),
(3, 1, 'Not fixable', NULL, '2012-10-04 16:37:37', NULL),
(4, 1, 'Duplicate', NULL, '2012-10-04 16:37:37', NULL),
(5, 1, 'No change required', NULL, '2012-10-04 16:37:37', NULL),
(11, 3, 'Fixed', NULL, '2012-10-10 15:34:05', NULL),
(12, 3, 'Unable to reproduce', NULL, '2012-10-10 15:34:05', NULL),
(13, 3, 'Not fixable', NULL, '2012-10-10 15:34:05', NULL),
(14, 3, 'Duplicate', NULL, '2012-10-10 15:34:05', NULL),
(15, 3, 'No change required', NULL, '2012-10-10 15:34:05', NULL),
(16, 4, 'Fixed', NULL, '2012-10-16 00:08:09', NULL),
(17, 4, 'Unable to reproduce', NULL, '2012-10-16 00:08:09', NULL),
(18, 4, 'Not fixable', NULL, '2012-10-16 00:08:09', NULL),
(19, 4, 'Duplicate', NULL, '2012-10-16 00:08:09', NULL),
(20, 4, 'No change required', NULL, '2012-10-16 00:08:09', NULL),
(21, 5, 'Fixed', NULL, '2012-10-17 14:31:15', NULL),
(22, 5, 'Unable to reproduce', NULL, '2012-10-17 14:31:15', NULL),
(23, 5, 'Not fixable', NULL, '2012-10-17 14:31:15', NULL),
(24, 5, 'Duplicate', NULL, '2012-10-17 14:31:15', NULL),
(25, 5, 'No change required', NULL, '2012-10-17 14:31:15', NULL),
(26, 6, 'Fixed', NULL, '2012-10-21 11:23:16', NULL),
(27, 6, 'Unable to reproduce', NULL, '2012-10-21 11:23:16', NULL),
(28, 6, 'Not fixable', NULL, '2012-10-21 11:23:16', NULL),
(29, 6, 'Duplicate', NULL, '2012-10-21 11:23:16', NULL),
(30, 6, 'No change required', NULL, '2012-10-21 11:23:16', NULL),
(31, 7, 'Fixed', NULL, '2012-10-27 15:35:58', NULL),
(32, 7, 'Unable to reproduce', NULL, '2012-10-27 15:35:58', NULL),
(33, 7, 'Not fixable', NULL, '2012-10-27 15:35:58', NULL),
(34, 7, 'Duplicate', NULL, '2012-10-27 15:35:58', NULL),
(35, 7, 'No change required', NULL, '2012-10-27 15:35:58', NULL),
(36, 9, 'Fixed', NULL, '2012-10-27 15:40:22', NULL),
(37, 9, 'Unable to reproduce', NULL, '2012-10-27 15:40:22', NULL),
(38, 9, 'Not fixable', NULL, '2012-10-27 15:40:22', NULL),
(39, 9, 'Duplicate', NULL, '2012-10-27 15:40:22', NULL),
(40, 9, 'No change required', NULL, '2012-10-27 15:40:22', NULL),
(41, 10, 'Fixed', NULL, '2012-10-30 15:41:29', NULL),
(42, 10, 'Unable to reproduce', NULL, '2012-10-30 15:41:29', NULL),
(43, 10, 'Not fixable', NULL, '2012-10-30 15:41:29', NULL),
(44, 10, 'Duplicate', NULL, '2012-10-30 15:41:29', NULL),
(45, 10, 'No change required', NULL, '2012-10-30 15:41:29', NULL),
(46, 11, 'Fixed', NULL, '2012-11-22 14:21:54', NULL),
(47, 11, 'Unable to reproduce', NULL, '2012-11-22 14:21:54', NULL),
(48, 11, 'Not fixable', NULL, '2012-11-22 14:21:54', NULL),
(49, 11, 'Duplicate', NULL, '2012-11-22 14:21:54', NULL),
(50, 11, 'No change required', NULL, '2012-11-22 14:21:54', NULL),
(86, 19, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2012-12-05 16:05:42', NULL),
(87, 19, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2012-12-05 16:05:42', NULL),
(88, 19, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2012-12-05 16:05:42', NULL),
(89, 19, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2012-12-05 16:05:42', NULL),
(90, 19, 'No Change Required', 'The problems does not require a change.', '2012-12-05 16:05:42', NULL),
(101, 22, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2012-12-06 09:11:41', NULL),
(102, 22, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2012-12-06 09:11:41', NULL),
(103, 22, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2012-12-06 09:11:41', NULL),
(104, 22, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2012-12-06 09:11:41', NULL),
(105, 22, 'No Change Required', 'The problems does not require a change.', '2012-12-06 09:11:41', NULL),
(186, 40, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2012-12-21 07:07:35', NULL),
(187, 40, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2012-12-21 07:07:35', NULL),
(188, 40, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2012-12-21 07:07:35', NULL),
(189, 40, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2012-12-21 07:07:35', NULL),
(190, 40, 'No Change Required', 'The problems does not require a change.', '2012-12-21 07:07:35', NULL),
(246, 53, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2013-01-16 21:25:53', NULL),
(247, 53, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2013-01-16 21:25:53', NULL),
(248, 53, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2013-01-16 21:25:53', NULL),
(249, 53, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2013-01-16 21:25:53', NULL),
(250, 53, 'No Change Required', 'The problems does not require a change.', '2013-01-16 21:25:53', NULL),
(970, 0, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '0000-00-00 00:00:00', NULL),
(971, 0, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '0000-00-00 00:00:00', NULL),
(972, 0, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '0000-00-00 00:00:00', NULL),
(973, 0, 'Duplicate', 'The problem is a duplicate of an existing issue.', '0000-00-00 00:00:00', NULL),
(974, 0, 'No Change Required', 'The problems does not require a change.', '0000-00-00 00:00:00', NULL),
(9701, 1940, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2014-05-08 19:17:02', NULL),
(9702, 1940, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2014-05-08 19:17:02', NULL),
(9703, 1940, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2014-05-08 19:17:02', NULL),
(9704, 1940, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2014-05-08 19:17:02', NULL),
(9705, 1940, 'No Change Required', 'The problems does not require a change.', '2014-05-08 19:17:02', NULL),
(9711, 1942, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2014-05-08 19:17:02', NULL),
(9712, 1942, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2014-05-08 19:17:02', NULL),
(9713, 1942, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2014-05-08 19:17:02', NULL),
(9714, 1942, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2014-05-08 19:17:02', NULL),
(9715, 1942, 'No Change Required', 'The problems does not require a change.', '2014-05-08 19:17:02', NULL),
(9721, 1944, 'Fixed', 'A fix for this issue is checked into the tree and tested.', '2014-05-08 19:17:02', NULL),
(9722, 1944, 'Cannot Reproduce', 'All attempts at reproducing this issue failed, or not enough information was available to reproduce the issue. Reading the code produces no clues as to why this behavior would occur. If more information appears later, please reopen the issue.', '2014-05-08 19:17:02', NULL),
(9723, 1944, 'Won''t Fix', 'The problem described is an issue which will never be fixed.', '2014-05-08 19:17:02', NULL),
(9724, 1944, 'Duplicate', 'The problem is a duplicate of an existing issue.', '2014-05-08 19:17:02', NULL),
(9725, 1944, 'No Change Required', 'The problems does not require a change.', '2014-05-08 19:17:02', NULL),
(9796, 1959, 'FIXED', '', '2014-06-26 14:00:20', '2014-06-26 12:04:26'),
(9798, 1959, 'WON''T FIX', '', '2014-06-26 14:00:20', '2014-06-26 12:04:49'),
(9799, 1959, 'DUPLICATE', '', '2014-06-26 14:00:20', '2014-06-26 12:05:17'),
(9801, 1959, 'NO RESOLUTION', '', '2014-06-26 12:04:15', NULL),
(9802, 1959, 'INVALID', '', '2014-06-26 12:04:35', NULL),
(9803, 1959, 'LATER', '', '2014-06-26 12:04:56', NULL),
(9804, 1959, 'REMIND', '', '2014-06-26 12:05:04', NULL),
(9805, 1959, 'WORKSFORME', '', '2014-06-26 12:05:26', NULL),
(9806, 1959, 'MOVED', '', '2014-06-26 12:05:35', NULL),
(9807, 1959, 'Waiting Verification', '', '2014-06-26 12:05:45', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_security_scheme`
--

CREATE TABLE IF NOT EXISTS `issue_security_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_security_scheme_level`
--

CREATE TABLE IF NOT EXISTS `issue_security_scheme_level` (
`id` bigint(20) unsigned NOT NULL,
  `issue_security_scheme_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `default_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_security_scheme_level_data`
--

CREATE TABLE IF NOT EXISTS `issue_security_scheme_level_data` (
`id` bigint(20) unsigned NOT NULL,
  `issue_security_scheme_level_id` bigint(20) unsigned NOT NULL,
  `permission_role_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `current_assignee` tinyint(3) unsigned DEFAULT NULL,
  `reporter` tinyint(3) unsigned DEFAULT NULL,
  `project_lead` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_status`
--

CREATE TABLE IF NOT EXISTS `issue_status` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9824 ;

--
-- Dumping data for table `issue_status`
--

INSERT INTO `issue_status` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1, 1, 'Open', NULL, '2012-10-04 16:37:37', NULL),
(2, 1, 'Resolved', NULL, '2012-10-04 16:37:37', NULL),
(3, 1, 'Closed', NULL, '2012-10-04 16:37:37', NULL),
(4, 1, 'In Progress', NULL, '2012-10-04 16:37:37', NULL),
(5, 1, 'Reopened', NULL, '2012-10-04 16:37:37', NULL),
(11, 3, 'Open', NULL, '2012-10-10 15:34:05', NULL),
(12, 3, 'Resolved', NULL, '2012-10-10 15:34:05', NULL),
(13, 3, 'Closed', NULL, '2012-10-10 15:34:05', NULL),
(14, 3, 'In Progress', NULL, '2012-10-10 15:34:05', NULL),
(15, 3, 'Reopened', NULL, '2012-10-10 15:34:05', NULL),
(16, 4, 'Open', NULL, '2012-10-16 00:08:09', NULL),
(17, 4, 'Resolved', NULL, '2012-10-16 00:08:09', NULL),
(18, 4, 'Closed', NULL, '2012-10-16 00:08:09', NULL),
(19, 4, 'In Progress', NULL, '2012-10-16 00:08:09', NULL),
(20, 4, 'Reopened', NULL, '2012-10-16 00:08:09', NULL),
(21, 5, 'Open', NULL, '2012-10-17 14:31:15', NULL),
(22, 5, 'Resolved', NULL, '2012-10-17 14:31:15', NULL),
(23, 5, 'Closed', NULL, '2012-10-17 14:31:15', NULL),
(24, 5, 'In Progress', NULL, '2012-10-17 14:31:15', NULL),
(25, 5, 'Reopened', NULL, '2012-10-17 14:31:15', NULL),
(26, 6, 'Open', NULL, '2012-10-21 11:23:16', NULL),
(27, 6, 'Resolved', NULL, '2012-10-21 11:23:16', NULL),
(28, 6, 'Closed', NULL, '2012-10-21 11:23:16', NULL),
(29, 6, 'In Progress', NULL, '2012-10-21 11:23:16', NULL),
(30, 6, 'Reopened', NULL, '2012-10-21 11:23:16', NULL),
(31, 7, 'Open', NULL, '2012-10-27 15:35:58', NULL),
(32, 7, 'Resolved', NULL, '2012-10-27 15:35:58', NULL),
(33, 7, 'Closed', NULL, '2012-10-27 15:35:58', NULL),
(34, 7, 'In Progress', NULL, '2012-10-27 15:35:58', NULL),
(35, 7, 'Reopened', NULL, '2012-10-27 15:35:58', NULL),
(36, 9, 'Open', NULL, '2012-10-27 15:40:22', NULL),
(37, 9, 'Resolved', NULL, '2012-10-27 15:40:22', NULL),
(38, 9, 'Closed', NULL, '2012-10-27 15:40:22', NULL),
(39, 9, 'In Progress', NULL, '2012-10-27 15:40:22', NULL),
(40, 9, 'Reopened', NULL, '2012-10-27 15:40:22', NULL),
(41, 10, 'Open', NULL, '2012-10-30 15:41:29', NULL),
(42, 10, 'Resolved', NULL, '2012-10-30 15:41:29', NULL),
(43, 10, 'Closed', NULL, '2012-10-30 15:41:29', NULL),
(44, 10, 'In Progress', NULL, '2012-10-30 15:41:29', NULL),
(45, 10, 'Reopened', NULL, '2012-10-30 15:41:29', NULL),
(46, 11, 'Open', NULL, '2012-11-22 14:21:54', NULL),
(47, 11, 'Resolved', NULL, '2012-11-22 14:21:54', NULL),
(48, 11, 'Closed', NULL, '2012-11-22 14:21:54', NULL),
(49, 11, 'In Progress', NULL, '2012-11-22 14:21:54', NULL),
(50, 11, 'Reopened', NULL, '2012-11-22 14:21:54', NULL),
(86, 19, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2012-12-05 16:05:42', NULL),
(87, 19, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2012-12-05 16:05:42', NULL),
(88, 19, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2012-12-05 16:05:42', NULL),
(89, 19, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2012-12-05 16:05:42', NULL),
(90, 19, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2012-12-05 16:05:42', NULL),
(101, 22, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2012-12-06 09:11:41', NULL),
(102, 22, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2012-12-06 09:11:41', NULL),
(103, 22, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2012-12-06 09:11:41', NULL),
(104, 22, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2012-12-06 09:11:41', NULL),
(105, 22, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2012-12-06 09:11:41', NULL),
(187, 40, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2012-12-21 07:07:35', NULL),
(188, 40, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2012-12-21 07:07:35', NULL),
(189, 40, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2012-12-21 07:07:35', NULL),
(190, 40, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2012-12-21 07:07:35', NULL),
(191, 40, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2012-12-21 07:07:35', NULL),
(251, 53, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2013-01-16 21:25:53', NULL),
(252, 53, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2013-01-16 21:25:53', NULL),
(253, 53, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2013-01-16 21:25:53', NULL),
(254, 53, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2013-01-16 21:25:53', NULL),
(255, 53, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2013-01-16 21:25:53', NULL),
(979, 0, 'Open', 'The issue is open and ready for the assignee to start work on it.', '0000-00-00 00:00:00', NULL),
(980, 0, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '0000-00-00 00:00:00', NULL),
(981, 0, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '0000-00-00 00:00:00', NULL),
(982, 0, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '0000-00-00 00:00:00', NULL),
(983, 0, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '0000-00-00 00:00:00', NULL),
(9717, 1940, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2014-05-08 19:17:02', NULL),
(9718, 1940, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2014-05-08 19:17:02', NULL),
(9719, 1940, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2014-05-08 19:17:02', NULL),
(9720, 1940, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2014-05-08 19:17:02', NULL),
(9721, 1940, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2014-05-08 19:17:02', NULL),
(9727, 1942, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2014-05-08 19:17:02', NULL),
(9728, 1942, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2014-05-08 19:17:02', NULL),
(9729, 1942, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2014-05-08 19:17:02', NULL),
(9730, 1942, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2014-05-08 19:17:02', NULL),
(9731, 1942, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2014-05-08 19:17:02', NULL),
(9737, 1944, 'Open', 'The issue is open and ready for the assignee to start work on it.', '2014-05-08 19:17:02', NULL),
(9738, 1944, 'Resolved', 'A resolution has been taken, and it is awaiting verification by reporter. From here issues are either reopened, or are closed.', '2014-05-08 19:17:02', NULL),
(9739, 1944, 'Closed', 'The issue is considered finished, the resolution is correct. Issues which are closed can be reopened.', '2014-05-08 19:17:02', NULL),
(9740, 1944, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2014-05-08 19:17:02', NULL),
(9741, 1944, 'Reopened', 'This issue was once resolved, but the resolution was deemed incorrect. From here issues are either marked assigned or resolved.', '2014-05-08 19:17:02', NULL),
(9816, 1959, 'RESOLVED', '', '2014-06-26 14:00:20', '2014-06-26 12:03:26'),
(9817, 1959, 'CLOSED', '', '2014-06-26 14:00:20', '2014-06-26 12:03:44'),
(9818, 1959, 'In Progress', 'This issue is being actively worked on at the moment by the assignee.', '2014-06-26 14:00:20', NULL),
(9819, 1959, 'REOPENED', '', '2014-06-26 14:00:20', '2014-06-26 12:03:12'),
(9820, 1959, 'UNCONFIRMED', '', '2014-06-26 12:02:38', NULL),
(9821, 1959, 'NEW', '', '2014-06-26 12:02:49', NULL),
(9822, 1959, 'ASSIGNED', '', '2014-06-26 12:02:57', NULL),
(9823, 1959, 'VERIFIED', '', '2014-06-26 12:03:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_type`
--

CREATE TABLE IF NOT EXISTS `issue_type` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `sub_task_flag` int(10) unsigned DEFAULT NULL,
  `icon_name` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15729 ;

--
-- Dumping data for table `issue_type`
--

INSERT INTO `issue_type` (`id`, `client_id`, `name`, `description`, `sub_task_flag`, `icon_name`, `date_created`, `date_updated`) VALUES
(1, 1, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-04 16:37:37', NULL),
(2, 1, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-04 16:37:37', NULL),
(3, 1, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-04 16:37:37', NULL),
(4, 1, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-04 16:37:37', NULL),
(5, 1, 'Story', 'A user story', 0, 'story.png', '2012-10-04 16:37:37', NULL),
(6, 1, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-04 16:37:37', NULL),
(7, 1, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-04 16:37:37', NULL),
(8, 1, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-04 16:37:37', NULL),
(17, 3, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-10 15:34:05', NULL),
(18, 3, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-10 15:34:05', NULL),
(19, 3, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-10 15:34:05', NULL),
(20, 3, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-10 15:34:05', NULL),
(21, 3, 'Story', 'A user story', 0, 'story.png', '2012-10-10 15:34:05', NULL),
(22, 3, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-10 15:34:05', NULL),
(23, 3, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-10 15:34:05', NULL),
(24, 3, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-10 15:34:05', NULL),
(25, 4, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-16 00:08:09', NULL),
(26, 4, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-16 00:08:09', NULL),
(27, 4, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-16 00:08:09', NULL),
(28, 4, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-16 00:08:09', NULL),
(29, 4, 'Story', 'A user story', 0, 'story.png', '2012-10-16 00:08:09', NULL),
(30, 4, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-16 00:08:09', NULL),
(31, 4, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-16 00:08:09', NULL),
(32, 4, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-16 00:08:09', NULL),
(33, 5, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-17 14:31:15', NULL),
(34, 5, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-17 14:31:15', NULL),
(35, 5, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-17 14:31:15', NULL),
(36, 5, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-17 14:31:15', NULL),
(37, 5, 'Story', 'A user story', 0, 'story.png', '2012-10-17 14:31:15', NULL),
(38, 5, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-17 14:31:15', NULL),
(39, 5, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-17 14:31:15', NULL),
(40, 5, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-17 14:31:15', NULL),
(41, 6, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-21 11:23:16', NULL),
(42, 6, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-21 11:23:16', NULL),
(43, 6, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-21 11:23:16', NULL),
(44, 6, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-21 11:23:16', NULL),
(45, 6, 'Story', 'A user story', 0, 'story.png', '2012-10-21 11:23:16', NULL),
(46, 6, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-21 11:23:16', NULL),
(47, 6, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-21 11:23:16', NULL),
(48, 6, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-21 11:23:16', NULL),
(49, 7, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-27 15:35:58', NULL),
(50, 7, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-27 15:35:58', NULL),
(51, 7, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-27 15:35:58', NULL),
(52, 7, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-27 15:35:58', NULL),
(53, 7, 'Story', 'A user story', 0, 'story.png', '2012-10-27 15:35:58', NULL),
(54, 7, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-27 15:35:58', NULL),
(55, 7, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-27 15:35:58', NULL),
(56, 7, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-27 15:35:58', NULL),
(57, 9, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-27 15:40:22', NULL),
(58, 9, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-27 15:40:22', NULL),
(59, 9, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-27 15:40:22', NULL),
(60, 9, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-27 15:40:22', NULL),
(61, 9, 'Story', 'A user story', 0, 'story.png', '2012-10-27 15:40:22', NULL),
(62, 9, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-27 15:40:22', NULL),
(63, 9, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-27 15:40:22', NULL),
(64, 9, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-27 15:40:22', NULL),
(65, 10, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-10-30 15:41:29', NULL),
(66, 10, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-10-30 15:41:29', NULL),
(67, 10, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-10-30 15:41:29', NULL),
(68, 10, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-10-30 15:41:29', NULL),
(69, 10, 'Story', 'A user story', 0, 'story.png', '2012-10-30 15:41:29', NULL),
(70, 10, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-10-30 15:41:29', NULL),
(71, 10, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-10-30 15:41:29', NULL),
(72, 10, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-10-30 15:41:29', NULL),
(73, 11, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-11-22 14:21:54', NULL),
(74, 11, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-11-22 14:21:54', NULL),
(75, 11, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-11-22 14:21:54', NULL),
(76, 11, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-11-22 14:21:54', NULL),
(77, 11, 'Story', 'A user story', 0, 'story.png', '2012-11-22 14:21:54', NULL),
(78, 11, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-11-22 14:21:54', NULL),
(79, 11, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-11-22 14:21:54', NULL),
(80, 11, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-11-22 14:21:54', NULL),
(137, 19, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-12-05 16:05:42', NULL),
(138, 19, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-12-05 16:05:42', NULL),
(139, 19, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-12-05 16:05:42', NULL),
(140, 19, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-12-05 16:05:42', NULL),
(141, 19, 'Story', 'A user story', 0, 'story.png', '2012-12-05 16:05:42', NULL),
(142, 19, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-12-05 16:05:42', NULL),
(143, 19, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-12-05 16:05:42', NULL),
(144, 19, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-12-05 16:05:42', NULL),
(161, 22, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-12-06 09:11:41', NULL),
(162, 22, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-12-06 09:11:41', NULL),
(163, 22, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-12-06 09:11:41', NULL),
(164, 22, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-12-06 09:11:41', NULL),
(165, 22, 'Story', 'A user story', 0, 'story.png', '2012-12-06 09:11:41', NULL),
(166, 22, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-12-06 09:11:41', NULL),
(167, 22, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-12-06 09:11:41', NULL),
(168, 22, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-12-06 09:11:41', NULL),
(297, 40, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2012-12-21 07:07:35', NULL),
(298, 40, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2012-12-21 07:07:35', NULL),
(299, 40, 'Task', 'A task that needs to be done.', 0, 'task.png', '2012-12-21 07:07:35', NULL),
(300, 40, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2012-12-21 07:07:35', NULL),
(301, 40, 'Story', 'A user story', 0, 'story.png', '2012-12-21 07:07:35', NULL),
(302, 40, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2012-12-21 07:07:35', NULL),
(303, 40, 'Technical task', 'A technical task.', 1, 'technical.png', '2012-12-21 07:07:35', NULL),
(304, 40, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2012-12-21 07:07:35', NULL),
(394, 53, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2013-01-16 21:25:53', NULL),
(395, 53, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2013-01-16 21:25:53', NULL),
(396, 53, 'Task', 'A task that needs to be done.', 0, 'task.png', '2013-01-16 21:25:53', NULL),
(397, 53, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2013-01-16 21:25:53', NULL),
(398, 53, 'Story', 'A user story', 0, 'story.png', '2013-01-16 21:25:53', NULL),
(399, 53, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2013-01-16 21:25:53', NULL),
(400, 53, 'Technical task', 'A technical task.', 1, 'technical.png', '2013-01-16 21:25:53', NULL),
(401, 53, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2013-01-16 21:25:53', NULL),
(1586, 0, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '0000-00-00 00:00:00', NULL),
(1587, 0, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '0000-00-00 00:00:00', NULL),
(1588, 0, 'Task', 'A task that needs to be done.', 0, 'task.png', '0000-00-00 00:00:00', NULL),
(1589, 0, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '0000-00-00 00:00:00', NULL),
(1590, 0, 'Story', 'A user story', 0, 'story.png', '0000-00-00 00:00:00', NULL),
(1591, 0, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '0000-00-00 00:00:00', NULL),
(1592, 0, 'Technical task', 'A technical task.', 1, 'technical.png', '0000-00-00 00:00:00', NULL),
(1593, 0, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '0000-00-00 00:00:00', NULL),
(15568, 1940, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2014-05-08 19:17:02', NULL),
(15569, 1940, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2014-05-08 19:17:02', NULL),
(15570, 1940, 'Task', 'A task that needs to be done.', 0, 'task.png', '2014-05-08 19:17:02', NULL),
(15571, 1940, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2014-05-08 19:17:02', NULL),
(15572, 1940, 'Story', 'A user story', 0, 'story.png', '2014-05-08 19:17:02', NULL),
(15573, 1940, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2014-05-08 19:17:02', NULL),
(15574, 1940, 'Technical task', 'A technical task.', 1, 'technical.png', '2014-05-08 19:17:02', NULL),
(15575, 1940, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2014-05-08 19:17:02', NULL),
(15584, 1942, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2014-05-08 19:17:02', NULL),
(15585, 1942, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2014-05-08 19:17:02', NULL),
(15586, 1942, 'Task', 'A task that needs to be done.', 0, 'task.png', '2014-05-08 19:17:02', NULL),
(15587, 1942, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2014-05-08 19:17:02', NULL),
(15588, 1942, 'Story', 'A user story', 0, 'story.png', '2014-05-08 19:17:02', NULL),
(15589, 1942, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2014-05-08 19:17:02', NULL),
(15590, 1942, 'Technical task', 'A technical task.', 1, 'technical.png', '2014-05-08 19:17:02', NULL),
(15591, 1942, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2014-05-08 19:17:02', NULL),
(15600, 1944, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2014-05-08 19:17:02', NULL),
(15601, 1944, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2014-05-08 19:17:02', NULL),
(15602, 1944, 'Task', 'A task that needs to be done.', 0, 'task.png', '2014-05-08 19:17:02', NULL),
(15603, 1944, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2014-05-08 19:17:02', NULL),
(15604, 1944, 'Story', 'A user story', 0, 'story.png', '2014-05-08 19:17:02', NULL),
(15605, 1944, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2014-05-08 19:17:02', NULL),
(15606, 1944, 'Technical task', 'A technical task.', 1, 'technical.png', '2014-05-08 19:17:02', NULL),
(15607, 1944, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2014-05-08 19:17:02', NULL),
(15721, 1959, 'Bug', 'A problem which impairs or prevents the functions of the product.', 0, 'bug.png', '2014-06-26 14:00:20', NULL),
(15722, 1959, 'New feature', 'A new feature of the product, which has yet to be developed.', 0, 'new_feature.png', '2014-06-26 14:00:20', NULL),
(15723, 1959, 'Task', 'A task that needs to be done.', 0, 'task.png', '2014-06-26 14:00:20', NULL),
(15724, 1959, 'Improvement', 'An improvement or enhancement to an existing feature or task.', 0, 'improvement.png', '2014-06-26 14:00:20', NULL),
(15725, 1959, 'Story', 'A user story', 0, 'story.png', '2014-06-26 14:00:20', NULL),
(15726, 1959, 'Epic', 'A big user story that needs to be broken down.', 0, 'epic.png', '2014-06-26 14:00:20', NULL),
(15727, 1959, 'Technical task', 'A technical task.', 1, 'technical.png', '2014-06-26 14:00:20', NULL),
(15728, 1959, 'Sub-task', 'The sub-task of the issue', 1, 'sub_task.png', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_field_configuration`
--

CREATE TABLE IF NOT EXISTS `issue_type_field_configuration` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1959 ;

--
-- Dumping data for table `issue_type_field_configuration`
--

INSERT INTO `issue_type_field_configuration` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1939, 1940, 'Default Field Configuration Scheme', 'Default Field Configuration Scheme', '2014-05-08 19:17:02', NULL),
(1941, 1942, 'Default Field Configuration Scheme', 'Default Field Configuration Scheme', '2014-05-08 19:17:02', NULL),
(1943, 1944, 'Default Field Configuration Scheme', 'Default Field Configuration Scheme', '2014-05-08 19:17:02', NULL),
(1958, 1959, 'Default Field Configuration Scheme', 'Default Field Configuration Scheme', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_field_configuration_data`
--

CREATE TABLE IF NOT EXISTS `issue_type_field_configuration_data` (
`id` bigint(20) unsigned NOT NULL,
  `issue_type_field_configuration_id` bigint(20) unsigned NOT NULL,
  `issue_type_id` bigint(20) unsigned NOT NULL,
  `field_configuration_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15685 ;

--
-- Dumping data for table `issue_type_field_configuration_data`
--

INSERT INTO `issue_type_field_configuration_data` (`id`, `issue_type_field_configuration_id`, `issue_type_id`, `field_configuration_id`, `date_created`) VALUES
(1557, 0, 1586, 0, '0000-00-00 00:00:00'),
(1558, 0, 1587, 0, '0000-00-00 00:00:00'),
(1559, 0, 1588, 0, '0000-00-00 00:00:00'),
(1560, 0, 1589, 0, '0000-00-00 00:00:00'),
(1561, 0, 1590, 0, '0000-00-00 00:00:00'),
(1562, 0, 1591, 0, '0000-00-00 00:00:00'),
(1563, 0, 1592, 0, '0000-00-00 00:00:00'),
(1564, 0, 1593, 0, '0000-00-00 00:00:00'),
(15525, 1939, 15568, 1941, '2014-05-08 19:17:02'),
(15526, 1939, 15569, 1941, '2014-05-08 19:17:02'),
(15527, 1939, 15570, 1941, '2014-05-08 19:17:02'),
(15528, 1939, 15571, 1941, '2014-05-08 19:17:02'),
(15529, 1939, 15572, 1941, '2014-05-08 19:17:02'),
(15530, 1939, 15573, 1941, '2014-05-08 19:17:02'),
(15531, 1939, 15574, 1941, '2014-05-08 19:17:02'),
(15532, 1939, 15575, 1941, '2014-05-08 19:17:02'),
(15541, 1941, 15584, 1943, '2014-05-08 19:17:02'),
(15542, 1941, 15585, 1943, '2014-05-08 19:17:02'),
(15543, 1941, 15586, 1943, '2014-05-08 19:17:02'),
(15544, 1941, 15587, 1943, '2014-05-08 19:17:02'),
(15545, 1941, 15588, 1943, '2014-05-08 19:17:02'),
(15546, 1941, 15589, 1943, '2014-05-08 19:17:02'),
(15547, 1941, 15590, 1943, '2014-05-08 19:17:02'),
(15548, 1941, 15591, 1943, '2014-05-08 19:17:02'),
(15557, 1943, 15600, 1945, '2014-05-08 19:17:02'),
(15558, 1943, 15601, 1945, '2014-05-08 19:17:02'),
(15559, 1943, 15602, 1945, '2014-05-08 19:17:02'),
(15560, 1943, 15603, 1945, '2014-05-08 19:17:02'),
(15561, 1943, 15604, 1945, '2014-05-08 19:17:02'),
(15562, 1943, 15605, 1945, '2014-05-08 19:17:02'),
(15563, 1943, 15606, 1945, '2014-05-08 19:17:02'),
(15564, 1943, 15607, 1945, '2014-05-08 19:17:02'),
(15677, 1958, 15721, 1960, '2014-06-26 14:00:20'),
(15678, 1958, 15722, 1960, '2014-06-26 14:00:20'),
(15679, 1958, 15723, 1960, '2014-06-26 14:00:20'),
(15680, 1958, 15724, 1960, '2014-06-26 14:00:20'),
(15681, 1958, 15725, 1960, '2014-06-26 14:00:20'),
(15682, 1958, 15726, 1960, '2014-06-26 14:00:20'),
(15683, 1958, 15727, 1960, '2014-06-26 14:00:20'),
(15684, 1958, 15728, 1960, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_scheme`
--

CREATE TABLE IF NOT EXISTS `issue_type_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `type` varchar(20) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3950 ;

--
-- Dumping data for table `issue_type_scheme`
--

INSERT INTO `issue_type_scheme` (`id`, `client_id`, `name`, `description`, `type`, `date_created`) VALUES
(3910, 1940, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'project', '2014-05-08 19:17:02'),
(3911, 1940, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'workflow', '2014-05-08 19:17:02'),
(3914, 1942, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'project', '2014-05-08 19:17:02'),
(3915, 1942, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'workflow', '2014-05-08 19:17:02'),
(3918, 1944, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'project', '2014-05-08 19:17:02'),
(3919, 1944, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'workflow', '2014-05-08 19:17:02'),
(3948, 1959, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'project', '2014-06-26 14:00:20'),
(3949, 1959, 'Default Issue Type Scheme', 'Default Issue Type Scheme', 'workflow', '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_scheme_data`
--

CREATE TABLE IF NOT EXISTS `issue_type_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `issue_type_scheme_id` bigint(20) unsigned NOT NULL,
  `issue_type_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=31923 ;

--
-- Dumping data for table `issue_type_scheme_data`
--

INSERT INTO `issue_type_scheme_data` (`id`, `issue_type_scheme_id`, `issue_type_id`, `date_created`) VALUES
(2493, 287, 1146, '2013-08-29 22:26:52'),
(2494, 287, 1147, '2013-08-29 22:26:52'),
(2495, 287, 1148, '2013-08-29 22:26:52'),
(2496, 287, 1149, '2013-08-29 22:26:52'),
(2497, 287, 1150, '2013-08-29 22:26:52'),
(2498, 287, 1151, '2013-08-29 22:26:52'),
(3465, 0, 1586, '0000-00-00 00:00:00'),
(3466, 0, 1587, '0000-00-00 00:00:00'),
(3467, 0, 1588, '0000-00-00 00:00:00'),
(3468, 0, 1589, '0000-00-00 00:00:00'),
(3469, 0, 1590, '0000-00-00 00:00:00'),
(3470, 0, 1591, '0000-00-00 00:00:00'),
(3471, 0, 1592, '0000-00-00 00:00:00'),
(3472, 0, 1593, '0000-00-00 00:00:00'),
(3473, 0, 1586, '0000-00-00 00:00:00'),
(3474, 0, 1587, '0000-00-00 00:00:00'),
(3475, 0, 1588, '0000-00-00 00:00:00'),
(3476, 0, 1589, '0000-00-00 00:00:00'),
(3477, 0, 1590, '0000-00-00 00:00:00'),
(3478, 0, 1591, '0000-00-00 00:00:00'),
(3479, 0, 1592, '0000-00-00 00:00:00'),
(3480, 0, 1593, '0000-00-00 00:00:00'),
(27662, 146, 570, '2013-11-27 15:09:39'),
(27663, 146, 571, '2013-11-27 15:09:39'),
(27664, 146, 572, '2013-11-27 15:09:39'),
(27665, 146, 573, '2013-11-27 15:09:39'),
(27666, 146, 574, '2013-11-27 15:09:39'),
(27667, 146, 575, '2013-11-27 15:09:39'),
(27668, 146, 576, '2013-11-27 15:09:39'),
(27669, 146, 577, '2013-11-27 15:09:39'),
(27670, 146, 1121, '2013-11-27 15:09:39'),
(31603, 3910, 15568, '2014-05-08 19:17:02'),
(31604, 3910, 15569, '2014-05-08 19:17:02'),
(31605, 3910, 15570, '2014-05-08 19:17:02'),
(31606, 3910, 15571, '2014-05-08 19:17:02'),
(31607, 3910, 15572, '2014-05-08 19:17:02'),
(31608, 3910, 15573, '2014-05-08 19:17:02'),
(31609, 3910, 15574, '2014-05-08 19:17:02'),
(31610, 3910, 15575, '2014-05-08 19:17:02'),
(31611, 3911, 15568, '2014-05-08 19:17:02'),
(31612, 3911, 15569, '2014-05-08 19:17:02'),
(31613, 3911, 15570, '2014-05-08 19:17:02'),
(31614, 3911, 15571, '2014-05-08 19:17:02'),
(31615, 3911, 15572, '2014-05-08 19:17:02'),
(31616, 3911, 15573, '2014-05-08 19:17:02'),
(31617, 3911, 15574, '2014-05-08 19:17:02'),
(31618, 3911, 15575, '2014-05-08 19:17:02'),
(31635, 3914, 15584, '2014-05-08 19:17:02'),
(31636, 3914, 15585, '2014-05-08 19:17:02'),
(31637, 3914, 15586, '2014-05-08 19:17:02'),
(31638, 3914, 15587, '2014-05-08 19:17:02'),
(31639, 3914, 15588, '2014-05-08 19:17:02'),
(31640, 3914, 15589, '2014-05-08 19:17:02'),
(31641, 3914, 15590, '2014-05-08 19:17:02'),
(31642, 3914, 15591, '2014-05-08 19:17:02'),
(31643, 3915, 15584, '2014-05-08 19:17:02'),
(31644, 3915, 15585, '2014-05-08 19:17:02'),
(31645, 3915, 15586, '2014-05-08 19:17:02'),
(31646, 3915, 15587, '2014-05-08 19:17:02'),
(31647, 3915, 15588, '2014-05-08 19:17:02'),
(31648, 3915, 15589, '2014-05-08 19:17:02'),
(31649, 3915, 15590, '2014-05-08 19:17:02'),
(31650, 3915, 15591, '2014-05-08 19:17:02'),
(31667, 3918, 15600, '2014-05-08 19:17:02'),
(31668, 3918, 15601, '2014-05-08 19:17:02'),
(31669, 3918, 15602, '2014-05-08 19:17:02'),
(31670, 3918, 15603, '2014-05-08 19:17:02'),
(31671, 3918, 15604, '2014-05-08 19:17:02'),
(31672, 3918, 15605, '2014-05-08 19:17:02'),
(31673, 3918, 15606, '2014-05-08 19:17:02'),
(31674, 3918, 15607, '2014-05-08 19:17:02'),
(31675, 3919, 15600, '2014-05-08 19:17:02'),
(31676, 3919, 15601, '2014-05-08 19:17:02'),
(31677, 3919, 15602, '2014-05-08 19:17:02'),
(31678, 3919, 15603, '2014-05-08 19:17:02'),
(31679, 3919, 15604, '2014-05-08 19:17:02'),
(31680, 3919, 15605, '2014-05-08 19:17:02'),
(31681, 3919, 15606, '2014-05-08 19:17:02'),
(31682, 3919, 15607, '2014-05-08 19:17:02'),
(31907, 3948, 15721, '2014-06-26 14:00:20'),
(31908, 3948, 15722, '2014-06-26 14:00:20'),
(31909, 3948, 15723, '2014-06-26 14:00:20'),
(31910, 3948, 15724, '2014-06-26 14:00:20'),
(31911, 3948, 15725, '2014-06-26 14:00:20'),
(31912, 3948, 15726, '2014-06-26 14:00:20'),
(31913, 3948, 15727, '2014-06-26 14:00:20'),
(31914, 3948, 15728, '2014-06-26 14:00:20'),
(31915, 3949, 15721, '2014-06-26 14:00:20'),
(31916, 3949, 15722, '2014-06-26 14:00:20'),
(31917, 3949, 15723, '2014-06-26 14:00:20'),
(31918, 3949, 15724, '2014-06-26 14:00:20'),
(31919, 3949, 15725, '2014-06-26 14:00:20'),
(31920, 3949, 15726, '2014-06-26 14:00:20'),
(31921, 3949, 15727, '2014-06-26 14:00:20'),
(31922, 3949, 15728, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_screen_scheme`
--

CREATE TABLE IF NOT EXISTS `issue_type_screen_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1960 ;

--
-- Dumping data for table `issue_type_screen_scheme`
--

INSERT INTO `issue_type_screen_scheme` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1940, 1940, 'Default Issue Type Screen Scheme', 'Default Issue Type Screen Scheme', '2014-05-08 19:17:02', NULL),
(1942, 1942, 'Default Issue Type Screen Scheme', 'Default Issue Type Screen Scheme', '2014-05-08 19:17:02', NULL),
(1944, 1944, 'Default Issue Type Screen Scheme', 'Default Issue Type Screen Scheme', '2014-05-08 19:17:02', NULL),
(1959, 1959, 'Default Issue Type Screen Scheme', 'Default Issue Type Screen Scheme', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `issue_type_screen_scheme_data`
--

CREATE TABLE IF NOT EXISTS `issue_type_screen_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `issue_type_screen_scheme_id` bigint(20) unsigned NOT NULL,
  `issue_type_id` bigint(20) unsigned NOT NULL,
  `screen_scheme_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15693 ;

--
-- Dumping data for table `issue_type_screen_scheme_data`
--

INSERT INTO `issue_type_screen_scheme_data` (`id`, `issue_type_screen_scheme_id`, `issue_type_id`, `screen_scheme_id`, `date_created`) VALUES
(1565, 0, 1586, 0, '0000-00-00 00:00:00'),
(1566, 0, 1587, 0, '0000-00-00 00:00:00'),
(1567, 0, 1588, 0, '0000-00-00 00:00:00'),
(1568, 0, 1589, 0, '0000-00-00 00:00:00'),
(1569, 0, 1590, 0, '0000-00-00 00:00:00'),
(1570, 0, 1591, 0, '0000-00-00 00:00:00'),
(1571, 0, 1592, 0, '0000-00-00 00:00:00'),
(1572, 0, 1593, 0, '0000-00-00 00:00:00'),
(15533, 1940, 15568, 1941, '2014-05-08 19:17:02'),
(15534, 1940, 15569, 1941, '2014-05-08 19:17:02'),
(15535, 1940, 15570, 1941, '2014-05-08 19:17:02'),
(15536, 1940, 15571, 1941, '2014-05-08 19:17:02'),
(15537, 1940, 15572, 1941, '2014-05-08 19:17:02'),
(15538, 1940, 15573, 1941, '2014-05-08 19:17:02'),
(15539, 1940, 15574, 1941, '2014-05-08 19:17:02'),
(15540, 1940, 15575, 1941, '2014-05-08 19:17:02'),
(15549, 1942, 15584, 1943, '2014-05-08 19:17:02'),
(15550, 1942, 15585, 1943, '2014-05-08 19:17:02'),
(15551, 1942, 15586, 1943, '2014-05-08 19:17:02'),
(15552, 1942, 15587, 1943, '2014-05-08 19:17:02'),
(15553, 1942, 15588, 1943, '2014-05-08 19:17:02'),
(15554, 1942, 15589, 1943, '2014-05-08 19:17:02'),
(15555, 1942, 15590, 1943, '2014-05-08 19:17:02'),
(15556, 1942, 15591, 1943, '2014-05-08 19:17:02'),
(15565, 1944, 15600, 1945, '2014-05-08 19:17:02'),
(15566, 1944, 15601, 1945, '2014-05-08 19:17:02'),
(15567, 1944, 15602, 1945, '2014-05-08 19:17:02'),
(15568, 1944, 15603, 1945, '2014-05-08 19:17:02'),
(15569, 1944, 15604, 1945, '2014-05-08 19:17:02'),
(15570, 1944, 15605, 1945, '2014-05-08 19:17:02'),
(15571, 1944, 15606, 1945, '2014-05-08 19:17:02'),
(15572, 1944, 15607, 1945, '2014-05-08 19:17:02'),
(15685, 1959, 15721, 1960, '2014-06-26 14:00:20'),
(15686, 1959, 15722, 1960, '2014-06-26 14:00:20'),
(15687, 1959, 15723, 1960, '2014-06-26 14:00:20'),
(15688, 1959, 15724, 1960, '2014-06-26 14:00:20'),
(15689, 1959, 15725, 1960, '2014-06-26 14:00:20'),
(15690, 1959, 15726, 1960, '2014-06-26 14:00:20'),
(15691, 1959, 15727, 1960, '2014-06-26 14:00:20'),
(15692, 1959, 15728, 1960, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `issue_version`
--

CREATE TABLE IF NOT EXISTS `issue_version` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `project_version_id` bigint(20) unsigned NOT NULL,
  `affected_targeted_flag` tinyint(3) unsigned DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2294 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_work_log`
--

CREATE TABLE IF NOT EXISTS `issue_work_log` (
`id` bigint(20) unsigned NOT NULL,
  `issue_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `time_spent` varchar(20) NOT NULL,
  `comment` mediumtext NOT NULL,
  `edited_flag` tinyint(3) unsigned NOT NULL,
  `date_started` datetime NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84 ;

--
-- Dumping data for table `issue_work_log`
--

INSERT INTO `issue_work_log` (`id`, `issue_id`, `user_id`, `time_spent`, `comment`, `edited_flag`, `date_started`, `date_created`) VALUES
(1, 286, 3, '3d', '', 1, '2013-04-20 10:18:00', '2013-04-20 10:19:20'),
(2, 286, 3, '1w', '', 0, '2013-04-20 10:20:00', '2013-04-20 10:20:44');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
`id` int(10) unsigned NOT NULL,
  `email_address` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `newsletter`
--

INSERT INTO `newsletter` (`id`, `email_address`, `date_created`) VALUES
(1, 'domnulnopcea@gmail.com', '2012-11-02 16:27:41'),
(2, 'me@danielmorosan.com', '2012-12-08 01:21:58'),
(3, 'phil@prett.net', '2013-10-22 13:10:03'),
(4, 'rwerw@fsfsd.com', '2014-01-31 15:27:28'),
(5, 'jvigno@gmail.com', '2014-03-15 01:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `notification_scheme`
--

CREATE TABLE IF NOT EXISTS `notification_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1964 ;

--
-- Dumping data for table `notification_scheme`
--

INSERT INTO `notification_scheme` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1944, 1940, 'Default Notification Scheme', 'Default Notification Scheme', '2014-05-08 19:17:02', NULL),
(1946, 1942, 'Default Notification Scheme', 'Default Notification Scheme', '2014-05-08 19:17:02', NULL),
(1948, 1944, 'Default Notification Scheme', 'Default Notification Scheme', '2014-05-08 19:17:02', NULL),
(1963, 1959, 'Default Notification Scheme', 'Default Notification Scheme', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification_scheme_data`
--

CREATE TABLE IF NOT EXISTS `notification_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `notification_scheme_id` bigint(20) unsigned NOT NULL,
  `event_id` bigint(20) unsigned NOT NULL,
  `permission_role_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `current_assignee` tinyint(3) unsigned DEFAULT NULL,
  `reporter` tinyint(3) unsigned DEFAULT NULL,
  `current_user` tinyint(3) unsigned DEFAULT NULL,
  `project_lead` tinyint(3) unsigned DEFAULT NULL,
  `component_lead` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46997 ;

--
-- Dumping data for table `notification_scheme_data`
--

INSERT INTO `notification_scheme_data` (`id`, `notification_scheme_id`, `event_id`, `permission_role_id`, `group_id`, `user_id`, `current_assignee`, `reporter`, `current_user`, `project_lead`, `component_lead`, `date_created`) VALUES
(2, 1, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(10, 1, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(11, 1, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(12, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(13, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(14, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(15, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(16, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(17, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(18, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(19, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(20, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(21, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(22, 1, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(45, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(47, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(48, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(49, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(50, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(51, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(52, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(53, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(54, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(55, 3, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(56, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(57, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(58, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(59, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(60, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(61, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(62, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(63, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(64, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(65, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(66, 3, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(67, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(68, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(69, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(70, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(71, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(72, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(73, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(74, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(75, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(76, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(77, 4, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(78, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(79, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(80, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(81, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(82, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(83, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(84, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(85, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(86, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(87, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(88, 4, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(89, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(90, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(91, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(92, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(93, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(94, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(95, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(96, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(97, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(98, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(99, 5, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(100, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(101, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(102, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(103, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(104, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(105, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(106, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(107, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(108, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(109, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(110, 5, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(111, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(112, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(113, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(114, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(115, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(116, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(117, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(118, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(119, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(120, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(121, 6, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(122, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(123, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(124, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(125, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(126, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(127, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(128, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(129, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(130, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(131, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(132, 6, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(133, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(134, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(135, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(136, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(137, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(138, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(139, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(140, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(141, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(142, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(143, 7, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(144, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(145, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(146, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(147, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(148, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(149, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(150, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(151, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(152, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(153, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(154, 7, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(155, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(156, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(157, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(158, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(159, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(160, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(161, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(162, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(163, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(164, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(165, 8, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(166, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(167, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(168, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(169, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(170, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(171, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(172, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(173, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(174, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(175, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(176, 8, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(177, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(178, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(179, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(180, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(181, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(182, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(183, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(184, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(185, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(186, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(187, 9, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(188, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(189, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(190, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(191, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(192, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(193, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(194, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(195, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(196, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(197, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(198, 9, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(815, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(816, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(817, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(818, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(819, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(820, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(821, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(822, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(823, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(824, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(825, 38, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(826, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(827, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(828, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(829, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(830, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(831, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(832, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(833, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(834, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(835, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(836, 38, 0, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4587, 0, 2319, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4588, 0, 2320, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4589, 0, 2321, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4590, 0, 2322, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4591, 0, 2323, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4592, 0, 2324, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4593, 0, 2325, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4594, 0, 2326, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4595, 0, 2327, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4596, 0, 2328, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4597, 0, 2330, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4598, 0, 2329, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4599, 0, 2319, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4600, 0, 2320, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4601, 0, 2321, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4602, 0, 2322, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4603, 0, 2323, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4604, 0, 2324, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4605, 0, 2325, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4606, 0, 2326, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4607, 0, 2327, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4608, 0, 2328, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4609, 0, 2330, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4610, 0, 2329, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46517, 1944, 23271, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46518, 1944, 23272, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46519, 1944, 23273, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46520, 1944, 23274, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46521, 1944, 23275, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46522, 1944, 23276, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46523, 1944, 23277, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46524, 1944, 23278, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46525, 1944, 23279, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46526, 1944, 23280, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46527, 1944, 23282, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46528, 1944, 23281, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46529, 1944, 23271, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46530, 1944, 23272, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46531, 1944, 23273, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46532, 1944, 23274, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46533, 1944, 23275, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46534, 1944, 23276, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46535, 1944, 23277, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46536, 1944, 23278, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46537, 1944, 23279, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46538, 1944, 23280, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46539, 1944, 23282, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46540, 1944, 23281, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46565, 1946, 23295, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46566, 1946, 23296, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46567, 1946, 23297, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46568, 1946, 23298, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46569, 1946, 23299, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46570, 1946, 23300, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46571, 1946, 23301, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46572, 1946, 23302, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46573, 1946, 23303, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46574, 1946, 23304, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46575, 1946, 23306, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46576, 1946, 23305, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46577, 1946, 23295, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46578, 1946, 23296, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46579, 1946, 23297, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46580, 1946, 23298, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46581, 1946, 23299, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46582, 1946, 23300, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46583, 1946, 23301, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46584, 1946, 23302, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46585, 1946, 23303, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46586, 1946, 23304, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46587, 1946, 23306, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46588, 1946, 23305, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46613, 1948, 23319, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46614, 1948, 23320, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46615, 1948, 23321, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46616, 1948, 23322, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46617, 1948, 23323, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46618, 1948, 23324, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46619, 1948, 23325, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46620, 1948, 23326, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46621, 1948, 23327, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46622, 1948, 23328, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46623, 1948, 23330, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46624, 1948, 23329, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46625, 1948, 23319, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46626, 1948, 23320, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46627, 1948, 23321, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46628, 1948, 23322, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46629, 1948, 23323, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46630, 1948, 23324, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46631, 1948, 23325, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46632, 1948, 23326, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46633, 1948, 23327, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46634, 1948, 23328, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46635, 1948, 23330, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46636, 1948, 23329, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46973, 1963, 23499, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46974, 1963, 23500, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46975, 1963, 23501, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46976, 1963, 23502, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46977, 1963, 23503, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46978, 1963, 23504, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46979, 1963, 23505, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46980, 1963, 23506, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46981, 1963, 23507, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46982, 1963, 23508, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46983, 1963, 23510, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46984, 1963, 23509, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46985, 1963, 23499, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46986, 1963, 23500, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46987, 1963, 23501, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46988, 1963, 23502, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46989, 1963, 23503, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46990, 1963, 23504, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46991, 1963, 23505, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46992, 1963, 23506, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46993, 1963, 23507, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46994, 1963, 23508, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46995, 1963, 23510, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(46996, 1963, 23509, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE IF NOT EXISTS `permission_role` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5890 ;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(584, 0, 'Administrators', 'The Administrator has all the privileges set', '0000-00-00 00:00:00', NULL),
(585, 0, 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '0000-00-00 00:00:00', NULL),
(586, 0, 'Users', 'Users', '0000-00-00 00:00:00', NULL),
(5828, 1940, 'Administrators', 'The Administrator has all the privileges set', '2014-05-08 19:17:02', NULL),
(5829, 1940, 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '2014-05-08 19:17:02', NULL),
(5830, 1940, 'Users', 'Users', '2014-05-08 19:17:02', NULL),
(5834, 1942, 'Administrators', 'The Administrator has all the privileges set', '2014-05-08 19:17:02', NULL),
(5835, 1942, 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '2014-05-08 19:17:02', NULL),
(5836, 1942, 'Users', 'Users', '2014-05-08 19:17:02', NULL),
(5840, 1944, 'Administrators', 'The Administrator has all the privileges set', '2014-05-08 19:17:02', NULL),
(5841, 1944, 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '2014-05-08 19:17:02', NULL),
(5842, 1944, 'Users', 'Users', '2014-05-08 19:17:02', NULL),
(5887, 1959, 'Administrators', 'The Administrator has all the privileges set', '2014-06-26 14:00:20', NULL),
(5888, 1959, 'Developers', 'The Developer has a basic set of privileges, mainly related to issues', '2014-06-26 14:00:20', NULL),
(5889, 1959, 'Users', 'Users', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role_data`
--

CREATE TABLE IF NOT EXISTS `permission_role_data` (
`id` bigint(20) unsigned NOT NULL,
  `permission_role_id` bigint(20) unsigned NOT NULL,
  `default_group_id` bigint(20) unsigned DEFAULT NULL,
  `default_user_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5969 ;

--
-- Dumping data for table `permission_role_data`
--

INSERT INTO `permission_role_data` (`id`, `permission_role_id`, `default_group_id`, `default_user_id`, `date_created`) VALUES
(1, 1, 1, NULL, '2012-10-04 16:37:37'),
(2, 2, 2, NULL, '2012-10-04 16:37:37'),
(3, 3, 3, NULL, '2012-10-04 16:37:37'),
(4, 4, 4, NULL, '2012-10-10 11:01:13'),
(5, 5, 5, NULL, '2012-10-10 11:01:13'),
(6, 6, 6, NULL, '2012-10-10 11:01:13'),
(7, 7, 7, NULL, '2012-10-10 15:34:05'),
(8, 8, 8, NULL, '2012-10-10 15:34:05'),
(9, 9, 9, NULL, '2012-10-10 15:34:05'),
(10, 12, 10, NULL, '2012-10-16 00:08:09'),
(11, 13, 11, NULL, '2012-10-16 00:08:09'),
(12, 14, 12, NULL, '2012-10-16 00:08:09'),
(13, 15, 13, NULL, '2012-10-17 14:31:15'),
(14, 16, 14, NULL, '2012-10-17 14:31:15'),
(15, 17, 15, NULL, '2012-10-17 14:31:15'),
(16, 18, 16, NULL, '2012-10-21 11:23:16'),
(17, 19, 17, NULL, '2012-10-21 11:23:16'),
(18, 20, 18, NULL, '2012-10-21 11:23:16'),
(19, 21, 19, NULL, '2012-10-27 15:35:58'),
(20, 22, 20, NULL, '2012-10-27 15:35:58'),
(21, 23, 21, NULL, '2012-10-27 15:35:58'),
(22, 24, 22, NULL, '2012-10-27 15:40:22'),
(23, 25, 23, NULL, '2012-10-27 15:40:22'),
(24, 26, 24, NULL, '2012-10-27 15:40:22'),
(25, 27, 25, NULL, '2012-10-30 15:41:29'),
(26, 28, 26, NULL, '2012-10-30 15:41:29'),
(27, 29, 27, NULL, '2012-10-30 15:41:29'),
(28, 30, 28, NULL, '2012-11-22 14:21:54'),
(29, 31, 29, NULL, '2012-11-22 14:21:54'),
(30, 32, 30, NULL, '2012-11-22 14:21:54'),
(31, 33, 31, NULL, '2012-12-01 13:40:04'),
(32, 34, 32, NULL, '2012-12-01 13:40:04'),
(33, 35, 33, NULL, '2012-12-01 13:40:04'),
(34, 36, 34, NULL, '2012-11-30 14:24:49'),
(35, 37, 35, NULL, '2012-11-30 14:24:49'),
(36, 38, 36, NULL, '2012-11-30 14:24:49'),
(37, 39, 37, NULL, '2012-11-30 04:12:03'),
(38, 40, 38, NULL, '2012-11-30 04:12:03'),
(39, 41, 39, NULL, '2012-11-30 04:12:03'),
(40, 42, 40, NULL, '2012-12-04 03:44:35'),
(41, 43, 41, NULL, '2012-12-04 03:44:35'),
(42, 44, 42, NULL, '2012-12-04 03:44:35'),
(43, 45, 43, NULL, '2012-12-04 06:16:39'),
(44, 46, 44, NULL, '2012-12-04 06:16:39'),
(45, 47, 45, NULL, '2012-12-04 06:16:39'),
(46, 48, 46, NULL, '2012-12-04 17:12:06'),
(47, 49, 47, NULL, '2012-12-04 17:12:06'),
(48, 50, 48, NULL, '2012-12-04 17:12:06'),
(49, 51, 49, NULL, '2012-12-04 20:34:29'),
(50, 52, 50, NULL, '2012-12-04 20:34:29'),
(51, 53, 51, NULL, '2012-12-04 20:34:29'),
(52, 54, 52, NULL, '2012-12-05 16:05:42'),
(53, 55, 53, NULL, '2012-12-05 16:05:42'),
(54, 56, 54, NULL, '2012-12-05 16:05:42'),
(55, 57, 55, NULL, '2012-12-05 17:55:56'),
(56, 58, 56, NULL, '2012-12-05 17:55:56'),
(57, 59, 57, NULL, '2012-12-05 17:55:56'),
(58, 60, 58, NULL, '2012-12-05 18:31:36'),
(59, 61, 59, NULL, '2012-12-05 18:31:36'),
(60, 62, 60, NULL, '2012-12-05 18:31:36'),
(61, 63, 61, NULL, '2012-12-06 09:11:41'),
(62, 64, 62, NULL, '2012-12-06 09:11:41'),
(63, 65, 63, NULL, '2012-12-06 09:11:41'),
(64, 66, 64, NULL, '2012-12-06 14:16:29'),
(65, 67, 65, NULL, '2012-12-06 14:16:29'),
(66, 68, 66, NULL, '2012-12-06 14:16:29'),
(67, 69, 67, NULL, '2012-12-09 21:32:49'),
(68, 70, 68, NULL, '2012-12-09 21:32:49'),
(69, 71, 69, NULL, '2012-12-09 21:32:49'),
(70, 72, 70, NULL, '2012-12-09 21:38:11'),
(71, 73, 71, NULL, '2012-12-09 21:38:11'),
(72, 74, 72, NULL, '2012-12-09 21:38:11'),
(73, 75, 73, NULL, '2012-12-12 14:42:09'),
(74, 76, 74, NULL, '2012-12-12 14:42:09'),
(75, 77, 75, NULL, '2012-12-12 14:42:09'),
(76, 78, 76, NULL, '2012-12-13 17:57:31'),
(77, 79, 77, NULL, '2012-12-13 17:57:31'),
(78, 80, 78, NULL, '2012-12-13 17:57:31'),
(79, 81, 79, NULL, '2012-12-13 20:27:33'),
(80, 82, 80, NULL, '2012-12-13 20:27:33'),
(81, 83, 81, NULL, '2012-12-13 20:27:33'),
(82, 84, 82, NULL, '2012-12-14 01:26:31'),
(83, 85, 83, NULL, '2012-12-14 01:26:31'),
(84, 86, 84, NULL, '2012-12-14 01:26:31'),
(85, 87, 85, NULL, '2012-12-15 14:53:11'),
(86, 88, 86, NULL, '2012-12-15 14:53:11'),
(87, 89, 87, NULL, '2012-12-15 14:53:11'),
(88, 90, 88, NULL, '2012-12-17 07:12:37'),
(89, 91, 89, NULL, '2012-12-17 07:12:37'),
(90, 92, 90, NULL, '2012-12-17 07:12:37'),
(91, 93, 91, NULL, '2012-12-17 08:12:26'),
(92, 94, 92, NULL, '2012-12-17 08:12:26'),
(93, 95, 93, NULL, '2012-12-17 08:12:26'),
(94, 96, 94, NULL, '2012-12-17 12:19:14'),
(95, 97, 95, NULL, '2012-12-17 12:19:14'),
(96, 98, 96, NULL, '2012-12-17 12:19:14'),
(97, 99, 97, NULL, '2012-12-17 23:38:50'),
(98, 100, 98, NULL, '2012-12-17 23:38:50'),
(99, 101, 99, NULL, '2012-12-17 23:38:50'),
(100, 102, 100, NULL, '2012-12-18 13:55:24'),
(101, 103, 101, NULL, '2012-12-18 13:55:24'),
(102, 104, 102, NULL, '2012-12-18 13:55:24'),
(103, 105, 103, NULL, '2012-12-18 21:34:49'),
(104, 106, 104, NULL, '2012-12-18 21:34:49'),
(105, 107, 105, NULL, '2012-12-18 21:34:49'),
(106, 108, 106, NULL, '2012-12-18 23:21:47'),
(107, 109, 107, NULL, '2012-12-18 23:21:47'),
(108, 110, 108, NULL, '2012-12-18 23:21:47'),
(109, 111, 109, NULL, '2012-12-20 05:23:30'),
(110, 112, 110, NULL, '2012-12-20 05:23:30'),
(111, 113, 111, NULL, '2012-12-20 05:23:30'),
(112, 114, 112, NULL, '2012-12-21 07:07:35'),
(113, 115, 113, NULL, '2012-12-21 07:07:35'),
(114, 116, 114, NULL, '2012-12-21 07:07:35'),
(115, 117, 115, NULL, '2012-12-21 13:17:45'),
(116, 118, 116, NULL, '2012-12-21 13:17:45'),
(117, 119, 117, NULL, '2012-12-21 13:17:45'),
(118, 120, 118, NULL, '2012-12-25 16:46:46'),
(119, 121, 119, NULL, '2012-12-25 16:46:46'),
(120, 122, 120, NULL, '2012-12-25 16:46:46'),
(121, 123, 121, NULL, '2012-12-27 07:57:57'),
(122, 124, 122, NULL, '2012-12-27 07:57:57'),
(123, 125, 123, NULL, '2012-12-27 07:57:57'),
(124, 126, 124, NULL, '2013-01-04 05:41:31'),
(125, 127, 125, NULL, '2013-01-04 05:41:31'),
(126, 128, 126, NULL, '2013-01-04 05:41:31'),
(127, 129, 127, NULL, '2013-01-04 16:13:09'),
(128, 130, 128, NULL, '2013-01-04 16:13:09'),
(129, 131, 129, NULL, '2013-01-04 16:13:09'),
(130, 132, 130, NULL, '2013-01-05 05:54:15'),
(131, 133, 131, NULL, '2013-01-05 05:54:15'),
(132, 134, 132, NULL, '2013-01-05 05:54:15'),
(133, 135, 133, NULL, '2013-01-07 10:27:43'),
(134, 136, 134, NULL, '2013-01-07 10:27:43'),
(135, 137, 135, NULL, '2013-01-07 10:27:43'),
(136, 138, 136, NULL, '2013-01-09 09:11:35'),
(137, 139, 137, NULL, '2013-01-09 09:11:35'),
(138, 140, 138, NULL, '2013-01-09 09:11:35'),
(139, 141, 139, NULL, '2013-01-11 23:36:40'),
(140, 142, 140, NULL, '2013-01-11 23:36:40'),
(141, 143, 141, NULL, '2013-01-11 23:36:40'),
(142, 144, 142, NULL, '2013-01-15 04:44:32'),
(143, 145, 143, NULL, '2013-01-15 04:44:32'),
(144, 146, 144, NULL, '2013-01-15 04:44:32'),
(145, 147, 145, NULL, '2013-01-16 17:58:46'),
(146, 148, 146, NULL, '2013-01-16 17:58:46'),
(147, 149, 147, NULL, '2013-01-16 17:58:46'),
(148, 150, 148, NULL, '2013-01-16 21:25:53'),
(149, 151, 149, NULL, '2013-01-16 21:25:53'),
(150, 152, 150, NULL, '2013-01-16 21:25:53'),
(151, 153, 151, NULL, '2013-01-19 16:58:50'),
(152, 154, 152, NULL, '2013-01-19 16:58:50'),
(153, 155, 153, NULL, '2013-01-19 16:58:50'),
(156, 157, 157, NULL, '2013-01-21 18:12:45'),
(157, 158, 158, NULL, '2013-01-21 18:12:45'),
(158, 159, 159, NULL, '2013-01-21 18:12:45'),
(159, 160, 160, NULL, '2013-01-23 04:46:06'),
(160, 161, 161, NULL, '2013-01-23 04:46:06'),
(161, 162, 162, NULL, '2013-01-23 04:46:06'),
(162, 163, 163, NULL, '2013-01-27 17:02:21'),
(163, 164, 164, NULL, '2013-01-27 17:02:21'),
(164, 165, 165, NULL, '2013-01-27 17:02:21'),
(165, 166, 166, NULL, '2013-01-28 02:25:17'),
(166, 167, 167, NULL, '2013-01-28 02:25:17'),
(167, 168, 168, NULL, '2013-01-28 02:25:17'),
(169, 170, 170, NULL, '2013-01-29 12:00:47'),
(178, 169, 169, NULL, '2013-01-29 13:37:16'),
(191, 171, 171, NULL, '2013-01-29 13:38:05'),
(192, 172, 172, NULL, '2013-01-30 18:50:34'),
(193, 173, 173, NULL, '2013-01-30 18:50:34'),
(194, 174, 174, NULL, '2013-01-30 18:50:34'),
(195, 175, 175, NULL, '2013-02-02 00:31:27'),
(196, 176, 176, NULL, '2013-02-02 00:31:27'),
(197, 177, 177, NULL, '2013-02-02 00:31:27'),
(198, 178, 178, NULL, '2013-02-02 22:11:55'),
(199, 179, 179, NULL, '2013-02-02 22:11:55'),
(200, 180, 180, NULL, '2013-02-02 22:11:55'),
(201, 181, 181, NULL, '2013-02-05 13:25:49'),
(202, 182, 182, NULL, '2013-02-05 13:25:49'),
(203, 183, 183, NULL, '2013-02-05 13:25:49'),
(204, 184, 184, NULL, '2013-02-05 21:34:20'),
(205, 185, 185, NULL, '2013-02-05 21:34:20'),
(206, 186, 186, NULL, '2013-02-05 21:34:20'),
(207, 187, 187, NULL, '2013-02-08 19:03:08'),
(208, 188, 188, NULL, '2013-02-08 19:03:08'),
(209, 189, 189, NULL, '2013-02-08 19:03:08'),
(210, 190, 190, NULL, '2013-02-09 21:43:45'),
(211, 191, 191, NULL, '2013-02-09 21:43:45'),
(212, 192, 192, NULL, '2013-02-09 21:43:45'),
(213, 193, 193, NULL, '2013-02-12 10:21:14'),
(214, 194, 194, NULL, '2013-02-12 10:21:14'),
(215, 195, 195, NULL, '2013-02-12 10:21:14'),
(216, 196, 196, NULL, '2013-02-13 19:27:34'),
(217, 197, 197, NULL, '2013-02-13 19:27:34'),
(218, 198, 198, NULL, '2013-02-13 19:27:34'),
(219, 199, 199, NULL, '2013-02-14 11:32:31'),
(220, 200, 200, NULL, '2013-02-14 11:32:31'),
(221, 201, 201, NULL, '2013-02-14 11:32:31'),
(222, 202, 202, NULL, '2013-02-15 17:49:15'),
(223, 203, 203, NULL, '2013-02-15 17:49:15'),
(224, 204, 204, NULL, '2013-02-15 17:49:15'),
(225, 205, 205, NULL, '2013-02-18 11:56:20'),
(226, 206, 206, NULL, '2013-02-18 11:56:20'),
(227, 207, 207, NULL, '2013-02-18 11:56:20'),
(228, 208, 208, NULL, '2013-02-20 14:05:43'),
(229, 209, 209, NULL, '2013-02-20 14:05:43'),
(230, 210, 210, NULL, '2013-02-20 14:05:43'),
(231, 211, 211, NULL, '2013-02-20 18:33:08'),
(232, 212, 212, NULL, '2013-02-20 18:33:08'),
(233, 213, 213, NULL, '2013-02-20 18:33:08'),
(234, 214, 215, NULL, '2013-02-26 13:14:18'),
(235, 215, 216, NULL, '2013-02-26 13:14:18'),
(236, 216, 217, NULL, '2013-02-26 13:14:18'),
(237, 217, 218, NULL, '2013-03-07 10:40:35'),
(238, 218, 219, NULL, '2013-03-07 10:40:35'),
(239, 219, 220, NULL, '2013-03-07 10:40:35'),
(240, 220, 221, NULL, '2013-03-08 18:19:25'),
(241, 221, 222, NULL, '2013-03-08 18:19:25'),
(242, 222, 223, NULL, '2013-03-08 18:19:25'),
(243, 223, 224, NULL, '2013-03-15 10:39:43'),
(244, 224, 225, NULL, '2013-03-15 10:39:43'),
(245, 225, 226, NULL, '2013-03-15 10:39:43'),
(246, 226, 227, NULL, '2013-03-18 23:01:35'),
(247, 227, 228, NULL, '2013-03-18 23:01:35'),
(248, 228, 229, NULL, '2013-03-18 23:01:35'),
(249, 229, 231, NULL, '2013-03-21 09:46:08'),
(250, 230, 232, NULL, '2013-03-21 09:46:08'),
(251, 231, 233, NULL, '2013-03-21 09:46:08'),
(252, 232, 234, NULL, '2013-03-24 16:19:07'),
(253, 233, 235, NULL, '2013-03-24 16:19:07'),
(254, 234, 236, NULL, '2013-03-24 16:19:07'),
(255, 235, 237, NULL, '2013-03-28 10:09:38'),
(256, 236, 238, NULL, '2013-03-28 10:09:38'),
(257, 237, 239, NULL, '2013-03-28 10:09:38'),
(258, 238, 240, NULL, '2013-03-27 12:12:14'),
(259, 239, 241, NULL, '2013-03-27 12:12:14'),
(260, 240, 242, NULL, '2013-03-27 12:12:14'),
(261, 241, 243, NULL, '2013-04-12 10:26:13'),
(262, 242, 244, NULL, '2013-04-12 10:26:13'),
(263, 243, 245, NULL, '2013-04-12 10:26:13'),
(264, 244, 246, NULL, '2013-04-12 10:37:17'),
(265, 245, 247, NULL, '2013-04-12 10:37:17'),
(266, 246, 248, NULL, '2013-04-12 10:37:17'),
(267, 247, 249, NULL, '2013-04-12 10:41:57'),
(268, 248, 250, NULL, '2013-04-12 10:41:57'),
(269, 249, 251, NULL, '2013-04-12 10:41:57'),
(270, 250, 252, NULL, '2013-04-12 11:04:03'),
(271, 251, 253, NULL, '2013-04-12 11:04:03'),
(272, 252, 254, NULL, '2013-04-12 11:04:03'),
(273, 253, 255, NULL, '2013-04-23 11:43:51'),
(274, 254, 256, NULL, '2013-04-23 11:43:51'),
(275, 255, 257, NULL, '2013-04-23 11:43:51'),
(276, 256, 258, NULL, '2013-04-29 01:02:06'),
(277, 257, 259, NULL, '2013-04-29 01:02:06'),
(278, 258, 260, NULL, '2013-04-29 01:02:06'),
(279, 259, 261, NULL, '2013-05-01 06:25:22'),
(280, 260, 262, NULL, '2013-05-01 06:25:22'),
(281, 261, 263, NULL, '2013-05-01 06:25:22'),
(282, 262, 264, NULL, '2013-05-03 13:08:11'),
(283, 263, 265, NULL, '2013-05-03 13:08:11'),
(284, 264, 266, NULL, '2013-05-03 13:08:11'),
(285, 265, 267, NULL, '2013-05-03 17:02:08'),
(286, 266, 268, NULL, '2013-05-03 17:02:08'),
(287, 267, 269, NULL, '2013-05-03 17:02:08'),
(288, 268, 270, NULL, '2013-05-05 20:40:01'),
(289, 269, 271, NULL, '2013-05-05 20:40:01'),
(290, 270, 272, NULL, '2013-05-05 20:40:01'),
(291, 271, 273, NULL, '2013-05-06 21:00:37'),
(292, 272, 274, NULL, '2013-05-06 21:00:37'),
(293, 273, 275, NULL, '2013-05-06 21:00:37'),
(294, 274, 277, NULL, '2013-05-08 10:05:24'),
(295, 275, 278, NULL, '2013-05-08 10:05:24'),
(296, 276, 279, NULL, '2013-05-08 10:05:24'),
(297, 277, 280, NULL, '2013-05-08 16:56:23'),
(298, 278, 281, NULL, '2013-05-08 16:56:23'),
(299, 279, 282, NULL, '2013-05-08 16:56:23'),
(301, 281, 284, NULL, '2013-05-09 08:54:23'),
(302, 282, 285, NULL, '2013-05-09 08:54:23'),
(303, 283, 286, NULL, '2013-05-11 04:48:05'),
(304, 284, 287, NULL, '2013-05-11 04:48:05'),
(305, 285, 288, NULL, '2013-05-11 04:48:05'),
(306, 286, 289, NULL, '2013-05-15 14:43:05'),
(307, 287, 290, NULL, '2013-05-15 14:43:05'),
(308, 288, 291, NULL, '2013-05-15 14:43:05'),
(309, 289, 292, NULL, '2013-05-18 09:57:56'),
(310, 290, 293, NULL, '2013-05-18 09:57:56'),
(311, 291, 294, NULL, '2013-05-18 09:57:56'),
(312, 292, 295, NULL, '2013-05-18 16:54:42'),
(313, 293, 296, NULL, '2013-05-18 16:54:42'),
(314, 294, 297, NULL, '2013-05-18 16:54:42'),
(315, 296, 299, NULL, '2013-05-19 20:51:56'),
(316, 297, 300, NULL, '2013-05-19 20:51:56'),
(317, 298, 301, NULL, '2013-05-19 20:51:56'),
(318, 299, 302, NULL, '2013-05-20 07:59:43'),
(319, 300, 303, NULL, '2013-05-20 07:59:43'),
(320, 301, 304, NULL, '2013-05-20 07:59:43'),
(321, 302, 305, NULL, '2013-05-20 20:34:59'),
(322, 303, 306, NULL, '2013-05-20 20:34:59'),
(323, 304, 307, NULL, '2013-05-20 20:34:59'),
(324, 305, 308, NULL, '2013-05-22 10:56:37'),
(325, 306, 309, NULL, '2013-05-22 10:56:37'),
(326, 307, 310, NULL, '2013-05-22 10:56:37'),
(327, 308, 311, NULL, '2013-05-22 21:59:56'),
(328, 309, 312, NULL, '2013-05-22 21:59:56'),
(329, 310, 313, NULL, '2013-05-22 21:59:56'),
(362, 312, 315, NULL, '2013-06-12 02:49:44'),
(363, 312, 318, NULL, '2013-06-12 02:49:44'),
(364, 313, 316, NULL, '2013-06-12 02:49:52'),
(365, 313, 319, NULL, '2013-06-12 02:49:52'),
(377, 313, NULL, 176, '2013-06-19 20:45:33'),
(378, 314, 322, NULL, '2013-06-20 06:41:05'),
(379, 315, 323, NULL, '2013-06-20 06:41:05'),
(380, 316, 324, NULL, '2013-06-20 06:41:05'),
(381, 317, 325, NULL, '2013-06-24 12:43:50'),
(382, 318, 326, NULL, '2013-06-24 12:43:50'),
(383, 319, 327, NULL, '2013-06-24 12:43:50'),
(384, 320, 328, NULL, '2013-06-26 02:40:03'),
(385, 321, 329, NULL, '2013-06-26 02:40:03'),
(386, 322, 330, NULL, '2013-06-26 02:40:03'),
(387, 323, 331, NULL, '2013-06-26 02:45:11'),
(388, 324, 332, NULL, '2013-06-26 02:45:11'),
(389, 325, 333, NULL, '2013-06-26 02:45:11'),
(390, 326, 334, NULL, '2013-06-29 11:35:31'),
(391, 327, 335, NULL, '2013-06-29 11:35:31'),
(392, 328, 336, NULL, '2013-06-29 11:35:31'),
(393, 329, 337, NULL, '2013-07-01 02:17:01'),
(394, 330, 338, NULL, '2013-07-01 02:17:01'),
(395, 331, 339, NULL, '2013-07-01 02:17:01'),
(396, 332, 340, NULL, '2013-07-02 18:07:27'),
(397, 333, 341, NULL, '2013-07-02 18:07:27'),
(398, 334, 342, NULL, '2013-07-02 18:07:27'),
(399, 335, 343, NULL, '2013-07-12 14:49:06'),
(400, 336, 344, NULL, '2013-07-12 14:49:06'),
(401, 337, 345, NULL, '2013-07-12 14:49:06'),
(402, 338, 346, NULL, '2013-07-16 16:46:07'),
(403, 339, 347, NULL, '2013-07-16 16:46:07'),
(404, 340, 348, NULL, '2013-07-16 16:46:07'),
(405, 341, 349, NULL, '2013-07-22 09:22:56'),
(406, 342, 350, NULL, '2013-07-22 09:22:56'),
(407, 343, 351, NULL, '2013-07-22 09:22:56'),
(408, 344, 352, NULL, '2013-07-22 10:01:10'),
(409, 345, 353, NULL, '2013-07-22 10:01:10'),
(410, 346, 354, NULL, '2013-07-22 10:01:10'),
(411, 347, 355, NULL, '2013-07-23 18:12:32'),
(412, 348, 356, NULL, '2013-07-23 18:12:32'),
(413, 349, 357, NULL, '2013-07-23 18:12:32'),
(414, 350, 358, NULL, '2013-07-23 20:04:03'),
(415, 351, 359, NULL, '2013-07-23 20:04:03'),
(416, 352, 360, NULL, '2013-07-23 20:04:03'),
(417, 353, 361, NULL, '2013-07-25 14:44:25'),
(418, 354, 362, NULL, '2013-07-25 14:44:25'),
(419, 355, 363, NULL, '2013-07-25 14:44:25'),
(420, 356, 364, NULL, '2013-07-31 09:40:39'),
(421, 357, 365, NULL, '2013-07-31 09:40:39'),
(422, 358, 366, NULL, '2013-07-31 09:40:39'),
(423, 359, 367, NULL, '2013-08-03 07:17:58'),
(424, 360, 368, NULL, '2013-08-03 07:17:58'),
(425, 361, 369, NULL, '2013-08-03 07:17:58'),
(426, 362, 370, NULL, '2013-08-07 23:44:27'),
(427, 363, 371, NULL, '2013-08-07 23:44:27'),
(428, 364, 372, NULL, '2013-08-07 23:44:27'),
(429, 365, 373, NULL, '2013-08-09 07:24:54'),
(430, 366, 374, NULL, '2013-08-09 07:24:54'),
(431, 367, 375, NULL, '2013-08-09 07:24:54'),
(432, 368, 376, NULL, '2013-08-10 02:54:04'),
(433, 369, 377, NULL, '2013-08-10 02:54:04'),
(434, 370, 378, NULL, '2013-08-10 02:54:04'),
(435, 371, 379, NULL, '2013-08-10 13:21:51'),
(436, 372, 380, NULL, '2013-08-10 13:21:51'),
(437, 373, 381, NULL, '2013-08-10 13:21:51'),
(438, 374, 382, NULL, '2013-08-12 15:05:44'),
(439, 375, 383, NULL, '2013-08-12 15:05:44'),
(440, 376, 384, NULL, '2013-08-12 15:05:44'),
(441, 377, 385, NULL, '2013-08-12 22:17:43'),
(442, 378, 386, NULL, '2013-08-12 22:17:43'),
(443, 379, 387, NULL, '2013-08-12 22:17:43'),
(444, 380, 388, NULL, '2013-08-14 02:26:24'),
(445, 381, 389, NULL, '2013-08-14 02:26:24'),
(446, 382, 390, NULL, '2013-08-14 02:26:24'),
(447, 383, 391, NULL, '2013-08-14 11:12:25'),
(448, 384, 392, NULL, '2013-08-14 11:12:25'),
(449, 385, 393, NULL, '2013-08-14 11:12:25'),
(450, 386, 394, NULL, '2013-08-16 04:37:38'),
(451, 387, 395, NULL, '2013-08-16 04:37:38'),
(452, 388, 396, NULL, '2013-08-16 04:37:38'),
(453, 389, 397, NULL, '2013-08-16 13:22:59'),
(454, 390, 398, NULL, '2013-08-16 13:22:59'),
(455, 391, 399, NULL, '2013-08-16 13:22:59'),
(456, 392, 400, NULL, '2013-08-16 20:34:01'),
(457, 393, 401, NULL, '2013-08-16 20:34:01'),
(458, 394, 402, NULL, '2013-08-16 20:34:01'),
(459, 395, 403, NULL, '2013-08-17 23:01:08'),
(460, 396, 404, NULL, '2013-08-17 23:01:08'),
(461, 397, 405, NULL, '2013-08-17 23:01:08'),
(462, 398, 406, NULL, '2013-08-18 14:15:02'),
(463, 399, 407, NULL, '2013-08-18 14:15:02'),
(464, 400, 408, NULL, '2013-08-18 14:15:02'),
(465, 401, 409, NULL, '2013-08-22 14:06:34'),
(466, 402, 410, NULL, '2013-08-22 14:06:34'),
(467, 403, 411, NULL, '2013-08-22 14:06:34'),
(468, 404, 412, NULL, '2013-08-24 08:00:11'),
(469, 405, 413, NULL, '2013-08-24 08:00:11'),
(470, 406, 414, NULL, '2013-08-24 08:00:11'),
(471, 407, 417, NULL, '2013-08-24 10:54:23'),
(472, 408, 418, NULL, '2013-08-24 10:54:23'),
(473, 409, 419, NULL, '2013-08-24 10:54:23'),
(474, 410, 422, NULL, '2013-08-24 10:58:20'),
(475, 411, 423, NULL, '2013-08-24 10:58:20'),
(476, 412, 424, NULL, '2013-08-24 10:58:20'),
(477, 413, 427, NULL, '2013-08-28 11:39:09'),
(478, 414, 428, NULL, '2013-08-28 11:39:09'),
(479, 415, 429, NULL, '2013-08-28 11:39:09'),
(480, 416, 432, NULL, '2013-08-28 15:28:02'),
(481, 417, 433, NULL, '2013-08-28 15:28:02'),
(482, 418, 434, NULL, '2013-08-28 15:28:02'),
(483, 419, 437, NULL, '2013-08-29 20:06:52'),
(484, 420, 438, NULL, '2013-08-29 20:06:52'),
(485, 421, 439, NULL, '2013-08-29 20:06:52'),
(486, 422, 442, NULL, '2013-08-30 22:26:11'),
(487, 423, 443, NULL, '2013-08-30 22:26:11'),
(488, 424, 444, NULL, '2013-08-30 22:26:11'),
(489, 425, 447, NULL, '2013-08-31 11:22:47'),
(490, 426, 448, NULL, '2013-08-31 11:22:47'),
(491, 427, 449, NULL, '2013-08-31 11:22:47'),
(494, 428, 452, NULL, '2013-08-31 16:51:52'),
(495, 429, 453, NULL, '2013-08-31 16:51:52'),
(496, 430, 454, NULL, '2013-08-31 16:51:52'),
(500, 431, 457, NULL, '2013-09-02 19:29:31'),
(501, 432, 458, NULL, '2013-09-02 19:29:31'),
(502, 433, 459, NULL, '2013-09-02 19:29:31'),
(503, 434, 462, NULL, '2013-09-03 01:27:39'),
(504, 435, 463, NULL, '2013-09-03 01:27:39'),
(505, 436, 464, NULL, '2013-09-03 01:27:39'),
(506, 437, 467, NULL, '2013-09-03 02:46:50'),
(507, 438, 468, NULL, '2013-09-03 02:46:50'),
(508, 439, 469, NULL, '2013-09-03 02:46:50'),
(509, 440, 472, NULL, '2013-09-05 09:53:05'),
(510, 441, 473, NULL, '2013-09-05 09:53:05'),
(511, 442, 474, NULL, '2013-09-05 09:53:05'),
(512, 443, 477, NULL, '2013-09-08 15:19:04'),
(513, 444, 478, NULL, '2013-09-08 15:19:04'),
(514, 445, 479, NULL, '2013-09-08 15:19:04'),
(515, 446, 482, NULL, '2013-09-09 17:55:54'),
(516, 447, 483, NULL, '2013-09-09 17:55:54'),
(517, 448, 484, NULL, '2013-09-09 17:55:54'),
(518, 449, 487, NULL, '2013-09-09 21:54:23'),
(519, 450, 488, NULL, '2013-09-09 21:54:23'),
(520, 451, 489, NULL, '2013-09-09 21:54:23'),
(521, 452, 492, NULL, '2013-09-10 14:16:29'),
(522, 453, 493, NULL, '2013-09-10 14:16:29'),
(523, 454, 494, NULL, '2013-09-10 14:16:29'),
(524, 455, 497, NULL, '2013-09-12 14:58:23'),
(525, 456, 498, NULL, '2013-09-12 14:58:23'),
(526, 457, 499, NULL, '2013-09-12 14:58:23'),
(527, 458, 502, NULL, '2013-09-12 15:14:32'),
(528, 459, 503, NULL, '2013-09-12 15:14:32'),
(529, 460, 504, NULL, '2013-09-12 15:14:32'),
(530, 461, 507, NULL, '2013-09-13 15:50:37'),
(531, 462, 508, NULL, '2013-09-13 15:50:37'),
(532, 463, 509, NULL, '2013-09-13 15:50:37'),
(533, 464, 512, NULL, '2013-09-15 19:42:40'),
(534, 465, 513, NULL, '2013-09-15 19:42:40'),
(535, 466, 514, NULL, '2013-09-15 19:42:40'),
(536, 467, 517, NULL, '2013-09-17 16:20:50'),
(537, 468, 518, NULL, '2013-09-17 16:20:50'),
(538, 469, 519, NULL, '2013-09-17 16:20:50'),
(539, 470, 522, NULL, '2013-09-17 21:13:51'),
(540, 471, 523, NULL, '2013-09-17 21:13:51'),
(541, 472, 524, NULL, '2013-09-17 21:13:51'),
(542, 473, 527, NULL, '2013-09-20 09:49:35'),
(543, 474, 528, NULL, '2013-09-20 09:49:35'),
(544, 475, 529, NULL, '2013-09-20 09:49:35'),
(545, 476, 532, NULL, '2013-09-18 15:53:00'),
(546, 477, 533, NULL, '2013-09-18 15:53:00'),
(547, 478, 534, NULL, '2013-09-18 15:53:00'),
(548, 479, 537, NULL, '2013-09-22 00:06:52'),
(549, 480, 538, NULL, '2013-09-22 00:06:52'),
(550, 481, 539, NULL, '2013-09-22 00:06:52'),
(554, 485, 545, NULL, '2013-09-25 10:45:27'),
(555, 486, 546, NULL, '2013-09-25 10:45:27'),
(556, 487, 547, NULL, '2013-09-25 10:45:27'),
(558, 416, NULL, 256, '2013-09-25 12:41:54'),
(560, 417, NULL, 256, '2013-09-25 12:42:01'),
(562, 418, NULL, 256, '2013-09-25 12:42:10'),
(563, 488, 550, NULL, '2013-09-25 15:21:17'),
(564, 489, 551, NULL, '2013-09-25 15:21:17'),
(565, 490, 552, NULL, '2013-09-25 15:21:17'),
(566, 491, 555, NULL, '2013-09-27 12:54:09'),
(567, 492, 556, NULL, '2013-09-27 12:54:09'),
(568, 493, 557, NULL, '2013-09-27 12:54:09'),
(569, 494, 560, NULL, '2013-09-29 06:40:54'),
(570, 495, 561, NULL, '2013-09-29 06:40:54'),
(571, 496, 562, NULL, '2013-09-29 06:40:54'),
(577, 500, 570, NULL, '2013-10-01 01:33:41'),
(578, 501, 571, NULL, '2013-10-01 01:33:41'),
(579, 502, 572, NULL, '2013-10-01 01:33:41'),
(580, 503, 575, NULL, '2013-10-01 21:21:11'),
(581, 504, 576, NULL, '2013-10-01 21:21:11'),
(582, 505, 577, NULL, '2013-10-01 21:21:11'),
(583, 506, 580, NULL, '2013-10-06 18:41:10'),
(584, 507, 581, NULL, '2013-10-06 18:41:10'),
(585, 508, 582, NULL, '2013-10-06 18:41:10'),
(586, 511, 585, NULL, '2013-10-07 00:44:26'),
(587, 512, 586, NULL, '2013-10-07 00:44:26'),
(588, 513, 587, NULL, '2013-10-07 00:44:26'),
(589, 514, 591, NULL, '2013-10-08 11:21:37'),
(590, 515, 592, NULL, '2013-10-08 11:21:37'),
(591, 516, 593, NULL, '2013-10-08 11:21:37'),
(592, 517, 596, NULL, '2013-10-08 12:32:40'),
(593, 518, 597, NULL, '2013-10-08 12:32:40'),
(594, 519, 598, NULL, '2013-10-08 12:32:40'),
(595, 520, 601, NULL, '2013-10-09 07:58:17'),
(596, 521, 602, NULL, '2013-10-09 07:58:17'),
(597, 522, 603, NULL, '2013-10-09 07:58:17'),
(598, 523, 608, NULL, '2013-10-10 04:22:54'),
(599, 524, 609, NULL, '2013-10-10 04:22:54'),
(600, 525, 610, NULL, '2013-10-10 04:22:54'),
(601, 526, 613, NULL, '2013-10-10 13:25:40'),
(602, 527, 614, NULL, '2013-10-10 13:25:40'),
(603, 528, 615, NULL, '2013-10-10 13:25:40'),
(604, 530, 618, NULL, '2013-10-14 14:43:17'),
(605, 531, 619, NULL, '2013-10-14 14:43:17'),
(606, 532, 620, NULL, '2013-10-14 14:43:17'),
(607, 533, 623, NULL, '2013-10-14 15:17:33'),
(608, 534, 624, NULL, '2013-10-14 15:17:33'),
(609, 535, 625, NULL, '2013-10-14 15:17:33'),
(610, 536, 628, NULL, '2013-10-14 20:45:49'),
(611, 537, 629, NULL, '2013-10-14 20:45:49'),
(612, 538, 630, NULL, '2013-10-14 20:45:49'),
(613, 539, 633, NULL, '2013-10-14 21:20:25'),
(614, 540, 634, NULL, '2013-10-14 21:20:25'),
(615, 541, 635, NULL, '2013-10-14 21:20:25'),
(616, 542, 638, NULL, '2013-10-15 14:39:36'),
(617, 543, 639, NULL, '2013-10-15 14:39:36'),
(618, 544, 640, NULL, '2013-10-15 14:39:36'),
(619, 545, 644, NULL, '2013-10-16 10:40:05'),
(620, 546, 645, NULL, '2013-10-16 10:40:05'),
(621, 547, 646, NULL, '2013-10-16 10:40:05'),
(622, 548, 649, NULL, '2013-10-16 09:52:58'),
(623, 549, 650, NULL, '2013-10-16 09:52:58'),
(624, 550, 651, NULL, '2013-10-16 09:52:58'),
(625, 551, 654, NULL, '2013-10-16 13:11:33'),
(626, 552, 655, NULL, '2013-10-16 13:11:33'),
(627, 553, 656, NULL, '2013-10-16 13:11:33'),
(628, 554, 659, NULL, '2013-10-16 18:10:30'),
(629, 555, 660, NULL, '2013-10-16 18:10:30'),
(630, 556, 661, NULL, '2013-10-16 18:10:30'),
(631, 557, 664, NULL, '2013-10-17 13:29:46'),
(632, 558, 665, NULL, '2013-10-17 13:29:46'),
(633, 559, 666, NULL, '2013-10-17 13:29:46'),
(634, 560, 669, NULL, '2013-10-17 14:34:33'),
(635, 561, 670, NULL, '2013-10-17 14:34:33'),
(636, 562, 671, NULL, '2013-10-17 14:34:33'),
(637, 563, 674, NULL, '2013-10-17 16:51:51'),
(638, 564, 675, NULL, '2013-10-17 16:51:51'),
(640, 566, 681, NULL, '2013-10-18 07:45:43'),
(641, 567, 682, NULL, '2013-10-18 07:45:43'),
(642, 568, 683, NULL, '2013-10-18 07:45:43'),
(643, 569, 686, NULL, '2013-10-18 07:59:01'),
(644, 570, 687, NULL, '2013-10-18 07:59:01'),
(645, 571, 688, NULL, '2013-10-18 07:59:01'),
(646, 572, 691, NULL, '2013-10-18 08:18:02'),
(647, 573, 692, NULL, '2013-10-18 08:18:02'),
(648, 574, 693, NULL, '2013-10-18 08:18:02'),
(649, 575, 696, NULL, '2013-10-18 08:25:01'),
(650, 576, 697, NULL, '2013-10-18 08:25:01'),
(651, 577, 698, NULL, '2013-10-18 08:25:01'),
(652, 578, 701, NULL, '2013-10-18 08:32:01'),
(653, 579, 702, NULL, '2013-10-18 08:32:01'),
(654, 580, 703, NULL, '2013-10-18 08:32:01'),
(655, 581, 706, NULL, '2013-10-18 08:33:02'),
(656, 582, 707, NULL, '2013-10-18 08:33:02'),
(657, 583, 708, NULL, '2013-10-18 08:33:02'),
(658, 584, 711, NULL, '0000-00-00 00:00:00'),
(659, 585, 712, NULL, '0000-00-00 00:00:00'),
(660, 586, 713, NULL, '0000-00-00 00:00:00'),
(661, 587, 716, NULL, '2013-10-18 09:41:01'),
(662, 588, 717, NULL, '2013-10-18 09:41:01'),
(663, 589, 718, NULL, '2013-10-18 09:41:01'),
(664, 590, 721, NULL, '2013-10-18 20:52:01'),
(665, 591, 722, NULL, '2013-10-18 20:52:01'),
(666, 592, 723, NULL, '2013-10-18 20:52:01'),
(667, 593, 726, NULL, '2013-10-21 10:36:01'),
(668, 594, 727, NULL, '2013-10-21 10:36:01'),
(669, 595, 728, NULL, '2013-10-21 10:36:01'),
(670, 596, 731, NULL, '2013-10-21 11:13:01'),
(671, 597, 732, NULL, '2013-10-21 11:13:01'),
(672, 598, 733, NULL, '2013-10-21 11:13:01'),
(673, 599, 736, NULL, '2013-10-21 15:03:01'),
(674, 600, 737, NULL, '2013-10-21 15:03:01'),
(675, 601, 738, NULL, '2013-10-21 15:03:01'),
(676, 602, 741, NULL, '2013-10-21 16:54:01'),
(677, 603, 742, NULL, '2013-10-21 16:54:01'),
(678, 604, 743, NULL, '2013-10-21 16:54:01'),
(679, 605, 746, NULL, '2013-10-22 07:49:01'),
(680, 606, 747, NULL, '2013-10-22 07:49:01'),
(681, 607, 748, NULL, '2013-10-22 07:49:01'),
(682, 608, 751, NULL, '2013-10-22 08:15:01'),
(683, 609, 752, NULL, '2013-10-22 08:15:01'),
(684, 610, 753, NULL, '2013-10-22 08:15:01'),
(685, 611, 756, NULL, '2013-10-22 10:13:01'),
(686, 612, 757, NULL, '2013-10-22 10:13:01'),
(687, 613, 758, NULL, '2013-10-22 10:13:01'),
(688, 614, 761, NULL, '2013-10-22 11:30:01'),
(689, 615, 762, NULL, '2013-10-22 11:30:01'),
(690, 616, 763, NULL, '2013-10-22 11:30:01'),
(691, 617, 766, NULL, '2013-10-22 12:57:01'),
(692, 618, 767, NULL, '2013-10-22 12:57:01'),
(693, 619, 768, NULL, '2013-10-22 12:57:01'),
(694, 620, 771, NULL, '2013-10-23 22:15:01'),
(695, 621, 772, NULL, '2013-10-23 22:15:01'),
(696, 622, 773, NULL, '2013-10-23 22:15:01'),
(697, 623, 776, NULL, '2013-10-23 22:35:01'),
(698, 624, 777, NULL, '2013-10-23 22:35:01'),
(699, 625, 778, NULL, '2013-10-23 22:35:01'),
(700, 626, 781, NULL, '2013-10-24 11:13:01'),
(701, 627, 782, NULL, '2013-10-24 11:13:01'),
(702, 628, 783, NULL, '2013-10-24 11:13:01'),
(703, 629, 786, NULL, '2013-10-24 12:47:01'),
(704, 630, 787, NULL, '2013-10-24 12:47:01'),
(705, 631, 788, NULL, '2013-10-24 12:47:01'),
(706, 632, 791, NULL, '2013-10-24 13:41:01'),
(707, 633, 792, NULL, '2013-10-24 13:41:01'),
(708, 634, 793, NULL, '2013-10-24 13:41:01'),
(709, 636, 796, NULL, '2013-10-29 17:22:01'),
(710, 637, 797, NULL, '2013-10-29 17:22:01'),
(711, 638, 798, NULL, '2013-10-29 17:22:01'),
(712, 639, 801, NULL, '2013-10-30 03:27:02'),
(713, 640, 802, NULL, '2013-10-30 03:27:02'),
(714, 641, 803, NULL, '2013-10-30 03:27:02'),
(715, 642, 806, NULL, '2013-10-30 23:27:01'),
(716, 643, 807, NULL, '2013-10-30 23:27:01'),
(717, 644, 808, NULL, '2013-10-30 23:27:01'),
(718, 645, 811, NULL, '2013-11-04 12:56:01'),
(719, 646, 812, NULL, '2013-11-04 12:56:01'),
(720, 647, 813, NULL, '2013-11-04 12:56:01'),
(721, 648, 816, NULL, '2013-11-04 12:58:01'),
(722, 649, 817, NULL, '2013-11-04 12:58:01'),
(723, 650, 818, NULL, '2013-11-04 12:58:01'),
(724, 651, 821, NULL, '2013-11-05 13:25:01'),
(725, 652, 822, NULL, '2013-11-05 13:25:01'),
(726, 653, 823, NULL, '2013-11-05 13:25:01'),
(727, 654, 826, NULL, '2013-11-06 21:53:01'),
(728, 655, 827, NULL, '2013-11-06 21:53:01'),
(729, 656, 828, NULL, '2013-11-06 21:53:01'),
(730, 657, 831, NULL, '2013-11-07 03:23:01'),
(731, 658, 832, NULL, '2013-11-07 03:23:01'),
(732, 659, 833, NULL, '2013-11-07 03:23:01'),
(733, 660, 836, NULL, '2013-11-08 09:20:01'),
(734, 661, 837, NULL, '2013-11-08 09:20:01'),
(735, 662, 838, NULL, '2013-11-08 09:20:01'),
(736, 663, 841, NULL, '2013-11-09 12:26:01'),
(737, 664, 842, NULL, '2013-11-09 12:26:01'),
(738, 665, 843, NULL, '2013-11-09 12:26:01'),
(739, 666, 846, NULL, '2013-11-12 18:17:01'),
(740, 667, 847, NULL, '2013-11-12 18:17:01'),
(741, 668, 848, NULL, '2013-11-12 18:17:01'),
(742, 669, 851, NULL, '2013-11-13 09:30:01'),
(743, 670, 852, NULL, '2013-11-13 09:30:01'),
(744, 671, 853, NULL, '2013-11-13 09:30:01'),
(745, 672, 856, NULL, '2013-11-13 09:32:01'),
(746, 673, 857, NULL, '2013-11-13 09:32:01'),
(747, 674, 858, NULL, '2013-11-13 09:32:01'),
(748, 675, 862, NULL, '2013-11-14 15:35:01'),
(749, 676, 863, NULL, '2013-11-14 15:35:01'),
(750, 677, 864, NULL, '2013-11-14 15:35:01'),
(752, 679, 868, NULL, '2013-11-18 12:17:01'),
(753, 680, 869, NULL, '2013-11-18 12:17:01'),
(754, 681, 870, NULL, '2013-11-18 12:17:01'),
(5159, 5086, 8213, NULL, '2013-11-21 10:57:01'),
(5160, 5087, 8214, NULL, '2013-11-21 10:57:01'),
(5161, 5088, 8215, NULL, '2013-11-21 10:57:01'),
(5162, 5089, 8218, NULL, '2013-11-21 10:57:01'),
(5163, 5090, 8219, NULL, '2013-11-21 10:57:01'),
(5164, 5091, 8220, NULL, '2013-11-21 10:57:01'),
(5165, 5092, 8223, NULL, '2013-11-21 10:57:01'),
(5166, 5093, 8224, NULL, '2013-11-21 10:57:01'),
(5167, 5094, 8225, NULL, '2013-11-21 10:57:01'),
(5168, 5095, 8228, NULL, '2013-11-21 16:51:01'),
(5169, 5096, 8229, NULL, '2013-11-21 16:51:01'),
(5170, 5097, 8230, NULL, '2013-11-21 16:51:01'),
(5171, 5098, 8233, NULL, '2013-11-22 16:04:01'),
(5172, 5099, 8234, NULL, '2013-11-22 16:04:01'),
(5173, 5100, 8235, NULL, '2013-11-22 16:04:01'),
(5174, 5101, 8238, NULL, '2013-11-23 17:13:01'),
(5175, 5102, 8239, NULL, '2013-11-23 17:13:01'),
(5176, 5103, 8240, NULL, '2013-11-23 17:13:01'),
(5177, 5104, 8243, NULL, '2013-11-24 21:45:01'),
(5178, 5105, 8244, NULL, '2013-11-24 21:45:01'),
(5179, 5106, 8245, NULL, '2013-11-24 21:45:01'),
(5180, 5107, 8248, NULL, '2013-11-25 07:54:01'),
(5181, 5108, 8249, NULL, '2013-11-25 07:54:01'),
(5182, 5109, 8250, NULL, '2013-11-25 07:54:01'),
(5183, 5110, 8253, NULL, '2013-11-27 15:56:01'),
(5184, 5111, 8254, NULL, '2013-11-27 15:56:01'),
(5185, 5112, 8255, NULL, '2013-11-27 15:56:01'),
(5186, 5113, 8258, NULL, '2013-11-27 18:47:01'),
(5187, 5114, 8259, NULL, '2013-11-27 18:47:01'),
(5188, 5115, 8260, NULL, '2013-11-27 18:47:01'),
(5189, 5116, 8263, NULL, '2013-12-02 06:44:01'),
(5190, 5117, 8264, NULL, '2013-12-02 06:44:01'),
(5191, 5118, 8265, NULL, '2013-12-02 06:44:01'),
(5192, 5119, 8268, NULL, '2013-12-06 01:23:01'),
(5193, 5120, 8269, NULL, '2013-12-06 01:23:01'),
(5194, 5121, 8270, NULL, '2013-12-06 01:23:01'),
(5195, 5122, 8273, NULL, '2013-12-06 01:49:01'),
(5196, 5123, 8274, NULL, '2013-12-06 01:49:01'),
(5197, 5124, 8275, NULL, '2013-12-06 01:49:01'),
(5198, 5125, 8278, NULL, '2013-12-07 04:36:01'),
(5199, 5126, 8279, NULL, '2013-12-07 04:36:01'),
(5200, 5127, 8280, NULL, '2013-12-07 04:36:01'),
(5201, 5128, 8283, NULL, '2013-12-07 11:11:01'),
(5202, 5129, 8284, NULL, '2013-12-07 11:11:01'),
(5203, 5130, 8285, NULL, '2013-12-07 11:11:01'),
(5204, 5131, 8288, NULL, '2013-12-07 12:41:01'),
(5205, 5132, 8289, NULL, '2013-12-07 12:41:01'),
(5206, 5133, 8290, NULL, '2013-12-07 12:41:01'),
(5207, 5134, 8294, NULL, '2013-12-09 08:11:02'),
(5208, 5135, 8295, NULL, '2013-12-09 08:11:02'),
(5209, 5136, 8296, NULL, '2013-12-09 08:11:02'),
(5210, 5137, 8299, NULL, '2013-12-09 20:07:01'),
(5211, 5138, 8300, NULL, '2013-12-09 20:07:01'),
(5212, 5139, 8301, NULL, '2013-12-09 20:07:01'),
(5213, 5140, 8304, NULL, '2013-12-09 23:42:01'),
(5214, 5141, 8305, NULL, '2013-12-09 23:42:01'),
(5215, 5142, 8306, NULL, '2013-12-09 23:42:01'),
(5216, 5143, 8309, NULL, '2013-12-10 03:55:02'),
(5217, 5144, 8310, NULL, '2013-12-10 03:55:02'),
(5218, 5145, 8311, NULL, '2013-12-10 03:55:02'),
(5219, 5146, 8314, NULL, '2013-12-11 07:37:01'),
(5220, 5147, 8315, NULL, '2013-12-11 07:37:01'),
(5221, 5148, 8316, NULL, '2013-12-11 07:37:01'),
(5222, 5149, 8319, NULL, '2013-12-11 11:33:01'),
(5223, 5150, 8320, NULL, '2013-12-11 11:33:01'),
(5224, 5151, 8321, NULL, '2013-12-11 11:33:01'),
(5227, 5153, 8324, NULL, '2013-12-19 08:53:01'),
(5228, 5154, 8325, NULL, '2013-12-19 08:53:01'),
(5229, 5155, 8326, NULL, '2013-12-19 08:53:01'),
(5230, 5156, 8329, NULL, '2013-12-22 20:25:01'),
(5231, 5157, 8330, NULL, '2013-12-22 20:25:01'),
(5232, 5158, 8331, NULL, '2013-12-22 20:25:01'),
(5233, 5159, 8334, NULL, '2013-12-28 03:51:01'),
(5234, 5160, 8335, NULL, '2013-12-28 03:51:01'),
(5235, 5161, 8336, NULL, '2013-12-28 03:51:01'),
(5236, 5162, 8339, NULL, '2013-12-28 21:32:02'),
(5237, 5163, 8340, NULL, '2013-12-28 21:32:02'),
(5238, 5164, 8341, NULL, '2013-12-28 21:32:02'),
(5239, 5165, 8344, NULL, '2013-12-29 19:31:01'),
(5240, 5166, 8345, NULL, '2013-12-29 19:31:01'),
(5241, 5167, 8346, NULL, '2013-12-29 19:31:01'),
(5242, 5168, 8349, NULL, '2013-12-30 17:39:01'),
(5243, 5169, 8350, NULL, '2013-12-30 17:39:01'),
(5244, 5170, 8351, NULL, '2013-12-30 17:39:01'),
(5245, 5171, 8354, NULL, '2014-01-03 12:07:01'),
(5246, 5172, 8355, NULL, '2014-01-03 12:07:01'),
(5247, 5173, 8356, NULL, '2014-01-03 12:07:01'),
(5248, 5174, 8359, NULL, '2014-01-03 13:46:01'),
(5249, 5175, 8360, NULL, '2014-01-03 13:46:01'),
(5250, 5176, 8361, NULL, '2014-01-03 13:46:01'),
(5251, 5177, 8364, NULL, '2014-01-03 16:42:01'),
(5252, 5178, 8365, NULL, '2014-01-03 16:42:01'),
(5253, 5179, 8366, NULL, '2014-01-03 16:42:01'),
(5254, 5180, 8369, NULL, '2014-01-09 15:36:02'),
(5255, 5181, 8370, NULL, '2014-01-09 15:36:02'),
(5256, 5182, 8371, NULL, '2014-01-09 15:36:02'),
(5257, 5183, 8374, NULL, '2014-01-11 18:46:01'),
(5258, 5184, 8375, NULL, '2014-01-11 18:46:01'),
(5259, 5185, 8376, NULL, '2014-01-11 18:46:01'),
(5260, 5186, 8379, NULL, '2014-01-19 22:51:01'),
(5261, 5187, 8380, NULL, '2014-01-19 22:51:01'),
(5262, 5188, 8381, NULL, '2014-01-19 22:51:01'),
(5263, 5189, 8384, NULL, '2014-01-22 17:21:02'),
(5264, 5190, 8385, NULL, '2014-01-22 17:21:02'),
(5265, 5191, 8386, NULL, '2014-01-22 17:21:02'),
(5266, 5192, 8389, NULL, '2014-01-24 08:48:01'),
(5267, 5193, 8390, NULL, '2014-01-24 08:48:01'),
(5268, 5194, 8391, NULL, '2014-01-24 08:48:01'),
(5269, 5195, 8394, NULL, '2014-02-04 13:01:01'),
(5270, 5196, 8395, NULL, '2014-02-04 13:01:01'),
(5271, 5197, 8396, NULL, '2014-02-04 13:01:01'),
(5272, 5198, 8399, NULL, '2014-02-04 15:51:01'),
(5273, 5199, 8400, NULL, '2014-02-04 15:51:01'),
(5274, 5200, 8401, NULL, '2014-02-04 15:51:01'),
(5275, 5201, 8404, NULL, '2014-02-10 22:04:02'),
(5276, 5202, 8405, NULL, '2014-02-10 22:04:02'),
(5277, 5203, 8406, NULL, '2014-02-10 22:04:02'),
(5278, 5204, 8409, NULL, '2014-02-10 22:05:01'),
(5279, 5205, 8410, NULL, '2014-02-10 22:05:01'),
(5280, 5206, 8411, NULL, '2014-02-10 22:05:01'),
(5281, 5207, 8414, NULL, '2014-02-10 22:06:01'),
(5282, 5208, 8415, NULL, '2014-02-10 22:06:01'),
(5283, 5209, 8416, NULL, '2014-02-10 22:06:01'),
(5284, 5210, 8419, NULL, '2014-02-10 22:07:01'),
(5285, 5211, 8420, NULL, '2014-02-10 22:07:01'),
(5286, 5212, 8421, NULL, '2014-02-10 22:07:01'),
(5287, 5213, 8424, NULL, '2014-02-10 22:08:01'),
(5288, 5214, 8425, NULL, '2014-02-10 22:08:01'),
(5289, 5215, 8426, NULL, '2014-02-10 22:08:01'),
(5290, 5216, 8429, NULL, '2014-02-10 22:09:01'),
(5291, 5217, 8430, NULL, '2014-02-10 22:09:01'),
(5292, 5218, 8431, NULL, '2014-02-10 22:09:01'),
(5293, 5219, 8434, NULL, '2014-02-10 22:10:01'),
(5294, 5220, 8435, NULL, '2014-02-10 22:10:01'),
(5295, 5221, 8436, NULL, '2014-02-10 22:10:01'),
(5296, 5222, 8439, NULL, '2014-02-10 22:11:01'),
(5297, 5223, 8440, NULL, '2014-02-10 22:11:01'),
(5298, 5224, 8441, NULL, '2014-02-10 22:11:01'),
(5299, 5225, 8444, NULL, '2014-02-10 22:12:01'),
(5300, 5226, 8445, NULL, '2014-02-10 22:12:01'),
(5301, 5227, 8446, NULL, '2014-02-10 22:12:01'),
(5302, 5228, 8449, NULL, '2014-02-10 22:13:01'),
(5303, 5229, 8450, NULL, '2014-02-10 22:13:01'),
(5304, 5230, 8451, NULL, '2014-02-10 22:13:01'),
(5305, 5231, 8454, NULL, '2014-02-10 22:14:01'),
(5306, 5232, 8455, NULL, '2014-02-10 22:14:01'),
(5307, 5233, 8456, NULL, '2014-02-10 22:14:01'),
(5308, 5234, 8459, NULL, '2014-02-10 22:15:01'),
(5309, 5235, 8460, NULL, '2014-02-10 22:15:01'),
(5310, 5236, 8461, NULL, '2014-02-10 22:15:01'),
(5311, 5237, 8464, NULL, '2014-02-10 22:16:01'),
(5312, 5238, 8465, NULL, '2014-02-10 22:16:01'),
(5313, 5239, 8466, NULL, '2014-02-10 22:16:01'),
(5314, 5240, 8469, NULL, '2014-02-10 22:17:02'),
(5315, 5241, 8470, NULL, '2014-02-10 22:17:02'),
(5316, 5242, 8471, NULL, '2014-02-10 22:17:02'),
(5317, 5243, 8474, NULL, '2014-02-10 22:18:01'),
(5318, 5244, 8475, NULL, '2014-02-10 22:18:01'),
(5319, 5245, 8476, NULL, '2014-02-10 22:18:01'),
(5320, 5246, 8479, NULL, '2014-02-10 22:19:01'),
(5321, 5247, 8480, NULL, '2014-02-10 22:19:01'),
(5322, 5248, 8481, NULL, '2014-02-10 22:19:01'),
(5323, 5249, 8484, NULL, '2014-02-10 22:20:01'),
(5324, 5250, 8485, NULL, '2014-02-10 22:20:01'),
(5325, 5251, 8486, NULL, '2014-02-10 22:20:01'),
(5326, 5252, 8489, NULL, '2014-02-10 22:21:02'),
(5327, 5253, 8490, NULL, '2014-02-10 22:21:02'),
(5328, 5254, 8491, NULL, '2014-02-10 22:21:02'),
(5329, 5255, 8494, NULL, '2014-02-10 22:22:02'),
(5330, 5256, 8495, NULL, '2014-02-10 22:22:02'),
(5331, 5257, 8496, NULL, '2014-02-10 22:22:02'),
(5332, 5258, 8499, NULL, '2014-02-10 22:23:01'),
(5333, 5259, 8500, NULL, '2014-02-10 22:23:01'),
(5334, 5260, 8501, NULL, '2014-02-10 22:23:01'),
(5335, 5261, 8504, NULL, '2014-02-10 22:24:01'),
(5336, 5262, 8505, NULL, '2014-02-10 22:24:01'),
(5337, 5263, 8506, NULL, '2014-02-10 22:24:01'),
(5338, 5264, 8509, NULL, '2014-02-10 22:25:01'),
(5339, 5265, 8510, NULL, '2014-02-10 22:25:01'),
(5340, 5266, 8511, NULL, '2014-02-10 22:25:01'),
(5341, 5267, 8514, NULL, '2014-02-10 22:26:01'),
(5342, 5268, 8515, NULL, '2014-02-10 22:26:01'),
(5343, 5269, 8516, NULL, '2014-02-10 22:26:01'),
(5344, 5270, 8519, NULL, '2014-02-10 22:27:01'),
(5345, 5271, 8520, NULL, '2014-02-10 22:27:01'),
(5346, 5272, 8521, NULL, '2014-02-10 22:27:01'),
(5347, 5273, 8524, NULL, '2014-02-10 22:28:01'),
(5348, 5274, 8525, NULL, '2014-02-10 22:28:01'),
(5349, 5275, 8526, NULL, '2014-02-10 22:28:01'),
(5350, 5276, 8529, NULL, '2014-02-10 22:29:01'),
(5351, 5277, 8530, NULL, '2014-02-10 22:29:01'),
(5352, 5278, 8531, NULL, '2014-02-10 22:29:01'),
(5353, 5279, 8534, NULL, '2014-02-10 22:30:01'),
(5354, 5280, 8535, NULL, '2014-02-10 22:30:01'),
(5355, 5281, 8536, NULL, '2014-02-10 22:30:01'),
(5356, 5282, 8539, NULL, '2014-02-10 22:31:01'),
(5357, 5283, 8540, NULL, '2014-02-10 22:31:01'),
(5358, 5284, 8541, NULL, '2014-02-10 22:31:01'),
(5359, 5285, 8544, NULL, '2014-02-10 22:32:01'),
(5360, 5286, 8545, NULL, '2014-02-10 22:32:01'),
(5361, 5287, 8546, NULL, '2014-02-10 22:32:01'),
(5362, 5288, 8549, NULL, '2014-02-10 22:33:01'),
(5363, 5289, 8550, NULL, '2014-02-10 22:33:01'),
(5364, 5290, 8551, NULL, '2014-02-10 22:33:01'),
(5365, 5291, 8554, NULL, '2014-02-10 22:34:02'),
(5366, 5292, 8555, NULL, '2014-02-10 22:34:02'),
(5367, 5293, 8556, NULL, '2014-02-10 22:34:02'),
(5368, 5294, 8559, NULL, '2014-02-10 22:35:01'),
(5369, 5295, 8560, NULL, '2014-02-10 22:35:01'),
(5370, 5296, 8561, NULL, '2014-02-10 22:35:01'),
(5371, 5297, 8564, NULL, '2014-02-10 22:36:01'),
(5372, 5298, 8565, NULL, '2014-02-10 22:36:01'),
(5373, 5299, 8566, NULL, '2014-02-10 22:36:01'),
(5374, 5300, 8569, NULL, '2014-02-10 22:37:02'),
(5375, 5301, 8570, NULL, '2014-02-10 22:37:02'),
(5376, 5302, 8571, NULL, '2014-02-10 22:37:02'),
(5377, 5303, 8574, NULL, '2014-02-10 22:38:01'),
(5378, 5304, 8575, NULL, '2014-02-10 22:38:01'),
(5379, 5305, 8576, NULL, '2014-02-10 22:38:01'),
(5380, 5306, 8579, NULL, '2014-02-10 22:39:01'),
(5381, 5307, 8580, NULL, '2014-02-10 22:39:01'),
(5382, 5308, 8581, NULL, '2014-02-10 22:39:01'),
(5383, 5309, 8584, NULL, '2014-02-10 22:40:01'),
(5384, 5310, 8585, NULL, '2014-02-10 22:40:01'),
(5385, 5311, 8586, NULL, '2014-02-10 22:40:01'),
(5386, 5312, 8589, NULL, '2014-02-10 22:41:01'),
(5387, 5313, 8590, NULL, '2014-02-10 22:41:01'),
(5388, 5314, 8591, NULL, '2014-02-10 22:41:01'),
(5389, 5315, 8594, NULL, '2014-02-10 22:42:01'),
(5390, 5316, 8595, NULL, '2014-02-10 22:42:01'),
(5391, 5317, 8596, NULL, '2014-02-10 22:42:01'),
(5392, 5318, 8599, NULL, '2014-02-10 22:43:01'),
(5393, 5319, 8600, NULL, '2014-02-10 22:43:01'),
(5394, 5320, 8601, NULL, '2014-02-10 22:43:01'),
(5395, 5321, 8604, NULL, '2014-02-10 22:44:01'),
(5396, 5322, 8605, NULL, '2014-02-10 22:44:01'),
(5397, 5323, 8606, NULL, '2014-02-10 22:44:01'),
(5398, 5324, 8609, NULL, '2014-02-10 22:45:02'),
(5399, 5325, 8610, NULL, '2014-02-10 22:45:02'),
(5400, 5326, 8611, NULL, '2014-02-10 22:45:02'),
(5401, 5327, 8614, NULL, '2014-02-10 22:46:01'),
(5402, 5328, 8615, NULL, '2014-02-10 22:46:01'),
(5403, 5329, 8616, NULL, '2014-02-10 22:46:01'),
(5404, 5330, 8619, NULL, '2014-02-10 22:47:01'),
(5405, 5331, 8620, NULL, '2014-02-10 22:47:01'),
(5406, 5332, 8621, NULL, '2014-02-10 22:47:01'),
(5407, 5333, 8624, NULL, '2014-02-10 22:48:01'),
(5408, 5334, 8625, NULL, '2014-02-10 22:48:01'),
(5409, 5335, 8626, NULL, '2014-02-10 22:48:01'),
(5410, 5336, 8629, NULL, '2014-02-10 22:49:02'),
(5411, 5337, 8630, NULL, '2014-02-10 22:49:02'),
(5412, 5338, 8631, NULL, '2014-02-10 22:49:02'),
(5413, 5339, 8634, NULL, '2014-02-10 22:50:01'),
(5414, 5340, 8635, NULL, '2014-02-10 22:50:01'),
(5415, 5341, 8636, NULL, '2014-02-10 22:50:01'),
(5416, 5342, 8639, NULL, '2014-02-10 22:51:01'),
(5417, 5343, 8640, NULL, '2014-02-10 22:51:01'),
(5418, 5344, 8641, NULL, '2014-02-10 22:51:01'),
(5419, 5345, 8644, NULL, '2014-02-10 22:52:01'),
(5420, 5346, 8645, NULL, '2014-02-10 22:52:01'),
(5421, 5347, 8646, NULL, '2014-02-10 22:52:01'),
(5422, 5348, 8649, NULL, '2014-02-11 07:19:01'),
(5423, 5349, 8650, NULL, '2014-02-11 07:19:01'),
(5424, 5350, 8651, NULL, '2014-02-11 07:19:01'),
(5425, 5351, 8654, NULL, '2014-02-11 11:51:01'),
(5426, 5352, 8655, NULL, '2014-02-11 11:51:01'),
(5427, 5353, 8656, NULL, '2014-02-11 11:51:01'),
(5428, 5354, 8659, NULL, '2014-02-11 11:53:01'),
(5429, 5355, 8660, NULL, '2014-02-11 11:53:01'),
(5430, 5356, 8661, NULL, '2014-02-11 11:53:01'),
(5431, 5358, 8664, NULL, '2014-02-13 14:25:01'),
(5432, 5359, 8665, NULL, '2014-02-13 14:25:01'),
(5433, 5360, 8666, NULL, '2014-02-13 14:25:01'),
(5434, 5361, 8669, NULL, '2014-02-13 15:11:02'),
(5435, 5362, 8670, NULL, '2014-02-13 15:11:02'),
(5436, 5363, 8671, NULL, '2014-02-13 15:11:02'),
(5437, 5364, 8674, NULL, '2014-02-13 17:35:01'),
(5438, 5365, 8675, NULL, '2014-02-13 17:35:01'),
(5439, 5366, 8676, NULL, '2014-02-13 17:35:01'),
(5440, 5367, 8679, NULL, '2014-02-14 02:07:01'),
(5441, 5368, 8680, NULL, '2014-02-14 02:07:01'),
(5442, 5369, 8681, NULL, '2014-02-14 02:07:01'),
(5443, 5370, 8684, NULL, '2014-02-14 11:18:01'),
(5444, 5371, 8685, NULL, '2014-02-14 11:18:01'),
(5445, 5372, 8686, NULL, '2014-02-14 11:18:01'),
(5446, 5373, 8689, NULL, '2014-02-14 20:24:01'),
(5447, 5374, 8690, NULL, '2014-02-14 20:24:01'),
(5448, 5375, 8691, NULL, '2014-02-14 20:24:01'),
(5449, 5376, 8694, NULL, '2014-02-16 21:37:01'),
(5450, 5377, 8695, NULL, '2014-02-16 21:37:01'),
(5451, 5378, 8696, NULL, '2014-02-16 21:37:01'),
(5452, 5379, 8699, NULL, '2014-02-17 16:02:01'),
(5453, 5380, 8700, NULL, '2014-02-17 16:02:01'),
(5454, 5381, 8701, NULL, '2014-02-17 16:02:01'),
(5455, 5382, 8704, NULL, '2014-02-19 13:32:01'),
(5456, 5383, 8705, NULL, '2014-02-19 13:32:01'),
(5457, 5384, 8706, NULL, '2014-02-19 13:32:01'),
(5458, 5385, 8709, NULL, '2014-02-20 17:55:01'),
(5459, 5386, 8710, NULL, '2014-02-20 17:55:01'),
(5460, 5387, 8711, NULL, '2014-02-20 17:55:01'),
(5461, 5388, 8714, NULL, '2014-02-22 04:05:01'),
(5462, 5389, 8715, NULL, '2014-02-22 04:05:01'),
(5463, 5390, 8716, NULL, '2014-02-22 04:05:01'),
(5464, 5391, 8719, NULL, '2014-02-22 22:26:01'),
(5465, 5392, 8720, NULL, '2014-02-22 22:26:01'),
(5466, 5393, 8721, NULL, '2014-02-22 22:26:01'),
(5467, 5394, 8724, NULL, '2014-02-24 07:43:02'),
(5468, 5395, 8725, NULL, '2014-02-24 07:43:02'),
(5469, 5396, 8726, NULL, '2014-02-24 07:43:02'),
(5470, 5397, 8729, NULL, '2014-02-25 09:32:01'),
(5471, 5398, 8730, NULL, '2014-02-25 09:32:01'),
(5472, 5399, 8731, NULL, '2014-02-25 09:32:01'),
(5473, 5400, 8734, NULL, '2014-03-04 15:20:02'),
(5474, 5401, 8735, NULL, '2014-03-04 15:20:02'),
(5475, 5402, 8736, NULL, '2014-03-04 15:20:02'),
(5476, 5403, 8739, NULL, '2014-03-09 07:05:01'),
(5477, 5404, 8740, NULL, '2014-03-09 07:05:01'),
(5478, 5405, 8741, NULL, '2014-03-09 07:05:01'),
(5479, 5406, 8744, NULL, '2014-03-11 07:54:01'),
(5480, 5407, 8745, NULL, '2014-03-11 07:54:01'),
(5481, 5408, 8746, NULL, '2014-03-11 07:54:01'),
(5482, 5409, 8749, NULL, '2014-03-11 15:36:02'),
(5483, 5410, 8750, NULL, '2014-03-11 15:36:02'),
(5484, 5411, 8751, NULL, '2014-03-11 15:36:02'),
(5485, 5412, 8754, NULL, '2014-03-12 15:42:01'),
(5486, 5413, 8755, NULL, '2014-03-12 15:42:01'),
(5487, 5414, 8756, NULL, '2014-03-12 15:42:01'),
(5488, 5415, 8759, NULL, '2014-03-12 15:42:02'),
(5489, 5416, 8760, NULL, '2014-03-12 15:42:02'),
(5490, 5417, 8761, NULL, '2014-03-12 15:42:02'),
(5491, 5418, 8764, NULL, '2014-03-12 15:43:01'),
(5492, 5419, 8765, NULL, '2014-03-12 15:43:01'),
(5493, 5420, 8766, NULL, '2014-03-12 15:43:01'),
(5494, 5421, 8769, NULL, '2014-03-12 15:43:01'),
(5495, 5422, 8770, NULL, '2014-03-12 15:43:01'),
(5496, 5423, 8771, NULL, '2014-03-12 15:43:01'),
(5497, 5424, 8774, NULL, '2014-03-12 15:47:01'),
(5498, 5425, 8775, NULL, '2014-03-12 15:47:01'),
(5499, 5426, 8776, NULL, '2014-03-12 15:47:01'),
(5500, 5427, 8779, NULL, '2014-03-12 15:49:01'),
(5501, 5428, 8780, NULL, '2014-03-12 15:49:01'),
(5502, 5429, 8781, NULL, '2014-03-12 15:49:01'),
(5503, 5430, 8784, NULL, '2014-03-12 15:50:01'),
(5504, 5431, 8785, NULL, '2014-03-12 15:50:01'),
(5505, 5432, 8786, NULL, '2014-03-12 15:50:01'),
(5506, 5433, 8789, NULL, '2014-03-12 15:51:02'),
(5507, 5434, 8790, NULL, '2014-03-12 15:51:02'),
(5508, 5435, 8791, NULL, '2014-03-12 15:51:02'),
(5509, 5436, 8794, NULL, '2014-03-12 15:51:02'),
(5510, 5437, 8795, NULL, '2014-03-12 15:51:02'),
(5511, 5438, 8796, NULL, '2014-03-12 15:51:02'),
(5512, 5439, 8799, NULL, '2014-03-12 15:53:01'),
(5513, 5440, 8800, NULL, '2014-03-12 15:53:01'),
(5514, 5441, 8801, NULL, '2014-03-12 15:53:01'),
(5515, 5442, 8804, NULL, '2014-03-12 15:54:01'),
(5516, 5443, 8805, NULL, '2014-03-12 15:54:01'),
(5517, 5444, 8806, NULL, '2014-03-12 15:54:01'),
(5518, 5445, 8809, NULL, '2014-03-12 15:55:01'),
(5519, 5446, 8810, NULL, '2014-03-12 15:55:01'),
(5520, 5447, 8811, NULL, '2014-03-12 15:55:01'),
(5521, 5448, 8814, NULL, '2014-03-12 15:55:01'),
(5522, 5449, 8815, NULL, '2014-03-12 15:55:01'),
(5523, 5450, 8816, NULL, '2014-03-12 15:55:01'),
(5524, 5451, 8819, NULL, '2014-03-12 16:00:02'),
(5525, 5452, 8820, NULL, '2014-03-12 16:00:02'),
(5526, 5453, 8821, NULL, '2014-03-12 16:00:02'),
(5527, 5454, 8824, NULL, '2014-03-12 16:01:01'),
(5528, 5455, 8825, NULL, '2014-03-12 16:01:01'),
(5529, 5456, 8826, NULL, '2014-03-12 16:01:01'),
(5530, 5457, 8829, NULL, '2014-03-12 16:01:01'),
(5531, 5458, 8830, NULL, '2014-03-12 16:01:01'),
(5532, 5459, 8831, NULL, '2014-03-12 16:01:01'),
(5533, 5460, 8834, NULL, '2014-03-12 16:04:01'),
(5534, 5461, 8835, NULL, '2014-03-12 16:04:01'),
(5535, 5462, 8836, NULL, '2014-03-12 16:04:01'),
(5536, 5463, 8839, NULL, '2014-03-12 16:07:01'),
(5537, 5464, 8840, NULL, '2014-03-12 16:07:01'),
(5538, 5465, 8841, NULL, '2014-03-12 16:07:01'),
(5539, 5466, 8844, NULL, '2014-03-12 16:07:01'),
(5540, 5467, 8845, NULL, '2014-03-12 16:07:01'),
(5541, 5468, 8846, NULL, '2014-03-12 16:07:01'),
(5542, 5469, 8849, NULL, '2014-03-12 16:09:01'),
(5543, 5470, 8850, NULL, '2014-03-12 16:09:01'),
(5544, 5471, 8851, NULL, '2014-03-12 16:09:01'),
(5545, 5472, 8854, NULL, '2014-03-12 16:10:01'),
(5546, 5473, 8855, NULL, '2014-03-12 16:10:01'),
(5547, 5474, 8856, NULL, '2014-03-12 16:10:01'),
(5548, 5475, 8859, NULL, '2014-03-12 16:13:02'),
(5549, 5476, 8860, NULL, '2014-03-12 16:13:02'),
(5550, 5477, 8861, NULL, '2014-03-12 16:13:02'),
(5551, 5478, 8864, NULL, '2014-03-12 16:14:01'),
(5552, 5479, 8865, NULL, '2014-03-12 16:14:01'),
(5553, 5480, 8866, NULL, '2014-03-12 16:14:01'),
(5554, 5481, 8869, NULL, '2014-03-12 16:17:01'),
(5555, 5482, 8870, NULL, '2014-03-12 16:17:01'),
(5556, 5483, 8871, NULL, '2014-03-12 16:17:01'),
(5557, 5484, 8874, NULL, '2014-03-12 16:17:01'),
(5558, 5485, 8875, NULL, '2014-03-12 16:17:01'),
(5559, 5486, 8876, NULL, '2014-03-12 16:17:01'),
(5560, 5487, 8879, NULL, '2014-03-12 16:17:01'),
(5561, 5488, 8880, NULL, '2014-03-12 16:17:01'),
(5562, 5489, 8881, NULL, '2014-03-12 16:17:01'),
(5563, 5490, 8884, NULL, '2014-03-12 16:18:01'),
(5564, 5491, 8885, NULL, '2014-03-12 16:18:01'),
(5565, 5492, 8886, NULL, '2014-03-12 16:18:01'),
(5566, 5493, 8889, NULL, '2014-03-12 16:21:01'),
(5567, 5494, 8890, NULL, '2014-03-12 16:21:01'),
(5568, 5495, 8891, NULL, '2014-03-12 16:21:01'),
(5569, 5496, 8894, NULL, '2014-03-12 16:23:02'),
(5570, 5497, 8895, NULL, '2014-03-12 16:23:02'),
(5571, 5498, 8896, NULL, '2014-03-12 16:23:02'),
(5572, 5499, 8899, NULL, '2014-03-12 16:23:02'),
(5573, 5500, 8900, NULL, '2014-03-12 16:23:02'),
(5574, 5501, 8901, NULL, '2014-03-12 16:23:02'),
(5575, 5502, 8904, NULL, '2014-03-12 16:25:01'),
(5576, 5503, 8905, NULL, '2014-03-12 16:25:01'),
(5577, 5504, 8906, NULL, '2014-03-12 16:25:01'),
(5578, 5505, 8909, NULL, '2014-03-12 16:25:02'),
(5579, 5506, 8910, NULL, '2014-03-12 16:25:02'),
(5580, 5507, 8911, NULL, '2014-03-12 16:25:02'),
(5581, 5508, 8914, NULL, '2014-03-12 16:26:01'),
(5582, 5509, 8915, NULL, '2014-03-12 16:26:01'),
(5583, 5510, 8916, NULL, '2014-03-12 16:26:01'),
(5584, 5511, 8919, NULL, '2014-03-12 16:27:01'),
(5585, 5512, 8920, NULL, '2014-03-12 16:27:01'),
(5586, 5513, 8921, NULL, '2014-03-12 16:27:01'),
(5587, 5514, 8924, NULL, '2014-03-12 16:29:01'),
(5588, 5515, 8925, NULL, '2014-03-12 16:29:01'),
(5589, 5516, 8926, NULL, '2014-03-12 16:29:01'),
(5590, 5517, 8929, NULL, '2014-03-12 16:32:01'),
(5591, 5518, 8930, NULL, '2014-03-12 16:32:01'),
(5592, 5519, 8931, NULL, '2014-03-12 16:32:01'),
(5593, 5520, 8934, NULL, '2014-03-12 16:37:01'),
(5594, 5521, 8935, NULL, '2014-03-12 16:37:01'),
(5595, 5522, 8936, NULL, '2014-03-12 16:37:01'),
(5596, 5523, 8939, NULL, '2014-03-12 16:38:01'),
(5597, 5524, 8940, NULL, '2014-03-12 16:38:01'),
(5598, 5525, 8941, NULL, '2014-03-12 16:38:01'),
(5599, 5526, 8944, NULL, '2014-03-12 16:47:01'),
(5600, 5527, 8945, NULL, '2014-03-12 16:47:01'),
(5601, 5528, 8946, NULL, '2014-03-12 16:47:01');
INSERT INTO `permission_role_data` (`id`, `permission_role_id`, `default_group_id`, `default_user_id`, `date_created`) VALUES
(5602, 5529, 8949, NULL, '2014-03-12 16:48:01'),
(5603, 5530, 8950, NULL, '2014-03-12 16:48:01'),
(5604, 5531, 8951, NULL, '2014-03-12 16:48:01'),
(5605, 5532, 8954, NULL, '2014-03-12 16:48:02'),
(5606, 5533, 8955, NULL, '2014-03-12 16:48:02'),
(5607, 5534, 8956, NULL, '2014-03-12 16:48:02'),
(5608, 5535, 8959, NULL, '2014-03-12 16:54:01'),
(5609, 5536, 8960, NULL, '2014-03-12 16:54:01'),
(5610, 5537, 8961, NULL, '2014-03-12 16:54:01'),
(5611, 5538, 8964, NULL, '2014-03-12 16:57:02'),
(5612, 5539, 8965, NULL, '2014-03-12 16:57:02'),
(5613, 5540, 8966, NULL, '2014-03-12 16:57:02'),
(5614, 5541, 8969, NULL, '2014-03-12 16:58:01'),
(5615, 5542, 8970, NULL, '2014-03-12 16:58:01'),
(5616, 5543, 8971, NULL, '2014-03-12 16:58:01'),
(5617, 5544, 8974, NULL, '2014-03-12 17:01:01'),
(5618, 5545, 8975, NULL, '2014-03-12 17:01:01'),
(5619, 5546, 8976, NULL, '2014-03-12 17:01:01'),
(5620, 5547, 8979, NULL, '2014-03-12 17:05:01'),
(5621, 5548, 8980, NULL, '2014-03-12 17:05:01'),
(5622, 5549, 8981, NULL, '2014-03-12 17:05:01'),
(5623, 5550, 8984, NULL, '2014-03-12 17:07:01'),
(5624, 5551, 8985, NULL, '2014-03-12 17:07:01'),
(5625, 5552, 8986, NULL, '2014-03-12 17:07:01'),
(5626, 5553, 8989, NULL, '2014-03-12 17:09:01'),
(5627, 5554, 8990, NULL, '2014-03-12 17:09:01'),
(5628, 5555, 8991, NULL, '2014-03-12 17:09:01'),
(5629, 5556, 8994, NULL, '2014-03-12 17:16:02'),
(5630, 5557, 8995, NULL, '2014-03-12 17:16:02'),
(5631, 5558, 8996, NULL, '2014-03-12 17:16:02'),
(5632, 5559, 8999, NULL, '2014-03-12 17:19:01'),
(5633, 5560, 9000, NULL, '2014-03-12 17:19:01'),
(5634, 5561, 9001, NULL, '2014-03-12 17:19:01'),
(5635, 5562, 9004, NULL, '2014-03-12 17:20:01'),
(5636, 5563, 9005, NULL, '2014-03-12 17:20:01'),
(5637, 5564, 9006, NULL, '2014-03-12 17:20:01'),
(5638, 5565, 9009, NULL, '2014-03-12 17:26:02'),
(5639, 5566, 9010, NULL, '2014-03-12 17:26:02'),
(5640, 5567, 9011, NULL, '2014-03-12 17:26:02'),
(5641, 5568, 9014, NULL, '2014-03-12 17:26:02'),
(5642, 5569, 9015, NULL, '2014-03-12 17:26:02'),
(5643, 5570, 9016, NULL, '2014-03-12 17:26:02'),
(5644, 5571, 9019, NULL, '2014-03-12 17:33:01'),
(5645, 5572, 9020, NULL, '2014-03-12 17:33:01'),
(5646, 5573, 9021, NULL, '2014-03-12 17:33:01'),
(5647, 5574, 9024, NULL, '2014-03-12 17:36:01'),
(5648, 5575, 9025, NULL, '2014-03-12 17:36:01'),
(5649, 5576, 9026, NULL, '2014-03-12 17:36:01'),
(5650, 5577, 9029, NULL, '2014-03-12 17:44:01'),
(5651, 5578, 9030, NULL, '2014-03-12 17:44:01'),
(5652, 5579, 9031, NULL, '2014-03-12 17:44:01'),
(5653, 5580, 9034, NULL, '2014-03-12 17:48:01'),
(5654, 5581, 9035, NULL, '2014-03-12 17:48:01'),
(5655, 5582, 9036, NULL, '2014-03-12 17:48:01'),
(5656, 5583, 9039, NULL, '2014-03-12 17:49:01'),
(5657, 5584, 9040, NULL, '2014-03-12 17:49:01'),
(5658, 5585, 9041, NULL, '2014-03-12 17:49:01'),
(5659, 5586, 9044, NULL, '2014-03-12 17:52:01'),
(5660, 5587, 9045, NULL, '2014-03-12 17:52:01'),
(5661, 5588, 9046, NULL, '2014-03-12 17:52:01'),
(5662, 5589, 9049, NULL, '2014-03-12 17:55:01'),
(5663, 5590, 9050, NULL, '2014-03-12 17:55:01'),
(5664, 5591, 9051, NULL, '2014-03-12 17:55:01'),
(5665, 5592, 9054, NULL, '2014-03-12 18:11:01'),
(5666, 5593, 9055, NULL, '2014-03-12 18:11:01'),
(5667, 5594, 9056, NULL, '2014-03-12 18:11:01'),
(5668, 5595, 9059, NULL, '2014-03-12 18:14:01'),
(5669, 5596, 9060, NULL, '2014-03-12 18:14:01'),
(5670, 5597, 9061, NULL, '2014-03-12 18:14:01'),
(5671, 5598, 9064, NULL, '2014-03-12 18:15:01'),
(5672, 5599, 9065, NULL, '2014-03-12 18:15:01'),
(5673, 5600, 9066, NULL, '2014-03-12 18:15:01'),
(5674, 5601, 9069, NULL, '2014-03-12 18:28:01'),
(5675, 5602, 9070, NULL, '2014-03-12 18:28:01'),
(5676, 5603, 9071, NULL, '2014-03-12 18:28:01'),
(5677, 5604, 9074, NULL, '2014-03-12 18:34:01'),
(5678, 5605, 9075, NULL, '2014-03-12 18:34:01'),
(5679, 5606, 9076, NULL, '2014-03-12 18:34:01'),
(5680, 5607, 9079, NULL, '2014-03-12 18:48:01'),
(5681, 5608, 9080, NULL, '2014-03-12 18:48:01'),
(5682, 5609, 9081, NULL, '2014-03-12 18:48:01'),
(5683, 5610, 9084, NULL, '2014-03-12 19:00:01'),
(5684, 5611, 9085, NULL, '2014-03-12 19:00:01'),
(5685, 5612, 9086, NULL, '2014-03-12 19:00:01'),
(5686, 5613, 9089, NULL, '2014-03-12 19:03:01'),
(5687, 5614, 9090, NULL, '2014-03-12 19:03:01'),
(5688, 5615, 9091, NULL, '2014-03-12 19:03:01'),
(5689, 5616, 9094, NULL, '2014-03-12 19:05:01'),
(5690, 5617, 9095, NULL, '2014-03-12 19:05:01'),
(5691, 5618, 9096, NULL, '2014-03-12 19:05:01'),
(5692, 5619, 9099, NULL, '2014-03-12 19:10:01'),
(5693, 5620, 9100, NULL, '2014-03-12 19:10:01'),
(5694, 5621, 9101, NULL, '2014-03-12 19:10:01'),
(5695, 5622, 9104, NULL, '2014-03-12 19:14:01'),
(5696, 5623, 9105, NULL, '2014-03-12 19:14:01'),
(5697, 5624, 9106, NULL, '2014-03-12 19:14:01'),
(5698, 5625, 9109, NULL, '2014-03-12 19:29:01'),
(5699, 5626, 9110, NULL, '2014-03-12 19:29:01'),
(5700, 5627, 9111, NULL, '2014-03-12 19:29:01'),
(5701, 5628, 9114, NULL, '2014-03-12 19:55:01'),
(5702, 5629, 9115, NULL, '2014-03-12 19:55:01'),
(5703, 5630, 9116, NULL, '2014-03-12 19:55:01'),
(5704, 5631, 9119, NULL, '2014-03-12 19:57:02'),
(5705, 5632, 9120, NULL, '2014-03-12 19:57:02'),
(5706, 5633, 9121, NULL, '2014-03-12 19:57:02'),
(5707, 5634, 9124, NULL, '2014-03-12 19:58:01'),
(5708, 5635, 9125, NULL, '2014-03-12 19:58:01'),
(5709, 5636, 9126, NULL, '2014-03-12 19:58:01'),
(5710, 5637, 9129, NULL, '2014-03-12 20:20:01'),
(5711, 5638, 9130, NULL, '2014-03-12 20:20:01'),
(5712, 5639, 9131, NULL, '2014-03-12 20:20:01'),
(5713, 5640, 9134, NULL, '2014-03-12 20:22:02'),
(5714, 5641, 9135, NULL, '2014-03-12 20:22:02'),
(5715, 5642, 9136, NULL, '2014-03-12 20:22:02'),
(5716, 5643, 9139, NULL, '2014-03-12 20:27:01'),
(5717, 5644, 9140, NULL, '2014-03-12 20:27:01'),
(5718, 5645, 9141, NULL, '2014-03-12 20:27:01'),
(5719, 5646, 9144, NULL, '2014-03-12 20:29:01'),
(5720, 5647, 9145, NULL, '2014-03-12 20:29:01'),
(5721, 5648, 9146, NULL, '2014-03-12 20:29:01'),
(5722, 5649, 9149, NULL, '2014-03-12 20:40:01'),
(5723, 5650, 9150, NULL, '2014-03-12 20:40:01'),
(5724, 5651, 9151, NULL, '2014-03-12 20:40:01'),
(5725, 5652, 9154, NULL, '2014-03-12 20:57:01'),
(5726, 5653, 9155, NULL, '2014-03-12 20:57:01'),
(5727, 5654, 9156, NULL, '2014-03-12 20:57:01'),
(5728, 5655, 9159, NULL, '2014-03-12 21:02:01'),
(5729, 5656, 9160, NULL, '2014-03-12 21:02:01'),
(5730, 5657, 9161, NULL, '2014-03-12 21:02:01'),
(5731, 5658, 9164, NULL, '2014-03-12 21:44:01'),
(5732, 5659, 9165, NULL, '2014-03-12 21:44:01'),
(5733, 5660, 9166, NULL, '2014-03-12 21:44:01'),
(5734, 5661, 9169, NULL, '2014-03-12 22:09:01'),
(5735, 5662, 9170, NULL, '2014-03-12 22:09:01'),
(5736, 5663, 9171, NULL, '2014-03-12 22:09:01'),
(5737, 5664, 9174, NULL, '2014-03-12 22:14:01'),
(5738, 5665, 9175, NULL, '2014-03-12 22:14:01'),
(5739, 5666, 9176, NULL, '2014-03-12 22:14:01'),
(5740, 5667, 9179, NULL, '2014-03-12 22:16:01'),
(5741, 5668, 9180, NULL, '2014-03-12 22:16:01'),
(5742, 5669, 9181, NULL, '2014-03-12 22:16:01'),
(5743, 5670, 9184, NULL, '2014-03-12 22:19:01'),
(5744, 5671, 9185, NULL, '2014-03-12 22:19:01'),
(5745, 5672, 9186, NULL, '2014-03-12 22:19:01'),
(5746, 5673, 9189, NULL, '2014-03-12 22:49:01'),
(5747, 5674, 9190, NULL, '2014-03-12 22:49:01'),
(5748, 5675, 9191, NULL, '2014-03-12 22:49:01'),
(5749, 5676, 9194, NULL, '2014-03-12 22:59:01'),
(5750, 5677, 9195, NULL, '2014-03-12 22:59:01'),
(5751, 5678, 9196, NULL, '2014-03-12 22:59:01'),
(5752, 5679, 9199, NULL, '2014-03-12 23:21:01'),
(5753, 5680, 9200, NULL, '2014-03-12 23:21:01'),
(5754, 5681, 9201, NULL, '2014-03-12 23:21:01'),
(5755, 5682, 9204, NULL, '2014-03-12 23:30:02'),
(5756, 5683, 9205, NULL, '2014-03-12 23:30:02'),
(5757, 5684, 9206, NULL, '2014-03-12 23:30:02'),
(5758, 5685, 9209, NULL, '2014-03-12 23:44:01'),
(5759, 5686, 9210, NULL, '2014-03-12 23:44:01'),
(5760, 5687, 9211, NULL, '2014-03-12 23:44:01'),
(5761, 5688, 9214, NULL, '2014-03-13 02:20:01'),
(5762, 5689, 9215, NULL, '2014-03-13 02:20:01'),
(5763, 5690, 9216, NULL, '2014-03-13 02:20:01'),
(5764, 5691, 9219, NULL, '2014-03-13 02:39:02'),
(5765, 5692, 9220, NULL, '2014-03-13 02:39:02'),
(5766, 5693, 9221, NULL, '2014-03-13 02:39:02'),
(5767, 5694, 9224, NULL, '2014-03-13 03:07:01'),
(5768, 5695, 9225, NULL, '2014-03-13 03:07:01'),
(5769, 5696, 9226, NULL, '2014-03-13 03:07:01'),
(5770, 5697, 9229, NULL, '2014-03-13 04:06:01'),
(5771, 5698, 9230, NULL, '2014-03-13 04:06:01'),
(5772, 5699, 9231, NULL, '2014-03-13 04:06:01'),
(5773, 5700, 9234, NULL, '2014-03-13 05:21:02'),
(5774, 5701, 9235, NULL, '2014-03-13 05:21:02'),
(5775, 5702, 9236, NULL, '2014-03-13 05:21:02'),
(5776, 5703, 9239, NULL, '2014-03-13 06:02:01'),
(5777, 5704, 9240, NULL, '2014-03-13 06:02:01'),
(5778, 5705, 9241, NULL, '2014-03-13 06:02:01'),
(5779, 5706, 9244, NULL, '2014-03-13 08:15:01'),
(5780, 5707, 9245, NULL, '2014-03-13 08:15:01'),
(5781, 5708, 9246, NULL, '2014-03-13 08:15:01'),
(5782, 5709, 9249, NULL, '2014-03-13 08:54:02'),
(5783, 5710, 9250, NULL, '2014-03-13 08:54:02'),
(5784, 5711, 9251, NULL, '2014-03-13 08:54:02'),
(5785, 5712, 9254, NULL, '2014-03-13 12:49:01'),
(5786, 5713, 9255, NULL, '2014-03-13 12:49:01'),
(5787, 5714, 9256, NULL, '2014-03-13 12:49:01'),
(5788, 5715, 9259, NULL, '2014-03-13 13:02:01'),
(5789, 5716, 9260, NULL, '2014-03-13 13:02:01'),
(5790, 5717, 9261, NULL, '2014-03-13 13:02:01'),
(5791, 5718, 9264, NULL, '2014-03-13 14:04:02'),
(5792, 5719, 9265, NULL, '2014-03-13 14:04:02'),
(5793, 5720, 9266, NULL, '2014-03-13 14:04:02'),
(5794, 5721, 9269, NULL, '2014-03-13 14:16:02'),
(5795, 5722, 9270, NULL, '2014-03-13 14:16:02'),
(5796, 5723, 9271, NULL, '2014-03-13 14:16:02'),
(5797, 5724, 9274, NULL, '2014-03-13 14:35:01'),
(5798, 5725, 9275, NULL, '2014-03-13 14:35:01'),
(5799, 5726, 9276, NULL, '2014-03-13 14:35:01'),
(5800, 5727, 9279, NULL, '2014-03-13 16:23:02'),
(5801, 5728, 9280, NULL, '2014-03-13 16:23:02'),
(5802, 5729, 9281, NULL, '2014-03-13 16:23:02'),
(5803, 5730, 9284, NULL, '2014-03-13 16:54:01'),
(5804, 5731, 9285, NULL, '2014-03-13 16:54:01'),
(5805, 5732, 9286, NULL, '2014-03-13 16:54:01'),
(5806, 5733, 9289, NULL, '2014-03-13 16:56:01'),
(5807, 5734, 9290, NULL, '2014-03-13 16:56:01'),
(5808, 5735, 9291, NULL, '2014-03-13 16:56:01'),
(5809, 5736, 9294, NULL, '2014-03-13 19:30:01'),
(5810, 5737, 9295, NULL, '2014-03-13 19:30:01'),
(5811, 5738, 9296, NULL, '2014-03-13 19:30:01'),
(5812, 5739, 9299, NULL, '2014-03-13 19:58:01'),
(5813, 5740, 9300, NULL, '2014-03-13 19:58:01'),
(5814, 5741, 9301, NULL, '2014-03-13 19:58:01'),
(5815, 5742, 9304, NULL, '2014-03-13 20:12:01'),
(5816, 5743, 9305, NULL, '2014-03-13 20:12:01'),
(5817, 5744, 9306, NULL, '2014-03-13 20:12:01'),
(5818, 5745, 9309, NULL, '2014-03-13 23:55:02'),
(5819, 5746, 9310, NULL, '2014-03-13 23:55:02'),
(5820, 5747, 9311, NULL, '2014-03-13 23:55:02'),
(5821, 5748, 9314, NULL, '2014-03-14 04:22:01'),
(5822, 5749, 9315, NULL, '2014-03-14 04:22:01'),
(5823, 5750, 9316, NULL, '2014-03-14 04:22:01'),
(5824, 5751, 9319, NULL, '2014-03-14 07:33:01'),
(5825, 5752, 9320, NULL, '2014-03-14 07:33:01'),
(5826, 5753, 9321, NULL, '2014-03-14 07:33:01'),
(5827, 5754, 9324, NULL, '2014-03-14 08:36:01'),
(5828, 5755, 9325, NULL, '2014-03-14 08:36:01'),
(5829, 5756, 9326, NULL, '2014-03-14 08:36:01'),
(5830, 5757, 9329, NULL, '2014-03-14 09:57:01'),
(5831, 5758, 9330, NULL, '2014-03-14 09:57:01'),
(5832, 5759, 9331, NULL, '2014-03-14 09:57:01'),
(5833, 5760, 9334, NULL, '2014-03-14 15:45:01'),
(5834, 5761, 9335, NULL, '2014-03-14 15:45:01'),
(5835, 5762, 9336, NULL, '2014-03-14 15:45:01'),
(5836, 5763, 9339, NULL, '2014-03-15 00:28:01'),
(5837, 5764, 9340, NULL, '2014-03-15 00:28:01'),
(5838, 5765, 9341, NULL, '2014-03-15 00:28:01'),
(5839, 5767, 9344, NULL, '2014-03-15 22:23:01'),
(5840, 5768, 9345, NULL, '2014-03-15 22:23:01'),
(5841, 5769, 9346, NULL, '2014-03-15 22:23:01'),
(5842, 5770, 9349, NULL, '2014-03-16 06:12:01'),
(5843, 5771, 9350, NULL, '2014-03-16 06:12:01'),
(5844, 5772, 9351, NULL, '2014-03-16 06:12:01'),
(5845, 5773, 9354, NULL, '2014-03-16 13:45:01'),
(5846, 5774, 9355, NULL, '2014-03-16 13:45:01'),
(5847, 5775, 9356, NULL, '2014-03-16 13:45:01'),
(5852, 5776, 9359, NULL, '2014-03-17 20:01:01'),
(5853, 5777, 9360, NULL, '2014-03-17 20:01:01'),
(5854, 5778, 9361, NULL, '2014-03-17 20:01:01'),
(5855, 5779, 9364, NULL, '2014-03-18 00:12:01'),
(5856, 5780, 9365, NULL, '2014-03-18 00:12:01'),
(5857, 5781, 9366, NULL, '2014-03-18 00:12:01'),
(5858, 5782, 9369, NULL, '2014-03-19 19:24:01'),
(5859, 5783, 9370, NULL, '2014-03-19 19:24:01'),
(5860, 5784, 9371, NULL, '2014-03-19 19:24:01'),
(5861, 5785, 9374, NULL, '2014-03-20 12:05:01'),
(5862, 5786, 9375, NULL, '2014-03-20 12:05:01'),
(5863, 5787, 9376, NULL, '2014-03-20 12:05:01'),
(5864, 5788, 9379, NULL, '2014-03-20 13:41:01'),
(5865, 5789, 9380, NULL, '2014-03-20 13:41:01'),
(5866, 5790, 9381, NULL, '2014-03-20 13:41:01'),
(5867, 5791, 9384, NULL, '2014-03-21 18:34:01'),
(5868, 5792, 9385, NULL, '2014-03-21 18:34:01'),
(5869, 5793, 9386, NULL, '2014-03-21 18:34:01'),
(5870, 5794, 9389, NULL, '2014-03-25 14:02:01'),
(5871, 5795, 9390, NULL, '2014-03-25 14:02:01'),
(5872, 5796, 9391, NULL, '2014-03-25 14:02:01'),
(5876, 311, 314, NULL, '2014-03-26 16:15:07'),
(5877, 5797, 9394, NULL, '2014-03-26 19:15:01'),
(5878, 5798, 9395, NULL, '2014-03-26 19:15:01'),
(5879, 5799, 9396, NULL, '2014-03-26 19:15:01'),
(5880, 5800, 9399, NULL, '2014-03-28 10:23:01'),
(5881, 5801, 9400, NULL, '2014-03-28 10:23:01'),
(5882, 5802, 9401, NULL, '2014-03-28 10:23:01'),
(5883, 5803, 9404, NULL, '2014-03-28 11:49:01'),
(5884, 5804, 9405, NULL, '2014-03-28 11:49:01'),
(5885, 5805, 9406, NULL, '2014-03-28 11:49:01'),
(5886, 5806, 9409, NULL, '2014-04-04 10:16:01'),
(5887, 5807, 9410, NULL, '2014-04-04 10:16:01'),
(5888, 5808, 9411, NULL, '2014-04-04 10:16:01'),
(5889, 5809, 9414, NULL, '2014-04-08 19:49:01'),
(5890, 5810, 9415, NULL, '2014-04-08 19:49:01'),
(5891, 5811, 9416, NULL, '2014-04-08 19:49:01'),
(5892, 5813, 9419, NULL, '2014-04-11 09:56:01'),
(5893, 5814, 9420, NULL, '2014-04-11 09:56:01'),
(5894, 5815, 9421, NULL, '2014-04-11 09:56:01'),
(5895, 5816, 9424, NULL, '2014-04-15 21:46:01'),
(5896, 5817, 9425, NULL, '2014-04-15 21:46:01'),
(5897, 5818, 9426, NULL, '2014-04-15 21:46:01'),
(5898, 5819, 9429, NULL, '2014-04-21 07:56:01'),
(5899, 5820, 9430, NULL, '2014-04-21 07:56:01'),
(5900, 5821, 9431, NULL, '2014-04-21 07:56:01'),
(5901, 5822, 9434, NULL, '2014-04-23 08:00:01'),
(5902, 5823, 9435, NULL, '2014-04-23 08:00:01'),
(5903, 5824, 9436, NULL, '2014-04-23 08:00:01'),
(5904, 5825, 9439, NULL, '2014-05-08 19:17:01'),
(5905, 5826, 9440, NULL, '2014-05-08 19:17:01'),
(5906, 5827, 9441, NULL, '2014-05-08 19:17:01'),
(5907, 5828, 9444, NULL, '2014-05-08 19:17:02'),
(5908, 5829, 9445, NULL, '2014-05-08 19:17:02'),
(5909, 5830, 9446, NULL, '2014-05-08 19:17:02'),
(5910, 5831, 9449, NULL, '2014-05-08 19:17:02'),
(5911, 5832, 9450, NULL, '2014-05-08 19:17:02'),
(5912, 5833, 9451, NULL, '2014-05-08 19:17:02'),
(5913, 5834, 9454, NULL, '2014-05-08 19:17:02'),
(5914, 5835, 9455, NULL, '2014-05-08 19:17:02'),
(5915, 5836, 9456, NULL, '2014-05-08 19:17:02'),
(5916, 5837, 9459, NULL, '2014-05-08 19:17:02'),
(5917, 5838, 9460, NULL, '2014-05-08 19:17:02'),
(5918, 5839, 9461, NULL, '2014-05-08 19:17:02'),
(5919, 5840, 9464, NULL, '2014-05-08 19:17:02'),
(5920, 5841, 9465, NULL, '2014-05-08 19:17:02'),
(5921, 5842, 9466, NULL, '2014-05-08 19:17:02'),
(5922, 5843, 9469, NULL, '2014-05-08 19:17:03'),
(5923, 5844, 9470, NULL, '2014-05-08 19:17:03'),
(5924, 5845, 9471, NULL, '2014-05-08 19:17:03'),
(5925, 5846, 9474, NULL, '2014-05-08 19:17:03'),
(5926, 5847, 9475, NULL, '2014-05-08 19:17:03'),
(5927, 5848, 9476, NULL, '2014-05-08 19:17:03'),
(5928, 5849, 9479, NULL, '2014-05-14 10:00:01'),
(5929, 5850, 9480, NULL, '2014-05-14 10:00:01'),
(5930, 5851, 9481, NULL, '2014-05-14 10:00:01'),
(5931, 5852, 9484, NULL, '2014-05-16 22:22:01'),
(5932, 5853, 9485, NULL, '2014-05-16 22:22:01'),
(5933, 5854, 9486, NULL, '2014-05-16 22:22:01'),
(5934, 5855, 9489, NULL, '2014-05-22 16:37:02'),
(5935, 5856, 9490, NULL, '2014-05-22 16:37:02'),
(5937, 5857, 9491, NULL, '2014-05-22 17:45:04'),
(5938, 5857, 9494, NULL, '2014-05-22 17:45:04'),
(5939, 5859, 9495, NULL, '2014-05-23 09:10:02'),
(5940, 5860, 9496, NULL, '2014-05-23 09:10:02'),
(5941, 5861, 9497, NULL, '2014-05-23 09:10:02'),
(5942, 5862, 9500, NULL, '2014-05-25 13:55:01'),
(5943, 5863, 9501, NULL, '2014-05-25 13:55:01'),
(5944, 5864, 9502, NULL, '2014-05-25 13:55:01'),
(5945, 5865, 9505, NULL, '2014-05-30 13:07:01'),
(5946, 5866, 9506, NULL, '2014-05-30 13:07:01'),
(5947, 5867, 9507, NULL, '2014-05-30 13:07:01'),
(5948, 5869, 9510, NULL, '2014-06-06 18:21:01'),
(5949, 5870, 9511, NULL, '2014-06-06 18:21:01'),
(5950, 5871, 9512, NULL, '2014-06-06 18:21:01'),
(5951, 5872, 9515, NULL, '2014-06-10 17:19:02'),
(5952, 5873, 9516, NULL, '2014-06-10 17:19:02'),
(5953, 5874, 9517, NULL, '2014-06-10 17:19:02'),
(5954, 5875, 9520, NULL, '2014-06-17 13:34:02'),
(5955, 5876, 9521, NULL, '2014-06-17 13:34:02'),
(5956, 5877, 9522, NULL, '2014-06-17 13:34:02'),
(5957, 5878, 9527, NULL, '2014-06-26 12:08:36'),
(5958, 5879, 9528, NULL, '2014-06-26 12:08:36'),
(5959, 5880, 9529, NULL, '2014-06-26 12:08:36'),
(5960, 5881, 9532, NULL, '2014-06-26 12:11:23'),
(5961, 5882, 9533, NULL, '2014-06-26 12:11:23'),
(5962, 5883, 9534, NULL, '2014-06-26 12:11:23'),
(5963, 5884, 9537, NULL, '2014-06-26 12:17:30'),
(5964, 5885, 9538, NULL, '2014-06-26 12:17:30'),
(5965, 5886, 9539, NULL, '2014-06-26 12:17:30'),
(5966, 5887, 9542, NULL, '2014-06-26 14:00:20'),
(5967, 5888, 9543, NULL, '2014-06-26 14:00:20'),
(5968, 5889, 9544, NULL, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `permission_scheme`
--

CREATE TABLE IF NOT EXISTS `permission_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1970 ;

--
-- Dumping data for table `permission_scheme`
--

INSERT INTO `permission_scheme` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1950, 1940, 'Default Permission Scheme', 'Default Permission Scheme', '2014-05-08 19:17:02', NULL),
(1952, 1942, 'Default Permission Scheme', 'Default Permission Scheme', '2014-05-08 19:17:02', NULL),
(1954, 1944, 'Default Permission Scheme', 'Default Permission Scheme', '2014-05-08 19:17:02', NULL),
(1969, 1959, 'Default Permission Scheme', 'Default Permission Scheme', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_scheme_data`
--

CREATE TABLE IF NOT EXISTS `permission_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `permission_scheme_id` bigint(20) unsigned NOT NULL,
  `sys_permission_id` bigint(20) unsigned NOT NULL,
  `permission_role_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `current_assignee` tinyint(3) unsigned DEFAULT NULL,
  `reporter` tinyint(3) unsigned DEFAULT NULL,
  `project_lead` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47899 ;

--
-- Dumping data for table `permission_scheme_data`
--

INSERT INTO `permission_scheme_data` (`id`, `permission_scheme_id`, `sys_permission_id`, `permission_role_id`, `group_id`, `user_id`, `current_assignee`, `reporter`, `project_lead`, `date_created`) VALUES
(4518, 0, 1, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4519, 0, 2, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4520, 0, 3, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4521, 0, 4, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4522, 0, 5, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4523, 0, 6, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4524, 0, 7, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4525, 0, 8, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4526, 0, 9, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4527, 0, 10, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4528, 0, 11, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4529, 0, 12, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4530, 0, 14, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4531, 0, 13, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4532, 0, 15, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4533, 0, 16, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4534, 0, 17, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4535, 0, 18, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4536, 0, 22, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4537, 0, 23, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4538, 0, 24, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4539, 0, 25, 586, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4540, 0, 26, 584, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(4541, 0, 20, 585, NULL, NULL, NULL, NULL, NULL, '0000-00-00 00:00:00'),
(47377, 1950, 1, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47378, 1950, 2, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47379, 1950, 3, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47380, 1950, 4, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47381, 1950, 5, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47382, 1950, 6, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47383, 1950, 7, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47384, 1950, 8, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47385, 1950, 9, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47386, 1950, 10, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47387, 1950, 11, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47388, 1950, 12, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47389, 1950, 14, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47390, 1950, 13, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47391, 1950, 15, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47392, 1950, 16, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47393, 1950, 17, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47394, 1950, 18, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47395, 1950, 22, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47396, 1950, 23, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47397, 1950, 24, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47398, 1950, 25, 5830, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47399, 1950, 26, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47400, 1950, 20, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47401, 1950, 27, 5829, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47402, 1950, 28, 5828, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47429, 1952, 1, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47430, 1952, 2, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47431, 1952, 3, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47432, 1952, 4, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47433, 1952, 5, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47434, 1952, 6, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47435, 1952, 7, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47436, 1952, 8, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47437, 1952, 9, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47438, 1952, 10, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47439, 1952, 11, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47440, 1952, 12, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47441, 1952, 14, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47442, 1952, 13, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47443, 1952, 15, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47444, 1952, 16, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47445, 1952, 17, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47446, 1952, 18, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47447, 1952, 22, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47448, 1952, 23, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47449, 1952, 24, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47450, 1952, 25, 5836, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47451, 1952, 26, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47452, 1952, 20, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47453, 1952, 27, 5835, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47454, 1952, 28, 5834, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47481, 1954, 1, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47482, 1954, 2, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47483, 1954, 3, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47484, 1954, 4, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47485, 1954, 5, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47486, 1954, 6, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47487, 1954, 7, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47488, 1954, 8, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47489, 1954, 9, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47490, 1954, 10, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47491, 1954, 11, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47492, 1954, 12, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47493, 1954, 14, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47494, 1954, 13, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47495, 1954, 15, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47496, 1954, 16, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47497, 1954, 17, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47498, 1954, 18, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47499, 1954, 22, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47500, 1954, 23, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47501, 1954, 24, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47502, 1954, 25, 5842, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47503, 1954, 26, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47504, 1954, 20, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47505, 1954, 27, 5841, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47506, 1954, 28, 5840, NULL, NULL, NULL, NULL, NULL, '2014-05-08 19:17:02'),
(47873, 1969, 1, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47874, 1969, 2, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47875, 1969, 3, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47876, 1969, 4, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47877, 1969, 5, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47878, 1969, 6, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47879, 1969, 7, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47880, 1969, 8, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47881, 1969, 9, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47882, 1969, 10, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47883, 1969, 11, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47884, 1969, 12, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47885, 1969, 14, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47886, 1969, 13, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47887, 1969, 15, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47888, 1969, 16, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47889, 1969, 17, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47890, 1969, 18, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47891, 1969, 22, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47892, 1969, 23, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47893, 1969, 24, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47894, 1969, 25, 5889, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47895, 1969, 26, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47896, 1969, 20, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47897, 1969, 27, 5888, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20'),
(47898, 1969, 28, 5887, NULL, NULL, NULL, NULL, NULL, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
`id` bigint(20) unsigned NOT NULL,
  `lead_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `issue_type_scheme_id` bigint(20) unsigned NOT NULL,
  `issue_type_screen_scheme_id` bigint(20) unsigned NOT NULL,
  `issue_type_field_configuration_id` bigint(20) unsigned NOT NULL,
  `workflow_scheme_id` bigint(20) unsigned NOT NULL,
  `permission_scheme_id` bigint(20) unsigned NOT NULL,
  `notification_scheme_id` bigint(20) unsigned NOT NULL,
  `issue_security_scheme_id` bigint(20) unsigned DEFAULT NULL,
  `project_category_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `code` varchar(5) NOT NULL,
  `description` varchar(250) NOT NULL,
  `help_desk_enabled_flag` tinyint(3) unsigned DEFAULT NULL,
  `issue_number` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=494 ;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `lead_id`, `client_id`, `issue_type_scheme_id`, `issue_type_screen_scheme_id`, `issue_type_field_configuration_id`, `workflow_scheme_id`, `permission_scheme_id`, `notification_scheme_id`, `issue_security_scheme_id`, `project_category_id`, `name`, `code`, `description`, `help_desk_enabled_flag`, `issue_number`, `date_created`, `date_updated`) VALUES
(458, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'MMS', 'code', 'MMS Sofia', 0, 5, '2014-06-26 14:00:20', NULL),
(459, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Bayer ISP on Myriad2', 'code', 'Bayer ISP pipeline for Myriad2', 0, 0, '2014-06-26 14:00:20', NULL),
(460, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Fragrak_postTO', 'code', 'Placeholder for bug/features that can be resolved after tapeout', 0, 0, '2014-06-26 14:00:20', NULL),
(461, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Fragrak_sw', 'code', 'Myriad 2 software related issues pre-tapeout', 0, 0, '2014-06-26 14:00:20', NULL),
(462, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'HW features', 'code', 'This product holds general requests/ideas for future Movidius silicon generations.', 0, 0, '2014-06-26 14:00:20', NULL),
(463, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'AptinaHDR', 'code', 'Aptina HDR', 0, 0, '2014-06-26 14:00:20', NULL),
(464, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'SDK', 'code', 'Movidius SDK', 0, 0, '2014-06-26 14:00:20', NULL),
(465, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Vision', 'code', 'Gesture recognition algorithm development, demo development (mv0153), and dataset construction', 0, 0, '2014-06-26 14:00:20', NULL),
(466, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Example Applications', 'code', 'Example appls such as Rectification, SkypeCam, SimpleVideoEffects', 0, 0, '2014-06-26 14:00:20', NULL),
(467, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Purple', 'code', 'for Linx Imaging', 0, 0, '2014-06-26 14:00:20', NULL),
(468, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'poLight', 'code', 'poLight auto-focus mod. to OV sensor on MV0121 board', 0, 0, '2014-06-26 14:00:20', NULL),
(469, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Mkt - 3D Camera Module', 'code', 'Marketing 3D Camerea Module Items', 0, 0, '2014-06-26 14:00:20', NULL),
(470, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Mkt - Converter Box', 'code', 'Marketing only converter box items', 0, 0, '2014-06-26 14:00:20', NULL),
(471, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Converter Box', 'code', 'All Converter Box 142, 142H, 142HC, 142H4 Products', 0, 0, '2014-06-26 14:00:20', NULL),
(472, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'MCNEX', 'code', '3D Camera with Samsung dual 5MP sensor on MV0150 board', 0, 0, '2014-06-26 14:00:20', NULL),
(473, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'ViewLinkTech', 'code', 'Ticket database for ViewLinkTech projects', 0, 0, '2014-06-26 14:00:20', NULL),
(474, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Boards and PCBs', 'code', 'This group relates to all activity in relation to the design, assembly test and repair of PCBs', 0, 0, '2014-06-26 14:00:20', NULL),
(475, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Lab Maintenance', 'code', 'All issues related to the upkeep of an efficient and productive laboratory environment in Movidius.', 0, 0, '2014-06-26 14:00:20', NULL),
(476, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, '3Dcamera Module', 'code', '3D camera module - ISP pipeline, MV0153, PC application', 0, 0, '2014-06-26 14:00:20', NULL),
(477, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'SW Best Practice', 'code', 'All best practice and guidelines to be followed towards achieving production grade quality', 0, 0, '2014-06-26 14:00:20', NULL),
(478, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'OMAP3', 'code', 'Common Software running on MV0122 OMAP3 board', 0, 0, '2014-06-26 14:00:20', NULL),
(479, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Fragrak', 'code', 'Movidius Generation 3 development', 0, 0, '2014-06-26 14:00:20', NULL),
(480, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, '3D', 'code', 'All 3D related issues', 0, 0, '2014-06-26 14:00:20', NULL),
(481, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'mp_sabre', 'code', 'Mass production Sabre', 0, 0, '2014-06-26 14:00:20', NULL),
(482, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'ISDB-T', 'code', 'ISDB-t (mobile tv for japanese market)', 0, 0, '2014-06-26 14:00:20', NULL),
(483, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'CoreDrivers', 'code', 'Sabre Drivers (typically written on leon)', 0, 0, '2014-06-26 14:00:20', NULL),
(484, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Video Edit', 'code', 'Video Edit', 0, 0, '2014-06-26 14:00:20', NULL),
(485, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Host', 'code', 'All host software for 6410', 0, 0, '2014-06-26 14:00:20', NULL),
(486, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'CoreSW', 'code', 'Core software including codecs, effects pipelines', 0, 0, '2014-06-26 14:00:20', NULL),
(487, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'moviTools', 'code', 'Development Tools for Movidius Processors', 0, 0, '2014-06-26 14:00:20', NULL),
(488, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'ESR', 'code', 'Customer, Sales and Marketing Originated Engineering Support Requests', 0, 0, '2014-06-26 14:00:20', NULL),
(489, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'MVMON', 'code', 'Movidia''s Grmon replacement', 0, 0, '2014-06-26 14:00:20', NULL),
(490, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'IT_Admin', 'code', 'IT Administration Bugs/Issues', 0, 0, '2014-06-26 14:00:20', NULL),
(491, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'ISAACSim', 'code', 'ISAAC Chip Shave Simulator', 0, 0, '2014-06-26 14:00:20', NULL),
(492, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'Sabre', 'code', 'Sabre project development', 0, 0, '2014-06-26 14:00:20', NULL),
(493, 2836, 1959, 3948, 1959, 1958, 1970, 1969, 1963, NULL, NULL, 'ISAAC', 'code', 'ISAAC Asic development bug tracker', 0, 0, '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_category`
--

CREATE TABLE IF NOT EXISTS `project_category` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `project_category`
--

INSERT INTO `project_category` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1, 44, 'Architecture', '', '2013-01-04 11:46:41', NULL),
(2, 44, 'Planning', '', '2013-01-04 11:46:48', NULL),
(3, 44, 'Documentation', '', '2013-01-04 11:46:55', NULL),
(4, 44, 'Design', '', '2013-01-04 11:47:01', NULL),
(5, 44, 'Development', '', '2013-01-04 11:47:07', NULL),
(6, 44, 'Issues', '', '2013-01-04 11:47:12', NULL),
(7, 47, 'Production', 'Projects that are currently in production', '2013-01-05 22:19:55', NULL),
(8, 55, 'Design', 'Tot ceea ce tine de design.', '2013-01-21 20:19:39', NULL),
(9, 55, 'Backend', '', '2013-01-21 20:20:46', NULL),
(10, 63, 'cat 1', 'cat 1', '2013-02-15 14:17:32', NULL),
(11, 63, 'cat 2', 'cat 2', '2013-02-15 14:17:40', NULL),
(12, 75, 'Tecnología', 'Proyectos puramente tecnológicos', '2013-03-07 13:40:16', NULL),
(13, 106, 'Software Development', 'For software development projects', '2013-06-12 12:17:43', NULL),
(14, 142, 'RTU', 'Tout type de panne concernant RTU', '2013-08-29 21:11:58', NULL),
(15, 142, 'SCADA', '', '2013-08-29 21:12:12', NULL),
(16, 144, 'Internos', '', '2013-08-31 14:30:55', NULL),
(17, 144, 'Externos', '', '2013-08-31 14:31:03', NULL),
(19, 155, 'TCC', '', '2013-09-12 16:27:54', NULL),
(20, 96, 'flyer', '', '2013-10-06 23:05:46', NULL),
(21, 174, 'Android Apps', '', '2013-10-09 12:39:29', NULL),
(22, 177, 'Perso', '', '2013-10-14 15:58:41', NULL),
(23, 203, 'dev', '', '2013-10-23 00:33:08', NULL),
(24, 89, 'Klant projecten', '', '2013-11-15 14:38:05', NULL),
(25, 89, 'Eigen projecten', '', '2013-11-15 14:38:12', NULL),
(27, 73, 'CASA', '', '2013-11-27 15:10:08', '2013-11-27 15:47:03'),
(28, 73, 'Fail', '', '2013-11-27 15:11:12', NULL),
(29, 73, 'HALA', '', '2013-11-27 15:19:06', '2013-11-27 15:47:30'),
(31, 73, 'AVIZE', '', '2013-11-27 15:19:44', NULL),
(32, 73, 'AMENAJARE', '', '2013-11-27 15:48:06', NULL),
(33, 1702, 'CASA', 'Locuinta unifamiliala', '2013-11-27 15:58:48', '2013-11-27 15:58:58'),
(34, 1702, 'HALA', 'Stucturi metalice din profile usoare', '2013-11-27 15:59:19', NULL),
(35, 1703, 'Artwork', '', '2013-11-27 18:50:44', NULL),
(36, 1703, 'Design Request', '', '2013-11-27 18:51:02', NULL),
(37, 1705, 'teste', 'teste', '2013-12-06 01:50:42', NULL),
(38, 1788, 'Development', 'This is the category for feature and project development.', '2014-02-14 11:22:13', NULL),
(39, 1797, 'Magazin online', '', '2014-02-25 09:42:48', NULL),
(40, 1919, 'Website', 'All of these projects are web sites that provide some sort of function.', '2014-03-15 01:08:08', NULL),
(41, 1952, 'Ecommerce Magento', '', '2014-05-30 14:10:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_component`
--

CREATE TABLE IF NOT EXISTS `project_component` (
`id` bigint(20) unsigned NOT NULL,
  `leader_id` bigint(20) unsigned DEFAULT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1337 ;

--
-- Dumping data for table `project_component`
--

INSERT INTO `project_component` (`id`, `leader_id`, `project_id`, `parent_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1066, 2836, 458, NULL, 'MMS', 'MMS Default Component', '2014-06-26 14:00:20', NULL),
(1067, 2836, 464, NULL, 'SDK-RTEMS', 'RTEMS Operating System', '2014-06-26 14:00:20', NULL),
(1068, 2836, 459, NULL, 'Bayer ISP application', 'Main loop, pre-proceser/mipi driver interface, IPIPE API', '2014-06-26 14:00:20', NULL),
(1069, 2836, 464, NULL, 'SIPP', 'Separate category to track SIPP items', '2014-06-26 14:00:20', NULL),
(1070, 2836, 459, NULL, 'Bayer ISP HW Models', 'C code for Bayer sipp accelerator blocks', '2014-06-26 14:00:20', NULL),
(1071, 2836, 459, NULL, 'Bayer ISP SW Kernels', 'Software C or asm kernel required for Bayer pipeline', '2014-06-26 14:00:20', NULL),
(1072, 2836, 459, NULL, 'Bayer ISP SIPP C', 'C code SIPP model for Bayer ISP', '2014-06-26 14:00:20', NULL),
(1073, 2836, 459, NULL, 'Bayer ISP Model', 'Octave model for Bayer pipeline', '2014-06-26 14:00:20', NULL),
(1074, 2836, 479, NULL, 'ROM', 'Issues related to Fragrak ROM Image', '2014-06-26 14:00:20', NULL),
(1075, 2836, 460, NULL, 'General', 'General placeholder', '2014-06-26 14:00:20', NULL),
(1076, 2836, 461, NULL, 'General', 'General software issues pre silicon', '2014-06-26 14:00:20', NULL),
(1077, 2836, 462, NULL, 'System', 'Suggestions/requests for system wide changes: busses, memories, connections between them etc.', '2014-06-26 14:00:20', NULL),
(1078, 2836, 462, NULL, 'SHAVE', 'Suggestions/requests for SHAVE features', '2014-06-26 14:00:20', NULL),
(1079, 2836, 462, NULL, 'Peripherals', 'General ideas about peripheral improvements', '2014-06-26 14:00:20', NULL),
(1080, 2836, 479, NULL, 'CMX', 'CMX memory', '2014-06-26 14:00:20', NULL),
(1081, 2836, 479, NULL, 'DDR', 'DDR subsystem, controller IP etc.', '2014-06-26 14:00:20', NULL),
(1082, 2836, 487, NULL, 'gcc Toolchain', 'All issues related to the sparc-elf-xx toolchain.\r\nThis includes the GNU compiler and linker', '2014-06-26 14:00:20', NULL),
(1083, 2836, 479, NULL, 'MSS', 'Media sub-system', '2014-06-26 14:00:20', NULL),
(1084, 2836, 479, NULL, 'Documentation', 'Any issues relating to fragrak documentation', '2014-06-26 14:00:20', NULL),
(1085, 2836, 463, NULL, 'SW - Kernels', 'Kernels and Shave code', '2014-06-26 14:00:20', NULL),
(1086, 2836, 463, NULL, 'SW - Leon', 'Leon contol code', '2014-06-26 14:00:20', NULL),
(1087, 2836, 463, NULL, 'SW - Reference', 'Reference pipeline', '2014-06-26 14:00:20', NULL),
(1088, 2836, 479, NULL, 'FPGA', 'Issues with the Fragrak FPGA environment', '2014-06-26 14:00:20', NULL),
(1089, 2836, 465, NULL, 'Smonut', 'Smonut - software running on any of smores or peanut board', '2014-06-26 14:00:20', NULL),
(1090, 2836, 465, NULL, 'Peanut', 'Peanut platform', '2014-06-26 14:00:20', NULL),
(1091, 2836, 465, NULL, 'Smores', 'Smores platform', '2014-06-26 14:00:20', NULL),
(1092, 2836, 464, NULL, 'SDK-Support: General', 'Support items in general or support for customers not requiring massive support time/resources allocated.', '2014-06-26 14:00:20', NULL),
(1093, 2836, 464, NULL, 'SDK-Support: Vuzix', 'Support items for Vuzix Corp.', '2014-06-26 14:00:20', NULL),
(1094, 2836, 464, NULL, 'MvCv', 'MvCv Kernels, Testing and Documentation', '2014-06-26 14:00:20', NULL),
(1095, 2836, 464, NULL, 'SDK Components', 'Component on SDK components', '2014-06-26 14:00:20', NULL),
(1096, 2836, 465, NULL, 'Twizzler', 'Tickets related to Twizzler platform', '2014-06-26 14:00:20', NULL),
(1097, 2836, 465, NULL, 'MV174 Dragonboard support', 'Anything to do with drivers on the dragonboard', '2014-06-26 14:00:20', NULL),
(1098, 2836, 465, NULL, 'vtrack - Vpipe', 'Vision pipeline on myriad', '2014-06-26 14:00:20', NULL),
(1099, 2836, 479, NULL, 'NAL', 'H.264 Network Abstraction Layer accelerator', '2014-06-26 14:00:20', NULL),
(1100, 2836, 479, NULL, 'LEON4', 'Leon4 RISC processer and related components from Gaisler IP', '2014-06-26 14:00:20', NULL),
(1101, 2836, 479, NULL, 'SEBI', 'Slave external bus interface', '2014-06-26 14:00:20', NULL),
(1102, 2836, 479, NULL, 'Verification', 'Anything related to Fragrak verification', '2014-06-26 14:00:20', NULL),
(1103, 2836, 465, NULL, 'vcplat', 'Compal platform', '2014-06-26 14:00:20', NULL),
(1104, 2836, 465, NULL, 'vaplat', 'vision tracking platform', '2014-06-26 14:00:20', NULL),
(1105, 2836, 467, NULL, '153 control code', 'mv0153 code to interface to mv161', '2014-06-26 14:00:20', NULL),
(1106, 2836, 467, NULL, 'Test', 'array camera kernel & pipeline tests', '2014-06-26 14:00:20', NULL),
(1107, 2836, 464, NULL, 'SDK-Support: Mammoth', 'This component is for Mammoth support tickets.', '2014-06-26 14:00:20', NULL),
(1108, 2836, 479, NULL, 'SHAVE', 'The SHAVE processor', '2014-06-26 14:00:20', NULL),
(1109, 2836, 465, NULL, 'vTrack - Myriad', 'Myriad side of the vTrack project', '2014-06-26 14:00:20', NULL),
(1110, 2836, 464, NULL, 'SDK Feature Requests', 'Use for new feature/example app requests', '2014-06-26 14:00:20', NULL),
(1111, 2836, 479, NULL, 'CIF', 'Camera interface', '2014-06-26 14:00:20', NULL),
(1112, 2836, 479, NULL, 'LCD', 'LCD controller', '2014-06-26 14:00:20', NULL),
(1113, 2836, 479, NULL, 'AMC', 'Accelerator Memory Controller', '2014-06-26 14:00:20', NULL),
(1114, 2836, 479, NULL, 'SIPP', 'Streaming Image Processing Pipeline accelerators', '2014-06-26 14:00:20', NULL),
(1115, 2836, 479, NULL, 'MIPI', '2x 2-lane/1x 4-lane MIPI controller (including 2x DPHY and 1x DPHYPLL IPs)', '2014-06-26 14:00:20', NULL),
(1116, 2836, 464, NULL, 'SDK Tools', 'This is the entry point for any field support issues that come up around SDK.', '2014-06-26 14:00:20', NULL),
(1117, 2836, 465, NULL, 'FeatureFlow', 'FAST and FeatureFlow releated stuff', '2014-06-26 14:00:20', NULL),
(1118, 2836, 464, NULL, 'SDK Release Requests', 'On this component we track the requests for releases that are out of regular as opposed generic releases made at the end of month.', '2014-06-26 14:00:20', NULL),
(1119, 2836, 464, NULL, 'SDK Build system', 'Issues with the generic build flow / makefile system', '2014-06-26 14:00:20', NULL),
(1120, 2836, 476, NULL, 'New AWB', 'New AWB pipeline', '2014-06-26 14:00:20', NULL),
(1121, 2836, 476, NULL, 'Still ISP', 'Still image ISP pipeline', '2014-06-26 14:00:20', NULL),
(1122, 2836, 464, NULL, 'SDK Test Tasks', 'test tasks associated with SDK/MDK', '2014-06-26 14:00:20', NULL),
(1123, 2836, 466, NULL, 'USB', 'video class driver', '2014-06-26 14:00:20', NULL),
(1124, 2836, 466, NULL, 'Sensor Profiles', 'Create sensor profiles', '2014-06-26 14:00:20', NULL),
(1125, 2836, 471, NULL, 'CMX-only video pipeline', 'Generic video pipeline that uses only CMX', '2014-06-26 14:00:20', NULL),
(1126, 2836, 466, NULL, 'H264 Encode', 'H264 encoder on the 153.', '2014-06-26 14:00:20', NULL),
(1127, 2836, 464, NULL, 'SDK Docs', 'SDK documentation', '2014-06-26 14:00:20', NULL),
(1128, 2836, 464, NULL, 'SDK Automated Test/Release', 'Build/Test/Release scripts / mechanism', '2014-06-26 14:00:20', NULL),
(1129, 2836, 464, NULL, 'MDK Examples', 'Example applications using SDK', '2014-06-26 14:00:20', NULL),
(1130, 2836, 464, NULL, 'SDK Drivers', 'Low and mid level drivers', '2014-06-26 14:00:20', NULL),
(1131, 2836, 471, NULL, '142R10', 'issues on mv142 R10 board', '2014-06-26 14:00:20', NULL),
(1132, 2836, 466, NULL, 'AAC encode / decode', 'Port AAC encode and decode to MV0153. \r\nAudio over USB', '2014-06-26 14:00:20', NULL),
(1133, 2836, 466, NULL, 'MPO', 'port 3Di application to MV0153', '2014-06-26 14:00:20', NULL),
(1134, 2836, 466, NULL, 'JPEG encode/decode', 'port JPEG codec to Mv0153', '2014-06-26 14:00:20', NULL),
(1135, 2836, 465, NULL, 'Dataset', 'Gesture recognition dataset', '2014-06-26 14:00:20', NULL),
(1136, 2836, 465, NULL, 'OpenCV Reference code', 'Algorithm development -- OpenCV reference code compiled on PC', '2014-06-26 14:00:20', NULL),
(1137, 2836, 466, NULL, 'SkypeUSBVideoCam', 'App to stream from MV121 sensor board with USB video class driver. MV0153 gets detected as a video class device', '2014-06-26 14:00:20', NULL),
(1138, 2836, 466, NULL, 'VideoEffectsHDMI', 'apply simple effects to the video stream and output to hdmi (aka Palomar)', '2014-06-26 14:00:20', NULL),
(1139, 2836, 466, NULL, 'Rectification', '3D rectification on video stream (aka Telene)', '2014-06-26 14:00:20', NULL),
(1140, 2836, 467, NULL, 'Asm Block', 'Assembler Block implementation', '2014-06-26 14:00:20', NULL),
(1141, 2836, 467, NULL, 'Reference Code', 'Referenc code for imaging pipeline', '2014-06-26 14:00:20', NULL),
(1142, 2836, 467, NULL, 'Framework', 'Pipeline framework component', '2014-06-26 14:00:20', NULL),
(1143, 2836, 471, NULL, 'PanStudio', 'PanStudio related issues', '2014-06-26 14:00:20', NULL),
(1144, 2836, 471, NULL, 'CCS', 'CCS', '2014-06-26 14:00:20', NULL),
(1145, 2836, 487, NULL, 'moviEclipse', 'Eclipse plugins for Movidius Tools', '2014-06-26 14:00:20', NULL),
(1146, 2836, 471, NULL, 'BIST', 'mv151 Built In Self Test functionality', '2014-06-26 14:00:20', NULL),
(1147, 2836, 476, NULL, 'Hardware', 'Issues with 155 or 153 hardware systems', '2014-06-26 14:00:20', NULL),
(1148, 2836, 471, NULL, 'CEC', 'Consumer Electronic Control (CEC) feature', '2014-06-26 14:00:20', NULL),
(1149, 2836, 471, NULL, 'USB Update', 'Anything about USB update', '2014-06-26 14:00:20', NULL),
(1150, 2836, 471, NULL, 'New ITE drv', 'issues related to the new ITE driver', '2014-06-26 14:00:20', NULL),
(1151, 2836, 468, NULL, 'Integration in 3Dcamcorder on 117', 'Add to 117 and integrate into 3DCamcorder application', '2014-06-26 14:00:20', NULL),
(1152, 2836, 473, NULL, 'HW -- VLT Wolf Form Factor Board', 'Any issues with the Wolf Form factor hardware', '2014-06-26 14:00:20', NULL),
(1153, 2836, 469, NULL, 'Marketing Issues', '3D Camera Module items', '2014-06-26 14:00:20', NULL),
(1154, 2836, 470, NULL, 'Marketing Issues', 'All issues', '2014-06-26 14:00:20', NULL),
(1155, 2836, 471, NULL, 'Automated Test', 'Automated test project', '2014-06-26 14:00:20', NULL),
(1156, 2836, 476, NULL, 'MV0155', 'Camera module with 2x Toshiba 8MP EDOF sensors', '2014-06-26 14:00:20', NULL),
(1157, 2836, 471, NULL, 'Test Tasks', 'Tasks around testing such as update of test spec, test plan, other tests than the usual test plan coming from HW, marketing teams or as interactions with the customers', '2014-06-26 14:00:20', NULL),
(1158, 2836, 476, NULL, 'MV0153 control code', 'control application for MV0153 board', '2014-06-26 14:00:20', NULL),
(1159, 2836, 476, NULL, '3D PC application', 'Application to drive the MV0153 and MV0155 3Dcamera module boards over USB', '2014-06-26 14:00:20', NULL),
(1160, 2836, 476, NULL, 'MPO file creator', 'Create mpo format file from stereo images', '2014-06-26 14:00:20', NULL),
(1161, 2836, 476, NULL, 'MV0153 USB Driver', 'USB video class driver & Extension Unit', '2014-06-26 14:00:20', NULL),
(1162, 2836, 476, NULL, 'MV0153 Camera link driver', 'Driver to talk to Myriad on camera module to configure sensors', '2014-06-26 14:00:20', NULL),
(1163, 2836, 476, NULL, 'MV0153 HDMI Driver for ITE TX chip', 'Driver for ITE TX chip', '2014-06-26 14:00:20', NULL),
(1164, 2836, 471, NULL, 'Marketing Issues', 'Marketing issues, customer bug reports', '2014-06-26 14:00:20', NULL),
(1165, 2836, 471, NULL, 'Analog', 'component and composite related issues', '2014-06-26 14:00:20', NULL),
(1166, 2836, 471, NULL, 'SM/OSD/menu/LED', 'State machine, LEDs, Menu, OSD, Modes etc', '2014-06-26 14:00:20', NULL),
(1167, 2836, 471, NULL, 'Change Requests', 'the place where change requests are being tracked', '2014-06-26 14:00:20', NULL),
(1168, 2836, 471, NULL, 'Audio', 'Anything audio really', '2014-06-26 14:00:20', NULL),
(1169, 2836, 471, NULL, 'Requirements & Spec', 'Spec or requirements/MRS related issues', '2014-06-26 14:00:20', NULL),
(1170, 2836, 471, NULL, '2D->3D', '2D to 3D related issues', '2014-06-26 14:00:20', NULL),
(1171, 2836, 471, NULL, 'EDID', 'EDID related issues', '2014-06-26 14:00:20', NULL),
(1172, 2836, 471, NULL, 'VEP, Effects', 'VEP and anaglyph effects', '2014-06-26 14:00:20', NULL),
(1173, 2836, 471, NULL, '142H4 Specific', 'Issues to specific 142H4 components (ITE Rx Mux, etc..)', '2014-06-26 14:00:20', NULL),
(1174, 2836, 471, NULL, 'ITE Tx', 'ITE Tx driver', '2014-06-26 14:00:20', NULL),
(1175, 2836, 471, NULL, 'ITE Rx', 'ITE Rx Driver', '2014-06-26 14:00:20', NULL),
(1176, 2836, 476, NULL, 'ISP Algorithms / Pipeline', 'e.g. AWB, AE, AF, Stabilization, zoom', '2014-06-26 14:00:20', NULL),
(1177, 2836, 476, NULL, '3D Algorithms / Pipeline', '3D auto-convergence, Matching, Color Rectification, Vertical Rectification etc', '2014-06-26 14:00:20', NULL),
(1178, 2836, 473, NULL, 'Host - Browser Use-case', 'Tickets for host side browser use case', '2014-06-26 14:00:20', NULL),
(1179, 2836, 472, NULL, 'Samsung Sensor Driver', 'Diriver integration with 3D component', '2014-06-26 14:00:20', NULL),
(1180, 2836, 472, NULL, '3D Integration', 'Integrate Samsung dual sensor with 3D engine', '2014-06-26 14:00:20', NULL),
(1181, 2836, 476, NULL, 'Sensor Driver', 'Leon driver code for sensors', '2014-06-26 14:00:20', NULL),
(1182, 2836, 476, NULL, 'Sensor Calibration', 'For tracking tasks/issues related to sensor calibration', '2014-06-26 14:00:20', NULL),
(1183, 2836, 473, NULL, 'Leon - TS Demux', 'Demux audio & video transport stream', '2014-06-26 14:00:20', NULL),
(1184, 2836, 473, NULL, 'Leon - Application', 'Decide encoder or decode to execute', '2014-06-26 14:00:20', NULL),
(1185, 2836, 473, NULL, 'Sabre - h264 Decoder', 'H264 video decode', '2014-06-26 14:00:20', NULL),
(1186, 2836, 473, NULL, 'HW - pico projecter', '3M MM200 picp projector Display', '2014-06-26 14:00:20', NULL),
(1187, 2836, 473, NULL, 'Leon - TS Mux', 'TS mux task', '2014-06-26 14:00:20', NULL),
(1188, 2836, 473, NULL, 'Sabre - Audio Driver', 'for Wolfson chip integration', '2014-06-26 14:00:20', NULL),
(1189, 2836, 473, NULL, 'HW - ViewlinkTech daughter card', 'MV0149 http://timi60/index.php/Mv0149_Viewlinktech_Mono', '2014-06-26 14:00:20', NULL),
(1190, 2836, 473, NULL, 'Sabre - LCD Driver', 'Syndiant LCD driver', '2014-06-26 14:00:20', NULL),
(1191, 2836, 473, NULL, 'Task', 'general ticket for task tracking on ViewlinkTech project', '2014-06-26 14:00:20', NULL),
(1192, 2836, 473, NULL, 'Sabre - Camera Driver', 'Camera driver on Sabre', '2014-06-26 14:00:20', NULL),
(1193, 2836, 473, NULL, 'Sabre - USB Driver', 'Sabre side USB issues', '2014-06-26 14:00:20', NULL),
(1194, 2836, 473, NULL, 'Sabre - h264 Encoder', 'h264 encoder', '2014-06-26 14:00:20', NULL),
(1195, 2836, 473, NULL, 'Host Application', 'Host side issues', '2014-06-26 14:00:20', NULL),
(1196, 2836, 474, NULL, 'Board Bringup', 'Tasks related to the bringup of new board designs.', '2014-06-26 14:00:20', NULL),
(1197, 2836, 474, NULL, 'Board Delivery', 'All issues around the delivery of boards to SW, Customers etc.', '2014-06-26 14:00:20', NULL),
(1198, 2836, 487, NULL, 'moviCompile', 'Movidius SHAVE Compiler', '2014-06-26 14:00:20', NULL),
(1199, 2836, 487, NULL, 'moviConvert', 'Movidius Object Converter', '2014-06-26 14:00:20', NULL),
(1200, 2836, 474, NULL, 'Component Issues', 'Any issues relating to the sourcing and supply of components', '2014-06-26 14:00:20', NULL),
(1201, 2836, 474, NULL, 'Board Repair', 'All board faults found should be reported in this ticket', '2014-06-26 14:00:20', NULL),
(1202, 2836, 475, NULL, 'Equipment', 'Any issues related to Lab equipment', '2014-06-26 14:00:20', NULL),
(1203, 2836, 476, NULL, 'Frakrak ISP', 'ISP pipeline implementation for Frakrak', '2014-06-26 14:00:20', NULL),
(1204, 2836, 476, NULL, 'Fragrak reference pipeline', 'C reference implementation of ISP pipeline targetting Fragrak', '2014-06-26 14:00:20', NULL),
(1205, 2836, 476, NULL, 'Sabre ISP', 'ISP pipeline targetting Sabre', '2014-06-26 14:00:20', NULL),
(1206, 2836, 476, NULL, 'Sabre reference pipeline', 'Octave and C reference implementations for Sabre ISP pipeline', '2014-06-26 14:00:20', NULL),
(1207, 2836, 486, NULL, 'EDEN', 'Improved denoise effect', '2014-06-26 14:00:20', NULL),
(1208, 2836, 477, NULL, 'Apps Debate', 'Place for apps general debate', '2014-06-26 14:00:20', NULL),
(1209, 2836, 477, NULL, 'Apps Code Re-use', 'Best practice on apps code re-use', '2014-06-26 14:00:20', NULL),
(1210, 2836, 477, NULL, 'Apps Development', 'Best practice for apps development', '2014-06-26 14:00:20', NULL),
(1211, 2836, 477, NULL, 'Apps Testing', 'Best practice for apps testing', '2014-06-26 14:00:20', NULL),
(1212, 2836, 477, NULL, 'Apps Release Process', 'Best practice for releases', '2014-06-26 14:00:20', NULL),
(1213, 2836, 477, NULL, 'Apps Build Framework', 'Best practice and guidelines for build', '2014-06-26 14:00:20', NULL),
(1214, 2836, 477, NULL, 'Apps Induction/Training', 'Basic Movidius apps induction and training that a new hire should get.', '2014-06-26 14:00:20', NULL),
(1215, 2836, 479, NULL, 'Analysis Flow', 'Parse scripts and Makefiles used for Fragrak architectural analysis', '2014-06-26 14:00:20', NULL),
(1216, 2836, 481, NULL, 'mp_sabre_bringup', 'Any issue related to the initial bringup and validation of mp_sabre devices. Genuine problems identified in this area will be subsequently moved to "mp_sabre_silicon"', '2014-06-26 14:00:20', NULL),
(1217, 2836, 478, NULL, 'Media', 'Gstreamer, gstreamer plugins and TI DSP issues', '2014-06-26 14:00:20', NULL),
(1218, 2836, 483, NULL, 'Analog Video-In', 'Analog video in driver', '2014-06-26 14:00:20', NULL),
(1219, 2836, 481, NULL, 'mp_sabre_silicon', 'Any issues found with the actual mp_sabre device.', '2014-06-26 14:00:20', NULL),
(1220, 2836, 478, NULL, 'SabreLink', 'SabreLink OMAP3 issues', '2014-06-26 14:00:20', NULL),
(1221, 2836, 478, NULL, 'Android2.x', 'Android OS issues', '2014-06-26 14:00:20', NULL),
(1222, 2836, 478, NULL, 'Drivers', 'Drivers for MV0122 OMAP3 board - e.g. LCD, I2S, Camera.  Also for Linux Kernel issues', '2014-06-26 14:00:20', NULL),
(1223, 2836, 480, NULL, 'Converter', 'converter/mv118/mv131/mv133/mv142 projects\r\n\r\nformerly known as Buffalo', '2014-06-26 14:00:20', NULL),
(1224, 2836, 486, NULL, 'Drivers', 'Drivers reviewed and adopted into the applications framework', '2014-06-26 14:00:20', NULL),
(1225, 2836, 487, NULL, 'moviSim', 'Movidius Simulator', '2014-06-26 14:00:20', NULL),
(1226, 2836, 480, NULL, '3D Anaglyph', 'Anaglyph hdmi in/out products', '2014-06-26 14:00:20', NULL),
(1227, 2836, 480, NULL, '3Deffects', 'New features/effects added to the 3D applications', '2014-06-26 14:00:20', NULL),
(1228, 2836, 480, NULL, '3Dgui', 'bugs on 3D GUI', '2014-06-26 14:00:20', NULL),
(1229, 2836, 481, NULL, 'mp_sabre_doc', 'Any documentation issues with mp_sabre', '2014-06-26 14:00:20', NULL),
(1230, 2836, 479, NULL, 'Feature', 'Fragrak feature request', '2014-06-26 14:00:20', NULL),
(1231, 2836, 480, NULL, 'MyVu', '3D software for MyVu project', '2014-06-26 14:00:20', NULL),
(1232, 2836, 481, NULL, 'mp_sabre_rom', 'Any issues relating to the implementation and testing of the mp_sabre_rom', '2014-06-26 14:00:20', NULL),
(1233, 2836, 481, NULL, 'mp_sabre Netlist ECO', 'Any issues which need to be fixed via ECO', '2014-06-26 14:00:20', NULL),
(1234, 2836, 484, NULL, 'VE Host - NEW', 'Non-gstreamer VE Host', '2014-06-26 14:00:20', NULL),
(1235, 2836, 480, NULL, '3D Host', 'Host related 3D specific issues', '2014-06-26 14:00:20', NULL),
(1236, 2836, 480, NULL, '3D Camcorder', '3D Video Capture', '2014-06-26 14:00:20', NULL),
(1237, 2836, 480, NULL, '3DPlayer', '3D Video Player (includes 2D-->3D)', '2014-06-26 14:00:20', NULL),
(1238, 2836, 480, NULL, '3Di', '3D Imaging project', '2014-06-26 14:00:20', NULL),
(1239, 2836, 483, NULL, 'USB', 'USB driver', '2014-06-26 14:00:20', NULL),
(1240, 2836, 492, NULL, 'Bringup Chips', 'Issues or faults with specific devices that is not suspected to be design related', '2014-06-26 14:00:20', NULL),
(1241, 2836, 492, NULL, 'Bringup Boards', 'Any issue relating to the development of boards, faults with boards etc.', '2014-06-26 14:00:20', NULL),
(1242, 2836, 484, NULL, 'VE SABRE', 'Video Edit SABRE', '2014-06-26 14:00:20', NULL),
(1243, 2836, 484, NULL, 'VE Host', 'Video Edit Host', '2014-06-26 14:00:20', NULL),
(1244, 2836, 486, NULL, 'VE App', 'Video Edit Application', '2014-06-26 14:00:20', NULL),
(1245, 2836, 486, NULL, '3D', 'Complete 3D program', '2014-06-26 14:00:20', NULL),
(1246, 2836, 486, NULL, 'Camcorder', 'Camcorder application', '2014-06-26 14:00:20', NULL),
(1247, 2836, 486, NULL, 'QEP -ISDBT-1Seg', 'Quality Enhancement Pipeline', '2014-06-26 14:00:20', NULL),
(1248, 2836, 486, NULL, 'VEP', 'Video Edit Pipeline', '2014-06-26 14:00:20', NULL),
(1249, 2836, 490, NULL, 'Timi Linux', 'Timi Linux machines', '2014-06-26 14:00:20', NULL),
(1250, 2836, 486, NULL, 'MP3 Encode', 'MP3 Encode', '2014-06-26 14:00:20', NULL),
(1251, 2836, 486, NULL, 'MP3 Decode', 'MP3 Decoder', '2014-06-26 14:00:20', NULL),
(1252, 2836, 486, NULL, 'AAC Encode', 'AAC encoder all variants (AAC+/LC/HE)', '2014-06-26 14:00:20', NULL),
(1253, 2836, 485, NULL, 'SABRELink', 'Sabre Link', '2014-06-26 14:00:20', NULL),
(1254, 2836, 485, NULL, 'VEEngine', 'Video-edit engine', '2014-06-26 14:00:20', NULL),
(1255, 2836, 485, NULL, 'VELib', 'Video-edit library', '2014-06-26 14:00:20', NULL),
(1256, 2836, 481, NULL, 'SOC RTL', 'Top Level RTL', '2014-06-26 14:00:20', NULL),
(1257, 2836, 482, NULL, 'docs', 'Documentation', '2014-06-26 14:00:20', NULL),
(1258, 2836, 482, NULL, 'pc-ref', 'reference PC application', '2014-06-26 14:00:20', NULL),
(1259, 2836, 483, NULL, 'camera', 'Camera input interface', '2014-06-26 14:00:20', NULL),
(1260, 2836, 483, NULL, 'Sabrelink', 'Sabre <--> Host data link', '2014-06-26 14:00:20', NULL),
(1261, 2836, 483, NULL, 'lcd', 'LCD driver', '2014-06-26 14:00:20', NULL),
(1262, 2836, 483, NULL, 'i2s (master)', 'Audio output driver', '2014-06-26 14:00:20', NULL),
(1263, 2836, 484, NULL, 'test', 'Video Edit test framework', '2014-06-26 14:00:20', NULL),
(1264, 2836, 484, NULL, 'Leon', 'Leon code issues (non-driver)', '2014-06-26 14:00:20', NULL),
(1265, 2836, 484, NULL, 'docs', 'Video edit documentation', '2014-06-26 14:00:20', NULL),
(1266, 2836, 485, NULL, 'Android', 'Android operating system specific issues', '2014-06-26 14:00:20', NULL),
(1267, 2836, 485, NULL, 'VE Application', 'Host specific VE application issues', '2014-06-26 14:00:20', NULL),
(1268, 2836, 485, NULL, 'Drivers', 'Host drivers', '2014-06-26 14:00:20', NULL),
(1269, 2836, 485, NULL, 'MMF', 'Multimedia framework (gstreamer etc)', '2014-06-26 14:00:20', NULL),
(1270, 2836, 486, NULL, 'JPEG', 'Jpeg encoder/decoder', '2014-06-26 14:00:20', NULL),
(1271, 2836, 486, NULL, 'VEL (video effects library)', 'Video Effects Library', '2014-06-26 14:00:20', NULL),
(1272, 2836, 486, NULL, 'AAC Decode', 'AAC decoder all variants (AAC+/LC/HE)', '2014-06-26 14:00:20', NULL),
(1273, 2836, 486, NULL, 'H264 Encode', 'h.264 Encoder', '2014-06-26 14:00:20', NULL),
(1274, 2836, 486, NULL, 'H264 Decode', 'h.264 decoder', '2014-06-26 14:00:20', NULL),
(1275, 2836, 492, NULL, 'Bringup', 'Any issue found during bringup, FPGA, Silicon, or Doc', '2014-06-26 14:00:20', NULL),
(1276, 2836, 487, NULL, 'Other', 'Sabre Tools Other', '2014-06-26 14:00:20', NULL),
(1277, 2836, 487, NULL, 'moviTools Documentation', 'Movidius Tools Documentation', '2014-06-26 14:00:20', NULL),
(1278, 2836, 487, NULL, 'moviDevelop', 'Movidius IDE (obsolete - replaced by moviEclipse)', '2014-06-26 14:00:20', NULL),
(1279, 2836, 487, NULL, 'VectorC', 'Sabre SVE VectorC compiler (obsolete - replaced by moviCompile)', '2014-06-26 14:00:20', NULL),
(1280, 2836, 487, NULL, 'moviProfile', 'Movidius Profiler', '2014-06-26 14:00:20', NULL),
(1281, 2836, 487, NULL, 'moviTest', 'Movidius Test Tool', '2014-06-26 14:00:20', NULL),
(1282, 2836, 487, NULL, 'moviLink', 'Movidius Linker', '2014-06-26 14:00:20', NULL),
(1283, 2836, 487, NULL, 'moviDump', 'Movidius Object Dumper', '2014-06-26 14:00:20', NULL),
(1284, 2836, 487, NULL, 'moviAsm', 'Movidius Assembler', '2014-06-26 14:00:20', NULL),
(1285, 2836, 487, NULL, 'moviDebug', 'Movidius Debugger', '2014-06-26 14:00:20', NULL),
(1286, 2836, 487, NULL, 'SabreSim', 'Sabre Simulator (obsolete - replaced by moviSim)', '2014-06-26 14:00:20', NULL),
(1287, 2836, 492, NULL, 'Sabre Netlist ECO', 'Sabre Netlist ECO', '2014-06-26 14:00:20', NULL),
(1288, 2836, 492, NULL, 'ROM Other', 'ROM General', '2014-06-26 14:00:20', NULL),
(1289, 2836, 492, NULL, 'ROM Boot', 'ROM Boot', '2014-06-26 14:00:20', NULL),
(1290, 2836, 488, NULL, 'Product Idea', 'New feature, product or platform suggestion', '2014-06-26 14:00:20', NULL),
(1291, 2836, 488, NULL, 'unspecified', 'unspecified', '2014-06-26 14:00:20', NULL),
(1292, 2836, 488, NULL, 'MA1101', 'MA1101 ISDB-T Full Seg MPEG2 1080i + Video Editing', '2014-06-26 14:00:20', NULL),
(1293, 2836, 488, NULL, 'MA1110', 'MA1110 Full Spec UGC Video Editing', '2014-06-26 14:00:20', NULL),
(1294, 2836, 488, NULL, 'MA1801', 'MA1801 DVB-T SD->QVGA Codec', '2014-06-26 14:00:20', NULL),
(1295, 2836, 492, NULL, 'SVU SystemC', 'SVU SystemC used in RTL verification', '2014-06-26 14:00:20', NULL),
(1296, 2836, 493, NULL, 'UGCDemoSW', 'UGC Software Demo development', '2014-06-26 14:00:20', NULL),
(1297, 2836, 489, NULL, 'Software', 'General Software issues', '2014-06-26 14:00:20', NULL),
(1298, 2836, 490, NULL, 'Dublin Laptops', 'as', '2014-06-26 14:00:20', NULL),
(1299, 2836, 490, NULL, 'Dublin Linux Servers', 'sd', '2014-06-26 14:00:20', NULL),
(1300, 2836, 491, NULL, 'Shave', 'Shave SystemC model', '2014-06-26 14:00:20', NULL),
(1301, 2836, 492, NULL, 'Other', 'Any bug that has no obvious place in another category', '2014-06-26 14:00:20', NULL),
(1302, 2836, 492, NULL, 'DOC', 'General ISAAC documentation bugs', '2014-06-26 14:00:20', NULL),
(1303, 2836, 492, NULL, 'SOC RTL', 'System on chip RTL (non-Shave RTL)', '2014-06-26 14:00:20', NULL),
(1304, 2836, 492, NULL, 'SVU RTL', 'Shave processor RTL', '2014-06-26 14:00:20', NULL),
(1305, 2836, 492, NULL, 'ASM', 'Assembler and dis-assembler', '2014-06-26 14:00:20', NULL),
(1306, 2836, 492, NULL, 'Sabresim', 'Sabresim (Sabre Instruction set simulator)', '2014-06-26 14:00:20', NULL),
(1307, 2836, 493, NULL, 'SVGA', 'SVGA controller', '2014-06-26 14:00:20', NULL),
(1308, 2836, 493, NULL, 'I2S', 'I2S designware module', '2014-06-26 14:00:20', NULL),
(1309, 2836, 493, NULL, 'SVU', 'Scaler Vector Unit', '2014-06-26 14:00:20', NULL),
(1310, 2836, 493, NULL, 'BUS', 'AHB, APB bus infrastructure', '2014-06-26 14:00:20', NULL),
(1311, 2836, 493, NULL, 'WSSSI', 'WPP Slave SPI interface', '2014-06-26 14:00:20', NULL),
(1312, 2836, 493, NULL, 'WMSSI', 'WPP master SPI interface', '2014-06-26 14:00:20', NULL),
(1313, 2836, 493, NULL, 'CMU', 'Compare Unit', '2014-06-26 14:00:20', NULL),
(1314, 2836, 493, NULL, 'IAU', 'Integer arithmetic unit', '2014-06-26 14:00:20', NULL),
(1315, 2836, 493, NULL, 'Leon', 'Leon Processor and FPU', '2014-06-26 14:00:20', NULL),
(1316, 2836, 493, NULL, 'SAU', 'Scaler arithmetic unit', '2014-06-26 14:00:20', NULL),
(1317, 2836, 493, NULL, 'SRF_IRF', 'Scaler register file', '2014-06-26 14:00:20', NULL),
(1318, 2836, 493, NULL, 'VRF', 'Vector Register File', '2014-06-26 14:00:20', NULL),
(1319, 2836, 493, NULL, 'EBI', 'External Bus interface', '2014-06-26 14:00:20', NULL),
(1320, 2836, 493, NULL, 'SDIO', 'SDIO module', '2014-06-26 14:00:20', NULL),
(1321, 2836, 493, NULL, 'LSU', 'load Store Unit', '2014-06-26 14:00:20', NULL),
(1322, 2836, 493, NULL, 'VAU', 'Vector Arithmetic unit', '2014-06-26 14:00:20', NULL),
(1323, 2836, 493, NULL, 'SHAVE', 'Shave slice containing SVU and CMX', '2014-06-26 14:00:20', NULL),
(1324, 2836, 493, NULL, 'WPE', 'WPP preocessing Engine', '2014-06-26 14:00:20', NULL),
(1325, 2836, 493, NULL, 'ICB', 'Interrupt controller', '2014-06-26 14:00:20', NULL),
(1326, 2836, 493, NULL, 'GPIO', 'general Purpose IO', '2014-06-26 14:00:20', NULL),
(1327, 2836, 493, NULL, 'IIC', 'I2C block', '2014-06-26 14:00:20', NULL),
(1328, 2836, 493, NULL, 'LSSSI', 'Leon Slave SPI block', '2014-06-26 14:00:20', NULL),
(1329, 2836, 493, NULL, 'LMSSI', 'leon Master SPI block', '2014-06-26 14:00:20', NULL),
(1330, 2836, 493, NULL, 'ROM', 'leon ROM memory', '2014-06-26 14:00:20', NULL),
(1331, 2836, 493, NULL, 'RAM', 'leon RAM memory', '2014-06-26 14:00:20', NULL),
(1332, 2836, 493, NULL, 'UART', 'UART block', '2014-06-26 14:00:20', NULL),
(1333, 2836, 493, NULL, 'TIM', 'Timers block', '2014-06-26 14:00:20', NULL),
(1334, 2836, 493, NULL, 'CPR', 'Clock power and reset', '2014-06-26 14:00:20', NULL),
(1335, 2836, 493, NULL, 'ISAAC Top', 'Isaac top level and core', '2014-06-26 14:00:20', NULL),
(1336, 2836, 493, NULL, 'RTL bug', 'Direct problem with RTL code not operating within spec', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_role_data`
--

CREATE TABLE IF NOT EXISTS `project_role_data` (
`id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `permission_role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3534 ;

--
-- Dumping data for table `project_role_data`
--

INSERT INTO `project_role_data` (`id`, `project_id`, `permission_role_id`, `user_id`, `group_id`, `date_created`) VALUES
(3426, 458, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3427, 458, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3428, 458, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3429, 459, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3430, 459, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3431, 459, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3432, 460, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3433, 460, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3434, 460, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3435, 461, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3436, 461, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3437, 461, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3438, 462, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3439, 462, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3440, 462, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3441, 463, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3442, 463, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3443, 463, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3444, 464, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3445, 464, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3446, 464, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3447, 465, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3448, 465, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3449, 465, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3450, 466, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3451, 466, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3452, 466, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3453, 467, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3454, 467, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3455, 467, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3456, 468, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3457, 468, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3458, 468, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3459, 469, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3460, 469, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3461, 469, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3462, 470, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3463, 470, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3464, 470, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3465, 471, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3466, 471, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3467, 471, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3468, 472, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3469, 472, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3470, 472, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3471, 473, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3472, 473, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3473, 473, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3474, 474, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3475, 474, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3476, 474, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3477, 475, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3478, 475, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3479, 475, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3480, 476, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3481, 476, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3482, 476, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3483, 477, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3484, 477, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3485, 477, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3486, 478, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3487, 478, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3488, 478, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3489, 479, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3490, 479, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3491, 479, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3492, 480, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3493, 480, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3494, 480, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3495, 481, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3496, 481, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3497, 481, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3498, 482, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3499, 482, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3500, 482, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3501, 483, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3502, 483, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3503, 483, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3504, 484, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3505, 484, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3506, 484, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3507, 485, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3508, 485, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3509, 485, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3510, 486, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3511, 486, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3512, 486, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3513, 487, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3514, 487, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3515, 487, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3516, 488, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3517, 488, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3518, 488, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3519, 489, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3520, 489, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3521, 489, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3522, 490, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3523, 490, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3524, 490, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3525, 491, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3526, 491, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3527, 491, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3528, 492, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3529, 492, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3530, 492, 5889, NULL, 9544, '2014-06-26 14:00:20'),
(3531, 493, 5887, NULL, 9542, '2014-06-26 14:00:20'),
(3532, 493, 5888, NULL, 9543, '2014-06-26 14:00:20'),
(3533, 493, 5889, NULL, 9544, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `project_version`
--

CREATE TABLE IF NOT EXISTS `project_version` (
`id` bigint(20) unsigned NOT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=523 ;

--
-- Dumping data for table `project_version`
--

INSERT INTO `project_version` (`id`, `project_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(424, 458, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(425, 459, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(426, 460, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(427, 461, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(428, 462, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(429, 463, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(430, 464, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(431, 465, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(432, 466, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(433, 471, '1.xx.xx', NULL, '2014-06-26 14:00:20', NULL),
(434, 471, '00.01.94', NULL, '2014-06-26 14:00:20', NULL),
(435, 467, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(436, 471, '55.xx', NULL, '2014-06-26 14:00:20', NULL),
(437, 471, '54.xx', NULL, '2014-06-26 14:00:20', NULL),
(438, 471, '53.xx', NULL, '2014-06-26 14:00:20', NULL),
(439, 471, '52.xx', NULL, '2014-06-26 14:00:20', NULL),
(440, 471, '51.xx', NULL, '2014-06-26 14:00:20', NULL),
(441, 471, '50.xx', NULL, '2014-06-26 14:00:20', NULL),
(442, 471, '49.xx', NULL, '2014-06-26 14:00:20', NULL),
(443, 471, '48.xx', NULL, '2014-06-26 14:00:20', NULL),
(444, 471, '47.xx', NULL, '2014-06-26 14:00:20', NULL),
(445, 471, '46.xx', NULL, '2014-06-26 14:00:20', NULL),
(446, 468, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(447, 469, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(448, 470, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(449, 471, 'USB', NULL, '2014-06-26 14:00:20', NULL),
(450, 471, '45.xx', NULL, '2014-06-26 14:00:20', NULL),
(451, 471, '44.xx', NULL, '2014-06-26 14:00:20', NULL),
(452, 471, '43.xx', NULL, '2014-06-26 14:00:20', NULL),
(453, 471, '42.xx', NULL, '2014-06-26 14:00:20', NULL),
(454, 471, '41.xx', NULL, '2014-06-26 14:00:20', NULL),
(455, 471, '40.xx', NULL, '2014-06-26 14:00:20', NULL),
(456, 471, '39.xx', NULL, '2014-06-26 14:00:20', NULL),
(457, 471, '38.xx', NULL, '2014-06-26 14:00:20', NULL),
(458, 471, '37.xx', NULL, '2014-06-26 14:00:20', NULL),
(459, 471, '36.xx', NULL, '2014-06-26 14:00:20', NULL),
(460, 471, '35.xx', NULL, '2014-06-26 14:00:20', NULL),
(461, 471, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(462, 480, 'USB', NULL, '2014-06-26 14:00:20', NULL),
(463, 480, '33.xx', NULL, '2014-06-26 14:00:20', NULL),
(464, 480, '32.xx', NULL, '2014-06-26 14:00:20', NULL),
(465, 480, '31.xx', NULL, '2014-06-26 14:00:20', NULL),
(466, 480, '30.xx', NULL, '2014-06-26 14:00:20', NULL),
(467, 480, '29.xx', NULL, '2014-06-26 14:00:20', NULL),
(468, 480, '28.xx', NULL, '2014-06-26 14:00:20', NULL),
(469, 480, '27.xx', NULL, '2014-06-26 14:00:20', NULL),
(470, 480, '26.xx', NULL, '2014-06-26 14:00:20', NULL),
(471, 480, '25.xx', NULL, '2014-06-26 14:00:20', NULL),
(472, 480, '24.xx', NULL, '2014-06-26 14:00:20', NULL),
(473, 480, '23.xx', NULL, '2014-06-26 14:00:20', NULL),
(474, 472, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(475, 480, '22.xx', NULL, '2014-06-26 14:00:20', NULL),
(476, 480, '21.xx', NULL, '2014-06-26 14:00:20', NULL),
(477, 480, '20.xx', NULL, '2014-06-26 14:00:20', NULL),
(478, 473, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(479, 480, '1080p', NULL, '2014-06-26 14:00:20', NULL),
(480, 480, '19.xx', NULL, '2014-06-26 14:00:20', NULL),
(481, 480, '18.xx', NULL, '2014-06-26 14:00:20', NULL),
(482, 480, '17.xx', NULL, '2014-06-26 14:00:20', NULL),
(483, 480, '16.xx', NULL, '2014-06-26 14:00:20', NULL),
(484, 480, '15.xx', NULL, '2014-06-26 14:00:20', NULL),
(485, 480, '14.xx', NULL, '2014-06-26 14:00:20', NULL),
(486, 474, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(487, 475, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(488, 476, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(489, 477, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(490, 478, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(491, 479, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(492, 480, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(493, 486, 'Camcorder', NULL, '2014-06-26 14:00:20', NULL),
(494, 486, 'QEP-ISDBT-1Seg', NULL, '2014-06-26 14:00:20', NULL),
(495, 486, 'VEP', NULL, '2014-06-26 14:00:20', NULL),
(496, 481, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(497, 482, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(498, 483, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(499, 484, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(500, 485, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(501, 486, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(502, 488, 'Demo', NULL, '2014-06-26 14:00:20', NULL),
(503, 488, 'Application', NULL, '2014-06-26 14:00:20', NULL),
(504, 488, 'Hardware', NULL, '2014-06-26 14:00:20', NULL),
(505, 487, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(506, 488, 'Silicon', NULL, '2014-06-26 14:00:20', NULL),
(507, 488, 'Systems', NULL, '2014-06-26 14:00:20', NULL),
(508, 488, 'Codecs', NULL, '2014-06-26 14:00:20', NULL),
(509, 488, '3rd Party', NULL, '2014-06-26 14:00:20', NULL),
(510, 488, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(511, 489, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(512, 490, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(513, 491, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(514, 492, 'unspecified', NULL, '2014-06-26 14:00:20', NULL),
(515, 492, '0.07', NULL, '2014-06-26 14:00:20', NULL),
(516, 492, '0.06', NULL, '2014-06-26 14:00:20', NULL),
(517, 492, '0.05', NULL, '2014-06-26 14:00:20', NULL),
(518, 492, '0.04', NULL, '2014-06-26 14:00:20', NULL),
(519, 492, '0.03', NULL, '2014-06-26 14:00:20', NULL),
(520, 492, '0.02', NULL, '2014-06-26 14:00:20', NULL),
(521, 492, '0.01', NULL, '2014-06-26 14:00:20', NULL),
(522, 493, 'other', NULL, '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `screen`
--

CREATE TABLE IF NOT EXISTS `screen` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5889 ;

--
-- Dumping data for table `screen`
--

INSERT INTO `screen` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(588, 0, 'Default Screen', 'Allows to update all system fields.', '0000-00-00 00:00:00', NULL),
(589, 0, 'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '0000-00-00 00:00:00', NULL),
(590, 0, 'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '0000-00-00 00:00:00', NULL),
(5827, 1940, 'Default Screen', 'Allows to update all system fields.', '2014-05-08 19:17:02', NULL),
(5828, 1940, 'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '2014-05-08 19:17:02', NULL),
(5829, 1940, 'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '2014-05-08 19:17:02', NULL),
(5833, 1942, 'Default Screen', 'Allows to update all system fields.', '2014-05-08 19:17:02', NULL),
(5834, 1942, 'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '2014-05-08 19:17:02', NULL),
(5835, 1942, 'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '2014-05-08 19:17:02', NULL),
(5839, 1944, 'Default Screen', 'Allows to update all system fields.', '2014-05-08 19:17:02', NULL),
(5840, 1944, 'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '2014-05-08 19:17:02', NULL),
(5841, 1944, 'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '2014-05-08 19:17:02', NULL),
(5885, 1959, 'Default Screen', 'Allows to update all system fields.', '2014-06-26 14:00:20', NULL),
(5886, 1959, 'Resolve Issue Screen', 'Allows to set resolution, change fix versions and assign an issue.', '2014-06-26 14:00:20', NULL),
(5887, 1959, 'Workflow Screen', 'This screen is used in the workflow and enables you to assign issues.', '2014-06-26 14:00:20', NULL),
(5888, 1959, 'Confirm Screen', '', '2014-06-26 13:53:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `screen_data`
--

CREATE TABLE IF NOT EXISTS `screen_data` (
`id` bigint(20) unsigned NOT NULL,
  `screen_id` bigint(20) unsigned NOT NULL,
  `field_id` bigint(20) unsigned NOT NULL,
  `position` int(10) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35325 ;

--
-- Dumping data for table `screen_data`
--

INSERT INTO `screen_data` (`id`, `screen_id`, `field_id`, `position`, `date_created`) VALUES
(34945, 5827, 29104, 1, '2014-05-08 19:17:02'),
(34946, 5827, 29105, 2, '2014-05-08 19:17:02'),
(34947, 5827, 29112, 3, '2014-05-08 19:17:02'),
(34948, 5827, 29110, 4, '2014-05-08 19:17:02'),
(34949, 5827, 29108, 5, '2014-05-08 19:17:02'),
(34950, 5827, 29106, 6, '2014-05-08 19:17:02'),
(34951, 5827, 29111, 7, '2014-05-08 19:17:02'),
(34952, 5827, 29107, 8, '2014-05-08 19:17:02'),
(34953, 5827, 29115, 9, '2014-05-08 19:17:02'),
(34954, 5827, 29114, 10, '2014-05-08 19:17:02'),
(34955, 5827, 29109, 11, '2014-05-08 19:17:02'),
(34956, 5827, 29113, 12, '2014-05-08 19:17:02'),
(34957, 5827, 29116, 13, '2014-05-08 19:17:02'),
(34958, 5828, 29107, 1, '2014-05-08 19:17:02'),
(34959, 5828, 29111, 2, '2014-05-08 19:17:02'),
(34960, 5828, 29102, 3, '2014-05-08 19:17:02'),
(34961, 5828, 29103, 4, '2014-05-08 19:17:02'),
(34962, 5829, 29107, 1, '2014-05-08 19:17:02'),
(34981, 5833, 29134, 1, '2014-05-08 19:17:02'),
(34982, 5833, 29135, 2, '2014-05-08 19:17:02'),
(34983, 5833, 29142, 3, '2014-05-08 19:17:02'),
(34984, 5833, 29140, 4, '2014-05-08 19:17:02'),
(34985, 5833, 29138, 5, '2014-05-08 19:17:02'),
(34986, 5833, 29136, 6, '2014-05-08 19:17:02'),
(34987, 5833, 29141, 7, '2014-05-08 19:17:02'),
(34988, 5833, 29137, 8, '2014-05-08 19:17:02'),
(34989, 5833, 29145, 9, '2014-05-08 19:17:02'),
(34990, 5833, 29144, 10, '2014-05-08 19:17:02'),
(34991, 5833, 29139, 11, '2014-05-08 19:17:02'),
(34992, 5833, 29143, 12, '2014-05-08 19:17:02'),
(34993, 5833, 29146, 13, '2014-05-08 19:17:02'),
(34994, 5834, 29137, 1, '2014-05-08 19:17:02'),
(34995, 5834, 29141, 2, '2014-05-08 19:17:02'),
(34996, 5834, 29132, 3, '2014-05-08 19:17:02'),
(34997, 5834, 29133, 4, '2014-05-08 19:17:02'),
(34998, 5835, 29137, 1, '2014-05-08 19:17:02'),
(35017, 5839, 29164, 1, '2014-05-08 19:17:02'),
(35018, 5839, 29165, 2, '2014-05-08 19:17:02'),
(35019, 5839, 29172, 3, '2014-05-08 19:17:02'),
(35020, 5839, 29170, 4, '2014-05-08 19:17:02'),
(35021, 5839, 29168, 5, '2014-05-08 19:17:02'),
(35022, 5839, 29166, 6, '2014-05-08 19:17:02'),
(35023, 5839, 29171, 7, '2014-05-08 19:17:02'),
(35024, 5839, 29167, 8, '2014-05-08 19:17:02'),
(35025, 5839, 29175, 9, '2014-05-08 19:17:02'),
(35026, 5839, 29174, 10, '2014-05-08 19:17:02'),
(35027, 5839, 29169, 11, '2014-05-08 19:17:02'),
(35028, 5839, 29173, 12, '2014-05-08 19:17:02'),
(35029, 5839, 29176, 13, '2014-05-08 19:17:02'),
(35030, 5840, 29167, 1, '2014-05-08 19:17:02'),
(35031, 5840, 29171, 2, '2014-05-08 19:17:02'),
(35032, 5840, 29162, 3, '2014-05-08 19:17:02'),
(35033, 5840, 29163, 4, '2014-05-08 19:17:02'),
(35034, 5841, 29167, 1, '2014-05-08 19:17:02'),
(35303, 5885, 29390, 1, '2014-06-26 14:00:20'),
(35304, 5885, 29391, 2, '2014-06-26 14:00:20'),
(35305, 5885, 29398, 3, '2014-06-26 14:00:20'),
(35306, 5885, 29396, 4, '2014-06-26 14:00:20'),
(35307, 5885, 29394, 5, '2014-06-26 14:00:20'),
(35308, 5885, 29392, 6, '2014-06-26 14:00:20'),
(35309, 5885, 29397, 7, '2014-06-26 14:00:20'),
(35310, 5885, 29393, 8, '2014-06-26 14:00:20'),
(35311, 5885, 29401, 9, '2014-06-26 14:00:20'),
(35312, 5885, 29400, 10, '2014-06-26 14:00:20'),
(35313, 5885, 29395, 11, '2014-06-26 14:00:20'),
(35314, 5885, 29399, 12, '2014-06-26 14:00:20'),
(35315, 5885, 29402, 13, '2014-06-26 14:00:20'),
(35316, 5886, 29393, 1, '2014-06-26 14:00:20'),
(35317, 5886, 29397, 2, '2014-06-26 14:00:20'),
(35318, 5886, 29388, 3, '2014-06-26 14:00:20'),
(35319, 5886, 29389, 4, '2014-06-26 14:00:20'),
(35320, 5887, 29393, 1, '2014-06-26 14:00:20'),
(35321, 5888, 29392, 4, '2014-06-26 13:53:03'),
(35322, 5888, 29393, 3, '2014-06-26 13:53:03'),
(35323, 5888, 29389, 1, '2014-06-26 13:53:03'),
(35324, 5888, 29398, 2, '2014-06-26 13:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `screen_scheme`
--

CREATE TABLE IF NOT EXISTS `screen_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1961 ;

--
-- Dumping data for table `screen_scheme`
--

INSERT INTO `screen_scheme` (`id`, `client_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1941, 1940, 'Default Screen Scheme', 'Default Screen Scheme', '2014-05-08 19:17:02', NULL),
(1943, 1942, 'Default Screen Scheme', 'Default Screen Scheme', '2014-05-08 19:17:02', NULL),
(1945, 1944, 'Default Screen Scheme', 'Default Screen Scheme', '2014-05-08 19:17:02', NULL),
(1960, 1959, 'Default Screen Scheme', 'Default Screen Scheme', '2014-06-26 14:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `screen_scheme_data`
--

CREATE TABLE IF NOT EXISTS `screen_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `screen_scheme_id` bigint(20) unsigned NOT NULL,
  `sys_operation_id` bigint(20) unsigned NOT NULL,
  `screen_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5884 ;

--
-- Dumping data for table `screen_scheme_data`
--

INSERT INTO `screen_scheme_data` (`id`, `screen_scheme_id`, `sys_operation_id`, `screen_id`, `date_created`) VALUES
(586, 0, 1, 588, '0000-00-00 00:00:00'),
(587, 0, 2, 588, '0000-00-00 00:00:00'),
(588, 0, 3, 588, '0000-00-00 00:00:00'),
(5824, 1941, 1, 5827, '2014-05-08 19:17:02'),
(5825, 1941, 2, 5827, '2014-05-08 19:17:02'),
(5826, 1941, 3, 5827, '2014-05-08 19:17:02'),
(5830, 1943, 1, 5833, '2014-05-08 19:17:02'),
(5831, 1943, 2, 5833, '2014-05-08 19:17:02'),
(5832, 1943, 3, 5833, '2014-05-08 19:17:02'),
(5836, 1945, 1, 5839, '2014-05-08 19:17:02'),
(5837, 1945, 2, 5839, '2014-05-08 19:17:02'),
(5838, 1945, 3, 5839, '2014-05-08 19:17:02'),
(5881, 1960, 1, 5885, '2014-06-26 14:00:20'),
(5882, 1960, 2, 5885, '2014-06-26 14:00:20'),
(5883, 1960, 3, 5885, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `server_settings`
--

CREATE TABLE IF NOT EXISTS `server_settings` (
`id` bigint(20) unsigned NOT NULL,
  `maintenance_server_message` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `server_settings`
--

INSERT INTO `server_settings` (`id`, `maintenance_server_message`) VALUES
(1, '');

-- --------------------------------------------------------

--
-- Table structure for table `svn_repository`
--

CREATE TABLE IF NOT EXISTS `svn_repository` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `user_created_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=183 ;

-- --------------------------------------------------------

--
-- Table structure for table `svn_repository_user`
--

CREATE TABLE IF NOT EXISTS `svn_repository_user` (
`id` bigint(20) unsigned NOT NULL,
  `svn_repository_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` bigint(20) unsigned NOT NULL,
  `password` varchar(250) DEFAULT NULL,
  `has_read` tinyint(1) DEFAULT NULL,
  `has_write` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=304 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_condition`
--

CREATE TABLE IF NOT EXISTS `sys_condition` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sys_condition`
--

INSERT INTO `sys_condition` (`id`, `name`, `description`) VALUES
(1, 'Only Assignee Condition', 'Condition to allow only the assignee to execute a transition. '),
(2, 'Only Reporter Condition ', 'Condition to allow only the reporter to execute a transition. '),
(3, 'Permission Condition ', 'Condition to allow only users with a certain permission to execute a transition. ');

-- --------------------------------------------------------

--
-- Table structure for table `sys_country`
--

CREATE TABLE IF NOT EXISTS `sys_country` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=197 ;

--
-- Dumping data for table `sys_country`
--

INSERT INTO `sys_country` (`id`, `name`) VALUES
(1, 'Afghanistan'),
(2, 'Albania'),
(3, 'Algeria'),
(4, 'Andorra'),
(5, 'Angola'),
(6, 'Antigua & Deps'),
(7, 'Argentina'),
(8, 'Armenia'),
(9, 'Australia'),
(10, 'Austria'),
(11, 'Azerbaijan'),
(12, 'Bahamas'),
(13, 'Bahrain'),
(14, 'Bangladesh'),
(15, 'Barbados'),
(16, 'Belarus'),
(17, 'Belgium'),
(18, 'Belize'),
(19, 'Benin'),
(20, 'Bhutan'),
(21, 'Bolivia'),
(22, 'Bosnia Herzegovina'),
(23, 'Botswana'),
(24, 'Brazil'),
(25, 'Brunei'),
(26, 'Bulgaria'),
(27, 'Burkina'),
(28, 'Burundi'),
(29, 'Cambodia'),
(30, 'Cameroon'),
(31, 'Canada'),
(32, 'Cape Verde'),
(33, 'Central African Rep'),
(34, 'Chad'),
(35, 'Chile'),
(36, 'China'),
(37, 'Colombia'),
(38, 'Comoros'),
(39, 'Congo'),
(40, 'Congo {Democratic Rep}'),
(41, 'Costa Rica'),
(42, 'Croatia'),
(43, 'Cuba'),
(44, 'Cyprus'),
(45, 'Czech Republic'),
(46, 'Denmark'),
(47, 'Djibouti'),
(48, 'Dominica'),
(49, 'Dominican Republic'),
(50, 'East Timor'),
(51, 'Ecuador'),
(52, 'Egypt'),
(53, 'El Salvador'),
(54, 'Equatorial Guinea'),
(55, 'Eritrea'),
(56, 'Estonia'),
(57, 'Ethiopia'),
(58, 'Fiji'),
(59, 'Finland'),
(60, 'France'),
(61, 'Gabon'),
(62, 'Gambia'),
(63, 'Georgia'),
(64, 'Germany'),
(65, 'Ghana'),
(66, 'Greece'),
(67, 'Grenada'),
(68, 'Guatemala'),
(69, 'Guinea'),
(70, 'Guinea-Bissau'),
(71, 'Guyana'),
(72, 'Haiti'),
(73, 'Honduras'),
(74, 'Hungary'),
(75, 'Iceland'),
(76, 'India'),
(77, 'Indonesia'),
(78, 'Iran'),
(79, 'Iraq'),
(80, 'Ireland {Republic}'),
(81, 'Israel'),
(82, 'Italy'),
(83, 'Ivory Coast'),
(84, 'Jamaica'),
(85, 'Japan'),
(86, 'Jordan'),
(87, 'Kazakhstan'),
(88, 'Kenya'),
(89, 'Kiribati'),
(90, 'Korea North'),
(91, 'Korea South'),
(92, 'Kosovo'),
(93, 'Kuwait'),
(94, 'Kyrgyzstan'),
(95, 'Laos'),
(96, 'Latvia'),
(97, 'Lebanon'),
(98, 'Lesotho'),
(99, 'Liberia'),
(100, 'Libya'),
(101, 'Liechtenstein'),
(102, 'Lithuania'),
(103, 'Luxembourg'),
(104, 'Macedonia'),
(105, 'Madagascar'),
(106, 'Malawi'),
(107, 'Malaysia'),
(108, 'Maldives'),
(109, 'Mali'),
(110, 'Malta'),
(111, 'Marshall Islands'),
(112, 'Mauritania'),
(113, 'Mauritius'),
(114, 'Mexico'),
(115, 'Micronesia'),
(116, 'Moldova'),
(117, 'Monaco'),
(118, 'Mongolia'),
(119, 'Montenegro'),
(120, 'Morocco'),
(121, 'Mozambique'),
(122, 'Myanmar, {Burma}'),
(123, 'Namibia'),
(124, 'Nauru'),
(125, 'Nepal'),
(126, 'Netherlands'),
(127, 'New Zealand'),
(128, 'Nicaragua'),
(129, 'Niger'),
(130, 'Nigeria'),
(131, 'Norway'),
(132, 'Oman'),
(133, 'Pakistan'),
(134, 'Palau'),
(135, 'Panama'),
(136, 'Papua New Guinea'),
(137, 'Paraguay'),
(138, 'Peru'),
(139, 'Philippines'),
(140, 'Poland'),
(141, 'Portugal'),
(142, 'Qatar'),
(143, 'Romania'),
(144, 'Russian Federation'),
(145, 'Rwanda'),
(146, 'St Kitts & Nevis'),
(147, 'St Lucia'),
(148, 'Saint Vincent & the Grenadines'),
(149, 'Samoa'),
(150, 'San Marino'),
(151, 'Sao Tome & Principe'),
(152, 'Saudi Arabia'),
(153, 'Senegal'),
(154, 'Serbia'),
(155, 'Seychelles'),
(156, 'Sierra Leone'),
(157, 'Singapore'),
(158, 'Slovakia'),
(159, 'Slovenia'),
(160, 'Solomon Islands'),
(161, 'Somalia'),
(162, 'South Africa'),
(163, 'South Sudan'),
(164, 'Spain'),
(165, 'Sri Lanka'),
(166, 'Sudan'),
(167, 'Suriname'),
(168, 'Swaziland'),
(169, 'Sweden'),
(170, 'Switzerland'),
(171, 'Syria'),
(172, 'Taiwan'),
(173, 'Tajikistan'),
(174, 'Tanzania'),
(175, 'Thailand'),
(176, 'Togo'),
(177, 'Tonga'),
(178, 'Trinidad & Tobago'),
(179, 'Tunisia'),
(180, 'Turkey'),
(181, 'Turkmenistan'),
(182, 'Tuvalu'),
(183, 'Uganda'),
(184, 'Ukraine'),
(185, 'United Arab Emirates'),
(186, 'United Kingdom'),
(187, 'United States'),
(188, 'Uruguay'),
(189, 'Uzbekistan'),
(190, 'Vanuatu'),
(191, 'Vatican City'),
(192, 'Venezuela'),
(193, 'Vietnam'),
(194, 'Yemen'),
(195, 'Zambia'),
(196, 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `sys_field_type`
--

CREATE TABLE IF NOT EXISTS `sys_field_type` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `code` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sys_field_type`
--

INSERT INTO `sys_field_type` (`id`, `name`, `description`, `code`) VALUES
(1, 'Text Field (< 255 characters)', 'A basic single line text box custom field to allow simple text input.', 'small_text_field'),
(2, 'Date Picker', 'A custom field that stores dates and uses a date picker to view them.', 'date_picker'),
(3, 'Date Time', 'A custom field that stores dates with a time component', 'date_time'),
(4, 'Free Text Field', 'A multiline text area custom field to allow input of longer text strings.', 'big_text_field'),
(5, 'Number Field', 'A custom field that stores and validates numeric (floating point) input.', 'number');

-- --------------------------------------------------------

--
-- Table structure for table `sys_operation`
--

CREATE TABLE IF NOT EXISTS `sys_operation` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `sys_operation`
--

INSERT INTO `sys_operation` (`id`, `name`) VALUES
(1, 'create'),
(2, 'edit'),
(3, 'view');

-- --------------------------------------------------------

--
-- Table structure for table `sys_permission`
--

CREATE TABLE IF NOT EXISTS `sys_permission` (
`id` bigint(20) unsigned NOT NULL,
  `sys_permission_category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `sys_permission`
--

INSERT INTO `sys_permission` (`id`, `sys_permission_category_id`, `name`, `description`) VALUES
(1, 1, 'Administer Projects', 'Ability to administer a project in Ubirimi.'),
(2, 1, 'Browse Projects', 'Ability to browse projects and the issues within them.'),
(3, 2, 'Create Issues', 'Ability to create issues.'),
(4, 2, 'Edit Issues', 'Ability to edit issues.'),
(5, 2, 'Assign Issues', 'Ability to assign issues to other people.'),
(6, 2, 'Assignable User', 'Users with this permission may be assigned to issues.'),
(7, 2, 'Resolve Issues', 'Ability to resolve and reopen issues. This includes the ability to set a fix version.'),
(8, 2, 'Close Issues', 'Ability to close issues. Often useful where your developers resolve issues, and a QA department closes them.'),
(9, 2, 'Modify Reporter', 'Ability to modify the reporter when creating or editing an issue.'),
(10, 2, 'Delete Issues', 'Ability to delete issues.'),
(11, 3, 'Add Comments', 'Ability to comment on issues.'),
(12, 3, 'Edit All Comments', 'Ability to edit all comments made on issues.'),
(13, 3, 'Edit Own Comments', 'Ability to edit own comments made on issues.'),
(14, 3, 'Delete All Comments', 'Ability to delete all comments made on issues.'),
(15, 3, 'Delete Own Comments', 'Ability to delete own comments made on issues.'),
(16, 4, 'Create Attachments', 'Users with this permission may create attachments.'),
(17, 4, 'Delete All Attachments', 'Users with this permission may delete all attachments.'),
(18, 4, 'Delete Own Attachments', 'Users with this permission may delete own attachments.'),
(19, 2, 'Set Issue Security', 'Ability to set the level of security on an issue so that only people in that security level can see the issue.'),
(20, 2, 'Link Issues', 'Ability to link issues together and create linked issues. Only useful if issue linking is turned on.'),
(21, 2, 'Move Issues', 'Ability to move issues between projects or between workflows of the same project (if applicable). Note the user can only move issues to a project he or she has the create permission for.'),
(22, 5, 'Work On Issues', 'Ability to log work done against an issue. Only useful if Time Tracking is turned on.'),
(23, 5, 'Edit Own Worklogs', 'Ability to edit own worklogs made on issues.'),
(24, 5, 'Edit All Worklogs', 'Ability to edit all worklogs made on issues.'),
(25, 5, 'Delete Own Worklogs', 'Ability to delete own worklogs made on issues.'),
(26, 5, 'Delete All Worklogs', 'Ability to delete all worklogs made on issues.'),
(27, 6, 'View Voters and Watchers', 'Ability to view the voters and watchers of an issue.'),
(28, 6, 'Manage Watchers', 'Ability to manage the watchers of an issue.');

-- --------------------------------------------------------

--
-- Table structure for table `sys_permission_category`
--

CREATE TABLE IF NOT EXISTS `sys_permission_category` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sys_permission_category`
--

INSERT INTO `sys_permission_category` (`id`, `name`) VALUES
(1, 'Project Permissions'),
(2, 'Issue Permissions'),
(3, 'Comments Permissions'),
(4, 'Attachments Permissions'),
(5, 'Time Tracking Permissions'),
(6, 'Voters & Watchers Permissions');

-- --------------------------------------------------------

--
-- Table structure for table `sys_permission_global`
--

CREATE TABLE IF NOT EXISTS `sys_permission_global` (
`id` bigint(20) unsigned NOT NULL,
  `sys_product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sys_permission_global`
--

INSERT INTO `sys_permission_global` (`id`, `sys_product_id`, `name`, `description`) VALUES
(1, 1, 'Yongo System Administrators', 'Permission to perform all Yongo administration functions.'),
(2, 1, 'Yongo Administrators', 'Ability to perform most administration functions (excluding Import & Export, etc.). '),
(3, 1, 'Yongo Users', 'Ability to log in to Yongo. They are a ''user''. Any new users created will automatically join these groups.'),
(4, 1, 'Bulk Change', 'Ability to modify a collection of issues at once. For example, resolve multiple issues in one step.'),
(5, 4, 'Documentator Administrator', 'Can administer the application but is disallowed from operations that may compromise system security.'),
(6, 4, 'Documentator System Administrator', 'Has complete control and access to all administrative functions.'),
(7, 4, 'Create Space', 'Able to add spaces to the site.');

-- --------------------------------------------------------

--
-- Table structure for table `sys_permission_global_data`
--

CREATE TABLE IF NOT EXISTS `sys_permission_global_data` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sys_permission_global_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15436 ;

--
-- Dumping data for table `sys_permission_global_data`
--

INSERT INTO `sys_permission_global_data` (`id`, `client_id`, `sys_permission_global_id`, `group_id`, `user_id`, `date_created`) VALUES
(1, 1, 1, 1, NULL, '0000-00-00 00:00:00'),
(2, 1, 2, 1, NULL, '0000-00-00 00:00:00'),
(3, 1, 3, 3, NULL, '0000-00-00 00:00:00'),
(7, 3, 1, 7, NULL, '0000-00-00 00:00:00'),
(8, 3, 2, 7, NULL, '0000-00-00 00:00:00'),
(9, 3, 3, 9, NULL, '0000-00-00 00:00:00'),
(10, 4, 1, 10, NULL, '0000-00-00 00:00:00'),
(11, 4, 2, 10, NULL, '0000-00-00 00:00:00'),
(12, 4, 3, 12, NULL, '0000-00-00 00:00:00'),
(13, 5, 1, 13, NULL, '0000-00-00 00:00:00'),
(14, 5, 2, 13, NULL, '0000-00-00 00:00:00'),
(15, 5, 3, 15, NULL, '0000-00-00 00:00:00'),
(16, 6, 1, 16, NULL, '0000-00-00 00:00:00'),
(17, 6, 2, 16, NULL, '0000-00-00 00:00:00'),
(18, 6, 3, 18, NULL, '0000-00-00 00:00:00'),
(19, 7, 1, 19, NULL, '0000-00-00 00:00:00'),
(20, 7, 2, 19, NULL, '0000-00-00 00:00:00'),
(21, 7, 3, 21, NULL, '0000-00-00 00:00:00'),
(22, 9, 1, 22, NULL, '0000-00-00 00:00:00'),
(23, 9, 2, 22, NULL, '0000-00-00 00:00:00'),
(24, 9, 3, 24, NULL, '0000-00-00 00:00:00'),
(25, 10, 1, 25, NULL, '0000-00-00 00:00:00'),
(26, 10, 2, 25, NULL, '0000-00-00 00:00:00'),
(27, 10, 3, 27, NULL, '0000-00-00 00:00:00'),
(28, 11, 1, 28, NULL, '0000-00-00 00:00:00'),
(29, 11, 2, 28, NULL, '0000-00-00 00:00:00'),
(30, 11, 3, 30, NULL, '0000-00-00 00:00:00'),
(52, 19, 1, 52, NULL, '0000-00-00 00:00:00'),
(53, 19, 2, 52, NULL, '0000-00-00 00:00:00'),
(54, 19, 3, 54, NULL, '0000-00-00 00:00:00'),
(61, 22, 1, 61, NULL, '0000-00-00 00:00:00'),
(62, 22, 2, 61, NULL, '0000-00-00 00:00:00'),
(63, 22, 3, 63, NULL, '0000-00-00 00:00:00'),
(112, 40, 1, 112, NULL, '0000-00-00 00:00:00'),
(113, 40, 2, 112, NULL, '0000-00-00 00:00:00'),
(114, 40, 3, 114, NULL, '0000-00-00 00:00:00'),
(148, 53, 1, 148, NULL, '0000-00-00 00:00:00'),
(149, 53, 2, 148, NULL, '0000-00-00 00:00:00'),
(150, 53, 3, 150, NULL, '0000-00-00 00:00:00'),
(1300, 0, 1, 711, NULL, '0000-00-00 00:00:00'),
(1301, 0, 2, 711, NULL, '0000-00-00 00:00:00'),
(1302, 0, 3, 713, NULL, '0000-00-00 00:00:00'),
(1303, 0, 4, 713, NULL, '0000-00-00 00:00:00'),
(1304, 0, 7, 714, NULL, '0000-00-00 00:00:00'),
(1305, 0, 5, 714, NULL, '0000-00-00 00:00:00'),
(1306, 0, 6, 714, NULL, '0000-00-00 00:00:00'),
(1307, 0, 7, 1, NULL, '0000-00-00 00:00:00'),
(15276, 1940, 1, 9444, NULL, '0000-00-00 00:00:00'),
(15277, 1940, 2, 9444, NULL, '0000-00-00 00:00:00'),
(15278, 1940, 3, 9446, NULL, '0000-00-00 00:00:00'),
(15279, 1940, 4, 9446, NULL, '0000-00-00 00:00:00'),
(15280, 1940, 7, 9447, NULL, '0000-00-00 00:00:00'),
(15281, 1940, 5, 9447, NULL, '0000-00-00 00:00:00'),
(15282, 1940, 6, 9447, NULL, '0000-00-00 00:00:00'),
(15283, 1940, 7, 1, NULL, '0000-00-00 00:00:00'),
(15292, 1942, 1, 9454, NULL, '0000-00-00 00:00:00'),
(15293, 1942, 2, 9454, NULL, '0000-00-00 00:00:00'),
(15294, 1942, 3, 9456, NULL, '0000-00-00 00:00:00'),
(15295, 1942, 4, 9456, NULL, '0000-00-00 00:00:00'),
(15296, 1942, 7, 9457, NULL, '0000-00-00 00:00:00'),
(15297, 1942, 5, 9457, NULL, '0000-00-00 00:00:00'),
(15298, 1942, 6, 9457, NULL, '0000-00-00 00:00:00'),
(15299, 1942, 7, 1, NULL, '0000-00-00 00:00:00'),
(15308, 1944, 1, 9464, NULL, '0000-00-00 00:00:00'),
(15309, 1944, 2, 9464, NULL, '0000-00-00 00:00:00'),
(15310, 1944, 3, 9466, NULL, '0000-00-00 00:00:00'),
(15311, 1944, 4, 9466, NULL, '0000-00-00 00:00:00'),
(15312, 1944, 7, 9467, NULL, '0000-00-00 00:00:00'),
(15313, 1944, 5, 9467, NULL, '0000-00-00 00:00:00'),
(15314, 1944, 6, 9467, NULL, '0000-00-00 00:00:00'),
(15315, 1944, 7, 1, NULL, '0000-00-00 00:00:00'),
(15428, 1959, 1, 9542, NULL, '0000-00-00 00:00:00'),
(15429, 1959, 2, 9542, NULL, '0000-00-00 00:00:00'),
(15430, 1959, 3, 9544, NULL, '0000-00-00 00:00:00'),
(15431, 1959, 4, 9544, NULL, '0000-00-00 00:00:00'),
(15432, 1959, 7, 9545, NULL, '0000-00-00 00:00:00'),
(15433, 1959, 5, 9545, NULL, '0000-00-00 00:00:00'),
(15434, 1959, 6, 9545, NULL, '0000-00-00 00:00:00'),
(15435, 1959, 7, 1, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sys_product`
--

CREATE TABLE IF NOT EXISTS `sys_product` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sys_product`
--

INSERT INTO `sys_product` (`id`, `name`, `description`) VALUES
(1, 'Yongo', 'Issue & Project Tracking Software'),
(2, 'SVN Hosting', 'SVN Hosting for your projects'),
(3, 'Cheetah', 'Agile Module'),
(4, 'Documentator', 'Content Creation, Collaboration & Knowledge Sharing for Teams'),
(5, 'Events', 'Events'),
(6, 'Helpdesk', '');

-- --------------------------------------------------------

--
-- Table structure for table `sys_product_release`
--

CREATE TABLE IF NOT EXISTS `sys_product_release` (
`id` bigint(20) unsigned NOT NULL,
  `sys_product_id` bigint(20) NOT NULL,
  `version` varchar(10) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_workflow_post_function`
--

CREATE TABLE IF NOT EXISTS `sys_workflow_post_function` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(80) NOT NULL,
  `description` varchar(250) NOT NULL,
  `user_addable_flag` tinyint(3) unsigned NOT NULL,
  `user_editable_flag` tinyint(3) unsigned NOT NULL,
  `user_deletable_flag` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `sys_workflow_post_function`
--

INSERT INTO `sys_workflow_post_function` (`id`, `name`, `description`, `user_addable_flag`, `user_editable_flag`, `user_deletable_flag`) VALUES
(1, 'Update Issue Field', 'Updates a simple issue field to a given value.', 1, 1, 1),
(2, 'Set issue status to the linked status of the destination workflow step', 'Set issue status to the linked status of the destination workflow step. ', 0, 0, 0),
(3, 'Update change history for the issue', 'Update change history for the issue', 0, 0, 0),
(4, 'Create issue', 'Create the issue originally', 0, 0, 0),
(5, 'Fire an event', 'Fire an event', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sys_workflow_step_property`
--

CREATE TABLE IF NOT EXISTS `sys_workflow_step_property` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sys_workflow_step_property`
--

INSERT INTO `sys_workflow_step_property` (`id`, `name`) VALUES
(1, 'ubirimi.issue.editable');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(80) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `first_name` varchar(200) DEFAULT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `avatar_picture` varchar(250) DEFAULT NULL,
  `issues_per_page` int(10) unsigned DEFAULT NULL,
  `issues_display_columns` text,
  `client_administrator_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `svn_administrator_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `super_user_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `notify_own_changes_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `customer_service_desk_flag` tinyint(4) NOT NULL DEFAULT '0',
  `date_created` datetime DEFAULT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3031 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `client_id`, `username`, `password`, `email`, `first_name`, `last_name`, `avatar_picture`, `issues_per_page`, `issues_display_columns`, `client_administrator_flag`, `svn_administrator_flag`, `super_user_flag`, `notify_own_changes_flag`, `customer_service_desk_flag`, `date_created`, `date_updated`) VALUES
(362, 0, 'cireasa', '$2a$08$K5iaIqaX8uR5QKBHyc45VOTUQFv7ewGgmRK3T.yG0EWa.8eCjoYiO', 'cireasa@stb-development.nl', 'fkdshkj', 'fkjdhf;skj', NULL, 20, 'code#summary#priority#status#created#type#updated#reporter#assignee', 1, 1, 0, 0, 0, '2013-10-18 09:35:02', NULL),
(2219, 1940, 'crsby', '$2a$08$.KB5uUFSfH38YXmODpeYy.DYI3Mrbm/roJO/zF8ONSs1eufmiVTwW', 'john@crsby.com', 'John', 'Crosby', NULL, 20, 'code#summary#priority#status#created#type#updated#reporter#assignee', 1, 1, 0, 0, 0, '2014-05-08 19:17:02', NULL),
(2221, 1942, 'evoorhees', '$2a$08$25SH3hYgwsfRTQ.SSH.Yz.r7pwELLMJVSfQSOOXYRfXLBZFnwM2ny', 'evoorhees@lerandom.com', 'Ethan', 'Voorhees', NULL, 20, 'code#summary#priority#status#created#type#updated#reporter#assignee', 1, 1, 0, 0, 0, '2014-05-08 19:17:02', NULL),
(2223, 1944, 'cmoraleda', '$2a$08$2eq9GhZj0tc8bfcPsKw.fudrzJtYE0ghJYJxI79HKsYnPRvYy3mfW', 'clemente.moraleda@gmail.com', 'Clemente', 'Moraleda', NULL, 20, 'code#summary#priority#status#created#type#updated#reporter#assignee', 1, 1, 0, 0, 0, '2014-05-08 19:17:02', NULL),
(2836, 1959, 'vali', '$2a$08$uJU1..tv2qLw5s7n/ZOYze6wbr.Ik0syZc02GUp.XC.f778tpAjqq', 'vali@vali.com', 'Vali', 'Muresan', NULL, 20, 'code#summary#priority#status#created#type#updated#reporter#assignee', 1, 1, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2837, 1959, 'sorin.gavril@movidius.com', '$2a$08$3xkkE.PAq7YesqYrqHLLee1JXovlQaouJgDKBuP5cng5IRqbUerry', 'sorin.gavril@movidius.com', 'Sorin', 'Gavril', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2838, 1959, 'orla.niloinsigh@movidius.com', '$2a$08$1MUH6Fg.o3OqRo1nQcoB4.Jb/uUVzzeRoCNbUA9.94cNBwV9RXPFC', 'orla.niloinsigh@movidius.com', 'Orla', 'Ni Loinsigh', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2839, 1959, 'shixong.xu@movidius.com', '$2a$08$VvBrJdORq6OFk.uj1aM2M.0lZ6TS8rUyW.FUv6FfHGyvJMra4NUGu', 'shixong.xu@movidius.com', 'Shixong', 'Xu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2840, 1959, 'lvasilev@mm-sol.com', '$2a$08$f7N2tUn2mxBk6OWVgMgEQOqzcP9SYJNTvHXwcS9KqI1fDHMooM6Wy', 'lvasilev@mm-sol.com', 'Lubomir', 'Vasilev MMS', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2841, 1959, 'andras.csermak@movidius.com', '$2a$08$6sHjuAMuYqnU9J.PA3HyYewd54FSBIXh2TddRv7crEId32rM3rfTm', 'andras.csermak@movidius.com', 'Andras', 'Csermak', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2842, 1959, 'dhasan@mm-sol.com', '$2a$08$DWUEN2UCGOIg8N0dvMUdc.fkfztgB8mlG0TIYgf6htDER2B3ZINNa', 'dhasan@mm-sol.com', 'Deniz', 'Hasan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2843, 1959, 'rfilipov@mm-sol.com', '$2a$08$HCn8x3qnKBl5hKHV6ynHHe2bnMTGum/6ZXQMfSH3TQp0ihSnMKCha', 'rfilipov@mm-sol.com', 'Ruslan', 'Filipov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2844, 1959, 'ypopov@mm-sol.com', '$2a$08$JeZUkkizkji5xJsUl6w70uzcrWC1VMuMISp9l4RHUfh8gE06skVlm', 'ypopov@mm-sol.com', 'Yanko', 'Popov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2845, 1959, 'jshandorov@mm-sol.com', '$2a$08$IcloXCziyNgqKKHnFa7Zd.aNyq/i6FRhNeU8GcOai.LRfyqi8UDhe', 'jshandorov@mm-sol.com', 'Julian', 'Shandorov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2846, 1959, 'eangelov@mm-sol.com', '$2a$08$MfQT0ZRzvDWrDTmvQB2SBOk3r0v3IGHFHu48QoRvEb2cnpJKQ52lO', 'eangelov@mm-sol.com', 'Emil', 'Angelov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2847, 1959, 'pyosifov@mm-sol.com', '$2a$08$mv4SXYj3aZeVhCK74/ExNelaYsSWHZ58M1p2yJzUZVjMyai.hVrCy', 'pyosifov@mm-sol.com', 'Pavel', 'Yosifov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2848, 1959, 'ytombakov@mm-sol.com', '$2a$08$uIGRmwvCOaCW/Y8EE7I8/uthvfK2CP0NeO1J4ZQcYcNScTDjX/ebS', 'ytombakov@mm-sol.com', 'Yordan', 'Tombakov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2849, 1959, 'pvalev@mm-sol.com', '$2a$08$mpCAYVoddoInq/Pj4kMPiuEBRGTZhNIT5QiynYBNzSarRL9Doe4RC', 'pvalev@mm-sol.com', 'Plamen', 'Valev', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2850, 1959, 'dmironov@mm-sol.com', '$2a$08$s3rSja/QhmCYIuG63pT8f.d7rU2.Z3xbB7xTB7z8W0kRp04F3Yfi6', 'dmironov@mm-sol.com', 'Dinko', 'Mironov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2851, 1959, 'vivanov@mm-sol.com', '$2a$08$v/E6I4xvYMkq21GxrwcSOet1feyWYGYtU2fnWJyjuEk6INKPqtOEi', 'vivanov@mm-sol.com', 'Valeri', 'Ivanov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2852, 1959, 'hhristov@mm-sol.com', '$2a$08$/RKuoRD7zNyD6ET8UaaTb.yu4/zrtSiHrMqVBbPWMKWW/pooyzwoC', 'hhristov@mm-sol.com', 'Hristo', 'Hristov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2853, 1959, 'ltodorov@mm-sol.com', '$2a$08$RqTU3vLuYvL5DZPC.DVqn.pTxEolemLwykPxa7LU8/9gy.oeC6mTG', 'ltodorov@mm-sol.com', 'Lubomir', 'Todorov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:20', NULL),
(2854, 1959, 'nnikolov@mm-sol.com', '$2a$08$H6OhEMh4GRU1ew3wYN.Moubw2HNmeH91hacvIyY4sFmLhO0UhuUuK', 'nnikolov@mm-sol.com', 'Nikolai', 'Nikolov MMS', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2855, 1959, 'steve.lloyd@movidius.com', '$2a$08$CRLgVui5IXpUwnaLmZe6m.BdWXx00riSn7TK3onG0CoysOFI8Njhe', 'steve.lloyd@movidius.com', 'Steve', 'Lloyd', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2856, 1959, 'vasilevi@mm-sol.com', '$2a$08$y18RFGSv70pFhLmLnjcHruw0AaYRjrZPPyNE3tl4OLZ2o5ROTOHE6', 'vasilevi@mm-sol.com', 'Ivan', 'Vasilev MMS', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2857, 1959, 'colm.keane@movidius.com', '$2a$08$39UeqTn9adb9A49TnV0hCu98.CJbMAJ4I6Tl7cVTlcbsRKBHTCRDy', 'colm.keane@movidius.com', 'Colm', 'Keane', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2858, 1959, 'catalin.curcanu@movidius.com', '$2a$08$csdyKYTHc4wad1ZtDSJO/O9bweQUSIVEBaiWVYdcg.Mnum3RM4ABW', 'catalin.curcanu@movidius.com', 'Catalin', 'Curcanu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2859, 1959, 'shfryan@gmail.com', '$2a$08$/pBiLYBTTkmofZoY/GqxXedHWwaNJDblBniLkd/YFXmRmOkQm5Wgq', 'shfryan@gmail.com', 'Stephen', 'Ryan Gmail', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2860, 1959, 'tester@mm-sol.com', '$2a$08$Nax0aPNRu3uX3Bgmz9eryOj6VpDYeqviDnPyu.7OVuFnTkKEGAW7S', 'tester@mm-sol.com', '', 'ester@mm-sol.com', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2861, 1959, 'ddenchev@mm-sol.com', '$2a$08$LKU1Erz0MSGQJO27/Q0TMeYu1pnx64ms9VB/GuRlzIpF3kmQiOXJm', 'ddenchev@mm-sol.com', 'Dobromir', 'Denchev MMS', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2862, 1959, 'robert.nicoras@movidius.com', '$2a$08$028FXVfTcne7nHb2/xt0JuthxuZlNfhvvytkDnO4C8BVrswvjmS3K', 'robert.nicoras@movidius.com', 'Robert', 'Nicoras', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2863, 1959, 'claudiu.steflea@movidius.com', '$2a$08$YIqsMyERiyXwfFeNR034iOg2t8W4WJbTZa3fBSlrBJRQNs0UGciHW', 'claudiu.steflea@movidius.com', 'Claudiu', 'Steflea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2864, 1959, 'calin.pantea@movidius.com', '$2a$08$OO1WpuGxU28BQVQz/j42UO0hZJk6TC7erzNi6W/E5AI/f.AiOK6wu', 'calin.pantea@movidius.com', 'Calin', 'Pantea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2865, 1959, 'athena.elafrou@movidius.com', '$2a$08$An2E/.msNjoUrMgxZ10KTOgj8lQX3Tw6k4mqSl0FpGFsNH62bjo0y', 'athena.elafrou@movidius.com', 'Athena', 'Elafrou', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2866, 1959, 'gabor.moricz@movidius.com', '$2a$08$4rKAoPht3DXvTuNfjV3aHOjVII3WCW.5tOTWQAm7vTD74BPWMYTs2', 'gabor.moricz@movidius.com', 'Gabor', 'Moricz', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2867, 1959, 'poyuan@movidius.com', '$2a$08$WE8cs8uRI3Jrfeyd4w0/3uRPJVEczdHlU4S7iDic4ugF7qVPhEDMO', 'poyuan@movidius.com', 'Po', 'Yuan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2868, 1959, 'adrian.hendroff@movidius.com', '$2a$08$Rga/npgJU5mB88qp43.iA.B8H6mUJG68Lhinn3bJJvK0hS8h7gyu.', 'adrian.hendroff@movidius.com', 'Adrian', 'Hendroff', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2869, 1959, 'tom.ryan@movidius.com', '$2a$08$qYmnpfdezmAYH1C2WW.Rh.Vok2miYJEw/OiNEML05IyIT9f8LEXTu', 'tom.ryan@movidius.com', 'Tom', 'Ryan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2870, 1959, 'flavio.cali@movidius.com', '$2a$08$.47bxg6cJYum9t1BmTuslOmLnVEnyYu2Fw6QZX51RFYV4bxerkOMW', 'flavio.cali@movidius.com', 'Flavio', 'Cali', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2871, 1959, 'jon.ewanich@movidius.com', '$2a$08$0uyWZ6ULXrf.eoPMjtX37ukZsCc1X09GvPS5gSRO3mTE3QY.fBx1C', 'jon.ewanich@movidius.com', 'Jon', 'Ewanich', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2872, 1959, 'juan.sanchez@movidius.com', '$2a$08$ulXJL1ptdSvP.bgiQ9bYPOGnAEgw3rS/Aq2PTVC08VQNDZ7s./Pni', 'juan.sanchez@movidius.com', 'Juan', 'Sanchez', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2873, 1959, 'chuck.handley@movidius.com', '$2a$08$Y58jfs5cm9e3tx34ank55uMtBXzno1OCWB2WMeHPKihFI.5vRfdWa', 'chuck.handley@movidius.com', 'Chuck', 'Handley', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2874, 1959, 'hadi.sadeghitaheri@movidius.com', '$2a$08$7NPvRf0ccCgR/UkPDq28l.iTnGG/Y4NRxgEsFnVr12Inwv.RxJcA2', 'hadi.sadeghitaheri@movidius.com', 'Hadi', 'Sadeghi Taheri', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2875, 1959, 'stephen.mcdonagh@movidius.com', '$2a$08$xK18x6viKWx38K3FMMEn1.D44B/cZCosdBVNgeE4IUhnKtiQFneH6', 'stephen.mcdonagh@movidius.com', 'Stephen', 'McDonagh', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2876, 1959, 'peter.hanos@movidius.com', '$2a$08$bWV5xY4w7xGP4s4aNMFjQ.abJGjY1aarLC6FwBR40Oj7nDZvZq1Ca', 'peter.hanos@movidius.com', 'Peter', 'Hanos', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2877, 1959, 'jozsef.ferencz@movidius.com', '$2a$08$O77VBzYnuuPXJImSkXKMfuwjJHFYGiPURtPjNxiz3P8jfytGUs4VS', 'jozsef.ferencz@movidius.com', 'Jozsef', 'Ferencz', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2878, 1959, 'viktor.szentgyorgyi@movidius.com', '$2a$08$rGV4fe.e2VAvmyj2brNNoezm/o4qBdug3yKrH2qdTd9mT7vlm79aS', 'viktor.szentgyorgyi@movidius.com', 'Viktor', ' Szentgyorgyi', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2879, 1959, 'Sergio.Monroy@movidius.com', '$2a$08$0AT8P/MEbuCiiD6XYovVeuzsYyxmxENpiGU7gQsSA3vmoEme6DyRy', 'Sergio.Monroy@movidius.com', 'Sergio', 'Monroy', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2880, 1959, 'zsolt.biro@movidius.com', '$2a$08$KlYOThEW/AC7wdluAk.JTuFWWsdczIuEWDVwAdcaBUdpllgQy2p96', 'zsolt.biro@movidius.com', 'Zsolt', 'Biro', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2881, 1959, 'bogdan.camalessa@movidius.com', '$2a$08$DQU5hDOwqd5r5laysvGToeqg3jkWRhvHfv4MHri3NsfGNYeh6q8UW', 'bogdan.camalessa@movidius.com', 'Bogdan', 'Camalessa', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2882, 1959, 'tomasz.szydzik@movidius.com', '$2a$08$D8sPXwpJTwrEF9sYiEQsT.hJP0uPpPx/bUaA5n8nPn04kdmBn.Hf.', 'tomasz.szydzik@movidius.com', 'Tomasz', 'Szydzik', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2883, 1959, 'emilia.murarescu@movidius.com', '$2a$08$eO4GNy9anUFJbqmDJ/ZQoOtTsrgIN3r4CUA6Vpn/O1z2dHQYoxyOO', 'emilia.murarescu@movidius.com', 'Emilia', 'Murarescu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2884, 1959, 'denis.denes@movidius.com', '$2a$08$T326MFqgUTO6/.qF4oD1iOqvuEKrs701rZmJA7/PKaNALx7Y2mfNO', 'denis.denes@movidius.com', '', 'enis', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2885, 1959, 'adrian.bobocica@movidius.com', '$2a$08$ThYru/IHczg1v2adKnsTUukNHoXPVByo03NcUyz9JcHTls16QGvMO', 'adrian.bobocica@movidius.com', 'Adrian', 'Bobocica', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2886, 1959, 'ana.popescu@movidius.com', '$2a$08$rgcY2hIHMC/TwlZ.D.X8NOSXBuo4G4URrU9dXzTrgxKYvjYYtDVJq', 'ana.popescu@movidius.com', 'Popescu', 'Ana-Maria', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2887, 1959, 'maria.ene@movidius.com', '$2a$08$sGehOUbnQzj/RNu90tMsyeykJw5aBE4ZsfgvD3/rxbpeJR5Qngdqi', 'maria.ene@movidius.com', '', '', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2888, 1959, 'sebastian.stancu@movidius.com', '$2a$08$/D8LG7SZl91w4s8.wZQcvOtePGNQFfLOcxF9XGPbIP5P0rKJI19JW', 'sebastian.stancu@movidius.com', 'Sebastian', 'Stancu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2889, 1959, 'daniel.turc@movidius.com', '$2a$08$o3WkWaPMY2a2MPs9.cpDxui61xFEl4owE77tAoA3Up65QmpWN3MOO', 'daniel.turc@movidius.com', 'Turc', 'Daniel', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2890, 1959, 'alexandru.paval@movidius.com', '$2a$08$K7WxFA0AHOX8PJ7c6pEijOsoboaurlZ61XncWVeqr/afsTIuj.tkK', 'alexandru.paval@movidius.com', 'Alexandru', 'Pavăl', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2891, 1959, 'radu.vasiu@movidius.com', '$2a$08$zILupR8ljs7tnhmSGZVZWu0bzdiIgt.7bBOezGlyGuYKZ2wzYa.RC', 'radu.vasiu@movidius.com', '', 'adu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2892, 1959, 'emilia.pitiga@movidius.com', '$2a$08$MrFBya0GuJk4XAmsstMHzeGEI4sx3/zLRcgxJRF397rXsxYN3H9fW', 'emilia.pitiga@movidius.com', 'Emilia', 'Pitiga', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2893, 1959, 'rolland.gal@movidius.com', '$2a$08$2Z7WNRICDQ0A/xRJsFH8FeBq/7TMsPSp9VrQmXzrEIHpcT4pEJOwS', 'rolland.gal@movidius.com', 'Rolland', 'Gal', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2894, 1959, 'hannu.lampinen@movidius.com', '$2a$08$bjY86VD7vNO0eHGBT.2sQOivyrVjMLUa1Hw0fUhNCagANdUxoYOaC', 'hannu.lampinen@movidius.com', 'Hannu', 'Lampinen', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2895, 1959, 'vcs.users@movidius.com', '$2a$08$.EGnLYDI4bq6WRneuYvbaesPcSQfJQX.raxHVM5NyLDA33CdzmC72', 'vcs.users@movidius.com', 'VCS', 'Users', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2896, 1959, 'mark.cane@movidius.com', '$2a$08$dPhSEXUKrcHqVi0zqHl7juUs3crkOnJugMVSQfpyRZvqxRjyNhvBy', 'mark.cane@movidius.com', 'Mark', 'Cane', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2897, 1959, 'cristina.marghes@movidius.com', '$2a$08$bmAFgjts2IxVixCo7CbgYOUVyvQXqrfHyN0Kaz4krY7dsRv6QBOnK', 'cristina.marghes@movidius.com', 'Cristina', 'Marghes', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2898, 1959, 'gerry.griffin@movidius.com', '$2a$08$YFWCpkpvCsYnXxgke.GIteG9IQKJ8k77Mj/wS6ISrd91i7pemZXda', 'gerry.griffin@movidius.com', 'Gerry', 'Griffin', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2899, 1959, 'gergely.kiss@movidius.com', '$2a$08$yIlVIiYPeNopMHXOyu0SguXsKlJnKJkC7DE1sqi5MEGAMJFWVpn7O', 'gergely.kiss@movidius.com', 'Gergely', 'Kiss', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2900, 1959, 'laszlo.vagasi@movidius.com', '$2a$08$rjBZphsaTAV8HOvViXPvU.ObLMX6nQ4ETTQe.0d7/aoeIdmEdr6Y2', 'laszlo.vagasi@movidius.com', 'Laszlo', 'Vagasi', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2901, 1959, 'stephen.rogers@movidius.com', '$2a$08$Jt/BkQeGkcuXyCzEJdKCmeDqonV0vZM/yeVwcav.QG8nwmoZtHVVu', 'stephen.rogers@movidius.com', 'Stephen', 'Rogers', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2902, 1959, 'nicolae.voinovan@movidius.com', '$2a$08$KsTMrOhI6L3ZUfnPHsZuTen73hh6ElIL/BadzBVStiGeZ7GqJIny.', 'nicolae.voinovan@movidius.com', 'Voinovan', 'Nicolae', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2903, 1959, 'ircona@movidius.com', '$2a$08$tTgrAaZ.WLIOE9dulLTDG.G6qCV4MVWYnRUf0iPlWMuLj.cQ9YNyG', 'ircona@movidius.com', '', 'rcona', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2904, 1959, 'codeplay2@movidius.com', '$2a$08$8ub544pnkw7KkZMsI/36m.7MQUpOfWCcG7MfA/quRCu3TCrLs.q6i', 'codeplay2@movidius.com', '', 'odeplay2', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2905, 1959, 'bogdan.mavrodin@movidius.com', '$2a$08$CJ8c1eV5Jli9VRinXISxX.p7h3QopT1xkO/Wp0TBIUEjZKYkbIZTm', 'bogdan.mavrodin@movidius.com', '', 'ogdan.mavrodin', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2906, 1959, 'dragos.vlad@movidius.com', '$2a$08$1Xr1lupgVachDhC87nRtzu6lNiqIhzL5OTa/jNg7MR/JvfkOGRPnK', 'dragos.vlad@movidius.com', 'Dragos', 'Vlad', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2907, 1959, 'codeplay@movidius.com', '$2a$08$JX4fmx7PfJXZGVO4J7DYXeFno61C7825AddTgIb/ciPnYcD9qHM1e', 'codeplay@movidius.com', '', 'odeplay', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2908, 1959, 'michael.doyle@movidius.com', '$2a$08$8xvFtDXpxLN6hPl8Y/YovursJ6BytZXMwFMZTfuKtYEvwRuQ.ACZ6', 'michael.doyle@movidius.com', 'Michael', 'Doyle', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2909, 1959, 'gilberto.muzzi@movidius.com', '$2a$08$nDQDmBEnII2Cnc0nPraX2uXKAuMDb3x1M54GFqSeAU9QgEB9wHJse', 'gilberto.muzzi@movidius.com', 'Gilberto', 'Muzzi', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2910, 1959, 'cristian.petruta@movidius.com', '$2a$08$iblQEzzUBrXOpMHnDv35nec0T1qZ2UzM42vJB5eXshJyh2jCGnZ4y', 'cristian.petruta@movidius.com', 'Cristian', 'Petruta', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2911, 1959, 'bugzilla_sw@movidius.com', '$2a$08$orKlJcLtPRzuM182YyID9uJ4Mc9SS.vRrILdARzIsKH3N4PcL4udG', 'bugzilla_sw@movidius.com', '', 'ugzilla_sw', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2912, 1959, 'bugzilla_hw@movidius.com', '$2a$08$tlnCre/TgQOujCRpv6CP/e4P8Ykt.JNGBesQ8DQK5DhpTldKgdxuG', 'bugzilla_hw@movidius.com', 'Bugzilla', 'HW', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2913, 1959, 'Silvano.Conte@movidius.com', '$2a$08$SGho0ClyJII8sNskDWyS.OKatQ2vFXfBXCphY9xBDhJLwXggxGGoC', 'Silvano.Conte@movidius.com', 'Silvano', 'Conte', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2914, 1959, 'Endre.Papp@movidius.com', '$2a$08$LOQDlqTyDOz347asGZqHqetIa0WicqPfggyNKhxfIUc.GWwlC/uQO', 'Endre.Papp@movidius.com', 'Endre', 'Papp', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2915, 1959, 'Goran.Petrovity@movidius.com', '$2a$08$GeOou47TL9Xo3LSE56p5telp/5dl78WY0pL2RmCfD/igv23eovVy6', 'Goran.Petrovity@movidius.com', 'Goran', 'Petrovity', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2916, 1959, 'Marek.Havel@movidius.com', '$2a$08$GUQFrRcO8BzTWc8SsDfh0OmBqvr7xyb5W/HkOySlTveGZbKCDRaTu', 'Marek.Havel@movidius.com', 'Marek', 'Havel', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2917, 1959, 'Milan.Tuma@movidius.com', '$2a$08$6URAdIqTyOuoFNtWd1TFH.J/MzuHId1IpScO/otfP.DqYHVF.xMpG', 'Milan.Tuma@movidius.com', 'Milan', 'Tuma', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2918, 1959, 'Andrea.Fedeli@movidius.com', '$2a$08$Ar6d8xLOoFvb5kZj4kaHHep8PCZU1YPYKT./J/pcUomhfEicyoAE6', 'Andrea.Fedeli@movidius.com', 'Andrea', 'Fedeli', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2919, 1959, 'Marco.Marini@movidius.com', '$2a$08$IdPY58k8YolsMq9eE93mhO2T8jN0wgZAVkmqMj8xyRWI2Bo4nR.1W', 'Marco.Marini@movidius.com', 'Marco', 'Marini', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2920, 1959, 'raffaele.pallavicino@movidius.com', '$2a$08$DOdVMKPDG7SZwclUC9ElgOZIDVJ8IOeZy8hRAPhmCSfQNgpwpq172', 'raffaele.pallavicino@movidius.com', 'Raffaele', 'Pallavicino', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:21', NULL),
(2921, 1959, 'marco.stanzani@movidius.com', '$2a$08$FbvKrz1wvxy4UxFAHuc.ju2USo7HLRu2QamN7Nhi0B0/r4my/HM0e', 'marco.stanzani@movidius.com', 'Marco', 'Stanzani', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2922, 1959, 'andrea.rigo@movidius.com', '$2a$08$g38iVER271.kNPGOwMRJLeOzq8BXpIJ7dnZAMtKuX4VkQ1cyVhqFG', 'andrea.rigo@movidius.com', 'Andrea', 'Rigo', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2923, 1959, 'michele.borgatti@movidius.com', '$2a$08$Sy7DmwZe0oAGS285GaE82eo74XinYkRUQOWMuut6sDrgWs5KhHh/6', 'michele.borgatti@movidius.com', 'Michele', 'Borgatti', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2924, 1959, 'gabor.molnar@movidius.com', '$2a$08$XFA0ua1F540NpT46TJG0qOzeYw.bj121IYBI5jN0GNx2wuZNQsHwi', 'gabor.molnar@movidius.com', 'Gabor', 'Molnar', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2925, 1959, 'imre.mados@movidius.com', '$2a$08$DL1G7FzIYIU26tMJEhOJiOOqWyfiizREZXxVLEQns3eIsEoXgzfG.', 'imre.mados@movidius.com', 'Imre', 'Mados', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2926, 1959, 'peter.vari@movidius.com', '$2a$08$dgY8E2w7KDI4shQP17dn7egZYOC8L2bFxYR6jXDHws3iQsHAu0Z6m', 'peter.vari@movidius.com', 'Peter', 'Vari', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2927, 1959, 'balazs.bodis@movidius.com', '$2a$08$aL9f4nWA2rI5IeE0UcDdDedPcQjL6H9PdyzDjtE9TtXSip4dn6i52', 'balazs.bodis@movidius.com', 'Balazs', 'Bodis', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2928, 1959, 'zsolt.sogorka@movidius.com', '$2a$08$sTvKODvgFAAIRBdF.x0spOXyA9LVCSgTE4RP28ApIIvGo4/R3O5Hi', 'zsolt.sogorka@movidius.com', 'Zsolt', 'Sogorka', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2929, 1959, 'david.kiraly@movidius.com', '$2a$08$6Rc2FSijhg6xsZH0DvbhXO3QHKRk9.DvqAlfQFvYBPxhlZMLQZH0S', 'david.kiraly@movidius.com', 'David', 'Kiraly', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2930, 1959, 'balint.voros@movidius.com', '$2a$08$FMBMMlfdNLLJs7F5/lm2.eG1XnnBBbk47/7wuxdiwF3YFLvzeC80G', 'balint.voros@movidius.com', 'Balint', 'Voros', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2931, 1959, 'attila.hudak@movidius.com', '$2a$08$rspXvDAAVzd7HVJS/kczXuDfof98VN1vpFsTxw1fMK9qdhaRHQmYe', 'attila.hudak@movidius.com', 'Attila', 'Hudak', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2932, 1959, 'csaba.nemeth@movidius.com', '$2a$08$KLtVhpMqi0aInN29cCNy8.qQ2tFwrK.2FiaPj57tpFbcuJyr0hs7u', 'csaba.nemeth@movidius.com', 'Csaba', 'Nemeth', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2933, 1959, 'martin.oriordan@movidius.com', '$2a$08$HwFov71DBSh4zKfWRKdM8OAn.6deAcHkCcbe2HtYm9u64cX./2Zxa', 'martin.oriordan@movidius.com', 'Martin', 'O''Riordan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2934, 1959, 'Stephen.Ryan@movidius.com', '$2a$08$EnEL2Gmix/RqJ9SRjN/6kOovBJZBVJ6hm0SW2haJB2kpx56TShoke', 'Stephen.Ryan@movidius.com', 'Stephen', 'Ryan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2935, 1959, 'Attila.Zigo@movidius.com', '$2a$08$yhV9.qbgZD.zLT1Bj9tHXuchZUicECk5si1rVK/r8fDasW4rEkr3.', 'Attila.Zigo@movidius.com', 'Attila', 'Zigo', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2936, 1959, 'Martin.Hoellerer@movidius.com', '$2a$08$./PHWmC3joejle9ZgQ/4uONqW.UzlTjvyfNwUFQMwmJZEcWjPXOeq', 'Martin.Hoellerer@movidius.com', 'Martin', 'Hoellerer', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2937, 1959, 'Sean.Power@movidius.com', '$2a$08$nuck73iBtKpSa.xrFGxKseCYXinyuUFwRxNZi9sYnKrzxbFebtOQ2', 'Sean.Power@movidius.com', 'Sean', 'Power', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2938, 1959, 'alexandru.horin@movidius.com', '$2a$08$6v6EE5MiUSEm84Jx0q8Llufv/cbrpTXdFdCdvH2fnaKFjRs1egTwG', 'alexandru.horin@movidius.com', 'Alexandru', 'Horin', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2939, 1959, 'alexandru.ghidan@movidius.com', '$2a$08$8nOEty9gLsNrQ4dAeEn5UOTlILwBIuU.2enogNHs0eeC8nb.ZZhx.', 'alexandru.ghidan@movidius.com', 'Alex', 'Ghidan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2940, 1959, 'natanael.cintean@movidius.com', '$2a$08$.CFx5m4mUj3h1hwfol1VqezZyWS/Z9TF6sOkYhneBOPtMNPZm2C8G', 'natanael.cintean@movidius.com', 'Natanael', 'Cintean', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2941, 1959, 'razvan.delibasa@movidius.com', '$2a$08$xKwotCDz3nRk2l0pywWot.09kIK/jpO2H6GKCTjRjL/rl/BsA4G/q', 'razvan.delibasa@movidius.com', 'Razvan', 'Delibasa', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2942, 1959, 'attila.csok@movidius.com', '$2a$08$Bs1ow7u/lpoPKk6I5Y/DYuYehgEAYB69hoZJd2MmNhNszT2o7esti', 'attila.csok@movidius.com', '', 'ttila.csok', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2943, 1959, 'Ted.Irvine@movidius.com', '$2a$08$FuX2HURTjqGKqnjHYYsHmetnrvwGdH.DnbIRx2PgzUNq.5G44.zFa', 'Ted.Irvine@movidius.com', 'Ted', 'Irvine', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2944, 1959, 'Conor.Macaoidh@movidius.com', '$2a$08$i9hMMk6Pl4TwPr6jbi0Iouz7ixMFBmuvtyEUAEZ3BxfujaidR81y.', 'Conor.Macaoidh@movidius.com', 'Conor', 'Mac Aoidh', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2945, 1959, 'andrei.minastireanu@movidius.com', '$2a$08$.pNZM94VDIVcRCqfmt8WLOyQiplfd3RKfXcMYp6qlU69YMoZkb9nK', 'andrei.minastireanu@movidius.com', 'Andrei', 'MINASTIREANU', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2946, 1959, 'marius.cosma@movidius.com', '$2a$08$73rQ2GM2S2.2uSaQrvo.DeOE4OnpVLV1yZWt8lqs6pUbGPUUz5D76', 'marius.cosma@movidius.com', 'Marius', 'Cosma', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2947, 1959, 'luminita.daraban@movidius.com', '$2a$08$XcN4WwEqdabjMjZYMzS64u0dMP2i29brdsOohU.S21JdF2DMVFb3y', 'luminita.daraban@movidius.com', 'Luminita', 'Daraban', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2948, 1959, 'catalin.mihai@movidius.com', '$2a$08$.m/q2TYnYz.NoUc3HbWEX.IYOMlK9C7gXAtkUBS1ONboiZP3ulbDu', 'catalin.mihai@movidius.com', 'Catalin', 'Mihai', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2949, 1959, 'andrei.tanase@movidius.com', '$2a$08$pXOc8qzya.zmzj9Q7oc0Nu46X6fnSc67TGRyXCd5Mi2T9YDyUOBTC', 'andrei.tanase@movidius.com', '', '', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2950, 1959, 'cristiana.crisan@movidius.com', '$2a$08$OBwujP1UbJRSvyZofW.ql.4B3sJyT63OdusaRbiRe9COp68NdVdqm', 'cristiana.crisan@movidius.com', 'Cristiana', 'Crisan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2951, 1959, 'flavia.halatiba@movidius.com', '$2a$08$m.0x2/LbEnl7.KZEqGTbW.0UGWm38PHS8bovWGYQthKtnPdht52ma', 'flavia.halatiba@movidius.com', 'Flavia', 'Halatiba', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2952, 1959, 'oana.ciortan@movidius.com', '$2a$08$lx1/5mPJIPq/m69rz03rv.CM.dQXiGdV052pgOHFoRYrFxv.d3OrG', 'oana.ciortan@movidius.com', 'Oana', 'Ciortan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2953, 1959, 'anca.alb@movidius.com', '$2a$08$gOTVcL4mqM/QlmWb5IYPE.DvuUD8h2lzgbmvCwpA.YjEKHSVC5cjO', 'anca.alb@movidius.com', '', 'nca.alb@movidius.com', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2954, 1959, 'cristina.dumitrascu@movidius.com', '$2a$08$OL5MjmoaPrPRVvnBx2TtW.r0VYR12gTmfMscfDtfZiawlbmlP01d6', 'cristina.dumitrascu@movidius.com', 'Cristina', 'Dumitrascu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2955, 1959, 'diana.sipoteanu@movidius.com', '$2a$08$M0V3wU/JPwJVsxBaHVLPgOXZEq7dpk5pnmBqiZ3UxQ9lsDPb5TE0a', 'diana.sipoteanu@movidius.com', 'Diana', 'Sipoteanu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2956, 1959, 'andrei.baiasu@movidius.com', '$2a$08$qfph0TAetF120ezSrbhm6Oekq8QIuFOxrdgeczSVKWGMXbyU4mdYy', 'andrei.baiasu@movidius.com', 'Andrei', 'Baiasu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2957, 1959, 'daniela.busu@movidius.com', '$2a$08$ND6RAAcSi2S72KSBKRQ55.QduOEAnv9FRY/XUIH/kQpp0RVuoA5tm', 'daniela.busu@movidius.com', 'Daniela', 'Busu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2958, 1959, 'marius.ciortan@movidius.com', '$2a$08$a16s0tsz2EDl1wVzVU7oA.e4bj4nZhTdHz0efcfMNew6eGgYxJXtW', 'marius.ciortan@movidius.com', 'Marius', 'Ciortan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2959, 1959, 'claudiu.cosma@movidius.com', '$2a$08$KoZ6n30mZvkSuBpMAigaZerXsOJFvjStFmY.qroaKilrEShJnZovi', 'claudiu.cosma@movidius.com', 'Claudiu', 'Cosma', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2960, 1959, 'tiberius.vinczi@movidius.com', '$2a$08$.Vfx3bnJBdBK4tGU8RugK.vFByRgJ1uA2xjVsKK/K7.3L6BZwWYza', 'tiberius.vinczi@movidius.com', 'Tiberius', 'Vinczi', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2961, 1959, 'cristina.prajescu@movidius.com', '$2a$08$T8FJRwEAWZzf3rtk2SVcRev8etQeF7bHAgosvDZaKgylMukQPEAzm', 'cristina.prajescu@movidius.com', 'Cristina', 'Prajescu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2962, 1959, 'andreea.dumitru@movidius.com', '$2a$08$46hAjzjGJ198g89Pj7kXeOT00zT1h2U7gVl5h.ONd9CsH25BPYvBO', 'andreea.dumitru@movidius.com', 'Andreea', 'Dumitru', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2963, 1959, 'andrei.purdea@movidius.com', '$2a$08$qV0TIKG49WMf0hRkV6vxkO6OOOKXr8BFcRaSMOZCnfh9elnYAYoL.', 'andrei.purdea@movidius.com', 'Andrei', 'Purdea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2964, 1959, 'ovidiu.popa@movidius.com', '$2a$08$OXgS/5fBwEP/c2mdXAQ3q.6L5HyFjl4Xss5ZLsnw5F8Viq4ywhv3G', 'ovidiu.popa@movidius.com', 'Ovidiu', 'Popa', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2965, 1959, 'vlad.bunea@movidius.com', '$2a$08$CQ4PxOEfqJAACyFhik4ONuy575zz80XjLA.CKrZNtC/8X8MtE9cP2', 'vlad.bunea@movidius.com', 'Vlad', 'Bunea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2966, 1959, 'alexandru.amaricai@movidius.com', '$2a$08$GfYIcNnYkbW5MMs/V58DcO0EcwVOE1NgXdeju65Lavdp9FJelVwNS', 'alexandru.amaricai@movidius.com', 'Alexandru', 'Amaricai-Boncalo', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2967, 1959, 'benjamin.lee@movidius.com', '$2a$08$N9yTF51VPjhNI1xrRQThTOTmjjGhtJO8YMiMKfhLzGmBgI0p88yTW', 'benjamin.lee@movidius.com', 'Benjamin', 'Lee', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2968, 1959, 'Daravith.Kho@movidius.com', '$2a$08$7eiHkb7QHsBEcbGooi3Clew4mesST9dt2XLWP1M48pKlgSu2HIVPu', 'Daravith.Kho@movidius.com', 'Daravith', 'Kho', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2969, 1959, 'dan.dunga@movidius.com', '$2a$08$lTFl5S9W4tw2xpJW9cEq9Ot4WljVvMxkcEHQD63ANOsfnQG6XPRb2', 'dan.dunga@movidius.com', 'Dan', 'Dunga', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2970, 1959, 'calin.precup@movidius.com', '$2a$08$q33o2/i3.IPLrwQfK8H.MecKFLMf4frg0ZfAyeI3Igb6m8jSB8FyG', 'calin.precup@movidius.com', 'Calin', 'Precup', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2971, 1959, 'iulia.stirb@movidius.com', '$2a$08$OgH9ZF8iByPE.iSegCEvxe2MbDI.SLlxahxTxQ2Sfb0RM7t5BuxT6', 'iulia.stirb@movidius.com', 'Iulia', 'Stirb', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2972, 1959, 'yuri.ivanov@movidius.com', '$2a$08$vwFqasCRJyRbSk/Pq4QFVu6o4OYgASnV2aExR0bTRepuey3BOBTyG', 'yuri.ivanov@movidius.com', '', 'uri', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2973, 1959, 'horea.pop@movidius.com', '$2a$08$yjq1lttlbEcG7EJhKLF.zumpaWZtnLpWCm/P2GsttlLLYzLoVx7JW', 'horea.pop@movidius.com', 'Horea', 'Pop', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2974, 1959, 'richard.richmond@movidius.com', '$2a$08$rqwnmTybH5v9atu2WWjvOeqHWqILbRwGUWR4C6Ny4AwYyeI5Urnc2', 'richard.richmond@movidius.com', 'Richard', 'Richmond', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2975, 1959, 'madalina.ghidovit@movidius.com', '$2a$08$k0CON4IgGrw/2S09/YzEmuNitufjvWUvLNR2.n.b70FW/adIISSVm', 'madalina.ghidovit@movidius.com', 'Mădălina', 'Ghidoviț', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2976, 1959, 'ovidiu.vesa@movidius.com', '$2a$08$zZ.90UNyKad/9N.IH.PSqewG8W4d2qXIgqo53fIc2SD3tFr/k.ZxO', 'ovidiu.vesa@movidius.com', 'Vesa', 'Ovidiu-Andrei', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2977, 1959, 'ancuta.ivascu@movidius.com', '$2a$08$DbVC67QCBqOOhK1WDMzmEehDWtBL6mAB.xz58i7oVpu1FkQZtXT9W', 'ancuta.ivascu@movidius.com', 'Ancuta-Maria', 'Ivascu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2978, 1959, 'alexandru.dan@movidius.com', '$2a$08$EXeJd7ghDXjLrPWk2tIlLOvY5QLkUYlBPyFGIbR4P0Lxkd1PGTMIq', 'alexandru.dan@movidius.com', 'Alexandru', 'Dan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2979, 1959, 'alexandru.dura@movidius.com', '$2a$08$ZREbEvYs0kfmBiVtjEJrC.CNaYuNrt/9iR1mWfLc5E8lFWZjNcE2y', 'alexandru.dura@movidius.com', 'Alexandru', 'Dura', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2980, 1959, 'oana.amaricai@movidius.com', '$2a$08$yowwcSC5ZNGid/y5jZr92./yLd9qH.2ZnAgDiqDHUr9XdThQ4lUCi', 'oana.amaricai@movidius.com', 'Oana', 'Boncalo', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2981, 1959, 'adelina.vig@movidius.com', '$2a$08$K9U2P9dPq9ASmT0Ql6CtO.SQmur7HjkYOJ00Dnc0Bx8Cfb690aw0C', 'adelina.vig@movidius.com', 'Adelina', 'Vig', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2982, 1959, 'raluca.veleanu@movidius.com', '$2a$08$5pQSR.IWydpJeTu0UBBFnOFG4mDDxHF9F1.53JNgTFuH5YegThSD6', 'raluca.veleanu@movidius.com', 'Raluca', 'Veleanu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2983, 1959, 'thomas.bohm@movidius.com', '$2a$08$rD0UhSV7fbJP08haglQP0O2ZXlm3VKr5srFycCnPF.8mZ/J8FGS/a', 'thomas.bohm@movidius.com', 'Thomas', 'Bohm', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2984, 1959, 'ivan.velciov@movidius.com', '$2a$08$VGJZaKJU36LvbKhaNSHIdeB56mYK./6yMzFGTiNJPdPTk74G2vTrm', 'ivan.velciov@movidius.com', 'Ivan', 'Velciov', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2985, 1959, 'ajit.deshpande@movidius.com', '$2a$08$Y6sAAg3Jc7/OW.QWM5USIO.2rtYckwreYleXE3pzJEbpTRvLrQ8b6', 'ajit.deshpande@movidius.com', '', 'jit', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2986, 1959, 'marius.truica@movidius.com', '$2a$08$rKhnGcEKUyACuBqeyhSaBu8X17u.EQ9XIh9NjYKwXwS6vu.4td48u', 'marius.truica@movidius.com', 'Marius', 'Truica', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2987, 1959, 'valentin.stangaciu@movidius.com', '$2a$08$r/ggAEkw8DToJcgc3NWV9O0aqeI9SbsTNG3vJr2r0rH0QotEkj7gC', 'valentin.stangaciu@movidius.com', 'Valentin', 'STANGACIU', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:22', NULL),
(2988, 1959, 'boards@movidius.com', '$2a$08$pmh/eKX3JWqp.XrP8vWNg.OyzYMPCqg92rN0S6T8L/3u3u36dtbQ.', 'boards@movidius.com', '', 'oards', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2989, 1959, 'florin.cania@movidius.com', '$2a$08$ODk5TJ8AEhnkr/bcq6gL0OeqhaEfog4FRkisVzSxtqlOw6uFYMqN6', 'florin.cania@movidius.com', 'Florin', 'Cania', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2990, 1959, 'sorin.petrusan@movidius.com', '$2a$08$hDvGgCxyHf6CZ88eSOEr8ekIv1rBFSQby/c6eUaZvfri9A1nYrNOi', 'sorin.petrusan@movidius.com', 'Sorin', 'Petrusan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2991, 1959, 'cliff.wong@movidius.com', '$2a$08$twuYdHQgvC5XRRsANMIaiOW2xZ03wszQ1e8SXuavuHZmwjHds7x22', 'cliff.wong@movidius.com', 'Cliff', 'Wong', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2992, 1959, 'camelia.valuch@movidius.com', '$2a$08$leVOYjvzRaMtigmlQlyH5.rGk99rx.4cFL2OeZE2JQv2IR8MRuuyy', 'camelia.valuch@movidius.com', 'Camelia', 'Valuch', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2993, 1959, 'cristina.stangaciu@movidius.com', '$2a$08$FYdkl6CB0KEZQBopriFvHu4/UVqeLJV9o2GVjlj1K96VdW8TBTrau', 'cristina.stangaciu@movidius.com', 'Cristina-Sorina', 'Stangaciu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2994, 1959, 'Barry.Jones@movidius.com', '$2a$08$EhpPBonLepCYpwH6rGNie.AjaCcwIbTNz.XW7w2/3P01Ln9I7cnYO', 'Barry.Jones@movidius.com', 'Barry', 'Jones', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2995, 1959, 'Stella.Lau@movidius.com', '$2a$08$LVqR9uSX46SfR0rRfqTEGu0CUnMuak9MeXeU42UnDVNO//XIionUu', 'Stella.Lau@movidius.com', 'Stella', 'Lau', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2996, 1959, 'bob.tait@movidius.com', '$2a$08$yvICSGuVE9O0QJe3tCCf6uM6ZAm1cuwg8jBKdA4TnfK/4IsW7AWB2', 'bob.tait@movidius.com', 'Bob', 'Tait', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2997, 1959, 'laszlo.joo@index.hu', '$2a$08$0VK./USW00GhpfaaPsU4x.gXA6FrJEiOM/VlRhORBi7wA20uSWXlG', 'laszlo.joo@index.hu', 'Laszlo', 'Joo', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2998, 1959, 'David.Nicholls@movidius.com', '$2a$08$h27URJW3KQafPEKf9Rebh.wK9de0Re4sntvXlYt.80rKr08m5NL1K', 'David.Nicholls@movidius.com', 'David', 'Nicholls', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(2999, 1959, 'david.donohoe@movidius.com', '$2a$08$cL4RZTOfNmUqJj6spcgGzOoTKuKhKAvt6kHQKJ.emYJqJw0iMHaUW', 'david.donohoe@movidius.com', 'David', 'Donohoe', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3000, 1959, 'Attila.Banyai@movidius.com', '$2a$08$yfqS.jZTbtaFCULwx2.17OvKnZ2PZWHx89XXiGZ6.dW60e3FmTaBK', 'Attila.Banyai@movidius.com', 'Attila', 'Banyai', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3001, 1959, 'cristian.olar@movidius.com', '$2a$08$yYNzSdseq32Th9ariFKkpOOt.KGyk7j6ya7mRifJIEt1MuET/3LfW', 'cristian.olar@movidius.com', 'Cristian', 'Olar', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3002, 1959, 'bogdan.manciu@movidius.com', '$2a$08$ANxE88W///kcV8zWi4h5F.c6dqVS8O0QXqyZHbhpG0UR4/BXOr/Vm', 'bogdan.manciu@movidius.com', 'Bogdan', 'MANCIU', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3003, 1959, 'emanuele.petrucci@movidius.com', '$2a$08$cMkeEdzd2.2g/fHXypM.3OXya9iPjH5JYXBWxfem51XBmU3knrThu', 'emanuele.petrucci@movidius.com', 'Emanuele', 'Petrucci', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3004, 1959, 'alin.dobre@movidius.com', '$2a$08$ia9c/rfePvezsbydSpH8LO7DpHlU97J1OAHa.ymj8Bqw8bXey1ZYC', 'alin.dobre@movidius.com', 'Alin', 'Dobre', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3005, 1959, 'ionel.adam@movidius.com', '$2a$08$ihNjeM3M3X9lzi/s4kD0w.AlM2FEC7zt/Z6X0KALjsSz/G8BjP8fq', 'ionel.adam@movidius.com', 'Ionel', 'Adam', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3006, 1959, 'nicolae.olteanu@movidius.com', '$2a$08$HGfKmLPihj5aBmgon2TyseEeLon4u15.tsiBrSFQKqUstHnMRQkh.', 'nicolae.olteanu@movidius.com', 'Nicolae', 'Olteanu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3007, 1959, 'darren.bowler@movidius.com', '$2a$08$bUl8EKTlQkuYiIhRFEJK8Os6.65wRoMXWk3uwJPtdrsh9q8Egw03e', 'darren.bowler@movidius.com', 'Darren', 'Bowler', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3008, 1959, 'dorin.dragan@movidius.com', '$2a$08$nlCYdEd3uaLv2aN9vZ0lnu0ODU27/LvGPclJF3OZ67QAHOtgjkGAu', 'dorin.dragan@movidius.com', 'Dorin', 'Dragan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3009, 1959, 'sergiu.grip@movidius.com', '$2a$08$ZEfpiQyzbhHi1sOncYD9L.GFwlCNYufWqvbRNFswyfkkQ2/Pm96Xe', 'sergiu.grip@movidius.com', 'Sergiu', 'Grip', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL);
INSERT INTO `user` (`id`, `client_id`, `username`, `password`, `email`, `first_name`, `last_name`, `avatar_picture`, `issues_per_page`, `issues_display_columns`, `client_administrator_flag`, `svn_administrator_flag`, `super_user_flag`, `notify_own_changes_flag`, `customer_service_desk_flag`, `date_created`, `date_updated`) VALUES
(3010, 1959, 'alex.balogh@movidius.com', '$2a$08$NZriHFfRfRm58oaOrDf/mOObpBXNea13Ne6FoMrfd5NsnRIhR0G3.', 'alex.balogh@movidius.com', 'Alex', 'Balogh', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3011, 1959, 'lucian.vancea@movidius.com', '$2a$08$jS.hdA4JZuh3DSeDCBs5tuNeJlMXh/7x63emzRlxUg5RS5s7SI.XW', 'lucian.vancea@movidius.com', 'Lucian', 'Vancea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3012, 1959, 'john.scott@movidius.com', '$2a$08$9pDrGZmvrgCqBmNcH5r6ouiEwEdxlh9FseZ/nbVyHowiEjyreaIbi', 'john.scott@movidius.com', 'John', 'Scott', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3013, 1959, 'virgil.petcu@movidius.com', '$2a$08$3Xen3XUea2IbpA5XyThu4.sAiWibsBxGObbXe0TNnDk0nONVx.1s6', 'virgil.petcu@movidius.com', 'Virgil', 'Petcu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3014, 1959, 'daniel.mariniuc@movidius.com', '$2a$08$LTNJSC5XP/L3adhmgAkfRenrWSI/f0WQ5/wJ7/606Af7j4DoPdQam', 'daniel.mariniuc@movidius.com', 'Daniel', 'Mariniuc', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3015, 1959, 'vasile.popescu@movidius.com', '$2a$08$j3lhtsmRuYNdaZE5jM99a.FY3HO.TXuz9cX/muuhCxqdOk5CTz25m', 'vasile.popescu@movidius.com', 'Vasile', 'Popescu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3016, 1959, 'denisa.popescu@movidius.com', '$2a$08$tAZjw8j1kd8Kp1pbvQurpeRZJU64AJKpjAiibSGKNl5eKpfiVH5uW', 'denisa.popescu@movidius.com', 'Denisa', 'Popescu', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3017, 1959, 'cristian.vesa@movidius.com', '$2a$08$wPC8okcLOS1KgjGcwy8uWevSnjPZ4zMMNlyYrIBL.uRZ07HX9tSg.', 'cristian.vesa@movidius.com', 'cristian', 'vesa', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3018, 1959, 'lucian.mirci@movidius.com', '$2a$08$eWuVnXG0m20msO8Ye1ycd.M/PgL7uvsewZcBQSOS6tDU0f1IbuyYe', 'lucian.mirci@movidius.com', '', 'ucian.Mirci', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3019, 1959, 'sebastian.perta@movidius.com', '$2a$08$/70nYbp4vXULVHuKZ1cWmOAo8STfygHqfFL4UZFNmAk3z/6/Yi1YG', 'sebastian.perta@movidius.com', 'Sebastian', 'Perta', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3020, 1959, 'cristian.cuna@movidius.com', '$2a$08$zPxjJZPROqNeZxmthS5JNOaOcYb/c1hveqyqQTFo1Hy2v4Kw2oXgK', 'cristian.cuna@movidius.com', 'Cristian', 'Cuna', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3021, 1959, 'albert.fazakas@movidius.com', '$2a$08$Jpt44PSNTR06M4Bu9wCBpeDwTKusavITWMyQwtWSrvsKuTSa88.0q', 'albert.fazakas@movidius.com', 'Albert', 'Fazakas', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3022, 1959, 'mircea.ionica@movidius.com', '$2a$08$nQ.P6rHv3EV3zZ8jKAJM9uvylvN.caDcn1uPbPZ0oUR9mf/hymQ2i', 'mircea.ionica@movidius.com', '', 'ircea', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3023, 1959, 'David.Moloney@movidius.com', '$2a$08$EqeeLi23P0uqlXnj6SZJpOnVv6TqkecrtGVMRxNbGctjhKkbZaRfK', 'David.Moloney@movidius.com', 'David', 'Moloney', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3024, 1959, 'sean.mitchell@movidius.com', '$2a$08$5bB9AvQfYWglt8n4AdupFeb/C1h/R1cOzrnPkE00Eron3QLdJcDnS', 'sean.mitchell@movidius.com', 'Sean', 'Mitchell', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3025, 1959, 'Vasile.Toma@movidius.com', '$2a$08$4yAQlVSyEEZiTBz56IjQ0eV3rtHByimhyFDzhTwXIk2PLQAN6kApy', 'Vasile.Toma@movidius.com', 'Vasile', 'Toma', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3026, 1959, 'valentin.muresan@movidius.com', '$2a$08$fExcs4xB9y1PqBtKFvVQguPtlt4ZbYRYM7G6zEST2kodAGA5vLyVK', 'valentin.muresan@movidius.com', 'Val', 'Muresan', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3027, 1959, 'andrei.lupas@movidius.com', '$2a$08$nJIjhBw2vQ58dhn0SJRP1uwwLfOrOagmRjDBzF7QdB9kl8y1Wve8K', 'andrei.lupas@movidius.com', 'Andrei', 'Lupas', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3028, 1959, 'fergal.connor@movidius.com', '$2a$08$57h6ibYAeo5DMq71IKRycevNhgDNNPGmiYZZ0ngii6h0Buei9ZSeG', 'fergal.connor@movidius.com', 'Fergal', 'Connor', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3029, 1959, 'cormac.brick@movidius.com', '$2a$08$ZF1qNKWcLFhZHhYB3TnPy.rkfD5iAiy.BeozzNLtv6fFKJxBG88Iq', 'cormac.brick@movidius.com', 'Cormac', 'Brick', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL),
(3030, 1959, 'brendan.barry@movidius.com', '$2a$08$zfOT9QQU6l1HUay8V/UgXOvbn73O6mj359WQqHLr4lat.dFUbJ0ZG', 'brendan.barry@movidius.com', 'Brendan', 'Barry', NULL, 50, 'code#summary#priority#status#created#type#updated#reporter#assignee', 0, 0, 0, 0, 0, '2014-06-26 14:00:23', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workflow`
--

CREATE TABLE IF NOT EXISTS `workflow` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `issue_type_scheme_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` mediumtext NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1998 ;

--
-- Dumping data for table `workflow`
--

INSERT INTO `workflow` (`id`, `client_id`, `issue_type_scheme_id`, `name`, `description`, `date_created`, `date_updated`) VALUES
(1976, 1940, 3911, 'Default Yongo Workflow', 'Default Yongo Workflow', '2014-05-08 19:17:02', NULL),
(1978, 1942, 3915, 'Default Yongo Workflow', 'Default Yongo Workflow', '2014-05-08 19:17:02', NULL),
(1980, 1944, 3919, 'Default Yongo Workflow', 'Default Yongo Workflow', '2014-05-08 19:17:02', NULL),
(1997, 1959, 3949, 'Movidius Workflow', '', '2014-06-26 14:00:20', '2014-06-26 12:06:31');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_condition_data`
--

CREATE TABLE IF NOT EXISTS `workflow_condition_data` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_data_id` bigint(20) unsigned NOT NULL,
  `definition_data` varchar(200) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23621 ;

--
-- Dumping data for table `workflow_condition_data`
--

INSERT INTO `workflow_condition_data` (`id`, `workflow_data_id`, `definition_data`) VALUES
(1, 2, '(cond_id=1)'),
(2, 3, '(perm_id=7[[AND]]perm_id=8)'),
(3, 4, '(perm_id=7)'),
(4, 5, '(cond_id=1)'),
(5, 6, '(perm_id=7)'),
(6, 7, '(perm_id=7[[AND]]perm_id=8)'),
(7, 8, '(perm_id=8)'),
(8, 9, '(perm_id=7)'),
(9, 10, '(perm_id=7)'),
(10, 11, '(perm_id=7[[AND]]perm_id=8)'),
(11, 12, '(cond_id=1)'),
(12, 13, '(perm_id=7)'),
(25, 28, '(cond_id=1)'),
(26, 29, '(perm_id=7[[AND]]perm_id=8)'),
(27, 30, '(perm_id=7)'),
(28, 31, '(cond_id=1)'),
(29, 32, '(perm_id=7)'),
(30, 33, '(perm_id=7[[AND]]perm_id=8)'),
(31, 34, '(perm_id=8)'),
(32, 35, '(perm_id=7)'),
(33, 36, '(perm_id=7)'),
(34, 37, '(perm_id=7[[AND]]perm_id=8)'),
(35, 38, '(cond_id=1)'),
(36, 39, '(perm_id=7)'),
(37, 41, '(cond_id=1)'),
(38, 42, '(perm_id=7[[AND]]perm_id=8)'),
(39, 43, '(perm_id=7)'),
(40, 44, '(cond_id=1)'),
(41, 45, '(perm_id=7)'),
(42, 46, '(perm_id=7[[AND]]perm_id=8)'),
(43, 47, '(perm_id=8)'),
(44, 48, '(perm_id=7)'),
(45, 49, '(perm_id=7)'),
(46, 50, '(perm_id=7[[AND]]perm_id=8)'),
(47, 51, '(cond_id=1)'),
(48, 52, '(perm_id=7)'),
(49, 54, '(cond_id=1)'),
(50, 55, '(perm_id=7[[AND]]perm_id=8)'),
(51, 56, '(perm_id=7)'),
(52, 57, '(cond_id=1)'),
(53, 58, '(perm_id=7)'),
(54, 59, '(perm_id=7[[AND]]perm_id=8)'),
(55, 60, '(perm_id=8)'),
(56, 61, '(perm_id=7)'),
(57, 62, '(perm_id=7)'),
(58, 63, '(perm_id=7[[AND]]perm_id=8)'),
(59, 64, '(cond_id=1)'),
(60, 65, '(perm_id=7)'),
(61, 67, '(cond_id=1)'),
(62, 68, '(perm_id=7[[AND]]perm_id=8)'),
(63, 69, '(perm_id=7)'),
(64, 70, '(cond_id=1)'),
(65, 71, '(perm_id=7)'),
(66, 72, '(perm_id=7[[AND]]perm_id=8)'),
(67, 73, '(perm_id=8)'),
(68, 74, '(perm_id=7)'),
(69, 75, '(perm_id=7)'),
(70, 76, '(perm_id=7[[AND]]perm_id=8)'),
(71, 77, '(cond_id=1)'),
(72, 78, '(perm_id=7)'),
(73, 80, '(cond_id=1)'),
(74, 81, '(perm_id=7[[AND]]perm_id=8)'),
(75, 82, '(perm_id=7)'),
(76, 83, '(cond_id=1)'),
(77, 84, '(perm_id=7)'),
(78, 85, '(perm_id=7[[AND]]perm_id=8)'),
(79, 86, '(perm_id=8)'),
(80, 87, '(perm_id=7)'),
(81, 88, '(perm_id=7)'),
(82, 89, '(perm_id=7[[AND]]perm_id=8)'),
(83, 90, '(cond_id=1)'),
(84, 91, '(perm_id=7)'),
(85, 93, '(cond_id=1)'),
(86, 94, '(perm_id=7[[AND]]perm_id=8)'),
(87, 95, '(perm_id=7)'),
(88, 96, '(cond_id=1)'),
(89, 97, '(perm_id=7)'),
(90, 98, '(perm_id=7[[AND]]perm_id=8)'),
(91, 99, '(perm_id=8)'),
(92, 100, '(perm_id=7)'),
(93, 101, '(perm_id=7)'),
(94, 102, '(perm_id=7[[AND]]perm_id=8)'),
(95, 103, '(cond_id=1)'),
(96, 104, '(perm_id=7)'),
(97, 106, '(cond_id=1)'),
(98, 107, '(perm_id=7[[AND]]perm_id=8)'),
(99, 108, '(perm_id=7)'),
(100, 109, '(cond_id=1)'),
(101, 110, '(perm_id=7)'),
(102, 111, '(perm_id=7[[AND]]perm_id=8)'),
(103, 112, '(perm_id=8)'),
(104, 113, '(perm_id=7)'),
(105, 114, '(perm_id=7)'),
(106, 115, '(perm_id=7[[AND]]perm_id=8)'),
(107, 116, '(cond_id=1)'),
(108, 117, '(perm_id=7)'),
(109, 119, '(cond_id=1)'),
(110, 120, '(perm_id=7[[AND]]perm_id=8)'),
(111, 121, '(perm_id=7)'),
(112, 122, '(cond_id=1)'),
(113, 123, '(perm_id=7)'),
(114, 124, '(perm_id=7[[AND]]perm_id=8)'),
(115, 125, '(perm_id=8)'),
(116, 126, '(perm_id=7)'),
(117, 127, '(perm_id=7)'),
(118, 128, '(perm_id=7[[AND]]perm_id=8)'),
(119, 129, '(cond_id=1)'),
(120, 130, '(perm_id=7)'),
(205, 223, '(cond_id=1)'),
(206, 224, '(perm_id=7[[AND]]perm_id=8)'),
(207, 225, '(perm_id=7)'),
(208, 226, '(cond_id=1)'),
(209, 227, '(perm_id=7)'),
(210, 228, '(perm_id=7[[AND]]perm_id=8)'),
(211, 229, '(perm_id=8)'),
(212, 230, '(perm_id=7)'),
(213, 231, '(perm_id=7)'),
(214, 232, '(perm_id=7[[AND]]perm_id=8)'),
(215, 233, '(cond_id=1)'),
(216, 234, '(perm_id=7)'),
(241, 262, '(cond_id=1)'),
(242, 263, '(perm_id=7[[AND]]perm_id=8)'),
(243, 264, '(perm_id=7)'),
(244, 265, '(cond_id=1)'),
(245, 266, '(perm_id=7)'),
(246, 267, '(perm_id=7[[AND]]perm_id=8)'),
(247, 268, '(perm_id=8)'),
(248, 269, '(perm_id=7)'),
(249, 270, '(perm_id=7)'),
(250, 271, '(perm_id=7[[AND]]perm_id=8)'),
(251, 272, '(cond_id=1)'),
(252, 273, '(perm_id=7)'),
(445, 483, '(cond_id=1)'),
(446, 484, '(perm_id=7[[AND]]perm_id=8)'),
(447, 485, '(perm_id=7)'),
(448, 486, '(cond_id=1)'),
(449, 487, '(perm_id=7)'),
(450, 488, '(perm_id=7[[AND]]perm_id=8)'),
(451, 489, '(perm_id=8)'),
(452, 490, '(perm_id=7)'),
(453, 491, '(perm_id=7)'),
(454, 492, '(perm_id=7[[AND]]perm_id=8)'),
(455, 493, '(cond_id=1)'),
(456, 494, '(perm_id=7)'),
(513, 557, '(perm_id=7)'),
(514, 558, '(perm_id=7[[AND]]perm_id=8)'),
(515, 559, '(cond_id=1)'),
(516, 560, '(perm_id=7)'),
(521, 575, '(perm_id=7)'),
(522, 576, '(perm_id=7[[AND]]perm_id=8)'),
(529, 584, '(cond_id=1)'),
(530, 585, '(perm_id=7[[AND]]perm_id=8)'),
(531, 586, '(perm_id=7)'),
(532, 587, '(cond_id=1)'),
(533, 588, '(perm_id=7)'),
(534, 589, '(perm_id=7[[AND]]perm_id=8)'),
(535, 590, '(perm_id=8)'),
(536, 591, '(perm_id=7)'),
(537, 592, '(perm_id=7)'),
(538, 593, '(perm_id=7[[AND]]perm_id=8)'),
(539, 594, '(cond_id=1)'),
(540, 595, '(perm_id=7)'),
(601, 672, '(cond_id=1)'),
(602, 673, '(perm_id=7[[AND]]perm_id=8)'),
(603, 674, '(perm_id=7)'),
(604, 675, '(cond_id=1)'),
(605, 676, '(perm_id=7)'),
(606, 677, '(perm_id=7[[AND]]perm_id=8)'),
(607, 678, '(perm_id=8)'),
(608, 679, '(perm_id=7)'),
(609, 680, '(perm_id=7)'),
(610, 681, '(perm_id=7[[AND]]perm_id=8)'),
(611, 682, '(cond_id=1)'),
(612, 683, '(perm_id=7)'),
(676, 754, '(cond_id=1)'),
(679, 757, '(perm_id=8)'),
(733, 826, '(perm_id=7)'),
(734, 827, '(perm_id=8)'),
(735, 828, '(perm_id=7)'),
(738, 829, '(perm_id=8)'),
(739, 830, '(perm_id=7)'),
(740, 831, '(perm_id=8)'),
(741, 840, '(perm_id=7[[AND]]perm_id=8)'),
(742, 839, '(perm_id=7[[AND]]perm_id=8)'),
(743, 837, '(perm_id=7[[AND]]perm_id=8)'),
(744, 833, '(perm_id=7[[AND]]perm_id=8)'),
(745, 845, '(perm_id=7[[AND]]perm_id=8)'),
(746, 834, '(perm_id=7)'),
(747, 835, '(perm_id=8)'),
(748, 836, '(perm_id=7)'),
(749, 841, '(perm_id=7[[AND]]perm_id=8)'),
(750, 842, '(perm_id=7)'),
(751, 843, '(perm_id=8)'),
(752, 844, '(perm_id=7)'),
(753, 847, '(perm_id=7)'),
(754, 848, '(perm_id=8)'),
(755, 849, '(perm_id=7)'),
(756, 850, '(perm_id=8)'),
(757, 851, '(perm_id=7)'),
(758, 852, '(perm_id=8)'),
(759, 853, '(perm_id=7[[AND]]perm_id=8)'),
(760, 854, '(perm_id=7)'),
(761, 855, '(perm_id=8)'),
(762, 856, '(perm_id=7)'),
(763, 857, '(perm_id=7[[AND]]perm_id=8)'),
(764, 858, '(perm_id=7[[AND]]perm_id=8)'),
(765, 859, '(perm_id=7[[AND]]perm_id=8)'),
(766, 860, '(perm_id=7[[AND]]perm_id=8)'),
(767, 861, '(perm_id=7)'),
(768, 862, '(perm_id=8)'),
(769, 863, '(perm_id=7)'),
(770, 864, '(perm_id=7[[AND]]perm_id=8)'),
(771, 872, '(perm_id=7)'),
(778, 881, '(perm_id=8)'),
(795, 892, '(perm_id=7[[AND]]perm_id=8)'),
(814, 908, '(perm_id=7[[AND]]perm_id=8)'),
(815, 909, '(perm_id=7[[AND]]perm_id=8)'),
(816, 910, '(perm_id=7[[AND]]perm_id=8)'),
(817, 911, '(perm_id=7[[AND]]perm_id=8)'),
(818, 912, '(perm_id=7[[AND]]perm_id=8)'),
(2009, 2217, '(cond_id=1)'),
(2010, 2218, '(perm_id=7[[AND]]perm_id=8)'),
(2011, 2219, '(perm_id=7)'),
(2012, 2220, '(cond_id=1)'),
(2013, 2221, '(perm_id=7)'),
(2014, 2222, '(perm_id=7[[AND]]perm_id=8)'),
(2015, 2223, '(perm_id=8)'),
(2016, 2224, '(perm_id=7)'),
(2017, 2225, '(perm_id=7)'),
(2018, 2226, '(perm_id=7[[AND]]perm_id=8)'),
(2019, 2227, '(cond_id=1)'),
(2020, 2228, '(perm_id=7)'),
(2261, 2493, '(cond_id=1)'),
(2264, 2496, '(cond_id=1)'),
(2265, 2497, '(perm_id=7)'),
(2266, 2498, '(perm_id=7[[AND]]perm_id=8)'),
(2271, 2503, '(cond_id=1)'),
(2417, 2662, '(cond_id=1)'),
(2418, 2663, '(perm_id=7[[AND]]perm_id=8)'),
(2419, 2664, '(perm_id=7)'),
(2420, 2665, '(cond_id=1)'),
(2421, 2666, '(perm_id=7)'),
(2422, 2667, '(perm_id=7[[AND]]perm_id=8)'),
(2423, 2668, '(perm_id=8)'),
(2424, 2669, '(perm_id=7)'),
(2425, 2670, '(perm_id=7)'),
(2426, 2671, '(perm_id=7[[AND]]perm_id=8)'),
(2427, 2672, '(cond_id=1)'),
(2428, 2673, '(perm_id=7)'),
(23381, 25433, '(cond_id=1)'),
(23382, 25434, '(perm_id=7[[AND]]perm_id=8)'),
(23383, 25435, '(perm_id=7)'),
(23384, 25436, '(cond_id=1)'),
(23385, 25437, '(perm_id=7)'),
(23386, 25438, '(perm_id=7[[AND]]perm_id=8)'),
(23387, 25439, '(perm_id=8)'),
(23388, 25440, '(perm_id=7)'),
(23389, 25441, '(perm_id=7)'),
(23390, 25442, '(perm_id=7[[AND]]perm_id=8)'),
(23391, 25443, '(cond_id=1)'),
(23392, 25444, '(perm_id=7)'),
(23405, 25459, '(cond_id=1)'),
(23406, 25460, '(perm_id=7[[AND]]perm_id=8)'),
(23407, 25461, '(perm_id=7)'),
(23408, 25462, '(cond_id=1)'),
(23409, 25463, '(perm_id=7)'),
(23410, 25464, '(perm_id=7[[AND]]perm_id=8)'),
(23411, 25465, '(perm_id=8)'),
(23412, 25466, '(perm_id=7)'),
(23413, 25467, '(perm_id=7)'),
(23414, 25468, '(perm_id=7[[AND]]perm_id=8)'),
(23415, 25469, '(cond_id=1)'),
(23416, 25470, '(perm_id=7)'),
(23429, 25485, '(cond_id=1)'),
(23430, 25486, '(perm_id=7[[AND]]perm_id=8)'),
(23431, 25487, '(perm_id=7)'),
(23432, 25488, '(cond_id=1)'),
(23433, 25489, '(perm_id=7)'),
(23434, 25490, '(perm_id=7[[AND]]perm_id=8)'),
(23435, 25491, '(perm_id=8)'),
(23436, 25492, '(perm_id=7)'),
(23437, 25493, '(perm_id=7)'),
(23438, 25494, '(perm_id=7[[AND]]perm_id=8)'),
(23439, 25495, '(cond_id=1)'),
(23440, 25496, '(perm_id=7)'),
(23609, 25682, '(cond_id=1)'),
(23610, 25683, '(perm_id=7[[AND]]perm_id=8)'),
(23611, 25684, '(perm_id=7)'),
(23612, 25685, '(cond_id=1)'),
(23613, 25686, '(perm_id=7)'),
(23614, 25687, '(perm_id=7[[AND]]perm_id=8)'),
(23615, 25688, '(perm_id=8)'),
(23616, 25689, '(perm_id=7)'),
(23617, 25690, '(perm_id=7)'),
(23618, 25691, '(perm_id=7[[AND]]perm_id=8)'),
(23619, 25692, '(cond_id=1)'),
(23620, 25693, '(perm_id=7)');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_data`
--

CREATE TABLE IF NOT EXISTS `workflow_data` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_id` bigint(20) unsigned NOT NULL,
  `screen_id` bigint(20) unsigned DEFAULT NULL,
  `workflow_step_id_from` bigint(20) unsigned DEFAULT NULL,
  `workflow_step_id_to` bigint(20) unsigned DEFAULT NULL,
  `transition_name` varchar(50) DEFAULT NULL,
  `transition_description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25712 ;

--
-- Dumping data for table `workflow_data`
--

INSERT INTO `workflow_data` (`id`, `workflow_id`, `screen_id`, `workflow_step_id_from`, `workflow_step_id_to`, `transition_name`, `transition_description`) VALUES
(2661, 0, NULL, 1246, 1247, 'Create Issue', ''),
(2662, 0, NULL, 1247, 1248, 'Start Progress', ''),
(2663, 0, 589, 1247, 1249, 'Close Issue', ''),
(2664, 0, 589, 1247, 1250, 'Resolve Issue', ''),
(2665, 0, NULL, 1248, 1247, 'Stop Progress', ''),
(2666, 0, 589, 1248, 1250, 'Resolve Issue', ''),
(2667, 0, 589, 1248, 1249, 'Close Issue', ''),
(2668, 0, 590, 1250, 1249, 'Close Issue', ''),
(2669, 0, 590, 1250, 1251, 'Reopen Issue', ''),
(2670, 0, 589, 1251, 1250, 'Resolve Issue', ''),
(2671, 0, 589, 1251, 1249, 'Close Issue', ''),
(2672, 0, NULL, 1251, 1248, 'Start Progress', ''),
(2673, 0, 590, 1249, 1251, 'Reopen Issue', ''),
(25432, 1976, NULL, 11798, 11799, 'Create Issue', ''),
(25433, 1976, NULL, 11799, 11800, 'Start Progress', ''),
(25434, 1976, 5828, 11799, 11801, 'Close Issue', ''),
(25435, 1976, 5828, 11799, 11802, 'Resolve Issue', ''),
(25436, 1976, NULL, 11800, 11799, 'Stop Progress', ''),
(25437, 1976, 5828, 11800, 11802, 'Resolve Issue', ''),
(25438, 1976, 5828, 11800, 11801, 'Close Issue', ''),
(25439, 1976, 5829, 11802, 11801, 'Close Issue', ''),
(25440, 1976, 5829, 11802, 11803, 'Reopen Issue', ''),
(25441, 1976, 5828, 11803, 11802, 'Resolve Issue', ''),
(25442, 1976, 5828, 11803, 11801, 'Close Issue', ''),
(25443, 1976, NULL, 11803, 11800, 'Start Progress', ''),
(25444, 1976, 5829, 11801, 11803, 'Reopen Issue', ''),
(25458, 1978, NULL, 11810, 11811, 'Create Issue', ''),
(25459, 1978, NULL, 11811, 11812, 'Start Progress', ''),
(25460, 1978, 5834, 11811, 11813, 'Close Issue', ''),
(25461, 1978, 5834, 11811, 11814, 'Resolve Issue', ''),
(25462, 1978, NULL, 11812, 11811, 'Stop Progress', ''),
(25463, 1978, 5834, 11812, 11814, 'Resolve Issue', ''),
(25464, 1978, 5834, 11812, 11813, 'Close Issue', ''),
(25465, 1978, 5835, 11814, 11813, 'Close Issue', ''),
(25466, 1978, 5835, 11814, 11815, 'Reopen Issue', ''),
(25467, 1978, 5834, 11815, 11814, 'Resolve Issue', ''),
(25468, 1978, 5834, 11815, 11813, 'Close Issue', ''),
(25469, 1978, NULL, 11815, 11812, 'Start Progress', ''),
(25470, 1978, 5835, 11813, 11815, 'Reopen Issue', ''),
(25484, 1980, NULL, 11822, 11823, 'Create Issue', ''),
(25485, 1980, NULL, 11823, 11824, 'Start Progress', ''),
(25486, 1980, 5840, 11823, 11825, 'Close Issue', ''),
(25487, 1980, 5840, 11823, 11826, 'Resolve Issue', ''),
(25488, 1980, NULL, 11824, 11823, 'Stop Progress', ''),
(25489, 1980, 5840, 11824, 11826, 'Resolve Issue', ''),
(25490, 1980, 5840, 11824, 11825, 'Close Issue', ''),
(25491, 1980, 5841, 11826, 11825, 'Close Issue', ''),
(25492, 1980, 5841, 11826, 11827, 'Reopen Issue', ''),
(25493, 1980, 5840, 11827, 11826, 'Resolve Issue', ''),
(25494, 1980, 5840, 11827, 11825, 'Close Issue', ''),
(25495, 1980, NULL, 11827, 11824, 'Start Progress', ''),
(25496, 1980, 5841, 11825, 11827, 'Reopen Issue', ''),
(25681, 1997, NULL, 11920, 11921, 'Create Issue', ''),
(25688, 1997, 5887, 11924, 11923, 'Close Issue', ''),
(25689, 1997, 5887, 11924, 11925, 'Reopen Issue', ''),
(25693, 1997, 5887, 11923, 11925, 'Reopen Issue', ''),
(25694, 1997, 5885, 11926, 11921, 'Open as new', ''),
(25695, 1997, 5885, 11923, 11924, 'Resolved', ''),
(25696, 1997, 5885, 11923, 11926, 'Unconfirmed', ''),
(25697, 1997, 5885, 11927, 11923, 'Closed', ''),
(25698, 1997, 5886, 11927, 11924, 'Resolved', ''),
(25699, 1997, 5885, 11927, 11925, 'Reopened', ''),
(25700, 1997, 5885, 11927, 11926, 'Unconfirmed', ''),
(25701, 1997, 5885, 11924, 11927, 'Verified', ''),
(25702, 1997, 5885, 11924, 11926, 'Unconfirmed', ''),
(25703, 1997, 5885, 11928, 11921, 'New', ''),
(25704, 1997, 5885, 11928, 11924, 'Resolved', ''),
(25706, 1997, 5885, 11925, 11924, 'Resolved', ''),
(25707, 1997, 5885, 11925, 11928, 'Assign', ''),
(25708, 1997, 5885, 11925, 11921, 'New', ''),
(25709, 1997, 5885, 11921, 11928, 'Assign', ''),
(25710, 1997, 5888, 11921, 11926, 'Confirm', ''),
(25711, 1997, 5885, 11921, 11924, 'Resolved', '');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_position`
--

CREATE TABLE IF NOT EXISTS `workflow_position` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_id` bigint(20) unsigned NOT NULL,
  `workflow_step_id` bigint(20) unsigned NOT NULL,
  `top_position` int(10) unsigned NOT NULL,
  `left_position` int(10) unsigned NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11997 ;

--
-- Dumping data for table `workflow_position`
--

INSERT INTO `workflow_position` (`id`, `workflow_id`, `workflow_step_id`, `top_position`, `left_position`) VALUES
(1312, 0, 1246, 29, 509),
(1313, 0, 1247, 190, 471),
(1314, 0, 1249, 736, 305),
(1315, 0, 1250, 277, 886),
(1316, 0, 1251, 438, 598),
(1317, 0, 1248, 680, 867),
(11873, 1976, 11798, 29, 509),
(11874, 1976, 11799, 190, 471),
(11875, 1976, 11801, 736, 305),
(11876, 1976, 11802, 277, 886),
(11877, 1976, 11803, 438, 598),
(11878, 1976, 11800, 680, 867),
(11885, 1978, 11810, 29, 509),
(11886, 1978, 11811, 190, 471),
(11887, 1978, 11813, 736, 305),
(11888, 1978, 11814, 277, 886),
(11889, 1978, 11815, 438, 598),
(11890, 1978, 11812, 680, 867),
(11897, 1980, 11822, 29, 509),
(11898, 1980, 11823, 190, 471),
(11899, 1980, 11825, 736, 305),
(11900, 1980, 11826, 277, 886),
(11901, 1980, 11827, 438, 598),
(11902, 1980, 11824, 680, 867),
(11991, 1997, 11920, 29, 509),
(11992, 1997, 11921, 190, 471),
(11993, 1997, 11923, 736, 305),
(11994, 1997, 11924, 277, 886),
(11995, 1997, 11925, 438, 598),
(11996, 1997, 11922, 680, 867);

-- --------------------------------------------------------

--
-- Table structure for table `workflow_post_function_data`
--

CREATE TABLE IF NOT EXISTS `workflow_post_function_data` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_data_id` bigint(20) unsigned NOT NULL,
  `sys_workflow_post_function_id` bigint(20) unsigned NOT NULL,
  `definition_data` varchar(250) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=84851 ;

--
-- Dumping data for table `workflow_post_function_data`
--

INSERT INTO `workflow_post_function_data` (`id`, `workflow_data_id`, `sys_workflow_post_function_id`, `definition_data`) VALUES
(1836, 557, 2, 'set_issue_status'),
(1837, 557, 3, 'update_issue_history'),
(1838, 557, 5, 'event=508'),
(1839, 558, 2, 'set_issue_status'),
(1840, 558, 3, 'update_issue_history'),
(1841, 558, 5, 'event=509'),
(1842, 559, 1, 'field_name=resolution###field_value=-1'),
(1843, 559, 2, 'set_issue_status'),
(1844, 559, 3, 'update_issue_history'),
(1845, 559, 5, 'event=514'),
(1846, 560, 1, 'field_name=resolution###field_value=-1'),
(1847, 560, 2, 'set_issue_status'),
(1848, 560, 3, 'update_issue_history'),
(1849, 560, 5, 'event=512'),
(1862, 566, 2, 'set_issue_status'),
(1863, 566, 3, 'update_issue_history'),
(1864, 566, 5, 'event=516'),
(1865, 567, 2, 'set_issue_status'),
(1866, 567, 3, 'update_issue_history'),
(1867, 567, 5, 'event=516'),
(1890, 575, 2, 'set_issue_status'),
(1891, 575, 3, 'update_issue_history'),
(1892, 575, 5, 'event=520'),
(1893, 576, 2, 'set_issue_status'),
(1894, 576, 3, 'update_issue_history'),
(1895, 576, 5, 'event=521'),
(2475, 754, 1, 'field_name=resolution###field_value=-1'),
(2476, 754, 2, 'set_issue_status'),
(2477, 754, 3, 'update_issue_history'),
(2478, 754, 5, 'event=660'),
(2485, 757, 2, 'set_issue_status'),
(2486, 757, 3, 'update_issue_history'),
(2487, 757, 5, 'event=654'),
(2518, 767, 2, 'set_issue_status'),
(2519, 767, 3, 'update_issue_history'),
(2520, 767, 5, 'event=661'),
(2705, 825, 4, 'create_issue'),
(2706, 825, 5, 'event=698'),
(2727, 832, 2, 'set_issue_status'),
(2728, 832, 3, 'update_issue_history'),
(2729, 832, 5, 'event=702'),
(2881, 891, 2, 'set_issue_status'),
(2882, 891, 3, 'update_issue_history'),
(2883, 891, 5, 'event=709'),
(2884, 892, 2, 'set_issue_status'),
(2885, 892, 3, 'update_issue_history'),
(2886, 892, 5, 'event=707'),
(2945, 911, 2, 'set_issue_status'),
(2946, 911, 3, 'update_issue_history'),
(2947, 911, 5, 'event=707'),
(2948, 912, 2, 'set_issue_status'),
(2949, 912, 3, 'update_issue_history'),
(2950, 912, 5, 'event=702'),
(3430, 1060, 2, 'set_issue_status'),
(3431, 1060, 3, 'update_issue_history'),
(3432, 1060, 5, 'event=24'),
(3433, 1061, 2, 'set_issue_status'),
(3434, 1061, 3, 'update_issue_history'),
(3435, 1061, 5, 'event=24'),
(7786, 2385, 2, 'set_issue_status'),
(7787, 2385, 3, 'update_issue_history'),
(7788, 2385, 5, 'event=2066'),
(7789, 2386, 2, 'set_issue_status'),
(7790, 2386, 3, 'update_issue_history'),
(7791, 2386, 5, 'event=2066'),
(7792, 2387, 2, 'set_issue_status'),
(7793, 2387, 3, 'update_issue_history'),
(7794, 2387, 5, 'event=2066'),
(8155, 2497, 2, 'set_issue_status'),
(8156, 2497, 3, 'update_issue_history'),
(8157, 2497, 5, 'event=2166'),
(8158, 2498, 2, 'set_issue_status'),
(8159, 2498, 3, 'update_issue_history'),
(8160, 2498, 5, 'event=2167'),
(8698, 2661, 4, 'create_issue'),
(8699, 2661, 5, 'event=2319'),
(8700, 2662, 1, 'field_name=resolution###field_value=-1'),
(8701, 2662, 2, 'set_issue_status'),
(8702, 2662, 3, 'update_issue_history'),
(8703, 2662, 5, 'event=2328'),
(8704, 2663, 2, 'set_issue_status'),
(8705, 2663, 3, 'update_issue_history'),
(8706, 2663, 5, 'event=2323'),
(8707, 2664, 2, 'set_issue_status'),
(8708, 2664, 3, 'update_issue_history'),
(8709, 2664, 5, 'event=2322'),
(8710, 2665, 1, 'field_name=resolution###field_value=-1'),
(8711, 2665, 2, 'set_issue_status'),
(8712, 2665, 3, 'update_issue_history'),
(8713, 2665, 5, 'event=2329'),
(8714, 2666, 2, 'set_issue_status'),
(8715, 2666, 3, 'update_issue_history'),
(8716, 2666, 5, 'event=2322'),
(8717, 2667, 2, 'set_issue_status'),
(8718, 2667, 3, 'update_issue_history'),
(8719, 2667, 5, 'event=2323'),
(8720, 2668, 2, 'set_issue_status'),
(8721, 2668, 3, 'update_issue_history'),
(8722, 2668, 5, 'event=2323'),
(8723, 2669, 1, 'field_name=resolution###field_value=-1'),
(8724, 2669, 2, 'set_issue_status'),
(8725, 2669, 3, 'update_issue_history'),
(8726, 2669, 5, 'event=2326'),
(8727, 2670, 2, 'set_issue_status'),
(8728, 2670, 3, 'update_issue_history'),
(8729, 2670, 5, 'event=2322'),
(8730, 2671, 2, 'set_issue_status'),
(8731, 2671, 3, 'update_issue_history'),
(8732, 2671, 5, 'event=2323'),
(8733, 2672, 1, 'field_name=resolution###field_value=-1'),
(8734, 2672, 2, 'set_issue_status'),
(8735, 2672, 3, 'update_issue_history'),
(8736, 2672, 5, 'event=2328'),
(8737, 2673, 1, 'field_name=resolution###field_value=-1'),
(8738, 2673, 2, 'set_issue_status'),
(8739, 2673, 3, 'update_issue_history'),
(8740, 2673, 5, 'event=2326'),
(83471, 25285, 2, 'set_issue_status'),
(83472, 25285, 3, 'update_issue_history'),
(83473, 25285, 5, 'event=23150'),
(83937, 25432, 4, 'create_issue'),
(83938, 25432, 5, 'event=23271'),
(83939, 25433, 1, 'field_name=resolution###field_value=-1'),
(83940, 25433, 2, 'set_issue_status'),
(83941, 25433, 3, 'update_issue_history'),
(83942, 25433, 5, 'event=23280'),
(83943, 25434, 2, 'set_issue_status'),
(83944, 25434, 3, 'update_issue_history'),
(83945, 25434, 5, 'event=23275'),
(83946, 25435, 2, 'set_issue_status'),
(83947, 25435, 3, 'update_issue_history'),
(83948, 25435, 5, 'event=23274'),
(83949, 25436, 1, 'field_name=resolution###field_value=-1'),
(83950, 25436, 2, 'set_issue_status'),
(83951, 25436, 3, 'update_issue_history'),
(83952, 25436, 5, 'event=23281'),
(83953, 25437, 2, 'set_issue_status'),
(83954, 25437, 3, 'update_issue_history'),
(83955, 25437, 5, 'event=23274'),
(83956, 25438, 2, 'set_issue_status'),
(83957, 25438, 3, 'update_issue_history'),
(83958, 25438, 5, 'event=23275'),
(83959, 25439, 2, 'set_issue_status'),
(83960, 25439, 3, 'update_issue_history'),
(83961, 25439, 5, 'event=23275'),
(83962, 25440, 1, 'field_name=resolution###field_value=-1'),
(83963, 25440, 2, 'set_issue_status'),
(83964, 25440, 3, 'update_issue_history'),
(83965, 25440, 5, 'event=23278'),
(83966, 25441, 2, 'set_issue_status'),
(83967, 25441, 3, 'update_issue_history'),
(83968, 25441, 5, 'event=23274'),
(83969, 25442, 2, 'set_issue_status'),
(83970, 25442, 3, 'update_issue_history'),
(83971, 25442, 5, 'event=23275'),
(83972, 25443, 1, 'field_name=resolution###field_value=-1'),
(83973, 25443, 2, 'set_issue_status'),
(83974, 25443, 3, 'update_issue_history'),
(83975, 25443, 5, 'event=23280'),
(83976, 25444, 1, 'field_name=resolution###field_value=-1'),
(83977, 25444, 2, 'set_issue_status'),
(83978, 25444, 3, 'update_issue_history'),
(83979, 25444, 5, 'event=23278'),
(84023, 25458, 4, 'create_issue'),
(84024, 25458, 5, 'event=23295'),
(84025, 25459, 1, 'field_name=resolution###field_value=-1'),
(84026, 25459, 2, 'set_issue_status'),
(84027, 25459, 3, 'update_issue_history'),
(84028, 25459, 5, 'event=23304'),
(84029, 25460, 2, 'set_issue_status'),
(84030, 25460, 3, 'update_issue_history'),
(84031, 25460, 5, 'event=23299'),
(84032, 25461, 2, 'set_issue_status'),
(84033, 25461, 3, 'update_issue_history'),
(84034, 25461, 5, 'event=23298'),
(84035, 25462, 1, 'field_name=resolution###field_value=-1'),
(84036, 25462, 2, 'set_issue_status'),
(84037, 25462, 3, 'update_issue_history'),
(84038, 25462, 5, 'event=23305'),
(84039, 25463, 2, 'set_issue_status'),
(84040, 25463, 3, 'update_issue_history'),
(84041, 25463, 5, 'event=23298'),
(84042, 25464, 2, 'set_issue_status'),
(84043, 25464, 3, 'update_issue_history'),
(84044, 25464, 5, 'event=23299'),
(84045, 25465, 2, 'set_issue_status'),
(84046, 25465, 3, 'update_issue_history'),
(84047, 25465, 5, 'event=23299'),
(84048, 25466, 1, 'field_name=resolution###field_value=-1'),
(84049, 25466, 2, 'set_issue_status'),
(84050, 25466, 3, 'update_issue_history'),
(84051, 25466, 5, 'event=23302'),
(84052, 25467, 2, 'set_issue_status'),
(84053, 25467, 3, 'update_issue_history'),
(84054, 25467, 5, 'event=23298'),
(84055, 25468, 2, 'set_issue_status'),
(84056, 25468, 3, 'update_issue_history'),
(84057, 25468, 5, 'event=23299'),
(84058, 25469, 1, 'field_name=resolution###field_value=-1'),
(84059, 25469, 2, 'set_issue_status'),
(84060, 25469, 3, 'update_issue_history'),
(84061, 25469, 5, 'event=23304'),
(84062, 25470, 1, 'field_name=resolution###field_value=-1'),
(84063, 25470, 2, 'set_issue_status'),
(84064, 25470, 3, 'update_issue_history'),
(84065, 25470, 5, 'event=23302'),
(84109, 25484, 4, 'create_issue'),
(84110, 25484, 5, 'event=23319'),
(84111, 25485, 1, 'field_name=resolution###field_value=-1'),
(84112, 25485, 2, 'set_issue_status'),
(84113, 25485, 3, 'update_issue_history'),
(84114, 25485, 5, 'event=23328'),
(84115, 25486, 2, 'set_issue_status'),
(84116, 25486, 3, 'update_issue_history'),
(84117, 25486, 5, 'event=23323'),
(84118, 25487, 2, 'set_issue_status'),
(84119, 25487, 3, 'update_issue_history'),
(84120, 25487, 5, 'event=23322'),
(84121, 25488, 1, 'field_name=resolution###field_value=-1'),
(84122, 25488, 2, 'set_issue_status'),
(84123, 25488, 3, 'update_issue_history'),
(84124, 25488, 5, 'event=23329'),
(84125, 25489, 2, 'set_issue_status'),
(84126, 25489, 3, 'update_issue_history'),
(84127, 25489, 5, 'event=23322'),
(84128, 25490, 2, 'set_issue_status'),
(84129, 25490, 3, 'update_issue_history'),
(84130, 25490, 5, 'event=23323'),
(84131, 25491, 2, 'set_issue_status'),
(84132, 25491, 3, 'update_issue_history'),
(84133, 25491, 5, 'event=23323'),
(84134, 25492, 1, 'field_name=resolution###field_value=-1'),
(84135, 25492, 2, 'set_issue_status'),
(84136, 25492, 3, 'update_issue_history'),
(84137, 25492, 5, 'event=23326'),
(84138, 25493, 2, 'set_issue_status'),
(84139, 25493, 3, 'update_issue_history'),
(84140, 25493, 5, 'event=23322'),
(84141, 25494, 2, 'set_issue_status'),
(84142, 25494, 3, 'update_issue_history'),
(84143, 25494, 5, 'event=23323'),
(84144, 25495, 1, 'field_name=resolution###field_value=-1'),
(84145, 25495, 2, 'set_issue_status'),
(84146, 25495, 3, 'update_issue_history'),
(84147, 25495, 5, 'event=23328'),
(84148, 25496, 1, 'field_name=resolution###field_value=-1'),
(84149, 25496, 2, 'set_issue_status'),
(84150, 25496, 3, 'update_issue_history'),
(84151, 25496, 5, 'event=23326'),
(84754, 25681, 4, 'create_issue'),
(84755, 25681, 5, 'event=23499'),
(84756, 25682, 1, 'field_name=resolution###field_value=-1'),
(84757, 25682, 2, 'set_issue_status'),
(84758, 25682, 3, 'update_issue_history'),
(84759, 25682, 5, 'event=23508'),
(84760, 25683, 2, 'set_issue_status'),
(84761, 25683, 3, 'update_issue_history'),
(84762, 25683, 5, 'event=23503'),
(84763, 25684, 2, 'set_issue_status'),
(84764, 25684, 3, 'update_issue_history'),
(84765, 25684, 5, 'event=23502'),
(84766, 25685, 1, 'field_name=resolution###field_value=-1'),
(84767, 25685, 2, 'set_issue_status'),
(84768, 25685, 3, 'update_issue_history'),
(84769, 25685, 5, 'event=23509'),
(84770, 25686, 2, 'set_issue_status'),
(84771, 25686, 3, 'update_issue_history'),
(84772, 25686, 5, 'event=23502'),
(84773, 25687, 2, 'set_issue_status'),
(84774, 25687, 3, 'update_issue_history'),
(84775, 25687, 5, 'event=23503'),
(84776, 25688, 2, 'set_issue_status'),
(84777, 25688, 3, 'update_issue_history'),
(84778, 25688, 5, 'event=23503'),
(84779, 25689, 1, 'field_name=resolution###field_value=-1'),
(84780, 25689, 2, 'set_issue_status'),
(84781, 25689, 3, 'update_issue_history'),
(84782, 25689, 5, 'event=23506'),
(84783, 25690, 2, 'set_issue_status'),
(84784, 25690, 3, 'update_issue_history'),
(84785, 25690, 5, 'event=23502'),
(84786, 25691, 2, 'set_issue_status'),
(84787, 25691, 3, 'update_issue_history'),
(84788, 25691, 5, 'event=23503'),
(84789, 25692, 1, 'field_name=resolution###field_value=-1'),
(84790, 25692, 2, 'set_issue_status'),
(84791, 25692, 3, 'update_issue_history'),
(84792, 25692, 5, 'event=23508'),
(84793, 25693, 1, 'field_name=resolution###field_value=-1'),
(84794, 25693, 2, 'set_issue_status'),
(84795, 25693, 3, 'update_issue_history'),
(84796, 25693, 5, 'event=23506'),
(84797, 25694, 2, 'set_issue_status'),
(84798, 25694, 3, 'update_issue_history'),
(84799, 25694, 5, 'event=23510'),
(84800, 25695, 2, 'set_issue_status'),
(84801, 25695, 3, 'update_issue_history'),
(84802, 25695, 5, 'event=23510'),
(84803, 25696, 2, 'set_issue_status'),
(84804, 25696, 3, 'update_issue_history'),
(84805, 25696, 5, 'event=23510'),
(84806, 25697, 2, 'set_issue_status'),
(84807, 25697, 3, 'update_issue_history'),
(84808, 25697, 5, 'event=23510'),
(84809, 25698, 2, 'set_issue_status'),
(84810, 25698, 3, 'update_issue_history'),
(84811, 25698, 5, 'event=23510'),
(84812, 25699, 2, 'set_issue_status'),
(84813, 25699, 3, 'update_issue_history'),
(84814, 25699, 5, 'event=23510'),
(84815, 25700, 2, 'set_issue_status'),
(84816, 25700, 3, 'update_issue_history'),
(84817, 25700, 5, 'event=23510'),
(84818, 25701, 2, 'set_issue_status'),
(84819, 25701, 3, 'update_issue_history'),
(84820, 25701, 5, 'event=23510'),
(84821, 25702, 2, 'set_issue_status'),
(84822, 25702, 3, 'update_issue_history'),
(84823, 25702, 5, 'event=23510'),
(84824, 25703, 2, 'set_issue_status'),
(84825, 25703, 3, 'update_issue_history'),
(84826, 25703, 5, 'event=23510'),
(84827, 25704, 2, 'set_issue_status'),
(84828, 25704, 3, 'update_issue_history'),
(84829, 25704, 5, 'event=23510'),
(84830, 25705, 2, 'set_issue_status'),
(84831, 25705, 3, 'update_issue_history'),
(84832, 25705, 5, 'event=23510'),
(84833, 25706, 2, 'set_issue_status'),
(84834, 25706, 3, 'update_issue_history'),
(84835, 25706, 5, 'event=23510'),
(84836, 25707, 2, 'set_issue_status'),
(84837, 25707, 3, 'update_issue_history'),
(84838, 25707, 5, 'event=23510'),
(84839, 25708, 2, 'set_issue_status'),
(84840, 25708, 3, 'update_issue_history'),
(84841, 25708, 5, 'event=23510'),
(84842, 25709, 2, 'set_issue_status'),
(84843, 25709, 3, 'update_issue_history'),
(84844, 25709, 5, 'event=23510'),
(84845, 25710, 2, 'set_issue_status'),
(84846, 25710, 3, 'update_issue_history'),
(84847, 25710, 5, 'event=23510'),
(84848, 25711, 2, 'set_issue_status'),
(84849, 25711, 3, 'update_issue_history'),
(84850, 25711, 5, 'event=23510');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_scheme`
--

CREATE TABLE IF NOT EXISTS `workflow_scheme` (
`id` bigint(20) unsigned NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1971 ;

--
-- Dumping data for table `workflow_scheme`
--

INSERT INTO `workflow_scheme` (`id`, `client_id`, `name`, `description`, `date_created`) VALUES
(1, 1, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-04 16:37:37'),
(3, 3, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-10 15:34:05'),
(4, 4, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-16 00:08:09'),
(5, 5, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-17 14:31:15'),
(6, 6, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-21 11:23:16'),
(7, 7, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-27 15:35:58'),
(8, 9, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-27 15:40:22'),
(9, 10, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-10-30 15:41:29'),
(10, 11, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-11-22 14:21:54'),
(18, 19, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-12-05 16:05:42'),
(21, 22, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-12-06 09:11:41'),
(39, 40, 'Default Workflow Scheme', 'Default Workflow Scheme', '2012-12-21 07:07:35'),
(52, 53, 'Default Workflow Scheme', 'Default Workflow Scheme', '2013-01-16 21:25:53'),
(1951, 1940, 'Default Workflow Scheme', 'Default Workflow Scheme', '2014-05-08 19:17:02'),
(1953, 1942, 'Default Workflow Scheme', 'Default Workflow Scheme', '2014-05-08 19:17:02'),
(1955, 1944, 'Default Workflow Scheme', 'Default Workflow Scheme', '2014-05-08 19:17:02'),
(1970, 1959, 'Default Workflow Scheme', 'Default Workflow Scheme', '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_scheme_data`
--

CREATE TABLE IF NOT EXISTS `workflow_scheme_data` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_scheme_id` bigint(20) unsigned NOT NULL,
  `workflow_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1982 ;

--
-- Dumping data for table `workflow_scheme_data`
--

INSERT INTO `workflow_scheme_data` (`id`, `workflow_scheme_id`, `workflow_id`, `date_created`) VALUES
(1, 1, 1, '2012-10-04 16:37:37'),
(3, 3, 3, '2012-10-10 15:34:05'),
(4, 4, 4, '2012-10-16 00:08:09'),
(5, 5, 5, '2012-10-17 14:31:15'),
(6, 6, 6, '2012-10-21 11:23:16'),
(7, 7, 7, '2012-10-27 15:35:58'),
(8, 8, 8, '2012-10-27 15:40:22'),
(9, 9, 9, '2012-10-30 15:41:29'),
(10, 10, 10, '2012-11-22 14:21:54'),
(18, 18, 18, '2012-12-05 16:05:42'),
(21, 21, 21, '2012-12-06 09:11:41'),
(38, 39, 38, '2012-12-21 07:07:35'),
(52, 52, 54, '2013-01-16 21:25:53'),
(1962, 1951, 1976, '2014-05-08 19:17:02'),
(1964, 1953, 1978, '2014-05-08 19:17:02'),
(1966, 1955, 1980, '2014-05-08 19:17:02'),
(1981, 1970, 1997, '2014-06-26 14:00:20');

-- --------------------------------------------------------

--
-- Table structure for table `workflow_step`
--

CREATE TABLE IF NOT EXISTS `workflow_step` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_id` bigint(20) unsigned NOT NULL,
  `linked_issue_status_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `initial_step_flag` tinyint(3) unsigned DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11929 ;

--
-- Dumping data for table `workflow_step`
--

INSERT INTO `workflow_step` (`id`, `workflow_id`, `linked_issue_status_id`, `name`, `initial_step_flag`, `date_created`, `date_updated`) VALUES
(1246, 0, NULL, 'Create Issue', 1, '0000-00-00 00:00:00', NULL),
(1247, 0, 979, 'Open', 0, '0000-00-00 00:00:00', NULL),
(1248, 0, 982, 'In Progress', 0, '0000-00-00 00:00:00', NULL),
(1249, 0, 981, 'Closed', 0, '0000-00-00 00:00:00', NULL),
(1250, 0, 980, 'Resolved', 0, '0000-00-00 00:00:00', NULL),
(1251, 0, 983, 'Reopened', 0, '0000-00-00 00:00:00', NULL),
(11798, 1976, NULL, 'Create Issue', 1, '0000-00-00 00:00:00', NULL),
(11799, 1976, 9717, 'Open', 0, '0000-00-00 00:00:00', NULL),
(11800, 1976, 9720, 'In Progress', 0, '0000-00-00 00:00:00', NULL),
(11801, 1976, 9719, 'Closed', 0, '0000-00-00 00:00:00', NULL),
(11802, 1976, 9718, 'Resolved', 0, '0000-00-00 00:00:00', NULL),
(11803, 1976, 9721, 'Reopened', 0, '0000-00-00 00:00:00', NULL),
(11810, 1978, NULL, 'Create Issue', 1, '0000-00-00 00:00:00', NULL),
(11811, 1978, 9727, 'Open', 0, '0000-00-00 00:00:00', NULL),
(11812, 1978, 9730, 'In Progress', 0, '0000-00-00 00:00:00', NULL),
(11813, 1978, 9729, 'Closed', 0, '0000-00-00 00:00:00', NULL),
(11814, 1978, 9728, 'Resolved', 0, '0000-00-00 00:00:00', NULL),
(11815, 1978, 9731, 'Reopened', 0, '0000-00-00 00:00:00', NULL),
(11822, 1980, NULL, 'Create Issue', 1, '0000-00-00 00:00:00', NULL),
(11823, 1980, 9737, 'Open', 0, '0000-00-00 00:00:00', NULL),
(11824, 1980, 9740, 'In Progress', 0, '0000-00-00 00:00:00', NULL),
(11825, 1980, 9739, 'Closed', 0, '0000-00-00 00:00:00', NULL),
(11826, 1980, 9738, 'Resolved', 0, '0000-00-00 00:00:00', NULL),
(11827, 1980, 9741, 'Reopened', 0, '0000-00-00 00:00:00', NULL),
(11920, 1997, NULL, 'Create Issue', 1, '0000-00-00 00:00:00', NULL),
(11921, 1997, 9821, 'Newly created', 0, '0000-00-00 00:00:00', '2014-06-26 12:55:42'),
(11923, 1997, 9817, 'Closed', 0, '0000-00-00 00:00:00', NULL),
(11924, 1997, 9816, 'Resolved', 0, '0000-00-00 00:00:00', NULL),
(11925, 1997, 9819, 'Reopened', 0, '0000-00-00 00:00:00', NULL),
(11926, 1997, 9820, 'Unconfirmed', NULL, '2014-06-26 12:18:31', NULL),
(11927, 1997, 9823, 'Verification', NULL, '2014-06-26 13:05:42', '2014-06-26 13:08:28'),
(11928, 1997, 9822, 'Assigned', NULL, '2014-06-26 13:17:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `workflow_step_property`
--

CREATE TABLE IF NOT EXISTS `workflow_step_property` (
`id` bigint(20) unsigned NOT NULL,
  `workflow_step_id` bigint(20) unsigned NOT NULL,
  `sys_workflow_step_property_id` bigint(20) unsigned NOT NULL,
  `value` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `yongo_issue`
--

CREATE TABLE IF NOT EXISTS `yongo_issue` (
`id` bigint(20) unsigned NOT NULL,
  `priority_id` bigint(20) unsigned NOT NULL,
  `status_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned NOT NULL,
  `resolution_id` bigint(20) unsigned DEFAULT NULL,
  `user_reported_id` bigint(20) unsigned NOT NULL,
  `user_assigned_id` bigint(20) unsigned DEFAULT NULL,
  `project_id` bigint(20) unsigned NOT NULL,
  `parent_id` bigint(20) unsigned DEFAULT NULL,
  `security_scheme_level_id` bigint(20) unsigned DEFAULT NULL,
  `nr` bigint(20) unsigned NOT NULL,
  `summary` varchar(250) NOT NULL,
  `description` mediumtext,
  `environment` varchar(250) DEFAULT NULL,
  `original_estimate` varchar(20) NOT NULL,
  `remaining_estimate` varchar(20) NOT NULL,
  `helpdesk_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_created` datetime NOT NULL,
  `date_updated` datetime DEFAULT NULL,
  `date_resolved` datetime DEFAULT NULL,
  `date_due` date DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3020 ;

--
-- Dumping data for table `yongo_issue`
--

INSERT INTO `yongo_issue` (`id`, `priority_id`, `status_id`, `type_id`, `resolution_id`, `user_reported_id`, `user_assigned_id`, `project_id`, `parent_id`, `security_scheme_level_id`, `nr`, `summary`, `description`, `environment`, `original_estimate`, `remaining_estimate`, `helpdesk_flag`, `date_created`, `date_updated`, `date_resolved`, `date_due`) VALUES
(3017, 9776, 9821, 15721, NULL, 2836, 2836, 458, NULL, NULL, 3, 'test', NULL, NULL, '', '', 0, '2014-06-26 13:34:47', NULL, NULL, NULL),
(3018, 9776, 9821, 15721, NULL, 2836, NULL, 458, NULL, NULL, 4, 'aaaa', NULL, NULL, '', '', 0, '2014-06-26 13:36:34', NULL, NULL, NULL),
(3019, 9776, 9821, 15721, NULL, 2836, 2836, 458, NULL, NULL, 5, 'tessst', NULL, NULL, '', '', 0, '2014-06-26 14:48:38', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `yongo_issue_sla`
--

CREATE TABLE IF NOT EXISTS `yongo_issue_sla` (
`id` bigint(20) unsigned NOT NULL,
  `yongo_issue_id` bigint(20) unsigned NOT NULL,
  `help_sla_id` bigint(20) unsigned NOT NULL,
  `help_sla_goal_id` bigint(20) unsigned DEFAULT NULL,
  `started_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `stopped_flag` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `started_date` datetime DEFAULT NULL,
  `stopped_date` datetime DEFAULT NULL,
  `value` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `yongo_issue_watch`
--

CREATE TABLE IF NOT EXISTS `yongo_issue_watch` (
`id` bigint(20) unsigned NOT NULL,
  `yongo_issue_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=776 ;

--
-- Dumping data for table `yongo_issue_watch`
--

INSERT INTO `yongo_issue_watch` (`id`, `yongo_issue_id`, `user_id`, `date_created`) VALUES
(1, 2249, 2, '2014-03-04 20:46:06'),
(2, 2249, 3, '2014-03-04 21:00:47'),
(3, 2250, 2, '2014-03-04 21:37:50'),
(4, 2250, 3, '2014-03-04 21:37:57'),
(5, 2251, 403, '2014-03-05 12:04:41'),
(6, 2252, 389, '2014-03-05 19:06:48'),
(7, 2253, 2018, '2014-03-05 13:42:45'),
(8, 2254, 2018, '2014-03-05 13:46:10'),
(9, 2255, 389, '2014-03-05 19:51:31'),
(10, 2256, 2018, '2014-03-05 13:52:36'),
(11, 2257, 389, '2014-03-05 22:15:07'),
(12, 2258, 389, '2014-03-05 22:23:33'),
(13, 2259, 389, '2014-03-05 22:26:50'),
(14, 2260, 389, '2014-03-05 22:28:42'),
(15, 2261, 389, '2014-03-05 22:28:44'),
(16, 2262, 389, '2014-03-05 22:30:12'),
(17, 2263, 389, '2014-03-05 22:33:17'),
(18, 2264, 389, '2014-03-05 22:38:30'),
(19, 2265, 389, '2014-03-05 22:40:14'),
(20, 2266, 389, '2014-03-05 22:46:20'),
(21, 2267, 389, '2014-03-05 22:57:36'),
(22, 2268, 389, '2014-03-05 23:05:47'),
(23, 2269, 389, '2014-03-05 23:07:00'),
(24, 2270, 389, '2014-03-05 23:08:42'),
(25, 2271, 389, '2014-03-05 23:16:02'),
(26, 2272, 389, '2014-03-05 23:16:57'),
(27, 2273, 389, '2014-03-05 23:23:03'),
(28, 2274, 389, '2014-03-05 23:26:19'),
(29, 2275, 389, '2014-03-05 23:29:06'),
(30, 2276, 389, '2014-03-05 23:31:30'),
(31, 2277, 389, '2014-03-05 23:36:28'),
(32, 2278, 389, '2014-03-05 23:37:46'),
(33, 2279, 389, '2014-03-05 23:43:10'),
(34, 2280, 389, '2014-03-06 16:55:03'),
(35, 2281, 389, '2014-03-06 17:02:17'),
(37, 2282, 389, '2014-03-06 17:10:36'),
(38, 2283, 395, '2014-03-06 18:22:03'),
(39, 2284, 2, '2014-03-06 15:01:32'),
(40, 2284, 3, '2014-03-06 15:01:43'),
(41, 2284, 2018, '2014-03-06 15:01:43'),
(42, 2253, 2, '2014-03-06 15:04:20'),
(43, 2285, 2, '2014-03-06 15:17:02'),
(44, 2274, 387, '2014-03-07 13:51:24'),
(45, 2282, 395, '2014-03-07 16:06:42'),
(46, 2286, 403, '2014-03-07 14:41:09'),
(47, 2287, 403, '2014-03-07 14:41:54'),
(48, 2288, 389, '2014-03-07 21:21:36'),
(49, 2289, 389, '2014-03-07 22:09:53'),
(50, 2290, 389, '2014-03-07 22:13:18'),
(51, 2291, 389, '2014-03-07 22:38:26'),
(52, 2292, 389, '2014-03-07 22:39:18'),
(53, 2293, 389, '2014-03-07 22:44:49'),
(54, 2294, 389, '2014-03-07 23:14:44'),
(55, 2295, 389, '2014-03-07 23:17:25'),
(56, 2298, 389, '2014-03-11 16:14:55'),
(57, 2299, 389, '2014-03-11 23:22:57'),
(58, 2301, 389, '2014-03-12 18:00:05'),
(59, 2302, 389, '2014-03-12 18:05:42'),
(60, 2303, 2047, '2014-03-12 11:13:53'),
(61, 2304, 2047, '2014-03-12 11:17:27'),
(62, 2305, 2047, '2014-03-12 11:27:02'),
(63, 2306, 2047, '2014-03-12 11:37:22'),
(64, 2307, 2047, '2014-03-12 11:43:19'),
(65, 2308, 2047, '2014-03-12 11:47:31'),
(66, 2309, 2047, '2014-03-12 11:52:44'),
(68, 2311, 2047, '2014-03-12 12:29:48'),
(70, 2310, 2047, '2014-03-12 12:36:53'),
(71, 2312, 2047, '2014-03-12 12:48:46'),
(72, 2313, 2047, '2014-03-12 12:50:17'),
(73, 2314, 2047, '2014-03-12 12:51:51'),
(74, 2315, 2047, '2014-03-12 12:53:16'),
(75, 2316, 2047, '2014-03-12 12:55:37'),
(76, 2317, 2047, '2014-03-12 12:56:17'),
(77, 2318, 2047, '2014-03-12 12:56:59'),
(78, 2319, 2041, '2014-03-12 20:00:48'),
(79, 2320, 341, '2014-03-12 13:04:47'),
(80, 2321, 341, '2014-03-12 13:10:28'),
(81, 2322, 341, '2014-03-12 13:15:35'),
(82, 2323, 341, '2014-03-12 13:24:07'),
(83, 2324, 389, '2014-03-12 20:27:21'),
(84, 2325, 341, '2014-03-12 15:41:27'),
(85, 2326, 389, '2014-03-12 23:37:38'),
(86, 2327, 2084, '2014-03-12 16:46:22'),
(87, 2328, 2084, '2014-03-12 16:46:57'),
(88, 2329, 2084, '2014-03-12 16:47:12'),
(89, 2330, 2, '2014-03-12 22:11:32'),
(90, 2331, 389, '2014-03-13 20:28:56'),
(91, 2332, 389, '2014-03-13 23:17:02'),
(92, 2332, 387, '2014-03-13 23:23:18'),
(93, 2333, 2162, '2014-03-13 20:16:13'),
(94, 2334, 2164, '2014-03-13 20:32:04'),
(95, 2335, 341, '2014-03-14 11:30:18'),
(96, 2336, 341, '2014-03-14 12:05:21'),
(97, 2337, 341, '2014-03-14 12:07:17'),
(98, 2338, 341, '2014-03-14 12:15:17'),
(99, 2339, 389, '2014-03-14 19:40:53'),
(100, 2340, 389, '2014-03-14 20:34:11'),
(101, 2341, 389, '2014-03-14 22:31:05'),
(102, 2342, 405, '2014-03-17 12:29:07'),
(103, 2343, 403, '2014-03-17 14:00:04'),
(104, 2344, 403, '2014-03-17 14:00:42'),
(105, 2345, 403, '2014-03-17 14:02:03'),
(106, 2346, 403, '2014-03-17 14:04:24'),
(107, 2347, 403, '2014-03-17 14:05:08'),
(108, 2348, 403, '2014-03-17 14:06:10'),
(109, 2349, 403, '2014-03-17 14:08:02'),
(110, 2350, 403, '2014-03-17 14:10:05'),
(111, 2351, 389, '2014-03-17 21:53:56'),
(112, 2117, 329, '2014-03-17 15:37:54'),
(113, 2352, 389, '2014-03-17 23:38:01'),
(114, 2122, 329, '2014-03-17 15:38:02'),
(115, 2353, 341, '2014-03-17 17:05:05'),
(116, 2354, 389, '2014-03-18 00:05:30'),
(117, 2355, 403, '2014-03-18 15:17:20'),
(118, 2365, 389, '2014-03-18 22:33:09'),
(119, 2366, 389, '2014-03-18 22:37:59'),
(120, 2367, 389, '2014-03-18 23:20:24'),
(121, 2368, 395, '2014-03-19 19:44:42'),
(122, 2369, 389, '2014-03-19 22:38:44'),
(123, 2370, 389, '2014-03-19 23:08:35'),
(124, 2371, 389, '2014-03-20 00:13:24'),
(125, 2372, 389, '2014-03-20 16:20:14'),
(126, 2373, 395, '2014-03-20 16:30:28'),
(127, 2374, 389, '2014-03-20 16:40:29'),
(128, 2375, 2162, '2014-03-20 17:55:36'),
(129, 2376, 2162, '2014-03-20 17:56:34'),
(130, 2377, 389, '2014-03-21 19:54:29'),
(131, 2378, 389, '2014-03-22 15:35:55'),
(132, 2379, 389, '2014-03-22 15:58:32'),
(133, 2380, 389, '2014-03-22 16:03:09'),
(134, 2381, 389, '2014-03-22 17:02:17'),
(135, 2382, 389, '2014-03-22 17:07:19'),
(136, 2383, 389, '2014-03-23 01:40:14'),
(137, 2384, 389, '2014-03-24 20:37:47'),
(138, 2385, 389, '2014-03-25 00:20:02'),
(139, 2386, 2018, '2014-03-25 13:50:06'),
(140, 2387, 2018, '2014-03-25 13:50:44'),
(141, 2388, 2018, '2014-03-25 13:54:15'),
(142, 2389, 2018, '2014-03-25 13:56:19'),
(143, 2390, 2018, '2014-03-25 13:57:57'),
(144, 2391, 389, '2014-03-25 20:05:03'),
(145, 2392, 389, '2014-03-25 20:37:54'),
(146, 2393, 389, '2014-03-25 20:46:49'),
(147, 2394, 389, '2014-03-25 21:02:58'),
(148, 2395, 403, '2014-03-25 15:05:30'),
(149, 2396, 389, '2014-03-25 21:14:16'),
(150, 2397, 389, '2014-03-25 21:26:24'),
(151, 2398, 164, '2014-03-26 19:05:01'),
(152, 2399, 405, '2014-03-26 13:34:55'),
(153, 2400, 164, '2014-03-26 19:45:45'),
(154, 2401, 164, '2014-03-26 19:47:36'),
(155, 2402, 164, '2014-03-26 19:52:56'),
(156, 2403, 164, '2014-03-26 20:07:27'),
(157, 2404, 164, '2014-03-26 20:10:04'),
(158, 2405, 164, '2014-03-26 20:11:05'),
(159, 2406, 389, '2014-03-26 22:36:56'),
(160, 2407, 389, '2014-03-26 23:08:57'),
(161, 2408, 389, '2014-03-26 23:26:03'),
(162, 2409, 389, '2014-03-26 23:46:40'),
(163, 2410, 2187, '2014-03-26 20:53:40'),
(164, 2411, 2187, '2014-03-26 20:54:34'),
(165, 2412, 2187, '2014-03-26 20:55:04'),
(166, 2413, 2187, '2014-03-26 20:55:16'),
(167, 2414, 2187, '2014-03-26 20:55:41'),
(168, 2415, 2187, '2014-03-26 21:07:50'),
(169, 2416, 2187, '2014-03-26 21:08:14'),
(170, 2417, 2187, '2014-03-26 21:08:38'),
(171, 2418, 329, '2014-03-27 05:22:03'),
(172, 2419, 329, '2014-03-27 05:26:41'),
(173, 2420, 2162, '2014-03-27 11:50:27'),
(174, 2421, 164, '2014-03-27 21:17:37'),
(175, 2422, 164, '2014-03-27 21:20:04'),
(176, 2423, 164, '2014-03-27 21:21:56'),
(177, 2424, 164, '2014-03-27 21:24:35'),
(178, 2425, 389, '2014-03-27 23:22:12'),
(179, 1948, 329, '2014-03-27 19:22:30'),
(180, 2426, 2, '2014-03-27 21:44:59'),
(181, 2427, 412, '2014-03-28 11:17:19'),
(182, 2428, 164, '2014-03-28 17:23:49'),
(183, 2429, 164, '2014-03-28 17:59:11'),
(184, 2430, 164, '2014-03-28 18:02:41'),
(185, 2431, 164, '2014-03-28 18:06:50'),
(186, 2432, 164, '2014-03-28 18:12:23'),
(187, 2433, 164, '2014-03-28 18:20:28'),
(188, 2434, 164, '2014-03-28 18:24:23'),
(189, 2435, 164, '2014-03-28 18:31:43'),
(191, 2436, 2190, '2014-03-28 10:36:43'),
(192, 2437, 164, '2014-03-28 18:42:10'),
(193, 2438, 164, '2014-03-28 18:45:58'),
(194, 2439, 164, '2014-03-28 18:54:22'),
(195, 2440, 164, '2014-03-28 19:09:22'),
(196, 2441, 137, '2014-03-28 12:13:53'),
(197, 2442, 137, '2014-03-28 12:14:21'),
(198, 2443, 137, '2014-03-28 12:14:58'),
(199, 2444, 137, '2014-03-28 12:16:16'),
(200, 2445, 137, '2014-03-28 12:17:07'),
(201, 2446, 2, '2014-03-28 13:39:08'),
(202, 2447, 2182, '2014-03-28 12:03:08'),
(203, 2448, 2, '2014-03-28 15:39:45'),
(204, 2449, 2, '2014-03-28 15:40:01'),
(205, 2450, 2, '2014-03-28 15:46:06'),
(206, 2451, 2, '2014-03-28 15:46:41'),
(207, 2452, 2, '2014-03-28 15:47:15'),
(208, 2453, 164, '2014-03-29 13:32:45'),
(209, 2454, 2162, '2014-03-29 15:53:48'),
(210, 2455, 1943, '2014-04-01 02:59:05'),
(212, 2456, 1943, '2014-04-01 03:47:24'),
(213, 2457, 1943, '2014-04-01 03:49:02'),
(214, 2458, 1943, '2014-04-01 03:51:15'),
(215, 2459, 412, '2014-04-01 08:24:09'),
(216, 2460, 412, '2014-04-01 08:24:44'),
(217, 2461, 412, '2014-04-01 08:27:42'),
(218, 2462, 403, '2014-04-01 10:49:47'),
(219, 2463, 137, '2014-04-01 13:40:08'),
(220, 2464, 389, '2014-04-01 21:33:35'),
(221, 2465, 1943, '2014-04-01 17:43:37'),
(222, 2466, 1943, '2014-04-01 17:46:10'),
(223, 2467, 1943, '2014-04-01 17:47:32'),
(224, 2468, 1943, '2014-04-01 17:51:21'),
(225, 2469, 1943, '2014-04-01 17:52:15'),
(226, 2470, 1943, '2014-04-01 17:53:19'),
(227, 2471, 1943, '2014-04-01 17:54:21'),
(228, 2472, 1943, '2014-04-01 17:58:20'),
(229, 2473, 137, '2014-04-02 10:08:47'),
(230, 2474, 412, '2014-04-02 11:48:30'),
(231, 2475, 412, '2014-04-02 12:06:12'),
(232, 2476, 341, '2014-04-02 11:27:54'),
(233, 2477, 341, '2014-04-02 11:28:55'),
(234, 2478, 341, '2014-04-02 11:34:34'),
(235, 2479, 341, '2014-04-02 11:48:13'),
(236, 2480, 389, '2014-04-02 19:20:52'),
(237, 2481, 405, '2014-04-02 15:39:25'),
(238, 2492, 3, '2014-04-02 23:10:13'),
(239, 2493, 3, '2014-04-02 23:18:07'),
(240, 2494, 3, '2014-04-02 22:12:40'),
(241, 2495, 341, '2014-04-02 23:00:01'),
(242, 2496, 3, '2014-04-03 00:12:12'),
(243, 2497, 389, '2014-04-03 16:29:58'),
(244, 2498, 389, '2014-04-03 16:57:34'),
(245, 2499, 164, '2014-04-03 17:21:34'),
(246, 2500, 164, '2014-04-03 17:34:13'),
(247, 2501, 164, '2014-04-03 18:21:34'),
(248, 2502, 164, '2014-04-03 18:22:45'),
(249, 2503, 2182, '2014-04-03 16:16:10'),
(250, 2504, 2182, '2014-04-03 16:16:38'),
(251, 2505, 2182, '2014-04-03 16:17:14'),
(252, 2506, 2182, '2014-04-03 16:17:29'),
(253, 2507, 2182, '2014-04-03 16:17:43'),
(254, 2508, 2182, '2014-04-03 16:17:57'),
(255, 2509, 2182, '2014-04-03 16:18:35'),
(256, 2510, 2182, '2014-04-03 16:18:52'),
(257, 2511, 2182, '2014-04-03 16:19:07'),
(258, 2512, 2182, '2014-04-03 16:19:25'),
(259, 2513, 2182, '2014-04-03 16:19:43'),
(260, 2514, 2182, '2014-04-03 16:19:58'),
(261, 2515, 164, '2014-04-04 12:14:16'),
(262, 2516, 164, '2014-04-04 12:15:47'),
(263, 2517, 164, '2014-04-04 12:17:38'),
(264, 2518, 164, '2014-04-04 12:18:44'),
(265, 2519, 164, '2014-04-04 12:23:15'),
(266, 2520, 164, '2014-04-04 12:25:59'),
(267, 2521, 164, '2014-04-04 12:27:47'),
(268, 2522, 164, '2014-04-04 12:28:58'),
(269, 2523, 164, '2014-04-04 12:31:55'),
(270, 2524, 2041, '2014-04-07 12:44:03'),
(271, 2525, 389, '2014-04-07 16:52:51'),
(272, 2526, 389, '2014-04-07 18:24:20'),
(273, 2527, 389, '2014-04-07 21:31:26'),
(274, 2528, 389, '2014-04-08 16:12:56'),
(275, 2529, 389, '2014-04-08 17:11:51'),
(276, 2530, 389, '2014-04-08 17:28:24'),
(277, 2531, 389, '2014-04-08 17:40:53'),
(278, 2532, 389, '2014-04-08 17:59:24'),
(279, 2533, 389, '2014-04-08 18:33:14'),
(280, 2534, 389, '2014-04-08 19:37:56'),
(281, 2535, 389, '2014-04-08 19:51:34'),
(282, 2536, 389, '2014-04-08 20:21:20'),
(283, 2537, 389, '2014-04-08 20:30:56'),
(284, 2538, 389, '2014-04-08 20:33:50'),
(285, 2539, 389, '2014-04-08 21:30:56'),
(286, 2540, 389, '2014-04-08 21:46:16'),
(287, 2541, 2182, '2014-04-08 16:43:24'),
(288, 2542, 2182, '2014-04-08 17:19:04'),
(289, 2543, 2182, '2014-04-08 17:21:25'),
(290, 2544, 2182, '2014-04-08 17:22:51'),
(291, 2545, 2182, '2014-04-08 20:58:40'),
(292, 2546, 2182, '2014-04-08 20:59:07'),
(293, 2547, 2182, '2014-04-08 20:59:22'),
(294, 2548, 2182, '2014-04-08 20:59:43'),
(295, 2549, 2182, '2014-04-08 20:59:43'),
(296, 2550, 2182, '2014-04-08 21:00:14'),
(297, 2551, 2182, '2014-04-08 21:00:34'),
(298, 2552, 2182, '2014-04-08 21:00:48'),
(299, 2553, 389, '2014-04-09 16:53:08'),
(300, 2554, 389, '2014-04-09 18:13:49'),
(301, 2555, 389, '2014-04-09 18:18:11'),
(302, 2556, 389, '2014-04-09 18:46:46'),
(303, 2557, 389, '2014-04-09 21:13:01'),
(304, 2558, 389, '2014-04-09 21:46:34'),
(305, 2559, 389, '2014-04-09 22:51:02'),
(306, 2560, 3, '2014-04-10 00:24:00'),
(307, 2561, 403, '2014-04-10 12:08:06'),
(308, 2562, 403, '2014-04-10 12:09:00'),
(309, 2563, 403, '2014-04-10 12:09:39'),
(310, 2564, 403, '2014-04-10 12:10:24'),
(311, 2565, 403, '2014-04-10 12:11:11'),
(312, 2566, 403, '2014-04-10 12:16:32'),
(313, 2567, 403, '2014-04-10 12:18:17'),
(314, 2568, 403, '2014-04-10 12:19:10'),
(315, 2569, 403, '2014-04-10 12:20:00'),
(316, 2570, 403, '2014-04-10 12:20:31'),
(317, 2571, 403, '2014-04-10 12:21:09'),
(318, 2572, 403, '2014-04-10 12:22:41'),
(319, 2573, 403, '2014-04-10 12:24:25'),
(320, 2574, 403, '2014-04-10 12:26:28'),
(321, 2575, 389, '2014-04-10 20:15:52'),
(322, 2576, 389, '2014-04-10 20:19:40'),
(323, 2577, 389, '2014-04-10 20:46:07'),
(324, 2578, 389, '2014-04-10 21:23:59'),
(325, 2579, 389, '2014-04-10 22:39:51'),
(326, 2580, 389, '2014-04-10 22:40:19'),
(327, 2581, 389, '2014-04-10 23:04:16'),
(328, 2582, 341, '2014-04-10 21:51:57'),
(329, 2583, 412, '2014-04-11 11:39:21'),
(330, 2584, 389, '2014-04-11 21:06:44'),
(331, 2585, 389, '2014-04-11 21:38:15'),
(332, 2586, 389, '2014-04-11 22:35:15'),
(333, 2587, 3, '2014-04-12 12:46:56'),
(334, 2588, 389, '2014-04-14 17:45:21'),
(335, 2589, 389, '2014-04-14 18:29:45'),
(336, 2590, 389, '2014-04-14 19:05:19'),
(337, 2591, 389, '2014-04-14 19:41:45'),
(338, 2592, 389, '2014-04-14 20:08:54'),
(339, 2593, 389, '2014-04-14 20:23:44'),
(340, 2594, 389, '2014-04-14 22:13:29'),
(341, 2595, 389, '2014-04-14 22:40:49'),
(342, 2596, 389, '2014-04-14 22:55:21'),
(343, 2597, 389, '2014-04-15 15:21:00'),
(344, 2598, 389, '2014-04-15 23:32:02'),
(345, 2599, 2208, '2014-04-15 17:52:40'),
(346, 2600, 2208, '2014-04-15 17:55:41'),
(347, 2601, 2208, '2014-04-15 17:56:38'),
(348, 2602, 2208, '2014-04-15 17:58:39'),
(349, 2603, 2182, '2014-04-15 19:40:57'),
(351, 2605, 1892, '2014-04-15 23:10:53'),
(352, 2606, 2172, '2014-04-15 22:44:55'),
(353, 2607, 389, '2014-04-16 18:58:33'),
(354, 2608, 389, '2014-04-16 19:48:26'),
(355, 2609, 389, '2014-04-16 21:03:05'),
(356, 2610, 389, '2014-04-16 22:00:05'),
(357, 2611, 389, '2014-04-16 22:18:04'),
(358, 2612, 2208, '2014-04-16 17:31:27'),
(359, 2613, 2208, '2014-04-16 17:41:41'),
(360, 2614, 330, '2014-04-17 08:20:30'),
(361, 2615, 331, '2014-04-17 08:23:01'),
(362, 2616, 331, '2014-04-17 08:32:53'),
(363, 2617, 331, '2014-04-17 08:37:11'),
(364, 2618, 331, '2014-04-17 08:38:17'),
(365, 2619, 331, '2014-04-17 08:39:55'),
(366, 2620, 331, '2014-04-17 08:42:27'),
(367, 2621, 331, '2014-04-17 08:43:55'),
(368, 2622, 331, '2014-04-17 08:45:11'),
(369, 2623, 331, '2014-04-17 08:46:05'),
(370, 2624, 331, '2014-04-17 08:46:12'),
(371, 2625, 331, '2014-04-17 08:47:28'),
(372, 2626, 331, '2014-04-17 08:49:36'),
(373, 2627, 331, '2014-04-17 08:50:52'),
(374, 2628, 331, '2014-04-17 08:51:54'),
(375, 2629, 331, '2014-04-17 08:51:54'),
(376, 2630, 331, '2014-04-17 09:00:14'),
(377, 2631, 331, '2014-04-17 09:01:07'),
(378, 2632, 331, '2014-04-17 09:01:41'),
(379, 2633, 331, '2014-04-17 09:08:39'),
(380, 2634, 331, '2014-04-17 10:02:15'),
(381, 2635, 331, '2014-04-17 10:03:25'),
(382, 2636, 331, '2014-04-17 10:04:06'),
(383, 2637, 331, '2014-04-17 10:20:22'),
(384, 2638, 331, '2014-04-17 10:21:25'),
(385, 2639, 331, '2014-04-17 10:22:53'),
(386, 2640, 331, '2014-04-17 10:29:31'),
(387, 2641, 331, '2014-04-17 10:34:41'),
(388, 2642, 331, '2014-04-17 10:38:56'),
(389, 2643, 331, '2014-04-17 10:41:29'),
(390, 2644, 331, '2014-04-17 10:44:44'),
(391, 2645, 331, '2014-04-17 10:46:02'),
(392, 2646, 331, '2014-04-17 10:46:46'),
(393, 2647, 331, '2014-04-17 10:54:28'),
(394, 2648, 331, '2014-04-17 10:55:07'),
(395, 2649, 331, '2014-04-17 10:55:44'),
(396, 2650, 331, '2014-04-17 10:56:24'),
(397, 2651, 331, '2014-04-17 11:05:18'),
(398, 2652, 331, '2014-04-17 11:06:59'),
(399, 2653, 331, '2014-04-17 11:07:54'),
(400, 2654, 331, '2014-04-17 11:10:10'),
(401, 2655, 331, '2014-04-17 11:10:58'),
(402, 2656, 331, '2014-04-17 11:12:05'),
(403, 2657, 331, '2014-04-17 11:14:07'),
(404, 2658, 331, '2014-04-17 11:14:07'),
(405, 2659, 331, '2014-04-17 11:28:15'),
(406, 2660, 331, '2014-04-17 11:31:33'),
(407, 2661, 331, '2014-04-17 11:35:18'),
(408, 2662, 331, '2014-04-17 11:36:19'),
(409, 2663, 331, '2014-04-17 11:38:36'),
(410, 2664, 331, '2014-04-17 11:42:02'),
(411, 2665, 331, '2014-04-17 11:42:49'),
(412, 2666, 331, '2014-04-17 11:43:28'),
(413, 2667, 331, '2014-04-17 11:45:52'),
(414, 2668, 331, '2014-04-17 12:13:26'),
(415, 2669, 331, '2014-04-17 12:14:17'),
(416, 2670, 331, '2014-04-17 12:21:37'),
(417, 2671, 331, '2014-04-17 12:21:38'),
(418, 2672, 331, '2014-04-17 12:38:49'),
(419, 2673, 331, '2014-04-17 12:42:23'),
(420, 2674, 331, '2014-04-17 12:43:13'),
(421, 2675, 331, '2014-04-17 12:44:42'),
(422, 2676, 331, '2014-04-17 12:47:34'),
(423, 2677, 331, '2014-04-17 12:48:53'),
(424, 2678, 331, '2014-04-17 12:49:43'),
(425, 2679, 329, '2014-04-17 12:51:36'),
(426, 2680, 329, '2014-04-17 12:55:47'),
(427, 2681, 329, '2014-04-17 12:58:07'),
(428, 2682, 329, '2014-04-17 13:04:25'),
(429, 2683, 389, '2014-04-17 22:13:17'),
(430, 2684, 389, '2014-04-17 22:44:38'),
(431, 2685, 389, '2014-04-17 22:45:16'),
(432, 2686, 389, '2014-04-17 22:48:01'),
(433, 2687, 389, '2014-04-17 22:55:02'),
(434, 2688, 3, '2014-04-18 10:32:15'),
(435, 2689, 3, '2014-04-18 10:32:41'),
(436, 2690, 3, '2014-04-18 10:35:00'),
(437, 2691, 3, '2014-04-18 10:35:30'),
(438, 2692, 3, '2014-04-18 10:36:00'),
(439, 2693, 3, '2014-04-18 10:36:45'),
(440, 2694, 3, '2014-04-18 10:36:46'),
(441, 2695, 3, '2014-04-18 10:37:15'),
(442, 2696, 3, '2014-04-18 10:38:48'),
(443, 2697, 3, '2014-04-18 10:42:02'),
(444, 2698, 3, '2014-04-18 10:42:32'),
(445, 2699, 3, '2014-04-18 10:43:45'),
(446, 2700, 3, '2014-04-18 10:46:33'),
(447, 2701, 3, '2014-04-18 10:47:00'),
(448, 2702, 3, '2014-04-18 10:47:48'),
(449, 2703, 3, '2014-04-18 10:51:05'),
(450, 2704, 3, '2014-04-18 10:51:33'),
(451, 2705, 3, '2014-04-18 10:53:16'),
(452, 2706, 3, '2014-04-18 10:54:21'),
(453, 2707, 3, '2014-04-18 10:55:22'),
(454, 2708, 3, '2014-04-18 10:57:02'),
(455, 2709, 389, '2014-04-21 19:02:15'),
(456, 2710, 389, '2014-04-21 19:17:10'),
(457, 2711, 389, '2014-04-21 21:05:02'),
(458, 2712, 389, '2014-04-21 23:12:01'),
(459, 2713, 389, '2014-04-21 23:26:34'),
(460, 2714, 389, '2014-04-22 13:24:12'),
(461, 2507, 2208, '2014-04-22 09:32:31'),
(462, 2511, 2208, '2014-04-22 09:51:14'),
(463, 2514, 2208, '2014-04-22 10:15:09'),
(464, 2715, 389, '2014-04-22 17:49:27'),
(465, 2450, 2193, '2014-04-22 20:57:16'),
(466, 2716, 2193, '2014-04-23 20:59:19'),
(467, 2717, 2193, '2014-04-23 21:02:42'),
(468, 2718, 389, '2014-04-24 16:23:46'),
(469, 2719, 389, '2014-04-24 17:07:43'),
(470, 2720, 389, '2014-04-24 21:37:29'),
(471, 2721, 389, '2014-04-24 21:58:25'),
(472, 2722, 389, '2014-04-24 22:32:05'),
(473, 2723, 3, '2014-04-24 19:23:16'),
(474, 2724, 3, '2014-04-24 19:58:46'),
(475, 2725, 3, '2014-04-24 19:59:07'),
(476, 2726, 331, '2014-04-25 06:00:47'),
(477, 2727, 331, '2014-04-25 06:10:43'),
(478, 2728, 331, '2014-04-25 06:15:15'),
(479, 2729, 3, '2014-04-25 10:30:15'),
(480, 2730, 389, '2014-04-25 15:33:02'),
(481, 2731, 389, '2014-04-25 17:47:03'),
(482, 2732, 2182, '2014-04-26 18:10:52'),
(483, 2733, 2182, '2014-04-26 18:11:20'),
(484, 2734, 2182, '2014-04-26 18:11:48'),
(485, 2735, 2182, '2014-04-26 18:12:06'),
(486, 2736, 2182, '2014-04-26 18:12:22'),
(487, 2737, 2182, '2014-04-26 18:13:00'),
(488, 2738, 2182, '2014-04-27 19:22:26'),
(489, 2739, 2182, '2014-04-27 19:29:27'),
(490, 2740, 2182, '2014-04-27 19:29:43'),
(491, 2741, 2182, '2014-04-27 19:30:10'),
(492, 2742, 2182, '2014-04-27 19:30:25'),
(493, 2743, 2182, '2014-04-27 19:30:50'),
(494, 2744, 2182, '2014-04-27 19:31:21'),
(495, 2745, 2182, '2014-04-27 19:31:52'),
(496, 2746, 331, '2014-04-28 05:43:01'),
(497, 2747, 389, '2014-04-28 16:02:02'),
(498, 2748, 389, '2014-04-28 17:28:20'),
(499, 2749, 395, '2014-04-28 18:21:45'),
(500, 2750, 389, '2014-04-28 20:16:07'),
(501, 2751, 2182, '2014-04-28 15:09:11'),
(502, 2752, 2182, '2014-04-28 15:10:17'),
(503, 2753, 2182, '2014-04-28 15:12:15'),
(504, 2754, 2182, '2014-04-28 16:05:55'),
(506, 2755, 331, '2014-04-29 06:00:48'),
(507, 2756, 412, '2014-04-29 10:51:33'),
(508, 2757, 389, '2014-04-29 17:17:27'),
(509, 2758, 389, '2014-04-29 18:29:30'),
(510, 2759, 2182, '2014-04-29 14:49:33'),
(511, 2760, 2203, '2014-04-29 18:35:37'),
(512, 2761, 329, '2014-05-02 12:41:27'),
(513, 2762, 389, '2014-05-03 15:32:56'),
(514, 2763, 389, '2014-05-03 18:35:02'),
(515, 2764, 389, '2014-05-03 18:48:02'),
(516, 2765, 389, '2014-05-03 18:52:49'),
(517, 2766, 1892, '2014-05-04 11:57:21'),
(518, 2767, 1892, '2014-05-04 11:58:24'),
(519, 2768, 3, '2014-05-04 19:19:58'),
(520, 2769, 2182, '2014-05-05 16:42:46'),
(521, 2770, 2182, '2014-05-05 16:43:52'),
(522, 2771, 2182, '2014-05-05 17:32:07'),
(523, 2772, 2182, '2014-05-05 17:32:33'),
(524, 2773, 331, '2014-05-06 05:49:23'),
(525, 2774, 331, '2014-05-06 05:52:58'),
(526, 2775, 389, '2014-05-06 20:27:01'),
(527, 2776, 389, '2014-05-06 22:36:02'),
(528, 2777, 2208, '2014-05-07 08:42:11'),
(529, 2778, 2208, '2014-05-07 08:59:11'),
(530, 2779, 1892, '2014-05-07 10:58:40'),
(531, 2780, 389, '2014-05-07 18:42:26'),
(532, 2781, 389, '2014-05-07 21:35:43'),
(533, 2782, 389, '2014-05-07 21:47:53'),
(534, 2783, 389, '2014-05-07 22:06:41'),
(535, 2784, 3, '2014-05-08 22:15:22'),
(536, 2785, 2, '2014-05-09 10:48:00'),
(537, 2786, 389, '2014-05-09 19:12:29'),
(538, 2787, 331, '2014-05-12 06:29:52'),
(539, 2788, 331, '2014-05-12 06:30:56'),
(540, 2789, 331, '2014-05-12 06:36:34'),
(541, 2790, 331, '2014-05-12 06:43:20'),
(542, 2791, 403, '2014-05-12 12:46:06'),
(543, 2792, 403, '2014-05-12 13:33:19'),
(544, 2793, 403, '2014-05-12 13:38:29'),
(545, 2794, 389, '2014-05-12 18:46:04'),
(546, 2795, 331, '2014-05-12 13:28:36'),
(547, 2796, 331, '2014-05-12 13:30:33'),
(548, 2797, 331, '2014-05-12 13:32:20'),
(549, 2798, 331, '2014-05-12 13:35:59'),
(550, 2799, 331, '2014-05-12 13:48:31'),
(551, 2800, 389, '2014-05-12 23:08:11'),
(552, 2801, 2, '2014-05-14 09:58:03'),
(553, 2802, 2227, '2014-05-14 11:43:14'),
(554, 2803, 2227, '2014-05-14 12:51:47'),
(555, 2804, 2227, '2014-05-14 12:58:26'),
(556, 2805, 67, '2014-05-14 19:30:16'),
(557, 2806, 2227, '2014-05-15 10:27:32'),
(558, 2807, 389, '2014-05-15 20:12:19'),
(559, 2808, 2200, '2014-05-15 18:37:00'),
(560, 2809, 2200, '2014-05-15 18:37:04'),
(561, 2810, 2200, '2014-05-15 18:37:10'),
(562, 2811, 2200, '2014-05-15 18:37:10'),
(563, 2812, 2200, '2014-05-15 18:37:10'),
(564, 2813, 2200, '2014-05-15 18:37:11'),
(565, 2814, 2200, '2014-05-15 18:37:11'),
(566, 2815, 2200, '2014-05-15 18:37:12'),
(567, 2816, 2200, '2014-05-15 18:39:11'),
(568, 2817, 2200, '2014-05-15 18:39:39'),
(569, 2818, 2200, '2014-05-15 18:40:00'),
(570, 2819, 2, '2014-05-15 21:09:12'),
(571, 2820, 3, '2014-05-15 21:09:43'),
(572, 2821, 2200, '2014-05-15 19:23:02'),
(573, 2822, 2200, '2014-05-15 19:28:53'),
(574, 2823, 2200, '2014-05-15 19:32:14'),
(575, 2824, 389, '2014-05-16 17:15:49'),
(576, 2825, 389, '2014-05-16 21:00:40'),
(577, 2826, 389, '2014-05-16 21:02:23'),
(578, 2827, 2041, '2014-05-19 16:10:56'),
(579, 2828, 389, '2014-05-19 18:23:17'),
(580, 2829, 389, '2014-05-19 19:08:02'),
(581, 2830, 389, '2014-05-19 19:19:48'),
(582, 2831, 389, '2014-05-19 19:20:50'),
(583, 2832, 2200, '2014-05-19 17:50:56'),
(584, 2833, 389, '2014-05-20 22:57:25'),
(585, 2834, 2, '2014-05-21 11:20:01'),
(586, 2835, 160, '2014-05-21 09:30:42'),
(587, 2836, 2200, '2014-05-21 21:23:49'),
(588, 2837, 2200, '2014-05-21 21:25:15'),
(589, 2838, 2200, '2014-05-21 21:25:22'),
(590, 2839, 2200, '2014-05-21 21:25:58'),
(591, 2840, 2200, '2014-05-21 21:27:30'),
(592, 2841, 2200, '2014-05-21 21:28:15'),
(593, 2842, 2200, '2014-05-21 21:29:02'),
(594, 2843, 2200, '2014-05-21 21:31:02'),
(595, 2844, 2200, '2014-05-21 21:32:13'),
(596, 2845, 2200, '2014-05-21 21:49:39'),
(597, 2846, 2200, '2014-05-21 21:50:37'),
(598, 2847, 2200, '2014-05-21 21:52:32'),
(599, 2848, 2200, '2014-05-21 21:58:12'),
(600, 2849, 2200, '2014-05-21 22:32:24'),
(601, 2850, 136, '2014-05-22 11:29:18'),
(602, 2851, 2230, '2014-05-22 17:41:00'),
(603, 2852, 2230, '2014-05-22 19:12:16'),
(604, 2853, 403, '2014-05-23 09:01:13'),
(605, 2854, 403, '2014-05-23 09:01:21'),
(606, 2855, 403, '2014-05-23 09:01:47'),
(607, 2856, 403, '2014-05-23 09:01:55'),
(608, 2857, 403, '2014-05-23 09:01:56'),
(609, 2858, 403, '2014-05-23 09:01:57'),
(610, 2859, 403, '2014-05-23 09:01:57'),
(611, 2860, 403, '2014-05-23 09:01:58'),
(612, 2861, 403, '2014-05-23 09:01:58'),
(613, 2862, 403, '2014-05-23 09:01:58'),
(614, 2863, 403, '2014-05-23 09:01:59'),
(615, 2864, 403, '2014-05-23 09:01:59'),
(616, 2865, 403, '2014-05-23 09:02:00'),
(617, 2866, 403, '2014-05-23 09:32:21'),
(618, 2867, 403, '2014-05-23 09:34:22'),
(619, 2868, 403, '2014-05-23 09:35:37'),
(620, 2869, 403, '2014-05-23 09:39:15'),
(621, 2870, 403, '2014-05-23 09:41:33'),
(622, 2871, 389, '2014-05-23 14:51:50'),
(623, 2872, 389, '2014-05-23 15:11:32'),
(624, 2873, 389, '2014-05-23 16:19:50'),
(625, 2874, 389, '2014-05-23 17:15:49'),
(626, 2875, 1892, '2014-05-23 10:30:26'),
(627, 2876, 331, '2014-05-23 12:29:10'),
(628, 2877, 331, '2014-05-23 12:33:11'),
(629, 2878, 331, '2014-05-23 12:35:50'),
(630, 2879, 331, '2014-05-23 12:38:49'),
(631, 2880, 331, '2014-05-23 12:42:10'),
(632, 2881, 331, '2014-05-23 12:45:33'),
(633, 2882, 331, '2014-05-23 12:49:18'),
(634, 2883, 2200, '2014-05-23 20:02:51'),
(635, 2884, 2200, '2014-05-23 20:08:30'),
(636, 2885, 2200, '2014-05-23 20:38:46'),
(637, 2886, 2200, '2014-05-23 20:39:53'),
(638, 2887, 1892, '2014-05-23 21:57:21'),
(639, 2888, 2200, '2014-05-26 08:28:14'),
(640, 2889, 405, '2014-05-26 11:29:36'),
(641, 2890, 331, '2014-05-26 12:52:02'),
(642, 2891, 331, '2014-05-26 12:54:49'),
(643, 2892, 2230, '2014-05-26 16:27:26'),
(644, 2893, 2230, '2014-05-26 16:28:09'),
(645, 2893, 2232, '2014-05-26 16:28:51'),
(646, 2894, 331, '2014-05-27 07:02:18'),
(647, 2895, 389, '2014-05-27 18:25:44'),
(648, 2896, 3, '2014-05-27 17:43:15'),
(649, 2897, 403, '2014-05-28 12:59:42'),
(650, 2898, 403, '2014-05-28 13:01:36'),
(651, 2899, 403, '2014-05-28 13:05:19'),
(652, 2900, 403, '2014-05-28 13:05:55'),
(653, 2901, 403, '2014-05-28 13:09:23'),
(654, 2902, 403, '2014-05-28 13:10:37'),
(655, 2903, 403, '2014-05-28 13:39:42'),
(656, 2904, 403, '2014-05-28 13:40:26'),
(657, 2905, 389, '2014-05-28 18:47:48'),
(658, 2906, 389, '2014-05-28 23:10:49'),
(659, 2907, 389, '2014-05-28 23:22:10'),
(660, 2908, 389, '2014-05-29 21:46:21'),
(661, 2909, 389, '2014-05-30 16:59:05'),
(662, 2910, 2235, '2014-05-30 14:18:19'),
(663, 2911, 389, '2014-05-30 21:51:23'),
(664, 2912, 389, '2014-05-30 21:52:20'),
(665, 2913, 389, '2014-05-30 22:49:55'),
(666, 2914, 389, '2014-05-30 23:52:59'),
(667, 2915, 389, '2014-06-02 19:57:51'),
(668, 2916, 2200, '2014-06-02 18:43:57'),
(669, 2917, 331, '2014-06-03 05:48:22'),
(670, 2918, 331, '2014-06-03 05:49:06'),
(671, 2919, 331, '2014-06-03 05:50:19'),
(672, 2920, 331, '2014-06-03 05:50:54'),
(673, 2921, 389, '2014-06-03 17:09:22'),
(674, 2922, 2200, '2014-06-04 10:51:13'),
(675, 2923, 2200, '2014-06-04 10:51:43'),
(676, 2924, 2041, '2014-06-04 18:58:17'),
(677, 2925, 2200, '2014-06-04 14:33:27'),
(678, 2926, 2200, '2014-06-04 14:34:04'),
(679, 2927, 389, '2014-06-04 21:55:23'),
(680, 2928, 389, '2014-06-04 21:57:40'),
(681, 2929, 389, '2014-06-04 22:32:56'),
(682, 2930, 389, '2014-06-04 23:05:52'),
(683, 2931, 2200, '2014-06-04 19:32:40'),
(684, 2932, 2200, '2014-06-04 19:35:38'),
(685, 2933, 2200, '2014-06-04 19:41:23'),
(686, 2934, 3, '2014-06-04 22:18:35'),
(687, 2935, 403, '2014-06-05 09:39:17'),
(688, 2936, 3, '2014-06-05 15:16:51'),
(689, 2937, 331, '2014-06-06 05:57:09'),
(690, 2938, 331, '2014-06-06 05:59:05'),
(691, 2939, 331, '2014-06-06 06:11:43'),
(692, 2940, 331, '2014-06-06 06:12:30'),
(693, 2941, 389, '2014-06-06 18:30:24'),
(694, 2942, 389, '2014-06-06 21:55:27'),
(695, 2943, 389, '2014-06-07 17:59:40'),
(696, 2944, 389, '2014-06-07 19:01:45'),
(697, 2945, 389, '2014-06-07 19:47:57'),
(698, 2946, 389, '2014-06-07 19:48:50'),
(699, 2947, 2200, '2014-06-08 20:30:00'),
(700, 2948, 2200, '2014-06-08 20:57:47'),
(701, 2949, 2200, '2014-06-08 20:58:39'),
(702, 2950, 2200, '2014-06-08 20:59:11'),
(703, 2951, 2200, '2014-06-08 21:00:32'),
(704, 2952, 2200, '2014-06-08 21:02:32'),
(705, 2953, 389, '2014-06-09 12:15:53'),
(706, 2954, 412, '2014-06-09 12:52:42'),
(707, 2955, 389, '2014-06-10 19:02:05'),
(708, 2956, 389, '2014-06-10 19:35:13'),
(709, 2957, 331, '2014-06-10 13:13:33'),
(710, 2958, 331, '2014-06-10 13:17:49'),
(711, 2959, 331, '2014-06-10 13:21:27'),
(712, 2960, 331, '2014-06-10 13:24:39'),
(713, 2961, 389, '2014-06-10 22:59:36'),
(714, 2962, 389, '2014-06-10 23:01:47'),
(715, 2963, 389, '2014-06-10 23:22:08'),
(716, 2964, 389, '2014-06-12 18:27:27'),
(719, 2965, 1892, '2014-06-12 16:03:58'),
(720, 2966, 412, '2014-06-13 12:08:52'),
(721, 2967, 331, '2014-06-16 11:53:00'),
(722, 2968, 331, '2014-06-16 12:18:45'),
(723, 2969, 331, '2014-06-16 12:23:41'),
(724, 2970, 331, '2014-06-16 12:26:02'),
(725, 2971, 331, '2014-06-16 12:30:48'),
(726, 2972, 331, '2014-06-16 12:41:23'),
(727, 2973, 331, '2014-06-16 14:08:28'),
(728, 2974, 2238, '2014-06-17 17:16:51'),
(729, 2975, 3, '2014-06-17 23:48:12'),
(730, 2976, 3, '2014-06-17 23:50:38'),
(731, 2977, 3, '2014-06-17 23:51:22'),
(732, 2978, 3, '2014-06-17 23:51:54'),
(733, 2979, 3, '2014-06-17 23:53:23'),
(734, 2980, 3, '2014-06-17 23:56:35'),
(735, 2981, 3, '2014-06-17 23:57:09'),
(736, 2982, 3, '2014-06-17 23:58:22'),
(737, 2983, 3, '2014-06-17 23:59:18'),
(738, 2984, 403, '2014-06-18 12:45:15'),
(739, 2985, 403, '2014-06-18 12:46:18'),
(740, 2986, 403, '2014-06-18 12:47:15'),
(741, 2987, 403, '2014-06-18 12:49:35'),
(742, 2988, 403, '2014-06-18 12:52:20'),
(743, 2989, 403, '2014-06-18 12:53:26'),
(744, 2990, 389, '2014-06-19 14:15:00'),
(745, 2991, 389, '2014-06-19 14:59:01'),
(746, 2992, 389, '2014-06-19 15:00:57'),
(747, 2993, 389, '2014-06-19 15:07:56'),
(748, 2994, 389, '2014-06-19 15:10:09'),
(749, 2995, 3, '2014-06-19 23:02:28'),
(750, 2996, 389, '2014-06-20 11:27:03'),
(751, 2997, 2246, '2014-06-23 08:49:14'),
(752, 2997, 2238, '2014-06-23 09:00:40'),
(753, 2998, 389, '2014-06-23 14:16:05'),
(754, 2999, 2, '2014-06-23 17:36:32'),
(755, 3000, 389, '2014-06-23 14:38:05'),
(756, 3001, 2238, '2014-06-24 11:59:11'),
(757, 3002, 2238, '2014-06-24 12:00:08'),
(758, 3003, 389, '2014-06-24 14:38:22'),
(759, 3004, 2, '2014-06-24 18:16:48'),
(760, 3005, 2, '2014-06-24 18:29:39'),
(761, 3006, 389, '2014-06-24 15:32:02'),
(762, 3007, 2, '2014-06-24 18:32:41'),
(763, 3008, 389, '2014-06-25 06:38:36'),
(764, 3009, 389, '2014-06-25 08:15:03'),
(765, 3010, 389, '2014-06-25 09:16:55'),
(766, 3011, 389, '2014-06-25 09:17:20'),
(767, 3012, 389, '2014-06-25 11:18:10'),
(768, 3013, 3, '2014-06-26 10:15:51'),
(770, 3014, 1892, '2014-06-26 08:32:59'),
(771, 3015, 2836, '2014-06-26 12:52:59'),
(772, 3016, 2836, '2014-06-26 13:20:10'),
(773, 3017, 2836, '2014-06-26 13:34:47'),
(774, 3018, 2836, '2014-06-26 13:36:34'),
(775, 3019, 2836, '2014-06-26 14:48:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agile_board`
--
ALTER TABLE `agile_board`
 ADD PRIMARY KEY (`id`), ADD KEY `user_created_id` (`user_created_id`), ADD KEY `client_id` (`client_id`), ADD KEY `filter_id` (`filter_id`);

--
-- Indexes for table `agile_board_column`
--
ALTER TABLE `agile_board_column`
 ADD PRIMARY KEY (`id`), ADD KEY `agile_board_id` (`agile_board_id`);

--
-- Indexes for table `agile_board_column_status`
--
ALTER TABLE `agile_board_column_status`
 ADD PRIMARY KEY (`id`), ADD KEY `agile_board_column_id` (`agile_board_column_id`), ADD KEY `issue_status_id` (`issue_status_id`);

--
-- Indexes for table `agile_board_project`
--
ALTER TABLE `agile_board_project`
 ADD PRIMARY KEY (`id`), ADD KEY `agile_board_id` (`agile_board_id`), ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `agile_board_sprint`
--
ALTER TABLE `agile_board_sprint`
 ADD PRIMARY KEY (`id`), ADD KEY `agile_board_id` (`agile_board_id`), ADD KEY `user_created_id` (`user_created_id`);

--
-- Indexes for table `agile_board_sprint_issue`
--
ALTER TABLE `agile_board_sprint_issue`
 ADD PRIMARY KEY (`id`), ADD KEY `agile_board_sprint_id` (`agile_board_sprint_id`), ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `cal_calendar`
--
ALTER TABLE `cal_calendar`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cal_calendar_default_reminder`
--
ALTER TABLE `cal_calendar_default_reminder`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_calendar_share`
--
ALTER TABLE `cal_calendar_share`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event`
--
ALTER TABLE `cal_event`
 ADD PRIMARY KEY (`id`), ADD KEY `cal_calendar_id` (`cal_calendar_id`), ADD KEY `user_created_id` (`user_created_id`), ADD KEY `cal_event_repeat_id` (`cal_event_repeat_id`), ADD KEY `cal_event_link_id` (`cal_event_link_id`);

--
-- Indexes for table `cal_event_reminder`
--
ALTER TABLE `cal_event_reminder`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event_reminder_period`
--
ALTER TABLE `cal_event_reminder_period`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event_reminder_type`
--
ALTER TABLE `cal_event_reminder_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event_repeat`
--
ALTER TABLE `cal_event_repeat`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event_repeat_cycle`
--
ALTER TABLE `cal_event_repeat_cycle`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cal_event_share`
--
ALTER TABLE `cal_event_share`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
 ADD PRIMARY KEY (`id`), ADD KEY `country_id` (`sys_country_id`);

--
-- Indexes for table `client_documentator_settings`
--
ALTER TABLE `client_documentator_settings`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_product`
--
ALTER TABLE `client_product`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `sys_product_id` (`sys_product_id`);

--
-- Indexes for table `client_settings`
--
ALTER TABLE `client_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_smtp_settings`
--
ALTER TABLE `client_smtp_settings`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `client_yongo_settings`
--
ALTER TABLE `client_yongo_settings`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `documentator_entity`
--
ALTER TABLE `documentator_entity`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_space_id` (`documentator_space_id`), ADD KEY `user_created_id` (`user_created_id`), ADD KEY `page_parent_id` (`parent_entity_id`), ADD KEY `documentator_entity_id` (`documentator_entity_type_id`);

--
-- Indexes for table `documentator_entity_attachment`
--
ALTER TABLE `documentator_entity_attachment`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_id` (`documentator_entity_id`);

--
-- Indexes for table `documentator_entity_attachment_revision`
--
ALTER TABLE `documentator_entity_attachment_revision`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_attachment_id` (`documentator_entity_attachment_id`), ADD KEY `user_created_id` (`user_created_id`);

--
-- Indexes for table `documentator_entity_comment`
--
ALTER TABLE `documentator_entity_comment`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_id` (`documentator_entity_id`), ADD KEY `user_id` (`user_id`), ADD KEY `parent_comment_id` (`parent_comment_id`);

--
-- Indexes for table `documentator_entity_file`
--
ALTER TABLE `documentator_entity_file`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_id` (`documentator_entity_id`);

--
-- Indexes for table `documentator_entity_file_revision`
--
ALTER TABLE `documentator_entity_file_revision`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_file_id` (`documentator_entity_file_id`), ADD KEY `user_created_id` (`user_created_id`);

--
-- Indexes for table `documentator_entity_revision`
--
ALTER TABLE `documentator_entity_revision`
 ADD PRIMARY KEY (`id`), ADD KEY `page_id` (`entity_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documentator_entity_snapshot`
--
ALTER TABLE `documentator_entity_snapshot`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_entity_id` (`documentator_entity_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documentator_entity_type`
--
ALTER TABLE `documentator_entity_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documentator_space`
--
ALTER TABLE `documentator_space`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `home_page_id` (`home_entity_id`);

--
-- Indexes for table `documentator_space_permission`
--
ALTER TABLE `documentator_space_permission`
 ADD PRIMARY KEY (`id`), ADD KEY `space_id` (`space_id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `documentator_space_permission_anonymous`
--
ALTER TABLE `documentator_space_permission_anonymous`
 ADD PRIMARY KEY (`id`), ADD KEY `documentator_space_id` (`documentator_space_id`);

--
-- Indexes for table `documentator_user_entity_favourite`
--
ALTER TABLE `documentator_user_entity_favourite`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `page_id` (`entity_id`);

--
-- Indexes for table `documentator_user_space_favourite`
--
ALTER TABLE `documentator_user_space_favourite`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `space_id` (`space_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `field`
--
ALTER TABLE `field`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `sys_field_type_id` (`sys_field_type_id`);

--
-- Indexes for table `field_configuration`
--
ALTER TABLE `field_configuration`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `field_configuration_data`
--
ALTER TABLE `field_configuration_data`
 ADD PRIMARY KEY (`id`), ADD KEY `field_configuration_id` (`field_configuration_id`), ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `field_issue_type_data`
--
ALTER TABLE `field_issue_type_data`
 ADD PRIMARY KEY (`id`), ADD KEY `field_id` (`field_id`), ADD KEY `issue_type_id` (`issue_type_id`);

--
-- Indexes for table `field_project_data`
--
ALTER TABLE `field_project_data`
 ADD PRIMARY KEY (`id`), ADD KEY `field_id` (`field_id`), ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `filter`
--
ALTER TABLE `filter`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `general_invoice`
--
ALTER TABLE `general_invoice`
 ADD PRIMARY KEY (`id`), ADD KEY `general_payment_id` (`general_payment_id`), ADD KEY `general_payment_id_2` (`general_payment_id`);

--
-- Indexes for table `general_log`
--
ALTER TABLE `general_log`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `user_id` (`user_id`), ADD KEY `sys_product_id` (`sys_product_id`);

--
-- Indexes for table `general_mail_queue`
--
ALTER TABLE `general_mail_queue`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `general_payment`
--
ALTER TABLE `general_payment`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `general_task_queue`
--
ALTER TABLE `general_task_queue`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `sys_product_id` (`sys_product_id`);

--
-- Indexes for table `group_data`
--
ALTER TABLE `group_data`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `help_customer`
--
ALTER TABLE `help_customer`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_filter`
--
ALTER TABLE `help_filter`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_organization`
--
ALTER TABLE `help_organization`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `help_organization_user`
--
ALTER TABLE `help_organization_user`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_reset_password`
--
ALTER TABLE `help_reset_password`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_sla`
--
ALTER TABLE `help_sla`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `help_sla_goal`
--
ALTER TABLE `help_sla_goal`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_attachment`
--
ALTER TABLE `issue_attachment`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_id` (`issue_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `issue_comment`
--
ALTER TABLE `issue_comment`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_id` (`issue_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `issue_component`
--
ALTER TABLE `issue_component`
 ADD PRIMARY KEY (`id`), ADD KEY `project_component_id` (`project_component_id`), ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `issue_custom_field_data`
--
ALTER TABLE `issue_custom_field_data`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_id` (`issue_id`), ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `issue_history`
--
ALTER TABLE `issue_history`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_id` (`issue_id`), ADD KEY `by_user_id` (`by_user_id`);

--
-- Indexes for table `issue_link`
--
ALTER TABLE `issue_link`
 ADD PRIMARY KEY (`id`), ADD KEY `parent_issue_id` (`parent_issue_id`), ADD KEY `sys_issue_link_type_id` (`issue_link_type_id`), ADD KEY `child_issue_id` (`child_issue_id`);

--
-- Indexes for table `issue_link_type`
--
ALTER TABLE `issue_link_type`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_priority`
--
ALTER TABLE `issue_priority`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_resolution`
--
ALTER TABLE `issue_resolution`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_security_scheme`
--
ALTER TABLE `issue_security_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_security_scheme_level`
--
ALTER TABLE `issue_security_scheme_level`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_security_scheme_id` (`issue_security_scheme_id`);

--
-- Indexes for table `issue_security_scheme_level_data`
--
ALTER TABLE `issue_security_scheme_level_data`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_security_scheme_level_id` (`issue_security_scheme_level_id`), ADD KEY `permission_role_id` (`permission_role_id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `issue_status`
--
ALTER TABLE `issue_status`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_type`
--
ALTER TABLE `issue_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_type_field_configuration`
--
ALTER TABLE `issue_type_field_configuration`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_type_field_configuration_data`
--
ALTER TABLE `issue_type_field_configuration_data`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_type_id` (`issue_type_id`), ADD KEY `field_configuration_id` (`field_configuration_id`), ADD KEY `issue_type_field_configuration_id` (`issue_type_field_configuration_id`);

--
-- Indexes for table `issue_type_scheme`
--
ALTER TABLE `issue_type_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_type_scheme_data`
--
ALTER TABLE `issue_type_scheme_data`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_type_scheme_id` (`issue_type_scheme_id`), ADD KEY `issue_type_id` (`issue_type_id`);

--
-- Indexes for table `issue_type_screen_scheme`
--
ALTER TABLE `issue_type_screen_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `issue_type_screen_scheme_data`
--
ALTER TABLE `issue_type_screen_scheme_data`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`issue_type_screen_scheme_id`), ADD KEY `issue_type_id` (`issue_type_id`), ADD KEY `screen_scheme_id` (`screen_scheme_id`);

--
-- Indexes for table `issue_version`
--
ALTER TABLE `issue_version`
 ADD PRIMARY KEY (`id`), ADD KEY `project_version_id` (`project_version_id`), ADD KEY `issue_id` (`issue_id`);

--
-- Indexes for table `issue_work_log`
--
ALTER TABLE `issue_work_log`
 ADD PRIMARY KEY (`id`), ADD KEY `issue_id` (`issue_id`,`user_id`);

--
-- Indexes for table `newsletter`
--
ALTER TABLE `newsletter`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_scheme`
--
ALTER TABLE `notification_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `notification_scheme_data`
--
ALTER TABLE `notification_scheme_data`
 ADD PRIMARY KEY (`id`), ADD KEY `notification_scheme_id` (`notification_scheme_id`), ADD KEY `permission_role_id` (`permission_role_id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`), ADD KEY `current_assignee` (`current_assignee`), ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `permission_role_data`
--
ALTER TABLE `permission_role_data`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`default_user_id`), ADD KEY `user_group_id` (`default_group_id`), ADD KEY `permission_role_id` (`permission_role_id`);

--
-- Indexes for table `permission_scheme`
--
ALTER TABLE `permission_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `permission_scheme_data`
--
ALTER TABLE `permission_scheme_data`
 ADD PRIMARY KEY (`id`), ADD KEY `permission_scheme_id` (`permission_scheme_id`), ADD KEY `permission_id` (`sys_permission_id`), ADD KEY `permission_role_id` (`permission_role_id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `owner_id` (`lead_id`), ADD KEY `issue_type_scheme_id` (`issue_type_scheme_id`), ADD KEY `workflow_scheme_id` (`workflow_scheme_id`), ADD KEY `issue_type_screen_scheme_id` (`issue_type_screen_scheme_id`), ADD KEY `issue_type_field_configuration_id` (`issue_type_field_configuration_id`), ADD KEY `permission_scheme_id` (`permission_scheme_id`), ADD KEY `notification_scheme_id` (`notification_scheme_id`), ADD KEY `issue_security_scheme_id` (`issue_security_scheme_id`), ADD KEY `project_category_id` (`project_category_id`);

--
-- Indexes for table `project_category`
--
ALTER TABLE `project_category`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `project_component`
--
ALTER TABLE `project_component`
 ADD PRIMARY KEY (`id`), ADD KEY `product_id` (`project_id`), ADD KEY `leader_id` (`leader_id`), ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `project_role_data`
--
ALTER TABLE `project_role_data`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`permission_role_id`), ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `project_version`
--
ALTER TABLE `project_version`
 ADD PRIMARY KEY (`id`), ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `screen`
--
ALTER TABLE `screen`
 ADD PRIMARY KEY (`id`), ADD KEY `project_id` (`client_id`);

--
-- Indexes for table `screen_data`
--
ALTER TABLE `screen_data`
 ADD PRIMARY KEY (`id`), ADD KEY `project_workflow_screen_id` (`screen_id`);

--
-- Indexes for table `screen_scheme`
--
ALTER TABLE `screen_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `screen_scheme_data`
--
ALTER TABLE `screen_scheme_data`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `server_settings`
--
ALTER TABLE `server_settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `svn_repository`
--
ALTER TABLE `svn_repository`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `user_created_id` (`user_created_id`);

--
-- Indexes for table `svn_repository_user`
--
ALTER TABLE `svn_repository_user`
 ADD PRIMARY KEY (`id`), ADD KEY `svn_repository_id` (`svn_repository_id`), ADD KEY `user_id` (`user_id`), ADD KEY `date_created` (`date_created`);

--
-- Indexes for table `sys_condition`
--
ALTER TABLE `sys_condition`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_country`
--
ALTER TABLE `sys_country`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_field_type`
--
ALTER TABLE `sys_field_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_operation`
--
ALTER TABLE `sys_operation`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_permission`
--
ALTER TABLE `sys_permission`
 ADD PRIMARY KEY (`id`), ADD KEY `sys_permission_category_id` (`sys_permission_category_id`);

--
-- Indexes for table `sys_permission_category`
--
ALTER TABLE `sys_permission_category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_permission_global`
--
ALTER TABLE `sys_permission_global`
 ADD PRIMARY KEY (`id`), ADD KEY `sys_product_id` (`sys_product_id`);

--
-- Indexes for table `sys_permission_global_data`
--
ALTER TABLE `sys_permission_global_data`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `sys_permission_global_id` (`sys_permission_global_id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sys_product`
--
ALTER TABLE `sys_product`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_product_release`
--
ALTER TABLE `sys_product_release`
 ADD PRIMARY KEY (`id`), ADD KEY `sys_product_id` (`sys_product_id`);

--
-- Indexes for table `sys_workflow_post_function`
--
ALTER TABLE `sys_workflow_post_function`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sys_workflow_step_property`
--
ALTER TABLE `sys_workflow_step_property`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `workflow`
--
ALTER TABLE `workflow`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `issue_type_scheme_id` (`issue_type_scheme_id`);

--
-- Indexes for table `workflow_condition_data`
--
ALTER TABLE `workflow_condition_data`
 ADD PRIMARY KEY (`id`), ADD KEY `workflow_data_id` (`workflow_data_id`);

--
-- Indexes for table `workflow_data`
--
ALTER TABLE `workflow_data`
 ADD PRIMARY KEY (`id`), ADD KEY `project_id` (`workflow_id`), ADD KEY `issue_status_from_id` (`workflow_step_id_from`,`workflow_step_id_to`), ADD KEY `screen_id` (`screen_id`);

--
-- Indexes for table `workflow_position`
--
ALTER TABLE `workflow_position`
 ADD PRIMARY KEY (`id`), ADD KEY `project_workflow_id` (`workflow_id`,`workflow_step_id`);

--
-- Indexes for table `workflow_post_function_data`
--
ALTER TABLE `workflow_post_function_data`
 ADD PRIMARY KEY (`id`), ADD KEY `project_workflow_data_id` (`workflow_data_id`,`sys_workflow_post_function_id`);

--
-- Indexes for table `workflow_scheme`
--
ALTER TABLE `workflow_scheme`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `workflow_scheme_data`
--
ALTER TABLE `workflow_scheme_data`
 ADD PRIMARY KEY (`id`), ADD KEY `workflow_scheme_id` (`workflow_scheme_id`), ADD KEY `workflow_id` (`workflow_id`);

--
-- Indexes for table `workflow_step`
--
ALTER TABLE `workflow_step`
 ADD PRIMARY KEY (`id`), ADD KEY `workflow_id` (`workflow_id`);

--
-- Indexes for table `workflow_step_property`
--
ALTER TABLE `workflow_step_property`
 ADD PRIMARY KEY (`id`), ADD KEY `workflow_step_id` (`workflow_step_id`), ADD KEY `sys_workflow_step_property_id` (`sys_workflow_step_property_id`);

--
-- Indexes for table `yongo_issue`
--
ALTER TABLE `yongo_issue`
 ADD PRIMARY KEY (`id`), ADD KEY `user_assigned_id` (`user_assigned_id`), ADD KEY `project_id` (`project_id`), ADD KEY `status_id` (`status_id`), ADD KEY `user_reported_id` (`user_reported_id`), ADD KEY `type_id` (`type_id`), ADD KEY `resolution_id` (`resolution_id`), ADD KEY `priority_id` (`priority_id`), ADD KEY `parent_id` (`parent_id`), ADD KEY `issue_security_scheme_level_id` (`security_scheme_level_id`);

--
-- Indexes for table `yongo_issue_sla`
--
ALTER TABLE `yongo_issue_sla`
 ADD PRIMARY KEY (`id`), ADD KEY `yongo_issue_id` (`yongo_issue_id`), ADD KEY `help_sla_id` (`help_sla_id`);

--
-- Indexes for table `yongo_issue_watch`
--
ALTER TABLE `yongo_issue_watch`
 ADD PRIMARY KEY (`id`), ADD KEY `yongo_issue_id` (`yongo_issue_id`), ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agile_board`
--
ALTER TABLE `agile_board`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `agile_board_column`
--
ALTER TABLE `agile_board_column`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=274;
--
-- AUTO_INCREMENT for table `agile_board_column_status`
--
ALTER TABLE `agile_board_column_status`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=472;
--
-- AUTO_INCREMENT for table `agile_board_project`
--
ALTER TABLE `agile_board_project`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=107;
--
-- AUTO_INCREMENT for table `agile_board_sprint`
--
ALTER TABLE `agile_board_sprint`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=126;
--
-- AUTO_INCREMENT for table `agile_board_sprint_issue`
--
ALTER TABLE `agile_board_sprint_issue`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=592;
--
-- AUTO_INCREMENT for table `cal_calendar`
--
ALTER TABLE `cal_calendar`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1516;
--
-- AUTO_INCREMENT for table `cal_calendar_default_reminder`
--
ALTER TABLE `cal_calendar_default_reminder`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1514;
--
-- AUTO_INCREMENT for table `cal_calendar_share`
--
ALTER TABLE `cal_calendar_share`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `cal_event`
--
ALTER TABLE `cal_event`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT for table `cal_event_reminder`
--
ALTER TABLE `cal_event_reminder`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `cal_event_reminder_period`
--
ALTER TABLE `cal_event_reminder_period`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cal_event_reminder_type`
--
ALTER TABLE `cal_event_reminder_type`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cal_event_repeat`
--
ALTER TABLE `cal_event_repeat`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `cal_event_repeat_cycle`
--
ALTER TABLE `cal_event_repeat_cycle`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `cal_event_share`
--
ALTER TABLE `cal_event_share`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1960;
--
-- AUTO_INCREMENT for table `client_documentator_settings`
--
ALTER TABLE `client_documentator_settings`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1945;
--
-- AUTO_INCREMENT for table `client_product`
--
ALTER TABLE `client_product`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3488;
--
-- AUTO_INCREMENT for table `client_settings`
--
ALTER TABLE `client_settings`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1944;
--
-- AUTO_INCREMENT for table `client_smtp_settings`
--
ALTER TABLE `client_smtp_settings`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=478;
--
-- AUTO_INCREMENT for table `client_yongo_settings`
--
ALTER TABLE `client_yongo_settings`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1960;
--
-- AUTO_INCREMENT for table `documentator_entity`
--
ALTER TABLE `documentator_entity`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=206;
--
-- AUTO_INCREMENT for table `documentator_entity_attachment`
--
ALTER TABLE `documentator_entity_attachment`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `documentator_entity_attachment_revision`
--
ALTER TABLE `documentator_entity_attachment_revision`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT for table `documentator_entity_comment`
--
ALTER TABLE `documentator_entity_comment`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `documentator_entity_file`
--
ALTER TABLE `documentator_entity_file`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=112;
--
-- AUTO_INCREMENT for table `documentator_entity_file_revision`
--
ALTER TABLE `documentator_entity_file_revision`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=116;
--
-- AUTO_INCREMENT for table `documentator_entity_revision`
--
ALTER TABLE `documentator_entity_revision`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=149;
--
-- AUTO_INCREMENT for table `documentator_entity_snapshot`
--
ALTER TABLE `documentator_entity_snapshot`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4273;
--
-- AUTO_INCREMENT for table `documentator_entity_type`
--
ALTER TABLE `documentator_entity_type`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `documentator_space`
--
ALTER TABLE `documentator_space`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT for table `documentator_space_permission`
--
ALTER TABLE `documentator_space_permission`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `documentator_space_permission_anonymous`
--
ALTER TABLE `documentator_space_permission_anonymous`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `documentator_user_entity_favourite`
--
ALTER TABLE `documentator_user_entity_favourite`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `documentator_user_space_favourite`
--
ALTER TABLE `documentator_user_space_favourite`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23511;
--
-- AUTO_INCREMENT for table `field`
--
ALTER TABLE `field`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29403;
--
-- AUTO_INCREMENT for table `field_configuration`
--
ALTER TABLE `field_configuration`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1961;
--
-- AUTO_INCREMENT for table `field_configuration_data`
--
ALTER TABLE `field_configuration_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29345;
--
-- AUTO_INCREMENT for table `field_issue_type_data`
--
ALTER TABLE `field_issue_type_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `field_project_data`
--
ALTER TABLE `field_project_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `filter`
--
ALTER TABLE `filter`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=117;
--
-- AUTO_INCREMENT for table `general_invoice`
--
ALTER TABLE `general_invoice`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `general_log`
--
ALTER TABLE `general_log`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10546;
--
-- AUTO_INCREMENT for table `general_mail_queue`
--
ALTER TABLE `general_mail_queue`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1092;
--
-- AUTO_INCREMENT for table `general_payment`
--
ALTER TABLE `general_payment`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `general_task_queue`
--
ALTER TABLE `general_task_queue`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=253;
--
-- AUTO_INCREMENT for table `group`
--
ALTER TABLE `group`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9547;
--
-- AUTO_INCREMENT for table `group_data`
--
ALTER TABLE `group_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10805;
--
-- AUTO_INCREMENT for table `help_customer`
--
ALTER TABLE `help_customer`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help_filter`
--
ALTER TABLE `help_filter`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `help_organization`
--
ALTER TABLE `help_organization`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `help_organization_user`
--
ALTER TABLE `help_organization_user`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help_reset_password`
--
ALTER TABLE `help_reset_password`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `help_sla`
--
ALTER TABLE `help_sla`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `help_sla_goal`
--
ALTER TABLE `help_sla_goal`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `issue_attachment`
--
ALTER TABLE `issue_attachment`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=857;
--
-- AUTO_INCREMENT for table `issue_comment`
--
ALTER TABLE `issue_comment`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1782;
--
-- AUTO_INCREMENT for table `issue_component`
--
ALTER TABLE `issue_component`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1351;
--
-- AUTO_INCREMENT for table `issue_custom_field_data`
--
ALTER TABLE `issue_custom_field_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=298;
--
-- AUTO_INCREMENT for table `issue_history`
--
ALTER TABLE `issue_history`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9048;
--
-- AUTO_INCREMENT for table `issue_link`
--
ALTER TABLE `issue_link`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=144;
--
-- AUTO_INCREMENT for table `issue_link_type`
--
ALTER TABLE `issue_link_type`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7769;
--
-- AUTO_INCREMENT for table `issue_priority`
--
ALTER TABLE `issue_priority`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9786;
--
-- AUTO_INCREMENT for table `issue_resolution`
--
ALTER TABLE `issue_resolution`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9808;
--
-- AUTO_INCREMENT for table `issue_security_scheme`
--
ALTER TABLE `issue_security_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `issue_security_scheme_level`
--
ALTER TABLE `issue_security_scheme_level`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `issue_security_scheme_level_data`
--
ALTER TABLE `issue_security_scheme_level_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `issue_status`
--
ALTER TABLE `issue_status`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9824;
--
-- AUTO_INCREMENT for table `issue_type`
--
ALTER TABLE `issue_type`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15729;
--
-- AUTO_INCREMENT for table `issue_type_field_configuration`
--
ALTER TABLE `issue_type_field_configuration`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1959;
--
-- AUTO_INCREMENT for table `issue_type_field_configuration_data`
--
ALTER TABLE `issue_type_field_configuration_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15685;
--
-- AUTO_INCREMENT for table `issue_type_scheme`
--
ALTER TABLE `issue_type_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3950;
--
-- AUTO_INCREMENT for table `issue_type_scheme_data`
--
ALTER TABLE `issue_type_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31923;
--
-- AUTO_INCREMENT for table `issue_type_screen_scheme`
--
ALTER TABLE `issue_type_screen_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1960;
--
-- AUTO_INCREMENT for table `issue_type_screen_scheme_data`
--
ALTER TABLE `issue_type_screen_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15693;
--
-- AUTO_INCREMENT for table `issue_version`
--
ALTER TABLE `issue_version`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2294;
--
-- AUTO_INCREMENT for table `issue_work_log`
--
ALTER TABLE `issue_work_log`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84;
--
-- AUTO_INCREMENT for table `newsletter`
--
ALTER TABLE `newsletter`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `notification_scheme`
--
ALTER TABLE `notification_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1964;
--
-- AUTO_INCREMENT for table `notification_scheme_data`
--
ALTER TABLE `notification_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=46997;
--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5890;
--
-- AUTO_INCREMENT for table `permission_role_data`
--
ALTER TABLE `permission_role_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5969;
--
-- AUTO_INCREMENT for table `permission_scheme`
--
ALTER TABLE `permission_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1970;
--
-- AUTO_INCREMENT for table `permission_scheme_data`
--
ALTER TABLE `permission_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47899;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=494;
--
-- AUTO_INCREMENT for table `project_category`
--
ALTER TABLE `project_category`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `project_component`
--
ALTER TABLE `project_component`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1337;
--
-- AUTO_INCREMENT for table `project_role_data`
--
ALTER TABLE `project_role_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3534;
--
-- AUTO_INCREMENT for table `project_version`
--
ALTER TABLE `project_version`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=523;
--
-- AUTO_INCREMENT for table `screen`
--
ALTER TABLE `screen`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5889;
--
-- AUTO_INCREMENT for table `screen_data`
--
ALTER TABLE `screen_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35325;
--
-- AUTO_INCREMENT for table `screen_scheme`
--
ALTER TABLE `screen_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1961;
--
-- AUTO_INCREMENT for table `screen_scheme_data`
--
ALTER TABLE `screen_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5884;
--
-- AUTO_INCREMENT for table `server_settings`
--
ALTER TABLE `server_settings`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `svn_repository`
--
ALTER TABLE `svn_repository`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=183;
--
-- AUTO_INCREMENT for table `svn_repository_user`
--
ALTER TABLE `svn_repository_user`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=304;
--
-- AUTO_INCREMENT for table `sys_condition`
--
ALTER TABLE `sys_condition`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sys_country`
--
ALTER TABLE `sys_country`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=197;
--
-- AUTO_INCREMENT for table `sys_field_type`
--
ALTER TABLE `sys_field_type`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sys_operation`
--
ALTER TABLE `sys_operation`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `sys_permission`
--
ALTER TABLE `sys_permission`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `sys_permission_category`
--
ALTER TABLE `sys_permission_category`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sys_permission_global`
--
ALTER TABLE `sys_permission_global`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `sys_permission_global_data`
--
ALTER TABLE `sys_permission_global_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15436;
--
-- AUTO_INCREMENT for table `sys_product`
--
ALTER TABLE `sys_product`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `sys_product_release`
--
ALTER TABLE `sys_product_release`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sys_workflow_post_function`
--
ALTER TABLE `sys_workflow_post_function`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `sys_workflow_step_property`
--
ALTER TABLE `sys_workflow_step_property`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3031;
--
-- AUTO_INCREMENT for table `workflow`
--
ALTER TABLE `workflow`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1998;
--
-- AUTO_INCREMENT for table `workflow_condition_data`
--
ALTER TABLE `workflow_condition_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23621;
--
-- AUTO_INCREMENT for table `workflow_data`
--
ALTER TABLE `workflow_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25712;
--
-- AUTO_INCREMENT for table `workflow_position`
--
ALTER TABLE `workflow_position`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11997;
--
-- AUTO_INCREMENT for table `workflow_post_function_data`
--
ALTER TABLE `workflow_post_function_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=84851;
--
-- AUTO_INCREMENT for table `workflow_scheme`
--
ALTER TABLE `workflow_scheme`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1971;
--
-- AUTO_INCREMENT for table `workflow_scheme_data`
--
ALTER TABLE `workflow_scheme_data`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1982;
--
-- AUTO_INCREMENT for table `workflow_step`
--
ALTER TABLE `workflow_step`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11929;
--
-- AUTO_INCREMENT for table `workflow_step_property`
--
ALTER TABLE `workflow_step_property`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `yongo_issue`
--
ALTER TABLE `yongo_issue`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3020;
--
-- AUTO_INCREMENT for table `yongo_issue_sla`
--
ALTER TABLE `yongo_issue_sla`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `yongo_issue_watch`
--
ALTER TABLE `yongo_issue_watch`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=776;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
