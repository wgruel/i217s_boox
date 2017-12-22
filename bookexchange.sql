-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 22, 2017 at 06:58 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookexchange`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `author` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `owner_id`, `author`, `title`, `price`) VALUES
(1, 7, 'Kai Ahnung', 'Ich weiss nichts...', '0.01'),
(2, 1, 'Douglas Adams', 'Hitchhikers Guide to the Galaxy', '12.00'),
(5, 1, 'Jan Boehmermann', 'Erdogan', '100.00'),
(16, 1, 'Rosamunde Pilcher', 'Mord am See', '1.00'),
(20, 7, 'Jamie Oliver', 'My Recipies', '19.99'),
(21, 1, 'Karl Marx', 'Das Kapitel', '29.00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `lat` varchar(15) NOT NULL,
  `lng` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `admin`, `email`, `address`, `lat`, `lng`) VALUES
(1, 'wgruel', '123456', 0, 'gruel@gruel.com', 'Nobelstr. 11, Stuttgart', '48.7402065', '9.102013'),
(4, 'anotherone', '123123123', 1, 'email@mail.com', 'Central Park, New York', '40.7828647', '-73.9653551'),
(7, 'superuser', '123123', 1, 'super@user.com', '', '', ''),
(31, 'koko', 'kokoko', 0, 'gruel@hdm-stuttgart.de', 'Nobelstr. 10, stuttgart', '48.7412561', '9.1008994'),
(32, 'bookworm', '123123', 0, 'bookwork@htm-stuttgart.de', 'Nobelstr. 12, 70569 Stuttgart', '48.7415146', '9.0970269');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
