-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2018 at 09:50 PM
-- Server version: 10.1.28-MariaDB
-- PHP Version: 7.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `availabledays`
--

CREATE TABLE `availabledays` (
  `daysId` int(11) NOT NULL,
  `creatorId` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `availabledays`
--

INSERT INTO `availabledays` (`daysId`, `creatorId`, `date`) VALUES
(1, 34, '2018-04-05'),
(3, 34, '2018-04-11');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `eventId` int(11) NOT NULL,
  `creatorId` int(11) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(1000) CHARACTER SET armscii8 NOT NULL,
  `eventName` varchar(70) CHARACTER SET armscii8 NOT NULL,
  `pictureDirectory` varchar(260) CHARACTER SET armscii8 NOT NULL,
  `approved` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventId`, `creatorId`, `date`, `description`, `eventName`, `pictureDirectory`, `approved`) VALUES
(58, 34, '0000-00-00', '<p>Welcome to my new site!</p>', 'Welcome!', 'images\\events\\58\\event.jpg', 0),
(59, 35, '0000-00-00', '<p><span style=\"color: #333333; font-family: Verdana, Arial, sans-serif; font-size: 13px; background-color: #fbfbfb;\">The opening crawl from this latest film \'Star Wars, Episode VIII: The Last Jedi\' reads that the First Order is plotting to seize military control of the galaxy. A team of Resistance fighters led by General Leia Organa (Carrie Fisher) are planning an evacuation from their main base as Supreme Leader Snoke\'s (Andy Serkis) forces are coming for them. The Resistance holds out hope that Luke Skywalker (Mark Hamill) will return to bring hope.</span></p>', 'Star Wars!', 'images\\events\\59\\event.jpg', 0),
(62, 34, '0000-00-00', '<p><span style=\"color: #333333; font-family: Verdana, Arial, sans-serif; font-size: 13px; background-color: #fbfbfb;\">After the events of Captain America: Civil War, King T\'Challa returns home to the reclusive, technologically advanced African nation of Wakanda to serve as his country\'s new leader. However, T\'Challa soon finds that he is challenged for the throne from factions within his own country. When two foes conspire to destroy Wakanda, the hero known as Black Panther must team up with C.I.A. agent Everett K. Ross and members of the Dora Milaje, Wakandan special forces, to prevent Wakanda from being dragged into a world war.</span></p>', 'Black Panther Movie Night!', 'images\\events\\62\\event.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `userpages`
--

CREATE TABLE `userpages` (
  `pageId` int(11) NOT NULL,
  `creatorId` int(11) NOT NULL,
  `color` varchar(30) CHARACTER SET armscii8 NOT NULL,
  `profilePicture` varchar(200) CHARACTER SET armscii8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `userpages`
--

INSERT INTO `userpages` (`pageId`, `creatorId`, `color`, `profilePicture`) VALUES
(8, 34, 'black', 'images\\userprofile\\Funk-Froese\\profile.jpg'),
(9, 35, 'white', 'images\\userprofile\\Man\\profile.jpg'),
(10, 36, 'black', 'images\\userprofile\\Man\\profile.jpg'),
(11, 37, 'white', 'images\\userprofile\\default.png'),
(15, 41, 'white', 'images\\userprofile\\Blahman\\profile.jpg'),
(16, 42, 'black', 'images\\userprofile\\MemeMasterLastNameMaster\\profile.jpg'),
(17, 43, 'black', 'images\\userprofile\\Dude\\profile.jpg'),
(18, 44, 'black', 'images\\userprofile\\Boop\\profile.jpg'),
(19, 45, 'black', 'images\\userprofile\\kbert\\profile.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `email` varchar(254) CHARACTER SET armscii8 NOT NULL,
  `password` varchar(128) CHARACTER SET armscii8 NOT NULL,
  `firstName` varchar(70) CHARACTER SET armscii8 NOT NULL,
  `lastName` varchar(70) CHARACTER SET armscii8 NOT NULL,
  `isAdmin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `email`, `password`, `firstName`, `lastName`, `isAdmin`) VALUES
(34, 'sorenff@gmail.com', '$2y$10$./dqVRHDiyobbO9WuSA1iOI/.nLW3UIrVoghlq//Z32J4NGRmdaGe', 'Soren', 'Funk-Froese', 1),
(35, 'man@man.com', '$2y$10$4Q1YQVK4oxIF7uwAXU3FKeHQiiKBcA1JkTBMIea2lQ2ckRP.DUTee', 'Dude', 'Man', 0),
(36, 'pizza@pizza.com', '$2y$10$hTluoM26cafG3yLVerRCaeMVBQEg2h67eTOwrkuG25AsSFyhERyJK', 'Pizza ', 'Man', 0),
(37, 'default@default.com', '$2y$10$YJdqvNP3M5oieeiYO/NxL.aU4.LvfZeGKeH.7BjhHoGsuRkWB.8Um', 'default', 'man', 0),
(41, 'blah@blah.com', '$2y$10$/xWZsazyahYQqfVbstVyTeWPKohJul.frPSaM4f/LyRM/JcXRn2/C', 'Blah', 'Blahman', 0),
(42, 'email@email.com', '$2y$10$V0pkRtmvXmOPhAqvEmJgsOH3yRJlBQZPYpar3zNXNwxpae0u7ke6W', 'MemeMaster', 'MemeMasterLastNameMaster', 0),
(43, 'dudeman@man.com', '$2y$10$lgibCQnwlUa3I7wcOUU78uSPGEe0q.8eyeQRsZJI10NJFRDa9n0nm', 'New', 'Dude', 0),
(44, 'beep@beep.com', '$2y$10$J3k6T.l1doqOTShZ1EWUke/epOaDYovSUeUzbKK6i5Sd7GG.rcSwm', 'Beep', 'Boop', 0),
(45, 'k@k.com', '$2y$10$PusVKLIdICQWrGRpfUNVsu5Y7C3AWQc/vzjxDvrBkPdk/wp8pXauW', 'KMan', 'kbert', 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `voteId` int(11) NOT NULL,
  `event` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `votes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`voteId`, `event`, `user`, `votes`) VALUES
(40, 58, 34, 1),
(41, 59, 35, 1),
(42, 58, 35, 1),
(48, 59, 34, 1),
(51, 62, 34, 1),
(57, 58, 45, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `availabledays`
--
ALTER TABLE `availabledays`
  ADD PRIMARY KEY (`daysId`),
  ADD KEY `creatorId` (`creatorId`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`eventId`),
  ADD KEY `eventFK` (`creatorId`);

--
-- Indexes for table `userpages`
--
ALTER TABLE `userpages`
  ADD PRIMARY KEY (`pageId`),
  ADD KEY `creatorId` (`creatorId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`voteId`),
  ADD KEY `userFK` (`user`),
  ADD KEY `eventVoteFK` (`event`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `availabledays`
--
ALTER TABLE `availabledays`
  MODIFY `daysId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `eventId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `userpages`
--
ALTER TABLE `userpages`
  MODIFY `pageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `voteId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `availabledays`
--
ALTER TABLE `availabledays`
  ADD CONSTRAINT `availabledays_ibfk_1` FOREIGN KEY (`creatorId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `eventFK` FOREIGN KEY (`creatorId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `userpages`
--
ALTER TABLE `userpages`
  ADD CONSTRAINT `userpages_ibfk_1` FOREIGN KEY (`creatorId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `eventVoteFK` FOREIGN KEY (`event`) REFERENCES `events` (`eventId`),
  ADD CONSTRAINT `userFK` FOREIGN KEY (`user`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
