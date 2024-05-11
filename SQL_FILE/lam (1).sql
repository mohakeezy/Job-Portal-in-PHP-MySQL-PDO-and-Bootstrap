-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2024 at 09:58 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(3) NOT NULL,
  `adminname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `mypassword` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `adminname`, `email`, `mypassword`, `created_at`) VALUES
(6, 'moo', 'moo@gmail.com', '$2y$10$HgE5plXMi8Pr2qdoGv2pqOKiHiGhHmHng30mOICKFftYbIL2Fmzjq', '2024-03-20 22:12:59'),
(7, 'ahmed', 'ahmed@gmail.com', '$2y$10$QV7OFf9YC/jHIeyQybC4GOmVS3rv.1p7SXgIrX4y92gDPjeoDAGN6', '2024-03-25 11:01:27'),
(9, 'moo1', 'moo@gmail.com', '$2y$10$n18jDahjqHrKZWdXeZbfwOUqvHAn3jnTFx0ZBo5WMOUFFnYFVsBeO', '2024-03-25 12:58:42'),
(12, 'b ', 'b@gmail.com', '$2y$10$e2gn57zxVvL6lYF6oiqecOBO6mGiFqmBIcstXfzP6MmZapi.6q4CO', '2024-04-21 05:54:13');

-- --------------------------------------------------------

--
-- Table structure for table `arts`
--

CREATE TABLE `arts` (
  `art_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `artist_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arts`
--

INSERT INTO `arts` (`art_id`, `title`, `description`, `artist_id`, `category_id`, `image`, `created_at`, `updated_at`, `status`) VALUES
(63, 'hh', 'jjkj', 6, 16, 'abstract-paint-background-with-multi-colored-watercolor-painting-generated-by-ai.jpg', '2024-05-04 08:57:58', '2024-05-04 08:57:58', 1),
(64, 'Polotically', 'Waa sawir Ama Shabe Qaab siyaasad Ah', 6, 17, 'long-tailed-grass-finch.jpg', '2024-05-04 09:01:20', '2024-05-04 09:01:20', 1),
(73, 'x', 'd', NULL, 16, 'abstract-paint-background-with-multi-colored-watercolor-painting-generated-by-ai.jpg', '2024-05-08 10:48:46', '2024-05-08 10:48:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`, `created_at`, `updated_at`, `image`) VALUES
(16, 'Funny Arts', 'This is Categories of Funny Arts', '2024-04-23 06:56:45', '2024-05-03 06:58:17', '../images/10115821.jpg'),
(17, 'Political Cartoons', 'This is Category of Political Cartoons', '2024-04-23 06:57:50', '2024-04-23 06:57:50', '../UploadCategories/O9FG400.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `art_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `art_id`, `created_at`) VALUES
(1, 8, 58, '2024-04-30 09:48:15'),
(2, 8, 54, '2024-04-30 09:55:17'),
(3, 8, 56, '2024-04-30 10:16:33'),
(4, 6, 59, '2024-05-02 15:40:44'),
(5, 6, 58, '2024-05-02 15:41:01'),
(6, 6, 56, '2024-05-02 15:45:34'),
(7, 8, 62, '2024-05-04 08:54:02'),
(8, 27, 65, '2024-05-07 15:37:40'),
(9, 27, 63, '2024-05-07 15:45:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mypassword` varchar(255) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `type` varchar(200) DEFAULT 'User',
  `bio` text DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` varchar(255) DEFAULT NULL,
  `expDate` datetime DEFAULT NULL,
  `code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `mypassword`, `img`, `type`, `bio`, `facebook`, `twitter`, `linkedin`, `created_at`, `token`, `expDate`, `code`) VALUES
(1, 'mohamed abdifitah ali', 'ko', 'm@m.com', '$2y$10$/UEXKd0XrOOepoGmjdV6buzmgb2YqOvYxPSWisrh/RapeIuofNQKC', 'uim.jpg', 'Artist', NULL, NULL, NULL, NULL, '2024-03-24 09:47:10', NULL, NULL, ''),
(2, 'Naasir abdi ', 'nasir', 'nasir@n.com', '$2y$10$kHdtzHV/yqLkMFcir4I8H.5f/8RpkRybqMhORmwtdiezIbHKtbNNa', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-03-24 09:50:10', NULL, NULL, ''),
(4, 'vaalo', 'vaal', 'vaalo@v.com', '$2y$10$DA8L6S7k.iqR6Z4rJZ4chu3RX..TGlk2Y6IE69FMC6Fw7hGsEhDYS', 'uim.jpg', 'Artist', 'Halkaan waxaa laga arki karaa Bio ga Userka.', NULL, NULL, NULL, '2024-03-24 10:08:33', NULL, NULL, ''),
(6, 'ahmed nor mohamed', 'ahmed', 'ahmed@gmail.com', '$2y$10$umJrj04kjJ57Z9ipVwEh4..40hxZQWftlOkqTadaGX8GHcb9W4pGu', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-03-25 10:58:38', NULL, NULL, ''),
(7, 'said ali', 'said', 'said@gmail.com', '$2y$10$dkB.xSj0qf/ELWVECqGlBeCYa4Kmsaj/TXahRRTgxWT38vZiR1BcO', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-03-25 12:49:46', NULL, NULL, ''),
(8, 'farhaan abdullahi ali', 'farhaan', 'farhaan@gmail.com', '$2y$10$lVA2xPCBOYBMX7ASioljpuHgSuONPl2v4IA9kMy2M5cma/FnlOnxq', 'uim.jpg', 'Artist', '', '', '', '', '2024-04-18 04:23:26', NULL, NULL, ''),
(9, 'kaamil hasan', 'kaamil', 'kaamil@gmail.com', '$2y$10$3yF1WWdhGJGxGxOzghbqgepWKB4RsXs4GjAdbl1HmvMSt.e6GZ24G', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-21 06:29:44', NULL, NULL, ''),
(10, 'jaamac cali', 'jaamac', 'jaamac@gmail.com', '$2y$10$cgHz1UGokGNk0dRHMTvp1uAb0X05M1INt/GtkLQUsj8l7FlRgchWW', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-21 06:30:56', NULL, NULL, ''),
(11, 'paana abdi', 'paana ', 'paana@gmail.com', '$2y$10$AH.1Mu.c94raDbsEJhgSS.R0nOeS7xPOsB96zvhlef3zJtu/8zBk2', 'uim.jpg', 'Artist', 'This Is My Bio Informations The following sentences provide examples of the concreteness, evocativeness and plausibility of good descriptive writing. Her last smile to me wasn’t a sunset. It was an eclipse, the last eclipse, noon dying away to darkness', '', '', '', '2024-04-23 12:50:44', NULL, NULL, ''),
(12, 'User', 'User', 'User@gmail.com', '$2y$10$2M7eCi32BYJKMNYpppMoFumfY1ZxlhTp3bQjFwBchudtnUTNsfLy6', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-28 07:20:05', NULL, NULL, ''),
(13, 'User1', 'User1', 'User1@gmail.com', '$2y$10$bG/5eBNnJkIGnzx4897p5O.uTKLGeQo3g/PAQec1huSDObkyGyPGe', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-28 07:22:15', NULL, NULL, ''),
(14, 'a', 'a', 'a@gmail.com', '$2y$10$Musfdeex78zXUHTz4AdSeuus/LFVNC7CP/RNSwOP7ZqxDCJZYHp0q', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-28 07:23:35', NULL, NULL, ''),
(15, 's', 's', 's@gmail.com', '$2y$10$wKCpX.9Pt00XkQOOYp.d2Ooe5j8.95WubpzQpQ9KG5E1xYmnaFIeW', 'uim.jpg', 'User', NULL, NULL, NULL, NULL, '2024-04-28 07:29:58', NULL, NULL, ''),
(16, 'p', 'p', 'p@gmail.com', '$2y$10$Kpe.2TK82OyM9GR/iloh9.wWQCLNdPvcQtfGWdY1.lEgmDAeTaENK', 'm.png', 'Artist', NULL, NULL, NULL, NULL, '2024-04-28 07:34:07', NULL, NULL, ''),
(17, 'oo', 'oo', 'oo@gmail.com', '$2y$10$l6f0tec7iDh7EIm1BUtDuOsP7gIiLMGXHS3DY3rVxgOkRVdz8.NUy', 'm.png', 'Artist', NULL, NULL, NULL, NULL, '2024-04-28 07:38:02', NULL, NULL, ''),
(18, 'k', 'k', 'k@gmail.com', '$2y$10$Wbne8A6sSaejn3f6R9.fmu07Y.K.bkQFq/BGj9EAvU3ydcVYSoHc6', 'm.png', 'Artist', NULL, NULL, NULL, NULL, '2024-04-28 07:41:39', NULL, NULL, ''),
(19, 'q', 'q', 'q@q.com', '$2y$10$vp2oOdJr7xyQpyoN.fR9dujswjH0jQXkV8/bbwYFW6g4F5WDhMPAS', 'm.png', 'Artist', NULL, NULL, NULL, NULL, '2024-04-28 07:43:09', NULL, NULL, ''),
(24, 'oooooooooooo hasan', 'ii', 'ooooooo@gmail.com', '$2y$10$DpRVAq4hnt7gBGbVO9SOwuN5Lq3.K8UJfTQCw5R3rLqzOwSgi38sa', 'm.png', 'User', NULL, NULL, NULL, NULL, '2024-05-02 15:05:50', NULL, NULL, ''),
(25, 'qaa', 'qaa', 'qaa@gmail.com', '$2y$10$YsOnNbSPg8wJN.0wSgcAYuJF1CnqRjbEjm/Xmu2OqBZ/aBsQf.2qu', 'm.png', 'Artist', NULL, NULL, NULL, NULL, '2024-05-02 15:06:55', NULL, NULL, ''),
(28, 'oop11', 'oop', 'oop11@gmail.com', '$2y$10$v0ShBQwnwkvGr.et5XVQ3OPp11tL0KpGsdf/OeaPr1AFRnKRk3ED2', 'm.png', 'User', NULL, NULL, NULL, NULL, '2024-05-09 07:49:16', NULL, NULL, ''),
(30, 'som', 'som', 'somartisans@gamail.com', '$2y$10$YgUwk2KtSz8S3tPJVlQ0e.Anjr03R.cApl.SWhq55.B9m3c8eTXWC', 'm.png', 'User', NULL, NULL, NULL, NULL, '2024-05-09 09:48:38', NULL, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arts`
--
ALTER TABLE `arts`
  ADD PRIMARY KEY (`art_id`),
  ADD KEY `artist_id` (`artist_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `code` (`code`),
  ADD KEY `code_2` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `arts`
--
ALTER TABLE `arts`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arts`
--
ALTER TABLE `arts`
  ADD CONSTRAINT `arts_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `arts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
