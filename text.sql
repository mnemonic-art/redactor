-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 31 2017 г., 18:01
-- Версия сервера: 5.5.50
-- Версия PHP: 5.4.45

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `imperavi`
--

-- --------------------------------------------------------

--
-- Структура таблицы `text`
--

CREATE TABLE IF NOT EXISTS `text` (
  `id` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `text`
--

INSERT INTO `text` (`id`, `text`) VALUES
(1, '<a href="http://web4myself" target="_blank"><img src="images/krasivaya_devushka_no_ochen_mokraya_640x480.jpg" alt="GIRL" style="float: left; width: 288px; height: 214px; margin: 0px 10px 10px 0px;" width="288" height="214"></a><strong><em>Технология <span style="color: rgb(79, 97, 40);">AJAX</span></em></strong><span style="color: rgb(79, 97, 40);"></span> - это взаимодействие с сервером в фоновом режиме, без \r\nперезагрузки страницы. Ничто не останется сегодня  тайной, вы узнаете \r\nвсё. \r\n	<br><p>\r\n	Всё реализовано на аяксе, но после успешного выполнения добавления, \r\nудаления и редактирования столбца, а также добавления новой строки \r\nнеобходима перезагрузка. Аякс-запрос на редактирование осуществляется по\r\n событию blur (потерей фокуса) элементом td (ячейкой таблицы). Если в \r\nячейке поменялся текст, тогда происходит редактирование mySQL таблицы в \r\nбазе данных в фоновом режиме. Никаких кнопок "Редактировать" нажимать не\r\n нужно, всё предусмотрено специалистами\r\n</p><hr style="height: 3px !important;"><pre>&lt;form enctype="multipart/form-data"&gt;\r\n  &lt;textarea name="text" class="wysiwyg"&gt;\r\n    &lt;?= $arrData[0][''text''] ?&gt;\r\n  &lt;/textarea&gt;\r\n  &lt;input type="hidden" name="art_id" value="&lt;?= $arrData[0][''id''] ?&gt;"&gt;\r\n  &lt;input type="submit" value="Сохранить"&gt;\r\n&lt;/form&gt;\r\n</pre>');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `text`
--
ALTER TABLE `text`
  ADD PRIMARY KEY (`id`),
  ADD FULLTEXT KEY `text` (`text`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `text`
--
ALTER TABLE `text`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
