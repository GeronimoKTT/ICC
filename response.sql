-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2019 at 01:54 AM
-- Server version: 10.1.35-MariaDB
-- PHP Version: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `ID` int(14) NOT NULL,
  `imei` varchar(255) NOT NULL DEFAULT '',
  `text_data` text NOT NULL,
  `names` varchar(255) NOT NULL DEFAULT '',
  `fingerprint` varchar(255) NOT NULL,
  `projectRessourcesArchive` text NOT NULL,
  `liked_or_not` varchar(20) DEFAULT 'unliked',
  `date_event` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `response`
--

INSERT INTO `response` (`ID`, `imei`, `text_data`, `names`, `fingerprint`, `projectRessourcesArchive`, `liked_or_not`, `date_event`) VALUES
(1, '355234080667518', 'sam', 'Domminique', '8f9693be585a981ad76f7b6c72fbbf607c9e8354b35f5dbc174e30a696a6ebd8380b88f29196fd68', 'http://192.168.43.130/xampp/prototype/assets/App/project-ressources/Archive-2a225d1fa3980690aa89b4e87e8e19c5cd960cd9cf88166dddcb76b6e07628d1bcdae94228293a40.zip', 'liked', '2011-01-01 00:00:00.000000'),
(2, '353626076192637', 'samir', 'Dominique', '8f9693be585a981ad76f7b6c72fbbf607c9e8354b35f5dbc174e30a696a6ebd8380b88f29196fd69', 'http://192.168.43.130/xampp/prototype/assets/App/project-ressources/Archive-1be47008376a38e7c9a2644a0df3baf53dc2a12f8dc1368ee91802a7165666812a6babf252578987.zip', 'liked', '2011-01-01 00:00:00.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `response`
--
ALTER TABLE `response`
  MODIFY `ID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
