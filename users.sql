-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2022 at 03:57 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `users`
--
CREATE DATABASE IF NOT EXISTS `users` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `users`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `topic_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `comment` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `topic_id`, `user_id`, `comment`, `date_created`, `date_updated`) VALUES
(1, 3, 1436675631, 'my first comment', '2022-11-22 18:38:01', '2022-11-22 18:38:01'),
(3, 3, 1436675631, 'new comment', '2022-11-22 19:04:42', '2022-11-22 19:04:42'),
(4, 3, 773442269, 'my comment&lt;p&gt;&lt;br&gt;&lt;/p&gt;', '2022-11-22 19:06:10', '2022-11-22 19:06:10'),
(5, 4, 773442269, 'this is a comment', '2022-11-22 19:14:08', '2022-11-22 19:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `forum_views`
--

CREATE TABLE IF NOT EXISTS `forum_views` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `topic_id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `forum_views`
--

INSERT INTO `forum_views` (`id`, `topic_id`, `user_id`) VALUES
(1, 3, 773442269);

-- --------------------------------------------------------

--
-- Table structure for table `personalinfo`
--

CREATE TABLE IF NOT EXISTS `personalinfo` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=1597710297 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `personalinfo`
--

INSERT INTO `personalinfo` (`uid`, `cid`, `fullName`, `userName`, `email`, `pwd`, `status`) VALUES
(336719793, 1230092949, 'Muhammad Zia Ur Rehman', 'muhammad', 'muhamad@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'Active'),
(773442269, 1386479395, 'testaccount', 'testaccount', 'testaccount@mail.com', '4acb4bc224acbbe3c2bfdcaa39a4324e', 'Active'),
(883689055, 551217612, 'Xia', 'xia', 'xia@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'Active'),
(1360853074, 1652577795, 'Hamza', 'hamza', 'hamza@gmail.com', 'c20ad4d76fe97759aa27a0c99bff6710', 'Active'),
(1436675631, 1319326519, 'developeraccount', 'devaccount', 'devaccount@gmail.com', '4acb4bc224acbbe3c2bfdcaa39a4324e', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `regusers`
--

CREATE TABLE IF NOT EXISTS `regusers` (
  `userNo` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `fullName` varchar(255) NOT NULL,
  PRIMARY KEY (`userNo`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `regusers`
--

INSERT INTO `regusers` (`userNo`, `uid`, `userName`, `fullName`) VALUES
(21, 1360853074, 'hamza', 'Hamza'),
(22, 336719793, 'muhammad', 'Muhammad Zia Ur Rehman'),
(23, 883689055, 'xia', 'Xia'),
(24, 1436675631, 'devaccount', 'developeraccount'),
(25, 773442269, 'testaccount', 'testaccount');

-- --------------------------------------------------------

--
-- Table structure for table `signedinusers`
--

CREATE TABLE IF NOT EXISTS `signedinusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sessionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `signedinusers`
--

INSERT INTO `signedinusers` (`id`, `uid`, `sessionId`) VALUES
(15, 1360853074, 1652577795),
(16, 336719793, 1230092949),
(17, 883689055, 551217612),
(18, 1436675631, 1319326519),
(19, 773442269, 1386479395);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `category_ids` text NOT NULL,
  `title` varchar(250) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(30) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`id`, `category_ids`, `title`, `content`, `user_id`, `date_created`) VALUES
(7, '', 'What are the differences between git pull and git fetch?', '&lt;span style=&quot;color: rgb(35, 38, 41); font-family: -apple-system, BlinkMacSystemFont, &amp;quot;Segoe UI Adjusted&amp;quot;, &amp;quot;Segoe UI&amp;quot;, &amp;quot;Liberation Sans&amp;quot;, sans-serif; font-size: 15px; text-align: left;&quot;&gt;What are the differences between&amp;nbsp;&lt;/span&gt;&lt;a href=&quot;https://git-scm.com/docs/git-pull&quot; rel=&quot;noreferrer&quot; style=&quot;border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &amp;quot;Segoe UI Adjusted&amp;quot;, &amp;quot;Segoe UI&amp;quot;, &amp;quot;Liberation Sans&amp;quot;, sans-serif; font-size: 15px; vertical-align: baseline; box-sizing: inherit; color: var(--theme-link-color); cursor: pointer; user-select: auto; text-align: left;&quot;&gt;&lt;code style=&quot;padding: var(--su2) var(--su4); border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: var(--ff-mono); font-size: var(--fs-body1); vertical-align: baseline; box-sizing: inherit; background-color: var(--black-075); white-space: pre-wrap; color: var(--theme-link-color); border-radius: var(--br-sm);&quot;&gt;git pull&lt;/code&gt;&lt;/a&gt;&lt;span style=&quot;color: rgb(35, 38, 41); font-family: -apple-system, BlinkMacSystemFont, &amp;quot;Segoe UI Adjusted&amp;quot;, &amp;quot;Segoe UI&amp;quot;, &amp;quot;Liberation Sans&amp;quot;, sans-serif; font-size: 15px; text-align: left;&quot;&gt;&amp;nbsp;and&amp;nbsp;&lt;/span&gt;&lt;a href=&quot;https://git-scm.com/docs/git-fetch&quot; rel=&quot;noreferrer&quot; style=&quot;border: 0px; font-variant-numeric: inherit; font-variant-east-asian: inherit; font-stretch: inherit; line-height: inherit; font-family: -apple-system, BlinkMacSystemFont, &amp;quot;Segoe UI Adjusted&amp;quot;, &amp;quot;Segoe UI&amp;quot;, &amp;quot;Liberation Sans&amp;quot;, sans-serif; font-size: 15px; vertical-align: baseline; box-sizing: inherit; color: var(--theme-link-color); cursor: pointer; user-select: auto; text-align: left;&quot;&gt;&lt;code style=&quot;padding: var(--su2) var(--su4); border: 0px; font-style: inherit; font-variant: inherit; font-weight: inherit; font-stretch: inherit; line-height: inherit; font-family: var(--ff-mono); font-size: var(--fs-body1); vertical-align: baseline; box-sizing: inherit; background-color: var(--black-075); white-space: pre-wrap; color: var(--theme-link-color); border-radius: var(--br-sm);&quot;&gt;git fetch&lt;/code&gt;&lt;/a&gt;&lt;span style=&quot;color: rgb(35, 38, 41); font-family: -apple-system, BlinkMacSystemFont, &amp;quot;Segoe UI Adjusted&amp;quot;, &amp;quot;Segoe UI&amp;quot;, &amp;quot;Liberation Sans&amp;quot;, sans-serif; font-size: 15px; text-align: left;&quot;&gt;?&lt;/span&gt;', 773442269, '2022-11-22 19:33:08');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `regusers`
--
ALTER TABLE `regusers`
  ADD CONSTRAINT `regusers_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `personalinfo` (`uid`);

--
-- Constraints for table `signedinusers`
--
ALTER TABLE `signedinusers`
  ADD CONSTRAINT `signedinusers_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `personalinfo` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
