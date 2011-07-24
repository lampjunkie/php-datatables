SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `browsers`
--

-- --------------------------------------------------------

--
-- Table structure for table `browsers`
--

CREATE TABLE IF NOT EXISTS `browsers` (
  `id` int(11) NOT NULL auto_increment,
  `rendering_engine` varchar(100) NOT NULL,
  `browser` varchar(100) NOT NULL,
  `platform` varchar(100) NOT NULL,
  `engine_version` varchar(100) NOT NULL,
  `css_grade` varchar(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=59 ;

--
-- Dumping data for table `browsers`
--

INSERT INTO `browsers` (`id`, `rendering_engine`, `browser`, `platform`, `engine_version`, `css_grade`) VALUES
(1, 'Trident', 'Internet Explorer 4.0', 'Win 95+', '4', 'X'),
(2, 'Trident', 'Internet Explorer 5.0', 'Win 95+', '5', 'C'),
(3, 'Trident', 'Internet Explorer 5.5', 'Win 95+', '5.5', 'A'),
(4, 'Trident', 'Internet Explorer 6', 'Win 98+', '6', 'A'),
(5, 'Trident', 'Internet Explorer 7', 'Win XP SP2+', '7', 'A'),
(6, 'Trident', 'AOL browser (AOL desktop)', 'Win XP', '6', 'A'),
(7, 'Gecko', 'Firefox 1.0', 'Win 98+ / OSX.2+', '1.7', 'A'),
(8, 'Gecko', 'Firefox 1.5', 'Win 98+ / OSX.2+', '1.8', 'A'),
(9, 'Gecko', 'Firefox 2.0', 'Win 98+ / OSX.2+', '1.8', 'A'),
(10, 'Gecko', 'Firefox 3.0', 'Win 2k+ / OSX.3+', '1.9', 'A'),
(11, 'Gecko', 'Camino 1.0', 'OSX.2+', '1.8', 'A'),
(12, 'Gecko', 'Camino 1.5', 'OSX.3+', '1.8', 'A'),
(13, 'Gecko', 'Netscape 7.2', 'Win 95+ / Mac OS 8.6-9.2', '1.7', 'A'),
(14, 'Gecko', 'Netscape Browser 8', 'Win 98SE+', '1.7', 'A'),
(15, 'Gecko', 'Netscape Navigator 9', 'Win 98+ / OSX.2+', '1.8', 'A'),
(16, 'Gecko', 'Mozilla 1.0', 'Win 95+ / OSX.1+', '1', 'A'),
(17, 'Gecko', 'Mozilla 1.1', 'Win 95+ / OSX.1+', '1.1', 'A'),
(18, 'Gecko', 'Mozilla 1.2', 'Win 95+ / OSX.1+', '1.2', 'A'),
(19, 'Gecko', 'Mozilla 1.3', 'Win 95+ / OSX.1+', '1.3', 'A'),
(20, 'Gecko', 'Mozilla 1.4', 'Win 95+ / OSX.1+', '1.4', 'A'),
(21, 'Gecko', 'Mozilla 1.5', 'Win 95+ / OSX.1+', '1.5', 'A'),
(22, 'Gecko', 'Mozilla 1.6', 'Win 95+ / OSX.1+', '1.6', 'A'),
(23, 'Gecko', 'Mozilla 1.7', 'Win 98+ / OSX.1+', '1.7', 'A'),
(24, 'Gecko', 'Mozilla 1.8', 'Win 98+ / OSX.1+', '1.8', 'A'),
(25, 'Gecko', 'Seamonkey 1.1', 'Win 98+ / OSX.2+', '1.8', 'A'),
(26, 'Gecko', 'Epiphany 2.20', 'Gnome', '1.8', 'A'),
(27, 'Webkit', 'Safari 1.2', 'OSX.3', '125.5', 'A'),
(28, 'Webkit', 'Safari 1.3', 'OSX.3', '312.8', 'A'),
(29, 'Webkit', 'Safari 2.0', 'OSX.4+', '419.3', 'A'),
(30, 'Webkit', 'Safari 3.0', 'OSX.4+', '522.1', 'A'),
(31, 'Webkit', 'Google Chrome 1.0', 'Win XP+', '525', 'A'),
(32, 'Webkit', 'OmniWeb 5.5', 'OSX.4+', '420', 'A'),
(33, 'Webkit', 'iPod Touch / iPhone', 'iPod', '420.1', 'A'),
(34, 'Webkit', 'S60', 'S60', '413', 'A'),
(35, 'Presto', 'Opera 7.0', 'Win 95+ / OSX.1+', '-', 'A'),
(36, 'Presto', 'Opera 7.5', 'Win 95+ / OSX.2+', '-', 'A'),
(37, 'Presto', 'Opera 8.0', 'Win 95+ / OSX.2+', '-', 'A'),
(38, 'Presto', 'Opera 8.5', 'Win 95+ / OSX.2+', '-', 'A'),
(39, 'Presto', 'Opera 9.0', 'Win 95+ / OSX.3+', '-', 'A'),
(40, 'Presto', 'Opera 9.2', 'Win 88+ / OSX.3+', '-', 'A'),
(41, 'Presto', 'Opera 9.5', 'Win 88+ / OSX.3+', '-', 'A'),
(42, 'Presto', 'Opera for Wii', 'Wii', '-', 'A'),
(43, 'Presto', 'Nokia N800', 'N800', '-', 'A'),
(44, 'Presto', 'Nintendo DS browser', 'Nintendo DS', '8.5', 'C/A1'),
(45, 'KHTML', 'Konqureror 3.1', 'KDE 3.1', '3.1', 'C'),
(46, 'KHTML', 'Konqureror 3.3', 'KDE 3.3', '3.3', 'A'),
(47, 'KHTML', 'Konqureror 3.5', 'KDE 3.5', '3.5', 'A'),
(48, 'Tasman', 'Internet Explorer 4.5', 'Mac OS 8-9', '-', 'X'),
(49, 'Tasman', 'Internet Explorer 5.1', 'Mac OS 7.6-9', '1', 'C'),
(50, 'Tasman', 'Internet Explorer 5.2', 'Mac OS 8-X', '1', 'C'),
(51, 'Misc', 'NetFront 3.1', 'Embedded devices', '-', 'C'),
(52, 'Misc', 'NetFront 3.4', 'Embedded devices', '-', 'A'),
(53, 'Misc', 'Dillo 0.8', 'Embedded devices', '-', 'X'),
(54, 'Misc', 'Links', 'Text only', '-', 'X'),
(55, 'Misc', 'Lynx', 'Text only', '-', 'X'),
(56, 'Misc', 'IE Mobile', 'Windows Mobile 6', '-', 'C'),
(57, 'Misc', 'PSP browser', 'PSP', '-', 'C'),
(58, 'Other browsers', 'All others', '-', '-', 'U');
