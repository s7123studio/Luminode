-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-11-16 22:02:59
-- 服务器版本： 8.0.31
-- PHP 版本： 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `dbname`
--

-- --------------------------------------------------------

--
-- 表的结构 `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cover` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `view` int NOT NULL,
  `postlike` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `posts`
--

INSERT INTO `posts` (`id`, `title`, `cover`, `view`, `postlike`, `content`, `user_id`, `created_at`) VALUES
(2, '如何设计一个完美的网页', 'https://tse4-mm.cn.bing.net/th/id/OIP-C.Cw7SlTxD4KFyJYMUgXHQigHaFr?rs=1&pid=ImgDetMain', 78, 7, '如何设计一个完美的网页：内容', 1, '2021-03-01 21:30:53'),
(3, '测试1', 'https://ts1.tc.mm.bing.net/th/id/R-C.dc6392741a3289ba0b3f46a040b8c652?rik=3OApF9hplgA58Q&riu=http%3a%2f%2fwww.talkingchina.com%2fuploadfiles%2fimage%2f20170824094844_8206.jpg&ehk=SMVhTkmgan8CF%2boNvOoq73Bu4cKxGS2O4Wy8%2fILgPdU%3d&risl=&pid=ImgRaw&r=0', 80, 8, '测试测试测试', 1, '2025-03-11 00:50:12');

--
-- 转储表的索引
--

--
-- 表的索引 `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
