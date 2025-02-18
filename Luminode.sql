-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- 主机： 1Panel-mysql-wy3J
-- 生成日期： 2025-02-18 15:54:09
-- 服务器版本： 8.2.0
-- PHP 版本： 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `project_7`
--

-- --------------------------------------------------------

--
-- 表的结构 `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `post_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_general_ci,
  `user_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`) VALUES
(1, 'sitename', '个人引导页示例', '2025-02-17 01:29:17'),
(2, 'signature', '光启代码，枢连万物<br>后台地址：/admin<br>账号密码：admin，12345678', '2025-02-17 21:56:06'),
(10, 'link_1_name', '链接', '2025-02-17 22:04:12'),
(11, 'link_1', 'https://example.com', '2025-02-17 22:04:12'),
(12, 'link_2_name', '链接', '2025-02-17 22:04:12'),
(13, 'link_2', 'https://example.com', '2025-02-17 22:04:12'),
(14, 'link_3_name', '链接', '2025-02-17 22:04:12'),
(15, 'link_3', 'https://example.com', '2025-02-17 22:04:12'),
(16, 'link_4_name', '链接', '2025-02-17 22:04:12'),
(17, 'link_4', 'https://example.com', '2025-02-17 22:04:12'),
(18, 'link_5_name', '链接', '2025-02-17 22:04:12'),
(19, 'link_5', 'https://example.com', '2025-02-17 22:04:12'),
(20, 'site_description', '个人引导页示例', '2025-02-17 22:28:46'),
(21, 'Copyright_date', '2024', '2025-02-17 22:32:59'),
(22, 'Copyright_name', '7123 Studio', '2025-02-17 22:35:26'),
(23, 'Copyright_Customize', '愿你的青春如花般绽放', '2025-02-17 22:37:57'),
(24, 'QQ_link', 'https://example.com/', '2025-02-17 22:48:12'),
(25, 'Mail_link', 's7123@foxmail.com', '2025-02-17 23:31:48'),
(26, 'Money_link', 'https://example.com/', '2025-02-17 23:27:15'),
(28, 'Gitee_link', 'https://example.com/', '2025-02-17 23:29:00'),
(29, 'Coolapk_link', 'https://example.com/', '2025-02-17 23:29:55'),
(30, 'avatar_link', '\\images\\Luminode.webp', '2025-02-17 23:33:26'),
(31, 'site_favicon', '\\images\\Luminode.webp', '2025-02-17 23:53:53'),
(32, 'Github_link', 'https://example.com/', '2025-02-18 01:12:27');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `last_login_ip` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `role`, `last_login_ip`, `last_login_at`) VALUES
(1, 'admin', '$2y$10$ZKvRrYR6J0eZXVFit3KUpusqKA7qSucW5rdJ7iq08/6P3kgJDRRWG', NULL, '2025-02-18 14:38:56', 'admin', NULL, NULL);

--
-- 转储表的索引
--

--
-- 表的索引 `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`);

--
-- 表的索引 `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- 表的索引 `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- 限制导出的表
--

--
-- 限制表 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`);

--
-- 限制表 `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
