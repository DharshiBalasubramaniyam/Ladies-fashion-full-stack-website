-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 01, 2023 at 09:07 AM
-- Server version: 8.0.32
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pink_pearl`
--

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `comment` varchar(300) NOT NULL,
  `num_of_stars` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `comment`, `num_of_stars`, `date`) VALUES
(1, 4, 'Had a question about sizing, and the customer service at pink pearl was incredibly helpful and responsive. They went above and beyond to assist me. Happy customer', 5, '2023-12-01 14:10:05'),
(2, 6, 'Beautiful clothing, I\'m in love!', 5, '2023-12-01 14:10:05'),
(3, 7, 'Fast delivery! Satisfied experience of online shopping', 5, '2023-12-01 14:10:05'),
(4, 8, 'Good quality clothes. I like the simple design too.', 5, '2023-12-01 14:10:05'),
(5, 5, 'What an amazing experience! Not only were the clothes exactly what I expected but service was also excellent.', 5, '2023-12-01 14:10:05'),
(6, 3, 'Quality was so good! I love the feel of the clothes and I enjoyed the style, the clothes match the image perfectly.', 5, '2023-12-01 14:10:05');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
