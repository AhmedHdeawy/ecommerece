-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2017 at 11:45 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT '0',
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(8, 'Hand Made', 'Hand Made Items', 0, 1, 1, 1, 1),
(9, 'Computers', 'Computers Item', 0, 2, 0, 0, 0),
(10, 'Cell Phones', 'Cell Phones', 0, 3, 0, 0, 0),
(11, 'Clothing', 'Clothing And Fashion', 8, 4, 0, 0, 0),
(12, 'Tools', 'Home Tools', 0, 5, 0, 0, 0),
(14, 'Blackberry', 'Blackberry Phones', 10, 2, 0, 0, 0),
(15, 'Hammers', 'Hammers Desc', 12, 1, 0, 0, 0),
(17, 'Games', 'Hand Made Games ', 12, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Comment_ID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Status` tinyint(4) NOT NULL,
  `Comment_Date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Comment_ID`, `Comment`, `Status`, `Comment_Date`, `item_id`, `user_id`) VALUES
(5, 'This Is Very Good Phone', 1, '2016-06-17', 16, 24),
(11, 'Helllo Comment', 1, '2016-06-17', 22, 30);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Status` varchar(255) NOT NULL,
  `Rating` smallint(6) NOT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT '0',
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(16, 'iPhone 6s', 'Apple iPhone 6s', '$300', '2016-05-09', 'USA', '', '2', 0, 1, 10, 24, ''),
(17, 'Magic Mouse', 'Apple Magic Mouse', '$150', '2016-05-09', 'USA', '', '1', 0, 1, 9, 24, ''),
(19, 'Game', 'Test Game For Item', '120', '2016-06-17', 'USA', '', '2', 0, 1, 9, 30, ''),
(20, 'iPhone 6s', 'iPhone 6s Very Cool Phone', '1500', '2016-06-17', 'USA', '', '2', 0, 1, 10, 30, ''),
(21, 'Hammer', 'A Very Good Iron Hammer', '30', '2016-06-17', 'China', '', '3', 0, 1, 12, 30, ''),
(22, 'Good Box', 'Nice Hand Made Box', '40', '2016-06-17', 'Egypt', '', '1', 0, 1, 8, 30, ''),
(23, 'Test Item', 'This Is Test Item To Test Approve Status', '100', '2016-06-17', 'Japan', '', '3', 0, 1, 8, 30, ''),
(24, 'Testing Item', 'Testing Description Testing Description', '120', '2016-06-17', 'Korea', '', '3', 0, 0, 10, 30, ''),
(25, 'Osama', 'Osama Osama Elzero Elzero', '100', '2016-06-17', 'Egypt', '', '3', 0, 1, 10, 30, ''),
(26, '12121212', '33333333333', '33333', '2016-06-17', '333333', '', '2', 0, 1, 11, 30, ''),
(27, 'My Item', 'My New Description', '12', '2016-06-17', 'Saudi Arabia', '', '1', 0, 1, 10, 30, 'Test, Discount, Elzero'),
(28, 'Wooden Game', 'A Good Wooden game', '100', '2016-07-25', 'Egypt', '', '1', 0, 1, 8, 30, 'Elzero, Hand, Discount, Gurantee'),
(29, 'Diablo III', 'Good Playstation 4 Game', '70', '2016-07-25', 'USA', '', '1', 0, 1, 17, 30, 'RPG, Online, Game'),
(30, 'Ys Oath In Felghana', 'A Good Ps Game', '100', '2016-07-25', 'Japan', '', '1', 0, 1, 17, 30, 'Online, RPG, Game');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT '0' COMMENT 'Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT '0' COMMENT 'User Approval',
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`) VALUES
(1, 'Osama', '6d3d186414f031caa55f7b69dd9e930a7679ef2f', 'o@elzero.info', 'Osama Mohamed', 1, 0, 1, '0000-00-00'),
(24, 'Ahmed', '265a828469ed0d20583f869863987eb4dcbc73ed', 'ahmed@ahmed.com', 'Ahmed Sameh', 1, 0, 1, '2016-05-06'),
(26, 'Sameh', '601f1889667efaebb33b8c12572835da3f027f78', 's123@s.com', 'Sameh Ahmed', 0, 0, 1, '2016-05-08'),
(27, 'Application', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'app@app.com', 'Application API', 0, 0, 1, '2016-05-11'),
(30, 'Turki', '601f1889667efaebb33b8c12572835da3f027f78', 'Turki@turki.com', '', 0, 0, 1, '2016-06-16'),
(31, 'Nada', '8cb2237d0679ca88db6464eac60da96345513964', 'nada@gmail.com', 'weiuriwurey', 0, 0, 1, '2017-04-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Comment_ID`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Comment_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=32;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
