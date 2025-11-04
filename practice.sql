-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- 主機: 127.0.0.1
-- 產生時間： 
-- 伺服器版本: 10.1.22-MariaDB
-- PHP 版本： 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `practice`
--

-- --------------------------------------------------------

--
-- 資料表結構 `event`
--

CREATE TABLE `event` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `event`
--

INSERT INTO `event` (`id`, `name`, `description`) VALUES
(1, '迎新茶會', '迎新茶會是專為新生設計的交流活動，讓新同學能夠認識師長與學長姐，了解資管系的學習環境與資源。活動中有輕鬆的茶點、趣味破冰遊戲，以及學長姐經驗分享，幫助新生快速融入大學生活。'),
(2, '資管一日營', '資管一日營邀請大一新生透過一整天的活動更了解資管系的課程與生活。活動內容包含常用網站介紹、校園導覽與學長姐座談、闖關遊戲，讓參加者為未來四年作好準備。');

-- --------------------------------------------------------

--
-- 資料表結構 `job`
--

CREATE TABLE `job` (
  `postid` int(11) NOT NULL,
  `company` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `job`
--

INSERT INTO `job` (`postid`, `company`, `content`, `pdate`) VALUES
(2, '樹德資訊', '誠徵雲端工程師，一年工作經驗以上', '2025-10-19'),
(3, '伯達資訊', '誠徵雲端工程師，三年工作經驗以上', '2025-10-20'),
(4, '利瑪竇資訊', '誠徵雲端工程師，三年工作經驗以上', '2025-10-25'),
(5, '輔雲科技', '誠徵雲端工程師，一年工作經驗以上', '2025-10-25'),
(6, '輔雲科技', '誠徵程式設計師，一年工作經驗以上', '2025-10-25'),
(7, '羅耀拉科技', '誠徵程式設計師，無經驗可。', '2025-10-31'),
(8, '羅耀拉科技', '誠徵雲端工程師，無經驗可。', '2025-11-05'),
(9, '樹德資訊', '誠徵專案經理，三年工作經驗以上。', '2025-11-05'),
(10, '伯達資訊', '誠徵專案經理，三年工作經驗以上。', '2025-11-05'),
(11, '羅耀拉科技', '誠徵專案經理，三年工作經驗以上。', '2025-11-07'),
(12, 'Apple', 'Programmer', '2019-08-29');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `account` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` char(1) CHARACTER SET ascii NOT NULL DEFAULT 'U',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`account`, `password`, `name`, `role`, `created_at`) VALUES
('root', '12345', '管理者', 'M', '2025-10-28 13:58:41'),
('teacher', 'password', '老師', 'T', '2025-10-21 14:03:43'),
('user1', 'pw1', '小明', 'S', '2025-10-21 14:03:43'),
('user2', 'pw2', '小華', 'S', '2025-10-21 14:03:43'),
('user3', 'pw3', '小美', 'S', '2025-10-21 14:03:43'),
('user4', 'pw4', '小強', 'S', '2025-10-21 14:03:43');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`postid`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`account`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `event`
--
ALTER TABLE `event`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用資料表 AUTO_INCREMENT `job`
--
ALTER TABLE `job`
  MODIFY `postid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
