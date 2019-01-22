-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2019 at 01:55 AM
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
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `ID` int(14) NOT NULL,
  `text_data` text NOT NULL,
  `audio_data` text NOT NULL,
  `video_data` text NOT NULL,
  `image_data` text NOT NULL,
  `imei` varchar(255) NOT NULL,
  `fingerprint` varchar(255) NOT NULL,
  `projectRessourcesArchive` text NOT NULL,
  `date_updated` datetime(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`ID`, `text_data`, `audio_data`, `video_data`, `image_data`, `imei`, `fingerprint`, `projectRessourcesArchive`, `date_updated`) VALUES
(206, 'Nexus%205X%20test', 'false', 'false', 'true', '353626076192637', 'b184635f107a1c6d6e11ca7ec272a269084b6692c3328826548d63c7e99911b244cc48a2e3633f72', 'assets/App/project-ressources/Archive-b184635f107a1c6d6e11ca7ec272a269084b6692c3328826548d63c7e99911b244cc48a2e3633f72.zip', '2019-01-19 19:43:26.000000'),
(207, 'Request%202%20Nexus%205X', 'false', 'true', 'false', '353626076192637', '1be47008376a38e7c9a2644a0df3baf53dc2a12f8dc1368ee91802a7165666812a6babf252578987', 'assets/App/project-ressources/Archive-1be47008376a38e7c9a2644a0df3baf53dc2a12f8dc1368ee91802a7165666812a6babf252578987.zip', '2019-01-20 17:58:46.000000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `ID` int(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
