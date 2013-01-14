-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- Хост: znachok.mysql.ukraine.com.ua
-- Время создания: Янв 14 2013 г., 11:47
-- Версия сервера: 5.1.61-cll
-- Версия PHP: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `znachok_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `dreamnet`
--

CREATE TABLE IF NOT EXISTS `dreamnet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) COLLATE cp1251_ukrainian_ci NOT NULL,
  `ammount` float NOT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `endtime` datetime NOT NULL,
  `com` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci AUTO_INCREMENT=69 ;

--
-- Дамп данных таблицы `dreamnet`
--

INSERT INTO `dreamnet` (`id`, `user`, `ammount`, `date`, `status`, `endtime`, `com`) VALUES
(54, '4444444444', 100, '2013-01-11 14:42:46', 100, '2013-01-24 14:42:46', 'WiFi:yakovlevskoe'),
(55, '1111111111', 0, '2013-01-11 17:09:55', 0, '2013-01-11 00:00:00', 'WiFi:yakovlevskoe'),
(56, '11111111111', 0, '2013-01-11 17:11:53', 0, '0000-00-00 00:00:00', 'WiFi:kalininec'),
(57, '111111111111', 0, '2013-01-11 17:14:47', 0, '2013-01-11 17:41:35', 'WiFi:kalininec'),
(58, '9265369369', 50, '2013-01-14 08:17:14', 0, '2013-01-01 09:17:13', 'WiFi:yakovlevskoe'),
(60, '9250017893', 10, '2013-01-14 11:56:27', 0, '2013-01-01 11:56:27', 'WiFi:yakovlevskoe'),
(61, '9250017893', 0, '2013-01-14 09:54:55', 0, '2013-01-01 10:09:55', 'WiFi:kalininec'),
(63, '9250017893', 10, '2013-01-14 13:27:02', 0, '2013-01-01 13:27:02', 'WiFi:yakovlevskoe'),
(64, '444444444444', 0, '2013-01-14 10:24:00', 0, '2013-01-01 10:39:00', 'WiFi:kalininec'),
(65, '11111111111', 10, '2013-01-14 10:25:33', 100, '2013-01-14 11:25:32', 'WiFi:kalininec'),
(66, '92500178983', 10, '2013-01-14 10:29:16', 100, '2013-01-14 11:29:16', 'WiFi:yakovlevskoe'),
(67, '9250017893', 10, '2013-01-14 10:32:24', 0, '2013-01-01 11:32:22', 'WiFi:yakovlevskoe'),
(68, '9250017893', 0, '2013-01-14 10:36:24', 0, '2013-01-14 10:41:17', 'WiFi:yakovlevskoe');

-- --------------------------------------------------------

--
-- Структура таблицы `dreamnet_log`
--

CREATE TABLE IF NOT EXISTS `dreamnet_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `user` varchar(128) COLLATE cp1251_ukrainian_ci NOT NULL,
  `tsn` varchar(128) COLLATE cp1251_ukrainian_ci DEFAULT NULL,
  `type` varchar(128) COLLATE cp1251_ukrainian_ci NOT NULL,
  `status` varchar(64) COLLATE cp1251_ukrainian_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci AUTO_INCREMENT=22 ;

--
-- Дамп данных таблицы `dreamnet_log`
--

INSERT INTO `dreamnet_log` (`id`, `date`, `user`, `tsn`, `type`, `status`) VALUES
(1, '2013-01-14 10:48:49', '9250017893', NULL, 'delete', 'OK'),
(2, '2013-01-14 10:49:58', '9250017893', NULL, 'delete', 'OK'),
(3, '2013-01-14 10:51:42', '9250017893', NULL, 'add', 'OK'),
(4, '2013-01-14 10:54:03', '9250017893', NULL, 'add', 'FAILED'),
(5, '2013-01-14 10:54:35', '9250017893', NULL, 'delete', 'OK'),
(6, '2013-01-14 10:54:56', '9250017893', NULL, 'add', 'OK'),
(7, '2013-01-14 10:55:25', '9250017893', NULL, 'delete', 'OK'),
(8, '2013-01-14 11:08:07', '9250017893', NULL, 'add', 'OK'),
(9, '2013-01-14 11:09:05', '9250017893', NULL, 'delete', 'OK'),
(10, '2013-01-14 11:10:30', '9250017893', NULL, 'add', 'OK'),
(11, '2013-01-14 11:13:35', '9250017893', NULL, 'delete', 'OK'),
(12, '2013-01-14 11:24:01', '444444444444', NULL, 'add', 'OK'),
(13, '2013-01-14 11:24:23', '444444444444', NULL, 'delete', 'OK'),
(14, '2013-01-14 11:25:33', '11111111111', NULL, 'add', 'OK'),
(15, '2013-01-14 11:27:50', '11111111111', NULL, 'add', 'FAILED'),
(16, '2013-01-14 11:29:16', '92500178983', NULL, 'add', 'OK'),
(17, '2013-01-14 11:32:24', '9250017893', NULL, 'add', 'OK'),
(18, '2013-01-14 11:33:09', '9250017893', NULL, 'add', 'FAILED'),
(19, '2013-01-14 11:35:21', '9250017893', NULL, 'delete', 'OK'),
(20, '2013-01-14 11:36:24', '9250017893', NULL, 'add', 'OK'),
(21, '2013-01-14 11:40:41', '9250017893', NULL, 'delete', 'OK');

-- --------------------------------------------------------

--
-- Структура таблицы `testaccounts`
--

CREATE TABLE IF NOT EXISTS `testaccounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(15) COLLATE cp1251_ukrainian_ci NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci AUTO_INCREMENT=2 ;

--
-- Дамп данных таблицы `testaccounts`
--

INSERT INTO `testaccounts` (`id`, `phone`, `date`) VALUES
(1, '9250017893', 2013);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
