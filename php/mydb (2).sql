-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2014 at 06:46 PM
-- Server version: 5.6.20
-- PHP Version: 5.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
`gallery_id` int(20) NOT NULL,
  `name_gallery` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `name_image` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `detail_th` varchar(500) CHARACTER SET tis620 COLLATE tis620_bin NOT NULL,
  `detail_en` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gallery_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `page`
--

CREATE TABLE IF NOT EXISTS `page` (
`pageid` int(6) NOT NULL,
  `gallery_id` int(20) NOT NULL,
  `textbox_id` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE IF NOT EXISTS `reservation` (
`id` int(6) unsigned NOT NULL,
  `roomtype` varchar(30) NOT NULL,
  `reserv_date` date DEFAULT NULL,
  `amount` int(1) DEFAULT NULL,
  `price` int(6) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `roomtype`, `reserv_date`, `amount`, `price`, `reg_date`) VALUES
(1, 'Delux', '2014-11-11', 3, 100, '2014-11-11 18:37:17'),
(2, 'Delux', '2014-11-12', 3, 100, '2014-11-11 18:37:28'),
(3, 'Delux', '2014-11-13', 3, 100, '2014-11-11 18:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `roomtype`
--

CREATE TABLE IF NOT EXISTS `roomtype` (
`id` int(6) unsigned NOT NULL,
  `roomname` varchar(30) NOT NULL,
  `price` int(6) NOT NULL,
  `amount` int(1) DEFAULT NULL,
  `pageid` int(6) NOT NULL DEFAULT '0',
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `textbox`
--

CREATE TABLE IF NOT EXISTS `textbox` (
  `textbox_id` int(50) NOT NULL DEFAULT '0',
  `title_th` varchar(100) CHARACTER SET tis620 COLLATE tis620_bin NOT NULL,
  `title_en` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `datail_th` varchar(500) CHARACTER SET tis620 COLLATE tis620_bin NOT NULL,
  `detail_en` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `gallery_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
 ADD PRIMARY KEY (`gallery_id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
 ADD PRIMARY KEY (`name_image`,`gallery_id`), ADD KEY `gallery_id` (`gallery_id`);

--
-- Indexes for table `page`
--
ALTER TABLE `page`
 ADD PRIMARY KEY (`pageid`,`gallery_id`,`textbox_id`), ADD KEY `gallery_id` (`gallery_id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roomtype`
--
ALTER TABLE `roomtype`
 ADD PRIMARY KEY (`id`,`pageid`), ADD KEY `pageid` (`pageid`);

--
-- Indexes for table `textbox`
--
ALTER TABLE `textbox`
 ADD PRIMARY KEY (`textbox_id`,`gallery_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
MODIFY `gallery_id` int(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `page`
--
ALTER TABLE `page`
MODIFY `pageid` int(6) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `roomtype`
--
ALTER TABLE `roomtype`
MODIFY `id` int(6) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `image`
--
ALTER TABLE `image`
ADD CONSTRAINT `image_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`gallery_id`);

--
-- Constraints for table `page`
--
ALTER TABLE `page`
ADD CONSTRAINT `page_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `gallery` (`gallery_id`);

--
-- Constraints for table `roomtype`
--
ALTER TABLE `roomtype`
ADD CONSTRAINT `roomtype_ibfk_1` FOREIGN KEY (`pageid`) REFERENCES `page` (`pageid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
